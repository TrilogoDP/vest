<?php
	class ControllerExtensionModuleCategory extends Controller {
		public function index() {
			
			$this->load->language('extension/module/category');
			$this->load->model('catalog/product');
			$this->document->addStyle('catalog/view/theme/default/stylesheet/ocfilter/ocfilter.css');
			
			$data['heading_title'] = $this->language->get('heading_title');
			$data['text_filter'] = $this->language->get('text_filter');
			
			if (isset($this->request->get['path'])) {
				$parts = explode('_', (string)$this->request->get['path']);
				} else {
				$parts = array();
			}
			
			if (isset($this->request->get['search'])) {
				$search = $this->request->get['search'];
				} else {
				$search = '';
			}
			
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = $this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = '';
			}	
			
			if (isset($this->request->get['tag'])) {
				$tag = $this->request->get['tag'];
				} elseif (isset($this->request->get['search'])) {
				$tag = $this->request->get['search'];
				} else {
				$tag = '';
			}
			
			if (isset($parts[0])) {
				$data['category_id'] = $parts[0];
				} else {
				$data['category_id'] = 0;
			}
			
			if (isset($parts[1])) {
				$data['child_id'] = $parts[1];
				} else {
				$data['child_id'] = 0;
			}
			
			if (isset($parts[2])) {
				$data['child2_id'] = $parts[2];
				} else {
				$data['child2_id'] = 0;
			}
			
			if (isset($parts[3])) {
				$data['child3_id'] = $parts[3];
				} else {
				$data['child3_id'] = 0;
			}
			
			$this->load->model('catalog/category');									
			$this->load->model('catalog/product');
			
			
			$data['is_search_page'] = (isset($this->request->get['route']) && $this->request->get['route'] == 'product/search');
			$data['is_special_page'] = (isset($this->request->get['route']) && $this->request->get['route'] == 'product/special');
			$data['is_manufacturer_page'] = (isset($this->request->get['route']) && $this->request->get['route'] == 'product/manufacturer/info' && $manufacturer_id);
			
			
			$this->load->model('extension/module/category');	
			$filter_data = array(
			'filter_name'         => $search,
			'filter_tag'          => $tag			
			);		
			
			
			
			if ($data['is_search_page']){
				$result_all_categories = false;
			}
			
			if ($data['is_special_page']){
				$result_all_categories = false;				
			}
			
			$hrefAddon = '';
			if ($data['is_manufacturer_page']){
				$result_all_categories = false;
				$hrefAddon = '&manufacturer_id=' . $manufacturer_id;
			}
			
			
			if (!$result_all_categories) {
				
				if ($data['is_search_page']){
					$categories = $this->model_extension_module_category->getCategories($filter_data);
					
					} elseif ($data['is_special_page']) {
					
					$filter_data = array(
					'filter_special' => true
					);
					
					$categories = $this->model_extension_module_category->getCategories($filter_data);
					
					} elseif ($data['is_manufacturer_page']) {
					
					$filter_data = array(
					'filter_manufacturer_id' => $manufacturer_id
					);
					
					$categories = $this->model_extension_module_category->getCategories($filter_data);
					
					} else {
					$categories = $this->model_catalog_category->getCategories(0);	
				}							
				
				foreach ($categories as $category) {
					$children_data = array();
					
					$children = $this->model_catalog_category->getCategories($category['category_id']);												
					
					foreach ($children as $child) {
						$children_data_level2 = array();
						
						$children_level2 = $this->model_catalog_category->getCategories($child['category_id']);				
						
						foreach ($children_level2 as $child_level2) {
							$data_level2 = array(
							'filter_category_id'  => $child_level2['category_id'],
							'filter_sub_category' => true
							);
							
							$children_data_level3 = array();
							
							$children_level3 = $this->model_catalog_category->getCategories($child_level2['category_id']);				
							
							foreach ($children_level3 as $child_level3) {
								
								$data_level3 = array(
								'filter_category_id'  => $child_level3['category_id'],
								'filter_sub_category' => true
								);
								
								$children_data_level3[] = array(
								'category_id' => $child_level3['category_id'],
								'name'  			=>  $child_level3['name'],
								'href'  			=> $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child_level2['category_id'] . '_' . $child_level3['category_id']  . $hrefAddon),
								'id' 					=> $category['category_id']. '_' . $child['category_id']. '_' . $child_level2['category_id'] . '_' . $child_level3['category_id']
								);
							}
							
							
							$children_data_level2[] = array(
							'category_id' 		=> $child_level2['category_id'],
							'name'  			=>  $child_level2['name'],
							'children3'   		=> $children_data_level3,
							'href'  			=> $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child_level2['category_id']  . $hrefAddon),
							'id' 				=> $category['category_id']. '_' . $child['category_id']. '_' . $child_level2['category_id']
							);
						}
						
						$children_data[] = array(
						'category_id' => $child['category_id'],
						'name'        => $child['name'],
						'children2'   => $children_data_level2,
						'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . $hrefAddon) 
						);		
					}
					
					$url = '';
					
					if (isset($this->request->get['search'])) {					
						$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
					}				
					
					if (isset($this->request->get['tag'])) {
						$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
					}
					
					if ($data['is_search_page']) {
						$href = $this->url->link('product/search', 'category_id=' . $category['category_id']  . $hrefAddon)  . $url;
						} elseif ($data['is_special_page']) {
						$href = $this->url->link('product/special', 'category_id=' . $category['category_id'])  . $url;
					} else {
						$href = $this->url->link('product/category', 'path=' . $category['category_id']  . $hrefAddon);
					}

					if ($data['is_special_page']){
						$filter_data = array(			
							'stock_status' 			=> true,
							'filter_not_archive' 	=> true,
							'filter_category_id'  	=> $category['category_id'],
						);

						$count = $this->model_catalog_product->getTotalProductSpecials($filter_data);
					} else {
						$count = $category['count'];
					}
					
					$result_all_categories[] = array(
						'category_id' => $category['category_id'],
						'name'        => $category['name'],
						'count'       => $count,
						'children'    => $children_data,				
						'href'        => $href
					);	
				}
				
			}
			
			$data['categories'] = $result_all_categories;			
			
			if ($data['is_search_page']){
				return $this->load->view('extension/module/categorysearchfilter', $data);
				} elseif($data['is_special_page']) {
				return $this->load->view('extension/module/categorysearchfilter', $data);
				} elseif($data['is_manufacturer_page']) {
				return $this->load->view('extension/module/categorysearchfilter', $data);
				} else {
				return $this->load->view('extension/module/category', $data);
			}		
		}
	}															