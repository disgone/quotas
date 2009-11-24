<?php
class UsersController extends AppController {
	var $name = 'Users';
	var $components = array("Security", "Cookie", "AjaxResponse");

	function login() {
		$this->pageTitle = "Log In";
		$this->User->set($this->data);
		
		if($this->data && $this->User->validates()) {
			if($user = $this->Login->validate($this->data)) {
				if($this->data['User']['remember_me']) {
					$cookie = array();
					$cookie['uid'] = $user['id'];
					$this->Cookie->write('User.pk', $cookie, true, "+2 weeks");
				}
				$this->Session->setFlash("Logged in successfully.", "flash/success");
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
			else {
				$this->data['User']['password'] = "";
				$this->Session->setFlash($this->Login->error ? $this->Login->error : "Invalid username or password.", "flash/error");
			}
		}
		else if($this->data && !$this->User->validates()) {
			$this->data['User']['password'] = "";
			$this->Session->setFlash("Username/password could not be authenticated.", "flash/error");
		}
		
		//Check for "Remember me" cookie.
		if(empty($this->data)) {
			$cookie = $this->Cookie->read('User.pk');
			if($cookie !== null) {
				$this->Login->cookieLogin($cookie['uid']);
			}
		}
	}
	
	function logout() {
		$this->Session->destroy();
		$this->Session->setFlash("You have been successfully logged out.", "flash/success");
		$this->Cookie->del('User.pk');
		$this->redirect(array('controller' => 'projects', 'action' => 'index'));
	}
	
	function admin_index() {
		$this->adminOnly();

		$users = $this->User->find('all');
		$this->set(compact('users'));
	}
	
	function track_project() {
		$params = array_merge($this->params['url'], $this->params['form']);
		$project_id = isset($params['project_id']) ? $params['project_id'] : $this->params['project_id'];
		$method = isset($params['method']) ? $params['method'] : $this->params['method'];
			
		if($this->RequestHandler->isAjax()) {
			$data['success'] = true;
			
			if($this->Session->check("User") == false)
				$this->AjaxResponse->error("You must be logged in.");
			else if(!$project_id)
				$this->AjaxResponse->error("Missing project id.");	
			else if($method == "remove" && ($err = $this->_setTracker($project_id, $method)) !== true)
				$this->AjaxResponse->error($err);
			else if($method == "add" && ($err = $this->_setTracker($project_id, $method)) !== true)
				$this->AjaxResponse->error($err);

			$this->set('data', $this->AjaxResponse->response());
		}
		else {
			if($this->Session->check("User") == false)
				$this->cakeError('login');
				
			$project = $this->User->Project->find('first', array('conditions' => array('Project.id' => $project_id)));
			if($this->_setTracker($project_id, $method) == true) {
				$this->Session->setFlash(sprintf("Project %s has been %s to your My Projects list.", $project['Project']['number'], $method == "add" ? "added" : "removed"),  "flash/success");
			}
			else {
				$this->Session->setFlash(sprintf("Project %s could not be %s to your My Projects list.", $project['Project']['number'], $method == "add" ? "added" : "removed"),  "flash/error");
			}
			
			$this->redirect($this->referer());
		}
	}
	
	function _setTracker($project_id, $action) {
		//Get record if it exists.
		$record = $this->User->ProjectsUser->find('first', array('conditions' => array('ProjectsUser.user_id' => $this->Session->read('User.id'), 'ProjectsUser.project_id' => $project_id)));

		if($action == 'add') {
			App::import('Model', 'Project');
			$Project = new Project();
			if(!$Project->findById($project_id)) {
				return "Error: Invalid project.";
			}
			//If the record doesn't already exist and the project is legit, associate the project with the user.
			if(empty($record)) {
				$this->User->ProjectsUser->create(array('user_id' => $this->Session->read('User.id'), 'project_id' => $project_id));
				if($this->User->ProjectsUser->save())
					return true;
				else
					return "Error: Project could not be added.";
			}
			else
				return "Error: Project already added.";
		}
		else {
			if(!empty($record)) {
				if($this->User->ProjectsUser->delete($record['ProjectsUser']['id']))
					return true;
				else
					return "Error: Project was not removed.";
			}
			else
				return true;
		}
		
		return false;
	}
}
?>