<?php
/* Создано в компании www.interkassa.com
 * =================================================================
 * Интеркасса модуль OPENCART 2.3.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 2.3.x
 *  www.interkassa.com не гарантирует правильную работу этого расширения на любой другой
 *  версии Opencart, кроме Opencart 2.3.x
 *  данный продукт не поддерживает программное обеспечение для других
 *  версий Opencart.
 * =================================================================
*/

class ModelExtensionPaymentIkgateway extends Model {

       
    public function AddExternalOrderId($orderId="",$externalId=""){
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "order` SET ext_order_id='".$externalId."' WHERE order_id=".$orderId);
    }

    public function GetOrderByExternalOrderId($orderId){
        return $this->db->query("Select * FROM `" . DB_PREFIX . "order` WHERE ext_order_id='".$orderId."' LIMIT 1")->row;
    }


    public function getMethod($address) {
        $this->load->language('extension/payment/ikgateway');

        if ($this->config->get('ikgateway_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('ikgateway_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

            if (!$this->config->get('ikgateway_geo_zone_id')) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code' => 'ikgateway',
                'title' => $this->language->get('text_title'),
                'terms'      => '',
                'description' => $this->language->get('text_description'),
                'sort_order' => $this->config->get('ikgateway_sort_order')
            );
        }
        return $method_data;
    }
}
?>