<?php
class ProjectsUser extends AppModel {

	var $name = 'ProjectsUser';
	
	var $validate = array(
		'user_id' 		=> array(
							'rule'			=> 'numeric',
							'required'		=> true,
							'allowEmpty'	=> false,
							'message'		=> 'Invalid user.'
							),
		'project_id' 	=> array(
							'rule'			=> 'numeric',
							'required'		=> true,
							'allowEmpty'	=> false,
							'message'		=> 'Invalid project.'
							)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'User' => array(
			'className' 	=> 'User',
			'foreignKey' 	=> 'user_id',
			'conditions' 	=> '',
			'fields' 		=> '',
			'order'			=> ''
		),
		'Project' => array(
			'className' 	=> 'Project',
			'foreignKey' 	=> 'project_id',
			'conditions' 	=> '',
			'fields' 		=> '',
			'order' 		=> ''
		)
	);

}
?>