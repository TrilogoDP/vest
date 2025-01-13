<?php
class ControllerExtensionModuleCurrencyUpdate extends Controller {
	
	private $error = array();
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->load->language('extension/module/currency_update');
		
		//error_reporting(-1);
		//ini_set('display_errors', 1);
	}

	public function index() {

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_source_alphavantage'] = $this->language->get('text_source_alphavantage');
		$data['text_source_fixer'] = $this->language->get('text_source_fixer');
		$data['text_source_xe'] = $this->language->get('text_source_xe');
		$data['text_alphavantage_api_key'] = $this->language->get('text_alphavantage_api_key');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_source'] = $this->language->get('entry_source');
		$data['entry_autoupdate'] = $this->language->get('entry_autoupdate');
		$data['entry_comission'] = $this->language->get('entry_comission');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_alphavantage_api_key'] = $this->language->get('entry_alphavantage_api_key');
		
		$data['help_source'] = $this->language->get('help_source');
		$data['help_autoupdate'] = $this->language->get('help_autoupdate');
		$data['help_comission'] = $this->language->get('help_comission');
		$data['help_alphavantage_api_key'] = $this->language->get('help_alphavantage_api_key');
		
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_update'] = $this->language->get('button_update');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_currency_update', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}
		
		if (isset($this->session->data['success'])) {
			$data['error_success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['error_success'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['alphavantage_api_key'])) {
			$data['error_alphavantage_api_key'] = $this->error['alphavantage_api_key'];
		} else {
			$data['error_alphavantage_api_key'] = '';
		}
		
		if (isset($this->error['comission'])) {
			$data['error_comission'] = $this->error['comission'];
		} else {
			$data['error_comission'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/currency_update', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/currency_update', 'token=' . $this->session->data['token'], true);

		$data['update'] = $this->url->link('extension/module/currency_update/update', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		if (isset($this->request->post['module_currency_update_autoupdate'])) {
			$data['module_currency_update_autoupdate'] = $this->request->post['module_currency_update_autoupdate'];
		} elseif ($this->config->get('module_currency_update_autoupdate')) {
			$data['module_currency_update_autoupdate'] = $this->config->get('module_currency_update_autoupdate');
		} else {
			$data['module_currency_update_autoupdate'] = 0;
		}
		
		if (isset($this->request->post['module_currency_update_source'])) {
			$data['module_currency_update_source'] = $this->request->post['module_currency_update_source'];
		} elseif ($this->config->get('module_currency_update_source')) {
			$data['module_currency_update_source'] = $this->config->get('module_currency_update_source');
		} else {
			$data['module_currency_update_source'] = 'fixer.io';
		}
		
		if (isset($this->request->post['module_currency_update_alphavantage_api_key'])) {
			$data['module_currency_update_alphavantage_api_key'] = $this->request->post['module_currency_update_alphavantage_api_key'];
		} elseif ($this->config->get('module_currency_update_alphavantage_api_key')) {
			$data['module_currency_update_alphavantage_api_key'] = $this->config->get('module_currency_update_alphavantage_api_key');
		} else {
			$data['module_currency_update_alphavantage_api_key'] = '';
		}
		
		if (isset($this->request->post['module_currency_update_comission'])) {
			$data['module_currency_update_comission'] = $this->request->post['module_currency_update_comission'];
		} elseif ($this->config->get('module_currency_update_comission')) {
			$data['module_currency_update_comission'] = $this->config->get('module_currency_update_comission');
		} else {
			$data['module_currency_update_comission'] = 0;
		}
		
		if (isset($this->request->post['module_currency_update_debug'])) {
			$data['module_currency_update_debug'] = $this->request->post['module_currency_update_debug'];
		} elseif ($this->config->get('module_currency_update_comission')) {
			$data['module_currency_update_debug'] = $this->config->get('module_currency_update_debug');
		} else {
			$data['module_currency_update_debug'] = 0;
		}
		
		if (isset($this->request->post['module_currency_update_status'])) {
			$data['module_currency_update_status'] = $this->request->post['module_currency_update_status'];
		} elseif ($this->config->get('module_currency_update_status')) {
			$data['module_currency_update_status'] = $this->config->get('module_currency_update_status');
		} else {
			$data['module_currency_update_status'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/currency_update', $data));
	}
	
	public function update() {	
		$this->load->model('extension/module/currency_update');
		$status = $this->model_extension_module_currency_update->update(true);
		
		if ($status) {
			$this->session->data['success'] = $this->language->get('text_update_success');
		} else {
			$this->session->data['warning'] = $this->language->get('text_update_error');
		}
		
		$this->response->redirect($this->url->link('extension/module/currency_update', 'token=' . $this->session->data['token'], true));
	}
	
	public function cron() {	
		$this->load->model('extension/module/currency_update');
		$status = $this->model_extension_module_currency_update->update(true);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/currency_update')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->request->post['module_currency_update_source'] == 'alphavantage.co') {
			if (strlen($this->request->post['module_currency_update_alphavantage_api_key']) <= 0) {
				$this->error['alphavantage_api_key'] = $this->language->get('error_alphavantage_api_key');
			}
		}
		
		if (strlen($this->request->post['module_currency_update_comission']) > 0) {
			preg_match_all('/^(?:\d+|\d*\.\d+)$/', $this->request->post['module_currency_update_comission'], $matches, PREG_SET_ORDER, 0);
			if (empty($matches)) {
				$this->error['comission'] = $this->language->get('error_comission');
			}
		}


		return !$this->error;
	}
}