<?php

class AppController extends Controller {
	var $name = "AppController";
	var $helpers = array("Html", "Form", "Javascript", "Cache", "Time", "Units");
	var $components = array("Session");
	var $logged = false;
	
	function beforeFilter() {
	}
}

?>