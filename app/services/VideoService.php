<?php
namespace app\services;

use app\services\LoggingService;

use prodigyview\media\Video;

class VideoService {
	
	/**
	 * Transcodes the media to a different format.
	 * 
	 * @param string $original_file The original file to be converted
	 * @param string $output_file The file to outputed. The format of file will be determined by the extension
	 * @param array  $options Options to be sent to the converter
	 * 
	 * @return $file
	 */
	public static function transcodeMedia($original_file, $output_file, $options = array()) {
		try {	
			return Video::convertVideoFile($original_file, $output_file, $options);
		} catch(Exception $e) {
			$data = array(
				'original_file' => $original_file,
				'output_file' => $output_file,
				'message' => $e -> getMessage(),
				'stacktrace' => $e -> getTraceAsString()
			);
			LoggingService::logsServiceAction($self, 'transcoding_failed', $data);
		}
		
		return false;
	}
	
	/**
	 * Get the length, in seconds, of the media file
	 * 
	 * @param string $file The location of the file locally
	 * 
	 * @return double The duration
	 */
	public static function getDuration($file) {
		if(file_exists($file)) {
			return exec ( 'ffprobe -i ' . $file .' -show_entries format=duration -v quiet -of csv="p=0"' );
		}
		
		return false;
	}
	
	/**
	 * Retrieves the file extension type based on the format.
	 * 
	 * @param string $type A format such as 'video/mpeg'
	 * 
	 * @return string Returns an extension such as .m2v
	 */
	public static function getFileExtension($type) {
		
		if($type === 'video/mpeg') {
			return '.m2v';
		} elseif($type === 'video/quicktime'){
			return '.mov';
		} elseif($type === 'video/vnd.mpegurl') {
			
		} elseif($type === 'video/x-msvideo'){
			return '.avi';
		} elseif($type === 'video/x-sgi-movie'){
			return '.movie';
		} elseif($type === 'video/mp4') {
			return '.mp4';
		} elseif($type === 'video/ogg') {
			return '.ogg';
		} elseif($type === 'video/webm') {
			return '.webm';
		} elseif($type === 'video/x-ms-wmv') {
			return '.wmv';
		} elseif($type === 'application/x-troff-msvideo') {
			return '.avi';
		} elseif($type === 'video/avi') {
			return '.avi';
		} elseif($type === 'video/msvideo') {
			return '.avi';
		} elseif($type === 'application/mp4') {
			return '.mp4';
		} elseif($type === 'application/vnd.rn-realmedia') {
			return '.rm';
		} elseif($type === 'video/x-ms-wmv') {
			return '.wmv';
		} elseif($type === 'video/ogg') {
			return '.ogg';
		} elseif($type === 'application/ogg') {
			return '.ogg';
		} elseif($type === 'video/webm') {
			return '.webm';
		} elseif($type === 'video/x-flv') {
			return '.flv';
		} elseif($type === 'audio/basic') {
			//return '.mpeg';
		} elseif($type === 'audio/midi'){
			return '.midi';
		} elseif($type === 'audio/mpeg') {
			 return '.mpeg';
		} elseif($type === 'audio/x-aiff'){
			return '.aif';
		} elseif($type === 'audio/x-mpegurl'){
			return '.m3u';
		} elseif($type === 'audio/x-pn-realaudio') {
			return '.ra';
		} elseif($type === 'audio/x-realaudio') {
			return '.ra';
		} elseif($type === 'audio/x-wav') {
			return '.wav';
		} elseif($type === 'application/octet-stream') {
			return '.mp3';
		}
		
		return false;
	}

	/**
	 * Downloads a remote file in an effecient way, ie stream
	 * 
	 * @param string $remote_location
	 * @param string $local_location
	 * 
	 * 
	 */
	public static function downloadRemoteFile($remote_location, $local_location) {
		exec('wget ' . $remote_location . ' -O ' .  $local_location);
	}
	
	
}
