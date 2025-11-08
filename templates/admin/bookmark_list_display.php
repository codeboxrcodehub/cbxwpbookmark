<?php
/**
 * Provide a dashboard bookmark log listing
 *
 * This file is used to markup the admin-facing bookmark log listing
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

do_action( 'cbxwpbookmark_before_vuejs_mount_before', 'bookmark-list' );
?>
<div id="cbxwpbookmark-log-listing"></div>
<?php
do_action( 'cbxwpbookmark_before_vuejs_mount_after', 'bookmark-list' );
