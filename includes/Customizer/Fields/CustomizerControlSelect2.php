<?php
namespace Cbx\Bookmark\Customizer\Fields;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Select2 customize control class.
 *
 */
class CustomizerControlSelect2 extends \WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 */
	public $type = 'cbxwpbookmark_select2';

	/**
	 * The type of Select2 Dropwdown to display. Can be either a single select dropdown or a multi-select dropdown. Either false for true. Default = false
	 */
	private $multiselect = false;
	/**
	 * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clear all option. Default = 'Please select...'
	 */
	private $placeholder = '';

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = [], $options = [] ) {
		parent::__construct( $manager, $id, $args );
		// Check if this is a multi-select field
		if ( isset( $this->input_attrs['multiselect'] ) && $this->input_attrs['multiselect'] ) {
			$this->multiselect = true;
		}
		// Check if a placeholder string has been specified
		if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
			$this->placeholder = $this->input_attrs['placeholder'];
		}

		$this->placeholder = esc_html__( 'Please select...', 'cbxwpbookmark' );
	}//end method construction

	/**
	 * Displays the multiple select on the customize screen.
	 *
	 */
	public function render_content() {
		$defaultValue = $this->value();

		if ( $this->multiselect ) {
			$defaultValue = explode( ',', $this->value() );
		}
		?>
        <div class="cbxwpbookmark_dropdown_select2_control">
			<?php if ( ! empty( $this->label ) ) { ?>
                <label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
                </label>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
                <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
            <input type="hidden" id="<?php echo esc_attr( $this->id ); ?>"
                   class="cbxwpbookmark-customize-control-dropdown-select2"
                   value="<?php echo esc_attr( $this->value() ); ?>"
                   name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> /> <select
                    name="select2-list-<?php echo( $this->multiselect ? 'multi[]' : 'single' ); ?>"
                    class="cbxwpbookmark-customize-control-select2"
                    data-placeholder="<?php echo esc_attr( $this->placeholder ); ?>" <?php echo( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
				<?php

				if ( ! $this->multiselect ) {
					// When using Select2 for single selection, the Placeholder needs an empty <option> at the top of the list for it to work (multi-selects dont need this)
					echo '<option></option>';
				}

				foreach ( $this->choices as $key => $value ) {
					if ( is_array( $value ) ) {
						echo '<optgroup label="' . esc_attr( $key ) . '">';
						foreach ( $value as $optgroupkey => $optgroupvalue ) {
							if ( $this->multiselect ) {
								echo '<option value="' . esc_attr( $optgroupkey ) . '" ' . ( in_array( esc_attr( $optgroupkey ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $optgroupvalue ) . '</option>';
							} else {
								echo '<option value="' . esc_attr( $optgroupkey ) . '" ' . selected( esc_attr( $optgroupkey ), $defaultValue, false ) . '>' . esc_attr( $optgroupvalue ) . '</option>';
							}

						}
						echo '</optgroup>';
					} else {
						if ( $this->multiselect ) {
							echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( esc_attr( $key ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $value ) . '</option>';
						} else {
							echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $key ), $defaultValue, false ) . '>' . esc_attr( $value ) . '</option>';
						}

					}
				}
				?>
            </select>
        </div>
	<?php }
}//end class CustomizerControlSelect2
