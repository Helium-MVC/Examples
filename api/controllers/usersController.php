<?php

include('apiController.php');

class usersController extends apiController {
	
	protected $_model = 'apps\models\mongo\users';
	
	protected $_id = 'user_id';
	
	
}
