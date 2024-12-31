(function ($) {
    'use strict';

    $(document).ready(function () {

        //select2
        $('.cbxwpbookmark-customize-control-dropdown-select2').each(function (index, element) {
            $(element).next('.cbxwpbookmark-customize-control-select2').select2({
                //$(element).select2({
                allowClear    : true,
                dropdownParent: $('#customize-theme-controls'),
            });
        });

        $('.cbxwpbookmark-customize-control-select2').on('change', function (event) {
            var select2Val = $(this).val();
            $(this).parent().find('.cbxwpbookmark-customize-control-dropdown-select2').val(select2Val).trigger('change');
        });

        //sortable
        /**
         * Pill Checkbox Custom Control
         *
         * @author Anthony Hortin <http://maddisondesigns.com>
         * @license http://www.gnu.org/licenses/gpl-2.0.html
         * @link https://github.com/maddisondesigns
         */
        $('.cbxwpbookmark_checkbox_control .sortable').sortable({
            placeholder: 'pill-ui-state-highlight',
            update     : function (event, ui) {
                cbxwpbookmarkGetAllPillCheckboxes($(this).parent());
            },
        });

        $('.cbxwpbookmark_checkbox_control .sortable-cbxwpbookmark-checkbox').on('change', function () {
            cbxwpbookmarkGetAllPillCheckboxes($(this).parent().parent().parent());
        });

        // Get the values from the checkboxes and add to our hidden field
        function cbxwpbookmarkGetAllPillCheckboxes($element) {
            var inputValues = $element.find('.sortable-cbxwpbookmark-checkbox').map(function () {
                if ($(this).is(':checked')) {
                    return $(this).val();
                }
            }).toArray();
            $element.find('.customize-control-sortable-cbxwpbookmark-checkbox').val(inputValues).trigger('change');
        }

    });//end dom ready
})(jQuery);
