<?php
class ModelCatalogMikrof extends Model {
	public function editManufacturer(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "manufacturer` WHERE 1";
		$query = $this->db->query($sql);
		foreach($query->rows as $row){
			$sql1 = "UPDATE `" . DB_PREFIX . "manufacturer_description` SET `name`='".$row['name']."' WHERE `manufacturer_id` = '".$row['manufacturer_id']."'";
			$query1 = $this->db->query($sql1);
			echo $row['name'].'<br>';
		}
	}
}
