<?php
	class ControllerHobotixHoboFAQ extends Controller {
		
		private function makeHref($href, $anchor){			
			return "<a href='$href' title='$anchor'>$anchor</a>";
		}
		
		private function makeProductTableLine($title, $product){
			return array(
			'title' => $title,
			'name' 	=> $product['name'],
			'price' => $product['special']?$this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']):$this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
			);
		}
		
		private function makeProductFaqQA($question, $answer, $products){
			
			$answer .= ' ';
			$counter = 0;
			foreach ($products as $product){
				if ($counter > 0){
					$answer .= ', ';
				}
				$answer .= $this->makeHref($this->url->link('product/product', 'product_id=' . $product['product_id']), $product['name']);
				$counter++;
			}
			
			return array(
			'question' 	=> $question,
			'answer'	=> $answer
			);
		}
		
		private function ocfilter($category_info){
			$ocfilter_page_info = $this->load->controller('extension/module/ocfilter/getPageInfo');
			
			if ($ocfilter_page_info && $ocfilter_page_info['name']){
			
					if (strpos(mb_strtolower($ocfilter_page_info['name']), mb_strtolower($category_info['name'])) === false){
					$category_info['name'] .= ' ' . trim($ocfilter_page_info['name']);
				} else {
					$category_info['name'] = $ocfilter_page_info['name'];
				}
				
				} else {
				
				$filter_title = $this->load->controller('extension/module/ocfilter/getSelectedsFilterTitleOnlyValue');
				
				$heading_title = $category_info['name'];
				
				if (false !== strpos($heading_title, '{filter}')) {
					$heading_title = trim(str_replace('{filter}', $filter_title, $heading_title));
					} else {
					if (false !== strpos($heading_title, $category_info['name'])){
						$heading_title = str_replace($category_info['name'], $category_info['name'] . ' ' . $filter_title, $heading_title);									
						} else {
						$heading_title .= ' ' . $filter_title;
					}
				}				
				$category_info['name'] = $heading_title;
			}
			
			return $category_info;
		}
		
		private function validateOCFilter(){
			
			//Задана страница
			if ($ocfilter_page_info = $this->load->controller('extension/module/ocfilter/getPageInfo')){
				return true;
			}
			
			//Фильтр первого уровня
			if (count(explode(';', $this->request->get['filter_ocfilter'])) == 1 && count($option_exploded = explode(':', $this->request->get['filter_ocfilter'])) == 2){
				
				return $this->model_hobotix_hobofaq->checkOCFilterOption($option_exploded[0]);
				
			}
			
			return false;
		}
		
		public function index(){						
			$this->load->model('hobotix/hobofaq');
			$this->load->model('catalog/product');
			$data = $this->load->language('hobotix/hobofaq');
			
			if (isset($this->request->get['route'])){	
				
				if (isset($this->request->get['page']) && $this->request->get['page'] > 1) {
					return '';
				}
				
				if ($this->request->get['route'] == 'product/category'){
									
					if (isset($this->request->get['filter_ocfilter']) && !$ocfilter = $this->validateOCFilter()) {					
						return '';
					}								
					
					$path = '';
					$parts = explode('_', (string)$this->request->get['path']);				
					$category_id = (int)array_pop($parts);
					
					if ($category_info = $this->model_catalog_category->getCategory($category_id)){
						$filter_data = array(
							'filter_category_id' => $category_id,
							'filter_ocfilter' => isset($this->request->get['filter_ocfilter'])?$this->request->get['filter_ocfilter']:null
						);
						
						$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
						
						if ($product_total <= 10){
							return '';
						}
						
						if ($ocfilter){
							$category_info = $this->ocfilter($category_info);
						}						
						
						if ($category_id == 62){
							return $this->load->controller('hobotix/hobofaq2');
						}
						
						$data['text_seo_price_table_header'] = sprintf($data['text_seo_price_table_header'], $category_info['name']);
						
						//Табличка с ценами
						$data['seo_table'] = array();
						
						//Самый дешевый
						if ($product = $this->model_hobotix_hobofaq->getCheapestProductsForCategory($category_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_cheapest'], $product);
						}
						
						//Самый дорогой
						if ($product = $this->model_hobotix_hobofaq->getExpensiveProductsForCategory($category_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_expensive'], $product);							
						}
						
						//Самый просматриваемый
						if ($product = $this->model_hobotix_hobofaq->getMostPopularProductsForCategory($category_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_popular'], $product);					
						}
						
						//Самый обсуждаемый
						if ($product = $this->model_hobotix_hobofaq->getMostReviewsProductsForCategory($category_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_reviewed'], $product);						
						}
						
						//Самый новый
						if ($product = $this->model_hobotix_hobofaq->getNewestProductsForCategory($category_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_newest'], $product);						
						}				
						
						/************************ FAQ *******************************/	
					/*	
						//Собственно FAQ
						if ($category_info['faq_name']){
							$data['faq_header'] = $category_info['faq_name'];						
							} else {
							$data['faq_header'] = sprintf($data['text_faq_header'], $category_info['name']);
						}
						
						$data['faq'] = array();
						
						//Самые просматриваемые
						if ($products = $this->model_hobotix_hobofaq->getMostPopularProductsForCategory($category_id, 3)){							
							$question = sprintf($data['text_faq_question_most_popular'], $category_info['name']);
							$answer = sprintf($data['text_faq_answer_most_popular'], $category_info['name'], $this->config->get('config_name'));
							$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);						
						}
						
						//Новинки
						if ($products = $this->model_hobotix_hobofaq->getNewestProductsForCategory($category_id, 3)){							
							$question = sprintf($data['text_faq_question_newest'], $category_info['name']);
							$answer = sprintf($data['text_faq_answer_newest'], $category_info['name'], $this->config->get('config_name')) ;
							$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);						
						}
						
						//Дешевые
						if ($products = $this->model_hobotix_hobofaq->getCheapestProductsForCategory($category_id, 3)){
							$question = sprintf($data['text_faq_question_cheapest'], $category_info['name']);
							$answer = sprintf($data['text_faq_answer_cheapest'], $category_info['name'], $this->config->get('config_name'));
						//	$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);
						}
						
						//Бестселлеры
						if ($products = $this->model_hobotix_hobofaq->getBestSellerProductsForCategory($category_id, 3)){
							$question = sprintf($data['text_faq_question_bestseller'], $category_info['name']);
							$answer = sprintf($data['text_faq_answer_bestseller'], $category_info['name'], $this->config->get('config_name'));
							$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);
						}
						
						//Как не платить за доставку
						$question = sprintf($data['text_faq_delivery_header'], $category_info['name']);
						$answer = sprintf($data['text_faq_delivery_text'], $this->url->link('information/information', 'information_id=6'));
						$data['faq'][] = array(
						'question' 	=> $question,
						'answer'	=> $answer					
						);
						
						
						
						//Ручной FAQ
						if (!$ocfilter){
							if ($handmade_faq = $this->model_hobotix_hobofaq->getCategoryFaq($category_id)){
								foreach ($handmade_faq as $handmade_qa){								
									$data['faq'][] = array(
									'question' 	=> $handmade_qa['question'],
									'answer'	=> $handmade_qa['answer']
									);								
								}							
							}
						}
					*/
					}
					
					
					} elseif ($this->request->get['route'] == 'product/manufacturer/info'){
									
					
					$manufacturer_id = $this->request->get['manufacturer_id'];
					if ($manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id)){
						$data['text_seo_price_table_header'] = sprintf($data['text_seo_price_manufacturer_table_header'], $manufacturer_info['name']);
						
						//Табличка с ценами
						$data['seo_table'] = array();
						
						//Самый дешевый
						if ($product = $this->model_hobotix_hobofaq->getCheapestProductsForManufacturer($manufacturer_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_cheapest'], $product);
						}
						
						//Самый дорогой
						if ($product = $this->model_hobotix_hobofaq->getExpensiveProductsForManufacturer($manufacturer_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_expensive'], $product);							
						}
						
						//Самый просматриваемый
						if ($product = $this->model_hobotix_hobofaq->getMostPopularProductsForManufacturer($manufacturer_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_popular'], $product);					
						}
						
						//Самый обсуждаемый
						if ($product = $this->model_hobotix_hobofaq->getMostReviewsProductsForManufacturer($manufacturer_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_reviewed'], $product);						
						}
						
						//Самый новый
						if ($product = $this->model_hobotix_hobofaq->getNewestProductsForManufacturer($manufacturer_id)){
							$data['seo_table'][] = $this->makeProductTableLine($data['text_seo_price_table_newest'], $product);						
						}
					
					/*
						//Собственно FAQ
						if ($manufacturer_info['faq_name']){
							$data['faq_header'] = $manufacturer_info['faq_name'];						
							} else {
							$data['faq_header'] = sprintf($data['text_faq_manufacturer_header'], $manufacturer_info['name']);
						}
						
						$data['faq'] = array();
						
						//Самые просматриваемые
						if ($products = $this->model_hobotix_hobofaq->getMostPopularProductsForManufacturer($manufacturer_id, 3)){							
							$question = sprintf($data['text_faq_manufacturer_question_most_popular'], $manufacturer_info['name']);
							$answer = sprintf($data['text_faq_manufacturer_answer_most_popular'], $manufacturer_info['name'], $this->config->get('config_name'));
							$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);						
						}
						
						//Новинки
						if ($products = $this->model_hobotix_hobofaq->getNewestProductsForManufacturer($manufacturer_id, 3)){							
							$question = sprintf($data['text_faq_manufacturer_question_newest'], $manufacturer_info['name']);
							$answer = sprintf($data['text_faq_manufacturer_answer_newest'], $manufacturer_info['name'], $this->config->get('config_name')) ;
							$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);						
						}
						
						//Дешевые
						if ($products = $this->model_hobotix_hobofaq->getNewestProductsForManufacturer($manufacturer_id, 3)){
							$question = sprintf($data['text_faq_manufacturer_question_cheapest'], $manufacturer_info['name']);
							$answer = sprintf($data['text_faq_manufacturer_answer_cheapest'], $manufacturer_info['name'], $this->config->get('config_name'));
							$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);
						}
						
						//Бестселлеры
						if ($products = $this->model_hobotix_hobofaq->getBestSellerProductsForManufacturer($manufacturer_id, 3)){
							$question = sprintf($data['text_faq_manufacturer_question_bestseller'], $manufacturer_info['name']);
							$answer = sprintf($data['text_faq_manufacturer_answer_bestseller'], $manufacturer_info['name'], $this->config->get('config_name'));
							$data['faq'][] = $this->makeProductFaqQA($question, $answer, $products);
						}
						
						//Как не платить за доставку
						$question = sprintf($data['text_faq_delivery_manufacturer_header'], $manufacturer_info['name']);
						$answer = sprintf($data['text_faq_delivery_manufacturer_text'], $this->url->link('information/information', 'information_id=6'));
						$data['faq'][] = array(
						'question' 	=> $question,
						'answer'	=> $answer					
						);
						
						//Ручной FAQ
						if ($handmade_faq = $this->model_hobotix_hobofaq->getManufacturerFaq($manufacturer_id)){
							foreach ($handmade_faq as $handmade_qa){								
								$data['faq'][] = array(
								'question' 	=> $handmade_qa['question'],
								'answer'	=> $handmade_qa['answer']
								);								
							}							
						}	
					*/
						
					}															
					} elseif ($this->request->get['route'] == 'product/product'){
					
					return '';
					
					$product_id = $this->request->get['product_id'];
					if ($product_info = $this->model_catalog_product->getProduct($product_id)){
						
						//Собственно FAQ
						if ($product_info['faq_name']){
							$data['faq_header'] = $product_info['faq_name'];						
							} else {
							$data['faq_header'] = sprintf($data['text_faq_product_header'], $product_info['name']);
						}
						
						//Ручной FAQ
						if ($handmade_faq = $this->model_hobotix_hobofaq->getProductFaq($product_id)){
							foreach ($handmade_faq as $handmade_qa){								
								$data['faq'][] = array(
								'question' 	=> $handmade_qa['question'],
								'answer'	=> $handmade_qa['answer']
								);								
							}							
						}		
					}
					}  elseif ($this->request->get['route'] == 'information/information'){
					$information_id = $this->request->get['information_id'];
					if ($information_info = $this->model_catalog_information->getInformation($information_id)){
						
						//Собственно FAQ
						if ($information_info['faq_name']){
							$data['faq_header'] = $information_info['faq_name'];						
							} else {
							$data['faq_header'] = sprintf($data['text_faq_information_header'], $information_info['title'], $this->config->get('config_name'));
						}
						
						//Ручной FAQ
						if ($handmade_faq = $this->model_hobotix_hobofaq->getInformationFaq($information_id)){
							foreach ($handmade_faq as $handmade_qa){								
								$data['faq'][] = array(
								'question' 	=> $handmade_qa['question'],
								'answer'	=> $handmade_qa['answer']
								);								
							}							
						}
					}
				}
				
				return $this->load->view('hobotix/hobofaq', $data);
				
				} else {
				
				return '';
				
			}
		}
	}
	
