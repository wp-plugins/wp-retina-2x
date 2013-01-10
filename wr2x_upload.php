<?php
/**
 * Uploadscreen for selecting and uploading new media file
 *
 * @author      Måns Jonasson  <http://www.mansjonasson.se>
 * @copyright   Måns Jonasson 13 sep 2010
 * @version     $Revision: 2303 $ | $Date: 2010-09-13 11:12:35 +0200 (ma, 13 sep 2010) $
 * @package     wordpress
 * @subpackage  wp-retina-2x
 *
 */

if (!current_user_can('upload_files'))
	wp_die(__('You do not have permission to upload files.', 'wp-retina-2x'));
global $wpdb;
$table_name = $wpdb->prefix . "posts";
$sql = "SELECT guid, post_mime_type FROM $table_name WHERE ID = " . (int) $_GET["attachment_id"];
list($current_filename, $current_filetype) = mysql_fetch_array(mysql_query($sql));
$current_filename = substr($current_filename, (strrpos($current_filename, "/") + 1));

?>
<div class="wrap">

	<?php
	$view = $_GET["pview"];
	$paged = $_GET["paged"];
	$url = admin_url( "upload.php?page=wp-retina-2x&view=replace&noheader=true&pview=$view&paged=$paged&attachment_id=" . (int) $_GET["attachment_id"]);
	$action = "wr2x_replace";
    $formurl = wp_nonce_url( $url, $action );
	if (FORCE_SSL_ADMIN) {
			$formurl = str_replace("http:", "https:", $formurl);
		}
	?>
	<form enctype="multipart/form-data" method="post" action="<?php echo $formurl; ?>">
	<?php
		#wp_nonce_field('wp-retina-2x');
	?>
		<input type="hidden" name="ID" value="<?php echo (int) $_GET["attachment_id"]; ?>" />
		<p><?php echo __("Choose a file to upload from your computer.", "wp-retina-2x"); ?></p>
		<input style='border: 1px solid #B4B4B4; background: #F1F1F1;' type="file" name="userfile" />
		<input class='button-primary' style='font-weight: bold; height: auto;' type="submit" class="button" value="<?php echo __("UPLOAD & REPLACE", "wp-retina-2x"); ?>" />
		<p class="howto"><?php echo __("Note: Please upload a file of the same type (", "wp-retina-2x"); ?><?php echo $current_filetype; ?><?php echo __(") as the one you are replacing. The name of the attachment will stay the same (", "wp-retina-2x"); ?><?php echo $current_filename; ?><?php echo __(") no matter what the file you upload is called.", "wp-retina-2x"); ?></p>
		<p class="howto"><?php echo __("Note 2: The original code for uploading and replacing a file comes from the plugin called <a href='http://wordpress.org/extend/plugins/enable-media-replace'>Enable Media Replace</a> developped by Mans Jonasson. All credit goes to go to him.", "wp-retina-2x"); ?></p>
	</form>
</div>

<?php jordy_meow_footer(); ?>
