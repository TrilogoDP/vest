<?php
	class ControllerExtensionModuleSlideshow extends Controller {
		public function index($setting) {
			static $module = 0;		
			
			$this->load->model('design/banner');
			$this->load->model('tool/image');
			
			$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
			$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
			
			$data['banners'] = array();
			
			$results = $this->model_design_banner->getBanner($setting['banner_id']);
			$bannerSettings = $this->model_design_banner->getBannerSettings($setting['banner_id']);
			
			
			if(isset($this->request->get['path'])){				
				$cat_ids = explode("_",$this->request->get['path']);
				$cat_id = $cat_ids[count($cat_ids)-1];
				if(isset($cat_id) && $cat_id > 0){
					foreach ($results as $key => $value) {
						if((int)$value['category_id'] != (int)$cat_id){
							unset($results[$key]);
						}
					}
				}
			}
			
			if ($bannerSettings['hidefilter'] && isset($this->request->get['path']) && !empty($this->request->get['filter_ocfilter'])){
				return '';
			}
			
			foreach ($results as $result) {
				if (is_file(DIR_IMAGE . $result['image'])) {									
					$data['banners'][] = array(
					'title' 		=> $result['title'],
					'banner_analytics_id' => prepareBannerAnalitycsID($result['title']),
					'hidefilter' 	=> $bannerSettings['hidefilter'],
					'link'  		=> $result['link'],
					'image' 		=> $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),		
					);
				}
			}
			
			$data['hidefilter'] = $bannerSettings['hidefilter'];
			$data['module'] = $module++;
			
			return $this->load->view('extension/module/slideshow', $data);
		}
	}
