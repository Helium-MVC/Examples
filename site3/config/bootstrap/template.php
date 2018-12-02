<?php
/**
 * Initialize the template class. This class is responsible for messages displayed to the user, displaying
 * javascript and css, and the overall process of the view. 'Template' uses aspects of 'PVTemplate' but they
 * are seperate classes,
 */

$template_options = array();
PVTemplate::init($template_options);

//**Set the Default Site Title
PVTemplate::setSiteTitle('Site 3');

/**
 * Adds an adapter to overrwrite the default method Template::_titleCheck and
 * write out the title of site in a different way.
 * 
 * Adapters are an example of aspect oritented programming and an alternative to dependency injection 
 */
PVTemplate::addAdapter('prodigyview\helium\He2Template', '_titleCheck', function($view) {
	
	$title = PVTemplate::getSiteTitle();
	
	if($title == 'Site 3' && !($view['view'] == 'index' && $view['prefix'] == 'index')) {
			
		if($view['prefix'] == 'index')
			$view['prefix'] = 'main';
		
		$view['prefix'] = ucwords($view['prefix']); 
		$view['view'] = ucwords($view['view']); 
		
		PVTemplate::setSiteTitle('Site 3 - ' . $view['view']. ' - '. $view['prefix'] );
	}
} , array('type' => 'closure'));
