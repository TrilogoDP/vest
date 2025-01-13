<?php
/**************************************************************/
/*	@copyright	OCTemplates 2019.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleOctInformationBar extends Controller {
    private $error = [];

    public function index() {
	    $data = [];
	    
	    $data = array_merge($data, $this->load->language('extension/module/oct_information_bar'));
		
		// Spectrum
		$this->document->addScript('view/javascript/spectrum/spectrum.js');
		$this->document->addStyle('view/javascript/spectrum/spectrum.css');
		
		//CKEditor
        if ($this->config->get('config_editor_default')) {
            $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
            $this->document->addScript('view/javascript/ckeditor/ckeditor_init.js');
        } else {
            $this->document->addScript('view/javascript/summernote/summernote.js');
            $this->document->addScript('view/javascript/summernote/lang/summernote-' . $this->language->get('lang') . '.js');
            $this->document->addScript('view/javascript/summernote/opencart.js');
            $this->document->addStyle('view/javascript/summernote/summernote.css');
        }
		
        $this->document->setTitle($this->language->get('heading_title'));
		
        $this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('oct_information_bar', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/oct_information_bar', 'token=' . $this->session->data['token'] . '&type=module', true));
        }
		
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        ];
		
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/oct_information_bar', 'token=' . $this->session->data['token'], true)
        ];
		
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
		
		if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
		
        $data['action'] = $this->url->link('extension/module/oct_information_bar', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] . '&type=module', true);

        $data['token'] = $this->session->data['token'];
		$data['ckeditor']  = $this->config->get('config_editor_default');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data = array_merge($data, $this->load->language('extension/module/oct_information_bar'));
		
		$this->load->model('catalog/information');
		
		$data['informations'] = [];

		$filter_data = [
			'sort'  => 'id.title',
			'order' => 'ASC',
			'start' => 0,
			'limit' => 10000
		];

		$informations_info = $this->model_catalog_information->getInformations($filter_data);

		foreach ($informations_info as $result) {
			$data['informations'][] = [
				'information_id' => $result['information_id'],
				'title'          => $result['title']
			];
		}
		
        if (isset($this->request->post['oct_information_bar_status'])) {
			$data['oct_information_bar_status'] = $this->request->post['oct_information_bar_status'];
		} else {
			$data['oct_information_bar_status'] = $this->config->get('oct_information_bar_status');
		}
		
        if (isset($this->request->post['oct_information_bar_data'])) {
			$data['oct_information_bar_data'] = $this->request->post['oct_information_bar_data'];
		} else {
			$data['oct_information_bar_data'] = $this->config->get('oct_information_bar_data');
		}

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');
		
        $this->response->setOutput($this->load->view('extension/module/oct_information_bar', $data));
    }

    public function install() {
        $this->load->language('extension/module/oct_information_bar');


        $this->load->model('localisation/language');
        $this->load->model('extension/extension');
        $this->load->model('setting/setting');
        
        $results = $this->model_localisation_language->getLanguages();
		
		$module_text = [];
		
        foreach ($results as $result) {
            $module_text[$result['language_id']] = '';
        }

        $this->model_setting_setting->editSetting('oct_information_bar', [
	        'oct_information_bar_status' => '1',
            'oct_information_bar_data' => [
	            'indormation_id' => 0,
	            'max_day' => 1,
	            'background_bar' => 'rgb(83, 194, 232)',
	            'color_text' => 'rgb(255, 255, 255)',
	            'color_url' => 'rgb(86, 96, 114)',
	            'background_button' => 'rgb(86, 96, 114)',
	            'background_button_hover' => 'rgb(255, 255, 255)',
	            'color_text_button' => 'rgb(255, 255, 255)',
	            'color_text_button_hover' => 'rgb(0, 0, 0)',
	            'value' => 'oct_information_bar',
	            'module_text' => $module_text
            ]
        ]);
        
        $this->session->data['success'] = $this->language->get('text_success');
    }
    
    public function uninstall() {
	    $this->load->model('setting/setting');
	    
	     $this->model_setting_setting->deleteSetting('oct_information_bar');
    }
    
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/oct_information_bar')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}