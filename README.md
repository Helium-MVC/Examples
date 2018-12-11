# He2MVC - Minimalist Framework For Rapid Prototyping

Welcome to the Helium Examples! These examples are designed to help you learn the many capabilits of not only framework but of many programming concepts as well.
One of Heliums goals is to create the freedom of choice, and allow developers and teams to come up with their own "right way" for building their products.

The example sites can be a beneficial learning lesson for both new and experienced developers.
#### Topics, technologies and skills include:
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
- and more

## Getting Started
Wow, you're still reading!? I must have caught your attention. Getting started is relatively quickly. We use docker to get setup promptly. To begin.

**1) Download Docker**

Head over to https://www.docker.com/, download it and get it running on your computer

**2) Clone The Repo**

In your favorite git GUI or command line, clone the repo to your computer.
```bash
git clone git@github.com:ProdigyView/helium-examples.git
```

**3) Add Sites To Your Etcs Hosts**

We are running this on your local, add these sites to your /etc/hosts local.
> 127.0.0.1       site1.he2examples.local

> 127.0.0.1       site2.he2examples.local

> 127.0.0.1       site3.he2examples.local

> 127.0.0.1       api.he2examples.local

A quick way to do this:
```bash
echo '127.0.0.1       site1.he2examples.local' | sudo tee -a /etc/hosts
echo '127.0.0.1       site2.he2examples.local' | sudo tee -a /etc/hosts
echo '127.0.0.1       site3.he2examples.local' | sudo tee -a /etc/hosts
echo '127.0.0.1       site4.he2examples.local' | sudo tee -a /etc/hosts
```

##### 4) Setup Your Config File

Go to the root directory of the project and cp the sample configuration to the main configuration.
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
There are 3 example sites provided for you to play around with and understand. Through this repo, you will find explanations on several of the pages as ReadME.md files which goes into greater detailer of what is happening. The code has documentation with comments to give explanations and guide you. 

A break down:

#### api
The is the api site. This api is special because it was designed for rapid development and creating a CRUD implementation for any model with only a few lines of code.

#### app
This where the meat of the application lives. This includes modes, libraries, services, email templates, the config, etc.

#### site1
Site 1 is a beginner example site. It uses MySQL, incremental IDs, cookie sessions, file cache, and VueJS - without NPM. This site will not scale well but services as a good starting point.

#### site2
Site 2 is more advanced than site one with Postgresql, UUID using Instagram's approach, database session, Redis caching, AngularJS - without NPM, and improved access control.

#### site3
Site 3 is more of the advanced site. It focuses on uses a microservice approach, assuming that your site will interact with other services. The sessions are in Redis, and starts to take a beginning approach to more functional programming.

## Special Thanks
All the examples templates are from here!
https://startbootstrap.com/template-overviews/clean-blog/