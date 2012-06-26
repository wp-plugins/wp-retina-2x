<?php
/*
Plugin Name: WP Retina 2x
Plugin URI: http://www.meow.fr/wp-retina-2x
Description: Your website will look beautiful and smoothly on Retina displays.
Version: 0.1
Author: Jordy Meow
Author URI: http://www.meow.fr

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html

Originally developed for two of my websites: 
- Totoro Times (http://www.totorotimes.com) 
- Haikyo (http://www.haikyo.org)
*/

/**
 *
 * @author      Jordy Meow  <http://www.meow.fr>
 * @package     Wordpress
 * @subpackage	Administration
 *
 */

//error_reporting(E_ALL);

add_filter( 'manage_media_columns', 'wr2x_manage_media_columns' );
add_action( 'manage_media_custom_column', 'wr2x_manage_media_custom_column', 10, 2 );
add_filter( 'views_upload', 'wr2x_views_upload' );
add_action( 'wp_enqueue_scripts', 'wr2x_wp_enqueue_scripts' );
add_action( 'admin_head', 'wr2x_admin_head' );
add_action( 'wp_ajax_wr2x_generate', 'wr2x_wp_ajax_wr2x_generate' );
add_filter( 'wp_generate_attachment_metadata', 'wr2x_wp_generate_attachment_metadata' );
add_action( 'delete_attachment', 'wr2x_delete_attachment' );
add_action( 'admin_init', 'wr2x_admin_init' );
add_action( 'admin_menu', 'wr2x_admin_menu' );
add_filter( 'update_option', 'wr2x_update_option' );
add_filter( 'generate_rewrite_rules', 'wr2x_generate_rewrite_rules' );

register_deactivation_hook( __FILE__, 'wr2x_deactivate' );
register_activation_hook( __FILE__, 'wr2x_activate' );

/**
 *
 * SETTINGS PAGE
 *
 */

function wr2x_settings_page() {
    $settings_api = WeDevs_Settings_API::getInstance();
	$method = wr2x_getoption( "method", "wr2x_advanced", 'Retina-Images' );
	echo "<h1>WP Retina 2x</h1>";
	
	if ( $method == 'retina.js' ) {
		echo "<p><span style='color: orange;'>Current method: <u>Client-side</u>.</span></p>";
	}
	if ( $method == 'Retina-Images' ) {
		echo "<p><span style='color: green;'>Current method: Server-side.</span></p>";
	}
	
	if ( !function_exists( 'enable_media_replace' ) ) {
		echo "<p style='color: green;'>This plugin supports and uses the <a href='http://wordpress.org/extend/plugins/enable-media-replace/'>Enable Media Replace</a> plugin if available. A 'Replace' button will appear in case your images are too small. It is strongly recommended to install it.</p>";
	}
	
    echo '<div class="wrap">';
    settings_errors();
    $settings_api->show_navigation();
    $settings_api->show_forms();
    echo '</div>';
	echo "<center><p>This plugin is actively developped and maintained by <a href='https://plus.google.com/106075761239802324012'>Jordy Meow</a>.<br />Please visit me at <a href='http://www.totorotimes.com'>Totoro Times</a>, a website about Japan, photography and abandoned places.<br />And thanks for linking us on <a href='https://www.facebook.com/totorotimes'>Facebook</a> and <a href='https://plus.google.com/106832157268594698217'>Google+</a> :)</p></center>";
}

function wr2x_getoption( $option, $section, $default = '' ) {
	$options = get_option( $section );
	if ( isset( $options[$option] ) )
		return $options[$option];
	return $default;
}
 
function wr2x_admin_menu() {
	add_options_page( 'WP Retina 2x', 'WP Retina 2x', 'manage_options', 'wr2x_settings', 'wr2x_settings_page' );
}

function wr2x_admin_init() {

	require( 'class.settings-api.php' );

	if (delete_transient('wr2x_flush_rules')) {
		global $wp_rewrite;
		wr2x_generate_rewrite_rules( $wp_rewrite, true );
	}
	
	$sections = array(
        array(
            'id' => 'wr2x_basics',
            'title' => __( 'Basics', 'wp-retina-2x' )
        ),
		array(
            'id' => 'wr2x_advanced',
            'title' => __( 'Advanced', 'wp-retina-2x' )
        )
    );
	
	global $_wp_additional_image_sizes;
	$wpsizes = $_wp_additional_image_sizes;
	$sizes = array();
	foreach ( get_intermediate_image_sizes() as $name => $attr )
		$sizes["$attr"] = $attr;	
	foreach ( $wpsizes as $name => $attr )
		$sizes["$name"] = $name;
	
	$fields = array(
        'wr2x_basics' => array(
			array(
                'name' => 'ignore_sizes',
                'label' => __( 'Disabled Sizes', 'wp-retina-2x' ),
                'desc' => __( 'The checked sizes will not be generated for Retina displays.', 'wp-retina-2x' ),
                'type' => 'multicheck',
                'options' => $sizes
            ),
			array(
                'name' => 'auto_generate',
                'label' => __( 'Auto Generate', 'wp-retina-2x' ),
                'desc' => __( 'Generate Retina images automatically when images are added to the Media Library.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
        ),
		'wr2x_advanced' => array(
			array(
                'name' => 'method',
                'label' => __( 'Method', 'wp-retina-2x' ),
                'desc' => __( 'The <b>server-side method</b> is very fast and efficient. However, depending on the cache system you are using (including services like Cloudflare) you might encounter issues. Please contact me if that is the case.
                The <b>client-side method</b> is fail-safe and only uses a JavaScript file. When a Retina Display is detected, requests for every images on the page will be sent to the server and a high resolution one will be retrieved if available. This method is not efficient and quite slow.', 'wp-retina-2x' ),
                'type' => 'radio',
                'default' => 'Retina-Images',
                'options' => array(
					'Retina-Images' => 'Server-side: Retina-Images (https://github.com/Retina-Images/Retina-Images)',
					'retina.js' => 'Client-side: Retina.js (http://retinajs.com/)'
                )
            ),
			array(
                'name' => 'debug',
                'label' => __( 'Debug Mode', 'wp-retina-2x' ),
                'desc' => __( 'If checked, the client will be always served Retina images. Convenient for testing.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            )
		)
    );
	$settings_api = WeDevs_Settings_API::getInstance();
    $settings_api->set_sections( $sections );
    $settings_api->set_fields( $fields );
    $settings_api->admin_init();
}

function wr2x_update_option( $option ) {
	if ($option == 'wr2x_advanced') {
		set_transient( 'wr2x_flush_rules', true );
	}
}

function wr2x_generate_rewrite_rules( $wp_rewrite, $flush = false ) {
	$method = wr2x_getoption( "method", "wr2x_advanced", "Retina-Images" );
	if ($method == "Retina-Images") {
		add_rewrite_rule( '.*\.(jpe?g|gif|png|bmp)', 'wp-content/plugins/wp-retina-2x/wr2x_image.php', 'top' );		
	}
	if ( $flush == true ) {
		$wp_rewrite->flush_rules();
	}
}

/**
 *
 * AJAX PART FOR THE 'GENERATE' BUTTON
 *
 */

function wr2x_admin_head() {
	
	?>
	<script type="text/javascript" >
		function wr2x_generate ($attachmentId) {
			var data = { action: 'wr2x_generate', attachmentId: $attachmentId };
			jQuery('#wr2x_generate_button_' + $attachmentId).text("<?php echo __( "Please wait...", 'wp-retina-2x' ); ?>");
			jQuery.post(ajaxurl, data, function (response) {
				jQuery('#wr2x_generate_button_' + $attachmentId).remove();
				if (response == 1) {
					// 1: SUCCESS
					jQuery('#wr2x_attachment_' + $attachmentId).html("<?php echo "<span style='text-align: center; color: green;'>" . __("OK", 'wp-retina-2x') . "</span>"; ?>");
				}
				else {
					// 0: ERROR
					jQuery('#wr2x_attachment_' + $attachmentId).html("<?php echo "<span style='text-align: center; color: red; font-weight: bold;'>" . __("ERROR", 'wp-retina-2x') . "</span>"; ?>");
				}
			});
		}
	</script>
	<?php
}

function wr2x_wp_ajax_wr2x_generate() {
	$attachmentId = intval( $_POST['attachmentId'] );
	$meta = wp_get_attachment_metadata( $attachmentId );
	wr2x_generate_images( $meta );
    echo 1;
	die();
}

/**
 *
 * SYSTEM
 *
 */

function wr2x_delete_attachment( $attach_id ) {
	$meta = wp_get_attachment_metadata( $attach_id );
	wr2x_delete_images( $meta );
}
 
function wr2x_wp_generate_attachment_metadata( $meta ) {
	if (wr2x_getoption( "auto_generate", "wr2x_basics", false ) == true)
		wr2x_generate_images( $meta );
    return $meta;
}

function wr2x_generate_images( $meta ) {
	require('vt_resize.php');
	$sizes = $meta['sizes'];
	$originalfile = $meta['file'];
	$uploads = wp_upload_dir();
	$pathinfo = pathinfo( $originalfile );
	$basepath = trailingslashit( $uploads['basedir'] ) . $pathinfo['dirname'];
	$ignore = wr2x_getoption( "ignore_sizes", "wr2x_basics", array() );
	foreach ( $sizes as $name => $attr ) {
		if ( in_array( $name, $ignore ) ) {
			continue;
		}
		$pathinfo = pathinfo( $attr['file'] );
		$retina_file = $pathinfo['filename'] . '@2x.' . $pathinfo['extension'];
		if ( file_exists( trailingslashit($basepath) . $retina_file ) ) {
			continue;
		}
		$crop = isset($attr['crop']) ? $attr['crop'] : false;
		$image = vt_resize( null, trailingslashit($uploads['baseurl']) . $meta['file'], $attr['width'] * 2, $attr['height'] * 2, $crop, $retina_file );
	}

    return $meta;
}

function wr2x_delete_images( $meta )
{
	$sizes = $meta['sizes'];
	$originalfile = $meta['file'];
	$pathinfo = pathinfo( $originalfile );
	$uploads = wp_upload_dir();
	$basepath = trailingslashit( $uploads['basedir'] ) . $pathinfo['dirname'];
	foreach ( $sizes as $name => $attr ) {
		$pathinfo = pathinfo( $attr['file'] );
		$retina_file = $pathinfo['filename'] . '@2x.' . $pathinfo['extension'];
		if ( file_exists( trailingslashit( $basepath ) . $retina_file ) ) {
			unlink( trailingslashit( $basepath ) . $retina_file );
		}
	}
    return $metadata;
}

/**
 *
 * MEDIA LIBRARY
 *
 */

function wr2x_views_upload( $views ) {
	$views['retina'] = "Issue with Retina";
    return $views;
}
 
function wr2x_manage_media_columns( $cols ) {
        $cols["Retina"] = "Retina";
        return $cols;
}

function wr2x_manage_media_custom_column( $column_name, $id ) {
    $meta = wp_get_attachment_metadata($id);
	$original_width = $meta['width'];
	$original_height = $meta['height'];
	
	// TODO: Would be better to replace this with a WP API function.
	global $_wp_additional_image_sizes;
	$sizes = $_wp_additional_image_sizes;
	$sizes = $meta['sizes'];
	
	$required_files = true;
	$required_pixels = 0;
	$required_width = 0;
	$required_height = 0;
	$originalfile = get_attached_file( $id );
	$pathinfo = pathinfo($originalfile);
	$basepath = $pathinfo['dirname'];
	$ignore = wr2x_getoption( "ignore_sizes", "wr2x_basics", array() );
	
	// Check if the original size can support the retina size for each size
	// TODO: Find a way to check the Medium and Large sizes (even the images aren't there yet)
	if ( $sizes ) {
		foreach ($sizes as $name => $attr) {
			if ( in_array( $name, $ignore ) ) {
				continue;
			}
			$pathinfo = pathinfo($attr['file']);
			$retina_file = $pathinfo['filename'] . '@2x.' . $pathinfo['extension'];
			if ( file_exists( trailingslashit( $basepath ) . $retina_file ) ) {
				continue;
			}
			else {
				$required_files = false;
			}
			if ($attr['width'] * $attr['height'] * 2 > $required_pixels) {
				$required_width = $attr['width'] * 2;
				$required_height = $attr['height'] * 2;
				$required_pixels = $required_width * $required_height;
			}
		}
	}

	// Shows the result
	echo "<p id='wr2x_attachment_$id' style='text-align: left; '>";
	if ( !$sizes ) {
		echo "<span style='color: green;'>NO FILES.</span>";
	}
	else if ( $required_files ) {
		echo "<span style='color: green;'>OK</span>";
	}
	else if ($required_width > $original_width || $required_height > $original_height) {
		
		echo "<span style='color: red; margin-bottom: 5px; display: block;'>ORIGINAL FILE IS TOO SMALL.</span>";
		printf( "<span style='font-size: 9px; color: red;'>CURRENT: %d × %d</span><br />", $original_width, $original_height );
		printf( "<span style='font-size: 9px; color: black;'>REQUIRED: %d × %d</span><br />", $required_width, $required_height );
		if (function_exists( 'enable_media_replace' )) {
			$_GET["attachment_id"] = $id;
			$form = enable_media_replace( "" );
			echo $form["enable-media-replace"]['html'];
		}
	}
	else {
		
		echo "<span style='color: orange; display: block; margin-bottom: -10px;'>RETINA FILES ARE NOT CREATED YET.</span><br />";
		echo "<a onclick='wr2x_generate($id)' id='wr2x_generate_button_$id' class='button-secondary'>" . __("Generate", 'wp-retina-2x') . "</a>";
		
	}
	echo "</p>";
}

/**
 *
 * LOAD RETINAJS FOR THE WEBSITE
 *
 */

function wr2x_wp_enqueue_scripts () {
	$debug = wr2x_getoption( "debug", "wr2x_advanced", false );
	$method = wr2x_getoption( "method", "wr2x_advanced", 'Retina-Images' );
	if ($method == "Retina-Images")
		return;
	if ($debug)
		wp_enqueue_script( 'debug', plugins_url( '/js/debug.js', __FILE__ ), array(), '1', false );
	if ($method == "retina.js")
		wp_enqueue_script( 'retinajs', plugins_url( '/js/retina.js', __FILE__ ), array(), '2012.04.02', true );
}

/**
 *
 * ACTIVATE / DESACTIVATE
 *
 */

function wr2x_activate() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function wr2x_deactivate() {
	remove_filter( 'generate_rewrite_rules', 'wr2x_generate_rewrite_rules' );
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

?>