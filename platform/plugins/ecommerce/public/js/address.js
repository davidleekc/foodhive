/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/address.js ***!
  \*******************************************************************/
$(function () {
  $(document).on('click', '.btn-trigger-add-address', function (e) {
    e.preventDefault();
    $('#add-address-modal').modal('show');
  });
  $(document).on('click', '#confirm-add-address-button', function (event) {
    event.preventDefault();
    var _self = $(event.currentTarget);
    _self.addClass('button-loading');
    $.ajax({
      type: 'POST',
      cache: false,
      url: _self.closest('.modal-content').find('form').prop('action'),
      data: _self.closest('.modal-content').find('form').serialize(),
      success: function success(res) {
        if (!res.error) {
          Botble.showNotice('success', res.message);
          $('#add-address-modal').modal('hide');
          _self.closest('.modal-content').find('form').get(0).reset();
          $('#address-histories').load($('.page-content form').prop('action') + ' #address-histories > *');
        } else {
          Botble.showNotice('error', res.message);
        }
        _self.removeClass('button-loading');
      },
      error: function error(res) {
        Botble.handleError(res);
        _self.removeClass('button-loading');
      }
    });
  });
  $(document).on('click', '.btn-trigger-edit-address', function (event) {
    event.preventDefault();
    var _self = $(event.currentTarget);
    $.ajax({
      type: 'GET',
      cache: false,
      url: _self.data('section'),
      success: function success(res) {
        if (!res.error) {
          $('#edit-address-modal .modal-body').html('');
          $('#edit-address-modal .modal-body').append(res);
          $('#edit-address-modal').modal('show');
        } else {
          Botble.showNotice('error', res.message);
        }
        _self.removeClass('button-loading');
      },
      error: function error(res) {
        Botble.handleError(res);
        _self.removeClass('button-loading');
      }
    });
  });
  $(document).on('click', '#confirm-edit-address-button', function (event) {
    event.preventDefault();
    var _self = $(event.currentTarget);
    _self.addClass('button-loading');
    $.ajax({
      type: 'POST',
      cache: false,
      url: _self.closest('.modal-content').find('form').prop('action'),
      data: _self.closest('.modal-content').find('form').serialize(),
      success: function success(res) {
        if (!res.error) {
          Botble.showNotice('success', res.message);
          $('#edit-address-modal').modal('hide');
          _self.closest('.modal-content').find('form').get(0).reset();
          $('#address-histories').load($('.page-content form').prop('action') + ' #address-histories > *');
        } else {
          Botble.showNotice('error', res.message);
        }
        _self.removeClass('button-loading');
      },
      error: function error(res) {
        Botble.handleError(res);
        _self.removeClass('button-loading');
      }
    });
  });
  $(document).on('click', '.deleteDialog', function (event) {
    event.preventDefault();
    var _self = $(event.currentTarget);
    $('.delete-crud-entry').data('section', _self.data('section'));
    $('.modal-confirm-delete').modal('show');
  });
  $('.delete-crud-entry').on('click', function (event) {
    event.preventDefault();
    var _self = $(event.currentTarget);
    _self.addClass('button-loading');
    var deleteURL = _self.data('section');
    $.ajax({
      url: deleteURL,
      type: 'POST',
      data: {
        '_method': 'DELETE'
      },
      success: function success(data) {
        if (data.error) {
          Botble.showError(data.message);
        } else {
          Botble.showSuccess(data.message);
          var formAction = $('.page-content form').prop('action');
          $('#address-histories').load(formAction + ' #address-histories > *');
        }
        _self.closest('.modal').modal('hide');
        _self.removeClass('button-loading');
      },
      error: function error(data) {
        Botble.handleError(data);
        _self.removeClass('button-loading');
      }
    });
  });
});
/******/ })()
;