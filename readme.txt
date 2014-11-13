=== WP Retina 2x ===
Contributors: TigrouMeow
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=H2S7S3G4XMJ6J
Tags: retina, images, image, admin, attachment, media, files, iphone, ipad, plugin, picture, pictures
License: GPLv2 or later
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 2.2.0

Make your website look beautiful and smooth on etina (high-DPI) displays such as the MacBook Pro Retina and the iPad. I am trying to keep a tutorial about this plugin up to date and comprehensive here: http://www.totorotimes.com/wp-retina-2x-plugin/.

== Description ==

This plugin creates the image files required by the Retina (high-DPI) devices and displays them to your visitors accordingly. Your website will look beautiful and sharp on every device. The retina images will be generated for you automatically (you can also do it manually), served, and you will be able to control everything from the Retina Dashboard.

It supports 4 different methods to serve the images to your visitors:

* PictureFill: The Picturefill method rewrites the HTML on-the-fly in order to use the new SRCSET. Since it is not supported by the browsers yet, the JS polyfill Picturefill is used to load the images. It is now the recommended method.
* Retina.js: The Retina JS method is a 100% JS solution. The HTML loads the normal images, then if a retina device is detected, the retina images will be loaded. It is fail-safe but not efficient (images are loaded twice).
* IMG Rewrite: The IMG Rewrite method rewrites IMG's SRC tags on-the-fly with the retina images directly if the device supports them. This method does not work with most caching solutions.
* Retina-Images: The Retina-Images method uses a server handler: the images will be loaded through the Retina-Images PHP handler. Your .htaccess will be modified automatically.

Pick the one that works best with your hosting and environment. WordPress Multi-site are supported as well. WP Retina 2x also loves WPEngine and strongly recommend it for your hosting. I am trying to keep a tutorial about this plugin up to date and comprehensive here: http://www.totorotimes.com/wp-retina-2x-plugin/.

Languages: English, French.

= Quickstart =

1. Set your option (for instance, you probably don't need retina images for every sizes set-up in your WP).
2. Generate the retina images (required only the first time, then images are generated automatically).
3. Check if it works! - if it doesn't, read the FAQ, the tutorial, and check the forums.

== Changelog ==

= 2.2.0 =
* Change: Links, documentation, readme.

= 2.0.8 =
* Add: Option to disable Retina in the WP Admin. Actually now disabled by default to avoid an issue with NextGen.
* Add: Option to disable the loading of the PictureFill script.
* Update: PictureFill, from 2.1.0 (2014-08-20) to 2.1.0 (2014-10-07).
* Change: Flattr button doesn't pop anymore. I know, that was annoying ;)
* Info: I am thinking of adding features through a pro version. I would love to know your thoughts. Please check this: https://wordpress.org/support/topic/what-about-a-pro-version

= 2.0.6 =
* Works with WP 4.

= 2.0.4 =
* Fix: PictureFill method now handles special characters.
* Change: Performance boost for PictureFill method.
* Change: Use PHP Simple HTML DOM instead of DOMDocument for PictureFill.
* Update: PictureFill, from 2.1.0 (2014-06-03) to 2.1.0 (2014-08-20).

= 2.0.2 =
* Fix: PictureFill issue with older version of PHP
* Fix: issue with boolean values in the options
* Fix: PictureFill method now ignore fallback img tags found in picture tags
* Change: logging enhanced for PictureFill

= 2.0.0 =
* Info: The new method PictureFill is currently beta but I believe is the best. Please help me test it and participate in the WordPress forums if you find any bug or a way to enhance it. Also, thanks a lot to those who made donations! :)
* Change: new PictureFill method
* Change: texts and method names
* Fix: debug mode was not logging
* Update for WordPress 3.9.1

= 1.9.4 =
* Update: for WordPress 3.9.
* Update: MobileDetect, from 2.6.0 to 2.8.0.
* Update: RetinaJS, from 1.1 to 1.3.
* Info: if you want new features / enhancements, please add a message in the WordPress forum and consider a little donation (or a flattr) and I will do my best to include it in the upcoming 2.0 version of the plugin.

= 1.9.2 =
* Fix: issue with the src-set method.
* Change: thumbnail size was reduced in the Retina dashboard.
* Update: French translation.

= 1.9.0 =
* Fix: issues when using custom UPLOADS / WP_SITEURL constants.
* Info: Please come say hello or make a donation if you love this plugin :)
* Info: I am getting married this year!

= 1.8.0 =
* Fix: HTML5 issues with the HTML srcset method.
* Change: RetinaJS (client-side) was updated to 1.1.0.

= 1.6.2 =
* Fix: encoding issue with the HTML srcset method.

= 1.6.0 =
* Add: HTML srcset method.
* Change: use one file less.
* Change: most methods were renamed nicely.

= 1.4.0 =
* Add: german translation and italian translation.
* Add: option to ignore mobile.
* Fix: avoid warnings if any issues during HTML Rewrite.
* Fix: generate button was not working anymore.
* Change: more logging for debug mode.
* Add: progress % during operations.

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
6. Read the tutorial about the plugin on <a href='http://www.totorotimes.com/wp-retina-2x-plugin/'>Totoro Times</a>.

== Frequently Asked Questions ==

The FAQ can be found at http://apps.meow.fr/wp-retina-2x/faq/.

== Screenshots ==

1. Retina Dashboard
2. Basic Settings
3. Advanced Settings
