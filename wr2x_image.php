<?php

	//$debug = wr2x_getoption( "debug", "wr2x_advanced", false );
	
	define('DEBUG',              false);   // Write debugging information to a log file
	define('SEND_ETAG',          true);     // You will want to disable this if you load balance multiple servers 
	define('SEND_EXPIRES',       true);     // 
	define('SEND_CACHE_CONTROL', true);     // 
	define('CACHE_TIME',         7*24*60*60); // default: 1 week
	
	require('wr2x_retinaimages.php');
?>
