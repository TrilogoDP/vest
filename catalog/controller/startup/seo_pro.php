<?php
	class ControllerStartupSeoPro extends Controller {
		private $cache_data = null;
		
		public function __construct($registry) {
			parent::__construct($registry);
			$this->cache_data = $this->cache->get('seo_pro');
			if (!$this->cache_data) {
				$query = $this->db->query("SELECT LOWER(`keyword`) as 'keyword', `query` FROM " . DB_PREFIX . "url_alias ORDER BY url_alias_id");
				$this->cache_data = array();
				foreach ($query->rows as $row) {
					if (isset($this->cache_data['keywords'][$row['keyword']])){
						$this->cache_data['keywords'][$row['query']] = $this->cache_data['keywords'][$row['keyword']];
						continue;
					}
					$this->cache_data['keywords'][$row['keyword']] = $row['query'];
					$this->cache_data['queries'][$row['query']] = $row['keyword'];
				}
				$this->cache->set('seo_pro', $this->cache_data);
			}
		}
		
		public function index() {
			// Add rewrite to url class
			if ($this->config->get('config_seo_url')) {
				$this->url->addRewrite($this);

      // OCFilter start
      if (!is_null($this->registry->get('ocfilter'))) {
  			$this->url->addRewrite($this->registry->get('ocfilter'));
  		}
      // OCFilter end
      
				} else {
				return;
			}
			
			// Decode URL
			if (!isset($this->request->get['_route_'])) {
				$this->validate();
				} else {
				$route_ = $route = $this->request->get['_route_'];
				
				$ampDir = 'amp';
				$route_ = str_replace('amp/', '', $route_);
				$route = str_replace('amp/', '', $route);
				$nn = $this->request->get['_route_'] ;
				$this->request->get['_route_'] = str_replace('amp/', '', $this->request->get['_route_']);
				
				unset($this->request->get['_route_']);
				$parts = explode('/', trim(utf8_strtolower($route), '/'));
				// var_dump($route);
				// echo '===<br>';
				list($last_part) = explode('.', array_pop($parts));
				array_push($parts, $last_part);
				
				$rows = array();
				foreach ($parts as $keyword) {
					if (isset($this->cache_data['keywords'][$keyword])) {
						$rows[] = array('keyword' => $keyword, 'query' => $this->cache_data['keywords'][$keyword]);
					}
				}
				
				if (isset($this->cache_data['keywords'][$route])){
					$keyword = $route;
					$parts = array($keyword);
					$rows = array(array('keyword' => $keyword, 'query' => $this->cache_data['keywords'][$keyword]));
				}
				
				
				// var_dump($rows);
				// var_dump($parts);
				// exit('+');
				
				if (count($rows) == sizeof($parts)) {
					// if ((count($rows) == sizeof($parts)) || (count($rows) == sizeof($parts) - 1 && $parts[count($parts)-1] == 'filter')) { // webfun
					$queries = array();
					foreach ($rows as $row) {
						$queries[utf8_strtolower($row['keyword'])] = $row['query'];
					}
					
					reset($parts);
					foreach ($parts as $part) {
						if(!isset($queries[$part])) return false;
						$url = explode('=', $queries[$part], 2);
						
						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
								} else {
								$this->request->get['path'] .= '_' . $url[1];
							}

        // oct_blog start
        } elseif ($url[0] == 'oct_blog_category_id') {
          if (!isset($this->request->get['cpath'])) {
            $this->request->get['cpath'] = $url[1];
          } else {
            $this->request->get['cpath'] .= '_' . $url[1];
          }
        // oct_blog end
      
							} elseif (count($url) > 1) {
							$this->request->get[$url[0]] = $url[1];
						}
					}
					} else {
					$this->request->get['route'] = 'error/not_found';
				}
				
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
					
					if (strpos($nn, "amp/") !== FALSE ) {
						$this->request->get['route'] = 'product/amp_product';
					}
					
					if (!isset($this->request->get['path'])) {
						$path = $this->getPathByProduct($this->request->get['product_id']);
						if ($path) $this->request->get['path'] = $path;
					}

        // oct_blog start
        } elseif (isset($this->request->get['oct_blog_article_id'])) {
          $this->request->get['route'] = 'octemplates/blog_article';
          if (!isset($this->request->get['cpath'])) {
            $path = $this->getPathByProduct($this->request->get['oct_blog_article_id']);
            if ($path) $this->request->get['cpath'] = $path;
          }
        } elseif (isset($this->request->get['cpath'])) {
          $this->request->get['route'] = 'octemplates/blog_category';
        // oct_blog end
      
					} elseif (isset($this->request->get['path'])) {
					$this->request->get['route'] = 'product/category';
					} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['route'] = 'product/manufacturer/info';
					} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
					} elseif (isset($this->request->get['special_id'])) {
					$this->request->get['route'] = 'information/ochelp_special/info';
					} elseif(isset($this->cache_data['queries'][$route_]) && isset($this->request->server['SERVER_PROTOCOL'])) {
					header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
					$this->response->redirect($this->cache_data['queries'][$route_], 301);
					} else {
					if (isset($queries[$parts[0]])) {
						$this->request->get['route'] = $queries[$parts[0]];
					}
				}
				
				$this->validate();
				
				if (isset($this->request->get['route'])) {
					return new Action($this->request->get['route']);
				}
			}
		}
		
		public function rewrite($link) {
			$ampDir = 'amp';
			
			if (!$this->config->get('config_seo_url')) return $link;
			
			$seo_url = '';
			
			$component = parse_url(str_replace('&amp;', '&', $link));
			
			$data = array();
			parse_str($component['query'], $data);
			
			$route = $data['route'];
			unset($data['route']);
			
			// webfun
			// $webfun_cat = false;
			// $webfun_is_ocfilter = false;
			// if (strripos($link, 'filter_ocfilter') !== false) {
			// 	$webfun_is_ocfilter = true;
			// }
			// webfun end
			
			switch ($route) {	
				case 'product/amp_product':
				if (isset($data['product_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('config_seo_url_include_path')) {
						$data['path'] = $this->getPathByProduct($tmp['product_id']);
						if (!$data['path']) return $link  ;
					}
					$data['product_id'] = $tmp['product_id'];
					
					$a_url = 1;
					
					if (isset($tmp['tracking'])) {
						$data['tracking'] = $tmp['tracking'];
					}
				}
				break;
				case 'product/product':
				if (isset($data['product_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('config_seo_url_include_path')) {
						$data['path'] = $this->getPathByProduct($tmp['product_id']);
						if (!$data['path']) return $link;
					}
					$data['product_id'] = $tmp['product_id'];
					$seo_pro_utm = preg_replace('~\r?\n~', "\n", $this->config->get('config_seo_pro_utm'));
					$allowed_parameters = explode("\n", $seo_pro_utm);
					foreach($allowed_parameters as $ap) {
						if (isset($tmp[trim($ap)])) {
							$data[trim($ap)] = $tmp[trim($ap)];
						}
					}
				}
				break;
				
				case 'product/category':
				if (isset($data['path'])) {
					$category = explode('_', $data['path']);
					$category = end($category);
					$data['path'] = $this->getPathByCategory($category);
					// $webfun_cat = true; // wenfun
					if (!$data['path']) return $link;
				}
				break;
				

        // oct_blog start
        case 'octemplates/blog_article':
          if (isset($data['oct_blog_article_id'])) {
            $tmp = $data;
            $data = array();
            if ($this->config->get('config_seo_url_include_path')) {
              $data['cpath'] = $this->getOctPathByBlogArticle($tmp['oct_blog_article_id']);
              if (!$data['cpath']) return $link;
            }
            $data['oct_blog_article_id'] = $tmp['oct_blog_article_id'];
            $seo_pro_utm = preg_replace('~\r?\n~', "\n", $this->config->get('config_seo_pro_utm'));
			$allowed_parameters = explode("\n", $seo_pro_utm);
			foreach($allowed_parameters as $ap) {
				if (isset($tmp[trim($ap)])) {
					$data[trim($ap)] = $tmp[trim($ap)];
				}
			}
          }
		  
          break;

        case 'octemplates/blog_category':
          if (isset($data['cpath'])) {
            $category = explode('_', $data['cpath']);
            $category = end($category);
            $data['cpath'] = $this->getOctPathByBlogCategory($category);
            if (!$data['cpath']) return $link;
          }

        break;
        // oct_blog end
      
				case 'product/product/review':
				case 'information/information/agree':
				return $link;
				break;
				
				default:
				break;
			}
			
			if ($component['scheme'] == 'https') {
				$link = $this->config->get('config_ssl');
				} else {
				$link = $this->config->get('config_url');
			}
			
			$link .= 'index.php?route=' . $route;
			
			if (count($data)) {
				$link .= '&amp;' . urldecode(http_build_query($data, '', '&amp;'));
			}
			
			$queries = array();
			if(!in_array($route, array('product/search', 'product/special'))) {
				foreach ($data as $key => $value) {
					switch ($key) {
						case 'product_id':
						case 'category_id':

        // oct_blog start
        case 'oct_blog_article_id':
        case 'oct_blog_category_id':
        // oct_blog end
      
						case 'information_id':
						//case 'order_id':
						$queries[] = $key . '=' . $value;
						unset($data[$key]);
						$postfix = 1;
						break;
						
						case 'manufacturer_id':
						$queries[] = 'product/manufacturer';
						$queries[] = $key . '=' . $value;
						unset($data[$key]);
						break;
						
						case 'special_id':
						$queries[] = 'information/ochelp_special';
						$queries[] = $key . '=' . $value;
						unset($data[$key]);
						break;
						

        // oct_blog start
        case 'cpath':
          $categories = explode('_', $value);
          foreach($categories as $category) {
            $queries[] = 'oct_blog_category_id=' . $category;
          }
          unset($data[$key]);
          break;
        // oct_blog end
      
						case 'path':
						$categories = explode('_', $value);
						foreach ($categories as $category) {
							$queries[] = 'category_id=' . $category;
						}
						unset($data[$key]);
						break;
						
						default:
						break;
					}
				}
			}
			
			
			if(empty($queries)) {
				$queries[] = $route;
			}
			
			$rows = array();
			foreach($queries as $query) {
				if(isset($this->cache_data['queries'][$query])) {
					$rows[] = array('query' => $query, 'keyword' => $this->cache_data['queries'][$query]);
				}
			}
			
			if (isset($a_url)) {
				$seo_url .=  $ampDir;
			}
			
			if(count($rows) == count($queries)) {
				$aliases = array();
				foreach($rows as $row) {
					$aliases[$row['query']] = $row['keyword'];
				}
				foreach($queries as $query) {
					$seo_url .= '/' . rawurlencode($aliases[$query]);
				}
			}
			
			if ($seo_url == '') return $link;
			
			$seo_url = trim($seo_url, '/');
			
			if ($component['scheme'] == 'https') {
				$seo_url = $this->config->get('config_ssl') . $seo_url;
				} else {
				$seo_url = $this->config->get('config_url') . $seo_url;
			}
			
			if (isset($postfix)) {
				$seo_url .= trim($this->config->get('config_seo_url_postfix'));
				} else {
				$seo_url .= '/';
			}
			
			if(substr($seo_url, -2) == '//') {
				$seo_url = substr($seo_url, 0, -1);
			}
			
			// webfun
			// if ($webfun_cat && $webfun_is_ocfilter) {
			// 	$seo_url .= 'filter/';
			// }
			// webfun end
			
			if (count($data)) {
				$seo_url .= '?' . urldecode(http_build_query($data, '', '&amp;'));
			}
			
			return $seo_url;
		}
		
		private function getPathByProduct($product_id) {
			$product_id = (int)$product_id;
			if ($product_id < 1) return false;
			
			static $path = null;
			if (!isset($path)) {
				$path = $this->cache->get('product.seopath');
				if (!isset($path)) $path = array();
			}
			
			if (!isset($path[$product_id])) {
				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $product_id . "' ORDER BY main_category DESC LIMIT 1");
				
				$path[$product_id] = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);
				
				$this->cache->set('product.seopath', $path);
			}
			
			return $path[$product_id];
		}
		
		private function getPathByCategory($category_id) {
			$category_id = (int)$category_id;
			if ($category_id < 1) return false;
			
			static $path = null;
			if (!isset($path)) {
				$path = $this->cache->get('category.seopath');
				if (!isset($path)) $path = array();
			}
			
			if (!isset($path[$category_id])) {
				$max_level = 10;
				
				$sql = "SELECT CONCAT_WS('_'";
				for ($i = $max_level-1; $i >= 0; --$i) {
					$sql .= ",t$i.category_id";
				}
				$sql .= ") AS path FROM " . DB_PREFIX . "category t0";
				for ($i = 1; $i < $max_level; ++$i) {
					$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i-1) . ".parent_id)";
				}
				$sql .= " WHERE t0.category_id = '" . $category_id . "'";
				
				$query = $this->db->query($sql);
				
				$path[$category_id] = $query->num_rows ? $query->row['path'] : false;
				
				$this->cache->set('category.seopath', $path);
			}
			
			return $path[$category_id];
		}
		

        // oct_blog start
        private function getOctPathByBlogArticle($oct_blog_article_id) {
          $oct_blog_article_id = (int)$oct_blog_article_id;
          if ($oct_blog_article_id < 1) return false;

          static $path = null;
		  
          if (!isset($path)) {
            $path = $this->cache->get('oct_blog_article.seopath');
            if (!isset($path)) $path = array();
          }

          if (!isset($path[$oct_blog_article_id])) {
            $query = $this->db->query("SELECT oct_blog_category_id FROM " . DB_PREFIX . "oct_blog_article_to_category WHERE oct_blog_article_id = '" . $oct_blog_article_id . "' ORDER BY main_oct_blog_category_id DESC LIMIT 1");
            $path[$oct_blog_article_id] = $this->getOctPathByBlogCategory($query->num_rows ? (int)$query->row['oct_blog_category_id'] : 0);
            $this->cache->set('oct_blog_article.seopath', $path);

          }

          return $path[$oct_blog_article_id];
        }

        private function getOctPathByBlogCategory($oct_blog_category_id) {
          $oct_blog_category_id = (int)$oct_blog_category_id;
          if ($oct_blog_category_id < 1) return false;

          static $path = null;
		  
          if (!isset($path)) {
            $path = $this->cache->get('oct_blog_category.seopath');
            if (!isset($path)) $path = array();
          }

          if (!isset($path[$oct_blog_category_id])) {
            $max_level = 10;

            $sql = "SELECT CONCAT_WS('_'";

            for ($i = $max_level-1; $i >= 0; --$i) {
              $sql .= ",t$i.oct_blog_category_id";
            }

            $sql .= ") AS cpath FROM " . DB_PREFIX . "oct_blog_category t0";

            for ($i = 1; $i < $max_level; ++$i) {
              $sql .= " LEFT JOIN " . DB_PREFIX . "oct_blog_category t$i ON (t$i.oct_blog_category_id = t" . ($i-1) . ".oct_blog_category_parent_id)";
            }

            $sql .= " WHERE t0.oct_blog_category_id = '" . $oct_blog_category_id . "'";

            $query = $this->db->query($sql);

            $path[$oct_blog_category_id] = $query->num_rows ? $query->row['cpath'] : false;

            $this->cache->set('oct_blog_category.seopath', $path);
          }

          return $path[$oct_blog_category_id];

        }
        // oct_blog end
      
		private function validate() {
			$ampDir = 'amp';
			if( isset( $this->request->get['add'] ) ) {
				return;
			}
			
			if (isset($this->request->get['route']) && $this->request->get['route'] == 'error/not_found') {
				return;
			}
			if(empty($this->request->get['route'])) {
				$this->request->get['route'] = 'common/home';
			}
			
			if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				return;
			}
			
			// if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			// 	$config_ssl = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);
			// 	$url = str_replace('&amp;', '&', $config_ssl . ltrim($this->request->server['REQUEST_URI'], '/'));
			// 	$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), true));
			// } else {
			// 	$config_url = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);
			// 	$url = str_replace('&amp;', '&', $config_url . ltrim($this->request->server['REQUEST_URI'], '/'));
			// 	$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), false));
			// }
			
			// webfun
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				// $test = $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), true);
				// echo 'test=' . $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), true);
				
				$config_ssl = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);
				$url = str_replace('&amp;', '&', $config_ssl . ltrim($this->request->server['REQUEST_URI'], '/'));
				$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), true));
				// $seo = str_replace('&amp;', '&', $test);
				
				// $test = $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), true);
				// var_dump('test=' . $test);
				} else {
				$config_url = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);
				$url = str_replace('&amp;', '&', $config_url . ltrim($this->request->server['REQUEST_URI'], '/'));
				$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), false));
			}
			// webfun end
			
			// var_dump($url);
			// var_dump($seo);
			// var_dump($this->getQueryString(array('route')));
			// exit();
			
			// if (rawurldecode($url) != rawurldecode($seo) && isset($this->request->server['SERVER_PROTOCOL'])) {
			
			if (rawurldecode($url) != rawurldecode($seo) && rawurldecode($seo) != $ampDir && isset($this->request->server['SERVER_PROTOCOL']) && strripos($url, 'page=') === false) {
				header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
				
				$this->response->redirect($seo, 301);
			}
		}
		
		private function strpos_offset($needle, $haystack, $occurrence) {
			// explode the haystack
			$arr = explode($needle, $haystack);
			// check the needle is not out of bounds
			switch($occurrence) {
				case $occurrence == 0:
				return false;
				case $occurrence > max(array_keys($arr)):
				return false;
				default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
			}
		}
		
		private function getQueryString($exclude = array()) {
			if (!is_array($exclude)) {
				$exclude = array();
			}
			
			return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
		}
	}					