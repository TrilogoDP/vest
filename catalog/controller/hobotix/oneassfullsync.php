<?php
	class ControllerHobotixOneassfullsync extends Controller {
		
		public function index(){
			
			ini_set('max_execution_time', '0');
			
			$cli_execution = false;
			if(strpos(php_sapi_name(), "cli") !== false){
				$cli_execution = true;		
				} else {
				echo exit('CLI EXECUTION ONLY');
			}
			
			
			$data = array();
			include(DIR_SYSTEM.'../Mikrof.php');
			
			
			$query = $this->db->query("SELECT sku FROM " . DB_PREFIX . "product WHERE archive = 0 AND status = 1 AND NOT (LOWER(sku) LIKE '%pl-')");
			
			$query_options = $this->db->query("SELECT sku FROM " . DB_PREFIX . "product_option_value WHERE LENGTH(sku) > 1 AND product_id IN 
			(SELECT product_id FROM " . DB_PREFIX . "product WHERE archive = 0 AND status = 1 AND NOT (LOWER(sku) LIKE '%pl-'))");
			
			echo 'Начали синхронизацию ' . date('Y-m-d H:i:s') . PHP_EOL;
			echo 'SKU: ' . count($query->rows) . PHP_EOL;
			
			foreach ($query->rows as $row){				
				Mikrof::getInfo1c($row['sku']);
			}
			
			echo 'SKU Опций: ' . count($query_options->rows) . PHP_EOL;
			
			foreach ($query_options->rows as $row){				
				Mikrof::getInfo1c($row['sku']);
			}
			
			echo 'Закончили синхронизацию ' . date('Y-m-d H:i:s') . PHP_EOL;
			echo 'Обработали ' . count($query->rows) . ' товаров'. PHP_EOL;
			echo 'Обработали ' . count($query_options->rows) . ' опций'. PHP_EOL;

			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = 0 WHERE quantity < 0 ");
			$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = 0 WHERE quantity < 0 ");
			$this->db->query("UPDATE " . DB_PREFIX . "product SET stock = 0 WHERE stock < 0 ");
			$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET stock = 0 WHERE stock < 0 ");

			$mysqli->query("UPDATE " . DB_PREFIX . "product SET quantity = stock + supplier WHERE 1");
			$mysqli->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = stock + supplier WHERE 1");
		}		
	}	