<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ModelExtensionModuleOctProductPreorder extends Model {
	public function addRequest($data) {
		$this->db->query("INSERT INTO ".DB_PREFIX."oct_product_preorder SET 
			info 				= '" . $this->db->escape($data['info']) . "', 
			note 				= '',
			product_id 			= '" . (int)$data['product_id'] . "',
			option_id 			= '" . (int)$data['option_id'] . "',
			option_value_id 	= '" . (int)$data['option_value_id'] . "',
			telephone 			= '" . $this->db->escape($data['telephone']) . "',
			language_id 		= '" . (int)$this->config->get('config_language_id') . "',
			customer_id 		= '" . (int)$this->customer->isLogged() . "',
			date_added 			= NOW()");
	}
}