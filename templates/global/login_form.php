<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
if ( ! is_user_logged_in() ):
	if ( is_singular() ) {
		$login_url    = wp_login_url( get_permalink() );
		$redirect_url = get_permalink();
	} else {
		global $wp;
		//$login_url =  wp_login_url( home_url( $wp->request ) );
		$login_url    = wp_login_url( home_url( add_query_arg( [], $wp->request ) ) );
		$redirect_url = home_url( add_query_arg( [], $wp->request ) );
	}

	$guest_html = '<div class="cbx-guest-wrap cbxwpbookmark-guest-wrap">';

	if ( isset( $inline ) && $inline == 1 ) {
		$guest_html .= '<p class="cbx-title-login cbxwpbookmark-title-login">' . wp_kses( __( 'Do you have account, <a role="button" class="guest-login-trigger cbxwpbookmark-guest-login-trigger" href="#">please login</a>', 'cbxwpbookmark' ), [ 'a' => [ 'href' => [], 'role' => [], 'class' => [], 'style' => [] ] ] ) . '</p>';
	}

	$guest_login_html = wp_login_form( [
		'redirect' => $redirect_url,
		'echo'     => false
	] );


	$guest_login_html = apply_filters( 'cbxwpbookmark_login_html', $guest_login_html, $login_url, $redirect_url );


	$guest_register_html = '';
	$guest_show_register = absint( $settings->get_field( 'guest_show_register', 'cbxwpbookmark_basics', 1 ) );
	if ( $guest_show_register ) {
		if ( get_option( 'users_can_register' ) ) {
			$register_url = add_query_arg( 'redirect_to', urlencode( $redirect_url ), wp_registration_url() );
			/* translators: %s: registration url */
			$guest_register_html .= '<p class="cbx-guest-register cbxwpbookmark-guest-register">' . sprintf( wp_kses( __( 'No account yet? <a href="%1$s">Register</a>', 'cbxwpbookmark' ), [ 'a' => [ 'href' => [] ] ] ), $register_url ) . '</p>';
		}

		$guest_register_html = apply_filters( 'cbxwpbookmark_register_html', $guest_register_html, $redirect_url );
	}//end show register

	$guest_html .= '<div class="cbx-guest-login-wrap cbxwpbookmark-guest-login-wrap">' . $guest_login_html . $guest_register_html . '</div>';
	$guest_html .= '</div>';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<div class="cbx-chota"><div class="container"><div class="row"><div class="col-12">' . $guest_html . '</div></div></div></div>';
endif;
if ( isset( $inline ) && $inline == 1 ):
	?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.cbx-guest-wrap').on('click', '.guest-login-trigger', function (e) {
                e.preventDefault();

                var $this  = $(this);
                var parent = $this.closest('.cbx-guest-wrap');
                parent.find('.cbx-guest-login-wrap').toggle();
            });
        });
    </script>
<?php endif;