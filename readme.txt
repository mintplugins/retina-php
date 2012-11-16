=== Plugin Name ===
Contributors: johnstonphilip
Donate link: http://mintthemes.com/
Tags: retina, 2x, images 
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin gives you a template tag function which you can use to serve images at 2x or at normal size depending on the users screen. It is different from retina.js because it does not search the DOM and swap out the images for @2x versions, but rather detects once, and serves up the right image the first time.

== Description ==

Gives you a template tag function which you can use to serve images at 2x or at normal size depending on the users screen. It is different from retina.js because it does not search the DOM and swap out the images for @2x versions, but rather detects once, and serves up the right image the first time.

It uses javascript to detect the screen type once, sets a cookie, and then based not he cookie, php loads the correct function for normal screens vs retina screens.

To use the function, install the plugin and then make your image tags look like this

<img src="<?php retina_php_load_image("http://YOURIMAGEURL.jpg"); ?>" />

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the `retina-php` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php retina_php_load_image("http://YOURIMAGEURL.jpg"); ?>` in the src attribute for your image tags. EG: <img src="`<?php retina_php_load_image("http://YOURIMAGEURL.jpg"); ?>`" />

== Frequently Asked Questions ==

= I've installed the plugin. What now? =

Anywhere where you have an image in your template and you want to serve up retina sized images (2x), simple format your image tag like this example: <img src="`<?php retina_php_load_image("http://YOURIMAGEURL.jpg"); ?>`" />

You will also need to upload a double sized version of the image in the same location and put @2x at the end of the filename. EG if the image is called image.jpg, you also need to upload image@2x.jpg to the same location as image.jpg

= Why aren't my images loading? =

Make sure you have uploaded a double sized version of the image in the same location as the original file and put '@2x' at the end of the filename. EG: if the image is called image.jpg, you also need to upload image@2x.jpg to the same location as image.jpg

= Where are the plugin options? =

There aren't any plugin options necessary. You simply put the template tag function in the src attribute of your HTML ing tags. EG: <img src="`<?php retina_php_load_image("http://YOURIMAGEURL.jpg"); ?>`" />

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.


== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`