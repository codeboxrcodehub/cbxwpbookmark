<!-- This file is used to markup the administration form of the widget. -->

<!-- Custom Title Field -->

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
		<?php esc_html_e( 'Title', 'cbxwpbookmark' ); ?>
    </label>

    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
           name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>
</p>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"><?php esc_html_e( "Display", "cbxwpbookmark" ) ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>">

            <option value="0" <?php echo ( $display == "0" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "List", "cbxwpbookmark" ) ?>
            </option>
            <option value="1" <?php echo ( $display == "1" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Dropdown", "cbxwpbookmark" ) ?>
            </option>
        </select> </label>

</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'show_bookmarks' ) ); ?>"><?php esc_html_e( "Show Bookmarks as Sublist(Only for List View)", "cbxwpbookmark" ) ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_bookmarks' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'show_bookmarks' ) ); ?>">
            <option value="0" <?php echo ( $show_bookmarks == "0" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "No", "cbxwpbookmark" ) ?>
            </option>
            <option value="1" <?php echo ( $show_bookmarks == "1" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Yes", "cbxwpbookmark" ) ?>
            </option>
        </select> </label>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'privacy' ) ); ?>">
		<?php esc_html_e( 'Privacy', "cbxwpbookmark" ); ?>
    </label>
    <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'privacy' ) ); ?>"
            name="<?php echo esc_attr( $this->get_field_name( 'privacy' ) ); ?>">
        <option value="2" <?php echo ( $privacy == 2 ) ? 'selected="selected"' : ''; ?>>
			<?php esc_html_e( "-- All --", "cbxwpbookmark" ) ?>
        </option>
        <option class="cbxwpbookmark-public" value="1" <?php echo ( $privacy == 1 ) ? 'selected="selected"' : ''; ?>>
			<?php esc_html_e( "Public only", "cbxwpbookmark" ) ?>
        </option>
        <option class="cbxwpbookmark-private" value="0" <?php echo ( $privacy == 0 ) ? 'selected="selected"' : ''; ?>>
			<?php esc_html_e( "Private only", "cbxwpbookmark" ) ?>
        </option>
    </select>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( "Order By", "cbxwpbookmark" ) ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
            <option value="id" <?php echo ( $order_by == "id" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Category ID", "cbxwpbookmark" ) ?>
            </option>
            <option value="cat_name" <?php echo ( $order_by == "cat_name" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Category Title", "cbxwpbookmark" ) ?>
            </option>
            <option value="privacy" <?php echo ( $order_by == "privacy" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Privacy", "cbxwpbookmark" ) ?>
            </option>
        </select>
    </label>
</p>

<?php
$order = strtoupper( esc_attr( $order ) );
?>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( "Order", "cbxwpbookmark" ) ?>

        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">

            <option value="ASC" <?php echo ( $order == "ASC" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Ascending", "cbxwpbookmark" ) ?>
            </option>

            <option value="DESC" <?php echo ( $order == "DESC" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Descending", "cbxwpbookmark" ) ?>
            </option>
        </select> </label>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'allowedit' ) ); ?>"><?php esc_html_e( "Allow Edit/Delete", "cbxwpbookmark" ) ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'allowedit' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'allowedit' ) ); ?>">

            <option value="1" <?php echo ( $allowedit == "1" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Yes", "cbxwpbookmark" ) ?>
            </option>

            <option value="0" <?php echo ( $allowedit == "0" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "No", "cbxwpbookmark" ) ?>
            </option>
        </select>
    </label>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( "Show Count", "cbxwpbookmark" ) ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>">

            <option value="1" <?php echo ( $show_count == "1" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Yes", "cbxwpbookmark" ) ?>
            </option>

            <option value="0" <?php echo ( $show_count == "0" ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "No", "cbxwpbookmark" ) ?>
            </option>
        </select> </label>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'honorauthor' ) ); ?>"><?php esc_html_e( "In Author Archive Show for Author", "cbxwpbookmark" ) ?>
        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'honorauthor' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'honorauthor' ) ); ?>">

            <option value="1" <?php echo ( $honorauthor == 1 ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "Yes", "cbxwpbookmark" ) ?>
            </option>

            <option value="0" <?php echo ( $honorauthor == 0 ) ? 'selected="selected"' : ''; ?>>
				<?php esc_html_e( "No", "cbxwpbookmark" ) ?>
            </option>

        </select>
    </label>
</p>
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'base_url' ) ); ?>">
		<?php esc_html_e( 'My Bookmark Page url(Base Url)', 'cbxwpbookmark' ); ?>
    </label>

    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'base_url' ) ); ?>"
           name="<?php echo esc_attr( $this->get_field_name( 'base_url' ) ); ?>" type="text" value="<?php echo esc_url( $base_url ); ?>"/>
</p>
