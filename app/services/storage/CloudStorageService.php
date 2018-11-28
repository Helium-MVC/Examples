<?php
namespace app\services;

use Aws\S3\S3Client;
use Aws\Common\Credentials\Credentials;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

/**
 * StorageService
 * 
 * The service responsible for communicating with the CDN for the story of assets
 */
class StorageService implements StorageInterface {
	
	protected $_key = null;
	
	protected $_secret = null;
	
	protected $_bucket = null;
	
	protected $_acl = 'public-read';
	
	public function __construct($key = null, $secret = null, $bucket = null, $acl = 'public-read') {
		$this -> _key = ($key) ?: \PVConfiguration::getConfiguration('s3') -> key;
		$this -> _secret = ($secret) ?: \PVConfiguration::getConfiguration('s3') -> secret;
		$this -> _bucket = ($bucket) ?: \PVConfiguration::getConfiguration('s3') -> bucket;
		$this -> _acl = ($acl) ?: \PVConfiguration::getConfiguration('s3') -> acl;
	}
	
	/**
	 * sendToStorage
	 * 
	 * Sends a file to the cdn for storage.
	 * 
	 * @param string $object The key to reference the image
	 * @param string $body The content to be uploaded
	 * @param string $acl Access priviliges on AWS
	 * @param string $contenty_type Assign a content type
	 * 
	 * @return string Returns a url where the object was saved
	 */
	public function upload($object, $body, $content_type = null) {
		
		//$credentials = new Aws\Credentials\Credentials(\PVConfiguration::getConfiguration('aws') -> access_id, \PVConfiguration::getConfiguration('aws') -> access_key);
		
		$s3 = S3Client::factory(array(
			'credentials' => array(
				'secret' => $this -> _secret,
				'key' => $this -> _key
			),
			'region' => 'us-west-2',
			'version' => '2006-03-01',
			'timeout' => 12000,
		));;
		
		try {
			
			if(strlen($body) > 255 || !file_exists($body)) {
				
				$result = $s3->putObject(array(
				    'Bucket'       => $this ->_bucket,
				    'Key'          => $object,
				    'Body'   => $body,
				    'ContentType'  => $content_type,
				    'ACL'          => $this ->_acl,
				));
			
				return $result['ObjectURL'];
			
			} elseif(file_exists($body) && filesize($body) < 2000000000) {
				
				$result = $s3->putObject(array(
				    'Bucket'       => $this -> _bucket,
				    'Key'          => $object,
				    'SourceFile'   => $body,
				    'ContentType'  => $content_type,
				    'ACL'          => $this ->_acl,
				    'curl.options' => array(
       					CURLOPT_TIMEOUT => 15000,
    					)
				));
			
				return $result['ObjectURL'];
			
			} else {
				
				$uploader = new MultipartUploader($s3, $body, [
			    		'bucket' => $bucket,
			    		'key'    => $object,
			    		'acl'    => $this ->_acl,
			    		'concurrency' => 2,
			    		'part_size' => (50 * 1024 * 1024),
			    		'before_initiate' => function(\Aws\Command $command) use ($content_type) {
	        				$command['ContentType'] = $content_type;
	    				}
				]);
				
					
				$result = $uploader->upload();
	
				return (isset($result['Location'])) ? $result['Location'] : false;
			}
			
		} catch (MultipartUploadException $e) {
			$data = array(
				'bucket' => $bucket,
				'message' => $e -> getMessage(),
				'stacktrace' => $e -> getTraceAsString()
			);
			LoggingService::logsServiceAction($this, 'upload_failed', $data);
					   
		} catch (Aws\Exception\S3Exception $e) {
		   $data = array(
				'bucket' => $bucket,
				'message' => $e -> getMessage(),
				'stacktrace' => $e -> getTraceAsString()
			);
			LoggingService::logsServiceAction($this, 'upload_failed', $data);
		   
		} catch (Exception $e) {
		  $data = array(
				'bucket' => $bucket,
				'message' => $e -> getMessage(),
				'stacktrace' => $e -> getTraceAsString()
			);
			LoggingService::logsServiceAction($this, 'upload_failed', $data);
		}
		
		return false;
	}
	
}
