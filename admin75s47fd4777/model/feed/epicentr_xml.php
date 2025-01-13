<?php
class ModelFeedEpicentrXml extends Model {

	public function uninstall() {
		
	}

	public function pre_install() {
		
	}

	public function getAuthData(){

	}


	public function getAPIToken(){

	}

	public function delMarketData(){

	}

	public function getMarketData(){

	}

	public function saveMarketData($data){

	}

	public function api_import_row($data){

	}

	public function local_import() {

	}


	public function clear_tables(){

	
	}


	public function checkTables() {


	    $product_col = array(
	          array('product' => 'epicentr_name'),
	          array('product' => 'epicentr_name_ua'),
	          array('product' => 'epicentr_price'),
	          array('product' => 'epicentr_quantity'),
	          array('product' => 'epicentr_status'),
	          array('product' => 'epicentr_description'),
	          array('product' => 'epicentr_description_ua')
	        );


	    $category_col = array(
	          array('category' => 'epicentr_name'),
	          array('category' => 'epicentr_coeff'),
	          array('category' => 'epicentr_country'),
	          array('category' => 'epicentr_category_id')
	        );

	    $image_col = array(
	          array('product_image' =>'epicentr_status'),
	          array('product_image' =>'epicentr_option')
	        );

	    $manufacturer =  array(
	    	  array('manufacturer' => 'epicentr_coeff'),
	    	  array('manufacturer' => 'epicentr_country')
	    	);

	    $tables = array(
	    	'epicentr_categories'
	    );

	    $columns = array_merge($product_col, $category_col, $image_col, $manufacturer);
		$results = array();



		foreach ($columns as $col) {
		
			$key = key($col);
			$val = current($col);

			$query= $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "".$key."` LIKE '".$val."' ");   

			if($query->num_rows  == 0) {

				$results[] = array('table' => $key, 'column' => $val, 'exists' => 0);

			} else {

				$results[] = array('table' => $key, 'column' => $val, 'exists' => 1);
			}

		}


		foreach ($tables as $table) {

			$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX.$table."' "); 

			if($query->num_rows == 1){

					$results[] = array('table' => $table, 'column' => 'таблица', 'exists' => 1);

			} else {

				    $results[] = array('table' => $table, 'column' => 'таблица', 'exists' => 0);
			}

		}

		return $results;

	}


	public function install() {

		$base_tables = array(

			'product' => array( 

					array('column'   => 'epicentr_name',        'type'  => 'TEXT',    'default' => 'NOT NULL', 'after'   => 'date_modified'),
					array('column'   => 'epicentr_name_ua',     'type'  => 'TEXT',    'default' => 'NOT NULL', 'after'   => 'date_modified'),
					array('column'   => 'epicentr_quantity',    'type'  => 'INT(10)', 'default' => 'NOT NULL', 'after'   => 'date_modified'), 
					array('column'   => 'epicentr_price',       'type'  => 'VARCHAR(10)', 'default' => 'NOT NULL', 'after'   => 'date_modified'),
					array('column'   => 'epicentr_status',      'type'  => 'INT(1)',  'default' => 'DEFAULT 1', 'after'  => 'date_modified'),
					array('column'   => 'epicentr_description', 'type'  => 'TEXT',    'default' => 'NOT NULL', 'after'   => 'date_modified'),
					array('column'   => 'epicentr_description_ua','type'  => 'TEXT',  'default' => 'NOT NULL', 'after'   => 'date_modified')
			), 

			'category' => array( 
								
					array('column'   => 'epicentr_name',        'type'  => 'TEXT', 'default' => 'NOT NULL', 'after'   => 'date_modified' ), 
					array('column'   => 'epicentr_country',     'type'  => 'TEXT', 'default' => 'NOT NULL', 'after'   => 'sort_order'),
					array('column'   => 'epicentr_coeff',       'type'  => 'VARCHAR(10)', 'default' => 'NOT NULL', 'after'    => 'date_modified'), 
					array('column'   => 'epicentr_category_id', 'type'  => 'INT(15)',     'default' => 'DEFAULT 0', 'after'   => 'date_modified')
			),

			'product_image' => array(

					array('column'   => 'epicentr_status', 'type'  => 'INT(1)', 'default' => 'DEFAULT 1', 'after'   => 'sort_order'),
					array('column'   => 'epicentr_option', 'type'  => 'INT(5)', 'default' => 'DEFAULT 0', 'after'   => 'sort_order')
			),

			'manufacturer' => array( 
				
					array('column'   => 'epicentr_coeff',     'type'   => 'VARCHAR(10)', 'default' => 'NOT NULL', 'after'   => 'sort_order'),
					array('column'   => 'epicentr_country',   'type'   => 'TEXT', 'default' => 'NOT NULL', 'after'   => 'sort_order')
			)

				);




		foreach ($base_tables as $table => $columns) {
		
			foreach ($columns as $column) {

			$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "".$table."` LIKE '".$column['column']."' ";  
	

	    		$result = $this->db->query($sql);   

		    	if($result->num_rows == 0) {

					$this->db->query("
						ALTER TABLE   `" . DB_PREFIX . "".$table."` 
								ADD   `".$column['column']."` ".$column['type']." ".$column['default']." 
								AFTER `".$column['after']."`
					");

				}
			}
		}

		//// таблица для каталога категорий
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "epicentr_categories` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `category_id` int(11) NOT NULL,
                          `parent_id` int(11) NOT NULL,
                          `name` varchar(255)  NOT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

		$result =  $this->db->query($sql);


	}


	public function saveEpicentrName($product_id, $epicentr_name) {

		$sql = "UPDATE `" . DB_PREFIX . "product` SET `epicentr_name`= '" . $this->db->escape($epicentr_name) . "' WHERE `product_id` = '" . (int)$product_id . "'";

		return $this->db->query($sql);
	}

	public function saveEpicentrPromo($product_id, $promo) {

		$sql = "UPDATE `" . DB_PREFIX . "product` SET `epicentr_promo`= '" . $this->db->escape($promo) . "' WHERE `product_id` = '" . (int)$product_id . "'";

		return $this->db->query($sql);
	}

	public function saveEpicentrQuantity($product_id, $quantity) {

		$sql = "UPDATE `" . DB_PREFIX . "product` SET `epicentr_quantity`= '" . $this->db->escape($quantity) . "' WHERE `product_id` = '" . (int)$product_id . "'";

		return $this->db->query($sql);
	}


	public function saveEpicentrStatus($product_id, $status) {

		$sql = "UPDATE `" . DB_PREFIX . "product` SET `epicentr_status`= '" . $this->db->escape($status) . "' WHERE `product_id` = '" . (int)$product_id . "'";

		return $this->db->query($sql);
	}


	public function saveEpicentrCategory($category_id, $epicentr_name, $epicentr_id){

		if (!empty($category_id) && !empty($epicentr_name) && !empty($epicentr_id)) {

			$sql = "UPDATE 
						" . DB_PREFIX . "category 
					SET 
						epicentr_name = '" . $this->db->escape($epicentr_name) . "', 
						epicentr_category_id = '" . $this->db->escape($epicentr_id) . "' 
					WHERE 
						category_id = " . (int)$category_id;


			return $this->db->query($sql);

		} else if(empty($epicentr_name) && !empty($epicentr_id)) {

			$sql = "UPDATE 
						" . DB_PREFIX . "category 
					SET 
						epicentr_name = '', epicentr_category_id = '' 
					WHERE 
						category_id = " . (int)$category_id;


			return $this->db->query($sql);
		}

	}


	public function saveEpicentrCatCoeff($category_id, $epicentr_coeff){

		$sql = "UPDATE " . DB_PREFIX . "category SET epicentr_coeff = '" . $this->db->escape($epicentr_coeff) . "' WHERE category_id = " . (int)$category_id;
		
		return $this->db->query($sql);
	}


	public function getCategoryStatus($category_id) {

		$categories = explode(',', $this->config->get('epicentr_xml_categories'));

		if(in_array($category_id, $categories)){
			return 1;
		} else {
			return 0;
		}

	}


	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}


	public function getProducts($data = array()) {
		
		$sql = "SELECT p.product_id, pd.name, p.price, p.quantity, p.epicentr_name, p.epicentr_quantity, p.epicentr_promo, p.epicentr_status, p2c.category_id 


				FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
				LEFT JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id)
				WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['category']) && !is_null($data['category'])) {
			$sql .= " AND p2c.category_id = '" . (int)$data['category'] . "'";
		}



		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

	
		$query = $this->db->query($sql);
		return $query->rows;
	}
  

  	public function getCategoryNameById($category_id){

		$sql = "SELECT `name` FROM " . DB_PREFIX . "epicentr_categories WHERE `category_id` = '".$category_id."' ";

		$query = $this->db->query($sql);

		return $query->row['name'];
	}



	public function getCategories($data = array()) {

		$sql = "SELECT `category_id`, `name` FROM " . DB_PREFIX . "epicentr_categories WHERE ";


		if (!empty($data['filter_name'])) {

			$sql .= " name LIKE '%" . $this->db->escape($data['filter_name']) . "%' OR category_id LIKE '" . $this->db->escape($data['filter_name']) . "%' ";
		}


		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}



		$query = $this->db->query($sql);

		return $query->rows;
	}


}