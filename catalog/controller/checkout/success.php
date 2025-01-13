<?php
class ControllerCheckoutSuccess extends Controller {


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


	public function index() {

        if (isset($this->session->data['order_id']) && (!empty($this->session->data['order_id']))) {
          $this->session->data['recent_order_id'] = $this->session->data['order_id'];
        }

        if (isset($this->session->data['guest']['firstname']) && (!empty($this->session->data['guest']['firstname']))) {
          $this->session->data['recent_firstname'] = $this->session->data['guest']['firstname'];
        } elseif ($this->customer->isLogged()) {
          $this->session->data['recent_firstname'] = $this->customer->getFirstName();
        } else {
          $this->session->data['recent_firstname'] = false;
        }
      
		$this->load->library('hobotix/BarcodeValidator');

		if (!isset($this->session->data['order_id']) && $_SERVER['REMOTE_ADDR'] == '31.43.104.37'){
			$this->session->data['order_id'] = 129319;
		}

		if (empty($this->session->data['recent_order_id'])){
			$this->session->data['recent_order_id'] = $this->session->data['order_id'];
		}

		$this->load->language('checkout/success');

		$data['currency_code'] = $this->config->get('config_currency');

			//***mf begin
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
				$this->cart->clear();

			// Add to activity log
				if ($this->config->get('config_customer_activity')) {
					$this->load->model('account/activity');

					if ($this->customer->isLogged()) {
						$activity_data = array(
							'customer_id' => $this->customer->getId(),
							'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
							'order_id'    => $this->session->data['order_id']
						);

						$this->model_account_activity->addActivity('order_account', $activity_data);
					} else {
						$activity_data = array(
							'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
							'order_id' => $this->session->data['order_id']
						);

						$this->model_account_activity->addActivity('order_guest', $activity_data);
					}
				}
				
				$data['order_id'] = $this->session->data['order_id'];
				$data['text_order_id'] = sprintf($this->language->get('text_order_id'), $data['order_id']);
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET analytics = '1' WHERE order_id = '" . (int)$this->session->data['order_id'] . "'");
				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['guest']);
				unset($this->session->data['comment']);
				unset($this->session->data['order_id']);
				unset($this->session->data['coupon']);
				unset($this->session->data['reward']);
				unset($this->session->data['voucher']);
				unset($this->session->data['vouchers']);
				unset($this->session->data['totals']);
			}
			
			
        // $this->document->setTitle($this->language->get('heading_title'));
      
			

        if (!empty($this->session->data['recent_order_id'])) {
          $this->document->setTitle(sprintf($this->language->get('heading_title_order'), $this->session->data['recent_order_id']));
        } else {
          $this->document->setTitle($this->language->get('heading_title'));
        }
        
        if (!empty($this->session->data['recent_order_id'])) {
          $data['heading_title'] = sprintf($this->language->get('heading_title_order'), $this->session->data['recent_order_id']);
        } else {
          $data['heading_title'] = $this->language->get('heading_title');
        }

        if (!empty($this->session->data['recent_order_id'])) {
          $this->load->model('checkout/order');

          $order_info = $this->model_checkout_order->getOrder($this->session->data['recent_order_id']);
          
          $order_history_comment = $this->db->query("SELECT comment FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_info['order_id'] . "'");

          if (isset($order_history_comment->row['comment']) && $order_history_comment->row['comment']) {
            $order_history_comment = nl2br($order_history_comment->row['comment']);
          } else {
            $order_history_comment = '';
          }
        }
      
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
				'text' => $this->language->get('text_success'),
				'href' => $this->url->link('checkout/success')
			);
			
			
        // $data['heading_title'] = $this->language->get('heading_title');
      
			
			if ($this->customer->isLogged()) {
				$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
			} else {
				$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
			}
			
			//***mf begin
			if(isset($this->request->get['type'])){
				$data['mf_type'] = $this->request->get['type'];
			}else{
				$data['mf_type'] = '';
			}
			$data['mf_heading_title'] = $this->language->get('mf_heading_title');
			$data['mf_heading'] = $this->language->get('mf_heading_title');
			$data['mf_text_pb'] = $this->language->get('mf_text_pb');
			$data['mf_text_np'] = $this->language->get('mf_text_np');
			$data['mf_text_online'] = $this->language->get('mf_text_online');
			//***mf end
			

        if (isset($this->session->data['recent_order_id'])) {
          if ($this->customer->isLogged()) {
            if (!empty($this->session->data['recent_firstname'])) {
              $data['text_message'] = sprintf($this->language->get('text_customer_ordervs_firstname'), $this->session->data['recent_firstname'], $this->session->data['recent_order_id'], $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'),  $this->url->link('information/contact'));
            } else {
              $data['text_message'] = sprintf($this->language->get('text_customer_order'), $this->session->data['recent_order_id'], $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'),  $this->url->link('information/contact'));
            }
          } else {
            if (!empty($this->session->data['recent_firstname'])) {
              $data['text_message'] = sprintf($this->language->get('text_guest_order_vs_firstname'), $this->session->data['recent_firstname'], $this->session->data['recent_order_id'], $this->url->link('information/contact'));
            } else {
              $data['text_message'] = sprintf($this->language->get('text_guest_order'), $this->session->data['recent_order_id'], $this->url->link('information/contact'));
            }
          }
        } else {
          if ($this->customer->isLogged()) {
            $data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
          } else {
            $data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
          }
        }

        if (isset($order_history_comment) && $order_history_comment) {
          $data['text_message'] .= '<hr/>'.$order_history_comment;
        }
      
			$data['button_continue'] = $this->language->get('button_continue');
			
			$data['continue'] = $this->url->link('common/home');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			$this->response->setOutput($this->load->view('common/success', $data));
		}
	}	