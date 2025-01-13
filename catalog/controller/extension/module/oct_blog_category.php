<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleoctBlogCategory extends Controller {
    public function index($setting) {
        $this->load->language('extension/module/oct_blog_category');
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $this->load->model('octemplates/blog_category');
        $this->load->model('octemplates/blog_article');
        
        if (isset($this->request->get['cpath'])) {
            $parts = explode('_', (string) $this->request->get['cpath']);
        } else {
            $parts = array();
        }
        
        if (isset($parts[0])) {
            $data['oct_blog_category_id'] = $parts[0];
        } else {
            $data['oct_blog_category_id'] = 0;
        }
        
        if (isset($parts[1])) {
            $data['oct_blog_category_child_id'] = $parts[1];
        } else {
            $data['oct_blog_category_child_id'] = 0;
        }
        
        $data['categories'] = array();
        
        $this->load->model('tool/image');
        
        $categories = $this->model_octemplates_blog_category->getCategories();
        
        foreach ($categories as $category) {
            $children_data = array();
            
            if ($category['oct_blog_category_id'] == $data['oct_blog_category_id']) {
                $children = $this->model_octemplates_blog_category->getCategories($category['oct_blog_category_id']);
                
                foreach ($children as $child) {
                    $filter_data_child = array(
                        'filter_category_id' => $child['oct_blog_category_id'],
                        'filter_sub_category' => false
                    );
                    
                    if ($child['image']) {
                        $children_image = $this->model_tool_image->resize($child['image'], $setting['width'], $setting['height']);
                    } else {
                        $children_image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                    }
                    
                    $children_data[] = array(
                        'oct_blog_category_id' => $child['oct_blog_category_id'],
                        'name' => $child['name'] . ' (' . $this->model_octemplates_blog_article->getTotalArticles($filter_data_child) . ')',
                        'thumb' => ($setting['show_image']) ? $children_image : false,
                        'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $category['oct_blog_category_id'] . '_' . $child['oct_blog_category_id'])
                    );
                }
            }
            
            $filter_data = array(
                'filter_category_id' => $category['oct_blog_category_id'],
                'filter_sub_category' => false
            );
            
            if ($category['image']) {
                $image = $this->model_tool_image->resize($category['image'], $setting['width'], $setting['height']);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
            }
            
            $data['categories'][] = array(
                'oct_blog_category_id' => $category['oct_blog_category_id'],
                'name' => $category['name'] . ' (' . $this->model_octemplates_blog_article->getTotalArticles($filter_data) . ')',
                'children' => $children_data,
                'thumb' => ($setting['show_image']) ? $image : false,
                'href' => $this->url->link('octemplates/blog_category', 'cpath=' . $category['oct_blog_category_id'])
            );
        }
        
        return $this->load->view('extension/module/oct_blog_category', $data);
    }
}