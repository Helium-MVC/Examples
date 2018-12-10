<?php

$_SERVER['HTTP_HOST'] = null;

//Define Directory Seperator
define('DS', DIRECTORY_SEPARATOR);
//Define Prodigy View ROot
define('PV_ROOT', './');
//Define heliums root
define('HELIUM', PV_ROOT . DS . 'vendor' . DS . 'prodigyview' . DS . 'helium' . DS);
//Set to site path
define('SITE_PATH', dirname(dirname(dirname(__FILE__))) . DS);
//Set the location of the public folder
define('PUBLIC_HTML', SITE_PATH . DS . 'public_html' . DS);
//Set the location of  local libraries
define('PV_LIBRARIES', SITE_PATH . DS . 'libraries' . DS);
//Define Template Directory
define('PV_TEMPLATES', SITE_PATH . DS . 'templates' . DS);
//Set the temp directory
define('PV_TMP', PUBLIC_HTML . 'tmp' . DS);

include (PV_ROOT . DS . 'vendor' . DS . 'autoload.php');

/*** include the controller class ***/
include HELIUM . 'controller.class.php';

/*** include the registry class ***/
include HELIUM . 'registry.class.php';

/*** include the router class ***/
include HELIUM . 'router.class.php';

/*** include the template class ***/
include HELIUM . 'template.class.php';

/*** include the template class ***/
include HELIUM . 'model.class.php';

/*** include the template class ***/
include HELIUM . 'app.class.php';

include HELIUM . 'console.class.php';

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

include ('./vendor/autoload.php');

include SITE_PATH . 'config/bootstrap.php';

use PHPUnit\Framework\TestCase;

class UserTests extends TestCase {
	private $model = 'app\models\basic\Users';

	private $instance = null;

	protected function setUp() {
		
		
		$_SERVER['argv'] = array();

		prodigyview\helium\HeliumConsole::init();

		$model = $this->model;
		$this->instance = new $model();
	}

	protected function tearDown() {
		//$this->calculator = NULL;
	}

	public function testCreateNoData() {
		$result = $this->instance->create(array());
		$this->assertFalse(3, $result);
	}

}
