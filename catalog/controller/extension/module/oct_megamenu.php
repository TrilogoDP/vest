<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleOctMegamenu extends Controller {
		// @type: 1 - simple link, 2 - category, 3 - brand, 4 - product, 5 - information, 6 - login block, 7 - custom html block
	public function index() {
		
		if ($return_data = $this->cache->get('ControllerExtensionModuleOctMegamenuIndex' . $this->config->get('config_language_id'))){				
			return $return_data;
		}
		
		$this->load->language('extension/module/oct_megamenu');

		$this->load->model('extension/module/oct_megamenu');
		$this->load->model('tool/image');

		$oct_megamenu_data         = $this->config->get('oct_megamenu_data');
		$data['oct_megamenu_data'] = $oct_megamenu_data;

		$data['text_register']     = $this->language->get('text_register');
		$data['text_logout']       = $this->language->get('text_logout');
		$data['text_forgotten']    = $this->language->get('text_forgotten');
		$data['text_category']     = $this->language->get('text_category');
		$data['text_menu']         = $this->language->get('text_menu');
		$data['text_back']         = $this->language->get('text_back');
		$data['text_info']         = $this->language->get('text_info');
		$data['text_acc']          = $this->language->get('text_acc');
		$data['text_contacts']     = $this->language->get('text_contacts');
		$data['text_settings']     = $this->language->get('text_settings');
		$data['text_all_category'] = $this->language->get('text_all_category');
		$data['text_more'] = $this->language->get('text_more');

		$data['entry_email']    = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_login'] = $this->language->get('button_login');
		$data['action']       = $this->url->link('account/login', '', 'SSL');
		$data['forgotten']    = $this->url->link('account/forgotten', '', 'SSL');
		$data['register']     = $this->url->link('account/register', '', 'SSL');
		$data['logout']       = $this->url->link('account/logout', '', 'SSL');

		$data['login_status'] = $this->customer->isLogged() ? true : false;

		if (isset($this->request->post['ocmm_login_email'])) {
			$data['email'] = $this->request->post['ocmm_login_email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['ocmm_login_password'])) {
			$data['password'] = $this->request->post['ocmm_login_password'];
		} else {
			$data['password'] = '';
		}

		$data['items'] = [];

		$menu_items = $this->cache->get('octemplates1.megamenu.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . (int)WEBPACCEPTABLE);

		if (!$menu_items) {
			$results = $this->model_extension_module_oct_megamenu->getMegamenus();

			foreach ($results as $result) {

				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 35, 35);
				} else {
					$image = false;
				}

				$childrens = [];

				if ($result['item_type'] == 2) {
					$children_data = $this->model_extension_module_oct_megamenu->getMegamenuCategory($result['megamenu_id']);

					$this->load->model('catalog/category');

					foreach ($children_data as $category_id) {
						$category_info = $this->model_catalog_category->getCategory($category_id);

						if ($category_info) {
							if ($category_info['image']) {
								$category_image = $this->model_tool_image->resize($category_info['image'], $result['img_width'], $result['img_height']);						
							} else {
								$category_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
							}																

							if($image == false) $image = $category_image;

							$sub_categories = [];								
							if ($result['sub_categories']) {
								$category_children = $this->model_catalog_category->getCategories($category_id);

								foreach ($category_children as $child) {
									if ($child['image']) {
										$sub_category_image = $this->model_tool_image->resize($child['image'], $result['img_width'], $result['img_height']);
									} else {
										$sub_category_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);									
									}

									$sub_categories_l2 	= [];	
									$children_l2 		= $this->model_catalog_category->getCategories($child['category_id']);
									if ($children_l2){
										foreach ($children_l2 as $child_l2){
											$sub_categories_l3 	= [];	
											$children_l3 		= $this->model_catalog_category->getCategories($child_l2['category_id']);

											if ($children_l3){
												foreach ($children_l3 as $child_l3){
													$sub_categories_l3[] 	= [
														'name' 			=> $child_l3['name'],
														'sort_order' 	=> $child_l3['sort_order'],															
														'top_svg' 		=> html_entity_decode($child_l2['top_svg'], ENT_QUOTES, 'UTF-8'),
														'href' 			=> $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'] . '_' . $child_l2['category_id'] . '_' . $child_l3['category_id'])		
													];
												}

												$cs_sort_order_l3 = [];

												foreach ($sub_categories_l3 as $key => $value) {
													$cs_sort_order_l3[$key] = $value['sort_order'];
												}

												array_multisort($cs_sort_order_l3, SORT_ASC, $sub_categories_l3);
											}																			

											$sub_categories_l2[] 	= [
												'name' 			=> $child_l2['name'],
												'sort_order' 	=> $child_l2['sort_order'],
												'children' 		=> $sub_categories_l3,												
												'top_svg' 		=> html_entity_decode($child_l2['top_svg'], ENT_QUOTES, 'UTF-8'),
												'href' 			=> $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'] . '_' . $child_l2['category_id'])													
											];
										}

										$cs_sort_order_l2 = [];

										foreach ($sub_categories_l2 as $key => $value) {
											$cs_sort_order_l2[$key] = $value['sort_order'];
										}

										array_multisort($cs_sort_order_l2, SORT_ASC, $sub_categories_l2);
									}

									$ocfilter_pages = [];
									$ocfilter_pages_linked = $this->model_catalog_category->getCategoryOcFilterPagesForMegaMenu($child['category_id']);

									foreach ($ocfilter_pages_linked as $ocfilter_page){
										$ocfilter_pages[] = [
											'name' 			=> $ocfilter_page['megamenu_name']?$ocfilter_page['megamenu_name']:$ocfilter_page['title'],
											'sort_order' 	=> $ocfilter_page['sort_order'],
											'href' 			=> $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id']) . $ocfilter_page['params']
										];
									}

									$cs_sort_order = [];

									foreach ($ocfilter_pages as $key => $value) {
										$cs_sort_order[$key] = $value['sort_order'];
									}

									array_multisort($cs_sort_order, SORT_ASC, $ocfilter_pages);		

									$sub_categories[] = array(
										'name' 			=> $child['name'],
										'category_id'	=> $child['category_id'],
										'sort_order' 	=> $child['sort_order'],
										'what_is' 		=> 'subcategory',
										'children'		=> $sub_categories_l2,
										'ocfilters' 	=> $ocfilter_pages,
										'top_svg' 		=> html_entity_decode($child['top_svg'], ENT_QUOTES, 'UTF-8'),
										'thumb' 		=> ($result['show_img']) ? $sub_category_image : false,
										'href' 			=> $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'])
									);
								}
							}

							$cs_sort_order = [];

							foreach ($sub_categories as $key => $value) {
								$cs_sort_order[$key] = $value['sort_order'];
							}

							array_multisort($cs_sort_order, SORT_ASC, $sub_categories);
																			
							$childrens[] = array(
								'category_id' 	=> $category_info['category_id'],
								'sort_order' 	=> $category_info['sort_order'],
								'thumb' 		=> ($result['show_img']) ? $category_image : false,
								'name' 			=> $category_info['name'],
								'top_svg' 		=> html_entity_decode($category_info['top_svg'], ENT_QUOTES, 'UTF-8'),
								'children' 		=> $sub_categories,								
								'href' 			=> $this->url->link('product/category', 'path=' . $category_info['category_id'])
							);
						}
					}



					$c_sort_order = [];

					foreach ($childrens as $key => $value) {
						$c_sort_order[$key] = $value['sort_order'];
					}

					array_multisort($c_sort_order, SORT_ASC, $childrens);
				}
					
					if ($result['item_type'] == 3) {
						$children_data = $this->model_extension_module_oct_megamenu->getMegamenuManufacturer($result['megamenu_id']);
						
						$this->load->model('catalog/manufacturer');
						
						foreach ($children_data as $manufacturer_id) {
							$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
							
							if ($manufacturer_info) {
								if ($manufacturer_info['image']) {
									$manufacturer_image = $this->model_tool_image->resize($manufacturer_info['image'], $result['img_width'], $result['img_height']);
								} else {
									$manufacturer_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
								}
								
								$childrens[] = array(
									'manufacturer_id' => $manufacturer_info['manufacturer_id'],
									'sort_order' => $manufacturer_info['sort_order'],
									'thumb' => ($result['show_img']) ? $manufacturer_image : false,
									'name' => $manufacturer_info['name'],
									'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id'])
								);
							}
						}
						
						$m_sort_order = [];
						
						foreach ($childrens as $key => $value) {
							$m_sort_order[$key] = $value['name'];
						}
						
						array_multisort($m_sort_order, SORT_ASC, $childrens);
					}
					
					if ($result['item_type'] == 4) {
						$children_data = $this->model_extension_module_oct_megamenu->getMegamenuProduct($result['megamenu_id']);
						
						$this->load->model('catalog/product');
						
						foreach ($children_data as $product_id) {
							$product_info = $this->model_catalog_product->getProduct($product_id);
							
							if ($product_info) {
								if ($product_info['image']) {
									$product_image = $this->model_tool_image->resize($product_info['image'], $result['img_width'], $result['img_height']);
								} else {
									$product_image = $this->model_tool_image->resize('no-image.png', $result['img_width'], $result['img_height']);
								}
								
								if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
									$product_price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
								} else {
									$product_price = false;
								}
								
								if ((float) $product_info['special']) {
									$ptoduct_special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
								} else {
									$ptoduct_special = false;
								}
								
								$childrens[] = array(
									'product_id' => $product_info['product_id'],
									'sort_order' => $product_info['sort_order'],
									'thumb' => ($result['show_img']) ? $product_image : false,
									'name' => $product_info['name'],
									'price' => $product_price,
									'special' => $ptoduct_special,
									'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
								);
							}
						}
						
						$p_sort_order = [];
						
						foreach ($childrens as $key => $value) {
							$p_sort_order[$key] = $value['name'];
						}
						
						array_multisort($p_sort_order, SORT_ASC, $childrens);
					}
					
					if ($result['item_type'] == 5) {
						$children_data = $this->model_extension_module_oct_megamenu->getMegamenuInformation($result['megamenu_id']);
						
						$this->load->model('catalog/information');
						
						foreach ($children_data as $information_id) {
							$information_info = $this->model_catalog_information->getInformation($information_id);
							if ($information_info) {
								$childrens[] = array(
									'href' => $this->url->link('information/information', 'information_id=' . $information_id),
									'title' => $information_info['title'],
									'sort_order' => $information_info['sort_order']
								);
							}
						}
						
						$i_sort_order = [];
						
						foreach ($childrens as $key => $value) {
							$i_sort_order[$key] = $value['title'];
						}
						
						array_multisort($i_sort_order, SORT_ASC, $childrens);
					}
					
					$menu_items[] = array(
						'megamenu_id' 		=> $result['megamenu_id'],
						'title' 			=> $result['title'],
						'image' 			=> $image,
						'href' 				=> ($result['link'] == "#") ? "javascript:void(0);" : $result['link'],
						'open_link_type' 	=> $result['open_link_type'],
						'description' 		=> ($result['info_text']) ? html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8') : '',
						'custom_html' 		=> ($result['custom_html']) ? html_entity_decode($result['custom_html'], ENT_QUOTES, 'UTF-8') : '',
						'display_type' 		=> $result['display_type'],
						'limit_item' 		=> $result['limit_item'],
						'show_img' 			=> $result['show_img'],
						'top_svg' 			=> html_entity_decode($result['top_svg'], ENT_QUOTES, 'UTF-8'),
						'children' 			=> $childrens,
						'item_type' 		=> $result['item_type']
					);
				}

				// print('<pre>');
				// print_r($menu_items);
				// print('</pre>');
				
				$this->cache->set('octemplates.megamenu.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . (int)WEBPACCEPTABLE, $menu_items);
			}
			
			$data['items'] = $menu_items;
			
			$return_data = $this->load->view('extension/module/oct_megamenu', $data);
			$this->cache->set('ControllerExtensionModuleOctMegamenuIndex' . $this->config->get('config_language_id'), $return_data);
			
			return $return_data;
		}
		
		public function login() {
			$json = [];
			
			$this->load->model('account/customer');
			$this->load->language('account/login');
			
			$this->event->trigger('pre.customer.login');
			
			// Check how many login attempts have been made.
			$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['ocmm_login_email']);
			
			if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
				$json['error'] = $this->language->get('error_attempts');
			}
			
			// Check if customer has been approved.
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['ocmm_login_email']);
			
			if ($customer_info && !$customer_info['approved']) {
				$json['error'] = $this->language->get('error_approved');
			}
			
			if (!isset($json['error'])) {
				if (!$this->customer->login($this->request->post['ocmm_login_email'], $this->request->post['ocmm_login_password'])) {
					$json['error'] = $this->language->get('error_login');
					
					$this->model_account_customer->addLoginAttempt($this->request->post['ocmm_login_email']);
				} else {
					$this->model_account_customer->deleteLoginAttempts($this->request->post['ocmm_login_email']);
					
					$this->event->trigger('post.customer.login');
				}
			}
			
			
			if (!isset($json['error'])) {
				unset($this->session->data['guest']);
				
				// Default Shipping Address
				$this->load->model('account/address');
				
				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}
				
				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}
				
				// Add to activity log
				$this->load->model('account/activity');
				
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);
				
				$this->model_account_activity->addActivity('login', $activity_data);
				
				$json['redirect'] = $this->url->link('account/account', '', 'SSL');
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}	