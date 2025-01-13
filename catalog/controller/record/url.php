<?php
	/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
	// http://opencartadmin.com © 2011-2018 All Rights Reserved
	// Distribution, without the author's consent is prohibited
	// Commercial license
	class ControllerRecordUrl extends Controller
	{
		public function url_construct() {
			
			$sc_ver = VERSION; if (!defined('SC_VERSION')) define('SC_VERSION', (int) substr(str_replace('.', '', $sc_ver), 0, 2));
			
			if ($this->config->get('ascp_settings') != '') {
				$this->seocms_settings = $this->config->get('ascp_settings');
				} else {
				$this->seocms_settings = Array();
			}
			
			if (SC_VERSION > 23) {
				$this->token_name = 'user_token';
				} else {
				$this->token_name = 'token';
			}
			
			$this->setUrlRegistry($this->registry);
			
			return true;
		}
		
		public function index() {
			return true;
		}
		
		public function setUrlRegistry($registry) {
			if (is_callable(array('Url', 'seocms_setRegistry'))) {
				$this->url->seocms_setRegistry($registry);
			}
		}
		
		public function before($rewrite) {
			if ($this->config->get('sc_ar_'.strtolower(get_class($rewrite)))) {
				return;
			}
			$this->config->set('sc_ar_'.strtolower(get_class($rewrite)), true);
		}
		
		public function after($modules) {
			
			if (!is_string($modules)) {
				return $modules;
			}
			
			if (
			isset($this->seocms_settings['latest_widget_status']) && $this->seocms_settings['latest_widget_status'] &&
			!$this->registry->get('seocms_url_alter') &&
			!class_exists('ControllerCommonSeoBlog') &&
			(class_exists('ControllerCommonSeoUrl') ||
			class_exists('ControllerCommonSeoPro') ||
			class_exists('ControllerStartupSeoUrl') ||
			class_exists('ControllerStartupSeoPro'))
			&& !$this->registry->get('admin_work')
			&& !$this->config->get('sc_ar_'.strtolower('ControllerCommonSeoBlog'))
			) {
				agoo_cont('record/addrewrite', $this->registry);
				$this->controller_record_addrewrite->add_construct($this->registry);
			}
			
			/*
				
				if (isset($this->seocms_settings['latest_widget_status']) && $this->seocms_settings['latest_widget_status']) {
				
				if (!$this->registry->get('seoblog')) {
	     		require_once(DIR_APPLICATION . 'controller/common/seoblog.php');
				$seoBlog = new ControllerCommonSeoBlog($this->registry);
	            $this->registry->set('seoblog', $seoBlog);
				}
				
				$modules = $this->seoblog->rewrite($modules);
				}
			*/
			
			
			if (!isset($this->seocms_settings['langmark_widget_status']) || !$this->seocms_settings['langmark_widget_status'] || $this->registry->get('admin_work')) {
				return $modules;
			}
			unset($this->seocms_settings);
			
			$langmark_settings  = $this->config->get('asc_langmark');
			
			if (!isset($langmark_settings['store']) || !in_array($this->config->get('config_store_id'), $langmark_settings['store'])) {
				return $modules;
			}
			
			
			$link_route = $modules;
			
			
			if (isset($langmark_settings['ex_url_route']) && $langmark_settings['ex_url_route']!='') {
				$ex_url_route = $langmark_settings['ex_url_route'];
				$ex_url_route_array = explode(PHP_EOL, $ex_url_route);
				
				if ($link_route!='') {
					foreach ($ex_url_route_array as $ex_route) {
						
						if (utf8_strpos(utf8_strtolower($link_route),trim($ex_route)) !== false) {
							$ajax = true;
							return $modules;
						}
					}
				}
			}
			
			if (isset($langmark_settings['ex_url_uri']) && $langmark_settings['ex_url_uri']!='') {
				$ex_url_uri = $langmark_settings['ex_url_uri'];
				$ex_url_uri_array = explode(PHP_EOL, $ex_url_uri);
				if (isset($this->request->server['REQUEST_URI'])) {
					foreach ($ex_url_uri_array as $ex_uri) {
						if (utf8_strpos(utf8_strtolower($this->request->server['REQUEST_URI']), trim($ex_uri)) !== false) {
							$ajax = true;
							return $modules;
						}
					}
				}
			}
			
			if (stripos($modules, $this->config->get('config_ssl'))!==false) {
				$scheme = 'https';
				$config_url = $this->config->get('config_ssl');
				} else {
				
				if (stripos($modules, $this->config->get('config_url'))!==false) {
					$scheme = 'http';
					$config_url = $this->config->get('config_url');
					} else {
					$scheme = true;
					$config_url = substr($modules, 0, $this->strpos_offset('/', $modules, 3) + 1);
				}
			}
			
			if ($scheme) {
				// folder ?
				$config_url = substr($config_url, 0, $this->strpos_offset('/', $config_url, 3) + 1);
				$url = str_replace($config_url, '', $modules);
				} else {
				$url = $modules;
			}
			
			
			if (isset($this->session->data['language'])) {
				$code_session = $this->session->data['language'];
				} else {
				$code_session = $this->config->get('config_language');
			}		
			
			$full_url_data = parse_url(str_replace('&amp;', '&', $modules));
			$domen_modules = $full_url_data['scheme'].'://'.$full_url_data['host'].'/';
			
			foreach ($langmark_settings['prefix'] as $lang_code => $prefix) {
				$array_prefix_url[$lang_code] = $full_url_data['scheme'].'://'.$prefix;
			}
			
			if ($domen_modules != $modules) {
				$razdel = '';
				} else {
				
			}
			
			if (is_array($array_prefix_url) && !empty($array_prefix_url)) {
				foreach ($array_prefix_url as $lang_code => $prefix_url) {
					if ($lang_code == $this->session->data['language']) {
						
						if ($domen_modules == $modules) {
							$razdel = '';
							} else {
							if (substr($prefix_url, -1) != '/') {
								$razdel = '/';
								} else {
								$razdel = '';
							}
						}
						
						$code_prefix = $prefix_url;
						$modules = str_ireplace($domen_modules, $prefix_url.$razdel, $modules);											
						
						break;
					}
				}
			}
			
			
			if (isset($langmark_settings['pagination']) && $langmark_settings['pagination']) {
				
				if ($this->registry->get('langmark_page') != '') {
					
					$title = $this->document->getTitle();
					$description = $this->document->getDescription();
					if (utf8_strpos($title, ' '. $this->registry->get('langmark_page')) === false) {
						$this->document->setTitle($title .  ' '.$langmark_settings['pagination_title'][$this->config->get('config_language')].' ' . $this->registry->get('langmark_page'));
						$this->document->setDescription($description .  ' '.$langmark_settings['pagination_title'][$this->config->get('config_language')].' ' . $this->registry->get('langmark_page'));
					}
				}
				$modules_params_1 = $modules;
				if (isset($modules_params_1) && is_string($modules_params_1) && strpos($modules_params_1, 'page=') !== false) {
					
					
					$component = parse_url(str_replace('&amp;', '&', $modules));
					
					$component['path'] = str_replace('/index.php', '', $component['path']);
					
					$data_array = array();
					if (isset($component['query'])) {
						parse_str($component['query'], $data_array);
					}
					
					if (count($data_array)) {
						/*** added code seo paging http://opencartadmin.com ***/
						$seo_url ='';
						$paging = '';
						$devider = '/'; // :)
						foreach ($data_array as $key => $value) {
							
							if ($key == $langmark_settings['pagination_prefix'] || $key=="page") {
								$key = $langmark_settings['pagination_prefix'];
								if ($devider != '/') {
									$paging = "/" . $key . "-" . $value;
									} else  {
									$paging = $key . "-" . $value;
								}
								
								unset($data_array[$key]);
								if (isset($data_array['page'])) {
									unset($data_array['page']);
								}
							}
						}
						// WTF?
						
						if (isset($modules_params_1) && is_string($modules_params_1) && strpos($modules_params_1, 'page={page}') !== false) {
							//  $paging = '';
						}
						
						if (trim($paging,'/') == $langmark_settings['pagination_prefix'].'-1') {
							$paging = '';
						}
						
						if (count($data_array)) {
							$seo_url .= $paging.'?' . urldecode(http_build_query($data_array, '', '&amp;'));
							} else {
							$seo_url .= $paging;
						}
						if (trim($component['path']) == '') $mydel =""; else $mydel = "/";
						$modules = $component['scheme'].'://'.$component['host'].'/' .trim($component['path'],'/').$mydel.$seo_url;
						
						if ($paging != '') {
							$modules = rtrim($modules,'/');
						}
					}
					
				}
				/* for pagination */
			}
			
			if (isset($langmark_settings['commonhome_status']) && $langmark_settings['commonhome_status']) {
				if (strpos(strtolower($modules),'index.php?route=common/home') !== false) {
					
					if(substr($code_prefix, -1) == '/' || $code_prefix == '') {
						$home_divider = '';
						} else {
						$home_divider = '/';
					}
					
					if ((utf8_strpos(utf8_strtolower($modules),'&') !== false) || (utf8_strpos(utf8_strtolower($modules),'&amp;') !== false)) {
						$modules = str_replace('&amp;', '&', $modules);
						$modules = str_replace('&', '&amp;', $modules);
						$modules = str_replace($home_divider.'index.php?route=common/home&amp;', '?', $modules);
						} else {
						$modules = str_replace($home_divider.'index.php?route=common/home', '', $modules);
					}
					
				}
			}
			if (isset($langmark_settings['two_status']) && $langmark_settings['two_status']) {
				$modules = preg_replace('/(?<!^[http:]|[https:])[\/]{2,}/', '/', trim($modules));
			}
			
			$this->registry->set('url_work', false);
			
			return $modules;
		}
		private function strpos_offset($needle, $haystack, $occurrence) {
			// explode the haystack
			$arr = explode($needle, $haystack);
			// check the needle is not out of bounds
			switch ($occurrence) {
				case $occurrence == 0:
				return false;
				case $occurrence > max(array_keys($arr)):
				return false;
				default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
			}
		}
	}