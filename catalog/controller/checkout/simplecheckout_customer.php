<?php
    /*
        @author	Dmitriy Kubarev
        @link	http://www.simpleopencart.com
        @link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
    */
    
    include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');
    
    class ControllerCheckoutSimpleCheckoutCustomer extends SimpleController {
        private $_templateData = array();
        
        private function init() {
            $this->loadLibrary('simple/simplecheckout');
            
            $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
            
            $this->language->load('checkout/simplecheckout');
            
            $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');
            
            if ($get_route == 'checkout/simplecheckout_customer') {
                $this->simplecheckout->init('customer');
            }
        }
        
        public function index() {
            $this->init();
            
            if ($this->simplecheckout->isBlockHidden('customer')) {
                return;
            }
            
            $this->_templateData['text_checkout_customer']       = $this->language->get('text_checkout_customer');
            $this->_templateData['text_checkout_customer_login'] = $this->language->get('text_checkout_customer_login');
            $this->_templateData['text_you_will_be_registered']  = $this->language->get('text_you_will_be_registered');
            $this->_templateData['text_account_created']         = $this->language->get('text_account_created');
            $this->_templateData['text_fill_with']        		 = $this->language->get('text_fill_with');
            $this->_templateData['entry_address_same']           = $this->language->get('entry_address_same');
            
            $this->_templateData['is_logged']					= $this->customer->isLogged();
            
            $this->_templateData['display_login']               = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayLogin', 'customer');
            $this->_templateData['display_registered']          = !empty($this->session->data['simple']['registered']) ? true : false;
            
            $this->_templateData['rows'] = $this->simplecheckout->getRows('customer');
            
            $this->_templateData['display_agreement_checkbox']       = $this->simplecheckout->getSettingValue('displayAgreementCheckbox');
			$this->_templateData['agreement_checkbox_step']          = $this->simplecheckout->getSettingValue('agreementCheckboxStep');
            $this->_templateData['display_error']                    = $this->simplecheckout->displayError();
			$this->_templateData['has_error']                        = $this->simplecheckout->hasError('agreement');
            
			if ($this->_templateData['display_agreement_checkbox']) {
				$disable_popup = $this->_templateData['popup'] ? true : $this->simplecheckout->getSettingValue('agreementDisablePopup');
				
				if (!$disable_popup) {
					$seo_url = $this->config->get('config_seo_url');
					$this->config->set('config_seo_url', false);
                }
				
				$agreement_id = $this->simplecheckout->getSettingValue('agreementId');
				$lang_id = ($this->config->get('config_template') == 'shoppica' || $this->config->get('config_template') == 'shoppica2') ? 'text_agree_shoppica' : 'text_agree';
				$agreement_text = $this->language->get($lang_id);  
				
				if ($disable_popup) {
					$agreement_text = str_replace('href=', 'target="_blank" href=', $agreement_text);
					$agreement_text = preg_replace('/colorbox|fancybox|agree/', '', $agreement_text);
                }
				
				$this->_templateData['text_agreements'] = array();
				
				if ($agreement_id) {
					$title = $this->simplecheckout->getInformationTitle($agreement_id);
					
					if ($this->simplecheckout->getSettingValue('agreementType') == 2) {
						$this->_templateData['text_agreements'][$agreement_id] = sprintf($agreement_text, $this->url->link($this->simplecheckout->getInformationRoute($disable_popup), $this->simplecheckout->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
						} else {
						$this->_templateData['text_agreements']['all'] = sprintf($agreement_text, $this->url->link($this->simplecheckout->getInformationRoute($disable_popup), $this->simplecheckout->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
                    }
					
					$errors = array();
					
					$errors[$agreement_id] = sprintf($this->language->get('error_agree'), $title);
					
					$this->_templateData['error_warning_agreement'] = $errors;
					} else {
					$agreements = $this->simplecheckout->getSettingValue('agreementIds');   
					if (!empty($agreements) && is_array($agreements)) {
						if ($this->simplecheckout->getSettingValue('agreementType') == 2) {
							$errors = array();
							
							foreach ($agreements as $agreement_id) {
								$title = $this->simplecheckout->getInformationTitle($agreement_id);
								
								$this->_templateData['text_agreements'][$agreement_id] = sprintf($agreement_text, $this->url->link($this->simplecheckout->getInformationRoute($disable_popup), $this->simplecheckout->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
								
								$errors[$agreement_id] = sprintf($this->language->get('error_agree'), $title);
                            }
							
							$this->_templateData['error_warning_agreement'] = $errors;
							} else {
							$agreement_link = '';
							
							if (@preg_match('/<a.+a>/', $agreement_text, $matches)) {
								$agreement_link = $matches[0];
								$agreement_text = @preg_replace('/<a.+a>/', '%s', $agreement_text);
                            }
							
							$links = array();
							$errors = array();
							
							foreach ($agreements as $agreement_id) {
								$title = $this->simplecheckout->getInformationTitle($agreement_id);
								
								$links[] = sprintf($agreement_link, $this->url->link($this->simplecheckout->getInformationRoute($disable_popup), $this->simplecheckout->getAdditionalParams() . 'information_id=' . $agreement_id, 'SSL'), $title, $title);
								
								$errors[$agreement_id] = sprintf($this->language->get('error_agree'), $title);
                            }
							
							$this->_templateData['text_agreements']['all'] = sprintf($agreement_text, implode(', ', $links));
							
							$this->_templateData['error_warning_agreement'] = $errors;
                        }
                    }
                }
				
				if (!$disable_popup) {
					$this->config->set('config_seo_url', $seo_url);
                }
            }
            
            if (!$this->simplecheckout->validateFields('customer')) {
                $this->simplecheckout->addError('customer');
            }
            
            unset($this->session->data['simple']['registered']);
            
            $this->_templateData['display_header']              = $this->simplecheckout->getSettingValue('displayHeader', 'customer');
            $this->_templateData['display_you_will_registered'] = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayYouWillRegistered', 'customer') && $this->session->data['simple']['customer']['register'] && !$this->simplecheckout->isFieldUsed('register', 'customer');
            $this->_templateData['display_error']               = $this->simplecheckout->displayError('customer');
            $this->_templateData['has_error']                   = $this->simplecheckout->hasError('customer');
            $this->_templateData['hide']                        = $this->simplecheckout->isBlockHidden('customer');
            
            $this->setOutputContent($this->renderPage('checkout/simplecheckout_customer', $this->_templateData));
        }
        
        public function update_session() {
            $this->init();
            
            if (empty($this->session->data['simple']['customer'])) {
                return;
            }
            
            $customer = $this->session->data['simple']['customer'];
            
            if (!$this->customer->isLogged()) {
                foreach ($customer as $key => $value) {
                    if ($key == 'register') {
                        continue;
                    }
                    
                    $this->session->data['guest'][$key] = $value;
                }
            }
            
            if (empty($this->session->data['guest']['customer_group_id'])) {
                $this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
            }
        }
    }
?>