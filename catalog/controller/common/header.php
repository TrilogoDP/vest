<?php
class ControllerCommonHeader extends Controller {

	public function oct_minify_css() {
		$oct_data = $this->config->get('oct_techstore_data');
		$files = array(
			'view/javascript/bootstrap/css/bootstrap.min.css',
			'view/theme/oct_techstore/stylesheet/flipclock.css',
			'view/theme/oct_techstore/stylesheet/stylesheet.css',
			'view/theme/oct_techstore/stylesheet/fonts.css',
			'view/theme/oct_techstore/stylesheet/autosearch.css',
			'view/theme/oct_techstore/stylesheet/popup.css',
			'view/theme/oct_techstore/stylesheet/responsive.css',
			'view/theme/oct_techstore/js/cloud-zoom/cloud-zoom.css',
			'view/javascript/jquery/magnific/magnific-popup.css',
			'view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css',
			'view/javascript/jquery/owl-carousel/owl.carousel.css',
			'view/theme/oct_techstore/js/fancy-box/jquery.fancybox.min.css',
			'view/javascript/octemplates/oct_product_filter/nouislider.css',
			'view/javascript/octemplates/tippy/tippy.css',
			'view/javascript/octemplates/tippy/tooltipster-sideTip-shadow.min.css',
			'view/theme/oct_techstore/stylesheet/dynamic_stylesheet.css',
			'view/theme/oct_techstore/js/toast/jquery.toast.css'
		);
		$file_compress = DIR_APPLICATION . 'view/theme/oct_techstore/stylesheet/stylesheet_minify.css';
		$cache_life = 3600;
		if ($files) {
			if (!file_exists($file_compress) or (time() - filemtime($file_compress) >= $cache_life)) {
				$buffer = "";
				foreach ($files as $file) {
					$buffer .= file_get_contents(DIR_APPLICATION . $file);
				}
				$buffer .= html_entity_decode($oct_data['customcss'], ENT_QUOTES, 'UTF-8');
				$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
				$buffer = str_replace(': ', ':', $buffer);
				$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
				$buffer = str_replace(array('  ', '    ', '    '), ' ', $buffer);
				file_put_contents($file_compress, $buffer);
			}
		}
	}
	public function oct_minify_js() {
		$oct_data = $this->config->get('oct_techstore_data');
		$files = array(
			'view/javascript/jquery/jquery-2.1.1.min.js',
			'view/javascript/bootstrap/js/bootstrap.min.js',
			'view/javascript/jquery/owl-carousel/owl.carousel.min.js',
			'view/theme/oct_techstore/js/main.js',
			'view/theme/oct_techstore/js/common.js',
			'view/theme/oct_techstore/js/flexmenu.min.js',
			'view/theme/oct_techstore/js/flipclock.js',
			'view/theme/oct_techstore/js/barrating.js',
			'view/javascript/jquery/owl-carousel/owl.carousel.min.js',
			'view/theme/oct_techstore/js/input-mask.js',
			'view/theme/oct_techstore/js/fancy-box/jquery.fancybox.min.js',
			'view/theme/oct_techstore/js/cloud-zoom/cloud-zoom.1.0.2.js',
			'view/javascript/jquery/magnific/jquery.magnific-popup.min.js',
			'view/theme/oct_techstore/js/moment.js',
			'view/javascript/jquery/datetimepicker/locale/ru-ru.js',
			'view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js',
			'view/javascript/octemplates/oct_product_filter/nouislider.js',
			'view/javascript/octemplates/oct_product_filter/wNumb.js',
			'view/theme/oct_techstore/js/lazyload/jquery.lazyload.min.js',
			'view/javascript/octemplates/tippy/tippy.min.js',
			'view/theme/oct_techstore/js/toast/jquery.toast.js'
		);
		$file_compress = DIR_APPLICATION . 'view/theme/oct_techstore/js/javascript_minify.js';
		$cache_life = 3600;
		if ($files) {
			if (!file_exists($file_compress) or (time() - filemtime($file_compress) >= $cache_life)) {
				$buffer = "";
				foreach ($files as $file) {
					$buffer .= file_get_contents(DIR_APPLICATION . $file);
					$buffer .= "\r\n\r\n";
				}
             // $buffer .= html_entity_decode($oct_data['customjavascrip'], ENT_QUOTES, 'UTF-8');
				$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
				$buffer = str_replace(array("\r\n", "\r"), "\n", $buffer);
				$buffer = preg_replace('/[^\S\n]+/', ' ', $buffer);
				$buffer = str_replace(array(" \n", "\n "), "\n", $buffer);
				$buffer = preg_replace('/\n+/', "\n", $buffer);
				$buffer = str_replace(': ', ':', $buffer);
				$buffer = preg_replace(array('(( )+{)','({( )+)'), '{', $buffer);
				$buffer = preg_replace(array('(( )+})','(}( )+)','(;( )*})'), '}', $buffer);
				$buffer = preg_replace(array('(;( )+)','(( )+;)'), ';', $buffer);
				$buffer = str_replace(array(' {',' }','{ ','; '),array('{','}','{',';'), $buffer);
				file_put_contents($file_compress, $buffer);
			}
		}
	}

	public function index() {


		$data['oct_product_preorder_data'] = $this->config->get('oct_product_preorder_data');
		$data['oct_popup_call_phone_data'] = $this->config->get('oct_popup_call_phone_data');
		$data['popup_call_phone_text'] = $this->language->load('extension/module/oct_popup_call_phone');

		if ($this->request->server['SERVER_NAME'] == '78.46.237.199'){
			header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
			$this->response->redirect(HTTPS_SERVER, 301);
		}
		
			// Analytics
		$this->load->model('extension/extension');
		
		$data['analytics'] = array();
		
		$analytics = $this->model_extension_extension->getExtensions('analytics');
		
		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get($analytic['code'] . '_status'));
			}
		}

		if (defined('UA_REDIRECTION_URI')){			
			$data['UA_REDIRECTION_URI'] = UA_REDIRECTION_URI;
		}
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
		$data['title'] = $this->document->getTitle();
		$data['tc_og'] = $this->document->getTc_og();
		$data['canonical'] = $this->document->getCanonical();
		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();		
		$data['lang'] = $this->language->get('hreflang');
		$data['pixel'] = $this->document->getPixel();

		$data['fbpixel_id'] = $this->config->get('fbpixel_id');
		$data['fbpixel_status'] = $this->config->get('fbpixel_status');

		$data['direction'] = $this->language->get('direction');
		$data['text_cookie_close'] = $this->language->get('text_cookie_close');
		$data['text_cookie'] = $this->language->get('text_cookie');
		$data['base_lang'] = $this->language->get('hreflang').'/';
		$data['robots'] = $this->document->getRobots();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();

		$data['noindex'] = $this->document->isNoindex();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['noindex'] = $this->document->isNoindex();

		/*---------------- SCRIPTS -------------*/
		$general_js = array(
			'catalog/view/javascript/jquery/jquery-2.1.1.min.js',
			'catalog/view/javascript/bootstrap/js/bootstrap.min.js',
			'catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js',
			'catalog/view/javascript/lazyload/jquery.lazyload.min.js',
			'catalog/view/javascript/social_auth.js',
			'catalog/view/javascript/ocfilter/ocfilter.js',
			'catalog/view/javascript/ecommerce.functions.js'
		);

		$query = "f=" . implode(',', $general_js);
		$data['general_minified_js_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'js');

		/* !!!!!!!!!! */
		$data['incompatible_scripts'] = array(			
		);

		$t = array();
		$data['added_minified_js_uri'] = false;
		if ($data['scripts']){
			foreach ($data['scripts'] as $script) {
				if (!in_array($script, $data['incompatible_scripts']) && !in_array($script, $general_js)) {
					$t[] = $script; 
				}
			}
		}
		if ($t){
			$query = "f=" . implode(',', $t);
			$data['added_minified_js_uri'] = Minify\StaticService\build_uri($static_uri, $query, 'js');
		}

		/*---------------- END SCRIPTS -------------*/
			//MINIFICATION ENGINE W/STATIC END


		$data['name'] = $this->config->get('config_name');

		$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 135, 50);

		$this->load->language('common/header');
		$data['og_url'] = (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_SERVER : HTTP_SERVER) . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI'])-1));
		$data['og_image'] = $this->document->getOgImage();


        // oct_blog start
		if (isset($this->request->get['oct_blog_article_id'])) {
			$oct_blog_article_id = (int)$this->request->get['oct_blog_article_id'];
		} else {
			$oct_blog_article_id = 0;
		}

		$this->load->model('octemplates/blog_article');
		$article_info = $this->model_octemplates_blog_article->getArticle($oct_blog_article_id);

		$data['og_meta_description'] = "";

		if ($article_info) {
			$this->load->model('tool/image');
			$data['og_image'] = $this->model_tool_image->resize($article_info['image'], 500, 500);
			$data['og_meta_description'] = utf8_substr(strip_tags(html_entity_decode($article_info['meta_description'], ENT_QUOTES, 'UTF-8')), 0, 250);
		}

		$data['text_home'] = $this->language->get('text_home');

		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_user_login'] = $this->language->get('text_user_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_page'] = $this->language->get('text_page');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['text_enter'] = $this->language->get('text_enter');
		$data['text_customers'] = $this->language->get('text_customers');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_blog'] = $this->language->get('text_blog');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_return'] = $this->language->get('text_return');

			$data['text_special'] = $this->language->get('text_special');//***
			$data['text_newproducts'] = $this->language->get('text_newproducts');
			
			$data['home'] = $this->url->link('common/home');
			$data['wishlist'] = $this->url->link('account/wishlist', '', true);
			$data['logged'] = $this->customer->isLogged();
			$data['account'] = $this->url->link('account/account', '', true);
			$data['register'] = $this->url->link('account/register', '', true);
			$data['login'] = $this->url->link('account/login', '', true);
			$data['order'] = $this->url->link('account/order', '', true);
			$data['transaction'] = $this->url->link('account/transaction', '', true);
			$data['download'] = $this->url->link('account/download', '', true);
			$data['logout'] = $this->url->link('account/logout', '', true);
			$data['shopping_cart'] = $this->url->link('checkout/cart');
			$data['checkout'] = $this->url->link('checkout/checkout', '', true);
			$data['special'] = $this->url->link('product/special');
			$data['contact'] = $this->url->link('information/contact');
			$data['telephone'] = $this->config->get('config_telephone');
			$data['newproducts'] = $this->url->link('product/newproducts', '', true);
			
			$this->load->language('octemplates/oct_techstore');
			$data['oct_techstore_news'] = $this->language->get('oct_techstore_news');
			$data['oct_techstore_contact'] = $this->language->get('oct_techstore_contact');
			$data['oct_techstore_clock'] = $this->language->get('oct_techstore_clock');
			$data['oct_techstore_client_center'] = $this->language->get('oct_techstore_client_center');
			$data['oct_techstore_see_more'] = $this->language->get('oct_techstore_see_more');
			$data['oct_techstore_mmenu'] = $this->language->get('oct_techstore_mmenu');
			$data['oct_techstore_minfo'] = $this->language->get('oct_techstore_minfo');
			$data['oct_techstore_msearch'] = $this->language->get('oct_techstore_msearch');
			$data['oct_techstore_msearchb'] = $this->language->get('oct_techstore_msearchb');
			$data['oct_techstore_data'] = $oct_data = $this->config->get('oct_techstore_data');
			$data['oct_techstore_status'] = $this->config->get('oct_techstore_status');
			$data['link_cart'] = $this->url->link('checkout/cart');
			$data['link_wishlist'] = $this->url->link('account/wishlist', '', true);
			$data['link_compare'] = $this->url->link('product/compare');
			$data['link_login'] = $this->url->link('account/login', '', true);
			if ($oct_data['enable_minify'] == 'on') {
				$this->oct_minify_css();
				$this->oct_minify_js();
			}
			$data['text_news'] = $this->language->get('oct_techstore_news');
			$data['text_contact'] = $this->language->get('oct_techstore_contact');
			$data['text_clock'] = $this->language->get('oct_techstore_clock');
			
			$data['text_client_center'] = $this->language->get('oct_techstore_client_center');
			
			if ($this->customer->isLogged()) {
				$data['text_client_center'] = $this->customer->getFirstName();
			}
			
			$data['text_see_more'] = $this->language->get('oct_techstore_see_more');
			$data['oct_techstore_news'] = $this->url->link('octemplates/blog_category', 'cpath=6');	
			// Вывод ссылок на статьи
			$data['oct_techstore_header_information_links'] = array();
			$this->load->model('catalog/information');
			if (isset($oct_data['header_information_links'])) {
				foreach ($this->model_catalog_information->getInformations() as $result) {
					if (in_array($result['information_id'], $oct_data['header_information_links'])) {
						$data['oct_techstore_header_information_links'][] = array(
							'title' => $result['title'],
							'href' => $this->url->link('information/information', 'information_id=' . $result['information_id'])
						);
					}
				}
			}

			$data['static_information_1'] = $this->url->link('information/information', 'information_id=6');
			$data['static_information_2'] = $this->url->link('information/information', 'information_id=4');

			// Свой CSS и Javascript код
			$data['oct_techstore_customcss'] = html_entity_decode($oct_data['customcss'], ENT_QUOTES, 'UTF-8');
			$data['oct_techstore_customjavascrip'] = html_entity_decode($oct_data['customjavascrip'], ENT_QUOTES, 'UTF-8');
			$oct_techstore_cont_clock = $oct_data['cont_clock'];
			if (isset($oct_techstore_cont_clock[$this->session->data['language']]) && !empty($oct_techstore_cont_clock[$this->session->data['language']])) {
				$data['oct_techstore_cont_clock'] = array_values(array_filter(explode(PHP_EOL, $oct_techstore_cont_clock[$this->session->data['language']])));
			} else {
				$data['oct_techstore_cont_clock'] = false;
			}
			if (isset($oct_data['cont_phones']) && !empty($oct_data['cont_phones'])) {
				$data['oct_techstore_cont_phones'] = array_values(array_filter(explode(PHP_EOL, $oct_data['cont_phones'])));
			} else {
				$data['oct_techstore_cont_phones'] = false;
			}
			
			$data['is_homepage'] = ($this->request->get['route'] == 'common/home');
			
			$data['header_text_wishlist'] = $this->language->get('header_text_wishlist');
			$data['header_text_compare'] = $this->language->get('header_text_compare');
			if ($this->customer->isLogged()) {
				$data['total_wishlist'] = $this->model_account_wishlist->getTotalWishlist();
			} else {
				$data['total_wishlist'] = isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0;
			}
			$data['total_compare'] = isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0;
			
			// Menu
			$this->load->model('catalog/category');
			
			$this->load->model('catalog/product');
			
			$categories = array();

			// start: oct_megamenu
			$data['categories'] = array();

			foreach ($categories as $category) {
				if ($category['top']) {
						// Level 2
					$children_data = array();

					$children = $this->model_catalog_category->getCategories($category['category_id']);

					foreach ($children as $child) {
						$filter_data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);

							// Level 3
						$children_data_2 = array();

						$children_2 = $this->model_catalog_category->getCategories($category['category_id']);

						foreach ($children_2 as $child_2) {
							$filter_data = array(
								'filter_category_id'  => $child_2['category_id'],
								'filter_sub_category' => true
							);

							$children_data_2[] = array(
								'children' => $children_data_2,
								'name'  => $child_2['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
								'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child_2['category_id'])
							);
						}

						$children_data[] = array(
							'children' => $children_data_2,
							'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
							'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
						);
					}

						// Level 1
					$data['categories'][] = array(
						'name'     => $category['name'],
						'children' => $children_data,
						'column'   => $category['column'] ? $category['column'] : 1,
						'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
					);
				}
			}

			$data['oct_megamenu_data'] = $this->config->get('oct_megamenu_data');
			$oct_megamenu_data = $this->config->get('oct_megamenu_data');
			$data['oct_megamenu'] = (isset($oct_megamenu_data['status']) && $oct_megamenu_data['status'] == 1) ? $this->load->controller('extension/module/oct_megamenu') : '';

			$data['language']	= $this->load->controller('common/language');
			$data['currency'] 	= $this->load->controller('common/currency');
			$data['search'] 	= $this->load->controller('common/search');
			$data['cart'] 		= $this->load->controller('common/cart');

			if (!$this->customer->isLogged()){
				$data['oct_popup_login'] 		= $this->load->controller('extension/module/oct_popup_login');
			}

			$data['oct_information_bar_value'] = false;

			$oct_information_bar_status = $this->config->get('oct_information_bar_status');
			$oct_information_bar_data = $this->config->get('oct_information_bar_data');

			if (isset($oct_information_bar_data['value']) && $oct_information_bar_data['value'] && !empty($oct_information_bar_data['value']) && ($oct_information_bar_status && (!isset($this->request->cookie[$oct_information_bar_data['value']]) || !$this->request->cookie[$oct_information_bar_data['value']])) && $this->config->get('config_maintenance') == 0) {
				$data['oct_information_bar_value']						= $oct_information_bar_data['value'];
				$data['oct_information_bar_background']					= $oct_information_bar_data['background_bar'];
				$data['oct_information_bar_color_text']					= $oct_information_bar_data['color_text'];
				$data['oct_information_bar_color_url']					= $oct_information_bar_data['color_url'];
				$data['oct_information_bar_background_button']			= $oct_information_bar_data['background_button'];
				$data['oct_information_bar_background_button_hover']	= $oct_information_bar_data['background_button_hover'];
				$data['oct_information_bar_color_text_button']			= $oct_information_bar_data['color_text_button'];
				$data['oct_information_bar_color_text_button_hover']	= $oct_information_bar_data['color_text_button_hover'];
			}

			$this->load->language('octemplates/oct_techstore');
			$data['oct_close'] = $this->language->get('oct_close');
			$data['oct_policy_more'] = $this->language->get('oct_policy_more');

			$data['text_oct_information_bar'] = false;
			$data['oct_max_day'] = 365;
			$data['oct_information_bar_value'] = 'oct_information_bar';
			$data['oct_information_bar_day_now'] = date("Y-m-d H:i:s");

			$oct_information_bar_status = $this->config->get('oct_information_bar_status');
			$oct_information_bar_data = $this->config->get('oct_information_bar_data');

			if (isset($oct_information_bar_data['value']) && $oct_information_bar_data['value'] && !empty($oct_information_bar_data['value'])) {
				$data['oct_information_bar_value'] = $oct_information_bar_value = $oct_information_bar_data['value'];
			}

			if ($oct_information_bar_status && (!isset($this->request->cookie[$oct_information_bar_value]) || !$this->request->cookie[$oct_information_bar_value])) {
				if (isset($oct_information_bar_data['module_text'][(int)$this->config->get('config_language_id')]) && !empty($oct_information_bar_data['module_text'][(int)$this->config->get('config_language_id')])) {
					$data['text_oct_information_bar'] = html_entity_decode($oct_information_bar_data['module_text'][(int)$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

					if (isset($oct_information_bar_data['indormation_id']) && $oct_information_bar_data['indormation_id']) {
						$data['text_oct_information_bar'] .= ' <a target="_blank" href="'. $this->url->link('information/information', 'information_id=' . $oct_information_bar_data['indormation_id']) . '">' . $data['oct_policy_more'] . '</a>';
					}

					if (isset($oct_information_bar_data['max_day']) && $oct_information_bar_data['max_day'] && !empty($oct_information_bar_data['max_day'])) {
						$data['oct_max_day'] = (int)$oct_information_bar_data['max_day'];
					}
				}
			}

			if (isset($this->request->get['route'])) {
				if (isset($this->request->get['product_id'])) {
					$class = '-' . $this->request->get['product_id'];
				} elseif (isset($this->request->get['path'])) {
					$class = '-' . $this->request->get['path'];
				} elseif (isset($this->request->get['manufacturer_id'])) {
					$class = '-' . $this->request->get['manufacturer_id'];
				} elseif (isset($this->request->get['information_id'])) {
					$class = '-' . $this->request->get['information_id'];
				} else {
					$class = '';
				}

				$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
			} else {
				$data['class'] = 'common-home';
			}

			if (!empty($this->request->get['route']) && $this->request->get['route'] == 'checkout/simplecheckout'){
				$this->language->load('checkout/simplecheckout');
				$data['text_if_you_have_questions_call']     = $this->language->get('text_if_you_have_questions_call');
				return $this->load->view('common/header_simple', $data);

			} else {
				return $this->load->view('common/header', $data);
			}
		}
	}
