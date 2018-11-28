<?php

class Angular {
	
	public function escape($string) {
		
		return htmlentities(str_replace('\'', "\'", $string));
	}
}
