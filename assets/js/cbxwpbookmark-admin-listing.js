(function ($) {
    'use strict';

    $(document).ready(function () {
        if($('#cbxwpbookmark_logs').length){
            $('#cbxwpbookmark_logs').find('table').removeClass('table-view-list').addClass('table table-bordered table-hover table-striped');
            $('.button.action').addClass('primary');

            $('#screen-meta').addClass('cbx-chota cbxwpbookmark-page-wrapper cbxwpbookmark-petitions-wrapper');
            $('#screen-options-apply').addClass('primary');
        }

        if($('#cbxwpbookmark_cats').length){
            //$('#cbxwpbookmark_cats').find('table').removeClass('wp-list-table widefat fixed striped table-view-list').addClass('table table-bordered table-hover table-striped');
            $('#cbxwpbookmark_cats').find('table').removeClass('table-view-list').addClass('table table-bordered table-hover table-striped');
            $('.button.action').addClass('primary');

            $('#screen-meta').addClass('cbx-chota cbxwpbookmark-page-wrapper cbxwpbookmark-petitions-wrapper');
            $('#screen-options-apply').addClass('primary');
        }
    });//end dom ready
})(jQuery);