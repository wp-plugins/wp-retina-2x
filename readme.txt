=== WP Retina 2x ===
Contributors: TigrouMeow
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JAWE2XWH7ZE5U
Tags: retina, iphone, macbookpro, apple, images, admin, attachment, media, files
Requires at least: 3.4
Tested up to: 3.4.1
Stable tag: 0.2.4

Make your website look beautiful and smooth on Retina (high-DPI) displays.

== Description ==

This plugin creates the image files required by the Retina (high-DPI) displays and it displays them to your visitors accordingly. Your website will now look beautiful and smooth on every device.

It handles two different methods to serves the images to your visitors. Pick the one that works best with your hosting and environment.

== Changelog ==

= 0.1 =
* Very first release.

= 0.1.4 =
* Fixed a minor bug that was displaying the wrong resolution in the Retina column in the Media Manager.

= 0.1.6 =
* Simplified the code of the server-side method.

= 0.1.8 =
* Resolved PHP warnings and notices.

= 0.2 =
* New feature: the Retina Dashboard.
* Can now generate Retina files in bulk.
* Fixed: the cropped images were not 'cropped'.
* The Retina Dashboard and the Media Library's column can be disabled via the settings.
* Resolved more PHP warning and notices.

= 0.2.1 =
* Removed 'error_reporting' (triggers warnings and notices with other plugins).
* Fix: on uninstall/disable, the .htaccess will be updated properly.

= 0.2.2 =
* Fix: the recommended resolution shown wasn't the most adequate one.
* Fix: in a few cases, the .htaccess wasn't properly generated.
* Fix: files were renamed to avoid conflicts.
* Fix: there was sometimes an issue with vertical images.
* Added: paging for the Retina Dashboard.
* Added: 'Generate for all files' handles and shows if there are errors.

= 0.2.4 =
* Fix: SQL optimization & memory usage huge improvement.

== Installation ==

Quick and easy installation:

1. Upload the folder `wp-retina-2x` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Check the settings of WP Retina 2x in the WordPress administration screen.
4. Check your Media Library where you can find the new 'Retina' column.
5. Check the Retina Dashboard.
6. Read the tutorial about the plugin on <a href='http://www.totorotimes.com/news/retina-display-wordpress-plugin'>Totoro Times</a>.

== Frequently Asked Questions ==

= What does this plugin do? =
It creates the image files required by the Retina devices. In the case the resolution of the original images are not high enough, you will see a warning in the Media Library where you will be able to re-upload bigger images.

The plugin then recognizes different devices and send the images required accordingly.

= What are those new images? =
The new images use the same name as your original files, with an "@2x" string added right before the extension. For example, if you have a Gundam-150x150.jpg file, a new Gundam-150x150@2x.jpg will be created. Its size will be doubled. This naming convention actually comes from Apple.

= Can I create all the Retina images at once for my whole Media Library? =
Yes, check the Retina Dashboard screen.

= I don't have a Retina device, how can I check whether it works or not? =
Go to the closest Apple Store and try it out! More seriously, you can check the "Debug" option in the plugin settings. Then your blog will always behave as if the client is using a Retina Display.

= This plugin is cool, how can I thank you? =
Thanks for asking! :p Please visit Totoro Times (http://www.totorotimes.com), and please talk about this plugin and this website to your friends :) That would definitely be cool.

= I don't understand a thing! =
Please check my tutorial and introduction to Retina Displays on <a href='http://www.totorotimes.com/news/retina-display-wordpress-plugin'>Totoro Times</a>.

== Screenshots ==

1. A new column in the Media Library.
2. Basic Settings
3. Advanced Settings
4. Retina Dashboard

== Wishlist ==

Do you have suggestions? Feel free to contact me at http://www.totorotimes.com.