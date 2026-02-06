jshelper = {
    closest: function (element, selector) {
        let parent = element.parentElement;
        let closest = parent && parent.closest(selector) ? parent.closest(selector) : false;
        let result = false;

        if (closest) {
            result = closest;
        } else {
            if (element.matches && element.matches(selector)) {
                result = element;
            } else if (element.msMatchesSelector && element.msMatchesSelector(selector)) {
                result = element;
            } else if (element.webkitMatchesSelector && element.webkitMatchesSelector(selector)) {
                result = element;
            }

        }

        return result;
    },

    addclass: function (element, className, addcb) {
        if (element.classList) {
            element.classList.add(className);
            if (addcb) {
                addcb(element);
            }
        }

        return element;
    },

    removeclass: function (element, className, removecb) {
        if (element.classList) {
            element.classList.remove(className);
            if (removecb) {
                removecb(element);
            }
        }

        return this;
    },

    event: function (event, selector, callback) {
        document.addEventListener(event, function (e) {
            let closest = jshelper.closest(e.target, selector);
            if (closest) {
                callback(e, closest, event);
            }
        });
    },

    absolute: {
        offset: {
            of: {
                node: function (el, box = 'padding') {
                    if (!el) return { x: 0, y: 0 };

                    const doc = el.ownerDocument || document;
                    const win = doc.defaultView || window;

                    const rect = el.getBoundingClientRect();
                    let x = (win.pageXOffset || doc.documentElement.scrollLeft || 0) + rect.left;
                    let y = (win.pageYOffset || doc.documentElement.scrollTop || 0) + rect.top;

                    const frameEl = win.frameElement;
                    if (frameEl) {
                        const iframeRect = frameEl.getBoundingClientRect();
                        x += (window.pageXOffset || document.documentElement.scrollLeft || 0) + iframeRect.left;
                        y += (window.pageYOffset || document.documentElement.scrollTop || 0) + iframeRect.top;
                    }

                    if (box !== 'content') {
                        const cs = win.getComputedStyle(el);
                        const bl = parseFloat(cs.borderLeftWidth) || 0;
                        const bt = parseFloat(cs.borderTopWidth) || 0;
                        const pl = parseFloat(cs.paddingLeft) || 0;
                        const pt = parseFloat(cs.paddingTop) || 0;
                        const pr = parseFloat(cs.paddingRight) || 0;
                        const pb = parseFloat(cs.paddingBottom) || 0;

                        if (box === 'padding') {
                            x += pl + pr; y += pt + pb;
                        }
                    }

                    return { x, y };
                },
                caret: function (el) {
                    if (!el || el.selectionStart == null) return null;

                    const doc = el.ownerDocument || document;
                    const win = doc.defaultView || window;
                    const cs = win.getComputedStyle(el);
                    const isTextarea = el.nodeName === 'TEXTAREA';

                    const mirror = doc.createElement('div');
                    mirror.style.position = 'absolute';
                    mirror.style.visibility = 'hidden';
                    mirror.style.whiteSpace = isTextarea ? 'pre-wrap' : 'pre';
                    mirror.style.wordWrap = isTextarea ? 'break-word' : 'normal';
                    mirror.style.overflow = 'hidden';
                    mirror.style.boxSizing = cs.boxSizing;
                    mirror.style.fontFamily = cs.fontFamily;
                    mirror.style.fontSize = cs.fontSize;
                    mirror.style.fontWeight = cs.fontWeight;
                    mirror.style.fontStyle = cs.fontStyle;
                    mirror.style.letterSpacing = cs.letterSpacing;
                    mirror.style.textTransform = cs.textTransform;
                    mirror.style.textIndent = cs.textIndent;
                    mirror.style.textDecoration = cs.textDecoration;
                    mirror.style.lineHeight = cs.lineHeight;
                    mirror.style.padding = cs.padding;
                    mirror.style.border = cs.border;
                    mirror.style.direction = cs.direction;
                    mirror.style.tabSize = cs.tabSize || '8';
                    mirror.style.width = isTextarea ? cs.width : 'auto';
                    mirror.style.left = '-9999px';
                    mirror.style.top = '0';

                    const start = el.selectionStart;
                    let before = el.value.slice(0, start);

                    if (isTextarea && before.endsWith('\n')) before += ' ';

                    mirror.textContent = before;

                    const marker = doc.createElement('span');
                    marker.textContent = '\u200b';
                    mirror.appendChild(marker);

                    doc.body.appendChild(mirror);

                    const elRect = el.getBoundingClientRect();
                    const mirrorRect = mirror.getBoundingClientRect();
                    const caretRect = marker.getBoundingClientRect();

                    let x = elRect.left + (win.pageXOffset || doc.documentElement.scrollLeft || 0)
                        + (caretRect.left - mirrorRect.left) - el.scrollLeft;
                    let y = elRect.top + (win.pageYOffset || doc.documentElement.scrollTop || 0)
                        + (caretRect.top - mirrorRect.top) - el.scrollTop;

                    const frameEl = win.frameElement;
                    if (frameEl) {
                        const iframeRect = frameEl.getBoundingClientRect();
                        x += iframeRect.left + window.pageXOffset;
                        y += iframeRect.top + window.pageYOffset;
                    }

                    doc.body.removeChild(mirror);
                    return { x, y };
                }
            },
        },

    },

    convert: {
        object: {
            to: {
                style: function (style) {
                    let styleString = '';
                    for (let key in style) {
                        styleString += `${key}: ${style[key]}; `;
                    }

                    return styleString;
                },
                attrs: function (object) {
                    let attrs = '';
                    for (let key in object) {
                        if (typeof object[key] === 'boolean' && object[key] === true || typeof object[key] !== 'boolean') {
                            attrs += `${key}="${object[key]}" `;
                        }
                    }

                    return attrs;
                },
            }
        },
        string: {
            to: {
                dom: function (string) {
                    const template = document.createElement('template');
                    template.innerHTML = string.trim();
                    return template.content.firstChild;
                }
            }
        }
    },

    get: {
        object: {
            value: function (object, path, defaultValue) {
                if (!object || typeof path !== 'string') {
                    return defaultValue;
                }

                const keys = path.split('.');

                let result = object;
                for (const key of keys) {
                    if (result && Object.prototype.hasOwnProperty.call(result, key)) {
                        result = result[key];
                    } else {
                        return defaultValue;
                    }
                }

                return result;
            },
            class: {
                name: function (object) {
                    return (object && (object.displayName || object.name)) || (object && object.prototype && object.prototype.constructor && object.prototype.constructor.name) || '';
                }
            }
        },
        chars: {
            before: {
                caret: function (el, count = 2) {
                    const start = el.selectionStart ?? 0;
                    const value = el.value ?? '';
                    return value.slice(Math.max(0, start - count), start);
                }
            }
        },
    },

    recursive: {
        merge: {
            objects: function (target, source) {
                for (let key in source) {
                    if (typeof source[key] === 'object' && source[key] !== null) {

                        const isClassInstance = source[key].constructor &&
                            source[key].constructor !== Object &&
                            source[key].constructor.name !== 'Object';

                        if (isClassInstance) {
                            target[key] = source[key];
                        } else {
                            if (!target[key]) {
                                target[key] = {};
                            }
                            target[key] = jshelper.recursive.merge.objects(target[key], source[key]);
                        }
                    } else {
                        target[key] = source[key];
                    }
                }

                return target;
            },
        }
    },

    insert: {
        at: {
            caret: function (el, text) {
                el.focus();
                const start = el.selectionStart ?? 0;
                const end = el.selectionEnd ?? start;

                if (typeof el.setRangeText === 'function') {
                    el.setRangeText(text, start, end, 'end');
                } else {
                    el.value = el.value.slice(0, start) + text + el.value.slice(end);
                    const pos = start + text.length;
                    el.setSelectionRange(pos, pos);
                }

                el.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    },
    delete: {
        chars: {
            before: {
                caret: function (el, count = 2) {
                    if (!el) return;
                    el.focus();

                    const selStart = el.selectionStart ?? 0;
                    const selEnd = el.selectionEnd ?? selStart;

                    const caret = selStart;
                    if (caret <= 0) return;

                    const from = Math.max(0, caret - count);

                    if (typeof el.setRangeText === 'function') {
                        el.setSelectionRange(caret, caret);
                        el.setRangeText('', from, caret, 'end');
                    } else {
                        const v = el.value ?? '';
                        el.value = v.slice(0, from) + v.slice(caret);
                        el.setSelectionRange(from, from);
                    }

                    el.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        }
    },
    slugify: function (string) {
        return string.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
    },
    is: {
        out: {
            of: {
                parent: function (el, parent = el?.parentElement, { fully = false } = {}) {
                    if (!el || !parent) return false;

                    const pr = parent.getBoundingClientRect();
                    const er = el.getBoundingClientRect();
                    const cs = getComputedStyle(parent);

                    const bl = parseFloat(cs.borderLeftWidth) || 0;
                    const br = parseFloat(cs.borderRightWidth) || 0;
                    const bt = parseFloat(cs.borderTopWidth) || 0;
                    const bb = parseFloat(cs.borderBottomWidth) || 0;
                    const pl = parseFloat(cs.paddingLeft) || 0;
                    const prp = parseFloat(cs.paddingRight) || 0;
                    const pt = parseFloat(cs.paddingTop) || 0;
                    const pb = parseFloat(cs.paddingBottom) || 0;

                    const contentLeft = pr.left + bl + pl;
                    const contentTop = pr.top + bt + pt;
                    const contentRight = pr.right - br - prp;
                    const contentBottom = pr.bottom - bb - pb;

                    if (fully) {
                        return !(
                            er.left >= contentLeft &&
                            er.top >= contentTop &&
                            er.right <= contentRight &&
                            er.bottom <= contentBottom
                        );
                    } else {
                        return (
                            er.right <= contentLeft ||
                            er.left >= contentRight ||
                            er.bottom <= contentTop ||
                            er.top >= contentBottom
                        );
                    }
                }
            }
        }
    },
    scroll: {
        to: {
            element: function (el, viewInsideElement = null, scrollElement = null, {
                behavior = 'smooth',
                align = 'nearest',
                margin = 0
            } = {}) {
                if (!el) return;

                if (!viewInsideElement) {
                    viewInsideElement = el.parentElement;
                    while (viewInsideElement) {
                        const style = getComputedStyle(viewInsideElement);
                        const overflow = style.overflow + style.overflowY + style.overflowX;
                        if (/(auto|scroll)/.test(overflow)) break;
                        viewInsideElement = viewInsideElement.parentElement;
                    }
                    if (!viewInsideElement) viewInsideElement = document.documentElement;
                }

                if (!scrollElement) {
                    scrollElement = viewInsideElement;
                }

                const m = typeof margin === 'number'
                    ? { top: margin, right: margin, bottom: margin, left: margin }
                    : { top: margin.top || 0, right: margin.right || 0, bottom: margin.bottom || 0, left: margin.left || 0 };

                const pr = scrollElement.getBoundingClientRect();
                const er = el.getBoundingClientRect();
                const cs = getComputedStyle(scrollElement);

                const bl = parseFloat(cs.borderLeftWidth) || 0;
                const bt = parseFloat(cs.borderTopWidth) || 0;
                const br = parseFloat(cs.borderRightWidth) || 0;
                const bb = parseFloat(cs.borderBottomWidth) || 0;
                const pl = parseFloat(cs.paddingLeft) || 0;
                const pt = parseFloat(cs.paddingTop) || 0;
                const prp = parseFloat(cs.paddingRight) || 0;
                const pb = parseFloat(cs.paddingBottom) || 0;

                const contentLeft = pr.left + bl + pl + m.left;
                const contentTop = pr.top + bt + pt + m.top;
                const contentRight = pr.right - br - prp - m.right;
                const contentBottom = pr.bottom - bb - pb - m.bottom;

                const needScrollYStart = er.top < contentTop;
                const needScrollYEnd = er.bottom > contentBottom;
                const needScrollXStart = er.left < contentLeft;
                const needScrollXEnd = er.right > contentRight;

                const contentWidth = contentRight - contentLeft;
                const contentHeight = contentBottom - contentTop;

                const elHeight = er.bottom - er.top;
                const elWidth = er.right - er.left;

                let offsetTop = 0;
                let offsetLeft = 0;

                if (align === 'start') {
                    offsetTop = er.top - contentTop;
                    offsetLeft = er.left - contentLeft;
                } else if (align === 'end') {
                    offsetTop = er.bottom - contentBottom;
                    offsetLeft = er.right - contentRight;
                } else if (align === 'center') {
                    offsetTop = (er.top + elHeight / 2) - (contentTop + contentHeight / 2);
                    offsetLeft = (er.left + elWidth / 2) - (contentLeft + contentWidth / 2);
                } else {

                    if (needScrollYStart) offsetTop = er.top - contentTop;
                    else if (needScrollYEnd) offsetTop = er.bottom - contentBottom;

                    if (needScrollXStart) offsetLeft = er.left - contentLeft;
                    else if (needScrollXEnd) offsetLeft = er.right - contentRight;
                }

                const targetTop = scrollElement.scrollTop + offsetTop;
                const targetLeft = scrollElement.scrollLeft + offsetLeft;

                scrollElement.scrollTo({ top: targetTop, left: targetLeft, behavior });
            }
        }
    }
};