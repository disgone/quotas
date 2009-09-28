<?php

class LighterHelper extends AppHelper {
	var $prefix = "<strong>";
	var $suffix = "</strong>";
	
	function hl($line, $term = null) {
		$term = trim($term);
		if($term == null || $term == "")
			return null;
		else {
			return preg_replace("/($term)/i", $this->prefix . "$1" . $this->suffix, $line);
		}
	}
}

?>