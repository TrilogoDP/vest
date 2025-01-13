<?php
	class ModelLocalisationCityUpdate extends Model {
		private $message = '';
		
		private function removeBrackets($line){
			if (strpos($line, '(')){
				$exploded = explode('(', $line);
				$line = $exploded[0];
			}
			
			
			
			$line = trim(str_replace("'", "`", $line));
			
			return trim($line);
		}
		
		private function setMessage($message){
			
			echoLine('[TG BOT] Сообщение:');
			echoLine($message);
			
			$this->message = $message;
			
			return $this;
		}
		
		private function sendResults(){
			$this->load->library('hobotix/TelegramSender');
			$telegramSender = new hobotix\TelegramSender;
			
			$telegramSender->SendMessage($this->message);
		}
		
		public function updateUkrPoshtaAPI(){
			
			
			$config = new \Ukrposhta\Data\Configuration();
			$config->setBearer('bf71517b-9264-3de3-af35-3036f50a1e49'); 
			$config->setToken('444723b7-532c-4691-a6e7-b52da4abb90b');
			
			
			$regionsObject = new \Ukrposhta\Directory\Region($config);
			$regions = $regionsObject->getList();
			
			foreach ($regions['Entry'] as $region){
				
				$query = $this->db->query("SELECT * FROM `oc_country` WHERE name LIKE '%".$region['REGION_UA']." область%'");
				if($query->row){
					$this->db->query("UPDATE `oc_country` SET up_ref='" . $region['REGION_ID'] . "' WHERE country_id='".$query->row['country_id']."'");
				}
				
			}
			
			
			
			$districtsObject = new \Ukrposhta\Directory\District($config);
			$districts = $districtsObject->getList();
			
			foreach ($districts['Entry'] as $district){
				$query = $this->db->query("INSERT INTO oc_up_districts SET 
				DISTRICT_ID = '" . (int)$district['DISTRICT_ID'] . "',
				REGION_ID = '" . (int)$district['REGION_ID'] . "',
				DISTRICT_UA = '" . $this->db->escape($district['DISTRICT_UA']) . "',
				DISTRICT_RU = '" . $this->db->escape(($district['DISTRICT_RU']?$district['DISTRICT_RU']:$district['DISTRICT_UA'])) . "'
				ON DUPLICATE KEY UPDATE
				REGION_ID = '" . (int)$district['REGION_ID'] . "',
				DISTRICT_UA = '" . $this->db->escape($district['DISTRICT_UA']) . "',
				DISTRICT_RU = '" . $this->db->escape(($district['DISTRICT_RU']?$district['DISTRICT_RU']:$district['DISTRICT_UA'])) . "'");
				
				echoLine($district['DISTRICT_UA'] . ' -> ' . $district['DISTRICT_ID']);
			}
			
			unset($district);
			
			$paramsObject = new \Ukrposhta\Data\Storage();
			$citiesObject = new \Ukrposhta\Directory\City($config);
			foreach ($districts['Entry'] as $district){
				
				$paramsObject->district_id = $district['DISTRICT_ID'];				
				$cities = $citiesObject->getList($paramsObject);	
				
				if (!empty($cities['Entry']['REGION_ID'])){
					$cities['Entry'] = array($cities['Entry']);
				}
				
				
				foreach ($cities['Entry'] as $city){
					
					$query = $this->db->query("INSERT INTO oc_up_cities SET 
					CITY_ID 	= '" . (int)$city['CITY_ID'] . "',
					DISTRICT_ID = '" . (int)$city['DISTRICT_ID'] . "',
					REGION_ID 	= '" . (int)$city['REGION_ID'] . "',					
					CITYTYPE_UA = '" . $this->db->escape($city['CITYTYPE_UA']) . "',
					CITYTYPE_RU = '" . $this->db->escape(($city['CITYTYPE_RU']?$city['CITYTYPE_RU']:$city['CITYTYPE_UA'])) . "',
					CITY_UA = '" . $this->db->escape($city['CITY_UA']) . "',
					CITY_RU = '" . $this->db->escape(($city['CITY_RU']?$city['CITY_RU']:$city['CITY_UA'])) . "',					
					POPULATION 	= '" . (int)$city['POPULATION'] . "',
					LONGITUDE 	= '" . $this->db->escape($city['LONGITUDE']) . "',
					LATTITUDE 	= '" . $this->db->escape($city['LATTITUDE']) . "'
					ON DUPLICATE KEY UPDATE
					DISTRICT_ID = '" . (int)$city['DISTRICT_ID'] . "',
					REGION_ID 	= '" . (int)$city['REGION_ID'] . "',
					CITYTYPE_UA = '" . $this->db->escape($city['CITYTYPE_UA']) . "',
					CITYTYPE_RU = '" . $this->db->escape(($city['CITYTYPE_RU']?$city['CITYTYPE_RU']:$city['CITYTYPE_UA'])) . "',
					CITY_UA = '" . $this->db->escape($city['CITY_UA']) . "',
					CITY_RU = '" . $this->db->escape(($city['CITY_RU']?$city['CITY_RU']:$city['CITY_UA'])) . "',
					
					POPULATION 	= '" . (int)$city['POPULATION'] . "',
					LONGITUDE 	= '" . $this->db->escape($city['LONGITUDE']) . "',
					LATTITUDE 	= '" . $this->db->escape($city['LATTITUDE']) . "'");
					
					echoLine($city['DISTRICT_UA'] . ' -> ' . $city['CITY_UA']);	
					
					
					//КИЕВ ПЕРЕМЕСТИТЬ В КИЕВСКУЮ ОБЛАСТЬ
					$this->db->query("UPDATE oc_up_cities SET REGION_ID = 11 WHERE REGION_ID = 10");
					
					
					$districtParamsObject = new \Ukrposhta\Data\Storage();
					$postOfficeObject = new Ukrposhta\Directory\Postoffice($config);
					$postoffices = $postOfficeObject->getByCityId($city['CITY_ID']);
					
					if (!empty($postoffices['Entry']['REGION_ID'])){
						$postoffices['Entry'] = array($postoffices['Entry']);
					}
					
					foreach ($postoffices['Entry'] as $postoffice){
						$query = $this->db->query("INSERT INTO oc_up_postoffices SET 
						ID 				= '" . (int)$postoffice['ID'] . "',
						CITY_ID 		= '" . (int)$postoffice['CITY_ID'] . "',
						DISTRICT_ID 	= '" . (int)$postoffice['DISTRICT_ID'] . "',		
						REGION_ID 		= '" . (int)$postoffice['REGION_ID'] . "',
						PARENT_ID 		= '" . (int)$postoffice['PARENT_ID'] . "',
						TYPE_LONG 		= '" . $this->db->escape($postoffice['TYPE_LONG']) . "',
						TYPE_ACRONYM 	= '" . $this->db->escape($postoffice['TYPE_ACRONYM']) . "',
						MEREZA_NUMBER 	= '" . $this->db->escape($postoffice['MEREZA_NUMBER']) . "',
						ADDRESS 		= '" . $this->db->escape($postoffice['ADDRESS']) . "',
						STREET_UA 		= '" . $this->db->escape($postoffice['STREET_UA']) . "',
						STREETTYPE_UA 	= '" . $this->db->escape($postoffice['STREETTYPE_UA']) . "',					
						HOUSENUMBER 	= '" . $this->db->escape($postoffice['HOUSENUMBER']) . "',	
						POSTINDEX 		= '" . $this->db->escape($postoffice['POSTINDEX']) . "',
						PO_SHORT 		= '" . $this->db->escape($postoffice['PO_SHORT']) . "',
						LONGITUDE 		= '" . $this->db->escape($postoffice['LONGITUDE']) . "',
						LATTITUDE 		= '" . $this->db->escape($postoffice['LATTITUDE']) . "'
						ON DUPLICATE KEY UPDATE
						CITY_ID 		= '" . (int)$postoffice['CITY_ID'] . "',
						DISTRICT_ID 	= '" . (int)$postoffice['DISTRICT_ID'] . "',		
						REGION_ID 		= '" . (int)$postoffice['REGION_ID'] . "',
						PARENT_ID 		= '" . (int)$postoffice['PARENT_ID'] . "',
						TYPE_LONG 		= '" . $this->db->escape($postoffice['TYPE_LONG']) . "',
						TYPE_ACRONYM 	= '" . $this->db->escape($postoffice['TYPE_ACRONYM']) . "',
						MEREZA_NUMBER 	= '" . $this->db->escape($postoffice['MEREZA_NUMBER']) . "',
						ADDRESS 		= '" . $this->db->escape($postoffice['ADDRESS']) . "',
						STREET_UA 		= '" . $this->db->escape($postoffice['STREET_UA']) . "',
						STREETTYPE_UA 	= '" . $this->db->escape($postoffice['STREETTYPE_UA']) . "',					
						HOUSENUMBER 	= '" . $this->db->escape($postoffice['HOUSENUMBER']) . "',	
						POSTINDEX 		= '" . $this->db->escape($postoffice['POSTINDEX']) . "',
						PO_SHORT 		= '" . $this->db->escape($postoffice['PO_SHORT']) . "',
						LONGITUDE 		= '" . $this->db->escape($postoffice['LONGITUDE']) . "',
						LATTITUDE 		= '" . $this->db->escape($postoffice['LATTITUDE']) . "'");
						
						echoLine($city['CITY_UA'] . ' -> ' . $postoffice['ADDRESS']);	
					}
				}			
			}	
			
			
			$this->db->query("UPDATE oc_up_cities SET HAS_POSTOFFICES = 0");
			$this->db->query("UPDATE oc_up_cities SET HAS_POSTOFFICES = 1 WHERE CITY_ID IN (SELECT DISTINCT CITY_ID FROM oc_up_postoffices)");
		}
		
		
		public function updateAPI() {
			//*** update Areas
			$soap = curl_init("http://api.novaposhta.ua/v2.0/xml/Address/getCities");
			$request = '<?xml version="1.0" encoding="utf-8"?>
			<file>
			<apiKey>044d882c20cda18020be46a5977a67e7</apiKey>
			<modelName>Address</modelName>
			<calledMethod>getAreas</calledMethod>
			</file>';
			
			curl_setopt($soap, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'Content-Length: '.strlen($request)));
			curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
			curl_setopt($soap, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($soap, CURLOPT_CONNECTTIMEOUT, 300);			
			
			$response = curl_exec($soap);
			curl_close($soap);
			
			$array_data = json_decode(json_encode(simplexml_load_string($response)), true);		
			
			if ($array_data['success'] == 'false' || !$array_data['data']){
				
				$message = '⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️' . PHP_EOL;
				$message .= 'Ахтунг! Ахтунг!' . PHP_EOL;
				$message .= 'Что-то пошло не так с обновлением справочников Новой Почты' . PHP_EOL;
				$message .= 'Ошибка: <b>' . $array_data['errors']['item'] . '</b>';
				
				$this->setMessage($message)->sendResults();
				
				die();
			}				
	
			foreach($array_data['data']['item'] as $ad){
				$query = $this->db->query("SELECT * FROM `oc_country` WHERE name LIKE '%".$ad['Description']." область%'");
				if($query->row){
					$this->db->query("UPDATE `oc_country` SET ref='".$ad['Ref']."' WHERE country_id='".$query->row['country_id']."'");
				}
			}
			
			//*** update Cities
			$soap = curl_init("http://api.novaposhta.ua/v2.0/xml/Address/getCities");
			$request = '<?xml version="1.0" encoding="utf-8"?>
			<file>
			<apiKey>044d882c20cda18020be46a5977a67e7</apiKey>
			<modelName>Address</modelName>
			<calledMethod>getCities</calledMethod>
			</file>';
			
			curl_setopt($soap, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'Content-Length: '.strlen($request)));
			curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
			curl_setopt($soap, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($soap, CURLOPT_CONNECTTIMEOUT, 10);			
			
			$response = curl_exec($soap);
			curl_close($soap);						
			
			$array_data = json_decode(json_encode(simplexml_load_string($response)), true);	
			
			$this->db->query("UPDATE`oc_zone` SET status = 0");
			
			foreach($array_data['data']['item'] as $ad){				
				$query = $this->db->ncquery("SELECT * FROM `oc_zone` WHERE code = '". (int)$ad['CityID'] . "'");

				$check_query = $this->db->ncquery("SELECT * FROM oc_zone WHERE 
					name LIKE '" . $this->db->escape($this->removeBrackets($ad['Description'])) . "' 
					AND area_ref LIKE '" . $this->db->escape($ad['Area']) . "' 
					AND code < '" . (int)$ad['CityID'] . "'");

				if (!$check_query->num_rows){
					$desc = $this->removeBrackets($ad['Description']);					
					$desc_ru = $this->removeBrackets($ad['DescriptionRu']);					
				} else {
					$desc 		= $ad['Description'];					
					$desc_ru 	= $ad['DescriptionRu'];	
				}

				if (!trim($desc_ru)){
					$desc_ru = $desc;
				}
				
				if($query->row){					
					echoLine($ad['Description'] . ' -> ' . $desc);
					
					$this->db->query("UPDATE `oc_zone` SET 
					ref 			= '" . $ad['Ref'] . "',
					status 			= '1',
					name 			= '" . $this->db->escape($desc). "',
					name_ru 		= '" . $this->db->escape($desc_ru). "',
					area_ref        = 	'" . $ad['Area'] . "'
					WHERE zone_id='" . $query->row['zone_id'] . "'");
					
					} else {

					$query_c = $this->db->query("SELECT * FROM `oc_country` WHERE ref = '". $ad['Area'] . "'");
					$country_id = $query_c->row['country_id'];
					if((int)$country_id > 0){
						echoLine( 'Need add ' . $desc . ' cid:'.$country_id.' - '.$ad['CityID'] );					
						
						$this->db->query("INSERT INTO `oc_zone` SET country_id='".$country_id."',
						name 			= 	'" . $this->db->escape($desc). "',
						name_ru 		= 	'" . $this->db->escape($desc_ru). "',
						status 			= 	'1',
						code			=	'". $ad['CityID'] . "',
						ref 			=	'" . $ad['Ref'] . "',
						area_ref        = 	'" . $ad['Area'] . "'");
						}else{
						echoLine( 'Need add '.$desc.' no city - '.$ad['CityID'] );
					}
				}
			}
			
			$soap = curl_init("http://api.novaposhta.ua/v2.0/xml/AddressGeneral/getWarehouses");
			$request = '<?xml version="1.0" encoding="utf-8"?>
			<file>
			<apiKey>044d882c20cda18020be46a5977a67e7</apiKey>
			<modelName>AddressGeneral</modelName>
			<calledMethod>getWarehouses</calledMethod>
			</file>';
			
			curl_setopt($soap, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=utf-8', 'Content-Length: '.strlen($request)));
			curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
			curl_setopt($soap, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($soap, CURLOPT_CONNECTTIMEOUT, 300);			
			
			$response = curl_exec($soap);
			curl_close($soap);
			
			$this->db->query("UPDATE`oc_city` SET status = 0");
			
			$array_data = json_decode(json_encode(simplexml_load_string($response)), true);		
			foreach($array_data['data']['item'] as $ad){
				$desc = $ad['Description'];
				$desc = str_replace("'","`",$desc);
				
				$desc_ru = $ad['DescriptionRu'];
				$desc_ru = str_replace("'","`",$desc_ru);
				
				if (!trim($desc_ru)){
					$desc_ru = $desc;
				}
				
				
				$query_c = $this->db->query("SELECT * FROM `oc_zone` WHERE ref = '".$ad['CityRef']."'");
				$zone_id = $query_c->row['zone_id'];					
				$query_f = $this->db->query("SELECT * FROM `oc_city` WHERE name = '".$desc."' AND code='".$ad['Number']."'");
				
				if($query_f->row){
					
					echoLine($ad['Description'] . ' -> ' . $desc);
					
					$this->db->query("UPDATE `oc_city` SET 
					ref='".$ad['Ref']."',
					status = '1',
					name = '" . $this->db->escape($desc). "',
					name_ru = '" . $this->db->escape($desc_ru). "'
					WHERE city_id='".$query_f->row['city_id']."'");
					
					} else {
					
					$this->db->query("INSERT INTO `oc_city` SET 
					zone_id='" . $zone_id . "',
					name = '" . $this->db->escape($desc). "',
					name_ru = '" . $this->db->escape($desc_ru). "',
					status = '1',
					code='".$ad['Number']."',
					sort_order='".$ad['Number']."',
					ref='".$ad['Ref']."'");
				}
			}

			$query = $this->db->query("DELETE FROM oc_city WHERE zone_id = 0");
			
			$query = $this->db->query("SELECT * FROM oc_zone WHERE status = 1"); 
			foreach ($query->rows as $row){ 
				
				$this->db->query("DELETE FROM oc_novaposhta_streets WHERE CityID = '" . (int)$row['zone_id'] . "'"); 
				
				oc_cli_output('Город ' . $row['name'] );
				
				$curl = curl_init("http://api.novaposhta.ua/v2.0/json/");
				$request = '{
				"modelName": "Address",
				"calledMethod": "getStreet",
				"methodProperties": {
				"CityRef":"'. $row['ref'] .'"
				},
				"apiKey": "044d882c20cda18020be46a5977a67e7"
				}';
				
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length: '.strlen($request)));
				curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);	
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);	
				curl_setopt($curl, CURLOPT_VERBOSE , true);		
				
				$response = curl_exec($curl);
				curl_close($curl);
				
				$data = json_decode($response, true);	
				
				foreach($data['data'] as $line){
					
					echoLine('     Улица ' . $line['Description'] );
					
					$this->db->query("INSERT INTO oc_novaposhta_streets (`CityID`, `CityRef`, `Ref`, `Description`, `StreetType`) VALUES ('" . (int)$row['zone_id'] . "', '" . $this->db->escape($row['ref']) . "', '" . $this->db->escape($line['Ref']) . "', '" . $this->db->escape($line['Description']) . "', '" . $this->db->escape($line['StreetsType']) . "')");
				}
			}


			
			
			$this->cache->flush();
		}
		
		public function checkDatabase() {
			
			
		}
		
	}												