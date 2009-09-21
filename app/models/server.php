<?php
class Server extends AppModel {

	var $name = 'Server';

	var $hasMany = array(
		'Project' => array(
			'className'		=> 'Project',
			'foreignKey'	=> 'server_id',
			'order'			=> 'Project.number+0 ASC'
		)
	);
}
?>