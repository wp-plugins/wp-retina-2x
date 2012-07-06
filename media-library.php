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
	$required_width = 0;
	$required_height = 0;
	$originalfile = get_attached_file( $id );
	$pathinfo = pathinfo($originalfile);
	$basepath = $pathinfo['dirname'];
	$ignore = wr2x_getoption( "ignore_sizes", "wr2x_basics", array() );
	
	// Check if the original size can support the retina size for each size
	if ( $sizes ) {
		foreach ($sizes as $name => $attr) {
			if ( in_array( $name, $ignore ) ) {
				continue;
			}
			
			// Check if the file related to this size is present
			$pathinfo = null;
			$retina_file = null;
			if (isset($meta['sizes'][$name]) && isset($meta['sizes'][$name]['file'])) {
				$pathinfo = pathinfo($meta['sizes'][$name]['file']);
				$retina_file = $pathinfo['filename'] . '@2x.' . $pathinfo['extension'];
			}

			if ( $retina_file && file_exists( trailingslashit( $basepath ) . $retina_file ) )
				continue;
			else
				$required_files = false;
			
			if ($attr['width'] * $attr['height'] * 2 > $required_pixels) {
				$required_width = $attr['width'] * 2;
				$required_height = $attr['height'] * 2;
				$required_pixels = $required_width * $required_height;
			}
		}
	}

	// Shows the result
	echo "<p id='wr2x_attachment_$id' style='margin-bottom: 2px;'>";
	if ( !$sizes ) {
		echo "<span style='color: green;'>NO FILES.</span>";
	}
	else if ( $required_files ) {
		echo "<img style='margin-top: -2px; margin-bottom: 2px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "tick-circle.png' />";
	}
	else if ($required_width > $original_width || $required_height > $original_height) {
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