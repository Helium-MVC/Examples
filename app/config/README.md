# Config File, Validation and Functional Programming

In this folder, we are housing our configuration for the entire site and extending our validation for our models. However, the intriguing feature we have is the beginning (not perfected) implementation of functional programming.

## Configuration File

This configuration is an open file that mainly utilizes the methods of Configuration, which then can be accessible in other areas of the site. For example, we have 4 sites in our solution that occasionally we will need to reference the url too.
```php
<?php
//Website Urls
Configuration::addConfiguration('sites', array(
    'main' => 'http://www.he2examples.local/',
    'site1' => 'http://site1.he2examples.local/',
    'site2' => 'http://site2.he2examples.local/',
    'site3' => 'http://site3.he2examples.local/',
    'api' => 'http://api.he2examples.local/',
));
?>
```

The first part of the Configuration is the key used to identify what configuration option we want. The 2nd part is an array of values the will call. To get the api site url later, we will do:

```php
<?php
PVConfiruation::getConfiguration(‘sites’) -> api; 
?>
```

Another example is how we have database connections set in the config.php like so:

```php
<?php
Configuration::addConfiguration('postgres', array(
    'dbprefix' => '',
    'dbhost' => 'postgres',
    'dbname' => 'helium',    
    'dbuser' => 'helium',
    'dbpass' => 'helium',
    'dbtype' =>'postgresql',   
    'dbschema' => 'public',
    'dbport' => '5432',
));
?>
```

And in the site2/config/database.php we assign those values as the database to connect in that site.

## Extending Validation And Functional Programming

Validation is important in helium when models are checking for values. Going into the app/models folder, look at any models validation section:

```php
<?php
protected $_validators = array(
        'user_id' => array(
            'notempty' => array('error' => 'Post must be associated with a user.'),
        ), 
        'title' => array(
            'notempty' => array('error' => 'The post requires a title.'),
        ), 
        'content' => array(
            'notempty' => array('error' => 'The post requires text.'),
        ), 
    );
?>
```

Every value will run through what Helium uses called the Validator, which is part of the ProdgiyView Toolkit.

Validator has many built-in functions from finding if a string contains an integer to detecting mime types. However, it cannot have an answer for every scenario that developers will have to solve, so we need to extend it. And we do this using anonymous functions, which opens up the possibility for functional programming. For example, we have this validation rule to check if something is a currency:

```php
<?php
$is_currency = function($number) {
    return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $number);
}
?>
```

If we follow the rules of functional programming:

1. Function does not access or modify the scope outside the function
2. Functions are first-class function, aka can be assigned to a variable
3. Function variables are immutable and cannot be changed once assigned
4. Function are pure, meaning when given the same inputs they will always return the same outputs
5. Functions avoid side affects, ie causing a state change outside of the functions scope

In many of Heliums implementations we can follow 1-4, 5 gets tricky. But with our example, we can add the annmomyous function to our validator class like so:

```php
<?php
Validator::addRule('is_currency', array('function' => function($number) {
    return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $number);
}));
?>
```

And we use that validation rule by adding it to the Validator like so:

```php
<?php
Validator::check(‘is_currecny’, $value); ?>
?>
```

Helium extends ProdgiyView design patterns of Adapters, Filters, and Observers. Each pattern takes in anonymous functions. We can utilize these extensions to get further into a semi-functional programming approach.
