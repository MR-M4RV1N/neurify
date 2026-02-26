(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$"
/*!****************************************************************************************************************!*\
  !*** ./assets/controllers/ sync ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \.[jt]sx?$ ***!
  \****************************************************************************************************************/
(module, __unused_webpack_exports, __webpack_require__) {

var map = {
	"./clipboard_controller.js": "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/clipboard_controller.js",
	"./hello_controller.js": "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/hello_controller.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$";

/***/ },

/***/ "./node_modules/@symfony/stimulus-bridge/dist/webpack/loader.js!./assets/controllers.json"
/*!************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/dist/webpack/loader.js!./assets/controllers.json ***!
  \************************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _symfony_ux_turbo_dist_turbo_controller_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @symfony/ux-turbo/dist/turbo_controller.js */ "./vendor/symfony/ux-turbo/assets/dist/turbo_controller.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  'symfony--ux-turbo--turbo-core': _symfony_ux_turbo_dist_turbo_controller_js__WEBPACK_IMPORTED_MODULE_0__["default"],
});

/***/ },

/***/ "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/clipboard_controller.js"
/*!**********************************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/clipboard_controller.js ***!
  \**********************************************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _default)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(t, e) { if (e && ("object" == _typeof(e) || "function" == typeof e)) return e; if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined"); return _assertThisInitialized(t); }
function _assertThisInitialized(e) { if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); return e; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _getPrototypeOf(t) { return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) { return t.__proto__ || Object.getPrototypeOf(t); }, _getPrototypeOf(t); }
function _inherits(t, e) { if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function"); t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), Object.defineProperty(t, "prototype", { writable: !1 }), e && _setPrototypeOf(t, e); }
function _setPrototypeOf(t, e) { return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) { return t.__proto__ = e, t; }, _setPrototypeOf(t, e); }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }

var _default = /*#__PURE__*/function (_Controller) {
  function _default() {
    _classCallCheck(this, _default);
    return _callSuper(this, _default, arguments);
  }
  _inherits(_default, _Controller);
  return _createClass(_default, [{
    key: "copy",
    value: function copy() {
      var text = (this.sourceTarget.innerText || this.sourceTarget.textContent || '').trim();
      var self = this;
      function ok() {
        self.showFeedback('Copied!');
      }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(ok)["catch"](function () {
          self.fallbackCopy(text, ok);
        });
      } else {
        this.fallbackCopy(text, ok);
      }
    }
  }, {
    key: "fallbackCopy",
    value: function fallbackCopy(text, done) {
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.setAttribute('readonly', '');
      ta.style.position = 'absolute';
      ta.style.left = '-9999px';
      document.body.appendChild(ta);
      ta.select();
      try {
        document.execCommand('copy');
      } catch (e) {/* ignore */}
      document.body.removeChild(ta);
      if (typeof done === 'function') done();
    }
  }, {
    key: "showFeedback",
    value: function showFeedback(message) {
      if (this.hasFeedbackTarget) {
        this.feedbackTarget.textContent = message;
        this.feedbackTarget.classList.remove('d-none');
        var self = this;
        setTimeout(function () {
          self.feedbackTarget.classList.add('d-none');
        }, 1500);
        return;
      }
      if (this.hasButtonTarget) {
        var original = this.buttonTarget.innerHTML;
        this.buttonTarget.innerHTML = '<i class="far fa-check-circle" aria-hidden="true"></i> ' + message;
        var self = this;
        setTimeout(function () {
          self.buttonTarget.innerHTML = original;
        }, 1500);
      }
    }
  }]);
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);
_defineProperty(_default, "targets", ['source', 'button', 'feedback']);


/***/ },

/***/ "./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/hello_controller.js"
/*!******************************************************************************************************************!*\
  !*** ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js!./assets/controllers/hello_controller.js ***!
  \******************************************************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _default)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(t, e) { if (e && ("object" == _typeof(e) || "function" == typeof e)) return e; if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined"); return _assertThisInitialized(t); }
function _assertThisInitialized(e) { if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); return e; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _getPrototypeOf(t) { return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) { return t.__proto__ || Object.getPrototypeOf(t); }, _getPrototypeOf(t); }
function _inherits(t, e) { if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function"); t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), Object.defineProperty(t, "prototype", { writable: !1 }), e && _setPrototypeOf(t, e); }
function _setPrototypeOf(t, e) { return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) { return t.__proto__ = e, t; }, _setPrototypeOf(t, e); }


/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
var _default = /*#__PURE__*/function (_Controller) {
  function _default() {
    _classCallCheck(this, _default);
    return _callSuper(this, _default, arguments);
  }
  _inherits(_default, _Controller);
  return _createClass(_default, [{
    key: "connect",
    value: function connect() {
      this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }
  }]);
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);


/***/ },

/***/ "./assets/app.js"
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAll: () => (/* binding */ initAll)
/* harmony export */ });
/* harmony import */ var _bootstrap__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./bootstrap */ "./assets/bootstrap.js");
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");
/* harmony import */ var _hotwired_turbo__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @hotwired/turbo */ "./node_modules/@hotwired/turbo/dist/turbo.es2017-esm.js");
/* harmony import */ var _khmyznikov_pwa_install__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @khmyznikov/pwa-install */ "./node_modules/@khmyznikov/pwa-install/dist/pwa-install.es.js");
/* harmony import */ var _js_init_account_links__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./js/init/account-links */ "./assets/js/init/account-links.js");
/* harmony import */ var _js_init_info_box__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./js/init/info-box */ "./assets/js/init/info-box.js");
/* harmony import */ var _js_init_bottom_add_menu__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./js/init/bottom-add-menu */ "./assets/js/init/bottom-add-menu.js");
/* harmony import */ var _js_init_matrix_show_item__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./js/init/matrix-show-item */ "./assets/js/init/matrix-show-item.js");
/* harmony import */ var _js_init_ckeditor_init__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./js/init/ckeditor-init */ "./assets/js/init/ckeditor-init.js");
/* harmony import */ var _js_init_matrix_item_image_upload__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./js/init/matrix-item-image-upload */ "./assets/js/init/matrix-item-image-upload.js");
/* harmony import */ var _js_init_matrix_item_delete__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./js/init/matrix-item-delete */ "./assets/js/init/matrix-item-delete.js");
/* harmony import */ var _js_init_activity_chart__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./js/init/activity-chart */ "./assets/js/init/activity-chart.js");
/* harmony import */ var _js_init_small_steps_chart__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./js/init/small-steps-chart */ "./assets/js/init/small-steps-chart.js");
/* harmony import */ var _js_init_weekly_score_chart__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./js/init/weekly-score-chart */ "./assets/js/init/weekly-score-chart.js");
/* harmony import */ var _js_init_typewriter__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./js/init/typewriter */ "./assets/js/init/typewriter.js");
/* harmony import */ var _js_init_events_feed__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./js/init/events-feed */ "./assets/js/init/events-feed.js");
/* harmony import */ var _js_init_event_ensemble_profile_chat__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./js/init/event-ensemble-profile-chat */ "./assets/js/init/event-ensemble-profile-chat.js");
/* harmony import */ var _js_init_event_comments__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./js/init/event-comments */ "./assets/js/init/event-comments.js");
/* harmony import */ var _js_init_event_form__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./js/init/event-form */ "./assets/js/init/event-form.js");
/* harmony import */ var _js_init_simple_form__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./js/init/simple-form */ "./assets/js/init/simple-form.js");
/* harmony import */ var _js_init_picture_form__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./js/init/picture-form */ "./assets/js/init/picture-form.js");
/* harmony import */ var _js_init_event_chat_init__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./js/init/event-chat-init */ "./assets/js/init/event-chat-init.js");
/* harmony import */ var _js_init_profile_username_check__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./js/init/profile-username-check */ "./assets/js/init/profile-username-check.js");
/* harmony import */ var _js_init_presentation_swiper__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./js/init/presentation-swiper */ "./assets/js/init/presentation-swiper.js");
/* harmony import */ var _js_init_users_profile_share__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./js/init/users-profile-share */ "./assets/js/init/users-profile-share.js");
/* harmony import */ var _js_init_events_iteration_bookmark_toggle__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! ./js/init/events-iteration-bookmark-toggle */ "./assets/js/init/events-iteration-bookmark-toggle.js");
/* harmony import */ var _js_init_account_info_box__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__(/*! ./js/init/account-info-box */ "./assets/js/init/account-info-box.js");
/* harmony import */ var _js_init_intro_init__WEBPACK_IMPORTED_MODULE_27__ = __webpack_require__(/*! ./js/init/intro-init */ "./assets/js/init/intro-init.js");
/* harmony import */ var _js_init_add_lux_menu__WEBPACK_IMPORTED_MODULE_28__ = __webpack_require__(/*! ./js/init/add-lux-menu */ "./assets/js/init/add-lux-menu.js");
/* harmony import */ var _js_init_share_button__WEBPACK_IMPORTED_MODULE_29__ = __webpack_require__(/*! ./js/init/share-button */ "./assets/js/init/share-button.js");
/* harmony import */ var _js_init_init_save_button__WEBPACK_IMPORTED_MODULE_30__ = __webpack_require__(/*! ./js/init/init-save-button */ "./assets/js/init/init-save-button.js");
/* harmony import */ var _js_init_goal_percent_charts__WEBPACK_IMPORTED_MODULE_31__ = __webpack_require__(/*! ./js/init/goal-percent-charts */ "./assets/js/init/goal-percent-charts.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_32__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_32___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_32__);
/* harmony import */ var react_dom_client__WEBPACK_IMPORTED_MODULE_33__ = __webpack_require__(/*! react-dom/client */ "./node_modules/react-dom/client.js");
/* harmony import */ var _react_App__WEBPACK_IMPORTED_MODULE_34__ = __webpack_require__(/*! ./react/App */ "./assets/react/App.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_35__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");





// INIT IMPORTS





























// REACT




// =======================
// REACT INIT
// =======================

var rootEl = document.getElementById('react-matrix-root');
if (rootEl) {
  var root = (0,react_dom_client__WEBPACK_IMPORTED_MODULE_33__.createRoot)(rootEl);
  root.render(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_35__.jsx)(_react_App__WEBPACK_IMPORTED_MODULE_34__["default"], {}));
}

// =======================
// CHAT AI INTRO TYPING & CLEANUP
// =======================
document.addEventListener('turbo:before-cache', function () {
  // Очистка чата (ваша текущая логика)
  var outputEl = document.getElementById('output');
  if (outputEl) {
    outputEl.textContent = '';
    outputEl.classList.remove('is-typing');
    delete outputEl.dataset.initialized;
  }

  // --- ДОБАВИТЬ ЭТО ---
  // Очистка тестовой кнопки оверлея
  var testBtn = document.getElementById('ai-overlay-test-btn');
  if (testBtn) {
    delete testBtn.dataset.initialized;
  }

  // Очистка самого оверлея (на всякий случай скрываем его перед кэшированием)
  var overlay = document.getElementById('ai-loading-overlay');
  if (overlay) {
    overlay.classList.add('d-none');
  }
});
function initChatIntroTyping() {
  var outputEl = document.getElementById('output');
  if (!outputEl || outputEl.dataset.initialized) return;
  outputEl.dataset.initialized = 'true';
  outputEl.classList.add('is-typing'); // ← ВАЖНО

  (0,_js_init_typewriter__WEBPACK_IMPORTED_MODULE_14__.initTypewriter)({
    selector: '#output',
    text: outputEl.dataset.text,
    speed: 80
  });
}
document.addEventListener('turbo:submit-start', function (e) {
  if (e.target.id === 'chat-form') {
    var overlay = document.getElementById('ai-loading-overlay');
    if (overlay) overlay.classList.remove('d-none');
  }
});
document.addEventListener('turbo:submit-end', function () {
  var overlay = document.getElementById('ai-loading-overlay');
  if (overlay) overlay.classList.add('d-none');
});

// =======================
// AI LOADING OVERLAY
// =======================
document.addEventListener('turbo:submit-start', function (event) {
  // Проверяем, что отправляется именно наша форма анализа
  if (event.target.id === 'ai-analysis-form') {
    var overlay = document.getElementById('ai-loading-overlay');
    if (overlay) {
      overlay.classList.remove('d-none');
    }
  }
});
// (Опционально) Скрывать оверлей, если сервер вернул ошибку или отправка завершилась без перехода
document.addEventListener('turbo:submit-end', function (event) {
  if (event.target.id === 'ai-analysis-form') {
    // Если успешный переход, Turbo сам заменит body и оверлей исчезнет.
    // Но если будет ошибка валидации (422) или редирект не сработал,
    // нужно скрыть оверлей вручную, чтобы интерфейс не завис.
    var overlay = document.getElementById('ai-loading-overlay');
    // Проверяем успех (event.detail.formSubmission.result.success), но для простоты можно просто скрыть:
    if (overlay && !event.detail.formSubmission.result.success) {
      overlay.classList.add('d-none');
    }
  }
});
// =======================
// MAIN INIT
// =======================
function initAll() {
  (0,_js_init_account_links__WEBPACK_IMPORTED_MODULE_4__.initAccountLinks)();
  (0,_js_init_info_box__WEBPACK_IMPORTED_MODULE_5__.initInfoBox)();
  (0,_js_init_bottom_add_menu__WEBPACK_IMPORTED_MODULE_6__.initBottomAddMenuToggle)();
  (0,_js_init_matrix_show_item__WEBPACK_IMPORTED_MODULE_7__.initMatrixShowItem)();
  (0,_js_init_ckeditor_init__WEBPACK_IMPORTED_MODULE_8__.initCKEditor)();
  (0,_js_init_matrix_item_image_upload__WEBPACK_IMPORTED_MODULE_9__.initMatrixItemImageUpload)();
  (0,_js_init_matrix_item_delete__WEBPACK_IMPORTED_MODULE_10__.initMatrixItemDeleteModal)();
  (0,_js_init_event_comments__WEBPACK_IMPORTED_MODULE_17__.initComments)();
  (0,_js_init_event_chat_init__WEBPACK_IMPORTED_MODULE_21__.initChatModal)();
  (0,_js_init_profile_username_check__WEBPACK_IMPORTED_MODULE_22__.initUsernameAvailabilityCheck)();
  (0,_js_init_presentation_swiper__WEBPACK_IMPORTED_MODULE_23__.initPresentationSwiper)();
  (0,_js_init_users_profile_share__WEBPACK_IMPORTED_MODULE_24__.initProfileShare)();
  (0,_js_init_events_iteration_bookmark_toggle__WEBPACK_IMPORTED_MODULE_25__.initBookmarkToggle)();
  (0,_js_init_account_info_box__WEBPACK_IMPORTED_MODULE_26__.initAccountInfoBox)();
  (0,_js_init_intro_init__WEBPACK_IMPORTED_MODULE_27__.initIntro)();
  (0,_js_init_add_lux_menu__WEBPACK_IMPORTED_MODULE_28__.initAddLuxMenu)();
  (0,_js_init_share_button__WEBPACK_IMPORTED_MODULE_29__.initShareButton)();
  (0,_js_init_activity_chart__WEBPACK_IMPORTED_MODULE_11__.initActivityChart)();
  (0,_js_init_small_steps_chart__WEBPACK_IMPORTED_MODULE_12__.initSmallStepsChart)();
  (0,_js_init_weekly_score_chart__WEBPACK_IMPORTED_MODULE_13__.initWeeklyScoreChart)();
  (0,_js_init_goal_percent_charts__WEBPACK_IMPORTED_MODULE_31__.initGoalPercentCharts)();

  // ✅ AI INTRO (важно: ВНУТРИ initAll)
  initChatIntroTyping();
  var pictureForm = document.querySelector('form[data-form-type="picture"]');
  if (pictureForm) (0,_js_init_picture_form__WEBPACK_IMPORTED_MODULE_20__.initPictureForm)();
  var simpleForm = document.querySelector('form[data-form-type="simple"]');
  if (simpleForm) (0,_js_init_simple_form__WEBPACK_IMPORTED_MODULE_19__.initSimpleForm)();
  var journalForm = document.querySelector('form[data-form-type="journal"]');
  if (journalForm) (0,_js_init_simple_form__WEBPACK_IMPORTED_MODULE_19__.initSimpleForm)();
  var eventForm = document.querySelector('form[data-form-context="event"]');
  if (eventForm) (0,_js_init_event_form__WEBPACK_IMPORTED_MODULE_18__.initEventForm)();
  (0,_js_init_events_feed__WEBPACK_IMPORTED_MODULE_15__.initEventsFeed)();
  var chatButton = document.getElementById('chatButton');
  if (chatButton) {
    var profileId = chatButton.dataset.profileId;
    (0,_js_init_event_ensemble_profile_chat__WEBPACK_IMPORTED_MODULE_16__.initProfileChat)(profileId, "/cpanel/chat/check/".concat(profileId), "/cpanel/chat/new/".concat(profileId));
  }
  (0,_js_init_init_save_button__WEBPACK_IMPORTED_MODULE_30__.initSaveButtonCountdown)();
}

// =======================
// TURBO ENTRY POINT
// =======================
document.addEventListener('turbo:load', initAll);

/***/ },

/***/ "./assets/bootstrap.js"
/*!*****************************!*\
  !*** ./assets/bootstrap.js ***!
  \*****************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   app: () => (/* binding */ app)
/* harmony export */ });
/* harmony import */ var _symfony_stimulus_bridge__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @symfony/stimulus-bridge */ "./node_modules/@symfony/stimulus-bridge/dist/index.js");


// Registers Stimulus controllers from controllers.json and in the controllers/ directory
var app = (0,_symfony_stimulus_bridge__WEBPACK_IMPORTED_MODULE_0__.startStimulusApp)(__webpack_require__("./assets/controllers sync recursive ./node_modules/@symfony/stimulus-bridge/lazy-controller-loader.js! \\.[jt]sx?$"));

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);

/***/ },

/***/ "./assets/js/init/account-info-box.js"
/*!********************************************!*\
  !*** ./assets/js/init/account-info-box.js ***!
  \********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAccountInfoBox: () => (/* binding */ initAccountInfoBox)
/* harmony export */ });
function initAccountInfoBox() {
  function initScrollContainer() {
    var scrollContainer = document.getElementById('scrollContainer');
    if (scrollContainer) {
      scrollContainer.scrollTo({
        left: 60,
        behavior: 'auto' // ← мгновенно, без анимации
      });
    }
  }
  window.addEventListener('load', initScrollContainer);
  document.addEventListener('turbo:load', initScrollContainer);
}

/***/ },

/***/ "./assets/js/init/account-links.js"
/*!*****************************************!*\
  !*** ./assets/js/init/account-links.js ***!
  \*****************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAccountLinks: () => (/* binding */ initAccountLinks)
/* harmony export */ });
function initAccountLinks() {
  // Это всё нужно на app_account_list - Чтобы по категорям кликать
  var bookmarkLink = document.getElementById("bookmarkLink");
  if (bookmarkLink) {
    bookmarkLink.addEventListener("click", function () {
      window.location.href = bookmarkLink.dataset.url;
    });
  }
  var defaultLink = document.getElementById("defaultLink");
  if (defaultLink) {
    defaultLink.addEventListener("click", function () {
      window.location.href = defaultLink.dataset.url;
    });
  }
  var featuredLink = document.getElementById("featuredLink");
  if (featuredLink) {
    featuredLink.addEventListener("click", function () {
      window.location.href = featuredLink.dataset.url;
    });
  }
  document.querySelectorAll(".clickable").forEach(function (cell) {
    cell.addEventListener("click", function () {
      var url = cell.getAttribute("data-url");
      if (url) {
        window.location.href = url;
      }
    });
  });
}

/***/ },

/***/ "./assets/js/init/activity-chart.js"
/*!******************************************!*\
  !*** ./assets/js/init/activity-chart.js ***!
  \******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initActivityChart: () => (/* binding */ initActivityChart)
/* harmony export */ });
/* harmony import */ var chart_js_auto__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! chart.js/auto */ "./node_modules/chart.js/auto/auto.js");

var activityChart = null;
function initActivityChart() {
  var chartCanvas = document.getElementById('eventsChart');
  if (!chartCanvas) return;
  var profileId = chartCanvas.dataset.profileId;
  var year = chartCanvas.dataset.year;
  fetch("/cpanel/progress/activity/api/statistics/".concat(profileId, "?year=").concat(year)).then(function (response) {
    return response.json();
  }).then(function (data) {
    var eventLabels = data.events.map(function (item) {
      return item.month;
    });
    var eventValues = data.events.map(function (item) {
      return item.count;
    });

    // 🔥 КЛЮЧЕВОЕ МЕСТО
    if (activityChart) {
      activityChart.destroy();
      activityChart = null;
    }
    activityChart = new chart_js_auto__WEBPACK_IMPORTED_MODULE_0__["default"](chartCanvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: eventLabels,
        datasets: [{
          label: "\u041A\u043E\u043B\u0438\u0447\u0435\u0441\u0442\u0432\u043E \u0432\u044B\u043F\u043E\u043B\u043D\u0435\u043D\u043D\u044B\u0445 \u0437\u0430\u043F\u0438\u0441\u0435\u0439 (".concat(year, ")"),
          data: eventValues,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  });
}

/***/ },

/***/ "./assets/js/init/add-lux-menu.js"
/*!****************************************!*\
  !*** ./assets/js/init/add-lux-menu.js ***!
  \****************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAddLuxMenu: () => (/* binding */ initAddLuxMenu)
/* harmony export */ });
function initAddLuxMenu() {
  var toggleSelector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '#addMenuToggle';
  var overlaySelector = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '#addMenuOverlay';
  var toggleButton = document.querySelector(toggleSelector);
  var overlay = document.querySelector(overlaySelector);
  var closeBtn = overlay === null || overlay === void 0 ? void 0 : overlay.querySelector('#closeAddMenu');
  if (!toggleButton || !overlay) {
    console.warn("Add menu elements not found");
    return;
  }
  var newToggleButton = toggleButton.cloneNode(true);
  toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
  var isMenuOpen = !overlay.classList.contains('hidden');
  newToggleButton.addEventListener('click', function (e) {
    e.preventDefault();
    if (!isMenuOpen) {
      overlay.classList.remove('hidden');
      isMenuOpen = true;
    } else {
      overlay.classList.add('hidden');
      isMenuOpen = false;
    }
  });
  if (closeBtn) {
    closeBtn.addEventListener('click', function () {
      overlay.classList.add('hidden');
      isMenuOpen = false;
    });
  }
  document.addEventListener('click', function (e) {
    var clickedInside = overlay.contains(e.target) || newToggleButton.contains(e.target);
    if (!clickedInside && isMenuOpen) {
      overlay.classList.add('hidden');
      isMenuOpen = false;
    }
  });
}

/***/ },

/***/ "./assets/js/init/bottom-add-menu.js"
/*!*******************************************!*\
  !*** ./assets/js/init/bottom-add-menu.js ***!
  \*******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initBottomAddMenuToggle: () => (/* binding */ initBottomAddMenuToggle)
/* harmony export */ });
function initBottomAddMenuToggle() {
  var toggleButton = document.getElementById('addMenuToggle');
  var menuWrapper = document.getElementById('addMenu');
  if (!toggleButton || !menuWrapper) return;
  var isMenuOpen = false;

  // Снимаем старые обработчики (в случае hot reload или turbo)
  var newToggleButton = toggleButton.cloneNode(true);
  toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
  newToggleButton.addEventListener('click', function (e) {
    e.preventDefault();
    if (!isMenuOpen) {
      menuWrapper.classList.remove('d-none');
      setTimeout(function () {
        menuWrapper.classList.add('active');
        isMenuOpen = true;
      }, 10);
    } else {
      menuWrapper.classList.remove('active');
      setTimeout(function () {
        menuWrapper.classList.add('d-none');
        isMenuOpen = false;
      }, 200);
    }
  });
  document.addEventListener('click', function (e) {
    var clickedInside = menuWrapper.contains(e.target) || newToggleButton.contains(e.target);
    if (!clickedInside && isMenuOpen) {
      menuWrapper.classList.remove('active');
      setTimeout(function () {
        menuWrapper.classList.add('d-none');
        isMenuOpen = false;
      }, 200);
    }
  });
}

/***/ },

/***/ "./assets/js/init/ckeditor-init.js"
/*!*****************************************!*\
  !*** ./assets/js/init/ckeditor-init.js ***!
  \*****************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initCKEditor: () => (/* binding */ initCKEditor)
/* harmony export */ });
function initCKEditor() {
  if (typeof CKEDITOR === 'undefined') {
    console.warn('CKEditor not loaded');
    return;
  }
  document.querySelectorAll('textarea[data-ckeditor]').forEach(function (textarea) {
    var name = textarea.getAttribute('name');

    // Не дублируем, если уже инициализирован
    if (!textarea.dataset.ckeditorInitialized && name) {
      // Проверим, не был ли CKEditor уже прикреплён к name
      if (CKEDITOR.instances[name]) {
        CKEDITOR.instances[name].destroy(true);
      }
      CKEDITOR.replace(textarea);
      textarea.dataset.ckeditorInitialized = 'true';
    }
  });
}

/***/ },

/***/ "./assets/js/init/event-chat-init.js"
/*!*******************************************!*\
  !*** ./assets/js/init/event-chat-init.js ***!
  \*******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initChatModal: () => (/* binding */ initChatModal)
/* harmony export */ });
function initChatModal() {
  var chatButton = document.getElementById('chatButton');
  var chatText = document.getElementById('chatText');
  var confirmModalEl = document.getElementById('confirmModal');
  var confirmButton = document.getElementById('confirmButton');
  if (!chatButton || !confirmButton || !confirmModalEl || !chatText) {
    console.warn('❌ Один из элементов чата не найден');
    return;
  }
  var confirmModal;
  try {
    confirmModal = new bootstrap.Modal(confirmModalEl, {});
  } catch (e) {
    console.error('Не удалось создать bootstrap.Modal:', e);
    return;
  }
  var checkUrl = chatButton.dataset.chatCheckUrl;
  var createUrl = chatButton.dataset.chatCreateUrl;
  chatButton.addEventListener('click', function () {
    chatText.textContent = 'Loading...';
    fetch(checkUrl).then(function (response) {
      return response.json();
    }).then(function (data) {
      if (data.result) {
        window.location.href = createUrl;
      } else {
        confirmModal.show();
        chatText.textContent = 'Send a message';
      }
    })["catch"](function (error) {
      console.error('Ошибка при проверке чата:', error);
      chatText.textContent = 'Send a message';
    });
  });
  confirmButton.addEventListener('click', function () {
    window.location.href = createUrl;
  });
}

/***/ },

/***/ "./assets/js/init/event-comments.js"
/*!******************************************!*\
  !*** ./assets/js/init/event-comments.js ***!
  \******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initComments: () => (/* binding */ initComments)
/* harmony export */ });
function initComments() {
  document.querySelectorAll('.btn-reply').forEach(function (button) {
    button.addEventListener('click', function () {
      var commentId = this.getAttribute('data-comment-id');
      var commentText = this.closest('.blog-comments__content').querySelector('p.comment-text').textContent.trim();
      var form = this.closest('.card-body').querySelector('.comment-form');
      var replyContainer = form.querySelector('.reply-container');
      var parentInput = form.querySelector('#parentCommentId');

      // Очистка предыдущих ответов
      replyContainer.innerHTML = '';
      parentInput.value = commentId;

      // Добавление видимого input с текстом родительского комментария
      var replyInput = document.createElement('input');
      replyInput.type = 'text';
      replyInput.name = 'replyToCommentText';
      replyInput.value = "\u041E\u0442\u0432\u0435\u0442 \u043D\u0430 \u043A\u043E\u043C\u043C\u0435\u043D\u0442\u0430\u0440\u0438\u0439: ".concat(commentText);
      replyInput.readOnly = true;
      replyInput.classList.add('form-control');
      replyContainer.appendChild(replyInput);
    });
  });
}

/***/ },

/***/ "./assets/js/init/event-ensemble-profile-chat.js"
/*!*******************************************************!*\
  !*** ./assets/js/init/event-ensemble-profile-chat.js ***!
  \*******************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initProfileChat: () => (/* binding */ initProfileChat)
/* harmony export */ });
function initProfileChat(profileId, chatCheckUrl, chatNewUrl) {
  var chatButton = document.getElementById('chatButton');
  var chatText = document.getElementById('chatText');
  var confirmModalEl = document.getElementById('confirmModal');
  var confirmButton = document.getElementById('confirmButton');
  if (!chatButton || !chatText || !confirmModalEl || !confirmButton) return;
  var confirmModal = new bootstrap.Modal(confirmModalEl);
  chatButton.addEventListener('click', function () {
    chatText.textContent = 'Loading...';
    fetch(chatCheckUrl).then(function (response) {
      return response.json();
    }).then(function (data) {
      if (data.result) {
        window.location.href = chatNewUrl;
      } else {
        confirmModal.show();
        chatText.textContent = 'Send a message';
      }
    })["catch"](function (error) {
      console.error('Chat check error:', error);
      chatText.textContent = 'Send a message';
    });
  });
  confirmButton.addEventListener('click', function () {
    window.location.href = chatNewUrl;
  });
}

/***/ },

/***/ "./assets/js/init/event-form.js"
/*!**************************************!*\
  !*** ./assets/js/init/event-form.js ***!
  \**************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initEventForm: () => (/* binding */ initEventForm)
/* harmony export */ });
/* harmony import */ var _init_image_upload__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./init-image-upload */ "./assets/js/init/init-image-upload.js");

function initEventForm() {
  (0,_init_image_upload__WEBPACK_IMPORTED_MODULE_0__.initImageUpload)({
    deleteUrlPrefix: '/cpanel/editor/events/images/',
    uploadUrl: '/cpanel/editor/events/dropzone'
  });
}

/***/ },

/***/ "./assets/js/init/events-feed.js"
/*!***************************************!*\
  !*** ./assets/js/init/events-feed.js ***!
  \***************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initEventsFeed: () => (/* binding */ initEventsFeed)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var listenersAttached = false;
function initEventsFeed() {
  // Инициализируем делегирование только один раз
  if (!listenersAttached) {
    initLikeButtonsDelegated();
    listenersAttached = true;
  }
  initBookmarkModal();
  initCommentToggles();
}

// --- Лайки (Делегирование) ---
function initLikeButtonsDelegated() {
  document.addEventListener("click", function (event) {
    var button = event.target.closest(".like-button");
    if (!button) return;

    // Предотвращаем всплытие, если нужно (обычно для кнопок не обязательно, но полезно)
    // event.preventDefault(); 

    var eventId = button.dataset.eventId;
    var csrfToken = button.dataset.csrfToken;
    var heartIcon = button.querySelector(".heart-icon");

    // Не отправляем запрос, если уже идет обработка (опционально, можно добавить состояние loading)
    if (button.classList.contains("is-loading")) return;
    button.classList.add("is-loading");
    fetch("/cpanel/like/".concat(eventId), {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        "X-CSRF-Token": csrfToken
      }
    }).then(function (res) {
      return res.json();
    }).then(function (data) {
      if (data.result) {
        heartIcon.classList.add("text-primary");
        heartIcon.classList.remove("text-muted");
      } else {
        heartIcon.classList.remove("text-primary");
        heartIcon.classList.add("text-muted");
      }
    })["catch"](function (error) {
      return console.error("Like error for event ".concat(eventId, ":"), error);
    })["finally"](function () {
      button.classList.remove("is-loading");
    });
  });
}

// --- Закладки + модалка ---
function initBookmarkModal() {
  var modalEl = document.getElementById("confirmModal");
  var confirmModal = modalEl ? new bootstrap.Modal(modalEl) : null;
  var confirmBtn = document.getElementById("confirmAccept");
  var titleEl = document.getElementById("confirmModalLabel");
  var bodyEl = modalEl === null || modalEl === void 0 ? void 0 : modalEl.querySelector(".modal-body");
  if (!confirmModal || !confirmBtn) return;
  document.querySelectorAll(".bookmark-toggle, .bookmark-remove").forEach(function (button) {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      var container = e.currentTarget.closest(".bookmark-button, .bookmark-remove");
      var eventId = container.dataset.eventId;
      var csrfToken = container.dataset.csrfToken;
      var isRemove = e.currentTarget.classList.contains("bookmark-remove");
      var isAccepted = container.classList.contains("accepted");
      titleEl.textContent = isRemove || isAccepted ? "Хотите отказаться от вызова?" : "Принятие вызова";
      bodyEl.textContent = isRemove || isAccepted ? "Вы действительно хотите отказаться от вызова?" : "Вы хотите принять вызов?";
      confirmBtn.dataset.eventId = eventId;
      confirmBtn.dataset.csrfToken = csrfToken;
      confirmBtn.dataset.isRemove = isRemove;
      confirmBtn.dataset.isAccepted = isAccepted;
      confirmModal.show();
    });
  });
  confirmBtn.addEventListener("click", /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
    var eventId, csrfToken, isRemove, isAccepted, url, method, response, container, icon, countSpan, currentCount;
    return _regeneratorRuntime().wrap(function _callee$(_context) {
      while (1) switch (_context.prev = _context.next) {
        case 0:
          eventId = confirmBtn.dataset.eventId;
          csrfToken = confirmBtn.dataset.csrfToken;
          isRemove = confirmBtn.dataset.isRemove === "true";
          isAccepted = confirmBtn.dataset.isAccepted === "true";
          url = isAccepted || isRemove ? "/cpanel/bookmark/remove/".concat(eventId) : "/cpanel/bookmark/add/".concat(eventId);
          method = isAccepted || isRemove ? "DELETE" : "POST";
          _context.prev = 6;
          _context.next = 9;
          return fetch(url, {
            method: method,
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-Token": csrfToken
            }
          });
        case 9:
          response = _context.sent;
          if (response.ok) {
            _context.next = 12;
            break;
          }
          throw new Error("HTTP ".concat(response.status));
        case 12:
          container = document.querySelector(".bookmark-button[data-event-id=\"".concat(eventId, "\"]"));
          icon = container === null || container === void 0 ? void 0 : container.querySelector(".bookmark-icon");
          countSpan = container === null || container === void 0 ? void 0 : container.querySelector(".bookmark-count");
          if (!isRemove) {
            _context.next = 18;
            break;
          }
          window.location.href = "/cpanel/bookmark/list";
          return _context.abrupt("return");
        case 18:
          if (icon && countSpan) {
            currentCount = parseInt(countSpan.textContent, 10) || 0;
            icon.classList.toggle("text-primary", !isAccepted);
            icon.classList.toggle("text-muted", isAccepted);
            container.classList.toggle("accepted", !isAccepted);
            countSpan.textContent = isAccepted ? Math.max(0, currentCount - 1) : currentCount + 1;
          }
          _context.next = 25;
          break;
        case 21:
          _context.prev = 21;
          _context.t0 = _context["catch"](6);
          console.error("Bookmark error:", _context.t0);
          alert("\u041E\u0448\u0438\u0431\u043A\u0430: ".concat(_context.t0.message));
        case 25:
          confirmModal.hide();
        case 26:
        case "end":
          return _context.stop();
      }
    }, _callee, null, [[6, 21]]);
  })));
}

// --- Комментарии (скролл к якорю) ---
function initCommentToggles() {
  document.querySelectorAll('.comment-toggle').forEach(function (button) {
    button.addEventListener('click', function (event) {
      var _button$closest;
      event.preventDefault();
      var path = (_button$closest = button.closest('.comment-button')) === null || _button$closest === void 0 ? void 0 : _button$closest.dataset.eventPath;
      if (path) {
        window.location.href = "".concat(path, "#target-element-id");
      }
    });
  });
}

/***/ },

/***/ "./assets/js/init/events-iteration-bookmark-toggle.js"
/*!************************************************************!*\
  !*** ./assets/js/init/events-iteration-bookmark-toggle.js ***!
  \************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initBookmarkToggle: () => (/* binding */ initBookmarkToggle)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function initBookmarkToggle() {
  var confirmModalEl = document.getElementById("confirmModal");
  var confirmButton = document.getElementById("confirmAccept");
  var modalTitle = document.getElementById("confirmModalLabel");
  var modalBody = document.querySelector("#confirmModal .modal-body");
  if (!confirmModalEl || !confirmButton || !modalTitle || !modalBody) {
    console.warn("❗ Элементы модального окна для закладок не найдены.");
    return;
  }
  var confirmModal = new bootstrap.Modal(confirmModalEl);
  function handleBookmarkClick(event) {
    event.preventDefault();
    var buttonContainer = event.currentTarget.closest('.bookmark-button, .bookmark-remove');
    var eventId = buttonContainer.dataset.eventId;
    var csrfToken = buttonContainer.dataset.csrfToken;
    var isRemoveButton = event.currentTarget.classList.contains("bookmark-remove");
    var isAccepted = buttonContainer.classList.contains("accepted");
    modalTitle.textContent = isRemoveButton || isAccepted ? "Хотите отказаться от вызова?" : "Принятие вызова";
    modalBody.textContent = isRemoveButton || isAccepted ? "Вы действительно хотите отказаться от вызова?" : "Вы хотите принять вызов?";
    confirmButton.dataset.eventId = eventId;
    confirmButton.dataset.csrfToken = csrfToken;
    confirmButton.dataset.isRemoveButton = isRemoveButton;
    confirmButton.dataset.isAccepted = isAccepted;
    confirmModal.show();
  }
  document.querySelectorAll('.bookmark-toggle, .bookmark-remove').forEach(function (button) {
    button.addEventListener('click', handleBookmarkClick);
  });
  confirmButton.addEventListener("click", /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
    var eventId, csrfToken, isRemoveButton, isAccepted, url, method, response, buttonContainer, icon, countSpan, currentCount;
    return _regeneratorRuntime().wrap(function _callee$(_context) {
      while (1) switch (_context.prev = _context.next) {
        case 0:
          eventId = this.dataset.eventId;
          csrfToken = this.dataset.csrfToken;
          isRemoveButton = this.dataset.isRemoveButton === "true";
          isAccepted = this.dataset.isAccepted === "true";
          url = isAccepted || isRemoveButton ? "/cpanel/bookmark/remove/".concat(eventId) : "/cpanel/bookmark/add/".concat(eventId);
          method = isAccepted || isRemoveButton ? 'DELETE' : 'POST';
          _context.prev = 6;
          _context.next = 9;
          return fetch(url, {
            method: method,
            headers: {
              'X-CSRF-Token': csrfToken,
              'Content-Type': 'application/json'
            }
          });
        case 9:
          response = _context.sent;
          if (response.ok) {
            _context.next = 12;
            break;
          }
          throw new Error("\u041E\u0448\u0438\u0431\u043A\u0430 ".concat(response.status, ": ").concat(response.statusText));
        case 12:
          if (!isRemoveButton) {
            _context.next = 15;
            break;
          }
          window.location.href = "/cpanel/bookmark/list"; // Можно и через `path()` вставить через Twig
          return _context.abrupt("return");
        case 15:
          buttonContainer = document.querySelector(".bookmark-button[data-event-id=\"".concat(eventId, "\"]"));
          icon = buttonContainer === null || buttonContainer === void 0 ? void 0 : buttonContainer.querySelector(".bookmark-icon");
          countSpan = buttonContainer === null || buttonContainer === void 0 ? void 0 : buttonContainer.querySelector(".bookmark-count");
          if (icon && countSpan) {
            currentCount = parseInt(countSpan.textContent, 10) || 0;
            icon.classList.toggle('text-primary', !isAccepted);
            icon.classList.toggle('text-muted', isAccepted);
            buttonContainer.classList.toggle("accepted", !isAccepted);
            countSpan.textContent = isAccepted ? Math.max(0, currentCount - 1) : currentCount + 1;
          }
          _context.next = 25;
          break;
        case 21:
          _context.prev = 21;
          _context.t0 = _context["catch"](6);
          console.error('Ошибка при изменении закладки:', _context.t0);
          alert("\u041F\u0440\u043E\u0438\u0437\u043E\u0448\u043B\u0430 \u043E\u0448\u0438\u0431\u043A\u0430: ".concat(_context.t0.message));
        case 25:
          confirmModal.hide();
        case 26:
        case "end":
          return _context.stop();
      }
    }, _callee, this, [[6, 21]]);
  })));
}

/***/ },

/***/ "./assets/js/init/goal-percent-charts.js"
/*!***********************************************!*\
  !*** ./assets/js/init/goal-percent-charts.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initGoalPercentCharts: () => (/* binding */ initGoalPercentCharts)
/* harmony export */ });
/* harmony import */ var chart_js_auto__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! chart.js/auto */ "./node_modules/chart.js/auto/auto.js");

function initGoalPercentCharts() {
  var canvases = document.querySelectorAll('.js-goal-percent-chart');
  if (!canvases.length) return;
  function getColorByPercent(percent) {
    if (percent < 30) return 'rgba(220, 53, 69, 1)'; // красный
    if (percent < 60) return 'rgba(255, 159, 64, 1)'; // оранжевый
    if (percent < 80) return 'rgba(255, 193, 7, 1)'; // жёлтый
    return 'rgba(40, 167, 69, 1)'; // зелёный
  }
  canvases.forEach(function (canvas) {
    var percent = parseInt(canvas.dataset.percent || '0', 10);
    var safe = Math.max(0, Math.min(100, percent));
    var mainColor = getColorByPercent(safe);

    // чтобы Turbo/повторный вызов не создавал график поверх
    if (canvas.__chart) {
      canvas.__chart.destroy();
      canvas.__chart = null;
    }
    canvas.__chart = new chart_js_auto__WEBPACK_IMPORTED_MODULE_0__["default"](canvas.getContext('2d'), {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [safe, 100 - safe],
          backgroundColor: [mainColor, 'rgba(220, 220, 220, 0.4)'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: false,
        maintainAspectRatio: false,
        animation: false,
        cutout: '70%',
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: false
          }
        }
      }
    });
  });
}

/***/ },

/***/ "./assets/js/init/info-box.js"
/*!************************************!*\
  !*** ./assets/js/init/info-box.js ***!
  \************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initInfoBox: () => (/* binding */ initInfoBox)
/* harmony export */ });
function initInfoBox() {
  // Это всё нужно на app_account_list - Блок для отображения количества записей над картинками
  var infoBox = document.getElementById("infoBox");
  if (!infoBox) return;

  // Заменим элемент на клон, чтобы очистить старые обработчики (если они вдруг есть)
  var newInfoBox = infoBox.cloneNode(true);
  infoBox.parentNode.replaceChild(newInfoBox, infoBox);
  var targetUrl = newInfoBox.dataset.url;
  if (targetUrl) {
    newInfoBox.addEventListener("click", function () {
      window.location.href = targetUrl;
    });
  }
}

/***/ },

/***/ "./assets/js/init/init-image-upload.js"
/*!*********************************************!*\
  !*** ./assets/js/init/init-image-upload.js ***!
  \*********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initImageUpload: () => (/* binding */ initImageUpload)
/* harmony export */ });
function initImageUpload(_ref) {
  var deleteUrlPrefix = _ref.deleteUrlPrefix,
    uploadUrl = _ref.uploadUrl;
  initImageDelete(deleteUrlPrefix);
  initDropzone(uploadUrl);
}
function initImageDelete(deleteUrlPrefix) {
  document.querySelectorAll('.devstyle-remove-image-btn').forEach(function (button) {
    if (button.dataset.bound === "true") return;
    button.dataset.bound = "true";
    button.addEventListener('click', function () {
      var _this = this;
      var imageId = this.dataset.id;
      if (confirm('Вы уверены, что хотите удалить изображение?')) {
        fetch("".concat(deleteUrlPrefix).concat(imageId, "/delete"), {
          method: 'DELETE',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        }).then(function (response) {
          if (response.ok) {
            _this.closest('.col-md-4').remove();
          } else {
            alert('Ошибка при удалении изображения');
          }
        })["catch"](function () {
          return alert('Ошибка при удалении изображения');
        });
      }
    });
  });
}
function initDropzone(uploadUrl) {
  if (typeof Dropzone === 'undefined') {
    console.warn('Dropzone не загружен');
    return;
  }
  Dropzone.autoDiscover = false;
  var dropzoneElement = document.getElementById("file-dropzone");
  if (dropzoneElement) {
    if (Dropzone.instances.length > 0) {
      Dropzone.instances.forEach(function (instance) {
        return instance.destroy();
      });
    }
    if (!dropzoneElement.dropzone) {
      new Dropzone(dropzoneElement, {
        url: uploadUrl,
        paramName: "images",
        maxFiles: 6,
        maxFilesize: 5,
        acceptedFiles: "image/*",
        addRemoveLinks: true,
        dictDefaultMessage: "Перетащите файлы сюда или кликните для загрузки",
        dictRemoveFile: "Удалить файл",
        dictMaxFilesExceeded: "Вы можете загрузить не более 6 файлов",
        init: function init() {
          this.on("success", function (file, response) {
            console.log("Файл загружен:", response);
          });
          this.on("error", function (file, errorMessage) {
            console.error("Ошибка загрузки:", errorMessage);
          });
        }
      });
    }
  }
}

/***/ },

/***/ "./assets/js/init/init-save-button.js"
/*!********************************************!*\
  !*** ./assets/js/init/init-save-button.js ***!
  \********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initSaveButtonCountdown: () => (/* binding */ initSaveButtonCountdown)
/* harmony export */ });
function initSaveButtonCountdown() {
  document.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form.matches('form[data-form-type="ai-generate"]')) return;
    var button = form.querySelector('#saveButton');
    if (!button) return;
    button.disabled = true;
    var counter = 20;
    var render = function render() {
      button.innerHTML = "\n                <span class=\"spinner-border spinner-border-sm mr-1\"\n                      role=\"status\"\n                      aria-hidden=\"true\"></span>\n                \u0413\u0435\u043D\u0435\u0440\u0430\u0446\u0438\u044F... (".concat(counter, ")\n            ");
    };
    render();
    var intervalId = setInterval(function () {
      counter--;
      render();
      if (counter <= 0) {
        clearInterval(intervalId);
      }
    }, 1000);
  });
}

/***/ },

/***/ "./assets/js/init/intro-init.js"
/*!**************************************!*\
  !*** ./assets/js/init/intro-init.js ***!
  \**************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initIntro: () => (/* binding */ initIntro)
/* harmony export */ });
/* harmony import */ var intro_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! intro.js */ "./node_modules/intro.js/intro.module.js");

function initIntro() {
  var introElement = document.getElementById('intro-data');
  if (!introElement) {
    return;
  }
  var userJustRegistered = introElement.dataset.userJustRegistered === 'true';
  if (!userJustRegistered) {
    return;
  }
  var intro = (0,intro_js__WEBPACK_IMPORTED_MODULE_0__["default"])();
  intro.setOptions({
    steps: [{
      element: '#neurify-gpt',
      intro: 'Тут нейросеть подскажет вам как пользоваться платформой.',
      position: 'bottom'
    }, {
      element: '#platform-challenges',
      intro: 'Тут вы узнаете что делать на платформе.',
      position: 'bottom'
    }, {
      element: '#week-challenge',
      intro: 'Тут каждую неделю выходят новые задания.',
      position: 'bottom'
    }, {
      element: '#mentors',
      intro: 'Тут наставники публикуют тематические задания.',
      position: 'bottom'
    }, {
      element: '#user-level',
      intro: 'Тут отображается ваш уровень.',
      position: 'bottom'
    }, {
      element: '#user-selected-artisan',
      intro: 'Тут вы можете создать портфолио.',
      position: 'bottom'
    }],
    showProgress: true,
    showBullets: true,
    nextLabel: 'Дальше',
    prevLabel: 'Назад',
    doneLabel: 'Готово',
    overlayOpacity: 0.5
  });
  intro.start();
}

/***/ },

/***/ "./assets/js/init/matrix-item-delete.js"
/*!**********************************************!*\
  !*** ./assets/js/init/matrix-item-delete.js ***!
  \**********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initMatrixItemDeleteModal: () => (/* binding */ initMatrixItemDeleteModal)
/* harmony export */ });
function initMatrixItemDeleteModal() {
  var buttons = document.querySelectorAll('.devstyle-delete-item-btn');
  buttons.forEach(function (button) {
    // чтобы не повесить несколько раз
    if (button.dataset.bound === "true") return;
    button.dataset.bound = "true";
    button.addEventListener('click', function () {
      var itemId = button.dataset.id;
      var modal = document.getElementById('itemDeletingModal');
      if (modal) {
        $(modal).modal('show');

        // Если у тебя есть input внутри модалки — тогда:
        var hiddenInput = document.getElementById('inputItemId');
        if (hiddenInput) {
          hiddenInput.value = itemId;
        }

        // ИЛИ если ты хочешь менять href прямо в ссылке:
        var deleteLink = modal.querySelector('.btn-danger');
        if (deleteLink) {
          deleteLink.setAttribute('href', "/cpanel/editor/item/delete/".concat(itemId));
        }
      }
    });
  });
}

/***/ },

/***/ "./assets/js/init/matrix-item-image-upload.js"
/*!****************************************************!*\
  !*** ./assets/js/init/matrix-item-image-upload.js ***!
  \****************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initMatrixItemImageUpload: () => (/* binding */ initMatrixItemImageUpload)
/* harmony export */ });
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
function initMatrixItemImageUpload() {
  var fileInput = document.getElementById('item-image-file');
  var previewImage = document.getElementById('blah');
  if (!fileInput || !previewImage) return;
  fileInput.addEventListener('change', function (evt) {
    var _fileInput$files = _slicedToArray(fileInput.files, 1),
      file = _fileInput$files[0];
    if (file) {
      previewImage.src = URL.createObjectURL(file);
    }
  });
}

/***/ },

/***/ "./assets/js/init/matrix-show-item.js"
/*!********************************************!*\
  !*** ./assets/js/init/matrix-show-item.js ***!
  \********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initMatrixShowItem: () => (/* binding */ initMatrixShowItem),
/* harmony export */   showItemModal: () => (/* binding */ showItemModal)
/* harmony export */ });
function initMatrixShowItem() {
  var table = document.querySelector('.devstyle-table');
  if (!table) return;
  table.addEventListener('click', function (e) {
    var cell = e.target.closest('.clickable-cell');
    if (!cell || !cell.dataset.id) return;
    var imageUrl = cell.dataset.imageUrl;
    var descriptionHtml = cell.dataset.description;
    var editLinkUrl = cell.dataset.editUrl;
    showItemModal({
      imageUrl: imageUrl,
      descriptionHtml: descriptionHtml,
      editLinkUrl: editLinkUrl
    });
  });
}
function decodeJsEscapedString(str) {
  try {
    return JSON.parse("\"".concat(str, "\""));
  } catch (e) {
    console.error("Ошибка декодирования строки:", str);
    return str;
  }
}
function showItemModal(_ref) {
  var imageUrl = _ref.imageUrl,
    descriptionHtml = _ref.descriptionHtml,
    editLinkUrl = _ref.editLinkUrl;
  var image = document.getElementById('image');
  var loader = document.getElementById('imageLoader');
  var description = document.getElementById('divDescription');
  var editLink = document.getElementById('editLink');

  // Сброс
  image.classList.add('d-none');
  loader.style.display = 'block';
  image.src = '';
  description.innerHTML = '';
  editLink.href = '#';

  // Декодирование и вставка описания
  var decodedHtml = decodeJsEscapedString(descriptionHtml);
  description.innerHTML = decodedHtml;

  // Обновление ссылки на редактирование
  if (editLinkUrl) editLink.href = editLinkUrl;

  // Обработка загрузки изображения
  image.onload = function () {
    loader.style.display = 'none';
    image.classList.remove('d-none');
  };
  image.onerror = function () {
    console.warn("Ошибка загрузки изображения:", imageUrl);
    loader.style.display = 'none';
    image.src = '/cpanel/images/maps/default.jpg'; // Показываем default, но без рекурсии
    image.classList.remove('d-none');
  };
  image.src = imageUrl;
  $('#itemShowModal').modal('show');
}

/***/ },

/***/ "./assets/js/init/picture-form.js"
/*!****************************************!*\
  !*** ./assets/js/init/picture-form.js ***!
  \****************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initPictureForm: () => (/* binding */ initPictureForm)
/* harmony export */ });
/* harmony import */ var _init_image_upload__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./init-image-upload */ "./assets/js/init/init-image-upload.js");

function initPictureForm() {
  (0,_init_image_upload__WEBPACK_IMPORTED_MODULE_0__.initImageUpload)({
    deleteUrlPrefix: '/cpanel/picture/images/',
    uploadUrl: '/cpanel/picture/dropzone'
  });
}

/***/ },

/***/ "./assets/js/init/presentation-swiper.js"
/*!***********************************************!*\
  !*** ./assets/js/init/presentation-swiper.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initPresentationSwiper: () => (/* binding */ initPresentationSwiper)
/* harmony export */ });
function initPresentationSwiper() {
  var swiperEl = document.querySelector('.swiper-container');

  // Проверка, нужен ли запуск (чтобы не дублировать инициализацию)
  if (!swiperEl || swiperEl.swiper) return;
  var currentStepEl = document.getElementById('presentation-current-step');
  new Swiper(swiperEl, {
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev'
    },
    loop: true,
    on: {
      slideChange: function slideChange() {
        if (currentStepEl) {
          currentStepEl.textContent = this.realIndex + 1;
        }
      }
    }
  });
}

/***/ },

/***/ "./assets/js/init/profile-username-check.js"
/*!**************************************************!*\
  !*** ./assets/js/init/profile-username-check.js ***!
  \**************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initUsernameAvailabilityCheck: () => (/* binding */ initUsernameAvailabilityCheck)
/* harmony export */ });
function initUsernameAvailabilityCheck() {
  var input = document.querySelector('[data-username-check-url]');
  if (!input) return;
  var checkUrl = input.dataset.usernameCheckUrl;
  var checkTarget = document.querySelector(input.dataset.usernameCheckTarget);
  var registerButton = document.getElementById(input.dataset.registerButtonId);
  var errorMessage = (checkTarget === null || checkTarget === void 0 ? void 0 : checkTarget.dataset.errorMsg) || 'Username already exists.';
  if (!checkTarget || !registerButton) return;
  input.addEventListener('input', function () {
    var username = input.value.trim();
    if (username.length === 0) {
      checkTarget.classList.add('d-none');
      registerButton.disabled = true;
      return;
    }
    fetch("".concat(checkUrl, "?username=").concat(encodeURIComponent(username))).then(function (response) {
      return response.json();
    }).then(function (data) {
      if (data.exists) {
        checkTarget.classList.remove('d-none');
        checkTarget.textContent = errorMessage;
        registerButton.disabled = true;
      } else {
        checkTarget.classList.add('d-none');
        checkTarget.textContent = '';
        registerButton.disabled = false;
      }
    })["catch"](function (error) {
      console.error('Username check failed:', error);
      registerButton.disabled = true;
    });
  });
}

/***/ },

/***/ "./assets/js/init/share-button.js"
/*!****************************************!*\
  !*** ./assets/js/init/share-button.js ***!
  \****************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initShareButton: () => (/* binding */ initShareButton)
/* harmony export */ });
function initShareButton() {
  var shareButtons = document.querySelectorAll('[data-share-button]');
  shareButtons.forEach(function (wrapper) {
    var message = wrapper.querySelector('[data-share-message]');
    var url = wrapper.dataset.url || window.location.href;
    var button = wrapper.querySelector('button');
    if (!button) return;
    button.addEventListener('click', function () {
      // Совместимость с HTTP
      var tempInput = document.createElement('input');
      tempInput.value = url;
      document.body.appendChild(tempInput);
      tempInput.select();
      try {
        var success = document.execCommand('copy');
        if (success && message) {
          message.style.display = 'inline';
          setTimeout(function () {
            message.style.display = 'none';
          }, 2000);
        }
      } catch (err) {
        alert('Ошибка при копировании ссылки: ' + err);
      }
      document.body.removeChild(tempInput);
    });
  });
}

/***/ },

/***/ "./assets/js/init/simple-form.js"
/*!***************************************!*\
  !*** ./assets/js/init/simple-form.js ***!
  \***************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initSimpleForm: () => (/* binding */ initSimpleForm)
/* harmony export */ });
/* harmony import */ var _init_image_upload__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./init-image-upload */ "./assets/js/init/init-image-upload.js");

function initSimpleForm() {
  (0,_init_image_upload__WEBPACK_IMPORTED_MODULE_0__.initImageUpload)({
    deleteUrlPrefix: '/cpanel/simple/images/',
    uploadUrl: '/cpanel/simple/dropzone'
  });
}

/***/ },

/***/ "./assets/js/init/small-steps-chart.js"
/*!*********************************************!*\
  !*** ./assets/js/init/small-steps-chart.js ***!
  \*********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initSmallStepsChart: () => (/* binding */ initSmallStepsChart)
/* harmony export */ });
/* harmony import */ var chart_js_auto__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! chart.js/auto */ "./node_modules/chart.js/auto/auto.js");

var smallStepsChart = null;
function initSmallStepsChart() {
  var chartCanvas = document.getElementById('smallStepsChart');
  if (!chartCanvas) return;
  var range = chartCanvas.dataset.range || 'weeks';
  var year = chartCanvas.dataset.year;
  var weeks = JSON.parse(chartCanvas.dataset.weeks || '[]');
  var days = JSON.parse(chartCanvas.dataset.days || '[]');
  var points = range === 'days' ? days : weeks;
  var labels = points.map(function (p) {
    return range === 'days' ? p.day : p.week;
  });
  var values = points.map(function (p) {
    return p.count;
  });
  if (smallStepsChart) {
    smallStepsChart.destroy();
    smallStepsChart = null;
  }
  smallStepsChart = new chart_js_auto__WEBPACK_IMPORTED_MODULE_0__["default"](chartCanvas.getContext('2d'), {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: range === 'days' ? "Small steps \u043F\u043E \u0434\u043D\u044F\u043C (\u043F\u043E\u0441\u043B\u0435\u0434\u043D\u0438\u0435 12)" : "Small steps \u043F\u043E \u043D\u0435\u0434\u0435\u043B\u044F\u043C (".concat(year, ")"),
        data: values,
        borderWidth: 1,
        tension: 0.35,
        fill: true,
        borderColor: '#C18A44',
        backgroundColor: 'rgba(212, 161, 95, 0.45)',
        pointRadius: 3,
        pointHoverRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}

/***/ },

/***/ "./assets/js/init/typewriter.js"
/*!**************************************!*\
  !*** ./assets/js/init/typewriter.js ***!
  \**************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initTypewriter: () => (/* binding */ initTypewriter)
/* harmony export */ });
function initTypewriter(_ref) {
  var selector = _ref.selector,
    text = _ref.text,
    _ref$speed = _ref.speed,
    speed = _ref$speed === void 0 ? 100 : _ref$speed;
  var outputEl = document.querySelector(selector);
  if (!outputEl || !text) return;
  var index = 0;
  function type() {
    if (index < text.length) {
      outputEl.innerHTML += text.charAt(index);
      index++;
      setTimeout(type, speed);
    }
  }

  // Очистим элемент на всякий случай
  outputEl.innerHTML = '';
  type();
}

/***/ },

/***/ "./assets/js/init/users-profile-share.js"
/*!***********************************************!*\
  !*** ./assets/js/init/users-profile-share.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initProfileShare: () => (/* binding */ initProfileShare)
/* harmony export */ });
function initProfileShare() {
  document.addEventListener('DOMContentLoaded', function () {
    // Копирование ссылки
    document.querySelectorAll('[data-action="copy-link"]').forEach(function (button) {
      button.addEventListener('click', function () {
        var link = button.dataset.link;
        navigator.clipboard.writeText(link).then(function () {
          return alert("\u0421\u0441\u044B\u043B\u043A\u0430 \u0441\u043A\u043E\u043F\u0438\u0440\u043E\u0432\u0430\u043D\u0430: ".concat(link));
        })["catch"](function () {
          return fallbackCopy(link);
        });
      });
    });

    // Поделиться ссылкой
    document.querySelectorAll('[data-action="share-link"]').forEach(function (button) {
      button.addEventListener('click', function () {
        var link = button.dataset.link;
        var username = button.dataset.username;
        if (navigator.share) {
          navigator.share({
            title: "\u041F\u0440\u043E\u0444\u0438\u043B\u044C \u043F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044F ".concat(username),
            text: "\u041F\u043E\u0441\u043C\u043E\u0442\u0440\u0438\u0442\u0435 \u043F\u0440\u043E\u0444\u0438\u043B\u044C \u043F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044F ".concat(username, " \u043D\u0430 Neurify"),
            url: link
          })["catch"](function (error) {
            return console.error('Ошибка при попытке поделиться:', error);
          });
        } else {
          navigator.clipboard.writeText(link).then(function () {
            return alert("\u0421\u0441\u044B\u043B\u043A\u0430 \u0441\u043A\u043E\u043F\u0438\u0440\u043E\u0432\u0430\u043D\u0430: ".concat(link));
          })["catch"](function () {
            return fallbackCopy(link);
          });
        }
      });
    });

    // Запасной способ копирования (для старых браузеров)
    function fallbackCopy(text) {
      var textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      alert("\u0421\u0441\u044B\u043B\u043A\u0430 \u0441\u043A\u043E\u043F\u0438\u0440\u043E\u0432\u0430\u043D\u0430: ".concat(text));
    }
  });
}

/***/ },

/***/ "./assets/js/init/weekly-score-chart.js"
/*!**********************************************!*\
  !*** ./assets/js/init/weekly-score-chart.js ***!
  \**********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initWeeklyScoreChart: () => (/* binding */ initWeeklyScoreChart)
/* harmony export */ });
/* harmony import */ var chart_js_auto__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! chart.js/auto */ "./node_modules/chart.js/auto/auto.js");

var weeklyScoreChart = null;
function initWeeklyScoreChart() {
  var canvas = document.getElementById('weeklyScoreChart');
  if (!canvas) return;
  var profileId = canvas.dataset.profileId;
  fetch("/cpanel/progress/journal/api/weekly-score/".concat(profileId)).then(function (r) {
    return r.json();
  }).then(function (data) {
    var labels = data.weeks.map(function (i) {
      return i.label;
    });
    var values = data.weeks.map(function (i) {
      return i.score;
    });
    var labelScore = canvas.dataset.labelScore || 'Score';
    if (weeklyScoreChart) {
      weeklyScoreChart.destroy();
      weeklyScoreChart = null;
    }
    weeklyScoreChart = new chart_js_auto__WEBPACK_IMPORTED_MODULE_0__["default"](canvas.getContext('2d'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: labelScore,
          data: values,
          borderWidth: 1,
          borderColor: 'rgba(255, 140, 0, 1)',
          // линия
          pointBackgroundColor: 'rgba(255, 140, 0, 1)',
          // точки
          pointBorderColor: 'rgba(255, 140, 0, 1)',
          fill: false
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        scales: {
          y: {
            beginAtZero: true,
            suggestedMax: 60
          }
        }
      }
    });
  });
}

/***/ },

/***/ "./assets/react/App.js"
/*!*****************************!*\
  !*** ./assets/react/App.js ***!
  \*****************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-router-dom */ "./node_modules/react-router-dom/dist/index.js");
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react-router-dom */ "./node_modules/react-router/dist/index.js");
/* harmony import */ var _components_MatrixPage__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/MatrixPage */ "./assets/react/components/MatrixPage.js");
/* harmony import */ var _components_AddItemPage__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/AddItemPage */ "./assets/react/components/AddItemPage.js");
/* harmony import */ var _components_EditItemPage__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/EditItemPage */ "./assets/react/components/EditItemPage.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
// assets/react/App.js






function App() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(react_router_dom__WEBPACK_IMPORTED_MODULE_1__.BrowserRouter, {
    basename: "/cpanel",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Routes, {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Route, {
        path: "/editor/show/:mapId",
        element: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_components_MatrixPage__WEBPACK_IMPORTED_MODULE_3__["default"], {})
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Route, {
        path: "/editor/show/create/:mapId",
        element: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_components_AddItemPage__WEBPACK_IMPORTED_MODULE_4__["default"], {})
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Route, {
        path: "/editor/item/edit/:id",
        element: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_components_EditItemPage__WEBPACK_IMPORTED_MODULE_5__["default"], {})
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Route, {
        path: "/",
        element: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
          children: "Matrix Overview?"
        })
      })]
    })
  });
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (App);

/***/ },

/***/ "./assets/react/components/AddItemPage.js"
/*!************************************************!*\
  !*** ./assets/react/components/AddItemPage.js ***!
  \************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-router-dom */ "./node_modules/react-router/dist/index.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }



var AddItemPage = function AddItemPage() {
  var _useParams = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_1__.useParams)(),
    mapId = _useParams.mapId;
  var navigate = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_1__.useNavigate)();
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(''),
    _useState2 = _slicedToArray(_useState, 2),
    mapName = _useState2[0],
    setMapName = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(true),
    _useState4 = _slicedToArray(_useState3, 2),
    loading = _useState4[0],
    setLoading = _useState4[1];
  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState6 = _slicedToArray(_useState5, 2),
    error = _useState6[0],
    setError = _useState6[1];
  var _useState7 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(''),
    _useState8 = _slicedToArray(_useState7, 2),
    title = _useState8[0],
    setTitle = _useState8[1];
  var _useState9 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(''),
    _useState10 = _slicedToArray(_useState9, 2),
    description = _useState10[0],
    setDescription = _useState10[1];
  var _useState11 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState12 = _slicedToArray(_useState11, 2),
    file = _useState12[0],
    setFile = _useState12[1];
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(function () {
    fetch("/api/matrix/add/".concat(mapId)).then(function (response) {
      if (!response.ok) throw new Error('Ошибка при загрузке данных карты');
      return response.json();
    }).then(function (data) {
      setMapName(data.name);
      setLoading(false);
    })["catch"](function (err) {
      console.error(err);
      setError(err.message);
      setLoading(false);
    });
  }, [mapId]);
  var handleTitleChange = function handleTitleChange(event) {
    setTitle(event.target.value);
  };
  var handleDescriptionChange = function handleDescriptionChange(event) {
    setDescription(event.target.value);
  };
  var handleFileChange = function handleFileChange(event) {
    setFile(event.target.files[0]);
  };
  var handleSubmit = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee(event) {
      var formData, response, errorData, responseData;
      return _regeneratorRuntime().wrap(function _callee$(_context) {
        while (1) switch (_context.prev = _context.next) {
          case 0:
            event.preventDefault();
            setLoading(true);
            setError(null);
            formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);
            if (file) {
              formData.append('file', file);
            }
            _context.prev = 7;
            _context.next = 10;
            return fetch("/api/matrix/item/create/".concat(mapId), {
              // Изменен URL
              method: 'POST',
              body: formData
            });
          case 10:
            response = _context.sent;
            if (response.ok) {
              _context.next = 16;
              break;
            }
            _context.next = 14;
            return response.json();
          case 14:
            errorData = _context.sent;
            throw new Error(errorData.error || 'Ошибка при добавлении элемента');
          case 16:
            _context.next = 18;
            return response.json();
          case 18:
            responseData = _context.sent;
            console.log('Item created:', responseData);
            navigate("/editor/show/".concat(mapId)); // Перенаправляем обратно
            _context.next = 27;
            break;
          case 23:
            _context.prev = 23;
            _context.t0 = _context["catch"](7);
            console.error(_context.t0);
            setError(_context.t0.message);
          case 27:
            _context.prev = 27;
            setLoading(false);
            return _context.finish(27);
          case 30:
          case "end":
            return _context.stop();
        }
      }, _callee, null, [[7, 23, 27, 30]]);
    }));
    return function handleSubmit(_x) {
      return _ref.apply(this, arguments);
    };
  }();
  if (loading) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    className: "text-center mt-5",
    children: "\u0417\u0430\u0433\u0440\u0443\u0437\u043A\u0430..."
  });
  if (error) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    className: "text-danger text-center mt-5",
    children: error
  });
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
    className: "main-content-container container-fluid",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "page-header row no-gutters py-4",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
        className: "col-12 col-sm-4 text-center text-sm-left mb-0",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
          className: "text-uppercase page-subtitle",
          children: "\u0421\u0442\u0440\u0443\u043A\u0442\u0443\u0440\u0438\u0440\u0443\u0439\u0442\u0435 \u0441\u0432\u043E\u0435 \u043C\u044B\u0448\u043B\u0435\u043D\u0438\u0435"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("h3", {
          className: "page-title",
          children: ["\u0422\u0430\u0431\u043B\u0438\u0446\u0430: ", mapName, " - \u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C \u044D\u043B\u0435\u043C\u0435\u043D\u0442"]
        })]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "row border bg-white p-2 mb-3",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("button", {
        className: "btn btn-sm btn-outline-secondary",
        onClick: function onClick() {
          return navigate("/editor/show/".concat(mapId));
        },
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("i", {
          className: "fas fa-angle-left"
        }), " \u041D\u0430\u0437\u0430\u0434"]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "row border bg-white",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
        className: "col-lg-12 col-md-12 col-sm-12 mt-4 mb-4 p-4",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("form", {
          onSubmit: handleSubmit,
          encType: "multipart/form-data",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("label", {
              children: "\u0417\u0430\u0433\u043E\u043B\u043E\u0432\u043E\u043A:"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
              type: "text",
              className: "form-control",
              name: "title",
              value: title,
              onChange: handleTitleChange
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("label", {
              className: "form-label",
              children: "\u0418\u0437\u043E\u0431\u0440\u0430\u0436\u0435\u043D\u0438\u0435:"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
              type: "file",
              name: "file",
              className: "form-control",
              onChange: handleFileChange
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("label", {
              children: "\u041E\u043F\u0438\u0441\u0430\u043D\u0438\u0435:"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("textarea", {
              className: "form-control",
              name: "description",
              rows: "5",
              value: description,
              onChange: handleDescriptionChange
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "text-center mt-3",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("button", {
              type: "submit",
              className: "btn btn-sm btn-outline-primary",
              disabled: loading,
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("i", {
                className: "fa fa-save"
              }), " \u0421\u043E\u0445\u0440\u0430\u043D\u0438\u0442\u044C"]
            }), loading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              className: "ml-2",
              children: "\u0421\u043E\u0445\u0440\u0430\u043D\u0435\u043D\u0438\u0435..."
            }), error && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
              className: "text-danger mt-2",
              children: error
            })]
          })]
        })
      })
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AddItemPage);

/***/ },

/***/ "./assets/react/components/EditItemPage.js"
/*!*************************************************!*\
  !*** ./assets/react/components/EditItemPage.js ***!
  \*************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-router-dom */ "./node_modules/react-router/dist/index.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }



var EditItemPage = function EditItemPage() {
  var _useParams = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_1__.useParams)(),
    itemId = _useParams.id;
  var _useParams2 = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_1__.useParams)(),
    mapId = _useParams2.mapId; // Возможно, вам понадобится mapId
  var navigate = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_1__.useNavigate)();
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState2 = _slicedToArray(_useState, 2),
    item = _useState2[0],
    setItem = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(true),
    _useState4 = _slicedToArray(_useState3, 2),
    loading = _useState4[0],
    setLoading = _useState4[1];
  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState6 = _slicedToArray(_useState5, 2),
    error = _useState6[0],
    setError = _useState6[1];
  var _useState7 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(''),
    _useState8 = _slicedToArray(_useState7, 2),
    title = _useState8[0],
    setTitle = _useState8[1];
  var _useState9 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(''),
    _useState10 = _slicedToArray(_useState9, 2),
    description = _useState10[0],
    setDescription = _useState10[1];
  var _useState11 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState12 = _slicedToArray(_useState11, 2),
    file = _useState12[0],
    setFile = _useState12[1];
  var _useState13 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(''),
    _useState14 = _slicedToArray(_useState13, 2),
    previewImage = _useState14[0],
    setPreviewImage = _useState14[1];
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(function () {
    fetch("/api/matrix/item/".concat(itemId)) // Создадим этот API-endpoint
    .then(function (response) {
      if (!response.ok) throw new Error('Ошибка при загрузке данных элемента');
      return response.json();
    }).then(function (data) {
      setItem(data);
      setTitle(data.title);
      setDescription(data.description);
      setPreviewImage("/cpanel/images/items/".concat(data.filename));
      setLoading(false);
    })["catch"](function (err) {
      console.error(err);
      setError(err.message);
      setLoading(false);
    });
  }, [itemId]);
  var handleTitleChange = function handleTitleChange(event) {
    setTitle(event.target.value);
  };
  var handleDescriptionChange = function handleDescriptionChange(event) {
    setDescription(event.target.value);
  };
  var handleFileChange = function handleFileChange(event) {
    var selectedFile = event.target.files[0];
    setFile(selectedFile);
    if (selectedFile) {
      var reader = new FileReader();
      reader.onloadend = function () {
        setPreviewImage(reader.result);
      };
      reader.readAsDataURL(selectedFile);
    } else {
      setPreviewImage(item !== null && item !== void 0 && item.filename ? "/cpanel/images/items/".concat(item.filename) : '');
    }
  };
  var handleSubmit = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee(event) {
      var formData, response, errorData, responseData;
      return _regeneratorRuntime().wrap(function _callee$(_context) {
        while (1) switch (_context.prev = _context.next) {
          case 0:
            event.preventDefault();
            setLoading(true);
            setError(null);
            formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);
            if (file) {
              formData.append('file', file);
            }
            _context.prev = 7;
            _context.next = 10;
            return fetch("/api/matrix/item/edit/".concat(itemId), {
              // Создадим этот API-endpoint
              method: 'POST',
              body: formData
            });
          case 10:
            response = _context.sent;
            if (response.ok) {
              _context.next = 16;
              break;
            }
            _context.next = 14;
            return response.json();
          case 14:
            errorData = _context.sent;
            throw new Error(errorData.error || 'Ошибка при обновлении элемента');
          case 16:
            _context.next = 18;
            return response.json();
          case 18:
            responseData = _context.sent;
            console.log('Item updated:', responseData);
            navigate("/editor/show/".concat(item.matrixMapId)); // Перенаправляем на страницу матрицы
            _context.next = 27;
            break;
          case 23:
            _context.prev = 23;
            _context.t0 = _context["catch"](7);
            console.error(_context.t0);
            setError(_context.t0.message);
          case 27:
            _context.prev = 27;
            setLoading(false);
            return _context.finish(27);
          case 30:
          case "end":
            return _context.stop();
        }
      }, _callee, null, [[7, 23, 27, 30]]);
    }));
    return function handleSubmit(_x) {
      return _ref.apply(this, arguments);
    };
  }();
  if (loading) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    className: "text-center mt-5",
    children: "\u0417\u0430\u0433\u0440\u0443\u0437\u043A\u0430..."
  });
  if (error) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    className: "text-danger text-center mt-5",
    children: error
  });
  if (!item) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    children: "\u042D\u043B\u0435\u043C\u0435\u043D\u0442 \u043D\u0435 \u043D\u0430\u0439\u0434\u0435\u043D"
  });
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
    className: "main-content-container container-fluid",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "page-header row no-gutters py-4",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
        className: "col-12 col-sm-4 text-center text-sm-left mb-0",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
          className: "text-uppercase page-subtitle",
          children: "\u0420\u0435\u0434\u0430\u043A\u0442\u0438\u0440\u043E\u0432\u0430\u043D\u0438\u0435 \u044D\u043B\u0435\u043C\u0435\u043D\u0442\u0430"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("h3", {
          className: "page-title",
          children: ["\u0420\u0435\u0434\u0430\u043A\u0442\u0438\u0440\u043E\u0432\u0430\u0442\u044C: ", item.title]
        })]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "row border bg-white p-2 mb-3",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("button", {
        className: "btn btn-sm btn-outline-secondary",
        onClick: function onClick() {
          return navigate("/editor/show/".concat(item.matrixMapId));
        },
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("i", {
          className: "fas fa-angle-left"
        }), " \u041D\u0430\u0437\u0430\u0434"]
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "row border bg-white",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
        className: "col-lg-12 col-md-12 col-sm-12 mt-4 mb-4 p-4",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("form", {
          onSubmit: handleSubmit,
          encType: "multipart/form-data",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("label", {
              children: "\u0417\u0430\u0433\u043E\u043B\u043E\u0432\u043E\u043A:"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
              type: "text",
              className: "form-control",
              name: "title",
              value: title,
              onChange: handleTitleChange
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("label", {
              className: "form-label",
              children: "\u0418\u0437\u043E\u0431\u0440\u0430\u0436\u0435\u043D\u0438\u0435:"
            }), previewImage && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("img", {
              src: previewImage,
              alt: "Preview",
              className: "img-fluid mb-2",
              style: {
                maxWidth: '200px'
              }
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
              type: "file",
              name: "file",
              className: "form-control",
              onChange: handleFileChange
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "form-group",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("label", {
              children: "\u041E\u043F\u0438\u0441\u0430\u043D\u0438\u0435:"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("textarea", {
              className: "form-control",
              name: "description",
              rows: "5",
              value: description,
              onChange: handleDescriptionChange
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "text-center mt-3",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("button", {
              type: "submit",
              className: "btn btn-sm btn-outline-primary",
              disabled: loading,
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("i", {
                className: "fa fa-save"
              }), " \u0421\u043E\u0445\u0440\u0430\u043D\u0438\u0442\u044C \u0438\u0437\u043C\u0435\u043D\u0435\u043D\u0438\u044F"]
            }), loading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              className: "ml-2",
              children: "\u0421\u043E\u0445\u0440\u0430\u043D\u0435\u043D\u0438\u0435..."
            }), error && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
              className: "text-danger mt-2",
              children: error
            })]
          })]
        })
      })
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (EditItemPage);

/***/ },

/***/ "./assets/react/components/ItemModal.js"
/*!**********************************************!*\
  !*** ./assets/react/components/ItemModal.js ***!
  \**********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-router-dom */ "./node_modules/react-router/dist/index.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");



var ItemModal = function ItemModal(_ref) {
  var item = _ref.item,
    onClose = _ref.onClose;
  if (!item) {
    return null;
  }
  var navigate = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_1__.useNavigate)();
  var handleEditClick = function handleEditClick() {
    navigate("/editor/item/edit/".concat(item[0])); // Предполагаемый маршрут редактирования
    onClose(); // Закрываем модальное окно после нажатия "Редактировать"
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    className: "modal fade show",
    style: {
      display: 'block'
    },
    "aria-modal": "true",
    role: "dialog",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "modal-dialog",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
        className: "modal-content",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
          className: "modal-header",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("h5", {
            className: "modal-title",
            children: "\u0418\u043D\u0444\u043E\u0440\u043C\u0430\u0446\u0438\u044F \u043E\u0431 \u044D\u043B\u0435\u043C\u0435\u043D\u0442\u0435"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("button", {
            type: "button",
            className: "close",
            onClick: onClose,
            "aria-label": "Close",
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              "aria-hidden": "true",
              children: "\xD7"
            })
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
          className: "modal-body",
          children: [item[1] && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
            style: {
              textAlign: 'justify'
            },
            children: item[1]
          }), !item[1] && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
            className: "text-muted",
            children: "\u041D\u0435\u0442 \u0434\u043E\u043F\u043E\u043B\u043D\u0438\u0442\u0435\u043B\u044C\u043D\u043E\u0439 \u0438\u043D\u0444\u043E\u0440\u043C\u0430\u0446\u0438\u0438."
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
          className: "modal-footer",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("div", {
            className: "d-flex justify-content-center",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("button", {
              type: "button",
              className: "btn btn-primary mr-2",
              onClick: handleEditClick,
              children: "\u0420\u0435\u0434\u0430\u043A\u0442\u0438\u0440\u043E\u0432\u0430\u0442\u044C"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("button", {
              type: "button",
              className: "btn btn-secondary",
              onClick: onClose,
              children: "\u0417\u0430\u043A\u0440\u044B\u0442\u044C"
            })]
          })
        })]
      })
    })
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ItemModal);

/***/ },

/***/ "./assets/react/components/MatrixGrid.js"
/*!***********************************************!*\
  !*** ./assets/react/components/MatrixGrid.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _ItemModal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ItemModal */ "./assets/react/components/ItemModal.js");
/* harmony import */ var _styles_MatrixGrid_module_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../styles/MatrixGrid.module.css */ "./assets/styles/MatrixGrid.module.css");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }




// Функция для получения еврейских букв по индексу (1-based)

var getSanskritLetter = function getSanskritLetter(index) {
  var letters = ['א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט'];
  return letters[index - 1] || '';
};
var MatrixGrid = function MatrixGrid(_ref) {
  var _ref$items = _ref.items,
    items = _ref$items === void 0 ? [] : _ref$items;
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState2 = _slicedToArray(_useState, 2),
    selectedItem = _useState2[0],
    setSelectedItem = _useState2[1];
  var handleCellClick = function handleCellClick(item) {
    setSelectedItem(item);
  };
  var handleCloseModal = function handleCloseModal() {
    setSelectedItem(null);
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("table", {
      className: "table table-bordered matrix-grid",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("tbody", {
        children: [0, 1, 2].map(function (row) {
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("tr", {
            children: [0, 1, 2].map(function (col) {
              var index = row * 3 + col;
              var item = items[index];
              var isEven = (index + 1) % 2 === 0;
              var defaultColor = isEven ? '#f7f7f7' : 'transparent';
              return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("td", {
                className: "td",
                style: {
                  cursor: 'pointer',
                  transition: 'background-color 0.3s ease',
                  backgroundColor: defaultColor
                },
                onClick: function onClick() {
                  return handleCellClick(item);
                },
                onMouseEnter: function onMouseEnter(e) {
                  return e.currentTarget.style.backgroundColor = '#e6e6e6';
                },
                onMouseLeave: function onMouseLeave(e) {
                  return e.currentTarget.style.backgroundColor = defaultColor;
                },
                children: item && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {
                  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
                    className: "devstyle-sanskrit-letter",
                    children: getSanskritLetter(index + 1)
                  }), item[1]]
                })
              }, index);
            })
          }, row);
        })
      })
    }), selectedItem && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_ItemModal__WEBPACK_IMPORTED_MODULE_1__["default"], {
      item: selectedItem,
      onClose: handleCloseModal
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MatrixGrid);

/***/ },

/***/ "./assets/react/components/MatrixPage.js"
/*!***********************************************!*\
  !*** ./assets/react/components/MatrixPage.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _MatrixGrid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MatrixGrid */ "./assets/react/components/MatrixGrid.js");
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react-router-dom */ "./node_modules/react-router-dom/dist/index.js");
/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react-router-dom */ "./node_modules/react-router/dist/index.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }




var MatrixPage = function MatrixPage() {
  var _useParams = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_3__.useParams)(),
    mapId = _useParams.mapId; // Получаем mapId из URL
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState2 = _slicedToArray(_useState, 2),
    map = _useState2[0],
    setMap = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(true),
    _useState4 = _slicedToArray(_useState3, 2),
    loading = _useState4[0],
    setLoading = _useState4[1];
  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState6 = _slicedToArray(_useState5, 2),
    error = _useState6[0],
    setError = _useState6[1];
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(function () {
    fetch("/api/matrix/".concat(mapId)).then(function (response) {
      if (!response.ok) throw new Error('Ошибка при загрузке карты');
      return response.json();
    }).then(function (data) {
      setMap(data);
      setLoading(false);
    })["catch"](function (err) {
      console.error(err);
      setError(err.message);
      setLoading(false);
    });
  }, [mapId]);
  if (loading) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
    className: "text-center mt-5",
    children: "\u0417\u0430\u0433\u0440\u0443\u0437\u043A\u0430 \u043A\u0430\u0440\u0442\u044B..."
  });
  if (error) return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
    className: "text-danger text-center mt-5",
    children: error
  });
  if (!map) return null;
  var name = map.name,
    description = map.description,
    filename = map.filename,
    _map$items = map.items,
    items = _map$items === void 0 ? [] : _map$items;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
    className: "row border bg-white p-4",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
      className: "col-xl-6 col-lg-8 col-md-12 col-sm-12 mx-auto",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
        className: "ratio-16x9 mb-3",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("img", {
          src: filename ? "/cpanel/images/maps/".concat(filename) : "/cpanel/images/maps/default.jpg",
          className: "img-fluid rounded",
          alt: "Map preview"
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("h6", {
        className: "text-center",
        style: {
          fontFamily: 'Georgia'
        },
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("strong", {
          children: name
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("p", {
        className: "text-center",
        style: {
          fontSize: 'small',
          fontFamily: 'Georgia'
        },
        children: description
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_MatrixGrid__WEBPACK_IMPORTED_MODULE_1__["default"], {
        items: items
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
        className: "text-center mt-3",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)(react_router_dom__WEBPACK_IMPORTED_MODULE_2__.Link, {
          to: "/editor/show/create/".concat(mapId),
          className: "btn btn-sm btn-outline-primary",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("i", {
            className: "fa fa-plus"
          }), " \u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C"]
        })
      })]
    })
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MatrixPage);

/***/ },

/***/ "./vendor/symfony/ux-turbo/assets/dist/turbo_controller.js"
/*!*****************************************************************!*\
  !*** ./vendor/symfony/ux-turbo/assets/dist/turbo_controller.js ***!
  \*****************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ turbo_controller)
/* harmony export */ });
/* harmony import */ var _hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @hotwired/stimulus */ "./node_modules/@hotwired/stimulus/dist/stimulus.js");
/* harmony import */ var _hotwired_turbo__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @hotwired/turbo */ "./node_modules/@hotwired/turbo/dist/turbo.es2017-esm.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }
function _possibleConstructorReturn(t, e) { if (e && ("object" == _typeof(e) || "function" == typeof e)) return e; if (void 0 !== e) throw new TypeError("Derived constructors may only return object or undefined"); return _assertThisInitialized(t); }
function _assertThisInitialized(e) { if (void 0 === e) throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); return e; }
function _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }
function _getPrototypeOf(t) { return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) { return t.__proto__ || Object.getPrototypeOf(t); }, _getPrototypeOf(t); }
function _inherits(t, e) { if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function"); t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), Object.defineProperty(t, "prototype", { writable: !1 }), e && _setPrototypeOf(t, e); }
function _setPrototypeOf(t, e) { return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) { return t.__proto__ = e, t; }, _setPrototypeOf(t, e); }


var turbo_controller = /*#__PURE__*/function (_Controller) {
  function turbo_controller() {
    _classCallCheck(this, turbo_controller);
    return _callSuper(this, turbo_controller, arguments);
  }
  _inherits(turbo_controller, _Controller);
  return _createClass(turbo_controller);
}(_hotwired_stimulus__WEBPACK_IMPORTED_MODULE_0__.Controller);


/***/ },

/***/ "./assets/styles/MatrixGrid.module.css"
/*!*********************************************!*\
  !*** ./assets/styles/MatrixGrid.module.css ***!
  \*********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ },

/***/ "./assets/styles/app.scss"
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_hotwired_turbo_dist_turbo_es2017-esm_js-node_modules_symfony_stimulus-br-dc91c4"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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REFBVTtRQUFDRSxLQUFLLEVBQUVBO01BQU0sQ0FBQyxDQUFDLGVBRTNCbGYsc0RBQUE7UUFBS2tkLFNBQVMsRUFBQyxrQkFBa0I7UUFBQWhELFFBQUEsZUFDN0JGLHVEQUFBLENBQUMrRixrREFBSTtVQUFDSyxFQUFFLHlCQUFBdmUsTUFBQSxDQUF5QnFaLEtBQUssQ0FBRztVQUFDZ0MsU0FBUyxFQUFDLGdDQUFnQztVQUFBaEQsUUFBQSxnQkFDaEZsYSxzREFBQTtZQUFHa2QsU0FBUyxFQUFDO1VBQVksQ0FBSSxDQUFDLHFEQUNsQztRQUFBLENBQU07TUFBQyxDQUNOLENBQUM7SUFBQSxDQUNMO0VBQUMsQ0FDTCxDQUFDO0FBRWQsQ0FBQztBQUVELGlFQUFldEQsVUFBVSxFOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUM5RHVCO0FBQ3ZCO0FBQUEsSUFFbkJ5RyxnQkFBZ0IsMEJBQUF4bEIsV0FBQTtFQUFBLFNBQUF3bEIsaUJBQUE7SUFBQXZsQixlQUFBLE9BQUF1bEIsZ0JBQUE7SUFBQSxPQUFBdGxCLFVBQUEsT0FBQXNsQixnQkFBQSxFQUFBcmxCLFNBQUE7RUFBQTtFQUFBQyxTQUFBLENBQUFvbEIsZ0JBQUEsRUFBQXhsQixXQUFBO0VBQUEsT0FBQUssWUFBQSxDQUFBbWxCLGdCQUFBO0FBQUEsRUFBUzFsQiwwREFBVTs7Ozs7Ozs7Ozs7OztBQ0h6Qzs7Ozs7Ozs7Ozs7OztBQ0FBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLyBcXC5banRdc3giLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2NvbnRyb2xsZXJzLmpzb24iLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2NvbnRyb2xsZXJzL2NsaXBib2FyZF9jb250cm9sbGVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9jb250cm9sbGVycy9oZWxsb19jb250cm9sbGVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9hcHAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2Jvb3RzdHJhcC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9hY2NvdW50LWluZm8tYm94LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L2FjY291bnQtbGlua3MuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvYWN0aXZpdHktY2hhcnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvYWRkLWx1eC1tZW51LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L2JvdHRvbS1hZGQtbWVudS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9ja2VkaXRvci1pbml0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L2V2ZW50LWNoYXQtaW5pdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9ldmVudC1jb21tZW50cy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9ldmVudC1lbnNlbWJsZS1wcm9maWxlLWNoYXQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvZXZlbnQtZm9ybS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9ldmVudHMtZmVlZC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9ldmVudHMtaXRlcmF0aW9uLWJvb2ttYXJrLXRvZ2dsZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9nb2FsLXBlcmNlbnQtY2hhcnRzLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L2luZm8tYm94LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L2luaXQtaW1hZ2UtdXBsb2FkLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L2luaXQtc2F2ZS1idXR0b24uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvaW50cm8taW5pdC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9tYXRyaXgtaXRlbS1kZWxldGUuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvbWF0cml4LWl0ZW0taW1hZ2UtdXBsb2FkLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L21hdHJpeC1zaG93LWl0ZW0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvcGljdHVyZS1mb3JtLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L3ByZXNlbnRhdGlvbi1zd2lwZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvcHJvZmlsZS11c2VybmFtZS1jaGVjay5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC9zaGFyZS1idXR0b24uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvc2ltcGxlLWZvcm0uanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvc21hbGwtc3RlcHMtY2hhcnQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2luaXQvdHlwZXdyaXRlci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaW5pdC91c2Vycy1wcm9maWxlLXNoYXJlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9pbml0L3dlZWtseS1zY29yZS1jaGFydC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvcmVhY3QvQXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9yZWFjdC9jb21wb25lbnRzL0FkZEl0ZW1QYWdlLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9yZWFjdC9jb21wb25lbnRzL0VkaXRJdGVtUGFnZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvcmVhY3QvY29tcG9uZW50cy9JdGVtTW9kYWwuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3JlYWN0L2NvbXBvbmVudHMvTWF0cml4R3JpZC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvcmVhY3QvY29tcG9uZW50cy9NYXRyaXhQYWdlLmpzIiwid2VicGFjazovLy8uL3ZlbmRvci9zeW1mb255L3V4LXR1cmJvL2Fzc2V0cy9kaXN0L3R1cmJvX2NvbnRyb2xsZXIuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3N0eWxlcy9NYXRyaXhHcmlkLm1vZHVsZS5jc3M/ODJjOSIsIndlYnBhY2s6Ly8vLi9hc3NldHMvc3R5bGVzL2FwcC5zY3NzPzNlOGEiXSwic291cmNlc0NvbnRlbnQiOlsidmFyIG1hcCA9IHtcblx0XCIuL2NsaXBib2FyZF9jb250cm9sbGVyLmpzXCI6IFwiLi9ub2RlX21vZHVsZXMvQHN5bWZvbnkvc3RpbXVsdXMtYnJpZGdlL2xhenktY29udHJvbGxlci1sb2FkZXIuanMhLi9hc3NldHMvY29udHJvbGxlcnMvY2xpcGJvYXJkX2NvbnRyb2xsZXIuanNcIixcblx0XCIuL2hlbGxvX2NvbnRyb2xsZXIuanNcIjogXCIuL25vZGVfbW9kdWxlcy9Ac3ltZm9ueS9zdGltdWx1cy1icmlkZ2UvbGF6eS1jb250cm9sbGVyLWxvYWRlci5qcyEuL2Fzc2V0cy9jb250cm9sbGVycy9oZWxsb19jb250cm9sbGVyLmpzXCJcbn07XG5cblxuZnVuY3Rpb24gd2VicGFja0NvbnRleHQocmVxKSB7XG5cdHZhciBpZCA9IHdlYnBhY2tDb250ZXh0UmVzb2x2ZShyZXEpO1xuXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhpZCk7XG59XG5mdW5jdGlvbiB3ZWJwYWNrQ29udGV4dFJlc29sdmUocmVxKSB7XG5cdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8obWFwLCByZXEpKSB7XG5cdFx0dmFyIGUgPSBuZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiICsgcmVxICsgXCInXCIpO1xuXHRcdGUuY29kZSA9ICdNT0RVTEVfTk9UX0ZPVU5EJztcblx0XHR0aHJvdyBlO1xuXHR9XG5cdHJldHVybiBtYXBbcmVxXTtcbn1cbndlYnBhY2tDb250ZXh0LmtleXMgPSBmdW5jdGlvbiB3ZWJwYWNrQ29udGV4dEtleXMoKSB7XG5cdHJldHVybiBPYmplY3Qua2V5cyhtYXApO1xufTtcbndlYnBhY2tDb250ZXh0LnJlc29sdmUgPSB3ZWJwYWNrQ29udGV4dFJlc29sdmU7XG5tb2R1bGUuZXhwb3J0cyA9IHdlYnBhY2tDb250ZXh0O1xud2VicGFja0NvbnRleHQuaWQgPSBcIi4vYXNzZXRzL2NvbnRyb2xsZXJzIHN5bmMgcmVjdXJzaXZlIC4vbm9kZV9tb2R1bGVzL0BzeW1mb255L3N0aW11bHVzLWJyaWRnZS9sYXp5LWNvbnRyb2xsZXItbG9hZGVyLmpzISBcXFxcLltqdF1zeD8kXCI7IiwiaW1wb3J0IGNvbnRyb2xsZXJfMCBmcm9tICdAc3ltZm9ueS91eC10dXJiby9kaXN0L3R1cmJvX2NvbnRyb2xsZXIuanMnO1xuZXhwb3J0IGRlZmF1bHQge1xuICAnc3ltZm9ueS0tdXgtdHVyYm8tLXR1cmJvLWNvcmUnOiBjb250cm9sbGVyXzAsXG59OyIsImltcG9ydCB7IENvbnRyb2xsZXIgfSBmcm9tICdAaG90d2lyZWQvc3RpbXVsdXMnO1xyXG5cclxuZXhwb3J0IGRlZmF1bHQgY2xhc3MgZXh0ZW5kcyBDb250cm9sbGVyIHtcclxuICAgIHN0YXRpYyB0YXJnZXRzID0gWydzb3VyY2UnLCAnYnV0dG9uJywgJ2ZlZWRiYWNrJ107XHJcblxyXG4gICAgY29weSgpIHtcclxuICAgICAgICB2YXIgdGV4dCA9ICh0aGlzLnNvdXJjZVRhcmdldC5pbm5lclRleHQgfHwgdGhpcy5zb3VyY2VUYXJnZXQudGV4dENvbnRlbnQgfHwgJycpLnRyaW0oKTtcclxuICAgICAgICB2YXIgc2VsZiA9IHRoaXM7XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIG9rKCkgeyBzZWxmLnNob3dGZWVkYmFjaygnQ29waWVkIScpOyB9XHJcblxyXG4gICAgICAgIGlmIChuYXZpZ2F0b3IuY2xpcGJvYXJkICYmIG5hdmlnYXRvci5jbGlwYm9hcmQud3JpdGVUZXh0KSB7XHJcbiAgICAgICAgICAgIG5hdmlnYXRvci5jbGlwYm9hcmQud3JpdGVUZXh0KHRleHQpLnRoZW4ob2spLmNhdGNoKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIHNlbGYuZmFsbGJhY2tDb3B5KHRleHQsIG9rKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgdGhpcy5mYWxsYmFja0NvcHkodGV4dCwgb2spO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBmYWxsYmFja0NvcHkodGV4dCwgZG9uZSkge1xyXG4gICAgICAgIHZhciB0YSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3RleHRhcmVhJyk7XHJcbiAgICAgICAgdGEudmFsdWUgPSB0ZXh0O1xyXG4gICAgICAgIHRhLnNldEF0dHJpYnV0ZSgncmVhZG9ubHknLCAnJyk7XHJcbiAgICAgICAgdGEuc3R5bGUucG9zaXRpb24gPSAnYWJzb2x1dGUnO1xyXG4gICAgICAgIHRhLnN0eWxlLmxlZnQgPSAnLTk5OTlweCc7XHJcbiAgICAgICAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZCh0YSk7XHJcbiAgICAgICAgdGEuc2VsZWN0KCk7XHJcbiAgICAgICAgdHJ5IHsgZG9jdW1lbnQuZXhlY0NvbW1hbmQoJ2NvcHknKTsgfSBjYXRjaCAoZSkgeyAvKiBpZ25vcmUgKi8gfVxyXG4gICAgICAgIGRvY3VtZW50LmJvZHkucmVtb3ZlQ2hpbGQodGEpO1xyXG4gICAgICAgIGlmICh0eXBlb2YgZG9uZSA9PT0gJ2Z1bmN0aW9uJykgZG9uZSgpO1xyXG4gICAgfVxyXG5cclxuICAgIHNob3dGZWVkYmFjayhtZXNzYWdlKSB7XHJcbiAgICAgICAgaWYgKHRoaXMuaGFzRmVlZGJhY2tUYXJnZXQpIHtcclxuICAgICAgICAgICAgdGhpcy5mZWVkYmFja1RhcmdldC50ZXh0Q29udGVudCA9IG1lc3NhZ2U7XHJcbiAgICAgICAgICAgIHRoaXMuZmVlZGJhY2tUYXJnZXQuY2xhc3NMaXN0LnJlbW92ZSgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgIHZhciBzZWxmID0gdGhpcztcclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBzZWxmLmZlZWRiYWNrVGFyZ2V0LmNsYXNzTGlzdC5hZGQoJ2Qtbm9uZScpO1xyXG4gICAgICAgICAgICB9LCAxNTAwKTtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKHRoaXMuaGFzQnV0dG9uVGFyZ2V0KSB7XHJcbiAgICAgICAgICAgIHZhciBvcmlnaW5hbCA9IHRoaXMuYnV0dG9uVGFyZ2V0LmlubmVySFRNTDtcclxuICAgICAgICAgICAgdGhpcy5idXR0b25UYXJnZXQuaW5uZXJIVE1MID0gJzxpIGNsYXNzPVwiZmFyIGZhLWNoZWNrLWNpcmNsZVwiIGFyaWEtaGlkZGVuPVwidHJ1ZVwiPjwvaT4gJyArIG1lc3NhZ2U7XHJcbiAgICAgICAgICAgIHZhciBzZWxmID0gdGhpcztcclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBzZWxmLmJ1dHRvblRhcmdldC5pbm5lckhUTUwgPSBvcmlnaW5hbDtcclxuICAgICAgICAgICAgfSwgMTUwMCk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59IiwiaW1wb3J0IHsgQ29udHJvbGxlciB9IGZyb20gJ0Bob3R3aXJlZC9zdGltdWx1cyc7XG5cbi8qXG4gKiBUaGlzIGlzIGFuIGV4YW1wbGUgU3RpbXVsdXMgY29udHJvbGxlciFcbiAqXG4gKiBBbnkgZWxlbWVudCB3aXRoIGEgZGF0YS1jb250cm9sbGVyPVwiaGVsbG9cIiBhdHRyaWJ1dGUgd2lsbCBjYXVzZVxuICogdGhpcyBjb250cm9sbGVyIHRvIGJlIGV4ZWN1dGVkLiBUaGUgbmFtZSBcImhlbGxvXCIgY29tZXMgZnJvbSB0aGUgZmlsZW5hbWU6XG4gKiBoZWxsb19jb250cm9sbGVyLmpzIC0+IFwiaGVsbG9cIlxuICpcbiAqIERlbGV0ZSB0aGlzIGZpbGUgb3IgYWRhcHQgaXQgZm9yIHlvdXIgdXNlIVxuICovXG5leHBvcnQgZGVmYXVsdCBjbGFzcyBleHRlbmRzIENvbnRyb2xsZXIge1xuICAgIGNvbm5lY3QoKSB7XG4gICAgICAgIHRoaXMuZWxlbWVudC50ZXh0Q29udGVudCA9ICdIZWxsbyBTdGltdWx1cyEgRWRpdCBtZSBpbiBhc3NldHMvY29udHJvbGxlcnMvaGVsbG9fY29udHJvbGxlci5qcyc7XG4gICAgfVxufVxuIiwiaW1wb3J0ICcuL2Jvb3RzdHJhcCc7XG5pbXBvcnQgJy4vc3R5bGVzL2FwcC5zY3NzJztcbmltcG9ydCAnQGhvdHdpcmVkL3R1cmJvJztcbmltcG9ydCAnQGtobXl6bmlrb3YvcHdhLWluc3RhbGwnO1xuXG4vLyBJTklUIElNUE9SVFNcbmltcG9ydCB7IGluaXRBY2NvdW50TGlua3MgfSBmcm9tICcuL2pzL2luaXQvYWNjb3VudC1saW5rcyc7XG5pbXBvcnQgeyBpbml0SW5mb0JveCB9IGZyb20gJy4vanMvaW5pdC9pbmZvLWJveCc7XG5pbXBvcnQgeyBpbml0Qm90dG9tQWRkTWVudVRvZ2dsZSB9IGZyb20gJy4vanMvaW5pdC9ib3R0b20tYWRkLW1lbnUnO1xuaW1wb3J0IHsgaW5pdE1hdHJpeFNob3dJdGVtIH0gZnJvbSAnLi9qcy9pbml0L21hdHJpeC1zaG93LWl0ZW0nO1xuaW1wb3J0IHsgaW5pdENLRWRpdG9yIH0gZnJvbSAnLi9qcy9pbml0L2NrZWRpdG9yLWluaXQnO1xuaW1wb3J0IHsgaW5pdE1hdHJpeEl0ZW1JbWFnZVVwbG9hZCB9IGZyb20gJy4vanMvaW5pdC9tYXRyaXgtaXRlbS1pbWFnZS11cGxvYWQnO1xuaW1wb3J0IHsgaW5pdE1hdHJpeEl0ZW1EZWxldGVNb2RhbCB9IGZyb20gJy4vanMvaW5pdC9tYXRyaXgtaXRlbS1kZWxldGUnO1xuaW1wb3J0IHsgaW5pdEFjdGl2aXR5Q2hhcnQgfSBmcm9tICcuL2pzL2luaXQvYWN0aXZpdHktY2hhcnQnO1xuaW1wb3J0IHsgaW5pdFNtYWxsU3RlcHNDaGFydCB9IGZyb20gJy4vanMvaW5pdC9zbWFsbC1zdGVwcy1jaGFydCc7XG5pbXBvcnQgeyBpbml0V2Vla2x5U2NvcmVDaGFydCB9IGZyb20gJy4vanMvaW5pdC93ZWVrbHktc2NvcmUtY2hhcnQnO1xuaW1wb3J0IHsgaW5pdFR5cGV3cml0ZXIgfSBmcm9tICcuL2pzL2luaXQvdHlwZXdyaXRlcic7XG5pbXBvcnQgeyBpbml0RXZlbnRzRmVlZCB9IGZyb20gJy4vanMvaW5pdC9ldmVudHMtZmVlZCc7XG5pbXBvcnQgeyBpbml0UHJvZmlsZUNoYXQgfSBmcm9tICcuL2pzL2luaXQvZXZlbnQtZW5zZW1ibGUtcHJvZmlsZS1jaGF0JztcbmltcG9ydCB7IGluaXRDb21tZW50cyB9IGZyb20gJy4vanMvaW5pdC9ldmVudC1jb21tZW50cyc7XG5pbXBvcnQgeyBpbml0RXZlbnRGb3JtIH0gZnJvbSAnLi9qcy9pbml0L2V2ZW50LWZvcm0nO1xuaW1wb3J0IHsgaW5pdFNpbXBsZUZvcm0gfSBmcm9tICcuL2pzL2luaXQvc2ltcGxlLWZvcm0nO1xuaW1wb3J0IHsgaW5pdFBpY3R1cmVGb3JtIH0gZnJvbSAnLi9qcy9pbml0L3BpY3R1cmUtZm9ybSc7XG5pbXBvcnQgeyBpbml0Q2hhdE1vZGFsIH0gZnJvbSAnLi9qcy9pbml0L2V2ZW50LWNoYXQtaW5pdCc7XG5pbXBvcnQgeyBpbml0VXNlcm5hbWVBdmFpbGFiaWxpdHlDaGVjayB9IGZyb20gJy4vanMvaW5pdC9wcm9maWxlLXVzZXJuYW1lLWNoZWNrJztcbmltcG9ydCB7IGluaXRQcmVzZW50YXRpb25Td2lwZXIgfSBmcm9tICcuL2pzL2luaXQvcHJlc2VudGF0aW9uLXN3aXBlcic7XG5pbXBvcnQgeyBpbml0UHJvZmlsZVNoYXJlIH0gZnJvbSAnLi9qcy9pbml0L3VzZXJzLXByb2ZpbGUtc2hhcmUnO1xuaW1wb3J0IHsgaW5pdEJvb2ttYXJrVG9nZ2xlIH0gZnJvbSAnLi9qcy9pbml0L2V2ZW50cy1pdGVyYXRpb24tYm9va21hcmstdG9nZ2xlJztcbmltcG9ydCB7IGluaXRBY2NvdW50SW5mb0JveCB9IGZyb20gJy4vanMvaW5pdC9hY2NvdW50LWluZm8tYm94JztcbmltcG9ydCB7IGluaXRJbnRybyB9IGZyb20gJy4vanMvaW5pdC9pbnRyby1pbml0JztcbmltcG9ydCB7IGluaXRBZGRMdXhNZW51IH0gZnJvbSAnLi9qcy9pbml0L2FkZC1sdXgtbWVudSc7XG5pbXBvcnQgeyBpbml0U2hhcmVCdXR0b24gfSBmcm9tICcuL2pzL2luaXQvc2hhcmUtYnV0dG9uJztcbmltcG9ydCB7IGluaXRTYXZlQnV0dG9uQ291bnRkb3duIH0gZnJvbSAnLi9qcy9pbml0L2luaXQtc2F2ZS1idXR0b24nO1xuaW1wb3J0IHsgaW5pdEdvYWxQZXJjZW50Q2hhcnRzIH0gZnJvbSAnLi9qcy9pbml0L2dvYWwtcGVyY2VudC1jaGFydHMnO1xuXG4vLyBSRUFDVFxuaW1wb3J0IFJlYWN0IGZyb20gJ3JlYWN0JztcbmltcG9ydCB7IGNyZWF0ZVJvb3QgfSBmcm9tICdyZWFjdC1kb20vY2xpZW50JztcbmltcG9ydCBBcHAgZnJvbSAnLi9yZWFjdC9BcHAnO1xuXG4vLyA9PT09PT09PT09PT09PT09PT09PT09PVxuLy8gUkVBQ1QgSU5JVFxuLy8gPT09PT09PT09PT09PT09PT09PT09PT1cbmNvbnN0IHJvb3RFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdyZWFjdC1tYXRyaXgtcm9vdCcpO1xuaWYgKHJvb3RFbCkge1xuICAgIGNvbnN0IHJvb3QgPSBjcmVhdGVSb290KHJvb3RFbCk7XG4gICAgcm9vdC5yZW5kZXIoPEFwcCAvPik7XG59XG5cbi8vID09PT09PT09PT09PT09PT09PT09PT09XG4vLyBDSEFUIEFJIElOVFJPIFRZUElORyAmIENMRUFOVVBcbi8vID09PT09PT09PT09PT09PT09PT09PT09XG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCd0dXJibzpiZWZvcmUtY2FjaGUnLCAoKSA9PiB7XG4gICAgLy8g0J7Rh9C40YHRgtC60LAg0YfQsNGC0LAgKNCy0LDRiNCwINGC0LXQutGD0YnQsNGPINC70L7Qs9C40LrQsClcbiAgICBjb25zdCBvdXRwdXRFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdvdXRwdXQnKTtcbiAgICBpZiAob3V0cHV0RWwpIHtcbiAgICAgICAgb3V0cHV0RWwudGV4dENvbnRlbnQgPSAnJztcbiAgICAgICAgb3V0cHV0RWwuY2xhc3NMaXN0LnJlbW92ZSgnaXMtdHlwaW5nJyk7XG4gICAgICAgIGRlbGV0ZSBvdXRwdXRFbC5kYXRhc2V0LmluaXRpYWxpemVkO1xuICAgIH1cblxuICAgIC8vIC0tLSDQlNCe0JHQkNCS0JjQotCsINCt0KLQniAtLS1cbiAgICAvLyDQntGH0LjRgdGC0LrQsCDRgtC10YHRgtC+0LLQvtC5INC60L3QvtC/0LrQuCDQvtCy0LXRgNC70LXRj1xuICAgIGNvbnN0IHRlc3RCdG4gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYWktb3ZlcmxheS10ZXN0LWJ0bicpO1xuICAgIGlmICh0ZXN0QnRuKSB7XG4gICAgICAgIGRlbGV0ZSB0ZXN0QnRuLmRhdGFzZXQuaW5pdGlhbGl6ZWQ7XG4gICAgfVxuXG4gICAgLy8g0J7Rh9C40YHRgtC60LAg0YHQsNC80L7Qs9C+INC+0LLQtdGA0LvQtdGPICjQvdCwINCy0YHRj9C60LjQuSDRgdC70YPRh9Cw0Lkg0YHQutGA0YvQstCw0LXQvCDQtdCz0L4g0L/QtdGA0LXQtCDQutGN0YjQuNGA0L7QstCw0L3QuNC10LwpXG4gICAgY29uc3Qgb3ZlcmxheSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhaS1sb2FkaW5nLW92ZXJsYXknKTtcbiAgICBpZiAob3ZlcmxheSkge1xuICAgICAgICBvdmVybGF5LmNsYXNzTGlzdC5hZGQoJ2Qtbm9uZScpO1xuICAgIH1cbn0pO1xuZnVuY3Rpb24gaW5pdENoYXRJbnRyb1R5cGluZygpIHtcbiAgICBjb25zdCBvdXRwdXRFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdvdXRwdXQnKTtcbiAgICBpZiAoIW91dHB1dEVsIHx8IG91dHB1dEVsLmRhdGFzZXQuaW5pdGlhbGl6ZWQpIHJldHVybjtcblxuICAgIG91dHB1dEVsLmRhdGFzZXQuaW5pdGlhbGl6ZWQgPSAndHJ1ZSc7XG4gICAgb3V0cHV0RWwuY2xhc3NMaXN0LmFkZCgnaXMtdHlwaW5nJyk7IC8vIOKGkCDQktCQ0JbQndCeXG5cbiAgICBpbml0VHlwZXdyaXRlcih7XG4gICAgICAgIHNlbGVjdG9yOiAnI291dHB1dCcsXG4gICAgICAgIHRleHQ6IG91dHB1dEVsLmRhdGFzZXQudGV4dCxcbiAgICAgICAgc3BlZWQ6IDgwXG4gICAgfSk7XG59XG5cbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3R1cmJvOnN1Ym1pdC1zdGFydCcsIChlKSA9PiB7XG4gICAgaWYgKGUudGFyZ2V0LmlkID09PSAnY2hhdC1mb3JtJykge1xuICAgICAgICBjb25zdCBvdmVybGF5ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2FpLWxvYWRpbmctb3ZlcmxheScpO1xuICAgICAgICBpZiAob3ZlcmxheSkgb3ZlcmxheS5jbGFzc0xpc3QucmVtb3ZlKCdkLW5vbmUnKTtcbiAgICB9XG59KTtcbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3R1cmJvOnN1Ym1pdC1lbmQnLCAoKSA9PiB7XG4gICAgY29uc3Qgb3ZlcmxheSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhaS1sb2FkaW5nLW92ZXJsYXknKTtcbiAgICBpZiAob3ZlcmxheSkgb3ZlcmxheS5jbGFzc0xpc3QuYWRkKCdkLW5vbmUnKTtcbn0pO1xuXG4vLyA9PT09PT09PT09PT09PT09PT09PT09PVxuLy8gQUkgTE9BRElORyBPVkVSTEFZXG4vLyA9PT09PT09PT09PT09PT09PT09PT09PVxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigndHVyYm86c3VibWl0LXN0YXJ0JywgKGV2ZW50KSA9PiB7XG4gICAgLy8g0J/RgNC+0LLQtdGA0Y/QtdC8LCDRh9GC0L4g0L7RgtC/0YDQsNCy0LvRj9C10YLRgdGPINC40LzQtdC90L3QviDQvdCw0YjQsCDRhNC+0YDQvNCwINCw0L3QsNC70LjQt9CwXG4gICAgaWYgKGV2ZW50LnRhcmdldC5pZCA9PT0gJ2FpLWFuYWx5c2lzLWZvcm0nKSB7XG4gICAgICAgIGNvbnN0IG92ZXJsYXkgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYWktbG9hZGluZy1vdmVybGF5Jyk7XG4gICAgICAgIGlmIChvdmVybGF5KSB7XG4gICAgICAgICAgICBvdmVybGF5LmNsYXNzTGlzdC5yZW1vdmUoJ2Qtbm9uZScpO1xuICAgICAgICB9XG4gICAgfVxufSk7XG4vLyAo0J7Qv9GG0LjQvtC90LDQu9GM0L3Qvikg0KHQutGA0YvQstCw0YLRjCDQvtCy0LXRgNC70LXQuSwg0LXRgdC70Lgg0YHQtdGA0LLQtdGAINCy0LXRgNC90YPQuyDQvtGI0LjQsdC60YMg0LjQu9C4INC+0YLQv9GA0LDQstC60LAg0LfQsNCy0LXRgNGI0LjQu9Cw0YHRjCDQsdC10Lcg0L/QtdGA0LXRhdC+0LTQsFxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigndHVyYm86c3VibWl0LWVuZCcsIChldmVudCkgPT4ge1xuICAgIGlmIChldmVudC50YXJnZXQuaWQgPT09ICdhaS1hbmFseXNpcy1mb3JtJykge1xuICAgICAgICAvLyDQldGB0LvQuCDRg9GB0L/QtdGI0L3Ri9C5INC/0LXRgNC10YXQvtC0LCBUdXJibyDRgdCw0Lwg0LfQsNC80LXQvdC40YIgYm9keSDQuCDQvtCy0LXRgNC70LXQuSDQuNGB0YfQtdC30L3QtdGCLlxuICAgICAgICAvLyDQndC+INC10YHQu9C4INCx0YPQtNC10YIg0L7RiNC40LHQutCwINCy0LDQu9C40LTQsNGG0LjQuCAoNDIyKSDQuNC70Lgg0YDQtdC00LjRgNC10LrRgiDQvdC1INGB0YDQsNCx0L7RgtCw0LssXG4gICAgICAgIC8vINC90YPQttC90L4g0YHQutGA0YvRgtGMINC+0LLQtdGA0LvQtdC5INCy0YDRg9GH0L3Rg9GOLCDRh9GC0L7QsdGLINC40L3RgtC10YDRhNC10LnRgSDQvdC1INC30LDQstC40YEuXG4gICAgICAgIGNvbnN0IG92ZXJsYXkgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYWktbG9hZGluZy1vdmVybGF5Jyk7XG4gICAgICAgIC8vINCf0YDQvtCy0LXRgNGP0LXQvCDRg9GB0L/QtdGFIChldmVudC5kZXRhaWwuZm9ybVN1Ym1pc3Npb24ucmVzdWx0LnN1Y2Nlc3MpLCDQvdC+INC00LvRjyDQv9GA0L7RgdGC0L7RgtGLINC80L7QttC90L4g0L/RgNC+0YHRgtC+INGB0LrRgNGL0YLRjDpcbiAgICAgICAgaWYgKG92ZXJsYXkgJiYgIWV2ZW50LmRldGFpbC5mb3JtU3VibWlzc2lvbi5yZXN1bHQuc3VjY2Vzcykge1xuICAgICAgICAgICAgb3ZlcmxheS5jbGFzc0xpc3QuYWRkKCdkLW5vbmUnKTtcbiAgICAgICAgfVxuICAgIH1cbn0pO1xuLy8gPT09PT09PT09PT09PT09PT09PT09PT1cbi8vIE1BSU4gSU5JVFxuLy8gPT09PT09PT09PT09PT09PT09PT09PT1cbmV4cG9ydCBmdW5jdGlvbiBpbml0QWxsKCkge1xuICAgIGluaXRBY2NvdW50TGlua3MoKTtcbiAgICBpbml0SW5mb0JveCgpO1xuICAgIGluaXRCb3R0b21BZGRNZW51VG9nZ2xlKCk7XG4gICAgaW5pdE1hdHJpeFNob3dJdGVtKCk7XG4gICAgaW5pdENLRWRpdG9yKCk7XG4gICAgaW5pdE1hdHJpeEl0ZW1JbWFnZVVwbG9hZCgpO1xuICAgIGluaXRNYXRyaXhJdGVtRGVsZXRlTW9kYWwoKTtcbiAgICBpbml0Q29tbWVudHMoKTtcbiAgICBpbml0Q2hhdE1vZGFsKCk7XG4gICAgaW5pdFVzZXJuYW1lQXZhaWxhYmlsaXR5Q2hlY2soKTtcbiAgICBpbml0UHJlc2VudGF0aW9uU3dpcGVyKCk7XG4gICAgaW5pdFByb2ZpbGVTaGFyZSgpO1xuICAgIGluaXRCb29rbWFya1RvZ2dsZSgpO1xuICAgIGluaXRBY2NvdW50SW5mb0JveCgpO1xuICAgIGluaXRJbnRybygpO1xuICAgIGluaXRBZGRMdXhNZW51KCk7XG4gICAgaW5pdFNoYXJlQnV0dG9uKCk7XG4gICAgaW5pdEFjdGl2aXR5Q2hhcnQoKTtcbiAgICBpbml0U21hbGxTdGVwc0NoYXJ0KCk7XG4gICAgaW5pdFdlZWtseVNjb3JlQ2hhcnQoKTtcbiAgICBpbml0R29hbFBlcmNlbnRDaGFydHMoKTtcblxuICAgIC8vIOKchSBBSSBJTlRSTyAo0LLQsNC20L3Qvjog0JLQndCj0KLQoNCYIGluaXRBbGwpXG4gICAgaW5pdENoYXRJbnRyb1R5cGluZygpO1xuXG4gICAgY29uc3QgcGljdHVyZUZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdmb3JtW2RhdGEtZm9ybS10eXBlPVwicGljdHVyZVwiXScpO1xuICAgIGlmIChwaWN0dXJlRm9ybSkgaW5pdFBpY3R1cmVGb3JtKCk7XG5cbiAgICBjb25zdCBzaW1wbGVGb3JtID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignZm9ybVtkYXRhLWZvcm0tdHlwZT1cInNpbXBsZVwiXScpO1xuICAgIGlmIChzaW1wbGVGb3JtKSBpbml0U2ltcGxlRm9ybSgpO1xuXG4gICAgY29uc3Qgam91cm5hbEZvcm0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdmb3JtW2RhdGEtZm9ybS10eXBlPVwiam91cm5hbFwiXScpO1xuICAgIGlmIChqb3VybmFsRm9ybSkgaW5pdFNpbXBsZUZvcm0oKTtcblxuICAgIGNvbnN0IGV2ZW50Rm9ybSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2Zvcm1bZGF0YS1mb3JtLWNvbnRleHQ9XCJldmVudFwiXScpO1xuICAgIGlmIChldmVudEZvcm0pIGluaXRFdmVudEZvcm0oKTtcblxuICAgIGluaXRFdmVudHNGZWVkKCk7XG5cbiAgICBjb25zdCBjaGF0QnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NoYXRCdXR0b24nKTtcbiAgICBpZiAoY2hhdEJ1dHRvbikge1xuICAgICAgICBjb25zdCBwcm9maWxlSWQgPSBjaGF0QnV0dG9uLmRhdGFzZXQucHJvZmlsZUlkO1xuICAgICAgICBpbml0UHJvZmlsZUNoYXQoXG4gICAgICAgICAgICBwcm9maWxlSWQsXG4gICAgICAgICAgICBgL2NwYW5lbC9jaGF0L2NoZWNrLyR7cHJvZmlsZUlkfWAsXG4gICAgICAgICAgICBgL2NwYW5lbC9jaGF0L25ldy8ke3Byb2ZpbGVJZH1gXG4gICAgICAgICk7XG4gICAgfVxuXG4gICAgaW5pdFNhdmVCdXR0b25Db3VudGRvd24oKTtcbn1cblxuLy8gPT09PT09PT09PT09PT09PT09PT09PT1cbi8vIFRVUkJPIEVOVFJZIFBPSU5UXG4vLyA9PT09PT09PT09PT09PT09PT09PT09PVxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigndHVyYm86bG9hZCcsIGluaXRBbGwpO1xuIiwiaW1wb3J0IHsgc3RhcnRTdGltdWx1c0FwcCB9IGZyb20gJ0BzeW1mb255L3N0aW11bHVzLWJyaWRnZSc7XG5cbi8vIFJlZ2lzdGVycyBTdGltdWx1cyBjb250cm9sbGVycyBmcm9tIGNvbnRyb2xsZXJzLmpzb24gYW5kIGluIHRoZSBjb250cm9sbGVycy8gZGlyZWN0b3J5XG5leHBvcnQgY29uc3QgYXBwID0gc3RhcnRTdGltdWx1c0FwcChyZXF1aXJlLmNvbnRleHQoXG4gICAgJ0BzeW1mb255L3N0aW11bHVzLWJyaWRnZS9sYXp5LWNvbnRyb2xsZXItbG9hZGVyIS4vY29udHJvbGxlcnMnLFxuICAgIHRydWUsXG4gICAgL1xcLltqdF1zeD8kL1xuKSk7XG5cbi8vIHJlZ2lzdGVyIGFueSBjdXN0b20sIDNyZCBwYXJ0eSBjb250cm9sbGVycyBoZXJlXG4vLyBhcHAucmVnaXN0ZXIoJ3NvbWVfY29udHJvbGxlcl9uYW1lJywgU29tZUltcG9ydGVkQ29udHJvbGxlcik7XG4iLCJleHBvcnQgZnVuY3Rpb24gaW5pdEFjY291bnRJbmZvQm94KCkge1xyXG4gICAgZnVuY3Rpb24gaW5pdFNjcm9sbENvbnRhaW5lcigpIHtcclxuICAgICAgICBjb25zdCBzY3JvbGxDb250YWluZXIgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc2Nyb2xsQ29udGFpbmVyJyk7XHJcbiAgICAgICAgaWYgKHNjcm9sbENvbnRhaW5lcikge1xyXG4gICAgICAgICAgICBzY3JvbGxDb250YWluZXIuc2Nyb2xsVG8oe1xyXG4gICAgICAgICAgICAgICAgbGVmdDogNjAsXHJcbiAgICAgICAgICAgICAgICBiZWhhdmlvcjogJ2F1dG8nIC8vIOKGkCDQvNCz0L3QvtCy0LXQvdC90L4sINCx0LXQtyDQsNC90LjQvNCw0YbQuNC4XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGluaXRTY3JvbGxDb250YWluZXIpO1xyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigndHVyYm86bG9hZCcsIGluaXRTY3JvbGxDb250YWluZXIpO1xyXG59IiwiZXhwb3J0IGZ1bmN0aW9uIGluaXRBY2NvdW50TGlua3MoKSB7XHJcbiAgICAvLyDQrdGC0L4g0LLRgdGRINC90YPQttC90L4g0L3QsCBhcHBfYWNjb3VudF9saXN0IC0g0KfRgtC+0LHRiyDQv9C+INC60LDRgtC10LPQvtGA0Y/QvCDQutC70LjQutCw0YLRjFxyXG4gICAgY29uc3QgYm9va21hcmtMaW5rID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJib29rbWFya0xpbmtcIik7XHJcbiAgICBpZiAoYm9va21hcmtMaW5rKSB7XHJcbiAgICAgICAgYm9va21hcmtMaW5rLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gYm9va21hcmtMaW5rLmRhdGFzZXQudXJsO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGNvbnN0IGRlZmF1bHRMaW5rID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJkZWZhdWx0TGlua1wiKTtcclxuICAgIGlmIChkZWZhdWx0TGluaykge1xyXG4gICAgICAgIGRlZmF1bHRMaW5rLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gZGVmYXVsdExpbmsuZGF0YXNldC51cmw7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgY29uc3QgZmVhdHVyZWRMaW5rID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJmZWF0dXJlZExpbmtcIik7XHJcbiAgICBpZiAoZmVhdHVyZWRMaW5rKSB7XHJcbiAgICAgICAgZmVhdHVyZWRMaW5rLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gZmVhdHVyZWRMaW5rLmRhdGFzZXQudXJsO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCIuY2xpY2thYmxlXCIpLmZvckVhY2goY2VsbCA9PiB7XHJcbiAgICAgICAgY2VsbC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB1cmwgPSBjZWxsLmdldEF0dHJpYnV0ZShcImRhdGEtdXJsXCIpO1xyXG4gICAgICAgICAgICBpZiAodXJsKSB7XHJcbiAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24uaHJlZiA9IHVybDtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgfSk7XHJcbn1cclxuIiwiaW1wb3J0IENoYXJ0IGZyb20gJ2NoYXJ0LmpzL2F1dG8nO1xyXG5cclxubGV0IGFjdGl2aXR5Q2hhcnQgPSBudWxsO1xyXG5cclxuZXhwb3J0IGZ1bmN0aW9uIGluaXRBY3Rpdml0eUNoYXJ0KCkge1xyXG4gICAgY29uc3QgY2hhcnRDYW52YXMgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXZlbnRzQ2hhcnQnKTtcclxuICAgIGlmICghY2hhcnRDYW52YXMpIHJldHVybjtcclxuXHJcbiAgICBjb25zdCBwcm9maWxlSWQgPSBjaGFydENhbnZhcy5kYXRhc2V0LnByb2ZpbGVJZDtcclxuICAgIGNvbnN0IHllYXIgPSBjaGFydENhbnZhcy5kYXRhc2V0LnllYXI7XHJcblxyXG4gICAgZmV0Y2goYC9jcGFuZWwvcHJvZ3Jlc3MvYWN0aXZpdHkvYXBpL3N0YXRpc3RpY3MvJHtwcm9maWxlSWR9P3llYXI9JHt5ZWFyfWApXHJcbiAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4gcmVzcG9uc2UuanNvbigpKVxyXG4gICAgICAgIC50aGVuKGRhdGEgPT4ge1xyXG5cclxuICAgICAgICAgICAgY29uc3QgZXZlbnRMYWJlbHMgPSBkYXRhLmV2ZW50cy5tYXAoaXRlbSA9PiBpdGVtLm1vbnRoKTtcclxuICAgICAgICAgICAgY29uc3QgZXZlbnRWYWx1ZXMgPSBkYXRhLmV2ZW50cy5tYXAoaXRlbSA9PiBpdGVtLmNvdW50KTtcclxuXHJcbiAgICAgICAgICAgIC8vIPCflKUg0JrQm9Cu0KfQldCS0J7QlSDQnNCV0KHQotCeXHJcbiAgICAgICAgICAgIGlmIChhY3Rpdml0eUNoYXJ0KSB7XHJcbiAgICAgICAgICAgICAgICBhY3Rpdml0eUNoYXJ0LmRlc3Ryb3koKTtcclxuICAgICAgICAgICAgICAgIGFjdGl2aXR5Q2hhcnQgPSBudWxsO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBhY3Rpdml0eUNoYXJ0ID0gbmV3IENoYXJ0KGNoYXJ0Q2FudmFzLmdldENvbnRleHQoJzJkJyksIHtcclxuICAgICAgICAgICAgICAgIHR5cGU6ICdiYXInLFxyXG4gICAgICAgICAgICAgICAgZGF0YToge1xyXG4gICAgICAgICAgICAgICAgICAgIGxhYmVsczogZXZlbnRMYWJlbHMsXHJcbiAgICAgICAgICAgICAgICAgICAgZGF0YXNldHM6IFt7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxhYmVsOiBg0JrQvtC70LjRh9C10YHRgtCy0L4g0LLRi9C/0L7Qu9C90LXQvdC90YvRhSDQt9Cw0L/QuNGB0LXQuSAoJHt5ZWFyfSlgLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICBkYXRhOiBldmVudFZhbHVlcyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgYmFja2dyb3VuZENvbG9yOiAncmdiYSg3NSwgMTkyLCAxOTIsIDAuMiknLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICBib3JkZXJDb2xvcjogJ3JnYmEoNzUsIDE5MiwgMTkyLCAxKScsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGJvcmRlcldpZHRoOiAxXHJcbiAgICAgICAgICAgICAgICAgICAgfV1cclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICBvcHRpb25zOiB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmVzcG9uc2l2ZTogdHJ1ZSxcclxuICAgICAgICAgICAgICAgICAgICBtYWludGFpbkFzcGVjdFJhdGlvOiBmYWxzZSxcclxuICAgICAgICAgICAgICAgICAgICBhbmltYXRpb246IGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIHNjYWxlczoge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB5OiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBiZWdpbkF0WmVybzogdHJ1ZVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxufSIsImV4cG9ydCBmdW5jdGlvbiBpbml0QWRkTHV4TWVudSh0b2dnbGVTZWxlY3RvciA9ICcjYWRkTWVudVRvZ2dsZScsIG92ZXJsYXlTZWxlY3RvciA9ICcjYWRkTWVudU92ZXJsYXknKSB7XHJcbiAgICBjb25zdCB0b2dnbGVCdXR0b24gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHRvZ2dsZVNlbGVjdG9yKTtcclxuICAgIGNvbnN0IG92ZXJsYXkgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKG92ZXJsYXlTZWxlY3Rvcik7XHJcbiAgICBjb25zdCBjbG9zZUJ0biA9IG92ZXJsYXk/LnF1ZXJ5U2VsZWN0b3IoJyNjbG9zZUFkZE1lbnUnKTtcclxuXHJcbiAgICBpZiAoIXRvZ2dsZUJ1dHRvbiB8fCAhb3ZlcmxheSkge1xyXG4gICAgICAgIGNvbnNvbGUud2FybihcIkFkZCBtZW51IGVsZW1lbnRzIG5vdCBmb3VuZFwiKTtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG4gICAgY29uc3QgbmV3VG9nZ2xlQnV0dG9uID0gdG9nZ2xlQnV0dG9uLmNsb25lTm9kZSh0cnVlKTtcclxuICAgIHRvZ2dsZUJ1dHRvbi5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZChuZXdUb2dnbGVCdXR0b24sIHRvZ2dsZUJ1dHRvbik7XHJcblxyXG4gICAgbGV0IGlzTWVudU9wZW4gPSAhb3ZlcmxheS5jbGFzc0xpc3QuY29udGFpbnMoJ2hpZGRlbicpO1xyXG5cclxuICAgIG5ld1RvZ2dsZUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuICAgICAgICBpZiAoIWlzTWVudU9wZW4pIHtcclxuICAgICAgICAgICAgb3ZlcmxheS5jbGFzc0xpc3QucmVtb3ZlKCdoaWRkZW4nKTtcclxuICAgICAgICAgICAgaXNNZW51T3BlbiA9IHRydWU7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgb3ZlcmxheS5jbGFzc0xpc3QuYWRkKCdoaWRkZW4nKTtcclxuICAgICAgICAgICAgaXNNZW51T3BlbiA9IGZhbHNlO1xyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG5cclxuICAgIGlmIChjbG9zZUJ0bikge1xyXG4gICAgICAgIGNsb3NlQnRuLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBvdmVybGF5LmNsYXNzTGlzdC5hZGQoJ2hpZGRlbicpO1xyXG4gICAgICAgICAgICBpc01lbnVPcGVuID0gZmFsc2U7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgIGNvbnN0IGNsaWNrZWRJbnNpZGUgPSBvdmVybGF5LmNvbnRhaW5zKGUudGFyZ2V0KSB8fCBuZXdUb2dnbGVCdXR0b24uY29udGFpbnMoZS50YXJnZXQpO1xyXG4gICAgICAgIGlmICghY2xpY2tlZEluc2lkZSAmJiBpc01lbnVPcGVuKSB7XHJcbiAgICAgICAgICAgIG92ZXJsYXkuY2xhc3NMaXN0LmFkZCgnaGlkZGVuJyk7XHJcbiAgICAgICAgICAgIGlzTWVudU9wZW4gPSBmYWxzZTtcclxuICAgICAgICB9XHJcbiAgICB9KTtcclxufSIsImV4cG9ydCBmdW5jdGlvbiBpbml0Qm90dG9tQWRkTWVudVRvZ2dsZSgpIHtcclxuICAgIGNvbnN0IHRvZ2dsZUJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhZGRNZW51VG9nZ2xlJyk7XHJcbiAgICBjb25zdCBtZW51V3JhcHBlciA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdhZGRNZW51Jyk7XHJcblxyXG4gICAgaWYgKCF0b2dnbGVCdXR0b24gfHwgIW1lbnVXcmFwcGVyKSByZXR1cm47XHJcblxyXG4gICAgbGV0IGlzTWVudU9wZW4gPSBmYWxzZTtcclxuXHJcbiAgICAvLyDQodC90LjQvNCw0LXQvCDRgdGC0LDRgNGL0LUg0L7QsdGA0LDQsdC+0YLRh9C40LrQuCAo0LIg0YHQu9GD0YfQsNC1IGhvdCByZWxvYWQg0LjQu9C4IHR1cmJvKVxyXG4gICAgY29uc3QgbmV3VG9nZ2xlQnV0dG9uID0gdG9nZ2xlQnV0dG9uLmNsb25lTm9kZSh0cnVlKTtcclxuICAgIHRvZ2dsZUJ1dHRvbi5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZChuZXdUb2dnbGVCdXR0b24sIHRvZ2dsZUJ1dHRvbik7XHJcblxyXG4gICAgbmV3VG9nZ2xlQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcclxuICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG4gICAgICAgIGlmICghaXNNZW51T3Blbikge1xyXG4gICAgICAgICAgICBtZW51V3JhcHBlci5jbGFzc0xpc3QucmVtb3ZlKCdkLW5vbmUnKTtcclxuICAgICAgICAgICAgc2V0VGltZW91dCgoKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBtZW51V3JhcHBlci5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIGlzTWVudU9wZW4gPSB0cnVlO1xyXG4gICAgICAgICAgICB9LCAxMCk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgbWVudVdyYXBwZXIuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIHNldFRpbWVvdXQoKCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgbWVudVdyYXBwZXIuY2xhc3NMaXN0LmFkZCgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgICAgICBpc01lbnVPcGVuID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0sIDIwMCk7XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcblxyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgIGNvbnN0IGNsaWNrZWRJbnNpZGUgPSBtZW51V3JhcHBlci5jb250YWlucyhlLnRhcmdldCkgfHwgbmV3VG9nZ2xlQnV0dG9uLmNvbnRhaW5zKGUudGFyZ2V0KTtcclxuXHJcbiAgICAgICAgaWYgKCFjbGlja2VkSW5zaWRlICYmIGlzTWVudU9wZW4pIHtcclxuICAgICAgICAgICAgbWVudVdyYXBwZXIuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIHNldFRpbWVvdXQoKCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgbWVudVdyYXBwZXIuY2xhc3NMaXN0LmFkZCgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgICAgICBpc01lbnVPcGVuID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0sIDIwMCk7XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn0iLCJleHBvcnQgZnVuY3Rpb24gaW5pdENLRWRpdG9yKCkge1xyXG4gICAgaWYgKHR5cGVvZiBDS0VESVRPUiA9PT0gJ3VuZGVmaW5lZCcpIHtcclxuICAgICAgICBjb25zb2xlLndhcm4oJ0NLRWRpdG9yIG5vdCBsb2FkZWQnKTtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG4gICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgndGV4dGFyZWFbZGF0YS1ja2VkaXRvcl0nKS5mb3JFYWNoKCh0ZXh0YXJlYSkgPT4ge1xyXG4gICAgICAgIGNvbnN0IG5hbWUgPSB0ZXh0YXJlYS5nZXRBdHRyaWJ1dGUoJ25hbWUnKTtcclxuXHJcbiAgICAgICAgLy8g0J3QtSDQtNGD0LHQu9C40YDRg9C10LwsINC10YHQu9C4INGD0LbQtSDQuNC90LjRhtC40LDQu9C40LfQuNGA0L7QstCw0L1cclxuICAgICAgICBpZiAoIXRleHRhcmVhLmRhdGFzZXQuY2tlZGl0b3JJbml0aWFsaXplZCAmJiBuYW1lKSB7XHJcbiAgICAgICAgICAgIC8vINCf0YDQvtCy0LXRgNC40LwsINC90LUg0LHRi9C7INC70LggQ0tFZGl0b3Ig0YPQttC1INC/0YDQuNC60YDQtdC/0LvRkdC9INC6IG5hbWVcclxuICAgICAgICAgICAgaWYgKENLRURJVE9SLmluc3RhbmNlc1tuYW1lXSkge1xyXG4gICAgICAgICAgICAgICAgQ0tFRElUT1IuaW5zdGFuY2VzW25hbWVdLmRlc3Ryb3kodHJ1ZSk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIENLRURJVE9SLnJlcGxhY2UodGV4dGFyZWEpO1xyXG4gICAgICAgICAgICB0ZXh0YXJlYS5kYXRhc2V0LmNrZWRpdG9ySW5pdGlhbGl6ZWQgPSAndHJ1ZSc7XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn0iLCJleHBvcnQgZnVuY3Rpb24gaW5pdENoYXRNb2RhbCgpIHtcclxuICAgIGNvbnN0IGNoYXRCdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2hhdEJ1dHRvbicpO1xyXG4gICAgY29uc3QgY2hhdFRleHQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2hhdFRleHQnKTtcclxuICAgIGNvbnN0IGNvbmZpcm1Nb2RhbEVsID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NvbmZpcm1Nb2RhbCcpO1xyXG4gICAgY29uc3QgY29uZmlybUJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjb25maXJtQnV0dG9uJyk7XHJcblxyXG4gICAgaWYgKCFjaGF0QnV0dG9uIHx8ICFjb25maXJtQnV0dG9uIHx8ICFjb25maXJtTW9kYWxFbCB8fCAhY2hhdFRleHQpIHtcclxuICAgICAgICBjb25zb2xlLndhcm4oJ+KdjCDQntC00LjQvSDQuNC3INGN0LvQtdC80LXQvdGC0L7QsiDRh9Cw0YLQsCDQvdC1INC90LDQudC00LXQvScpO1xyXG4gICAgICAgIHJldHVybjtcclxuICAgIH1cclxuXHJcbiAgICBsZXQgY29uZmlybU1vZGFsO1xyXG4gICAgdHJ5IHtcclxuICAgICAgICBjb25maXJtTW9kYWwgPSBuZXcgYm9vdHN0cmFwLk1vZGFsKGNvbmZpcm1Nb2RhbEVsLCB7fSk7XHJcbiAgICB9IGNhdGNoIChlKSB7XHJcbiAgICAgICAgY29uc29sZS5lcnJvcign0J3QtSDRg9C00LDQu9C+0YHRjCDRgdC+0LfQtNCw0YLRjCBib290c3RyYXAuTW9kYWw6JywgZSk7XHJcbiAgICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG5cclxuICAgIGNvbnN0IGNoZWNrVXJsID0gY2hhdEJ1dHRvbi5kYXRhc2V0LmNoYXRDaGVja1VybDtcclxuICAgIGNvbnN0IGNyZWF0ZVVybCA9IGNoYXRCdXR0b24uZGF0YXNldC5jaGF0Q3JlYXRlVXJsO1xyXG5cclxuICAgIGNoYXRCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoKSA9PiB7XHJcbiAgICAgICAgY2hhdFRleHQudGV4dENvbnRlbnQgPSAnTG9hZGluZy4uLic7XHJcblxyXG4gICAgICAgIGZldGNoKGNoZWNrVXJsKVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpXHJcbiAgICAgICAgICAgIC50aGVuKGRhdGEgPT4ge1xyXG4gICAgICAgICAgICAgICAgaWYgKGRhdGEucmVzdWx0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBjcmVhdGVVcmw7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbmZpcm1Nb2RhbC5zaG93KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgY2hhdFRleHQudGV4dENvbnRlbnQgPSAnU2VuZCBhIG1lc3NhZ2UnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAuY2F0Y2goZXJyb3IgPT4ge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5lcnJvcign0J7RiNC40LHQutCwINC/0YDQuCDQv9GA0L7QstC10YDQutC1INGH0LDRgtCwOicsIGVycm9yKTtcclxuICAgICAgICAgICAgICAgIGNoYXRUZXh0LnRleHRDb250ZW50ID0gJ1NlbmQgYSBtZXNzYWdlJztcclxuICAgICAgICAgICAgfSk7XHJcbiAgICB9KTtcclxuXHJcbiAgICBjb25maXJtQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xyXG4gICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gY3JlYXRlVXJsO1xyXG4gICAgfSk7XHJcbn0iLCJleHBvcnQgZnVuY3Rpb24gaW5pdENvbW1lbnRzKCkge1xyXG4gICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmJ0bi1yZXBseScpLmZvckVhY2goYnV0dG9uID0+IHtcclxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIGNvbnN0IGNvbW1lbnRJZCA9IHRoaXMuZ2V0QXR0cmlidXRlKCdkYXRhLWNvbW1lbnQtaWQnKTtcclxuICAgICAgICAgICAgY29uc3QgY29tbWVudFRleHQgPSB0aGlzLmNsb3Nlc3QoJy5ibG9nLWNvbW1lbnRzX19jb250ZW50JylcclxuICAgICAgICAgICAgICAgIC5xdWVyeVNlbGVjdG9yKCdwLmNvbW1lbnQtdGV4dCcpXHJcbiAgICAgICAgICAgICAgICAudGV4dENvbnRlbnQudHJpbSgpO1xyXG5cclxuICAgICAgICAgICAgY29uc3QgZm9ybSA9IHRoaXMuY2xvc2VzdCgnLmNhcmQtYm9keScpLnF1ZXJ5U2VsZWN0b3IoJy5jb21tZW50LWZvcm0nKTtcclxuICAgICAgICAgICAgY29uc3QgcmVwbHlDb250YWluZXIgPSBmb3JtLnF1ZXJ5U2VsZWN0b3IoJy5yZXBseS1jb250YWluZXInKTtcclxuICAgICAgICAgICAgY29uc3QgcGFyZW50SW5wdXQgPSBmb3JtLnF1ZXJ5U2VsZWN0b3IoJyNwYXJlbnRDb21tZW50SWQnKTtcclxuXHJcbiAgICAgICAgICAgIC8vINCe0YfQuNGB0YLQutCwINC/0YDQtdC00YvQtNGD0YnQuNGFINC+0YLQstC10YLQvtCyXHJcbiAgICAgICAgICAgIHJlcGx5Q29udGFpbmVyLmlubmVySFRNTCA9ICcnO1xyXG4gICAgICAgICAgICBwYXJlbnRJbnB1dC52YWx1ZSA9IGNvbW1lbnRJZDtcclxuXHJcbiAgICAgICAgICAgIC8vINCU0L7QsdCw0LLQu9C10L3QuNC1INCy0LjQtNC40LzQvtCz0L4gaW5wdXQg0YEg0YLQtdC60YHRgtC+0Lwg0YDQvtC00LjRgtC10LvRjNGB0LrQvtCz0L4g0LrQvtC80LzQtdC90YLQsNGA0LjRj1xyXG4gICAgICAgICAgICBjb25zdCByZXBseUlucHV0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcclxuICAgICAgICAgICAgcmVwbHlJbnB1dC50eXBlID0gJ3RleHQnO1xyXG4gICAgICAgICAgICByZXBseUlucHV0Lm5hbWUgPSAncmVwbHlUb0NvbW1lbnRUZXh0JztcclxuICAgICAgICAgICAgcmVwbHlJbnB1dC52YWx1ZSA9IGDQntGC0LLQtdGCINC90LAg0LrQvtC80LzQtdC90YLQsNGA0LjQuTogJHtjb21tZW50VGV4dH1gO1xyXG4gICAgICAgICAgICByZXBseUlucHV0LnJlYWRPbmx5ID0gdHJ1ZTtcclxuICAgICAgICAgICAgcmVwbHlJbnB1dC5jbGFzc0xpc3QuYWRkKCdmb3JtLWNvbnRyb2wnKTtcclxuXHJcbiAgICAgICAgICAgIHJlcGx5Q29udGFpbmVyLmFwcGVuZENoaWxkKHJlcGx5SW5wdXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfSk7XHJcbn0iLCJleHBvcnQgZnVuY3Rpb24gaW5pdFByb2ZpbGVDaGF0KHByb2ZpbGVJZCwgY2hhdENoZWNrVXJsLCBjaGF0TmV3VXJsKSB7XHJcbiAgICBjb25zdCBjaGF0QnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NoYXRCdXR0b24nKTtcclxuICAgIGNvbnN0IGNoYXRUZXh0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NoYXRUZXh0Jyk7XHJcbiAgICBjb25zdCBjb25maXJtTW9kYWxFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjb25maXJtTW9kYWwnKTtcclxuICAgIGNvbnN0IGNvbmZpcm1CdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY29uZmlybUJ1dHRvbicpO1xyXG5cclxuICAgIGlmICghY2hhdEJ1dHRvbiB8fCAhY2hhdFRleHQgfHwgIWNvbmZpcm1Nb2RhbEVsIHx8ICFjb25maXJtQnV0dG9uKSByZXR1cm47XHJcblxyXG4gICAgY29uc3QgY29uZmlybU1vZGFsID0gbmV3IGJvb3RzdHJhcC5Nb2RhbChjb25maXJtTW9kYWxFbCk7XHJcblxyXG4gICAgY2hhdEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcclxuICAgICAgICBjaGF0VGV4dC50ZXh0Q29udGVudCA9ICdMb2FkaW5nLi4uJztcclxuXHJcbiAgICAgICAgZmV0Y2goY2hhdENoZWNrVXJsKVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpXHJcbiAgICAgICAgICAgIC50aGVuKGRhdGEgPT4ge1xyXG4gICAgICAgICAgICAgICAgaWYgKGRhdGEucmVzdWx0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBjaGF0TmV3VXJsO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb25maXJtTW9kYWwuc2hvdygpO1xyXG4gICAgICAgICAgICAgICAgICAgIGNoYXRUZXh0LnRleHRDb250ZW50ID0gJ1NlbmQgYSBtZXNzYWdlJztcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSlcclxuICAgICAgICAgICAgLmNhdGNoKGVycm9yID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoJ0NoYXQgY2hlY2sgZXJyb3I6JywgZXJyb3IpO1xyXG4gICAgICAgICAgICAgICAgY2hhdFRleHQudGV4dENvbnRlbnQgPSAnU2VuZCBhIG1lc3NhZ2UnO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgIH0pO1xyXG5cclxuICAgIGNvbmZpcm1CdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoKSA9PiB7XHJcbiAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBjaGF0TmV3VXJsO1xyXG4gICAgfSk7XHJcbn0iLCJpbXBvcnQgeyBpbml0SW1hZ2VVcGxvYWQgfSBmcm9tICcuL2luaXQtaW1hZ2UtdXBsb2FkJztcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiBpbml0RXZlbnRGb3JtKCkge1xyXG4gICAgaW5pdEltYWdlVXBsb2FkKHtcclxuICAgICAgICBkZWxldGVVcmxQcmVmaXg6ICcvY3BhbmVsL2VkaXRvci9ldmVudHMvaW1hZ2VzLycsXHJcbiAgICAgICAgdXBsb2FkVXJsOiAnL2NwYW5lbC9lZGl0b3IvZXZlbnRzL2Ryb3B6b25lJyxcclxuICAgIH0pO1xyXG59IiwibGV0IGxpc3RlbmVyc0F0dGFjaGVkID0gZmFsc2U7XHJcblxyXG5leHBvcnQgZnVuY3Rpb24gaW5pdEV2ZW50c0ZlZWQoKSB7XHJcbiAgICAvLyDQmNC90LjRhtC40LDQu9C40LfQuNGA0YPQtdC8INC00LXQu9C10LPQuNGA0L7QstCw0L3QuNC1INGC0L7Qu9GM0LrQviDQvtC00LjQvSDRgNCw0LdcclxuICAgIGlmICghbGlzdGVuZXJzQXR0YWNoZWQpIHtcclxuICAgICAgICBpbml0TGlrZUJ1dHRvbnNEZWxlZ2F0ZWQoKTtcclxuICAgICAgICBsaXN0ZW5lcnNBdHRhY2hlZCA9IHRydWU7XHJcbiAgICB9XHJcblxyXG4gICAgaW5pdEJvb2ttYXJrTW9kYWwoKTtcclxuICAgIGluaXRDb21tZW50VG9nZ2xlcygpO1xyXG59XHJcblxyXG4vLyAtLS0g0JvQsNC50LrQuCAo0JTQtdC70LXQs9C40YDQvtCy0LDQvdC40LUpIC0tLVxyXG5mdW5jdGlvbiBpbml0TGlrZUJ1dHRvbnNEZWxlZ2F0ZWQoKSB7XHJcbiAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgY29uc3QgYnV0dG9uID0gZXZlbnQudGFyZ2V0LmNsb3Nlc3QoXCIubGlrZS1idXR0b25cIik7XHJcbiAgICAgICAgaWYgKCFidXR0b24pIHJldHVybjtcclxuXHJcbiAgICAgICAgLy8g0J/RgNC10LTQvtGC0LLRgNCw0YnQsNC10Lwg0LLRgdC/0LvRi9GC0LjQtSwg0LXRgdC70Lgg0L3Rg9C20L3QviAo0L7QsdGL0YfQvdC+INC00LvRjyDQutC90L7Qv9C+0Log0L3QtSDQvtCx0Y/Qt9Cw0YLQtdC70YzQvdC+LCDQvdC+INC/0L7Qu9C10LfQvdC+KVxyXG4gICAgICAgIC8vIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7IFxyXG5cclxuICAgICAgICBjb25zdCBldmVudElkID0gYnV0dG9uLmRhdGFzZXQuZXZlbnRJZDtcclxuICAgICAgICBjb25zdCBjc3JmVG9rZW4gPSBidXR0b24uZGF0YXNldC5jc3JmVG9rZW47XHJcbiAgICAgICAgY29uc3QgaGVhcnRJY29uID0gYnV0dG9uLnF1ZXJ5U2VsZWN0b3IoXCIuaGVhcnQtaWNvblwiKTtcclxuXHJcbiAgICAgICAgLy8g0J3QtSDQvtGC0L/RgNCw0LLQu9GP0LXQvCDQt9Cw0L/RgNC+0YEsINC10YHQu9C4INGD0LbQtSDQuNC00LXRgiDQvtCx0YDQsNCx0L7RgtC60LAgKNC+0L/RhtC40L7QvdCw0LvRjNC90L4sINC80L7QttC90L4g0LTQvtCx0LDQstC40YLRjCDRgdC+0YHRgtC+0Y/QvdC40LUgbG9hZGluZylcclxuICAgICAgICBpZiAoYnV0dG9uLmNsYXNzTGlzdC5jb250YWlucyhcImlzLWxvYWRpbmdcIikpIHJldHVybjtcclxuICAgICAgICBidXR0b24uY2xhc3NMaXN0LmFkZChcImlzLWxvYWRpbmdcIik7XHJcblxyXG4gICAgICAgIGZldGNoKGAvY3BhbmVsL2xpa2UvJHtldmVudElkfWAsIHtcclxuICAgICAgICAgICAgbWV0aG9kOiBcIlBPU1RcIixcclxuICAgICAgICAgICAgaGVhZGVyczoge1xyXG4gICAgICAgICAgICAgICAgXCJYLVJlcXVlc3RlZC1XaXRoXCI6IFwiWE1MSHR0cFJlcXVlc3RcIixcclxuICAgICAgICAgICAgICAgIFwiQ29udGVudC1UeXBlXCI6IFwiYXBwbGljYXRpb24vanNvblwiLFxyXG4gICAgICAgICAgICAgICAgXCJYLUNTUkYtVG9rZW5cIjogY3NyZlRva2VuXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KVxyXG4gICAgICAgICAgICAudGhlbihyZXMgPT4gcmVzLmpzb24oKSlcclxuICAgICAgICAgICAgLnRoZW4oZGF0YSA9PiB7XHJcbiAgICAgICAgICAgICAgICBpZiAoZGF0YS5yZXN1bHQpIHtcclxuICAgICAgICAgICAgICAgICAgICBoZWFydEljb24uY2xhc3NMaXN0LmFkZChcInRleHQtcHJpbWFyeVwiKTtcclxuICAgICAgICAgICAgICAgICAgICBoZWFydEljb24uY2xhc3NMaXN0LnJlbW92ZShcInRleHQtbXV0ZWRcIik7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGhlYXJ0SWNvbi5jbGFzc0xpc3QucmVtb3ZlKFwidGV4dC1wcmltYXJ5XCIpO1xyXG4gICAgICAgICAgICAgICAgICAgIGhlYXJ0SWNvbi5jbGFzc0xpc3QuYWRkKFwidGV4dC1tdXRlZFwiKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSlcclxuICAgICAgICAgICAgLmNhdGNoKGVycm9yID0+IGNvbnNvbGUuZXJyb3IoYExpa2UgZXJyb3IgZm9yIGV2ZW50ICR7ZXZlbnRJZH06YCwgZXJyb3IpKVxyXG4gICAgICAgICAgICAuZmluYWxseSgoKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBidXR0b24uY2xhc3NMaXN0LnJlbW92ZShcImlzLWxvYWRpbmdcIik7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgfSk7XHJcbn1cclxuXHJcbi8vIC0tLSDQl9Cw0LrQu9Cw0LTQutC4ICsg0LzQvtC00LDQu9C60LAgLS0tXHJcbmZ1bmN0aW9uIGluaXRCb29rbWFya01vZGFsKCkge1xyXG4gICAgY29uc3QgbW9kYWxFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiY29uZmlybU1vZGFsXCIpO1xyXG4gICAgY29uc3QgY29uZmlybU1vZGFsID0gbW9kYWxFbCA/IG5ldyBib290c3RyYXAuTW9kYWwobW9kYWxFbCkgOiBudWxsO1xyXG4gICAgY29uc3QgY29uZmlybUJ0biA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiY29uZmlybUFjY2VwdFwiKTtcclxuICAgIGNvbnN0IHRpdGxlRWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImNvbmZpcm1Nb2RhbExhYmVsXCIpO1xyXG4gICAgY29uc3QgYm9keUVsID0gbW9kYWxFbD8ucXVlcnlTZWxlY3RvcihcIi5tb2RhbC1ib2R5XCIpO1xyXG5cclxuICAgIGlmICghY29uZmlybU1vZGFsIHx8ICFjb25maXJtQnRuKSByZXR1cm47XHJcblxyXG4gICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbChcIi5ib29rbWFyay10b2dnbGUsIC5ib29rbWFyay1yZW1vdmVcIikuZm9yRWFjaChidXR0b24gPT4ge1xyXG4gICAgICAgIGJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgZSA9PiB7XHJcbiAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgY29uc3QgY29udGFpbmVyID0gZS5jdXJyZW50VGFyZ2V0LmNsb3Nlc3QoXCIuYm9va21hcmstYnV0dG9uLCAuYm9va21hcmstcmVtb3ZlXCIpO1xyXG5cclxuICAgICAgICAgICAgY29uc3QgZXZlbnRJZCA9IGNvbnRhaW5lci5kYXRhc2V0LmV2ZW50SWQ7XHJcbiAgICAgICAgICAgIGNvbnN0IGNzcmZUb2tlbiA9IGNvbnRhaW5lci5kYXRhc2V0LmNzcmZUb2tlbjtcclxuICAgICAgICAgICAgY29uc3QgaXNSZW1vdmUgPSBlLmN1cnJlbnRUYXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKFwiYm9va21hcmstcmVtb3ZlXCIpO1xyXG4gICAgICAgICAgICBjb25zdCBpc0FjY2VwdGVkID0gY29udGFpbmVyLmNsYXNzTGlzdC5jb250YWlucyhcImFjY2VwdGVkXCIpO1xyXG5cclxuICAgICAgICAgICAgdGl0bGVFbC50ZXh0Q29udGVudCA9IGlzUmVtb3ZlIHx8IGlzQWNjZXB0ZWQgPyBcItCl0L7RgtC40YLQtSDQvtGC0LrQsNC30LDRgtGM0YHRjyDQvtGCINCy0YvQt9C+0LLQsD9cIiA6IFwi0J/RgNC40L3Rj9GC0LjQtSDQstGL0LfQvtCy0LBcIjtcclxuICAgICAgICAgICAgYm9keUVsLnRleHRDb250ZW50ID0gaXNSZW1vdmUgfHwgaXNBY2NlcHRlZFxyXG4gICAgICAgICAgICAgICAgPyBcItCS0Ysg0LTQtdC50YHRgtCy0LjRgtC10LvRjNC90L4g0YXQvtGC0LjRgtC1INC+0YLQutCw0LfQsNGC0YzRgdGPINC+0YIg0LLRi9C30L7QstCwP1wiXHJcbiAgICAgICAgICAgICAgICA6IFwi0JLRiyDRhdC+0YLQuNGC0LUg0L/RgNC40L3Rj9GC0Ywg0LLRi9C30L7Qsj9cIjtcclxuXHJcbiAgICAgICAgICAgIGNvbmZpcm1CdG4uZGF0YXNldC5ldmVudElkID0gZXZlbnRJZDtcclxuICAgICAgICAgICAgY29uZmlybUJ0bi5kYXRhc2V0LmNzcmZUb2tlbiA9IGNzcmZUb2tlbjtcclxuICAgICAgICAgICAgY29uZmlybUJ0bi5kYXRhc2V0LmlzUmVtb3ZlID0gaXNSZW1vdmU7XHJcbiAgICAgICAgICAgIGNvbmZpcm1CdG4uZGF0YXNldC5pc0FjY2VwdGVkID0gaXNBY2NlcHRlZDtcclxuXHJcbiAgICAgICAgICAgIGNvbmZpcm1Nb2RhbC5zaG93KCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9KTtcclxuXHJcbiAgICBjb25maXJtQnRuLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBhc3luYyAoKSA9PiB7XHJcbiAgICAgICAgY29uc3QgZXZlbnRJZCA9IGNvbmZpcm1CdG4uZGF0YXNldC5ldmVudElkO1xyXG4gICAgICAgIGNvbnN0IGNzcmZUb2tlbiA9IGNvbmZpcm1CdG4uZGF0YXNldC5jc3JmVG9rZW47XHJcbiAgICAgICAgY29uc3QgaXNSZW1vdmUgPSBjb25maXJtQnRuLmRhdGFzZXQuaXNSZW1vdmUgPT09IFwidHJ1ZVwiO1xyXG4gICAgICAgIGNvbnN0IGlzQWNjZXB0ZWQgPSBjb25maXJtQnRuLmRhdGFzZXQuaXNBY2NlcHRlZCA9PT0gXCJ0cnVlXCI7XHJcblxyXG4gICAgICAgIGNvbnN0IHVybCA9IGlzQWNjZXB0ZWQgfHwgaXNSZW1vdmVcclxuICAgICAgICAgICAgPyBgL2NwYW5lbC9ib29rbWFyay9yZW1vdmUvJHtldmVudElkfWBcclxuICAgICAgICAgICAgOiBgL2NwYW5lbC9ib29rbWFyay9hZGQvJHtldmVudElkfWA7XHJcbiAgICAgICAgY29uc3QgbWV0aG9kID0gaXNBY2NlcHRlZCB8fCBpc1JlbW92ZSA/IFwiREVMRVRFXCIgOiBcIlBPU1RcIjtcclxuXHJcbiAgICAgICAgdHJ5IHtcclxuICAgICAgICAgICAgY29uc3QgcmVzcG9uc2UgPSBhd2FpdCBmZXRjaCh1cmwsIHtcclxuICAgICAgICAgICAgICAgIG1ldGhvZCxcclxuICAgICAgICAgICAgICAgIGhlYWRlcnM6IHtcclxuICAgICAgICAgICAgICAgICAgICBcIkNvbnRlbnQtVHlwZVwiOiBcImFwcGxpY2F0aW9uL2pzb25cIixcclxuICAgICAgICAgICAgICAgICAgICBcIlgtQ1NSRi1Ub2tlblwiOiBjc3JmVG9rZW5cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBpZiAoIXJlc3BvbnNlLm9rKSB0aHJvdyBuZXcgRXJyb3IoYEhUVFAgJHtyZXNwb25zZS5zdGF0dXN9YCk7XHJcblxyXG4gICAgICAgICAgICBjb25zdCBjb250YWluZXIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKGAuYm9va21hcmstYnV0dG9uW2RhdGEtZXZlbnQtaWQ9XCIke2V2ZW50SWR9XCJdYCk7XHJcbiAgICAgICAgICAgIGNvbnN0IGljb24gPSBjb250YWluZXI/LnF1ZXJ5U2VsZWN0b3IoXCIuYm9va21hcmstaWNvblwiKTtcclxuICAgICAgICAgICAgY29uc3QgY291bnRTcGFuID0gY29udGFpbmVyPy5xdWVyeVNlbGVjdG9yKFwiLmJvb2ttYXJrLWNvdW50XCIpO1xyXG5cclxuICAgICAgICAgICAgaWYgKGlzUmVtb3ZlKSB7XHJcbiAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24uaHJlZiA9IFwiL2NwYW5lbC9ib29rbWFyay9saXN0XCI7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChpY29uICYmIGNvdW50U3Bhbikge1xyXG4gICAgICAgICAgICAgICAgY29uc3QgY3VycmVudENvdW50ID0gcGFyc2VJbnQoY291bnRTcGFuLnRleHRDb250ZW50LCAxMCkgfHwgMDtcclxuICAgICAgICAgICAgICAgIGljb24uY2xhc3NMaXN0LnRvZ2dsZShcInRleHQtcHJpbWFyeVwiLCAhaXNBY2NlcHRlZCk7XHJcbiAgICAgICAgICAgICAgICBpY29uLmNsYXNzTGlzdC50b2dnbGUoXCJ0ZXh0LW11dGVkXCIsIGlzQWNjZXB0ZWQpO1xyXG4gICAgICAgICAgICAgICAgY29udGFpbmVyLmNsYXNzTGlzdC50b2dnbGUoXCJhY2NlcHRlZFwiLCAhaXNBY2NlcHRlZCk7XHJcbiAgICAgICAgICAgICAgICBjb3VudFNwYW4udGV4dENvbnRlbnQgPSBpc0FjY2VwdGVkID8gTWF0aC5tYXgoMCwgY3VycmVudENvdW50IC0gMSkgOiBjdXJyZW50Q291bnQgKyAxO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgIH0gY2F0Y2ggKGVycm9yKSB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoXCJCb29rbWFyayBlcnJvcjpcIiwgZXJyb3IpO1xyXG4gICAgICAgICAgICBhbGVydChg0J7RiNC40LHQutCwOiAke2Vycm9yLm1lc3NhZ2V9YCk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBjb25maXJtTW9kYWwuaGlkZSgpO1xyXG4gICAgfSk7XHJcbn1cclxuXHJcbi8vIC0tLSDQmtC+0LzQvNC10L3RgtCw0YDQuNC4ICjRgdC60YDQvtC70Lsg0Log0Y/QutC+0YDRjikgLS0tXHJcbmZ1bmN0aW9uIGluaXRDb21tZW50VG9nZ2xlcygpIHtcclxuICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5jb21tZW50LXRvZ2dsZScpLmZvckVhY2goYnV0dG9uID0+IHtcclxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBldmVudCA9PiB7XHJcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIGNvbnN0IHBhdGggPSBidXR0b24uY2xvc2VzdCgnLmNvbW1lbnQtYnV0dG9uJyk/LmRhdGFzZXQuZXZlbnRQYXRoO1xyXG4gICAgICAgICAgICBpZiAocGF0aCkge1xyXG4gICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBgJHtwYXRofSN0YXJnZXQtZWxlbWVudC1pZGA7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgIH0pO1xyXG59IiwiZXhwb3J0IGZ1bmN0aW9uIGluaXRCb29rbWFya1RvZ2dsZSgpIHtcclxuICAgIGNvbnN0IGNvbmZpcm1Nb2RhbEVsID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJjb25maXJtTW9kYWxcIik7XHJcbiAgICBjb25zdCBjb25maXJtQnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJjb25maXJtQWNjZXB0XCIpO1xyXG4gICAgY29uc3QgbW9kYWxUaXRsZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiY29uZmlybU1vZGFsTGFiZWxcIik7XHJcbiAgICBjb25zdCBtb2RhbEJvZHkgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiI2NvbmZpcm1Nb2RhbCAubW9kYWwtYm9keVwiKTtcclxuXHJcbiAgICBpZiAoIWNvbmZpcm1Nb2RhbEVsIHx8ICFjb25maXJtQnV0dG9uIHx8ICFtb2RhbFRpdGxlIHx8ICFtb2RhbEJvZHkpIHtcclxuICAgICAgICBjb25zb2xlLndhcm4oXCLinZcg0K3Qu9C10LzQtdC90YLRiyDQvNC+0LTQsNC70YzQvdC+0LPQviDQvtC60L3QsCDQtNC70Y8g0LfQsNC60LvQsNC00L7QuiDQvdC1INC90LDQudC00LXQvdGLLlwiKTtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG4gICAgY29uc3QgY29uZmlybU1vZGFsID0gbmV3IGJvb3RzdHJhcC5Nb2RhbChjb25maXJtTW9kYWxFbCk7XHJcblxyXG4gICAgZnVuY3Rpb24gaGFuZGxlQm9va21hcmtDbGljayhldmVudCkge1xyXG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG4gICAgICAgIGNvbnN0IGJ1dHRvbkNvbnRhaW5lciA9IGV2ZW50LmN1cnJlbnRUYXJnZXQuY2xvc2VzdCgnLmJvb2ttYXJrLWJ1dHRvbiwgLmJvb2ttYXJrLXJlbW92ZScpO1xyXG4gICAgICAgIGNvbnN0IGV2ZW50SWQgPSBidXR0b25Db250YWluZXIuZGF0YXNldC5ldmVudElkO1xyXG4gICAgICAgIGNvbnN0IGNzcmZUb2tlbiA9IGJ1dHRvbkNvbnRhaW5lci5kYXRhc2V0LmNzcmZUb2tlbjtcclxuICAgICAgICBjb25zdCBpc1JlbW92ZUJ1dHRvbiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKFwiYm9va21hcmstcmVtb3ZlXCIpO1xyXG4gICAgICAgIGNvbnN0IGlzQWNjZXB0ZWQgPSBidXR0b25Db250YWluZXIuY2xhc3NMaXN0LmNvbnRhaW5zKFwiYWNjZXB0ZWRcIik7XHJcblxyXG4gICAgICAgIG1vZGFsVGl0bGUudGV4dENvbnRlbnQgPSBpc1JlbW92ZUJ1dHRvbiB8fCBpc0FjY2VwdGVkXHJcbiAgICAgICAgICAgID8gXCLQpdC+0YLQuNGC0LUg0L7RgtC60LDQt9Cw0YLRjNGB0Y8g0L7RgiDQstGL0LfQvtCy0LA/XCJcclxuICAgICAgICAgICAgOiBcItCf0YDQuNC90Y/RgtC40LUg0LLRi9C30L7QstCwXCI7XHJcblxyXG4gICAgICAgIG1vZGFsQm9keS50ZXh0Q29udGVudCA9IGlzUmVtb3ZlQnV0dG9uIHx8IGlzQWNjZXB0ZWRcclxuICAgICAgICAgICAgPyBcItCS0Ysg0LTQtdC50YHRgtCy0LjRgtC10LvRjNC90L4g0YXQvtGC0LjRgtC1INC+0YLQutCw0LfQsNGC0YzRgdGPINC+0YIg0LLRi9C30L7QstCwP1wiXHJcbiAgICAgICAgICAgIDogXCLQktGLINGF0L7RgtC40YLQtSDQv9GA0LjQvdGP0YLRjCDQstGL0LfQvtCyP1wiO1xyXG5cclxuICAgICAgICBjb25maXJtQnV0dG9uLmRhdGFzZXQuZXZlbnRJZCA9IGV2ZW50SWQ7XHJcbiAgICAgICAgY29uZmlybUJ1dHRvbi5kYXRhc2V0LmNzcmZUb2tlbiA9IGNzcmZUb2tlbjtcclxuICAgICAgICBjb25maXJtQnV0dG9uLmRhdGFzZXQuaXNSZW1vdmVCdXR0b24gPSBpc1JlbW92ZUJ1dHRvbjtcclxuICAgICAgICBjb25maXJtQnV0dG9uLmRhdGFzZXQuaXNBY2NlcHRlZCA9IGlzQWNjZXB0ZWQ7XHJcblxyXG4gICAgICAgIGNvbmZpcm1Nb2RhbC5zaG93KCk7XHJcbiAgICB9XHJcblxyXG4gICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmJvb2ttYXJrLXRvZ2dsZSwgLmJvb2ttYXJrLXJlbW92ZScpLmZvckVhY2goYnV0dG9uID0+IHtcclxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBoYW5kbGVCb29rbWFya0NsaWNrKTtcclxuICAgIH0pO1xyXG5cclxuICAgIGNvbmZpcm1CdXR0b24uYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGFzeW5jIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBjb25zdCBldmVudElkID0gdGhpcy5kYXRhc2V0LmV2ZW50SWQ7XHJcbiAgICAgICAgY29uc3QgY3NyZlRva2VuID0gdGhpcy5kYXRhc2V0LmNzcmZUb2tlbjtcclxuICAgICAgICBjb25zdCBpc1JlbW92ZUJ1dHRvbiA9IHRoaXMuZGF0YXNldC5pc1JlbW92ZUJ1dHRvbiA9PT0gXCJ0cnVlXCI7XHJcbiAgICAgICAgY29uc3QgaXNBY2NlcHRlZCA9IHRoaXMuZGF0YXNldC5pc0FjY2VwdGVkID09PSBcInRydWVcIjtcclxuXHJcbiAgICAgICAgY29uc3QgdXJsID0gaXNBY2NlcHRlZCB8fCBpc1JlbW92ZUJ1dHRvblxyXG4gICAgICAgICAgICA/IGAvY3BhbmVsL2Jvb2ttYXJrL3JlbW92ZS8ke2V2ZW50SWR9YFxyXG4gICAgICAgICAgICA6IGAvY3BhbmVsL2Jvb2ttYXJrL2FkZC8ke2V2ZW50SWR9YDtcclxuICAgICAgICBjb25zdCBtZXRob2QgPSBpc0FjY2VwdGVkIHx8IGlzUmVtb3ZlQnV0dG9uID8gJ0RFTEVURScgOiAnUE9TVCc7XHJcblxyXG4gICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgIGNvbnN0IHJlc3BvbnNlID0gYXdhaXQgZmV0Y2godXJsLCB7XHJcbiAgICAgICAgICAgICAgICBtZXRob2QsXHJcbiAgICAgICAgICAgICAgICBoZWFkZXJzOiB7XHJcbiAgICAgICAgICAgICAgICAgICAgJ1gtQ1NSRi1Ub2tlbic6IGNzcmZUb2tlbixcclxuICAgICAgICAgICAgICAgICAgICAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL2pzb24nXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgaWYgKCFyZXNwb25zZS5vaykge1xyXG4gICAgICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKGDQntGI0LjQsdC60LAgJHtyZXNwb25zZS5zdGF0dXN9OiAke3Jlc3BvbnNlLnN0YXR1c1RleHR9YCk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChpc1JlbW92ZUJ1dHRvbikge1xyXG4gICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBcIi9jcGFuZWwvYm9va21hcmsvbGlzdFwiOyAvLyDQnNC+0LbQvdC+INC4INGH0LXRgNC10LcgYHBhdGgoKWAg0LLRgdGC0LDQstC40YLRjCDRh9C10YDQtdC3IFR3aWdcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgYnV0dG9uQ29udGFpbmVyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihgLmJvb2ttYXJrLWJ1dHRvbltkYXRhLWV2ZW50LWlkPVwiJHtldmVudElkfVwiXWApO1xyXG4gICAgICAgICAgICBjb25zdCBpY29uID0gYnV0dG9uQ29udGFpbmVyPy5xdWVyeVNlbGVjdG9yKFwiLmJvb2ttYXJrLWljb25cIik7XHJcbiAgICAgICAgICAgIGNvbnN0IGNvdW50U3BhbiA9IGJ1dHRvbkNvbnRhaW5lcj8ucXVlcnlTZWxlY3RvcihcIi5ib29rbWFyay1jb3VudFwiKTtcclxuXHJcbiAgICAgICAgICAgIGlmIChpY29uICYmIGNvdW50U3Bhbikge1xyXG4gICAgICAgICAgICAgICAgbGV0IGN1cnJlbnRDb3VudCA9IHBhcnNlSW50KGNvdW50U3Bhbi50ZXh0Q29udGVudCwgMTApIHx8IDA7XHJcbiAgICAgICAgICAgICAgICBpY29uLmNsYXNzTGlzdC50b2dnbGUoJ3RleHQtcHJpbWFyeScsICFpc0FjY2VwdGVkKTtcclxuICAgICAgICAgICAgICAgIGljb24uY2xhc3NMaXN0LnRvZ2dsZSgndGV4dC1tdXRlZCcsIGlzQWNjZXB0ZWQpO1xyXG4gICAgICAgICAgICAgICAgYnV0dG9uQ29udGFpbmVyLmNsYXNzTGlzdC50b2dnbGUoXCJhY2NlcHRlZFwiLCAhaXNBY2NlcHRlZCk7XHJcbiAgICAgICAgICAgICAgICBjb3VudFNwYW4udGV4dENvbnRlbnQgPSBpc0FjY2VwdGVkID8gTWF0aC5tYXgoMCwgY3VycmVudENvdW50IC0gMSkgOiBjdXJyZW50Q291bnQgKyAxO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgIH0gY2F0Y2ggKGVycm9yKSB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoJ9Ce0YjQuNCx0LrQsCDQv9GA0Lgg0LjQt9C80LXQvdC10L3QuNC4INC30LDQutC70LDQtNC60Lg6JywgZXJyb3IpO1xyXG4gICAgICAgICAgICBhbGVydChg0J/RgNC+0LjQt9C+0YjQu9CwINC+0YjQuNCx0LrQsDogJHtlcnJvci5tZXNzYWdlfWApO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgY29uZmlybU1vZGFsLmhpZGUoKTtcclxuICAgIH0pO1xyXG59IiwiaW1wb3J0IENoYXJ0IGZyb20gJ2NoYXJ0LmpzL2F1dG8nO1xyXG5cclxuZXhwb3J0IGZ1bmN0aW9uIGluaXRHb2FsUGVyY2VudENoYXJ0cygpIHtcclxuICAgIGNvbnN0IGNhbnZhc2VzID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCgnLmpzLWdvYWwtcGVyY2VudC1jaGFydCcpO1xyXG4gICAgaWYgKCFjYW52YXNlcy5sZW5ndGgpIHJldHVybjtcclxuXHJcbiAgICBmdW5jdGlvbiBnZXRDb2xvckJ5UGVyY2VudChwZXJjZW50KSB7XHJcbiAgICAgICAgaWYgKHBlcmNlbnQgPCAzMCkgcmV0dXJuICdyZ2JhKDIyMCwgNTMsIDY5LCAxKSc7ICAgLy8g0LrRgNCw0YHQvdGL0LlcclxuICAgICAgICBpZiAocGVyY2VudCA8IDYwKSByZXR1cm4gJ3JnYmEoMjU1LCAxNTksIDY0LCAxKSc7ICAvLyDQvtGA0LDQvdC20LXQstGL0LlcclxuICAgICAgICBpZiAocGVyY2VudCA8IDgwKSByZXR1cm4gJ3JnYmEoMjU1LCAxOTMsIDcsIDEpJzsgICAvLyDQttGR0LvRgtGL0LlcclxuICAgICAgICByZXR1cm4gJ3JnYmEoNDAsIDE2NywgNjksIDEpJzsgICAgICAgICAgICAgICAgICAgICAvLyDQt9C10LvRkdC90YvQuVxyXG4gICAgfVxyXG5cclxuICAgIGNhbnZhc2VzLmZvckVhY2goKGNhbnZhcykgPT4ge1xyXG4gICAgICAgIGNvbnN0IHBlcmNlbnQgPSBwYXJzZUludChjYW52YXMuZGF0YXNldC5wZXJjZW50IHx8ICcwJywgMTApO1xyXG4gICAgICAgIGNvbnN0IHNhZmUgPSBNYXRoLm1heCgwLCBNYXRoLm1pbigxMDAsIHBlcmNlbnQpKTtcclxuXHJcbiAgICAgICAgY29uc3QgbWFpbkNvbG9yID0gZ2V0Q29sb3JCeVBlcmNlbnQoc2FmZSk7XHJcblxyXG4gICAgICAgIC8vINGH0YLQvtCx0YsgVHVyYm8v0L/QvtCy0YLQvtGA0L3Ri9C5INCy0YvQt9C+0LIg0L3QtSDRgdC+0LfQtNCw0LLQsNC7INCz0YDQsNGE0LjQuiDQv9C+0LLQtdGA0YVcclxuICAgICAgICBpZiAoY2FudmFzLl9fY2hhcnQpIHtcclxuICAgICAgICAgICAgY2FudmFzLl9fY2hhcnQuZGVzdHJveSgpO1xyXG4gICAgICAgICAgICBjYW52YXMuX19jaGFydCA9IG51bGw7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBjYW52YXMuX19jaGFydCA9IG5ldyBDaGFydChjYW52YXMuZ2V0Q29udGV4dCgnMmQnKSwge1xyXG4gICAgICAgICAgICB0eXBlOiAnZG91Z2hudXQnLFxyXG4gICAgICAgICAgICBkYXRhOiB7XHJcbiAgICAgICAgICAgICAgICBkYXRhc2V0czogW3tcclxuICAgICAgICAgICAgICAgICAgICBkYXRhOiBbc2FmZSwgMTAwIC0gc2FmZV0sXHJcbiAgICAgICAgICAgICAgICAgICAgYmFja2dyb3VuZENvbG9yOiBbXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG1haW5Db2xvcixcclxuICAgICAgICAgICAgICAgICAgICAgICAgJ3JnYmEoMjIwLCAyMjAsIDIyMCwgMC40KSdcclxuICAgICAgICAgICAgICAgICAgICBdLFxyXG4gICAgICAgICAgICAgICAgICAgIGJvcmRlcldpZHRoOiAwXHJcbiAgICAgICAgICAgICAgICB9XVxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICBvcHRpb25zOiB7XHJcbiAgICAgICAgICAgICAgICByZXNwb25zaXZlOiBmYWxzZSxcclxuICAgICAgICAgICAgICAgIG1haW50YWluQXNwZWN0UmF0aW86IGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgYW5pbWF0aW9uOiBmYWxzZSxcclxuICAgICAgICAgICAgICAgIGN1dG91dDogJzcwJScsXHJcbiAgICAgICAgICAgICAgICBwbHVnaW5zOiB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGVnZW5kOiB7IGRpc3BsYXk6IGZhbHNlIH0sXHJcbiAgICAgICAgICAgICAgICAgICAgdG9vbHRpcDogeyBlbmFibGVkOiBmYWxzZSB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgIH0pO1xyXG59IiwiZXhwb3J0IGZ1bmN0aW9uIGluaXRJbmZvQm94KCkge1xyXG4gICAgLy8g0K3RgtC+INCy0YHRkSDQvdGD0LbQvdC+INC90LAgYXBwX2FjY291bnRfbGlzdCAtINCR0LvQvtC6INC00LvRjyDQvtGC0L7QsdGA0LDQttC10L3QuNGPINC60L7Qu9C40YfQtdGB0YLQstCwINC30LDQv9C40YHQtdC5INC90LDQtCDQutCw0YDRgtC40L3QutCw0LzQuFxyXG4gICAgY29uc3QgaW5mb0JveCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiaW5mb0JveFwiKTtcclxuXHJcbiAgICBpZiAoIWluZm9Cb3gpIHJldHVybjtcclxuXHJcbiAgICAvLyDQl9Cw0LzQtdC90LjQvCDRjdC70LXQvNC10L3RgiDQvdCwINC60LvQvtC9LCDRh9GC0L7QsdGLINC+0YfQuNGB0YLQuNGC0Ywg0YHRgtCw0YDRi9C1INC+0LHRgNCw0LHQvtGC0YfQuNC60LggKNC10YHQu9C4INC+0L3QuCDQstC00YDRg9CzINC10YHRgtGMKVxyXG4gICAgY29uc3QgbmV3SW5mb0JveCA9IGluZm9Cb3guY2xvbmVOb2RlKHRydWUpO1xyXG4gICAgaW5mb0JveC5wYXJlbnROb2RlLnJlcGxhY2VDaGlsZChuZXdJbmZvQm94LCBpbmZvQm94KTtcclxuXHJcbiAgICBjb25zdCB0YXJnZXRVcmwgPSBuZXdJbmZvQm94LmRhdGFzZXQudXJsO1xyXG5cclxuICAgIGlmICh0YXJnZXRVcmwpIHtcclxuICAgICAgICBuZXdJbmZvQm94LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gdGFyZ2V0VXJsO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG59IiwiZXhwb3J0IGZ1bmN0aW9uIGluaXRJbWFnZVVwbG9hZCh7IGRlbGV0ZVVybFByZWZpeCwgdXBsb2FkVXJsIH0pIHtcclxuICAgIGluaXRJbWFnZURlbGV0ZShkZWxldGVVcmxQcmVmaXgpO1xyXG4gICAgaW5pdERyb3B6b25lKHVwbG9hZFVybCk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGluaXRJbWFnZURlbGV0ZShkZWxldGVVcmxQcmVmaXgpIHtcclxuICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5kZXZzdHlsZS1yZW1vdmUtaW1hZ2UtYnRuJykuZm9yRWFjaChidXR0b24gPT4ge1xyXG4gICAgICAgIGlmIChidXR0b24uZGF0YXNldC5ib3VuZCA9PT0gXCJ0cnVlXCIpIHJldHVybjtcclxuICAgICAgICBidXR0b24uZGF0YXNldC5ib3VuZCA9IFwidHJ1ZVwiO1xyXG5cclxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIGNvbnN0IGltYWdlSWQgPSB0aGlzLmRhdGFzZXQuaWQ7XHJcblxyXG4gICAgICAgICAgICBpZiAoY29uZmlybSgn0JLRiyDRg9Cy0LXRgNC10L3Riywg0YfRgtC+INGF0L7RgtC40YLQtSDRg9C00LDQu9C40YLRjCDQuNC30L7QsdGA0LDQttC10L3QuNC1PycpKSB7XHJcbiAgICAgICAgICAgICAgICBmZXRjaChgJHtkZWxldGVVcmxQcmVmaXh9JHtpbWFnZUlkfS9kZWxldGVgLCB7XHJcbiAgICAgICAgICAgICAgICAgICAgbWV0aG9kOiAnREVMRVRFJyxcclxuICAgICAgICAgICAgICAgICAgICBoZWFkZXJzOiB7ICdYLVJlcXVlc3RlZC1XaXRoJzogJ1hNTEh0dHBSZXF1ZXN0JyB9XHJcbiAgICAgICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHJlc3BvbnNlLm9rKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmNsb3Nlc3QoJy5jb2wtbWQtNCcpLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYWxlcnQoJ9Ce0YjQuNCx0LrQsCDQv9GA0Lgg0YPQtNCw0LvQtdC90LjQuCDQuNC30L7QsdGA0LDQttC10L3QuNGPJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAgICAgICAgIC5jYXRjaCgoKSA9PiBhbGVydCgn0J7RiNC40LHQutCwINC/0YDQuCDRg9C00LDQu9C10L3QuNC4INC40LfQvtCx0YDQsNC20LXQvdC40Y8nKSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgIH0pO1xyXG59XHJcblxyXG5mdW5jdGlvbiBpbml0RHJvcHpvbmUodXBsb2FkVXJsKSB7XHJcbiAgICBpZiAodHlwZW9mIERyb3B6b25lID09PSAndW5kZWZpbmVkJykge1xyXG4gICAgICAgIGNvbnNvbGUud2FybignRHJvcHpvbmUg0L3QtSDQt9Cw0LPRgNGD0LbQtdC9Jyk7XHJcbiAgICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG5cclxuICAgIERyb3B6b25lLmF1dG9EaXNjb3ZlciA9IGZhbHNlO1xyXG5cclxuICAgIGNvbnN0IGRyb3B6b25lRWxlbWVudCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiZmlsZS1kcm9wem9uZVwiKTtcclxuXHJcbiAgICBpZiAoZHJvcHpvbmVFbGVtZW50KSB7XHJcbiAgICAgICAgaWYgKERyb3B6b25lLmluc3RhbmNlcy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIERyb3B6b25lLmluc3RhbmNlcy5mb3JFYWNoKGluc3RhbmNlID0+IGluc3RhbmNlLmRlc3Ryb3koKSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBpZiAoIWRyb3B6b25lRWxlbWVudC5kcm9wem9uZSkge1xyXG4gICAgICAgICAgICBuZXcgRHJvcHpvbmUoZHJvcHpvbmVFbGVtZW50LCB7XHJcbiAgICAgICAgICAgICAgICB1cmw6IHVwbG9hZFVybCxcclxuICAgICAgICAgICAgICAgIHBhcmFtTmFtZTogXCJpbWFnZXNcIixcclxuICAgICAgICAgICAgICAgIG1heEZpbGVzOiA2LFxyXG4gICAgICAgICAgICAgICAgbWF4RmlsZXNpemU6IDUsXHJcbiAgICAgICAgICAgICAgICBhY2NlcHRlZEZpbGVzOiBcImltYWdlLypcIixcclxuICAgICAgICAgICAgICAgIGFkZFJlbW92ZUxpbmtzOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgZGljdERlZmF1bHRNZXNzYWdlOiBcItCf0LXRgNC10YLQsNGJ0LjRgtC1INGE0LDQudC70Ysg0YHRjtC00LAg0LjQu9C4INC60LvQuNC60L3QuNGC0LUg0LTQu9GPINC30LDQs9GA0YPQt9C60LhcIixcclxuICAgICAgICAgICAgICAgIGRpY3RSZW1vdmVGaWxlOiBcItCj0LTQsNC70LjRgtGMINGE0LDQudC7XCIsXHJcbiAgICAgICAgICAgICAgICBkaWN0TWF4RmlsZXNFeGNlZWRlZDogXCLQktGLINC80L7QttC10YLQtSDQt9Cw0LPRgNGD0LfQuNGC0Ywg0L3QtSDQsdC+0LvQtdC1IDYg0YTQsNC50LvQvtCyXCIsXHJcbiAgICAgICAgICAgICAgICBpbml0OiBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5vbihcInN1Y2Nlc3NcIiwgKGZpbGUsIHJlc3BvbnNlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKFwi0KTQsNC50Lsg0LfQsNCz0YDRg9C20LXQvTpcIiwgcmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgIHRoaXMub24oXCJlcnJvclwiLCAoZmlsZSwgZXJyb3JNZXNzYWdlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoXCLQntGI0LjQsdC60LAg0LfQsNCz0YDRg9C30LrQuDpcIiwgZXJyb3JNZXNzYWdlKTtcclxuICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59IiwiZXhwb3J0IGZ1bmN0aW9uIGluaXRTYXZlQnV0dG9uQ291bnRkb3duKCkge1xyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgZnVuY3Rpb24gKGUpIHtcclxuICAgICAgICBjb25zdCBmb3JtID0gZS50YXJnZXQ7XHJcblxyXG4gICAgICAgIGlmICghZm9ybS5tYXRjaGVzKCdmb3JtW2RhdGEtZm9ybS10eXBlPVwiYWktZ2VuZXJhdGVcIl0nKSkgcmV0dXJuO1xyXG5cclxuICAgICAgICBjb25zdCBidXR0b24gPSBmb3JtLnF1ZXJ5U2VsZWN0b3IoJyNzYXZlQnV0dG9uJyk7XHJcbiAgICAgICAgaWYgKCFidXR0b24pIHJldHVybjtcclxuXHJcbiAgICAgICAgYnV0dG9uLmRpc2FibGVkID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgbGV0IGNvdW50ZXIgPSAyMDtcclxuXHJcbiAgICAgICAgY29uc3QgcmVuZGVyID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBidXR0b24uaW5uZXJIVE1MID0gYFxyXG4gICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJzcGlubmVyLWJvcmRlciBzcGlubmVyLWJvcmRlci1zbSBtci0xXCJcclxuICAgICAgICAgICAgICAgICAgICAgIHJvbGU9XCJzdGF0dXNcIlxyXG4gICAgICAgICAgICAgICAgICAgICAgYXJpYS1oaWRkZW49XCJ0cnVlXCI+PC9zcGFuPlxyXG4gICAgICAgICAgICAgICAg0JPQtdC90LXRgNCw0YbQuNGPLi4uICgke2NvdW50ZXJ9KVxyXG4gICAgICAgICAgICBgO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIHJlbmRlcigpO1xyXG5cclxuICAgICAgICBjb25zdCBpbnRlcnZhbElkID0gc2V0SW50ZXJ2YWwoKCkgPT4ge1xyXG4gICAgICAgICAgICBjb3VudGVyLS07XHJcbiAgICAgICAgICAgIHJlbmRlcigpO1xyXG5cclxuICAgICAgICAgICAgaWYgKGNvdW50ZXIgPD0gMCkge1xyXG4gICAgICAgICAgICAgICAgY2xlYXJJbnRlcnZhbChpbnRlcnZhbElkKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0sIDEwMDApO1xyXG4gICAgfSk7XHJcbn0iLCJpbXBvcnQgaW50cm9KcyBmcm9tICdpbnRyby5qcyc7XHJcblxyXG5leHBvcnQgZnVuY3Rpb24gaW5pdEludHJvKCkge1xyXG4gICAgY29uc3QgaW50cm9FbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ludHJvLWRhdGEnKTtcclxuICAgIGlmICghaW50cm9FbGVtZW50KSB7XHJcbiAgICAgICAgcmV0dXJuO1xyXG4gICAgfVxyXG5cclxuICAgIGNvbnN0IHVzZXJKdXN0UmVnaXN0ZXJlZCA9IGludHJvRWxlbWVudC5kYXRhc2V0LnVzZXJKdXN0UmVnaXN0ZXJlZCA9PT0gJ3RydWUnO1xyXG4gICAgaWYgKCF1c2VySnVzdFJlZ2lzdGVyZWQpIHtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG4gICAgY29uc3QgaW50cm8gPSBpbnRyb0pzKCk7XHJcblxyXG4gICAgaW50cm8uc2V0T3B0aW9ucyh7XHJcbiAgICAgICAgc3RlcHM6IFtcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgZWxlbWVudDogJyNuZXVyaWZ5LWdwdCcsXHJcbiAgICAgICAgICAgICAgICBpbnRybzogJ9Ci0YPRgiDQvdC10LnRgNC+0YHQtdGC0Ywg0L/QvtC00YHQutCw0LbQtdGCINCy0LDQvCDQutCw0Log0L/QvtC70YzQt9C+0LLQsNGC0YzRgdGPINC/0LvQsNGC0YTQvtGA0LzQvtC5LicsXHJcbiAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2JvdHRvbSdcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgZWxlbWVudDogJyNwbGF0Zm9ybS1jaGFsbGVuZ2VzJyxcclxuICAgICAgICAgICAgICAgIGludHJvOiAn0KLRg9GCINCy0Ysg0YPQt9C90LDQtdGC0LUg0YfRgtC+INC00LXQu9Cw0YLRjCDQvdCwINC/0LvQsNGC0YTQvtGA0LzQtS4nLFxyXG4gICAgICAgICAgICAgICAgcG9zaXRpb246ICdib3R0b20nXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIGVsZW1lbnQ6ICcjd2Vlay1jaGFsbGVuZ2UnLFxyXG4gICAgICAgICAgICAgICAgaW50cm86ICfQotGD0YIg0LrQsNC20LTRg9GOINC90LXQtNC10LvRjiDQstGL0YXQvtC00Y/RgiDQvdC+0LLRi9C1INC30LDQtNCw0L3QuNGPLicsXHJcbiAgICAgICAgICAgICAgICBwb3NpdGlvbjogJ2JvdHRvbSdcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgZWxlbWVudDogJyNtZW50b3JzJyxcclxuICAgICAgICAgICAgICAgIGludHJvOiAn0KLRg9GCINC90LDRgdGC0LDQstC90LjQutC4INC/0YPQsdC70LjQutGD0Y7RgiDRgtC10LzQsNGC0LjRh9C10YHQutC40LUg0LfQsNC00LDQvdC40Y8uJyxcclxuICAgICAgICAgICAgICAgIHBvc2l0aW9uOiAnYm90dG9tJ1xyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICBlbGVtZW50OiAnI3VzZXItbGV2ZWwnLFxyXG4gICAgICAgICAgICAgICAgaW50cm86ICfQotGD0YIg0L7RgtC+0LHRgNCw0LbQsNC10YLRgdGPINCy0LDRiCDRg9GA0L7QstC10L3RjC4nLFxyXG4gICAgICAgICAgICAgICAgcG9zaXRpb246ICdib3R0b20nXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIGVsZW1lbnQ6ICcjdXNlci1zZWxlY3RlZC1hcnRpc2FuJyxcclxuICAgICAgICAgICAgICAgIGludHJvOiAn0KLRg9GCINCy0Ysg0LzQvtC20LXRgtC1INGB0L7Qt9C00LDRgtGMINC/0L7RgNGC0YTQvtC70LjQvi4nLFxyXG4gICAgICAgICAgICAgICAgcG9zaXRpb246ICdib3R0b20nXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgXSxcclxuICAgICAgICBzaG93UHJvZ3Jlc3M6IHRydWUsXHJcbiAgICAgICAgc2hvd0J1bGxldHM6IHRydWUsXHJcbiAgICAgICAgbmV4dExhYmVsOiAn0JTQsNC70YzRiNC1JyxcclxuICAgICAgICBwcmV2TGFiZWw6ICfQndCw0LfQsNC0JyxcclxuICAgICAgICBkb25lTGFiZWw6ICfQk9C+0YLQvtCy0L4nLFxyXG4gICAgICAgIG92ZXJsYXlPcGFjaXR5OiAwLjVcclxuICAgIH0pO1xyXG5cclxuICAgIGludHJvLnN0YXJ0KCk7XHJcbn0iLCJleHBvcnQgZnVuY3Rpb24gaW5pdE1hdHJpeEl0ZW1EZWxldGVNb2RhbCgpIHtcclxuICAgIGNvbnN0IGJ1dHRvbnMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcuZGV2c3R5bGUtZGVsZXRlLWl0ZW0tYnRuJyk7XHJcblxyXG4gICAgYnV0dG9ucy5mb3JFYWNoKGJ1dHRvbiA9PiB7XHJcbiAgICAgICAgLy8g0YfRgtC+0LHRiyDQvdC1INC/0L7QstC10YHQuNGC0Ywg0L3QtdGB0LrQvtC70YzQutC+INGA0LDQt1xyXG4gICAgICAgIGlmIChidXR0b24uZGF0YXNldC5ib3VuZCA9PT0gXCJ0cnVlXCIpIHJldHVybjtcclxuICAgICAgICBidXR0b24uZGF0YXNldC5ib3VuZCA9IFwidHJ1ZVwiO1xyXG5cclxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IGl0ZW1JZCA9IGJ1dHRvbi5kYXRhc2V0LmlkO1xyXG5cclxuICAgICAgICAgICAgY29uc3QgbW9kYWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaXRlbURlbGV0aW5nTW9kYWwnKTtcclxuICAgICAgICAgICAgaWYgKG1vZGFsKSB7XHJcbiAgICAgICAgICAgICAgICAkKG1vZGFsKS5tb2RhbCgnc2hvdycpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vINCV0YHQu9C4INGDINGC0LXQsdGPINC10YHRgtGMIGlucHV0INCy0L3Rg9GC0YDQuCDQvNC+0LTQsNC70LrQuCDigJQg0YLQvtCz0LTQsDpcclxuICAgICAgICAgICAgICAgIGNvbnN0IGhpZGRlbklucHV0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2lucHV0SXRlbUlkJyk7XHJcbiAgICAgICAgICAgICAgICBpZiAoaGlkZGVuSW5wdXQpIHtcclxuICAgICAgICAgICAgICAgICAgICBoaWRkZW5JbnB1dC52YWx1ZSA9IGl0ZW1JZDtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAvLyDQmNCb0Jgg0LXRgdC70Lgg0YLRiyDRhdC+0YfQtdGI0Ywg0LzQtdC90Y/RgtGMIGhyZWYg0L/RgNGP0LzQviDQsiDRgdGB0YvQu9C60LU6XHJcbiAgICAgICAgICAgICAgICBjb25zdCBkZWxldGVMaW5rID0gbW9kYWwucXVlcnlTZWxlY3RvcignLmJ0bi1kYW5nZXInKTtcclxuICAgICAgICAgICAgICAgIGlmIChkZWxldGVMaW5rKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZGVsZXRlTGluay5zZXRBdHRyaWJ1dGUoJ2hyZWYnLCBgL2NwYW5lbC9lZGl0b3IvaXRlbS9kZWxldGUvJHtpdGVtSWR9YCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgIH0pO1xyXG59XHJcbiIsImV4cG9ydCBmdW5jdGlvbiBpbml0TWF0cml4SXRlbUltYWdlVXBsb2FkKCkge1xyXG4gICAgY29uc3QgZmlsZUlucHV0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2l0ZW0taW1hZ2UtZmlsZScpO1xyXG4gICAgY29uc3QgcHJldmlld0ltYWdlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JsYWgnKTtcclxuXHJcbiAgICBpZiAoIWZpbGVJbnB1dCB8fCAhcHJldmlld0ltYWdlKSByZXR1cm47XHJcblxyXG4gICAgZmlsZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGZ1bmN0aW9uIChldnQpIHtcclxuICAgICAgICBjb25zdCBbZmlsZV0gPSBmaWxlSW5wdXQuZmlsZXM7XHJcbiAgICAgICAgaWYgKGZpbGUpIHtcclxuICAgICAgICAgICAgcHJldmlld0ltYWdlLnNyYyA9IFVSTC5jcmVhdGVPYmplY3RVUkwoZmlsZSk7XHJcbiAgICAgICAgfVxyXG4gICAgfSk7XHJcbn0iLCJleHBvcnQgZnVuY3Rpb24gaW5pdE1hdHJpeFNob3dJdGVtKCkge1xyXG4gICAgY29uc3QgdGFibGUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuZGV2c3R5bGUtdGFibGUnKTtcclxuICAgIGlmICghdGFibGUpIHJldHVybjtcclxuXHJcbiAgICB0YWJsZS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgY29uc3QgY2VsbCA9IGUudGFyZ2V0LmNsb3Nlc3QoJy5jbGlja2FibGUtY2VsbCcpO1xyXG4gICAgICAgIGlmICghY2VsbCB8fCAhY2VsbC5kYXRhc2V0LmlkKSByZXR1cm47XHJcblxyXG4gICAgICAgIGNvbnN0IGltYWdlVXJsID0gY2VsbC5kYXRhc2V0LmltYWdlVXJsO1xyXG4gICAgICAgIGNvbnN0IGRlc2NyaXB0aW9uSHRtbCA9IGNlbGwuZGF0YXNldC5kZXNjcmlwdGlvbjtcclxuICAgICAgICBjb25zdCBlZGl0TGlua1VybCA9IGNlbGwuZGF0YXNldC5lZGl0VXJsO1xyXG5cclxuICAgICAgICBzaG93SXRlbU1vZGFsKHtcclxuICAgICAgICAgICAgaW1hZ2VVcmwsXHJcbiAgICAgICAgICAgIGRlc2NyaXB0aW9uSHRtbCxcclxuICAgICAgICAgICAgZWRpdExpbmtVcmxcclxuICAgICAgICB9KTtcclxuICAgIH0pO1xyXG59XHJcblxyXG5mdW5jdGlvbiBkZWNvZGVKc0VzY2FwZWRTdHJpbmcoc3RyKSB7XHJcbiAgICB0cnkge1xyXG4gICAgICAgIHJldHVybiBKU09OLnBhcnNlKGBcIiR7c3RyfVwiYCk7XHJcbiAgICB9IGNhdGNoIChlKSB7XHJcbiAgICAgICAgY29uc29sZS5lcnJvcihcItCe0YjQuNCx0LrQsCDQtNC10LrQvtC00LjRgNC+0LLQsNC90LjRjyDRgdGC0YDQvtC60Lg6XCIsIHN0cik7XHJcbiAgICAgICAgcmV0dXJuIHN0cjtcclxuICAgIH1cclxufVxyXG5cclxuZXhwb3J0IGZ1bmN0aW9uIHNob3dJdGVtTW9kYWwoeyBpbWFnZVVybCwgZGVzY3JpcHRpb25IdG1sLCBlZGl0TGlua1VybCB9KSB7XHJcbiAgICBjb25zdCBpbWFnZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdpbWFnZScpO1xyXG4gICAgY29uc3QgbG9hZGVyID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ltYWdlTG9hZGVyJyk7XHJcbiAgICBjb25zdCBkZXNjcmlwdGlvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkaXZEZXNjcmlwdGlvbicpO1xyXG4gICAgY29uc3QgZWRpdExpbmsgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZWRpdExpbmsnKTtcclxuXHJcbiAgICAvLyDQodCx0YDQvtGBXHJcbiAgICBpbWFnZS5jbGFzc0xpc3QuYWRkKCdkLW5vbmUnKTtcclxuICAgIGxvYWRlci5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcclxuICAgIGltYWdlLnNyYyA9ICcnO1xyXG4gICAgZGVzY3JpcHRpb24uaW5uZXJIVE1MID0gJyc7XHJcbiAgICBlZGl0TGluay5ocmVmID0gJyMnO1xyXG5cclxuICAgIC8vINCU0LXQutC+0LTQuNGA0L7QstCw0L3QuNC1INC4INCy0YHRgtCw0LLQutCwINC+0L/QuNGB0LDQvdC40Y9cclxuICAgIGNvbnN0IGRlY29kZWRIdG1sID0gZGVjb2RlSnNFc2NhcGVkU3RyaW5nKGRlc2NyaXB0aW9uSHRtbCk7XHJcbiAgICBkZXNjcmlwdGlvbi5pbm5lckhUTUwgPSBkZWNvZGVkSHRtbDtcclxuXHJcbiAgICAvLyDQntCx0L3QvtCy0LvQtdC90LjQtSDRgdGB0YvQu9C60Lgg0L3QsCDRgNC10LTQsNC60YLQuNGA0L7QstCw0L3QuNC1XHJcbiAgICBpZiAoZWRpdExpbmtVcmwpIGVkaXRMaW5rLmhyZWYgPSBlZGl0TGlua1VybDtcclxuXHJcbiAgICAvLyDQntCx0YDQsNCx0L7RgtC60LAg0LfQsNCz0YDRg9C30LrQuCDQuNC30L7QsdGA0LDQttC10L3QuNGPXHJcbiAgICBpbWFnZS5vbmxvYWQgPSAoKSA9PiB7XHJcbiAgICAgICAgbG9hZGVyLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XHJcbiAgICAgICAgaW1hZ2UuY2xhc3NMaXN0LnJlbW92ZSgnZC1ub25lJyk7XHJcbiAgICB9O1xyXG5cclxuICAgIGltYWdlLm9uZXJyb3IgPSAoKSA9PiB7XHJcbiAgICAgICAgY29uc29sZS53YXJuKFwi0J7RiNC40LHQutCwINC30LDQs9GA0YPQt9C60Lgg0LjQt9C+0LHRgNCw0LbQtdC90LjRjzpcIiwgaW1hZ2VVcmwpO1xyXG4gICAgICAgIGxvYWRlci5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xyXG4gICAgICAgIGltYWdlLnNyYyA9ICcvY3BhbmVsL2ltYWdlcy9tYXBzL2RlZmF1bHQuanBnJzsgLy8g0J/QvtC60LDQt9GL0LLQsNC10LwgZGVmYXVsdCwg0L3QviDQsdC10Lcg0YDQtdC60YPRgNGB0LjQuFxyXG4gICAgICAgIGltYWdlLmNsYXNzTGlzdC5yZW1vdmUoJ2Qtbm9uZScpO1xyXG4gICAgfTtcclxuXHJcbiAgICBpbWFnZS5zcmMgPSBpbWFnZVVybDtcclxuXHJcbiAgICAkKCcjaXRlbVNob3dNb2RhbCcpLm1vZGFsKCdzaG93Jyk7XHJcbn0iLCJpbXBvcnQgeyBpbml0SW1hZ2VVcGxvYWQgfSBmcm9tICcuL2luaXQtaW1hZ2UtdXBsb2FkJztcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiBpbml0UGljdHVyZUZvcm0oKSB7XHJcbiAgICBpbml0SW1hZ2VVcGxvYWQoe1xyXG4gICAgICAgIGRlbGV0ZVVybFByZWZpeDogJy9jcGFuZWwvcGljdHVyZS9pbWFnZXMvJyxcclxuICAgICAgICB1cGxvYWRVcmw6ICcvY3BhbmVsL3BpY3R1cmUvZHJvcHpvbmUnLFxyXG4gICAgfSk7XHJcbn0iLCJleHBvcnQgZnVuY3Rpb24gaW5pdFByZXNlbnRhdGlvblN3aXBlcigpIHtcclxuICAgIGNvbnN0IHN3aXBlckVsID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLnN3aXBlci1jb250YWluZXInKTtcclxuXHJcbiAgICAvLyDQn9GA0L7QstC10YDQutCwLCDQvdGD0LbQtdC9INC70Lgg0LfQsNC/0YPRgdC6ICjRh9GC0L7QsdGLINC90LUg0LTRg9Cx0LvQuNGA0L7QstCw0YLRjCDQuNC90LjRhtC40LDQu9C40LfQsNGG0LjRjilcclxuICAgIGlmICghc3dpcGVyRWwgfHwgc3dpcGVyRWwuc3dpcGVyKSByZXR1cm47XHJcblxyXG4gICAgY29uc3QgY3VycmVudFN0ZXBFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwcmVzZW50YXRpb24tY3VycmVudC1zdGVwJyk7XHJcblxyXG4gICAgbmV3IFN3aXBlcihzd2lwZXJFbCwge1xyXG4gICAgICAgIHBhZ2luYXRpb246IHtcclxuICAgICAgICAgICAgZWw6ICcuc3dpcGVyLXBhZ2luYXRpb24nLFxyXG4gICAgICAgICAgICBjbGlja2FibGU6IHRydWUsXHJcbiAgICAgICAgfSxcclxuICAgICAgICBuYXZpZ2F0aW9uOiB7XHJcbiAgICAgICAgICAgIG5leHRFbDogJy5zd2lwZXItYnV0dG9uLW5leHQnLFxyXG4gICAgICAgICAgICBwcmV2RWw6ICcuc3dpcGVyLWJ1dHRvbi1wcmV2JyxcclxuICAgICAgICB9LFxyXG4gICAgICAgIGxvb3A6IHRydWUsXHJcbiAgICAgICAgb246IHtcclxuICAgICAgICAgICAgc2xpZGVDaGFuZ2U6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGlmIChjdXJyZW50U3RlcEVsKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY3VycmVudFN0ZXBFbC50ZXh0Q29udGVudCA9IHRoaXMucmVhbEluZGV4ICsgMTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG59IiwiZXhwb3J0IGZ1bmN0aW9uIGluaXRVc2VybmFtZUF2YWlsYWJpbGl0eUNoZWNrKCkge1xyXG4gICAgY29uc3QgaW5wdXQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdbZGF0YS11c2VybmFtZS1jaGVjay11cmxdJyk7XHJcbiAgICBpZiAoIWlucHV0KSByZXR1cm47XHJcblxyXG4gICAgY29uc3QgY2hlY2tVcmwgPSBpbnB1dC5kYXRhc2V0LnVzZXJuYW1lQ2hlY2tVcmw7XHJcbiAgICBjb25zdCBjaGVja1RhcmdldCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoaW5wdXQuZGF0YXNldC51c2VybmFtZUNoZWNrVGFyZ2V0KTtcclxuICAgIGNvbnN0IHJlZ2lzdGVyQnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoaW5wdXQuZGF0YXNldC5yZWdpc3RlckJ1dHRvbklkKTtcclxuICAgIGNvbnN0IGVycm9yTWVzc2FnZSA9IGNoZWNrVGFyZ2V0Py5kYXRhc2V0LmVycm9yTXNnIHx8ICdVc2VybmFtZSBhbHJlYWR5IGV4aXN0cy4nO1xyXG5cclxuICAgIGlmICghY2hlY2tUYXJnZXQgfHwgIXJlZ2lzdGVyQnV0dG9uKSByZXR1cm47XHJcblxyXG4gICAgaW5wdXQuYWRkRXZlbnRMaXN0ZW5lcignaW5wdXQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgY29uc3QgdXNlcm5hbWUgPSBpbnB1dC52YWx1ZS50cmltKCk7XHJcblxyXG4gICAgICAgIGlmICh1c2VybmFtZS5sZW5ndGggPT09IDApIHtcclxuICAgICAgICAgICAgY2hlY2tUYXJnZXQuY2xhc3NMaXN0LmFkZCgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgIHJlZ2lzdGVyQnV0dG9uLmRpc2FibGVkID0gdHJ1ZTtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZmV0Y2goYCR7Y2hlY2tVcmx9P3VzZXJuYW1lPSR7ZW5jb2RlVVJJQ29tcG9uZW50KHVzZXJuYW1lKX1gKVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpXHJcbiAgICAgICAgICAgIC50aGVuKGRhdGEgPT4ge1xyXG4gICAgICAgICAgICAgICAgaWYgKGRhdGEuZXhpc3RzKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY2hlY2tUYXJnZXQuY2xhc3NMaXN0LnJlbW92ZSgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgY2hlY2tUYXJnZXQudGV4dENvbnRlbnQgPSBlcnJvck1lc3NhZ2U7XHJcbiAgICAgICAgICAgICAgICAgICAgcmVnaXN0ZXJCdXR0b24uZGlzYWJsZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjaGVja1RhcmdldC5jbGFzc0xpc3QuYWRkKCdkLW5vbmUnKTtcclxuICAgICAgICAgICAgICAgICAgICBjaGVja1RhcmdldC50ZXh0Q29udGVudCA9ICcnO1xyXG4gICAgICAgICAgICAgICAgICAgIHJlZ2lzdGVyQnV0dG9uLmRpc2FibGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgIC5jYXRjaChlcnJvciA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmVycm9yKCdVc2VybmFtZSBjaGVjayBmYWlsZWQ6JywgZXJyb3IpO1xyXG4gICAgICAgICAgICAgICAgcmVnaXN0ZXJCdXR0b24uZGlzYWJsZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgIH0pO1xyXG59IiwiZXhwb3J0IGZ1bmN0aW9uIGluaXRTaGFyZUJ1dHRvbigpIHtcclxuICAgIGNvbnN0IHNoYXJlQnV0dG9ucyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tkYXRhLXNoYXJlLWJ1dHRvbl0nKTtcclxuXHJcbiAgICBzaGFyZUJ1dHRvbnMuZm9yRWFjaCgod3JhcHBlcikgPT4ge1xyXG4gICAgICAgIGNvbnN0IG1lc3NhZ2UgPSB3cmFwcGVyLnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLXNoYXJlLW1lc3NhZ2VdJyk7XHJcbiAgICAgICAgY29uc3QgdXJsID0gd3JhcHBlci5kYXRhc2V0LnVybCB8fCB3aW5kb3cubG9jYXRpb24uaHJlZjtcclxuXHJcbiAgICAgICAgY29uc3QgYnV0dG9uID0gd3JhcHBlci5xdWVyeVNlbGVjdG9yKCdidXR0b24nKTtcclxuICAgICAgICBpZiAoIWJ1dHRvbikgcmV0dXJuO1xyXG5cclxuICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIC8vINCh0L7QstC80LXRgdGC0LjQvNC+0YHRgtGMINGBIEhUVFBcclxuICAgICAgICAgICAgY29uc3QgdGVtcElucHV0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcclxuICAgICAgICAgICAgdGVtcElucHV0LnZhbHVlID0gdXJsO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHRlbXBJbnB1dCk7XHJcbiAgICAgICAgICAgIHRlbXBJbnB1dC5zZWxlY3QoKTtcclxuXHJcbiAgICAgICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBzdWNjZXNzID0gZG9jdW1lbnQuZXhlY0NvbW1hbmQoJ2NvcHknKTtcclxuICAgICAgICAgICAgICAgIGlmIChzdWNjZXNzICYmIG1lc3NhZ2UpIHtcclxuICAgICAgICAgICAgICAgICAgICBtZXNzYWdlLnN0eWxlLmRpc3BsYXkgPSAnaW5saW5lJztcclxuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbWVzc2FnZS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xyXG4gICAgICAgICAgICAgICAgICAgIH0sIDIwMDApO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9IGNhdGNoIChlcnIpIHtcclxuICAgICAgICAgICAgICAgIGFsZXJ0KCfQntGI0LjQsdC60LAg0L/RgNC4INC60L7Qv9C40YDQvtCy0LDQvdC40Lgg0YHRgdGL0LvQutC4OiAnICsgZXJyKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgZG9jdW1lbnQuYm9keS5yZW1vdmVDaGlsZCh0ZW1wSW5wdXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfSk7XHJcbn0iLCJpbXBvcnQgeyBpbml0SW1hZ2VVcGxvYWQgfSBmcm9tICcuL2luaXQtaW1hZ2UtdXBsb2FkJztcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiBpbml0U2ltcGxlRm9ybSgpIHtcclxuICAgIGluaXRJbWFnZVVwbG9hZCh7XHJcbiAgICAgICAgZGVsZXRlVXJsUHJlZml4OiAnL2NwYW5lbC9zaW1wbGUvaW1hZ2VzLycsXHJcbiAgICAgICAgdXBsb2FkVXJsOiAnL2NwYW5lbC9zaW1wbGUvZHJvcHpvbmUnLFxyXG4gICAgfSk7XHJcbn0iLCJpbXBvcnQgQ2hhcnQgZnJvbSAnY2hhcnQuanMvYXV0byc7XHJcblxyXG5sZXQgc21hbGxTdGVwc0NoYXJ0ID0gbnVsbDtcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiBpbml0U21hbGxTdGVwc0NoYXJ0KCkge1xyXG4gICAgY29uc3QgY2hhcnRDYW52YXMgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnc21hbGxTdGVwc0NoYXJ0Jyk7XHJcbiAgICBpZiAoIWNoYXJ0Q2FudmFzKSByZXR1cm47XHJcblxyXG4gICAgY29uc3QgcmFuZ2UgPSBjaGFydENhbnZhcy5kYXRhc2V0LnJhbmdlIHx8ICd3ZWVrcyc7XHJcbiAgICBjb25zdCB5ZWFyID0gY2hhcnRDYW52YXMuZGF0YXNldC55ZWFyO1xyXG5cclxuICAgIGNvbnN0IHdlZWtzID0gSlNPTi5wYXJzZShjaGFydENhbnZhcy5kYXRhc2V0LndlZWtzIHx8ICdbXScpO1xyXG4gICAgY29uc3QgZGF5cyAgPSBKU09OLnBhcnNlKGNoYXJ0Q2FudmFzLmRhdGFzZXQuZGF5cyAgfHwgJ1tdJyk7XHJcblxyXG4gICAgY29uc3QgcG9pbnRzID0gKHJhbmdlID09PSAnZGF5cycpID8gZGF5cyA6IHdlZWtzO1xyXG5cclxuICAgIGNvbnN0IGxhYmVscyA9IHBvaW50cy5tYXAocCA9PiAocmFuZ2UgPT09ICdkYXlzJykgPyBwLmRheSA6IHAud2Vlayk7XHJcbiAgICBjb25zdCB2YWx1ZXMgPSBwb2ludHMubWFwKHAgPT4gcC5jb3VudCk7XHJcblxyXG4gICAgaWYgKHNtYWxsU3RlcHNDaGFydCkge1xyXG4gICAgICAgIHNtYWxsU3RlcHNDaGFydC5kZXN0cm95KCk7XHJcbiAgICAgICAgc21hbGxTdGVwc0NoYXJ0ID0gbnVsbDtcclxuICAgIH1cclxuXHJcbiAgICBzbWFsbFN0ZXBzQ2hhcnQgPSBuZXcgQ2hhcnQoY2hhcnRDYW52YXMuZ2V0Q29udGV4dCgnMmQnKSwge1xyXG4gICAgICAgIHR5cGU6ICdsaW5lJyxcclxuICAgICAgICBkYXRhOiB7XHJcbiAgICAgICAgICAgIGxhYmVscyxcclxuICAgICAgICAgICAgZGF0YXNldHM6IFt7XHJcbiAgICAgICAgICAgICAgICBsYWJlbDogKHJhbmdlID09PSAnZGF5cycpXHJcbiAgICAgICAgICAgICAgICAgICAgPyBgU21hbGwgc3RlcHMg0L/QviDQtNC90Y/QvCAo0L/QvtGB0LvQtdC00L3QuNC1IDEyKWBcclxuICAgICAgICAgICAgICAgICAgICA6IGBTbWFsbCBzdGVwcyDQv9C+INC90LXQtNC10LvRj9C8ICgke3llYXJ9KWAsXHJcbiAgICAgICAgICAgICAgICBkYXRhOiB2YWx1ZXMsXHJcbiAgICAgICAgICAgICAgICBib3JkZXJXaWR0aDogMSxcclxuICAgICAgICAgICAgICAgIHRlbnNpb246IDAuMzUsXHJcbiAgICAgICAgICAgICAgICBmaWxsOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgYm9yZGVyQ29sb3I6ICcjQzE4QTQ0JyxcclxuICAgICAgICAgICAgICAgIGJhY2tncm91bmRDb2xvcjogJ3JnYmEoMjEyLCAxNjEsIDk1LCAwLjQ1KScsXHJcbiAgICAgICAgICAgICAgICBwb2ludFJhZGl1czogMyxcclxuICAgICAgICAgICAgICAgIHBvaW50SG92ZXJSYWRpdXM6IDUsXHJcbiAgICAgICAgICAgIH1dXHJcbiAgICAgICAgfSxcclxuICAgICAgICBvcHRpb25zOiB7XHJcbiAgICAgICAgICAgIHJlc3BvbnNpdmU6IHRydWUsXHJcbiAgICAgICAgICAgIG1haW50YWluQXNwZWN0UmF0aW86IGZhbHNlLFxyXG4gICAgICAgICAgICBhbmltYXRpb246IGZhbHNlLFxyXG4gICAgICAgICAgICBzY2FsZXM6IHsgeTogeyBiZWdpbkF0WmVybzogdHJ1ZSB9IH1cclxuICAgICAgICB9XHJcbiAgICB9KTtcclxufSIsImV4cG9ydCBmdW5jdGlvbiBpbml0VHlwZXdyaXRlcih7IHNlbGVjdG9yLCB0ZXh0LCBzcGVlZCA9IDEwMCB9KSB7XHJcbiAgICBjb25zdCBvdXRwdXRFbCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc2VsZWN0b3IpO1xyXG4gICAgaWYgKCFvdXRwdXRFbCB8fCAhdGV4dCkgcmV0dXJuO1xyXG5cclxuICAgIGxldCBpbmRleCA9IDA7XHJcblxyXG4gICAgZnVuY3Rpb24gdHlwZSgpIHtcclxuICAgICAgICBpZiAoaW5kZXggPCB0ZXh0Lmxlbmd0aCkge1xyXG4gICAgICAgICAgICBvdXRwdXRFbC5pbm5lckhUTUwgKz0gdGV4dC5jaGFyQXQoaW5kZXgpO1xyXG4gICAgICAgICAgICBpbmRleCsrO1xyXG4gICAgICAgICAgICBzZXRUaW1lb3V0KHR5cGUsIHNwZWVkKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgLy8g0J7Rh9C40YHRgtC40Lwg0Y3Qu9C10LzQtdC90YIg0L3QsCDQstGB0Y/QutC40Lkg0YHQu9GD0YfQsNC5XHJcbiAgICBvdXRwdXRFbC5pbm5lckhUTUwgPSAnJztcclxuICAgIHR5cGUoKTtcclxufSIsImV4cG9ydCBmdW5jdGlvbiBpbml0UHJvZmlsZVNoYXJlKCkge1xyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsICgpID0+IHtcclxuICAgICAgICAvLyDQmtC+0L/QuNGA0L7QstCw0L3QuNC1INGB0YHRi9C70LrQuFxyXG4gICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tkYXRhLWFjdGlvbj1cImNvcHktbGlua1wiXScpLmZvckVhY2goYnV0dG9uID0+IHtcclxuICAgICAgICAgICAgYnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgY29uc3QgbGluayA9IGJ1dHRvbi5kYXRhc2V0Lmxpbms7XHJcbiAgICAgICAgICAgICAgICBuYXZpZ2F0b3IuY2xpcGJvYXJkLndyaXRlVGV4dChsaW5rKVxyXG4gICAgICAgICAgICAgICAgICAgIC50aGVuKCgpID0+IGFsZXJ0KGDQodGB0YvQu9C60LAg0YHQutC+0L/QuNGA0L7QstCw0L3QsDogJHtsaW5rfWApKVxyXG4gICAgICAgICAgICAgICAgICAgIC5jYXRjaCgoKSA9PiBmYWxsYmFja0NvcHkobGluaykpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLy8g0J/QvtC00LXQu9C40YLRjNGB0Y8g0YHRgdGL0LvQutC+0LlcclxuICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdbZGF0YS1hY3Rpb249XCJzaGFyZS1saW5rXCJdJykuZm9yRWFjaChidXR0b24gPT4ge1xyXG4gICAgICAgICAgICBidXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBsaW5rID0gYnV0dG9uLmRhdGFzZXQubGluaztcclxuICAgICAgICAgICAgICAgIGNvbnN0IHVzZXJuYW1lID0gYnV0dG9uLmRhdGFzZXQudXNlcm5hbWU7XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKG5hdmlnYXRvci5zaGFyZSkge1xyXG4gICAgICAgICAgICAgICAgICAgIG5hdmlnYXRvci5zaGFyZSh7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRpdGxlOiBg0J/RgNC+0YTQuNC70Ywg0L/QvtC70YzQt9C+0LLQsNGC0LXQu9GPICR7dXNlcm5hbWV9YCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dDogYNCf0L7RgdC80L7RgtGA0LjRgtC1INC/0YDQvtGE0LjQu9GMINC/0L7Qu9GM0LfQvtCy0LDRgtC10LvRjyAke3VzZXJuYW1lfSDQvdCwIE5ldXJpZnlgLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB1cmw6IGxpbmtcclxuICAgICAgICAgICAgICAgICAgICB9KS5jYXRjaChlcnJvciA9PiBjb25zb2xlLmVycm9yKCfQntGI0LjQsdC60LAg0L/RgNC4INC/0L7Qv9GL0YLQutC1INC/0L7QtNC10LvQuNGC0YzRgdGPOicsIGVycm9yKSk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIG5hdmlnYXRvci5jbGlwYm9hcmQud3JpdGVUZXh0KGxpbmspXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC50aGVuKCgpID0+IGFsZXJ0KGDQodGB0YvQu9C60LAg0YHQutC+0L/QuNGA0L7QstCw0L3QsDogJHtsaW5rfWApKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAuY2F0Y2goKCkgPT4gZmFsbGJhY2tDb3B5KGxpbmspKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8vINCX0LDQv9Cw0YHQvdC+0Lkg0YHQv9C+0YHQvtCxINC60L7Qv9C40YDQvtCy0LDQvdC40Y8gKNC00LvRjyDRgdGC0LDRgNGL0YUg0LHRgNCw0YPQt9C10YDQvtCyKVxyXG4gICAgICAgIGZ1bmN0aW9uIGZhbGxiYWNrQ29weSh0ZXh0KSB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRleHRhcmVhID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgndGV4dGFyZWEnKTtcclxuICAgICAgICAgICAgdGV4dGFyZWEudmFsdWUgPSB0ZXh0O1xyXG4gICAgICAgICAgICBkb2N1bWVudC5ib2R5LmFwcGVuZENoaWxkKHRleHRhcmVhKTtcclxuICAgICAgICAgICAgdGV4dGFyZWEuc2VsZWN0KCk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LmV4ZWNDb21tYW5kKCdjb3B5Jyk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LmJvZHkucmVtb3ZlQ2hpbGQodGV4dGFyZWEpO1xyXG4gICAgICAgICAgICBhbGVydChg0KHRgdGL0LvQutCwINGB0LrQvtC/0LjRgNC+0LLQsNC90LA6ICR7dGV4dH1gKTtcclxuICAgICAgICB9XHJcbiAgICB9KTtcclxufSIsImltcG9ydCBDaGFydCBmcm9tICdjaGFydC5qcy9hdXRvJztcclxuXHJcbmxldCB3ZWVrbHlTY29yZUNoYXJ0ID0gbnVsbDtcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiBpbml0V2Vla2x5U2NvcmVDaGFydCgpIHtcclxuICAgIGNvbnN0IGNhbnZhcyA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd3ZWVrbHlTY29yZUNoYXJ0Jyk7XHJcbiAgICBpZiAoIWNhbnZhcykgcmV0dXJuO1xyXG5cclxuICAgIGNvbnN0IHByb2ZpbGVJZCA9IGNhbnZhcy5kYXRhc2V0LnByb2ZpbGVJZDtcclxuXHJcbiAgICBmZXRjaChgL2NwYW5lbC9wcm9ncmVzcy9qb3VybmFsL2FwaS93ZWVrbHktc2NvcmUvJHtwcm9maWxlSWR9YClcclxuICAgICAgICAudGhlbihyID0+IHIuanNvbigpKVxyXG4gICAgICAgIC50aGVuKGRhdGEgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCBsYWJlbHMgPSBkYXRhLndlZWtzLm1hcChpID0+IGkubGFiZWwpO1xyXG4gICAgICAgICAgICBjb25zdCB2YWx1ZXMgPSBkYXRhLndlZWtzLm1hcChpID0+IGkuc2NvcmUpO1xyXG4gICAgICAgICAgICBjb25zdCBsYWJlbFNjb3JlID0gY2FudmFzLmRhdGFzZXQubGFiZWxTY29yZSB8fCAnU2NvcmUnO1xyXG5cclxuICAgICAgICAgICAgaWYgKHdlZWtseVNjb3JlQ2hhcnQpIHtcclxuICAgICAgICAgICAgICAgIHdlZWtseVNjb3JlQ2hhcnQuZGVzdHJveSgpO1xyXG4gICAgICAgICAgICAgICAgd2Vla2x5U2NvcmVDaGFydCA9IG51bGw7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIHdlZWtseVNjb3JlQ2hhcnQgPSBuZXcgQ2hhcnQoY2FudmFzLmdldENvbnRleHQoJzJkJyksIHtcclxuICAgICAgICAgICAgICAgIHR5cGU6ICdsaW5lJyxcclxuICAgICAgICAgICAgICAgIGRhdGE6IHtcclxuICAgICAgICAgICAgICAgICAgICBsYWJlbHMsXHJcbiAgICAgICAgICAgICAgICAgICAgZGF0YXNldHM6IFt7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxhYmVsOiBsYWJlbFNjb3JlLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICBkYXRhOiB2YWx1ZXMsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGJvcmRlcldpZHRoOiAxLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICBib3JkZXJDb2xvcjogJ3JnYmEoMjU1LCAxNDAsIDAsIDEpJywgICAgICAgIC8vINC70LjQvdC40Y9cclxuICAgICAgICAgICAgICAgICAgICAgICAgcG9pbnRCYWNrZ3JvdW5kQ29sb3I6ICdyZ2JhKDI1NSwgMTQwLCAwLCAxKScsIC8vINGC0L7Rh9C60LhcclxuICAgICAgICAgICAgICAgICAgICAgICAgcG9pbnRCb3JkZXJDb2xvcjogJ3JnYmEoMjU1LCAxNDAsIDAsIDEpJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgZmlsbDogZmFsc2VcclxuICAgICAgICAgICAgICAgICAgICB9XVxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgIG9wdGlvbnM6IHtcclxuICAgICAgICAgICAgICAgICAgICByZXNwb25zaXZlOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgICAgIG1haW50YWluQXNwZWN0UmF0aW86IGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGFuaW1hdGlvbjogZmFsc2UsXHJcbiAgICAgICAgICAgICAgICAgICAgc2NhbGVzOiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHk6IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJlZ2luQXRaZXJvOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc3VnZ2VzdGVkTWF4OiA2MFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxufSIsIi8vIGFzc2V0cy9yZWFjdC9BcHAuanNcclxuaW1wb3J0IFJlYWN0IGZyb20gJ3JlYWN0JztcclxuaW1wb3J0IHsgQnJvd3NlclJvdXRlciwgUm91dGVzLCBSb3V0ZSB9IGZyb20gJ3JlYWN0LXJvdXRlci1kb20nO1xyXG5pbXBvcnQgTWF0cml4UGFnZSBmcm9tICcuL2NvbXBvbmVudHMvTWF0cml4UGFnZSc7XHJcbmltcG9ydCBBZGRJdGVtUGFnZSBmcm9tICcuL2NvbXBvbmVudHMvQWRkSXRlbVBhZ2UnO1xyXG5pbXBvcnQgRWRpdEl0ZW1QYWdlIGZyb20gJy4vY29tcG9uZW50cy9FZGl0SXRlbVBhZ2UnO1xyXG5mdW5jdGlvbiBBcHAoKSB7XHJcbiAgICByZXR1cm4gKFxyXG4gICAgICAgIDxCcm93c2VyUm91dGVyIGJhc2VuYW1lPVwiL2NwYW5lbFwiPlxyXG4gICAgICAgICAgICA8Um91dGVzPlxyXG4gICAgICAgICAgICAgICAgPFJvdXRlIHBhdGg9XCIvZWRpdG9yL3Nob3cvOm1hcElkXCIgZWxlbWVudD17PE1hdHJpeFBhZ2UgLz59IC8+XHJcbiAgICAgICAgICAgICAgICA8Um91dGUgcGF0aD1cIi9lZGl0b3Ivc2hvdy9jcmVhdGUvOm1hcElkXCIgZWxlbWVudD17PEFkZEl0ZW1QYWdlIC8+fSAvPlxyXG4gICAgICAgICAgICAgICAgPFJvdXRlIHBhdGg9XCIvZWRpdG9yL2l0ZW0vZWRpdC86aWRcIiBlbGVtZW50PXs8RWRpdEl0ZW1QYWdlIC8+fSAvPlxyXG4gICAgICAgICAgICAgICAgPFJvdXRlIHBhdGg9XCIvXCIgZWxlbWVudD17PGRpdj5NYXRyaXggT3ZlcnZpZXc/PC9kaXY+fSAvPlxyXG4gICAgICAgICAgICA8L1JvdXRlcz5cclxuICAgICAgICA8L0Jyb3dzZXJSb3V0ZXI+XHJcbiAgICApO1xyXG59XHJcbmV4cG9ydCBkZWZhdWx0IEFwcDsiLCJpbXBvcnQgUmVhY3QsIHsgdXNlU3RhdGUsIHVzZUVmZmVjdCB9IGZyb20gJ3JlYWN0JztcclxuaW1wb3J0IHsgdXNlUGFyYW1zLCB1c2VOYXZpZ2F0ZSB9IGZyb20gJ3JlYWN0LXJvdXRlci1kb20nO1xyXG5cclxuY29uc3QgQWRkSXRlbVBhZ2UgPSAoKSA9PiB7XHJcbiAgICBjb25zdCB7IG1hcElkIH0gPSB1c2VQYXJhbXMoKTtcclxuICAgIGNvbnN0IG5hdmlnYXRlID0gdXNlTmF2aWdhdGUoKTtcclxuICAgIGNvbnN0IFttYXBOYW1lLCBzZXRNYXBOYW1lXSA9IHVzZVN0YXRlKCcnKTtcclxuICAgIGNvbnN0IFtsb2FkaW5nLCBzZXRMb2FkaW5nXSA9IHVzZVN0YXRlKHRydWUpO1xyXG4gICAgY29uc3QgW2Vycm9yLCBzZXRFcnJvcl0gPSB1c2VTdGF0ZShudWxsKTtcclxuICAgIGNvbnN0IFt0aXRsZSwgc2V0VGl0bGVdID0gdXNlU3RhdGUoJycpO1xyXG4gICAgY29uc3QgW2Rlc2NyaXB0aW9uLCBzZXREZXNjcmlwdGlvbl0gPSB1c2VTdGF0ZSgnJyk7XHJcbiAgICBjb25zdCBbZmlsZSwgc2V0RmlsZV0gPSB1c2VTdGF0ZShudWxsKTtcclxuXHJcbiAgICB1c2VFZmZlY3QoKCkgPT4ge1xyXG4gICAgICAgIGZldGNoKGAvYXBpL21hdHJpeC9hZGQvJHttYXBJZH1gKVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiB7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXJlc3BvbnNlLm9rKSB0aHJvdyBuZXcgRXJyb3IoJ9Ce0YjQuNCx0LrQsCDQv9GA0Lgg0LfQsNCz0YDRg9C30LrQtSDQtNCw0L3QvdGL0YUg0LrQsNGA0YLRiycpO1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlc3BvbnNlLmpzb24oKTtcclxuICAgICAgICAgICAgfSlcclxuICAgICAgICAgICAgLnRoZW4oZGF0YSA9PiB7XHJcbiAgICAgICAgICAgICAgICBzZXRNYXBOYW1lKGRhdGEubmFtZSk7XHJcbiAgICAgICAgICAgICAgICBzZXRMb2FkaW5nKGZhbHNlKTtcclxuICAgICAgICAgICAgfSlcclxuICAgICAgICAgICAgLmNhdGNoKGVyciA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmVycm9yKGVycik7XHJcbiAgICAgICAgICAgICAgICBzZXRFcnJvcihlcnIubWVzc2FnZSk7XHJcbiAgICAgICAgICAgICAgICBzZXRMb2FkaW5nKGZhbHNlKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICB9LCBbbWFwSWRdKTtcclxuXHJcbiAgICBjb25zdCBoYW5kbGVUaXRsZUNoYW5nZSA9IChldmVudCkgPT4ge1xyXG4gICAgICAgIHNldFRpdGxlKGV2ZW50LnRhcmdldC52YWx1ZSk7XHJcbiAgICB9O1xyXG5cclxuICAgIGNvbnN0IGhhbmRsZURlc2NyaXB0aW9uQ2hhbmdlID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgc2V0RGVzY3JpcHRpb24oZXZlbnQudGFyZ2V0LnZhbHVlKTtcclxuICAgIH07XHJcblxyXG4gICAgY29uc3QgaGFuZGxlRmlsZUNoYW5nZSA9IChldmVudCkgPT4ge1xyXG4gICAgICAgIHNldEZpbGUoZXZlbnQudGFyZ2V0LmZpbGVzWzBdKTtcclxuICAgIH07XHJcblxyXG4gICAgY29uc3QgaGFuZGxlU3VibWl0ID0gYXN5bmMgKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICBzZXRMb2FkaW5nKHRydWUpO1xyXG4gICAgICAgIHNldEVycm9yKG51bGwpO1xyXG5cclxuICAgICAgICBjb25zdCBmb3JtRGF0YSA9IG5ldyBGb3JtRGF0YSgpO1xyXG4gICAgICAgIGZvcm1EYXRhLmFwcGVuZCgndGl0bGUnLCB0aXRsZSk7XHJcbiAgICAgICAgZm9ybURhdGEuYXBwZW5kKCdkZXNjcmlwdGlvbicsIGRlc2NyaXB0aW9uKTtcclxuICAgICAgICBpZiAoZmlsZSkge1xyXG4gICAgICAgICAgICBmb3JtRGF0YS5hcHBlbmQoJ2ZpbGUnLCBmaWxlKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgIGNvbnN0IHJlc3BvbnNlID0gYXdhaXQgZmV0Y2goYC9hcGkvbWF0cml4L2l0ZW0vY3JlYXRlLyR7bWFwSWR9YCwgeyAvLyDQmNC30LzQtdC90LXQvSBVUkxcclxuICAgICAgICAgICAgICAgIG1ldGhvZDogJ1BPU1QnLFxyXG4gICAgICAgICAgICAgICAgYm9keTogZm9ybURhdGEsXHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgaWYgKCFyZXNwb25zZS5vaykge1xyXG4gICAgICAgICAgICAgICAgY29uc3QgZXJyb3JEYXRhID0gYXdhaXQgcmVzcG9uc2UuanNvbigpOyAvLyDQn9GA0LXQtNC/0L7Qu9Cw0LPQsNC10LwsINGH0YLQviBBUEkg0LLQvtC30LLRgNCw0YnQsNC10YIgSlNPTiDRgSDQvtGI0LjQsdC60L7QuVxyXG4gICAgICAgICAgICAgICAgdGhyb3cgbmV3IEVycm9yKGVycm9yRGF0YS5lcnJvciB8fCAn0J7RiNC40LHQutCwINC/0YDQuCDQtNC+0LHQsNCy0LvQtdC90LjQuCDRjdC70LXQvNC10L3RgtCwJyk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGNvbnN0IHJlc3BvbnNlRGF0YSA9IGF3YWl0IHJlc3BvbnNlLmpzb24oKTtcclxuICAgICAgICAgICAgY29uc29sZS5sb2coJ0l0ZW0gY3JlYXRlZDonLCByZXNwb25zZURhdGEpO1xyXG4gICAgICAgICAgICBuYXZpZ2F0ZShgL2VkaXRvci9zaG93LyR7bWFwSWR9YCk7IC8vINCf0LXRgNC10L3QsNC/0YDQsNCy0LvRj9C10Lwg0L7QsdGA0LDRgtC90L5cclxuICAgICAgICB9IGNhdGNoIChlcnIpIHtcclxuICAgICAgICAgICAgY29uc29sZS5lcnJvcihlcnIpO1xyXG4gICAgICAgICAgICBzZXRFcnJvcihlcnIubWVzc2FnZSk7XHJcbiAgICAgICAgfSBmaW5hbGx5IHtcclxuICAgICAgICAgICAgc2V0TG9hZGluZyhmYWxzZSk7XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICBpZiAobG9hZGluZykgcmV0dXJuIDxkaXYgY2xhc3NOYW1lPVwidGV4dC1jZW50ZXIgbXQtNVwiPtCX0LDQs9GA0YPQt9C60LAuLi48L2Rpdj47XHJcbiAgICBpZiAoZXJyb3IpIHJldHVybiA8ZGl2IGNsYXNzTmFtZT1cInRleHQtZGFuZ2VyIHRleHQtY2VudGVyIG10LTVcIj57ZXJyb3J9PC9kaXY+O1xyXG5cclxuICAgIHJldHVybiAoXHJcbiAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJtYWluLWNvbnRlbnQtY29udGFpbmVyIGNvbnRhaW5lci1mbHVpZFwiPlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cInBhZ2UtaGVhZGVyIHJvdyBuby1ndXR0ZXJzIHB5LTRcIj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwiY29sLTEyIGNvbC1zbS00IHRleHQtY2VudGVyIHRleHQtc20tbGVmdCBtYi0wXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3NOYW1lPVwidGV4dC11cHBlcmNhc2UgcGFnZS1zdWJ0aXRsZVwiPtCh0YLRgNGD0LrRgtGD0YDQuNGA0YPQudGC0LUg0YHQstC+0LUg0LzRi9GI0LvQtdC90LjQtTwvc3Bhbj5cclxuICAgICAgICAgICAgICAgICAgICA8aDMgY2xhc3NOYW1lPVwicGFnZS10aXRsZVwiPtCi0LDQsdC70LjRhtCwOiB7bWFwTmFtZX0gLSDQlNC+0LHQsNCy0LjRgtGMINGN0LvQtdC80LXQvdGCPC9oMz5cclxuICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJyb3cgYm9yZGVyIGJnLXdoaXRlIHAtMiBtYi0zXCI+XHJcbiAgICAgICAgICAgICAgICA8YnV0dG9uIGNsYXNzTmFtZT1cImJ0biBidG4tc20gYnRuLW91dGxpbmUtc2Vjb25kYXJ5XCIgb25DbGljaz17KCkgPT4gbmF2aWdhdGUoYC9lZGl0b3Ivc2hvdy8ke21hcElkfWApfT5cclxuICAgICAgICAgICAgICAgICAgICA8aSBjbGFzc05hbWU9XCJmYXMgZmEtYW5nbGUtbGVmdFwiPjwvaT4g0J3QsNC30LDQtFxyXG4gICAgICAgICAgICAgICAgPC9idXR0b24+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cInJvdyBib3JkZXIgYmctd2hpdGVcIj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwiY29sLWxnLTEyIGNvbC1tZC0xMiBjb2wtc20tMTIgbXQtNCBtYi00IHAtNFwiPlxyXG4gICAgICAgICAgICAgICAgICAgIDxmb3JtIG9uU3VibWl0PXtoYW5kbGVTdWJtaXR9IGVuY1R5cGU9XCJtdWx0aXBhcnQvZm9ybS1kYXRhXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwiZm9ybS1ncm91cFwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPtCX0LDQs9C+0LvQvtCy0L7Qujo8L2xhYmVsPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJ0ZXh0XCIgY2xhc3NOYW1lPVwiZm9ybS1jb250cm9sXCIgbmFtZT1cInRpdGxlXCIgdmFsdWU9e3RpdGxlfSBvbkNoYW5nZT17aGFuZGxlVGl0bGVDaGFuZ2V9IC8+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cImZvcm0tZ3JvdXBcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbCBjbGFzc05hbWU9XCJmb3JtLWxhYmVsXCI+0JjQt9C+0LHRgNCw0LbQtdC90LjQtTo8L2xhYmVsPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJmaWxlXCIgbmFtZT1cImZpbGVcIiBjbGFzc05hbWU9XCJmb3JtLWNvbnRyb2xcIiBvbkNoYW5nZT17aGFuZGxlRmlsZUNoYW5nZX0gLz5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwiZm9ybS1ncm91cFwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPtCe0L/QuNGB0LDQvdC40LU6PC9sYWJlbD5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx0ZXh0YXJlYSBjbGFzc05hbWU9XCJmb3JtLWNvbnRyb2xcIiBuYW1lPVwiZGVzY3JpcHRpb25cIiByb3dzPVwiNVwiIHZhbHVlPXtkZXNjcmlwdGlvbn0gb25DaGFuZ2U9e2hhbmRsZURlc2NyaXB0aW9uQ2hhbmdlfT48L3RleHRhcmVhPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwidGV4dC1jZW50ZXIgbXQtM1wiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwic3VibWl0XCIgY2xhc3NOYW1lPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1wcmltYXJ5XCIgZGlzYWJsZWQ9e2xvYWRpbmd9PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpIGNsYXNzTmFtZT1cImZhIGZhLXNhdmVcIj48L2k+INCh0L7RhdGA0LDQvdC40YLRjFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9idXR0b24+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB7bG9hZGluZyAmJiA8c3BhbiBjbGFzc05hbWU9XCJtbC0yXCI+0KHQvtGF0YDQsNC90LXQvdC40LUuLi48L3NwYW4+fVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAge2Vycm9yICYmIDxkaXYgY2xhc3NOYW1lPVwidGV4dC1kYW5nZXIgbXQtMlwiPntlcnJvcn08L2Rpdj59XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgIDwvZm9ybT5cclxuICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICA8L2Rpdj5cclxuICAgICk7XHJcbn07XHJcblxyXG5leHBvcnQgZGVmYXVsdCBBZGRJdGVtUGFnZTsiLCJpbXBvcnQgUmVhY3QsIHsgdXNlU3RhdGUsIHVzZUVmZmVjdCB9IGZyb20gJ3JlYWN0JztcclxuaW1wb3J0IHsgdXNlUGFyYW1zLCB1c2VOYXZpZ2F0ZSB9IGZyb20gJ3JlYWN0LXJvdXRlci1kb20nO1xyXG5cclxuY29uc3QgRWRpdEl0ZW1QYWdlID0gKCkgPT4ge1xyXG4gICAgY29uc3QgeyBpZDogaXRlbUlkIH0gPSB1c2VQYXJhbXMoKTtcclxuICAgIGNvbnN0IHsgbWFwSWQgfSA9IHVzZVBhcmFtcygpOyAvLyDQktC+0LfQvNC+0LbQvdC+LCDQstCw0Lwg0L/QvtC90LDQtNC+0LHQuNGC0YHRjyBtYXBJZFxyXG4gICAgY29uc3QgbmF2aWdhdGUgPSB1c2VOYXZpZ2F0ZSgpO1xyXG4gICAgY29uc3QgW2l0ZW0sIHNldEl0ZW1dID0gdXNlU3RhdGUobnVsbCk7XHJcbiAgICBjb25zdCBbbG9hZGluZywgc2V0TG9hZGluZ10gPSB1c2VTdGF0ZSh0cnVlKTtcclxuICAgIGNvbnN0IFtlcnJvciwgc2V0RXJyb3JdID0gdXNlU3RhdGUobnVsbCk7XHJcbiAgICBjb25zdCBbdGl0bGUsIHNldFRpdGxlXSA9IHVzZVN0YXRlKCcnKTtcclxuICAgIGNvbnN0IFtkZXNjcmlwdGlvbiwgc2V0RGVzY3JpcHRpb25dID0gdXNlU3RhdGUoJycpO1xyXG4gICAgY29uc3QgW2ZpbGUsIHNldEZpbGVdID0gdXNlU3RhdGUobnVsbCk7XHJcbiAgICBjb25zdCBbcHJldmlld0ltYWdlLCBzZXRQcmV2aWV3SW1hZ2VdID0gdXNlU3RhdGUoJycpO1xyXG5cclxuICAgIHVzZUVmZmVjdCgoKSA9PiB7XHJcbiAgICAgICAgZmV0Y2goYC9hcGkvbWF0cml4L2l0ZW0vJHtpdGVtSWR9YCkgLy8g0KHQvtC30LTQsNC00LjQvCDRjdGC0L7RgiBBUEktZW5kcG9pbnRcclxuICAgICAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4ge1xyXG4gICAgICAgICAgICAgICAgaWYgKCFyZXNwb25zZS5vaykgdGhyb3cgbmV3IEVycm9yKCfQntGI0LjQsdC60LAg0L/RgNC4INC30LDQs9GA0YPQt9C60LUg0LTQsNC90L3Ri9GFINGN0LvQtdC80LXQvdGC0LAnKTtcclxuICAgICAgICAgICAgICAgIHJldHVybiByZXNwb25zZS5qc29uKCk7XHJcbiAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgIC50aGVuKGRhdGEgPT4ge1xyXG4gICAgICAgICAgICAgICAgc2V0SXRlbShkYXRhKTtcclxuICAgICAgICAgICAgICAgIHNldFRpdGxlKGRhdGEudGl0bGUpO1xyXG4gICAgICAgICAgICAgICAgc2V0RGVzY3JpcHRpb24oZGF0YS5kZXNjcmlwdGlvbik7XHJcbiAgICAgICAgICAgICAgICBzZXRQcmV2aWV3SW1hZ2UoYC9jcGFuZWwvaW1hZ2VzL2l0ZW1zLyR7ZGF0YS5maWxlbmFtZX1gKTtcclxuICAgICAgICAgICAgICAgIHNldExvYWRpbmcoZmFsc2UpO1xyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAuY2F0Y2goZXJyID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoZXJyKTtcclxuICAgICAgICAgICAgICAgIHNldEVycm9yKGVyci5tZXNzYWdlKTtcclxuICAgICAgICAgICAgICAgIHNldExvYWRpbmcoZmFsc2UpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgIH0sIFtpdGVtSWRdKTtcclxuXHJcbiAgICBjb25zdCBoYW5kbGVUaXRsZUNoYW5nZSA9IChldmVudCkgPT4ge1xyXG4gICAgICAgIHNldFRpdGxlKGV2ZW50LnRhcmdldC52YWx1ZSk7XHJcbiAgICB9O1xyXG5cclxuICAgIGNvbnN0IGhhbmRsZURlc2NyaXB0aW9uQ2hhbmdlID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgc2V0RGVzY3JpcHRpb24oZXZlbnQudGFyZ2V0LnZhbHVlKTtcclxuICAgIH07XHJcblxyXG4gICAgY29uc3QgaGFuZGxlRmlsZUNoYW5nZSA9IChldmVudCkgPT4ge1xyXG4gICAgICAgIGNvbnN0IHNlbGVjdGVkRmlsZSA9IGV2ZW50LnRhcmdldC5maWxlc1swXTtcclxuICAgICAgICBzZXRGaWxlKHNlbGVjdGVkRmlsZSk7XHJcbiAgICAgICAgaWYgKHNlbGVjdGVkRmlsZSkge1xyXG4gICAgICAgICAgICBjb25zdCByZWFkZXIgPSBuZXcgRmlsZVJlYWRlcigpO1xyXG4gICAgICAgICAgICByZWFkZXIub25sb2FkZW5kID0gKCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgc2V0UHJldmlld0ltYWdlKHJlYWRlci5yZXN1bHQpO1xyXG4gICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICByZWFkZXIucmVhZEFzRGF0YVVSTChzZWxlY3RlZEZpbGUpO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIHNldFByZXZpZXdJbWFnZShpdGVtPy5maWxlbmFtZSA/IGAvY3BhbmVsL2ltYWdlcy9pdGVtcy8ke2l0ZW0uZmlsZW5hbWV9YCA6ICcnKTtcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIGNvbnN0IGhhbmRsZVN1Ym1pdCA9IGFzeW5jIChldmVudCkgPT4ge1xyXG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgc2V0TG9hZGluZyh0cnVlKTtcclxuICAgICAgICBzZXRFcnJvcihudWxsKTtcclxuXHJcbiAgICAgICAgY29uc3QgZm9ybURhdGEgPSBuZXcgRm9ybURhdGEoKTtcclxuICAgICAgICBmb3JtRGF0YS5hcHBlbmQoJ3RpdGxlJywgdGl0bGUpO1xyXG4gICAgICAgIGZvcm1EYXRhLmFwcGVuZCgnZGVzY3JpcHRpb24nLCBkZXNjcmlwdGlvbik7XHJcbiAgICAgICAgaWYgKGZpbGUpIHtcclxuICAgICAgICAgICAgZm9ybURhdGEuYXBwZW5kKCdmaWxlJywgZmlsZSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICB0cnkge1xyXG4gICAgICAgICAgICBjb25zdCByZXNwb25zZSA9IGF3YWl0IGZldGNoKGAvYXBpL21hdHJpeC9pdGVtL2VkaXQvJHtpdGVtSWR9YCwgeyAvLyDQodC+0LfQtNCw0LTQuNC8INGN0YLQvtGCIEFQSS1lbmRwb2ludFxyXG4gICAgICAgICAgICAgICAgbWV0aG9kOiAnUE9TVCcsXHJcbiAgICAgICAgICAgICAgICBib2R5OiBmb3JtRGF0YSxcclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBpZiAoIXJlc3BvbnNlLm9rKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBlcnJvckRhdGEgPSBhd2FpdCByZXNwb25zZS5qc29uKCk7XHJcbiAgICAgICAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoZXJyb3JEYXRhLmVycm9yIHx8ICfQntGI0LjQsdC60LAg0L/RgNC4INC+0LHQvdC+0LLQu9C10L3QuNC4INGN0LvQtdC80LXQvdGC0LAnKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgY29uc3QgcmVzcG9uc2VEYXRhID0gYXdhaXQgcmVzcG9uc2UuanNvbigpO1xyXG4gICAgICAgICAgICBjb25zb2xlLmxvZygnSXRlbSB1cGRhdGVkOicsIHJlc3BvbnNlRGF0YSk7XHJcbiAgICAgICAgICAgIG5hdmlnYXRlKGAvZWRpdG9yL3Nob3cvJHtpdGVtLm1hdHJpeE1hcElkfWApOyAvLyDQn9C10YDQtdC90LDQv9GA0LDQstC70Y/QtdC8INC90LAg0YHRgtGA0LDQvdC40YbRgyDQvNCw0YLRgNC40YbRi1xyXG4gICAgICAgIH0gY2F0Y2ggKGVycikge1xyXG4gICAgICAgICAgICBjb25zb2xlLmVycm9yKGVycik7XHJcbiAgICAgICAgICAgIHNldEVycm9yKGVyci5tZXNzYWdlKTtcclxuICAgICAgICB9IGZpbmFsbHkge1xyXG4gICAgICAgICAgICBzZXRMb2FkaW5nKGZhbHNlKTtcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxuICAgIGlmIChsb2FkaW5nKSByZXR1cm4gPGRpdiBjbGFzc05hbWU9XCJ0ZXh0LWNlbnRlciBtdC01XCI+0JfQsNCz0YDRg9C30LrQsC4uLjwvZGl2PjtcclxuICAgIGlmIChlcnJvcikgcmV0dXJuIDxkaXYgY2xhc3NOYW1lPVwidGV4dC1kYW5nZXIgdGV4dC1jZW50ZXIgbXQtNVwiPntlcnJvcn08L2Rpdj47XHJcbiAgICBpZiAoIWl0ZW0pIHJldHVybiA8ZGl2PtCt0LvQtdC80LXQvdGCINC90LUg0L3QsNC50LTQtdC9PC9kaXY+O1xyXG5cclxuICAgIHJldHVybiAoXHJcbiAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJtYWluLWNvbnRlbnQtY29udGFpbmVyIGNvbnRhaW5lci1mbHVpZFwiPlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cInBhZ2UtaGVhZGVyIHJvdyBuby1ndXR0ZXJzIHB5LTRcIj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwiY29sLTEyIGNvbC1zbS00IHRleHQtY2VudGVyIHRleHQtc20tbGVmdCBtYi0wXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3NOYW1lPVwidGV4dC11cHBlcmNhc2UgcGFnZS1zdWJ0aXRsZVwiPtCg0LXQtNCw0LrRgtC40YDQvtCy0LDQvdC40LUg0Y3Qu9C10LzQtdC90YLQsDwvc3Bhbj5cclxuICAgICAgICAgICAgICAgICAgICA8aDMgY2xhc3NOYW1lPVwicGFnZS10aXRsZVwiPtCg0LXQtNCw0LrRgtC40YDQvtCy0LDRgtGMOiB7aXRlbS50aXRsZX08L2gzPlxyXG4gICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cInJvdyBib3JkZXIgYmctd2hpdGUgcC0yIG1iLTNcIj5cclxuICAgICAgICAgICAgICAgIDxidXR0b24gY2xhc3NOYW1lPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1zZWNvbmRhcnlcIiBvbkNsaWNrPXsoKSA9PiBuYXZpZ2F0ZShgL2VkaXRvci9zaG93LyR7aXRlbS5tYXRyaXhNYXBJZH1gKX0+XHJcbiAgICAgICAgICAgICAgICAgICAgPGkgY2xhc3NOYW1lPVwiZmFzIGZhLWFuZ2xlLWxlZnRcIj48L2k+INCd0LDQt9Cw0LRcclxuICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJyb3cgYm9yZGVyIGJnLXdoaXRlXCI+XHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cImNvbC1sZy0xMiBjb2wtbWQtMTIgY29sLXNtLTEyIG10LTQgbWItNCBwLTRcIj5cclxuICAgICAgICAgICAgICAgICAgICA8Zm9ybSBvblN1Ym1pdD17aGFuZGxlU3VibWl0fSBlbmNUeXBlPVwibXVsdGlwYXJ0L2Zvcm0tZGF0YVwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cImZvcm0tZ3JvdXBcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD7Ql9Cw0LPQvtC70L7QstC+0Lo6PC9sYWJlbD5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCB0eXBlPVwidGV4dFwiIGNsYXNzTmFtZT1cImZvcm0tY29udHJvbFwiIG5hbWU9XCJ0aXRsZVwiIHZhbHVlPXt0aXRsZX0gb25DaGFuZ2U9e2hhbmRsZVRpdGxlQ2hhbmdlfSAvPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJmb3JtLWdyb3VwXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWwgY2xhc3NOYW1lPVwiZm9ybS1sYWJlbFwiPtCY0LfQvtCx0YDQsNC20LXQvdC40LU6PC9sYWJlbD5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHtwcmV2aWV3SW1hZ2UgJiYgKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbWcgc3JjPXtwcmV2aWV3SW1hZ2V9IGFsdD1cIlByZXZpZXdcIiBjbGFzc05hbWU9XCJpbWctZmx1aWQgbWItMlwiIHN0eWxlPXt7IG1heFdpZHRoOiAnMjAwcHgnIH19IC8+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICApfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJmaWxlXCIgbmFtZT1cImZpbGVcIiBjbGFzc05hbWU9XCJmb3JtLWNvbnRyb2xcIiBvbkNoYW5nZT17aGFuZGxlRmlsZUNoYW5nZX0gLz5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwiZm9ybS1ncm91cFwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPtCe0L/QuNGB0LDQvdC40LU6PC9sYWJlbD5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx0ZXh0YXJlYSBjbGFzc05hbWU9XCJmb3JtLWNvbnRyb2xcIiBuYW1lPVwiZGVzY3JpcHRpb25cIiByb3dzPVwiNVwiIHZhbHVlPXtkZXNjcmlwdGlvbn0gb25DaGFuZ2U9e2hhbmRsZURlc2NyaXB0aW9uQ2hhbmdlfT48L3RleHRhcmVhPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwidGV4dC1jZW50ZXIgbXQtM1wiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwic3VibWl0XCIgY2xhc3NOYW1lPVwiYnRuIGJ0bi1zbSBidG4tb3V0bGluZS1wcmltYXJ5XCIgZGlzYWJsZWQ9e2xvYWRpbmd9PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpIGNsYXNzTmFtZT1cImZhIGZhLXNhdmVcIj48L2k+INCh0L7RhdGA0LDQvdC40YLRjCDQuNC30LzQtdC90LXQvdC40Y9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAge2xvYWRpbmcgJiYgPHNwYW4gY2xhc3NOYW1lPVwibWwtMlwiPtCh0L7RhdGA0LDQvdC10L3QuNC1Li4uPC9zcGFuPn1cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHtlcnJvciAmJiA8ZGl2IGNsYXNzTmFtZT1cInRleHQtZGFuZ2VyIG10LTJcIj57ZXJyb3J9PC9kaXY+fVxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8L2Zvcm0+XHJcbiAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgPC9kaXY+XHJcbiAgICApO1xyXG59O1xyXG5cclxuZXhwb3J0IGRlZmF1bHQgRWRpdEl0ZW1QYWdlOyIsImltcG9ydCBSZWFjdCBmcm9tICdyZWFjdCc7XHJcbmltcG9ydCB7IHVzZU5hdmlnYXRlIH0gZnJvbSAncmVhY3Qtcm91dGVyLWRvbSc7XHJcblxyXG5jb25zdCBJdGVtTW9kYWwgPSAoeyBpdGVtLCBvbkNsb3NlIH0pID0+IHtcclxuICAgIGlmICghaXRlbSkge1xyXG4gICAgICAgIHJldHVybiBudWxsO1xyXG4gICAgfVxyXG4gICAgY29uc3QgbmF2aWdhdGUgPSB1c2VOYXZpZ2F0ZSgpO1xyXG4gICAgY29uc3QgaGFuZGxlRWRpdENsaWNrID0gKCkgPT4ge1xyXG4gICAgICAgIG5hdmlnYXRlKGAvZWRpdG9yL2l0ZW0vZWRpdC8ke2l0ZW1bMF19YCk7IC8vINCf0YDQtdC00L/QvtC70LDQs9Cw0LXQvNGL0Lkg0LzQsNGA0YjRgNGD0YIg0YDQtdC00LDQutGC0LjRgNC+0LLQsNC90LjRj1xyXG4gICAgICAgIG9uQ2xvc2UoKTsgLy8g0JfQsNC60YDRi9Cy0LDQtdC8INC80L7QtNCw0LvRjNC90L7QtSDQvtC60L3QviDQv9C+0YHQu9C1INC90LDQttCw0YLQuNGPIFwi0KDQtdC00LDQutGC0LjRgNC+0LLQsNGC0YxcIlxyXG4gICAgfTtcclxuXHJcbiAgICByZXR1cm4gKFxyXG4gICAgICAgIDxkaXYgY2xhc3NOYW1lPVwibW9kYWwgZmFkZSBzaG93XCIgc3R5bGU9e3sgZGlzcGxheTogJ2Jsb2NrJyB9fSBhcmlhLW1vZGFsPVwidHJ1ZVwiIHJvbGU9XCJkaWFsb2dcIj5cclxuICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJtb2RhbC1kaWFsb2dcIj5cclxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwibW9kYWwtY29udGVudFwiPlxyXG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwibW9kYWwtaGVhZGVyXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxoNSBjbGFzc05hbWU9XCJtb2RhbC10aXRsZVwiPtCY0L3RhNC+0YDQvNCw0YbQuNGPINC+0LEg0Y3Qu9C10LzQtdC90YLQtTwvaDU+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzTmFtZT1cImNsb3NlXCIgb25DbGljaz17b25DbG9zZX0gYXJpYS1sYWJlbD1cIkNsb3NlXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8c3BhbiBhcmlhLWhpZGRlbj1cInRydWVcIj4mdGltZXM7PC9zcGFuPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2J1dHRvbj5cclxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cIm1vZGFsLWJvZHlcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAge2l0ZW1bMV0gJiYgPGRpdiBzdHlsZT17eyB0ZXh0QWxpZ246ICdqdXN0aWZ5JyB9fT57aXRlbVsxXX08L2Rpdj59XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHshaXRlbVsxXSAmJiA8ZGl2IGNsYXNzTmFtZT1cInRleHQtbXV0ZWRcIj7QndC10YIg0LTQvtC/0L7Qu9C90LjRgtC10LvRjNC90L7QuSDQuNC90YTQvtGA0LzQsNGG0LjQuC48L2Rpdj59XHJcbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XHJcbiAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJtb2RhbC1mb290ZXJcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJkLWZsZXgganVzdGlmeS1jb250ZW50LWNlbnRlclwiPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3NOYW1lPVwiYnRuIGJ0bi1wcmltYXJ5IG1yLTJcIiBvbkNsaWNrPXtoYW5kbGVFZGl0Q2xpY2t9PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgINCg0LXQtNCw0LrRgtC40YDQvtCy0LDRgtGMXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2J1dHRvbj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzTmFtZT1cImJ0biBidG4tc2Vjb25kYXJ5XCIgb25DbGljaz17b25DbG9zZX0+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg0JfQsNC60YDRi9GC0YxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvYnV0dG9uPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICA8L2Rpdj5cclxuICAgICk7XHJcbn07XHJcblxyXG5leHBvcnQgZGVmYXVsdCBJdGVtTW9kYWw7IiwiaW1wb3J0IFJlYWN0LCB7IHVzZVN0YXRlIH0gZnJvbSAncmVhY3QnO1xyXG5pbXBvcnQgSXRlbU1vZGFsIGZyb20gJy4vSXRlbU1vZGFsJztcclxuaW1wb3J0IHN0eWxlcyBmcm9tICcuLi8uLi9zdHlsZXMvTWF0cml4R3JpZC5tb2R1bGUuY3NzJztcclxuXHJcbi8vINCk0YPQvdC60YbQuNGPINC00LvRjyDQv9C+0LvRg9GH0LXQvdC40Y8g0LXQstGA0LXQudGB0LrQuNGFINCx0YPQutCyINC/0L4g0LjQvdC00LXQutGB0YMgKDEtYmFzZWQpXHJcbmNvbnN0IGdldFNhbnNrcml0TGV0dGVyID0gKGluZGV4KSA9PiB7XHJcbiAgICBjb25zdCBsZXR0ZXJzID0gWyfXkCcsICfXkScsICfXkicsICfXkycsICfXlCcsICfXlScsICfXlicsICfXlycsICfXmCddO1xyXG4gICAgcmV0dXJuIGxldHRlcnNbaW5kZXggLSAxXSB8fCAnJztcclxufTtcclxuXHJcbmNvbnN0IE1hdHJpeEdyaWQgPSAoeyBpdGVtcyA9IFtdIH0pID0+IHtcclxuICAgIGNvbnN0IFtzZWxlY3RlZEl0ZW0sIHNldFNlbGVjdGVkSXRlbV0gPSB1c2VTdGF0ZShudWxsKTtcclxuXHJcbiAgICBjb25zdCBoYW5kbGVDZWxsQ2xpY2sgPSAoaXRlbSkgPT4ge1xyXG4gICAgICAgIHNldFNlbGVjdGVkSXRlbShpdGVtKTtcclxuICAgIH07XHJcblxyXG4gICAgY29uc3QgaGFuZGxlQ2xvc2VNb2RhbCA9ICgpID0+IHtcclxuICAgICAgICBzZXRTZWxlY3RlZEl0ZW0obnVsbCk7XHJcbiAgICB9O1xyXG5cclxuICAgIHJldHVybiAoXHJcbiAgICAgICAgPD5cclxuICAgICAgICAgICAgPHRhYmxlIGNsYXNzTmFtZT1cInRhYmxlIHRhYmxlLWJvcmRlcmVkIG1hdHJpeC1ncmlkXCI+XHJcbiAgICAgICAgICAgICAgICA8dGJvZHk+XHJcbiAgICAgICAgICAgICAgICB7WzAsIDEsIDJdLm1hcChyb3cgPT4gKFxyXG4gICAgICAgICAgICAgICAgICAgIDx0ciBrZXk9e3Jvd30+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHtbMCwgMSwgMl0ubWFwKGNvbCA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zdCBpbmRleCA9IHJvdyAqIDMgKyBjb2w7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zdCBpdGVtID0gaXRlbXNbaW5kZXhdO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgaXNFdmVuID0gKGluZGV4ICsgMSkgJSAyID09PSAwO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgZGVmYXVsdENvbG9yID0gaXNFdmVuID8gJyNmN2Y3ZjcnIDogJ3RyYW5zcGFyZW50JztcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx0ZFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBrZXk9e2luZGV4fVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU9XCJ0ZFwiXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHN0eWxlPXt7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjdXJzb3I6ICdwb2ludGVyJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRyYW5zaXRpb246ICdiYWNrZ3JvdW5kLWNvbG9yIDAuM3MgZWFzZScsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBiYWNrZ3JvdW5kQ29sb3I6IGRlZmF1bHRDb2xvclxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9fVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBvbkNsaWNrPXsoKSA9PiBoYW5kbGVDZWxsQ2xpY2soaXRlbSl9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9uTW91c2VFbnRlcj17KGUpID0+IGUuY3VycmVudFRhcmdldC5zdHlsZS5iYWNrZ3JvdW5kQ29sb3IgPSAnI2U2ZTZlNid9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9uTW91c2VMZWF2ZT17KGUpID0+IGUuY3VycmVudFRhcmdldC5zdHlsZS5iYWNrZ3JvdW5kQ29sb3IgPSBkZWZhdWx0Q29sb3J9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB7aXRlbSAmJiAoXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3NOYW1lPVwiZGV2c3R5bGUtc2Fuc2tyaXQtbGV0dGVyXCI+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHtnZXRTYW5za3JpdExldHRlcihpbmRleCArIDEpfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHtpdGVtWzFdfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC8+XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICl9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC90ZD5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pfVxyXG4gICAgICAgICAgICAgICAgICAgIDwvdHI+XHJcbiAgICAgICAgICAgICAgICApKX1cclxuICAgICAgICAgICAgICAgIDwvdGJvZHk+XHJcbiAgICAgICAgICAgIDwvdGFibGU+XHJcblxyXG4gICAgICAgICAgICB7c2VsZWN0ZWRJdGVtICYmIChcclxuICAgICAgICAgICAgICAgIDxJdGVtTW9kYWwgaXRlbT17c2VsZWN0ZWRJdGVtfSBvbkNsb3NlPXtoYW5kbGVDbG9zZU1vZGFsfSAvPlxyXG4gICAgICAgICAgICApfVxyXG4gICAgICAgIDwvPlxyXG4gICAgKTtcclxufTtcclxuXHJcbmV4cG9ydCBkZWZhdWx0IE1hdHJpeEdyaWQ7IiwiaW1wb3J0IFJlYWN0LCB7IHVzZUVmZmVjdCwgdXNlU3RhdGUgfSBmcm9tICdyZWFjdCc7XHJcbmltcG9ydCBNYXRyaXhHcmlkIGZyb20gJy4vTWF0cml4R3JpZCc7XHJcbmltcG9ydCB7IExpbmssIHVzZVBhcmFtcyB9IGZyb20gJ3JlYWN0LXJvdXRlci1kb20nO1xyXG5cclxuY29uc3QgTWF0cml4UGFnZSA9ICgpID0+IHtcclxuICAgIGNvbnN0IHsgbWFwSWQgfSA9IHVzZVBhcmFtcygpOyAvLyDQn9C+0LvRg9GH0LDQtdC8IG1hcElkINC40LcgVVJMXHJcbiAgICBjb25zdCBbbWFwLCBzZXRNYXBdID0gdXNlU3RhdGUobnVsbCk7XHJcbiAgICBjb25zdCBbbG9hZGluZywgc2V0TG9hZGluZ10gPSB1c2VTdGF0ZSh0cnVlKTtcclxuICAgIGNvbnN0IFtlcnJvciwgc2V0RXJyb3JdID0gdXNlU3RhdGUobnVsbCk7XHJcblxyXG4gICAgdXNlRWZmZWN0KCgpID0+IHtcclxuICAgICAgICBmZXRjaChgL2FwaS9tYXRyaXgvJHttYXBJZH1gKVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiB7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXJlc3BvbnNlLm9rKSB0aHJvdyBuZXcgRXJyb3IoJ9Ce0YjQuNCx0LrQsCDQv9GA0Lgg0LfQsNCz0YDRg9C30LrQtSDQutCw0YDRgtGLJyk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gcmVzcG9uc2UuanNvbigpO1xyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAudGhlbihkYXRhID0+IHtcclxuICAgICAgICAgICAgICAgIHNldE1hcChkYXRhKTtcclxuICAgICAgICAgICAgICAgIHNldExvYWRpbmcoZmFsc2UpO1xyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAuY2F0Y2goZXJyID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoZXJyKTtcclxuICAgICAgICAgICAgICAgIHNldEVycm9yKGVyci5tZXNzYWdlKTtcclxuICAgICAgICAgICAgICAgIHNldExvYWRpbmcoZmFsc2UpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgIH0sIFttYXBJZF0pO1xyXG5cclxuICAgIGlmIChsb2FkaW5nKSByZXR1cm4gPGRpdiBjbGFzc05hbWU9XCJ0ZXh0LWNlbnRlciBtdC01XCI+0JfQsNCz0YDRg9C30LrQsCDQutCw0YDRgtGLLi4uPC9kaXY+O1xyXG4gICAgaWYgKGVycm9yKSByZXR1cm4gPGRpdiBjbGFzc05hbWU9XCJ0ZXh0LWRhbmdlciB0ZXh0LWNlbnRlciBtdC01XCI+e2Vycm9yfTwvZGl2PjtcclxuICAgIGlmICghbWFwKSByZXR1cm4gbnVsbDtcclxuXHJcbiAgICBjb25zdCB7IG5hbWUsIGRlc2NyaXB0aW9uLCBmaWxlbmFtZSwgaXRlbXMgPSBbXSB9ID0gbWFwO1xyXG5cclxuICAgIHJldHVybiAoXHJcbiAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJyb3cgYm9yZGVyIGJnLXdoaXRlIHAtNFwiPlxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cImNvbC14bC02IGNvbC1sZy04IGNvbC1tZC0xMiBjb2wtc20tMTIgbXgtYXV0b1wiPlxyXG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9XCJyYXRpby0xNng5IG1iLTNcIj5cclxuICAgICAgICAgICAgICAgICAgICA8aW1nXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHNyYz17ZmlsZW5hbWUgPyBgL2NwYW5lbC9pbWFnZXMvbWFwcy8ke2ZpbGVuYW1lfWAgOiBgL2NwYW5lbC9pbWFnZXMvbWFwcy9kZWZhdWx0LmpwZ2B9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZT1cImltZy1mbHVpZCByb3VuZGVkXCJcclxuICAgICAgICAgICAgICAgICAgICAgICAgYWx0PVwiTWFwIHByZXZpZXdcIlxyXG4gICAgICAgICAgICAgICAgICAgIC8+XHJcbiAgICAgICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICAgICAgICAgIDxoNiBjbGFzc05hbWU9XCJ0ZXh0LWNlbnRlclwiIHN0eWxlPXt7Zm9udEZhbWlseTogJ0dlb3JnaWEnfX0+XHJcbiAgICAgICAgICAgICAgICAgICAgPHN0cm9uZz57bmFtZX08L3N0cm9uZz5cclxuICAgICAgICAgICAgICAgIDwvaDY+XHJcbiAgICAgICAgICAgICAgICA8cCBjbGFzc05hbWU9XCJ0ZXh0LWNlbnRlclwiIHN0eWxlPXt7Zm9udFNpemU6ICdzbWFsbCcsIGZvbnRGYW1pbHk6ICdHZW9yZ2lhJ319PlxyXG4gICAgICAgICAgICAgICAgICAgIHtkZXNjcmlwdGlvbn1cclxuICAgICAgICAgICAgICAgIDwvcD5cclxuXHJcbiAgICAgICAgICAgICAgICA8TWF0cml4R3JpZCBpdGVtcz17aXRlbXN9Lz5cclxuXHJcbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzTmFtZT1cInRleHQtY2VudGVyIG10LTNcIj5cclxuICAgICAgICAgICAgICAgICAgICA8TGluayB0bz17YC9lZGl0b3Ivc2hvdy9jcmVhdGUvJHttYXBJZH1gfSBjbGFzc05hbWU9XCJidG4gYnRuLXNtIGJ0bi1vdXRsaW5lLXByaW1hcnlcIj5cclxuICAgICAgICAgICAgICAgICAgICAgICAgPGkgY2xhc3NOYW1lPVwiZmEgZmEtcGx1c1wiPjwvaT4g0JTQvtCx0LDQstC40YLRjFxyXG4gICAgICAgICAgICAgICAgICAgIDwvTGluaz5cclxuICAgICAgICAgICAgICAgIDwvZGl2PlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICA8L2Rpdj5cclxuICAgICk7XHJcbn07XHJcblxyXG5leHBvcnQgZGVmYXVsdCBNYXRyaXhQYWdlO1xyXG4iLCJpbXBvcnQgeyBDb250cm9sbGVyIH0gZnJvbSAnQGhvdHdpcmVkL3N0aW11bHVzJztcbmltcG9ydCAnQGhvdHdpcmVkL3R1cmJvJztcblxuY2xhc3MgdHVyYm9fY29udHJvbGxlciBleHRlbmRzIENvbnRyb2xsZXIge1xufVxuXG5leHBvcnQgeyB0dXJib19jb250cm9sbGVyIGFzIGRlZmF1bHQgfTtcbiIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6WyJDb250cm9sbGVyIiwiX2RlZmF1bHQiLCJfQ29udHJvbGxlciIsIl9jbGFzc0NhbGxDaGVjayIsIl9jYWxsU3VwZXIiLCJhcmd1bWVudHMiLCJfaW5oZXJpdHMiLCJfY3JlYXRlQ2xhc3MiLCJrZXkiLCJ2YWx1ZSIsImNvcHkiLCJ0ZXh0Iiwic291cmNlVGFyZ2V0IiwiaW5uZXJUZXh0IiwidGV4dENvbnRlbnQiLCJ0cmltIiwic2VsZiIsIm9rIiwic2hvd0ZlZWRiYWNrIiwibmF2aWdhdG9yIiwiY2xpcGJvYXJkIiwid3JpdGVUZXh0IiwidGhlbiIsImZhbGxiYWNrQ29weSIsImRvbmUiLCJ0YSIsImRvY3VtZW50IiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsInN0eWxlIiwicG9zaXRpb24iLCJsZWZ0IiwiYm9keSIsImFwcGVuZENoaWxkIiwic2VsZWN0IiwiZXhlY0NvbW1hbmQiLCJlIiwicmVtb3ZlQ2hpbGQiLCJtZXNzYWdlIiwiaGFzRmVlZGJhY2tUYXJnZXQiLCJmZWVkYmFja1RhcmdldCIsImNsYXNzTGlzdCIsInJlbW92ZSIsInNldFRpbWVvdXQiLCJhZGQiLCJoYXNCdXR0b25UYXJnZXQiLCJvcmlnaW5hbCIsImJ1dHRvblRhcmdldCIsImlubmVySFRNTCIsIl9kZWZpbmVQcm9wZXJ0eSIsImRlZmF1bHQiLCJjb25uZWN0IiwiZWxlbWVudCIsImluaXRBY2NvdW50TGlua3MiLCJpbml0SW5mb0JveCIsImluaXRCb3R0b21BZGRNZW51VG9nZ2xlIiwiaW5pdE1hdHJpeFNob3dJdGVtIiwiaW5pdENLRWRpdG9yIiwiaW5pdE1hdHJpeEl0ZW1JbWFnZVVwbG9hZCIsImluaXRNYXRyaXhJdGVtRGVsZXRlTW9kYWwiLCJpbml0QWN0aXZpdHlDaGFydCIsImluaXRTbWFsbFN0ZXBzQ2hhcnQiLCJpbml0V2Vla2x5U2NvcmVDaGFydCIsImluaXRUeXBld3JpdGVyIiwiaW5pdEV2ZW50c0ZlZWQiLCJpbml0UHJvZmlsZUNoYXQiLCJpbml0Q29tbWVudHMiLCJpbml0RXZlbnRGb3JtIiwiaW5pdFNpbXBsZUZvcm0iLCJpbml0UGljdHVyZUZvcm0iLCJpbml0Q2hhdE1vZGFsIiwiaW5pdFVzZXJuYW1lQXZhaWxhYmlsaXR5Q2hlY2siLCJpbml0UHJlc2VudGF0aW9uU3dpcGVyIiwiaW5pdFByb2ZpbGVTaGFyZSIsImluaXRCb29rbWFya1RvZ2dsZSIsImluaXRBY2NvdW50SW5mb0JveCIsImluaXRJbnRybyIsImluaXRBZGRMdXhNZW51IiwiaW5pdFNoYXJlQnV0dG9uIiwiaW5pdFNhdmVCdXR0b25Db3VudGRvd24iLCJpbml0R29hbFBlcmNlbnRDaGFydHMiLCJSZWFjdCIsImNyZWF0ZVJvb3QiLCJBcHAiLCJqc3giLCJfanN4Iiwicm9vdEVsIiwiZ2V0RWxlbWVudEJ5SWQiLCJyb290IiwicmVuZGVyIiwiYWRkRXZlbnRMaXN0ZW5lciIsIm91dHB1dEVsIiwiZGF0YXNldCIsImluaXRpYWxpemVkIiwidGVzdEJ0biIsIm92ZXJsYXkiLCJpbml0Q2hhdEludHJvVHlwaW5nIiwic2VsZWN0b3IiLCJzcGVlZCIsInRhcmdldCIsImlkIiwiZXZlbnQiLCJkZXRhaWwiLCJmb3JtU3VibWlzc2lvbiIsInJlc3VsdCIsInN1Y2Nlc3MiLCJpbml0QWxsIiwicGljdHVyZUZvcm0iLCJxdWVyeVNlbGVjdG9yIiwic2ltcGxlRm9ybSIsImpvdXJuYWxGb3JtIiwiZXZlbnRGb3JtIiwiY2hhdEJ1dHRvbiIsInByb2ZpbGVJZCIsImNvbmNhdCIsInN0YXJ0U3RpbXVsdXNBcHAiLCJhcHAiLCJyZXF1aXJlIiwiY29udGV4dCIsImluaXRTY3JvbGxDb250YWluZXIiLCJzY3JvbGxDb250YWluZXIiLCJzY3JvbGxUbyIsImJlaGF2aW9yIiwid2luZG93IiwiYm9va21hcmtMaW5rIiwibG9jYXRpb24iLCJocmVmIiwidXJsIiwiZGVmYXVsdExpbmsiLCJmZWF0dXJlZExpbmsiLCJxdWVyeVNlbGVjdG9yQWxsIiwiZm9yRWFjaCIsImNlbGwiLCJnZXRBdHRyaWJ1dGUiLCJDaGFydCIsImFjdGl2aXR5Q2hhcnQiLCJjaGFydENhbnZhcyIsInllYXIiLCJmZXRjaCIsInJlc3BvbnNlIiwianNvbiIsImRhdGEiLCJldmVudExhYmVscyIsImV2ZW50cyIsIm1hcCIsIml0ZW0iLCJtb250aCIsImV2ZW50VmFsdWVzIiwiY291bnQiLCJkZXN0cm95IiwiZ2V0Q29udGV4dCIsInR5cGUiLCJsYWJlbHMiLCJkYXRhc2V0cyIsImxhYmVsIiwiYmFja2dyb3VuZENvbG9yIiwiYm9yZGVyQ29sb3IiLCJib3JkZXJXaWR0aCIsIm9wdGlvbnMiLCJyZXNwb25zaXZlIiwibWFpbnRhaW5Bc3BlY3RSYXRpbyIsImFuaW1hdGlvbiIsInNjYWxlcyIsInkiLCJiZWdpbkF0WmVybyIsInRvZ2dsZVNlbGVjdG9yIiwibGVuZ3RoIiwidW5kZWZpbmVkIiwib3ZlcmxheVNlbGVjdG9yIiwidG9nZ2xlQnV0dG9uIiwiY2xvc2VCdG4iLCJjb25zb2xlIiwid2FybiIsIm5ld1RvZ2dsZUJ1dHRvbiIsImNsb25lTm9kZSIsInBhcmVudE5vZGUiLCJyZXBsYWNlQ2hpbGQiLCJpc01lbnVPcGVuIiwiY29udGFpbnMiLCJwcmV2ZW50RGVmYXVsdCIsImNsaWNrZWRJbnNpZGUiLCJtZW51V3JhcHBlciIsIkNLRURJVE9SIiwidGV4dGFyZWEiLCJuYW1lIiwiY2tlZGl0b3JJbml0aWFsaXplZCIsImluc3RhbmNlcyIsInJlcGxhY2UiLCJjaGF0VGV4dCIsImNvbmZpcm1Nb2RhbEVsIiwiY29uZmlybUJ1dHRvbiIsImNvbmZpcm1Nb2RhbCIsImJvb3RzdHJhcCIsIk1vZGFsIiwiZXJyb3IiLCJjaGVja1VybCIsImNoYXRDaGVja1VybCIsImNyZWF0ZVVybCIsImNoYXRDcmVhdGVVcmwiLCJzaG93IiwiYnV0dG9uIiwiY29tbWVudElkIiwiY29tbWVudFRleHQiLCJjbG9zZXN0IiwiZm9ybSIsInJlcGx5Q29udGFpbmVyIiwicGFyZW50SW5wdXQiLCJyZXBseUlucHV0IiwicmVhZE9ubHkiLCJjaGF0TmV3VXJsIiwiaW5pdEltYWdlVXBsb2FkIiwiZGVsZXRlVXJsUHJlZml4IiwidXBsb2FkVXJsIiwiX3JlZ2VuZXJhdG9yUnVudGltZSIsInQiLCJyIiwiT2JqZWN0IiwicHJvdG90eXBlIiwibiIsImhhc093blByb3BlcnR5IiwibyIsImRlZmluZVByb3BlcnR5IiwiaSIsIlN5bWJvbCIsImEiLCJpdGVyYXRvciIsImMiLCJhc3luY0l0ZXJhdG9yIiwidSIsInRvU3RyaW5nVGFnIiwiZGVmaW5lIiwiZW51bWVyYWJsZSIsImNvbmZpZ3VyYWJsZSIsIndyaXRhYmxlIiwid3JhcCIsIkdlbmVyYXRvciIsImNyZWF0ZSIsIkNvbnRleHQiLCJtYWtlSW52b2tlTWV0aG9kIiwidHJ5Q2F0Y2giLCJhcmciLCJjYWxsIiwiaCIsImwiLCJmIiwicyIsIkdlbmVyYXRvckZ1bmN0aW9uIiwiR2VuZXJhdG9yRnVuY3Rpb25Qcm90b3R5cGUiLCJwIiwiZCIsImdldFByb3RvdHlwZU9mIiwidiIsInZhbHVlcyIsImciLCJkZWZpbmVJdGVyYXRvck1ldGhvZHMiLCJfaW52b2tlIiwiQXN5bmNJdGVyYXRvciIsImludm9rZSIsIl90eXBlb2YiLCJyZXNvbHZlIiwiX19hd2FpdCIsImNhbGxJbnZva2VXaXRoTWV0aG9kQW5kQXJnIiwiRXJyb3IiLCJtZXRob2QiLCJkZWxlZ2F0ZSIsIm1heWJlSW52b2tlRGVsZWdhdGUiLCJzZW50IiwiX3NlbnQiLCJkaXNwYXRjaEV4Y2VwdGlvbiIsImFicnVwdCIsIlR5cGVFcnJvciIsInJlc3VsdE5hbWUiLCJuZXh0IiwibmV4dExvYyIsInB1c2hUcnlFbnRyeSIsInRyeUxvYyIsImNhdGNoTG9jIiwiZmluYWxseUxvYyIsImFmdGVyTG9jIiwidHJ5RW50cmllcyIsInB1c2giLCJyZXNldFRyeUVudHJ5IiwiY29tcGxldGlvbiIsInJlc2V0IiwiaXNOYU4iLCJkaXNwbGF5TmFtZSIsImlzR2VuZXJhdG9yRnVuY3Rpb24iLCJjb25zdHJ1Y3RvciIsIm1hcmsiLCJzZXRQcm90b3R5cGVPZiIsIl9fcHJvdG9fXyIsImF3cmFwIiwiYXN5bmMiLCJQcm9taXNlIiwia2V5cyIsInJldmVyc2UiLCJwb3AiLCJwcmV2IiwiY2hhckF0Iiwic2xpY2UiLCJzdG9wIiwicnZhbCIsImhhbmRsZSIsImNvbXBsZXRlIiwiZmluaXNoIiwiX2NhdGNoIiwiZGVsZWdhdGVZaWVsZCIsImFzeW5jR2VuZXJhdG9yU3RlcCIsIl9hc3luY1RvR2VuZXJhdG9yIiwiYXBwbHkiLCJfbmV4dCIsIl90aHJvdyIsImxpc3RlbmVyc0F0dGFjaGVkIiwiaW5pdExpa2VCdXR0b25zRGVsZWdhdGVkIiwiaW5pdEJvb2ttYXJrTW9kYWwiLCJpbml0Q29tbWVudFRvZ2dsZXMiLCJldmVudElkIiwiY3NyZlRva2VuIiwiaGVhcnRJY29uIiwiaGVhZGVycyIsInJlcyIsIm1vZGFsRWwiLCJjb25maXJtQnRuIiwidGl0bGVFbCIsImJvZHlFbCIsImNvbnRhaW5lciIsImN1cnJlbnRUYXJnZXQiLCJpc1JlbW92ZSIsImlzQWNjZXB0ZWQiLCJfY2FsbGVlIiwiaWNvbiIsImNvdW50U3BhbiIsImN1cnJlbnRDb3VudCIsIl9jYWxsZWUkIiwiX2NvbnRleHQiLCJzdGF0dXMiLCJwYXJzZUludCIsInRvZ2dsZSIsIk1hdGgiLCJtYXgiLCJ0MCIsImFsZXJ0IiwiaGlkZSIsIl9idXR0b24kY2xvc2VzdCIsInBhdGgiLCJldmVudFBhdGgiLCJtb2RhbFRpdGxlIiwibW9kYWxCb2R5IiwiaGFuZGxlQm9va21hcmtDbGljayIsImJ1dHRvbkNvbnRhaW5lciIsImlzUmVtb3ZlQnV0dG9uIiwic3RhdHVzVGV4dCIsImNhbnZhc2VzIiwiZ2V0Q29sb3JCeVBlcmNlbnQiLCJwZXJjZW50IiwiY2FudmFzIiwic2FmZSIsIm1pbiIsIm1haW5Db2xvciIsIl9fY2hhcnQiLCJjdXRvdXQiLCJwbHVnaW5zIiwibGVnZW5kIiwiZGlzcGxheSIsInRvb2x0aXAiLCJlbmFibGVkIiwiaW5mb0JveCIsIm5ld0luZm9Cb3giLCJ0YXJnZXRVcmwiLCJfcmVmIiwiaW5pdEltYWdlRGVsZXRlIiwiaW5pdERyb3B6b25lIiwiYm91bmQiLCJfdGhpcyIsImltYWdlSWQiLCJjb25maXJtIiwiRHJvcHpvbmUiLCJhdXRvRGlzY292ZXIiLCJkcm9wem9uZUVsZW1lbnQiLCJpbnN0YW5jZSIsImRyb3B6b25lIiwicGFyYW1OYW1lIiwibWF4RmlsZXMiLCJtYXhGaWxlc2l6ZSIsImFjY2VwdGVkRmlsZXMiLCJhZGRSZW1vdmVMaW5rcyIsImRpY3REZWZhdWx0TWVzc2FnZSIsImRpY3RSZW1vdmVGaWxlIiwiZGljdE1heEZpbGVzRXhjZWVkZWQiLCJpbml0Iiwib24iLCJmaWxlIiwibG9nIiwiZXJyb3JNZXNzYWdlIiwibWF0Y2hlcyIsImRpc2FibGVkIiwiY291bnRlciIsImludGVydmFsSWQiLCJzZXRJbnRlcnZhbCIsImNsZWFySW50ZXJ2YWwiLCJpbnRyb0pzIiwiaW50cm9FbGVtZW50IiwidXNlckp1c3RSZWdpc3RlcmVkIiwiaW50cm8iLCJzZXRPcHRpb25zIiwic3RlcHMiLCJzaG93UHJvZ3Jlc3MiLCJzaG93QnVsbGV0cyIsIm5leHRMYWJlbCIsInByZXZMYWJlbCIsImRvbmVMYWJlbCIsIm92ZXJsYXlPcGFjaXR5Iiwic3RhcnQiLCJidXR0b25zIiwiaXRlbUlkIiwibW9kYWwiLCIkIiwiaGlkZGVuSW5wdXQiLCJkZWxldGVMaW5rIiwiZmlsZUlucHV0IiwicHJldmlld0ltYWdlIiwiZXZ0IiwiX2ZpbGVJbnB1dCRmaWxlcyIsIl9zbGljZWRUb0FycmF5IiwiZmlsZXMiLCJzcmMiLCJVUkwiLCJjcmVhdGVPYmplY3RVUkwiLCJ0YWJsZSIsImltYWdlVXJsIiwiZGVzY3JpcHRpb25IdG1sIiwiZGVzY3JpcHRpb24iLCJlZGl0TGlua1VybCIsImVkaXRVcmwiLCJzaG93SXRlbU1vZGFsIiwiZGVjb2RlSnNFc2NhcGVkU3RyaW5nIiwic3RyIiwiSlNPTiIsInBhcnNlIiwiaW1hZ2UiLCJsb2FkZXIiLCJlZGl0TGluayIsImRlY29kZWRIdG1sIiwib25sb2FkIiwib25lcnJvciIsInN3aXBlckVsIiwic3dpcGVyIiwiY3VycmVudFN0ZXBFbCIsIlN3aXBlciIsInBhZ2luYXRpb24iLCJlbCIsImNsaWNrYWJsZSIsIm5hdmlnYXRpb24iLCJuZXh0RWwiLCJwcmV2RWwiLCJsb29wIiwic2xpZGVDaGFuZ2UiLCJyZWFsSW5kZXgiLCJpbnB1dCIsInVzZXJuYW1lQ2hlY2tVcmwiLCJjaGVja1RhcmdldCIsInVzZXJuYW1lQ2hlY2tUYXJnZXQiLCJyZWdpc3RlckJ1dHRvbiIsInJlZ2lzdGVyQnV0dG9uSWQiLCJlcnJvck1zZyIsInVzZXJuYW1lIiwiZW5jb2RlVVJJQ29tcG9uZW50IiwiZXhpc3RzIiwic2hhcmVCdXR0b25zIiwid3JhcHBlciIsInRlbXBJbnB1dCIsImVyciIsInNtYWxsU3RlcHNDaGFydCIsInJhbmdlIiwid2Vla3MiLCJkYXlzIiwicG9pbnRzIiwiZGF5Iiwid2VlayIsInRlbnNpb24iLCJmaWxsIiwicG9pbnRSYWRpdXMiLCJwb2ludEhvdmVyUmFkaXVzIiwiX3JlZiRzcGVlZCIsImluZGV4IiwibGluayIsInNoYXJlIiwidGl0bGUiLCJ3ZWVrbHlTY29yZUNoYXJ0Iiwic2NvcmUiLCJsYWJlbFNjb3JlIiwicG9pbnRCYWNrZ3JvdW5kQ29sb3IiLCJwb2ludEJvcmRlckNvbG9yIiwic3VnZ2VzdGVkTWF4IiwiQnJvd3NlclJvdXRlciIsIlJvdXRlcyIsIlJvdXRlIiwiTWF0cml4UGFnZSIsIkFkZEl0ZW1QYWdlIiwiRWRpdEl0ZW1QYWdlIiwianN4cyIsIl9qc3hzIiwiYmFzZW5hbWUiLCJjaGlsZHJlbiIsIl9hcnJheVdpdGhIb2xlcyIsIl9pdGVyYWJsZVRvQXJyYXlMaW1pdCIsIl91bnN1cHBvcnRlZEl0ZXJhYmxlVG9BcnJheSIsIl9ub25JdGVyYWJsZVJlc3QiLCJfYXJyYXlMaWtlVG9BcnJheSIsInRvU3RyaW5nIiwiQXJyYXkiLCJmcm9tIiwidGVzdCIsImlzQXJyYXkiLCJ1c2VTdGF0ZSIsInVzZUVmZmVjdCIsInVzZVBhcmFtcyIsInVzZU5hdmlnYXRlIiwiX3VzZVBhcmFtcyIsIm1hcElkIiwibmF2aWdhdGUiLCJfdXNlU3RhdGUiLCJfdXNlU3RhdGUyIiwibWFwTmFtZSIsInNldE1hcE5hbWUiLCJfdXNlU3RhdGUzIiwiX3VzZVN0YXRlNCIsImxvYWRpbmciLCJzZXRMb2FkaW5nIiwiX3VzZVN0YXRlNSIsIl91c2VTdGF0ZTYiLCJzZXRFcnJvciIsIl91c2VTdGF0ZTciLCJfdXNlU3RhdGU4Iiwic2V0VGl0bGUiLCJfdXNlU3RhdGU5IiwiX3VzZVN0YXRlMTAiLCJzZXREZXNjcmlwdGlvbiIsIl91c2VTdGF0ZTExIiwiX3VzZVN0YXRlMTIiLCJzZXRGaWxlIiwiaGFuZGxlVGl0bGVDaGFuZ2UiLCJoYW5kbGVEZXNjcmlwdGlvbkNoYW5nZSIsImhhbmRsZUZpbGVDaGFuZ2UiLCJoYW5kbGVTdWJtaXQiLCJmb3JtRGF0YSIsImVycm9yRGF0YSIsInJlc3BvbnNlRGF0YSIsIkZvcm1EYXRhIiwiYXBwZW5kIiwiX3giLCJjbGFzc05hbWUiLCJvbkNsaWNrIiwib25TdWJtaXQiLCJlbmNUeXBlIiwib25DaGFuZ2UiLCJyb3dzIiwiX3VzZVBhcmFtczIiLCJzZXRJdGVtIiwiX3VzZVN0YXRlMTMiLCJfdXNlU3RhdGUxNCIsInNldFByZXZpZXdJbWFnZSIsImZpbGVuYW1lIiwic2VsZWN0ZWRGaWxlIiwicmVhZGVyIiwiRmlsZVJlYWRlciIsIm9ubG9hZGVuZCIsInJlYWRBc0RhdGFVUkwiLCJtYXRyaXhNYXBJZCIsImFsdCIsIm1heFdpZHRoIiwiSXRlbU1vZGFsIiwib25DbG9zZSIsImhhbmRsZUVkaXRDbGljayIsInJvbGUiLCJ0ZXh0QWxpZ24iLCJzdHlsZXMiLCJGcmFnbWVudCIsIl9GcmFnbWVudCIsImdldFNhbnNrcml0TGV0dGVyIiwibGV0dGVycyIsIk1hdHJpeEdyaWQiLCJfcmVmJGl0ZW1zIiwiaXRlbXMiLCJzZWxlY3RlZEl0ZW0iLCJzZXRTZWxlY3RlZEl0ZW0iLCJoYW5kbGVDZWxsQ2xpY2siLCJoYW5kbGVDbG9zZU1vZGFsIiwicm93IiwiY29sIiwiaXNFdmVuIiwiZGVmYXVsdENvbG9yIiwiY3Vyc29yIiwidHJhbnNpdGlvbiIsIm9uTW91c2VFbnRlciIsIm9uTW91c2VMZWF2ZSIsIkxpbmsiLCJzZXRNYXAiLCJfbWFwJGl0ZW1zIiwiZm9udEZhbWlseSIsImZvbnRTaXplIiwidG8iLCJ0dXJib19jb250cm9sbGVyIl0sInNvdXJjZVJvb3QiOiIifQ==