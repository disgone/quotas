<?php

class AppController extends Controller {
	var $name = "AppController";
	var $helpers = array("Html", "Form", "Javascript", "Cache", "Time", "Units");
	var $components = array("Session", "Cookie", "Login", "RequestHandler");
	var $logged = false;
	
	function beforeFilter() {
		if(!$this->Session->check("User")) {
			//Check for auto login
			$cookie = $this->Cookie->read('User.pk');
			if($cookie !== null) {
				$this->Login->cookieLogin($cookie['uid']);
			}
		}
		
		if(($servers = Cache::read("servers", 'mem')) === false) {
			App::import('Server');
			$this->Server->unbindModel(
				array('hasMany' => array('Project'))
			);
			$servers = $this->Server->find('all');
			Cache::write("servers", $servers, 'mem');
		}
		
		if($this->RequestHandler->isAjax()) {
			 Configure::write('debug', 0);
			 $this->layout = "ajax";
		}
	}
	
	function adminOnly() {
		if($this->Session->read('User.Group.name') != "Admin") {
			$this->Session->setFlash("Access denied.", "flash/error");
			$this->redirect("/");
		}
	}
}

?>