<?php
	class ControllerHobotixKSMUA extends Controller {
		private $ymlFile = 'http://ksmua.com.ua/XML/price.xml';
		
		
		private $supplerPrefix = '02';
		
		private $urlify;
		private $xml2array;	
		private $directory;	
		
		
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
			$result = array();
			
			//если параметр один
			if (isset($params['@value'])){
				$result[] = array(
				'name' 	=> trim($params['@attributes']['name']),
				'value' => trim($params['@value'])
				);
			}
			
			foreach ($params as $param){							
				if (isset($param['@attributes']) && isset($param['@attributes']['name']) && isset($param['@value'])){					
					$result[] = array(
					'name' 	=> trim($param['@attributes']['name']),
					'value' => trim($param['@value'])
					);
				}
			}
			
			return $result;
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
		
		public static function getParamValueName($data, $param){		
			foreach ($data as $param_value){
				if ($param_value["@attributes"]["name"] == $param){
					return $param_value['@value'];
				}				
			}
			
			return false;
		}
		
		public function cron(){
			ini_set('memory_limit','2G');
			
			$this->load->model('catalog/product');
			
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
			
			$existentProducts = array();
			$existentOptions = array();
			
			foreach ($yml["price"]["items"]["item"] as $item){
			
				$sku = $this->getParamValue($item, 'id');
			
				if (!$this->model_catalog_product->checkIfSKUWasDeleted($sku)){
					
					if (!empty($item["param"])){
						foreach ($item["param"] as &$param){							
							if (isset($searchReplaceArray[$param["@attributes"]['name']])){
								$param["@attributes"]['name'] = $searchReplaceArray[$param["@attributes"]['name']];
								
								$attributesArray[$param["@attributes"]["name"]] = $searchReplaceArray[$param["@attributes"]["name"]];
								} else {
								$attributesArray[$param["@attributes"]["name"]] = '';
							}
						}
					}
					
					$images = array('image' => array());
					
					if (is_array($item['image'])){
						foreach ($item['image'] as $image){
							if ($image != 'photo.ksmua.com.ua'){
								$images['image'][] = $image;
							}
						}
						} else {
						if ($item["image"] != 'photo.ksmua.com.ua'){
							$images['image'][] = $item['image'];
						}
					}
					
					$available = false;
					if ($this->getParamValue($item, 'available') == true){
						$available = true;
					}
					
					
					$quantity = ($available)?3:0;
					if ($product_id = $this->model_catalog_product->findProductBySKU($this->supplerPrefix . $sku)){
						
						$product = $this->model_catalog_product->getProduct($product_id);
						
						if ($available){
							$quantity = 3 + $product['stock'];
							} else {
							$quantity = $product['stock'];
						}						
					}
				
					$xml['item'][] = array(
					'name' 				=> $item["name"],
					'ean'				=> $item["barcode"]['@value'],
					'mpn' 				=> $item["vendorCode"],
					'sku'				=> $sku,
					'category' 			=> 'ksmua',
					'brand' 			=> $item["vendor"],
					'price'				=> $product['dnup']?$product['price']:$this->currency->convert($item["price"], 'USD', 'UAH'),
					'priceUSD'			=> $item["price"],
					'quantity' 			=> $quantity,
					'images' 			=> $images,
					'description'		=> array('@cdata' => isset($item["description"])?$item["description"]:''),
					'param'				=> isset($item["param"])?$item["param"]:'',					
					);	
					
					//MKING SUPPLER PREFIX
					if (false && $product_id){						
						//UPDATE QUERIES
						$this->echoLine('[KSMUA] Нашли товар ' . $product_id . ' - ' . $sku);
						
						$existentProducts[] = $product_id;
						
						$quantity = ($available)?3:0;
						$this->db->query("UPDATE " . DB_PREFIX . "product SET supplier = '" . (int)$quantity . "' WHERE product_id = '" . (int)$product_id . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = stock + supplier WHERE product_id = '" . (int)$product_id . "'");
						
						if (!$product['dnup']){
						//	$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$this->currency->convert($item["price"], 'USD', 'UAH') . "' WHERE product_id = '" . (int)$product_id . "'");
						}
						
						} else {
						$this->echoLine('[KSMUA] Не нашли товар ' . $sku);
					}
					
					} else {
					$this->echoLine('[KSMUA] Пропускаем товар ' . $sku);				
				}
			}
			
			$unExistent = $this->model_hobotix_hoboparser->disableUnexsistentProducts($existentProducts, $existentOptions, $this->supplerPrefix);
			
			mkdir(DIR_SUPPLIERS . $this->supplerCode, 0755, true);
			
			$unexistentProductsString = '';
			foreach ($unExistent['unexistentProducts'] as $key => $value){
				$unexistentProductsString .= $value . PHP_EOL;
			}
			
			$unexistentProductsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'ksmua.unexistentproducts.csv';
			file_put_contents($unexistentProductsFile, $unexistentProductsString);
			
			$unexistentOptionsString = '';
			foreach ($unExistent['unexistentOptions'] as $key => $value){
				$unexistentOptionsString .= $value . PHP_EOL;
			}
			
			$unexistentOptionsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'ksmua.unexistentoptions.csv';
			file_put_contents($unexistentOptionsFile, $unexistentOptionsString);
			
			$xmlString = LaLit\Array2XML::createXML('ksmua', $xml)->saveXML();;					
			$xmlFile = DIR_SUPPLIERS . 'ksmua.converted.xml';
			
			//			var_dump($xmlString);
			file_put_contents($xmlFile, $xmlString);
			
			$attributesString = '';
			foreach ($attributesArray as $key => $value){
				$attributesString .= $key . ';' . $value . PHP_EOL;
			}
			
			$attributesFile = DIR_SUPPLIERS . 'ksmua.attributes.csv';
			file_put_contents($attributesFile, $attributesString);
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}