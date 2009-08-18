<?php
class Project extends AppModel {

	var $name = 'Project';
	var $order = array("Project.number + 0" => 'ASC', "Project.name" => "ASC");
	var $recursive = 0;

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Quota' => array('className' => 'Quota',
								'foreignKey' => 'project_id',
								'dependent' => false,
								'conditions' => '',
								'fields' => '',
								'order' => '',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			)
	);


}
?>