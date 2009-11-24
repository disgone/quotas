<?php

class ProjectsController extends AppController {
	var $name = "Projects";
	var $helpers = array('Units');
	var $uses = array('Project', 'Quota', 'User', 'Server');
	var $components = array("RequestHandler");
	
	var $paginate = array(
		'limit' => 25,
		'order' => array('Project.number +0' => 'ASC', 'Project.name' => 'ASC'),
		'recursive' => 0
	);
	
	/*
	 * Project Index
	 * 
	 * Displays all projects, or projects belonging to a specified server.
	 * 
	 * @param int $server Name of the server to filter the projects by.
	 */
	function index($server = null) {
		$this->pageTitle = __("Project Directory", true);
		//Filter the project directory by server if specified.
		if($server) {
			$this->Server->recursive = -1;
			$server = $this->Server->find('first', array('conditions' => array('Server.name' => $server, 'Server.enabled' => 1)));
			if(!empty($server))
				$this->paginate["conditions"] = array("Project.server_id" => $server['Server']['id']);
		}
		
		$projects = $this->paginate('Project');
		
		//This queries for usage details for the projects being displayed on the page.
		if(!empty($projects)) {
			//Extract the project_id's of the projects on page X, and get the usage...
			$ids = Set::extract("/Project/id", $projects);
			$updates = $this->Quota->getLatest($ids);
			
			//..then add the usage data to the project it belongs to.
			foreach($projects as $ndx => &$project) {
				$project['Project']['Quota'] = $updates[$ndx]['Quota'];
			}
			unset($ids, $updates);
		}
		
		//Get list of projects in "My Projects" list if a user is logged in.
		$favs = $this->Session->check("User") ? $this->User->favorites($this->Session->read("User.id")) : array();
		$favs = Set::classicExtract($favs, "{n}.ProjectsUser.project_id");

		$this->set(compact('projects', 'server', 'favs'));
		$this->set('servers', $this->Server->find('all'));
	}
	
	/*
	 * Project Details
	 * 
	 * Displays the details for a specified project
	 * 
	 * @param int $id ID of project to display details for.
	 */
	function details($id = null) {
		$this->pageTitle = __("Project Details", true);
		//Throw a 404 error if ID was not specified.
		if(!$id)
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));
			
		//Grabs period time if specified in the url or a named parameter.
		//?period=3d or period:3d
		$period = $this->_getPeriod();
			
		//Get project and quota data.
		$cache = "project_" . $id . "_project_data_" . $period['duration'];
		if(($project = Cache::read($cache, 'default')) === false) {
			$project = $this->_requestProjectData($id, $period);
			//Cache::write($cache, $project, 'default');
		}
	
		//Throw a 404 error if the project with ID was not found in the database.
		if(empty($project))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));

		//Get the last time the project had a change in usage.
		$changed = $this->Quota->getLastChange($id);
		$following = $this->_isFollowing($id);

		$this->pageTitle = trim($project['Project']['number'] . " " . $project['Project']['name']);
		
		$this->set(compact('project', 'changed'));
		$this->set('following', $following);
		$this->set('quota', $project['Meta']);
		
		unset($min, $max, $start, $end, $project, $quota, $durations);
	}
	
	function view($id = null) {
		$this->pageTitle = __("Project Details", true);
		$filters = $this->_getDataFilter();
		
		$project = $this->Project->findById($id);
		
		if($filters['level'] > 1) {
			$quotas = $this->Quota->getRange($id, $filters['start'], $filters['end']);
			$project['Details']['usage'] = $this->_calcDetails($quotas);
		}
		if($filters['level'] > 2) {
			$project['Details']['quotas'] = array(Set::classicExtract($quotas, "{n}.Quota"));
		}
					
		$this->set(compact('project'));
	}
	
	/*
	 * Delete a project
	 * 
	 * Deletes a specified project (and data) from the system.
	 * 
	 * @param int $id ID of project to delete.
	 */
	function delete($id = null) {
		$this->adminOnly();
		
		$this->pageTitle = __("Delete Project", true);
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
		}
	}
	

	/*
	 * Track Project
	 * 
	 * Adds the specified project to the logged in user's "My Project" list.
	 * 
	 * @param int $id ID of project to display details for.
	 * @param string $action Add/Remove action
	 */
	function xtrack($id = null, $action = null) {
		//No id, or not numeric.
		if(!$id)
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/track'));
			
		if(!$this->Session->check('User'))
			$this->cakeError('login');

		$this->Project->id = $id;
		$project = $this->Project->read();
		if(empty($project))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/track'));
			
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
		
		App::import('Sanitize');
		Configure::write('debug', 0);
		$this->autoRender = false;
		
		$this->Project->id = $id;
		$project = $this->Project->read();
		if(empty($this->data)) {
			echo $project['Project']['name'];
		}
		else {
			if($this->Project->saveField('name', Sanitize::html($this->data['Project']['title']))) {
				echo Sanitize::html($this->data['Project']['title']);
				Cache::delete("project_" . $id);
			}
			else {
				echo Sanitize::html($project['Project']['name']);
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
		
		$this->params['url']['period'] = '1m';
		$period = $this->_getPeriod();
		
		$cache = "project_" . $id . "_quota_data_" . $period['duration'];
		if(($data = Cache::read($cache, 'default')) === false) {
			$data = $this->_requestProjectData($id, $period);
			Cache::write($cache, $data, 'default');
		}

		//Throw a 404 error if the project with ID was not found in the database.
		if(empty($data))
			$this->cakeError('missingProject', array('project_id' => $id, 'url' => 'projects/details'));

		debug($data);
		$this->set('data', $data);
		
		unset($project, $period);
	}
	
	function _getDataFilter() {
		$filters = array(
						'start'		=> date('Y-m-d 00:00:00'),
						'end'		=> date('Y-m-d 23:59:59'),
						'level'		=> 1
						);
		$options = array_merge($this->params['url'], $this->params['named']);
		
		//Date Ranges
		if(isset($options['pstart']) && $date = strtotime($options['pstart'])) {
			$filters['start'] = date('Y-m-d 00:00:00', $date);
		}
		
		if(isset($options['pend']) && $date = strtotime($options['pend'])) {
			$filters['end'] = date('Y-m-d 23:59:59', $date);
		}
		
		if(isset($options['lvl']) && is_numeric($options['lvl']))
			$filters['level'] = $options['lvl'];
			
		unset($options);

		return $filters;
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
	function _requestProjectData($id, $period = null) {
		$project = $this->Project->findById($id);
		//Project not found
		if(empty($project))
			return null;
			
		if($period) {
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

		//Get overall changes, plus add a "changed" field for each logged quota.
		$project['Meta'] = $this->_calcDetails(&$project['Quota']);
		
		return $project;
	}

	function _calcDetails($data) {
		//Import units helper
		App::import('Helper', 'Units');
		$units = new UnitsHelper();
		
		if(!isset($data[0]))
			return null;
		
		//Get maximum or minimum quota usage.
		$max = 0;
		$min = $change_min = $data[0]['Quota']['consumed'];
		
		foreach($data as $key => $quota) {
			if($quota['Quota']['consumed'] > $max)
				$max = $quota['Quota']['consumed'];
			if($quota['Quota']['consumed'] < $min)
				$min = $quota['Quota']['consumed'];

			//Calculate change from previous update.
			if($key > 0) {
				$data[$key]['Quota']['change'] = $quota['Quota']['consumed'] - $data[$key-1]['Quota']['consumed'];
				$change_min = $data[$key]['Quota']['change'] < $change_min ? $data[$key]['Quota']['change'] : $change_min;
			}
			else
				$data[$key]['Quota']['change'] = 0;
		}
	
		$quota = array(
			'current'		=> $data[count($data)-1]['Quota']['consumed'],
			'start'			=> $data[0]['Quota']['consumed'],
			'change'		=> $data[count($data)-1]['Quota']['consumed'] - $data[0]['Quota']['consumed'],
			'allowed'		=> $data[count($data)-1]['Quota']['allowance'],
			'max'			=> $max,
			'min'			=> $min,
			'unit'			=> array('changelabel' => $units->unit($change_min), 'change_index' => $units->unitIndex($change_min), 'label' => $units->unit($min), 'index' => $units->unitIndex($min)),
			'date_start'	=> $data[0]['Quota']['created'],
			'date_end'		=> $data[count($data)-1]['Quota']['created'],
		);
		
		return $quota;
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
			
			$start = date("Y-m-d 00:00:00", strtotime($value*-1 . $unit));
			$end = date("Y-m-d 23:59:59");

			return array('start' => $start, 'end' => $end, 'duration' => $duration);
		}
		
		return null;
	}
	
	function _isFollowing($project_id) {
		if($this->Session->check("User") == false)
			return false;
			
		$favs = $this->User->favorites($this->Session->read("User.id"));
		$favs = Set::classicExtract($favs, "{n}.ProjectsUser.project_id");

		return in_array($project_id, $favs);
	}
}

?>