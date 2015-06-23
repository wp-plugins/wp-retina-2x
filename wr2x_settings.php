<?php

add_action( 'admin_init', 'wr2x_admin_init' );

/**
 *
 * SETTINGS PAGE
 *
 */
 
function wr2x_settings_page() {
    global $wr2x_settings_api;
	echo '<div class="wrap">';
    jordy_meow_donation(true);
	$method = wr2x_getoption( "method", "wr2x_advanced", 'Picturefill' );

	echo "<div id='icon-options-general' class='icon32'><br></div><h2>Retina";
    by_jordy_meow();
    echo "</h2>";
	if ( $method == 'retina.js' ) {
		echo "<p><span>" . __( "Current method:", 'wp-retina-2x' ) . " <u>" . __( "Client side", 'wp-retina-2x' ) . "</u>.</span>";
	}
    if ( $method == 'Picturefill' ) {
        echo "<p><span>" . __( "Current method:", 'wp-retina-2x' ) . " <u>" . __( "PictureFill", 'wp-retina-2x' ) . "</u>.</span>";
    }
	if ( $method == 'Retina-Images' ) {
        echo "<p><span>" . __( "Current method:", 'wp-retina-2x' ) . " <u>" . __( "Server side", 'wp-retina-2x' ) . "</u>.</span>";
        if ( defined( 'MULTISITE' ) && MULTISITE == true  ) {
            if ( get_site_option( 'ms_files_rewriting' ) ) {
                // MODIFICATION: Craig Foster
                // 'ms_files_rewriting' support
                echo " <span style='color: red;'>" . __( "By the way, you are using a <b>WordPress Multi-Site installation</b>! You must edit your .htaccess manually and add '<b>RewriteRule ^files/(.+) wp-content/plugins/wp-retina-2x/wr2x_image.php?ms=true&file=$1 [L]</b>' as the first RewriteRule if you want the server-side to work.", 'wp-retina-2x' ) . "</span>";
            }
            else
                echo " <span style='color: red;'>" . __( "By the way, you are using a <b>WordPress Multi-Site installation</b>! You must edit your .htaccess manually and add '<b>RewriteRule ^(wp-content/.+\.(png|gif|jpg|jpeg|bmp|PNG|GIF|JPG|JPEG|BMP)) wp-content/plugins/wp-retina-2x/wr2x_image.php?ms=true&file=$1 [L]</b>' as the first RewriteRule if you want the server-side to work.", 'wp-retina-2x' ) . "</span>";   
        }
		echo "</p>";
		if ( !get_option('permalink_structure') )
			echo "<p><span style='color: red;'>" . __( "The permalinks are not enabled. They need to be enabled in order to use the server-side method.", 'wp-retina-2x' ) . "</span>";
	}
	
    //settings_errors();
    $wr2x_settings_api->show_navigation();
    $wr2x_settings_api->show_forms();
    echo '</div>';
	jordy_meow_footer();
}

function wr2x_setoption( $option, $section, $value ) {
    $options = get_option( $section );
    if ( empty( $options ) ) {
        $options = array();
    }
    $options[$option] = $value;
    update_option( $section, $options );
}

function wr2x_getoption( $option, $section, $default = '' ) {
	$options = get_option( $section );
	if ( isset( $options[$option] ) ) {
        if ( $options[$option] == "off" ) {
            return false;
        }
        if ( $options[$option] == "on" ) {
            return true;
        }
		return $options[$option];
    }
	return $default;
}

function wr2x_admin_init() {
    if ( isset( $_POST ) && isset( $_POST['wr2x_pro'] ) )
        wr2x_validate_pro( $_POST['wr2x_pro']['subscr_id'] );
    $pro_status = get_option( 'wr2x_pro_status', "Not Pro." );
	require( 'wr2x_class.settings-api.php' );
	if ( delete_transient( 'wr2x_flush_rules' ) ) {
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
        ),
        array(
            'id' => 'wr2x_pro',
            'title' => __( 'Pro', 'wp-retina-2x' )
        )
    );
	
    // Default Auto-Generate
    $auto_generate = wr2x_getoption( 'auto_generate', 'wr2x_basics', null );
    if ( $auto_generate === null )
        wr2x_setoption( 'auto_generate', 'wr2x_basics', 'on' );

	$wpsizes = wr2x_get_image_sizes();
	$sizes = array();
	foreach ( $wpsizes as $name => $attr )
		$sizes["$name"] = sprintf( "%s (%dx%d)", $name, $attr['width'], $attr['height'] );
	
	$fields = array(
        'wr2x_basics' => array(
			array(
                'name' => 'ignore_sizes',
                'label' => __( 'Disabled Sizes', 'wp-retina-2x' ),
                'desc' => __( '<br />The selected sizes will not have their retina equivalent generated.', 'wp-retina-2x' ),
                'type' => 'multicheck',
                'options' => $sizes
            ),
			array(
                'name' => 'auto_generate',
                'label' => __( 'Auto Generate', 'wp-retina-2x' ),
                'desc' => __( 'Generate retina images automatically when images are uploaded or re-generated.<br />The \'Disabled Sizes\' will be skipped.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => true
            ),
            array(
                'name' => 'full_size',
                'label' => __( 'Full Size Retina (Pro)', 'wp-retina-2x' ),
                'desc' => __( 'Retina for the full-size image will be considered required.<br />Checks and upload feature are available.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            )
        ),
		'wr2x_advanced' => array(
			array(
                'name' => 'method',
                'label' => __( 'Method', 'wp-retina-2x' ),
                'desc' => __( '<br />Check the <a href="http://apps.meow.fr/wp-retina-2x/">plugin official page</a> if you want to know more about the methods to deliver the retina images.', 'wp-retina-2x' ),
                'type' => 'radio',
                'default' => 'Picturefill',
                'options' => array(
                    'Picturefill' => __( "Picturefill (Recommended)", 'wp-retina-2x' ),
                    'retina.js' => __( "Retina.js", 'wp-retina-2x' ),
                    'HTML Rewrite' => __( "IMG Rewrite", 'wp-retina-2x' ),
					'Retina-Images' => __( "Retina-Images", 'wp-retina-2x' ),
					'none' => __( "None", 'wp-retina-2x' )
                )
            ),
            array(
                'name' => 'image_quality',
                'label' => __( 'Quality', 'wp-retina-2x' ),
                'desc' => __( '<br />Image Compression quality (between 0 and 100).<br />That doesn\'t always actually work depending on your hosting service.', 'wp-retina-2x' ),
                'type' => 'text',
                'default' => 90
            ),
            array(
                'name' => 'debug',
                'label' => __( 'Debug Mode', 'wp-retina-2x' ),
                'desc' => __( 'If checked, the client will be always served Retina images. <br />Please use it for testing purposes. It creates a <a href="' . plugins_url("wp-retina-2x") . '/wp-retina-2x.log">log file</a> in the plugin folder.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
            array(
                'name' => 'cdn_domain',
                'label' => __( 'Custom CDN Domain (Pro)', 'wp-retina-2x' ),
                'desc' => __( '<br />If not empty, your site domain will be replaced with this CDN domain (PictureFill and HTML Rewrite only).', 'wp-retina-2x' ),
                'type' => 'text',
                'default' => ""
            ),
            array(
                'name' => 'picture_fill',
                'label' => '',
                'desc' => __( '<h2>For PictureFill</h2>', 'wp-retina-2x' ),
                'type' => 'html'
            ),
            array(
                'name' => 'picturefill_keep_src',
                'label' => __( 'Keep IMG SRC (Pro)', 'wp-retina-2x' ),
                'desc' => __( 'With PictureFill, <b>src</b> tags are replaced by <b>src-set</b> tags and consequently search engines might not be able to find and reference those images. Keeping it will load images twice for retina devices on old browsers.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
            array(
                'name' => 'picturefill_lazysizes',
                'label' => __( 'Use Lazysizes (Pro)', 'wp-retina-2x' ),
                'desc' => __( 'HTML will be rewritten to support the lazysizes and the script will be also loaded. The images will be loaded in a lazy way (when the visitor is getting close to them).', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
            array(
                'name' => 'picturefill_noscript',
                'label' => __( 'No Picturefill Script', 'wp-retina-2x' ),
                'desc' => __( 'The script for Picturefill will not be loaded. Only the browsers with src-set support (e.g. Chrome) will display images. You can also load the Picturefill script manually.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
            array(
                'name' => 'admin_screens',
                'label' => '',
                'desc' => __( '<h2>Admin Screens</h2>', 'wp-retina-2x' ),
                'type' => 'html'
            ),
			array(
                'name' => 'hide_retina_column',
                'label' => __( 'Hide \'Retina\' column', 'wp-retina-2x' ),
                'desc' => __( 'Will hide the \'Retina Column\' from the Media Library.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
			array(
                'name' => 'hide_retina_dashboard',
                'label' => __( 'Hide Retina Dashboard', 'wp-retina-2x' ),
                'desc' => __( 'Doesn\'t show the Retina Dashboard menu and tools.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
            array(
                'name' => 'retina_admin',
                'label' => __( 'Admin in Retina', 'wp-retina-2x' ),
                'desc' => __( 'If checked, the WordPress Admin will also be Retina. Some plugins (like NextGen) do not like Retina enabled in the admin.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
            array(
                'name' => 'mobile',
                'label' => '',
                'desc' => __( '<h2>Mobiles</h2>', 'wp-retina-2x' ),
                'type' => 'html'
            ),
            array(
                'name' => 'ignore_mobile',
                'label' => __( 'Ignore Mobile', 'wp-retina-2x' ),
                'desc' => __( 'Doesn\'t deliver Retina images to mobiles.<br />PictureFill doesn\'t support it and cache will also prevent it from working.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            ),
		),
        'wr2x_pro' => array(
            array(
                'name' => 'pro',
                'label' => '',
                'desc' => __( sprintf( 'Status: %s<br /><br />With the Pro version, full support for the <b>Full Size Retina</b> will be added. You will also get a new and nice pop-up window with more <b>Details</b> in the Retina dashboard.', $pro_status ), 'wp-retina-2x' ),
                'type' => 'html'
            ),
            array(
                'name' => 'subscr_id',
                'label' => __( 'Serial', 'wp-retina-2x' ),
                'desc' => __( '<br />Enter your serial or subscription ID here. If you don\'t have one yet, get one <a target="_blank" href="http://apps.meow.fr/wp-retina-2x/">right here</a>.', 'wp-retina-2x' ),
                'type' => 'text',
                'default' => ""
            ),
        )
    );
    global $wr2x_settings_api;
	$wr2x_settings_api = new WeDevs_Settings_API;
    $wr2x_settings_api->set_sections( $sections );
    $wr2x_settings_api->set_fields( $fields );
    $wr2x_settings_api->admin_init();
}

function wr2x_update_option( $option ) {
	if ($option == 'wr2x_advanced') {
		set_transient( 'wr2x_flush_rules', true );
	}
}

function wr2x_generate_rewrite_rules( $wp_rewrite, $flush = false ) {
	global $wp_rewrite;
	$method = wr2x_getoption( "method", "wr2x_advanced", "retina.js" );
	if ($method == "Retina-Images") {

		// MODIFICATION: docwhat
		// get_home_url() -> trailingslashit(site_url())
		// REFERENCE: http://wordpress.org/support/topic/plugin-wp-retina-2x-htaccess-generated-with-incorrect-rewriterule
		
		// MODIFICATION BY h4ir9 
		// .*\.(jpg|jpeg|gif|png|bmp) -> (.+.(?:jpe?g|gif|png))
		// REFERENCE: http://wordpress.org/support/topic/great-but-needs-a-little-update
		
		$handlerurl = str_replace( trailingslashit(site_url()), '', plugins_url( 'wr2x_image.php', __FILE__ ) );
		add_rewrite_rule( '(.+.(?:jpe?g|gif|png))', $handlerurl, 'top' );		
	}
	if ( $flush == true ) {
		$wp_rewrite->flush_rules();
	}
}

?>
