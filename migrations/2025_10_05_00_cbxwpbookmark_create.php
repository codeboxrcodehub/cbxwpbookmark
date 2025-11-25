<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXWPBookmarkScoped\Illuminate\Database\Capsule\Manager as Capsule;

if ( ! class_exists( 'CBXWPBookmarkCreateTable' ) ) {
	/**
	 * Class CBXWPBookmarkCreateTable
	 * @since 2.0.0
	 */
	class CBXWPBookmarkCreateTable {

		/**
		 * Run migration
		 */
		public static function up() {
			$bookmark_table = 'cbxwpbookmark';

			try {
				if ( ! Capsule::schema()->hasTable( $bookmark_table ) ) {

					Capsule::schema()->create( $bookmark_table, function ( $table ) {
						$table->bigIncrements( 'id' );
						$table->bigInteger( 'object_id' );
						$table->string( 'object_type' )->default( 'post' );
						$table->integer( 'cat_id' )->default( 0 );
						$table->bigInteger( 'user_id' )->default( 0 );
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
				if ( Capsule::schema()->hasTable( 'cbxwpbookmark' ) ) {
					Capsule::schema()->dropIfExists( 'cbxwpbookmark' );
				}
			} catch ( \Exception $e ) {
				if ( function_exists( 'write_log' ) ) {
					write_log( $e->getMessage() );
				}
			}
		}//end method down

	}//end class CBXWPBookmarkCreateTable
}

if ( isset( $action ) && $action == 'up' ) {
	CBXWPBookmarkCreateTable::up();
} elseif ( isset( $action ) && $action == 'drop' ) {
	CBXWPBookmarkCreateTable::down();
}
