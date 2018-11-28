# He2 Model Basics

Welcome to the models section. Models allows access to the database and a create virtual representation of the database connected to your website, aka an ORM. Models are compromised of the 3 important features: **virtual schema, validation, and joins**. CRUD operations are in a different section.

### Model Names & Relationship To The Database

The models in He2 have a very strong relationship to the database, starting with the naming of the model. For example, if you were to name your model *'Posts'*, that model in Helium would create a table named *posts* and perform CRUD operations on it. A model named *Users* relates to the table *users*, etc.

What about camel case notations if we name our models *UserPasswords* or *PostTags*. Helum will automatically separate the two words by their capital letters, add a hyphen, and lowercase them. So:
- UserPasswords = user_passwords
- PostTags = post_tags

**Advanced Concept:** While the model name automatically ties to a table, you can change the table it has access too with the $_config. For examples:
```php
class Posts extends HModel {
	protected $_config = array('table' => 'articles')
}
```

This model will now utilize the articles table, even through its name is Posts.

### Virtual Schema
I really dislike not knowing the schema of a model when I am trying query or save data. Heliums Model allows you to understand and even create tables with columns without ever writing a line of query. It does this via a virtual schema.

In every model, you will define a schema as such:

```php
class Users extends HModel {
       protected $_schema = array(
		'user_id' => array('type' => 'serial', 'primary_key' => true, 'auto_increment' => true),
		'name' => array('type' => 'string', 'precision' => 255, 'default' => '', 'cast' => 'santize'),
		'date_registered' => array('type' => 'datetime', 'default' => 'now()'),
		'last_login' => array('type' => 'datetime', 'not_null' => false, 'default' => null)
	);
}
```
The first key, in the example above being **user_id, name, date_registered**, etc, is the name of the column in the database. The associated array then defines the attributes of that column.

**type:** Types can be int, double, string, floats, texts and a variety of other fields. The types automatically convert to the database you are using. For example, the string will convert to varchar in MySQL, bool with convert to tinyint in Postgres, and so forth.

**primary_key:** This option will determine if the field should be registered as the primary key. The default is false.

**precision:** Certain fields such as varchar require a definition. So precision => 255 when combined with string will create varchar(255).

**default:** Give the column a default value to insert into the database. Or it can be a function such the date() which will give the current DateTime if the database has the function, ie Postgres.

There are a variety of other fields such as not_null which allow null values for a column, or cast, which can be a special operation of sending the data through a function before inserting into the database. Personally, I use casting to scrub data clean from any malicious attacks such as someone trying to put javascript in my database.

**Important: **Once the schema is set, on CRUD operations is will automatically remove values not in the schema from being inserted into the database. The schema can also be used to create tables and columns with schema check.

### Validation

Validation can be used on CRUD operation to ensure we have the correct data before attempting to insert or update the database. Validation is tied into the models in a validation array like below:

```php
class Users extends HModel {
 	protected $_validators = array(
		'first_name' => array(
			'notempty' => array('error' => 'First name is required'),
		), 
		'email' => array(
			'notempty' => array('error' => 'Email address name is required'),
			'email'=>array(
				'error'=>'A valid email address is required.',
				'event' => array('create', 'update_email')
			),
			'unique_email' => array(
			'	error' => 'Email address is already registered. Please login or use the forgot password',
				'event' => array('create')
			)
        ),
    );
}
```

When we a CRUD operation such as:
```php
$user = new Users();
$user -> create($data);
```

It will run checks against those validators. For example, it will check if the first_name field is not empty, and if it is the CRUD operation fails. For the email, there are more rules where it checks if the email is already registered, is a valid email, and if its empty.

The error field defines the error message that is displayed on a failure, and the optional event field will only run the check on certain events if specified. For example, *'create'* and *'update'* are considered separate events. You can also create your own unique events.

### Joins
In an MVC, you can use the same join in multiple controllers - why should you have to write that join every single time and then update them all when changes occur? In Helium, we can define joins in the model and how it relates to other other models. Example below:
```php
class Users extends HModel {
	protected $_joins = array(
        	'post' => array('type' => 'join', 'model' => 'app\models\uuid\Posts', 'on' => 'users,user_id = posts.user_id'),
		'password' => array('type' => 'natural', 'model' => 'app\models\uuid\UserPasswords'),
    );
}
```

The first keys in the array, *'post'* and *'password'*, is a reference in our CRUD operation. For example, if we want to find all the users with the posts they have published:

```php
$users  = Users::findAll(array(
	'join' => array('post')
));
```

That will use our 'post' join. Now going into the join properties we have:

type: This is referring to the type of join. We can use left, right, natural and join. As the names might suggest, left is LEFT JOIN and natural is NATURAL JOIN.

model: The model is will call another models table name. It is important to use this approach in case per the above instance, we decide to change the name of a table in its model, the join will be unaffected.

on: The on is used to specify how to relate the two fields. Normally, we would use table1.id = table2.id.

### Finished

Those at the basics of constructing your models. Other chapters include:
- CRUD Operations
- Automatic Schema Generation
- Model Adapters
- Model Filters
- Model Observers

