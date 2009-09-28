<?php

class SearchController extends AppController {
	var $name = "Search";
	var $helpers = array('Units', 'Javascript', 'Cache', 'Time', 'Form', 'Lighter');
	var $uses = array('Project');
	var $components = array("RequestHandler");
	
	function index() {
		
	}
	
	function results() {
		$results = $this->_getSearchResults();
		$term = $this->_getTerm();
		$this->set(compact('results','term'));
	}
	
	function autosense() {
		$results = $this->_getSearchResults();
		$term = $this->_getTerm();
		$this->set(compact('results','term'));
	}
	
	function _getTerm() {
		App::import('Sanitize');
		$termVar = 'q';
		
		if(isset($this->params['named'][$termVar]) && trim($this->params['named'][$termVar]) != null) {
			return Sanitize::clean($this->params['named'][$termVar]);
		}
		else if(isset($this->params['url'][$termVar]) && trim($this->params['url'][$termVar]) != null) {
			return Sanitize::clean($this->params['url'][$termVar]);
		}
		else
			return null;
	}
	
	function _getSearchResults() {
		$term = $this->_getTerm();

		if(!$term)
			return null;
		
		if($this->RequestHandler->isAjax()) {
			 Configure::write('debug', 0);
		}
			
		return $this->Project->search($term);
	}

}

?>