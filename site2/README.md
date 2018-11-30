# Site 2 With Angular, UUIDs and Advanced Concepts

Welcome to Site 2. Unlike the first site, this is implemented with a few advanced features and designed to scale vertically.

## Postgresql
Postgresql is an awesome database to use for two reasons.
##### 1) MIT Licensing
Unlike Mysql which requires the purchase of a commercial license if your code is not open sourced, PostgreSQL has the less restrictive MIT license, which means it can be used in proprietary products.

##### 2) Advanced Features
Postgres has many advanced features such as its data types with arrays, hstore, cidr, and event user-defined types. Their full-text search which uses features like search vectors is far more advanced than MySQL. PostresSQL also has table inheritance, multiple index types and a lot more.
##### UUID and HSTORE
In our example site, we use UUID and hstore. We can start by looking at the virtual schema defined in app\models\uuid\Users.php Pulling out a portion of the schema in to use an example.
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

Commented out is also UUIDs. The kinds of UUID are usable in Postgres and look like this:` '6f1bba18-8f7c-4ed8-8f03-a094468ca90a'`

The last advance feature we use is hstore, which is a key => value store within a column. In our examples, we can have multiple preferences defined for a user without the need to add extra columns.

## Database Session

In site 2, we use a database session. Database sessions work by having a session ID assigned to a user in something like a cookie. The cookie then correlates with a record in the database that has all the information about the session. Using this approach, we can more securely store data, save the data after the session expires, scale vertically, and keep more substantial amounts of data.

The session service being used is `app/services/session/DBSessionService.php` The session utilizes a model in our database in app/models/uuid/Sessions.php for storing the data.  Both of the classes are called in the entry point in `sites2/public_html/index.php` at:

```php
 //Set the model and service used for session handling
  app\services\session\SessionService::initializeSession(app\services\session\DBSessionService::initializeSession('app\models\uuid\Sessions'), true);
```

## Caching and Adapter Design Pattern

The site uses a more advanced version of caching that Site 1. The cache is using Redis and also uses one of the unique properties of Helium, the adapter pattern. In this pattern, we can override another classes function by creating an adapter for you.

To view, the adapter created, go to site2/libraries/RedisCache . In here the are two classes: RedisCache and the RedisAdapter. The cache class is a class that uses Redis as a handler, go through the source code to read. The RedisAdapter function to replace the default PVCache function that the models utilize. For example:

```php
<?php
PVCache::addAdapter('PVCache', 'writeCache', 'RedisCache');
?>
```
This line says everytime the function PVCache::writeCache is called, we are going to call RedisCache::writeCache instead. Its part of the magic in Helium! And we can replace the functionality of entire classes!

The RedisCache is loaded in the libraries during the bootstrap. You can view this at `site2/config/bootstrap/libraries.php` . The line that loads it is:
```php
<?php
//Load a library that is local only to the current site, inside the libraries folder
PVLibraries::addLibrary('RedisCache', array('explicit_load' => true));
?>
```
In site2\config\libraries.php, look around to see other libraries loaded for Site 2.

## AngularJS

Site 2 uses Angular 1 in a decoupled way for its frontend framework. Similar to VueJS in Site 1, we are not using node, npm or webpack. You will notice the libraries located in site2/public_html/js/. It is important to focus on the resources and controller that have the javascript CRUD resources.

The angular application is loaded by having the ng-app which is located at the beginning of the HTML file found it site2/templates/default.html.php here:
```html
<html lang="en" ng-app="Site2App">
```

Without this tag, angular will not activate. The ng-controller is used to set the scopes in which AngularJS is active or not. For example, if we go the user register in site2/views/users/register.html.php, we will see this defining the scope Angular has based on the HTML:

```html
<!-- Set everything in the form tag to this ng-controller -->
<form  id="contactForm"method="post" ng-controller="UsersCtrl" action="<?= PVTools::getCurrentUrl(); ?>">
```

This has an effect on the input fields below with ng-model like this:
                    ```html
    <input type="text" class="form-control" maxlength="255" name="first_name" ng-model="data.first_name" value="" />```

## Access Control & Functional Programming

Access control for this site is defined at the earliest place when the application is made aware of the current route, via PVRouter. We can go to `site2/config/bootstrap/access.php` to see the access control in action. This is also another example of anonymous functions working in the observer pattern.

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

