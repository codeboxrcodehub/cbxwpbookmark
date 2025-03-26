(function ($) {
    'use strict';

    function cbxwpbookmark_copyStringToClipboard(str) {
        // Create new element
        var el   = document.createElement('textarea');
        // Set value (string to be copied)
        el.value = str;
        // Set non-editable to avoid focus and move outside of view
        el.setAttribute('readonly', '');
        el.style = {position: 'absolute', left: '-9999px'};
        document.body.appendChild(el);
        // Select text inside element
        el.select();
        // Copy text to clipboard
        document.execCommand('copy');
        // Remove temporary element
        document.body.removeChild(el);
    }

    function cbxwpbookmark_stripslashes(str) {
        // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   improved by: Ates Goral (http://magnetiq.com)
        // +      fixed by: Mick@el
        // +   improved by: marrtins
        // +   bugfixed by: Onno Marsman
        // +   improved by: rezna
        // +   input by: Rick Waldron
        // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
        // +   input by: Brant Messenger (http://www.brantmessenger.com/)
        // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
        // *     example 1: stripslashes('Kevin\'s code');
        // *     returns 1: "Kevin's code"
        // *     example 2: stripslashes('Kevin\\\'s code');
        // *     returns 2: "Kevin\'s code"
        return (str + '').replace(/\\(.?)/g, function (s, n1) {
            switch (n1) {
                case '\\':
                    return '\\';
                case '0':
                    return '\u0000';
                case '':
                    return '';
                default:
                    return n1;
            }
        });
    }

    $(document).ready(function () {

        var ajaxurl     = cbxwpbookmark.ajaxurl;
        var awn_options = {
            labels: {
                tip: cbxwpbookmark.awn_options.tip,
                info: cbxwpbookmark.awn_options.info,
                success: cbxwpbookmark.awn_options.success,
                warning: cbxwpbookmark.awn_options.warning,
                alert: cbxwpbookmark.awn_options.alert,
                async: cbxwpbookmark.awn_options.async,
                confirm: cbxwpbookmark.awn_options.confirm,
                confirmOk: cbxwpbookmark.awn_options.confirmOk,
                confirmCancel: cbxwpbookmark.awn_options.confirmCancel
            }
        };

        //on click on +add  it will open the boomark panel
        $(document.body).on('click', '.cbxwpbkmarktrig', function (event) {
            event.stopPropagation();
            event.preventDefault();

            //var $this            = $(this);
            var $bookmark_btn = $(this);
            //var $bookmark_panel = $bookmark_btn.next('.cbxwpbkmarklistwrap');

            //console.log(cbxComputePosition);


            //console.log(computePosition);

            //window.FloatingUIDOM.computePosition($bookmark_btn[0], $bookmark_panel[0]).then(({x, y}) => {
            /*cbxComputePosition($bookmark_btn[0], $bookmark_panel[0]).then(({x, y}) => {
                console.log(x);
                console.log(y);

                Object.assign($bookmark_panel[0].style, {
                    left: x+'px',
                    top: y+'px'
                });
            });*/

            //var test = reposition($bookmark_btn[0], $bookmark_panel[0]);

            var $logged_in = Number($bookmark_btn.data('loggedin')); //user logged in or not

            //if guest return with warning
            if ($logged_in === 0) {

                CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_gust', $bookmark_btn, $bookmark_panel);

                var $bookmark_guest_panel = $bookmark_btn.next('.cbxwpbkmarkguestwrap');
                /*cbxReposition($bookmark_btn[0], $bookmark_guest_panel[0],{
                    position: 'bottom-middle'
                    //container: $bookmark_panel[0].getBoundingClientRect()
                });*/

                /* window.computePosition($bookmark_btn[0], $bookmark_guest_panel[0], {
                     placement: 'bottom',
                     strategy: 'fixed', //fixed | absolute
                     middleware: [window.autoPlacement()],
                 }).then(({x, y}) => {
                     Object.assign($bookmark_guest_panel[0].style, {
                         left: x+'px',
                         top: y+'px'
                     });
                 });*/

                $($bookmark_guest_panel).toggle('fast', function () {

                });

                //$bookmark_guest_panel.show();
                //$bookmark_guest_panel.toggle();

                return;
            }

            var $bookmark_panel = $bookmark_btn.next('.cbxwpbkmarklistwrap');

            CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_user', $bookmark_btn, $bookmark_panel);

            var $object_id   = $bookmark_btn.data('object_id'); //post id
            var $object_type = $bookmark_btn.data('type'); //post type

            //user category or global category mode
            if (cbxwpbookmark.bookmark_mode !== 'no_cat') {
                //if mode is user_cat or global_cat

                CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_cats_pre', $bookmark_btn, $bookmark_panel);

                var $cat_add_form  = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
                var $cat_edit_form = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
                var $cat_book_list = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
                var $cat_edit_list = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
                var $toolbar       = $bookmark_panel.find('.cbxwpbkmark-toolbar');

                $bookmark_panel.find('.cbxlbjs-searchbar').val('');
                $bookmark_panel.find('.cbxwpbkmark-field-cat').val('').removeClass('error');
                $bookmark_panel.find('.cbxwpbkmark-form-note').hide().text('').removeClass('cbxwpbkmark-form-note-success');

                $cat_add_form.hide();
                $cat_edit_form.hide();
                $cat_edit_list.hide();
                //$toolbar.find('.cbxwpbkmark-toolbar-listcat').hide();
                $toolbar.find('.cbxwpbkmark-toolbar-listcat').addClass('cbxwpbkmark-hide');

                $cat_book_list.show();
                //$toolbar.find('.cbxwpbkmark-toolbar-newcat').show();
                $toolbar.find('.cbxwpbkmark-toolbar-newcat').removeClass('cbxwpbkmark-hide');
                $bookmark_btn.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_default);

                /* cbxReposition($bookmark_btn[0], $bookmark_panel[0],{
                     position: 'bottom-middle'
                     //container: $bookmark_panel[0].getBoundingClientRect()
                 });*/

                /* window.computePosition($bookmark_btn[0], $bookmark_panel[0], {
                     placement: 'bottom',
                     strategy: 'absolute', //fixed | absolute
                     middleware: [window.autoPlacement()],
                 }).then(({x, y}) => {
                     Object.assign($bookmark_panel[0].style, {
                         left: x+'px',
                         top: y+'px'
                     });
                 });*/

                $($bookmark_panel).toggle('fast', function () {
                    //if current user can not create category
                    if (Number(cbxwpbookmark.user_can_create_cat) === 0) {
                        $cat_add_form.remove();
                        $toolbar.find('.cbxwpbkmark-toolbar-newcat').remove();
                    }

                    //show the ajax icon
                    $bookmark_panel.find('.cbxwpbkmarkloading').show();
                    //send ajax request to popular the categories as fresh

                    var data = {
                        'action': 'cbx_find_category',
                        'security': cbxwpbookmark.nonce,
                        'object_id': $object_id,
                        'object_type': $object_type
                        //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                    };

                    $.post(ajaxurl, data, function (response) {
                        response = $.parseJSON(response);

                        if (response && response.code === 1) {
                            //categories found
                            $bookmark_panel.find('.cbxwpbkmarkloading').hide();

                            var cats             = $.parseJSON(response.cats);
                            var $bookmark_count  = Number(response.bookmark_count);
                            var $bookmark_byuser = Number(response.bookmark_byuser);

                            if ($bookmark_byuser > 0) {
                                $bookmark_btn.addClass('cbxwpbkmarktrig-marked');
                            } else {
                                $bookmark_btn.removeClass('cbxwpbkmarktrig-marked');
                            }

                            //update bookmark count
                            $bookmark_btn.find('.cbxwpbkmarktrig-count').html($bookmark_count);

                            var $cat_options_add  = '';
                            var $cat_options_edit = '';

                            cbxwpbookmark.user_current_cat_count = cats.length;
                            cbxwpbookmark.user_current_cats      = cats;

                            $.each(cats, function (key, val) {
                                $cat_options_add += '<li class="cbxlbjs-item" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';
                                $cat_options_edit += '<li class="cbxlbjs-item" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';
                            });

                            $cat_book_list.find('.cbxwpbkmarklist').html($cat_options_add);
                            $cat_edit_list.find('.cbxwpbkmarklist').html($cat_options_edit);

                            if ((cbxwpbookmark.max_cat_limit > 0) && (cbxwpbookmark.user_current_cat_count >= cbxwpbookmark.max_cat_limit)) {
                                //$bookmark_panel.find('.cbxwpbkmark-toolbar-newcat').hide();
                                $bookmark_panel.find('.cbxwpbkmark-toolbar-newcat').addClass('cbxwpbkmark-hide');
                            }

                            CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_cats_loaded', $bookmark_btn, $bookmark_panel, cats);

                        } else {
                            //failed to get category for this user

                            $bookmark_panel.find('.cbxwpbkmarkloading').hide();
                            $cat_book_list.show();

                            //if no category found show message and put option to create one
                            $bookmark_panel.find('.cbxwpbkmarklist').hide();
                            if ($bookmark_panel.find('.cbxwpbkmark-lbjs').find('.cbxwpbkmarklist-nocatfound').length === 0) {
                                if (cbxwpbookmark.bookmark_mode === 'user_cat') {
                                    $bookmark_panel.find('.cbxwpbkmark-lbjs').append('<p class="cbxwpbkmarklist-nocatfound"><span data-clicked="0" class="cbxwpbkmarklist-nocatfoundtrig">' + response.msg + '</span></p>');
                                } else if (cbxwpbookmark.bookmark_mode === 'global_cat') {
                                    $bookmark_panel.find('.cbxwpbkmark-lbjs').append('<p class="cbxwpbkmarklist-nocatfound">' + response.msg + '</p>');
                                }

                            }

                        }

                    });
                });
            }
            else {
                CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_nocats_pre', $bookmark_btn, $bookmark_panel);

                //no category or single click mode
                var $busy = Number($bookmark_btn.data('busy'));
                if ($busy === 1) return;


                $bookmark_btn.data('busy', 1);
                //$bookmark_btn.addClass('cbxwpbkmarktrig-busy');
                $bookmark_btn.addClass('running');

                var $bookmark_label   = $bookmark_btn.data('bookmark-label');
                var $bookmarked_label = $bookmark_btn.data('bookmarked-label');
                var $show_count       = Number($bookmark_btn.data('show-count'));
                var $display_label    = Number($bookmark_btn.data('display-label'));

                CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_nocats_bk_click', $bookmark_btn, $bookmark_panel, $object_id, $object_type, 0);//last param category id

                var bookmarkData = {
                    'action': 'cbx_add_bookmark',
                    'security': cbxwpbookmark.nonce,
                    'cat_id': 0,
                    'object_id': $object_id,
                    'object_type': $object_type
                    //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                };

                $.post(ajaxurl, bookmarkData, function (response) {
                    response = $.parseJSON(response);

                    var $bookmark_count  = Number(response.bookmark_count);
                    var $bookmark_byuser = Number(response.bookmark_byuser);

                    $bookmark_btn.data('busy', 0);
                    //$bookmark_btn.removeClass('cbxwpbkmarktrig-busy');
                    $bookmark_btn.removeClass('running');

                    var $show_count_html = '';
                    if ($show_count) {
                        $show_count_html = '(<i class="cbxwpbkmarktrig-count">' + $bookmark_count + '</i>)';
                    }

                    var $bookmark_label_html = '';

                    var $display_label_style = '';
                    if ($display_label === 0) {
                        $display_label_style = ' style="display:none;" ';
                    }

                    if ($bookmark_byuser > 0) {
                        $bookmark_label_html = '<span class="cbxwpbkmarktrig-icon"></span><span class="ld ld-ring ld-spin"></span><span ' + $display_label_style + ' class="cbxwpbkmarktrig-label">' + $bookmarked_label + $show_count_html + '</span>';

                        $bookmark_btn.addClass('cbxwpbkmarktrig-marked');
                        //$bookmark_btn.html($bookmark_label_html+$show_count_html);
                        $bookmark_btn.html($bookmark_label_html);
                    } else {
                        $bookmark_label_html = '<span class="cbxwpbkmarktrig-icon"></span><span class="ld ld-ring ld-spin"></span><span ' + $display_label_style + ' class="cbxwpbkmarktrig-label">' + $bookmark_label + $show_count_html + '</span>';

                        $bookmark_btn.removeClass('cbxwpbkmarktrig-marked');
                        //$bookmark_btn.html($bookmark_label_html+$show_count_html);
                        $bookmark_btn.html($bookmark_label_html);
                    }

                    CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_nocats_bk_response', response, $bookmark_btn, $bookmark_panel, $object_id, $object_type, 0); //last param is cat id

                    /*$bookmark_btn.find('.cbxwpbkmarktrig-count').html($bookmark_count);

                    if ($bookmark_byuser > 0) {
                      $bookmark_btn.addClass('cbxwpbkmarktrig-marked');
                    } else {
                      $bookmark_btn.removeClass('cbxwpbkmarktrig-marked');
                    }*/

                    //show modal alert if there defined
                    if (response.alert_msg) {
                        new AWN(awn_options).warning(response.alert_msg);
                    }
                });//end response


            }//end no cat mode

        });//end: on click on +add  it will open the boomark panel

        //on click add category(show category form panel)
        $(document.body).on('click', '.cbxwpbkmark-toolbar-newcat', function (event) {
            event.preventDefault();

            var $this = $(this);

            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            if ((cbxwpbookmark.max_cat_limit > 0) && (cbxwpbookmark.user_current_cat_count >= cbxwpbookmark.max_cat_limit)) {
                //$this.hide();
                $this.addClass('cbxwpbkmark-hide');
                alert(cbxwpbookmark.max_cat_limit_error);
                return;
            } else {
                //hide new button, show list button
                //$toolbar.find('.cbxwpbkmark-toolbar-listcat').show();
                $toolbar.find('.cbxwpbkmark-toolbar-listcat').removeClass('cbxwpbkmark-hide');
                //$this.hide();
                $this.addClass('cbxwpbkmark-hide');

                $cat_book_list.hide(); //hide default category list panel
                $cat_edit_list.hide(); //hide bookmark manage categories
                $cat_add_form.show(); //show category create form wrapper
                $cat_edit_form.hide(); //hide category edit

                $cat_add_form.find('.cbxwpbkmark-form-note').val('').hide();
                $cat_add_form.find('.cbxwpbkmark-field-cat').val('');
                $cat_add_form.find('.cbxwpbkmark-field-catid').val('0');

                //$cat_add_form.find('.cbxwpbkmark-field-privacy').val(1);
                $cat_add_form.find('.cbxwpbkmark-field-privacy').val(cbxwpbookmark.category_default_status);

                $cat_add_form.find('.cbxwpbkmark-field-cat').focus();
                $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_cat_create);
            }
        });//end: on click add category(show category form panel)

        //on click manage categories
        $(document.body).on('click', '.cbxwpbkmark-toolbar-editcat', function (event) {
            event.preventDefault();

            var $this        = $(this);
            var $object_id   = $this.data('object_id'); //post id
            var $object_type = $this.data('type'); //post type

            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            $cat_add_form.hide();
            $cat_edit_form.hide();
            $cat_book_list.hide();
            $cat_edit_list.show();
            //$this.hide();

            //$toolbar.find('.cbxwpbkmark-toolbar-newcat').hide();
            $toolbar.find('.cbxwpbkmark-toolbar-newcat').addClass('cbxwpbkmark-hide');
            //$toolbar.find('.cbxwpbkmark-toolbar-listcat').show();
            $toolbar.find('.cbxwpbkmark-toolbar-listcat').removeClass('cbxwpbkmark-hide');

            $cat_edit_list.find('.cbxlbjs-searchbar').val('');
            $bookmark_panel.find('.cbxwpbkmarkloading').show();

            var data = {
                'action': 'cbx_find_category',
                'security': cbxwpbookmark.nonce,
                'object_id': $object_id,
                'object_type': $object_type,
                // 'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };

            $.post(ajaxurl, data, function (response) {
                response = $.parseJSON(response);

                if (response && response.code === 1) {
                    //categories found
                    $bookmark_panel.find('.cbxwpbkmarkloading').hide();

                    var cats = $.parseJSON(response.cats);

                    var $cat_options_add  = '';
                    var $cat_options_edit = '';

                    cbxwpbookmark.user_current_cat_count = cats.length;
                    cbxwpbookmark.user_current_cats      = cats;

                    $.each(cats, function (key, val) {
                        $cat_options_add += '<li class="cbxlbjs-item" data-catname="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';

                        $cat_options_edit += '<li class="cbxlbjs-item" data-catname="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';

                    });

                    $cat_book_list.find('.cbxwpbkmarklist').html($cat_options_add);
                    $cat_edit_list.find('.cbxwpbkmarklist').html($cat_options_edit);

                    $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_cat_list);

                } else {
                    //failed to get category for this user

                    $bookmark_panel.find('.cbxwpbkmarkloading').hide();

                    //$cat_book_list.hide();
                    //$cat_edit_list.show();

                    //if no category found show message and put option to create one
                    $cat_edit_list.find('.cbxwpbkmarklist').hide();
                    if ($cat_edit_list.find('.cbxwpbkmarklist-nocatfound').length === 0) {
                        if (cbxwpbookmark.bookmark_mode === 'user_cat') {
                            $cat_edit_list.find('.cbxwpbkmark-lbjs').append('<p class="cbxwpbkmarklist-nocatfound"><span data-clicked="0" class="cbxwpbkmarklist-nocatfoundtrig">' + response.msg + '</span></p>');
                        } else if (cbxwpbookmark.bookmark_mode === 'global_cat') {
                            $cat_edit_list.find('.cbxwpbkmark-lbjs').append('<p class="cbxwpbkmarklist-nocatfound">' + response.msg + '</p>');
                        }
                    }

                }

            });
        });//end: on click manage categories

        //on click list categories
        $(document.body).on('click', '.cbxwpbkmark-toolbar-listcat', function (event) {
            event.preventDefault();

            var $this        = $(this);
            var $object_id   = $this.data('object_id'); //post id
            var $object_type = $this.data('type'); //post type

            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            $cat_add_form.hide();
            $cat_edit_form.hide();
            $cat_book_list.show();
            $cat_edit_list.hide();

            //$toolbar.find('.cbxwpbkmark-toolbar-newcat').show();
            $toolbar.find('.cbxwpbkmark-toolbar-newcat').removeClass('cbxwpbkmark-hide');
            //$toolbar.find('.cbxwpbkmark-toolbar-listcat').hide();
            $toolbar.find('.cbxwpbkmark-toolbar-listcat').addClass('cbxwpbkmark-hide');

            $cat_book_list.find('.cbxlbjs-searchbar').val('');
            $bookmark_panel.find('.cbxwpbkmarkloading').show();

            var data = {
                'action': 'cbx_find_category',
                'security': cbxwpbookmark.nonce,
                'object_id': $object_id,
                'object_type': $object_type,
                //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };

            $.post(ajaxurl, data, function (response) {
                response = $.parseJSON(response);

                if (response && response.code === 1) {
                    //categories found
                    $bookmark_panel.find('.cbxwpbkmarkloading').hide();

                    var cats = $.parseJSON(response.cats);

                    var $cat_options_add  = '';
                    var $cat_options_edit = '';

                    cbxwpbookmark.user_current_cat_count = cats.length;
                    cbxwpbookmark.user_current_cats      = cats;

                    $.each(cats, function (key, val) {
                        $cat_options_add += '<li class="cbxlbjs-item" data-catname="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';

                        $cat_options_edit += '<li class="cbxlbjs-item" data-catname="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';

                    });

                    $cat_book_list.find('.cbxwpbkmarklist').html($cat_options_add);
                    $cat_edit_list.find('.cbxwpbkmarklist').html($cat_options_edit);

                    $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_default);

                } else {
                    //failed to get category for this user

                    $bookmark_panel.find('.cbxwpbkmarkloading').hide();

                    //$cat_book_list.hide();
                    //$cat_edit_list.show();

                    //if no category found show message and put option to create one
                    $cat_book_list.find('.cbxwpbkmarklist').hide();
                    if ($cat_book_list.find('.cbxwpbkmarklist-nocatfound').length === 0) {
                        if (cbxwpbookmark.bookmark_mode === 'user_cat') {
                            $cat_book_list.find('.cbxwpbkmark-lbjs').append('<p class="cbxwpbkmarklist-nocatfound"><span data-clicked="0" class="cbxwpbkmarklist-nocatfoundtrig">' + response.msg + '</span></p>');
                        } else if (cbxwpbookmark.bookmark_mode === 'global_cat') {
                            $cat_book_list.find('.cbxwpbkmark-lbjs').append('<p class="cbxwpbkmarklist-nocatfound">' + response.msg + '</p>');
                        }
                    }

                }

            });
        });//end: on click list categories

        //on enter on category field on create mode
        $(document.body).on('keypress', '.cbxwpbkmark-field-cat-add', function (event) {
            //event.preventDefault();
            if (event.which === 13) {
                //$('form#login').submit();
                var $this           = $(this);
                var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');

                $bookmark_panel.find('.cbxwpbkmark-field-create-submit').trigger('click');

                return false;    //<---- Add this line
            }
        });
        //end on enter on category field on create mode

        //on click new category button
        $(document.body).on('click', '.cbxwpbkmark-field-create-submit', function (event) {
            event.preventDefault();

            var $this = $(this);
            var $busy = Number($this.data('busy'));
            if ($busy === 1) return;

            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            if ((cbxwpbookmark.max_cat_limit > 0) && (cbxwpbookmark.user_current_cat_count >= cbxwpbookmark.max_cat_limit)) {
                $cat_add_form.hide();

                //alert(cbxwpbookmark.max_cat_limit_error);
                new AWN(awn_options).warning(cbxwpbookmark.max_cat_limit_error);

                return;
            }

            var $object_id     = $bookmark_panel.data('object_id');
            var $object_type   = $bookmark_panel.data('type');
            var $cat_field     = $cat_add_form.find('.cbxwpbkmark-field-cat');
            var $privacy_field = $cat_add_form.find('.cbxwpbkmark-field-privacy');
            var $catid_field   = $cat_add_form.find('.cbxwpbkmark-field-catid');

            var $cat_name = $cat_field.val();
            var $privacy  = Number($privacy_field.val());
            var $cat_id   = Number($catid_field.val());

            $cat_field.removeClass('error');
            $cat_add_form.find('.cbxwpbkmark-form-note').hide().removeClass('cbxwpbkmark-form-note-success').text('');

            //check if the input text field is empty or not
            //if the text input for category name validate then send ajax request

            if ($cat_name !== '') {

                var $ajax_action = ($cat_id > 0) ? 'cbx_edit_bookmark_category' : 'cbx_add_bookmark_category';
                $bookmark_panel.find('.cbxwpbkmarkloading').show();

                //send ajax request
                var data = {
                    'action': $ajax_action,
                    'security': cbxwpbookmark.nonce,
                    'cat_name': $cat_name,
                    'privacy': $privacy,
                    'cat_id': $cat_id,
                    'object_id': $object_id,
                    'object_type': $object_type,
                    //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                };

                $this.data('busy', 1);
                $this.addClass('running');

                // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                $.post(ajaxurl, data, function (response) {
                    response = $.parseJSON(response);

                    if (response.code === 1) {

                        //category created
                        //$cat_name.val('');

                        $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_cat_edit);

                        $catid_field.val(response.cat_id);

                        var cats                             = $.parseJSON(response.cats);
                        cbxwpbookmark.user_current_cat_count = cats.length;
                        cbxwpbookmark.user_current_cats      = cats;

                        var $cat_options_add  = '';
                        var $cat_options_edit = '';

                        $.each(cats, function (key, val) {
                            $cat_options_add += '<li class="cbxlbjs-item" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';

                            $cat_options_edit += '<li class="cbxlbjs-item" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';
                        });

                        $bookmark_panel.find('.cbxwpbkmarklist').show();
                        $bookmark_panel.find('.cbxwpbkmarkloading').hide();
                        $bookmark_panel.find('.cbxwpbkmarklist-nocatfound').remove();

                        $cat_book_list.find('.cbxwpbkmarklist').html($cat_options_add);
                        $cat_edit_list.find('.cbxwpbkmarklist').html($cat_options_edit);

                        //show success message
                        $cat_add_form.find('.cbxwpbkmark-form-note').show().addClass('cbxwpbkmark-form-note-success').text(response.msg);

                        //check if max user cat limit crossed
                        if ((cbxwpbookmark.max_cat_limit > 0) && (cbxwpbookmark.user_current_cat_count >= cbxwpbookmark.max_cat_limit)) {
                            //then hide the category create panel
                            $cat_add_form.hide();
                            $this.hide();
                            $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_max_cat);

                        }

                    } else {
                        //failed or duplicate
                        $bookmark_panel.find('.cbxwpbkmarkloading').hide('slow');
                        $cat_add_form.find('.cbxwpbkmark-form-note').show().text(response.msg);
                        $cat_field.addClass('error');
                    }//end failed

                    $this.data('busy', 0);
                    $this.removeClass('running');
                });

            } else {
                $cat_field.addClass('error');
                $cat_add_form.find('.cbxwpbkmark-form-note').show().text(cbxwpbookmark.category_name_empty);
            }

        });//end: on click new category button

        //on enter on category field on edit mode
        $(document.body).on('keypress', '.cbxwpbkmark-field-cat-edit', function (event) {
            //event.preventDefault();
            if (event.which === 13) {
                //$('form#login').submit();
                var $this           = $(this);
                var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');

                $bookmark_panel.find('.cbxwpbkmark-field-update-submit').trigger('click');

                return false;    //<---- Add this line
            }
        });
        //end on enter on category field on create mode

        //on click Edit category button
        $(document.body).on('click', '.cbxwpbkmark-field-update-submit', function (event) {
            event.preventDefault();

            var $this = $(this);
            var $busy = Number($this.data('busy'));
            if ($busy === 1) return;

            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            var $object_id     = Number($bookmark_panel.data('object_id'));
            var $object_type   = $bookmark_panel.data('type');
            var $cat_field     = $cat_edit_form.find('.cbxwpbkmark-field-cat');
            var $privacy_field = $cat_edit_form.find('.cbxwpbkmark-field-privacy');
            var $catid_field   = $cat_edit_form.find('.cbxwpbkmark-field-catid');

            var $cat_name = $cat_field.val();
            var $privacy  = Number($privacy_field.val());
            var $cat_id   = Number($catid_field.val());

            $cat_field.removeClass('error');
            $cat_edit_form.find('.cbxwpbkmark-form-note').hide().removeClass('cbxwpbkmark-form-note-success').text('');

            //check if the input text field is empty or not
            //if the text input for category name validate then send ajax request

            if ($cat_name !== '') {

                //$bookmark_panel.find('.cbxwpbkmarkloading').show();

                //send ajax request
                var data = {
                    'action': 'cbx_edit_bookmark_category',
                    'security': cbxwpbookmark.nonce,
                    'cat_name': $cat_name,
                    'privacy': $privacy,
                    'cat_id': $cat_id,
                    'object_id': $object_id,
                    'object_type': $object_type,
                    //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                };

                $this.data('busy', 1);
                $this.addClass('running');

                // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                $.post(ajaxurl, data, function (response) {
                    response = $.parseJSON(response);

                    if (response.code === 1) {
                        var cats                             = $.parseJSON(response.cats);
                        cbxwpbookmark.user_current_cat_count = cats.length;
                        cbxwpbookmark.user_current_cats      = cats;

                        var $cat_options_add  = '';
                        var $cat_options_edit = '';

                        $.each(cats, function (key, val) {
                            $cat_options_add += '<li class="cbxlbjs-item" data-catname="' + cats[key].cat_name + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';

                            $cat_options_edit += '<li class="cbxlbjs-item" data-catname="' + cats[key].cat_name + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';
                        });

                        $bookmark_panel.find('.cbxwpbkmarklist').show();
                        //$bookmark_panel.find('.cbxwpbkmarkloading').hide();
                        $bookmark_panel.find('.cbxwpbkmarklist-nocatfound').remove();

                        $cat_book_list.find('.cbxwpbkmarklist').html($cat_options_add);
                        $cat_edit_list.find('.cbxwpbkmarklist').html($cat_options_edit);

                        //show success message
                        $cat_edit_form.find('.cbxwpbkmark-form-note').show().addClass('cbxwpbkmark-form-note-success').text(response.msg);
                    } else {
                        //failed or duplicate
                        //$bookmark_panel.find('.cbxwpbkmarkloading').hide('slow');
                        $cat_edit_form.find('.cbxwpbkmark-form-note').show().text(response.msg);
                        $cat_field.addClass('error');
                    }//end failed

                    $this.data('busy', 0);
                    $this.removeClass('running');
                });

            } else {
                $cat_field.addClass('error');
                $cat_edit_form.find('.cbxwpbkmark-form-note').show().text(cbxwpbookmark.category_name_empty);
            }

        });//end: on click Edit category button


        //on click close add new category panel
        $(document.body).on('click', '.cbxwpbkmark-field-create-close', function (event) {
            event.preventDefault();

            var $this           = $(this);
            //var $parent = $this.closest('.cbxwpbkmark-toolbar');
            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            //cbxwpbkmark-field-wrap
            $cat_add_form.hide();
            $cat_edit_form.hide();
            $cat_book_list.show();
            $cat_edit_list.hide();

            //$toolbar.find('.cbxwpbkmark-toolbar-newcat').hide();
            $toolbar.find('.cbxwpbkmark-toolbar-newcat').addClass('cbxwpbkmark-hide');
            //$toolbar.find('.cbxwpbkmark-toolbar-listcat').hide();
            $toolbar.find('.cbxwpbkmark-toolbar-listcat').addClass('cbxwpbkmark-hide');

            $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_default);

            //check if max user cat limit crossed
            if ((cbxwpbookmark.max_cat_limit > 0) && (cbxwpbookmark.user_current_cat_count >= cbxwpbookmark.max_cat_limit)) {
                //then hide the category create panel
                //$toolbar.find('.cbxwpbkmark-toolbar-newcat').hide();
                $toolbar.find('.cbxwpbkmark-toolbar-newcat').addClass('cbxwpbkmark-hide');
            } else {
                //$toolbar.find('.cbxwpbkmark-toolbar-newcat').show();
                $toolbar.find('.cbxwpbkmark-toolbar-newcat').removeClass('cbxwpbkmark-hide');
            }

        });//end: on click close add new category panel

        //on click close edit category panel
        $(document.body).on('click', '.cbxwpbkmark-field-update-close', function (event) {
            event.preventDefault();

            var $this           = $(this);
            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            //$bookmark_panel.find('.cbxwpbkmark_cat_book_list').hide();

            $cat_edit_form.hide();
            $cat_edit_list.show();

            $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_cat_edit);

        });//end: on click close edit category panel

        //on click delete category button
        $(document.body).on('click', '.cbxwpbkmark-field-delete-submit', function (event) {
            event.preventDefault();


            var $this           = $(this);
            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            var $cat_name    = $cat_edit_form.find('.cbxwpbkmark-field-cat');
            var $cat         = $cat_edit_form.find('.cbxwpbkmark-field-catid');
            var $object_id   = Number($bookmark_panel.data('object_id'));
            var $object_type = $bookmark_panel.data('type');

            var $cat_id = $cat.val();

            var data = {
                'action': 'cbx_delete_bookmark_category',
                'security': cbxwpbookmark.nonce,
                'id': $cat_id,
                'object_type': $object_type,
                'object_id': $object_id
                //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };

            if ($cat_id > 0) {
                var notifier = new AWN(awn_options);

                var onCancel = () => {

                };

                var onOk = () => {
                    $bookmark_panel.find('.cbxwpbkmarkloading').show();
                    $this.attr('disabled', true);

                    $.post(ajaxurl, data, function (response) {

                        $bookmark_panel.find('.cbxwpbkmarkloading').hide();
                        response = $.parseJSON(response);

                        var message = '';
                        if (response.msg === 1) {

                            //reset the edit fields
                            $cat_name.val(''); //reset category name field
                            $cat.val(''); //reset hidden category id field

                            var cats                             = $.parseJSON(response.cats);
                            cbxwpbookmark.user_current_cat_count = cats.length;
                            cbxwpbookmark.user_current_cats      = cats;

                            var $cat_options_add  = '';
                            var $cat_options_edit = '';

                            $.each(cats, function (key, val) {
                                $cat_options_add += '<li class="cbxlbjs-item" data-catname="' + cats[key].cat_name + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';

                                $cat_options_edit += '<li class="cbxlbjs-item" data-catname="' + cats[key].cat_name + '" data-privacy="' + cats[key].privacy + '" data-incat="' + cats[key].incat + '"  data-value="' + cats[key].id + '"><span title="' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '" class="cbxlbjs-item-name">' + cbxwpbookmark_stripslashes(cats[key].cat_name) + '</span></li>';
                            });

                            $bookmark_panel.find('.cbxwpbkmarklist-nocatfound').remove();

                            //update category list for add view and edit view
                            $cat_book_list.find('.cbxwpbkmarklist').html($cat_options_add);
                            $cat_edit_list.find('.cbxwpbkmarklist').html($cat_options_edit);

                            //hide the category edit panel
                            $cat_edit_form.hide();
                            $cat_edit_list.show();

                            // success Message
                            message = cbxwpbookmark.category_delete_success;
                            $cat_edit_form.find('.cbxwpbkmark-form-note').hide().addClass('cbxwpbkmark-form-note-success').text('');
                            $bookmark_panel.find('.cbxwpbkmarktrig_label').text(message);
                        } else {
                            message = cbxwpbookmark.category_delete_error;
                            $cat_edit_form.find('.cbxwpbkmark-form-note').show().text(message);
                        }

                        $this.attr('disabled', false);
                    });
                };

                notifier.confirm(
                    cbxwpbookmark.are_you_sure_delete_desc,
                    onOk,
                    onCancel,
                    {
                        labels: {
                            confirm: cbxwpbookmark.are_you_sure_global
                        }
                    }
                );

            }
        });//end: on click delete category button

        //adding click event on the list - bookmark list
        $(document.body).on('click', '.cbxwpbkmark_cat_book_list .cbxlbjs-item', function (e) {

            var $item           = $(this);
            var $bookmark_panel = $item.closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            $bookmark_panel.find('.cbxwpbkmarkloading').show();

            var $bookmark_panel_parent = $bookmark_panel.closest('.cbxwpbkmarkwrap');
            var $bookmark_btn          = $bookmark_panel_parent.find('.cbxwpbkmarktrig');

            var $object_id   = $bookmark_panel.data('object_id');
            var $object_type = $bookmark_panel.data('type'); //object type

            var $cat_id = $item.attr('data-value');

            var $bookmark_label   = $bookmark_btn.data('bookmark-label');
            var $bookmarked_label = $bookmark_btn.data('bookmarked-label');
            var $show_count       = Number($bookmark_btn.data('show-count'));
            var $display_label    = Number($bookmark_btn.data('display-label'));


            CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_cats_bk_click', $bookmark_btn, $bookmark_panel, $object_id, $object_type, $cat_id);//last param category id

            //now send ajax request to save this post id and category as bookmark for this user
            //post id already in variable $object_id

            var bookmarkData = {
                'action': 'cbx_add_bookmark',
                'security': cbxwpbookmark.nonce,
                'cat_id': $cat_id,
                'object_id': $object_id,
                'object_type': $object_type,
                //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };

            $.post(ajaxurl, bookmarkData, function (response) {

                response             = $.parseJSON(response);
                var $bookmark_count  = Number(response.bookmark_count);
                var $bookmark_byuser = Number(response.bookmark_byuser);


                if (response && response.code === 1) {
                    $item.attr('data-incat', response.operation);

                    $bookmark_panel.find('.cbxwpbkmarkloading').hide();
                    $bookmark_panel.find('.cbxwpbkmarktrig_label').text(response.msg);

                    var $show_count_html = '';
                    if ($show_count) {
                        $show_count_html = '(<i class="cbxwpbkmarktrig-count">' + $bookmark_count + '</i>)';
                    }

                    var $display_label_style = '';
                    if ($display_label === 0) {
                        $display_label_style = ' style="display:none;" ';
                    }

                    if ($bookmark_byuser > 0) {
                        var $bookmark_label_html = '<span class="cbxwpbkmarktrig-icon"></span><span ' + $display_label_style + ' class="cbxwpbkmarktrig-label">' + $bookmarked_label + $show_count_html + '</span>';

                        $bookmark_btn.addClass('cbxwpbkmarktrig-marked');
                        //$bookmark_btn.html($bookmark_label_html+$show_count_html);
                        $bookmark_btn.html($bookmark_label_html);
                    } else {
                        var $bookmark_label_html = '<span class="cbxwpbkmarktrig-icon"></span><span ' + $display_label_style + ' class="cbxwpbkmarktrig-label">' + $bookmark_label + $show_count_html + '</span>';

                        $bookmark_btn.removeClass('cbxwpbkmarktrig-marked');
                        //$bookmark_btn.html($bookmark_label_html+$show_count_html);
                        $bookmark_btn.html($bookmark_label_html);
                    }

                    CBXWPBookmarkEvents_do_action('cbxwpbookmark_trigger_cats_bk_response', response, $bookmark_btn, $bookmark_panel, $object_id, $object_type, $cat_id);

                } else {
                    $bookmark_panel.find('.cbxwpbkmarktrig_label').text(response.msg);
                    $bookmark_panel.find('.cbxwpbkmarkloading').hide();
                }

                //show modal alert if there defined
                if (response.alert_msg) {
                    new AWN(awn_options).warning(response.alert_msg);
                }
            });//end response

        });//end: adding click event on the list - bookmark list

        //adding click event on the list - bookmark  edit list
        $(document.body).on('click', '.cbxwpbkmark_cat_edit_list .cbxlbjs-item', function (e) {
            var $this           = $(this);
            var $bookmark_panel = $(this).closest('.cbxwpbkmarklistwrap');
            var $cat_add_form   = $bookmark_panel.find('.cbxwpbkmark_cat_add_form');
            var $cat_edit_form  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_form');
            var $cat_book_list  = $bookmark_panel.find('.cbxwpbkmark_cat_book_list');
            var $cat_edit_list  = $bookmark_panel.find('.cbxwpbkmark_cat_edit_list');
            var $toolbar        = $bookmark_panel.find('.cbxwpbkmark-toolbar');

            var $object_id   = $bookmark_panel.data('object_id');
            var $object_type = $bookmark_panel.data('type'); //object type

            var $cat_id      = Number($this.attr('data-value'));
            var $cat_privacy = Number($this.attr('data-privacy'));
            var $cat_name    = $this.attr('data-catname');

            $cat_edit_list.hide();
            $cat_edit_form.show();

            $cat_edit_form.find('.cbxwpbkmark-field-cat').val($cat_name).focus();
            $cat_edit_form.find('.cbxwpbkmark-field-catid').val($cat_id);
            $cat_edit_form.find('.cbxwpbkmark-field-privacy_' + $object_id).val($cat_privacy);

            $bookmark_panel.find('.cbxwpbkmarktrig_label').text(cbxwpbookmark.add_to_head_cat_edit);
        });//end: adding click event on the list - bookmark  edit list

        //on click on nocategory found
        $(document.body).on('click', '.cbxwpbkmarklist-nocatfoundtrig', function (event) {
            event.preventDefault();

            var $this           = $(this);
            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');
            $bookmark_panel.find('.cbxwpbkmark-toolbar-newcat').trigger('click');
        });//end: on click on nocategory found

        //on click on close icon disable the bookmark popup
        $(document.body).on('click', '.cbxwpbkmarktrig_close', function (event) {
            var $this           = $(this);
            var $bookmark_panel = $this.closest('.cbxwpbkmarklistwrap');

            $bookmark_panel.fadeOut();

            //CBXWPBookmarkEvents_do_action('cbxwpbookmark_pop_closed', $this, $bookmark_panel);
        });//end: on click on close icon disable the bookmark popup

        //on click on close icon disable the bookmark popup for guest view
        $(document.body).on('click', '.cbxwpbkmarkguesttrig_close', function (event) {
            event.stopPropagation();
            event.preventDefault();

            var $this           = $(this);
            var $bookmark_panel = $this.closest('.cbxwpbkmarkguestwrap');
            $bookmark_panel.fadeOut();

            //CBXWPBookmarkEvents_do_action('cbxwpbookmark_pop_guest_closed', $this, $bookmark_panel);
        });//end: on click on close icon disable the bookmark popup for guest view

        //on click on any where of body except the bookmark popup close the bookmark popup
        $('body').on('mouseup', function (e) {
            var $bookmark_panel = $('.cbxwpbkmarklistwrap');
            if ((e.target.id !== 'cbxwpbkmarklistwrap-' + $bookmark_panel.data('object_id')) && !$bookmark_panel.has(e.target).length) {
                $bookmark_panel.fadeOut();
            }

            var $bookmark_guest_panel = $('.cbxwpbkmarkguestwrap');
            if ((e.target.id !== 'cbxwpbkmarkguestwrap-' + $bookmark_guest_panel.data('object_id')) && !$bookmark_guest_panel.has(e.target).length) {
                $bookmark_guest_panel.fadeOut();
            }
        });//end: on click on any where of body except the bookmark popup close the bookmark popup

        //category search using fuzzy logic
        $(document.body).on('keyup', '.cbxlbjs-searchbar', function (event) {
            var $this = $(this);
            var val   = $this.val();

            var catlistholder = $this.closest('.cbxlbjs-searchbar-wrapper').next('.cbxwpbkmarklist');

            if (this.value.length > 0) {
                catlistholder.find('li').hide().filter(function () {
                    return $(this).text().toLowerCase().indexOf(val.toLowerCase()) !== -1;
                }).show();
            } else {
                catlistholder.find('li').show();
            }

        });

        //we don't need this as after changing css class name the above code works for both book list and edit list
        /*$(document.body).on('keyup', '.cbxlbjs-searchbar-manage', function (event) {
          var $this = $(this);
          var val   = $this.val();

          var catlistholder = $this.closest('.cbxlbjs-searchbar-wrapper-manage').next('.cbxwpbkmarklist-manage');

          if (this.value.length > 0) {
            catlistholder.find('li').hide().filter(function () {
              return $(this).text().toLowerCase().indexOf(val.toLowerCase()) !== -1;
            }).show();
          }
          else {
            catlistholder.find('li').show();
          }

        });*/

        /* User Front Admin */

        //my category delete button action
        $('.cbxbookmark-category-list').on('click', '.cbxbookmark-delete-btn', function (event) {
            event.preventDefault();

            var $this         = $(this);
            var $this_item    = $this.closest('li');
            var $this_wrapper = $this_item.closest('ul');
            var $id           = $this.data('id');
            //var $object_id 		= Number($this.data('object_id'));
            //var $object_type 	= $this.data('object_type');

            var data = {
                'action': 'cbx_delete_bookmark_category',
                'security': cbxwpbookmark.nonce,
                'id': Number($id)
                //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations

            if (Number($id) > 0) {
                var notifier = new AWN(awn_options);
                var onCancel = () => {

                };

                var onOk = () => {
                    $this.attr('disabled', true);
                    //$this.addClass('cbxbookmark-delete-btn-busy');
                    $this.addClass('running');

                    $.post(ajaxurl, data, function (response) {

                        response = $.parseJSON(response);

                        if (response.msg === 1) {
                            // success Message
                            var message = cbxwpbookmark.category_delete_success;
                            // Remove the li tag if the category deleted
                            $this_item.remove();
                            new AWN(awn_options).success(message);

                            if ($this_wrapper.find('li').length === 0) {
                                $this_wrapper.append('<li class="cbxbookmark-category-list-item-notfound">' + cbxwpbookmark.no_cats_found + '</li>');
                            }


                        } else {
                            var message = cbxwpbookmark.category_delete_error;
                            new AWN(awn_options).warning(message);

                            $this.attr('disabled', false);
                            //$this.removeClass('cbxbookmark-delete-btn-busy');
                            $this.removeClass('running');
                        }
                    });
                };

                notifier.confirm(
                    cbxwpbookmark.are_you_sure_delete_desc,
                    onOk,
                    onCancel,
                    {
                        labels: {
                            confirm: cbxwpbookmark.are_you_sure_global
                        }
                    }
                );


            }
        });

        //my category update event


        //on enter on category field on add mode
        $(document.body).on('keypress', '.cbxbmedit-catname-add', function (event) {
            //event.preventDefault();
            if (event.which === 13) {
                //$('form#login').submit();
                var $this   = $(this);
                var $parent = $this.closest('#cbxbookmark-category-list-create-wrap');

                $parent.find('.cbxbookmark-cat-save').trigger('click');

                return false;
            }
        });
        //end on enter on category field on add mode

        //on enter on category field on edit mode
        $(document.body).on('keypress', '.cbxbmedit-catname-edit', function (event) {
            //event.preventDefault();
            if (event.which === 13) {
                //$('form#login').submit();
                var $this   = $(this);
                var $parent = $this.closest('li.cbxbookmark-category-list-item');

                $parent.find('.cbxbookmark-cat-save').trigger('click');

                return false;
            }
        });
        //end on enter on category field on edit mode

        //category save button action
        $('.cbxbookmark-category-list').on('click', '.cbxbookmark-cat-save', function (event) {
            event.preventDefault();

            var $this = $(this);
            var $busy = Number($this.data('busy'));
            if ($busy == 1) return;

            var $parent = $this.closest('li.cbxbookmark-category-list-item');

            var $ucatid = $parent.data('id'); //get this value from the parent data attribute

            //get new value
            var $ucatname = $parent.find('.cbxbmedit-catname').val();
            var $uprivacy = $parent.find('.cbxbmedit-privacy').val();

            var updatedata = {
                'action': 'cbx_update_bookmark_category',
                'security': cbxwpbookmark.nonce,
                'id': $ucatid,
                'catname': $ucatname,
                'privacy': $uprivacy,
                //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };

            $this.data('busy', 1);
            $this.addClass('running');

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            $.post(ajaxurl, updatedata, function (response) {

                response = $.parseJSON(response);

                if (response.flag === 1) {
                    $parent.find('.cbxbookmark-mycat-editbox').hide();
                    $parent.data('privacy', response.privacy);
                    $parent.data('name', response.catname);
                    $parent.find('.cbxlbjs-item-widget').html(response.catname);
                } else if (response.flag === 0) {
                    $parent.closest('.cbxbookmark-category-list').prev('.cbxbookmark-errormsg').html(response.msg);
                }

                $this.data('busy', 0);
                $this.removeClass('running');
            });

        });

        //my category edit button action
        //edit button action and it opens the edit panel for the clicked item
        $('.cbxbookmark-category-list').on('click', '.cbxbookmark-edit-btn', function (event) {
            event.preventDefault();

            var $this = $(this);

            //var $parent = $(this).closest('.cbxbookmark-mycat-item');
            var $parent = $this.closest('.cbxbookmark-category-list-item');

            var $catid   = $parent.data('id');
            var $catname = $parent.data('name');
            var $privacy = $parent.data('privacy');

            var $editpanel = $parent.find('.cbxbookmark-mycat-editbox');
            if ($editpanel.length === 0) {
                var $template = $.parseJSON(cbxwpbookmark.cat_template);

                /* Repalcing Input Values */
                $template = $template.replace(/##catname##/g, $catname);
                $parent.append($template);

                if ($privacy === 0) {
                    $parent.find('.cbxbmedit-privacy option:eq(1)').prop('selected', true);
                    $parent.find('.cbxbmedit-privacy option:eq(0)').prop('selected', false);
                }

                if ($privacy === 1) {
                    $parent.find('.cbxbmedit-privacy option:eq(0)').prop('selected', true);
                    $parent.find('.cbxbmedit-privacy option:eq(1)').prop('selected', false);
                }
            } else {
                $editpanel.show();
                $parent.find('.cbxbmedit-catname').val($catname);
                //$parent.find('.cbxbmedit-privacy').val($privacy);

                if ($privacy === 0) {
                    $parent.find('.cbxbmedit-privacy option:eq(1)').prop('selected', true);
                    $parent.find('.cbxbmedit-privacy option:eq(0)').prop('selected', false);
                }

                if ($privacy === 1) {
                    $parent.find('.cbxbmedit-privacy option:eq(0)').prop('selected', true);
                    $parent.find('.cbxbmedit-privacy option:eq(1)').prop('selected', false);
                }
            }

        });

        //close action for edit panel for each single item
        $('.cbxbookmark-category-list').on('click', '.cbxbookmark-cat-close', function (e) {
            e.preventDefault();

            $(this).closest('.cbxbookmark-mycat-editbox').css('display', 'none');
        });

        /**
         * Delete a bookmark in list mode
         */
        $('.cbxwpbookmark-mylist').on('click', '.cbxbookmark-post-delete', function (event) {
            event.preventDefault();

            var $postdelete = $(this);
            var $wrapper    = $postdelete.closest('div.cbxwpbookmark-mylist-wrap');

            var $object_id   = $postdelete.data('object_id');
            var $bookmark_id = $postdelete.data('bookmark_id');
            var $busy        = parseInt($postdelete.data('busy'));

            var data = {
                'action': 'cbx_delete_bookmark_post',
                'security': cbxwpbookmark.nonce,
                'object_id': $object_id,
                'bookmark_id': $bookmark_id
                //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations

            if ($busy === 0) {
                var notifier = new AWN(awn_options);

                var onCancel = () => {

                };

                var onOk = () => {

                    //$wrapper.find('.cbxbookmark-alert').remove();
                    $postdelete.data('busy', 1);
                    //$postdelete.addClass('cbxbookmark-delete-btn-busy');
                    $postdelete.addClass('running');

                    $.post(ajaxurl, data, function (response) {
                        response = $.parseJSON(response);

                        if (response.msg === 0) {
                            // Remove the li tag if the bookmark is deleted
                            $postdelete.closest('li').remove();

                            new AWN(awn_options).success(cbxwpbookmark.bookmark_removed);

                            if ($wrapper.find('ul.cbxwpbookmark-mylist li').length === 0) {
                                $wrapper.find('ul.cbxwpbookmark-mylist').append('<li class="cbxwpbookmark-mylist-item cbxwpbookmark-mylist-item-notfound">' + cbxwpbookmark.bookmark_removed_empty + '</li>');
                            }
                        } else if (response.msg === 1) {
                            new AWN(awn_options).warning(cbxwpbookmark.bookmark_removed_failed);

                            //$this.removeClass('cbxbookmark-delete-btn-busy');
                            $this.removeClass('running');
                        }
                    });
                };

                notifier.confirm(
                    cbxwpbookmark.are_you_sure_delete_desc,
                    onOk,
                    onCancel,
                    {
                        labels: {
                            confirm: cbxwpbookmark.are_you_sure_global
                        }
                    }
                );
            }


        });

        //implementing the bookmark load more feature
        $('.cbxwpbookmark-mylist-wrap').on('click', '.cbxbookmark-more', function (e) {
            e.preventDefault();
            var _this = $(this);
            var $busy = Number(_this.data('busy'));
            if ($busy === 1) return;

            var $wrapper = _this.closest('div.cbxwpbookmark-mylist-wrap');

            //$wrapper.find('.cbxwpbm_ajax_icon').show();

            var limit  = _this.data('limit');
            var offset = _this.data('offset');
            var catid  = _this.data('catid');
            var type   = _this.data('type');

            var order   = _this.data('order');
            var orderby = _this.data('orderby');
            var userid  = _this.data('userid');

            var totalpage   = _this.data('totalpage');
            var currpage    = _this.data('currpage');
            var allowdelete = _this.data('allowdelete');

            if (currpage + 1 >= totalpage) {
                _this.hide();
            } else {
                _this.show();
            }

            var addcat = {
                'action': 'cbx_bookmark_loadmore',
                'security': cbxwpbookmark.nonce,
                'limit': limit,
                'offset': offset,
                'catid': catid,
                'type': type,
                'order': order,
                'orderby': orderby,
                'userid': userid,
                'allowdelete': allowdelete,
                //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
            };

            _this.data('busy', 1);
            _this.addClass('running');

            $.post(ajaxurl, addcat, function (response) {
                response = $.parseJSON(response);

                if (response.code) {
                    _this.data('offset', limit + offset);
                    _this.data('currpage', currpage + 1);

                    $wrapper.find('ul.cbxwpbookmark-mylist').append(response.data);
                    //$wrapper.find('.cbxwpbm_ajax_icon').hide();

                } else {
                    //var $error_html = $('<div class="cbxbookmark-alert cbxbookmark-alert-error">' + cbxwpbookmark.error_msg + response.code + '</div>');
                    //$wrapper.find('ul.cbxwpbookmark-mylist').append($error_html);

                    new AWN(awn_options).warning(cbxwpbookmark.error_msg + response.code);
                }

                _this.data('busy', 0);
                _this.removeClass('running');
            });

        });

        //bookmarks list items as sublist
        $('.cbxbookmark-category-list-item-expand').on('click', '.cbxlbjs-item-widget', function (e) {
            e.preventDefault();

            var $this      = $(this);
            var $parent    = $this.closest('.cbxbookmark-category-list-item-expand');
            var $cat_id    = Number($parent.data('id'));
            var $cat_total = Number($parent.data('total'));
            var $processed = Number($parent.data('processed'));
            var $privacy   = Number($parent.data('privacy'));
            var $userid    = Number($parent.data('userid'));
            var $totalpage = Number($parent.data('totalpage'));
            var $page      = Number($parent.data('page'));

            if ($totalpage > 0) {
                if ($processed === 0) {
                    $this.data('busy', 1);
                    $this.next('ul').remove();

                    var data = {
                        'action': 'cbx_load_bookmarks_sublist',
                        'security': cbxwpbookmark.nonce,
                        'cat_id': $cat_id,
                        'cat_total': $cat_total,
                        'privacy': $privacy,
                        'userid': $userid,
                        'totalpage': $totalpage,
                        'page': $page,
                        //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                    };

                    $.post(ajaxurl, data, function (response) {
                        $this.data('busy', 0);
                        response = $.parseJSON(response);

                        if (Number(response.code)) {
                            $parent.data('processed', 1);
                            var output    = $.parseJSON(response.output);
                            var show_more = Number(response.show_more);

                            if (show_more) {
                                $page++;
                            }

                            var show_more_html = (show_more) ? '<a data-busy="0" href="#" data-page="' + $page + '" data-totalpage="' + $totalpage + '" data-userid="' + $userid + '" data-privacy="' + $privacy + '" data-cat_total="' + $cat_total + '" data-cat_id="' + $cat_id + '" class="cbxbookmark-category-list-item-sublist-a-more">' + cbxwpbookmark.load_more + '</a>' : '';

                            if ($this.next('ul').length === 0) {
                                $parent.append('<ul class="cbxbookmark-category-list-item-sublist">' + output + '</ul>' + show_more_html);
                            } else {
                                $this.next('ul').append(output);
                            }
                        } else {
                            $parent.data('processed', 1);
                        }
                    });
                }
            } else {
                if ($processed === 0) {
                    $parent.data('processed', 1);
                    $parent.append('<ul class="cbxbookmark-category-list-item-sublist cbxbookmark-category-list-item-sublist-notfound"><li>' + cbxwpbookmark.bookmark_not_found + '</li></ul>');
                }
            }
        });

        //implementing the bookmark load more feature for category sub list
        $(document.body).on('click', '.cbxbookmark-category-list-item-sublist-a-more', function (e) {
            e.preventDefault();

            var $this = $(this);


            var $cat_id    = Number($this.data('cat_id'));
            var $cat_total = Number($this.data('cat_total'));
            var $privacy   = Number($this.data('privacy'));
            var $userid    = Number($this.data('userid'));
            var $totalpage = Number($this.data('totalpage'));
            var $page      = Number($this.data('page')); //current page
            var $busy      = Number($this.data('busy'));
            var $parent    = $this.prev('.cbxbookmark-category-list-item-sublist');

            if ($busy === 0) {
                $this.data('busy', 1);
                if ($page === $totalpage) {
                    $this.remove();
                }

                var data = {
                    'action': 'cbx_load_bookmarks_sublist',
                    'security': cbxwpbookmark.nonce,
                    'cat_id': $cat_id,
                    'cat_total': $cat_total,
                    'privacy': $privacy,
                    'userid': $userid,
                    'totalpage': $totalpage,
                    'page': $page,
                    //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                };

                $.post(ajaxurl, data, function (response) {

                    response = $.parseJSON(response);

                    if (Number(response.code)) {
                        $parent.data('processed', 1);
                        var output    = $.parseJSON(response.output);
                        var show_more = Number(response.show_more);

                        if (show_more) {
                            $page++;
                            $this.data('page', $page);

                            $this.data('busy', 0);
                        }

                        $parent.append(output);
                    }
                });//end ajax
            }//end busy
        });


        $('#cbxbookmark-category-list-create-wrap').on('click', '.cbxbookmark-category-list-create', function (e) {
            e.preventDefault();

            var $this   = $(this);
            var $parent = $this.closest('#cbxbookmark-category-list-create-wrap');
            var $form   = $parent.find('.cbxbookmark-category-list-create-form');

            $form.toggle();
        });

        //on click new category button
        $('#cbxbookmark-category-list-create-wrap').on('click', '.cbxbookmark-cat-close', function (event) {
            event.preventDefault();

            var $this         = $(this);
            var $parent       = $this.closest('#cbxbookmark-category-list-create-wrap');
            var $cat_add_form = $parent.find('.cbxbookmark-category-list-create-form');
            $cat_add_form.toggle();
        });

        //on click new category button from category list
        $('#cbxbookmark-category-list-create-wrap').on('click', '.cbxbookmark-cat-save', function (event) {
            event.preventDefault();

            var $this = $(this);
            var $busy = Number($this.data('busy'));
            if ($busy == 1) return;


            var $parent        = $this.closest('#cbxbookmark-category-list-create-wrap');
            var $cat_add_form  = $parent.find('.cbxbookmark-category-list-create-form');
            var $category_list = $parent.next('.cbxbookmark-category-list');

            var $cat_field     = $cat_add_form.find('.cbxbmedit-catname');
            var $privacy_field = $cat_add_form.find('.cbxbmedit-privacy');
            //var $catid_field   = $cat_add_form.find('.cbxwpbkmark-field-catid');

            var $cat_name = $cat_field.val();
            var $privacy  = Number($privacy_field.val());

            if ($cat_name !== '') {
                $cat_field.removeClass('cbxbmedit-catname-error');

                var data = {
                    'action': 'cbx_add_bookmark_category_std',
                    'security': cbxwpbookmark.nonce,
                    'cat_name': $cat_name,
                    'privacy': $privacy
                    //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                };

                $this.data('busy', 1);
                $this.addClass('running');

                $.post(ajaxurl, data, function (response) {
                    response = $.parseJSON(response);

                    $this.data('busy', 0);
                    $this.removeClass('running');

                    if (response.code === 1) {
                        $cat_field.val('');
                        $cat_field.removeClass('cbxbmedit-catname-error');

                        $category_list.prepend(response.list_html);
                        new AWN(awn_options).success(response.msg);


                        $category_list.find('.cbxbookmark-category-list-item-notfound').remove();

                    } else {
                        $cat_field.addClass('cbxbmedit-catname-error');
                        new AWN(awn_options).warning(response.msg);
                    }

                });


            } else {
                $cat_field.addClass('cbxbmedit-catname-error');
            }
        });

        //delete all bookmarks of a user by user
        $('.cbxwpbookmark_deleteall').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $parent = $this.closest('.cbxwpbookmark-title');
            var $busy = Number($this.data('busy'));


            if ($busy === 1) return;


            var notifier = new AWN(awn_options);

            var onCancel = () => {

            };

            var onOk = () => {

                $this.data('busy', 1);
                $this.prop('disabled', true);
                $this.addClass('running');

                var data = {
                    'action': 'cbxwpbkmark_delete_all_bookmarks_by_user',
                    'security': cbxwpbookmark.nonce,
                    //'cbxbookmark_lang': cbxwpbookmark.cbxbookmark_lang
                };

                $.post(ajaxurl, data, function (response) {
                        $this.data('busy', 0);
                        $this.prop('disabled', false);
                        $this.removeClass('running');

                        if (response && response.code === 1) {
                            new AWN(awn_options).success(response.msg);

                            $parent.next('.cbxwpbookmark-list-generic').remove();
                            $parent.next('.cbxbookmark_cards_wrapper_postgrid').remove();
                        } else {
                            $this.data('busy', 0);
                            $this.prop('disabled', false);
                            $this.removeClass('cbxwpbookmark_deleteall_busy');
                        }
                    }
                );
            };

            notifier.confirm(
                cbxwpbookmark.are_you_sure_delete_desc,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbxwpbookmark.are_you_sure_global
                    }
                }
            );


        });

        $('.cbxwpbookmark_share').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);

            var $share_url    = $this.attr('href');
            var shareurl_html = cbxwpbookmark.shareurl_html;
            shareurl_html     = shareurl_html.replace(/##share_url##/g, $share_url);
            shareurl_html     = shareurl_html.replace(/##share_url##/g, $share_url);

            new AWN().modal(
                shareurl_html,
                'awn-popup-body-cbxbookmark-shareurl',
                awn_options
            )
        });

        var cbxbookmark_sharecopy_timeoutId;

        $(document.body).on('click', '.shareurl_copy_btn', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $url  = $this.data('url');
            $this.prop('disabled', true);

            cbxwpbookmark_copyStringToClipboard($url);
            $this.text(cbxwpbookmark.shareurl_trans.copied);

            clearTimeout(cbxbookmark_sharecopy_timeoutId);
            cbxbookmark_sharecopy_timeoutId = setTimeout(function () {
                $this.text(cbxwpbookmark.shareurl_trans.copy);
                $this.prop('disabled', false);
            }, 1000);
        });
    });//end dom ready
})(jQuery);