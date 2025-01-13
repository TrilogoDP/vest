<?php
class agooMultilang extends Controller
{
	private $langcode_all;
	private $languages_all;
	private $domen = '';
	private $stores = array();
	private $jetcache_buildcache = false;

	public function __construct($registry) {

		parent::__construct($registry);

		if (!defined('VERSION')) return; if (!defined('SC_VERSION')) define('SC_VERSION', substr(str_replace('.', '', VERSION), 0, 2));

		$langmark_settings  = $this->config->get('asc_langmark');

		if (!isset($langmark_settings['store']) || !in_array($this->config->get('config_store_id'), $langmark_settings['store'])) {
			return;
		}

		$language_code        		= '';
		$language_code_parefix		= '';
		$language_code_cookie 		= '';
		$language_code_flag   		= false;

        $flag_pagination 	= false;
		$ajax        		= false;
		$slash       		= false;
        $is_main 			= false;
        $main_pref 			= false;

		if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
			$conf_ssl = $this->config->get('config_ssl');
			if (!$conf_ssl) $conf_ssl = HTTPS_SERVER;
			// folder ?
			$this->domen = substr($conf_ssl, 0, $this->strpos_offset('/', $conf_ssl, 3) + 1);
			$config_url = $conf_ssl;
		} else {
			$conf_url = $this->config->get('config_url');
			if (!$conf_url) $conf_url = HTTP_SERVER;
			$this->domen = substr($conf_url, 0, $this->strpos_offset('/', $conf_url, 3) + 1);
			$config_url = $conf_url;
		}

        $uri = $this->request->server['REQUEST_URI'];
        $full_url = rtrim($config_url, '/').$uri;
        $full_url_data = parse_url(str_replace('&amp;', '&', $full_url));

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->jetcache_buildcache = false;
			$jetcache_headers = getallheaders();
			if (isset($jetcache_headers['JETCACHE_BUILDCACHE'])) {
				$this->jetcache_buildcache = true;
			}
		}

		if (isset($this->request->cookie['language'])) {
			$language_code_cookie = $this->request->cookie['language'];
		}

		if (isset($this->session->data['language'])) {
			$language_code_cookie = $language_code_before = $this->session->data['language'];
		} else {
			$language_code_before = '';
		}

		if (!isset($_SESSION['language_first'])) {
			$_SESSION['language_first'] = true;
		}

		if (isset($this->request->get['_route_'])) {
			$route = urldecode($this->request->get['_route_']);
		} else {
			$route = '';
		}

	    if (!isset($__route__)) {
        	$__route__ = $route;
        }

        $full_url_route = rtrim($this->domen, '/').'/'.$route;
        $url_data = parse_url(str_replace('&amp;', '&', $uri));

        if (isset($url_data['path'])) {
        	$url_data['path'] = trim($url_data['path'], '/');
        } else {
        	$url_data['path'] = '';
        }

        $path_info = pathinfo($url_data['path']);

        if (isset($path_info['extension'])) {
        	$url_data['ext'] = $path_info['extension'];
        } else {
        	$url_data['ext'] = '';
        }

		if ($url_data['ext'] =='js') {
           	$ajax = true;
		}

		if (isset($this->request->server['HTTP_ACCEPT'])) {

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('image')) !== false) {

             	if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('html')) !== false) {
                    $ajax = false;
				} else {
					$ajax = true;
				}
            }

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('js')) !== false) {
            	$ajax = true;
			}

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('json')) !== false) {
	            $ajax = true;
			}

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('ajax')) !== false) {
    	        $ajax = true;
			}

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('javascript')) !== false) {
        	    $ajax = true;
			}

		}

        if (isset($langmark_settings['ex_multilang_route']) && $langmark_settings['ex_multilang_route']!='') {
	        $ex_multilang_route = $langmark_settings['ex_multilang_route'];
	        $ex_multilang_route_array = explode(PHP_EOL, $ex_multilang_route);
			if (isset($this->request->get['route'])) {
				foreach ($ex_multilang_route_array as $ex_route) {
					if (utf8_strpos(utf8_strtolower($this->request->get['route']),trim($ex_route)) !== false) {
	            		$ajax = true;
					}
				}
			}
        }

        if (isset($langmark_settings['ex_multilang_uri']) && $langmark_settings['ex_multilang_uri']!='') {
	        $ex_multilang_uri = $langmark_settings['ex_multilang_uri'];
	        $ex_multilang_uri_array = explode(PHP_EOL, $ex_multilang_uri);
			if (isset($this->request->server['REQUEST_URI'])) {
				foreach ($ex_multilang_uri_array as $ex_uri) {
					if (utf8_strpos(utf8_strtolower($this->request->server['REQUEST_URI']), trim($ex_uri)) !== false) {
		            	$ajax = true;
					}
				}
			}
		}

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if (!$this->jetcache_buildcache) {
				$ajax = true;
			}
		}

		$languages = array();
		$query     = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");
		foreach ($query->rows as $result) {
			$languages[$result['code']] = $result;
			$this->langcode_all[$result['code']]         = $result;
			$this->languages_all[$result['language_id']] = $result;
		}

        foreach ($languages as $language_code => $language_result) {
			if (isset($langmark_settings['prefix_home_default'][$language_code]) && $langmark_settings['prefix_home_default'][$language_code]) {
				$config_language_default[$language_code] = $language_code;
				$this->config->set('config_language_default_langmark', $config_language_default);
	        }
        }

        $parts = explode('/', trim(utf8_strtolower($route), '/'));
		$parts_first = $parts[0];

		$parts_first_array = explode('?', $parts_first);
		$parts_first =  $parts_first_array[0];

		foreach ($langmark_settings['prefix'] as $lang_code => $prefix) {
			$array_prefix_url[$lang_code] = $full_url_data['scheme'].'://'.$prefix;
		}

        //if (!$ajax) {
        	$max_len = 0;
        	if (is_array($array_prefix_url) && !empty($array_prefix_url)) {
        		foreach ($array_prefix_url as $lang_code => $prefix_url) {
                	$prefix_len_source = substr($full_url_route, 0, strlen($prefix_url));

                    //$after_prfix = substr($full_url_route, strlen($prefix_url));
                    $this_prefix = trim(str_ireplace($this->domen, '', $prefix_url), '/');
                    $route_prefix_array = explode('/', $route);
                    $route_prefix = trim($route_prefix_array[0], '/');

                    /*
                    print_my($this_prefix);
                    print_my($route_prefix);
					print_my($prefix_url);

                    print_my($full_url_route);
                    print_my($route);
                    print_my(str_ireplace($route, '', $full_url_route));


                    print_my($prefix_len_source);

                    print_my($after_prfix);
                    print_my(str_ireplace($prefix_url, '', $full_url_route));


                    print_my(str_ireplace(str_ireplace($prefix_url, '', $full_url_route), '', $route));

                    print_my('--------------------------------------------------');
                     */

                    if (($this_prefix == $route_prefix) || ($this_prefix == '' && $route_prefix != '')) {
						if ($prefix_len_source == $prefix_url && strlen($prefix_url) > $max_len) {
							$max_len = strlen($prefix_url);

							$__route__ = str_ireplace($prefix_url, '', $full_url_route);
						 	$switch_flag = true;
							$lang_code_source = $lang_code;
							$language_code_parefix = $langmark_settings['prefix'][$lang_code];
						}
                     }
				}
        	}

	        if (isset($__route__ ) && $__route__ != '') {
	        	if (isset($this->request->get['route'])) {
	        		unset($this->request->get['_route_']);
	        		unset($_GET['_route_']);
	        	} else {
	        		$this->request->get['_route_'] = $_GET['_route_'] = $__route__;
	        	}
	        } else {
	        	unset($this->request->get['_route_']);
	        	unset($_GET['_route_']);
	        }

        if (!$ajax) {
	        if (isset($switch_flag) && $switch_flag) {
				if (isset($languages[$lang_code_source]['language_id'])) {
					$this->switchLanguage($languages[$lang_code_source]['language_id'], $lang_code_source);
				}
	        }
            //unset($this->session->data['currency_old']);
            if (!isset($this->session->data['currency_old'])) {
            	$this->session->data['currency_old'] = Array();
            }

               if (isset($this->session->data['currency']) && isset($this->session->data['language']) && isset($this->session->data['currency_old'][$this->session->data['language']]['currency']) && $this->session->data['currency'] !== $this->session->data['currency_old'][$this->session->data['language']]['currency']) {

               } else {

					if (isset($langmark_settings['currency'][$this->session->data['language']]) && $langmark_settings['currency'][$this->session->data['language']] != '') {


						$this->session->data['currency'] = $langmark_settings['currency'][$this->session->data['language']];

						if (SC_VERSION > 21) {
							unset($this->session->data['shipping_method']);
							unset($this->session->data['shipping_methods']);
						} else {
							$this->currency->set($langmark_settings['currency'][$this->session->data['language']]);
						}

                        if (isset($langmark_settings['currency_switch']) && $langmark_settings['currency_switch']) {
                         	unset($this->session->data['currency_old']);
                        }

						$this->session->data['currency_old'][$this->session->data['language']]['switch'] = true;
						$this->session->data['currency_old'][$this->session->data['language']]['currency'] = $this->session->data['currency'];
						$this->session->data['currency_old'][$this->session->data['language']]['language'] = $this->session->data['language'];

	 				}
               }

        }

        $parts_route = explode('/', trim($__route__, '/'));

		if (isset($langmark_settings['pagination']) && $langmark_settings['pagination']) {
		        /* for seo pagination */

				$parts_end = end($parts_route);

				if ($langmark_settings['pagination_prefix'] != '' && (strpos($parts_end, $langmark_settings['pagination_prefix'].'-') !== false || strpos($parts_end, 'page-') !== false)) {
						list($key, $value) = explode("-", $parts_end);
                        $value = (int)$value;
						if ($value > 1) {
							$this->request->get['page'] = $value;
						}

			   			$title = $this->document->getTitle();
			   			$description = $this->document->getDescription();

                        $this->registry->set('langmark_page', $value);

						$this->document->setTitle($title .  ' '.$langmark_settings['pagination_title'][$this->config->get('config_language')].' ' . $this->registry->get('langmark_page'));
						$this->document->setDescription($description .  ' '.$langmark_settings['pagination_title'][$this->config->get('config_language')].' ' . $this->registry->get('langmark_page'));

						unset($parts_route[count($parts_route) - 1]);

                        $flag_pagination = true;

		        		reset($parts_route);

		       	}
		        /* for seo pagination */
        }

		if (isset($this->request->get['_route_'])) {
			if (!isset($langmark_settings['jazz']) || !$langmark_settings['jazz'] || $flag_pagination) {
				if (isset($langmark_settings['jazz']) && $langmark_settings['jazz'] && $flag_pagination) {
					array_unshift($parts_route, trim($language_code_parefix, '/'));
					$this->request->get['_route_'] = $_GET['_route_'] = implode('/', $parts_route);
				}

                if (!$flag_pagination) {
               		$this->request->get['_route_'] = $_GET['_route_'] = $__route__;
               	} else {
               		$this->request->get['_route_'] = $_GET['_route_'] = implode('/', $parts_route);
               	}
			}
		}

		if (isset($this->request->get['route']) || empty($parts_route)) {
			if (!isset($langmark_settings['jazz']) || !$langmark_settings['jazz'] || $flag_pagination) {
				unset($this->request->get['_route_']);
			}
		}

        return;
		/************************************************************************************************/
	}

	private function getQueryString($exclude = array()) {
		if (!is_array($exclude)) {
				$exclude = array();
		}
		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}

    public function index() {
    }

	private function strpos_offset($needle, $haystack, $occurrence)
	{
		$arr = explode($needle, $haystack);

		switch ($occurrence) {
			case $occurrence == 0:
				return false;
			case $occurrence > max(array_keys($arr)):
				return false;
			default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
		}
	}

	public function switchLanguage($language_id, $language_code)	{
		if ($language_code != '') {

				$language_code_old = $this->session->data['language'];
				$this->session->data['language'] = $language_code;
				setcookie('language', $language_code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
				$this->config->set('config_language_id', $language_id);
				$this->config->set('config_language', $language_code);

				if (SC_VERSION > 21) {
                    $language_construct = $language_code;
				} else {
					$language_construct = $this->langcode_all[$language_code]['directory'];
				}
				$language = new Language($language_construct);

				if (SC_VERSION > 15) {
					if (SC_VERSION > 21) {
						$language->load($language_code);
					} else {
						$language->load('default');
						$language->load($language_construct);
					}

				} else {
					$language->load($this->langcode_all[$language_code]['filename']);
				}
				$this->registry->set('language', $language);
				//$this->session->data['language_old'] = $language_code;


				$langdata = $this->config->get('config_langdata');
				if (isset($langdata[$language_id])) {
				  foreach ($langdata[$language_id] as $key => $value) {
				    $this->config->set('config_' . $key, $value);
				  }
				}


		}
	}

	private function session_clear() {
		$data = $_SESSION;
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				if ($key != 'user_id' || $key != 'token') {
					unset($_SESSION[$key]);
				}
			}
		}
	}

}
