<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

global $wpdb;
$log_id = ( isset( $_GET['id'] ) && absint( $_GET['id'] ) > 0 ) ? absint( $_GET['id'] ) : 0; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>

<?php
$back_svg = cbxwpbookmarks_load_svg( 'icon_back' );
?>

<div class="wrap cbx-chota cbxwpbookmark-page-wrapper cbxwpbookmark-category-edit-wrapper" id="cbxwpbookmark-category-edit">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2></h2>
				<?php
				$cat_addedit_error = get_transient( 'cbxwpbookmark_cat_addedit_error' );
				if ( $cat_addedit_error ) {
					$validation = $cat_addedit_error;
					delete_transient( 'cbxwpbookmark_cat_addedit_error' );

					$error_class = ( isset( $validation['error'] ) && intval( $validation['error'] ) == 1 ) ? 'notice notice-error' : 'notice notice-success';

					if ( isset( $validation['msg'] ) ) {
						echo '<div class="' . esc_attr( $error_class ) . '"><p>' . esc_attr( $validation['msg'] ) . '</p></div>';
					}
				}


				$operation_mode = ( $log_id > 0 ) ? esc_html__( 'Edit', 'cbxwpbookmark' ) : esc_html__( 'Add', 'cbxwpbookmark' )

				?>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_before', 'cbxwpbookmark-cats-edit' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_before', 'cbxwpbookmark-cats-edit' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbxwpbookmark">
							<?php
							/* translators: %s: Category add/edit mode */
							echo sprintf( esc_html_x( 'Category: %s', 'Category add or edit', 'cbxwpbookmark' ), esc_attr( $operation_mode ) );
							?>
                        </h1>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_left_after', 'cbxwpbookmark-cats-edit' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right pull-right">
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_before', 'cbxwpbookmark-cats-edit' ); ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=cbxwpbookmarkcats' ) ); ?>" class="button secondary icon">
                            <i class="cbx-icon">
								<?php
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo $back_svg;
								?>
                            </i>
                            <span class="button-label"> <?php esc_html_e( 'Back', 'cbxwpbookmark' ); ?></span>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=cbxwpbookmark_settings' ) ); ?>" class="button outline primary"><?php esc_html_e( 'Global Settings', 'cbxwpbookmark' ); ?></a>
						<?php do_action( 'cbxwpbookmark_wpheading_wrap_right_after', 'cbxwpbookmark-cats-edit' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbxwpbookmark_wpheading_wrap_after', 'cbxwpbookmark-cats-edit' ); ?>
            </div>
        </div>
    </div>
    <div class="container">
		<?php do_action( 'cbxwpbookmark_category_admineditform_before_postbox', $log_id ); ?>
		<?php do_action( 'cbxwpbookmark_category_admineditform_before', $log_id ); ?>

		<?php
		$category_info = null;
		$privacy       = 1;
		$cat_name      = '';

		if ( $log_id > 0 ) {
			$category_info = CBXWPBookmarkHelper::singleCategory( $log_id );

			if ( ! is_null( $category_info ) ) {

				$privacy  = isset( $category_info['privacy'] ) ? intval( $category_info['privacy'] ) : 1;
				$cat_name = isset( $category_info['cat_name'] ) ? stripslashes( $category_info['cat_name'] ) : '';
				$user_id  = isset( $category_info['user_id'] ) ? intval( $category_info['user_id'] ) : 0;


				$created_date = '';
				if ( $category_info['created_date'] != '0000-00-00 00:00:00' ) {
					$created_date = CBXWPBookmarkHelper::dateReadableFormat( stripslashes( $category_info['created_date'] ) );
				}

				$date_modified = '';
				if ( $category_info['modyfied_date'] != '0000-00-00 00:00:00' ) {
					$date_modified = CBXWPBookmarkHelper::dateReadableFormat( stripslashes( $category_info['modyfied_date'] ) );
				}

			}//end review information
		}
		?>

        <form data-busy="0" id="cbxwpbookmark_category_admineditform" method="post" class="cbx_form_wrapper cbx_form_wrapper_cbxwpbookmark" novalidate>
			<?php do_action( 'cbxwpbookmark_category_add_form_start', $log_id ); ?>
            <div class="row">
                <div class="col-8">
                    <div class="postbox">
                        <div class="inside">
							<?php if ( $log_id > 0 ) : ?>
                                <div class="cbxwpbookmark-form-field">
                                    <label for="user_id"><?php esc_html_e( 'Created By', 'cbxwpbookmark' ); ?></label>
                                    <div class="cbxwpbookmark-form-field-output">
                                        <a target="_blank" class="disabled" href="<?php echo esc_url( get_edit_user_link( intval( $user_id ) ) ) ?>">
											<?php echo esc_html( get_userdata( $user_id )->display_name ); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="cbxwpbookmark-form-field">
                                    <label for="cat_created"><?php esc_html_e( 'Created', 'cbxwpbookmark' ); ?></label>
                                    <input disabled readonly id="cat_created" class="cbxwpbookmark-form-field-input" type="text" value="<?php echo esc_attr( $created_date ); ?>"/>
                                </div>
                                <div class="cbxwpbookmark-form-field">
                                    <label for="cat_updated"><?php esc_html_e( 'Updated', 'cbxwpbookmark' ); ?></label>
                                    <input disabled readonly id="cat_updated" class="cbxwpbookmark-form-field-input" type="text" value="<?php echo esc_attr( $date_modified ); ?>"/>
                                </div>
							<?php endif; ?>
							<?php
							do_action( 'cbxwpbookmark_category_admineditform_start', $log_id, $category_info );
							?>
                            <div class="cbxwpbookmark-form-field">
                                <label for="cbxwpbookmark_cat_name" class=""><?php esc_html_e( 'Category Name', 'cbxwpbookmark' ); ?></label>
                                <input type="text" name="cbxwpbookmark_form[cat_name]"
                                       id="cbxwpbookmark_cat_name"
                                       class="regular-text cbxwpbookmark-form-field-input cbxwpbookmark-form-field-input-text cbxwpbookmark_cat_name"
                                       required
                                       placeholder="<?php esc_html_e( 'Category Name', 'cbxwpbookmark' ); ?>"
                                       value="<?php echo esc_attr( wp_unslash( $cat_name ) ); ?>"/>
                            </div>
							<?php
							do_action( 'cbxwpbookmark_category_admineditform_end', $log_id, $category_info );
							?>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="postbox">
                        <div class="postbox-header"><h2><?php esc_html_e( 'Actions', 'cbxwpbookmark' ); ?></h2></div>
                        <div class="inside">
                            <div class="cbxwpbookmark-form-field">
                                <label for="cbxwpbookmark_privacy"><?php esc_html_e( 'Status', 'cbxwpbookmark' ); ?></label><br/>
                                <select name="cbxwpbookmark_form[privacy]" id="cbxwpbookmark_privacy">
                                    <option <?php selected( 1, $privacy, true ); ?>
                                            value="1"><?php esc_html_e( 'Public', 'cbxwpbookmark' ); ?></option>
                                    <option <?php selected( 0, $privacy, true ); ?>
                                            value="0"><?php esc_html_e( 'Private', 'cbxwpbookmark' ); ?></option>
                                </select>
                            </div>

                            <input type="hidden" name="cbxwpbookmark_cat_addedit" value="1"/> <input
                                    type="hidden" name="cbxwpbookmark_form[ajax]" value="0"/>
							<?php wp_nonce_field( 'cbxwpbookmark_cat_addedit', 'cbxwpbookmark_cat_nonce' ); ?>

                            <input type="hidden" id="cbxwpbookmark_id" name="cbxwpbookmark_form[id]"
                                   value="<?php echo absint( $log_id ); ?>"/>
                            <p class="label-cbxwpbookmark-submit-processing"
                               style="display: none;"><?php esc_html_e( 'Please wait, do not close this window.', 'cbxwpbookmark' ); ?></p>
                            <br/>
                            <button type="submit"
                                    class="button primary btn-cbxwpbookmark-submit"><?php echo ( $log_id > 0 ) ? esc_html__( 'Submit Edit', 'cbxwpbookmark' ) : esc_html__( 'Submit Create', 'cbxwpbookmark' ); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

		<?php do_action( 'cbxwpbookmark_category_admineditform_after', $log_id ); ?>
		<?php do_action( 'cbxwpbookmark_category_admineditform_after_postbox', $log_id ); ?>
    </div>
</div>