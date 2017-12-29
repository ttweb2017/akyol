<?php
/* Создано в компании www.ttweb.com
 * =================================================================
 * Ecomerce модуль OPENCART 2.3.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 2.3.x
 *  данный продукт не поддерживает программное обеспечение для других
 *  версий Opencart.
 * =================================================================
*/

class ControllerExtensionPaymentIkgateway extends Controller {
    
    private $error = array();

    public function index() {
        $this->load->language('extension/payment/ikgateway');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('ikgateway', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
        }

		$data['heading_title'] = $this->language->get('heading_title');
		
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
       
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        
        $data['ikgateway_counter_text'] = $this->language->get('ikgateway_counter_text');
        $data['ikgateway_counter_help'] = $this->language->get('ikgateway_counter_help');

        $data['entry_ik_shop_id'] = $this->language->get('entry_ik_shop_id');
        $data['entry_ik_shop_id_help'] = $this->language->get('entry_ik_shop_id_help');

        $data['entry_ik_sign_hash'] = $this->language->get('entry_ik_sign_hash');
        $data['entry_ik_sign_hash_help'] = $this->language->get('entry_ik_sign_hash_help');
        
        $data['entry_ik_currency'] = $this->language->get('entry_ik_currency');
        $data['entry_ik_currency_help'] = $this->language->get('entry_ik_currency_help');
       
        
        $data['tab_general'] = $this->language->get('tab_general');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        
        $data['text_ik_parameters'] = $this->language->get('text_ik_parameters');
       
        //$data['action']                    = $this->makeUrl('extension/payment/ikgateway');
		$data['action'] = $this->url->link('extension/payment/ikgateway', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
        //$data['ikgateway_success_url']  = HTTP_CATALOG . 'index.php?route=extension/payment/ikgateway/success';
        //$data['ikgateway_fail_url']     = HTTP_CATALOG . 'index.php?route=extension/payment/ikgateway/fail';
      
        

		if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['ik_shop_id'])) {
            $data['error_ik_shop_id'] = $this->error['ik_shop_id'];
        } else {
            $data['error_ik_shop_id'] = '';
        }

        if (isset($this->error['ik_sign_hash'])) {
            $data['error_ik_sign_hash'] = $this->error['ik_sign_hash'];
        } else {
            $data['error_ik_sign_hash'] = '';
        }

       
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->makeUrl('common/home'),
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->makeUrl('extension/payment'),
            'text'      => $this->language->get('text_payment'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->makeUrl('extension/payment/ikgateway'),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        //переопределяю некотр. перемен., не работает метод _updateData:(
        if (isset($this->request->post['ikgateway_sort_order'])) {
            $data['ikgateway_sort_order'] = $this->request->post['ikgateway_sort_order'];
        } elseif($this->config->get('ikgateway_sort_order')) {
            $data['ikgateway_sort_order'] = $this->config->get('ikgateway_sort_order');
        } else {
            $data['ikgateway_sort_order'] = "0";
        }

        if (isset($this->request->post['ikgateway_shop_id'])) {
            $data['ikgateway_shop_id'] = $this->request->post['ikgateway_shop_id'];
        } elseif($this->config->get('ikgateway_shop_id')) {
            $data['ikgateway_shop_id'] = $this->config->get('ikgateway_shop_id');
        } else {
            $data['ikgateway_shop_id']= "";
        }

        if (isset($this->request->post['ikgateway_sign_hash'])) {
            $data['ikgateway_sign_hash'] = $this->request->post['ikgateway_sign_hash'];
        } elseif($this->config->get('ikgateway_sign_hash')) {
            $data['ikgateway_sign_hash'] = $this->config->get('ikgateway_sign_hash');
        } else {
            $data['ikgateway_sign_hash']= "";
        }

        

        if (isset($this->request->post['ikgateway_currency'])) {
            $data['ikgateway_currency'] = $this->request->post['ikgateway_currency'];
        } elseif($this->config->get('ikgateway_currency')) {
            $data['ikgateway_currency'] = $this->config->get('ikgateway_currency');
        } else {
            $data['ikgateway_currency']= "";
        }


        if (isset($this->request->post['ikgateway_order_status_id'])) {
            $data['ikgateway_order_status_id'] = $this->request->post['ikgateway_order_status_id'];
        } elseif($this->config->get('ikgateway_order_status_id')) {
            $data['ikgateway_order_status_id'] = $this->config->get('ikgateway_order_status_id');
        } else {
            $data['ikgateway_order_status_id']= "";
        }

         if (isset($this->request->post['ikgateway_counter'])) {
            $data['ikgateway_counter'] = $this->request->post['ikgateway_counter'];
        } elseif($this->config->get('ikgateway_counter')) {
            $data['ikgateway_counter'] = $this->config->get('ikgateway_counter');
        } else {
            $data['ikgateway_counter']= 0;
        }


        if (isset($this->request->post['ikgateway_status'])) {
            $data['ikgateway_status'] = $this->request->post['ikgateway_status'];
        } elseif($this->config->get('ikgateway_status')) {
            $data['ikgateway_status'] = $this->config->get('ikgateway_status');
        } else {
            $data['ikgateway_status']= "";
        }
        

        //конец переопределения

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = array_merge(
            array(0 => array(
                'name' => $this->language->get('text_order_status_cart')
            )),
            $this->model_localisation_order_status->getOrderStatuses()
        );

       

        $this->load->model('setting/store');
        $stores = $this->model_setting_store->getStores();
        foreach ($stores as $store){
            $data['stores'][] = $store['url'];
        }
        $data['stores'][] = $this->config->get('config_url');

        $this->load->model('localisation/currency');

        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/ikgateway.tpl', $data));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/ikgateway')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
            
		if (!isset($this->request->post['ikgateway_sign_hash']) || !$this->request->post['ikgateway_sign_hash']) {
            $this->error['ikgateway_sign_hash'] = $this->language->get('error_ik_sign_hash');
        }
          
        if (!isset($this->request->post['ikgateway_shop_id']) || !$this->request->post['ikgateway_shop_id']) {
            $this->error['ikgateway_shop_id'] = $this->language->get('error_ik_shop_id');
        }
		
        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function readLastLines($filename, $lines) {
        if (!is_file($filename)) {
            return array();
        }
        $handle = @fopen($filename, "r");
        if (!$handle) {
            return array();
        }
        $linecounter = $lines;
        $pos = -1;
        $beginning = false;
        $text = array();

        while ($linecounter > 0) {
            $t = " ";

            while ($t != "\n") {
                /* if fseek() returns -1 we need to break the cycle*/
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }

            $linecounter--;

            if ($beginning) {
                rewind($handle);
            }

            $text[$lines - $linecounter - 1] = fgets($handle);

            if ($beginning) {
                break;
            }
        }
        fclose($handle);

        return array_reverse($text);
    }

    function makeUrl($route, $url = '')
    {
        return str_replace('&amp;', '&', $this->url->link($route, $url.'&token=' . $this->session->data['token'], 'SSL'));
    }
}
