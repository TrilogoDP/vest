<?php
	
	class ControllerHobotixOrderQueue extends Controller {
		private $host 		= "http://65.21.193.185:9191/base/ws/ws1.1cws?wsdl";
		private $host2 		= "http://65.21.193.185:9191/llc/ws/ws1.1cws?wsdl";


		private $user 		=  "Сайт";
		private $password 	= "DthtcH24041990";

		private $user2 		=  "vest";
		private $password2 	= "vest";

		private $soapClient;
		
		public function addOrderHistory(){
			$order_id = 104899;
			
			$this->load->model('checkout/order');
			
			$this->request->cookie['_ga'] = '';
			
			$this->db->query("DELETE FROM oc_order_history WHERE order_id = '104899'");
			$this->db->query("UPDATE oc_order SET order_status_id = 0 WHERE order_id = '104899'");
			
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'), 'test', false);
		}
		
		private function echoLine($line){
			$line = str_replace('<![CDATA[', '', $line);
			$line = str_replace(']]>', '', $line);
			echo $line . PHP_EOL;			
		}
		
		private function setMessage($message){
			
			$this->echoLine('[TG BOT] Сообщение:');
			$this->echoLine($message);
			
			$this->message = $message;
			
			return $this;
		}
		
		private function sendResults(){
			$this->load->library('hobotix/TelegramSender');
			$telegramSender = new hobotix\TelegramSender;
			
			$telegramSender->setGroupID('-1001883376418');
			
			$telegramSender->SendMessage($this->message);
		}
		
		private function echoSimple($line){
			echo $line;			
		}
		
		private function memoryUnits($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		}
		
		private function SoapConnect(){			
			$this->soapClient = false;
			
			try {
				$opts = array(
				'http'=>array(
				'user_agent' => 'PHPSoapClient'
				)
				);
				$context = stream_context_create($opts);
				$this->soapClient = new SoapClient($this->host, 
				array(
				'login' 		=> $this->user,
				'password'		=> $this->password,
				'soap_version' 	=> SOAP_1_2,
				'cache_wsdl' 	=> WSDL_CACHE_NONE,
				'trace' 		=> true,
				'features' 		=> SOAP_USE_XSI_ARRAY_TYPE
				)
				);
				} catch (SoapFault $fault) {
				
				$this->echoLine("Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)");
				
				$message = '⚠️⚠️⚠️⚠️ <b>Проблема с отправкой заказов в 1С!</b>' . PHP_EOL;
				$message .= "⚠️ Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)" . PHP_EOL;
				$this->setMessage($message)->sendResults();
				
				
				die();
			}
		}

		private function SoapConnect2(){			
			$this->soapClient2 = false;
			
			try {
				$opts = array(
				'http'=>array(
				'user_agent' => 'PHPSoapClient'
				)
				);
				$context = stream_context_create($opts);
				$this->soapClient = new SoapClient($this->host2, 
				array(
				'login' 		=> $this->user2,
				'password'		=> $this->password2,
				'soap_version' 	=> SOAP_1_2,
				'cache_wsdl' 	=> WSDL_CACHE_NONE,
				'trace' 		=> true,
				'features' 		=> SOAP_USE_XSI_ARRAY_TYPE
				)
				);
				} catch (SoapFault $fault) {
				
				$this->echoLine("Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)");
				
				$message = '⚠️⚠️⚠️⚠️ <b>Проблема с отправкой ПДВ заказов в 1С!</b>' . PHP_EOL;
				$message .= "⚠️ Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)" . PHP_EOL;
				$this->setMessage($message)->sendResults();
				
				
				die();
			}
		}

		public function output() {
			if (!empty($this->request->get['orders'])){
				$this->load->model('export/exchange1c');
				$this->load->model('checkout/order');

				$tmp = explode(',', $this->request->get['orders']);
				$orders = [];
				foreach ($tmp as $order_id){
					$orders[$order_id] = $this->config->get('config_order_status_id');
				}
			
				$xml = $this->model_export_exchange1c->queryOrders($orders);

				header("Content-type: text/xml");
				$this->response->setOutput($xml);
			}
		}

		public function cron2() {
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			$this->load->model('export/exchange1c');
			$this->load->model('checkout/order');
			$this->SoapConnect2();
			
			$this->echoLine('[' . date('H:i:s') . '] Подключились к 1С. Отбираем заказы в очереди');
			
			$orders_export = $this->model_export_exchange1c->queryOrdersExport2();						
			$xml = $this->model_export_exchange1c->queryOrders($orders_export);
			
			
			if ($xml){
			
				file_put_contents(DIR_SYSTEM . '/storage/orders/last2.xml', $xml);
			
				try {
					
					$this->echoLine('[' . date('H:i:s') . '] Начали отправку');
					$this->soapClient->saveOrders(array('xmlOrders' => $xml));
					
					foreach ($orders_export as $order_id => $order_status_id){
						echo 'Заказ ' . $order_id . PHP_EOL;
						$this->db->query("DELETE FROM `" . DB_PREFIX . "order_queue` WHERE order_id = '" . (int)$order_id . "'");
						
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('exchange1c_order_status_exported'), '', true, false, '[1C QUEUE] ', true);
					}
					
					$this->echoLine('[' . date('H:i:s') . '] Закончили отправку');
					
					} 	 catch (SoapFault $fault) {
					$this->echoLine("Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)");
					
					$message = '⚠️⚠️⚠️⚠️ <b>Проблема с отправкой заказов в 1С!</b>' . PHP_EOL;
					$message .= "⚠️ Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)" . PHP_EOL;
					$this->setMessage($message)->sendResults();
					
					die();
				}			
				} else {
				$this->echoLine("Нет пока заказов на выгрузку в 1Ску");
			}
			
		}
		
		
		public function cron() {
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			$this->load->model('export/exchange1c');
			$this->load->model('checkout/order');
			$this->SoapConnect();
			
			$this->echoLine('[' . date('H:i:s') . '] Подключились к 1С. Отбираем заказы в очереди');
			
			$orders_export = $this->model_export_exchange1c->queryOrdersExport();						
			$xml = $this->model_export_exchange1c->queryOrders($orders_export);
			
			
			if ($xml){
			
				file_put_contents(DIR_SYSTEM . '/storage/orders/last.xml', $xml);
			
				try {
					
					$this->echoLine('[' . date('H:i:s') . '] Начали отправку');
					$this->soapClient->saveOrders(array('xmlOrders' => $xml));
					
					foreach ($orders_export as $order_id => $order_status_id){
						echo 'Заказ ' . $order_id . PHP_EOL;
						$this->db->query("DELETE FROM `" . DB_PREFIX . "order_queue` WHERE order_id = '" . (int)$order_id . "'");
						
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('exchange1c_order_status_exported'), '', true, false, '[1C QUEUE] ', true);
					}
					
					$this->echoLine('[' . date('H:i:s') . '] Закончили отправку');
					
					} 	 catch (SoapFault $fault) {
					$this->echoLine("Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)");
					
					$message = '⚠️⚠️⚠️⚠️ <b>Проблема с отправкой заказов в 1С!</b>' . PHP_EOL;
					$message .= "⚠️ Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)" . PHP_EOL;
					$this->setMessage($message)->sendResults();
					
					die();
				}			
				} else {
				$this->echoLine("Нет пока заказов на выгрузку в 1Ску");
			}

			$this->cron2();
			
		}
		
		public function add($route, $output, $order_id, $order_status_id) {
			$this->load->model('checkout/order');
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_queue` SET order_id = '" . $order_id . "'");
		}
		
	}					