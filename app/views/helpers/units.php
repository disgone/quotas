<?php

class UnitsHelper extends AppHelper {
	var $denom = array('B', 'KB', 'MB', 'GB', 'TB');
	
	/**
	 * Formats a number into the smallest container.
	 * IE 15,728,640B = 15MB
	 * 
	 * @param int $size Size to format.
	 * @param bool $showUnits Append unit marker to formatted size.
	 * @param int $digits Number of digits to round values to.
	 * @return int|string
	 */
	function format($size, $showUnits = true, $digits = 2) {
		//Simplifies the size into the smallest container and returns the index.
		$unit = $this->_getUnits($size);
		//Calculate the value for the specified size, IE 1024kb = 1mb
		$value = round($size/pow(1024,$unit), $digits);
		//Appends the units to the value.
		$showUnits ? $value .= ' ' . $this->denom[$unit] : $value;
		
		return $value;
	}
	
	/**
	 * Converts to a specific data type.
	 * convertTo('9699847', 'MB', 0, 3) would return 9.25 (in MB).
	 * 
	 * @param int $size Size to convert.
	 * @param string|int $unit Unit to convert size to.
	 * @param int $index Unit 
	 * @param int $digits
	 * @return unknown_type
	 */
	function convertTo($size, $unit = 'MB', $digits = 2, $index = 0) {
		$sign = $size < 0 ? -1 : 1;
		$size = abs($size);
		
		if(is_string($unit)) {
			$unit = strtoupper($unit);
			if($ndx = array_search($unit, $this->denom))
				$unit = $ndx;
		}
		else if(is_numeric($unit)) {
			if($unit >= count($this->denom))
				$unit = 2;
		}
		else
			$unit = 2;
			
		while($index < $unit) {
			$size = round($size/1024, $digits);
			$index++;
		}
		
		return $size * $sign;
	}
	
	/**
	 * Returns the unit abbreviation for the size (bytes [0] by default) parameter.
	 * IE 1024B = 1MB, which returns MB
	 * 
	 * @param int $size Size to determine unit for
	 * @param int $index Unit index to begin at
	 * @return string Unit abbreviation
	 */
	function unit($size, $index = 0) {
		return $this->denom[$this->_getUnits($size)];
	}
	
	/**
	 * Returns the unit index for the size (bytes [0] by default) parameter.
	 * IE 1024B = 1MB, which returns 2
	 * 
	 * @param int $size Size to determine unit for
	 * @param int $index Unit index to begin at
	 * @return string Unit abbreviation
	 */
	function unitIndex($size, $index = 0) {
		return $this->_getUnits($size);
	}

	/**
	 * Takes a size (bytes [0] by default) and converts it to it's smallest container and returns the unit index.
	 * IE 15,728,640B = 15MB, which would return 2
	 *
	 * @access protected
	 * @see UnitsHelper::$denom
	 * @param int $size Size to get unit index for
	 * @param int $index Unit index to start at (defaults to bytes)
	 * @return int Unit index in UnitsHelper
	 */
	function _getUnits($size, $index = 0) {
		$size = abs($size);
			
		if($size > 999) {
			return $this->_getUnits($size/1024, ++$index);
		}
		
		return $index;
	}
	
}

?>