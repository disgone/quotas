<?php

class AppError extends ErrorHandler {
	
	function missingProject($params) {
		$this->controller->set('project', $params['project_id']);
		$this->_outputMessage('missing_project');
	}
	
}

?>