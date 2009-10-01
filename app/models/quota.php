<?php
class Quota extends AppModel {

	var $name = 'Quota';
	var $recursive = -1;
		
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
		if(is_array($project_id)) {
			//Extract a list of project_id for this group of projects.
			$list = implode(',', $project_id);
			//Get the last recorded updates for all projects on this page.
			$query = sprintf("SELECT Quota.* FROM quotas Quota LEFT JOIN (SELECT MAX(id) id1 FROM quotas WHERE project_id IN (%s) GROUP BY project_id ORDER BY max(id) desc) t on id1 = id LEFT JOIN projects on Quota.project_id = projects.id where id1 IS NOT null ORDER BY projects.number+0 ASC", $list);
			return $this->query($query);
		}
		else {
			$cond = array(
				'order' 		=> array('Quota.created' => 'DESC'),
				'conditions' 	=> array('Quota.project_id' => $project_id),
				'limit'			=> 1
			);
			return $this->find('first', $cond);
		}
	}
	
	function getRange($project_id, $start = null, $end = null) {
		//Default to current 24 hour period if none specified.
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

	function getLastChange($project_id) {
		$cond = array(
			'fields'		=> array('Quota.*', 'DATEDIFF(now(),Quota.created) as days'),
			'order'			=> array('Quota.created' => 'DESC'),
			'conditions'	=> array('Quota.project_id' => $project_id),
			'group'			=> array('Quota.consumed'),
			'limit'			=> 1
		);
		
		return $this->find('all', $cond);
	}
	
	function getMovers($options = array()) {
		$defaults = array(
			'start'			=> date('Y-m-d 00:00:00', strtotime(date('Y-m-d'))),
			'end'			=> date('Y-m-d 23:59:59'),
			'limit'			=> 10,
			'dir'			=> 'desc'
			);
			
		$options = array_merge($defaults, $options);
			
		$query = sprintf("
					SELECT			projects.id, projects.number, projects.name, CAST(csEnd as SIGNED) - CAST(csStart as SIGNED) as movement
					FROM			(
									SELECT			Quota.project_id pid, consumed csEnd, allowance alEnd
									FROM			quotas Quota
									LEFT JOIN		latest on mid = Quota.id
									WHERE			mid IS NOT NULL
									ORDER BY		Quota.project_id
									) r
					LEFT JOIN		(
									SELECT			project_id id2, consumed csStart, allowance alStart
									FROM			quotas
									WHERE			created > '%s'
									GROUP BY		project_id
									) x on id2 = pid
					LEFT JOIN		projects ON projects.id = pid
					WHERE 			CAST(csEnd as SIGNED) - CAST(csStart as SIGNED) IS NOT null
					ORDER BY		movement %s
					LIMIT			%d
					", $options['start'], $options['dir'], $options['limit']);
		
		return $this->query($query);
	}
	
	function totalChange($options = array()) {
			$defaults = array(
				'start'			=> date('Y-m-d 00:00:00', strtotime("-1 days")),
				'end'			=> date('Y-m-d 23:59:59'),
				'limit'			=> 10,
				'dir'			=> 'desc'
				);
				
			$options = array_merge($defaults, $options);
				
			$query = sprintf("
						SELECT			SUM(CAST(csEnd as SIGNED) - CAST(csStart as SIGNED)) as difference
						FROM			(
										SELECT			Quota.project_id pid, consumed csEnd, allowance alEnd
										FROM			quotas Quota
										LEFT JOIN		latest on mid = Quota.id
										WHERE			mid IS NOT NULL
										ORDER BY		Quota.project_id
										) r
						LEFT JOIN		(
										SELECT			project_id id2, consumed csStart, allowance alStart
										FROM			quotas
										WHERE			created > '%s'
										GROUP BY		project_id
										) x on id2 = pid
						", $options['start'], $options['dir'], $options['limit']);
			
			return $this->query($query);
		}
	}
?>