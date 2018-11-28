
# Site 1 - VueJS, Mysql and The Basics

Welcome to Site 1! The basic example and use of He2MVC. The site is considered basic because many of the strategies used in its created will scale vertically (making server larger by adding more RAM, CPUs, and disk space), and is not very secure.

## Mysql
Mysql is one of the most commonly used and taught databases and uses standard SQL. Many of its features work right out the box, no configuration required. The models used in site 1 are located in the app/models/basic/ folder.

One of the basic features of this database is its linear generation of incremental ids. This means for example when a post is created, its given an id of 1, the next post id is 2, then 3 and so forth. This approach can make it easy for outsiders to go through your website, i.e., they can go directly to http://site1.he2example.com/posts/3 without having to know the path, which can expose elements of your site you do not want. 

It also makes it difficult to backup and restores data in an accident because the ids might collide. For example, lets say 20 posts are made, and MySQL breaks. You create a new server, and suddenly users make 20 more posts before you can import the old post. You have 40 posts with the exact same ids.

## VueJS
The site utilizes VueJS for its frontend framework. Vue is a great Javascript solution for getting started as it easy to bind with the HTML, had a great templating system (that we do not use in our example), and has plenty of plug-ins. The feel files are located in` site1/pubic_html/js`, and you can see how the library is structured in `site1/templates/_javacript.html.php`.

In our version of view, we have it completely decoupled with PHP . For example, if you go `site1/views/posts/create`, you will notice this in the header:
```php
<?php 
//We are injecting our js app into the html
PVLibraries::enqueueJavascript('components/posts.js'); 
?>
```
If we remove this, the framework would have no effect. So current v-model bindings such as
```html
<input type="text" class="form-control" maxlength="255" name="title" v-model="title" value="<?= $post -> title; ?>" />

```
would no longer have an effect.

## Session Stored In Browser

The sessions for Site 1 are stored in the browser. This is a very easy and fast way of storing sessions, but it does not scale vertically and makes data susceptible to attack. 

##### Scaling Vertically
Let's say we do not use cookies but the session that is stored in the /tmp folder on a server. This means if your site was behind a load balancer that head 3 servers, a users data could only be on one server at a time. Meaning if they log in on the server, and the load balancer then flips them to another server, they are no longer logged because the session data is on the first server.

##### Susceptible Data
If we want to scale vertically with this approach, we have to make sure to utilize cookie data and not the sessions. Storing data is a cookie is readable to third parties, which can lead to security vulnerabilities and exposing the user. In our solution, we encrypt and decrypt the data as a measure. But this approach also takes up space for the user, and the data cannot be saved for future purposes.

##### Session Info

For this site, you can look at the WebSessionService located at
`app/services/session/WebSessionService`.

The session handler is set at the earliest point in execution in the index.php entry point located at `site1/public_html/indexp.php`

You will see this line setting the session model:

 ```php
 //Set the model and service used for session handling
 app\services\session\SessionService::initializeSession(app\services\session\WebSessionService::initializeSession(''), true);
```

## File Cache

File cache is referring to the caching of information in the file system. For example, if a user visits a page that takes a lot of complex algorithms, we can copy all the HTML to a file and save it there. When another user returns, we can serve that file saved and not run the complex operations.

File caching had the drawback of not scaling horizontally but also causes I/O operations, meaning data is written to and from the file system. This can be taxing on a server but also use a large amount of space.

In our example, we are using query caching where we are caching the query results from a model to file. You can see it being activated in `site1/controllers/logsController.php.`

```php
public function index() : array  {
        
        //Notice in this example, we are caching
        //the query results
        $logs = ActionLogger::findAll(array(
            'order_by' => 'date_recoded DESC'
        ), array('cache' => true)); // Cache activated here
        
        return array('logs' => $logs);
    }
```

The cache is inited in the bootstrap in the file site1/config/boostrap/cache.php

Access Control

Access control is the ability to decide what routes users have access too and restrict them specific files. In our access control for site 1, we've implemented it in the constructor of the controller.

The constructor in PHP is called every time a new object is created. Example

```php
$object = new MyObject();
```

Having it placed in the constructor ensures that the access control functionality is being called. So it in action, you make view it at `site1/controller/usersController.php` 
With this:
```php
/**
     * In the construct, we are restricting access to certian routes of this controller.
     */
    public function __construct($registry, $configurtion = array()) {
        parent::__construct($registry, $configurtion );
        
        $restricted_routes = array(
            '/users/account',
            '/users/myposts',
            '/users/logout'
        );
        
        if(!SessionService::read('is_loggedin') && in_array($this->registry -> route[0], $restricted_routes)) {
            PVTemplate::errorMessage('The section is restricted to members. Please login.');
            PVRouter::redirect('/login');
        }
        
    }
```