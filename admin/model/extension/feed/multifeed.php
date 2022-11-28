<?php


class ModelExtensionFeedMultifeed extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "multifeed_category` (
				`multifeed_category_id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL,
				`platform` varchar(65) NOT NULL,
				PRIMARY KEY (`multifeed_category_id`, `platform`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "multifeed_category_to_category` (
				`multifeed_category_id` INT(11) NOT NULL,
				`category_id` INT(11) NOT NULL,
				`platform` varchar(65) NOT NULL,
				PRIMARY KEY (`multifeed_category_id`, `category_id`, `platform`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}
	
	

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "multifeed_category`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "multifeed_category_to_category`");
	}
	
	





    public function import($string, $platform) {
 
	 	
	 switch ($platform) {
		  case 'Facebook':
			 return $this->importFacebookCategories($string);
		   break;
		  case 'Google':
		   return $this->importGoogleCategories($string);
		   break;	
		  default:
		   return false;
     }
	 
	 
      
   
   
    }
	
	public function importFacebookCategories ($string) {


       $this->db->query("DELETE FROM " . DB_PREFIX . "multifeed_category WHERE platform = 'Facebook'");

        $lines = explode("\n", $string);

 
        foreach ($lines as $line) {
		 
	            $part = explode(',', $line);
	            if (isset($part[1])) {
				
				    if ($part[0] != 'category_id' && $part[1] != 'category') {
					
	                $this->db->query("INSERT INTO " . DB_PREFIX . "multifeed_category SET multifeed_category_id = '" . (int)$part[0] . "', 
					name = '" . $this->db->escape($part[1]) . "', platform = 'Facebook'");
					
					}
					
	            }
		 
        }
   
    
	   
		
	}
	
	public function importGoogleCategories ($string) {

	 
       $this->db->query("DELETE FROM " . DB_PREFIX . "multifeed_category WHERE platform = 'Google'");

        $lines = explode("\n", $string);

        foreach ($lines as $line) {
			if (substr($line, 0, 1) != '#') {
	            $part = explode(' - ', $line, 2);
	            if (isset($part[1])) {
	                $this->db->query("INSERT INTO " . DB_PREFIX . "multifeed_category SET multifeed_category_id = '" . (int)$part[0] . "', 
					name = '" . $this->db->escape($part[1]) . "', platform = 'Google'");
	            }
			}
        }

	
		
	}
		
	
	 

    public function getMultiFeedsCategories($data = array()) {
       
	    $sql = "SELECT * FROM `" . DB_PREFIX . "multifeed_category` WHERE name LIKE '%" . $this->db->escape($data['filter_name']) . "%' 
		AND platform = '" . $data['category_feed'] . "' ORDER BY name ASC";
 
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

	public function addCategory($data) {
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "multifeed_category_to_category WHERE category_id = '" . (int)$data['category_id'] . "' 
		AND multifeed_category_id = '" . (int)$data['multifeed_category_id'] . "' AND platform = '" . $data['platform'] . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "multifeed_category_to_category SET multifeed_category_id = '" . (int)$data['multifeed_category_id'] . "', 
		category_id = '" . (int)$data['category_id'] . "', platform = '" . $data['platform'] . "'");
		
		
	}
	

	public function deleteCategory($data = array()) {
	
		$sql = "DELETE FROM " . DB_PREFIX . "multifeed_category_to_category 
		
		WHERE multifeed_category_id = '" . $data['multifeed_category_id'] . "' 
		AND category_id = '" . $data['category_id'] . "' 
		AND platform = '" . $data['platform'] . "'";
	
	 
	  $this->db->query($sql);
	  
	  
	}

    public function getCategories($data = array()) {
	
        $sql = "SELECT multifeed_category_id, platform,
		(SELECT name FROM `" . DB_PREFIX . "multifeed_category` gbc WHERE gbc.multifeed_category_id = gbc2c.multifeed_category_id AND gbc.platform = '" . $data['platform'] . "') 
		AS multifeed_category, category_id, 
		(SELECT name FROM `" . DB_PREFIX . "category_description` cd WHERE cd.category_id = gbc2c.category_id 
		AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS category 
		FROM `" . DB_PREFIX . "multifeed_category_to_category` gbc2c WHERE gbc2c.platform = '" . $data['platform'] . "' ORDER BY multifeed_category ASC";

 
 
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
 
 
		$query = $this->db->query($sql);

		return $query->rows;
    }

	public function getTotalCategories($data = array()) {
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "multifeed_category_to_category WHERE platform = '" . $data['platform'] . "'");

		return $query->row['total'];
    }
}
