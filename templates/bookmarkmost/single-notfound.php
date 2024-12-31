<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

echo '<li class="cbxwpbookmark-mostlist-item' . esc_attr( $li_class ) . '">';

do_action( 'cbxwpbookmark_bookmarkmost_not_found_start' );
echo esc_html__( 'No item found', "cbxwpbookmark" );
do_action( 'cbxwpbookmark_bookmarkmost_not_found_end' );

echo '</li>';