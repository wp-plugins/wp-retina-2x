<?php
/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * Modified by Jordy Meow for WP Retina 2x
 */

function wr2x_vt_resize( $img_url, $width, $height, $crop, $newfile ) {
	$file_path = parse_url( $img_url );
	$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
	// Look for Multisite Path
	if(file_exists($file_path) === false){
		global $blog_id;
		$file_path = parse_url( $img_url );
		if (preg_match("/files/", $file_path['path'])) {
			$path = explode('/',$file_path['path']);
			foreach($path as $k=>$v){
				if($v == 'files'){
					$path[$k-1] = 'wp-content/blogs.dir/'.$blog_id;
				}
			}
			$path = implode('/',$path);
		}
		$file_path = $_SERVER['DOCUMENT_ROOT'].$path;
	}
	$orig_size = getimagesize( $file_path );
	$image_src[0] = $img_url;
	$image_src[1] = $orig_size[0];
	$image_src[2] = $orig_size[1];


	$file_info = pathinfo( $file_path );

	// check if file exists
	$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
	if ( !file_exists($base_file) )
	 return;

	$extension = '.'. $file_info['extension'];

	// the image path without the extension
	$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
	$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
	
	// check if image width is smaller than set width
	$img_size = getimagesize( $file_path );
	if ( $img_size[0] <= $width ) $width = $img_size[0];
	
	// Check if GD Library installed
	if (!function_exists ('imagecreatetruecolor')) {
		echo 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
		return;
	}

	$new_img_path = image_resize( $file_path, $width, $height, $crop, $width.'x'.$height."-wr2x-tmp" );
	if ( is_object( $new_img_path ))
		return false;
	if (rename($new_img_path, dirname($new_img_path) . DIRECTORY_SEPARATOR . $newfile)) {
		$new_img_path = dirname($new_img_path) . DIRECTORY_SEPARATOR . $newfile;
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
