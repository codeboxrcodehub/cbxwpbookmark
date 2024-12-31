'use strict';

(function (blocks, element, components, editor, ServerSideRender, blockEditor) {
    var el                = element.createElement,
        registerBlockType = blocks.registerBlockType,

        //InspectorControls = editor.InspectorControls,
        InspectorControls = blockEditor.InspectorControls,
        //ServerSideRender  = components.ServerSideRender,

        RangeControl      = components.RangeControl,
        Panel             = components.Panel,
        PanelBody         = components.PanelBody,
        PanelRow          = components.PanelRow,
        TextControl       = components.TextControl,
        //NumberControl = components.NumberControl,
        TextareaControl   = components.TextareaControl,
        CheckboxControl   = components.CheckboxControl,
        RadioControl      = components.RadioControl,
        SelectControl     = components.SelectControl,
        ToggleControl     = components.ToggleControl,
        //ColorPicker = components.ColorPalette,
        //ColorPicker = components.ColorPicker,
        //ColorPicker = components.ColorIndicator,
        PanelColorPicker  = editor.PanelColorSettings,
        DateTimePicker    = components.DateTimePicker,
        HorizontalRule    = components.HorizontalRule,
        ExternalLink      = components.ExternalLink;

    var MediaUpload = wp.editor.MediaUpload;

    var iconEl = el('svg', {width: 24, height: 24},
        el('path', {
            fill: '#212120',
            d: 'M2.5,2.5c0-1.3,1.1-2.4,2.4-2.4h14.2c1.3,0,2.4,1.1,2.4,2.4v21.3L12,19.1l-9.5,4.7V2.5z M4.9,2.5v17.8l7.1-3.6\n' +
                '\tl7.1,3.6V2.5H4.9z M10.8,8.4V6.1h2.4v2.4h2.4v2.4h-2.4v2.4h-2.4v-2.4H8.4V8.4H10.8z'
        })
    );

    registerBlockType('codeboxr/cbxwpbookmark-btn-block', {
        title   : cbxwpbookmark_btn_block.block_title,
        icon    : iconEl,
        category: cbxwpbookmark_btn_block.block_category,

        /*
         * In most other blocks, you'd see an 'attributes' property being defined here.
         * We've defined attributes in the PHP, that information is automatically sent
         * to the block editor, so we don't need to redefine it here.
         */
        edit: function (props) {

            return [
                /*
                 * The ServerSideRender element uses the REST API to automatically call
                 * php_block_render() in your PHP code whenever it needs to get an updated
                 * view of the block.
                 */
                el(ServerSideRender, {
                    block     : 'codeboxr/cbxwpbookmark-btn-block',
                    attributes: props.attributes
                }),

                el(InspectorControls, {},
                    // 1st Panel – Form Settings
                    el(PanelBody, {title: cbxwpbookmark_btn_block.general_settings.title, initialOpen: true},
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_btn_block.general_settings.show_count,
                                onChange: (value) => {
                                    props.setAttributes({show_count: value});
                                },
                                checked : props.attributes.show_count,
                            }
                        )
                    )
                )

            ];
        },
        // We're going to be rendering in PHP, so save() can just return null.
        save: function () {
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.editor,
    window.wp.serverSideRender,
    window.wp.blockEditor
));