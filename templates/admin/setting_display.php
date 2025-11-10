<?php
/**
 * This template provides the setting view of the plugin
 *
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxwpbookmark
 * @subpackage cbxwpbookmark/templates/admin
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php
$cbxwpbookmark_save_svg = cbxwpbookmarks_load_svg( 'icon_save' );
?>
<div class="wrap cbx-chota cbxchota-setting-common cbx-page-wrapper cbxwpbookmark-page-wrapper cbxwpbookmark-setting-wrapper" id="cbxwpbookmark-setting">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2></h2>
				<?php
				settings_errors();
				?>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_before', 'settings' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_before', 'settings' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbxwpbookmark">
							<?php esc_html_e( 'Bookmarks: Global Settings', 'cbxwpbookmark' ); ?>
                        </h1>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_after', 'settings' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right  pull-right">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_before', 'settings' ); ?>
						<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <a href="<?php echo admin_url( 'admin.php?page=cbxwpbookmarkdash' ); ?>" class="button outline primary"><?php esc_html_e( 'Support & Docs', 'cbxwpbookmark' ); ?></a>
                        <a role="button" href="#" id="save_settings" class="button primary icon icon-right  mr-5">
                            <i class="cbx-icon">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo $cbxwpbookmark_save_svg;
								?>
                            </i>
                            <span class="button-label"><?php esc_html_e( 'Save Settings', 'cbxwpbookmark' ); ?></span>
                        </a>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_after', 'settings' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_after', 'settings' ); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
				<?php do_action( 'cbxwpbookmark_settings_form_before', 'settings' ); ?>
                <div class="postbox">
                    <div class="clear clearfix"></div>
                    <div class="inside setting-form-wrap">
                        <div class="clear clearfix"></div>
						<?php do_action( 'cbxwpbookmark_settings_form_start', 'settings' ); ?>
						<?php
						$settings->show_navigation();
						$settings->show_forms();
						?>
						<?php do_action( 'cbxwpbookmark_settings_form_end', 'settings' ); ?>
                        <div class="clear clearfix"></div>
                    </div>
                    <div class="clear clearfix"></div>
                </div>
				<?php do_action( 'cbxwpbookmark_settings_form_after', 'settings' ); ?>
            </div>
        </div>
    </div>
</div>