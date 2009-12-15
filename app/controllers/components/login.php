<?php

class LoginComponent extends Object {
	var $components = array("Session", "LDAP");
	var $model;
	var $modelName;
	var $config;
	var $error;
	
	function initialize(&$controller, $settings = array()) {
		Configure::load("authentication");
		$this->config = Configure::read('Login');
		$this->model = ClassRegistry::init($this->config['model']);
		$this->modelName = $this->config['model'];
		
		$this->model->unbindModel(
			array('hasAndBelongsToMany' => array('Project'))
		);
	}
	
	function validate($data = array()) {
		if(empty($data))
			return false;
		
		foreach($this->config['authtype'] as $method) {
			switch($method) {
				case "ldap":
					$login = $this->ldapAuth($data);
					break;
				case "db":
				default:
					$login = $this->dbAuth($data);
					break;
			}
			
			if($login && $this->error == null)
				return $login;
			else if($login && $this->error)
				return false;
		}
		
		return false;
	}
	
	function ldapAuth($data) {
		if($this->LDAP->connect()) {
			//If LDAP successfully connected, check user credentials
			if(@$this->LDAP->bind($data['User'][$this->config['form_user_field']], $data['User'][$this->config['form_pass_field']])) {
				//User credentails passed, get the user details from AD.
				$ldapDetails = $this->LDAP->getUser($data['User'][$this->config['form_user_field']]);
				
				//If user details are not available its probably not a valid user account.
				if(!$ldapDetails || $ldapDetails['count'] < 1)
					return false;
				//Check if the user was already in the QT database.
				else {
					$username = preg_replace("/@" . $this->config['domain'] . "/", "", $ldapDetails['details']['userprincipalname']);
					//See if we've added the user to the DB yet.
					$details = $this->model->findByUsername($username);

					//User was not found in the QT database
					if(!empty($details)) {
						$this->model->id = $details[$this->modelName]['id'];
						$this->model->saveField('password', $this->encrypt($data[$this->modelName][$this->config['form_pass_field']], null, true));
						return $this->setSession($details);
					}
					else {
						$this->model->create();
						$this->model->set(array(
							'guid'			=> bin2hex($ldapDetails['details']['objectguid']),
							'displayname'	=> $ldapDetails['details']['displayname'],
							'username'		=> $username,
							'password'		=> $this->encrypt($data[$this->modelName][$this->config['form_pass_field']], null, true),
							'email'			=> isset($ldapDetails['details']['mail']) ? $ldapDetails['details']['mail'] : null
						));
						
						if($this->model->save()) {
							$details = $this->model->read();
							return $this->setSession($details);
						}
						else {
							return false;
						}
					}
				}
			}
			$this->LDAP->close();
		}

		return false;
	}
	
	function dbAuth($data) {
		$hash = $this->encrypt($data['User'][$this->config['form_pass_field']], null, true);
		unset($data['User'][$this->config['form_pass_field']]);
		
		$user = $this->model->find(array($this->config['src_user_field'] => $data['User'][$this->config['form_user_field']], 'password' => $hash));
		
		if(!empty($user)) {
			if($user['User']['enabled'])
				return $this->setSession($user);
			else {
				$this->error = "Your account has been locked.";
				return true;
			}
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

		if(!empty($user) && $user['User']['enabled']) {
			//Remove password so it doesn't show up in session data.
			unset($user['User']['password']);
			$this->Session->write('User', $user['User']);
			$this->Session->write('User.Group', $user['Group']);
			return $user['User'];
		}

		return false;
	}
	
	function setSession(array $user) {
		unset($user['User']['password']);
		$this->Session->write('User', $user['User']);
		$this->Session->write('User.Group', $user['Group']);
		return $user['User'];
	}
	
	function encrypt($value) {
		return Security::hash($value, null, true);
	}
	
	function isAdmin() {
		if($this->Session->read("User.Group.name") == $this->config['admin_group'])
			return true;
		else
			return false;
	}
	
}

?>