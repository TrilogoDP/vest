<?php
	
	class ModelHobotixHoboPrice extends Model {
		
		
		public function addPrice($data){
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "price_history WHERE product_id = '" . (int)$data['product_id'] . "' AND DATE(date) <= (DATE_SUB(NOW(), INTERVAL 3 MONTH))");
			
			$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "price_history SET 
			product_id = '" . (int)$data['product_id'] . "', 
			suppler_code = '" . $data['suppler_code'] . "', 
			price = '" . (float)$data['price'] . "',
			alerted = 0,
			date = NOW()
			ON DUPLICATE KEY UPDATE price = '" . (float)$data['price'] . "'");
		}
		
		public function getSupplerCodes(){
			$suppler_code_data = array();
			
			$query = $this->db->query("SELECT DISTINCT(suppler_code) FROM " . DB_PREFIX . "price_history WHERE 1");		
				
			foreach ($query->rows as $row){
				$suppler_code_data[] = $row['suppler_code'];
			}	
			
			return $suppler_code_data;

		}
		
		public function getAllDNUPProducts(){				
			$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE dnup = 1");
			
			return $query->rows;
		}
		
		public function getPriceHistory($product_id, $suppler_code) {		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price_history WHERE suppler_code = '" . $this->db->escape($suppler_code) . "' AND product_id = '" . (int)$product_id . "' ORDER by date DESC");		
			
			return $query->rows;
			
		}
		
		public function getPriceHistoryOnDate($product_id, $suppler_code, $date) {		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price_history WHERE product_id = '" . (int)$product_id . "' AND suppler_code = '" . $this->db->escape($suppler_code) . "' AND date = '" . date('Y-m-d', strtotime($date)) . "' LIMIT 1");		
			
			if ($query->num_rows){
				return $query->row['price'];
			} else {
				return false;
			}
		}
		
		public function countDNUPProducts(){
			$query = $this->db->query("SELECT count(product_id) as total FROM " . DB_PREFIX . "product WHERE dnup = 1");
			
			return $query->row['total'];
		}
		
		public function getPriceHistoryOnAnyDateBetween($product_id, $suppler_code, $date1, $date2, $last_price) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price_history WHERE product_id = '" . (int)$product_id . "' AND suppler_code = '" . $this->db->escape($suppler_code) . "' AND price <> '" . (float)$last_price . "' AND date <= '" . date('Y-m-d', strtotime($date1)) . "'  AND date >= '" . date('Y-m-d', strtotime($date2)) . "' ORDER BY date DESC LIMIT 1");		
			if ($query->num_rows){
				return $query->row['price'];
			} else {
				return false;
			}
		}
		
		public function getLastPriceHistory($product_id, $suppler_code) {		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price_history WHERE product_id = '" . (int)$product_id . "' AND suppler_code = '" . $this->db->escape($suppler_code) . "' ORDER by date DESC LIMIT 1");		
			
			if (!empty($query->row['price'])){
				return $query->row['price'];
			}
			
			return false;
			
		}
		
		public function getLastPriceHistoryDate($product_id) {		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price_history WHERE product_id = '" . (int)$product_id . "' ORDER by date DESC LIMIT 1");		
			
			if (!empty($query->row['date'])){
				return $query->row['date'];
			}
			
			return false;
			
		}
		
		public function setAlertAboutPrice($product_id, $price){
			$query = $this->db->query("UPDATE " . DB_PREFIX . "price_history SET alerted = 1 WHERE product_id = '" . (int)$product_id . "' AND price = '" . (float)$price . "'");		
		}
		
		public function checkIfPriceWasAlerted($product_id, $price){
			$query = $this->db->query("SELECT alerted FROM " . DB_PREFIX . "price_history WHERE product_id = '" . (int)$product_id . "' AND ROUND(price) = '" . (float)$price . "'");
			
			return !empty($query->row['alerted'])?$query->row['alerted']:false;
		}

		public function checkIfPriceWasAlertedWithinRecentDays($product_id, $suppler_code, $price){
			//Выбираем все цены этого поставщика за последние 5 дней
			$sql = "SELECT * FROM " . DB_PREFIX . "price_history WHERE
				product_id = '" . (int)$product_id . "' 
				AND DATE(date) >= '" . date('Y-m-d', strtotime('-2 day')) . "'
			 	AND suppler_code = '" . $this->db->escape($suppler_code) . "'";

			$query = $this->db->query($sql);			

			//Если нету цен за последние 5 дней, сравнить не с чём, отправляем
			if (!$query->num_rows){
				return true;
			}			

			//В этой переменной будет счётчик уведомлений о этом товаре за последние 5 дней
			$alerted_counter = 0;
			foreach ($query->rows as $row){

				echoLine('	[i] ' . ' Текущая цена: ' . $price . ', цена истории ' . $row['date'] . ': ' . $row['price']);

				//Если какая-то цена из истории за 5 дней больше или меньше чем текущая на порог в условные 20 грн
				if ($row['price'] > ($price + 20) || $row['price'] < ($price - 20)){
					//То мы отправляем изменение в любом случае
					echoLine('	[i] ' . ' Цена товара изменилась за 5 дней более чем на 20');
					return true;
				} else {										
					//Цена из истории находится в пределах порога и мы о ней уведомляли
					if ($row['alerted']){						
						$alerted_counter ++;
					}
				}
			}

			//Если счетчик уведомлений за 5 дней == 0, значит мы ни разу не отправляли уведомление и только в этом случае нам надо его отправить
			//В случае если счётчик уведомлений не равен нулю, значит за последние 5 дней было хотя бы одно уведомление о этом товаре, и отправлять не нужно
			if ($alerted_counter == 0){
				echoLine('	[i] ' . ' Счётчик уведомлений равен нулю, отправляем');
				return true;
			}

			echoLine('	[i] ' . ' Логика исключения отправки 5 дней 20 грн сработала, не отправляем');

			return false;
		}
		
		
		/*
			Цена текущая, цена новая
		*/
		public function comparePrice($price1, $price2, $rangeindex = 0){
			$limits = $this->config->get('config_pricehistory');		
			$limits = explode(PHP_EOL, $limits);
			
			$price1 = (float)$price1;
			$price2 = (float)$price2;
			
			$result = array();
		
			foreach ($limits as $limit){
				if (trim($limit)){
					$limit_exploded = explode(':', $limit);
					$pricelimit = (int)$limit_exploded[0];
					$pricerange = explode('-', $limit_exploded[1]);
					
					$pricerange[0] = (float)$pricerange[0];
					$pricerange[1] = (float)$pricerange[1];

					if ($price1 <= $pricelimit){
						if ($price1 < $price2){
							
							if ($price1 + (int)$pricerange[$rangeindex] <= $price2){
								$result['warning'] = 'less';
								$result['range'] = $pricerange[$rangeindex];
								$result['difference'] = ($price2 - $price1); 
							}						
						}
						
						if ($price1 > $price2){							
							if ($price1 - (int)$pricerange[$rangeindex] >= $price2){
								$result['warning'] = 'more';
								$result['range'] = $pricerange[$rangeindex];
								$result['difference'] = ($price1 - $price2); 
							}
						}
						
						return $result;
					}	
					
				}
			}
			
			return false;
		}
	}			
