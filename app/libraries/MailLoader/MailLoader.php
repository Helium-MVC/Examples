<?php
/**
 * MailLoader
 * 
 * A specialized function for loading email templates to be sent to users.
 */
class MailLoader {

	/**
	 * Loads the message for html
	 */
	public static function loadHtml($name, array $data = array()) : string {

		$file = PV_ROOT . DS . 'app' . DS . 'emails' . DS . $name . '.html.php';

		foreach ($data as $key => $value) {
			$$key = $value;
		}

		ob_start();

		include ($file);

		$content = ob_get_contents();

		ob_end_clean();

		return $content;
	}

	public static function loadText($name, $data = array()) : string {
		$file = PV_ROOT . DS . 'app' . DS . 'emails' . DS . $name . '.text.php';

		foreach ($data as $key => $value) {
			$$key = $value;
		}

		ob_start();

		include ($file);

		$content = ob_get_contents();

		ob_end_clean();

		return $content;

	}

}
