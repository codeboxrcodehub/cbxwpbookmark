<?php
namespace Cbx\Bookmark\Controllers;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//use Cbx\Bookmark\Helpers\CBXWPBookmarkHelper;
use Cbx\Bookmark\Models\Category;
use Exception;
use Rakit\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class CategoryController {

	/**
	 * Get all categories
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

			$categoriesData = \CBXWPBookmarkHelper::categoryListing( $filter );

			if(isset($categoriesData['error'])){
				$resData = [
					'success' => false,
					'info'    => $categoriesData['error']
				];
			}else{
				$resData = [
					'success' => true,
					'data'    => $categoriesData,
					'info'    => esc_html__( 'List of category', 'cbxwpbookmark' )
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
	 * Get single Category
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function getCategory( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized request', 'cbxwpbookmark' ) );
			}

			$requestData = $request->get_params();
			if ( empty( $requestData['id'] ) ) {
				throw new Exception( esc_html__( 'Category id required. Category not found!', 'cbxwpbookmark' ) );
			}


			$category = Category::with('user')->find( $requestData['id'] );

			if ( ! $category ) {
				throw new Exception( esc_html__( 'Category not found!', 'cbxwpbookmark' ) );
			}


			$response->set_data( [
				'success'   => true,
				'info'      => esc_html__( 'Category information', 'cbxwpbookmark' ),
				'data'      => $category
			] );
			
		} catch ( Exception $e ) {
			$response->set_data( [
				'success' => false,
				'info'    => $e->getMessage(),
			] );
		}

		return $response;
	} //end method getCategory


	/**
	 * Delete Category
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 * @since 1.0.0
	 */
	public function deleteCategory( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$success_count = $fail_count = 0;

		try {
			if ( ! is_user_logged_in() ) {
				throw new Exception( esc_html__( 'Unauthorized', 'cbxwpbookmark' ) );
			}

			$requestData = $request->get_params();
			if ( empty( $requestData['id'] ) ) {
				throw new Exception( esc_html__( 'Category id is required.', 'cbxwpbookmark' ) );
			}

			$ids = explode( ',', $requestData['id'] );


			foreach ( $ids as $id ) {
				$category       = Category::query()->find( intval( $id ) );

				if ( $category ) {
					if ( $category->delete() ) {
						$success_count ++;
					} else {
						$fail_count ++;
					}
				}
			}


			$success_msg = $fail_msg = '';
			if ( $success_count > 0 ) {
				/* translators: %d: Category successfully deleted count */
				$success_msg = sprintf( esc_html__( '%d Category(s) deleted successfully. ', 'cbxwpbookmark' ), $success_count );

			}

			if ( $fail_count > 0 ) {
				/* translators: %d: Category delete fail count */
				$fail_msg = sprintf( esc_html__( '%d Category(s) can`t be deleted as they may have dependency.', 'cbxwpbookmark' ),
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

	} //end method deleteCategory

	/**
	 * save Category
	 *
	 * @param  WP_REST_Request  $request
	 *
	 * @return WP_REST_Response
	 */
	public function save( WP_REST_Request $request ) {
		$response = new WP_REST_Response();
		$response->set_status( 200 );

		$data = $request->get_params();
		$login_user_id = intval( get_current_user_id() );
		try {

			$validator = new Validator;

			$validation = $validator->validate( $data, [
				'cat_name'   => 'required'
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
				'cat_name'            => isset( $data['cat_name'] ) ? sanitize_text_field( $data['cat_name'] ) : '',		
				'privacy'         	  => isset( $data['privacy'] ) ? absint( $data['privacy'] ) : 1,			
			];

			if ( isset( $data['id'] ) ) {	
				$postData['modyfied_date']    = gmdate( 'Y-m-d H:i:s' );
			} else {
				$postData['user_id']     = absint( $login_user_id );
				$postData['created_date']   = gmdate( 'Y-m-d H:i:s' );
			}


			$postData = apply_filters( 'cbxwpbookmark_category_posted_data', $postData );

			if ( isset( $data['id'] ) ) {		
				
				do_action( 'cbxwpbookmark_before_category_update', $postData );

				Category::query()->where( 'id', absint( $data['id'] ) )->update( $postData );
				$savedCategory    = Category::find($data['id'] );

				do_action( 'cbxbookmark_category_edit', $data['id'], $savedCategory->user_id, $savedCategory->cat_name );
			} else {
				do_action( 'cbxwpbookmark_before_category_create', $postData );

				$savedCategory = Category::query()->create( $postData );

				do_action( 'cbxbookmark_category_added', $savedCategory->id, $savedCategory->user_id, $savedCategory->cat_name );
			}

			if ( $savedCategory ) {
				
				if ( ! isset( $data['id'] ) ) {
					do_action( 'cbxwpbookmark_category_created', $savedCategory, 'backend' );

					$return_msg = esc_html__( 'Category created successfully', 'cbxwpbookmark' );
				} else {
					$return_msg = esc_html__( 'Category updated successfully', 'cbxwpbookmark' );
				}

				$response->set_data( [
					'success' => true,
					'data'    => $savedCategory,
					'info'    => $return_msg
				] );

				return $response;
			} else {
				if ( isset( $data['id'] ) ) {
					$return_msg = esc_html__( 'Category update failed', 'cbxwpbookmark' );
				} else {
					$return_msg = esc_html__( 'Category creation failed', 'cbxwpbookmark' );
				}

				$return = [
					'success' => false,
					'info'    => $return_msg
				];
				$response->set_data( $return );

				return $response;
			}

		} catch ( Exception $e ) {
			$return = [
				'success' => false,
				'err'     => $e->getMessage(),
				'info'    => esc_html__( 'Server Error', 'cbxwpbookmark' )
			];
			$response->set_data( $return );

			return $response;
		}
	} //end of method save
} //end class CategoryController