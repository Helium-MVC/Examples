<?php
/**
 * Important Explanation
 * 
 * All the models in Helium use Cache when the caching is enabled. Cache by default
 * used the file system - which has limitations.
 * 
 * For this site, if you check in the libraries/RedisCache, you will what is known as an
 * adapter. This adapter replaces the functions of Cache to use Redis instead of the 
 * default file system.
 * 
 * See the controller/logsController.php for cached results example
 */
use prodigyview\util\Cache;
use prodigyview\system\Configuration;

$redis = Configuration::getConfiguration('redis');

Cache::init(array(
	'host' => $redis -> host,
	'port' => $redis -> port
));