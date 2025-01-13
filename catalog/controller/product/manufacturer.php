<?php
	class ControllerProductManufacturer extends Controller {
		public function index() {

        $oct_data = $this->config->get('oct_techstore_data');
        if (isset($oct_data['showmanlogos']) && $oct_data['showmanlogos'] == "on") {
          $data['show_logo'] = 1;
        } else {
          $data['show_logo'] = 0;
        }
      

        $data['oct_popup_view_data'] = $this->config->get('oct_popup_view_data');
        $data['button_popup_view'] = $this->language->get('button_popup_view');
      
			$this->load->language('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/product');
			
			$this->load->model('tool/image');
			
			$this->document->setTitle($this->language->get('heading_title'));
			$this->document->setDescription(sprintf($this->language->get('meta_description'), $this->config->get('config_telephone')));
			//TODO Galaxy-IT JAKIM START before
			$this->document->setCanonical($this->url->link('product/manufacturer'));
			//TODO Galaxy-IT JAKIM END before
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_index'] = $this->language->get('text_index');
			$data['text_empty'] = $this->language->get('text_empty');
			
			$data['button_continue'] = $this->language->get('button_continue');
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('product/manufacturer')
			);
			
			$data['categories'] = array();
			
			$results = $this->model_catalog_manufacturer->getManufacturers();
			
			foreach ($results as $result) {
				
				$show_brand = !$result['hide_manufacturer'];
				$total = 0;
				
				if ($show_brand){
					$filter_data = array(
					'filter_manufacturer_id' 		=> $result['manufacturer_id'],
					'filter_hide_manufacturer' 	=> $result['hide_manufacturer'],
					'filter_not_archive' 	 			=> true,
					'filter_status'		 	 				=> true,	
					);
					
					$total = $this->model_catalog_product->getTotalProducts($filter_data);	
				}
				
				if ($show_brand && $total){
					
					$name = $result['name'];
					
					if (is_numeric(utf8_substr($name, 0, 1))) {
						$key = '0 - 9';
						} else {
						$key = utf8_substr(utf8_strtoupper($name), 0, 1);
					}
					
					if (!isset($data['categories'][$key])) {
						$data['categories'][$key]['name'] = $key;
					}
					
					$data['categories'][$key]['manufacturer'][] = array(
					'name' => $name,
					'total' => $total,

        'image' => $this->model_tool_image->resize($result['image'], 150, 150),
      
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
					);
				}
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
			
			$this->response->setOutput($this->load->view('product/manufacturer_list', $data));
		}
		
		public function info() {

	    $this->load->language('octemplates/oct_techstore');
        $data['oct_home_text'] = $this->language->get('oct_home_text');
           
			$this->load->language('product/manufacturer');
			
			$this->load->model('catalog/manufacturer');
			
			$this->load->model('catalog/product');
			
			$this->load->model('tool/image');
			

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
	  
			if (isset($this->request->get['manufacturer_id'])) {
				$manufacturer_id = (int)$this->request->get['manufacturer_id'];
				} else {
				$manufacturer_id = 0;
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
				$limit = (int)$this->config->get($this->config->get('config_theme') . '_product_limit');
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('product/manufacturer')
			);
			
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

        $data['oct_popup_view_data'] = $this->config->get('oct_popup_view_data');
        $data['button_popup_view'] = $this->language->get('button_popup_view');
      
			$filter_data = array(
			'filter_manufacturer_id' => $manufacturer_id,
			'filter_not_archive' 	 => true,
			'filter_status'		 	 => true,
			);
			
			if (!empty($manufacturer_info['hide_manufacturer'])){
				$filter_data['filter_hide_manufacturer'] = true;
			}
			
			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$data['text_product_total'] = $this->language->get('text_products_plural_pre') . ' ';
			$data['text_product_total'] .= pluralForm($product_total, $this->language->get('text_products_plural'));
			
			if ($manufacturer_info && $product_total) {

				if (!$this->crawlerDetect->isCrawler()){
					$this->load->model('catalog/superstat');
					$this->model_catalog_superstat->addToSuperStat('m', $manufacturer_id);
				}
				
				if (!empty($this->request->get['page']) && (int)$this->request->get['page'] > 1){
					$data['seo_page'] = sprintf($this->language->get('text_page'), (int)$this->request->get['page']);			
				}
				
				$this->document->setTitle($manufacturer_info['name']);
				//TODO Galaxy-IT JAKIM START before
				$this->document->setCanonical($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']));
				//TODO Galaxy-IT JAKIM END before
				$url = '';
				
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
				'text' => $manufacturer_info['name'],
				'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
				
				if ($manufacturer_info['meta_title']) {
					$this->document->setTitle($manufacturer_info['meta_title']);
					} else {
					$this->document->setTitle($manufacturer_info['name']);
				}
				
				$this->document->setDescription($manufacturer_info['meta_description']);
				$this->document->setKeywords($manufacturer_info['meta_keyword']);
				
				if ($manufacturer_info['meta_h1']) {
					$data['heading_title'] = $manufacturer_info['meta_h1'];
					} else {
					$data['heading_title'] = $manufacturer_info['name'];
				}
				
				if ($manufacturer_info['image']) {
					$data['thumb'] = $this->model_tool_image->resize($manufacturer_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
					$this->document->setOgImage($data['thumb']);
					} else {
					$data['thumb'] = '';
				}
				
				$data['description'] = html_entity_decode($manufacturer_info['description'], ENT_QUOTES, 'UTF-8');
				
				$data['text_empty'] = $this->language->get('text_empty');
				$data['text_quantity'] = $this->language->get('text_quantity');
				$data['text_manufacturer'] = $this->language->get('text_manufacturer');
				$data['text_model'] = $this->language->get('text_model');
				$data['text_price'] = $this->language->get('text_price');
				$data['text_tax'] = $this->language->get('text_tax');
				$data['text_points'] = $this->language->get('text_points');
				$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
				$data['text_sort'] = $this->language->get('text_sort');
				$data['text_limit'] = $this->language->get('text_limit');
				
				$data['button_cart'] = $this->language->get('button_cart');
				$data['button_wishlist'] = $this->language->get('button_wishlist');
				$data['button_compare'] = $this->language->get('button_compare');
				$data['button_continue'] = $this->language->get('button_continue');
				$data['button_list'] = $this->language->get('button_list');
				$data['button_grid'] = $this->language->get('button_grid');
				$data['text_gift'] = $this->language->get('text_gift');
				$data['compare'] = $this->url->link('product/compare');
				$data['button_write_review'] = $this->language->get('button_write_review');
				
				$data['products'] = array();
				
				$filter_data = array(
				'filter_manufacturer_id' => $manufacturer_id,
				'sort'                   => $sort,
				'order'                  => $order,
				'filter_not_archive' 	 => true,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
				);
				
				if (!empty($manufacturer_info['hide_manufacturer'])){
					$filter_data['filter_hide_manufacturer'] = true;
				}
				
				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				
				$results = $this->model_catalog_product->getProducts($filter_data);
				
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
      
					'product_id'  		=> $result['product_id'],
					'free_delivery'  	=> $result['free_delivery'],
					'thumb'       		=> $image,				
					'name'        		=> $result['name'],
					'ecommerceData'	   	=> $result['ecommerceData'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',

				'quantity'       => $result['quantity'], 
				'product_preorder_text' => $product_preorder_text,
				'product_preorder_status' => $product_preorder_status,
			
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
					'rating'      => $rating,
					'reviews'     => (int)$result['reviews'],			
					'action_stickers' 	=> $result['action_stickers'],
					'model'		 => $result['sku'],
					'href'        => $this->url->link('product/product', 'manufacturer_id=' . $result['manufacturer_id'] . '&product_id=' . $result['product_id'] . $url)
					);
				}
				
				$url = '';
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$data['sorts'] = array();
				/*
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
					'value' => 'p.sort_order-ASC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.sort_order&order=ASC' . $url)
					);
					
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=pd.name&order=ASC' . $url)
					);
					
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=pd.name&order=DESC' . $url)
					);
				*/
				$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=ASC' . $url)
				);
				
				$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.price&order=DESC' . $url)
				);
				
				if ($this->config->get('config_review_status')) {
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=DESC' . $url)
					);
					/*
						$data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_asc'),
						'value' => 'rating-ASC',
						'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=rating&order=ASC' . $url)
						);
					*/
				}
				/*
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_model_asc'),
					'value' => 'p.model-ASC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.model&order=ASC' . $url)
					);
					
					$data['sorts'][] = array(
					'text'  => $this->language->get('text_model_desc'),
					'value' => 'p.model-DESC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&sort=p.model&order=DESC' . $url)
					);
				*/
				
				$url = '';
				
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
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&limit=' . $value)
					);
				}
				
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
				
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->url = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] .  $url . '&page={page}');
				
				$data['pagination'] = $pagination->render();
				
				$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
				
				// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
				if ($page == 1) {
					$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'], true), 'canonical');
					} elseif ($page == 2) {
					$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'], true), 'prev');
					} else {
					$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&page='. ($page - 1), true), 'prev');
				}
				
				//big page fix
				if ($product_total > 0 && ceil($product_total / $limit) < $page){
					$this->response->redirect($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id']));
				}
				
				if ($page > 1){
					$this->document->setCanonical($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . '&page=' . ($page)));
					$this->document->setRobots("index, follow");
				}
				
				if ($limit && ceil($product_total / $limit) > $page) {
					$this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url . '&page='. ($page + 1), true), 'next');
				}
				
				$data['sort'] = $sort;
				$data['order'] = $order;
				$data['limit'] = $limit;
				

				$data['hobofaq'] = $this->load->controller('hobotix/hobofaq');
				$data['description'] .= '<div class="row"><div class="col-xs-12">' . $data['hobofaq'] . '</div></div>';
			
				$data['continue'] = $this->url->link('common/home');
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				
				$this->response->setOutput($this->load->view('product/manufacturer_info', $data));
				} else {
				
				//$this->response->redirect($this->url->link('product/manufacturer'), 301);
				
				$url = '';
				

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
	  
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
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
				'href' => $this->url->link('product/manufacturer/info', $url)
				);
				
				$this->document->setTitle($this->language->get('text_error'));
				
				$data['heading_title'] = $this->language->get('text_error');
				
				$data['text_error'] = $this->language->get('text_error');
				
				$data['button_continue'] = $this->language->get('button_continue');
				

				$data['hobofaq'] = $this->load->controller('hobotix/hobofaq');
				$data['description'] .= '<div class="row"><div class="col-xs-12">' . $data['hobofaq'] . '</div></div>';
			
				$data['continue'] = $this->url->link('common/home');
				
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
				
				$data['header'] = $this->load->controller('common/header');
				$data['footer'] = $this->load->controller('common/footer');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				
				$this->response->setOutput($this->load->view('error/not_found', $data));
			}
		}
	}
