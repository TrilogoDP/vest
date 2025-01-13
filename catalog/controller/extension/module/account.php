<?php
class ControllerExtensionModuleAccount extends Controller {
	public function index() {
		$this->load->language('extension/module/account');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_register'] 		= $this->language->get('text_register');
		$data['text_login'] 		= $this->language->get('text_login');
		$data['text_logout'] 		= $this->language->get('text_logout');
		$data['text_forgotten'] 	= $this->language->get('text_forgotten');
		$data['text_account'] 		= $this->language->get('text_account');
		$data['text_edit'] 			= $this->language->get('text_edit');
		$data['text_password'] 		= $this->language->get('text_password');
		$data['text_address'] 		= $this->language->get('text_address');
		$data['text_wishlist'] 		= $this->language->get('text_wishlist');
		$data['text_order'] 		= $this->language->get('text_order');
		$data['text_download'] 		= $this->language->get('text_download');
		$data['text_reward'] 		= $this->language->get('text_reward');
		$data['text_return'] 		= $this->language->get('text_return');
		$data['text_transaction'] 	= $this->language->get('text_transaction');
		$data['text_newsletter'] 	= $this->language->get('text_newsletter');
		$data['text_recurring'] 	= $this->language->get('text_recurring');

		$route = '';
		if (!empty($this->request->get['route'])){
			$route = $this->request->get['route'];
		}

		$data['logged'] 		= $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', true); 
		$data['register_active'] = ($route == 'account/register');

		$data['login'] = $this->url->link('account/login', '', true);
		$data['login_active'] = ($route == 'account/login');

		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['logout_active'] = ($route == 'account/logout');

		$data['forgotten'] = $this->url->link('account/forgotten', '', true);
		$data['forgotten_active'] = ($route == 'account/forgotten');  

		$data['account'] = $this->url->link('account/account', '', true);
		$data['account_active'] = ($route == 'account/account');

		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['edit_active'] = ($route == 'account/edit');

		$data['password'] = $this->url->link('account/password', '', true);
		$data['password_active'] = ($route == 'account/password');

		$data['address'] = $this->url->link('account/address', '', true); 
		$data['address_active'] = ($route == 'account/address');

		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['wishlist_active'] = ($route == 'account/wishlist');

		$data['order'] = $this->url->link('account/order', '', true);
		$data['order_active'] = ($route == 'account/order');

		$data['download'] = $this->url->link('account/download', '', true);
		$data['download_active'] = ($route == 'account/download');

		$data['reward'] = $this->url->link('account/reward', '', true);
		$data['reward_active'] = ($route == 'account/reward');

		$data['return'] = $this->url->link('account/return', '', true);
		$data['return_active'] = ($route == 'account/return');

		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['transaction_active'] = ($route == 'account/transaction'); 

		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['newsletter_active'] = ($route == 'account/newsletter');

		$data['recurring'] = $this->url->link('account/recurring', '', true);
		$data['recurring_active'] = ($route == 'account/recurring');


		return $this->load->view('extension/module/account', $data);
	}
}