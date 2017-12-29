<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ModelCatalogUsedStaff extends Model {
    public function getUsedStaff($used_product_id) {
		$query = $this->db->query("SELECT DISTINCT *, upd.name AS name, up.image, up.price,  up.period, up.customer_id, up.sort_order FROM " . DB_PREFIX . "used_product up LEFT JOIN " . DB_PREFIX . "used_product_description upd ON (up.used_product_id = upd.used_product_id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = up.customer_id) WHERE up.used_product_id = '" . (int)$used_product_id . "' AND upd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND up.status = '1'");
//SELECT * FROM `oc_used_product` WHERE date_modified BETWEEN CURDATE() AND CURDATE() - INTERVAL 3 DAY  ****select * from oc_used_product where date_modified IN (select date(curdate()-3 ))
//SELECT DISTINCT *, upd.name AS name, up.image, up.price, up.period, up.customer_id, up.sort_order FROM oc_used_product up LEFT JOIN oc_used_product_description upd ON (up.used_product_id = upd.used_product_id) LEFT JOIN oc_customer c ON (c.customer_id = up.customer_id) WHERE up.used_product_id = '1' AND upd.language_id = '1' AND up.status = '1'              
		
                if ($query->num_rows) {
			return array(
				'used_product_id'  => $query->row['used_product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'model'            => $query->row['model'],
				'quantity'         => $query->row['quantity'],
				'image'            => $query->row['image'],
				'price'            => $query->row['price'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
                'customer_id'      => $query->row['customer_id'],
                'period'           => $query->row['period'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
                'firstname'        => $query->row['firstname'],
                'lastname'         => $query->row['lastname'],
				'email'            => $query->row['email'],
				'telephone'        => $query->row['telephone']
			);
		} else {
			return false;
		}
    }
    
    public function getUsedStaffs($data = array()) {
        //SELECT c.firstname, c.lastname, c.email, c.telephone, up.used_product_id, up.price FROM oc_used_product up LEFT JOIN oc_customer c ON (up.customer_id = c.customer_id) LEFT JOIN oc_used_product_description upd ON (up.used_product_id = upd.used_product_id) WHERE upd.language_id = '1' AND up.status = '1'
		$sql = "SELECT c.firstname, c.lastname, c.email, c.telephone, up.used_product_id, up.price";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "used_product_to_category up2c ON (cp.category_id = up2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "used_product_to_category up2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (up2c.used_product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "used_product up ON (pf.product_id = up.used_product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "used_product up ON (up2c.used_product_id = up.used_product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "used_product up";
		}

		$sql .= " LEFT JOIN " .DB_PREFIX. "customer c ON (up.customer_id = c.customer_id) LEFT JOIN " . DB_PREFIX . "used_product_description upd ON (up.used_product_id = upd.used_product_id) WHERE upd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND up.status = '1'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND up2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "upd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR upd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "upd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(up.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		$sql .= " GROUP BY up.used_product_id";

		$sort_data = array(
			'upd.name',
			'up.model',
			'up.quantity',
			'up.price',
			'up.sort_order',
			'up.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'upd.name' || $data['sort'] == 'up.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'up.price') {
				$sql .= " ORDER BY up.price";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY up.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(upd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(upd.name) ASC";
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

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['used_product_id']] = $this->getUsedStaff($result['used_product_id']);
		}

		return $product_data;
    }
    
    public function getUsedStaffImages($used_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "used_product_image WHERE used_product_id = '" . (int)$used_product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
    }
    
    public function getCategories($used_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "used_product_to_category WHERE used_product_id = '" . (int)$used_product_id . "'");

		return $query->rows;
    }
    
    //SELECT * FROM oc_product_category WHERE product_id = $used_product_id
    public function getTotalUsedStaffs($data = array()) {
		$sql = "SELECT COUNT(DISTINCT up.used_product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "used_product_to_category up2c ON (cp.category_id = up2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "used_product_to_category up2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (up2c.used_product_id = upf.used_product_id) LEFT JOIN " . DB_PREFIX . "used_product up ON (pf.used_product_id = up.used_product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "used_product up ON (up2c.used_product_id = up.used_product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "used_product up";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "used_product_description upd ON (up.used_product_id = upd.used_product_id)  WHERE upd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND up.status = '1'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND up2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "upd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR upd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "upd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(up.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
    }
    
    //ADD PRODUCT
    public function addUsedStaff($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "used_product SET customer_id = '" . (int)$data['customer_id'] . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', price = '" . (float)$data['price'] . "', status = '0', period = '" . (int)$data['period'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), date_modified = NOW()");

                //$this->log->write("INSERT INTO " . DB_PREFIX . "used_product SET customer_id = '" . (int)$data['customer_id'] . "', model = '" . $this->db->escape($data['model']) . "', quantity = '" . (int)$data['quantity'] . "', price = '" . (float)$data['price'] . "', status = '0', period = '" . (int)$data['period'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), date_modified = NOW()");
                
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
	
	public function getAutoCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if(isset($data['parent_id'])){
			$sql .= " AND c2.parent_id = " . $data['parent_id'];
		}
		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

		//SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' > ') AS name, c1.parent_id, c1.sort_order FROM oc_category_path cp LEFT JOIN oc_category c1 ON (cp.category_id = c1.category_id) LEFT JOIN oc_category c2 ON (cp.path_id = c2.category_id) LEFT JOIN oc_category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN oc_category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '1' AND cd2.language_id = '1' AND cd2.name LIKE '%Cust%' AND c2.parent_id = '59' AND c2.category_id = '59' GROUP BY cp.category_id ORDER BY name ASC LIMIT 0,5
		return $query->rows;
	}
}

