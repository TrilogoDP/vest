<?php
class ControllerExtensionModuleFbpixel extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/fbpixel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fbpixel', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$language_strings = $this->language->load('extension/module/fbpixel');

		foreach ($language_strings as $code => $languageVariable) {
		     $data[$code] = $languageVariable;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['fbpixel_id'])) {
			$data['error_pixel_id'] = $this->error['fbpixel_id'];
		} else {
			$data['error_pixel_id'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/fbpixel', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/fbpixel', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['fbpixel_id'])) {
			$data['fbpixel_id'] = $this->request->post['fbpixel_id'];
		} else {
			$data['fbpixel_id'] = $this->config->get('fbpixel_id');
		}

		if (isset($this->request->post['fbpixel_status'])) {
			$data['fbpixel_status'] = $this->request->post['fbpixel_status'];
		} else {
			$data['fbpixel_status'] = $this->config->get('fbpixel_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/fbpixel', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/fbpixel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['fbpixel_id']) {
			$this->error['fbpixel_id'] = $this->language->get('error_id');
		}

		return !$this->error;
	}
}