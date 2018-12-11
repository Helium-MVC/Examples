<?php

use PHPUnit\Framework\TestCase;

class UserTests extends TestCase {
	private $model = 'app\models\basic\Users';

	private $instance = null;

	protected function setUp() {

		app\models\basic\Users::clearObservers('app\models\basic\Users::create');
		$model = $this->model;
		$this->instance = new $model();
	}

	protected function tearDown() {
		//$this->calculator = NULL;
	}

	public function testCreateNoData() {
		$result = $this->instance->create(array());
		$this->assertFalse($result);
	}
	
	public function testCreateNoEmail() {
		$result = $this->instance->create(array());
		$this->assertFalse($result);
	}

}
