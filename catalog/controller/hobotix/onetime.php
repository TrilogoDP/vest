<?php
	class ControllerHobotixOnetime extends Controller {

		public function updateAllProducts(){
			include(DIR_SYSTEM.'../Mikrof.php');

			$query = $this->db->ncquery("SELECT product_id FROM oc_product WHERE status = 1 AND archive = 0 AND NOT (LOWER(sku) LIKE '%pl-') AND tmp_updated = 0 ORDER BY RAND()");
			echo 'Всего ' . $query->num_rows . PHP_EOL;

			$ebaniyYarik = new Mikrof();

			foreach ($query->rows as $row){
				$product_id = $row['product_id'];


				try {
				$sku = $ebaniyYarik->getSkuForupdate((int)$product_id, true);
				if(strlen($sku) || is_array($sku)){
					
					if (is_array($sku)){
						foreach ($sku as $sku_line){						
							echo 'Обновляем ' . $sku_line . PHP_EOL;
							$ebaniyYarik->getInfo1c($sku_line);
						}
						} else {
						$ebaniyYarik->getInfo1c($sku);
					}

					$this->db->ncquery("UPDATE oc_product SET tmp_updated = 1 WHERE product_id = '" . $product_id . "'");

				}
				} catch (SoapFault $fault) {		
					$this->db->ncquery("UPDATE oc_product SET tmp_updated = 0 WHERE product_id = '" . $product_id . "'");		
					echo "Ошибка SOAP: (faultcode: $fault->faultcode, faultstring: $fault->faultstring)";
					sleep(40);
				}

				


			}


		}
		
		public function uploadOrders(){
			$data = array();
			include(DIR_SYSTEM.'../Mikrof.php');
			
			$this->load->model('checkout/order');
			$this->load->model('tool/upload');
			
			for ($i = 24544; $i <= 24624; $i++){
				
				$order_id = $i;
				$order_info = $this->model_checkout_order->getOrder($order_id);
				
				if ($order_info && $order_info['order_status_id']){
					// Products
					$data = array();
					$data['products'] = array();
					
					$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
					
					foreach ($order_product_query->rows as $order_product) {
						// Check if there are any linked downloads
						$product_download_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_to_download` WHERE product_id = '" . (int)$order_product['product_id'] . "'");
						
						if ($product_download_query->row['total']) {
							$download_status = true;
						}
					}
					
					foreach ($order_product_query->rows as $product) {
						$option_data = array();
						
						$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
						
						foreach ($order_option_query->rows as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
								} else {
								$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
								
								if ($upload_info) {
									$value = $upload_info['name'];
									} else {
									$value = '';
								}
							}
							
							$option_data[] = array(
							'name'  => $option['name'],
							'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
							);
						}
						
						$data['products'][] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
						);
					}
					
					echo 'Отправляю ' . $order_id . PHP_EOL;
					
					//*** send order to google doc Yaroslav 15-08-2017 BEGIN
					
					Mikrof::sendOrderToGogoleDocs($order_info, $order_id, $data['products']);
					//*** END	
					} else {
					echo 'Пропускаю, нет заказа ' . $order_id . PHP_EOL;
				}
				
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
		
		
		
	}					