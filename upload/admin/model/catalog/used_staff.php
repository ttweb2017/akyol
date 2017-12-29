<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ModelCatalogUsedStaff extends Model {
    public function addUsedStaff($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "used_product SET customer_id = '" . $this->db->escape($data['customer_id']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', price = '" . (float)$data['price'] . "', status = '" . (int)$data['status'] . "', period = '" . (int)$data['period'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), date_modified = NOW()");

		$used_product_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "used_product SET image = '" . $this->db->escape($data['image']) . "' WHERE used_product_id = '" . (int)$used_product_id . "'");
		}

		foreach ($data['used_product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "used_product_description SET used_product_id = '" . (int)$used_product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['used_product_image'])) {
			foreach ($data['used_product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "used_product_image SET used_product_id = '" . (int)$used_product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['used_product_category'])) {
			foreach ($data['used_product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "used_product_to_category SET used_product_id = '" . (int)$used_product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}


		$this->cache->delete('used_staff');

		return $used_product_id;
    }  
    
    public function editUsedStaff($used_product_id, $data) {
                //$this->log->write("UPDATE " . DB_PREFIX . "used_product SET customer_id = '" . $this->db->escape($data['customer_id']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', price = '" . (float)$data['price'] . "', status = '" . (int)$data['status'] . "', period = '" . (int)$data['period'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE used_product_id = '" . (int)$used_product_id . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "used_product SET customer_id = '" . $this->db->escape($data['customer_id']) . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', price = '" . (float)$data['price'] . "', status = '" . (int)$data['status'] . "', period = '" . (int)$data['period'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE used_product_id = '" . (int)$used_product_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "used_product SET image = '" . $this->db->escape($data['image']) . "' WHERE used_product_id = '" . (int)$used_product_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "used_product_description WHERE used_product_id = '" . (int)$used_product_id . "'");

		foreach ($data['used_product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "used_product_description SET used_product_id = '" . (int)$used_product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "used_product_image WHERE used_product_id = '" . (int)$used_product_id . "'");

		if (isset($data['used_product_image'])) {
			foreach ($data['used_product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "used_product_image SET used_product_id = '" . (int)$used_product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "used_product_to_category WHERE used_product_id = '" . (int)$used_product_id . "'");

		if (isset($data['used_product_category'])) {
			foreach ($data['used_product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "used_product_to_category SET used_product_id = '" . (int)$used_product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->cache->delete('used_staff');
    }
    
    public function deleteUsedStaff($used_product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "used_product WHERE used_product_id = '" . (int)$used_product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "used_product_description WHERE used_product_id = '" . (int)$used_product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "used_product_image WHERE used_product_id = '" . (int)$used_product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "used_product_to_category WHERE used_product_id = '" . (int)$used_product_id . "'");

		$this->cache->delete('used_product');
    }
    
    public function getUsedStaff($used_product_id) {
		$query = $this->db->query("SELECT DISTINCT *, up.status AS staff_status FROM " . DB_PREFIX . "used_product up LEFT JOIN ". DB_PREFIX . "customer c ON (up.customer_id = c.customer_id) LEFT JOIN " . DB_PREFIX . "used_product_description upd ON (up.used_product_id = upd.used_product_id) WHERE up.used_product_id = '" . (int)$used_product_id . "' AND upd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
    }
    
    public function getUsedStaffs($data = array()) {
		$sql = "SELECT *, up.status AS staff_status FROM " . DB_PREFIX . "used_product up LEFT JOIN ". DB_PREFIX . "customer c ON (up.customer_id = c.customer_id) LEFT JOIN " . DB_PREFIX . "used_product_description upd ON (up.used_product_id = upd.used_product_id) WHERE upd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND up.status = '" . (int)$data['filter_status'] . "'";
		}
		
		$sql .= " GROUP BY up.used_product_id";

		$sort_data = array(
			'upd.name',
			'up.model',
			'up.price',
			'up.quantity',
			'up.status',
			'up.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY upd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
    }
    
    public function getUsedStaffsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "used_product up LEFT JOIN ". DB_PREFIX . "customer c ON (up.customer_id = c.customer_id) LEFT JOIN " . DB_PREFIX . "used_product_description upd ON (up.used_product_id = upd.used_product_id) LEFT JOIN " . DB_PREFIX . "used_product_to_category up2c ON (up.used_product_id = up2c.used_product_id) WHERE upd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND up2c.category_id = '" . (int)$category_id . "' ORDER BY upd.name ASC");

		return $query->rows;
    }
    
    public function getUsedStaffDescriptions($used_product_id) {
		$used_product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "used_product_description WHERE used_product_id = '" . (int)$used_product_id . "'");

		foreach ($query->rows as $result) {
			$used_product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description']
			);
		}

		return $used_product_description_data;
    }
    
    public function getUsedStaffCategories($used_product_id) {
		$used_product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "used_product_to_category WHERE used_product_id = '" . (int)$used_product_id . "'");

		foreach ($query->rows as $result) {
			$used_product_category_data[] = $result['category_id'];
		}

		return $used_product_category_data;
    }
    
    public function getUsedStaffImages($used_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "used_product_image WHERE used_product_id = '" . (int)$used_product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
    }
    
    public function getTotalUsedStaffs($data = array()) {
		$sql = "SELECT COUNT(DISTINCT up.used_product_id) AS total FROM " . DB_PREFIX . "used_product up LEFT JOIN " . DB_PREFIX . "used_product_description upd ON (up.used_product_id = upd.used_product_id)";

		$sql .= " WHERE upd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND up.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
    }
}
