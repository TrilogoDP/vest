<?php

class ControllerFeedRozetkaYMLBuilder extends Controller {
	private $limit 					= 1000;
	private $langprefix 			= '';
	private $color_options_array 	= array(14, 17, 18, 19);
	private $print_options_array 	= array(19);	
	private $exclude_ocfilters 		= array('Цвет', 'Колір', 'Принт');
	private $multicolor_text 		= array('ru-ru' => 'Разные цвета', 'uk-ua' => 'Різні кольори');
	private $multiprint_text 		= array('ru-ru' => 'Разные принты', 'uk-ua' => 'Різні принти');
	private $multi_ocfilters 		= array('Совместимость');
	private $common_colours 		= array('red','orange','yellow','green','blue','purple','brown','magenta','tan','cyan','olive','maroon','navy','aquamarine','turquoise','silver','lime','teal','indigo','violet','pink','black','white','gray','grey');
	private $statusFile 			= DIR_CACHE . '/feedupdater.status';
	private $status;
	private $currency_code;
	private $purifier;

	private $pid_divider = 134063;

	private $mode        = 'rozetka';

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

	private function getProductColor($attribute_groups, $options, $product_name){

			//Если задан атрибут - то цвет мы берем из него
		foreach ($attribute_groups as $ag){
			if (!empty($ag['attribute'])){
				foreach ($ag['attribute'] as $attribute){
					if (stripos($attribute['name'], 'цвет') !== false || stripos($attribute['name'], 'колір') !== false ){
						return trim($attribute['name']);
						break;
					}
				}					
			}
		}

			//Проверка в названии товара, существует ли нечто, определяющее цвет
		foreach ($this->common_colours as $colour){
				//да, нестрогое соответствие, потому что в начале цвет не пишут
			if (stripos($product_name, $colour . ' ') != false){
				return $colour;
			}

			if (mb_strtolower(substr($product_name, -1 * mb_strlen($colour))) == $colour){
				return $colour;
			}
		}

			//Если в атрибутах нету чего-либо, обозначающего цвет, то мы берем опции, и если опция имеет только одно значение
		foreach ($options as $option){
			if (in_array($option['option_id'], $this->color_options_array) || stripos($option['name'], 'цвет' || stripos($attribute['name'], 'колір') !== false) !== false){
				if (!empty($option['product_option_value']) && count($option['product_option_value']) == 1){
					if ($option['product_option_value'][0]['name']){
						return trim($option['product_option_value'][0]['name']);
					}
				}
			}
		}						

		return false;
	}

	private function formatProductAttributesForDescription($attribute_groups){

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

	protected function strip_html_tags( $text ) {
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

	protected function prepareField($field) {
		$field = htmlspecialchars_decode($field);

		$field = $this->purifier->purify($field);
		$field = str_replace(['<br>', '<br/>', '<br />'], PHP_EOL, $field);
		$field = strip_tags($field, "<br/><br /><br><a>");		
		$from = array('&', '>', '<', '\'', '&nbsp;');
		$to = array('&amp;', '&gt;', '&lt;', '`', ' ');
		$field = str_replace($from, $to, $field);
		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}

	protected function prepareFieldParamName($field) {
		$field = htmlspecialchars_decode($field);
		$field = strip_tags($field, "<br><a><br />");		
		$from = array('&', '>', '<', '\'', '&nbsp;', '  ', '"');
		$to = array(' and ', '&gt;', '&lt;', '&apos;', ' ', ' ', '');
		$field = str_replace($from, $to, $field);
		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}
		
	protected function array2Param($params) {
		$retval = '';
		foreach ($params as $param) {
			$retval .= '<param name="' . $this->prepareField($param['name']);
			if (isset($param['unit'])) {
				$retval .= '" unit="' . $this->prepareField($param['unit']);
			}
			$retval .= '">' . $this->prepareField($param['value']) . '</param>' . $this->eol;
		}

		return $retval;
	}			

	private function addCDATA($value){

		if ($this->mode == 'allo'){
			return $this->prepareFieldParamName($value);
		}

		return '<![CDATA[' . $this->prepareField(trim($value)) . ']]>';

	}
		
	private function getProductVendorCode($product){

		$vendorCode = false;
		if (!empty($product['sku'])){
			$vendorCode = trim($product['sku']);
		}				

		if (!$vendorCode && !empty($product['product_id'])){
			$vendorCode = trim($product['product_id']);
		}	

		if (!$vendorCode && !empty($product['mpn'])){
			$vendorCode = trim($product['mpn']);
		}

		if (!$vendorCode && !empty($product['ean'])){
			$vendorCode = trim($product['ean']);
		}	

		if (!$vendorCode && !empty($product['upc'])){
			$vendorCode = trim($product['upc']);
		}

		if (!$vendorCode && !empty($product['product_id'])){
			$vendorCode = trim($product['product_id']);
		}	

		return $vendorCode;
	}

	private function getOptionType($options){			
		if ($options){				
			foreach ($options as $option){
				if ($option['option_id'] == 14 || $option['option_id'] == 17 || $option['option_id'] == 18){
					return 'color';
				}

				if ($option['option_id'] == 19){
					return 'print';
				}
			}
		}

		return false;
	}

	private function getRozetkaOverprice($category_id)
	{
		$query = $this->db->query("SELECT rozetka_overprice FROM " . DB_PREFIX . "category WHERE category_id='" . (int)$category_id . "'");

		return $query->row['rozetka_overprice'];
	}

	private function getProductCategory($product_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id='" . (int)$product_id . "' order by (main_category=1) DESC LIMIT 1");

		return $query->row['category_id'];
	}

	private function checkIfCanDoPromoPrice($product)
	{
		if ($product['special']) {
			if ($product['special'] < $product['price']) {
				if (($product['price'] - ($product['price']/100)*15) > $product['special']) {
					$promo_discount = (($product['special']/100)*1);
					if ($promo_discount > 1) {
						return round($product['special'] - $promo_discount);
					} else {
						return round($product['special'] - 1);
					}
				}
			}
		}

		return false;
	}

	public function index() {
		if (!defined('OPENCART_CLI_MODE')){
			die('CLI ONLY');
		}
	}

	private function getStatus(){

		if (!file_exists($this->statusFile)){
			$this->setStatus('idle');
		}

		$this->status = file_get_contents($this->statusFile);

	}

	private function setStatus($status){
		file_put_contents($this->statusFile, $status);			
	}	

	public function cron(){
		$this->cron_wrapped();
		$this->cron_wrapped(true);

		$this->mode = 'allo';
		$this->cron_wrapped(true);
	}

		
	public function cron_wrapped($from = false) {
		if (!defined('OPENCART_CLI_MODE')){
			die('CLI ONLY');
		}

		$this->purifier = new \HTMLPurifier(\HTMLPurifier_Config::createDefault());

		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = 0 WHERE quantity < 0 ");
		$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = 0 WHERE quantity < 0 ");

		$this->getStatus();

		if ($this->status != 'idle'){
			$this->echoLine('now working, exit');
		}

		$this->setStatus('rozetkayml');

		$this->echoLine('[TIME] Начали в ' . date('H:i:s'));

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('feed/google_merchant_center');
		$this->load->model('tool/image');
		$this->load->model('localisation/currency');
		$this->load->model('export/yandex_yml');

		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();			

		foreach ($languages as $language){	

			if ($language['code'] == 'uk-ua'){
				continue;
			}

			$this->language_code = $language['code'];
			$this->config->set('config_language_id', $language['language_id']);
			$this->config->set('config_language', $language['code']);

			$this->currency_code = $currency_code = $this->config->get('config_currency');			
			$currency_value = $this->currency->getValue($currency_code);
			$language_id = $this->config->get('config_language_id');
			$store_id = $this->config->get('config_store_id');

			$langmark = $this->config->get('asc_langmark');

			if ($langmark && isset($langmark['prefix']) && isset($langmark['prefix'][$language['code']])){
				$this->langprefix = trim($langmark['prefix'][$language['code']]);
			}

			$file = DIR_FEEDS . 'rozetka_hotline_ymlfeed.xml';

			if ($from){
				$file = DIR_FEEDS . 'rozetka_hotline_ymlfeed_' . $this->pid_divider . '.xml';
			}

			if ($this->mode == 'allo'){
				$file = DIR_FEEDS . 'allo_ua_ymlfeed_' . $this->pid_divider . '.xml';
			}

			$this->echoLine('[i] Язык ' . $language['code'] . ', файл ' . $file);		

			$output = '';
			$output .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
			$output .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . PHP_EOL;
			$output .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . PHP_EOL;
			$output .= '<shop>' . PHP_EOL;
			$output .= '<name>' . $this->prepareField($this->config->get('config_name')) . '</name>' . PHP_EOL;
			$output .= '<company>' . $this->prepareField($this->config->get('config_owner')) . '</company> ' . PHP_EOL;
			$output .= '<url>' . $this->config->get('config_url') . '</url> ' . PHP_EOL;
			$output .= '<phone>' . $this->config->get('config_telephone') . '</phone> ' . PHP_EOL;
			$output .= '<platform>Rozetka.ua YML CLI Builder (v.zaichikov@gmail.com)</platform> ' . PHP_EOL;
			$output .= '<version>2.3</version>' . PHP_EOL;

			$output .= '<currencies>' . PHP_EOL;
			$output .= '	<currency id="' . $currency_code . '" rate="1"/>' . PHP_EOL;								
			$output .= '</currencies>' . PHP_EOL;


			$output .= '<categories>' . PHP_EOL;
			$categories = $this->model_catalog_category->getAllCategories();			

			foreach ($categories as $category) {
				if ($category['name'] && $category['category_id']){
					if ($category['parent_id']){
						$output .= '	<category id="' . $category['category_id'] . '" parentID="' . $category['parent_id'] . '">' . $this->prepareField($category['name']) . '</category>' . PHP_EOL;
					} else {
						$output .= '	<category id="' . $category['category_id'] . '">' . $this->prepareField($category['name']) . '</category>' . PHP_EOL;
					}
				}				
			}			

			$output .= '</categories>' . PHP_EOL;

			$output .= '<offers>' . PHP_EOL;

			$filter_data = [];		
			$filter_data['filter_not_archive'] 		= true;	
			$filter_data['filter_not_tax'] 			= true;
			$filter_data['filter_custom_yml_mf'] 	= true;

			if ($from){
				$filter_data['filter_min_product_id'] 	= $this->pid_divider;
			} else {
				$filter_data['filter_max_product_id'] 	= $this->pid_divider;
			}

			$products = $this->model_catalog_product->getProducts($filter_data);

			$this->echoLine('[i] Всего товаров ' . count($products));	

			$i = 1;
			foreach ($products as $product){

				if ($i%100 == 0){
					$this->echoSimple($i . '...');
				}
				$i++;

				$options 			= $this->model_catalog_product->getProductOptions($product['product_id']);					
				$descriptions 		= $this->model_catalog_product->getProductDescriptions($product['product_id']);

				$attributes 		= $this->model_catalog_product->getProductAttributes($product['product_id']);					
				$attributes_ua 		= $this->model_catalog_product->getProductAttributes($product['product_id'], 3);

				$ocfilters 			= $this->model_catalog_product->getProductOcFilterActiveValues($product['product_id'], '|');
				$ocfilters_ua 		= $this->model_catalog_product->getProductOcFilterActiveValues($product['product_id'], '|', 3);

				$images 			= $this->model_catalog_product->getProductImagesForFeeds($product['product_id']);
				$main_category_id 	= $this->model_catalog_product->getMainCategoryID($product['product_id']);

				$product['main_category_id'] = $main_category_id;

				$product['name_ua'] 				= $descriptions[3]['name'];
				$product['description_ua'] 			= $descriptions[3]['description'];
				$product['fake_description_ua'] 	= $descriptions[3]['fake_description'];

				$ocfilters 		= attributesOcFilterUnique($attributes, $ocfilters, 'ocfilter');	
				$ocfilters_ua 	= attributesOcFilterUnique($attributes_ua, $ocfilters_ua, 'ocfilter');	

				if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
					array_unshift($images, array('image' => $product['image']));
				}

				if ($options && $this->getOptionType($options)){
					$product['group_id'] = $product['product_id'];
				}

				if (!$options){
					$output .= $this->ymlProduct($product, $product['product_id'], $options, $ocfilters, $attributes, $ocfilters_ua, $attributes_ua, $images, $main_category_id);
				}

					//OPTIONS OFFERS
				if ($options){
					foreach ($options as $option){
						if (!empty($option['product_option_value'])){
							foreach ($option['product_option_value'] as $option_value){

								$o_product = $product;
								
								if ($this->mode != 'allo'){
									$o_product['product_id'] 	= 	$product['group_id']
								.(isset($option_value['option_value_id']) ? str_pad($option_value['option_value_id'], 7, '0', STR_PAD_LEFT) : '');
								}

								$o_product['is_option_id'] 	= 	$option_value['product_option_value_id'];
								$o_product['group_id'] 		=	$product['product_id'];
								$o_product['name'] 			= 	trim($product['name']) . ' ' .  colorToEng(mb_strtolower($option_value['name']));

								$option_value_name_ua = $this->model_catalog_product->getProductOptionsUAForPromUa($option['option_id'], $option_value['option_value_id']);

								if ($option_value_name_ua){
									$o_product['name_ua'] = trim($product['name_ua']) . ' ' .  colorToEng(mb_strtolower($option_value_name_ua));
								} else {
									$o_product['name_ua'] = trim($product['name_ua']) . ' ' .  colorToEng(mb_strtolower($option_value['name']));											
								}

								$settle_color = false;
								if (in_array($option['option_id'], $this->color_options_array) !== false){
									$settle_color = $option_value['name'];

									$settle_color_as_print = false;
									if (in_array($option['option_id'], $this->print_options_array) !== false){
										$settle_color_as_print = true;
									}
								}

								if ($option_value['price_prefix'] == '+') {
									$o_product['price'] = $product['price'] + $option_value['price'];
									if ($product['special']){
										$o_product['special'] = $product['special'] + $option_value['price'];
									}
								} elseif ($option_value['price_prefix'] == '-') {
									$o_product['price'] = $product['price'] - $option_value['price'];
									if ($product['special']){
										$o_product['special'] = $product['special'] - $option_value['price'];
									}
								} elseif ($option_value['price_prefix'] == '=') {
									$o_product['price'] = $option_value['price'];

									if ($product['special']){
										if ($product['special'] > $o_product['price']){
											$o_product['special'] = false;
										} else {
											$o_product['special'] = $product['special'];
										}
									}
								}


								$prefix = '';
								$prefixed = false;
								if (count($exploded = explode('-', $product['model'])) == 2) {										
									if (strpos($product['sku'], $exploded[1]) === 0) {
										$prefix = $exploded[1];
										$o_product['sku'] = $prefix . $option_value['sku'];
										$prefixed = true;
									}
								}

								if (!$prefixed && $option_value['sku']) {
									$o_product['sku'] = $option_value['sku'];
								}

								if ($option_value['mpn']){
									$o_product['mpn'] = $option_value['mpn'];
								}

								if ($option_value['model']){
									$o_product['model'] = $option_value['model'];
								} else {
									$o_product['model'] = $product['model'];
								}

								if ($option_value['ean']){
									$o_product['ean'] = $option_value['ean'];
								}

								$o_product['quantity'] = $option_value['quantity'];

								$o_images = array();
								if ($option_value['o_v_image'] && file_exists(DIR_IMAGE . $option_value['o_v_image'])){
									$o_images[] = array('image' => $option_value['o_v_image']);
								}

								$oct_images = $this->model_catalog_product->getProductImagesByOptionValueId($product['product_id'], array($option_value['option_value_id']));

								if ($oct_images){
									foreach ($oct_images as $oct_image){
										if ($oct_image['image'] != $option_value['o_v_image']){
											$o_images[] = array('image' => $oct_image['image']);
										}
									}
								}

								if (!$o_images){
									$o_images = $images;
								}

								$output .= $this->ymlProduct($o_product, $product['product_id'], array(),  $ocfilters, $attributes, $ocfilters_ua, $attributes_ua, $o_images, $main_category_id, $settle_color, $option_value['product_option_value_id'], $settle_color_as_print);

							}
						}
					}							
				}
			}

			$output .= '</offers>' . PHP_EOL;
			$output .= '</shop>' . PHP_EOL;
			$output .= '</yml_catalog>' . PHP_EOL;

			$this->echoLine('');

			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $output);
			flock($handle, LOCK_UN);
			fclose($handle);

			unset($output);
			unset($products);
			gc_collect_cycles();
			$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
		}								

		$this->echoLine('[TIME] Закончили в ' . date('H:i:s'));

		$this->setStatus('idle');

	}


	private function ymlProduct($product, $product_id, $options,  $ocfilters, $attributes, $ocfilters_ua, $attributes_ua, $images, $main_category_id, $settle_color = false, $is_option_id = false, $settle_color_as_print = false){

		$output = '';

		$offer_id = (int)$product['product_id'];
		if ($this->mode == 'allo'){
			$offer_id = $product['product_id'] . '-' . (string)$product['sku'];
			// $prefix = '';
			// if (count($exploded = explode('-', $product['model'])) == 2) {										
			// 	if (strpos($product['sku'], $exploded[1]) === 0) {
			// 		$prefix = $exploded[1];
			// 		$offer_id['id'] = $prefix . (string)$product['sku'];
			// 	}
			// }
		}
		
		$output = '<offer id="';

		if ($product['quantity'] > 0){
			$output .= $offer_id . '" available="true"';
		} else {
			$output .= $offer_id . '" available="false"';
		}

		if (isset($product['group_id'])){
			$output .= ' group_id="' . $product['group_id'] . '"';
		}

		$output .= '>' . PHP_EOL;

		if (!$is_option_id){
			$output .= '<url><![CDATA[' . $this->link('product/product', 'product_id=' . $product_id) . ']]></url>' . PHP_EOL;
		} else {					
			$output .= '<url><![CDATA[' . $this->link('product/product', 'product_id=' . $product_id . '&option_id=' . $is_option_id) . ']]></url>' . PHP_EOL;
		}
		$output .= '<name><![CDATA[' . $this->prepareField($product['name']) . ']]></name>' . PHP_EOL;
		$output .= '<name_ua><![CDATA[' . $this->prepareField($product['name_ua']) . ']]></name_ua>' . PHP_EOL;

		$output .= '<categoryId><![CDATA[' . $main_category_id . ']]></categoryId>' . PHP_EOL;

		if ($product['manufacturer']){
			$output .= '<vendor><![CDATA[' . $product['manufacturer'] . ']]></vendor>' . PHP_EOL;
		} else {
			$output .= '<vendor><![CDATA[' . 'NoName' . ']]></vendor>' . PHP_EOL;
		}

			//Всякие разные идентификаторы
			//vendorCode = mpn			
		$output .= '<vendorCode><![CDATA[' . $product['mpn'] . ']]></vendorCode>' . PHP_EOL;

			//code = sku, для опций переназначено при обработке опции
		$output .= '<code><![CDATA[' . $product['sku'] . ']]></code>' . PHP_EOL;

			//model = model_marketplace, только в том случае, если задано
		if (!empty($product['model_marketplace'])){
			$output .= '<model><![CDATA[' . $product['model_marketplace'] . ']]></model>' . PHP_EOL;
		} else {
			//	$output .= '<model><![CDATA[' . $product['model'] . ']]></model>' . PHP_EOL;
		}

		$product['price_rrc'] = $product['price'];

		if ($product['special'] && $product['special'] < $product['price']) {
			$tmp = [];

			$product['price_sale'] = $product['special'];

			$tmp['price_old'] 	= $product['price'];
			$tmp['price'] 		= $product['special'];				

			if ($promo = $this->checkIfCanDoPromoPrice($product)) {
				$tmp['price_promo'] = $promo;
			}

			$product['price'] 		= $tmp['price'];
			$product['price_old'] 	= $tmp['price_old'];

			if (isset($tmp['price_promo'])){
				$product['price_promo'] = $tmp['price_promo'];
			}
		}


		$rozetka_overprice = $this->getRozetkaOverprice($product['main_category_id']);
		if ($rozetka_overprice) {
			$product['price']  = $product['price'] + ($product['price']/100)*$rozetka_overprice;

			if (isset($product['price_old'])) {
				$product['price_old']      = $product['price_old'] + ($product['price_old']/100)*$rozetka_overprice;
			}

			if (isset($product['price_promo'])) {
				$product['price_promo']    = $product['price_promo'] + ($product['price_promo']/100)*$rozetka_overprice;
			}

			if (isset($product['price_rrc'])) {
				$product['price_rrc']    = $product['price_rrc'] + ($product['price_rrc']/100)*$rozetka_overprice;
			}

			if (isset($product['price_sale'])) {
				$product['price_sale']    = $product['price_sale'] + ($product['price_sale']/100)*$rozetka_overprice;
			}
		}

		$output .= '<price>' . number_format($this->currency->format($product['price'], 'UAH', 1, false), 0, '', '') . '</price>' . PHP_EOL;

		if (isset($product['price_old'])) {
			$output .= '<price_old>' . number_format($this->currency->format($product['price_old'], 'UAH', 1, false), 0, '', '') . '</price_old>' . PHP_EOL;
		}

		if (isset($product['price_rrc'])) {
			$output .= '<price_rrc>' . number_format($this->currency->format($product['price_rrc'], 'UAH', 1, false), 0, '', '') . '</price_rrc>' . PHP_EOL;
		}

		if (isset($product['price_sale'])) {
			$output .= '<price_sale>' . number_format($this->currency->format($product['price_sale'], 'UAH', 1, false), 0, '', '') . '</price_sale>' . PHP_EOL;
		}

		if (isset($product['price_promo'])) {
			//	$output .= '<price_promo>' . number_format($this->currency->format($product['price_promo'], 'UAH', 1, false), 0, '', '') . '</price_promo>' . PHP_EOL;
		}

		if ($product['minimum'] > 1) {
			$output .= '<sales_notes><![CDATA[Минимальное кол-во заказа: ' . (int)$product['minimum'] . ']]></sales_notes>' . PHP_EOL;				
		}

		$output .= '<stock_quantity><![CDATA[' . $product['quantity'] . ']]></stock_quantity>' . PHP_EOL;
		$output .= '<currencyId><![CDATA[' . $this->currency_code . ']]></currencyId>' . PHP_EOL;											
		$output .= '<delivery>true</delivery>' . PHP_EOL;

		foreach (array_slice($images, 0, 10, true) as $image){							
			if (file_exists(DIR_IMAGE . $image['image'])){
				$output .= '<picture><![CDATA[' . $this->model_tool_image->resize($image['image'], 
					$this->config->get($this->config->get('config_theme') . '_image_popup_width'), 
					$this->config->get($this->config->get('config_theme') . '_image_popup_height')) . ']]></picture>' . PHP_EOL;
			}
		}

		$product['description'] = '';
		if ($product['fake_description'] && mb_strlen(strip_tags($product['fake_description'])) > 10){
			$product['description'] = $product['fake_description'];
		}
		if (!$product['description'] || mb_strlen(strip_tags($product['description'])) < 10){
			$product['description'] = $product['name'];
		}
		if ($attributes){
			$product['description'] .= ' <p>' . $this->formatProductAttributesForDescription($attributes).'</p>';
		}
		if ($ocfilters){
			$product['description'] .= ' <p>' . $this->formatProductOcFiltersForDescription($ocfilters).'</p>';
		}
		$output .= '<description><![CDATA[' . $this->prepareField($product['description']) . ']]></description>' . PHP_EOL;



		$product['description_ua'] = '';
		if ($product['fake_description_ua'] && mb_strlen(strip_tags($product['fake_description_ua'])) > 10){
			$product['description_ua'] = $product['fake_description_ua'];
		}
		if (!$product['description_ua'] || mb_strlen(strip_tags($product['description_ua'])) < 10){
			$product['description_ua'] = $product['name_ua'];
		}
		if ($attributes_ua){
			$product['description_ua'] .= ' <p>' . $this->formatProductAttributesForDescription($attributes_ua).'</p>';
		}
		if ($ocfilters_ua){
			$product['description_ua'] .= ' <p>' . $this->formatProductOcFiltersForDescription($ocfilters_ua).'</p>';
		}
		$output .= '<description_ua><![CDATA[' . $this->prepareField($product['description_ua']) . ']]></description_ua>' . PHP_EOL;

		$has_options_of_type = $this->getOptionType($options);

		foreach ($attributes as $ag){
			if (!empty($ag['attribute'])){
				foreach ($ag['attribute'] as $attribute){
					$add = true;

					if (in_array($attribute['name'], $this->multi_ocfilters)){
						$exploded_value = explode(',', $attribute['text']);
						$attribute['text'] = implode('|', array_map('trim', $exploded_value));
					}

					if (in_array($attribute['name'], $this->multi_ocfilters)){
						$exploded_value = explode('/', $attribute['text']);
						$attribute['text'] = implode('|', array_map('trim', $exploded_value));
					}	

					if ($settle_color && in_array($attribute['name'], $this->exclude_ocfilters)){
						$add = false;
						$attribute['text'] = $this->multicolor_text[$this->language_code];

						if ($has_options_of_type){
							$add = false;
						}
					}

					if ($add){
						$output .= '<param name="' . $this->prepareFieldParamName($attribute['name']) . '">' . $this->addCDATA($attribute['text']) . '</param>' . PHP_EOL;					
					}
				}					
			}
		}

		foreach ($ocfilters as $ocfilter){
			$add = true;

			if (in_array($ocfilter['name'], $this->multi_ocfilters)){
				$exploded_value = explode(',', $ocfilter['value']);
				$ocfilter['value'] = implode('|', array_map('trim', $exploded_value));
			}										

			if (in_array($ocfilter['name'], $this->multi_ocfilters)){
				$exploded_value = explode('/', $ocfilter['value']);
				$ocfilter['value'] = implode('|', array_map('trim', $exploded_value));
			}

			if ($settle_color && in_array($ocfilter['name'], $this->exclude_ocfilters)){
				$add = false;
				$ocfilter['value'] = $this->multicolor_text[$this->language_code];

				if ($has_options_of_type){
					$add = false;
				}
			}

			if ($add){
				$output .= '<param name="' . $this->prepareFieldParamName($ocfilter['name']) . '">' . $this->addCDATA($ocfilter['value']) . '</param>' . PHP_EOL;						
			}
		}

		if ($settle_color){		
			if ($settle_color_as_print){
				$output .= '<param name="Принт">' . $this->addCDATA($settle_color) . '</param>' . PHP_EOL;
			} else {
				$output .= '<param name="Цвет">' . $this->addCDATA($settle_color) . '</param>' . PHP_EOL;
			}
		} elseif (!$has_options_of_type && $color = $this->getProductColor($attributes, $options, $product['name'])){
			$output .= '<param name="Цвет">' . $this->addCDATA($color) . '</param>' . PHP_EOL;
		} elseif ($has_options_of_type == 'color'){
			$output .= '<param name="Цвет">' . $this->addCDATA($this->multicolor_text[$this->language_code]) . '</param>' . PHP_EOL;
		} elseif ($has_options_of_type == 'print'){
			$output .= '<param name="Принт">' . $this->addCDATA($this->multiprint_text[$this->language_code]) . '</param>' . PHP_EOL;
		}

		if ($product['tag']){
			$tags = explode(',', $product['tag']);
				//	$output .= '<keywords><![CDATA[' . $this->prepareField(implode(',', array_map('trim', $tags))) . ']]></keywords>' . PHP_EOL;							
		}

		$output .= '</offer>' . PHP_EOL;

		return $output;
	}

}																												