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



    //icon url https://www.iconfinder.com/icons/4634467/categories_category_interface_link_icon
    var iconEl = el('svg', {width: 24, height: 24},
        el('path', {
            fill: '#212120',
            d: 'M8.3,15.7H2.5c-0.4,0-0.7-0.3-0.7-0.7V9.1c0-0.4,0.3-0.7,0.7-0.7h5.9c0.4,0,0.7,0.3,0.7,0.7V15\n' +
                '\t\tC9.1,15.4,8.7,15.7,8.3,15.7z M3.2,14.2h4.4V9.8H3.2V14.2z'
        }),
        el('path', {
            fill: '#212120',
            d: 'M21.6,20.9h-5.9c-0.4,0-0.7-0.3-0.7-0.7v-5.9c0-0.4,0.3-0.7,0.7-0.7h5.9c0.4,0,0.7,0.3,0.7,0.7v5.9\n' +
                '\t\tC22.3,20.5,22,20.9,21.6,20.9z M16.4,19.4h4.4V15h-4.4V19.4z'
        }),
        el('path', {
            fill: '#212120',
            d: 'M21.6,9.1h-5.9c-0.4,0-0.7-0.3-0.7-0.7V2.5c0-0.4,0.3-0.7,0.7-0.7h5.9c0.4,0,0.7,0.3,0.7,0.7v5.9\n' +
                '\t\tC22.3,8.8,22,9.1,21.6,9.1z M16.4,7.6h4.4V3.2h-4.4V7.6z'
        }),
        el('path', {
            fill: '#212120', d: 'M12,23.8c-0.4,0-0.7-0.3-0.7-0.7V1c0-0.4,0.3-0.7,0.7-0.7s0.7,0.3,0.7,0.7v22.1\n' +
                '\t\tC12.8,23.5,12.4,23.8,12,23.8z'
        }),
        el('path', {
            fill: '#212120',
            d: 'M12,12.8H8.3c-0.4,0-0.7-0.3-0.7-0.7s0.3-0.7,0.7-0.7H12c0.4,0,0.7,0.3,0.7,0.7S12.4,12.8,12,12.8\n' +
                '\t\tz'
        }),
        el('path', {
            fill: '#212120',
            d: 'M15.7,6.2H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h3.7c0.4,0,0.7,0.3,0.7,0.7\n' +
                '\t\tC16.4,5.8,16.1,6.2,15.7,6.2z'
        }),
        el('path', {
            fill: '#212120',
            d: 'M15.7,17.9H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h3.7c0.4,0,0.7,0.3,0.7,0.7\n' +
                '\t\tS16.1,17.9,15.7,17.9z'
        })
    );

    registerBlockType('codeboxr/cbxwpbookmark-mycat-block', {
        title   : cbxwpbookmark_mycat_block.block_title,
        icon    : iconEl,
        category: cbxwpbookmark_mycat_block.block_category,

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
                    block     : 'codeboxr/cbxwpbookmark-mycat-block',
                    attributes: props.attributes
                }),

                el(InspectorControls, {},
                    // 1st Panel – Form Settings
                    el(PanelBody, {title: cbxwpbookmark_mycat_block.general_settings.heading, initialOpen: true},
                        el(TextControl, {
                            label   : cbxwpbookmark_mycat_block.general_settings.title,
                            onChange: (value) => {
                                props.setAttributes({
                                    title: value
                                });
                            },
                            value   : props.attributes.title
                        }),
                        el('p', {'className': 'cbxwpbookmark_block_note'}, cbxwpbookmark_mycat_block.general_settings.title_desc),
                        el(SelectControl, {
                            label   : cbxwpbookmark_mycat_block.general_settings.order,
                            options : cbxwpbookmark_mycat_block.general_settings.order_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    order: value
                                });
                            },
                            value   : props.attributes.order
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_mycat_block.general_settings.orderby,
                            options : cbxwpbookmark_mycat_block.general_settings.orderby_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    orderby: value
                                });
                            },
                            value   : props.attributes.orderby
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_mycat_block.general_settings.display,
                            options : cbxwpbookmark_mycat_block.general_settings.display_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    display: value
                                });
                            },
                            value   : props.attributes.display
                        }),
                        el(SelectControl, {
                            label   : cbxwpbookmark_mycat_block.general_settings.privacy,
                            options : cbxwpbookmark_mycat_block.general_settings.privacy_options,
                            onChange: (value) => {
                                props.setAttributes({
                                    privacy: value
                                });
                            },
                            value   : props.attributes.privacy
                        }),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_mycat_block.general_settings.show_count,
                                onChange: (value) => {
                                    props.setAttributes({show_count: value});
                                },
                                checked : props.attributes.show_count
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_mycat_block.general_settings.allowedit,
                                onChange: (value) => {
                                    props.setAttributes({allowedit: value});
                                },
                                checked : props.attributes.allowedit
                            }
                        ),
                        el(ToggleControl,
                            {
                                label   : cbxwpbookmark_mycat_block.general_settings.show_bookmarks,
                                onChange: (value) => {
                                    props.setAttributes({show_bookmarks: value});
                                },
                                checked : props.attributes.show_bookmarks
                            }
                        ),
                        el(TextControl, {
                            label   : cbxwpbookmark_mycat_block.general_settings.base_url,
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