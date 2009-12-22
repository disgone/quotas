<?php

class DashboardController extends AppController {
	var $name = "Dashboard";
	var $uses = array("Project", "Quota", "User");
	var $components = array("Cookie");
	
	function index() {
		$this->pageTitle = "My Dashboard";
		
		$projects = null;
		
		if($this->Session->check('User')) {
			$this->Project->Behaviors->attach('Containable');
			$this->Project->contain('Server', 'Quota');
			
			$mine = $this->Project->ProjectsUser->find('all', array('conditions' => array('ProjectsUser.user_id' => $this->Session->read('User.id'))));
			
			if(isset($mine[0])) {
				$ids = Set::extract("/ProjectsUser/project_id", $mine);
				$projects = $this->Project->find('all', array('conditions' => array('Project.id' => $ids)));
				
				$total = array('used' => 0, 'allowance' => 0);
				foreach($projects as &$project) {
					$total['used'] += $project['Quota'][0]['consumed'];
					$total['allowance'] += $project['Quota'][0]['allowance'];
				}
			}

		}

		$this->set(compact('projects', 'total'));
		unset($ids, $projects, $updates, $project, $ndx, $total_used, $total_allotted);
	}
}