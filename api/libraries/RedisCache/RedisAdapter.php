<?php
/**
 * The adapter will replace the functionality of Cache's methods and adapt them to use the 
 * methods in the RedisCache class. In other words, RedisCache will be called whenever Cache
 * is used.
 * 
 * The adapter is an example of aspect oriented programming.
 */
use prodigyview\util\Cache;

Cache::addAdapter('prodigyview\util\Cache', 'init', 'RedisCache');
 
Cache::addAdapter('prodigyview\util\Cache', 'writeCache', 'RedisCache');

Cache::addAdapter('prodigyview\util\Cache', 'readCache', 'RedisCache');

Cache::addAdapter('prodigyview\util\Cache', 'hasExpired', 'RedisCache');

Cache::addAdapter('prodigyview\util\Cache', 'getExpiration', 'RedisCache');

Cache::addAdapter('prodigyview\util\Cache', 'deleteCache', 'RedisCache');