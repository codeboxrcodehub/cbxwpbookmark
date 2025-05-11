<?php
/**
 * This template provides the Dashboard view of the plugin
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
$plugin_url = CBXWPBookmarkHelper::url_utmy( 'https://codeboxr.com/product/cbx-wordpress-bookmark/' );
$doc_url    = CBXWPBookmarkHelper::url_utmy( 'https://codeboxr.com/doc/cbxwpbookmark-doc/' );
?>
<div class="wrap cbx-chota cbxwpbookmark-page-wrapper cbxwpbookmark-support-wrapper" id="cbxwpbookmark-support">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2></h2>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_before', 'support' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_before', 'support' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbxwpbookmark">
							<?php esc_html_e( 'CBX Bookmarks: Dashboard', 'cbxwpbookmark' ); ?>
                        </h1>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_after', 'support' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right pull-right">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_before', 'support' ); ?>
						<?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <a href="<?php echo admin_url( 'admin.php?page=cbxwpbookmark_settings' ); ?>" class="button outline primary pull-right"><?php esc_html_e( 'Global Settings', 'cbxwpbookmark' ); ?></a>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_after', 'support' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_after', 'support' ); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="cbx-backend-card dashboard-stat">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Summary at a Glance', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div id="dashboard_stats">
                            <div class="dashboard_stat dashboard_stat_bookmarks">
                                <h3>
                                   <span class="dashboard_stat_icon">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1792 1792"><path d="M1408 256H384v1242l423-406 89-85 89 85 423 406V256zm12-128q23 0 44 9 33 13 52.5 41t19.5 62v1289q0 34-19.5 62t-52.5 41q-19 8-44 8-48 0-83-32l-441-424-441 424q-36 33-83 33-23 0-44-9-33-13-52.5-41t-19.5-62V240q0-34 19.5-62t52.5-41q21-9 44-9h1048z"></path></svg>
                                   </span> <?php esc_html_e( 'Bookmark', 'cbxwpbookmark' ); ?></h3>
								<?php
								$total_bookmarks = cbxwpbookmarks_getTotalBookmarkCount();
								?>
                                <p>
									<?php
									/* translators: %d: Total system wide bookmark count */
									echo sprintf( esc_html__( '%d bookmarks', 'cbxwpbookmark' ), absint( $total_bookmarks ) );
									?>
                                </p>
                            </div>
                            <div class="dashboard_stat dashboard_stat_category">
                                <h3><span class="dashboard_stat_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024"><path
                                                    d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 2.7.4 5.3.7 8 1.1-4.5-.7-8.8-1.8-12.9-3.5l7.2 3c-2.8-1.2-5.5-2.6-8.1-4.3-5.6-3.7 2.9 2.7 1.3 1.2-1.1-1.1-2.3-2-3.4-3.1-.9-.9-1.8-1.9-2.7-2.9-3.3-3.6 4.8 6.9.8.8-1.7-2.6-3.1-5.3-4.3-8.1l3 7.2c-1.7-4.2-2.9-8.5-3.5-12.9.4 2.7.7 5.3 1.1 8-1-7.8-.5-16-.5-23.9V170.1c0-2.6.2-5.1.5-7.7-.4 2.7-.7 5.3-1.1 8 .7-4.5 1.8-8.8 3.5-12.9l-3 7.2c1.2-2.8 2.6-5.5 4.3-8.1 3.7-5.6-2.7 2.9-1.2 1.3 1.1-1.1 2-2.3 3.1-3.4.9-.9 1.9-1.8 2.9-2.7 3.6-3.3-6.9 4.8-.8.8 2.6-1.7 5.3-3.1 8.1-4.3l-7.2 3c4.2-1.7 8.5-2.9 12.9-3.5-2.7.4-5.3.7-8 1.1 9.8-1.2 20.1-.5 30-.5h198.2c11.8 0 24.2-1 35.9.4-2.7-.4-5.3-.7-8-1.1 3.6.6 6.9 1.5 10.3 2.8l-7.2-3c2.4 1 4.5 2.3 6.7 3.5 5 2.7-5.3-5-1.2-1 .9.9 1.9 1.7 2.8 2.6.7.7 1.4 1.5 2.1 2.3 1.4 1.6 2.8 3.2 4.2 4.9 6.2 7.2 12.5 14.4 18.7 21.5 17.5 20.2 35.1 40.4 52.6 60.6 6.8 7.8 13.5 15.6 20.3 23.4 7.9 9.2 13.8 16.5 28.3 17 7.3.2 14.7 0 22 0h293.8c29.5 0 59-.1 88.4 0 2.6 0 5.2.2 7.8.5-2.7-.4-5.3-.7-8-1.1 4.5.7 8.8 1.8 12.9 3.5l-7.2-3c2.8 1.2 5.5 2.6 8.1 4.3 5.6 3.7-2.9-2.7-1.3-1.2 1.1 1.1 2.3 2 3.4 3.1.9.9 1.8 1.9 2.7 2.9 3.3 3.6-4.8-6.9-.8-.8 1.7 2.6 3.1 5.3 4.3 8.1l-3-7.2c1.7 4.2 2.9 8.5 3.5 12.9-.4-2.7-.7-5.3-1.1-8 .8 6.7.5 13.6.5 20.4v472.1c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.4-2.7.7-5.3 1.1-8-.7 4.5-1.8 8.8-3.5 12.9l3-7.2c-1.2 2.8-2.7 5.5-4.3 8.1-3.7 5.6 2.7-2.9 1.2-1.3-1.1 1.1-2 2.3-3.1 3.4-.9.9-1.9 1.8-2.9 2.7-3.6 3.3 6.9-4.8.8-.8-2.6 1.7-5.3 3.1-8.1 4.3l7.2-3c-4.2 1.7-8.5 2.9-12.9 3.5 2.7-.4 5.3-.7 8-1.1-2 .2-4.3.3-6.7.3-15.7.2-30.7 13.6-30 30 .7 16.1 13.2 30.2 30 30 25.2-.3 48.6-11.7 64-31.7 11.1-14.3 16.9-31.5 17.1-49.7.1-5.6 0-11.1 0-16.7V338.7c0-26.8 3-56-12.8-79.4-14.9-22.1-37.5-35-64.1-37.2-2.7-.2-5.4-.2-8.1-.2H502.8c7.1 2.9 14.1 5.9 21.2 8.8-23.1-26.6-46.2-53.3-69.3-79.9-10.8-12.4-21.5-24.8-32.3-37.3C407.2 96 385.9 88 363.1 88H131.5c-14.7 0-29.4-.5-43.3 5.1-23.7 9.4-40.9 27.1-49.3 51.2-2.8 8.2-3.9 17.2-3.9 25.8-.1 16.1 0 32.3 0 48.4V824.5c0 18.8-2 40.3 5.1 57.9 9.5 23.5 26.7 40.4 50.6 49.1 8.3 3 17.5 4.1 26.3 4.1h790.9c15.7 0 30.7-13.8 30-30-.7-16-13.1-29.8-30-29.8z"></path><path
                                                    d="M907.9 875.8H116.7c-2.6 0-5.2-.1-7.8-.5 2.7.4 5.3.7 8 1.1-4.5-.7-8.8-1.8-12.9-3.5l7.2 3c-2.8-1.2-5.5-2.6-8.1-4.3-5.6-3.7 2.9 2.7 1.3 1.2-1.1-1.1-2.3-2-3.4-3.1-.9-.9-1.8-1.9-2.7-2.9-3.3-3.6 4.8 6.9.8.8-1.7-2.6-3.1-5.3-4.3-8.1l3 7.2c-1.7-4.2-2.9-8.5-3.5-12.9.4 2.7.7 5.3 1.1 8-.8-6.7-.5-13.6-.5-20.4V369.3c0-21.9-.2-43.8 0-65.7 0-2.5.2-5 .5-7.5-.4 2.7-.7 5.3-1.1 8 .7-4.5 1.8-8.8 3.5-12.9l-3 7.2c1.2-2.8 2.6-5.5 4.3-8.1 3.7-5.6-2.7 2.9-1.2 1.3 1.1-1.1 2-2.3 3.1-3.4.9-.9 1.9-1.8 2.9-2.7 3.6-3.3-6.9 4.8-.8.8 2.6-1.7 5.3-3.1 8.1-4.3l-7.2 3c4.2-1.7 8.5-2.9 12.9-3.5-2.7.4-5.3.7-8 1.1 8.8-1.1 17.9-.5 26.7-.5H906.9c2.6 0 5.2.1 7.8.5-2.7-.4-5.3-.7-8-1.1 4.5.7 8.8 1.8 12.9 3.5l-7.2-3c2.8 1.2 5.5 2.6 8.1 4.3 5.6 3.7-2.9-2.7-1.3-1.2 1.1 1.1 2.3 2 3.4 3.1.9.9 1.8 1.9 2.7 2.9 3.3 3.6-4.8-6.9-.8-.8 1.7 2.6 3.1 5.3 4.3 8.1l-3-7.2c1.7 4.2 2.9 8.5 3.5 12.9-.4-2.7-.7-5.3-1.1-8 .8 6.7.5 13.6.5 20.4v472.1c0 21.9.2 43.8 0 65.7 0 2.5-.2 5-.5 7.5.4-2.7.7-5.3 1.1-8-.7 4.5-1.8 8.8-3.5 12.9l3-7.2c-1.2 2.8-2.7 5.5-4.3 8.1-3.7 5.6 2.7-2.9 1.2-1.3-1.1 1.1-2 2.3-3.1 3.4-.9.9-1.9 1.8-2.9 2.7-3.6 3.3 6.9-4.8.8-.8-2.6 1.7-5.3 3.1-8.1 4.3l7.2-3c-4.2 1.7-8.5 2.9-12.9 3.5 2.7-.4 5.3-.7 8-1.1-2.1.4-4.4.5-6.8.5-15.7.2-30.7 13.6-30 30 .7 16.1 13.2 30.2 30 30 35.5-.5 65.6-23 77.2-56.3 3.1-9 3.9-18.8 3.9-28.3V303c-.2-14.8-4.1-30.1-12.1-42.6-10-15.7-24-26.5-41.1-33.4-13-5.3-26.7-5.1-40.4-5.1H120.1c-2.3 0-4.7 0-7 .1-34.7 2-65.2 25.1-75 59-3.9 13.4-3.1 27.7-3.1 41.5v507.7c0 8.2-.1 16.4 0 24.6.2 15.6 4.5 31.9 13.6 44.8 10.6 15.1 24.3 25.5 41.5 31.9 9 3.4 18.8 4.3 28.4 4.3H908.1c15.7 0 30.7-13.8 30-30-.9-16.2-13.3-30-30.2-30z"></path></svg>
                                    </span> <?php esc_html_e( 'Category', 'cbxwpbookmark' ); ?></h3>
								<?php
								$total_bookmarks = cbxwpbookmarks_getTotalCategoryCount();
								?>
                                <p>
									<?php
									/* translators: %d: Total system wide category count */
									echo sprintf( esc_html__( '%d categories', 'cbxwpbookmark' ), absint( $total_bookmarks ) );
									?>
                                </p>
                            </div>
                        </div>
                        <div id="dashboard_stats_types">
							<?php
							$object_types         = CBXWPBookmarkHelper::object_types_assoc();
							$allowed_object_types = cbxwpbookmarks_allowed_object_type();
							$count_by_type        = [];

							$type_total = 0;
							if ( is_array( $allowed_object_types ) && count( $allowed_object_types ) ) {
								foreach ( $allowed_object_types as $type ) {
									$count_by_type[ $type ] = $type_count = cbxwpbookmarks_getTotalBookmarkCountByType( $type );
									$type_total             += $type_count;
								}
							}

							foreach ( $count_by_type as $type => $count ) {
								$count_percent = ( $type_total > 0 ) ? absint( $count * 100 / $type_total ) : 0;

								echo '<div class="dashboard_stats_type_container dashboard_stats_type_container_' . esc_attr( $type ) . '">';
								echo '<div title="' . absint( $count_percent ) . '%" class="dashboard_stats_type_bar dashboard_stats_type_bar_' . esc_attr( $type ) . '" style="width:' . absint( $count_percent ) . '%;"></div>';
								echo '<div class="dashboard_stats_type_bar_label">' . esc_attr( $object_types[ $type ] ) . '(' . absint( $count_percent ) . '%)</div>';
								echo '</div>';
							}
							?>
                        </div>
                    </div>
                </div>
                <div class="cbx-backend-card">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Get Free & Pro Addons', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="row">
                            <div class="col-12">
                                <div class="cbx-backend-feature-card">
                                    <div class="feature-card-body static">
                                        <div class="feature-card-header">
                                            <a href="https://codeboxr.com/product/cbx-wordpress-bookmark/?utm_source=plgsidebarinfo&utm_medium=plgsidebar&utm_campaign=wpfreemium"
                                               target="_blank"> <img
                                                        src="https://codeboxr.com/wp-content/uploads/productshots/445-profile.png"
                                                        alt="CBX Bookmark for WordPress"/> </a>

                                        </div>
                                        <div class="feature-card-description">
                                            <h3>
                                                <a href="https://codeboxr.com/product/cbx-wordpress-bookmark/?utm_source=plgsidebarinfo&utm_medium=plgsidebar&utm_campaign=wpfreemium"
                                                   target="_blank">CBX Bookmark Pro Addon</a></h3>
                                            <p>Pro features for CBX Bookmark plugin.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-6">
                                <div class="cbx-backend-feature-card">
                                    <div class="feature-card-body static">
                                        <div class="feature-card-header">
                                            <a href="https://codeboxr.com/product/cbx-bookmark-mycred-addon/?utm_source=plgsidebarinfo&utm_medium=plgsidebar&utm_campaign=wpfreemium"
                                               target="_blank"> <img
                                                        src="https://codeboxr.com/wp-content/uploads/productshots/11792-profile.png"
                                                        alt="CBX Bookmark myCred Addon"/> </a>

                                        </div>
                                        <div class="feature-card-description">
                                            <h3>
                                                <a href="https://codeboxr.com/product/cbx-bookmark-mycred-addon/?utm_source=plgsidebarinfo&utm_medium=plgsidebar&utm_campaign=wpfreemium"
                                                   target="_blank">CBX Bookmark myCred Addon</a></h3>
                                            <p>myCred integration. Point on bookmark.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="cbx-backend-card dashboard-changelog">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Changelog Core Plugin', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 1.9.10
                            </p>
                            <ul>
                                <li>[updated] Pro addon plugin 1.5.3 released</li>
                                <li>[improved] Minor improvements</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 1.9.9
                            </p>
                            <ul>
                                <li>[improved] WordPress version 6.8 compatibility checked</li>
                                <li>[updated] Pro addon plugin 1.5.2 released</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 1.9.8
                            </p>
                            <ul>
                                <li>[improved] Bookmark button tooltip improved based on bookmarked or not <a href="https://wordpress.org/support/topic/return-to-page-message/">https://wordpress.org/support/topic/return-to-page-message/</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="cbx-backend-card dashboard-changelog">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Changelog Pro Addon', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 1.5.3
                            </p>
                            <ul>
                                <li>[improved] WordPress version 6.8 compatibility checked</li>
                                <li>[updated] Core plugin 1.9.9 released</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 1.5.2
                            </p>
                            <ul>
                                <li>[improved] WordPress version 6.8 compatibility checked</li>
                                <li>[updated] Core plugin 1.9.9 released</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 1.5.1
                            </p>
                            <ul>
                                <li>[updated] Merged mycred addon with pro addon.</li>
                                <li>[new] Add gamipress support from V1.5.1</li>
                                <li>[updated] Core plugin V1.9.5 released</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="cbx-backend-card dashboard-support">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Help & Supports', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <a href="<?php echo esc_url( $plugin_url ); ?>" target="_blank">
                                <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <defs/>
                                    <path d="M10 2.6c-4.4 0-7.9 3.6-7.9 7.9s3.6 7.9 7.9 7.9 7.9-3.6 7.9-7.9-3.5-7.9-7.9-7.9zm1.7 12.3c-.4.2-.7.3-1 .4-.2.1-.5.1-.8.1-.5 0-.9-.1-1.2-.4-.3-.2-.4-.5-.4-.9v-.4c0-.2.1-.3.1-.5l.5-1.8c0-.2.1-.4.1-.5v-.4c0-.2 0-.4-.1-.5-.1-.1-.3-.2-.5-.2-.1 0-.3 0-.4.1-.2 0-.3.1-.4.1l.1-.6c.3-.1.7-.3 1-.3.3-.1.6-.2.9-.2.5 0 .9.1 1.1.4.3.2.4.5.4.9v.4c0 .2-.1.4-.1.5l-.5 1.9c0 .1-.1.3-.1.5v.4c0 .2.1.4.2.5.1.1.3.1.6.1.1 0 .3 0 .4-.1.2 0 .3-.1.3-.1l-.2.6zm-.1-7.3c-.2.2-.5.3-.9.3-.3 0-.6-.1-.9-.3-.2-.2-.3-.5-.3-.8 0-.3.1-.6.4-.8.2-.2.5-.3.9-.3.3 0 .6.1.9.3.2.2.4.5.4.8-.2.3-.3.6-.5.8z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'CBX Bookmark Plugin Details', 'cbxwpbookmark' ); ?> </a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="<?php echo esc_url( $doc_url ); ?>" target="_blank">
                                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.5834 3.75C12.9584 3.75 11.2084 4.08333 10 5C8.79171 4.08333 7.04171 3.75 5.41671 3.75C4.20837 3.75 2.92504 3.93333 1.85004 4.40833C1.24171 4.68333 0.833374 5.275 0.833374 5.95V15.35C0.833374 16.4333 1.85004 17.2333 2.90004 16.9667C3.71671 16.7583 4.58337 16.6667 5.41671 16.6667C6.71671 16.6667 8.10004 16.8833 9.21671 17.4333C9.71671 17.6833 10.2834 17.6833 10.775 17.4333C11.8917 16.875 13.275 16.6667 14.575 16.6667C15.4084 16.6667 16.275 16.7583 17.0917 16.9667C18.1417 17.2417 19.1584 16.4417 19.1584 15.35V5.95C19.1584 5.275 18.75 4.68333 18.1417 4.40833C17.075 3.93333 15.7917 3.75 14.5834 3.75ZM17.5 14.3583C17.5 14.8833 17.0167 15.2667 16.5 15.175C15.875 15.0583 15.225 15.0083 14.5834 15.0083C13.1667 15.0083 11.125 15.55 10 16.2583V6.66667C11.125 5.95833 13.1667 5.41667 14.5834 5.41667C15.35 5.41667 16.1084 5.49167 16.8334 5.65C17.2167 5.73333 17.5 6.075 17.5 6.46667V14.3583Z"
                                          fill="currentColor"></path>
                                    <path d="M11.65 9.17504C11.3833 9.17504 11.1416 9.00837 11.0583 8.74171C10.95 8.41671 11.1333 8.05838 11.4583 7.95838C12.7416 7.54171 14.4 7.40838 15.925 7.58338C16.2666 7.62504 16.5166 7.93338 16.475 8.27504C16.4333 8.61671 16.125 8.86671 15.7833 8.82504C14.4333 8.66671 12.9583 8.79171 11.8416 9.15004C11.775 9.15837 11.7083 9.17504 11.65 9.17504ZM11.65 11.3917C11.3833 11.3917 11.1416 11.225 11.0583 10.9584C10.95 10.6334 11.1333 10.275 11.4583 10.175C12.7333 9.75837 14.4 9.62504 15.925 9.80004C16.2666 9.84171 16.5166 10.15 16.475 10.4917C16.4333 10.8334 16.125 11.0834 15.7833 11.0417C14.4333 10.8834 12.9583 11.0084 11.8416 11.3667C11.779 11.3827 11.7146 11.3911 11.65 11.3917ZM11.65 13.6084C11.3833 13.6084 11.1416 13.4417 11.0583 13.175C10.95 12.85 11.1333 12.4917 11.4583 12.3917C12.7333 11.975 14.4 11.8417 15.925 12.0167C16.2666 12.0584 16.5166 12.3667 16.475 12.7084C16.4333 13.05 16.125 13.2917 15.7833 13.2584C14.4333 13.1 12.9583 13.225 11.8416 13.5834C11.779 13.5993 11.7146 13.6077 11.65 13.6084Z"
                                          fill="currentColor"></path>
                                </svg>
								<?php esc_html_e( 'Documentation & User Guide', 'cbxwpbookmark' ); ?> </a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="https://wordpress.org/support/plugin/cbxwpbookmark/reviews/#new-post" target="_blank">
                                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.3 8.1c-.1-.3-.3-.5-.6-.5l-4.8-.7-2.2-4.4c-.1-.3-.4-.4-.7-.4-.3 0-.5.2-.7.4L7.2 6.9l-4.9.7c-.3 0-.5.2-.6.5-.1.3 0 .6.2.8l3.5 3.4-.8 4.7c0 .3.1.6.3.7.1.1.3.1.4.1.1 0 .2 0 .4-.1l4.3-2.3 4.3 2.3c.1.1.2.1.4.1.4 0 .8-.3.8-.8v-.2l-.8-4.8 3.5-3.4c.1 0 .2-.3.1-.5z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'Review & Rate CBX Bookmark Plugin', 'cbxwpbookmark' ); ?> </a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="https://wordpress.org/support/plugin/cbxwpbookmark/" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 22">
                                    <defs/>
                                    <path fill="currentColor" fill-rule="evenodd"
                                          d="M16 2H3c-.55 0-1 .45-1 1v14l4-4h10c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 2v7H5.17L4 12.17V4h11zm4 2h2c.55 0 1 .45 1 1v15l-4-4H7c-.55 0-1-.45-1-1v-2h13V6z"
                                          clip-rule="evenodd"/>
                                </svg>
								<?php esc_html_e( 'Core Plugin Support', 'cbxwpbookmark' ); ?></a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="https://codeboxr.com/contact-us" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 22">
                                    <defs/>
                                    <path fill="currentColor" fill-rule="evenodd"
                                          d="M16 2H3c-.55 0-1 .45-1 1v14l4-4h10c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm-1 2v7H5.17L4 12.17V4h11zm4 2h2c.55 0 1 .45 1 1v15l-4-4H7c-.55 0-1-.45-1-1v-2h13V6z"
                                          clip-rule="evenodd"/>
                                </svg>
								<?php esc_html_e( 'Pro Addon Support', 'cbxwpbookmark' ); ?></a>
                        </div>
                    </div>
                </div>
                <!--Third Party plugin Integration-->
                <div class="cbx-backend-card dashboard-plugin-Integration">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Third Party plugin Integration(Pro)', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <defs/>
                                    <path d="M9.4 11.9c-2 0-3.5-1.6-3.5-3.5V5.9c0-.2.2-.4.4-.4s.4.2.4.4v2.5c0 1.5 1.3 2.8 2.8 2.8s2.8-1.3 2.8-2.8v-.7c0-.2.2-.4.4-.4s.4.2.4.4v.6c-.1 2-1.7 3.6-3.7 3.6z"
                                          fill="currentColor"/>
                                    <path d="M13.2 6.2H5.6c-.2 0-.4-.2-.4-.4s.2-.4.4-.4h7.6c.2 0 .4.2.4.4 0 .3-.2.4-.4.4z"
                                          fill="currentColor"/>
                                    <path d="M8.3 6.2H7c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM11.9 6.2h-1.3c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM10.8 13.2H8c-.2 0-.4-.2-.4-.4v-1.6c0-.2.2-.4.4-.4h2.8c.2 0 .4.2.4.4v1.6c0 .2-.2.4-.4.4zm-2.4-.8h2v-.9h-2v.9z"
                                          fill="currentColor"/>
                                    <path d="M10.7 16.7c-.9 0-1.6-.7-1.6-1.6v-2.3c0-.2.2-.4.4-.4s.4.2.4.4v2.3c0 .5.4.9.9.9s.9-.4.9-.9v-.3c0-.2.2-.4.4-.4s.4.2.4.4v.3c-.2.8-1 1.6-1.8 1.6zM14.4 14.9c-.2 0-.4-.2-.4-.4v-1.9c0-.5-.4-.9-.9-.9s-.9.4-.9.9v.4c0 .2-.2.4-.4.4s-.4-.2-.4-.4v-.5c0-.9.7-1.6 1.6-1.6.9 0 1.6.7 1.6 1.6v1.9c.2.3 0 .5-.2.5zM10.8 7.7H6.2c-.2 0-.3-.2-.3-.4s.2-.4.4-.4h4.5c.2 0 .4.2.4.4s-.2.4-.4.4z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'Ultimate member', 'cbxwpbookmark' ); ?></p>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <defs/>
                                    <path d="M9.4 11.9c-2 0-3.5-1.6-3.5-3.5V5.9c0-.2.2-.4.4-.4s.4.2.4.4v2.5c0 1.5 1.3 2.8 2.8 2.8s2.8-1.3 2.8-2.8v-.7c0-.2.2-.4.4-.4s.4.2.4.4v.6c-.1 2-1.7 3.6-3.7 3.6z"
                                          fill="currentColor"/>
                                    <path d="M13.2 6.2H5.6c-.2 0-.4-.2-.4-.4s.2-.4.4-.4h7.6c.2 0 .4.2.4.4 0 .3-.2.4-.4.4z"
                                          fill="currentColor"/>
                                    <path d="M8.3 6.2H7c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM11.9 6.2h-1.3c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM10.8 13.2H8c-.2 0-.4-.2-.4-.4v-1.6c0-.2.2-.4.4-.4h2.8c.2 0 .4.2.4.4v1.6c0 .2-.2.4-.4.4zm-2.4-.8h2v-.9h-2v.9z"
                                          fill="currentColor"/>
                                    <path d="M10.7 16.7c-.9 0-1.6-.7-1.6-1.6v-2.3c0-.2.2-.4.4-.4s.4.2.4.4v2.3c0 .5.4.9.9.9s.9-.4.9-.9v-.3c0-.2.2-.4.4-.4s.4.2.4.4v.3c-.2.8-1 1.6-1.8 1.6zM14.4 14.9c-.2 0-.4-.2-.4-.4v-1.9c0-.5-.4-.9-.9-.9s-.9.4-.9.9v.4c0 .2-.2.4-.4.4s-.4-.2-.4-.4v-.5c0-.9.7-1.6 1.6-1.6.9 0 1.6.7 1.6 1.6v1.9c.2.3 0 .5-.2.5zM10.8 7.7H6.2c-.2 0-.3-.2-.3-.4s.2-.4.4-.4h4.5c.2 0 .4.2.4.4s-.2.4-.4.4z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'usersWP', 'cbxwpbookmark' ); ?></p>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <defs/>
                                    <path d="M9.4 11.9c-2 0-3.5-1.6-3.5-3.5V5.9c0-.2.2-.4.4-.4s.4.2.4.4v2.5c0 1.5 1.3 2.8 2.8 2.8s2.8-1.3 2.8-2.8v-.7c0-.2.2-.4.4-.4s.4.2.4.4v.6c-.1 2-1.7 3.6-3.7 3.6z"
                                          fill="currentColor"/>
                                    <path d="M13.2 6.2H5.6c-.2 0-.4-.2-.4-.4s.2-.4.4-.4h7.6c.2 0 .4.2.4.4 0 .3-.2.4-.4.4z"
                                          fill="currentColor"/>
                                    <path d="M8.3 6.2H7c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM11.9 6.2h-1.3c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM10.8 13.2H8c-.2 0-.4-.2-.4-.4v-1.6c0-.2.2-.4.4-.4h2.8c.2 0 .4.2.4.4v1.6c0 .2-.2.4-.4.4zm-2.4-.8h2v-.9h-2v.9z"
                                          fill="currentColor"/>
                                    <path d="M10.7 16.7c-.9 0-1.6-.7-1.6-1.6v-2.3c0-.2.2-.4.4-.4s.4.2.4.4v2.3c0 .5.4.9.9.9s.9-.4.9-.9v-.3c0-.2.2-.4.4-.4s.4.2.4.4v.3c-.2.8-1 1.6-1.8 1.6zM14.4 14.9c-.2 0-.4-.2-.4-.4v-1.9c0-.5-.4-.9-.9-.9s-.9.4-.9.9v.4c0 .2-.2.4-.4.4s-.4-.2-.4-.4v-.5c0-.9.7-1.6 1.6-1.6.9 0 1.6.7 1.6 1.6v1.9c.2.3 0 .5-.2.5zM10.8 7.7H6.2c-.2 0-.3-.2-.3-.4s.2-.4.4-.4h4.5c.2 0 .4.2.4.4s-.2.4-.4.4z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'buddyBoss', 'cbxwpbookmark' ); ?></p>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <defs/>
                                    <path d="M9.4 11.9c-2 0-3.5-1.6-3.5-3.5V5.9c0-.2.2-.4.4-.4s.4.2.4.4v2.5c0 1.5 1.3 2.8 2.8 2.8s2.8-1.3 2.8-2.8v-.7c0-.2.2-.4.4-.4s.4.2.4.4v.6c-.1 2-1.7 3.6-3.7 3.6z"
                                          fill="currentColor"/>
                                    <path d="M13.2 6.2H5.6c-.2 0-.4-.2-.4-.4s.2-.4.4-.4h7.6c.2 0 .4.2.4.4 0 .3-.2.4-.4.4z"
                                          fill="currentColor"/>
                                    <path d="M8.3 6.2H7c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM11.9 6.2h-1.3c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM10.8 13.2H8c-.2 0-.4-.2-.4-.4v-1.6c0-.2.2-.4.4-.4h2.8c.2 0 .4.2.4.4v1.6c0 .2-.2.4-.4.4zm-2.4-.8h2v-.9h-2v.9z"
                                          fill="currentColor"/>
                                    <path d="M10.7 16.7c-.9 0-1.6-.7-1.6-1.6v-2.3c0-.2.2-.4.4-.4s.4.2.4.4v2.3c0 .5.4.9.9.9s.9-.4.9-.9v-.3c0-.2.2-.4.4-.4s.4.2.4.4v.3c-.2.8-1 1.6-1.8 1.6zM14.4 14.9c-.2 0-.4-.2-.4-.4v-1.9c0-.5-.4-.9-.9-.9s-.9.4-.9.9v.4c0 .2-.2.4-.4.4s-.4-.2-.4-.4v-.5c0-.9.7-1.6 1.6-1.6.9 0 1.6.7 1.6 1.6v1.9c.2.3 0 .5-.2.5zM10.8 7.7H6.2c-.2 0-.3-.2-.3-.4s.2-.4.4-.4h4.5c.2 0 .4.2.4.4s-.2.4-.4.4z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'myCred', 'cbxwpbookmark' ); ?></p>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <defs/>
                                    <path d="M9.4 11.9c-2 0-3.5-1.6-3.5-3.5V5.9c0-.2.2-.4.4-.4s.4.2.4.4v2.5c0 1.5 1.3 2.8 2.8 2.8s2.8-1.3 2.8-2.8v-.7c0-.2.2-.4.4-.4s.4.2.4.4v.6c-.1 2-1.7 3.6-3.7 3.6z"
                                          fill="currentColor"/>
                                    <path d="M13.2 6.2H5.6c-.2 0-.4-.2-.4-.4s.2-.4.4-.4h7.6c.2 0 .4.2.4.4 0 .3-.2.4-.4.4z"
                                          fill="currentColor"/>
                                    <path d="M8.3 6.2H7c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM11.9 6.2h-1.3c-.2 0-.4-.2-.4-.4V4.3c0-.6.5-1 1-1 .6 0 1 .5 1 1v1.5c0 .3-.1.4-.3.4zm-1-.7h.6V4.3c0-.2-.1-.3-.3-.3-.2 0-.3.1-.3.3v1.2zM10.8 13.2H8c-.2 0-.4-.2-.4-.4v-1.6c0-.2.2-.4.4-.4h2.8c.2 0 .4.2.4.4v1.6c0 .2-.2.4-.4.4zm-2.4-.8h2v-.9h-2v.9z"
                                          fill="currentColor"/>
                                    <path d="M10.7 16.7c-.9 0-1.6-.7-1.6-1.6v-2.3c0-.2.2-.4.4-.4s.4.2.4.4v2.3c0 .5.4.9.9.9s.9-.4.9-.9v-.3c0-.2.2-.4.4-.4s.4.2.4.4v.3c-.2.8-1 1.6-1.8 1.6zM14.4 14.9c-.2 0-.4-.2-.4-.4v-1.9c0-.5-.4-.9-.9-.9s-.9.4-.9.9v.4c0 .2-.2.4-.4.4s-.4-.2-.4-.4v-.5c0-.9.7-1.6 1.6-1.6.9 0 1.6.7 1.6 1.6v1.9c.2.3 0 .5-.2.5zM10.8 7.7H6.2c-.2 0-.3-.2-.3-.4s.2-.4.4-.4h4.5c.2 0 .4.2.4.4s-.2.4-.4.4z"
                                          fill="currentColor"/>
                                </svg>
								<?php esc_html_e( 'Fifu(Featured Image from URL)', 'cbxwpbookmark' ); ?></p>
                        </div>

                    </div>
                </div><!--End Third Party plugin Integration-->
                <div class="cbx-backend-card dashboard-wp-plugin">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Other WordPress Plugins', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
						<?php
						$top_plugins = [
							//'https://codeboxr.com/product/cbx-wordpress-bookmark/' => 'CBX Bookmark & Favorite',
							'https://codeboxr.com/product/cbx-changelog-for-wordpress/'                           => 'CBX Changelog',
							'https://codeboxr.com/product/cbx-tour-user-walkthroughs-guided-tours-for-wordpress/' => 'CBX Tour – User Walkthroughs/Guided Tours',
							'https://codeboxr.com/product/cbx-currency-converter-for-wordpress/'                  => 'CBX Currency Converter',
							'https://codeboxr.com/product/cbx-email-logger-for-wordpress/'                        => 'CBX Email SMTP & Logger',
							'https://codeboxr.com/product/cbx-petition-for-wordpress/'                            => 'CBX Petition',
							'https://codeboxr.com/product/cbx-accounting/'                                        => 'CBX Accounting',
							'https://codeboxr.com/product/cbx-poll-for-wordpress/'                                => 'CBX Poll',
							'https://codeboxr.com/product/show-next-previous-article-for-wordpress'               => 'CBX Next Previous Article ',
							'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/'        => 'CBX Multi Criteria Rating & Review',
							'https://codeboxr.com/product/cbx-user-online-for-wordpress/'                         => 'CBX User Online & Last Login',
							'https://codeboxr.com/product/woocommerce-product-dropdown-field-for-contact-form7/'  => 'Woocommerce Product Dropdown field for Contact Form7',
						];

						foreach ( $top_plugins as $link => $title ) {
							echo '<div class="cbx-backend-settings-row">
                            <a href="' . esc_url( $link ) . '" target="_blank">
                                <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <defs/>
                                    <path d="M16.4 9.1L12.2 5c-.3-.3-.7-.3-1-.2s-.6.5-.6.9v1.7H4.2c-.5 0-.9.4-.9.9v3.4c0 .2.1.5.3.7.2.2.4.3.7.3h6.4v1.7c0 .4.2.7.6.9.4.1.8.1 1-.2l4.1-4.2c.4-.5.4-1.3 0-1.8z"
                                          fill="currentColor"/>
                                </svg>
                                ' . esc_attr( $title ) . '</a>
                        </div>';
						}
						?>
                    </div>
                </div>
                <div class="cbx-backend-card dashboard-wp-plugin">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Codeboxr News Updates', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
						<?php
						$items = CBXWPBookmarkHelper::codeboxr_news_feed();
						if ( $items !== false && count( $items ) > 0 ) {
							foreach ( $items as $item ) {
								$url   = $item['url'];
								$title = $item['title'];

								echo '<div class="cbx-backend-settings-row">';
								echo '<a href="' . esc_url( $url ) . '" target="_blank">';
                                    echo '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <defs/>
                                                <path d="M16.4 9.1L12.2 5c-.3-.3-.7-.3-1-.2s-.6.5-.6.9v1.7H4.2c-.5 0-.9.4-.9.9v3.4c0 .2.1.5.3.7.2.2.4.3.7.3h6.4v1.7c0 .4.2.7.6.9.4.1.8.1 1-.2l4.1-4.2c.4-.5.4-1.3 0-1.8z"
                                                      fill="currentColor"/>
                                            </svg>';

                                    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    echo $title;
								echo '</a>';
								echo '</div>';
							}//end for loop
						}//if data found
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>