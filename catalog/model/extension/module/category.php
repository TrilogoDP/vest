<?php
	
	
	class ModelExtensionModuleCategory extends Model {
		
		
		public function topViewedCategories($data = array()){

			$sql = "SELECT DISTINCT(category_id) FROM superstat_viewed sv LEFT JOIN oc_category c ON (sv.entity_id = c.category_id) WHERE 
				c.status = 1 AND 
				sv.entity_type = 'c' AND 
				week = WEEK(NOW()) AND 
				year = YEAR(NOW())";				

			if (!empty($data['filter_not_main'])) {
				$sql .= " AND c.category_id NOT IN (SELECT category_id FROM oc_category WHERE parent_id = 0) ";
			}

			$sql .= " ORDER BY times DESC";

			if (!empty($data['limit'])){
				$sql .= " LIMIT 0, " . (int)$data['limit'];
			}

			$query = $this->db->query($sql);
			
			$result = [];
			foreach ($query->rows as $row) {				
				$result[] = $row['category_id'];
			}			

			return $result;
		}

		
		public function getCategories($data = array()) {
			$sql = "SELECT DISTINCT(p2c.category_id), COUNT(DISTINCT p.product_id) as count ";
			
			$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			
			$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_option_value pov ON (p.product_id = pov.product_id) ";
			}
			
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) ";
			
			if (!empty($data['filter_top_viewed'])) {
				$sql .= " INNER JOIN (SELECT product_id FROM " . DB_PREFIX . "product WHERE status = 1 AND quantity > 0 AND product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category p2c2 WHERE main_category = 1 AND p2c2.category_id IN (SELECT category_id FROM " . DB_PREFIX . "category ca2 WHERE ca2.efs = 0)) ORDER BY viewed DESC LIMIT 0,500) as p2 ON (p2.product_id = p2c.product_id AND p2c.main_category = 1)";
			}
			
			$sql .= " WHERE p.status = '1' AND archive = 0 AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";		
						
			
			if (!empty($data['filter_special']) || !empty($data['filter_new']) || (!empty($data['filter_bestseller']) && empty($data['filter_bestseller_explicit']))){
				$sql .= " AND p2c.main_category = 1 AND p2c.category_id NOT IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE efs = 1)";		
			}
			
			if (!empty($data['filter_special']) || !empty($data['filter_new']) || !empty($data['filter_bestseller'])){
				$sql .= " AND p2c.main_category = 1";		
			}
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
				
				if (!empty($data['filter_name'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
					
					if (!empty($data['filter_description'])) {
						$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$implode = array();
					
					$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));
					
					foreach ($words as $word) {
						$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" AND ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_name'])) {
					$sql .= " OR LCASE(p.sku) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
					$sql .= " OR LCASE(p.model) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
//					$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
//					$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(pov.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(pov.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
					$sql .= " OR LCASE(pov.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				}
				
				$sql .= ")";
			}
			
			if (!empty($data['filter_bestseller'])) {
				$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE o.order_status_id > 0 AND o.date_added >= '" . date('Y-m-d', strtotime('-180 day')) . "')";
			}						
			
			if (!empty($data['filter_special'])) {
				$sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_special ps WHERE ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end > NOW())))";
			}
			
			if (!empty($data['filter_manufacturer_id'])) {
				$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
				
				$checkManufacturerQuery = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "' AND hide_manufacturer = 1");
				
				if ($checkManufacturerQuery->num_rows){
					$sql .= " AND p2c.category_id NOT IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE hide_manufacturer = 1)";
				}
				
			}
			
			if (!empty($data['filter_quantity'])) {
				$sql .= " AND p.quantity > 0";
			}
			
			if (!empty($data['filter_new'])) {
				$sql .= " AND DATE(p.date_added) >= '" . date('Y-m-d', strtotime('-30 day')) . "'";
			}
			
			if (!empty($data['filter_not_main'])) {
				$sql .= " AND p2c.category_id NOT IN (SELECT category_id FROM " . DB_PREFIX . "category WHERE parent_id = 0)";
			}

			$sql .= " GROUP BY p2c.category_id";
			
			if (!empty($data['filter_product_limit'])) {
				$sql .= " HAVING COUNT(DISTINCT p.product_id) >= " . (int)$data['filter_product_limit'];
			}
			
			if (!empty($data['limit'])){
				$sql .= " LIMIT 0, " . (int)$data['limit'];
			}
			
			$category_data = array();
			$this->load->model('catalog/category');
			
		//	$this->log->printsql($sql);
			
			$query = $this->db->query($sql);							
			
			foreach ($query->rows as $result) {
				$category = $this->model_catalog_category->getCategory($result['category_id']);
				if ($category) {
					$category['count'] = $result['count'];
					$category_data[$result['category_id']] = $category;
				}
			}
			
			return $category_data;
		}
	}		