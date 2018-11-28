# Session Storage

In our example, we are going to make use of two ways of storing and retrieving session data with either a database or cookie.

### Database Session
The database session refers to when session data is stored and retrieved from a database. While any database can be used, session storage and retrieval should be lighting fast and not become a bottleneck. Therefore, many solutions utilize Redis or Memcached. 

The pros and cons to database sessions are:

**Pros**
- Can be made persistent
- More secure than storing data in the browser
- Can store a lot more data
- Can scale across a large infrastructure

**Cons**
- Can cause latency
- Has to be made redundant to not become a point of failure

### Cookie Session

Cookie Sessions is when a session is stored on the cookie in the browser or in PHP, the current session can be stored in the server /tmp directory. This approach works right out the box with PHP so its easy to implement. The pros and cons to this approach are:
**Pros**
- Lighting fast storing and retrieval
- Easy to implement

**Cons**

- Limited to the size of storage on the server or in the browser
- Can be insecure if not properly encrypted
- Is not persistent and the client can cause data to be lost

### Our Examples

The two examples we have above utilize a database storage of session and a cookie session. The database session works by creating a session and storing the id of the session in the browser. The session data is then stored and retrieved by using that id stored in the browser.