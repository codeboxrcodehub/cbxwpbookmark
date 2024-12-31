(function ($) {
    'use strict';

    $(document).ready(function () {
        if($('#cbxwpbookmark_logs').length){
            $('#cbxwpbookmark_logs').find('table').removeClass('wp-list-table widefat fixed striped table-view-list').addClass('table table-bordered table-hover table-striped');
        }

        if($('#cbxwpbookmark_cats').length){
            $('#cbxwpbookmark_cats').find('table').removeClass('wp-list-table widefat fixed striped table-view-list').addClass('table table-bordered table-hover table-striped');
        }
    });//end dom ready
})(jQuery);