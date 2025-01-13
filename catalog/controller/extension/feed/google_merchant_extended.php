<?php
	error_reporting(1);
	ini_set('fastcgi_read_timeout', 6400);//***mf
	ini_set('proxy_read_timeout', 6400);//***mf
	
	
	class ControllerExtensionFeedGoogleMerchantExtended extends Controller {
		private $color_options_array = array(14, 17, 18);
		private $is_cli;
		
		private function is_cli(){
			
		}
		
		protected function strip_html_tags( $text )
		{
			$text = preg_replace(
			array(
			// Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
			// Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
			),
			$text );
			return strip_tags( $text );
		}
		
		private function normalizeForGoogle($text){		
			$text = str_replace('&nbsp;', ' ', $text);
			$text = str_replace(' & ', ' and ', $text);
			$text = str_replace('&', ' and ', $text);
			$text = preg_replace("/&#?[a-z0-9]{2,8};/i","",$text);
			
			return trim($text);
		}
		
		public static function fixEncoding($string){
			$string=str_replace("&lt;br&gt;"," ",$string);
			$string=str_replace("&amp;lt;","&lt;",$string);
			$string=str_replace("&amp;gt;","&gt;",$string);
			$string=str_replace("&amp;quot;","&quot;",$string);
			$string=str_replace("&amp;amp;","&amp;",$string);
			$string=str_replace("&amp;nbsp;","&amp;&nbsp;",$string);
			$string=str_replace("&amp;&nbsp;"," ",$string);
			$string=str_replace("&nbsp;"," ",$string);
			$string=str_replace("&quot;","\"",$string);
			$string=str_replace("&gt;",">",$string);
			$string=str_replace("&lt;","<",$string);
			$string=str_replace("&amp;","&",$string);
			$string=str_replace("<br>"," ",$string);
			return $string;
		}
		
		private function clearDescriptionForGoogle($text){
			$text= str_replace("
			", " ",str_replace("\t", " ",str_replace("\n", " ", str_replace("\r", " ", str_replace("\r\n", " ", htmlspecialchars($this->strip_html_tags(htmlspecialchars_decode($text,ENT_COMPAT)),ENT_COMPAT, 'UTF-8'))))));
			while (strpos($text, "  ") !== false) {
				$text=str_replace("  "," ",$text);
			}
			$text=$this->fixEncoding($text);
			
			while($this->startsWith($text,"&amp;nbsp;") || $this->endsWith($text,"&amp;nbsp;") || $this->startsWith($text," ") || $this->endsWith($text," ")) {
				$text = $this->clearDescription($text,"&amp;nbsp;");
				$text = $this->clearDescription($text," ");
			}
			
			while (strpos($text, '  ') !== false) {
				$text=str_replace('  ',' ',$text);
			}
			
			$text=trim($text);
			
			return $text;
		}
		
		private function clearDescription($string, $remove)
		{
			while ($this->startsWith($string,$remove)){
				$string = substr($string, strlen($remove));
			}
			while ($this->endsWith($string,$remove)){
        		$string = substr($string, 0, strlen($string) - strlen($remove));
			}
			
			
			
			return $string;
		}
		
		private function startsWith($haystack, $needles)
		{
			foreach ((array) $needles as $needle)
			{
				if ($needle != '' && strpos($haystack, $needle) === 0) return true;
			}
			return false;
		}
		
		private function endsWith($haystack, $needles)
		{
			foreach ((array) $needles as $needle)
			{
				if ((string) $needle === substr($haystack, -strlen($needle))) return true;
			}
			return false;
		}
		
		protected function getPath($parent_id, $current_path = '') {
			$category_info = $this->model_catalog_category->getCategory($parent_id);
			
			if ($category_info) {
				if (!$current_path) {
					$new_path = $category_info['category_id'];
					} else {
					$new_path = $category_info['category_id'] . '_' . $current_path;
				}
				
				$path = $this->getPath($category_info['parent_id'], $new_path);
				
				if ($path) {
					return $path;
					} else {
					return $new_path;
				}
			}
		}
		
		public function index(){
		
			die('MOVED TO CLI');
		
			if ($this->config->get('google_merchant_center_status')) {
				$this->load->model('catalog/category');
				$this->load->model('catalog/product');
				$this->load->model('feed/google_merchant_center');
				$this->load->model('tool/image');
				
				$image_width = $this->config->get('config_image_popup_width');
				$image_height = $this->config->get('config_image_popup_height');
				if ($width < 600 || $height < 600) {
					$width = 600;
					$height = 600;
				}
				
				$currency_code = $this->config->get('config_currency');			
				$currency_value = $this->currency->getValue($currency_code);
				$language_id = $this->config->get('config_language_id');
				$store_id = $this->config->get('config_store_id');
				
				$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
				$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
				$output .= '  <channel>';
				$output .= '  <title>' . $this->config->get('config_name') . '</title>';
				$output .= '  <description>' . $this->config->get('config_meta_description') . '</description>';
				$output .= '  <link>' . $this->config->get('config_url') . '</link>';
				
				
				$products = $this->model_feed_google_merchant_center->getProductsOptioned($language_id, $store_id);
				
				foreach ($products as $_product){
					$product = $this->model_catalog_product->getProduct($_product['product_id']);
					$options = $this->model_catalog_product->getProductOptions($product['product_id']);
					
					$categories = $this->model_catalog_product->getCategories($product['product_id']);
					$category_path = '';
					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);
						
						if ($path) {
							$category_path = '';
							
							foreach (explode('_', $path) as $path_id) {
								$category_info = $this->model_catalog_category->getCategory($path_id);
								
								if ($category_info) {
									if (!$category_path) {
										$category_path = $category_info['name'];
										} else {
										$category_path .= ' &gt; ' . $category_info['name'];
									}
								}
							}													
						}
					}
					
					if ($options){
						foreach ($options as $option){		
							
							if (!empty($option['product_option_value'])){
								foreach ($option['product_option_value'] as $option_value){
									
									$output .= '<item>';									
									$output .= '<title><![CDATA[' . trim($this->fixEncoding($product['name'] . ' ' .  mb_strtolower($option_value['name']))) . ']]></title>' . PHP_EOL;
									$output .= '<link><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id'] . '&option_id=' . $option_value['product_option_value_id']) . ']]></link>' . PHP_EOL;
									$output .= '<description><![CDATA[' . $this->clearDescriptionForGoogle($product['description']) . ']]></description>' . PHP_EOL;
									
									//IDS
									$output .= '<g:id><![CDATA[' . $product['product_id'] . '-' . (int)$option['option_id'] . '-' . (int)$option_value['option_value_id'] . ']]></g:id>' . PHP_EOL;
									
									if ($product['ean'] || $option_value['ean']){
										if ($option_value['ean']){
											$output .= '  <g:gtin><![CDATA[' . trim($this->fixEncoding($option_value['ean'])) . ']]></g:gtin>' . PHP_EOL;
											} elseif ($product['ean']) {
											$output .= '  <g:gtin><![CDATA[' . trim($this->fixEncoding($product['ean'])) . ']]></g:gtin>' . PHP_EOL;
										}
									}
									
									if ($option_value['sku']){
										$output .= '  <g:mpn><![CDATA[' . trim($this->fixEncoding($option_value['sku'])) . ']]></g:mpn>' . PHP_EOL;
										} else {
										$output .= '  <g:mpn><![CDATA[' . $product['product_id'] . '-' . (int)$option['option_id'] . '-' . (int)$option_value['option_value_id'] . ']]></g:mpn>' . PHP_EOL;
									}
									
									if ($product['upc']) {
										$output .= '  <g:upc>' . trim($this->fixEncoding($product['upc'])) . '</g:upc>' . PHP_EOL;
									}
									
									$output .= '<g:model_number><![CDATA[' . trim($this->fixEncoding($product['model'])) . ']]></g:model_number>' . PHP_EOL;
									$output .= '<g:item_group_id><![CDATA[' . trim($product['product_id']) . ']]></g:item_group_id>' . PHP_EOL;
									
									$output .= '<g:brand><![CDATA[' . trim($this->fixEncoding($product['manufacturer'])) . ']]></g:brand>' . PHP_EOL;
									$output .= '<g:condition>new</g:condition>' . PHP_EOL;
									
									$output .= '  <g:price>' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false) . ' ' . $currency_code . '</g:price>' . PHP_EOL;
									
									if ((float)$product['special']) {
										$output .= '<g:sale_price_effective_date></g:sale_price_effective_date>' . PHP_EOL;
										$output .= '  <g:sale_price>' .  $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency_code, $currency_value, false) . ' ' . $currency_code . '</g:sale_price>' . PHP_EOL;
									}
									
									//Картинка
									if ($option_value['o_v_image'] && file_exists(DIR_IMAGE . $option_value['o_v_image'])){
										$output .= '  <g:image_link><![CDATA[' . trim($this->model_tool_image->resize($option_value['o_v_image'], $width, $height)) . ']]></g:image_link>' . PHP_EOL;
										} elseif ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
										$output .= '  <g:image_link><![CDATA[' . trim($this->model_tool_image->resize($product['image'], $width, $height)) . ']]></g:image_link>' . PHP_EOL;
										} else {
										$output .= '  <g:image_link></g:image_link>' . PHP_EOL;
									}
									
									//Если это цвет
									if (in_array($option['option_id'], $this->color_options_array)){
										$output .= '  <g:color><![CDATA[' . trim($this->fixEncoding($option_value['name'])) . ']]></g:color>' . PHP_EOL;
									}
									
									$output .= '<g:product_type><![CDATA[' . trim($this->fixEncoding($category_path)) . ']]></g:product_type>' . PHP_EOL;
									$output .= '<g:google_product_category>1</g:google_product_category>'. PHP_EOL;																	
									
									$output .= '  <g:quantity><![CDATA[' . $option_value['quantity'] . ']]></g:quantity>' . PHP_EOL;
									if ($product['weight']){
										$output .= '  <g:weight><![CDATA[' . $this->weight->format($product['weight'], $product['weight_class_id']) . ']]></g:weight>' . PHP_EOL;
									}
									$output .= '  <g:availability><![CDATA[' . ($option_value['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>' . PHP_EOL;
									$output .= '</item>';
									
								}
								
							}
						}
					}
				}
				
				$output .= '  </channel>';
				$output .= '</rss>';
				
				//	file_put_contents(DIR_FEEDS . 'google_merchant_extended_options_l' . $language_id . 's' . $store_id . '.xml' , $output);
				
				//$this->log->printr($reviews);
				$this->response->addHeader('Content-Type: application/xml');				
				$this->response->setOutput(trim($output));
				
			}
		}
	}																			