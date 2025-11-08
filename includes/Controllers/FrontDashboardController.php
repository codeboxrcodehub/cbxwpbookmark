<?php
namespace Cbx\Bookmark\Controllers;
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Cbx\Bookmark\CBXWPBookmarkSettings;
use Exception;

class FrontDashboardController {

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

			$user_id = get_current_user_id();
			
			// Get the current month and year, or fetch them from request params if needed
			$month      = isset( $data['month'] ) ? intval( $data['month'] ) : gmdate('m');
			$year       = isset( $data['year'] ) ? intval( $data['year'] ) : gmdate('Y');

			$daily_bookmark = cbxwpbookmark_get_daily_bookmark_counts( $year , $month, $user_id );
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

			$settings = new CBXWPBookmarkSettings();
			$category_mode = $settings->get_field( 'bookmark_mode', 'cbxwpbookmark_basics', 'no_cat' );

			$bookmarkCategoriesDataTemp = [];

			if( $category_mode == 'user_cat'){
				$bookmarkCategoriesDataTemp = \CBXWPBookmarkHelper::getBookmarkCategoriesWithCount( $year, $month, $user_id );
			}elseif(  $category_mode == 'global_cat' ){
				$bookmarkCategoriesDataTemp = \CBXWPBookmarkHelper::getBookmarkCategoriesWithCount( $year, $month );
			}
			

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
}//end class FrontDashboardController