<?php
/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * Modified by Jordy Meow for WP Retina 2x
 */

function wr2x_vt_resize( $file_path, $width, $height, $crop, $newfile ) {

	$orig_size = getimagesize( $file_path );
	$image_src[0] = $file_path;
	$image_src[1] = $orig_size[0];
	$image_src[2] = $orig_size[1];

	$file_info = pathinfo( $file_path );
	$extension = '.'. $file_info['extension'];

	// the image path without the extension
	$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
	$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
	
	// check if image width is smaller than set width
	$img_size = getimagesize( $file_path );
	if ( $img_size[0] <= $width ) $width = $img_size[0];
	
	// Check if GD Library installed
	if ( !function_exists ( 'imagecreatetruecolor' ) ) {
		wr2x_log( 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library' );
		return;
	}
	
	$new_img_path = image_resize( $file_path, $width, $height, $crop, $width.'x'.$height."-wr2x-tmp" );
	
	if ( is_wp_error( $new_img_path ) ) {
		wr2x_log( "  image_resize error with {$file_path}: " . htmlspecialchars_decode( $new_img_path->get_error_message() ) );
		return false;
	}

	if ( rename( $new_img_path, $newfile ) ) {
		$new_img_path = $newfile;
	}

	$new_img_size = getimagesize( $new_img_path );
	$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
	
	// resized output
	$vt_image = array (
		'url' => $new_img,
		'width' => $new_img_size[0],
		'height' => $new_img_size[1]
	);
	return $vt_image;
}
