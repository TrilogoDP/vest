<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleOctPopupSubscribe extends Controller {
    public function index() {
        $data = array();
        
        $this->load->language('extension/module/oct_popup_subscribe');
        $this->load->model('extension/module/oct_popup_subscribe');
        $this->load->model('tool/image');
        
        $oct_popup_subscribe_form_data         = (array) $this->config->get('oct_popup_subscribe_form_data');
        $oct_popup_subscribe_text_data         = (array) $this->config->get('oct_popup_subscribe_text_data');
        $data['oct_popup_subscribe_form_data'] = $oct_popup_subscribe_form_data;
        $language_code                         = $this->session->data['language'];
        
        $data['heading_title']    = $this->language->get('heading_title');
        $data['button_subscribe'] = $this->language->get('button_subscribe');
        $data['button_close']     = $this->language->get('button_close');
        $data['enter_email']      = $this->language->get('enter_email');
        $data['text_stop']        = $this->language->get('text_stop');
        
        $data['expire'] = $oct_popup_subscribe_form_data['expire'] ? $oct_popup_subscribe_form_data['expire'] : '1';
        
        if ($oct_popup_subscribe_form_data['image']) {
            $data['thumb'] = $oct_popup_subscribe_form_data['image'];
        } else {
            $data['thumb'] = false;
        }
        
        if (isset($oct_popup_subscribe_text_data[$language_code])) {
            $data['promo_text'] = html_entity_decode($oct_popup_subscribe_text_data[$language_code]['promo_text'], ENT_QUOTES, 'UTF-8');
        }
        
        if ($this->customer->isLogged()) {
            $data['email'] = $this->customer->getEmail();
        } else {
            $data['email'] = '';
        }
        
        $oct_data = $this->config->get('oct_techstore_data');
        
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
        
        $this->response->setOutput($this->load->view('extension/module/oct_popup_subscribe', $data));
    }
    
    public function set_cookie() {
        if (isset($this->request->get['set']) && !empty($this->request->get['set'])) {
            setcookie('oct_popup_subscribe', 1, time() + (60 * 60 * 24 * $this->request->get['set']), "/");
        }
    }
    
    public function make_subscribe() {
        $json = array();
        
        $this->language->load('extension/module/oct_popup_subscribe');
        $this->load->model('extension/module/oct_popup_subscribe');
        
        $oct_popup_subscribe_form_data = (array) $this->config->get('oct_popup_subscribe_form_data');
        
        if (isset($this->request->post['email'])) {
            
            if (empty($this->request->post['email'])) {
                $json['error'] = $this->language->get('error_enter_email');
            }
            
            if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
                $json['error'] = $this->language->get('error_valid_email');
            }
            
            $subscribe_status = $this->model_extension_module_oct_popup_subscribe->checkSubscribe($this->request->post['email']);
            
            if ($subscribe_status) {
                $json['error'] = $this->language->get('error_already_subscribed_email');
            }
            
            $oct_data = $this->config->get('oct_techstore_data');
            
            if (isset($oct_data['terms']) && $oct_data['terms']) {
                if (!isset($this->request->post['terms'])) {
                    $this->load->model('catalog/information');
                    
                    $information_info = $this->model_catalog_information->getInformation($oct_data['terms']);
                    
                    $json['error'] = sprintf($this->language->get('error_oct_terms'), $information_info['title']);
                }
            }
            
            if (!isset($json['error'])) {
                
                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $ip = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $ip = $this->request->server['REMOTE_ADDR'];
                }
                
                $subscribe_data = array(
                    'email' => $this->request->post['email'],
                    'ip' => $ip
                );
                
                $this->model_extension_module_oct_popup_subscribe->addSubscribe($subscribe_data);
                
                $json['output'] = $this->language->get('text_success_subscribe');
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}