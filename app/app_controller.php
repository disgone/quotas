<?php

class AppController extends Controller {
	var $name = "AppController";
	var $helpers = array("Html", "Form", "Javascript", "Cache", "Time", "Units");
	var $components = array("Session", "Cookie", "Login");
	var $logged = false;
	
	function beforeFilter() {
		if(!$this->Session->check("User")) {
			//Check for auto login
			$cookie = $this->Cookie->read('User.pk');
			if($cookie !== null) {
				$this->Login->cookieLogin($cookie['uid']);
			}
		}
	}
}

?>