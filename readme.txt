=== WP Retina 2x ===
Contributors: tigroumeow
Tags: retina, iphone, macbookpro, apple, images, admin, attachment, media, files
Requires at least: 3.4
Tested up to: 3.4
Stable tag: 0.1.2

Make your website look beautiful and smooth on Retina (high-DPI) displays.

== Description ==

This plugin creates the image files required by the Retina (high-DPI) displays and it displays them to your visitors accordingly. Your website will now look beautiful and smooth on every device.

In the case the resolution of the original images are not high enough, you will see a warning in the Media Library where you will be able to re-upload bigger images.

It handles two different methods (for now) to serves the images to your visitors. Pick the one that works best with your hosting and environment! 

Credits: Retina Images (http://retina-images.complexcompulsions.com/) has been developed by Jeremy Worboys and Retina.js has been developed Imulus (http://retinajs.com/).

== Changelog ==

= 0.1.2 =
* Text corrections.
* Added a script for the server-side method to make sure the Retina Display is detected.

= 0.1 =
* Very first release.

== Installation ==

Quick and easy installation:

1. Upload the folder `wp-retina-2x` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Check the settings of WP Retina 2x in the WordPress administration screen
4. Check your Media Library where you can find the new 'Retina' column

== Frequently Asked Questions ==

= What does this plugin do? =
It creates the image files required by the Retina devices. In the case the resolution of the original images are not high enough, you will see a warning in the Media Library where you will be able to re-upload bigger images.

The plugin then recognizes different devices and send the images required accordingly.

= What are those new images? =
The new images use the same name as your original files, with an "@2x" string added right before the extension. For example, if you have a Gundam-150x150.jpg file, a new Gundam-150x150@2x.jpg will be created. Its size will be doubled. This naming convention actually comes from Apple.

= Can I create all the Retina images at once for my whole Media Library? =
Unfortunately, the bulk action functionality has not yet been developed. I want to make sure everything works perfectly for everyone before adding it.

= I don't have a Retina device, how can I check whether it works or not? =
Go to the closest Apple Store and try it out! More seriously, you can check the "Debug" option in the plugin settings. Then your blog will always behave as if the client is using a Retina Display.

= This plugin is cool, how can I thank you? =
Thanks for asking! :p Please visit Totoro Times (http://www.totorotimes.com), and please talk about this plugin and this website to your friends :) That would definitely be cool.

== Screenshots ==

1. A new column in the Media Library.
2. Basic Settings
3. Advanced Settings

== Wishlist ==

Do you have suggestions? Feel free to contact me at http://www.totorotimes.com.