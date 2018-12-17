# Session Storage

In our example, we are going to make use of two ways of storing and retrieving session data with either a database or cookie.

### Database Session
The database session refers to when session data is stored and retrieved from a database. While any database can be used, session storage and retrieval should be lighting fast and not become a bottleneck. Therefore, many solutions utilize Redis or Memcached. 

The pros and cons to database sessions are:

**Pros**
- Can be made persistent
- More secure than storing data in the browser
- Can store a lot more data
- Can scale horizantally

**Cons**
- Can cause latency
- Has to be made redundant in order to not become a point of failure

### Cookie Session

Cookie Sessions is when a session is stored on the cookie in the browser or in PHP, the current session can be stored in the server /tmp directory. This approach works right out the box with PHP so its easy to implement. The pros and cons to this approach are:
**Pros**
- Lighting fast storing and retrieval
- Easy to implement

**Cons**

- Limited to the size of storage on the server or in the browser
- Can be insecure if not properly encrypted
- Is not persistent and the client can cause data to be lost
- Does not scale horizantally, each server will have a different session

### Our Examples With Dependency Injection

In our example we are using dependency inject to easily swap out what kind of session we are utilizing. For example, lets look in our entry points for the sites:
##### sites1/public_html/index.php
```php
//Set the model and service used for session handling
 app\services\session\SessionService::initializeSession(app\services\session\WebSessionService::initializeSession(''), true);
```
 
#####  sites2/public_html/index.php
```php
   //Set the model and service used for session handling
 app\services\session\SessionService::initializeSession(app\services\session\DBSessionService::initializeSession('app\models\uuid\Sessions'), true);
```
Both examples are using the SessionService, but one is receiving  WebSessionService while the other is receiving  DBSessionService.

Dive into those classes to see how they differ in their implementation.


## Static Classes

You will notice that each of our session classes utlizes the use of static methods with dependency injection. The benefit to this approach with sessions it
creation of a global session that is unchanging through the execution of the application. There is no use case where a user should change a session and this
approach all classes interact with the same session data.