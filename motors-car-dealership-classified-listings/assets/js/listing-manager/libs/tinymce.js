(function ($) {
    'use strict';

    var initTinyMCE = function () {
        if (typeof tinymce === 'undefined') {
            return;
        }

        tinymce.init({
            selector: '#description',
            height: 300,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
                'template', 'codesample', 'visualchars', 'nonbreaking', 'pagebreak',
                'quickbars', 'save', 'directionality', 'resize'
            ],
            toolbar: 'undo redo | styles fontfamily | bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link customInsertImage media table codesample | ' +
                'forecolor backcolor emoticons | removeformat code fullscreen | ' +
                'ltr rtl',
            toolbar_mode: 'sliding',
            menubar: 'view format tools',
            menu: {
                edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
                view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
                insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor | insertdatetime' },
                format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' },
                tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount' },
            },
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
            font_formats: 'Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,sans-serif; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
            fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
            style_formats: [
                {
                    title: 'Headers', items: [
                        { title: 'Header 1', format: 'h1' },
                        { title: 'Header 2', format: 'h2' },
                        { title: 'Header 3', format: 'h3' },
                        { title: 'Header 4', format: 'h4' },
                        { title: 'Header 5', format: 'h5' },
                        { title: 'Header 6', format: 'h6' }
                    ]
                },
                {
                    title: 'Inline', items: [
                        { title: 'Bold', format: 'bold' },
                        { title: 'Italic', format: 'italic' },
                        { title: 'Underline', format: 'underline' },
                        { title: 'Strikethrough', format: 'strikethrough' },
                        { title: 'Superscript', format: 'superscript' },
                        { title: 'Subscript', format: 'subscript' },
                        { title: 'Code', format: 'code' }
                    ]
                },
                {
                    title: 'Blocks', items: [
                        { title: 'Paragraph', format: 'p' },
                        { title: 'Blockquote', format: 'blockquote' },
                        { title: 'Div', format: 'div' },
                        { title: 'Pre', format: 'pre' }
                    ]
                },
                {
                    title: 'Alignment', items: [
                        { title: 'Left', format: 'alignleft' },
                        { title: 'Center', format: 'aligncenter' },
                        { title: 'Right', format: 'alignright' },
                        { title: 'Justify', format: 'alignjustify' }
                    ]
                }
            ],
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
            quickbars_insert_toolbar: 'customInsertImage quicktable',
            quickbars_image_toolbar: 'alignleft aligncenter alignright | rotateleft rotateright | imageoptions',
            image_advtab: true,
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: null,
            templates: [
                { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
                { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
                { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 300,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
            contextmenu: 'link wpimage table configurepermanentpen',
            resize: true,
            resize_img_proportional: true,
            resize_use_percentages: true,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            file_picker_callback: function (cb, value, meta) {
                if (MVL_Listing_Manager.isWpMediaAvailable()) {
                    MVL_Listing_Manager.descriptionWpMediaOpen = true;
                    MVL_Listing_Manager.descriptionCb = cb;

                    let wpmedia = MVL_Listing_Manager.wpmedia;
                    let state = wpmedia.state();
                    let isMultiple = false;
                    let selection = state.get('selection');
                    let view = state.get('view');
                    let library = state.get('library');

                    library.props.set('type', listingManagerImageMediaArgs.library.type);

                    state.set('multiple', isMultiple);

                    if (selection) {
                        selection.multiple = isMultiple;
                        selection.reset();
                    }

                    if (view) {
                        view.refresh();
                    }

                    wpmedia.open();
                }
            },
            setup: function (editor) {
                MVL_Listing_Manager.descriptionEditor = editor;
                editor.ui.registry.addButton('customInsertImage', {
                    icon: 'image',
                    tooltip: 'Insert image',
                    onAction: function () {
                        if (MVL_Listing_Manager.isWpMediaAvailable()) {
                            MVL_Listing_Manager.descriptionWpMediaOpen = true;

                            let wpmedia = MVL_Listing_Manager.wpmedia;
                            let state = wpmedia.state();
                            let isMultiple = false;
                            let selection = state.get('selection');
                            let view = state.get('view');
                            let library = state.get('library');

                            library.props.set('type', listingManagerImageMediaArgs.library.type);

                            state.set('multiple', isMultiple);

                            if (selection) {
                                selection.multiple = isMultiple;
                                selection.reset();
                            }

                            if (view) {
                                view.refresh();
                            }

                            wpmedia.open();
                        }
                    }
                });

                // Это официальный способ переопределить пункт 'image' из вашего `contextmenu`
                editor.ui.registry.addMenuItem('wpimage', {
                    text: 'Image...',
                    icon: 'image',
                    // onAction - это и есть "событие" клика по пункту меню
                    onAction: function () {
                        if (MVL_Listing_Manager.isWpMediaAvailable()) {
                            MVL_Listing_Manager.descriptionWpMediaOpen = true;

                            let wpmedia = MVL_Listing_Manager.wpmedia;
                            let state = wpmedia.state();
                            let isMultiple = false;
                            let selection = state.get('selection');
                            let view = state.get('view');
                            let library = state.get('library');

                            library.props.set('type', listingManagerImageMediaArgs.library.type);

                            state.set('multiple', isMultiple);

                            if (selection) {
                                selection.multiple = isMultiple;
                                selection.reset();
                            }

                            if (view) {
                                view.refresh();
                            }

                            wpmedia.open();
                        }
                    },
                    // Этот обработчик делает пункт меню активным только если выделен тег IMG
                    onSetup: function (api) {
                        var nodeChangeHandler = function (e) {
                            api.setDisabled(e.element.nodeName.toLowerCase() !== 'img');
                        };
                        editor.on('NodeChange', nodeChangeHandler);
                        return function () {
                            editor.off('NodeChange', nodeChangeHandler);
                        };
                    }
                });

                editor.on('change', function (e) {
                    MVL_Listing_Manager.nodes.form.querySelector('#description').innerHTML = editor.getContent();
                    MVL_Listing_Manager.formChanged = true;
                    MVL_Listing_Manager.change(e);

                });
                editor.on('input', function (e) {
                    MVL_Listing_Manager.nodes.form.querySelector('#description').innerHTML = editor.getContent();
                    MVL_Listing_Manager.formChanged = true;
                    MVL_Listing_Manager.change(e);
                });


            }
        });
    };

    $(document).ready(function () {
        initTinyMCE();
    });

    // Reinitialize TinyMCE when form is loaded via AJAX
    $(document).on('ajaxComplete', function () {
        initTinyMCE();
    });

    if (MVL_Listing_Manager.isWpMediaAvailable()) {
        MVL_Listing_Manager.wpmedia.on('select', function (e) {
            if (MVL_Listing_Manager.descriptionWpMediaOpen) {
                MVL_Listing_Manager.descriptionWpMediaOpen = false;

                let state = MVL_Listing_Manager.wpmedia.state();
                let selection = state.get('selection');
                let images = selection.toJSON().map(attachment => ({
                    id: attachment.id,
                    value: attachment.id,
                    url: attachment.sizes.large ? attachment.sizes.large.url : attachment.url,
                    name: attachment.filename,
                }));

                if (MVL_Listing_Manager.descriptionEditor) {
                    for (let i = 0; i < images.length; i++) {
                        MVL_Listing_Manager.descriptionEditor.insertContent('<img src="' + images[i].url + '" alt="' + images[i].name + '" />');
                    }
                } else {
                    if (MVL_Listing_Manager.descriptionCb) {
                        MVL_Listing_Manager.descriptionEditor.execCommand('Delete');
                        MVL_Listing_Manager.descriptionCb(images[0].url, { title: images[0].name });
                    }
                }
            }
        });

        MVL_Listing_Manager.wpmedia.on('close', function (e) {
            requestAnimationFrame(function () {
                MVL_Listing_Manager.descriptionWpMediaOpen = false;
                MVL_Listing_Manager.descriptionCb = null;
            });
        });
    }

})(jQuery); 