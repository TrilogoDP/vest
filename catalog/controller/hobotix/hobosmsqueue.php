<?php
	class ControllerHobotixHoboSMSQueue extends Controller {
		private $sms_log;
		private $login = '0961692783';
		private $password = 'DthtcHjvfy2404';
		
		
		private function normalizePhone($phone){
			
			if ($phone[0] == '+'){
				$phone = substr($phone, 1);			
			}
			
			$phone =preg_replace("/\D+/", "", $phone);
			
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
		
		
		private function balance()
		{
			
		}
		
		private function send($sms)
		{		
			$debug_mode = true;
			
			echo '[SMS] Отправляю на номер ' . $params ['phone'] . ', текст ' . $params ['text'] . ', от имени '. $params ['sender'] . PHP_EOL;
			$params ['text'] = $sms['message'];
			$params ['phone'] = $this->normalizePhone($sms['to']);
			
			
			$this->sms_log->write('TRY SEND: ' . $params ['phone'] . ' : ' .  $params ['text'] . ' : '. $params ['sender']);
			
			$xml = "<?xml version='1.0' encoding='utf-8'?>
			<request_sendsms>
            <username><![CDATA[" . $this->username . "]]></username>
            <password><![CDATA[" . $this->password . "]]></password>
            <from><![CDATA[XML]]></from>
            <to><![CDATA[" . $sms['to'] . "]]></to>
            <text><![CDATA[" . $sms['message'] . "]]></text>
			</request_sendsms>";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CRLF, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_URL, 'https://gate.smsclub.mobi/xml/');
			$result = curl_exec($ch);
						
			$this->sms_log->write('Ответ сервера: '. $result);			
		}
		
		public function queue(){
			$this->sms_log = new Log('sms_smsclub.txt');			
			
			$query = $this->db->ncquery("SELECT * FROM " . DB_PREFIX . "sms_queue ORDER BY RAND() LIMIT 4");
			
			foreach ($query->rows as $encoded_sms){
				$sms_query_id = $encoded_sms['sms_id'];
				$encoded_body = json_decode(base64_decode($encoded_sms['sms_body']), true);
				
				$encoded_body['to'] = $this->normalizePhone($encoded_body['to']);
				
				$campaign_id = $this->send($encoded_body);
				
				if ($campaign_id){
					$this->db->ncquery("DELETE FROM " . DB_PREFIX . "sms_queue WHERE sms_id = '" . (int)$sms_query_id . "'");												
					
					$this->sms_log->write('[SMS] ' . (int)$sms_query_id . ' send ok, campaign returned : '.$campaign_id);						
					echo '[SMS] ' . (int)$sms_query_id . ' отправлено ок, номер кампании : '.$campaign_id . PHP_EOL;
					
					} else {
					
					$this->sms_log->write('[SMS] ' . (int)$sms_query_id . ' не отправлено');
					echo '[SMS] ' . (int)$sms_query_id . ' не отправлено' . PHP_EOL;	
				}
				
			}
		}
		
		
	}												