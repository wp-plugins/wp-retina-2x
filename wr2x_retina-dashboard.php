<?php

add_action( 'admin_menu', 'wr2x_admin_menu_dashboard' );

/**
 *
 * RETINA DASHBOARD
 *
 */

function wr2x_admin_menu_dashboard () {
	$flagged = 0;
	$warning_title = "Retina files";
	$menu_label = sprintf( __( 'Retina Dashboard %s' ), "<span class='update-plugins count-$flagged' title='$warning_title'><span class='update-count'>" . number_format_i18n( $flagged ) . "</span></span>" );
	add_media_page( 'WP Retina 2x', $menu_label, 'manage_options', 'wp-retina-2x', 'wpr2x_wp_retina_2x' ); 
}
 
function wpr2x_wp_retina_2x() {

	$view = isset ( $_GET[ 'view' ] ) ? $_GET[ 'view' ] : 'issues';
	$paged = isset ( $_GET[ 'paged' ] ) ? $_GET[ 'paged' ] : 1;
	$issues = $count = 0;
	$sizes = wr2x_get_image_sizes();
	$posts_per_page = get_option('posts_per_page');
	$media_query = new WP_Query( array( 
		'post_type' => 'attachment', 
		'post_status' => 'inherit',
		'posts_per_page' => -1
		) );
	
	$results = array();
	foreach ($media_query->posts as $post) {
		$count++;
		$info = wr2x_retina_info( $post->ID );
		$has_issue = false;
		foreach ($info as $aindex => $aval) {
			if ( is_array( $aval ) || $aval == 'PENDING' ) {
				$has_issue = true;
				$issues++;
				break;
			}
		}
		if ( $view == 'issues' && $has_issue == false )
			continue;
		
		if ( $view == 'issues' ) {
			if ( $issues > ( ( $paged - 1 ) * $posts_per_page ) && $issues <= ( ( $paged ) * $posts_per_page ) )
				array_push( $results, array( 'post' => $post, 'info' => $info ) );
		} else {
			if ( $count > ( ( $paged - 1 ) * $posts_per_page ) && $count <= ( ( $paged ) * $posts_per_page ) )
				array_push( $results, array( 'post' => $post, 'info' => $info ) );
		}
	}
	?>
	<div class='wrap'>
	<div id="icon-upload" class="icon32"><br></div>
	<h2>Retina Dashboard</h2>
	<p></p>
	<a id='wr2x_generate_button_all' onclick='wr2x_generate_all()' class='button-primary'><?php _e("Generate for all files", 'wp-retina-2x'); ?></a> <span id='wr2x_progression'></span>
	<p><?php _e("This screen allows you to check the media for which the retina files are missing. You can then create the files independently for each media ('Generate' button) or for all of them ('Generate for all the files' button).", 'wp-retina-2x'); ?></p>
	
	<div style='float: right; padding-top: 10px;'>
	<?php
	$pagescount = (($view == 'issues' ? $issues : $count ) / $posts_per_page) + 1;
	if ( $pagescount > 2 )
		for ( $i = 1; $i < $pagescount; $i++ ) {
			echo '<a href="?page=wp-retina-2x&view=' . $view . '&paged=' . $i  . '"' . ( ( $paged == $i) ? ' style="font-weight: bold; font-decoration:none;"' : ' style="font-decoration:none;"' ) . ' />' . $i . '</a> ';
		}
	?>
	</div>
	
	<ul class="subsubsub">
		<li class="all"><a <?php if ( $view == 'all' ) echo "class='current'"; ?> href='?page=wp-retina-2x&view=all'>All</a><span class="count">(<?php echo $count; ?>)</span></li> |
		<li class="all"><a <?php if ( $view == 'issues' ) echo "class='current'"; ?> href='?page=wp-retina-2x&view=issues'>Issues</a><span class="count">(<?php echo $issues; ?>)</span></li>
	</ul>
	<table class='wp-list-table widefat fixed media'>
		<thead><tr>
			<?php
			echo "<th class='manage-column'>Title</th>";
			foreach ($sizes as $name => $attr) {
				echo "<th class='manage-column'>" . $name . "</th>";
			}
			echo "<th class='manage-column'>Generate</th>";
			if ( function_exists( 'enable_media_replace' ) ) {
				echo "<th class='manage-column'>Upload</th>";
			}
			?>
		</tr></thead>
		<tbody>
			<?php
			foreach ($results as $index => $attr) {
				$meta = wp_get_attachment_metadata($attr['post']->ID);
				$original_width = $meta['width'];
				$original_height = $meta['height'];
				echo "<tr>";
				echo "<td><a style='position: relative; top: -2px;' href='media.php?attachment_id=" . $attr['post']->ID . "&action=edit'>" . 
					$attr['post']->post_title . '<br />' .
					"<span style='font-size: 9px; line-height: 10px; display: block;'>" . $original_width . "×" . $original_height . "</span>";
					"</a></td>";
				foreach ($attr['info'] as $aindex => $aval) {
					echo "<td id='wr2x_" . $aindex .  "_" . $attr['post']->ID . "'>";
					if ( is_array( $aval ) ) {
						echo "<img title='Please upload a bigger original image.' style='margin-top: 3px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "exclamation.png' />" .
						"<span style='font-size: 9px; margin-left: 5px; position: relative; top: -4px;'>< " . $aval['width'] . "×" . $aval['height'] . "</span>";
					}
					else if ( $aval == 'EXISTS' ) {
						echo "<img style='margin-top: 3px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "tick-circle.png' />";
					}
					else if ( $aval == 'PENDING' ) {
						echo "<img title='Click on \"Generate\".' style='margin-top: 3px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "clock.png' />";
					}
					else if ( $aval == 'MISSING' ) {
						echo "<img title='The file related to this size is missing.' style='margin-top: 3px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "cross-small.png' />";
					}
					else if ( $aval == 'IGNORED' ) {
						echo "<img title='Retina disabled.' style='margin-top: 3px;' src='" . trailingslashit( WP_PLUGIN_URL ) . trailingslashit( 'wp-retina-2x/img') . "prohibition-small.png' />";
					}
					else {
						echo "<span style='position: relative; top: 3px;'>" . $aval . "</span>";
					}
					echo "</td>";
				}
				echo "<td><a style='position: relative; top: 3px;' onclick='wr2x_generate(" . $attr['post']->ID . ", true)' id='wr2x_generate_button_" . $attr['post']->ID . "' class='button-secondary'>" . __( "Generate", 'wp-retina-2x' ) . "</a></td>";
				
				if ( function_exists( 'enable_media_replace' ) ) {
					echo "<td style='padding-top: 5px; padding-bottom: 0px;'>";
					$_GET["attachment_id"] = $attr['post']->ID;
					$form = enable_media_replace( "" );
					echo str_replace( "Upload a new file", "Upload", $form["enable-media-replace"]['html'] );
					echo "</td>";
				}
				
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
	</div>
	<?php
}
?>