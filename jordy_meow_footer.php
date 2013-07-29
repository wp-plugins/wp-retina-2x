<?php
	if ( !function_exists( 'jordy_meow_donation' ) ) {
		function jordy_meow_donation($showWPE = false) {
			if ( defined( 'WP_HIDE_DONATION_BUTTONS' ) && WP_HIDE_DONATION_BUTTONS == true )
				return;
			
			if ( $showWPE ) {
				echo "<a href='http://www.wpengine.com' target='_blank'><img style='float: right; margin-top: 5px; margin-left: 15px;' width='90px' height='34px' src='" . plugins_url('/img/wpengine.png', __FILE__) . "' /></a>";
			}
			?>

			<a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JAWE2XWH7ZE5U' target='_blank'>
				<img style='float: right; margin-top: 5px;' width='145px' height='39px' src='<?php echo plugins_url('/img/donation.png', __FILE__); ?>' />
			</a>
			
			<?php
		}
	}

	if ( !function_exists('jordy_meow_footer') ) {
		function jordy_meow_footer() {
			?>
			<div style='font-size: 14px; color: #32595E; border-top: 1px solid #DFDFDF; position: absolute;margin-right: 20px;right: 0px;left: 0px;text-align: center;font-family: Tahoma;z-index: 10;background: white;margin-top: 15px;'>
				<p><b>This plugin is actively developed and maintained by <a href='https://plus.google.com/106075761239802324012'>Jordy Meow</a></b>.<br />Please visit <a href='http://www.totorotimes.com'>Totoro Times</a>, a website about Japan, Photography, Urban Exploration & Adventures.<br />And thanks for following me on <a href='https://twitter.com/TigrouMeow'>Twitter</a> or <a href='https://plus.google.com/106075761239802324012'>Google+</a> :)</p>
			</div>
			<?php
		}
	}
?>