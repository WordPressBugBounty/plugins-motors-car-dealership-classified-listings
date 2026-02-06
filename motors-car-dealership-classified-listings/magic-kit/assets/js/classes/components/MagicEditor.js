class MagicEditor extends MagicTextarea {
    wpmedia = null;
    initialized = false;

    openWPMedia() {
        this.current = this;
        this.wpmedia.open();
    }

    mediaSelected() {
        let ths = this;
        let selection = ths.wpmedia.state().get('selection');
        let images = selection.toJSON().map(attachment => ({
            id: attachment.id,
            value: attachment.id,
            url: attachment.sizes.large ? attachment.sizes.large.url : attachment.url,
            name: attachment.filename,
        }));

        if (ths.editor) {
            for (let i = 0; i < images.length; i++) {
                this.data.image = images[i];
                ths.editor.insertContent(ths.__replace_vars(this.data.media.image.html));
            }
            this.data.image = false;
        }
    }

    value(newValue) {
        if (newValue !== undefined && newValue !== null) {
            this.editor.setContent(newValue);
        }
        return super.value(newValue);
    }

    init() {
        let ths = this;
        if (!this.inititialized) {
            this.wpmedia = wp.media({
                multiple: false,
                library: {
                    type: 'image',
                },
            });
        }
        this.wpmedia.on('select', ths.mediaSelected.bind(ths));
        this.data.settings = this.data.settings || {};

        tinymce.init(jshelper.recursive.merge.objects({
            selector: ths.selector('area'),
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
                'template', 'codesample', 'visualchars', 'nonbreaking', 'pagebreak',
                'quickbars', 'save', 'directionality'
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
            setup: function (editor) {
                ths.editor = editor;
                let events = ['change', 'input', 'paste', 'keydown', 'click', 'blur'];

                for (let event of events) {
                    editor.on(event, function (event) {
                        ths.node('area').value = editor.getContent();
                        ths._onchange(event);
                    });
                }

                editor.on('init', function () {
                    ths.editor.setContent(ths.options.value);
                    ths.node('area').value = ths.editor.getContent();
                    ths.options.value = ths.editor.getContent();
                    ths.data.value = ths.editor.getContent();
                });

                if (ths.data['smart-tag']) {

                    editor.on('click', function (event) {
                        ths.data['smart-tag'].remove('choices');
                    });

                    editor.on('keydown', function (event) {
                        if (event.key === 'Escape') {
                            ths.data['smart-tag'].remove('choices');
                        }

                        if (event.key === '{') {
                            ths.data['smart-tag'].remove('choices');
                            requestAnimationFrame(() => {
                                document.body.insertAdjacentHTML('beforeend', ths.data['smart-tag'].choicesHTML());
                                document.querySelector(ths.data['smart-tag'].selector('choice')).classList.add('active');
                                ths.data['smart-tag'].node('choices').style.position = 'absolute';
                                ths.data['smart-tag'].node('choices').style.top = ths.getCaretAbsPosition().y - ths.data['smart-tag'].node('choices').getBoundingClientRect().height + 'px';
                                ths.data['smart-tag'].node('choices').style.left = ths.getCaretAbsPosition().x + 'px';
                            });
                        }

                        ths.data['smart-tag']._keydown_by_document(event);

                        requestAnimationFrame(() => {
                            if (ths.getCharsBeforeCaret(1) !== '{') {
                                ths.data['smart-tag'].remove('choices');
                            }
                        });

                    });
                }

                editor.ui.registry.addMenuItem('wpimage', {
                    text: 'Image...',
                    icon: 'image',
                    onAction: ths.openWPMedia.bind(ths)
                });

                editor.ui.registry.addButton('customInsertImage', {
                    text: '',
                    icon: 'image',
                    onAction: ths.openWPMedia.bind(ths)
                });
            },
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
        }, ths.data.settings));
    }

    getCaretAbsPosition() {
        let editor = this.editor;
        let doc = editor.getDoc();
        let rng = editor.selection.getRng();
        let rect = null;

        if (rng && rng.getClientRects && rng.getClientRects().length) {
            rect = rng.getClientRects()[0];
        } else if (rng) {
            const saved = rng.cloneRange();
            const span = doc.createElement('span');
            span.appendChild(doc.createTextNode('\u200b'));
            rng.insertNode(span);
            rect = span.getBoundingClientRect();
            span.parentNode.removeChild(span);
            editor.selection.setRng(saved);
        } else {
            return null;
        }

        let x, y;

        if (!editor.inline) {
            let iframe = editor.iframeElement || editor.getContentAreaContainer().querySelector('iframe');
            let iframeRect = iframe.getBoundingClientRect();
            x = (window.pageXOffset || window.scrollX || 0) + iframeRect.left + rect.left;
            y = (window.pageYOffset || window.scrollY || 0) + iframeRect.top + rect.top;
        } else {
            x = (window.pageXOffset || window.scrollX || 0) + rect.left;
            y = (window.pageYOffset || window.scrollY || 0) + rect.top;
        }

        return { x: x, y: y };
    }

    getCharsBeforeCaret(count = 2) {
        const editor = this.editor;
        const doc = editor.getDoc();
        const selRange = editor.selection.getRng();

        if (!selRange) return '';

        const r = doc.createRange();
        r.setStart(editor.getBody(), 0);
        r.setEnd(selRange.startContainer, selRange.startOffset);

        const text = r.toString();
        return text.slice(-count);
    }

    deleteCharsBeforeCaret(count = 2) {
        let editor = this.editor;
        let doc = editor.getDoc();
        let rng = editor.selection.getRng();

        if (!rng) return;

        if (!rng.collapsed) {
            editor.execCommand('Delete');
            return;
        }

        let endNode = rng.startContainer;
        let endOffset = rng.startOffset;

        let remaining = count;

        function rightmostText(node) {
            while (node && node.nodeType === 1 && node.lastChild) {
                node = node.lastChild;
            }
            return node;
        }

        function prevNode(node) {
            if (!node) return null;
            if (node.previousSibling) {
                return rightmostText(node.previousSibling);
            }
            let parent = node.parentNode;
            while (parent && !parent.previousSibling) {
                node = parent;
                parent = parent.parentNode;
            }
            if (!parent) return null;
            return rightmostText(parent.previousSibling);
        }

        function normalizeToText(node, offset) {
            if (!node) return { node: null, offset: 0 };
            if (node.nodeType === 3) return { node, offset };
            if (node.childNodes && node.childNodes.length) {
                if (offset > 0) {
                    node = node.childNodes[offset - 1];
                    node = rightmostText(node);
                    return node && node.nodeType === 3 ? { node, offset: node.nodeValue.length } : { node: null, offset: 0 };
                } else {
                    node = prevNode(node);
                    return node && node.nodeType === 3 ? { node, offset: node.nodeValue.length } : { node: null, offset: 0 };
                }
            } else {
                node = prevNode(node);
                return node && node.nodeType === 3 ? { node, offset: node.nodeValue.length } : { node: null, offset: 0 };
            }
        }

        let pos = normalizeToText(endNode, endOffset);
        let startNode = pos.node;
        let startOffset = pos.offset;

        if (!startNode) return;

        while (remaining > 0 && startNode) {
            if (startNode.nodeType === 3 && startOffset > 0) {
                let step = Math.min(startOffset, remaining);
                startOffset -= step;
                remaining -= step;
                if (remaining === 0) break;
            }
            if (startOffset === 0) {
                startNode = prevNode(startNode);
                startOffset = startNode && startNode.nodeType === 3 ? startNode.nodeValue.length : 0;
                if (!startNode) break;
            }
        }

        let delRange = doc.createRange();
        delRange.setStart(startNode || editor.getBody(), startNode ? startOffset : 0);
        delRange.setEnd(endNode, endOffset);
        delRange.deleteContents();

        let collapse = doc.createRange();
        collapse.setStart(delRange.startContainer, delRange.startOffset);
        collapse.collapse(true);
        editor.selection.setRng(collapse);
    }

    __options() {
        return {
            ...super.__options(),
            media: {
                image: {
                    html: '<img width="100%" style="max-width: 100%;" src="{{image.url}}" alt="{{image.name}}" />',
                },
            }
        };
    }
}

MagicAPI.add.component('editor', MagicEditor);
