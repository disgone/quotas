<?php
class UsersController extends AppController {
	var $name = 'Users';
	var $components = array("Security", "Cookie");

	function login() {
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
				$this->Session->setFlash("Invalid username or password", "flash/error");
			}
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
	
	function register() {
		
	}
	
}
?>