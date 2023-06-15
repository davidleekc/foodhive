/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************************************!*\
  !*** ./platform/plugins/location/resources/assets/js/location.js ***!
  \*******************************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var Location = /*#__PURE__*/function () {
  function Location() {
    _classCallCheck(this, Location);
  }
  _createClass(Location, [{
    key: "init",
    value: function init() {
      var country = 'select[data-type="country"]';
      var state = 'select[data-type="state"]';
      var city = 'select[data-type="city"]';
      $(document).on('change', country, function (e) {
        e.preventDefault();
        var $parent = getParent($(e.currentTarget));
        var $state = $parent.find(state);
        var $city = $parent.find(city);
        $state.find('option:not([value=""]):not([value="0"])').remove();
        $city.find('option:not([value=""]):not([value="0"])').remove();
        if ($state.length) {
          var val = $(e.currentTarget).val();
          if (val) {
            var $button = $(e.currentTarget).closest('form').find('button[type=submit], input[type=submit]');
            Location.getStates($state, val, $button);
          }
        }
      });
      $(document).on('change', state, function (e) {
        e.preventDefault();
        var $parent = getParent($(e.currentTarget));
        var $city = $parent.find(city);
        if ($city.length) {
          $city.find('option:not([value=""]):not([value="0"])').remove();
          var val = $(e.currentTarget).val();
          if (val) {
            var $button = $(e.currentTarget).closest('form').find('button[type=submit], input[type=submit]');
            Location.getCities($city, val, $button);
          }
        }
      });
      function getParent($el) {
        var $parent = $(document);
        var formParent = $el.data('form-parent');
        if (formParent && $(formParent).length) {
          $parent = $(formParent);
        }
        return $parent;
      }
    }
  }], [{
    key: "getStates",
    value: function getStates($el, countryId) {
      var $button = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
      $.ajax({
        url: $el.data('url'),
        data: {
          country_id: countryId
        },
        type: 'GET',
        beforeSend: function beforeSend() {
          $button && $button.prop('disabled', true);
        },
        success: function success(res) {
          if (res.error) {
            Botble.showError(res.message);
          } else {
            var options = '';
            $.each(res.data, function (index, item) {
              options += '<option value="' + (item.id || '') + '">' + item.name + '</option>';
            });
            $el.html(options);
          }
        },
        complete: function complete() {
          $button && $button.prop('disabled', false);
        }
      });
    }
  }, {
    key: "getCities",
    value: function getCities($el, stateId) {
      var $button = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
      $.ajax({
        url: $el.data('url'),
        data: {
          state_id: stateId
        },
        type: 'GET',
        beforeSend: function beforeSend() {
          $button && $button.prop('disabled', true);
        },
        success: function success(res) {
          if (res.error) {
            Botble.showError(res.message);
          } else {
            var options = '';
            $.each(res.data, function (index, item) {
              options += '<option value="' + (item.id || '') + '">' + item.name + '</option>';
            });
            $el.html(options);
            $el.trigger('change');
          }
        },
        complete: function complete() {
          $button && $button.prop('disabled', false);
        }
      });
    }
  }]);
  return Location;
}();
$(function () {
  new Location().init();
});
/******/ })()
;