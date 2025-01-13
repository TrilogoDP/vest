<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	/**************************************************************/
	
	class ControllerExtensionModuleOctProductTab extends Controller {
		private $setting;
		
		private function prepareProductArray($array, $setting){
			$this->load->model('tool/image');
			$this->load->model('catalog/oct_product_stickers');
						
			$products = array();
			
			foreach ($array as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
					$price = false;
				}
				
				if ((float) $result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
					$special = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
					} else {
					$rating = false;
				}
				
				$product_stickers_data = $this->config->get('oct_product_stickers_data');
				$product_stickers      = array();
				
				if (isset($product_stickers_data['status']) && $product_stickers_data['status']) {										
					if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
						$stickers = unserialize($result['oct_product_stickers']);
						} else {
						$stickers = array();
					}
					
					foreach ($stickers as $product_sticker_id) {
						$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
						
						if ($sticker_info) {
							$product_stickers[] = array(
							'text' => $sticker_info['text'],
							'color' => $sticker_info['color'],
							'background' => $sticker_info['background']
							);
						}
					}
					
					$sticker_sort_order = array();
					
					foreach ($stickers as $key => $product_sticker_id) {
						$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
						
						if ($sticker_info) {
							$sticker_sort_order[$key] = $sticker_info['sort_order'];
						}
					}
					
					array_multisort($sticker_sort_order, SORT_ASC, $product_stickers);
				}
				
				$oct_product_preorder_text     = $this->config->get('oct_product_preorder_text');
				$oct_product_preorder_data     = $this->config->get('oct_product_preorder_data');
				$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
				
				if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
					$product_preorder_text   = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
					$product_preorder_status = 1;
					} else {
					$product_preorder_text   = $oct_product_preorder_language['text_out_of_stock'];
					$product_preorder_status = 2;
				}
				
				$products[] = array(
				'oct_product_stickers' => $product_stickers,
				'product_id' => $result['product_id'],
				'thumb' => $image,
				'name' => $result['name'],
				'quantity' => $result['quantity'],
				'product_preorder_text' => $product_preorder_text,
				'product_preorder_status' => $product_preorder_status,
				'action_stickers' 	=> $result['action_stickers'],
				'price' => $price,
				'special' => $special,
				'saving' => round((($result['price'] - $result['special']) / ($result['price'] + 0.01)) * 100, 0),
				'rating' => $rating,
				'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
				'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
			
			
			return $products;	
		}
		
		public function vestlogic($setting){
			
			if ($return_data = $this->cache->get('ControllerExtensionModuleOctProductTabVestLogic' . $this->config->get('config_language_id') . md5(serialize($setting)))){
				return $return_data;
			}
			
			static $module = 0;
			
			$data['oct_popup_view_data'] = $this->config->get('oct_popup_view_data');
			$data['button_popup_view']   = $this->language->get('button_popup_view');
			
			$this->load->language('extension/module/oct_product_tab');

		$oct_data = $this->config->get('oct_techstore_data');

		if ((isset($oct_data['oct_lazyload']) && $oct_data['oct_lazyload'] == 1) && (isset($oct_data['oct_lazyload_module']) && $oct_data['oct_lazyload_module'] == 1)) {
			if ($oct_data['enable_minify'] == 'off') {
				$this->document->addScript('catalog/view/theme/oct_techstore/js/lazyload/jquery.lazyload.min.js');
			}

			$data['oct_lazyload'] = $oct_data['oct_lazyload'];

			if (isset($oct_data['oct_lazyload_image']) && $oct_data['oct_lazyload_image']) {
				$data['oct_lazyload_image'] = 'image/'.$oct_data['oct_lazyload_image'];
			} else {
				$data['oct_lazyload_image'] = '/image/catalog/1lazy/oct_loader_product.gif';
			}
		}
	  
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['button_cart']     = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare']  = $this->language->get('button_compare');
			
			$this->load->model('catalog/product');
			$this->load->model('extension/module/oct_product_tab');
			$this->load->model('tool/image');
			
			$data['position'] = $setting['position'];
			
			//ACTIONS
			$categories = $this->model_extension_module_oct_product_tab->getActionCategoriesWithProducts($setting['vestlogiclimit'], $setting['limit']);
			$data['special_categories'] = array();
			foreach ($categories as $category){
				$data['special_categories'][] = array(
				'category' => array(
				'name' 			=> $category['category']['name'],
				'category_id'   => $category['category']['category_id'],
				),
				'products' => $this->prepareProductArray($category['products'], $setting)													
				);
			}
			
			//Новинки
			$categories = $this->model_extension_module_oct_product_tab->getNewCategoriesWithProducts($setting['vestlogiclimit'], $setting['limit']);
			$data['new_categories'] = array();

			foreach ($categories as $category){
				$data['new_categories'][] = array(
				'category' => array(
				'name' 			=> $category['category']['name'],
				'category_id'   => $category['category']['category_id'],
				),
				'products' => $this->prepareProductArray($category['products'], $setting)													
				);
			}
			
			//Топ продаж
			$categories = $this->model_extension_module_oct_product_tab->getBestSellerCategoriesWithProducts($setting['vestlogiclimit'], $setting['limit']);
			$data['bestseller_categories'] = array();

			foreach ($categories as $category){
				$data['bestseller_categories'][] = array(
				'category' => array(
				'name' 			=> $category['category']['name'],
				'category_id'   => $category['category']['category_id'],
				),
				'products' => $this->prepareProductArray($category['products'], $setting)													
				);
			}
			
			//Топ просмотров
			$categories = $this->model_extension_module_oct_product_tab->getTopViewedCategoriesWithProducts($setting['vestlogiclimit'], $setting['limit']);
			$data['top_viewed_categories'] = array();

			foreach ($categories as $category){
				$data['top_viewed_categories'][] = array(
				'category' => array(
				'name' 			=> $category['category']['name'],
				'category_id'   => $category['category']['category_id'],
				),
				'products' => $this->prepareProductArray($category['products'], $setting)													
				);
			}
			
			
			$data['tabs'] = array();
			$data['tabs'][] = array(
			'tab' 	=> $this->language->get('tab_special'),
			'index' => 'special',
			'is'    => count($data['special_categories']),
			'data'	=> $data['special_categories']			
			);
			
			$data['tabs'][] = array(
			'tab' 	=> $this->language->get('tab_latest'),
			'index' => 'new',
			'is'    => count($data['new_categories']),
			'data'	=> $data['new_categories']			
			);
			
			$data['tabs'][] = array(
			'tab' 	=> $this->language->get('tab_bestseller'),
			'index' => 'bestseller',
			'is'    => count($data['bestseller_categories']),
			'data'	=> $data['bestseller_categories']			
			);
			
			$data['tabs'][] = array(
			'tab' 	=> $this->language->get('tab_top_viewed'),
			'index' => 'top_viewed',
			'is'    => count($data['top_viewed_categories']),
			'data'	=> $data['top_viewed_categories']			
			);
			
			$data['module'] = $module++;
			
			$return_data = $this->load->view('extension/module/oct_product_tab_auto', $data);
			$this->cache->set('ControllerExtensionModuleOctProductTabVestLogic' . $this->config->get('config_language_id') . md5(serialize($setting)), $return_data);
			return $return_data;					
		}
			
		public function index($setting) {
			static $module = 0;
			
			$data['oct_popup_view_data'] = $this->config->get('oct_popup_view_data');
			$data['button_popup_view']   = $this->language->get('button_popup_view');
			
			$this->load->language('extension/module/oct_product_tab');

		$oct_data = $this->config->get('oct_techstore_data');

		if ((isset($oct_data['oct_lazyload']) && $oct_data['oct_lazyload'] == 1) && (isset($oct_data['oct_lazyload_module']) && $oct_data['oct_lazyload_module'] == 1)) {
			if ($oct_data['enable_minify'] == 'off') {
				$this->document->addScript('catalog/view/theme/oct_techstore/js/lazyload/jquery.lazyload.min.js');
			}

			$data['oct_lazyload'] = $oct_data['oct_lazyload'];

			if (isset($oct_data['oct_lazyload_image']) && $oct_data['oct_lazyload_image']) {
				$data['oct_lazyload_image'] = 'image/'.$oct_data['oct_lazyload_image'];
			} else {
				$data['oct_lazyload_image'] = '/image/catalog/1lazy/oct_loader_product.gif';
			}
		}
	  
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['tab_latest']     = $this->language->get('tab_latest');
			$data['tab_featured']   = $this->language->get('tab_featured');
			$data['tab_bestseller'] = $this->language->get('tab_bestseller');
			$data['tab_special']    = $this->language->get('tab_special');
			$data['tab_top_viewed'] = $this->language->get('tab_top_viewed');
			
			$data['button_cart']     = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare']  = $this->language->get('button_compare');
			
			$this->load->model('catalog/product');
			$this->load->model('extension/module/oct_product_tab');
			$this->load->model('tool/image');
			
			$data['position'] = $setting['position'];
			
			if (!empty($setting['vestlogic'])){				
				return $this->vestlogic($setting);	
			}
			
			//Latest Products
			$data['latest_products'] = array();
			
			$latest_results = $this->model_catalog_product->getLatestProducts($setting['limit']);
			if (!empty($latest_results)) {
				foreach ($latest_results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
						} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$price = false;
					}
					
					if ((float) $result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$special = false;
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
						} else {
						$rating = false;
					}
					
					$product_stickers_data = $this->config->get('oct_product_stickers_data');
					$product_stickers      = array();
					
					if (isset($product_stickers_data['status']) && $product_stickers_data['status']) {
						$this->load->model('catalog/oct_product_stickers');
						
						if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
							$stickers = unserialize($result['oct_product_stickers']);
							} else {
							$stickers = array();
						}
						
						foreach ($stickers as $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$product_stickers[] = array(
								'text' => $sticker_info['text'],
								'color' => $sticker_info['color'],
								'background' => $sticker_info['background']
								);
							}
						}
						
						$sticker_sort_order = array();
						
						foreach ($stickers as $key => $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$sticker_sort_order[$key] = $sticker_info['sort_order'];
							}
						}
						
						array_multisort($sticker_sort_order, SORT_ASC, $product_stickers);
					}
					
					$oct_product_preorder_text     = $this->config->get('oct_product_preorder_text');
					$oct_product_preorder_data     = $this->config->get('oct_product_preorder_data');
					$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
					
					if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
						$product_preorder_text   = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
						$product_preorder_status = 1;
						} else {
						$product_preorder_text   = $oct_product_preorder_language['text_out_of_stock'];
						$product_preorder_status = 2;
					}
					
					$data['latest_products'][] = array(
					'oct_product_stickers' => $product_stickers,
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'quantity' => $result['quantity'],
					'product_preorder_text' => $product_preorder_text,
					'product_preorder_status' => $product_preorder_status,
					'action_stickers' 	=> $result['action_stickers'],
					'price' => $price,
					'special' => $special,
					'saving' => round((($result['price'] - $result['special']) / ($result['price'] + 0.01)) * 100, 0),
					'rating' => $rating,
					'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}
			}
			//Specials product
			$data['special_products'] = array();
			
			$special_data = array(
			//'sort' => 'p.sort_order',//pd.name
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit'],
			'stock_status' => true
			);
			
			$special_results = array();
			if ($setting['special_product']){
				foreach ($setting['special_product'] as $__product){
					$check_query = $this->db->query("SELECT ps.product_id FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON ps.product_id = p.product_id
					WHERE ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) AND p.status = 1 AND p.date_available <= NOW() AND quantity > 0 AND ps.product_id = '" . (int)$__product . "'");
					
					if ($check_query->num_rows){
						$special_results[$__product] = $this->model_catalog_product->getProduct($__product);
					}
				}
			}
			
			if (count($special_results) < $setting['limit']){
				$special_data['limit'] = $setting['limit'] - count($special_results);
				$special_results2 = $this->model_catalog_product->getProductSpecials($special_data);
				
				$special_results = array_merge($special_results, $special_results2);
			}
			
			if (!empty($special_results)) {
				foreach ($special_results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
						} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$price = false;
					}
					
					if ((float) $result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$special = false;
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
						} else {
						$rating = false;
					}
					
					$product_stickers_data = $this->config->get('oct_product_stickers_data');
					$product_stickers      = array();
					
					if (isset($product_stickers_data['status']) && $product_stickers_data['status']) {
						$this->load->model('catalog/oct_product_stickers');
						
						if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
							$stickers = unserialize($result['oct_product_stickers']);
							} else {
							$stickers = array();
						}
						
						foreach ($stickers as $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$product_stickers[] = array(
								'text' => $sticker_info['text'],
								'color' => $sticker_info['color'],
								'background' => $sticker_info['background']
								);
							}
						}
						
						$sticker_sort_order = array();
						
						foreach ($stickers as $key => $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$sticker_sort_order[$key] = $sticker_info['sort_order'];
							}
						}
						
						array_multisort($sticker_sort_order, SORT_ASC, $product_stickers);
					}
					
					$oct_product_preorder_text     = $this->config->get('oct_product_preorder_text');
					$oct_product_preorder_data     = $this->config->get('oct_product_preorder_data');
					$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
					
					if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
						$product_preorder_text   = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
						$product_preorder_status = 1;
						} else {
						$product_preorder_text   = $oct_product_preorder_language['text_out_of_stock'];
						$product_preorder_status = 2;
					}
					
					$data['special_products'][] = array(
					'oct_product_stickers' => $product_stickers,
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'quantity' => $result['quantity'],
					'product_preorder_text' => $product_preorder_text,
					'product_preorder_status' => $product_preorder_status,
					'price' => $price,
					'special' => $special,
					'action_stickers' 	=> $result['action_stickers'],
					'saving' => round((($result['price'] - $result['special']) / ($result['price'] + 0.01)) * 100, 0),
					'rating' => $rating,
					'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}
			}
			//BestSeller
			$data['bestseller_products'] = array();
			
			$bestseller_results = $this->model_catalog_product->getBestSellerProducts($setting['limit'], true);			
			
			if (!empty($bestseller_results)) {
				foreach ($bestseller_results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
						} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$price = false;
					}
					
					if ((float) $result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$special = false;
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
						} else {
						$rating = false;
					}
					
					$product_stickers_data = $this->config->get('oct_product_stickers_data');
					$product_stickers      = array();
					
					if (isset($product_stickers_data['status']) && $product_stickers_data['status']) {
						$this->load->model('catalog/oct_product_stickers');
						
						if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
							$stickers = unserialize($result['oct_product_stickers']);
							} else {
							$stickers = array();
						}
						
						foreach ($stickers as $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$product_stickers[] = array(
								'text' => $sticker_info['text'],
								'color' => $sticker_info['color'],
								'background' => $sticker_info['background']
								);
							}
						}
						
						$sticker_sort_order = array();
						
						foreach ($stickers as $key => $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$sticker_sort_order[$key] = $sticker_info['sort_order'];
							}
						}
						
						array_multisort($sticker_sort_order, SORT_ASC, $product_stickers);
					}
					
					$oct_product_preorder_text     = $this->config->get('oct_product_preorder_text');
					$oct_product_preorder_data     = $this->config->get('oct_product_preorder_data');
					$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
					
					if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
						$product_preorder_text   = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
						$product_preorder_status = 1;
						} else {
						$product_preorder_text   = $oct_product_preorder_language['text_out_of_stock'];
						$product_preorder_status = 2;
					}
					
					$data['bestseller_products'][] = array(
					'oct_product_stickers' => $product_stickers,
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'quantity' => $result['quantity'],
					'product_preorder_text' => $product_preorder_text,
					'product_preorder_status' => $product_preorder_status,
					'action_stickers' 	=> $result['action_stickers'],
					'price' => $price,
					'special' => $special,
					'saving' => round((($result['price'] - $result['special']) / ($result['price'] + 0.01)) * 100, 0),
					'rating' => $rating,
					'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}
			}
			//Featured
			$data['featured_products'] = array();
			
			$products = explode(',', $this->config->get('featured_product'));
			
			if (empty($setting['limit'])) {
				$setting['limit'] = 5;
			}
			
			if (!empty($setting['product'])) {
				$products = array_slice($setting['product'], 0, (int) $setting['limit']);
				
				foreach ($products as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);
					
					if ($product_info) {
						if ($product_info['image']) {
							$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
							} else {
							$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
						}
						
						if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
							} else {
							$price = false;
						}
						
						if ((float) $product_info['special']) {
							$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
							} else {
							$special = false;
						}
						
						if ($this->config->get('config_review_status')) {
							$rating = $product_info['rating'];
							} else {
							$rating = false;
						}
						
						$product_stickers_data = $this->config->get('oct_product_stickers_data');
						$product_stickers      = array();
						
						if (isset($product_stickers_data['status']) && $product_stickers_data['status']) {
							$this->load->model('catalog/oct_product_stickers');
							
							if ($product_info['oct_product_stickers']) {
								$stickers = unserialize($product_info['oct_product_stickers']);
								} else {
								$stickers = array();
							}
							
							foreach ($stickers as $product_sticker_id) {
								$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
								
								if ($sticker_info) {
									$product_stickers[] = array(
									'text' => $sticker_info['text'],
									'color' => $sticker_info['color'],
									'background' => $sticker_info['background']
									);
								}
							}
							
							$sticker_sort_order = array();
							
							foreach ($stickers as $key => $product_sticker_id) {
								$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
								
								if ($sticker_info) {
									$sticker_sort_order[$key] = $sticker_info['sort_order'];
								}
							}
							
							array_multisort($sticker_sort_order, SORT_ASC, $product_stickers);
						}
						
						$oct_product_preorder_text     = $this->config->get('oct_product_preorder_text');
						$oct_product_preorder_data     = $this->config->get('oct_product_preorder_data');
						$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
						
						if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($product_info['oct_stock_status_id']) && in_array($product_info['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
							$product_preorder_text   = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
							$product_preorder_status = 1;
							} else {
							$product_preorder_text   = $oct_product_preorder_language['text_out_of_stock'];
							$product_preorder_status = 2;
						}
						
						$data['featured_products'][] = array(
						'oct_product_stickers' => $product_stickers,
						'product_id' => $product_info['product_id'],
						'thumb' => $image,
						'name' => $product_info['name'],
						'quantity' => $product_info['quantity'],
						'product_preorder_text' => $product_preorder_text,
						'product_preorder_status' => $product_preorder_status,
						'action_stickers' 	=> $product_info['action_stickers'],
						'price' => $price,
						'special' => $special,
						'saving' => round((($product_info['price'] - $product_info['special']) / ($product_info['price'] + 0.01)) * 100, 0),
						'rating' => $rating,
						'reviews' => sprintf($this->language->get('text_reviews'), (int) $product_info['reviews']),
						'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
						);
					}
				}
			}
			
			//Top Viewed
			$data['top_viewed_products'] = array();
			
			$top_viewed_results = $this->model_extension_module_oct_product_tab->getTopViewedProducts($setting['limit'], true);
			if (!empty($top_viewed_results)) {
				foreach ($top_viewed_results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
						} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$price = false;
					}
					
					if ((float) $result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
						$special = false;
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
						} else {
						$rating = false;
					}
					
					$product_stickers_data = $this->config->get('oct_product_stickers_data');
					$product_stickers      = array();
					
					if (isset($product_stickers_data['status']) && $product_stickers_data['status']) {
						$this->load->model('catalog/oct_product_stickers');
						
						if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
							$stickers = unserialize($result['oct_product_stickers']);
							} else {
							$stickers = array();
						}
						
						foreach ($stickers as $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$product_stickers[] = array(
								'text' => $sticker_info['text'],
								'color' => $sticker_info['color'],
								'background' => $sticker_info['background']
								);
							}
						}
						
						$sticker_sort_order = array();
						
						foreach ($stickers as $key => $product_sticker_id) {
							$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
							
							if ($sticker_info) {
								$sticker_sort_order[$key] = $sticker_info['sort_order'];
							}
						}
						
						array_multisort($sticker_sort_order, SORT_ASC, $product_stickers);
					}
					
					$oct_product_preorder_text     = $this->config->get('oct_product_preorder_text');
					$oct_product_preorder_data     = $this->config->get('oct_product_preorder_data');
					$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
					
					if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
						$product_preorder_text   = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
						$product_preorder_status = 1;
						} else {
						$product_preorder_text   = $oct_product_preorder_language['text_out_of_stock'];
						$product_preorder_status = 2;
					}
					
					$data['top_viewed_products'][] = array(
					'oct_product_stickers' => $product_stickers,
					'product_id' => $result['product_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'quantity' => $result['quantity'],
					'product_preorder_text' => $product_preorder_text,
					'product_preorder_status' => $product_preorder_status,
					'action_stickers' 	=> $result['action_stickers'],
					'price' => $price,
					'special' => $special,
					'saving' => round((($result['price'] - $result['special']) / ($result['price'] + 0.01)) * 100, 0),
					'rating' => $rating,
					'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
					'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}
			}
			
			$data['module'] = $module++;
			$return_data = $this->load->view('extension/module/oct_product_tab', $data);			
			return $return_data;
		}
	}								