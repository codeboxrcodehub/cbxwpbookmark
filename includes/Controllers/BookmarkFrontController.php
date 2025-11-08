<?php
namespace Cbx\Bookmark\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Cbx\Bookmark\Models\Bookmark;
use Rakit\Validation\Validator;
use Exception;
use WP_REST_Request;
use WP_REST_Response;

class BookmarkFrontController {

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
			$user_id = get_current_user_id();

			$filter['limit']    = isset( $data['limit'] ) ? absint( $data['limit'] ) : 10;
			$filter['page']     = isset( $data['page'] ) ? absint( $data['page'] ) : 1;			
			$filter['order_by'] = $data['order_by'] ?? 'id';
			$filter['sort']     = $data['sort'] ?? 'desc';
			$filter['search']   = $data['search'] ?? null;	
			$filter['user_id']  = $user_id;	
			$filter['cat_id']   = $data['cat_id'] ?? null;	
			$filter['type']     = $data['type'] ?? null;	

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

			$user_id = get_current_user_id();

			$data = $request->get_params();
			if ( empty( $data['id'] ) ) {
				throw new Exception( esc_html__( 'Bookmark id is required.', 'cbxwpbookmark' ) );
			}

			$ids = explode( ',', $data['id'] );

			foreach ( $ids as $id ) {
				$bookmark       = Bookmark::where( 'id', $id )->where('user_id', $user_id );

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

	/**
	 * Get single Bookmark
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function getBookmark( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized request', 'cbxwpbookmark' ) );
			}

			$user_id = get_current_user_id();
			$data = $request->get_params();

			if ( empty( $data['id'] ) ) {
				throw new Exception( esc_html__( 'Bookmark id required. Bookmark not found!', 'cbxwpbookmark' ) );
			}

			$bookmark = Bookmark::where( 'id', $data['id'] )->where('user_id', $user_id )->first();

			if ( ! $bookmark ) {
				throw new Exception( esc_html__( 'Bookmark not found!', 'cbxwpbookmark' ) );
			}


			$response->set_data( [
				'success'   => true,
				'info'      => esc_html__( 'Bookmark information', 'cbxwpbookmark' ),
				'data'      => $bookmark
			] );
			
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );
		}

		return $response;
	} //end method getBookmark

	/**
	 * save Category
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 */
	public function saveCategory( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$data = $request->get_params();
		
		try {

			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized request', 'cbxwpbookmark' ) );
			}

			$login_user_id = intval( get_current_user_id() );

			$validator = new Validator;

			$validation = $validator->validate( $data, [
				'cat_id'   => 'required'
			] );

			if ( $validation->fails() ) {
				$errors = $validation->errors();
				$response->set_data( [
					'success' => false,
					'errors'  => $errors->firstOfAll(),
					'info'    => esc_html__( 'Server error', 'cbxwpbookmark' ),
				] );

				return $response;
			}

			$postData = [
				'cat_id'            => isset( $data['cat_id'] ) ? sanitize_text_field( $data['cat_id'] ) : ''	
			];

			$postData['modyfied_date']    = gmdate( 'Y-m-d H:i:s' );

			$cat = Bookmark::where( 'id', $data['id'] )->where('user_id', $login_user_id )->first();

			if(!$cat){
				$response->set_data( [
					'success' => false,
					'info'    => esc_html__( 'Bookmark not found', 'cbxwpbookmark' )
				] );

				return $response;
			}

			Bookmark::query()->where( 'id', absint( $data['id'] ) )->update( $postData );
		
			$response->set_data( [
				'success' => true,
				'info'    => esc_html__( 'Category Updated successfully', 'cbxwpbookmark' )
			] );

			return $response;
		

		} catch ( Exception $e ) {
			$return = [
				'success' => false,
				'err'     => $e->getMessage(),
				'info'    => esc_html__( 'Server Error', 'cbxwpbookmark' )
			];
			$response->set_data( $return );

			return $response;
		}
	} //end of method saveCategory

	/**
	 * save Order
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 */
	public function saveOrder( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$data = $request->get_params();
		
		try {

			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized request', 'cbxwpbookmark' ) );
			}

			$login_user_id = intval( get_current_user_id() );

			$validator = new Validator;

			$validation = $validator->validate( $data, [
				'sort_order'   => 'required|numeric|min:0'
			] );

			if ( $validation->fails() ) {
				$errors = $validation->errors();
				$response->set_data( [
					'success' => false,
					'errors'  => $errors->firstOfAll(),
					'info'    => esc_html__( 'Server error', 'cbxwpbookmark' ),
				] );

				return $response;
			}

			$postData = [
				'sort_order'            => isset( $data['sort_order'] ) ? intval( $data['sort_order'] ) : ''	
			];

			$postData['modyfied_date']    = gmdate( 'Y-m-d H:i:s' );

			$cat = Bookmark::where( 'id', $data['id'] )->where('user_id', $login_user_id )->first();

			if(!$cat){
				$response->set_data( [
					'success' => false,
					'info'    => esc_html__( 'Bookmark not found', 'cbxwpbookmark' )
				] );

				return $response;
			}

			Bookmark::query()->where( 'id', absint( $data['id'] ) )->update( $postData );
		
			$response->set_data( [
				'success' => true,
				'info'    => esc_html__( 'Bookmark Order Updated successfully', 'cbxwpbookmark' )
			] );

			return $response;
		

		} catch ( Exception $e ) {
			$return = [
				'success' => false,
				'err'     => $e->getMessage(),
				'info'    => esc_html__( 'Server Error', 'cbxwpbookmark' )
			];
			$response->set_data( $return );

			return $response;
		}
	} //end of method saveOrder
} //end class BookmarkFrontController