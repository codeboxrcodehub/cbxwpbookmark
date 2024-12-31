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

echo '<li class="cbxwpbookmark-mylist-item ' . esc_attr( $sub_item_class ) . '">';
do_action( 'cbxwpbookmark_bookmarkpost_single_item_start', $object_id, $item );
echo '<a title="' . esc_attr( $object_title ) . '" href="' . esc_url( $object_link ) . '">' . $object_title . '</a>' . $action_html;  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
do_action( 'cbxwpbookmark_bookmarkpost_single_item_end', $object_id, $item );
echo '</li>';