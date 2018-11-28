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
 
PVLibraries::init(array());

//Load a library that is local only to the current site, inside the libraries folder
PVLibraries::addLibrary('twitter_bootstrap_alerts', array('explicit_load' => true));

//Load a library that is local only to the current site, inside the libraries folder
PVLibraries::addLibrary('hstore', array('explicit_load' => true));

//Load a library that is in the global path, in the app/libraries folder
PVLibraries::addLibrary('MailLoader', array('path' => PV_ROOT.DS.'app' .DS.'libraries'.DS.'MailLoader'.DS, 'explicit_load' => true));

PVLibraries::loadLibraries();
