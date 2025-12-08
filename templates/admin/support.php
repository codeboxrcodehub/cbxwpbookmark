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
$cbxwpbookmark_plugin_url = CBXWPBookmarkHelper::url_utmy( 'https://codeboxr.com/product/cbx-wordpress-bookmark/' );
$cbxwpbookmark_doc_url    = CBXWPBookmarkHelper::url_utmy( 'https://codeboxr.com/doc/cbxwpbookmark-doc/' );
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
                        <a href="<?php echo admin_url( 'admin.php?page=cbxwpbookmark-settings' ); ?>" class="button outline primary pull-right"><?php esc_html_e( 'Global Settings',
                                    'cbxwpbookmark' ); ?></a>
                        <?php do_action( 'cbxwpbookmark_wpheading_wrap_right_after', 'support' ); ?>
                    </div>
                </div>
                <?php do_action( 'cbxwpbookmark_wpheading_wrap_after', 'support' ); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
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
                                Version - 2.0.6
                            </p>
                            <ul>
                                <li>[fixed] Dashboard and frontend bookmark listing js error fixed if single click category mode</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.5
                            </p>
                            <ul>
                                <li>[fixed] VC widget error fixed</li>
                                <li>[updated] WordPress 6.9 compatible</li>
                                <li>[updated] Pro Addon 2.0.5 released</li>
                                <li>[new] Pro addon now supports automatic update [check setting]</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.4
                            </p>
                            <ul>
                                <li>[fixed] Composer based custom scope/namespace for vendor to avoid conflict with other plugins</li>
                                <li>[fixed] Composer based custom scope/namespace for vendor to avoid conflict with other plugins</li>
                                <li>[fixed] Pro addon 2.0.4 released and needed to update to make it work with core 2.0.4</li>
                                <li>[note] Pro addon note: deactivate, manually delete &amp; install new version or install the new version as replacement.</li>
                                <li>[fixed] Astra theme hash base vue js route fixed</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.3
                            </p>
                            <ul>
                                <li>[important] From core version 2.0.0 there is compatibility issues with php and pro addon. For safe deactivate Bookmark pro addon(s). Update core &amp; activate. the
                                    update pro addon.
                                </li>
                                <li>[fixed] Fixed site crash or db connectivity error if not default mysql port</li>
                                <li>[improved] Table names are now escape[security improvement]</li>
                                <li>[improved] Pro Addon compatibility checking improved(2nd attempt)</li>
                                <li>[fixed] Frontend Dashboard - bookmarks and category link fixed</li>
                                <li>[updated] Pro addon 2.0.3 released</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.2
                            </p>
                            <ul>
                                <li>[fixed] Pro Addon compatibility checking improved</li>
                                <li>[updated] Pro addon 2.0.2 released</li>
                                <li>[fixed] Auto page create security issue fixed(Reported by Nabil Irawan)</li>
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
                                Version - 2.0.5
                            </p>
                            <ul>
                                <li>[fixed] VC widget error fixed</li>
                                <li>[updated] WordPress 6.9 compatible</li>
                                <li>[updated] Core plugin 2.0.5 released</li>
                                <li>[new] Pro addon now supports automatic update [check setting]</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.4
                            </p>
                            <ul>
                                <li>[note] Need core plugin 2.0.4</li>
                                <li>[updated] Core plugin 2.0.4 released</li>
                                <li>[fixed] Composer/PHP package Conflict issue solved</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.3
                            </p>
                            <ul>
                                <li>[improved] Table names are now escape[security improvement]</li>
                                <li>[updated] Core plugin 2.0.3 released</li>
                            </ul>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <p>
                                Version - 2.0.2
                            </p>
                            <ul>
                                <li>[updated] Misc improvement</li>
                                <li>[updated] Core plugin 2.0.2 released</li>
                            </ul>
                        </div>
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
                        $cbxwpbookmark_items = CBXWPBookmarkHelper::codeboxr_news_feed();
                        if ( $cbxwpbookmark_items !== false && count( $cbxwpbookmark_items ) > 0 ) {
                            //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                            foreach ( $cbxwpbookmark_items as $item ) {
                                $url   = $item['url']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                                $title = $item['title'];//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

                                echo '<div class="cbx-backend-settings-row">';
                                echo '<a href="' . esc_url( $url ) . '" target="_blank">';
                                echo '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <defs/>
                                                <path d="M16.4 9.1L12.2 5c-.3-.3-.7-.3-1-.2s-.6.5-.6.9v1.7H4.2c-.5 0-.9.4-.9.9v3.4c0 .2.1.5.3.7.2.2.4.3.7.3h6.4v1.7c0 .4.2.7.6.9.4.1.8.1 1-.2l4.1-4.2c.4-.5.4-1.3 0-1.8z"
                                                      fill="currentColor"/>
                                            </svg>';

                                //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
                                echo $title;
                                echo '</a>';
                                echo '</div>';
                            }//end for loop
                        }//if data found
                        ?>
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
                            <a href="<?php echo esc_url( $cbxwpbookmark_plugin_url ); ?>" target="_blank">
                                <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <defs/>
                                    <path d="M10 2.6c-4.4 0-7.9 3.6-7.9 7.9s3.6 7.9 7.9 7.9 7.9-3.6 7.9-7.9-3.5-7.9-7.9-7.9zm1.7 12.3c-.4.2-.7.3-1 .4-.2.1-.5.1-.8.1-.5 0-.9-.1-1.2-.4-.3-.2-.4-.5-.4-.9v-.4c0-.2.1-.3.1-.5l.5-1.8c0-.2.1-.4.1-.5v-.4c0-.2 0-.4-.1-.5-.1-.1-.3-.2-.5-.2-.1 0-.3 0-.4.1-.2 0-.3.1-.4.1l.1-.6c.3-.1.7-.3 1-.3.3-.1.6-.2.9-.2.5 0 .9.1 1.1.4.3.2.4.5.4.9v.4c0 .2-.1.4-.1.5l-.5 1.9c0 .1-.1.3-.1.5v.4c0 .2.1.4.2.5.1.1.3.1.6.1.1 0 .3 0 .4-.1.2 0 .3-.1.3-.1l-.2.6zm-.1-7.3c-.2.2-.5.3-.9.3-.3 0-.6-.1-.9-.3-.2-.2-.3-.5-.3-.8 0-.3.1-.6.4-.8.2-.2.5-.3.9-.3.3 0 .6.1.9.3.2.2.4.5.4.8-.2.3-.3.6-.5.8z"
                                          fill="currentColor"/>
                                </svg>
                                <?php esc_html_e( 'CBX Bookmark Plugin Details', 'cbxwpbookmark' ); ?> </a>
                        </div>
                        <div class="cbx-backend-settings-row">
                            <a href="<?php echo esc_url( $cbxwpbookmark_doc_url ); ?>" target="_blank">
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
                <div class="cbx-backend-card dashboard-wp-plugin">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'Other WordPress Plugins', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <?php
                        $cbxwpbookmark_top_plugins = [
                                'https://codeboxr.com/product/cbx-petition-for-wordpress/'                            => 'CBX Petition',
                                'https://codeboxr.com/product/cbx-multi-criteria-rating-review-for-wordpress/'        => 'CBX Multi Criteria Rating & Review',
                                'https://codeboxr.com/product/comfort-restaurant-booking-for-wordpress/'              => 'Comfort Restaurant Booking',
                                'https://codeboxr.com/product/cbx-changelog-for-wordpress/'                           => 'CBX Changelog',
                                'https://codeboxr.com/product/cbx-tour-user-walkthroughs-guided-tours-for-wordpress/' => 'CBX Tour – User Walkthroughs/Guided Tours',
                                'https://codeboxr.com/product/cbx-email-logger-for-wordpress/'                        => 'CBX Email SMTP & Logger',
                                'https://codeboxr.com/product/cbx-poll-for-wordpress/'                                => 'CBX Poll',
                                'https://codeboxr.com/product/cbx-user-online-for-wordpress/'                         => 'CBX User Online & Last Login',
                                'https://codeboxr.com/product/cbx-single-criteria-rating-review-for-wordpress/'       => 'CBX 5 Star Rating & Review',
                                'https://codeboxr.com/product/cbx-google-map-for-wordpress/'                          => 'CBX Map for Google Map & OpenStreetMap'
                        ];

                        foreach ( $cbxwpbookmark_top_plugins as $link => $title ) {
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
                <div class="cbx-backend-card dashboard-hrm-erp">
                    <div class="header">
                        <div class="text">
                            <h2><?php esc_html_e( 'New Plugins: HRM & ERP ', 'cbxwpbookmark' ); ?></h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="cbx-backend-settings-row">
                            <ul>
                                <li>
                                    <a rel="external" target="_blank" href="https://comforterp.com/product/comfort-accounting-accounting-solution-for-wordpress">Comfort
                                        Accounting</a></li>
                                <li>
                                    <a rel="external" target="_blank" href="https://comforterp.com/product/comfort-inventory-and-invoice-complete-invoice-plugin-for-wordpress">Comfort
                                        Inventory & Invoice</a></li>
                                <li><a rel="external" target="_blank" href="https://comforthrm.com/product/comfort-resume-wordpress-resume-builder-plugin">Comfort
                                        Resume</a></li>
                                <li><a rel="external" target="_blank" href="https://comforthrm.com/product/comfort-job-wordpress-job-board-plugin">Comfort
                                        Job Board</a></li>
                                <li><a rel="external" target="_blank" href="https://comforthrm.com/product/comfort-job-application-plugin-for-wordpress">Comfort
                                        Job Application</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>