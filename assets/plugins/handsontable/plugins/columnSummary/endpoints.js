"use strict";

exports.__esModule = true;
exports.default = void 0;
require("core-js/modules/es.array.index-of.js");
require("core-js/modules/es.object.to-string.js");
require("core-js/modules/es.number.to-fixed.js");
var _array = require("../../helpers/array");
var _console = require("../../helpers/console");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
/**
 * Class used to make all endpoint-related operations.
 *
 * @private
 * @class Endpoints
 */var Endpoints = /*#__PURE__*/function () {
  function Endpoints(plugin, settings) {
    _classCallCheck(this, Endpoints);
    /**
     * The main plugin instance.
     */
    this.plugin = plugin;
    /**
     * Handsontable instance.
     *
     * @type {object}
     */
    this.hot = this.plugin.hot;
    /**
     * Array of declared plugin endpoints (calculation destination points).
     *
     * @type {Array}
     * @default {Array} Empty array.
     */
    this.endpoints = [];
    /**
     * The plugin settings, taken from Handsontable configuration.
     *
     * @type {object|Function}
     * @default null
     */
    this.settings = settings;
    /**
     * Settings type. Can be either 'array' or 'function.
     *
     * @type {string}
     * @default {'array'}
     */
    this.settingsType = 'array';
    /**
     * The current endpoint (calculation destination point) in question.
     *
     * @type {object}
     * @default null
     */
    this.currentEndpoint = null;
    /**
     * Array containing a list of changes to be applied.
     *
     * @private
     * @type {Array}
     * @default {[]}
     */
    this.cellsToSetCache = [];
  }

  /**
   * Get a single endpoint object.
   *
   * @param {number} index Index of the endpoint.
   * @returns {object}
   */
  _createClass(Endpoints, [{
    key: "getEndpoint",
    value: function getEndpoint(index) {
      if (this.settingsType === 'function') {
        return this.fillMissingEndpointData(this.settings)[index];
      }
      return this.endpoints[index];
    }

    /**
     * Get an array with all the endpoints.
     *
     * @returns {Array}
     */
  }, {
    key: "getAllEndpoints",
    value: function getAllEndpoints() {
      if (this.settingsType === 'function') {
        return this.fillMissingEndpointData(this.settings);
      }
      return this.endpoints;
    }

    /**
     * Used to fill the blanks in the endpoint data provided by a settings function.
     *
     * @private
     * @param {Function} func Function provided in the HOT settings.
     * @returns {Array} An array of endpoints.
     */
  }, {
    key: "fillMissingEndpointData",
    value: function fillMissingEndpointData(func) {
      return this.parseSettings(func.call(this));
    }

    /**
     * Parse plugin's settings.
     *
     * @param {Array} settings The settings array.
     * @returns {object[]}
     */
  }, {
    key: "parseSettings",
    value: function parseSettings(settings) {
      var _this = this;
      var endpointsArray = [];
      var settingsArray = settings;
      if (!settingsArray && typeof this.settings === 'function') {
        this.settingsType = 'function';
        return;
      }
      if (!settingsArray) {
        settingsArray = this.settings;
      }
      (0, _array.arrayEach)(settingsArray, function (val) {
        var newEndpoint = {};
        _this.assignSetting(val, newEndpoint, 'ranges', [[0, _this.hot.countRows() - 1]]);
        _this.assignSetting(val, newEndpoint, 'reversedRowCoords', false);
        _this.assignSetting(val, newEndpoint, 'destinationRow', new Error("\n        You must provide a destination row for the Column Summary plugin in order to work properly!\n      "));
        _this.assignSetting(val, newEndpoint, 'destinationColumn', new Error("\n        You must provide a destination column for the Column Summary plugin in order to work properly!\n      "));
        _this.assignSetting(val, newEndpoint, 'sourceColumn', val.destinationColumn);
        _this.assignSetting(val, newEndpoint, 'type', 'sum');
        _this.assignSetting(val, newEndpoint, 'forceNumeric', false);
        _this.assignSetting(val, newEndpoint, 'suppressDataTypeErrors', true);
        _this.assignSetting(val, newEndpoint, 'suppressDataTypeErrors', true);
        _this.assignSetting(val, newEndpoint, 'customFunction', null);
        _this.assignSetting(val, newEndpoint, 'readOnly', true);
        _this.assignSetting(val, newEndpoint, 'roundFloat', false);
        endpointsArray.push(newEndpoint);
      });
      return endpointsArray;
    }

    /**
     * Setter for the internal setting objects.
     *
     * @param {object} settings Object with the settings.
     * @param {object} endpoint Contains information about the endpoint for the the calculation.
     * @param {string} name Settings name.
     * @param {object} defaultValue Default value for the settings.
     */
  }, {
    key: "assignSetting",
    value: function assignSetting(settings, endpoint, name, defaultValue) {
      if (name === 'ranges' && settings[name] === void 0) {
        endpoint[name] = defaultValue;
        return;
      } else if (name === 'ranges' && settings[name].length === 0) {
        return;
      }
      if (settings[name] === void 0) {
        if (defaultValue instanceof Error) {
          throw defaultValue;
        }
        endpoint[name] = defaultValue;
      } else {
        /* eslint-disable no-lonely-if */
        if (name === 'destinationRow' && endpoint.reversedRowCoords) {
          endpoint[name] = this.hot.countRows() - settings[name] - 1;
        } else {
          endpoint[name] = settings[name];
        }
      }
    }

    /**
     * Resets the endpoint setup before the structure alteration (like inserting or removing rows/columns). Used for settings provided as a function.
     *
     * @private
     * @param {string} action Type of the action performed.
     * @param {number} index Row/column index.
     * @param {number} number Number of rows/columns added/removed.
     */
  }, {
    key: "resetSetupBeforeStructureAlteration",
    value: function resetSetupBeforeStructureAlteration(action, index, number) {
      if (this.settingsType !== 'function') {
        return;
      }
      var type = action.indexOf('row') > -1 ? 'row' : 'col';
      var endpoints = this.getAllEndpoints();
      (0, _array.arrayEach)(endpoints, function (val) {
        if (type === 'row' && val.destinationRow >= index) {
          if (action === 'insert_row') {
            val.alterRowOffset = number;
          } else if (action === 'remove_row') {
            val.alterRowOffset = -1 * number;
          }
        }
        if (type === 'col' && val.destinationColumn >= index) {
          if (action === 'insert_col') {
            val.alterColumnOffset = number;
          } else if (action === 'remove_col') {
            val.alterColumnOffset = -1 * number;
          }
        }
      });
      this.resetAllEndpoints(endpoints, false);
    }

    /**
     * AfterCreateRow/afterCreateRow/afterRemoveRow/afterRemoveCol hook callback. Reset and reenables the summary functionality
     * after changing the table structure.
     *
     * @private
     * @param {string} action Type of the action performed.
     * @param {number} index Row/column index.
     * @param {number} number Number of rows/columns added/removed.
     * @param {Array} [logicRows] Array of the logical indexes.
     * @param {string} [source] Source of change.
     * @param {boolean} [forceRefresh] `true` of the endpoints should refresh after completing the function.
     */
  }, {
    key: "resetSetupAfterStructureAlteration",
    value: function resetSetupAfterStructureAlteration(action, index, number, logicRows, source) {
      var _this2 = this;
      var forceRefresh = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : true;
      if (this.settingsType === 'function') {
        // We need to run it on a next avaiable hook, because the TrimRows' `afterCreateRow` hook triggers after this one,
        // and it needs to be run to properly calculate the endpoint value.
        var beforeViewRenderCallback = function beforeViewRenderCallback() {
          _this2.hot.removeHook('beforeViewRender', beforeViewRenderCallback);
          return _this2.refreshAllEndpoints();
        };
        this.hot.addHookOnce('beforeViewRender', beforeViewRenderCallback);
        return;
      }
      var type = action.indexOf('row') > -1 ? 'row' : 'col';
      var multiplier = action.indexOf('remove') > -1 ? -1 : 1;
      var endpoints = this.getAllEndpoints();
      var rowMoving = action.indexOf('move_row') === 0;
      var placeOfAlteration = index;
      (0, _array.arrayEach)(endpoints, function (val) {
        if (type === 'row' && val.destinationRow >= placeOfAlteration) {
          val.alterRowOffset = multiplier * number;
        }
        if (type === 'col' && val.destinationColumn >= placeOfAlteration) {
          val.alterColumnOffset = multiplier * number;
        }
      });
      this.resetAllEndpoints(endpoints, !rowMoving);
      if (rowMoving) {
        (0, _array.arrayEach)(endpoints, function (endpoint) {
          _this2.extendEndpointRanges(endpoint, placeOfAlteration, logicRows[0], logicRows.length);
          _this2.recreatePhysicalRanges(endpoint);
          _this2.clearOffsetInformation(endpoint);
        });
      } else {
        (0, _array.arrayEach)(endpoints, function (endpoint) {
          _this2.shiftEndpointCoordinates(endpoint, placeOfAlteration);
        });
      }
      if (forceRefresh) {
        this.refreshAllEndpoints();
      }
    }

    /**
     * Clear the offset information from the endpoint object.
     *
     * @private
     * @param {object} endpoint And endpoint object.
     */
  }, {
    key: "clearOffsetInformation",
    value: function clearOffsetInformation(endpoint) {
      endpoint.alterRowOffset = void 0;
      endpoint.alterColumnOffset = void 0;
    }

    /**
     * Extend the row ranges for the provided endpoint.
     *
     * @private
     * @param {object} endpoint The endpoint object.
     * @param {number} placeOfAlteration Index of the row where the alteration takes place.
     * @param {number} previousPosition Previous endpoint result position.
     * @param {number} offset Offset generated by the alteration.
     */
  }, {
    key: "extendEndpointRanges",
    value: function extendEndpointRanges(endpoint, placeOfAlteration, previousPosition, offset) {
      (0, _array.arrayEach)(endpoint.ranges, function (range) {
        // is a range, not a single row
        if (range[1]) {
          if (placeOfAlteration >= range[0] && placeOfAlteration <= range[1]) {
            if (previousPosition > range[1]) {
              range[1] += offset;
            } else if (previousPosition < range[0]) {
              range[0] -= offset;
            }
          } else if (previousPosition >= range[0] && previousPosition <= range[1]) {
            range[1] -= offset;
            if (placeOfAlteration <= range[0]) {
              range[0] += 1;
              range[1] += 1;
            }
          }
        }
      });
    }

    /**
     * Recreate the physical ranges for the provided endpoint. Used (for example) when a row gets moved and extends an existing range.
     *
     * @private
     * @param {object} endpoint An endpoint object.
     */
  }, {
    key: "recreatePhysicalRanges",
    value: function recreatePhysicalRanges(endpoint) {
      var _this3 = this;
      var ranges = endpoint.ranges;
      var newRanges = [];
      var allIndexes = [];
      (0, _array.arrayEach)(ranges, function (range) {
        var newRange = [];
        if (range[1]) {
          for (var i = range[0]; i <= range[1]; i++) {
            newRange.push(_this3.hot.toPhysicalRow(i));
          }
        } else {
          newRange.push(_this3.hot.toPhysicalRow(range[0]));
        }
        allIndexes.push(newRange);
      });
      (0, _array.arrayEach)(allIndexes, function (range) {
        var newRange = [];
        (0, _array.arrayEach)(range, function (coord, index) {
          if (index === 0) {
            newRange.push(coord);
          } else if (range[index] !== range[index - 1] + 1) {
            newRange.push(range[index - 1]);
            newRanges.push(newRange);
            newRange = [];
            newRange.push(coord);
          }
          if (index === range.length - 1) {
            newRange.push(coord);
            newRanges.push(newRange);
          }
        });
      });
      endpoint.ranges = newRanges;
    }

    /**
     * Shifts the endpoint coordinates by the defined offset.
     *
     * @private
     * @param {object} endpoint Endpoint object.
     * @param {number} offsetStartIndex Index of the performed change (if the change is located after the endpoint, nothing about the endpoint has to be changed.
     */
  }, {
    key: "shiftEndpointCoordinates",
    value: function shiftEndpointCoordinates(endpoint, offsetStartIndex) {
      if (endpoint.alterRowOffset && endpoint.alterRowOffset !== 0) {
        endpoint.destinationRow += endpoint.alterRowOffset || 0;
        (0, _array.arrayEach)(endpoint.ranges, function (element) {
          (0, _array.arrayEach)(element, function (subElement, j) {
            if (subElement >= offsetStartIndex) {
              element[j] += endpoint.alterRowOffset || 0;
            }
          });
        });
      } else if (endpoint.alterColumnOffset && endpoint.alterColumnOffset !== 0) {
        endpoint.destinationColumn += endpoint.alterColumnOffset || 0;
        endpoint.sourceColumn += endpoint.alterColumnOffset || 0;
      }
    }

    /**
     * Resets (removes) the endpoints from the table.
     *
     * @param {Array} [endpoints] Array containing the endpoints.
     * @param {boolean} [useOffset=true] Use the cell offset value.
     */
  }, {
    key: "resetAllEndpoints",
    value: function resetAllEndpoints() {
      var _this4 = this;
      var endpoints = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : this.getAllEndpoints();
      var useOffset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      var anyEndpointOutOfRange = endpoints.some(function (endpoint) {
        var alterRowOffset = endpoint.alterRowOffset || 0;
        var alterColOffset = endpoint.alterColumnOffset || 0;
        if (endpoint.destinationRow + alterRowOffset >= _this4.hot.countRows() || endpoint.destinationColumn + alterColOffset >= _this4.hot.countCols()) {
          return true;
        }
        return false;
      });
      if (anyEndpointOutOfRange) {
        return;
      }
      this.cellsToSetCache = [];
      (0, _array.arrayEach)(endpoints, function (endpoint) {
        _this4.resetEndpointValue(endpoint, useOffset);
      });
      this.hot.setDataAtCell(this.cellsToSetCache, 'ColumnSummary.reset');
      this.cellsToSetCache = [];
    }

    /**
     * Calculate and refresh all defined endpoints.
     */
  }, {
    key: "refreshAllEndpoints",
    value: function refreshAllEndpoints() {
      var _this5 = this;
      this.cellsToSetCache = [];
      (0, _array.arrayEach)(this.getAllEndpoints(), function (value) {
        _this5.currentEndpoint = value;
        _this5.plugin.calculate(value);
        _this5.setEndpointValue(value, 'init');
      });
      this.currentEndpoint = null;
      this.hot.setDataAtCell(this.cellsToSetCache, 'ColumnSummary.reset');
      this.cellsToSetCache = [];
    }

    /**
     * Calculate and refresh endpoints only in the changed columns.
     *
     * @param {Array} changes Array of changes from the `afterChange` hook.
     */
  }, {
    key: "refreshChangedEndpoints",
    value: function refreshChangedEndpoints(changes) {
      var _this6 = this;
      var needToRefresh = [];
      this.cellsToSetCache = [];
      (0, _array.arrayEach)(changes, function (value, key, changesObj) {
        // if nothing changed, dont update anything
        if ("".concat(value[2] || '') === "".concat(value[3])) {
          return;
        }
        (0, _array.arrayEach)(_this6.getAllEndpoints(), function (endpoint, j) {
          if (_this6.hot.propToCol(changesObj[key][1]) === endpoint.sourceColumn && needToRefresh.indexOf(j) === -1) {
            needToRefresh.push(j);
          }
        });
      });
      (0, _array.arrayEach)(needToRefresh, function (value) {
        _this6.refreshEndpoint(_this6.getEndpoint(value));
      });
      this.hot.setDataAtCell(this.cellsToSetCache, 'ColumnSummary.reset');
      this.cellsToSetCache = [];
    }

    /**
     * Calculate and refresh a single endpoint.
     *
     * @param {object} endpoint Contains the endpoint information.
     */
  }, {
    key: "refreshEndpoint",
    value: function refreshEndpoint(endpoint) {
      this.currentEndpoint = endpoint;
      this.plugin.calculate(endpoint);
      this.setEndpointValue(endpoint);
      this.currentEndpoint = null;
    }

    /**
     * Reset the endpoint value.
     *
     * @param {object} endpoint Contains the endpoint information.
     * @param {boolean} [useOffset=true] Use the cell offset value.
     */
  }, {
    key: "resetEndpointValue",
    value: function resetEndpointValue(endpoint) {
      var useOffset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      var alterRowOffset = endpoint.alterRowOffset || 0;
      var alterColOffset = endpoint.alterColumnOffset || 0;
      var _ref = [this.hot.toVisualRow(endpoint.destinationRow), this.hot.toVisualColumn(endpoint.destinationColumn)],
        visualRowIndex = _ref[0],
        visualColumnIndex = _ref[1];
      if (visualColumnIndex !== null && visualRowIndex !== null) {
        // Clear the meta on the "old" indexes
        var cellMeta = this.hot.getCellMeta(visualRowIndex, visualColumnIndex);
        cellMeta.readOnly = false;
        cellMeta.className = '';
      }
      this.cellsToSetCache.push([this.hot.toVisualRow(endpoint.destinationRow + (useOffset ? alterRowOffset : 0)), this.hot.toVisualColumn(endpoint.destinationColumn + (useOffset ? alterColOffset : 0)), '']);
    }

    /**
     * Set the endpoint value.
     *
     * @param {object} endpoint Contains the endpoint information.
     * @param {string} [source] Source of the call information.
     * @param {boolean} [render=false] `true` if it needs to render the table afterwards.
     */
  }, {
    key: "setEndpointValue",
    value: function setEndpointValue(endpoint, source) {
      var render = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
      // We'll need the reversed offset values, because cellMeta will be shifted AGAIN afterwards.
      var reverseRowOffset = -1 * endpoint.alterRowOffset || 0;
      var reverseColOffset = -1 * endpoint.alterColumnOffset || 0;
      var visualEndpointRowIndex = this.hot.toVisualRow(endpoint.destinationRow);
      if (endpoint.destinationRow >= this.hot.countRows() || endpoint.destinationColumn >= this.hot.countCols()) {
        this.throwOutOfBoundsWarning();
        return;
      }
      var destinationVisualRow = this.hot.toVisualRow(endpoint.destinationRow + reverseRowOffset);
      if (destinationVisualRow !== null) {
        var cellMeta = this.hot.getCellMeta(destinationVisualRow, endpoint.destinationColumn + reverseColOffset);
        if (source === 'init' || cellMeta.readOnly !== endpoint.readOnly) {
          cellMeta.readOnly = endpoint.readOnly;
          cellMeta.className = 'columnSummaryResult';
        }
      }
      if (endpoint.roundFloat && !isNaN(endpoint.result)) {
        endpoint.result = endpoint.result.toFixed(endpoint.roundFloat);
      }
      if (render) {
        this.hot.setDataAtCell(visualEndpointRowIndex, endpoint.destinationColumn, endpoint.result, 'ColumnSummary.set');
      } else {
        this.cellsToSetCache.push([visualEndpointRowIndex, endpoint.destinationColumn, endpoint.result]);
      }
      endpoint.alterRowOffset = void 0;
      endpoint.alterColumnOffset = void 0;
    }

    /**
     * Throw an error for the calculation range being out of boundaries.
     *
     * @private
     */
  }, {
    key: "throwOutOfBoundsWarning",
    value: function throwOutOfBoundsWarning() {
      (0, _console.warn)('One of the Column Summary plugins\' destination points you provided is beyond the table boundaries!');
    }
  }]);
  return Endpoints;
}();
var _default = Endpoints;
exports.default = _default;