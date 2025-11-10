<?php
namespace CBXWPBookmark\Customizer\Fields;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Switch customize control class.
 *
 */
class CustomizerControlSwitch extends \WP_Customize_Control {

	/**
	 * The type of control being rendered
	 */
	public $type = 'cbxwpbookmark_switch';


	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
        <div class="cbxwpbookmark-switch-control">
            <div class="cbxwpbookmark-switch">
                <input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>"
                       name="<?php echo esc_attr( $this->id ); ?>" class="cbxwpbookmark-switch-checkbox"
                       value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link();
				checked( $this->value() ); ?>> <label class="cbxwpbookmark-switch-label"
                                                      for="<?php echo esc_attr( $this->id ); ?>"> <span
                            class="cbxwpbookmark-switch-inner"></span> <span class="cbxwpbookmark-switch-switch"></span>
                </label>
            </div>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) { ?>
                <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
        </div>
		<?php
	}//end method render_content
}//end class CustomizerControlSwitch
