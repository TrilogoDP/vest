<?php

class ControllerExtensionPaymentWayforpay extends Controller
{

	public $codesCurrency = [
		980 => 'UAH',
		840 => 'USD',
		978 => 'EUR',
		643 => 'RUB',		
	];

	public function index()
	{
		
		$order_id = $this->session->data['order_id'];

		$this->load->model('checkout/order');
		$order = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$_ga = false;
		if (!empty($this->request->cookie['_ga'])){
			$_ga = $this->request->cookie['_ga'];
		}

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = 'loading';
		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/wayforpay.tpl', $data);
	}

	public function form(){
		$w4p = new WayForPay();
		$key = $this->config->get('wayforpay_secretkey');
		$w4p->setSecretKey($key);

		$order_id = $this->session->data['order_id'];

		$this->load->model('checkout/order');
		$order = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$_ga = false;
		if (!empty($this->request->cookie['_ga'])){
			$_ga = $this->request->cookie['_ga'];
		}

		$serviceUrl = $this->config->get('wayforpay_serviceUrl');
		$returnUrl = $this->config->get('wayforpay_returnUrl');

		if ($_ga){
			$serviceUrl = $serviceUrl . '&_ga=' . $_ga;
			$returnUrl = $returnUrl . '&_ga=' . $_ga;
		}

		$currency = isset($this->codesCurrency[$order['currency_code']]) ? $this->codesCurrency[$order['currency_code']] : $order['currency_code'];
		$amount = round(($order['total'] * $order['currency_value']), 2);

		$fields = array(
			'orderReference' 		=> $order_id . WayForPay::ORDER_SEPARATOR . time(),
			'merchantAccount' 		=> $this->config->get('wayforpay_merchant'),
			'orderDate' 			=> strtotime($order['date_added']),
			'merchantAuthType' 		=> 'simpleSignature',
			'merchantDomainName' 	=> $_SERVER['HTTP_HOST'],
			'merchantTransactionSecureType' => 'AUTO',
			'amount' 				=> $amount,
			'currency' 				=> $currency,
			'serviceUrl' 			=> $serviceUrl,
			'returnUrl' 			=> $returnUrl,
			'language' 				=> $this->config->get('wayforpay_language')
		);

		$productNames = array();
		$productQty = array();
		$productPrices = array();
		$this->load->model('account/order');
		$products = $this->model_account_order->getOrderProducts($order_id);
		foreach ($products as $product) {
			$productNames[] = str_replace(["'", '"', '&#39;', '&'], '', htmlspecialchars_decode($product['name']));
			$productPrices[] = round($product['price'], 2);
			$productQty[] = intval(round($product['quantity']));
		}

		$fields['productName'] 	= $productNames;
		$fields['productPrice'] = $productPrices;
		$fields['productCount'] = $productQty;

		$phone = str_replace(array('+', ' ', '(', ')'), array('', '', '', ''), $order['telephone']);
		if (strlen($phone) == 10) {
			$phone = '38' . $phone;
		} elseif (strlen($phone) == 11) {
			$phone = '3' . $phone;
		}

		$fields['clientFirstName'] = $order['payment_firstname'];
		$fields['clientLastName'] = $order['payment_lastname'];
		$fields['clientEmail'] = $order['email'];
		$fields['clientPhone'] = $phone;
		$fields['clientCity'] = $order['payment_city'];
		$fields['clientAddress'] = $order['payment_address_1'] . ' ' . $order['payment_address_2'];
		$fields['clientCountry'] = $order['payment_iso_code_3'];

		$fields['merchantSignature'] = $w4p->getRequestSignature($fields);

		$data['fields'] = $fields;
		$data['action'] = WayForPay::URL;
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = 'loading';
		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/wayforpay_form.tpl', $data);
	}

	public function confirm(){
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if ($order_info) {
			$order_id = $this->session->data['order_id'];

			if ($order_info['order_status_id'] == 0) {
				//$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('wayforpay_order_status_progress_id'), 'WayForPay');				
			} elseif ($order_info['order_status_id'] != $this->config->get('wayforpay_order_status_progress_id')) {
				//$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('wayforpay_order_status_progress_id'), 'WayForPay', true);
			}
		}
	}

	public function response(){

		$w4p = new WayForPay();
		$key = $this->config->get('wayforpay_secretkey');
		$w4p->setSecretKey($key);

		$paymentInfo = $w4p->isPaymentValid($_POST);

		if ($paymentInfo === true) {
			list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $_POST['orderReference']);

			$message = '';

			$this->load->model('checkout/order');


			$orderInfo = $this->model_checkout_order->getOrder($order_id);
			if (
				$orderInfo &&
				$orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')
			) {
			} else {
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('wayforpay_order_status_id'), $message, false);
			}

			$this->response->redirect($this->url->link('checkout/success') . '&type=wayforpay' );
		} else {
			$this->session->data['error'] = $paymentInfo;
			$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
		}
	}

	public function callback(){

		$data = json_decode(file_get_contents("php://input"), true);

		$w4p = new WayForPay();
		$key = $this->config->get('wayforpay_secretkey');
		$w4p->setSecretKey($key);

		$paymentInfo = $w4p->isPaymentValid($data);

		if ($paymentInfo === true) {
			list($order_id,) = explode(WayForPay::ORDER_SEPARATOR, $data['orderReference']);

			$message = '';

			$this->load->model('checkout/order');

			$orderInfo = $this->model_checkout_order->getOrder($order_id);
			if (
				$orderInfo &&
				$orderInfo['order_status_id'] == $this->config->get('wayforpay_order_status_id')
			) {
			} else {
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('wayforpay_order_status_id'), $message, false);
			}

			echo $w4p->getAnswerToGateWay($data);
		} else {
			echo $paymentInfo;
		}
		exit();
	}

	public function getOrderDeliveryCost($order_id){
		$this->load->model('checkout/order');
		$totals = $this->model_checkout_order->getOrderTotals($order_id);

		foreach ($totals as $total){
			if ($total['code'] == 'shipping'){
				return $total['value'];
			}
		}
	}

	public function getOrderTotal($order_id){
		$this->load->model('checkout/order');
		$totals = $this->model_checkout_order->getOrderTotals($order_id);

		foreach ($totals as $total){
			if ($total['code'] == 'total'){
				return $total['value'];
			}
		}
	}

	public function presuccess(){
				$this->load->model('checkout/order');
				$this->load->library('hobotix/BarcodeValidator');

				if (!isset($this->session->data['order_id']) && $_SERVER['REMOTE_ADDR'] == '31.43.104.42'){
					$this->session->data['order_id'] = 129319;
				}

				if (empty($this->session->data['recent_order_id'])){
					$this->session->data['recent_order_id'] = $this->session->data['order_id'];
				}

				$this->load->language('checkout/success');
				$this->load->language('extension/payment/wayforpay');

				$data['currency_code'] = $this->config->get('config_currency');

				if (isset($this->session->data['recent_order_id'])) {
					$this->load->model('checkout/order');
					$this->load->model('catalog/product');
					$this->load->model('customer/customer');

					$order_info = $this->model_checkout_order->getOrder($this->session->data['recent_order_id']);
					$products = $this->model_checkout_order->getOrderProducts($this->session->data['recent_order_id']);

					$this->data['has_analytics'] = (int)$order_info['analytics'];
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET analytics = '1' WHERE order_id = '" . (int)$this->session->data['recent_order_id'] . "'");

					$data['shipping_code'] = $order_info['shipping_code'];

					foreach ($products as $product){
						$realProduct = $this->model_catalog_product->getProduct($product['product_id'], false);

						$gtin = false;				
						if (\hobotix\BarcodeValidator::IsValidEAN13($realProduct['ean']) || \hobotix\BarcodeValidator::IsValidEAN8($realProduct['ean'])){
							$gtin = $realProduct['ean'];
						}					

						if ($realProduct['current_in_stock']){
							$productsOnStock++;
						}

						$transactionProduct = array(
							'id'  			=> $product['product_id'],
							'sku' 			=> $realProduct['sku']?$realProduct['sku']:$realProduct['model'],
							'name' 			=> $realProduct['name'],
							'manufacturer' 	=> $realProduct['manufacturer'],
							'category' 		=> $this->model_catalog_product->getGoogleCategoryPath($realProduct['product_id']),
							'price' 		=> $product['price'],
							'total' 		=> $product['total'],
							'quantity' 		=> $product['quantity'],
						);										

						if ($gtin){
							$transactionGTINS[] = '{"gtin":"' . prepareEcommString($gtin) . '"}';
						}

						$transactionProduct = array_map('prepareEcommString', $transactionProduct);					
						$transactionProducts[] = $transactionProduct;			
					}

					$data['google_ecommerce_info'] = array(
						'transactionId' 			=> $order_info['order_id'],	
						'transactionEmail' 			=> $order_info['email'],	
						'transactionCurrency'		=> $this->config->get('config_currency'),
						'transactionAffiliation'    => $this->config->get('config_url'),
						'transactionTax'			=> 0.00,
						'transactionTotal'			=> $this->getOrderTotal($order_info['order_id']),
						'transactionShipping'		=> $this->getOrderDeliveryCost($order_info['order_id']),
						'transactionEstimatedDelivery'	=> date('Y-m-d', strtotime('+2 day')),
						'transactionCountryCode'	=> 'UA',
					);	

					if (!empty($this->session->data['coupon'])){
						$data['google_ecommerce_info']['transactionCoupon'] = $this->session->data['coupon'];
					}								

					$data['google_ecommerce_info'] = array_map('prepareEcommString', $data['google_ecommerce_info']);
					$data['google_ecommerce_info']['transactionProducts'] = $transactionProducts;
					$data['google_ecommerce_info']['transactionGTINS'] = $transactionGTINS;

					$data['google_ecommerce_info']['customerData'] = [					
						'email' 	=> $order_info['email'],
						'new'		=> ((int)$this->model_customer_customer->getCountOrdersByCustomer(['email' => $order_info['email'], 'telephone' => $order_info['telephone']]) >= 2)?'false':'true',
						'telephone' => $order_info['telephone'],
						'name' 		=> $order_info['firstname'],
						'city'		=> $order_info['shipping_zone'],
						'postcode'	=> $order_info['shipping_postcode'],
						'address'	=> $order_info['shipping_city'],
						'country'	=> 'Україна'
					];

					$data['google_ecommerce_info']['customerData'] = array_map('prepareEcommString', $data['google_ecommerce_info']['customerData']);


					$product_ids = array();				
					foreach ($products as $product) {
						$product_ids[] = $product['product_id'];
					}

					$last_pixel_purchase_exist = false;

					$pixel = '';
					$products_id_string = implode(',', $product_ids);
					$products_id_string = str_replace(',', "','", $products_id_string);
					$pixel .= "fbq('track', 'Purchase', {
						content_ids: ['" . $products_id_string . "'],
						content_id: ['" . $products_id_string . "'],
						content_type: 'product',
						value: '". $order_info['total'] ."',
						currency: '". $this->session->data['currency'] ."',
						num_items: '".count($product_ids)."'				
					});";

					$pixel .= "ttq.track('Checkout', {
						contents: ['" . $products_id_string . "'],
						content_id: ['" . $products_id_string . "'],
						content_type: 'product',
						value: '". $this->currency->convert($order_info['total'], 'RUB', 'UAH') ."',
						currency: 'RUB',
						num_items: '".count($product_ids)."'				
					});";

					$pixel .= "dataLayer.push({
						'event': 'purchase',
						'value': '". $order_info['total'] ."',
						'items': [";

						foreach ($product_ids as $product_id){
							$pixel .= "{ 
								'item': ". $product_id .",
								'google_business_vertical': 'retail'													
							},";
						}

						$pixel = rtrim($pixel,',');

						$pixel .= "	]
					});";

					$this->session->data['mf_pixel_purchase'] = $this->session->data['recent_order_id'];
				}

				$this->document->setPixel($pixel);

				if (isset($this->session->data['order_id'])) {
					$data['order_id'] = $this->session->data['order_id'];
					$data['text_order_id'] = sprintf($this->language->get('text_order_id'), $data['order_id']);
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET analytics = '1' WHERE order_id = '" . (int)$this->session->data['order_id'] . "'");
				}

				$this->document->setTitle($this->language->get('wfp_heading_title'));

				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/home')
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_basket'),
					'href' => $this->url->link('checkout/cart')
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_checkout'),
					'href' => $this->url->link('checkout/checkout', '', true)
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('wfp_text_crumb'),
					'href' => $this->url->link('extension/payment/wayforpay/presuccess')
				);

				$data['heading_title'] = $this->language->get('wfp_heading_title');

				if ($this->customer->isLogged()) {
					$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
				} else {
					$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
				}


				if(isset($this->request->get['type'])){
					$data['mf_type'] = $this->request->get['type'];
				}else{
					$data['mf_type'] = '';
				}
				$data['mf_heading_title'] 	= $this->language->get('mf_heading_title');
				$data['mf_heading'] 		= $this->language->get('mf_heading_title');
				$data['mf_text_pb'] 		= $this->language->get('mf_text_pb');
				$data['mf_text_np'] 		= $this->language->get('mf_text_np');
				$data['mf_text_online'] 	= $this->language->get('mf_text_online');

				$data['wfp_text_wait'] 	= $this->language->get('wfp_text_wait');

				$data['button_continue'] = $this->language->get('button_continue');

				$data['continue'] = $this->url->link('common/home');

				$data['column_left'] 	= $this->load->controller('common/column_left');
				$data['column_right'] 	= $this->load->controller('common/column_right');
				$data['content_top'] 	= $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] 		= $this->load->controller('common/footer');
				$data['header'] 		= $this->load->controller('common/header');
				$data['wayforpay_form'] = $this->load->controller('extension/payment/wayforpay/form');

				$this->response->setOutput($this->load->view('extension/payment/wayforpay_ga', $data));

			}
}

		class WayForPay
		{
			const ORDER_APPROVED = 'Approved';
			const ORDER_HOLD_APPROVED = 'WaitingAuthComplete';

			const ORDER_SEPARATOR = '#';

			const SIGNATURE_SEPARATOR = ';';

			const URL = "https://secure.wayforpay.com/pay/";

			protected $secret_key = '';
			protected $keysForResponseSignature = array(
				'merchantAccount',
				'orderReference',
				'amount',
				'currency',
				'authCode',
				'cardPan',
				'transactionStatus',
				'reasonCode'
			);

			/** @var array */
			protected $keysForSignature = array(
				'merchantAccount',
				'merchantDomainName',
				'orderReference',
				'orderDate',
				'amount',
				'currency',
				'productName',
				'productCount',
				'productPrice'
			);


		/**
			* @param $option
			* @param $keys
			* @return string
		*/
			public function getSignature($option, $keys)
			{
				$hash = array();
				foreach ($keys as $dataKey) {
					if (!isset($option[$dataKey])) {
						continue;
					}
					if (is_array($option[$dataKey])) {
						foreach ($option[$dataKey] as $v) {
							$hash[] = $v;
						}
					} else {
						$hash [] = $option[$dataKey];
					}
				}

				$hash = implode(self::SIGNATURE_SEPARATOR, $hash);
				return hash_hmac('md5', $hash, $this->getSecretKey());
			}


		/**
			* @param $options
			* @return string
		*/
			public function getRequestSignature($options)
			{
				return $this->getSignature($options, $this->keysForSignature);
			}

		/**
			* @param $options
			* @return string
		*/
			public function getResponseSignature($options)
			{
				return $this->getSignature($options, $this->keysForResponseSignature);
			}


		/**
			* @param array $data
			* @return string
		*/
			public function getAnswerToGateWay($data)
			{
				$time = time();
				$responseToGateway = array(
					'orderReference' => $data['orderReference'],
					'status' => 'accept',
					'time' => $time
				);
				$sign = array();
				foreach ($responseToGateway as $dataKey => $dataValue) {
					$sign [] = $dataValue;
				}
				$sign = implode(self::SIGNATURE_SEPARATOR, $sign);
				$sign = hash_hmac('md5', $sign, $this->getSecretKey());
				$responseToGateway['signature'] = $sign;

				return json_encode($responseToGateway);
			}

		/**
			* @param $response
			* @return bool|string
		*/
			public function isPaymentValid($response)
			{

				if (!isset($response['merchantSignature']) && isset($response['reason'])) {
					return $response['reason'];
				}
				$sign = $this->getResponseSignature($response);
				if ($sign != $response['merchantSignature']) {
					return 'An error has occurred during payment';
				}

				if (
					$response['transactionStatus'] == self::ORDER_APPROVED ||
					$response['transactionStatus'] == self::ORDER_HOLD_APPROVED
				) {
					return true;
				}

				return false;
			}

			public function setSecretKey($key)
			{
				$this->secret_key = $key;
			}
			
			public function getSecretKey()
			{
				return $this->secret_key;
			}
		}
