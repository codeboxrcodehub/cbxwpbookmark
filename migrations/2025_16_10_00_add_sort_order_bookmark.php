<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Illuminate\Database\Capsule\Manager as Capsule;

if (!class_exists("CBXWPBookmarkAddSortOrder")) {
    /**
     * Class CBXWPBookmarkAddSortOrder
     * @since 2.0.0
     */
    class CBXWPBookmarkAddSortOrder
    {
        /**
         * Migration run
         */
        public static function up()
        {
            $log_table = 'cbxwpbookmark';

            try {
                if (Capsule::schema()->hasTable($log_table)) {
                    Capsule::schema()->table($log_table, function ($table) {
                        $table->integer( 'sort_order' )->default( 0 );
                    });
                }
            } catch (\Exception $e) {
                if (function_exists('write_log')) {
                    write_log($e->getMessage());
                }
            }
        }//end method up

        /**
         * Drop migrations
         */
        public static function down()
        {
            try {
                if (Capsule::schema()->hasTable('cbxwpbookmark')) {
                    Capsule::schema()->table('cbxwpbookmark', function ($table) {
                        $table->dropColumn('sort_order');
                    });
                }
            } catch (\Exception $e) {
                if (function_exists('write_log')) {
                    write_log($e->getMessage());
                }
            }
        }//end method down

    }//end class CBXWPBookmarkAddSortOrder
}


if (isset($action) && $action == 'up') {
    CBXWPBookmarkAddSortOrder::up();
} elseif (isset($action) && $action == "drop") {
    CBXWPBookmarkAddSortOrder::down();
}