<?php
/* All rights reserved belong to the module, the module developers https://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
if (!class_exists('ControllerJetcacheJetcache', false)) {
class ControllerJetcacheJetcache extends Controller {
	protected $data;
	protected $template;
	protected $seocms_settings;
	protected $sc_cache_name;
	protected $token_name;
	protected $jetcache_cont_number;
  	protected $flag_cache_access_output = false;
	protected $jetcache_buildcache = false;
	protected $count_model_cached = 0;
	protected $count_cont_cached = 0;
	protected $count_query_cached = 0;
	protected $jc_ajax = false;
	protected $jc_cont_log = array();
	protected $template_engine;
	protected $template_directory;
	protected $langcode_all;
	protected $jc_cont_document;
	protected $url_link_ssl;
	protected $url_protocol;
	protected $admin_logged = false;
	protected $user_id = false;
	public $request_uri_trim;
	public $jetcache_settings;

	public function jetcache_construct() {
 		if (!$this->registry->get('jetcache_construct')) {
            $this->registry->set('jetcache_construct', true);
            if (!defined('VERSION')) return false;
			if (!defined('SC_VERSION')) define('SC_VERSION', (int) substr(str_replace('.', '', VERSION), 0, 2));
	        if (SC_VERSION > 23) {
	        	$this->token_name = 'user_token';
	        } else {
	        	$this->token_name = 'token';
	        }

		    if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
		    	$this->url_link_ssl = true;
		    	$this->url_protocol = 'https';
		    } else {
		    	if (SC_VERSION < 20) {
		    		$this->url_link_ssl = 'NONSSL';
		    	} else {
		    		$this->url_link_ssl = false;
		    	}
		    	$this->url_protocol = 'http';
		    }

            $this->request_uri_trim = trim(ltrim($this->registry->get('request')->server['REQUEST_URI'], '/'));
            $this->jc_ajax = false;
			if (isset($this->registry->get('request')->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->registry->get('request')->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				$this->jc_ajax = true;

				$this->jetcache_buildcache = false;
				$jetcache_headers = getallheaders();
				if (isset($jetcache_headers['JETCACHE_BUILDCACHE'])) {
					$this->jetcache_buildcache = true;
					$this->jc_ajax = false;
				}
			}

		    if (isset($this->registry->get('request')->post['jc_cont_ajax'])) {
            	$this->jc_ajax = true;
		    }

			if (defined('HTTP_CATALOG')) {
				$this->registry->set('admin_work', true);
				$this->jc_ajax = true;
			}

			if ($this->config->get('ascp_settings') != '') {
				$this->seocms_settings = $this->config->get('ascp_settings');
			} else {
				$this->seocms_settings = Array();
			}
			$this->jetcache_settings = $this->config->get('asc_jetcache_settings');

	        $this->setOutputRegistry($this->registry);

			if (isset($this->session->data['customer_id'])) {
	        	$customer_id = $this->session->data['customer_id'];
			} else {
				$customer_id = 0;
			}
            if (SC_VERSION > 20) {
				$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$customer_id . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

	            if ($cart_query->num_rows > 0) {
	                foreach ($cart_query->rows as $row) {
	                	$customer_id_cart[] = $row;
	                }
	            	$this->config->set('seocms_jetcache_cart', $customer_id_cart);
	            } else {
	            	$this->config->set('seocms_jetcache_cart', array());
	            }
            } else {
				if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
					$this->session->data['cart'] = array();
				}
            	$this->config->set('seocms_jetcache_cart', $this->session->data['cart']);
			}

			if (isset($this->session->data['user_id'])) {
	        	$this->user_id = $this->session->data['user_id'];
			} else {
				$this->user_id = false;
			}

			if (($this->user_id || $this->registry->get('admin_work')) && !$this->jetcache_buildcache) {
				$this->admin_logged = true;
			} else {
				$this->admin_logged = false;
			}

		    if (($this->admin_logged && isset($this->jetcache_settings['jetcache_info_status']) && $this->jetcache_settings['jetcache_info_status']) || (isset($this->jetcache_settings['jetcache_info_demo_status']) && $this->jetcache_settings['jetcache_info_demo_status'])) {
            	if (file_exists(DIR_APPLICATION . 'view/theme/default/stylesheet/jetcache.css')) {
					$this->document->addStyle('catalog/view/theme/default/stylesheet/jetcache.css');
				}
			}

            $this->load->model('jetcache/jetcache');
			if (!$this->config->get('asc_cache_auto_clear') || $this->config->get('asc_cache_auto_clear') == '') {
	             $this->model_jetcache_jetcache->editSetting('asc_cache_auto', array('asc_cache_auto_clear' => time()));
			}

	        if (isset($this->jetcache_settings['cache_auto_clear']) && $this->jetcache_settings['cache_auto_clear'] != '' && $this->config->get('asc_cache_auto_clear') != '' && $this->config->get('asc_cache_auto_clear') > 0 ) {
		        if (((time() - $this->config->get('asc_cache_auto_clear')) / 60 / 60) > $this->jetcache_settings['cache_auto_clear']) {
	             // Clear all cache
	             // Save current time to setting - asc_cache_auto_clear
	             $this->model_jetcache_jetcache->editSetting('asc_cache_auto', array('asc_cache_auto_clear' => time()));
	             $this->cacheremove('noaccess');
		        }
			}

	        return true;
        } else {
        	return false;
        }
	}

	public function setOutputRegistry($registry) {
		if (is_callable(array('Response', 'jetcache_setRegistry'))) {
			$this->response->jetcache_setRegistry($registry);
		}
		if (is_callable(array('DB', 'jc_setRegistry'))) {
			$this->db->jc_setRegistry($registry);
		}
		/* No, because override in agooCache
		if (is_callable(array('Cache', 'jetcache_setRegistry'))) {
			$this->cache->jetcache_setRegistry($registry);
		}
		*/
    }

	public function jc_lazy($output) {

		if (!$this->jc_ajax) {

			if (isset($this->jetcache_settings['lazy_tokens']) && $this->jetcache_settings['lazy_tokens'] != '') {

				if (isset($this->registry->get('request')->get['route'])) {
					$route = $this->registry->get('request')->get['route'];
				} else {
					$route = 'common/home';
				}

                $access_status = true;
				if (isset($this->jetcache_settings['lazy_ex_route']) && $this->jetcache_settings['lazy_ex_route'] != '') {

					$lazy_ex_route_array = explode(PHP_EOL, trim($this->jetcache_settings['lazy_ex_route']));

				    foreach($lazy_ex_route_array as $num => $lazy_ex_route) {
				    	$lazy_ex_route = trim($lazy_ex_route);
						if ($lazy_ex_route[0] != '#' && $lazy_ex_route != '' && strpos($route, $lazy_ex_route) !== false) {
							$access_status = false;
						}
				    }
				}

                if ($access_status) {

					$jc_lazy_js = '<script>
document.addEventListener("DOMContentLoaded",function(){function a(){c=[].slice.call(document.querySelectorAll("img[data-src]"));for(var a=window.pageYOffset,d=0;d<c.length;d++)jc_img=c[d],jc_img.getBoundingClientRect().top<=window.innerHeight&&0<=jc_img.getBoundingClientRect().bottom&&"none"!==getComputedStyle(jc_img).display&&"undefined"!=jc_img.dataset.src&&(jc_img.src=jc_img.dataset.src,delete jc_img.dataset.src);0==c.length&&(document.removeEventListener("scroll",b),window.removeEventListener("resize",b),window.removeEventListener("orientationChange",b))}function b(){d&&clearTimeout(d),d=setTimeout(a(),10)}var c,d;a(),document.addEventListener("scroll",b),window.addEventListener("resize",b),window.addEventListener("orientationChange",b),window.addEventListener("DOMNodeInserted",function(){d=setTimeout(a(),100)})});
</script>';
					$lazy_tokens_array = explode(PHP_EOL, trim($this->jetcache_settings['lazy_tokens']));
				    foreach($lazy_tokens_array as $num => $lazy_token) {
				    	$lazy_token = trim($lazy_token);
						if ($lazy_token[0] != '#' && $lazy_token != '' && strpos($lazy_token, '|') !== false) {
		                	$tokens_array = explode('|', trim($lazy_token));
		                	$output = str_ireplace(html_entity_decode(trim($tokens_array[0]), ENT_QUOTES, 'UTF-8'), html_entity_decode(trim($tokens_array[1]), ENT_QUOTES, 'UTF-8'), $output);
						}
				    }
					$output = str_ireplace('</head>', $jc_lazy_js . '
</head>', $output);
				}
			}
		}
		return $output;
	}

	public function jetcache_minify($output) {
		if ($this->cache_access_output()) {
			$output = $this->jetcache_minify_css($output);
			$output = $this->jetcache_minify_js($output);
			$output = $this->jetcache_minify_html($output);
		}
		return $output;
	}

	private function jetcache_minify_html($output) {
		if (isset($this->jetcache_settings['minify_html_status']) && $this->jetcache_settings['minify_html_status']) {
			if (isset($this->registry->get('request')->get['route'])) {
				$route = $this->registry->get('request')->get['route'];
			} else {
				$route = 'common/home';
			}

			$access_status = true;
			if (isset($this->jetcache_settings['minify_html_ex_route']) && $this->jetcache_settings['minify_html_ex_route'] != '') {

				$minify_html_ex_route_array = explode(PHP_EOL, trim($this->jetcache_settings['minify_html_ex_route']));

			    foreach($minify_html_ex_route_array as $num => $minify_html_ex_route) {
			    	$minify_html_ex_route = trim($minify_html_ex_route);
					if ($minify_html_ex_route[0] != '#' && $minify_html_ex_route != '' && strpos($route, $minify_html_ex_route) !== false) {
						$access_status = false;
					}
			    }
			}
	        if ($access_status) {
		    	/*
		    	$output = preg_replace('/(?![^<]*<\/pre>)[\n\r\t]+/', "\n", $output);
				$output = preg_replace('/ {2,}/', ' ', $output);
				$output = preg_replace('/>[\n]+/', '>', $output);
				*/
				if (file_exists(DIR_SYSTEM . 'library/jetcache/minify/html/html.php')) {
					if (function_exists('modification')) {
						require_once(modification(DIR_SYSTEM . 'library/jetcache/minify/html/html.php'));
					} else {
						require_once(DIR_SYSTEM . 'library/jetcache/minify/html/html.php');
					}
				}

			    $jc_minify_html = new jc_Minify_HTML($output, array(
			        'jsCleanComments' => true
			    	)
				);

			    $output = $jc_minify_html->process();
		    }
		}
		return $output;
	}

	private function jetcache_minify_css($output) {
		if (isset($this->jetcache_settings['minify_css_status']) && $this->jetcache_settings['minify_css_status']) {
		}
		return $output;
	}

	private function jetcache_minify_js($output) {
		if (isset($this->jetcache_settings['minify_js_status']) && $this->jetcache_settings['minify_js_status']) {
		}
		return $output;
	}

	public function hook_Registry_get() {
		if ($this->registry->get('seocms_cache_status') && !$this->flag_cache_access_output) {

			if (!$this->registry->get('hook_Registry_get')) {
	            $this->page_from_cache();
	            if (isset($this->jetcache_settings['pages_forsage']) && $this->jetcache_settings['pages_forsage']) {
    	        	$this->registry->set('hook_Registry_get', true);
    	        }
            }
		}
	}

	public function page_from_cache() {

		if ($this->jetcache_settings['jetcache_widget_status'] && $this->jetcache_settings['pages_status']) {

			$settings_name = array();

			$this->set_cache_name($settings_name);

			$this->registry->set('jetcache_page_filename', $this->sc_cache_name);

			if (!$this->config->get('blog_work')) {
				$this->config->set('blog_work', true);
			}

 			$cache_content = $this->cache->get($this->sc_cache_name);





			if ($this->config->get('blog_work')) {
				$this->config->set('blog_work', false);
			}

			if (!$cache_content) {
				return;
			} else {
				$this->registry->set('jetcache_page_content', true);
			}


            $cache_filename = $this->registry->get('jetcache_cache_filename');



			if (isset($cache_content['output']) && $cache_content['output'] != '' && !$this->registry->get('jetcache_response_set_cache')) {

                if (!isset($cache_content['route'])) {
                	return;
                }


				$this->registry->get('request')->get['route'] = $cache_content['route'];

				$this->flag_cache_access_output = $this->cache_access_output();

				if (!$this->flag_cache_access_output) {
					return;
				}

				$jetcache_content = $cache_content['output'];
			    $jetcache_headers = $cache_content['headers'];
			    $jetcache_time = $cache_content['time'];
			    $jetcache_queries = $cache_content['queries'];
			    $jetcache_filename = $cache_content['filename'];

				if (!empty($jetcache_headers)) {
					$jetcache_headers_now = $this->response->jetcache_getHeaders();

					foreach ($jetcache_headers as $jc_header) {
			    		$jetcache_headers_now_exists = false;
			    		foreach ($jetcache_headers_now as $jc_header_now) {
			    			if ($jc_header == $jc_header_now) {
			    				$jetcache_headers_now_exists = true;
			    				break;
			    			}
			    		}
			    		if (!$jetcache_headers_now_exists) {
			    			$this->response->addHeader($jc_header);
			    		}
					}
			    }
                // in common/footer
                $this->addOnline();

                if (isset($this->registry->get('request')->get['product_id'])) {
                	$this->updateViewed();
                }

       			if ((isset($this->registry->get('request')->get['record_id']) && isset($this->registry->get('request')->get['route']) && $this->registry->get('request')->get['route'] == 'record/record') ||
                   	(isset($this->registry->get('request')->get['blog_id']) && isset($this->registry->get('request')->get['route']) && $this->registry->get('request')->get['route'] == 'record/blog'))
                {
					if (isset($this->registry->get('request')->get['record_id'])) {
						$this->countRecordUpdate();
					}
					if ($this->checkAccess()) {
						$this->response->addHeader($this->registry->get('request')->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
					} else {
						$this->response->addHeader($this->registry->get('request')->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
					}
				}

			    if (($this->admin_logged && isset($this->jetcache_settings['jetcache_info_status']) && $this->jetcache_settings['jetcache_info_status']) || (isset($this->jetcache_settings['jetcache_info_demo_status']) && $this->jetcache_settings['jetcache_info_demo_status']) ) {
					//if (isset($GLOBALS['jetcache_opencart_core_start']))  {
					//	$time_visual['start'] = $GLOBALS['jetcache_opencart_core_start'];
				//	} else {
						$time_visual['start'] = $this->registry->get('sc_time_start');
					//}

				    $time_visual['end'] = microtime(true);
				    $time_visual['load'] = $jetcache_time;
				    $time_visual['queries'] = $jetcache_queries;
				    $time_visual['filename'] = $jetcache_filename;

					$this->registry->set('jetcache_output_visual', $time_visual);
				}

				if (isset($this->jetcache_settings['cachecontrol_status']) && $this->jetcache_settings['cachecontrol_status']) {
				    //header('Cache-Control:public, max-age=31536000');
				    $this->response->addHeader('Cache-Control:public, max-age=31536000');
				}

				if (isset($this->jetcache_settings['expires_status']) && $this->jetcache_settings['expires_status']) {
					//header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 604800));
					$this->response->addHeader('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 604800));
				}

                if (isset($this->jetcache_settings['lastmod_status']) && $this->jetcache_settings['lastmod_status'] && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && file_exists($cache_filename)) {

	                $cache_filemtime = filemtime($cache_filename);
                    $status_304 = true;

			        if ($status_304 && !empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $cache_filemtime) {
			            //RewriteRule ^(.*)$ $1 [E=HTTP_IF_MODIFIED_SINCE:%{HTTP:If-Modified-Since}]
			            //header('HTTP/1.1 304 Not Modified');
                        $this->response->addHeader($this->registry->get('request')->server['SERVER_PROTOCOL'] . '/1.1 304 Not Modified');
			        }

		            //header('Last-Modified: '.gmdate('D, d M Y H:i:s \G\M\T', $cache_filemtime));
		            $this->response->addHeader('Last-Modified: '.gmdate('D, d M Y H:i:s \G\M\T', $cache_filemtime));

		        }

				$this->response->setOutput($jetcache_content);

				if ($this->config->get('config_compression') > 0) {
					$this->response->setCompression($this->config->get('config_compression'));
				}

                $this->registry->set('page_fromcache', true);

				$this->response->output();
				exit();
			}

		}
	}


	public function page_to_cache() {
        if ($this->jetcache_settings['jetcache_widget_status'] && $this->jetcache_settings['pages_status'] && !$this->registry->get('page_fromcache')) {

			if (!$this->registry->get('jetcache_page_content') && $this->cache_access_output()) {

	            $settings_name = array();

				if ($this->registry->get('seocms_cache_status')) {

					$cache_output = $this->response->jetcache_getOutput();

					$cache_headers = $this->response->jetcache_getHeaders();

					if (!$this->config->get('blog_work')) {
						$this->config->set('blog_work', true);
						$off_blog_work = true;
					} else {
						$off_blog_work = false;
					}

					if (isset($cache_output) && is_string($cache_output) && $cache_output != '') {

						if ($this->registry->get('jetcache_page_filename')) {
	        				$this->sc_cache_name = $this->registry->get('jetcache_page_filename');
				        } else {
							$this->set_cache_name($settings_name);
						}

					    $sc_time_end = microtime(true);

					    if (isset($GLOBALS['jetcache_opencart_core_start']))  {
							$cache['time'] = $sc_time_end - $GLOBALS['jetcache_opencart_core_start'];
						} else {
							$cache['time'] = $sc_time_end - $this->registry->get('sc_time_start');
						}

					    if (is_callable(array('DB', 'get_sc_jetcache_query_count'))) {
					    	$cache['queries'] = $this->db->get_sc_jetcache_query_count();
					    } else {
					    	$cache['queries'] = '';
					    }

						if (isset($this->registry->get('request')->get['route'])) {
							$cache['route'] = $this->registry->get('request')->get['route'];
						} else {
							$cache['route'] = 'common/home';
						}
					    $cache['filename'] = $this->sc_cache_name;
					    $cache['headers'] = $cache_headers;
					    $cache['output'] = $cache_output;

					    $this->cache->set($this->sc_cache_name, $cache);

                        if (isset($this->jetcache_settings['edit_product_id']) && $this->jetcache_settings['edit_product_id']) {
							$jetcache_product_id_pages = $this->registry->get('jetcache_product_id_pages');;
				        	if (!empty($jetcache_product_id_pages)) {
					        	$product_id_array = $jetcache_product_id_pages;
					        	foreach ($jetcache_product_id_pages as $product_id => $array_product_id) {
				             		$product_id_array[$product_id][$this->sc_cache_name] = $this->sc_cache_name;
					        	}
					        	$this->registry->set('jetcache_product_id_pages', $product_id_array);
				        	}
			        	}

					 	$this->jetcache_product_id_update('pages');

					 	$this->registry->set('jetcache_product_id_pages', array());
					}

					if ($off_blog_work) {
						$this->config->set('blog_work', false);
					}
					$this->registry->set('jetcache_response_set_cache', true);

				}
	       	}
       	}
	}




	public function cache_access_output($query = false) {

        $access_status = false;
        $home = false;

        if ($this->jc_ajax) {
        	$this->jetcache_settings['jetcache_widget_status'] = false;
        	return $access_status = false;
        }

		if (!isset($this->registry->get('request')->get['route'])) {
			if (!isset($this->registry->get('request')->get['_route_'])) {
            	$home = true;
			}
		}

		if (isset($this->registry->get('request')->get['record_id']) && isset($this->registry->get('request')->get['blog_id'])) {
			unset($this->registry->get('request')->get['blog_id']);
		}

		if ($this->jc_ajax) {
			if (!$this->jetcache_buildcache) {
				return $access_status = false;
			}
		}

		if (isset($this->registry->get('request')->server['REQUEST_METHOD']) && $this->registry->get('request')->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->jetcache_buildcache) {
				return $access_status = false;
			}
		}

		if ($home || (isset($this->registry->get('request')->get['route']) && $this->registry->get('request')->get['route'] != 'error/not_found')) {

	      	if (isset($this->jetcache_settings['store']) && in_array($this->config->get('config_store_id'), $this->jetcache_settings['store'])) {
	       		$access_status = true;
	      	} else {
				return $access_status = false;
			}

			if (isset($this->jetcache_settings['jetcache_widget_status']) && $this->jetcache_settings['jetcache_widget_status']) {
				$access_status = true;
			} else {
				return $access_status = false;
			}

			if (isset($this->jetcache_settings['pages_status']) && $this->jetcache_settings['pages_status']) {
				$access_status = true;
			} else {
				if (!$query) {
					return $access_status = false;
				}
			}

			if (!$this->registry->get('admin_work')) {
	        	$access_status = true;
			} else {
	       		//if (!$this->jetcache_buildcache) {
	       			return $access_status = false;
	       		//}
			}
			$access_status = $this->access_exeptions();

        }

		return $access_status;
	}




	public function info() {

        if ($this->access_exeptions()) {

	    	if ($this->registry->get('seocms_cache_status')) {
	    		$visual_html = '';

				if (!$this->jc_ajax) {

			       	if (($this->admin_logged && isset($this->jetcache_settings['jetcache_info_status']) && $this->jetcache_settings['jetcache_info_status']) || (isset($this->jetcache_settings['jetcache_info_demo_status']) && $this->jetcache_settings['jetcache_info_demo_status']) ) {

						if (is_array($this->registry->get('jetcache_output_visual'))) {
					        $time_visual = $this->registry->get('jetcache_output_visual');
							$visual_html = $this->visual($time_visual);
						} else {

						   // if (isset($GLOBALS['jetcache_opencart_core_start']))  {
							//	$time_visual['start'] = $GLOBALS['jetcache_opencart_core_start'];
						//	} else {
								$time_visual['start'] = $this->registry->get('sc_time_start');
						//	}

					        $time_visual['end'] = microtime(true);

							if (isset($GLOBALS['jetcache_opencart_core_start']))  {
								$time_visual['load'] = round($time_visual['end'] - $GLOBALS['jetcache_opencart_core_start'], 3);
							} else {
								$time_visual['load'] = round($time_visual['end'] - $time_visual['start'], 3);
							}

					        if (is_callable(array('DB', 'jc_setRegistry'))) {
					        	$time_visual['queries'] = $this->db->get_sc_jetcache_query_count();
					        } else {
					        	$time_visual['queries'] = '';
					        }
							$visual_html = $this->visual($time_visual);
						}
					}
				}
	        	return $visual_html;
	        }
       	} else {
       		return NULL;
       	}
	}

	public function visual($arg) {

        if (SC_VERSION > 20) {
        	$this->registry->get('load')->language('jetcache/jetcache');
        } else {
        	$this->registry->get('language')->load('jetcache/jetcache');
        }

        if (SC_VERSION > 15) {
			if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
		       	$this->url_link_ssl = true;
	        } else {
	        	$this->url_link_ssl = false;
	        }
        } else {
        	$this->url_link_ssl = 'SSL';
        }
        $this->data['jetcache_url_cache_remove'] = $this->url->link('jetcache/jetcache/cacheremove', '', $this->url_link_ssl);
        $this->data['icon'] = getSCWebDir(DIR_IMAGE).'jetcache/jetcache-icon.png';

        $html = '';

        $this->data['load'] = $arg['load'];
        $this->data['start'] = $arg['start'];
        $this->data['end'] = $arg['end'];
        $this->data['queries'] = $arg['queries'];
        if (isset($arg['filename'])) {
        	$this->data['filename'] = $arg['filename'];
        } else {
        	$this->data['filename'] = '';
        }

        $this->data['cache'] = round($arg['end'] - $arg['start'], 3);

        if (isset($GLOBALS['jetcache_opencart_core_start']))  {
			$this->data['cache_all'] = round($arg['end'] - $GLOBALS['jetcache_opencart_core_start'], 3);
		} else {
			$this->data['cache_all'] = 0;
		}

        $this->data['rate'] = round($this->data['load'] / $this->data['cache'], 0);

		$this->data['url_jetcache_buy'] = $this->language->get('url_jetcache_buy');
        $this->data['entry_jetcache'] = $this->language->get('entry_jetcache');
        $this->data['entry_jetcache_buy'] = $this->language->get('entry_jetcache_buy');
        $this->data['text_jetcache_loading'] = $this->language->get('text_jetcache_loading');
        $this->data['text_jetcache_cache_remove_fail'] = $this->language->get('text_jetcache_cache_remove_fail');
        $this->data['text_jetcache_url_cache_remove'] = $this->language->get('text_jetcache_url_cache_remove');
        $this->data['entry_jetcache_db'] = $this->language->get('entry_jetcache_db');
        $this->data['text_jetcache_queries'] = $this->language->get('text_jetcache_queries');
        $this->data['entry_jetcache_queries'] = $this->language->get('entry_jetcache_queries');
        $this->data['entry_jetcache_queries_cache'] = $this->language->get('entry_jetcache_queries_cache');
        $this->data['entry_queries_count_cache'] = $this->language->get('entry_queries_count_cache');
        $this->data['entry_queries_time_cache'] = $this->language->get('entry_queries_time_cache');
        $this->data['entry_jetcache_sec'] = $this->language->get('entry_jetcache_sec');
        $this->data['entry_jetcache_opencart_core'] = $this->language->get('entry_jetcache_opencart_core');
        $this->data['entry_jetcache_sec'] = $this->language->get('entry_jetcache_sec');
        $this->data['entry_jetcache_pages'] = $this->language->get('entry_jetcache_pages');
        $this->data['entry_jetcache_withoutcache'] = $this->language->get('entry_jetcache_withoutcache');
        $this->data['entry_jetcache_cache'] = $this->language->get('entry_jetcache_cache');
        $this->data['entry_jetcache_sec'] = $this->language->get('entry_jetcache_sec');

        $this->data['entry_count_model_cached'] = $this->language->get('entry_count_model_cached');
        $this->data['count_model_cached'] = $this->count_model_cached;

        $this->data['entry_count_query_cached'] = $this->language->get('entry_count_query_cached');
        $this->data['count_query_cached'] = $this->count_query_cached;

        $this->data['entry_count_cont_cached'] = $this->language->get('entry_count_cont_cached');
        $this->data['count_cont_cached'] = $this->count_cont_cached;

        if (is_callable(array('DB', 'get_sc_jetcache_query_count'))) {
        	$this->data['queries_cache'] = $this->db->get_sc_jetcache_query_count();
        } else {
        	$this->data['queries_cache'] = '';
        }

        if (is_callable(array('DB', 'get_sc_jetcache_query_count_cache'))) {
        	$this->data['queries_count_cache'] = $this->db->get_sc_jetcache_query_count_cache();
        } else {
        	$this->data['queries_count_cache'] = '';
        }
        if (is_callable(array('DB', 'get_sc_jetcache_query_time_cache'))) {
        	$this->data['queries_time_cache'] = $this->db->get_sc_jetcache_query_time_cache();
        } else {
        	$this->data['queries_time_cache'] = '';
        }

        if ($this->registry->get('jetcache_opencart_core')) {
        	$this->data['jetcache_opencart_core'] = $this->registry->get('jetcache_opencart_core');
        } else {
        	$this->data['jetcache_opencart_core'] = '';
        }
        if ($this->data['queries_cache'] > 0) {
        	$this->data['round_queries_queries_cache'] = round($this->data['queries'] / $this->data['queries_cache'], 0);
        } else {
        	$this->data['round_queries_queries_cache'] = 0;

        }
        $this->data['round_queries'] = round($this->data['queries'], 3);
        $this->data['round_queries_cache'] = round($this->data['queries_cache'], 3);
        $this->data['round_queries_count_cache'] = round($this->data['queries_count_cache'], 5);
        $this->data['round_queries_time_cache'] = round($this->data['queries_time_cache'], 3);
        $this->data['round_jetcache_opencart_core'] = round($this->data['jetcache_opencart_core'], 3);
		$this->data['round_load'] = round($this->data['load'], 3);
		$this->data['round_cache'] = round($this->data['cache'], 3);
		$this->data['round_cache_all'] = round($this->data['cache_all'], 3);

        if (($this->data['round_queries'] > $this->data['round_queries_cache']) && $this->data['count_query_cached'] == 0) {
        	$this->data['count_query_cached'] = $this->data['round_queries'] - $this->data['round_queries_cache'];
        	$this->data['entry_count_query_cached'] = $this->language->get('entry_jetcache_queries_cached');
        }

        $this_template = 'default';
        $this->data['language'] = $this->language;

        $template = 'agootemplates/jetcache/visual';

        if (SC_VERSION < 30) {
			$template = $template . '.tpl';
			$file_ext = '';
		} else {
			$file_ext = '.tpl';
		}

		if (file_exists(DIR_TEMPLATE . $this_template . '/template/'. $template . $file_ext) && is_file(DIR_TEMPLATE . $this_template . '/template/'. $template . $file_ext)) {
			$this->template = $template;
			if (SC_VERSION < 22) {
				$this->template = $this_template . '/template/'. $template;
			}
			if ($this->registry->get('page_fromcache') && SC_VERSION > 23)  {
				$this->template = $this_template . '/template/' . $template;
			}
		}

		if (SC_VERSION < 20) {
			$this->children = array();
			$html = $this->render();
		} else {

			if (SC_VERSION > 23) {
				$this->template_engine = $this->config->get('template_engine');
				$this->config->set('template_engine', 'template');
				if (!$this->registry->get('page_fromcache')) {
					$this->template_directory = $this->config->get('template_directory');
	            	$this->config->set('template_directory', 'default/template/');
	            }

	        }

	        if (!is_array($this->data))	$this->data = array();

			$html = $this->load->view($this->template, $this->data);

			if (SC_VERSION > 23) {
			 	if (!$this->registry->get('page_fromcache')) {
			 		$this->config->set('template_directory', $this->template_directory);
			 	}
				$this->config->set('template_engine', $this->template_engine);
	        }
		}

		return $html;
	}

	public function query_model_access($class_function) {
        $access_status = false;

		if (isset($class_function[1]['class'])) {
	    	$query_model_class = $class_function[1]['class'];
            $query_model_function = $class_function[1]['function'];
		} else {
			$query_model_class = '';
			$query_model_function = '';
		}
        if (!isset($this->jetcache_settings['query_model']) || empty($this->jetcache_settings['query_model'])) {
        	$this->jetcache_settings = $this->config->get('asc_jetcache_settings');
        }

	    foreach($this->jetcache_settings['query_model'] as $query_model) {
	    	if ($query_model['status']) {
	    		if (($query_model['model'] == $query_model_class && $query_model['method'] == '') || ($query_model['model'] == $query_model_class && $query_model['method'] == $query_model_function)) {
	    			return $access_status = true;
	    		}
	    	}
		}

		return $access_status = false;
	}

    public function access_exeptions() {

		if (isset($this->jetcache_settings['ex_route']) && !empty($this->jetcache_settings['ex_route'])) {
			if (isset($this->registry->get('request')->get['route'])) {
				$routes = explode('/', $this->registry->get('request')->get['route']);
			} else {
				$routes = array();
			}
	        $routes_count = count($routes);

		    foreach($this->jetcache_settings['ex_route'] as $ex_route) {
				if ($ex_route['status']) {
		    		$ex_routes = explode('/', $ex_route['route']);
	                $ex_routes_count = count($ex_routes);
					if ($ex_routes_count <= $routes_count) {

	                    $new_array = array();
	                    $prom_array = array();
					    $key_search = array_search('%', $ex_routes);
					    if ($routes_count - $ex_routes_count > 0) {
	                    	$prom_array = array_fill($key_search, $routes_count - $ex_routes_count , '%');
	                    }

	                    array_splice($ex_routes, $key_search, 0, $prom_array);

		                $key = 0;
						foreach ($routes as $routes_val) {
	                    	if ($ex_routes[$key] == '%') {
	                    		$ex_routes[$key] = $routes_val;
	                    	}
							$key++;
		    			}

	                    if ($routes == $ex_routes)  {
	                    	 return $access_status = false;
	                    }
					}
				}
		    }
		}

		if (isset($this->jetcache_settings['ex_uri']) && $this->jetcache_settings['ex_uri'] != '') {

			$ex_uri_array = explode(PHP_EOL, trim($this->jetcache_settings['ex_uri']));

		    foreach($ex_uri_array as $num => $ex_uri) {
		    	$ex_uri = trim($ex_uri);
				if ($ex_uri[0] != '#' && $ex_uri != '' && strpos($this->request_uri_trim, $ex_uri) !== false) {
					return $access_status = false;
				}
		    }
		}

        return true;

    }

	public function addOnline() {
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->registry->get('request')->server['REMOTE_ADDR'])) {
				$ip = $this->registry->get('request')->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->registry->get('request')->server['HTTP_HOST']) && isset($this->registry->get('request')->server['REQUEST_URI'])) {
				$url = $this->url_protocol . '://' . $this->registry->get('request')->server['HTTP_HOST'] . $this->registry->get('request')->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->registry->get('request')->server['HTTP_REFERER'])) {
				$referer = $this->registry->get('request')->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}
            if (SC_VERSION > 20) {
				$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
			} else {
				$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
			}
		}
	}

    public function updateViewed() {
    	$this->load->model('catalog/product');
    	$this->model_catalog_product->updateViewed((int)$this->registry->get('request')->get['product_id']);
    }


	public function model_method_access($model_model_class, $model_model_function = '') {
        $access_status = false;
		if (isset($this->jetcache_settings['jetcache_model_status']) && !$this->jetcache_settings['jetcache_model_status']) return $access_status;
        if (isset($this->jetcache_settings['model']) && !empty($this->jetcache_settings['model'])) {
	        foreach($this->jetcache_settings['model'] as $number => $model_model) {
	        	if ($model_model['status']) {
	        		if ((strtolower($model_model['model']) == strtolower($model_model_class) && (strtolower($model_model['method']) == '' || $model_model_function == '')) || (strtolower($model_model['model']) == strtolower($model_model_class) && strtolower($model_model['method']) == strtolower($model_model_function))) {
	        			return $access_status = $number;
	        		}
	        	}
			}
		}
		return $access_status = false;
	}

    public function model_to_cache($output, $route, $method, $args) {

			if ($this->jetcache_settings['jetcache_model_status']) {
				$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', substr($route, 0, strrpos($route, '/')));
	            $settings = $this->model_method_access($class, $method);

				if ($settings !== false) {

					if ($this->cache_access_output(true)) {

		                $setting = $this->jetcache_settings['model'][$settings];

						$settings_name['type'] = 'model';
						$settings_name['route'] = str_replace('/', '_', strtolower($route));
						$settings_name['setting'] = $args;
						$settings_name['tuning'] = $setting;

						$this->set_cache_name($settings_name);

						if (!$this->config->get('blog_work')) {
							$this->config->set('blog_work', true);
							$off_blog_work = true;
						} else {
							$off_blog_work = false;
						}

		                if (isset($settings_name['tuning']['onefile']) && $settings_name['tuning']['onefile']) {
		                	$cache_data = $this->cache->get($this->sc_cache_name);
		                	$hache_args = md5($this->sc_cache_name.(var_export($args, true)));
		                    $cache_data[$hache_args] = $output;
		                    $output = $cache_data;
		                }

						$this->cache->set($this->sc_cache_name, $output);

                        $this->jetcache_product_id_update('model');
                        $this->registry->set('jetcache_product_id_model', array());

						if ($off_blog_work) {
							$this->config->set('blog_work', false);
						}

						return true;
					} else  {
						return false;
					}

		    	} else {
			    	return false;
		    	}

	    	}
    	return false;
    }


    public function model_from_cache($route, $method, $args) {

		if ($this->jetcache_settings['jetcache_model_status']) {
			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', substr($route, 0, strrpos($route, '/')));
	        $settings = $this->model_method_access($class, $method);

			if ($settings !== false) {

				if ($this->cache_access_output(true)) {
                   // $this->registry->set('jetcache_product_id_model', array());

		            $setting = $this->jetcache_settings['model'][$settings];

					$settings_name['type'] = 'model';
					$settings_name['route'] = str_replace('/', '_', strtolower($route));
					$settings_name['setting'] = $args;
					$settings_name['tuning'] = $setting;

					$this->set_cache_name($settings_name);

					if (!$this->config->get('blog_work')) {
						$this->config->set('blog_work', true);
						$off_blog_work = true;
					} else {
						$off_blog_work = false;
					}

                    $cache_data = $this->cache->get($this->sc_cache_name);

		            if (isset($settings_name['tuning']['onefile']) && $settings_name['tuning']['onefile']) {
		            	$hache_args = md5($this->sc_cache_name.(var_export($args, true)));
                        if (isset($cache_data[$hache_args])) {
                        	$this->count_model_cached++;
                        	return $cache_data[$hache_args];
                        } else {
                        	return false;
                        }
		            }

                    if ($cache_data !== false) {

                    	$this->count_model_cached++;
                    	return $cache_data;
                    } else {
                    	return false;
                    }

					if ($off_blog_work) {
						$this->config->set('blog_work', false);
					}

				} else  {
					return false;
				}

			} else {
		    	return false;
			}

	    }
    	return false;
    }



	public function writeLog() {

		if (((isset($this->jetcache_settings['query_log_status']) && $this->jetcache_settings['query_log_status']) ||
			(isset($this->jetcache_settings['cont_log_status']) && $this->jetcache_settings['cont_log_status']) ||
			(isset($this->jetcache_settings['session_log_status']) && $this->jetcache_settings['session_log_status'])) && $this->user_id
		) {
				if (isset($GLOBALS['jetcache_opencart_core_start']))  {
					$time_start = $GLOBALS['jetcache_opencart_core_start'];
				} else {
					$time_start = $this->registry->get('sc_time_start');
				}
				if ($this->jc_ajax) {
					$ajax_status = 'AJAX';
				} else {
					$ajax_status = '';
				}
				$uri = $this->registry->get('request')->server['REQUEST_URI'];

		        if (SC_VERSION > 20) {
		        	$this->registry->get('load')->language('jetcache/jetcache');
		        } else {
		        	$this->registry->get('language')->load('jetcache/jetcache');
		        }

				if (is_callable(array('DB', 'get_sc_jetcache_db_log'))) {
			      	if (isset($this->jetcache_settings['query_log_file']) && $this->jetcache_settings['query_log_file'] != '' && isset($this->jetcache_settings['query_log_status']) && $this->jetcache_settings['query_log_status']) {
						$jc_db_log = $this->db->get_sc_jetcache_db_log();

					    $jc_log_file = $this->jetcache_settings['query_log_file'];

						$entry_jetcache_page = $this->language->get('entry_jetcache_page');
						$entry_jetcache_page_source = $this->language->get('entry_jetcache_page_source');
						$entry_jetcache_page_time = $this->language->get('entry_jetcache_page_time');
						$entry_jetcache_query_time = $this->language->get('entry_jetcache_query_time');
						$entry_jetcache_query_number = $this->language->get('entry_jetcache_query_number');
						$healthy = array('{Page}', '{Source}', '{Query time}', '{Page time}', '{Query number}');
						$yummy = array($entry_jetcache_page, $entry_jetcache_page_source, $entry_jetcache_page_time, $entry_jetcache_query_time, $entry_jetcache_query_number);
						$jc_db_log = str_replace($healthy, $yummy, $jc_db_log);

					    file_put_contents(DIR_LOGS . $jc_log_file, $jc_db_log, FILE_APPEND);

					}
				}

				if (isset($this->jetcache_settings['cont_log_status']) && $this->jetcache_settings['cont_log_status']) {
					$cont_log = '';
					if (!empty($this->jc_cont_log)) {
						$jc_cont_log = $this->jc_cont_log;
						foreach ($this->jc_cont_log as $hache => $cont) {
							if (!isset($cont['end'])) {
								$cont['end'] = $cont['start'];
								$cont['time'] = 0;
							}
							if (isset($cont['cache']) && $cont['cache']) {
								$from_cache = ' ' . $this->language->get('text_log_cache');
							} else {
								$from_cache = '';
							}

							if (isset($this->jetcache_settings['cont_log_maxtime']) && $this->jetcache_settings['cont_log_maxtime'] != '' && $cont['time'] >= $this->jetcache_settings['cont_log_maxtime']) {
								$cont_log = $cont_log . $ajax_status . '*************************************** ' . $uri . ' ***************************************' . PHP_EOL;
				                $cont_log = $cont_log . $this->language->get('text_log_route') . $from_cache . ': ' . $cont['route'] . PHP_EOL;
				                foreach ($jc_cont_log as $jc_hache => $jc_cont) {
				                	if (!isset($jc_cont['end'])) {
										$jc_cont['end'] = $jc_cont['start'];
										$jc_cont['time'] = 0;
									}
				                	if (!isset($jc_cont['start'])) {
										$jc_cont['start'] = $cont['start'] = 0;
										$jc_cont['time'] = 0;
									}

				                	if ((($jc_cont['end'] - $time_start) > ($cont['end']  - $time_start)) && (($jc_cont['start'] - $time_start) < ($cont['start'] - $time_start))) {
				                		$cont_log = $cont_log . $this->language->get('text_log_child') . ': ' . $jc_cont['route'] . PHP_EOL;
				                	}
				                }
				                $cont_log = $cont_log . PHP_EOL . $this->language->get('text_log_start') . ': ' . round($cont['start'] - $time_start, 5) . PHP_EOL;
				                $cont_log = $cont_log . $this->language->get('text_log_end') . ': ' . round($cont['end'] - $time_start, 5) . PHP_EOL;
				                $cont_log = $cont_log . $this->language->get('text_log_time') . ': ' . round($cont['time'], 5) . PHP_EOL . PHP_EOL;
				                if ($cont['data'] != '[]') {
				                	$cont_log = $cont_log . $this->language->get('text_log_data') . ': ' . $cont['data'] . PHP_EOL . PHP_EOL;
				                }
							}
						}
					}

		        	if (isset($this->jetcache_settings['cont_log_file']) && $this->jetcache_settings['cont_log_file'] != '') {
		            	file_put_contents(DIR_LOGS . $this->jetcache_settings['cont_log_file'], htmlspecialchars($cont_log) . PHP_EOL, FILE_APPEND);
		        	}
				}

				if (isset($this->jetcache_settings['session_log_status']) && $this->jetcache_settings['session_log_status']) {
		        	if (isset($this->jetcache_settings['session_log_file']) && $this->jetcache_settings['session_log_file'] != '') {
		            	file_put_contents(DIR_LOGS . $this->jetcache_settings['session_log_file'], htmlspecialchars(json_encode($this->session->data)) . PHP_EOL, FILE_APPEND);
		        	} else {
		        		$this->log->write('SESSION: ' . json_encode($this->session->data).PHP_EOL);
		        	}
				}
		}
	}

	private function countRecordUpdate() {
		$msql = "UPDATE `" . DB_PREFIX . "record` SET `viewed`=`viewed` + 1 WHERE `record_id`='" . (int) ($this->db->escape($this->registry->get('request')->get['record_id'])) . "'";
		$this->db->query($msql);
	}


	private function checkAccess() {
		$check = false;
		if (isset($this->seocms_settings['latest_widget_status']) && $this->seocms_settings['latest_widget_status']) {
			if (!$this->config->get('ascp_customer_groups')) {
				agoo_cont('record/customer', $this->registry);
				$data = $this->controller_record_customer->customer_groups($this->seocms_settings);
				$this->config->set('ascp_customer_groups', $data['customer_groups']);
			} else {
				$data['customer_groups'] = $this->config->get('ascp_customer_groups');
			}

			if (isset($this->registry->get('request')->get['record_id']) && isset($this->registry->get('request')->get['route']) && $this->registry->get('request')->get['route'] == 'record/record') {
	   			$this->load->model('record/record');
				//$this->load->model('record/blog');

				$record_info = $this->model_record_record->getRecord($this->registry->get('request')->get['record_id']);
				if ($record_info) {
					$check = true;
				} else {
					$check = false;
				}
			}
			if (isset($this->registry->get('request')->get['blog_id']) && isset($this->registry->get('request')->get['route']) && $this->registry->get('request')->get['route'] == 'record/blog') {
	   			//$this->load->model('record/record');
				$this->load->model('record/blog');
				$blog_info = $this->model_record_blog->getBlog($this->registry->get('request')->get['blog_id']);
				if ($blog_info) {
					$check = true;
				} else {
					$check = false;
				}
			}
		}
		return $check;
	}

    public function query_to_cache($cache_output, $cont_route, $cont_setting = '' ) {
    	if (is_string($cont_route)) {

			$settings_name['type'] = 'query';
			$settings_name['route'] = str_replace('/', '_', $cont_route);
			$settings_name['setting'] = $cont_setting;
			$this->set_cache_name($settings_name);

			if (!$this->config->get('blog_work')) {
				$this->config->set('blog_work', true);
				$off_blog_work = true;
			} else {
				$off_blog_work = false;
			}

	        $this->cache->set($this->sc_cache_name, $cache_output);

			if ($off_blog_work) {
				$this->config->set('blog_work', false);
			}
		}
    }



    public function query_from_cache($cont_route, $cont_setting = '') {
        if (is_string($cont_route)) {

           // $this->registry->set('jetcache_product_id_query', array());

			$settings_name['type'] = 'query';
			$settings_name['route'] = str_replace('/', '_', $cont_route);
			$settings_name['setting'] = $cont_setting;
			$this->set_cache_name($settings_name);

            if (!$this->config->get('blog_work')) {
				$this->config->set('blog_work', true);
				$off_blog_work = true;
			} else {
				$off_blog_work = false;
			}

	        $cache_content = $this->cache->get($this->sc_cache_name);

			if ($off_blog_work) {
				$this->config->set('blog_work', false);
			}

			if (isset($cache_content) && !empty($cache_content)) {
				$this->count_query_cached++;
				return $cache_content;
			}
			return false;
        }
    }


    public function jetcache_cont_access($params) {
		if (isset($this->jetcache_settings['jetcache_widget_status']) && $this->jetcache_settings['jetcache_widget_status'] && (!isset($this->registry->get('request')->server['REQUEST_METHOD'])) || $this->registry->get('request')->server['REQUEST_METHOD'] != 'POST') {
			if (isset($this->jetcache_settings['store']) && in_array($this->config->get('config_store_id'), $this->jetcache_settings['store'])) {
		       	if (isset($this->jetcache_settings['cont_status']) && $this->jetcache_settings['cont_status']) {
			       if (isset($this->jetcache_settings['add_cont']) && !empty($this->jetcache_settings['add_cont'])) {
				       foreach($this->jetcache_settings['add_cont'] as $number => $add_cont) {
	         				if ($params == $add_cont['cont'] && $add_cont['status']) {
	         					$access_status = true;
	         					$this->jetcache_cont_number = $number;
	         					$access_status = $this->access_exeptions();
	         					return $access_status;
	         				}
				       }
			       }
				}
			}
		}
		return false;
    }

    public function cont_from_cache($cont_route, $cont_setting = '') {
        if (is_string($cont_route)) {

        	$this->registry->set('jetcache_product_id_cont', array());

			$settings_name['type'] = 'cont';
			$settings_name['route'] = str_replace('/', '_', $cont_route);
			$settings_name['setting'] = $cont_setting;
			$settings_name['tuning'] = $this->jetcache_settings['add_cont'][$this->jetcache_cont_number];

			$this->set_cache_name($settings_name);

            if (!$this->config->get('blog_work')) {
				$this->config->set('blog_work', true);
				$off_blog_work = true;
			} else {
				$off_blog_work = false;
			}

	        $cache_content = $this->cache->get($this->sc_cache_name);

			if ($off_blog_work) {
				$this->config->set('blog_work', false);
			}

			if (isset($cache_content['output']) && $cache_content['output'] != '') {
				if (isset($cache_content['styles'])) {
					$styles = $cache_content['styles'];
	                foreach($styles as $style => $style_setting) {
	                	$this->document->addStyle($style_setting['href'], $style_setting['rel'], $style_setting['media']);
	                }
				}
				if (isset($cache_content['links'])) {
					$links = $cache_content['links'];
	                foreach($links as $link => $link_setting) {
	                	$this->document->addLink($link_setting['href'], $link_setting['rel']);
	                }
				}
				if (isset($cache_content['scripts'])) {
					$scripts = $cache_content['scripts'];
	                foreach($scripts as $script => $script_position) {
	                	$this->document->addScript($script);
	                }
				}

				if ($cont_route == 'common/footer') {
					$this->addOnline();
				}

				$this->count_cont_cached++;
				return $cache_content['output'];
			} else {
				$this->jc_cont_document['links'] = $this->document->getLinks();
				$this->jc_cont_document['styles'] = $this->document->getStyles();
				$this->jc_cont_document['scripts'] = $this->document->getScripts();

			}

			return false;
        }
    }

    public function cont_to_cache($cache_output, $cont_route, $cont_setting = '' ) {
    	if (is_string($cache_output) && is_string($cont_route)) {

			$settings_name['type'] = 'cont';
			$settings_name['route'] = str_replace('/', '_', $cont_route);
			$settings_name['setting'] = $cont_setting;
			$settings_name['tuning'] = $this->jetcache_settings['add_cont'][$this->jetcache_cont_number];

			$this->set_cache_name($settings_name);

			if (!$this->config->get('blog_work')) {
				$this->config->set('blog_work', true);
				$off_blog_work = true;
			} else {
				$off_blog_work = false;
			}

            $cont_links_new = $this->document->getLinks();
            $cont_links_old = $this->jc_cont_document['links'];
            if (!empty($cont_links_new)) {
            	foreach ($cont_links_new as $link => $options) {
            		if (isset($cont_links_old[$link])) unset($cont_links_new[$link]);
            	}
            	if (!empty($cont_links_new)) {
            		$cache['links'] = $cont_links_new;
            	}
            }

            $cont_styles_new = $this->document->getStyles();
            $cont_styles_old = $this->jc_cont_document['styles'];
            if (!empty($cont_styles_new)) {
            	foreach ($cont_styles_new as $link => $options) {
            		if (isset($cont_styles_old[$link])) unset($cont_styles_new[$link]);
            	}
            	if (!empty($cont_styles_new)) {
            		$cache['styles'] = $cont_styles_new;
            	}
            }

            $cont_scripts_new = $this->document->getScripts();
            $cont_scripts_old = $this->jc_cont_document['scripts'];
            if (!empty($cont_scripts_new)) {
            	foreach ($cont_scripts_new as $link => $options) {
            		if (isset($cont_scripts_old[$link])) unset($cont_scripts_new[$link]);
            	}
            	if (!empty($cont_scripts_new)) {
            		$cache['scripts'] = $cont_scripts_new;
            	}
            }

	        if (isset($settings_name['tuning']['onefile']) && $settings_name['tuning']['onefile']) {
	        	$cache_data = $this->cache->get($this->sc_cache_name);

	        	$hache_args = md5($this->sc_cache_name.(var_export($settings_name['setting'], true)));

	            $cache_data[$hache_args] = $cache_output;
	            $cache_output = $cache_data;
	        }

			$cache['output'] = $cache_output;

	        $this->cache->set($this->sc_cache_name, $cache);

            $this->jetcache_product_id_update('cont');
           // $this->registry->set('jetcache_product_id_cont', array());

			if ($off_blog_work) {
				$this->config->set('blog_work', false);
			}
		}
    }

	private function set_cache_name($settings) {

		if (isset($settings['type'])) {
			$settings_type = $settings['type'];
		} else {
			$settings_type = 'pages';
		}
		if (isset($settings['route'])) {
			$settings_route = $settings['route'];
		} else {
			$settings_route = '';
		}
		if (isset($settings['setting'])) {
			$settings_setting = $settings['setting'];
		} else {
			$settings_setting = array();
		}
		if (isset($settings['tuning'])) {
			$settings_tuning = $settings['tuning'];
		} else {
			$settings_tuning = false;
		}

		$session_data = $this->session->data;

        $ex_session_black_flag = false;
		if (isset($this->jetcache_settings['ex_session_black_status']) && $this->jetcache_settings['ex_session_black_status']) {
			if (isset($this->jetcache_settings['ex_session_black']) && $this->jetcache_settings['ex_session_black'] != '') {
                $ex_session_black_flag = true;
				$ex_session_black_array = explode(PHP_EOL, trim($this->jetcache_settings['ex_session_black']));

			    foreach($session_data as $session_key => $session_parameter) {
		            $del_key = true;
		            foreach($ex_session_black_array as $num => $key) {
						if (trim($key) == trim($session_key)) {
							$del_key = false;
						}
		            }
		            if ($del_key) {
		            	unset($session_data[$session_key]);
		            }
				}
			}
		}

		if ($this->jetcache_buildcache) {
			$save_session = $this->session->data;
			unset($session_data['user_id']);
			unset($session_data['account']);
			unset($session_data[$this->token_name]);
		}

		if (isset($session_data[$this->token_name])) {
			unset($session_data[$this->token_name]);
		}
		if (isset($session_data['captcha'])) {
			unset($session_data['captcha']);
		}
		if (isset($session_data['language_old'])) {
			unset($session_data['language_old']);
		}
		if (isset($session_data['currency_old'])) {
			unset($session_data['currency_old']);
		}

            if ($settings_type != 'query') {

	        	$data_cache['cart'] = $this->config->get('seocms_jetcache_cart');

				if (isset($settings_tuning['no_session']) && $settings_tuning['no_session']) {
					$data_cache['session'] = array();
				} else {
					$data_cache['session'] = $session_data;
				}

				if (isset($settings_tuning['no_getpost']) && $settings_tuning['no_getpost']) {
					$data_cache['get'] = array();
					$data_cache['post'] = array();
				} else {

					$data_cache['get'] = $this->registry->get('request')->get;

					if (isset($this->jetcache_settings['ex_get']) && $this->jetcache_settings['ex_get'] != '') {

						$ex_get_array = explode(PHP_EOL, trim($this->jetcache_settings['ex_get'], PHP_EOL));

						foreach($data_cache['get'] as $data_get_param => $data_get) {
							$data_get_param = trim($data_get_param);
							foreach($ex_get_array as $ex_get) {
				                $ex_get = trim($ex_get);
				                if ($data_get_param == $ex_get) {
				                	unset($data_cache['get'][$ex_get]);
				                }
							}
			       		}
		           	}
					$data_cache['post'] = $this->registry->get('request')->post;
				}

				if (isset($settings_tuning['no_url']) && $settings_tuning['no_url']) {
					$data_cache['url'] = '';
				} else {
					$data_cache['url'] = $this->registry->get('request')->server['HTTP_HOST'] . $this->registry->get('request')->server['REQUEST_URI'];
				}

			}

			if (!empty($settings_setting)) {
				if (isset($settings_tuning['onefile']) && $settings_tuning['onefile']) {
					$settings_setting = array();
				}
				$data_cache['setting'] = md5(json_encode($settings_setting));
			}

	        unset($data_cache['get']['_route_']);

			if ($settings_type != 'query') {

				if (!$ex_session_black_flag && isset($this->jetcache_settings['ex_session']) && $this->jetcache_settings['ex_session'] != '') {

					$ex_session_array = explode (PHP_EOL, trim($this->jetcache_settings['ex_session'], PHP_EOL));

					foreach($data_cache['session'] as $data_session_param => $data_session) {
						$data_session_param = trim($data_session_param);
						foreach($ex_session_array as $ex_session) {
			                $ex_session = trim($ex_session);
			                if ($data_session_param == $ex_session) {
			                	unset($data_cache['session'][$ex_session]);
			                }
						}
		       		}
	           	}
           	}

			if (!empty($settings_setting)) {
				$data_cache['setting'] = md5(var_export($settings_setting, true));
			} else {
				unset($data_cache['setting']);
			}

			$hash = md5(var_export($data_cache, true));

			$route_name = '';
			if (isset($this->registry->get('request')->get['route'])) {
				$route_name .= '_'.str_replace('/', '_', $this->registry->get('request')->get['route']);
			}
			unset($data_cache);

			if ($settings_route == '' || (isset($settings_tuning['no_route']) && $settings_tuning['no_route'])) {
				$settings_route = '_';
				if (isset($settings_tuning['no_route']) && $settings_tuning['no_route']) {
					$route_name = '';
				}
			}

            $lang_store_name = $this->config->get('config_language_id').'_'.$this->config->get('config_store_id');

            if ($settings_type == 'categories') {
            	$route_name = $settings_route = $hash = '_';
            }

			if (isset($this->jetcache_settings[$settings_type.'_db_status']) && $this->jetcache_settings[$settings_type.'_db_status']) {
	        	$this->sc_cache_name  = 'blog.db.' . $settings_type . '.' . $hash . '.' . $settings_route . $lang_store_name . $route_name;
			} else {
	        	$this->sc_cache_name = 'blog.jetcache_' . $settings_type . '.' . $lang_store_name . $route_name.'.' . $settings_route . '.' . $hash;
			}

			if ($this->jetcache_buildcache) {
				$this->session->data = $save_session;
			}


		if (isset($this->jetcache_settings['edit_product_id']) && $this->jetcache_settings['edit_product_id']) {

            $jetcache_product_id_cont = $this->registry->get('jetcache_product_id_cont');
            $jetcache_product_id_model = $this->registry->get('jetcache_product_id_model');
            $jetcache_product_id_query = $this->registry->get('jetcache_product_id_query');

            /*
        	$jetcache_product_id_pages = $this->registry->get('jetcache_product_id_pages');
        	if ($settings_type == 'pages' && !empty($jetcache_product_id_pages)) {
	        	$product_id_array = $jetcache_product_id_pages;
	        	foreach ($jetcache_product_id_pages as $product_id => $array_product_id) {
             		$product_id_array[$product_id][$this->sc_cache_name] = $this->sc_cache_name;
	        	}
	        	$this->registry->set('jetcache_product_id_pages', $product_id_array);
        	}
        	*/
        	if ($settings_type == 'cont' && !empty($jetcache_product_id_cont)) {
	        	$product_id_array = $jetcache_product_id_cont;
	        	foreach ($jetcache_product_id_cont as $product_id => $array_product_id) {
             		$product_id_array[$product_id][$this->sc_cache_name] = $this->sc_cache_name;
	        	}
	        	$this->registry->set('jetcache_product_id_cont', $product_id_array);
        	}
        	if ($settings_type == 'model' && !empty($jetcache_product_id_model)) {
	        	$product_id_array = $jetcache_product_id_model;
	        	foreach ($jetcache_product_id_model as $product_id => $array_product_id) {
             		$product_id_array[$product_id][$this->sc_cache_name] = $this->sc_cache_name;
	        	}
	        	$this->registry->set('jetcache_product_id_model', $product_id_array);
        	}
        	if ($settings_type == 'query' && !empty($jetcache_product_id_query)) {
	        	$product_id_array = $jetcache_product_id_query;
	        	foreach ($jetcache_product_id_query as $product_id => $array_product_id) {
             		$product_id_array[$product_id][$this->sc_cache_name] = $this->sc_cache_name;
	        	}
	        	$this->registry->set('jetcache_product_id_query', $product_id_array);
        	}

		} else {
			$this->registry->set('jetcache_product_id_pages', array());
			$this->registry->set('jetcache_product_id_cont', array());
			$this->registry->set('jetcache_product_id_model', array());
			$this->registry->set('jetcache_product_id_query', array());
		}

   		return $this->sc_cache_name;
    }


	public function hook_header_categories($categories) {

		if (!$this->config->get('blog_work')) {
			$this->config->set('blog_work', true);
			$off_blog_work = true;
		} else {
			$off_blog_work = false;
		}
		$settings_name['type'] = 'categories';

		$this->set_cache_name($settings_name);

		if (empty($categories)) {
			$categories = $this->cache->get($this->sc_cache_name);
		} else {
			$this->cache->set($this->sc_cache_name, $categories);
		}

		if ($off_blog_work) {
			$this->config->set('blog_work', false);
		}

		return $categories;

	}



	public function hook_maintenance_index() {

	    if (isset($GLOBALS['jetcache_opencart_core_start'])) {
	    	$this->registry->set('jetcache_opencart_core', microtime(true) - $GLOBALS['jetcache_opencart_core_start']);
	    }

		//if (isset($GLOBALS['jetcache_opencart_core_start']))  {
		//	$this->registry->set('sc_time_start', $GLOBALS['jetcache_opencart_core_start']);
		//} else {
			$this->registry->set('sc_time_start', microtime(true));
		//}

		if ($this->config->get('ascp_settings') != '') {
			$seocms_settings = $this->config->get('ascp_settings');
		} else {
			$seocms_settings = Array();
		}

		if ($this->config->get('ascp_settings') != '') {
			$this->jetcache_settings = $this->config->get('asc_jetcache_settings');
		} else {
			$this->jetcache_settings = Array();
		}
        if (!is_callable(array($this->cache, 'json_error'))) {
			$Cache = $this->registry->get('cache');
			$this->registry->set('cache_old', $Cache);
			loadlibrary('agoo/cache');
			$jcCache = new agooCache($this->registry);
			$jcCache->agooconstruct();
			$this->registry->set('cache', $jcCache);
		}

		if (!$this->registry->get('admin_work') && ((
			(isset($this->jetcache_settings['jetcache_widget_status'])) && $this->jetcache_settings['jetcache_widget_status']) ||
			(isset($seocms_settings['cache_pages'])) && $seocms_settings['cache_pages'])
		) {
			$this->registry->set('seocms_cache_status', true);
		}

		if ($this->registry->get('seocms_cache_status') && !$this->registry->get('contstruct_jetcache_loading')) {

			/*
		    if (isset($this->jetcache_settings['seocms_jetcache_alter']) && $this->jetcache_settings['seocms_jetcache_alter'] && is_callable(array('Response', 'jc_setRegistry'))) {
		       	$this->registry->set('seocms_jetcache_alter', true);
		    } else {
		    	$this->registry->set('seocms_jetcache_alter', false);
		    }
		    */

		    $this->registry->set('contstruct_jetcache_loading', $this->jetcache_construct());
	    }

         if (isset($this->jetcache_settings['pages_forsage']) && $this->jetcache_settings['pages_forsage']) {
         	$this->hook_Registry_get();
         }
	}




	public function cont_ajax_response() {
		$cont_ajax_cycle = '';
		if (isset($this->jetcache_settings['cont_ajax_header']) && $this->jetcache_settings['cont_ajax_header']) {
		    $cont_ajax_cycle .= "
			jc_reg = /\<head[^>]*\>([^]*)\<\/head\>/mi;
			jc_cont_ajax_loaded_head = html.match(jc_reg)[1];

			if (typeof jc_cont_ajax_loaded_head !== 'undefined') {
		    	$('head').html(jc_cont_ajax_loaded_head);
		    }
		    ";
		}
		$cont_ajax_html = "
		<script>
		$(document).ready(function() {
		   if ($('.jc-cont-ajax').length > 0) {
				$.ajax({
					type: 'POST',
					url: '/".$this->request_uri_trim."',
					data: {jc_cont_ajax: '1'},
					async: true,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(html){
			            " . $cont_ajax_cycle . "

						$.each($('.jc-cont-ajax'), function(num, value) {
							cont_setting_md5 = $(this).attr('data-set');
				    		jc_cont_ajax_loaded_ = $(html).find('.jc-cont-ajax-loaded-' + cont_setting_md5).html();
							if (typeof jc_cont_ajax_loaded_ !== 'undefined') {
								$('.jc-cont-ajax-' + cont_setting_md5).replaceWith(jc_cont_ajax_loaded_);
							} else {
								$('.jc-cont-ajax-' + cont_setting_md5).replaceWith('');
							}
						});
					}
				});
			}

		});
		</script>
		";
		return $cont_ajax_html;
	}



	public function hook_Loader_controller($type, $route, $data = array(), $output = NULL) {

		if (!$this->registry->get('admin_work')) {

			if ($this->jetcache_settings['cont_log_status']) {
            	$cont_log_hache = md5($route . json_encode($data));
            	$this->jc_cont_log[$cont_log_hache]['route'] = $route;
            	$this->jc_cont_log[$cont_log_hache]['data'] = json_encode($data);
			}

			if (isset($data) && !empty($data)) {
				$cont_setting = $data;
			} else {
			   	$cont_setting = '';
			}


		    if ($type == 'before') {

                if (isset($this->jetcache_settings['cont_log_status']) && $this->jetcache_settings['cont_log_status']) {
                	$this->jc_cont_log[$cont_log_hache]['start'] = microtime(true);
                }

			    if (isset($this->jetcache_settings['cont_ajax_status']) && $this->jetcache_settings['cont_ajax_status'] && !$this->jc_ajax) {
                    $cont_setting_md5 = md5($route.json_encode($cont_setting));

					$cont_ajax_route_array = explode(PHP_EOL, trim($this->jetcache_settings['cont_ajax_route']));

				    foreach($cont_ajax_route_array as $num => $cont_ajax_route) {
				    	$cont_ajax_route = trim($cont_ajax_route);
						if ($cont_ajax_route[0] != '#' && $cont_ajax_route != '' && strpos($route, $cont_ajax_route) !== false) {
							$html_ajax = "<i class='jc-cont-ajax jc-cont-ajax-".$cont_setting_md5."' data-set='".$cont_setting_md5."'><img src='image/jetcache/jetcache-loading.gif'></i>";
							return $html_ajax;
						}
				    }

			    }

				if ($this->registry->get('seocms_cache_status') && $this->jetcache_cont_access($route)) {

			        if ($this->jetcache_settings['cont_status'] && !isset($this->registry->get('request')->post['jc_cont_ajax'])) {
			        	$cache_from = $this->cont_from_cache($route, $cont_setting);
			        } else {
			        	$cache_from = false;
			        }

			        if ($cache_from) {
		                if ($this->jetcache_settings['cont_log_status']) {
		                	$this->jc_cont_log[$cont_log_hache]['cache'] = true;
							$this->jc_cont_log[$cont_log_hache]['end'] = microtime(true);
							$this->jc_cont_log[$cont_log_hache]['time'] = $this->jc_cont_log[$cont_log_hache]['end'] - $this->jc_cont_log[$cont_log_hache]['start'];
		                }
			        	return $cache_from;
			        } else {
			        	return NULL;
			        }
				}
			}

			if ($type == 'after' && $output != NULL) {

				if ($this->registry->get('seocms_cache_status') && $this->jetcache_settings['cont_status'] && !isset($this->registry->get('request')->post['jc_cont_ajax'])) {
			    	if ($this->controller_jetcache_jetcache->jetcache_cont_access($route)) {
		       			$this->cont_to_cache($output, $route, $cont_setting);
					}
				}

				if ($this->jetcache_settings['cont_log_status']) {
					$this->jc_cont_log[$cont_log_hache]['end'] = microtime(true);
					$this->jc_cont_log[$cont_log_hache]['time'] = $this->jc_cont_log[$cont_log_hache]['end'] - $this->jc_cont_log[$cont_log_hache]['start'];
				}

			    if (isset($this->registry->get('request')->post['jc_cont_ajax'])) {
                    $cont_setting_md5 = md5($route.json_encode($cont_setting));
					$cont_ajax_route_array = explode(PHP_EOL, trim($this->jetcache_settings['cont_ajax_route']));

				    foreach($cont_ajax_route_array as $num => $cont_ajax_route) {
				    	$cont_ajax_route = trim($cont_ajax_route);
						if ($cont_ajax_route[0] != '#' && $cont_ajax_route != '' && strpos($route, $cont_ajax_route) !== false) {
                        	return "<i class='jc-cont-ajax-loaded-".$cont_setting_md5."'>" . $output . "</i>";
						}
					}

			    }
			    return $output;
			}

            if ($type == 'after' && $output == NULL) {
		    	return $output;
			}
        }
	}

    public function hook_getProduct($product_id) {

    	if (isset($this->jetcache_settings['edit_product_id']) && $this->jetcache_settings['edit_product_id']) {

		    $jetcache_product_id_pages = $this->registry->get('jetcache_product_id_pages');
		    $jetcache_product_id_pages = (array)$jetcache_product_id_pages;

		    if (!isset($jetcache_product_id_pages[$product_id])) {
		    	$jetcache_product_id_pages[$product_id] = array();
		    	$this->registry->set('jetcache_product_id_pages', $jetcache_product_id_pages);
		    }

		    $jetcache_product_id_cont = $this->registry->get('jetcache_product_id_cont');
		    $jetcache_product_id_cont = (array)$jetcache_product_id_cont;
		    if (!isset($jetcache_product_id_cont[$product_id])) {
		    	$jetcache_product_id_cont[$product_id] = array();
		    	$this->registry->set('jetcache_product_id_cont', $jetcache_product_id_cont);
		    }

		    $jetcache_product_id_model = $this->registry->get('jetcache_product_id_model');
		    $jetcache_product_id_model = (array)$jetcache_product_id_model;
		    if (!isset($jetcache_product_id_model[$product_id])) {
		    	$jetcache_product_id_model[$product_id] = array();
		    	$this->registry->set('jetcache_product_id_model', $jetcache_product_id_model);
		    }
    	}
    }

	public function jetcache_product_id_update($type) {
		if (isset($this->jetcache_settings['edit_product_id']) && $this->jetcache_settings['edit_product_id']) {
            $jetcache_product_id_pages = $this->registry->get('jetcache_product_id_pages');
            $jetcache_product_id_cont = $this->registry->get('jetcache_product_id_cont');
            $jetcache_product_id_model = $this->registry->get('jetcache_product_id_model');

	    	if ($type == 'pages' && !empty($jetcache_product_id_pages)) {
		    	foreach ($jetcache_product_id_pages as $product_id => $product_id_array) {
		    		foreach ($product_id_array as $filecache) {
		    			$this->model_jetcache_jetcache->edit_product_id($product_id, $filecache, $this->jetcache_settings['cache_expire']);
		    		}
		    	}
	    	}
	    	if ($type == 'cont' && !empty($jetcache_product_id_cont)) {
		    	foreach ($jetcache_product_id_cont as $product_id => $product_id_array) {
		    		foreach ($product_id_array as $filecache) {
		    			$this->model_jetcache_jetcache->edit_product_id($product_id, $filecache, $this->jetcache_settings['cache_expire']);
		    		}
		    	}
	    	}
	    	if ($type == 'model' && !empty($jetcache_product_id_model)) {
		    	foreach ($jetcache_product_id_model as $product_id => $product_id_array) {
		    		foreach ($product_id_array as $filecache) {
		    			$this->model_jetcache_jetcache->edit_product_id($product_id, $filecache, $this->jetcache_settings['cache_expire']);
		    		}
		    	}
	    	}
		}

	}

	public function cacheremove($access = 'islogged', $messages = true) {
        $file_deleted = false;

        if ($messages) {
	        if (SC_VERSION > 20) {
	        	$this->registry->get('load')->language('jetcache/jetcache');
	        } else {
	        	$this->registry->get('language')->load('jetcache/jetcache');
	        }
        }

        if (is_array($access)) $access = 'islogged';

        if (strtolower($access) != 'noaccess') {
			if (SC_VERSION > 21) {
				$place_user = 'cart/user';
				$class_user = 'Cart\User';
			} else {
				$place_user = 'user';
				$class_user = 'User';
			}

			loadlibrary($place_user);
			$this->registry->set('user', new $class_user($this->registry));
		}

		if (isset($this->session->data['user_id'])) {
	    	$this->user_id = $this->session->data['user_id'];
		} else {
			$this->user_id = false;
		}

		if (strtolower($access) == 'noaccess' || ($this->registry->get('user') && $this->registry->get('user')->hasPermission('modify', 'jetcache/jetcache') && $this->user_id && strtolower($access) == 'islogged')) {

			if (!defined('VERSION')) return; if (!defined('SC_VERSION')) define('SC_VERSION', (int)substr(str_replace('.','',VERSION), 0,2));
			$status = false;
			$html = '';
			if (function_exists('modification')) {
				require_once(modification(DIR_SYSTEM . 'library/exceptionizer.php'));
			} else {
				require_once(DIR_SYSTEM . 'library/exceptionizer.php');
			}
	        $exceptionizer = new PHP_Exceptionizer(E_ALL);

            $status = true;

			if (!isset($this->registry->get('request')->get['image'])) {
				$dir_for_clear = DIR_CACHE;
			} else {
				$dir_for_clear = DIR_IMAGE.'cache/';
			}

			if (isset($this->registry->get('request')->get['mod'])) {
				$dir_root = str_ireplace('/system/', '', DIR_SYSTEM);
  				$dir_for_clear = $dir_root.'/vqmod/vqcache/';

  				if (!is_dir($dir_for_clear)) {
  					$html.= $this->language->get('text_cache_remove_fail');
  					$status = false;
  				}
			}
			if (isset($this->registry->get('request')->post['filename']) && $this->registry->get('request')->post['filename'] != '') {
				if (!$this->config->get('blog_work')) {
					$this->config->set('blog_work', true);
				}

				if ($this->cache->delete($this->db->escape($this->registry->get('request')->post['filename']))) {
					$file_deleted = true;
				}

				if ($this->config->get('blog_work')) {
					$this->config->set('blog_work', false);
				}
				$status = false;
			}

            if ($status) {
		        $files = $this->getDelFiles($dir_for_clear, '*', array('index.html', '.htaccess'));
				if ($files) {
					foreach ($files as $file) {
						if (file_exists($file)) {
						    try {
								unlink($file);
								$status = true;
						    }  catch (E_WARNING $e) {
		                     	$status = false;
						    }
						}
					}
				}

				for ($i = 0; $i < 5; $i++) {
					$table = DB_PREFIX . "jetcache_pages_".$i;
					if ($this->table_exists($table)) {
						$sql = "TRUNCATE TABLE " . $table;
						$query = $this->db->query($sql);
	                }
					$table = DB_PREFIX . "jetcache_cont_".$i;
					if ($this->table_exists($table)) {
						$sql = "TRUNCATE TABLE " . $table;
						$query = $this->db->query($sql);
	                }
					$table = DB_PREFIX . "jetcache_model_".$i;
					if ($this->table_exists($table)) {
						$sql = "TRUNCATE TABLE " . $table;
						$query = $this->db->query($sql);
	                }

                }

				$table = DB_PREFIX . "jetcache_product_cache";
				if ($this->table_exists($table)) {
					$sql = "TRUNCATE TABLE " . $table;
					$query = $this->db->query($sql);
	            }
			}

	        if ($status) {
	        	$html.= $this->language->get('text_jetcache_cache_remove_success');
	        } else {
	        	if ($file_deleted) {
                	$html.= $this->language->get('text_jetcache_file_deleted_success');
	        	} else {
	        		$html.= $this->language->get('text_jetcache_cache_remove_fail');
	        	}
	        }

		} else {
			$html = $this->language->get('text_jetcache_no_access');
		}
		if (isset($messages) && $messages) {
			$this->response->setOutput($html);
		}
	}



	private function table_exists($tableName) {
		$found= false;
		$like   = addcslashes($tableName, '%_\\');
		$result = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape($like) . "';");
		$found  = $result->num_rows > 0;
		return $found;
	}

	private function delTree($dir) {
		if (function_exists('modification')) {
			require_once(modification(DIR_SYSTEM . 'library/exceptionizer.php'));
		} else {
			require_once(DIR_SYSTEM . 'library/exceptionizer.php');
		}
	    $exceptionizer = new PHP_Exceptionizer(E_ALL);
	    try {
			$files = array_diff(scandir($dir), array('.','..'));
			foreach ($files as $file) {
			  (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
			}
			return rmdir($dir);
			$status = true;
	    }  catch (E_WARNING $e) {
	       	$status = false;
	    }
	}

	private function getDelFiles($dir, $ext = "*", $exp = array()) {
		$files = Array();
		if (function_exists('modification')) {
			require_once(modification(DIR_SYSTEM . 'library/exceptionizer.php'));
		} else {
			require_once(DIR_SYSTEM . 'library/exceptionizer.php');
		}
        $exceptionizer = new PHP_Exceptionizer(E_ALL);
		try {
		    $handle = opendir($dir);
		    $subfiles = Array();
		    while (false !== ($file = readdir($handle))) {
		      if ($file != '.' && $file != '..') {
		        if (is_dir($dir."/".$file)) {

		          $subfiles = $this->getDelFiles($dir."/".$file, $ext);
	              $this->delTree($dir."/".$file);
		          $files = array_merge($files,$subfiles);
		        } else {
			        $flie_name = $dir."/".$file;
			        $flie_name = str_replace("//", "/",$flie_name);
			        if ((substr($flie_name, strrpos($flie_name, '.')) == $ext) || ($ext == "*")) {
						if (!in_array($file, $exp)) {
							$files[] = $flie_name;
						}
					}
		        }
		      }
		    }
		    closedir($handle);
			$status = true;
		}  catch (E_WARNING $e) {
            $status = false;
		}
	    return $files;
	}

	public function index() {
		return true;
	}


	public function jc_function_exists($function) {

		if (function_exists('ini_get')) {
			$disabled = @ini_get('disable_functions');
		}
		if (extension_loaded('suhosin') && function_exists('ini_get')) {
			$suhosin_disabled = @ini_get('suhosin.executor.func.blacklist');

			if (!empty($suhosin_disabled)) {
				$suhosin_disabled = explode(',', $suhosin_disabled);
				$suhosin_disabled = array_map('trim', $suhosin_disabled);
				$suhosin_disabled = array_map('strtolower', $suhosin_disabled);
				if (function_exists($function) && !in_array($function, $suhosin_disabled)) {
					return true;
				}
				return false;
			}
		}
		return function_exists($function);
	}

	public function jc_quick_mimetype($path) {
		$pathextension = strtolower(trim(substr(strrchr($path, '.'), 1), '.'));
		switch ($pathextension) {
			case 'jpg':
			case 'jpeg':
			case 'jpe':
				return 'image/jpeg';
			case 'png':
				return 'image/png';
			case 'gif':
				return 'image/gif';
			case 'webp':
				return 'image/webp';
			case 'pdf':
				return 'application/pdf';
			default:
				return false;
		}
	}

	public function jc_mimetype( $path, $case ) {
		$type = false;

		if ( 'iq' === $case ) {
			return $this->jc_quick_mimetype( $path );
		}/*
		if ( 'i' === $case && preg_match( '/^RIFF.+WEBPVP8/', file_get_contents( $path, null, null, 0, 16 ) ) ) {
			 return 'image/webp';
		}*/
		if ( 'i' === $case ) {
			$fhandle = fopen( $path, 'rb' );
			if ( $fhandle ) {
				// Read first 12 bytes, which equates to 24 hex characters.
				$magic = bin2hex( fread( $fhandle, 12 ) );
				if ( 0 === strpos( $magic, '52494646' ) && 16 === strpos( $magic, '57454250' ) ) {
					$type = 'image/webp';
					return $type;
				}
				if ( 'ffd8ff' === substr( $magic, 0, 6 ) ) {
					$type = 'image/jpeg';
					return $type;
				}
				if ( '89504e470d0a1a0a' === substr( $magic, 0, 16 ) ) {
					$type = 'image/png';
					return $type;
				}
				if ( '474946383761' === substr( $magic, 0, 12 ) || '474946383961' === substr( $magic, 0, 12 ) ) {
					return $type;
				}
				if ( '25504446' === substr( $magic, 0, 8 ) ) {
					$type = 'application/pdf';
					return $type;
				}
			} else {

			}
		}
		if ( 'b' === $case ) {
			$fhandle = fopen( $path, 'rb' );
			if ( $fhandle ) {
				// Read first 4 bytes, which equates to 8 hex characters.
				$magic = bin2hex( fread( $fhandle, 4 ) );
				// Mac (Mach-O) binary.
				if ( 'cffaedfe' === $magic || 'feedface' === $magic || 'feedfacf' === $magic || 'cefaedfe' === $magic || 'cafebabe' === $magic ) {
					$type = 'application/x-executable';
					return $type;
				}
				// ELF (Linux or BSD) binary.
				if ( '7f454c46' === $magic ) {
					$type = 'application/x-executable';
					return $type;
				}
				// MS (DOS) binary.
				if ( '4d5a9000' === $magic ) {
					$type = 'application/x-executable';
					return $type;
				}
			} else {

			}
		}
		return false;
	}


	public function hook_image($jc_newimage) {

        if (isset($this->jetcache_settings['image_status']) && $this->jetcache_settings['image_status']) {

            if (PHP_OS == 'Darwin') { return $jc_newimage; }
		    if (PHP_OS == 'SunOS') { return $jc_newimage; }
		    if (PHP_OS == 'FreeBSD') { return $jc_newimage; }

         	 if (isset($this->jetcache_settings['image_ex']) && $this->jetcache_settings['image_ex'] != '') {
				$ex_imaget_array = explode(PHP_EOL, trim($this->jetcache_settings['image_ex'], PHP_EOL));
				foreach($ex_imaget_array as $num  => $image_ex) {
					$image_ex = trim($image_ex);
					if ($image_ex[0] != '#' && $image_ex != '') {
						if (stripos($jc_newimage, $image_ex) === false) {
						} else {
							return $jc_newimage;
						}
					}
				}
         	 }

            $jpegmoz_version = $optipng_version = $jpegoptim_version= array();
            $io_path_image = DIR_IMAGE . $jc_newimage;

            $io_mimetype = $this->jc_mimetype($io_path_image, 'i');

		    if (strtolower(PHP_OS) == 'linux') {

                if (isset($this->jetcache_settings['jc_path_mozjpeg']) && $this->jetcache_settings['jc_path_mozjpeg'] != '') {
                	$io_path_mozjpeg = $this->jetcache_settings['jc_path_mozjpeg'];
                } else {
		    		$io_path_mozjpeg = DIR_SYSTEM . 'library/io/mozjpeg/cjpeg';
		    	}
                if (isset($this->jetcache_settings['jc_path_optipng']) && $this->jetcache_settings['jc_path_optipng'] != '') {
                	$io_path_optipng = $this->jetcache_settings['jc_path_optipng'];
                } else {
		    		$io_path_optipng = DIR_SYSTEM . 'library/io/optipng/optipng';
		    	}
                if (isset($this->jetcache_settings['jc_path_jpegoptim']) && $this->jetcache_settings['jc_path_jpegoptim'] != '') {
                	$io_path_jpegoptim = $this->jetcache_settings['jc_path_jpegoptim'];
                } else {
		    		$io_path_jpegoptim = DIR_SYSTEM . 'library/io/jpegoptim/jpegoptim';
		    	}

                if (isset($this->jetcache_settings['image_mozjpeg_status']) && $this->jetcache_settings['image_mozjpeg_status']) {
                    clearstatcache();
					if (isset($this->jetcache_settings['image_mozjpeg_optimize']) && $this->jetcache_settings['image_mozjpeg_optimize']) {
						$image_mozjpeg_optimize = '-optimize';
					} else {
						$image_mozjpeg_optimize = '';
					}

					if (isset($this->jetcache_settings['image_mozjpeg_progressive']) && $this->jetcache_settings['image_mozjpeg_progressive']) {
						$image_mozjpeg_progressive = '-progressive';
					} else {
						$image_mozjpeg_progressive = '';
					}

	                if ($io_mimetype == 'image/jpeg') {
						$io_path_image_tmp = $io_path_image . '.jpg';
						if (file_exists($io_path_image)) {
							rename($io_path_image, $io_path_image_tmp);
						}
						$mozjpeg_exec = $io_path_mozjpeg . " " . $image_mozjpeg_optimize . " " . $image_mozjpeg_progressive . " -outfile '" . $io_path_image . "' '" . $io_path_image_tmp . "' 2>&1";

			  			if (isset($this->jetcache_settings['image_exec']) && $this->jetcache_settings['image_exec']) {
			  				exec($mozjpeg_exec, $jpegmoz_version);
			  			}

						if ((isset($this->jetcache_settings['image_exec']) && !$this->jetcache_settings['image_exec']) && (isset($this->jetcache_settings['image_proc_open']) && $this->jetcache_settings['image_proc_open'])) {
							$descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"));
							$process = proc_open($mozjpeg_exec, $descriptorspec, $pipes);
							if (is_resource($process)) {
							    $jpegmoz_version = proc_close($process);
							}
		                }

						if (file_exists($io_path_image) && (filesize($io_path_image) > 0)) {
							if (file_exists($io_path_image_tmp)) {
								unlink($io_path_image_tmp);
							}
							return true;
						} else {
	                   		if (file_exists($io_path_image_tmp)) {
	                   			rename($io_path_image_tmp, $io_path_image);
	                   		}
						}
					}
                }

                if (isset($this->jetcache_settings['image_jpegoptim_status']) && $this->jetcache_settings['image_jpegoptim_status']) {
	                clearstatcache();
	                if ($io_mimetype == 'image/jpeg') {
						if (isset($this->jetcache_settings['image_jpegoptim_optimize']) && $this->jetcache_settings['image_jpegoptim_optimize']) {
							$image_jpegoptim_optimize = '--force ';
						} else {
							$image_jpegoptim_optimize = '';
						}

						if (isset($this->jetcache_settings['image_jpegoptim_level']) && $this->jetcache_settings['image_jpegoptim_level'] > 1 && $this->jetcache_settings['image_jpegoptim_level'] < 100) {
							$image_jpegoptim_level = '--max=' . (int)$this->jetcache_settings['image_jpegoptim_level'] . ' ';
						} else {
							$image_jpegoptim_level = '';
						}

						if (isset($this->jetcache_settings['image_jpegoptim_size']) && $this->jetcache_settings['image_jpegoptim_size'] > 1 && $this->jetcache_settings['image_jpegoptim_size'] < 100) {
							$image_jpegoptim_size = '--size=' . (int)$this->jetcache_settings['image_jpegoptim_size'] . '% ';
						} else {
							$image_jpegoptim_size = '';
						}

						if (isset($this->jetcache_settings['image_jpegoptim_strip']) && $this->jetcache_settings['image_jpegoptim_strip']) {
							$image_jpegoptim_strip = '--strip-all --strip-iptc ';
						} else {
							$image_jpegoptim_strip = '';
						}

						if (isset($this->jetcache_settings['image_jpegoptim_progressive']) && $this->jetcache_settings['image_jpegoptim_progressive']) {
							$image_jpegoptim_progressive = '--all-progressive ';
						} else {
							$image_jpegoptim_progressive = '';
						}

						$jpegoptim_exec_string = $io_path_jpegoptim . " " . $image_jpegoptim_optimize . $image_jpegoptim_progressive . $image_jpegoptim_strip . $image_jpegoptim_size . $image_jpegoptim_level . "--overwrite '" . $io_path_image . "'  2>&1";


			  			if (isset($this->jetcache_settings['image_exec']) && $this->jetcache_settings['image_exec']) {
	                		exec($jpegoptim_exec_string, $jpegoptim_version);
			  			}

						if ((isset($this->jetcache_settings['image_exec']) && !$this->jetcache_settings['image_exec']) && (isset($this->jetcache_settings['image_proc_open']) && $this->jetcache_settings['image_proc_open'])) {
							$descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"));
							$process = proc_open($jpegoptim_exec_string, $descriptorspec, $pipes);
							if (is_resource($process)) {
							    $jpegoptim_version = proc_close($process);
							}
		                }
	                }
                }

                if (isset($this->jetcache_settings['image_optipng_status']) && $this->jetcache_settings['image_optipng_status']) {
                    clearstatcache();
					if (isset($this->jetcache_settings['optipng_optimize_level'])) {
						$image_optipng_optimize = (int)$this->jetcache_settings['optipng_optimize_level'];
					} else {
						$image_optipng_optimize = '1';
					}

	                if ($io_mimetype == 'image/png') {
	                	$optipng_exec = $io_path_optipng . " -o" . $image_optipng_optimize . " -quiet -strip all '" . $io_path_image . "' 2>&1";
			  			if (isset($this->jetcache_settings['image_exec']) && $this->jetcache_settings['image_exec']) {
	                		exec($optipng_exec, $optipng_version);
			  			}

						if ((isset($this->jetcache_settings['image_exec']) && !$this->jetcache_settings['image_exec']) && (isset($this->jetcache_settings['image_proc_open']) && $this->jetcache_settings['image_proc_open'])) {
							$descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"));
							$process = proc_open($optipng_exec, $descriptorspec, $pipes);
							if (is_resource($process)) {
							    $optipng_version = proc_close($process);
							}
		                }
	                }
                }
		    }
        }
	}


}
}
