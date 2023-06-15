/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************************************************!*\
  !*** ./platform/plugins/marketplace/resources/assets/js/marketplace-setting.js ***!
  \*********************************************************************************/
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var MarketplaceSetting = /*#__PURE__*/function () {
  function MarketplaceSetting() {
    _classCallCheck(this, MarketplaceSetting);
    this.eventListeners();
  }
  _createClass(MarketplaceSetting, [{
    key: "eventListeners",
    value: function eventListeners() {
      var _this = this;
      $(document).on('click', '#add-new-commission-setting-category', function (event) {
        event.preventDefault();
        event.stopPropagation();
        _this.addNewCommissionSetting(event.currentTarget);
        return false;
      });
      $(document).on('click', '.remove-commission-setting', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var index = $(e.target).attr('data-index');
        $(document).find("#commission-setting-item-".concat(index)).remove();
      });
      var input = document.querySelectorAll('.tagify-commission-setting');
      input.forEach(function (element) {
        _this.tagify(element);
      });
    }
  }, {
    key: "tagify",
    value: function tagify(element) {
      var self = this;
      new Tagify(element, {
        delimiters: null,
        enforceWhitelist: true,
        whitelist: self.formatWhitelist(),
        dropdown: {
          enabled: 1,
          // suggest tags after a single character input
          classname: 'extra-properties',
          // custom class for the suggestion dropdown,
          searchBy: ['name']
        }
      });
    }
  }, {
    key: "formatWhitelist",
    value: function formatWhitelist() {
      var data = [];
      window.tagifyWhitelist.map(function (item) {
        data.push({
          value: item.name,
          id: item.id
        });
      });
      return data;
    }
  }, {
    key: "addNewCommissionSetting",
    value: function addNewCommissionSetting() {
      var tpl = $('#commission-setting-item-template').html();
      var index = $('.commission-setting-item').length;
      var html = tpl.replace(/__index__/g, index);
      $('.commission-setting-item-wrapper').append(html);
      var element = document.querySelector("#commission-setting-item-".concat(index, " .tagify-commission-setting"));
      this.tagify(element);
    }
  }]);
  return MarketplaceSetting;
}();
$(document).ready(function () {
  new MarketplaceSetting();
});
/******/ })()
;