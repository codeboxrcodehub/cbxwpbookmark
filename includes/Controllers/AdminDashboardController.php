<?php
namespace Cbx\Bookmark\Controllers;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//use Cbx\Bookmark\Helpers\CBXWPBookmarkHelper;
use Exception;
use Cbx\Bookmark\MigrationManage;

class AdminDashboardController {

	/**
	 * Get get global overview data
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 * @since 2.0.0
	 */
	public function getGlobalOverviewData(\WP_REST_Request $request) {
		$response = new \WP_REST_Response();
		$response->set_status( 200 );

		try {
			$data       = $request->get_params();
			
			// Get the current month and year, or fetch them from request params if needed
			$month      = isset( $data['month'] ) ? intval( $data['month'] ) : gmdate('m');
			$year       = isset( $data['year'] ) ? intval( $data['year'] ) : gmdate('Y');

			$daily_bookmark = cbxwpbookmark_get_daily_bookmark_counts( $year , $month );
			$labels = array_map('strval', array_keys($daily_bookmark));
			$backgroundColors = array_map(fn() => sprintf('#%06X', wp_rand(0, 0xFFFFFF)), range(1, 12));

			// Prepare the chart data format
			$dailyBookmarkData = [
				'labels' => $labels,
				'datasets' => [
					[
						'label' => 'Bookmark Count',
						'backgroundColor' => $backgroundColors,
						'data' => $daily_bookmark
					]
				]
			];

			$bookmarkCategoriesDataTemp = \CBXWPBookmarkHelper::getBookmarkCategoriesWithCount( $year, $month );

			$typeLabels = [];
			$typeData   = [];

			foreach ( $bookmarkCategoriesDataTemp as $categoryDataArr ) {
				$typeLabels[] = $categoryDataArr['title'];
				$typeData[]   = intval( $categoryDataArr['bookmark_count'] );
			}

			$backgroundColors = array_map( fn() => sprintf( '#%06X', wp_rand( 0, 0xFFFFFF ) ), range( 1, count( $typeLabels ) ) );

			$bookmarkCategoriesData = [
				'labels'   => $typeLabels,
				'datasets' => [
					[
						'label'           => 'Bookmark Count',
						'backgroundColor' => $backgroundColors,
						'data'            => $typeData
					]
				]
			];

			$response->set_data( [
				'success' => true,
				'dailyBookmarkData'      => $dailyBookmarkData,
				'bookmarkCategoriesData' => $bookmarkCategoriesData,
			] );

			return $response;
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );

			return $response;
		}
	}//end method getGlobalOverviewData

	/**
	 * Full plugin option reset
	 */
	public function pluginOptionsReset(\WP_REST_Request $request) {

		$response = new \WP_REST_Response();
		$response->set_status( 200 );


		if ( ! current_user_can( 'manage_options' ) ) {
			throw new Exception( esc_html__( 'Sorry, you don\'t have enough permission!', 'cbxwpbookmark' ) );
		}

		try {
			$data = $request->get_params();
			
			do_action( 'cbxwpbookmark_plugin_reset_before' );

			//delete options
			$reset_options = isset( $data['reset_options'] ) ? $data['reset_options'] : [];

			foreach ( $reset_options as $key => $option ) {
				if($option){
					delete_option( $key );
				}
			}
		
			do_action( 'cbxwpbookmark_plugin_option_delete' );
			do_action( 'cbxwpbookmark_plugin_reset_after' );
			do_action( 'cbxwpbookmark_plugin_reset' );

			$response->set_data( [
				'success' => true,
				'info'    =>esc_html__( 'Bookmark setting options reset successfully', 'cbxwpbookmark' )
			] );

			return $response;
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );

			return $response;
		}
	} //end plugin_reset

	/**
	 * Full plugin option reset
	 */
	public function runMigration(\WP_REST_Request $request) {

		$response = new \WP_REST_Response();
		$response->set_status( 200 );

		try {
			MigrationManage::run();

			do_action( 'cbxwpbookmark_manual_migration_run_after' );
			
			$response->set_data( [
				'success' => true,
				'info'    => esc_html__( 'Migrated successfully', 'cbxwpbookmark' )
			] );

			return $response;
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );

			return $response;
		}
	} //end runMigration
}//end class AdminDashboardController