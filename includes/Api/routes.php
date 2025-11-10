<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use CBXWPBookmark\Api\CbxRoute;
use CBXWPBookmark\Controllers\BookmarkController;
use CBXWPBookmark\Controllers\BookmarkFrontController;
use CBXWPBookmark\Controllers\CategoryController;
use CBXWPBookmark\Controllers\CategoryFrontController;
use CBXWPBookmark\Controllers\AdminDashboardController;
use CBXWPBookmark\Controllers\FrontDashboardController;

//routes admin
CbxRoute::middleware( 'cbxwpbookmark_log_manage' )->get( 'v1/admin/get-bookmarks', [ BookmarkController::class, 'index' ] );
CbxRoute::middleware( 'cbxwpbookmark_log_delete' )->get( 'v1/admin/delete-bookmark', [ BookmarkController::class, 'destroy' ] );

//front bookmark
CbxRoute::get( 'v1/get-bookmarks', [ BookmarkFrontController::class, 'index' ] );
CbxRoute::get( 'v1/get-bookmark', [ BookmarkFrontController::class, 'getBookmark' ] );
CbxRoute::get( 'v1/get-bookmark', [ BookmarkFrontController::class, 'getBookmark' ] );
CbxRoute::post( 'v1/save-bookmark-category', [ BookmarkFrontController::class, 'saveCategory' ] );
CbxRoute::post( 'v1/save-bookmark-order', [ BookmarkFrontController::class, 'saveOrder' ] );
CbxRoute::get( 'v1/delete-bookmark', [ BookmarkFrontController::class, 'destroy' ] );

CbxRoute::middleware( 'cbxwpbookmark_log_manage' )->get( 'v1/admin/dashboard-overview', [ AdminDashboardController::class, 'getGlobalOverviewData' ] );

CbxRoute::get( 'v1/dashboard-overview', [ FrontDashboardController::class, 'getGlobalOverviewData' ] );

CbxRoute::middleware( 'manage_options' )->post( 'v1/admin/reset-option', [ AdminDashboardController::class, 'pluginOptionsReset' ] );
CbxRoute::middleware( 'manage_options' )->post( 'v1/admin/migrate-table', [ AdminDashboardController::class, 'runMigration' ] );

CbxRoute::middleware( 'cbxwpbookmark_category_manage' )->get( 'v1/admin/get-categories', [ CategoryController::class, 'index' ] );
CbxRoute::middleware( 'cbxwpbookmark_category_manage' )->get( 'v1/admin/get-category', [ CategoryController::class, 'getCategory' ] );
CbxRoute::middleware( 'cbxwpbookmark_category_edit' )->post( 'v1/admin/save-category', [ CategoryController::class, 'save' ] );
CbxRoute::middleware( 'cbxwpbookmark_category_create' )->post( 'v1/admin/store-category', [ CategoryController::class, 'save' ] );
CbxRoute::middleware( 'cbxwpbookmark_category_delete' )->get( 'v1/admin/delete-category', [ CategoryController::class, 'deleteCategory' ] );

//front category
CbxRoute::get( 'v1/get-categories-list', [ CategoryFrontController::class, 'list' ] );
CbxRoute::get( 'v1/get-categories', [ CategoryFrontController::class, 'index' ] );
CbxRoute::get( 'v1/get-category', [ CategoryFrontController::class, 'getCategory' ] );
CbxRoute::post( 'v1/save-category', [ CategoryFrontController::class, 'save' ] );
CbxRoute::post( 'v1/store-category', [ CategoryFrontController::class, 'save' ] );
CbxRoute::get( 'v1/delete-category', [ CategoryFrontController::class, 'deleteCategory' ] );