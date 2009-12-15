<?php

class ReportsController extends AppController {
	var $name = "Reports";
	var $helpers = array('Units');
	var $uses = array('Project', 'Quota', 'Server', 'Scans');
	
	var $paginate = array(
		'limit' => 30,
		'order' => array('Project.number +0' => 'ASC', 'Project.name' => 'ASC'),
		'recursive' => 0
	);
	
	function index() {
		$this->pageTitle = "Report Dashboard";
		
		if(($report = Cache::read("main", 'reports')) === false) {
			$report['gainers'] 	= $this->Quota->getMovers();
			$report['losers'] 	= $this->Quota->getMovers(array('dir' => 'asc'));
			$report['usage'] 	= $this->Server->getUsage();
			$report['projects'] = $this->Project->getNewProjects();
			Cache::write("main", $report, 'reports');
		}
		
		$gainers 	= $report['gainers'];
		$losers 	= $report['losers'];
		$usage		= $report['usage'];
		$projects 	= $report['projects'];
		
		$this->set(compact('gainers', 'losers', 'usage', 'projects'));
		
		unset($gainers, $losers, $usage, $projects);
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
		if(($report = Cache::read("main", 'reports')) === false) {
			$report['gainers'] 	= $this->Quota->getMovers();
			$report['losers'] 	= $this->Quota->getMovers(array('dir' => 'asc'));
		}
		$gainers 	= $report['gainers'];
		$losers 	= $report['losers'];
		
		if($type == 'losers') {
			$this->set('movers', $losers);
			$this->render('/elements/reports/movers', 'ajax');
		}
		else {
			$this->set('movers', $gainers);
			$this->render('/elements/reports/movers', 'ajax');
		}
	}
}

?>