<?php
class Action extends AppModel {

	var $name = 'Action';

	var $belongsTo = array(
		'User' => array(
				'className' 	=> 'User',
				'foreignKey' 	=> 'user_id',
				'conditions' 	=> '',
				'fields' 		=> ''
			)
	);
	
	function log($user_id, $message) {
		$data = array(
					'user_id' => $user_id,
					'action' => $message
					);
					
		return $this->save($data);
	}
}
?>