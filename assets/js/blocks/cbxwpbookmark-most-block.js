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

    //var MediaUpload = wp.editor.MediaUpload;

    //icon custom created, see stored svg
    /*var iconEl = el('svg', {width: 24, height: 24},
        el('path', {d:"M15.8839 3.15789L1.09202 3.15789C0.496373 3.15789 0 2.42105 0 1.57895C0 0.736842 0.496373 0 1.09202 0L15.8839 0C16.4796 0 16.976 0.736842 16.976 1.57895C16.976 2.42105 16.4796 3.15789 15.8839 3.15789L15.8839 3.15789Z", transform:"translate(6.949203 0.421051)", id:"Shape", fill:"#000000", stroke:"none"}),
        el('path', {d:"M15.8839 3.15789L1.09202 3.15789C0.496373 3.15789 0 2.42105 0 1.57895C0 0.736842 0.496373 0 1.09202 0L15.8839 0C16.4796 0 16.9759 0.736842 16.9759 1.57895C16.9759 2.42105 16.4796 3.15789 15.8839 3.15789L15.8839 3.15789Z", transform:"translate(7.024048 11.99994)", id:"Shape-2", fill:"#000000", stroke:"none"}),
        el('path', {d:"M16.7717 2.99071L1.15305 2.99071C0.524111 2.99069 -1.90735e-06 2.29286 0 1.49533C3.8147e-06 0.697816 0.524126 -1.14441e-05 1.15307 0L16.7717 1.90735e-06C17.4007 1.90735e-06 17.9248 0.697834 17.9248 1.49536C17.9248 2.29287 17.4007 2.99071 16.7717 2.99071L16.7717 2.99071Z", transform:"matrix(6.124291E-08 1.000001 -1.000001 6.124291E-08 16.52359 5.916611)", id:"Shape-3", fill:"#000000" , stroke:"none"}),
        el('path', {d:"M0 2.10526C0 0.942558 0.888934 0 1.98549 0C3.08205 0 3.97098 0.942558 3.97098 2.10526C3.97098 3.26797 3.08205 4.21052 1.98549 4.21052C0.888934 4.21052 0 3.26797 0 2.10526Z", id:"Circle", fill:"#000000", stroke:"none"}),
        el('path', {d:"M0 2.10526C0 0.942558 0.888934 0 1.98549 0C3.08205 0 3.97098 0.942558 3.97098 2.10526C3.97098 3.26797 3.08205 4.21053 1.98549 4.21053C0.888934 4.21053 0 3.26797 0 2.10526Z", transform:"translate(0 9.89473)", id:"Circle", fill:"#000000", stroke:"none"}),
        el('path', {d:"M0 2.10526C0 0.942558 0.888934 0 1.98549 0C3.08205 0 3.97098 0.942558 3.97098 2.10526C3.97098 3.26797 3.08205 4.21053 1.98549 4.21053C0.888934 4.21053 0 3.26797 0 2.10526Z", transform:"translate(0 19.78947)", id:"Circle", fill:"#000000", stroke:"none"}),
    ); */

    var iconEl = el('svg', {width: 24, height: 24},
        el('path', {
            fill: "#212120", d: "M22.9,3.2H8.1C7.5,3.2,7,2.4,7,1.6C7,0.7,7.5,0,8.1,0h14.8C23.5,0,24,0.7,24,1.6\n" +
                "\t\tC24,2.4,23.5,3.2,22.9,3.2L22.9,3.2z"
        }),
        el('path', {
            fill: "#212120",
            d: "M22.9,16.7H8.1C7.5,16.7,7,16,7,15.1c0-0.8,0.5-1.6,1.1-1.6h14.8c0.6,0,1.1,0.7,1.1,1.6\n" +
                "\t\t\tC24,15.9,23.5,16.7,22.9,16.7L22.9,16.7z"
        }),
        el('path', {
            fill: "#212120", d: "M14,22.8V7.2C14,6.6,14.7,6,15.5,6C16.3,6,17,6.5,17,7.2v15.6c0,0.6-0.7,1.2-1.5,1.2\n" +
                "\t\t\tC14.7,24,14,23.5,14,22.8L14,22.8z"
        }),

        el('path', {fill: "#212120", d: "M0,2.1C0,0.9,0.9,0,2,0s2,0.9,2,2.1S3.1,4.2,2,4.2S0,3.3,0,2.1z"}),
        el('path', {fill: "#212120", d: "M0,12c0-1.2,0.9-2.1,2-2.1s2,0.9,2,2.1s-0.9,2.1-2,2.1S0,13.2,0,12z"}),
        el('path', {fill: "#212120", d: "M0,21.9c0-1.2,0.9-2.1,2-2.1s2,0.9,2,2.1S3.1,24,2,24S0,23.1,0,21.9z"}),
    );

    registerBlockType('codeboxr/cbxwpbookmark-most-block', {
        title   : cbxwpbookmark_most_block.block_title,
        icon    : iconEl,
        category: cbxwpbookmark_most_block.block_category,

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
                    block     : 'codeboxr/cbxwpbookmark-most-block',
                    attributes: props.attributes
                }),

                el(InspectorControls, {},
                    // 1st Panel – Form Settings
                    el(PanelBody, {title: cbxwpbookmark_most_block.general_settings.heading, initialOpen: true},
                        el(TextControl, {
                            label   : cbxwpbookmark_most_block.general_settings.title,
                            onChange: (value) => {
                                props.setAttributes({
                                    title: value
                                });
                            },
                            value   : props.attributes.title
                        }),
                        el('p', {'className': 'cbxwpbookmark_block_note'}, cbxwpbookmark_most_block.general_settings.title_desc),
                        el(SelectControl, {
                            label   : cbxwpbookmark_most_block.general_settings.order,
                            options : cbxwpbookmark_most_block.general_settings.order_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    order: value
                                });
                            },
                            value   : props.attributes.order
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_most_block.general_settings.orderby,
                            options : cbxwpbookmark_most_block.general_settings.orderby_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    orderby: value
                                });
                            },
                            value   : props.attributes.orderby
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_most_block.general_settings.type,
                            options : cbxwpbookmark_most_block.general_settings.type_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    type: value
                                });
                            },
                            multiple: true,
                            value   : props.attributes.type
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_most_block.general_settings.daytime,
                            options : cbxwpbookmark_most_block.general_settings.daytime_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    daytime: value
                                });
                            },
                            value   : props.attributes.daytime
                        }),
                        el(TextControl, {
                            label   : cbxwpbookmark_most_block.general_settings.limit,
                            onChange: (value) => {
                                props.setAttributes({
                                    limit: value
                                });
                            },
                            value   : props.attributes.limit
                        }),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_most_block.general_settings.show_count,
                                onChange: (value) => {
                                    props.setAttributes({show_count: value});
                                },
                                checked : props.attributes.show_count
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_most_block.general_settings.show_thumb,
                                onChange: (value) => {
                                    props.setAttributes({show_thumb: value});
                                },
                                checked : props.attributes.show_thumb
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