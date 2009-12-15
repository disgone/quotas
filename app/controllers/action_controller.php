<?php

class ActionController extends AppController {
	var $name = 'Action';
	var $uses = array("Project", "Quota", "User", "Action");
	
	var $paginate = array(
		'limit' => 30,
		'order' => array('Action.created' => 'DESC'),
		'recursive' => 0
	);
	
	function admin_log() {
		$this->adminOnly();
		$this->pageTitle = "Activity Log";
		$this->set('activities', $this->paginate('Action'));
	}
}

?>