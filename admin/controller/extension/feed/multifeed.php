<?php 

class ControllerExtensionFeedMultifeed extends Controller {

	private $error = array(); 


	public function index() {

		$this->load->language('extension/feed/multifeed');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('multifeed', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true));
		}
 

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_data_feed'] = $this->language->get('entry_data_feed');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_category'] = $this->language->get('tab_category');
		$data['entry_multifeed_category'] = $this->language->get('entry_multifeed_category');
	    $data['entry_category'] = $this->language->get('entry_category');	
	    $data['button_category_add'] = $this->language->get('button_category_add');		
	    $data['text_edit'] = $this->language->get('text_edit');		
		$data['text_google'] = $this->language->get('text_google');
		$data['text_facebook'] = $this->language->get('text_facebook');
		$data['text_default'] = $this->language->get('text_default');
		$data['tab_store'] = $this->language->get('tab_store');
	    $data['text_language'] = $this->language->get('text_language');		
	    $data['text_password'] = $this->language->get('text_password');		
	    $data['text_options'] = $this->language->get('text_options');		
	    $data['text_attributes'] = $this->language->get('text_attributes');		
	    $data['button_import'] = $this->language->get('button_import');		
	    $data['text_width'] = $this->language->get('text_width');		
	    $data['text_height'] = $this->language->get('text_height');	
	    $data['text_storexml'] = $this->language->get('text_storexml');		
	    $data['text_facebookxml'] = $this->language->get('text_facebookxml');		
	    $data['text_googlexml'] = $this->language->get('text_googlexml');	
		
		

		
		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=extension/feed/multifeed';
 			
				
		$data['token'] = $this->session->data['token'];	
		

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
      		'separator' => false
   		);



   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_feed'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/feed/multifeed', 'token=' . $this->session->data['token'], true),
      		'separator' => ' :: '
   		);

		
		$data['action'] = $this->url->link('extension/feed/multifeed', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true);

			


		if (isset($this->request->post['multifeed_status'])) {
			$data['multifeed_status'] = $this->request->post['multifeed_status'];
		} else {
			$data['multifeed_status'] = $this->config->get('multifeed_status');
		}

		 
		if (isset($this->request->post['multifeed_password'])) {
			$data['multifeed_password'] = $this->request->post['multifeed_password'];
		} else {
			$data['multifeed_password'] = $this->config->get('multifeed_password');
		}


				
 		if (isset($this->request->post['multifeed_options'])) {
			$data['multifeed_options'] = $this->request->post['multifeed_options'];
		} else {
			$data['multifeed_options'] = $this->config->get('multifeed_options');
		}

		 
		if (isset($this->request->post['multifeed_attributes'])) {
			$data['multifeed_attributes'] = $this->request->post['multifeed_attributes'];
		} else {
			$data['multifeed_attributes'] = $this->config->get('multifeed_attributes');
		}
		


		if (isset($this->request->post['multifeed_width'])) {
			$data['multifeed_width'] = $this->request->post['multifeed_width'];
		} else {
			$data['multifeed_width'] = $this->config->get('multifeed_width');
		}
		
		
		if (isset($this->request->post['multifeed_height'])) {
			$data['multifeed_height'] = $this->request->post['multifeed_height'];
		} else {
			$data['multifeed_height'] = $this->config->get('multifeed_height');
		}		
		
		
				
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $result) {
			$data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'] ,
				'code'        => $result['code']				
			);		
		}

		if (isset($this->request->post['multifeed_language'])) {
			$data['multifeed_language'] = $this->request->post['multifeed_language'];
		} else {
			$data['multifeed_language'] = $this->config->get('multifeed_language');
		}
		
		
				
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();
 
		if (isset($this->request->post['multifeed_store'])) {
			$data['multifeed_store'] = $this->request->post['multifeed_store'];
		} else {
			$data['multifeed_store'] = $this->config->get('multifeed_store');
		}
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/multifeed', $data));

	} 

 
	protected function validate() {

		if (!$this->user->hasPermission('modify', 'extension/feed/multifeed')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;

	}	
	
 	public function install() {
	
		$this->load->model('extension/feed/multifeed');

		$this->model_extension_feed_multifeed->install();
		
		
	}




	public function uninstall() {
	
		$this->load->model('extension/feed/multifeed');

		$this->model_extension_feed_multifeed->uninstall();
	
	}

	public function import() {
		
		$this->load->language('extension/feed/multifeed');



		$kategoriId = $this->request->get['platform'];
		
       
		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'extension/feed/multifeed')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				// Allowed file extension types
				if (utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)) != 'txt') {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Allowed file mime types
				if ($this->request->files['file']['type'] != 'text/plain') {
					$json['error'] = $this->language->get('error_filetype');
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
		
			$json['success'] = $this->language->get('text_success');

			$this->load->model('extension/feed/multifeed');

			// Get the contents of the uploaded file
			$content = file_get_contents($this->request->files['file']['tmp_name']);

		    $this->model_extension_feed_multifeed->import($content, $kategoriId);

			unlink($this->request->files['file']['tmp_name']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function category() {
	
		$this->load->language('extension/feed/multifeed');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['column_multifeed_category'] = $this->language->get('column_multifeed_category');
		$data['column_category'] = $this->language->get('column_category');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$categorId = $this->request->get['getcategory'];
		 	 
		$data['multifeed_categories'] = array();

		$this->load->model('extension/feed/multifeed');

		$results = $this->model_extension_feed_multifeed->getCategories(array('platform' => $categorId));

		foreach ($results as $result) {
			$data['multifeed_categories'][] = array(
				'multifeed_category_id'   => $result['multifeed_category_id'],
				'multifeed_category'      => $result['multifeed_category'],
				'category_id'            => $result['category_id'],
				'category'               => $result['category'],
				'platform'               => $result['platform']
			);
		}

		$category_total = $this->model_extension_feed_multifeed->getTotalCategories(array('platform' => $categorId));

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('extension/feed/multifeed/category', 'token=' . $this->session->data['token'] . '&platform=' . $categorId . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($category_total - 10)) ? $category_total : ((($page - 1) * 10) + 10), $category_total, ceil($category_total / 10));

		sprintf($this->response->setOutput($this->load->view('extension/feed/multifeed_category', $data)));
		
	}

	public function addCategory() {
	
	 
		$this->load->language('extension/feed/multifeed');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (!empty($this->request->post['multifeed_category_id']) && !empty($this->request->post['category_id'])) {
			$this->load->model('extension/feed/multifeed');
 
			$this->model_extension_feed_multifeed->addCategory($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	
	}

	public function removeCategory() {
		$this->load->language('extension/feed/multifeed');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/feed/multifeed');

			 $this->model_extension_feed_multifeed->deleteCategory($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		
		$json = array();
 

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('extension/feed/multifeed');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$categoryFeed = $this->request->get['getcategory'];

			$filter_data = array(
				'filter_name' => html_entity_decode($filter_name, ENT_QUOTES, 'UTF-8'),
				'category_feed' => $categoryFeed ,
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_feed_multifeed->getMultiFeedsCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'multifeed_category_id' => $result['multifeed_category_id'],
					'name'                 => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	
	

}