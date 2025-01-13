<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleOctBlogArticle extends Controller {
    public function index($setting) {
        static $module = 0;
        
        $this->load->language('extension/module/oct_blog_article');
        
        $data['heading_title']   = $setting['heading'][$this->session->data['language']];
        $data['link']            = $setting['link'][$this->session->data['language']];
        $data['button_readmore'] = $this->language->get('button_readmore');
        
        $this->load->model('octemplates/blog_article');
        
        $data['position'] = $setting['position'];
        
        $data['articles'] = array();
        $filter_data      = array(
            'sort' => $setting['sort_order'],
            'limit' => $setting['limit'],
            'exclude' => isset($this->request->get['oct_blog_article_id'])?(int)$this->request->get['oct_blog_article_id']:false,
            'start' => 0,
            'filter_category_id' => ($setting['oct_blog']) ? implode(',', $setting['oct_blog']) : array()
        );
        
        $this->load->model('tool/image');
        
        $results = $this->model_octemplates_blog_article->getArticles($filter_data);
        
        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
            }
            
            $data['articles'][] = array(
                'name' => $result['name'],
                'date_added' => date("d.m.Y", strtotime($result['date_added'])),
                'thumb' => ($setting['show_image']) ? $image : false,
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $setting['desc_limit']) . '..',
                'href' => $this->url->link('octemplates/blog_article', 'oct_blog_article_id=' . $result['oct_blog_article_id'])
            );
        }
        
        $data['module'] = $module++;
        
        if ($data['articles']) {
            return $this->load->view('extension/module/oct_blog_article', $data);
        }
    }
}