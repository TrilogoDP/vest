<?php
class ModelAccountSearch extends Model {
	public function addSearch($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_search` SET `store_id` = '" . (int)$this->config->get('config_store_id') . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `customer_id` = '" . (int)$data['customer_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "', `category_id` = '" . (int)$data['category_id'] . "', `sub_category` = '" . (int)$data['sub_category'] . "', `description` = '" . (int)$data['description'] . "', `products` = '" . (int)$data['products'] . "', `ip` = '" . $this->db->escape($data['ip']) . "', `date_added` = NOW(), `timestamp` = UNIX_TIMESTAMP()");
	}
	
	
	public function limitSearch($data){
		$query = $this->db->query(
			"SELECT COUNT(*) as count FROM `" . DB_PREFIX . "customer_search` 
				WHERE ip = '" . $this->db->escape($data['ip']) . "' 
				AND timestamp >= '" . strtotime('-3 minute') . "'"
		);
		
		return $query->row['count'];
		
	}
}
