<?php
/**
 *
 * @ MikroF
 * @ version : 1.0
 * @ Author     : Yaroslav Smachylo
 * @ Release on : 14.09.2018
 * @ Website    : https://it.mikro-f.com.ua
 *
 **/
 
class ControllerBatchEditorMikrof extends Controller {
	function index() {
		//
	}
	
	function edit() {	
	}

	function getPriceDate(){
		$query = $this->db->query("SELECT price_date FROM " . DB_PREFIX . "product WHERE product_id = '". (int)$this->request->get['product_id'] . "'");

		$this->response->setOutput($query->row['price_date']);
	}

	function editPrice(){
		$this->load->model('catalog/product');

		$this->model_catalog_product->updateProductPriceDate($this->request->post['product_id'], $this->request->post['price']);
	}

	function getDnupDate(){
		$query = $this->db->query("SELECT dnup_date FROM " . DB_PREFIX . "product WHERE product_id = '". (int)$this->request->get['product_id'] . "'");

		$this->response->setOutput($query->row['dnup_date']);
	}

	function editDnup(){
		$this->load->model('catalog/product');

		$this->model_catalog_product->updateProductDNUPDate($this->request->post['product_id'], $this->request->post['dnup']);
	}
	
	function addFilters($product_id){		
		return true;		
	}
	function edit_option() {
		$json = json_decode($_POST['data'], true);
		$product_option_value_id = 0;
		foreach($json as $key => $d){
			if(strpos($d['name'], "[product_option_value_id]")!== false){
				$product_option_value_id = $d['value'];
			}
			if(strpos($d['name'], "[sku]")!== false){
				$sku = $d['value'];
				if(strlen($sku) > 0 && (int)$product_option_value_id > 0){
					$sql = "UPDATE `" . DB_PREFIX . "product_option_value` SET `sku`='".$sku."' WHERE product_option_value_id ='".(int)$product_option_value_id."'";
					$query = $this->db->query($sql);
					echo 'edit success sku';
				}
			}
			if(strpos($d['name'], "[o_v_image]")!== false){
				$o_v_image = $d['value'];
				if(strlen($o_v_image) > 0 && (int)$product_option_value_id > 0){
					$sql = "UPDATE `" . DB_PREFIX . "product_option_value` SET `o_v_image`='".$o_v_image."' WHERE product_option_value_id ='".(int)$product_option_value_id."'";
					$query = $this->db->query($sql);
					echo 'edit success image';
				}
			}			
		}
	}
}