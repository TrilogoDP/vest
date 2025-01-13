<?php
	
	class ControllerHobotixEcommerce extends Controller {

		public function test(){
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory(170299, 17, $comment = '', $notify = false, $override = false, $adminSubjAddon = '', $passQueue = true);
		}


		public function ecommerceCheckoutSteps(){
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$this->load->model('tool/upload');
			
			$allowedEvents = array(
			'PopupCartOpened' => 1,
			'checkoutPageOpened' => 2
			);
			
			
			if (!empty($this->request->get['ecommerceEvent']) && isset($allowedEvents[$this->request->get['ecommerceEvent']])){
				$data['ecommerceEvent'] = $this->request->get['ecommerceEvent'];
				$data['ecommerceStep'] = $allowedEvents[$this->request->get['ecommerceEvent']];
				} else {
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
				$this->response->setOutput('');
			}
			
			$data['products'] = array();
			
			$products = array_reverse($this->cart->getProducts());
			
			foreach ($products as $product) {
				$real_product = $this->model_catalog_product->getProduct($product['product_id']);
				
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 140, 140);
					} else {
					$image = '';
				}
				
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
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
					);
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $currency);
					} else {
					$price = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $currency);
					} else {
					$total = false;
				}
				
				$data['products'][] = array(
				'key'       	=> $product['cart_id'],
				'id'       		=> $product['product_id'],
				'ecommerceData' => $real_product['ecommerceData'],
				'thumb'     	=> $image,
				'name'      	=> $product['name'],
				'model'     	=> $product['model'],
				'option'   		=> $option_data,
				'recurring'		=> ($product['recurring'] ? $product['recurring']['name'] : ''),
				'manufacturer'	=> $product['manufacturer'],
				'quantity'  	=> $product['quantity'],
				'stock'   	 	=> $this->config->get('config_stock_checkout'),
				'minimum'    	=> $product['minimum'],
				'maximum'    	=> $product['maximum'],
				'price'     	=> $price,
				'total'     	=> $total,
				'href'      	=> $this->url->link('product/product', 'product_id=' . $product['product_id'], true)
				);				
			}
			
			
			
			$this->response->setOutput($this->load->view('structured/ecommerce_checkout', $data));
			
			
			
		}
	}	