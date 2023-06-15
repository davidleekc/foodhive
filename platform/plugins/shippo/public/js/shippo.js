/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!***************************************************************!*\
  !*** ./platform/plugins/shippo/resources/assets/js/shippo.js ***!
  \***************************************************************/


var Shippo = Shippo || {};
Shippo.init = function () {
  $(document).on('show.bs.modal', '#shippo-view-n-create-transaction', function (e) {
    var $self = $(e.currentTarget);
    var $related = $(e.relatedTarget);
    $self.find('.modal-body').html('');
    $.ajax({
      type: 'GET',
      url: $related.data('url'),
      beforeSend: function beforeSend() {
        $related.addClass('button-loading');
      },
      success: function success(res) {
        if (res.error) {
          Botble.showError(res.message);
        } else {
          $self.find('.modal-body').html(res.data.html);
        }
      },
      error: function error(res) {
        Botble.handleError(res);
      },
      complete: function complete() {
        $related.removeClass('button-loading');
      }
    });
  });
  $(document).on('click', '#shippo-view-n-create-transaction .create-transaction', function (e) {
    var $self = $(e.currentTarget);
    $.ajax({
      type: 'POST',
      url: $self.data('url'),
      beforeSend: function beforeSend() {
        $self.addClass('button-loading');
      },
      success: function success(res) {
        if (res.error) {
          Botble.showError(res.message);
        } else {
          $('[data-bs-target="#shippo-view-n-create-transaction"]').addClass('d-none');
          $('#shippo-view-n-create-transaction').modal('hide');
          Botble.showSuccess(res.message);
        }
      },
      error: function error(res) {
        Botble.handleError(res);
      },
      complete: function complete() {
        $self.removeClass('button-loading');
      }
    });
  });
  $(document).on('click', '#shippo-view-n-create-transaction .get-new-rates', function (e) {
    var $self = $(e.currentTarget);
    $.ajax({
      type: 'GET',
      url: $self.data('url'),
      beforeSend: function beforeSend() {
        $self.addClass('button-loading');
      },
      success: function success(res) {
        if (res.error) {
          Botble.showError(res.message);
        } else {
          Botble.showSuccess(res.message);
          $self.addClass('d-none');
          $self.parent().append(res.data.html);
        }
      },
      error: function error(res) {
        Botble.handleError(res);
      },
      complete: function complete() {
        $self.removeClass('button-loading');
      }
    });
  });
  $(document).on('submit', '.update-rate-shipment', function (e) {
    e.preventDefault();
    var $self = $(e.currentTarget);
    var $button = $self.find('button[type=submit]');
    $.ajax({
      type: 'POST',
      url: $self.prop('action'),
      data: $self.serializeArray(),
      beforeSend: function beforeSend() {
        $button.addClass('button-loading');
      },
      success: function success(res) {
        if (res.error) {
          Botble.showError(res.message);
        } else {
          Botble.showSuccess(res.message);
          $('#shippo-view-n-create-transaction').find('.modal-body').html(res.data.html);
        }
      },
      error: function error(res) {
        Botble.handleError(res);
      },
      complete: function complete() {
        $button.removeClass('button-loading');
      }
    });
  });
};
$(function () {
  Shippo.init();
});
/******/ })()
;