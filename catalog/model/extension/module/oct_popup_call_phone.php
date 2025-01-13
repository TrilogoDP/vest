<?php

class ModelExtensionModuleOctPopupCallPhone extends Model {
	public function addRequest($data) {
		$this->db->query("INSERT INTO ".DB_PREFIX."oct_popup_call_phone SET 
			info 			= '".$this->db->escape($data['info'])."', 
			request 		= '".$this->db->escape(json_encode($_SERVER))."',
			binotel_call_id = '" . (int)$binotel_call_id . "', 
			date_added 		= NOW(),
			ip 				= '" . $this->db->escape($data['ip']) . "',
			`timestamp` 	= UNIX_TIMESTAMP()");


		return $this->db->getLastId();		
	}

	public function setBinotelCallId($request_id, $binotel_call_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "oct_popup_call_phone SET binotel_call_id = '" . (int)$binotel_call_id . "' WHERE request_id = '" . (int)$request_id . "'");
	}

	public function limitCalls($data){
		$query = $this->db->ncquery(
			"SELECT COUNT(*) as count FROM `" . DB_PREFIX . "oct_popup_call_phone` 
			WHERE ip = '" . $this->db->escape($data['ip']) . "' 
			AND timestamp >= '" . strtotime('-30 minute') . "'"
		);
		
		return $query->row['count'];		
	}
}	