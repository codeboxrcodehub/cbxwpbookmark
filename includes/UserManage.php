<?php
namespace Cbx\Bookmark;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Some user functionality
 *
 * Class UserManage
 *
 * @package Cbx\Bookmark
 * @since 1.0.0
 */
class UserManage {

	/**
	 * Get current logged in user roles
	 *
	 * @return array
	 */
	public static function get_current_user_role() {
		if (is_user_logged_in()) {
			$current_user = wp_get_current_user();

			$roles = $current_user->roles;
			if(!is_array($roles)) $roles = [];
		} else {
			$roles = ['guest'];
		}

		return $roles;
	}//end method get_current_user_role
}//end class UserManage