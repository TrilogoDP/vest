<?php
	
	class ModelHobotixHoboParser extends Model {
		
		public function disableUnexsistentProducts($existentProducts, $existentOptions, $supplier_prefix){
			$productArray = array();
			$optionsArray = array();
			
			//получить все товары поставщика
			$query = $this->db->ncquery("SELECT product_id, sku, model FROM oc_product WHERE model LIKE '%-". $this->db->escape($supplier_prefix) ."' AND SUBSTR(sku, 1, 2) = '" . $this->db->escape($supplier_prefix) . "'");			
			
			foreach ($query->rows as $row){
				$productArray[] = $row['product_id'];
			}
			
			//получить все опции поставщика			
			$query = $this->db->query("SELECT product_option_value_id FROM oc_product_option_value WHERE product_id IN (" . implode(', ', $productArray) . ")");
			
			foreach ($query->rows as $row){
				$optionsArray[] = $row['product_option_value_id'];
			}
			
			//Проверяем значения,
			$unexistentProducts = array();
			if ($existentProducts){
				$unexistentProducts = array_diff($productArray, $existentProducts);
				echoLine('Несуществующих в фиде товаров: ' . count($unexistentProducts) . ', ' . implode(',', $unexistentProducts));			
				
				$this->db->query("UPDATE oc_product SET supplier = 0 WHERE product_id IN (" . implode(',', $unexistentProducts) . ")");
				$this->db->query("UPDATE oc_product SET quantity = stock + supplier WHERE product_id IN (" . implode(',', $unexistentProducts) . ")");

				$this->db->query("UPDATE oc_product_option_value SET supplier = 0 WHERE product_id IN (" . implode(',', $unexistentProducts) . ")");
				$this->db->query("UPDATE oc_product_option_value SET quantity = stock + supplier WHERE product_id IN (" . implode(',', $unexistentProducts) . ")");
			}
			
			$unexistentOptions = array();
			if ($existentOptions){
				$unexistentOptions = array_diff($optionsArray, $existentOptions);
				echoLine('Несуществующих в фиде опций: ' . count($unexistentOptions) . ', ' . implode(',', $unexistentOptions));

				$this->db->query("UPDATE oc_product_option_value SET supplier = 0 WHERE product_option_value_id IN (" . implode(',', $unexistentOptions) . ")");
					$this->db->query("UPDATE oc_product_option_value SET quantity = stock + supplier WHERE product_option_value_id IN (" . implode(',', $unexistentOptions) . ")");
			}
			
			
			return array(
				'unexistentProducts' => $unexistentProducts,
				'unexistentOptions'  => $unexistentOptions
			
			);
			
			
			
		}
		
		public function init(){
			
			$query = $this->db->ncquery("SELECT a.attribute_id, ad.name, a.suppler_mappings 
			FROM `" . DB_PREFIX . "attribute` a 
			LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (a.attribute_id = ad.attribute_id AND ad.language_id = '" . $this->config->get('config_language_id') . "')
			WHERE LENGTH(a.suppler_mappings) > 0");
			
			foreach ($query->rows as $row){
				$exploded = explode(PHP_EOL, $row['suppler_mappings']);
				
				foreach ($exploded as $explodedPart){
					
					$explodedPart = trim($explodedPart);
					$explodedPart = str_replace(PHP_EOL, '', $explodedPart);				
					
					$searchReplaceArray[$explodedPart] = $row['name'];
					$searchReplaceArray[html_entity_decode($explodedPart)] = $row['name'];
				}
			}
			
			return $searchReplaceArray;
			}
		
		public function initDeleted(){
			$deletedSKU = array();
			
			$query = $this->db->ncquery("SELECT TRIM(sku) as sku FROM " . DB_PREFIX . "sku_deleted WHERE 1");
			
			foreach ($query->rows as $row){
				$deletedSKU[] = $row['sku'];
			}
			
			$query = $this->db->ncquery("SELECT TRIM(sku_deleted) as sku_deleted FROM " . DB_PREFIX . "product_sku_deleted WHERE 1");
			
			foreach ($query->rows as $row){
				$deletedSKU[] = $row['sku_deleted'];
			}
			
			return $deletedSKU;
		}
		
		public function initProductOptionsToSKU($suppler_prefix){
			$productOptionsToSKU = array();
			
			
			$query = $this->db->ncquery("SELECT TRIM(sku) as sku, TRIM(optsku) as optsku, product_option_value_id FROM " . DB_PREFIX . "product_option_value WHERE 1");
			
			foreach ($query->rows as $row){
				if ($row['sku']){
					$productOptionsToSKU[$row['sku']] = $row['product_option_value_id'];
					$productOptionsToSKU[$suppler_prefix . $row['sku']] = $row['product_option_value_id'];				
				}
				
				if ($row['optsku']){
					$productOptionsToSKU[$row['optsku']] = $row['product_option_value_id'];
					$productOptionsToSKU[$suppler_prefix . $row['optsku']] = $row['product_option_value_id'];
				}
			}
			
			return $productOptionsToSKU;									
			
		}
		
		public function initProductSKU(){
			$productSKU = array();
			
			$query = $this->db->ncquery("SELECT product_id, TRIM(sku) as sku FROM " . DB_PREFIX . "product WHERE 1");
			
			foreach ($query->rows as $row){
				$productSKU[$row['sku']] = $row['product_id'];
			}
			
			return $productSKU;
		}
	}
