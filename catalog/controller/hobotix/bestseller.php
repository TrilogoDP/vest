<?php
	
	
	class ControllerHobotixBestseller extends Controller {
		

		public function countbestsellers(){

			$this->db->query("UPDATE oc_product p SET orders_90 = (SELECT COUNT(DISTINCT op.order_id) as total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) WHERE product_id = p.product_id AND o.date_added >= '" . date('Y-m-d', strtotime('-90 day')) . "')");

			$this->db->query("UPDATE oc_product p SET orders_180 = (SELECT COUNT(DISTINCT op.order_id) as total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) WHERE product_id = p.product_id AND o.date_added >= '" . date('Y-m-d', strtotime('-180 day')) . "')");


			$query = $this->db->query("SELECT DISTINCT category_id FROM oc_product_to_category WHERE main_category = 1");

			foreach ($query->rows as $row){
				echo('.');
				$this->db->query("UPDATE oc_product SET bestseller = 0 WHERE product_id IN (SELECT product_id FROM oc_product_to_category WHERE category_id = '" . $row['category_id'] . "' AND main_category = 1)");
				$this->db->query("UPDATE oc_product SET bestseller = 1 WHERE product_id IN (SELECT product_id FROM oc_product_to_category WHERE category_id = '" . $row['category_id'] . "' AND main_category = 1) AND quantity > 0 AND status = 1 AND archive = 0 AND orders_90 > 0 ORDER BY orders_90 DESC LIMIT 5");	
			}
		}















	}
		