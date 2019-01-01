
# Site 1 - VueJS, Mysql and The Basics

#### Difficulty Rate: 2/10

Welcome to Site 1! A basic example and use of He2MVC. The site is considered basic because many of the strategies used in its creation will scale vertically (making a server larger by adding more RAM, CPUs, and disk space), and its not very secure.

## Mysql
Mysql is one of the most commonly taught databases and uses standard SQL. Many of its features work right out the box, no configuration required. The models used in Site 1 are located in the `app/models/basic/` folder.

One of the features of this database is its linear generation of incremental ids. For example, when a post is created, its given a post id of 1. The next post id is 2, then 3 and so forth. This approach can make it easy for outsiders to figure out intimate details about your website, For example, they can go directly to http://site1.he2example.com/posts/view/100, and eventually figure out how many posts you actually have. 

It also makes it difficult to backup and restores data in an accident because the ids might collide. For example, let us say 20 posts are made and the database crashes. You create a new database, and suddenly users make 20 more posts. You then magically are able to recover data from the old server and want to import it but you cannot because you now have 40 posts with the same ids.

## VueJS
The site utilizes VueJS for its frontend framework. Vue is a great Javascript solution for getting started as it easy to bind with the HTML, has a great templating system (that we do not use in our example), and has plenty of plug-ins. The files are located in` site1/pubic_html/js`, and you can see how the library is structured in `site1/templates/_javacript.html.php`.

In our views, we have it completely decoupled with PHP . For example, if you go to `site1/views/posts/create`, you will notice this in the header:
```php
<?php 
//We are injecting our js app into the html
Libraries::enqueueJavascript('components/posts.js'); 
?>
```

If we remove this, the VueJS would have no effect. So current v-model bindings such as:

```html
<input type="text" class="form-control" maxlength="255" name="title" v-model="title" value="<?= $post -> title; ?>" />
```

will no longer function.

## Session Stored In Browser

The sessions for Site 1 are stored in the browser. This is a very easy and fast way of storing session data, but it does not scale vertically and makes data susceptible to attack.

#### Scaling Vertically
Let us say we do not use cookies but the session in PHP. This means the data stored in the /tmp folder on a server. If your site is behind a load balancer that has 3 servers, a users data could only be on one server at a time. Meaning if they log in on one server, and the load balancer then flips them to another server, they are no longer logged because the session data is on the first server.

#### Susceptible Data
If we want to scale vertically with this approach, we have to make sure to utilize cookie data and not the sessions. Storing data in a cookie is readable by third parties, which can lead to security vulnerabilities and exposing the user. In our solution, we encrypt and decrypt the data as a measure.

#### Session Info

For this site, you can look at the WebSessionService located at
`app/services/session/WebSessionService`.

The session handler is set at the earliest point in execution in the index.php entry point located at `site1/public_html/indexp.php`

You will see this line setting the session model:

 ```php
<?php
 //Set the model and service used for session handling
 app\services\session\SessionService::initializeSession(app\services\session\WebSessionService::initializeSession(''), true);
?>
```

## File Cache

File cache is referring to the caching of information on the local file system. For example, if a user visits a page that takes a lot of complex algorithms and has a long load time, we want to cache this page so we can serve it faster. One technique is to copy all the HTML to a file and save it there. When another user hits the same page, we can serve the saved html and not rerun the complex time-consuming operations.

File caching on a local server has the drawback of not scaling horizontally but also causes I/O operations, meaning data is written to and from a disk. This can be taxing on a server with a large amount of requests, creates a single point of failure, also can use a large amount of disk space.

In our example we are using query caching, where we are caching the query results from a model to file. You can see it being activated in `site1/controllers/logsController.php`.

```php
<?php
public function index() : array  {
        
        //Notice in this example, we are caching
        //the query results
        $logs = ActionLogger::findAll(array(
            'order_by' => 'date_recoded DESC'
        ), array('cache' => true)); // Cache activated here
        
        return array('logs' => $logs);
    }
?>
```
The cache is instantiated in the bootstrap in the file  of `site1/config/boostrap/cache.php`.

## Access Control

Access control is the ability to decide what routes users have access too and restrict them specific files. In our access control for Site 1, we have implemented it in the constructor of the controller.

A constructor in PHP is a method that is called every time a new object is created. Example:

```php
$object = new MyObject();
```
will automatically call the __constructor function every time the new operation is used.
```php
class MyObject {
     //called every time
     public function __constructor() {
           //Code to run
      }
}
```
Having it placed in the constructor of a controller ensures that the access control functionality is being called everytime a user hits a page governed by that controller. In our example, we can look at `site1/controller/usersController.php`. 

With this code:
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
		Template::errorMessage('The section is restricted to members. Please login.');
		Router::redirect('/login');
	}
        
}
```

## CSRF Tokens

CSRF Tokens are used to prevent cross-site injection. This occurs when a third party site is able to submit data to the forms. The site has a basic CSRF strategy tied to the session.
The tokens are generated in `site1/extensions/template/CSRF.php` and injected into each form like so:

```html
<input type="hidden" name="csrf_token" v-model="csrf_token" value="<?= $this -> CSRF -> getCSRFTokenInput('token_name'); ?>" />
```

Afterwards on a POST operation to a controller,the token generated in the html is checked in with the helper `site2/extensions/controllers/Token.php` such as below:

```php
if($this -> registry -> post && $this->Token->check('post_token', $this -> registry -> post['csrf_token'])) {
	//Execute code				
}
```

And this preven cross-site injections.

## Basic Token Authorizatio For The API

In our example, VueJS utilizes the API located at `site1/contollers/apiController.php`. When access the api, it uses an authorization header like so:

```javascript
let HTTP = axios.create({
  baseURL: '/api',
  transformRequest: [function (data, headers) {
    headers['Authorization'] = document.getElementById('api_token').value;
    return JSON.stringify(data)
  }],
  headers: {
    'Content-Type': 'application/json'
  }
});
```

The token is generated in the `site1/templates/default.html.php` by getting the token from Session helper in `site1/extensions/template/Session.php` like so:

```html
<input type="hidden" name="api_token" value="<?= $this -> Session -> get('api_token'); ?>" id="api_token" />
```

In the apiContoller.php construcuter, the Authorization taken is retrieved and checked again the current session token like so:

```php
$request = new Request();
$authorization = $request -> getHeader('Authorization');
		 
if($authorization != SessionService::read('api_token')) {
	echo Response::createResponse(400,  'Invalid Token Sent');
	exit();
}
```
If it matches, access is allow. If not, access is denied. This is a very basic approach to API authentication.

## Business Logic In The Models

The final note on Site 1 is the location of the business logic. This site uses "Fat Models", in which the majority of the business logic is kept in an
associated model. For example, send emails related to the model and access control is all executed from the model.
