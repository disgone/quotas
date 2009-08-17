<?php
class Quota extends AppModel {

	var $name = 'Quota';

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
			'Project' => array('className' => 'Project',
								'foreignKey' => 'project_id',
								'conditions' => '',
								'fields' => '',
								'order' => ''
			)
	);

	function getProjectQuotas($project_id) {
		$cond = array(
			'order' 		=> array('Quota.created' => 'ASC'),
			'conditions' 	=> array('Quota.project_id' => $project_id)
		);
		return $this->find('all', $cond);
	}
	
	function getLatest($project_id) {
		$cond = array(
			'order' 		=> array('Quota.created' => 'DESC'),
			'conditions' 	=> array('Quota.project_id' => $project_id)
		);
		return $this->find('first', $cond);
	}
	
	function getRange($project_id, $start = null, $end = null) {
		$start === null ? $start = date('Y-m-d') : $start;
		$end === null ? $end = date('Y-m-d', strtotime('+1 day')) : $end;
		
		$cond = array(
			'order'			=> array('Quota.created' => 'ASC'),
			'conditions'	=> array('Quota.project_id' => $project_id, 'Quota.created BETWEEN ? AND ?' => array($start, $end))
		);
		return $this->find('all', $cond);
	}
	
	function getLastFromEachDay($project_id) {
		//Since we're running a direct query, lets make sure our params are clean.
		App::import('Sanitize');
		$project_id = Sanitize::paranoid($project_id);
		
		return $this->query("SELECT b.created, Quota.consumed, Quota.allowance FROM quotas as Quota, (SELECT created FROM quotas WHERE project_id = $project_id ORDER BY created DESC) as b WHERE Quota.project_id = $project_id GROUP BY DAY(b.created) ORDER BY b.created ASC");
	}
	
	function getSingleFromDate($project_id, $date = null) {
		$date === null ? $date = date('Y-m-d') : $date;
		
		$cond = array(
			'order'			=> array('Quota.created' => 'ASC'),
			'conditions'	=> array('Quota.project_id' => $project_id, 'Quota.created >' => $date),
			'limit'			=> 1
		);
		return $this->find('all', $cond);
	}

}
?>