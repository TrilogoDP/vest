<?php
	
	
	class ControllerFeedGoogleMerchantExtendedBuilder extends Controller {
		private $limit = 1000;
		private $langprefix = '';
		private $default_taxonomy_id = '222';
		
		private $ocfilterBrandCompatibilityTriggerWord = array('Совместимость', 'Сумісність');
		private $ocfilterModelCompatibilityTriggerWord = array('Модель');
		
		private $deliveries = array(
		'ru-ru' => array(
		'Доставка по Украине Новой Почтой',
		'Адресная доставка курьером Новой Почты',
		'Доставка по Украине Justin',
		'Доставка по Украине Укрпочтой',
		),
		'uk-ua' => array(
		'Доставка по Україні Новою Поштою',
		'Адресна доставка кур`єром Нової Пошти ',
		'Доставка по Україні Justin',
		'Доставка по Україні Укрпоштою',
		)
		
		);
		
		public function index() {
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
		}
		
		private $color_options_array = array(14, 17, 18);
		private $pattern_options_array = array(19);
		private $is_cli;
		
		private function is_cli(){
			
		}
		
		protected function strip_html_tags( $text )
		{
			$text = preg_replace(
			array(
			// Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
			// Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
			),
			$text );
			return strip_tags( $text );
		}
		
		private function normalizeForGoogle($text){		
			$text = str_replace('&nbsp;', ' ', $text);
			$text = str_replace(' & ', ' and ', $text);
			$text = str_replace('&', ' and ', $text);
			$text = preg_replace("/&#?[a-z0-9]{2,8};/i","",$text);
			
			return trim($text);
		}
		
		public static function fixEncoding($string){
			$string=str_replace("&amp;lt;","&lt;",$string);
			$string=str_replace("&amp;gt;","&gt;",$string);
			$string=str_replace("&amp;quot;","&quot;",$string);
			$string=str_replace("&amp;amp;","&amp;",$string);
			$string=str_replace("&amp;nbsp;","&amp;&nbsp;",$string);
			$string=str_replace("&amp;&nbsp;"," ",$string);
			$string=str_replace("&nbsp;"," ",$string);
			$string=str_replace("&quot;","\"",$string);
			$string=str_replace("&gt;",">",$string);
			$string=str_replace("&amp;","&",$string);
			return $string;
		}
		
		private function clearDescriptionForGoogle($text){
			$text= str_replace("
			", " ",str_replace("\t", " ",str_replace("\n", " ", str_replace("\r", " ", str_replace("\r\n", " ", htmlspecialchars($this->strip_html_tags(htmlspecialchars_decode($text,ENT_COMPAT)),ENT_COMPAT, 'UTF-8'))))));
			while (strpos($text, "  ") !== false) {
				$text=str_replace("  "," ",$text);
			}
			$text=$this->fixEncoding($text);
			
			while($this->startsWith($text,"&amp;nbsp;") || $this->endsWith($text,"&amp;nbsp;") || $this->startsWith($text," ") || $this->endsWith($text," ")) {
				$text = $this->clearDescription($text,"&amp;nbsp;");
				$text = $this->clearDescription($text," ");
			}
			
			while (strpos($text, '  ') !== false) {
				$text=str_replace('  ',' ',$text);
			}
			
			$text=trim($text);
			
			return $text;
		}
		
		private function clearDescription($string, $remove)
		{
			while ($this->startsWith($string,$remove)){
				$string = substr($string, strlen($remove));
			}
			while ($this->endsWith($string,$remove)){
        		$string = substr($string, 0, strlen($string) - strlen($remove));
			}
			
			
			
			return $string;
		}
		
		private function startsWith($haystack, $needles)
		{
			foreach ((array) $needles as $needle)
			{
				if ($needle != '' && strpos($haystack, $needle) === 0) return true;
			}
			return false;
		}
		
		private function endsWith($haystack, $needles)
		{
			foreach ((array) $needles as $needle)
			{
				if ((string) $needle === substr($haystack, -strlen($needle))) return true;
			}
			return false;
		}
		
		protected function getPath($parent_id, $current_path = '') {
			$category_info = $this->model_catalog_category->getCategory($parent_id);
			
			if ($category_info) {
				if (!$current_path) {
					$new_path = $category_info['category_id'];
					} else {
					$new_path = $category_info['category_id'] . '_' . $current_path;
				}
				
				$path = $this->getPath($category_info['parent_id'], $new_path);
				
				if ($path) {
					return $path;
					} else {
					return $new_path;
				}
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
		
		private function formatProductAttributes($attribute_groups){
			
			$result = '';
			foreach ($attribute_groups as $ag){
				if (!empty($ag['attribute'])){
					foreach ($ag['attribute'] as $attribute){
						$result .= $attribute['name'] . ' : ' . $attribute['text'] . '. ';
					}					
				}
			}
			
			$result = trim($result);
			
			return $result;
		}
		
		private function formatProductOcFiltersForDescription($ocfilters){
			
			$result = '';
			
			foreach ($ocfilters as $ocfilter){
				$result .= $ocfilter['name'] . ' : ' . $ocfilter['value'] . '. ';
			}					
			
			
			$result = trim($result);
			
			return $result;
		}
		
		private function formatMaterial($string){
			
			$exploded = explode(',', $string);
			$resultArray = array();
			$result = '';
			
			foreach ($exploded as $line){
				$resultArray[] = trim(mb_ucfirst($line));
			}
			
			return implode('/', $resultArray);
		}
		
		private function getProductMaterial($attribute_groups){
			
			foreach ($attribute_groups as $ag){
				if (!empty($ag['attribute'])){
					foreach ($ag['attribute'] as $attribute){
						//	$this->echoLine('[MTR] Атрибут, ' . $attribute['name']);
						if (mb_strpos(mb_strtolower(trim($attribute['name'])), 'матер') === 0){
							//	$this->echoLine('[MTR] Нашли материал, ' . trim($this->formatMaterial($attribute['text'])));
							return trim($this->formatMaterial($attribute['text']));
							break;
						}
						
					}					
				}
			}
			
			return false;
		}				
		
		private function getProductGoogleTaxonomyClever($categories){
			$this->load->model('feed/google_merchant_center');
			
			foreach ($categories as $category){
				//Первый цикл - основная категория задана
				if ($category['main_category']){
					if ($category['taxonomy_id']){						
						return $this->model_feed_google_merchant_center->getTaxonomyName($category['taxonomy_id']);
						} else {
						//Не задано у основной категории, пройдемся по дереву
						$path = $this->getPath($category['category_id']);					
						if ($path) {	
							$exploded = explode('_', $path);
							array_pop($exploded);
							
							foreach ($exploded as $path_id) {								
								$category_info = $this->model_catalog_category->getCategory($path_id);								
								if ($category_info['taxonomy_id']){								
									return $this->model_feed_google_merchant_center->getTaxonomyName($category_info['taxonomy_id']);
								}
							}
						}
					}				
					} else {
					continue;
				}
			}
			
			//Второй цикл - ну вот почему-то основная категория не задана
			unset($category);
			foreach ($categories as $category){
				if ($category['taxonomy_id']){
					return $this->model_feed_google_merchant_center->getTaxonomyName($category['taxonomy_id']);
					} else {
					$path = $this->getPath($category['category_id']);
					if ($path) {	
						$exploded = explode('_', $path);
						array_pop($exploded);
						
						foreach ($exploded as $path_id) {
							$category_info = $this->model_catalog_category->getCategory($path_id);
							if ($category_info['taxonomy_id']){
								return $this->model_feed_google_merchant_center->getTaxonomyName($category_info['taxonomy_id']);
							}
						}
					}
				}				
			}
			
			//дефолтное значение
			return $this->model_feed_google_merchant_center->getTaxonomyName($this->default_taxonomy_id);
		}
		
		private function generateHighlights($product_info, $attribute_groups){
			$highlights = array();
			
			if (trim(str_replace(PHP_EOL, '', $product_info['highlight']))){
				$product_info_highlights = explodeByEOL($product_info['highlight']);
				
				foreach ($product_info_highlights as $line){
					$highlights[] = $line;
				}
			}		
			
			if ($attribute_groups){
				foreach ($attribute_groups as $ag){
					if (!empty($ag['attribute'])){				
						foreach ($ag['attribute'] as $attribute){							
							if ($attribute['highlight'] && trim($attribute['name']) && trim($attribute['text'])){
								$highlights[] = $attribute['name'] . ': ' . $attribute['text'];
							}
						}
					}
				}
			}					
			
			if (count($highlights) > 2){			
				return array_slice(array_unique($highlights), 0, 10);
				} else {
				return array();
			}
			
		}
		
		private function guessCompatibilityBrandAndModel($ocfilters){
			$result = array(
			'brand' => false,
			'model' => false
			);
		
			
			foreach ($ocfilters as $key => $ocfilter){
				
				foreach ($this->ocfilterBrandCompatibilityTriggerWord as $ocfilterBrandCompatibilityTriggerWord){
					if (!$result['brand'] && stripos(mb_strtolower($key), mb_strtolower($ocfilterBrandCompatibilityTriggerWord)) !== false){
						$result['brand'] = $ocfilter['value'];
						break;
					}
				}
				
				foreach ($this->ocfilterModelCompatibilityTriggerWord as $ocfilterModelCompatibilityTriggerWord){
					if (!$result['model'] && stripos(mb_strtolower($key), mb_strtolower($ocfilterModelCompatibilityTriggerWord)) !== false){
						$result['model'] = $ocfilter['value'];
						break;
					}
				}
			}
			
			
			return $result;
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
		
		public function supplemental(){					
			$this->load->model('catalog/product');
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = 0 WHERE quantity < 0 ");
			$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = 0 WHERE quantity < 0 ");
			
			$currency_code = $this->config->get('config_currency');			
			$currency_value = $this->currency->getValue($currency_code);
			
			$products = $this->model_catalog_product->getSupplementalFeedOptions();	
			
			$file = DIR_FEEDS . 'google_merchant_options_supplemental_paq.xml';
			
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL;
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . PHP_EOL;
			$output .= '<channel>' . PHP_EOL;
			$output .= '<title><![CDATA[' . $this->config->get('config_name') . ']]>111</title>' . PHP_EOL;
			$output .= '<description><![CDATA[' . $this->config->get('config_meta_description') . ']]>111</description>' . PHP_EOL;
			$output .= '<link><![CDATA[' . $this->config->get('config_url') . ']]></link>' . PHP_EOL;
			
			foreach ($products as $product){
				$output .= '<item>' . PHP_EOL;	
				$output .= '<g:id><![CDATA[' . $product['product_id'] . '-' . (int)$product['option_id'] . '-' . (int)$product['option_value_id'] . ']]></g:id>' . PHP_EOL;	
				$output .= '<g:quantity><![CDATA[' . $product['quantity'] . ']]></g:quantity>' . PHP_EOL;
				$output .= '<g:availability><![CDATA[' . ($product['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>' . PHP_EOL;
				$output .= '<g:price><![CDATA[' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false) . ' ' . $currency_code . ']]></g:price>' . PHP_EOL;					
				if ((float)$product['special']) {
					$output .= '<g:sale_price_effective_date></g:sale_price_effective_date>' . PHP_EOL;
					$output .= '<g:sale_price><![CDATA[' .  $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency_code, $currency_value, false) . ' ' . $currency_code . ']]></g:sale_price>' . PHP_EOL;
				}
				$output .= '</item>' . PHP_EOL;	
			}
			
			$output .= '</channel>'. PHP_EOL;
			$output .= '</rss>'. PHP_EOL;
			
			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);
			
			gc_collect_cycles();
			
			header('Content-Type: application/xml');
			echo($output);
			
		}
		
		public function cron(){
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = 0 WHERE quantity < 0 ");
			$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = 0 WHERE quantity < 0 ");
			
			$this->echoLine('[TIME] Начали в ' . date('H:i:s'));
			
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('feed/google_merchant_center');
			$this->load->model('tool/image');
			
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();						
			
			foreach ($languages as $language){									
				$this->config->set('config_language_id', $language['language_id']);
				$this->config->set('config_language', $language['code']);
				
				$currency_code = $this->config->get('config_currency');			
				$currency_value = $this->currency->getValue($currency_code);
				$language_id = $this->config->get('config_language_id');
				$store_id = $this->config->get('config_store_id');
				$attribute_id = $this->config->get('google_merchant_center_attribute');
				$attribute_id_type = $this->config->get('google_merchant_center_attribute_type');
				$google_merchant_center_availability = $this->config->get('google_merchant_center_availability');
				$width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
				$height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
				if ($width < 600 || $height < 600) {
					$width = 600;
					$height = 600;
				}
				
				$langmark = $this->config->get('asc_langmark');
				
				if ($langmark && isset($langmark['prefix']) && isset($langmark['prefix'][$language['code']])){
					$this->langprefix = trim($langmark['prefix'][$language['code']]);
				}
				
				$file = DIR_FEEDS . 'google_merchant_extended_options_' . $language['code'] . '.xml';
				
				$this->echoLine('[i] Язык 111 ' . $language['code'] . ', файл ' . $file);		
				
				if ($language['code'] == 'ru-ru') {
					$shop_name = 'Интернет-магазин VEST';
					$shop_description = 'VEST – интернет-магазин аксессуаров для смартфонов, планшетов и смарт-часов. Большой выбор, доступные цены и быстрая доставка по Украине ✈ ☑ Гарантия 100% ☎ +38 (050) 167-30-44 ';
				} else if ($language['code'] == 'uk-ua') {
					$shop_name = 'Інтернет-магазин VEST';
					$shop_description = 'VEST - інтернет-магазин аксесуарів для смартфонів, планшетів та смарт-годинників. Великий вибір, доступні ціни та швидка доставка по Україні ✈  ☑ Гарантія 100%  ☎ +38 (050) 167-30-44';
				} else {
					$shop_name = $this->config->get('config_name');
					$shop_description = $this->config->get('config_meta_description');
				}

				$this->echoLine('[i] Название ' . $shop_name);		
				$this->echoLine('[i] Описание ' . $shop_description);	
				
				$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
				$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
				$output .= '  <channel>';
				$output .= '  <title>' . $shop_name . '222</title>';
				$output .= '  <description>' . $shop_description . '222</description>';
				$output .= '  <link>' . $this->config->get('config_url') . '</link>';
				
				
				$products = $this->model_feed_google_merchant_center->getProductsOptioned($language_id, $store_id);
				$this->echoLine('[i] Всего товаров с опциями ' . count($products));		
				
				$i = 1;
				foreach ($products as $_product){
					
					if ($i%100 == 0){
						$this->echoSimple($i . '...');
					}
					$i++;
					
				//	var_dump($_product['product_id']);
					
					$product = $this->model_catalog_product->getProduct($_product['product_id']);
					$options = $this->model_catalog_product->getProductOptions($product['product_id']);
					$attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);
					$ocfilters = $this->model_catalog_product->getProductOcFilterActiveValues($product['product_id'], ', ');	
					
					$ocfilters = attributesOcFilterUnique($attributes, $ocfilters, 'ocfilter');	
					$highlights = $this->generateHighlights($product, $attributes);
					
					$categories = $this->model_catalog_product->getCategoriesExtended($product['product_id']);
					$category_path = '';
					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);
						
						if ($path) {
							$category_path = '';
							
							foreach (explode('_', $path) as $path_id) {
								$category_info = $this->model_catalog_category->getCategory($path_id);
								
								if ($category_info) {
									if (!$category_path) {
										$category_path = $category_info['name'];
										} else {
										$category_path .= ' &gt; ' . $category_info['name'];
									}
								}
							}													
						}
					}
					
					$description = $product['description'];
					if ($attributes){
						$description .= ' ' . $this->formatProductAttributes($attributes);
						$material = $this->getProductMaterial($attributes);
					}
					if ($ocfilters){
						$description .= ' ' . $this->formatProductOcFiltersForDescription($ocfilters).'';
					}
					
					//Дополнительные характеристики (совместимость с модулем FGMC)
					$FGMC_data = $this->model_feed_google_merchant_center->getProductExtra($product['product_id'], $attribute_id, $language_id);
					
					$FGMC_addon = '';
					if (isset($FGMC_data['age_group']) && $FGMC_data['age_group']!=''){
						$FGMC_addon .= '<g:age_group><![CDATA['. $FGMC_data['age_group'] . ']]></g:age_group>' . PHP_EOL;
						} else {					
						//$FGMC_addon .= '<g:age_group><![CDATA[adult]]></g:age_group>' . PHP_EOL;
					}
					
					if (isset($FGMC_data['gender']) && $FGMC_data['gender'] != ''){
						$FGMC_addon .= '<g:gender><![CDATA['. $FGMC_data['gender'] . ']]></g:gender>' . PHP_EOL;
						} else {					
						//$FGMC_addon .= '<g:gender><![CDATA[unisex]]></g:gender>' . PHP_EOL;
					}										
					
					$google_taxonomy = $this->getProductGoogleTaxonomyClever($categories);
					
					if ($options){
						foreach ($options as $option){		
							
							if (!empty($option['product_option_value'])){
								foreach ($option['product_option_value'] as $option_value){
									
									$output .= '<item>';									
									$output .= '<title><![CDATA[' . trim($this->fixEncoding($product['name'] . ' ' .  mb_strtolower($option_value['name']))) . ']]></title>' . PHP_EOL;
									$output .= '<link><![CDATA[' . $this->link('product/product', 'product_id=' . $product['product_id'] . '&option_id=' . $option_value['product_option_value_id']) . ']]></link>' . PHP_EOL;																											
									$output .= '<description><![CDATA[' . $this->clearDescriptionForGoogle($description) . ']]></description>' . PHP_EOL;
									
									//IDS
									$output .= '<g:id><![CDATA[' . $product['product_id'] . '-' . (int)$option['option_id'] . '-' . (int)$option_value['option_value_id'] . ']]></g:id>' . PHP_EOL;
									
									if ($product['ean'] || $option_value['ean']){
										if ($option_value['ean']){
											$output .= '  <g:gtin><![CDATA[' . trim($this->fixEncoding($option_value['ean'])) . ']]></g:gtin>' . PHP_EOL;
											} elseif ($product['ean']) {
											$output .= '  <g:gtin><![CDATA[' . trim($this->fixEncoding($product['ean'])) . ']]></g:gtin>' . PHP_EOL;
										}
									}
									
									if ($option_value['sku']){
										$output .= '  <g:mpn><![CDATA[' . trim($this->fixEncoding($option_value['sku'])) . ']]></g:mpn>' . PHP_EOL;
										} else {
										$output .= '  <g:mpn><![CDATA[' . $product['product_id'] . '-' . (int)$option['option_id'] . '-' . (int)$option_value['option_value_id'] . ']]></g:mpn>' . PHP_EOL;
									}
									
									if ($product['upc']) {
										$output .= '  <g:upc><![CDATA[' . trim($this->fixEncoding($product['upc'])) . ']]></g:upc>' . PHP_EOL;
									}
									
									if ($material){
										$output .= '<g:material><![CDATA[' . trim($this->fixEncoding($material)) . ']]></g:material>' . PHP_EOL;
									}
									
									if ($attributes){
										foreach ($attributes as $ag){
											if (!empty($ag['attribute'])){
												foreach ($ag['attribute'] as $attribute){
													if (!$attribute['highlight']){
														$output .= '<g:product_detail>' . PHP_EOL;
														$output .= '<g:section_name><![CDATA[' . $ag['name'] . ']]></g:section_name>' . PHP_EOL;
														$output .= '<g:attribute_name><![CDATA[' . $attribute['name'] . ']]></g:attribute_name>' . PHP_EOL;
														$output .= '<g:attribute_value><![CDATA[' . $attribute['text'] . ']]></g:attribute_value>' . PHP_EOL;
														$output .= '</g:product_detail>' . PHP_EOL;
													}
												}					
											}
										}
									}
									
									if ($ocfilters){
										foreach ($ocfilters as $ocfilter){
											$output .= '<g:product_detail>' . PHP_EOL;
											$output .= '<g:section_name><![CDATA[Характеристики]]></g:section_name>' . PHP_EOL;
											$output .= '<g:attribute_name><![CDATA[' . $ocfilter['name'] . ']]></g:attribute_name>' . PHP_EOL;
											$output .= '<g:attribute_value><![CDATA[' . $ocfilter['value'] . ']]></g:attribute_value>' . PHP_EOL;
											$output .= '</g:product_detail>' . PHP_EOL;
										}					
									}
									
									if ($highlights){
										foreach ($highlights as $highlight){
											$output .= '<g:product_highlight><![CDATA[' . $highlight . ']]></g:product_highlight>' . PHP_EOL;
										}
									}
									
									
									$output .= '<g:model_number><![CDATA[' . trim($this->fixEncoding($product['model'])) . ']]></g:model_number>' . PHP_EOL;
									$output .= '<g:item_group_id><![CDATA[' . trim($product['product_id']) . ']]></g:item_group_id>' . PHP_EOL;
									
									$output .= '<g:brand><![CDATA[' . trim($this->fixEncoding($product['manufacturer'])) . ']]></g:brand>' . PHP_EOL;
									$output .= '<g:condition>new</g:condition>' . PHP_EOL;
									
									$output .= '  <g:price><![CDATA[' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false) . ' ' . $currency_code . ']]></g:price>' . PHP_EOL;
									
									$price = $product['price'];
									
									if ((float)$product['special']) {
										$output .= '<g:sale_price_effective_date></g:sale_price_effective_date>' . PHP_EOL;
										$output .= '<g:sale_price><![CDATA[' .  $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency_code, $currency_value, false) . ' ' . $currency_code . ']]></g:sale_price>' . PHP_EOL;
										
										$price = $product['special'];
									}
									//Совместимость в особую метку					
									$compatibility = $this->guessCompatibilityBrandAndModel($ocfilters);
									
									if ($compatibility['brand']){
									//	$output .= '<g:custom_label_0><![CDATA['. $compatibility['brand'] .']]></g:custom_label_0>' . PHP_EOL;
									}
									
									if ($compatibility['model']){
									//	$output .= '<g:custom_label_1><![CDATA['. $compatibility['model'] .']]></g:custom_label_1>' . PHP_EOL;
									}
									
								
									if ($this->model_catalog_product->checkIfProductIsFreeDelivered($product)){
										
										$output .= '<g:custom_label_4>free_delivery</g:custom_label_4>' . PHP_EOL;
										
										if (isset($this->deliveries[$language['code']])){							
											foreach ($this->deliveries[$language['code']] as $delivery){
												$output .= '<g:shipping>' . PHP_EOL;						
												$output .= '<g:country>UA</g:country>' . PHP_EOL;
												$output .= '<g:shipping_country>UA</g:shipping_country>' . PHP_EOL;
												$output .= '<g:service>' . $delivery . '</g:service>' . PHP_EOL;
												$output .= '<g:price>0 ' . $currency_code . '</g:price>' . PHP_EOL;					
												$output .= '</g:shipping>' . PHP_EOL;
											}
										}
										
										} else {					
										$output .= '<g:custom_label_4>paid_delivery</g:custom_label_4>' . PHP_EOL;
									}
									
									//Картинка
									if ($option_value['o_v_image'] && file_exists(DIR_IMAGE . $option_value['o_v_image'])){
										$output .= '  <g:image_link><![CDATA[' . trim($this->model_tool_image->resize($option_value['o_v_image'], $width, $height)) . ']]></g:image_link>' . PHP_EOL;
										} elseif ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
										$output .= '  <g:image_link><![CDATA[' . trim($this->model_tool_image->resize($product['image'], $width, $height)) . ']]></g:image_link>' . PHP_EOL;
										} else {
										$output .= '  <g:image_link></g:image_link>' . PHP_EOL;
									}
									
									//OCT COMPATIBLE
									$oct_images = $this->model_catalog_product->getProductImagesByOptionValueId($product['product_id'], array($option_value['option_value_id']));
									
									if ($oct_images){
										foreach ($oct_images as $oct_image){
											if ($oct_image['image'] != $option_value['o_v_image']){
												if (file_exists(DIR_IMAGE . $oct_image['image'])){
													$output .= '<g:additional_image_link><![CDATA[' . str_replace(" ","%20",htmlspecialchars($this->model_tool_image->resize($oct_image['image'], $width, $height), ENT_COMPAT, 'UTF-8')) . ']]></g:additional_image_link>' . PHP_EOL;
												}
											}
										}
									}
									
									//Если это цвет
									if (in_array($option['option_id'], $this->color_options_array)){
										$output .= '  <g:color><![CDATA[' . trim($this->fixEncoding($option_value['name'])) . ']]></g:color>' . PHP_EOL;
									}
									
									//Если это узор
									if (in_array($option['option_id'], $this->pattern_options_array)){										
										$output .= '  <g:pattern><![CDATA[' . trim($this->fixEncoding($option_value['name'])) . ']]></g:pattern>' . PHP_EOL;
									}
									
									if ($FGMC_addon){
										$output .= $FGMC_addon;
									}
									
									$output .= '<g:product_type><![CDATA[' . trim($this->fixEncoding($category_path)) . ']]></g:product_type>' . PHP_EOL;
									$output .= '<g:google_product_category><![CDATA[' . $google_taxonomy . ']]></g:google_product_category>'. PHP_EOL;													
									
									$output .= '  <g:quantity><![CDATA[' . $option_value['quantity'] . ']]></g:quantity>' . PHP_EOL;
									if ($product['weight']){
										$output .= '  <g:weight><![CDATA[' . $this->weight->format($product['weight'], $product['weight_class_id']) . ']]></g:weight>' . PHP_EOL;
									}
									$output .= '  <g:availability><![CDATA[' . ($option_value['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>' . PHP_EOL;
									$output .= '</item>'. PHP_EOL;
									
								}
								
							}
						}
					}
				}
				
				$output .= '  </channel>'. PHP_EOL;
				$output .= '</rss>'. PHP_EOL;
				
				$this->echoLine('');
				
				$handle = fopen($file, 'w+');
				flock($handle, LOCK_EX);
				fwrite($handle, $output);
				flock($handle, LOCK_UN);
				fclose($handle);
				
				gc_collect_cycles();
				$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
				
				
			}
			
			$this->echoLine('[TIME] Закончили в ' . date('H:i:s'));
		}
	}																				