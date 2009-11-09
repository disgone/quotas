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
		$query = sprintf("
						SELECT Server.name, SUM(Quota.consumed) as consumed, SUM(Quota.allowance) as allowance, AVG(Quota.consumed) as average_consumed, AVG(Quota.allowance) as average_quota
						FROM latest
						LEFT JOIN quotas Quota ON Quota.id = latest.mid
						LEFT JOIN projects Project on Project.id = Quota.project_id
						LEFT JOIN servers Server ON Server.id = Project.server_id
						WHERE Project.server_id IS NOT null
						AND Quota.created > '%s'
						GROUP BY Project.server_id
						ORDER BY Server.name ASC
		", strtotime('Y-m-d'));
	 
		return $this->query($query);
	}
}
?>