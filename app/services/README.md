# Services

The services are a unique grouping of extended functionality for your site. In this file we are going to cover:

1. What are the current services
2. When you should create a service

## Explanation of The Current Services

### EmailService

The email service is responsible for sending emails, recording if they were successful and sent, and how users engaged with the email. Many sites us 3rd party solutions such as Sendgrid or Mailgun for sending emails that have their own logs, but we should always keep our own record of what emails we are sending.

Our email service in this example uses PHPMailer for sending the email, the LoggingService for logging the emails, and the configuration file is set in `app\config\config.php` in the email configuration.

```php
<?php
//Set the smtp arguments for sending an email
PVConfiguration::addConfiguration('mail', array(
    'mailer' => 'smtp', 
    'login' => '', 
    'password' => '', 
    'port' => 587, 
    'host' => '', 
    'from_address' => '',
    'from_name' => '',
));
?>

```
### AuthenticationService

The Authentication Service is responsible for logging a user in. Upon login, it sets the current session for that user. Why Authentication as service and not part of another component such as the Users Model?

When thinking of a distributed system, users have the option of authenticating from mobile endpoints - website, API, mobile device, etc. Good design creates a standardized way for a user to log in from any source.

The Session Handler associated with the authentication is defined the public_html/index.php of each site and looks like this:

```php
<?php
//Set the model used in Authentication
  app\services\AuthenticationService::init('app\models\basic\Users');
?>
```

### LoggingService

In our system, every interaction that a user takes is recorded. This ranges from when they register, attempt to login and fail, to leaving a communing a post. For example, in every models observer function (which is anonymous function dynamically attached to model), we call actions on success and failure of CRUD operations like so:

```php
<?php

//Observer to be executed after CRUD create operation
Posts::addObserver('app\models\uuid\Posts::create', 'read_closure', function($model, $result, $id, $data, $options) {
    
    //Only execute if successful
    if($result){
        //Log a new user has successfully be created
        LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_SUCCESS);
        
    } else {
        //Log the user failed to be created
        LoggingService::logModelAction($model, ActionLogger::ACTION_CREATED_FAILED);
    }
    
}, array('type' => 'closure'));

?>
```

It is good practice to record the actions a user takes for debugging purposes, behavioral purposes, and understanding how your system work. In a distributed system with many microservices, having this information can help paint a complete system on how different parts are interacting.

### ServerService

The server service is a class that depicts how PHP can interact with your server to gain information. A class like this will likely never be used in production but can help give ideas on how to solve system related problems with PHP.


## When To Create A Service

Next, we are going to briefly discuss when you should consider creating a new service.

### When It Does Not Belong In A Model

Models should be used only when dealing with database interactions. Having too much functionality in a model can lead to extremely bloated models. For example, a site will sender a user multiple emails pertaining to that users account: Welcome Email, Account Activation Email, Password Reset Email, etc. Having all of the functionality being handles in the model can cause code bloat, therefore add it to a service.

### Code That Creates Fat Controllers

Keeping your controllers lean as possible can improve maintainability. The controllers job is to take a request, gather the required data, and either pass that data to the view or return it in a response to the user. A complex operation should never be executed in the controller. Consider making excessive like this a code a service.

### Redundant Code Used Across The Site

There will be pieces of code that can be re-used in both controllers, models and even sometimes views. To reduce redundancy, consider making your code into a service.