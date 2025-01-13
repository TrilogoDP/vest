<?php
//error_reporting(0);
class ControllerFeedEpicentrXml extends Controller {

	private $error   = array();
	public  $version = '1.2.2';

	public function index() {
		$this->load->language('feed/epicentr_xml');

		$this->document->setTitle('Epicentr XML version '.$this->version);

		$this->load->model('setting/setting');
		$this->load->model('feed/epicentr_xml');

		//предустановки
		$this->model_feed_epicentr_xml->pre_install();

		$this->load->model('catalog/product');
		$this->load->model('localisation/stock_status');
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/attribute_group');
		$this->load->model('catalog/attribute');
		$this->load->model('catalog/option');
		$this->load->model('localisation/currency');
		$this->load->model('localisation/language');


		if(version_compare(VERSION, '2.3') > 0) {
    		
			$return_url  = 'extension/extension';
			$return_type = '&type=feed';
			$module_url  = 'extension/extension'; 
		} else {

			$return_url  = 'extension/feed';
			$return_type = '';
			$module_url  = 'extension/module';
		}


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			if (isset($this->request->post['epicentr_xml_attrib'])) {
				$this->request->post['epicentr_xml_attrib'] = json_encode($this->request->post['epicentr_xml_attrib']);
			}


 			if (isset($this->request->post['epicentr_xml_products'])) {
				$this->request->post['epicentr_xml_products'] = implode(',', $this->request->post['epicentr_xml_products']);
			}

			if (isset($this->request->post['epicentr_xml_categories'])) {
				$this->request->post['epicentr_xml_categories'] = implode(',', $this->request->post['epicentr_xml_categories']);
			}

			if (isset($this->request->post['epicentr_xml_options'])) {
				$this->request->post['epicentr_xml_options'] = implode(',', $this->request->post['epicentr_xml_options']);
			}

			if (isset($this->request->post['epicentr_xml_manufacturers'])) {
				$this->request->post['epicentr_xml_manufacturers'] = implode(',', $this->request->post['epicentr_xml_manufacturers']);
			}

		    if (isset($this->request->post['epicentr_xml_out_of_stock'])) {
				$this->request->post['epicentr_xml_out_of_stock'] = implode(',', $this->request->post['epicentr_xml_out_of_stock']);
			}


			$this->model_setting_setting->editSetting('epicentr_xml', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');


			if($this->request->post['stay'] == 'yes'){
				$this->response->redirect($this->url->link('feed/epicentr_xml', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link($return_url, 'token=' . $this->session->data['token'].$return_type, 'SSL'));
			}


		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_data_feed'] = $this->language->get('entry_data_feed');
		$data['entry_shopname'] = $this->language->get('entry_shopname');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_in_stock'] = $this->language->get('entry_in_stock');
		$data['entry_out_of_stock'] = $this->language->get('entry_out_of_stock');
		$data['entry_telephone'] = 'Только номер телефона';
		
		$data['help_shopname'] = $this->language->get('help_shopname');
		$data['help_company'] = $this->language->get('help_company');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_currency'] = $this->language->get('help_currency');
		$data['help_in_stock'] = $this->language->get('help_in_stock');
		$data['help_out_of_stock'] = 'При нулевом остатке с какими статусами на складке выгружать товары? Если ничего не выбрано то выгружаются все статусы';
		$data['help_epicentr_xml'] = $this->language->get('help_epicentr_xml');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_and_stay'] = 'Сохранить и остаться';
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['tab_general'] = $this->language->get('tab_general');

		$data['success'] = $this->session->data['success'];
		unset($this->session->data['success']);

		$data['token']   = $this->session->data['token'];
		$data['version'] = $this->version;
		$data['new']     = $this->check_version();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link($module_url, 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_module'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link($return_url, 'token=' . $this->session->data['token'].$return_type, 'SSL'),
			'text'      => $this->language->get('text_feed'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link($return_url, 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_epicentr'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('feed/epicentr_xml', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link($return_url, 'token=' . $this->session->data['token'], 'SSL');

		
		if (isset($this->request->post['epicentr_xml_status'])) {
			$data['epicentr_xml_status'] = $this->request->post['epicentr_xml_status'];
		} else {
			$data['epicentr_xml_status'] = $this->config->get('epicentr_xml_status');
		}

		$data['data_feed'] = ($this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG) . 'index.php?route=feed/epicentr_xml';

		$data['install_link'] = ($this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG) . 'epic_categories/write.php';
		$data['tree_link']    = ($this->config->get('config_secure') ? HTTPS_CATALOG : HTTP_CATALOG) . 'epic_categories/read.php';



		if (isset($this->request->post['epicentr_xml_shopname'])) {
			$data['epicentr_xml_shopname'] = $this->request->post['epicentr_xml_shopname'];
		} else {
			$data['epicentr_xml_shopname'] = $this->config->get('epicentr_xml_shopname');
		}


		if (isset($this->request->post['epicentr_xml_telephone'])) {
			$data['epicentr_xml_telephone'] = $this->request->post['epicentr_xml_telephone'];
		} else {
			$data['epicentr_xml_telephone'] = $this->config->get('epicentr_xml_telephone');
		}



		if (isset($this->request->post['epicentr_xml_company'])) {
			$data['epicentr_xml_company'] = $this->request->post['epicentr_xml_company'];
		} else {
			$data['epicentr_xml_company'] = $this->config->get('epicentr_xml_company');
		}

		if (isset($this->request->post['epicentr_xml_currency'])) {
			$data['epicentr_xml_currency'] = $this->request->post['epicentr_xml_currency'];
		} else {
			$data['epicentr_xml_currency'] = $this->config->get('epicentr_xml_currency');
		}

		
		if (isset($this->request->post['epicentr_xml_language_ru'])) {
			$data['epicentr_xml_language_ru'] = $this->request->post['epicentr_xml_language_ru'];
		} else {
			$data['epicentr_xml_language_ru'] = $this->config->get('epicentr_xml_language_ru');
		}


		if (isset($this->request->post['epicentr_xml_language_ua'])) {
			$data['epicentr_xml_language_ua'] = $this->request->post['epicentr_xml_language_ua'];
		} else {
			$data['epicentr_xml_language_ua'] = $this->config->get('epicentr_xml_language_ua');
		}


		if (isset($this->request->post['epicentr_xml_zero'])) {
			$data['epicentr_xml_zero'] = $this->request->post['epicentr_xml_zero'];
		} else {
			$data['epicentr_xml_zero'] = $this->config->get('epicentr_xml_zero');
		}

		if (isset($this->request->post['epicentr_xml_descript'])) {
			$data['epicentr_xml_descript'] = $this->request->post['epicentr_xml_descript'];
		} else {
			$data['epicentr_xml_descript'] = $this->config->get('epicentr_xml_descript');
		}
		

		if (isset($this->request->post['epicentr_xml_img'])) {
			$data['epicentr_xml_img'] = $this->request->post['epicentr_xml_img'];
		} else {
			$data['epicentr_xml_img'] = $this->config->get('epicentr_xml_img');
		}


		if (isset($this->request->post['epicentr_xml_option'])) {
			$data['epicentr_xml_option'] = $this->request->post['epicentr_xml_option'];
		} else {
			$data['epicentr_xml_option'] = $this->config->get('epicentr_xml_option');
		}


		if (isset($this->request->post['epicentr_xml_brand'])) {
			$data['epicentr_xml_brand'] = $this->request->post['epicentr_xml_brand'];
		} else {
			$data['epicentr_xml_brand'] = $this->config->get('epicentr_xml_brand');
		}



		if (isset($this->request->post['epicentr_xml_feedtype'])) {
			$data['epicentr_xml_feedtype'] = $this->request->post['epicentr_xml_feedtype'];
		} else {
			$data['epicentr_xml_feedtype'] = $this->config->get('epicentr_xml_feedtype');
		}


		if (isset($this->request->post['epicentr_xml_country'])) {
			$data['epicentr_xml_country'] = $this->request->post['epicentr_xml_country'];
		} elseif ($this->config->get('epicentr_xml_country')) {
			$data['epicentr_xml_country'] = $this->config->get('epicentr_xml_country');
		} else {
			$data['epicentr_xml_country'] = '';
		}


		if (isset($this->request->post['epicentr_xml_delivery'])) {
			$data['epicentr_xml_delivery'] = $this->request->post['epicentr_xml_delivery'];
		} elseif ($this->config->get('epicentr_xml_delivery')) {
			$data['epicentr_xml_delivery'] = $this->config->get('epicentr_xml_delivery');
		} else {
			$data['epicentr_xml_delivery'] = '';
		}


		if (isset($this->request->post['epicentr_xml_guarantee'])) {
			$data['epicentr_xml_guarantee'] = $this->request->post['epicentr_xml_guarantee'];
		} elseif ($this->config->get('epicentr_xml_guarantee')) {
			$data['epicentr_xml_guarantee'] = $this->config->get('epicentr_xml_guarantee');
		} else {
			$data['epicentr_xml_guarantee'] = '';
		}



		if (isset($this->request->post['epicentr_xml_width'])) {
			$data['epicentr_xml_width'] = $this->request->post['epicentr_xml_width'];
		} else {
			$data['epicentr_xml_width'] = $this->config->get('epicentr_xml_width');
		}



		if (isset($this->request->post['epicentr_xml_height'])) {
			$data['epicentr_xml_height'] = $this->request->post['epicentr_xml_height'];
		} else {
			$data['epicentr_xml_height'] = $this->config->get('epicentr_xml_height');
		}



		if (isset($this->request->post['epicentr_xml_coeff'])) {
			$data['epicentr_xml_coeff'] = $this->request->post['epicentr_xml_coeff'];
		} elseif ($this->config->get('epicentr_xml_coeff')) {
			$data['epicentr_xml_coeff'] = $this->config->get('epicentr_xml_coeff');
		} else {
			$data['epicentr_xml_coeff'] = 1;
		}

		
		if (isset($this->request->post['epicentr_xml_images'])) {
			$data['epicentr_xml_images'] = $this->request->post['epicentr_xml_images'];
		} elseif ($this->config->get('epicentr_xml_images')) {
			$data['epicentr_xml_images'] = $this->config->get('epicentr_xml_images');
		} else {
			$data['epicentr_xml_images'] = 10;
		}



		if (isset($this->request->post['epicentr_xml_format'])) {
			$data['epicentr_xml_format'] = $this->request->post['epicentr_xml_format'];
		} elseif ($this->config->get('epicentr_xml_format')) {
			$data['epicentr_xml_format'] = $this->config->get('epicentr_xml_format');
		} else {
			$data['epicentr_xml_format'] = '{name}';
		}



		if (isset($this->request->post['epicentr_xml_charset'])) {
			$data['epicentr_xml_charset'] = $this->request->post['epicentr_xml_charset'];
		} elseif ($this->config->get('epicentr_xml_charset')) {
			$data['epicentr_xml_charset'] = (int)$this->config->get('epicentr_xml_charset');
		} 


		if (isset($this->request->post['epicentr_xml_promo'])) {
			$data['epicentr_xml_promo'] = $this->request->post['epicentr_xml_promo'];
		} elseif ($this->config->get('epicentr_xml_promo')) {
			$data['epicentr_xml_promo'] = (int)$this->config->get('epicentr_xml_promo');
		} 


		if (isset($this->request->post['epicentr_xml_out_of_stock'])) {
			$data['epicentr_xml_out_of_stock'] = $this->request->post['epicentr_xml_out_of_stock'];
		} elseif ($this->config->get('epicentr_xml_out_of_stock') != '') {
			$data['epicentr_xml_out_of_stock'] = explode(',', $this->config->get('epicentr_xml_out_of_stock'));
		} else {
			$data['epicentr_xml_out_of_stock'] = array();
		}


		$data['iattributes'] = $this->jsonToArray($this->config->get('epicentr_xml_attrib'));
	

		if (isset($this->request->post['epicentr_xml_attribchange'])) {
			$data['epicentr_xml_attribchange'] = $this->request->post['epicentr_xml_attribchange'];
		} else {
			$data['epicentr_xml_attribchange'] = (int)$this->config->get('epicentr_xml_attribchange');
		}



		if (isset($this->request->post['epicentr_xml_attrib_type'])) {
			$data['epicentr_xml_attrib_type'] = $this->request->post['epicentr_xml_attrib_type'];
		} else {
			$data['epicentr_xml_attrib_type'] = (int)$this->config->get('epicentr_xml_attrib_type');
		}



		if (isset($this->request->post['epicentr_xml_attrib'])) {
			$data['epicentr_xml_attrib'] = $this->request->post['epicentr_xml_attrib'];
		} elseif ($this->config->get('epicentr_xml_attrib') != '') {
			$data['epicentr_xml_attrib'] = json_decode($this->config->get('epicentr_xml_attrib'));
		} else {
			$data['epicentr_xml_attrib'] = array();
		}



		if (isset($this->request->post['epicentr_xml_products'])) {
			$data['epicentr_xml_products'] = $this->request->post['epicentr_xml_products'];
		} elseif ($this->config->get('epicentr_xml_products') != '') {
			$data['epicentr_xml_products'] = explode(',', $this->config->get('epicentr_xml_products'));
		} else {
			$data['epicentr_xml_products'] = array();
		}



		$data['xml_products'] = array();

		foreach ($data['epicentr_xml_products'] as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['xml_products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}



		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();


		$data['categories'] = $this->model_catalog_category->getCategories(0);

		if (isset($this->request->post['epicentr_xml_categories'])) {
			$data['epicentr_xml_categories'] = $this->request->post['epicentr_xml_categories'];
		} elseif ($this->config->get('epicentr_xml_categories') != '') {
			$data['epicentr_xml_categories'] = explode(',', $this->config->get('epicentr_xml_categories'));
		} else {
			$data['epicentr_xml_categories'] = array();
		}


		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
 

 		if (isset($this->request->post['epicentr_xml_manufacturers'])) {
			$data['epicentr_xml_manufacturers'] = $this->request->post['epicentr_xml_manufacturers'];
		} elseif ($this->config->get('epicentr_xml_manufacturers') != '') {
			$data['epicentr_xml_manufacturers'] = explode(',', $this->config->get('epicentr_xml_manufacturers'));
		} else {
			$data['epicentr_xml_manufacturers'] = array();
		}



		$results = $this->model_catalog_attribute_group->getAttributeGroups();

		foreach ($results as $result) {
			$data['attribute_groups'][] = array(
				'attribute_group_id' => $result['attribute_group_id'],
				'name'               => $result['name']
			);
		}


		$results = $this->model_catalog_attribute->getAttributes();

		foreach ($results as $result) {
			$data['attributes'][] = array(
				'attribute_id'    => $result['attribute_id'],
				'name'            => $result['name'],
				'attribute_group' => $result['attribute_group'],
				'attribute_group_id' => $result['attribute_group_id']
			);
		}


		$filter_option = array('select', 'checkbox', 'radio');

		$results = $this->model_catalog_option->getOptions();

		foreach ($results as $result) {

			if(in_array($result['type'], $filter_option)) {
				$data['options'][] = array(
					'option_id'  => $result['option_id'],
					'name'       => $result['name']
				);
			}
		}

		if (isset($this->request->post['epicentr_xml_options'])) {
			$data['epicentr_xml_options'] = $this->request->post['epicentr_xml_options'];
		} elseif ($this->config->get('epicentr_xml_options') != '') {
			$data['epicentr_xml_options'] = explode(',', $this->config->get('epicentr_xml_options'));
		} else {
			$data['epicentr_xml_options'] = array();
		}


		if (isset($this->request->post['epicentr_xml_options_null'])) {
			$data['epicentr_xml_options_null'] = $this->request->post['epicentr_xml_options_null'];
		} elseif ($this->config->get('epicentr_xml_options_null')) {
			$data['epicentr_xml_options_null'] = $this->config->get('epicentr_xml_options_null');
		} else {
			$data['epicentr_xml_options_null'] = 0;
		}

		if (isset($this->request->post['epicentr_xml_options_id_form'])) {
			$data['epicentr_xml_options_id_form'] = $this->request->post['epicentr_xml_options_id_form'];
		} elseif ($this->config->get('epicentr_xml_options_id_form')) {
			$data['epicentr_xml_options_id_form'] = $this->config->get('epicentr_xml_options_id_form');
		} else {
			$data['epicentr_xml_options_id_form'] = 0;
		}



		$currencies = $this->model_localisation_currency->getCurrencies();
		$allowed_currencies = array_flip(array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH'));
		$data['currencies'] = array_intersect_key($currencies, $allowed_currencies);



		$data['languages'] = $this->model_localisation_language->getLanguages();


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$data['l'] = $this->check_licence();

		$this->response->setOutput($this->load->view('feed/epicentr_xml.tpl', $data));


	}



	public function install() {

		$this->load->model('feed/epicentr_xml');

		$this->model_feed_epicentr_xml->install();

	}




	public function uninstall() {
		
	}


	public function jsonToArray($data){

		$data   = (array)json_decode($data);
		$result = array();

		foreach ($data as $key_g => $value_g) {

			$value_g = (array)$value_g;

			foreach ($value_g as $key_a => $value_a) {

				$result[$key_g][$key_a] =  (array)$value_a;
			}
			
		}

		return $result;
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'feed/epicentr_xml')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}



	public function getCategoryProducts(){

		$category = $this->request->post['category_id'];
		$start    = 0;
		$limit    = 1000;
		$json     = array();

		$this->load->model('feed/epicentr_xml');
		$model    = $this->model_feed_epicentr_xml; 

		$filter_data = array(
				'category'     => $category,
				'start'        => $start,
				'limit'        => $limit
			);


		$category_info    = $model->getCategory($category);
		$cat_status       = $model->getCategoryStatus($category);
		$results          = $model->getProducts($filter_data);


		$json['category'] = array(
			'category_id'      => $category,
 			'name'             => $category_info['name'],
			'epicentr_name'     => (is_null($category_info['epicentr_name'])) ? '' : $category_info['epicentr_name'],
			'epicentr_coeff'    => (empty($category_info['epicentr_coeff']))  ? '' : $category_info['epicentr_coeff'],
			'epicentr_id'       => $category_info['epicentr_category_id'],
			'status'           => $cat_status
		);

		foreach ($results as $result) {

			$json['products'][] = array(
				'product_id'    => $result['product_id'],
				'name'          => $result['name'],
				'epicentr_name'  => $result['epicentr_name'],

				'quantity'         => $result['quantity'],
				'epicentr_quantity' => $result['epicentr_quantity'],
				'price'            => $result['price'],
				'epicentr_promo'    => $result['epicentr_promo'],
				'epicentr_status'   => $result['epicentr_status'],

			);

		}



		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}



	public function saveEpicentrCategory(){

		$category_id   = $this->request->post['category_id'];
		$epicentr_name  = $this->request->post['epicentr_name'];
		$epicentr_id    = $this->request->post['epicentr_id'];

		$this->load->model('feed/epicentr_xml');

		$result = $this->model_feed_epicentr_xml->saveEpicentrCategory($category_id, $epicentr_name, $epicentr_id);

		if($result) {
			return true;
		} else {
			return false;
		}
	}


	public function saveEpicentrCatCoeff(){

		$category_id = $this->request->post['category_id'];
		$coeff       = $this->request->post['coeff'];

		$this->load->model('feed/epicentr_xml');

		$result = $this->model_feed_epicentr_xml->saveEpicentrCatCoeff($category_id, $coeff);

		if($result) {
			return true;
		} else {
			return false;
		}
	}



	public function saveEpicentrName() {

		$product_id    = $this->request->post['product_id'];
		$epicentr_name  = $this->request->post['epicentr_name'];

		$this->load->model('feed/epicentr_xml');

		$result = $this->model_feed_epicentr_xml->saveEpicentrName($product_id, $epicentr_name);

		if($result) {
			return true;
		} else {
			return false;
		}
	}

	
	public function saveEpicentrPromo() {


		$product_id = $this->request->post['product_id'];
		$promo      = $this->request->post['promo'];

		$this->load->model('feed/epicentr_xml');

		$result = $this->model_feed_epicentr_xml->saveEpicentrPromo($product_id, $promo);

		if($result) {
			return true;
		} else {
			return false;
		}
	}

	
	public function saveEpicentrQuantity() {


		$product_id = $this->request->post['product_id'];
		$quantity   = $this->request->post['quantity'];

		$this->load->model('feed/epicentr_xml');

		$result = $this->model_feed_epicentr_xml->saveEpicentrQuantity($product_id, $quantity);

		if($result) {
			return true;
		} else {
			return false;
		}
	}


	public function saveEpicentrStatus() {


		$product_id    = $this->request->post['product_id'];
		$status        = $this->request->post['status'];

		$this->load->model('feed/epicentr_xml');

		$result = $this->model_feed_epicentr_xml->saveEpicentrStatus($product_id, $status);

		if($result) {
			return true;
		} else {
			return false;
		}
	}



	public function check_licence(){

		$server  = 'https://licence.ionline.su'; 
		$request = $server.'/check.php?module=epicentr_xml&version='.$this->version.'&domain='.strtolower($_SERVER['SERVER_NAME']).'&server='.strtolower($_SERVER['SERVER_NAME']).'&opencart='.VERSION;

		$options = array(
	        CURLOPT_RETURNTRANSFER => true,   // return web page
	        CURLOPT_HEADER         => false,  // don't return headers
	        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
	        CURLOPT_MAXREDIRS      => 5,      // stop after 5 redirects
	        CURLOPT_ENCODING       => "",     // handle compressed
	        CURLOPT_USERAGENT      => $_SERVER['SERVER_NAME'], // name of client
	        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
	        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
	        CURLOPT_TIMEOUT        => 120,    // time-out on response
	    ); 

	    $ch = curl_init($request);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);

	  	if($content == 'OK'){
	  		return 1;
	  	} else {
	  		return 0;
	  	}

	}

	public function check_version(){

		$server  = 'https://licence.ionline.su';
        $request = $server.'/version.php?module=epicentr_xml&version='.$this->version.'&opencart='.VERSION;

        $options = array(
	        CURLOPT_RETURNTRANSFER => true,   // return web page
	        CURLOPT_HEADER         => false,  // don't return headers
	        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
	        CURLOPT_MAXREDIRS      => 5,      // stop after 5 redirects
	        CURLOPT_ENCODING       => "",     // handle compressed
	        CURLOPT_USERAGENT      => $_SERVER['SERVER_NAME'], // name of client
	        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
	        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
	        CURLOPT_TIMEOUT        => 120,    // time-out on response
	    ); 

	    $ch = curl_init($request);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);

	  	if($content == 'yes'){
	  		return 1;
	  	} else {
	  		return 0;
	  	}


	}


	   public function authorization_loguot(){

	   }

	   public function authorization_ajax(){

	   }

    public function authorization($login, $pass, $local){

    }

	public function local_import(){

	}

    public function api_import(){

    }

 	public function getEpicentrCategories($token, $page = 1){
        
    }



	public function check_tables(){

		$this->load->model('feed/epicentr_xml');
		$result = $this->model_feed_epicentr_xml->checkTables();

		echo "<table style='width:100%;'>";
		echo "<tr><td style='width:33%; text-align:center'>ТАБЛИЦА</td><td style='width:33%;text-align:center;'>КОЛОНКА</td><td style='width:33%;text-align:center;'>СОСТОЯНИЕ</td></tr>";
		foreach ($result as $line) {
		
			if($line['exists'] == 1){
				echo '<tr><td>'.$line['table'].'</td><td>'. $line['column'].'</td><td><span style="color:green;">присутствует</span></td></tr>';
			} else {
				echo '<tr><td>'.$line['table'].'</td><td>'. $line['column'].'</td><td><span style="color:red;">отсутствует</span></td></tr>';
			}

		}

		echo "</table>";		
	}

	
	public function install_tables(){

		$this->load->model('feed/epicentr_xml');
		$result = $this->model_feed_epicentr_xml->install();

		echo '<div style="text-align:center; line-height:40px;">Установка прошла успешно. Выполните проверку базы</div>';
		
	}



	public function saveMarketData($data){

	}



	public function getMarketData(){

	}


	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {

			$this->load->model('feed/epicentr_xml');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 8
			);

			$results = $this->model_feed_epicentr_xml->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


}
?>
