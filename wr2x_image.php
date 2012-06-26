<?php
	define('WP_USE_THEMES', false);
	require('../../..//wp-load.php');
	$debug = wr2x_getoption( "debug", "wr2x_advanced", false );
	if ($debug) {
		$_COOKIE['devicePixelRatio'] = 2;
	}
	require('retinaimages.php');
?>