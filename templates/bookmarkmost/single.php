<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


$object_id    = $item->object_id;
$object_type  = $item->object_type;
$object_title = wp_strip_all_tags( get_the_title( $object_id ) );
if ( $object_title == '' ) {
	$object_title = esc_html__( 'Untitled', 'cbxwpbookmark' );
}
$object_link = get_permalink( $object_id );

$show_thumb = isset( $instance['show_thumb'] ) ? intval( $instance['show_thumb'] ) : 1;
$thumb_size = 'thumbnail';
$thumb_attr = [];

echo '<li class="cbxwpbookmark-mostlist-item' . esc_attr( $li_class ) . '" >';

do_action( 'cbxwpbookmark_bookmarkmost_single_item_start', $object_id, $item );

echo '<a title="' . esc_attr( $object_title ) . '" href="' . esc_url( $object_link ) . '">';
$thumb_html = '';
if ( $show_thumb ) {
	if ( has_post_thumbnail( $object_id ) ) {
		$thumb_html = get_the_post_thumbnail( $object_id, $thumb_size, $thumb_attr );
	} elseif ( ( $parent_id = wp_get_post_parent_id( $object_id ) ) && has_post_thumbnail( $parent_id ) ) {
		$thumb_html = get_the_post_thumbnail( $parent_id, $thumb_size, $thumb_attr );
	}

	echo $thumb_html;  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
echo $object_title . $show_count_html;  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '</a>';
do_action( 'cbxwpbookmark_bookmarkmost_single_item_end', $object_id, $item );
echo '</li>';