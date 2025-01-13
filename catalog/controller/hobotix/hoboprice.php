<?php
	class ControllerHobotixHoboprice extends Controller {
		
		private $ranges = array(
		'0', '1', '7', '14', '30', '60'	
		);
		
		private $rangeindexes = array(
		0, 0, 0, 1, 1, 1		
		);
		
		private $message = '';
		private $MAX_MESSAGES = 6;
		
		
		private $priceranges = array();
		private $supplercodes = array();
		
		private function echoSimple($line){
			echo $line;			
		}


		public function setarchiveproducts(){

			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}

			$sql = "UPDATE oc_product SET archive = 1, archive_auto = 1 WHERE quantity = 0 AND status = 1 AND DATE(date_added) <= '" . date('Y-m-d', strtotime('-2 year')) . "' AND oc_product.product_id NOT IN (SELECT product_id FROM oc_order_product WHERE order_id IN (SELECT order_id FROM oc_order WHERE DATE(date_added) >= '" . date('Y-m-d', strtotime('-2 year')) . "')) ORDER BY date_added DESC";		
			$this->db->query($sql);

			$this->db->query("UPDATE oc_product SET archive = 0 WHERE quantity > 0");
		}
		
		private function memoryUnits($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		}
		
		private function echoLine($line){
			$line = str_replace('<![CDATA[', '', $line);
			$line = str_replace(']]>', '', $line);
			echo $line . PHP_EOL;			
		}
		
		private function setPriceRange(){
			$result = array();
			
			$ranges = $this->config->get('config_pricehistory');
			$ranges_exploded = explode(PHP_EOL, $ranges);
			
			foreach ($ranges_exploded as $range_line){
				$range_line_exploded = explode(':', $range_line);
				$range_prices_exploded = explode('-', $range_line_exploded[1]);
				
				$result[$range_line_exploded[0]] = array(
				$range_prices_exploded[0],
				$range_prices_exploded[1]
				);
			}
			
			$this->priceranges = $result;
			
			return $this;
		}
		
		private function setMessage($message){
			
			$this->echoLine('[TG BOT] Сообщение:');
			$this->echoLine($message);
			
			$this->message = $message;
			
			return $this;
		}
		
		private function setSupplerCodes(){
			$this->load->model('hobotix/hoboprice');
			
			$this->supplercodes = $this->model_hobotix_hoboprice->getSupplerCodes();
			
			return $this;
		}
		
		private function comparePrice($price1, $price2, $rangeindex = 0){
			$price1 = (float)$price1;
			$price2 = (float)$price2;
			
			
			foreach ($this->priceranges as $pricelimit => $pricerange){		
				
				if ($price1 <= (float)$pricelimit){							
					
					if ($price1 < $price2){															
						if (($price1 + (int)$pricerange[$rangeindex]) <= $price2){
							$result['warning'] = 'less';
							$result['range'] = $pricerange[$rangeindex];
							$result['difference'] = ($price2 - $price1); 
						}						
					}
					
					if ($price1 > $price2){				
						if (($price1 - (int)$pricerange[$rangeindex]) >= $price2){
							$result['warning'] = 'more';
							$result['range'] = $pricerange[$rangeindex];
							$result['difference'] = ($price1 - $price2); 
						}
					}								
					
					return $result;
				}	
			}
			
			return false;
		}		

		public function testbot(){
			$this->load->library('hobotix/TelegramSender');
			$telegramSender = new hobotix\TelegramSender;
			
			$telegramSender->setGroupID('-1001650893338');
			
			$telegramSender->SendMessage('Всім привітики');
		}
		
		private function sendResults(){
			$this->load->library('hobotix/TelegramSender');
			$telegramSender = new hobotix\TelegramSender;
			
			$telegramSender->setGroupID('-1001650893338');
			
			$telegramSender->SendMessage($this->message);
		}		
		
		public function cron(){
			$logFilePrice = DIR_SUPPLIERS . 'logfile_price.log';

			$messages = array();
			$messageCounter = 1;
			ini_set('memory_limit','2G');
			
			$this->load->model('catalog/product');
			$this->load->model('hobotix/hoboprice');
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			$this->setPriceRange()->setSupplerCodes();	
			
			//Получить все товары
			$products = $this->model_hobotix_hoboprice->getAllDNUPProducts();
			
			foreach ($products as $productID){
				$product_id = $productID['product_id'];
				
				$alertedAlready = false;

				if ($productID['product_id'] != 8257){
				//	continue;
				}

				$product = $this->model_catalog_product->getProduct($productID['product_id']);
				
				
				if (!$product){
					$this->echoLine('[HBP] Товар ' . $product['product_id'] . ' отключен, пропускаем');
					continue;
				}
				
				if (!$product['quantity']){
					$this->echoLine('[HBP] Товар ' . $product['product_id'] . ' с нулевым наличием, пропускаем');
					continue;
				}
				
				$this->echoLine('[HBP] Товар ' . $product['product_id'] . '');
				
				$counter = 0;
				for ($counter = 0; $counter <= (count($this->supplercodes)-1); $counter++){
					$supplercode = $this->supplercodes[$counter];
					
					$history = $this->model_hobotix_hoboprice->getPriceHistory($productID['product_id'], $supplercode);
					
					if (!$history){
						//	$this->echoLine('	[i] ' . $supplercode . ' товар ' . $product['product_id'] . ' нет истории, пропускаем');
						continue;
					}
					
					$date = $date_of_last = date('Y-m-d');
					$last_price = $this->model_hobotix_hoboprice->getLastPriceHistory($productID['product_id'],  $supplercode);	
					
					if ($last_price){
						$this->echoLine('	[i] '. $supplercode .' последняя цена, дата ' . $date . ': ' . $last_price);
						} else {
						//	$this->echoLine('	[i] '. $supplercode .' нет последней цены');
						continue;
					}
					
					$rangeCounter = 0;
					for ($rangeCounter = 0; $rangeCounter <= (count($this->ranges)-1); $rangeCounter++){					
						$range = (int)$this->ranges[$rangeCounter];
						$prevrange = !empty($this->ranges[$rangeCounter-1])?$this->ranges[$rangeCounter-1]:false;
						$nextrange = !empty($this->ranges[$rangeCounter+1])?$this->ranges[$rangeCounter+1]:false;
						
						$date = date('Y-m-d', strtotime("-$range day"));
						$date_price = $this->model_hobotix_hoboprice->getPriceHistoryOnDate($productID['product_id'],  $supplercode, $date);
						
						if ($prevrange && $nextrange){
							$date1 = date('Y-m-d', strtotime("-$prevrange day"));
							$date2 = date('Y-m-d', strtotime("-$nextrange day"));					
							$date_price = $this->model_hobotix_hoboprice->getPriceHistoryOnAnyDateBetween($productID['product_id'],  $supplercode, $date1, $date2, $last_price);
						}					
						
						if ($nextrange){
							$date1 = date('Y-m-d', strtotime("-$range day"));
							$date2 = date('Y-m-d', strtotime("-$nextrange day"));
							$date_price = $this->model_hobotix_hoboprice->getPriceHistoryOnAnyDateBetween($productID['product_id'],  $supplercode, $date1, $date2, $last_price);
						}
						
						if ($date_price){
							$this->echoLine('	[i] ' . $product_id . ' '. $supplercode .' цена на дату ' . $date . ': ' . $date_price);
							} else {						
							$this->echoLine('	[i] '. $supplercode .' нет цены на даты между ' . $date1 . '-' . $date2 . ': ' . $date_price);
							continue;
						}

						/*  */
						if ($supplercode == 'kosmotech') {
							if ($date_price){
								$message = '	[i] ' . $product_id . ' '. $supplercode .' цена на дату ' . $date . ': ' . $date_price;
								file_put_contents($logFilePrice, $message . PHP_EOL, FILE_APPEND);
							} else {
								$message = '	[i] '. $supplercode .' нет цены на даты между ' . $date1 . '-' . $date2 . ': ' . $date_price;
								file_put_contents($logFilePrice, $message . PHP_EOL, FILE_APPEND);
							}
						}
						
						if ($date_price){
							$compare = $this->comparePrice($last_price, $date_price, $this->rangeindexes[$rangeCounter]);															
							
							if ($compare && !$alertedAlready && $this->model_hobotix_hoboprice->checkIfPriceWasAlertedWithinRecentDays($product_id, $supplercode, $this->currency->format($last_price, $this->config->get('config_currency'), '', false)) && !$this->model_hobotix_hoboprice->checkIfPriceWasAlerted($product_id, $this->currency->format($last_price, $this->config->get('config_currency'), '', false))){	
								$message = '';		

								$logFile = DIR_SUPPLIERS . 'pricealert.log'; // Укажите путь к вашему лог файлу

								if ($compare['warning'] == 'more'){
									$message = '🔥🔥🔥🔥 <b>Изменение номер ' . $messageCounter . '</b> 🔥🔥🔥🔥'  . PHP_EOL;
									$message .= '👍👍 <b>Цена товара повысилась!</b>'  . PHP_EOL;
									$message .= '⚠ Товар ' . $product['name'] . ', ' . $product_id . ', <b>' . $product['sku'] . '</b>'  . PHP_EOL;
									$message .= '⚠ Изменение <b>' . $this->currency->format($date_price, $this->config->get('config_currency')) . '</b> -> <b>' . $this->currency->format($last_price, $this->config->get('config_currency')) . '</b>' . PHP_EOL;
									$message .= PHP_EOL;

									// Записываем сообщение в лог файл
    								file_put_contents($logFile, strip_tags($message) . PHP_EOL, FILE_APPEND);
									
									$messageCounter++;
									$messages[] = $message;
								}
								
								
								
								if ($compare['warning'] == 'less'){
									$message .= '🔥🔥🔥🔥 <b>Изменение номер ' . $messageCounter . '</b> 🔥🔥🔥🔥'  . PHP_EOL;
									$message .= '👎👎 <b>Цена товара понизилась!</b>' . PHP_EOL;
									$message .= '⚠ Товар ' . $product['name'] . ', ' . $product_id . ', <b>' . $product['sku'] . '</b>'  . PHP_EOL;
									$message .= '⚠ Изменение <b>' . $this->currency->format($date_price, $this->config->get('config_currency')) . '</b> -> <b>' . $this->currency->format($last_price, $this->config->get('config_currency')) . '</b>' . PHP_EOL;
									$message .= PHP_EOL;

									// Записываем сообщение в лог файл
    								file_put_contents($logFile, strip_tags($message) . PHP_EOL, FILE_APPEND);
									
									$messageCounter++;
									$messages[] = $message;
								}
								
								
								$alertedAlready = true;
								$this->model_hobotix_hoboprice->setAlertAboutPrice($product_id, $last_price);
								
								continue;
							}
						}
					}
				}
			}
			
			if (!$messages){
				$message = '';
				$message .= '☘ Всё окей, цены не менялись!' . PHP_EOL;
				$message .= 'Сегодня ' . date('d.m.Y') . PHP_EOL;
				$message .= 'У нас всего ' . $this->model_hobotix_hoboprice->countDNUPProducts() . ' товаров, по которым мы отслеживаем цену.' . PHP_EOL;
				
				$messages[] = $message;				
			}
			
			//	var_dump($message);
			//	die();
			
			if (count($messages) <= $this->MAX_MESSAGES){
				foreach ($messages as $message){
					$unifiedMessage .= $message . PHP_EOL;						
				}
				$this->setMessage($unifiedMessage)->sendResults();
				} else {
				
				$cntr = 0;
				$sendmessage = '';
				foreach ($messages as $message){
					
					$unifiedMessage .= $message . PHP_EOL;								
					
					if ($cntr > $this->MAX_MESSAGES && (($cntr % ($this->MAX_MESSAGES+1)) == 0)){						
						$this->setMessage($unifiedMessage)->sendResults();
						$unifiedMessage = '';
						sleep (2);
					}
					
					$cntr++;
				}
			}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}										