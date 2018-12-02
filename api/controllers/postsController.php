<?php

include('apiController.php');

class postsController extends apiController {
	
	protected $_model = 'app\models\mongo\Posts';
	
	protected $_id = '_id';
	
	
}
