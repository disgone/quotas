<?php
class Project extends AppModel {

	var $name = 'Project';
	var $order = array("Project.number" => 'ASC', "Project.name" => "ASC");
	var $recursive = 1;
	
	var $hasMany = array(
		'Quota' => array(
			'className' 	=> 'Quota',
			'foreignKey' 	=> 'project_id',
			'dependent' 	=> false,
			'limit'			=> 1,
			'finderQuery'	=> 'SELECT Quota.* FROM quotas Quota LEFT JOIN (SELECT MAX(id) id1 FROM quotas WHERE project_id = {$__cakeID__$} GROUP BY project_id ORDER BY max(id) desc) t on id1 = id LEFT JOIN projects on Quota.project_id = projects.id where id1 IS NOT null'
		)
	);

	var $belongsTo = array(
		'Server' => array(
			'className'		=> 'Server',
			'foreignKey'	=> 'server_id',
			'fields'		=> array('Server.id', 'Server.name')
		)
	);

	var $hasAndBelongsToMany = array(
		'User' => array(
			'className'					=> 'User',
			'joinTable'					=> 'projects_users',
			'foreignKey'				=> 'user_id',
			'associationForeignKey' 	=> 'project_id',
			'unique'					=> false
		)
	);
	
	function search($token) {
		$cond = array(
			'conditions'	=> array('Project.status' => 1, array('or' => array('Project.number LIKE' => "%$token%", 'Project.name LIKE' => "%$token%"))),
			'order'			=> array('Project.number' => 'ASC', 'Project.name' => 'ASC')
			);
			
		return $this->find('all', $cond);
	}
	
	function getNewProjects($server_id = null, $date = null, $limit = 10) {
		$date == null ? $date = date('Y-m-d') : null;
		$cond = array(
			'conditions'	=> array('Project.created >' 	=> $date),
			'order' 		=> array('Project.created' => 'ASC', 'Project.number + 0' => 'ASC', 'Project.name' => 'ASC'),
			'limit'			=> $limit
			);
		
		if($server_id) {
			array_push($cond['conditions'], array('Project.server_id' => $server_id));
		}
		
		return $this->find('all', $cond);
	}
	
	/*
	 * Returns projects with duplicate numbers
	 * 
	 * Projects should have unique numbers per server, so this returns a list of projects that have been mislabeled or duplicated.
	 */
	function getDupes() {
		$cond = array(
			'fields'		=> array('Project.number'),
			'group'			=> array('Project.number HAVING COUNT(*) > 1'),
			);

		//Get dupes in list form so we can pull them by project number.
		$dupes = $this->find('list', $cond);
		return $this->find('all', array('conditions' => array('Project.number' => $dupes)));
	}

}
?>