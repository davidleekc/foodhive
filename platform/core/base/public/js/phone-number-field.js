/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************************************************!*\
  !*** ./platform/core/base/resources/assets/js/form/phone-number-field.js ***!
  \***************************************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var PhoneNumberField = /*#__PURE__*/function () {
  function PhoneNumberField() {
    _classCallCheck(this, PhoneNumberField);
  }
  _createClass(PhoneNumberField, [{
    key: "init",
    value: function init() {
      $(document).find('.js-phone-number-mask').each(function (index, element) {
        window.intlTelInput(element, {
          // allowDropdown: false,
          // autoHideDialCode: false,
          // autoPlaceholder: "off",
          // dropdownContainer: document.body,
          // excludeCountries: ["us"],
          // formatOnDisplay: false,
          geoIpLookup: function geoIpLookup(callback) {
            $.get('https://ipinfo.io', function () {}, 'jsonp').always(function (resp) {
              callback(resp && resp.country ? resp.country : '');
            });
          },
          // hiddenInput: "full_number",
          initialCountry: 'auto',
          // localizedCountries: { 'de': 'Deutschland' },
          // nationalMode: false,
          // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
          // placeholderNumberType: "MOBILE",
          // preferredCountries: ['cn', 'jp'],
          // separateDialCode: true,
          utilsScript: '/vendor/core/core/base/libraries/intl-tel-input/js/utils.js'
        });
      });
    }
  }]);
  return PhoneNumberField;
}();
$(document).ready(function () {
  new PhoneNumberField().init();
  document.addEventListener('payment-form-reloaded', function () {
    new PhoneNumberField().init();
  });
});
/******/ })()
;