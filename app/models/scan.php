<?php
class Scan extends AppModel {

	var $name = 'Scan';

	var $belongsTo = array(
		'Server' => array(
				'className' 	=> 'Server',
				'foreignKey' 	=> 'server_id',
				'conditions' 	=> '',
				'fields' 		=> ''
			)
	);
}
?>