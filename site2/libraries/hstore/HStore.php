<?php
/**
 * HStore
 * 
 * Parse or compile postgres HStore function.
 */
class HStore {
	
	/**
	 * Take an hstore string and parses into an array of elements.
	 * 
	 * @param string $data The string to be parsed
	 */
	public static function parseHStore(string $data) : array  {

		if ($data === 'NULL')
			return null;

		@eval(sprintf("\$hstore = array(%s);", $data));

		if (!(isset($hstore) and is_array($hstore))) {
			throw new Exception(sprintf("Could not parse hstore string '%s' to array.", $data));
		}

		return $hstore;
	}

	/**
	 * Compiles the a string of data into an array 
	 *
	 * @see \Pomm\Converter\ConverterInterface
	 **/
	public static function compileHStore(array $data, string $type = null) : string {
			
		if (!is_array($data)) {
			return 'hstore(array[]::varchar[])';
		}

		$insert_values = array();

		foreach ($data as $key => $value) {
			if (is_null($value)) {
				$insert_values[] = sprintf('"%s" => NULL', $key);
			} else {
				$insert_values[] = sprintf('"%s" => "%s"', addcslashes($key, '\"'), addcslashes($value, '\"'));
			}
		}

		return sprintf("%s(\$hst\$%s\$hst\$)", $type, join(', ', $insert_values));
	}
}
