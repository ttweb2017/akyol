<?php
class ModelExtensionTotalStoreCreditTotal extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/store_credit_total');

		if(isset($this->session->data['store_credit_option'])){
			$this->log->write('get total method: '. $this->session->data['store_credit_option']);
			/*$store_credit_downpayment = $this->config->get('store_credit_downpayment');
        
			$string = $this->config->get('store_credit_percents');
			$c = substr_count($string, ':');
			$array = array();
			for($i = 0; $i < $c; $i++){
				$s2 = strlen($string);
				if($s2 > 0){
					$str_before = strtok($string, ',');
					$s1 = strlen($str_before);
					$month = strtok($str_before, ':');
					if($month == 2){
						$percent = substr(strstr($str_before, ':'), 1);
						$total = $total['total'];
						$credited_price = round(($total * (float) $percent)/100, 2);
						$downpayment = round(($credited_price * $store_credit_downpayment)/100, 2);
						$rest_payment = $credited_price - $downpayment;
						$monthly_payment = round($rest_payment/$month, 2);
					}
				}
				$string = substr($string,($s1 + 1));
			}*/

			$total['totals'][] = array(
				'code'       => 'store_credit_total',
				'title'      => $this->language->get('text_total'),
				'value'      => 0,
				'sort_order' => $this->config->get('store_credit_total_sort_order')
			);

			$total['total'] += 0;
		}
	}
}
