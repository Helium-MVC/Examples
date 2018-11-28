<?php
/**
 * CacheLite is the pear extension that allows the caching of information on a server. This class can be used
 * as a standalone cache interface or can be adapted to work in
 */
 
class RedisCache {
	
	protected static $_redis = null;
	
	protected static $_peristent = null;
	
	protected static $_cache_expire = null;
	
	/**
	 * Initialize the cache lite object. This method will instantiate an instance of
	 * cache list that will be used in the other methods for interfacing with the cache.
	 * 
	 * @param array $config Options that define how the works
	 * 			-'cacheDir' _string_: The directory to store the cache in
	 * 			-'lifeTime' _int_: The time in seconds for how long the cache will last
	 * 			-'pearErrorMode' _constant_: How PEAR will handle errors with the case
	 * 			-'group' _string_: The default group associated with cache when writing and reading
	 * 
	 * @return void
	 * @access public
	 */
	public static function init(array $config = array()) {
		
		$defaults = array(
		    'host' => '127.0.0.1',
		    'port' => 6379,
		    'timeout' => 2.5,
		    'peristent' => false,
		    'auth' => null,
		    'cache_expire' => 300, 
		);
		
		$config += $defaults;
		
		self::$_redis = new Redis();
		
		self::$_peristent = $config['peristent'];
		self::$_cache_expire = $config['cache_expire'];
		
		if($config['peristent']) {
			self::$_redis -> pconnect($config['host'], $config['port'], $config['timeout']);
		} else {
			self::$_redis -> connect($config['host'], $config['port'], $config['timeout']);
		}
		
		if($config['auth']) {
			self::$_redis ->auth($config['auth']);
		}
		
	}
	
	/**
	 * Writes content to pear cache..
	 * 
	 * @param string $key The key to be used when refering to the cache
	 * @param string $content The content to be stored in the cache
	 * @param array $options Options for customzing the saving of cache
	 * 			-'group' _string_: 
	 * 
	 * @return boolean Returns true if the cache saved successfully
	 * @param public
	 */
	public static function writeCache($key, $content, $options = array()) {
		
		$defaults = array('cache_expire' => self::$_cache_expire);
		$options += $defaults;
		
		if (is_array($content) || is_object($content)) {
			$content = serialize($content);
		}
		
		if(self::$_peristent) {
			return self::$_redis -> psetex($key, $options['cache_expire'], $content);
		} else {
			return self::$_redis -> setex($key, $options['cache_expire'], $content);
		}
	}
	
	/**
	 * Read the data that is stored in cache and assoicated with an id and a group
	 * 
	 * @param string $key The name for referencing a certain cache
	 * @param array $options Options for customizing the reading of cache
	 * 			-'group' _string_: The group the cached was saved with.
	 * 			-'doNotTestCacheValidity' _boolean_: ????
	 * 	
	 * @return mixed Returns content if content is associated witht he key
	 * @access public
	 */
	public static function readCache($key, $options = array()) {
		
		$content = self::$_redis -> get ( $key);
		
		$data = @unserialize($content);
		if ($data !== false || $content === 'b:0;')
			$content = $data;
		
		return $content;
	}
	
	/**
	 * @todo figure out a way to get expirated date with Cache_list
	 */
	public static function hasExpired($key, $options = array()) {
		return !(self::$_redis->exists($key));
	}
	
	/**
	 * @todo figure out a way to get expirated date with Cache_list
	 */
	public static function getExpiration($key, $options = array()) {
		
	}
	
	/**
	 * Removes a value from cache and also has the ability to remove all the cache
	 * if the 'clear' option is set.
	 * 
	 * @param string $key The reference to the cache to remove
	 * @param array $options Options for clearing the cache
	 * 		-'group' _string_: The group name associated with the cass
	 * 		-'clear' _boolean: Will clear all the cache if set to true. Default is false
	 * 
	 * @return boolean $removed Returns true if the cache was succesffuly removed
	 * @access public
	 */
	public static function deleteCache($key, $options = array()) {
		
		return self::$_redis -> delete($key);
		
	}
	
}