<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ControllerCatalogUsedStaff extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/used_staff');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/used_staff');

		$this->getList();
	}
        
        public function add() {
		$this->load->language('catalog/used_staff');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/used_staff');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_used_staff->addUsedStaff($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}
        
        public function edit() {
		$this->load->language('catalog/used_staff');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/used_staff');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                    //$this->log->write($this->request->post);
			$this->model_catalog_used_staff->editUsedStaff($this->request->get['used_product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}
        
        public function delete() {
		$this->load->language('catalog/used_staff');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/used_staff');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $used_product_id) {
				$this->model_catalog_used_staff->deleteUsedStaff($used_product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}
        
        protected function getList() {
			
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'upd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/used_staff/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/used_staff/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['used_staffs'] = array();

		$filter_data = array(
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_used_staff->getTotalUsedStaffs($filter_data);

		$results = $this->model_catalog_used_staff->getUsedStaffs($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$data['used_staffs'][] = array(
				'used_product_id' => $result['used_product_id'],
				'image'           => $image,
                'customer_name'   =>$result['firstname'] . ' ' . $result['lastname'],
                'customer_phone'  => $result['telephone'],
                'email'           => $result['email'],
				'name'            => $result['name'],
				'model'           => $result['model'],
				'price'           => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'quantity'        => $result['quantity'],
				'status'          => $result['staff_status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'            => $this->url->link('catalog/used_staff/edit', 'token=' . $this->session->data['token'] . '&used_product_id=' . $result['used_product_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_image'] = $this->language->get('entry_image');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		
		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . '&sort=upd.name' . $url, true);
		$data['sort_model'] = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . '&sort=up.model' . $url, true);
		$data['sort_price'] = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . '&sort=up.price' . $url, true);
		$data['sort_quantity'] = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . '&sort=up.quantity' . $url, true);
		$data['sort_status'] = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . '&sort=up.status' . $url, true);
		$data['sort_order'] = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . '&sort=up.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/used_staff_list', $data));
	}
        
    protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		$data['entry_customer_name'] = $this->language->get('entry_customer_name');
		$data['entry_customer_email'] = $this->language->get('entry_customer_email');
		$data['entry_customer_phone'] = $this->language->get('entry_customer_phone');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_period'] = $this->language->get('entry_period');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_additional_image'] = $this->language->get('entry_additional_image');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_download'] = $this->language->get('entry_download');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_required'] = $this->language->get('entry_required');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_layout'] = $this->language->get('entry_layout');

		
		$data['help_stock_status'] = $this->language->get('help_stock_status');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_download'] = $this->language->get('help_download');
		$data['help_tag'] = $this->language->get('help_tag');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_links'] = $this->language->get('tab_links');
			
		$data['text_form'] = !isset($this->request->get['used_product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['quantity'])) {
			$data['error_quantity'] = $this->error['quantity'];
		} else {
			$data['error_quantity'] = array();
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['price'])) {
			$data['error_price'] = $this->error['price'];
		} else {
			$data['error_price'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['used_product_id'])) {
			$data['action'] = $this->url->link('catalog/used_staff/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/used_staff/edit', 'token=' . $this->session->data['token'] . '&used_product_id=' . $this->request->get['used_product_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/used_staff', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['used_product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_used_staff->getUsedStaff($this->request->get['used_product_id']);
                        $this->log->write($product_info);                        
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['used_product_description'])) {
			$data['used_product_description'] = $this->request->post['used_product_description'];
		} elseif (isset($this->request->get['used_product_id'])) {
			$data['used_product_description'] = $this->model_catalog_used_staff->getUsedStaffDescriptions($this->request->get['used_product_id']);
		} else {
			$data['used_product_description'] = array();
		}

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$data['price'] = $product_info['price'];
		} else {
			$data['price'] = '';
		}


		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$data['quantity'] = $product_info['quantity'];
		} else {
			$data['quantity'] = 1;
		}

		if (isset($this->request->post['period'])) {
			$data['period'] = $this->request->post['period'];
		} elseif (!empty($product_info)) {
			$data['period'] = $product_info['period'];
		} else {
			$data['period'] = 3;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}
                
        if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$data['status'] = $product_info['staff_status'];
		} else {
			$data['status'] = true;
		}
                
        if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($product_info)) {
			$data['customer_id'] = $product_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}
                
        if (isset($this->request->post['firstname']) && isset($this->request->post['lastname'])) {
			$data['customer_name'] = $this->request->post['firstname'] . ' ' . $this->request->post['lastname'];
		} elseif (!empty($product_info)) {
			$data['customer_name'] = $product_info['firstname'] . ' ' . $product_info['lastname'];
		} else {
			$data['customer_name'] = '';
		}
                
        if (isset($this->request->post['telephone'])) {
			$data['customer_phone'] = $this->request->post['telephone'];
		} elseif (!empty($product_info)) {
			$data['customer_phone'] = $product_info['telephone'];
		} else {
			$data['customer_phone'] = '';
		}
                
        if (isset($this->request->post['email'])) {
			$data['customer_email'] = $this->request->post['email'];
		} elseif (!empty($product_info)) {
			$data['customer_email'] = $product_info['email'];
		} else {
			$data['customer_email'] = '';
		}

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['used_product_category'])) {
			$categories = $this->request->post['used_product_category'];
		} elseif (isset($this->request->get['used_product_id'])) {
			$categories = $this->model_catalog_used_staff->getUsedStaffCategories($this->request->get['used_product_id']);
		} else {
			$categories = array();
		}

		$data['used_product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['used_product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		
		// Image
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		// Images
		if (isset($this->request->post['used_product_image'])) {
			$product_images = $this->request->post['used_product_image'];
		} elseif (isset($this->request->get['used_product_id'])) {
			$product_images = $this->model_catalog_used_staff->getUsedStaffImages($this->request->get['used_product_id']);
		} else {
			$product_images = array();
		}

		$data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
				$thumb = $product_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/used_staff_form', $data));
	}
        
        protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/used_staff')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['used_product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}
		
		if ((utf8_strlen($this->request->post['price']) < 1) || (utf8_strlen($this->request->post['price']) > 10)) {
			$this->error['price'] = $this->language->get('error_price');
		}
		
		if ((utf8_strlen($this->request->post['quantity']) < 1) || (utf8_strlen($this->request->post['quantity']) > 4)) {
			$this->error['quantity'] = $this->language->get('error_quantity');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
        
        protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/used_staff')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
