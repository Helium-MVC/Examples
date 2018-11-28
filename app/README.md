# The Applications Structure

The app is the core of your product that relates to all the site(s) and properties pertaining to a single product. While this current setup is only an example guideline, how you structure and design your application is 100% up to you.

#### build
While the example sites contains both VueJs and Angular 1, neither of these were built with npm nor compiled with webpack. A long time ago in a faraway galaxy and before the existence of NodeJS, javacript and css had to be managed in different ways. The build folder contains tools for compiling assets - ole skool.

#### config
The configuration contains the config.php, which is a registry of for variables that are accessed site which. Information such as database connections to email configurations reside in the config, along with a special file for validation.

#### emails
This example site has the ability to send emails. Per the config talk above, if you enter your SMTP in the mail config, emails will go out. For templating, the emails use basic php.

#### libraries
Not every library can has been conveniently added to composer. The libraries folder is a collection of libraries that still have to be added and maintained manually.

#### models
The models are the life-blood the application. The models interface with the database, are used in controllers in CRUD operation, have validation. They also created a virtual schema with relations to objects in the DB.

#### services

Services are tools that create extra functionality and support the operations executed by models and controllers. This includes handling Session, storage, authentication and other services.