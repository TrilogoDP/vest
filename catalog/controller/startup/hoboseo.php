<?php
	
	class ControllerStartupHoboSeo extends Controller {
		public function index() {
			
			if (isset($this->request->get['coupon']) && $this->request->get['coupon']){
				$this->session->data['coupon'] = $this->db->escape($this->request->get['coupon']);
			}
			
			//noindex, nofollow
			
			$new_page = false;
			
			//ONLY uk-ua			
			
			if (strpos($this->request->server['REQUEST_URI'], '&amp;')){
				$this->request->server['REQUEST_URI'] = str_replace('&amp;', '&', $this->request->server['REQUEST_URI']);
			}
			
			if (isset($this->request->get['page']) && $this->request->get['page'] == 1){
				$new_page = str_replace('&amp;page=1', '', $this->request->server['REQUEST_URI']);
				$new_page = str_replace('&page=1', '', $new_page);
				$new_page = str_replace('?page=1', '', $new_page);								
				} elseif (isset($this->request->get['page']) && $this->request->get['page'] == '{page}'){
				$new_page = str_replace('&amp;page={page}', '', $this->request->server['REQUEST_URI']);
				$new_page = str_replace('&page={page}', '', $new_page);
				$new_page = str_replace('?page={page}', '', $new_page);								
				} elseif (isset($this->request->get['page']) && (int)$this->request->get['page'] <= 0){
				$new_page = str_replace('&amp;page=' . (int)$this->request->get['page'], '', $this->request->server['REQUEST_URI']);
				$new_page = str_replace('&page=' . (int)$this->request->get['page'], '', $new_page);
				$new_page = str_replace('?page=' . (int)$this->request->get['page'], '', $new_page);								
			}
			
			//ЛОГИКА КОРОТКИХ УРЛОВ
			$request_query = $this->request->server['REQUEST_URI'];
			if (strpos($request_query, '?')){
				$query_string = substr($request_query, (strpos($request_query, '?')+1));						
			}
			
			//удаляем query_string
			if (isset($query_string) && strlen($query_string)>0){
				$request_query = substr($request_query, 0, -1*(strlen($query_string)+1));						
			}
			
			//удаляем первый и последний слэш, если он есть	
			if (substr($request_query, -1) == '/') {
				$request_query = substr($request_query, 0, -1);
			}
			
			if (strlen($request_query)>0 && $request_query[0] == '/') {						
				$request_query = substr($request_query, 1);
			}
			
			if (mb_strlen($request_query) > 3){								
				if ($alias = $this->shortAlias->getURL($request_query, true)) {																		
					$new_page = str_replace('&amp;', '&', $alias);	
				}
			}
			
			if ($new_page) {										
				$this->response->redirect($new_page, 301);
			}
			
		}
		
	}								