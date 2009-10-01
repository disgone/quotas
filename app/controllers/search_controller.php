<?php

class SearchController extends AppController {
	var $name = "Search";
	var $helpers = array('Units', 'Lighter');
	var $uses = array('Project');
	var $components = array("RequestHandler");
	
	function index() {
		
	}
	
	function results() {
		$results = $this->_getSearchResults();
		$term = $this->_getTerm();
		if(count($results) == 1) {
			$this->redirect(array('controller' => 'projects', 'action' => 'details', $results[0]['Project']['id']));
		}
		$this->set(compact('results','term'));
		unset($results, $term);
	}
	
	function autosense() {
		$results = $this->_getSearchResults();
		$term = $this->_getTerm();
		$this->layout = "ajax";
		$this->set(compact('results','term'));
		unset($results, $term);
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
	
	function _getSearchResults($limit = 10) {
		$term = $this->_getTerm();

		if(!$term)
			return null;
		
		if($this->RequestHandler->isAjax()) {
			 Configure::write('debug', 0);
		}
			
		return $this->Project->search($term, $limit);
	}

}

?>