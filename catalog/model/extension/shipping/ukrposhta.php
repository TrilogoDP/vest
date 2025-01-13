<?php
class ModelExtensionShippingUkrposhta extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/ukrposhta');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ukrposhta_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('ukrposhta_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();
			
			$text = $this->currency->format($this->tax->calculate($this->config->get('ukrposhta_cost'), $this->config->get('ukrposhta_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']);
			$cost = $this->config->get('ukrposhta_cost');
			$free_shipping = false;
			
			 //OVERLOAD LOGIC
            $text = $this->language->get('text_by_tariff');
            if ($this->config->get('ukrposhta_min_total_for_free_delivery') && $this->config->get('ukrposhta_min_total_for_free_delivery') < $this->cart->getSubTotal() && $this->cart->hasProductsFromFreeDeliveryCategories()) {
                $cost = 0.00;
                $text = $this->language->get('text_free_tariff');
				$free_shipping = $this->language->get('text_free_shipping');
            }

			$quote_data['ukrposhta'] = array(
				'code'         => 'ukrposhta.ukrposhta',
				'title'        => $this->language->get('text_description'),
				'free_shipping'=> $free_shipping,
				'cost'         => $cost,
				'tax_class_id' => $this->config->get('ukrposhta_tax_class_id'),
				'text'         => $text
			);

			$method_data = array(
				'code'       => 'ukrposhta',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('ukrposhta_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}