<?php
	class PaymentFeeDiscountHelper extends Model
	{
		public function getTotal($data)
		{		    		
			$this->calculateTotal($data);
		}    
	}
	
	class ModelExtensionTotalPaymentFeeDiscount extends PaymentFeeDiscountHelper
	{
		private $type = "total";
		private $extension = "payment_feediscount";
		private $path = '';	
		
		public function calculateTotal($data)
		{
			$total_data =& $data['totals'];
			$total =& $data['total'];
			$taxes =& $data['taxes'];
			
			if (!isset($this->session->data['payment_method'])) return;

			$this->language->load('extension/'.$this->type.'/'.$this->extension);
			
			$this->path = 'extension';
		 	
			$group = $this->customer->getGroupId();
			$customer_groups = $this->config->get($this->extension.'_customer_groups');
			
			if (!empty($customer_groups) && !in_array($group, $customer_groups)) return;
			
			$subtotal = $this->cart->getSubTotal();

			if (!$subtotal) return;
			
			if (!$total) $total = $subtotal;
			
			$valuetypes = array('fees', 'discounts');
			
			foreach ($valuetypes as $valuetype) {
				$items = $this->config->get($this->extension.'_'.$valuetype);
				
				foreach ($items as $item) {				
					if ($item['payment'] == $this->session->data['payment_method']['code']) {
					
						if ($item['value'] && (!$item['minimum'] || ($subtotal > $item['minimum'])) && (!$item['maximum'] || ($subtotal < $item['maximum']))) {
							$basevalue = $item['value'];														
							
							if ($item['maximum_over']){
								$maximum_over = $item['maximum_over'];
							}
							
							break 2;
						}
					}
				}
			}
					
			
			if (!empty($basevalue)) {
				if (strpos($basevalue, '%') !== false) {
					$value = $total * (trim($basevalue, '%'))/100;
					$roundby = $this->config->get($this->extension.'_round');
					if ($roundby) $value = ceil($value / $roundby) * $roundby;
					} else {
					$value = $basevalue;
				}
				
				$value = ($valuetype == 'discounts' ? -abs($value) : abs($value));
				
				// Check inactive
				
				if ($this->config->get($this->extension.'_inactive_'.$valuetype)) {
					$inactive_items = array();
					
					foreach ($this->config->get($this->extension.'_inactive_'.$valuetype) as $inactive) {
						$inactive_values = explode(':', $inactive);
						
						if (($inactive_values[0] == 'payment') && !empty($this->session->data['payment_method'])) {
							if ($inactive_values[1] == $this->session->data['payment_method']['code']) return;
							} elseif (($inactive_values[0] == 'shipping') && !empty($this->session->data['shipping_method'])) {
							$shipping_method = explode('.', $this->session->data['shipping_method']['code']);
							
							if ($inactive_values[1] == $shipping_method[0]) return;
							} elseif ($inactive_values[0] == 'total') {
							$inactive_items[] = $inactive_values[1];
						}
					}
					
					if (!empty($inactive_items)) {
						$this->load->model('extension/extension');
						$totals = $this->model_extension_extension->getExtensions('total');
						
						$inactive_data = array();
						$inactive_total = 0;
						$inactive_taxes = $this->cart->getTaxes();
						
						foreach ($totals as $item) {
							if (($item['code'] != $this->extension) && $this->config->get($item['code'].'_status')) {
								$this->load->model(($this->path ? $this->path.'/' : '').'total/'.$item['code']);
								
								$data = array('totals' => &$inactive_data, 'total' => &$inactive_total, 'taxes' => &$inactive_taxes);
								$this->{'model_'.($this->path ? $this->path.'_' : '').'total_'.$item['code']}->getTotal($data);
							}
						}
						
						foreach ($inactive_data as $item) {						
							if (in_array($item['code'], $inactive_items)) return;
						}
					}
				}
				
				// Create title
				
				$title = $this->language->get('text_payment_'.$valuetype);
				
				$add_name = $this->config->get($this->extension.'_add_name');
				$add_value = $this->config->get($this->extension.'_add_value');
				
				if ($add_name || $add_value) {
					$info = array();
					
					if ($add_name) $info[] = $this->session->data['payment_method']['title'];
					if ($add_value && strpos($basevalue, '%')) $info[] = $basevalue;
					
					if ($info) $title .= " (".implode(", ", $info).")";
				}
				
				// Recalculate taxes
				
				if ($this->config->get($this->extension.'_tax_class')) {
					$tax_rates = $this->tax->getRates($value, $this->config->get($this->extension.'_tax_class'));
					
					foreach ($tax_rates as $tax_rate) {
						if (!isset($taxes[$tax_rate['tax_rate_id']])) $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
						else $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
				
				if (!empty($maximum_over) && abs($value) > $maximum_over){
					if ($value > 0){
						$value = $maximum_over;
					} else {
						$value = -1 * $maximum_over;
					}
				}
				
				$total += $value;
				
				// Hide total
				
				if ($this->config->get($this->extension.'_hide_total')) {
					foreach ($total_data as $key => $item) {
						if ($item['code'] == $this->config->get($this->extension.'_add_to')) {
							$newvalue = $item['value'] + $value;
							$total_data[$key]['text'] = $this->currency->format($newvalue, $this->config->get('config_currency'));
							$total_data[$key]['value'] = $newvalue;
							
							if ($this->config->get($this->extension.'_add_info')) {
								$total_data[$key]['title'] .= $this->language->get('text_including_'.$valuetype);
							}
							
							return;
						}
					}
				}
				
				$total_data[] = array(
				'code' => $this->extension,
		     	'title' => $title,
    		    'text' => ($value < 0 ? "-" : "").$this->currency->format(abs($value), $this->config->get('config_currency')),
        		'value' => $value,
				'sort_order' => $this->config->get($this->extension.'_sort_order'));
			}
		}	
	}	