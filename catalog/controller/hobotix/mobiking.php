<?php
	class ControllerHobotixMobiking extends Controller {
		private $server = 'ftpclient.mobiking.com.ua';
		private $port = 2323;
		private $username = 'Site_VestInUa';
		private $password = "E.;~mtxt\*&2NG)b\PSa";
		
		private $deletedSKU = array();
		private $productsToSKU = array();
		private $productOptionsToSKU = array();
		
		private $queryQueueArray = array();
		
		//CRON2
		private $username2 = 'Site_VestInUa2';
		private $password2 = "povCoqO%aI7ho.F{~RGO";
		
		private $supplerPrefix = '01';
		private $supplerCode = 'mobiking';
		
		private $urlify;
		private $xml2array;	
		private $directory;	
		
		private $rate = false;
		
		private $passAttributes = array(
		'Категория товара',
		'Подкатегория товаров',
		'Наличие сенсора'
		);
		
		private $twoColorWords = array(
		'Grey Blue' => 'GreyBlue',
		'Dark Blue' => 'DarkBlue',
		'Dark Green' => 'DarkGreen',
		'Rose Flower' => 'RoseFlower',
		'Peony Red' => 'PeonyRed',
		'Peony White' => 'PeonyWhite',
		'Peony Pink' => 'PeonyPink',
		'Mint Mohito' => 'MntMohito',
		'Strawbery Mohito' => 'StrawberyMohito',
		'Watermelon Mohito' => 'WatermelonMohito',
		'Black/Blue' => 'BlackBlue',
		'Violet/Yellow' => 'VioletYellow',
		'Green/Red' => 'GreenRed',
		'Black/White' => 'BlackWhite',
		'Blue/White' => 'BlueWhite',
		'Mint/Violet' => 'MntViolet',
		'Pink/White' => 'PinkWhite',
		'Violet/White' => 'VioletWhite',
		'Alaskan Blue' => 'AlaskanBlue',
		'Pine Green' => 'PineGreen',
		'Cosmos Blue' => 'CosmosBlue',
		'Lavender Grey' => 'LavenderGrey',
		'Marina Green' => 'MarinaGreen',
		'Pink Sand' => 'PinkSand',
		'Rose Red' => 'RoseRed',
		'Olive Green' => 'OliveGreen',
		'Light Green' => 'LightGreen',
		'Hot Pink' => 'HotPink',
		'Midnight Blue'=> 'MdnightBlue',
		'Horizon Blue' => 'HorizonBlue',
		'Canary Yellow' => 'CanaryYellow',
		'Ice Sea Blue' => 'IceSeaBlue',
		'Pinery Green' => 'PineryGreen',
		'Granny Grey' => 'GrannyGrey',
		'Marine Blue' => 'MarineBlue',
		'Dragon Fruit' => 'DragonFruit',
		'Pine Green' => 'PineGreen',
		'Pinery Green' => 'PineryGreen',
		'Sapphire Blue' => 'SapphireBlue',
		'Space Blue' => 'SpaceBlue',
		'Army Green' => 'ArmyGreen',
		'Blue Cobalt' => 'BlueCobalt',
		'Blue Grey' => 'BlueGrey',
		'Bright Pink' => 'BrightPink',
		'Camellia Red' => 'CamelliaRed',
		'Corn Flower' => 'CornFlower',
		'Dark Olive' => 'DarkOlive',
		'Deep Lake Blue' => 'DeepLakeBlue',
		'Denim Blue' => 'DenimBlue',
		'FireFly Rose' => 'FireFlyRose',
		'Forest Green' => 'ForestGreen',
		'Light Blue' => 'LightBlue',
		'Light Pink' => 'LightPink',
		'Camellia Red' => 'CamelliaRed',
		'Corn Flower' => 'CornFlower',
		'Dark Purple' => 'DarkPurple',
		'Lavender Grey' => 'LavenderGrey',
		'Mellow Yellow' => 'MellowYellow',
		'Royal Blue' => 'RoyalBlue',
		'Shine Olive' => 'ShineOlive',
		'Corn Flower' => 'CornFlower',
		'Midnight Blue' => 'MdnightBlue',
		'Lake Blue' => 'LakeBlue',
		'Ice sea blue' => 'Iceseablue',
		'Shine Green' => 'ShineGreen',
		'Blue Horizon' => 'BlueHorizon',
		'Delft Blue' => 'DelftBlue',
		'Pacific Green' => 'PacificGreen',
		'Corn Flower' => 'CornFlower',
		'Neon Green' => 'NeonGreen',
		'Mint Green' => 'MntGreen',
		'Military Desert' => 'MlitaryDesert',
		'Military Khaki' => 'MlitaryKhaki',
		'Blue Green' => 'BlueGreen',
		'Mint Green' => 'MntGreen',
		'Grey Blue' => 'GreyBlue',
		'Black with Window' => 'BlackwithWindow',
		'Gold with Window' => 'GoldwithWindow',
		'White/Pearl with Window' => 'WhitePearlwithWindow',
		'Silver Shine' => 'SilverShine',
		'Sun Shine' => 'SunShine',
		'Milky Way' => 'MlkyWay',
		'Dark Blue (Bears)' => 'DarkBlueBears',
		'Dark Blue (Gnome)' => 'DarkBlueGnome',
		'Blue (Gnome)' => 'BlueGnome',
		'Blue (Pair )' => 'BluePair',
		'Violet (Bears)' => 'VioletBears',
		'Red (Gnome)' => 'RedGnome',
		'Peony Blue' => 'PeonyBlue',
		'Peony Pink' => 'PeonyPink',
		'Peony White' => 'PeonyWhite',
		'Peony Red' => 'PeonyRed',
		'Space Grey' => 'SpaceGrey',
		'Green/Red' => 'GreenRed',
		'Mint/Violet' => 'MntViolet',
		'Light Green' => 'LightGreen',
		'Sun Yellow' => 'SunYellow',
		'Sea Blue' => 'SeaBlue',
		'Pollen Yellow' => 'PollenYellow',
		'Ocean Blue' => 'OceanBlue',
		'Flash Yellow' => 'FlashYellow',
		'Charcoal Grey' => 'CharcoalGrey',
		'Marine Green (without logo)' => 'MarineGreenwithoutlogo',
		'Ocean Mint' => 'OceanMnt',
		'Deep Lake Blue' => 'DeepLakeBlue',
		'Star Wars' => 'StarWars',
		'Rolls-Royce' => 'RollsRoyce',
		'Ghost Rider' => 'GhostRider',
		'Color Flowers' => 'ColorFlowers',
		'Magic Flowers' => 'MagicFlowers',
		'Fantasy Flowers' => 'FantasyFlowers',
		'Winnie The Pooh' => 'WinnieThePooh',
		'Minnie Mouse' => 'MnnieMouse',
		'Mickey Mouse' => 'MckeyMouse',
		'Mint' => 'Mnt'
		);
		
		private $optionCompareCategories = array(
		'Чехлы',
		'Защитные пленки и стекла'
		);
		
		private $optionCompareAttributes = array(
		'color' => 'Цвет',		
		);
		
		private $removeAttributes = array(
		'Цвет',		
		);
		
		private $optionCompareAttributesExcludeValue = array(
		'color' => 'С рисунком'
		);
		
		private $optionCompareAttributesExcludeValueWords = array(
		'Face to face',
		'2 Fingers'
		);
		
		private function parseTwoColorWords($line){
			
			foreach ($this->twoColorWords as $key => $value){
				$line = str_replace($key, $value, $line);
			}
			
			return $line;
		}
		
		private $collisionWords = array(
		'Max','Pro','Plus','+','Lite','Realme', 'iPhone', 'Note','Z','Prime','E','2016','2017','2018','2019','2020','2021','2021','2023','2022','Ultra', 'redmi','mi','galaxy','honor','pro','max','ultra','lite','prime','se', 'Mat', 'M6T','Y6P','Iphone X','Iphone XS','Iphone 7','Iphone 8','Y5P', 'zoom','5G','SE','alpha','M-Design','R-Design','Y5 (2019)', 'P Smart S/Y8P', 'Y5 (2018)', 'Y7 (2019)', 'Y5P', 'Y7 (2019)', 'Y6 (2019)', 'Y6P', 'Redmi 9', 'Redmi 9a', 'Redmi 9c', 'Cosmos', 'Lavender', 'Sand', 'Ultra','A015 (A01)','M015 (M01)','A01 Core','A02s', 'A02', 'A015 (A01) / M015 (M01)', 'A02', 'A01 core', 'A02s', 'Galaxy A02', 'A01 core', 'Galaxy A02s');
		
		private $collisionPrefixes = array('"','A','E','I','O','U','Y','B','C','D','F','G','H','J','K','L','M','N','P','Q','R','S','T','V','W','X','Z');		
		
		public function createCollisionArray(){
			
			foreach ($this->collisionModels as $model){
				foreach ($this->collisionPrefixes as $prefix){
					$this->collisionWords[] = $model . mb_strtolower($prefix);
					$this->collisionWords[] = mb_strtolower($prefix) . $model;
					$this->collisionWords[] = $model . $prefix;
					$this->collisionWords[] = $prefix . $model;
				}
				
				for ($i=1; $i<=1000; $i++){
					$this->collisionWords[] = $model . $i;
					$this->collisionWords[] = $i . $model;
				}
			}
			
			for ($i=1; $i<=100; $i++){
				$this->collisionWords[] = $i;
			}
			
			for ($i=10; $i<=1000; $i++){
				$this->collisionWords[] = $i;
				foreach ($this->collisionPrefixes as $prefix){
					$this->collisionWords[] = $i . mb_strtolower($prefix);
					$this->collisionWords[] = mb_strtolower($prefix) . $i;
					$this->collisionWords[] = $i . $prefix;
					$this->collisionWords[] = $prefix . $i;
				}
			}
		}
		
		
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
		
		private function parseYMLParamArrayToOneDimension($params){
			$array = $this->parseYMLParamArray($params);
			$result = array();
			
			foreach ($array as $item){
				$result[$item['name']] = $item['value'];
			}
			
			return $result;
			
		}
		
		private function checkCollision($text1, $text2){
			
			foreach ($this->collisionWords as $word){
				
				if (stripos($text1, ' ' . $word . ' ') === false && !(stripos($text2, ' ' . $word . ' ') === false)){
					return false;
				}
				
				if (stripos($text2, ' ' . $word . ' ') === false && !(stripos($text1, ' ' . $word . ' ') === false)){
					return false;
				}
				
				if (stripos($text1, ' ' . $word) === false && !(stripos($text2, ' ' . $word) === false)){
					return false;
				}
				
				if (stripos($text2, ' ' . $word) === false && !(stripos($text1, ' ' . $word) === false)){
					return false;
				}			
			}
			
			return true;
		}
		
		private function compareParams($item1, $item2){
			
			$item1 = $this->parseYMLParamArrayToOneDimension($item1['param']);					
			$item2 = $this->parseYMLParamArrayToOneDimension($item2['param']);					
			$difference = array_diff($item1, $item2);						
			
			return $difference;
			
		}
		
		private function compareParamsCheck($item1, $item2){
			
			$difference = $this->compareParams($item1, $item2);
			
			if (count($difference) == 1){
				foreach ($this->optionCompareAttributes as $key => $oCA){
					if (isset($difference[$oCA]) && $difference[$oCA]){						
						return $oCA;
					}
				}
			}
			
			return false;
		}
		
		private function getComparedAttributeValue($item, $oCA){
			
			$params = $this->parseYMLParamArrayToOneDimension($item['param']);				
			
			if (isset($params[$oCA])){
				return $params[$oCA];
				} else {
				return false;
			}
		}
		
		private function getComparedParamKey($oCA){
			
			foreach ($this->optionCompareAttributes as $key => $value){
				if ($value == $oCA){
					return $key;
				}
			}
			
			return false;
		}
		
		private function parseNameWithNumberColor($name){
			$color = false;
			
			if (stripos($name, '№')){
				$exploded = explode('№', $name);
				
				if (count($exploded) == 2){
					$color = $exploded['1'];
					
					$this->echoLine('NUMNAME: Название с нумерацией ' . $name . ', цвет ' . $color);
				}
			}
			
			if ($color){
				$tStr = '№' . $color;
				return array(
				'name'  => trim(str_replace( $tStr, '', $name)),
				'color' => $color
				);
				} else {
				return false;
			}
		}
		
		private function regroupItems($itemsArray){
			$result = array();			
			$alreadyAddedSecondarySKU = array();
			$itemsArray2 = $itemsArray;	
			
			$itemsToCollapse = array();
			
			$count = count($itemsArray);
			$i = 1;
			foreach ($itemsArray as $item1){
				echoLine('[regroupItems]' . $i .'/' . $count . ' Обработка ' . $item1['name']);
				$i++;
				
				$__isCollapsed = false;			
				if (!in_array($item1['sku'], $alreadyAddedSecondarySKU)){
					if (in_array($item1['category_text'], $this->optionCompareCategories)){
						
						foreach ($itemsArray2 as $item2){
							
						//	echoLine('Начали проверку для ' . $item1['name'] . ' -> ' . $item2['name']);
							
							if (in_array($item2['category_text'], $this->optionCompareCategories)){
								
								if ($item1['sku'] != $item2['sku'] && $item1['price'] == $item2['price'] && $item1['brand'] == $item2['brand']){
									
									$similar_rating = similar_text($item1['name'], $item2['name'], $similar_percent);
									
								//	echoLine('Сравниваем ' . $item1['name'] . ', ' . $item2['name'] . ': ' . $similar_percent);
									
									if ($similar_percent > 80){										
										
										$oCA = $this->compareParamsCheck($item1, $item2);
										
									//	$this->echoLine($item1['sku'] . ':' . $item2['sku'] . ' - ' . $oCA);
										
										if ($oCA && $this->checkCollision($item1['name'], $item2['name'])){
											
										//	echoLine('Коллизия пройдена ' . $item1['name'] . ', ' . $item2['name']);
											
											//INIT ARRAY								
											$__MAIN_SKU  = $item1['sku'];
											$__MAIN_NAME = $item1['name'];
											
											if (!is_array($itemsToCollapse[$__MAIN_SKU])){
												$itemsToCollapse[$__MAIN_SKU]['items'] = array();
												$itemsToCollapse[$__MAIN_SKU]['skus'] = array();
											}
											
										//	$this->echoLine('SKU MAIN: ' . $__MAIN_SKU . ', ' . $item1['name']);
										//	$this->echoLine('SKU SECOND: ' . $item2['sku'] . ', ' . $item2['name']);
										//	$this->echoLine('---');
											
											$item1[$this->getComparedParamKey($oCA)] = $this->getComparedAttributeValue($item1, $oCA);
											$item2[$this->getComparedParamKey($oCA)] = $this->getComparedAttributeValue($item2, $oCA);
											
											$item1['original_sku'] = $item1['sku'];
											$item2['original_sku'] = $item2['sku'];
											$item2['sku'] = $__MAIN_SKU;										
											
											if (!in_array($__MAIN_SKU, $itemsToCollapse[$__MAIN_SKU]['skus'])){
												$itemsToCollapse[$__MAIN_SKU]['items'][] = $item1;	
												$itemsToCollapse[$__MAIN_SKU]['skus'][] = $__MAIN_SKU;	
										//		$this->echoLine('Главный SKU: ' . $__MAIN_SKU);
											}
											
											$itemsToCollapse[$__MAIN_SKU]['items'][] = $item2;
										//	$this->echoLine('Добавлено: ' . $item2['original_sku'] .'->'. $item2['sku']);
											
											$alreadyAddedSecondarySKU[] = $item2['original_sku'];
											
											$__isCollapsed = true;										
											
											} else {
											
										//	echoLine('Коллизия не пройдена ' . $item1['name'] . ', ' . $item2['name']);
											
										}
										
									}
								}
							}
						}
					}
					
					if (!$__isCollapsed){
						$result[] = $item1;
					}
					
				}
			}
			
			
			//Подмена основного SKU, в случае если найден дочерний товар
			$replaceSKUArray = array();
			foreach ($itemsToCollapse as $sku => $collapsedItems){
				foreach ($collapsedItems['items'] as $collapsedItem){				
					if (!empty($collapsedItem['original_sku']) && $collapsedItem['original_sku'] != $sku){
						if ($product_id = $this->findProductBySKU($this->supplerPrefix . $collapsedItem['original_sku'])){
							$this->echoLine('   Проблема: Нашли дочерний товар, заменяем текущий на SKU ' . $collapsedItem['original_sku']);
							$replaceSKUArray[$sku] = $collapsedItem['original_sku'];
							break;
						}
					}
				}			
			}
			
			unset($collapsedItems);
			unset($collapsedItem);
			unset($sku);
			
			if ($replaceSKUArray){
				
				foreach ($replaceSKUArray as $skuFromReplace => $skuToReplace){
					$recollapsedItems = array();
					
					foreach ($itemsToCollapse[$skuFromReplace]['items'] as $collapsedItem){
						$collapsedItem['sku'] = $skuToReplace;
						$recollapsedItems[] = $collapsedItem;
					}
					
					unset($itemsToCollapse[$skuFromReplace]);
					$itemsToCollapse[$skuToReplace]['items'] = $recollapsedItems;
				}			
			}
			
			unset($collapsedItems);
			unset($collapsedItem);
			unset($sku);
			
			foreach ($itemsToCollapse as $sku => $collapsedItems){
				
				$this->echoLine('SKU: ' . $sku);				
				
				//Добавляем к картинкам основного товара по одной картинке из дочерних
				if ($__MAIN_PRODUCT_ID = $this->findProductBySKU($this->supplerPrefix . $sku)){
					$this->echoLine('Все заебись: Основной товар ' . $sku);
					$options = $this->model_catalog_product->getProductOptions($__MAIN_PRODUCT_ID);									
				}	
				
				$picturesToOptionSKU = array();
				foreach ($collapsedItems['items'] as $collapsedItem){
					$this->echoLine('   cI: ' . $collapsedItem['name']);
					
					//Добавляем к картинкам основного товара по одной картинке из дочерних
					if (!empty($collapsedItem['original_sku']) && $collapsedItem['original_sku'] != $sku){
						if ($product_id = $this->findProductBySKU($this->supplerPrefix . $collapsedItem['original_sku'])){
							$this->echoLine('   Проблема: Нашли дочерний товар, нужно удалить ' . $collapsedItem['original_sku']);
						}
					}			
					
					//Добавляем к картинкам основного товара по одной картинке из дочерних
					$collapsedItems['items'][0]['pictures']['picture'][] = $collapsedItem['pictures']['picture'][0];															
					$collapsedItems['items'][0]['pictures']['picture'] = $collapsedItems['items'][0]['pictures']['picture'];	
					
					$mainPictureCount = count($collapsedItems['items'][0]['pictures']['picture']);
					$picturesToOptionSKU[$collapsedItem['original_sku']] = $collapsedItems['items']['pictures']['picture'][0];
					
					//Если товар существует, то пробуем найти опции по значению и добавить артикул и картинку
					if ($__MAIN_PRODUCT_ID) {
						
						// Обновление SKU
						$this->queryQueue("UPDATE " . DB_PREFIX . "product_option_value SET sku = optsku WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "'");
						
						foreach ($options as $option) {
							if ($option['option_id'] == '14') {
								foreach ($option['product_option_value'] as $product_option_value) {
									if ($product_option_value['name'] == $collapsedItem['color'] || $product_option_value['optsku'] == $collapsedItem['original_sku']) {
										$this->echoLine('   Нашли опцию цвета, апдейт ' . $collapsedItem['color']);
										
										$this->queryQueue("UPDATE " . DB_PREFIX . "product_option_value SET quantity = '" . (int)$collapsedItem['quantity'] . "' WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "'");
									}
								}
							}
						}
						
						// Подсчет общего количества товара и обновление в таблице product
						// $this->queryQueue("UPDATE " . DB_PREFIX . "product p SET quantity = (SELECT SUM(quantity) FROM " . DB_PREFIX . "product_option_value WHERE product_id = p.product_id GROUP BY product_id) WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "'");

						$this->queryQueue("UPDATE " . DB_PREFIX . "product p SET supplier = (SELECT SUM(quantity) FROM " . DB_PREFIX . "product_option_value WHERE product_id = p.product_id GROUP BY product_id) WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "'");

						$this->queryQueue("UPDATE " . DB_PREFIX . "product SET quantity = supplier + stock WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "'");
						
						// Подсчет итогового количества товара для логирования
						$query = $this->db->query("SELECT SUM(quantity) AS total_quantity FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "'");
						$totalQuantity = $query->row['total_quantity'];
						
						// Путь к лог-файлу
						$logFile = DIR_SUPPLIERS . 'product_quantity_log.log';
						
						// Запись в лог-файл
						$logMessage = date('Y-m-d H:i:s') . " - Product ID: " . $__MAIN_PRODUCT_ID . " - Total Quantity: " . $totalQuantity . "\n";
						file_put_contents($logFile, $logMessage, FILE_APPEND);
					}
				}
				
				//И пробуем обновить/найти картинки из основного
				if ($__MAIN_PRODUCT_ID){
					$this->queryQueue("UPDATE " . DB_PREFIX . "product_option_value SET o_v_image = (SELECT image FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "') WHERE product_id = '" . $__MAIN_PRODUCT_ID . "' AND optsku = '" . $this->db->escape($sku) . "' AND LENGTH(o_v_image) < 2");	
					
					//COUNT product_images
					$__COUNT_MAIN_PRODUCT_IMAGES = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "'")->row['total'];
					$__MAIN_PRODUCT_IMAGES = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "' ORDER BY sort_order ASC")->rows;
					
					$iterator = count($collapsedItems['items']);
					foreach ($collapsedItems['items'] as $collapsedItem){
						
						if ($collapsedItem['original_sku'] != $sku && isset($__MAIN_PRODUCT_IMAGES[$__COUNT_MAIN_PRODUCT_IMAGES - $iterator]['image'])){													
							$this->queryQueue("UPDATE " . DB_PREFIX . "product_option_value SET o_v_image = '" . $this->db->escape($__MAIN_PRODUCT_IMAGES[$__COUNT_MAIN_PRODUCT_IMAGES - $iterator]['image']) . "' WHERE product_id = '" . (int)$__MAIN_PRODUCT_ID . "' AND optsku = '" . $this->db->escape($collapsedItem['original_sku']) . "' AND LENGTH(o_v_image) < 2");
						}
						
						$iterator--;
					}					
					
				}
				
				unset($collapsedItem);
				foreach ($collapsedItems['items'] as $collapsedItem){
					$result[] = $collapsedItem;
				}
				
			}					
			
			return $result;
		}
		
		private function queryQueue($query){
			$this->queryQueueArray[] = $query;
		}
		
		private function clearQueryQueue(){
			$this->queryQueueArray = array();
		}
		
		private function doUpdateQueryQueue(){
			
			$count = count($this->queryQueueArray);
			echoLine('[QUERYQUEUE] Всего запросов обновления: ' . $count);
			
			$i = 1;
			foreach ($this->queryQueueArray as $query){
				try{
					echoLine('[QUERYQUEUE] ' . $i . '/' . $count . ': ['. $query .']');
					$this->db->query($query);
				
				} catch (Exception $e){
					echoLine('[QUERYQUEUE] Exception ' . $e->getMessage());
					sleep(2);
					$this->db->query($query);
				}
				$i++;
			}
			
			$this->clearQueryQueue();
		}
		
		private function getLastColorWord($string){
			$string = trim($string);
			
			$last_word_start = strrpos($string, ' ') + 1;
			$last_word = substr($string, $last_word_start);
			
			$last_word = trim($last_word);
			
			return str_replace('№', '', $last_word);
		}
		
		private function checkIfExcludedMainCompareParamValue($item){
			if (!empty($item["Свойства"])){
				foreach ($item["Свойства"] as $parameters){
					foreach ($parameters as $param){
						
						foreach ($this->optionCompareAttributes as $key => $value){							
							if ($param["@attributes"]["Name"] == $value){
								
								if (isset($this->optionCompareAttributesExcludeValue[$key]) && $this->optionCompareAttributesExcludeValue[$key] == $param["@attributes"]["Value"]){
									
									foreach ($this->optionCompareAttributesExcludeValueWords as $excludedValueWord){
										if (mb_stripos($item['Наименование'], $excludedValueWord) !== false){
											return array(
											'name'  => trim(str_replace(array('№', $excludedValueWord), '', $item['Наименование'])),
											'color' => $excludedValueWord
											);
										}
									}
									
									return array(
									'name'  => trim(str_replace($this->getLastColorWord($item['Наименование']), '', $item['Наименование'])),
									'color' => $this->getLastColorWord($item['Наименование'])
									);
								}
								
							}							
						}
						
					}
					
				}
			}
			
			return false;
		}
		
		private function reparseAndDeleteParam($itemsArray){
			$result = array();
			
			foreach ($itemsArray as $item){
				
				if (!empty($item["param"])){
					$resultParam = array();
					foreach ($item["param"] as $param){
						if (!in_array($param["@attributes"]["name"], $this->removeAttributes)){
							$resultParam[] = $param;
						}
					}
					$item["param"] = $resultParam;
				}
				
				$result[] = $item;			
				
				
			}
			
			return $result;
			
		}
		
		private function getPBUSDRate(){
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://api.privatbank.ua/p24api/pubinfo?exchange&json&coursid=5');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$content = curl_exec($curl);
			curl_close($curl);
			
			$json_array = json_decode($content, true);
			foreach ($json_array as &$json){
				if ($json['base_ccy'] == 'UAH' && $json['ccy'] == 'USD'){
					if ((float)$json['sale']) {
						$this->rate = (float)$json['sale'];
					}			
				}
			}
			
			if (!$this->rate){
				$this->echoLine('[XML] Не могу получить курс доллара! Грусть-печаль.');
				die();
			}
			
			$this->echoLine('[XML] Получили курс доллара к грн: ' . $this->rate);
		}
		
		
		private function loadCollisionArrayFromSettings(){
			
			$collisionArray = $this->config->get('config_mobiking_collisionWords');
			$collisionArray = explode(',', $collisionArray);
			
			if (is_array($collisionArray)){
				$this->collisionWords = array();
				
				foreach ($collisionArray as $word){
					$this->collisionWords[] = trim($word);
				}
				
			}			
			
			return $this;
		}
		
		private function loadoptionCompareAttributesExcludeValueWordsArrayFromSettings(){
			
			$optionCompareAttributesExcludeValueWords = $this->config->get('config_mobiking_optionCompareAttributesExcludeValueWords');
			$optionCompareAttributesExcludeValueWords = explode(',', $optionCompareAttributesExcludeValueWords);
			
			if (is_array($optionCompareAttributesExcludeValueWords)){
				$this->optionCompareAttributesExcludeValueWords = array();
				
				foreach ($optionCompareAttributesExcludeValueWords as $word){
					$this->optionCompareAttributesExcludeValueWords[] = trim($word);
				}
				
			}			
			
			return $this;
		}
		
		
		private function findProductBySKU($sku){
			
			if (!empty($this->productsToSKU[$sku])){
				return $this->productsToSKU[$sku];
			}
			
			return false;
		}
		
		private function findProductOptionBySKU($sku){
			
			if (!empty($this->productOptionsToSKU[$sku])){
				return $this->productOptionsToSKU[$sku];
			}
			
			return false;
			
		}
		
		private function checkIfSKUWasDeleted($sku){
			
			if (in_array($sku, $this->deletedSKU)){
				return true;
			}
			
			if (in_array($sku, $this->deletedSKU)){
				return true;
			}
			
			return false;
		}
		
		
		public function cron2(){
			
			ini_set('memory_limit','2G');
			
			$this->load->model('catalog/product');
			$this->load->model('hobotix/hoboprice');
			
			$this->config->set('config_language_id', 1);
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			
			$this->getPBUSDRate();
			$this->loadoptionCompareAttributesExcludeValueWordsArrayFromSettings()->loadCollisionArrayFromSettings()->createCollisionArray();
			
			require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'XML2Array2.php');
			require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'Array2XML.php');
			require_once( DIR_SYSTEM . 'library/urlify.php');			
			
			
			if ($ftpConnection = ftp_connect($this->server, $this->port)){
				$this->echoLine('[FTP] Подключились, продолжаем');				
				} else {
				$this->echoLine('[FTP] Нет подключения');
				die();
			}
			
			if ($loginResult  = ftp_login($ftpConnection, trim($this->username2), trim($this->password2))){
				$this->echoLine('[FTP] Авторизовались, продолжаем');				
				} else {
				$this->echoLine('[FTP] Нет подключения');
				die();
			}
			
			if ($pasvResult  = ftp_pasv($ftpConnection, true)){
				$this->echoLine('[FTP] PASV режим включен, продолжаем');	
				} else {
				$this->echoLine('[FTP] Ошибка PASV');
				die();
			}
			
			$fileList = ftp_nlist($ftpConnection, '/');	
			rsort($fileList);
			
			if (!isset($fileList[0])){			
				$this->echoLine('[FTP] Странный список файлов ');
				var_dump($fileList);
				die();
			}
			
			$fileList = ftp_nlist($ftpConnection, '.');
			
			
			$mostRecent = array(
			'time' => 0,
			'file' => null
			);
			
			$ftpCount = count($fileList);
			$ftpCounter = 1;
			foreach ($fileList as $file) {				
				$time = ftp_mdtm($ftpConnection, $file);
				
				if ($time > $mostRecent['time']) {
					$this->echoLine('[FTP] ' . $ftpCounter .'/'. $ftpCount . ' Файлик ' . $file . ' новее, время ' . $time);
					$mostRecent['time'] = $time;
					$mostRecent['file'] = $file;
					} else {
					$this->echoLine('[FTP] ' . $ftpCounter .'/'. $ftpCount . ' Файлик ' . $file . ' старше, время ' . $time);
					
					if ($time < strtotime('-3 day')){
						$this->echoLine('[FTP] ' . $ftpCounter .'/'. $ftpCount . ' Файлик ' . $file . ' старше чем 3 дня, удаляем, время ' . $time);
						ftp_delete($ftpConnection, $file);
					}
				}
				
				$ftpCounter++;
			}
			
			$lastModifiedFileName = $mostRecent['file'];
			$localFile = DIR_CACHE . trim($lastModifiedFileName,'/');
			
			if ($fileGetResult = ftp_get($ftpConnection, $localFile, $mostRecent['file'], FTP_BINARY)){
				$this->echoLine('[FTP] Получаем файл ' . $lastModifiedFileName);
				} else {
				$this->echoLine('[FTP] Сбой получения файла ' . $lastModifiedFileName);
				die();
			}
			
			$zip = new ZipArchive;
			$zipRes = $zip->open($localFile);
			if ($zipRes === true) {
				$zip->extractTo(pathinfo(realpath($localFile), PATHINFO_DIRNAME));
				$zip->close();
				$this->echoLine('[ZIP] Распаковали ' . $localFile);
				} else {
				$this->echoLine('[ZIP] Не могу распаковать ' . $localFile);
				die();
			}						
			
			$ymlFile = pathinfo(realpath($localFile), PATHINFO_DIRNAME) . '/' . pathinfo(realpath($localFile), PATHINFO_FILENAME) . '.xml';	
			
			if (!file_exists($ymlFile)){
				$this->echoLine('[XML] Чудеса какие-то, а где XML ' . $ymlFile);
				die();				
			}
			
			if ($ymlContents = file_get_contents($ymlFile)){
				$this->echoLine('[XML] Загрузили XML ');	
				} else {
				$this->echoLine('[XML] Не получилось загрузить XML ');	
				die();
			}
		
			
		//	$ymlContents =  file_get_contents(DIR_APPLICATION . '/mobiking_test.xml');		
			
			$xmlFile = DIR_SUPPLIERS . 'mobiking.original.xml';
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
			
			echoLine('[HOBOPARSER] Удаленные SKU');
			$this->deletedSKU = $this->model_hobotix_hoboparser->initDeleted();
			
			echoLine('[HOBOPARSER] Код товара к SKU');
			$this->productsToSKU = $this->model_hobotix_hoboparser->initProductSKU();
			
			echoLine('[HOBOPARSER] Опции к SKU');
			$this->productOptionsToSKU = $this->model_hobotix_hoboparser->initProductOptionsToSKU($this->supplerPrefix);		
			
			echoLine('[HOBOPARSER] ' . memory_get_usage(true) . ' байт');
			
			$attributesArray = array();
			$categoriesArray = array();
			$passedArray = array();
			$itemsReparsedNameWithPrintColor = array();
			
			$existentProducts = array();
			$existentOptions = array();
			
			foreach ($yml["Tovary"]["Item"] as $item){
				
				/*	if (strpos($item['Наименование'], 'Gelius Bumper Mat Case for Samsung G780 (S20 FE)') === false){
					continue;
				} */
				
				$item["Код"] = trim($item["Код"]);
				
				if (!$this->checkIfSKUWasDeleted($item["Код"]) && !$this->checkIfSKUWasDeleted($this->supplerPrefix . $item["Код"])){
					$item['param'] = array();
					
					$pictures = array();
					if (is_array($item["Картинки"]["picture"])){
						foreach ($item["Картинки"]["picture"] as $picture){
							$pictures[] = $picture;
						}
						} else {
						$pictures[] = $item["Картинки"]["picture"];
					}
					
					
					if (!empty($item["Свойства"])){
						foreach ($item["Свойства"] as $parameters){
							
							foreach ($parameters as $param){
								
								if (!in_array($param["@attributes"]["Name"], $this->passAttributes)){
									
									$attribute_name = $param["@attributes"]["Name"];
									if (isset($searchReplaceArray[$param["@attributes"]["Name"]])){
										$attribute_name = $searchReplaceArray[$param["@attributes"]["Name"]];
										
										$attributesArray[$param["@attributes"]["Name"]] = $searchReplaceArray[$param["@attributes"]["Name"]];
										
										} else {
										$attributesArray[$param["@attributes"]["Name"]] = '';
									}
									$item['param'][] = array(
									"@value" => $param["@attributes"]["Value"],
									"@attributes" => array(
									"name" => $attribute_name
									)
									);																		
									
								}	
							}
						}						
					}
					
					//ЦВЕТА ИЗ ДВУХ СЛОВ
					$item["Наименование"] = $this->parseTwoColorWords($item["Наименование"]);
					
					//Попытка преобразовать принты	
					if ($reparsedNameWithNumber = $this->parseNameWithNumberColor($item["Наименование"])){
						$item["Наименование"] = $reparsedNameWithNumber['name'];												
						
						$rebuildItemParam = array();
						foreach ($item['param'] as $itemParam){
							if ($itemParam["@attributes"]["name"] == $this->optionCompareAttributes['color']){
								$itemParam["@value"] = $reparsedNameWithNumber['color'];
							}						
							
							$rebuildItemParam[] = $itemParam;
						}
						
						$item['param'] = $rebuildItemParam;					
						} elseif ($reparsedNameWithPrintColor = $this->checkIfExcludedMainCompareParamValue($item)){
						
						if (!stripos($item["Наименование"], '№')){
							$itemsReparsedNameWithPrintColor[] = $item["Наименование"];
						}
						
						$this->echoLine('[MAGIC] С рисунком! Название ' . $item["Наименование"]);
						
						$item["Наименование"] = $reparsedNameWithPrintColor['name'];	
						
						$rebuildItemParam = array();
						foreach ($item['param'] as $itemParam){
							if ($itemParam["@attributes"]["name"] == $this->optionCompareAttributes['color']){
								$itemParam["@value"] = $reparsedNameWithPrintColor['color'];
							}						
							
							$rebuildItemParam[] = $itemParam;
						}
						
						$item['param'] = $rebuildItemParam;
						
					}
					
					$categoriesArray[$item['КатегорияID']] = $item['Категория'];
					
					$quantity = ($item["Остаток"] > 900)?3:$item["Остаток"];
					$product_id = false;
					$product    = false;
					if ($product_id = $this->findProductBySKU($this->supplerPrefix . $item["Код"])){
						
						$product = $this->model_catalog_product->getExplicitProduct($product_id);
						
						if ($item["Остаток"] > 900){
							$quantity = 3 + $product['stock'];
							} else {
							$quantity = $item["Остаток"] + $product['stock'];
						}						
					}
					
					/*	if ($this->supplerPrefix . $item["Код"] == '0100000079876'){
						var_dump($item);
						var_dump($product);
						die();
						}
					*/
					
					//Опция
					$product_option_value_id = false;
					$option = false;
					if ($product_option_value_id = $this->findProductOptionBySKU($this->supplerPrefix . $item["Код"])){
						
						$option = $this->model_catalog_product->getProductOptionValue($product_option_value_id);
						
						if ($item["Остаток"] > 900){
							$quantity = 3 + $option['stock'];
							} else {
							$quantity = $item["Остаток"] + $option['stock'];
						}						
					}
					
					$special = '';
					if ($product_id && $product && $product['dnup']){
						$special = $this->model_catalog_product->getProductSpecialActual($product_id);
						if ($special && (float)$special['price'] > 0){
							$special = (int)round($special['price']);
						}
					}
					
					$xml['item'][] = array(
					'name' 				=> $item["Наименование"],
					'sku' 				=> $item["Код"],
					'category' 			=> 'mobiking',
					'brand' 			=> $item["Производитель"],
					'cost'				=> $item["ЦенаЗакупки"],
					'recommendedprice'	=> $product['dnup']?$product['price']:$item["ЦенаРРЦ"],
					'special'			=> $special,
					'quantity' 			=> $quantity,
					'color'				=> '',
					'original_sku'		=> '',
					'category_id'		=> $item['КатегорияID'],
					'category_text'		=> $item['Категория'],
					'ean'  				=> !empty($item['EAN13'])?$item['EAN13']:'',
					'pictures' 			=> array('picture' => $pictures),
					'description'		=> array('@cdata' => isset($item["ДопОписание"])?$item["ДопОписание"]:''),
					'param'				=> $item['param']										
					);												
					
					//MKING SUPPLER PREFIX
					if ($product_id){						
						//UPDATE QUERIES
						$this->echoLine('[MKNG] Нашли товар ' . $product_id . ' - ' . $item["Код"]);
						
						$existentProducts[] = $product_id;
						
						$quantity = ($item["Остаток"] > 900)?3:$item["Остаток"];
						$this->queryQueue("UPDATE " . DB_PREFIX . "product SET supplier = '" . (int)$quantity . "' WHERE product_id = '" . (int)$product_id . "'");
						$this->queryQueue("UPDATE " . DB_PREFIX . "product SET quantity = supplier + stock WHERE product_id = '" . (int)$product_id . "'");
						
						if (!$product['dnup']){
							$this->queryQueue("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$item["ЦенаРРЦ"] . "' WHERE product_id = '" . (int)$product_id . "'");						
						}
						
						$history = array(
						'product_id' 	=> $product_id,
						'suppler_code' 	=> $this->supplerCode,
						'price'			=> $item["ЦенаЗакупки"] * $this->rate
						);
						
						$this->model_hobotix_hoboprice->addPrice($history);
						
						} else {
						$this->echoLine('[MKNG] Не нашли товар ' . $item["Код"]);
					}
					
					//Обновление наличия опции
					if ($product_option_value_id){
						//UPDATE QUERIES
						$this->echoLine('[MKNG] Нашли товар - опцию ' . $product_option_value_id . ' - ' . $item["Код"]);
						
						$existentOptions[] = $product_option_value_id;
						
						$quantity = ($item["Остаток"] > 900)?3:$item["Остаток"];
						$this->queryQueue("UPDATE " . DB_PREFIX . "product_option_value SET supplier = '" . (int)$quantity . "' WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
						$this->queryQueue("UPDATE " . DB_PREFIX . "product_option_value SET quantity = supplier + stock WHERE product_option_value_id = '" . (int)$product_option_value_id . "'");
					}
					
					} else {
					$this->echoLine('[MKNG] Пропускаем товар ' . $item["Код"]);
					$passedArray[] = $item["Код"];
					
				}
			}
			
			$this->doUpdateQueryQueue();
			$this->clearQueryQueue();
			
			$unExistent = $this->model_hobotix_hoboparser->disableUnexsistentProducts($existentProducts, $existentOptions, $this->supplerPrefix);
			
			mkdir(DIR_SUPPLIERS . $this->supplerCode, 0755, true);
			
			$unexistentProductsString = '';
			foreach ($unExistent['unexistentProducts'] as $key => $value){
				$unexistentProductsString .= $value . PHP_EOL;
			}
			
			$unexistentProductsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mobiking.unexistentproducts.csv';
			file_put_contents($unexistentProductsFile, $unexistentProductsString);
			
			$unexistentOptionsString = '';
			foreach ($unExistent['unexistentOptions'] as $key => $value){
				$unexistentOptionsString .= $value . PHP_EOL;
			}
			
			$unexistentOptionsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mobiking.unexistentoptions.csv';
			file_put_contents($unexistentOptionsFile, $unexistentOptionsString);
			
			//ЗАПИСЬ СЛУЖЕБНОЙ ИНФОРМАЦИИ
			$itemsReparsedNameWithPrintColorString = '';			
			foreach ($itemsReparsedNameWithPrintColor as $key){
				$itemsReparsedNameWithPrintColorString .= $key . PHP_EOL;
			}
			
			$itemsReparsedNameWithPrintColorFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mobiking.printnames.csv';
			file_put_contents($itemsReparsedNameWithPrintColorFile, $itemsReparsedNameWithPrintColorString);	
			
			$attributesString = '';
			foreach ($attributesArray as $key => $value){
				$attributesString .= $key . ';' . $value . PHP_EOL;
			}
			
			$attributesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mobiking.attributes.csv';
			file_put_contents($attributesFile, $attributesString);
			
			
			$itemsReparsedNameWithPrintColorString = '';
			foreach (array_unique($itemsReparsedNameWithPrintColor) as $key){
				$itemsReparsedNameWithPrintColorString .= $key . PHP_EOL;
			}
			
			$itemsReparsedNameWithPrintColorFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mobiking.printnames.csv';
			file_put_contents($itemsReparsedNameWithPrintColorFile, $itemsReparsedNameWithPrintColorString);
			
			$categoriesString = '';
			foreach ($categoriesArray as $key => $value){
				$categoriesString .= $key . ';' . $value . PHP_EOL;
			}
			
			$categoriesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mobiking.categories.csv';
			file_put_contents($categoriesFile, $categoriesString);
			
			$passedString = '';
			foreach ($passedArray as $line){
				$passedString .= $line . PHP_EOL;
			}
			
			$passedFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'mobiking.passedsku.csv';
			file_put_contents($passedFile, $passedString);
			//ЗАПИСЬ СЛУЖЕБНОЙ ИНФОРМАЦИИ
			
			
			$xml['item'] = $this->regroupItems($xml['item']);		
			$this->doUpdateQueryQueue();
			
			$xml['item'] = $this->reparseAndDeleteParam($xml['item']);
			
			$xmlString = LaLit\Array2XML::createXML('mobiking', $xml)->saveXML();;					
			$xmlFile = DIR_SUPPLIERS . 'mobiking.converted.xml';
			//			var_dump($xmlString);
			file_put_contents($xmlFile, $xmlString);
			
			
		}
		
		
		public function cron(){
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'XML2Array2.php');
			require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'Array2XML.php');
			require_once( DIR_SYSTEM . 'library/urlify.php');
			//	$this->xml2Array = new \XML2Array();
			
			if ($ftpConnection = ftp_connect($this->server, $this->port)){
				$this->echoLine('[FTP] Подключились, продолжаем');				
				} else {
				$this->echoLine('[FTP] Нет подключения');
				die();
			}
			
			if ($loginResult  = ftp_login($ftpConnection, trim($this->username), trim($this->password))){
				$this->echoLine('[FTP] Авторизовались, продолжаем');				
				} else {
				$this->echoLine('[FTP] Нет подключения');
				die();
			}
			
			if ($pasvResult  = ftp_pasv($ftpConnection, true)){
				$this->echoLine('[FTP] PASV режим включен, продолжаем');	
				} else {
				$this->echoLine('[FTP] Ошибка PASV');
				die();
			}
			
			$fileList = ftp_nlist($ftpConnection, '/');	
			rsort($fileList);
			
			if (!isset($fileList[0])){			
				$this->echoLine('[FTP] Странный список файлов ');
				var_dump($fileList);
				die();
			}
			
			$localFile = DIR_CACHE . trim($fileList[0],'/');
			
			if ($fileGetResult = ftp_get($ftpConnection, $localFile, $fileList[0], FTP_BINARY)){
				$this->echoLine('[FTP] Получаем файл ' . $fileList[0]);
				} else {
				$this->echoLine('[FTP] Сбой получения файла ' . $fileList[0]);
				die();
			}
			
			$zip = new ZipArchive;
			$zipRes = $zip->open($localFile);
			if ($zipRes === true) {
				$zip->extractTo(pathinfo(realpath($localFile), PATHINFO_DIRNAME));
				$zip->close();
				$this->echoLine('[ZIP] Распаковали ' . $localFile);
				} else {
				$this->echoLine('[ZIP] Не могу распаковать ' . $localFile);
				die();
			}						
			
			$ymlFile = pathinfo(realpath($localFile), PATHINFO_DIRNAME) . '/' . pathinfo(realpath($localFile), PATHINFO_FILENAME) . '.yml';
			
			if (!file_exists($ymlFile)){
				$this->echoLine('[YML] Чудеса какие-то, а где YML ' . $ymlFile);
				die();				
			}
			
			if ($ymlContents = file_get_contents($ymlFile)){
				$this->echoLine('[YML] Загрузили YML ');	
				} else {
				$this->echoLine('[YML] Не получилось загрузить YML ');	
				die();
			}
			
			try {
				$yml = LaLit\XML2Array::createArray($ymlContents);
				$this->echoLine('[YML] Загрузили YML в массив');	
				} catch (Exception $e){
				$this->echoLine('[YML] Ошибка разбора XML. ' . $e->getMessage());
				die ();
			}
			
			$directory = DIR_IMAGE . 'mobiking/';
			
			if (!is_dir($directory)){
				mkdir($directory, 0755);
			}
			
			$directory_logs = DIR_IMAGE . 'mobiking/_logs/';
			
			if (!is_dir($directory_logs)){
				mkdir($directory_logs, 0755);
			}
			
			$full_log = '';
			$added_log = '';
			foreach($yml['yml_catalog']['shop']['offers']['offer'] as $offer){
				unset($category_name);							
				
				if (isset($offer['param']) && $category_name = $this->getParamValueName($offer['param'], 'Категория товара')){
					$this->echoLine('[YMLOffer] Категория товара задана: ' . $category_name);	
					} else {
					$this->echoLine('[YMLOffer] Категория товара не задана');	
					$category_name = 'Без категории';
				}
				
				$category_dir = $directory . Urlify::filter($category_name) . '/';
				
				if (!is_dir($category_dir)){
					mkdir($category_dir, 0755);
				}
				
				$description = '';
				$description .= 'Код MobiKing: ' . $this->getParamValue($offer, 'id') . PHP_EOL;
				$description .= 'Название: ' . $offer['name'] . PHP_EOL;
				$description .= 'Артикул: '  . $offer['vendorCode'] . PHP_EOL;
				$description .= 'Бренд: '  	 . $offer['vendor'] . PHP_EOL;								
				
				if (isset($offer['param']) && is_array($offer['param'])){
					foreach ($this->parseYMLParamArray($offer['param']) as $param){
						$description .= $param['name'] .': ' . $param['value'] . PHP_EOL;
					}
				}
				
				$description .= 'Описание: ' . $this->checkCDATA($offer['description']) . PHP_EOL;
				
				$offer_dir = $category_dir . Urlify::filter($offer['name']) . '/';
				
				if (!is_dir($offer_dir)){
					mkdir($offer_dir, 0755);
					$this->echoLine('[YMLOffer] Создали директорию товара ' . $offer_dir);
					$added_log .= '"' . $offer['name'] . '"' . ';' . '"' . Urlify::filter($category_name) . '/' . Urlify::filter($offer['name']) . '/' .'"'. PHP_EOL;
				}
				
				file_put_contents($offer_dir . 'description.txt', $description);
				
				//В полный лог
				$full_log .= '"' . $offer['name'] . '"' . ';' . '"' . Urlify::filter($category_name) . '/' . Urlify::filter($offer['name']) . '/' .'"'. PHP_EOL;
				
				if (is_array($offer['picture'])){
					$images = $offer['picture'];
					} else {
					$images = array($offer['picture']);
				}
				
				$i = 0;
				foreach ($images as $image){
					
					$image_ext = pathinfo($image, PATHINFO_EXTENSION);
					$image_new = Urlify::filter($offer['name']) . '-' . $i . '.' . $image_ext;					
					
					if (!file_exists($offer_dir . $image_new)){
						$image_content = file_get_contents($image);
						file_put_contents($offer_dir . $image_new, $image_content);
						$this->echoLine('[YMLOfferPicture] Тырим картинку ' . $image . ' -> ' . $image_new);	
						} else {
						$this->echoLine('[YMLOfferPicture] Картинка ' . $image_new . ' существует, пропускаем');
					}
					
					
					$i++;
				}
				
				
			}
			
			file_put_contents($directory_logs . date('Y-m-d') . '_mobiking_added_products.csv', $added_log);
			file_put_contents($directory . 'all_mobiking_products.csv', $full_log);
			
			//Чистим логи за 10 дней
			if (file_exists($directory_logs)) {
				foreach (new DirectoryIterator($directory_logs) as $fileInfo) {
					if ($fileInfo->isDot()) {
						continue;
					}
					if (time() - $fileInfo->getCTime() >= 10*24*60*60) {
						unlink($fileInfo->getRealPath());
					}
				}
			}
			
			
			//deleting file
			unlink ($localFile);
			unlink ($ymlFile);
			
			
		}		
	}																																																		