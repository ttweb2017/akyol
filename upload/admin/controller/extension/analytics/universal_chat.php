<?php
class ControllerExtensionAnalyticsUniversalChat extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/analytics/universal_chat');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('universal_chat', $this->request->post, $this->request->get['store_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=analytics', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_sku_sku'] = $this->language->get('text_sku_sku');
        $data['text_sku_model'] = $this->language->get('text_sku_model');

        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_use_sku'] = $this->language->get('entry_use_sku');
        $data['help_use_sku'] = $this->language->get('help_use_sku');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=analytics', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/analytics/universal_chat', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], true)
        );

        $data['action'] = $this->url->link('extension/analytics/universal_chat', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=analytics', true);

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['universal_chat_code'])) {
            $data['universal_chat_code'] = $this->request->post['universal_chat_code'];
        } else {
            $data['universal_chat_code'] = $this->model_setting_setting->getSettingValue('universal_chat_code', $this->request->get['store_id']);
        }

        if (isset($this->request->post['universal_chat_use_sku'])) {
            $data['universal_chat_use_sku'] = $this->request->post['universal_chat_use_sku'];
        } else {
            $data['universal_chat_use_sku'] = $this->model_setting_setting->getSettingValue('universal_chat_use_sku', $this->request->get['store_id']);
        }

        if (isset($this->request->post['universal_chat_status'])) {
            $data['universal_chat_status'] = $this->request->post['universal_chat_status'];
        } else {
            $data['universal_chat_status'] = $this->model_setting_setting->getSettingValue('universal_chat_status', $this->request->get['store_id']);
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/analytics/universal_chat', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/analytics/universal_chat')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }        

        return !$this->error;
    }
}
