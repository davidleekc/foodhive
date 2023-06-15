/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
/*!*************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/global-option.js ***!
  \*************************************************************************/


$(document).ready(function () {
  var jsOption = {
    currentType: 'N/A',
    init: function init() {
      this.initFormFields($('.option-type').val());
      this.eventListeners();
      $('.option-sortable').sortable({
        stop: function stop() {
          var idsInOrder = $('.option-sortable').sortable('toArray', {
            attribute: 'data-index'
          });
          idsInOrder.map(function (id, index) {
            $('.option-row[data-index="' + id + '"]').find('.option-order').val(index);
          });
        }
      });
    },
    addNewRow: function addNewRow() {
      $('.add-new-row').click(function () {
        var table = $(this).parent().find('table tbody');
        var tr = table.find('tr').last().clone();
        var labelName = 'options[' + table.find('tr').length + '][option_value]',
          affectName = 'options[' + table.find('tr').length + '][affect_price]',
          affectTypeName = 'options[' + table.find('tr').length + '][affect_type]';
        tr.find('.option-label').attr('name', labelName);
        tr.find('.affect_price').attr('name', affectName);
        tr.find('.affect_type').attr('name', affectTypeName);
        table.append(tr);
      });
      return this;
    },
    removeRow: function removeRow() {
      $('.option-setting-tab').on('click', '.remove-row', function () {
        var table = $(this).parent().parent().parent();
        if (table.find('tr').length > 1) {
          $(this).parent().parent().remove();
        } else {
          return false;
        }
      });
      return this;
    },
    eventListeners: function eventListeners() {
      this.onOptionChange();
      this.addNewRow().removeRow();
    },
    onOptionChange: function onOptionChange() {
      var self = this;
      $('.option-type').change(function () {
        var value = $(this).val();
        this.currentType = value;
        self.initFormFields(value);
      });
    },
    initFormFields: function initFormFields(value) {
      this.currentType = value;
      if (value !== 'N/A') {
        value = value.split('\\');
        var id = value[value.length - 1];
        if (id !== 'Field') {
          id = 'multiple';
        }
        $('.empty, .option-setting-tab').hide();
        id = '#option-setting-' + id.toLowerCase();
        $(id).show();
      }
    }
  };
  jsOption.init();
});
/******/ })()
;