<?php

class ReportsController extends AppController {
	var $name = "Reports";
	var $helpers = array('Units');
	var $uses = array('Project', 'Quota', 'Server');
	
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
		$this->set('pageTitle', $this->pageTitle);
		unset($gainers, $losers, $usage, $projects);
	}
	
	function new_projects() {
		$this->pageTitle = "New Projects";
		$projects = $this->Project->getNewProjects(null, date('Y-m-d 00:00:00', strtotime('-7 days')));
		
		$this->set(compact('projects'));
		$this->set('pageTitle', $this->pageTitle);
		unset($projects);
	}
	
	function conflicts() {
		$this->pageTitle = "Conflicts Report";
		$dupes = $this->Project->getDupes();
		
		$ids = Set::extract("/Project/id", $dupes);
		$updates = $this->Quota->getLatest($ids);
		
		foreach($dupes as $ndx => &$project) {
			$project['Project']['Quota'] = $updates[$ndx]['Quota'];
		}

		$this->set(compact('dupes'));
	}
}

?>