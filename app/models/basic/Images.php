<?php
namespace app\models\basic;

use app\models\HModel;
use app\services\LoggingService;

/**
 * Images
 * 
 * The model handles the uploading, resizing, and assigning of images to other
 * entities.
 */
class Images extends HModel {
	
	protected $_schema = array(
		'image_id' =>  array('type' => 'int', 'primary_key' => true, 'auto_increment' => true),
		'image_original_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_large_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_large_square_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_medium_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_medium_square_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_small_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_small_square_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_thumbnail_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_thumbnail_square_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_square_url' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'image_date_created' => array('type' => 'datetime'),
		'image_transfered' => array('type' => 'tinyint', 'default' => 0),
		'entity_type' => array('type' => 'string', 'precision' => 25, 'default' => ''),
		'entity_id' => array('type' => 'int', 'not_null' => false, 'default' => null),
	);
	
	protected $_validators = array(
		'file'=>array(
			'notempty'=>array(
				'error'=>'File is required.',
				'event' => array('create')	
			),
			'is_image_file'=>array(
				'error'=>'File must be an image.',
				'event' => array('create')
			),
		),
	);
	
	protected $_storageService = 'app\services\storage\FileStorageService';
	
	protected $_localStorage = true;
	
	/**
	 * scaleImage
	 * 
	 * Scales the image to different sizes and uploads it to the cdn
	 * 
	 * @param int $width The width to scale too
	 * @param int $height The height to scale too
	 * @param string $file The path to the file
	 * @param string $save_size The size to store this image. Includes: image_large_url, image_medium_url, image_small_url
	 * @param string $save_size_square The size to store the square image. Includes: image_large_square_url, image_medium_square_url, image_small_square_url
	 * 
	 * @return void 
	 */
	public function scaleImage($width, $height, $file, $save_size, $save_size_square) {
		
		//Create CDNS @todo move to class object
		$storageProdiver = $this -> _storageService;
		$storage_service = new $storageProdiver;
		
		//Create The Image
		$image = \PVImage::scaleImage(($file) ?:$this -> image_original_url , $width, $height, array('bestfit' => true, 'return' => 'image_object'));
		$content_type = 'image/' . strtolower($image->getImageFormat());
		$extension = $this -> getExtension($content_type);
		
		//Set Save Name
		$save_location = '';
			
		if($this -> _localStorage) {
			$save_location = SITE_PATH.'/public_html/img/uploads/'.$this -> image_id.'_' . $save_size.$extension;
		} else {
			$save_location = $this -> image_id.'_' . $save_size.$extension;
		}
			
		//Upload To CDN
		$uploaded_file = $storage_service ->upload( $save_location , $image -> getImageBlob() , $content_type);
		
		if($uploaded_file) {
			$this -> update(array(
				$save_size => $uploaded_file
			));
		}
		
		//Clear image save memory
		$image -> clear();
		
		//Create The Image
		$image = \PVImage::scaleImage(($file) ?:$this -> image_original_url , $width, $height, array('bestfit' => false, 'return' => 'image_object'));
		
		//Set Save Name
		$save_location = '';
			
		if($this -> _localStorage) {
			$save_location = SITE_PATH.'/public_html/img/uploads/'.$this -> image_id.'_'. $save_size .'_sq'.$extension;
		} else {
			$save_location = $this -> image_id.'_'. $save_size .'_sq'.$extension;
		}
			
		//Upload To CDN
		$uploaded_file = $storage_service ->upload( $save_location , $image -> getImageBlob() ,$content_type);
		
		if($uploaded_file) {
			$this -> update(array(
				$save_size_square => $uploaded_file
			));	
		}
		
		//Clear Image save memory
		$image -> clear();
		
	}
	
	/**
	 * uploadImage
	 * 
	 * Uploads and processes an image to different sizes
	 * 
	 * @param string $file The location of the file, on the file system
	 * 
	 * @return mixed Returns an image if the upload was successful, otherwise returns false
	 */
	public static function uploadImage($file) {
		
		$image = new Images();
		if($image -> create(array('file' => $file))) {
			return $image;
		}
		
		return false;
	}
	
	/**
	 * fixOrientation
	 * 
	 * When images are uploaded from devices like a phone, they can be upside down. This will make sure
	 * all images are right side up.
	 * 
	 * @param string $file The file to be worked on, locations hould be on file system
	 */
	public function fixOrientation($file, $return = 'file') {
		 	
		$mime = \PVFileManager::getFileMimeType($file);
    
		$save_name = \PVTools::generateRandomString().uniqid(). $this -> getExtension($mime);
	    
	    if(!\PVValidator::check('gif_file', $mime)) {
	      $image = new \Imagick($file);
	      $orientation = $image -> getImageOrientation();
	      
	      switch($orientation) {
	            case \imagick::ORIENTATION_BOTTOMRIGHT: 
	                $image -> rotateimage("#000", 180); // rotate 180 degrees
	            break;
	    
	            case \imagick::ORIENTATION_RIGHTTOP:
	                $image -> rotateimage("#000", 90); // rotate 90 degrees CW
	            break;
	    
	            case \imagick::ORIENTATION_LEFTBOTTOM: 
	                $image -> rotateimage("#000", -90); // rotate 90 degrees CCW
	            break;
	        }
	      
	      $image -> setImageOrientation (\Imagick::ORIENTATION_TOPLEFT);
	      
	      $location = SITE_PATH.'tmp'.DS.$save_name;
		  
		  file_put_contents($location, $image -> getImageBlob());
		  
		  return $location;
	      
	    } else {
	      
	     	return $file;
	      
	    }
	}
	
	/**
	 * Basedo on the images mime_type, will return a file extension
	 * 
	 * @param string $mime_type
	 * 
	 * @return mixed Returns false or string of the mime type
	 */
	public function getExtension(string $mime_type){

    		if(\PVValidator::check('png_file', $mime_type)) {
	      return '.png';
	    } else if(\PVValidator::check('jpg_file', $mime_type)) {
	      return '.jpeg';
	    } else if(\PVValidator::check('gif_file', $mime_type)) {
	      return'.gif';
	    } else if(\PVValidator::check('bmp_file', $mime_type)) {
	      return '.bmp';
	    } else {
	    		return false;
		}

	}
	
	/**
	 * Converts the image to multiple sizes, stores it in a storage system,
	 * and places it in the image model accordingly.
	 * 
	 * @param string $file The location of the file to convert
	 * 
	 * @return void
	 */
	public function transcodeFile($file) : void{
		
		$file = $this -> fixOrientation($file);
		
		if($file) {
			
			$storageProdiver = $this -> _storageService;
			$storage_service = new $storageProdiver;
			$mime_type = \PVFileManager::getFileMimeType($file);
			$extension = $this -> getExtension($mime_type);
			$save_location = '';
			
			if($this -> _localStorage) {
				$save_location = SITE_PATH.'/public_html/img/uploads/'.$this -> image_id.$extension;
			} else {
				$save_location = $this -> image_id.$extension;
			}
			
			$uploaded_file = $storage_service ->upload( $save_location , file_get_contents($file) , $mime_type);
			
			if($uploaded_file) {
				
				$this -> update(array(
					'image_original_url' => $uploaded_file
				));	
				
				$this ->  scaleImage(800, 600, $file, 'image_large_url', 'image_large_square_url');
				$this ->  scaleImage(500, 334, $file, 'image_medium_url', 'image_medium_square_url');
				$this ->  scaleImage(240, 160, $file, 'image_small_url', 'image_small_square_url');
				$this ->  scaleImage(100, 100, $file, 'image_thumbnail_url', 'image_thumbnail_square_url');
				
				
				if(file_exists($file)) {
					unlink($file);
				}
			}
		}
	}

	/**
	 * Determines the class that will be used executing the storing of the
	 * image files.
	 * 
	 * @param string $storage_class The name of the class, full namespace
	 * @param boolean $is_local Set true if the file is being saved locall
	 * 
	 * @return void
	 */
	public function setStorage(string $storage_class,bool $is_local = true) : void {
		$this -> _storageService = $storage_class;
		$this -> _localStorage = $is_local;
	}
	
}


//Observer to execute on create
Images::addObserver('app\models\basic\Images::create', 'read_closure', function($model, $result, $id, $data, $options) {
	
	//Check to make sure the update was succesful and the event type is correct
	if($result){
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_SUCCESS, $model -> image_id);
		
		$model -> transcodeFile($data['file']);
	} else {
		//Log the user failed to be created
		LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_FAILED);
	}
	
}, array('type' => 'closure'));


//Observer to execute on CRUD update action
Images::addObserver('app\models\basic\Images::update', 'read_closure', function($model, $result, $data, $conditions, $options) {
	
	//Checks to make sure the update was a success
	if($result){
		//Log successfull update	
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_SUCCESS, $model -> image_id);
	} else {
		//Log failure to update
		LoggingService::logModelAction($model, ActionLogger::ACTION_UPDATED_FAILED, $model -> image_id);
	}
	
}, array('type' => 'closure'));

