/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./platform/core/media/resources/assets/js/App/Config/MediaConfig.js":
/*!***************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Config/MediaConfig.js ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   MediaConfig: () => (/* binding */ MediaConfig),
/* harmony export */   RecentItems: () => (/* binding */ RecentItems)
/* harmony export */ });
var MediaConfig = $.parseJSON(localStorage.getItem('MediaConfig')) || {};
var defaultConfig = {
  app_key: RV_MEDIA_CONFIG.random_hash ? RV_MEDIA_CONFIG.random_hash : '21d06709fe1d3abdf0e35ddda89c4b279',
  request_params: {
    view_type: 'tiles',
    filter: 'everything',
    view_in: 'all_media',
    sort_by: 'created_at-desc',
    folder_id: 0
  },
  hide_details_pane: false,
  icons: {
    folder: 'fa fa-folder'
  },
  actions_list: {
    basic: [{
      icon: 'fa fa-eye',
      name: 'Preview',
      action: 'preview',
      order: 0,
      "class": 'rv-action-preview'
    }, {
      icon: 'fa fa-crop',
      name: 'Crop',
      action: 'crop',
      order: 1,
      "class": 'rv-action-crop'
    }],
    file: [{
      icon: 'fa fa-link',
      name: 'Copy link',
      action: 'copy_link',
      order: 0,
      "class": 'rv-action-copy-link'
    }, {
      icon: 'far fa-edit',
      name: 'Rename',
      action: 'rename',
      order: 1,
      "class": 'rv-action-rename'
    }, {
      icon: 'fa fa-copy',
      name: 'Make a copy',
      action: 'make_copy',
      order: 2,
      "class": 'rv-action-make-copy'
    }, {
      icon: 'fas fa-file-signature',
      name: 'Alt text',
      action: 'alt_text',
      order: 3,
      "class": 'rv-action-alt-text'
    }],
    user: [{
      icon: 'fa fa-star',
      name: 'Favorite',
      action: 'favorite',
      order: 2,
      "class": 'rv-action-favorite'
    }, {
      icon: 'fa fa-star',
      name: 'Remove favorite',
      action: 'remove_favorite',
      order: 3,
      "class": 'rv-action-favorite'
    }],
    other: [{
      icon: 'fa fa-download',
      name: 'Download',
      action: 'download',
      order: 0,
      "class": 'rv-action-download'
    }, {
      icon: 'fa fa-trash',
      name: 'Move to trash',
      action: 'trash',
      order: 1,
      "class": 'rv-action-trash'
    }, {
      icon: 'fa fa-eraser',
      name: 'Delete permanently',
      action: 'delete',
      order: 2,
      "class": 'rv-action-delete'
    }, {
      icon: 'fa fa-undo',
      name: 'Restore',
      action: 'restore',
      order: 3,
      "class": 'rv-action-restore'
    }]
  }
};
if (!MediaConfig.app_key || MediaConfig.app_key !== defaultConfig.app_key) {
  MediaConfig = defaultConfig;
}
MediaConfig.request_params.search = '';
var RecentItems = $.parseJSON(localStorage.getItem('RecentItems')) || [];


/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js":
/*!************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Helpers/Helpers.js ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Helpers: () => (/* binding */ Helpers)
/* harmony export */ });
/* harmony import */ var _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Config/MediaConfig */ "./platform/core/media/resources/assets/js/App/Config/MediaConfig.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }

var Helpers = /*#__PURE__*/function () {
  function Helpers() {
    _classCallCheck(this, Helpers);
  }
  _createClass(Helpers, null, [{
    key: "getUrlParam",
    value: function getUrlParam(paramName) {
      var url = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      if (!url) {
        url = window.location.search;
      }
      var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
      var match = url.match(reParam);
      return match && match.length > 1 ? match[1] : null;
    }
  }, {
    key: "asset",
    value: function asset(url) {
      if (url.substring(0, 2) === '//' || url.substring(0, 7) === 'http://' || url.substring(0, 8) === 'https://') {
        return url;
      }
      var baseUrl = RV_MEDIA_URL.base_url.substr(-1, 1) !== '/' ? RV_MEDIA_URL.base_url + '/' : RV_MEDIA_URL.base_url;
      if (url.substring(0, 1) === '/') {
        return baseUrl + url.substring(1);
      }
      return baseUrl + url;
    }
  }, {
    key: "showAjaxLoading",
    value: function showAjaxLoading() {
      var $element = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : $('.rv-media-main');
      $element.addClass('on-loading').append($('#rv_media_loading').html());
    }
  }, {
    key: "hideAjaxLoading",
    value: function hideAjaxLoading() {
      var $element = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : $('.rv-media-main');
      $element.removeClass('on-loading').find('.loading-wrapper').remove();
    }
  }, {
    key: "isOnAjaxLoading",
    value: function isOnAjaxLoading() {
      var $element = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : $('.rv-media-items');
      return $element.hasClass('on-loading');
    }
  }, {
    key: "jsonEncode",
    value: function jsonEncode(object) {
      if (typeof object === 'undefined') {
        object = null;
      }
      return JSON.stringify(object);
    }
  }, {
    key: "jsonDecode",
    value: function jsonDecode(jsonString, defaultValue) {
      if (!jsonString) {
        return defaultValue;
      }
      if (typeof jsonString === 'string') {
        var result;
        try {
          result = $.parseJSON(jsonString);
        } catch (err) {
          result = defaultValue;
        }
        return result;
      }
      return jsonString;
    }
  }, {
    key: "getRequestParams",
    value: function getRequestParams() {
      if (window.rvMedia.options && window.rvMedia.options.open_in === 'modal') {
        return $.extend(true, _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params, window.rvMedia.options || {});
      }
      return _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params;
    }
  }, {
    key: "setSelectedFile",
    value: function setSelectedFile(fileId) {
      if (typeof window.rvMedia.options !== 'undefined') {
        window.rvMedia.options.selected_file_id = fileId;
      } else {
        _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params.selected_file_id = fileId;
      }
    }
  }, {
    key: "getConfigs",
    value: function getConfigs() {
      return _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig;
    }
  }, {
    key: "storeConfig",
    value: function storeConfig() {
      localStorage.setItem('MediaConfig', Helpers.jsonEncode(_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig));
    }
  }, {
    key: "storeRecentItems",
    value: function storeRecentItems() {
      localStorage.setItem('RecentItems', Helpers.jsonEncode(_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.RecentItems));
    }
  }, {
    key: "addToRecent",
    value: function addToRecent(id) {
      if (id instanceof Array) {
        _.each(id, function (value) {
          _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.RecentItems.push(value);
        });
      } else {
        _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.RecentItems.push(id);
        this.storeRecentItems();
      }
    }
  }, {
    key: "getItems",
    value: function getItems() {
      var items = [];
      $('.js-media-list-title').each(function (index, el) {
        var $box = $(el);
        var data = $box.data() || {};
        data.index_key = $box.index();
        items.push(data);
      });
      return items;
    }
  }, {
    key: "getSelectedItems",
    value: function getSelectedItems() {
      var selected = [];
      $('.js-media-list-title input[type=checkbox]:checked').each(function (index, el) {
        var $box = $(el).closest('.js-media-list-title');
        var data = $box.data() || {};
        data.index_key = $box.index();
        selected.push(data);
      });
      return selected;
    }
  }, {
    key: "getSelectedFiles",
    value: function getSelectedFiles() {
      var selected = [];
      $('.js-media-list-title[data-context=file] input[type=checkbox]:checked').each(function (index, el) {
        var $box = $(el).closest('.js-media-list-title');
        var data = $box.data() || {};
        data.index_key = $box.index();
        selected.push(data);
      });
      return selected;
    }
  }, {
    key: "getSelectedFolder",
    value: function getSelectedFolder() {
      var selected = [];
      $('.js-media-list-title[data-context=folder] input[type=checkbox]:checked').each(function (index, el) {
        var $box = $(el).closest('.js-media-list-title');
        var data = $box.data() || {};
        data.index_key = $box.index();
        selected.push(data);
      });
      return selected;
    }
  }, {
    key: "isUseInModal",
    value: function isUseInModal() {
      return window.rvMedia && window.rvMedia.options && window.rvMedia.options.open_in === 'modal';
    }
  }, {
    key: "resetPagination",
    value: function resetPagination() {
      RV_MEDIA_CONFIG.pagination = {
        paged: 1,
        posts_per_page: 40,
        in_process_get_media: false,
        has_more: true
      };
    }
  }]);
  return Helpers;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Services/ActionsService.js":
/*!********************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Services/ActionsService.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ActionsService: () => (/* binding */ ActionsService)
/* harmony export */ });
/* harmony import */ var _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Config/MediaConfig */ "./platform/core/media/resources/assets/js/App/Config/MediaConfig.js");
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
/* harmony import */ var _MessageService__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MessageService */ "./platform/core/media/resources/assets/js/App/Services/MessageService.js");
/* harmony import */ var cropperjs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! cropperjs */ "./node_modules/cropperjs/dist/cropper.js");
/* harmony import */ var cropperjs__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(cropperjs__WEBPACK_IMPORTED_MODULE_3__);
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }




var ActionsService = /*#__PURE__*/function () {
  function ActionsService() {
    _classCallCheck(this, ActionsService);
  }
  _createClass(ActionsService, null, [{
    key: "handleDropdown",
    value: function handleDropdown() {
      var selected = _.size(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems());
      ActionsService.renderActions();
      if (selected > 0) {
        $('.rv-dropdown-actions').removeClass('disabled');
      } else {
        $('.rv-dropdown-actions').addClass('disabled');
      }
    }
  }, {
    key: "handlePreview",
    value: function handlePreview() {
      var selected = [];
      _.each(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFiles(), function (value) {
        if (value.preview_url) {
          selected.push({
            src: value.preview_url,
            type: value.preview_type
          });
          _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.RecentItems.push(value.id);
        }
      });
      if (_.size(selected) > 0) {
        $.fancybox.open(selected);
        _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.storeRecentItems();
      } else {
        this.handleGlobalAction('download');
      }
    }
  }, {
    key: "renderCropImage",
    value: function renderCropImage() {
      var html = $('#rv_media_crop_image').html();
      var modal = $('#modal_crop_image .crop-image').empty();
      var item = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems()[0];
      var form = $('#modal_crop_image .form-crop');
      var cropData;
      var el = html.replace(/__src__/gi, item.full_url);
      modal.append(el);
      var image = modal.find('img')[0];
      var options = {
        minContainerWidth: 550,
        minContainerHeight: 550,
        dragMode: 'move',
        crop: function crop(event) {
          cropData = event.detail;
          form.find('input[name="image_id"]').val(item.id);
          form.find('input[name="crop_data"]').val(JSON.stringify(cropData));
          setHeight(cropData.height);
          setWidth(cropData.width);
        }
      };
      var cropper = new (cropperjs__WEBPACK_IMPORTED_MODULE_3___default())(image, options);
      form.find('#aspectRatio').on('click', function () {
        cropper.destroy();
        if ($(this).is(':checked')) {
          options.aspectRatio = cropData.width / cropData.height;
        } else {
          options.aspectRatio = null;
        }
        cropper = new (cropperjs__WEBPACK_IMPORTED_MODULE_3___default())(image, options);
      });
      form.find('#dataHeight').on('change', function () {
        cropData.height = parseFloat($(this).val());
        cropper.setData(cropData);
        setHeight(cropData.height);
      });
      form.find('#dataWidth').on('change', function () {
        cropData.width = parseFloat($(this).val());
        cropper.setData(cropData);
        setWidth(cropData.width);
      });
      var setHeight = function setHeight(height) {
        form.find('#dataHeight').val(parseInt(height));
      };
      var setWidth = function setWidth(width) {
        form.find('#dataWidth').val(parseInt(width));
      };
    }
  }, {
    key: "handleCopyLink",
    value: function handleCopyLink() {
      var links = '';
      _.each(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFiles(), function (value) {
        if (!_.isEmpty(links)) {
          links += '\n';
        }
        links += value.full_url;
      });
      var $clipboardTemp = $('.js-rv-clipboard-temp');
      $clipboardTemp.data('clipboard-text', links);
      new Clipboard('.js-rv-clipboard-temp', {
        text: function text() {
          return links;
        }
      });
      _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.showMessage('success', RV_MEDIA_CONFIG.translations.clipboard.success, RV_MEDIA_CONFIG.translations.message.success_header);
      $clipboardTemp.trigger('click');
    }
  }, {
    key: "handleGlobalAction",
    value: function handleGlobalAction(type, callback) {
      var selected = [];
      _.each(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems(), function (value) {
        selected.push({
          is_folder: value.is_folder,
          id: value.id,
          full_url: value.full_url
        });
      });
      switch (type) {
        case 'rename':
          $('#modal_rename_items').modal('show').find('form.rv-form').data('action', type);
          break;
        case 'copy_link':
          ActionsService.handleCopyLink();
          break;
        case 'preview':
          ActionsService.handlePreview();
          break;
        case 'alt_text':
          $('#modal_alt_text_items').modal('show').find('form.rv-form').data('action', type);
          break;
        case 'crop':
          $('#modal_crop_image').modal('show').find('form.rv-form').data('action', type);
          break;
        case 'trash':
          $('#modal_trash_items').modal('show').find('form.rv-form').data('action', type);
          break;
        case 'delete':
          $('#modal_delete_items').modal('show').find('form.rv-form').data('action', type);
          break;
        case 'empty_trash':
          $('#modal_empty_trash').modal('show').find('form.rv-form').data('action', type);
          break;
        case 'download':
          var files = [];
          _.each(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems(), function (value) {
            if (!_.includes(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getConfigs().denied_download, value.mime_type)) {
              files.push({
                id: value.id,
                is_folder: value.is_folder
              });
            }
          });
          if (files.length) {
            ActionsService.handleDownload(files);
          } else {
            _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.showMessage('error', RV_MEDIA_CONFIG.translations.download.error, RV_MEDIA_CONFIG.translations.message.error_header);
          }
          break;
        default:
          ActionsService.processAction({
            selected: selected,
            action: type
          }, callback);
          break;
      }
    }
  }, {
    key: "processAction",
    value: function processAction(data) {
      var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      $.ajax({
        url: RV_MEDIA_URL.global_actions,
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function beforeSend() {
          _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.showAjaxLoading();
        },
        success: function success(res) {
          _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
          if (!res.error) {
            _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.showMessage('success', res.message, RV_MEDIA_CONFIG.translations.message.success_header);
          } else {
            _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.showMessage('error', res.message, RV_MEDIA_CONFIG.translations.message.error_header);
          }
          if (callback) {
            callback(res);
          }
        },
        complete: function complete() {
          _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.hideAjaxLoading();
        },
        error: function error(data) {
          _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.handleError(data);
        }
      });
    }
  }, {
    key: "renderRenameItems",
    value: function renderRenameItems() {
      var VIEW = $('#rv_media_rename_item').html();
      var $itemsWrapper = $('#modal_rename_items .rename-items').empty();
      _.each(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems(), function (value) {
        var item = VIEW.replace(/__icon__/gi, value.icon || 'fa fa-file').replace(/__placeholder__/gi, 'Input file name').replace(/__value__/gi, value.name);
        var $item = $(item);
        $item.data('id', value.id);
        $item.data('is_folder', value.is_folder);
        $item.data('name', value.name);
        $itemsWrapper.append($item);
      });
    }
  }, {
    key: "renderAltTextItems",
    value: function renderAltTextItems() {
      var VIEW = $('#rv_media_alt_text_item').html();
      var $itemsWrapper = $('#modal_alt_text_items .alt-text-items').empty();
      _.each(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems(), function (value) {
        var item = VIEW.replace(/__icon__/gi, value.icon || 'fa fa-file').replace(/__placeholder__/gi, 'Input file alt').replace(/__value__/gi, value.alt === null ? '' : value.alt);
        var $item = $(item);
        $item.data('id', value.id);
        $item.data('alt', value.alt);
        $itemsWrapper.append($item);
      });
    }
  }, {
    key: "renderActions",
    value: function renderActions() {
      var hasFolderSelected = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFolder().length > 0;
      var ACTION_TEMPLATE = $('#rv_action_item').html();
      var initializedItem = 0;
      var $dropdownActions = $('.rv-dropdown-actions .dropdown-menu');
      $dropdownActions.empty();
      var actionsList = $.extend({}, true, _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getConfigs().actions_list);
      if (hasFolderSelected) {
        actionsList.basic = _.reject(actionsList.basic, function (item) {
          return item.action === 'preview';
        });
        actionsList.basic = _.reject(actionsList.basic, function (item) {
          return item.action === 'crop';
        });
        actionsList.file = _.reject(actionsList.file, function (item) {
          return item.action === 'alt_text';
        });
        actionsList.file = _.reject(actionsList.file, function (item) {
          return item.action === 'copy_link';
        });
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.create')) {
          actionsList.file = _.reject(actionsList.file, function (item) {
            return item.action === 'make_copy';
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.edit')) {
          actionsList.file = _.reject(actionsList.file, function (item) {
            return _.includes(['rename'], item.action);
          });
          actionsList.user = _.reject(actionsList.user, function (item) {
            return _.includes(['rename'], item.action);
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.trash')) {
          actionsList.other = _.reject(actionsList.other, function (item) {
            return _.includes(['trash', 'restore'], item.action);
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.destroy')) {
          actionsList.other = _.reject(actionsList.other, function (item) {
            return _.includes(['delete'], item.action);
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.favorite')) {
          actionsList.other = _.reject(actionsList.other, function (item) {
            return _.includes(['favorite', 'remove_favorite'], item.action);
          });
        }
      }
      var selectedFiles = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFiles();
      var canPreview = _.filter(selectedFiles, function (value) {
        return value.preview_url;
      }).length;
      if (!canPreview) {
        actionsList.basic = _.reject(actionsList.basic, function (item) {
          return item.action === 'preview';
        });
      }
      var fileIsImage = _.filter(selectedFiles, function (value) {
        return value.type === 'image';
      }).length;
      if (!fileIsImage) {
        actionsList.basic = _.reject(actionsList.basic, function (item) {
          return item.action === 'crop';
        });
        actionsList.file = _.reject(actionsList.file, function (item) {
          return item.action === 'alt_text';
        });
      }
      if (selectedFiles.length > 0) {
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.create')) {
          actionsList.file = _.reject(actionsList.file, function (item) {
            return item.action === 'make_copy';
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.edit')) {
          actionsList.file = _.reject(actionsList.file, function (item) {
            return _.includes(['rename'], item.action);
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.trash')) {
          actionsList.other = _.reject(actionsList.other, function (item) {
            return _.includes(['trash', 'restore'], item.action);
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.destroy')) {
          actionsList.other = _.reject(actionsList.other, function (item) {
            return _.includes(['delete'], item.action);
          });
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.favorite')) {
          actionsList.other = _.reject(actionsList.other, function (item) {
            return _.includes(['favorite', 'remove_favorite'], item.action);
          });
        }
        if (selectedFiles.length > 1) {
          actionsList.basic = _.reject(actionsList.basic, function (item) {
            return item.action === 'crop';
          });
        }
      }
      _.each(actionsList, function (action, key) {
        _.each(action, function (item, index) {
          var is_break = false;
          switch (_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().view_in) {
            case 'all_media':
              if (_.includes(['remove_favorite', 'delete', 'restore'], item.action)) {
                is_break = true;
              }
              break;
            case 'recent':
              if (_.includes(['remove_favorite', 'delete', 'restore', 'make_copy'], item.action)) {
                is_break = true;
              }
              break;
            case 'favorites':
              if (_.includes(['favorite', 'delete', 'restore', 'make_copy'], item.action)) {
                is_break = true;
              }
              break;
            case 'trash':
              if (!_.includes(['preview', 'delete', 'restore', 'rename', 'download'], item.action)) {
                is_break = true;
              }
              break;
          }
          if (!is_break) {
            var template = ACTION_TEMPLATE.replace(/__action__/gi, item.action || '').replace(/__icon__/gi, item.icon || '').replace(/__name__/gi, RV_MEDIA_CONFIG.translations.actions_list[key][item.action] || item.name);
            if (!index && initializedItem) {
              template = '<li role="separator" class="divider"></li>' + template;
            }
            $dropdownActions.append(template);
          }
        });
        if (action.length > 0) {
          initializedItem++;
        }
      });
    }
  }, {
    key: "handleDownload",
    value: function handleDownload(files) {
      var html = $('.media-download-popup');
      var downloadTimeout = null;
      $.ajax({
        url: RV_MEDIA_URL.download,
        method: 'POST',
        data: {
          selected: files
        },
        xhrFields: {
          responseType: 'blob'
        },
        beforeSend: function beforeSend() {
          downloadTimeout = setTimeout(function () {
            html.show();
          }, 1000);
        },
        success: function success(response, status, xhr) {
          if (response.error) {
            _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.showMessage('error', response.message);
            return;
          }
          var downloadUrl = URL.createObjectURL(response);
          var a = document.createElement('a');
          var fileName = xhr.getResponseHeader('Content-Disposition').split('filename=')[1].split(';')[0];
          a.href = downloadUrl;
          a.download = fileName;
          document.body.appendChild(a);
          a.click();
          a.remove();
          window.URL.revokeObjectURL(downloadUrl);
        },
        complete: function complete() {
          html.hide();
          clearTimeout(downloadTimeout);
        },
        error: function error(data) {
          _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.handleError(data);
        }
      });
    }
  }]);
  return ActionsService;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Services/ContextMenuService.js":
/*!************************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Services/ContextMenuService.js ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ContextMenuService: () => (/* binding */ ContextMenuService)
/* harmony export */ });
/* harmony import */ var _ActionsService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ActionsService */ "./platform/core/media/resources/assets/js/App/Services/ActionsService.js");
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }


var ContextMenuService = /*#__PURE__*/function () {
  function ContextMenuService() {
    _classCallCheck(this, ContextMenuService);
  }
  _createClass(ContextMenuService, null, [{
    key: "initContext",
    value: function initContext() {
      if (jQuery().contextMenu) {
        $.contextMenu({
          selector: '.js-context-menu[data-context="file"]',
          build: function build() {
            return {
              items: ContextMenuService._fileContextMenu()
            };
          }
        });
        $.contextMenu({
          selector: '.js-context-menu[data-context="folder"]',
          build: function build() {
            return {
              items: ContextMenuService._folderContextMenu()
            };
          }
        });
      }
    }
  }, {
    key: "_fileContextMenu",
    value: function _fileContextMenu() {
      var items = {
        preview: {
          name: 'Preview',
          icon: function icon(opt, $itemElement, itemKey, item) {
            $itemElement.html('<i class="fa fa-eye" aria-hidden="true"></i> ' + item.name);
            return 'context-menu-icon-updated';
          },
          callback: function callback() {
            _ActionsService__WEBPACK_IMPORTED_MODULE_0__.ActionsService.handlePreview();
          }
        }
      };
      _.each(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getConfigs().actions_list, function (actionGroup, key) {
        _.each(actionGroup, function (value) {
          items[value.action] = {
            name: value.name,
            icon: function icon(opt, $itemElement, itemKey, item) {
              $itemElement.html('<i class="' + value.icon + '" aria-hidden="true"></i> ' + (RV_MEDIA_CONFIG.translations.actions_list[key][value.action] || item.name));
              return 'context-menu-icon-updated';
            },
            callback: function callback() {
              $('.js-files-action[data-action="' + value.action + '"]').trigger('click');
            }
          };
        });
      });
      var except = [];
      switch (_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().view_in) {
        case 'all_media':
          except = ['remove_favorite', 'delete', 'restore'];
          break;
        case 'recent':
          except = ['remove_favorite', 'delete', 'restore', 'make_copy'];
          break;
        case 'favorites':
          except = ['favorite', 'delete', 'restore', 'make_copy'];
          break;
        case 'trash':
          items = {
            preview: items.preview,
            rename: items.rename,
            download: items.download,
            "delete": items["delete"],
            restore: items.restore
          };
          break;
      }
      _.each(except, function (value) {
        items[value] = undefined;
      });
      var hasFolderSelected = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFolder().length > 0;
      if (hasFolderSelected) {
        items.preview = undefined;
        items.crop = undefined;
        items.copy_link = undefined;
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.create')) {
          items.make_copy = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.edit')) {
          items.rename = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.trash')) {
          items.trash = undefined;
          items.restore = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.destroy')) {
          items["delete"] = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'folders.favorite')) {
          items.favorite = undefined;
          items.remove_favorite = undefined;
        }
      }
      var selectedFiles = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFiles();
      if (selectedFiles.length > 0) {
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.create')) {
          items.make_copy = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.edit')) {
          items.rename = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.trash')) {
          items.trash = undefined;
          items.restore = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.destroy')) {
          items["delete"] = undefined;
        }
        if (!_.includes(RV_MEDIA_CONFIG.permissions, 'files.favorite')) {
          items.favorite = undefined;
          items.remove_favorite = undefined;
        }
        if (selectedFiles.length > 1) {
          items.crop = undefined;
        }
      }
      var canPreview = _.filter(selectedFiles, function (value) {
        return value.preview_url;
      }).length;
      if (!canPreview) {
        items.preview = undefined;
      }
      var fileIsImage = _.filter(selectedFiles, function (value) {
        return value.type === 'image';
      }).length;
      if (!fileIsImage) {
        items.crop = undefined;
        items.alt_text = undefined;
      }
      return items;
    }
  }, {
    key: "_folderContextMenu",
    value: function _folderContextMenu() {
      var items = ContextMenuService._fileContextMenu();
      items.preview = undefined;
      items.copy_link = undefined;
      return items;
    }
  }, {
    key: "destroyContext",
    value: function destroyContext() {
      if (jQuery().contextMenu) {
        $.contextMenu('destroy');
      }
    }
  }]);
  return ContextMenuService;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Services/DownloadService.js":
/*!*********************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Services/DownloadService.js ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   DownloadService: () => (/* binding */ DownloadService)
/* harmony export */ });
/* harmony import */ var _MediaService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MediaService */ "./platform/core/media/resources/assets/js/App/Services/MediaService.js");
/* harmony import */ var _MessageService__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MessageService */ "./platform/core/media/resources/assets/js/App/Services/MessageService.js");
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, defineProperty = Object.defineProperty || function (obj, key, desc) { obj[key] = desc.value; }, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return defineProperty(generator, "_invoke", { value: makeInvokeMethod(innerFn, self, context) }), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; defineProperty(this, "_invoke", { value: function value(method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; } function maybeInvokeDelegate(delegate, context) { var methodName = context.method, method = delegate.iterator[methodName]; if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel; var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), defineProperty(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (val) { var object = Object(val), keys = []; for (var key in object) keys.push(key); return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }



var DownloadService = /*#__PURE__*/function () {
  function DownloadService() {
    _classCallCheck(this, DownloadService);
    this.MediaService = new _MediaService__WEBPACK_IMPORTED_MODULE_0__.MediaService();
    $(document).on('shown.bs.modal', '#modal_download_url', function (event) {
      $(event.currentTarget).find('.form-download-url input[type=text]').focus();
    });
  }
  _createClass(DownloadService, [{
    key: "download",
    value: function () {
      var _download = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(urls, onProgress) {
        var _self, index, hasError, _iterator, _step, _loop;
        return _regeneratorRuntime().wrap(function _callee$(_context2) {
          while (1) switch (_context2.prev = _context2.next) {
            case 0:
              _self = this;
              urls = $.trim(urls).split(/\r?\n/);
              index = 0;
              hasError = false;
              _iterator = _createForOfIteratorHelper(urls);
              _context2.prev = 5;
              _loop = /*#__PURE__*/_regeneratorRuntime().mark(function _loop() {
                var url, filename, ok;
                return _regeneratorRuntime().wrap(function _loop$(_context) {
                  while (1) switch (_context.prev = _context.next) {
                    case 0:
                      url = _step.value;
                      filename = '';
                      try {
                        filename = new URL(url).pathname.split('/').pop();
                      } catch (e) {
                        filename = url;
                      }
                      ok = onProgress("".concat(index, " / ").concat(urls.length), filename, url);
                      _context.next = 6;
                      return new Promise(function (resolve) {
                        $.ajax({
                          url: RV_MEDIA_URL.download_url,
                          type: 'POST',
                          data: {
                            folderId: _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_2__.Helpers.getRequestParams().folder_id,
                            url: url
                          },
                          dataType: 'json',
                          success: function success(res) {
                            if (res.error) {
                              var _res$message, _res$data;
                              hasError = true;
                              ok(false, (_res$message = res.message) !== null && _res$message !== void 0 ? _res$message : (_res$data = res.data) === null || _res$data === void 0 ? void 0 : _res$data.message);
                            } else {
                              var _res$message2, _res$data2;
                              ok(true, (_res$message2 = res.message) !== null && _res$message2 !== void 0 ? _res$message2 : (_res$data2 = res.data) === null || _res$data2 === void 0 ? void 0 : _res$data2.message);
                            }
                            resolve();
                          },
                          error: function error(data) {
                            _MessageService__WEBPACK_IMPORTED_MODULE_1__.MessageService.handleError(data);
                          }
                        });
                      });
                    case 6:
                      index += 1;
                    case 7:
                    case "end":
                      return _context.stop();
                  }
                }, _loop);
              });
              _iterator.s();
            case 8:
              if ((_step = _iterator.n()).done) {
                _context2.next = 12;
                break;
              }
              return _context2.delegateYield(_loop(), "t0", 10);
            case 10:
              _context2.next = 8;
              break;
            case 12:
              _context2.next = 17;
              break;
            case 14:
              _context2.prev = 14;
              _context2.t1 = _context2["catch"](5);
              _iterator.e(_context2.t1);
            case 17:
              _context2.prev = 17;
              _iterator.f();
              return _context2.finish(17);
            case 20:
              _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_2__.Helpers.resetPagination();
              _self.MediaService.getMedia(true);
              if (!hasError) {
                DownloadService.closeModal();
                _MessageService__WEBPACK_IMPORTED_MODULE_1__.MessageService.showMessage('success', RV_MEDIA_CONFIG.translations.message.success_header);
              }
            case 23:
            case "end":
              return _context2.stop();
          }
        }, _callee, this, [[5, 14, 17, 20]]);
      }));
      function download(_x, _x2) {
        return _download.apply(this, arguments);
      }
      return download;
    }()
  }], [{
    key: "closeModal",
    value: function closeModal() {
      $(document).find('#modal_download_url').modal('hide');
    }
  }]);
  return DownloadService;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Services/FolderService.js":
/*!*******************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Services/FolderService.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   FolderService: () => (/* binding */ FolderService)
/* harmony export */ });
/* harmony import */ var _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Config/MediaConfig */ "./platform/core/media/resources/assets/js/App/Config/MediaConfig.js");
/* harmony import */ var _MediaService__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MediaService */ "./platform/core/media/resources/assets/js/App/Services/MediaService.js");
/* harmony import */ var _MessageService__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MessageService */ "./platform/core/media/resources/assets/js/App/Services/MessageService.js");
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }




var FolderService = /*#__PURE__*/function () {
  function FolderService() {
    _classCallCheck(this, FolderService);
    this.MediaService = new _MediaService__WEBPACK_IMPORTED_MODULE_1__.MediaService();
    $(document).on('shown.bs.modal', '#modal_add_folder', function (event) {
      $(event.currentTarget).find('.form-add-folder input[type=text]').focus();
    });
  }
  _createClass(FolderService, [{
    key: "create",
    value: function create(folderName) {
      var _self = this;
      $.ajax({
        url: RV_MEDIA_URL.create_folder,
        type: 'POST',
        data: {
          parent_id: _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_3__.Helpers.getRequestParams().folder_id,
          name: folderName
        },
        dataType: 'json',
        beforeSend: function beforeSend() {
          _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_3__.Helpers.showAjaxLoading();
        },
        success: function success(res) {
          if (res.error) {
            _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.showMessage('error', res.message, RV_MEDIA_CONFIG.translations.message.error_header);
          } else {
            _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.showMessage('success', res.message, RV_MEDIA_CONFIG.translations.message.success_header);
            _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_3__.Helpers.resetPagination();
            _self.MediaService.getMedia(true);
            FolderService.closeModal();
          }
        },
        complete: function complete() {
          _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_3__.Helpers.hideAjaxLoading();
        },
        error: function error(data) {
          _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.handleError(data);
        }
      });
    }
  }, {
    key: "changeFolder",
    value: function changeFolder(folderId) {
      _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params.folder_id = folderId;
      _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_3__.Helpers.storeConfig();
      this.MediaService.getMedia(true);
    }
  }], [{
    key: "closeModal",
    value: function closeModal() {
      $(document).find('#modal_add_folder').modal('hide');
    }
  }]);
  return FolderService;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Services/MediaService.js":
/*!******************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Services/MediaService.js ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   MediaService: () => (/* binding */ MediaService)
/* harmony export */ });
/* harmony import */ var _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Config/MediaConfig */ "./platform/core/media/resources/assets/js/App/Config/MediaConfig.js");
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
/* harmony import */ var _MessageService__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./MessageService */ "./platform/core/media/resources/assets/js/App/Services/MessageService.js");
/* harmony import */ var _ActionsService__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ActionsService */ "./platform/core/media/resources/assets/js/App/Services/ActionsService.js");
/* harmony import */ var _ContextMenuService__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ContextMenuService */ "./platform/core/media/resources/assets/js/App/Services/ContextMenuService.js");
/* harmony import */ var _Views_MediaList__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../Views/MediaList */ "./platform/core/media/resources/assets/js/App/Views/MediaList.js");
/* harmony import */ var _Views_MediaDetails__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../Views/MediaDetails */ "./platform/core/media/resources/assets/js/App/Views/MediaDetails.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }







var MediaService = /*#__PURE__*/function () {
  function MediaService() {
    _classCallCheck(this, MediaService);
    this.MediaList = new _Views_MediaList__WEBPACK_IMPORTED_MODULE_5__.MediaList();
    this.MediaDetails = new _Views_MediaDetails__WEBPACK_IMPORTED_MODULE_6__.MediaDetails();
    this.breadcrumbTemplate = $('#rv_media_breadcrumb_item').html();
  }
  _createClass(MediaService, [{
    key: "getMedia",
    value: function getMedia() {
      var reload = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      var is_popup = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var load_more_file = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
      if (typeof RV_MEDIA_CONFIG.pagination != 'undefined') {
        if (RV_MEDIA_CONFIG.pagination.in_process_get_media) {
          return;
        }
        RV_MEDIA_CONFIG.pagination.in_process_get_media = true;
      }
      var _self = this;
      _self.getFileDetails({
        icon: 'far fa-image',
        nothing_selected: ''
      });
      var params = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams();
      if (params.view_in === 'recent') {
        params.recent_items = _Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.RecentItems;
      }
      if (is_popup === true) {
        params.is_popup = true;
      } else {
        params.is_popup = undefined;
      }
      params.onSelectFiles = undefined;
      if (typeof params.search != 'undefined' && params.search != '' && typeof params.selected_file_id != 'undefined') {
        params.selected_file_id = undefined;
      }
      params.load_more_file = load_more_file;
      if (typeof RV_MEDIA_CONFIG.pagination != 'undefined') {
        params.paged = RV_MEDIA_CONFIG.pagination.paged;
        params.posts_per_page = RV_MEDIA_CONFIG.pagination.posts_per_page;
      }
      $.ajax({
        url: RV_MEDIA_URL.get_media,
        type: 'GET',
        data: params,
        dataType: 'json',
        beforeSend: function beforeSend() {
          _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.showAjaxLoading();
        },
        success: function success(res) {
          _self.MediaList.renderData(res.data, reload, load_more_file);
          _self.renderBreadcrumbs(res.data.breadcrumbs);
          MediaService.refreshFilter();
          _ActionsService__WEBPACK_IMPORTED_MODULE_3__.ActionsService.renderActions();
          if (typeof RV_MEDIA_CONFIG.pagination != 'undefined') {
            if (typeof RV_MEDIA_CONFIG.pagination.paged != 'undefined') {
              RV_MEDIA_CONFIG.pagination.paged += 1;
            }
            if (typeof RV_MEDIA_CONFIG.pagination.in_process_get_media != 'undefined') {
              RV_MEDIA_CONFIG.pagination.in_process_get_media = false;
            }
            if (typeof RV_MEDIA_CONFIG.pagination.posts_per_page != 'undefined' && res.data.files.length + res.data.folders.length < RV_MEDIA_CONFIG.pagination.posts_per_page && typeof RV_MEDIA_CONFIG.pagination.has_more != 'undefined') {
              RV_MEDIA_CONFIG.pagination.has_more = false;
            }
          }
        },
        complete: function complete() {
          _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.hideAjaxLoading();
        },
        error: function error(data) {
          _MessageService__WEBPACK_IMPORTED_MODULE_2__.MessageService.handleError(data);
        }
      });
    }
  }, {
    key: "getFileDetails",
    value: function getFileDetails(data) {
      this.MediaDetails.renderData(data);
    }
  }, {
    key: "renderBreadcrumbs",
    value: function renderBreadcrumbs(breadcrumbItems) {
      var _self = this;
      var $breadcrumbContainer = $('.rv-media-breadcrumb .breadcrumb');
      $breadcrumbContainer.find('li').remove();
      _.each(breadcrumbItems, function (value) {
        var template = _self.breadcrumbTemplate;
        template = template.replace(/__name__/gi, value.name || '').replace(/__icon__/gi, value.icon ? '<i class="' + value.icon + '"></i>' : '').replace(/__folderId__/gi, value.id || 0);
        $breadcrumbContainer.append($(template));
      });
      $('.rv-media-container').attr('data-breadcrumb-count', _.size(breadcrumbItems));
    }
  }], [{
    key: "refreshFilter",
    value: function refreshFilter() {
      var $rvMediaContainer = $('.rv-media-container');
      var viewIn = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().view_in;
      if (viewIn !== 'all_media' && !_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().folder_id) {
        $('.rv-media-actions .btn:not([data-type="refresh"]):not(label)').addClass('disabled');
        $rvMediaContainer.attr('data-allow-upload', 'false');
      } else {
        $('.rv-media-actions .btn:not([data-type="refresh"]):not(label)').removeClass('disabled');
        $rvMediaContainer.attr('data-allow-upload', 'true');
      }
      $('.rv-media-actions .btn.js-rv-media-change-filter-group').removeClass('disabled');
      var $empty_trash_btn = $('.rv-media-actions .btn[data-action="empty_trash"]');
      if (viewIn === 'trash') {
        $empty_trash_btn.removeClass('hidden').removeClass('disabled');
        if (!_.size(_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getItems())) {
          $empty_trash_btn.addClass('hidden').addClass('disabled');
        }
      } else {
        $empty_trash_btn.addClass('hidden');
      }
      _ContextMenuService__WEBPACK_IMPORTED_MODULE_4__.ContextMenuService.destroyContext();
      _ContextMenuService__WEBPACK_IMPORTED_MODULE_4__.ContextMenuService.initContext();
      $rvMediaContainer.attr('data-view-in', viewIn);
    }
  }]);
  return MediaService;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Services/MessageService.js":
/*!********************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Services/MessageService.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   MessageService: () => (/* binding */ MessageService)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
var MessageService = /*#__PURE__*/function () {
  function MessageService() {
    _classCallCheck(this, MessageService);
  }
  _createClass(MessageService, null, [{
    key: "showMessage",
    value: function showMessage(type, message) {
      toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-bottom-right',
        onclick: null,
        showDuration: 1000,
        hideDuration: 1000,
        timeOut: 10000,
        extendedTimeOut: 1000,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
      };
      var messageHeader = '';
      switch (type) {
        case 'error':
          messageHeader = RV_MEDIA_CONFIG.translations.message.error_header;
          break;
        case 'success':
          messageHeader = RV_MEDIA_CONFIG.translations.message.success_header;
          break;
      }
      toastr[type](message, messageHeader);
    }
  }, {
    key: "handleError",
    value: function handleError(data) {
      if (typeof data.responseJSON !== 'undefined' && !_.isArray(data.errors)) {
        MessageService.handleValidationError(data.responseJSON.errors);
      } else {
        if (typeof data.responseJSON !== 'undefined') {
          if (typeof data.responseJSON.errors !== 'undefined') {
            if (data.status === 422) {
              MessageService.handleValidationError(data.responseJSON.errors);
            }
          } else if (typeof data.responseJSON.message !== 'undefined') {
            MessageService.showMessage('error', data.responseJSON.message);
          } else {
            $.each(data.responseJSON, function (index, el) {
              $.each(el, function (key, item) {
                MessageService.showMessage('error', item);
              });
            });
          }
        } else {
          MessageService.showMessage('error', data.statusText);
        }
      }
    }
  }, {
    key: "handleValidationError",
    value: function handleValidationError(errors) {
      var message = '';
      $.each(errors, function (index, item) {
        message += item + '<br />';
        var $input = $('*[name="' + index + '"]');
        $input.addClass('field-has-error');
        var $input_array = $('*[name$="[' + index + ']"]');
        $input_array.addClass('field-has-error');
      });
      MessageService.showMessage('error', message);
    }
  }]);
  return MessageService;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Services/UploadService.js":
/*!*******************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Services/UploadService.js ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   UploadService: () => (/* binding */ UploadService)
/* harmony export */ });
/* harmony import */ var _MediaService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MediaService */ "./platform/core/media/resources/assets/js/App/Services/MediaService.js");
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }
function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }


var UploadService = /*#__PURE__*/function () {
  function UploadService() {
    _classCallCheck(this, UploadService);
    this.$body = $('body');
    this.dropZone = null;
    this.uploadUrl = RV_MEDIA_URL.upload_file;
    this.uploadProgressBox = $('.rv-upload-progress');
    this.uploadProgressContainer = $('.rv-upload-progress .rv-upload-progress-table');
    this.uploadProgressTemplate = $('#rv_media_upload_progress_item').html();
    this.totalQueued = 1;
    this.MediaService = new _MediaService__WEBPACK_IMPORTED_MODULE_0__.MediaService();
    this.totalError = 0;
  }
  _createClass(UploadService, [{
    key: "init",
    value: function init() {
      if (_.includes(RV_MEDIA_CONFIG.permissions, 'files.create') && $('.rv-media-items').length > 0) {
        this.setupDropZone();
      }
      this.handleEvents();
    }
  }, {
    key: "setupDropZone",
    value: function setupDropZone() {
      var _self = this;
      var _dropZoneConfig = this.getDropZoneConfig();
      _self.filesUpload = 0;
      if (_self.dropZone) {
        _self.dropZone.destroy();
      }
      _self.dropZone = new Dropzone(document.querySelector('.rv-media-items'), _objectSpread(_objectSpread({}, _dropZoneConfig), {}, {
        thumbnailWidth: false,
        thumbnailHeight: false,
        parallelUploads: 1,
        autoQueue: true,
        clickable: '.js-dropzone-upload',
        previewTemplate: false,
        previewsContainer: false,
        sending: function sending(file, xhr, formData) {
          formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
          formData.append('folder_id', _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().folder_id);
          formData.append('view_in', _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().view_in);
          formData.append('path', file.fullPath);
        },
        chunksUploaded: function chunksUploaded(file, done) {
          _self.uploadProgressContainer.find('.progress-percent').html('100%');
          done();
        },
        accept: function accept(file, done) {
          _self.filesUpload++;
          _self.totalError = 0;
          done();
        },
        uploadprogress: function uploadprogress(file, progress, bytesSent) {
          var percent = bytesSent / file.size * 100;
          if (file.upload.chunked && percent > 99) {
            percent = percent - 1;
          }
          var percentShow = (percent > 100 ? '100' : parseInt(percent)) + '%';
          var el = _self.uploadProgressContainer.find('li').eq(file.index - 1);
          el.find('.progress-percent').html(percentShow);
        }
      }));
      _self.dropZone.on('addedfile', function (file) {
        file.index = _self.totalQueued;
        _self.totalQueued++;
      });
      _self.dropZone.on('sending', function (file) {
        _self.initProgress(file.name, file.size);
      });
      _self.dropZone.on('complete', function (file) {
        if (file.accepted) {
          _self.changeProgressStatus(file);
        }
        _self.filesUpload = 0;
      });
      _self.dropZone.on('queuecomplete', function () {
        _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
        _self.MediaService.getMedia(true);
        if (_self.totalError === 0) {
          setTimeout(function () {
            $('.rv-upload-progress .close-pane').trigger('click');
          }, 5000);
        }
      });
    }
  }, {
    key: "handleEvents",
    value: function handleEvents() {
      var _self = this;
      /**
       * Close upload progress pane
       */
      _self.$body.off('click', '.rv-upload-progress .close-pane').on('click', '.rv-upload-progress .close-pane', function (event) {
        event.preventDefault();
        $('.rv-upload-progress').addClass('hide-the-pane');
        _self.totalError = 0;
        setTimeout(function () {
          $('.rv-upload-progress li').remove();
          _self.totalQueued = 1;
        }, 300);
      });
    }
  }, {
    key: "initProgress",
    value: function initProgress($fileName, $fileSize) {
      var template = this.uploadProgressTemplate.replace(/__fileName__/gi, $fileName).replace(/__fileSize__/gi, UploadService.formatFileSize($fileSize)).replace(/__status__/gi, 'warning').replace(/__message__/gi, 'Uploading');
      if (this.checkUploadTotalProgress() && this.uploadProgressContainer.find('li').length >= 1) {
        return;
      }
      this.uploadProgressContainer.append(template);
      this.uploadProgressBox.removeClass('hide-the-pane');
      this.uploadProgressBox.find('.panel-body').animate({
        scrollTop: this.uploadProgressContainer.height()
      }, 150);
    }
  }, {
    key: "changeProgressStatus",
    value: function changeProgressStatus(file) {
      var _self = this;
      var $progressLine = _self.uploadProgressContainer.find('li:nth-child(' + file.index + ')');
      if (this.checkUploadTotalProgress()) {
        $progressLine = _self.uploadProgressContainer.find('li:first');
      }
      var $label = $progressLine.find('.label');
      $label.removeClass('label-success label-danger label-warning');
      var response = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.jsonDecode(file.xhr.responseText || '', {});
      _self.totalError = _self.totalError + (response.error === true || file.status === 'error' ? 1 : 0);
      $label.addClass(response.error === true || file.status === 'error' ? 'label-danger' : 'label-success');
      $label.html(response.error === true || file.status === 'error' ? 'Error' : 'Uploaded');
      if (file.status === 'error') {
        if (file.xhr.status === 422) {
          var errorHtml = '';
          $.each(response.errors, function (key, item) {
            errorHtml += '<span class="text-danger">' + item + '</span><br>';
          });
          $progressLine.find('.file-error').html(errorHtml);
        } else if (file.xhr.status === 500) {
          $progressLine.find('.file-error').html('<span class="text-danger">' + file.xhr.statusText + '</span>');
        }
      } else if (response.error) {
        $progressLine.find('.file-error').html('<span class="text-danger">' + response.message + '</span>');
      } else {
        _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.addToRecent(response.data.id);
        _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.setSelectedFile(response.data.id);
      }
    }
  }, {
    key: "getDropZoneConfig",
    value: function getDropZoneConfig() {
      return {
        url: this.uploadUrl,
        uploadMultiple: !RV_MEDIA_CONFIG.chunk.enabled,
        chunking: RV_MEDIA_CONFIG.chunk.enabled,
        forceChunking: true,
        // forces chunking when file.size < chunkSize
        parallelChunkUploads: false,
        // allows chunks to be uploaded in parallel (this is independent of the parallelUploads option)
        chunkSize: RV_MEDIA_CONFIG.chunk.chunk_size,
        // chunk size 1,000,000 bytes (~1MB)
        retryChunks: true,
        // retry chunks on failure
        retryChunksLimit: 3,
        // retry maximum of 3 times (default is 3)
        timeout: 0,
        // MB,
        maxFilesize: RV_MEDIA_CONFIG.chunk.max_file_size,
        // MB
        maxFiles: null // max files upload,
      };
    }
  }, {
    key: "checkUploadTotalProgress",
    value: function checkUploadTotalProgress() {
      return this.filesUpload === 1;
    }
  }], [{
    key: "formatFileSize",
    value: function formatFileSize(bytes) {
      var si = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var thresh = si ? 1000 : 1024;
      if (Math.abs(bytes) < thresh) {
        return bytes + ' B';
      }
      var units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
      var u = -1;
      do {
        bytes /= thresh;
        ++u;
      } while (Math.abs(bytes) >= thresh && u < units.length - 1);
      return bytes.toFixed(1) + ' ' + units[u];
    }
  }]);
  return UploadService;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Views/MediaDetails.js":
/*!***************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Views/MediaDetails.js ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   MediaDetails: () => (/* binding */ MediaDetails)
/* harmony export */ });
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }

var MediaDetails = /*#__PURE__*/function () {
  function MediaDetails() {
    _classCallCheck(this, MediaDetails);
    this.$detailsWrapper = $('.rv-media-main .rv-media-details');
    this.descriptionItemTemplate = '<div class="rv-media-name"><p>__title__</p>__url__</div>';
    this.onlyFields = ['name', 'alt', 'full_url', 'size', 'mime_type', 'created_at', 'updated_at', 'nothing_selected'];
  }
  _createClass(MediaDetails, [{
    key: "renderData",
    value: function renderData(data) {
      var _this = this;
      var _self = this;
      var thumb = data.type === 'image' ? '<img src="' + data.full_url + '" alt="' + data.name + '">' : '<i class="' + data.icon + '"></i>';
      var description = '';
      var useClipboard = false;
      _.forEach(data, function (val, index) {
        if (_.includes(_self.onlyFields, index) && val) {
          if (!_.includes(['size', 'mime_type'], index)) {
            description += _self.descriptionItemTemplate.replace(/__title__/gi, RV_MEDIA_CONFIG.translations[index]).replace(/__url__/gi, val ? index === 'full_url' ? '<div class="input-group"><input id="file_details_url" type="text" value="' + val + '" class="form-control"><span class="input-group-text"><button class="btn btn-default js-btn-copy-to-clipboard" type="button" data-clipboard-target="#file_details_url" title="Copied"><img class="clippy" src="' + _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.asset('/vendor/core/core/media/images/clippy.svg') + '" width="13" alt="Copy to clipboard"></button></span></div>' : '<span title="' + val + '">' + val + '</span>' : '');
            if (index === 'full_url') {
              useClipboard = true;
            }
          }
        }
      });
      _self.$detailsWrapper.find('.rv-media-thumbnail').html(thumb);
      _self.$detailsWrapper.find('.rv-media-description').html(description);
      if (useClipboard) {
        new Clipboard('.js-btn-copy-to-clipboard');
        $('.js-btn-copy-to-clipboard').tooltip().on('mouseenter', function () {
          $(_this).tooltip('hide');
        }).on('mouseleave', function () {
          $(_this).tooltip('hide');
        });
      }
    }
  }]);
  return MediaDetails;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/App/Views/MediaList.js":
/*!************************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/App/Views/MediaList.js ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   MediaList: () => (/* binding */ MediaList)
/* harmony export */ });
/* harmony import */ var _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
/* harmony import */ var _Services_ActionsService__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../Services/ActionsService */ "./platform/core/media/resources/assets/js/App/Services/ActionsService.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }


var MediaList = /*#__PURE__*/function () {
  function MediaList() {
    _classCallCheck(this, MediaList);
    this.group = {};
    this.group.list = $('#rv_media_items_list').html();
    this.group.tiles = $('#rv_media_items_tiles').html();
    this.item = {};
    this.item.list = $('#rv_media_items_list_element').html();
    this.item.tiles = $('#rv_media_items_tiles_element').html();
    this.$groupContainer = $('.rv-media-items');
  }
  _createClass(MediaList, [{
    key: "renderData",
    value: function renderData(data) {
      var reload = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var load_more_file = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
      var _self = this;
      var MediaConfig = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getConfigs();
      var template = _self.group[_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getRequestParams().view_type];
      var view_in = _Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getRequestParams().view_in;
      if (!_.includes(['all_media', 'public', 'trash', 'favorites', 'recent'], view_in)) {
        view_in = 'all_media';
      }
      template = template.replace(/__noItemIcon__/gi, RV_MEDIA_CONFIG.translations.no_item[view_in].icon || '').replace(/__noItemTitle__/gi, RV_MEDIA_CONFIG.translations.no_item[view_in].title || '').replace(/__noItemMessage__/gi, RV_MEDIA_CONFIG.translations.no_item[view_in].message || '');
      var $result = $(template);
      var $itemsWrapper = $result.find('ul');
      if (load_more_file && this.$groupContainer.find('.rv-media-grid ul').length > 0) {
        $itemsWrapper = this.$groupContainer.find('.rv-media-grid ul');
      }
      if (_.size(data.folders) > 0 || _.size(data.files) > 0 || load_more_file) {
        $('.rv-media-items').addClass('has-items');
      } else {
        $('.rv-media-items').removeClass('has-items');
      }
      _.forEach(data.folders, function (value) {
        var item = _self.item[_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getRequestParams().view_type];
        item = item.replace(/__type__/gi, 'folder').replace(/__id__/gi, value.id).replace(/__name__/gi, value.name || '').replace(/__size__/gi, '').replace(/__date__/gi, value.created_at || '').replace(/__thumb__/gi, '<i class="fa fa-folder"></i>');
        var $item = $(item);
        _.forEach(value, function (val, index) {
          $item.data(index, val);
        });
        $item.data('is_folder', true);
        $item.data('icon', MediaConfig.icons.folder);
        $itemsWrapper.append($item);
      });
      _.forEach(data.files, function (value) {
        var item = _self.item[_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getRequestParams().view_type];
        item = item.replace(/__type__/gi, 'file').replace(/__id__/gi, value.id).replace(/__name__/gi, value.name || '').replace(/__size__/gi, value.size || '').replace(/__date__/gi, value.created_at || '');
        if (_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getRequestParams().view_type === 'list') {
          item = item.replace(/__thumb__/gi, '<i class="' + value.icon + '"></i>');
        } else {
          item = item.replace(/__thumb__/gi, value.type === 'image' ? '<img src="' + (value.thumb ? value.thumb : value.full_url) + '" alt="' + value.name + '">' : '<i class="' + value.icon + '"></i>');
        }
        var $item = $(item);
        $item.data('is_folder', false);
        _.forEach(value, function (val, index) {
          $item.data(index, val);
        });
        $itemsWrapper.append($item);
      });
      if (reload !== false) {
        _self.$groupContainer.empty();
      }
      if (!(load_more_file && this.$groupContainer.find('.rv-media-grid ul').length > 0)) {
        _self.$groupContainer.append($result);
      }
      _self.$groupContainer.find('.loading-wrapper').remove();
      _Services_ActionsService__WEBPACK_IMPORTED_MODULE_1__.ActionsService.handleDropdown();

      // Trigger event click for file selected
      $('.js-media-list-title[data-id=' + data.selected_file_id + ']').trigger('click');
    }
  }]);
  return MediaList;
}();

/***/ }),

/***/ "./platform/core/media/resources/assets/js/integrate.js":
/*!**************************************************************!*\
  !*** ./platform/core/media/resources/assets/js/integrate.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   EditorService: () => (/* binding */ EditorService)
/* harmony export */ });
/* harmony import */ var _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./App/Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
/* harmony import */ var _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./App/Config/MediaConfig */ "./platform/core/media/resources/assets/js/App/Config/MediaConfig.js");
/* harmony import */ var _App_Services_ContextMenuService__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./App/Services/ContextMenuService */ "./platform/core/media/resources/assets/js/App/Services/ContextMenuService.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }



var EditorService = /*#__PURE__*/function () {
  function EditorService() {
    _classCallCheck(this, EditorService);
  }
  _createClass(EditorService, null, [{
    key: "editorSelectFile",
    value: function editorSelectFile(selectedFiles) {
      var is_ckeditor = _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getUrlParam('CKEditor') || _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getUrlParam('CKEditorFuncNum');
      if (window.opener && is_ckeditor) {
        var firstItem = _.first(selectedFiles);
        window.opener.CKEDITOR.tools.callFunction(_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getUrlParam('CKEditorFuncNum'), firstItem.full_url);
        if (window.opener) {
          window.close();
        }
      } else {
        // No WYSIWYG editor found, use custom method.
      }
    }
  }]);
  return EditorService;
}();
var rvMedia = /*#__PURE__*/_createClass(function rvMedia(selector, options) {
  _classCallCheck(this, rvMedia);
  window.rvMedia = window.rvMedia || {};
  var $body = $('body');
  var defaultOptions = {
    multiple: true,
    type: '*',
    onSelectFiles: function onSelectFiles(files, $el) {}
  };
  options = $.extend(true, defaultOptions, options);
  var clickCallback = function clickCallback(event) {
    event.preventDefault();
    var $current = $(event.currentTarget);
    $('#rv_media_modal').modal('show');
    window.rvMedia.options = options;
    window.rvMedia.options.open_in = 'modal';
    window.rvMedia.$el = $current;
    _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_1__.MediaConfig.request_params.filter = 'everything';
    _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.storeConfig();
    var elementOptions = window.rvMedia.$el.data('rv-media');
    if (typeof elementOptions !== 'undefined' && elementOptions.length > 0) {
      elementOptions = elementOptions[0];
      window.rvMedia.options = $.extend(true, window.rvMedia.options, elementOptions || {});
      if (typeof elementOptions.selected_file_id !== 'undefined') {
        window.rvMedia.options.is_popup = true;
      } else if (typeof window.rvMedia.options.is_popup !== 'undefined') {
        window.rvMedia.options.is_popup = undefined;
      }
    }
    if ($('#rv_media_body .rv-media-container').length === 0) {
      $('#rv_media_body').load(RV_MEDIA_URL.popup, function (data) {
        if (data.error) {
          alert(data.message);
        }
        $('#rv_media_body').removeClass('media-modal-loading').closest('.modal-content').removeClass('bb-loading');
        $(document).find('.rv-media-container .js-change-action[data-type=refresh]').trigger('click');
        if (_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getRequestParams().filter !== 'everything') {
          $('.rv-media-actions .btn.js-rv-media-change-filter-group.js-filter-by-type').hide();
        }
        _App_Services_ContextMenuService__WEBPACK_IMPORTED_MODULE_2__.ContextMenuService.destroyContext();
        _App_Services_ContextMenuService__WEBPACK_IMPORTED_MODULE_2__.ContextMenuService.initContext();
      });
    } else {
      $(document).find('.rv-media-container .js-change-action[data-type=refresh]').trigger('click');
    }
  };
  if (typeof selector === 'string') {
    $body.off('click', selector).on('click', selector, clickCallback);
  } else {
    selector.off('click').on('click', clickCallback);
  }
});
window.RvMediaStandAlone = rvMedia;
$('.js-insert-to-editor').off('click').on('click', function (event) {
  event.preventDefault();
  var selectedFiles = _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.getSelectedFiles();
  if (_.size(selectedFiles) > 0) {
    EditorService.editorSelectFile(selectedFiles);
  }
});
$.fn.rvMedia = function (options) {
  var $selector = $(this);
  _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_1__.MediaConfig.request_params.filter = 'everything';
  $(document).find('.js-insert-to-editor').prop('disabled', _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_1__.MediaConfig.request_params.view_in === 'trash');
  _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_0__.Helpers.storeConfig();
  new rvMedia($selector, options);
};

/***/ }),

/***/ "./node_modules/cropperjs/dist/cropper.js":
/*!************************************************!*\
  !*** ./node_modules/cropperjs/dist/cropper.js ***!
  \************************************************/
/***/ (function(module) {

/*!
 * Cropper.js v1.5.13
 * https://fengyuanchen.github.io/cropperjs
 *
 * Copyright 2015-present Chen Fengyuan
 * Released under the MIT license
 *
 * Date: 2022-11-20T05:30:46.114Z
 */

(function (global, factory) {
   true ? module.exports = factory() :
  0;
})(this, (function () { 'use strict';

  function ownKeys(object, enumerableOnly) {
    var keys = Object.keys(object);
    if (Object.getOwnPropertySymbols) {
      var symbols = Object.getOwnPropertySymbols(object);
      enumerableOnly && (symbols = symbols.filter(function (sym) {
        return Object.getOwnPropertyDescriptor(object, sym).enumerable;
      })), keys.push.apply(keys, symbols);
    }
    return keys;
  }
  function _objectSpread2(target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = null != arguments[i] ? arguments[i] : {};
      i % 2 ? ownKeys(Object(source), !0).forEach(function (key) {
        _defineProperty(target, key, source[key]);
      }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) {
        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
      });
    }
    return target;
  }
  function _typeof(obj) {
    "@babel/helpers - typeof";

    return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
      return typeof obj;
    } : function (obj) {
      return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    }, _typeof(obj);
  }
  function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError("Cannot call a class as a function");
    }
  }
  function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }
  function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    Object.defineProperty(Constructor, "prototype", {
      writable: false
    });
    return Constructor;
  }
  function _defineProperty(obj, key, value) {
    if (key in obj) {
      Object.defineProperty(obj, key, {
        value: value,
        enumerable: true,
        configurable: true,
        writable: true
      });
    } else {
      obj[key] = value;
    }
    return obj;
  }
  function _toConsumableArray(arr) {
    return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
  }
  function _arrayWithoutHoles(arr) {
    if (Array.isArray(arr)) return _arrayLikeToArray(arr);
  }
  function _iterableToArray(iter) {
    if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
  }
  function _unsupportedIterableToArray(o, minLen) {
    if (!o) return;
    if (typeof o === "string") return _arrayLikeToArray(o, minLen);
    var n = Object.prototype.toString.call(o).slice(8, -1);
    if (n === "Object" && o.constructor) n = o.constructor.name;
    if (n === "Map" || n === "Set") return Array.from(o);
    if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
  }
  function _arrayLikeToArray(arr, len) {
    if (len == null || len > arr.length) len = arr.length;
    for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];
    return arr2;
  }
  function _nonIterableSpread() {
    throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }

  var IS_BROWSER = typeof window !== 'undefined' && typeof window.document !== 'undefined';
  var WINDOW = IS_BROWSER ? window : {};
  var IS_TOUCH_DEVICE = IS_BROWSER && WINDOW.document.documentElement ? 'ontouchstart' in WINDOW.document.documentElement : false;
  var HAS_POINTER_EVENT = IS_BROWSER ? 'PointerEvent' in WINDOW : false;
  var NAMESPACE = 'cropper';

  // Actions
  var ACTION_ALL = 'all';
  var ACTION_CROP = 'crop';
  var ACTION_MOVE = 'move';
  var ACTION_ZOOM = 'zoom';
  var ACTION_EAST = 'e';
  var ACTION_WEST = 'w';
  var ACTION_SOUTH = 's';
  var ACTION_NORTH = 'n';
  var ACTION_NORTH_EAST = 'ne';
  var ACTION_NORTH_WEST = 'nw';
  var ACTION_SOUTH_EAST = 'se';
  var ACTION_SOUTH_WEST = 'sw';

  // Classes
  var CLASS_CROP = "".concat(NAMESPACE, "-crop");
  var CLASS_DISABLED = "".concat(NAMESPACE, "-disabled");
  var CLASS_HIDDEN = "".concat(NAMESPACE, "-hidden");
  var CLASS_HIDE = "".concat(NAMESPACE, "-hide");
  var CLASS_INVISIBLE = "".concat(NAMESPACE, "-invisible");
  var CLASS_MODAL = "".concat(NAMESPACE, "-modal");
  var CLASS_MOVE = "".concat(NAMESPACE, "-move");

  // Data keys
  var DATA_ACTION = "".concat(NAMESPACE, "Action");
  var DATA_PREVIEW = "".concat(NAMESPACE, "Preview");

  // Drag modes
  var DRAG_MODE_CROP = 'crop';
  var DRAG_MODE_MOVE = 'move';
  var DRAG_MODE_NONE = 'none';

  // Events
  var EVENT_CROP = 'crop';
  var EVENT_CROP_END = 'cropend';
  var EVENT_CROP_MOVE = 'cropmove';
  var EVENT_CROP_START = 'cropstart';
  var EVENT_DBLCLICK = 'dblclick';
  var EVENT_TOUCH_START = IS_TOUCH_DEVICE ? 'touchstart' : 'mousedown';
  var EVENT_TOUCH_MOVE = IS_TOUCH_DEVICE ? 'touchmove' : 'mousemove';
  var EVENT_TOUCH_END = IS_TOUCH_DEVICE ? 'touchend touchcancel' : 'mouseup';
  var EVENT_POINTER_DOWN = HAS_POINTER_EVENT ? 'pointerdown' : EVENT_TOUCH_START;
  var EVENT_POINTER_MOVE = HAS_POINTER_EVENT ? 'pointermove' : EVENT_TOUCH_MOVE;
  var EVENT_POINTER_UP = HAS_POINTER_EVENT ? 'pointerup pointercancel' : EVENT_TOUCH_END;
  var EVENT_READY = 'ready';
  var EVENT_RESIZE = 'resize';
  var EVENT_WHEEL = 'wheel';
  var EVENT_ZOOM = 'zoom';

  // Mime types
  var MIME_TYPE_JPEG = 'image/jpeg';

  // RegExps
  var REGEXP_ACTIONS = /^e|w|s|n|se|sw|ne|nw|all|crop|move|zoom$/;
  var REGEXP_DATA_URL = /^data:/;
  var REGEXP_DATA_URL_JPEG = /^data:image\/jpeg;base64,/;
  var REGEXP_TAG_NAME = /^img|canvas$/i;

  // Misc
  // Inspired by the default width and height of a canvas element.
  var MIN_CONTAINER_WIDTH = 200;
  var MIN_CONTAINER_HEIGHT = 100;

  var DEFAULTS = {
    // Define the view mode of the cropper
    viewMode: 0,
    // 0, 1, 2, 3

    // Define the dragging mode of the cropper
    dragMode: DRAG_MODE_CROP,
    // 'crop', 'move' or 'none'

    // Define the initial aspect ratio of the crop box
    initialAspectRatio: NaN,
    // Define the aspect ratio of the crop box
    aspectRatio: NaN,
    // An object with the previous cropping result data
    data: null,
    // A selector for adding extra containers to preview
    preview: '',
    // Re-render the cropper when resize the window
    responsive: true,
    // Restore the cropped area after resize the window
    restore: true,
    // Check if the current image is a cross-origin image
    checkCrossOrigin: true,
    // Check the current image's Exif Orientation information
    checkOrientation: true,
    // Show the black modal
    modal: true,
    // Show the dashed lines for guiding
    guides: true,
    // Show the center indicator for guiding
    center: true,
    // Show the white modal to highlight the crop box
    highlight: true,
    // Show the grid background
    background: true,
    // Enable to crop the image automatically when initialize
    autoCrop: true,
    // Define the percentage of automatic cropping area when initializes
    autoCropArea: 0.8,
    // Enable to move the image
    movable: true,
    // Enable to rotate the image
    rotatable: true,
    // Enable to scale the image
    scalable: true,
    // Enable to zoom the image
    zoomable: true,
    // Enable to zoom the image by dragging touch
    zoomOnTouch: true,
    // Enable to zoom the image by wheeling mouse
    zoomOnWheel: true,
    // Define zoom ratio when zoom the image by wheeling mouse
    wheelZoomRatio: 0.1,
    // Enable to move the crop box
    cropBoxMovable: true,
    // Enable to resize the crop box
    cropBoxResizable: true,
    // Toggle drag mode between "crop" and "move" when click twice on the cropper
    toggleDragModeOnDblclick: true,
    // Size limitation
    minCanvasWidth: 0,
    minCanvasHeight: 0,
    minCropBoxWidth: 0,
    minCropBoxHeight: 0,
    minContainerWidth: MIN_CONTAINER_WIDTH,
    minContainerHeight: MIN_CONTAINER_HEIGHT,
    // Shortcuts of events
    ready: null,
    cropstart: null,
    cropmove: null,
    cropend: null,
    crop: null,
    zoom: null
  };

  var TEMPLATE = '<div class="cropper-container" touch-action="none">' + '<div class="cropper-wrap-box">' + '<div class="cropper-canvas"></div>' + '</div>' + '<div class="cropper-drag-box"></div>' + '<div class="cropper-crop-box">' + '<span class="cropper-view-box"></span>' + '<span class="cropper-dashed dashed-h"></span>' + '<span class="cropper-dashed dashed-v"></span>' + '<span class="cropper-center"></span>' + '<span class="cropper-face"></span>' + '<span class="cropper-line line-e" data-cropper-action="e"></span>' + '<span class="cropper-line line-n" data-cropper-action="n"></span>' + '<span class="cropper-line line-w" data-cropper-action="w"></span>' + '<span class="cropper-line line-s" data-cropper-action="s"></span>' + '<span class="cropper-point point-e" data-cropper-action="e"></span>' + '<span class="cropper-point point-n" data-cropper-action="n"></span>' + '<span class="cropper-point point-w" data-cropper-action="w"></span>' + '<span class="cropper-point point-s" data-cropper-action="s"></span>' + '<span class="cropper-point point-ne" data-cropper-action="ne"></span>' + '<span class="cropper-point point-nw" data-cropper-action="nw"></span>' + '<span class="cropper-point point-sw" data-cropper-action="sw"></span>' + '<span class="cropper-point point-se" data-cropper-action="se"></span>' + '</div>' + '</div>';

  /**
   * Check if the given value is not a number.
   */
  var isNaN = Number.isNaN || WINDOW.isNaN;

  /**
   * Check if the given value is a number.
   * @param {*} value - The value to check.
   * @returns {boolean} Returns `true` if the given value is a number, else `false`.
   */
  function isNumber(value) {
    return typeof value === 'number' && !isNaN(value);
  }

  /**
   * Check if the given value is a positive number.
   * @param {*} value - The value to check.
   * @returns {boolean} Returns `true` if the given value is a positive number, else `false`.
   */
  var isPositiveNumber = function isPositiveNumber(value) {
    return value > 0 && value < Infinity;
  };

  /**
   * Check if the given value is undefined.
   * @param {*} value - The value to check.
   * @returns {boolean} Returns `true` if the given value is undefined, else `false`.
   */
  function isUndefined(value) {
    return typeof value === 'undefined';
  }

  /**
   * Check if the given value is an object.
   * @param {*} value - The value to check.
   * @returns {boolean} Returns `true` if the given value is an object, else `false`.
   */
  function isObject(value) {
    return _typeof(value) === 'object' && value !== null;
  }
  var hasOwnProperty = Object.prototype.hasOwnProperty;

  /**
   * Check if the given value is a plain object.
   * @param {*} value - The value to check.
   * @returns {boolean} Returns `true` if the given value is a plain object, else `false`.
   */
  function isPlainObject(value) {
    if (!isObject(value)) {
      return false;
    }
    try {
      var _constructor = value.constructor;
      var prototype = _constructor.prototype;
      return _constructor && prototype && hasOwnProperty.call(prototype, 'isPrototypeOf');
    } catch (error) {
      return false;
    }
  }

  /**
   * Check if the given value is a function.
   * @param {*} value - The value to check.
   * @returns {boolean} Returns `true` if the given value is a function, else `false`.
   */
  function isFunction(value) {
    return typeof value === 'function';
  }
  var slice = Array.prototype.slice;

  /**
   * Convert array-like or iterable object to an array.
   * @param {*} value - The value to convert.
   * @returns {Array} Returns a new array.
   */
  function toArray(value) {
    return Array.from ? Array.from(value) : slice.call(value);
  }

  /**
   * Iterate the given data.
   * @param {*} data - The data to iterate.
   * @param {Function} callback - The process function for each element.
   * @returns {*} The original data.
   */
  function forEach(data, callback) {
    if (data && isFunction(callback)) {
      if (Array.isArray(data) || isNumber(data.length) /* array-like */) {
        toArray(data).forEach(function (value, key) {
          callback.call(data, value, key, data);
        });
      } else if (isObject(data)) {
        Object.keys(data).forEach(function (key) {
          callback.call(data, data[key], key, data);
        });
      }
    }
    return data;
  }

  /**
   * Extend the given object.
   * @param {*} target - The target object to extend.
   * @param {*} args - The rest objects for merging to the target object.
   * @returns {Object} The extended object.
   */
  var assign = Object.assign || function assign(target) {
    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }
    if (isObject(target) && args.length > 0) {
      args.forEach(function (arg) {
        if (isObject(arg)) {
          Object.keys(arg).forEach(function (key) {
            target[key] = arg[key];
          });
        }
      });
    }
    return target;
  };
  var REGEXP_DECIMALS = /\.\d*(?:0|9){12}\d*$/;

  /**
   * Normalize decimal number.
   * Check out {@link https://0.30000000000000004.com/}
   * @param {number} value - The value to normalize.
   * @param {number} [times=100000000000] - The times for normalizing.
   * @returns {number} Returns the normalized number.
   */
  function normalizeDecimalNumber(value) {
    var times = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 100000000000;
    return REGEXP_DECIMALS.test(value) ? Math.round(value * times) / times : value;
  }
  var REGEXP_SUFFIX = /^width|height|left|top|marginLeft|marginTop$/;

  /**
   * Apply styles to the given element.
   * @param {Element} element - The target element.
   * @param {Object} styles - The styles for applying.
   */
  function setStyle(element, styles) {
    var style = element.style;
    forEach(styles, function (value, property) {
      if (REGEXP_SUFFIX.test(property) && isNumber(value)) {
        value = "".concat(value, "px");
      }
      style[property] = value;
    });
  }

  /**
   * Check if the given element has a special class.
   * @param {Element} element - The element to check.
   * @param {string} value - The class to search.
   * @returns {boolean} Returns `true` if the special class was found.
   */
  function hasClass(element, value) {
    return element.classList ? element.classList.contains(value) : element.className.indexOf(value) > -1;
  }

  /**
   * Add classes to the given element.
   * @param {Element} element - The target element.
   * @param {string} value - The classes to be added.
   */
  function addClass(element, value) {
    if (!value) {
      return;
    }
    if (isNumber(element.length)) {
      forEach(element, function (elem) {
        addClass(elem, value);
      });
      return;
    }
    if (element.classList) {
      element.classList.add(value);
      return;
    }
    var className = element.className.trim();
    if (!className) {
      element.className = value;
    } else if (className.indexOf(value) < 0) {
      element.className = "".concat(className, " ").concat(value);
    }
  }

  /**
   * Remove classes from the given element.
   * @param {Element} element - The target element.
   * @param {string} value - The classes to be removed.
   */
  function removeClass(element, value) {
    if (!value) {
      return;
    }
    if (isNumber(element.length)) {
      forEach(element, function (elem) {
        removeClass(elem, value);
      });
      return;
    }
    if (element.classList) {
      element.classList.remove(value);
      return;
    }
    if (element.className.indexOf(value) >= 0) {
      element.className = element.className.replace(value, '');
    }
  }

  /**
   * Add or remove classes from the given element.
   * @param {Element} element - The target element.
   * @param {string} value - The classes to be toggled.
   * @param {boolean} added - Add only.
   */
  function toggleClass(element, value, added) {
    if (!value) {
      return;
    }
    if (isNumber(element.length)) {
      forEach(element, function (elem) {
        toggleClass(elem, value, added);
      });
      return;
    }

    // IE10-11 doesn't support the second parameter of `classList.toggle`
    if (added) {
      addClass(element, value);
    } else {
      removeClass(element, value);
    }
  }
  var REGEXP_CAMEL_CASE = /([a-z\d])([A-Z])/g;

  /**
   * Transform the given string from camelCase to kebab-case
   * @param {string} value - The value to transform.
   * @returns {string} The transformed value.
   */
  function toParamCase(value) {
    return value.replace(REGEXP_CAMEL_CASE, '$1-$2').toLowerCase();
  }

  /**
   * Get data from the given element.
   * @param {Element} element - The target element.
   * @param {string} name - The data key to get.
   * @returns {string} The data value.
   */
  function getData(element, name) {
    if (isObject(element[name])) {
      return element[name];
    }
    if (element.dataset) {
      return element.dataset[name];
    }
    return element.getAttribute("data-".concat(toParamCase(name)));
  }

  /**
   * Set data to the given element.
   * @param {Element} element - The target element.
   * @param {string} name - The data key to set.
   * @param {string} data - The data value.
   */
  function setData(element, name, data) {
    if (isObject(data)) {
      element[name] = data;
    } else if (element.dataset) {
      element.dataset[name] = data;
    } else {
      element.setAttribute("data-".concat(toParamCase(name)), data);
    }
  }

  /**
   * Remove data from the given element.
   * @param {Element} element - The target element.
   * @param {string} name - The data key to remove.
   */
  function removeData(element, name) {
    if (isObject(element[name])) {
      try {
        delete element[name];
      } catch (error) {
        element[name] = undefined;
      }
    } else if (element.dataset) {
      // #128 Safari not allows to delete dataset property
      try {
        delete element.dataset[name];
      } catch (error) {
        element.dataset[name] = undefined;
      }
    } else {
      element.removeAttribute("data-".concat(toParamCase(name)));
    }
  }
  var REGEXP_SPACES = /\s\s*/;
  var onceSupported = function () {
    var supported = false;
    if (IS_BROWSER) {
      var once = false;
      var listener = function listener() {};
      var options = Object.defineProperty({}, 'once', {
        get: function get() {
          supported = true;
          return once;
        },
        /**
         * This setter can fix a `TypeError` in strict mode
         * {@link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Errors/Getter_only}
         * @param {boolean} value - The value to set
         */
        set: function set(value) {
          once = value;
        }
      });
      WINDOW.addEventListener('test', listener, options);
      WINDOW.removeEventListener('test', listener, options);
    }
    return supported;
  }();

  /**
   * Remove event listener from the target element.
   * @param {Element} element - The event target.
   * @param {string} type - The event type(s).
   * @param {Function} listener - The event listener.
   * @param {Object} options - The event options.
   */
  function removeListener(element, type, listener) {
    var options = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
    var handler = listener;
    type.trim().split(REGEXP_SPACES).forEach(function (event) {
      if (!onceSupported) {
        var listeners = element.listeners;
        if (listeners && listeners[event] && listeners[event][listener]) {
          handler = listeners[event][listener];
          delete listeners[event][listener];
          if (Object.keys(listeners[event]).length === 0) {
            delete listeners[event];
          }
          if (Object.keys(listeners).length === 0) {
            delete element.listeners;
          }
        }
      }
      element.removeEventListener(event, handler, options);
    });
  }

  /**
   * Add event listener to the target element.
   * @param {Element} element - The event target.
   * @param {string} type - The event type(s).
   * @param {Function} listener - The event listener.
   * @param {Object} options - The event options.
   */
  function addListener(element, type, listener) {
    var options = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
    var _handler = listener;
    type.trim().split(REGEXP_SPACES).forEach(function (event) {
      if (options.once && !onceSupported) {
        var _element$listeners = element.listeners,
          listeners = _element$listeners === void 0 ? {} : _element$listeners;
        _handler = function handler() {
          delete listeners[event][listener];
          element.removeEventListener(event, _handler, options);
          for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
            args[_key2] = arguments[_key2];
          }
          listener.apply(element, args);
        };
        if (!listeners[event]) {
          listeners[event] = {};
        }
        if (listeners[event][listener]) {
          element.removeEventListener(event, listeners[event][listener], options);
        }
        listeners[event][listener] = _handler;
        element.listeners = listeners;
      }
      element.addEventListener(event, _handler, options);
    });
  }

  /**
   * Dispatch event on the target element.
   * @param {Element} element - The event target.
   * @param {string} type - The event type(s).
   * @param {Object} data - The additional event data.
   * @returns {boolean} Indicate if the event is default prevented or not.
   */
  function dispatchEvent(element, type, data) {
    var event;

    // Event and CustomEvent on IE9-11 are global objects, not constructors
    if (isFunction(Event) && isFunction(CustomEvent)) {
      event = new CustomEvent(type, {
        detail: data,
        bubbles: true,
        cancelable: true
      });
    } else {
      event = document.createEvent('CustomEvent');
      event.initCustomEvent(type, true, true, data);
    }
    return element.dispatchEvent(event);
  }

  /**
   * Get the offset base on the document.
   * @param {Element} element - The target element.
   * @returns {Object} The offset data.
   */
  function getOffset(element) {
    var box = element.getBoundingClientRect();
    return {
      left: box.left + (window.pageXOffset - document.documentElement.clientLeft),
      top: box.top + (window.pageYOffset - document.documentElement.clientTop)
    };
  }
  var location = WINDOW.location;
  var REGEXP_ORIGINS = /^(\w+:)\/\/([^:/?#]*):?(\d*)/i;

  /**
   * Check if the given URL is a cross origin URL.
   * @param {string} url - The target URL.
   * @returns {boolean} Returns `true` if the given URL is a cross origin URL, else `false`.
   */
  function isCrossOriginURL(url) {
    var parts = url.match(REGEXP_ORIGINS);
    return parts !== null && (parts[1] !== location.protocol || parts[2] !== location.hostname || parts[3] !== location.port);
  }

  /**
   * Add timestamp to the given URL.
   * @param {string} url - The target URL.
   * @returns {string} The result URL.
   */
  function addTimestamp(url) {
    var timestamp = "timestamp=".concat(new Date().getTime());
    return url + (url.indexOf('?') === -1 ? '?' : '&') + timestamp;
  }

  /**
   * Get transforms base on the given object.
   * @param {Object} obj - The target object.
   * @returns {string} A string contains transform values.
   */
  function getTransforms(_ref) {
    var rotate = _ref.rotate,
      scaleX = _ref.scaleX,
      scaleY = _ref.scaleY,
      translateX = _ref.translateX,
      translateY = _ref.translateY;
    var values = [];
    if (isNumber(translateX) && translateX !== 0) {
      values.push("translateX(".concat(translateX, "px)"));
    }
    if (isNumber(translateY) && translateY !== 0) {
      values.push("translateY(".concat(translateY, "px)"));
    }

    // Rotate should come first before scale to match orientation transform
    if (isNumber(rotate) && rotate !== 0) {
      values.push("rotate(".concat(rotate, "deg)"));
    }
    if (isNumber(scaleX) && scaleX !== 1) {
      values.push("scaleX(".concat(scaleX, ")"));
    }
    if (isNumber(scaleY) && scaleY !== 1) {
      values.push("scaleY(".concat(scaleY, ")"));
    }
    var transform = values.length ? values.join(' ') : 'none';
    return {
      WebkitTransform: transform,
      msTransform: transform,
      transform: transform
    };
  }

  /**
   * Get the max ratio of a group of pointers.
   * @param {string} pointers - The target pointers.
   * @returns {number} The result ratio.
   */
  function getMaxZoomRatio(pointers) {
    var pointers2 = _objectSpread2({}, pointers);
    var maxRatio = 0;
    forEach(pointers, function (pointer, pointerId) {
      delete pointers2[pointerId];
      forEach(pointers2, function (pointer2) {
        var x1 = Math.abs(pointer.startX - pointer2.startX);
        var y1 = Math.abs(pointer.startY - pointer2.startY);
        var x2 = Math.abs(pointer.endX - pointer2.endX);
        var y2 = Math.abs(pointer.endY - pointer2.endY);
        var z1 = Math.sqrt(x1 * x1 + y1 * y1);
        var z2 = Math.sqrt(x2 * x2 + y2 * y2);
        var ratio = (z2 - z1) / z1;
        if (Math.abs(ratio) > Math.abs(maxRatio)) {
          maxRatio = ratio;
        }
      });
    });
    return maxRatio;
  }

  /**
   * Get a pointer from an event object.
   * @param {Object} event - The target event object.
   * @param {boolean} endOnly - Indicates if only returns the end point coordinate or not.
   * @returns {Object} The result pointer contains start and/or end point coordinates.
   */
  function getPointer(_ref2, endOnly) {
    var pageX = _ref2.pageX,
      pageY = _ref2.pageY;
    var end = {
      endX: pageX,
      endY: pageY
    };
    return endOnly ? end : _objectSpread2({
      startX: pageX,
      startY: pageY
    }, end);
  }

  /**
   * Get the center point coordinate of a group of pointers.
   * @param {Object} pointers - The target pointers.
   * @returns {Object} The center point coordinate.
   */
  function getPointersCenter(pointers) {
    var pageX = 0;
    var pageY = 0;
    var count = 0;
    forEach(pointers, function (_ref3) {
      var startX = _ref3.startX,
        startY = _ref3.startY;
      pageX += startX;
      pageY += startY;
      count += 1;
    });
    pageX /= count;
    pageY /= count;
    return {
      pageX: pageX,
      pageY: pageY
    };
  }

  /**
   * Get the max sizes in a rectangle under the given aspect ratio.
   * @param {Object} data - The original sizes.
   * @param {string} [type='contain'] - The adjust type.
   * @returns {Object} The result sizes.
   */
  function getAdjustedSizes(_ref4) {
    var aspectRatio = _ref4.aspectRatio,
      height = _ref4.height,
      width = _ref4.width;
    var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'contain';
    var isValidWidth = isPositiveNumber(width);
    var isValidHeight = isPositiveNumber(height);
    if (isValidWidth && isValidHeight) {
      var adjustedWidth = height * aspectRatio;
      if (type === 'contain' && adjustedWidth > width || type === 'cover' && adjustedWidth < width) {
        height = width / aspectRatio;
      } else {
        width = height * aspectRatio;
      }
    } else if (isValidWidth) {
      height = width / aspectRatio;
    } else if (isValidHeight) {
      width = height * aspectRatio;
    }
    return {
      width: width,
      height: height
    };
  }

  /**
   * Get the new sizes of a rectangle after rotated.
   * @param {Object} data - The original sizes.
   * @returns {Object} The result sizes.
   */
  function getRotatedSizes(_ref5) {
    var width = _ref5.width,
      height = _ref5.height,
      degree = _ref5.degree;
    degree = Math.abs(degree) % 180;
    if (degree === 90) {
      return {
        width: height,
        height: width
      };
    }
    var arc = degree % 90 * Math.PI / 180;
    var sinArc = Math.sin(arc);
    var cosArc = Math.cos(arc);
    var newWidth = width * cosArc + height * sinArc;
    var newHeight = width * sinArc + height * cosArc;
    return degree > 90 ? {
      width: newHeight,
      height: newWidth
    } : {
      width: newWidth,
      height: newHeight
    };
  }

  /**
   * Get a canvas which drew the given image.
   * @param {HTMLImageElement} image - The image for drawing.
   * @param {Object} imageData - The image data.
   * @param {Object} canvasData - The canvas data.
   * @param {Object} options - The options.
   * @returns {HTMLCanvasElement} The result canvas.
   */
  function getSourceCanvas(image, _ref6, _ref7, _ref8) {
    var imageAspectRatio = _ref6.aspectRatio,
      imageNaturalWidth = _ref6.naturalWidth,
      imageNaturalHeight = _ref6.naturalHeight,
      _ref6$rotate = _ref6.rotate,
      rotate = _ref6$rotate === void 0 ? 0 : _ref6$rotate,
      _ref6$scaleX = _ref6.scaleX,
      scaleX = _ref6$scaleX === void 0 ? 1 : _ref6$scaleX,
      _ref6$scaleY = _ref6.scaleY,
      scaleY = _ref6$scaleY === void 0 ? 1 : _ref6$scaleY;
    var aspectRatio = _ref7.aspectRatio,
      naturalWidth = _ref7.naturalWidth,
      naturalHeight = _ref7.naturalHeight;
    var _ref8$fillColor = _ref8.fillColor,
      fillColor = _ref8$fillColor === void 0 ? 'transparent' : _ref8$fillColor,
      _ref8$imageSmoothingE = _ref8.imageSmoothingEnabled,
      imageSmoothingEnabled = _ref8$imageSmoothingE === void 0 ? true : _ref8$imageSmoothingE,
      _ref8$imageSmoothingQ = _ref8.imageSmoothingQuality,
      imageSmoothingQuality = _ref8$imageSmoothingQ === void 0 ? 'low' : _ref8$imageSmoothingQ,
      _ref8$maxWidth = _ref8.maxWidth,
      maxWidth = _ref8$maxWidth === void 0 ? Infinity : _ref8$maxWidth,
      _ref8$maxHeight = _ref8.maxHeight,
      maxHeight = _ref8$maxHeight === void 0 ? Infinity : _ref8$maxHeight,
      _ref8$minWidth = _ref8.minWidth,
      minWidth = _ref8$minWidth === void 0 ? 0 : _ref8$minWidth,
      _ref8$minHeight = _ref8.minHeight,
      minHeight = _ref8$minHeight === void 0 ? 0 : _ref8$minHeight;
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    var maxSizes = getAdjustedSizes({
      aspectRatio: aspectRatio,
      width: maxWidth,
      height: maxHeight
    });
    var minSizes = getAdjustedSizes({
      aspectRatio: aspectRatio,
      width: minWidth,
      height: minHeight
    }, 'cover');
    var width = Math.min(maxSizes.width, Math.max(minSizes.width, naturalWidth));
    var height = Math.min(maxSizes.height, Math.max(minSizes.height, naturalHeight));

    // Note: should always use image's natural sizes for drawing as
    // imageData.naturalWidth === canvasData.naturalHeight when rotate % 180 === 90
    var destMaxSizes = getAdjustedSizes({
      aspectRatio: imageAspectRatio,
      width: maxWidth,
      height: maxHeight
    });
    var destMinSizes = getAdjustedSizes({
      aspectRatio: imageAspectRatio,
      width: minWidth,
      height: minHeight
    }, 'cover');
    var destWidth = Math.min(destMaxSizes.width, Math.max(destMinSizes.width, imageNaturalWidth));
    var destHeight = Math.min(destMaxSizes.height, Math.max(destMinSizes.height, imageNaturalHeight));
    var params = [-destWidth / 2, -destHeight / 2, destWidth, destHeight];
    canvas.width = normalizeDecimalNumber(width);
    canvas.height = normalizeDecimalNumber(height);
    context.fillStyle = fillColor;
    context.fillRect(0, 0, width, height);
    context.save();
    context.translate(width / 2, height / 2);
    context.rotate(rotate * Math.PI / 180);
    context.scale(scaleX, scaleY);
    context.imageSmoothingEnabled = imageSmoothingEnabled;
    context.imageSmoothingQuality = imageSmoothingQuality;
    context.drawImage.apply(context, [image].concat(_toConsumableArray(params.map(function (param) {
      return Math.floor(normalizeDecimalNumber(param));
    }))));
    context.restore();
    return canvas;
  }
  var fromCharCode = String.fromCharCode;

  /**
   * Get string from char code in data view.
   * @param {DataView} dataView - The data view for read.
   * @param {number} start - The start index.
   * @param {number} length - The read length.
   * @returns {string} The read result.
   */
  function getStringFromCharCode(dataView, start, length) {
    var str = '';
    length += start;
    for (var i = start; i < length; i += 1) {
      str += fromCharCode(dataView.getUint8(i));
    }
    return str;
  }
  var REGEXP_DATA_URL_HEAD = /^data:.*,/;

  /**
   * Transform Data URL to array buffer.
   * @param {string} dataURL - The Data URL to transform.
   * @returns {ArrayBuffer} The result array buffer.
   */
  function dataURLToArrayBuffer(dataURL) {
    var base64 = dataURL.replace(REGEXP_DATA_URL_HEAD, '');
    var binary = atob(base64);
    var arrayBuffer = new ArrayBuffer(binary.length);
    var uint8 = new Uint8Array(arrayBuffer);
    forEach(uint8, function (value, i) {
      uint8[i] = binary.charCodeAt(i);
    });
    return arrayBuffer;
  }

  /**
   * Transform array buffer to Data URL.
   * @param {ArrayBuffer} arrayBuffer - The array buffer to transform.
   * @param {string} mimeType - The mime type of the Data URL.
   * @returns {string} The result Data URL.
   */
  function arrayBufferToDataURL(arrayBuffer, mimeType) {
    var chunks = [];

    // Chunk Typed Array for better performance (#435)
    var chunkSize = 8192;
    var uint8 = new Uint8Array(arrayBuffer);
    while (uint8.length > 0) {
      // XXX: Babel's `toConsumableArray` helper will throw error in IE or Safari 9
      // eslint-disable-next-line prefer-spread
      chunks.push(fromCharCode.apply(null, toArray(uint8.subarray(0, chunkSize))));
      uint8 = uint8.subarray(chunkSize);
    }
    return "data:".concat(mimeType, ";base64,").concat(btoa(chunks.join('')));
  }

  /**
   * Get orientation value from given array buffer.
   * @param {ArrayBuffer} arrayBuffer - The array buffer to read.
   * @returns {number} The read orientation value.
   */
  function resetAndGetOrientation(arrayBuffer) {
    var dataView = new DataView(arrayBuffer);
    var orientation;

    // Ignores range error when the image does not have correct Exif information
    try {
      var littleEndian;
      var app1Start;
      var ifdStart;

      // Only handle JPEG image (start by 0xFFD8)
      if (dataView.getUint8(0) === 0xFF && dataView.getUint8(1) === 0xD8) {
        var length = dataView.byteLength;
        var offset = 2;
        while (offset + 1 < length) {
          if (dataView.getUint8(offset) === 0xFF && dataView.getUint8(offset + 1) === 0xE1) {
            app1Start = offset;
            break;
          }
          offset += 1;
        }
      }
      if (app1Start) {
        var exifIDCode = app1Start + 4;
        var tiffOffset = app1Start + 10;
        if (getStringFromCharCode(dataView, exifIDCode, 4) === 'Exif') {
          var endianness = dataView.getUint16(tiffOffset);
          littleEndian = endianness === 0x4949;
          if (littleEndian || endianness === 0x4D4D /* bigEndian */) {
            if (dataView.getUint16(tiffOffset + 2, littleEndian) === 0x002A) {
              var firstIFDOffset = dataView.getUint32(tiffOffset + 4, littleEndian);
              if (firstIFDOffset >= 0x00000008) {
                ifdStart = tiffOffset + firstIFDOffset;
              }
            }
          }
        }
      }
      if (ifdStart) {
        var _length = dataView.getUint16(ifdStart, littleEndian);
        var _offset;
        var i;
        for (i = 0; i < _length; i += 1) {
          _offset = ifdStart + i * 12 + 2;
          if (dataView.getUint16(_offset, littleEndian) === 0x0112 /* Orientation */) {
            // 8 is the offset of the current tag's value
            _offset += 8;

            // Get the original orientation value
            orientation = dataView.getUint16(_offset, littleEndian);

            // Override the orientation with its default value
            dataView.setUint16(_offset, 1, littleEndian);
            break;
          }
        }
      }
    } catch (error) {
      orientation = 1;
    }
    return orientation;
  }

  /**
   * Parse Exif Orientation value.
   * @param {number} orientation - The orientation to parse.
   * @returns {Object} The parsed result.
   */
  function parseOrientation(orientation) {
    var rotate = 0;
    var scaleX = 1;
    var scaleY = 1;
    switch (orientation) {
      // Flip horizontal
      case 2:
        scaleX = -1;
        break;

      // Rotate left 180°
      case 3:
        rotate = -180;
        break;

      // Flip vertical
      case 4:
        scaleY = -1;
        break;

      // Flip vertical and rotate right 90°
      case 5:
        rotate = 90;
        scaleY = -1;
        break;

      // Rotate right 90°
      case 6:
        rotate = 90;
        break;

      // Flip horizontal and rotate right 90°
      case 7:
        rotate = 90;
        scaleX = -1;
        break;

      // Rotate left 90°
      case 8:
        rotate = -90;
        break;
    }
    return {
      rotate: rotate,
      scaleX: scaleX,
      scaleY: scaleY
    };
  }

  var render = {
    render: function render() {
      this.initContainer();
      this.initCanvas();
      this.initCropBox();
      this.renderCanvas();
      if (this.cropped) {
        this.renderCropBox();
      }
    },
    initContainer: function initContainer() {
      var element = this.element,
        options = this.options,
        container = this.container,
        cropper = this.cropper;
      var minWidth = Number(options.minContainerWidth);
      var minHeight = Number(options.minContainerHeight);
      addClass(cropper, CLASS_HIDDEN);
      removeClass(element, CLASS_HIDDEN);
      var containerData = {
        width: Math.max(container.offsetWidth, minWidth >= 0 ? minWidth : MIN_CONTAINER_WIDTH),
        height: Math.max(container.offsetHeight, minHeight >= 0 ? minHeight : MIN_CONTAINER_HEIGHT)
      };
      this.containerData = containerData;
      setStyle(cropper, {
        width: containerData.width,
        height: containerData.height
      });
      addClass(element, CLASS_HIDDEN);
      removeClass(cropper, CLASS_HIDDEN);
    },
    // Canvas (image wrapper)
    initCanvas: function initCanvas() {
      var containerData = this.containerData,
        imageData = this.imageData;
      var viewMode = this.options.viewMode;
      var rotated = Math.abs(imageData.rotate) % 180 === 90;
      var naturalWidth = rotated ? imageData.naturalHeight : imageData.naturalWidth;
      var naturalHeight = rotated ? imageData.naturalWidth : imageData.naturalHeight;
      var aspectRatio = naturalWidth / naturalHeight;
      var canvasWidth = containerData.width;
      var canvasHeight = containerData.height;
      if (containerData.height * aspectRatio > containerData.width) {
        if (viewMode === 3) {
          canvasWidth = containerData.height * aspectRatio;
        } else {
          canvasHeight = containerData.width / aspectRatio;
        }
      } else if (viewMode === 3) {
        canvasHeight = containerData.width / aspectRatio;
      } else {
        canvasWidth = containerData.height * aspectRatio;
      }
      var canvasData = {
        aspectRatio: aspectRatio,
        naturalWidth: naturalWidth,
        naturalHeight: naturalHeight,
        width: canvasWidth,
        height: canvasHeight
      };
      this.canvasData = canvasData;
      this.limited = viewMode === 1 || viewMode === 2;
      this.limitCanvas(true, true);
      canvasData.width = Math.min(Math.max(canvasData.width, canvasData.minWidth), canvasData.maxWidth);
      canvasData.height = Math.min(Math.max(canvasData.height, canvasData.minHeight), canvasData.maxHeight);
      canvasData.left = (containerData.width - canvasData.width) / 2;
      canvasData.top = (containerData.height - canvasData.height) / 2;
      canvasData.oldLeft = canvasData.left;
      canvasData.oldTop = canvasData.top;
      this.initialCanvasData = assign({}, canvasData);
    },
    limitCanvas: function limitCanvas(sizeLimited, positionLimited) {
      var options = this.options,
        containerData = this.containerData,
        canvasData = this.canvasData,
        cropBoxData = this.cropBoxData;
      var viewMode = options.viewMode;
      var aspectRatio = canvasData.aspectRatio;
      var cropped = this.cropped && cropBoxData;
      if (sizeLimited) {
        var minCanvasWidth = Number(options.minCanvasWidth) || 0;
        var minCanvasHeight = Number(options.minCanvasHeight) || 0;
        if (viewMode > 1) {
          minCanvasWidth = Math.max(minCanvasWidth, containerData.width);
          minCanvasHeight = Math.max(minCanvasHeight, containerData.height);
          if (viewMode === 3) {
            if (minCanvasHeight * aspectRatio > minCanvasWidth) {
              minCanvasWidth = minCanvasHeight * aspectRatio;
            } else {
              minCanvasHeight = minCanvasWidth / aspectRatio;
            }
          }
        } else if (viewMode > 0) {
          if (minCanvasWidth) {
            minCanvasWidth = Math.max(minCanvasWidth, cropped ? cropBoxData.width : 0);
          } else if (minCanvasHeight) {
            minCanvasHeight = Math.max(minCanvasHeight, cropped ? cropBoxData.height : 0);
          } else if (cropped) {
            minCanvasWidth = cropBoxData.width;
            minCanvasHeight = cropBoxData.height;
            if (minCanvasHeight * aspectRatio > minCanvasWidth) {
              minCanvasWidth = minCanvasHeight * aspectRatio;
            } else {
              minCanvasHeight = minCanvasWidth / aspectRatio;
            }
          }
        }
        var _getAdjustedSizes = getAdjustedSizes({
          aspectRatio: aspectRatio,
          width: minCanvasWidth,
          height: minCanvasHeight
        });
        minCanvasWidth = _getAdjustedSizes.width;
        minCanvasHeight = _getAdjustedSizes.height;
        canvasData.minWidth = minCanvasWidth;
        canvasData.minHeight = minCanvasHeight;
        canvasData.maxWidth = Infinity;
        canvasData.maxHeight = Infinity;
      }
      if (positionLimited) {
        if (viewMode > (cropped ? 0 : 1)) {
          var newCanvasLeft = containerData.width - canvasData.width;
          var newCanvasTop = containerData.height - canvasData.height;
          canvasData.minLeft = Math.min(0, newCanvasLeft);
          canvasData.minTop = Math.min(0, newCanvasTop);
          canvasData.maxLeft = Math.max(0, newCanvasLeft);
          canvasData.maxTop = Math.max(0, newCanvasTop);
          if (cropped && this.limited) {
            canvasData.minLeft = Math.min(cropBoxData.left, cropBoxData.left + (cropBoxData.width - canvasData.width));
            canvasData.minTop = Math.min(cropBoxData.top, cropBoxData.top + (cropBoxData.height - canvasData.height));
            canvasData.maxLeft = cropBoxData.left;
            canvasData.maxTop = cropBoxData.top;
            if (viewMode === 2) {
              if (canvasData.width >= containerData.width) {
                canvasData.minLeft = Math.min(0, newCanvasLeft);
                canvasData.maxLeft = Math.max(0, newCanvasLeft);
              }
              if (canvasData.height >= containerData.height) {
                canvasData.minTop = Math.min(0, newCanvasTop);
                canvasData.maxTop = Math.max(0, newCanvasTop);
              }
            }
          }
        } else {
          canvasData.minLeft = -canvasData.width;
          canvasData.minTop = -canvasData.height;
          canvasData.maxLeft = containerData.width;
          canvasData.maxTop = containerData.height;
        }
      }
    },
    renderCanvas: function renderCanvas(changed, transformed) {
      var canvasData = this.canvasData,
        imageData = this.imageData;
      if (transformed) {
        var _getRotatedSizes = getRotatedSizes({
            width: imageData.naturalWidth * Math.abs(imageData.scaleX || 1),
            height: imageData.naturalHeight * Math.abs(imageData.scaleY || 1),
            degree: imageData.rotate || 0
          }),
          naturalWidth = _getRotatedSizes.width,
          naturalHeight = _getRotatedSizes.height;
        var width = canvasData.width * (naturalWidth / canvasData.naturalWidth);
        var height = canvasData.height * (naturalHeight / canvasData.naturalHeight);
        canvasData.left -= (width - canvasData.width) / 2;
        canvasData.top -= (height - canvasData.height) / 2;
        canvasData.width = width;
        canvasData.height = height;
        canvasData.aspectRatio = naturalWidth / naturalHeight;
        canvasData.naturalWidth = naturalWidth;
        canvasData.naturalHeight = naturalHeight;
        this.limitCanvas(true, false);
      }
      if (canvasData.width > canvasData.maxWidth || canvasData.width < canvasData.minWidth) {
        canvasData.left = canvasData.oldLeft;
      }
      if (canvasData.height > canvasData.maxHeight || canvasData.height < canvasData.minHeight) {
        canvasData.top = canvasData.oldTop;
      }
      canvasData.width = Math.min(Math.max(canvasData.width, canvasData.minWidth), canvasData.maxWidth);
      canvasData.height = Math.min(Math.max(canvasData.height, canvasData.minHeight), canvasData.maxHeight);
      this.limitCanvas(false, true);
      canvasData.left = Math.min(Math.max(canvasData.left, canvasData.minLeft), canvasData.maxLeft);
      canvasData.top = Math.min(Math.max(canvasData.top, canvasData.minTop), canvasData.maxTop);
      canvasData.oldLeft = canvasData.left;
      canvasData.oldTop = canvasData.top;
      setStyle(this.canvas, assign({
        width: canvasData.width,
        height: canvasData.height
      }, getTransforms({
        translateX: canvasData.left,
        translateY: canvasData.top
      })));
      this.renderImage(changed);
      if (this.cropped && this.limited) {
        this.limitCropBox(true, true);
      }
    },
    renderImage: function renderImage(changed) {
      var canvasData = this.canvasData,
        imageData = this.imageData;
      var width = imageData.naturalWidth * (canvasData.width / canvasData.naturalWidth);
      var height = imageData.naturalHeight * (canvasData.height / canvasData.naturalHeight);
      assign(imageData, {
        width: width,
        height: height,
        left: (canvasData.width - width) / 2,
        top: (canvasData.height - height) / 2
      });
      setStyle(this.image, assign({
        width: imageData.width,
        height: imageData.height
      }, getTransforms(assign({
        translateX: imageData.left,
        translateY: imageData.top
      }, imageData))));
      if (changed) {
        this.output();
      }
    },
    initCropBox: function initCropBox() {
      var options = this.options,
        canvasData = this.canvasData;
      var aspectRatio = options.aspectRatio || options.initialAspectRatio;
      var autoCropArea = Number(options.autoCropArea) || 0.8;
      var cropBoxData = {
        width: canvasData.width,
        height: canvasData.height
      };
      if (aspectRatio) {
        if (canvasData.height * aspectRatio > canvasData.width) {
          cropBoxData.height = cropBoxData.width / aspectRatio;
        } else {
          cropBoxData.width = cropBoxData.height * aspectRatio;
        }
      }
      this.cropBoxData = cropBoxData;
      this.limitCropBox(true, true);

      // Initialize auto crop area
      cropBoxData.width = Math.min(Math.max(cropBoxData.width, cropBoxData.minWidth), cropBoxData.maxWidth);
      cropBoxData.height = Math.min(Math.max(cropBoxData.height, cropBoxData.minHeight), cropBoxData.maxHeight);

      // The width/height of auto crop area must large than "minWidth/Height"
      cropBoxData.width = Math.max(cropBoxData.minWidth, cropBoxData.width * autoCropArea);
      cropBoxData.height = Math.max(cropBoxData.minHeight, cropBoxData.height * autoCropArea);
      cropBoxData.left = canvasData.left + (canvasData.width - cropBoxData.width) / 2;
      cropBoxData.top = canvasData.top + (canvasData.height - cropBoxData.height) / 2;
      cropBoxData.oldLeft = cropBoxData.left;
      cropBoxData.oldTop = cropBoxData.top;
      this.initialCropBoxData = assign({}, cropBoxData);
    },
    limitCropBox: function limitCropBox(sizeLimited, positionLimited) {
      var options = this.options,
        containerData = this.containerData,
        canvasData = this.canvasData,
        cropBoxData = this.cropBoxData,
        limited = this.limited;
      var aspectRatio = options.aspectRatio;
      if (sizeLimited) {
        var minCropBoxWidth = Number(options.minCropBoxWidth) || 0;
        var minCropBoxHeight = Number(options.minCropBoxHeight) || 0;
        var maxCropBoxWidth = limited ? Math.min(containerData.width, canvasData.width, canvasData.width + canvasData.left, containerData.width - canvasData.left) : containerData.width;
        var maxCropBoxHeight = limited ? Math.min(containerData.height, canvasData.height, canvasData.height + canvasData.top, containerData.height - canvasData.top) : containerData.height;

        // The min/maxCropBoxWidth/Height must be less than container's width/height
        minCropBoxWidth = Math.min(minCropBoxWidth, containerData.width);
        minCropBoxHeight = Math.min(minCropBoxHeight, containerData.height);
        if (aspectRatio) {
          if (minCropBoxWidth && minCropBoxHeight) {
            if (minCropBoxHeight * aspectRatio > minCropBoxWidth) {
              minCropBoxHeight = minCropBoxWidth / aspectRatio;
            } else {
              minCropBoxWidth = minCropBoxHeight * aspectRatio;
            }
          } else if (minCropBoxWidth) {
            minCropBoxHeight = minCropBoxWidth / aspectRatio;
          } else if (minCropBoxHeight) {
            minCropBoxWidth = minCropBoxHeight * aspectRatio;
          }
          if (maxCropBoxHeight * aspectRatio > maxCropBoxWidth) {
            maxCropBoxHeight = maxCropBoxWidth / aspectRatio;
          } else {
            maxCropBoxWidth = maxCropBoxHeight * aspectRatio;
          }
        }

        // The minWidth/Height must be less than maxWidth/Height
        cropBoxData.minWidth = Math.min(minCropBoxWidth, maxCropBoxWidth);
        cropBoxData.minHeight = Math.min(minCropBoxHeight, maxCropBoxHeight);
        cropBoxData.maxWidth = maxCropBoxWidth;
        cropBoxData.maxHeight = maxCropBoxHeight;
      }
      if (positionLimited) {
        if (limited) {
          cropBoxData.minLeft = Math.max(0, canvasData.left);
          cropBoxData.minTop = Math.max(0, canvasData.top);
          cropBoxData.maxLeft = Math.min(containerData.width, canvasData.left + canvasData.width) - cropBoxData.width;
          cropBoxData.maxTop = Math.min(containerData.height, canvasData.top + canvasData.height) - cropBoxData.height;
        } else {
          cropBoxData.minLeft = 0;
          cropBoxData.minTop = 0;
          cropBoxData.maxLeft = containerData.width - cropBoxData.width;
          cropBoxData.maxTop = containerData.height - cropBoxData.height;
        }
      }
    },
    renderCropBox: function renderCropBox() {
      var options = this.options,
        containerData = this.containerData,
        cropBoxData = this.cropBoxData;
      if (cropBoxData.width > cropBoxData.maxWidth || cropBoxData.width < cropBoxData.minWidth) {
        cropBoxData.left = cropBoxData.oldLeft;
      }
      if (cropBoxData.height > cropBoxData.maxHeight || cropBoxData.height < cropBoxData.minHeight) {
        cropBoxData.top = cropBoxData.oldTop;
      }
      cropBoxData.width = Math.min(Math.max(cropBoxData.width, cropBoxData.minWidth), cropBoxData.maxWidth);
      cropBoxData.height = Math.min(Math.max(cropBoxData.height, cropBoxData.minHeight), cropBoxData.maxHeight);
      this.limitCropBox(false, true);
      cropBoxData.left = Math.min(Math.max(cropBoxData.left, cropBoxData.minLeft), cropBoxData.maxLeft);
      cropBoxData.top = Math.min(Math.max(cropBoxData.top, cropBoxData.minTop), cropBoxData.maxTop);
      cropBoxData.oldLeft = cropBoxData.left;
      cropBoxData.oldTop = cropBoxData.top;
      if (options.movable && options.cropBoxMovable) {
        // Turn to move the canvas when the crop box is equal to the container
        setData(this.face, DATA_ACTION, cropBoxData.width >= containerData.width && cropBoxData.height >= containerData.height ? ACTION_MOVE : ACTION_ALL);
      }
      setStyle(this.cropBox, assign({
        width: cropBoxData.width,
        height: cropBoxData.height
      }, getTransforms({
        translateX: cropBoxData.left,
        translateY: cropBoxData.top
      })));
      if (this.cropped && this.limited) {
        this.limitCanvas(true, true);
      }
      if (!this.disabled) {
        this.output();
      }
    },
    output: function output() {
      this.preview();
      dispatchEvent(this.element, EVENT_CROP, this.getData());
    }
  };

  var preview = {
    initPreview: function initPreview() {
      var element = this.element,
        crossOrigin = this.crossOrigin;
      var preview = this.options.preview;
      var url = crossOrigin ? this.crossOriginUrl : this.url;
      var alt = element.alt || 'The image to preview';
      var image = document.createElement('img');
      if (crossOrigin) {
        image.crossOrigin = crossOrigin;
      }
      image.src = url;
      image.alt = alt;
      this.viewBox.appendChild(image);
      this.viewBoxImage = image;
      if (!preview) {
        return;
      }
      var previews = preview;
      if (typeof preview === 'string') {
        previews = element.ownerDocument.querySelectorAll(preview);
      } else if (preview.querySelector) {
        previews = [preview];
      }
      this.previews = previews;
      forEach(previews, function (el) {
        var img = document.createElement('img');

        // Save the original size for recover
        setData(el, DATA_PREVIEW, {
          width: el.offsetWidth,
          height: el.offsetHeight,
          html: el.innerHTML
        });
        if (crossOrigin) {
          img.crossOrigin = crossOrigin;
        }
        img.src = url;
        img.alt = alt;

        /**
         * Override img element styles
         * Add `display:block` to avoid margin top issue
         * Add `height:auto` to override `height` attribute on IE8
         * (Occur only when margin-top <= -height)
         */
        img.style.cssText = 'display:block;' + 'width:100%;' + 'height:auto;' + 'min-width:0!important;' + 'min-height:0!important;' + 'max-width:none!important;' + 'max-height:none!important;' + 'image-orientation:0deg!important;"';
        el.innerHTML = '';
        el.appendChild(img);
      });
    },
    resetPreview: function resetPreview() {
      forEach(this.previews, function (element) {
        var data = getData(element, DATA_PREVIEW);
        setStyle(element, {
          width: data.width,
          height: data.height
        });
        element.innerHTML = data.html;
        removeData(element, DATA_PREVIEW);
      });
    },
    preview: function preview() {
      var imageData = this.imageData,
        canvasData = this.canvasData,
        cropBoxData = this.cropBoxData;
      var cropBoxWidth = cropBoxData.width,
        cropBoxHeight = cropBoxData.height;
      var width = imageData.width,
        height = imageData.height;
      var left = cropBoxData.left - canvasData.left - imageData.left;
      var top = cropBoxData.top - canvasData.top - imageData.top;
      if (!this.cropped || this.disabled) {
        return;
      }
      setStyle(this.viewBoxImage, assign({
        width: width,
        height: height
      }, getTransforms(assign({
        translateX: -left,
        translateY: -top
      }, imageData))));
      forEach(this.previews, function (element) {
        var data = getData(element, DATA_PREVIEW);
        var originalWidth = data.width;
        var originalHeight = data.height;
        var newWidth = originalWidth;
        var newHeight = originalHeight;
        var ratio = 1;
        if (cropBoxWidth) {
          ratio = originalWidth / cropBoxWidth;
          newHeight = cropBoxHeight * ratio;
        }
        if (cropBoxHeight && newHeight > originalHeight) {
          ratio = originalHeight / cropBoxHeight;
          newWidth = cropBoxWidth * ratio;
          newHeight = originalHeight;
        }
        setStyle(element, {
          width: newWidth,
          height: newHeight
        });
        setStyle(element.getElementsByTagName('img')[0], assign({
          width: width * ratio,
          height: height * ratio
        }, getTransforms(assign({
          translateX: -left * ratio,
          translateY: -top * ratio
        }, imageData))));
      });
    }
  };

  var events = {
    bind: function bind() {
      var element = this.element,
        options = this.options,
        cropper = this.cropper;
      if (isFunction(options.cropstart)) {
        addListener(element, EVENT_CROP_START, options.cropstart);
      }
      if (isFunction(options.cropmove)) {
        addListener(element, EVENT_CROP_MOVE, options.cropmove);
      }
      if (isFunction(options.cropend)) {
        addListener(element, EVENT_CROP_END, options.cropend);
      }
      if (isFunction(options.crop)) {
        addListener(element, EVENT_CROP, options.crop);
      }
      if (isFunction(options.zoom)) {
        addListener(element, EVENT_ZOOM, options.zoom);
      }
      addListener(cropper, EVENT_POINTER_DOWN, this.onCropStart = this.cropStart.bind(this));
      if (options.zoomable && options.zoomOnWheel) {
        addListener(cropper, EVENT_WHEEL, this.onWheel = this.wheel.bind(this), {
          passive: false,
          capture: true
        });
      }
      if (options.toggleDragModeOnDblclick) {
        addListener(cropper, EVENT_DBLCLICK, this.onDblclick = this.dblclick.bind(this));
      }
      addListener(element.ownerDocument, EVENT_POINTER_MOVE, this.onCropMove = this.cropMove.bind(this));
      addListener(element.ownerDocument, EVENT_POINTER_UP, this.onCropEnd = this.cropEnd.bind(this));
      if (options.responsive) {
        addListener(window, EVENT_RESIZE, this.onResize = this.resize.bind(this));
      }
    },
    unbind: function unbind() {
      var element = this.element,
        options = this.options,
        cropper = this.cropper;
      if (isFunction(options.cropstart)) {
        removeListener(element, EVENT_CROP_START, options.cropstart);
      }
      if (isFunction(options.cropmove)) {
        removeListener(element, EVENT_CROP_MOVE, options.cropmove);
      }
      if (isFunction(options.cropend)) {
        removeListener(element, EVENT_CROP_END, options.cropend);
      }
      if (isFunction(options.crop)) {
        removeListener(element, EVENT_CROP, options.crop);
      }
      if (isFunction(options.zoom)) {
        removeListener(element, EVENT_ZOOM, options.zoom);
      }
      removeListener(cropper, EVENT_POINTER_DOWN, this.onCropStart);
      if (options.zoomable && options.zoomOnWheel) {
        removeListener(cropper, EVENT_WHEEL, this.onWheel, {
          passive: false,
          capture: true
        });
      }
      if (options.toggleDragModeOnDblclick) {
        removeListener(cropper, EVENT_DBLCLICK, this.onDblclick);
      }
      removeListener(element.ownerDocument, EVENT_POINTER_MOVE, this.onCropMove);
      removeListener(element.ownerDocument, EVENT_POINTER_UP, this.onCropEnd);
      if (options.responsive) {
        removeListener(window, EVENT_RESIZE, this.onResize);
      }
    }
  };

  var handlers = {
    resize: function resize() {
      if (this.disabled) {
        return;
      }
      var options = this.options,
        container = this.container,
        containerData = this.containerData;
      var ratioX = container.offsetWidth / containerData.width;
      var ratioY = container.offsetHeight / containerData.height;
      var ratio = Math.abs(ratioX - 1) > Math.abs(ratioY - 1) ? ratioX : ratioY;

      // Resize when width changed or height changed
      if (ratio !== 1) {
        var canvasData;
        var cropBoxData;
        if (options.restore) {
          canvasData = this.getCanvasData();
          cropBoxData = this.getCropBoxData();
        }
        this.render();
        if (options.restore) {
          this.setCanvasData(forEach(canvasData, function (n, i) {
            canvasData[i] = n * ratio;
          }));
          this.setCropBoxData(forEach(cropBoxData, function (n, i) {
            cropBoxData[i] = n * ratio;
          }));
        }
      }
    },
    dblclick: function dblclick() {
      if (this.disabled || this.options.dragMode === DRAG_MODE_NONE) {
        return;
      }
      this.setDragMode(hasClass(this.dragBox, CLASS_CROP) ? DRAG_MODE_MOVE : DRAG_MODE_CROP);
    },
    wheel: function wheel(event) {
      var _this = this;
      var ratio = Number(this.options.wheelZoomRatio) || 0.1;
      var delta = 1;
      if (this.disabled) {
        return;
      }
      event.preventDefault();

      // Limit wheel speed to prevent zoom too fast (#21)
      if (this.wheeling) {
        return;
      }
      this.wheeling = true;
      setTimeout(function () {
        _this.wheeling = false;
      }, 50);
      if (event.deltaY) {
        delta = event.deltaY > 0 ? 1 : -1;
      } else if (event.wheelDelta) {
        delta = -event.wheelDelta / 120;
      } else if (event.detail) {
        delta = event.detail > 0 ? 1 : -1;
      }
      this.zoom(-delta * ratio, event);
    },
    cropStart: function cropStart(event) {
      var buttons = event.buttons,
        button = event.button;
      if (this.disabled

      // Handle mouse event and pointer event and ignore touch event
      || (event.type === 'mousedown' || event.type === 'pointerdown' && event.pointerType === 'mouse') && (
      // No primary button (Usually the left button)
      isNumber(buttons) && buttons !== 1 || isNumber(button) && button !== 0

      // Open context menu
      || event.ctrlKey)) {
        return;
      }
      var options = this.options,
        pointers = this.pointers;
      var action;
      if (event.changedTouches) {
        // Handle touch event
        forEach(event.changedTouches, function (touch) {
          pointers[touch.identifier] = getPointer(touch);
        });
      } else {
        // Handle mouse event and pointer event
        pointers[event.pointerId || 0] = getPointer(event);
      }
      if (Object.keys(pointers).length > 1 && options.zoomable && options.zoomOnTouch) {
        action = ACTION_ZOOM;
      } else {
        action = getData(event.target, DATA_ACTION);
      }
      if (!REGEXP_ACTIONS.test(action)) {
        return;
      }
      if (dispatchEvent(this.element, EVENT_CROP_START, {
        originalEvent: event,
        action: action
      }) === false) {
        return;
      }

      // This line is required for preventing page zooming in iOS browsers
      event.preventDefault();
      this.action = action;
      this.cropping = false;
      if (action === ACTION_CROP) {
        this.cropping = true;
        addClass(this.dragBox, CLASS_MODAL);
      }
    },
    cropMove: function cropMove(event) {
      var action = this.action;
      if (this.disabled || !action) {
        return;
      }
      var pointers = this.pointers;
      event.preventDefault();
      if (dispatchEvent(this.element, EVENT_CROP_MOVE, {
        originalEvent: event,
        action: action
      }) === false) {
        return;
      }
      if (event.changedTouches) {
        forEach(event.changedTouches, function (touch) {
          // The first parameter should not be undefined (#432)
          assign(pointers[touch.identifier] || {}, getPointer(touch, true));
        });
      } else {
        assign(pointers[event.pointerId || 0] || {}, getPointer(event, true));
      }
      this.change(event);
    },
    cropEnd: function cropEnd(event) {
      if (this.disabled) {
        return;
      }
      var action = this.action,
        pointers = this.pointers;
      if (event.changedTouches) {
        forEach(event.changedTouches, function (touch) {
          delete pointers[touch.identifier];
        });
      } else {
        delete pointers[event.pointerId || 0];
      }
      if (!action) {
        return;
      }
      event.preventDefault();
      if (!Object.keys(pointers).length) {
        this.action = '';
      }
      if (this.cropping) {
        this.cropping = false;
        toggleClass(this.dragBox, CLASS_MODAL, this.cropped && this.options.modal);
      }
      dispatchEvent(this.element, EVENT_CROP_END, {
        originalEvent: event,
        action: action
      });
    }
  };

  var change = {
    change: function change(event) {
      var options = this.options,
        canvasData = this.canvasData,
        containerData = this.containerData,
        cropBoxData = this.cropBoxData,
        pointers = this.pointers;
      var action = this.action;
      var aspectRatio = options.aspectRatio;
      var left = cropBoxData.left,
        top = cropBoxData.top,
        width = cropBoxData.width,
        height = cropBoxData.height;
      var right = left + width;
      var bottom = top + height;
      var minLeft = 0;
      var minTop = 0;
      var maxWidth = containerData.width;
      var maxHeight = containerData.height;
      var renderable = true;
      var offset;

      // Locking aspect ratio in "free mode" by holding shift key
      if (!aspectRatio && event.shiftKey) {
        aspectRatio = width && height ? width / height : 1;
      }
      if (this.limited) {
        minLeft = cropBoxData.minLeft;
        minTop = cropBoxData.minTop;
        maxWidth = minLeft + Math.min(containerData.width, canvasData.width, canvasData.left + canvasData.width);
        maxHeight = minTop + Math.min(containerData.height, canvasData.height, canvasData.top + canvasData.height);
      }
      var pointer = pointers[Object.keys(pointers)[0]];
      var range = {
        x: pointer.endX - pointer.startX,
        y: pointer.endY - pointer.startY
      };
      var check = function check(side) {
        switch (side) {
          case ACTION_EAST:
            if (right + range.x > maxWidth) {
              range.x = maxWidth - right;
            }
            break;
          case ACTION_WEST:
            if (left + range.x < minLeft) {
              range.x = minLeft - left;
            }
            break;
          case ACTION_NORTH:
            if (top + range.y < minTop) {
              range.y = minTop - top;
            }
            break;
          case ACTION_SOUTH:
            if (bottom + range.y > maxHeight) {
              range.y = maxHeight - bottom;
            }
            break;
        }
      };
      switch (action) {
        // Move crop box
        case ACTION_ALL:
          left += range.x;
          top += range.y;
          break;

        // Resize crop box
        case ACTION_EAST:
          if (range.x >= 0 && (right >= maxWidth || aspectRatio && (top <= minTop || bottom >= maxHeight))) {
            renderable = false;
            break;
          }
          check(ACTION_EAST);
          width += range.x;
          if (width < 0) {
            action = ACTION_WEST;
            width = -width;
            left -= width;
          }
          if (aspectRatio) {
            height = width / aspectRatio;
            top += (cropBoxData.height - height) / 2;
          }
          break;
        case ACTION_NORTH:
          if (range.y <= 0 && (top <= minTop || aspectRatio && (left <= minLeft || right >= maxWidth))) {
            renderable = false;
            break;
          }
          check(ACTION_NORTH);
          height -= range.y;
          top += range.y;
          if (height < 0) {
            action = ACTION_SOUTH;
            height = -height;
            top -= height;
          }
          if (aspectRatio) {
            width = height * aspectRatio;
            left += (cropBoxData.width - width) / 2;
          }
          break;
        case ACTION_WEST:
          if (range.x <= 0 && (left <= minLeft || aspectRatio && (top <= minTop || bottom >= maxHeight))) {
            renderable = false;
            break;
          }
          check(ACTION_WEST);
          width -= range.x;
          left += range.x;
          if (width < 0) {
            action = ACTION_EAST;
            width = -width;
            left -= width;
          }
          if (aspectRatio) {
            height = width / aspectRatio;
            top += (cropBoxData.height - height) / 2;
          }
          break;
        case ACTION_SOUTH:
          if (range.y >= 0 && (bottom >= maxHeight || aspectRatio && (left <= minLeft || right >= maxWidth))) {
            renderable = false;
            break;
          }
          check(ACTION_SOUTH);
          height += range.y;
          if (height < 0) {
            action = ACTION_NORTH;
            height = -height;
            top -= height;
          }
          if (aspectRatio) {
            width = height * aspectRatio;
            left += (cropBoxData.width - width) / 2;
          }
          break;
        case ACTION_NORTH_EAST:
          if (aspectRatio) {
            if (range.y <= 0 && (top <= minTop || right >= maxWidth)) {
              renderable = false;
              break;
            }
            check(ACTION_NORTH);
            height -= range.y;
            top += range.y;
            width = height * aspectRatio;
          } else {
            check(ACTION_NORTH);
            check(ACTION_EAST);
            if (range.x >= 0) {
              if (right < maxWidth) {
                width += range.x;
              } else if (range.y <= 0 && top <= minTop) {
                renderable = false;
              }
            } else {
              width += range.x;
            }
            if (range.y <= 0) {
              if (top > minTop) {
                height -= range.y;
                top += range.y;
              }
            } else {
              height -= range.y;
              top += range.y;
            }
          }
          if (width < 0 && height < 0) {
            action = ACTION_SOUTH_WEST;
            height = -height;
            width = -width;
            top -= height;
            left -= width;
          } else if (width < 0) {
            action = ACTION_NORTH_WEST;
            width = -width;
            left -= width;
          } else if (height < 0) {
            action = ACTION_SOUTH_EAST;
            height = -height;
            top -= height;
          }
          break;
        case ACTION_NORTH_WEST:
          if (aspectRatio) {
            if (range.y <= 0 && (top <= minTop || left <= minLeft)) {
              renderable = false;
              break;
            }
            check(ACTION_NORTH);
            height -= range.y;
            top += range.y;
            width = height * aspectRatio;
            left += cropBoxData.width - width;
          } else {
            check(ACTION_NORTH);
            check(ACTION_WEST);
            if (range.x <= 0) {
              if (left > minLeft) {
                width -= range.x;
                left += range.x;
              } else if (range.y <= 0 && top <= minTop) {
                renderable = false;
              }
            } else {
              width -= range.x;
              left += range.x;
            }
            if (range.y <= 0) {
              if (top > minTop) {
                height -= range.y;
                top += range.y;
              }
            } else {
              height -= range.y;
              top += range.y;
            }
          }
          if (width < 0 && height < 0) {
            action = ACTION_SOUTH_EAST;
            height = -height;
            width = -width;
            top -= height;
            left -= width;
          } else if (width < 0) {
            action = ACTION_NORTH_EAST;
            width = -width;
            left -= width;
          } else if (height < 0) {
            action = ACTION_SOUTH_WEST;
            height = -height;
            top -= height;
          }
          break;
        case ACTION_SOUTH_WEST:
          if (aspectRatio) {
            if (range.x <= 0 && (left <= minLeft || bottom >= maxHeight)) {
              renderable = false;
              break;
            }
            check(ACTION_WEST);
            width -= range.x;
            left += range.x;
            height = width / aspectRatio;
          } else {
            check(ACTION_SOUTH);
            check(ACTION_WEST);
            if (range.x <= 0) {
              if (left > minLeft) {
                width -= range.x;
                left += range.x;
              } else if (range.y >= 0 && bottom >= maxHeight) {
                renderable = false;
              }
            } else {
              width -= range.x;
              left += range.x;
            }
            if (range.y >= 0) {
              if (bottom < maxHeight) {
                height += range.y;
              }
            } else {
              height += range.y;
            }
          }
          if (width < 0 && height < 0) {
            action = ACTION_NORTH_EAST;
            height = -height;
            width = -width;
            top -= height;
            left -= width;
          } else if (width < 0) {
            action = ACTION_SOUTH_EAST;
            width = -width;
            left -= width;
          } else if (height < 0) {
            action = ACTION_NORTH_WEST;
            height = -height;
            top -= height;
          }
          break;
        case ACTION_SOUTH_EAST:
          if (aspectRatio) {
            if (range.x >= 0 && (right >= maxWidth || bottom >= maxHeight)) {
              renderable = false;
              break;
            }
            check(ACTION_EAST);
            width += range.x;
            height = width / aspectRatio;
          } else {
            check(ACTION_SOUTH);
            check(ACTION_EAST);
            if (range.x >= 0) {
              if (right < maxWidth) {
                width += range.x;
              } else if (range.y >= 0 && bottom >= maxHeight) {
                renderable = false;
              }
            } else {
              width += range.x;
            }
            if (range.y >= 0) {
              if (bottom < maxHeight) {
                height += range.y;
              }
            } else {
              height += range.y;
            }
          }
          if (width < 0 && height < 0) {
            action = ACTION_NORTH_WEST;
            height = -height;
            width = -width;
            top -= height;
            left -= width;
          } else if (width < 0) {
            action = ACTION_SOUTH_WEST;
            width = -width;
            left -= width;
          } else if (height < 0) {
            action = ACTION_NORTH_EAST;
            height = -height;
            top -= height;
          }
          break;

        // Move canvas
        case ACTION_MOVE:
          this.move(range.x, range.y);
          renderable = false;
          break;

        // Zoom canvas
        case ACTION_ZOOM:
          this.zoom(getMaxZoomRatio(pointers), event);
          renderable = false;
          break;

        // Create crop box
        case ACTION_CROP:
          if (!range.x || !range.y) {
            renderable = false;
            break;
          }
          offset = getOffset(this.cropper);
          left = pointer.startX - offset.left;
          top = pointer.startY - offset.top;
          width = cropBoxData.minWidth;
          height = cropBoxData.minHeight;
          if (range.x > 0) {
            action = range.y > 0 ? ACTION_SOUTH_EAST : ACTION_NORTH_EAST;
          } else if (range.x < 0) {
            left -= width;
            action = range.y > 0 ? ACTION_SOUTH_WEST : ACTION_NORTH_WEST;
          }
          if (range.y < 0) {
            top -= height;
          }

          // Show the crop box if is hidden
          if (!this.cropped) {
            removeClass(this.cropBox, CLASS_HIDDEN);
            this.cropped = true;
            if (this.limited) {
              this.limitCropBox(true, true);
            }
          }
          break;
      }
      if (renderable) {
        cropBoxData.width = width;
        cropBoxData.height = height;
        cropBoxData.left = left;
        cropBoxData.top = top;
        this.action = action;
        this.renderCropBox();
      }

      // Override
      forEach(pointers, function (p) {
        p.startX = p.endX;
        p.startY = p.endY;
      });
    }
  };

  var methods = {
    // Show the crop box manually
    crop: function crop() {
      if (this.ready && !this.cropped && !this.disabled) {
        this.cropped = true;
        this.limitCropBox(true, true);
        if (this.options.modal) {
          addClass(this.dragBox, CLASS_MODAL);
        }
        removeClass(this.cropBox, CLASS_HIDDEN);
        this.setCropBoxData(this.initialCropBoxData);
      }
      return this;
    },
    // Reset the image and crop box to their initial states
    reset: function reset() {
      if (this.ready && !this.disabled) {
        this.imageData = assign({}, this.initialImageData);
        this.canvasData = assign({}, this.initialCanvasData);
        this.cropBoxData = assign({}, this.initialCropBoxData);
        this.renderCanvas();
        if (this.cropped) {
          this.renderCropBox();
        }
      }
      return this;
    },
    // Clear the crop box
    clear: function clear() {
      if (this.cropped && !this.disabled) {
        assign(this.cropBoxData, {
          left: 0,
          top: 0,
          width: 0,
          height: 0
        });
        this.cropped = false;
        this.renderCropBox();
        this.limitCanvas(true, true);

        // Render canvas after crop box rendered
        this.renderCanvas();
        removeClass(this.dragBox, CLASS_MODAL);
        addClass(this.cropBox, CLASS_HIDDEN);
      }
      return this;
    },
    /**
     * Replace the image's src and rebuild the cropper
     * @param {string} url - The new URL.
     * @param {boolean} [hasSameSize] - Indicate if the new image has the same size as the old one.
     * @returns {Cropper} this
     */
    replace: function replace(url) {
      var hasSameSize = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      if (!this.disabled && url) {
        if (this.isImg) {
          this.element.src = url;
        }
        if (hasSameSize) {
          this.url = url;
          this.image.src = url;
          if (this.ready) {
            this.viewBoxImage.src = url;
            forEach(this.previews, function (element) {
              element.getElementsByTagName('img')[0].src = url;
            });
          }
        } else {
          if (this.isImg) {
            this.replaced = true;
          }
          this.options.data = null;
          this.uncreate();
          this.load(url);
        }
      }
      return this;
    },
    // Enable (unfreeze) the cropper
    enable: function enable() {
      if (this.ready && this.disabled) {
        this.disabled = false;
        removeClass(this.cropper, CLASS_DISABLED);
      }
      return this;
    },
    // Disable (freeze) the cropper
    disable: function disable() {
      if (this.ready && !this.disabled) {
        this.disabled = true;
        addClass(this.cropper, CLASS_DISABLED);
      }
      return this;
    },
    /**
     * Destroy the cropper and remove the instance from the image
     * @returns {Cropper} this
     */
    destroy: function destroy() {
      var element = this.element;
      if (!element[NAMESPACE]) {
        return this;
      }
      element[NAMESPACE] = undefined;
      if (this.isImg && this.replaced) {
        element.src = this.originalUrl;
      }
      this.uncreate();
      return this;
    },
    /**
     * Move the canvas with relative offsets
     * @param {number} offsetX - The relative offset distance on the x-axis.
     * @param {number} [offsetY=offsetX] - The relative offset distance on the y-axis.
     * @returns {Cropper} this
     */
    move: function move(offsetX) {
      var offsetY = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : offsetX;
      var _this$canvasData = this.canvasData,
        left = _this$canvasData.left,
        top = _this$canvasData.top;
      return this.moveTo(isUndefined(offsetX) ? offsetX : left + Number(offsetX), isUndefined(offsetY) ? offsetY : top + Number(offsetY));
    },
    /**
     * Move the canvas to an absolute point
     * @param {number} x - The x-axis coordinate.
     * @param {number} [y=x] - The y-axis coordinate.
     * @returns {Cropper} this
     */
    moveTo: function moveTo(x) {
      var y = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : x;
      var canvasData = this.canvasData;
      var changed = false;
      x = Number(x);
      y = Number(y);
      if (this.ready && !this.disabled && this.options.movable) {
        if (isNumber(x)) {
          canvasData.left = x;
          changed = true;
        }
        if (isNumber(y)) {
          canvasData.top = y;
          changed = true;
        }
        if (changed) {
          this.renderCanvas(true);
        }
      }
      return this;
    },
    /**
     * Zoom the canvas with a relative ratio
     * @param {number} ratio - The target ratio.
     * @param {Event} _originalEvent - The original event if any.
     * @returns {Cropper} this
     */
    zoom: function zoom(ratio, _originalEvent) {
      var canvasData = this.canvasData;
      ratio = Number(ratio);
      if (ratio < 0) {
        ratio = 1 / (1 - ratio);
      } else {
        ratio = 1 + ratio;
      }
      return this.zoomTo(canvasData.width * ratio / canvasData.naturalWidth, null, _originalEvent);
    },
    /**
     * Zoom the canvas to an absolute ratio
     * @param {number} ratio - The target ratio.
     * @param {Object} pivot - The zoom pivot point coordinate.
     * @param {Event} _originalEvent - The original event if any.
     * @returns {Cropper} this
     */
    zoomTo: function zoomTo(ratio, pivot, _originalEvent) {
      var options = this.options,
        canvasData = this.canvasData;
      var width = canvasData.width,
        height = canvasData.height,
        naturalWidth = canvasData.naturalWidth,
        naturalHeight = canvasData.naturalHeight;
      ratio = Number(ratio);
      if (ratio >= 0 && this.ready && !this.disabled && options.zoomable) {
        var newWidth = naturalWidth * ratio;
        var newHeight = naturalHeight * ratio;
        if (dispatchEvent(this.element, EVENT_ZOOM, {
          ratio: ratio,
          oldRatio: width / naturalWidth,
          originalEvent: _originalEvent
        }) === false) {
          return this;
        }
        if (_originalEvent) {
          var pointers = this.pointers;
          var offset = getOffset(this.cropper);
          var center = pointers && Object.keys(pointers).length ? getPointersCenter(pointers) : {
            pageX: _originalEvent.pageX,
            pageY: _originalEvent.pageY
          };

          // Zoom from the triggering point of the event
          canvasData.left -= (newWidth - width) * ((center.pageX - offset.left - canvasData.left) / width);
          canvasData.top -= (newHeight - height) * ((center.pageY - offset.top - canvasData.top) / height);
        } else if (isPlainObject(pivot) && isNumber(pivot.x) && isNumber(pivot.y)) {
          canvasData.left -= (newWidth - width) * ((pivot.x - canvasData.left) / width);
          canvasData.top -= (newHeight - height) * ((pivot.y - canvasData.top) / height);
        } else {
          // Zoom from the center of the canvas
          canvasData.left -= (newWidth - width) / 2;
          canvasData.top -= (newHeight - height) / 2;
        }
        canvasData.width = newWidth;
        canvasData.height = newHeight;
        this.renderCanvas(true);
      }
      return this;
    },
    /**
     * Rotate the canvas with a relative degree
     * @param {number} degree - The rotate degree.
     * @returns {Cropper} this
     */
    rotate: function rotate(degree) {
      return this.rotateTo((this.imageData.rotate || 0) + Number(degree));
    },
    /**
     * Rotate the canvas to an absolute degree
     * @param {number} degree - The rotate degree.
     * @returns {Cropper} this
     */
    rotateTo: function rotateTo(degree) {
      degree = Number(degree);
      if (isNumber(degree) && this.ready && !this.disabled && this.options.rotatable) {
        this.imageData.rotate = degree % 360;
        this.renderCanvas(true, true);
      }
      return this;
    },
    /**
     * Scale the image on the x-axis.
     * @param {number} scaleX - The scale ratio on the x-axis.
     * @returns {Cropper} this
     */
    scaleX: function scaleX(_scaleX) {
      var scaleY = this.imageData.scaleY;
      return this.scale(_scaleX, isNumber(scaleY) ? scaleY : 1);
    },
    /**
     * Scale the image on the y-axis.
     * @param {number} scaleY - The scale ratio on the y-axis.
     * @returns {Cropper} this
     */
    scaleY: function scaleY(_scaleY) {
      var scaleX = this.imageData.scaleX;
      return this.scale(isNumber(scaleX) ? scaleX : 1, _scaleY);
    },
    /**
     * Scale the image
     * @param {number} scaleX - The scale ratio on the x-axis.
     * @param {number} [scaleY=scaleX] - The scale ratio on the y-axis.
     * @returns {Cropper} this
     */
    scale: function scale(scaleX) {
      var scaleY = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : scaleX;
      var imageData = this.imageData;
      var transformed = false;
      scaleX = Number(scaleX);
      scaleY = Number(scaleY);
      if (this.ready && !this.disabled && this.options.scalable) {
        if (isNumber(scaleX)) {
          imageData.scaleX = scaleX;
          transformed = true;
        }
        if (isNumber(scaleY)) {
          imageData.scaleY = scaleY;
          transformed = true;
        }
        if (transformed) {
          this.renderCanvas(true, true);
        }
      }
      return this;
    },
    /**
     * Get the cropped area position and size data (base on the original image)
     * @param {boolean} [rounded=false] - Indicate if round the data values or not.
     * @returns {Object} The result cropped data.
     */
    getData: function getData() {
      var rounded = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      var options = this.options,
        imageData = this.imageData,
        canvasData = this.canvasData,
        cropBoxData = this.cropBoxData;
      var data;
      if (this.ready && this.cropped) {
        data = {
          x: cropBoxData.left - canvasData.left,
          y: cropBoxData.top - canvasData.top,
          width: cropBoxData.width,
          height: cropBoxData.height
        };
        var ratio = imageData.width / imageData.naturalWidth;
        forEach(data, function (n, i) {
          data[i] = n / ratio;
        });
        if (rounded) {
          // In case rounding off leads to extra 1px in right or bottom border
          // we should round the top-left corner and the dimension (#343).
          var bottom = Math.round(data.y + data.height);
          var right = Math.round(data.x + data.width);
          data.x = Math.round(data.x);
          data.y = Math.round(data.y);
          data.width = right - data.x;
          data.height = bottom - data.y;
        }
      } else {
        data = {
          x: 0,
          y: 0,
          width: 0,
          height: 0
        };
      }
      if (options.rotatable) {
        data.rotate = imageData.rotate || 0;
      }
      if (options.scalable) {
        data.scaleX = imageData.scaleX || 1;
        data.scaleY = imageData.scaleY || 1;
      }
      return data;
    },
    /**
     * Set the cropped area position and size with new data
     * @param {Object} data - The new data.
     * @returns {Cropper} this
     */
    setData: function setData(data) {
      var options = this.options,
        imageData = this.imageData,
        canvasData = this.canvasData;
      var cropBoxData = {};
      if (this.ready && !this.disabled && isPlainObject(data)) {
        var transformed = false;
        if (options.rotatable) {
          if (isNumber(data.rotate) && data.rotate !== imageData.rotate) {
            imageData.rotate = data.rotate;
            transformed = true;
          }
        }
        if (options.scalable) {
          if (isNumber(data.scaleX) && data.scaleX !== imageData.scaleX) {
            imageData.scaleX = data.scaleX;
            transformed = true;
          }
          if (isNumber(data.scaleY) && data.scaleY !== imageData.scaleY) {
            imageData.scaleY = data.scaleY;
            transformed = true;
          }
        }
        if (transformed) {
          this.renderCanvas(true, true);
        }
        var ratio = imageData.width / imageData.naturalWidth;
        if (isNumber(data.x)) {
          cropBoxData.left = data.x * ratio + canvasData.left;
        }
        if (isNumber(data.y)) {
          cropBoxData.top = data.y * ratio + canvasData.top;
        }
        if (isNumber(data.width)) {
          cropBoxData.width = data.width * ratio;
        }
        if (isNumber(data.height)) {
          cropBoxData.height = data.height * ratio;
        }
        this.setCropBoxData(cropBoxData);
      }
      return this;
    },
    /**
     * Get the container size data.
     * @returns {Object} The result container data.
     */
    getContainerData: function getContainerData() {
      return this.ready ? assign({}, this.containerData) : {};
    },
    /**
     * Get the image position and size data.
     * @returns {Object} The result image data.
     */
    getImageData: function getImageData() {
      return this.sized ? assign({}, this.imageData) : {};
    },
    /**
     * Get the canvas position and size data.
     * @returns {Object} The result canvas data.
     */
    getCanvasData: function getCanvasData() {
      var canvasData = this.canvasData;
      var data = {};
      if (this.ready) {
        forEach(['left', 'top', 'width', 'height', 'naturalWidth', 'naturalHeight'], function (n) {
          data[n] = canvasData[n];
        });
      }
      return data;
    },
    /**
     * Set the canvas position and size with new data.
     * @param {Object} data - The new canvas data.
     * @returns {Cropper} this
     */
    setCanvasData: function setCanvasData(data) {
      var canvasData = this.canvasData;
      var aspectRatio = canvasData.aspectRatio;
      if (this.ready && !this.disabled && isPlainObject(data)) {
        if (isNumber(data.left)) {
          canvasData.left = data.left;
        }
        if (isNumber(data.top)) {
          canvasData.top = data.top;
        }
        if (isNumber(data.width)) {
          canvasData.width = data.width;
          canvasData.height = data.width / aspectRatio;
        } else if (isNumber(data.height)) {
          canvasData.height = data.height;
          canvasData.width = data.height * aspectRatio;
        }
        this.renderCanvas(true);
      }
      return this;
    },
    /**
     * Get the crop box position and size data.
     * @returns {Object} The result crop box data.
     */
    getCropBoxData: function getCropBoxData() {
      var cropBoxData = this.cropBoxData;
      var data;
      if (this.ready && this.cropped) {
        data = {
          left: cropBoxData.left,
          top: cropBoxData.top,
          width: cropBoxData.width,
          height: cropBoxData.height
        };
      }
      return data || {};
    },
    /**
     * Set the crop box position and size with new data.
     * @param {Object} data - The new crop box data.
     * @returns {Cropper} this
     */
    setCropBoxData: function setCropBoxData(data) {
      var cropBoxData = this.cropBoxData;
      var aspectRatio = this.options.aspectRatio;
      var widthChanged;
      var heightChanged;
      if (this.ready && this.cropped && !this.disabled && isPlainObject(data)) {
        if (isNumber(data.left)) {
          cropBoxData.left = data.left;
        }
        if (isNumber(data.top)) {
          cropBoxData.top = data.top;
        }
        if (isNumber(data.width) && data.width !== cropBoxData.width) {
          widthChanged = true;
          cropBoxData.width = data.width;
        }
        if (isNumber(data.height) && data.height !== cropBoxData.height) {
          heightChanged = true;
          cropBoxData.height = data.height;
        }
        if (aspectRatio) {
          if (widthChanged) {
            cropBoxData.height = cropBoxData.width / aspectRatio;
          } else if (heightChanged) {
            cropBoxData.width = cropBoxData.height * aspectRatio;
          }
        }
        this.renderCropBox();
      }
      return this;
    },
    /**
     * Get a canvas drawn the cropped image.
     * @param {Object} [options={}] - The config options.
     * @returns {HTMLCanvasElement} - The result canvas.
     */
    getCroppedCanvas: function getCroppedCanvas() {
      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      if (!this.ready || !window.HTMLCanvasElement) {
        return null;
      }
      var canvasData = this.canvasData;
      var source = getSourceCanvas(this.image, this.imageData, canvasData, options);

      // Returns the source canvas if it is not cropped.
      if (!this.cropped) {
        return source;
      }
      var _this$getData = this.getData(),
        initialX = _this$getData.x,
        initialY = _this$getData.y,
        initialWidth = _this$getData.width,
        initialHeight = _this$getData.height;
      var ratio = source.width / Math.floor(canvasData.naturalWidth);
      if (ratio !== 1) {
        initialX *= ratio;
        initialY *= ratio;
        initialWidth *= ratio;
        initialHeight *= ratio;
      }
      var aspectRatio = initialWidth / initialHeight;
      var maxSizes = getAdjustedSizes({
        aspectRatio: aspectRatio,
        width: options.maxWidth || Infinity,
        height: options.maxHeight || Infinity
      });
      var minSizes = getAdjustedSizes({
        aspectRatio: aspectRatio,
        width: options.minWidth || 0,
        height: options.minHeight || 0
      }, 'cover');
      var _getAdjustedSizes = getAdjustedSizes({
          aspectRatio: aspectRatio,
          width: options.width || (ratio !== 1 ? source.width : initialWidth),
          height: options.height || (ratio !== 1 ? source.height : initialHeight)
        }),
        width = _getAdjustedSizes.width,
        height = _getAdjustedSizes.height;
      width = Math.min(maxSizes.width, Math.max(minSizes.width, width));
      height = Math.min(maxSizes.height, Math.max(minSizes.height, height));
      var canvas = document.createElement('canvas');
      var context = canvas.getContext('2d');
      canvas.width = normalizeDecimalNumber(width);
      canvas.height = normalizeDecimalNumber(height);
      context.fillStyle = options.fillColor || 'transparent';
      context.fillRect(0, 0, width, height);
      var _options$imageSmoothi = options.imageSmoothingEnabled,
        imageSmoothingEnabled = _options$imageSmoothi === void 0 ? true : _options$imageSmoothi,
        imageSmoothingQuality = options.imageSmoothingQuality;
      context.imageSmoothingEnabled = imageSmoothingEnabled;
      if (imageSmoothingQuality) {
        context.imageSmoothingQuality = imageSmoothingQuality;
      }

      // https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D.drawImage
      var sourceWidth = source.width;
      var sourceHeight = source.height;

      // Source canvas parameters
      var srcX = initialX;
      var srcY = initialY;
      var srcWidth;
      var srcHeight;

      // Destination canvas parameters
      var dstX;
      var dstY;
      var dstWidth;
      var dstHeight;
      if (srcX <= -initialWidth || srcX > sourceWidth) {
        srcX = 0;
        srcWidth = 0;
        dstX = 0;
        dstWidth = 0;
      } else if (srcX <= 0) {
        dstX = -srcX;
        srcX = 0;
        srcWidth = Math.min(sourceWidth, initialWidth + srcX);
        dstWidth = srcWidth;
      } else if (srcX <= sourceWidth) {
        dstX = 0;
        srcWidth = Math.min(initialWidth, sourceWidth - srcX);
        dstWidth = srcWidth;
      }
      if (srcWidth <= 0 || srcY <= -initialHeight || srcY > sourceHeight) {
        srcY = 0;
        srcHeight = 0;
        dstY = 0;
        dstHeight = 0;
      } else if (srcY <= 0) {
        dstY = -srcY;
        srcY = 0;
        srcHeight = Math.min(sourceHeight, initialHeight + srcY);
        dstHeight = srcHeight;
      } else if (srcY <= sourceHeight) {
        dstY = 0;
        srcHeight = Math.min(initialHeight, sourceHeight - srcY);
        dstHeight = srcHeight;
      }
      var params = [srcX, srcY, srcWidth, srcHeight];

      // Avoid "IndexSizeError"
      if (dstWidth > 0 && dstHeight > 0) {
        var scale = width / initialWidth;
        params.push(dstX * scale, dstY * scale, dstWidth * scale, dstHeight * scale);
      }

      // All the numerical parameters should be integer for `drawImage`
      // https://github.com/fengyuanchen/cropper/issues/476
      context.drawImage.apply(context, [source].concat(_toConsumableArray(params.map(function (param) {
        return Math.floor(normalizeDecimalNumber(param));
      }))));
      return canvas;
    },
    /**
     * Change the aspect ratio of the crop box.
     * @param {number} aspectRatio - The new aspect ratio.
     * @returns {Cropper} this
     */
    setAspectRatio: function setAspectRatio(aspectRatio) {
      var options = this.options;
      if (!this.disabled && !isUndefined(aspectRatio)) {
        // 0 -> NaN
        options.aspectRatio = Math.max(0, aspectRatio) || NaN;
        if (this.ready) {
          this.initCropBox();
          if (this.cropped) {
            this.renderCropBox();
          }
        }
      }
      return this;
    },
    /**
     * Change the drag mode.
     * @param {string} mode - The new drag mode.
     * @returns {Cropper} this
     */
    setDragMode: function setDragMode(mode) {
      var options = this.options,
        dragBox = this.dragBox,
        face = this.face;
      if (this.ready && !this.disabled) {
        var croppable = mode === DRAG_MODE_CROP;
        var movable = options.movable && mode === DRAG_MODE_MOVE;
        mode = croppable || movable ? mode : DRAG_MODE_NONE;
        options.dragMode = mode;
        setData(dragBox, DATA_ACTION, mode);
        toggleClass(dragBox, CLASS_CROP, croppable);
        toggleClass(dragBox, CLASS_MOVE, movable);
        if (!options.cropBoxMovable) {
          // Sync drag mode to crop box when it is not movable
          setData(face, DATA_ACTION, mode);
          toggleClass(face, CLASS_CROP, croppable);
          toggleClass(face, CLASS_MOVE, movable);
        }
      }
      return this;
    }
  };

  var AnotherCropper = WINDOW.Cropper;
  var Cropper = /*#__PURE__*/function () {
    /**
     * Create a new Cropper.
     * @param {Element} element - The target element for cropping.
     * @param {Object} [options={}] - The configuration options.
     */
    function Cropper(element) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      _classCallCheck(this, Cropper);
      if (!element || !REGEXP_TAG_NAME.test(element.tagName)) {
        throw new Error('The first argument is required and must be an <img> or <canvas> element.');
      }
      this.element = element;
      this.options = assign({}, DEFAULTS, isPlainObject(options) && options);
      this.cropped = false;
      this.disabled = false;
      this.pointers = {};
      this.ready = false;
      this.reloading = false;
      this.replaced = false;
      this.sized = false;
      this.sizing = false;
      this.init();
    }
    _createClass(Cropper, [{
      key: "init",
      value: function init() {
        var element = this.element;
        var tagName = element.tagName.toLowerCase();
        var url;
        if (element[NAMESPACE]) {
          return;
        }
        element[NAMESPACE] = this;
        if (tagName === 'img') {
          this.isImg = true;

          // e.g.: "img/picture.jpg"
          url = element.getAttribute('src') || '';
          this.originalUrl = url;

          // Stop when it's a blank image
          if (!url) {
            return;
          }

          // e.g.: "https://example.com/img/picture.jpg"
          url = element.src;
        } else if (tagName === 'canvas' && window.HTMLCanvasElement) {
          url = element.toDataURL();
        }
        this.load(url);
      }
    }, {
      key: "load",
      value: function load(url) {
        var _this = this;
        if (!url) {
          return;
        }
        this.url = url;
        this.imageData = {};
        var element = this.element,
          options = this.options;
        if (!options.rotatable && !options.scalable) {
          options.checkOrientation = false;
        }

        // Only IE10+ supports Typed Arrays
        if (!options.checkOrientation || !window.ArrayBuffer) {
          this.clone();
          return;
        }

        // Detect the mime type of the image directly if it is a Data URL
        if (REGEXP_DATA_URL.test(url)) {
          // Read ArrayBuffer from Data URL of JPEG images directly for better performance
          if (REGEXP_DATA_URL_JPEG.test(url)) {
            this.read(dataURLToArrayBuffer(url));
          } else {
            // Only a JPEG image may contains Exif Orientation information,
            // the rest types of Data URLs are not necessary to check orientation at all.
            this.clone();
          }
          return;
        }

        // 1. Detect the mime type of the image by a XMLHttpRequest.
        // 2. Load the image as ArrayBuffer for reading orientation if its a JPEG image.
        var xhr = new XMLHttpRequest();
        var clone = this.clone.bind(this);
        this.reloading = true;
        this.xhr = xhr;

        // 1. Cross origin requests are only supported for protocol schemes:
        // http, https, data, chrome, chrome-extension.
        // 2. Access to XMLHttpRequest from a Data URL will be blocked by CORS policy
        // in some browsers as IE11 and Safari.
        xhr.onabort = clone;
        xhr.onerror = clone;
        xhr.ontimeout = clone;
        xhr.onprogress = function () {
          // Abort the request directly if it not a JPEG image for better performance
          if (xhr.getResponseHeader('content-type') !== MIME_TYPE_JPEG) {
            xhr.abort();
          }
        };
        xhr.onload = function () {
          _this.read(xhr.response);
        };
        xhr.onloadend = function () {
          _this.reloading = false;
          _this.xhr = null;
        };

        // Bust cache when there is a "crossOrigin" property to avoid browser cache error
        if (options.checkCrossOrigin && isCrossOriginURL(url) && element.crossOrigin) {
          url = addTimestamp(url);
        }

        // The third parameter is required for avoiding side-effect (#682)
        xhr.open('GET', url, true);
        xhr.responseType = 'arraybuffer';
        xhr.withCredentials = element.crossOrigin === 'use-credentials';
        xhr.send();
      }
    }, {
      key: "read",
      value: function read(arrayBuffer) {
        var options = this.options,
          imageData = this.imageData;

        // Reset the orientation value to its default value 1
        // as some iOS browsers will render image with its orientation
        var orientation = resetAndGetOrientation(arrayBuffer);
        var rotate = 0;
        var scaleX = 1;
        var scaleY = 1;
        if (orientation > 1) {
          // Generate a new URL which has the default orientation value
          this.url = arrayBufferToDataURL(arrayBuffer, MIME_TYPE_JPEG);
          var _parseOrientation = parseOrientation(orientation);
          rotate = _parseOrientation.rotate;
          scaleX = _parseOrientation.scaleX;
          scaleY = _parseOrientation.scaleY;
        }
        if (options.rotatable) {
          imageData.rotate = rotate;
        }
        if (options.scalable) {
          imageData.scaleX = scaleX;
          imageData.scaleY = scaleY;
        }
        this.clone();
      }
    }, {
      key: "clone",
      value: function clone() {
        var element = this.element,
          url = this.url;
        var crossOrigin = element.crossOrigin;
        var crossOriginUrl = url;
        if (this.options.checkCrossOrigin && isCrossOriginURL(url)) {
          if (!crossOrigin) {
            crossOrigin = 'anonymous';
          }

          // Bust cache when there is not a "crossOrigin" property (#519)
          crossOriginUrl = addTimestamp(url);
        }
        this.crossOrigin = crossOrigin;
        this.crossOriginUrl = crossOriginUrl;
        var image = document.createElement('img');
        if (crossOrigin) {
          image.crossOrigin = crossOrigin;
        }
        image.src = crossOriginUrl || url;
        image.alt = element.alt || 'The image to crop';
        this.image = image;
        image.onload = this.start.bind(this);
        image.onerror = this.stop.bind(this);
        addClass(image, CLASS_HIDE);
        element.parentNode.insertBefore(image, element.nextSibling);
      }
    }, {
      key: "start",
      value: function start() {
        var _this2 = this;
        var image = this.image;
        image.onload = null;
        image.onerror = null;
        this.sizing = true;

        // Match all browsers that use WebKit as the layout engine in iOS devices,
        // such as Safari for iOS, Chrome for iOS, and in-app browsers.
        var isIOSWebKit = WINDOW.navigator && /(?:iPad|iPhone|iPod).*?AppleWebKit/i.test(WINDOW.navigator.userAgent);
        var done = function done(naturalWidth, naturalHeight) {
          assign(_this2.imageData, {
            naturalWidth: naturalWidth,
            naturalHeight: naturalHeight,
            aspectRatio: naturalWidth / naturalHeight
          });
          _this2.initialImageData = assign({}, _this2.imageData);
          _this2.sizing = false;
          _this2.sized = true;
          _this2.build();
        };

        // Most modern browsers (excepts iOS WebKit)
        if (image.naturalWidth && !isIOSWebKit) {
          done(image.naturalWidth, image.naturalHeight);
          return;
        }
        var sizingImage = document.createElement('img');
        var body = document.body || document.documentElement;
        this.sizingImage = sizingImage;
        sizingImage.onload = function () {
          done(sizingImage.width, sizingImage.height);
          if (!isIOSWebKit) {
            body.removeChild(sizingImage);
          }
        };
        sizingImage.src = image.src;

        // iOS WebKit will convert the image automatically
        // with its orientation once append it into DOM (#279)
        if (!isIOSWebKit) {
          sizingImage.style.cssText = 'left:0;' + 'max-height:none!important;' + 'max-width:none!important;' + 'min-height:0!important;' + 'min-width:0!important;' + 'opacity:0;' + 'position:absolute;' + 'top:0;' + 'z-index:-1;';
          body.appendChild(sizingImage);
        }
      }
    }, {
      key: "stop",
      value: function stop() {
        var image = this.image;
        image.onload = null;
        image.onerror = null;
        image.parentNode.removeChild(image);
        this.image = null;
      }
    }, {
      key: "build",
      value: function build() {
        if (!this.sized || this.ready) {
          return;
        }
        var element = this.element,
          options = this.options,
          image = this.image;

        // Create cropper elements
        var container = element.parentNode;
        var template = document.createElement('div');
        template.innerHTML = TEMPLATE;
        var cropper = template.querySelector(".".concat(NAMESPACE, "-container"));
        var canvas = cropper.querySelector(".".concat(NAMESPACE, "-canvas"));
        var dragBox = cropper.querySelector(".".concat(NAMESPACE, "-drag-box"));
        var cropBox = cropper.querySelector(".".concat(NAMESPACE, "-crop-box"));
        var face = cropBox.querySelector(".".concat(NAMESPACE, "-face"));
        this.container = container;
        this.cropper = cropper;
        this.canvas = canvas;
        this.dragBox = dragBox;
        this.cropBox = cropBox;
        this.viewBox = cropper.querySelector(".".concat(NAMESPACE, "-view-box"));
        this.face = face;
        canvas.appendChild(image);

        // Hide the original image
        addClass(element, CLASS_HIDDEN);

        // Inserts the cropper after to the current image
        container.insertBefore(cropper, element.nextSibling);

        // Show the hidden image
        removeClass(image, CLASS_HIDE);
        this.initPreview();
        this.bind();
        options.initialAspectRatio = Math.max(0, options.initialAspectRatio) || NaN;
        options.aspectRatio = Math.max(0, options.aspectRatio) || NaN;
        options.viewMode = Math.max(0, Math.min(3, Math.round(options.viewMode))) || 0;
        addClass(cropBox, CLASS_HIDDEN);
        if (!options.guides) {
          addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-dashed")), CLASS_HIDDEN);
        }
        if (!options.center) {
          addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-center")), CLASS_HIDDEN);
        }
        if (options.background) {
          addClass(cropper, "".concat(NAMESPACE, "-bg"));
        }
        if (!options.highlight) {
          addClass(face, CLASS_INVISIBLE);
        }
        if (options.cropBoxMovable) {
          addClass(face, CLASS_MOVE);
          setData(face, DATA_ACTION, ACTION_ALL);
        }
        if (!options.cropBoxResizable) {
          addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-line")), CLASS_HIDDEN);
          addClass(cropBox.getElementsByClassName("".concat(NAMESPACE, "-point")), CLASS_HIDDEN);
        }
        this.render();
        this.ready = true;
        this.setDragMode(options.dragMode);
        if (options.autoCrop) {
          this.crop();
        }
        this.setData(options.data);
        if (isFunction(options.ready)) {
          addListener(element, EVENT_READY, options.ready, {
            once: true
          });
        }
        dispatchEvent(element, EVENT_READY);
      }
    }, {
      key: "unbuild",
      value: function unbuild() {
        if (!this.ready) {
          return;
        }
        this.ready = false;
        this.unbind();
        this.resetPreview();
        var parentNode = this.cropper.parentNode;
        if (parentNode) {
          parentNode.removeChild(this.cropper);
        }
        removeClass(this.element, CLASS_HIDDEN);
      }
    }, {
      key: "uncreate",
      value: function uncreate() {
        if (this.ready) {
          this.unbuild();
          this.ready = false;
          this.cropped = false;
        } else if (this.sizing) {
          this.sizingImage.onload = null;
          this.sizing = false;
          this.sized = false;
        } else if (this.reloading) {
          this.xhr.onabort = null;
          this.xhr.abort();
        } else if (this.image) {
          this.stop();
        }
      }

      /**
       * Get the no conflict cropper class.
       * @returns {Cropper} The cropper class.
       */
    }], [{
      key: "noConflict",
      value: function noConflict() {
        window.Cropper = AnotherCropper;
        return Cropper;
      }

      /**
       * Change the default options.
       * @param {Object} options - The new default options.
       */
    }, {
      key: "setDefaults",
      value: function setDefaults(options) {
        assign(DEFAULTS, isPlainObject(options) && options);
      }
    }]);
    return Cropper;
  }();
  assign(Cropper.prototype, render, preview, events, handlers, change, methods);

  return Cropper;

}));


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**********************************************************!*\
  !*** ./platform/core/media/resources/assets/js/media.js ***!
  \**********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./App/Config/MediaConfig */ "./platform/core/media/resources/assets/js/App/Config/MediaConfig.js");
/* harmony import */ var _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./App/Helpers/Helpers */ "./platform/core/media/resources/assets/js/App/Helpers/Helpers.js");
/* harmony import */ var _App_Services_MediaService__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./App/Services/MediaService */ "./platform/core/media/resources/assets/js/App/Services/MediaService.js");
/* harmony import */ var _App_Services_FolderService__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./App/Services/FolderService */ "./platform/core/media/resources/assets/js/App/Services/FolderService.js");
/* harmony import */ var _App_Services_UploadService__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./App/Services/UploadService */ "./platform/core/media/resources/assets/js/App/Services/UploadService.js");
/* harmony import */ var _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./App/Services/ActionsService */ "./platform/core/media/resources/assets/js/App/Services/ActionsService.js");
/* harmony import */ var _App_Services_DownloadService__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./App/Services/DownloadService */ "./platform/core/media/resources/assets/js/App/Services/DownloadService.js");
/* harmony import */ var _integrate__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./integrate */ "./platform/core/media/resources/assets/js/integrate.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, defineProperty = Object.defineProperty || function (obj, key, desc) { obj[key] = desc.value; }, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return defineProperty(generator, "_invoke", { value: makeInvokeMethod(innerFn, self, context) }), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; defineProperty(this, "_invoke", { value: function value(method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; } function maybeInvokeDelegate(delegate, context) { var methodName = context.method, method = delegate.iterator[methodName]; if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel; var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), defineProperty(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (val) { var object = Object(val), keys = []; for (var key in object) keys.push(key); return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }








var MediaManagement = /*#__PURE__*/function () {
  function MediaManagement() {
    _classCallCheck(this, MediaManagement);
    this.MediaService = new _App_Services_MediaService__WEBPACK_IMPORTED_MODULE_2__.MediaService();
    this.UploadService = new _App_Services_UploadService__WEBPACK_IMPORTED_MODULE_4__.UploadService();
    this.FolderService = new _App_Services_FolderService__WEBPACK_IMPORTED_MODULE_3__.FolderService();
    this.DownloadService = new _App_Services_DownloadService__WEBPACK_IMPORTED_MODULE_6__.DownloadService();
    this.$body = $('body');
  }
  _createClass(MediaManagement, [{
    key: "init",
    value: function init() {
      _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
      this.setupLayout();
      this.handleMediaList();
      this.changeViewType();
      this.changeFilter();
      this.search();
      this.handleActions();
      this.UploadService.init();
      this.handleModals();
      this.scrollGetMore();
    }
  }, {
    key: "setupLayout",
    value: function setupLayout() {
      /**
       * Sidebar
       */
      var $current_filter = $('.js-rv-media-change-filter[data-type="filter"][data-value="' + _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().filter + '"]');
      $current_filter.closest('li').addClass('active').closest('.dropdown').find('.js-rv-media-filter-current').html('(' + $current_filter.html() + ')');
      var $current_view_in = $('.js-rv-media-change-filter[data-type="view_in"][data-value="' + _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().view_in + '"]');
      $current_view_in.closest('li').addClass('active').closest('.dropdown').find('.js-rv-media-filter-current').html('(' + $current_view_in.html() + ')');
      if (_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.isUseInModal()) {
        $('.rv-media-footer').removeClass('hidden');
      }

      /**
       * Sort
       */
      $('.js-rv-media-change-filter[data-type="sort_by"][data-value="' + _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().sort_by + '"]').closest('li').addClass('active');

      /**
       * Details pane
       */
      var $mediaDetailsCheckbox = $('#media_details_collapse');
      $mediaDetailsCheckbox.prop('checked', _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.hide_details_pane || false);
      setTimeout(function () {
        $('.rv-media-details').removeClass('hidden');
      }, 300);
      $mediaDetailsCheckbox.on('change', function (event) {
        event.preventDefault();
        _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.hide_details_pane = $(event.currentTarget).is(':checked');
        _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.storeConfig();
      });
      $(document).on('click', '.js-download-action', function (event) {
        event.preventDefault();
        $('#modal_download_url').modal('show');
      });
      $(document).on('click', '.js-create-folder-action', function (event) {
        event.preventDefault();
        $('#modal_add_folder').modal('show');
      });
    }
  }, {
    key: "handleMediaList",
    value: function handleMediaList() {
      var _self = this;

      /*Ctrl key in Windows*/
      var ctrl_key = false;

      /*Command key in MAC*/
      var meta_key = false;

      /*Shift key*/
      var shift_key = false;
      $(document).on('keyup keydown', function (e) {
        /*User hold ctrl key*/
        ctrl_key = e.ctrlKey;
        /*User hold command key*/
        meta_key = e.metaKey;
        /*User hold shift key*/
        shift_key = e.shiftKey;
      });
      _self.$body.off('click', '.js-media-list-title').on('click', '.js-media-list-title', function (event) {
        event.preventDefault();
        var $current = $(event.currentTarget);
        if (shift_key) {
          var firstItem = _.first(_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems());
          if (firstItem) {
            var firstIndex = firstItem.index_key;
            var currentIndex = $current.index();
            $('.rv-media-items li').each(function (index, el) {
              if (index > firstIndex && index <= currentIndex) {
                $(el).find('input[type=checkbox]').prop('checked', true);
              }
            });
          }
        } else if (!ctrl_key && !meta_key) {
          $current.closest('.rv-media-items').find('input[type=checkbox]').prop('checked', false);
        }
        var $lineCheckBox = $current.find('input[type=checkbox]');
        $lineCheckBox.prop('checked', true);
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.handleDropdown();
        _self.MediaService.getFileDetails($current.data());
      }).on('dblclick', '.js-media-list-title', function (event) {
        event.preventDefault();
        var data = $(event.currentTarget).data();
        if (data.is_folder === true) {
          _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
          _self.FolderService.changeFolder(data.id);
        } else {
          if (!_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.isUseInModal()) {
            _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.handlePreview();
          } else if (_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getConfigs().request_params.view_in !== 'trash') {
            var selectedFiles = _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFiles();
            if (_.size(selectedFiles) > 0) {
              _integrate__WEBPACK_IMPORTED_MODULE_7__.EditorService.editorSelectFile(selectedFiles);
            }
          }
        }
      }).on('dblclick', '.js-up-one-level', function (event) {
        event.preventDefault();
        var count = $('.rv-media-breadcrumb .breadcrumb li').length;
        $('.rv-media-breadcrumb .breadcrumb li:nth-child(' + (count - 1) + ') a').trigger('click');
      }).on('contextmenu', '.js-context-menu', function (event) {
        if (!$(event.currentTarget).find('input[type=checkbox]').is(':checked')) {
          $(event.currentTarget).trigger('click');
        }
      }).on('click contextmenu', '.rv-media-items', function (e) {
        if (!_.size(e.target.closest('.js-context-menu'))) {
          $('.rv-media-items input[type="checkbox"]').prop('checked', false);
          $('.rv-dropdown-actions').addClass('disabled');
          _self.MediaService.getFileDetails({
            icon: 'far fa-image',
            nothing_selected: ''
          });
        }
      });
    }
  }, {
    key: "changeViewType",
    value: function changeViewType() {
      var _self = this;
      _self.$body.off('click', '.js-rv-media-change-view-type .btn').on('click', '.js-rv-media-change-view-type .btn', function (event) {
        event.preventDefault();
        var $current = $(event.currentTarget);
        if ($current.hasClass('active')) {
          return;
        }
        $current.closest('.js-rv-media-change-view-type').find('.btn').removeClass('active');
        $current.addClass('active');
        _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params.view_type = $current.data('type');
        if ($current.data('type') === 'trash') {
          $(document).find('.js-insert-to-editor').prop('disabled', true);
        } else {
          $(document).find('.js-insert-to-editor').prop('disabled', false);
        }
        _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.storeConfig();
        if (typeof RV_MEDIA_CONFIG.pagination != 'undefined') {
          if (typeof RV_MEDIA_CONFIG.pagination.paged != 'undefined') {
            RV_MEDIA_CONFIG.pagination.paged = 1;
          }
        }
        _self.MediaService.getMedia(true, false);
      });
      $('.js-rv-media-change-view-type .btn[data-type="' + _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().view_type + '"]').trigger('click');
      this.bindIntegrateModalEvents();
    }
  }, {
    key: "changeFilter",
    value: function changeFilter() {
      var _self = this;
      _self.$body.off('click', '.js-rv-media-change-filter').on('click', '.js-rv-media-change-filter', function (event) {
        event.preventDefault();
        if (!_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.isOnAjaxLoading()) {
          var $current = $(event.currentTarget);
          var $parent = $current.closest('ul');
          var data = $current.data();
          _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params[data.type] = data.value;
          if (window.rvMedia.options) {
            window.rvMedia.options.view_in = data.value;
          }
          if (data.type === 'view_in') {
            _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params.folder_id = 0;
            if (data.value === 'trash') {
              $(document).find('.js-insert-to-editor').prop('disabled', true);
            } else {
              $(document).find('.js-insert-to-editor').prop('disabled', false);
            }
          }
          $current.closest('.dropdown').find('.js-rv-media-filter-current').html('(' + $current.html() + ')');
          _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.storeConfig();
          _App_Services_MediaService__WEBPACK_IMPORTED_MODULE_2__.MediaService.refreshFilter();
          _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
          _self.MediaService.getMedia(true);
          $parent.find('> li').removeClass('active');
          $current.closest('li').addClass('active');
        }
      });
    }
  }, {
    key: "search",
    value: function search() {
      var _self = this;
      $('.input-search-wrapper input[type="text"]').val(_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getRequestParams().search || '');
      _self.$body.off('submit', '.input-search-wrapper').on('submit', '.input-search-wrapper', function (event) {
        event.preventDefault();
        _App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params.search = $(event.currentTarget).find('input[type="text"]').val();
        _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.storeConfig();
        _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
        _self.MediaService.getMedia(true);
      });
    }
  }, {
    key: "handleActions",
    value: function handleActions() {
      var _self = this;
      _self.$body.off('click', '.rv-media-actions .js-change-action[data-type="refresh"]').on('click', '.rv-media-actions .js-change-action[data-type="refresh"]', function (event) {
        event.preventDefault();
        _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
        var ele_options = typeof window.rvMedia.$el !== 'undefined' ? window.rvMedia.$el.data('rv-media') : undefined;
        if (typeof ele_options !== 'undefined' && ele_options.length > 0 && typeof ele_options[0].selected_file_id !== 'undefined') {
          _self.MediaService.getMedia(true, true);
        } else {
          _self.MediaService.getMedia(true, false);
        }
      }).off('click', '.rv-media-items li.no-items').on('click', '.rv-media-items li.no-items', function (event) {
        event.preventDefault();
        $('.rv-media-header .rv-media-top-header .rv-media-actions .js-dropzone-upload').trigger('click');
      }).off('submit', '.form-add-folder').on('submit', '.form-add-folder', function (event) {
        event.preventDefault();
        var $input = $(event.currentTarget).find('input[type=text]');
        var folderName = $input.val();
        _self.FolderService.create(folderName);
        $input.val('');
        return false;
      }).off('click', '.js-change-folder').on('click', '.js-change-folder', function (event) {
        event.preventDefault();
        var folderId = $(event.currentTarget).data('folder');
        _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
        _self.FolderService.changeFolder(folderId);
      }).off('click', '.js-files-action').on('click', '.js-files-action', function (event) {
        event.preventDefault();
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.handleGlobalAction($(event.currentTarget).data('action'), function () {
          _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.resetPagination();
          _self.MediaService.getMedia(true);
        });
      }).off('submit', '.form-download-url').on('submit', '.form-download-url', /*#__PURE__*/function () {
        var _ref = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(event) {
          var $el, $wrapper, $notice, $header, $input, $button, url, remainUrls;
          return _regeneratorRuntime().wrap(function _callee$(_context) {
            while (1) switch (_context.prev = _context.next) {
              case 0:
                $el = $('#modal_download_url'), $wrapper = $el.find('#download-form-wrapper'), $notice = $el.find('#modal-notice').empty();
                event.preventDefault();
                $header = $el.find('.modal-title');
                $input = $el.find('textarea[name="urls"]').prop('disabled', true);
                $button = $el.find('[type="submit"]').addClass('button-loading').prop('disabled', true);
                url = $input.val();
                remainUrls = [];
                $wrapper.slideUp();

                // start to download
                _context.next = 10;
                return _self.DownloadService.download(url, function (progress, item, url) {
                  var $noticeItem = $("\n                        <div class=\"p-2 text-primary\">\n                            <i class=\"fa fa-info-circle\"></i>\n                            <span>".concat(item, "</span>\n                        </div>\n                    "));
                  $notice.append($noticeItem).scrollTop($notice[0].scrollHeight);
                  $header.html("<i class=\"fas fa-cloud-download-alt\"></i> ".concat($header.data('downloading'), " (").concat(progress, ")"));
                  return function (success) {
                    var message = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
                    if (!success) {
                      remainUrls.push(url);
                    }
                    $noticeItem.find('span').text("".concat(item, ": ").concat(message));
                    $noticeItem.attr('class', "p-2 text-".concat(success ? 'success' : 'danger')).find('i').attr('class', success ? 'fas fa-check-circle' : 'fas fa-times-circle');
                  };
                });
              case 10:
                $wrapper.slideDown();
                $input.val(remainUrls.join('\n')).prop('disabled', false);
                $header.html("<i class=\"fas fa-cloud-download-alt\"></i> ".concat($header.data('text')));
                $button.removeClass('button-loading').prop('disabled', false);
                return _context.abrupt("return", false);
              case 15:
              case "end":
                return _context.stop();
            }
          }, _callee);
        }));
        return function (_x) {
          return _ref.apply(this, arguments);
        };
      }());
    }
  }, {
    key: "handleModals",
    value: function handleModals() {
      var _self = this;

      /*Rename files*/
      _self.$body.on('show.bs.modal', '#modal_rename_items', function () {
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.renderRenameItems();
      });
      _self.$body.on('show.bs.modal', '#modal_alt_text_items', function () {
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.renderAltTextItems();
      });
      _self.$body.on('show.bs.modal', '#modal_crop_image', function () {
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.renderCropImage();
      });
      _self.$body.on('hidden.bs.modal', '#modal_download_url', function () {
        var $el = $('#modal_download_url');
        $el.find('textarea').val('');
        $el.find('#modal-notice').empty();
      });
      _self.$body.off('submit', '#modal_crop_image .form-crop').on('submit', '#modal_crop_image .form-crop', function (event) {
        event.preventDefault();
        var $form = $(event.currentTarget);
        var imageId = $form.find('input[name="image_id"]').val();
        var cropData = $form.find('input[name="crop_data"]').val();
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.processAction({
          action: $form.data('action'),
          imageId: imageId,
          cropData: cropData
        }, function (response) {
          if (!response.error) {
            $form.closest('.modal').modal('hide');
            _self.MediaService.getMedia(true);
          }
        });
      });
      _self.$body.off('submit', '#modal_rename_items .form-rename').on('submit', '#modal_rename_items .form-rename', function (event) {
        event.preventDefault();
        var items = [];
        var $form = $(event.currentTarget);
        $('#modal_rename_items .form-control').each(function (index, el) {
          var $current = $(el);
          var data = $current.closest('.form-group').data();
          data.name = $current.val();
          items.push(data);
        });
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.processAction({
          action: $form.data('action'),
          selected: items
        }, function (res) {
          if (!res.error) {
            $form.closest('.modal').modal('hide');
            _self.MediaService.getMedia(true);
          } else {
            $('#modal_rename_items .form-group').each(function (index, el) {
              var $current = $(el);
              if (_.includes(res.data, $current.data('id'))) {
                $current.addClass('has-error');
              } else {
                $current.removeClass('has-error');
              }
            });
          }
        });
      });
      _self.$body.off('submit', '#modal_alt_text_items .form-alt-text').on('submit', '#modal_alt_text_items .form-alt-text', function (event) {
        event.preventDefault();
        var items = [];
        var $form = $(event.currentTarget);
        $('#modal_alt_text_items .form-control').each(function (index, el) {
          var $current = $(el);
          var data = $current.closest('.form-group').data();
          data.alt = $current.val();
          items.push(data);
        });
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.processAction({
          action: $form.data('action'),
          selected: items
        }, function (res) {
          if (!res.error) {
            $form.closest('.modal').modal('hide');
            _self.MediaService.getMedia(true);
          } else {
            $('#modal_alt_text_items .form-group').each(function (index, el) {
              var $current = $(el);
              if (_.includes(res.data, $current.data('id'))) {
                $current.addClass('has-error');
              } else {
                $current.removeClass('has-error');
              }
            });
          }
        });
      });

      /*Delete files*/
      _self.$body.off('submit', '.form-delete-items').on('submit', '.form-delete-items', function (event) {
        event.preventDefault();
        var items = [];
        var $form = $(event.currentTarget);
        _.each(_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedItems(), function (value) {
          items.push({
            id: value.id,
            is_folder: value.is_folder
          });
        });
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.processAction({
          action: $form.data('action'),
          selected: items
        }, function (res) {
          $form.closest('.modal').modal('hide');
          if (!res.error) {
            _self.MediaService.getMedia(true);
          }
        });
      });

      /*Empty trash*/
      _self.$body.off('submit', '#modal_empty_trash .rv-form').on('submit', '#modal_empty_trash .rv-form', function (event) {
        event.preventDefault();
        var $form = $(event.currentTarget);
        _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.processAction({
          action: $form.data('action')
        }, function () {
          $form.closest('.modal').modal('hide');
          _self.MediaService.getMedia(true);
        });
      });
      if (_App_Config_MediaConfig__WEBPACK_IMPORTED_MODULE_0__.MediaConfig.request_params.view_in === 'trash') {
        $(document).find('.js-insert-to-editor').prop('disabled', true);
      } else {
        $(document).find('.js-insert-to-editor').prop('disabled', false);
      }
      this.bindIntegrateModalEvents();
    }
  }, {
    key: "checkFileTypeSelect",
    value: function checkFileTypeSelect(selectedFiles) {
      if (typeof window.rvMedia.$el !== 'undefined') {
        var firstItem = _.first(selectedFiles);
        var ele_options = window.rvMedia.$el.data('rv-media');
        if (typeof ele_options !== 'undefined' && typeof ele_options[0] !== 'undefined' && typeof ele_options[0].file_type !== 'undefined' && firstItem !== 'undefined' && firstItem.type !== 'undefined') {
          if (!ele_options[0].file_type.match(firstItem.type)) {
            return false;
          } else {
            if (typeof ele_options[0].ext_allowed !== 'undefined' && $.isArray(ele_options[0].ext_allowed)) {
              if ($.inArray(firstItem.mime_type, ele_options[0].ext_allowed) === -1) {
                return false;
              }
            }
          }
        }
      }
      return true;
    }
  }, {
    key: "bindIntegrateModalEvents",
    value: function bindIntegrateModalEvents() {
      var $mainModal = $('#rv_media_modal');
      var _self = this;
      $mainModal.off('click', '.js-insert-to-editor').on('click', '.js-insert-to-editor', function (event) {
        event.preventDefault();
        var selectedFiles = _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFiles();
        if (_.size(selectedFiles) > 0) {
          window.rvMedia.options.onSelectFiles(selectedFiles, window.rvMedia.$el);
          if (_self.checkFileTypeSelect(selectedFiles)) {
            $mainModal.find('.btn-close').trigger('click');
          }
        }
      });
      $mainModal.off('dblclick', '.js-media-list-title').on('dblclick', '.js-media-list-title', function (event) {
        event.preventDefault();
        if (_App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getConfigs().request_params.view_in !== 'trash') {
          var selectedFiles = _App_Helpers_Helpers__WEBPACK_IMPORTED_MODULE_1__.Helpers.getSelectedFiles();
          if (_.size(selectedFiles) > 0) {
            window.rvMedia.options.onSelectFiles(selectedFiles, window.rvMedia.$el);
            if (_self.checkFileTypeSelect(selectedFiles)) {
              $mainModal.find('.btn-close').trigger('click');
            }
          }
        } else {
          _App_Services_ActionsService__WEBPACK_IMPORTED_MODULE_5__.ActionsService.handlePreview();
        }
      });
    }
  }, {
    key: "scrollGetMore",
    value:
    // Scroll get more media
    function scrollGetMore() {
      var _self = this;
      $('.rv-media-main .rv-media-items').bind('DOMMouseScroll mousewheel', function (e) {
        if (e.originalEvent.detail > 0 || e.originalEvent.wheelDelta < 0) {
          var loadMore;
          if ($(e.currentTarget).closest('.media-modal').length > 0) {
            loadMore = $(e.currentTarget).scrollTop() + $(e.currentTarget).innerHeight() / 2 >= $(e.currentTarget)[0].scrollHeight - 450;
          } else {
            loadMore = $(e.currentTarget).scrollTop() + $(e.currentTarget).innerHeight() >= $(e.currentTarget)[0].scrollHeight - 150;
          }
          if (loadMore) {
            if (typeof RV_MEDIA_CONFIG.pagination != 'undefined' && RV_MEDIA_CONFIG.pagination.has_more) {
              _self.MediaService.getMedia(false, false, true);
            }
          }
        }
      });
    }
  }], [{
    key: "setupSecurity",
    value: function setupSecurity() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    }
  }]);
  return MediaManagement;
}();
$(document).ready(function () {
  window.rvMedia = window.rvMedia || {};
  MediaManagement.setupSecurity();
  new MediaManagement().init();
});
})();

/******/ })()
;