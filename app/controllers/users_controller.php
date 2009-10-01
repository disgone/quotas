<?php
class UsersController extends AppController {
	var $name = 'Users';
	var $components = array("Security");

	function login() {
		$this->User->set($this->data);
		
		if($this->data && $this->User->validates()) {
			if($user = $this->_validateLogin($this->data)) {
				$this->Session->setFlash("Logged in successfully.", "flash/success");
				$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
			}
			else {
				$this->Session->setFlash("Invalid username or password", "flash/error");
			}
		}
	}
	
	function logout() {
		$this->Session->destroy();
		$this->Session->setFlash("You have been successfully logged out.", "flash/success");
		$this->redirect(array('controller' => 'projects', 'action' => 'index'));
	}
	
	function _validateLogin($data) {
		$hash = Security::hash($this->data['User']['password'], null, true);
		unset($this->data['User']['password']);
		
		$this->User->unbindModel(
			array('hasAndBelongsToMany' => array('Project'))
		);
		
		$user = $this->User->find(array('email' => $this->data['User']['email'], 'password' => $hash));
		if(empty($user) == false) {
			//Remove password so it doesn't show up in session data.
			unset($user['User']['password']);
			$this->Session->write('User', $user['User']);
			$this->Session->write('User.Group', $user['Group']);
			return $user['User'];
		}
		
		return null;
	}
}
?>