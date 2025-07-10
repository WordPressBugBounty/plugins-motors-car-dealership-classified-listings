(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
"use strict";

Vue.component('mvl_page_generator', {
  props: ['field_data'],
  components: [],
  data: function data() {
    return {
      loading: false
    };
  },
  methods: {
    generatePages: function generatePages() {
      var vm = this;
      if (vm.loading) return false;
      vm.loading = true;
      this.$http.post(ajaxurl + '?action=wpcfto_generate_pages', JSON.stringify(vm.field_data)).then(function (data) {
        location.reload();
        vm.loading = false;
      });
    }
  }
});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJWdWUiLCJjb21wb25lbnQiLCJwcm9wcyIsImNvbXBvbmVudHMiLCJkYXRhIiwibG9hZGluZyIsIm1ldGhvZHMiLCJnZW5lcmF0ZVBhZ2VzIiwidm0iLCIkaHR0cCIsInBvc3QiLCJhamF4dXJsIiwiSlNPTiIsInN0cmluZ2lmeSIsImZpZWxkX2RhdGEiLCJ0aGVuIiwibG9jYXRpb24iLCJyZWxvYWQiXSwic291cmNlcyI6WyJmYWtlX2ZiMjkxODMuanMiXSwic291cmNlc0NvbnRlbnQiOlsiXCJ1c2Ugc3RyaWN0XCI7XG5cblZ1ZS5jb21wb25lbnQoJ212bF9wYWdlX2dlbmVyYXRvcicsIHtcbiAgcHJvcHM6IFsnZmllbGRfZGF0YSddLFxuICBjb21wb25lbnRzOiBbXSxcbiAgZGF0YTogZnVuY3Rpb24gZGF0YSgpIHtcbiAgICByZXR1cm4ge1xuICAgICAgbG9hZGluZzogZmFsc2VcbiAgICB9O1xuICB9LFxuICBtZXRob2RzOiB7XG4gICAgZ2VuZXJhdGVQYWdlczogZnVuY3Rpb24gZ2VuZXJhdGVQYWdlcygpIHtcbiAgICAgIHZhciB2bSA9IHRoaXM7XG4gICAgICBpZiAodm0ubG9hZGluZykgcmV0dXJuIGZhbHNlO1xuICAgICAgdm0ubG9hZGluZyA9IHRydWU7XG4gICAgICB0aGlzLiRodHRwLnBvc3QoYWpheHVybCArICc/YWN0aW9uPXdwY2Z0b19nZW5lcmF0ZV9wYWdlcycsIEpTT04uc3RyaW5naWZ5KHZtLmZpZWxkX2RhdGEpKS50aGVuKGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xuICAgICAgICB2bS5sb2FkaW5nID0gZmFsc2U7XG4gICAgICB9KTtcbiAgICB9XG4gIH1cbn0pOyJdLCJtYXBwaW5ncyI6IkFBQUEsWUFBWTs7QUFFWkEsR0FBRyxDQUFDQyxTQUFTLENBQUMsb0JBQW9CLEVBQUU7RUFDbENDLEtBQUssRUFBRSxDQUFDLFlBQVksQ0FBQztFQUNyQkMsVUFBVSxFQUFFLEVBQUU7RUFDZEMsSUFBSSxFQUFFLFNBQVNBLElBQUlBLENBQUEsRUFBRztJQUNwQixPQUFPO01BQ0xDLE9BQU8sRUFBRTtJQUNYLENBQUM7RUFDSCxDQUFDO0VBQ0RDLE9BQU8sRUFBRTtJQUNQQyxhQUFhLEVBQUUsU0FBU0EsYUFBYUEsQ0FBQSxFQUFHO01BQ3RDLElBQUlDLEVBQUUsR0FBRyxJQUFJO01BQ2IsSUFBSUEsRUFBRSxDQUFDSCxPQUFPLEVBQUUsT0FBTyxLQUFLO01BQzVCRyxFQUFFLENBQUNILE9BQU8sR0FBRyxJQUFJO01BQ2pCLElBQUksQ0FBQ0ksS0FBSyxDQUFDQyxJQUFJLENBQUNDLE9BQU8sR0FBRywrQkFBK0IsRUFBRUMsSUFBSSxDQUFDQyxTQUFTLENBQUNMLEVBQUUsQ0FBQ00sVUFBVSxDQUFDLENBQUMsQ0FBQ0MsSUFBSSxDQUFDLFVBQVVYLElBQUksRUFBRTtRQUM3R1ksUUFBUSxDQUFDQyxNQUFNLENBQUMsQ0FBQztRQUNqQlQsRUFBRSxDQUFDSCxPQUFPLEdBQUcsS0FBSztNQUNwQixDQUFDLENBQUM7SUFDSjtFQUNGO0FBQ0YsQ0FBQyxDQUFDIiwiaWdub3JlTGlzdCI6W119
},{}]},{},[1])