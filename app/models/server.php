<?php
class Server extends AppModel {
	 
	var $name = 'Server';
	var $order = array("Server.name" => 'ASC');
	 
	var $hasMany = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'server_id',
			'order' => 'Project.number+0 ASC'
		)
	);
	 
	function getUsage() {
		$query = "SELECT * FROM server_stats";
	 
		return $this->query($query);
	}
}
?>