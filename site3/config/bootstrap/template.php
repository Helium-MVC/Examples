<?php
/**
 * Initialize the template class. This class is responsible for messages displayed to the user, displaying
 * javascript and css, and the overall process of the view. 'Template' uses aspects of 'Template' but they
 * are seperate classes,
 */
use prodigyview\template\Template;
use prodigyview\network\Router;

$template_options = array();
Template::init($template_options);

//**Set the Default Site Title
Template::setSiteTitle('Site 3');

/**
 * Adds an adapter to overrwrite the default method Template::_titleCheck and
 * write out the title of site in a different way.
 * 
 * Adapters are an example of aspect oritented programming and an alternative to dependency injection 
 */
prodigyview\helium\He2Template::addAdapter('prodigyview\helium\He2Template', '_titleCheck', function($view) {
	
	$title = Template::getSiteTitle();
	
	if($title == 'Site 3' && !($view['view'] == 'index' && $view['prefix'] == 'index')) {
			
		if($view['prefix'] == 'index')
			$view['prefix'] = 'main';
		
		$view['prefix'] = ucwords($view['prefix']); 
		$view['view'] = ucwords($view['view']); 
		
		Template::setSiteTitle('Site 3 - ' . $view['view']. ' - '. $view['prefix'] );
	}
} , array('type' => 'closure'));

/**
 * Uses the adapter pattern to ovveride the current display and use Twig templating inside of the default
 * html templates and views
 */
prodigyview\helium\He2Router::addAdapter('prodigyview\helium\He2Router', 'renderTemplate', function($controller, $vars = array()) {
	
	//Get the route to find the current action
	$route = Router::getRoute();	
	$action = (empty($route['action'])) ? Router::getRouteVariable('action') : $route['action'];
	
	if(!$action) {
		$action = 'index';
	}
	
	//Load the variables set in the controller
	$template_vars = $controller->getTemplate();
	$view_vars = $controller->getView();
	
	//Formulate the path based on the variables
	$template = $template_vars['prefix'] .'.twig.' . $template_vars['extension'];
	$view = ((isset($view_vars['view'])) ? $view_vars['view'] : str_replace('Controller', '', get_class($controller)) ) .DS. $action .'.twig.' . $view_vars['extension'];
	
	
	//Setup The Twig Environment of files to load
	$loader = new Twig_Loader_Filesystem();
	$loader -> addPath(SITE_PATH.'templates'.DS);
	$loader -> addPath(SITE_PATH.'views'.DS);
	$loader -> addPath(SITE_PATH.'extensions'.DS.'twig'.DS);
	
	
	//Create the environment
	$twig = new Twig_Environment($loader, array(
		//'cache' => PV_ROOT.DS.'tmp'.DS,
	));
	
	//Add global extension
	$twig->addGlobal('navigation', new Navigation());
	$twig->addGlobal('alert', new ShowAlert());
	$twig->addGlobal('session', new Session());
	$twig->addGlobal('format', new Format());
	
	//Convert Vars approrpiate twig values
	foreach($vars as $key => $value) {
		if($value instanceof \Collection) {
			$vars[$key] = $value -> getData();
		}
	}
	
	//Set the name of the view to be loaded
	$vars['_twig_content'] = $view;
	
	//Set Meta Parameters
	$vars['SITE_TITLE'] = Template::getSiteTitle();
	$vars['SITE_KEYWORDS'] = Template::getSiteKeywords();
	$vars['SITE_META'] = Template::getSiteMetaTags();
	$vars['SITE_DESCRIPTION'] = Template::getSiteMetaDescription();
	//$vars['HEADER_ADDITION'] = Template::getHeader(array());
	
	//Display The Template
	echo $twig->render($template, $vars);
	
	exit();
	
 }, array('type' => 'closure'));

