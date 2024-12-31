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

    //icon url https://www.flaticon.com/free-icon/list_151917
    var iconEl = el('svg', {width: 24, height: 24},
        el('path', {
            fill: '#212120',
            d: 'M23,23H8.1C7.5,23,7,22.4,7,21.5C7,20.7,7.5,20,8.1,20H23c0.6,0,1.1,0.7,1.1,1.5C24,22.4,23.5,23,23,23z'
        }),
        el('path', {
            fill: '#212120',
            d: 'M23,13.5H8.1C7.5,13.5,7,12.8,7,12s0.5-1.5,1.1-1.5H23c0.6,0,1.1,0.7,1.1,1.5S23.5,13.5,23,13.5z'
        }),
        el('path', {
            fill: '#212120',
            d: 'M23,4H8.1C7.4,4,6.9,3.3,6.9,2.5S7.4,1,8.1,1H23c0.5,0,1,0.7,1,1.5S23.5,4,23,4z'
        }),
        el('circle', {fill: '#212120', cx: '1.9', cy: '2.6', r: '2'}),
        el('circle', {fill: '#212120', cx: '1.9', cy: '12', r: '2'}),
        el('circle', {fill: '#212120', cx: '1.9', cy: '21.4', r: '2'})
    );

    registerBlockType('codeboxr/cbxwpbookmark-post-block', {
        title   : cbxwpbookmark_post_block.block_title,
        icon    : iconEl,
        category: cbxwpbookmark_post_block.block_category,

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
                    block     : 'codeboxr/cbxwpbookmark-post-block',
                    attributes: props.attributes
                }),

                el(InspectorControls, {},
                    // 1st Panel – Form Settings
                    el(PanelBody, {title: cbxwpbookmark_post_block.general_settings.heading, initialOpen: true},
                        el(TextControl, {
                            label   : cbxwpbookmark_post_block.general_settings.title,
                            onChange: (value) => {
                                props.setAttributes({
                                    title: value
                                });
                            },
                            value   : props.attributes.title
                        }),
                        el('p', {'className': 'cbxwpbookmark_block_note'}, cbxwpbookmark_post_block.general_settings.title_desc),
                        el(SelectControl, {
                            label   : cbxwpbookmark_post_block.general_settings.order,
                            options : cbxwpbookmark_post_block.general_settings.order_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    order: value,
                                });
                            },
                            value   : props.attributes.order
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_post_block.general_settings.orderby,
                            options : cbxwpbookmark_post_block.general_settings.orderby_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    orderby: value
                                });
                            },
                            value   : props.attributes.orderby
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_post_block.general_settings.type,
                            options : cbxwpbookmark_post_block.general_settings.type_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    type: value
                                });
                            },
                            multiple: true,
                            value   : props.attributes.type
                        }),
                        el(TextControl, {
                            label   : cbxwpbookmark_post_block.general_settings.catid,
                            onChange: (value) => {
                                props.setAttributes({
                                    catid: value
                                });
                            },
                            value   : props.attributes.catid
                        }),
                        el('p', {'className': 'cbxwpbookmark_block_note'}, cbxwpbookmark_post_block.general_settings.catid_note),
                        el(TextControl, {
                            label   : cbxwpbookmark_post_block.general_settings.limit,
                            onChange: (value) => {
                                props.setAttributes({
                                    limit: value
                                });
                            },
                            value   : props.attributes.limit
                        }),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_post_block.general_settings.loadmore,
                                onChange: (value) => {
                                    props.setAttributes({loadmore: value});
                                },
                                checked : props.attributes.loadmore
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_post_block.general_settings.cattitle,
                                onChange: (value) => {
                                    props.setAttributes({cattitle: value});
                                },
                                checked : props.attributes.cattitle
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_post_block.general_settings.catcount,
                                onChange: (value) => {
                                    props.setAttributes({catcount: value});
                                },
                                checked : props.attributes.catcount
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_post_block.general_settings.allowdelete,
                                onChange: (value) => {
                                    props.setAttributes({allowdelete: value});
                                },
                                checked : props.attributes.allowdelete
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_post_block.general_settings.allowdeleteall,
                                onChange: (value) => {
                                    props.setAttributes({allowdeleteall: value});
                                },
                                checked : props.attributes.allowdeleteall
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_post_block.general_settings.showshareurl,
                                onChange: (value) => {
                                    props.setAttributes({showshareurl: value});
                                },
                                checked : props.attributes.showshareurl
                            }
                        ),
                        el(TextControl, {
                            label   : cbxwpbookmark_post_block.general_settings.base_url,
                            onChange: (value) => {
                                props.setAttributes({
                                    base_url: value
                                });
                            },
                            value   : props.attributes.base_url
                        })
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