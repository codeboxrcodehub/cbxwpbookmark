<?php
namespace Cbx\Bookmark\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Cbx\Bookmark\CBXWPBookmarkSettings;
//use Cbx\Bookmark\Helpers\CBXWPBookmarkHelper;
use Cbx\Bookmark\Models\Bookmark;
use Exception;
use WP_REST_Request;
use WP_REST_Response;

class BookmarkController {

	/**
	 * Get all bookmarks
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function index( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized request', 'cbxwpbookmark' ) );
			}

			$data = $request->get_params();

			$filter['limit']    = isset( $data['limit'] ) ? absint( $data['limit'] ) : 10;
			$filter['page']     = isset( $data['page'] ) ? absint( $data['page'] ) : 1;			
			$filter['order_by'] = $data['order_by'] ?? 'id';
			$filter['sort']     = $data['sort'] ?? 'desc';
			$filter['search']   = $data['search'] ?? null;		



			$bookmarksData = \CBXWPBookmarkHelper::bookmarkListing( $filter );

			if(isset($bookmarksData['error'])){
				$resData = [
					'success' => false,
					'info'    => $bookmarksData['error']
				];
			}else{
				$resData = [
					'success' => true,
					'data'    => $bookmarksData,
					'info'    => esc_html__( 'List of bookmark', 'cbxwpbookmark' )
				];			
			}
			$response->set_data( $resData );
		} catch ( Exception $e ) {
			$response->set_data( [
				'info'    => $e->getMessage(),
				'success' => false
			] );
		}

		return $response;
	} //end method index


	/**
	 * Delete bookmark
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function destroy( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$success_count = $fail_count = 0;

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized', 'cbxwpbookmark' ) );
			}

			$requestData = $request->get_params();
			if ( empty( $requestData['id'] ) ) {
				throw new Exception( esc_html__( 'Bookmark id is required.', 'cbxwpbookmark' ) );
			}

			$ids = explode( ',', $requestData['id'] );


			foreach ( $ids as $id ) {
				$bookmark       = Bookmark::query()->find( intval( $id ) );

				if ( $bookmark ) {
					if ( $bookmark->delete() ) {
						$success_count ++;
					} else {
						$fail_count ++;
					}
				}
			}


			$success_msg = $fail_msg = '';
			if ( $success_count > 0 ) {
				/* translators: %d: bookmark successfully deleted count */
				$success_msg = sprintf( esc_html__( '%d bookmark(s) deleted successfully. ', 'cbxwpbookmark' ), $success_count );

			}

			if ( $fail_count > 0 ) {
				/* translators: %d: bookmark delete fail count */
				$fail_msg = sprintf( esc_html__( '%d bookmark(s) can`t be deleted as they may have dependency.', 'cbxwpbookmark' ),
					$fail_count );

			}

			$response->set_data( [
				'success' => true,
				'info'    => $success_msg . $fail_msg
			] );

			return $response;

		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'err'     => $e->getMessage(),
				/* translators: 1: Delete success count 2. Delete fail count */
				'info'    => sprintf( esc_html__( 'Incomplete deletion. %1$d successfully and %$2d failed', 'cbxwpbookmark' ),
					$success_count, $fail_count ),
			] );

			return $response;
		}

	} //end method destroy
} //end class BookmarkController