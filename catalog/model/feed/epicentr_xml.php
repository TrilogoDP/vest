<?php
/*
   Epicentr XML Feed by Alexey Soloviov aka ASEN :: ionline.su // shop.ionline.su
   v1.2.2 BASED ON RXML 2.6.5 / OC 2.x
*/
class ModelFeedEpicentrXML extends Model {

	private $version = '1.2.2';
	
	public function getCategory($allowed_categories, $language) {

	$sql = "
		SELECT 
			c.category_id, cd.name , c.epicentr_category_id, c.epicentr_name, c.rozetka_overprice, c.epicentr_coeff, c.epicentr_country as category_country,

			(SELECT category_id FROM " . DB_PREFIX . "category WHERE category_id = c.parent_id AND category_id != c.category_id) as parent_id

		FROM " . DB_PREFIX . "category c 

		     LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
		     LEFT JOIN " . DB_PREFIX . "category_to_store   c2s ON (c.category_id = c2s.category_id) 

		     	WHERE  ( 
		     			c.category_id IN (" . $this->db->escape($allowed_categories) . ")
		     				OR
						c.category_id IN (SELECT parent_id  FROM " . DB_PREFIX . "category WHERE category_id IN (" . $this->db->escape($allowed_categories) . ") AND status = '1')
						)

		     	  AND cd.language_id = '" . $language . "' 
		     	  AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  
		     	  AND c.status = '1'";


		$query = $this->db->query($sql);

		return $query->rows;
	}
	

	//функция для получения последней привязанной категории к товару
	public function getProductLastCategory($product_id, $allowed_categories) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = " . (int)$product_id . "");
		$return = '';
		if(!empty($query->rows)){
			foreach ($query->rows as $result) {
				
				$return = (in_array($result['category_id'], explode(',', $allowed_categories))) ? $result['category_id'] : $return;
			}
		}
		return $return;
	}
	

	public function getCategoryByManufacturers($manufacturers = array() , $language){	 

	$sql = " 
			SELECT 
				cd.name , m.epicentr_coeff, c.epicentr_name, c.epicentr_category_id, c.category_id,

				m.epicentr_country   as manufacturer_country,
				c.epicentr_country   as category_country,
				 
				(SELECT category_id FROM " . DB_PREFIX . "category WHERE category_id = c.parent_id AND category_id != c.category_id) as parent_id

			 FROM " . DB_PREFIX . "product p 

			        LEFT JOIN " . DB_PREFIX . "product_to_category   p2c ON (p.product_id      = p2c.product_id) 
			        LEFT JOIN " . DB_PREFIX . "category              c   ON (p2c.category_id   = c.category_id)
			        LEFT JOIN " . DB_PREFIX . "category_description  cd  ON (c.category_id     = cd.category_id)
			        LEFT JOIN " . DB_PREFIX . "manufacturer          m   ON (m.manufacturer_id = p.manufacturer_id) 

			 WHERE 
			 		p.manufacturer_id IN (" . $this->db->escape($manufacturers) . ") 
			       	AND p.epicentr_status = 1
			       	AND cd.language_id = '" . $language . "' 
			
			 GROUP BY c.category_id";


		$query = $this->db->query($sql);
	 
		return $query->rows;

	}


	

	public function getCategoryByProducts($products = array() , $language){	 


	$sql = " 
			SELECT 
				cd.name ,c.category_id, c.rozetka_overprice, c.epicentr_coeff, c.epicentr_name,  c.epicentr_category_id, 

				m.epicentr_country   as manufacturer_country,
				c.epicentr_country   as category_country,

				(SELECT category_id FROM " . DB_PREFIX . "category WHERE category_id = c.parent_id AND category_id != c.category_id) as parent_id

			FROM " . DB_PREFIX . "product p 

			        JOIN " . DB_PREFIX . "product_to_category AS     p2c ON (p.product_id = p2c.product_id) 
			        LEFT JOIN " . DB_PREFIX . "category              c   ON (p2c.category_id = c.category_id)
			        LEFT JOIN " . DB_PREFIX . "category_description  cd  ON (c.category_id = cd.category_id)
			        LEFT JOIN " . DB_PREFIX . "manufacturer          m   ON (m.manufacturer_id = p.manufacturer_id) 
			
			WHERE 
					p.product_id IN (" . $this->db->escape($products) . ") 
			       	AND p.epicentr_status = 1
			       	AND cd.language_id = '" . $language . "' 
			 
			GROUP BY c.category_id";

			 
		$query = $this->db->query($sql);
	 
		return $query->rows;

	}




	public function getProduct($allowed_categories, $allowed_products, $out_of_stock_id, $zero_remain, $manufacturers = array(), $type = 0 , $language) {

		$sql = "

		SELECT p.*, pd.name, pd.description, pd.fake_description,  
		    m.name AS manufacturer,
			p2c.category_id,
		 	COALESCE( NULLIF( p.epicentr_price, 0 ), p.price ) AS price, 
		 	ps.price AS special
		 	


		FROM " . DB_PREFIX . "product p 

		 	  JOIN " . DB_PREFIX . "product_to_category AS   p2c ON (p.product_id = p2c.product_id) 
		 	  LEFT JOIN " . DB_PREFIX . "manufacturer        m   ON (p.manufacturer_id = m.manufacturer_id) 
		      LEFT JOIN " . DB_PREFIX . "product_description pd  ON (p.product_id = pd.product_id) 
		      LEFT JOIN " . DB_PREFIX . "product_to_store    p2s ON (p.product_id = p2s.product_id) 
		      LEFT JOIN " . DB_PREFIX . "product_special     ps  ON (p.product_id = ps.product_id) 

			      AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' 
			      AND ps.date_start < NOW() 
			      AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()) 

		      WHERE ";

			if($type == 0) {
				$sql .= " p2c.category_id     IN (" . $this->db->escape($allowed_categories) . ") ";
			} else if($type == 1) {
				$sql .= " p.manufacturer_id   IN (" . $this->db->escape($manufacturers) . ") ";
			} else if($type == 2) {
				$sql .= " ( p.manufacturer_id IN (" . $this->db->escape($manufacturers) . ") AND p2c.category_id IN (" . $this->db->escape($allowed_categories) . ") ) ";
			} else if($type == 3) {
				$sql .= " p.product_id        IN (" . $this->db->escape($allowed_products) . ") ";
			}


		    $sql .= " 
		     	AND p2s.store_id   = '" . (int)$this->config->get('config_store_id') . "' 
		      	AND pd.language_id = '" . $language . "' 
		      	AND p.date_available <= NOW() 
		      	AND p.status = '1' ";

		     if($zero_remain == 1) {

		     	$sql .= " AND ( (p.quantity > '0') OR ( p.quantity = '0'"; 

		     	if($out_of_stock_id) {

		     		$sql .= " AND p.stock_status_id IN (" .  $out_of_stock_id . ") ";
		        }

		     	$sql .= ") )";

		     } else {

		     	$sql .= " AND p.quantity > '0' ";
		     }
		      	

		  $sql .= " AND p.custom_yml_mf = '1' ";
		  $sql .= " AND p.archive = '0' ";
		  $sql .= " AND p.archive_auto = '0' ";
		  $sql .= " AND p.tax_class_id = '0' ";
		  $sql .= " AND p.price > 0 ";
		  $sql .= " GROUP BY p.product_id";

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getProductOptions($product_id, $allowed_options, $types, $zero) {
		$product_option_data = array();

		$sql = "SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') ."' ";

		if($types) {
			$sql .= " AND o.type IN('".implode("','",$types)."') ";
		}


		if($allowed_options) {
			$sql .= " AND o.option_id IN(". $allowed_options .") ";
		}

		$sql .= " ORDER BY o.sort_order";


		$product_option_query = $this->db->query($sql);



		foreach ($product_option_query->rows as $product_option) {

			$product_option_value_data = array();

			$o_sql = "SELECT * 
							FROM " . DB_PREFIX . "product_option_value pov 
							     LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) 
							     LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 

							WHERE 
								 pov.product_id = '" . (int)$product_id . "' 
							 AND 
							     pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' 
							 AND 
							 	 ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

			if(!$zero){

				$o_sql .= " AND pov.quantity > 0 ";
			}
							 
				$o_sql .= " ORDER BY ov.sort_order";


			$product_option_value_query = $this->db->query($o_sql);

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'sku'                     => $product_option_value['sku'],
					'image'                   => $product_option_value['image'],
					'o_v_image'               => $product_option_value['o_v_image'],
					'quantity'                => $product_option_value['quantity'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value']
			);
		}
		
		// echo '<pre>';
		// var_dump($product_option_data);
		// echo '</pre>';
		// exit;

		return $product_option_data;

	}




	public function getProductAttributes($product_id , $type, $language) {

		$product_attribute_group_data = array();


		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$language . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {

			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$language . "' AND pa.language_id = '" . (int)$language . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);


				$name = $type == 0 ? $product_attribute['name'] : $product_attribute_group['name'];
				$text = $type == 0 ? $product_attribute['text'] : $product_attribute['name'];

				$product_attribute_group_data[$product_attribute_group['attribute_group_id']][$product_attribute['attribute_id']] = array(
					'name' => $name,
					'text' => $text
				);

			}


		}

		return $product_attribute_group_data;
	}



	public function getProductImages($product_id , $count=10) {

		$sql = "SELECT `image`, `epicentr_option` as `option` FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'  ORDER BY sort_order ASC LIMIT ". $count ." ";


//$sql = "SELECT `image`, `epicentr_option` as `option` FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND `epicentr_status` = 1 ORDER BY sort_order ASC LIMIT ". $count ." ";


		$query = $this->db->query($sql);

		return $query->rows;
	}



	public function getProductUaDescription($product_id, $language) {

		// var_dump($product_id);
		// exit;

		$sql = "SELECT `fake_description` FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '".$language."'";

		$query = $this->db->query($sql);
		
		// var_dump($query->row['fake_description']);
		// exit;
		return $query->row['fake_description'];
	}


	public function getProductDescriptionUa($product_id, $language) {

		$sql = "SELECT `description` FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '".$language."'";

		$query = $this->db->query($sql);
		

		return $query->row['description'];
	}


	public function getProductUaName($product_id , $language) {

		$sql = "SELECT `name` FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '".$language."'";

		$query = $this->db->query($sql);

		return $query->row['name'];
	}



	public function getOptionUaName($option , $language_ua) {

		$sql = "SELECT `name` FROM " . DB_PREFIX . "product_option_value pov 
				  LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) 
				  LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 

							WHERE 
							    pov.product_option_value_id = '" . (int)$option['product_option_value_id'] . "' 
							 AND 
							 	 ovd.language_id = '" . (int)$language_ua . "' ";

		$query = $this->db->query($sql);

		return $query->row['name'];
	}


	public function check_licence(){


		$server  = 'https://licence.ionline.su'; 
		$request = $server.'/check.php?module=epicentr_xml&version='.$this->version.'&domain='.strtolower($_SERVER['SERVER_NAME']).'&server='.strtolower($_SERVER['SERVER_NAME']).'&opencart='.VERSION;
		
		$options = array(
	        CURLOPT_RETURNTRANSFER => true,   // return web page
	        CURLOPT_HEADER         => false,  // don't return headers
	        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
	        CURLOPT_MAXREDIRS      => 5,      // stop after 5 redirects
	        CURLOPT_ENCODING       => "",     // handle compressed
	        CURLOPT_USERAGENT      => $_SERVER['SERVER_NAME'], // name of client
	        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
	        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
	        CURLOPT_TIMEOUT        => 120,    // time-out on response
	    ); 

	    $ch = curl_init($request);
	    curl_setopt_array($ch, $options);
	    $content  = curl_exec($ch);
	    curl_close($ch);

	  	if($content == 'OK'){
	  		return 1;
	  	} else {
	  		return 0;
	  	}


	}

}
?>
