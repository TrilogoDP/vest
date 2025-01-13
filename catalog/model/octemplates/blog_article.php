<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ModelOctemplatesBlogArticle extends Model {    
    public function updateViewed($oct_blog_article_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "oct_blog_article SET viewed = (viewed + 1) WHERE oct_blog_article_id = '" . (int) $oct_blog_article_id . "'");
    }

    public function getArticle($oct_blog_article_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "oct_blog_comments c1 WHERE c1.oct_blog_article_id = a.oct_blog_article_id AND c1.status = '1' GROUP BY c1.oct_blog_article_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "oct_blog_comments c2 WHERE c2.oct_blog_article_id = a.oct_blog_article_id AND c2.status = '1' GROUP BY c2.oct_blog_article_id) AS comments FROM " . DB_PREFIX . "oct_blog_article a LEFT JOIN " . DB_PREFIX . "oct_blog_article_description ad ON (a.oct_blog_article_id = ad.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_to_store a2s ON (a.oct_blog_article_id = a2s.oct_blog_article_id) WHERE a.oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND ad.language_id = '" . (int) $this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND a.status = '1'");

        return $query->row;
    }

    public function getArticles($data = array()) {
        $sql = "SELECT a.oct_blog_article_id, ad.name, a.date_added, a.image, a.viewed, ad.description, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "oct_blog_comments c1 WHERE c1.oct_blog_article_id = a.oct_blog_article_id AND c1.status = '1' GROUP BY c1.oct_blog_article_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "oct_blog_comments c2 WHERE c2.oct_blog_article_id = a.oct_blog_article_id AND c2.status = '1' GROUP BY c2.oct_blog_article_id) AS comments";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "oct_blog_category_path cp LEFT JOIN " . DB_PREFIX . "oct_blog_article_to_category a2c ON (cp.oct_blog_category_id = a2c.oct_blog_category_id)";
            } else {
                $sql .= " FROM " . DB_PREFIX . "oct_blog_article_to_category a2c";
            }

            $sql .= " LEFT JOIN " . DB_PREFIX . "oct_blog_article a ON (a2c.oct_blog_article_id = a.oct_blog_article_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "oct_blog_article a";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "oct_blog_article_description ad ON (a.oct_blog_article_id = ad.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_to_store a2s ON (a.oct_blog_article_id = a2s.oct_blog_article_id) WHERE ad.language_id = '" . (int) $this->config->get('config_language_id') . "' AND a.status = '1' AND a2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.oct_blog_category_path_id = '" . (int) $data['filter_category_id'] . "'";
            } else {
                $sql .= " AND a2c.oct_blog_category_id = '" . (int) $data['filter_category_id'] . "'";
            }
        }
        
        if (!empty($data['exclude'])) {
            $sql .= " AND a.oct_blog_article_id <> '" . (int) $data['exclude'] . "'";
        }

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "ad.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR ad.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $sql .= "ad.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
            }
            $sql .= ")";
        }

        $sql .= " GROUP BY a.oct_blog_article_id";

        if (isset($data['sort'])) {
            if ($data['sort'] == 'ad.name_asc') {
                $sql .= " ORDER BY LCASE(ad.name) ASC";
            } elseif ($data['sort'] == 'ad.name_desc') {
                $sql .= " ORDER BY LCASE(ad.name) DESC";
            } elseif ($data['sort'] == 'a.sort_order_asc') {
                $sql .= " ORDER BY a.sort_order ASC";
            } elseif ($data['sort'] == 'a.date_added_asc') {
                $sql .= " ORDER BY a.date_added ASC";
            } elseif ($data['sort'] == 'a.date_added_desc') {
                $sql .= " ORDER BY a.date_added DESC";
            } else {
                $sql .= " ORDER BY a.sort_order DESC";
            }
        } else {
            $sql .= " ORDER BY a.date_added DESC ";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $article_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $article_data[$result['oct_blog_article_id']] = $this->getArticle($result['oct_blog_article_id']);
        }

        return $article_data;
    }


    public function getTotalArticles($data = array()) {
        $sql = "SELECT COUNT(DISTINCT a.oct_blog_article_id) AS total";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "oct_blog_category_path cp LEFT JOIN " . DB_PREFIX . "oct_blog_article_to_category a2c ON (cp.oct_blog_category_id = a2c.oct_blog_category_id)";
            } else {
                $sql .= " FROM " . DB_PREFIX . "oct_blog_article_to_category a2c";
            }

            $sql .= " LEFT JOIN " . DB_PREFIX . "oct_blog_article a ON (a2c.oct_blog_article_id = a.oct_blog_article_id)";
        } else {
            $sql .= " FROM " . DB_PREFIX . "oct_blog_article a";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "oct_blog_article_description ad ON (a.oct_blog_article_id = ad.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_to_store a2s ON (a.oct_blog_article_id = a2s.oct_blog_article_id) WHERE ad.language_id = '" . (int) $this->config->get('config_language_id') . "' AND a.status = '1' AND a2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";

        if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND cp.oct_blog_category_path_id = '" . (int) $data['filter_category_id'] . "'";
            } else {
                $sql .= " AND a2c.oct_blog_category_id = '" . (int) $data['filter_category_id'] . "'";
            }
        }
        
        if (!empty($data['exclude'])) {
            $sql .= " AND a.oct_blog_article_id <> '" . (int) $data['exclude'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getArticlesLayoutId($oct_blog_article_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_blog_article_to_layout WHERE oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getArticleImages($oct_blog_article_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_blog_article_image WHERE oct_blog_article_id = '" . (int) $oct_blog_article_id . "' ORDER BY sort_order ASC");

        return $query->rows;
    }

    public function getArticleProductsRelated($oct_blog_article_id) {
        $article_data = array();

        $this->load->model('catalog/product');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oct_blog_article_products_related ar LEFT JOIN " . DB_PREFIX . "product p ON (ar.product_related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE ar.oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");

        if ($query->rows) {
            foreach ($query->rows as $result) {
                $article_data[$result['product_related_id']] = $this->model_catalog_product->getProduct($result['product_related_id']);
            }
        }

        return $article_data;
    }

    public function getArticleArticlesRelated($oct_blog_article_id) {
        $article_data = array();

        $query = $this->db->query("SELECT ar.* FROM " . DB_PREFIX . "oct_blog_article_articles_related ar LEFT JOIN " . DB_PREFIX . "oct_blog_article a ON (a.oct_blog_article_id = ar.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_description ad ON (a.oct_blog_article_id = ad.oct_blog_article_id) LEFT JOIN " . DB_PREFIX . "oct_blog_article_to_store a2s ON (a.oct_blog_article_id = a2s.oct_blog_article_id) WHERE ar.oct_blog_article_id = '" . (int) $oct_blog_article_id . "' AND ad.language_id = '" . (int) $this->config->get('config_language_id') . "' AND a2s.store_id = '" . (int) $this->config->get('config_store_id') . "' AND a.status = '1'");

        if ($query->rows) {
            foreach ($query->rows as $row) {
                $article_data[] = $row['article_related_id'];
            }
        }

        return $article_data;
    }
}
