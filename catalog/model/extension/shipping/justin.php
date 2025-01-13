<?php
	class ModelExtensionShippingJustin extends Model {
		function getQuote($address) {
			$this->load->language('extension/shipping/justin');
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('intime_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get('intime_geo_zone_id')) {
				$status = true;
				} elseif ($query->num_rows) {
				$status = true;
				} else {
				$status = false;
			}
			
			$method_data = array();
			
			if ($status) {
				$quote_data = array();
				
				$text = $this->currency->format($this->tax->calculate($this->config->get('intime_cost'), $this->config->get('ukrposhta_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']);
				$cost = $this->config->get('intime_cost');
				
				$free_shipping = '';
				
				//OVERLOAD LOGIC
				$text = $this->language->get('text_by_tariff');
				if ($this->config->get('justin_min_total_for_free_delivery') && $this->config->get('justin_min_total_for_free_delivery') < $this->cart->getSubTotal() && $this->cart->hasProductsFromFreeDeliveryCategories()) {
					$cost = 0.00;
					$text = $this->language->get('text_free_tariff');		
					$free_shipping = $this->language->get('text_free_shipping');
				}
				
				$quote_data['justin'] = array(
				'code'         => 'justin.justin',
				'title'        => $this->language->get('text_description'),
				'cost'         => $cost,
				'free_shipping'=> $free_shipping,
				'tax_class_id' => $this->config->get('intime_tax_class_id'),
				'text'         => $text
				);
				
				$method_data = array(
				'code'       => 'justin',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('intime_sort_order'),
				'error'      => false
				);
			}
			
			return $method_data;
		}
	}	