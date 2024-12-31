<!-- This file is used to markup the administration form of the widget. -->

<!-- Widget Tittle -->
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
		<?php esc_html_e( 'Title', "cbxwpbookmark" ); ?>
    </label>

    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
           name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>">
		<?php esc_html_e( 'Display Limit', "cbxwpbookmark" ); ?>
    </label>

    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
           name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo absint( $limit ); ?>"/>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'daytime' ) ); ?>"> <?php esc_html_e( 'Select Time Duration', "cbxwpbookmark" ); ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'daytime' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'daytime' ) ); ?>">
            <option value="0"><?php esc_html_e( '-- All Time --', "cbxwpbookmark" ); ?></option>
            <option value="1" <?php echo ( $daytime == '1' ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "1 Day", 'cbxwpbookmark' ); ?>
            </option>
            <option value="7" <?php echo ( $daytime == "7" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "7 Days", 'cbxwpbookmark' ); ?>
            </option>
            <option value="30" <?php echo ( $daytime == "30" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "30 Days", 'cbxwpbookmark' ); ?>
            </option>
            <option value="180" <?php echo ( $daytime == "180" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "6 Months", 'cbxwpbookmark' ); ?>
            </option>
            <option value="365" <?php echo ( $daytime == "365" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "1 Year", 'cbxwpbookmark' ); ?>
            </option>
        </select> </label>

</p>
<?php
$object_types = CBXWPBookmarkHelper::object_types();
?>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"> <?php esc_html_e( 'Post Type(s)', "cbxwpbookmark" ); ?>

        <select multiple="true" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>[]">

            <option value="" <?php echo ( sizeof( $type ) == 0 ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Select Post Type(s)", "cbxwpbookmark" ) ?>
            </option>
			<?php
			if ( isset( $object_types['builtin']['types'] ) ) {
				echo '<optgroup label="' . esc_html__( 'Built-in Post Types', 'cbxwpbookmark' ) . '">';
				foreach ( $object_types['builtin']['types'] as $type_slug => $type_name ) {
					$type_slug = esc_attr( $type_slug );
					$selected  = in_array( $type_slug, $type ) ? ' selected="selected" ' : '';
					echo '<option value="' . esc_attr( $type_slug ) . '" ' . $selected . ' >' . esc_attr( $type_name ) . '</option>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				echo '</optgroup>';
			}

			if ( isset( $object_types['custom']['types'] ) ) {
				echo '<optgroup label="' . esc_html__( 'Custom Post Types', 'cbxwpbookmark' ) . '">';
				foreach ( $object_types['custom']['types'] as $type_slug => $type_name ) {
					$type_slug = esc_attr( $type_slug );
					$selected  = in_array( $type_slug, $type ) ? ' selected="selected" ' : '';
					echo '<option value="' . esc_attr( $type_slug ) . '" ' . $selected . ' >' . esc_attr( $type_name ) . '</option>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				echo '</optgroup>';
			}
			?>

        </select>

    </label>

</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( "Order By", "cbxwpbookmark" ); ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
            <option value="object_count" <?php echo ( $order_by == "object_count" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Bookmark Count", "cbxwpbookmark" ); ?></option>
            <option value="object_type" <?php echo ( $order_by == "object_type" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Post Type", "cbxwpbookmark" ); ?></option>
            <option value="object_id" <?php echo ( $order_by == "object_id" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Post ID", "cbxwpbookmark" ); ?></option>
            <option value="id" <?php echo ( $order_by == "id" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Bookmark ID", "cbxwpbookmark" ); ?></option>
            <option value="title" <?php echo ( $order_by == "title" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Post Title", "cbxwpbookmark" ); ?></option>
        </select>

    </label>

</p>
<?php
$order = strtoupper( $order );
?>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( "Order", "cbxwpbookmark" ); ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
            <option value="ASC" <?php echo ( $order == "ASC" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Ascending", "cbxwpbookmark" ) ?> </option>
            <option value="DESC" <?php echo ( $order == "DESC" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Descending", "cbxwpbookmark" ) ?> </option>
        </select> </label>

</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( "Show Count", "cbxwpbookmark" ); ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>">

            <option value="1" <?php echo ( $show_count == "1" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Yes", "cbxwpbookmark" ) ?>
            </option>

            <option value="0" <?php echo ( $show_count == "0" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "No", "cbxwpbookmark" ) ?>
            </option>
        </select>
    </label>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>"><?php esc_html_e( "Show Thumb", "cbxwpbookmark" ) ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>">
            <option value="1" <?php echo ( $show_thumb == 1 ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Yes", "cbxwpbookmark" ) ?>
            </option>
            <option value="0" <?php echo ( $show_thumb == 0 ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "No", "cbxwpbookmark" ) ?>
            </option>
        </select>
    </label>
</p>