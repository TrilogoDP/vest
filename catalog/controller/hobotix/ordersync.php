<?
	
	class ControllerHobotixOrderSync extends Controller {
		const 	MULTIPLE_PRODUCT = 'MP';
		private $host = "65.21.193.185";
		private $error = array();
		private $success = array();
		private $order_statuses = array();
		
		private function echoLine($line){
			$line = str_replace('<![CDATA[', '', $line);
			$line = str_replace(']]>', '', $line);
			echo $line . PHP_EOL;			
		}
		
		private function echoSimple($line){
			echo $line;			
		}			
		
		private function checkIfOrderStatusExists($order_status_id){
			
			foreach ($this->order_statuses as $_os){
				if ($_os['order_status_id'] == (int)$order_status_id){
					return true;
				}
			}
			
			return false;
		}
		
		private function findProduct($sku){
			$sku = trim($sku);
			
			$product_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE TRIM(sku) LIKE '" . $this->db->escape(trim($sku)) . "'");
			
			if ($product_query->num_rows && $product_query->num_rows == 1){
				return $product_query->row['product_id'];
				} elseif ($product_query->num_rows && $product_query->num_rows > 1){
				return MULTIPLE_PRODUCT;
			}
			
			
			return false;
			
		}
		
		private function findOption($sku){
			$sku = trim($sku);
			
			$exploded_sku = explode('-', $sku);

			if (count($exploded_sku) == 2 && $sku[0] == '0'){
				$sql = "SELECT * FROM `" . DB_PREFIX . "product_option_value` WHERE TRIM(sku) LIKE '" . $this->db->escape(trim($sku)) . "' OR TRIM(sku) LIKE '" . $this->db->escape(substr(trim($sku), 2)) . "'";
			} else {
				$sql = "SELECT * FROM `" . DB_PREFIX . "product_option_value` WHERE TRIM(sku) LIKE '" . $this->db->escape(trim($sku)) . "'";
			}
			
			$option_query =  $this->db->query($sql);
			
			if ($option_query->num_rows && $option_query->num_rows == 1){
				return $option_query->row;
				} elseif ($option_query->num_rows && $option_query->num_rows > 1) {
				return MULTIPLE_PRODUCT;
			}


			
			return false;
		}
		
		private function updateOrderSystemNUM($order_id, $data){
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
			system_num = '" . $this->db->escape($data) . "'
			WHERE order_id = '" . (int)$order_id . "'");
			
		}
		
		private function updateOrderDeliveryInfo($order_id, $data){
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
			shipping_code = '" . $this->db->escape($data['ИД']) . "'
			WHERE order_id = '" . (int)$order_id . "' AND shipping_code = ''");
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
			shipping_method = '" . $this->db->escape($data['Способ']) . "'
			WHERE order_id = '" . (int)$order_id . "' AND shipping_method = ''");
			
			if ($data['ИД'] == 'novaposhta.novaposhta'){
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
				payment_country = '" . $this->db->escape($data['Область']) . "',
				payment_city = '" . $this->db->escape($data['Адрес']) . "',
				shipping_country = '" . $this->db->escape($data['Область']) . "',
				shipping_city = '" . $this->db->escape($data['Адрес']) . "'
				WHERE order_id = '" . (int)$order_id . "'");
			}
			
		}
		
		private function updateCustomer($order_id, $data){
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
			firstname = '" . $this->db->escape($data['Имя']) . "',
			lastname = '" . $this->db->escape($data['Фамилия']) . "',
			shipping_firstname = '" . $this->db->escape($data['Имя']) . "',
			shipping_lastname = '" . $this->db->escape($data['Фамилия']) . "',
			payment_firstname = '" . $this->db->escape($data['Имя']) . "',
			payment_lastname = '" . $this->db->escape($data['Фамилия']) . "',
			email = '" . $this->db->escape($data['Email']) . "',
			telephone = '" . $this->db->escape($data['Телефон']) . "'
			WHERE order_id = '" . (int)$order_id . "'");
			
			$query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
			
			if ($query->num_rows && !empty($query->row['customer_id'])){
				$customer_id = $query->row['customer_id'];
				
				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET 
				firstname = '" . $this->db->escape($data['Имя']) . "',
				lastname = '" . $this->db->escape($data['Фамилия']) . "',			
				email = '" . $this->db->escape($data['Email']) . "',
				telephone = '" . $this->db->escape($data['Телефон']) . "'
				WHERE customer_id = '" . (int)$customer_id . "'");
			}
		}
		
		private function findCustomer($customer){
		}
		
		public function sync(){
			
			$this->sync2();
		/*
			$this->load->model('localisation/order_status');
			$this->load->model('catalog/product');
			$this->load->model('sale/order');
			$this->load->model('checkout/order');
			$this->load->model('extension/shipping/track_no');
			
			$responseBody = file_get_contents('php://input');
			
			if (empty($responseBody)){
				$this->error[] = 'JSON: NO_JSON';
			}
			
			if (!$this->error){
				if (!$order = json_decode($responseBody, true)){
					
					$constants = get_defined_constants(true);
					$json_errors = array();
					foreach ($constants["json"] as $name => $value) {
						if (!strncmp($name, "JSON_ERROR_", 11)) {
							$json_errors[$value] = $name;
						}
					}
					
					$this->error[] = 'JSON: ' . $json_errors[json_last_error()];
				}
			}
			
			if (!$this->error){			
				//Статусы заказов
				$this->order_statuses = $this->model_localisation_order_status->getOrderStatuses(array('nocache' => true));
				$order_id = $order['ИД'];
				$order_exists = $this->model_sale_order->getOrder($order_id);
				$order_products = $this->model_sale_order->getOrderProducts($order_id);
				
				if ($order_exists){
					
					if (!empty($order['Номер1С'])){
						$this->updateOrderSystemNUM($order_id, $order['Номер1С']);
					}
					
					//ТТН
					if (!empty($order['Доставка']) && !empty($order['Доставка']['НомерТТН'])){
						$this->model_extension_shipping_track_no->save($order_id, trim($order['Доставка']['НомерТТН']));
						$this->success[] = 'НомерТТН';
					}
					
					if (!empty($order['СтатусИД']) && $this->checkIfOrderStatusExists($order['СтатусИД'])){
						if ($order_exists['order_status_id'] != (int)$order['СтатусИД']){
							$this->model_checkout_order->addOrderHistory($order_id, (int)$order['СтатусИД'], '', 0, 0);
							$this->success[] = 'СтатусИД';
						}
					}
					
					if (!empty($order['Контрагент'])){
						$this->updateCustomer($order_id, $order['Контрагент']);
						$this->success[] = 'Контрагент';
					}
					
					if (!empty($order['Доставка'])){
						$this->updateOrderDeliveryInfo($order_id, $order['Доставка']);
						$this->success[] = 'Доставка';
					}
					
					
				}
			}
			
			$this->response->setOutput(json_encode(array('error' => $this->error, 'success' => $this->success)));
		*/
			
		}
		
		
		
		public function sync2(){
			$this->load->model('localisation/order_status');
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');
			$this->load->model('sale/order');
			$this->load->model('checkout/order');
			$this->load->model('extension/shipping/track_no');
			

			if (!empty($this->request->post['json'])){
				$responseBody = $this->request->post['json'];
			}

			if (empty($responseBody)){
				$responseBody = file_get_contents('php://input');
			}

			
			if (empty($responseBody)){
				$this->error[] = 'JSON: NO_JSON';
			}			

			$log = new Log('orders_from_1c.txt');
			$log->write($responseBody);
			
			if (!$this->error){
				if (!$order = json_decode($responseBody, true)){
					
					$constants = get_defined_constants(true);
					$json_errors = array();
					foreach ($constants["json"] as $name => $value) {
						if (!strncmp($name, "JSON_ERROR_", 11)) {
							$json_errors[$value] = $name;
						}
					}
					
					$this->error[] = 'JSON: ' . $json_errors[json_last_error()];
				}
			}
			
			if (!$this->error){			
				//Статусы заказов
				$this->order_statuses = $this->model_localisation_order_status->getOrderStatuses(array('nocache' => true));
				$order_id = $order['ИД'];
				$order_exists 	= $this->model_sale_order->getOrder($order_id);
				$order_products = $this->model_sale_order->getOrderProducts($order_id);

				$sync = true;
				if (!empty($order['Товары'])){
					foreach ($order['Товары'] as $product){
						if ($product['ИД'] == 'A00000'){
							$sync = false;
							break;
						}
					}					
				} else {
					$sync = false;
				}
				
				if ($sync){
					$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
					$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "'");
				}
				
				if ($order_exists){
					
					if (!empty($order['Номер1С'])){
						$this->updateOrderSystemNUM($order_id, $order['Номер1С']);
						$this->success[] = 'Номер1С';
					}
					
					//ТТН
					if (!empty($order['Доставка']) && !empty($order['Доставка']['НомерТТН'])){
						$this->model_extension_shipping_track_no->save($order_id, trim($order['Доставка']['НомерТТН']));
						$this->success[] = 'НомерТТН';
					}
					
					if (!empty($order['СтатусИД']) && $this->checkIfOrderStatusExists($order['СтатусИД'])){
						if ($order_exists['order_status_id'] != (int)$order['СтатусИД']){
							$this->model_checkout_order->addOrderHistory($order_id, (int)$order['СтатусИД'], '', 0, 0);
							$this->success[] = 'СтатусИД';
						}
					}
					
					if (!empty($order['Контрагент'])){
						$this->updateCustomer($order_id, $order['Контрагент']);
						$this->success[] = 'Контрагент';
					}
					
					if (!empty($order['Доставка'])){
						$this->updateOrderDeliveryInfo($order_id, $order['Доставка']);
						$this->success[] = 'Доставка';
					}
									
					
					if ($sync){
						$all_products_good = true;
						$products = array();
						foreach ($order['Товары'] as $product){
							$product_id = false;
							$option = false;
							$option_id = false;
							$product_option_id = false;
							$option_value_id = false;
							$product_option_value_id = false;
							
							$product_id = $this->findProduct($product['ИД']);						
							
							if (!$product_id){
								$option = $this->findOption($product['ИД']);
								
								if (!empty($option['product_id'])){
									$product_id 				= $option['product_id'];
									$option_id 					= $option['option_id'];
									$product_option_id 			= $option['product_option_id'];
									$option_value_id 			= $option['option_value_id'];
									$product_option_value_id 	= $option['product_option_value_id'];
								}
							}
							
							if (!$product_id){
								$this->error[] = 'NO PRODUCT BY SKU ' . trim($product['ИД']);
								$all_products_good = false;
							}
							
							if ($product_id == MULTIPLE_PRODUCT){
								$this->error[] = 'MULTIPLE_PRODUCT BY SKU ' . trim($product['ИД']);
								$all_products_good = false;
							}													
							
							if ($product_id && $product_id != MULTIPLE_PRODUCT){
								
								$real_product = $this->model_catalog_product->getProduct($product_id);
								$real_product_descriptions = $this->model_catalog_product->getProductDescriptions($product_id);
								
								$option = array();
								if ($product_option_value_id){
									$real_option = $this->model_catalog_option->getOption($option_id);
									$real_option_descriptions = $this->model_catalog_option->getOptionDescriptions($option_id);
									$real_product_option_descriptions = $this->model_catalog_option->getOptionValueDescriptions($option_id);
									
									$option_name = '';
										
									foreach ($real_product_option_descriptions as $_rpod) {	
										if ($_rpod['option_value_id'] == $option_value_id){
											$option_name = $_rpod['option_value_description'][$order_exists['language_id']]['name'];
										}
									}
									
									$option = array(										
										'product_option_id' 		=> $product_option_id,
										'product_option_value_id' 	=> $option_value_id,
										'name' 						=> $real_option_descriptions[$order_exists['language_id']]['name'],
										'value'						=> $option_name,
										'type'						=> $real_option['type']
									);
								}
								
								$price = $product['ЦенаЗаЕдиницу'];
								if (!empty($product['Скидка'])){
									$price = $product['Скидка'];
								}
								
								$total = (float)$price * (int)$product['Количество'];
								
							//	var_dump($product_id);
							//	var_dump((int)$product_id);
								
								$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET 
								order_id = '" . (int)$order_id . "',
								product_id = '" . (int)$product_id . "', 
								name = '" . $this->db->escape($real_product_descriptions[$order_exists['language_id']]['name']) . "', 
								model = '" . $this->db->escape($real_product['model']) . "', 
								quantity = '" . (int)$product['Количество'] . "', 
								price = '" . (float)$price . "', 
								total = '" . (float)$total . "',
								tax = '0', 
								reward = '0'"
								);
								
								$order_product_id = $this->db->getLastId();
								
								if ($option){
									
									$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET 
										order_id 				= '" . (int)$order_id . "', 
										order_product_id 		= '" . (int)$order_product_id . "', 
										product_option_id 		= '" . (int)$option['product_option_id'] . "', 
										product_option_value_id = '" . (int)$option['product_option_value_id'] . "', 
										name = '" . $this->db->escape($option['name']) . "', 
										`value` = '" . $this->db->escape($option['value']) . "', 
										`type` = '" . $this->db->escape($option['type']) . "'");
									
								}
							}
						}							
					}
					
					unset($products);
					unset($product);
									
					$products = $this->model_sale_order->getOrderProducts($order_id);									
					
					$sub_total = 0;
					foreach ($products as $product){
						$sub_total += $product['total'];
					}
					
					$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = '" . (float)$sub_total . "' WHERE order_id = '" . (int)$order_id . "' AND code = 'sub_total'");

					$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND value < 0");
					
					
					$totals = $this->model_sale_order->getOrderTotals($order_id);
					$total_total = 0;
					foreach ($totals as $total){
						if ($total['code'] != 'total'){
							$total_total += $total['value'];
						}
					}
					
					$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = '" . (float)$total_total . "' WHERE order_id = '" . (int)$order_id . "' AND code = 'total'");
					$this->db->query("UPDATE " . DB_PREFIX . "order SET total = '" . (float)$total_total . "' WHERE order_id = '" . (int)$order_id . "'");
					
					
					} else {
					$this->error[] = 'ЗаказНеСуществует';
				}

				
			}
			
			$this->response->setOutput(json_encode(array('error' => $this->error, 'success' => $this->success)));
			
		}
		
		
		
		
		
	}							