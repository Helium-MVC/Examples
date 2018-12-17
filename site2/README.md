# Site 2 With Angular, UUIDs and Advanced Concepts

#### Difficulty Rate: 5/10

Welcome to Site 2. Unlike the first site, this is implemented with a few advanced features and designed to scale vertically.

## Postgresql
Postgresql is an awesome database to use for two reasons.

##### 1) MIT Licensing
Unlike Mysql which requires the purchase of a commercial license if your code is not open sourced, PostgreSQL has the less restrictive MIT license, which means it can be used in proprietary products.

##### 2) Advanced Features
Postgres has many advanced features such as its data types with arrays, hstore, cidr, and event user-defined types. There are other advanced features like full-text search which uses vectors and dictionaries, table inheritance, multiple index types and a lot more.
##### UUID and HSTORE
In our example site, we use UUID and hstore. We can start by looking at the virtual schema defined in `app\models\uuid\Users.php`. Referencing a portion of the schema to use in an example.
```php
<?php
//Virtual Schema
    protected $_schema = array(
        'user_id' => array('type' => 'bigint', 'primary_key' => true, 'default' => 'shard_1.id_generator()' , 'execute_default' => true, 'auto_increment' => true),
        //Optional UUID using built-in Postgres OSSP features
        //'user_id' => array('type' => 'uuid', 'primary_key' => true, 'default' => 'uuid_generate_v4()' , 'execute_default' => true, 'auto_increment' => true),
        'preferences' => array('type' => 'hstore', 'exclude' => true, 'default' => 'hstore(array[]::varchar[])', 'execute_default' => true),
    );
?>
```

The first part is we are using a custom defined id generator created by Instagram! The ids are random numbers determined by time and are quite long. This is an example of an ID generated `'1920736220380398685'`.  A user cannot hack this kind of id to figure out how large your site is.

Commented out is also traditional UUIDs. These kinds of UUID are usable in Postgres and look like this:` '6f1bba18-8f7c-4ed8-8f03-a094468ca90a'`. There are many differents between these types of uuid:

1. Instagrams can be sorted in an asceending or descending order, tradional UUID have no order.
2. Instagrams is 4 bytes in memory, traditional UUID are 16 bytes.

*Important Note:*: UUID have two drawbacks. The first being their write time can be slower than serial, and they take up more room when indexing. There have been instances where UUID indexs are larger than the data length of the table.

The last advance feature we use is hstore, which is a key => value store within a column. In our examples, we can have multiple preferences defined for a user without the need to add extra columns.

## Database Session

In site 2, we use a database session. Database sessions work by having a session ID assigned to a user and stored in a cookie for reference. The cookie then correlates with a record in the database that has all the information about the session. Using this approach, we can more securely store data, save the data after the session expires, scale vertically, and keep more substantial amounts of data.

The session service being used is `app/services/session/DBSessionService.php` The session utilizes a model in our database in `app/models/uuid/Sessions.php` for storing the data.  Both of the classes are called in the entry point in `sites2/public_html/index.php` at:

```php
 //Set the model and service used for session handling
  app\services\session\SessionService::initializeSession(app\services\session\DBSessionService::initializeSession('app\models\uuid\Sessions'), true);
```

## Caching and Adapter Design Pattern

The site uses a more advanced version of caching that Site 1. The cache is using Redis and also uses one of the unique properties of Helium, the adapter pattern. In this pattern, we can override another classes function by creating an adapter for it.

To view, the adapter created, go to `site2/libraries/RedisCache`. In here the are two classes: `RedisCache` and the `RedisAdapter`. The cache class is a class that uses Redis as a handler, go through the source code to read. The RedisAdapter function to replace the default PVCache function that the models utilize. For example:

```php
<?php
PVCache::addAdapter('PVCache', 'writeCache', 'RedisCache');
?>
```
This line says that every time the function PVCache::writeCache is called, we are going to call RedisCache::writeCache instead. Its part of the magic in Helium! And we can replace the functionality of entire classes with this approach.

The RedisCache is loaded in the libraries during the bootstrap. You can view this at `site2/config/bootstrap/libraries.php` . The line that loads it is:
```php
<?php
//Load a library that is local only to the current site, inside the libraries folder
PVLibraries::addLibrary('RedisCache', array('explicit_load' => true));
?>
```
In `site2/config/bootstrap/libraries.php`, look around to see other libraries loaded for Site 2.

## AngularJS

Site 2 uses Angular 1 in a decoupled way for its frontend framework. Similar to VueJS in Site 1, we are not using node, npm or webpack. You will notice the libraries located in `site2/public_html/js/`. It is important to focus on the resources and controller that have the javascript CRUD resources.

The angular application is initialized by having the ng-app which is located at the beginning of the HTML file found it `site2/templates/default.html.php` here:

```html
<html lang="en" ng-app="Site2App">
```

Without this tag, angular will not activate. The ng-controller is used to set the scopes in which AngularJS is active or not. For example, if we go the user register in `site2/views/users/register.html.php`, we will see this defining the scope Angular has based on the HTML:

```html
<!-- Set everything in the form tag to this ng-controller -->
<form  id="contactForm"method="post" ng-controller="UsersCtrl" action="<?= PVTools::getCurrentUrl(); ?>">
```

This has an effect on the input fields below with ng-model like this:
```html
    <input type="text" class="form-control" maxlength="255" name="first_name" ng-model="data.first_name" value="" />
```

## Access Control & Functional Programming

Access control for this site is defined at the earliest place when the application is made aware of the current route, via PVRouter. We can go to `site2/config/bootstrap/access.php` to see the access control in action. This is another example of anonymous functions working in the observer pattern.

This also represents one of Heliums approach to functional programming. If we go over the ProdigyViews PVRouter.php class at https://github.com/ProdigyView/ProdigyView-Core/blob/master/network/PVRouter.php#L277, we will this observer being set:

```php
<?php
        self::_notify(get_class() . '::' . __FUNCTION__, $final_route, $route_options);
?>
```

This means that for every observer attached to PVRouter, they are going to receive two variables: $final_route and $route_options . We are going to create an anonymous function like so:

```php
<?php
 function($final_route, $route_options) {
//Your functional code
}
?>
```

In the anonymous function, we can try to stick close to the rules of functional programming as possible. And when we are ready, we attach the anonymous function like so:

```php
<?php
$myRouteListenerFunction = function($final_route, $route_options) {
//Do Functional Stuff
}

PVRouter::addObserver('PVRouter::setRoute', 'access_closure', $myRouteListenerFunction);
?>
```

It is not perfect functional programming, but a step to making Helium a framework that accepts functional programming.

## CSRF Tokens

Site 2 implements a more robust version of CSRF tokens than Site 1. It allows for multiple randonmly generated token that are stored in Redis. The tokens are
generated in `site2/extensions/CSRF.php` and injected into the forms using a helper:

```html
<?= $this->CSRF->getCSRFTokenInput(); ?>
```
The CSFR Token is then checked in all POST requests in `site2/extensions/controllers/Token.php` like so:

```php
if($this -> registry -> post && $this->Token->check($this -> registry -> post)) {
	//Execute code
}
```

In comparison to Site 1, each form entry generates a unique token that is stored in a database. With this approach, we can implement single-use tokens
that expire

## JSON-Like Web Token For API Access

Site 2 also improves the access to the api through JSON-Like Web Tokens. The tokens are generated in `site2/extensions/template/Session.php`
by creating a public key and signature:

```php
public function generateApiToken() {
		
	$private_key = SessionService::read('api_token');
		
	$public_key = PVSecurity::generateToken(20);
		
	$signature = PVSecurity::encodeHmacSignature($public_key, $private_key);
		
	return '
		<input type="hidden" name="api_public_key" value="' . $public_key . '" id="api_public_key"  />
		<input type="hidden" name="api_signature" value="' . $signature . '"  id="api_signature" />
	';
		
}
```
The public key and signature is then outputted into the html using the helper:

```html
<?= $this-> Session -> generateApiToken(); ?>
```

AngularJS then sends the key and signature over in each request. The signature must then be regenerated using the public key must then be regenerated and
compared to allow access to the api in `site2/controllers/apiController.php`L:

```php
$public_key = $this -> registry -> get['api_key'];
		
$signature = $this -> registry -> get['sig'];
		
$private_key = SessionService::read('api_token');
		
$session_signature = PVSecurity::encodeHmacSignature($public_key, $private_key);
		
if($signature != $session_signature) {
	echo PVResponse::createResponse('400', 'Invalid API Verification');
	exit();
}

```

On each page a new public key and signature is created to improve security.

## Business Logic In Services

In comparison to Site 1, Site 2 keeps the models skinny and puts the business logic in the services. With this approach, the business logic is accessible
independent of the model.

