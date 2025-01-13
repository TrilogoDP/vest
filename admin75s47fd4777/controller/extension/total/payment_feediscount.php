<?php
class ControllerExtensionTotalPaymentFeeDiscount extends Controller
{
	private $module = false;
	private $type = "total";
	private $extension = "payment_feediscount";
	private $path = '';
	private $error = array();
	private $options = array(
		'customer_groups' => 'checkbox',
		'tax_class' => 'select',
		'geo_zone' => 'select',
		'status' => 'select',
		'sort_order' => 'input');
	
	public function index()
	{
		$data['extension'] = $this->extension;
		$data['type'] = $this->type;
		$data['token'] = $this->session->data['token'];
		$data['path'] = $this->path;
		$this->path = 'extension/';
				
		$this->language->load('extension/'.$this->type.'/'.$this->extension);	
		
		if ((strpos($this->request->get['route'], 'uninstall') !== false) || (strpos($this->request->get['route'], 'install') !== false)) return;
		
		if (file_exists(DIR_APPLICATION.'model/extension'.$this->type.'/'.$this->extension.'.php')) {
			$this->load->model('extension/'.$this->type.'/'.$this->extension);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$this->document->setTitle($data['heading_title']);
		
		$this->load->model('setting/setting');
		
		if ($this->module) {
			$this->load->model('extension/module');
			$module_id = isset($this->request->get['module_id']) ? $this->request->get['module_id'] : 0;
		}

		if (!empty($module_id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($module_id);
		}
								
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (method_exists($this, 'preSave')) {
				$this->preSave($this->request->post);
			}
						
			if ($this->module) {
				$this->request->post['name'] = $this->request->post[$this->extension.'_name'];
				$this->request->post['status'] = $this->request->post[$this->extension.'_status'];
					
				if (!empty($module_id)) {
					$this->model_extension_module->editModule($module_id, $this->request->post);
				} else {
					$this->model_extension_module->addModule($this->extension, $this->request->post);
					
					$query = $this->db->query("SELECT MAX(module_id) AS id FROM `".DB_PREFIX."module` WHERE code = '".$this->extension."'");
					$module_id = $query->row['id'];
				}
			} else {
				$this->model_setting_setting->editSetting($this->extension, $this->request->post);
			}
						
			if (empty($this->session->data['success'])) {
				$this->session->data['success'] = sprintf($this->language->get('message_success'), $data['heading_title']);
			}

			if (method_exists($this, 'postSave')) {
				$this->postSave($this->request->post);
			}			
			
			if ($this->request->post['apply']) {
				$this->response->redirect($this->url->link($this->path.$this->type.'/'.$this->extension, 'token='.$data['token'].(!empty($module_id) ? '&module_id='.$module_id : ''), true));
			} else {
				$this->response->redirect($this->url->link('extension/extension', 'token='.$data['token'].'&type='.$this->type, true));
			}
		}
		
		if (isset($this->session->data['success'])) $data['success'] = $this->session->data['success'];
		else $data['success'] = "";
		
		$this->session->data['success'] = "";
			
		$data['version'] = "";
		
		$data['text_edit'] = sprintf($this->language->get('text_edit_title'), $data['heading_title']);
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_remove_all'] = $this->language->get('text_remove_all');
		$data['text_no_results'] = $this->language->get('text_no_results');		
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_help'] = $this->language->get('button_help');		
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token='.$data['token'], true));
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type='.$this->type, true));
					
		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			'href' => $this->url->link('extension/'.$this->type.'/'.$this->extension, 'token='.$this->session->data['token'].(!empty($module_id) ? '&module_id='.$module_id : ''), true));
		
		$data['mainaction'] = $this->url->link('extension/'.$this->type.'/'.$this->extension, 'token='.$this->session->data['token'].(!empty($module_id) ? '&module_id='.$module_id : ''), true);
		$data['maincancel'] = $this->url->link('extension/extension', 'token='.$this->session->data['token'].'&type='.$this->type, true);
		
		$this->load->model('customer/customer_group');
		$groupmodel = 'model_customer_customer_group';
		
		$customer_groups = $this->{$groupmodel}->getCustomerGroups();
		
		foreach ($customer_groups as $customer_group) {
			$data['customer_groups'][] = array($customer_group['customer_group_id'], $customer_group['name']);
		}
		
		$this->load->model('localisation/tax_class');
		$taxes = $this->model_localisation_tax_class->getTaxClasses();
		
		$data['tax_class'][] = array(0, $this->language->get('text_none'));
		
		foreach ($taxes as $tax) {
			$data['tax_class'][] = array($tax['tax_class_id'], $tax['title']);
		}
		
		$this->load->model('localisation/geo_zone');
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
		
		$data['geo_zone'][] = array(0, $this->language->get('text_all_zones'));
		
		foreach ($geo_zones as $geo_zone) {
			$data['geo_zone'][] = array($geo_zone['geo_zone_id'], $geo_zone['name']);
		}
		
		$this->load->model('localisation/order_status');
        $statuses = $this->model_localisation_order_status->getOrderStatuses();
		
        $data['order_status'] = array();

        foreach ($statuses as $status) {
        	$data['order_status'][] = array($status['order_status_id'], $status['name']);
        }
		
		$data['status'] = array(
			array('0', $data['text_disabled']),
			array('1', $data['text_enabled']));
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		foreach ($data['languages'] as $key => $language) {
			$data['languages'][$key]['flag'] = 'language/'.$language['code'].'/'.$language['code'].'.png';
		}

		$this->load->model('localisation/stock_status');
		$statuses = $this->model_localisation_stock_status->getStockStatuses();
		
        foreach ($statuses as $status) {
        	$data['stock_status'][] = array($status['stock_status_id'], $status['name']);
        }

		$data['date_short'] = $this->language->get('date_format_short');
		$data['date_long'] = $this->language->get('date_format_long');
		$data['stylesheet'] = $this->extension;
				
		/* Extension specific code */
		
		$data['help'] = "";
		
		unset($this->options['geo_zone']);
		
		$data['settings'] = array(
			'general' => array_merge(array(
				'round' => 'input',
				'add_name' => 'radio',
				'add_value' => 'radio',
				'hide_total' => 'radio',
				'add_to' => 'select',
				'add_info' => 'radio',
				'inactive_fees' => 'checkbox',
				'inactive_discounts' => 'checkbox',			
				'version' => 'radio'), $this->options),
			'payments' => array(
				'fees' => 'html',
				'discounts' => 'html'));
		
		$this->load->model('extension/extension');
		
		$data['entry_payment'] = $this->language->get('entry_payment');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_maximum'] = $this->language->get('entry_maximum');
		$data['entry_value'] = $this->language->get('entry_value');
		
		$data['button_item_add'] = $this->language->get('button_item_add');
		$data['button_item_remove'] = $this->language->get('button_item_remove');
		
		$inactives = array('payment', 'shipping', 'total');
		
		$data['totals'] = array();
		$data['inactive_fees'] = array();
		$data['inactive_discounts'] = array();
		
		foreach ($inactives as $inactive) {
			$text = $this->language->get('text_'.$inactive);
			
			$items = $this->model_extension_extension->getInstalled($inactive);

			foreach ($items as $item) {
				$this->language->load($this->path.$inactive.'/'.$item);
				$data['inactive_fees'][] = $data['inactive_discounts'][] = array($inactive.':'.$item, $text.': '.$this->language->get('heading_title'));
				
				if ($inactive == 'total') $data['totals'][] = array($item, $this->language->get('heading_title'));
			}
		}
		
		$data['payments'] = array();

		$payments = $this->model_extension_extension->getInstalled('payment');
		
		foreach ($payments as $payment) {
			$this->language->load($this->path.'payment/'.$payment);
			$data['payments'][] = array($payment, $this->language->get('heading_title'));
		}
				
		$this->language->load('extension/'.$this->type.'/'.$this->extension);
						
		$valuetypes = array('fees', 'discounts');
		
		foreach ($valuetypes as $valuetype) {		
			if (isset($this->request->post[$this->extension.'_'.$valuetype])) {
				$data[$valuetype] = $this->request->post[$this->extension.'_'.$valuetype];
			} elseif ($this->config->get($this->extension.'_'.$valuetype)) {
				$data[$valuetype] = $this->config->get($this->extension.'_'.$valuetype);
			} else {
				$data[$valuetype] = array(0 => array('payment' => '', 'minimum' => '', 'maximum' => '', 'maximum_over' => '', 'value' => ''));
			}

			if ($data['payments']) {
				$html = "<div id='".$valuetype."' class='items'>";
			
				foreach ($data[$valuetype] as $itemkey => $itemdata) {
					$html .= "<div id='".$valuetype."-".$itemkey."' class='item-row ".$valuetype."'>";
					$html .= "<div class='item-inputs'>";
					$html .= "<select name='".$this->extension."_".$valuetype."[".$itemkey."][payment]' class='form-control'>";
					$html .= "<option value=''>".$data['entry_payment']."</option>";
			
					foreach ($data['payments'] as $payment) {
						$html .= "<option value='".$payment[0]."'".($itemdata['payment'] == $payment[0] ? " selected" : "").">".$payment[1]."</option>";
					}
			
					$html .= "</select>";
					$html .= '</div>';
				
					$html .= "<div class='item-inputs'>";
					$html .= "<input type='text' name='".$this->extension."_".$valuetype."[".$itemkey."][minimum]' class='form-control' value='".$itemdata['minimum']."' placeholder='".$data['entry_minimum']."' />";
					$html .= '</div>';
					
					$html .= "<div class='item-inputs'>";
					$html .= "<input type='text' name='".$this->extension."_".$valuetype."[".$itemkey."][maximum]' class='form-control' value='".$itemdata['maximum']."' placeholder='".$data['entry_maximum']."' />";
					$html .= '</div>';
					
					$html .= "<div class='item-inputs'>";
					$html .= "<input type='text' name='".$this->extension."_".$valuetype."[".$itemkey."][maximum_over]' class='form-control' value='".$itemdata['maximum_over']."' placeholder='".$data['entry_maximum']."' />";
					$html .= '</div>';
					
					$html .= "<div class='item-inputs'>";
					$html .= "<input type='text' name='".$this->extension."_".$valuetype."[".$itemkey."][value]' class='form-control' value='".$itemdata['value']."' placeholder='".$data['entry_value']."' />";
					$html .= '</div>';
              		
              		$html .= "<div class='item-buttons'>";
              	
              		if ($itemkey == (count($data[$valuetype]) - 1)) {
						$html .= "<a data-toggle='tooltip' title='".$this->language->get('button_item_add')."' class='btn btn-primary add-item'><i class='fa fa-plus'></i></a>";
   	    	   		} else {
   	    	    		$html .= "<a data-toggle='tooltip' title='".$this->language->get('button_item_remove')."' class='btn btn-danger remove-item'><i class='fa fa-minus'></i></a>";
   	    	   		}
   	    	   												
              		$html .= "</div>";
              		$html .= "</div>";
				}
			
				$html .= '</div>';
    		} else {
    			$html = $this->language->get('error_payments');
    		}
    		
    		$data[$this->extension.'_'.$valuetype] = $html;
    	}
	
		$data['add_to'] = $data['totals'];
				
		/* Generic code */
		
		if (!empty($module_id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($module_id);
		}
		
		foreach ($data['settings'] as $tab => $options) {
			if (empty($data['tab_'.$tab])) $data['tab_'.$tab] = $this->language->get('tab_'.$tab);
			
			$data['help_'.$tab] = $this->language->get('help_'.$tab);
			
			foreach ($options as $key => $type) {
				$data['entry_'.$key] = $this->language->get('entry_'.$key);
				$data['help_'.$key] = $this->language->get('help_'.$key);
			
				$from_post = (isset($this->request->post[$this->extension.'_'.$key]) ? $this->request->post[$this->extension.'_'.$key] : "");
				$from_config = (!empty($module_info) && isset($module_info[$this->extension.'_'.$key]) ? $module_info[$this->extension.'_'.$key] : $this->config->get($this->extension.'_'.$key));
				$default = ($type == 'checkbox' ? array() : "");
			
				if (!isset($data[$this->extension.'_'.$key])) {
					if (!empty($from_post)) $data[$this->extension.'_'.$key] = $from_post;
					elseif (isset($from_config)) $data[$this->extension.'_'.$key] = $from_config;
					else $data[$this->extension.'_'.$key] = $default;
				}
			}
		}
		
		if (method_exists($this, 'setDefaults')) {
			$this->setDefaults($data);
		}
					
		if (isset($this->session->data['errors'])) {
			foreach ($this->session->data['errors'] as $key => $text) {
				$this->error[$key] = $text;
			}
			
			unset($this->session->data['errors']);
		}
		
		if (!empty($this->error)) {
			$data['errors'] = $this->error;
		} else {
			$data['errors'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view("extension/".$this->type."/".$this->extension, $data));
	}
	
	private function preSave(&$post)
	{
		$post[$this->extension.'_fees'] = array_values($post[$this->extension.'_fees']);
		$post[$this->extension.'_discounts'] = array_values($post[$this->extension.'_discounts']);
		
		return $post;
	}
		
	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/'.$this->type.'/'.$this->extension)) {
			$this->error['warning'] = sprintf($this->language->get('error_permission'), $this->language->get('heading_title'));
		} else {
			/* Extension specific code */
		
			$valuetypes = array('fees', 'discounts');
		
			foreach ($valuetypes as $valuetype) {
				foreach ($this->request->post[$this->extension.'_'.$valuetype] as $itemkey => $itemdata) {
					if (strpos($itemdata['value'], "%")) $value = str_replace("%", "", $itemdata['value']);
					else $value = $itemdata['value'];
			
	        		if (!empty($value) && !is_numeric($value)) {
    	    			$this->language->load($this->path.'payment/'.$itemdata['payment']);
        	   			$this->error[] = sprintf($this->language->get('error_value'), $this->language->get('text_'.$valuetype), $this->language->get('heading_title'));
        	   		}
           		}
	        }

			$this->language->load('extension/'.$this->type.'/'.$this->extension);
		
			/* Generic code */
		
			$numerics = array('round', 'sort_order');
			$percent = array();
			$date = array();
			$nonempty = array();
			
			$fields = array_unique(array_merge($numerics, array_merge($percent, $nonempty)));
			$post = $this->request->post;
			
			if ($fields) {
				foreach ($fields as $field) {
					if (isset($post[$this->extension.'_'.$field])) {
						$value = $post[$this->extension.'_'.$field];
						
						if (in_array($field, $nonempty) && !$value) {
							$this->error[] = sprintf($this->language->get('error_empty'), $this->language->get('entry_'.$field));
						} elseif (in_array($field, $date) && (strtotime($value) === false)) {
							$this->error[] = sprintf($this->language->get('error_date'), $this->language->get('entry_'.$field));
						} elseif (!is_array($value)) {
							$value = trim($value, "%");
							
							if (!empty($value) && !is_numeric($value)) {
								if (in_array($field, $numerics)) {
									$this->error[] = sprintf($this->language->get('error_numerical'), $this->language->get('entry_'.$field));
								} elseif (in_array($field, $percent)) {
									$this->error[] = sprintf($this->language->get('error_percent'), $this->language->get('entry_'.$field));
								}
							} elseif ($value < 0) {
								$this->error[] = sprintf($this->language->get('error_positive'), $this->language->get('entry_'.$field));
							}
						}
					} elseif (in_array($field, $nonempty)) {
						$this->error[] = sprintf($this->language->get('error_empty'), $this->language->get('entry_'.$field));
					}
				}
			}
		}
		
		if (!$this->error) return true;
		else return false;
	}
}

?>