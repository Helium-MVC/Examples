<?php
/**
 * A special extension designed for incoproating AngularJS
 */
class Angular {
	
	/**
	 * Data can be pushed to angular with ng-init. This feature will ensure characters
	 * do not break angular.
	 * 
	 * @param string $string The string to escape
	 * 
	 * @return $string
	 */
	public function escape($string) {
		
		return htmlentities(str_replace('\'', "\'", $string));
	}
}
