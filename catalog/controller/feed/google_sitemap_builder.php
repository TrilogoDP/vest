<?php
	
	
	class ControllerFeedGoogleSitemapBuilder extends Controller {
		private $limit = 1500;
		private $langprefix = '';
		
		public function index() {
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
		}
		
		private function echoLine($line){
			$line = str_replace('<![CDATA[', '', $line);
			$line = str_replace(']]>', '', $line);
			echo $line . PHP_EOL;			
		}
		
		private function echoSimple($line){
			echo $line;			
		}
		
		private function memoryUnits($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		}
		
		private function link($route, $args = ''){
			$url = $this->url->link($route, $args);	
			
			$result = $url;
			if ($this->langprefix){	
				$scheme = parse_url($url, PHP_URL_SCHEME);
				$path = parse_url($url, PHP_URL_PATH);
				$query = parse_url($url, PHP_URL_QUERY);
				
				$result = $scheme . '://' . rtrim($this->langprefix, '/') . '/' . ltrim($path, '/');
				
				if ($query){
					$result .= '?' . $query;
				}
			}
			
			return htmlspecialchars_decode(trim($result));
		}
		
		public function cron(){
			ini_set('memory_limit', '2G');
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			if ($this->config->get('google_sitemap_status')) {							
				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				$this->load->model('catalog/category');
				$this->load->model('catalog/manufacturer');
				$this->load->model('catalog/information');
				$this->load->model('catalog/ocfilter');
				$this->load->model('octemplates/blog_category');
				$this->load->model('octemplates/blog_article');
				
				$this->load->model('localisation/language');
				$languages = $this->model_localisation_language->getLanguages();
				
				foreach ($languages as $language){									
					$this->config->set('config_language_id', $language['language_id']);
					$this->config->set('config_language', $language['code']);	
					
					$langmark = $this->config->get('asc_langmark');
					
					if ($langmark && isset($langmark['prefix']) && isset($langmark['prefix'][$language['code']])){
						$this->langprefix = trim($langmark['prefix'][$language['code']]);
					}
					
					$sitemap_index = array();
					
					$file = DIR_SITEMAPS . 'sitemap_' . $language['code'] . '.xml';
					$file_masked = DIR_SITEMAPS . 'sitemap_products_' . $language['code'] . '_[iterator]' . '.xml';
					$file_content = DIR_SITEMAPS . 'sitemap_content_' . $language['code'] . '.xml';
					$file_filter = DIR_SITEMAPS . 'sitemap_filters_' . $language['code'] . '.xml';
					
					
					//Категории c фильтрами
					$output  = '<?xml version="1.0" encoding="UTF-8"?>';
					$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
					
					$this->echoLine('[i] Язык ' . $language['code'] . ', файл с категориями ' . $file_filter);				
					$this->echoLine('[с] Начали фильтр категорий');
					$output .= $this->getCategories(0);
					
					$this->echoLine('');
					
					$output .= '</urlset>';
					
					$handle = fopen($file_filter, 'w+');
					flock($handle, LOCK_EX);
					fwrite($handle, $output);
					flock($handle, LOCK_UN);
					fclose($handle);
					
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					
					//Производители и статьи
					$output  = '<?xml version="1.0" encoding="UTF-8"?>';
					$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
					
					$this->echoLine('[с] Начали производителей');
					//БРЕНДЫ
					$this->echoLine('');
					$this->echoLine('[m] Начали бренды');
					$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
					
					foreach ($manufacturers as $manufacturer) {
						$output .= '<url>';
						$output .= '<loc><![CDATA[' . $this->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . ']]></loc>';
						$output .= '<changefreq>weekly</changefreq>';
						$output .= '<priority>0.7</priority>';
						$output .= '</url>';
						
						
						$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
						$filter_data = array(
						'filter_manufacturer_id' => $manufacturer['manufacturer_id'],				
						'sort'               => 'rating',
						'order'              => 'DESC',				
						);
						
						$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
						
						$this->echoLine('[c] Бренд '. $manufacturer['name'] .' всего товаров ' . $product_total . ', страниц ' . ceil($product_total / $limit));
						
						for ($i = 2; $i <= ceil($product_total / $limit); $i++){
							$output .= '<url>';
							$output .= '<loc><![CDATA[' .  $this->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'] . '&page=' . $i) . ']]></loc>';
							$output .= '<changefreq>weekly</changefreq>';
							$output .= '<priority>0.7</priority>';
							$output .= '</url>';
						}
						
					}
					
					unset($manufacturers); 
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					
					//ШТАТНАЯ ЛОГИКА СТАТЕЙ
					$this->echoLine('');
					$this->echoLine('[i] Начали штатные статьи');
					$informations = $this->model_catalog_information->getInformations();
					
					foreach ($informations as $information) {
						$output .= '<url>';
						$output .= '<loc><![CDATA[' .  $this->link('information/information', 'information_id=' . $information['information_id']) . ']]></loc>';
						$output .= '<changefreq>weekly</changefreq>';
						$output .= '<priority>0.5</priority>';
						$output .= '</url>';
					}
					
					unset($informations); 
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					
					//СТРАНИЦЫ OCFILTER
					if ($this->config->get('ocfilter_sitemap_status')) {
						$this->echoLine('');
						$this->echoLine('[ocf] Начали страницы OCFILTER');
						$ocfilter_pages = $this->model_catalog_ocfilter->getPages();
						
						foreach ($ocfilter_pages as $page) {
							
							$params = $this->load->controller('extension/module/ocfilter/decode', array('return' => true, 'string' => $page['keyword'], 'category_id' => $page['category_id']));
							$this->echoLine('[cpo] Страница ' . $page['keyword']);
							
							if (!$params) continue;
							
							$continue = true;
							foreach ($params as $key => $param){
								if (!$param){
									$this->echoLine('[cpo] Не получилось распарсить страницу');
									$continue = false;
								}
							}
							
							if (!$continue) continue;
							
							$filter_data = array(
							'filter_category_id' => $page['category_id'],
							'filter_ocfilter'    => encodeParamsToString($params, $this->config)
							);
							if ($total_products_by_filter = $this->model_catalog_product->getTotalProducts($filter_data)){
								$this->echoLine('[cpo] Страница имеет ' . $total_products_by_filter . ' товаров');
								} else {
								$add = false;
								$this->echoLine('[cpo] На странице нет товаров, пропускаем');
								continue;
							}
							
							$link = rtrim($this->link('product/category', 'path=' . $page['category_id']), '/');
							
							if ($page['keyword']) {
								$link .= '/' . $page['keyword'];
								} else {
								$link .= '/' . $page['params'];
							}
							
							if ($this->config->get('config_seo_url_type') == 'seo_pro') {
								$link .= '/';
							}
							
							$output .= '<url>';
							$output .= '<loc><![CDATA[' . $link . ']]></loc>';
							$output .= '<changefreq>weekly</changefreq>';
							$output .= '<priority>0.7</priority>';
							$output .= '</url>';
							
						}
					}
					
					unset($ocfilter_pages); 
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					
					
					//БЛОГ OCTEMPLATES
					$this->echoLine('');
					$this->echoLine('[ocb] Начали блог OCTEMPLATES');
					$oct_blog_categories = array();
					
					foreach ($this->model_octemplates_blog_category->getCategories(0) as $oct_blog_category) {
						$oct_blog_children_data = array();
						
						foreach($this->model_octemplates_blog_category->getCategories($oct_blog_category['oct_blog_category_id']) as $oct_blog_child) {
							
							$oct_blog_articles_child = array();
							
							$oct_blog_articles_child_results = $this->model_octemplates_blog_article->getArticles(array('filter_category_id' => $oct_blog_child['oct_blog_category_id']));
							
							foreach ($oct_blog_articles_child_results as $oct_blog_articles_child_result) {
								$oct_blog_articles_child[] = array(
								'href' => $this->link('octemplates/blog_article', 'cpath=' . $oct_blog_category['oct_blog_category_id'] . '_' . $oct_blog_child['oct_blog_category_id'] . '&oct_blog_article_id=' . $oct_blog_articles_child_result['oct_blog_article_id'])
								);
							}
							
							$oct_blog_children_data[] = array(
							'articles' => $oct_blog_articles_child,
							'href' => $this->link('octemplates/blog_category', 'cpath=' . $oct_blog_category['oct_blog_category_id'] . '_' . $oct_blog_child['oct_blog_category_id'])
							);
						}
						
						$oct_blog_articles = array();
						
						foreach ($this->model_octemplates_blog_article->getArticles(array('filter_category_id' => $oct_blog_category['oct_blog_category_id'])) as $oct_blog_articles_result) {
							$oct_blog_articles[] = array(
							'href' => $this->link('octemplates/blog_article', 'cpath=' . $oct_blog_category['oct_blog_category_id'] . '&oct_blog_article_id=' . $oct_blog_articles_result['oct_blog_article_id'])
							);
						}
						
						$oct_blog_categories[] = array(
						'children'    => $oct_blog_children_data,
						'articles'    => $oct_blog_articles,
						'href'        => $this->link('octemplates/blog_category', 'cpath=' . $oct_blog_category['oct_blog_category_id'])
						);
					}
					
					if ($oct_blog_categories) {		
						foreach ($oct_blog_categories as $oct_blog_category) {
							$output .= '<url>';
							$output .= '	<loc><![CDATA[' . $oct_blog_category['href'] . ']]></loc>';
							$output .= '	<changefreq>weekly</changefreq>';
							$output .= '	<priority>0.7</priority>';
							$output .= '</url>';
							
							if ($oct_blog_category['articles']) {
								foreach ($oct_blog_category['articles'] as $oct_blog_child_article) {
									$output .= '<url>';
									$output .= '<loc><![CDATA[' . $oct_blog_child_article['href'] . ']]></loc>';
									$output .= '<changefreq>weekly</changefreq>';
									$output .= '<priority>1.0</priority>';
									$output .= '</url>';
								}
							}
							
							if ($oct_blog_category['children']) {
								foreach ($oct_blog_category['children'] as $oct_blog_child) {
									$output .= '<url>';
									$output .= '	<loc><![CDATA[' . $oct_blog_child['href'] . ']]></loc>';
									$output .= '	<changefreq>weekly</changefreq>';
									$output .= '	<priority>0.7</priority>';
									$output .= '</url>';
									
									if ($oct_blog_child['articles']) {
										foreach ($oct_blog_child['articles'] as $oct_blog_child_inner) {
											$output .= '<url>';
											$output .= '<loc><![CDATA[' . $oct_blog_child_inner['href'] . ']]></loc>';
											$output .= '<changefreq>weekly</changefreq>';
											$output .= '<priority>1.0</priority>';
											$output .= '</url>';
										}
									}
								}
							}
						}
					}
					
					unset($oct_blog_categories); 
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					
					$this->echoLine('');
					
					$output .= '</urlset>';
					
					$handle = fopen($file_content, 'w+');
					flock($handle, LOCK_EX);
					fwrite($handle, $output);
					flock($handle, LOCK_UN);
					fclose($handle);
					
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));										
					
					//Товары
					//START PRODUCTS
					$total_products = $this->model_catalog_product->getTotalProducts();
					$index = (int)($total_products / $this->limit);		
					
					$this->echoLine('[p] Начали товары, всего ' . $total_products);
					
					$outputSitemapIndex  = '<?xml version="1.0" encoding="UTF-8"?>';
					$outputSitemapIndex .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
					$sitemapsIndex = array();
					$this->echoLine('[i] Язык ' . $language['code'] . ', общий файл ' . $file);
					
					for ($i=0; $i<=$index; $i++){
						$fileIterator = str_replace('[iterator]', $i, $file_masked);
						$this->echoLine('[i] Язык ' . $language['code'] . ', файл ' . $fileIterator);	
						
						$start = $i * $this->limit;	
						
						$this->echoLine($start . '...');
						
						$output = '';
						$outputIterator  = '<?xml version="1.0" encoding="UTF-8"?>';
						$outputIterator .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
						
						$data = array(
						'start' => $start,
						'limit' => $this->limit
						);
						$products = $this->model_catalog_product->getProducts($data);
						
						unset($product);
						foreach ($products as $product) {						
							$output .= '<url>';
							$output .= '<loc><![CDATA[' . $this->link('product/product', 'product_id=' . $product['product_id']) . ']]></loc>';							
							$output .= '<changefreq>weekly</changefreq>';													
							
							$date_modified = $product['date_modified'] != '0000-00-00 00:00:00' ? $product['date_modified'] : $product['date_added'];
							if ($product['date_modified'] == '0000-00-00 00:00:00' || date('Y-m-d\TH:i:sP', strtotime($product['date_modified'])) == '-0001-11-30T00:00:00+00:00' || date('Y-m-d\TH:i:sP', strtotime($product['date_modified'])) == '0001-01-01T00:00:00+00:00'){
								$date_modified = date('Y-m-d\TH:i:sP');
							}
							
							$output .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($date_modified)) . '</lastmod>';
							$output .= '<priority>1.0</priority>';
							if ($product['image']) {
								$output .= '<image:image>';
								$output .= '<image:loc><![CDATA[' . $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')) . ']]></image:loc>';
								$output .= '<image:caption><![CDATA[' . $product['name'] . ']]></image:caption>';
								$output .= '<image:title><![CDATA[' . $product['name'] . ']]></image:title>';
								$output .= '</image:image>';															
								$output .= '<image:image>';
								$output .= '<image:loc><![CDATA[' . $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')) . ']]></image:loc>';
								$output .= '<image:caption><![CDATA[' . $product['name'] . ']]></image:caption>';
								$output .= '<image:title><![CDATA[' . $product['name'] . ']]></image:title>';
								$output .= '</image:image>';							
							}
							
							$images = $this->model_catalog_product->getProductImagesForFeeds($product['product_id']);
							
							if ($images){
								foreach ($images as $image){
									$output .= '<image:image>';
									$output .= '<image:loc><![CDATA[' . $this->model_tool_image->resize($image['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')) . ']]></image:loc>';
									$output .= '<image:caption><![CDATA[' . $product['name'] . ']]></image:caption>';
									$output .= '<image:title><![CDATA[' . $product['name'] . ']]></image:title>';
									$output .= '</image:image>';							
									$output .= '<image:image>';
									$output .= '<image:loc><![CDATA[' . $this->model_tool_image->resize($image['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')) . ']]></image:loc>';
									$output .= '<image:caption><![CDATA[' . $product['name'] . ']]></image:caption>';
									$output .= '<image:title><![CDATA[' . $product['name'] . ']]></image:title>';
									$output .= '</image:image>';
								}							
							}
							
							$output .= '</url>';
						}	
						
						
						$this->echoLine('');
						unset($products); 
						gc_collect_cycles();
						$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
						$this->echoLine('');
						
						$outputIterator .= $output;
						$outputIterator .= '</urlset>';
						
						$sitemapsIndex[] = $fileIterator;
						
						$handle = fopen($fileIterator, 'w+');
						flock($handle, LOCK_EX);
						fwrite($handle, $outputIterator);
						flock($handle, LOCK_UN);
						fclose($handle);		
					}
					
					$this->echoLine('');
					unset($products); 
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					$this->echoLine('');
					
					
					foreach ($sitemapsIndex as $sitemap){
						
						$outputSitemapIndex .= '<sitemap>';
						$outputSitemapIndex .= '<loc>' . str_replace(DIR_SITEMAPS, $this->config->get('config_ssl') . 'sitemaps/', $sitemap) . '</loc>';
						$outputSitemapIndex .= '<lastmod>' . date('Y-m-d\TH:i:sP') . '</lastmod>';		
						$outputSitemapIndex .= '</sitemap>';
					}
					
					$outputSitemapIndex .= '</sitemapindex>';
					
					$handle = fopen($file, 'w+');
					flock($handle, LOCK_EX);
					fwrite($handle, $outputSitemapIndex);
					flock($handle, LOCK_UN);
					fclose($handle);	
					
					
				}
			}
		}
		
		protected function getCategories($parent_id, $current_path = '') {
			$output = '';
			
			$results = $this->model_catalog_category->getCategories($parent_id);
			
			foreach ($results as $result) {
				$this->echoLine('[c] Категория ' . $result['name']);
				
				if (!$current_path) {
					$new_path = $result['category_id'];
					} else {
					$new_path = $current_path . '_' . $result['category_id'];
				}
				
				$output .= '<url>';
				$output .= '<loc><![CDATA[' .  $this->link('product/category', 'path=' . $new_path) . ']]></loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';
				
				$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
				$filter_data = array(
				'filter_category_id' => $result['category_id'],				
				'sort'               => 'rating',
				'order'              => 'DESC',				
				);
				
				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				
				$this->echoLine('[c] Категория, всего товаров ' . $product_total . ', страниц ' . ceil($product_total / $limit));
				
				for ($i = 2; $i <= ceil($product_total / $limit); $i++){
					$output .= '<url>';
					$output .= '<loc><![CDATA[' .  $this->link('product/category', 'path=' . $new_path . '&page=' . $i) . ']]></loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>0.7</priority>';
					$output .= '</url>';
				}
				
				$options = array();
				
				//Производители, совместимость OCFILTER
				if ($this->config->get('ocfilter_manufacturer')) {
					$manufacturers = $this->model_catalog_ocfilter->getManufacturersByCategoryId($result['category_id'], true);
					
					if ($manufacturers) {
						foreach ($manufacturers as $manufacturer){
							$this->echoLine('[mf] Бренд ' . $manufacturer['name'] . ', URL: ' . $this->link('product/category', 'path=' . $new_path . '&manufacturer_id=' . $manufacturer['value_id']) . '/');
							
							$output .= '<url>';
							$output .= '<loc><![CDATA[' .  $this->link('product/category', 'path=' . $new_path . '&manufacturer_id=' . $manufacturer['value_id']) . '/' . ']]></loc>';
							$output .= '<changefreq>weekly</changefreq>';
							$output .= '<priority>0.7</priority>';
							$output .= '</url>';
						}
					}
				}
				
				$options = $this->model_catalog_ocfilter->getOCFilterOptionsByCategoryId($result['category_id']);
				$options_added = $this->model_catalog_ocfilter->webfunGetOCFilterOptionsBySort();
				
				if ($options && $options_added) {
					$options = array_merge($options_added, $options);
				}
				
				$alertMessage = '🔥🔥🔥🔥 Пустые значения опций OCFILTER 🔥🔥🔥🔥';
				$alertMessage .= '<b> Категория '. $result['name'] . '</b>' . PHP_EOL;
				$emptyCounter = 1;
				
				if ($options){
					foreach ($options as $option){																				
						$this->echoLine('[cf] Опция ' . $option['name']);
						foreach ($option['values'] as $option_value){
							
							$filter_data = array(
							'filter_category_id' => $result['category_id'],
							'filter_ocfilter'    => $option_value['option_id'].':'.$option_value['value_id']
							);
							
							if ($total_products_by_filter = $this->model_catalog_product->getTotalProducts($filter_data)){
								
								$this->echoLine('[cfo] Значение ' . $option_value['name'] . ', товаров ' . $total_products_by_filter . ', URL: ' . $this->link('product/category', 'path=' . $new_path) . $option['keyword'] . '/' . $option_value['keyword']);
								
								$output .= '<url>';
								$output .= '<loc><![CDATA[' .  $this->link('product/category', 'path=' . $new_path) . $option['keyword'] . '/' . $option_value['keyword'] . '/' . ']]></loc>';
								$output .= '<changefreq>weekly</changefreq>';
								$output .= '<priority>0.7</priority>';
								$output .= '</url>';
								
								} else {
								
								$alertMessage .= 'Опция: <b>'. $option['name'] . '</b>, значение <b>' . $option_value['name'] . '</b>' . PHP_EOL;
								$alertMessage .= 'URL: ' . $this->link('product/category', 'path=' . $new_path) . $option['keyword'] . '/' . $option_value['keyword'] . PHP_EOL;
								$alertMessage .= PHP_EOL;
								
								$emptyCounter++;
								
								var_dump($alertMessage);
								
								$this->echoLine('[cfo] Значение ' . $option_value['name'] . ', товаров 0');
								
							}
						}
					}
				}
				
				if ($alertMessage){
					
					$this->load->library('hobotix/TelegramSender');
					$telegramSender = new hobotix\TelegramSender;
					
					$telegramSender->setGroupID('-569669916');
					
				//	$telegramSender->SendMessage($alertMessage);
					
				}
				
				$output .= $this->getCategories($result['category_id'], $new_path);
			}
			
			return $output;
		}		
	}																																							