<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();
	private $sms_log;
	private $token = 'jZK9saoXuc-i5UE';
	private $alpha = 'VEST.in.ua';

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->document->addScript('catalog/view/javascript/jquery/jquery.inputmask.bundle.min.js');

		$this->load->language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->language('mail/forgotten');

			if (!empty($this->request->post['email']) && $this->validateEmail()) {

				$code = token(40);
				$this->model_account_customer->editCode($this->request->post['email'], $code);

				$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

				$message  = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
				$message .= $this->language->get('text_change') . "\n\n";
				$message .= $this->url->link('account/reset', 'code=' . $code, true) . "\n\n";
				$message .= sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR']) . "\n\n";

				$data['text_footer'] = '';        
				$data['text_link'] = '';
				$data['reset_link'] = $this->url->link('account/reset', 'code=' . $code, true);
				$data['ip'] = sprintf($this->language->get('text_ip'), $this->request->server['REMOTE_ADDR']);

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($this->request->post['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($this->load->view('mail/reset_password', $data));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();

				$this->session->data['success'] = $this->language->get('text_success');

			} elseif (!empty($this->request->post['telephone']) && $this->validateTelephone()) {

				$code = mt_rand(1110, 9998);
				$this->model_account_customer->editPasswordByTelephone($this->request->post['telephone'], $code);

				$this->sendSMS(['to' => $this->request->post['telephone'], 'text' => sprintf($this->language->get('new_password'), $code) ]);	

				$this->session->data['success'] = $this->language->get('text_success_telephone');
			}
			

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

				if ($customer_info) {
					$this->load->model('account/activity');

					$activity_data = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
					);

					$this->model_account_activity->addActivity('forgotten', $activity_data);
				}
			}

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_email'] = $this->language->get('text_your_email');
		$data['placeholder_email'] = $this->language->get('placeholder_email');

		$data['text_email'] = $this->language->get('text_email');
		$data['text_telephone'] = $this->language->get('text_telephone');

		$data['entry_by_email'] = $this->language->get('entry_by_email');
		$data['entry_by_telephone'] = $this->language->get('entry_by_telephone');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('account/forgotten', '', true);		

		$data['back'] = $this->url->link('account/login', '', true);

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/forgotten', $data));
	}

	private function sendSMS($sms)
	{			

		$log = new Log('sms_smsclub.txt');

	//	$log->write('[SMS] Отправляю на номер ' . $sms ['to'] . ', текст ' . $sms ['text']);

		$token = 'your_bearer_token';
		$url = 'https://im.smsclub.mobi/sms/send';

		$data = json_encode([
			'phone' 	=> [normalizePhone($sms['to'])],
			'message' 	=> $sms['text'],
			'src_addr' 	=> $this->alpha
		]);

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Bearer ' . $this->token,
				'Content-Type: application/json'
			]
		]);

		$result = curl_exec($ch);		
		curl_close($ch);

		$log->write('Ответ сервера: '. $result);			
	}

	protected function validateTelephone() {
		if (!isset($this->request->post['telephone'])) {
			$this->error['warning'] = $this->language->get('error_telephone');
		} elseif (!$this->model_account_customer->getTotalCustomersByTelephone($this->request->post['telephone'])) {
			$this->error['warning'] = $this->language->get('error_telephone');
		}

		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['telephone']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		return !$this->error;
	}

	protected function validateEmail() {
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		return !$this->error;
	}
}
