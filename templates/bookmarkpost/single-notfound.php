<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

echo '<li class="cbxwpbookmark-mylist-item cbxwpbookmark-mylist-item-notfound">';
do_action( 'cbxwpbookmark_bookmarkpost_not_found_start' );
echo esc_html__( 'No bookmark found', 'cbxwpbookmark' );
do_action( 'cbxwpbookmark_bookmarkpost_not_found_end' );
echo '</li>';