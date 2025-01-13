<?php
class ModelLocalisationZone extends Model {
	public function addZone($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "'");

		$this->cache->delete('zone');
		
		return $this->db->getLastId();
	}

	public function editZone($zone_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "zone SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "' WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}

	public function deleteZone($zone_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}

	public function getZone($zone_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row;
	}

	public function getZones($data = array()) {
		$sql = "SELECT *, z.name, c.name AS country FROM " . DB_PREFIX . "zone z LEFT JOIN " . DB_PREFIX . "country c ON (z.country_id = c.country_id)";

		$sort_data = array(
			'c.name',
			'z.name',
			'z.code'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.name";
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

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

			$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}

	public function getTotalZones() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone");

		return $query->row['total'];
	}

	public function getTotalZonesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "'");

		return $query->row['total'];
	}
	//******mf begin
	public function addUpdateJustinArea($obj){
		$query_exist = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_areas_region WHERE uuid = '" . $obj->uuid . "'");
		if($query_exist->num_rows == 0){
			$descr = $obj->descr;
			$descr = str_replace("'",'`',$descr);
			$this->db->query("INSERT INTO " . DB_PREFIX . "justin_areas_region SET uuid = '" . $obj->uuid . "', code = '" . $obj->code . "', descr = '" . $descr . "', owner = '" . $obj->objectOwner->uuid . "'");
		}
		return true;
	}
	public function addUpdateJustinCity($obj){
		$query_exist = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_areas_cities WHERE uuid = '" . $obj->uuid . "'");
		if($query_exist->num_rows == 0){
			$descr = $obj->descr;
			$descr = str_replace("'",'`',$descr);
			$this->db->query("INSERT INTO " . DB_PREFIX . "justin_areas_cities SET uuid = '" . $obj->uuid . "', code = '" . $obj->code . "', descr = '" . $descr . "', owner = '" . $obj->objectOwner->uuid . "'");
		}
		return true;
	}
	public function addUpdateJustinWarehouse($obj){
		$query_exist = $this->db->query("SELECT * FROM " . DB_PREFIX . "justin_warehouses WHERE uuid = '" . $obj->uuid . "'");
		if($query_exist->num_rows == 0){
			$descr = $obj->descr; $descr = str_replace("'",'`',$descr);
			$address = $obj->address; $address = str_replace("'",'`',$address);
			$this->db->query("INSERT INTO " . DB_PREFIX . "justin_warehouses SET uuid = '" . $obj->Depart->uuid . "', city = '" . $obj->city->uuid . "', descr = '" . $descr . "', address = '" . $address . "'");
		}
		return true;
	}	
	public function getTotalZonesJustin() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "justin_areas_cities");

		return $query->row['total'];
	}	
	public function getZonesJustin() {
		$sql = "SELECT *, c.name AS country FROM " . DB_PREFIX . "justin_areas_cities z LEFT JOIN " . DB_PREFIX . "country c ON (z.owner = c.justin_ref)";

		$query = $this->db->query($sql);

		return $query->rows;
	}	
	//******mf end
}