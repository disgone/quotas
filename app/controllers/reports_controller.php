<?php

class ReportsController extends AppController {
	var $name = "Reports";
	var $helpers = array('Units', 'Javascript', 'Cache', 'Time', 'Form');
	var $uses = array('Project', 'Quota', 'Server');
	
	function index() {
		$this->pageTitle = "Project Reporting";
		$gainers = $this->Quota->getMovers();
		$losers = $this->Quota->getMovers(array('dir' => 'asc'));
		$usage = $this->Server->getUsage();
		
		$this->set(compact('gainers', 'losers', 'usage'));
	}
}

?>