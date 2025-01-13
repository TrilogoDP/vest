<?php
	class ModelCatalogProduct extends Model {
		public function updateViewed($product_id) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
		}
		
		public function addProductToCustomerViewed($product_id, $customer_id) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_viewed SET 
			customer_id = '" . (int)$customer_id . "',
			product_id = '" . (int)$product_id . "'
			ON DUPLICATE KEY UPDATE times = times + 1
			");
		}
		
		public function checkIfProductIsFreeDelivered($product_info){

			if (!$this->getIfProductIsInFreeDeliveryCategories($product_info['product_id'])){
				return false;
			}
			
			$price = $product_info['special']?$product_info['special']:$product_info['price'];
			
			$codes = array(
			'novaposhta_min_total_for_free_delivery', 'justin_min_total_for_free_delivery', 'ukrposhta_min_total_for_free_delivery', 'novaposhtacopy_min_total_for_free_delivery'				);
			
			foreach ($codes as $_code){
				
				if ($this->config->get($_code) > 0 && $price > (int)$this->config->get($_code)){
					return true;
				}
				
			}
			
			return false;
		}
		
		public function getIfProductIsInFreeDeliveryCategories($product_id){
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE free_delivery = 1)");
			
			return $query->num_rows;
		}
		
		public function getGoogleCategoryPath($product_id){
			if (!$string = $this->cache->get('productfullpath.' . (int)$product_id . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'))){
				$this->load->model('catalog/category');	
				
				$category = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1")->row;
				
				$path = $this->getPath($category['category_id']);
				
				if ($path) {
					$string = '';
					
					foreach (explode('_', $path) as $path_id) {
						$category_info = $this->model_catalog_category->getCategory($path_id);
						
						if ($category_info) {
							if (!$string) {
								$string = $category_info['name'];
								} else {
								$string .= '/' . $category_info['name'];
							}
						}
					}
				}
				
				$this->cache->set('productfullpath.' . (int)$product_id . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'), $string);
			}
			
			return $string;
		}

		public function addProductBestSellerSticker($product_data){

			$product_data['action_stickers']['bestseller'] = array(
					'label' 		=> $this->language->get('text_bestseller'),
					'label_color' 	=> '#ffffff',
					'label_bg' 		=> '#ffa900',
					'special_id'	=> false,
					'href'			=> false
			);

			return $product_data;

		}

		public function getProductUA($product_id) {
			
			$product_data = $this->cache->get('product.' . $product_id . '.3.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'));
			
			if (!$product_data) {
				$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, (SELECT md.name FROM " . DB_PREFIX . "manufacturer_description md WHERE md.manufacturer_id = p.manufacturer_id AND md.language_id = '3') AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '3') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '3') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '3') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '3' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
				
				
				if ($query->num_rows) {
					$ecommerceData = array(
					'id'		=> (int)$query->row['product_id'],
					'name' 		=> prepareEcommString($query->row['name']),
					'gtin' 		=> prepareEcommString($query->row['ean']),			
					'brand' 	=> prepareEcommString($query->row['manufacturer']),		
					'price' 	=> prepareEcommString($query->row['special']?$query->row['special']:$query->row['price']),
					'category' 	=> prepareEcommString($this->getGoogleCategoryPath($query->row['product_id']))
					);
				}
				
				if ($query->num_rows) {
					$product_data = array(
					'product_id'       => $query->row['product_id'],
        			'oct_product_stickers'     => (isset($query->row['oct_product_stickers'])) ? $query->row['oct_product_stickers'] : array(),      
					'name'             => $query->row['name'],
					'ecommerceData'	   => $ecommerceData,					
					'description'      => $query->row['description'],
					'fake_description' => $query->row['fake_description'],
					'bestseller'	   => $query->row['bestseller'],
					'orders_180'	   => $query->row['orders_180'],
					'orders_90'	   	   => $query->row['orders_90'],
					'highlight' 	   => $query->row['highlight'],
					'meta_title'       => $query->row['meta_title'],
					'meta_h1'          => $query->row['meta_h1'],
					'meta_description' => $query->row['meta_description'],
					'faq_name' 			=> $query->row['faq_name'],
					'meta_keyword'     => $query->row['meta_keyword'],
					'tag'              => $query->row['tag'],
					'model'            => $query->row['model'],
					'model_marketplace' => $query->row['model_marketplace'],
					'sku'              => $query->row['sku'],
					'upc'              => $query->row['upc'],
					'ean'              => $query->row['ean'],
					'jan'              => $query->row['jan'],
					'isbn'             => $query->row['isbn'],
					'mpn'              => $query->row['mpn'],
					'location'         => $query->row['location'],
					'quantity'         => $query->row['quantity'],
					'stock'            => $query->row['stock'],
					'stock_status'     => $query->row['stock_status'],
					'image'            => $query->row['image'],
					'manufacturer_id'  => $query->row['manufacturer_id'],
					'category_id'	   => $this->getMainCategoryID($query->row['product_id']),
					'manufacturer'     => $query->row['manufacturer'],
					'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
					'special'          => $query->row['special'],
					'dnup'             => $query->row['dnup'],
					'reward'           => $query->row['reward'],
					'points'           => $query->row['points'],
					'tax_class_id'     => $query->row['tax_class_id'],
					'date_available'   => $query->row['date_available'],
					'weight'           => $query->row['weight'],
					'weight_class_id'  => $query->row['weight_class_id'],
					'length'           => $query->row['length'],
					'width'            => $query->row['width'],
					'height'           => $query->row['height'],
					'length_class_id'  => $query->row['length_class_id'],
					'subtract'         => $query->row['subtract'],
					'rating'           => round($query->row['rating']),
					'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
					'minimum'          => $query->row['minimum'],
					'sort_order'       => $query->row['sort_order'],
					'status'           => $query->row['status'],
					'archive'          => $query->row['archive'],
					'date_added'       => $query->row['date_added'],
					'date_modified'    => $query->row['date_modified'],

				// oct_product_preorder start
				'oct_stock_status_id' => $query->row['stock_status_id'],
				// oct_product_preorder end
			
					'viewed'           => $query->row['viewed'],
					'action_stickers'  => array()
					);
					
					//ADDING PROMO STICKERS
					$this->load->model('catalog/ochelp_special');
					$activeSpecialsProduct = $this->model_catalog_ochelp_special->getCurrentActiveSpecialsProduct($product_id);
					
					foreach($activeSpecialsProduct as $activeSpecialProduct){	
						if (!empty($activeSpecialProduct['label'])){
							$product_data['action_stickers'][] = array(
							'label' 		=> $activeSpecialProduct['label'],
							'label_color' 	=> $activeSpecialProduct['label_color'],
							'label_bg' 		=> $activeSpecialProduct['label_bg'],
							'special_id'	=> $activeSpecialProduct['special_id'],
							'href'			=> $this->url->link('information/ochelp_special/info', 'special_id=' . $activeSpecialProduct['special_id'])
							);
						}
					}
					
					if ($this->checkIfProductIsFreeDelivered($product_data)){
						$product_data['action_stickers'][] = array(
						'label' 		=> $this->language->get('text_free_shipping'),
						'label_color' 	=> '#ffffff',
						'label_bg' 		=> '#7aae50',
						'special_id'	=> false,
						'href'			=> false
						);
					}

					if ($product_data['bestseller']){
						$product_data['action_stickers']['bestseller'] = array(
						'label' 		=> $this->language->get('text_bestseller'),
						'label_color' 	=> '#ffffff',
						'label_bg' 		=> '#ffa900',
						'special_id'	=> false,
						'href'			=> false
						);
					}
					
					$this->cache->set('product.' . $product_id . '.3.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'), $product_data);
					
					} else {
					$product_data = false;
				}
			}
			
			return $product_data;
		}
		
		public function getProduct($product_id) {
			
			$product_data = $this->cache->get('product.' . $product_id . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'));
			
			if (!$product_data) {
				$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, (SELECT md.name FROM " . DB_PREFIX . "manufacturer_description md WHERE md.manufacturer_id = p.manufacturer_id AND md.language_id = '" . (int)$this->config->get('config_language_id') . "') AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
				
				
				if ($query->num_rows) {
					$ecommerceData = array(
					'id'		=> (int)$query->row['product_id'],
					'name' 		=> prepareEcommString($query->row['name']),
					'gtin' 		=> prepareEcommString($query->row['ean']),			
					'brand' 	=> prepareEcommString($query->row['manufacturer']),		
					'price' 	=> prepareEcommString($query->row['special']?$query->row['special']:$query->row['price']),
					'category' 	=> prepareEcommString($this->getGoogleCategoryPath($query->row['product_id']))
					);
				}
				
				if ($query->num_rows) {
					$product_data = array(
					'product_id'       => $query->row['product_id'],
        			'oct_product_stickers'     => (isset($query->row['oct_product_stickers'])) ? $query->row['oct_product_stickers'] : array(),      
					'name'             => $query->row['name'],
					'ecommerceData'	   => $ecommerceData,					
					'description'      => $query->row['description'],
					'fake_description' => $query->row['fake_description'],
					'bestseller'	   => $query->row['bestseller'],
					'orders_180'	   => $query->row['orders_180'],
					'orders_90'	   	   => $query->row['orders_90'],
					'highlight' 	   => $query->row['highlight'],
					'meta_title'       => $query->row['meta_title'],
					'meta_h1'          => $query->row['meta_h1'],
					'meta_description' => $query->row['meta_description'],
					'faq_name' => $query->row['faq_name'],
					'meta_keyword'     => $query->row['meta_keyword'],
					'tag'              => $query->row['tag'],
					'model'            => $query->row['model'],
					'model_marketplace' => $query->row['model_marketplace'],
					'sku'              => $query->row['sku'],
					'upc'              => $query->row['upc'],
					'ean'              => $query->row['ean'],
					'jan'              => $query->row['jan'],
					'isbn'             => $query->row['isbn'],
					'mpn'              => $query->row['mpn'],
					'location'         => $query->row['location'],
					'quantity'         => $query->row['quantity'],
					'stock'            => $query->row['stock'],
					'stock_status'     => $query->row['stock_status'],
					'image'            => $query->row['image'],
					'manufacturer_id'  => $query->row['manufacturer_id'],
					'category_id'	   => $this->getMainCategoryID($query->row['product_id']),
					'manufacturer'     => $query->row['manufacturer'],
					'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
					'special'          => $query->row['special'],
					'dnup'             => $query->row['dnup'],
					'reward'           => $query->row['reward'],
					'points'           => $query->row['points'],
					'tax_class_id'     => $query->row['tax_class_id'],
					'date_available'   => $query->row['date_available'],
					'weight'           => $query->row['weight'],
					'weight_class_id'  => $query->row['weight_class_id'],
					'length'           => $query->row['length'],
					'width'            => $query->row['width'],
					'height'           => $query->row['height'],
					'length_class_id'  => $query->row['length_class_id'],
					'subtract'         => $query->row['subtract'],
					'rating'           => round($query->row['rating']),
					'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
					'minimum'          => $query->row['minimum'],
					'sort_order'       => $query->row['sort_order'],
					'status'           => $query->row['status'],
					'archive'          => $query->row['archive'],
					'date_added'       => $query->row['date_added'],
					'date_modified'    => $query->row['date_modified'],
					'oct_stock_status_id' => $query->row['stock_status_id'],
					'viewed'           => $query->row['viewed'],
					'action_stickers'  => array()
					);
					
					$this->load->model('catalog/ochelp_special');
					$activeSpecialsProduct = $this->model_catalog_ochelp_special->getCurrentActiveSpecialsProduct($product_id);
					
					foreach($activeSpecialsProduct as $activeSpecialProduct){	
						if (!empty($activeSpecialProduct['label'])){
							$product_data['action_stickers'][] = array(
							'label' 		=> $activeSpecialProduct['label'],
							'label_color' 	=> $activeSpecialProduct['label_color'],
							'label_bg' 		=> $activeSpecialProduct['label_bg'],
							'special_id'	=> $activeSpecialProduct['special_id'],
							'href'			=> $this->url->link('information/ochelp_special/info', 'special_id=' . $activeSpecialProduct['special_id'])
							);
						}
					}
					
					if ($this->checkIfProductIsFreeDelivered($product_data)){
						$product_data['action_stickers'][] = array(
						'label' 		=> $this->language->get('text_free_shipping'),
						'label_color' 	=> '#ffffff',
						'label_bg' 		=> '#7aae50',
						'special_id'	=> false,
						'href'			=> false
						);
					}

					if ($product_data['bestseller']){
						$product_data['action_stickers']['bestseller'] = array(
						'label' 		=> $this->language->get('text_bestseller'),
						'label_color' 	=> '#ffffff',
						'label_bg' 		=> '#ffa900',
						'special_id'	=> false,
						'href'			=> false
						);
					}
					
					$this->cache->set('product.' . $product_id . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'), $product_data);
					
					} else {
					$product_data = false;
				}
			}
			
			return $product_data;
		}
		
		public function getExplicitProduct($product_id){
			
			$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, (SELECT md.name FROM " . DB_PREFIX . "manufacturer_description md WHERE md.manufacturer_id = p.manufacturer_id AND md.language_id = '" . (int)$this->config->get('config_language_id') . "') AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if ($query->num_rows) {
				$product_data = array(
				'product_id'       => $query->row['product_id'],

        'oct_product_stickers'     => (isset($query->row['oct_product_stickers'])) ? $query->row['oct_product_stickers'] : array(),
      
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'fake_description' => $query->row['fake_description'],
				'highlight' 	   => $query->row['highlight'],
				'meta_title'       => $query->row['meta_title'],
				'meta_h1'          => $query->row['meta_h1'],
				'meta_description' => $query->row['meta_description'],
'faq_name' => $query->row['faq_name'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock'            => $query->row['stock'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'dnup'             => $query->row['dnup'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'archive'          => $query->row['archive'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],

				// oct_product_preorder start
				'oct_stock_status_id' => $query->row['stock_status_id'],
				// oct_product_preorder end
			
				'viewed'           => $query->row['viewed']
				);
				
			}
			
			return $product_data;
		}
		
		public function getSupplementalFeedProducts(){
			
			$sql = "SELECT p.product_id, p.quantity, p.price, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p WHERE status = 1 AND archive = 0 AND dnmerchant = 0 AND p.date_available <= NOW()";
			
			$query = $this->db->ncquery($sql);
			
			return $query->rows;
			
		}
		
		public function getSupplementalFeedOptions(){
			
			$sql = "SELECT opv.*, p.price, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM
			" . DB_PREFIX . "product_option_value opv LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = opv.product_id)
			WHERE p.status = 1 AND archive = 0 AND p.date_available <= NOW()";
			
			$query = $this->db->ncquery($sql);
			
			return $query->rows;
		}
		
		public function findProductBySKU($sku){
			
			$query = $this->db->ncquery("SELECT * FROM " . DB_PREFIX . "product WHERE TRIM(sku) LIKE ('" . $this->db->escape(trim($sku)) . "') LIMIT 1");
			
			if ($query->num_rows) {
				return $query->row['product_id'];
				} else {
				return false;
			}
		}
		
		public function findProductOptionBySKU($suppler_prefix, $sku){
			
			$query = $this->db->ncquery("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE 
			sku LIKE ('" . $this->db->escape($sku) . "') 
			OR optsku LIKE ('" . $this->db->escape($sku) . "')
			OR sku LIKE ('" . $this->db->escape($suppler_prefix . $sku) . "') 
			OR optsku LIKE ('" . $this->db->escape($suppler_prefix . $sku) . "') 
			LIMIT 1");
			
			if ($query->num_rows) {
				return $query->row['product_option_value_id'];
				} else {
				return false;
			}
		}
		
		public function getProductOptionValue($product_option_value_id){
			
			$query = $this->db->ncquery("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_value_id = '" . $product_option_value_id . "' LIMIT 1");
			
			if ($query->num_rows) {
				return $query->row;
				} else {
				return false;
			}
		}
		
		public function checkIfSKUWasDeleted($sku){
			
			$query = $this->db->ncquery("SELECT * FROM " . DB_PREFIX . "sku_deleted WHERE sku LIKE ('" . $this->db->escape($sku) . "')");
			
			if (!$query->num_rows){
				$query = $this->db->ncquery("SELECT * FROM " . DB_PREFIX . "product_sku_deleted WHERE sku_deleted LIKE ('" . $this->db->escape($sku) . "')");
			}
			
			return $query->num_rows;
			
		}
		
		
		public function getProducts($data = array()) {
			$sql = "SELECT p.product_id, p.orders_180, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
					} else {
					$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
				}
				
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
					} else {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
				}
				} else {
				$sql .= " FROM " . DB_PREFIX . "product p";
			}
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (p.product_id = pov.product_id) ";
			}
					
			if (!empty($data['filter_ocfilter'])) {
				$this->load->model('catalog/ocfilter');

				$ocfilter_product_sql = $this->model_catalog_ocfilter->getSearchSQL($data['filter_ocfilter']);
			} else {
				$ocfilter_product_sql = false;
			}

			if ($ocfilter_product_sql && $ocfilter_product_sql->join) {
				$sql .= $ocfilter_product_sql->join;
			}
      
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND archive = 0 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

			// Исключаем товары из категории 28 для категории 39
			if (!empty($data['filter_category_id']) && $data['filter_category_id'] == 95 || $data['filter_category_id'] == 79) {
				$sql .= " AND p.product_id NOT IN (
					SELECT p2c.product_id 
					FROM " . DB_PREFIX . "product_to_category p2c 
					WHERE p2c.category_id = '" . (int)$data['exclude_category_id'] . "'
				)";
			}
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
				
				if (!empty($data['filter_filter'])) {
					$implode = array();
					
					$filters = explode(',', $data['filter_filter']);
					
					foreach ($filters as $filter_id) {
						$implode[] = (int)$filter_id;
					}
					
					$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
				}
			}
			
			if (!empty($data['filter_special'])) {
				
				$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_special ps WHERE ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end > NOW())))";
				
			}
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
				
				if (!empty($data['filter_name'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
					
					if (!empty($data['filter_description'])) {
						$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.sku) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
					$sql .= " OR LCASE(p.model) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
					//					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					//					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(pov.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(pov.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(pov.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_category_id']) && !empty($data['filter_exclude_categories']) ) {
				$sql .= " AND p.product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id IN (" . implode(',', $data['filter_exclude_categories']) . "))";
			}
			
			if (!empty($ocfilter_product_sql) && $ocfilter_product_sql->where) {
				$sql .= $ocfilter_product_sql->where;
			}
      
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
				if (!empty($data['filter_hide_manufacturer'])) {
					$sql .= " AND (p.product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE main_category = 1 AND p2c.category_id IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE hide_manufacturer = 1)))";
				}
			}

			if (!empty($data['filter_custom_yml_mf'])) {
				$sql .= " AND p.custom_yml_mf = 1";
			}
			
			if (!empty($data['filter_quantity'])) {
				$sql .= " AND p.quantity > 0";
			}
			
			if (!empty($data['filter_not_archive'])) {
				$sql .= " AND p.archive = 0";
			}
			
			if (!empty($data['filter_not_tax'])) {			
				$sql .= " AND p.tax_class_id = '0'";
			}

			if (!empty($data['filter_min_product_id'])) {			
				$sql .= " AND p.product_id > '" . (int)$data['filter_min_product_id'] . "'";
			}

			if (!empty($data['filter_max_product_id'])) {			
				$sql .= " AND p.product_id <= '" . (int)$data['filter_max_product_id'] . "'";
			}

			if (!empty($data['filter_status'])) {							
				$sql .= " AND p.status = 1";
			}
			
			if (!empty($data['filter_gmc'])) {
				$sql .= " AND p.dnmerchant = 0";
			}
			
			$sql .= " GROUP BY p.product_id";			
			
			$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.orders_180',
			'p.viewed',
			'p.sort_order',
			'p.date_added'
			);

			if ($data['sort'] == 'rating'){
				$data['sort'] = 'p.orders_180';
			}
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY (p.quantity>0) DESC, LCASE(" . $data['sort'] . ")";
					} elseif ($data['sort'] == 'p.price') {
					$sql .= " ORDER BY (p.quantity>0) DESC, (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
					} elseif ($data['sort'] == 'p.date_added') {
					$sql .= " ORDER BY p.date_added DESC, (p.quantity>0) ";
					} else {
					$sql .= " ORDER BY (p.quantity>0) DESC, " . $data['sort'];
				}
				} else {
				$sql .= " ORDER BY (p.quantity>0) DESC, p.sort_order";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
				} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$product_data = array();
			
			$query = $this->db->query($sql);
			
			if (!empty($data['filter_return_simple'])){
				return $query->rows;			
			}
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		public function getProductSpecialActual($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special ps WHERE product_id = '" . (int)$product_id . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY priority ASC LIMIT 1 ");
			
			return $query->row;
		}
		
		
		public function getProductSpecials($data = array()) {
			//$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.quantity > '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";
			
			$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating_old, (SELECT COUNT(DISTINCT order_id) FROM " . DB_PREFIX . "order_product WHERE product_id = p.product_id) as rating";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
					} else {
					$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
				}
				
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
					} else {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
				}
				} else {
				$sql .= " FROM " . DB_PREFIX . "product p";
			}
			

		// OCFilter start
		if (!empty($data['filter_ocfilter'])) {
    	$this->load->model('catalog/ocfilter');

      $ocfilter_product_sql = $this->model_catalog_ocfilter->getSearchSQL($data['filter_ocfilter']);
		} else {
      $ocfilter_product_sql = false;
    }

    if ($ocfilter_product_sql && $ocfilter_product_sql->join) {
    	$sql .= $ocfilter_product_sql->join;
    }
    // OCFilter end
      
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id)";
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";			
			$sql .= " WHERE p.status = '1' AND p.quantity > '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
				
				if (!empty($data['filter_filter'])) {
					$implode = array();
					
					$filters = explode(',', $data['filter_filter']);
					
					foreach ($filters as $filter_id) {
						$implode[] = (int)$filter_id;
					}
					
					$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
				}
			}
			
			$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_special ps WHERE ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end > NOW())))";
			
			if(isset($data['stock_status']) && $data['stock_status'] == true){
				$sql .= " AND p.product_id NOT IN (SELECT DISTINCT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE main_category = 1 AND p2c.category_id IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE efs = 1))";
			}
			
			$sql .= " GROUP BY p.product_id";
			
			$sort_data = array(
				'pd.name',
				'p.model',
				'ps.price',
				'rating',
				'p.sort_order'
			);
	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} elseif ($data['sort'] == 'ps.price') {
					$sql .= " ORDER BY ps.priority ASC, ps.price";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY p.sort_order";
			}
	
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
			} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			
			
			$product_data = array();
			
			
			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		public function getLatestProducts($limit) {
			$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
			}
			
			return $product_data;
		}
		
		public function getPopularProducts($limit) {
			$product_data = $this->cache->get('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
			}
			
			return $product_data;
		}
		
		public function guessSameProducts($product_id, $product_name, $limit, $in_stock = false){							
			$results = array();
			$exploded = explode(' ', $product_name);
			
			$categories = $this->getCategories($product_id);
			
			$just_categories = array();
			foreach ($categories as $category){
				if ($category['main_category']){
					$main_category_id = $category['category_id'];
					} else {
					$just_categories[] = $category['category_id'];
				}
			}
			
			$exclude_ids = array($product_id);
			
			$results = array();			
			
			$results = $this->getSimilarProductsByOcFilter($product_id, $main_category_id, $limit);
			
			
			//Пробуем найти товары, похожие по двум первым словам названия и 
			$results = array_merge($results, $this->getSimilarProductsByAttribute($product_id, $main_category_id, ($limit - count($results))));
			
			//Попытка получить по трем словам
			if (count($results) < $limit){
				if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2]) && isset($exploded[3])){
					$results = array_merge($results, $this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2] . ' ' . $exploded[3], $product_id, ($limit - count($results)), $in_stock, $main_category_id));					
				}
			}
			
			//Попытка получить по трем словам
			if (count($results) < $limit){
				if (isset($exploded[0]) && isset($exploded[1]) && isset($exploded[2])){
					$results = array_merge($results, $this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1] . ' ' . $exploded[2], $product_id, ($limit - count($results)), $in_stock, $main_category_id));					
				}
			}
			
			//Попытка получить по двум словам
			if (count($results) < $limit){
				
				if (isset($exploded[0]) && isset($exploded[1])){
					$results = array_merge($results, $this->getSimilarProductsByName($exploded[0] . ' ' . $exploded[1], $product_id, ($limit - count($results)),  $in_stock, $main_category_id));	
				}
				
			}
			
			//Попытка получить по одному слову
			if (count($results) < $limit){
				
				if (isset($exploded[0]) && isset($exploded[1])){
					$results = array_merge($results, $this->getSimilarProductsByName($exploded[0], $product_id, ($limit - count($results)), $in_stock, $main_category_id));	
				}
				
			}
			
			return $results;
			
		}
		
		public function getProductOCFilterSelectionFilters($product_id){
			$query = $this->db->query("SELECT oovtp.option_id, GROUP_CONCAT(oovtp.value_id SEPARATOR ',') as value_id FROM " . DB_PREFIX . "ocfilter_option_value_to_product oovtp 
			LEFT JOIN " . DB_PREFIX . "ocfilter_option oo ON (oovtp.option_id = oo.option_id) WHERE oovtp.product_id = '". (int)$product_id ."' AND oo.selection = 1 GROUP BY oovtp.option_id");
			
			return $query->rows;
		}
		
		public function getProductOcFilterActiveValues($product_id, $separator = ' ,', $language_id = false){

			if (!$language_id){
				$language_id = $this->config->get('config_language_id');
			}

			$query = $this->db->query("SELECT 
			ooovd.option_id, ooovd.value_id, oood.name as name, 
			GROUP_CONCAT(ooovd.name SEPARATOR '". $this->db->escape($separator) ."') as 'value' FROM `" . DB_PREFIX . "ocfilter_option_value_to_product` ooov2p 
			LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_description ooovd ON (ooov2p.option_id = ooovd.option_id AND ooov2p.value_id = ooovd.value_id AND ooovd.language_id = '" . (int)$language_id . "') 
			LEFT JOIN " . DB_PREFIX . "ocfilter_option_description oood ON (ooovd.option_id = oood.option_id AND oood.language_id = '" . (int)$language_id . "') 
			WHERE product_id = '" . (int)$product_id . "' 
			AND LENGTH(oood.name) 
			GROUP BY ooovd.option_id");
			
			
			return $query->rows;
		}
		
		public function getProductOcFilterActiveValuesNonGrouped($product_id, $category_id = false){
			
			if (!$category_id){
				$category_id = $this->getMainCategoryID($product_id);
			}
			
			$query = $this->db->query("SELECT ooovd.option_id, ooovd.value_id, CONCAT(ooovd.option_id, ':', ooovd.value_id) as option_value, oood.name as name, ooovd.name as 'value' 
			FROM `" . DB_PREFIX . "ocfilter_option_value_to_product` ooov2p 
			LEFT JOIN " . DB_PREFIX . "ocfilter_option_value_description ooovd ON (ooov2p.option_id = ooovd.option_id AND ooov2p.value_id = ooovd.value_id AND ooovd.language_id = '" . (int)$this->config->get('config_language_id') . "') 
			LEFT JOIN " . DB_PREFIX . "ocfilter_option_description oood ON (ooovd.option_id = oood.option_id AND oood.language_id = '" . (int)$this->config->get('config_language_id') . "') 
			WHERE product_id = '" . (int)$product_id . "' 
			AND LENGTH(oood.name) 
			AND ooovd.option_id IN (SELECT option_id FROM " . DB_PREFIX . "ocfilter_option_to_category WHERE category_id = '" . (int)$category_id . "') 
			AND ooovd.option_id NOT IN (SELECT option_id FROM " . DB_PREFIX . "ocfilter_option WHERE status = 0)
			");
			
			return $query->rows;
		}
		
		public function getSimilarProductsByOcFilter($product_id, $category_id, $limit){
			
			$option_value_array = $this->getProductOCFilterSelectionFilters($product_id);		
			
			if ($option_value_array){
				
				$option_sql = array();
				foreach ($option_value_array as $option_value){			
					$option_sql[] = "oovtp.product_id IN (SELECT product_id FROM " . DB_PREFIX . "ocfilter_option_value_to_product WHERE option_id = '" . (int)$option_value['option_id'] . "' AND value_id IN (" . $this->db->escape($option_value['value_id']) . "))";	
				}
				$sql = "SELECT DISTINCT oovtp.product_id FROM " . DB_PREFIX . "ocfilter_option_value_to_product oovtp";
				$sql .=" LEFT JOIN " . DB_PREFIX . "product p ON (oovtp.product_id = p.product_id)";
				$sql .=" WHERE oovtp.product_id <> '" . (int)$product_id . "'";
				$sql .= " AND (" . implode(' AND ', $option_sql) . ")";
				$sql .= " AND oovtp.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "' AND main_category = 1) AND p.quantity > 0 AND p.status = 1 LIMIT " . (int)$limit . "";
				
				$query = $this->db->query($sql);
				
				$product_data = array();
				if ($query->num_rows){
					foreach ($query->rows as $row){
						$product_data[$row['product_id']] = $this->getProduct($row['product_id']);
					}
				}
				
				return $product_data;
				
				} else {
				return array();
			}
		}
		
		public function getSimilarProductsByAttribute($product_id, $category_id, $limit){
			$attribute_array = array(83);
			
			$attribute_groups = $this->getProductAttributes($product_id);
			$attribute_values = array();
			
			foreach ($attribute_groups as $attribute_group){
				foreach ($attribute_group['attribute'] as $attribute){					
					foreach ($attribute_array as $attribute_id_to_get){
						if ($attribute['attribute_id'] == $attribute_id_to_get){
							$attribute_values[$attribute_id_to_get] = $attribute['text'];
						}
					}
				}				
			}	
			
			$sql = "SELECT pa.product_id FROM " . DB_PREFIX . "product_attribute pa 
			LEFT JOIN " . DB_PREFIX . "product p ON (pa.product_id = p.product_id) 
			WHERE pa.product_id <> '" . (int)$product_id . "'
			AND language_id = '" . $this->config->get('config_language_id') . "'			";
			
			
			$_sql = array();
			foreach ($attribute_values as $attribute_id => $attribute_value){
				$_sql[] = " (TRIM(LOWER(text)) LIKE '" . $this->db->escape(trim(mb_strtolower($attribute_value))) . "')";
			}			
			
			if ($_sql) { 
				$sql .= " AND (" . implode(' OR ', $_sql) . ")";
			}
			$sql .= " AND pa.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "' AND main_category = 1) AND p.quantity > 0 AND p.status = 1 LIMIT " . (int)$limit . "";
			
			$query = $this->db->query($sql);
			
			$product_data = array();
			if ($query->num_rows){
				foreach ($query->rows as $row){
					$product_data[$row['product_id']] = $this->getProduct($row['product_id']);
				}
			}
			
			return $product_data;
		}
		
		public function getSimilarProductsByName($product_name, $product_id, $limit, $in_stock = false, $category_id = 0){
			$product_data = array();
			
			$sql = "SELECT DISTINCT pd.product_id FROM " . DB_PREFIX . "product_description pd 
			LEFT JOIN " . DB_PREFIX . "product p ON (pd.product_id = p.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
			LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id AND main_category = 1) 
			WHERE pd.language_id = '" . $this->config->get('config_language_id') . "' 
			AND TRIM(LCASE(pd.name)) LIKE ('" . $this->db->escape(trim(mb_strtolower($product_name))) . "%')
			AND pd.product_id <> '" . (int)$product_id . "'
			AND p.status = '1'
			AND p2c.category_id = '" . (int)$category_id . "'
			AND p.date_available <= NOW() 
			AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			if ($in_stock){
				$sql .= " AND p.quantity > 0";
			}
			
			$sql .= " ORDER BY RAND() LIMIT " . (int)$limit . "";								
			
			$query = $this->db->query($sql);
			
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			return $product_data;
		}
		
		
		
		public function getBestSellerProducts($limit, $stock_status = false) {
			$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
			
			if (!$product_data) {
				$product_data = array();
				
				//***mf begin
				$sql = "SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit;
				
				if(isset($stock_status) && $stock_status == true){
					$arr_pids = array();
					$sql_cat_prod = "SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id='62' OR category_id='99' OR category_id='64'";
					$query_cat_prod = $this->db->query($sql_cat_prod);
					foreach ($query_cat_prod->rows as $result) {
						$arr_pids[] = $result['product_id'];
					}				
					$sql = str_replace("GROUP BY", "AND p.quantity > 0 AND p.product_id NOT IN ('".implode("','",$arr_pids)."') GROUP BY", $sql);
				}
				//***mf end				
				
				$query = $this->db->query($sql);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
			}
			
			return $product_data;
		}
		
		public function getProductAttributes($product_id, $language_id = false) {

			if (!$language_id){
				$language_id = $this->config->get('config_language_id');
			}
			
			$product_attribute_group_data = $this->cache->get('product.attributes.' . $product_id . '.' . (int)$language_id . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'));
			
			if (!$product_attribute_group_data){
				
				$product_attribute_group_data = array();
				
				$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$language_id . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
				
				foreach ($product_attribute_group_query->rows as $product_attribute_group) {
					$product_attribute_data = array();
					
					$product_attribute_query = $this->db->query("SELECT a.attribute_id, a.highlight, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$language_id . "' AND pa.language_id = '" . (int)$language_id . "' ORDER BY a.sort_order, ad.name");
					
					foreach ($product_attribute_query->rows as $product_attribute) {
						$product_attribute_data[] = array(
						'attribute_id' => $product_attribute['attribute_id'],
						'highlight'    => $product_attribute['highlight'],
						'name'         => $product_attribute['name'],
						'text'         => $product_attribute['text']
						);
					}
					
					$product_attribute_group_data[] = array(
					'attribute_group_id' => $product_attribute_group['attribute_group_id'],
					'name'               => $product_attribute_group['name'],
					'attribute'          => $product_attribute_data
					);
				}
				
				$this->cache->set('product.attributes.' . $product_id . '.' . (int)$language_id . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'), $product_attribute_group_data);
				
			}
			
			return $product_attribute_group_data;
		}

		public function getProductOptionsUAForPromUa($option_id, $option_value_id) {
			$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "option_value_description WHERE option_id = '" . (int)$option_id . "' AND option_value_id = '" . (int)$option_value_id . "' AND language_id = 3");
			
			return $query->row['name'];
		}

		
		
        // oct_advanced_options_settings start
        public function getProductOptions($product_id) {
          $oct_advanced_options_settings_data = $this->config->get('oct_advanced_options_settings_data');
          $product_option_data = array();
          $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
          foreach ($product_option_query->rows as $product_option) {
            $product_option_value_data = array();
            if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) {
              if (!$oct_advanced_options_settings_data['quantity_status'] && $product_option['type'] == 'oct_quantity') {
              } else {
                $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
                foreach ($product_option_value_query->rows as $product_option_value) {
                  $product_option_value_data[] = array(
                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                    'option_value_id'         => $product_option_value['option_value_id'],
                    'name'                    => $product_option_value['name'],
                    'image'                   => $product_option_value['image'],
                    'o_v_image'               => $product_option_value['o_v_image'],
                    'sku'                     => (isset($product_option_value['sku'])) ? $product_option_value['sku'] : '',
					'ean'                     => (isset($product_option_value['ean'])) ? $product_option_value['ean'] : '',
                    'model'                   => (isset($product_option_value['model'])) ? $product_option_value['model'] : '',
                    'quantity'                => $product_option_value['quantity'],
                    'subtract'                => $product_option_value['subtract'],
                    'price'                   => $product_option_value['price'],
                    'price_prefix'            => $product_option_value['price_prefix'],
                    'weight'                  => $product_option_value['weight'],
                    'weight_prefix'           => $product_option_value['weight_prefix']
                  );
                }
                $product_option_data[] = array(
                  'product_option_id'    => $product_option['product_option_id'],
                  'product_option_value' => $product_option_value_data,
                  'option_id'            => $product_option['option_id'],
                  'name'                 => $product_option['name'],
                  'type'                 => $product_option['type'],
                  'value'                => $product_option['value'],
                  'required'             => $product_option['required']
                );
              }
            } else {
              $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
              foreach ($product_option_value_query->rows as $product_option_value) {
                $product_option_value_data[] = array(
                  // oct_advanced_options_settings start
                  'sku' => (isset($product_option_value['sku'])) ? $product_option_value['sku'] : '',
				   'ean' => (isset($product_option_value['ean'])) ? $product_option_value['ean'] : '',
                  'model' => (isset($product_option_value['model'])) ? $product_option_value['model'] : '',
                  // oct_advanced_options_settings end
                  'product_option_value_id' => $product_option_value['product_option_value_id'],
                  'option_value_id'         => $product_option_value['option_value_id'],
                  'name'                    => $product_option_value['name'],
                  'image'                   => $product_option_value['image'],
                  'quantity'                => $product_option_value['quantity'],
                  'subtract'                => $product_option_value['subtract'],
                  'price'                   => $product_option_value['price'],
                  'price_prefix'            => $product_option_value['price_prefix'],
                  'weight'                  => $product_option_value['weight'],
                  'weight_prefix'           => $product_option_value['weight_prefix']
                );
              }
              $product_option_data[] = array(
                'product_option_id'    => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id'            => $product_option['option_id'],
                'name'                 => $product_option['name'],
                'type'                 => $product_option['type'],
                'value'                => $product_option['value'],
                'required'             => $product_option['required']
              );
            }
          }
          return $product_option_data;
        }
        // oct_advanced_options_settings end
        public function getProductOptionsOld($product_id) {
      
			$product_option_data = array();
			
			$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");
			
			foreach ($product_option_query->rows as $product_option) {
				$product_option_value_data = array();
				
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(

        // oct_advanced_options_settings start
        'sku' => (isset($product_option_value['sku'])) ? $product_option_value['sku'] : '',
        'model' => (isset($product_option_value['model'])) ? $product_option_value['model'] : '',
        // oct_advanced_options_settings end
      
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}
				
				$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
				);
			}
			
			return $product_option_data;
		}
		
		public function getProductDiscounts($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");
			
			return $query->rows;
		}
		
		protected function getPath($parent_id, $current_path = '') {
			$this->load->model('catalog/category');	
			$category_info = $this->model_catalog_category->getCategory($parent_id);
			
			if ($category_info) {
				if (!$current_path) {
					$new_path = $category_info['category_id'];
					} else {
					$new_path = $category_info['category_id'] . '_' . $current_path;
				}
				
				$path = $this->getPath($category_info['parent_id'], $new_path);
				
				if ($path) {
					return $path;
					} else {
					return $new_path;
				}
			}
		}
		
		public function getProductImagesForFeeds($product_id) {
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND video_in_product = '' ORDER BY sort_order ASC");
			
			return $query->rows;
		
		}
		

        // oct_advanced_options_settings start
        public function getProductImagesByOptionValueId($product_id, $options) {
          $sql = "SELECT * FROM " . DB_PREFIX . "product_image pi LEFT JOIN " . DB_PREFIX . "oct_product_image_by_option pito ON (pi.product_image_id = pito.product_image_id) WHERE pi.product_id = '" . (int)$product_id . "'";
          $implode = array();
          foreach ($options as $option) {
            if ($option) {
              $implode[] = $option;
            }
          }
          $sql .= " AND pito.option_value_id IN (" . implode(',', $implode) . ") GROUP BY pi.image ORDER BY pi.sort_order ASC";
          $query = $this->db->query($sql);
          return $query->rows;
        }
        public function getProductOptionValueId($product_id, $product_option_value_id) {
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "'");
          if ($query->row) {
            return $query->row['option_value_id'];
          }
        }
        // oct_advanced_options_settings end
      
		public function getProductImages($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");
			$results = $query->rows;
			
			$query = $this->db->query("SELECT youtube_single FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
			if (!empty($query->row['youtube_single'])){
				$results[] = array(
				'image' 			=> '',
				'video_in_product' 	=> $query->row['youtube_single'],
				'sort_order' 		=> 100500
				);
			}
			
			return $results;
		}
		
		public function getProductRelated($product_id, $limit = false) {
			$product_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.archive = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.quantity > 0 LIMIT 10");//***
			
			foreach ($query->rows as $result) {
				$product = $this->getProduct($result['product_id']);
				$product['sort_order'] = 'rel';
				$product_data[$result['related_id']] = $product;
			}
			
			return $product_data;
		}
		
		public function getProductBoughtWith($product_id) {
			
			$product_data = array();
			
			$query = $this->db->query("SELECT DISTINCT op.product_id FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE op.order_id IN (SELECT order_id FROM " . DB_PREFIX . "order_product WHERE product_id = '" . (int)$product_id . "') AND op.product_id <> '" . (int)$product_id . "' AND p.status = '1' AND p.archive = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.quantity > 0 LIMIT 10");	
			
			foreach ($query->rows as $result) {
				$product = $this->getProduct($result['product_id']);
				$product['sort_order'] = $this->getProductBoughtTogetherCount($product_id, $result['product_id']);
				$product_data[$result['product_id']] = $product;
			}
			
			return $product_data;
			
		}
		
		public function getProductBoughtTogetherCount($product_id1, $product_id2) {
			$query = $this->db->query("SELECT COUNT(DISTINCT order_id) as cnt FROM " . DB_PREFIX . "order_product op WHERE order_id IN (SELECT DISTINCT order_id FROM " . DB_PREFIX . "order_product op1 WHERE product_id = '" . (int)$product_id1 . "') 
			AND order_id IN (SELECT DISTINCT order_id FROM " . DB_PREFIX . "order_product op2 WHERE product_id = '" . (int)$product_id2 . "')");
			
			return $query->row['cnt'];
			
			
		}
		
		public function getProductLayoutId($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
			if ($query->num_rows) {
				return $query->row['layout_id'];
				} else {
				return 0;
			}
		}
		
		public function getCategories($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			return $query->rows;
		}
		
		public function getCategoriesIDS($product_id) {
			$product_category_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $row){
				$product_category_data[$row['category_id']] = $row['category_id'];
			}
			
			return $product_category_data;
		}
		
		public function getMainCategoryID($product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1");
			
			if ($query->num_rows){
				return $query->row['category_id'];
				} else {
				return 0;
			}
			
		}
		
		public function getIfToShowBrandMainCategoryID($product_info) {
			$query = $this->db->query("SELECT c.hide_manufacturer 
			FROM " . DB_PREFIX . "product_to_category p2c 
			LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) 
			WHERE p2c.product_id = '" . (int)$product_info['product_id'] . "'
			ORDER BY p2c.main_category DESC LIMIT 1");
			
			if ($query->num_rows){
				return $query->row['hide_manufacturer'];
				} else {
				return 0;
			}
			
		}
		
		public function getProductMainCategoryAddonDiscount($product_id) {
			$query = $this->db->query("SELECT c.addon_discount FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) WHERE p2c.product_id = '" . (int)$product_id . "' ORDER BY p2c.main_category DESC LIMIT 1");
			
			if ($query->num_rows){
				return $query->row['addon_discount'];
				} else {
				return 0;
			}
			
		}
		
		public function getProductMainCategoryAFPDiscount($product_id) {
			$query = $this->db->query("SELECT c.afp_discount FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) WHERE p2c.product_id = '" . (int)$product_id . "' ORDER BY p2c.main_category DESC LIMIT 1");
			
			if ($query->num_rows){
				return $query->row['afp_discount'];
				} else {
				return 0;
			}
			
		}
		
		public function getCategoriesExtended($product_id) {
			$query = $this->db->query("SELECT *, c.taxonomy_id FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON p2c.category_id = c.category_id WHERE product_id = '" . (int)$product_id . "'");
			
			return $query->rows;
		}
		
		public function getTotalProducts($data = array()) {
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
					} else {
					$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
				}
				
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
					} else {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
				}
				} else {
				$sql .= " FROM " . DB_PREFIX . "product p";
			}
			
			

		// OCFilter start
		if (!empty($data['filter_ocfilter'])) {
    	$this->load->model('catalog/ocfilter');

      $ocfilter_product_sql = $this->model_catalog_ocfilter->getSearchSQL($data['filter_ocfilter']);
		} else {
      $ocfilter_product_sql = false;
    }

    if ($ocfilter_product_sql && $ocfilter_product_sql->join) {
    	$sql .= $ocfilter_product_sql->join;
    }
    // OCFilter end
      
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.archive = '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
				
				if (!empty($data['filter_filter'])) {
					$implode = array();
					
					$filters = explode(',', $data['filter_filter']);
					
					foreach ($filters as $filter_id) {
						$implode[] = (int)$filter_id;
					}
					
					$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
				}
			}
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
				
				if (!empty($data['filter_name'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
					
					if (!empty($data['filter_description'])) {
						$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_quantity'])) {
				$sql .= " AND p.quantity > 0";
			}
			
			if (!empty($data['filter_not_archive'])) {							
				$sql .= " AND p.archive = 0";
			}

			if (!empty($data['filter_not_tax'])) {							
				$sql .= " AND p.tax_class_id = '0'";
			}
			
			if (!empty($data['filter_status'])) {							
				$sql .= " AND p.status = 1";
			}
			
			if (!empty($data['filter_gmc'])) {
				$sql .= " AND p.dnmerchant = 0";
			}
			
			
			if (!empty($data['filter_category_id']) && !empty($data['filter_exclude_categories']) ) {
				$sql .= " AND p.product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id IN (" . implode(',', $data['filter_exclude_categories']) . "))";
			}
			

    // OCFilter start
    if (!empty($ocfilter_product_sql) && $ocfilter_product_sql->where) {
    	$sql .= $ocfilter_product_sql->where;
    }
    // OCFilter end
      
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
				if (!empty($data['filter_hide_manufacturer'])) {
					$sql .= " AND (p.product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE main_category = 1 AND p2c.category_id IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE hide_manufacturer = 1)))";
				}
			}
			
			
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getProductDescriptions($product_id) {
			$product_description_data = array();
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
			
			foreach ($query->rows as $result) {
				$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'fake_description' => $result['fake_description'],
				'highlight' 	   => $result['highlight'],
				'meta_title'       => $result['meta_title'],
				'meta_h1'          => $result['meta_h1'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
				);
			}
			
			return $product_description_data;
		}
		
		public function getProfile($product_id, $recurring_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "product_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.product_id = '" . (int)$product_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");
			
			return $query->row;
		}
		
		public function getProfiles($product_id) {
			$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "product_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.product_id = " . (int)$product_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");
			
			return $query->rows;
		}
		
		public function getTotalProductSpecials($data = array()) {
			//$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.quantity > '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");
			
			
			$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
					} else {
					$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
				}
				
				if (!empty($data['filter_filter'])) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
					} else {
					$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
				}
				} else {
				$sql .= " FROM " . DB_PREFIX . "product p";
			}
			
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";			
			$sql .= " WHERE p.status = '1' AND p.quantity > '0' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			
			if (!empty($data['filter_category_id'])) {
				if (!empty($data['filter_sub_category'])) {
					$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
					} else {
					$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
				}
				
				if (!empty($data['filter_filter'])) {
					$implode = array();
					
					$filters = explode(',', $data['filter_filter']);
					
					foreach ($filters as $filter_id) {
						$implode[] = (int)$filter_id;
					}
					
					$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
				}
			}
			
			$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_special ps WHERE ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end > NOW())))";
			
			if(isset($data['stock_status']) && $data['stock_status'] == true){
				$sql .= " AND p.product_id NOT IN (SELECT DISTINCT p2c.product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE main_category = 1 AND p2c.category_id IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE efs = 1))";
			}

			$query = $this->db->query($sql);
			
			if (isset($query->row['total'])) {
				return $query->row['total'];
				} else {
				return 0;
			}
		}

		public function getCategoryProductIsbns($category_id) {
			// Основной запрос для получения isbn всех товаров из категории
			$query = $this->db->query("
				SELECT DISTINCT p.isbn 
				FROM " . DB_PREFIX . "product p
				LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (p.product_id = pc.product_id)
				WHERE pc.category_id = '" . (int)$category_id . "'
			");
		
			$isbns = array();
			
			foreach ($query->rows as $result) {
				$isbns[] = $result['isbn'];
			}

			return $isbns;
		}

		public function getCategoryProductIDs($category_id) {
			// Основной запрос для получения product_id всех товаров из категории
			$query = $this->db->query("
				SELECT DISTINCT p.product_id
				FROM " . DB_PREFIX . "product p
				LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (p.product_id = pc.product_id)
				WHERE pc.category_id = '" . (int)$category_id . "'
			");
		
			$product_ids = array();
			
			foreach ($query->rows as $result) {
				$product_ids[] = $result['product_id'];
			}

			return $product_ids;
		}

		public function getCategoryProductOptionIsbns($category_id) {
			// SQL-запит для отримання унікальних ISBN-опцій
			$query = $this->db->query("
				SELECT DISTINCT pov.isbn
				FROM " . DB_PREFIX . "product_option_value pov
				INNER JOIN " . DB_PREFIX . "product_to_category pc ON (pov.product_id = pc.product_id)
				WHERE pc.category_id = '" . (int)$category_id . "'
				AND pov.isbn IS NOT NULL
			");
		
			$isbns = array();
		
			foreach ($query->rows as $result) {
				$isbns[] = $result['isbn'];
			}
		
			return $isbns;
		}		
		

		public function removeSingleOptionAndValueForCategory($category_id, $option_id) {
			// SQL-запрос для получения всех товаров в указанной категории, у которых есть только одно значение указанной опции
			$query = $this->db->query("
				SELECT pov.product_id, COUNT(pov.product_option_value_id) AS option_value_count
				FROM " . DB_PREFIX . "product_option_value pov
				INNER JOIN " . DB_PREFIX . "product_to_category pc ON (pov.product_id = pc.product_id)
				WHERE pc.category_id = '" . (int)$category_id . "'
				AND pov.option_id = '" . (int)$option_id . "'
				GROUP BY pov.product_id
				HAVING option_value_count <= 1
			");
		
			// Массив для хранения ID товаров, которые нужно обновить
			$products_to_update = array();
		
			foreach ($query->rows as $result) {
				$products_to_update[] = $result['product_id'];
			}
		
			// Если найдены товары с одной опцией, удаляем значения опции и саму опцию
			if (!empty($products_to_update)) {
				// Удаляем значения опции
				$this->db->query("
					DELETE FROM " . DB_PREFIX . "product_option_value
					WHERE product_id IN (" . implode(",", $products_to_update) . ")
					AND option_id = '" . (int)$option_id . "'
				");
		
				// Удаляем саму опцию из товаров
				$this->db->query("
					DELETE FROM " . DB_PREFIX . "product_option
					WHERE product_id IN (" . implode(",", $products_to_update) . ")
					AND option_id = '" . (int)$option_id . "'
				");
			}
		
			return count($products_to_update); // Возвращаем количество обновленных товаров
		}

		public function getProductsWithoutOptions($category_id) {
			// Массив для хранения ISBN товаров
			$isbns = array();
			
			// SQL-запрос для получения ISBN товаров из категории с указанным ID, у которых нет опций
			$query = $this->db->query("
				SELECT p.isbn
				FROM " . DB_PREFIX . "product p
				INNER JOIN " . DB_PREFIX . "product_to_category pc ON p.product_id = pc.product_id
				LEFT JOIN " . DB_PREFIX . "product_option po ON p.product_id = po.product_id
				WHERE pc.category_id = '" . (int)$category_id . "'
				AND po.product_option_id IS NULL
			");
			
			// Перебираем результат запроса и добавляем ISBN в массив
			foreach ($query->rows as $row) {
				$isbns[] = $row['isbn'];
			}
			
			// Возвращаем массив ISBN товаров без опций
			return $isbns;
		}	
		
		public function removeOptionsNotInOffers($offers_isbn, $option_isbns, $category_id) {
			// Находим те SKU из $option_isbns, которых нет в $offers_isbn
			$sku_to_delete = array_diff($option_isbns, $offers_isbn);
		
			// Если есть значения для удаления, удаляем их из базы данных
			if (!empty($sku_to_delete)) {
				// Преобразуем массив в строку для использования в SQL запросе
				$sku_to_delete_str = "'" . implode("','", $sku_to_delete) . "'";
		
				// Обнуляем все виды остатков значений опций, которых нет в массиве $offers_isbn
				// и которые принадлежат товарам из категории с ID
				$this->db->query("
					UPDATE " . DB_PREFIX . "product_option_value pov
					INNER JOIN " . DB_PREFIX . "product_to_category ptc ON pov.product_id = ptc.product_id
					SET pov.supplier = 0, pov.stock = 0, pov.quantity = 0
					WHERE pov.isbn IN (" . $sku_to_delete_str . ")
					AND ptc.category_id = " . $category_id . "
				");
			}
		
			return count($sku_to_delete); // Возвращаем количество удаленных опций
		}		

		public function removeProductsNotInOffers($offers_isbn, $single_products, $category_id) {
			// Находим те SKU из $option_isbns, которых нет в $offers_isbn
			$products_to_delete = array_diff($single_products, $offers_isbn);
		
			// Если есть значения для удаления, удаляем их из базы данных
			if (!empty($products_to_delete)) {
				// Преобразуем массив в строку для использования в SQL запросе
				$products_to_delete_str = "'" . implode("','", $products_to_delete) . "'";
		
				// Обнуляем все виды остатков значений опций, которых нет в массиве $offers_isbn
				$this->db->query("
					UPDATE " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id
					SET p.supplier = 0, p.stock = 0, p.quantity = 0
					WHERE isbn IN (" . $products_to_delete_str . ")		
					AND ptc.category_id = " . $category_id . "
				");
			}

			// "UPDATE oc_product SET supplier = quantity - stock WHERE product_id='".(int)$product_id."'"
		
			return count($products_to_delete); // Возвращаем количество удаленных опций
		}

		public function getProductsByCategoryWithZeroOptions($category_id) {
			// Спочатку отримуємо ID товарів, які відповідають умовам
			$sql = "SELECT p.product_id
					FROM " . DB_PREFIX . "product p
					INNER JOIN " . DB_PREFIX . "product_to_category p2c ON p.product_id = p2c.product_id
					JOIN " . DB_PREFIX . "product_option_value pov ON p.product_id = pov.product_id
					WHERE p2c.category_id = '" . (int)$category_id . "'
					GROUP BY p.product_id
					HAVING COUNT(pov.product_option_value_id) = SUM(pov.quantity = 0)";
			

			$query = $this->db->query($sql);
			
			
			$product_ids = array();
			
			foreach ($query->rows as $result) {
				$product_ids[] = (int)$result['product_id'];
			}
	
			// Якщо є товари, які відповідають умовам, оновлюємо їх залишки
			if (!empty($product_ids)) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = 0, stock = 0, supplier = 0 WHERE product_id IN (" . implode(',', $product_ids) . ")");
			}
	
			return $product_ids; // Повертаємо ID товарів для підтвердження
		}
	}
