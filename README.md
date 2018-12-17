# He2MVC - Minimalist Framework For Rapid Prototyping

Welcome to the Helium Examples! These examples are designed to help you learn the many capabilits of not only the framework but of many core programming concepts 
used to extend the framework.

There is also a lot of general educational information provided through the README.md.
One of Heliums goals is to create the freedom of choice, and allow developers and teams to come up with their own "right way" for building their products.

#### Beneficial learning lessons covered for both new and experienced developers:
- Browser Sessions and Database Sessions
- AngularJS, VueJS, React implementations
- Microservices
- Design Patterns: Dependency Injection, Adapters, Filters, Observers
- Functional Programming
- Mysql, Postgresql, MongoDB, Firebase
- UUID for databases
- Nginx Virtual Hosts
- Protopying API
- Security Features - CSFR Tokens, API, Authentication
- Twig Templating
- and more

## Getting Started
Getting started is relatively easy and quick. We use docker to get setup promptly. To begin.

**1) Download Docker**

Head over to https://www.docker.com/, download it and get it running on your computer.

**2) Clone The Repo**

In your favorite git GUI or command line, clone the repo to your computer.
```bash
git clone git@github.com:/Helium-MVC/Examples.git
```

**3) Add Sites To Your Etcs Hosts**

We are running this on your local, add these sites to your /etc/hosts local.
> 127.0.0.1       site1.he2examples.local

> 127.0.0.1       site2.he2examples.local

> 127.0.0.1       site3.he2examples.local

> 127.0.0.1       api.he2examples.local

A quick way to do this, copy and paste the below in your terminal:
```bash
echo '127.0.0.1       site1.he2examples.local' | sudo tee -a /etc/hosts
echo '127.0.0.1       site2.he2examples.local' | sudo tee -a /etc/hosts
echo '127.0.0.1       site3.he2examples.local' | sudo tee -a /etc/hosts
echo '127.0.0.1       api.he2examples.local' | sudo tee -a /etc/hosts
```

##### 4) Setup Your Config File

Go to the root directory of the project and copy the sample configuration as the main configuration.
```bash
cp app/config/config.sample.php app/config/config.php
```

**5) Boot Up Docker!**

Go to the folder where you cloned the repo from git and lets boot up docker! This will take a few minutes:
```bash
docker-compose up
```

**6) Composer And Database Setup**

Next we have to install the PHP packages via composer and sync our database. First ssh into your docker instance:
```bash
docker exec -it he2_php bash
```
Then we are going to the sites root directory and installing composer
```bash
cd /code
composer install
```
When that is done we are going to set up your database with a few commands.
```bash
cd /code
php site1/helium.php DbCli schemacheck
php site2/helium.php DbCli activateExtensions
php site2/helium.php DbCli schemacheck
```

**7) Good To Go!**

Now that your environment is set up you are good to go! Those sites above will now work on your browser. Happy exploring!

## How To Learn He2MVC Really Quickly
There are 3 example sites provided for you to play around with and understand. Through this repo, you will find explanations on several of the pages as ReadME.md files which goes into greater detailer of what is happening. The code also has documentation as comments to give explanations and help guide you. 

A break down:

#### API - Restful CRUD
##### Difficulty - 8/10
The is the api site. This api is special because it was designed for rapid development and creating a CRUD implementation for any model with only a few lines of code.

#### app
This is  where the meat of the application lives. This includes models, libraries, services, email templates, the config, etc. Classes here are univerally accessible across all the sites.

#### Site 1 - Mysql, VueJs, Basic MVC Concepts with Business Logic In Models
##### Difficulty - 2/10
Site 1 is a beginner example site. It uses MySQL, incremental IDs, cookie sessions, file cache, and VueJS - without NPM. This site will not scale well but services as a good starting point for developers beginning in MVC.

#### Site 2 - Postgresql, AngularJS, Advanced MVC Concepts with Business Logic In Services
##### Difficulty - 5/10
Site 2 is more advanced than Site 1 with Postgresql, UUID using Instagram's approach, database session, Redis caching, AngularJS - without NPM, and improved access control.

#### Site 3 - Firebase, Twig, Facades, Factories, Microsevices Concept
##### Difficulty - 8/10
Site 3 is the most advanced site behind the api. It focuses on uses a microservice approach, assuming that your site will interact with other services. The sessions are in Redis, and starts to take a beginning approach to more functional programming.

## Special Thanks
All the examples templates are from here!
https://startbootstrap.com/template-overviews/clean-blog/