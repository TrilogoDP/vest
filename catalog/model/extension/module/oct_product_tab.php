<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	/**************************************************************/
	
	class ModelExtensionModuleOctProductTab extends Model {
		public function getTopViewedProducts($limit, $stock_status = false) {
			$product_data = $this->cache->get('product.top_viewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
			$this->load->model('catalog/product');
			
			if (!$product_data) {
				$product_data = array();
				
				//***mf begin
				$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC LIMIT " . (int)$limit;
				if(isset($stock_status) && $stock_status == true){
					
					$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.quantity > 0 AND p.product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id='62' OR category_id='99' OR category_id='64' OR category_id='108') ORDER BY p.viewed DESC LIMIT " . (int)$limit;
				}
				//***mf end				
				
				$query = $this->db->query($sql);
				
				foreach ($query->rows as $result) {
					$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
				}
				
				$this->cache->set('product.top_viewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
			}
			
			return $product_data;
		}
		
		public function getTopViewedCategoriesWithProducts($limit, $product_limit){
			$data = array();
			$categories = array();
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('extension/module/category');
			
			$filter_data = array(
			'filter_not_main'=> true,
			'filter_top_viewed' => true,
			'filter_quantity'=> true,
			'filter_product_limit' => 5
			);
			
			$topViewedCategories = $this->model_extension_module_category->getCategories($filter_data);
			
			
			if ($topViewedCategories){					
			
				$temp_sort = array();
				foreach ($topViewedCategories as $special_category_id => $specialCount){
					$temp_sort[$special_category_id] = $specialCount['count'];
				}	
				
				arsort($temp_sort);
				
				$counter = 1;
				foreach ($temp_sort as $category_id => $count){
					$products = array();
					
					$filter_data = array(
					'sort' 					=> 'p.viewed',
					'order' 				=> 'DESC',
					'filter_category_id'	=> $category_id,
					'filter_quantity'		=> true,
					'filter_not_archive'	=> true,
					'start'					=> 0,
					'limit'					=> (int)$product_limit
					);
					
					$data[$category_id] = array(
					'category' => $this->model_catalog_category->getCategory($category_id),
					'products' => $this->model_catalog_product->getProducts($filter_data)
					);
					
					if ($counter == $limit){
						break;
					}
					
					$counter++;
				}
				
			}
			
			return $data;
		}
		
		public function getBestSellerCategoriesWithProducts($limit, $product_limit){
			$data = array();
			$categories = array();
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('extension/module/category');
			
			$filter_data = array(
			'filter_not_main'=> true,
			'filter_bestseller' => true,
			'filter_quantity'=> true,
			'filter_product_limit' => 5
			);
			
			$bestSellerCategories = $this->model_extension_module_category->getCategories($filter_data);
			
			
			if ($bestSellerCategories){
				
				//Посчитаем товары
				foreach ($bestSellerCategories as $special_category_id => $specialCount){
					$count = $this->db->query("SELECT COUNT(op.order_id) as total
					FROM `" . DB_PREFIX . "order_product` op 
					LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id)
					LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) 
					LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
					WHERE op.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE category_id = '" . (int)$special_category_id	. "') 				
					AND o.order_status_id > 0 
					AND DATE(o.date_added) >= '" . date('Y-m-d', strtotime('-30 day')) . "'
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
					AND p.quantity > 0 
					AND p.archive = 0");
					
					$categories[$special_category_id] = $count->row['total'];
				}
				
				arsort($categories);
				
				$counter = 1;
				foreach ($categories as $category_id => $count){
					$products = array();
					
					$filter_data = array(
					'sort' 					=> 'rating',
					'order' 				=> 'DESC',
					'filter_category_id'	=> $category_id,
					'filter_quantity'		=> true,
					'filter_not_archive'	=> true,
					'start'					=> 0,
					'limit'					=> (int)$product_limit
					);
					
					$data[$category_id] = array(
					'category' => $this->model_catalog_category->getCategory($category_id),
					'products' => $this->model_catalog_product->getProducts($filter_data)
					);
					
					if ($counter == $limit){
						break;
					}
					
					$counter++;
				}
				
			}
			
			return $data;
		}
		
		
		public function getNewCategoriesWithProducts($limit, $product_limit){
			$data = array();
			$categories = array();
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('extension/module/category');
			
			$filter_data = array(
			'filter_not_main'=> true,
			'filter_new' => true,
			'filter_quantity'=> true,
			'filter_product_limit' => 5
			);
			
			$newCategories = $this->model_extension_module_category->getCategories($filter_data);
			
			
			if ($newCategories){
				
				//Посчитаем товары
				foreach ($newCategories as $special_category_id => $specialCount){
					$count = $this->db->query("SELECT COUNT(op.order_id) as total
					FROM `" . DB_PREFIX . "order_product` op 
					LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id)
					LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) 
					LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
					WHERE op.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE category_id = '" . (int)$special_category_id	. "') 				
					AND o.order_status_id > 0 
					AND DATE(o.date_added) >= '" . date('Y-m-d', strtotime('-30 day')) . "'
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
					AND p.quantity > 0 
					AND p.archive = 0");
					
					$categories[$special_category_id] = $count->row['total'];
				}
				
				arsort($categories);
				
				$counter = 1;
				foreach ($categories as $category_id => $count){
					$products = array();
					
					$filter_data = array(
					'sort' 					=> 'p.date_added',
					'order' 				=> 'DESC',
					'filter_category_id'	=> $category_id,
					'filter_quantity'		=> true,
					'filter_not_archive'	=> true,
					'start'					=> 0,
					'limit'					=> (int)$product_limit
					);
					
					$data[$category_id] = array(
					'category' => $this->model_catalog_category->getCategory($category_id),
					'products' => $this->model_catalog_product->getProducts($filter_data)
					);
					
					if ($counter == $limit){
						break;
					}
					
					$counter++;
				}
				
			}
			
			
			return $data;
		}
		
		
		public function getActionCategoriesWithProducts($limit, $product_limit){
			$data = array();
			$categories = array();
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('extension/module/category');
			
			$filter_data = array(
			'filter_special' => true,
			//	'limit'			 => $limit,
			'filter_not_main'=> true,
			'filter_quantity'=> true,
			'filter_product_limit' => 5
			);
			
			$specialCategories = $this->model_extension_module_category->getCategories($filter_data);
			
			if ($specialCategories){
				
				//Посчитаем товары
				foreach ($specialCategories as $special_category_id => $specialCount){
					$count = $this->db->query("SELECT COUNT(op.order_id) as total
					FROM `" . DB_PREFIX . "order_product` op 
					LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id)
					LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) 
					LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
					WHERE op.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE category_id = '" . (int)$special_category_id	. "') 				
					AND o.order_status_id > 0 
					AND DATE(o.date_added) >= '" . date('Y-m-d', strtotime('-30 day')) . "'
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
					AND p.quantity > 0 
					AND p.archive = 0");
					
					$categories[$special_category_id] = $count->row['total'];
				}
				
				arsort($categories);
				
				$counter = 1;
				foreach ($categories as $category_id => $count){
					$products = array();
					
					$filter_data = array(
					'sort' 					=> 'rating',
					'order' 				=> 'DESC',
					'filter_category_id'	=> $category_id,
					'filter_special'		=> true,
					'filter_quantity'		=> true,
					'filter_not_archive'	=> true,
					'start'					=> 0,
					'limit'					=> (int)$product_limit
					);
					
					$data[$category_id] = array(
					'category' => $this->model_catalog_category->getCategory($category_id),
					'products' => $this->model_catalog_product->getProducts($filter_data)
					);
					
					if ($counter == $limit){
						break;
					}
					
					$counter++;
				}
				
			}
			
			
			return $data;
			
			
		}
	}					