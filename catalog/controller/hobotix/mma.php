<?php
class ControllerHobotixMMA extends Controller {		
	private $ymlFile = 'https://mma.in.ua/export-products-file?filename=PsIONFyUH';
		//private $ymlFile = 'https://vest.in.ua/suppliers/122.xml';

	private $supplerPrefix = '05';
	private $supplerCode = 'mmainua';

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
		'Объем, м3'
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

	private function guessColorFromName($offer){					
		if (strpos($offer['name'], $this->optionDeterminationParam)){
			$exploded = explode($this->optionDeterminationParam, $offer['name']);
			$exploded_id = explode('_', $this->getParamValue($offer, 'id'));

			return array(
				'name'  		=> trim($exploded[0]),
				'color' 		=> trim($exploded[1]),
				'option_sku' 	=> $this->prepareSKU($offer['vendorCode'] . '-' . $exploded_id[1])
			);
		}

		return false;
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

		return false;

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
		$this->echoLine('[MMAIMG] ' . ' размер ' . $remoteSize);

		foreach ($images as $image){
			if (filesize(DIR_IMAGE . $image) == $remoteSize){
				$this->echoLine('[MMAIMG] ' . ' совпадение размера ' . $image);
				return $image;
			}
		}

		return false;
	}						

	public function cron(){

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

		if ($ymlContents = file_get_contents($this->ymlFile)){
			$this->echoLine('[XML] Загрузили XML ');	
		} else {
			$this->echoLine('[XML] Не получилось загрузить XML ');	
			die();
		}

		$xmlFile = DIR_SUPPLIERS . 'mma.original.xml';
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

		$existentProducts = array();
		$existentOptions = array();

		foreach ($yml["price"]["catalog"]["category"] as $category){
			$categoriesArray[$this->getItemValue($category)] = $this->getParamValue($category, 'id');
			$categoriesArrayReverse[$this->getParamValue($category, 'id')] = $this->getItemValue($category);;
		}

		$this->categoriesArray = $categoriesArray;

		foreach ($yml["price"]["items"]["item"] as $offer){
			
			/*	if (mb_stripos($offer['name'], 'uag outback')){
					var_dump($offer);
				} else {
					continue;
				}
			*/					
				if ($offer['vendorCode'] != 'ЦУ-00031273'){
				//	continue;
				}

				$offer['vendorCode'] = $this->prepareSKU($offer['vendorCode']);
				$offer['originalVendorCode'] = $this->prepareSKU($offer['vendorCode']);
				$offer['color']		 = '';
				$offer['option_sku'] = '';		
				
				if ($this->validateCollapsedCategory($offer)){
					
					if ($nameWithColor = $this->guessColorFromName($offer)){
						$this->echoLine('[MMA] Товар с цветом! ' . $nameWithColor['name'] . ': ' . $nameWithColor['color'] . ': ' . $nameWithColor['option_sku']);
						$offer['name'] = $nameWithColor['name'];
						$offer['color'] = $nameWithColor['color'];
						$offer['option_sku'] = $nameWithColor['option_sku'];
					}
					
				} else {
					
					$this->echoLine('[MMA] Товар не в категории свертывания! ' . $offer['name']);
					$offer['vendorCode'] = $this->prepareSKUOfOption($offer);
					
				}
				
				if (!$this->model_catalog_product->checkIfSKUWasDeleted($offer["vendorCode"]) && !$this->model_catalog_product->checkIfSKUWasDeleted($this->supplerPrefix . $offer["vendorCode"])){
					
					if (!empty($offer["param"])){

						$tmp_param = [];
						foreach ($this->parseYMLParamArray($offer["param"]) as $param){
							if ($this->checkIfToParseAttribute($param["@attributes"]['name'])){
								$tmp_param[] = $param;
							} else {
								$this->echoLine('[MMA] Пропускаем атрибут ' . $param["@attributes"]['name']);
							}
						}
						unset($param);
						$offer["param"] = $tmp_param;


						foreach ($this->parseYMLParamArray($offer["param"]) as &$param){	
							if (isset($searchReplaceArray[$param["@attributes"]['name']])){
								$param["@attributes"]['name'] = $searchReplaceArray[$param["@attributes"]['name']];
								$attributesArray[$param["@attributes"]["name"]] = $searchReplaceArray[$param["@attributes"]["name"]];
							} else {
								$attributesArray[$param["@attributes"]["name"]] = '';
							}
						}
					}
					
					$available = false;
					if ($offer['available'] == 'true'){
						$available = true;
					}
					
					$quantity = ($available)?3:0;

					$product = false;
					$product_id = $this->model_catalog_product->findProductBySKU($this->supplerPrefix . $offer["vendorCode"]);					
					if ($product_id){						
						$product = $this->model_catalog_product->getExplicitProduct($product_id);
					}
					
					//Опция					
					$product_option_value_id = false;
					if ($offer['option_sku']){
						$product_option_value_id = $this->model_catalog_product->findProductOptionBySKU($this->supplerPrefix, $offer['option_sku']);
					}
					
					$offer['images'] = array('image' => array($offer['image']));
					
					if (is_array($offer['picture'])){
						foreach ($offer['picture'] as $picture){
							$offer['images']['image'][] = $picture;
						}
					} elseif (!empty($offer['picture'])){
						$offer['images']['image'][] = $offer['picture'];
					}
					
					$offer["description"] = $this->checkCDATA($offer["description"]);
					
					$xml['item'][] = array(
						'name' 				=> trim($offer["name"]),
						'sku' 				=> trim($offer["vendorCode"]),
						'category' 			=> 'MMAINUA',
						'brand' 			=> trim($offer["vendor"]),
						'cost'				=> $offer["priceuah"],
						'price'				=> (!empty($product) && !empty($product['price']))?$product['price']:$offer["priceuah"],
						'quantity' 			=> $quantity,
						'images' 			=> $offer['images'],	
						'ean' 				=> $offer['barcode'],					
						'description'		=> array('@cdata' => !empty($offer["description"])?$offer["description"]:''),
						'color' 			=> $offer['color'],	
						'option_sku'		=> $offer['option_sku'],	
						'param'				=> $offer["param"]?$offer["param"]:'',										
					);	
					
					if ($product_id){						
						//UPDATE QUERIES
						$this->echoLine('[MMA] Нашли товар ' . $product_id . ' - ' . $offer["vendorCode"]);
						
						$existentProducts[] = $product_id;
						
						$quantity = ($available)?3:0;
						$this->db->query("UPDATE " . DB_PREFIX . "product SET supplier = '" . (int)$quantity . "' WHERE product_id = '" . (int)$product_id . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = stock + supplier WHERE product_id = '" . (int)$product_id . "'");					
						$this->db->query("UPDATE " . DB_PREFIX . "product SET cost = '" . (float)$offer["priceuah"] . "' WHERE product_id = '" . (int)$product_id . "'");		
						
						$history = array(
							'product_id' 	=> $product_id,
							'suppler_code' 	=> $this->supplerCode,
							'price'			=> $offer["priceuah"]
						);
						
						$this->model_hobotix_hoboprice->addPrice($history);
						
					} else {
						$this->echoLine('[MMA] Не нашли товар ' . $offer["vendorCode"]);
					}
					
					//Обновление наличия опции
					if ($offer['option_sku'] && $product_option_value_id){	
						//UPDATE QUERIES
						$this->echoLine('[MMA] Нашли товар - опцию ' . $product_option_value_id . ' - ' . $offer['option_sku']);
						$quantity = ($available)?3:0;
						
						$existentOptions[] = $product_option_value_id;
						
						if (empty($optionsStockArray[$product_id])){
							$optionsStockArray[$product_id] = array();
						}
						
						$optionsStockArray[$product_id][$product_option_value_id] = $quantity;
						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET supplier = '" . (int)$quantity . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = supplier + stock WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");						
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET sku = optsku WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
						
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . $product_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "' AND LENGTH(o_v_image) > 2");
						
						if (!$query->num_rows){
							$this->echoLine('[MMAIMG] Пробуем найти картинку ' . $offer['image']);
							if ($optionImage = $this->tryToFindOptionImage($product_id, $offer['image'])){
								
								$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET o_v_image = '" . $this->db->escape($optionImage) . "' WHERE product_id = '" . $product_id . "' AND product_option_value_id = '" . (int)$product_option_value_id . "' AND LENGTH(o_v_image) < 2");
								
							};
						}
					}
					
				} else {
					$this->echoLine('[MMA] Пропускаем товар ' . $offer["vendorCode"]);				
				}
				
			}	
			
			//Повторное обновление наличия
			unset($product_id);
			unset($product_option_value_id);
			unset($quantity);
			
			foreach ($optionsStockArray as $product_id => $stockArray){
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET supplier = '0' WHERE product_id = '" . (int)$product_id . "'");
				
				$this->echoLine('[MMAS] Повторное обновление ' . $product_id);
				
				foreach ($stockArray as $product_option_value_id => $quantity){

					$this->echoLine('[MMASO] Наличие ' . $product_option_value_id . ' -> ' . $quantity);

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

					$unExistent = $this->model_hobotix_hoboparser->disableUnexsistentProducts($existentProducts, $existentOptions, $this->supplerPrefix);

					mkdir(DIR_SUPPLIERS . $this->supplerCode, 0755, true);

					$unexistentProductsString = '';
					foreach ($unExistent['unexistentProducts'] as $key => $value){
						$unexistentProductsString .= $value . PHP_EOL;
					}

					$unexistentProductsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mma.unexistentproducts.csv';
					file_put_contents($unexistentProductsFile, $unexistentProductsString);

					$unexistentOptionsString = '';
					foreach ($unExistent['unexistentOptions'] as $key => $value){
						$unexistentOptionsString .= $value . PHP_EOL;
					}

					$unexistentOptionsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mma.unexistentoptions.csv';
					file_put_contents($unexistentOptionsFile, $unexistentOptionsString);


					$attributesString = '';
					foreach ($attributesArray as $key => $value){
						$attributesString .= $key . ';' . $value . PHP_EOL;
					}

					$attributesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mma.attributes.csv';
					file_put_contents($attributesFile, $attributesString);

					$categoriesString = '';
					foreach ($categoriesArray as $key => $value){
						$categoriesString .= $key . ';' . $value . PHP_EOL;
					}

					$categoriesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mma.categories.csv';
					file_put_contents($categoriesFile, $categoriesString);


					$xmlString = LaLit\Array2XML::createXML('mma', $xml)->saveXML();;					
					$xmlFile = DIR_SUPPLIERS . 'mma.converted.xml';


	//			var_dump($xmlString);
					file_put_contents($xmlFile, $xmlString);

				}
			}																										