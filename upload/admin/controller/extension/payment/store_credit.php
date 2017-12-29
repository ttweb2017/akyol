<?php
class ControllerExtensionPaymentStoreCredit extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/store_credit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('store_credit', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_credit_currency'] = $this->language->get('entry_credit_currency');
		$data['entry_downpayment'] = $this->language->get('entry_downpayment');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_percents'] = $this->language->get('entry_percents');

		$data['help_limit'] = $this->language->get('help_limit');
		$data['help_downpayment'] = $this->language->get('help_downpayment');
		$data['help_percents'] = $this->language->get('help_percents');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}
                
                if (isset($this->error['limit'])) {
			$data['error_limit'] = $this->error['limit'];
		} else {
			$data['error_limit'] = '';
		}
                
                if (isset($this->error['downpayment'])) {
			$data['error_downpayment'] = $this->error['downpayment'];
		} else {
			$data['error_downpayment'] = '';
		}
                
                if (isset($this->error['percents'])) {
			$data['error_percents'] = $this->error['percents'];
		} else {
			$data['error_percents'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/store_credit', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/store_credit', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['store_credit_email'])) {
			$data['store_credit_email'] = $this->request->post['store_credit_email'];
		} else {
			$data['store_credit_email'] = $this->config->get('store_credit_email');
		}

		if (isset($this->request->post['store_credit_limit'])) {
			$data['store_credit_limit'] = $this->request->post['store_credit_limit'];
		} else {
			$data['store_credit_limit'] = $this->config->get('store_credit_limit');
		}

		if (isset($this->request->post['store_credit_currency'])) {
			$data['store_credit_currency'] = $this->request->post['store_credit_currency'];
		} else {
			$data['store_credit_currency'] = $this->config->get('store_credit_currency');
		}

                if (isset($this->request->post['store_credit_downpayment'])) {
			$data['store_credit_downpayment'] = $this->request->post['store_credit_downpayment'];
		} else {
			$data['store_credit_downpayment'] = $this->config->get('store_credit_downpayment');
		}
                
                if (isset($this->request->post['store_credit_percents'])) {
			$data['store_credit_percents'] = $this->request->post['store_credit_percents'];
		} elseif ($this->config->has('store_credit_percents')) {
		   	$data['store_credit_percents'] = $this->config->get('store_credit_percents');
		} else {
			$data['store_credit_percents'] = '';
		}
                
                /*
                $string = $this->config->get('credit_percents');
                $c = substr_count($string, ':');
                $this->log->write($c);//2:10,3:15,4:20,5:25,6:30
                $array = array();
                for($i = 0; $i < $c; $i++){
                    $s2 = strlen($string);
                    if($s2 > 0){
                        $str_before = strtok($string, ',');
                        //$str_after = strstr($string, ',');
                        $this->log->write($str_before);
                        $month = strtok($str_before, ':');
                        $percent = strstr($str_before, ':');
                        $this->log->write('Month: ' .trim($month) .' Percent'.trim($percent));
                        $s1 = strlen($str_before);
                        $string = substr($string,($s1 + 1));
                    }
                    //array_push($array, $s1);
                }*/
                
		$this->load->model('localisation/order_status');

                $this->load->model('localisation/geo_zone');
                
                $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
                
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['store_credit_geo_zone_id'])) {
			$data['store_credit_geo_zone_id'] = $this->request->post['store_credit_geo_zone_id'];
		} else {
			$data['store_credit_geo_zone_id'] = $this->config->get('store_credit_geo_zone_id');
		}

		if (isset($this->request->post['store_credit_status'])) {
			$data['store_credit_status'] = $this->request->post['store_credit_status'];
		} else {
			$data['store_credit_status'] = $this->config->get('store_credit_status');
		}

		if (isset($this->request->post['store_credit_sort_order'])) {
			$data['store_credit_sort_order'] = $this->request->post['store_credit_sort_order'];
		} else {
			$data['store_credit_sort_order'] = $this->config->get('store_credit_sort_order');
		}
                
                $this->load->model('localisation/currency');

                $data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/store_credit', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/store_credit')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['store_credit_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}
                
                if (!$this->request->post['store_credit_limit']) {
			$this->error['limit'] = $this->language->get('error_limit');
		}
                
                if (!$this->request->post['store_credit_downpayment']) {
			$this->error['downpayment'] = $this->language->get('error_downpayment');
		}
                        
                if (!$this->request->post['store_credit_percents']) {
			$this->error['percents'] = $this->language->get('error_percents');
		}

		return !$this->error;
	}
}
