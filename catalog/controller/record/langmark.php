<?php
class ControllerRecordLangmark extends Controller {

	protected $data;
	protected $settings;
    private $jetcache_buildcache = false;

	public function __construct($registry) {

		parent::__construct($registry);

		if (!defined('SC_VERSION')) define('SC_VERSION', substr(str_replace('.','',VERSION), 0,2));

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->jetcache_buildcache = false;
			$jetcache_headers = getallheaders();
			if (isset($jetcache_headers['JETCACHE_BUILDCACHE'])) {
				$this->jetcache_buildcache = true;
			}
		}

	}

	public function index() {

			$this->config->set("blog_work", true);

			$this->settings = $this->config->get('asc_langmark');

			if (SC_VERSION < 20) {
				$this->load->language('module/language');
			} else {
				$this->load->language('common/language');
			}

			$this->data['text_language'] = $this->language->get('text_language');

			$this->data['action'] = '';

			$this->data['code'] = $this->data['language_code'] = $this->session->data['language'];

			$this->load->model('localisation/language');

            $array_hreflang = array();
			$this->data['languages'] = array();

			$language_code = $this->config->get('config_language');

			$language_id = $this->config->get('config_language_id');
	        if (isset($this->request->get['route'])) {
		      	$route = $this->request->get['route'];
	        } else {
	            $route = 'common/home';
	        }

			if (isset($this->settings['use_link_status']) && !$this->settings['use_link_status']) {

				$url_current = $this->url->link($route, $this->getQueryString(array(
								'route',
								'_route_',
								'site_language',
								'tag'
								)));

				$prefix_current = $this->settings['prefix'][$this->data['code']];

                $pos_current = stripos($url_current,  $prefix_current);
                $pos_current_len = $pos_current + strlen($prefix_current);
                $substr_pos_current_len = substr($url_current, $pos_current_len, 1);
                $substr_prefix_current = substr($prefix_current, -1);

			}

			$results = $this->model_localisation_language->getLanguages();
			$count_languages = count($results);

			if ($count_languages > 1) {

				foreach ($results as $result) {
						if ($result['status']) {

							if ((isset($this->settings['hreflang_switcher'][$result['code']]) && $this->settings['hreflang_switcher'][$result['code']]) || (isset($this->settings['prefix_switcher'][$result['code']]) && $this->settings['prefix_switcher'][$result['code']])) {

			                	if (!isset($this->settings['use_link_status']) || (isset($this->settings['use_link_status']) && $this->settings['use_link_status'])) {

				                	$this->switchLanguage($result['language_id'], $result['code']);

									$url_lang = $this->url->link($route, $this->getQueryString(array(
									'route',
									'_route_',
									'site_language',
									'tag'
									)));
								} else {

									$prefix_replace = $this->settings['prefix'][$result['code']];

                                    $substr_prefix_replace = substr($prefix_replace, -1);

									if ($substr_prefix_current != '/' && $substr_prefix_replace == '/' && $substr_pos_current_len == '/') {
										$prefix_replace = substr($prefix_replace, 0, -1);
									}

									if ($substr_prefix_current == '/' && $substr_prefix_replace != '/' && $substr_pos_current_len != '/' && $substr_pos_current_len) {
										$prefix_replace = $prefix_replace. '/';
									}

                                	$url_lang = str_ireplace($prefix_current,  $prefix_replace, $url_current);
								}

							}

							if (isset($this->settings['hreflang_switcher'][$result['code']]) && $this->settings['hreflang_switcher'][$result['code']]) {

			                    if (isset($this->settings['hreflang'][$result['code']]) && $this->settings['hreflang'][$result['code']]!='') {
			                        $hreflang = $this->settings['hreflang'][$result['code']];
			                    } else {
			                    	$hreflang = $result['code'];
			                    }
								$array_hreflang[$result['code']] = Array('href' => $url_lang , 'hreflang' => $hreflang );
                            }

							if (isset($this->settings['prefix_switcher'][$result['code']]) && $this->settings['prefix_switcher'][$result['code']]) {
								 if (!isset($result['image'])) {
								 	$result['image'] = 'catalog/language/'. $result['code'].'/'.$result['code'].'.png';
								 }
								 $this->data['languages'][] = array(
									'url'  => $url_lang,
									'name'  => $result['name'],
									'code'  => $result['code'],
									'code2'  => $result['code2'],
									'image' => $result['image']
								 );

							}
						}
				}

			    $this->switchLanguage($language_id, $language_code);

		        if (method_exists($this->document, 'setSCHreflang')  && isset($this->settings['hreflang_status']) && $this->settings['hreflang_status']) {
					if (!empty($array_hreflang)) {
						$this->document->setSCHreflang($array_hreflang);
					}
				}



			$template = 'langmark.tpl';
		    $template_info  = pathinfo($template);
		    $template = $template_info['filename'];
			$this_template = $this->seocmslib->template('agootemplates/record/' . $template);




				$this->data['language'] = $this->language;
				$this->data['theme'] = $this->seocmslib->theme_folder;

				$this->config->set("blog_work", false);

				$this->template = $this_template;

				if (SC_VERSION < 20) {
					$html = $this->render();
				} else {
					$html = $this->load->view($this->template, $this->data);
				}

				return $html;
			} else {
				$this->config->set("blog_work", false);
			}

	}

	private function getQueryString($exclude = array())	{
		if (!is_array($exclude)) {
			$exclude = array();
		}
		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}

	public function switchLanguage($language_id, $code) {


 			$ajax = false;
            $asc_langmark  = $this->config->get('asc_langmark');

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

	        if (isset($asc_langmark['ex_multilang_route']) && $asc_langmark['ex_multilang_route']!='') {
		        $ex_multilang_route = $asc_langmark['ex_multilang_route'];
		        $ex_multilang_route_array = explode(PHP_EOL, $ex_multilang_route);
				if (isset($this->request->get['route'])) {
					foreach ($ex_multilang_route_array as $ex_route) {
						if (utf8_strpos(utf8_strtolower($this->request->get['route']),trim($ex_route)) !== false) {
		            		$ajax = true;
						}
					}
				}
	        }

	        if (isset($asc_langmark['ex_multilang_uri']) && $asc_langmark['ex_multilang_uri']!='') {
		        $ex_multilang_uri = $asc_langmark['ex_multilang_uri'];
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

			if ($code != '' && !$ajax) {

				$this->config->set('config_language_id', $language_id);
				$this->config->set('config_language', $code);
				$this->session->data['language'] = $code;

                if (isset($this->settings['jazz']) && $this->settings['jazz']) {
					setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
				}

			}
	}
}
