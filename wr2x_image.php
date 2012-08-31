<?php
	//require( 'wp-retina-2x.php' );
	//$debug = wr2x_getoption( "debug", "wr2x_advanced", false );

	define('DEBUG',              true);   	// Write debugging information to a log file
	define('SEND_ETAG',          true);     // You will want to disable this if you load balance multiple servers 
	define('SEND_EXPIRES',       true);     // 
	define('SEND_CACHE_CONTROL', true);     // 
	define('CACHE_TIME',         7*24*60*60); // default: 1 week
	
	// Retina Images doesn't handle the float value for the cookie devicePixelRatio, so let's ceil it!
	if ( isset( $_COOKIE['devicePixelRatio'] ) ) {
		$_COOKIE['devicePixelRatio'] = ceil(floatval($_COOKIE['devicePixelRatio']));
	}
	
	require('wr2x_retinaimages.php');
?>
