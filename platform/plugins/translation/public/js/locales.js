/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************************************!*\
  !*** ./platform/plugins/translation/resources/assets/js/locales.js ***!
  \*********************************************************************/
$(document).ready(function () {
  var _this = this;
  var languageTable = $('.table-language');
  languageTable.on('click', '.delete-locale-button', function (event) {
    event.preventDefault();
    $('.delete-crud-entry').data('url', $(event.currentTarget).data('url'));
    $('.modal-confirm-delete').modal('show');
  });
  $(document).on('click', '.delete-crud-entry', function (event) {
    event.preventDefault();
    $('.modal-confirm-delete').modal('hide');
    var deleteURL = $(event.currentTarget).data('url');
    $(_this).prop('disabled', true).addClass('button-loading');
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
          if (data.data) {
            languageTable.find('i[data-locale=' + data.data + ']').unwrap();
            $('.tooltip').remove();
          }
          languageTable.find('a[data-url="' + deleteURL + '"]').closest('tr').remove();
          Botble.showSuccess(data.message);
        }
        $(_this).prop('disabled', false).removeClass('button-loading');
      },
      error: function error(data) {
        $(_this).prop('disabled', false).removeClass('button-loading');
        Botble.handleError(data);
      }
    });
  });
  $(document).on('click', '.add-locale-form button[type=submit]', function (event) {
    var _this2 = this;
    event.preventDefault();
    event.stopPropagation();
    $(this).prop('disabled', true).addClass('button-loading');
    $.ajax({
      type: 'POST',
      cache: false,
      url: $(this).closest('form').prop('action'),
      data: new FormData($(this).closest('form')[0]),
      contentType: false,
      processData: false,
      success: function success(res) {
        if (!res.error) {
          Botble.showSuccess(res.message);
          languageTable.load(window.location.href + ' .table-language > *');
        } else {
          Botble.showError(res.message);
        }
        $(_this2).prop('disabled', false).removeClass('button-loading');
      },
      error: function error(res) {
        $(_this2).prop('disabled', false).removeClass('button-loading');
        Botble.handleError(res);
      }
    });
  });
  var $availableRemoteLocales = $('#available-remote-locales');
  if ($availableRemoteLocales.length) {
    var getRemoteLocales = function getRemoteLocales() {
      $.ajax({
        url: $availableRemoteLocales.data('url'),
        type: 'GET',
        success: function success(res) {
          if (res.error) {
            Botble.showError(res.message);
          } else {
            languageTable.load(window.location.href + ' .table-language > *');
            $availableRemoteLocales.html(res.data);
          }
        },
        error: function error(res) {
          Botble.handleError(res);
        }
      });
    };
    getRemoteLocales();
    $(document).on('click', '.btn-import-remote-locale', function (event) {
      event.preventDefault();
      $('.button-confirm-import-locale').data('url', $(this).data('url'));
      $('.modal-confirm-import-locale').modal('show');
    });
    $('.button-confirm-import-locale').on('click', function (event) {
      event.preventDefault();
      var _self = $(event.currentTarget);
      _self.addClass('button-loading');
      var url = _self.data('url');
      $.ajax({
        url: url,
        type: 'POST',
        success: function success(res) {
          if (res.error) {
            Botble.showError(res.message);
          } else {
            Botble.showSuccess(res.message);
            getRemoteLocales();
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
  }
});
/******/ })()
;