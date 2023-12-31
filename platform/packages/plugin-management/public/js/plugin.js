/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************************************************!*\
  !*** ./platform/packages/plugin-management/resources/assets/js/plugin.js ***!
  \***************************************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var PluginManagement = /*#__PURE__*/function () {
  function PluginManagement() {
    _classCallCheck(this, PluginManagement);
  }
  _createClass(PluginManagement, [{
    key: "init",
    value: function init() {
      $('#plugin-list').on('click', '.btn-trigger-change-status', function (event) {
        event.preventDefault();
        var _self = $(event.currentTarget);
        _self.addClass('button-loading');
        $.ajax({
          url: route('plugins.change.status', {
            name: _self.data('plugin')
          }),
          type: 'POST',
          data: {
            '_method': 'PUT'
          },
          success: function success(data) {
            if (data.error) {
              Botble.showError(data.message);
            } else {
              Botble.showSuccess(data.message);
              $('#plugin-list #app-' + _self.data('plugin')).load(window.location.href + ' #plugin-list #app-' + _self.data('plugin') + ' > *');
              window.location.reload();
            }
            _self.removeClass('button-loading');
          },
          error: function error(data) {
            Botble.handleError(data);
            _self.removeClass('button-loading');
          }
        });
      });
      $(document).on('click', '.btn-trigger-remove-plugin', function (event) {
        event.preventDefault();
        $('#confirm-remove-plugin-button').data('plugin', $(event.currentTarget).data('plugin'));
        $('#remove-plugin-modal').modal('show');
      });
      $(document).on('click', '#confirm-remove-plugin-button', function (event) {
        event.preventDefault();
        var _self = $(event.currentTarget);
        _self.addClass('button-loading');
        $.ajax({
          url: route('plugins.remove', {
            plugin: _self.data('plugin')
          }),
          type: 'POST',
          data: {
            '_method': 'DELETE'
          },
          success: function success(data) {
            if (data.error) {
              Botble.showError(data.message);
            } else {
              Botble.showSuccess(data.message);
              window.location.reload();
            }
            _self.removeClass('button-loading');
            $('#remove-plugin-modal').modal('hide');
          },
          error: function error(data) {
            Botble.handleError(data);
            _self.removeClass('button-loading');
            $('#remove-plugin-modal').modal('hide');
          }
        });
      });
      $(document).on('click', '.btn-trigger-update-plugin', function (event) {
        event.preventDefault();
        var _self = $(event.currentTarget);
        var uuid = _self.data('uuid');
        _self.addClass('button-loading');
        _self.attr('disabled', true);
        $.ajax({
          url: route('plugins.marketplace.ajax.update', {
            id: uuid
          }),
          type: 'POST',
          success: function success(data) {
            if (data.error) {
              Botble.showError(data.message);
              _self.removeClass('button-loading');
              _self.removeAttr('disabled', true);
              if (data.data && data.data.redirect) {
                window.location.href;
              }
            } else {
              Botble.showSuccess(data.message);
              setTimeout(function () {
                window.location.reload();
              }, 2000);
            }
          },
          error: function error(data) {
            Botble.handleError(data);
            _self.removeClass('button-loading');
            _self.removeAttr('disabled', true);
          }
        });
      });
      this.checkUpdate();
    }
  }, {
    key: "checkUpdate",
    value: function checkUpdate() {
      var _this = this;
      $.ajax({
        url: route('plugins.marketplace.ajax.check-update'),
        type: 'POST',
        success: function success(data) {
          if (data.data) {
            Object.keys(data.data).forEach(function (key) {
              var plugin = data.data[key];
              var element = $('[data-check-update="' + plugin.name + '"]');
              $checkVersion = _this.checkVersion(element.data('version'), plugin.version);
              if ($checkVersion) {
                element.attr('style', 'display: show;');
                element.attr('data-uuid', plugin.id);
              }
            });
          }
        }
      });
    }
  }, {
    key: "checkVersion",
    value: function checkVersion(currentVersion, latestVersion) {
      var current = currentVersion.toString().split('.');
      var latest = latestVersion.toString().split('.');
      var length = Math.max(current.length, latest.length);
      for (var i = 0; i < length; i++) {
        var oldVer = ~~current[i];
        var newVer = ~~latest[i];
        if (newVer > oldVer) {
          return true;
        }
      }
      return false;
    }
  }]);
  return PluginManagement;
}();
$(document).ready(function () {
  new PluginManagement().init();
});
/******/ })()
;