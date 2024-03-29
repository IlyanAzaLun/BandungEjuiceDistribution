"use strict";

exports.__esModule = true;
exports.default = void 0;
var _array = require("../../helpers/array");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
/**
 * @private
 * @class DataFilter
 */var DataFilter = /*#__PURE__*/function () {
  function DataFilter(conditionCollection) {
    var columnDataFactory = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {
      return [];
    };
    _classCallCheck(this, DataFilter);
    /**
     * Reference to the instance of {ConditionCollection}.
     *
     * @type {ConditionCollection}
     */
    this.conditionCollection = conditionCollection;
    /**
     * Function which provide source data factory for specified column.
     *
     * @type {Function}
     */
    this.columnDataFactory = columnDataFactory;
  }

  /**
   * Filter data based on the conditions collection.
   *
   * @returns {Array}
   */
  _createClass(DataFilter, [{
    key: "filter",
    value: function filter() {
      var _this = this;
      var filteredData = [];
      (0, _array.arrayEach)(this.conditionCollection.getFilteredColumns(), function (physicalColumn, index) {
        var columnData = _this.columnDataFactory(physicalColumn);
        if (index) {
          columnData = _this._getIntersectData(columnData, filteredData);
        }
        filteredData = _this.filterByColumn(physicalColumn, columnData);
      });
      return filteredData;
    }

    /**
     * Filter data based on specified physical column index.
     *
     * @param {number} column The physical column index.
     * @param {Array} [dataSource] Data source as array of objects with `value` and `meta` keys (e.g. `{value: 'foo', meta: {}}`).
     * @returns {Array} Returns filtered data.
     */
  }, {
    key: "filterByColumn",
    value: function filterByColumn(column) {
      var _this2 = this;
      var dataSource = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      var filteredData = [];
      (0, _array.arrayEach)(dataSource, function (dataRow) {
        if (dataRow !== void 0 && _this2.conditionCollection.isMatch(dataRow, column)) {
          filteredData.push(dataRow);
        }
      });
      return filteredData;
    }

    /**
     * Intersect data.
     *
     * @private
     * @param {Array} data The data to intersect.
     * @param {Array} needles The collection intersected rows with the data.
     * @returns {Array}
     */
  }, {
    key: "_getIntersectData",
    value: function _getIntersectData(data, needles) {
      var result = [];
      (0, _array.arrayEach)(needles, function (needleRow) {
        var row = needleRow.meta.visualRow;
        if (data[row] !== void 0) {
          result[row] = data[row];
        }
      });
      return result;
    }
  }]);
  return DataFilter;
}();
var _default = DataFilter;
exports.default = _default;