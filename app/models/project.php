<?php
class Project extends AppModel {

	var $name = 'Project';
	var $order = array("Project.number + 0" => 'ASC', "Project.name" => "ASC");
	var $recursive = 0;

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'Quota' => array(
			'className' 	=> 'Quota',
			'foreignKey' 	=> 'project_id',
			'dependent' 	=> false,
			'conditions'	=> '',
			'fields' 		=> '',
			'order' 		=> '',
			'limit' 		=> '',
			'offset' 		=> '',
			'exclusive' 	=> '',
			'finderQuery' 	=> '',
			'counterQuery' 	=> ''
		)
	);

	var $belongsTo = array(
		'Server' => array(
			'className'		=> 'Server',
			'foreignKey'	=> 'server_id'
		)
	);
	
	function getNewProjects($server_id = null, $date = null) {
		$date == null ? $date = date('Y-m-d') : null;
		$cond = array(
			'conditions'	=> array('Project.created >' 	=> $date),
			'order' 		=> array('Project.number + 0' => 'ASC', 'Project.name' => 'ASC')
			);
		
		if($server_id) {
			array_push($cond['conditions'], array('Project.server_id' => $server_id));
		}
		
		return $this->find('all', $cond);
	}
}
?>