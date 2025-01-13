<?php
class ModelExtensionModuleAPRI extends Model {
	
	public function getAPRIOrders(){
		$sql = "SELECT GROUP_CONCAT(order_id ORDER BY order_id DESC SEPARATOR ',') AS list_orders, customer_id, firstname, lastname, email, language_id FROM `" . DB_PREFIX . "order`
				WHERE order_status_id IN (" . implode(',', $this->config->get('apri_allowed_statuses')) . ")
				AND order_id NOT IN (SELECT order_id FROM " . DB_PREFIX . "apri) 
				AND MD5(email) NOT IN (SELECT md5_email FROM " . DB_PREFIX . "apri_unsubscribe) ";
		
		if ($this->config->get('apri_start_date')) {
			$sql .= "AND date_added >= '" . $this->config->get('apri_start_date') . "' "; 
		}
		
		if ($this->config->get('apri_days_after')) {
			$sql .= "AND DATEDIFF(NOW(),date_added) >= " . $this->config->get('apri_days_after') . " "; 
		}
		
		$sql .= "GROUP BY email";
		
		$query = $this->db->query($sql);

		return $query->rows;	
	}
	
	public function getAPRIOrderProducts($list_orders){
		$sql = "SELECT DISTINCT op.product_id, op.name, p.image FROM " . DB_PREFIX . "order_product op 
				LEFT JOIN " . DB_PREFIX . "product p ON op.product_id = p.product_id 
				WHERE op.order_id IN (" . $list_orders . ")";

		$query = $this->db->query($sql);

		return $query->rows;	
	}
	
	public function setAsNotified($orders_list) {
	
		$orders = explode(",", $orders_list);
		
		foreach ($orders as $order_id) {
		
			$sql = "INSERT INTO " . DB_PREFIX . "apri
					SET order_id   = '" . (int)$order_id . "',
						date_added = NOW()";
						
			$this->db->query($sql);
		}	
	}
	
	private function isInUnsubscribersList($md5_email) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "apri_unsubscribe WHERE md5_email = '" . $md5_email . "'" ;
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function unsubscribe($md5_email) {
		if (!$this->isInUnsubscribersList($md5_email)) {
			
			$sql = "INSERT INTO " . DB_PREFIX . "apri_unsubscribe
					SET md5_email      = '" . $this->db->escape($md5_email) . "',
						date_added = NOW()";
						
			$this->db->query($sql);			
		}
	}
}
?>