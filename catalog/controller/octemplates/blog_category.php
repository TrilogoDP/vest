<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	
	/**************************************************************/
	
	class ControllerOctemplatesBlogCategory extends Controller {
		public function index() {
			$this->load->language('octemplates/blog_category');
			$this->load->language('octemplates/oct_techstore');
			
			$data['oct_home_text'] = $this->language->get('oct_home_text');
			
			$oct_blog_setting_data = $this->config->get('oct_blog_setting_data');
			
			$this->load->model('octemplates/blog_category');
			
			$this->load->model('octemplates/blog_article');
			
			$this->load->model('tool/image');
			
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				} else {
				$sort = 'a.date_added_desc';
			}
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				} else {
				$page = 1;
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = (int)$this->request->get['limit'];
				} else {
				$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
			}
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
			
			if (isset($this->request->get['cpath'])) {
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$path = '';
				
				$parts = explode('_', (string)$this->request->get['cpath']);
				
				$oct_blog_category_id = (int)array_pop($parts);
				
				foreach ($parts as $oct_blog_category_path_id) {
					if (!$path) {
						$path = (int)$oct_blog_category_path_id;
						} else {
						$path .= '_' . (int)$oct_blog_category_path_id;
					}
					
					$category_info = $this->model_octemplates_blog_category->getCategory($oct_blog_category_path_id);
					
					if ($category_info) {
						$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $path . $url)
						);
					}
				}
				} else {
				$oct_blog_category_id = 0;
			}
			
			$category_info = $this->model_octemplates_blog_category->getCategory($oct_blog_category_id);
			
			if ($category_info) {
				$this->document->setTitle($category_info['meta_title']);
				$this->document->setDescription($category_info['meta_description']);
				$this->document->setKeywords($category_info['meta_keyword']);
				
				//TODO Galaxy-IT JAKIM START before
				$this->document->setCanonical($this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath']));
				//TODO Galaxy-IT JAKIM END before
				$data['heading_title'] = $category_info['name'];
				$data['text_refine'] = $this->language->get('text_refine');
				$data['text_empty'] = $this->language->get('text_empty');
				$data['text_sort'] = $this->language->get('text_sort');
				$data['text_limit'] = $this->language->get('text_limit');
				$data['text_comments'] = $this->language->get('text_comments');
				$data['text_viewed'] = $this->language->get('text_viewed');
				$data['text_date_added'] = $this->language->get('text_date_added');
				$data['text_rating'] = $this->language->get('text_rating');
				
				if ($page > 1){
					$data['heading_title'] .= sprintf($this->language->get('text_page'), $page);
				}
				
				$data['button_continue'] = $this->language->get('button_continue');
				
				// Set the last category breadcrumb
				$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath'])
				);
				
				if ($category_info['image']) {
					$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $oct_blog_setting_data['c_image_width'], $oct_blog_setting_data['c_image_height']);
					} else {
					$data['thumb'] = '';
				}
				
				$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$data['products'] = array();
				
				$filter_data = array(
				'filter_category_id' => $oct_blog_category_id,
				'sort' => $sort,
				'start' => ($page - 1) * $limit,
				'limit' => $limit
				);
				
				$article_total = $this->model_octemplates_blog_article->getTotalArticles($filter_data);
				
				$data['articles'] = array();
				
				$a_results = $this->model_octemplates_blog_article->getArticles($filter_data);
				
				foreach ($a_results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $oct_blog_setting_data['a_image_width_in_category'], $oct_blog_setting_data['a_image_height_in_category']);
						} else {
						$image = $this->model_tool_image->resize('placeholder.png', $oct_blog_setting_data['a_image_width_in_category'], $oct_blog_setting_data['a_image_height_in_category']);
					}
					
					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'];
						} else {
						$rating = false;
					}
					
					$data['articles'][] = array(
					'oct_blog_article_id' => $result['oct_blog_article_id'],
					'thumb' => $image,
					'name' => $result['name'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'comments' => $result['comments'] ? $result['comments'] : 0,
					'viewed' => $result['viewed'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $oct_blog_setting_data['desc_limit']) . '..',
					'rating' => $rating,
					'href' => $this->url->link('octemplates/blog_article', 'cpath=' . $this->request->get['cpath'] . '&oct_blog_article_id=' . $result['oct_blog_article_id'])
					);
				}
				
				$url = '';
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$data['sorts'] = array();
				
				$data['sorts'][] = array(
				'text' => $this->language->get('text_sort_order_asc'),
				'value' => 'a.sort_order_asc',
				'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath'] . '&sort=a.sort_order_asc' . $url)
				);
				
				$data['sorts'][] = array(
				'text' => $this->language->get('text_sort_order_desc'),
				'value' => 'a.sort_order_desc',
				'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath'] . '&sort=a.sort_order_desc' . $url)
				);
				
				$data['sorts'][] = array(
				'text' => $this->language->get('text_name_asc'),
				'value' => 'ad.name_asc',
				'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath'] . '&sort=ad.name_asc' . $url)
				);
				
				$data['sorts'][] = array(
				'text' => $this->language->get('text_name_desc'),
				'value' => 'ad.name_desc',
				'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath'] . '&sort=ad.name_desc' . $url)
				);
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				$data['limits'] = array();
				
				$limits = array_unique(array(
				$this->config->get($this->config->get('config_theme') . '_product_limit'),
				25,
				50,
				75,
				100
				));
				
				sort($limits);
				
				foreach ($limits as $value) {
					$data['limits'][] = array(
					'text' => $value,
					'value' => $value,
					'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath'] . $url . '&limit=' . $value)
					);
				}
				
				$url = '';
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$pagination = new Pagination();
				$pagination->total = $article_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->url = $this->url->link('octemplates/blog_category', 'cpath=' . $this->request->get['cpath'] . $url . '&page={page}');
				
				$data['pagination'] = $pagination->render();
				
				$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));
				
				// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
				if ($page == 1) {
					$this->document->addLink($this->url->link('octemplates/blog_category', 'cpath=' . $category_info['oct_blog_category_id'], 'SSL'), 'canonical');
					} elseif ($page == 2) {  
					$this->document->addLink($this->url->link('octemplates/blog_category', 'cpath=' . $category_info['oct_blog_category_id'] . '&page=' . $page, 'SSL'), 'canonical');
					$this->document->addLink($this->url->link('octemplates/blog_category', 'cpath=' . $category_info['oct_blog_category_id'], 'SSL'), 'prev');
					} else {
					$this->document->addLink($this->url->link('octemplates/blog_category', 'cpath=' . $category_info['oct_blog_category_id'] . '&page=' . $page, 'SSL'), 'canonical');
					$this->document->addLink($this->url->link('octemplates/blog_category', 'cpath=' . $category_info['oct_blog_category_id'] . '&page=' . ($page - 1), 'SSL'), 'prev');
				}
				
				if ($limit && ceil($article_total / $limit) > $page) {
					$this->document->addLink($this->url->link('octemplates/blog_category', 'cpath=' . $category_info['oct_blog_category_id'] . '&page=' . ($page + 1), 'SSL'), 'next');
				}
				
				$data['sort'] = $sort;
				$data['limit'] = $limit;
				
				$data['continue'] = $this->url->link('common/home');
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				
				$this->response->setOutput($this->load->view('octemplates/blog_category', $data));
				} else {
				$url = '';
				
				if (isset($this->request->get['cpath'])) {
					$url .= '&cpath=' . $this->request->get['cpath'];
				}
				
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				
				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}
				
				$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('octemplates/blog_category', $url)
				);
				
				$this->document->setTitle($this->language->get('text_error'));
				
				$data['heading_title'] = $this->language->get('text_error');
				
				$data['text_error'] = $this->language->get('text_error');
				
				$data['button_continue'] = $this->language->get('button_continue');
				
				$data['continue'] = $this->url->link('common/home');
				
				$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
				
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				
				$this->response->setOutput($this->load->view('error/not_found', $data));
			}
		}
		
		public function sitemap() {
			$this->load->model('octemplates/blog_category');
			$this->load->model('octemplates/blog_article');
			
			$oct_blog_categories = array();
			
			foreach ($this->model_octemplates_blog_category->getCategories(0) as $oct_blog_category) {
				$oct_blog_children_data = array();
				
				foreach($this->model_octemplates_blog_category->getCategories($oct_blog_category['oct_blog_category_id']) as $oct_blog_child) {
					
					$oct_blog_articles_child = array();
					
					$oct_blog_articles_child_results = $this->model_octemplates_blog_article->getArticles(array('filter_category_id' => $oct_blog_child['oct_blog_category_id']));
					
					foreach ($oct_blog_articles_child_results as $oct_blog_articles_child_result) {
						$oct_blog_articles_child[] = array(
						'href' => $this->url->link('octemplates/blog_article', 'cpath=' . $oct_blog_category['oct_blog_category_id'] . '_' . $oct_blog_child['oct_blog_category_id'] . '&oct_blog_article_id=' . $oct_blog_articles_child_result['oct_blog_article_id'])
						);
					}
					
					$oct_blog_children_data[] = array(
					'articles' => $oct_blog_articles_child,
					'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $oct_blog_category['oct_blog_category_id'] . '_' . $oct_blog_child['oct_blog_category_id'])
					);
				}
				
				$oct_blog_articles = array();
				
				foreach ($this->model_octemplates_blog_article->getArticles(array('filter_category_id' => $oct_blog_category['oct_blog_category_id'])) as $oct_blog_articles_result) {
					$oct_blog_articles[] = array(
					'href' => $this->url->link('octemplates/blog_article', 'cpath=' . $oct_blog_category['oct_blog_category_id'] . '&oct_blog_article_id=' . $oct_blog_articles_result['oct_blog_article_id'])
					);
				}
				
				$oct_blog_categories[] = array(
				'children'    => $oct_blog_children_data,
				'articles'    => $oct_blog_articles,
				'href'        => $this->url->link('octemplates/blog_category', 'cpath=' . $oct_blog_category['oct_blog_category_id'])
				);
			}
			
			$output = '';
			
			if ($oct_blog_categories) {
				$output .= '<?xml version="1.0" encoding="UTF-8"?>';
				$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
				
				foreach ($oct_blog_categories as $oct_blog_category) {
					$output .= '<url>';
					$output .= '	<loc>' . $oct_blog_category['href'] . '</loc>';
					$output .= '	<changefreq>weekly</changefreq>';
					$output .= '	<priority>0.7</priority>';
					$output .= '</url>';
					
					if ($oct_blog_category['articles']) {
						foreach ($oct_blog_category['articles'] as $oct_blog_child_article) {
							$output .= '<url>';
							$output .= '<loc>' . $oct_blog_child_article['href'] . '</loc>';
							$output .= '<changefreq>weekly</changefreq>';
							$output .= '<priority>1.0</priority>';
							$output .= '</url>';
						}
					}
					
					if ($oct_blog_category['children']) {
						foreach ($oct_blog_category['children'] as $oct_blog_child) {
							$output .= '<url>';
							$output .= '	<loc>' . $oct_blog_child['href'] . '</loc>';
							$output .= '	<changefreq>weekly</changefreq>';
							$output .= '	<priority>0.7</priority>';
							$output .= '</url>';
							
							if ($oct_blog_child['articles']) {
								foreach ($oct_blog_child['articles'] as $oct_blog_child_inner) {
									$output .= '<url>';
									$output .= '<loc>' . $oct_blog_child_inner['href'] . '</loc>';
									$output .= '<changefreq>weekly</changefreq>';
									$output .= '<priority>1.0</priority>';
									$output .= '</url>';
								}
							}
						}
					}
				}
				
				$output .= '</urlset>';
			}
			
			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
		}
	}
