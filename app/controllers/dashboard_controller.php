<?php

class DashboardController extends AppController {
	var $name = "Dashboard";
	var $uses = array("Project", "Quota", "User", "ProjectsUser");
	
	function index() {
		$this->pageTitle = "My Dashboard";
		
		$projects = null;
		
		if($this->Session->check('User')) {
			$ids = Set::extract("/Project/id", $this->User->getProjects($this->Session->read('User.id')));
			$projects = $this->Project->find('all', array('conditions' => array('Project.id' => $ids)));
			$updates = $this->Quota->getLatest($ids);
			$total_used = $total_allotted = 0;
			foreach($projects as $ndx => &$project) {
				$project['Project']['Quota'] = $updates[$ndx]['Quota'];
				$total_used += $project['Project']['Quota']['consumed'];
				$total_allotted += $project['Project']['Quota']['allowance'];
			}
			$total = array('used' => $total_used, 'allowance' => $total_allotted);
		}

		$this->set(compact('projects', 'total'));
		unset($ids, $projects, $updates, $project, $ndx, $total_used, $total_allotted);
	}
}