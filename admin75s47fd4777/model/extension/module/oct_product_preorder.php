<?php
	/**************************************************************/
	/*	@copyright	OCTemplates 2018.							  */
	/*	@support	https://octemplates.net/					  */
	/*	@license	LICENSE.txt									  */
	/**************************************************************/
	
	class ModelExtensionModuleOctProductPreorder extends Model {
		public function getCall($request_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "oct_product_preorder` WHERE request_id = '" . (int) $request_id . "'");
			
			return $query->row;
		}
		
		public function deleteCall($request_id) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "oct_product_preorder` WHERE request_id = '" . (int) $request_id . "'");
		}
		
		public function deleteAllCallArray() {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "oct_product_preorder`");
		}
		
		public function addRequest($data) {
			$this->db->query("INSERT INTO ".DB_PREFIX."oct_product_preorder SET 
			info = '" . $this->db->escape($data['info']) . "', 
			note = '',
			product_id = '" . (int)$data['product_id'] . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			language_id = '" . (int)$this->config->get('config_language_id') . "',		
			date_added = NOW()");
		}
		
		public function getCallArrayNoLimit() {
			$sql = "SELECT *,  COUNT(*) as count, p.sku, pd.name  FROM `" . DB_PREFIX . "oct_product_preorder` opp
			LEFT JOIN `" . DB_PREFIX . "product` p ON (opp.product_id = p.product_id)
			LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = p.product_id)
			WHERE opp.product_id > 0
			AND p.quantity = 0
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND p.status = 1
			AND opp.sms_date_added = '0000-00-00 00:00:00'
			GROUP BY opp.product_id
			ORDER BY count DESC
			";
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getCallArray($data = array()) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "oct_product_preorder` ";
			
			$sql .= " GROUP BY request_id";
			
			$sort_data = array(
            'date_added'
			);
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
				} else {
				$sql .= " ORDER BY date_added";
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
				} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getTotalCallArray() {
			$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "oct_product_preorder`";
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function updateNote($request_id, $note) {
			$this->db->query("UPDATE `" . DB_PREFIX . "oct_product_preorder` SET note = '" . $this->db->escape($note) . "' WHERE request_id = '" . (int) $request_id . "'");
		}
		
		public function makeDB() {
			$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oct_product_preorder` ( ";
			$sql .= "`request_id` int(11) NOT NULL AUTO_INCREMENT, ";
			$sql .= "`info` text COLLATE utf8_general_ci NOT NULL, ";
			$sql .= "`note` text COLLATE utf8_general_ci NOT NULL, ";
			$sql .= "`date_added` datetime NOT NULL, ";
			$sql .= "PRIMARY KEY (`request_id`) ";
			$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;";
			
			$this->db->query($sql);
		}
		
		public function deleteDB() {
			$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oct_product_preorder`;");
		}
	}	