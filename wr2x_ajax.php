<?php

add_action( 'wp_ajax_wr2x_generate', 'wr2x_wp_ajax_wr2x_generate' );
add_action( 'wp_ajax_wr2x_generate_all', 'wr2x_wp_ajax_wr2x_generate_all' );

/**
 *
 * AJAX CLIENT-SIDE
 *
 */

function wr2x_admin_head() {
	?>
	<script type="text/javascript" >
	
		var current;
		var ids = [];
	
		function wr2x_process_next () {
			var data = { action: 'wr2x_generate', attachmentId: ids[current - 1] };
			jQuery('#wr2x_progression').text(current + "/" + ids.length);
			jQuery.post(ajaxurl, data, function (response) {
				if (++current <= ids.length)
					wr2x_process_next();
				else {
					jQuery('#wr2x_progression').text("<?php echo __( "Done.", 'wp-retina-2x' ); ?>");
				}
			});
		}
	
		function wr2x_generate_all () {
			current = 1;
			ids = [];
			var data = { action: 'wr2x_generate_all' };
			jQuery('#wr2x_progression').text("<?php echo __( "Please wait...", 'wp-retina-2x' ); ?>");
			jQuery.post(ajaxurl, data, function (response) {
				reply = jQuery.parseJSON(response);
				if ((typeof reply.error) == 'string') {
					jQuery('#wr2x_progression').html('Error: ' + reply.error);
				}
				else {
					ids = reply.ids;
					jQuery('#wr2x_progression').html(current + "/" + ids.length);
					wr2x_process_next();
				}
			});
		}
	
		function wr2x_generate (attachmentId, retinaDashboard) {
			retinaDashboard = typeof retinaDashboard !== 'undefined' ? retinaDashboard : 0;
			var data = { action: 'wr2x_generate', attachmentId: attachmentId, retinaDashboard: retinaDashboard };
			jQuery('#wr2x_generate_button_' + attachmentId).text("<?php echo __( "Please wait...", 'wp-retina-2x' ); ?>");
			jQuery.post(ajaxurl, data, function (response) {
				if (retinaDashboard) {
					jQuery('#wr2x_generate_button_' + attachmentId).html("GENERATE");
					var reply = jQuery.parseJSON(response);
					jQuery.each(reply, function (index, sizes) {
						var index = index;
						jQuery.each(sizes, function (size, rsize) {
							if (rsize == 'EXISTS')
								jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'tick-circle.png'; ?>' />");
							else if (rsize == 'MISSING')
								jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'cross-small.png'; ?>' />");
							else if (rsize == 'PENDING')
								jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'clock.png'; ?>' />");
							else if (rsize == 'IGNORED')
								jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'prohibition-small.png'; ?>' />");
							else if (jQuery.isPlainObject(rsize))
								jQuery('#wr2x_' + size + '_' + index).html("<img title='Please upload a bigger original image.' style='margin-top: 3px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'exclamation.png'; ?>' /><span style='font-size: 9px; margin-left: 5px; position: relative; top: -4px;'><br />< " + rsize.width + "×" + rsize.height + "</span>");
							else {
								jQuery('#wr2x_' + size + '_' + index).html(rsize);
							}
						});
					});
				}
				else {
					jQuery('#wr2x_generate_button_' + attachmentId).remove();
					if (response == 1) {
						// 1: SUCCESS
						jQuery('#wr2x_attachment_' + attachmentId).html("<img style='margin-top: -2px; margin-bottom: 2px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'tick-circle.png' ?>' />");
					}
					else {
						// 0: ERROR
						jQuery('#wr2x_attachment_' + attachmentId).html("<?php echo "<span style='text-align: center; color: red; font-weight: bold;'>" . __("ERROR", 'wp-retina-2x') . "</span>"; ?>");
					}
				}
			});
		}
	</script>
	<?php
}

/**
 *
 * AJAX SERVER-SIDE
 *
 */

function wr2x_wp_ajax_wr2x_generate_all() {
	$reply = array();
	try {
		$ids = array();
		$total = 0;
		global $wpdb;
		$postids = $wpdb->get_col( $wpdb->prepare ( "
			SELECT p.ID
			FROM $wpdb->posts p
			WHERE post_status = 'inherit'
			AND post_type = 'attachment'
			AND ( post_mime_type = 'image/jpeg' OR
				post_mime_type = 'image/png' OR
				post_mime_type = 'image/gif' )
		" ) );
		foreach ($postids as $id) {
			array_push( $ids, $id );
			$total++;
		}
		$reply['ids'] = $ids;
		$reply['total'] = $total;
		echo json_encode( $reply );
		die;
	}
	catch (Exception $e) {
		$reply['error'] = $e->getMessage();
		echo json_encode( $reply );
		die;
	}
}
 
function wr2x_wp_ajax_wr2x_generate() {
	$attachmentId = intval( $_POST['attachmentId'] );
	$retinaDashboard = $_POST['retinaDashboard'];

	$meta = wp_get_attachment_metadata( $attachmentId );
	wr2x_generate_images( $meta );
	
	// RESULTS FOR RETINA DASHBOARD
	if ( $retinaDashboard ) {
		$info = wr2x_retina_info( $attachmentId );
		$results[$attachmentId] = $info;
		echo json_encode( $results );
	}
	// RESULTS FOR MEDIA LIBRARY
	else {
		echo 1;
	}
	die();
}

?>