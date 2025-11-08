<?php
/**
 * Provide a dashboard bookmark category listing
 *
 * This file is used to mark up the admin-facing bookmark category listing
 *
 * @link       https://codeboxr.com
 * @since      1.0.7
 *
 * @package    cbxwpbookmark
 * @subpackage cbxwpbookmark/templates/admin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<?php

do_action( 'cbxwpbookmark_before_vuejs_mount_before', 'category-list' );
?>
<div id="cbxwpbookmark-category"></div>
<?php
do_action( 'cbxwpbookmark_before_vuejs_mount_after', 'category-list' );