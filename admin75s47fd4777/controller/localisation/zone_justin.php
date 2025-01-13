<?php
error_reporting(1);
class ControllerLocalisationZoneJustin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/zone');

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();
		
		$data['update'] = $this->url->link('localisation/zone_justin/update', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/zone_justin', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['zones'] = array();

		$zone_total = $this->model_localisation_zone->getTotalZonesJustin();

		$results = $this->model_localisation_zone->getZonesJustin();

		foreach ($results as $result) {
			$data['zones'][] = array(
				'country' => $result['country'],
				'descr'    => $result['descr'],
				'code'    => $result['code']
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_country'] = $this->language->get('column_country');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_code'] = $this->language->get('column_code');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_country'] = $this->url->link('localisation/zone_justin', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url, true);
		$data['sort_name'] = $this->url->link('localisation/zone_justin', 'token=' . $this->session->data['token'] . '&sort=z.name' . $url, true);
		$data['sort_code'] = $this->url->link('localisation/zone_justin', 'token=' . $this->session->data['token'] . '&sort=z.code' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $zone_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/zone_justin', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($zone_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($zone_total - $this->config->get('config_limit_admin'))) ? $zone_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $zone_total, ceil($zone_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/zone_list_justin', $data));
	}
	
	public function update() {
		$this->load->model('localisation/zone');
		
		$this->db->query("TRUNCATE table oc_justin_areas_cities");
		$this->db->query("TRUNCATE table oc_justin_areas_region");
		$this->db->query("TRUNCATE table oc_justin_warehouses");
		
		$login = "Vest";
		$auth = sha1("hzOP1EhB:".date("Y-m-d")); //echo "hzOP1EhB:".date("Y-m-d").' - '.$auth;
		
			//Get areas
			$params = array(
				"keyAccount" => $login,
				"sign" => $auth,
				"request" 	=> "getData",
				"type" 		=> "catalog",
				"name" 		=> "cat_areasRegion",
				"language"	=> "UA",
			);	
			$requestBody = json_encode($params, true);
			
			$host = "https://api.justin.ua/justin_pms/hs/v2/runRequest";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);	
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
			$response = curl_exec($ch);		
			if($response){
				$return_arr = json_decode($response);
				foreach($return_arr->data as $area){
					$ret = $this->model_localisation_zone->addUpdateJustinArea($area->fields);
				}
			}
			
			//Get cities
			$params = array(
				"keyAccount" => $login,
				"sign" => $auth,
				"request" 	=> "getData",
				"type" 		=> "catalog",
				"name" 		=> "cat_Cities",
				"language"	=> "UA",
			);	
			$requestBody = json_encode($params, true);
			
			$host = "https://api.justin.ua/justin_pms/hs/v2/runRequest";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);	
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
			$response = curl_exec($ch);		
			if($response){
				$return_arr = json_decode($response);
				foreach($return_arr->data as $area){
					$ret = $this->model_localisation_zone->addUpdateJustinCity($area->fields);
				}
			}

			//Get warehouses
			$params = array(
				"keyAccount" => $login,
				"sign" => $auth,
				"request" 	=> "getData",
				"type" 		=> "request",
				"name" 		=> "req_DepartmentsLang",
				"language"	=> "UA",
				"params"	=> array("language" => "UA"),
				//"filter"	=> [array("name" => "region","comparison" => "equal","leftValue" => "90e2a0dc-dbfc-11e7-80c6-00155dfbfb00")],
			);	
			$requestBody = json_encode($params, true);
			$host = "https://api.justin.ua/justin_pms/hs/v2/runRequest";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);	
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
			$response = curl_exec($ch);		
			if($response){
				$return_arr = json_decode($response);				
				foreach($return_arr->data as $wh){
					echo $wh->uuid.' -- '.$wh->fields->city->uuid.' - '.$wh->fields->descr.' - '.$wh->fields->address.'<br>';
					$ret = $this->model_localisation_zone->addUpdateJustinWarehouse($wh->fields);
				}
			}
			
		
		/*$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/zone');		
		$this->getList();*/
	}	
}