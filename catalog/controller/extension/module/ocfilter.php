<?php
	
	class ControllerExtensionModuleOCFilter extends Controller {
		protected $registry;
		protected $data = array();
		
		
		public function __construct($registry) {
			parent::__construct($registry);
			
			if ($this->registry->has('ocfilter')) {
				$this->data = $this->registry->get('ocfilter')->data;
				
				return;
			}
			
			$this->load->language('extension/module/ocfilter');
			
			$this->load->config('ocfilter');
			$this->load->helper('ocfilter');
			
			$this->load->model('catalog/ocfilter');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			
			
			// Decode URL
			$this->decode();
			
			if (!$this->path) {
				return;
			}
			
			$parts = explode('_', $this->path);
			
			$this->category_id = (int)end($parts);
			
			if (isset($this->request->get['filter_ocfilter'])) {
				$this->params = cleanParamsString($this->request->get['filter_ocfilter'], $this->config);							
				
				if ($this->params) {
					$options_get = decodeParamsFromString($this->params, $this->config);
					
					$this->options_get = $options_get;
					
					if ($this->config->get('ocfilter_show_price') && !empty($options_get['p'])) {
						$range = getRangeParts(end($options_get['p']));
						
						if (isset($range['from']) && isset($range['to'])) {
							$this->min_price_get = $range['from'];
							$this->max_price_get = $range['to'];
						}
					}
					
					// if (!$this->page_info) {
					//    $this->document->setNoindex(true);
					// }
					// webfun - индексируем только первый выбранный фильтр
					if (!$this->page_info) {
						$values_count = 0;
						
						foreach ($this->options_get as $option_id => $values) {
							$values_count += count($values);
						}
						
						if ($values_count >= 2) {
							$this->document->setNoindex(true);
						}
					}
					// webfun end
				}
			}
			
			// Get values counter
			$filter_data = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' => $this->params
			);
			
			$this->counters = $this->model_catalog_ocfilter->getCounters($filter_data);
			
			if ($this->config->get('ocfilter_show_price')) {
				$filter_data['filter_ocfilter'] = $this->cancelOptionParams('p');
				
				$this->product_prices = $this->model_catalog_ocfilter->getProductPrices($filter_data);
				
				if ($this->product_prices) {
					$this->min_price = $this->currency->format(floor($this->product_prices['min']), $this->session->data['currency'], '', false);
					$this->max_price = $this->currency->format(floor($this->product_prices['max']), $this->session->data['currency'], '', false);
					
					if ($this->min_price == $this->min_price_get && $this->max_price == $this->max_price_get){
						$this->params = $this->cancelOptionParams('p');
						$options_get = decodeParamsFromString($this->params, $this->config);					
						$this->options_get = $options_get;
					}
				}
			}
			
			$this->registry->set('ocfilter', $this);
		}
		
		// Array access
		public function __get($key) {
			if (isset($this->data[$key])) {
				return $this->data[$key];
				} else if ($this->registry->has($key)) {
				return $this->registry->get($key);
				} else {
				return null;
			}
		}
		
		public function __set($key, $value) {
			$this->data[$key] = $value;
		}
		
		// Empty method to prevent execution of index()
		public function initialise() {
			
		}
		
		public function index($settings = array()) {
			if (!$this->category_id) {
				return;
			}
			
			$this->load->language('extension/module/ocfilter');
			
			// if ($this->config->get('ocfilter_show_price') && $this->min_price < $this->max_price - 1) {
			//   $data['show_price'] = 1;
			// } else {
			//   $data['show_price'] = 0;
			// }
			
			// webfun
			if ($this->config->get('ocfilter_show_price')) {
				$data['show_price'] = 1;
				} else {
				$data['show_price'] = 0;
			}
			// webfun end
			
			$data['heading_title'] = $this->language->get('heading_title');
			$data['lang'] = $this->language->get('code');
			if ($this->min_price_get && $this->min_price_get < $this->min_price) {
				$this->min_price = $this->min_price_get;
			}
			
			if ($this->max_price_get && $this->max_price_get > $this->max_price) {
				$this->max_price = $this->max_price_get;
			}
			
			$data['options']              = $this->getOCFilterOptions();
			$data['min_price']            = $this->min_price;
			$data['max_price']            = $this->max_price;
			$data['min_price_get']        = $this->min_price_get ? $this->min_price_get : $this->min_price;
			$data['max_price_get']        = $this->max_price_get ? $this->max_price_get : $this->max_price;
			$data['path']                 = $this->path;
			
			// webfun
			if ($data['min_price'] >= $data['max_price']) {
				$data['max_price'] = $data['max_price'] + 1;
			}
			if ($data['min_price_get'] >= $data['max_price_get']) {
				$data['max_price_get'] = $data['max_price_get'] + 1;
			}
			// webfun end
			
			$data['link']                 = str_replace('&amp;', '&', $this->link());
			// $data['link'] = 'http://test.vest.in.ua/accessories-for-phones/chehly/filter/?sort=p.sort_order&order=ASC&limit=18';
			
			$data['params']               = $this->params;
			
			$data['index']                = $this->config->get('ocfilter_url_index');
			$data['show_counter']         = $this->config->get('ocfilter_show_counter');
			$data['search_button']        = $this->config->get('ocfilter_search_button');
			$data['show_values_limit']    = $this->config->get('ocfilter_show_values_limit');
			$data['manual_price']         = $this->config->get('ocfilter_manual_price');
			
			$data['text_show_all']        = $this->language->get('text_show_all');
			$data['text_hide']            = $this->language->get('text_hide');
			$data['button_select']        = $this->language->get('button_select');
			$data['text_load']            = $this->language->get('text_load');
			$data['text_price']           = $this->language->get('text_price');
			$data['text_any']             = $this->language->get('text_any');
			$data['text_cancel_all']      = $this->language->get('text_cancel_all');
			
			$data['symbol_left']          = $this->currency->getSymbolLeft($this->session->data['currency']);
			$data['symbol_right']         = $this->currency->getSymbolRight($this->session->data['currency']);
			
			$data['show_options'] = !empty($this->params);
			
			if ($this->config->get('ocfilter_show_selected') && $this->options_get) {
				$data['selecteds'] = $this->getSelectedOptions();
				} else {
				$data['selecteds'] = array();
			}
			
			if ($this->config->get('ocfilter_show_options_limit') && $this->config->get('ocfilter_show_options_limit') < count($data['options'])) {
				$data['show_options_limit'] = $this->config->get('ocfilter_show_options_limit');
				} else {
				$data['show_options_limit'] = false;
			}
			
			$this->document->addStyle('catalog/view/javascript/ocfilter/nouislider.min.css');
			$this->document->addStyle('catalog/view/theme/default/stylesheet/ocfilter/ocfilter.css');
			
			$this->document->addScript('catalog/view/javascript/ocfilter/nouislider.min.js');
			//	$this->document->addScript('catalog/view/javascript/ocfilter/ocfilter.js');
			
			return $this->load->view('extension/module/ocfilter/module', $data);
		}
		
		protected function getOCFilterOptions() {
			if (!is_null($this->options)) {
				return $this->options;
			}
			
			$options = array();
			
			// Manufacturers filtering
			if ($this->config->get('ocfilter_manufacturer')) {
				$results = $this->model_catalog_ocfilter->getManufacturersByCategoryId($this->category_id, true);
				
				if ($results) {
					$options[] = array(
					'option_id'   => 'm',
					'name'        => $this->language->get('text_manufacturer'),
					'description' => $this->language->get('text_manufacturer_description'),
					'type'        => $this->config->get('ocfilter_manufacturer_type'),
					'values'      => $results
					);
				}
			}
			
			// Stock status filtering
			if ($this->config->get('ocfilter_stock_status')) {
				if ($this->config->get('ocfilter_stock_status_method') == 'stock_status_id') {
					$results = $this->model_catalog_ocfilter->getStockStatuses();
					
					$options['stock'] = array(
					'option_id'   => 's',
					'name'        => $this->language->get('text_stock'),
					'description' => $this->language->get('text_stock_description'),
					'type'        => $this->config->get('ocfilter_stock_status_type'),
					'values'      => $results
					);
					} else if ($this->config->get('ocfilter_stock_status_method') == 'quantity') {
					$options['stock'] = array(
					'option_id'   => 's',
					'name'        => $this->language->get('text_stock'),
					'description' => $this->language->get('text_stock_description'),
					'type'        => ($this->config->get('ocfilter_stock_out_value') ? 'radio' : 'checkbox'),
					'values'      => array(
					array(
					'value_id'    => 'in',
					'name'        => $this->language->get('text_in_stock')
					)
					)
					);
					
					if ($this->config->get('ocfilter_stock_out_value')) {
						$options['stock']['values'][] = array(
						'value_id'    => 'out',
						'name'        => $this->language->get('text_out_of_stock')
						);
					}
				}
			}
						
			$results = $this->model_catalog_ocfilter->getOCFilterOptionsByCategoryId($this->category_id);			
			$results1 = $this->model_catalog_ocfilter->webfunGetOCFilterOptionsBySort();
			
			if ($results && $results1) {				
				$options = array_merge($results1, $results, $options);
			}
			
			$options_data = array();
			
			$index = 0;
			
			foreach ($options as $key => $option) {
				if ($option['type'] == 'select') {
					$option['type'] = 'radio';
					$option['selectbox'] = true;
				}
				
				$this_option = isset($this->options_get[$option['option_id']]);
				
				$values = array();
				
				if ($option['type'] != 'slide' && $option['type'] != 'slide_dual') {
					foreach ($option['values'] as $value) {
						$this_value = isset($this->options_get[$option['option_id']]) && in_array($value['value_id'], $this->options_get[$option['option_id']]);
						
						$count = 0;
						
						if (isset($this->counters[$option['option_id'] . $value['value_id']])) {
							if ($this_option && $option['type'] == 'checkbox') {
								$count = '+' . $this->counters[$option['option_id'] . $value['value_id']];
								} else {
								$count = $this->counters[$option['option_id'] . $value['value_id']];
							}
						}
						
						// var_dump($count . '  ');
						
						// if ($count || !$this->config->get('ocfilter_hide_empty_values')) {
						// if ($count || !$this->config->get('ocfilter_hide_empty_values') || (isset($option['sort_order']) && $option['sort_order'] == '-2')) { // webfun
						if (isset($option['image']) && $option['image'] && isset($value['image']) && $value['image'] && file_exists(DIR_IMAGE . $value['image'])) {
							$image = $this->model_tool_image->resize($value['image'], 19, 19);
							} else {
							$image = false;
						}
						
						$params = $this->getValueParams($option['option_id'], $value['value_id'], $option['type']);
						
						$values[] = array(
						'value_id' => $value['value_id'],
						'id'       => $option['option_id'] . $value['value_id'],
						'name'     => html_entity_decode($value['name'] . (isset($option['postfix']) ? $option['postfix'] : ''), ENT_QUOTES, 'UTF-8'),
						'keyword'  => html_entity_decode((isset($value['keyword']) ? $value['keyword'] : $value['value_id']), ENT_QUOTES, 'UTF-8'),
						'color'    => ((isset($value['color']) && $value['color']) ? $value['color'] : '#FFFFFF'),
						'image'    => $image,
						'border'    => !empty($value['border'])?$value['border']:false,
						'params'   => $params,
						'count'    => $count,
						'selected' => $this_value
						);
						// }
					}								
					
					if (!$values) {
						continue;
					}
					} else {
					$range = $this->model_catalog_ocfilter->getSliderRange($option['option_id'], array(
					'filter_category_id' => $this->category_id,
					'filter_ocfilter' => $this->cancelOptionParams($option['option_id']),
					));
					
					if ($range['min'] == $range['max']) {
						continue;
					}
					
					$option['slide_value_min'] = $range['min'];
					$option['slide_value_max'] = $range['max'];
				}
				
				if ($option['type'] == 'radio') {
					$params = $this->cancelOptionParams($option['option_id']);
					
					if (isset($this->counters[$option['option_id'] . 'all'])) {
						$count = $this->counters[$option['option_id'] . 'all'];
						} else {
						$count = 1;
					}
					
					array_unshift($values, array(
					'value_id' => $option['option_id'],
					'id'       => 'cancel-' . $option['option_id'],
					'name'     => $this->language->get('text_any'),
					'params'   => $params,
					'count'    => $count,
					'selected' => !$this_option
					));
				}
				
				$option_data = array(
				'option_id'           => $option['option_id'],
				'index'               => ++$index,
				'name'                => html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8'),
				'selectbox'           => (isset($option['selectbox']) ? $option['selectbox'] : false),
				'color'               => (isset($option['color']) ? $option['color'] : false),
				'image'               => (isset($option['image']) ? $option['image'] : false),
				'keyword'             => (isset($option['keyword']) ? $option['keyword'] : $option['option_id']),
				'postfix'             => (isset($option['postfix']) ? html_entity_decode($option['postfix'], ENT_QUOTES, 'UTF-8') : ''),
				'description'         => (isset($option['description']) ? $option['description'] : ''),
				'slide_value_min'     => (isset($option['slide_value_min']) ? $option['slide_value_min'] : 0),
				'slide_value_max'     => (isset($option['slide_value_max']) ? $option['slide_value_max'] : 0),
				'slide_value_min_get' => (isset($option['slide_value_min']) ? $option['slide_value_min'] : 0),
				'slide_value_max_get' => (isset($option['slide_value_max']) ? $option['slide_value_max'] : 0),
				'type'                => $option['type'],
				'selected'            => $this_option,
				'values'              => $values
				// 'sort_order'          => $option['sort_order'] // webfun
				);
				// webfun
				if (isset($option['sort_order'])) {
					$option_data['sort_order'] = $option['sort_order'];
				}
				// webfun end
				
				if (($option['type'] == 'slide' || $option['type'] == 'slide_dual') && isset($this->options_get[$option['option_id']][0])) {
					$range = getRangeParts($this->options_get[$option['option_id']][0]);
					
					if (isset($range['from']) && isset($range['to'])) {
						$option_data['slide_value_min_get'] = $range['from'];
						$option_data['slide_value_max_get'] = $range['to'];
						
						// For getSelectedOptions
						array_unshift($option_data['values'], array(
						'value_id' => $range['from'] . '-' . $range['to'],
						'name'     => $this->language->get('text_from') . $range['from'] . $this->language->get('text_to') . $range['to'] . $option['postfix']
						));
					}
				}
				
				$options_data[] = $option_data;
			} // End options each
			
			$this->options = $options_data;
			
			return $options_data;
		}
		
		protected function getValueParams($option_id, $value_id, $type = 'checkbox') {
			$decoded_params = decodeParamsFromString($this->params, $this->config);
			
			if ($type == 'checkbox') {
				if (isset($decoded_params[$option_id])) {
					if (false !== $key = array_search($value_id, $decoded_params[$option_id])) {
						unset($decoded_params[$option_id][$key]);
						} else {
						$decoded_params[$option_id][] = $value_id;
					}
					} else {
					$decoded_params[$option_id] = array($value_id);
				}
				} else if ($type == 'select' || $type == 'radio') {
				if (isset($decoded_params[$option_id])) {
					unset($decoded_params[$option_id]);
				}
				
				$decoded_params[$option_id] = array($value_id);
			}
			
			return encodeParamsToString($decoded_params, $this->config);
		}
		
		protected function cancelOptionParams($option_id) {
			if ($this->params) {
				$params = decodeParamsFromString($this->params, $this->config);
				
				if (isset($params[$option_id])) {
					unset($params[$option_id]);
				}
				
				return encodeParamsToString($params, $this->config);
			}
		}
		
		protected function getSelectedOptions() {
			$selected_options = array();
			
			$category_options = $this->getOCFilterOptions();
			
			if ($this->min_price_get && $this->max_price_get) {
				$category_options[] = array(
				'option_id' => 'p',
				'name'      => $this->language->get('text_price'),
				'type'      => 'select',
				'selected'  => isset($this->options_get['p']),
				'values'    => array(array(
				'value_id'  => $this->min_price_get . '-' . $this->max_price_get,
				'name'      => $this->language->get('text_from') . $this->currency->getSymbolLeft($this->session->data['currency']) . $this->min_price_get . $this->language->get('text_to') . $this->max_price_get . $this->currency->getSymbolRight($this->session->data['currency'])
				))
				);
			}
			
			foreach ($category_options as $option) {
				if (!$option['selected']) {
					continue;
				}
				
				$option_id = $option['option_id'];
				
				$values = array();
				
				foreach ($option['values'] as $value) {
					if (!in_array($value['value_id'], $this->options_get[$option_id])) {
						continue;
					}
					
					$params = '';
					
					if (count($this->options_get) > 1 || count($this->options_get[$option_id]) > 1) {
						if ($option['type'] == 'radio' || $option['type'] == 'select' || $option['type'] == 'slide' || $option['type'] == 'slide_dual') {
							$params .= $this->cancelOptionParams($option_id);
							} else {
							$params .= $value['params'];
						}
					}
					
					$name = html_entity_decode($value['name'], ENT_QUOTES, 'UTF-8');
					
					$values[] = array(
					'name' => $name,
					'id'   => $option_id . $value['value_id'],
					'href' => $this->link($params),
					);
				}
				
				$selected_options[$option_id] = array(
				'name'      => $option['name'],
				'values'    => $values
				);
			}
			
			return $selected_options;
		}
		
		public function decode($return = false) {	
			
			if ($return && $return['string'] && $return['category_id']){
				$this->request->get['_route_'] = $return['string'];
				$this->request->get['path'] = $return['category_id'];
			}
			
			if (isset($this->request->get['path'])) {
				$this->path = $this->request->get['path'];
			}
			
			if (!isset($this->request->get['_route_'])) {				
				return;				
			}
			
			$_route_ = $this->request->get['_route_'];					
			
			$_route_ = ltrim($_route_, '/');
			$keywords = explode('/', $_route_);			
			
			// remove any empty arrays from trailing
			if (utf8_strlen(end($keywords)) == 0) {
				array_pop($keywords);
			}
			
			$ignored = array();
			
			$page_keywords = array();
			
			// Get category path
			if (!$this->path) {
				$path_info = $this->model_catalog_ocfilter->decodeCategory($keywords);
				
				if ($path_info && $path_info->path) {
					$this->path = $path_info->path;
					
					$ignored = $path_info->keywords;
				}
			}
			
			if (!$this->path) {
				return;
			}
			
			$parts = explode('_', $this->path);
			
			$category_id = (int)end($parts);
			
			// Ignore language
			$key = array_search($this->session->data['language'], $keywords);
			//$key = array_search('ua', $keywords);				
			
			if (false !== $key) {
				$ignored[] = $keywords[$key];
			}
			
			// Get SEO Page
			foreach ($keywords as $key => $keyword) {
				if (in_array($keyword, $ignored)) {
					continue;
				}						
				
				$page_info = $this->model_catalog_ocfilter->decodePage($category_id, $keyword);							
				
				if ($page_info) {
					$this->page_info = $page_info;
					
					$keywords = explode('/', $this->page_info['params']);
					
					// remove any empty arrays from trailing
					if (utf8_strlen(end($keywords)) == 0) {
						array_pop($keywords);
					}
					
					break;
				}
			}
			
			$params = array();
			
			// Special filters
			foreach ($keywords as $key => $keyword) {
				if (in_array($keyword, $ignored)) {
					continue;
				}
				
				if ($keyword == 'price') {
					unset($keywords[$key++]);
					
					$page_keywords[] = $keyword;
					
					if (isset($keywords[$key]) && isRange($keywords[$key])) {
						$params['p'] = array($keywords[$key]);
						
						$page_keywords[] = $keywords[$key];
						
						unset($keywords[$key]);
					}
					} else if ($keyword == 'sklad' && $this->config->get('ocfilter_stock_status_method') == 'quantity') {
					unset($keywords[$key++]);
					
					$page_keywords[] = $keyword;
					
					if (isset($keywords[$key]) && ($keywords[$key] == 'in' || $keywords[$key] == 'out')) {
						if (!isset($params['s'])) {
							$params['s'] = array();
						}
						
						$params['s'][$keywords[$key]] = $keywords[$key];
						
						$page_keywords[] = $keywords[$key];
						
						unset($keywords[$key]);
					}
				}
			}
			
			$current = '';
			
			foreach ($keywords as $key => $keyword) {
				if (in_array($keyword, $ignored)) {
					continue;
				}
				
				$founded = 0;
				
				// Values
				if ($current == 's' && isID($keyword) && $this->config->get('ocfilter_stock_status_method') == 'stock_status_id') {
					$params['s'][$keyword] = $keyword;
					
					$founded = 1;
					} else if ($current) {
					$value_id = $this->model_catalog_ocfilter->decodeValue($keyword, $current);
					
					if ($value_id) {
						$params[$current][$value_id] = $value_id;
						
						$founded = 1;
						} else if (isRange($keyword)) { // If Slider
						$params[$current][$keyword] = $keyword;
						
						$founded = 2;
					}
				}
				
				if ($founded > 0) {
					$page_keywords[] = $keyword;
					
					if ($founded > 1) {
						$current = '';
					}
					
					unset($keywords[$key]);
					
					continue;
				}
				
				// Options
				if ($keyword == 'sklad' && $this->config->get('ocfilter_stock_status_method') == 'stock_status_id') {
					$params['s'] = array();
					
					$current = 's';
					
					$page_keywords[] = $keyword;
					
					unset($keywords[$key]);
					} else if (!isRange($keyword)) {
					$option_id = $this->model_catalog_ocfilter->decodeOption($keyword, $category_id);
					
					if ($option_id) {
						$params[$option_id] = array();
						
						$current = $option_id;
						
						$page_keywords[] = $keyword;
						
						unset($keywords[$key]);
					}
				}
			}
			
			// Manufacturer
			foreach ($keywords as $key => $keyword) {
				$manufacturer_id = $this->model_catalog_ocfilter->decodeManufacturer($keyword);
				
				if ($manufacturer_id) {
					if (!isset($params['m'])) {
						$params['m'] = array();
					}
					
					$params['m'][$manufacturer_id] = $manufacturer_id;
					
					$page_keywords[] = $keyword;
					
					unset($keywords[$key]);
				}
			}
			
			// Add category SEO keywords to _route_
			if ($this->page_info) {
				$path = $this->model_catalog_ocfilter->getCategorySeoPathByCategoryId($this->page_info['category_id']);
				
				if ($path) {
					$parts = explode('/', $path);
					
					foreach (array_reverse($parts) as $part) {
						array_unshift($keywords, $part);
					}
				}
			}
			
			if (!$this->page_info && $page_keywords) {
				$this->page_info = $this->model_catalog_ocfilter->getPage($category_id, implode('/', $page_keywords));
			}
			
			if ($keywords) {
				$this->request->get['_route_'] = implode('/', $keywords);
			}
			
			if ($return){
				return $params;
			}
			
			if ($params) {
				$this->request->get['filter_ocfilter'] = encodeParamsToString($params, $this->config);
				
				if (isset($this->request->get['route'])) {
					unset($this->request->get['route']);
					} else {
					$this->request->get['route'] = 'product/category';
				}
			}
		}
		
		public function rewrite($link) {
			$url_info = parse_url(str_replace('&amp;', '&', $link));
			
			if (!isset($url_info['query'])) {
				return $link;
			}
			
			$data = array();
			
			parse_str($url_info['query'], $data);
			
			if (!isset($data['filter_ocfilter'])) {
				return $link;
			}
			
			$params = decodeParamsFromString($data['filter_ocfilter'], $this->config);
			
			unset($data['filter_ocfilter']);
			
			$path = '';
			
			foreach ($params as $option_id => $values) {
				if ($option_id == 'p') {
					$path .= '/price';
					} else if ($option_id == 's') {
					$path .= '/sklad';
					} else if ($option_id != 'm') {
					$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "ocfilter_option WHERE option_id = '" . (int)$option_id . "'");
					
					if ($query->num_rows && $query->row['keyword']) {
						$path .= '/' . $query->row['keyword'];
						} else {
						$path .= '/' . $option_id;
					}
				}
				
				foreach ($values as $value_id) {
					$query = false;
					
					if ($option_id == 'm') {
						$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE `query` = 'manufacturer_id=" . (int)$value_id . "'");
						} else if (isID($value_id)) {
						$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "ocfilter_option_value WHERE value_id = '" . $this->db->escape((string)$value_id) . "'");
					}
					
					if ($query && $query->num_rows && $query->row['keyword']) {
						$path .= '/' . $query->row['keyword'];
						} else {
						$path .= '/' . $value_id;
					}
				}
			}
			
			if ($path) {
				$page_path = ltrim($path, '/');
				
				$page_info = $this->model_catalog_ocfilter->getPage($this->category_id, $page_path);
				
				if ($page_info && $page_info['keyword']) {
					$path = '/' . $page_info['keyword'];
				}
			}
			
			$rewrite = $url_info['scheme'] . '://' . $url_info['host'];
			
			if (isset($url_info['port'])) {
				$rewrite .= ':' . $url_info['port'];
			}
			
			if (isset($url_info['path'])) {
				$rewrite .= str_replace('/index.php', '', $url_info['path']);
				} else {
				$rewrite .= '/index.php';
			}
			
			if ($path) {
				$rewrite = rtrim($rewrite, '/') . $path;
				
				if ($this->config->has('config_seo_url_type') && $this->config->get('config_seo_url_type') == 'seo_pro') {
					$rewrite .= '/';
				}
			}
			
			$query = '';
			
			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
				}
				
				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}
			
			$rewrite .= $query;
			
			return $rewrite;
		}
		
		public function getPageInfo() {
			return $this->page_info;
		}
		
		public function getSelectedsFilterTitleOnlyValue() {
			
			$filter_title = '';
			$selecteds = $this->getSelectedOptions();
			
			foreach ($selecteds as $option_id => $option) {
				if ($filter_title) {
					$filter_title .= ', ';
				}
				
				
				$values_name  = '';
				
				foreach ($option['values'] as $value) {
					if ($values_name) {
						$values_name .= ', ';
					}
					
					$values_name .= $value['name'];
				}
				
				if ($values_name) {
					$filter_title .= $values_name;
				}
			}
			
			return $filter_title;
		}
		
		public function getSelectedsFilterTitle() {
			$filter_title = '';
			
			$selecteds = $this->getSelectedOptions();
			
			foreach ($selecteds as $option_id => $option) {
				if ($filter_title) {
					$filter_title .= ', ';
				}
				
				if ($option_id == 'm') {
					$values_name  = '';
					
					foreach ($option['values'] as $value) {
						if ($values_name) {
							$values_name .= ', ';
						}
						
						$values_name .= $value['name'];
					}
					
					if ($values_name) {
						$filter_title .= $values_name;
					}
					} else if ($option_id == 'p') {
					$price = array_shift($option['values']);
					
					$filter_title .= $price['name'];
					} else if ($option_id == 's') {
					if ($this->config->get('ocfilter_stock_status_method') == 'quantity') {
						$stock_status = array_shift($option['values']);
						
						if ($stock_status['name'] == 'in') {
							$filter_title .= 'в наличии';
							} else if ($stock_status['name'] == 'out') {
							$filter_title .= 'нет в наличии';
						}
						} else {
						$values_name  = '';
						
						foreach ($option['values'] as $value) {
							if ($values_name) {
								$values_name .= ', ';
							}
							
							$values_name .= $value['name'];
						}
						
						if ($values_name) {
							$filter_title .= $values_name;
						}
					}
					} else {
					$values_name  = '';
					
					foreach ($option['values'] as $value) {
						if ($values_name) {
							$values_name .= ', ';
						}
						
						$values_name .= $value['name'];
					}
					
					if ($values_name) {
						$filter_title .= $option['name'] . ' ' . $values_name;
					}
				}
			}
			
			return $filter_title;
		}
		
		protected function link($filter_ocfilter = '') {
			$url = '';
			
			if ($this->path) {
				$url .= '&path=' . (string)$this->path;
			}
			
			if ($filter_ocfilter) {
				$url .= '&filter_ocfilter=' . (string)$filter_ocfilter;
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . (string)$this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . (string)$this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . (int)$this->request->get['limit'];
			}
			
			return $this->url->link('product/category', $url);
		}
		
		public function callback() {
			if (!$this->path) {
				return;
			}
			
			// webfun - ajax products update
			if (isset($this->request->get['webfun_sort']) && $this->request->get['webfun_sort'] != 'false') {
				$webfun_sort = $this->request->get['webfun_sort'];
				} else {
				// $webfun_sort = 'p.sort_order';
				$webfun_sort = 'rating';
			}
			
			if (isset($this->request->get['webfun_order']) && $this->request->get['webfun_order'] != 'false') {
				$webfun_order = $this->request->get['webfun_order'];
				} else {
				// $webfun_order = 'ASC';
				$webfun_order = 'DESC';
			}
			
			if (isset($this->request->get['webfun_limit']) && $this->request->get['webfun_limit'] != 'false') {
				$webfun_limit = (int) $this->request->get['webfun_limit'];
				} else {
				$webfun_limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
			}
			
			// if (isset($this->request->get['webfun_page']) && $this->request->get['webfun_page'] != 'false') {
			//   $page = (int) $this->request->get['webfun_page'];
			// } else {
			$page = 1;
			// }
			// webfun end
			
			$this->load->language('extension/module/ocfilter');
			$data['text_sku'] = $this->language->get('text_sku');
			
			$json = array();
			
			if (isset($this->request->get['option_id'])) {
				$option_id = $this->request->get['option_id'];
				} else {
				$option_id = 0;
			}
			
			$filter_data = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' => $this->params,
			'limit' => 1,
			);
			
			if ($this->config->get('ocfilter_sub_category')) {
				$filter_data['filter_sub_category'] = true;
			}
			
			$total_products = $this->model_catalog_product->getTotalProducts($filter_data);
			
			$json['total'] = $total_products;
			$json['text_total'] = declOfNum($total_products, array(
			$this->language->get('button_show_total_1'),
			$this->language->get('button_show_total_2'),
			$this->language->get('button_show_total_3')
			));
			
			$json['values'] = array();
			$json['sliders'] = array();
			
			if ($this->config->get('ocfilter_show_price') && $option_id != 'p') {
				$_filter_data = $filter_data;
				
				$_filter_data['filter_ocfilter'] = $this->cancelOptionParams('p');
				
				$product_prices = $this->model_catalog_ocfilter->getProductPrices($_filter_data);
				
				if ($product_prices) {
					$json['sliders']['p'] = array(
					'min' => $this->currency->format(floor($product_prices['min']), $this->session->data['currency'], '', false),
					'max' => $this->currency->format(ceil($product_prices['max']), $this->session->data['currency'], '', false),
					);
				}
			}
			
			$options = $this->getOCFilterOptions();
			
			foreach ($options as $option) {
				if ($option['type'] == 'slide' || $option['type'] == 'slide_dual') {
					if ($option['option_id'] != $option_id) {
						$json['sliders'][$option['option_id']] = $this->model_catalog_ocfilter->getSliderRange($option['option_id'], $filter_data);
					}
					
					continue;
				}
				
				if ($option['type'] == 'select' || $option['type'] == 'radio') {
					$params = $this->cancelOptionParams($option['option_id']);
					
					$json['values']['cancel-' . $option['option_id']] = array(
					't' => 1,
					'p' => $params,
					's' => false
					);
				}
				
				foreach ($option['values'] as $value) {
					$json['values'][$value['id']] = array(
					't' => $value['count'],
					'p' => $value['params'],
					's' => isset($this->options_get[$option['option_id']][$value['value_id']])
					);
				}
			}

			$param_url = '';

			if (isset($this->request->get['webfun_sort']) && $this->request->get['webfun_sort'] != 'false') {
				$param_url .= '&sort=' . $this->request->get['webfun_sort'];
			}

			if (isset($this->request->get['webfun_order']) && $this->request->get['webfun_order'] != 'false') {
				$param_url .= '&order=' . $this->request->get['webfun_order'];
			}

			if (isset($this->request->get['webfun_limit']) && $this->request->get['webfun_limit'] != 'false') {
				$param_url .= '&limit=' . $this->request->get['webfun_limit'];
			}

			if (isset($this->request->get['webfun_page']) && $this->request->get['webfun_page'] > 1) {
				$param_url .= '&page=' . $this->request->get['webfun_page'];
			}

			if ($param_url[0] == '&'){
				$param_url[0] = '?';
			}
			
			$json['href'] = str_replace('&amp;', '&', $this->link($this->params)) . $param_url;
			
			// webfun - ajax products update
			$json['webfun_products'] = '';
			$webfun_products = array();
			
			$this->load->model('tool/image');
			$this->load->model('tool/mikrof');
			$this->load->model('catalog/category');
			$this->load->language('product/category');
			
			// $page = 1;
			
			$filter_exclude_categories = false;
			if (empty($this->request->get['filter_ocfilter']) || !$this->params){
				$filter_exclude_categories = $this->model_catalog_category->getCategoryExcluded($this->category_id);
			}
			
			$filter_data = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' 	 => $this->params,
			'filter_not_archive' => true,
			'filter_exclude_categories' => $filter_exclude_categories,
			'sort'               => $webfun_sort,
			'order'              => $webfun_order,
			'start'              => ($page - 1) * $webfun_limit,
			'limit'              => $webfun_limit
			);
			
			if ($this->config->get('ocfilter_sub_category')) {
				$filter_data['filter_sub_category'] = true;
			}
			
			$results = $this->model_catalog_product->getProducts($filter_data);


			//getBestSellers For current 
			if (count($results) > 10 && $page == 1){
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
					foreach ($this->model_catalog_product->getProductAttributes($result['product_id']) as $attribute_group) {
						foreach ($attribute_group['attribute'] as $attribute) {
							if (isset($oct_advanced_attributes_settings_data['allowed_attributes']) && (in_array($attribute['attribute_id'], $oct_advanced_attributes_settings_data['allowed_attributes']))) {
								$oct_attributes[] = array(
								'name' => $attribute['name'],
								'text' => $attribute['text']
								);
							}
						}
					}
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
				
				$webfun_products[] = array(
				'saving'      			=> round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
				'model'       			=> $result['model'],
				'stock'       			=> $stock,
				'oct_product_stickers' 	=> $oct_product_stickers,
				'oct_options' 		=> $oct_options,
				'oct_attributes' 	=> $oct_attributes,
				'product_id'  	=> $result['product_id'],
				'sku'       	=> $result['sku'],
				'thumb'       	=> $image,
				'name'        	=> $result['name'],
				'description' 	=> utf8_substr(strip_tags(html_entity_decode($this->model_tool_mikrof->formatStringdisplay($result['description']), ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
				'quantity'       => $result['quantity'], 
				'product_preorder_text' => $product_preorder_text,
				'product_preorder_status' => $product_preorder_status,
				'price'       => $price,
				'special'     => $special,
				'action_stickers' => $result['action_stickers'],
				'tax'         => $tax,
				'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
				'rating'      => $rating,
				'gift_teaser'		  => $this->load->controller('extension/module/giftteaser/checkGiftsForCurrentProduct', array('product_id' => $result['product_id'], 'return_data' => true)),	
				'href'        => $this->url->link('product/product', 'path=' . $this->path . '&product_id=' . $result['product_id'])
				);
			}
			
			$oct_popup_view_data = $this->config->get('oct_popup_view_data');
			$button_popup_view = $this->language->get('button_popup_view');
			
			$oct_data = $this->config->get('oct_techstore_data');
			if (isset($oct_data['oct_lazyload']) && $oct_data['oct_lazyload'] == 1) {
				$oct_lazyload = $oct_data['oct_lazyload'];
			}
			
			foreach ($webfun_products as $product) {
				$json['webfun_products'] .= '<div class="product-layout product-grid col-md-4 col-sm-6 col-xs-6"><div class="product-thumb';
				if (isset($product['product_preorder_status']) && $product['product_preorder_status'] != 1 && $product['quantity'] <= 0) {
					$json['webfun_products'] .= ' no_quantity';
				}
				$json['webfun_products'] .= '" title="' . $product['name'] . '"><div class="image">';
				if (isset($oct_popup_view_data['status']) && $oct_popup_view_data['status'] && $product['quantity'] > 0) {
					$json['webfun_products'] .= '<div class="quick-view"><a onclick="get_oct_popup_product_view(\'' . $product['product_id'] . '\');">' . $button_popup_view . '</a></div>';
				}
				if ($product['special']) {
					$json['webfun_products'] .= '<div class="oct-discount-box"><div class="oct-discount-item">-' . $product['saving'] . '%</div></div>';
				}



				if ($product['gift_teaser']) {
					$json['webfun_products'] .= '<div class="oct-discount-box">													
					<span class="label label-danger" style="font-size: 14px">' .  $this->language->get('text_gift') . '</span>	
					</div>';
				}

				if ($product['action_stickers']) {
					$json['webfun_products'] .= '<div class="oct-discount-box_wrap">';
					foreach ($product['action_stickers'] as $action_sticker) {
						$json['webfun_products'] .= '<div class="oct-discount-box">';
						$json['webfun_products'] .= '<span class="label label-danger" style="font-size: 14px;';
						if ($action_sticker['label_color']) { 
							$json['webfun_products'] .= 'color:' . $action_sticker['text_color'] . ' !important;';
						} 
						if ($action_sticker['label_bg']) {
							$json['webfun_products'] .= 'background-color:' . $action_sticker['label_bg'] . '!important;';
						}
						$json['webfun_products'] .= '">';
						$json['webfun_products'] .= $action_sticker['label'];
						$json['webfun_products'] .= '</span>';
						$json['webfun_products'] .= '	</div>';
					}
					$json['webfun_products'] .= '</div>';																						
				}

				if ($product['oct_product_stickers']) {
					$json['webfun_products'] .= '<div class="oct-sticker-box">';
					foreach ($product['oct_product_stickers'] as $product_sticker) {
						$json['webfun_products'] .= '<div class="oct-sticker-item" style="color: ' . $product_sticker['color'] . '; background: ' . $product_sticker['background'] . ';">' . $product_sticker['text'] . '</div>';
					}
					$json['webfun_products'] .= '</div>';
				}
				if (isset($oct_lazyload) && $oct_lazyload) {
					$json['webfun_products'] .= '<a href="' . $product['href'] . '" class="lazy_link"><img data-original="' . $product['thumb'] . '" src="' . $oct_lazyload_image . '" class="img-responsive lazy" alt="' . $product['name'] . '" /></a>';
					} else {
					$json['webfun_products'] .= '<a href="' . $product['href'] . '"><img src="' . $product['thumb'] . '" class="img-responsive" alt="' . $product['name'] . '" /></a>';
				}
				$json['webfun_products'] .= '</div><div><div class="caption"><p class="cat-model">';
				if(strlen($product['sku'])) {
					$json['webfun_products'] .= $this->language->get('text_sku') . ' <span>' . $product['sku'] . '</span>';
				}
				$json['webfun_products'] .= '</p><h4><a href="' . $product['href'] . '">' . $product['name'] . '</a></h4>';
				if ($product['rating']) {
					$json['webfun_products'] .= '<div class="rating">';
					for ($i = 1; $i <= 5; $i++) {
						if ($product['rating'] < $i) {
							$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>';
							} else {
							$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>';
						}
					}
					$json['webfun_products'] .= '</div>';
					} else {
					$json['webfun_products'] .= '<div class="rating">';
					for ($i = 1; $i <= 5; $i++) {
						$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star-o" aria-hidden="true"></i></span>';
					}
					$json['webfun_products'] .= '</div>';
				}
				$json['webfun_products'] .= '<hr><div class="cat-box-effect"><div class="cat-box-effect-inner">';
				if ($product['price']) {
					$json['webfun_products'] .= '<p class="price">';
					if (!$product['special']) {
						$json['webfun_products'] .= '<span class="common-price">' . $product['price'] . '</span>';
						} else {
						$json['webfun_products'] .= '<span class="price-new">' . $product['special'] . '</span> <span class="price-old">' . $product['price'] . '</span>';
					}
					$json['webfun_products'] .= '</p>';
				}
				$json['webfun_products'] .= '<div class="cart">';
				if ($product['quantity'] > 0) {
					$json['webfun_products'] .= '<a class="button-cart oct-button" title="' . $this->language->get('button_cart') . '" onclick="get_oct_popup_add_to_cart(\'' . $product['product_id'] . '\', \'1\');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">' . $this->language->get('button_cart') . '</span></a>';
					} else {
					$json['webfun_products'] .= '<a class="button-cart out-of-stock-button oct-button" ';
					if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) {
						$json['webfun_products'] .= 'onclick="get_oct_product_preorder(\'' . $product['product_id'] . '\');"';
					}
					$json['webfun_products'] .= '><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">' . $product['product_preorder_text'] . '</span></a>';
				}
				$json['webfun_products'] .= '<a onclick="get_oct_popup_add_to_wishlist(\'' . $product['product_id'] . '\');" title="' . $this->language->get('button_wishlist') . '" class="wishlist oct-button"><i class="fa fa-heart" aria-hidden="true"></i></a><a onclick="get_oct_popup_add_to_compare(\'' . $product['product_id'] . '\');" title="' . $this->language->get('button_compare') . '" class="compare oct-button"><i class="fa fa-sliders" aria-hidden="true"></i></a></div></div></div><p class="oct-product-stock"><span class="hidden-xs">' . $this->language->get('text_stock') . '</span> <span>' . $product['stock'] . '</span></p><div class="oct-additional-info"><p class="oct-product-desc">' . $product['description'] . '</p>';
				if (isset($product['oct_options']) && $product['oct_options']) {
					$json['webfun_products'] .= '<div class="cat-options">';
					foreach ($product['oct_options'] as $option) {
						if ($option['type'] == 'radio') {
							$json['webfun_products'] .= '<div class="form-group"><label class="control-label">' . $option['name'] . '</label>';
							if ($option['product_option_value']) {
								foreach ($option['product_option_value'] as $product_option_value) {
									if ($product_option_value['image']) {
										$json['webfun_products'] .= '<div class="radio"><img src="' . $product_option_value['image'] . '" alt="' . $product_option_value['name'] . '" class="img-thumbnail" title="' . $product_option_value['name'] . '" />
										</div>';
										} else {
										$json['webfun_products'] .= '<div class="radio"><label class="not-selected">' . $product_option_value['name'] . '</label>
										</div>';
									}
								}
							}
							$json['webfun_products'] .= '</div>';
							} else {
							$json['webfun_products'] .= '<div class="form-group size-box"><label class="control-label">' . $option['name'] . '</label>';
							if ($option['product_option_value']) {
								foreach ($option['product_option_value'] as $product_option_value) {
									$json['webfun_products'] .= '<div class="radio"><label class="not-selected">' . $product_option_value['name'] . '</label></div>';
								}
							}
							$json['webfun_products'] .= '</div>';
						}
					}
					$json['webfun_products'] .= '</div>';
				}
				if (isset($product['oct_attributes']) && $product['oct_attributes']) {
					$json['webfun_products'] .= '<div class="cat-options">';
					foreach ($product['oct_attributes'] as $attribute) {
						$json['webfun_products'] .= '<div class="form-group size-box"><label class="control-label">' . $attribute['name'] . '</label>: <span>' . $attribute['text'] . '</span></div>';
					}
					$json['webfun_products'] .= '</div>';
				}
				$json['webfun_products'] .= '</div></div></div></div></div>';
			}
			
			// $json['webfun_html'] = file_get_contents($json['href']);
			
			// sorts
			if (strripos($json['href'], '?') !== false) {
				$parts = explode('?', $json['href']);
				$url = $parts[0];
				} else {
				$url = $json['href'];
			}
			
			$sorts = array();
			$json['sort'] = '';
			
			// $sorts[] = array(
			//   'text'  => $this->language->get('text_default'),
			//   'value' => 'p.sort_order-ASC',
			//   'href'  => $url . '?sort=p.sort_order&order=ASC&limit=' . $webfun_limit
			// );
			
			$sorts[] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $url . '?sort=pd.name&order=ASC&limit=' . $webfun_limit
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $url . '?sort=pd.name&order=DESC&limit=' . $webfun_limit
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $url . '?sort=p.price&order=ASC&limit=' . $webfun_limit
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $url . '?sort=p.price&order=DESC&limit=' . $webfun_limit
			);
			
			if ($this->config->get('config_review_status')) {
				$sorts[] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $url . '?sort=rating&order=DESC&limit=' . $webfun_limit
				);
				
				$sorts[] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $url . '?sort=rating&order=ASC&limit=' . $webfun_limit
				);
			}
			
			$sorts[] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => $url . '?sort=p.model&order=ASC&limit=' . $webfun_limit
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $url . '?sort=pd.name&order=ASC&limit=' . $webfun_limit
			);
			
			foreach ($sorts as $sorts) {
				if ($sorts['value'] == $webfun_sort . '-' . $webfun_order) {
					$json['sort'] .= '<option value="' . $sorts['href'] . '" selected="selected">' . $sorts['text'] . '</option>';
					} else {
					$json['sort'] .= '<option value="' . $sorts['href'] . '">' . $sorts['text'] . '</option>';
				}
			}
			
			// limits
			if (strripos($json['href'], 'limit=') !== false) {
				$json['limit'] = '';
				$limitsHtml = array();
				$parts = explode('limit=', $json['href']);
				$url = $parts[0];
				
				$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));
				
				sort($limits);
				
				foreach($limits as $value) {
					$limitsHtml[] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $url . 'limit=' . $value
					);
				}
				
				foreach ($limitsHtml as $limits) {
					if ($limits['value'] == $webfun_limit) {
						$json['limit'] .= '<option value="' . $limits['href'] . '" selected="selected">' . $limits['text'] . '</option>';
						} else {
						$json['limit'] .= '<option value="' . $limits['href'] . '">' . $limits['text'] . '</option>';
					}
				}
			}
			
			// selected options
			if ($this->config->get('ocfilter_show_selected') && $this->options_get) {
				$json['selecteds'] = "<script>
				function displayPreloader(){
				$('.ocfilter').append('<div class=\"webfun_ocfilter_preloader\"></div>');
				$('#res-products .row').eq(0).append('<div class=\"webfun_ocfilter_preloader\"></div>');
				}
				</script>";
				
				$selecteds = $this->getSelectedOptions();
				if ($selecteds) {
					$json['selecteds'] .= '<div class="list-group-item selected-options">';
					foreach ($selecteds as $key => $option) {
						$json['selecteds'] .= '<div class="ocfilter-option"><span>' . $option['name'] . ':</span>';
						foreach ($option['values'] as $value) {
							$json['selecteds'] .= '<button type="button" class="btn btn-xs btn-danger" style="padding: 1px 4px;" data-wf-option-id="' . $key . '" data-wf-option-id-value="' . $value['id'] . '"><i class="fa fa-times"></i> ' . $value['name'] . '</button>';
						}
						$json['selecteds'] .= '</div>';
					}
					
					$json['selecteds'] .= '<button type="button" class="btn btn-block btn-danger" style="border-radius: 0;" onclick="displayPreloader(); location=\'' . str_replace('&amp;', '&', $this->link()) . '\'"><i class="fa fa-times-circle"></i> ' . $this->language->get('text_cancel_all') . '</button></div>';
				}
			}
			
			// pagination
			if (strripos($json['href'], '?') !== false) {
				$parts = explode('?', $json['href']);
				$url = $parts[0];
				} else {
				$url = $json['href'];
			}
			
			$url .= '?sort=' . $webfun_sort . '&order=' . $webfun_order . '&limit=' . $webfun_limit;
			
			$pagination = new Pagination();
			$pagination->total = $json['total'];
			$pagination->page = $page;
			$pagination->limit = $webfun_limit;
			$pagination->url = $url . '&page={page}';
			
			$json['pagination'] = $pagination->render();
			
			// new h1
			$this->load->model('catalog/category');
			
			$category_info = $this->model_catalog_category->getCategory($this->category_id);
			if ($category_info) {
				if ($category_info['meta_h1']) {
					$new_h1 = $category_info['meta_h1'];
					} else {
					$new_h1 = $category_info['name'];
				}
				
				$filter_title = $this->getSelectedsFilterTitle();
				
				if ($filter_title) {
					if (false !== strpos($new_h1, '{filter}')) {
						$new_h1 = trim(str_replace('{filter}', $filter_title, $new_h1));
						} else {
						$new_h1 .= ' ' . $filter_title;
					}
					
					$json['heading_title'] = $new_h1;
					} else {
					$json['heading_title'] = trim(str_replace('{filter}', '', $new_h1));
				}
			}
			
			// webfun end
			$hack_data = $data;
			$hack_data['webfun_products'] 	= $webfun_products;
			$hack_data['oct_data'] 			= $oct_data;
			$hack_data['oct_popup_view_data']= $oct_popup_view_data;
			$hack_data = $hack_data + $this->load->language('extension/module/ocfilter');

			$json['webfun_products'] = $this->load->view('product/ocfilter_callback', ($hack_data));

			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
				
		public function callback1() {
			if (!$this->path) {
				return;
			}
			
			// if (isset($_COOKIE['WF_CALLBACK1']) &&)
			
			// webfun - ajax products update
			if (isset($this->request->get['webfun_sort']) && $this->request->get['webfun_sort'] != 'false') {
				$webfun_sort = $this->request->get['webfun_sort'];
				} else {
				// $webfun_sort = 'p.sort_order';
				$webfun_sort = 'rating';
			}
			
			if (isset($this->request->get['webfun_order']) && $this->request->get['webfun_order'] != 'false') {
				$webfun_order = $this->request->get['webfun_order'];
				} else {
				// $webfun_order = 'ASC';
				$webfun_order = 'DESC';
			}
			
			if (isset($this->request->get['webfun_limit']) && $this->request->get['webfun_limit'] != 'false') {
				$webfun_limit = (int) $this->request->get['webfun_limit'];
				} else {
				$webfun_limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
			}
			$webfun_limit_config = $this->config->get($this->config->get('config_theme') . '_product_limit');
			
			if (isset($this->request->get['webfun_page']) && $this->request->get['webfun_page'] != 'false') {
				$page = (int) $this->request->get['webfun_page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['webfun_data_limit']) && $this->request->get['webfun_data_limit'] != 'false') {
				$webfun_data_limit = (int) $this->request->get['webfun_data_limit'];
				} else {
				$webfun_data_limit = 2;
			}
			// webfun end
			
			$this->load->language('extension/module/ocfilter');
			
			$json = array();
			
			// $json['wenfun_load_product_btn'] = '<div class="webfun_load_more_product" data-limit="2">' . $this->language->get('text_webfun_load_more_product') . '</div>';
			
			if (isset($this->request->get['option_id'])) {
				$option_id = $this->request->get['option_id'];
				} else {
				$option_id = 0;
			}
			
			$filter_data = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' 	=> $this->params,
			'start' 			=> ($page - 1) * $webfun_limit,
			'limit' 			=> $webfun_limit
			);
			
			if ($this->config->get('ocfilter_sub_category')) {
				$filter_data['filter_sub_category'] = true;
			}
			
			// $filter_data_config = array(
			//   'filter_category_id' => $this->category_id,
			//   'filter_ocfilter' => $this->params,
			//   'start' => ($page + $webfun_data_limit - 2) * $webfun_limit_config,
			//   'limit' => $webfun_limit_config
			// );
			
			// if ($this->config->get('ocfilter_sub_category')) {
			//   $filter_data_config['filter_sub_category'] = true;
			// }
			
			$total_products = $this->model_catalog_product->getTotalProducts($filter_data);
			// $total_products_config = $this->model_catalog_product->getTotalProducts($filter_data_config);
			
			$json['total'] = $total_products;
			// $json['total_config'] = $total_products_config;
			$json['text_total'] = declOfNum($total_products, array(
			$this->language->get('button_show_total_1'),
			$this->language->get('button_show_total_2'),
			$this->language->get('button_show_total_3')
			));
			
			$json['values'] = array();
			$json['sliders'] = array();
			
			if ($this->config->get('ocfilter_show_price') && $option_id != 'p') {
				$_filter_data = $filter_data;
				
				$_filter_data['filter_ocfilter'] = $this->cancelOptionParams('p');
				
				$product_prices = $this->model_catalog_ocfilter->getProductPrices($_filter_data);
				
				if ($product_prices) {
					$json['sliders']['p'] = array(
					'min' => $this->currency->format(floor($product_prices['min']), $this->session->data['currency'], '', false),
					'max' => $this->currency->format(ceil($product_prices['max']), $this->session->data['currency'], '', false),
					);
				}
			}
			
			$options = $this->getOCFilterOptions();
			
			foreach ($options as $option) {
				if ($option['type'] == 'slide' || $option['type'] == 'slide_dual') {
					if ($option['option_id'] != $option_id) {
						$json['sliders'][$option['option_id']] = $this->model_catalog_ocfilter->getSliderRange($option['option_id'], $filter_data);
					}
					
					continue;
				}
				
				if ($option['type'] == 'select' || $option['type'] == 'radio') {
					$params = $this->cancelOptionParams($option['option_id']);
					
					$json['values']['cancel-' . $option['option_id']] = array(
					't' => 1,
					'p' => $params,
					's' => false
					);
				}
				
				foreach ($option['values'] as $value) {
					$json['values'][$value['id']] = array(
					't' => $value['count'],
					'p' => $value['params'],
					's' => isset($this->options_get[$option['option_id']][$value['value_id']])
					);
				}
			}
			
			$param_url = '';

			if (isset($this->request->get['webfun_sort']) && $this->request->get['webfun_sort'] != 'false') {
				$param_url .= '&sort=' . $this->request->get['webfun_sort'];
			}

			if (isset($this->request->get['webfun_order']) && $this->request->get['webfun_order'] != 'false') {
				$param_url .= '&order=' . $this->request->get['webfun_order'];
			}

			if (isset($this->request->get['webfun_limit']) && $this->request->get['webfun_limit'] != 'false') {
				$param_url .= '&limit=' . $this->request->get['webfun_limit'];
			}

			if (isset($this->request->get['webfun_page']) && $this->request->get['webfun_page'] > 1) {
				$param_url .= '&page=' . $this->request->get['webfun_page'];
			}

			if ($param_url[0] == '&'){
				$param_url[0] = '?';
			}
			
			$json['href'] = str_replace('&amp;', '&', $this->link($this->params)) . $param_url;
			
			if ($page > 1) {
				$json['href'] .= '&page=' . $page;
			}
			
			// webfun - ajax products update
			$json['webfun_products'] = '';
			$webfun_products = array();
			
			$this->load->model('tool/image');
			$this->load->model('tool/mikrof');
			$this->load->language('product/category');
			$this->load->model('catalog/category');
			
			// $page = 1;
			
			$filter_data = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' => $this->params,
			'sort'               => $webfun_sort,
			'order'              => $webfun_order,
			'start'              => ($page - 1) * $webfun_limit,
			'limit'              => $webfun_limit
			);
			
			if ($this->config->get('ocfilter_sub_category')) {
				$filter_data['filter_sub_category'] = true;
			}
			
			$filter_exclude_categories = false;
			if (empty($this->request->get['filter_ocfilter']) || !$this->params){
				$filter_exclude_categories = $this->model_catalog_category->getCategoryExcluded($this->category_id);
			}
			
			$filter_data_config = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' 	 => $this->params,
			'filter_exclude_categories' => $filter_exclude_categories,
			'sort'               => $webfun_sort,
			'order'              => $webfun_order,
			'start'              => ($page + $webfun_data_limit - 2) * $webfun_limit_config,
			'limit'              => $webfun_limit_config
			);
			
			if ($this->config->get('ocfilter_sub_category')) {
				$filter_data_config['filter_sub_category'] = true;
			}
			
			$results = $this->model_catalog_product->getProducts($filter_data_config);


			//getBestSellers For current 
				if (count($results) > 10 && $page == 1 && (!$webfun_data_limit || $webfun_data_limit < 2)){
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
					foreach ($this->model_catalog_product->getProductAttributes($result['product_id']) as $attribute_group) {
						foreach ($attribute_group['attribute'] as $attribute) {
							if (isset($oct_advanced_attributes_settings_data['allowed_attributes']) && (in_array($attribute['attribute_id'], $oct_advanced_attributes_settings_data['allowed_attributes']))) {
								$oct_attributes[] = array(
								'name' => $attribute['name'],
								'text' => $attribute['text']
								);
							}
						}
					}
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
				
				$webfun_products[] = array(
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
				'sku'       => $result['sku'],//***
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($this->model_tool_mikrof->formatStringdisplay($result['description']), ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
				'quantity'       => $result['quantity'], 
				'product_preorder_text' => $product_preorder_text,
				'product_preorder_status' => $product_preorder_status,
				'price'       => $price,
				'special'     => $special,
				'action_stickers' => $result['action_stickers'],
				'tax'         => $tax,
				'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
				'rating'      => $rating,
				'href'        => $this->url->link('product/product', 'path=' . $this->path . '&product_id=' . $result['product_id'])
				);
			}
			
			$oct_popup_view_data = $this->config->get('oct_popup_view_data');
			$button_popup_view = $this->language->get('button_popup_view');
			
			$oct_data = $this->config->get('oct_techstore_data');
			if (isset($oct_data['oct_lazyload']) && $oct_data['oct_lazyload'] == 1) {
				$oct_lazyload = $oct_data['oct_lazyload'];
			}
			
			foreach ($webfun_products as $product) {
				$json['webfun_products'] .= '<div class="product-layout product-grid col-md-4 col-sm-6 col-xs-6"><div class="product-thumb';
				if (isset($product['product_preorder_status']) && $product['product_preorder_status'] != 1 && $product['quantity'] <= 0) {
					$json['webfun_products'] .= ' no_quantity';
				}
				$json['webfun_products'] .= '" title="' . $product['name'] . '"><div class="image">';
				if (isset($oct_popup_view_data['status']) && $oct_popup_view_data['status'] && $product['quantity'] > 0) {
					$json['webfun_products'] .= '<div class="quick-view"><a onclick="get_oct_popup_product_view(\'' . $product['product_id'] . '\');">' . $button_popup_view . '</a></div>';
				}
				if ($product['special']) {
					$json['webfun_products'] .= '<div class="oct-discount-box"><div class="oct-discount-item">-' . $product['saving'] . '%</div></div>';
				}

				if ($product['action_stickers']) {
					$json['webfun_products'] .= '<div class="oct-discount-box_wrap">';
					foreach ($product['action_stickers'] as $action_sticker) {
						$json['webfun_products'] .= '<div class="oct-discount-box">';
						$json['webfun_products'] .= '<span class="label label-danger" style="font-size: 14px;';
						if ($action_sticker['label_color']) { 
							$json['webfun_products'] .= 'color:' . $action_sticker['text_color'] . ' !important;';
						} 
						if ($action_sticker['label_bg']) {
							$json['webfun_products'] .= 'background-color:' . $action_sticker['label_bg'] . '!important;';
						}
						$json['webfun_products'] .= '">';
						$json['webfun_products'] .= $action_sticker['label'];
						$json['webfun_products'] .= '</span>';
						$json['webfun_products'] .= '	</div>';
					}
					$json['webfun_products'] .= '</div>';																						
				}

				if ($product['oct_product_stickers']) {
					$json['webfun_products'] .= '<div class="oct-sticker-box">';
					foreach ($product['oct_product_stickers'] as $product_sticker) {
						$json['webfun_products'] .= '<div class="oct-sticker-item" style="color: ' . $product_sticker['color'] . '; background: ' . $product_sticker['background'] . ';">' . $product_sticker['text'] . '</div>';
					}
					$json['webfun_products'] .= '</div>';
				}
				if (isset($oct_lazyload) && $oct_lazyload) {
					$json['webfun_products'] .= '<a href="' . $product['href'] . '" class="lazy_link"><img data-original="' . $product['thumb'] . '" src="' . $oct_lazyload_image . '" class="img-responsive lazy" alt="' . $product['name'] . '" /></a>';
					} else {
					$json['webfun_products'] .= '<a href="' . $product['href'] . '"><img src="' . $product['thumb'] . '" class="img-responsive" alt="' . $product['name'] . '" /></a>';
				}
				$json['webfun_products'] .= '</div><div><div class="caption"><p class="cat-model">';
				if(strlen($product['sku'])) {
					$json['webfun_products'] .= $this->language->get('text_sku') . ' <span>' . $product['sku'] . '</span>';
				}
				$json['webfun_products'] .= '</p><h4><a href="' . $product['href'] . '">' . $product['name'] . '</a></h4>';
				if ($product['rating']) {
					$json['webfun_products'] .= '<div class="rating">';
					for ($i = 1; $i <= 5; $i++) {
						if ($product['rating'] < $i) {
							$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>';
							} else {
							$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>';
						}
					}
					$json['webfun_products'] .= '</div>';
					} else {
					$json['webfun_products'] .= '<div class="rating">';
					for ($i = 1; $i <= 5; $i++) {
						$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star-o" aria-hidden="true"></i></span>';
					}
					$json['webfun_products'] .= '</div>';
				}
				$json['webfun_products'] .= '<hr><div class="cat-box-effect"><div class="cat-box-effect-inner">';
				if ($product['price']) {
					$json['webfun_products'] .= '<p class="price">';
					if (!$product['special']) {
						$json['webfun_products'] .= '<span class="common-price">' . $product['price'] . '</span>';
						} else {
						$json['webfun_products'] .= '<span class="price-new">' . $product['special'] . '</span> <span class="price-old">' . $product['price'] . '</span>';
					}
					$json['webfun_products'] .= '</p>';
				}
				$json['webfun_products'] .= '<div class="cart">';
				if ($product['quantity'] > 0) {
					$json['webfun_products'] .= '<a class="button-cart oct-button" title="' . $this->language->get('button_cart') . '" onclick="get_oct_popup_add_to_cart(\'' . $product['product_id'] . '\', \'1\');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">' . $this->language->get('button_cart') . '</span></a>';
					} else {
					$json['webfun_products'] .= '<a class="button-cart out-of-stock-button oct-button" ';
					if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) {
						$json['webfun_products'] .= 'onclick="get_oct_product_preorder(\'' . $product['product_id'] . '\');"';
					}
					$json['webfun_products'] .= '><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">' . $product['product_preorder_text'] . '</span></a>';
				}
				$json['webfun_products'] .= '<a onclick="get_oct_popup_add_to_wishlist(\'' . $product['product_id'] . '\');" title="' . $this->language->get('button_wishlist') . '" class="wishlist oct-button"><i class="fa fa-heart" aria-hidden="true"></i></a><a onclick="get_oct_popup_add_to_compare(\'' . $product['product_id'] . '\');" title="' . $this->language->get('button_compare') . '" class="compare oct-button"><i class="fa fa-sliders" aria-hidden="true"></i></a></div></div></div><p class="oct-product-stock"><span class="hidden-xs">' . $this->language->get('text_stock') . '</span> <span>' . $product['stock'] . '</span></p><div class="oct-additional-info"><p class="oct-product-desc">' . $product['description'] . '</p>';
				if (isset($product['oct_options']) && $product['oct_options']) {
					$json['webfun_products'] .= '<div class="cat-options">';
					foreach ($product['oct_options'] as $option) {
						if ($option['type'] == 'radio') {
							$json['webfun_products'] .= '<div class="form-group"><label class="control-label">' . $option['name'] . '</label><br/>';
							if ($option['product_option_value']) {
								foreach ($option['product_option_value'] as $product_option_value) {
									if ($product_option_value['image']) {
										$json['webfun_products'] .= '<div class="radio"><img src="' . $product_option_value['image'] . '" alt="' . $product_option_value['name'] . '" class="img-thumbnail" title="' . $product_option_value['name'] . '" />
										</div>';
										} else {
										$json['webfun_products'] .= '<div class="radio"><label class="not-selected">' . $product_option_value['name'] . '</label>
										</div>';
									}
								}
							}
							$json['webfun_products'] .= '</div>';
							} else {
							$json['webfun_products'] .= '<div class="form-group size-box"><label class="control-label">' . $option['name'] . '</label><br/>';
							if ($option['product_option_value']) {
								foreach ($option['product_option_value'] as $product_option_value) {
									$json['webfun_products'] .= '<div class="radio"><label class="not-selected">' . $product_option_value['name'] . '</label></div>';
								}
							}
							$json['webfun_products'] .= '</div>';
						}
					}
					$json['webfun_products'] .= '</div>';
				}
				if (isset($product['oct_attributes']) && $product['oct_attributes']) {
					$json['webfun_products'] .= '<div class="cat-options">';
					foreach ($product['oct_attributes'] as $attribute) {
						$json['webfun_products'] .= '<div class="form-group size-box"><label class="control-label">' . $attribute['name'] . '</label><br/><span>' . $attribute['text'] . '</span></div>';
					}
					$json['webfun_products'] .= '</div>';
				}
				$json['webfun_products'] .= '</div></div></div></div></div>';
			}
						
			
			// selected options
			if ($this->config->get('ocfilter_show_selected') && $this->options_get) {
				$json['selecteds'] = '';
				
				$selecteds = $this->getSelectedOptions();
				if ($selecteds) {
					$json['selecteds'] .= '<div class="list-group-item selected-options">';
					foreach ($selecteds as $key => $option) {
						$json['selecteds'] .= '<div class="ocfilter-option"><span>' . $option['name'] . ':</span>';
						foreach ($option['values'] as $value) {
							$json['selecteds'] .= '<button type="button" class="btn btn-xs btn-danger" style="padding: 1px 4px;" data-wf-option-id="' . $key . '" data-wf-option-id-value="' . $value['id'] . '"><i class="fa fa-times"></i> ' . $value['name'] . '</button>';
						}
						$json['selecteds'] .= '</div>';
					}
					
					$json['selecteds'] .= '<button type="button" class="btn btn-block btn-danger wf-ocfilter-cancel-all" style="border-radius: 0;"><i class="fa fa-times-circle"></i> ' . $this->language->get('text_cancel_all') . '</button></div>';
				}
			}
			
			// pagination
			if (strripos($json['href'], '?') !== false) {
				$parts = explode('?', $json['href']);
				$url = $parts[0];
				} else {
				$url = $json['href'];
			}
			
			$url .= '?sort=' . $webfun_sort . '&order=' . $webfun_order . '&limit=' . $webfun_limit_config;
			if ($webfun_data_limit > 3) {
				$url .= '&page=' . ($webfun_data_limit - 2 + 1);
			}
			
			$pagination = new Pagination();
			$pagination->total = $json['total'];
			// $pagination->page = $page;
			$pagination->page = $page + $webfun_data_limit - 2 + 1;
			$pagination->limit = $webfun_limit;
			$pagination->url = $url . '&page={page}';
			
			$json['pagination'] = $pagination->render();

			// webfun end
			$hack_data = $data;
			$hack_data['webfun_products'] 		= $webfun_products;
			$hack_data['oct_data'] 				= $oct_data;
			$hack_data['oct_popup_view_data']	= $oct_popup_view_data;
			$hack_data = $hack_data + $this->load->language('extension/module/ocfilter');

			$json['webfun_products'] = $this->load->view('product/ocfilter_callback', ($hack_data));

			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		
		public function callback2() {
			if (!$this->path) {
				return;
			}
			
			if (isset($this->request->get['webfun_sort']) && $this->request->get['webfun_sort'] != 'false') {
				$webfun_sort = $this->request->get['webfun_sort'];
				} else {			
				$webfun_sort = 'rating';
			}
			
			if (isset($this->request->get['webfun_order']) && $this->request->get['webfun_order'] != 'false') {
				$webfun_order = $this->request->get['webfun_order'];
				} else {
				// $webfun_order = 'ASC';
				$webfun_order = 'DESC';
			}
			
			if (isset($this->request->get['webfun_limit']) && $this->request->get['webfun_limit'] != 'false') {
				$webfun_limit = (int) $this->request->get['webfun_limit'];
				} else {
				$webfun_limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
			}
			
			if (isset($this->request->get['webfun_page']) && $this->request->get['webfun_page'] != 'false') {
				$page = (int) $this->request->get['webfun_page'];
				} else {
				$page = 1;
			}
			
			$this->load->language('extension/module/ocfilter');
			
			$json = array();
			
			if (isset($this->request->get['option_id'])) {
				$option_id = $this->request->get['option_id'];
				} else {
				$option_id = 0;
			}
			
			$filter_data = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' => $this->params,
			'start' => ($page - 1) * $webfun_limit,
			'limit' => $webfun_limit
			);
			
			if ($this->config->get('ocfilter_sub_category')) {
				$filter_data['filter_sub_category'] = true;
			}
			
			$this->load->model('catalog/category');
			$filter_exclude_categories = false;
			if (empty($this->request->get['filter_ocfilter']) || !$this->params){
				$filter_exclude_categories = $this->model_catalog_category->getCategoryExcluded($this->category_id);
				$filter_data['filter_exclude_categories'] = $filter_exclude_categories;
			}
			
			$total_products = $this->model_catalog_product->getTotalProducts($filter_data);
			
			$json['total'] = $total_products;
			$json['text_total'] = declOfNum($total_products, array(
			$this->language->get('button_show_total_1'),
			$this->language->get('button_show_total_2'),
			$this->language->get('button_show_total_3')
			));
			
			$json['values'] = array();
			$json['sliders'] = array();
			
			if ($this->config->get('ocfilter_show_price') && $option_id != 'p') {
				$_filter_data = $filter_data;
				
				$_filter_data['filter_ocfilter'] = $this->cancelOptionParams('p');
				
				$product_prices = $this->model_catalog_ocfilter->getProductPrices($_filter_data);
				
				if ($product_prices) {
					$json['sliders']['p'] = array(
					'min' => $this->currency->format(floor($product_prices['min']), $this->session->data['currency'], '', false),
					'max' => $this->currency->format(ceil($product_prices['max']), $this->session->data['currency'], '', false),
					);
				}
			}
			
			$options = $this->getOCFilterOptions();
			
			foreach ($options as $option) {
				if ($option['type'] == 'slide' || $option['type'] == 'slide_dual') {
					if ($option['option_id'] != $option_id) {
						$json['sliders'][$option['option_id']] = $this->model_catalog_ocfilter->getSliderRange($option['option_id'], $filter_data);
					}
					
					continue;
				}
				
				if ($option['type'] == 'select' || $option['type'] == 'radio') {
					$params = $this->cancelOptionParams($option['option_id']);
					
					$json['values']['cancel-' . $option['option_id']] = array(
					't' => 1,
					'p' => $params,
					's' => false
					);
				}
				
				foreach ($option['values'] as $value) {
					$json['values'][$value['id']] = array(
					't' => $value['count'],
					'p' => $value['params'],
					's' => isset($this->options_get[$option['option_id']][$value['value_id']])
					);
				}
			}

			$param_url = '';

			if (isset($this->request->get['webfun_sort']) && $this->request->get['webfun_sort'] != 'false') {
				$param_url .= '&sort=' . $this->request->get['webfun_sort'];
			}

			if (isset($this->request->get['webfun_order']) && $this->request->get['webfun_order'] != 'false') {
				$param_url .= '&order=' . $this->request->get['webfun_order'];
			}

			if (isset($this->request->get['webfun_limit']) && $this->request->get['webfun_limit'] != 'false') {
				$param_url .= '&limit=' . $this->request->get['webfun_limit'];
			}

			if (isset($this->request->get['webfun_page']) && $this->request->get['webfun_page'] > 1) {
				$param_url .= '&page=' . $this->request->get['webfun_page'];
			}

			if ($param_url[0] == '&'){
				$param_url[0] = '?';
			}
			
			$json['href'] = str_replace('&amp;', '&', $this->link($this->params)) . $param_url;
						
			$json['webfun_products'] = '';
			$webfun_products = array();
			
			$this->load->model('tool/image');
			$this->load->model('tool/mikrof');
			$this->load->model('catalog/category');
			$this->load->language('product/category');
				
			$filter_data = array(
			'filter_category_id' => $this->category_id,
			'filter_ocfilter' 	 => $this->params,
			'filter_exclude_categories' => $filter_exclude_categories,
			'sort'               => $webfun_sort,
			'order'              => $webfun_order,
			'start'              => ($page - 1) * $webfun_limit,
			'limit'              => $webfun_limit
			);
			
			if ($this->config->get('ocfilter_sub_category')) {
				$filter_data['filter_sub_category'] = true;
			}
			
			$results = $this->model_catalog_product->getProducts($filter_data);

			//getBestSellers For current 
			if (count($results) > 10 && $page == 1){
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
					foreach ($this->model_catalog_product->getProductAttributes($result['product_id']) as $attribute_group) {
						foreach ($attribute_group['attribute'] as $attribute) {
							if (isset($oct_advanced_attributes_settings_data['allowed_attributes']) && (in_array($attribute['attribute_id'], $oct_advanced_attributes_settings_data['allowed_attributes']))) {
								$oct_attributes[] = array(
								'name' => $attribute['name'],
								'text' => $attribute['text']
								);
							}
						}
					}
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
				
				$webfun_products[] = array(
				// oct_techstore start
				'saving'      => round((($result['price'] - $result['special'])/($result['price'] + 0.01))*100, 0),
				'model'       => $result['model'],
				'stock'       => $stock,
				'oct_product_stickers' => $oct_product_stickers,
				'oct_options' => $oct_options,
				'oct_attributes' => $oct_attributes,
				'product_id'  => $result['product_id'],
				'sku'       => $result['sku'],//***
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($this->model_tool_mikrof->formatStringdisplay($result['description']), ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
				'quantity'       => $result['quantity'], 
				'product_preorder_text' => $product_preorder_text,
				'product_preorder_status' => $product_preorder_status,
				'price'       => $price,
				'special'     => $special,
				'action_stickers' => $result['action_stickers'],
				'tax'         => $tax,
				'minimum'     => ($result['minimum'] > 0) ? $result['minimum'] : 1,
				'rating'      => $rating,
				'href'        => $this->url->link('product/product', 'path=' . $this->path . '&product_id=' . $result['product_id'])
				);
			}
			
			$oct_popup_view_data = $this->config->get('oct_popup_view_data');
			$button_popup_view = $this->language->get('button_popup_view');
			
			$oct_data = $this->config->get('oct_techstore_data');
			if (isset($oct_data['oct_lazyload']) && $oct_data['oct_lazyload'] == 1) {
				$oct_lazyload = $oct_data['oct_lazyload'];
			}
			
			foreach ($webfun_products as $product) {
				$json['webfun_products'] .= '<div class="product-layout product-grid col-md-4 col-sm-6 col-xs-6"><div class="product-thumb';
				if (isset($product['product_preorder_status']) && $product['product_preorder_status'] != 1 && $product['quantity'] <= 0) {
					$json['webfun_products'] .= ' no_quantity';
				}
				$json['webfun_products'] .= '" title="' . $product['name'] . '"><div class="image">';
				if (isset($oct_popup_view_data['status']) && $oct_popup_view_data['status'] && $product['quantity'] > 0) {
					$json['webfun_products'] .= '<div class="quick-view"><a onclick="get_oct_popup_product_view(\'' . $product['product_id'] . '\');">' . $button_popup_view . '</a></div>';
				}
				if ($product['special']) {
					$json['webfun_products'] .= '<div class="oct-discount-box"><div class="oct-discount-item">-' . $product['saving'] . '%</div></div>';
				}

				if ($product['action_stickers']) {
					$json['webfun_products'] .= '<div class="oct-discount-box_wrap">';
					foreach ($product['action_stickers'] as $action_sticker) {
						$json['webfun_products'] .= '<div class="oct-discount-box">';
						$json['webfun_products'] .= '<span class="label label-danger" style="font-size: 14px;';
						if ($action_sticker['label_color']) { 
							$json['webfun_products'] .= 'color:' . $action_sticker['text_color'] . ' !important;';
						} 
						if ($action_sticker['label_bg']) {
							$json['webfun_products'] .= 'background-color:' . $action_sticker['label_bg'] . '!important;';
						}
						$json['webfun_products'] .= '">';
						$json['webfun_products'] .= $action_sticker['label'];
						$json['webfun_products'] .= '</span>';
						$json['webfun_products'] .= '	</div>';
					}
					$json['webfun_products'] .= '</div>';																						
				}


				if ($product['oct_product_stickers']) {
					$json['webfun_products'] .= '<div class="oct-sticker-box">';
					foreach ($product['oct_product_stickers'] as $product_sticker) {
						$json['webfun_products'] .= '<div class="oct-sticker-item" style="color: ' . $product_sticker['color'] . '; background: ' . $product_sticker['background'] . ';">' . $product_sticker['text'] . '</div>';
					}
					$json['webfun_products'] .= '</div>';
				}
				if (isset($oct_lazyload) && $oct_lazyload) {
					$json['webfun_products'] .= '<a href="' . $product['href'] . '" class="lazy_link"><img data-original="' . $product['thumb'] . '" src="' . $oct_lazyload_image . '" class="img-responsive lazy" alt="' . $product['name'] . '" /></a>';
					} else {
					$json['webfun_products'] .= '<a href="' . $product['href'] . '"><img src="' . $product['thumb'] . '" class="img-responsive" alt="' . $product['name'] . '" /></a>';
				}
				$json['webfun_products'] .= '</div><div><div class="caption"><p class="cat-model">';
				if(strlen($product['sku'])) {
					$json['webfun_products'] .= $this->language->get('text_sku') . ' <span>' . $product['sku'] . '</span>';
				}
				$json['webfun_products'] .= '</p><h4><a href="' . $product['href'] . '">' . $product['name'] . '</a></h4>';
				if ($product['rating']) {
					$json['webfun_products'] .= '<div class="rating">';
					for ($i = 1; $i <= 5; $i++) {
						if ($product['rating'] < $i) {
							$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>';
							} else {
							$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>';
						}
					}
					$json['webfun_products'] .= '</div>';
					} else {
					$json['webfun_products'] .= '<div class="rating">';
					for ($i = 1; $i <= 5; $i++) {
						$json['webfun_products'] .= '<span class="fa fa-stack"><i class="fa fa-star-o" aria-hidden="true"></i></span>';
					}
					$json['webfun_products'] .= '</div>';
				}
				$json['webfun_products'] .= '<hr><div class="cat-box-effect"><div class="cat-box-effect-inner">';
				if ($product['price']) {
					$json['webfun_products'] .= '<p class="price">';
					if (!$product['special']) {
						$json['webfun_products'] .= '<span class="common-price">' . $product['price'] . '</span>';
						} else {
						$json['webfun_products'] .= '<span class="price-new">' . $product['special'] . '</span> <span class="price-old">' . $product['price'] . '</span>';
					}
					$json['webfun_products'] .= '</p>';
				}
				$json['webfun_products'] .= '<div class="cart">';
				if ($product['quantity'] > 0) {
					$json['webfun_products'] .= '<a class="button-cart oct-button" title="' . $this->language->get('button_cart') . '" onclick="get_oct_popup_add_to_cart(\'' . $product['product_id'] . '\', \'1\');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">' . $this->language->get('button_cart') . '</span></a>';
					} else {
					$json['webfun_products'] .= '<a class="button-cart out-of-stock-button oct-button" ';
					if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) {
						$json['webfun_products'] .= 'onclick="get_oct_product_preorder(\'' . $product['product_id'] . '\');"';
					}
					$json['webfun_products'] .= '><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">' . $product['product_preorder_text'] . '</span></a>';
				}
				$json['webfun_products'] .= '<a onclick="get_oct_popup_add_to_wishlist(\'' . $product['product_id'] . '\');" title="' . $this->language->get('button_wishlist') . '" class="wishlist oct-button"><i class="fa fa-heart" aria-hidden="true"></i></a><a onclick="get_oct_popup_add_to_compare(\'' . $product['product_id'] . '\');" title="' . $this->language->get('button_compare') . '" class="compare oct-button"><i class="fa fa-sliders" aria-hidden="true"></i></a></div></div></div><p class="oct-product-stock"><span class="hidden-xs">' . $this->language->get('text_stock') . '</span> <span>' . $product['stock'] . '</span></p><div class="oct-additional-info"><p class="oct-product-desc">' . $product['description'] . '</p>';
				if (isset($product['oct_options']) && $product['oct_options']) {
					$json['webfun_products'] .= '<div class="cat-options">';
					foreach ($product['oct_options'] as $option) {
						if ($option['type'] == 'radio') {
							$json['webfun_products'] .= '<div class="form-group"><label class="control-label">' . $option['name'] . '</label><br/>';
							if ($option['product_option_value']) {
								foreach ($option['product_option_value'] as $product_option_value) {
									if ($product_option_value['image']) {
										$json['webfun_products'] .= '<div class="radio"><img src="' . $product_option_value['image'] . '" alt="' . $product_option_value['name'] . '" class="img-thumbnail" title="' . $product_option_value['name'] . '" />
										</div>';
										} else {
										$json['webfun_products'] .= '<div class="radio"><label class="not-selected">' . $product_option_value['name'] . '</label>
										</div>';
									}
								}
							}
							$json['webfun_products'] .= '</div>';
							} else {
							$json['webfun_products'] .= '<div class="form-group size-box"><label class="control-label">' . $option['name'] . '</label><br/>';
							if ($option['product_option_value']) {
								foreach ($option['product_option_value'] as $product_option_value) {
									$json['webfun_products'] .= '<div class="radio"><label class="not-selected">' . $product_option_value['name'] . '</label></div>';
								}
							}
							$json['webfun_products'] .= '</div>';
						}
					}
					$json['webfun_products'] .= '</div>';
				}
				if (isset($product['oct_attributes']) && $product['oct_attributes']) {
					$json['webfun_products'] .= '<div class="cat-options">';
					foreach ($product['oct_attributes'] as $attribute) {
						$json['webfun_products'] .= '<div class="form-group size-box"><label class="control-label">' . $attribute['name'] . '</label><br/><span>' . $attribute['text'] . '</span></div>';
					}
					$json['webfun_products'] .= '</div>';
				}
				$json['webfun_products'] .= '</div></div></div></div></div>';
			}
			
			// $json['webfun_html'] = file_get_contents($json['href']);
			
			// sorts
			if (strripos($json['href'], '?') !== false) {
				$parts = explode('?', $json['href']);
				$url = $parts[0];
				} else {
				$url = $json['href'];
			}
			
			$sorts = array();
			$json['sort'] = '';
			
			// $sorts[] = array(
			//   'text'  => $this->language->get('text_default'),
			//   'value' => 'p.sort_order-ASC',
			//   'href'  => $url . '?sort=p.sort_order&order=ASC&limit=' . $webfun_limit
			// );
			
			$sorts[] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $url . '?sort=pd.name&order=ASC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $url . '?sort=pd.name&order=DESC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $url . '?sort=p.price&order=ASC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $url . '?sort=p.price&order=DESC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
			);
			
			if ($this->config->get('config_review_status')) {
				$sorts[] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $url . '?sort=rating&order=DESC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
				);
				
				$sorts[] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $url . '?sort=rating&order=ASC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
				);
			}
			
			$sorts[] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => $url . '?sort=p.model&order=ASC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
			);
			
			$sorts[] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $url . '?sort=pd.name&order=ASC&limit=' . $webfun_limit . (($page > 1) ? '&page=' . $page : '')
			);
			
			foreach ($sorts as $sorts) {
				if ($sorts['value'] == $webfun_sort . '-' . $webfun_order) {
					$json['sort'] .= '<option value="' . $sorts['href'] . '" selected="selected">' . $sorts['text'] . '</option>';
					} else {
					$json['sort'] .= '<option value="' . $sorts['href'] . '">' . $sorts['text'] . '</option>';
				}
			}
			
			// limits
			if (strripos($json['href'], 'limit=') !== false) {
				$json['limit'] = '';
				$limitsHtml = array();
				$parts = explode('limit=', $json['href']);
				$url = $parts[0];
				
				$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));
				
				sort($limits);
				
				foreach($limits as $value) {
					$limitsHtml[] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $url . 'limit=' . $value . (($page > 1) ? '&page=' . $page : '')
					);
				}
				
				foreach ($limitsHtml as $limits) {
					if ($limits['value'] == $webfun_limit) {
						$json['limit'] .= '<option value="' . $limits['href'] . '" selected="selected">' . $limits['text'] . '</option>';
						} else {
						$json['limit'] .= '<option value="' . $limits['href'] . '">' . $limits['text'] . '</option>';
					}
				}
			}
			
			// selected options
			if ($this->config->get('ocfilter_show_selected') && $this->options_get) {
				$json['selecteds'] = '';
				
				$selecteds = $this->getSelectedOptions();
				if ($selecteds) {
					$json['selecteds'] .= '<div class="list-group-item selected-options">';
					foreach ($selecteds as $key => $option) {
						$json['selecteds'] .= '<div class="ocfilter-option"><span>' . $option['name'] . ':</span>';
						foreach ($option['values'] as $value) {
							$json['selecteds'] .= '<button type="button" class="btn btn-xs btn-danger" style="padding: 1px 4px;" data-wf-option-id="' . $key . '" data-wf-option-id-value="' . $value['id'] . '"><i class="fa fa-times"></i> ' . $value['name'] . '</button>';
						}
						$json['selecteds'] .= '</div>';
					}
					
					$json['selecteds'] .= '<button type="button" class="btn btn-block btn-danger wf-ocfilter-cancel-all" style="border-radius: 0;"><i class="fa fa-times-circle"></i> ' . $this->language->get('text_cancel_all') . '</button></div>';
				}
			}
			
			// pagination
			if (strripos($json['href'], '?') !== false) {
				$parts = explode('?', $json['href']);
				$url = $parts[0];
				} else {
				$url = $json['href'];
			}
			
			$url .= '?sort=' . $webfun_sort . '&order=' . $webfun_order . '&limit=' . $webfun_limit;
			
			$pagination = new Pagination();
			$pagination->total = $json['total'];
			$pagination->page = $page;
			$pagination->limit = $webfun_limit;
			$pagination->url = $url . '&page={page}';
			
			$json['pagination'] = $pagination->render();
			
			// new h1
			$this->load->model('catalog/category');
			
			$category_info = $this->model_catalog_category->getCategory($this->category_id);
			if ($category_info) {
				if ($category_info['meta_h1']) {
					$new_h1 = $category_info['meta_h1'];
					} else {
					$new_h1 = $category_info['name'];
				}
				
				$filter_title = $this->getSelectedsFilterTitle();
				
				if ($filter_title) {
					if (false !== strpos($new_h1, '{filter}')) {
						$new_h1 = trim(str_replace('{filter}', $filter_title, $new_h1));
						} else {
						$new_h1 .= ' ' . $filter_title;
					}
					
					$json['heading_title'] = $new_h1;
					} else {
					$json['heading_title'] = trim(str_replace('{filter}', '', $new_h1));
				}
			}

			$hack_data = $data;
			$hack_data['webfun_products'] 		= $webfun_products;
			$hack_data['oct_data'] 				= $oct_data;
			$hack_data['oct_popup_view_data']	= $oct_popup_view_data;
			$hack_data = $hack_data + $this->load->language('extension/module/ocfilter');
			
			$json['webfun_products'] = $this->load->view('product/ocfilter_callback', ($hack_data));			
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}										