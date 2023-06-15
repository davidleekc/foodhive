/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************************/
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
  data: function data() {
    return {
      isLoading: true
    };
  },
  props: {
    data: {
      type: Array,
      "default": function _default() {
        return [];
      },
      required: true
    }
  },
  mounted: function mounted() {
    var _this = this;
    if (!this.data.length) {
      return;
    }
    var series = [];
    var colors = [];
    var labels = [];
    var total = 0;
    this.data.map(function (x) {
      total += parseFloat(x.value);
      labels.push(x.label);
      colors.push(x.color);
    });
    if (total == 0) {
      this.data.map(function () {
        series.push(100 / _this.data.length);
      });
    } else {
      this.data.map(function (x) {
        series.push(100 / total * parseFloat(x.value));
      });
    }
    new ApexCharts(this.$el.querySelector('.revenue-chart'), {
      series: series,
      colors: colors,
      chart: {
        height: '250',
        type: 'donut'
      },
      chartOptions: {
        labels: labels
      },
      plotOptions: {
        pie: {
          donut: {
            size: '71%',
            polygons: {
              strokeWidth: 0
            }
          },
          expandOnClick: true
        }
      },
      states: {
        hover: {
          filter: {
            type: 'darken',
            value: .9
          }
        }
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        show: false
      },
      tooltip: {
        enabled: false
      }
    }).render();
    if (jQuery && jQuery().tooltip) {
      $('[data-bs-toggle="tooltip"]').tooltip({
        placement: 'top',
        boundary: 'window'
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=script&lang=js& ***!
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      isLoading: true,
      earningSales: [],
      colors: ['#fcb800', '#80bc00'],
      chart: null,
      filtering: ''
    };
  },
  props: {
    url: {
      type: String,
      "default": null,
      required: true
    },
    format: {
      type: String,
      "default": 'dd/MM/yy',
      required: false
    },
    filters: {
      type: Array,
      "default": function _default() {
        return [];
      },
      required: false
    },
    filterDefault: {
      type: String,
      "default": '',
      required: false
    }
  },
  mounted: function mounted() {
    this.setFiltering();
    this.renderChart();
  },
  methods: {
    setFiltering: function setFiltering() {
      var f = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
      if (!f) {
        f = this.filterDefault;
      }
      if (this.filters.length) {
        var filter = this.filters.find(function (x) {
          return x.key == f;
        });
        if (filter) {
          this.filtering = filter.text;
        } else {
          this.filtering = f;
        }
      }
    },
    renderChart: function renderChart() {
      var _this = this;
      if (this.url) {
        axios.get(this.url).then(function (res) {
          if (res.data.error) {
            Botble.showError(res.data.message);
          } else {
            _this.earningSales = res.data.data.earningSales;
            _this.chart = new ApexCharts(_this.$el.querySelector('.sales-reports-chart'), {
              series: res.data.data.series,
              chart: {
                height: 350,
                type: 'area',
                toolbar: {
                  show: false
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                curve: 'smooth'
              },
              colors: res.data.data.colors,
              xaxis: {
                type: 'datetime',
                categories: res.data.data.dates
              },
              tooltip: {
                x: {
                  format: _this.format
                }
              },
              noData: {
                text: BotbleVariables.languages.tables.no_data
              }
            });
            _this.chart.render();
          }
        });
      }
    },
    clickFilter: function clickFilter(filter, event) {
      var _this2 = this;
      event.preventDefault();
      this.setFiltering('...');
      var context = this;
      axios.get(context.url, {
        params: {
          filter: filter
        }
      }).then(function (res) {
        if (res.data.error) {
          Botble.showError(res.data.message);
        } else {
          context.earningSales = res.data.data.earningSales;
          var options = {
            xaxis: {
              type: 'datetime',
              categories: res.data.data.dates
            },
            series: res.data.data.series
          };
          if (res.data.data.colors) {
            options.colors = res.data.data.colors;
          }
          _this2.chart.updateOptions(options);
        }
        _this2.setFiltering(filter);
      });
    }
  }
});

/***/ }),

/***/ "./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue":
/*!************************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _RevenueChart_vue_vue_type_template_id_54809eeb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RevenueChart.vue?vue&type=template&id=54809eeb& */ "./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=template&id=54809eeb&");
/* harmony import */ var _RevenueChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RevenueChart.vue?vue&type=script&lang=js& */ "./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _RevenueChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _RevenueChart_vue_vue_type_template_id_54809eeb___WEBPACK_IMPORTED_MODULE_0__.render,
  _RevenueChart_vue_vue_type_template_id_54809eeb___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue":
/*!*****************************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SalesReportsChart_vue_vue_type_template_id_33d4b412___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SalesReportsChart.vue?vue&type=template&id=33d4b412& */ "./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=template&id=33d4b412&");
/* harmony import */ var _SalesReportsChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SalesReportsChart.vue?vue&type=script&lang=js& */ "./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SalesReportsChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SalesReportsChart_vue_vue_type_template_id_33d4b412___WEBPACK_IMPORTED_MODULE_0__.render,
  _SalesReportsChart_vue_vue_type_template_id_33d4b412___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RevenueChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RevenueChart.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RevenueChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SalesReportsChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SalesReportsChart.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SalesReportsChart_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=template&id=54809eeb&":
/*!*******************************************************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=template&id=54809eeb& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RevenueChart_vue_vue_type_template_id_54809eeb___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   staticRenderFns: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RevenueChart_vue_vue_type_template_id_54809eeb___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RevenueChart_vue_vue_type_template_id_54809eeb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RevenueChart.vue?vue&type=template&id=54809eeb& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=template&id=54809eeb&");


/***/ }),

/***/ "./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=template&id=33d4b412&":
/*!************************************************************************************************************************!*\
  !*** ./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=template&id=33d4b412& ***!
  \************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SalesReportsChart_vue_vue_type_template_id_33d4b412___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   staticRenderFns: () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SalesReportsChart_vue_vue_type_template_id_33d4b412___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SalesReportsChart_vue_vue_type_template_id_33d4b412___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SalesReportsChart.vue?vue&type=template&id=33d4b412& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=template&id=33d4b412&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=template&id=54809eeb&":
/*!**********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue?vue&type=template&id=54809eeb& ***!
  \**********************************************************************************************************************************************************************************************************************************************************/
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
  return _vm._m(0)
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", [_c("div", { staticClass: "revenue-chart" })])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=template&id=33d4b412&":
/*!***************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue?vue&type=template&id=33d4b412& ***!
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
  return _c("div", [
    _vm.filters.length
      ? _c("div", { staticClass: "btn-group d-block text-end" }, [
          _c(
            "a",
            {
              staticClass: "btn btn-sm btn-secondary",
              attrs: {
                href: "javascript:;",
                "data-bs-toggle": "dropdown",
                "aria-expanded": "false",
              },
            },
            [
              _c("i", {
                staticClass: "fa fa-filter",
                attrs: { "aria-hidden": "true" },
              }),
              _vm._v(" "),
              _c("span", [_vm._v(_vm._s(_vm.filtering))]),
              _vm._v(" "),
              _c("i", { staticClass: "fa fa-angle-down " }),
            ]
          ),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "dropdown-menu float-end" },
            _vm._l(_vm.filters, function (filter) {
              return _c("li", { key: filter.key }, [
                _c(
                  "a",
                  {
                    attrs: { href: "#" },
                    on: {
                      click: function ($event) {
                        return _vm.clickFilter(filter.key, $event)
                      },
                    },
                  },
                  [
                    _vm._v(
                      "\n                    " +
                        _vm._s(filter.text) +
                        "\n                "
                    ),
                  ]
                ),
              ])
            }),
            0
          ),
        ])
      : _vm._e(),
    _vm._v(" "),
    _c("div", { staticClass: "sales-reports-chart" }),
    _vm._v(" "),
    _vm.earningSales.length
      ? _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col-12" }, [
            _c(
              "ul",
              _vm._l(_vm.earningSales, function (earningSale) {
                return _c("li", { key: earningSale.text }, [
                  _c("i", {
                    staticClass: "fas fa-circle",
                    style: { color: earningSale.color },
                  }),
                  _vm._v(" " + _vm._s(earningSale.text) + "\n                "),
                ])
              }),
              0
            ),
          ]),
        ])
      : _vm._e(),
    _vm._v(" "),
    _c("div", { staticClass: "loading" }),
  ])
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
  !*** ./platform/plugins/ecommerce/resources/assets/js/report.js ***!
  \******************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_SalesReportsChart__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/SalesReportsChart */ "./platform/plugins/ecommerce/resources/assets/js/components/SalesReportsChart.vue");
/* harmony import */ var _components_RevenueChart__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/RevenueChart */ "./platform/plugins/ecommerce/resources/assets/js/components/RevenueChart.vue");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }


vueApp.booting(function (vue) {
  vue.component('sales-reports-chart', _components_SalesReportsChart__WEBPACK_IMPORTED_MODULE_0__["default"]);
  vue.component('revenue-chart', _components_RevenueChart__WEBPACK_IMPORTED_MODULE_1__["default"]);
});
$(function () {
  var _ranges;
  if (!window.moment || !jQuery().daterangepicker) {
    return;
  }
  moment.locale($('html').attr('lang'));
  var $dateRange = $(document).find('.date-range-picker');
  var dateFormat = $dateRange.data('format') || 'YYYY-MM-DD';
  var startDate = $dateRange.data('start-date') || moment().subtract(29, 'days');
  var today = moment();
  var endDate = moment().endOf('month');
  if (endDate > today) {
    endDate = today;
  }
  var rangesTrans = BotbleVariables.languages.reports;
  var ranges = (_ranges = {}, _defineProperty(_ranges, rangesTrans.today, [today, today]), _defineProperty(_ranges, rangesTrans.this_week, [moment().startOf('week'), today]), _defineProperty(_ranges, rangesTrans.last_7_days, [moment().subtract(6, 'days'), today]), _defineProperty(_ranges, rangesTrans.last_30_days, [moment().subtract(29, 'days'), today]), _defineProperty(_ranges, rangesTrans.this_month, [moment().startOf('month'), endDate]), _defineProperty(_ranges, rangesTrans.this_year, [moment().startOf('year'), moment().endOf('year')]), _ranges);
  $dateRange.daterangepicker({
    ranges: ranges,
    alwaysShowCalendars: true,
    startDate: startDate,
    endDate: endDate,
    maxDate: endDate,
    opens: 'left',
    drops: 'auto',
    locale: {
      format: dateFormat
    },
    autoUpdateInput: false
  }, function (start, end, label) {
    $.ajax({
      url: $dateRange.data('href'),
      data: {
        date_from: start.format('YYYY-MM-DD'),
        date_to: end.format('YYYY-MM-DD'),
        predefined_range: label
      },
      type: 'GET',
      success: function success(data) {
        if (data.error) {
          Botble.showError(data.message);
        } else {
          $('.widget-item').each(function (key, widget) {
            var widgetEl = $(widget).prop('id');
            $("#".concat(widgetEl)).replaceWith($(data.data).find("#".concat(widgetEl)));
          });
          if ($('.report-chart-content').length) {
            $('.report-chart-content').html(data.data.html);
            window.vueApp.vue.component('sales-reports-chart', _components_SalesReportsChart__WEBPACK_IMPORTED_MODULE_0__["default"]);
            new window.vueApp.vue({
              el: '#report-chart'
            });
          }
          if (window.LaravelDataTables) {
            Object.keys(window.LaravelDataTables).map(function (key) {
              var table = window.LaravelDataTables[key];
              var url = new URL(table.ajax.url());
              url.searchParams.set('date_from', start.format('YYYY-MM-DD'));
              url.searchParams.set('date_to', end.format('YYYY-MM-DD'));
              table.ajax.url(url.href).load();
            });
          }
        }
      },
      error: function error(data) {
        Botble.handleError(data);
      }
    });
  });
  $dateRange.on('apply.daterangepicker', function (ev, picker) {
    var $this = $(this);
    var formatValue = $this.data('format-value');
    if (!formatValue) {
      formatValue = '__from__ - __to__';
    }
    var value = formatValue.replace('__from__', picker.startDate.format(dateFormat)).replace('__to__', picker.endDate.format(dateFormat));
    $this.find('span').text(value);
  });
});
})();

/******/ })()
;