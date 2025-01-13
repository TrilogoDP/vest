<?php
	class ModelReportProduct extends Model {
		public function getProductsViewed($data = array()) {
			$sql = "SELECT pd.name, p.model, p.viewed FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.viewed > 0 ORDER BY p.viewed DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}

		public function getProductsWeekViewed($data = array()) {
			$sql = "SELECT pd.name, p.model, sv.times FROM superstat_viewed sv LEFT JOIN " . DB_PREFIX . "product p ON (sv.entity_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND sv.entity_type = 'p' AND sv.week = WEEK(NOW()) AND sv.year = YEAR(NOW()) ORDER BY sv.times DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}


		public function putArchivedProductsToDeletedSKU(){
			$query = $this->db->query("SELECT p.product_id, p.sku, pd.name FROM oc_product p LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) WHERE p.archive = 1 AND pd.language_id = '" . $this->config->get('config_language_id') . "' AND sku <> ''");

			$this->load->model('catalog/product');

			$t = 0;
			foreach ($query->rows as $row){
				if ($this->model_catalog_product->validateIfProductIsAddedFromSupplier($row['product_id'])){
					$this->insertDeletedSKU($row);
					$t++;
				}
			}

			return $t;

		}

		public function getProductBySKU($sku){

			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.sku = '" . $this->db->escape($sku) . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");
			
			return $query->row;

		}
		
		public function getProductsDeletedSKU($data = array()) {
			$sql = "SELECT sku, name FROM " . DB_PREFIX . "sku_deleted WHERE 1 ";
			
			if (isset($data['filter_sku'])){
				$sql .= " AND sku LIKE ('%" . $this->db->escape($data['filter_sku']) . "%')";
			}
			
			if (isset($data['filter_name'])){
				$sql .= " AND LOWER(name) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%')";
			}
			
			$sql .= " ORDER BY name DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function deleteDeletedSKU($sku) {
			
			if (trim($sku)){
				$this->db->query("DELETE FROM " . DB_PREFIX . "sku_deleted WHERE sku = '" . $this->db->escape($sku) . "'");
			}
			
		}
		
		public function insertDeletedSKU($data) {
			
			if (trim($data['sku'])){
				$this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "sku_deleted SET sku = '" . $this->db->escape($data['sku']) . "', `name` = '" . $this->db->escape($data['name']) . "'");
			}
			
		}
		
		public function getTotalProductsDeletedSKU($data) {
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sku_deleted WHERE 1 ";
			
			if (isset($data['filter_sku'])){
				$sql .= " AND sku LIKE ('%" . $this->db->escape($data['filter_sku']) . "%')";
			}
			
			if (isset($data['filter_name'])){
				$sql .= " AND LOWER(name) LIKE ('%" . $this->db->escape(mb_strtolower($data['filter_name'])) . "%')";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
		
		public function getTotalProductViews() {
			$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");
			
			return $query->row['total'];
		}

		public function getTotalProductWeekViews() {
		$query = $this->db->query("SELECT SUM(times) AS total FROM superstat_viewed WHERE entity_type = 'p' AND week = WEEK(NOW()) AND year = YEAR(NOW())");
		
		return $query->row['total'];
		}

		public function getTotalProductsWeekViewed() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM superstat_viewed WHERE entity_type = 'p' AND week = WEEK(NOW()) AND year = YEAR(NOW())");
		
		return $query->row['total'];
		}
		
		public function getTotalProductsViewed() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE viewed > 0");
		
		return $query->row['total'];
		}
		
		public function reset() {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = '0'");
		}
		
		public function getPurchased($data = array()) {
			$sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.price + (op.tax * op.quantity)) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
			
			if (!empty($data['filter_order_status_id'])) {
				$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
				} else {
				$sql .= " WHERE o.order_status_id > '0'";
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$sql .= " GROUP BY op.product_id ORDER BY total DESC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		}
		
		public function getTotalPurchased($data) {
			$sql = "SELECT COUNT(DISTINCT op.product_id) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
			
			if (!empty($data['filter_order_status_id'])) {
				$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
				} else {
				$sql .= " WHERE o.order_status_id > '0'";
			}
			
			if (!empty($data['filter_date_start'])) {
				$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}
			
			if (!empty($data['filter_date_end'])) {
				$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];
		}
	}
