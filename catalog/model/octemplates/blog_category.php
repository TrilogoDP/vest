<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ModelOctemplatesBlogCategory extends Model {
	public function getCategory($oct_blog_category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "oct_blog_category c LEFT JOIN " . DB_PREFIX . "oct_blog_category_description cd ON (c.oct_blog_category_id = cd.oct_blog_category_id) LEFT JOIN " . DB_PREFIX . "oct_blog_category_to_store c2s ON (c.oct_blog_category_id = c2s.oct_blog_category_id) WHERE c.oct_blog_category_id = '" . (int)$oct_blog_category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}

	public function getCategories($oct_blog_category_parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_blog_category c LEFT JOIN " . DB_PREFIX . "oct_blog_category_description cd ON (c.oct_blog_category_id = cd.oct_blog_category_id) LEFT JOIN " . DB_PREFIX . "oct_blog_category_to_store c2s ON (c.oct_blog_category_id = c2s.oct_blog_category_id) WHERE c.oct_blog_category_parent_id = '" . (int)$oct_blog_category_parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}

	public function getCategoryLayoutId($oct_blog_category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_blog_category_to_layout WHERE oct_blog_category_id = '" . (int)$oct_blog_category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}
}