"use strict";

exports.__esModule = true;
exports.default = void 0;
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
/**
 * @private
 */var ClipboardData = /*#__PURE__*/function () {
  function ClipboardData() {
    _classCallCheck(this, ClipboardData);
    this.data = {};
  }
  _createClass(ClipboardData, [{
    key: "setData",
    value: function setData(type, value) {
      this.data[type] = value;
    }
  }, {
    key: "getData",
    value: function getData(type) {
      return this.data[type] || void 0;
    }
  }]);
  return ClipboardData;
}();
exports.default = ClipboardData;