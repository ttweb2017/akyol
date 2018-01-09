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

class ControllerExtensionPaymentIkgateway extends Controller
{
    private $log;
    private $order;
    private $key;

	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		//$data['continue'] = $this->url->link('checkout/success');
		
		if (isset($this->session->data['payment_method']['code'])) {
			$data['code'] = $this->session->data['payment_method']['code'];
		} else {
			$data['code'] = '';
		}

		return $this->load->view('extension/payment/ikgateway', $data);
	}
	
    public function confirm()
    {
		$json = array();

		if(isset($this->session->data['order_id']) && isset($this->request->post['payment_method'])){
			$this->language->load('extension/payment/ikgateway');
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$counter = $this->config->get('ikgateway_counter');
			$originalOrderId = $this->session->data['order_id'];
			$orderId = $this->session->data['order_id'] + $this->config->get('ikgateway_counter');

			$uname = $this->config->get('ikgateway_shop_id');
			$pass = $this->config->get('ikgateway_sign_hash');
			$amount = $order_info['total']*100;
			$iso = $this->config->get('ikgateway_currency');
			$failUrl= $this->url->link('extension/payment/ikgateway/fail', '');
			$description = 'Toleg';
			$sign = sha1("$orderId:$amount:$description:$description:$orderId:$amount");
			$returnUrl = $this->url->link('extension/payment/ikgateway/success', '');
			
			
			$url = "https://mpi.gov.tm/payment/rest/register.do?currency=934&language=ru&pageView=DESKTOP&description=Toleg&orderNumber=".urlencode($orderId)."&failUrl=".$failUrl."&userName=".urlencode($uname)."&password=".urlencode($pass)."&amount=".urlencode($amount)."&returnUrl=".urlencode($returnUrl.'&sign='.$sign."&origOrderId=".$originalOrderId);
			//$url = "http://192.168.50.116:8085/home/register?currency=934&language=ru&pageView=DESKTOP&description=Toleg&orderNumber=".urlencode($orderId)."&failUrl=".$failUrl."&userName=".urlencode($uname)."&password=".urlencode($pass)."&amount=".urlencode($amount)."&returnUrl=".urlencode($returnUrl.'&sign='.$sign."&origOrderId=".$originalOrderId);
			
			$this->logWrite($url,$url);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FAILONERROR,true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$retValue = curl_exec($ch);          
			curl_close($ch);
			$receivedData = json_decode($retValue,TRUE);
			
			$this->logWrite($receivedData,'recieved data');
			
			if($receivedData != "")
			{
				$response_status = $receivedData["errorCode"];
				if($response_status == "0")
				{
					if($receivedData["orderId"] != ""){
						$data["ext_order_id"] = $receivedData["orderId"];
						$this->logWrite($receivedData["orderId"], 'order_id');
						
						$this->load->model('extension/payment/ikgateway');
						$this->model_extension_payment_ikgateway->AddExternalOrderId($originalOrderId,$receivedData["orderId"]);

						$form_url = $receivedData["formUrl"];
						$this->logWrite($receivedData,'recived');
						$json['redirect'] = $form_url;
						//$this->response->redirect($form_url);
					}else{
						// do something with registered order here
						$json['error'] = 'Order ID is not getted';
					}
				}else{
					// do something with registered order here
					/*if ($response_status == "1"){
						redirect to http://localhost/OpenCartV2.3/upload/index.php?route=checkout/checkout because that order is already registered
					}*/
					$json['error'] = 'Error Code: ' .$receivedData["errorCode"]. ' Error Message: '. $receivedData["errorMessage"];
				}
			}else{
				$json['error'] = 'Received data is empty!';
			}
		}else{
			$json['error'] = 'Make your order first to be able to pay!';
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
      
    }
    
    
    public function success() {
         
        if(isset($this->request->get["sign"])&&isset($this->request->get["orderId"])&&isset($this->request->get["origOrderId"])){
               
               $this->load->model('checkout/order');
               $item = $this->model_checkout_order->getOrder($this->request->get["origOrderId"]);
               $extId = $this->request->get["orderId"];
               $orderId = $item["order_id"] + $this->config->get('ikgateway_counter');
               $uname = $this->config->get('ikgateway_shop_id');
		       $pass = $this->config->get('ikgateway_sign_hash');
		       $amount = $item['total']*100;
		       $description = 'Toleg';
               
		       $verifySign = sha1("$orderId:$amount:$description:$description:$orderId:$amount");
               if($verifySign == $this->request->get["sign"]) {
                    $url = "https://mpi.gov.tm/payment/rest/getOrderStatus.do?userName=$uname&password=$pass&orderId=$extId&language=ru";
                    //$url = "http://192.168.50.116:8085/home/GetOrderStatus?userName=$uname&password=$pass&orderId=$extId&language=ru";
					$this->logWrite($url,"Url");
	                $ch = curl_init($url);
		            curl_setopt($ch, CURLOPT_FAILONERROR,true);
		            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
		            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		            $retValue = curl_exec($ch);
		            curl_close($ch);
                    $this->logWrite($retValue,"retValue");
                    if($retValue != ""){
                        $data = json_decode($retValue,true);
		                $OrderStatus = $data["OrderStatus"];//orderStatus
		                $ErrorCode = $data["ErrorCode"];//errorCode

                        if(($ErrorCode == "0" && $OrderStatus == "2")||($ErrorCode == 0 && $OrderStatus == 2))
		                {
                            //$approvalCode = $data["approvalCode"];
							//$cardholderName = $data["cardholderName"];
                            $this->model_checkout_order->addOrderHistory($item["order_id"], $this->config->get('ikgateway_order_status_id'));
							
							$this->response->redirect($this->url->link('checkout/success'));
							
                        }else{
							
                            $data['text_error'] = 'Error Code: ' .$data["ErrorCode"]. ' Error Message: '. $data["ErrorMessage"];
                        }
                       
                    }else {
						$data['text_error'] = 'Empty value returned!';
					}
                }else {
					$data['text_error'] = 'Sign is not verified!';
				}

        }else{
			$data['text_error'] = 'One of the parameter is not set!';
        }
		
		//here we clear the cart because of registered order in ecom
        $this->cart->clear();
		
        $this->load->language('extension/payment/ikgateway');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);
		
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/payment/ikgateway/fail'),
			'text' => $this->language->get('text_error')
		);

		$data['continue'] = $this->url->link('common/home');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$data['button_continue'] = $this->language->get('button_continue');
		
		$this->response->setOutput($this->load->view('extension/payment/ikgateway_fail', $data));
    }

    public function fail()
    {
		$this->load->language('extension/payment/ikgateway_fail');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);
		
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/payment/ikgateway/fail'),
			'text' => $this->language->get('text_error')
		);

		$data['continue'] = $this->url->link('checkout/checkout');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$data['button_continue'] = $this->language->get('button_continue');
		
		$data['text_error'] = $this->language->get('text_error');
		
		$this->response->setOutput($this->load->view('extension/payment/ikgateway_fail', $data));
		
    }

  
	private function logWrite($message, $type)
    {
        if (!$this->log) {
            $this->log = new Log('ikgateway.log');
        }
        $this->log->Write($message);
    }

}

?>
