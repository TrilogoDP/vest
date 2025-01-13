<?php
error_reporting(1);
ini_set('error_reporting', 1);
/*
   Epicentr XML Feed by Alexey Soloviov aka ASEN :: ionline.su // shop.ionline.su
   v1.2.2 BASED ON RXML 2.6.5 / OC 2.x
*/
class ControllerFeedEpicentrXML extends Controller {

	private $shop       = array();
	private $currencies = array();
	private $categories = array();
	private $offers     = array();
	private $from_charset = 'utf-8';
	private $curr_charset = 0;
	private $eol     = "\n";
	private $version = '1.2.2';
	private $replace_attrib;

	private $language_ru;
	private $language_ua;

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

	// private function getStatus(){
			
	// 	if (!file_exists($this->statusFile)){
	// 		$this->setStatus('idle');
	// 	}
		
	// 	$this->status = file_get_contents($this->statusFile);
		
	// }
	
	private function setStatus($status){
		file_put_contents($this->statusFile, $status);			
	}	

	public function cron() {
		
		if ($this->config->get('epicentr_xml_status')) {

		$start_timer = microtime(true);

			if (!($allowed_categories = $this->config->get('epicentr_xml_categories')) && !($allowed_manufacturers = $this->config->get('epicentr_xml_manufacturers')) ) {
					echo 'Необходима корректная настрока модуля. Если необходима помощь, обратитесь к создателю модуля';
					exit();
				}

			$this->load->model('feed/epicentr_xml');
			$this->load->model('localisation/currency');
			$this->load->model('tool/image');


			if(!$this->model_feed_epicentr_xml->check_licence()) { 
			echo 'Нет лицензии на этот домен! Обратитесь к разработчику или приобретите на <a href="https://shop.ionline.su" target="_blank">официальном сайте</a>';
			exit; }


			// Магазин
			$telephone = ($this->config->get('epicentr_xml_telephone')) ? $this->config->get('epicentr_xml_telephone') : $this->config->get('config_telephone');
			
			//$this->setShop('name',    $this->config->get('epicentr_xml_shopname'));
			//$this->setShop('company', $this->config->get('epicentr_xml_company'));
			//$this->setShop('url', HTTP_SERVER);
			//$this->setShop('phone', $telephone);
			//$this->setShop('platform', 'Opencart');
			//$this->setShop('version',  VERSION .'/'.$this->version);



 			$this->curr_charset = $this->config->get('epicentr_xml_charset');
			$offers_currency    = $this->config->get('epicentr_xml_currency');

			if (!$this->currency->has($offers_currency)) exit();

			$decimal_place = (int)$this->currency->getDecimalPlace($offers_currency);

			$shop_currency = $this->config->get('config_currency');

			$this->setCurrency($offers_currency, 1);

			$currencies = $this->model_localisation_currency->getCurrencies();

			$supported_currencies = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH');

			$currencies = array_intersect_key($currencies, array_flip($supported_currencies));

			foreach ($currencies as $currency) {
				if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
					$this->setCurrency($currency['code'], number_format(1/$this->currency->convert($currency['value'], $offers_currency, $shop_currency), 4, '.', ''));
				}
			}


			$feedtype              = $this->config->get('epicentr_xml_feedtype');  
			$allowed_categories    = $this->config->get('epicentr_xml_categories');
			$allowed_manufacturers = $this->config->get('epicentr_xml_manufacturers');
			$allowed_products      = $this->config->get('epicentr_xml_products');

			// $this->load->model('catalog/product');

			// $products = array();
				
			// $results = $this->model_catalog_product->getProducts();

			// foreach ($results as $result) {
			// 	$products[]  = $result['product_id'];
			// }
			
			// $allowed_products = implode(",", $products);

			// var_dump($allowed_products);
			// exit;

			$categories_data       = array();
			$language = ( (int)$this->config->get('epicentr_xml_language_ru') == 0) ? (int)$this->config->get('config_language_id') : $this->config->get('epicentr_xml_language_ru');

			$language_ru = $language;
			$language_ua = ( (int)$this->config->get('epicentr_xml_language_ua') == 0) ? $language_ru  : $this->config->get('epicentr_xml_language_ua');

			$this->language_ru = $language_ru;
			$this->language_ua = $language_ua;



			/////////////////
			/////// Категории
			/////////////////

			if($feedtype == 1){
				//по производителям
				$categories = $this->model_feed_epicentr_xml->getCategoryByManufacturers($allowed_manufacturers , $language); 

			} else if($feedtype == 3){
				//по товарам
				$categories = $this->model_feed_epicentr_xml->getCategoryByProducts($allowed_products, $language); 

			} else {
				//по категориям | по производителям + категориям
				$categories = $this->model_feed_epicentr_xml->getCategory($allowed_categories , $language); 
			}


			foreach ($categories as $category) {

				$category_name = $category['epicentr_name'] ? $this->my_mb_ucfirst($category['epicentr_name']) : $this->my_mb_ucfirst($category['name']);
				$coefficient[$category['category_id']]     = !empty($category['rozetka_overprice']) ? $category['rozetka_overprice'] : '' ;


				$country       = $category['manufacturer_country']   ? $category['manufacturer_country']   : $category['category_country'];
				$delivery      = $category['manufacturer_delivery']  ? $category['manufacturer_delivery']  : $category['category_delivery'];
				$guarantee     = $category['manufacturer_guarantee'] ? $category['manufacturer_guarantee'] : $category['category_guarantee'];


				$categories_data[$category['category_id']] = array(

							'name'        => trim($category_name),
							'coefficient' => $coefficient[$category['category_id']],
							'country'     => $this->my_mb_ucfirst($country),
							'delivery'    => $this->my_mb_ucfirst($delivery),
							'guarantee'   => $this->my_mb_ucfirst($guarantee)
						);

				
				$this->setCategory($category_name, $category['category_id'], $category['parent_id'], $category['epicentr_category_id']);
					 
			}
 



			////////////////////////////
			/////// Товары
			////////////////////////////

			//$in_stock_id     = $this->config->get('epicentr_xml_in_stock');  
			$_todaydate      = date('Y-m-d');
			$out_of_stock_id = $this->config->get('epicentr_xml_out_of_stock'); 
			$zero_remain     = (int)$this->config->get('epicentr_xml_zero');
			// $markup          = $this->config->get('epicentr_xml_coeff') ? (float)$this->config->get('epicentr_xml_coeff') : 1;
			$markup          = 0;
			$img_setting     = (int)$this->config->get('epicentr_xml_img');
			$img_width       =  $this->config->get('epicentr_xml_width')  ? (int)$this->config->get('epicentr_xml_width')  : 600;
			$img_height      =  $this->config->get('epicentr_xml_height') ? (int)$this->config->get('epicentr_xml_height') : 500;
			$roz_option      = (int)$this->config->get('epicentr_xml_option');
			$brand_name      = (int)$this->config->get('epicentr_xml_brand');
			$attr_change     = $this->config->get('epicentr_xml_attribchange');
			$descr_html      = (int)$this->config->get('epicentr_xml_descript');
			$show_promo      = (int)$this->config->get('epicentr_xml_promo');
			$attributes_type = (int)$this->config->get('epicentr_xml_attrib_type');
			$allowed_options = $this->config->get('epicentr_xml_options');
			$options_zero    = $this->config->get('epicentr_xml_options_null');
			$options_id_from = $this->config->get('epicentr_xml_options_id_form');

			$format = $this->config->get('epicentr_xml_format') ? $this->config->get('epicentr_xml_format') : '{name}';		
		    $find   = array( '{name}', '{brand}', '{model}', '{option}', '{sku}' );

			$this->replace_attrib  = $this->jsonToArray($this->config->get('epicentr_xml_attrib'));
			$types                 = array('select', 'checkbox', 'radio');   

			$config_country   = $this->config->get('epicentr_xml_country');
			$config_delivery  = $this->config->get('epicentr_xml_delivery');
			$config_guarantee = $this->config->get('epicentr_xml_guarantee');

			$config_images    =  $this->config->get('epicentr_xml_images'); 

			$create_cache     = false;
			$create_cache     = $this->request->get['cache'];
			if($create_cache && $create_cache != 'true') { unset($create_cache); }

			if ($this->status != 'idle'){
				$this->echoLine('now working, exit');
			//	return;
			}

		$this->echoLine('[TIME] Начали в ' . date('H:i:s'));

		$products = $this->model_feed_epicentr_xml->getProduct($allowed_categories, $allowed_products, $out_of_stock_id, $zero_remain, $allowed_manufacturers , $feedtype , $language);

		$this->echoLine('[i] Всего товаров ' . count($products));	

		$i = 1;
		foreach ($products as $product) {

			if ($i%100 == 0){
				$this->echoSimple($i . '...');
			}
			$i++;

				$data             = array();
				$mpictures        = array();
				$price            = '';
				$special          = '';


  				//$data['currencyId']     = $offers_currency;
 				// $data['id'] 			   = $product['product_id'];
				 $data['id'] 			   = (string)$product['sku'];
				//  var_dump((string)$product['sku']);

				//$data['categoryId']     = $product['category_id'];

				$category_name = $categories_data[$product['category_id']]['name']; 

				$data['category']       = mb_convert_encoding($category_name, "utf-8");


				//$data['delivery']       = ($product['shipping'] == 1);
				$data['available'] 		= ($product['quantity'] > 0); 
				$data['vendor']         = mb_convert_encoding($product['manufacturer'], "utf-8");
				// $data['vendorCode']     = $product['model'];
				$data['barcode']        = mb_convert_encoding($product['ean'], "utf-8");
				//$data['model']          = $product['name'];

				//$data['stock_quantity'] = !empty($product['epicentr_quantity'])? $product['epicentr_quantity'] : $product['quantity'];
				//$data['url'] 			= $this->url->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);
				



				// $data['weight'] = ($product['weight'] > 0) ? round(mb_convert_encoding($product['weight'], "utf-8"),3) : 500;

				// $data['length'] = ($product['length'] > 0) ? round(mb_convert_encoding($product['length'], "utf-8"),3) : 500;
				// $data['height'] = ($product['height'] > 0) ? round(mb_convert_encoding($product['height'], "utf-8"),3) : 500;
				// $data['width']  = ($product['width'] > 0)  ? round(mb_convert_encoding($product['width'], "utf-8"),3)  : 500;
				


				// $country   = !empty($categories_data[$product['category_id']]['country']) ? $categories_data[$product['category_id']]['country'] :  $config_country;
				$country   = 'Китай';
				
				if(!empty($country)) {	
					$data['country_of_origin'] = mb_convert_encoding($country, "utf-8");
				}
				
				$attribute_set = $categories_data[$product['category_id']]['name']; 
				$data['attribute_set']       = mb_convert_encoding($attribute_set, "utf-8");

				$c = (int)ceil($coefficient[$product['category_id']]);
				$c = (empty($coefficient[$product['category_id']]) || $c == 0 || $c < 0) ? $markup : (int)ceil($coefficient[$product['category_id']]);

				if ((float)$product['special']) {


					$data['price']     = number_format($this->currency->convert($this->tax->calculate($product['special'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');		
					   
					$data['price_old'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
					    

				   $special = $data['price']     = ceil($data['price'] + ($data['price'] / 100 * $c));
					$price   = $data['price_old'] = ceil($data['price_old'] + ($data['price_old'] / 100 * $c));
						

				} else {

					 $data['price'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
			 
					 $price = $data['price'] = ceil($data['price'] + ($data['price'] / 100 * $c));
						
				}


				$product_name = !empty($product['epicentr_name']) ? trim($product['epicentr_name']) : trim($product['name']);


				if($language_ua != $language_ru){


					$product_name_ua = !empty($product['epicentr_name_ua']) ? trim($product['epicentr_name_ua']) : $this->getProductUaName($product['product_id']);

					if(empty(trim($product_name_ua))){
						$product_name_ua = $product_name;
					}


				} else {
					$product_name_ua = $product_name;
				}
				
				$product_name    = mb_convert_encoding($product_name   , "utf-8");
				$product_name_ua = mb_convert_encoding($product_name_ua, "utf-8");


				$replace = array(
							'name'    => $product_name,
							'brand'   => $product['manufacturer'],
							'model'   => $product['model'],
							'option'  => $option_value,
							'sku'     => $product['sku']
						);

				$replace_ua = array(
							'name'    => $product_name_ua,
							'brand'   => $product['manufacturer'],
							'model'   => $product['model'],
							'option'  => $option_value,
							'sku'     => $product['sku']
				);

				$data['name_ru'] = trim(str_replace($find, $replace, $format));
				$data['name_ua'] = trim(str_replace($find, $replace_ua, $format)); 


				// $description = (!empty($product['epicentr_description']) && strlen($product['epicentr_description']) > 50) ? $product['epicentr_description'] : $product['description'];


				$description = (!empty($product['fake_description']) && strlen($product['fake_description']) > 50) ? $product['fake_description'] : $product['description'];


		
				if($language_ua != $language_ru){

					// $language = 1;
					// $fake = true;

					$description_ua = (!empty($product['fake_description']) && strlen($product['fake_description']) > 50) ? $this->getProductUaDescription($product['product_id']) : '';

					// var_dump($description_ua);
					// exit;

					if(empty(trim($description_ua))){
						$test = $this->model_feed_epicentr_xml->getProductDescriptionUa($product['product_id'], 3);
						$description_ua = $test;
					}

				} else {
					$description_ua = $description;
				}


				if($descr_html == 1) {

					$data['description_ru'] = '<![CDATA['.$description.']]>';
					$data['description_ua'] = '<![CDATA['.$description_ua.']]>';

				} else if($descr_html == 2) {

					$data['description_ru'] = '<![CDATA['.strip_tags($description).']]>';
					$data['description_ua'] = '<![CDATA['.strip_tags($description_ua).']]>';

				} else {

					$data['description_ru'] = '<![CDATA['.htmlspecialchars_decode($description).']]>';
					$data['description_ua'] = '<![CDATA['.htmlspecialchars_decode($description_ua).']]>';
				}
				
				if ($product['image']) {

					$pictures = $this->model_feed_epicentr_xml->getProductImages($product['product_id'], $config_images);

			
						if($img_setting == 1){
							array_push($mpictures, HTTP_SERVER. 'image/'.$product['image']);
						} else {
							array_push($mpictures, $this->model_tool_image->resize($product['image'], $img_width, $img_height));
						}
						

						if(!empty($pictures)){

							foreach($pictures as $picture){

							if($picture['image']){
								if($img_setting == 1){
									array_push($mpictures, HTTP_SERVER . 'image/'.$picture['image']);
								} else {
									array_push($mpictures, $this->model_tool_image->resize($picture['image'], $img_width, $img_height));
								}
							}
								
							}	
						}

					$data['picture'] = $mpictures;	
				}		


				$params = $this->model_feed_epicentr_xml->getProductAttributes($product['product_id'], $attributes_type, $language);
				$params = ($attr_change) ? $this->paramReplace($params) : $params;
				$data['param'] = $params;

				
				///// OPTIONS ////

			
				if($roz_option == 1) {

				$options    = $this->model_feed_epicentr_xml->getProductOptions($product['product_id'], $allowed_options, $types, $options_zero);
				
				// var_dump(count($options));
				$options = array_diff($options, array_diff_assoc($options, array_unique($options)));
				// print_r($result);
	
				if(!empty($options)){					

					foreach($options as $option){

					foreach($option['product_option_value'] as $po){

			
							$data['available']      = ($po['quantity'] > 0);
							//$data['stock_quantity'] = $po['quantity'];

							// $data['id'] = ($options_id_from == 0) ? $product['product_id'].$po['product_option_value_id'] : $product['product_id'].$po['option_value_id'];
							$data['id'] = (string)$po['sku'];

							$prefix = '';
							if (count($exploded = explode('-', $product['model'])) == 2) {										
								if (strpos($product['sku'], $exploded[1]) === 0) {
									$prefix = $exploded[1];
									$data['id'] = $prefix . (string)$po['sku'];
								}
							}


							//выполняем пересортировку только если эта опция закреплена за каким-то изображением
							//чтоб лишний раз не жрать ресурс процессора 
					
							if(array_search($po['product_option_value_id'], array_column($pictures, 'option')) !== false) {



							$pictures = $this->sortImages($pictures, $po['product_option_value_id'], $product['image']);

							if(!empty($pictures)){

								$mpictures        = array();

								foreach($pictures as $picture){

									if($img_setting == 1){
										array_push($mpictures, HTTP_SERVER . 'image/'.$picture['image']);
									} else {
										array_push($mpictures, $this->model_tool_image->resize($picture['image'], $img_width, $img_height));
									}
									break;
								}	
							}
					}


					$pictures = $this->model_feed_epicentr_xml->getProductImages($product['product_id'], $config_images);

					$mpictures        = array();

					if(!empty($pictures)){

						foreach($pictures as $picture){

							if($picture['image']){
								if($img_setting == 1){
									array_push($mpictures, HTTP_SERVER . 'image/'.$picture['image']);
								} else {
									array_push($mpictures, $this->model_tool_image->resize($picture['image'], $img_width, $img_height));
								}
							}
								
						}
					}

							if (isset($po['o_v_image']) && ($this->model_tool_image->resize($po['o_v_image'], $img_width, $img_height)) != $mpictures[0]) {
								array_unshift($mpictures, $this->model_tool_image->resize($po['o_v_image'], $img_width, $img_height));
							}

							// var_dump($po_key);
							// if ($po_key > 0) {
							// 	for ($i = 1; $i <= $po_key; $i++) {
							// 		unset($mpictures[$i]);
							// 		var_dump($i);
							// 	}
							// }

							$data['picture'] = $mpictures;
							// echo '<pre>';
							// var_dump($mpictures);
							// echo '</pre>';
							// // exit;

							$po['name_ua'] = $this->getOptionUaName($po);

							$replace = array(
										'name'    => $product_name,
										'brand'   => $product['manufacturer'],
										'model'   => $product['model'],
										'option'  => $po['name'],
										'sku'     => $product['sku']
									);

							$replace_ua = array(
										'name'    => $product_name_ua,
										'brand'   => $product['manufacturer'],
										'model'   => $product['model'],
										'option'  => $po['name_ua'],
										'sku'     => $product['sku']
							);

							$data['name_ru'] = trim(str_replace($find, $replace, $format));
							$data['name_ua'] = trim(str_replace($find, $replace_ua, $format)); 

							// $data['vendorCode'] = $product['model'] . $po['sku'];

 							$data['param'] = '';
							$data['param'] = $params;
 							$data['param']['op-'.mt_rand(9,99)][] = array('attribute_id' => mt_rand(1,9), 
 																	 'name' => $option['name'], 
 																	 'text' => $po['name']);



							///// NEW PRICE ////


							if($po['price_prefix'] == '='){

					
								if(!empty($special)){

									$data['price']     = $special;
									$data['price_old'] = $po['price'];

								} else {

									$data['price'] =  $po['price'];

								}


							} else if($po['price_prefix'] == '+'){


								if(!empty($special)){

									$data['price']     =  ceil($special + $po['price']);
									$data['price_old'] =  ceil($price   + $po['price']);

								} else {

									$data['price'] = ceil($price + $po['price']);

								}


							} else if($po['price_prefix'] == '-') {


								if(!empty($special)){

									$data['price']     =  ceil($special - $po['price']);
									$data['price_old'] =  ceil($price   - $po['price']);

								} else {

									$data['price'] = ceil($price - $po['price']);

								}

							}


							$data['price']     = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');


							if($data['price_old']) {
								$data['price_old'] = number_format($this->currency->convert($this->tax->calculate($data['price_old'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
							}


 							////////////////
		
							$this->setOffer($data);
				
				 
					}

				}

				} else { $this->setOffer($data); }
				} else { $this->setOffer($data); }

				//////// END OF OPTIONS ///////

 

			}
			//$this->response->addHeader('Content-Type: application/xml');
			//$this->response->setOutput($this->getYml());


			$this->response->addHeader('Content-Type: application/xml');

			$file = DIR_FEEDS . 'epicentr.xml';

			$FEED = $this->getYml();
				
			$this->echoLine('[i], файл ' . $file);

			$this->echoLine('');
				
			$handle = fopen($file, 'w+');
			flock($handle, LOCK_EX);
			fwrite($handle, $FEED);
			flock($handle, LOCK_UN);
			fclose($handle);
			
			unset($FEED);
			unset($products);
			gc_collect_cycles();
			$this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));

			// $FEED = $this->getYml();

			// if($create_cache){

				// $cache_filename = 'epicentr';

				// file_put_contents('/home/vest/web/vest.in.ua/public_html/feeds/'.$cache_filename.'.xml', $FEED);
				// echo 'Фид был успешно закеширован в файле '.$cache_filename.'.xml<br>';
				// echo 'Время выполнения:' . round(microtime(true) - $start_timer, 4).' сек<br>';
				// echo 'Товаров в фиде:' . (int)count($this->offers).'<br>';
				// exit;
			// }

			// $this->response->setOutput($FEED);

		}

		$this->echoLine('[TIME] Закончили в ' . date('H:i:s'));
			
		$this->setStatus('idle');
	}

	public function index() {
		
		if ($this->config->get('epicentr_xml_status')) {

		$start_timer = microtime(true);

			if (!($allowed_categories = $this->config->get('epicentr_xml_categories')) && !($allowed_manufacturers = $this->config->get('epicentr_xml_manufacturers')) ) {
					echo 'Необходима корректная настрока модуля. Если необходима помощь, обратитесь к создателю модуля';
					exit();
				}

			$this->load->model('feed/epicentr_xml');
			$this->load->model('localisation/currency');
			$this->load->model('tool/image');


			if(!$this->model_feed_epicentr_xml->check_licence()) { 
			echo 'Нет лицензии на этот домен! Обратитесь к разработчику или приобретите на <a href="https://shop.ionline.su" target="_blank">официальном сайте</a>';
			exit; }


			// Магазин
			$telephone = ($this->config->get('epicentr_xml_telephone')) ? $this->config->get('epicentr_xml_telephone') : $this->config->get('config_telephone');
			
			//$this->setShop('name',    $this->config->get('epicentr_xml_shopname'));
			//$this->setShop('company', $this->config->get('epicentr_xml_company'));
			//$this->setShop('url', HTTP_SERVER);
			//$this->setShop('phone', $telephone);
			//$this->setShop('platform', 'Opencart');
			//$this->setShop('version',  VERSION .'/'.$this->version);



 			$this->curr_charset = $this->config->get('epicentr_xml_charset');
			$offers_currency    = $this->config->get('epicentr_xml_currency');

			if (!$this->currency->has($offers_currency)) exit();

			$decimal_place = (int)$this->currency->getDecimalPlace($offers_currency);

			$shop_currency = $this->config->get('config_currency');

			$this->setCurrency($offers_currency, 1);

			$currencies = $this->model_localisation_currency->getCurrencies();

			$supported_currencies = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH');

			$currencies = array_intersect_key($currencies, array_flip($supported_currencies));

			foreach ($currencies as $currency) {
				if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
					$this->setCurrency($currency['code'], number_format(1/$this->currency->convert($currency['value'], $offers_currency, $shop_currency), 4, '.', ''));
				}
			}


			$feedtype              = $this->config->get('epicentr_xml_feedtype');  
			$allowed_categories    = $this->config->get('epicentr_xml_categories');
			$allowed_manufacturers = $this->config->get('epicentr_xml_manufacturers');
			$allowed_products      = $this->config->get('epicentr_xml_products');

			// $this->load->model('catalog/product');

			// $products = array();
				
			// $results = $this->model_catalog_product->getProducts();

			// foreach ($results as $result) {
			// 	$products[]  = $result['product_id'];
			// }
			
			// $allowed_products = implode(",", $products);

			// var_dump($allowed_products);
			// exit;

			$categories_data       = array();
			$language = ( (int)$this->config->get('epicentr_xml_language_ru') == 0) ? (int)$this->config->get('config_language_id') : $this->config->get('epicentr_xml_language_ru');

			$language_ru = $language;
			$language_ua = ( (int)$this->config->get('epicentr_xml_language_ua') == 0) ? $language_ru  : $this->config->get('epicentr_xml_language_ua');

			$this->language_ru = $language_ru;
			$this->language_ua = $language_ua;



			/////////////////
			/////// Категории
			/////////////////

			if($feedtype == 1){
				//по производителям
				$categories = $this->model_feed_epicentr_xml->getCategoryByManufacturers($allowed_manufacturers , $language); 

			} else if($feedtype == 3){
				//по товарам
				$categories = $this->model_feed_epicentr_xml->getCategoryByProducts($allowed_products, $language); 

			} else {
				//по категориям | по производителям + категориям
				$categories = $this->model_feed_epicentr_xml->getCategory($allowed_categories , $language); 
			}


			foreach ($categories as $category) {

				$category_name = $category['epicentr_name'] ? $this->my_mb_ucfirst($category['epicentr_name']) : $this->my_mb_ucfirst($category['name']);
				$coefficient[$category['category_id']]     = !empty($category['rozetka_overprice']) ? $category['rozetka_overprice'] : '' ;


				$country       = $category['manufacturer_country']   ? $category['manufacturer_country']   : $category['category_country'];
				$delivery      = $category['manufacturer_delivery']  ? $category['manufacturer_delivery']  : $category['category_delivery'];
				$guarantee     = $category['manufacturer_guarantee'] ? $category['manufacturer_guarantee'] : $category['category_guarantee'];


				$categories_data[$category['category_id']] = array(

							'name'        => trim($category_name),
							'coefficient' => $coefficient[$category['category_id']],
							'country'     => $this->my_mb_ucfirst($country),
							'delivery'    => $this->my_mb_ucfirst($delivery),
							'guarantee'   => $this->my_mb_ucfirst($guarantee)
						);

				
				$this->setCategory($category_name, $category['category_id'], $category['parent_id'], $category['epicentr_category_id']);
					 
			}
 



			////////////////////////////
			/////// Товары
			////////////////////////////

			//$in_stock_id     = $this->config->get('epicentr_xml_in_stock');  
			$_todaydate      = date('Y-m-d');
			$out_of_stock_id = $this->config->get('epicentr_xml_out_of_stock'); 
			$zero_remain     = (int)$this->config->get('epicentr_xml_zero');
			// $markup          = $this->config->get('epicentr_xml_coeff') ? (float)$this->config->get('epicentr_xml_coeff') : 1;
			$markup          = 0;
			$img_setting     = (int)$this->config->get('epicentr_xml_img');
			$img_width       =  $this->config->get('epicentr_xml_width')  ? (int)$this->config->get('epicentr_xml_width')  : 600;
			$img_height      =  $this->config->get('epicentr_xml_height') ? (int)$this->config->get('epicentr_xml_height') : 500;
			$roz_option      = (int)$this->config->get('epicentr_xml_option');
			$brand_name      = (int)$this->config->get('epicentr_xml_brand');
			$attr_change     = $this->config->get('epicentr_xml_attribchange');
			$descr_html      = (int)$this->config->get('epicentr_xml_descript');
			$show_promo      = (int)$this->config->get('epicentr_xml_promo');
			$attributes_type = (int)$this->config->get('epicentr_xml_attrib_type');
			$allowed_options = $this->config->get('epicentr_xml_options');
			$options_zero    = $this->config->get('epicentr_xml_options_null');
			$options_id_from = $this->config->get('epicentr_xml_options_id_form');

			$format = $this->config->get('epicentr_xml_format') ? $this->config->get('epicentr_xml_format') : '{name}';		
		    $find   = array( '{name}', '{brand}', '{model}', '{option}', '{sku}' );

			$this->replace_attrib  = $this->jsonToArray($this->config->get('epicentr_xml_attrib'));
			$types                 = array('select', 'checkbox', 'radio');   

			$config_country   = $this->config->get('epicentr_xml_country');
			$config_delivery  = $this->config->get('epicentr_xml_delivery');
			$config_guarantee = $this->config->get('epicentr_xml_guarantee');

			$config_images    =  $this->config->get('epicentr_xml_images'); 

			$create_cache     = false;
			$create_cache     = $this->request->get['cache'];
			if($create_cache && $create_cache != 'true') { unset($create_cache); }


			$products = $this->model_feed_epicentr_xml->getProduct($allowed_categories, $allowed_products, $out_of_stock_id, $zero_remain, $allowed_manufacturers , $feedtype , $language);


			foreach ($products as $product) {


				$data             = array();
				$mpictures        = array();
				$price            = '';
				$special          = '';
 

 				//$data['currencyId']     = $offers_currency;
 				// $data['id'] 			   = $product['product_id'];
				 $data['id'] 			   = (string)$product['sku'];
				//  var_dump((string)$product['sku']);

				//$data['categoryId']     = $product['category_id'];

				$category_name = $categories_data[$product['category_id']]['name']; 

				$data['category']       = mb_convert_encoding($category_name, "utf-8");


				//$data['delivery']       = ($product['shipping'] == 1);
				$data['available'] 		= ($product['quantity'] > 0); 
				$data['vendor']         = mb_convert_encoding($product['manufacturer'], "utf-8");
				// $data['vendorCode']     = $product['model'];
				$data['barcode']        = mb_convert_encoding($product['ean'], "utf-8");
				//$data['model']          = $product['name'];

				//$data['stock_quantity'] = !empty($product['epicentr_quantity'])? $product['epicentr_quantity'] : $product['quantity'];
				//$data['url'] 			= $this->url->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);
				



				// $data['weight'] = ($product['weight'] > 0) ? round(mb_convert_encoding($product['weight'], "utf-8"),3) : 500;

				// $data['length'] = ($product['length'] > 0) ? round(mb_convert_encoding($product['length'], "utf-8"),3) : 500;
				// $data['height'] = ($product['height'] > 0) ? round(mb_convert_encoding($product['height'], "utf-8"),3) : 500;
				// $data['width']  = ($product['width'] > 0)  ? round(mb_convert_encoding($product['width'], "utf-8"),3)  : 500;
				


				// $country   = !empty($categories_data[$product['category_id']]['country']) ? $categories_data[$product['category_id']]['country'] :  $config_country;
				$country   = 'Китай';
				
				if(!empty($country)) {	
					$data['country_of_origin'] = mb_convert_encoding($country, "utf-8");
				}
				
				$attribute_set = $categories_data[$product['category_id']]['name']; 
				$data['attribute_set']       = mb_convert_encoding($attribute_set, "utf-8");

				$c = (int)ceil($coefficient[$product['category_id']]);
				$c = (empty($coefficient[$product['category_id']]) || $c == 0 || $c < 0) ? $markup : (int)ceil($coefficient[$product['category_id']]);

				if ((float)$product['special']) {


					$data['price']     = number_format($this->currency->convert($this->tax->calculate($product['special'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');		
					   
					$data['price_old'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
					    

				   $special = $data['price']     = ceil($data['price'] + ($data['price'] / 100 * $c));
					$price   = $data['price_old'] = ceil($data['price_old'] + ($data['price_old'] / 100 * $c));
						

				} else {

					 $data['price'] = number_format($this->currency->convert($this->tax->calculate($product['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
			 
					 $price = $data['price'] = ceil($data['price'] + ($data['price'] / 100 * $c));
						
				}


				$product_name = !empty($product['epicentr_name']) ? trim($product['epicentr_name']) : trim($product['name']);


				if($language_ua != $language_ru){


					$product_name_ua = !empty($product['epicentr_name_ua']) ? trim($product['epicentr_name_ua']) : $this->getProductUaName($product['product_id']);

					if(empty(trim($product_name_ua))){
						$product_name_ua = $product_name;
					}


				} else {
					$product_name_ua = $product_name;
				}
				
				$product_name    = mb_convert_encoding($product_name   , "utf-8");
				$product_name_ua = mb_convert_encoding($product_name_ua, "utf-8");


				$replace = array(
							'name'    => $product_name,
							'brand'   => $product['manufacturer'],
							'model'   => $product['model'],
							'option'  => $option_value,
							'sku'     => $product['sku']
						);

				$replace_ua = array(
							'name'    => $product_name_ua,
							'brand'   => $product['manufacturer'],
							'model'   => $product['model'],
							'option'  => $option_value,
							'sku'     => $product['sku']
				);

				$data['name_ru'] = trim(str_replace($find, $replace, $format));
				$data['name_ua'] = trim(str_replace($find, $replace_ua, $format)); 


				// $description = (!empty($product['epicentr_description']) && strlen($product['epicentr_description']) > 50) ? $product['epicentr_description'] : $product['description'];


				$description = (!empty($product['fake_description']) && strlen($product['fake_description']) > 50) ? $product['fake_description'] : $product['description'];


		
				if($language_ua != $language_ru){

					// $language = 1;
					// $fake = true;

					$description_ua = (!empty($product['fake_description']) && strlen($product['fake_description']) > 50) ? $this->getProductUaDescription($product['product_id']) : '';

					// var_dump($description_ua);
					// exit;

					if(empty(trim($description_ua))){
						$test = $this->model_feed_epicentr_xml->getProductDescriptionUa($product['product_id'], 3);
						$description_ua = $test;
					}

				} else {
					$description_ua = $description;
				}


				if($descr_html == 1) {

					$data['description_ru'] = '<![CDATA['.$description.']]>';
					$data['description_ua'] = '<![CDATA['.$description_ua.']]>';

				} else if($descr_html == 2) {

					$data['description_ru'] = '<![CDATA['.strip_tags($description).']]>';
					$data['description_ua'] = '<![CDATA['.strip_tags($description_ua).']]>';

				} else {

					$data['description_ru'] = '<![CDATA['.htmlspecialchars_decode($description).']]>';
					$data['description_ua'] = '<![CDATA['.htmlspecialchars_decode($description_ua).']]>';
				}
				
				if ($product['image']) {

					$pictures = $this->model_feed_epicentr_xml->getProductImages($product['product_id'], $config_images);

			
						if($img_setting == 1){
							array_push($mpictures, HTTP_SERVER. 'image/'.$product['image']);
						} else {
							array_push($mpictures, $this->model_tool_image->resize($product['image'], $img_width, $img_height));
						}
						

						if(!empty($pictures)){

							foreach($pictures as $picture){

							if($picture['image']){
								if($img_setting == 1){
									array_push($mpictures, HTTP_SERVER . 'image/'.$picture['image']);
								} else {
									array_push($mpictures, $this->model_tool_image->resize($picture['image'], $img_width, $img_height));
								}
							}
								
							}	
						}

					$data['picture'] = $mpictures;	
				}		


				$params = $this->model_feed_epicentr_xml->getProductAttributes($product['product_id'], $attributes_type, $language);
				$params = ($attr_change) ? $this->paramReplace($params) : $params;
				$data['param'] = $params;

				
				///// OPTIONS ////

			
				if($roz_option == 1) {

				$options    = $this->model_feed_epicentr_xml->getProductOptions($product['product_id'], $allowed_options, $types, $options_zero);
	
				if(!empty($options)){					

					foreach($options as $option){

					foreach($option['product_option_value'] as $po){

			
						$data['available']      = ($po['quantity'] > 0);
						//$data['stock_quantity'] = $po['quantity'];

						// $data['id'] = ($options_id_from == 0) ? $product['product_id'].$po['product_option_value_id'] : $product['product_id'].$po['option_value_id'];
						$data['id'] = (string)$po['sku'];

						$prefix = '';
						if (count($exploded = explode('-', $product['model'])) == 2) {										
							if (strpos($product['sku'], $exploded[1]) === 0) {
								$prefix = $exploded[1];
								$data['id'] = $prefix . (string)$po['sku'];
							}
						}


					//выполняем пересортировку только если эта опция закреплена за каким-то изображением
					//чтоб лишний раз не жрать ресурс процессора 
			 
					if(array_search($po['product_option_value_id'], array_column($pictures, 'option')) !== false) {



							$pictures = $this->sortImages($pictures, $po['product_option_value_id'], $product['image']);

							if(!empty($pictures)){

								$mpictures        = array();

								foreach($pictures as $picture){

									if($img_setting == 1){
										array_push($mpictures, HTTP_SERVER . 'image/'.$picture['image']);
									} else {
										array_push($mpictures, $this->model_tool_image->resize($picture['image'], $img_width, $img_height));
									}
									break;
								}	
							}
					}


					$pictures = $this->model_feed_epicentr_xml->getProductImages($product['product_id'], $config_images);

					$mpictures        = array();

					if(!empty($pictures)){

						foreach($pictures as $picture){

							if($picture['image']){
								if($img_setting == 1){
									array_push($mpictures, HTTP_SERVER . 'image/'.$picture['image']);
								} else {
									array_push($mpictures, $this->model_tool_image->resize($picture['image'], $img_width, $img_height));
								}
							}
								
						}
					}

							if (isset($po['o_v_image']) && ($this->model_tool_image->resize($po['o_v_image'], $img_width, $img_height)) != $mpictures[0]) {
								array_unshift($mpictures, $this->model_tool_image->resize($po['o_v_image'], $img_width, $img_height));
							}

							// var_dump($po_key);
							// if ($po_key > 0) {
							// 	for ($i = 1; $i <= $po_key; $i++) {
							// 		unset($mpictures[$i]);
							// 		var_dump($i);
							// 	}
							// }

							$data['picture'] = $mpictures;
							// echo '<pre>';
							// var_dump($mpictures);
							// echo '</pre>';
							// // exit;

							$po['name_ua'] = $this->getOptionUaName($po);

							$replace = array(
										'name'    => $product_name,
										'brand'   => $product['manufacturer'],
										'model'   => $product['model'],
										'option'  => $po['name'],
										'sku'     => $product['sku']
									);

							$replace_ua = array(
										'name'    => $product_name_ua,
										'brand'   => $product['manufacturer'],
										'model'   => $product['model'],
										'option'  => $po['name_ua'],
										'sku'     => $product['sku']
							);

							$data['name_ru'] = trim(str_replace($find, $replace, $format));
							$data['name_ua'] = trim(str_replace($find, $replace_ua, $format)); 

							// $data['vendorCode'] = $product['model'] . $po['sku'];

 							$data['param'] = '';
							$data['param'] = $params;
 							$data['param']['op-'.mt_rand(9,99)][] = array('attribute_id' => mt_rand(1,9), 
 																	 'name' => $option['name'], 
 																	 'text' => $po['name']);



							///// NEW PRICE ////


							if($po['price_prefix'] == '='){

					
								if(!empty($special)){

									$data['price']     = $special;
									$data['price_old'] = $po['price'];

								} else {

									$data['price'] =  $po['price'];

								}


							} else if($po['price_prefix'] == '+'){


								if(!empty($special)){

									$data['price']     =  ceil($special + $po['price']);
									$data['price_old'] =  ceil($price   + $po['price']);

								} else {

									$data['price'] = ceil($price + $po['price']);

								}


							} else if($po['price_prefix'] == '-') {


								if(!empty($special)){

									$data['price']     =  ceil($special - $po['price']);
									$data['price_old'] =  ceil($price   - $po['price']);

								} else {

									$data['price'] = ceil($price - $po['price']);

								}

							}


							$data['price']     = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');


							if($data['price_old']) {
								$data['price_old'] = number_format($this->currency->convert($this->tax->calculate($data['price_old'], $product['tax_class_id']), $shop_currency, $offers_currency), $decimal_place, '.', '');
							}


 							////////////////
		
							$this->setOffer($data);
				
				 
					}

				}

				} else { $this->setOffer($data); }
				} else { $this->setOffer($data); }

				//////// END OF OPTIONS ///////

 

			}
			//$this->response->addHeader('Content-Type: application/xml');
			//$this->response->setOutput($this->getYml());


			$this->response->addHeader('Content-Type: application/xml');

			$FEED = $this->getYml();

			// if($create_cache){

				$cache_filename = 'epicentr';

				file_put_contents('/home/vest/web/vest.in.ua/public_html/feeds/'.$cache_filename.'.xml', $FEED);
				echo 'Фид был успешно закеширован в файле '.$cache_filename.'.xml<br>';
				echo 'Время выполнения:' . round(microtime(true) - $start_timer, 4).' сек<br>';
				echo 'Товаров в фиде:' . (int)count($this->offers).'<br>';
				exit;
			// }

			// $this->response->setOutput($FEED);

		}
	}





	private function setShop($name, $value) {
		$allowed = array('name', 'company', 'url', 'phone', 'platform', 'version', 'agency', 'email');
		if (in_array($name, $allowed)) {
			$this->shop[$name] = $this->prepareField($value);
		}
	}

	private function setCurrency($id, $rate = 'CBRF', $plus = 0) {

		$allow_id = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAH');
		if (!in_array($id, $allow_id)) {
			return false;
		}
		$allow_rate = array('CBRF', 'NBU', 'NBK', 'CB');
		if (in_array($rate, $allow_rate)) {
			$plus = str_replace(',', '.', $plus);
			if (is_numeric($plus) && $plus > 0) {
				$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate,
					'plus'=>(float)$plus
				);
			} else {
				$this->currencies[] = array(
					'id'=>$this->prepareField(strtoupper($id)),
					'rate'=>$rate
				);
			}
		} else {
			$rate = str_replace(',', '.', $rate);
			if (!(is_numeric($rate) && $rate > 0)) {
				return false;
			}
			$this->currencies[] = array(
				'id'=>$this->prepareField(strtoupper($id)),
				'rate'=>(float)$rate
			);
		}

		return true;
	}



	private function setCategory($name, $id, $parent_id = 0, $epicentr_id) {

		$id = (int)$id;
		
		if ($id < 1 || trim($name) == '') {
			return false;
		}

		$this->categories[$id] = array(
						'id'   => $id,
						'name' => $this->prepareField($name)
					);

		if ((int)$parent_id > 0) {

			$this->categories[$id]['parentId']= (int)$parent_id;		
		} 

		if ((int)$epicentr_id > 0) {

			$this->categories[$id]['rz_id']= (int)$epicentr_id;		
		} 

		
		return true;
	}



	private function setOffer($data) {
		$offer = array();

		$attributes = array('id', 'type', 'available', 'bid', 'cbid', 'param', 'picture', 'product_delivery');
		$attributes = array_intersect_key($data, array_flip($attributes));
		foreach ($attributes as $key => $value) {
			switch ($key)
			{
				case 'id':
				case 'bid':
				case 'cbid':
					// $value = (int)$value;
					$value = (string)$value;
					if (isset($value)) {
						$offer[$key] = $value;
					}
					break;

				case 'type':

					break;

				case 'available':
					$offer['available'] = ($value ? 'true' : 'false');
					break;

				case 'param':
					if (is_array($value)) {
						$offer['param'] = $value;
					}
					break;
				case 'picture':
					if (is_array($value)) {
						$offer['picture'] = $value;
					}
					break;
				default:
					break;
			}
		}


		$allowed_tags = array(  
							  		  'price'    =>  1, 
							 		  'price_old'=>  2,
							 		  'category' =>  3,  
									  'vendor'   =>  5, 

									   
									
									  'name_ru'  =>  6, 
									  'name_ua'  =>  7, 
									  
									  'description_ru'=>8, 
									  'description_ua'=>9, 

									  								
									  'weight'   =>  10,
									  'width'    =>  11,
									  'length'   =>  12,
									  'height'   =>  13,

									  'barcode'  =>  14,									   

									  'country_of_origin' => 1,

									  'attribute_set'   =>  15,
 
							);



		$required_tags = array_filter($allowed_tags);

		if (sizeof(array_intersect_key($data, $required_tags)) != sizeof($required_tags)) {
			//return;
		}

		$data = array_intersect_key($data, $allowed_tags);
		$allowed_tags = array_intersect_key($allowed_tags, $data);
		$offer['data'] = array();

		foreach ($allowed_tags as $key => $value) {
			$offer['data'][$key] = ($key != 'description_ru' && $key != 'description_ua') ? $this->prepareField($data[$key]) : $this->prepareDesc($data[$key]);
		}

		$this->offers[] = $offer;
	}

	/**
	 * Формирование YML файла
	 *
	 * @return string
	 */
	private function getYml() {

		if($this->curr_charset){
			$yml  = '<?xml version="1.0" encoding="utf-8"?>' . $this->eol;
		} else {
			$yml  = '<?xml version="1.0" encoding="windows-1251"?>' . $this->eol;
		}
		
		$yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol;
		$yml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol;
		//$yml .= '<shop>' . $this->eol;
		// информация о магазине
		//$yml .= $this->array2Tag($this->shop);

		// валюты
	/*	 
		$yml .= '<currencies>' . $this->eol;
		foreach ($this->currencies as $currency) {
			$yml .= $this->getElement($currency, 'currency');
		}
		$yml .= '</currencies>' . $this->eol;

		// категории
		$yml .= '<categories>' . $this->eol;
		foreach ($this->categories as $category) {

			$category_name =  $category['name'];

			unset($category['name'], $category['export']);

			$yml .= $this->getElement($category, 'category', $category_name);
		}
		$yml .= '</categories>' . $this->eol;
	*/
	 

		// товарные предложения
		$yml .= '<offers>' . $this->eol;


	
		foreach ($this->offers as $offer) {

			if ($offer['picture']){


				if (isset($offer['picture'])) {
					$pics = $this->array2Picture($offer['picture']);
					unset($offer['picture']);
				}


				$tags = $this->array2Tag($offer['data'] , $pics);
				unset($offer['data']);


				if (isset($offer['param'])) {
					$tags .= $this->array2Param($offer['param']);
					unset($offer['param']);
				}



				$yml .= $this->getElement($offer, 'offer', $tags);
			}
		}

		
		$yml .= '</offers>' . $this->eol;

		//$yml .= '</shop>';
		$yml .= '</yml_catalog>';

		return $yml;
	}

	/**
	 * Фрмирование элемента
	 *
	 * @param array $attributes
	 * @param string $element_name
	 * @param string $element_value
	 * @return string
	 */
	private function getElement($attributes, $element_name, $element_value = '') {
		$retval = '<' . $element_name . ' ';
		foreach ($attributes as $key => $value) {
			$retval .= $key . '="' . $value . '" ';
		}
		$retval .= $element_value ? '>' . $this->eol . $element_value . '</' . $element_name . '>' : '/>';
		$retval .= $this->eol;

		return $retval;
	}

	/**
	 * Преобразование массива в теги
	 *
	 * @param array $tags
	 * @return string
	 */
	private function array2Tag($tags , $pictures) {
		$retval = '';
		foreach ($tags as $key => $value) {


			if($key == 'name_ru' || $key == 'name_ua'){

				$retval .= '<name lang="'.str_replace('name_', '', $key) .'">' . $this->prepareField($value) . '</name>' . $this->eol;
				
			} else if($key == 'description_ru' || $key == 'description_ua'){

				$retval .= '<description lang="'.str_replace('description_', '', $key) .'">' . $value . '</description>' . $this->eol;

			} else {
				
			 	$retval .= '<' . $key . '>' . $this->prepareField($value) . '</' . $key . '>' . $this->eol;
			}


			if($key == 'category') {

				$retval .= $pictures . $this->eol;
			

			}


			



		}

		return $retval;
	}



	private function array2Param($params) {
		$retval = '';

		$attr = array();
		// $retval .= '<param paramcode="measure" name="Одиниця виміру та кількість" valuecode="measure_pcs"><![CDATA[шт.]]></param>' . $this->eol;
		// $retval .= '<param paramcode="ratio" name="Мінімальна кратність товару"><![CDATA[1.0]]></param>' . $this->eol;		
		$retval .= '<param name="Міра виміру" paramcode="measure" valuecode="measure_pcs">шт.</param>' . $this->eol;
		$retval .= '<param name="Мінімальна кратність товару" paramcode="ratio">1</param>' . $this->eol;
		// foreach ($params as $param) {

		// 	foreach ($param as $attr) {

		// 		if(!empty($attr['name'])) {

		// 			$retval .= '<param name="' . $this->prepareField($attr['name']).'">'. $this->prepareField($attr['text']) . '</param>' . $this->eol;
		// 		}
		// 	}

		// }


		return $retval;
	}
	
	private function array2Picture($params) {
		$retval = '';
		$retval .= '<picture>';
		foreach ($params as $i=> $param) {
			if($i+1<count($params)) { 
				$retval .= $this->prepareField($param) . ', ';
			} else { 
				$retval .= $this->prepareField($param);
			}
		}
		$retval .= '</picture>'. $this->eol;
		return $retval;
	}




	private function paramReplace($params){

		$replace = $this->replace_attrib;

		$result  = $this->recursive_array_replace($params, $replace);

		return $result;
	}




	public function recursive_array_replace($params, $replace) {

		foreach ($replace as $key_g => $value_g) {

			foreach ($value_g as $key_a => $value_a) {
		

				if($value_a['active'] == 1) {  

					if(is_array($params[$key_g][$key_a])) {

						if($value_a['status'] == 1){  

							$params[$key_g][$key_a]['name'] = $value_a['name'];

						} 	
					}

				} else {

					$params[$key_g][$key_a]['name'] = '';
					$params[$key_g][$key_a]['text'] = '';
				}

			}
			
		}


	    return $params;
	}



	private function jsonToArray($data){

		$data = (array)json_decode($data);
		$result = array();

		foreach ($data as $key_g => $value_g) {

			$value_g = (array)$value_g;

			foreach ($value_g as $key_a => $value_a) {

				$result[$key_g][$key_a] =  (array)$value_a;
			}
			
		}

		return $result;
	}


	public function sortImages($images, $option, $main_image){

		//совместили все фото в один массив
		array_push($images, array('image'=>$main_image, 'option'=>-1));

		$new_main  = array();
		$new_other = array();

		foreach ($images as $key => $image) {
			if($image['option'] == $option) {
				$new_main[] = $image;
			} else {
				$new_other[] = $image;
			}
		}

		//объединяем массывы с фото выставляя новое главное фото в начало 
		//отсортируем массив с доп фото чтоб бывшее гавное фото стало 2м 
		//в случае ошибки выявления нового главного фото - останется старое главное
		usort($new_other, function($a, $b){
		    return ($a['price'] - $b['price']);
		});

		$images = array_merge($new_main, $new_other);

		return $images;
	}


	private function prepareField($field) {


		$n = substr_count($field, "\n");

		if($n > 0) {
			$field = str_replace("\n" , '[br]' , $field);
		}

		$field = htmlspecialchars_decode($field);
		$field = strip_tags($field);

		$from = array('"', '&', '>', '<', '\'', '[br]');
		$to   = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;', '&lt;br&gt;');
		$field = str_replace($from, $to, $field);

		if ($this->from_charset != 'windows-1251' && $this->curr_charset == 0) {
			$field = iconv($this->from_charset, 'windows-1251//TRANSLIT//IGNORE', $field);
		}

		$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}

	private function prepareDesc($field) {
	//	$field = htmlspecialchars_decode($field);
		//$field = strip_tags($field);
	//	$from = array('"', '&', '>', '<', '\'');
	//	$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
	//	$field = str_replace($from, $to, $field);
		if ($this->from_charset != 'windows-1251' && $this->curr_charset == 0) {
			$field = iconv($this->from_charset, 'windows-1251//TRANSLIT//IGNORE', $field);
		}
		//$field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

		return trim($field);
	}

	protected function getPath($category_id, $current_path = '') {
		if (isset($this->categories[$category_id])) {
			$this->categories[$category_id]['export'] = 1;

			if (!$current_path) {
				$new_path = $this->categories[$category_id]['id'];
			} else {
				$new_path = $this->categories[$category_id]['id'] . '_' . $current_path;
			}	

			if (isset($this->categories[$category_id]['parentId'])) {
				return $this->getPath($this->categories[$category_id]['parentId'], $new_path);
			} else {
				return $new_path;
			}

		}
	}



	protected function getProductUaName($product_id) {

		$lang_ua = $this->language_ua;

		$this->load->model('feed/epicentr_xml');

		$name = $this->model_feed_epicentr_xml->getProductUaName($product_id, $lang_ua);

		return $name;

	}


	protected function getProductUaDescription($product_id) {

		$lang_ua = $this->language_ua;

		$this->load->model('feed/epicentr_xml');

		$description = $this->model_feed_epicentr_xml->getProductUaDescription($product_id, $lang_ua);

		return $description;

	}

	protected function getOptionUaName($option) {

		$lang_ua = $this->language_ua;

		$this->load->model('feed/epicentr_xml');

		$name = $this->model_feed_epicentr_xml->getOptionUaName($option, $lang_ua);

		return $name;

	}



	function filterCategory($category) {
		$category['name'] = ucfirst(strtolower($category['name']));
		return $category;
	}
	
	function my_mb_ucfirst($str) {
   		 $fc = mb_strtoupper(mb_substr($str, 0, 1));
    	
    	 return $fc.mb_substr(mb_strtolower($str), 1);
	}
}
?>