<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ModelExtensionModuleOctAdvancedOptionsSettings extends Model {
    public function makeDB() {
        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oct_product_image_by_option` ( ";
        $sql .= "`product_id` int(11) NOT NULL, ";
        $sql .= "`product_image_id` int(11) NOT NULL, ";
        $sql .= "`option_value_id` int(11) NOT NULL ";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $this->db->query($sql);
        
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD `sku` text COLLATE 'utf8_general_ci' NOT NULL;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD `model` text COLLATE 'utf8_general_ci' NOT NULL;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD `o_v_image` varchar(255) DEFAULT NULL;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` ADD `sku` text COLLATE 'utf8_general_ci' NOT NULL;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` ADD `model` text COLLATE 'utf8_general_ci' NOT NULL;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` ADD `oct_quantity_value` text COLLATE 'utf8_general_ci' NOT NULL;");
    }
    
    public function removeDB() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "oct_product_image_by_option`;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` DROP COLUMN `sku`;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` DROP COLUMN `model`;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` DROP COLUMN `o_v_image`;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` DROP COLUMN `sku`;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` DROP COLUMN `model`;");
        $this->db->query("ALTER TABLE `" . DB_PREFIX . "order_option` DROP COLUMN `oct_quantity_value`;");
    }
}