<?php 
class ControllerModuleGoogleRemarketing extends Controller { 
    private $error = array();
    
    private $data_to_view = array();
    
    public function __construct($registry) {
        parent::__construct($registry);

        $this->extension_name = 'google_remarketing';

        $this->data_to_view = array(
            'button_apply_allowed' => true,
            'button_save_allowed' => true,
            'extension_name' => $this->extension_name,
        );

        $this->extension_group_config = 'google_remarketing';
        $this->extension_id = '54206892-2c04-4d6d-b624-0994fa641b0a';

        //IMPORTANT - DEFINE TYPE EXTENSION - MODULE, PAYMENT, SHIPPING...
        $this->extension_type = 'module';
        $this->real_extension_type = version_compare(VERSION, '2.3.0.0', '>=') ? 'extension/'.$this->extension_type : $this->extension_type;

        $this->extension_url_cancel_oc_15x = 'extension/'.$this->extension_type;
        $this->extension_url_cancel_oc_20x = 'extension/'.$this->extension_type;
        $this->extension_url_cancel_oc_23x = 'extension/extension';
        $this->extension_url_after_save_oc_15x = 'extension/'.$this->extension_type;
        $this->extension_url_after_save_oc_20x = 'extension/'.$this->extension_type;
        $this->extension_url_after_save_oc_23x = 'extension/extension';
        $this->extension_url_after_save_error = 'extension/'.$this->extension_type.'/'.$this->extension_name;

        $loader = new Loader($registry);
        $loader->model('design/layout');
        $loader->model('devmanextensions/tools');
        $loader->model('localisation/language');
        $loader->language($this->extension_type.'/'.$this->extension_name);

        //Set layouts 
            $layouts_temp = $this->model_design_layout->getLayouts();
            $layouts = array();
            foreach ($layouts_temp as $key => $layout) {
                $layouts[$layout['layout_id']] = $layout['name'];
            }
            $this->layouts = $layouts;
        //END Set layouts

        //Opencart Quality Extensions - info@devmanextensions.com - 2016-10-09 19:32:04 - Load stores
            $this->stores = $this->model_devmanextensions_tools->getStores();
        //END

        //Opencart Quality Extensions - info@devmanextensions.com - 2016-10-09 19:39:52 - Load languages
            $this->languages = $this->model_localisation_language->getLanguages();
        //END

        //Set statuses
            $this->statuses = array(
                1 => $this->language->get('active'),
                0 => $this->language->get('disabled')
            );
        //END Set statuses

        //Set positions
            $this->positions = array(
                'content_top' => $this->language->get('text_content_top'),
                'content_bottom' => $this->language->get('text_content_bottom'),
                'column_left' => $this->language->get('text_column_left'),
                'column_right' => $this->language->get('text_column_right'),
            );
        //END Set positions
    }

    public function index() {
        //Load languages
            $this->load->language($this->extension_type.'/'.$this->extension_name);

        //Set document title
            $this->document->setTitle($this->language->get('heading_title'));

        //Add scripts and css
            if(version_compare(VERSION, '2.0.0.0', '<'))
            {
                $this->document->addScript('view/javascript/devmanextensions/bootstrap.min.js');
                $this->document->addStyle('view/stylesheet/devmanextensions/bootstrap.min.css');
            }
            
            $this->document->addStyle('view/stylesheet/devmanextensions/colpick.css');
            $this->document->addStyle('view/stylesheet/devmanextensions/bootstrap-select.min.css');
            $this->document->addScript('view/javascript/devmanextensions/colpick.js');
            $this->document->addScript('view/javascript/devmanextensions/bootstrap-select.min.js');
            $this->document->addScript('view/javascript/devmanextensions/tools.js');

            if(version_compare(VERSION, '2.0.0.0', '>='))
            {
                $this->document->addScript('view/javascript/devmanextensions/oc2x.js');
                $this->document->addStyle('view/stylesheet/devmanextensions/oc2x.css');
            }
            else
            {
                $this->document->addScript('view/javascript/devmanextensions/oc15x.js');
                $this->document->addStyle('view/stylesheet/devmanextensions/oc15x.css');
                $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
                $this->document->addStyle('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
            }
        //END Add scripts and css
            
        //Add custom js
            if(file_exists('view/javascript/devmanextensions_'.$this->extension_name.'.js'))
                $this->document->addScript('view/javascript/devmanextensions_'.$this->extension_name.'.js');

            if(version_compare(VERSION, '2.0.0.0', '>=') && file_exists('view/javascript/devmanextensions_'.$this->extension_name.'_oc2x.js'))
                $this->document->addScript('view/javascript/devmanextensions_'.$this->extension_name.'_oc2x.js');
            elseif(file_exists('view/javascript/devmanextensions_'.$this->extension_name.'_oc15x.js'))
                $this->document->addScript('view/javascript/devmanextensions_'.$this->extension_name.'_oc15x.js');

        //Add custom css
            if(file_exists('view/stylesheet/devmanextensions_'.$this->extension_name.'.css'))
                $this->document->addStyle('view/stylesheet/devmanextensions_'.$this->extension_name.'.css');

            if(version_compare(VERSION, '2.0.0.0', '>=') && file_exists('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc2x.css'))
                $this->document->addStyle('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc2x.css');
            elseif(file_exists('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc15x.css'))
                $this->document->addStyle('view/stylesheet/devmanextensions_'.$this->extension_name.'_oc15x.css');
            
        //Pressed save button
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $no_exit = !empty($this->request->post['no_exit']) ? 1 : 0;

                //Opencart Quality Extensions - info@devmanextensions.com - 2016-10-21 18:57:30 - Custom functions
                    if(!empty($this->request->post['force_function']) || !empty($this->request->get['force_function']))
                    {
                        $post_get = !empty($this->request->post['force_function']) ? 'post' : 'get';
                        $this->{$this->request->{$post_get}['force_function']}();
                    }
                //END
                
                unset($this->request->post['no_exit']);

                //Serialize multiples field from table inputs
                    foreach ($this->request->post as $input_name => $data_post) {
                        if(is_array($data_post) && isset($data_post['replace_by_number']))
                        {
                            unset($data_post['replace_by_number']);

                            if(empty($data_post))
                                $this->request->post[$input_name] = '';
                            else
                                $this->request->post[$input_name] = base64_encode(serialize(array_values($data_post)));
                        }
                    }
                //END Serialize multiples field from table inputs

                $error = $this->_test_before_save();

                if(!$error)
                {
                    $this->load->model('setting/setting');
                    $this->model_setting_setting->editSetting($this->extension_group_config, $this->request->post);   

                    if(!empty($no_exit))
                    {
                        $array_return = array(
                            'error' => false,
                            'message' => $this->language->get('text_success')
                        );
                        echo json_encode($array_return); die;
                    }
                    else
                        $this->session->data['success'] = $this->language->get('text_success');

                    $after_save_temp = version_compare(VERSION, '2.0.0.0', '>=') ? $this->extension_url_after_save_oc_20x : $this->extension_url_after_save_oc_15x;
                    $after_save_temp = version_compare(VERSION, '2.3.0.0', '>=') ? $this->extension_url_after_save_oc_23x : $after_save_temp;

                    if(version_compare(VERSION, '2.0.0.0', '>='))
                        $this->response->redirect($this->url->link($after_save_temp, 'token=' . $this->session->data['token'], 'SSL'));
                    else
                        $this->redirect($after_save_temp, 'token=' . $this->session->data['token'], 'SSL');
                }
                else
                {
                    if(!empty($no_exit))
                    {
                        $array_return = array(
                            'error' => true,
                            'message' => $error
                        );
                        echo json_encode($array_return); die;
                    }
                    else
                        $this->session->data['error'] = $error;

                    if(version_compare(VERSION, '2.0.0.0', '>='))
                        $this->response->redirect($this->url->link($this->extension_url_after_save_error, 'token=' . $this->session->data['token'], 'SSL'));
                    else
                        $this->redirect($this->extension_url_after_save_error, 'token=' . $this->session->data['token'], 'SSL');
                }       
            }
        //END Pressed save button

        //Opencart Quality Extensions - info@devmanextensions.com - 2016-10-21 18:57:30 - Custom functions
            if(!empty($this->request->post['force_function']) || !empty($this->request->get['force_function']))
            {
                $post_get = !empty($this->request->post['force_function']) ? 'post' : 'get';
                $this->{$this->request->{$post_get}['force_function']}();
            }
        //END
            
        //Send token to view
            $this->data_to_view['token'] = $this->session->data['token'];

        //Actions
            $this->data_to_view['action'] = $this->url->link($this->real_extension_type.'/'.$this->extension_name, 'token=' . $this->session->data['token'], 'SSL');
            $this->data_to_view['cancel'] = $this->url->link(version_compare(VERSION, '2.0.0.0', '>=') ? $this->extension_url_cancel_oc_20x : $this->extension_url_cancel_oc_15x, 'token=' . $this->session->data['token'], 'SSL');

        //Load extension languages
            $lang_array = array(
                'heading_title',
                'button_save',
                'button_cancel',
                'apply_changes',
                'text_image_manager',
                'text_browse',
                'text_clear'
            );

            foreach ($lang_array as $key => $value) {
                $this->data_to_view[$value] = $this->language->get($value);
            }
        //END Load extension languages

        //Construct view template form
            $form_view = $this->_contruct_view_form();
            $this->data_to_view['form_view'] = $form_view;

            //Load devmanextensions/tools.php model
                $this->load->model('devmanextensions/tools');

            //OC Versions compatibility
                $this->data_to_view['form'] = $this->model_devmanextensions_tools->generateForm($form_view);
        //END Construct view template form

        //Opencart Quality Extensions - info@devmanextensions.com - 2016-11-19 14:43:03 - Send custom variables to view
                $this->_send_custom_variables_to_view();
        //END

        $this->data_to_view['breadcrumbs'] = array();
        $this->data_to_view['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        if(!in_array($this->extension_type, array('tool')))
        {
            $this->data_to_view['breadcrumbs'][] = array(
                'text'      => $this->language->get('text_'.$this->extension_type),
                'href'      => $this->url->link($this->extension_url_cancel, 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            );
        }

        $this->data_to_view['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link($this->real_extension_type.'/'.$this->extension_name, 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        //OC Versions compatibility
            if(version_compare(VERSION, '2.0.0.0', '>='))
            {
                $data = $this->data_to_view;
                $data['header'] = $this->load->controller('common/header');
                $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');

                $this->response->setOutput($this->load->view($this->extension_type.'/'.$this->extension_name.'.tpl', $data));
            }
            else
            {
                $this->data = $this->data_to_view;
                $this->template = $this->extension_type.'/'.$this->extension_name.'.tpl';
                $this->children = array(
                    'common/header',
                    'common/footer'
                );

                $this->response->setOutput($this->render());
            }
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', $this->real_extension_type.'/'.$this->extension_name)) {
            if(!empty($this->request->post['no_exit']))
            {
                $array_return = array(
                    'error' => true,
                    'message' => $this->language->get('error_permission')
                );
                echo json_encode($array_return); die;
            }
            else
                $this->session->data['error'] = $this->language->get('error_permission');
            return false;
        }
        return true;
    }

    public function _contruct_view_form()
    {
        //Ecommerce place options
            $remarketing_types = array(
                $this->language->get('remarketing_dynamic'),
                $this->language->get('remarketing_standard'),
            );
        //END Ecommerce place options

        $form_view = array(
            'action' => $this->url->link($this->real_extension_type.'/'.$this->extension_name, 'token=' . $this->session->data['token'], 'SSL'),
            'id' => $this->extension_name,
            'extension_name' => $this->extension_name,
            'columns' => 1,
            'multi_store' => true,
            'tabs' => array(
                $this->language->get('tab_remarketing') => array(
                    'fields' => array(
                        array(
                            'text' => '<i class="fa fa-cog"></i>'.$this->language->get('configuration'),
                            'type' => 'legend',
                        ),

                        array(
                            'label' => $this->language->get('status'),
                            'type' => 'boolean',
                            'value' => $this->config->get('google_remarketing_status'),
                            'name' => 'status'
                        ),

                        array(
                            'label' => $this->language->get('type'),
                            'type' => 'select',
                            'value' => $this->config->get('google_remarketing_type'),
                            'name' => 'type',
                            'options' => $remarketing_types
                        ),

                        array(
                            'label' => $this->language->get('remarketing_id_preffix'),
                            'help' => $this->language->get('remarketing_id_preffix_help'),
                            'type' => 'text',
                            'value' => $this->config->get('google_remarketing_id_preffix'),
                            'name' => 'id_preffix',
                        ),

                        array(
                            'label' => $this->language->get('remarketing_id_suffix'),
                            'help' => $this->language->get('remarketing_id_suffix_help'),
                            'type' => 'text',
                            'value' => $this->config->get('google_remarketing_id_suffix'),
                            'name' => 'id_suffix',
                        ),

                        array(
                            'label' => $this->language->get('code'),
                            'help' => $this->language->get('google_remarketing_code_help'),
                            'type' => 'textarea',
                            'style' => 'height:200px;',
                            'value' => $this->config->get('google_remarketing_code'),
                            'name' => 'code'
                        ),

                        array(
                            'text' => '<i class="fa fa-info-circle"></i>'.$this->language->get('additional_information'),
                            'type' => 'legend',
                        ),  

                        array(
                            'type' => 'html_code',
                            'html_code' => $this->language->get('remarketing_ai_general').'<br><br>'.$this->language->get('remarketing_ai_other_sites'),
                        ),            
                    ),
                ),
                $this->language->get('tab_help') => array(
                    'form_colums' => 2,
                    'icon' => '<i class="fa fa-question-circle"></i>',
                    'custom_content' => '<iframe src="http://opencartqualityextensions.com/open_ticket" style="height:500px; width: 100%;"></iframe>'
                ),
                $this->language->get('tab_changelog') => array(
                    'form_colums' => 2,
                    'icon' => '<i class="fa fa-file-text"></i>',
                    'custom_content' => '<iframe src="http://opencartqualityextensions.com/changelogs/changelogs/get_changelogs/'.$this->extension_id.'" style="width: 100%; border:none;"></iframe>'
                ),
            )
        );
        return $form_view;
    }

    public function _send_custom_variables_to_view()
    {
        
    }

    public function install()
    {
        return true;
    }

    public function _test_before_save()
    {
        return false;
    }
}
?>