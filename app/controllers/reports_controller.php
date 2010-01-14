<?php

class ReportsController extends AppController {
	var $name = "Reports";
	var $helpers = array('Units');
	var $uses = array('Project', 'Quota', 'Server', 'Scans');
	
	function index() {
		$this->pageTitle = "Report Dashboard";
		
		if(($report = Cache::read("main", 'reports')) === false) {
			$report['projects'] = $this->Project->getNewProjects();
			Cache::write("main", $report, 'reports');
		}

		$projects 	= $report['projects'];
		
		$this->set(compact('projects'));
		
		unset($projects);
	}
	
	function new_projects() {
		$this->pageTitle = "New Projects";
		
		$this->paginate['conditions'] = array('Project.created >=' => date('Y-m-d 00:00:00', strtotime('-7 days')));
		$this->paginate['order'] = array('Project.created' => 'ASC', 'Project.number +0' => 'ASC', 'Project.name' => 'ASC');
		$projects = $this->paginate('Project');
		
		$this->set(compact('projects'));
		$this->set('pageTitle', $this->pageTitle);
		
		unset($projects);
	}
	
	function duplicates() {
		$this->pageTitle = "Duplicates Report";
		
		if(($dupes = Cache::read("dupes", 'reports')) === false) {
			$dupes = $this->Project->getDupes();
			Cache::write("dupes", $dupes, 'reports');
		}
		
		$this->set(compact('dupes'));
	}
	
	function movers($type = null) {		
		if(($movers = Cache::read("movers", "reports")) === false) {
			$movers['gainers'] 	= $this->Quota->getMovers();
			$movers['losers'] 	= $this->Quota->getMovers(array('dir' => 'asc'));
			Cache::write("movers", $movers, "reports");
		}
		
		$gainers 	= $movers['gainers'];
		$losers 	= $movers['losers'];
		
		if($type == 'decrease') {
			$this->set('movers', $losers);
			$this->render('/elements/reports/movers', 'ajax');
		}
		else {
			$this->set('movers', $gainers);
			$this->render('/elements/reports/movers', 'ajax');
		}
	}
	
	function server_stats() {
		if(($usage = Cache::read("server_usage", "reports")) === false) {
			$usage = $this->Server->getUsage();
			Cache::write("server_usage", $usage, "reports");
		}
		
		$this->set('usage', $usage);
	}
}

?>