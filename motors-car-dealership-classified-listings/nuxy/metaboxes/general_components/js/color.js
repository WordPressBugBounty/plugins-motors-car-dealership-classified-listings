(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) {
  var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"];

  if (!it) {
    if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") {
      if (it) o = it;
      var i = 0;

      var F = function F() {};

      return {
        s: F,
        n: function n() {
          if (i >= o.length) return {
            done: true
          };
          return {
            done: false,
            value: o[i++]
          };
        },
        e: function e(_e) {
          throw _e;
        },
        f: F
      };
    }

    throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }

  var normalCompletion = true,
      didErr = false,
      err;
  return {
    s: function s() {
      it = it.call(o);
    },
    n: function n() {
      var step = it.next();
      normalCompletion = step.done;
      return step;
    },
    e: function e(_e2) {
      didErr = true;
      err = _e2;
    },
    f: function f() {
      try {
        if (!normalCompletion && it["return"] != null) it["return"]();
      } finally {
        if (didErr) throw err;
      }
    }
  };
}

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return _arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

Vue.component('wpcfto_color', {
  template: "\n        <div class=\"wpcfto_generic_field wpcfto_generic_field_color\">\n        \n            <wpcfto_fields_aside_before :fields=\"fields\" :field_label=\"field_label\"></wpcfto_fields_aside_before>\n            \n            <div class=\"wpcfto-field-content\">\n                        \n                <div class=\"stm_colorpicker_wrapper\" v-bind:class=\"['picker-position-' + position]\">\n\n                    <span v-bind:style=\"{'background-color': input_value}\" @click=\"$refs.field_name.focus()\"></span>\n    \n                    <input type=\"text\"\n                           v-bind:name=\"field_name\"\n                           v-bind:placeholder=\"field_label\"\n                           v-bind:id=\"field_id\"\n                           v-model=\"input_value\"\n                           ref=\"field_name\"\n                    />\n    \n                    <div @click=\"changeValueFormat\">\n                        <slider-picker ref=\"colorPicker\" v-model=\"value\"></slider-picker>\n                    </div>\n\n                      <a href=\"#\" @click=\"resetValue\" v-if=\"input_value\" class=\"wpcfto_generic_field_color__clear\">\n                        <i class=\"fa fa-times\"></i>\n                      </a>\n    \n                </div>\n            \n            </div>\n            \n            <wpcfto_fields_aside_after :fields=\"fields\"></wpcfto_fields_aside_after>\n            \n        </div>\n    ",
  props: ['fields', 'field_label', 'field_name', 'field_id', 'field_value', 'default_value'],
  components: {
    'slider-picker': VueColor.Chrome
  },
  data: function data() {
    return {
      input_value: '',
      position: 'bottom',
      current_format: 'hex',
      value: {
        hex: '#000000',
        rgba: {
          r: 0,
          g: 0,
          b: 0,
          a: 1
        },
        hsl: {
          a: 1,
          h: 1,
          l: 0,
          s: 1
        }
      }
    };
  },
  created: function created() {
    if (this.fields.position) {
      this.position = this.fields.position;
    }
  },
  mounted: function mounted() {
    var _this = this;

    this.$nextTick(function () {
      _this.updatePickerValue(_this.field_value);
    });
  },
  methods: {
    resetValue: function resetValue(event) {
      event.preventDefault();
      this.updateInputValue(this.default_value);
      this.updatePickerValue(this.default_value);
    },
    updatePickerValue: function updatePickerValue(value) {
      if (typeof value === 'string') {
        if (value.indexOf('rgb') !== -1) {
          var colors = value.replace('rgba(', '').slice(0, -1).split(',');
          this.current_format = 'rgba';
          this.value = {
            r: colors[0],
            g: colors[1],
            b: colors[2],
            a: colors[3],
            rgba: {
              r: colors[0],
              g: colors[1],
              b: colors[2],
              a: colors[3]
            }
          };
          this.$refs.colorPicker.fieldsIndex = 1;
        } else if (value.indexOf('hsl') !== -1) {
          var colors = value.replace('hsla(', '').slice(0, -1).split(',');
          this.current_format = 'hsl';
          this.value = {
            hsl: {
              h: colors[0],
              s: colors[1].replace('%', '') / 100,
              l: colors[2].replace('%', '') / 100,
              a: colors[3]
            }
          };
          this.$refs.colorPicker.fieldsIndex = 2;
        } else if (value.indexOf('#') !== -1) {
          this.current_format = 'hex';
          this.value = {
            hex: value
          };
          this.$refs.colorPicker.fieldsIndex = 0;
        }

        this.input_value = value;
      }
    },
    getValueFormat: function getValueFormat(value) {
      var format = 'hex';

      if (typeof value === 'string') {
        if (value.indexOf('rgb') !== -1) {
          format = 'rgba';
        } else if (value.indexOf('hsl') !== -1) {
          format = 'hsl';
        } else if (value.indexOf('#') !== -1) {
          format = 'hex';
        }
      }

      return format;
    },
    updateInputValue: function updateInputValue(value) {
      this.$set(this, 'input_value', value);
      this.$emit('wpcfto-get-value', value);
    },
    changeValueFormat: function changeValueFormat(event) {
      if (event.target.classList.contains('vc-chrome-toggle-icon') || event.target.closest('.vc-chrome-toggle-icon')) {
        var wrapper = event.target.closest('.vc-chrome-fields-wrap');

        if (wrapper) {
          var fields = wrapper.querySelectorAll('.vc-chrome-fields');

          var _iterator = _createForOfIteratorHelper(fields),
              _step;

          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var field = _step.value;

              if (field.style.display !== 'none') {
                var format = field.querySelector('.vc-input__label').textContent.toLowerCase().trim();
                var colorValue = '';

                switch (format) {
                  case 'hex':
                    this.current_format = 'hex';
                    colorValue = field.querySelector('.vc-input__input').getAttribute('aria-label');
                    break;

                  case 'r':
                    var rgba = field.querySelectorAll('.vc-input__input');
                    this.current_format = 'rgba';
                    colorValue = 'rgba(' + rgba[0].getAttribute('aria-label') + ',' + rgba[1].getAttribute('aria-label') + ',' + rgba[2].getAttribute('aria-label') + ',' + rgba[3].getAttribute('aria-label') + ')';
                    break;

                  case 'h':
                    var hsla = field.querySelectorAll('.vc-input__input');
                    this.current_format = 'hsla';
                    colorValue = 'hsla(' + hsla[0].getAttribute('aria-label') + ',' + hsla[1].getAttribute('aria-label') + ',' + hsla[2].getAttribute('aria-label') + ',' + hsla[3].getAttribute('aria-label') + ')';
                    break;
                }

                this.updateInputValue(colorValue);
                break;
              }
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        }
      }
    }
  },
  watch: {
    input_value: function input_value(value) {
      this.$emit('wpcfto-get-value', value);
    },
    value: function value(_value) {
      if (_value.rgba && _value.rgba.a !== undefined && _value.rgba.a < 1 && this.current_format === 'hex') {
        this.current_format = 'rgba';
      }

      switch (this.current_format) {
        case 'hex':
          this.updateInputValue(_value.hex);
          break;

        case 'rgba':
          this.updateInputValue('rgba(' + _value.rgba.r + ',' + _value.rgba.g + ',' + _value.rgba.b + ',' + _value.rgba.a + ')');
          break;

        case 'hsl':
          this.updateInputValue('hsla(' + Math.ceil(_value.hsl.h) + ',' + _value.hsl.s * 100 + '%,' + _value.hsl.l * 100 + '%,' + _value.hsl.a + ')');
          break;
      }
    }
  }
});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJfY3JlYXRlRm9yT2ZJdGVyYXRvckhlbHBlciIsIm8iLCJhbGxvd0FycmF5TGlrZSIsIml0IiwiU3ltYm9sIiwiaXRlcmF0b3IiLCJBcnJheSIsImlzQXJyYXkiLCJfdW5zdXBwb3J0ZWRJdGVyYWJsZVRvQXJyYXkiLCJsZW5ndGgiLCJpIiwiRiIsInMiLCJuIiwiZG9uZSIsInZhbHVlIiwiZSIsIl9lIiwiZiIsIlR5cGVFcnJvciIsIm5vcm1hbENvbXBsZXRpb24iLCJkaWRFcnIiLCJlcnIiLCJjYWxsIiwic3RlcCIsIm5leHQiLCJfZTIiLCJtaW5MZW4iLCJfYXJyYXlMaWtlVG9BcnJheSIsIk9iamVjdCIsInByb3RvdHlwZSIsInRvU3RyaW5nIiwic2xpY2UiLCJjb25zdHJ1Y3RvciIsIm5hbWUiLCJmcm9tIiwidGVzdCIsImFyciIsImxlbiIsImFycjIiLCJWdWUiLCJjb21wb25lbnQiLCJ0ZW1wbGF0ZSIsInByb3BzIiwiY29tcG9uZW50cyIsIlZ1ZUNvbG9yIiwiQ2hyb21lIiwiZGF0YSIsImlucHV0X3ZhbHVlIiwicG9zaXRpb24iLCJjdXJyZW50X2Zvcm1hdCIsImhleCIsInJnYmEiLCJyIiwiZyIsImIiLCJhIiwiaHNsIiwiaCIsImwiLCJjcmVhdGVkIiwiZmllbGRzIiwibW91bnRlZCIsIl90aGlzIiwiJG5leHRUaWNrIiwidXBkYXRlUGlja2VyVmFsdWUiLCJmaWVsZF92YWx1ZSIsIm1ldGhvZHMiLCJyZXNldFZhbHVlIiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsInVwZGF0ZUlucHV0VmFsdWUiLCJkZWZhdWx0X3ZhbHVlIiwiaW5kZXhPZiIsImNvbG9ycyIsInJlcGxhY2UiLCJzcGxpdCIsIiRyZWZzIiwiY29sb3JQaWNrZXIiLCJmaWVsZHNJbmRleCIsImdldFZhbHVlRm9ybWF0IiwiZm9ybWF0IiwiJHNldCIsIiRlbWl0IiwiY2hhbmdlVmFsdWVGb3JtYXQiLCJ0YXJnZXQiLCJjbGFzc0xpc3QiLCJjb250YWlucyIsImNsb3Nlc3QiLCJ3cmFwcGVyIiwicXVlcnlTZWxlY3RvckFsbCIsIl9pdGVyYXRvciIsIl9zdGVwIiwiZmllbGQiLCJzdHlsZSIsImRpc3BsYXkiLCJxdWVyeVNlbGVjdG9yIiwidGV4dENvbnRlbnQiLCJ0b0xvd2VyQ2FzZSIsInRyaW0iLCJjb2xvclZhbHVlIiwiZ2V0QXR0cmlidXRlIiwiaHNsYSIsIndhdGNoIiwiX3ZhbHVlIiwidW5kZWZpbmVkIiwiTWF0aCIsImNlaWwiXSwic291cmNlcyI6WyJmYWtlX2MzOGViZTgxLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIlwidXNlIHN0cmljdFwiO1xuXG5mdW5jdGlvbiBfY3JlYXRlRm9yT2ZJdGVyYXRvckhlbHBlcihvLCBhbGxvd0FycmF5TGlrZSkgeyB2YXIgaXQgPSB0eXBlb2YgU3ltYm9sICE9PSBcInVuZGVmaW5lZFwiICYmIG9bU3ltYm9sLml0ZXJhdG9yXSB8fCBvW1wiQEBpdGVyYXRvclwiXTsgaWYgKCFpdCkgeyBpZiAoQXJyYXkuaXNBcnJheShvKSB8fCAoaXQgPSBfdW5zdXBwb3J0ZWRJdGVyYWJsZVRvQXJyYXkobykpIHx8IGFsbG93QXJyYXlMaWtlICYmIG8gJiYgdHlwZW9mIG8ubGVuZ3RoID09PSBcIm51bWJlclwiKSB7IGlmIChpdCkgbyA9IGl0OyB2YXIgaSA9IDA7IHZhciBGID0gZnVuY3Rpb24gRigpIHt9OyByZXR1cm4geyBzOiBGLCBuOiBmdW5jdGlvbiBuKCkgeyBpZiAoaSA+PSBvLmxlbmd0aCkgcmV0dXJuIHsgZG9uZTogdHJ1ZSB9OyByZXR1cm4geyBkb25lOiBmYWxzZSwgdmFsdWU6IG9baSsrXSB9OyB9LCBlOiBmdW5jdGlvbiBlKF9lKSB7IHRocm93IF9lOyB9LCBmOiBGIH07IH0gdGhyb3cgbmV3IFR5cGVFcnJvcihcIkludmFsaWQgYXR0ZW1wdCB0byBpdGVyYXRlIG5vbi1pdGVyYWJsZSBpbnN0YW5jZS5cXG5JbiBvcmRlciB0byBiZSBpdGVyYWJsZSwgbm9uLWFycmF5IG9iamVjdHMgbXVzdCBoYXZlIGEgW1N5bWJvbC5pdGVyYXRvcl0oKSBtZXRob2QuXCIpOyB9IHZhciBub3JtYWxDb21wbGV0aW9uID0gdHJ1ZSwgZGlkRXJyID0gZmFsc2UsIGVycjsgcmV0dXJuIHsgczogZnVuY3Rpb24gcygpIHsgaXQgPSBpdC5jYWxsKG8pOyB9LCBuOiBmdW5jdGlvbiBuKCkgeyB2YXIgc3RlcCA9IGl0Lm5leHQoKTsgbm9ybWFsQ29tcGxldGlvbiA9IHN0ZXAuZG9uZTsgcmV0dXJuIHN0ZXA7IH0sIGU6IGZ1bmN0aW9uIGUoX2UyKSB7IGRpZEVyciA9IHRydWU7IGVyciA9IF9lMjsgfSwgZjogZnVuY3Rpb24gZigpIHsgdHJ5IHsgaWYgKCFub3JtYWxDb21wbGV0aW9uICYmIGl0W1wicmV0dXJuXCJdICE9IG51bGwpIGl0W1wicmV0dXJuXCJdKCk7IH0gZmluYWxseSB7IGlmIChkaWRFcnIpIHRocm93IGVycjsgfSB9IH07IH1cblxuZnVuY3Rpb24gX3Vuc3VwcG9ydGVkSXRlcmFibGVUb0FycmF5KG8sIG1pbkxlbikgeyBpZiAoIW8pIHJldHVybjsgaWYgKHR5cGVvZiBvID09PSBcInN0cmluZ1wiKSByZXR1cm4gX2FycmF5TGlrZVRvQXJyYXkobywgbWluTGVuKTsgdmFyIG4gPSBPYmplY3QucHJvdG90eXBlLnRvU3RyaW5nLmNhbGwobykuc2xpY2UoOCwgLTEpOyBpZiAobiA9PT0gXCJPYmplY3RcIiAmJiBvLmNvbnN0cnVjdG9yKSBuID0gby5jb25zdHJ1Y3Rvci5uYW1lOyBpZiAobiA9PT0gXCJNYXBcIiB8fCBuID09PSBcIlNldFwiKSByZXR1cm4gQXJyYXkuZnJvbShvKTsgaWYgKG4gPT09IFwiQXJndW1lbnRzXCIgfHwgL14oPzpVaXxJKW50KD86OHwxNnwzMikoPzpDbGFtcGVkKT9BcnJheSQvLnRlc3QobikpIHJldHVybiBfYXJyYXlMaWtlVG9BcnJheShvLCBtaW5MZW4pOyB9XG5cbmZ1bmN0aW9uIF9hcnJheUxpa2VUb0FycmF5KGFyciwgbGVuKSB7IGlmIChsZW4gPT0gbnVsbCB8fCBsZW4gPiBhcnIubGVuZ3RoKSBsZW4gPSBhcnIubGVuZ3RoOyBmb3IgKHZhciBpID0gMCwgYXJyMiA9IG5ldyBBcnJheShsZW4pOyBpIDwgbGVuOyBpKyspIHsgYXJyMltpXSA9IGFycltpXTsgfSByZXR1cm4gYXJyMjsgfVxuXG5WdWUuY29tcG9uZW50KCd3cGNmdG9fY29sb3InLCB7XG4gIHRlbXBsYXRlOiBcIlxcbiAgICAgICAgPGRpdiBjbGFzcz1cXFwid3BjZnRvX2dlbmVyaWNfZmllbGQgd3BjZnRvX2dlbmVyaWNfZmllbGRfY29sb3JcXFwiPlxcbiAgICAgICAgXFxuICAgICAgICAgICAgPHdwY2Z0b19maWVsZHNfYXNpZGVfYmVmb3JlIDpmaWVsZHM9XFxcImZpZWxkc1xcXCIgOmZpZWxkX2xhYmVsPVxcXCJmaWVsZF9sYWJlbFxcXCI+PC93cGNmdG9fZmllbGRzX2FzaWRlX2JlZm9yZT5cXG4gICAgICAgICAgICBcXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVxcXCJ3cGNmdG8tZmllbGQtY29udGVudFxcXCI+XFxuICAgICAgICAgICAgICAgICAgICAgICAgXFxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XFxcInN0bV9jb2xvcnBpY2tlcl93cmFwcGVyXFxcIiB2LWJpbmQ6Y2xhc3M9XFxcIlsncGlja2VyLXBvc2l0aW9uLScgKyBwb3NpdGlvbl1cXFwiPlxcblxcbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gdi1iaW5kOnN0eWxlPVxcXCJ7J2JhY2tncm91bmQtY29sb3InOiBpbnB1dF92YWx1ZX1cXFwiIEBjbGljaz1cXFwiJHJlZnMuZmllbGRfbmFtZS5mb2N1cygpXFxcIj48L3NwYW4+XFxuICAgIFxcbiAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XFxcInRleHRcXFwiXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgdi1iaW5kOm5hbWU9XFxcImZpZWxkX25hbWVcXFwiXFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgdi1iaW5kOnBsYWNlaG9sZGVyPVxcXCJmaWVsZF9sYWJlbFxcXCJcXG4gICAgICAgICAgICAgICAgICAgICAgICAgICB2LWJpbmQ6aWQ9XFxcImZpZWxkX2lkXFxcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgIHYtbW9kZWw9XFxcImlucHV0X3ZhbHVlXFxcIlxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlZj1cXFwiZmllbGRfbmFtZVxcXCJcXG4gICAgICAgICAgICAgICAgICAgIC8+XFxuICAgIFxcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBAY2xpY2s9XFxcImNoYW5nZVZhbHVlRm9ybWF0XFxcIj5cXG4gICAgICAgICAgICAgICAgICAgICAgICA8c2xpZGVyLXBpY2tlciByZWY9XFxcImNvbG9yUGlja2VyXFxcIiB2LW1vZGVsPVxcXCJ2YWx1ZVxcXCI+PC9zbGlkZXItcGlja2VyPlxcbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XFxuXFxuICAgICAgICAgICAgICAgICAgICAgIDxhIGhyZWY9XFxcIiNcXFwiIEBjbGljaz1cXFwicmVzZXRWYWx1ZVxcXCIgdi1pZj1cXFwiaW5wdXRfdmFsdWVcXFwiIGNsYXNzPVxcXCJ3cGNmdG9fZ2VuZXJpY19maWVsZF9jb2xvcl9fY2xlYXJcXFwiPlxcbiAgICAgICAgICAgICAgICAgICAgICAgIDxpIGNsYXNzPVxcXCJmYSBmYS10aW1lc1xcXCI+PC9pPlxcbiAgICAgICAgICAgICAgICAgICAgICA8L2E+XFxuICAgIFxcbiAgICAgICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICBcXG4gICAgICAgICAgICA8L2Rpdj5cXG4gICAgICAgICAgICBcXG4gICAgICAgICAgICA8d3BjZnRvX2ZpZWxkc19hc2lkZV9hZnRlciA6ZmllbGRzPVxcXCJmaWVsZHNcXFwiPjwvd3BjZnRvX2ZpZWxkc19hc2lkZV9hZnRlcj5cXG4gICAgICAgICAgICBcXG4gICAgICAgIDwvZGl2PlxcbiAgICBcIixcbiAgcHJvcHM6IFsnZmllbGRzJywgJ2ZpZWxkX2xhYmVsJywgJ2ZpZWxkX25hbWUnLCAnZmllbGRfaWQnLCAnZmllbGRfdmFsdWUnLCAnZGVmYXVsdF92YWx1ZSddLFxuICBjb21wb25lbnRzOiB7XG4gICAgJ3NsaWRlci1waWNrZXInOiBWdWVDb2xvci5DaHJvbWVcbiAgfSxcbiAgZGF0YTogZnVuY3Rpb24gZGF0YSgpIHtcbiAgICByZXR1cm4ge1xuICAgICAgaW5wdXRfdmFsdWU6ICcnLFxuICAgICAgcG9zaXRpb246ICdib3R0b20nLFxuICAgICAgY3VycmVudF9mb3JtYXQ6ICdoZXgnLFxuICAgICAgdmFsdWU6IHtcbiAgICAgICAgaGV4OiAnIzAwMDAwMCcsXG4gICAgICAgIHJnYmE6IHtcbiAgICAgICAgICByOiAwLFxuICAgICAgICAgIGc6IDAsXG4gICAgICAgICAgYjogMCxcbiAgICAgICAgICBhOiAxXG4gICAgICAgIH0sXG4gICAgICAgIGhzbDoge1xuICAgICAgICAgIGE6IDEsXG4gICAgICAgICAgaDogMSxcbiAgICAgICAgICBsOiAwLFxuICAgICAgICAgIHM6IDFcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH07XG4gIH0sXG4gIGNyZWF0ZWQ6IGZ1bmN0aW9uIGNyZWF0ZWQoKSB7XG4gICAgaWYgKHRoaXMuZmllbGRzLnBvc2l0aW9uKSB7XG4gICAgICB0aGlzLnBvc2l0aW9uID0gdGhpcy5maWVsZHMucG9zaXRpb247XG4gICAgfVxuICB9LFxuICBtb3VudGVkOiBmdW5jdGlvbiBtb3VudGVkKCkge1xuICAgIHZhciBfdGhpcyA9IHRoaXM7XG5cbiAgICB0aGlzLiRuZXh0VGljayhmdW5jdGlvbiAoKSB7XG4gICAgICBfdGhpcy51cGRhdGVQaWNrZXJWYWx1ZShfdGhpcy5maWVsZF92YWx1ZSk7XG4gICAgfSk7XG4gIH0sXG4gIG1ldGhvZHM6IHtcbiAgICByZXNldFZhbHVlOiBmdW5jdGlvbiByZXNldFZhbHVlKGV2ZW50KSB7XG4gICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgdGhpcy51cGRhdGVJbnB1dFZhbHVlKHRoaXMuZGVmYXVsdF92YWx1ZSk7XG4gICAgICB0aGlzLnVwZGF0ZVBpY2tlclZhbHVlKHRoaXMuZGVmYXVsdF92YWx1ZSk7XG4gICAgfSxcbiAgICB1cGRhdGVQaWNrZXJWYWx1ZTogZnVuY3Rpb24gdXBkYXRlUGlja2VyVmFsdWUodmFsdWUpIHtcbiAgICAgIGlmICh0eXBlb2YgdmFsdWUgPT09ICdzdHJpbmcnKSB7XG4gICAgICAgIGlmICh2YWx1ZS5pbmRleE9mKCdyZ2InKSAhPT0gLTEpIHtcbiAgICAgICAgICB2YXIgY29sb3JzID0gdmFsdWUucmVwbGFjZSgncmdiYSgnLCAnJykuc2xpY2UoMCwgLTEpLnNwbGl0KCcsJyk7XG4gICAgICAgICAgdGhpcy5jdXJyZW50X2Zvcm1hdCA9ICdyZ2JhJztcbiAgICAgICAgICB0aGlzLnZhbHVlID0ge1xuICAgICAgICAgICAgcjogY29sb3JzWzBdLFxuICAgICAgICAgICAgZzogY29sb3JzWzFdLFxuICAgICAgICAgICAgYjogY29sb3JzWzJdLFxuICAgICAgICAgICAgYTogY29sb3JzWzNdLFxuICAgICAgICAgICAgcmdiYToge1xuICAgICAgICAgICAgICByOiBjb2xvcnNbMF0sXG4gICAgICAgICAgICAgIGc6IGNvbG9yc1sxXSxcbiAgICAgICAgICAgICAgYjogY29sb3JzWzJdLFxuICAgICAgICAgICAgICBhOiBjb2xvcnNbM11cbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9O1xuICAgICAgICAgIHRoaXMuJHJlZnMuY29sb3JQaWNrZXIuZmllbGRzSW5kZXggPSAxO1xuICAgICAgICB9IGVsc2UgaWYgKHZhbHVlLmluZGV4T2YoJ2hzbCcpICE9PSAtMSkge1xuICAgICAgICAgIHZhciBjb2xvcnMgPSB2YWx1ZS5yZXBsYWNlKCdoc2xhKCcsICcnKS5zbGljZSgwLCAtMSkuc3BsaXQoJywnKTtcbiAgICAgICAgICB0aGlzLmN1cnJlbnRfZm9ybWF0ID0gJ2hzbCc7XG4gICAgICAgICAgdGhpcy52YWx1ZSA9IHtcbiAgICAgICAgICAgIGhzbDoge1xuICAgICAgICAgICAgICBoOiBjb2xvcnNbMF0sXG4gICAgICAgICAgICAgIHM6IGNvbG9yc1sxXS5yZXBsYWNlKCclJywgJycpIC8gMTAwLFxuICAgICAgICAgICAgICBsOiBjb2xvcnNbMl0ucmVwbGFjZSgnJScsICcnKSAvIDEwMCxcbiAgICAgICAgICAgICAgYTogY29sb3JzWzNdXG4gICAgICAgICAgICB9XG4gICAgICAgICAgfTtcbiAgICAgICAgICB0aGlzLiRyZWZzLmNvbG9yUGlja2VyLmZpZWxkc0luZGV4ID0gMjtcbiAgICAgICAgfSBlbHNlIGlmICh2YWx1ZS5pbmRleE9mKCcjJykgIT09IC0xKSB7XG4gICAgICAgICAgdGhpcy5jdXJyZW50X2Zvcm1hdCA9ICdoZXgnO1xuICAgICAgICAgIHRoaXMudmFsdWUgPSB7XG4gICAgICAgICAgICBoZXg6IHZhbHVlXG4gICAgICAgICAgfTtcbiAgICAgICAgICB0aGlzLiRyZWZzLmNvbG9yUGlja2VyLmZpZWxkc0luZGV4ID0gMDtcbiAgICAgICAgfVxuXG4gICAgICAgIHRoaXMuaW5wdXRfdmFsdWUgPSB2YWx1ZTtcbiAgICAgIH1cbiAgICB9LFxuICAgIGdldFZhbHVlRm9ybWF0OiBmdW5jdGlvbiBnZXRWYWx1ZUZvcm1hdCh2YWx1ZSkge1xuICAgICAgdmFyIGZvcm1hdCA9ICdoZXgnO1xuXG4gICAgICBpZiAodHlwZW9mIHZhbHVlID09PSAnc3RyaW5nJykge1xuICAgICAgICBpZiAodmFsdWUuaW5kZXhPZigncmdiJykgIT09IC0xKSB7XG4gICAgICAgICAgZm9ybWF0ID0gJ3JnYmEnO1xuICAgICAgICB9IGVsc2UgaWYgKHZhbHVlLmluZGV4T2YoJ2hzbCcpICE9PSAtMSkge1xuICAgICAgICAgIGZvcm1hdCA9ICdoc2wnO1xuICAgICAgICB9IGVsc2UgaWYgKHZhbHVlLmluZGV4T2YoJyMnKSAhPT0gLTEpIHtcbiAgICAgICAgICBmb3JtYXQgPSAnaGV4JztcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICByZXR1cm4gZm9ybWF0O1xuICAgIH0sXG4gICAgdXBkYXRlSW5wdXRWYWx1ZTogZnVuY3Rpb24gdXBkYXRlSW5wdXRWYWx1ZSh2YWx1ZSkge1xuICAgICAgdGhpcy4kc2V0KHRoaXMsICdpbnB1dF92YWx1ZScsIHZhbHVlKTtcbiAgICAgIHRoaXMuJGVtaXQoJ3dwY2Z0by1nZXQtdmFsdWUnLCB2YWx1ZSk7XG4gICAgfSxcbiAgICBjaGFuZ2VWYWx1ZUZvcm1hdDogZnVuY3Rpb24gY2hhbmdlVmFsdWVGb3JtYXQoZXZlbnQpIHtcbiAgICAgIGlmIChldmVudC50YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCd2Yy1jaHJvbWUtdG9nZ2xlLWljb24nKSB8fCBldmVudC50YXJnZXQuY2xvc2VzdCgnLnZjLWNocm9tZS10b2dnbGUtaWNvbicpKSB7XG4gICAgICAgIHZhciB3cmFwcGVyID0gZXZlbnQudGFyZ2V0LmNsb3Nlc3QoJy52Yy1jaHJvbWUtZmllbGRzLXdyYXAnKTtcblxuICAgICAgICBpZiAod3JhcHBlcikge1xuICAgICAgICAgIHZhciBmaWVsZHMgPSB3cmFwcGVyLnF1ZXJ5U2VsZWN0b3JBbGwoJy52Yy1jaHJvbWUtZmllbGRzJyk7XG5cbiAgICAgICAgICB2YXIgX2l0ZXJhdG9yID0gX2NyZWF0ZUZvck9mSXRlcmF0b3JIZWxwZXIoZmllbGRzKSxcbiAgICAgICAgICAgICAgX3N0ZXA7XG5cbiAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgZm9yIChfaXRlcmF0b3IucygpOyAhKF9zdGVwID0gX2l0ZXJhdG9yLm4oKSkuZG9uZTspIHtcbiAgICAgICAgICAgICAgdmFyIGZpZWxkID0gX3N0ZXAudmFsdWU7XG5cbiAgICAgICAgICAgICAgaWYgKGZpZWxkLnN0eWxlLmRpc3BsYXkgIT09ICdub25lJykge1xuICAgICAgICAgICAgICAgIHZhciBmb3JtYXQgPSBmaWVsZC5xdWVyeVNlbGVjdG9yKCcudmMtaW5wdXRfX2xhYmVsJykudGV4dENvbnRlbnQudG9Mb3dlckNhc2UoKS50cmltKCk7XG4gICAgICAgICAgICAgICAgdmFyIGNvbG9yVmFsdWUgPSAnJztcblxuICAgICAgICAgICAgICAgIHN3aXRjaCAoZm9ybWF0KSB7XG4gICAgICAgICAgICAgICAgICBjYXNlICdoZXgnOlxuICAgICAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRfZm9ybWF0ID0gJ2hleCc7XG4gICAgICAgICAgICAgICAgICAgIGNvbG9yVmFsdWUgPSBmaWVsZC5xdWVyeVNlbGVjdG9yKCcudmMtaW5wdXRfX2lucHV0JykuZ2V0QXR0cmlidXRlKCdhcmlhLWxhYmVsJyk7XG4gICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgICAgICAgICAgICBjYXNlICdyJzpcbiAgICAgICAgICAgICAgICAgICAgdmFyIHJnYmEgPSBmaWVsZC5xdWVyeVNlbGVjdG9yQWxsKCcudmMtaW5wdXRfX2lucHV0Jyk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudF9mb3JtYXQgPSAncmdiYSc7XG4gICAgICAgICAgICAgICAgICAgIGNvbG9yVmFsdWUgPSAncmdiYSgnICsgcmdiYVswXS5nZXRBdHRyaWJ1dGUoJ2FyaWEtbGFiZWwnKSArICcsJyArIHJnYmFbMV0uZ2V0QXR0cmlidXRlKCdhcmlhLWxhYmVsJykgKyAnLCcgKyByZ2JhWzJdLmdldEF0dHJpYnV0ZSgnYXJpYS1sYWJlbCcpICsgJywnICsgcmdiYVszXS5nZXRBdHRyaWJ1dGUoJ2FyaWEtbGFiZWwnKSArICcpJztcbiAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgICAgICAgICAgIGNhc2UgJ2gnOlxuICAgICAgICAgICAgICAgICAgICB2YXIgaHNsYSA9IGZpZWxkLnF1ZXJ5U2VsZWN0b3JBbGwoJy52Yy1pbnB1dF9faW5wdXQnKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50X2Zvcm1hdCA9ICdoc2xhJztcbiAgICAgICAgICAgICAgICAgICAgY29sb3JWYWx1ZSA9ICdoc2xhKCcgKyBoc2xhWzBdLmdldEF0dHJpYnV0ZSgnYXJpYS1sYWJlbCcpICsgJywnICsgaHNsYVsxXS5nZXRBdHRyaWJ1dGUoJ2FyaWEtbGFiZWwnKSArICcsJyArIGhzbGFbMl0uZ2V0QXR0cmlidXRlKCdhcmlhLWxhYmVsJykgKyAnLCcgKyBoc2xhWzNdLmdldEF0dHJpYnV0ZSgnYXJpYS1sYWJlbCcpICsgJyknO1xuICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB0aGlzLnVwZGF0ZUlucHV0VmFsdWUoY29sb3JWYWx1ZSk7XG4gICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9IGNhdGNoIChlcnIpIHtcbiAgICAgICAgICAgIF9pdGVyYXRvci5lKGVycik7XG4gICAgICAgICAgfSBmaW5hbGx5IHtcbiAgICAgICAgICAgIF9pdGVyYXRvci5mKCk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICB9LFxuICB3YXRjaDoge1xuICAgIGlucHV0X3ZhbHVlOiBmdW5jdGlvbiBpbnB1dF92YWx1ZSh2YWx1ZSkge1xuICAgICAgdGhpcy4kZW1pdCgnd3BjZnRvLWdldC12YWx1ZScsIHZhbHVlKTtcbiAgICB9LFxuICAgIHZhbHVlOiBmdW5jdGlvbiB2YWx1ZShfdmFsdWUpIHtcbiAgICAgIGlmIChfdmFsdWUucmdiYSAmJiBfdmFsdWUucmdiYS5hICE9PSB1bmRlZmluZWQgJiYgX3ZhbHVlLnJnYmEuYSA8IDEgJiYgdGhpcy5jdXJyZW50X2Zvcm1hdCA9PT0gJ2hleCcpIHtcbiAgICAgICAgdGhpcy5jdXJyZW50X2Zvcm1hdCA9ICdyZ2JhJztcbiAgICAgIH1cblxuICAgICAgc3dpdGNoICh0aGlzLmN1cnJlbnRfZm9ybWF0KSB7XG4gICAgICAgIGNhc2UgJ2hleCc6XG4gICAgICAgICAgdGhpcy51cGRhdGVJbnB1dFZhbHVlKF92YWx1ZS5oZXgpO1xuICAgICAgICAgIGJyZWFrO1xuXG4gICAgICAgIGNhc2UgJ3JnYmEnOlxuICAgICAgICAgIHRoaXMudXBkYXRlSW5wdXRWYWx1ZSgncmdiYSgnICsgX3ZhbHVlLnJnYmEuciArICcsJyArIF92YWx1ZS5yZ2JhLmcgKyAnLCcgKyBfdmFsdWUucmdiYS5iICsgJywnICsgX3ZhbHVlLnJnYmEuYSArICcpJyk7XG4gICAgICAgICAgYnJlYWs7XG5cbiAgICAgICAgY2FzZSAnaHNsJzpcbiAgICAgICAgICB0aGlzLnVwZGF0ZUlucHV0VmFsdWUoJ2hzbGEoJyArIE1hdGguY2VpbChfdmFsdWUuaHNsLmgpICsgJywnICsgX3ZhbHVlLmhzbC5zICogMTAwICsgJyUsJyArIF92YWx1ZS5oc2wubCAqIDEwMCArICclLCcgKyBfdmFsdWUuaHNsLmEgKyAnKScpO1xuICAgICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgIH1cbiAgfVxufSk7Il0sIm1hcHBpbmdzIjoiQUFBQTs7QUFFQSxTQUFTQSwwQkFBVCxDQUFvQ0MsQ0FBcEMsRUFBdUNDLGNBQXZDLEVBQXVEO0VBQUUsSUFBSUMsRUFBRSxHQUFHLE9BQU9DLE1BQVAsS0FBa0IsV0FBbEIsSUFBaUNILENBQUMsQ0FBQ0csTUFBTSxDQUFDQyxRQUFSLENBQWxDLElBQXVESixDQUFDLENBQUMsWUFBRCxDQUFqRTs7RUFBaUYsSUFBSSxDQUFDRSxFQUFMLEVBQVM7SUFBRSxJQUFJRyxLQUFLLENBQUNDLE9BQU4sQ0FBY04sQ0FBZCxNQUFxQkUsRUFBRSxHQUFHSywyQkFBMkIsQ0FBQ1AsQ0FBRCxDQUFyRCxLQUE2REMsY0FBYyxJQUFJRCxDQUFsQixJQUF1QixPQUFPQSxDQUFDLENBQUNRLE1BQVQsS0FBb0IsUUFBNUcsRUFBc0g7TUFBRSxJQUFJTixFQUFKLEVBQVFGLENBQUMsR0FBR0UsRUFBSjtNQUFRLElBQUlPLENBQUMsR0FBRyxDQUFSOztNQUFXLElBQUlDLENBQUMsR0FBRyxTQUFTQSxDQUFULEdBQWEsQ0FBRSxDQUF2Qjs7TUFBeUIsT0FBTztRQUFFQyxDQUFDLEVBQUVELENBQUw7UUFBUUUsQ0FBQyxFQUFFLFNBQVNB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
},{}]},{},[1])