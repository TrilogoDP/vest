<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2018 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
if (!class_exists('ControllerCatalogLangmark')) {
class ControllerCatalogLangmark extends Controller
{
	private $error = array();
	private $domen;
	protected $data;
	protected $template;
	protected $children;
	protected $url_link_ssl = false;

	public function __construct($registry) {
		parent::__construct($registry);
		if (version_compare(phpversion(), '5.3.0', '<') == true) {
			exit('PHP5.3+ Required');
		}
		if (!defined('SC_VERSION')) define('SC_VERSION', (int)substr(str_replace('.','',VERSION), 0,2));
	}


	public function index() {
        $this->load_start();
        $this->load_session_token();
        $this->load_language_get();
        $this->load_model();
        $this->load_version();
        $this->load_setTitle();
        $this->load_ssl_domen();
        $this->save_settings();
        $this->load_settings();
        $this->load_scripts();
        $this->load_url_link();
        $this->load_get_languages();
        $this->load_get_stores();
        $this->load_get_currencies();
        $this->load_get_layouts();
        $this->load_messages();
        $this->load_menu();
        $this->load_set_get_pagination();
        $this->load_set_hreflang_switcher();
        $this->load_set_desc_type();
        $this->load_set_ex_multilang_route();
        $this->load_set_ex_multilang_uri();
        $this->load_set_ex_url_route();
        $this->load_set_ex_url_uri();
        $this->load_set_use_link_status();
        $this->load_view();
	}

    private function load_start() {
		$this->config->set('blog_work', true);
	}

    private function load_session_token() {
        if (SC_VERSION > 23) {
        	$this->data['token_name'] = 'user_token';
        } else {
        	$this->data['token_name'] = 'token';
        }
        $this->data['token'] = $this->session->data[$this->data['token_name']];
	}

	private function load_language_get() {

		$this->data['language'] = $this->language;

		$this->language->load('localisation/currency');
		$this->language->load('module/blog');
		$this->language->load('catalog/langmark');

        $this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['language_heading_dev'] = $this->language->get('heading_dev');
        $this->data['language_text_success'] = $this->language->get('text_success');

		$this->data['tab_options'] = $this->language->get('tab_options');
		$this->data['tab_pagination'] = $this->language->get('tab_pagination');
		$this->data['tab_main'] = $this->language->get('tab_main');
        $this->data['tab_ex'] = $this->language->get('tab_ex');
        $this->data['tab_other'] = $this->language->get('tab_other');
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_list'] = $this->language->get('tab_list');

		$this->data['entry_install_update'] = $this->language->get('entry_install_update');
		$this->data['entry_widget_status'] = $this->language->get('entry_widget_status');
        $this->data['entry_lang_default'] = $this->language->get('entry_lang_default');
        $this->data['entry_prefix'] = $this->language->get('entry_prefix');
        $this->data['entry_prefix_switcher'] = $this->language->get('entry_prefix_switcher');
        $this->data['entry_hreflang_switcher'] = $this->language->get('entry_hreflang_switcher');
		$this->data['entry_langmark_template'] = $this->language->get('entry_langmark_template');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');

		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_langmark_text'] = $this->language->get('url_langmark_text');
		$this->data['url_record_text'] = $this->language->get('url_record_text');
		$this->data['url_fields_text'] = $this->language->get('url_fields_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text'] = $this->language->get('url_create_text');
		$this->data['url_delete_text'] = $this->language->get('url_delete_text');
	}

	private function load_model() {
		$this->load->model('setting/setting');
	    $this->load->model('localisation/language');
	    $this->load->model('setting/store');
	    $this->load->model('localisation/currency');
	    $this->load->model('design/layout');
	}

	private function load_version() {
		$this->data['oc_version'] = str_pad(str_replace('.', '', VERSION), 7, '0');
		$this->data['blog_version']       = '*';
		$this->data['blog_version_model'] = '*';
		$settings_admin = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) {
			$this->data['blog_version'] = $value;
		}
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) {
			$this->data['blog_version_model'] = $value;
		}
		$this->data['blog_version'] = $this->data['blog_version'] . ' ' . $this->data['blog_version_model'];


		$this->data['langmark_version_text'] = $this->language->get('langmark_version');
		$this->data['langmark_version'] = '*';
		$settings_admin = $this->model_setting_setting->getSetting('asc_langmark_version', 'asc_langmark_version');
		foreach ($settings_admin as $key => $value) {
			$this->data['langmark_version'] = $value;
		}

		if ($this->data['langmark_version'] != $this->data['langmark_version_text']) {
			$this->data['text_update'] = $this->language->get('text_update');
		}
	}

	private function load_menu() {
        $this->cont('agooa/adminmenu');
        $this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();
	}

	private function load_setTitle() {
		$this->document->setTitle(strip_tags($this->data['heading_title']));
	}

	private function load_ssl_domen() {
		if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {

			$conf_ssl = $this->config->get('config_ssl');
			if (!$conf_ssl) $conf_ssl = HTTPS_SERVER;
			// folder ?
			$this->domen = substr($conf_ssl, 0, $this->strpos_offset('/', $conf_ssl, 3) + 1);
			$config_url = $conf_ssl;

			$this->url_link_ssl = true;


		} else {
			$conf_url = $this->config->get('config_url');
			if (!$conf_url) $conf_url = HTTP_SERVER;
			$this->domen = substr($conf_url, 0, $this->strpos_offset('/', $conf_url, 3) + 1);
			$config_url = $conf_url;

	    	if (SC_VERSION < 20) {
	    		$this->url_link_ssl = 'NONSSL';
	    	} else {
	    		$this->url_link_ssl = false;
	    	}

		}
		$this->domen = str_ireplace(array('http://','https://', '//') , array('','', ''), trim($this->domen));
	}

	private function save_settings() {
        $this->data['ascp_settings'] = $this->config->get('ascp_settings');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->cache->delete('langmark');
			$this->cache->delete('html');

			$data['asc_langmark']['asc_langmark'] = $this->request->post['asc_langmark'];
			$this->model_setting_setting->editSetting('asc_langmark', $data['asc_langmark']);

            $data['ascp_settings']['ascp_settings'] = array_merge($this->data['ascp_settings'], $this->request->post['ascp_settings']);
            $this->model_setting_setting->editSetting('ascp_settings', $data['ascp_settings']);


			$this->session->data['success'] = $this->language->get('text_success');
			if (SC_VERSION < 20) {
				$this->redirect($this->url->link('catalog/langmark', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl));
			} else {
				$this->response->redirect($this->url->link('catalog/langmark', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl));
			}
		}
	}

    private function load_settings() {
		$this->data['modules'] = array();

		if (isset($this->request->post['langmark_module'])) {
			$this->data['modules'] = $this->request->post['langmark_module'];
		} elseif ($this->config->get('langmark_module')) {
			$this->data['modules'] = $this->config->get('langmark_module');
		}

		if (isset($this->request->post['asc_langmark'])) {
			$this->data['asc_langmark'] = $this->request->post['asc_langmark'];
		} else {
			$this->data['asc_langmark'] = $this->config->get('asc_langmark');
		}

		if (isset($this->request->post['ascp_settings'])) {
			$this->data['ascp_settings'] = $this->request->post['ascp_settings'];
		} else {
			$this->data['ascp_settings'] = $this->config->get('ascp_settings');
		}
    }

	private function load_scripts() {
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/jquery/tabs.js')) {
			$this->document->addScript('view/javascript/jquery/tabs.js');
		} else {
			if (file_exists(DIR_APPLICATION . 'view/javascript/blog/tabs/tabs.js')) {
				$this->document->addScript('view/javascript/blog/tabs/tabs.js');
			}
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/seocmspro.js')) {
			$this->document->addScript('view/javascript/blog/seocmspro.js');
		}

		if (SC_VERSION < 20) {
			$this->document->addStyle('view/javascript/seocms/bootstrap/css/bootstrap.css');
		}

		if (file_exists(DIR_APPLICATION . 'view/stylesheet/langmark/langmark.css')) {
			$this->document->addStyle('view/stylesheet/langmark/langmark.css');
		}
		$this->data['icon'] = getSCWebDir(DIR_IMAGE , $this->data['ascp_settings']).'langmark/langmark-icon.png';

	}

    private function load_url_link() {
		$this->data['url_langmark'] = $this->url->link('catalog/langmark', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_record'] = $this->url->link('catalog/record', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_fields'] = $this->url->link('catalog/fields', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_comment'] = $this->url->link('catalog/comment', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_create'] = $this->url->link('catalog/langmark/createtables', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_delete'] = $this->url->link('catalog/langmark/deletesettings', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_modules'] = $this->url->link('extension/module', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_options'] = $this->url->link('catalog/langmark', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_schemes'] = $this->url->link('catalog/langmark/schemes', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['url_widgets'] = $this->url->link('catalog/langmark/widgets', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['action'] = $this->url->link('catalog/langmark', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
		$this->data['cancel'] = $this->url->link('extension/module', $this->data['token_name'] . '=' . $this->data['token'], $this->url_link_ssl);
    }

	private function load_get_languages() {
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		foreach ($this->data['languages'] as $code => $language) {

			if (!isset($language['image']) || SC_VERSION > 21) {
            	$this->data['languages'][$code]['image'] = 'language/'.$code.'/'.$code.'.png';
			} else {
                $this->data['languages'][$code]['image'] = 'view/image/flags/'.$language['image'];
			}
			if (!file_exists(DIR_APPLICATION.$this->data['languages'][$code]['image'])) {
				$this->data['languages'][$code]['image'] = 'view/image/seocms/sc_1x1.png';
			}
		}

        $this->data['config_language_id'] = $this->config->get('config_language_id');
	}

	private function load_get_stores() {
		$this->data['stores'] = $this->model_setting_store->getStores();
	}

    private function load_get_currencies() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$data = array(
			'sort'  => $sort,
			'order' => $order,
		);
		$results = $this->model_localisation_currency->getCurrencies($data);

		foreach ($results as $result) {
			$this->data['currencies'][] = array(
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'] . (($result['code'] == $this->config->get('config_currency')) ? $this->language->get('text_default') : null),
				'code'          => $result['code'],
				'value'         => $result['value'],
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified']))
			);
		}
    }

    private function load_get_layouts() {
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
    }

    private function load_messages() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['session_success'] = $this->session->data['success'];
		}
	}

    private function load_set_get_pagination() {
		if (!isset($this->data['asc_langmark']['get_pagination'])) {
			$this->data['asc_langmark']['get_pagination'] = 'tracking';
		}
	}


    private function load_set_hreflang_switcher() {
		foreach ($this->data['languages'] as $code => $language) {
			if (!isset($this->data['asc_langmark']['hreflang_switcher'][$language['code']])) {
				$this->data['asc_langmark']['hreflang_switcher'][$language['code']] = true;
			}
			if (!isset($this->data['asc_langmark']['prefix_switcher'][$language['code']])) {
				$this->data['asc_langmark']['prefix_switcher'][$language['code']] = true;
			}
		}
	}

    private function load_set_desc_type() {
		if (isset($this->request->post['asc_langmark']['desc_type'])) {
              foreach ($this->request->post['asc_langmark']['desc_type'] as $type_id => $desc_type) {
                 if ($desc_type ['title']=='') {
                   $this->request->post['asc_langmark']['desc_type'][$desc_type ['type_id']] ['title'] = 'Type-'.$desc_type ['type_id'];
              	 }

              	 if ($type_id != $desc_type ['type_id']) {
              	 	unset($this->request->post['asc_langmark']['desc_type'][$type_id]);
              	 	$this->request->post['asc_langmark']['desc_type'][$desc_type ['type_id']] = $desc_type;
              	 }
              }
		}

		if (!isset($this->data['asc_langmark']['desc_type']) || empty($this->data['asc_langmark']['desc_type'])) {
			 $this->data['asc_langmark']['desc_type'] =
			 array( '1' =>
			 		array( 'type_id' => '1',
			 				'title' => 'product/category.tpl'
			 			 ),
					'2' =>
			 		array( 'type_id' => '2',
			 				'title' =>  'product/manufacturer_info.tpl'
			 			 ),
					'3' =>
			 		array( 'type_id' => '3',
			 				'title' => 'information/information.tpl'
			 			 )
			 );
		}
	}

    private function load_set_ex_multilang_route() {
		if (!isset($this->data['asc_langmark']['ex_multilang_route'])) {
        	$this->data['asc_langmark']['ex_multilang_route'] = "quickview".PHP_EOL."api/".PHP_EOL."common/simple_connector".PHP_EOL."search".PHP_EOL."assets".PHP_EOL."captcha";
		} else {
			$this->data['asc_langmark']['ex_multilang_route'] = str_ireplace('|', PHP_EOL, $this->data['asc_langmark']['ex_multilang_route']);
		}
	}

	private function load_set_ex_multilang_uri() {
		if (!isset($this->data['asc_langmark']['ex_multilang_uri'])) {
        	$this->data['asc_langmark']['ex_multilang_uri'] = '';
		} else {
			$this->data['asc_langmark']['ex_multilang_uri'] = str_ireplace('|', PHP_EOL, $this->data['asc_langmark']['ex_multilang_uri']);
		}
	}

	private function load_set_ex_url_route() {
		if (!isset($this->data['asc_langmark']['ex_url_route'])) {
        	$this->data['asc_langmark']['ex_url_route'] = "quickview".PHP_EOL."api/".PHP_EOL."common/simple_connector".PHP_EOL."assets".PHP_EOL."captcha";
		} else {
			$this->data['asc_langmark']['ex_url_route'] = str_ireplace('|', PHP_EOL, $this->data['asc_langmark']['ex_url_route']);
		}
	}

	private function load_set_ex_url_uri() {
		if (!isset($this->data['asc_langmark']['ex_url_uri'])) {
        	$this->data['asc_langmark']['ex_url_uri'] = '';
		} else {
			$this->data['asc_langmark']['ex_url_uri'] = str_ireplace('|', PHP_EOL, $this->data['asc_langmark']['ex_url_uri']);
		}
	}

	private function load_set_use_link_status() {
		if (!isset($this->data['asc_langmark']['use_link_status'])) {
			$this->data['asc_langmark']['use_link_status'] = true;
		}
	}

	private function load_view() {

		$this->template = 'catalog/langmark.tpl';

		if (SC_VERSION < 20) {
			$this->data['column_left'] = '';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			$html                      = $this->render();
		} else {
			$this->data['header']      = $this->load->controller('common/header');
			$this->data['footer']      = $this->load->controller('common/footer');
			$this->data['column_left'] = $this->load->controller('common/column_left');
			$html                      = $this->load->view($this->template, $this->data);
		}
		$this->response->setOutput($html);
	}

/***************************************/
	public function cont($cont) {
		$file  = DIR_CATALOG . 'controller/' . $cont . '.php';
		if (file_exists($file)) {
           $this->cont_loading($cont, $file);
		} else {
			$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
            if (file_exists($file)) {
             	$this->cont_loading($cont, $file);
            } else {
				trigger_error('Error: Could not load controller ' . $cont . '!');
				exit();
			}
		}
	}
	private function cont_loading ($cont, $file) {
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
	}
/***************************************/
	private function validate() {
		$this->language->load('catalog/langmark');
		if (!$this->user->hasPermission('modify', 'catalog/langmark')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			$this->request->post = array();
			return false;
		}
	}
/***************************************/
	public function deletesettings() {
	    if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate()) {
		    $html = "";
			$this->language->load('catalog/langmark');
			$this->load->model('setting/setting');

			$this->model_setting_setting->deleteSetting('asc_langmark');
			$this->model_setting_setting->deleteSetting('asc_langmark_version');

			$html = $this->language->get('text_success');

			$this->response->setOutput($html);
		} else {

			$html = $this->language->get('error_permission');

			$this->response->setOutput($html);
		}
	}

	public function createTables() {
        if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validate()) {
            $html = "";
			$this->language->load('catalog/langmark');
			$this->data['langmark_version'] = $this->language->get('langmark_version');
			$this->load->model('setting/setting');

			$setting_version = Array(
				'asc_langmark_version' => $this->data['langmark_version']
			);
			$this->model_setting_setting->editSetting('asc_langmark_version', $setting_version);


			$msql = "SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `route`='product/search'";
			$query = $this->db->query($msql);
			if (count($query->rows) <= 0) {
				$msql = "INSERT INTO `" . DB_PREFIX . "layout` (`name`) VALUES  ('Search');";
				$query = $this->db->query($msql);
				$msql = "INSERT INTO `" . DB_PREFIX . "layout_route` (`route`, `layout_id`) VALUES  ('product/search'," . $this->db->getLastId() . ");";
				$query = $this->db->query($msql);
			}


		if ($this->config->get('config_seo_url_type')!='seo_url') {
			$devider = true;
		} else {
			$devider = false;
		}

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

		$this->domen = str_ireplace(array('http://','https://', '//') , array('','', ''), trim($this->domen));

		if (!$this->config->get('asc_langmark') && !is_array($this->config->get('asc_langmark'))) {
            $aoptions = Array(
            	'switch' => true,
            	'cache_widgets' => false,
            	'pagination' => false,
            	'pagination_prefix' => 'page',
            	'hreflang_status' => true,
            	'url_close_slash' => $devider,
            	'description_status' => true,
             	'ex_multilang_route' => "quickview".PHP_EOL."api/".PHP_EOL."common/simple_connector".PHP_EOL."assets".PHP_EOL."captcha".PHP_EOL."module/language",
             	'ex_multilang_uri' => "product/search",
             	'ex_url_route' => "quickview".PHP_EOL."api/".PHP_EOL."common/simple_connector".PHP_EOL."assets".PHP_EOL."captcha".PHP_EOL."module/language",
             	'ex_url_uri' => ""
            );



            $this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();
			foreach ($languages as $language) {

				$prefix = $language['code'].'/';
				if ($this->config->get('config_language') == $language['code']) {
					$prefix = '';
				}
				$prefix = substr($prefix, 0, strpos($prefix, '-'));
				$aoptions['prefix'][$language['code']] = $this->domen . $prefix ;
				$aoptions['hreflang'][$language['code']] = $language['code'];
				$aoptions['hreflang_switcher'][$language['code']] = true;
				$aoptions['prefix_switcher'][$language['code']] = true;

				$pagination_title = $this->language->get('text_pagination_title');

				if ($language['code'] == 'ru') {
					$pagination_title = $this->language->get('text_pagination_title_russian');
				}
				if ($language['code'] == 'ua') {
					$pagination_title = $this->language->get('text_pagination_title_ukraine');
				}

				$aoptions['pagination_title'][$language['code']] = $pagination_title;
			}

			$settings = Array(
				'asc_langmark' => $aoptions
			);
			$this->model_setting_setting->editSetting('asc_langmark', $settings);

			$html .= $this->language->get('text_install_ok');

		} else {

			$data['asc_langmark'] = $this->config->get('asc_langmark');

			foreach ($data['asc_langmark']['prefix'] as $code => $value) {
				if (strpos($value, $this->domen) === false) {
			    	$data['asc_langmark']['prefix'][$code] = $this->domen . $value;
				}
			}

			$settings = Array(
				'asc_langmark' => $data['asc_langmark']
			);
			$this->model_setting_setting->editSetting('asc_langmark', $settings);

            $html .= $this->language->get('text_install_already');
		}


		$this->response->setOutput($html);
		}  else {
			$html = $this->language->get('error_permission');
			$this->response->setOutput($html);
		}
	}


	private function strpos_offset($needle, $haystack, $occurrence) {
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
/***************************************/
}
}