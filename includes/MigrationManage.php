<?php
namespace CBXWPBookmark;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXWPBookmark\Models\Migrations;
use Exception;
use CBXWPBookmarkScoped\Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Manage migration and database table
 * Class MigrationManage
 * @package CBXWPBookmark
 * @since 1.0.0
 */
class MigrationManage {

	/**
	 * Migration run and database table manage
	 *
	 * @since 1.0.0
	 */
	public static function run() {
		do_action('cbxwpbookmark_migration_run_start');

		$migrations = self::migration_files();

		$migrate_files = [];
		if ( ! Capsule::schema()->hasTable( 'cbxmigrations' ) ) {
			$migrate_files = $migrations;
		} else {
			$migrations_table_files = Migrations::query()->where( 'plugin', CBXWPBOOKMARK_PLUGIN_NAME )->get()->toArray();
			$migrated_files         = array_column( $migrations_table_files, 'migration' );
			$migrate_files      = array_values( array_diff( $migrations, $migrated_files ) );
		}

		// migration running
		foreach ( $migrate_files as $migration ) {

			$is_run_migration = self::load_migration( $migration, 'up' );

			 if ( $is_run_migration ) {
			 	if ( Capsule::schema()->hasTable( 'cbxmigrations' ) ) {
			 		Migrations::query()->create( [
			 			'migration' => $migration,
			 			'batch'     => 0,
			 			'plugin'    => CBXWPBOOKMARK_PLUGIN_NAME
			 		] );
			 	}
			 }
		}

		do_action('cbxwpbookmark_migration_run_end');
	} //end method run

	/**
	 * Drop migrations
	 *
	 * @return false
	 * @since 1.0.0
	 */
	public static function drop() {

		if ( ! Capsule::schema()->hasTable( 'cbxmigrations' ) ) {
			return false;
		}

		$migrations = Migrations::query()->where( 'plugin', CBXWPBOOKMARK_PLUGIN_NAME )->orderByDesc( 'id' )->get();

		foreach ( $migrations as $migration ) {
			$is_drop_migration = self::load_migration( $migration->migration, 'drop' );

			if ( $is_drop_migration ) {
				$migration->delete();
			}
		}

	} //end method drop

	/**
	 * Load Migration files
	 *
	 * @param  string  $filePath
	 * @param  string  $action  {up,drop}
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function load_migration( $filePath, $action ) {

		$fileFullPath = plugin_dir_path( __FILE__ ) . '../migrations/' . $filePath . '.php';

		try {
			if ( file_exists( $fileFullPath ) ) {

				include $fileFullPath;

				return true;
			} else {
				return false;
			}

		} catch ( Exception $e ) {

			return false;
		}

	} //end method load_migration

	/**
	 * get all Migration files
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function migration_files( ) {
		$migrations_released = [];//oficial released migration files

		//dev migration files
		$migrations_dev = [
			'2023_05_07_01_create_migrations_table',
			'2025_10_05_00_cbxwpbookmark_create',
			'2025_10_05_01_cbxwpbookmarkcat_create',
			'2025_16_10_00_add_sort_order_bookmark'
		];

		return $migrations_dev + $migrations_released;
	} //end method migration_files

	/**
	 * get Migration files left
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function migration_files_left( ) {

		$migrations = self::migration_files();

		if ( ! Capsule::schema()->hasTable( 'cbxmigrations' ) ) {
			$migrate_files = $migrations;
		} else {
			$migrations_table_files = Migrations::query()->where( 'plugin', CBXWPBOOKMARK_PLUGIN_NAME )->get()->toArray();
			$migrated_files         = array_column( $migrations_table_files, 'migration' );
			$migrate_files      = array_values( array_diff( $migrations, $migrated_files ) );
		}

		return $migrate_files;
	} //end method migration_files_left

} //end class MigrationManage