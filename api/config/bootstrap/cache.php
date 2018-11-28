<?php
/**
 * Important Explanation
 * 
 * All the models in Helium use PVCache when the caching is enabled. PVCache by default
 * used the file system - which has limitations.
 * 
 * For this site, if you check in the libraries/RedisCache, you will what is known as an
 * adapter. This adapter replaces the functions of PVCache to use Redis instead of the 
 * default file system.
 * 
 * See the controller/logsController.php for cached results example
 */

$redis = PVConfiguration::getConfiguration('redis');

PVCache::init(array(
	'host' => $redis -> host,
	'port' => $redis -> port
));