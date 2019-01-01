# Site 3 - Microservice Site

#### Difficulty Rate: 8/10

Welcome to Site 3! The most advanced of 3 examples sites. There is no javascript framework in this site, but we to treat this site as close to a microservice architecture as possible. This will include Firebase,  Queueing System, Controller Level Caching, Redis Session, Factories and Facades

## Firebase Setup

In this site, we are going to remove the models and use a database as a service solution, Firebase! A quick setup is required for you to do this.

1. Create a firebase database at https://firebase.google.com/
2. Download your Service Account json key. This can be found in your Project Overview: Project Settings -> Service Accounts
3. Rename the downloaded key to 'google-service-account.json' and copy it to app/build/config/google-service-account.json .

You are done! You notice the file is referenced in the app/config/config.php like so:

```php
<?php
Configuration::addConfiguration('firebase', array(
    'jsonFile' => PV_ROOT.DS.'app/config/google-service-account.json', 
));
?>
```

And then the file is used to call your firebase database in `site3/controllers/baseController.php` like so:

```php
<?php
//Setup the Firebase Connection
        $serviceAccount = ServiceAccount::fromJsonFile(Configuration::getConfiguration('firebase') -> jsonFile);
        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
        $this -> _firebase = $firebase->getDatabase();
?>
```

SUPER IMPORTANT. For authentication, please ensure that the Email Login is enabled in your Firebase Authentication preferences. You must login to
the firebase console to do.

## Facades

This will be our first site to dive into Facades. Facades is a design pattern that hides complex operations that uses multiple components in a single call.

For example, because we are using Firebase and not using the models, we cannot rely on our models default values. Therefore in our `app/facades/FirebaseModelFacade.php`, we have a lot of extra functionality added when we create a new object  like a post:

```php
<?php
//Create Document ID
        $id = $uuid5 = Uuid::uuid4();
                
        //Assign Values
        $data['post_id'] = $id;
        
        //Embed User In Post
        $data['user'] = $this -> retrieveUser($data['user_id'], 'array');
        
        //Set The Data Created
        $data['date_created'] = date('Y-m-d H:i:s');
        
        //Empty Comments Array
        $data['comments'] = array();
        
        $this -> _firebase->getReference('posts/'. $data['post_id'])->set($data);
        
        return new \Collection($data);
?>
```

Imagine trying to write that out in each controller that creates a post and when you have to make a change, you would have to change it in every place where you are creating a new post. Thats a lot of work. This Facade Design Pattern makes it easier. 

For our site, we have FirebaseModelFacade called in `site3/controllers/baseController.php` like so:

```php
<?php
//Call the FirebaseModelFacade created in app/facades folder
  $this-> _models = new FirebaseModelFacade($this -> _firebase);
?>
```

And you will notice the Facade being called on CRUD operations:
```php
<?php
if($this -> registry -> post && $this -> validate('post','create', $this -> registry -> post)) {
 		//Our Facade being callled
		$post = $this -> _models -> createPost($this -> registry -> post);
}
?>
```

## Factories

In this site, we also make use of Factories. Factories is a design pattern that makes it easy to create an object. In our Site 3, we use app/factories/ServiceFactory.php for calling various services. For example, instead of:

```php
<?php

use app\services\EmailService;

$email = new EmailService();
$email -> sendWelcomeEmail();
?>
```
We can now call any service like so:

```php
<?php
use app\factories\ServiceFactory;

$email = ServiceFactory::get('email');
$email -> sendWelcomeEmail();

$session = ServiceFactory::get('session');
$session::read('user_id');
?>
```

We configure our ServiceFactory in `site3/config/bootstrap/factories.php` where we are able to add services like so:

```php
<?php
//Add Email Service
ServiceFactory::add('email', 'app\services\EmailService');
?>

```
## Twig Templating

Unlike the other sites that used HTML for basic templating, this site used Symphones templating engine called Twig. It accomplishes
this through the Adapter Pattern which can be viewed in `site3/config/bootstrap/template.php`. In there you will find the twig
implementation.

## Controller Level Caching

In Site 1 we explored view level caching, Site 2 we did query level caching, now we are going to explore controller level caching. Services like Firebase can create slow response times for our website, sometimes causing an additional 1 to 10 seconds of additional wait for an operation to complete, which is based for the user.

In Site 3, most of heavy operations take place in our controllers action. For example:
```php
<?php
public function index() : array {
        
       	$posts = $this -> _models -> queryPosts(array('is_published' => 1));
        
        return array('posts' => $posts);
    }
?>
```

This query to Firebase each time is expensive, but the results '`return array('posts' => $posts);`' should be the same each time, or at least looking for different results every 10 minutes is not bad. So lets cache it! But how? If we look at Heliums Router at:

https://github.com/ProdigyView/Helium/blob/master/router.class.php#L89

You see this function is responsible for calling the controllers action and it has an adapter!

```php
<?php
public function executeControllerAction($controller, $action) {
            
        if (self::_hasAdapter(get_called_class(), __FUNCTION__))
            return self::_callAdapter(get_called_class(), __FUNCTION__, $controller, $action);
        
        $filtered = self::_applyFilter(get_class(), __FUNCTION__, array('controller' => $controller, 'action' => $action), array('event' => 'args'));
        $controller = $filtered['controller'];
        $action = $filtered['action'];
            
        return $controller -> $action();
            
        self::_notify(get_called_class() . '::' . __FUNCTION__, $this, $controller, $action);
    }
?>
```
Coming over to our `site3/config/bootstrap/cache.php`, we have created an anonymous function that will replace the above function in our router with the option for pulling cached data.

```php
<?php
 prodigyview\helium\He2Router::addAdapter('prodigyview\helium\He2Router', 'executeControllerAction', function($controller, $action) {
     
    //Create a cache key from the controller and action
    $cache_name = get_class($controller).''. $action;
    
    //Create a new cache if cache does not exist
    if(Cache::hasExpired($cache_name)) {
        //Call the controller and get the data
        $data = $controller -> $action();
        
        //Disable the cache
        if(isset($data['disable_cache'])) {
            return $data;
        } else {
            //Write the data to a cache file
            Cache::writeCache($cache_name, $data);
        }
        
        return $data;
    } else {
        //Return cached data
        //Never has to call the controller
        return Cache::readCache($cache_name);
    }
    
 }, array('type' => 'closure'));
 ?>
```

And just like that, we are caching the output so our site should now load faster!

## Microservice Logging Setup

In our next example, we are going to use logging as a microservice. Start by going go Loggly.com and registering for a free account. After you do, find the http key. Afterwards go to `app/config/config.php` and enter the key into loggly api section:

```php
<?php
//Loggly API Key for HTTP Requests
Configuration::addConfiguration('loggly', array(
	'key' => '', 
));
?>
```

Now if we head over to `site3/controllers/baseController.php`, we will come across the sending of the logs to Loggly.
```php
<?php
$loggly_key = Configuration::getConfiguration('loggly') -> key;
		
//PVCommunicator sends CURL call to loggly
$communicator = new PVCommunicator();
$result = $communicator -> send('POST', 'http://logs-01.loggly.com/inputs/'.$loggly_key.'/tag/http/', $data);
?>
```
PVCommunicator is a class that can send Curl request, SOAP requests and socket communication. In our example, we are using PVCommunicator to send information to another service, in this case Loggly. Think of this as when building your apps, and easy way to communicate with other services.

## Queueing System

We are trying to keep this site to work with microservices. With that, we have a messaging system! Messaging systems can come in a variety of different formats from sending Restful Requests to a service to services like Gearman. We are going to keep it simples with a Redis Queue system.

Start by looking at `app/services/QueueService.php` and you will notice is a simple push/pop service. One of the areas the service is utilized is in `app/facades/FirebaseModelFacade.php` . When creating a new users...

```php
<?php
//Send welcome email via queue
        $queue = ServiceFactory::get('queue');
        
        $email_data = array(
            'user' => \Conversions::arrayToObject($data),
            'site_url' => \Configuration::getConfiguration('sites') -> site3
        );
        
        $queue -> add('sendWelcomeEmail', $email_data);
?>
```

To briefly summarize, we get the queue service with 'ServiceFactory::get('queue')'. Then we assign the data to '$email_data' array, which is then passed to our queue with the key 'sendWelcomeEmail'.

Heading over to `site3/cli/QueueCli.php` and using our imagination, this is a service that runs on another system. The code that is ran:
```php
<?php
public function sendWelcome() {
        
        $queue = ServiceFactory::get('queue');
        
        $data = $queue -> pop('sendWelcomeEmail');
        
        $email = ServiceFactory::get('email');
        
        $email -> sendActivationEmail($data -> user, $data -> site_url);
    }
?>
```
Which gets the data from our queue, and then passes the data onto the email service to send out an email. You can run the code yourself on the command linke:

```bash
 php site3/helium.php QueueCli sendWelcome
```
 