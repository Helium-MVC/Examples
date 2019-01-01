# Facades

Welcome to the Facades section of your app. Like everything in Helium, your use of Facades is optional. This section will go over what Facades are an example use cases.

## What Is the Facade Design Pattern?
The Facade Design Pattern is a pattern that takes complex operations in an application and makes it executable in a relatively simple way, hiding the majority of the complexity from the developer.

For example, if we had an e-commerce platform and a user wanted to check out - the process can be relatively complicated. It might take sever steps and several components such as:

- Pulling all the items out of the shopping carts
- Adding up all the totals
- Connecting to the payment gateway like Paypal
- Alerting a distributor if the items have to be shipped
- Sending the purchaser an email

And more steps if you can imagine. Now, what if in your application, there are several places can do this check out:
- On the purchase page of an item
- In the customer service section of the site
- In their account shopping cart
- From a mobile device

Rewriting all that code several times is a lot of code to main in many different locations. The idea behind on the Facade is to hide that complexity into one call.

## Example of Facade

The Facade Design Pattern is heavily used in Site 3 because of the absence of the model on that site. The task of creating a user is as follows:

```php
<?php
//Create Document ID
        $id =  Uuid::uuid4();
                
        //Assign Values
        $data['user_id'] = $id;
        $data['password'] = \Security::hash($data['password']);
        $data['activation_token'] = \Tools::generateRandomString();
        $data['preferences'] = array(
            'email_weekly_updates' =>true,
            'email_comment_responses' => true
        );
        
        $data['date_registered'] = date('Y-m-d H:i:s');
        
        $this -> _firebase->getReference('users/'. $data['user_id'])->set($data);
        
        //Send welcome email via queue
        $queue = ServiceFactory::get('queue');
        
        $email_data = array(
            'user' => \Conversions::arrayToObject($data),
            'site_url' => \Configuration::getConfiguration('sites') -> site3
        );
        
        $queue -> add('sendWelcomeEmail', $email_data);
?>
```
Thats quite a bit of steps! From creating the UUIDs, the default data, sending the welcome email and saving the data to Firebase. If we had multiple points of creating a user such as on the /login page, via API, or another service it, copying that code is a pain.a

So we will store all of that code in the `app/facades/FirebaseModelFacade.php`, and we call it ins the `site3/controllers/usersController.php` like so:

```php
<?php
//Set in the base mode
        $this-> _models = new FirebaseModelFacade($this -> _firebase);
//Called in the usersController.php
 $user = $this -> _models -> createUser($this -> registry -> post);
?>
```

Consider using the Facade Design Pattern for your more complex operations you will have when building your app.