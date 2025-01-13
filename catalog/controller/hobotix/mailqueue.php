<?php
	
	
	class ControllerHobotixMailQueue extends Controller {
		private $limit = 10;
		private $langprefix = '';
		
		public function index() {
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			
			$query = $this->db->ncquery("SELECT * FROM " . DB_PREFIX . "email_queue WHERE 1 ORDER BY RAND() LIMIT 10");
			
			if ($query->num_rows){
				foreach ($query->rows as $row){
					$json = json_decode(base64_decode($row['data']), true);
					
					echo 'Отправляем почту ' . $json['from'] . ' -> ' . $json['to'] . PHP_EOL;

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					
					$mail->setTo($json['to']);
					$mail->setFrom($json['from']);
					$mail->setSender($json['sender']);
					$mail->setSubject($json['subject']);
					$mail->setHtml($json['html']);
					$mail->setText($json['text']);
					$mail->send(false);
					
					$query = $this->db->query("DELETE FROM " . DB_PREFIX . "email_queue WHERE queue_id = '" . (int)$row['queue_id'] . "'");
					
				}				
			}
			
		}
		
	}	