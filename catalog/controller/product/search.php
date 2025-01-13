<?php
	class ControllerProductSearch extends Controller {
		
		private $search = '';
		private $filter_category_id = 0;
		private $filter_manufacturer_id = 0;
		private $elasticSearch;
		
		public function __construct($registry){						
			parent::__construct($registry);
			
			$this->load->library('hobotix/ElasticSearch');
			$this->elasticSearch = new hobotix\ElasticSearch($registry);
			
			
		}
		
		private function elasticSingleProductResult($results){
			$this->load->model('catalog/product');
			
			if (!empty($results['hits']['hits'][0]) && !empty($results['hits']['hits'][0]['_source']) && !empty($results['hits']['hits'][0]['_source']['product_id'])){
				
				if ($product = $this->model_catalog_product->getProduct($results['hits']['hits'][0]['_source']['product_id'])){
					
					return $product;
				}
			}
			
			return false;
		}
		
		private function elasticResults($results, $field){
			$this->load->model('catalog/product');
			
			$product_data = array();
			$manufacturer_data = array();	
			
			foreach ($results['hits']['hits'] as $hit){
				
				$name = $hit['_source'][$field];
				
				if (!empty($hit['highlight'][$field]) && $hit['highlight'][$field]){
					$name = $hit['highlight'][$field][0];
				}
				
				//$product_data[$hit['_source']['product_id']] = $product;
				//$product_data[$hit['_source']['product_id']]['name'] = $name;
				
				if ($product = $this->model_catalog_product->getProduct($hit['_source']['product_id'])){
					$product_data[$hit['_source']['product_id']] = $product;
					$product_data[$hit['_source']['product_id']]['name'] = $name;
					} else {
					//Товар не найден, нужно его удалить из индекса))
					$this->elasticSearch->deleteUnexistentProduct($hit['_source']['product_id']);
				}
			}
			
			return ['product_data' => $product_data, 'manufacturer_data' => $manufacturer_data];
		}
		
		private function elasticResultsCMA($results, $field, $exact){
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$data = array();
			
			foreach ($results['hits']['hits'] as $hit){
				$href 		= '';
				$id 		= '';
				$idtype 	= '';
				$type 		= '';
				
				$name = $hit['_source'][$field];
				
				if ($exact && !empty($hit['highlight'][$field])){
					$name = $hit['highlight'][$field][0];
				}
				
				$name = $this->elasticSearch->checkUAName($name);
				
				if (!empty($hit['_source']['product_id'])){
					
					$href 	= $this->url->link('product/product', 'product_id=' . $hit['_source']['product_id']);					
					$id 	= $hit['_source']['product_id'];
					$idtype = 'p' . $hit['_source']['product_id'];
					$type 	= 'p';
					
					
					} elseif ($hit['_source']['category_id'] && $hit['_source']['ocfilter_filter'] && $hit['_source']['ocfilter_page_id']) {
					
					$href = $this->url->link('product/category', 'path=' . $hit['_source']['category_id']);
					$href = rtrim($href, '/');
					$hit['_source']['ocfilter_page_keyword'] = trim($hit['_source']['ocfilter_page_keyword']);
					$hit['_source']['ocfilter_filter_params'] = trim($hit['_source']['ocfilter_filter_params']);
					
					if ($hit['_source']['ocfilter_page_keyword']) {
						$href .= '/' . $hit['_source']['ocfilter_page_keyword'];
						} else {
						$href .= '/' . $hit['_source']['ocfilter_filter_params'];
					}
					
					$href 	= $href;
					$id 	= $hit['_source']['ocfilter_page_id'];
					$idtype = 'ocfp' . $hit['_source']['ocfilter_page_id'];
					$type 	= 'ocfp';
					
					} elseif ($hit['_source']['category_id']) {
					
					$href 	= $this->url->link('product/category', 'path=' . $hit['_source']['category_id']);
					$id 	= $hit['_source']['category_id'];
					$idtype = 'c' . $hit['_source']['category_id'];
					$type 	= 'c';
					
				}
				
				$data[$idtype] = array(
				'name' 		=> $name,
				'href' 		=> $href,
				'id'   		=> $id,
				'idtype'   	=> $idtype,	
				'type' 		=> $type
				);	
			}
			
			return $data;
		}
		
		private function prepareCategories($results){
			$this->load->model('catalog/category');
			$data = array();
			
			foreach ($results as $result){
				
				$category = $this->model_catalog_category->getCategory($result['key']);	
				
				if ($category){
					
					$href = $this->url->link('product/search', 'search=' . $this->search . '&filter_category_id=' . $category['category_id']);
					
					$data[] = array(
					'name' 		=> $this->elasticSearch->checkUAName($category['name']),
					'active' 	=> ($category['category_id'] == $this->filter_category_id),
					'href' 		=> $href,
					'count' 	=> $result['doc_count']
					);
					
				}
			}
			
			
			return $data;
		}		
		
		private function prepareManufacturers($results){
			$this->load->model('catalog/manufacturer');
			
			
			foreach ($results as $result){
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($result['key']);	
				
				if ($manufacturer){
					
					$data[] = array(
					'name' 	=> $this->elasticSearch->checkUAName($manufacturer['name']),
					'active' 	=> ($manufacturer['manufacturer_id'] == $this->filter_manufacturer_id),
					'href' 	=> $this->url->link('product/search', 'search=' . $this->search . '&filter_manufacturer_id=' . $manufacturer['manufacturer_id']),
					'count' => $result['doc_count']
					);
					
				}
			}
			
			return $data;
		}
		
		public function index() {

        $data['oct_popup_view_data'] = $this->config->get('oct_popup_view_data');
        $data['button_popup_view'] = $this->language->get('button_popup_view');
      
			$this->load->language('product/search');			

		$oct_data = $this->config->get('oct_techstore_data');

		if (isset($oct_data['oct_lazyload']) && $oct_data['oct_lazyload'] == 1) {
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
	  
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('account/search');
			


			if (isset($this->request->get['search']) && strpos($this->request->get['search'],'Ð') !== false){
				$this->request->get['search'] = mb_convert_encoding($this->request->get['search'], "pass", "auto");
			}			
			
			if ($this->model_account_search->limitSearch(['ip' => $this->request->server['REMOTE_ADDR']]) > 50){
				addToFirewall($this->request->server['REMOTE_ADDR']);
				
				$this->response->redirect($this->request->server['REMOTE_ADDR']);
			}
			
			$this->load->model('tool/image');
			
			if (isset($this->request->get['search'])) {
				$search = $this->request->get['search'];
				} else {
				$search = '';
			}
			
			if (isset($this->request->get['tag'])) {
				$tag = $this->request->get['tag'];
				} elseif (isset($this->request->get['search'])) {
				$tag = $this->request->get['search'];
				} else {
				$tag = '';
			}
			
			if (isset($this->request->get['description'])) {
				$description = $this->request->get['description'];
				} else {
				$description = '';
			}
			
			if (isset($this->request->get['category_id'])) {
				$category_id = $this->request->get['category_id'];
				} else {
				$category_id = 0;
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
				} else {
				$filter_category_id = 0;
			} 
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
				} else {
				$filter_manufacturer_id = 0;
			} 
			
			if (isset($this->request->get['sub_category'])) {
				$sub_category = $this->request->get['sub_category'];
				} else {
				$sub_category = '';
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				// $sort = 'p.sort_order';
				$sort = 'rating';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				// $order = 'ASC';
				$order = 'DESC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = (int)$this->request->get['limit'];
				} else {
				// $limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
				$limit = 20;
			}
			
			if (isset($this->request->get['search'])) {
				$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
				} elseif (isset($this->request->get['tag'])) {
				$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
				} else {
				$this->document->setTitle($this->language->get('heading_title'));
			}
			
			if (!empty($this->request->get['page']) && (int)$this->request->get['page'] > 1){
				$data['seo_page'] = sprintf($this->language->get('text_page'), (int)$this->request->get['page']);			
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
			
			//***mf begin
			if (isset($this->request->get['ajax_mf'])) {
				$data['ajax_mf'] = true;
				}else{
				$data['ajax_mf'] = false;
			}
			//***mf end
			
			$url = '';
			
			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}
			
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}
			
			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_manufacturer_id'])) {
				$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/search', $url)
			);
			
			if (isset($this->request->get['search'])) {
				$data['heading_title'] = $this->language->get('heading_title') .  ' `<i>' . $this->request->get['search'] . '</i>`';
				} else {
				$data['heading_title'] = $this->language->get('heading_title');
			}		
			
			if ($category_id && $search) {
				$this->load->model('catalog/category');
				$category = $this->model_catalog_category->getCategory($this->request->get['category_id']);	
				
				if ($category){
					$data['heading_title'] .= ' в ' . $category['name'] . '';
				}
			}
			
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_search'] = $this->language->get('text_search');
			$data['text_keyword'] = $this->language->get('text_keyword');
			$data['text_category'] = $this->language->get('text_category');
			$data['text_sub_category'] = $this->language->get('text_sub_category');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');
			$data['text_go2cat'] = $this->language->get('text_go2cat');
			$data['mf_text_request'] = $this->language->get('mf_text_request');//***mf
			$data['mf_text_request_no'] = $this->language->get('mf_text_request_no');//***mf
			$data['mf_search'] = $this->request->get['search'];//***mf
			
			$data['entry_search'] = $this->language->get('entry_search');
			$data['entry_description'] = $this->language->get('entry_description');
			
			$data['button_search'] = $this->language->get('button_search');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');
			$data['text_gift'] = $this->language->get('text_gift');
			$data['button_write_review'] = $this->language->get('button_write_review');
			
			$data['compare'] = $this->url->link('product/compare');
			
			$this->load->model('catalog/category');
			
			$data['products'] = array();
			
			if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
				$filter_data = array(
				'filter_name'         => $search,
				'filter_tag'          => $tag,
				'filter_description'  => $description,
				'filter_category_id'  => $category_id,
				'filter_sub_category' => $sub_category,
				//	'filter_category_id'  		=> $filter_category_id, 
				'filter_manufacturer_id'  	=> $filter_manufacturer_id,
				'sort'                => $sort,
				'order'               => $order,
				'filter_not_archive'  => true,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
				);
				
				
				if (!empty($this->request->get['search']) && mb_strlen($this->request->get['search']) > 1){
					try {
						
						$query = $this->request->get['search'];
						$query = $this->elasticSearch->prepareQueryExceptions($query);
						$query = trim(mb_strtolower($query));	
						$this->search = $query;
						$this->filter_category_id = $filter_category_id;
						$this->filter_manufacturer_id = $filter_manufacturer_id;
						
				
						//TRY TO FIND BY SKU										
						if ($this->elasticSearch->validateResult($resultSKU = $this->elasticSearch->sku($query)) == 1){
							if ($productFoundBySKU = $this->elasticSingleProductResult($resultSKU)){
								$this->response->redirect($this->url->link('product/product', 'product_id=' . $productFoundBySKU['product_id'] . '&search=' .  urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'))));
							}
						}
												
						
						$field1 = $this->elasticSearch->buildField('name');
						$field2 = 'names';
						$suggest = $this->elasticSearch->buildField('suggest');
						
						
						$product_total = $this->elasticSearch->fuzzyProducts('products', $query, $field1, $field2, ['getTotal' => true]);	

						$data['text_product_total'] = $this->language->get('text_products_plural_pre') . ' ';
						$data['text_product_total'] .= pluralForm($product_total, $this->language->get('text_products_plural'));
						
						
						$resultsE = $this->elasticSearch->fuzzyProducts('products', $query, $field1, $field2, ['start' => (($page - 1) * $limit), 'limit' => $limit, 'filter_manufacturer_id' => $filter_manufacturer_id, 'filter_category_id' => $filter_category_id, 'sort' => $sort, 'order' => $order]);
						
						$results = $this->elasticResults($resultsE, $field1);
						
						$resultAggregations = $this->elasticSearch->fuzzyProducts('products', $query, $field1, $field2, ['count' => true]);
						$data['intersections'] = $this->prepareCategories($this->elasticSearch->validateAggregationResult($resultAggregations, 'categories'));
						
						//Поиск категорий, брендов и связок
						//Самый первый запрос, просто поиск по названию, надеюсь в большинстве случаев его достаточно
						$exact = true;
						$resultsCMA = $this->elasticSearch->fuzzyCategories('categories', $query, $field2, $field1, $suggest, ['limit' => 10]);	
						
						
						if ($_SERVER['REMOTE_ADDR'] == '185.41.249.201'){
							//	$this->log->printr($resultsCMA);
						}
						
						
						$data['top_found_cmas'] = $this->elasticResultsCMA($resultsCMA, $field1, $exact);
						
						
						//	$this->log->printr($this->data['top_found_cmas']);
						
						} catch ( Exception $e ) {
						$data['elastic_failed_error'] = 'Наш очень умный поиск поломался! Мы уже в процессе починки';
						$data['elastic_failed_error_message'] = $e->getMessage();
					};
					
					} else {
					$results = array('product_data' => []);
				}
				
				
				//	$product_total = $this->model_catalog_product->getTotalProducts($filter_data);							
				//	$results['product_data'] = $this->model_catalog_product->getProducts($filter_data);
				
				foreach ($results['product_data'] as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
						} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
					}
					
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
					$price = false;
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
					

        // oct_advanced_attributes_settings start
        $oct_attributes = array();
        $oct_advanced_attributes_settings_data = $this->config->get('oct_advanced_attributes_settings_data');

        if (isset($oct_advanced_attributes_settings_data['status']) && $oct_advanced_attributes_settings_data['status']) {
			
			$attribute_groups = $this->model_catalog_product->getProductAttributes($result['product_id']);	
		//	$ocfilters = $this->model_catalog_product->getProductOcFilterActiveValues($result['product_id'], ', ');
		//	$ocfilters = attributesOcFilterUnique($attribute_groups, $ocfilters, 'ocfilter');		
		
          foreach ($attribute_groups as $attribute_group) {
            foreach ($attribute_group['attribute'] as $attribute) {
              if (isset($oct_advanced_attributes_settings_data['allowed_attributes']) && (in_array($attribute['attribute_id'], $oct_advanced_attributes_settings_data['allowed_attributes']))) {
                $oct_attributes[] = array(
                  'name' => $attribute['name'],
                  'text' => $attribute['text']
                );
              }
            }
          }
		  
		  /*
		  foreach ($ocfilters as $ocfilter){
		  if (trim($ocfilter['value']) && trim($ocfilter['name'])){
		  $oct_attributes[] = array(
                  'name' => $ocfilter['name'],
                  'text' => $ocfilter['value']
                );
		  }
		  }
		  */
		  
		  
        }
        // oct_advanced_attributes_settings end
      

        // oct_advanced_options_settings start
        $oct_options = array();
        $oct_advanced_options_settings_data = $this->config->get('oct_advanced_options_settings_data');
        foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
          $product_option_value_data = array();
          if (isset($oct_advanced_options_settings_data['allowed_options']) && (in_array($option['option_id'], $oct_advanced_options_settings_data['allowed_options']))) {
            foreach ($option['product_option_value'] as $option_value) {
              if (!$option_value['subtract'] || ($option_value['quantity'] >= 0)) {
                if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                  $oct_option_price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                } else {
                  $oct_option_price = false;
                }
                $product_option_value_data[] = array(
                  'product_option_value_id' => $option_value['product_option_value_id'],
                  'option_value_id'         => $option_value['option_value_id'],
                  'name'                    => $option_value['name'],
                  'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
                  'price'                   => $oct_option_price,
                  'price_prefix'            => $option_value['price_prefix']
               );
              }
            }
            $oct_options[] = array(
              'product_option_id'    => $option['product_option_id'],
              'product_option_value' => $product_option_value_data,
              'option_id'            => $option['option_id'],
              'name'                 => $option['name'],
              'type'                 => $option['type'],
              'value'                => $option['value'],
              'required'             => $option['required']
            );
          }
        }
        // oct_advanced_options_settings end
      

        $oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
        $oct_product_stickers = array();

        if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
          $this->load->model('catalog/oct_product_stickers');

          if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
            $stickers = unserialize($result['oct_product_stickers']);
          } else {
            $stickers = array();
          }

          if ($stickers) {
              foreach ($stickers as $product_sticker_id) {
                $sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
                
                if ($sticker_info) {
                  $oct_product_stickers[] = array(
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
              
              array_multisort($sticker_sort_order, SORT_ASC, $oct_product_stickers);
          }
        }
      

        // oct_techstore start
        if ($result['quantity'] <= 0) {
          $stock = $result['stock_status'];
        } elseif ($this->config->get('config_stock_display')) {
          $stock = $result['quantity'];
        } else {
          $stock = $this->language->get('text_instock');
        }
        // oct_techstore end
      

				$oct_product_preorder_text = $this->config->get('oct_product_preorder_text');
				$oct_product_preorder_data = $this->config->get('oct_product_preorder_data');
				$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
				$data['text_stock'] = $this->language->get('text_stock');
			
				if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
					$product_preorder_text = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
					$product_preorder_status = 1;
				} else {
					$product_preorder_text = $oct_product_preorder_language['text_out_of_stock'];
					$product_preorder_status = 2;
				}
			
					$data['products'][] = array(

        // oct_techstore start
        'saving'      => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
        'model'       => $result['model'],
        'stock'       => $stock,
        // oct_techstore end
      

        'oct_product_stickers' => $oct_product_stickers,
      

        // oct_advanced_options_settings start
        'oct_options' => $oct_options,
        // oct_advanced_options_settings end
      

        // oct_advanced_attributes_settings start
        'oct_attributes' => $oct_attributes,
        // oct_advanced_attributes_settings end
      
					'product_id'  => $result['product_id'],
					'free_delivery'  	=> $result['free_delivery'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'ecommerceData'	   => $result['ecommerceData'],
					'description' => utf8_substr(strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'action_stickers'=> $result['action_stickers'],

				'quantity'       => $result['quantity'], 
				'product_preorder_text' => $product_preorder_text,
				'product_preorder_status' => $product_preorder_status,
			
					'price'       => $price,
					'special'     => $special,
					'sku'     	  => $result['sku'],//***
					'tax'         => $tax,
					'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'rating'      => $rating,
					'reviews'     => (int)$result['reviews'],				
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
					);
				}
				
				
				if (($total_results = ($product_total + count($this->data['intersections2']) + count($this->data['top_found_cmas']))) == 0){
					//NOTHING FOUND
					$this->data['nothing_found'] = true;
					
					} else {
					//FOUND
					$this->load->model('hobotix/search');
					$this->model_hobotix_search->writeSearchHistory($query, $total_results);
					
				}
				
				$url = '';
				
				if (isset($this->request->get['search'])) {
					$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['tag'])) {
					$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}
				
				if (isset($this->request->get['category_id'])) {
					$url .= '&category_id=' . $this->request->get['category_id'];
				}
				
				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
				}
				
				if (isset($this->request->get['filter_manufacturer_id'])) {
					$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
				}
				
				if (isset($this->request->get['sub_category'])) {
					$url .= '&sub_category=' . $this->request->get['sub_category'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$data['sorts'] = array();
				
				
				
				$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url)
				);
				
				$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url)
				);
				
				if ($this->config->get('config_review_status')) {
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/search', 'sort=rating&order=DESC' . $url)
					);
					
				}
				
				$url = '';
				
				if (isset($this->request->get['search'])) {
					$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['tag'])) {
					$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}
				
				if (isset($this->request->get['category_id'])) {
					$url .= '&category_id=' . $this->request->get['category_id'];
				}
				
				if (isset($this->request->get['sub_category'])) {
					$url .= '&sub_category=' . $this->request->get['sub_category'];
				}
				
				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
				}
				
				if (isset($this->request->get['filter_manufacturer_id'])) {
					$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				$data['limits'] = array();
				
				$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 50, 100));
				
				sort($limits);
				
				foreach($limits as $value) {
					$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/search', $url . '&limit=' . $value)
					);
				}
				
				$url = '';
				
				if (isset($this->request->get['search'])) {
					$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['tag'])) {
					$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
				}
				
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}
				
				if (isset($this->request->get['category_id'])) {
					$url .= '&category_id=' . $this->request->get['category_id'];
				}
				
				if (isset($this->request->get['sub_category'])) {
					$url .= '&sub_category=' . $this->request->get['sub_category'];
				}
				
				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
				}
				
				if (isset($this->request->get['filter_manufacturer_id'])) {
					$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->url = $this->url->link('product/search', $url . '&page={page}');
				
				$data['pagination'] = $pagination->render();
				
				$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
				
				// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
				if ($page == 1) {
					$this->document->addLink($this->url->link('product/search', '', true), 'canonical');
					} elseif ($page == 2) {
					$this->document->addLink($this->url->link('product/search', '', true), 'prev');
					} else {
					$this->document->addLink($this->url->link('product/search', $url . '&page='. ($page - 1), true), 'prev');
				}
				
				if ($limit && ceil($product_total / $limit) > $page) {
					$this->document->addLink($this->url->link('product/search', $url . '&page='. ($page + 1), true), 'next');
				}
				
				if (isset($this->request->get['search']) && $this->config->get('config_customer_search')) {
					$this->load->model('account/search');
					
					if ($this->customer->isLogged()) {
						$customer_id = $this->customer->getId();
						} else {
						$customer_id = 0;
					}
					
					if (isset($this->request->server['REMOTE_ADDR'])) {
						$ip = $this->request->server['REMOTE_ADDR'];
						} else {
						$ip = '';
					}
					
					$search_data = array(
					'keyword'       => $search,
					'category_id'   => $category_id,
					'sub_category'  => $sub_category,
					'description'   => $description,
					'products'      => $product_total,
					'customer_id'   => $customer_id,
					'ip'            => $ip
					);
					
					$this->model_account_search->addSearch($search_data);
				}
			}
			
			$data['search'] = $search;
			$data['description'] = $description;
			$data['category_id'] = $category_id;
			$data['sub_category'] = $sub_category;
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			$this->response->setOutput($this->load->view('product/search', $data));
		}
		
		
	}
