<?php

class ControllerHobotixCopy extends Controller
{
	

	public function index(){

	}

	public function copyPrivacyMatte(){
		$this->load->model('catalog/copy');


		$query = $this->db->query("SELECT product_id, sku FROM oc_product WHERE price = 179 AND sku LIKE('" . $this->db->escape($this->skuToCopy) . "%')
		 AND product_id NOT IN (SELECT copied_from FROM oc_product WHERE 1)
		 AND product_id NOT IN (SELECT product_id FROM oc_product_description WHERE name LIKE ('%панель%'))");

			foreach ($query->rows as $row){
				$this->model_catalog_copy->copyInobiMatte($row['product_id']);
			}

		}

		public function copyInobiMatte(){
		$this->load->model('catalog/copy2');


		$query = $this->db->query("SELECT product_id, sku FROM oc_product WHERE price = 179 AND sku LIKE('" . $this->db->escape($this->skuToCopy) . "%')
		 AND product_id NOT IN (SELECT copied_from2 FROM oc_product WHERE 1)");

			foreach ($query->rows as $row){
				$this->model_catalog_copy2->copyInobiMatte($row['product_id']);
			}

		}
		
	}	