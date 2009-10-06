<?php
class User extends AppModel {

	var $name = 'User';
	var $validate = array(
		'email' 		=> array(
								'email'	=> array(
									'rule' 		=> 'email',
									'required' 	=> true,
									'message' 	=> 'Invalid email addresss'
									),
								'unique' => array(
									'rule'		=> 'isUnique',
									'on'		=> array('create'),
									'message'	=> 'Account already exists with that email address.'
									)
								),
		'password' 		=> array(
								'notEmpty'	=> array(
									'rule' 		=> 'notEmpty',
									'required' 	=> true,
									'message' 	=> 'A password is required'
									),
								'minLength'	=> array(
									'rule' 		=> array('minLength', 6),
									'required' 	=> true,
									'on'		=> array('create'),
									'message' 	=> 'Passwords must be at least 6 characters'
									),
								'matches'	=> array(
									'rule'		=> 'confirmPassword',
									'message'	=> 'Password does not match',
									'on'		=> array('create')
									)
								),
		'confirm'		=> array(
								'notEmpty' => array(
									'rule'		=> 'notEmpty',
									'required'	=> true,
									'on'		=> array('create'),
									'message'	=> 'You must confirm your password.'
									)
								),
		'displayname' 	=> array('notempty')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasAndBelongsToMany = array(
		'Project' => array(
			'className'					=> 'Project',
			'joinTable'					=> 'projects_users',
			'foreignKey'				=> 'user_id',
			'associationForeignKey' 	=> 'project_id',
			'unique'					=> false,
			'order'						=> array('Project.number +0' => 'ASC', 'Project.name' => 'ASC')
		)
	);
	
	function confirmPassword($data) {
		$valid = $data['password'] == $this->data['User']['confirm'] ? true : false; 
		return $valid;
	}
}
?>