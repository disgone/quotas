<?php

class ProjectsController extends AppController {
	var $name = "Projects";
	var $helpers = array('Units');
	var $uses = array('Project', 'Quota', 'User');
	var $components = array("RequestHandler");
	
	var $paginate = array(
		'limit' => 30,
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
	
	/*
	 * Project Details
	 * 
	 * Displays the details for a specified project
	 * 
	 * @param int $id ID of project to display details for.
	 */
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
		
		//Check if this project belongs to a logged in users "my project" list.
		$my_project = null;
		if($this->Session->check('User'))
			$my_project = $this->User->ProjectsUser->find('all', array('conditions' => array('ProjectsUser.user_id' => 1, 'ProjectsUser.project_id' => $id)));
		
		$this->pageTitle = trim($project['Project']['number'] . " " . $project['Project']['name']);
		
		$this->set(compact('project', 'changed'));
		$this->set('my_project', $my_project);
		$this->set('quota', $project['Meta']);
		
		unset($min, $max, $start, $end, $project, $quota, $durations);
	}
	
	function delete($id = null) {
		$this->pageTitle = "Delete Project";
		//Throw a 404 error if ID was not specified.
		if(!$id)
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/delete'));
			
		if($this->RequestHandler->isAjax()) {
			 Configure::write('debug', 0);
			 $this->autoRender = false;
			 exit();
		}
			
		$this->Project->id = $id;
		$project = $this->Project->read();
		/*
		if($this->Project->delete($id)) {
			if($this->RequestHandler->isAjax()) {
				echo "success";
			}
			else {
				$this->Session->setFlash(sprintf("Project %s has been deleted from the tracker.", $project['Project']['number']));
				$this->redirect(array('action'=>'index'));
			}
		}
		else {
			if($this->RequestHandler->isAjax()) {
				echo "error";
			}
			else {
				$this->Session->setFlash(sprintf("Oh snap!  We've broken something and were not able to delete project %s.", $project['Project']['number']));
				$this->redirect(array('action'=>'index'));
			}
		}*/
	}
	
	/*
	 * Track Project
	 * 
	 * Adds the specified project to the logged in user's "My Project" list.
	 * 
	 * @param int $id ID of project to display details for.
	 */
	function track($id = null, $action = null) {
		if(!$id)
			$this->cakeError('error404', array('url' => "projects/projectData/$id"));
			
		if(!$this->Session->check('User'))
			$this->cakeError('login');

		$this->Project->id = $id;
		$project = $this->Project->read();
		if(empty($project))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/delete'));
			
		$data = array(
			'user_id' => $this->Session->read('User.id'),
			'project_id' => $id
			);
			
		$exists = $this->Project->ProjectsUser->find('first', array('conditions' => array('ProjectsUser.user_id' => $data['user_id'], 'ProjectsUser.project_id' => $data['project_id'])));

		switch($action) {
			case "remove":
				if(!empty($exists)) {
					if($this->Project->ProjectsUser->delete($exists['ProjectsUser']['id'])); {
						if(!$this->RequestHandler->isAjax())
							$this->Session->setFlash("Project removed from <strong>My Projects</strong> list.", "flash/success");
					}
				}
				break;
			case "add":
				if(empty($exists)) {
					if($this->Project->ProjectsUser->save($data)) {
						if(!$this->RequestHandler->isAjax())
							$this->Session->setFlash("Project added to <strong>My Projects</strong> list.", "flash/success");
					}
				}
				break;
			default:
				exit();
		}
		
		if($this->RequestHandler->isAjax()) {
			$this->layout = "ajax";
		}
		else
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
		
	}
	
	/*
	 * Update Title
	 * 
	 * Changes the name of a specified project.
	 * 
	 * @param int $id ID of project to display details for.
	 */
	function updateTitle($id = null) {
		if(!$id)
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/delete'));
		
		Configure::write('debug', 0);
		$this->autoRender = false;
		
		$this->Project->id = $id;
		$project = $this->Project->read();
		if(empty($this->data)) {
			echo $project['Project']['name'];
		}
		else {
			if($this->Project->saveField('name', $this->data['Project']['title'])) {
				echo $this->data['Project']['title'];
				Cache::delete("project_" . $id);
			}
			else {
				echo $project['Project']['name'];
			}
		}
	}
	
	/*
	 * Project Details
	 * 
	 * URL access point for project data.  Used primarily for sending quota data to the
	 * Quota graphs on the details page.
	 * 
	 * @param int $id ID of project to display details for.
	 */
	function projectData($id = null) {
		Configure::write('debug', 0);
		if(!$id)
			$this->cakeError('error404', array('url' => "projects/projectData/$id"));
		
		$period = $this->_getPeriod();
		if(($project = Cache::read("project_" . $id . "_qd", 'default')) === false) {
			$project = $this->_requestProjectData($id, null, true);
			//Cache::write("project_" . $id . "_qd", $project, 'default');
		}

		//Throw a 404 error if the project with ID was not found in the database.
		if(empty($project))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));

		$this->set('data', $project);
		
		unset($project, $period);
	}

	/*
	 * Request Project Data
	 * 
	 * Retreives all quota data and stats for a given project.
	 * 
	 * @param int ID of project to get data for.
	 * @param array Start/End dates to retrive data for.
	 * @param bool Set to true to return all data for the specified project.
	 */
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
	
	function merge() {
		
	}
	
	/*
	 * Get Time Period
	 * 
	 * Translates shorthand period variables (url parameter or named) to a strtotime consumable
	 * format.
	 * 
	 * For example:
	 * 3d => 3 days
	 */
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