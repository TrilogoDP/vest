<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

class ModelToolSimpleApiCustom extends Model {
    public function example($filterFieldValue) {
        $values = array();

        $values[] = array(
            'id'   => 'my_id',
            'text' => 'my_text'
        );

        return $values;
    }

    public function checkCaptcha($value, $filter) {
        if (isset($this->session->data['captcha']) && $this->session->data['captcha'] != $value) {
            return false;
        }

        return true;
    }

    public function getYesNo($filter = '') {
        return array(
            array(
                'id'   => '1',
                'text' => $this->language->get('text_yes')
            ),
            array(
                'id'   => '0',
                'text' => $this->language->get('text_no')
            )
        );
    }
	
	 public function getNovaPoshtaStreets($city_id){
            $values = array();
            
            $query = $this->db->query("SELECT * FROM ". DB_PREFIX ."novaposhta_streets WHERE CityID = '" . $this->db->escape($city_id) . "' ORDER BY Description ASC");
           
          //  var_dump($query->rows);
           
            foreach ($query->rows as $row){
                $values[] = array(
                'id'   => $row['Description'],
                'text' => $row['Description']
                );
            }
            
            return $values;

        }

	 public function getCityJustin($uuid) {

		$query = $this->db->query("SELECT descr FROM oc_justin_areas_cities WHERE uuid = '" . $uuid . "'");
		
		if ($query->num_rows){
			return $query->row['descr'];
		}
		
		return false;
	 }
	 
	 public function getWarehouseJustin($uuid) {

		$query = $this->db->query("SELECT descr FROM oc_justin_warehouses WHERE uuid = '" . $uuid . "'");
		
		if ($query->num_rows){
			return $query->row['descr'];
		}
		
		return false;
	 }
	 
	 public function getCityUP($city_id) {

		$query = $this->db->query("SELECT CITY_UA FROM oc_up_cities WHERE CITY_ID = '" . (int)$city_id . "' ORDER BY CITY_UA ASC");
		
		if ($query->num_rows){
			return $query->row['CITY_UA'];
		}
		
		return false;
	 }
	 
	 public function getPostOfficeUP($id) {

		$query = $this->db->query("SELECT POSTINDEX, ADDRESS FROM oc_up_postoffices WHERE ID = '" . (int)$id . "' ORDER BY ADDRESS ASC");
		
		if ($query->num_rows){
			return $query->row;
		}
		
		return false;
	 }
	 
	 
	 public function getCitiesUP($countryId) {
		$values[] = array(
			'id'   => 0,
			'text' => $this->language->get('text_none')
		);
			
        $query_ref = $this->db->query("SELECT up_ref FROM oc_country WHERE country_id = '" . (int)$countryId . "'");
		
		$up_ref = 0;
		if ($query_ref->num_rows) {
			$up_ref = $query_ref->row['up_ref'];
		}
		
		$query = $this->db->query("SELECT c.*, d.DISTRICT_RU, d.DISTRICT_UA FROM oc_up_cities c LEFT JOIN oc_up_districts d ON (c.DISTRICT_ID = d.DISTRICT_ID) WHERE c.REGION_ID = '" . (int)$up_ref . "' AND c.HAS_POSTOFFICES = 1 ORDER BY CITY_UA ASC");
			
        foreach ($query->rows as $result) {
			
			if ($this->config->get('config_language_id') == 1){
				$name = $result['CITY_RU'] . ', ' . mb_strtolower($result['CITYTYPE_RU']);
				$name = $name .' ('. $result['DISTRICT_RU'] . ')';
				
			} else {
				$name = $result['CITY_UA'] . ', ' . mb_strtolower($result['CITYTYPE_UA']);
				$name = $name .' ('. $result['DISTRICT_UA'] . ')';
			}
		
            $values[] = array(
                'id'   => $result['CITY_ID'],
                'text' => $name 
            );
        }

        return $values;
    }    
	 
	 
	public function getPostOfficesUP($city_id) {
		$values[] = array(
			'id'   => 0,
			'text' => $this->language->get('text_none')
		);
			
		$query = $this->db->query("SELECT * FROM oc_up_postoffices WHERE CITY_ID = '" . $city_id . "' GROUP BY POSTINDEX ORDER BY ADDRESS ASC");
			
        foreach ($query->rows as $result) {
            $values[] = array(
                'id'   => $result['ID'],
                'text' => $result['POSTINDEX'].', '.$result['ADDRESS']
            );
        }

        return $values;
    }	
	 
	 
    public function getCitiesJustin($countryId) {
		$values[] = array(
			'id'   => 0,
			'text' => $this->language->get('text_none')
		);
			
        $query_j = $this->db->query("SELECT justin_ref FROM oc_country WHERE country_id = '" . (int)$countryId . "'");
		$code_j = "";
		if ($query_j->num_rows) {
			$code_j = $query_j->row['justin_ref'];
		}
		
		$query = $this->db->query("SELECT * FROM oc_justin_areas_cities WHERE owner = '" . $code_j . "'");
			
        foreach ($query->rows as $result) {
            $values[] = array(
                'id'   => $result['uuid'],
                'text' => $result['descr']
            );
        }

        return $values;
    }    
	
	public function getWsrehousiesJustin($code_j) {
		$values[] = array(
			'id'   => 0,
			'text' => $this->language->get('text_none')
		);
			
		$query = $this->db->query("SELECT * FROM oc_justin_warehouses WHERE city = '" . $code_j . "'");
			
        foreach ($query->rows as $result) {
            $values[] = array(
                'id'   => $result['uuid'],
                'text' => $result['descr'].', '.$result['address']
            );
        }

        return $values;
    }	
}