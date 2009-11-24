<?php

class AjaxResponseComponent extends Object {
	var $error;
	var $message;
	var $success = true;
	var $data = array();
	
	function response() {
		$data = array(
			'success' 	=> $this->success
			);
			
		if($this->error)
			$data['error'] = $this->error;
			
		if($this->data)
			$data['data'] = $this->data;
			
		return $data;
	}
	
	function error($message, $triggerFail = true) {
		if($this->success)
			$this->error = $message;
			
		if($triggerFail === true)
			$this->success = false;
	}
	
	function message($message) {
		$this->message = $message;
	}
	
	function set($var, $data) {
		$this->data[$var] = $data;
	}
	
	function delete($var) {
		unset($this->data[$var]);
	}
}

?>