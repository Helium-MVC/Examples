# Javascript and CSS Builds
Today, most javascript web applications are a mixture of NPM and Webpack for version management and compiling. In Site 1 and Site 2, we do not use either but use two different technologies, Google Closure and Yahoo Compressor. The Builds are managed by XML files and use an ant build process.

## Ant

Ant is a tool by Apache that uses an XML script and Java to execute build commands. Aka think of it as a type of ‘make’ used in compiling C. We use ant against the build.xml files described at the bottom of this article.

## Google Closure
Google Closure is a powerful tool for getting your javascript ready for production. It does a few unique things you should be aware of such as:

##### 1. Getting Rid of Unreachable Code
The compiler shrinks your code by finding unreachable code aka dead code and removing it.

##### 2. Finding Bad/Vulnerable Code
Sometimes we make small mistakes that can have unforeseen effects on our code. For example:

```php
function add(x) {
	y = 5;
	return x+ y;
}
```

The y variable above should have begun with a var or let, but it will probably work when executed while you are developing. Google Closure sees this as a potential problem from both a security and performance perspective. Depending on the compilation settings, Google Closure will either pass or fail the build.

##### 3. Minifying Javascript

After you code passes the checks, Google Closure then minifies the code to make it more compressed to download by removing comments, white spaces, and shortening variables names.

## YUICompresor and ShrinkSafe

The YuiCompressor is a tool by a Yahoo and ShrinkSafe is a tool by Dojokit. What both of these tools have in common is they can minify your CSS to a more compact version. Either one can be used to shrink our scrips. Minifying your CSS will decrease the amount of bandwidth in serving files and small files decrease the load time of your page, thus improving the experience for your users.

## Running The Build Scripts

The build scripts are located in Site 1 and Site 2 at

> site1/build/build.xml
> site2/build/build.xml

We can execute a build script with the ant command by doing the following:

```bash
cd site2/build/
ant
```

And that’s it! If your build fails, it will display an error. If it passes, you will have a new file script.js fit and style.css file in your public_html directories.


