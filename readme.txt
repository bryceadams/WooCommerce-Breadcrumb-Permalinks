=== Plugin Name ===
Contributors: bryceadams, toro_unit
Donate link: http://bryceadams.com
Tags: woocommerce, permalinks, permalink, breadcrumb permalinks, woocommerce category url, ancestory permalinks, ancestory
Requires at least: 4.0.0
Tested up to: 4.3.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you have breadcrumb permalinks for WooCommerce, including parent and multiple child categories in a single product's URL.

== Description ==

WooCommerce allows you have to have plain old ugly URLs, pretty URLs with just the product name, prettier URLs with the product name and it's category but... what about when you want a sexy URL with the:

* Product Name
* Child Category
* Parent Category
* Shop Name

For example:

`http://meawesome.com/items/cool-stuff/cooler-stuff/the-stuff/`

Now that's a sexy URL.

Install the plugin, set it up under **Settings > Permalinks** and start improving your SEO or whatever.

The plugin also supports multiple child categories in the URL, so you could even have a URL like this:

`http://meawesome.com/items/cool-stuff/cooler-stuff/mooarrrr-stuff/the-stuff/`

> #### PHP Requirement
> This plugin requires PHP version 5.4 or higher.<br />
> If you're still at PHP 5.2, it's time to update. [Read here why and how](http://www.wpupdatephp.com/update/).

== Installation ==

I'll make this as simple as possible.

1. Either download the plugin and upload it or search for it under **Plugins > Add New**. Activate it, too.
2. Go to your **Settings > Permalinks** and head to the bottom.
3. Make the **WooCommerce Product Permalink Base** something like `/items/%product_cat%` - it doesn't have to be items. It could be `shop` or `animals` or `bryce-is-awesome-store`.
4. Make the **Shop Permalinks Base** the same as the 'base' just set above. If you went with the `/items/%product_cat%` one, this option would need to be `items`.
5. Save & be awesome!

![Yo](http://i.cloudup.com/o9XpoK7Hqp.png)

== Frequently Asked Questions ==

= I followed your instructions but keep getting those damn 404s! =

Well that's no good - maybe you don't have standard WordPress pretty permalinks enabled? Go to the top of the **Settings > Permalinks** page and make sure the **Common Settings** is something like **Post Name**.

= It's all going to hell and I need help! =

Please calm down and start a support thread. This is a pretty experimental, 'breaking-the-boundaries-of-what-we-thought-was-possible' kind of plugin, so things can get of control.

= What about foo bar? =

I don't have the answer.

== Screenshots ==

1. What your settings should look like (but `items` can be whatever you want).
2. An example of an awesome URL, made possible by this plugin.

== Changelog ==

= 1.1.0 =
* The day Hiroshi Urabe made it a lot better, by doing...
* Fixing permalinks for multiple child category levels.
* Using add_permastruct for registering the product permalink.
* PHP 5.4 required - if that's a problem for you, use an older version or don't use the plugin - http://wpupdatephp.com/update/

= 1.0.0 =
* The day it started.

== Upgrade Notice ==