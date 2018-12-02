<?php

include('apiController.php');

class usersController extends apiController {
	
	protected $_model = 'app\models\mongo\Users';
	
	protected $_id = '_id';
	
	
}
