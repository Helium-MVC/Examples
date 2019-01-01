<?php
/**
 * Add additional libraries in this class. Also add adapters, filters and observers in this class. Libraries
 * are a way of extending Helium's functionality. Helium can also be extended through using plug-ins
 * and applications that are available in ProdigyView.
 */

/**
 * Setup the configuration for the libraries and initliaze the libraries
 * class
 */
use prodigyview\system\Libraries;
 
Libraries::init(array());

//Load a library that is local only to the current site, inside the libraries folder
Libraries::addLibrary('twitter_bootstrap_alerts', array('explicit_load' => true));

//Load a library that is local only to the current site, inside the libraries folder
Libraries::addLibrary('hstore', array('explicit_load' => true));

//Load a library that is in the global path, in the app/libraries folder
Libraries::addLibrary('MailLoader', array('path' => PV_ROOT.DS.'app' .DS.'libraries'.DS.'MailLoader'.DS, 'explicit_load' => true));

//Load a library that is local only to the current site, inside the libraries folder
Libraries::addLibrary('RedisCache', array('explicit_load' => true));

Libraries::loadLibraries();
