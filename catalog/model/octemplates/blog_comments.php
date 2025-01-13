<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ModelOctemplatesBlogComments extends Model {
    public function addComment($oct_blog_article_id, $data) {
        $oct_blog_setting_data = $this->config->get('oct_blog_setting_data');
        
        if ($oct_blog_setting_data['comment_moderation'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "oct_blog_comments SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int) $this->customer->getId() . "', oct_blog_article_id = '" . (int) $oct_blog_article_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int) $data['rating'] . "', status = '" . (int) $status . "', date_added = NOW()");
    }
    
    public function getCommentsByArticleId($oct_blog_article_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }
        
        if ($limit < 1) {
            $limit = 20;
        }
        
        $query = $this->db->query("SELECT c.oct_blog_comment_id, c.author, c.rating, c.text, c.plus, c.minus, a.oct_blog_article_id, ad.name, a.image, c.date_added FROM " . DB_PREFIX . "oct_blog_comments c LEFT JOIN " . DB_PREFIX . "oct_blog_article a ON (c.oct_blog_article_id = a.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_description ad ON (a.oct_blog_article_id = ad.oct_blog_article_id) WHERE a.oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND a.date_added <= NOW() AND a.status = '1' AND c.status = '1' AND ad.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY c.date_added DESC LIMIT " . (int) $start . "," . (int) $limit);
        
        return $query->rows;
    }
    
    public function getTotalCommentsByArticleId($oct_blog_article_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "oct_blog_comments c LEFT JOIN " . DB_PREFIX . "oct_blog_article a ON (c.oct_blog_article_id = a.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_description ad ON (a.oct_blog_article_id = ad.oct_blog_article_id) WHERE a.oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND a.date_added <= NOW() AND a.status = '1' AND c.status = '1' AND ad.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        
        return $query->row['total'];
    }
    
    public function updateLike($oct_blog_comment_id, $oct_blog_article_id, $type) {
        $sql = "UPDATE " . DB_PREFIX . "oct_blog_comments";
        
        if ($type == 'plus') {
            $sql .= " SET plus = (plus + 1)";
        }
        
        if ($type == 'minus') {
            $sql .= " SET minus = (minus + 1)";
        }
        
        $sql .= " WHERE oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND oct_blog_comment_id = '" . (int) $oct_blog_comment_id . "'";
        
        $this->db->query($sql);
    }
    
    public function addCommentLike($oct_blog_comment_id, $oct_blog_article_id, $data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "oct_blog_comments_like SET customer_ip = '" . $this->db->escape($data['customer_ip']) . "', customer_id = '" . $this->db->escape($data['customer_id']) . "', oct_blog_article_id = '" . (int) $oct_blog_article_id . "', oct_blog_comment_id = '" . (int) $oct_blog_comment_id . "'");
    }
    
    public function checkLike($oct_blog_comment_id, $oct_blog_article_id, $data) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_blog_comments_like WHERE oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND oct_blog_comment_id = '" . (int) $oct_blog_comment_id . "' AND coalesce(customer_ip = '" . $this->db->escape($data['customer_ip']) . "', customer_id = '" . $this->db->escape($data['customer_id']) . "')");
        
        return $query->rows;
    }
    
    public function getLikeCount($oct_blog_comment_id, $oct_blog_article_id, $type) {
        $query = $this->db->query("SELECT MAX(" . $type . ") as total FROM " . DB_PREFIX . "oct_blog_comments c LEFT JOIN " . DB_PREFIX . "oct_blog_article a ON (c.oct_blog_article_id = a.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_description ad ON (a.oct_blog_article_id = ad.oct_blog_article_id) WHERE a.oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND a.date_added <= NOW() AND a.status = '1' AND c.status = '1' AND c.oct_blog_comment_id = '" . (int) $oct_blog_comment_id . "' AND ad.language_id = '" . (int) $this->config->get('config_language_id') . "'");
        
        return $query->row['total'];
    }
}