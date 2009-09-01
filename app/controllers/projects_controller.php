<?php

class ProjectsController extends AppController {
	var $name = "Projects";
	var $helpers = array('Units', 'Javascript', 'Cache', 'Time', 'Form');
	var $uses = array('Project', 'Quota');
	var $components = array("RequestHandler");
	
	var $paginate = array(
		'limit' => 25,
		'order' => array('Project.number +0' => 'ASC', 'Project.name' => 'ASC'),
		'recursive' => 0
	);
	
	var $cacheAction = array(
		'xindex' 				=> '+1 hour',
		'xdetails'				=> '15 minutes'
	);
	
	function index() {
		$this->pageTitle = "Project Directory";
		$projects = $this->paginate('Project');
		
		$ids = Set::extract("/Project/id", $projects);
		$updates = $this->Quota->getLatest($ids);

		foreach($projects as $ndx => &$project) {
			$project['Project']['Quota'] = $updates[$ndx]['Quota'];
		}

		$this->set('projects', $projects);
		unset($list, $updates, $projects, $ids);
	}
	
	function details($id = null) {
		$this->pageTitle = "Project Details";
		//Throw a 404 error if ID was not specified.
		if(!$id)
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));
			
		//Grabs period time if specified in the url or a named parameter.
		//?period=3d or period:3d
		$period = $this->_getPeriod();
			
		//Get project and quota data.
		if(($project = Cache::read("project_" . $id, 'default')) === false) {
			$project = $this->_requestProjectData($id, $period);
			Cache::write("project_" . $id, $project, 'default');
		}
		
		//Throw a 404 error if the project with ID was not found in the database.
		if(empty($project))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));

		//Get the last time the project had a change in usage.
		$changed = $this->Quota->getLastChange($id);
		
		$this->pageTitle = $project['Project']['number'] . " Details";
		$this->set(compact('project', 'changed'));
		$this->set('quota', $project['Meta']);
		
		unset($min, $max, $start, $end, $project, $quota, $durations);
	}
	
	function projectData($id = null) {
		Configure::write('debug', 0);
		//Throw a 404 error if ID was not specified.
		if(!$id)
			$this->cakeError('error404', array('url' => "projects/projectData/$id"));
		
		$period = $this->_getPeriod();
		if(($project = Cache::read("project_" . $id . "_qd", 'default')) === false) {
			$project = $this->_requestProjectData($id, null, true);
			Cache::write("project_" . $id . "_qd", $project, 'default');
		}

		//Throw a 404 error if the project with ID was not found in the database.
		
		if(empty($project))
			$this->cakeError('error404', array('url' => "projects/projectData/$id"));

		$this->set('data', $project);
		
		unset($project);
	}
	
	function update($id = null) {
		Configure::write('debug', 0);
		//Throw a 404 error if ID was not specified.
		if(!$id)
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));
			
		$this->layout = 'ajax';
		
		$period = $this->_getPeriod();
			
		//Get project and quota data.
		if(($project = Cache::read("project_" . $id, 'default')) === false) {
			$project = $this->_requestProjectData($id, $period);
			Cache::write("project_" . $id . "_" . $period['duration'], $project, 'default');
		}
		
		//Throw a 404 error if the project with ID was not found in the database.
		if(empty($project))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));
			
		$this->set('project', $project);
		$this->set('quota', $project['Meta']);
	}
	
	function updateTitle() {
		if(!empty($this->data)) {
			App::import("Core", "Sanitize");
			$title = Sanitize::clean($this->data['Project']['title']);
			$this->Project->id = $this->data['Project']['id'];
			$this->Project->saveField("title", $title);

			$this->set('title', $title);
		}
		$this->set('title', 'XXX');
	}
	
	function _requestProjectData($id, $period = null, $max = false) {
		$project = $this->Project->findById($id);
		//Project not found
		if(empty($project))
			return null;

		if($max)
			$project['Quota'] = $this->Quota->getProjectQuotas($id);
		else if($period) {
			$project['Quota'] = $this->Quota->getRange($id, $period['start'], $period['end']);
		}
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
		
		if($max) {
			$tmp = array();
			if(count($project['Quota']) > 1000) {
				for($i = 0; $i < count($project['Quota']); $i=$i+4) {
					array_push($tmp, $project['Quota'][$i]);
				}
				$project['Quota'] = $tmp;
			}
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
	
	function _scope($data) {
		print_r($data);
	}
	
	function _getPeriod() {
		if(isset($this->params['url']['period']) || isset($this->params['named']['period'])) {
			//Get the period variable from the url.
			$period = isset($this->params['url']['period']) ? $this->params['url']['period'] : $this->params['named']['period'];
			//Get the date unit, IE m, d, w, h etc..
			$unit = preg_split('/([0-9])+/i', $period, -1, PREG_SPLIT_NO_EMPTY);
			$unit = isset($unit[0]) ? $unit[0] : 'd';
			//Get the number from the period value.
			$value = preg_split('/([a-z])+/i', $period, -1, PREG_SPLIT_NO_EMPTY);
			$value = isset($value[0]) ? abs($value[0]) : 1;
			
			$duration = $value . $unit; 
			
			//Convert the date char to a strtotime friendly value.
			switch($unit) {
				case 'h':
					$unit = 'hours';
					break;
				case 'w':
					$unit = 'weeks';
					break;
				case 'm':
					$unit = 'months';
					break;
				case 'y':
					$unit = 'years';
					break;
				case 'd':
				default:
					$unit = 'days';
					break;
			}
			
			$start = date("Y-m-d H:i:s", strtotime($value*-1 . $unit));
			$end = date("Y-m-d H:i:s");
			
			return array('start' => $start, 'end' => $end, 'duration' => $duration);
		}
		
		return null;
	}
}

?>