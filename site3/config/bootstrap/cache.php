<?php
/**
 * Important Explanation
 * 
 * All the models in Helium use PVCache when the caching is enabled. PVCache by default
 * used the file system - which has limitations.
 * 
 * For this basic site, we are just goign to stick with file cache.
 */

PVCache::init(array());