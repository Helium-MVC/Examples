<?php

namespace app\services\storage;

interface StorageInterface {
	
	public function upload($file_name, $content);
	
}
