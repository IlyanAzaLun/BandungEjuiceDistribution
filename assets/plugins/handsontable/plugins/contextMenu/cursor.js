"use strict";

exports.__esModule = true;
exports.default = void 0;
var _element = require("../../helpers/dom/element");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
/**
 * Helper class for checking if element will fit at the desired side of cursor.
 *
 * @private
 * @class Cursor
 */var Cursor = /*#__PURE__*/function () {
  function Cursor(object, rootWindow) {
    _classCallCheck(this, Cursor);
    var windowScrollTop = (0, _element.getWindowScrollTop)(rootWindow);
    var windowScrollLeft = (0, _element.getWindowScrollLeft)(rootWindow);
    var top;
    var topRelative;
    var left;
    var leftRelative;
    var cellHeight;
    var cellWidth;
    this.rootWindow = rootWindow;
    this.type = this.getSourceType(object);
    if (this.type === 'literal') {
      top = parseInt(object.top, 10);
      left = parseInt(object.left, 10);
      cellHeight = object.height || 0;
      cellWidth = object.width || 0;
      topRelative = top;
      leftRelative = left;
      top += windowScrollTop;
      left += windowScrollLeft;
    } else if (this.type === 'event') {
      top = parseInt(object.pageY, 10);
      left = parseInt(object.pageX, 10);
      cellHeight = object.target.clientHeight;
      cellWidth = object.target.clientWidth;
      topRelative = top - windowScrollTop;
      leftRelative = left - windowScrollLeft;
    }
    this.top = top;
    this.topRelative = topRelative;
    this.left = left;
    this.leftRelative = leftRelative;
    this.scrollTop = windowScrollTop;
    this.scrollLeft = windowScrollLeft;
    this.cellHeight = cellHeight;
    this.cellWidth = cellWidth;
  }

  /**
   * Get source type name.
   *
   * @param {*} object Event or Object with coordinates.
   * @returns {string} Returns one of this values: `'literal'`, `'event'`.
   */
  _createClass(Cursor, [{
    key: "getSourceType",
    value: function getSourceType(object) {
      var type = 'literal';
      if (object instanceof Event) {
        type = 'event';
      }
      return type;
    }

    /**
     * Checks if element can be placed above the cursor.
     *
     * @param {HTMLElement} element Element to check if it's size will fit above the cursor.
     * @returns {boolean}
     */
  }, {
    key: "fitsAbove",
    value: function fitsAbove(element) {
      return this.topRelative >= element.offsetHeight;
    }

    /**
     * Checks if element can be placed below the cursor.
     *
     * @param {HTMLElement} element Element to check if it's size will fit below the cursor.
     * @param {number} [viewportHeight] The viewport height.
     * @returns {boolean}
     */
  }, {
    key: "fitsBelow",
    value: function fitsBelow(element) {
      var viewportHeight = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.rootWindow.innerHeight;
      return this.topRelative + element.offsetHeight <= viewportHeight;
    }

    /**
     * Checks if element can be placed on the right of the cursor.
     *
     * @param {HTMLElement} element Element to check if it's size will fit on the right of the cursor.
     * @param {number} [viewportWidth] The viewport width.
     * @returns {boolean}
     */
  }, {
    key: "fitsOnRight",
    value: function fitsOnRight(element) {
      var viewportWidth = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : this.rootWindow.innerWidth;
      return this.leftRelative + this.cellWidth + element.offsetWidth <= viewportWidth;
    }

    /**
     * Checks if element can be placed on the left on the cursor.
     *
     * @param {HTMLElement} element Element to check if it's size will fit on the left of the cursor.
     * @returns {boolean}
     */
  }, {
    key: "fitsOnLeft",
    value: function fitsOnLeft(element) {
      return this.leftRelative >= element.offsetWidth;
    }
  }]);
  return Cursor;
}();
var _default = Cursor;
exports.default = _default;