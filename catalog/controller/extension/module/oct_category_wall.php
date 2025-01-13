<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	/**************************************************************/
	
	class ControllerExtensionModuleOctCategoryWall extends Controller {
		public function index($setting) {
			
			if ($return_data = $this->cache->get('ControllerExtensionModuleOctCategoryWall' . $this->config->get('config_language_id') . md5(serialize($setting)))){
				return $return_data;
			}
			
			
			$this->load->language('extension/module/oct_category_wall');
			
			$data['heading_title'] = $setting['heading'][$this->session->data['language']];
			$data['text_see_more'] = $this->language->get('text_see_more');
			
			$data['position'] = $setting['position'];
			$data['limit']    = $setting['limit'];
			$data['lang'] = $this->language->get('code');
			$this->load->model('catalog/category');
			$this->load->model('tool/image');
			
			$data['categories'] = array();
			
			if ($setting['autoselect']){
				$this->load->model('extension/module/category');
				
				$filter_data = array(
				'filter_not_main'				=> false,
				'filter_bestseller' 			=> true,
				'filter_bestseller_explicit' 	=> true,
				'filter_quantity'				=> true,
				'filter_product_limit'			=> 5,
				'start'							=> 0,
				'limit'							=> (int)$setting['limit']
				);
				
				$bestSellerCategories = $this->model_extension_module_category->topViewedCategories($filter_data);

				if ($bestSellerCategories){
					
				/*	foreach ($bestSellerCategories as $special_category_id => $category){
						$count = $this->db->query("SELECT COUNT(op.order_id) as total
						FROM `" . DB_PREFIX . "order_product` op 
						LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id)
						LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) 
						LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
						WHERE op.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category p2c WHERE category_id = '" . (int)$special_category_id	. "') 				
						AND o.order_status_id > 0 
						AND DATE(o.date_added) >= '" . date('Y-m-d', strtotime('-180 day')) . "'
						AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
						AND p.quantity > 0 
						AND p.archive = 0");
						
						$categories[$special_category_id] = $count->row['total'];
					}
					
					arsort($categories);
				*/
					
					$setting['module_categories'] = array();
					
					foreach ($bestSellerCategories as $category_id){
						$setting['module_categories'][] = $category_id;
					}	
				}								
			}
			

			if (isset($setting['module_categories']) && $setting['module_categories']) {
				
				foreach ($setting['module_categories'] as $category_id) {
					$category_info = $this->model_catalog_category->getCategory($category_id);
					
					if ($category_info) {
						if ($category_info['image']) {
							$category_image = $this->model_tool_image->resize($category_info['image'], $setting['width'], $setting['height']);				
							} else {
							$category_image = $this->model_tool_image->resize('no-image.png', $setting['width'], $setting['height']);
						}
						
						$sub_categories = array();
						
						if ($setting['show_sub_categories']) {
							$category_children = $this->model_catalog_category->getCategories($category_id);
							
							foreach ($category_children as $child) {
								$sub_categories[] = array(
								'name' => $child['name'],
								'href' => $this->url->link('product/category', 'path=' . $category_id . '_' . $child['category_id'])
								);
							}
						}
						
						$cs_sort_order = array();
						
						foreach ($sub_categories as $key => $value) {
							$cs_sort_order[$key] = $value['name'];
						}
						
						array_multisort($cs_sort_order, SORT_ASC, $sub_categories);
						
						$data['categories'][] = array(
						'category_id' => $category_info['category_id'],
						'sort_order' => $category_info['sort_order'],
						'thumb' => ($setting['show_image']) ? $category_image : false,		
						'name' => $category_info['name'],
						'children' => $sub_categories,
						'href' => $this->url->link('product/category', 'path=' . $category_info['category_id'])
						);
					}
				}
				
				if (!$setting['autoselect']){
					$c_sort_order = array();
					
					foreach ($data['categories'] as $key => $value) {
						$c_sort_order[$key] = $value['name'];
					}
					
					array_multisort($c_sort_order, SORT_ASC, $data['categories']);
				}
				
				$return_data = $this->load->view('extension/module/oct_category_wall', $data);
				$this->cache->set('ControllerExtensionModuleOctCategoryWall' . $this->config->get('config_language_id') . md5(serialize($setting)), $return_data);
				return $return_data;
				
			}
		}
	}								