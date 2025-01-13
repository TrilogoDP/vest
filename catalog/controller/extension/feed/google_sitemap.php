<?php
	class ControllerExtensionFeedGoogleSitemap extends Controller {
		public function index() {
			if ($this->config->get('google_sitemap_status')) {
				$output  = '<?xml version="1.0" encoding="UTF-8"?>';
				$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
				
				$this->load->model('catalog/product');
				$this->load->model('tool/image');	
				
				$this->load->model('localisation/language');
				$languages = $this->model_localisation_language->getLanguages();
				
				//FROM GET PARAM
				$language_from_url = (isset($this->request->get['language_id'])?array($this->request->get['language_id']):array('nolanguage'));					
				
				$code_from_url = false;
				foreach($language_from_url as $lang){			
					unset($value);
					$break = false;
					foreach ($languages as $key => $value) {
						if ($value['code'] == $lang){						
							$code_from_url = $key;
							$break = true;
							break;
						}
					}
					if ($break) break;
				}
				
			
				// Set the config language_id
				if ($code_from_url && array_key_exists($code_from_url, $languages)){
					$this->config->set('config_language_id', $languages[$code_from_url]['language_id']);
					$this->config->set('config_language', $code_from_url);	
					$language_id = $this->config->get('config_language_id');
					} else {
					$language_id = $this->config->get('config_language_id');
				}
				
				//ОЧЕНЬ ГРЯЗНЫЙ ХАК, но иначе придется разгребать говнокод сео-мультиланг-про-экстрим-эдишн 
								
				$products = $this->model_catalog_product->getProducts();
				
				foreach ($products as $product) {
					if ($product['image']) {
						$output .= '<url>';
						$output .= '<loc>' . $this->formatUrl($this->url->link('product/product', 'product_id=' . $product['product_id'])) . '</loc>';
						$output .= '<changefreq>weekly</changefreq>';
						$output .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($product['date_modified'])) . '</lastmod>';
						$output .= '<priority>1.0</priority>';
						$output .= '</url>';
					}
				}
				
				$this->load->model('catalog/category');
				
				$output .= $this->getCategories(0);
				
				$this->load->model('catalog/manufacturer');
				
				$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
				
				foreach ($manufacturers as $manufacturer) {
					$output .= '<url>';
					$output .= '<loc>' . $this->formatUrl($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])) . '</loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>0.7</priority>';
					$output .= '</url>';
				}
				
				$this->load->model('catalog/information');
				
				$informations = $this->model_catalog_information->getInformations();
				
				foreach ($informations as $information) {
					$output .= '<url>';
					$output .= '<loc>' .  $this->formatUrl($this->url->link('information/information', 'information_id=' . $information['information_id'])) . '</loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>0.5</priority>';
					$output .= '</url>';
				}
				
				$output .= '</urlset>';
				
				$this->response->addHeader('Content-Type: application/xml');
				$this->response->setOutput($output);
			}
		}
		
		protected function getCategories($parent_id, $current_path = '') {
			$output = '';
			
			$results = $this->model_catalog_category->getCategories($parent_id);
			
			foreach ($results as $result) {
				if (!$current_path) {
					$new_path = $result['category_id'];
					} else {
					$new_path = $current_path . '_' . $result['category_id'];
				}
				
				$output .= '<url>';
				$output .= '<loc>' .  $this->formatUrl($this->url->link('product/category', 'path=' . $new_path)) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';
				
				// webfun
				$query = $this->db->query("SELECT `option_id` FROM " . DB_PREFIX . "ocfilter_option_to_category WHERE `category_id` = '" . (int)$result['category_id'] . "' AND `option_id` != '0'");
				if ($query->num_rows) {
					foreach ($query->rows as $wf_option_id) {
						$query1 = $this->db->query("SELECT `keyword` FROM " . DB_PREFIX . "ocfilter_option WHERE `option_id` = '" . (int)$wf_option_id['option_id'] . "' AND `status` = '1' LIMIT 1");
						if ($query1->num_rows) {
							$fw_option_name = $query1->row['keyword'];
							
							$query2 = $this->db->query("SELECT `keyword` FROM " . DB_PREFIX . "ocfilter_option_value WHERE `option_id` = '" . (int)$wf_option_id['option_id'] . "' ORDER BY `sort_order`");
							if ($query2->num_rows) {
								foreach ($query2->rows as $wf_option_value) {
									$output .= '<url>';
									$output .= '<loc>' .  $this->formatUrl($this->url->link('product/category', 'path=' . $new_path) . $fw_option_name . '/' . $wf_option_value['keyword']) . '</loc>';
									$output .= '<changefreq>weekly</changefreq>';
									$output .= '<priority>0.7</priority>';
									$output .= '</url>';
								}
							}
						}
					}
				}
				// webfun end
				
				$output .= $this->getCategories($result['category_id'], $new_path);
			}
			
			return $output;
		}
		//***ms begin
		function getFilterUrl($filter_id, $filter_group_id, $lang_id){
			$url = "?filter=".$filter_id.','.$filter_group_id;
			if(isset($filter_id) && (int)$filter_id > 0 && (int)$filter_group_id > 0){
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_filter_product_standard WHERE `filter_id` = '" . (int)$filter_id . "' AND `filter_group_id` = '".(int)$filter_group_id."' AND language_id='".(int)$lang_id."'");
				
				if ($query->num_rows) {
					$url = 'filter/'.$query->row['filter_name_mod'].'/'.$query->row['filter_value_mod'].'/?sort=p.quantity&order=asc&limit=15&page=1';
				}
			}
			return $url;
		}
		
		function formatUrl($url){
			$url = str_replace("?sort=p.quantity&order=asc&limit=15&page=1","",$url);
			// $url = str_replace("/ru/","/ua/",$url);
			return '<![CDATA['.htmlspecialchars_decode($url).']]>';
		}
		
		function find_seo_data($filename, $url_f) {
			$url_f = str_replace("http://new.vest.in.ua", "https://vest.in.ua", $url_f);
			$f = fopen($filename, "r");
			$result = false;
			while ($row = fgetcsv($f)) {
				//echo $row[0].' - '.$url_f.'<br>';
				if (strpos($row[0], $url_f) !== false) {
					$result = $row;
					break;
				}
			}
			fclose($f);
			return $result;
		}	
		function format_text($text){
			$text = str_replace("'","\'",$text);
			return $text;
		}
		//***ms end
	}
