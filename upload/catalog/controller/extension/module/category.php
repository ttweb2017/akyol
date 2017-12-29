<?php
class ControllerExtensionModuleCategory extends Controller {
	public function index() {
		$this->load->language('extension/module/category');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			$children_data = array();

			if ($category['category_id'] == $data['category_id']) {
				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach($children as $child) {
					$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

					//this is for USED PRODUCT and CUSTOM PC
					$href = '';
					$name = '';
					switch ($category['category_id']){
						case '59':
							$this->load->model('catalog/used_staff');
							
							$child_href = $this->url->link('used/staff', 'path=' . $category['category_id'] . '_' . $child['category_id']);
							$child_name = $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_used_staff->getTotalUsedStaffs($filter_data) . ')' : '');
							break;
						case '60':
							$child_href = $this->url->link('custom/pc', 'path=' . $category['category_id'] . '_' . $child['category_id']);
							$child_name = $child['name'];
							break;
						default :
							$child_href = $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']);
							$child_name = $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '');
					}//ENDS HERE
			
					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name' => $child_name,
						'href' => $child_href
					);
				}
			}

			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

			//this is for USED PRODUCT and CUSTOM PC
			$href = '';
			$name = '';
            switch ($category['category_id']){
                case '59':
                    $href = $this->url->link('used/staff', 'path=' . $category['category_id']);
					$name = $category['name'];
                    break;
                case '60':
                    $href = $this->url->link('custom/pc', 'path=' . $category['category_id']);
					$name = $category['name'];
                    break;
                default :
                    $href = $this->url->link('product/category', 'path=' . $category['category_id']);
					$name = $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '');
            }//ENDS HERE
			
			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $name,
				'children'    => $children_data,
				'href'        => $href
			);
		}

		return $this->load->view('extension/module/category', $data);
	}
}