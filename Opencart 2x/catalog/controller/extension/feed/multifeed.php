<?php 

class ControllerExtensionFeedMultifeed extends Controller {
 
    public $server;
	
	public function index() {

	
	if ($this->request->server['HTTPS']) {
		$this->server = $this->config->get('config_ssl');
	} else {
		$this->server = $this->config->get('config_url');
	}	
 	
	$platform = $this->request->get['platform'];
		
	 switch ($platform) {
		  case 'Facebook':
			 return $this->facebookXML();
		   break;
		  case 'Google':
		   return $this->googleXML();
		   break;	
		  case 'Store':
			 return $this->storeXML();
		   break;	
		  default:
		     return $this->Invalid();
     }
 	 
   }

 
	public function storeXML() {
  		
 	   if ($this->config->get('multifeed_password') == $this->request->get['sifre'] && $this->config->get('multifeed_status') == true) {
 
		   $filter_data = array(
			   'store'     => $this->config->get('multifeed_store'),
			   'language'  => $this->config->get('multifeed_language')
		   );
				
				
 			$this->load->model('catalog/category');
			$this->load->model('extension/feed/feeds');
			$this->load->model('tool/image');
		 
 
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<items>';
			
			$products = $this->model_extension_feed_feeds->getProducts($filter_data);

 			foreach ($products as $product) {
		 
               $output .= '<item>';
			   
 			   $keyVars = array_keys($product);
			    
			   foreach ($keyVars as $keyVar) {
			   
			   if ($keyVar == 'image') {
			   
				     $output .= "<mainImage>" . $this->server . "image/" . $product[$keyVar] . "</mainImage>";
					 
				     $resimler = $this->model_extension_feed_feeds->getProductImages($product['product_id']);
				  
				 if ($resimler) {  
				 
			     $output .= "<additionalImage>";
				  
				 foreach ($resimler as $key => $resim) {
				 
				   $output .= "<image_".$key.">" . $this->server . "image/" . $resim['image'] . "</image_".$key.">";
			     
				 }
				 
				  $output .= "</additionalImage>";
				  
				 }
				 
			     } else {
				 
			      $output .= "<".$keyVar.">" .  strip_tags($product[$keyVar]) . "</".$keyVar.">";
				  
				} 
			
			 }
 			     
				 $categories = $this->model_extension_feed_feeds->getCategories($product['product_id']);
				 
				 foreach ($categories as $categorykey => $category) {
							
							$path = $this->getStorePath($category['category_id']);

							if ($path) {
								$string = '';

								foreach (explode('_', $path) as $path_id) {
									$category_info = $this->model_catalog_category->getCategory($path_id);

									if ($category_info) {
										if (!$string) {
											$string = $category_info['name'];
										} else {
											$string .= ' &gt; ' . $category_info['name'];
										}
									}
								}
								$output .= "<category_".$categorykey.">" .  $string . "</category_".$categorykey.">";
							}
				 } 
				 
				 
				 
			    if ($this->config->get('multifeed_options')) {
				     
					 $productOptions = array(
					 	'product_id' => $product['product_id'],
					    'language'  => $this->config->get('multifeed_language')
						
						
					 );
					 
					 
					 
			         $getProductOptions = $this->model_extension_feed_feeds->getProductOptions($productOptions);
			 
					 $output .= '<options>';
					 
					 foreach ($getProductOptions as $key => $option) {
					 
						  $output .= "<option_".$key.">";
						  
						  $keyVars = array_keys($option);
				
						  foreach ($keyVars as $keyVar) {
						  
						  
						  if ($keyVar == 'product_option_value') {	  
							  
							  $output .=   "<productOptionValues>";
							  foreach ($option[$keyVar] as $valueKey => $val) {
								$output .=   "<productOptionValues_" . $valueKey . ">";
									 $keyVals = array_keys($val);
									 
									  foreach ($keyVals as $keyVal) {
									  
										 $output .= "<". $keyVal .">" . $val[$keyVal] . "</". $keyVal .">";
									 }
								$output .=   "</productOptionValues_" . $valueKey . ">"; 
						  }	
						  
						  $output .=   "</productOptionValues>";
									  
						  } else {
							$output .= "<".$keyVar.">" . $option[$keyVar] . "</".$keyVar.">";
						  }	
							 
 					 }
 
						  $output .= "</option_".$key.">";
				 
					  }
					 
					 $output .= '</options>';				 
				     
					 }
				
				
				if ($this->config->get('multifeed_attributes')) {
				
				 $productAttributes = array(
					 	'product_id' => $product['product_id'],
					    'language'  => $this->config->get('multifeed_language')
						
						
				   );
				
				$getProductAttributes = $this->model_extension_feed_feeds->getProductAttributes($productAttributes);
			
				$output .= '<attributes>';
				
				foreach ($getProductAttributes as $attribute) {
				
				
				$attributeKey = array_keys($attribute);
				
		 
				foreach ($attributeKey as $attKey) {
				 
				  if ($attKey == 'attribute') {
				
				  
				   
				   foreach ($attribute[$attKey] as $attr_Key => $attval) {
					
					 
					$output .= "<attribute_".$attr_Key.">";
					
					$attrKeys = array_keys($attval);
					
					foreach ($attrKeys as $attrKey) {
					 
						$output .= "<".$attrKey.">" . $attval[$attrKey] . "</".$attrKey.">";
					 
					}
					   
					$output .= "</attribute_".$attr_Key.">";   
					
				   }
				   
				
				 
				} else {
				
					$output .= "<".$attKey.">" . $attribute[$attKey] . "</".$attKey.">";
					
				}
				  
			  }
				
		 }
				
				$output .= '</attributes>';				
				 
				}
				 
			    $output .= '</item>';
 
 		     }
				
 			$output .= '</items>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
 
		}  else {
		
		    return $this->Invalid();
		
		}
   }
	


	public function facebookXML() {
  						
	   if ($this->config->get('multifeed_password') == $this->request->get['sifre'] && $this->config->get('multifeed_status') == true) {
 
		   $filter = array(
			   'store'     => $this->config->get('multifeed_store'),
			   'language'  => $this->config->get('config_language_id'),
			   'platform'  => 'Facebook'
		   );
		   
 			$this->load->model('extension/feed/feeds');
			$this->load->model('tool/image');
						
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
			$output .= '  <channel>';
			$output .= '  <title>' . $this->config->get('config_name') . '</title>';
			$output .= '  <description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '  <link>' . $this->config->get('config_url') . '</link>';


			$product_data = array();

  			$multifeed_categories = $this->model_extension_feed_feeds->getFeedCategories($filter);

 
			foreach ($multifeed_categories as $multifeed_category) {
 
			
				$filter_data = array(
					'filter_category_id' => $multifeed_category['category_id'],
					'store'              => $this->config->get('multifeed_store'),
					'language'           => $this->config->get('multifeed_language'),
					'filter_filter'		 => false
				);
 			   
 

				$products = $this->model_extension_feed_feeds->getProducts($filter_data);

				foreach ($products as $product) {
				
 
					if (!in_array($product['product_id'], $product_data) && $product['description']) {
						$output .= '<item>';
						$output .= '<g:id>STR_ID-' . $product['product_id'] . '</g:id>';
						$output .= '<title><![CDATA[' . trim($product['name']) . ']]></title>';
						$output .= '<g:brand>Tuygun Mobilya</g:brand>';	
						$output .= '<description><![CDATA[' . strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')) . ']]></description>';
						$output .= '<g:condition>New</g:condition>';
						$output .= '  <g:currency>' . $this->config->get('config_currency') . '</g:currency>';

						if ((float)$product['special']) {
							$output .= '  <g:price>' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) . '</g:price>';
						    $output .= '  <g:sale_price>' .  $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) . '</g:sale_price>';
						  } else {
							$output .= '  <g:price>' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) . '</g:price>';
						}						
						
						
						$output .= '  <g:quantity>' . $product['quantity'] . '</g:quantity>';
						$output .= '  <g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>';
						$output .= '  <g:availability>' . ($product['quantity'] ? 'in stock' : 'out of stock') . '</g:availability>';						
						if ($product['model']) {
						    $output .= '  <g:model_number>' . $product['model'] . '</g:model_number>';
						}
						
						if ($product['mpn']) {
							$output .= '  <g:mpn><![CDATA[' . $product['mpn'] . ']]></g:mpn>' ;
						} else {
							$output .= '  <g:identifier_exists>false</g:identifier_exists>';
						}

						if ($product['upc']) {
							$output .= '  <g:upc>' . $product['upc'] . '</g:upc>';
						}

						if ($product['ean']) {
							$output .= '  <g:ean>' . $product['ean'] . '</g:ean>';
						}					   
					   
 			
						
				    if ($product['image']) {
							$output .= '  <g:image_link>' . $this->model_tool_image->resize($product['image'], $this->config->get('multifeed_width'), $this->config->get('multifeed_height')) . '</g:image_link>';
					 } else {
							$output .= '  <g:image_link></g:image_link>';
					 }


                    $additional_images = $this->model_extension_feed_feeds->getProductImages($product['product_id']);
				   
				    if ($additional_images) {

						  $output .= '<g:additional_image_link>';
						  
						  $virgul = "";
						  
		 				  $additionalImages = "";
						  
						  foreach ($additional_images as $additional_image) {
							
						   $additionalImages .= $virgul.$this->model_tool_image->resize($additional_image['image'], $this->config->get('multifeed_width'), $this->config->get('multifeed_height'));
							
							if ($additional_image['image']) {
						        $virgul = ", ";
							}
							 
						 }
						 
						 $output .= $additionalImages;
						 
						 $output .= '</g:additional_image_link>';
						 
						}

						$output .= '  <g:google_product_category><![CDATA[' . trim($multifeed_category['multifeed_category']) . ']]></g:google_product_category>';

						$categories = $this->model_extension_feed_feeds->getCategories($product['product_id']);

						
						$kategori = array();
						
						foreach ($categories as $category) {
 	
							$kategori = array(
								'category_id' => $category['category_id'],
								'store'       => $this->config->get('multifeed_store'),
								'language'    => $this->config->get('multifeed_language')
							);
										
									
							$path = $this->getPath($kategori);
			
		 					if ($path) {
								$string = '';
								
								
								$katId = array();
								 
								foreach (explode('_', $path) as $path_id) {
								
								    $katId = array(
										'category_id' => $path_id,
										'store'       => $this->config->get('multifeed_store'),
										'language'    => $this->config->get('multifeed_language')
									);
											

									$category_info = $this->model_extension_feed_feeds->getCategory($katId);

									if ($category_info) {
										if (!$string) {
											$string = $category_info['name'];
										} else {
											$string .= ' &gt; ' . $category_info['name'];
										}
									}
								}

								$output .= '<g:product_type><![CDATA[' . $string . ']]></g:product_type>';
							}
						}

						$output .= '<link>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</link>';
						$output .= '</item>';
					}
				}
			}

			$output .= '  </channel>';
			$output .= '</rss>';

			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
			
 		}  else {
		
		    return $this->Invalid();
		
		}


	}
	
 
 
    public function googleXML() {
  						
	   if ($this->config->get('multifeed_password') == $this->request->get['sifre'] && $this->config->get('multifeed_status') == true) {
 
		   $filter = array(
			   'store'     => $this->config->get('multifeed_store'),
			   'language'  => $this->config->get('multifeed_language'),
			   'platform'  => 'Google'
		   );
		   
 			$this->load->model('catalog/category');
			$this->load->model('extension/feed/feeds');
			$this->load->model('tool/image');
						
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
			$output .= '  <channel>';
			$output .= '  <title>' . $this->config->get('config_name') . '</title>';
			$output .= '  <description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '  <link>' . $this->config->get('config_url') . '</link>';
			

			$product_data = array();

  			$multifeed_categories = $this->model_extension_feed_feeds->getFeedCategories($filter);

 
			foreach ($multifeed_categories as $multifeed_category) {
 
			
				$filter_data = array(
					'filter_category_id' => $multifeed_category['category_id'],
					'store'              => $this->config->get('multifeed_store'),
					'language'           => $this->config->get('multifeed_language'),
					'filter_filter'		 => false
				);
 			   
 

				$products = $this->model_extension_feed_feeds->getProducts($filter_data);

				foreach ($products as $product) {
				
 
					if (!in_array($product['product_id'], $product_data) && $product['description']) {
						$output .= '<item>';
						
						
						$output .= '<g:id>' . $product['product_id'] . '</g:id>';
						$output .= '<title>' . trim($product['name']) . '</title>';
						$output .= '<g:brand>Tuygun Mobilya</g:brand>';
						$output .= '<description><![CDATA[' . strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')) . ']]></description>';
						$output .= '<g:condition>New</g:condition>';
						$output .= '<g:currency>' . $this->config->get('config_currency') . '</g:currency>';

 
						if ((float)$product['special']) {
							$output .= '<g:price>' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) . '</g:price>';
						    $output .= '<g:sale_price>' .  $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) . '</g:sale_price>';
						  } else {
							$output .= '<g:price>' . $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) . '</g:price>';
						}						
						
						
						$output .= '<g:quantity>' . $product['quantity'] . '</g:quantity>';
						$output .= '<g:weight>' . $this->weight->format($product['weight'], $product['weight_class_id']) . '</g:weight>';
						$output .= '  <g:availability><![CDATA[' . ($product['quantity'] ? 'in stock' : 'out of stock') . ']]></g:availability>';					
						if ($product['model']) {
						    $output .= '<g:model_number>' . $product['model'] . '</g:model_number>';
						}
						
						if ($product['mpn']) {
							$output .= '<g:mpn>' . $product['mpn'] . '</g:mpn>' ;
						} else {
							$output .= '<g:identifier_exists>false</g:identifier_exists>';
						}

						if ($product['upc']) {
							$output .= '<g:upc>' . $product['upc'] . '</g:upc>';
						}

						if ($product['ean']) {
							$output .= '<g:ean>' . $product['ean'] . '</g:ean>';
						}					   
					   
 			
						
				    if ($product['image']) {
							$output .= '<g:image_link>' . $this->model_tool_image->resize($product['image'], $this->config->get('multifeed_width'), $this->config->get('multifeed_height')) . '</g:image_link>';
					 }  

                    $additional_images = $this->model_extension_feed_feeds->getProductImages($product['product_id']);
				   
				    if ($additional_images) {
		 
		 				  foreach ($additional_images as $additional_image) {
						     $output .= '<g:additional_image_link>';
							 $output .= $this->model_tool_image->resize($additional_image['image'], $this->config->get('multifeed_width'), $this->config->get('multifeed_height'));
							 $output .= '</g:additional_image_link>';
						 }
						
						}
 
						$output .= '<g:google_product_category><![CDATA[' . $multifeed_category['multifeed_category'] . ']]></g:google_product_category>';
							
						$categories = $this->model_extension_feed_feeds->getCategories($product['product_id']);
						
						$kategori = array();
 							
						foreach ($categories as $category) {
						
						$kategori = array(
								 'category_id' => $category['category_id'],
								 'store'       => $this->config->get('multifeed_store'),
								 'language'    => $this->config->get('multifeed_language')
							);
														
							$path = $this->getPath($kategori);

							if ($path) {
								$string = '';

								foreach (explode('_', $path) as $path_id) {
									$category_info = $this->model_extension_feed_feeds->getCategory($kategori);

									if ($category_info) {
										if (!$string) {
											$string = $category_info['name'];
										} else {
											$string .= ' &gt; ' . $category_info['name'];
										}
									}
								}

								$output .= '<g:product_type><![CDATA[' . $string . ']]></g:product_type>';
							}
						}

						$output .= '<link>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</link>';
						
						$output .= '</item>';
					}
				}
			}

			$output .= '  </channel>';
			$output .= '</rss>';
 
			$this->response->addHeader('Content-Type: application/xml');
			$this->response->setOutput($output);
			
 		}  else {
		
		    return $this->Invalid();
		
		}


	}
	
	
    protected function getPath($kategori, $current_path = '') {
 
	   
		$this->load->model('extension/feed/feeds');
				
		$category_info = $this->model_extension_feed_feeds->getCategory($kategori);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}	
 
 
    protected function getStorePath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}
	
	
	public function Invalid() {
	
	     echo " Sorry, but something went wrong, please contact store owner ";
	    
	}	

}
