<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	/**************************************************************/
	
	class ControllerExtensionModuleOctPopupProductOptions extends Controller {
		public function index() {
			$data = [];
			
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$this->load->language('extension/module/oct_popup_product_options');
			
			$oct_popup_product_options_data         = (array) $this->config->get('oct_popup_product_options_data');
			$data['oct_popup_product_options_data'] = $oct_popup_product_options_data;
			
			$data['oct_advanced_options_settings_data'] = $this->config->get('oct_advanced_options_settings_data');
			
			if (isset($this->request->get['product_id'])) {
				$product_id = (int) $this->request->get['product_id'];
				} else {
				$product_id = 0;
			}
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			$data['product_id']   = $product_id;
			$data['product_name'] = $product_info['name'];
			
			if ($product_info) {
				$data['heading_title']            = $this->language->get('heading_title');
				$data['button_shopping']          = $this->language->get('button_shopping');
				$data['button_checkout']          = $this->language->get('button_checkout');
				$data['button_upload']            = $this->language->get('button_upload');
				$data['text_stock']               = $this->language->get('text_stock');
				$data['entry_quantity']           = $this->language->get('entry_quantity');
				$data['text_minimum']             = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
				$data['text_select']              = $this->language->get('text_select');
				$data['text_option']              = $this->language->get('text_option');
				$data['text_loading']             = $this->language->get('text_loading');
				$data['text_option_disable']      = $this->language->get('text_option_disable');
				$data['text_col_option_name']     = $this->language->get('text_col_option_name');
				$data['text_col_option_image']    = $this->language->get('text_col_option_image');
				$data['text_col_option_sku']      = $this->language->get('text_col_option_sku');
				$data['text_col_option_model']    = $this->language->get('text_col_option_model');
				$data['text_col_option_price']    = $this->language->get('text_col_option_price');
				$data['text_col_option_quantity'] = $this->language->get('text_col_option_quantity');

				$this->load->model('tool/image');
				
				$image_width  = ($oct_popup_purchase_data['image_width']) ? $oct_popup_purchase_data['image_width'] : '152';
				$image_height = ($oct_popup_purchase_data['image_height']) ? $oct_popup_purchase_data['image_height'] : '152';
				
				if ($product_info['image']) {
					$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $image_width, $image_height);
					} else {
					$data['thumb'] = $this->model_tool_image->resize("placeholder.png", $image_width, $image_height);
				}
				
				if ($product_info['quantity'] <= 0) {
					$data['stock_warning'] = $product_info['stock_status'];
					} else {
					$data['stock_warning'] = '';
				}
				
				if ($product_info['minimum']) {
					$data['minimum'] = $product_info['minimum'];
					} else {
					$data['minimum'] = 1;
				}
				
				$data['model'] = $product_info['model'];
				$data['sku']   = $product_info['sku'];
				
				$data['products'] = [];				
				$results 		= $this->model_catalog_product->getProductRelated($this->request->get['product_id']);																				
				$boughtWith 	= $this->model_catalog_product->getProductBoughtWith($this->request->get['product_id']);							
				
				$bw_sort_order = [];
				
				foreach ($boughtWith as $key => $value) {
					$bw_sort_order[$key] = $value['sort_order'];
				}
				
				array_multisort($bw_sort_order, SORT_DESC, $boughtWith);
				
				$boughtWithArrBeforeRel = [];
				$boughtWithArrAfterRel 	= [];
				foreach ($boughtWith as $boughtWithProduct){
					if ($boughtWithProduct['sort_order'] > 1){
						$boughtWithArrBeforeRel[$boughtWithProduct['product_id']] = $boughtWithProduct;
						} else {
						$boughtWithArrAfterRel[$boughtWithProduct['product_id']] = $boughtWithProduct;
					}
				}				
				
				$relatedDistinct = [];
				foreach ($results as $result){
					if (isset($boughtWithArrBeforeRel[$result['product_id']])){						
						} elseif (isset($boughtWithArrAfterRel[$result['product_id']])){
						$relatedDistinct[$result['product_id']] = $result;
						unset($boughtWithArrAfterRel[$result['product_id']]);
						} else {						
						$relatedDistinct[$result['product_id']] = $result;
					}
				}								
				
				$results = [];
				foreach ($boughtWithArrBeforeRel as $p){
					$results[] = $p;
				}
				foreach ($relatedDistinct as $p){
					$results[] = $p;
				}
				foreach ($boughtWithArrAfterRel as $p){
					$results[] = $p;
				}
				
				$costLessThanMain = [];
				$costMoreThanMain = [];
				
				foreach ($results as $result){				
					if ($result['price'] <= $product_info['price']){
						$costLessThanMain[] = $result;
						} else {
						$costMoreThanMain[] = $result;
					}
				}
				unset($result);
				
				$results = [];
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
					
					$data['products'][] = array(
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
				
				$data['options'] = [];
				
				
				foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {						
					$product_option_value_data = [];
					
					$count_options_in_stock = 0;
					foreach ($option['product_option_value'] as $option_value) {
						if ($option_value['quantity'] > 0){
							$count_options_in_stock++;
						}
					}
					unset($option_value);
					
					foreach ($option['product_option_value'] as $option_value) {
						if (!$option_value['subtract'] || ($option_value['quantity'] >= 0)) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
								} else {
								$price = false;
							}
							
							$selected = false;					
							
							if (count($option['product_option_value']) == 1){
								$selected = true;
							}		
							
							if ($count_options_in_stock == 1){
								if ($option_value['quantity'] > 0){
									$selected = true;
								}
							}
							
							$product_option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'quantity_status' => ($option_value['quantity'] <= 0) ? false : true,
                            'sku' => (isset($option_value['sku']) && $option_value['sku']) ? $option_value['sku'] : ($product_info['sku'] ? $product_info['sku'] : ''),
                            'model' => (isset($option_value['model']) && $option_value['model']) ? $option_value['model'] : $product_info['model'],
                            'o_v_image' => (isset($option_value['o_v_image']) && $option_value['o_v_image']) ? $this->model_tool_image->resize($option_value['o_v_image'], 50, 50) : $this->model_tool_image->resize("no_image.jpg", 50, 50),
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
							'selected'  => $selected,
                            'image' => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
                            'price' => $price,
                            'price_prefix' => $option_value['price_prefix']
							);
						}
					}
					
					$data['options'][] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => strpos($this->language->get('text_select_option'), '%s')?sprintf($this->language->get('text_select_option'), mb_strtolower($option['name'])):$option['name'],
                    'type' => $option['type'],
                    'value' => $option['value'],
                    'required' => $option['required']
					);
				}
				
				$data['recurrings'] = $this->model_catalog_product->getProfiles($product_id);
				
				$this->response->setOutput($this->load->view('extension/module/oct_popup_product_options', $data));
				} else {
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}
		
		public function add() {
			$this->load->language('checkout/cart');
			
			$json = [];
			
			if (isset($this->request->post['product_id'])) {
				$product_id = (int) $this->request->post['product_id'];
				} else {
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) {
				if (isset($this->request->post['quantity']) && ((int) $this->request->post['quantity'] >= $product_info['minimum'])) {
					$quantity = (int) $this->request->post['quantity'];
					} else {
					$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
				}
				
				if (isset($this->request->post['option'])) {
					$option = array_filter($this->request->post['option']);
					} else {
					$option = [];
				}
				
				if (isset($this->request->post['product_addon'])) {
					$product_addon = array_filter($this->request->post['product_addon']);
					} else {
					$product_addon = [];
				}
				
				$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
				
				foreach ($product_options as $product_option) {
					if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
						$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
					}
				}
				
				if (isset($this->request->post['recurring_id'])) {
					$recurring_id = $this->request->post['recurring_id'];
					} else {
					$recurring_id = 0;
				}
				
				$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);
				
				if ($recurrings) {
					$recurring_ids = [];
					
					foreach ($recurrings as $recurring) {
						$recurring_ids[] = $recurring['recurring_id'];
					}
					
					if (!in_array($recurring_id, $recurring_ids)) {
						$json['error']['recurring'] = $this->language->get('error_recurring_required');
					}
				}
				
				if (!$json) {
					$this->cart->add($this->request->post['product_id'], $quantity, $option, $recurring_id);
					
					foreach ($product_addon as $product_addon_id){
						//check if exists
						if ($addon_product = $this->model_catalog_product->getProduct($product_addon_id)){
							$this->cart->add($addon_product['product_id'], 1, array(), 0, $product_info['product_id']);
						}
					}
					
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
					
					// Unset all shipping and payment methods
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
					
					// Totals
					$this->load->model('extension/extension');
					
					$totals = [];
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
						$sort_order = [];
						
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
						
						$sort_order = [];
						
						foreach ($totals as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}
						
						array_multisort($sort_order, SORT_ASC, $totals);
					}
					
					$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
					$json['total']   = sprintf($this->language->get('text_cart_items'), $this->currency->format($total, $this->session->data['currency']), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0));
				}
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}		