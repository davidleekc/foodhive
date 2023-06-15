/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    checkUpdateUrl: {
      type: String,
      "default": function _default() {
        return null;
      },
      required: true
    },
    settingUrl: {
      type: String,
      "default": function _default() {
        return null;
      },
      required: true
    }
  },
  data: function data() {
    return {
      hasNewVersion: false,
      message: null
    };
  },
  mounted: function mounted() {
    this.checkUpdate();
  },
  methods: {
    checkUpdate: function checkUpdate() {
      var _this = this;
      axios.get(this.checkUpdateUrl).then(function (res) {
        if (!res.data.error && res.data.data.has_new_version) {
          _this.hasNewVersion = true;
          _this.message = res.data.message;
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    verifyUrl: {
      type: String,
      "default": function _default() {
        return null;
      },
      required: true
    },
    settingUrl: {
      type: String,
      "default": function _default() {
        return null;
      },
      required: true
    }
  },
  data: function data() {
    return {
      verified: true
    };
  },
  mounted: function mounted() {
    this.verifyLicense();
  },
  methods: {
    verifyLicense: function verifyLicense() {
      var _this = this;
      axios.get(this.verifyUrl).then(function (res) {
        if (res.data.error) {
          _this.verified = false;
        }
      });
    }
  }
});

/***/ }),

/***/ "./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue":
/*!*****************************************************************************************!*\
  !*** ./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CheckUpdateComponent_vue_vue_type_template_id_0f929218___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CheckUpdateComponent.vue?vue&type=template&id=0f929218& */ "./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=template&id=0f929218&");
/* harmony import */ var _CheckUpdateComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CheckUpdateComponent.vue?vue&type=script&lang=js& */ "./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CheckUpdateComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CheckUpdateComponent_vue_vue_type_template_id_0f929218___WEBPACK_IMPORTED_MODULE_0__.render,
  _CheckUpdateComponent_vue_vue_type_template_id_0f929218___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue":
/*!*******************************************************************************************!*\
  !*** ./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _VerifyLicenseComponent_vue_vue_type_template_id_19926a86___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./VerifyLicenseComponent.vue?vue&type=template&id=19926a86& */ "./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=template&id=19926a86&");
/* harmony import */ var _VerifyLicenseComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./VerifyLicenseComponent.vue?vue&type=script&lang=js& */ "./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _VerifyLicenseComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _VerifyLicenseComponent_vue_vue_type_template_id_19926a86___WEBPACK_IMPORTED_MODULE_0__.render,
  _VerifyLicenseComponent_vue_vue_type_template_id_19926a86___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************!*\
  !*** ./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CheckUpdateComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CheckUpdateComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CheckUpdateComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************!*\
  !*** ./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_VerifyLicenseComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./VerifyLicenseComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_VerifyLicenseComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=template&id=0f929218&":
/*!************************************************************************************************************************!*\
  !*** ./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=template&id=0f929218& ***!
  \************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CheckUpdateComponent_vue_vue_type_template_id_0f929218___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   staticRenderFns: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CheckUpdateComponent_vue_vue_type_template_id_0f929218___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CheckUpdateComponent_vue_vue_type_template_id_0f929218___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CheckUpdateComponent.vue?vue&type=template&id=0f929218& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=template&id=0f929218&");


/***/ }),

/***/ "./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=template&id=19926a86&":
/*!**************************************************************************************************************************!*\
  !*** ./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=template&id=19926a86& ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_VerifyLicenseComponent_vue_vue_type_template_id_19926a86___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   staticRenderFns: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_VerifyLicenseComponent_vue_vue_type_template_id_19926a86___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_VerifyLicenseComponent_vue_vue_type_template_id_19926a86___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./VerifyLicenseComponent.vue?vue&type=template&id=19926a86& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=template&id=19926a86&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=template&id=0f929218&":
/*!***************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue?vue&type=template&id=0f929218& ***!
  \***************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render),
/* harmony export */   staticRenderFns: () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.hasNewVersion
    ? _c("div", { staticClass: "note note-warning" }, [
        _c("p", [
          _vm._v(_vm._s(_vm.message) + ", please go to "),
          _c("a", { attrs: { href: _vm.settingUrl } }, [
            _vm._v("System Updater"),
          ]),
          _vm._v(" to upgrade to the latest version!"),
        ]),
      ])
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=template&id=19926a86&":
/*!*****************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue?vue&type=template&id=19926a86& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render),
/* harmony export */   staticRenderFns: () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return !_vm.verified
    ? _c("div", { staticClass: "note note-warning" }, [
        _c("p", [
          _vm._v(
            "Your license is invalid, please contact support. If you didn't setup license code, please go to "
          ),
          _c("a", { attrs: { href: _vm.settingUrl } }, [_vm._v("Settings")]),
          _vm._v(" to activate license!"),
        ]),
      ])
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


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
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
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
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!******************************************************************!*\
  !*** ./platform/core/dashboard/resources/assets/js/dashboard.js ***!
  \******************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_VerifyLicenseComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/VerifyLicenseComponent.vue */ "./platform/core/dashboard/resources/assets/js/components/VerifyLicenseComponent.vue");
/* harmony import */ var _components_CheckUpdateComponent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/CheckUpdateComponent.vue */ "./platform/core/dashboard/resources/assets/js/components/CheckUpdateComponent.vue");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }


if (typeof vueApp !== 'undefined') {
  vueApp.booting(function (vue) {
    vue.component('verify-license-component', _components_VerifyLicenseComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"]);
    vue.component('check-update-component', _components_CheckUpdateComponent_vue__WEBPACK_IMPORTED_MODULE_1__["default"]);
  });
}
var callbackWidgets = {};
var BDashboard = /*#__PURE__*/function () {
  function BDashboard() {
    _classCallCheck(this, BDashboard);
  }
  _createClass(BDashboard, [{
    key: "init",
    value: function init() {
      var list_widgets = $('#list_widgets');
      $(document).on('click', '.portlet > .portlet-title .tools > a.remove', function (event) {
        event.preventDefault();
        $('#hide-widget-confirm-bttn').data('id', $(event.currentTarget).closest('.widget_item').prop('id'));
        $('#hide_widget_modal').modal('show');
      });
      list_widgets.on('click', '.page_next, .page_previous', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        var href = $this.prop('href');
        if (href) {
          BDashboard.loadWidget($this.closest('.portlet').find('.portlet-body'), href);
        }
      });
      list_widgets.on('change', '.number_record .numb', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        var numb = $this.closest('.number_record').find('.numb');
        var paginate = numb.val();
        if (!isNaN(paginate) && paginate > 0) {
          BDashboard.loadWidget($this.closest('.portlet').find('.portlet-body'), $this.closest('.widget_item').attr('data-url'), {
            paginate: paginate
          });
        } else {
          Botble.showError('Please input a number!');
        }
      });
      list_widgets.on('click', '.btn_change_paginate', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        var numb = $this.closest('.number_record').find('.numb');
        var min = parseInt(numb.prop('min') || 5);
        var max = parseInt(numb.prop('max') || 100);
        var step = parseInt(numb.prop('step') || 5);
        var paginate = parseInt(numb.val());
        if ($this.hasClass('btn_up')) {
          if (paginate < max) {
            paginate += step;
          }
        } else if ($this.hasClass('btn_down')) {
          if (paginate - step > 0) {
            paginate -= step;
          } else {
            paginate = step;
          }
          if (paginate < min) {
            paginate = min;
          }
        }
        if (paginate != parseInt(numb.val())) {
          numb.val(paginate).trigger('change');
        }
      });
      $('#hide-widget-confirm-bttn').on('click', function (event) {
        event.preventDefault();
        var name = $(event.currentTarget).data('id');
        $.ajax({
          type: 'GET',
          cache: false,
          url: route('dashboard.hide_widget', {
            name: name
          }),
          success: function success(res) {
            if (!res.error) {
              $('#' + name).fadeOut();
              Botble.showSuccess(res.message);
            } else {
              Botble.showError(res.message);
            }
            $('#hide_widget_modal').modal('hide');
            var portlet = $(event.currentTarget).closest('.portlet');
            if ($(document).hasClass('page-portlet-fullscreen')) {
              $(document).removeClass('page-portlet-fullscreen');
            }
            portlet.find('[data-bs-toggle=tooltip]').tooltip('destroy');
            portlet.remove();
          },
          error: function error(data) {
            Botble.handleError(data);
          }
        });
      });
      $(document).on('click', '.portlet:not(.widget-load-has-callback) > .portlet-title .tools > a.reload', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        BDashboard.loadWidget($this.closest('.portlet').find('.portlet-body'), $this.closest('.widget_item').attr('data-url'));
      });
      $(document).on('click', '.portlet > .portlet-title .tools > .collapse, .portlet .portlet-title .tools > .expand', function (event) {
        event.preventDefault();
        var _self = $(event.currentTarget);
        var $portlet = _self.closest('.portlet');
        var state = $.trim(_self.data('state'));
        if (state === 'expand') {
          $portlet.find('.portlet-body').removeClass('collapse').addClass('expand');
          BDashboard.loadWidget($portlet.find('.portlet-body'), _self.closest('.widget_item').attr('data-url'));
        } else {
          $portlet.find('.portlet-body').removeClass('expand').addClass('collapse');
        }
        $.ajax({
          type: 'POST',
          cache: false,
          url: route('dashboard.edit_widget_setting_item'),
          data: {
            name: _self.closest('.widget_item').prop('id'),
            setting_name: 'state',
            setting_value: state
          },
          success: function success() {
            if (state === 'collapse') {
              _self.data('state', 'expand');
              $portlet.find('.predefined-ranges').addClass('d-none');
              $portlet.find('a.reload').addClass('d-none');
              $portlet.find('a.fullscreen').addClass('d-none');
            } else {
              _self.data('state', 'collapse');
              $portlet.find('.predefined-ranges').removeClass('d-none');
              $portlet.find('a.reload').removeClass('d-none');
              $portlet.find('a.fullscreen').removeClass('d-none');
            }
          },
          error: function error(data) {
            Botble.handleError(data);
          }
        });
      });
      $(document).on('change', '.portlet select[name=predefined_range]', function (e) {
        e.preventDefault();
        var $this = $(e.currentTarget);
        BDashboard.loadWidget($this.closest('.portlet').find('.portlet-body'), $this.closest('.widget_item').attr('data-url'), {
          changed_predefined_range: 1
        });
      });
      var $manageWidgetModal = $('#manage_widget_modal');
      $(document).on('click', '.manage-widget', function (event) {
        event.preventDefault();
        $manageWidgetModal.modal('show');
      });
      $manageWidgetModal.on('change', '.swc_wrap input', function (event) {
        $(event.currentTarget).closest('section').find('i').toggleClass('widget_none_color');
      });
    }
  }], [{
    key: "loadWidget",
    value: function loadWidget(el, url, data, callback) {
      var widgetItem = el.closest('.widget_item');
      var widgetId = widgetItem.attr('id');
      if (typeof callback !== 'undefined') {
        callbackWidgets[widgetId] = callback;
      }
      var $collapseExpand = widgetItem.find('a.collapse-expand');
      if ($collapseExpand.length && $collapseExpand.hasClass('collapse')) {
        return;
      }
      Botble.blockUI({
        target: el,
        iconOnly: true,
        overlayColor: 'none'
      });
      if (typeof data === 'undefined' || data == null) {
        data = {};
      }
      var predefinedRange = widgetItem.find('select[name=predefined_range]');
      if (predefinedRange.length) {
        data.predefined_range = predefinedRange.val();
      }
      $.ajax({
        type: 'GET',
        cache: false,
        url: url,
        data: data,
        success: function success(res) {
          Botble.unblockUI(el);
          if (!res.error) {
            el.html(res.data);
            if (typeof callback !== 'undefined') {
              callback();
            } else if (callbackWidgets[widgetId]) {
              callbackWidgets[widgetId]();
            }
            if (el.find('.scroller').length !== 0) {
              Botble.callScroll(el.find('.scroller'));
            }
            $('.equal-height').equalHeights();
            BDashboard.initSortable();
          } else {
            el.html('<div class="dashboard_widget_msg col-12"><p>' + res.message + '</p>');
          }
        },
        error: function error(res) {
          Botble.unblockUI(el);
          Botble.handleError(res);
        }
      });
    }
  }, {
    key: "initSortable",
    value: function initSortable() {
      if ($('#list_widgets').length > 0) {
        var el = document.getElementById('list_widgets');
        Sortable.create(el, {
          group: 'widgets',
          // or { name: "...", pull: [true, false, clone], put: [true, false, array] }
          sort: true,
          // sorting inside list
          delay: 0,
          // time in milliseconds to define when the sorting should start
          disabled: false,
          // Disables the sortable if set to true.
          store: null,
          // @see Store
          animation: 150,
          // ms, animation speed moving items when sorting, `0` — without animation
          handle: '.portlet-title',
          ghostClass: 'sortable-ghost',
          // Class name for the drop placeholder
          chosenClass: 'sortable-chosen',
          // Class name for the chosen item
          dataIdAttr: 'data-id',
          forceFallback: false,
          // ignore the HTML5 DnD behaviour and force the fallback to kick in
          fallbackClass: 'sortable-fallback',
          // Class name for the cloned DOM Element when using forceFallback
          fallbackOnBody: false,
          // Appends the cloned DOM Element into the Document's Body

          scroll: true,
          // or HTMLElement
          scrollSensitivity: 30,
          // px, how near the mouse must be to an edge to start scrolling.
          scrollSpeed: 10,
          // px

          // Changed sorting within list
          onUpdate: function onUpdate() {
            var items = [];
            $.each($('.widget_item'), function (index, widget) {
              items.push($(widget).prop('id'));
            });
            $.ajax({
              type: 'POST',
              cache: false,
              url: route('dashboard.update_widget_order'),
              data: {
                items: items
              },
              success: function success(res) {
                if (!res.error) {
                  Botble.showSuccess(res.message);
                } else {
                  Botble.showError(res.message);
                }
              },
              error: function error(data) {
                Botble.handleError(data);
              }
            });
          }
        });
      }
    }
  }]);
  return BDashboard;
}();
$(document).ready(function () {
  new BDashboard().init();
  window.BDashboard = BDashboard;
});
})();

/******/ })()
;