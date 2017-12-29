<?php
/* Создано в компании www.ttweb.org
 * =================================================================
*/
class ControllerExtensionPaymentStoreCredit extends Controller {
	public function index() {
		$this->load->language('extension/payment/store_credit');

		$data['text_credit'] ='credit'; //$this->language->get('text_credit');
		$data['text_loading'] = 'Loading...';
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['continue'] = $this->url->link('checkout/success');

                //config data of credit
		$data['store_credit_email'] = $this->config->get('store_credit_email');
        $data['store_credit_limit'] = $this->config->get('store_credit_limit');
        $data['store_credit_status'] = $this->config->get('store_credit_status');
        $data['store_credit_downpayment'] = $this->config->get('store_credit_downpayment');
        $data['store_credit_currency'] = $this->config->get('store_credit_currency');
        $data['store_credit_geo_zone_id'] = $this->config->get('store_credit_geo_zone_id');
        $data['store_credit_sort_order'] = $this->config->get('store_credit_sort_order');
        $data['store_credit_percents'] = $this->config->get('store_credit_percents');

		
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {
			$data['business'] = $this->config->get('store_credit_email');
			$data['item_name'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$data['products'] = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
						
						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$data['products'][] = array(
					'name'     => htmlspecialchars($product['name']),
					'model'    => htmlspecialchars($product['model']),
					'price'    => $this->currency->format($product['price'], $order_info['currency_code'], false, false),
					'quantity' => $product['quantity'],
					'option'   => $option_data,
					'weight'   => $product['weight']
				);
			}

			$data['discount_amount_cart'] = 0;

			$total = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);

			if ($total > 0) {
				$data['products'][] = array(
					'name'     => $this->language->get('text_total'),
					'model'    => '',
					'price'    => $total,
					'quantity' => 1,
					'option'   => array(),
					'weight'   => 0
				);
			} else {
				$data['discount_amount_cart'] -= $total;
			}

			$data['options'] = array();
        
			$string = $this->config->get('store_credit_percents');
			$c = substr_count($string, ':');
			//$this->log->write($c);//2:10,3:15,4:20,5:25,6:30
			$array = array();
			for($i = 0; $i < $c; $i++){
				$s2 = strlen($string);
				if($s2 > 0){
					$str_before = strtok($string, ',');
					$s1 = strlen($str_before);
					//$str_after = strstr($string, ',');
					//$this->log->write($str_before);
					$month = strtok($str_before, ':');
					$percent = substr(strstr($str_before, ':'), 1);
					//$this->log->write('Month: ' .trim($month) .' Percent: '.trim($percent));
					$string = substr($string,($s1 + 1));
				}
				
				$total_2 = $order_info['total'];
				$downpayment = ($total_2 * $data['store_credit_downpayment'])/100;
				$rest_payment = $total_2 - $downpayment;
				$credited_price = ($total_2 * (float) $percent)/100;
				//$this->log->write('total: ' .$total_2 .', percent: '.$percent .', credit: '.$credited_price .', downpayment: '.$downpayment .' rest payment: '.$rest_payment);
				$data['options'][] = array(
							'month' => trim($month),
							'percent' => trim($percent),
							'credited_price' => $credited_price,
							'downpayment' => $downpayment
				);
			}
			
			//$this->log->write($data['options']);
		
			$data['currency_code'] = $order_info['currency_code'];
			$data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$data['last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
			$data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
			$data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
			$data['country'] = $order_info['payment_iso_code_2'];
			$data['email'] = $order_info['email'];
			$data['invoice'] = $this->session->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
			$data['lc'] = $this->session->data['language'];
			$data['return'] = $this->url->link('checkout/success');
			$data['cancel_return'] = $this->url->link('checkout/checkout', '', true);

			$data['custom'] = $this->session->data['order_id'];

			return $this->load->view('extension/payment/store_credit', $data);
		}
	}
	
	public function getcreditoption(){
		if (isset($this->request->post['options'])) {
			
			$this->load->model('checkout/order');
			
			// Totals
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			$this->load->model('extension/extension');

			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
					
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}
			
			
			//$this->log->write(DIR_TEMPLATE .' get credit options: '. $total);
			
			$text_month = 'Month';
			$text_price = 'Price';
			$text_total = 'Total';
			$text_downpayment = 'Downpayment: ';
			$text_restpayment = 'Rest Payment: ';
			$price = '';
			
			$store_credit_downpayment = $this->config->get('store_credit_downpayment');
        
			$string = $this->config->get('store_credit_percents');
			$c = substr_count($string, ':');
			$array = array();
			for($i = 0; $i < $c; $i++){
				$s2 = strlen($string);
				if($s2 > 0){
					$str_before = strtok($string, ',');
					$s1 = strlen($str_before);
					$month = strtok($str_before, ':');
					if($month == $this->request->post['options']){
						$percent = substr(strstr($str_before, ':'), 1);
						$credited_price = round($total + ($total * (float) $percent)/100, 2);
						$downpayment = round(($credited_price * $store_credit_downpayment)/100, 2);
						$rest_payment = $credited_price - $downpayment;
						$monthly_payment = round($rest_payment/$month, 2);
						$price .= '<div class="table-responsive">
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<td class="text-left">
													' .$text_month. '
												</td>
												<td class="text-right">
													' .$text_price. '
												</td>
												<td class="text-right">
													' .$text_total. '
												</td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-left">
													' .$month. '
												</td>
												<td class="text-right">
													' .$this->currency->format($total, $this->session->data['currency']). '
												</td>
												<td class="text-right">
													' .$this->currency->format($credited_price, $this->session->data['currency']). '
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan=2 class="text-right"><strong>
													' .$text_downpayment. '
												</strong></td>
												<td class="text-right">
													' .$this->currency->format($downpayment, $this->session->data['currency']). '
												</td>
											</tr>';
											for($row = 0; $row < $month; $row++){
												$price .= '<tr>
																<td colspan=2 class="text-right"><strong>
																	' .$text_month. ' '.($row + 1).': 
																</strong></td>
																<td class="text-right">
																	' .$this->currency->format($monthly_payment, $this->session->data['currency']). '
																</td>
															</tr>';
											}
											$price .= '<tr>
															<td colspan=2 class="text-right"><strong>
																' .$text_restpayment. '
															</strong></td>
															<td class="text-right">
																' .$this->currency->format($rest_payment, $this->session->data['currency']). '
															</td>
														</tr>
										</tfoot>
									</table>
								</div>';
						//$price = 'Total: <b>' .$total .'</b><br /> Percent: <b>'.$percent .'%</b><br /> Credit: <b>'.$credited_price .'</b><br /> Downpayment: <b>'.$downpayment .'</b><br /> Rest payment: <b>'.$rest_payment.'</b>';
						//$this->log->write($price);
						break;
					}
					//$this->log->write('Month: ' .trim($month) .' Percent: '.trim($percent));
					$string = substr($string,($s1 + 1));
				}
				
				
				/*$downpayment = ($total * $store_credit_downpayment)/100;
				$rest_payment = $total - $downpayment;
				$credited_price = ($total * (float) $percent)/100;
				$this->log->write('total: ' .$total .', percent: '.$percent .', credit: '.$credited_price .', downpayment: '.$downpayment .' rest payment: '.$rest_payment);
				$data['options'][] = array(
							'month' => trim($month),
							'percent' => trim($percent),
							'credited_price' => $credited_price,
							'downpayment' => $downpayment
				);*/
			}
			
			//$this->log->write($data['options']);
			
			//$price = '<b>'.$this->request->post['options'].') 10.00</b>';
		}else{
			$price = '<b>0) 10.00</b>';
		}
		$json = array('price' => $price);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}
	
	public function getcredit(){
		if (isset($this->request->post['payment_method'])) {
			
			if($this->request->post['payment_method'] == 'store_credit'){
				$text_month_credit = 'Month';
				$text_credit_option = 'Choose credit option';
			
				$string = $this->config->get('store_credit_percents');
				$c = substr_count($string, ':');
				$output = '<h4 class="text-center">' .$text_credit_option. '</h4><hr /><div class="form-group required">
								<label class="control-label">' .$text_credit_option. '</label>';
				
				for($i = 0; $i < $c; $i++){
					$s2 = strlen($string);
					if($s2 > 0){
						$str_before = strtok($string, ',');
						$s1 = strlen($str_before);
						$month = strtok($str_before, ':');
						$string = substr($string,($s1 + 1));
					}
					
					
					$output .=	'<div id="credit-option">
									<div class="radio">
										<label>
											<input type="radio" onclick="addOptionPrice();" name="options" 
																value="' .trim($month). '" />' .trim($month). ' ' .$text_month_credit. '
										</label>
									</div>
								</div>';
				}
				
				/*$output = '';
				for($i = 0; $i < $c; $i++){
					$s2 = strlen($string);
					if($s2 > 0){
						$str_before = strtok($string, ',');
						$s1 = strlen($str_before);
						$month = strtok($str_before, ':');
						$string = substr($string,($s1 + 1));
					}
								
					$output .= '<label class="control-label" for="input-option-' .trim($month). '">' .$text_credit_option. '</label>
                                    <select onChange="addOptionPrice('.trim($month).');" name="options" id="input-option-' .trim($month). '" class="form-control">
                                        <option value="">' .$text_select. '</option>';
                                        foreach ($option['product_option_value'] as $option_value) {
                                            $output .= '<option value="' .trim($month). '">' .trim($month). ' ' .$text_month_credit. '</option>';
                                        }
                                  $output .= '</select>
                                </div>';
				}*/
				
				$output .= '</div><div id="credit_result"></div>';
			}else{
				$text_get_credit = 'You can apply to credit. Our team will check and if you will be eligible you can get credit from our store!';
				$output = '<p class="text-center"><b>'.$text_get_credit.'</b></p>';
			}
			
			$json = array('output' => $output);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			
		}
		
	}
	
	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'store_credit') {
			$this->load->model('checkout/order');
			$this->load->model('extension/payment/store_credit');
			
			// Totals
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			$this->load->model('extension/extension');

			$sort_order = array();

			$results = $this->model_extension_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
					
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}
			
			$store_credit_downpayment = $this->config->get('store_credit_downpayment');
        
			$string = $this->config->get('store_credit_percents');
			$c = substr_count($string, ':');
			$array = array();
			for($i = 0; $i < $c; $i++){
				$s2 = strlen($string);
				if($s2 > 0){
					$str_before = strtok($string, ',');
					$s1 = strlen($str_before);
					$month = strtok($str_before, ':');
					if($month == $this->session->data['store_credit_option']){
						$percent = substr(strstr($str_before, ':'), 1);
						$credited_price = round($total + ($total * (float) $percent)/100, 2);
						$downpayment = round(($credited_price * $store_credit_downpayment)/100, 2);
						$rest_payment = $credited_price - $downpayment;
						$monthly_payment = round($rest_payment/$month, 2);
						
						break;
					}
					$string = substr($string,($s1 + 1));
				}
			}
			
			$this->load->language('extension/total/store_credit_total');

			$data['totals'][] = array(
					'code'       => 'store_credit_total',
					'title'      => $this->language->get('text_downpayment'),
					'value'      => max(0, $downpayment),
					'sort_order' => '10'
				);
				
			$data['totals'][] = array(
					'code'       => 'store_credit_total',
					'title'      => $this->language->get('text_monthly') . ' x ' . $month,
					'value'      => max(0, $monthly_payment),
					'sort_order' => '11'
				);
				
			$data['totals'][] = array(
					'code'       => 'store_credit_total',
					'title'      => $this->language->get('text_total'),
					'value'      => max(0, $credited_price),
					'sort_order' => '12'
				);

			//$this->log->write($data);
		
			$this->model_extension_payment_store_credit->editOrderTotal($this->session->data['order_id'], $data, $credited_price);
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 1);
		}
	}

}
