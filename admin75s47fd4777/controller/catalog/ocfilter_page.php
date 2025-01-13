<?php
	class ControllerCatalogOCFilterPage extends Controller {
		private $error = array();
		private $warning = array();

		private $get = array(
		'filter_name' => null,
		'filter_type' => null,
		'filter_status' => null,
		'filter_category_id' => null,
		'page' => 1,
		'sort' => 'op.name',
		'order' => 'ASC'
		);
		
		public function index() {
			$this->load->language('catalog/ocfilter_page');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter_page');
			
			$this->getList();
		}
		
		public function add() {
			$this->load->language('catalog/ocfilter_page');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter_page');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_ocfilter_page->addPage($this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
				}
				
				if (isset($this->request->get['filter_title'])) {
					$url .= '&filter_title=' . $this->request->get['filter_title'];
				}

				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
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
				
				$this->response->redirect($this->url->link('catalog/ocfilter_page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function edit() {
			$this->load->language('catalog/ocfilter_page');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter_page');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_ocfilter_page->editPage($this->request->get['ocfilter_page_id'], $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
				}
				
				if (isset($this->request->get['filter_title'])) {
					$url .= '&filter_title=' . $this->request->get['filter_title'];
				}

				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
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
				
				$this->response->redirect($this->url->link('catalog/ocfilter_page/edit', 'ocfilter_page_id='.(int)$this->request->get['ocfilter_page_id'].'&token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getForm();
		}
		
		public function delete() {
			$this->load->language('catalog/ocfilter_page');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('catalog/ocfilter_page');
			
			if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $ocfilter_page_id) {
					$this->model_catalog_ocfilter_page->deletePage($ocfilter_page_id);
				}
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$url = '';
				
				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
				}
				
				if (isset($this->request->get['filter_title'])) {
					$url .= '&filter_title=' . $this->request->get['filter_title'];
				}

				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
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
				
				$this->response->redirect($this->url->link('catalog/ocfilter_page', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			
			$this->getList();
		}
		
		private function getList() {
			$data = $this->load->language('catalog/ocfilter_page');
			
			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
				} else {
				$filter_category_id = null;
			}
			
			if (isset($this->request->get['filter_title'])) {
				$filter_title = $this->request->get['filter_title'];
				} else {
				$filter_title = '';
			}

			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
				} else {
				$filter_status = null;
			}
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = null;
			}
			
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				} else {
				$order = null;
			}						

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = $this->config->get('config_limit_admin');
			}			
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			$data['breadcrumbs']   = array();
			
			$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home')
			);
			
			$data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/ocfilter_page', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title')
			);
			
			$url = '';
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . $this->request->get['filter_title'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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


			$data['add']  = $this->url->link('catalog/ocfilter_page/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$data['delete']  = $this->url->link('catalog/ocfilter_page/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$this->load->model('catalog/ocfilter');
			$data['categories']  = $this->model_catalog_ocfilter->getCategories(0);
			
			$filter_data = array(
			'filter_category_id' => $filter_category_id,
			'filter_title' 		 => $filter_title,
			'sort' 	=> $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $limit,
			);
			
			$data['pages'] = array();
			
			$pages_total = $this->model_catalog_ocfilter_page->getTotalPages($filter_data);			
			$results = $this->model_catalog_ocfilter_page->getPages($filter_data);
			
			foreach ($results as $result) {
				$data['pages'][] = array(
				'ocfilter_page_id' 		=> $result['ocfilter_page_id'],
				'title' 				=> $result['title'],
				'name' 					=> $result['name'],
				'megamenu_name'			=> $result['megamenu_name'],
				'category' 				=> $result['category'],
				'params' 				=> $result['params'],
				'keyword' 				=> $result['keyword'],
				'selected' 				=> isset($this->request->post['selected']) && in_array($result['option_id'], $this->request->post['selected']),
				'status' 				=> $result['status'],
				'megamenu' 				=> $result['megamenu'],
				'sort_order' 			=> $result['sort_order'],
				'edit' 					=> $this->url->link('catalog/ocfilter_page/edit', 'token=' . $this->session->data['token'] . '&ocfilter_page_id=' . $result['ocfilter_page_id'] . $url, 'SSL')
				);
			}
			
			$data['token'] = $this->session->data['token'];
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
				} else {
				$data['error_warning'] = '';
			}
			
			if (isset($this->warning['warning'])) {
				$data['warning_warning'] = $this->warning['warning'];
				} else {
				$data['warning_warning'] = '';
			}
			
			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
				} else {
				$data['success'] = '';
			}
			
			$url = '';
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . $this->request->get['filter_title'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$pagination         = new Pagination();
			$pagination->total  = $pages_total;
			$pagination->page   = $page;
			$pagination->limit  = $this->config->get('config_limit_admin');
			$pagination->text   = $this->language->get('text_pagination');
			$pagination->url    = $this->url->link('catalog/ocfilter_page', 'token=' . $this->session->data['token'] . '&page={page}' . $url, 'SSL');
			
			$data['pagination'] = $pagination->render();
			$data['results']    = sprintf($this->language->get('text_pagination'), ($pages_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($pages_total - $limit)) ? $pages_total : ((($page - 1) * $limit) + $limit), $pages_total, ceil($pages_total / $this->config->get('config_limit_admin')));
			
			$data['filter_category_id'] = $filter_category_id;
			$data['filter_title'] = $filter_title;
			$data['filter_status'] = $filter_status;
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;
			$data['config_limit'] = $this->config->get('config_limit_admin');

			$data['header']      = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer']      = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('catalog/ocfilter_page_list.tpl', $data));
		}
		
		private function getForm() {
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
			
			$data = $this->load->language('catalog/ocfilter_page');
			$data['ckeditor'] = $this->config->get('config_editor_default');
			
			
			$data['text_form'] = !isset($this->request->get['ocfilter_page_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
				} else {
				$data['error_warning'] = '';
			}						
			
			if (isset($this->error['title'])) {
				$data['error_title'] = $this->error['title'];
				} else {
				$data['error_title'] = array();
			}
			
			if (isset($this->error['meta_title'])) {
				$data['error_meta_title'] = $this->error['meta_title'];
				} else {
				$data['error_meta_title'] = array();
			}
			
			if (isset($this->error['keyword'])) {
				$data['error_keyword'] = $this->error['keyword'];
				} else {
				$data['error_keyword'] = '';
			}
			
			if (isset($this->error['params'])) {
				$data['error_params'] = $this->error['params'];
				} else {
				$data['error_params'] = '';
			}
			
			if (isset($this->error['category'])) {
				$data['error_category'] = $this->error['category'];
				} else {
				$data['error_category'] = '';
			}
			
			$data['breadcrumbs']   = array();
			
			$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home')
			);
			
			$data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/ocfilter_page', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title')
			);
			
			$url = '';
			
			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
			}
			
			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . $this->request->get['filter_title'];
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
			
			if (!isset($this->request->get['ocfilter_page_id'])) {
				$data['action'] = $this->url->link('catalog/ocfilter_page/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
				} else {
				$data['action'] = $this->url->link('catalog/ocfilter_page/edit', 'token=' . $this->session->data['token'] . $url . '&ocfilter_page_id=' . $this->request->get['ocfilter_page_id'], 'SSL');
			}
			
			$data['cancel'] = $this->url->link('catalog/ocfilter_page', 'token=' . $this->session->data['token'] . $url, 'SSL');
			
			if (isset($this->request->get['ocfilter_page_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$page_info = $this->model_catalog_ocfilter_page->getPage($this->request->get['ocfilter_page_id']);
			}
			
			if (isset($this->request->post['status'])) {
				$data['status'] = $this->request->post['status'];
				} elseif (isset($page_info)) {
				$data['status'] = $page_info['status'];
				} else {
				$data['status'] = 1;
			}

			if (isset($this->request->post['megamenu'])) {
				$data['megamenu'] = $this->request->post['megamenu'];
				} elseif (isset($page_info)) {
				$data['megamenu'] = $page_info['megamenu'];
				} else {
				$data['megamenu'] = 0;
			}

			if (isset($this->request->post['sort_order'])) {
				$data['sort_order'] = $this->request->post['sort_order'];
				} elseif (isset($page_info)) {
				$data['sort_order'] = $page_info['sort_order'];
				} else {
				$data['sort_order'] = 0;
			}			
			
			if (isset($this->request->post['params'])) {
				$data['params'] = $this->request->post['params'];
				} elseif (isset($page_info)) {
				$data['params'] = $page_info['params'];
				} else {
				$data['params'] = '';
			}
			
			$this->load->model('localisation/language');
			
			$data['languages'] = $this->model_localisation_language->getLanguages();
			
			if (isset($this->request->post['page_description'])) {
				$data['page_description'] = $this->request->post['page_description'];
				} elseif (isset($page_info)) {
				$data['page_description'] = $this->model_catalog_ocfilter_page->getPageDescriptions($this->request->get['ocfilter_page_id']);
				} else {
				$data['page_description'] = array();
			}
			
			if (isset($this->request->post['keyword'])) {
				$data['keyword'] = $this->request->post['keyword'];
				} elseif (isset($page_info)) {
				$data['keyword'] = $page_info['keyword'];
				} else {
				$data['keyword'] = '';
			}
			
			if (utf8_strlen($data['keyword']) > 0) {
				$this->load->model('catalog/url_alias');
				
				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($data['keyword']);
				
				if ($url_alias_info) {
					$this->warning['keyword'] = 'ЧПУ присутствует в системной базе ('. $url_alias_info['query'] .'), ничего страшного';						
				}
								
				//Проверяем является ли урл урлом другой страницы
				if (isset($this->request->get['ocfilter_page_id'])){
					$url_alias_info = $this->model_catalog_ocfilter_page->getPageUrlAlias($data['keyword'], $this->request->get['ocfilter_page_id']);					
					if ($url_alias_info){
						if (isset($this->warning['keyword'])){
							$this->warning['keyword'] .= '<br />ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
							} else {
							$this->warning['keyword'] = 'ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
						}
					}
				} else {
					$url_alias_info = $this->model_catalog_ocfilter_page->getPageUrlAlias($data['keyword']);					
					if ($url_alias_info){
						if (isset($this->warning['keyword'])){
							$this->warning['keyword'] .= '<br />ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
							} else {
							$this->warning['keyword'] = 'ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
						}
					}
				}
				$this->session->data['warning_keyword'] = $this->warning['keyword'];				
				
				//Проверяем является ли урл урлом чего-то другого)
				$url_alias_info = $this->model_catalog_ocfilter_page->getUrlAlias($data['keyword'], $this->request->get['ocfilter_page_id']);
				if ($url_alias_info && (stripos($url_alias_info['query'], 'ocfilter_page_id=') === false)) {
					$this->error['keyword'] = 'ЧПУ уже назначено какой-то структурной единице фильтра c значением ' . $url_alias_info['query'];
				}
			}
			
			if (isset($this->session->data['warning_keyword'])) {
				$data['warning_keyword'] = $this->session->data['warning_keyword'];
				} else {
				$data['warning_keyword'] = '';
			}
			unset($this->session->data['warning_keyword']);
			
			if (isset($this->request->post['category_id'])) {
				$data['category_id'] = $this->request->post['category_id'];
				} elseif (isset($page_info)) {
				$data['category_id'] = $page_info['category_id'];
				} else {
				$data['category_id'] = 0;
			}
			
			if (isset($this->request->post['path'])) {
				$data['path'] = $this->request->post['path'];
				} elseif (isset($page_info)) {
				$data['path'] = $page_info['path'];
				} else {
				$data['path'] = '';
			}
			
			$data['token'] = $this->session->data['token'];
			
			$data['header']      = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer']      = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('catalog/ocfilter_page_form.tpl', $data));
		}
		
		protected function validateDelete() {
			if (!$this->user->hasPermission('modify', 'catalog/ocfilter_page')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			return !$this->error;
		}
		
		protected function validateForm() {
			if (!$this->user->hasPermission('modify', 'catalog/ocfilter_page')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
			
			foreach ($this->request->post['page_description'] as $language_id => $value) {
				if ((utf8_strlen($value['title']) < 2) || (utf8_strlen($value['title']) > 128)) {
					$this->error['title'][$language_id] = $this->language->get('error_title');
				}
				
				if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
					$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
				}
			}
			
			if (utf8_strlen($this->request->post['keyword']) > 0) {
				$this->load->model('catalog/url_alias');
				
				$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);
				
				if ($url_alias_info) {
					$this->warning['keyword'] = 'ЧПУ присутствует в системной базе ('. $url_alias_info['query'] .'), ничего страшного';						
				}
								
				//Проверяем является ли урл урлом другой страницы
				if (isset($this->request->get['ocfilter_page_id'])){
					$url_alias_info = $this->model_catalog_ocfilter_page->getPageUrlAlias($this->request->post['keyword'], $this->request->get['ocfilter_page_id']);					
					if ($url_alias_info){
						if (isset($this->warning['keyword'])){
							$this->warning['keyword'] .= '<br />ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
							} else {
							$this->warning['keyword'] = 'ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
						}
					}
				} else {
					$url_alias_info = $this->model_catalog_ocfilter_page->getPageUrlAlias($this->request->post['keyword']);					
					if ($url_alias_info){
						if (isset($this->warning['keyword'])){
							$this->warning['keyword'] .= '<br />ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
							} else {
							$this->warning['keyword'] = 'ЧПУ уже назначено другой странице фильтра c значением ' . $url_alias_info['query'] . ', ничего страшного';
						}
					}
				}
				$this->session->data['warning_keyword'] = $this->warning['keyword'];				
				
				//Проверяем является ли урл урлом чего-то другого)
				$url_alias_info = $this->model_catalog_ocfilter_page->getUrlAlias($this->request->post['keyword'], $this->request->get['ocfilter_page_id']);
				if ($url_alias_info && (stripos($url_alias_info['query'], 'ocfilter_page_id=') === false)) {
					$this->error['keyword'] = 'ЧПУ уже назначено какой-то структурной единице фильтра c значением ' . $url_alias_info['query'];
				}
			}
			
			if (!$this->request->post['category_id']) {
				$this->error['category'] = $this->language->get('error_category');
			}
			
			if (!$this->request->post['params']) {
				$this->error['params'] = $this->language->get('error_params');
			}
			
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}
			
			return !$this->error;
		}
	}
?>