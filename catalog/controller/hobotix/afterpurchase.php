<?php
	
	class ControllerHobotixAfterPurchase extends Controller {
		private $days = 10;
		private $utm_medium = 'zapit-pro-vidguk';
		
		/* EMAIL */
		private $utm_email = 'utm_source=email&utm_medium=zapit-pro-vidguk&utm_campaign=vidguk&utm_term={order_id}';			
		private $emails = array(
		'dummy-email-simple@localhost.net',
		'technik@vest.in.ua',
		'dummy-email-simple@localhost.n'
		);
		private $email_template = array(
		'1' => 'afterpurchasemail_ru.tpl',
		'3' => 'afterpurchasemail_ua.tpl'
		);
		private $email_title_template = array(
		// '1' => '{name}, оставьте отзыв про {product_name} и получите скидку до -20%!',
		'1' => '{name}, залиште відгук про {product_name} та отримайте знижку до -20%!',
		'3' => '{name}, залиште відгук про {product_name} та отримайте знижку до -20%!'
		);
		private $email_host = 'smtp-pulse.com';
		private $email_port = '2525';
		private $email_login = 'verom.aukro@gmail.com';
		private $email_passwd = 'rZBaNFACRBRfKJ';
		
		/* SMS */
		private $utm_sms = 'utm_source=sms&utm_medium=zapit-pro-vidguk&utm_campaign=vidguk&utm_term={order_id}';		
		private $sms_template = array(
		// '1' => 'До -20% скидки за отзыв про покупку! {link}',
		'1' => 'До -20% знижки за відгук про покупку! {link}',
		'3' => 'До -20% знижки за відгук про покупку! {link}'
		);
		private $sms_log;
		private $login = '380961692783';
		private $password = 'DthtcHjvfy2404';
		private $token = 'jZK9saoXuc-i5UE';
		private $alpha = 'VEST.in.ua';
		/* SMS END */
		
		public function index(){	
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}		
		}	
		
		public function setAFPKey(){			
			if (isset($this->request->get['utm_medium']) && $this->request->get['utm_medium'] == $this->utm_medium && isset($this->request->get['utm_term']) && (int)$this->request->get['utm_term'] && isset($this->request->get['product_id'])){
				
				$query = $this->db->ncquery("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$this->request->get['utm_term'] . "' AND product_id = '" . (int)$this->request->get['product_id'] . "'");
				
				if ($query->num_rows){			
					$this->session->data['afp_key'] = md5('vest' . $this->request->get['product_id'] . $this->request->get['utm_term']);
					$this->session->data['afp_id'] = (int)$this->request->get['utm_term'];
					} else {
					//$this->deleteAFPKey();
				}
			}		
		}
		
		public function validateAFPkey(){
			if (isset($this->request->get['product_id']) && isset($this->session->data['afp_key']) && isset($this->session->data['afp_id'])){						
				if ($this->session->data['afp_key'] == md5('vest' . $this->request->get['product_id'] . $this->session->data['afp_id'])){
					
					return (int)$this->session->data['afp_id'];
				
				/*
					$query = $this->db->ncquery("SELECT * FROM `" . DB_PREFIX . "order_afp` WHERE order_id = '" . (int)$this->session->data['afp_id'] . "' AND ISNULL(afp_promocode)");
					
					if (!$query->num_rows){					
						return (int)$this->session->data['afp_id'];
					}
				*/
				}
			}
			
			return false;
		}
		
		
		public function deleteAFPKey(){
			if (isset($this->session->data['afp_key'])){
				unset($this->session->data['afp_key']);
			}
			if (isset($this->session->data['afp_id'])){
				unset($this->session->data['afp_id']);
			}
		}
		
		private function normalizePhone($phone){
			
			if ($phone[0] == '+'){
				$phone = substr($phone, 1);			
			}
			
			$phone = preg_replace("/\D+/", "", $phone);
			
			if ($phone[0] == '3'){
				$phone = '+' . $phone;			
			}
			
			if ($phone[0] == '8'){
				$phone = '+3' . $phone;			
			}
			
			if ($phone[0] == '0'){
				$phone = '+38' . $phone;			
			}
			
			if ($phone[0] == '+'){
				$phone = substr($phone, 1);			
			}
			
			$phone = '+' . preg_replace("/\D+/", "", $phone);
			
			return $phone;
			
		}
		
		private function sendSMS($sms){
			
			$json = array(
			"phone" => array($sms['to']),
			"message" => $sms['text'],
			"src_addr" => $this->alpha
			);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token, 'Content-type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
			curl_setopt($ch, CURLOPT_URL, 'https://im.smsclub.mobi/sms/send');
			$result = curl_exec($ch);
			
			if ($json = json_decode($result, true)){
				if (isset($json['success_request']) && isset($json['success_request']['info'])){
					return true;
				}
			}
			
			return false;
			
		}
		
		private function sendMail($to, $subject, $html){
			$to = trim($to);
			
			try{
				$mail = new Mail();
				
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setHtml($html);
				
				$mail->setTo($to);
				
				$mail->protocol      = 'smtp';
				$mail->parameter     = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->email_host;
				$mail->smtp_username = $this->email_login;
				$mail->smtp_password = $this->email_passwd;
				$mail->smtp_port     = $this->email_port;
				$mail->smtp_timeout  = $this->config->get('config_mail_smtp_timeout');
				
				$mail->send(false);
				
				
				} catch (\Exception $e){
				$this->echoLine('[VEST] ERROR Почта не отправляется, пропускаем ' . $e->getMessage());
				return false;
			}
			
			return true;
			
			
		}
		
		public function template(){
			
			$this->load->model('account/order');
			$this->load->model('tool/image');
			
			$order_id = 43243;
			
			$order = $this->model_account_order->getOrder($order_id);
			$product = $this->getProduct($order);
			
			$product = $this->getProduct($order, 'email');
			
			$html_data['image'] = $this->model_tool_image->resize($product['image'], 300, 300);
			$html_data['name'] = $product['name'];
			$html_data['firstname'] = $order['firstname'];
			$html_data['link'] = $product['link'];
			$html_data['date_added']      = date('d.m.Y H:i:s', time());
			$html_data['logo']            = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo_small');
		//	$html_data['logo']			  = $this->model_tool_image->resize($this->config->get('config_logo'), 135, 50);
			$html_data['store_name']      = $this->config->get('config_name');
			$html_data['store_url']       = $this->config->get('config_url');
			
			// $subject = str_replace(array('{name}', '{product_name}'), array($order['firstname'], $product['name']), $this->email_title_template[$order['language_id']]);
			$subject = str_replace(array('{name}', '{product_name}'), array($order['firstname'], $product['name']), $this->email_title_template[3]);
			
			// $html = $this->load->view('mail/' . $this->email_template[$order['language_id']], $html_data);
			$html = $this->load->view('mail/' . $this->email_template[3], $html_data);
			
			if (!empty($this->request->get['email'])){
				$this->sendMail('v.zaichikov@gmail.com', $subject, $html);
			//	$this->sendMail('veresrv@vest.in.ua', $subject, $html);
			//	$this->sendMail('verom.aukro@gmail.com', $subject, $html);
			}
			
			$this->response->setOutput($html);
			
		}
		
		private function echoLine($line){
			echo $line . PHP_EOL;
		}
		
		private function validateEmail($email){
			
			if (!trim($email) || in_array($email, $this->emails) || !strpos($email, '@')){
				return false;
			}
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return false;
			}
			
			return true;	
		}
		
		private function getProduct($order, $type = false){
			$query = $this->db->query("SELECT pd.name, p.image, op.product_id FROM `" . DB_PREFIX . "order_product` op
			LEFT JOIN `" . DB_PREFIX . "product` p ON p.product_id = op.product_id
			LEFT JOIN `" . DB_PREFIX . "product_description` pd ON pd.product_id = op.product_id
			WHERE order_id = '" . (int)$order['order_id'] . "'
			AND pd.language_id = '3'
			ORDER BY op.price DESC LIMIT 1;
			");
			
			if (!$type){
				return $query->num_rows;
			}
			
			if ($query->num_rows){
				
				if ($type == 'sms'){					
					return array(
					'link' => $this->shortAlias->shortenURL($this->url->link('product/product', 'product_id=' . $query->row['product_id'] . '&' . str_replace('{order_id}', $order['order_id'], $this->utm_sms))),
					'image' => $query->row['image'],
					'name' => $query->row['name']
					);
				}
				
				if ($type == 'email'){
					return array(
					'link' => $this->shortAlias->shortenURL($this->url->link('product/product', 'product_id=' . $query->row['product_id'] . '&' . str_replace('{order_id}', $order['order_id'], $this->utm_email))),
					'image' => $query->row['image'],
					'name' => $query->row['name']
					);
					
				}
				
				} else {
				return false;
			}
		}	
		
		private function setOrderSent($order, $afp_type){
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_afp` SET order_id = '" . (int)$order['order_id'] . "', afp_type = '" . $this->db->escape($afp_type) . "', afp_sent_date = NOW() ON DUPLICATE KEY UPDATE afp_sent_date = NOW()");		
		}
		
		public function cron(){	
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			$this->load->model('account/order');
			$this->load->model('tool/image');
			//GETTING ORDERS
			
			$query = $this->db->query("SELECT DISTINCT order_id FROM `" . DB_PREFIX . "order` 
			WHERE order_status_id = 5 
			AND order_id NOT IN (SELECT order_id FROM " . DB_PREFIX . "order_afp WHERE 1)
			AND order_id IN (SELECT order_id FROM `" . DB_PREFIX . "order_history` WHERE order_status_id = 5  AND DATE(date_added) = '" . date('Y-m-d', strtotime('-' . $this->days . ' day')) . "')
			");						

			// $orders = 	$query->rows;
			// $orders = [
			// 	[
			// 		'order_id' => '165019'
			// 	]
			// ];
			
			$order_counter = 0;
			$sms_counter = 0;
			$email_counter = 0;
			foreach ($orders as $row){
				$order = $this->model_account_order->getOrder($row['order_id']);
				$product = $this->getProduct($order);
				
				if ($order && $order['order_id'] && $product){
					$order_counter++;
					
					$this->echoLine('[VEST] Заказ ' . $order['order_id']);
					
					if ($this->validateEmail($order['email'])){
						
						$this->echoLine('[VEST] Почта ' . $order['email']);						
						
						$product = $this->getProduct($order, 'email');
						
						$html_data['image'] 		= $this->model_tool_image->resize($product['image'], 300, 300);
						$html_data['name'] 			= $product['name'];
						$html_data['firstname'] 	= $order['firstname'];
						$html_data['link'] 			= $product['link'];
						$html_data['date_added']      = date('d.m.Y H:i:s', time());
						$html_data['logo']            = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
						$html_data['store_name']      = $this->config->get('config_name');
						$html_data['store_url']       = $this->config->get('config_url');
						
						// $subject = str_replace(array('{name}', '{product_name}'), array($order['firstname'], $product['name']), $this->email_title_template[$order['language_id']]);
						$subject = str_replace(array('{name}', '{product_name}'), array($order['firstname'], $product['name']), $this->email_title_template[3]);
						
						// $html = $this->load->view('mail/' . $this->email_template[$order['language_id']], $html_data);
						$html = $this->load->view('mail/' . $this->email_template[3], $html_data);
						
						$sent = $this->sendMail($order['email'], $subject, $html);
						
						$this->echoLine('	[VEST] EMAIL ' . $subject);
						
						$afp_type = 'email';
						$email_counter++;
						
						} else {
						$this->echoLine('[VEST] SMS ' . $order['telephone']);
						
						$product = $this->getProduct($order, 'sms');
						
						if (mb_strlen($product['name']) >= 56){
							$product['name'] = mb_substr($product['name'], 0, 56);
							$product['name'] = trim($product['name']);
							$product['name'] .= '...';
							} else {
							$product['name'] .= '.';
						}
						
						$sms = array(
						'to' => $this->normalizePhone($order['telephone']),
						// 'text' => str_replace(array('{product_name}', '{link}'), array($product['name'], $product['link']), $this->sms_template[$order['language_id']])
						'text' => str_replace(array('{product_name}', '{link}'), array($product['name'], $product['link']), $this->sms_template[3])
						);						
						
						$this->sendSMS($sms);
						$this->echoLine('	[VEST] SMS ' . $sms['text']);
						
						$sent = true;
						
						$afp_type = 'sms';
						$sms_counter++;
						
					}
					
					if ($sent){
						$this->setOrderSent($order, $afp_type);			
					}
				}
			}
			
			
			$this->echoLine('[VEST] Заказов Всего ' . $order_counter);
			$this->echoLine('[VEST] Email Всего ' . $email_counter);
			$this->echoLine('[VEST] SMS Всего ' . $sms_counter);
			
		}
		
		
	}																			