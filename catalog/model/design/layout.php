<?php
class ModelDesignLayout extends Model {
	public function getLayout($route) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE '" . $this->db->escape($route) . "' LIKE CONCAT(route, '%') AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1");

        if (isset($query->row['layout_id'])) {
        	$query->row['layout_id'] = $this->seocmslib->sc_getLayout($query->row['layout_id']);
        }

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}
	
	public function getLayoutModules($layout_id, $position) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "' AND position = '" . $this->db->escape($position) . "' ORDER BY sort_order");

        if (isset($layout_id) && isset($position)) {
       		$query->rows = $this->seocmslib->sc_getLayoutModules($layout_id, $position, $query->rows);
        }

		return $query->rows;
	}
}