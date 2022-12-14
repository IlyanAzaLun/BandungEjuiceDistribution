(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory(require("handsontable"));
	else if(typeof define === 'function' && define.amd)
		define(["handsontable"], factory);
	else {
		var a = typeof exports === 'object' ? factory(require("handsontable")) : factory(root["Handsontable"]);
		for(var i in a) (typeof exports === 'object' ? exports : root)[i] = a[i];
	}
})(typeof self !== 'undefined' ? self : this, function(__WEBPACK_EXTERNAL_MODULE__3__) {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */
/***/ (function(module, exports) {

function _interopRequireDefault(obj) {
  return obj && obj.__esModule ? obj : {
    "default": obj
  };
}
module.exports = _interopRequireDefault, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),
/* 2 */
/***/ (function(module, exports) {

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
module.exports = _defineProperty, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),
/* 3 */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE__3__;

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _interopRequireDefault = __webpack_require__(1);
exports.__esModule = true;
exports.default = void 0;
var _defineProperty2 = _interopRequireDefault(__webpack_require__(2));
var _handsontable = _interopRequireDefault(__webpack_require__(3));
var _dictionary;
var C = _handsontable.default.languages.dictionaryKeys;
var dictionary = (_dictionary = {
  languageCode: 'cs-CZ'
}, (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_NO_ITEMS, 'Žádné volby nejsou dostupné'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ROW_ABOVE, 'Vložit řádek nad'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ROW_BELOW, 'Vložit řádek pod'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_INSERT_LEFT, 'Vložit sloupec vlevo'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_INSERT_RIGHT, 'Vložit sloupec vpravo'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_ROW, ['Smazat řádek', 'Smazat řádky']), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_COLUMN, ['Smazat sloupec', 'Smazat sloupce']), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_UNDO, 'Zpět'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_REDO, 'Znovu'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_READ_ONLY, 'Pouze pro čtení'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_CLEAR_COLUMN, 'Vymazat obsah sloupce'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT, 'Zarovnat'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_LEFT, 'Vlevo'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_CENTER, 'Na střed'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_RIGHT, 'Vpravo'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_JUSTIFY, 'Do bloku'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_TOP, 'Nahoru'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_MIDDLE, 'Na střed'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_BOTTOM, 'Dolů'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_FREEZE_COLUMN, 'Zmrazit sloupec'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_UNFREEZE_COLUMN, 'Zrušit zmrazení sloupce'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS, 'Ohraničení'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_TOP, 'Nahoře'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_RIGHT, 'Vpravo'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_BOTTOM, 'Dole'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_LEFT, 'Vlevo'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_BORDERS, 'Zrušit ohraničení'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_ADD_COMMENT, 'Přidat komentář'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_EDIT_COMMENT, 'Upravit komentář'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_COMMENT, 'Vymazat komentář'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_READ_ONLY_COMMENT, 'Komenář pouze pro čtení'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_MERGE_CELLS, 'Sloučit buňky'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_UNMERGE_CELLS, 'Zrušit sloučení buněk'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_COPY, 'Kopírovat'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_CUT, 'Vyjmout'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_NESTED_ROWS_INSERT_CHILD, 'Vložit podřízený řádek'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_NESTED_ROWS_DETACH_CHILD, 'Oddělit od nadřízeného'), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_HIDE_COLUMN, ['Skrýt sloupec', 'Skrýt sloupce']), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_SHOW_COLUMN, ['Zobrazit sloupec', 'Zobrazit sloupce']), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_HIDE_ROW, ['Skrýt řádek', 'Skrýt řádky']), (0, _defineProperty2.default)(_dictionary, C.CONTEXTMENU_ITEMS_SHOW_ROW, ['Zobrazit řádek', 'Zobrazit řádky']), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_NONE, 'Žádné'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_EMPTY, 'Prázdné'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_NOT_EMPTY, 'Neprázdné'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_EQUAL, 'Rovná se'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_NOT_EQUAL, 'Nerovná se'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_BEGINS_WITH, 'Začíná na'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_ENDS_WITH, 'Končí na'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_CONTAINS, 'Obsahuje'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_NOT_CONTAIN, 'Neobsahuje'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_GREATER_THAN, 'Větší než'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_GREATER_THAN_OR_EQUAL, 'Větší nebo se rovná'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_LESS_THAN, 'Menší než'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_LESS_THAN_OR_EQUAL, 'Menší nebo se rovná'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_BETWEEN, 'Je v rozsahu'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_NOT_BETWEEN, 'Není v rozsahu'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_AFTER, 'Po'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_BEFORE, 'Před'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_TODAY, 'Dnes'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_TOMORROW, 'Zítra'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_CONDITIONS_YESTERDAY, 'Včera'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_VALUES_BLANK_CELLS, 'Prádné buňky'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_DIVS_FILTER_BY_CONDITION, 'Filtrovat dle stavu'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_DIVS_FILTER_BY_VALUE, 'Filtrovat dle hodnoty'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_LABELS_CONJUNCTION, 'A'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_LABELS_DISJUNCTION, 'Nebo'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_BUTTONS_SELECT_ALL, 'Vybrat vše'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_BUTTONS_CLEAR, 'Smazat'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_BUTTONS_OK, 'OK'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_BUTTONS_CANCEL, 'Storno'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_BUTTONS_PLACEHOLDER_SEARCH, 'Hledat'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_BUTTONS_PLACEHOLDER_VALUE, 'Hodnota'), (0, _defineProperty2.default)(_dictionary, C.FILTERS_BUTTONS_PLACEHOLDER_SECOND_VALUE, 'Druhá hodnota'), _dictionary);
_handsontable.default.languages.registerLanguageDictionary(dictionary);
var _default = dictionary;
exports.default = _default;

/***/ })
/******/ ])["___"];
});