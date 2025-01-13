<?php
	class ControllerHobotixFeedUpdater extends Controller {
		private $status;
		private $phrases = array(
		'Эй, мы тут фиды обновили, вот список.',
		'Cэр, задача выполнена, фиды обновлены! Список в письме.',
		'Господа, фиды обновлены! Список тут',
		'Фіди оновлено, джентльмени. Подивіться, будь ласка.',
		'Головнокомандуючий, завдання виконано! Фіди оновлено!',
		'Людоньки, що ж це коїться? Фідів наоновлювали тут мені..',
		);
		private $statusFile = DIR_CACHE . '/feedupdater.status';
		
		private function echoLine($line){
			$line = str_replace('<![CDATA[', '', $line);
			$line = str_replace(']]>', '', $line);
			echo $line . PHP_EOL;			
		}
		
		private function echoSimple($line){
			echo $line;			
		}
		
		private function memoryUnits($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		}
		
		private function getStatus(){
			
			if (file_exists($this->statusFile)){
				$this->echoLine('[i] Время файла ' . date("F d Y H:i:s.", filectime($this->statusFile)));
				$this->echoLine('[i] Сейчас ' . date("F d Y H:i:s."));
				if (filectime($this->statusFile) <= strtotime("-1 hour")){	
				
					$this->echoLine('[i] Сбросим файл ' . date("F d Y H:i:s."));
					$this->setStatus('idle');
				}
			}
				
			if (!file_exists($this->statusFile)){
				$this->setStatus('idle');
			}
			
			$this->status = file_get_contents($this->statusFile);
			
		}
		
		private function setStatus($status){
			file_put_contents($this->statusFile, $status);			
		}		
		
		private function listFiles(){
			clearstatcache();
			$files = array();
			if ($handle = opendir(DIR_FEEDS)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$files[$file] = filemtime(DIR_FEEDS . $file);
					}
				}
				closedir($handle);
			}
			
			ksort($files);
			
			return $files;
		}
		
		private function sendMail(){
			
			$files = $this->listFiles();
			
			$text = '';
			foreach ($files as $file => $time){
				$text .= 'Фид ' . $file . ', время обновления ' . date('Y-m-d H:i:s', $time) . PHP_EOL;
			}
			
			$subject = $this->phrases[rand(0, count($this->phrases) - 1)];
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			$mail->setTo('info@vest.in.ua');
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode('VEST Feeds Updater', ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($text);
			$mail->send();
			
			
		}
		
		
		public function cron(){
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			$this->getStatus();
			
			if ($this->status == 'idle'){
				die('idle');
			}
			
			if ($this->status != 'command'){
				die('working');
			}
			
			if ($this->status == 'command'){
				$this->echoLine('command, making feeds');
				
				//GOOGLE MERCHANT
				$this->setStatus('google_merchant');
				$this->load->controller('feed/google_merchant_center_builder/cron');
				$this->setStatus('idle');
				
				$this->setStatus('google_merchant_extended');
				$this->load->controller('feed/google_merchant_extended_builder/cron');
				$this->setStatus('idle');
			
				$this->setStatus('facebook');
				$this->load->controller('feed/google_merchant_center_builder/facebook');
				$this->setStatus('idle');

				$this->setStatus('promua');
				$this->load->controller('feed/prom_yml_builder/cron');
				$this->setStatus('idle');
				
				$this->setStatus('rozetka_hotline');
				$this->load->controller('extension/feed/custom_yml_mf/cron');
				$this->setStatus('idle');

				$this->setStatus('hotline');
				$this->load->controller('extension/feed/custom_yml_mf/cron_hotline');
				$this->setStatus('idle');
				
				$this->sendMail();			
				
			}
			
		}
	}				