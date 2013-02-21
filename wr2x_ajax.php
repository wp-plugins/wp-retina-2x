<?php

add_action( 'wp_ajax_wr2x_generate', 'wr2x_wp_ajax_wr2x_generate' );
add_action( 'wp_ajax_wr2x_generate_all', 'wr2x_wp_ajax_wr2x_generate_all' );
add_action( 'wp_ajax_wr2x_replace', 'wr2x_wp_ajax_wr2x_replace' );
add_action( 'admin_head', 'wr2x_admin_head' );

/**
 *
 * AJAX CLIENT-SIDE
 *
 */

function wr2x_admin_head() {
	?>
	<script type="text/javascript" >
	
		/* GENERATE RETINA IMAGES ACTION */

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
	
		// Refresh the dashboard with the results from the Ajax operation (Replace or Generate)
		function wr2x_refresh_dashboard (results) {
			jQuery.each(results, function (index, sizes) {
				var index = index;
				jQuery.each(sizes, function (size, rsize) {
					if (rsize == 'EXISTS')
						jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px; width: 16px; height: 16px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'tick-circle.png'; ?>' />");
					else if (rsize == 'MISSING')
						jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px; width: 16px; height: 16px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'cross-small.png'; ?>' />");
					else if (rsize == 'PENDING')
						jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px; width: 16px; height: 16px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'clock.png'; ?>' />");
					else if (rsize == 'IGNORED')
						jQuery('#wr2x_' + size + '_' + index).html("<img style='margin-top: 3px; width: 16px; height: 16px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'prohibition-small.png'; ?>' />");
					else if (jQuery.isPlainObject(rsize))
						jQuery('#wr2x_' + size + '_' + index).html("<img title='Please upload a bigger original image.' style='margin-top: 3px; width: 16px; height: 16px;' src='<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . 'exclamation.png'; ?>' /><span style='font-size: 9px; margin-left: 5px; position: relative; top: -4px;'><br />< " + rsize.width + "×" + rsize.height + "</span>");
					else {
						jQuery('#wr2x_' + size + '_' + index).html(rsize);
					}
				});
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
					wr2x_refresh_dashboard(reply);
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

		/* REPLACE FUNCTION */

		function wr2x_stop_propagation(evt) {
			evt.stopPropagation();
			evt.preventDefault();
		}

		function wr2x_handleReaderLoad(evt) {
			var attachmentId = evt.target.attachmentId;
			var fileData = evt.target.result;
			fileData = fileData.substr(fileData.indexOf('base64') + 7);
			var data = { 
				action: 'wr2x_replace',
				isAjax: true,
				filename: evt.target.filename,
				data: fileData,
				attachmentId: evt.target.attachmentId
			};

			jQuery.post(ajaxurl, data, function (response) {
				var data = jQuery.parseJSON(response);
				jQuery('[postid=' + attachmentId + ']').removeClass('wr2x-loading-file wr2x-hover-drop');
				var imgSelector = '[postid=' + attachmentId + '] .wr2x-image img';
				jQuery(imgSelector).attr('src', jQuery(imgSelector).attr('src')+'?'+ Math.random());

				if (data.success === false) {
					alert(data.message);
				}
				else {
					wr2x_refresh_dashboard(data.results);
				}
			});
		}

		function wr2x_filedropped (evt) {
			wr2x_stop_propagation(evt);
			var files = evt.dataTransfer.files;
			var count = files.length;
			if (count < 0) {
				return;
			}
			jQuery(evt.target).parents('.wr2x-file-row').addClass('wr2x-loading-file');
			var file = files[0];
			var reader = new FileReader();
			reader.filename = file.name;
			reader.attachmentId = jQuery(evt.target).parents('.wr2x-file-row').attr('postid');
			reader.onload = wr2x_handleReaderLoad;
			reader.readAsDataURL(file);
		}

		jQuery(document).ready(function () {
			jQuery('.wr2x-file-row').on('dragenter', function (evt) {
				wr2x_stop_propagation(evt);
				jQuery(this).addClass('wr2x-hover-drop');
			});

			jQuery('.wr2x-file-row').on('dragover', function (evt) {
				wr2x_stop_propagation(evt);
				jQuery(this).addClass('wr2x-hover-drop');
			});

			jQuery('.wr2x-file-row').on('dragleave', function (evt) {
				wr2x_stop_propagation(evt);
				jQuery(this).removeClass('wr2x-hover-drop');
			});

			jQuery('.wr2x-file-row').on('dragexit', wr2x_stop_propagation);

			jQuery('.wr2x-file-row').each(function (index, elem) {
				this.addEventListener('drop', wr2x_filedropped);
			});
		});

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
		$postids = $wpdb->get_col( "
			SELECT p.ID
			FROM $wpdb->posts p
			WHERE post_status = 'inherit'
			AND post_type = 'attachment'
			AND ( post_mime_type = 'image/jpeg' OR
				post_mime_type = 'image/png' OR
				post_mime_type = 'image/gif' )
		" );
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

	wr2x_delete_attachment( $attachmentId );
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

function wr2x_wp_ajax_wr2x_replace() {

	if ( !current_user_can('upload_files') ) {
		echo json_encode( array(
			'success'  => false,
			'message' => "You do not have permission to upload files."
		));
		die();
	}
	
	// Clean the data by removing the type
	$data = $_POST['data'];
	//$data = preg_replace( '/^data:.*;base64,/', '', $_POST['data'], 1 );
	/*
	$data = $data.substr($data.indexOf('base64') + 7);

	if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
		echo json_encode( array(
			'success' => false,
			'message' => "Backtrack limit was exhausted."
		));
		die();
	}
	*/


	// Create the file as a TMP
	$tmpfname = tempnam( "/tmp", "wr2x_up_" );
	$handle = fopen( $tmpfname, "w" );
	fwrite( $handle, base64_decode( $data ) );
	fclose( $handle );

	// Check if it is an image
	$file_info = getimagesize( $tmpfname );
	if ( empty( $file_info ) ) {
		unlink( $tmpfname );
		echo json_encode( array(
			'success' => false,
			'message' => "The file is not an image or the upload went wrong."
		));
		die();
	}

	$filedata = wp_check_filetype_and_ext( $tmpfname, $_POST['filename'] );
	if ( $filedata["ext"] == "" ) {
		unlink( $current_file );
		echo json_encode( array(
			'success' => false,
			'message' => "You cannot use this file (wrong extension? wrong type?)."
		));
		die();
	}
	
	$attachmentId = (int) $_POST['attachmentId'];
	$meta = wp_get_attachment_metadata( $attachmentId );
	$current_file = get_attached_file( $attachmentId );
	wr2x_delete_attachment( $attachmentId );
	$pathinfo = pathinfo( $current_file );
	$basepath = $pathinfo['dirname'];

	// Let's clean everything first
	if ( wp_attachment_is_image( $attachmentId ) ) {
		$sizes = wr2x_get_image_sizes();
		foreach ($sizes as $name => $attr) {
			if (isset($meta['sizes'][$name]) && isset($meta['sizes'][$name]['file']) && file_exists( trailingslashit( $basepath ) . $meta['sizes'][$name]['file'] )) {
				$normal_file = trailingslashit( $basepath ) . $meta['sizes'][$name]['file'];
				$pathinfo = pathinfo( $normal_file );
				$retina_file = trailingslashit( $pathinfo['dirname'] ) . $pathinfo['filename'] . '@2x.' . $pathinfo['extension'];
				if ( file_exists($normal_file) )
					unlink( $normal_file );
				if ( file_exists($retina_file) )
					unlink( $retina_file );
			}
		}
	}
	if ( file_exists($current_file) )
		unlink( $current_file );

	// Insert the new file and delete the temporary one
	rename( $tmpfname, $current_file );
	chmod( $current_file, 0644 );
	
	// Generate the images
	wp_update_attachment_metadata( $attachmentId, wp_generate_attachment_metadata( $attachmentId, $current_file ) );
	$meta = wp_get_attachment_metadata( $attachmentId );
	wr2x_generate_images( $meta );

	// Get the results
	$info = wr2x_retina_info( $attachmentId );
	$results[$attachmentId] = $info;

	echo json_encode( array(
		'success' => true,
		'results' => $results,
		'message' => "Replaced successfuly."
	));
	die();
}

?>