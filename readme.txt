=== WP Retina 2x ===
Contributors: TigrouMeow
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JAWE2XWH7ZE5U
Tags: retina, images, image, admin, attachment, media, files, iphone, ipad, plugin, picture, pictures
License: GPLv2 or later
Requires at least: 3.5
Tested up to: 3.5
Stable tag: 1.2.0

Make your website look beautiful and smooth on Retina (high-DPI) displays such as the MacBook Pro Retina and the iPad.

== Description ==

This plugin creates the image files required by the Retina (high-DPI) displays and it displays them to your visitors accordingly. Your website will look beautiful and sharp on every device. The retina images will be generated for you automatically, served, and you will be able to control everything from the Retina Dashboard.

It handles three different methods to serves the images to your visitors. Pick the one that works best with your hosting and environment. WordPress Multi-site are supported as well.

Languages: English, French.

= Quickstart =

1. Set your option (for instance, you probably don't need retina images for every sizes set-up in your WP).
2. Generate the retina images (required only the first time, then images are generated automatically).
3. Check if it works! - if it doesn't, read the FAQ, the tutorial, and check the forums.

== Changelog ==

= 1.2.0 =
* Add: new method called "HTML Rewrite".
* Change: .htaccess regex for images.
* Add: donation button (can be removed, check the FAQ).
* Change: new icons.
* Add: french translation.
* Fix: little fixes.

= 1.0.0 =
* Change: enhancement of the Retina Dashboard.
* Change: better management of the 'issues'.
* Change: handle images with technical problems.
* Fix: random little fixes again.

= 0.9.8 =
* Change: upload is now HTML5, by drag and drop in the Retina Dashboard!
* Add: delete all retina files button.
* Change: hide the columns to ignore in the Retina dashboard.
* Change: generate button only generates pending items (images).
* Fix: performance boost!
* Fix: random little fixes.

= 0.9.6 =
* Fix: warnings when uploading/replacing an image file.

= 0.9.4 =
* Fix: esthetical issue related to the icons in the Retina dashboard.
* Fix: warnings when uploading/replacing an image file.

= 0.9.2 =
* Change: Media Replace is not used anymore, the code has been embedded in the plugin directly.

= 0.9 =
* Fix: code cleaning.
* Fix: no more notices in case there are weird/unsupported/broken image files.

= 0.8 =
* Fix: Works with WP 3.5.

= 0.4.2 =
* Update: to the new version of Retina.js (client-method).
* Fix: updated rewrite-rule (server-method) that works with multi-site.

= 0.4 =
* Fix: support for Network install (multi-site). Thanks to Jeremy (Retina-Images).

= 0.3.4 =
* Change: Retina.js updated to its last version (should be slighlty faster).
* Change: Retina-Images updated to its last version (now handles 404 error, yay!).
* Fix: using a Retina display, the Retina Dashboard was not looking very nice.
* Fix: the "ignored" media for retina are handled in a better way.
* Change: the FAQ was improved.

= 0.3.0 =
* Fix: was not generating the images properly on multisite WordPress installs.
* Add: warning message if using the server-side method without the pretty permalinks.
* Add: warning message if using the server-side method on a multisite WordPress install.
* Change: the client-method (retina.js) is now used by default.

= 0.2.9 =
* Fix: in a few cases, the retina images were not generated (for no apparent reasons).

= 0.2.8 =
* Fix: the retina image was not being generated if equal to the resolution of the original image.
* Add: optimization and enhancement of the issues management.
* Add: a little counter icon to show the number of issues.
* Add: an 'IGNORE' button to hide issues that should not be.

= 0.2.6 =
* Fix: simplified version of the .htaccess directive.
* Fix: new version of the client-side method (Retina.js), works 100x faster.

= 0.2.4 =
* Fix: SQL optimization & memory usage huge improvement.

= 0.2.2 =
* Fix: the recommended resolution shown wasn't the most adequate one.
* Fix: in a few cases, the .htaccess wasn't properly generated.
* Fix: files were renamed to avoid conflicts.
* Add: paging for the Retina Dashboard.
* Add: 'Generate for all files' handles and shows if there are errors.

= 0.2.1 =
* Removed 'error_reporting' (triggers warnings and notices with other plugins).
* Fix: on uninstall/disable, the .htaccess will be updated properly.

= 0.2 =
* Add: the Retina Dashboard.
* Add: can now generate Retina files in bulk.
* Fix: the cropped images were not 'cropped'.
* Add: The Retina Dashboard and the Media Library's column can be disabled via the settings.
* Fix: resolved more PHP warning and notices.

= 0.1.8 =
* Fix: resolved PHP warnings and notices.

= 0.1.6 =
* Change: simplified the code of the server-side method.

= 0.1.4 =
* Fix: the wrong resolution was displayed in the Retina column of the Media Manager.

= 0.1 =
* Very first release.

== Installation ==

Quick and easy installation:

1. Upload the folder `wp-retina-2x` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Check the settings of WP Retina 2x in the WordPress administration screen.
4. Check the Retina Dashboard.
6. Read the tutorial about the plugin on <a href='http://www.totorotimes.com/news/retina-display-wordpress-plugin'>Totoro Times</a>.

== Frequently Asked Questions ==

= What does this plugin do? =
It creates the image files required by the Retina devices. In the case the resolution of the original images are not high enough, you will see a warning in the Media Library where you will be able to re-upload bigger images. The plugin then recognizes different devices and send the images required accordingly.

= What are those new images? =
The new images use the same name as your original files, with an "@2x" string added right before the extension. For example, if you have a Gundam-150x150.jpg file, a new Gundam-150x150@2x.jpg will be created. Its size will be doubled. This naming convention actually comes from Apple.

= Can I create all the Retina images at once for my whole Media Library? =
Yes, check the Retina Dashboard screen (under the Media menu).

= I don't have a Retina device, how can I check whether it works or not? =
Go to the closest Apple Store and try it out! More seriously, you can check the "Debug" option in the plugin settings. Then your blog will always behave as if the client is using a Retina Display.

= Does it work for other High-DPI devices? =
I tried it on a few High-DPI mobile devices and it works fine.

= It doesn't work, what should I check? =
* Are the images created? Check the Retina Dasboard (under Media).
* Are you using an "Image Size" in your posts that is NOT "Full Size"? The plugin generates Retina images for all your images except (obviously) the "Full Sizes" and the ones you opted-out in the Settings.
* Are you using Cloudflare? The Cloudflare cache is too "powerful" at the moment, so please set the plugin to use the Client-side method.

= The logo or icons of my theme are not displayed as Retina, why? =
The plugin can transform the images that go through the WordPress API and the 'Image Sizes' properly (which means that they are part of the Media Library). Themes often uses a one-time customized size for the logo, and for that reason the image wouldn't be taken care of by the plugin. The easiest way to go around this is to create the Retina image by yourself. For example, if you are logo is 200x100 and named 'logo.png', upload a 400x200 version of that logo named 'logo@2x.png' next to the other one. It will work immediately.

= My logo / images appeared twice bigger on Retina =
Let's say you have a logo which is 200x400. The retina logo would be 400*800, but it should STILL be shown as a 200x400 image relatively to the rest of your website. The workaround is to keep it simple and neat: always set the (normal) width and the height for all your images (in HTML directly or via CSS).

= I have issues with images in my slideshows / sliders =
That is unfortunately the most infamous issue to expect with this "Retina" technology. A lot of developers ignore it, sometimes don't code properly, think that what they did is "enough". You should ask the developer to do something about it, and if he cares, he will do it. You can also do it by yourself, check the next question.

= I have issues with images loaded on the fly / asynchronously =
If you use the Server-side, it might work properly. However, with the Client-side, you will probably have issues. The explanation is that the script cannot apply Retina on the images it is not aware of. The Retina script must be called after those operations. You can see how to resolve this issue <a href='https://github.com/imulus/retinajs/issues/19'>on this Github discussion</a>. Basically, it involves adding this code after loading new images:

`$('img').each(function(){
  new RetinaImage(this);
});`

= It's not working with multisite + subdomains + the server-method, what can I do? =
Jeremy the creator of "Retina-Images" helped me with this issue. Thanks to him, it nows work perfectly since WP Retina 2x 0.4! Although you will have to do edit the .htaccess file by yourself, and to add the RewriteRule "^files/(.+) wp-content/plugins/wp-retina-2x/wr2x_image.php?ms=true&file=$1 [L]" as the first RewriteRule.

= I use a CDN and it doesn't work =
The retina files have to be sent to the CDN, then the plugin should work fine (using the client-method). The plugin in charge of sending the files to the CDN is not the WP Retina 2x plugin, and cannot. 

The developer of the other plugin has to implement support for the Retina files. It should be very easy! In order to help those developers, I created two WordPress actions (when a retina file is added or removed) and they both send two arguments: the attachment id and the full path to the retina file.

* wr2x_retina_file_added
* wr2x_retina_file_removed

= WordPress stops responding when [...] =
Maybe you don't have enough memory allocated to PHP or the script takes longer than the maximum execution time limit. You can change those values using the PHP Configuration File (php.ini):

* php_value memory_limit = "128M";
* max_execution_time = 360;

... or by modifying the WordPress PHP files (wp-settings.php ideally):

* ini_set('memory_limit', '512M');
* ini_set('max_execution_time', 300);

Please note that it doesn't work with some cheap web hosts, as they don't want you do to that instead. The real issue can also be tracked in the PHP error logs.

= It still doesn't work! =
Create a new support thread <a href='http://wordpress.org/support/plugin/wp-retina-2x'>here</a> or contact me directly, and always send me a screenshot copy of your "Image Sizes" settings in Settings -> Media, and another screenshot of your Retina Dashboard. I will do my best to help you.

= I still don't understand a thing! =
Please check my tutorial and introduction to Retina Displays on <a href='http://www.totorotimes.com/news/retina-display-wordpress-plugin'>Totoro Times</a>. You should also have a look at the WordPress forums.

= This plugin is great, how can I thank you? =
Thanks for asking, since we, developers, get usually 10x more complains than thanks! I don't blame anyone, I persnnally don't say thank you to every single developer of all the software I am using ;) But if you are happy, please write a <a href='http://wordpress.org/support/view/plugin-reviews/wp-retina-2x'>nice review here</a>. You can also do one of the things I ask in the following question.


= Can I contact you? =
Yes, sure, you can! But since the plugin got popular, I get many emails everyday which consume almost 10% of my whole lifetime (not kidding! + it's rarely issues coming from the plugin itself). Before contacting me, please do one of the following:

* Tweet a page of <a href='http://www.totorotimes.com'>Totoro Times</a> to more than 500 followers, mentioning @TigrouMeow
* Facebook a page of <a href='http://www.totorotimes.com'>Totoro Times</a> to more than 600 friends/followers (using a page is fine too)
* Link a page of <a href='http://www.totorotimes.com'>Totoro Times</a> from your website/blog
* Make <a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JAWE2XWH7ZE5U'>a donation</a> and buy me some sushi (I live in Tokyo and they are really good here!)

Then please <a href='http://www.totorotimes.com/about/'>contact me</a> :) Thank you!

= I donated, can I get rid of the donation button? =
Of course. I don't like to see too many of those buttons neither ;) You can disable the donation buttons from all my plugins by adding this to your wp-config.php:
`define('WP_HIDE_DONATION_BUTTONS', true);`

== Screenshots ==

1. The Retina Dashboard
