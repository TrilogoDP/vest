
<?php
class ControllerHobotixKosmotech extends Controller {	
	// private $ymlFile = 'https://b2b.kosmotech.com.ua/data/0af95c8108db9a85f6183b8a6e2cbddb.xml';
	// private $ymlFile = 'https://vest.in.ua/suppliers/kosmotech.xml';
	// xmllint --valid --noout suppliers/kosmotech.xml
	// xmllint --noout --schema http://lucas.ucs.ed.ac.uk/xml-schema/xmlns/simple.xsd suppliers/kosmotech.xml
		//private $ymlFile = 'https://vest.in.ua/suppliers/122.xml';
		// php oc_cli.php catalog hobotix/kosmotech/cron

	private $supplerPrefix = '06';
	private $supplerCode = 'kosmotech';

	private $categoriesArray = array();

	private $urlify;
	private $xml2array;	
	private $directory;	

	private $optionDeterminationParam = 'Цвет';
	private $optionDelimiterParam = '|';

	private $skuImages = array();

	private $excludedAttributes = [
		'Глубина, мм',
		'Вес товара в упаковке, кг',
		'Объем, м3',
		'Час роботи, хв',
		'Час зарядки, год'
	];

	private function echoLine($line){
		$line = str_replace('<![CDATA[', '', $line);
		$line = str_replace(']]>', '', $line);
		echo $line . PHP_EOL;			
	}

	function array_to_xml($data, &$xml) {

		foreach($data as $key => $value) {
			if (is_array($value)) {
				if (!is_numeric($key)) {
					$subnode = $xml->addChild(preg_replace('/\d/', '', $key));
					$this->array_to_xml($value, $subnode);
				}
			}
			else {
				$xml->addChild($key, $value);
			}
		}

		return $xml;
	}

	private function echoSimple($line){
		echo $line;			
	}


	private function memoryUnits($size)
	{
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}

	public static function parseYMLParamArray($params){
			//если параметр один
		if (isset($params['@value'])){
			return array($params);
		}

		return $params;
	}

	public static function getParamValue($data, $param){		

		if (isset($data["@attributes"][$param])){
			return $data["@attributes"][$param];
		}

		return false;
	}

	public static function checkCDATA($data){

		if (isset($data["@cdata"])){
			return $data["@cdata"];
		} else {
			return $data;
		}
	}

	public static function getItemValue($data){

		if (isset($data["@value"])){
			return $data["@value"];
		}

		return false;

	}

	public function checkIfToParseAttribute($name){

		return !in_array($name, $this->excludedAttributes);

	}

	public static function getParamValueName($data, $param){		
		foreach ($data as $param_value){
			if ($param_value["@attributes"]["name"] == $param){
				return $param_value['@value'];
			}				
		}

		return false;
	}


	private function guessIfThisIsMultiProduct($offer){

		if (empty($offer['param'])){
			return false;
		}

		$params = $this->parseYMLParamArray($offer['param']);

		foreach ($params as $param){
			if ($param["@attributes"]['name'] == $this->optionDeterminationParam){
				if (strpos($param["@value"], $this->optionDelimiterParam)){
					return explode($this->optionDelimiterParam, $param["@value"]);
				}

			}
		}

		return false;

	}	

	private function guessColorFromName($offer, $key){					
				$name = (string)$offer["name"];
				$vendorCode = (string)$offer["vendorCode"];
				//$id = (string)$item['id']; // Получаем значение атрибута id
				$sku = (string)$offer["vendorCode"];

				// Получаем цвет из названия товара
				// Получаем параметры совместимости
				$name_trash = array('(BD1)', '(BD2)', '(BD2d)', '5G', 'Galaxy', 'iPhone12', 'iPhone13', 'iPhone14', 'iPhone15', 'iPhone16',  'iPhone 12', 'iPhone 13', 'iPhone 14', 'iPhone 15', 'iPhone 16', 'iPhone 12 Pro Max', 'iPhone 13 Pro Max', 'iPhone 14 Pro Max', 'iPhone 15 Pro Max', 'iPhone 16 Pro Max', 'iPhone 12 Pro', 'iPhone 13 Pro', 'iPhone 14 Pro', 'iPhone 15 Pro', 'iPhone 16 Pro', 'Redmi Note 12 Pro 4G', 'S23', 'Spark10 Pro (KI7)');

				$colors_map = [  "AntiqueWhite" => "White",
				"AppleGreen" => "Green",
				"BearYellow" => "Yellow",
				"BerryPurple" => "Purple",
				"CanaryYellow" => "Yellow",
				"CanglingGreen" => "Green",
				"ChalkPink" => "Pink",
				"ChanelPink" => "Pink",
				"ChinaRed" => "Red",
				"CokeRed" => "Red",
				"DarkBlue" => "Blue",
				"DarkGreen" => "Green",
				"DarkGrey" => "Grey",
				"DeepBlue" => "Blue",
				"DeepPurple" => "Purple",
				"GraphiteBlack" => "Black",
				"GreySmoke" => "Grey",
				"GunGrey" => "Grey",
				"LightBlue" => "Blue",
				"LightGreen" => "Green",
				"LightPurple" => "Purple",
				"MaxDarkGreen" => "DarkGreen",
				"MintGreen" => "Mint",
				"NavyBlue" => "Blue",
				"PearlyWhite" => "White",
				"SeaBlue" => "Blue",
				"SierraBlue" => "Sierra",
				"Deep" => "Deep",
				"Ocean" => "Ocean",
				"Ice" => "Ice",
				"Official" => "Official",
				"Offcial" => "Offcial",
				"Watermelon" => "Watermelon",
				"Wine" => "Wine",
				"Apple" => "Apple",
				"Dark" => "Dark",
				"Canary" => "Canary",
				"Sea" => "Sea",
				"IceSea" => "IceSea",
				"Lavander" => "Lavander",
				"Lavender" => "Lavender",
				"Sapphire" => "Sapphire",
				"Plum" => "Plum",
				"Rose" => "Rose",
				"Silver" => "Silver",
				"Orange" => "Orange",
				"Chalk" => "Chalk",
				"Olive" => "Olive",
				"Clear" => "Clear",
				"Gold" => "Gold",
				"RoyalBlue" => "Blue"
				];

				$pretrash_colors = ['LSBlue', 'LSGreen', 'LSGrey', 'LSPurple'];
				$trash_colors = ['DBlue', 'DGreen', 'DGrey', 'DPurple', 'LBlue', 'LGreen', 'LGrey', 'LPurple', 'SBlue', 'SGreen', 'SGrey', 'SPurple', 'RBlue', 'RGreen', 'RGrey', 'RPurple'];

				// Проверяем, есть ли параметр "Цвет" и "Сумісність з моделями"
				$compatibility = null;
				$brand_compatibility = null;

				foreach ($this->parseYMLParamArray($offer["param"]) as $param) {
					$param_name = (string) $param["@attributes"]['name'];		
			
					// Находим параметр с именем "Сумісність з моделями"
					if ($param_name == 'Сумісність з моделями') {
						// Извлекаем значение CDATA
						$compatibility = (string) $param["@cdata"];
					}

					if ($param_name == 'Сумісність з брендом') {
						// Извлекаем значение CDATA
						$brand_compatibility = (string) $param["@cdata"];

						// Если есть совместимость с брендом, вычитаем его из модели
						$to_remove_compatibility = trim(str_ireplace($brand_compatibility, '', $compatibility));
					}								
				}

				// Отделяем часть названия товара, содержащее Бренд + Модель + Цвет
				$name_parts = explode(' for ', (string) $name);

				if (!empty($compatibility) || !empty($brand_compatibility)) {
					if (count($name_parts) == 2) {
						// Получаем вторую часть названия
						$second_part = trim(str_ireplace($brand_compatibility, '', $name_parts[1]));
						// Убираем параметры совместимости
						$color_parts = trim(str_ireplace($to_remove_compatibility, '', $second_part));
						$color_parts = str_replace($name_trash, '', $color_parts);
						$color_parts = str_replace(' ', '', $color_parts);
						// Отделяем цвет после запятой или точки
						$delimiter = strpos($color_parts, ',') !== false ? ',' : (strpos($color_parts, '.') !== false ? '.' : null);
						if ($delimiter) {
							$color_parts = explode($delimiter, $color_parts, 2)[1];
						}

						$product_name = (string) ($name_parts[0] . ' for ' . $compatibility);


					} else {
						$second_part = $color_parts = null;
					}
				} else {
					$second_part = $color_parts = null;
					$product_name = (string) $name_parts[0];
				}

				// Подготовка общего Артикула для вариации
				if (strpos($vendorCode, '-') !== false) {
					$sku = explode('-', $vendorCode)[0];
				}

				$sku = str_replace($pretrash_colors, '', $sku);
				$sku = str_replace($trash_colors, '', $sku);

				// Конвертируем цвета
				if ($color_parts != null) {
					$sku = str_replace($color_parts, '', $sku);
				}

				$sku = str_replace(array_values($colors_map), '', $sku);

				return array(
					'name'  		=> $product_name,
					'color' 		=> $color_parts,
					'option_sku' 	=> $vendorCode,
					'vendorCode' 	=> $sku,
				);							
	}


	private function prepareSKU($sku){			
		$sku = str_replace('ЦУ-', '', $sku);
		$sku = trim($sku);

		return $sku;
	}

	private function prepareSKUOfOption($offer){

		$sku = $this->prepareSKU($offer['vendorCode']);

		if (strpos($this->getParamValue($offer, 'id'), '_')){
			$exploded_id = explode('_', $this->getParamValue($offer, 'id'));
			$sku .= '-' . trim($exploded_id[1]);
		}

		$sku = trim($sku);

		return $sku;
	}


	private function validateCollapsedCategory($offer){

		$configAllowedCategories = explode(PHP_EOL, $this->config->get('config_mma_option_categories'));
		$allowedCategories = array();
		$allowedCategoriesIDS = array();			

		foreach ($configAllowedCategories as $cAC){
			$cAC = trim($cAC);
			if ($cAC && !empty($this->categoriesArray[$cAC])){				
				$allowedCategories[$this->categoriesArray[$cAC]] = $cAC;
			}
		}

		if (!empty($allowedCategories[$offer['categoryId']])){
			return true;
		}

		// return false;
		return true;

	}	

	private function getRemoteImageSize($image){
		$curl = curl_init($image);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_exec($curl);
		return curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);			
	}

	private function tryToFindOptionImage($product_id, $remoteImage){
		$images = array();

		$query = $this->db->query("SELECT image FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id . "'");
		$images[] = $query->row['image'];

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . $product_id . "'");
		foreach ($query->rows as $row){
			$images[] = $row['image'];
		}

		$remoteSize = $this->getRemoteImageSize($remoteImage);				
		$this->echoLine('[KOSMOTECHIMG] ' . ' размер ' . $remoteSize);

		foreach ($images as $image){
			if (filesize(DIR_IMAGE . $image) == $remoteSize){
				$this->echoLine('[KOSMOTECHIMG] ' . ' совпадение размера ' . $image);
				return $image;
			}
		}

		return false;
	}						

	private function isCategoryInCheholCats($offer) {
		$chehol_cats = array('247','768','770','771','773','774','777','778','780','797','798','799','800','802','807','809','814','820','840','842','843','845','855','856','858','864','726','727','728','729','730','731','738','739','740','741','742','743','748','752');
		
		return in_array($offer, $chehol_cats);
	}

	public function cron(){
		/* ОБРАБОТКА ОШИБКИ В ФАЙЛЕ */		

		// Укажите URL внешнего XML ресурса
		$xmlUrl = 'https://b2b.kosmotech.com.ua/data/0af95c8108db9a85f6183b8a6e2cbddb.xml';

		// Скачайте файл с внешнего ресурса
		$xmlContent = file_get_contents($xmlUrl);

		// Проверьте, был ли файл загружен успешно
		if ($xmlContent === false) {
			die('Не удалось загрузить XML файл.');
		}

		// Найдите позицию тега <name>Kosmotech</name>
		$position = strpos($xmlContent, '<name>Kosmotech</name>');

		if ($position === false) {
			die('Тег <name>Kosmotech</name> не найден.');
		}

		// Определите строку для замены
		$replacement = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL . '<shop>';

		// Замените часть до тега на новую строку
		$modifiedXml = $replacement . substr($xmlContent, $position);

		// Укажите путь для сохранения измененного XML файла
		$ymlFile = DIR_SUPPLIERS . 'kosmotech.xml';

		// Сохраните файл
		 file_put_contents($ymlFile, $modifiedXml);

		 
		ini_set('memory_limit','2G');
		$this->config->set('config_language_id', 1);

		$this->load->model('catalog/product');
		$this->load->model('hobotix/hoboprice');

		if (!defined('OPENCART_CLI_MODE')){
			die('CLI ONLY');
		}

		require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'XML2Array2.php');
		require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'Array2XML.php');
		require_once( DIR_SYSTEM . 'library/urlify.php');		


		if ($ymlContents = file_get_contents($ymlFile)){
			$this->echoLine('[XML] Загрузили XML ');	
		} else {
			$this->echoLine('[XML] Не получилось загрузить XML ');	
			die();
		}

		$xmlFile = DIR_SUPPLIERS . 'kosmotech.original.xml';
		file_put_contents($xmlFile, $ymlContents);

		try {
			$yml = LaLit\XML2Array::createArray($ymlContents);
			$this->echoLine('[YML] Загрузили XML в массив');	
		} catch (Exception $e){
			$this->echoLine('[YML] Ошибка разбора XML. ' . $e->getMessage());
			die ();
		}

		$xml = array('item' => array());




		$this->load->model('hobotix/hoboparser');
		$searchReplaceArray = $this->model_hobotix_hoboparser->init();

		$attributesArray = array();
		$categoriesArray = array();
		$optionsStockArray = array();
		$offers_isbn = array();

		$existentProducts = array();
		$existentOptions = array();

		foreach ($yml["shop"]["categories"]["category"] as $category){
			$categoriesArray[$this->getItemValue($category)] = $this->getParamValue($category, 'id');
			$categoriesArrayReverse[$this->getParamValue($category, 'id')] = $this->getItemValue($category);;
		}

		$this->categoriesArray = $categoriesArray;		

		foreach ($yml["shop"]["items"]["item"] as $key => $offer){

				$offer['vendorCode'] = $this->checkCDATA($offer['vendorCode']);
				$offer['originalVendorCode'] = $this->prepareSKU($offer['vendorCode']);
				$offer['color']		 = '';
				$offer['option_sku'] = '';
				$offer["original_name"] = $this->checkCDATA($offer["name"]);	
				$offer["name"] = $this->checkCDATA($offer["name"]);
				$offer["isbn"] = $this->getParamValue($offer, 'id');	
				
				$offers_isbn[] = $offer["isbn"];
				
				if ($this->isCategoryInCheholCats($offer['categoryId'])) {
					if ($nameWithColor = $this->guessColorFromName($offer, $key)){
						$this->echoLine('[KOSMOTECH] Товар с цветом! ' . $nameWithColor['name'] . ': ' . $nameWithColor['color'] . ': ' . $nameWithColor['option_sku']);
						$offer['name'] = $nameWithColor['name'];
						$offer['color'] = $nameWithColor['color'];
						$offer['option_sku'] = $nameWithColor['option_sku'];
						$offer["vendorCode"] = $nameWithColor['vendorCode'];
					}
				}

				if (!$this->model_catalog_product->checkIfSKUWasDeleted($offer["vendorCode"]) && !$this->model_catalog_product->checkIfSKUWasDeleted($this->supplerPrefix . $offer["vendorCode"])){
				  if ($offer["vendorCode"]){
					
	
					// Указываем путь к файлу лога
					$logFile =  DIR_SUPPLIERS . 'logfile_last.log';
					$productData = print_r('[KOSMOTECH] атрибуты товара ' . $offer["original_name"], true);
					file_put_contents($logFile, $productData . "\n", FILE_APPEND);
				
					if (!empty($offer["param"])) {

						$tmp_param = [];
					
						foreach ($this->parseYMLParamArray($offer["param"]) as &$param) {
							
							// Логирование входящего атрибута 'name'
							if (isset($param["@attributes"]['name'])) {
								$attributeName = $param["@attributes"]['name'];
								$paramData = print_r('[KOSMOTECH] входящий атрибут ' . json_encode($attributeName, JSON_UNESCAPED_UNICODE), true);
								file_put_contents($logFile, $paramData . "\n", FILE_APPEND);
					
								// Проверка и обработка атрибута 'unit'
								if (!empty($param["@attributes"]['unit'])) {
									$param["@attributes"]['name'] .= ', ' . $param["@attributes"]['unit'];
									$param["@attributes"]['unit'] = ''; // Очистка атрибута 'unit' после добавления к 'name'
								}
					
								// Проверка на соответствие исключенным атрибутам
								if ($this->checkIfToParseAttribute($attributeName)) {
									
									// Замена имени на значение из $searchReplaceArray, если найдено
									if (isset($searchReplaceArray[$attributeName])) {
										$param["@attributes"]['name'] = $searchReplaceArray[$attributeName];
										$attributesArray[$param["@attributes"]['name']] = $searchReplaceArray[$attributeName];
										
										$paramData_r = print_r('[KOSMOTECH] отработало сравнение атрибута ' . json_encode($param["@attributes"]['name'], JSON_UNESCAPED_UNICODE), true);
										file_put_contents($logFile, $paramData_r . "\n", FILE_APPEND);
									} else {
										// Если совпадение не найдено, устанавливаем значение в пустую строку
										$attributesArray[$attributeName] = '';
										
										$paramData = print_r('[KOSMOTECH] не отработало сравнение атрибута ' . json_encode($attributeName, JSON_UNESCAPED_UNICODE), true);
										file_put_contents($logFile, $paramData . "\n", FILE_APPEND);
									}
					
									$tmp_param[] = $param; // Добавляем в $tmp_param только нужные атрибуты
								} else {
									// Логирование исключенного атрибута
									$this->echoLine('[KOSMOTECH] Пропускаем атрибут ' . $attributeName);
									$attrData = print_r('[KOSMOTECH] пропустили атрибут ' . json_encode($attributeName, JSON_UNESCAPED_UNICODE), true);
									file_put_contents($logFile, $attrData . "\n", FILE_APPEND);
								}
					
							} else {
								// Логирование, если атрибут 'name' отсутствует
								$errorData = print_r('[KOSMOTECH] Атрибут name отсутствует в param', true);
								file_put_contents($logFile, $errorData . "\n", FILE_APPEND);
							}
						}
					
						$offer["param"] = $tmp_param; // Присваиваем обработанный массив параметров
					}
												
					
					$available = false;

					if ($this->getParamValue($offer, 'available') == 'true'){
						$available = true;
					}
					
					$quantity = ($available) ? 3 :0;

					$product = false;
					$product_id = $this->model_catalog_product->findProductBySKU($this->supplerPrefix . $offer["vendorCode"]);
					

					if ($product_id){						
						$product = $this->model_catalog_product->getExplicitProduct($product_id);
					}
					
					//Опция					
					$product_option_value_id = false;
					
					if ($offer['option_sku']){
						$product_option_value_id = $this->model_catalog_product->findProductOptionBySKU($this->supplerPrefix = NULL, $offer['option_sku']);
					}

					// Создаем массив для URL изображений
						$images = array('image' => array());

					// Проходим по каждому элементу item и собираем изображения
					if (is_array($offer['image'])){
						foreach ($offer['image'] as $image) {
							$images['image'][] = $image;
						}
					} else {
							$images['image'][] = $offer['image'];	
					}

						unset($image);
						$tmp = array('image' => array());
						foreach ($images['image'] as &$image){												
							mkdir($dir = (DIR_IMAGE . 'catalog/' . $this->supplerCode . '/tmp/'), 0755, true);
							$local_file = str_replace('img', '', mb_strtolower(basename($image)));
							$local_img = $dir . $local_file;
														
							$this->echoLine($image . ' -> ' . $local_img);
	
							if (file_exists($local_img) && filesize($local_img) == 0){
								unlink($local_img);
							}
	
							if (!file_exists($local_img) || filesize($local_img) == 0){							
								$img = file_get_contents(mb_strtolower($image));
								file_put_contents($local_img, $img);
							}
							$tmp['image'][] = 'https://vest.in.ua/image/catalog/' . $this->supplerCode . '/tmp/' . $local_file;	
						}					
						
						$images = $tmp;

					$offer["description"] = $this->checkCDATA($offer["description"]);
					
					$xml['item'][] = array(
						'name' 				=> $offer["name"],
						'sku' 				=> $offer["vendorCode"],
						'category' 			=> 'KOSMOTECH',
						'brand' 			=> $this->checkCDATA($offer["vendor"]),
						'cost'				=> $offer["price"],
						'price'				=> !empty($product['dnup'])?$product['price']:$offer["price"],
						'quantity' 			=> $quantity,
						'images' 			=> $images,				
						'description'		=> $offer["description"],
						'color' 			=> $offer['color'],	
						'option_sku'		=> $offer['option_sku'],
						'isbn' 				=> $offer["isbn"],
						'param'				=> $offer["param"]?$offer["param"]:'',										
					);	

				
					if ($product_id){
						//UPDATE QUERIES
						$this->echoLine('[KOSMOTECH] Нашли товар ' . $product_id . ' - ' . $offer["vendorCode"]);
						
						$existentProducts[] = $product_id;
						
						$quantity = ($available) ? 3 : 0;

						if ($offer["vendorCode"] == 'AGMattFrameMGiP14P') {
							die();
						}
						
						$this->db->query("UPDATE " . DB_PREFIX . "product SET supplier = '" . (int)$quantity . "' WHERE product_id = '" . (int)$product_id . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = stock + supplier WHERE product_id = '" . (int)$product_id . "'");					
						$this->db->query("UPDATE " . DB_PREFIX . "product SET cost = '" . (float)$offer["price"] . "' WHERE product_id = '" . (int)$product_id . "'");		
						
						$history = array(
							'product_id' 	=> $product_id,
							'suppler_code' 	=> $this->supplerCode,
							'price'			=> $offer["price"]
						);
						
						$this->model_hobotix_hoboprice->addPrice($history);
						
					} else {
						$this->echoLine('[KOSMOTECH] Не нашли товар - ' . $offer["vendorCode"]);
					}
					
					// Обновление наличия опции
					if ($offer['option_sku'] && $product_option_value_id) {
						// UPDATE QUERIES
						$this->echoLine('[KOSMOTECH] Нашли товар - опцию ' . $product_option_value_id . ' - ' . $offer['option_sku']);
						$quantity = ($available) ? 3 : 0;

						$existentOptions[] = $product_option_value_id;

						if (empty($optionsStockArray[$product_id])) {
							$optionsStockArray[$product_id] = array();
						}

						$optionsStockArray[$product_id][$product_option_value_id] = $quantity;

						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET isbn = '" . (int)$offer["isbn"] . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET sku = optsku WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET supplier = '" . (int)$quantity . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "' AND isbn = '" . (int)$offer["isbn"] . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = supplier + stock WHERE product_option_value_id = '" . (int)$product_option_value_id . "' AND isbn = '" . (int)$offer["isbn"] . "'");

						// Установка цены опции в 0
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET price = 0 WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");

						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . $product_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "' AND LENGTH(o_v_image) > 2");

						if (!$query->num_rows) {
							$optionImage = str_replace('https://vest.in.ua/image/', '', $images['image'][0]);
							$this->echoLine('[KOSMOTECHIMG] Пробуем найти картинку ' . $optionImage);
							
							// if ($optionImage = $this->tryToFindOptionImage($product_id, $images['image'][0])){
							$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET o_v_image = '" . $this->db->escape($optionImage) . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
							
							// };
						}
					}
					
					} else {
						$this->echoLine('[KOSMOTECH] Пропускаем товар ' . $offer["vendorCode"]);				
					}
			  }
			}
		
			$this->load->model('catalog/product');
			$option_id = 14; // ID опции Цвет
			$category_id = 228; // ID категории KOSMOTECH
	//		$isbns = $this->model_catalog_product->getCategoryProductIsbns($category_id);
			$product_ids = $this->model_catalog_product->getCategoryProductIDs($category_id);
			$option_isbns = $this->model_catalog_product->getCategoryProductOptionIsbns($category_id);

			$logFile =  DIR_SUPPLIERS . 'logfile_kosmotech_all.log';
			$paramData = print_r('[KOSMOTECH] товар, который есть в базе ' . json_encode($product_ids, JSON_UNESCAPED_UNICODE), true);
			file_put_contents($logFile, $paramData . "\n", FILE_APPEND);

			$logFile =  DIR_SUPPLIERS . 'logfile_base_option.log';
			$paramData = print_r('[KOSMOTECH] опция, которая есть в базе ' . json_encode($option_isbns, JSON_UNESCAPED_UNICODE), true);
			file_put_contents($logFile, $paramData . "\n", FILE_APPEND);

			$logFile =  DIR_SUPPLIERS . 'logfile_feed_option.log';
			$paramData = print_r('[KOSMOTECH] опция, которая есть в фиде ' . json_encode($offers_isbn, JSON_UNESCAPED_UNICODE), true);
			file_put_contents($logFile, $paramData . "\n", FILE_APPEND);

		//	$combined_isbns = array_unique(array_merge($isbns, $option_isbns));
		//	print_r($combined_isbns);
		//	$deleted_count = $this->model_catalog_product->removeOptionsNotInOffers($offers_isbn, $option_isbns);
		//  echo "Количество удаленных значений опций: " . $deleted_count;

			$options_removed = $this->model_catalog_product->removeSingleOptionAndValueForCategory($category_id, $option_id);
			echo "Количество очищенных значений опций: " . $options_removed;

			$deleted_options = $this->model_catalog_product->removeOptionsNotInOffers($offers_isbn, $option_isbns, $category_id);
		    echo "Количество зануленых значений опций: " . $deleted_options;

			foreach ($product_ids as $product_id) {
				$this->db->query("UPDATE oc_product SET supplier = 3 WHERE product_id='".(int)$product_id."'");
				$this->db->query("UPDATE oc_product SET quantity = supplier + stock WHERE product_id='".(int)$product_id."'");
			}

			$single_products = $this->model_catalog_product->getProductsWithoutOptions($category_id);

			$logFile =  DIR_SUPPLIERS . 'logfile_single.log';
			$paramData = print_r('[KOSMOTECH] товар без опций ' . json_encode($single_products, JSON_UNESCAPED_UNICODE), true);
			file_put_contents($logFile, $paramData . "\n", FILE_APPEND);

			$deleted_single_products = $this->model_catalog_product->removeProductsNotInOffers($offers_isbn, $single_products, $category_id);
		    echo "Количество зануленых простых товаров: " . $deleted_single_products;

			$deleted_options_products = $this->model_catalog_product->getProductsByCategoryWithZeroOptions($category_id);
			echo "Количество зануленых товаров с опциями: " . count($deleted_options_products);

			$logFile =  DIR_SUPPLIERS . 'logfile_products_optionNull.log';
			$paramData = print_r('[KOSMOTECH] товар у которого опции по нулям ' . json_encode($deleted_options_products, JSON_UNESCAPED_UNICODE), true);
			file_put_contents($logFile, $paramData . "\n", FILE_APPEND);
		//	die();
			
				//Повторное обновление наличия
				unset($product_id);
				unset($product_option_value_id);
				unset($quantity);


			// ЗАПОЛНЯЕМ ТОВАР ФОТОГРАФИЯМИ ИЗ ОПЦИЙ
			$this->addOptionImagesToProductImages($category_id, $option_id);

				/*
				foreach ($optionsStockArray as $product_id => $stockArray){
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET supplier = '0' WHERE product_id = '" . (int)$product_id . "'");
					
					$this->echoLine('[KOSMOTECHS] Повторное обновление ' . $product_id);
					
					foreach ($stockArray as $product_option_value_id => $quantity){

						$this->echoLine('[KOSMOTECHSO] Наличие ' . $product_option_value_id . ' -> ' . $quantity);

						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET supplier = '" . (int)$quantity . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");					
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = supplier + stock WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");						
					}

					//Обновление наличия товара на складе
					$this->db->query("UPDATE oc_product SET stock = (SELECT SUM(stock) as stock FROM oc_product_option_value WHERE product_id = '".(int)$product_id."' GROUP BY product_id) WHERE product_id='".(int)$product_id."'");

					//Обновление наличия товара у поставщика
					$this->db->query("UPDATE oc_product SET supplier = (SELECT SUM(supplier) as supplier FROM oc_product_option_value WHERE product_id = '".(int)$product_id."' GROUP BY product_id) WHERE product_id='".(int)$product_id."'");

					//Общее наличие товара на фронте
						$this->db->query("UPDATE oc_product SET quantity = stock + supplier WHERE product_id='".(int)$product_id."'");
					}

					*/

					// $unExistent = $this->model_hobotix_hoboparser->disableUnexsistentProducts($existentProducts, $existentOptions, $this->supplerPrefix);

					mkdir(DIR_SUPPLIERS . $this->supplerCode, 0755, true);

					$unexistentProductsString = '';
					foreach ($unExistent['unexistentProducts'] as $key => $value){
						$unexistentProductsString .= $value . PHP_EOL;
					}

					$unexistentProductsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kosmotech.unexistentproducts.csv';
					file_put_contents($unexistentProductsFile, $unexistentProductsString);

					$unexistentOptionsString = '';
					foreach ($unExistent['unexistentOptions'] as $key => $value){
						$unexistentOptionsString .= $value . PHP_EOL;
					}

					$unexistentOptionsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kosmotech.unexistentoptions.csv';
					file_put_contents($unexistentOptionsFile, $unexistentOptionsString);


					$attributesString = '';
					foreach ($attributesArray as $key => $value){
						$attributesString .= $key . ';' . $value . PHP_EOL;
					}

					$attributesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kosmotech.attributes.csv';
					file_put_contents($attributesFile, $attributesString);

					$categoriesString = '';
					foreach ($categoriesArray as $key => $value){
						$categoriesString .= $key . ';' . $value . PHP_EOL;
					}

					$categoriesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kosmotech.categories.csv';
					file_put_contents($categoriesFile, $categoriesString);


					$xmlString = LaLit\Array2XML::createXML('kosmotech', $xml)->saveXML();;					
					$xmlFile = DIR_SUPPLIERS . 'kosmotech.converted.xml';


					// var_dump($xmlString);
					file_put_contents($xmlFile, $xmlString);

	}

	public function addOptionImagesToProductImages($category_id, $specific_option_id) {
		// Initialize the database connection
		$db = $this->db;
	
		// Get the maximum product_image_id currently in use
		$query = $db->query("SELECT IFNULL(MAX(product_image_id), 0) AS max_pid FROM `" . DB_PREFIX . "product_image`");
		$max_pid = (int)$query->row['max_pid'];
	
		// Set the max_pid variable in the SQL session
		$db->query("SET @max_pid := " . $max_pid);
	
		// Prepare the SQL query
		$sql = "
		INSERT INTO `" . DB_PREFIX . "product_image` (
			`product_id`, 
			`image`, 
			`epicentr_option`, 
			`epicentr_status`, 
			`sort_order`, 
			`video_in_product`
		)
		SELECT
			pov.product_id,
			pov.o_v_image AS image,
			NULL AS epicentr_option,
			0 AS epicentr_status,
			0 AS sort_order,
			'' AS video_in_product
		FROM
			`" . DB_PREFIX . "product_option_value` pov
		INNER JOIN `" . DB_PREFIX . "product_to_category` ptc
			ON ptc.product_id = pov.product_id AND ptc.category_id = '" . (int)$category_id . "'
		INNER JOIN (
			SELECT DISTINCT product_id
			FROM `" . DB_PREFIX . "product_option`
			WHERE option_id = '" . (int)$specific_option_id . "'
		) po
			ON po.product_id = pov.product_id
		LEFT JOIN `" . DB_PREFIX . "product_image` pi
			ON pi.product_id = pov.product_id AND pi.image = pov.o_v_image
		WHERE
			pov.o_v_image IS NOT NULL AND pov.o_v_image != '' AND
			pi.product_image_id IS NULL;
		";
	
		// Execute the SQL query
		$db->query($sql);
	}	

	public function find_unclosed_tags_with_line_numbers($xml_content)
	{
		$lines = explode("\n", $xml_content); // Разбиваем XML на строки
		$open_tags = [];
		$close_tags = [];
	
		// Проходим по каждой строке
		foreach ($lines as $line_number => $line) {
			// Поиск всех открывающихся тегов в строке
			preg_match_all('/<([a-zA-Z0-9]+)([^>]*)>/', $line, $open_matches);
			foreach ($open_matches[1] as $tag) {
				$open_tags[] = ['tag' => $tag, 'line' => $line_number + 1];
			}
	
			// Поиск всех закрывающихся тегов в строке
			preg_match_all('/<\/([a-zA-Z0-9]+)>/', $line, $close_matches);
			foreach ($close_matches[1] as $tag) {
				$close_tags[] = ['tag' => $tag, 'line' => $line_number + 1];
			}
		}
	
		// Сравниваем открытые и закрытые теги
		$unclosed_tags = [];
		foreach ($open_tags as $open_tag) {
			$found = false;
			foreach ($close_tags as $key => $close_tag) {
				if ($close_tag['tag'] == $open_tag['tag']) {
					unset($close_tags[$key]); // Закрывающий тег найден, удаляем его из массива
					$found = true;
					break;
				}
			}
	
			// Если закрывающий тег не найден, добавляем в массив незакрытых тегов
			if (!$found) {
				$unclosed_tags[] = $open_tag;
			}
		}
	
		return $unclosed_tags;
	}

	public function tagsProblems(){
		$xml_content = file_get_contents($this->ymlFile);  // Загрузить XML из файла
		$unclosed_tags = $this->find_unclosed_tags_with_line_numbers($xml_content);

		if (!empty($unclosed_tags)) {
			echo "Незакрытые теги найдены:\n";
			foreach ($unclosed_tags as $unclosed_tag) {
				echo "Тег <{$unclosed_tag['tag']}> незакрыт. Обнаружен на строке {$unclosed_tag['line']}.\n";
			}
		} else {
			echo "Все теги корректно закрыты.\n";
		}	
	}

	public function validate_xml($xml_content) {
		$dom = new DOMDocument();
		libxml_use_internal_errors(true); // Включить сбор ошибок

		if (!$dom->loadXML($xml_content)) {
			foreach (libxml_get_errors() as $error) {
				echo "Ошибка: ", $error->message;
			}
			libxml_clear_errors(); // Очистить ошибки
			return false;
		}

		return true;
	}

	public function otherProblems(){
		$xml_content = file_get_contents($this->ymlFile);  // Загрузить XML из файла
		if ($this->validate_xml($xml_content)) {
			echo "XML валиден.";
		} else {
			echo "Обнаружены ошибки в XML.";
		}
	}


	public function convert_file_to_utf8($input_file, $output_file) {
		// Читаем содержимое исходного файла
		$file_content = file_get_contents($input_file);
		
		if ($file_content === false) {
			die("Ошибка при чтении файла $input_file");
		}

		// Определяем текущую кодировку файла
		$current_encoding = mb_detect_encoding($file_content, 'auto', true);

		// Преобразуем содержимое в UTF-8
		if ($current_encoding != 'UTF-8') {
			$utf8_content = mb_convert_encoding($file_content, 'UTF-8', $current_encoding);
		} else {
			$utf8_content = $file_content; // Если файл уже в UTF-8, оставляем содержимое как есть
		}

		// Записываем результат в новый файл (или перезаписываем исходный)
		$xmlFile = DIR_SUPPLIERS . $output_file;

		$result = file_put_contents($xmlFile, $utf8_content);
		
		if ($result === false) {
			die("Ошибка при записи файла $output_file");
		}

		echo "Файл успешно преобразован в UTF-8 и сохранен как $output_file\n";
	}

	public function remove_description_tags($input_file, $output_file)
	{
		    // Читаем содержимое XML-файла
			$xml_content = file_get_contents($input_file);
    
			if ($xml_content === false) {
				die("Ошибка при чтении файла $input_file");
			}
		
			// Используем регулярное выражение для поиска всех тегов <description>
			$pattern = '/<description>.*?<\/description>/s';
			preg_match_all($pattern, $xml_content, $matches);
			
			// Подсчитываем количество найденных тегов <description>
			$description_count = count($matches[0]);
		
			// Удаляем все теги <description> и их содержимое
			$cleaned_xml_content = preg_replace($pattern, '', $xml_content);
		
			if ($cleaned_xml_content === null) {
				die("Ошибка при удалении тегов <description>");
			}
		
			// Записываем очищенное содержимое в новый файл
			$xmlFile = DIR_SUPPLIERS . $output_file;
			$result = file_put_contents($xmlFile, $cleaned_xml_content);
			
			if ($result === false) {
				die("Ошибка при записи файла $output_file");
			}
		
			// Выводим количество удалённых тегов
			echo "Удалено $description_count тегов <description>.\n";
			echo "Файл сохранен как $output_file\n";
	}

	public function clean_description_tags($input_file, $output_file)
	{
		// Читаем содержимое XML-файла
		$xml_content = file_get_contents($input_file);
		
		if ($xml_content === false) {
			die("Ошибка при чтении файла $input_file");
		}

		// Используем регулярное выражение для поиска содержимого тегов <description>
		$pattern = '/<description>(.*?)<\/description>/s';
		
		// Замена содержимого тегов <description>
		$cleaned_xml_content = preg_replace_callback($pattern, function($matches) {
			// Очищаем содержимое: убираем спецсимволы и пустые строки
			$cleaned_content = preg_replace('/\s+/', ' ', $matches[1]); // Удаляем лишние пробелы и переносы строк
			$cleaned_content = preg_replace('/[^\p{L}\p{N}\s]/u', '', $cleaned_content); // Удаляем спецсимволы
			return "<description>" . trim($cleaned_content) . "</description>";
		}, $xml_content);

		if ($cleaned_xml_content === null) {
			die("Ошибка при очистке содержимого тегов <description>");
		}

		// Записываем очищенное содержимое в новый файл
		$xmlFile = DIR_SUPPLIERS . $output_file;
		$result = file_put_contents($xmlFile, $cleaned_xml_content);
		
		if ($result === false) {
			die("Ошибка при записи файла $output_file");
		}

		// Выводим сообщение об успешной обработке
		echo "Содержимое тегов <description> очищено от спецсимволов и пустых строк.\n";
		echo "Файл сохранен как $output_file\n";
	}

	public function convertFile() {
	// Пример использования
		$input_file = $this->ymlFile; // Путь к исходному файлу
		$output_file = 'kosmotech_clean.xml'; // Путь к файлу, в который будет сохранён результат

		$this->clean_description_tags($input_file, $output_file);
	}		
}																								