<?php

class ModelExtensionFeedFeeds extends Model {


	public function getProduct($filter_data) {
	 
	    if ($filter_data['language_id']) {
		  $language = $filter_data['language_id'];
		} else {
		  $language = (int)$this->config->get('config_language_id');
		} 
	
 
		if ($filter_data['store_id']) {
		  $store_id = $filter_data['store_id'];
		} else {
		  $store_id = (int)$this->config->get('config_store_id');
		} 
  
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . $language . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . $language . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . $language . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$filter_data['product_id'] . "' AND pd.language_id = '" . $language . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . $store_id . "'");


		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => 'Tuygun Mobily',
				'currency'         => $this->config->get('config_currency'),
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'currency_id'      => $query->row['currency_id'],
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'] ? $query->row['points'] : '',
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => ($query->row['length'] > 0) ? $query->row['length'] : '',
				'width'            => ($query->row['width'] > 0) ? $query->row['width'] : '',
				'height'           => ($query->row['height'] > 0) ? $query->row['height'] : '',
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => $query->row['reviews'] ? round($query->row['rating']) : '',
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : '',
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {

		$sql = "SELECT p.product_id, pd.language_id, p2s.store_id FROM " . DB_PREFIX . "product p ";

		if (!empty($data['filter_category_id'])) {		
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";		
		}
 

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE  pd.language_id = '" . $data['language'] . "' AND p.status = '1' AND p.date_available <= NOW() 
		AND p2s.store_id = '" . $data['store'] . "'";

		if (!empty($data['filter_category_id'])) {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}


		$query = $this->db->query($sql);
 
	    $product_data = array();
 
 		foreach ($query->rows as $result) {
		
			$filter_data = array (
					'language_id'   => $result['language_id'],
					'product_id'    => $result['product_id'],
					'store_id'      => $result['store_id'],
		    );	
				
			$product_data[$result['product_id']] = $this->getProduct($filter_data);
		}

		return $product_data;
			
 
	}
 
	public function getProductAttributes($productAttributes) {
	
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$productAttributes['product_id'] . "' 
		AND agd.language_id = '" . (int)$productAttributes['language'] . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
		
			$product_attribute_data = array();

			
			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa 
			LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) 
			LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) 
			WHERE pa.product_id = '" . (int)$product_id . "' 
			AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' 
			AND ad.language_id = '" . $productAttributes['language'] . "' 
			AND pa.language_id = '" . $productAttributes['language'] . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($productOptions) {
	
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po 
		LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) 
		LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) 
		WHERE po.product_id = '" . (int)$productOptions['product_id'] . "' 
		AND od.language_id = '" . $productOptions['language'] . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov 
			LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) 
			LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 
			WHERE pov.product_id = '" . (int)$product_id . "' 
			AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' 
			AND ovd.language_id = '" . (int)$product_option['language_id'] . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductImages($product_id) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
  
	public function getCategories($product_id) {
	
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

    public function getFeedCategories($data) {

		$sql = "SELECT multifeed_category_id, 
		(SELECT name FROM `" . DB_PREFIX . "multifeed_category` gbc WHERE gbc.multifeed_category_id = gbc2c.multifeed_category_id 
		AND gbc.platform = '" . $data['platform'] . "') AS multifeed_category, category_id, 
		(SELECT name FROM `" . DB_PREFIX . "category_description` cd WHERE cd.category_id = gbc2c.category_id 
		AND cd.language_id = '" . $data['language'] . "') AS category FROM `" . DB_PREFIX . "multifeed_category_to_category` gbc2c
		 WHERE gbc2c.platform = '" . $data['platform'] . "' ORDER BY multifeed_category ASC";

        $query = $this->db->query($sql);

		return $query->rows;
    }

	public function getCategory($data) {
	
 		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c 
		LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
		WHERE c.category_id = '" . (int)$data['category_id'] . "' 
		AND cd.language_id = '" . (int)$data['language'] . "' 
		AND c2s.store_id = '" . (int)$data['store'] . "' 
		AND c.status = '1'");

		return $query->row;
	}
	
	
 
 }
