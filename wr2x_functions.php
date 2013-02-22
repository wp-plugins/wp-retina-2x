<?php

// Based on http://wordpress.stackexchange.com/questions/6645/turn-a-url-into-an-attachment-post-id
function wr2x_get_attachment_id( $file ) {
    $query = array(
        'post_type' => 'attachment',
		'meta_query' => array(
			array(
				'key'		=> '_wp_attached_file',
				'value'		=> ltrim( $file, '/' )
			)
		)
    );
    $posts = get_posts( $query );
    foreach( $posts as $post )
		return $post->ID;
    return false;
}

?>