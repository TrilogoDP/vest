<?
	
	class ControllerHobotixPreorderCron extends Controller {
		private $sms_log;
		private $login = '380961692783';
		private $password = 'DthtcHjvfy2404';
		private $token = 'jZK9saoXuc-i5UE';
		private $alpha = 'VEST.in.ua';
		private $utm = 'utm_source=sms&utm_medium=forma_nema_v_nayavnosti&utm_campaign=rozsilka_towar_v_nayavnosti&utm_term=View_product';
		private $text = array(
		// '1' => '{product_name} уже в наличии! {link}',
		'1' => '{product_name} вже в наявності! {link}',
		'3' => '{product_name} вже в наявності! {link}'
		);
		
		private function echoLine($line){
			$line = str_replace('<![CDATA[', '', $line);
			$line = str_replace(']]>', '', $line);
			echo $line . PHP_EOL;			
		}
		
		private function echoSimple($line){
			echo $line;			
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
		
		public function cron(){
			
			$this->db->ncquery("DELETE FROM oc_oct_product_preorder WHERE DATE(date_added) < DATE_SUB(NOW(), INTERVAL 3 MONTH)");
			
			$query = $this->db->ncquery("SELECT * FROM oc_oct_product_preorder opp LEFT JOIN oc_product p ON p.product_id = opp.product_id WHERE opp.sms_text = '' AND opp.product_id > 0 AND p.quantity > 0 AND opp.option_id = 0 AND opp.option_value_id = 0");		
			
			$this->echoLine('');
			$this->echoLine('[PC] Нашли ' . $query->num_rows . ' товаров');
			
			$product_quantities 		= [];
			$product_quantity_counters 	= [];

			foreach ($query->rows as $row){
				if ($row['product_id']){
					$product_quantities[$row['product_id']] 		= $row['quantity'];
					$product_quantity_counters[$row['product_id']] 	= 0;
				}
			}
			unset($row);			
			
			foreach ($query->rows as $row){
				if ($row['product_id']){				
					if ($product_quantity_counters[$row['product_id']] >= $product_quantities[$row['product_id']]){
						$this->echoLine('[PC] Товар ' . $row['product_id'] . ': пропускаем по количеству.');
						continue;
					}
				
					$product_name = $this->db->ncquery("SELECT name FROM oc_product_description WHERE product_id = '" . (int)$row['product_id'] . "' AND language_id = '3'")->row['name'];
					$url = $this->url->link('catalog/product', 'product_id=' . $row['product_id'] . '&' . $this->utm);
					$url = $this->shortAlias->shortenURL($url);
					
					$sms = array(
						'to' => $this->normalizePhone($row['telephone']),
						'text' => str_replace(array('{product_name}', '{link}'), array($product_name, $url), $this->text[3])
					);
					
					$this->echoLine('[PC] ' . $row['telephone'] . ': ' . $sms['text'] . ', длина ' . mb_strlen($sms['text']));

					if ($this->sendSMS($sms)){
						$this->db->query("UPDATE oc_oct_product_preorder SET sms_text = '" . $this->db->escape($sms['text']) . "', sms_date_added = NOW() WHERE request_id = '" . (int)$row['request_id'] . "'");
						
						$product_quantity_counters[$row['product_id']]++;
					}				
				}
			}

			$query2 = $this->db->ncquery("SELECT * FROM oc_oct_product_preorder opp LEFT JOIN oc_product_option_value opv ON (opv.product_id = opp.product_id AND opv.product_option_id = opp.option_id AND opv.product_option_value_id = opp.option_value_id) WHERE opp.sms_text = '' AND opp.product_id > 0 AND opv.quantity > 0 AND opp.option_id > 0 AND opp.option_value_id > 0");

			$product_quantities 		= [];
			$product_quantity_counters 	= [];

			foreach ($query2->rows as $row){
				if ($row['product_option_value_id']){
					$product_quantities[$row['product_option_value_id']] 			= $row['quantity'];
					$product_quantity_counters[$row['product_option_value_id']] 	= 0;
				}
			}
			unset($row);	

			foreach ($query2->rows as $row){
				if ($row['product_option_value_id']){				
					if ($product_quantity_counters[$row['product_option_value_id']] >= $product_quantities[$row['product_option_value_id']]){
						$this->echoLine('[PC] Опция ' . $row['product_option_value_id'] . ': пропускаем по количеству.');
						continue;
					}
				
					$product_name = $this->db->ncquery("SELECT name FROM oc_product_description WHERE product_id 		= '" . (int)$row['product_id'] . "' AND language_id = '3'")->row['name'];
					$option_name  = $this->db->ncquery("SELECT ovd.name FROM oc_product_option_value opv LEFT JOIN oc_option_value_description ovd ON (ovd.option_id = opv.option_id AND ovd.option_value_id = opv.option_value_id) WHERE opv.product_id = '" . (int)$row['product_id'] . "' AND opv.product_option_id = '" . (int)$row['product_option_id'] . "' AND  opv.product_option_value_id = '" . (int)$row['product_option_value_id'] . "' AND ovd.language_id = 3")->row['name'];	
					$product_name .= ' ' . $option_name;

					$url = $this->url->link('catalog/product', 'product_id=' . $row['product_id'] . '&option_id=' . $row['product_option_value_id'] . '&' . $this->utm);
					$url = $this->shortAlias->shortenURL($url);					
					
					$sms = array(
						'to' => $this->normalizePhone($row['telephone']),
						'text' => str_replace(array('{product_name}', '{link}'), array($product_name, $url), $this->text[3])
					);
					
					$this->echoLine('[PC] ' . $row['telephone'] . ': ' . $sms['text'] . ', длина ' . mb_strlen($sms['text']));

					if ($this->sendSMS($sms)){
						$this->db->query("UPDATE oc_oct_product_preorder SET sms_text = '" . $this->db->escape($sms['text']) . "', sms_date_added = NOW() WHERE request_id = '" . (int)$row['request_id'] . "'");
						
						$product_quantity_counters[$row['product_option_value_id']]++;
					}				
				}
			}
		}
		
		
	}		