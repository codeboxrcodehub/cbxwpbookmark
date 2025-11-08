<?php
namespace Cbx\Bookmark\Widgets\Vc\Params;

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CBXWPBookmarkVCParamDropDownMulti {
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initiator.
	 */
	public function __construct() {
		$this->version = CBXWPBOOKMARK_PLUGIN_VERSION;
		$version       = $this->version;

		$css_url_part     = CBXWPBOOKMARK_ROOT_URL . 'assets/css/';
		$js_url_part      = CBXWPBOOKMARK_ROOT_URL . 'assets/js/';
		$vendors_url_part = CBXWPBOOKMARK_ROOT_URL . 'assets/vendors/';

		$css_path_part     = CBXWPBOOKMARK_ROOT_PATH . 'assets/css/';
		$js_path_part      = CBXWPBOOKMARK_ROOT_PATH . 'assets/js/';
		$vendors_path_part = CBXWPBOOKMARK_ROOT_PATH . 'assets/vendors/';

		if ( defined( 'WPB_VC_VERSION' ) && version_compare( WPB_VC_VERSION, 4.8 ) >= 0 ) {
			if ( function_exists( 'vc_add_shortcode_param' ) ) {

				wp_register_style( 'select2', $vendors_url_part . 'select2/select2.min.css', [], $version );           //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
				wp_register_script( 'select2', $vendors_url_part . 'select2/select2.min.js', [ 'jquery' ], $version ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter

				wp_enqueue_style( 'select2' );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'select2' );

				vc_add_shortcode_param( 'cbxwpbookmarkdownmulti', [ $this, 'dropdown_multi_render' ] );
			}
		} else {
			if ( function_exists( 'add_shortcode_param' ) ) {

				wp_register_style( 'select2', $vendors_url_part . 'select2/select2.min.css', [], $version );           //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
				wp_register_script( 'select2', $vendors_url_part . 'select2/select2.min.js', [ 'jquery' ], $version ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter

				wp_enqueue_style( 'select2' );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'select2' );

				add_shortcode_param( 'cbxwpbookmarkdownmulti', [ $this, 'dropdown_multi_render' ] );
			}
		}
	}

	/**
	 * Select2 Dropdown Field Render
	 *
	 * @param $settings
	 * @param $value
	 *
	 * @return string
	 */
	public function dropdown_multi_render( $settings, $value ) {
		$dependency = '';
		$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$type       = isset( $settings['type'] ) ? $settings['type'] : '';
		$class      = isset( $settings['class'] ) ? $settings['class'] : '';

		$uni    = uniqid( 'cbxwpbookmarkdownmulti-' . wp_rand() );
		$output = '<style>
.select2-container{
	width: 100% !important;
</style>';
		$output .= '<select multiple name="' . esc_attr( $param_name ) . '" class="wpb_vc_param_value wpb-input wpb-select cbxwpbookmarkdownmulti ' . esc_attr( $param_name ) . ' ' . esc_attr( $type ) . '' . esc_attr( $class ) . '">';


		$param_value_arr = [];

		if ( ! is_array( $value ) ) {
			$param_value_arr = explode( ',', $value );
		} else {
			$param_value_arr = $value;
		}

		foreach ( $settings['value'] as $text_val => $val ) {
			if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
				$text_val = $val;
			}

			$selected = '';


			//if ( $value !== '' && in_array( $val, $param_value_arr ) ) {
			if ( sizeof( $param_value_arr ) > 0 && in_array( $val, $param_value_arr ) ) {
				$selected = ' selected="selected"';
			}

			$output .= '<option class="cbxwpbookmarkdownmulti-' . esc_attr( $val ) . '" value="' . esc_attr( $val ) . '"' . $selected . '>' . $text_val . '</option>';
		};
		$output .= '</select>';

		$output .= '<script type="text/javascript">
 					jQuery(\'.cbxwpbookmarkdownmulti\').select2({
 						allowClear: false,
 						dropdownParent: jQuery("#vc_ui-panel-edit-element")
 					});
				</script>';

		return $output;
	}

}

new CBXWPBookmarkVCParamDropDownMulti();