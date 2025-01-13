<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	/**************************************************************/
	
	class ControllerExtensionModuleOctPopupCallPhone extends Controller {
		private $worktime = '9-18';
		private $worktime_st = '10-16';
		private $worktime_su = '12-16';
		
		private function sendCallToBinotel($data){
			$log = new Log('binotel_calls.log');
			$callID = false;
			
			if (!empty($data['referer'])){
				try {
					$client = new \denostr\Binotel\Client('f5373a-6626650', '58b1a1-e82e76-1bb5d4-a37978-160c3dc1');																				
					$numbers = $client->settings->listOfEmployees();					
					
					$log->write('Заявка на звонок на номер ' . $data['telephone']);
					$log->write('Проверяем статус на линии...');
					
					$number = false;
					foreach ($numbers as $id => $info){
						if (!empty($info['extNumber']) && in_array($info['extNumber'], array(901, 902))){
							$log->write('Сотрудник ' . $id . ' ' . $info['presenceState'] . ', ' . $info['extStatus']['status'] . ', ' . $info['endpointData']['status']['status']);
							
							if (isset($info['endpointData']['status']) && $info['presenceState'] == 'active' && $info['extStatus']['status'] == 'online' && $info['endpointData']['status']['status'] == 'online'){
								$number = $info['endpointData']['internalNumber'];
								break;
							}
						}
					}
					
					if ($number){
						$log->write('Попытка инициации звонка на номер ' . preg_replace('/\D/', '',  $data['telephone']) . ', сотрудник ' . $number);
						$callID = $client->calls->extToPhone([
						'ext_number' => $number,
						'phone_number' => preg_replace('/\D/', '',  $data['telephone']),
						]);
						
						
						if ($callID){
							$log->write('Айдишка звонка: ' . $callID);
						}
						} else {
						return false;
					}
					
					
					} catch (\denostr\Binotel\Exception $e) {
					$log->write(sprintf('Error (%d): %s', $e->getCode(), $e->getMessage()));
					return false;
				}
			}
			
			return $callID;
			
		}
		
		public function index() {
			$data = array();
			
			$this->load->model('catalog/product');
			$this->load->language('extension/module/oct_popup_call_phone');
			
			$data['oct_techstore_data'] = $oct_data = $this->config->get('oct_techstore_data');
			
			$oct_popup_call_phone_data         = (array) $this->config->get('oct_popup_call_phone_data');
			$data['oct_popup_call_phone_data'] = $oct_popup_call_phone_data;
			
			//NOTIME				
			date_default_timezone_set("Europe/Kiev"); 	
			
			if (date('N') == 6){
				$worktime = explode('-', $this->worktime_st);
				} elseif (date('N') == 7){
				$worktime = explode('-', $this->worktime_su);
				} else {
				$worktime = explode('-', $this->worktime);
			}
			$worktime_start = $worktime[0];
			$worktime_end = $worktime[1];
			
			$is_worktime = ((int)date('H') >= (int)$worktime_start && (int)date('H') <= (int)$worktime_end);
			
			if ($is_worktime){
				$data['text_call_back']   = $this->language->get('call_back_intime');
				} else {
				$data['text_call_back']   = $this->language->get('call_back_notime');
			}
			
			$data['heading_title']   = $this->language->get('heading_title');
			$data['button_close']    = $this->language->get('button_close');
			$data['button_send']     = $this->language->get('button_send');
			$data['enter_name']      = $this->language->get('enter_name');
			$data['enter_telephone'] = $this->language->get('enter_telephone');
			$data['enter_comment']   = $this->language->get('enter_comment');
			$data['enter_time']      = $this->language->get('enter_time');
			$data['text_select']     = $this->language->get('text_select');
			$data['text_loading']    = $this->language->get('text_loading');
			$data['text_wait']    	 = $this->language->get('text_wait');
			$data['referer']  		 = isset($this->request->server['HTTP_REFERER'])?$this->request->server['HTTP_REFERER']:HTTPS_SERVER;
			
			$data['name']      = ($this->customer->isLogged()) ? $this->customer->getFirstName() : '';
			$data['telephone'] = ($this->customer->isLogged()) ? $this->customer->getTelephone() : '';
			
			if (isset($oct_data['cont_phones']) && !empty($oct_data['cont_phones'])) {
				$data['oct_techstore_cont_phones'] = array_values(array_filter(explode(PHP_EOL, $oct_data['cont_phones'])));
				} else {
				$data['oct_techstore_cont_phones'] = false;
			}
			
			$data['comment'] = '';
			$data['time']    = '';
			$data['mask']    = ($oct_popup_call_phone_data['mask']) ? $oct_popup_call_phone_data['mask'] : '';
			
			if (isset($oct_data['terms']) && $oct_data['terms']) {
				$this->load->model('catalog/information');
				
				$information_info = $this->model_catalog_information->getInformation($oct_data['terms']);
				
				if ($information_info) {
					$data['text_terms'] = sprintf($this->language->get('text_oct_terms'), $this->url->link('information/information', 'information_id=' . $oct_data['terms'], 'SSL'), $information_info['title'], $information_info['title']);
					} else {
					$data['text_terms'] = '';
				}
				} else {
				$data['text_terms'] = '';
			}
			
			$this->response->setOutput($this->load->view('extension/module/oct_popup_call_phone', $data));
		}
		
		public function validate(){
			$json = array();
			
			$this->language->load('extension/module/oct_popup_call_phone');
			
			$this->load->model('extension/module/oct_popup_call_phone');
			
			$oct_popup_call_phone_data = (array) $this->config->get('oct_popup_call_phone_data');
			
			if (isset($this->request->post['name'])) {
				if ((isset($oct_popup_call_phone_data['name']) && $oct_popup_call_phone_data['name'] == 2) && (utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
					$json['error']['field']['name'] = $this->language->get('error_name');
				}
				} else {
				if ((isset($oct_popup_call_phone_data['name']) && $oct_popup_call_phone_data['name'] == 2) && (utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
					$json['error']['field']['name'] = $this->language->get('error_name');
				}
			}
			
			if (isset($this->request->post['time'])) {
				if (isset($oct_popup_call_phone_data['time']) && $oct_popup_call_phone_data['time'] == 2) {
					if (empty($this->request->post['time'])) {
						$json['error']['field']['time'] = $this->language->get('error_time');
					}
				}
				} else {
				if (isset($oct_popup_call_phone_data['time']) && $oct_popup_call_phone_data['time'] == 2) {
					if (empty($this->request->post['time'])) {
						$json['error']['field']['time'] = $this->language->get('error_time');
					}
				}
			}
			
			if (isset($this->request->post['telephone']) && !empty($oct_popup_call_phone_data['mask'])) {
				$phone_count = utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $oct_popup_call_phone_data['mask']));
				
				if ((isset($oct_popup_call_phone_data['telephone']) && $oct_popup_call_phone_data['telephone'] == 2) && (utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])) < $phone_count || 
				strpos(trim(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])), '380') !== 0)) {
					$json['error']['field']['telephone'] = $this->language->get('error_telephone_mask');
				}
				} elseif (isset($this->request->post['telephone'])) {
				if ((isset($oct_popup_call_phone_data['telephone']) && $oct_popup_call_phone_data['telephone'] == 2) && (utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])) > 15 || utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])) < 3)) {
					$json['error']['field']['telephone'] = $this->language->get('error_telephone');
				}
			}
			
			if (isset($this->request->post['comment'])) {
				if ((isset($oct_popup_call_phone_data['comment']) && $oct_popup_call_phone_data['comment'] == 2) && (utf8_strlen($this->request->post['comment']) < 3) || (utf8_strlen($this->request->post['comment']) > 500)) {
					//$json['error']['field']['comment'] = $this->language->get('error_comment');
				}
				} else {
				if (isset($oct_popup_call_phone_data['comment']) && $oct_popup_call_phone_data['comment'] == 2) {
					//$json['error']['field']['comment'] = $this->language->get('error_comment');
				}
			}
			
			$oct_data = $this->config->get('oct_techstore_data');
			
			if (isset($oct_data['terms']) && $oct_data['terms']) {
				if (!isset($this->request->post['terms'])) {
					$this->load->model('catalog/information');
					
					$information_info = $this->model_catalog_information->getInformation($oct_data['terms']);
					
					$json['error']['field']['terms'] = sprintf($this->language->get('error_oct_terms'), $information_info['title']);
				}
			}
			
			$data = array();
			$callData = array();
			
			if (!isset($json['error'])) {
				$json['success'] = 'success';
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			
			
		}
		
		public function send() {
			

			
			$json = array();
			
			$this->language->load('extension/module/oct_popup_call_phone');
			
			$this->load->model('extension/module/oct_popup_call_phone');

			if (empty($this->request->server['HTTP_X_REQUESTED_WITH']) || strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
				header('HTTP/1.1 301 Fuck off, man');
				header('Location: https://' . $this->request->server['REMOTE_ADDR']);
				return;
			}

			if ($this->model_extension_module_oct_popup_call_phone->limitCalls(['ip' => $this->request->server['REMOTE_ADDR']]) > 3){
				//addToFirewall($this->request->server['REMOTE_ADDR']);				
				
				header('HTTP/1.1 301 Fuck off, man');
				header('Location: https://' . $this->request->server['REMOTE_ADDR']);
				return;
			}
			
			$oct_popup_call_phone_data = (array) $this->config->get('oct_popup_call_phone_data');
			
			if (isset($this->request->post['name'])) {
				if ((isset($oct_popup_call_phone_data['name']) && $oct_popup_call_phone_data['name'] == 2) && (utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
					$json['error']['field']['name'] = $this->language->get('error_name');
				}
				} else {
				if ((isset($oct_popup_call_phone_data['name']) && $oct_popup_call_phone_data['name'] == 2) && (utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
					$json['error']['field']['name'] = $this->language->get('error_name');
				}
			}
			
			if (isset($this->request->post['time'])) {
				if (isset($oct_popup_call_phone_data['time']) && $oct_popup_call_phone_data['time'] == 2) {
					if (empty($this->request->post['time'])) {
						$json['error']['field']['time'] = $this->language->get('error_time');
					}
				}
				} else {
				if (isset($oct_popup_call_phone_data['time']) && $oct_popup_call_phone_data['time'] == 2) {
					if (empty($this->request->post['time'])) {
						$json['error']['field']['time'] = $this->language->get('error_time');
					}
				}
			}
			
			if (isset($this->request->post['telephone']) && !empty($oct_popup_call_phone_data['mask'])) {
				$phone_count = utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $oct_popup_call_phone_data['mask']));
				
				if ((isset($oct_popup_call_phone_data['telephone']) && $oct_popup_call_phone_data['telephone'] == 2) && (utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])) < $phone_count || 
				strpos(trim(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])), '380') !== 0)) {
					$json['error']['field']['telephone'] = $this->language->get('error_telephone_mask');
				}
				} elseif (isset($this->request->post['telephone'])) {
				if ((isset($oct_popup_call_phone_data['telephone']) && $oct_popup_call_phone_data['telephone'] == 2) && (utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])) > 15 || utf8_strlen(str_replace(array('_','-','(',')','+',' '), "", $this->request->post['telephone'])) < 3)) {
					$json['error']['field']['telephone'] = $this->language->get('error_telephone');
				}
			}
			
			if (isset($this->request->post['comment'])) {
				if ((isset($oct_popup_call_phone_data['comment']) && $oct_popup_call_phone_data['comment'] == 2) && (utf8_strlen($this->request->post['comment']) < 3) || (utf8_strlen($this->request->post['comment']) > 500)) {
					//$json['error']['field']['comment'] = $this->language->get('error_comment');
				}
				} else {
				if (isset($oct_popup_call_phone_data['comment']) && $oct_popup_call_phone_data['comment'] == 2) {
					//$json['error']['field']['comment'] = $this->language->get('error_comment');
				}
			}
			
			$oct_data = $this->config->get('oct_techstore_data');
			
			if (isset($oct_data['terms']) && $oct_data['terms']) {
				if (!isset($this->request->post['terms'])) {
					$this->load->model('catalog/information');
					
					$information_info = $this->model_catalog_information->getInformation($oct_data['terms']);
					
					$json['error']['field']['terms'] = sprintf($this->language->get('error_oct_terms'), $information_info['title']);
				}
			}
			
			$data = array();
			$callData = array();
			
			if (!isset($json['error'])) {
				
				//NOTIME				
				date_default_timezone_set("Europe/Kiev"); 	
				
				if (date('N') == 6){
					$worktime = explode('-', $this->worktime_st);
					} elseif (date('N') == 7){
					$worktime = explode('-', $this->worktime_su);
					} else {
					$worktime = explode('-', $this->worktime);
				}
				$worktime_start = $worktime[0];
				$worktime_end = $worktime[1];
				
				$is_worktime = ((int)date('H') >= (int)$worktime_start && (int)date('H') <= (int)$worktime_end);
				
				
				
				$post_data = $this->request->post;
				
				$callData['name'] = 'Callback';
				if (isset($post_data['name'])) {
					$data[] = array(
                    'name' => 'name',
                    'value' => $post_data['name']
					);
					$callData['name'] .= ' ' . trim($post_data['name']);
				}
				
				$callData['telephone'] = '';
				if (isset($post_data['telephone'])) {
					$data[] = array(
                    'name' => 'telephone',
                    'value' => $post_data['telephone']
					);
					$callData['telephone'] = trim($post_data['telephone']);
				}
				
				$callData['comment'] = '';
				if (isset($post_data['comment'])) {
					$data[] = array(
                    'name' => 'comment',
                    'value' => $post_data['comment']
					);
					$callData['comment'] = $post_data['comment'];
				}
				
				if (isset($post_data['time'])) {
					$data[] = array(
                    'name' => 'time',
                    'value' => $post_data['time']
					);
				}
				
				$callData['referer'] = '';
				if (isset($post_data['referer'])) {
					$data[] = array(
                    'name' => 'referer',
                    'value' => $post_data['referer']
					); 
					$callData['referer'] = $post_data['referer'];
				}
				
				$data_send = array(
                'info' 	=> serialize($data),
                'ip'	=> $this->request->server['REMOTE_ADDR']
				);

				$lastCallId = $this->model_extension_module_oct_popup_call_phone->addRequest($data_send);
				
				$binotel_call_id = 0;
				
				if ($is_worktime){
					$binotel_call_id = $this->sendCallToBinotel($callData);
					$this->model_extension_module_oct_popup_call_phone->setBinotelCallId($lastCallId, $binotel_call_id);
					$data_send['binotel_call_id'] = $binotel_call_id;				
				}
				
				if ($binotel_call_id){									
					$json['output'] = $this->language->get('text_call');
					
					} else {			
					
					if ($is_worktime){
						$json['output'] = $this->language->get('text_nocall');
						} else {
						$json['output'] = $this->language->get('text_notime');
					}
					
					
					//ORDER DATA
					$order_data = array(
					'invoice_prefix' => $this->config->get('config_invoice_prefix'),
					'store_id' => $store_id = (int) $this->config->get('config_store_id'),
					'store_name' => $this->config->get('config_name'),
					'store_url' => $store_id ? $this->config->get('config_url') : HTTP_SERVER,
					'customer_id' => $this->customer->isLogged() ? $this->customer->getId() : 0,
					'customer_group_id' => $this->customer->isLogged() ? $this->customer->getGroupId() : $this->config->get('config_customer_group_id'),
					'firstname' => $callData['name'],
					'lastname' => '',
					'email' => $alt_notify_email,
					'telephone' => $callData['telephone'],
					'fax' => '',
					'shipping_city' => '',
					'shipping_postcode' => '',
					'shipping_country' => '',
					'shipping_country_id' => '',
					'shipping_zone_id' => '',
					'shipping_zone' => '',
					'shipping_address_format' => '',
					'shipping_firstname' => $callData['name'],
					'shipping_lastname' => '',
					'shipping_company' => '',
					'shipping_address_1' => '',
					'shipping_address_2' => '',
					'shipping_code' => '',
					'shipping_method' => '',
					'payment_city' => '',
					'payment_postcode' => '',
					'payment_country' => '',
					'payment_country_id' => '',
					'payment_zone' => '',
					'payment_zone_id' => '',
					'payment_address_format' => '',
					'payment_firstname' => $callData['name'],
					'payment_lastname' => '',
					'payment_company' => '',
					'payment_address_1' => '',
					'payment_address_2' => '',
					'payment_company_id' => '',
					'payment_tax_id' => '',
					'payment_code' => (version_compare(VERSION, '2.1.0.1') < 0) ? '' : 'free_checkout',
					'payment_method' => (version_compare(VERSION, '2.1.0.1') < 0) ? '' : '--',
					'forwarded_ip' => $forwarded_ip,
					'user_agent' => $user_agent,
					'accept_language' => $accept_language,
					'vouchers' => array(),
					'comment' => $callData['comment'] . PHP_EOL . 'Телефон: ' . $callData['telephone'] . PHP_EOL .'Страница: ' . $callData['referer'],
					'total' => $total,
					'reward' => '',
					'affiliate_id' => '',
					'tracking' => '',
					'commission' => '',
					'marketing_id' => '',
					'language_id' => $this->config->get('config_language_id'),
					'currency_id' => $this->currency->getId($this->session->data['currency']),
					'currency_code' => $this->session->data['currency'],
					'currency_value' => $this->currency->getValue($this->session->data['currency']),
					'ip' => $this->request->server['REMOTE_ADDR'],
					'products' => array(),
					'totals' => array()
					);
					
					$this->load->model('checkout/order');
					$order_id = $this->model_checkout_order->addOrder($order_data);
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 18);
					
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . '18' . "', date_modified = NOW() WHERE order_id = '" . $order_id . "'");
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_queue` SET order_id = '" . $order_id . "'");					
					
					if ($oct_popup_call_phone_data['notify_status']) {
						$html_data['date_added']      = date('d.m.Y H:i:s', time());
						$html_data['logo']            = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
						$html_data['store_name']      = $this->config->get('config_name');
						$html_data['store_url']       = $this->config->get('config_url');
						$html_data['text_info']       = $this->language->get('text_info');
						$html_data['text_date_added'] = $this->language->get('text_date_added');
						$html_data['data_info']       = $data;
						
						$html = $this->load->view('mail/oct_popup_call_phone_mail', $html_data);
						
						if (version_compare(VERSION, '2.0.2', '<')) {
							$mail = new Mail($this->config->get('config_mail'));
							} else {
							$mail                = new Mail();
							$mail->protocol      = $this->config->get('config_mail_protocol');
							$mail->parameter     = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = (version_compare(VERSION, '2.0.3', '<')) ? $this->config->get('config_mail_smtp_host') : $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port     = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout  = $this->config->get('config_mail_smtp_timeout');
						}
						
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->config->get('config_name'));
						$mail->setSubject($this->language->get('heading_title') . " -- " . $html_data['date_added']);
						$mail->setHtml($html);
						
						$emails = explode(',', $oct_popup_call_phone_data['notify_email']);
						
						foreach ($emails as $email) {
							if ($email && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
								$mail->setTo($email);
								$mail->send();
							}
						}
											
					}
				}
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}
