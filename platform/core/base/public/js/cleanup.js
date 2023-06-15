/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!***********************************************************!*\
  !*** ./platform/core/base/resources/assets/js/cleanup.js ***!
  \***********************************************************/


$(document).ready(function () {
  $(document).on('click', '.btn-trigger-cleanup', function (event) {
    event.preventDefault();
    $('#cleanup-modal').modal('show');
  });
  $(document).on('click', '#cleanup-submit-action', function (event) {
    event.preventDefault();
    event.stopPropagation();
    var _self = $(event.currentTarget);
    _self.addClass('button-loading');
    var $form = $('#form-cleanup-database');
    $.ajax({
      type: 'POST',
      cache: false,
      url: $form.prop('action'),
      data: new FormData($form[0]),
      contentType: false,
      processData: false,
      success: function success(res) {
        if (!res.error) {
          Botble.showSuccess(res.message);
        } else {
          Botble.showError(res.message);
        }
        _self.removeClass('button-loading');
        $('#cleanup-modal').modal('hide');
      },
      error: function error(res) {
        _self.removeClass('button-loading');
        Botble.handleError(res);
      }
    });
  });
});
/******/ })()
;