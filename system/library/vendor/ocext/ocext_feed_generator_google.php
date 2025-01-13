<?php
class ocextFeedGeneratorGoogle {
    private $data = array();
    private $path_oc_version;
    private $language;
    private $db;
    private $settings = array(
        'edition'=>array(
            'version_host'=>'manyfeed-google-merchant.ocext',
            'extension'=>'manyfeed_pe',
            'version'=>'5.0.0.0'
        ),
        'functional'=>array(
            'template_engine'=>1,
            'installment'=>1,
            'tax'=>1,
            'loyalty_points'=>1,
            'filter_columns'=>1,
            'find_replace'=>1,
            'multi_store'=>1,
	    'review'=>1,
        ),
    );

    public function __construct($registry,$path_oc_version,$language,$load,$db) {
        $this->registry = $registry;
        $this->language = $language;
        $this->db = $db;
        $this->load = $load;
        $this->path_oc_version = $path_oc_version;
        $this->setSetings();
    }
    
    public function setSetings() {
        foreach ($this->settings as $key => $value) {
            $this->data[$key] = $value;
        }
    }
    
    public function get($key) {
            return (isset($this->data[$key]) ? $this->data[$key] : null);
    }
    
    public function getData() {
            return $this->data;
    }

    public function set($key, $value) {
            $this->data[$key] = $value;
    }
    
    public function getSqlWhereOperators() {
        $operators = array('&lt;'=>'&lt;','≤'=>'≤','='=>'=','≥'=>'≥','&gt;'=>'&gt;','≠'=>'≠','±'=>'±','like_left'=>'Contain left','like_right'=>'Contain right','like'=>'Contain','not_like_left'=>'Does not contain left' ,'not_like_right'=>'Does not contain right','not_like'=>'Does not contain');
        return $operators;
    }
    public function getStringWhereOperators() {
        $operators = array('&lt;'=>'&lt;','≤'=>'≤','='=>'=','≥'=>'≥','&gt;'=>'&gt;','≠'=>'≠','±'=>'±','like'=>'contains','not_like'=>'Does not contain');
        return $operators;
    }
    
    public function getSqlWhereLogic() {
        $operators = array('OR'=>'OR','AND'=>'AND');
        return $operators;
    }
    
    public function getLimitProducts() {
        $limit_products = array(1=>1000,2=>2000,5=>5000,10=>10000,20=>20000,50=>50000,100=>100000);
        return $limit_products;
    }
    
    public function getCurrencies() {
        $currency_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

        foreach ($query->rows as $result) {
                $currency_data[$result['code']] = array(
                        'currency_id'   => $result['currency_id'],
                        'title'         => $result['title'],
                        'code'          => $result['code'],
                        'symbol_left'   => $result['symbol_left'],
                        'symbol_right'  => $result['symbol_right'],
                        'decimal_place' => $result['decimal_place'],
                        'value'         => $result['value'],
                        'status'        => $result['status'],
                        'date_modified' => $result['date_modified']
                );
        }
        $this->load->model('localisation/currency');
        
        return $currency_data;
    }
    
    public function getRulePictures() {
        $rule_pictures = array('by_wh_side','by_h_side','by_w_side','no_cache'); 
        return $rule_pictures;
    }
    
    public function getPartsSelect($basic_sql,$count_sql,$limit,$parts_select) {
        
        if($limit){
            
            $query = $this->db->query($count_sql);
            
                if($query->num_rows > ($limit*1000)){

                    $count_parts = ceil($query->num_rows/($limit*1000));

                    $parts_select = array();

                    for($cpi=0;$cpi<$count_parts;$cpi++){

                        $parts_select[] = $basic_sql." LIMIT  ".($cpi*($limit*1000)).", ".($limit*1000) ;

                    }

            }
            
        }
        
        return $parts_select;
    }

    public function getSettingVersionSettings(){
        return $this->settings;
    }
    
    public function getAdvancedSettings($param,$setting_name){
        
        $methods = get_class_methods($this);
        
        $result = '';
        
        if(in_array('get_'.$setting_name.'_advanced_settings_veiw', $methods)){
            
            $result = $this->{'get_'.$setting_name.'_advanced_settings_veiw'}($param);
            
        }
        
        return $result;
        
    }
    
    public function getProductColumns() {
        
        $result = array();
        
        $columns = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . 'product` ' );
        
        foreach ($columns->rows as $column) {
            
            $result[$column['Field']] = $column['Field'];
            
        }
        
        return $result;
        
    }
    
    public function getProductOptionValueColumns() {
        
        $result = array();
        
        $columns = $this->db->query('SHOW COLUMNS FROM `' . DB_PREFIX . 'product_option_value` ' );
        
        foreach ($columns->rows as $column) {
            
            $result[$column['Field']] = $column['Field'];
            
        }
        
        return $result;
        
    }
    
    public function resizeImage($file,$w,$h,$d,$HTTP_SERVER) {
	
		if( is_file(DIR_IMAGE.$file) ){
			
			$extension = pathinfo(DIR_IMAGE.$file, PATHINFO_EXTENSION);
			$old_image = $file;
			$image_new = 'cache/' . utf8_substr($file, 0, utf8_strrpos($file, '.')) . '-' . (int)$w . 'x' . (int)$h . '.' . $extension;

			if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $image_new))) {
			
                                $path = '';

				$directories = explode('/', dirname(str_replace('../', '', $image_new)));

				foreach ($directories as $directory) {
					$path = $path . '/' . $directory;

					if (!is_dir(DIR_IMAGE . $path)) {
						@mkdir(DIR_IMAGE . $path, 0777);
					}
				}
                            
				$image = new Image(DIR_IMAGE.$file);
				$imagesize = getimagesize(DIR_IMAGE.$file);
				$lw = $imagesize[0];
				$lh = $imagesize[1];
				if($d=='w'){
					$ws = $w/$lw;
					$h = $ws*$lh;
				}elseif($d=='h'){
					$hs = $h/$lh;
					$w = $hs*$lw;
				}
				$image->resize($w, $h);
				$image->save(DIR_IMAGE . $image_new);
				$image_new = str_replace(' ', '%20', $HTTP_SERVER.'image/'.$image_new); 
				
                        }else{
                            
                            $image_new = str_replace(' ', '%20', $HTTP_SERVER.'image/'.$image_new); 
                            
                        }
			
		}else{
			
			$image_new = '';
			
		}
		
        return $image_new;
    }
    
    public function getStores() {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

            $store_data = $query->rows;

            return $store_data;
    }
    
    public function get_template_engine_advanced_settings_veiw($param){
        
        $data['setting'] = $param['template_setting']['setting'];
        $data['sample_setting_id'] = $param['sample_setting_id'];
        $data['setting_id'] = $param['setting_id'];
        $path_oc_version = $param['path_oc_version'];
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        $data['text_disable'] = $this->language->get('text_disable');
        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_setting_template_engine'] = $this->language->get('text_setting_template_engine');
        $data['text_setting_claen_descr_html'] = $this->language->get('text_setting_claen_descr_html');
        
        return $this->manyfeed_view('template_engine.tpl', $data);
        
    }
    
    public function get_installment_advanced_settings_veiw($param){
        
        $data['setting'] = $param['template_setting']['setting'];
        $data['sample_setting_id'] = $param['sample_setting_id'];
        $data['setting_id'] = $param['setting_id'];
        $path_oc_version = $param['path_oc_version'];
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        $data['text_disable'] = $this->language->get('text_disable');
        $data['text_enable'] = $this->language->get('text_enable');
        $data['text_product_ids_only'] = $this->language->get('text_product_ids_only');
        $data['text_manufacturer_ids_only'] = $this->language->get('text_manufacturer_ids_only');
        $data['text_category_ids_only'] = $this->language->get('text_category_ids_only');
        $data['text_setting_installment'] = $this->language->get('text_setting_installment');
        
        return $this->manyfeed_view('installment.tpl', $data);
        
    }
    
    public function get_tax_advanced_settings_veiw($param){
        
        $data['setting'] = $param['template_setting']['setting'];
        $data['sample_setting_id'] = $param['sample_setting_id'];
        $data['setting_id'] = $param['setting_id'];
        $path_oc_version = $param['path_oc_version'];
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        $data['text_disable'] = $this->language->get('text_disable');
        $data['text_enable'] = $this->language->get('text_enable');
       $data['text_setting_tax'] = $this->language->get('text_setting_tax');
        $data['text_product_ids_only'] = $this->language->get('text_product_ids_only');
        $data['text_manufacturer_ids_only'] = $this->language->get('text_manufacturer_ids_only');
        $data['text_category_ids_only'] = $this->language->get('text_category_ids_only');
        
        return $this->manyfeed_view('tax.tpl', $data);
        
    }
    
    public function get_loyalty_points_advanced_settings_veiw($param){
        
        $data['setting'] = $param['template_setting']['setting'];
        $data['sample_setting_id'] = $param['sample_setting_id'];
        $data['setting_id'] = $param['setting_id'];
        $path_oc_version = $param['path_oc_version'];
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        $data['text_disable'] = $this->language->get('text_disable');
        $data['text_enable'] = $this->language->get('text_enable');
       $data['text_setting_loyalty_points'] = $this->language->get('text_setting_loyalty_points');
        $data['text_product_ids_only'] = $this->language->get('text_product_ids_only');
        $data['text_manufacturer_ids_only'] = $this->language->get('text_manufacturer_ids_only');
        $data['text_category_ids_only'] = $this->language->get('text_category_ids_only');
        
        return $this->manyfeed_view('loyalty_points.tpl', $data);
        
    }
    
    public function get_review_advanced_settings_veiw($param){
        
        $path_oc_version = $param['path_oc_version'];
        
        $path_on_model = $param['path_on_model'];
        
        $filter_data_group_id = $param['filter_data_group_id'];
        
        $loader = $this->registry->get('load');
			
        $loader->model($path_on_model.'/ocext_feed_generator_google');
        
        $model = $this->registry->get('model_'.$path_on_model.'_ocext_feed_generator_google');
        
        $data['ym_review'] = $model->getFilterData('ocext_feed_generator_google_ym_review',FALSE,$filter_data_group_id);
        
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        
        $data['text_disable'] = $this->language->get('text_disable');
        
        $data['text_review_deleted_reviews'] = $this->language->get('text_review_deleted_reviews');
	
	$data['text_review_reviews_by_review_id'] = $this->language->get('text_review_reviews_by_review_id');
	
	$data['text_review_reviews_by_product_id'] = $this->language->get('text_review_reviews_by_product_id');
	
	$data['text_review_skip_reviews_by_product_id'] = $this->language->get('text_review_skip_reviews_by_product_id');
	
	$data['text_review_skip_reviews_by_review_id'] = $this->language->get('text_review_skip_reviews_by_review_id');
	
	$data['text_review_min_rating'] = $this->language->get('text_review_min_rating');
	
	$data['text_review_min_date'] = $this->language->get('text_review_min_date');
	
	$data['text_review_aggregator_name'] = $this->language->get('text_review_aggregator_name');
	
	$data['text_review_publisher_name'] = $this->language->get('text_review_publisher_name');
	
	$data['text_review_publisher_favicon'] = $this->language->get('text_review_publisher_favicon');
	
	$data['text_link_on_manual'] = $this->language->get('text_link_on_manual');
	
	
	
	
	
	
	
        
        return $this->manyfeed_view('review.tpl', $data);
        
    }
    
    public function get_filter_columns_advanced_settings_veiw($param){
        
        $path_oc_version = $param['path_oc_version'];
        
        $path_on_model = $param['path_on_model'];
        
        $filter_data_group_id = $param['filter_data_group_id'];
        
        $loader = $this->registry->get('load');
			
        $loader->model($path_on_model.'/ocext_feed_generator_google');
        
        $model = $this->registry->get('model_'.$path_on_model.'_ocext_feed_generator_google');
        
        $data['ym_columns'] = $model->getFilterData('ocext_feed_generator_google_ym_filter_columns',FALSE,$filter_data_group_id);
        
        $data['operators'] = $this->getSqlWhereOperators();
        
        $data['columns'] = $this->getProductColumns();
        
        $data['logics'] = $this->getSqlWhereLogic();
        
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        
        $data['text_disable'] = $this->language->get('text_disable');
        
        $data['text_enable'] = $this->language->get('text_enable');
        
        $data['text_p_column'] = $this->language->get('text_p_column');
        
        $data['text_p_operator'] = $this->language->get('text_p_operator');
        
        $data['text_p_value'] = $this->language->get('text_p_value');
        
        $data['text_p_logic'] = $this->language->get('text_p_logic');
        
        $data['text_p_set'] = $this->language->get('text_p_set');
        
        return $this->manyfeed_view('filter_columns.tpl', $data);
        
    }
    
    public function get_find_replace_advanced_settings_veiw($param){
        
        $path_oc_version = $param['path_oc_version'];
        
        $path_on_model = $param['path_on_model'];
        
        $filter_data_group_id = $param['filter_data_group_id'];
        
        $loader = $this->registry->get('load');
			
        $loader->model($path_on_model.'/ocext_feed_generator_google');
        
        $model = $this->registry->get('model_'.$path_on_model.'_ocext_feed_generator_google');
        
        $data['ym_find_replace'] = $model->getFilterData('ocext_feed_generator_google_ym_find_replace',FALSE,$filter_data_group_id);
        
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        
        $data['text_disable'] = $this->language->get('text_disable');
        
        $data['text_enable'] = $this->language->get('text_enable');
        
        $data['text_ym_filter_data_find_replace'] = $this->language->get('text_ym_filter_data_find_replace');
        
        $data['text_find'] = $this->language->get('text_find');
        
        $data['text_replace'] = $this->language->get('text_replace');
        
        return $this->manyfeed_view('find_replace.tpl', $data);
        
    }
    
    public function get_multi_store_advanced_settings_veiw($param){
        
        $path_oc_version = $param['path_oc_version'];
        
        $path_on_model = $param['path_on_model'];
        
        $filter_data_group_id = $param['filter_data_group_id'];
        
        $loader = $this->registry->get('load');
			
        $loader->model($path_on_model.'/ocext_feed_generator_google');
        
        $model = $this->registry->get('model_'.$path_on_model.'_ocext_feed_generator_google');
        
        $data['stores'] = $this->getStores();
        
        $data['ym_multi_store'] = $model->getFilterData('ocext_feed_generator_google_ym_multi_store',FALSE,$filter_data_group_id);
        
        $this->load->language($path_oc_version.'/ocext_feed_generator_google');
        
        $data['text_disable'] = $this->language->get('text_disable');
        
        $data['text_enable'] = $this->language->get('text_enable');
        
        $data['text_default'] = $this->language->get('text_default');
        
        return $this->manyfeed_view('multi_store.tpl', $data);
        
    }
    
    
    public function manyfeed_view($template_file,$data) {
        
        $file = DIR_SYSTEM.'library/vendor/ocext/manyfeed_view/' . $template_file;
        
        $output = '';

        if (is_file($file)) {
            
                extract($data);

                ob_start();

                require($file);

                $output = ob_get_clean();
                
        }
        
        return $output;
        
    }
    
}