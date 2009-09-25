<?php

class SearchController extends AppController {
	var $name = "Search";
	var $helpers = array('Units', 'Javascript', 'Cache', 'Time', 'Form');
	var $uses = array('Project');
	var $components = array("RequestHandler");
	
	function index() {
		
	}
	
	function autosense() {
		App::import('Sanitize');
		$termVar = 'q';
		
		if(isset($this->params['named'][$termVar]) && trim($this->params['named'][$termVar]) != null) {
			$term = Sanitize::clean($this->params['named'][$termVar]);
		}
		else if(isset($this->params['url'][$termVar]) && trim($this->params['url'][$termVar]) != null) {
			$term = Sanitize::clean($this->params['url'][$termVar]);
		}
		else
			exit();
			
		if($this->RequestHandler->isAjax()) {
			 Configure::write('debug', 0);
		}
			
		$results = $this->Project->search($term);

		$this->set(compact('results'));
	}

}

?>