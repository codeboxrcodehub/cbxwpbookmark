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
$bookmark_list = new CBXWPBookmark_List_Table( [ 'screen' => get_current_screen()->id ] );

//Fetch, prepare, sort, and filter CBXSCRatingReviewLog data
$bookmark_list->prepare_items();
?>
<div class="wrap cbx-chota cbxwpbookmark-page-wrapper cbxwpbookmark-logs-listing-wrapper" id="cbxwpbookmark-logs-listing">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2></h2>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_before', 'cbxwpbookmark-logs' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_before', 'cbxwpbookmark-logs' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbxwpbookmark">
							<?php esc_html_e( 'Bookmark Manager', 'cbxwpbookmark' ); ?>
                        </h1>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_after', 'cbxwpbookmark-logs' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right  pull-right">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_before', 'cbxwpbookmark-logs' ); ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=cbxwpbookmark_settings' ) ); ?>" class="button outline primary"><?php esc_html_e( 'Global Settings', 'cbxwpbookmark' ); ?></a>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_after', 'cbxwpbookmark-logs' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_after', 'cbxwpbookmark-logs' ); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
				<?php do_action( 'cbxwpbookmark_logs_listing_before_postbox' ); ?>
                <div class="postbox">
                    <div class="clear clearfix"></div>
                    <div class="inside">
						<?php do_action( 'cbxwpbookmark_logs_listing_before' ); ?>
                        <form id="cbxwpbookmark_logs" method="post" class="cbx-wplisttable">
							<?php do_action( 'cbxwpbookmark_logs_listing_form_start' ); ?>
							<?php $bookmark_list->views(); ?>
							<?php //phpcs:ignore WordPress.Security.NonceVerification.Recommended  ?>
                            <input type="hidden" name="page" value="<?php echo isset( $_REQUEST['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) ) : ''; ?>"/>
							<?php $bookmark_list->display() ?>
							<?php do_action( 'cbxwpbookmark_logs_listing_form_end' ); ?>
                        </form>
						<?php do_action( 'cbxwpbookmark_logs_listing_after' ); ?>
                    </div>
                    <div class="clear clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>