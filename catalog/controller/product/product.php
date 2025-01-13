<?php
	class ControllerProductProduct extends Controller {
		private $error = [];
		
		private function isRealNumeric($string){
			$string = trim(str_ireplace(' ', '', $string));
			$string = trim(str_ireplace('.', '', $string));
			$string = trim(str_ireplace('/', '', $string));
			$string = trim(str_ireplace('\\', '', $string));
			$string = trim(str_ireplace('-', '', $string));
			
			return is_numeric($string);
		}
		
		public function getEcommerceInfo(){
			$json = [];
			
			if (isset($this->request->get['product_id'])) {
                $product_id = (int)$this->request->get['product_id'];
				} else {
                $product_id = 0;
			}
			
			$this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($product_id);
			
			

        // oct_advanced_options_settings start
        $data['text_col_option_name'] = $this->language->get('text_col_option_name');
        $data['text_col_option_image'] = $this->language->get('text_col_option_image');
        $data['text_col_option_sku'] = $this->language->get('text_col_option_sku');
        $data['text_col_option_model'] = $this->language->get('text_col_option_model');
        $data['text_col_option_price'] = $this->language->get('text_col_option_price');
        $data['text_col_option_quantity'] = $this->language->get('text_col_option_quantity');
        // oct_advanced_options_settings end
      
			if ($product_info) {											
				$json['currency'] 	= $this->session->data['currency'];
				$json['id'] 		= $product_info['ecommerceData']['id'];
				$json['product_id'] = $product_info['ecommerceData']['id'];
				$json['name'] 		= $product_info['ecommerceData']['name'];				
				$json['price'] 		= $product_info['ecommerceData']['price'];
				$json['gtin'] 		= $product_info['ecommerceData']['ean'];
				$json['brand'] 		= $product_info['ecommerceData']['brand'];				
				$json['category']   = $product_info['ecommerceData']['category'];
				
				$json = array_map('prepareEcommString', $json);
				
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		
		public function catchAlsoViewed($product_id)
    {
     unset($this->session->data['alsoviewed']);
     unset($this->session->data['avd']);

     if (empty($this->session->data['avwd'])) {
      $this->session->data['avwd'] = $product_id;
    } else {
      if (strstr($this->session->data['avwd'], $product_id) === false) {
        $this->session->data['avwd'] .= ',' . $product_id;
      }
    }		

    $alsoViewed = explode(',', $this->session->data['avwd']);
    sort($alsoViewed);

    $groupedalsoViewed = [];
    foreach ($alsoViewed as $k => $b) {
      for ($i = 1; $i < count($alsoViewed); $i++) {
        if (!empty($alsoViewed[$k + $i])) {
          $groupedalsoViewed[] = array('low' => $b, 'high' => $alsoViewed[$k + $i]);
        }
      }
    }

    $groupedalsoViewed = array_slice($groupedalsoViewed, -4);

    foreach ($groupedalsoViewed as $p) {
      if ((int)$p['low'] && (int)$p['high']) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "alsoviewed` (low, high, number, date_added) VALUES ('" . (int)$p['low'] . "', '" . (int)$p['high'] . "', '1', NOW()) ON DUPLICATE KEY UPDATE number = number+1");
      }
    }
  }				


  public function update_prices() {
    if (isset($this->request->request['product_id']) && isset($this->request->request['quantity'])) {
      $this->load->model('catalog/product');
      $option_price    = 0;
      $product_id      = (int)$this->request->request['product_id'];
      $quantity        = (int)$this->request->request['quantity'];
      $product_info    = $this->model_catalog_product->getProduct($product_id);
      $product_options = $this->model_catalog_product->getProductOptions($product_id);
      if (!empty($this->request->request['option'])) {
        $options = $this->request->request['option'];
      } else {
        $options = [];
      }
      $options_arr = [];
      if ($options) {
        foreach ($options as $key => $option) {
          if (is_array($option)) {
            foreach ($option as $option_value) {
              $product_option_value_id_data = explode("|", $option_value);
              if (isset($product_option_value_id_data[0]) && isset($product_option_value_id_data[1])) {
                $options_arr[$key][$product_option_value_id_data[0]] = array(
                  'product_option_value_id' => $product_option_value_id_data[0],
                  'quantity' => $product_option_value_id_data[1]
                );
              }
            }
          }
        }
      }
      foreach ($product_options as $product_option) {
        if (is_array($product_option['product_option_value'])) {
          foreach ($product_option['product_option_value'] as $option_value) {
            if ($product_option['type'] == 'oct_quantity') {
              if (isset($options_arr[$product_option['product_option_id']][$option_value['product_option_value_id']])) {
                if (($options_arr[$product_option['product_option_id']][$option_value['product_option_value_id']]['product_option_value_id'] == $option_value['product_option_value_id'])) {
                  $oct_quantity = (isset($options_arr[$product_option['product_option_id']][$option_value['product_option_value_id']]['quantity'])) ? $options_arr[$product_option['product_option_id']][$option_value['product_option_value_id']]['quantity'] : 1;
                  if ($option_value['price_prefix'] == '+') {
                    $option_price += $option_value['price'] * $oct_quantity;
                  } elseif ($option_value['price_prefix'] == '-') {
                    $option_price -= $option_value['price'] * $oct_quantity;
                  }
                }
              }
            } else {
              if (isset($options[$product_option['product_option_id']])) {
                if (($options[$product_option['product_option_id']] == $option_value['product_option_value_id']) || ((is_array($options[$product_option['product_option_id']])) && (in_array($option_value['product_option_value_id'], $options[$product_option['product_option_id']])))) {
                  if ($option_value['price_prefix'] == '+') {
                    $option_price += $option_value['price'];
                  } elseif ($option_value['price_prefix'] == '-') {
                    $option_price -= $option_value['price'];
                  }
                }
              }
            }
          }
        }
      }
      $json = [];
      $json['special'] = $this->currency->format(($this->tax->calculate($this->get_price_discount($product_id, $quantity, 'special'), $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) + $this->tax->calculate($option_price * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
      if ($json['special']) {
        $economy = round(((($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) - ($this->tax->calculate($this->get_price_discount($product_id, $quantity, 'special'), $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity))/(($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) + 0.01))*100, 0);
        $saver = round(($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))) - ($this->tax->calculate($this->get_price_discount($product_id, $quantity, 'special'), $product_info['tax_class_id'], $this->config->get('config_tax'))));
              //$json['you_save'] = $this->currency->format(($this->tax->calculate($saver, $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) + $this->tax->calculate($quantity * $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) . ' (-'.$economy.'%)';
        $json['you_save'] = '-'.$economy.'%';
      } else {
        $json['you_save'] = false;
      }
      $json['price'] = $this->currency->format(($this->tax->calculate($this->get_price_discount($product_id, $quantity, 'price'), $product_info['tax_class_id'], $this->config->get('config_tax')) * $quantity) + $this->tax->calculate($option_price * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
      $json['tax'] = $this->currency->format(($this->get_price_discount($product_id, $quantity, 'price') + $option_price) * $quantity, $this->session->data['currency']);
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  
  public function get_price_discount($product_id, $quantity, $type) {
    $this->load->model('catalog/product');
    $customer_group_id = ($this->customer->isLogged()) ? (int)$this->customer->getGroupId() : (int)$this->config->get('config_customer_group_id');
    $product_info = (array)$this->model_catalog_product->getProduct($product_id);
    if ($type == 'price') {
      $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
      if ($product_discount_query->row) {
        $price = $product_discount_query->row['price'];
      } else {
        $price = $product_info['price'];
      }
    }
    if ($type == 'special') {
      $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
      if ($product_special_query->row) {
        $price = $product_special_query->row['price'];
      } else {
        $price = $product_info['price'];
      }
    }
    return $price;
  }
     
  public function getPImages() {
    $json = [];
    $this->load->model('catalog/product');
    $this->load->model('tool/image');
    $this->load->language('product/product');

    if (isset($this->request->get['product_id'])) {
      $product_id = $this->request->get['product_id'];
    } else {
      $product_id = 0;
    }
    $oct_advanced_options_settings_data = $this->config->get('oct_advanced_options_settings_data');
    $check_zoom = isset($oct_advanced_options_settings_data['allow_zoom']) ? $oct_advanced_options_settings_data['allow_zoom']: '0';
    $product_info = $this->model_catalog_product->getProduct($product_id);

    if (isset($this->request->get['image_width'])) {
     $popup_width = (int)$this->request->get['image_width'];
   } else {
     $popup_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
   }

   if (isset($this->request->get['image_height'])) {
     $popup_height = (int)$this->request->get['image_height'];
   } else {
     $popup_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
   }

   if (isset($this->request->get['image_additional_width'])) {
     $thumb_width = (int)$this->request->get['image_additional_width'];
   } else {
     $thumb_width = $this->config->get($this->config->get('config_theme') . '_image_additional_width');
   }

   if (isset($this->request->get['image_additional_height'])) {
     $thumb_height = (int)$this->request->get['image_additional_height'];
   } else {
     $thumb_height = $this->config->get($this->config->get('config_theme') . '_image_additional_height');
   }

   $main_img_width = $this->config->get($this->config->get('config_theme') . '_image_thumb_width');
   $main_img_height = $this->config->get($this->config->get('config_theme') . '_image_thumb_height');

   $main_popup_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
   $main_popup_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
		  /*
            if (isset($this->request->post['option'])) {
              $opt_array = [];
              foreach ($this->request->post['option'] as $value) {
                if (is_array($value)) {
                  foreach ($value as $val) {
                    if ($val) {
                      $opt_array[] = $this->model_catalog_product->getProductOptionValueId($this->request->get['product_id'], $val);
                    }
                  }
                } else {
                  if ($value) {
                    $opt_array[] = $this->model_catalog_product->getProductOptionValueId($this->request->get['product_id'], $value);
                  }
                }
              }

             $results = $this->model_catalog_product->getProductImagesByOptionValueId($this->request->get['product_id'], $opt_array);
			 //$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

              foreach ($results as $result) {
                $json['images'][] = array(
				  'video_in_product' => $result['video_in_product'],
			'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
			'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
                  'popup' => $this->model_tool_image->resize($result['image'], $popup_width, $popup_height),
                  'thumb' => $this->model_tool_image->resize($result['image'], $thumb_width, $thumb_height),
                  'main_img'   => $this->model_tool_image->resize($result['image'], $main_img_width, $main_img_height),
                  'main_popup' => $this->model_tool_image->resize($result['image'], $main_popup_width, $main_popup_height)
                );
              }
            } else {
              $results = false;
            }
			
			//*** ОТКЛЮЧЕНИЕ ПОДМЕНЫ КАРТИНОК ОПЦИЙ
			$results = false;
			$json['images'] = [];
		  */
			
			//***mf begin
			if (!$results && isset($this->request->get['option_id'])) {
			//$query_img = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$this->request->get['product_id'] . "' AND product_option_value_id = '" . (int)$this->request->get['option_id'] . "'");
       $query_img = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov
        LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) 
        LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 
        WHERE pov.product_id = '" . (int)$this->request->get['product_id'] . "' AND pov.product_option_value_id = '" . (int)$this->request->get['option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order LIMIT 1");
       
       if ($query_img->row) {
        $json['images'][] = array(
          'video_in_product' => $result['video_in_product'],
          'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
          'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
          'popup' => $this->model_tool_image->resize($query_img->row['o_v_image'], $popup_width, $popup_height),
          'thumb' => $this->model_tool_image->resize($query_img->row['o_v_image'], $thumb_width, $thumb_height),
          'main_img'   => $this->model_tool_image->resize($query_img->row['o_v_image'], $main_img_width, $main_img_height),
          'main_popup' => $this->model_tool_image->resize($query_img->row['o_v_image'], $main_popup_width, $main_popup_height)
        );
        $results = false;		
        
        if ($product_info['meta_h1']) {
         $heading_title = $product_info['meta_h1'];
       } else {
         $heading_title = $product_info['name'];
       }

       if ($query_img->row['quantity'] <= 0) {
        $data['stock'] = $product_info['stock_status'];
      } elseif ($this->config->get('config_stock_display')) {
        $data['stock'] = $query_img->row['quantity'];
      } else {
        $data['stock'] = $this->language->get('text_instock');
      } 

      $json['stock_data'] = array(
        'quantity' => $query_img->row['quantity'],
        'stock'    => $data['stock']
      );
      
      $json['text_data'] = array(
       'name' => $heading_title . ' ' . mb_strtolower($query_img->row['name'])
     );
    }			
  }
			//***mf end				

  if (!$results) {
    $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
    $json['images'][] = array(
     'video_in_product' => $result['video_in_product'],
     'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
     'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
     'popup' => $this->model_tool_image->resize($product_info['image'], $popup_width, $popup_height),
     'thumb' => $this->model_tool_image->resize($product_info['image'], $thumb_width, $thumb_height),
     'main_img'   => $this->model_tool_image->resize($product_info['image'], $main_img_width, $main_img_height),
     'main_popup' => $this->model_tool_image->resize($product_info['image'], $main_popup_width, $main_popup_height)
   );

    foreach ($results as $result) {
      $json['images'][] = array(
        'video_in_product' => $result['video_in_product'],
        'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
        'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
        'popup' => $this->model_tool_image->resize($result['image'], $popup_width, $popup_height),
        'thumb' => $this->model_tool_image->resize($result['image'], $thumb_width, $thumb_height),
        'main_img'   => $this->model_tool_image->resize($result['image'], $main_img_width, $main_img_height),
        'main_popup' => $this->model_tool_image->resize($result['image'], $main_popup_width, $main_popup_height)
      );
    }
  }
  $this->response->addHeader('Content-Type: application/json');
  $this->response->setOutput(json_encode($json));
}       
     
		public function index() {

        // oct_techstore start
		$data['text_home'] = $this->language->get('text_home');
        $data['oct_techstore_data'] = $oct_data = $this->config->get('oct_techstore_data');
        $this->load->language('octemplates/oct_techstore');
        $data['oct_home_text'] = $this->language->get('oct_home_text');
        $data['oct_text_review'] = $this->language->get('oct_text_review');
        $data['tech_pr_micro'] = $oct_data['pr_micro'];
        $data['oct_tech_currency_code_data'] = $this->session->data['currency'];
        $data['text_sku'] = $this->language->get('text_sku');
        $data['text_counter'] = $this->language->get('text_counter');
        $data['text_raiting'] = $this->language->get('text_raiting');
        $data['text_oct_option_disable'] = $this->language->get('oct_option_disable');
        $data['language_code'] = $this->session->data['language'];
        $data['oct_popup_view_data'] = $this->config->get('oct_popup_view_data');
        $data['button_popup_view'] = $this->language->get('button_popup_view');
        $data['oct_popup_purchase_data'] = $this->config->get('oct_popup_purchase_data');
        $data['oct_popup_found_cheaper_data'] = $this->config->get('oct_popup_found_cheaper_data');
      

        $oct_advanced_options_settings_data = $this->config->get('oct_advanced_options_settings_data');
        if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status'] && $oct_advanced_options_settings_data['allow_zoom']) {
          $this->document->addStyle('catalog/view/theme/oct_techstore/js/cloud-zoom/cloud-zoom.css');
          $this->document->addScript('catalog/view/theme/oct_techstore/js/cloud-zoom/cloud-zoom.1.0.2.js');
        }

			$oct_data = $this->config->get('oct_techstore_data');
			
			$this->document->addStyle('catalog/view/theme/oct_techstore/js/toast/jquery.toast.css');
            $this->document->addScript('catalog/view/theme/oct_techstore/js/toast/jquery.toast.js');
			
			$this->load->language('extension/module/oct_product_reviews');			
			$data['oct_product_reviews_data'] = $this->config->get('oct_product_reviews_data');

			if (!empty($this->request->get['utm_medium']) && $this->request->get['utm_medium'] == 'zapit-pro-vidguk'){
				$data['this_is_review_mail'] = true;				
			}
			
			$data['entry_positive_text'] = $this->language->get('entry_positive_text');
			$data['entry_negative_text'] = $this->language->get('entry_negative_text');
			$data['text_where_bought'] = $this->language->get('text_where_bought');
			$data['text_where_bought_yes'] = $this->language->get('text_where_bought_yes');
			$data['text_where_bought_no'] = $this->language->get('text_where_bought_no');
			
			$this->load->language('product/product');
			
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
	  
			$data['breadcrumbs'] = [];
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
			

		if(isset($this->request->post['email_mf'])){
			$email_subject = "Повідомте, коли з'явиться";
			$email_text = "E-mail: " .$this->request->post['email_mf']. "\n\n";
			$email_text .= $this->url->link('product/product', '&product_id=' . $this->request->get['product_id']);

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($email_subject);
			$mail->setText($email_text);
			$mail->send();			
		}
		$data['email_success'] = $this->language->get('email_success');
      
			$this->load->model('catalog/category');
			
			if (isset($this->request->get['path'])) {
				$path = '';
				
				$parts = explode('_', (string)$this->request->get['path']);
				
				$category_id = (int)array_pop($parts);
				
				foreach ($parts as $path_id) {
					if (!$path) {
						$path = $path_id;
						} else {
						$path .= '_' . $path_id;
					}
					
					$category_info = $this->model_catalog_category->getCategory($path_id);
					
					if ($category_info) {
						$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
						);
					}
				}
				
				// Set the last category breadcrumb
				$category_info = $this->model_catalog_category->getCategory($category_id);
				
				if ($category_info) {
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
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
					);
				}
			}
			
			$this->load->model('catalog/manufacturer');
			
			if (isset($this->request->get['manufacturer_id'])) {
				$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
				);
				
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
				
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
				
				if ($manufacturer_info) {
					$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
					);
				}
			}
			
			if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
				$url = '';
				
				if (isset($this->request->get['search'])) {
					$url .= '&search=' . $this->request->get['search'];
				}
				
				if (isset($this->request->get['tag'])) {
					$url .= '&tag=' . $this->request->get['tag'];
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
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
				);
			}
			
			if (isset($this->request->get['product_id'])) {
				$product_id = (int)$this->request->get['product_id'];
				} else {
				$product_id = 0;
			}
			
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			

        // oct_advanced_options_settings start
        $data['text_col_option_name'] = $this->language->get('text_col_option_name');
        $data['text_col_option_image'] = $this->language->get('text_col_option_image');
        $data['text_col_option_sku'] = $this->language->get('text_col_option_sku');
        $data['text_col_option_model'] = $this->language->get('text_col_option_model');
        $data['text_col_option_price'] = $this->language->get('text_col_option_price');
        $data['text_col_option_quantity'] = $this->language->get('text_col_option_quantity');
        // oct_advanced_options_settings end
      
			if ($product_info) {

				if ($this->customer->isLogged()){
					$this->model_catalog_product->addProductToCustomerViewed($product_id, $this->customer->getID());
				}

				if (!$this->crawlerDetect->isCrawler()){
					$this->load->model('catalog/superstat');
					$this->model_catalog_superstat->addToSuperStat('p', $product_info['product_id']);

					if ($product_info['category_id']){
						$this->model_catalog_superstat->addToSuperStat('c', $product_info['category_id']);
					}

					if ($product_info['manufacturer_id']){
						$this->model_catalog_superstat->addToSuperStat('m', $product_info['manufacturer_id']);
					}
				}

				
				$this->load->controller('hobotix/afterpurchase/setAFPKey');									
				
				if (!empty($this->request->get['option_id'])){
					$selected_option_id = $this->request->get['option_id'];
				}
				
				$this->catchAlsoViewed($product_info['product_id']);
				
				$url = '';
				
				if (isset($this->request->get['path'])) {
					$url .= '&path=' . $this->request->get['path'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}
				
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				}
				
				if (isset($this->request->get['search'])) {
					$url .= '&search=' . $this->request->get['search'];
				}
				
				if (isset($this->request->get['tag'])) {
					$url .= '&tag=' . $this->request->get['tag'];
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
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
				);
				
				if ($product_info['meta_title']) {
					$this->document->setTitle($product_info['meta_title']);
					} else {
					$this->document->setTitle($product_info['name']);
				}
				
				$this->document->setDescription($product_info['meta_description']);
				$this->document->setKeywords($product_info['meta_keyword']);

  		$pixel = "fbq('track', 'ViewContent', {
        content_ids: [".$this->request->get['product_id']."],
        content_type: 'product',
        value: ".$product_info['price'].",
        currency: '".$this->session->data['currency']."'
        });
		
		ttq.track('ViewContent', {
        content_ids: [".$this->request->get['product_id']."],
        content_type: 'product',
        value: ".$product_info['price'].",
        currency: '".$this->session->data['currency']."'
        });";
	
      $this->document->setPixel($pixel);
  		
				$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');

				if ($this->config->get('amp_product_pro_status')){
					$this->document->addLink($this->url->link('product/amp_product', 'product_id=' . $this->request->get['product_id']), 'amphtml');
				}

				if (isset($this->request->get['add'])) {
					$this->cart->add($this->request->get['product_id']);
					$this->response->redirect($this->url->link('checkout/cart'));
				}
				
				if ($product_info['meta_h1']) {
					$data['heading_title'] = $product_info['meta_h1'];
					} else {
					$data['heading_title'] = $product_info['name'];
				}
				
				$data['text_select'] = $this->language->get('text_select');
				$data['text_manufacturer'] = $this->language->get('text_manufacturer');
				$data['text_model'] = $this->language->get('text_model');
				$data['text_reward'] = $this->language->get('text_reward');
				$data['text_points'] = $this->language->get('text_points');
				$data['text_stock'] = $this->language->get('text_stock');
				$data['text_discount'] = $this->language->get('text_discount');
				$data['text_tax'] = $this->language->get('text_tax');
				$data['text_option'] = $this->language->get('text_option');
				$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
				$data['text_write'] = $this->language->get('text_write');
				$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
				$data['text_note'] = $this->language->get('text_note');
				$data['text_tags'] = $this->language->get('text_tags');
				$data['text_related'] = $this->language->get('text_related');
				$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
				$data['text_loading'] = $this->language->get('text_loading');
				
				$data['attribute_footer'] = $this->language->get('attribute_footer');
				$data['enter_telephone'] = $this->language->get('enter_telephone');
				$data['text_call_me'] = $this->language->get('text_call_me');
				$data['text_look_at_this'] = $this->language->get('text_look_at_this');
				$data['button_write_review'] = $this->language->get('button_write_review');			
				$data['text_buy_one_click'] = $this->language->get('text_buy_one_click');
				
				$data['fastorder_telephone'] = ($this->customer->isLogged()) ? $this->customer->getTelephone() : '';
				
				//AFP
				$data['is_afp_session'] = $this->load->controller('hobotix/afterpurchase/validateAFPkey');
				$data['text_afp'] = $this->language->get('text_afp');
				$data['text_afp_thank'] = $this->language->get('text_afp_thank');
				$data['text_afp_alert'] = $this->language->get('text_afp_alert');
				$data['btn_continue'] = $this->language->get('btn_continue');
				$data['text_copy'] = $this->language->get('text_copy');
				
				
				$data['entry_qty'] = $this->language->get('entry_qty');
				$data['entry_name'] = $this->language->get('entry_name');
				$data['entry_review'] = $this->language->get('entry_review');
				$data['entry_rating'] = $this->language->get('entry_rating');
				$data['entry_good'] = $this->language->get('entry_good');
				$data['entry_bad'] = $this->language->get('entry_bad');
				

		$data['text_mf_stock'] = $this->language->get('text_mf_stock');
      
				$data['button_cart'] = $this->language->get('button_cart');
				$data['button_wishlist'] = $this->language->get('button_wishlist');
				$data['button_compare'] = $this->language->get('button_compare');
				$data['button_upload'] = $this->language->get('button_upload');
				$data['button_continue'] = $this->language->get('button_continue');
				
				$this->load->model('catalog/review');
				
				$data['tab_description'] = $this->language->get('tab_description');
				$data['tab_attribute'] = $this->language->get('tab_attribute');
				$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
				
				$data['product_id'] = (int)$this->request->get['product_id'];
				
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
				
				$data['hide_manufacturer'] = false;
				if ($manufacturer['hide_manufacturer']){
					$data['hide_manufacturer'] = $this->model_catalog_product->getIfToShowBrandMainCategoryID($product_info);
				}
				
				$data['manufacturer'] = $product_info['manufacturer']?$product_info['manufacturer']:'Vest';
				$data['manufacturers'] = $product_info['manufacturer_id']?$this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']):false;
				
				$data['archive'] = $product_info['archive'];
				$data['text_archive'] = $this->language->get('text_archive');
				

        $oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
        $data['oct_product_stickers'] = [];

        if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
          $this->load->model('catalog/oct_product_stickers');

          if (isset($product_info['oct_product_stickers']) && $product_info['oct_product_stickers']) {
            $stickers = unserialize($product_info['oct_product_stickers']);
          } else {
            $stickers = [];
          }

          foreach ($stickers as $product_sticker_id) {
            $sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
            
            if ($sticker_info) {
              $data['oct_product_stickers'][] = array(
                'text' => $sticker_info['text'],
                'color' => $sticker_info['color'],
                'background' => $sticker_info['background']
              );
            }
          }

          $sticker_sort_order = [];

          foreach ($stickers as $key => $product_sticker_id) {
            $sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
            
            if ($sticker_info) {
              $sticker_sort_order[$key] = $sticker_info['sort_order'];
            }
          }
          
          array_multisort($sticker_sort_order, SORT_ASC, $data['oct_product_stickers']);
        }
      
				$data['model'] = $product_info['model'];

        // oct_techstore start
        $data['sku'] = $product_info['sku'];
        if ((float)$product_info['special']) {
          $data['economy'] = round((($product_info['price'] - $product_info['special'])/($product_info['price'] + 0.01))*100, 0);
          $saver = round($product_info['price'] - $product_info['special']);
          $data['you_save'] = $this->currency->format($this->tax->calculate($saver, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          $this->load->model('octemplates/p_special_timer');
          $product_info_special = $this->model_octemplates_p_special_timer->getProduct($product_id);
          if ($product_info_special) {
            $data['special_date_start']= $product_info_special['date_start'];
            $data['special_date_end'] = $product_info_special['date_end'];
          }
        } else {
          $data['economy'] = false;
          $data['you_save'] = false;
          $data['special_date_start'] = false;
          $data['special_date_end'] = false;
        }
        $data['garanted_text'] = [];
        if (isset($oct_data['pr_garantedtext_show']) && $oct_data['pr_garantedtext_show'] == 'on' && isset($oct_data['pr_garantedtext']) && $oct_data['pr_garantedtext']) {
          foreach ($oct_data['pr_garantedtext'] as $key => $pr_garantedtext) {
            if ($pr_garantedtext['popup'] == 'on') {
              $pr_garantedtext_link = (isset($pr_garantedtext['description'][$this->session->data['language']])) ? str_replace('index.php?route=information/information&', 'index.php?route=information/information/agree&', $pr_garantedtext['description'][$this->session->data['language']]['link']) : '';
            } else {
              $pr_garantedtext_link = (isset($pr_garantedtext['description'][$this->session->data['language']])) ? $pr_garantedtext['description'][$this->session->data['language']]['link'] : '';
            }
            $data['garanted_text'][] = array(
              'id'    => $key,
              'icon'  => $pr_garantedtext['icon'],
              'popup' => $pr_garantedtext['popup'],
              'name'  => (isset($pr_garantedtext['description'][$this->session->data['language']])) ? $pr_garantedtext['description'][$this->session->data['language']]['name'] : '',
              'link'  => ($pr_garantedtext_link == "#" || empty($pr_garantedtext_link)) ? "javascript:void(0);" : $pr_garantedtext_link
            );
          }
        }
        $data['oct_pr_additional_tab'] = [];
        if (isset($oct_data['pr_additional_tab_show']) && $oct_data['pr_additional_tab_show'] == 'on') {
          if (isset($oct_data['pr_additional_tab_heading'][$this->session->data['language']]) && !empty($oct_data['pr_additional_tab_heading'][$this->session->data['language']])) {
            $oct_pr_additional_tab_heading = html_entity_decode($oct_data['pr_additional_tab_heading'][$this->session->data['language']], ENT_QUOTES, 'UTF-8');
          } else {
            $oct_pr_additional_tab_heading = '';
          }
          if (isset($oct_data['pr_additional_tab_text'][$this->session->data['language']]) && !empty($oct_data['pr_additional_tab_text'][$this->session->data['language']])) {
            $oct_pr_additional_tab_text = html_entity_decode($oct_data['pr_additional_tab_text'][$this->session->data['language']], ENT_QUOTES, 'UTF-8');
          } else {
            $oct_pr_additional_tab_text = '';
          }
          if ($oct_pr_additional_tab_heading && $oct_pr_additional_tab_text) {
            $data['oct_pr_additional_tab'] = array(
              'heading' => $oct_pr_additional_tab_heading,
              'text'    => $oct_pr_additional_tab_text
            );
          }
        }
        $data['oct_techstore_pr_social_button_script'] = html_entity_decode($oct_data['pr_social_button_script'], ENT_QUOTES, 'UTF-8');
        if (isset($oct_data['terms']) && $oct_data['terms']) {
          $this->load->model('catalog/information');
          $information_info = $this->model_catalog_information->getInformation($oct_data['terms']);
          if ($information_info) {
            $data['text_terms'] = sprintf($this->language->get('text_oct_terms'), $this->url->link('information/information', 'information_id=' . $oct_data['terms'], 'SSL'), $information_info['title'], $information_info['title']);
          } else {
            $data['text_terms'] = '';
          }
        } else {
          $data['text_terms'] = '';
        }
        // oct_techstore end
      
				$data['model'] = trim($product_info['model']);
				
				$data['micro_sku'] = trim($product_info['sku']);				
				if (!$data['micro_sku']){
					if ($this->isRealNumeric($product_info['model'])){
						$data['micro_sku'] = trim($product_info['model']);
					}
				}
				if (!$data['micro_sku']){
					$data['micro_sku'] = trim($product_info['product_id']);
				}
				
				$data['micro_mpn'] = trim($product_info['mpn']);
				
				if (!$data['micro_mpn']){
					if ($this->isRealNumeric($product_info['model'])){
						$data['micro_mpn'] = trim($product_info['model']);
					}
				}
				
				if (!$data['micro_mpn']){
					$data['micro_mpn'] = trim($product_info['product_id']);
				}									
				
				$data['micro_ean'] = trim($product_info['ean']);								
				
				$data['reward'] = $product_info['reward'];
				$data['points'] = $product_info['points'];
				
       $data['description'] = str_replace("<img", "<img class=\"img-responsive\"",  html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8'));
      
				
				//Акции				
				$this->load->model('catalog/ochelp_special');
				$data['current_action'] = $this->model_catalog_ochelp_special->getActiveSpecialByProduct($product_info['product_id']);
				
				
				if ($data['current_action']){				
				
					$data['current_action']['href'] = $this->url->link('information/ochelp_special', 'special_id=' . $data['current_action']['special_id']);
					$data['current_action']['date_text'] = sprintf($this->language->get('text_current_action_dates'), date('d.m.Y', strtotime($data['current_action']['date_added'])), date('d.m.Y', strtotime($data['current_action']['date_end'])));
					$data['current_action']['date_end'] = dateDiff(date('Y-m-d', strtotime($data['current_action']['date_end'])));
					$data['text_action'] = $this->language->get('text_action');
					$data['button_show_more'] = $this->language->get('button_show_more');
					$data['text_special'] = sprintf($this->language->get('text_action_left'), $data['current_action']['date_end']);
				}
				

		$data['outofstock'] = false;
		if($product_info['quantity'] <= 0) {
			$data['outofstock'] = true;
		}
      

        // oct_popup_found_cheaper start
        $data['text_oct_popup_found_cheaper'] = $this->language->get('text_oct_popup_found_cheaper');
        // oct_popup_found_cheaper end
      

        // oct_popup_purchase start
        $data['text_oct_popup_purchase'] = $this->language->get('text_oct_popup_purchase');
        // oct_popup_purchase end
      

        $oct_product_tabs_data = $this->config->get('oct_product_tabs_data');
        $data['oct_product_extra_tabs'] = [];

        if (isset($oct_product_tabs_data['status']) && $oct_product_tabs_data['status']) {
          $this->load->model('catalog/oct_product_tabs');

          $oct_product_extra_tabs = $this->model_catalog_oct_product_tabs->getProductTabs($product_id);

          if ($oct_product_extra_tabs) {            
            foreach ($oct_product_extra_tabs as $extra_tab) {
              $data['oct_product_extra_tabs'][] = array(
                'title' => $extra_tab['title'],
                'text'  => html_entity_decode($extra_tab['text'], ENT_QUOTES, 'UTF-8')
              );
            }
          }
        }
      
				
				$data['disable_buy'] = 0;
				
				$oct_product_preorder_text = $this->config->get('oct_product_preorder_text');
				$oct_product_preorder_data = $this->config->get('oct_product_preorder_data');
				$oct_product_stock_checkout = $this->config->get('config_stock_checkout');

				if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($product_info['oct_stock_status_id']) && in_array($product_info['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
					if ($product_info['quantity'] <= 0) {
						$data['stock'] = $product_info['stock_status'];
						$data['stockbutton'] = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
						if ($oct_product_stock_checkout == 0) {
							$data['disable_buy'] = 1;
						}
					} elseif ($this->config->get('config_stock_display')) {
						$data['stock'] = $product_info['quantity'];
					} elseif ($product_info['quantity'] >= 1 && $product_info['quantity'] <= 3) {
						$data['stock'] = $this->language->get('text_minstock');
					} else {
						$data['stock'] = $this->language->get('text_instock');
					}
				} else {
					if ($product_info['quantity'] <= 0) {
						$data['stock'] = $product_info['stock_status'];
						$data['stockbutton'] = $product_info['stock_status'];
						if ($oct_product_stock_checkout == 0) {
							$data['disable_buy'] = 2;
						}
					} elseif ($this->config->get('config_stock_display')) {
						$data['stock'] = $product_info['quantity'];
						$data['stockbutton'] = $product_info['quantity'];
					} else {
						$data['stock'] = $this->language->get('text_instock');
						$data['stockbutton'] = $this->language->get('text_instock');
					}
				}
			
				
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
					$data['price'] = false;
				}
				
				if ((float)$product_info['special']) {
					$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					
					if (!$data['current_action']){
						$data['current_special'] = $this->model_catalog_product->getProductSpecialActual($product_info['product_id']);
						
												
						if (is_null($data['current_special']['date_end']) || !$data['current_special']['date_end'] || $data['current_special']['date_end'] == '0000-00-00'){
							$data['current_special']['date_end'] = date('Y-m-d', strtotime("+2 Week"));
						}
						
						
						$data['current_special']['date_text'] = sprintf($this->language->get('text_current_action_dates'), date('d.m.Y', strtotime($data['current_special']['date_start'])), date('d.m.Y', strtotime($data['current_special']['date_end'] . '-1 day')));
					}
					
					
					} else {
					$data['special'] = false;
					$data['current_special'] = false;
				}
				
				if ($this->config->get('config_tax')) {
					$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
					$data['tax'] = false;
				}
				
				$data['gift_teaser'] = $this->load->controller('extension/module/giftteaser/checkGiftsForCurrentProduct', array('product_id' => $this->request->get['product_id'], 'return_data' => true));		
				$data['text_gift'] = $this->language->get('text_gift');
				
				$data['product_id'] = $this->request->get['product_id'];
				$data['language']  = $this->config->get('config_language');
				$this->load->model('extension/module/giftteaser');
				$freeGifts = $this->model_extension_module_giftteaser->getCurrentGifts();	 	
				$data['freeGifts'] = [];
				
				foreach ($freeGifts as $freeGift) {
					
					$descriptions = unserialize(base64_decode($freeGift['description']));
					
					$data['freeGifts'][] = array(
					'id' 			=> $freeGift['item_id'],
					'start_date'    => $freeGift['start_date'],
					'end_date'    	=> $freeGift['end_date'],
					'description' 	=> html_entity_decode($descriptions['desc_' . $this->config->get('config_language')]),
					);
				}
				
				$this->load->model('setting/setting');
				
				$setting = $this->model_setting_setting->getSetting('giftteaser', $this->config->get('config_store_id'));
				if(isset($setting['giftteaser'])) {
					$data['giftTeaser_data'] = $setting['giftteaser']; 
				}
				
				$data['action_stickers'] = $product_info['action_stickers'];

			//	var_dump($product_info['action_stickers']);
				

      if(!$data['special']){
        $data['fbprice'] = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
      }else{
        $data['fbprice'] = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
      }
       $data['fbcurrency'] = $this->session->data['currency'];
      
				$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
				
				$data['discounts'] = [];
				
				foreach ($discounts as $discount) {
					$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
				
				$data['options'] = [];
				
				foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
					$product_option_value_data = [];
					
					$count_options_in_stock = 0;
					foreach ($option['product_option_value'] as $option_value) {
						if ($option_value['quantity'] > 0){
							$count_options_in_stock++;
						}
					}
					unset($option_value);
					
					$big_option_images = false;
					foreach ($option['product_option_value'] as $option_value) {
						if (true /*!$option_value['subtract'] ||  ($option_value['quantity'] > 0)*/) {
							if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
								$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
								} else {
								$price = false;
							}
							
							$selected = false;
							if (!empty($selected_option_id) && $option_value['product_option_value_id'] == (int)$selected_option_id){
								$selected = true;
							}
							
							if (count($option['product_option_value']) == 1){
							//	$selected_option_id = $option_value['product_option_value_id'];
							//	$selected = true;
							}		
							
							if ($count_options_in_stock == 1){
								if ($option_value['quantity'] > 0){
							//		$selected_option_id = $option_value['product_option_value_id'];
							//		$selected = true;
								}
							}
							
							if (!$option_value['image'] && $option_value['o_v_image']){
								$option_value['image'] = $option_value['o_v_image'];
								$big_option_images = true;
							}													
							
							
							$product_option_value_data[] = array(
                'quantity_status'         => ($option_value['quantity'] <= 0) ? false : true,
                'sku'                     => (isset($option_value['sku']) && $option_value['sku']) ? $option_value['sku'] : ($product_info['sku'] ? $product_info['sku'] : ''),
                'ean'                     => (isset($option_value['ean']) && $option_value['ean']) ? $option_value['ean'] : ($product_info['ean'] ? $product_info['ean'] : ''),
                'model'                   => (isset($option_value['model']) && $option_value['model']) ? $option_value['model'] : $product_info['model'],
                'model'                   => (isset($option_value['isbn']) && $option_value['isbn']) ? $option_value['isbn'] : $product_info['isbn'],
                'o_v_image'               => (isset($option_value['o_v_image']) && $option_value['o_v_image']) ? $this->model_tool_image->resize($option_value['o_v_image'], 50, 50) : $this->model_tool_image->resize("no_image.jpg", 50, 50),
                'product_option_value_id' => $option_value['product_option_value_id'],
                'option_value_id'         => $option_value['option_value_id'],
                'name'                    => $option_value['name'],
                'quantity'				  => $option_value['quantity'],
                'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
                'price'                   => $price,
                'selected'                => $selected,
                'price_prefix'            => $option_value['price_prefix']
              );
						}
					}

					//rebuild options_data					
					$stock_options_data = [];
					$not_in_stock_options_data = [];
					foreach ($product_option_value_data as $__option_value_data){
						if ($__option_value_data['quantity']){
							$stock_options_data[] = $__option_value_data;
						} else {
							$not_in_stock_options_data[] = $__option_value_data;
						}
					}

					$product_option_value_data = [];

					foreach ($stock_options_data as $_stock_options_data){
						$product_option_value_data[] = $_stock_options_data;
					}

					foreach ($not_in_stock_options_data as $_not_in_stock_options_data){
						$product_option_value_data[] = $_not_in_stock_options_data;
					}

					$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'big_option_images'    => $big_option_images,
					'option_id'            => $option['option_id'],
					'name'                 => strpos($this->language->get('text_select_option'), '%s')?sprintf($this->language->get('text_select_option'), mb_strtolower($option['name'])):$option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
					);
				}
				
				$this->load->model('tool/image');
				
				$data['thumb_noimage'] = $this->model_tool_image->resize('no_image.png', $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'));
				
				
				if ($product_info['image']) {
					$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
					} else {
					$data['popup'] = '';
				}
				
				if ($product_info['image']) {
					$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
					$this->document->setOgImage($data['thumb']);
					} else {
					$data['thumb'] = '';
				}
				
				if ($data['archive']){
					if ($product_info['image']) {
						$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'), $greyscale = true);
						} else {
						$data['popup'] = '';
					}
					
					if ($product_info['image']) {
						$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'), $greyscale = true);
						$this->document->setOgImage($data['thumb']);												
						} else {
						$data['thumb'] = '';
					}
				}
				
				$data['images'] = [];
				
				
        // oct_advanced_options_settings start
        $data['oct_advanced_options_settings_data'] = $oct_advanced_options_settings_data = $this->config->get('oct_advanced_options_settings_data');
        $data['check_zoom'] = isset($oct_advanced_options_settings_data['allow_zoom']) ? $oct_advanced_options_settings_data['allow_zoom'] : '0';
        if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) {
          $this->document->addScript('catalog/view/theme/oct_techstore/js/jquery.magnify.js');
          $this->document->addStyle('catalog/view/theme/oct_techstore/stylesheet/magnify.css');
          $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
          $data['images'][] = array(
			'video_in_product' => $result['video_in_product'],
			'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
			'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
            'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
            'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height')),
            'main_img'   => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')),
            'main_popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'))
          );
          foreach ($results as $result) {
            $data['images'][] = array(
			  'video_in_product' => $result['video_in_product'],
			'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
			'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
              'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
              'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height')),
              'main_img'   => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')),
              'main_popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'))
            );
          }
        } else {
          $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
          $data['images'][] = array(
			'video_in_product' => $result['video_in_product'],
			'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
			'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
            'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
            'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
          );
          foreach ($results as $result) {
            $data['images'][] = array(
				'video_in_product' => $result['video_in_product'],
			'video_in_product_width' => $this->config->get($this->config->get('config_theme') . '_image_additional_width'),
			'video_in_product_height' => $this->config->get($this->config->get('config_theme') . '_image_additional_height'),
              'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
              'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
            );
          }
        }
        // oct_advanced_options_settings end
      
				if ($data['archive']){					
					$data['imagesarchive'] = [];
					
					$data['imagesarchive'][] = array(
					'popup' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'), $greyscale = true),
					'thumb' => $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'), $greyscale = true)
					);
					
					$resultsarchive = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
					foreach ($resultsarchive as $resultarchive) {
						$data['imagesarchive'][] = array(
						'popup' => $this->model_tool_image->resize($resultarchive['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'), $greyscale = true),
						'thumb' => $this->model_tool_image->resize($resultarchive['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'), $greyscale = true)
						);
					}
					$data['images'] = $data['imagesarchive'];
				}
				
										
				$data['overloaded_from_option'] = false;
				if (!empty($selected_option_id)){
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov
           LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) 
           LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 
           WHERE pov.product_id = '" . (int)$this->request->get['product_id'] . "' AND pov.product_option_value_id = '" . (int)$selected_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order LIMIT 1");
					
					if ($query->num_rows && !empty($query->row['name'])){
						$data['heading_title'] = $data['heading_title'] . ' ' . mb_strtolower($query->row['name']);
						$this->document->setTitle(str_ireplace($product_info['name'], $product_info['name'] . ' ' . mb_strtolower($query->row['name']), $this->document->getTitle()));
						$data['breadcrumbs'][count($data['breadcrumbs']) - 1]['text'] = $data['breadcrumbs'][count($data['breadcrumbs']) - 1]['text'] . ' ' . mb_strtolower($query->row['name']);
						
						if (!empty($query->row['sku'])) {
							$data['micro_sku'] = trim($query->row['sku']);
           } else {
             $data['micro_sku'] = trim((int)$this->request->get['product_id'] . '-' . (int)$query->row['option_id'] . '-' . (int)$query->row['option_value_id']);
           }

           $product_info['quantity'] = (int)$query->row['quantity'];


           $data['outofstock'] = false;
           if($product_info['quantity'] <= 0) {
             $data['outofstock'] = true;
           }

           $data['text_oct_popup_found_cheaper'] = $this->language->get('text_oct_popup_found_cheaper');
           $data['text_oct_popup_purchase'] = $this->language->get('text_oct_popup_purchase');

           $oct_product_tabs_data = $this->config->get('oct_product_tabs_data');
           $data['oct_product_extra_tabs'] = [];

           if (isset($oct_product_tabs_data['status']) && $oct_product_tabs_data['status']) {
            $this->load->model('catalog/oct_product_tabs');

            $oct_product_extra_tabs = $this->model_catalog_oct_product_tabs->getProductTabs($product_id);

            if ($oct_product_extra_tabs) {            
              foreach ($oct_product_extra_tabs as $extra_tab) {
                $data['oct_product_extra_tabs'][] = array(
                  'title' => $extra_tab['title'],
                  'text'  => html_entity_decode($extra_tab['text'], ENT_QUOTES, 'UTF-8')
                );
              }
            }
          }
      
						
				$data['disable_buy'] = 0;
				
				$oct_product_preorder_text = $this->config->get('oct_product_preorder_text');
				$oct_product_preorder_data = $this->config->get('oct_product_preorder_data');
				$oct_product_stock_checkout = $this->config->get('config_stock_checkout');

				if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($product_info['oct_stock_status_id']) && in_array($product_info['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
					if ($product_info['quantity'] <= 0) {
						$data['stock'] = $product_info['stock_status'];
						$data['stockbutton'] = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
						if ($oct_product_stock_checkout == 0) {
							$data['disable_buy'] = 1;
						}
					} elseif ($this->config->get('config_stock_display')) {
						$data['stock'] = $product_info['quantity'];
					} elseif ($product_info['quantity'] >= 1 && $product_info['quantity'] <= 3) {
						$data['stock'] = $this->language->get('text_minstock');
					} else {
						$data['stock'] = $this->language->get('text_instock');
					}
				} else {
					if ($product_info['quantity'] <= 0) {
						$data['stock'] = $product_info['stock_status'];
						$data['stockbutton'] = $product_info['stock_status'];
						if ($oct_product_stock_checkout == 0) {
							$data['disable_buy'] = 2;
						}
					} elseif ($this->config->get('config_stock_display')) {
						$data['stock'] = $product_info['quantity'];
						$data['stockbutton'] = $product_info['quantity'];
					} else {
						$data['stock'] = $this->language->get('text_instock');
						$data['stockbutton'] = $this->language->get('text_instock');
					}
				}
			

						$data['overloaded_from_option'] = true;
						
						if (!empty($query->row['ean'])){
							$data['micro_ean'] = trim($query->row['ean']);
						}
						
					}
					
					//а вот теперь нам надо как-то совместить логику с advanced_options_settings от оцтемплейтс
					if ($query->num_rows && $query->row['o_v_image']){
						//1 вариант - картинка и так главная, делать ничего не будем
						if ($query->row['o_v_image'] == $product_info['image']){
							
							} else {
							
							$tmp_images = [];	
							
							$resized_o_v_image =  $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));			
							
							
							
							foreach ($data['images'] as $__image){
								//Список картинок содержит картинку опции
								$image_list_contains_ovimage = false;
								//set a main
								if ($__image['popup'] == $resized_o_v_image){
									$image_list_contains_ovimage = true;
									
									//штатная логика
									$data['popup'] = $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_width'));
									$data['thumb'] = $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_width'));	
									$this->document->setOgImage($data['thumb']);								
									
									//логика оцтемплейтс
									if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) {
										
										//в начало массива
										array_unshift($tmp_images, array(
										'popup' => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
										'thumb' => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height')),
										'main_img'   => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')),
										'main_popup' => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'))
										) 
										);
										
									}									
									} else {									
									
									$tmp_images[] = $__image;
								}
							}
							
							$data['images'] = $tmp_images;
						}
					}
					
					if (!$image_list_contains_ovimage){
						//Добавляем ее в самое начало, и ставим первой
						
						//штатная логика
						$data['popup'] = $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_width'));
						$data['thumb'] = $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_width'));	
						$this->document->setOgImage($data['thumb']);	
						
						array_unshift($data['images'], array(
						'popup' => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
						'thumb' => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height')),
						'main_img'   => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')),
						'main_popup' => $this->model_tool_image->resize($query->row['o_v_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'))
						) 
						);
						
						
						
						
					}
					
					if ($query->num_rows && $query->row['sku']){
						$data['sku'] = $query->row['sku'];
					}
				}
				
				if ($product_info['minimum']) {
					$data['minimum'] = $product_info['minimum'];
					} else {
					$data['minimum'] = 1;
				}
				
				$data['review_status'] = $this->config->get('config_review_status');
				
				if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
					$data['review_guest'] = true;
					} else {
					$data['review_guest'] = false;
				}
				
				if ($this->customer->isLogged()) {
					$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
					} else {
					$data['customer_name'] = '';
				}
				
				$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
				$data['rating'] = (int)$product_info['rating'];
				
				// Captcha
				if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
					$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
					} else {
					$data['captcha'] = '';
				}
				
				$data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);
				$data['share_txt'] = $this->language->get('text_look_at_this') . ' ' . $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);
				$data['share_txt_with_name'] = $this->language->get('text_look_at_this') . ' ' . $data['heading_title'];
				
				$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);	
				$data['ocfilters'] = $this->model_catalog_product->getProductOcFilterActiveValues($this->request->get['product_id'], ', ');
				$data['ocfilters_nonstructured'] = $this->model_catalog_product->getProductOcFilterActiveValuesNonGrouped($this->request->get['product_id'], $category_id);				
				$data['ocfilters'] = attributesOcFilterUnique($data['attribute_groups'], $data['ocfilters'], 'ocfilter');	
				
				if ($category_info){
					foreach ($data['ocfilters_nonstructured'] as &$ocfilter_nonstuctured){
						$ocfilter_nonstuctured['href'] = $this->url->link('product/category', 'path=' . $category_info['category_id'] . '&filter_ocfilter=' . $ocfilter_nonstuctured['option_value']);
						$ocfilter_nonstuctured['title'] = trim($category_info['name']) . ', ' . $ocfilter_nonstuctured['name'] . ': ' . $ocfilter_nonstuctured['value'];
					}					
				}		
				
				
				//Уникальные опции оцфильтра, после уникализации с атрибутами (атрибуты на первом месте)
				$ocfilter_option_ids = [];
				foreach ($data['ocfilters'] as $ocfilter){
					$ocfilter_option_ids[] = $ocfilter['option_id'];
				}
				
				//Убрали из необъединенного оцфильтра опции, которые подменяются структурировано атрибутами
				$data['ocfilters_restructured'] = [];				
				foreach ($data['ocfilters_nonstructured'] as $ocfilter_nonstuctured2){
					if (in_array($ocfilter_nonstuctured2['option_id'], $ocfilter_option_ids)){
						if (!isset($data['ocfilters_restructured'][$ocfilter_nonstuctured2['option_id']])){
							$data['ocfilters_restructured'][$ocfilter_nonstuctured2['option_id']] = array($ocfilter_nonstuctured2);
							} else {
							$data['ocfilters_restructured'][$ocfilter_nonstuctured2['option_id']][] = $ocfilter_nonstuctured2;
						}
					}
				}
				
				
				//А это чтоб сохранить совместимость
				$data['ocfilters'] = $data['ocfilters_restructured'];
				
				//А теперь ищем в атрибутах то, что мы, возможно, подменили
				$data['attribute_groups'] = attributeToOCFilterLinkMagic($data['attribute_groups'], $data['ocfilters_nonstructured']);
				
				
				if ($category_info){
					foreach ($data['attribute_groups'] as &$ag){
						if (!empty($ag['attribute'])){				
							foreach ($ag['attribute'] as &$attribute){
								if (!empty($attribute['option_id'])){
									$attribute['href'] = $this->url->link('product/category', 'path=' . $category_info['category_id'] . '&filter_ocfilter=' . $attribute['option_id'].':'.$attribute['value_id']);
									$attribute['title'] = trim($category_info['name']) . ', ' . $attribute['name'] . ': ' . $attribute['text'];
								}
							}
						}
					}					
				}		
				
				
				//Бесплатная доставка
				$price = $product_info['price'];				
				if ($product_info['special']){
					$price = $product_info['special'];
				}
				$data['free_delivery'] = false;
				$data['delivery_link'] = $this->url->link('information/information', 'information_id=6', 'SSL');
				$data['delivery_text'] = $this->language->get('delivery_text');
				
				$codes = array(
				'novaposhta_min_total_for_free_delivery', 'justin_min_total_for_free_delivery', 'ukrposhta_min_total_for_free_delivery', 'novaposhtacopy_min_total_for_free_delivery'				);
				
				foreach ($codes as $_code){
					
					if ($this->config->get($_code) > 0 && $price > (int)$this->config->get($_code) && $this->model_catalog_product->getIfProductIsInFreeDeliveryCategories($product_info['product_id'])){
						$data['free_delivery'] = true;	
						$data['delivery_text'] = $this->language->get('free_delivery_text');
						break;
					}
					
				}
				

				$data['hobofaq'] = $this->load->controller('hobotix/hobofaq');
				$data['description'] .= '<div class="row"><div class="col-xs-12">' . $data['hobofaq'] . '</div></div>';
			
				$data['products'] = [];
				
				$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);																				
				$boughtWith = $this->model_catalog_product->getProductBoughtWith($this->request->get['product_id']);							
				
				$bw_sort_order = [];
				
				foreach ($boughtWith as $key => $value) {
					$bw_sort_order[$key] = $value['sort_order'];
				}
				
				//сортируем по количеству покупок
				array_multisort($bw_sort_order, SORT_DESC, $boughtWith);
				
				//Разбиваем на 2 массива, те которые идут до и которые идут после
				$boughtWithArrBeforeRel = [];
				$boughtWithArrAfterRel = [];
				foreach ($boughtWith as $boughtWithProduct){
					if ($boughtWithProduct['sort_order'] > 1){
						$boughtWithArrBeforeRel[$boughtWithProduct['product_id']] = $boughtWithProduct;
						} else {
						$boughtWithArrAfterRel[$boughtWithProduct['product_id']] = $boughtWithProduct;
					}
				}				
				
				//Уникализируем массив ручных, с логикой, что товар может входить в массивы уже купленных))
				$relatedDistinct = [];
				foreach ($results as $result){
					if (isset($boughtWithArrBeforeRel[$result['product_id']])){
						//pass, ничего не делаем, он будет выше и так
						} elseif (isset($boughtWithArrAfterRel[$result['product_id']])){
						//его таки купили 1 раз, но мы хотим показать его все-таки выше
						$relatedDistinct[$result['product_id']] = $result;
						unset($boughtWithArrAfterRel[$result['product_id']]);
						} else {
						//не покупали ниразу
						$relatedDistinct[$result['product_id']] = $result;
					}
				}								
				
				
				//и лепим все в один массив
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
				foreach ($costMoreThanMain as &$p){
					$p['can_do_addon_discount'] = false;
					$results[] = $p;
				}	

				foreach ($costLessThanMain as &$p){
					$p['can_do_addon_discount'] = true;
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
					

        $oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
        $oct_product_stickers = [];

        if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
          $this->load->model('catalog/oct_product_stickers');

          if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
            $stickers = unserialize($result['oct_product_stickers']);
          } else {
            $stickers = [];
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
    
              $sticker_sort_order = [];
    
              foreach ($stickers as $key => $product_sticker_id) {
                $sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
                
                if ($sticker_info) {
                  $sticker_sort_order[$key] = $sticker_info['sort_order'];
                }
              }
              
              array_multisort($sticker_sort_order, SORT_ASC, $oct_product_stickers);
          }
        }
      

        $oct_product_preorder_text = $this->config->get('oct_product_preorder_text');
				$oct_product_preorder_data = $this->config->get('oct_product_preorder_data');
				$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');

				if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
					$product_preorder_text = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
					$product_preorder_status = 1;
				} else {
					$product_preorder_text = $oct_product_preorder_language['text_out_of_stock'];
					$product_preorder_status = 2;
				}
      
					$data['products'][] = array(

        'quantity' => $result['quantity'], 
		    'product_preorder_text' => $product_preorder_text,
			  'product_preorder_status' => $product_preorder_status,
      

        'oct_product_stickers' => $oct_product_stickers,
      
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'thumb_addon' => $image_addon,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'can_do_addon_discount' => $result['can_do_addon_discount'],
					'action_stickers' 	=> $result['action_stickers'],
					'special'     => $special,
					'tax'         => $tax,
					'sort_order'  => $result['sort_order'],
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}				
				
				//ЗАМЕНИТЕЛИ
				$data['replacements'] = [];
				//(int)0 НЕ МЕНЯТЬ, для совместимости с модификаторами OCT
				if ($product_info['quantity'] <= (int)0) {										
					$results = $this->model_catalog_product->guessSameProducts($this->request->get['product_id'], $product_info['name'], 7, true);
					
					foreach ($results as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
							} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
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
						
						/* OCT */
						$oct_product_stickers_data = $this->config->get('oct_product_stickers_data');
						$oct_product_stickers = [];
						
						if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) {
							$this->load->model('catalog/oct_product_stickers');
							
							if (isset($result['oct_product_stickers']) && $result['oct_product_stickers']) {
								$stickers = unserialize($result['oct_product_stickers']);
								} else {
								$stickers = [];
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
								
								$sticker_sort_order = [];
								
								foreach ($stickers as $key => $product_sticker_id) {
									$sticker_info = $this->model_catalog_oct_product_stickers->getProductSticker($product_sticker_id);
									
									if ($sticker_info) {
										$sticker_sort_order[$key] = $sticker_info['sort_order'];
									}
								}
								
								array_multisort($sticker_sort_order, SORT_ASC, $oct_product_stickers);
							}
						}
						
						
						$oct_product_preorder_text = $this->config->get('oct_product_preorder_text');
						$oct_product_preorder_data = $this->config->get('oct_product_preorder_data');
						$oct_product_preorder_language = $this->load->language('extension/module/oct_product_preorder');
						
						if (isset($oct_product_preorder_data['status']) && $oct_product_preorder_data['status'] && isset($oct_product_preorder_data['stock_statuses']) && isset($result['oct_stock_status_id']) && in_array($result['oct_stock_status_id'], $oct_product_preorder_data['stock_statuses'])) {
							$product_preorder_text = $oct_product_preorder_text[$this->session->data['language']]['call_button'];
							$product_preorder_status = 1;
							} else {
							$product_preorder_text = $oct_product_preorder_language['text_out_of_stock'];
							$product_preorder_status = 2;
						}
						
						
						$data['replacements'][] = array(
						'product_id'  => $result['product_id'],
						'quantity' => $result['quantity'], 
						'product_preorder_text' => $product_preorder_text,
						'product_preorder_status' => $product_preorder_status,
						'oct_product_stickers' => $oct_product_stickers,						
						'thumb'       => $image,
						'name'        => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
						// 'action_stickers' 	=> $result['action_stickers'],
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
						);
					}
					
					if ($data['replacements']){					
						$data['replacements'] = $this->load->controller('product/product/loadReplacements', array('products' => $data['replacements']));
					}
				}
				
				$data['tags'] = [];
				
				if ($product_info['tag']) {
					$tags = explode(',', $product_info['tag']);
					
					foreach ($tags as $tag) {
						$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
						);
					}
				}
				
				//ЮТУБ ВИДЕО В ОПИСАНИЯ
				foreach ($data['images'] as $___image){
					if ($___image['video_in_product']){
						$data['description'] .= '<p style="text-align:center;"><iframe src="//www.youtube.com/embed/'. $___image['video_in_product'] .'" class="note-video-clip" width="640" height="360" frameborder="0"></iframe><br /></p>';
					}										
				}
				
				$data['has_description'] = mb_strlen(trim(strip_tags($data['description'])));
				
				
				$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);
				
				$this->model_catalog_product->updateViewed($this->request->get['product_id']);

        // oct_product_viewed start
        $this->session->data['oct_product_viewed'][] = $this->request->get['product_id'];
        // oct_product_viewed end
      


				//Ecommerce информация для быстрого заказа
				$transactionProduct = array(
					'id'  			=> $product_info['product_id'],
					'sku' 			=> $product_info['sku']?$product_info['sku']:$product_info['model'],
					'name' 			=> $product_info['name'],
					'manufacturer' 	=> $product_info['manufacturer'],
					'category' 		=> $this->model_catalog_product->getGoogleCategoryPath($product_info['product_id']),
					'price' 		=> $product_info['special']?$product_info['special']:$product_info['price'],
					'total' 		=> $product_info['special']?$product_info['special']:$product_info['price'],
					'quantity' 		=> 1,
				);			

				$data['google_ecommerce_info'] = array(	
					'transactionCurrency'		=> $this->config->get('config_currency'),
					'transactionAffiliation'    => $this->config->get('config_url'),
					'transactionTax'			=> 0.00,
					'transactionTotal'			=> $product_info['special']?$product_info['special']:$product_info['price'],
					'transactionShipping'		=> 0.00,
					'transactionEstimatedDelivery'	=> date('Y-m-d', strtotime('+2 day')),
					'transactionCountryCode'	=> 'UA',
				);	

				$data['google_ecommerce_info'] = array_map('prepareEcommString', $data['google_ecommerce_info']);
				$data['google_ecommerce_info']['transactionProducts'][] = $transactionProduct;
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				$data['review'] = $this->load->controller('product/product/review', array('return' => true));
				
				$this->response->setOutput($this->load->view('product/product', $data));
				} else {
				$url = '';
				
				if (isset($this->request->get['path'])) {
					$url .= '&path=' . $this->request->get['path'];
				}
				
				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}
				
				if (isset($this->request->get['manufacturer_id'])) {
					$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
				}
				
				if (isset($this->request->get['search'])) {
					$url .= '&search=' . $this->request->get['search'];
				}
				
				if (isset($this->request->get['tag'])) {
					$url .= '&tag=' . $this->request->get['tag'];
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
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
				);
				
				$this->document->setTitle($this->language->get('text_error'));
				
				$data['heading_title'] = $this->language->get('text_error');
				
				$data['text_error'] = $this->language->get('text_error');
				
				$data['button_continue'] = $this->language->get('button_continue');
				
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
		
		public function loadReplacements($products){
			$this->load->language('product/product');
			$products['text_replacement'] = $this->language->get('text_similar_in_stock');
			return $this->load->view('product/product_replacement', $products);
		}
		

        // oct_product_reviews start
        public function oct_review_reputation() {
          $json = [];

          $this->load->language('extension/module/oct_product_reviews');

          if (isset($this->request->get['review_id']) && isset($this->request->get['reputation_type'])) {

            $this->load->model('catalog/review');

            $check_ip = $this->model_catalog_review->checkOctUserIp($this->request->server['REMOTE_ADDR'], $this->request->get['review_id']);

			// var_dump($check_ip);

            if ($check_ip) {
              $json['error'] = $this->language->get('error_ip_exist');
            }

            if (!isset($json['error'])) {

              $filter_data = array(
                'review_id' => (int)$this->request->get['review_id'],
                'ip' => $this->request->server['REMOTE_ADDR'],
                'reputation_type' => (int)$this->request->get['reputation_type']
              );

              $this->model_catalog_review->addOctProductReputation($filter_data);

			  $this->cache->flush();

              $json['success'] = $this->language->get('text_success');
            }
          }

          $this->response->addHeader('Content-Type: application/json');
          $this->response->setOutput(json_encode($json));
        }
        // oct_product_reviews end
      
        public function review($return = false) {        
          $data['oct_product_reviews_data'] = $this->config->get('oct_product_reviews_data');
          $this->load->language('product/product');
          $this->load->language('extension/module/oct_product_reviews');

          $this->load->model('catalog/review');
          $this->load->model('catalog/product');

          $data['text_no_reviews'] = $this->language->get('text_no_reviews');        

          $oct_product_reviews_data = $this->config->get('oct_product_reviews_data');

          $data['entry_positive_text'] = $this->language->get('entry_positive_text');
          $data['entry_negative_text'] = $this->language->get('entry_negative_text');
          $data['text_where_bought'] = $this->language->get('text_where_bought');
          $data['text_where_bought_yes'] = $this->language->get('text_where_bought_yes');
          $data['text_where_bought_no'] = $this->language->get('text_where_bought_no');
          $data['text_admin_answer'] = $this->language->get('text_admin_answer');
          $data['text_my_assessment'] = $this->language->get('text_my_assessment');

          $data['product'] = $this->model_catalog_product->getProduct($this->request->get['product_id']);

          if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
          } else {
            $page = 1;
          }

          $data['reviews'] = [];

          $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);
          $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);					

          $rating_total = 0;
          foreach ($results as $result) {
            if (isset($oct_product_reviews_data['status']) && $oct_product_reviews_data['status']) {
              $positive_text = html_entity_decode($result['positive_text'], ENT_QUOTES, 'UTF-8');
              $negative_text = html_entity_decode($result['negative_text'], ENT_QUOTES, 'UTF-8');
              $admin_answer = html_entity_decode($result['admin_answer'], ENT_QUOTES, 'UTF-8');
              $plus_reputation = (int)$result['plus_reputation'];
              $minus_reputation = (int)$result['minus_reputation'];
              $where_bought = ($result['where_bought']) ? $this->language->get('text_where_bought_yes') : $this->language->get('text_where_bought_no');
            } else {
              $positive_text = '';
              $negative_text = '';
              $admin_answer = '';
              $plus_reputation = FALSE;
              $minus_reputation = FALSE;
              $where_bought = FALSE;
            }

           if (isset($result['rating'])) {
				if ((int)$result['rating'] < 0.5) {
					$product_rating = 0;
				} elseif ((int)$result['rating'] >= 1) {
					$product_rating = 1;
				}
			} else {
				$product_rating = 0;
			}

			$rating_total+= (int)$product_rating;

            $data['reviews'][] = array(
              'review_id'        => $result['review_id'],
              'positive_text'    => $positive_text,
              'negative_text'    => $negative_text,
              'admin_answer'     => $admin_answer,
              'plus_reputation'  => $plus_reputation,
              'minus_reputation' => $minus_reputation,
              'where_bought'     => $where_bought,
              'author'     => $result['author'],
              'text'       => nl2br($result['text']),
              'rating'     => $product_rating,
              'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
          }

          if ($results){
            $data['ratingValue'] = round($rating_total/$review_total, 1);
          }

          $pagination = new Pagination();
          $pagination->total = $review_total;
          $pagination->page = $page;
          $pagination->limit = 5;
          $pagination->url = str_replace('/ua/', '/', $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}'));

          $data['pagination'] = $pagination->render();

          $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

          if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->response->setOutput($this->load->view('product/review', $data));
          } else {
            if (!$return){
             $this->response->redirect($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']));
           }
           return $this->load->view('product/review', $data);
         }
       }
		
		public function write() {
			$this->load->language('product/product');
			
			$json = [];
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
					$json['error'] = $this->language->get('error_name');
				}
				
				if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
					$json['error'] = $this->language->get('error_text');
				}
				

        $oct_data = $this->config->get('oct_techstore_data');
        if (isset($oct_data['terms']) && $oct_data['terms']) {
          if (!isset($this->request->post['terms'])) {
            $this->load->model('catalog/information');
            $information_info = $this->model_catalog_information->getInformation($oct_data['terms']);
            $json['error'] = sprintf($this->language->get('error_oct_terms'), $information_info['title']);
          }
        }
      
				if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
					$json['error'] = $this->language->get('error_rating');
				}
				
				// Captcha
				if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
					$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
					
					if ($captcha) {
						$json['error'] = $captcha;
					}
				}
				
				if (!isset($json['error'])) {
					$this->load->model('catalog/review');
					
					$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);
					
					if ($afp_id = $this->load->controller('hobotix/afterpurchase/validateAFPkey')){
						$this->load->model('marketing/coupon');	
						$coupon = $this->model_marketing_coupon->generateCouponForAFP($afp_id);
						$this->load->controller('hobotix/afterpurchase/deleteAFPkey');
						$json['coupon'] = $coupon['code'];						
					}
					
					$json['success'] = $this->language->get('text_success');
				}
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		
		public function getRecurringDescription() {
			$this->load->language('product/product');
			$this->load->model('catalog/product');
			
			if (isset($this->request->post['product_id'])) {
				$product_id = $this->request->post['product_id'];
				} else {
				$product_id = 0;
			}
			
			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
				} else {
				$recurring_id = 0;
			}
			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
				} else {
				$quantity = 1;
			}
			
			$product_info = $this->model_catalog_product->getProduct($product_id);
			$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);
			
			$json = [];
			
			if ($product_info && $recurring_info) {
				if (!$json) {
					$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
					);
					
					if ($recurring_info['trial_status'] == 1) {
						$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
						} else {
						$trial_text = '';
					}
					
					$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					
					if ($recurring_info['duration']) {
						$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
						} else {
						$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
					}
					
					$json['success'] = $text;
				}
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}																											