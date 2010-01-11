<?php

class AdminController extends AppController {
	var $name = "Admin";
	var $uses = array("Project", "Quota", "User", "ProjectsUser");
	
	function beforeFilter() {
		$this->adminOnly();
	}
	
	function index() {
		$this->pageTitle = "Administrator Control Panel";
	}

}

?>