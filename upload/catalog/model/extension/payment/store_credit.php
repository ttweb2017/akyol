<?php
/* Создано в компании www.ttweb.org
 * =================================================================
*/

class ModelExtensionPaymentStoreCredit extends Model {

    public function getMethod($address, $total) {
        $this->load->language('extension/payment/store_credit');

        if ($this->config->get('store_credit_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('store_credit_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

			if ($this->config->get('store_credit_limit') < $total) {
				$status = false;
            }elseif (!$this->config->get('store_credit_geo_zone_id')) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }
		
		
		//this will aloow checking currency code
		$currencies = array(
			'TMM'
		);
		
		if (!in_array(strtoupper($this->session->data['currency']), $currencies)) {
			$status = false;
		}

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code' => 'store_credit',
                'title' => $this->language->get('text_title'),
                'terms'      => '',
                'description' => $this->language->get('text_description'),
                'sort_order' => $this->config->get('store_credit_sort_order')
            );
        }
        return $method_data;
    }
	
	public function editOrderTotal($order_id, $data, $credited_total) {
		foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}
		$this->db->query("UPDATE " . DB_PREFIX . "order SET total = '" .(float)$credited_total. "' WHERE order_id = '" . (int)$order_id ."'");
	}
}
?>