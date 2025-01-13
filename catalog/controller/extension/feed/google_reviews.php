<?php
	error_reporting(1);
	ini_set('fastcgi_read_timeout', 6400);//***mf
	ini_set('proxy_read_timeout', 6400);//***mf
	
	class ControllerExtensionFeedGoogleReviews extends Controller {
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
		
		private function fixEncoding($string){
			$string=str_replace("&amp;lt;","&lt;",$string);
			$string=str_replace("&amp;gt;","&gt;",$string);
			$string=str_replace("&amp;quot;","&quot;",$string);
			$string=str_replace("&amp;amp;","&amp;",$string);
			return $string;
		}
		
		private function NormalizeTextForReview($text){
			
			return trim($this->strip_html_tags($this->fixEncoding($text)));
			
		}
		
		private function isRealNumeric($string){
			$string = trim(str_ireplace(' ', '', $string));
			$string = trim(str_ireplace('.', '', $string));
			$string = trim(str_ireplace('/', '', $string));
			$string = trim(str_ireplace('\\', '', $string));
			$string = trim(str_ireplace('-', '', $string));
			
			return is_numeric($string);
		}
		
		private function getProductGTIN($product){
			
			$gtin = false;
			if (!empty($product['ean'])){
				$gtin = trim($product['ean']);
			}
			
			return $gtin;
			
		}
		
		private function getProductMPN($product){
			
			$mpn = false;
			if (!empty($product['mpn'])){
				$mpn = trim($product['mpn']);
			}
			
			if (!empty($product['upc'])){
				$mpn = trim($product['upc']);
			}
			
			if (!$mpn && !empty($product['ean'])){
				$mpn = trim($product['ean']);
			}
			
			if (!$mpn && !empty($product['sku'])){
				$mpn = trim($product['sku']);
			}
			
			if (!$mpn && !empty($product['model'])){
				$mpn = trim($product['model']);
			}
			
			return $mpn;
		}
		
		private function getProductSKU($product){
			
			$sku = false;
			if (!empty($product['sku'])){
				$sku = trim($product['sku']);
			}
			
			if (!$sku && !empty($product['mpn'])){
				$sku = trim($product['mpn']);
			}
			
			if (!empty($product['upc'])){
				$sku = trim($product['upc']);
			}
			
			if (!$sku && !empty($product['ean'])){
				$sku = trim($product['ean']);
			}
			
			if (!$sku && !empty($product['model'])){
				$sku = trim($product['model']);
			}
			
			return $sku;
		}
		
		private function getProductOptionSKU($option){
			
			$sku = false;
			
			if (!empty($option['sku'])){
				$sku = trim($option['sku']);
			}
			
			if (!empty($option['model'])){
				$sku = trim($option['model']);
			}
			
			return $sku;
		}
		
		private function getProductOptionMPN($option){
			
			$mpn = false;
			
			if (!empty($option['model'])){
				$mpn = trim($option['model']);
			}
			
			return $mpn;
		}
		
		public function index() {
			
			die('MOVED TO CLI');
		
			if ($this->config->get('google_merchant_center_status')) {
				
				$this->load->model('catalog/category');
				$this->load->model('catalog/product');
				$this->load->model('feed/google_merchant_center');
				$this->load->model('tool/image');
				
				$currency_code = $this->config->get('config_currency');			
				$currency_value = $this->currency->getValue($currency_code);
				$language_id = $this->config->get('config_language_id');
				$store_id = $this->config->get('config_store_id');
				
				
				$output  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
				$output .= '<feed xmlns:vc="http://www.w3.org/2007/XMLSchema-versioning"
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xsi:noNamespaceSchemaLocation=
				"http://www.google.com/shopping/reviews/schema/product/2.2/product_reviews.xsd">
				' . PHP_EOL;
				$output .= '<version>2.2</version>' . PHP_EOL;
				$output .= '<aggregator>' . PHP_EOL;
				$output .= '	<name>' . $this->config->get('config_name') . '</name>' . PHP_EOL;
				$output .= '</aggregator>' . PHP_EOL;
				$output .= '<publisher>' . PHP_EOL;
				$output .= '	<name> ' . $this->config->get('config_name') . ' </name>' . PHP_EOL;
				$output .= '<favicon>' . HTTPS_SERVER . 'image' . $this->config->get('config_icon') . '</favicon> ' . PHP_EOL;
				$output .= '</publisher> ' . PHP_EOL;
				
				
				$reviews = $this->model_feed_google_merchant_center->getReviews();
				
				$output .= '<reviews>' . PHP_EOL;
				foreach ($reviews as $review){
					$product = $this->model_catalog_product->getProduct($review['product_id']);
					$options = $this->model_catalog_product->getProductOptions($review['product_id']);
					
					if ($product){
						$output .= '<review>' . PHP_EOL;
						$output .= '<review_id>' . $review['review_id'] . '</review_id>' . PHP_EOL;
						
						$output .= '<reviewer>' . PHP_EOL;
						if (empty($review['author'])){
							$output .= '<name is_anonymous="true">Anonymous</name>' . PHP_EOL;
							} else {
							$output .= '<name><![CDATA[' . $review['author'] . ']]></name>' . PHP_EOL;
						}
						$output .= '</reviewer>' . PHP_EOL;
						
						$output .= '<review_timestamp>' . date('Y-m-d\TH:i:sP', strtotime($review['date_added'])) . '</review_timestamp>' . PHP_EOL;
						
						//	$output .= ' <title>Excellent</title>'  . PHP_EOL;
						
						$output .= '  <content><![CDATA[' . $this->NormalizeTextForReview($review['text']) . ']]></content>'  . PHP_EOL;
						
						if (!empty($review['positive_text'])){
							$pros = explode(PHP_EOL, $review['positive_text']);
							
							$do_pros = false;
							foreach ($pros as $pro){
								if (mb_strlen($this->NormalizeTextForReview($pro)) > 2){
									$do_pros = true;
									break;
								}
							}
							unset($pro);
							
							if ($do_pros) {
								if (is_array($pros)){
									$output .= '<pros>' . PHP_EOL;
									foreach ($pros as $pro){
										if (mb_strlen($this->NormalizeTextForReview($pro)) > 2){
											$output .= '<pro><![CDATA[' . $this->NormalizeTextForReview($pro) . ']]></pro>' . PHP_EOL;
										}
									}
									$output .= '</pros>' . PHP_EOL;
								}
							}
						}
						
						if (!empty($review['negative_text'])){
							$cons = explode(PHP_EOL, $review['negative_text']);
							
							if (is_array($cons)){
								
								$do_cons = false;
								foreach ($cons as $con){
									if (mb_strlen($this->NormalizeTextForReview($con)) > 2){
										$do_cons = true;
										break;
									}
								}
								unset($con);
								
								if ($do_cons) {
									$output .= '<cons>' . PHP_EOL;
									foreach ($cons as $con){
										if (mb_strlen($this->NormalizeTextForReview($con)) > 2){
											$output .= '<con><![CDATA[' . $this->NormalizeTextForReview($con) . ']]></con>' . PHP_EOL;
										}
									}
									$output .= '</cons>' . PHP_EOL;
								}
							}
						}
						
						$output .= '<review_url type="group">' . $this->url->link('product/product', 'product_id=' . $review['product_id']) . '</review_url>'  . PHP_EOL;
						
						//RATING
						$output .= '<ratings>' . PHP_EOL;
						$output .= '	<overall min="1" max="5">' . $review['rating'] . '</overall>' . PHP_EOL;					
						$output .= '</ratings>' . PHP_EOL;
						
						//PRODUCT
						$output .= '<products>' . PHP_EOL;
						$output .= '	<product>' . PHP_EOL;
						$output .= '	<product_ids>' . PHP_EOL;
						
						if ($this->getProductGTIN($product)){
							$output .= '	<gtins>' . PHP_EOL;
							$output .= '		<gtin><![CDATA[' . $this->NormalizeTextForReview($this->getProductGTIN($product)) . ']]></gtin>' . PHP_EOL;
							$output .= '	</gtins>' . PHP_EOL;
						} 
						
						$output .= '	<mpns>' . PHP_EOL;
						if ($this->getProductMPN($product)){
							$output .= '		<mpn><![CDATA[' . $this->NormalizeTextForReview($this->getProductMPN($product)) . ']]></mpn>' . PHP_EOL;							
						}
						$output .= '	<mpn><![CDATA[' . $this->NormalizeTextForReview($product['product_id']) . ']]></mpn>' . PHP_EOL;		
						$output .= '	</mpns>' . PHP_EOL;
						
						$output .= '<skus>' . PHP_EOL;
						if ($this->getProductSKU($product)){
							$output .= '	<sku><![CDATA[' . $this->NormalizeTextForReview($this->getProductSKU($product)) . ']]></sku>' . PHP_EOL;									
						}
						$output .= '	<sku><![CDATA[' . $this->NormalizeTextForReview($product['product_id']) . ']]></sku>' . PHP_EOL;				
						$output .= '</skus>' . PHP_EOL;
						
						if (!empty($product['manufacturer'])){
							$output .= '<brands>' . PHP_EOL;
							$output .= '	<brand><![CDATA[' . $this->NormalizeTextForReview($product['manufacturer']) . ']]></brand>' . PHP_EOL;
							$output .= '</brands>' . PHP_EOL;
						}
						
						$output .= '</product_ids>' . PHP_EOL;
						$output .= '<product_name><![CDATA[' . $this->NormalizeTextForReview($product['name']) . ']]></product_name>' . PHP_EOL;
						$output .= '<product_url><![CDATA[' .  $this->url->link('product/product', 'product_id=' . $review['product_id']) . ']]></product_url>' . PHP_EOL;
						$output .= '</product>' . PHP_EOL;
						
						//ADDING OPTIONS
						if ($options){
							foreach ($options as $option){															
								//			$this->log->printr($option);
								if (!empty($option['product_option_value'])){
									foreach ($option['product_option_value'] as $option_value){
										
										$output .= '<product>' . PHP_EOL;
										$output .= '<product_ids>' . PHP_EOL;
										
										//GIVEN MPN
										$output .= '<mpns>' . PHP_EOL;
										if ($this->getProductOptionMPN($option_value) || $this->getProductOptionSKU($option_value)){
											
											if ($this->getProductOptionMPN($option_value)){
												$output .= '<mpn><![CDATA[' . $this->NormalizeTextForReview($this->getProductOptionMPN($option_value)) . ']]></mpn>' . PHP_EOL;
											}
											
											if ($this->getProductOptionSKU($option_value)){										
												$output .= '<mpn><![CDATA[' . $this->NormalizeTextForReview($this->getProductOptionSKU($option_value)) . ']]></mpn>' . PHP_EOL;
											}																						
										}
										$output .= '<mpn><![CDATA[' . $product['product_id'] . '-' . (int)$option['option_id'] . '-' . (int)$option_value['option_value_id'] . ']]></mpn>' . PHP_EOL;
										$output .= '</mpns>' . PHP_EOL;
										
										
										$output .= '<skus>' . PHP_EOL;
										//PID - OID - OVID
										$output .= '<sku><![CDATA[' . $product['product_id'] . '-' . (int)$option['option_id'] . '-' . (int)$option_value['option_value_id'] . ']]></sku>' . PHP_EOL;
										//PID - POVID
										$output .= '<sku><![CDATA[' . $product['product_id'] . '-' . (int)$option_value['product_option_value_id'] . ']]></sku>' . PHP_EOL;									
										//GIVEN SKU FOR OPTION
										if ($this->getProductOptionSKU($option_value)){										
											$output .= '<sku><![CDATA[' . $this->NormalizeTextForReview($this->getProductOptionSKU($option_value)) . ']]></sku>' . PHP_EOL;
										}
										//PRODUCT SKU
										if ($this->isRealNumeric($this->getProductSKU($product))){
											$output .= '<sku><![CDATA[' . $this->NormalizeTextForReview($this->getProductSKU($product)) . ']]></sku>' . PHP_EOL;
										}										
										$output .= '</skus>' . PHP_EOL;
										
										if (!empty($product['manufacturer'])){
											$output .= '<brands>' . PHP_EOL;
											$output .= '	<brand><![CDATA[' . $this->NormalizeTextForReview($product['manufacturer']) . ']]></brand>' . PHP_EOL;
											$output .= '</brands>' . PHP_EOL;
										}
										
										$output .= '</product_ids>' . PHP_EOL;
										$output .= '<product_name><![CDATA[' . $this->NormalizeTextForReview($product['name'] . ' ' .  mb_strtolower($option_value['name'])) . ']]></product_name>' . PHP_EOL;
										$output .= '<product_url><![CDATA[' .  $this->url->link('product/product', 'product_id=' . $review['product_id'] . '&option_id=' . (int)$option_value['product_option_value_id']) . ']]></product_url>' . PHP_EOL;
										$output .= '</product>' . PHP_EOL;	
										
									}
									unset($option_value);
								}
							}							
						}						
						
						$output .= '</products>' . PHP_EOL;
						
						$output .= '<is_spam>false</is_spam>' . PHP_EOL;
						$output .= '<collection_method>post_fulfillment</collection_method>' . PHP_EOL;
						
						$output .= '</review>' . PHP_EOL;
					}
				}
				$output .= '</reviews>' . PHP_EOL;
				
				$output .= '</feed>';
				
			//	file_put_contents(DIR_FEEDS . 'google_reviews_l' . $language_id . 's' . $store_id . '.xml' , $output);
				
				//$this->log->printr($reviews);
				$this->response->addHeader('Content-Type: application/xml');
				//	$this->response->addHeader('Content-Disposition: attachment; filename="google_merchant_center.xml"');
				$this->response->setOutput(trim($output));
			}
		}
	}																		