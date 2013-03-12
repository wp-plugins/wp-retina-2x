<?php

add_action( 'admin_init', 'wr2x_admin_init' );

/**
 *
 * SETTINGS PAGE
 *
 */
 
function wr2x_settings_page() {
    $settings_api = wr2x_WeDevs_Settings_API::getInstance();
	echo '<div class="wrap">';
	$method = wr2x_getoption( "method", "wr2x_advanced", 'retina.js' );
	echo "<div id='icon-options-general' class='icon32'><br></div><h2>WP Retina 2x</h2>";
	if ( $method == 'retina.js' ) {
		echo "<p><span style='color: blue;'>" . __( "Current method:", 'wp-retina-2x' ) . " <u>" . __( "Client side", 'wp-retina-2x' ) . "</u>.</span>";
	}
	if ( $method == 'Retina-Images' ) {
		echo "<p><span style='color: blue;'>" . __( "Current method:", 'wp-retina-2x' ) . " <u>" . __( "Server side", 'wp-retina-2x' ) . "</u>.</span>";
		if ( defined( 'MULTISITE' ) && MULTISITE == true  )
			echo " <span style='color: red;'>" . __( "By the way, you are using a <b>WordPress Multi-Site installation</b>! You must edit your .htaccess manually and add '<b>RewriteRule ^files/(.+) wp-content/plugins/wp-retina-2x/wr2x_image.php?ms=true&file=$1 [L]</b>' as the first RewriteRule if you want the server-side to work.", 'wp-retina-2x' ) . "</span>";
		echo "</p>";
		if ( !get_option('permalink_structure') )
			echo "<p><span style='color: red;'>" . __( "The permalinks are not enabled. They need to be enabled in order to use the server-side method.", 'wp-retina-2x' ) . "</span>";
	}
	
    //settings_errors();
    $settings_api->show_navigation();
    $settings_api->show_forms();
    echo '</div>';
	jordy_meow_footer();
}

function wr2x_getoption( $option, $section, $default = '' ) {
	$options = get_option( $section );
	if ( isset( $options[$option] ) )
		return $options[$option];
	return $default;
}
 
function wr2x_admin_init() {

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
        )
    );
	
	$wpsizes = wr2x_get_image_sizes();
	$sizes = array();
	foreach ( $wpsizes as $name => $attr )
		$sizes["$name"] = sprintf( "%s (%dx%d)", $name, $attr['width'], $attr['height'] );
	
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
                'desc' => __( 'Generate Retina images automatically when images are uploaded to the Media Library.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
            )
        ),
		'wr2x_advanced' => array(
			array(
                'name' => 'method',
                'label' => __( 'Method', 'wp-retina-2x' ),
                'desc' => __( '<br />The <b>server-side method</b> is very fast and efficient. However, depending on the hosting and cache system you are using (including services like Cloudflare) you might encounter issues.
                <br /><br />The <b>client-side method</b> is fail-safe and only uses a JavaScript file. When a Retina Display is detected, requests for every images on the page will be sent to the server and a high resolution one will be retrieved if available. Most websites (Apple for instance) use this method.', 'wp-retina-2x' ),
                'type' => 'radio',
                'default' => 'retina.js',
                'options' => array(
                	'HTML Rewrite' => __( "HTML Rewrite", 'wp-retina-2x' ),
                	'retina.js' => __( "Client side", 'wp-retina-2x' ) . ': <a href=\'http://retinajs.com/\'>Retina.js</a>',
					'Retina-Images' => __( "Server side", 'wp-retina-2x' ) . ': <a href=\'https://github.com/Retina-Images/Retina-Images\'>Retina-Images</a>',
					'none' => __( "None", 'wp-retina-2x' )
                )
            ),
			array(
                'name' => 'debug',
                'label' => __( 'Debug Mode', 'wp-retina-2x' ),
                'desc' => __( 'If checked, the client will be always served Retina images. Convenient for testing.', 'wp-retina-2x' ),
                'type' => 'checkbox',
                'default' => false
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
            )
		)
    );
	$settings_api = wr2x_WeDevs_Settings_API::getInstance();
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
