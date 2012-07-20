<?php

add_filter( 'views_upload', 'wr2x_views_upload' );
add_filter( 'manage_media_columns', 'wr2x_manage_media_columns' );
add_action( 'manage_media_custom_column', 'wr2x_manage_media_custom_column', 10, 2 );

/**
 *
 * MEDIA LIBRARY
 *
 */

function wr2x_views_upload( $views ) {
	//$views['retina'] = "Issue with Retina";
    return $views;
}
 
function wr2x_manage_media_columns( $cols ) {
	$cols["Retina"] = "Retina";
	return $cols;
}

function wr2x_manage_media_custom_column( $column_name, $id ) {
	if ($column_name != 'Retina')
		return;
    $meta = wp_get_attachment_metadata($id);
	if ( $meta == null ) {
		echo "<span style='color: orange;'>NOT RECOGNIZED.</span>";
		return;
	}
	$original_width = $meta['width'];
	$original_height = $meta['height'];
	$sizes = wr2x_get_image_sizes();
	
	$required_files = true;
	$required_pixels = 0;
	$info = wr2x_retina_info( $id );
	foreach ( $info as $name => $attr ) {
		if ( $attr == "EXISTS" )
			continue;
		if ( is_array( $attr ) && $attr['pixels'] > $required_pixels ) {
			$required_width = $attr['width'];
			$required_height = $attr['height'];
			$required_pixels = $attr['pixels'];
			$required_files = false;
		}
		else if ( $attr == 'PENDING' )
			$required_files = false;
	}
	
	// Let's clean the issues status
	wr2x_update_issue_status( $id, null, $info );
	
	// Displays the result
	echo "<p id='wr2x_attachment_$id' style='margin-bottom: 2px;'>";
	if ( $required_files ) {
		echo "<img style='margin-top: -2px; margin-bottom: 2px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "tick-circle.png' />";
	}
	else if ( $required_pixels > 0 )  {
		echo "<img title='Please upload a bigger original image.' style='margin-top: -2px; margin-bottom: 2px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "exclamation.png' />" .
			"<span style='font-size: 9px; margin-left: 5px; position: relative; top: -6px;'>Original too small (< " . $required_width . "×" . $required_height . ")</span>";
		if ( function_exists( 'enable_media_replace' ) ) {
			$_GET["attachment_id"] = $id;
			$form = enable_media_replace( "" );
			echo $form["enable-media-replace"]['html'];
		}
	}
	else {
		
		echo "<img title='Click on \"Generate\".' style='margin-top: -2px; margin-bottom: 2px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "clock.png' />" .
			"<span style='font-size: 9px; margin-left: 5px; position: relative; top: -6px;'>" . __("Not created yet.", 'wp-retina-2x') . "</span><br />";
		echo "<a onclick='wr2x_generate($id)' id='wr2x_generate_button_$id' class='button-secondary'>" . __("Generate", 'wp-retina-2x') . "</a>";
		
	}
	echo "</p>";
}

?>