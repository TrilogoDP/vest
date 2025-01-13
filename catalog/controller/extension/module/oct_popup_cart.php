<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	/**************************************************************/
	
	class ControllerExtensionModuleOctPopupCart extends Controller {
		public function index() {
			$data = array();
			
			$this->load->language('extension/module/oct_popup_cart');
			
			$data['text_recurring_item'] = $this->language->get('text_recurring_item');
			$data['text_next']           = $this->language->get('text_next');
			$data['text_next_choice']    = $this->language->get('text_next_choice');
			$data['text_quantity']       = $this->language->get('text_quantity');
			$data['button_shopping']     = $this->language->get('button_shopping');
			$data['button_checkout']     = $this->language->get('button_checkout');
			$data['button_addon']     	 = $this->language->get('button_addon');
			$data['empty']               = $this->language->get('text_empty');
			$data['column_image']        = $this->language->get('column_image');
			$data['column_name']         = $this->language->get('column_name');
			$data['column_quantity']     = $this->language->get('column_quantity');
			$data['column_price']        = $this->language->get('column_price');
			
			if (isset($this->request->request['remove'])) {
				$this->cart->remove($this->request->request['remove']);
				unset($this->session->data['vouchers'][$this->request->request['remove']]);
			}
			
			if (isset($this->request->request['update'])) {
				$this->cart->update($this->request->request['update'], $this->request->request['quantity']);
			}
			
			if (isset($this->request->request['add'])) {
				$this->cart->add($this->request->request['add'], $this->request->request['quantity']);
			}
			
			if (isset($this->request->request['addon'])) {
				$this->cart->add($this->request->request['addon_id'], 1, array(), 0, $this->request->request['product_id']);
			}
			
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
			
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
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
					$image = $this->model_tool_image->resize($product['image'], 70, 95);
					} else {
					$image = $this->model_tool_image->resize("placeholder.png", 70, 95);
				}
				
				/*
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

					if ($option['image']) {
						$option_thumb = $this->model_tool_image->resize($option['image'], 70, 95);
					 }else {
						$option_thumb = false;
					}
					
					$option_data[] = array(
                    'name' => $option['name'],
					'thumb' => $option_thumb,
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
				*/

				$option_data = array();

				$product_id = $product['product_id'];
				$options = $product['option']; // Массив выбранных опций

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
			
					if ($option['image']) {
						$option_thumb = $this->model_tool_image->resize($option['image'], 70, 95);
					} else {
						$option_thumb = false;
					}

					$option_id = $option['option_id'];
       				$option_value_id = $option['option_value_id'];

					// Выполняем запрос для получения остатка
					$query = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product_option_value 
					WHERE product_id = '" . (int)$product_id . "' 
					AND option_id = '" . (int)$option_id . "' 
					AND option_value_id = '" . (int)$option_value_id . "'");

					if ($query->num_rows) {
						$option_quantity = $query->row['quantity'];
						
					} else {
						$option_quantity = 0;
					}
			
					// Проверка количества опций на складе
					if ($option_quantity < $product['quantity']) {
						$data['error_option_stock'] = sprintf($this->language->get('error_option_stock'), $product['name'], $option['name']);
					} else {
						$data['error_option_stock'] = '';
					}
					$option_data[] = array(
						'name' => $option['name'],
						'thumb' => $option_thumb,
						'quantity' => $option_quantity,
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$p_price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);	
					$p_price_numeric = $product['price'];
					} else {
					$p_price = false;
				}
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$p_total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']);
					} else {
					$p_total = false;
				}
				
				$p_old_total = false;
				$p_old_price = false;
				if ($product['old_price']){
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$p_old_price = $this->currency->format($this->tax->calculate($product['old_price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$p_old_price = false;
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$p_old_total = $this->currency->format($this->tax->calculate($product['old_price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']);
						} else {
						$p_old_total = false;
					}
				}
				
				$recurring = '';
				
				if ($product['recurring']) {
					$frequencies = array(
                    'day' => $this->language->get('text_day'),
                    'week' => $this->language->get('text_week'),
                    'semi_month' => $this->language->get('text_semi_month'),
                    'month' => $this->language->get('text_month'),
                    'year' => $this->language->get('text_year')
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
                'key' => $product['cart_id'],
                'product_id' => $product['product_id'],
                'thumb' => $image,
                'name' => $product['name'],
                'model' => $product['model'],
                'option' => $option_data,
                'recurring' => $recurring,
                'quantity' => $product['quantity'],
                'stock' => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                'reward' => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                'price' => $p_price,
				'old_price' => $p_old_price,
                'total' => $p_total,
				'old_total' => $p_old_total,
                'href' => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}
			
			$data['addons'] = array();
			if (count($data['products']) == 1){				
				$product_id = $data['products'][0]['product_id'];
				$data['main_product_id'] = $product_id;
				
				$results = $this->model_catalog_product->getProductRelated($product_id);																				
				$boughtWith = $this->model_catalog_product->getProductBoughtWith($product_id);							
				
				$bw_sort_order = array();
				
				foreach ($boughtWith as $key => $value) {
					$bw_sort_order[$key] = $value['sort_order'];
				}
				
				//сортируем по количеству покупок
				array_multisort($bw_sort_order, SORT_DESC, $boughtWith);
				
				//Разбиваем на 2 массива, те которые идут до и которые идут после
				$boughtWithArrBeforeRel = array();
				$boughtWithArrAfterRel = array();
				foreach ($boughtWith as $boughtWithProduct){
					if ($boughtWithProduct['sort_order'] > 1){
						$boughtWithArrBeforeRel[$boughtWithProduct['product_id']] = $boughtWithProduct;
						} else {
						$boughtWithArrAfterRel[$boughtWithProduct['product_id']] = $boughtWithProduct;
					}
				}				
				
				//Уникализируем массив ручных, с логикой, что товар может входить в массивы уже купленных))
				$relatedDistinct = array();
				foreach ($results as $result){
					if (isset($boughtWithArrBeforeRel[$result['product_id']])){
						//pass, ничего не делаем, он будет выше и так
						} elseif (isset($boughtWithArrAfterRel[$result['product_id']])){
						//его таки купили 1 раз, но мы хотим показать его все-таки выше
						$relatedDistinct[$result['product_id']] = $result;
						unset($boughtWithArrAfterRel[$result['product_id']]);
						} else {
						//не покупали ниразу
						$relatedDistinct[$result['product_id']] = $result;
					}
				}								
				
				
				//и лепим все в один массив
				$results = array();
				foreach ($boughtWithArrBeforeRel as $p){
					$results[] = $p;
				}
				foreach ($relatedDistinct as $p){
					$results[] = $p;
				}
				foreach ($boughtWithArrAfterRel as $p){
					$results[] = $p;
				}
				
				$costLessThanMain = array();
				$costMoreThanMain = array();
				
				foreach ($results as $result){				
					if ($result['price'] <= $p_price_numeric){
						$costLessThanMain[] = $result;
						} else {
						$costMoreThanMain[] = $result;
					}
				}
				unset($result);
				
				$results = array();
				foreach ($costLessThanMain as &$p){
					$p['can_do_addon_discount'] = true;
					$results[] = $p;
				}
				foreach ($costMoreThanMain as &$p){
					$p['can_do_addon_discount'] = false;
					$results[] = $p;
				}								
				
				unset($result);
				
				foreach ($results as $result) {
					if ($result['can_do_addon_discount'] && !(float)$result['special']){
						$data['text_related'] = $this->language->get('text_related_havediscount');
						break;
					}
				}
				unset($result);
				
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
						$image_addon = $this->model_tool_image->resize($result['image'], 50, 50);
						} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
						$image_addon = $this->model_tool_image->resize($result['image'], 50, 50);
					}
					
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$price = false;
					}
					
					if ($result['can_do_addon_discount'] && !(float)$result['special'] && $mainCategoryAddonDiscount = (int)$this->model_catalog_product->getProductMainCategoryAddonDiscount($result['product_id'])){
						$result['special'] = $result['price'] - (($result['price'] / 100) * $mainCategoryAddonDiscount);
					}
					
					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$special = false;
					}
					
					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
						} else {
						$tax = false;
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'];
						} else {
						$rating = false;
					}
					
					$data['addons'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'thumb_addon' => $image_addon,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'can_do_addon_discount' => $result['can_do_addon_discount'],
					'special'     => $special,
					'tax'         => $tax,
					'sort_order'  => $result['sort_order'],
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}					
				//PRODUCT ADDONS
				
				
			}
			
			// Gift Voucher
			$data['vouchers'] = array();
			
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$data['vouchers'][] = array(
                    'key' => $key,
                    'description' => $voucher['description'],
                    'amount' => $this->currency->format($voucher['amount'], $this->session->data['currency']),
                    'remove' => $this->url->link('checkout/cart', 'remove=' . $key)
					);
				}
			}
			
			// Totals
			$this->load->model('extension/extension');
			
			$totals = array();
			$taxes  = $this->cart->getTaxes();
			$total  = 0;
			
			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
            'totals' => &$totals,
            'taxes' => &$taxes,
            'total' => &$total
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
			
			foreach ($totals as $total_value) {
				if ($total_value['code'] == 'total') {
					$data['totals'][] = array(
                    'title' => $total_value['title'],
                    'text' => $this->currency->format($total_value['value'], $this->session->data['currency'])
					);
				}
			}
			
			$data['checkout_link']   = $this->url->link('checkout/checkout');
			$data['heading_title']   = $this->language->get('heading_title');
			//$data['text_cart_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
			$count = $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0);
			$productWord = $this->getProductWord($count);
			$totalFormatted = $this->currency->format($total, $this->session->data['currency']);
			$data['text_cart_items'] = sprintf($this->language->get('text_items'), $count, $productWord, $totalFormatted);		

			$this->response->setOutput($this->load->view('extension/module/oct_popup_cart', $data));
		}
		
		public function getProductWord($count) {
			$this->load->language('extension/module/oct_popup_cart');

			$count = abs($count) % 100;
			$num = $count % 10;


			if ($count > 10 && $count < 20) {
				return $this->language->get('num_items');
			}
			if ($num > 1 && $num < 5) {
				return $this->language->get('num_itemes');
			}
			if ($num == 1) {
				return $this->language->get('num_item');
			}
			return $this->language->get('num_items');
		}

		public function add_addon(){
			$json = array();
			
			
			
			
			
			
			
			
			
			
			
		}
		
		public function status_cart() {
			$json = array();
			
			$this->load->language('extension/module/oct_popup_cart');
			
			// Totals
			$this->load->model('extension/extension');
			
			$totals = array();
			$taxes  = $this->cart->getTaxes();
			$total  = 0;
			
			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
            'totals' => &$totals,
            'taxes' => &$taxes,
            'total' => &$total
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
			
			$json['total']           = sprintf($this->language->get('text_cart_items'), $this->currency->format($total, $this->session->data['currency']), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
			$json['text_items']      = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
			$json['text_cart_items'] = sprintf($this->language->get('text_cart_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}			