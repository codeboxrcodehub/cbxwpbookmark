<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXWPBookmarkScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXWPBookmarkCreateCatTable' ) ) {
	/**
	 * Class CBXWPBookmarkCreateCatTable
	 * @since 2.0.0
	 */
	class CBXWPBookmarkCreateCatTable {

		/**
		 * Run migration
		 */
		public static function up() {
			$bookmarkcat_table = 'cbxwpbookmarkcat';

			try {
				if ( ! Capsule::schema()->hasTable( $bookmarkcat_table ) ) {

					Capsule::schema()->create( $bookmarkcat_table, function ( $table ) {
						$table->bigIncrements( 'id' );
						$table->string( 'cat_name' );
						$table->bigInteger( 'user_id' )->default( 0 );						
						$table->tinyInteger( 'privacy' )->default( 1 );
						$table->tinyInteger( 'locked' )->default( 0 );
						$table->timestamp('created_date')->useCurrent()->comment( 'created datetime' );
						$table->timestamp('modyfied_date')->useCurrentOnUpdate()->nullable()->comment( 'modified datetime' );
					} );

				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method up

		/**
		 * Drop migration
		 */
		public static function down() {
			try {
				if ( Capsule::schema()->hasTable( 'cbxwpbookmarkcat' ) ) {
					Capsule::schema()->dropIfExists( 'cbxwpbookmarkcat' );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXWPBookmarkCreateCatTable
}

if ( isset( $action ) && $action == 'up' ) {
	CBXWPBookmarkCreateCatTable::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXWPBookmarkCreateCatTable::down();
}
