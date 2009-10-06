<?php

class LoginComponent extends Object {
	var $components = array("Session");
	var $model;
	
	function initialize(&$controller, $settings = array()) {
		$this->model = ClassRegistry::init('User');
	}
	
	function validate($data = array()) {
		if(empty($data))
			return false;
			
		$hash = $this->encrypt($data['User']['password'], null, true);
		unset($data['User']['password']);
		
		$this->model->unbindModel(
			array('hasAndBelongsToMany' => array('Project'))
		);
		
		$user = $this->model->find(array('email' => $data['User']['email'], 'password' => $hash));
		if(empty($user) == false) {
			//Remove password so it doesn't show up in session data.
			unset($user['User']['password']);
			$this->Session->write('User', $user['User']);
			$this->Session->write('User.Group', $user['Group']);
			return $user['User'];
		}
		
		return false;
	}
	
	function cookieLogin($uid) {
		if(!is_numeric($uid)) {
			return false;
		}
		
		$this->model->unbindModel(
			array('hasAndBelongsToMany' => array('Project'))
		);
		
		$this->model->id = $uid;
		$user = $this->model->read();

		if(empty($user) == false) {
			//Remove password so it doesn't show up in session data.
			unset($user['User']['password']);
			$this->Session->write('User', $user['User']);
			$this->Session->write('User.Group', $user['Group']);
			return $user['User'];
		}
		
		return false;
	}
	
	function encrypt($value) {
		return Security::hash($value, null, true);
	}
	
}

?>