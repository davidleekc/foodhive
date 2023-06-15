/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!******************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/front/order-return.js ***!
  \******************************************************************************/


(function ($) {
  $(document).on('change', '.product-quantity .select-return-item-qty', function (e) {
    var $this = $(e.currentTarget);
    var $option = $this.find(':selected');
    if ($option.length) {
      $option.closest('tr').find('.return-amount').html($option.data('amount'));
    }
  });
})(jQuery);
/******/ })()
;