# Libraries

Most PHP Libraries today can be installed through composer and have versioning easily managed. But not every library has been added to composer for a variety of reasons, but those libraries can still be used in your application.

#### Global Libraries
In this folder, we have several libraries that considered global. The defining characteristic of a global library is that can be re-used in multiple sites.

#### Local Libraries

Local libraries are libraries that only apply to that site. For example, site2/libaries/RedisCache is a local library only for Site 2 because it is the only site that is caching with Redis.

## Loading Libraries

Even those the libraries are placed in the library folder, they have to loaded in each sites bootstrap to take affect. For example, if we go to the library folder in site2/config/libraries.php, we come up with the following examples:

```php
<?php
//Load a library that is in the global path, in the app/libraries folder
PVLibraries::addLibrary('MailLoader', array('path' => PV_ROOT.DS.'app' .DS.'libraries'.DS.'MailLoader'.DS, 'explicit_load' => true));

//Load a library that is local only to the current site, inside the libraries folder
PVLibraries::addLibrary('RedisCache', array('explicit_load' => true));
?>
```
Libraries are added with PVLibraries::addLibrary with the first value being the key, or library name. The 2nd value is an array of options used to configure how the library is loaded. The configuration options passed into the library are defined below:

##### path
The path is the location of the library if it is not in a local scope. In other words, we can place our library anywhere and point to it.

##### explicit_load
Some libraries can easily be autoloaded, especially if they are namespaced. Others will have mandates that require the library be included on the application boot. Setting this option to true will force all the files in the library to be included.

##### Namespaced
If the library is namespaced, we have to tell the loader this. We merely change this boolean to true in the options.