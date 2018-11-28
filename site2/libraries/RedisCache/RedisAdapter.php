<?php
/**
 * The adapter will replace the functionality of PVCache's methods and adapt them to use the 
 * methods in the RedisCache class. In other words, RedisCache will be called whenever PVCache
 * is used.
 * 
 * The adapter is an example of aspect oriented programming.
 */

PVCache::addAdapter('PVCache', 'init', 'RedisCache');
 
PVCache::addAdapter('PVCache', 'writeCache', 'RedisCache');

PVCache::addAdapter('PVCache', 'readCache', 'RedisCache');

PVCache::addAdapter('PVCache', 'hasExpired', 'RedisCache');

PVCache::addAdapter('PVCache', 'getExpiration', 'RedisCache');

PVCache::addAdapter('PVCache', 'deleteCache', 'RedisCache');