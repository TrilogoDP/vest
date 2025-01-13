<?php
	class ControllerHobotixSearch extends Controller {
		private $elasticSearch;
		
		public function __construct($registry){						
			parent::__construct($registry);
			
			$this->load->library('hobotix/ElasticSearch');
			$this->elasticSearch = new hobotix\ElasticSearch($registry);
			
			
		}
		
		public function clear(){
			if (!empty($this->request->post['id'])){
				$this->load->model('hobotix/search');
				$this->model_hobotix_search->clearSearchHistory($this->request->post['id']);
			}
		}	
		
		private function createData($hit, $field, $exact, $suggestLogic, $query, &$data){
			$href 		= '';
			$id 		= '';
			$idtype 	= '';
			$type 		= '';
			
			$name = $hit['_source'][$field];
			
			if ($exact && !empty($hit['highlight'][$field])){
				$name = $hit['highlight'][$field][0];
			}
			
			if (!empty($hit['_source']['product_id'])){
				
				$href 	= $this->url->link('product/product', 'product_id=' . $hit['_source']['product_id']);					
				$id 	= $hit['_source']['product_id'];
				$idtype = 'p' . $hit['_source']['product_id'];
				$type 	= 'p';
				
				
				} elseif ($hit['_source']['category_id'] && $hit['_source']['ocfilter_filter'] && $hit['_source']['ocfilter_page_id']) {

				$href = $this->url->link('product/category', 'path=' . $hit['_source']['category_id']);
				$href = rtrim($href, '/');
				$hit['_source']['ocfilter_page_keyword'] = trim($hit['_source']['ocfilter_page_keyword']);
				$hit['_source']['ocfilter_filter_params'] = trim($hit['_source']['ocfilter_filter_params']);
				
				if ($hit['_source']['ocfilter_page_keyword']) {
					$href .= '/' . $hit['_source']['ocfilter_page_keyword'];
					} else {
					$href .= '/' . $hit['_source']['ocfilter_filter_params'];
				}
				
				$href 	= $href;
				$id 	= $hit['_source']['ocfilter_page_id'];
				$idtype = 'ocfp' . $hit['_source']['ocfilter_page_id'];
				$type 	= 'ocfp';
				
				} elseif ($hit['_source']['category_id'] && !$hit['_source']['ocfilter_filter'] && !$hit['_source']['ocfilter_page_id']) {
				
				$href 	= $this->url->link('product/category', 'path=' . $hit['_source']['category_id']);
				$id 	= $hit['_source']['category_id'];
				$idtype = 'c' . $hit['_source']['category_id'];
				$type 	= 'c';
				
			}		
			
			$name = $this->elasticSearch->checkUAName($name);
			
			if ($suggestLogic){
				$name 	= mb_strtolower($this->elasticSearch->checkUAName($hit['_source'][$field]));
				
				if ($query){
					$name = str_ireplace($query, '<b>' . $query . '</b>', $name);
				}
				
				$type 	= 's';
				$id 	= 's' . $hit['_id'];
				$idtype = 's' . $hit['_id'];
			}
			
			$data[$idtype] = array(
			'name' 		=> $name,
			'href' 		=> $href,
			'id'   		=> $id,
			'idtype'   	=> $idtype,	
			'type' 		=> $type
			);		

		}
		
		private function prepareResults($results, $field, $exact, $query = false){
			$this->load->model('catalog/manufacturer');
			$this->load->model('catalog/category');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			$data = array();
			
			if (!empty($results['suggest']['completition-suggestion']) && count($results['suggest']['completition-suggestion'][0]['options'])){
				
				foreach ($results['suggest']['completition-suggestion'][0]['options'] as $option){					
					$this->createData($option, $field, $exact, true, $query, $data);	
				}
				
			}
			
			foreach ($results['hits']['hits'] as $hit){				
				$this->createData($hit, $field, $exact, false, $query, $data);	
			}	
			
			$parsedData = ['p' => [], 'c' => [], 'ocfp' => [], 's' => [] ];
			
			foreach ($data as $result){
				
				if ($result['type'] == 'p'){
					
					$product = $this->model_catalog_product->getProduct($result['id']);
					if ($product){
						$parsedData['p'][$result['id']] = array(
						'id' 		=> $result['id'],
						'href' 		=> $result['href'],
						'name' 		=> $result['name'],
						'price' 	=> $this->currency->format($product['price'], $this->session->data['currency']),
						'saving'    => $product['special']?round((($product['price'] - $product['special'])/($product['price'] + 0.01))*100, 0):false,
						'special' 	=> $product['special']?$this->currency->format($product['special'], $this->session->data['currency']):false,
						'thumb'		=> $this->model_tool_image->resize($product['image'], 100, 100)
						);
					}
				}
				
				if ($result['type'] == 'c'){
					$parsedData['c'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);					
				}
				
				if ($result['type'] == 'ocfp'){
					$parsedData['ocfp'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);					
				}
				
				if ($result['type'] == 's'){
					
					$parsedData['s'][$result['id']] = array(
					'id' 		=> $result['id'],
					'href' 		=> $result['href'],
					'name' 		=> $result['name']
					);
					
				}
				
			}
			
			return $parsedData;
		}				
		
		public function index(){
			
			$query = $this->request->get['query'];
			$query = $this->elasticSearch->prepareQueryExceptions($query);
			$query = trim(mb_strtolower($query));	
			$length = mb_strlen($query);
						
			ini_set('display_errors', 'On');			
			
			$data['text_retranslate_search_nothing_found'] = $this->language->get('text_retranslate_search_nothing_found');
			
			if (!mb_strlen($query)){
				//display_history or popular searches
				$this->load->model('hobotix/search');
				$data['histories'] = array();
				
				$histories = $this->model_hobotix_search->getSearchHistory();
				if ($histories){
					foreach ($histories as $history){
						$data['histories'][] = $history;
					}
				}
				
				$data['populars'] = array();
				$populars = $this->model_hobotix_search->getPopularSearches();
				
				
				foreach ($populars as $popular){
					if (trim($popular['text'])){
						$data['populars'][] = array(
						'href' 		=> $this->url->link('product/search', 'search=' . trim($popular['text'])),
						'results'	=> $popular['results']?($popular['results'] . ' ' . morphos\Russian\NounPluralization::pluralize($popular['results'], $this->language->get('text_result_total_search'))):false,
						'text' 		=> trim($popular['text'])
						);				
					}
				}
			}
			
			try {
				
				if ($length <= 3){
					
					$field = $this->elasticSearch->buildField('name');
					$suggest = $this->elasticSearch->buildField('suggest');
					
					$results = $this->elasticSearch->completition('categories', $query, $suggest);
					$r1 = $this->prepareResults($results, $field, true, $query);
					
					} else {
					
					
					$field = 'names';
					$highlight = $this->elasticSearch->buildField('name');
					$suggest = $this->elasticSearch->buildField('suggest');
					
					//Самый первый запрос, просто поиск по названию, надеюсь в большинстве случаев его достаточно
					$filter_data = [];
					if ($this->registry->get('isMobile')){
						$filter_data['limit'] = 5;
					}

					$results = $this->elasticSearch->fuzzyCategories('categories', $query, $field, $highlight, $suggest, $filter_data);	
					$r1 = $this->prepareResults($results, $highlight, true, $query);

					
					$field1 = $this->elasticSearch->buildField('name');
					$highlight = $this->elasticSearch->buildField('name');
					$field2 = 'names';
					
					$resultsP = $this->elasticSearch->sku($query);
					
					if (!$this->elasticSearch->validateResult($resultsP)){
						$resultsP = $this->elasticSearch->fuzzyProducts('products', $query, $field1, $field2);		
					}									
					
					$r2 = $this->prepareResults($resultsP, $highlight, true, $query);
					
				}
				
				if (empty($r1['results'])){
					$r1['results'] = [];
				}
				
				if (empty($r2['results'])){
					$r2['results'] = [];
				}
				} catch ( Exception $e ) {
				$this->log->printr($e->getMessage());
				
			}
			
			$data['results'] = [];
			$data['results']['p'] = $data['results']['c'] = $data['results']['ocfp'] = $data['results']['s'] = [];
			
			foreach (['p', 'c', 'ocfp', 's'] as $idx){
				foreach ($r1[$idx] as $itr){
					$data['results'][$idx][$itr['id']] = $itr;
				}
				
				unset($itr);
				foreach ($r2[$idx] as $itr){
					$data['results'][$idx][$itr['id']] = $itr;
				}
				
				unset($itr);
			}
			
			$data['results_count'] = count($data['results']['p']) + count($data['results']['c']) + count($data['results']['ocfp'])  + count($data['results']['s']);
			
			$this->response->setOutput($this->load->view('structured/vest_search.tpl', $data));
		}
		
		public function test(){
			$product_id = $this->request->get['id'];
			
			$params = [
			'index' => 'products',
			'id'    => $product_id
			];			
			
			$response = $this->elasticSearch->elastic->get($params);
			$this->log->printr($response);
			$this->response->setOutput('');
			
		}

		public function indexertest(){
			$this->elasticSearch->indexertest();
		}
		
		public function indexer(){
			
			if(!is_cli()){
				die('cli only');	
			}
			
			ini_set('memory_limit', '2G');
			
		//	$this->elasticSearch->productsindexer();
			
			$this->elasticSearch->recreateIndices()->indexer()->productsindexer();															
			
		}
		
		
	}												