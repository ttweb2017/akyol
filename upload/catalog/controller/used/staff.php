<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerUsedStaff
 *
 * @author User
 */
class ControllerUsedStaff extends Controller{
    private $error = array();
    public function index() {
		$this->load->language('used/staff');

		$this->load->model('catalog/category');

		$this->load->model('catalog/used_staff');

		$this->load->model('tool/image');
                
		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'up.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('used/staff', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');
			$data['text_refine'] = $this->language->get('text_refine');
			
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');
			$data['button_add'] = $this->language->get('button_add');

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('used/staff', 'path=' . $this->request->get['path'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			//$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_used_staff->getTotalUsedStaffs($filter_data) . ')' : ''),
					'href' => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}

			$data['used_staffs'] = array();

			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_used_staff->getTotalUsedStaffs($filter_data);

			$results = $this->model_catalog_used_staff->getUsedStaffs($filter_data);
			

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				$data['used_staffs'][] = array(
					'used_product_id'  => $result['used_product_id'],
					'thumb'            => $image,
                    'customer_name'    =>$result['firstname'],
                    'customer_phone'   => $result['telephone'],
                    'email'            => $result['email'],
					'name'             => $result['name'],
					'description'      => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'            => $price,
					'href'             => $this->url->link('product/used_staff', 'path=' . $this->request->get['path'] . '&used_product_id=' . $result['used_product_id'] . $url)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'up.sort_order-ASC',
				'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '&sort=up.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'upd.name-ASC',
				'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '&sort=upd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'upd.name-DESC',
				'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '&sort=upd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'up.price-ASC',
				'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '&sort=up.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'up.price-DESC',
				'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '&sort=up.price&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'up.model-ASC',
				'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '&sort=up.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'up.model-DESC',
				'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . '&sort=up.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('used/staff', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('used/staff', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('used/staff', 'path=' . $category_info['category_id']), 'canonical');
			} else {
				$this->document->addLink($this->url->link('used/staff', 'path=' . $category_info['category_id'] . '&page='. $page), 'canonical');
			}
			
			if ($page > 1) {
			    $this->document->addLink($this->url->link('used/staff', 'path=' . $category_info['category_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('used/staff', 'path=' . $category_info['category_id'] . '&page='. ($page + 1)), 'next');
			}
                        
            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            $data['add'] = $this->url->link('used/staff/add');
                        
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('used/staff', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('used/staff', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
        
        public function add() {
		$this->load->language('used/staff');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/used_staff');
                
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() && $this->customer->isLogged()) {
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

			$this->response->redirect($this->url->link('used/staff&path=59'));
		}
                
		$this->getForm();
	}
        
    protected function getForm() {
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
		
		$data['heading_title'] = $this->language->get('name');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_image'] = $this->language->get('tab_image');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_image_add'] = $this->language->get('button_image_add');
		
		$data['help_stock_status'] = $this->language->get('help_stock_status');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_download'] = $this->language->get('help_download');
		$data['help_tag'] = $this->language->get('help_tag');
		
		$data['entry_customer'] = $this->language->get('entry_customer');
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
		
		//check if customer is logged!
		if (!$this->customer->isLogged()) {
			$data['error_customer'] = $this->language->get('error_customer');
		} else {
			$data['error_customer'] = '';
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
			'href' => $this->url->link('common/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/used_staff')
		);

		if (!isset($this->request->get['used_product_id'])) {
			$data['action'] = $this->url->link('used/staff/add');
		} else {
			$data['action'] = $this->url->link('catalog/used_staff/edit', 'used_product_id=' . $this->request->get['used_product_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('used/staff', 'path=59');

		if (isset($this->request->get['used_product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_used_staff->getUsedStaff($this->request->get['used_product_id']);
		}

		//$data['token'] = $this->session->data['token'];

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
                
        if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($product_info)) {
			$data['customer_id'] = $product_info['customer_id'];
		} else {
			$data['customer_id'] = $this->customer->getId();
		}
                
        if (isset($this->request->post['firstname']) && isset($this->request->post['lastname'])) {
			$data['customer_name'] = $this->request->post['firstname'] . ' ' . $this->request->post['lastname'];
		} elseif (!empty($product_info)) {
			$data['customer_name'] = $product_info['firstname'] . ' ' . $product_info['lastname'];
		} else {
			$data['customer_name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
		}
                
        if (isset($this->request->post['telephone'])) {
			$data['customer_phone'] = $this->request->post['telephone'];
		} elseif (!empty($product_info)) {
			$data['customer_phone'] = $product_info['telephone'];
		} else {
			$data['customer_phone'] = $this->customer->getTelephone();
		}
                
        if (isset($this->request->post['email'])) {
			$data['customer_email'] = $this->request->post['email'];
		} elseif (!empty($product_info)) {
			$data['customer_email'] = $product_info['email'];
		} else {
			$data['customer_email'] = $this->customer->getEmail();
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$data['status'] = $product_info['status'];
		} else {
			$data['status'] = true;
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

        $category_info = $this->model_catalog_category->getCategory(59);
        if ($category_info) {
				$data['used_product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => $category_info['name']
				);
		}
          
		/*foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['used_product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => $category_info['name']
				);
			}
		}*/

		
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
		 
  
		$this->document->addScript('catalog/view/javascript/codemirror/lib/codemirror.js');
		$this->document->addScript('catalog/view/javascript/codemirror/lib/xml.js');
		$this->document->addScript('catalog/view/javascript/codemirror/lib/formatting.js');
		$this->document->addStyle('catalog/view/javascript/codemirror/lib/codemirror.css');
		$this->document->addStyle('catalog/view/javascript/codemirror/theme/monokai.css');
		$this->document->addScript('catalog/view/javascript/summernote/summernote.js');
		//$this->document->addScript('catalog/view/javascript/summernote/summernote-image-attributes.js');
		$this->document->addScript('catalog/view/javascript/summernote/opencart.js');
		$this->document->addStyle('catalog/view/javascript/summernote/summernote.css" rel="stylesheet');
			
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('used/staff_form', $data));
	}
        
    protected function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/used_staff')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}*/

		if (!$this->customer->isLogged()) {
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
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/used_staff');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'parent_id'   => 59,
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_used_staff->getAutoCategories($filter_data);

			//$this->log->write($results);
			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
