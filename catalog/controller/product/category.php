<?php
	class ControllerProductCategory extends Controller {
		public function index() {
        
        $data['oct_techstore_data'] = $this->config->get('oct_techstore_data');
        $data['oct_popup_view_data'] = $this->config->get('oct_popup_view_data');
       $data['button_popup_view'] = $this->language->get('button_popup_view');
      

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
	  
			$this->load->language('product/category');			
			$this->load->model('catalog/category');			
			$this->load->model('catalog/product');			
			$this->load->model('tool/image');
			$this->load->model('tool/mikrof');
			
			if (isset($this->request->get['filter'])) {
				$filter = $this->request->get['filter'];
				} else {
				$filter = '';
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'rating';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
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
				$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
			}
			

		// OCFilter start
    if (isset($this->request->get['filter_ocfilter'])) {
      $filter_ocfilter = $this->request->get['filter_ocfilter'];
    } else {
      $filter_ocfilter = '';
    }
		// OCFilter end
      
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
			
			if (isset($this->request->get['path'])) {
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$path = '';
				
				$parts = explode('_', (string)$this->request->get['path']);
				
				$category_id = (int)array_pop($parts);
				
				foreach ($parts as $path_id) {
					if (!$path) {
						$path = (int)$path_id;
						} else {
						$path .= '_' . (int)$path_id;
					}
					
					$category_info = $this->model_catalog_category->getCategory($path_id);
					
					if ($category_info) {
						$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
						);
					}
				}
				$data['webfun_path'] = (string)$this->request->get['path'];
				} else {
				$category_id = 0;
			}
			
			$data['category_id'] = $category_id;						
			$data['button_show_more'] = $this->language->get('button_show_more');
			$data['button_hide_more'] = $this->language->get('button_hide_more');
			$category_info = $this->model_catalog_category->getCategory($category_id);
						
			if ($category_info) {

				if (!$this->crawlerDetect->isCrawler()){
					$this->load->model('catalog/superstat');
					$this->model_catalog_superstat->addToSuperStat('c', $data['category_id']);
				}
				
				if (!empty($this->request->get['page']) && (int)$this->request->get['page'] > 1){
					$data['seo_page'] = sprintf($this->language->get('text_page'), (int)$this->request->get['page']);			
				}
				
				
				if ($category_info['meta_title']) {
					$this->document->setTitle($category_info['meta_title']);
					} else {
					$this->document->setTitle($category_info['name']);
				}
				
				$this->document->setDescription($category_info['meta_description']);
				$this->document->setKeywords($category_info['meta_keyword']);
				
				if ($category_info['meta_h1']) {
					$data['heading_title'] = $category_info['meta_h1'];
					} else {
					$data['heading_title'] = $category_info['name'];
				}
				
				$data['text_refine'] = $this->language->get('text_refine');
				$data['text_empty'] = $this->language->get('text_empty');
				$data['text_home'] = $this->language->get('text_home');
				$data['text_quantity'] = $this->language->get('text_quantity');
				$data['text_manufacturer'] = $this->language->get('text_manufacturer');
				$data['text_model'] = $this->language->get('text_model');
				$data['text_price'] = $this->language->get('text_price');
				$data['text_tax'] = $this->language->get('text_tax');
				$data['text_points'] = $this->language->get('text_points');
				$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
				$data['text_sort'] = $this->language->get('text_sort');
				$data['text_limit'] = $this->language->get('text_limit');
				$data['text_webfun_load_more_product'] = $this->language->get('text_webfun_load_more_product');
				$data['lang'] = $this->language->get('code');
				$data['button_cart'] = $this->language->get('button_cart');
				$data['button_wishlist'] = $this->language->get('button_wishlist');
				$data['button_compare'] = $this->language->get('button_compare');
				$data['button_continue'] = $this->language->get('button_continue');
				$data['button_list'] = $this->language->get('button_list');
				$data['button_grid'] = $this->language->get('button_grid');

        // oct_techstore start
        $data['text_model'] = $this->language->get('text_model');
        $data['text_stock'] = $this->language->get('text_stock');
        $this->load->language('octemplates/oct_techstore');
        $data['oct_home_text'] = $this->language->get('oct_home_text');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_sku '] = $this->language->get('text_sku ');//***
        $data['text_stock'] = $this->language->get('text_stock');
        $data['text_instock'] = $this->language->get('text_instock');
        $data['oct_choose_subcategory'] = $this->language->get('oct_choose_subcategory');
        // oct_techstore end
      
				$data['text_sku'] = $this->language->get('text_sku');
				$data['text_gift'] = $this->language->get('text_gift');
				$data['button_write_review'] = $this->language->get('button_write_review');
				
				// Set the last category breadcrumb
				$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
				);

				$this->document->setCanonical($this->url->link('product/category', 'path=' . $this->request->get['path']));
				$this->document->setRobots("index, follow");

				if ($category_info['image']) {
					$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
					$this->document->setOgImage($data['thumb']);
					} else {
					$data['thumb'] = '';
				}				
				
       $data['description'] = str_replace("<img", "<img class=\"img-responsive\"",  html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8'));
      
				$data['compare'] = $this->url->link('product/compare');
				
				$url = '';
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
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
				
				$data['categories'] = array();
				
				$results = $this->model_catalog_category->getCategories($category_id);
				
				foreach ($results as $result) {
					$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_not_archive' 	=> true,
					'filter_sub_category' => true
					);
					

        // oct_techstore start
        if (isset($result['image']) && $result['image'] !="") {
          $cat_image = $this->model_tool_image->resize($result['image'], 100, 100);
        } else {
          $cat_image = $this->model_tool_image->resize("no-image.png", 100, 100);
        }
        // oct_techstore end
      
					$data['categories'][] = array(

        // oct_techstore start
        'thumb' => $cat_image,
        // oct_techstore end
      
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
					);
				}
				
				$data['products'] = array();
				
				$filter_exclude_categories = false;
				if (empty($this->request->get['filter_ocfilter'])){
					$filter_exclude_categories = $this->model_catalog_category->getCategoryExcluded($category_id);
				}
			
				
				$filter_data = array(
				'filter_category_id' 	=> $category_id,
				'exclude_category_id' => 228,
				'filter_sub_category' => true,
				'filter_filter'      	=> $filter,
				'filter_exclude_categories' => $filter_exclude_categories,
				'filter_not_archive' => true,
				'filter_ocfilter' 	 => $filter_ocfilter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
				);

                // Додаємо прапорець, якщо підкатегорії є
                $subcategories = $this->model_catalog_category->getCategories($category_id);
                if (!empty($subcategories)) {
                    $data['has_subcategories'] = true;
                }

				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);				
				$results = $this->model_catalog_product->getProducts($filter_data);

				$data['text_product_total'] = $this->language->get('text_products_plural_pre') . ' ';
				$data['text_product_total'] .= pluralForm($product_total, $this->language->get('text_products_plural'));

				//getBestSellers For current 
				if (!empty($this->request->get['filter_ocfilter']) && count($results) > 10 && $page == 1){
					$tmp_results = $results;

					foreach ($tmp_results as $key => &$tmp_result){
						if ($tmp_result['quantity'] == 0){
							unset($tmp_results[$key]);
						}
					}

					if (count($tmp_results) >= 10){

						$col = array_column( $tmp_results, "orders_90" );
						array_multisort( $col, SORT_DESC, $tmp_results );
						$slice = array_slice($tmp_results, 0, 3, true);

						$bestsellers = [];
						foreach ($slice as $element){
							if ($element['quantity'] > 0){
								$bestsellers[] = $element['product_id'];
							}
						}
						
						foreach ($results as &$result){	
							if (in_array($result['product_id'], $bestsellers)){
								$result = $this->model_catalog_product->addProductBestSellerSticker($result);
							}
						}
					}
				}
				
				foreach ($results as $result) {
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
		if (!empty($oct_advanced_options_settings_data['allowed_options'])){
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
					'sku'  		  => $result['sku'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'ecommerceData'	   => $result['ecommerceData'],
					'description' => utf8_substr(strip_tags(html_entity_decode($this->model_tool_mikrof->formatStringdisplay($result['description']), ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',

				'quantity'       => $result['quantity'], 
				'product_preorder_text' => $product_preorder_text,
				'product_preorder_status' => $product_preorder_status,
			
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'rating'      => $rating,
                    'sort_order'  => $result['sort_order'],		
					'reviews'     => (int)$result['reviews'],
					'action_stickers' 	=> $result['action_stickers'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
					);
				}
				
				
				
				$url = '';
				

      // OCFilter start
			if (isset($this->request->get['filter_ocfilter'])) {
				$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
			}
      // OCFilter end
      
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$data['sorts'] = array();
				
				/* $data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
				 	'value' => 'p.sort_order-ASC',
				 	'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
					);
					
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
					);
					
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
					);
				*/
				
				$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
				);
				
				$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
				);
				
				if ($this->config->get('config_review_status')) {
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
					);
					/*
						$data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_asc'),
						'value' => 'rating-ASC',
						'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
						);
					*/
				}
				/*
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_model_asc'),
					'value' => 'p.model-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
					);
					
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_model_desc'),
					'value' => 'p.model-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
					);
				*/
				
				$url = '';
				

      // OCFilter start
			if (isset($this->request->get['filter_ocfilter'])) {
				$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
			}
      // OCFilter end
      
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
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
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
					);
				}
				
				$url = '';
				

      // OCFilter start
			if (isset($this->request->get['filter_ocfilter'])) {
				$url .= '&filter_ocfilter=' . $this->request->get['filter_ocfilter'];
			}
      // OCFilter end
      
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
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
				$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
				
				$data['pagination'] = $pagination->render();
				if ($page * $limit < $product_total && !empty($data['pagination'])) {
					$data['webfun_view_load_more'] = true;
				}
				
				$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
								
				//big page fix
				if ($product_total > 0 && ceil($product_total / $limit) < $page){
					$this->response->redirect($this->url->link('product/category', 'path=' . $category_info['category_id']));
				}
				
				if ($page > 1){
					$this->document->setCanonical($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page)));
					$this->document->setRobots("noindex, follow");
				}
				
				if (!empty($this->request->get['filter_ocfilter'])){
					if ($page > 1){
						$this->document->setCanonical($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&filter_ocfilter=' . $this->request->get['filter_ocfilter'] . '&page=' . ($page)));
						$this->document->setRobots("noindex, follow");
						} else {
						$this->document->setCanonical($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&filter_ocfilter=' . $this->request->get['filter_ocfilter']));
						$this->document->setRobots("index, follow");
					}
				}								
				
				$data['sort'] = $sort;
				$data['order'] = $order;
				$data['limit'] = $limit;

				if (!empty($this->request->get['sort']) || !empty($this->request->get['order']) || !empty($this->request->get['limit'])){
						$this->document->setRobots("noindex, nofollow");
				}
								
				if (isset($this->request->get['filter_ocfilter'])) {
					if (!$product_total) {
						$this->response->redirect($this->url->link('product/category', 'path=' . $this->request->get['path']));
					}
					
					$data['description'] = '';
				}							
				
				$ocfilter_page_info = $this->load->controller('extension/module/ocfilter/getPageInfo');
				
				if ($ocfilter_page_info) {
					$this->document->setTitle($ocfilter_page_info['meta_title']);
					
					if ($ocfilter_page_info['meta_description']) {
						$this->document->setDescription($ocfilter_page_info['meta_description']);
					}
					
					if ($ocfilter_page_info['meta_keyword']) {
						$this->document->setKeywords($ocfilter_page_info['meta_keyword']);
					}
					
					$data['heading_title'] = $ocfilter_page_info['title'];
					
					if ($ocfilter_page_info['description']) {
						$data['description'] = html_entity_decode($ocfilter_page_info['description'], ENT_QUOTES, 'UTF-8');
						} else {
						$data['description'] = '';
					}
					
					$data['breadcrumbs'][] = array(
					'text' => $ocfilter_page_info['title'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
					);
					} else {
					$meta_title = $this->document->getTitle();
					$meta_description = $this->document->getDescription();
					$meta_keyword = $this->document->getKeywords();
					
					$filter_title = $this->load->controller('extension/module/ocfilter/getSelectedsFilterTitle');
					
					if ($filter_title) {
						if (false !== strpos($meta_title, '{filter}')) {
							$meta_title = trim(str_replace('{filter}', $filter_title, $meta_title));
							} else {
							if (false !== strpos($meta_title, $category_info['name'])){
								$meta_title = str_replace($category_info['name'], $category_info['name'] . ' ' . $filter_title, $meta_title);
								} else {
								$meta_title .= ' ' . $filter_title;
							}
						}
						
						$this->document->setTitle($meta_title);
						
						if ($meta_description) {
							if (false !== strpos($meta_description, '{filter}')) {
								$meta_description = trim(str_replace('{filter}', $filter_title, $meta_description));
								} else {
								if (false !== strpos($meta_description, $category_info['name'])){
									$meta_description = str_replace($category_info['name'], $category_info['name'] . ' ' . $filter_title, $meta_description);									
									} else {
									$meta_description .= ' ' . $filter_title;
								}
							}
							
							$this->document->setDescription($meta_description);
						}
						
						if ($meta_keyword) {
							if (false !== strpos($meta_keyword, '{filter}')) {
								$meta_keyword = trim(str_replace('{filter}', $filter_title, $meta_keyword));
								} else {
								if (false !== strpos($meta_keyword, $category_info['name'])){
									$meta_keyword = str_replace($category_info['name'], $category_info['name'] . ' ' . $filter_title, $meta_keyword);									
									} else {
									$meta_keyword .= ' ' . $filter_title;
								}
							}
							
							$this->document->setKeywords($meta_keyword);
						}
						
						$heading_title = $data['heading_title'];
						
						if (false !== strpos($heading_title, '{filter}')) {
							$heading_title = trim(str_replace('{filter}', $filter_title, $heading_title));
							} else {
							if (false !== strpos($heading_title, $category_info['name'])){
								$heading_title = str_replace($category_info['name'], $category_info['name'] . ' ' . $filter_title, $heading_title);									
								} else {
								$heading_title .= ' ' . $filter_title;
							}
						}
						
						$data['heading_title'] = $heading_title;
						
						$data['breadcrumbs'][] = array(
						'text' => (utf8_strlen($heading_title) > 30 ? utf8_substr($heading_title, 0, 30) . '..' : $heading_title),
						'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
						);
						} else {
						$this->document->setTitle(trim(str_replace('{filter}', '', $meta_title)));
						$this->document->setDescription(trim(str_replace('{filter}', '', $meta_description)));
						$this->document->setKeywords(trim(str_replace('{filter}', '', $meta_keyword)));
						
						$data['heading_title'] = trim(str_replace('{filter}', '', $data['heading_title']));
					}
				}
				// OCFilter End
				
				if ($page > 1){
					$data['description'] = '';
				}
				
				

				$data['hobofaq'] = $this->load->controller('hobotix/hobofaq');
				$data['description'] .= '<div class="row"><div class="col-xs-12">' . $data['hobofaq'] . '</div></div>';
			
				$data['continue'] = $this->url->link('common/home');
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				
				$this->response->setOutput($this->load->view('product/category', $data));
				} else {
				$url = '';
				
				if (isset($this->request->get['path'])) {
					$url .= '&path=' . $this->request->get['path'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
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
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
				);
				
				$this->document->setTitle($this->language->get('text_error'));
				
				$data['heading_title'] = $this->language->get('text_error');
				
				$data['text_error'] = $this->language->get('text_error');
				
				$data['button_continue'] = $this->language->get('button_continue');
				

				$data['hobofaq'] = $this->load->controller('hobotix/hobofaq');
				$data['description'] .= '<div class="row"><div class="col-xs-12">' . $data['hobofaq'] . '</div></div>';
			
				$data['continue'] = $this->url->link('common/home');
				
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				
				$this->response->setOutput($this->load->view('error/not_found', $data));
			}
		}
	}
