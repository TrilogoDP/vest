<?
	class ControllerFeedGoogleDynamicFeedBuider extends Controller {
		private $limit = 1000;
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
		
		private function prepareMark($mark){
			
			$mark =  mb_strtoupper($mark);
			$mark = str_replace(array(';', ',', '-', ' '), '_', $mark);
			$mark = str_replace(array(')', '(', '"', "'"), '', $mark);
			
			return $mark;
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
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = 0 WHERE quantity < 0 ");
			$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = 0 WHERE quantity < 0 ");
			
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
					
					$file = DIR_FEEDS . 'google_dynamic_feed_' . $language['code'] . '.csv';					
					
					$output  = 'Page URL,Custom label' . PHP_EOL;
					
					$this->echoLine('[i] Язык ' . $language['code'] . ', файл с категориями ' . $file);				
					$this->echoLine('[с] Начали категории');
					$output .= $this->getCategories(0);
					
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					
					/*//БРЕНДЫ
					$this->echoLine('');
					$this->echoLine('[m] Начали бренды');
					$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
					
					foreach ($manufacturers as $manufacturer) {
						$output .= $this->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . ',' . 'MANUFACTURER_PAGE' . ';' . $this->prepareMark($manufacturer['name']) . PHP_EOL;
					}
					
					unset($manufacturers); 
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
					*/
					
					//Товары
					//START PRODUCTS
					$this->echoLine('');
					$data = array(				
						'filter_quantity' => true
					);
					$total_products = $this->model_catalog_product->getTotalProducts($data);
					$index = (int)($total_products / $this->limit);		
					
					$this->echoLine('[p] Начали товары, всего ' . $total_products);
					
					for ($i=0; $i<=$index; $i++){										
						$start = $i * $this->limit;	
						
						$this->echoSimple($start . '...');
						
						$data = array(
						'start' => $start,
						'limit' => $this->limit,
						'filter_quantity' => true
						);
						$products = $this->model_catalog_product->getProducts($data);
						
						foreach ($products as $product) {
							$output .= $this->link('product/product', 'product_id=' . $product['product_id']) . ',' . 'SINGLE_PRODUCT' . PHP_EOL;
						}
						
						unset($product);
					}
					
					$this->echoLine('');
					unset($products); 
					gc_collect_cycles();
					$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));									
					
					$handle = fopen($file, 'w+');
					flock($handle, LOCK_EX);
					fwrite($handle, $output);
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
				
				$output .= $this->link('product/category', 'path=' . $new_path) . ',' . 'CATEGORY_PAGE;' . $this->prepareMark($result['name']) . PHP_EOL;				
				
				$options = array();							
				
				$output .= $this->getCategories($result['category_id'], $new_path);
			}
			
			return $output;
			
		}	
		
		
		
		
		
		
		
		
		
		
		
	}					