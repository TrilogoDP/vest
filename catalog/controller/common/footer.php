<?php
	class ControllerCommonFooter extends Controller {
		public function index() {

        // subscribe start
        $data['oct_popup_subscribe_form_data'] = $this->config->get('oct_popup_subscribe_form_data');
        $data['oct_popup_subscribe'] = $this->load->controller('extension/module/oct_static_subscribe');
        // subscribe end
        

        // popup_product_options start
        $data['oct_popup_product_options_data'] = $this->config->get('oct_popup_product_options_data');
        // popup_product_options end
      

        // oct_popup_call_phone start
        $data['oct_popup_call_phone_data'] = $this->config->get('oct_popup_call_phone_data');
        $data['popup_call_phone_text'] = $this->language->load('extension/module/oct_popup_call_phone');
        // oct_popup_call_phone end
      
			$this->load->language('common/footer');

        $this->document->addScript('catalog/view/javascript/giftteaser/fancybox/jquery.fancybox.pack.js');
        $this->document->addStyle('catalog/view/javascript/giftteaser/fancybox/jquery.fancybox.css');
     //  $this->document->addStyle('catalog/view/theme/default/stylesheet/giftteaser.css');
    //	$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
	//	$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
	//	$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');  
      
			
			$data['scripts'] = $this->document->getScripts('footer');
			
			$data['text_information'] = $this->language->get('text_information');
			$data['text_service'] = $this->language->get('text_service');
			$data['text_extra'] = $this->language->get('text_extra');
			$data['text_contact'] = $this->language->get('text_contact');
			$data['lang'] = $this->language->get('code');
			$data['text_return'] = $this->language->get('text_return');
			$data['text_sitemap'] = $this->language->get('text_sitemap');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_voucher'] = $this->language->get('text_voucher');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			$data['text_special'] = $this->language->get('text_special');
			$data['text_newproducts'] = $this->language->get('text_newproducts');
			$data['text_account'] = $this->language->get('text_account');
			$data['text_order'] = $this->language->get('text_order');
			$data['text_wishlist'] = $this->language->get('text_wishlist');
			$data['text_newsletter'] = $this->language->get('text_newsletter');
			$data['text_cookie_close'] = $this->language->get('text_cookie_close');//***
			$data['text_cookie'] = $this->language->get('text_cookie');//***
			$data['text_look_at_this'] = $this->language->get('text_look_at_this');//***
			$data['button_hide_more'] = $this->language->get('button_hide_more');//***
			$data['button_show_more'] = $this->language->get('button_show_more');//***
			
			$data['text_retranslate_app_block'] = $this->language->get('text_retranslate_app_block');//***
			$data['text_retranslate_app_install'] = $this->language->get('text_retranslate_app_install');//***
			
			$this->load->model('catalog/information');
			
			$data['informations'] = array();
			
			foreach ($this->model_catalog_information->getInformations() as $result) {
				if ($result['bottom']) {
					$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
					);
				}
			}
			
			require_once DIR_SYSTEM . '../min/static/lib.php';
			$static_uri = "/min/static";
			
			$general_css = array(	
			'catalog/view/javascript/jquery/owl-carousel/owl.carousel.css',
			'catalog/view/theme/oct_techstore/stylesheet/font-awesome/css/font-awesome.min2.css',
			);
			
			array_unique($general_css);
			$query = "f=" . implode(',', $general_css);
			$data['minified_css_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'css');

			$data['gp_login'] = $this->url->link('extension/module/social_auth/iframeGoogleLogin');
			$data['fb_login'] = $this->url->link('extension/module/social_auth/iframeFacebookLogin');
			
			
			$general_js = array(
			'catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js',
			'catalog/view/theme/oct_techstore/js/common.js',
			'catalog/view/theme/oct_techstore/js/main.js'
			);
			
			$query = "f=" . implode(',', $general_js);
			$data['minified_js_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'js');
			

        // start: oct_page_bar
        $data['oct_page_bar_data'] = $this->config->get('oct_page_bar_data');
        $oct_page_bar_data = $this->config->get('oct_page_bar_data');
        $data['oct_page_bar'] = (isset($oct_page_bar_data['status']) && $oct_page_bar_data['status'] == 1) ? $this->load->controller('extension/module/oct_page_bar') : '';
        // end: oct_page_bar
      

        $data['oct_techstore_data'] = $oct_data = $this->config->get('oct_techstore_data');
        $this->load->language('octemplates/oct_techstore');
        $data['oct_techstore_contact_us'] = $this->language->get('oct_techstore_contact_us');
        $data['oct_techstore_categories'] = $this->language->get('oct_techstore_categories');
        $data['oct_techstore_our_contacts'] = $this->language->get('oct_techstore_our_contacts');
        $data['oct_techstore_payments'] = $this->language->get('oct_techstore_payments');
        $data['oct_techstore_powered'] = $this->language->get('oct_techstore_powered');
        $data['text_contact_us'] = $this->language->get('oct_techstore_contact_us');
        $data['text_categories'] = $this->language->get('oct_techstore_categories');
        $data['text_our_contacts'] = $this->language->get('oct_techstore_our_contacts');
        $data['text_payments'] = $this->language->get('oct_techstore_payments');
        $data['oct_powered'] = sprintf($this->language->get('oct_techstore_powered'), $this->config->get('config_name'));
        // Вывод ссылок на статьи
        $data['oct_techstore_footer_informations'] = array();
        if (isset($oct_data['foot_info_links'])) {
          foreach ($this->model_catalog_information->getInformations() as $result) {
            if (in_array($result['information_id'], $oct_data['foot_info_links'])) {
              $data['oct_techstore_footer_informations'][] = array(
                'title' => $result['title'],
                'href' => $this->url->link('information/information', 'information_id=' . $result['information_id'])
              );
            }
          }
        }
        $this->load->model('catalog/category');
        $data['oct_techstore_footer_categories'] = array();
        if (isset($oct_data['foot_cat_links'])) {
          foreach ($oct_data['foot_cat_links'] as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);
            if ($category_info) {
              $data['oct_techstore_footer_categories'][] = array(
                'name' => $category_info['name'],
                'href' => $this->url->link('product/category', 'path=' . $category_info['category_id'])
              );
            }
          }
        }
        $data['garanted_text'] = array();
        if (isset($oct_data['foot_garantedtext_show']) && $oct_data['foot_garantedtext_show'] == 'on' && isset($oct_data['foot_garantedtext']) && $oct_data['foot_garantedtext']) {
          foreach ($oct_data['foot_garantedtext'] as $key => $foot_garantedtext) {
            if ($foot_garantedtext['popup'] == 'on') {
              $foot_garantedtext_link = (isset($foot_garantedtext['description'][$this->session->data['language']])) ? str_replace('index.php?route=information/information&', 'index.php?route=information/information/agree&', $foot_garantedtext['description'][$this->session->data['language']]['link']) : '';
            } else {
              $foot_garantedtext_link = (isset($foot_garantedtext['description'][$this->session->data['language']])) ? $foot_garantedtext['description'][$this->session->data['language']]['link'] : '';
            }
            $data['garanted_text'][] = array(
              'id'    => $key,
              'icon'  => $foot_garantedtext['icon'],
              'popup' => $foot_garantedtext['popup'],
              'name'  => (isset($foot_garantedtext['description'][$this->session->data['language']])) ? $foot_garantedtext['description'][$this->session->data['language']]['name'] : '',
              'text'  => (isset($foot_garantedtext['description'][$this->session->data['language']])) ? $foot_garantedtext['description'][$this->session->data['language']]['text'] : '',
              'link'  => ($foot_garantedtext_link == "#" || empty($foot_garantedtext_link)) ? "javascript:void(0);" : $foot_garantedtext_link
            );
          }
        }
        $oct_techstore_cont_adress = $oct_data['cont_adress'];
        if (isset($oct_techstore_cont_adress[$this->session->data['language']]) && !empty($oct_techstore_cont_adress[$this->session->data['language']])) {
          $data['oct_techstore_cont_adress'] = html_entity_decode($oct_techstore_cont_adress[$this->session->data['language']], ENT_QUOTES, 'UTF-8');
        } else {
          $data['oct_techstore_cont_adress'] = false;
        }
        if (isset($oct_data['cont_phones']) && !empty($oct_data['cont_phones'])) {
          $data['oct_techstore_cont_phones'] = array_values(array_filter(explode(PHP_EOL, $oct_data['cont_phones'])));
        } else {
          $data['oct_techstore_cont_phones'] = false;
        }
        if (isset($oct_data['cont_email']) && !empty($oct_data['cont_email'])) {
          $data['oct_techstore_cont_email'] = html_entity_decode($oct_data['cont_email'], ENT_QUOTES, 'UTF-8');
        } else {
          $data['oct_techstore_cont_email'] = false;
        }
        if (isset($oct_data['cont_skype']) && !empty($oct_data['cont_skype'])) {
          $data['oct_techstore_cont_skype'] = html_entity_decode($oct_data['cont_skype'], ENT_QUOTES, 'UTF-8');
        } else {
          $data['oct_techstore_cont_skype'] = false;
        }
        $oct_techstore_cont_clock = $oct_data['cont_clock'];
        if (isset($oct_techstore_cont_clock[$this->session->data['language']]) && !empty($oct_techstore_cont_clock[$this->session->data['language']])) {
          $data['oct_techstore_cont_clock'] = array_values(array_filter(explode(PHP_EOL, $oct_techstore_cont_clock[$this->session->data['language']])));
        } else {
          $data['oct_techstore_cont_clock'] = false;
        }

        $data['ps_additional_icons'] = array();

        if (isset($oct_data['ps_additional_icons']) && $oct_data['ps_additional_icons']) {
          foreach ($oct_data['ps_additional_icons'] as $ps_additional_icon) {
            $data['ps_additional_icons'][] = array(
              'image'      => $this->model_tool_image->resize($ps_additional_icon['image'], 53, 33),
              'sort_order' => ($ps_additional_icon['sort_order']) ? $ps_additional_icon['sort_order'] : 0
            );
          }

          foreach ($data['ps_additional_icons'] as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
          }

          array_multisort($sort_order, SORT_ASC, $data['ps_additional_icons']);
        }
      
			$data['contact'] = $this->url->link('information/contact');
			$data['return'] = $this->url->link('account/return/add', '', true);
			$data['sitemap'] = $this->url->link('information/sitemap');
			$data['manufacturer'] = $this->url->link('product/manufacturer');
			$data['voucher'] = $this->url->link('account/voucher', '', true);
			$data['affiliate'] = $this->url->link('affiliate/account', '', true);
			$data['special'] = $this->url->link('product/special');
			$data['newproducts'] = $this->url->link('product/newproducts', '', true);
			$data['account'] = $this->url->link('account/account', '', true);
			$data['order'] = $this->url->link('account/order', '', true);
			$data['wishlist'] = $this->url->link('account/wishlist', '', true);
			$data['newsletter'] = $this->url->link('account/newsletter', '', true);

			$data['customer_id'] = $this->customer->isLogged();
			
			$data['search'] = $this->url->link('product/search', '', true);
			
			$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
			
			// Whos Online
			if ($this->config->get('config_customer_online')) {
				$this->load->model('tool/online');
				
				if (isset($this->request->server['REMOTE_ADDR'])) {
					$ip = $this->request->server['REMOTE_ADDR'];
					} else {
					$ip = '';
				}
				
				if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
					$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
					} else {
					$url = '';
				}
				
				if (isset($this->request->server['HTTP_REFERER'])) {
					$referer = $this->request->server['HTTP_REFERER'];
					} else {
					$referer = '';
				}
				
				$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
			}
			
			if (!empty($this->request->get['route']) && $this->request->get['route'] == 'checkout/simplecheckout'){
				
				return $this->load->view('common/footer_simple', $data);
				
				} else {
				
				return $this->load->view('common/footer', $data);
			}
		}
	}
