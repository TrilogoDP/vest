<?php
	class ControllerCatalogCategory extends Controller {

	public function filter() {
		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category/filter');
		} else {
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
		}
	}

	public function category() {
		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category/category');
		} else {
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
		}
	}

	public function load_popup() {
		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category/load_popup');
		} else {
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
		}
	}

	public function refresh_data() {
		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category/refresh_data');
		} else {
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
		}
	}

	public function quick_update() {
		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category/quick_update');
		} else {
			$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
		}
	}
			
		private $error = array();
		private $category_id = 0;
		private $path = array();
		

//quicksave
	public function qsave() {
		$this->language->load('catalog/category');

		$this->load->model('catalog/category');

		$json = array();

		if ($this->validateForm()) {
			$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);
			$json['success'] = ($this->language->get('text_success')).' --- '.(date("Y-m-d - H:i:s"));
		} else {
			$json['error'] = $this->error;
		}

		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($json));
	}
//quicksave end
			
		public function index() {

		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category');
		}
			
			$this->load->language('catalog/category');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/category');
			
			$this->getList();
		}
		
		public function add() {
			$this->load->language('catalog/category');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/category');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_category->addCategory($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
			}
			
			$this->getForm();
		}
		
		public function edit() {
			$this->load->language('catalog/category');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/category');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_category->editCategory($this->request->get['category_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
			}
			
			$this->getForm();
		}
		
		public function delete() {

		$this->load->model('feed/google_merchant_center');
			

		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category/delete');
		}
			
			$this->load->language('catalog/category');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/category');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $category_id) {
					$this->model_catalog_category->deleteCategory($category_id);

		$this->model_feed_google_merchant_center->removeCategory($category_id);
			
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
			}
			
			$this->getList();
		}
		
		public function repair() {
			$this->load->language('catalog/category');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/category');
			
			if ($this->validateRepair()) {
				$this->model_catalog_category->repairCategories();
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				$this->response->redirect($this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true));
			}
			
			$this->getList();
		}
		
		protected function getList() {
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'name';
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = 'ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$url = '';
			

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			$data['add'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, true);
			$data['delete'] = $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . $url, true);
			$data['repair'] = $this->url->link('catalog/category/repair', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['categories'] = array();
			
			if (isset($this->request->get['path'])) {
				if ($this->request->get['path'] != '') {
					$this->path = explode('_', $this->request->get['path']);
					$this->category_id = end($this->path);
					$this->session->data['path'] = $this->request->get['path'];
					} else {
					unset($this->session->data['path']);
				}
				} elseif (isset($this->session->data['path'])) {
				$this->path = explode('_', $this->session->data['path']);
				$this->category_id = end($this->path);
			}
			
			$data['categories'] = $this->getCategories(0);

			foreach ($data['categories'] as &$tmp_category) {
				$tmp_category['viewed'] = $this->model_catalog_category->getCategorySuperStat($tmp_category['category_id']);
			}			
			
			$category_total = count($data['categories']);
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_list'] = $this->language->get('text_list');
			$data['text_no_results'] = $this->language->get('text_no_results');
			$data['text_confirm'] = $this->language->get('text_confirm');
			
			$data['column_name'] = $this->language->get('column_name');
			$data['column_sort_order'] = $this->language->get('column_sort_order');
			$data['column_action'] = $this->language->get('column_action');
			
			$data['button_add'] = $this->language->get('button_add');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_delete'] = $this->language->get('button_delete');
			$data['button_rebuild'] = $this->language->get('button_rebuild');
			
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
			
			if (isset($this->request->post['selected'])) {
				$data['selected'] = (array)$this->request->post['selected'];
				} else {
				$data['selected'] = array();
			}
			
			$url = '';
			
			if ($order == 'ASC') {
				$url .= '&order=DESC';
				} else {
				$url .= '&order=ASC';
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$data['sort_name'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
			$data['sort_sort_order'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);
			
			$url = '';
			

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			$pagination = new Pagination();
			$pagination->total = $category_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');
			$pagination->url = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
			
			$data['pagination'] = $pagination->render();
			
			$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('catalog/category_list', $data));
		}
		
		protected function getForm() {
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
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
			$data['text_none'] = $this->language->get('text_none');
			$data['text_default'] = $this->language->get('text_default');
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_select_all'] = $this->language->get('text_select_all');
			$data['text_unselect_all'] = $this->language->get('text_unselect_all');
			
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_description'] = $this->language->get('entry_description');
			$data['entry_meta_title'] = $this->language->get('entry_meta_title');
			$data['entry_meta_h1'] = $this->language->get('entry_meta_h1');
			$data['entry_meta_description'] = $this->language->get('entry_meta_description');
			$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
			$data['entry_keyword'] = $this->language->get('entry_keyword');
			$data['entry_parent'] = $this->language->get('entry_parent');
			$data['entry_filter'] = $this->language->get('entry_filter');
			$data['entry_store'] = $this->language->get('entry_store');
			$data['entry_image'] = $this->language->get('entry_image');
			$data['entry_top'] = $this->language->get('entry_top');
			$data['entry_column'] = $this->language->get('entry_column');
			$data['entry_sort_order'] = $this->language->get('entry_sort_order');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_layout'] = $this->language->get('entry_layout');
			
			$data['help_filter'] = $this->language->get('help_filter');
			$data['help_keyword'] = $this->language->get('help_keyword');
			$data['help_top'] = $this->language->get('help_top');
			$data['help_column'] = $this->language->get('help_column');
			
			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');

				$data['column_faq_name'] = $this->language->get('column_faq_name');
				$data['column_question'] = $this->language->get('column_question');
				$data['column_faq'] = $this->language->get('column_faq');
				$data['column_icon'] = $this->language->get('column_icon');
				$data['tab_faq'] = $this->language->get('tab_faq');
				$data['faq_name'] = $this->language->get('faq_name');
				$data['button_remove'] = $this->language->get('button_remove');
			
			
			$data['tab_general'] = $this->language->get('tab_general');
			$data['tab_data'] = $this->language->get('tab_data');
			$data['tab_design'] = $this->language->get('tab_design');
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
				} else {
				$data['error_warning'] = '';
			}
			
			if (isset($this->error['name'])) {
				$data['error_name'] = $this->error['name'];
				} else {
				$data['error_name'] = array();
			}
			
			if (isset($this->error['keyword'])) {
				$data['error_keyword'] = $this->error['keyword'];
				} else {
				$data['error_keyword'] = '';
			}
			
			if (isset($this->error['parent'])) {
				$data['error_parent'] = $this->error['parent'];
				} else {
				$data['error_parent'] = '';
			}
			
			$url = '';
			

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true)
			);
			
			if (!isset($this->request->get['category_id'])) {
				$data['action'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'] . $url, true);
				} else {
				$data['action'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $this->request->get['category_id'] . $url, true);
			}
			

//quicksave
	$data['pidqs'] = isset($this->request->get['category_id']) ? $this->request->get['category_id'] : '';
//quicksave end
			
			$data['cancel'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . $url, true);
			
			if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);			
			}
			
			$data['token'] = $this->session->data['token'];
			$data['ckeditor'] = $this->config->get('config_editor_default');
			

if ($this->user->hasPermission('modify', 'module/auto_translator')) {
    $data['at_status'] = $this->config->get('auto_translator_status');
} else { $data['at_status'] = 0; }
$data['at_source'] = $this->config->get('auto_translator_source');
$data['at_api_key'] = $this->config->get('auto_translator_api_key');
$data['at_g_language'] = $this->config->get('auto_translator_g_language');
$data['at_m_language'] = $this->config->get('auto_translator_m_language');
$data['m_token'] = '';
$this->load->model('module/auto_translator');
if ($this->config->get('auto_translator_microsoft_key')) { $token = $this->model_module_auto_translator->getСognitiveMicrosoftToken();
} else { $token = $this->model_module_auto_translator->getMicrosoftToken(); }
if(is_object($token)){ if(isset($token->message)){ $data['microsoft_token_error'] = 'Error: ' . $token->message;
    } else if(isset($token->error->message)){ $data['microsoft_token_error'] = 'Error: ' . $token->error->message;
    } else if(isset($token->error_description)){ $data['microsoft_token_error'] = 'Error: ' . $token->error_description;
    } else { $data['microsoft_token_error'] = 'Error: Failed to generate Microsoft API token!'; }
} else { $data['m_token'] = $token; }
$this->load->language('module/auto_translator');
$data['btn_translate'] = $this->language->get('button_translate');
$data['btn_translate_to'] = $this->language->get('button_translate_to');
$data['btn_translate_all'] = $this->language->get('button_translate_all');
$data['btn_copy'] = $this->language->get('button_copy');
$data['btn_copy_to'] = $this->language->get('button_copy_to');
$data['btn_copy_all'] = $this->language->get('button_copy_all');
$data['btn_copy_name'] = $this->language->get('button_copy_name');
			$this->load->model('localisation/language');
			
			$data['languages'] = $this->model_localisation_language->getLanguages();
			
			$data['lang'] = $this->language->get('lang');
			
			if (isset($this->request->post['category_description'])) {
				$data['category_description'] = $this->request->post['category_description'];
				} elseif (isset($this->request->get['category_id'])) {
				$data['category_description'] = $this->model_catalog_category->getCategoryDescriptions($this->request->get['category_id']);
				} else {
				$data['category_description'] = array();
			}
			
			if (isset($data['category_description'][$this->config->get('config_language_id')])){			
				$data['heading_title'] = $data['category_description'][$this->config->get('config_language_id')]['name'];
			}
			
			// Categories
			$categories = $this->model_catalog_category->getAllCategories();
			$data['categories'] = $this->getAllCategories($categories);
			$data['all_categories'] = $data['categories'];
			
			if (isset($category_info)) {
				unset($data['categories'][$category_info['category_id']]);
				unset($data['all_categories'][$category_info['category_id']]);
			}
			
			if (isset($this->request->post['category_category'])) {
				$data['category_category'] = $this->request->post['category_category'];
				} elseif (isset($this->request->get['category_id'])) {
				$data['category_category'] = $this->model_catalog_category->getCategoryRelated($this->request->get['category_id']);
				} else {
				$data['category_category'] = array();
			}
			
			if (isset($this->request->post['category_excluded'])) {
				$data['category_excluded'] = $this->request->post['category_excluded'];
				} elseif (isset($this->request->get['category_id'])) {
				$data['category_excluded'] = $this->model_catalog_category->getCategoryExcluded($this->request->get['category_id']);
				} else {
				$data['category_excluded'] = array();
			}
			
			if (isset($this->request->post['parent_id'])) {
				$data['parent_id'] = $this->request->post['parent_id'];
				} elseif (!empty($category_info)) {
				$data['parent_id'] = $category_info['parent_id'];
				} else {
				$data['parent_id'] = 0;
			}
			
			$this->load->model('catalog/filter');
			
			if (isset($this->request->post['category_filter'])) {
				$filters = $this->request->post['category_filter'];
				} elseif (isset($this->request->get['category_id'])) {
				$filters = $this->model_catalog_category->getCategoryFilters($this->request->get['category_id']);
				} else {
				$filters = array();
			}
			
			$data['category_filters'] = array();
			
			foreach ($filters as $filter_id) {
				$filter_info = $this->model_catalog_filter->getFilter($filter_id);
				
				if ($filter_info) {
					$data['category_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
					);
				}
			}
			
			$this->load->model('setting/store');
			
			$data['stores'] = $this->model_setting_store->getStores();
			
			if (isset($this->request->post['category_store'])) {
				$data['category_store'] = $this->request->post['category_store'];
				} elseif (isset($this->request->get['category_id'])) {
				$data['category_store'] = $this->model_catalog_category->getCategoryStores($this->request->get['category_id']);
				} else {
				$data['category_store'] = array(0);
			}
			



                $this->load->model('feed/epicentr_xml');

				if (isset($this->request->post['epicentr_name'])) {
					$data['epicentr_name'] = $this->request->post['epicentr_name'];
				} elseif (!empty($category_info)) {
					$data['epicentr_name'] = $category_info['epicentr_name'];
				} else {
					$data['epicentr_name'] = '';
				}

				if (isset($this->request->post['epicentr_country'])) {
					$data['epicentr_country'] = $this->request->post['epicentr_country'];
				} elseif (!empty($category_info)) {
					$data['epicentr_country'] = $category_info['epicentr_country'];
				} else {
					$data['epicentr_country'] = '';
				}


				if (isset($this->request->post['epicentr_coeff'])) {
					$data['epicentr_coeff'] = $this->request->post['epicentr_coeff'];
				} elseif (!empty($category_info)) {
					$data['epicentr_coeff'] = $category_info['epicentr_coeff'];
				} else {
					$data['epicentr_coeff'] = '';
				}
				
				if (isset($this->request->post['epicentr_category_id'])) {
					$data['epicentr_category_id'] = $this->request->post['epicentr_category_id'];
				} elseif (!empty($category_info)) {
					$data['epicentr_category_id'] = $category_info['epicentr_category_id'];
				} else {
					$data['epicentr_category_id'] = '';
				}
				
				if($data['epicentr_category_id'] > 0){
					$data['epicentr_category_name'] = $this->model_feed_epicentr_xml->getCategoryNameById($data['epicentr_category_id']);
				} else {
					$data['epicentr_category_name'] = '';
				}
				
				

			
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
				} elseif (!empty($category_info)) {
				$data['keyword'] = $category_info['keyword'];
				} else {
				$data['keyword'] = '';
			}

			if (isset($this->request->post['top_svg'])) {
				$data['top_svg'] = $this->request->post['top_svg'];
				} elseif (!empty($category_info)) {
				$data['top_svg'] = $category_info['top_svg'];
				} else {
				$data['top_svg'] = '';
			}
			
			if (isset($this->request->post['image'])) {
				$data['image'] = $this->request->post['image'];
				} elseif (!empty($category_info)) {
				$data['image'] = $category_info['image'];
				} else {
				$data['image'] = '';
			}
			
			$this->load->model('tool/image');
			
			if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
				} elseif (!empty($category_info) && is_file(DIR_IMAGE . $category_info['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
				} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
			
			$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			
			if (isset($this->request->post['top'])) {
				$data['top'] = $this->request->post['top'];
				} elseif (!empty($category_info)) {
				$data['top'] = $category_info['top'];
				} else {
				$data['top'] = 0;
			}
			
			if (isset($this->request->post['free_delivery'])) {
				$data['free_delivery'] = $this->request->post['free_delivery'];
				} elseif (!empty($category_info)) {
				$data['free_delivery'] = $category_info['free_delivery'];
				} else {
				$data['free_delivery'] = 0;
			}
			
			if (isset($this->request->post['addon_discount'])) {
				$data['addon_discount'] = $this->request->post['addon_discount'];
				} elseif (!empty($category_info)) {
				$data['addon_discount'] = $category_info['addon_discount'];
				} else {
				$data['addon_discount'] = 0;
			}
			
			if (isset($this->request->post['afp_discount'])) {
				$data['afp_discount'] = $this->request->post['afp_discount'];
				} elseif (!empty($category_info)) {
				$data['afp_discount'] = $category_info['afp_discount'];
				} else {
				$data['afp_discount'] = 0;
			}

			if (isset($this->request->post['rozetka_overprice'])) {
				$data['rozetka_overprice'] = $this->request->post['rozetka_overprice'];
				} elseif (!empty($category_info)) {
				$data['rozetka_overprice'] = $category_info['rozetka_overprice'];
				} else {
				$data['rozetka_overprice'] = 0;
			}
			
			if (isset($this->request->post['efs'])) {
				$data['efs'] = $this->request->post['efs'];
				} elseif (!empty($category_info)) {
				$data['efs'] = $category_info['efs'];
				} else {
				$data['efs'] = 0;
			}
			
			if (isset($this->request->post['column'])) {
				$data['column'] = $this->request->post['column'];
				} elseif (!empty($category_info)) {
				$data['column'] = $category_info['column'];
				} else {
				$data['column'] = 1;
			}
			
			if (isset($this->request->post['sort_order'])) {
				$data['sort_order'] = $this->request->post['sort_order'];
				} elseif (!empty($category_info)) {
				$data['sort_order'] = $category_info['sort_order'];
				} else {
				$data['sort_order'] = 0;
			}
			
			if (isset($this->request->post['status'])) {
				$data['status'] = $this->request->post['status'];
				} elseif (!empty($category_info)) {
				$data['status'] = $category_info['status'];
				} else {
				$data['status'] = true;
			}
			
			if (isset($this->request->post['rozetka'])) {
				$data['rozetka'] = $this->request->post['rozetka'];
				} elseif (!empty($category_info)) {
				$data['rozetka'] = $category_info['rozetka'];
				} else {
				$data['rozetka'] = false;
			}
			
			if (isset($this->request->post['prom'])) {
				$data['prom'] = $this->request->post['prom'];
				} elseif (!empty($category_info)) {
				$data['prom'] = $category_info['prom'];
				} else {
				$data['prom'] = false;
			}
			
			if (isset($this->request->post['hotline'])) {
				$data['hotline'] = $this->request->post['hotline'];
				} elseif (!empty($category_info)) {
				$data['hotline'] = $category_info['hotline'];
				} else {
				$data['hotline'] = false;
			}
			
			if (isset($this->request->post['hide_manufacturer'])) {
				$data['hide_manufacturer'] = $this->request->post['hide_manufacturer'];
				} elseif (!empty($category_info)) {
				$data['hide_manufacturer'] = $category_info['hide_manufacturer'];
				} else {
				$data['hide_manufacturer'] = false;
			}

			$this->load->model('feed/google_merchant_center');
			$this->language->load('feed/google_merchant_center');

			$data['entry_google_merchant_category'] = $this->language->get('entry_google_merchant_category');
			$data['help_google_merchant_category'] 	= $this->language->get('help_google_merchant_category');

			if (isset($this->request->post['google_merchant_center_status'])) {
				$data['google_merchant_center_status'] = $this->request->post['google_merchant_center_status'];
			} else {
				$data['google_merchant_center_status'] = $this->config->get('google_merchant_center_status');
			}

			if (isset($this->request->post['facebook_catalog_status'])) {
				$data['facebook_catalog_status'] = $this->request->post['facebook_catalog_status'];
			} else {
				$data['facebook_catalog_status'] = $this->config->get('facebook_catalog_status');
			}

			if (isset($this->request->post['taxonomy_id'])) {
				$data['taxonomy_id'] = $this->request->post['taxonomy_id'];
				} elseif (!empty($category_info)) {
				$data['taxonomy_id'] = $category_info['taxonomy_id'];
				} else {
				$data['taxonomy_id'] = false;
			}

			$google_taxonomy_category = $this->model_feed_google_merchant_center->getCategory(0);
			foreach ($google_taxonomy_category as $google_category) {
				$data['google_merchant_category'][] = array (
					'taxonomy_id' 	=> $google_category['taxonomy_id'],				
					'status' 		=> $google_category['status'],
					'name' 			=> $google_category['name']
				);
			}
			
						
				
				$data['category_faq'] = array(); 
				if (isset($this->request->post['category_faq'])) {
				$category_faq = $this->request->post['category_faq'];
				} elseif (isset($this->request->get['category_id'])) {
				$category_faq = $this->model_catalog_category->getCategoryFaq($this->request->get['category_id']);
				} else {
				$category_faq = array();
				}
				
				$data['category_faq'] = array();
				
				foreach ($category_faq as $category_faq) {
				$data['category_faq'][] = array(
				'question'       => unserialize($category_faq['question']),
				'faq'       => unserialize($category_faq['faq']),
				'icon'     => $category_faq['icon'],
				'sort_order' => $category_faq['sort_order']
				);
				}
			
			if (isset($this->request->post['category_layout'])) {
				$data['category_layout'] = $this->request->post['category_layout'];
				} elseif (isset($this->request->get['category_id'])) {
				$data['category_layout'] = $this->model_catalog_category->getCategoryLayouts($this->request->get['category_id']);
				} else {
				$data['category_layout'] = array();
			}
			
			$this->load->model('design/layout');
			
			$data['layouts'] = $this->model_design_layout->getLayouts();
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('catalog/category_form', $data));
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/category')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($this->request->post['category_description'] as $language_id => $value) {
				if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
					$this->error['name'][$language_id] = $this->language->get('error_name');
				}
			}
			
			if (isset($this->request->get['category_id']) && $this->request->post['parent_id']) {
				$results = $this->model_catalog_category->getCategoryPath($this->request->post['parent_id']);
				
				foreach ($results as $result) {
					if ($result['path_id'] == $this->request->get['category_id']) {
						$this->error['parent'] = $this->language->get('error_parent');
						
						break;
					}
				}
			}
			
			if (utf8_strlen($this->request->post['keyword']) > 0) {
				$this->load->model('catalog/url_alias');
				
				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);
				
				if ($url_alias_info && isset($this->request->get['category_id']) && $url_alias_info['query'] != 'category_id=' . $this->request->get['category_id']) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}
				
				if ($url_alias_info && !isset($this->request->get['category_id'])) {
					$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
				}
			}
			
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			
			return !$this->error;
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/category')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			return !$this->error;
		}
		
		protected function validateRepair() {
			if (!$this->user->hasPermission('modify', 'catalog/category')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			return !$this->error;
		}
		
		private function getCategories($parent_id, $parent_path = '', $indent = '') {
			$category_id = array_shift($this->path);
			
			$output = array();
			
			static $href_category = null;
			static $href_action = null;
			
			if ($href_category === null) {
				$href_category = $this->url->link('catalog/category', 'token=' . $this->session->data['token'] . '&path=', 'SSL');
				$href_action = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=', 'SSL');
			}
			
			$url = '';
			

		if ($this->config->get('aqe_status') && $this->config->get('aqe_catalog_categories_status')) {
			foreach ($this->config->get('aqe_catalog_categories') as $column => $attr) {
				if ($attr['filter']['show'] && isset($this->request->get['filter_' . $column])) {
					$url .= '&filter_' . $column . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $column], ENT_QUOTES, 'UTF-8'));
				}
			}
		}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$results = $this->model_catalog_category->getCategoriesByParentId($parent_id);
			
			foreach ($results as $result) {
				$path = $parent_path . $result['category_id'];
				
				$href = ($result['children']) ? $href_category . $path : '';
				
				$name = $result['name'];
				
				if ($category_id == $result['category_id']) {
					$name = '<b>' . $name . '</b>';
					
					$data['breadcrumbs'][] = array(
					'text'      => $result['name'],
					'href'      => $href,
					'separator' => ' :: '
					);
					
					$href = '';
				}
				
				$selected = isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']);
				
				$action = array();
				
				$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $href_action . $result['category_id']
				);
				
				$output[$result['category_id']] = array(
				'category_id' 		=> $result['category_id'],
				'taxonomy'    		=> $this->model_catalog_category->getTaxonomyName($result['taxonomy_id']),
				'name'        		=> $name,
				'alternate_name'    => $result['alternate_name'],
				'rozetka'     		=> $result['rozetka'],
				'hotline'     		=> $result['hotline'],
				'prom'        		=> $result['prom'],
				'addon_discount' 	=> $result['addon_discount'],
				'rozetka_overprice'	=> $result['rozetka_overprice'],
				'afp_discount' 		=> $result['afp_discount'],
				'free_delivery' 	=> $result['free_delivery'],
				'sort_order'  		=> $result['sort_order'],
				'selected'    		=> $selected,
				'action'      		=> $action,
				'edit'        		=> $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL'),
				'delete'      		=> $this->url->link('catalog/category/delete', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'] . $url, 'SSL'),
				'href'        		=> $href,
				'indent'      		=> $indent
				);
				
				if ($category_id == $result['category_id']) {
					$output += $this->getCategories($result['category_id'], $path . '_', $indent . str_repeat('&nbsp;', 8));
				}
			}
			
			return $output;
		}
		
		private function getAllCategories($categories, $parent_id = 0, $parent_name = '') {
			$output = array();
			
			if (array_key_exists($parent_id, $categories)) {
				if ($parent_name != '') {
					//$parent_name .= $this->language->get('text_separator');
					$parent_name .= ' &gt; ';
				}
				
				foreach ($categories[$parent_id] as $category) {
					$output[$category['category_id']] = array(
					'category_id' => $category['category_id'],
					'name'        => $parent_name . $category['name']
					);
					
					$output += $this->getAllCategories($categories, $category['category_id'], $parent_name . $category['name']);
				}
			}
			
			uasort($output, array($this, 'sortByName'));
			
			return $output;
		}
		
		function sortByName($a, $b) {
			return strcmp($a['name'], $b['name']);
		}
		
		public function autocomplete() {

		if ((int)$this->config->get('aqe_status') && (int)$this->config->get('aqe_catalog_categories_status')) {
			return $this->load->controller('catalog/aqe/category/autocomplete');
		}
			
			$json = array();
			
			if (isset($this->request->get['filter_name'])) {
				$this->load->model('catalog/category');
				
				$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
				);
				
				$results = $this->model_catalog_category->getCategories($filter_data);
				
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
