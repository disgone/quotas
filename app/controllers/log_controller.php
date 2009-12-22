<?php

class LogController extends AppController {
	var $name = 'Log';
	var $uses = array("Project", "Quota", "User", "Action");
	
	var $paginate = array(
		'limit' => 30,
		'order' => array('Action.created' => 'DESC'),
		'recursive' => 0
	);
	
	function admin_read() {
		$this->adminOnly();
		$this->pageTitle = "History Log";
		$this->set('activities', $this->paginate('Action'));
	}
}

?>