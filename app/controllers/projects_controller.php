<?php

class ProjectsController extends AppController {
	var $name = "Projects";
	var $helpers = array('Units', 'Javascript', 'Cache');
	var $uses = array('Project', 'Quota');
	var $components = array("RequestHandler");
	
	var $paginate = array(
		'limit' => 25,
		'order' => array('Project.number +0' => 'ASC', 'Project.name' => 'ASC'),
		'recursive' => 0
	);
	
	var $cacheAction = array(
		'index' 				=> '10 minutes',
		'details'				=> '5 minutes'
	);
	
	function index() {
		$projects = $this->paginate('Project');
		
		//Get the most recent quota updates for each project.
		foreach($projects as &$project) {
			$quota = $this->Quota->getLatest($project['Project']['id']);
			$project['Project']['Quota'] = $quota['Quota'];
		}

		$this->set('projects', $projects);
	}
	
	function details($id) {
		//Throw a 404 error if ID was not specified.
		if(!$id)
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));
			
		//Get project and quota data.
		if(($project = Cache::read("project_" . $id, 'default')) === false) {
			$project = $this->_requestProjectData($id);
			Cache::write("project_" . $id, $project, 'default');
		}
		
		//Throw a 404 error if the project with ID was not found in the database.
		if(empty($project))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));
		
		$this->set('project', $project);
		$this->set('quota', $project['Meta']);
		
		unset($min, $max, $start, $end, $project, $quota);
	}
	
	function projectData($id) {
		Configure::write('debug', 0);
		//Throw a 404 error if ID was not specified.
		if(!$id)
			$this->cakeError('error404', array('url' => "projects/projectData/$id"));
		
		//Get project and quota data.
		if(($project = Cache::read("project_full_" . $id, 'default')) === false) {
			$project = $this->_requestProjectData($id, true);
			Cache::write("project_full_" . $id, $project, 'default');
		}

		//Throw a 404 error if the project with ID was not found in the database.
		
		if(empty($project))
			$this->cakeError('error404', array('url' => "projects/projectData/$id"));

		$this->set('data', $project);
		
		unset($project);
	}
	
	function _requestProjectData($id, $max = false) {
		$project = $this->Project->findById($id);
		//Project not found
		if(empty($project))
			return null;

		if($max)
			$project['Quota'] = $this->Quota->getProjectQuotas($id);
		else
			$project['Quota'] = $this->Quota->getRange($id);
		
		//If no quota data was returned for the specified times we need to show the latest updates we have.
		if(empty($project['Quota'])) {
			$last = $this->Quota->find('first', array('conditions' => array('Quota.project_id' => $id), 'order' => 'Quota.created DESC', 'limit' => 1));
			$start = date('Y-m-d', strtotime($last['Quota']['created'] . " - 1 day"));
			$end = date('Y-m-d 23:59:59', strtotime($last['Quota']['created']));
			$project['Quota'] = $this->Quota->getRange($id, $start, $end);
			unset($last, $start, $end);
		}
		
		//Import units helper
		App::import('Helper', 'Units');
		$units = new UnitsHelper();
		
		//Get maximum or minimum quota usage.
		$max = 0;
		$min = $project['Quota'][0]['Quota']['consumed'];
		
		foreach($project['Quota'] as $key => $quota) {
			if($quota['Quota']['consumed'] > $max)
				$max = $quota['Quota']['consumed'];
			if($quota['Quota']['consumed'] < $min)
				$min = $quota['Quota']['consumed'];
				
			//Calculate change from previous update.
			if($key > 0) {
				$project['Quota'][$key]['Quota']['change'] = $quota['Quota']['consumed'] - $project['Quota'][$key-1]['Quota']['consumed'];
			}
			else
				$project['Quota'][$key]['Quota']['change'] = 0;
			
		}
	
		$quota = array(
			'current'		=> $project['Quota'][count($project['Quota'])-1]['Quota']['consumed'],
			'start'			=> $project['Quota'][0]['Quota']['consumed'],
			'change'		=> $project['Quota'][count($project['Quota'])-1]['Quota']['consumed'] - $project['Quota'][0]['Quota']['consumed'],
			'allowed'		=> $project['Quota'][count($project['Quota'])-1]['Quota']['allowance'],
			'max'			=> $max,
			'min'			=> $min,
			'unit'			=> array('label' => $units->unit($min), 'index' => $units->unitIndex($min))
		);
		
		$project['Meta'] = $quota;
		
		unset($quota, $max, $min, $options, $units);

		return $project;
	}
}

?>