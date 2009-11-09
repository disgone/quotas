<?php

class LDAPComponent extends Object {
	private $servers;
	private $domain;
	private $connection = false;
	private $search_path;

	function initialize(&$controller, $settings = array()) {
		Configure::load("authentication");
		$this->servers = Configure::read('Login.auth_servers');
		$this->domain = Configure::read('Login.domain');
		$this->search_path = Configure::read('Login.ldap_search_path');
	}
	
	function connect() {
		if(!$this->_ldapEnabled()) {
			$this->log("LDAP plugin must be installed to use LDAP authentication.");
			return null;
		}
		
		$i = 0;
		do {
			$this->connection = ldap_connect($this->servers[$i]);
			$i++;
		} while($this->connection === false && $i < count($this->servers));
		
		return $this->connection !== false ? true : false;
	}
	
	function bind($user, $pass) {
		if(strlen(trim($pass)) < 0 || strlen(trim($user)) < 0)
			return false;
		return ldap_bind($this->connection, $user. "@" . $this->domain, $pass);
	}
	
	function getUser($username) {
		$query = ldap_search($this->connection, $this->search_path, "(&(samaccountname=$username))");
		$result = ldap_get_entries($this->connection, $query);
		if($result['count'] > 0)
			return $this->_cleanLDAPResults($result);
		return null;
	}
	
	function close() {
		ldap_close($this->connection);
	}
	
	function _cleanLDAPResults($data) {
		$results = array('count' => $data['count']);
		$results['details'] = array();
		foreach($data[0] as $key => $value) {
			if(isset($value[0]) && !is_numeric($key))
				$results['details'][$key] = $value[0];
		}
		
		return $results;
	}
	
	function _ldapEnabled() {
		if(!function_exists('ldap_connect'))
			return false;
		
		return true;
	}
}

?>