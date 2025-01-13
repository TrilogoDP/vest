<?php
	class ControllerCheckoutCart extends Controller {
		public function index() {
			$this->load->language('checkout/cart');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
			);
			
			$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/cart'),
			'text' => $this->language->get('heading_title')
			);
			
			if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
				$data['heading_title'] = $this->language->get('heading_title');
				
				$data['text_recurring_item'] = $this->language->get('text_recurring_item');
				$data['text_next'] = $this->language->get('text_next');
				$data['text_next_choice'] = $this->language->get('text_next_choice');
				
				$data['column_image'] = $this->language->get('column_image');
				$data['column_name'] = $this->language->get('column_name');
				$data['column_model'] = $this->language->get('column_model');
				$data['column_quantity'] = $this->language->get('column_quantity');
				$data['column_price'] = $this->language->get('column_price');
				$data['column_total'] = $this->language->get('column_total');
				
				$data['button_update'] = $this->language->get('button_update');
				$data['button_remove'] = $this->language->get('button_remove');
				$data['button_shopping'] = $this->language->get('button_shopping');
				$data['button_checkout'] = $this->language->get('button_checkout');

        // oct_advanced_options_settings start
        $data['oct_text_option_sku'] = $this->language->get('oct_text_option_sku');
        $data['oct_text_option_model'] = $this->language->get('oct_text_option_model');
        $data['oct_advanced_options_settings_data'] = $this->config->get('oct_advanced_options_settings_data');
        // oct_advanced_options_settings end
      
				
				if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
					$data['error_warning'] = $this->language->get('error_stock');
					} elseif (isset($this->session->data['error'])) {
					$data['error_warning'] = $this->session->data['error'];
					
					unset($this->session->data['error']);
					} else {
					$data['error_warning'] = '';
				}
				
				if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
					$data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
					} else {
					$data['attention'] = '';
				}
				
				if (isset($this->session->data['success'])) {
					$data['success'] = $this->session->data['success'];
					
					unset($this->session->data['success']);
					} else {
					$data['success'] = '';
				}
				
				$data['action'] = $this->url->link('checkout/cart/edit', '', true);
				
				if ($this->config->get('config_cart_weight')) {
					$data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
					} else {
					$data['weight'] = '';
				}
				
				$this->load->model('tool/image');
				$this->load->model('tool/upload');
				
				$data['products'] = array();
				
				$products = $this->cart->getProducts();
				
				foreach ($products as $product) {
					$product_total = 0;
					
					foreach ($products as $product_2) {
						if ($product_2['product_id'] == $product['product_id']) {
							$product_total += $product_2['quantity'];
						}
					}
					
					if ($product['minimum'] > $product_total) {
						$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
					}
					
					if ($product['image']) {
						$image = $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
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

        // oct_advanced_options_settings start
        'sku'     => (isset($option['sku'])) ? $option['sku'] : '',
        'model'   => (isset($option['model'])) ? $option['model'] : '',
        'oct_quantity_value'  => (isset($option['oct_quantity_value'])) ? $option['oct_quantity_value'] : '',
        // oct_advanced_options_settings end
      
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
						);
					}
					
					// Display prices
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
						
						$price = $this->currency->format($unit_price, $this->session->data['currency']);
						$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
						} else {
						$price = false;
						$total = false;
					}
					
					$recurring = '';
					
					if ($product['recurring']) {
						$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year'),
						);
						
						if ($product['recurring']['trial']) {
							$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
						}
						
						if ($product['recurring']['duration']) {
							$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
							} else {
							$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
						}
					}
					
					$data['products'][] = array(
					'cart_id'   => $product['cart_id'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'recurring' => $recurring,
					'quantity'  => $product['quantity'],
					'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'     => $price,
					'total'     => $total,
					'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
					);
				}
				
				// Gift Voucher
				$data['vouchers'] = array();
				
				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $key => $voucher) {
						$data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
						);
					}
				}
				
				// Totals
				$this->load->model('extension/extension');
				
				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;
				
				// Because __call can not keep var references so we put them into an array. 			
				$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
				);
				
				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
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
					
					$sort_order = array();
					
					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
					
					array_multisort($sort_order, SORT_ASC, $totals);
				}
				
				$data['totals'] = array();
				
				foreach ($totals as $total) {
					$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
					);
				}
				
				$data['continue'] = $this->url->link('common/home');
				
				$data['checkout'] = $this->url->link('checkout/checkout', '', true);
				
				$this->load->model('extension/extension');
				
				$data['modules'] = array();
				
				$files = glob(DIR_APPLICATION . '/controller/extension/total/*.php');
				
				if ($files) {
					foreach ($files as $file) {
						$result = $this->load->controller('extension/total/' . basename($file, '.php'));
						
						if ($result) {
							$data['modules'][] = $result;
						}
					}
				}
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				
				$this->response->setOutput($this->load->view('checkout/cart', $data));
				} else {
				$data['heading_title'] = $this->language->get('heading_title');
				
				$data['text_error'] = $this->language->get('text_empty');
				
				$data['button_continue'] = $this->language->get('button_continue');
				
				$data['continue'] = $this->url->link('common/home');
				
				unset($this->session->data['success']);
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				
				$this->response->setOutput($this->load->view('error/not_found', $data));
			}
		}
		
		public function add() {
			$this->load->language('checkout/cart');
			
			$json = array();
			
			if (isset($this->request->post['product_id'])) {
				$product_id = (int)$this->request->post['product_id'];
				} else {
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if (isset($this->request->post['quantity']) && ((int)$this->request->post['quantity'] >= $product_info['minimum'])) {
					$quantity = (int)$this->request->post['quantity'];
					} else {
					$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
				}
				
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
					} else {
					$option = array();
				}
				
				if (isset($this->request->post['product_addon'])) {
					$product_addon = array_filter($this->request->post['product_addon']);
					} else {
					$product_addon = array();
				}
				
				$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
				
				foreach ($product_options as $product_option) {
					
					$product_option_value_data = array();
					
					$count_options_in_stock = 0;
					foreach ($product_option['product_option_value'] as $option_value) {
						if ($option_value['quantity'] > 0){
							$count_options_in_stock++;
						}
					}
					unset($option_value);
					
					foreach ($product_option['product_option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] >= 0)) {							
							$selected = false;					
							
							if (count($product_option['product_option_value']) == 1){
								$selected = $option_value['product_option_value_id'];
								break;								
							}		
							
							if ($count_options_in_stock == 1){
								if ($option_value['quantity'] > 0){
									$selected = $option_value['product_option_value_id'];
									break;
								}
							}
						}
					}				
					
					if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
						
						if ($selected){
							$option[$product_option['product_option_id']] = $selected;
							} else {					
							$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), mb_strtolower($product_option['name']));
						}
					}
				}
				
				if (isset($this->request->post['recurring_id'])) {
					$recurring_id = $this->request->post['recurring_id'];
					} else {
					$recurring_id = 0;
				}
				
				$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);
				
				if ($recurrings) {
					$recurring_ids = array();
					
					foreach ($recurrings as $recurring) {
						$recurring_ids[] = $recurring['recurring_id'];
					}
					
					if (!in_array($recurring_id, $recurring_ids)) {
						$json['error']['recurring'] = $this->language->get('error_recurring_required');
					}
				}
				
				if (!$json) {										
					if(isset($this->session->data['cart'])) {
				$oldCart = $this->session->data['cart']; 
			}
			$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);
					
					foreach ($product_addon as $product_addon_id){
						//check if exists
						if ($addon_product = $this->model_catalog_product->getProduct($product_addon_id)){
							if(isset($this->session->data['cart'])) {
				$oldCart = $this->session->data['cart']; 
			}
			$this->cart->add($addon_product['product_id'], 1, array(), 0, $product_info['product_id']);
						}
					}
					
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
					
					$this->load->model('setting/setting');
					$giftTeaser = $this->model_setting_setting->getSetting('giftteaser', $this->config->get('config_store_id'));
					if (empty($giftTeaser['giftteaser']['Enabled']) || $giftTeaser['giftteaser']['Enabled'] == 'no') { } else {
						$this->load->language('extension/module/giftteaser');
						$addition = '';
						
						foreach ($this->cart->getProducts() as $key => $val) {
							if (!empty($val['gift_teaser']) && $val['gift_teaser']==true ) {
								if (!isset($this->session->data['success_addition'])) {
									$addition = $this->language->get('gift_added_to_cart');
								}
							}
						}	
						
						$json['success'] .= $addition;	
					}
					
					// Unset all shipping and payment methods
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
					
					// Totals
					$this->load->model('extension/extension');
					
					$totals = array();
					$taxes = $this->cart->getTaxes();
					$total = 0;
					
					// Because __call can not keep var references so we put them into an array. 			
					$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
					);
					
					// Display prices
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
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
						
						$sort_order = array();
						
						foreach ($totals as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
						
						array_multisort($sort_order, SORT_ASC, $totals);
					}
					
					
        // oct_techstore start
        $json['total'] = sprintf($this->language->get('text_cart_items'), $this->currency->format($total, $this->session->data['currency']), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
        // oct_techstore end
      
					} else {
					
        // popup_product_options start
        $oct_popup_product_options_data = $this->config->get('oct_popup_product_options_data');
        
        if (isset($oct_popup_product_options_data['status']) && $oct_popup_product_options_data['status']) {
            //$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
        } else {
            $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
        }
        // popup_product_options end
      
				}
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		
		public function edit() {
			$this->load->language('checkout/cart');
			
			$json = array();
			
			// Update
			if (!empty($this->request->post['quantity'])) {
				foreach ($this->request->post['quantity'] as $key => $value) {
					$this->cart->update($key, $value);
				}
				
				$this->session->data['success'] = $this->language->get('text_remove');
				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['reward']);
$this->checkGifts();
				
				$this->response->redirect($this->url->link('checkout/cart'));
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		
		public function giftTeaserOptions() {
			$this->language->load('extension/module/giftteaser');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$this->document->addStyle('catalog/view/theme/default/stylesheet/giftteaser.css');
			$product_info= $this->model_catalog_product->getProduct($this->request->get['product_id']);
			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
			if ($product_info['image']) {
				$image = $this->model_tool_image->resize($product_info['image'], 80, 80);
				} else {
				$image = false;
			}
			
			if ($this->config->get('config_review_status')) {
				$rating = (int)$product_info['rating'];
				} else {
				$rating = false;
			}
			
			$product_options = $this->model_catalog_product->getProductOptions($product_info['product_id']);
			$data['options'] = array();
			
			foreach ($product_options as $option) { 
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') { 
					$option_value_data = array();
					
					foreach ($option['product_option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
								} else {
								$price = false;
							}
							
							$option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							);
						}
					}
					
					$data['options'][] = array(
					'product_option_id' => $option['product_option_id'],
					'option_id'         => $option['option_id'],
					'name'              => $option['name'],
					'type'              => $option['type'],
					'option_value'      => $option_value_data,
					'required'          => $option['required']
					);                  
					} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$data['options'][] = array(
					'product_option_id' => $option['product_option_id'],
					'option_id'         => $option['option_id'],
					'name'              => $option['name'],
					'type'              => $option['type'],
					'option_value'      => $option['option_value'],
					'required'          => $option['required']
					);                      
				}
			}
			$data['gift'] = array(
			'product_id' => $product_info['product_id'],
			'thumb'      => $image,
			'name'       => $product_info['name'],
			'rating'     => $rating,
			'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
			'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
			'options' => $data['options']
			);
			$data['text_select'] = $this->language->get('text_select');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_option_heading'] =  $this->language->get('gift_added_to_cart');
			$data['heading_title'] = $this->language->get('heading_title');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['Continue'] = $this->language->get('Continue');
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$data['data']['giftTeaser'] = str_replace('http', 'https', $this->config->get('giftteaser'));
				} else {
				$data['data']['giftTeaser'] = $this->config->get('giftteaser');
			}
			
			
			$this->response->setOutput($this->load->view('extension/module/giftteaser/giftteaser_options', $data));
			
		}
		
		public function checkGifts() { 
			$this->load->model('extension/module/giftteaser');
			$json = $this->model_extension_module_giftteaser->checkGifts();
			
			$this->response->setOutput(json_encode($json));   
			
		}
		
		public function GiftAdd() { 
			$this->language->load('checkout/cart');
			$json = array();
			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
				} else {
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {            
				if (isset($this->request->post['quantity'])) {
					$quantity = $this->request->post['quantity'];
					} else {
					$quantity = 1;
				}
				
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
					} else {
					$option = array();  
				}
				
				if (isset($this->request->post['recurring_id'])) {
					$recurring_id = $this->request->post['recurring_id'];
					} else {
					$recurring_id = 0;
				}
				
				$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
				
				foreach ($product_options as $product_option) {
					if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
						$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
					}
				}
				
				$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);
				
				if ($recurrings) {
					$recurring_ids = array();
					
					foreach ($recurrings as $recurring) {
						$recurring_ids[] = $recurring['recurring_id'];
					}
					
					if (!in_array($recurring_id, $recurring_ids)) {
						$json['error']['recurring'] = $this->language->get('error_recurring_required');
					}
				}
				
				if (!$json) { 
					$this->cart->prepareGiftTeaserArg($gift = true);
					
					if(isset($this->session->data['cart'])) {
				$oldCart = $this->session->data['cart']; 
			}
			$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
					
					$this->load->model('extension/module/giftteaser');
					$this->load->model('setting/setting');
					
					$giftTeaser = $this->model_setting_setting->getSetting('giftteaser', $this->config->get('config_store_id'));
					
					if (empty($giftTeaser['giftteaser']['Enabled']) || $giftTeaser['giftteaser']['Enabled'] == 'no') { } else {
						$this->load->language('extension/module/giftteaser');
						$prods = $this->cart->getProducts(); 
						$addition = '';
						
						foreach ($prods as $prod) {
							if ($prod['gift_teaser']==true && !isset($this->session->data['gift_teaser_success'])) {
								$addition = $this->language->get('gift_added_to_cart');
								$this->session->data['gift_teaser_success'] = true;
							}
						}
						if(isset($json['success'])) {
							$json['success'] .= $addition;
						}   
					}
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
					
					} else {
					
        // popup_product_options start
        $oct_popup_product_options_data = $this->config->get('oct_popup_product_options_data');
        
        if (isset($oct_popup_product_options_data['status']) && $oct_popup_product_options_data['status']) {
            //$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
        } else {
            $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
        }
        // popup_product_options end
      
				}
			}
			
			$this->response->setOutput(json_encode($json));       
		}
		
		public function remove() {
			$this->load->language('checkout/cart');
			
			$json = array();
			
			// Remove
			if (isset($this->request->post['key'])) {
				$this->cart->remove($this->request->post['key']);
				
				unset($this->session->data['vouchers'][$this->request->post['key']]);
				
				$json['success'] = $this->language->get('text_remove');
				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['reward']);
$this->checkGifts();
				
				// Totals
				$this->load->model('extension/extension');
				
				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;
				
				// Because __call can not keep var references so we put them into an array. 			
				$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
				);
				
				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
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
					
					$sort_order = array();
					
					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
					
					array_multisort($sort_order, SORT_ASC, $totals);
				}
				
				
        // oct_techstore start
        $json['total'] = sprintf($this->language->get('text_cart_items'), $this->currency->format($total, $this->session->data['currency']), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
        // oct_techstore end
      
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}
