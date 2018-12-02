<?php
use app\factories\ServiceFactory;

/**
 * In this secion we are going to add servives to our ServiceFactory. To review, the Factory Design Pattern
 * is creates an object you want to use.
 * 
 * We include this file in the bootstrap.php to activate it and then we can use it throughout this site.
 */
 
 //Add Sessions Service
ServiceFactory::add('session', 'app\services\session\SessionService');

 //Add Storage Service
ServiceFactory::add('service', 'app\services\storage\CloudStorageService');

//Add Email Service
ServiceFactory::add('email', 'app\services\EmailService');

//Add Logging Service
ServiceFactory::add('logging', 'app\services\LoggingService');

//Add Logging Service
ServiceFactory::add('queue', 'app\services\QueueService');