import "core-js/modules/es.symbol.js";
import "core-js/modules/es.symbol.description.js";
import "core-js/modules/es.symbol.iterator.js";
import "core-js/modules/es.function.name.js";
import "core-js/modules/es.object.freeze.js";
var _templateObject, _templateObject2;
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _taggedTemplateLiteral(strings, raw) { if (!raw) { raw = strings.slice(0); } return Object.freeze(Object.defineProperties(strings, { raw: { value: Object.freeze(raw) } })); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }
function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }
import "core-js/modules/es.array.iterator.js";
import "core-js/modules/es.object.to-string.js";
import "core-js/modules/es.set.js";
import "core-js/modules/es.string.iterator.js";
import "core-js/modules/web.dom-collections.iterator.js";
import "core-js/modules/es.array.includes.js";
import "core-js/modules/es.array.sort.js";
import "core-js/modules/es.array.splice.js";
import "core-js/modules/es.number.is-integer.js";
import "core-js/modules/es.number.constructor.js";
import "core-js/modules/es.array.slice.js";
import "core-js/modules/es.array.concat.js";
import "core-js/modules/es.array.fill.js";
import "core-js/modules/es.array.map.js";
import "core-js/modules/es.regexp.exec.js";
import "core-js/modules/es.string.replace.js";
import "core-js/modules/es.array.from.js";
import "core-js/modules/es.array.index-of.js";
import "core-js/modules/es.array.reverse.js";
import "core-js/modules/web.dom-collections.for-each.js";
import "core-js/modules/web.timers.js";
import "core-js/modules/web.immediate.js";
import "core-js/modules/es.map.js";
import { addClass, empty, removeClass } from "./helpers/dom/element.mjs";
import { isFunction } from "./helpers/function.mjs";
import { isDefined, isUndefined, isRegExp, _injectProductInfo, isEmpty } from "./helpers/mixed.mjs";
import { isMobileBrowser, isIpadOS } from "./helpers/browser.mjs";
import { warn } from "./helpers/console.mjs";
import { toSingleLine } from "./helpers/templateLiteralTag.mjs";
import EditorManager from "./editorManager.mjs";
import EventManager from "./eventManager.mjs";
import { deepClone, duckSchema, isObjectEqual, isObject, deepObjectSize, hasOwnProperty, createObjectPropListener, objectEach } from "./helpers/object.mjs";
import { arrayMap, arrayEach, arrayReduce, getDifferenceOfArrays, stringToArray, pivot } from "./helpers/array.mjs";
import { instanceToHTML } from "./utils/parseTable.mjs";
import { getPlugin, getPluginsNames } from "./plugins/registry.mjs";
import { getRenderer } from "./renderers/registry.mjs";
import { getValidator } from "./validators/registry.mjs";
import { randomString, toUpperCaseFirst } from "./helpers/string.mjs";
import { rangeEach, rangeEachReverse, isNumericLike } from "./helpers/number.mjs";
import TableView from "./tableView.mjs";
import DataSource from "./dataMap/dataSource.mjs";
import { cellMethodLookupFactory, spreadsheetColumnLabel } from "./helpers/data.mjs";
import { IndexMapper } from "./translations/index.mjs";
import { registerAsRootInstance, hasValidParameter, isRootInstance } from "./utils/rootInstance.mjs";
import { ViewportColumnsCalculator } from "./3rdparty/walkontable/src/index.mjs";
import Hooks from "./pluginHooks.mjs";
import { hasLanguageDictionary, getValidLanguageCode, getTranslatedPhrase } from "./i18n/registry.mjs";
import { warnUserAboutLanguageRegistration, normalizeLanguageCode } from "./i18n/utils.mjs";
import { Selection } from "./selection/index.mjs";
import { MetaManager, DynamicCellMetaMod, ExtendMetaPropertiesMod, replaceData } from "./dataMap/index.mjs";
import { createUniqueMap } from "./utils/dataStructures/uniqueMap.mjs";
import { createShortcutManager } from "./shortcuts/index.mjs";
var SHORTCUTS_GROUP = 'gridDefault';
var activeGuid = null;
var deprecationWarns = new Set();

/* eslint-disable jsdoc/require-description-complete-sentence */
/**
 * Handsontable constructor.
 *
 * @core
 * @class Core
 * @description
 *
 * The `Handsontable` class to which we refer as to `Core`, allows you to modify the grid's behavior by using one of the available public methods.
 *
 * ## How to call a method
 *
 * ::: only-for javascript
 * ```js
 * // First, let's construct Handsontable
 * const hot = new Handsontable(document.getElementById('example'), options);
 *
 * // Then, let's use the setDataAtCell method
 * hot.setDataAtCell(0, 0, 'new value');
 * ```
 * :::
 *
 * ::: only-for react
 * ```jsx
 * const hotRef = useRef(null);
 *
 * ...
 *
 * // First, let's contruct Handsontable
 * <HotTable
 *   ref={hotRef}
 *   settings={options}
 * />
 *
 * ...
 *
 * const hot = hotRef.current.hotInstance;
 * // Then, let's use the setDataAtCell method
 * hot.setDataAtCell(0, 0, 'new value');
 * ```
 * :::
 *
 * @param {HTMLElement} rootElement The element to which the Handsontable instance is injected.
 * @param {object} userSettings The user defined options.
 * @param {boolean} [rootInstanceSymbol=false] Indicates if the instance is root of all later instances created.
 */
export default function Core(rootElement, userSettings) {
  var _userSettings$layoutD,
    _this = this;
  var rootInstanceSymbol = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
  var preventScrollingToCell = false;
  var instance = this;
  var eventManager = new EventManager(instance);
  var datamap;
  var dataSource;
  var grid;
  var editorManager;
  var firstRun = true;
  if (hasValidParameter(rootInstanceSymbol)) {
    registerAsRootInstance(this);
  }

  // TODO: check if references to DOM elements should be move to UI layer (Walkontable)
  /**
   * Reference to the container element.
   *
   * @private
   * @type {HTMLElement}
   */
  this.rootElement = rootElement;
  /**
   * The nearest document over container.
   *
   * @private
   * @type {Document}
   */
  this.rootDocument = rootElement.ownerDocument;
  /**
   * Window object over container's document.
   *
   * @private
   * @type {Window}
   */
  this.rootWindow = this.rootDocument.defaultView;
  /**
   * A boolean to tell if the Handsontable has been fully destroyed. This is set to `true`
   * after `afterDestroy` hook is called.
   *
   * @memberof Core#
   * @member isDestroyed
   * @type {boolean}
   */
  this.isDestroyed = false;
  /**
   * The counter determines how many times the render suspending was called. It allows
   * tracking the nested suspending calls. For each render suspend resuming call the
   * counter is decremented. The value equal to 0 means the render suspending feature
   * is disabled.
   *
   * @private
   * @type {number}
   */
  this.renderSuspendedCounter = 0;
  /**
   * The counter determines how many times the execution suspending was called. It allows
   * tracking the nested suspending calls. For each execution suspend resuming call the
   * counter is decremented. The value equal to 0 means the execution suspending feature
   * is disabled.
   *
   * @private
   * @type {number}
   */
  this.executionSuspendedCounter = 0;
  var layoutDirection = (_userSettings$layoutD = userSettings === null || userSettings === void 0 ? void 0 : userSettings.layoutDirection) !== null && _userSettings$layoutD !== void 0 ? _userSettings$layoutD : 'inherit';
  var rootElementDirection = ['rtl', 'ltr'].includes(layoutDirection) ? layoutDirection : this.rootWindow.getComputedStyle(this.rootElement).direction;
  this.rootElement.setAttribute('dir', rootElementDirection);

  /**
   * Checks if the grid is rendered using the right-to-left layout direction.
   *
   * @since 12.0.0
   * @memberof Core#
   * @function isRtl
   * @returns {boolean} True if RTL.
   */
  this.isRtl = function () {
    return rootElementDirection === 'rtl';
  };

  /**
   * Checks if the grid is rendered using the left-to-right layout direction.
   *
   * @since 12.0.0
   * @memberof Core#
   * @function isLtr
   * @returns {boolean} True if LTR.
   */
  this.isLtr = function () {
    return !instance.isRtl();
  };

  /**
   * Returns 1 for LTR; -1 for RTL. Useful for calculations.
   *
   * @since 12.0.0
   * @memberof Core#
   * @function getDirectionFactor
   * @returns {number} Returns 1 for LTR; -1 for RTL.
   */
  this.getDirectionFactor = function () {
    return instance.isLtr() ? 1 : -1;
  };
  userSettings.language = getValidLanguageCode(userSettings.language);
  var metaManager = new MetaManager(instance, userSettings, [DynamicCellMetaMod, ExtendMetaPropertiesMod]);
  var tableMeta = metaManager.getTableMeta();
  var globalMeta = metaManager.getGlobalMeta();
  var pluginsRegistry = createUniqueMap();
  this.container = this.rootDocument.createElement('div');
  this.renderCall = false;
  rootElement.insertBefore(this.container, rootElement.firstChild);
  if (isRootInstance(this)) {
    _injectProductInfo(userSettings.licenseKey, rootElement);
  }
  this.guid = "ht_".concat(randomString()); // this is the namespace for global events

  /**
   * Instance of index mapper which is responsible for managing the column indexes.
   *
   * @memberof Core#
   * @member columnIndexMapper
   * @type {IndexMapper}
   */
  this.columnIndexMapper = new IndexMapper();
  /**
   * Instance of index mapper which is responsible for managing the row indexes.
   *
   * @memberof Core#
   * @member rowIndexMapper
   * @type {IndexMapper}
   */
  this.rowIndexMapper = new IndexMapper();
  dataSource = new DataSource(instance);
  if (!this.rootElement.id || this.rootElement.id.substring(0, 3) === 'ht_') {
    this.rootElement.id = this.guid; // if root element does not have an id, assign a random id
  }

  var visualToRenderableCoords = function visualToRenderableCoords(coords) {
    var visualRow = coords.row,
      visualColumn = coords.col;
    return instance._createCellCoords(
    // We just store indexes for rows and columns without headers.
    visualRow >= 0 ? instance.rowIndexMapper.getRenderableFromVisualIndex(visualRow) : visualRow, visualColumn >= 0 ? instance.columnIndexMapper.getRenderableFromVisualIndex(visualColumn) : visualColumn);
  };
  var renderableToVisualCoords = function renderableToVisualCoords(coords) {
    var renderableRow = coords.row,
      renderableColumn = coords.col;
    return instance._createCellCoords(
    // We just store indexes for rows and columns without headers.
    renderableRow >= 0 ? instance.rowIndexMapper.getVisualFromRenderableIndex(renderableRow) : renderableRow, renderableColumn >= 0 ? instance.columnIndexMapper.getVisualFromRenderableIndex(renderableColumn) : renderableColumn // eslint-disable-line max-len
    );
  };

  var selection = new Selection(tableMeta, {
    rowIndexMapper: function rowIndexMapper() {
      return instance.rowIndexMapper;
    },
    columnIndexMapper: function columnIndexMapper() {
      return instance.columnIndexMapper;
    },
    countCols: function countCols() {
      return instance.countCols();
    },
    countRows: function countRows() {
      return instance.countRows();
    },
    propToCol: function propToCol(prop) {
      return datamap.propToCol(prop);
    },
    isEditorOpened: function isEditorOpened() {
      return instance.getActiveEditor() ? instance.getActiveEditor().isOpened() : false;
    },
    countColsTranslated: function countColsTranslated() {
      return _this.view.countRenderableColumns();
    },
    countRowsTranslated: function countRowsTranslated() {
      return _this.view.countRenderableRows();
    },
    getShortcutManager: function getShortcutManager() {
      return instance.getShortcutManager();
    },
    createCellCoords: function createCellCoords(row, column) {
      return instance._createCellCoords(row, column);
    },
    createCellRange: function createCellRange(highlight, from, to) {
      return instance._createCellRange(highlight, from, to);
    },
    visualToRenderableCoords: visualToRenderableCoords,
    renderableToVisualCoords: renderableToVisualCoords,
    isDisabledCellSelection: function isDisabledCellSelection(visualRow, visualColumn) {
      return instance.getCellMeta(visualRow, visualColumn).disableVisualSelection;
    }
  });
  this.selection = selection;
  var onIndexMapperCacheUpdate = function onIndexMapperCacheUpdate(_ref) {
    var hiddenIndexesChanged = _ref.hiddenIndexesChanged;
    if (hiddenIndexesChanged) {
      _this.selection.refresh();
    }
  };
  this.columnIndexMapper.addLocalHook('cacheUpdated', onIndexMapperCacheUpdate);
  this.rowIndexMapper.addLocalHook('cacheUpdated', onIndexMapperCacheUpdate);
  this.selection.addLocalHook('beforeSetRangeStart', function (cellCoords) {
    _this.runHooks('beforeSetRangeStart', cellCoords);
  });
  this.selection.addLocalHook('beforeSetRangeStartOnly', function (cellCoords) {
    _this.runHooks('beforeSetRangeStartOnly', cellCoords);
  });
  this.selection.addLocalHook('beforeSetRangeEnd', function (cellCoords) {
    _this.runHooks('beforeSetRangeEnd', cellCoords);
    if (cellCoords.row < 0) {
      cellCoords.row = _this.view._wt.wtTable.getFirstVisibleRow();
    }
    if (cellCoords.col < 0) {
      cellCoords.col = _this.view._wt.wtTable.getFirstVisibleColumn();
    }
  });
  this.selection.addLocalHook('afterSetRangeEnd', function (cellCoords) {
    var preventScrolling = createObjectPropListener(false);
    var selectionRange = _this.selection.getSelectedRange();
    var _selectionRange$curre = selectionRange.current(),
      from = _selectionRange$curre.from,
      to = _selectionRange$curre.to;
    var selectionLayerLevel = selectionRange.size() - 1;
    _this.runHooks('afterSelection', from.row, from.col, to.row, to.col, preventScrolling, selectionLayerLevel);
    _this.runHooks('afterSelectionByProp', from.row, instance.colToProp(from.col), to.row, instance.colToProp(to.col), preventScrolling, selectionLayerLevel); // eslint-disable-line max-len

    var isSelectedByAnyHeader = _this.selection.isSelectedByAnyHeader();
    var currentSelectedRange = _this.selection.selectedRange.current();
    var scrollToCell = true;
    if (preventScrollingToCell) {
      scrollToCell = false;
    }
    if (preventScrolling.isTouched()) {
      scrollToCell = !preventScrolling.value;
    }
    var isSelectedByRowHeader = _this.selection.isSelectedByRowHeader();
    var isSelectedByColumnHeader = _this.selection.isSelectedByColumnHeader();
    if (scrollToCell !== false) {
      if (!isSelectedByAnyHeader) {
        if (currentSelectedRange && !_this.selection.isMultiple()) {
          _this.view.scrollViewport(visualToRenderableCoords(currentSelectedRange.from));
        } else {
          _this.view.scrollViewport(visualToRenderableCoords(cellCoords));
        }
      } else if (isSelectedByRowHeader) {
        _this.view.scrollViewportVertically(instance.rowIndexMapper.getRenderableFromVisualIndex(cellCoords.row));
      } else if (isSelectedByColumnHeader) {
        _this.view.scrollViewportHorizontally(instance.columnIndexMapper.getRenderableFromVisualIndex(cellCoords.col));
      }
    }

    // @TODO: These CSS classes are no longer needed anymore. They are used only as a indicator of the selected
    // rows/columns in the MergedCells plugin (via border.js#L520 in the walkontable module). After fixing
    // the Border class this should be removed.
    if (isSelectedByRowHeader && isSelectedByColumnHeader) {
      addClass(_this.rootElement, ['ht__selection--rows', 'ht__selection--columns']);
    } else if (isSelectedByRowHeader) {
      removeClass(_this.rootElement, 'ht__selection--columns');
      addClass(_this.rootElement, 'ht__selection--rows');
    } else if (isSelectedByColumnHeader) {
      removeClass(_this.rootElement, 'ht__selection--rows');
      addClass(_this.rootElement, 'ht__selection--columns');
    } else {
      removeClass(_this.rootElement, ['ht__selection--rows', 'ht__selection--columns']);
    }
    _this._refreshBorders(null);
  });
  this.selection.addLocalHook('afterSelectionFinished', function (cellRanges) {
    var selectionLayerLevel = cellRanges.length - 1;
    var _cellRanges$selection = cellRanges[selectionLayerLevel],
      from = _cellRanges$selection.from,
      to = _cellRanges$selection.to;
    _this.runHooks('afterSelectionEnd', from.row, from.col, to.row, to.col, selectionLayerLevel);
    _this.runHooks('afterSelectionEndByProp', from.row, instance.colToProp(from.col), to.row, instance.colToProp(to.col), selectionLayerLevel);
  });
  this.selection.addLocalHook('afterIsMultipleSelection', function (isMultiple) {
    var changedIsMultiple = _this.runHooks('afterIsMultipleSelection', isMultiple.value);
    if (isMultiple.value) {
      isMultiple.value = changedIsMultiple;
    }
  });
  this.selection.addLocalHook('beforeModifyTransformStart', function (cellCoordsDelta) {
    _this.runHooks('modifyTransformStart', cellCoordsDelta);
  });
  this.selection.addLocalHook('afterModifyTransformStart', function (coords, rowTransformDir, colTransformDir) {
    _this.runHooks('afterModifyTransformStart', coords, rowTransformDir, colTransformDir);
  });
  this.selection.addLocalHook('beforeModifyTransformEnd', function (cellCoordsDelta) {
    _this.runHooks('modifyTransformEnd', cellCoordsDelta);
  });
  this.selection.addLocalHook('afterModifyTransformEnd', function (coords, rowTransformDir, colTransformDir) {
    _this.runHooks('afterModifyTransformEnd', coords, rowTransformDir, colTransformDir);
  });
  this.selection.addLocalHook('afterDeselect', function () {
    editorManager.destroyEditor();
    _this._refreshBorders();
    removeClass(_this.rootElement, ['ht__selection--rows', 'ht__selection--columns']);
    _this.runHooks('afterDeselect');
  });
  this.selection.addLocalHook('insertRowRequire', function (totalRows) {
    _this.alter('insert_row_above', totalRows, 1, 'auto');
  });
  this.selection.addLocalHook('insertColRequire', function (totalCols) {
    _this.alter('insert_col_start', totalCols, 1, 'auto');
  });
  grid = {
    /**
     * Inserts or removes rows and columns.
     *
     * @private
     * @param {string} action Possible values: "insert_row_above", "insert_row_below", "insert_col_start", "insert_col_end",
     *                        "remove_row", "remove_col".
     * @param {number|Array} index Row or column visual index which from the alter action will be triggered.
     *                             Alter actions such as "remove_row" and "remove_col" support array indexes in the
     *                             format `[[index, amount], [index, amount]...]` this can be used to remove
     *                             non-consecutive columns or rows in one call.
     * @param {number} [amount=1] Amount of rows or columns to remove.
     * @param {string} [source] Optional. Source of hook runner.
     * @param {boolean} [keepEmptyRows] Optional. Flag for preventing deletion of empty rows.
     */alter: function alter(action, index) {
      var _index, _index2;
      var amount = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
      var source = arguments.length > 3 ? arguments[3] : undefined;
      var keepEmptyRows = arguments.length > 4 ? arguments[4] : undefined;
      var normalizeIndexesGroup = function normalizeIndexesGroup(indexes) {
        if (indexes.length === 0) {
          return [];
        }
        var sortedIndexes = _toConsumableArray(indexes);

        // Sort the indexes in ascending order.
        sortedIndexes.sort(function (_ref2, _ref3) {
          var _ref4 = _slicedToArray(_ref2, 1),
            indexA = _ref4[0];
          var _ref5 = _slicedToArray(_ref3, 1),
            indexB = _ref5[0];
          if (indexA === indexB) {
            return 0;
          }
          return indexA > indexB ? 1 : -1;
        });

        // Normalize the {index, amount} groups into bigger groups.
        var normalizedIndexes = arrayReduce(sortedIndexes, function (acc, _ref6) {
          var _ref7 = _slicedToArray(_ref6, 2),
            groupIndex = _ref7[0],
            groupAmount = _ref7[1];
          var previousItem = acc[acc.length - 1];
          var _previousItem = _slicedToArray(previousItem, 2),
            prevIndex = _previousItem[0],
            prevAmount = _previousItem[1];
          var prevLastIndex = prevIndex + prevAmount;
          if (groupIndex <= prevLastIndex) {
            var amountToAdd = Math.max(groupAmount - (prevLastIndex - groupIndex), 0);
            previousItem[1] += amountToAdd;
          } else {
            acc.push([groupIndex, groupAmount]);
          }
          return acc;
        }, [sortedIndexes[0]]);
        return normalizedIndexes;
      };

      /* eslint-disable no-case-declarations */
      switch (action) {
        case 'insert_row':
          // backward compatibility
          if (!deprecationWarns.has(action)) {
            deprecationWarns.add(action);
            warn(toSingleLine(_templateObject || (_templateObject = _taggedTemplateLiteral(["The `", "` action of the `alter()` method is deprecated and will be removed \n                              in the next major release of Handsontable. Use the `insert_row_above` action instead."], ["The \\`", "\\` action of the \\`alter()\\` method is deprecated and will be removed\\x20\n                              in the next major release of Handsontable. Use the \\`insert_row_above\\` action instead."])), action));
          }
        // falls through
        case 'insert_row_below':
        case 'insert_row_above':
          var numberOfSourceRows = instance.countSourceRows();
          if (tableMeta.maxRows === numberOfSourceRows) {
            return;
          }

          // `above` is the default behavior for creating new rows
          var insertRowMode = action === 'insert_row_below' ? 'below' : 'above';

          // The line below ensures backward compatibility of the `alter()` method's `insert_row` action.
          // Calling the `insert_row` action with no arguments adds a new row at the end of the data set.
          // Calling the `insert_row_above` action adds a new row at the beginning of the data set.
          // eslint-disable-next-line no-param-reassign
          index = (_index = index) !== null && _index !== void 0 ? _index : action === 'insert_row' || insertRowMode === 'below' ? numberOfSourceRows : 0;
          var _datamap$createRow = datamap.createRow(index, amount, {
              source: source,
              mode: insertRowMode
            }),
            rowDelta = _datamap$createRow.delta,
            startRowPhysicalIndex = _datamap$createRow.startPhysicalIndex;
          if (rowDelta) {
            metaManager.createRow(startRowPhysicalIndex, amount);
            var currentSelectedRange = selection.selectedRange.current();
            var currentFromRange = currentSelectedRange === null || currentSelectedRange === void 0 ? void 0 : currentSelectedRange.from;
            var currentFromRow = currentFromRange === null || currentFromRange === void 0 ? void 0 : currentFromRange.row;
            var startVisualRowIndex = instance.toVisualRow(startRowPhysicalIndex);
            if (selection.isSelectedByCorner()) {
              instance.selectAll();
            } else if (isDefined(currentFromRow) && currentFromRow >= startVisualRowIndex) {
              // Moving the selection (if it exists) downward – it should be applied to the "old" row.
              // TODO: The logic here should be handled by selection module.
              var _currentSelectedRange = currentSelectedRange.to,
                currentToRow = _currentSelectedRange.row,
                currentToColumn = _currentSelectedRange.col;
              var currentFromColumn = currentFromRange.col;

              // Workaround: headers are not stored inside selection.
              if (selection.isSelectedByRowHeader()) {
                currentFromColumn = -1;
              }

              // Remove from the stack the last added selection as that selection below will be
              // replaced by new transformed selection.
              selection.getSelectedRange().pop();
              // I can't use transforms as they don't work in negative indexes.
              selection.setRangeStartOnly(instance._createCellCoords(currentFromRow + rowDelta, currentFromColumn), true);
              selection.setRangeEnd(instance._createCellCoords(currentToRow + rowDelta, currentToColumn)); // will call render() internally
            } else {
              instance._refreshBorders(); // it will call render and prepare methods
            }
          }

          break;
        case 'insert_col':
          // backward compatibility
          if (!deprecationWarns.has(action)) {
            deprecationWarns.add(action);
            warn(toSingleLine(_templateObject2 || (_templateObject2 = _taggedTemplateLiteral(["The `", "` action of the `alter()` method is deprecated and will be removed \n                              in the next major release of Handsontable. Use the `insert_col_start` action instead."], ["The \\`", "\\` action of the \\`alter()\\` method is deprecated and will be removed\\x20\n                              in the next major release of Handsontable. Use the \\`insert_col_start\\` action instead."])), action));
          }
        // falls through
        case 'insert_col_start':
        case 'insert_col_end':
          // "start" is a default behavior for creating new columns
          var insertColumnMode = action === 'insert_col_end' ? 'end' : 'start';

          // The line below ensures backward compatibility of the `alter()` method's `insert_col` action.
          // Calling the `insert_col` action with no arguments adds a new column to the right of the data set.
          // Calling the `insert_col_start` action adds a new column to the left of the data set.
          // eslint-disable-next-line no-param-reassign
          index = (_index2 = index) !== null && _index2 !== void 0 ? _index2 : action === 'insert_col' || insertColumnMode === 'end' ? instance.countSourceCols() : 0;
          var _datamap$createCol = datamap.createCol(index, amount, {
              source: source,
              mode: insertColumnMode
            }),
            colDelta = _datamap$createCol.delta,
            startColumnPhysicalIndex = _datamap$createCol.startPhysicalIndex;
          if (colDelta) {
            metaManager.createColumn(startColumnPhysicalIndex, amount);
            if (Array.isArray(tableMeta.colHeaders)) {
              var spliceArray = [instance.toVisualColumn(startColumnPhysicalIndex), 0];
              spliceArray.length += colDelta; // inserts empty (undefined) elements at the end of an array
              Array.prototype.splice.apply(tableMeta.colHeaders, spliceArray); // inserts empty (undefined) elements into the colHeader array
            }

            var _currentSelectedRange2 = selection.selectedRange.current();
            var _currentFromRange = _currentSelectedRange2 === null || _currentSelectedRange2 === void 0 ? void 0 : _currentSelectedRange2.from;
            var _currentFromColumn = _currentFromRange === null || _currentFromRange === void 0 ? void 0 : _currentFromRange.col;
            var startVisualColumnIndex = instance.toVisualColumn(startColumnPhysicalIndex);
            if (selection.isSelectedByCorner()) {
              instance.selectAll();
            } else if (isDefined(_currentFromColumn) && _currentFromColumn >= startVisualColumnIndex) {
              // Moving the selection (if it exists) rightward – it should be applied to the "old" column.
              // TODO: The logic here should be handled by selection module.
              var _currentSelectedRange3 = _currentSelectedRange2.to,
                _currentToRow = _currentSelectedRange3.row,
                _currentToColumn = _currentSelectedRange3.col;
              var _currentFromRow = _currentFromRange.row;

              // Workaround: headers are not stored inside selection.
              if (selection.isSelectedByColumnHeader()) {
                _currentFromRow = -1;
              }

              // Remove from the stack the last added selection as that selection below will be
              // replaced by new transformed selection.
              selection.getSelectedRange().pop();

              // I can't use transforms as they don't work in negative indexes.
              selection.setRangeStartOnly(instance._createCellCoords(_currentFromRow, _currentFromColumn + colDelta), true);
              selection.setRangeEnd(instance._createCellCoords(_currentToRow, _currentToColumn + colDelta)); // will call render() internally
            } else {
              instance._refreshBorders(); // it will call render and prepare methods
            }
          }

          break;
        case 'remove_row':
          var removeRow = function removeRow(indexes) {
            var offset = 0;

            // Normalize the {index, amount} groups into bigger groups.
            arrayEach(indexes, function (_ref8) {
              var _ref9 = _slicedToArray(_ref8, 2),
                groupIndex = _ref9[0],
                groupAmount = _ref9[1];
              var calcIndex = isEmpty(groupIndex) ? instance.countRows() - 1 : Math.max(groupIndex - offset, 0);

              // If the 'index' is an integer decrease it by 'offset' otherwise pass it through to make the value
              // compatible with datamap.removeCol method.
              if (Number.isInteger(groupIndex)) {
                // eslint-disable-next-line no-param-reassign
                groupIndex = Math.max(groupIndex - offset, 0);
              }

              // TODO: for datamap.removeRow index should be passed as it is (with undefined and null values). If not, the logic
              // inside the datamap.removeRow breaks the removing functionality.
              var wasRemoved = datamap.removeRow(groupIndex, groupAmount, source);
              if (!wasRemoved) {
                return;
              }
              metaManager.removeRow(instance.toPhysicalRow(calcIndex), groupAmount);
              var totalRows = instance.countRows();
              var fixedRowsTop = tableMeta.fixedRowsTop;
              if (fixedRowsTop >= calcIndex + 1) {
                tableMeta.fixedRowsTop -= Math.min(groupAmount, fixedRowsTop - calcIndex);
              }
              var fixedRowsBottom = tableMeta.fixedRowsBottom;
              if (fixedRowsBottom && calcIndex >= totalRows - fixedRowsBottom) {
                tableMeta.fixedRowsBottom -= Math.min(groupAmount, fixedRowsBottom);
              }
              offset += groupAmount;
            });
          };
          if (Array.isArray(index)) {
            removeRow(normalizeIndexesGroup(index));
          } else {
            removeRow([[index, amount]]);
          }
          grid.adjustRowsAndCols();
          instance._refreshBorders(); // it will call render and prepare methods
          break;
        case 'remove_col':
          var removeCol = function removeCol(indexes) {
            var offset = 0;

            // Normalize the {index, amount} groups into bigger groups.
            arrayEach(indexes, function (_ref10) {
              var _ref11 = _slicedToArray(_ref10, 2),
                groupIndex = _ref11[0],
                groupAmount = _ref11[1];
              var calcIndex = isEmpty(groupIndex) ? instance.countCols() - 1 : Math.max(groupIndex - offset, 0);
              var physicalColumnIndex = instance.toPhysicalColumn(calcIndex);

              // If the 'index' is an integer decrease it by 'offset' otherwise pass it through to make the value
              // compatible with datamap.removeCol method.
              if (Number.isInteger(groupIndex)) {
                // eslint-disable-next-line no-param-reassign
                groupIndex = Math.max(groupIndex - offset, 0);
              }

              // TODO: for datamap.removeCol index should be passed as it is (with undefined and null values). If not, the logic
              // inside the datamap.removeCol breaks the removing functionality.
              var wasRemoved = datamap.removeCol(groupIndex, groupAmount, source);
              if (!wasRemoved) {
                return;
              }
              metaManager.removeColumn(physicalColumnIndex, groupAmount);
              var fixedColumnsStart = tableMeta.fixedColumnsStart;
              if (fixedColumnsStart >= calcIndex + 1) {
                tableMeta.fixedColumnsStart -= Math.min(groupAmount, fixedColumnsStart - calcIndex);
              }
              if (Array.isArray(tableMeta.colHeaders)) {
                if (typeof physicalColumnIndex === 'undefined') {
                  physicalColumnIndex = -1;
                }
                tableMeta.colHeaders.splice(physicalColumnIndex, groupAmount);
              }
              offset += groupAmount;
            });
          };
          if (Array.isArray(index)) {
            removeCol(normalizeIndexesGroup(index));
          } else {
            removeCol([[index, amount]]);
          }
          grid.adjustRowsAndCols();
          instance._refreshBorders(); // it will call render and prepare methods

          break;
        default:
          throw new Error("There is no such action \"".concat(action, "\""));
      }
      if (!keepEmptyRows) {
        grid.adjustRowsAndCols(); // makes sure that we did not add rows that will be removed in next refresh
      }
    },
    /**
     * Makes sure there are empty rows at the bottom of the table.
     *
     * @private
     */adjustRowsAndCols: function adjustRowsAndCols() {
      var minRows = tableMeta.minRows;
      var minSpareRows = tableMeta.minSpareRows;
      var minCols = tableMeta.minCols;
      var minSpareCols = tableMeta.minSpareCols;
      if (instance.countRows() === 0 && instance.countCols() === 0) {
        selection.deselect();
      }
      if (minRows) {
        // should I add empty rows to data source to meet minRows?
        var nrOfRows = instance.countRows();
        if (nrOfRows < minRows) {
          // The synchronization with cell meta is not desired here. For `minRows` option,
          // we don't want to touch/shift cell meta objects.
          datamap.createRow(nrOfRows, minRows - nrOfRows, {
            source: 'auto'
          });
        }
      }
      if (minSpareRows) {
        var emptyRows = instance.countEmptyRows(true);

        // should I add empty rows to meet minSpareRows?
        if (emptyRows < minSpareRows) {
          var emptyRowsMissing = minSpareRows - emptyRows;
          var rowsToCreate = Math.min(emptyRowsMissing, tableMeta.maxRows - instance.countSourceRows());

          // The synchronization with cell meta is not desired here. For `minSpareRows` option,
          // we don't want to touch/shift cell meta objects.
          datamap.createRow(instance.countRows(), rowsToCreate, {
            source: 'auto'
          });
        }
      }
      {
        var emptyCols;

        // count currently empty cols
        if (minCols || minSpareCols) {
          emptyCols = instance.countEmptyCols(true);
        }
        var nrOfColumns = instance.countCols();

        // should I add empty cols to meet minCols?
        if (minCols && !tableMeta.columns && nrOfColumns < minCols) {
          // The synchronization with cell meta is not desired here. For `minSpareRows` option,
          // we don't want to touch/shift cell meta objects.
          var colsToCreate = minCols - nrOfColumns;
          emptyCols += colsToCreate;
          datamap.createCol(nrOfColumns, colsToCreate, {
            source: 'auto'
          });
        }
        // should I add empty cols to meet minSpareCols?
        if (minSpareCols && !tableMeta.columns && instance.dataType === 'array' && emptyCols < minSpareCols) {
          nrOfColumns = instance.countCols();
          var emptyColsMissing = minSpareCols - emptyCols;
          var _colsToCreate = Math.min(emptyColsMissing, tableMeta.maxCols - nrOfColumns);

          // The synchronization with cell meta is not desired here. For `minSpareRows` option,
          // we don't want to touch/shift cell meta objects.
          datamap.createCol(nrOfColumns, _colsToCreate, {
            source: 'auto'
          });
        }
      }
      if (selection.isSelected()) {
        var rowCount = instance.countRows();
        var colCount = instance.countCols();
        arrayEach(selection.selectedRange, function (range) {
          var selectionChanged = false;
          var fromRow = range.from.row;
          var fromCol = range.from.col;
          var toRow = range.to.row;
          var toCol = range.to.col;

          // if selection is outside, move selection to last row
          if (fromRow > rowCount - 1) {
            fromRow = rowCount - 1;
            selectionChanged = true;
            if (toRow > fromRow) {
              toRow = fromRow;
            }
          } else if (toRow > rowCount - 1) {
            toRow = rowCount - 1;
            selectionChanged = true;
            if (fromRow > toRow) {
              fromRow = toRow;
            }
          }
          // if selection is outside, move selection to last row
          if (fromCol > colCount - 1) {
            fromCol = colCount - 1;
            selectionChanged = true;
            if (toCol > fromCol) {
              toCol = fromCol;
            }
          } else if (toCol > colCount - 1) {
            toCol = colCount - 1;
            selectionChanged = true;
            if (fromCol > toCol) {
              fromCol = toCol;
            }
          }
          if (selectionChanged) {
            instance.selectCell(fromRow, fromCol, toRow, toCol);
          }
        });
      }
      if (instance.view) {
        instance.view.adjustElementsSize();
      }
    },
    /**
     * Populate the data from the provided 2d array from the given cell coordinates.
     *
     * @private
     * @param {object} start Start selection position. Visual indexes.
     * @param {Array} input 2d data array.
     * @param {object} [end] End selection position (only for drag-down mode). Visual indexes.
     * @param {string} [source="populateFromArray"] Source information string.
     * @param {string} [method="overwrite"] Populate method. Possible options: `shift_down`, `shift_right`, `overwrite`.
     * @param {string} direction (left|right|up|down) String specifying the direction.
     * @param {Array} deltas The deltas array. A difference between values of adjacent cells.
     *                       Useful **only** when the type of handled cells is `numeric`.
     * @returns {object|undefined} Ending td in pasted area (only if any cell was changed).
     */populateFromArray: function populateFromArray(start, input, end, source, method, direction, deltas) {
      // TODO: either remove or implement the `direction` argument. Currently it's not working at all.
      var r;
      var rlen;
      var c;
      var clen;
      var setData = [];
      var current = {};
      var newDataByColumns = [];
      var startRow = start.row;
      var startColumn = start.col;
      rlen = input.length;
      if (rlen === 0) {
        return false;
      }
      var columnsPopulationEnd = 0;
      var rowsPopulationEnd = 0;
      if (isObject(end)) {
        columnsPopulationEnd = end.col - startColumn + 1;
        rowsPopulationEnd = end.row - startRow + 1;
      }

      // insert data with specified pasteMode method
      switch (method) {
        case 'shift_down':
          // translate data from a list of rows to a list of columns
          var populatedDataByColumns = pivot(input);
          var numberOfDataColumns = populatedDataByColumns.length;
          // method's argument can extend the range of data population (data would be repeated)
          var numberOfColumnsToPopulate = Math.max(numberOfDataColumns, columnsPopulationEnd);
          var pushedDownDataByRows = instance.getData().slice(startRow);

          // translate data from a list of rows to a list of columns
          var pushedDownDataByColumns = pivot(pushedDownDataByRows).slice(startColumn, startColumn + numberOfColumnsToPopulate);
          for (c = 0; c < numberOfColumnsToPopulate; c += 1) {
            if (c < numberOfDataColumns) {
              for (r = 0, rlen = populatedDataByColumns[c].length; r < rowsPopulationEnd - rlen; r += 1) {
                // repeating data for rows
                populatedDataByColumns[c].push(populatedDataByColumns[c][r % rlen]);
              }
              if (c < pushedDownDataByColumns.length) {
                newDataByColumns.push(populatedDataByColumns[c].concat(pushedDownDataByColumns[c]));
              } else {
                // if before data population, there was no data in the column
                // we fill the required rows' newly-created cells with `null` values
                newDataByColumns.push(populatedDataByColumns[c].concat(new Array(pushedDownDataByRows.length).fill(null)));
              }
            } else {
              // Repeating data for columns.
              newDataByColumns.push(populatedDataByColumns[c % numberOfDataColumns].concat(pushedDownDataByColumns[c]));
            }
          }
          instance.populateFromArray(startRow, startColumn, pivot(newDataByColumns));
          break;
        case 'shift_right':
          var numberOfDataRows = input.length;
          // method's argument can extend the range of data population (data would be repeated)
          var numberOfRowsToPopulate = Math.max(numberOfDataRows, rowsPopulationEnd);
          var pushedRightDataByRows = instance.getData().slice(startRow).map(function (rowData) {
            return rowData.slice(startColumn);
          });
          for (r = 0; r < numberOfRowsToPopulate; r += 1) {
            if (r < numberOfDataRows) {
              for (c = 0, clen = input[r].length; c < columnsPopulationEnd - clen; c += 1) {
                // repeating data for rows
                input[r].push(input[r][c % clen]);
              }
              if (r < pushedRightDataByRows.length) {
                for (var i = 0; i < pushedRightDataByRows[r].length; i += 1) {
                  input[r].push(pushedRightDataByRows[r][i]);
                }
              } else {
                var _input$r;
                // if before data population, there was no data in the row
                // we fill the required columns' newly-created cells with `null` values
                (_input$r = input[r]).push.apply(_input$r, _toConsumableArray(new Array(pushedRightDataByRows[0].length).fill(null)));
              }
            } else {
              // Repeating data for columns.
              input.push(input[r % rlen].slice(0, numberOfRowsToPopulate).concat(pushedRightDataByRows[r]));
            }
          }
          instance.populateFromArray(startRow, startColumn, input);
          break;
        case 'overwrite':
        default:
          // overwrite and other not specified options
          current.row = start.row;
          current.col = start.col;
          var selected = {
            // selected range
            row: end && start ? end.row - start.row + 1 : 1,
            col: end && start ? end.col - start.col + 1 : 1
          };
          var skippedRow = 0;
          var skippedColumn = 0;
          var pushData = true;
          var cellMeta;
          var getInputValue = function getInputValue(row) {
            var col = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
            var rowValue = input[row % input.length];
            if (col !== null) {
              return rowValue[col % rowValue.length];
            }
            return rowValue;
          };
          var rowInputLength = input.length;
          var rowSelectionLength = end ? end.row - start.row + 1 : 0;
          if (end) {
            rlen = rowSelectionLength;
          } else {
            rlen = Math.max(rowInputLength, rowSelectionLength);
          }
          for (r = 0; r < rlen; r++) {
            if (end && current.row > end.row && rowSelectionLength > rowInputLength || !tableMeta.allowInsertRow && current.row > instance.countRows() - 1 || current.row >= tableMeta.maxRows) {
              break;
            }
            var visualRow = r - skippedRow;
            var colInputLength = getInputValue(visualRow).length;
            var colSelectionLength = end ? end.col - start.col + 1 : 0;
            if (end) {
              clen = colSelectionLength;
            } else {
              clen = Math.max(colInputLength, colSelectionLength);
            }
            current.col = start.col;
            cellMeta = instance.getCellMeta(current.row, current.col);
            if ((source === 'CopyPaste.paste' || source === 'Autofill.fill') && cellMeta.skipRowOnPaste) {
              skippedRow += 1;
              current.row += 1;
              rlen += 1;
              /* eslint-disable no-continue */
              continue;
            }
            skippedColumn = 0;
            for (c = 0; c < clen; c++) {
              if (end && current.col > end.col && colSelectionLength > colInputLength || !tableMeta.allowInsertColumn && current.col > instance.countCols() - 1 || current.col >= tableMeta.maxCols) {
                break;
              }
              cellMeta = instance.getCellMeta(current.row, current.col);
              if ((source === 'CopyPaste.paste' || source === 'Autofill.fill') && cellMeta.skipColumnOnPaste) {
                skippedColumn += 1;
                current.col += 1;
                clen += 1;
                continue;
              }
              if (cellMeta.readOnly && source !== 'UndoRedo.undo') {
                current.col += 1;
                /* eslint-disable no-continue */
                continue;
              }
              var visualColumn = c - skippedColumn;
              var value = getInputValue(visualRow, visualColumn);
              var orgValue = instance.getDataAtCell(current.row, current.col);
              var index = {
                row: visualRow,
                col: visualColumn
              };
              if (source === 'Autofill.fill') {
                var result = instance.runHooks('beforeAutofillInsidePopulate', index, direction, input, deltas, {}, selected);
                if (result) {
                  value = isUndefined(result.value) ? value : result.value;
                }
              }
              if (value !== null && _typeof(value) === 'object') {
                // when 'value' is array and 'orgValue' is null, set 'orgValue' to
                // an empty array so that the null value can be compared to 'value'
                // as an empty value for the array context
                if (Array.isArray(value) && orgValue === null) orgValue = [];
                if (orgValue === null || _typeof(orgValue) !== 'object') {
                  pushData = false;
                } else {
                  var orgValueSchema = duckSchema(Array.isArray(orgValue) ? orgValue : orgValue[0] || orgValue);
                  var valueSchema = duckSchema(Array.isArray(value) ? value : value[0] || value);

                  /* eslint-disable max-depth */
                  if (isObjectEqual(orgValueSchema, valueSchema)) {
                    value = deepClone(value);
                  } else {
                    pushData = false;
                  }
                }
              } else if (orgValue !== null && _typeof(orgValue) === 'object') {
                pushData = false;
              }
              if (pushData) {
                setData.push([current.row, current.col, value]);
              }
              pushData = true;
              current.col += 1;
            }
            current.row += 1;
          }
          instance.setDataAtCell(setData, null, null, source || 'populateFromArray');
          break;
      }
    }
  };

  /**
   * Internal function to set `language` key of settings.
   *
   * @private
   * @param {string} languageCode Language code for specific language i.e. 'en-US', 'pt-BR', 'de-DE'.
   * @fires Hooks#afterLanguageChange
   */
  function setLanguage(languageCode) {
    var normalizedLanguageCode = normalizeLanguageCode(languageCode);
    if (hasLanguageDictionary(normalizedLanguageCode)) {
      instance.runHooks('beforeLanguageChange', normalizedLanguageCode);
      globalMeta.language = normalizedLanguageCode;
      instance.runHooks('afterLanguageChange', normalizedLanguageCode);
    } else {
      warnUserAboutLanguageRegistration(languageCode);
    }
  }

  /**
   * Internal function to set `className` or `tableClassName`, depending on the key from the settings object.
   *
   * @private
   * @param {string} className `className` or `tableClassName` from the key in the settings object.
   * @param {string|string[]} classSettings String or array of strings. Contains class name(s) from settings object.
   */
  function setClassName(className, classSettings) {
    var element = className === 'className' ? instance.rootElement : instance.table;
    if (firstRun) {
      addClass(element, classSettings);
    } else {
      var globalMetaSettingsArray = [];
      var settingsArray = [];
      if (globalMeta[className]) {
        globalMetaSettingsArray = Array.isArray(globalMeta[className]) ? globalMeta[className] : stringToArray(globalMeta[className]);
      }
      if (classSettings) {
        settingsArray = Array.isArray(classSettings) ? classSettings : stringToArray(classSettings);
      }
      var classNameToRemove = getDifferenceOfArrays(globalMetaSettingsArray, settingsArray);
      var classNameToAdd = getDifferenceOfArrays(settingsArray, globalMetaSettingsArray);
      if (classNameToRemove.length) {
        removeClass(element, classNameToRemove);
      }
      if (classNameToAdd.length) {
        addClass(element, classNameToAdd);
      }
    }
    globalMeta[className] = classSettings;
  }
  this.init = function () {
    dataSource.setData(tableMeta.data);
    instance.runHooks('beforeInit');
    if (isMobileBrowser() || isIpadOS()) {
      addClass(instance.rootElement, 'mobile');
    }
    this.updateSettings(tableMeta, true);
    this.view = new TableView(this);
    editorManager = EditorManager.getInstance(instance, tableMeta, selection);
    instance.runHooks('init');
    this.forceFullRender = true; // used when data was changed
    this.view.render();
    if (_typeof(firstRun) === 'object') {
      instance.runHooks('afterChange', firstRun[0], firstRun[1]);
      firstRun = false;
    }
    instance.runHooks('afterInit');
  };

  /**
   * @ignore
   * @returns {object}
   */
  function ValidatorsQueue() {
    // moved this one level up so it can be used in any function here. Probably this should be moved to a separate file
    var resolved = false;
    return {
      validatorsInQueue: 0,
      valid: true,
      addValidatorToQueue: function addValidatorToQueue() {
        this.validatorsInQueue += 1;
        resolved = false;
      },
      removeValidatorFormQueue: function removeValidatorFormQueue() {
        this.validatorsInQueue = this.validatorsInQueue - 1 < 0 ? 0 : this.validatorsInQueue - 1;
        this.checkIfQueueIsEmpty();
      },
      onQueueEmpty: function onQueueEmpty() {},
      checkIfQueueIsEmpty: function checkIfQueueIsEmpty() {
        if (this.validatorsInQueue === 0 && resolved === false) {
          resolved = true;
          this.onQueueEmpty(this.valid);
        }
      }
    };
  }

  /**
   * Get parsed number from numeric string.
   *
   * @private
   * @param {string} numericData Float (separated by a dot or a comma) or integer.
   * @returns {number} Number if we get data in parsable format, not changed value otherwise.
   */
  function getParsedNumber(numericData) {
    // Unifying "float like" string. Change from value with comma determiner to value with dot determiner,
    // for example from `450,65` to `450.65`.
    var unifiedNumericData = numericData.replace(',', '.');
    if (isNaN(parseFloat(unifiedNumericData)) === false) {
      return parseFloat(unifiedNumericData);
    }
    return numericData;
  }

  /**
   * @ignore
   * @param {Array} changes The 2D array containing information about each of the edited cells.
   * @param {string} source The string that identifies source of validation.
   * @param {Function} callback The callback function fot async validation.
   */
  function validateChanges(changes, source, callback) {
    if (!changes.length) {
      return;
    }
    var activeEditor = instance.getActiveEditor();
    var beforeChangeResult = instance.runHooks('beforeChange', changes, source || 'edit');
    var shouldBeCanceled = true;
    if (beforeChangeResult === false) {
      if (activeEditor) {
        activeEditor.cancelChanges();
      }
      return;
    }
    var waitingForValidator = new ValidatorsQueue();
    waitingForValidator.onQueueEmpty = function (isValid) {
      if (activeEditor && shouldBeCanceled) {
        activeEditor.cancelChanges();
      }
      callback(isValid); // called when async validators are resolved and beforeChange was not async
    };

    for (var i = changes.length - 1; i >= 0; i--) {
      if (changes[i] === null) {
        changes.splice(i, 1);
      } else {
        var _changes$i = _slicedToArray(changes[i], 4),
          row = _changes$i[0],
          prop = _changes$i[1],
          newValue = _changes$i[3];
        var col = datamap.propToCol(prop);
        var cellProperties = instance.getCellMeta(row, col);
        if (cellProperties.type === 'numeric' && typeof newValue === 'string' && isNumericLike(newValue)) {
          changes[i][3] = getParsedNumber(newValue);
        }

        /* eslint-disable no-loop-func */
        if (instance.getCellValidator(cellProperties)) {
          waitingForValidator.addValidatorToQueue();
          instance.validateCell(changes[i][3], cellProperties, function (index, cellPropertiesReference) {
            return function (result) {
              if (typeof result !== 'boolean') {
                throw new Error('Validation error: result is not boolean');
              }
              if (result === false && cellPropertiesReference.allowInvalid === false) {
                shouldBeCanceled = false;
                changes.splice(index, 1); // cancel the change
                cellPropertiesReference.valid = true; // we cancelled the change, so cell value is still valid

                var cell = instance.getCell(cellPropertiesReference.visualRow, cellPropertiesReference.visualCol);
                if (cell !== null) {
                  removeClass(cell, tableMeta.invalidCellClassName);
                }
                // index -= 1;
              }

              waitingForValidator.removeValidatorFormQueue();
            };
          }(i, cellProperties), source);
        }
      }
    }
    waitingForValidator.checkIfQueueIsEmpty();
  }

  /**
   * Internal function to apply changes. Called after validateChanges.
   *
   * @private
   * @param {Array} changes Array in form of [row, prop, oldValue, newValue].
   * @param {string} source String that identifies how this change will be described in changes array (useful in onChange callback).
   * @fires Hooks#beforeChangeRender
   * @fires Hooks#afterChange
   */
  function applyChanges(changes, source) {
    var i = changes.length - 1;
    if (i < 0) {
      return;
    }
    for (; i >= 0; i--) {
      var skipThisChange = false;
      if (changes[i] === null) {
        changes.splice(i, 1);
        /* eslint-disable no-continue */
        continue;
      }
      if ((changes[i][2] === null || changes[i][2] === void 0) && (changes[i][3] === null || changes[i][3] === void 0)) {
        /* eslint-disable no-continue */
        continue;
      }
      if (tableMeta.allowInsertRow) {
        while (changes[i][0] > instance.countRows() - 1) {
          var _datamap$createRow2 = datamap.createRow(void 0, void 0, {
              source: source
            }),
            numberOfCreatedRows = _datamap$createRow2.delta;
          if (numberOfCreatedRows >= 1) {
            metaManager.createRow(null, numberOfCreatedRows);
          } else {
            skipThisChange = true;
            break;
          }
        }
      }
      if (instance.dataType === 'array' && (!tableMeta.columns || tableMeta.columns.length === 0) && tableMeta.allowInsertColumn) {
        while (datamap.propToCol(changes[i][1]) > instance.countCols() - 1) {
          var _datamap$createCol2 = datamap.createCol(void 0, void 0, {
              source: source
            }),
            numberOfCreatedColumns = _datamap$createCol2.delta;
          if (numberOfCreatedColumns >= 1) {
            metaManager.createColumn(null, numberOfCreatedColumns);
          } else {
            skipThisChange = true;
            break;
          }
        }
      }
      if (skipThisChange) {
        /* eslint-disable no-continue */
        continue;
      }
      datamap.set(changes[i][0], changes[i][1], changes[i][3]);
    }
    instance.forceFullRender = true; // used when data was changed
    grid.adjustRowsAndCols();
    instance.runHooks('beforeChangeRender', changes, source);
    editorManager.lockEditor();
    instance._refreshBorders(null);
    editorManager.unlockEditor();
    instance.view.adjustElementsSize();
    instance.runHooks('afterChange', changes, source || 'edit');
    var activeEditor = instance.getActiveEditor();
    if (activeEditor && isDefined(activeEditor.refreshValue)) {
      activeEditor.refreshValue();
    }
  }

  /**
   * Creates and returns the CellCoords object.
   *
   * @private
   * @memberof Core#
   * @function _createCellCoords
   * @param {number} row The row index.
   * @param {number} column The column index.
   * @returns {CellCoords}
   */
  this._createCellCoords = function (row, column) {
    return instance.view._wt.createCellCoords(row, column);
  };

  /**
   * Creates and returns the CellRange object.
   *
   * @private
   * @memberof Core#
   * @function _createCellRange
   * @param {CellCoords} highlight Defines the border around a cell where selection was started and to edit the cell
   *                               when you press Enter. The highlight cannot point to headers (negative values).
   * @param {CellCoords} from Initial coordinates.
   * @param {CellCoords} to Final coordinates.
   * @returns {CellRange}
   */
  this._createCellRange = function (highlight, from, to) {
    return instance.view._wt.createCellRange(highlight, from, to);
  };

  /**
   * Validate a single cell.
   *
   * @memberof Core#
   * @function validateCell
   * @param {string|number} value The value to validate.
   * @param {object} cellProperties The cell meta which corresponds with the value.
   * @param {Function} callback The callback function.
   * @param {string} source The string that identifies source of the validation.
   */
  this.validateCell = function (value, cellProperties, callback, source) {
    var validator = instance.getCellValidator(cellProperties);

    // the `canBeValidated = false` argument suggests, that the cell passes validation by default.
    /**
     * @private
     * @function done
     * @param {boolean} valid Indicates if the validation was successful.
     * @param {boolean} [canBeValidated=true] Flag which controls the validation process.
     */
    function done(valid) {
      var canBeValidated = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      // Fixes GH#3903
      if (!canBeValidated || cellProperties.hidden === true) {
        callback(valid);
        return;
      }
      var col = cellProperties.visualCol;
      var row = cellProperties.visualRow;
      var td = instance.getCell(row, col, true);
      if (td && td.nodeName !== 'TH') {
        var renderableRow = instance.rowIndexMapper.getRenderableFromVisualIndex(row);
        var renderableColumn = instance.columnIndexMapper.getRenderableFromVisualIndex(col);
        instance.view._wt.getSetting('cellRenderer', renderableRow, renderableColumn, td);
      }
      callback(valid);
    }
    if (isRegExp(validator)) {
      validator = function (expression) {
        return function (cellValue, validatorCallback) {
          validatorCallback(expression.test(cellValue));
        };
      }(validator);
    }
    if (isFunction(validator)) {
      // eslint-disable-next-line no-param-reassign
      value = instance.runHooks('beforeValidate', value, cellProperties.visualRow, cellProperties.prop, source);

      // To provide consistent behaviour, validation should be always asynchronous
      instance._registerImmediate(function () {
        validator.call(cellProperties, value, function (valid) {
          if (!instance) {
            return;
          }
          // eslint-disable-next-line no-param-reassign
          valid = instance.runHooks('afterValidate', valid, value, cellProperties.visualRow, cellProperties.prop, source);
          cellProperties.valid = valid;
          done(valid);
          instance.runHooks('postAfterValidate', valid, value, cellProperties.visualRow, cellProperties.prop, source);
        });
      });
    } else {
      // resolve callback even if validator function was not found
      instance._registerImmediate(function () {
        cellProperties.valid = true;
        done(cellProperties.valid, false);
      });
    }
  };

  /**
   * @ignore
   * @param {number} row The visual row index.
   * @param {string|number} propOrCol The visual prop or column index.
   * @param {*} value The cell value.
   * @returns {Array}
   */
  function setDataInputToArray(row, propOrCol, value) {
    if (Array.isArray(row)) {
      // it's an array of changes
      return row;
    }
    return [[row, propOrCol, value]];
  }

  /**
   * @description
   * Set new value to a cell. To change many cells at once (recommended way), pass an array of `changes` in format
   * `[[row, col, value],...]` as the first argument.
   *
   * @memberof Core#
   * @function setDataAtCell
   * @param {number|Array} row Visual row index or array of changes in format `[[row, col, value],...]`.
   * @param {number} [column] Visual column index.
   * @param {string} [value] New value.
   * @param {string} [source] String that identifies how this change will be described in the changes array (useful in afterChange or beforeChange callback). Set to 'edit' if left empty.
   */
  this.setDataAtCell = function (row, column, value, source) {
    var input = setDataInputToArray(row, column, value);
    var changes = [];
    var changeSource = source;
    var i;
    var ilen;
    var prop;
    for (i = 0, ilen = input.length; i < ilen; i++) {
      if (_typeof(input[i]) !== 'object') {
        throw new Error('Method `setDataAtCell` accepts row number or changes array of arrays as its first parameter');
      }
      if (typeof input[i][1] !== 'number') {
        throw new Error('Method `setDataAtCell` accepts row and column number as its parameters. If you want to use object property name, use method `setDataAtRowProp`'); // eslint-disable-line max-len
      }

      if (input[i][1] >= this.countCols()) {
        prop = input[i][1];
      } else {
        prop = datamap.colToProp(input[i][1]);
      }
      changes.push([input[i][0], prop, dataSource.getAtCell(this.toPhysicalRow(input[i][0]), input[i][1]), input[i][2]]);
    }
    if (!changeSource && _typeof(row) === 'object') {
      changeSource = column;
    }
    instance.runHooks('afterSetDataAtCell', changes, changeSource);
    validateChanges(changes, changeSource, function () {
      applyChanges(changes, changeSource);
    });
  };

  /**
   * @description
   * Set new value to a cell. To change many cells at once (recommended way), pass an array of `changes` in format
   * `[[row, prop, value],...]` as the first argument.
   *
   * @memberof Core#
   * @function setDataAtRowProp
   * @param {number|Array} row Visual row index or array of changes in format `[[row, prop, value], ...]`.
   * @param {string} prop Property name or the source string (e.g. `'first.name'` or `'0'`).
   * @param {string} value Value to be set.
   * @param {string} [source] String that identifies how this change will be described in changes array (useful in onChange callback).
   */
  this.setDataAtRowProp = function (row, prop, value, source) {
    var input = setDataInputToArray(row, prop, value);
    var changes = [];
    var changeSource = source;
    var i;
    var ilen;
    for (i = 0, ilen = input.length; i < ilen; i++) {
      changes.push([input[i][0], input[i][1], dataSource.getAtCell(this.toPhysicalRow(input[i][0]), input[i][1]), input[i][2]]);
    }
    if (!changeSource && _typeof(row) === 'object') {
      changeSource = prop;
    }
    instance.runHooks('afterSetDataAtRowProp', changes, changeSource);
    validateChanges(changes, changeSource, function () {
      applyChanges(changes, changeSource);
    });
  };

  /**
   * Listen to the keyboard input on document body. This allows Handsontable to capture keyboard events and respond
   * in the right way.
   *
   * @memberof Core#
   * @function listen
   * @fires Hooks#afterListen
   */
  this.listen = function () {
    if (instance && !instance.isListening()) {
      activeGuid = instance.guid;
      instance.runHooks('afterListen');
    }
  };

  /**
   * Stop listening to keyboard input on the document body. Calling this method makes the Handsontable inactive for
   * any keyboard events.
   *
   * @memberof Core#
   * @function unlisten
   */
  this.unlisten = function () {
    if (this.isListening()) {
      activeGuid = null;
      instance.runHooks('afterUnlisten');
    }
  };

  /**
   * Returns `true` if the current Handsontable instance is listening to keyboard input on document body.
   *
   * @memberof Core#
   * @function isListening
   * @returns {boolean} `true` if the instance is listening, `false` otherwise.
   */
  this.isListening = function () {
    return activeGuid === instance.guid;
  };

  /**
   * Destroys the current editor, render the table and prepares the editor of the newly selected cell.
   *
   * @memberof Core#
   * @function destroyEditor
   * @param {boolean} [revertOriginal=false] If `true`, the previous value will be restored. Otherwise, the edited value will be saved.
   * @param {boolean} [prepareEditorIfNeeded=true] If `true` the editor under the selected cell will be prepared to open.
   */
  this.destroyEditor = function () {
    var revertOriginal = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
    var prepareEditorIfNeeded = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    instance._refreshBorders(revertOriginal, prepareEditorIfNeeded);
  };

  /**
   * Populates cells at position with 2D input array (e.g. `[[1, 2], [3, 4]]`). Use `endRow`, `endCol` when you
   * want to cut input when a certain row is reached.
   *
   * The `populateFromArray()` method can't change [`readOnly`](@/api/options.md#readonly) cells.
   *
   * Optional `method` argument has the same effect as pasteMode option (see {@link Options#pasteMode}).
   *
   * @memberof Core#
   * @function populateFromArray
   * @param {number} row Start visual row index.
   * @param {number} column Start visual column index.
   * @param {Array} input 2d array.
   * @param {number} [endRow] End visual row index (use when you want to cut input when certain row is reached).
   * @param {number} [endCol] End visual column index (use when you want to cut input when certain column is reached).
   * @param {string} [source=populateFromArray] Used to identify this call in the resulting events (beforeChange, afterChange).
   * @param {string} [method=overwrite] Populate method, possible values: `'shift_down'`, `'shift_right'`, `'overwrite'`.
   * @param {string} direction Populate direction, possible values: `'left'`, `'right'`, `'up'`, `'down'`.
   * @param {Array} deltas The deltas array. A difference between values of adjacent cells.
   *                       Useful **only** when the type of handled cells is `numeric`.
   * @returns {object|undefined} Ending td in pasted area (only if any cell was changed).
   */
  this.populateFromArray = function (row, column, input, endRow, endCol, source, method, direction, deltas) {
    if (!(_typeof(input) === 'object' && _typeof(input[0]) === 'object')) {
      throw new Error('populateFromArray parameter `input` must be an array of arrays'); // API changed in 0.9-beta2, let's check if you use it correctly
    }

    var c = typeof endRow === 'number' ? instance._createCellCoords(endRow, endCol) : null;
    return grid.populateFromArray(instance._createCellCoords(row, column), input, c, source, method, direction, deltas);
  };

  /**
   * Adds/removes data from the column. This method works the same as Array.splice for arrays.
   *
   * @memberof Core#
   * @function spliceCol
   * @param {number} column Index of the column in which do you want to do splice.
   * @param {number} index Index at which to start changing the array. If negative, will begin that many elements from the end.
   * @param {number} amount An integer indicating the number of old array elements to remove. If amount is 0, no elements are removed.
   * @param {...number} [elements] The elements to add to the array. If you don't specify any elements, spliceCol simply removes elements from the array.
   * @returns {Array} Returns removed portion of columns.
   */
  this.spliceCol = function (column, index, amount) {
    var _datamap;
    for (var _len = arguments.length, elements = new Array(_len > 3 ? _len - 3 : 0), _key = 3; _key < _len; _key++) {
      elements[_key - 3] = arguments[_key];
    }
    return (_datamap = datamap).spliceCol.apply(_datamap, [column, index, amount].concat(elements));
  };

  /**
   * Adds/removes data from the row. This method works the same as Array.splice for arrays.
   *
   * @memberof Core#
   * @function spliceRow
   * @param {number} row Index of column in which do you want to do splice.
   * @param {number} index Index at which to start changing the array. If negative, will begin that many elements from the end.
   * @param {number} amount An integer indicating the number of old array elements to remove. If amount is 0, no elements are removed.
   * @param {...number} [elements] The elements to add to the array. If you don't specify any elements, spliceCol simply removes elements from the array.
   * @returns {Array} Returns removed portion of rows.
   */
  this.spliceRow = function (row, index, amount) {
    var _datamap2;
    for (var _len2 = arguments.length, elements = new Array(_len2 > 3 ? _len2 - 3 : 0), _key2 = 3; _key2 < _len2; _key2++) {
      elements[_key2 - 3] = arguments[_key2];
    }
    return (_datamap2 = datamap).spliceRow.apply(_datamap2, [row, index, amount].concat(elements));
  };

  /**
   * Returns indexes of the currently selected cells as an array of arrays `[[startRow, startCol, endRow, endCol],...]`.
   *
   * Start row and start column are the coordinates of the active cell (where the selection was started).
   *
   * The version 0.36.0 adds a non-consecutive selection feature. Since this version, the method returns an array of arrays.
   * Additionally to collect the coordinates of the currently selected area (as it was previously done by the method)
   * you need to use `getSelectedLast` method.
   *
   * @memberof Core#
   * @function getSelected
   * @returns {Array[]|undefined} An array of arrays of the selection's coordinates.
   */
  this.getSelected = function () {
    // https://github.com/handsontable/handsontable/issues/44  //cjl
    if (selection.isSelected()) {
      return arrayMap(selection.getSelectedRange(), function (_ref12) {
        var from = _ref12.from,
          to = _ref12.to;
        return [from.row, from.col, to.row, to.col];
      });
    }
  };

  /**
   * Returns the last coordinates applied to the table as a an array `[startRow, startCol, endRow, endCol]`.
   *
   * @since 0.36.0
   * @memberof Core#
   * @function getSelectedLast
   * @returns {Array|undefined} An array of the selection's coordinates.
   */
  this.getSelectedLast = function () {
    var selected = this.getSelected();
    var result;
    if (selected && selected.length > 0) {
      result = selected[selected.length - 1];
    }
    return result;
  };

  /**
   * Returns the current selection as an array of CellRange objects.
   *
   * The version 0.36.0 adds a non-consecutive selection feature. Since this version, the method returns an array of arrays.
   * Additionally to collect the coordinates of the currently selected area (as it was previously done by the method)
   * you need to use `getSelectedRangeLast` method.
   *
   * @memberof Core#
   * @function getSelectedRange
   * @returns {CellRange[]|undefined} Selected range object or undefined if there is no selection.
   */
  this.getSelectedRange = function () {
    // https://github.com/handsontable/handsontable/issues/44  //cjl
    if (selection.isSelected()) {
      return Array.from(selection.getSelectedRange());
    }
  };

  /**
   * Returns the last coordinates applied to the table as a CellRange object.
   *
   * @memberof Core#
   * @function getSelectedRangeLast
   * @since 0.36.0
   * @returns {CellRange|undefined} Selected range object or undefined` if there is no selection.
   */
  this.getSelectedRangeLast = function () {
    var selectedRange = this.getSelectedRange();
    var result;
    if (selectedRange && selectedRange.length > 0) {
      result = selectedRange[selectedRange.length - 1];
    }
    return result;
  };

  /**
   * Erases content from cells that have been selected in the table.
   *
   * @memberof Core#
   * @function emptySelectedCells
   * @param {string} [source] String that identifies how this change will be described in the changes array (useful in afterChange or beforeChange callback). Set to 'edit' if left empty.
   * @since 0.36.0
   */
  this.emptySelectedCells = function (source) {
    var _this2 = this;
    if (!selection.isSelected() || this.countRows() === 0 || this.countCols() === 0) {
      return;
    }
    var changes = [];
    arrayEach(selection.getSelectedRange(), function (cellRange) {
      var topStart = cellRange.getTopStartCorner();
      var bottomEnd = cellRange.getBottomEndCorner();
      rangeEach(topStart.row, bottomEnd.row, function (row) {
        rangeEach(topStart.col, bottomEnd.col, function (column) {
          if (!_this2.getCellMeta(row, column).readOnly) {
            changes.push([row, column, null]);
          }
        });
      });
    });
    if (changes.length > 0) {
      this.setDataAtCell(changes, source);
    }
  };

  /**
   * Checks if the table rendering process was suspended. See explanation in {@link Core#suspendRender}.
   *
   * @memberof Core#
   * @function isRenderSuspended
   * @since 8.3.0
   * @returns {boolean}
   */
  this.isRenderSuspended = function () {
    return this.renderSuspendedCounter > 0;
  };

  /**
   * Suspends the rendering process. It's helpful to wrap the table render
   * cycles triggered by API calls or UI actions (or both) and call the "render"
   * once in the end. As a result, it improves the performance of wrapped operations.
   * When the table is in the suspend state, most operations will have no visual
   * effect until the rendering state is resumed. Resuming the state automatically
   * invokes the table rendering. To make sure that after executing all operations,
   * the table will be rendered, it's highly recommended to use the {@link Core#batchRender}
   * method or {@link Core#batch}, which additionally aggregates the logic execution
   * that happens behind the table.
   *
   * The method is intended to be used by advanced users. Suspending the rendering
   * process could cause visual glitches when wrongly implemented.
   *
   * Every [`suspendRender()`](@/api/core.md#suspendrender) call needs to correspond with one [`resumeRender()`](@/api/core.md#resumerender) call.
   * For example, if you call [`suspendRender()`](@/api/core.md#suspendrender) 5 times, you need to call [`resumeRender()`](@/api/core.md#resumerender) 5 times as well.
   *
   * @memberof Core#
   * @function suspendRender
   * @since 8.3.0
   * @example
   * ```js
   * hot.suspendRender();
   * hot.alter('insert_row_above', 5, 45);
   * hot.alter('insert_col_start', 10, 40);
   * hot.setDataAtCell(1, 1, 'John');
   * hot.setDataAtCell(2, 2, 'Mark');
   * hot.setDataAtCell(3, 3, 'Ann');
   * hot.setDataAtCell(4, 4, 'Sophia');
   * hot.setDataAtCell(5, 5, 'Mia');
   * hot.selectCell(0, 0);
   * hot.resumeRender(); // It re-renders the table internally
   * ```
   */
  this.suspendRender = function () {
    this.renderSuspendedCounter += 1;
  };

  /**
   * Resumes the rendering process. In combination with the {@link Core#suspendRender}
   * method it allows aggregating the table render cycles triggered by API calls or UI
   * actions (or both) and calls the "render" once in the end. When the table is in
   * the suspend state, most operations will have no visual effect until the rendering
   * state is resumed. Resuming the state automatically invokes the table rendering.
   *
   * The method is intended to be used by advanced users. Suspending the rendering
   * process could cause visual glitches when wrongly implemented.
   *
   * Every [`suspendRender()`](@/api/core.md#suspendrender) call needs to correspond with one [`resumeRender()`](@/api/core.md#resumerender) call.
   * For example, if you call [`suspendRender()`](@/api/core.md#suspendrender) 5 times, you need to call [`resumeRender()`](@/api/core.md#resumerender) 5 times as well.
   *
   * @memberof Core#
   * @function resumeRender
   * @since 8.3.0
   * @example
   * ```js
   * hot.suspendRender();
   * hot.alter('insert_row_above', 5, 45);
   * hot.alter('insert_col_start', 10, 40);
   * hot.setDataAtCell(1, 1, 'John');
   * hot.setDataAtCell(2, 2, 'Mark');
   * hot.setDataAtCell(3, 3, 'Ann');
   * hot.setDataAtCell(4, 4, 'Sophia');
   * hot.setDataAtCell(5, 5, 'Mia');
   * hot.selectCell(0, 0);
   * hot.resumeRender(); // It re-renders the table internally
   * ```
   */
  this.resumeRender = function () {
    var nextValue = this.renderSuspendedCounter - 1;
    this.renderSuspendedCounter = Math.max(nextValue, 0);
    if (!this.isRenderSuspended() && nextValue === this.renderSuspendedCounter) {
      if (this.renderCall) {
        this.render();
      } else {
        this._refreshBorders(null);
      }
    }
  };

  /**
   * Rerender the table. Calling this method starts the process of recalculating, redrawing and applying the changes
   * to the DOM. While rendering the table all cell renderers are recalled.
   *
   * Calling this method manually is not recommended. Handsontable tries to render itself by choosing the most
   * optimal moments in its lifecycle.
   *
   * @memberof Core#
   * @function render
   */
  this.render = function () {
    if (this.view) {
      this.renderCall = true;
      this.forceFullRender = true; // used when data was changed

      if (!this.isRenderSuspended()) {
        editorManager.lockEditor();
        this._refreshBorders(null);
        editorManager.unlockEditor();
      }
    }
  };

  /**
   * The method aggregates multi-line API calls into a callback and postpones the
   * table rendering process. After the execution of the operations, the table is
   * rendered once. As a result, it improves the performance of wrapped operations.
   * Without batching, a similar case could trigger multiple table render calls.
   *
   * @memberof Core#
   * @function batchRender
   * @param {Function} wrappedOperations Batched operations wrapped in a function.
   * @returns {*} Returns result from the wrappedOperations callback.
   * @since 8.3.0
   * @example
   * ```js
   * hot.batchRender(() => {
   *   hot.alter('insert_row_above', 5, 45);
   *   hot.alter('insert_col_start', 10, 40);
   *   hot.setDataAtCell(1, 1, 'John');
   *   hot.setDataAtCell(2, 2, 'Mark');
   *   hot.setDataAtCell(3, 3, 'Ann');
   *   hot.setDataAtCell(4, 4, 'Sophia');
   *   hot.setDataAtCell(5, 5, 'Mia');
   *   hot.selectCell(0, 0);
   *   // The table will be rendered once after executing the callback
   * });
   * ```
   */
  this.batchRender = function (wrappedOperations) {
    this.suspendRender();
    var result = wrappedOperations();
    this.resumeRender();
    return result;
  };

  /**
   * Checks if the table indexes recalculation process was suspended. See explanation
   * in {@link Core#suspendExecution}.
   *
   * @memberof Core#
   * @function isExecutionSuspended
   * @since 8.3.0
   * @returns {boolean}
   */
  this.isExecutionSuspended = function () {
    return this.executionSuspendedCounter > 0;
  };

  /**
   * Suspends the execution process. It's helpful to wrap the table logic changes
   * such as index changes into one call after which the cache is updated. As a result,
   * it improves the performance of wrapped operations.
   *
   * The method is intended to be used by advanced users. Suspending the execution
   * process could cause visual glitches caused by not updated the internal table cache.
   *
   * @memberof Core#
   * @function suspendExecution
   * @since 8.3.0
   * @example
   * ```js
   * hot.suspendExecution();
   * const filters = hot.getPlugin('filters');
   *
   * filters.addCondition(2, 'contains', ['3']);
   * filters.filter();
   * hot.getPlugin('columnSorting').sort({ column: 1, sortOrder: 'desc' });
   * hot.resumeExecution(); // It updates the cache internally
   * ```
   */
  this.suspendExecution = function () {
    this.executionSuspendedCounter += 1;
    this.columnIndexMapper.suspendOperations();
    this.rowIndexMapper.suspendOperations();
  };

  /**
   * Resumes the execution process. In combination with the {@link Core#suspendExecution}
   * method it allows aggregating the table logic changes after which the cache is
   * updated. Resuming the state automatically invokes the table cache updating process.
   *
   * The method is intended to be used by advanced users. Suspending the execution
   * process could cause visual glitches caused by not updated the internal table cache.
   *
   * @memberof Core#
   * @function resumeExecution
   * @param {boolean} [forceFlushChanges=false] If `true`, the table internal data cache
   * is recalculated after the execution of the batched operations. For nested
   * {@link Core#batchExecution} calls, it can be desire to recalculate the table
   * after each batch.
   * @since 8.3.0
   * @example
   * ```js
   * hot.suspendExecution();
   * const filters = hot.getPlugin('filters');
   *
   * filters.addCondition(2, 'contains', ['3']);
   * filters.filter();
   * hot.getPlugin('columnSorting').sort({ column: 1, sortOrder: 'desc' });
   * hot.resumeExecution(); // It updates the cache internally
   * ```
   */
  this.resumeExecution = function () {
    var forceFlushChanges = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
    var nextValue = this.executionSuspendedCounter - 1;
    this.executionSuspendedCounter = Math.max(nextValue, 0);
    if (!this.isExecutionSuspended() && nextValue === this.executionSuspendedCounter || forceFlushChanges) {
      this.columnIndexMapper.resumeOperations();
      this.rowIndexMapper.resumeOperations();
    }
  };

  /**
   * The method aggregates multi-line API calls into a callback and postpones the
   * table execution process. After the execution of the operations, the internal table
   * cache is recalculated once. As a result, it improves the performance of wrapped
   * operations. Without batching, a similar case could trigger multiple table cache rebuilds.
   *
   * @memberof Core#
   * @function batchExecution
   * @param {Function} wrappedOperations Batched operations wrapped in a function.
   * @param {boolean} [forceFlushChanges=false] If `true`, the table internal data cache
   * is recalculated after the execution of the batched operations. For nested calls,
   * it can be a desire to recalculate the table after each batch.
   * @returns {*} Returns result from the wrappedOperations callback.
   * @since 8.3.0
   * @example
   * ```js
   * hot.batchExecution(() => {
   *   const filters = hot.getPlugin('filters');
   *
   *   filters.addCondition(2, 'contains', ['3']);
   *   filters.filter();
   *   hot.getPlugin('columnSorting').sort({ column: 1, sortOrder: 'desc' });
   *   // The table cache will be recalculated once after executing the callback
   * });
   * ```
   */
  this.batchExecution = function (wrappedOperations) {
    var forceFlushChanges = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    this.suspendExecution();
    var result = wrappedOperations();
    this.resumeExecution(forceFlushChanges);
    return result;
  };

  /**
   * It batches the rendering process and index recalculations. The method aggregates
   * multi-line API calls into a callback and postpones the table rendering process
   * as well aggregates the table logic changes such as index changes into one call
   * after which the cache is updated. After the execution of the operations, the
   * table is rendered, and the cache is updated once. As a result, it improves the
   * performance of wrapped operations.
   *
   * @memberof Core#
   * @function batch
   * @param {Function} wrappedOperations Batched operations wrapped in a function.
   * @returns {*} Returns result from the wrappedOperations callback.
   * @since 8.3.0
   * @example
   * ```js
   * hot.batch(() => {
   *   hot.alter('insert_row_above', 5, 45);
   *   hot.alter('insert_col_start', 10, 40);
   *   hot.setDataAtCell(1, 1, 'x');
   *   hot.setDataAtCell(2, 2, 'c');
   *   hot.setDataAtCell(3, 3, 'v');
   *   hot.setDataAtCell(4, 4, 'b');
   *   hot.setDataAtCell(5, 5, 'n');
   *   hot.selectCell(0, 0);
   *
   *   const filters = hot.getPlugin('filters');
   *
   *   filters.addCondition(2, 'contains', ['3']);
   *   filters.filter();
   *   hot.getPlugin('columnSorting').sort({ column: 1, sortOrder: 'desc' });
   *   // The table will be re-rendered and cache will be recalculated once after executing the callback
   * });
   * ```
   */
  this.batch = function (wrappedOperations) {
    this.suspendRender();
    this.suspendExecution();
    var result = wrappedOperations();
    this.resumeExecution();
    this.resumeRender();
    return result;
  };

  /**
   * Updates dimensions of the table. The method compares previous dimensions with the current ones and updates accordingly.
   *
   * @memberof Core#
   * @function refreshDimensions
   * @fires Hooks#beforeRefreshDimensions
   * @fires Hooks#afterRefreshDimensions
   */
  this.refreshDimensions = function () {
    if (!instance.view) {
      return;
    }
    var _instance$view$getLas = instance.view.getLastSize(),
      lastWidth = _instance$view$getLas.width,
      lastHeight = _instance$view$getLas.height;
    var _instance$rootElement = instance.rootElement.getBoundingClientRect(),
      width = _instance$rootElement.width,
      height = _instance$rootElement.height;
    var isSizeChanged = width !== lastWidth || height !== lastHeight;
    var isResizeBlocked = instance.runHooks('beforeRefreshDimensions', {
      width: lastWidth,
      height: lastHeight
    }, {
      width: width,
      height: height
    }, isSizeChanged) === false;
    if (isResizeBlocked) {
      return;
    }
    if (isSizeChanged || instance.view._wt.wtOverlays.scrollableElement === instance.rootWindow) {
      instance.view.setLastSize(width, height);
      instance.render();
    }
    instance.runHooks('afterRefreshDimensions', {
      width: lastWidth,
      height: lastHeight
    }, {
      width: width,
      height: height
    }, isSizeChanged);
  };

  /**
   * The `updateData()` method replaces Handsontable's [`data`](@/api/options.md#data) with a new dataset.
   *
   * The `updateData()` method:
   * - Keeps cells' states (e.g. cells' [formatting](@/guides/cell-features/formatting-cells.md) and cells' [`readOnly`](@/api/options.md#readonly) states)
   * - Keeps rows' states (e.g. row order)
   * - Keeps columns' states (e.g. column order)
   *
   * To replace Handsontable's [`data`](@/api/options.md#data) and reset states, use the [`loadData()`](#loaddata) method.
   *
   * Read more:
   * - [Binding to data](@/guides/getting-started/binding-to-data.md)
   * - [Saving data](@/guides/getting-started/saving-data.md)
   *
   * @memberof Core#
   * @function updateData
   * @since 11.1.0
   * @param {Array} data An [array of arrays](@/guides/getting-started/binding-to-data.md#array-of-arrays), or an [array of objects](@/guides/getting-started/binding-to-data.md#array-of-objects), that contains Handsontable's data
   * @param {string} [source] The source of the `updateData()` call
   * @fires Hooks#beforeUpdateData
   * @fires Hooks#afterUpdateData
   * @fires Hooks#afterChange
   */
  this.updateData = function (data, source) {
    var _this3 = this;
    replaceData(data, function (newDataMap) {
      datamap = newDataMap;
    }, function (newDataMap) {
      datamap = newDataMap;
      instance.columnIndexMapper.fitToLength(_this3.getInitialColumnCount());
      instance.rowIndexMapper.fitToLength(_this3.countSourceRows());
      grid.adjustRowsAndCols();
    }, {
      hotInstance: instance,
      dataMap: datamap,
      dataSource: dataSource,
      internalSource: 'updateData',
      source: source,
      firstRun: firstRun
    });
  };

  /**
   * The `loadData()` method replaces Handsontable's [`data`](@/api/options.md#data) with a new dataset.
   *
   * Additionally, the `loadData()` method:
   * - Resets cells' states (e.g. cells' [formatting](@/guides/cell-features/formatting-cells.md) and cells' [`readOnly`](@/api/options.md#readonly) states)
   * - Resets rows' states (e.g. row order)
   * - Resets columns' states (e.g. column order)
   *
   * To replace Handsontable's [`data`](@/api/options.md#data) without resetting states, use the [`updateData()`](#updatedata) method.
   *
   * Read more:
   * - [Binding to data](@/guides/getting-started/binding-to-data.md)
   * - [Saving data](@/guides/getting-started/saving-data.md)
   *
   * @memberof Core#
   * @function loadData
   * @param {Array} data An [array of arrays](@/guides/getting-started/binding-to-data.md#array-of-arrays), or an [array of objects](@/guides/getting-started/binding-to-data.md#array-of-objects), that contains Handsontable's data
   * @param {string} [source] The source of the `loadData()` call
   * @fires Hooks#beforeLoadData
   * @fires Hooks#afterLoadData
   * @fires Hooks#afterChange
   */
  this.loadData = function (data, source) {
    replaceData(data, function (newDataMap) {
      datamap = newDataMap;
    }, function () {
      metaManager.clearCellsCache();
      instance.initIndexMappers();
      grid.adjustRowsAndCols();
      if (firstRun) {
        firstRun = [null, 'loadData'];
      }
    }, {
      hotInstance: instance,
      dataMap: datamap,
      dataSource: dataSource,
      internalSource: 'loadData',
      source: source,
      firstRun: firstRun
    });
  };

  /**
   * Gets the initial column count, calculated based on the `columns` setting.
   *
   * @private
   * @returns {number} The calculated number of columns.
   */
  this.getInitialColumnCount = function () {
    var columnsSettings = tableMeta.columns;
    var finalNrOfColumns = 0;

    // We will check number of columns when the `columns` property was defined as an array. Columns option may
    // narrow down or expand displayed dataset in that case.
    if (Array.isArray(columnsSettings)) {
      finalNrOfColumns = columnsSettings.length;
    } else if (isFunction(columnsSettings)) {
      if (instance.dataType === 'array') {
        var nrOfSourceColumns = this.countSourceCols();
        for (var columnIndex = 0; columnIndex < nrOfSourceColumns; columnIndex += 1) {
          if (columnsSettings(columnIndex)) {
            finalNrOfColumns += 1;
          }
        }

        // Extended dataset by the `columns` property? Moved code right from the refactored `countCols` method.
      } else if (instance.dataType === 'object' || instance.dataType === 'function') {
        finalNrOfColumns = datamap.colToPropCache.length;
      }

      // In some cases we need to check columns length from the schema, i.e. `data` may be empty.
    } else if (isDefined(tableMeta.dataSchema)) {
      var schema = datamap.getSchema();

      // Schema may be defined as an array of objects. Each object will define column.
      finalNrOfColumns = Array.isArray(schema) ? schema.length : deepObjectSize(schema);
    } else {
      // We init index mappers by length of source data to provide indexes also for skipped indexes.
      finalNrOfColumns = this.countSourceCols();
    }
    return finalNrOfColumns;
  };

  /**
   * Init index mapper which manage indexes assigned to the data.
   *
   * @private
   */
  this.initIndexMappers = function () {
    this.columnIndexMapper.initToLength(this.getInitialColumnCount());
    this.rowIndexMapper.initToLength(this.countSourceRows());
  };

  /**
   * Returns the current data object (the same one that was passed by `data` configuration option or `loadData` method,
   * unless some modifications have been applied (i.e. Sequence of rows/columns was changed, some row/column was skipped).
   * If that's the case - use the {@link Core#getSourceData} method.).
   *
   * Optionally you can provide cell range by defining `row`, `column`, `row2`, `column2` to get only a fragment of table data.
   *
   * @memberof Core#
   * @function getData
   * @param {number} [row] From visual row index.
   * @param {number} [column] From visual column index.
   * @param {number} [row2] To visual row index.
   * @param {number} [column2] To visual column index.
   * @returns {Array[]} Array with the data.
   * @example
   * ```js
   * // Get all data (in order how it is rendered in the table).
   * hot.getData();
   * // Get data fragment (from top-left 0, 0 to bottom-right 3, 3).
   * hot.getData(3, 3);
   * // Get data fragment (from top-left 2, 1 to bottom-right 3, 3).
   * hot.getData(2, 1, 3, 3);
   * ```
   */
  this.getData = function (row, column, row2, column2) {
    if (isUndefined(row)) {
      return datamap.getAll();
    }
    return datamap.getRange(instance._createCellCoords(row, column), instance._createCellCoords(row2, column2), datamap.DESTINATION_RENDERER);
  };

  /**
   * Returns a string value of the selected range. Each column is separated by tab, each row is separated by a new
   * line character.
   *
   * @memberof Core#
   * @function getCopyableText
   * @param {number} startRow From visual row index.
   * @param {number} startCol From visual column index.
   * @param {number} endRow To visual row index.
   * @param {number} endCol To visual column index.
   * @returns {string}
   */
  this.getCopyableText = function (startRow, startCol, endRow, endCol) {
    return datamap.getCopyableText(instance._createCellCoords(startRow, startCol), instance._createCellCoords(endRow, endCol));
  };

  /**
   * Returns the data's copyable value at specified `row` and `column` index.
   *
   * @memberof Core#
   * @function getCopyableData
   * @param {number} row Visual row index.
   * @param {number} column Visual column index.
   * @returns {string}
   */
  this.getCopyableData = function (row, column) {
    return datamap.getCopyable(row, datamap.colToProp(column));
  };

  /**
   * Returns schema provided by constructor settings. If it doesn't exist then it returns the schema based on the data
   * structure in the first row.
   *
   * @memberof Core#
   * @function getSchema
   * @returns {object} Schema object.
   */
  this.getSchema = function () {
    return datamap.getSchema();
  };

  /**
   * Use it if you need to change configuration after initialization. The `settings` argument is an object containing the changed
   * settings, declared the same way as in the initial settings object.
   *
   * __Note__, that although the `updateSettings` method doesn't overwrite the previously declared settings, it might reset
   * the settings made post-initialization. (for example - ignore changes made using the columnResize feature).
   *
   * Since 8.0.0 passing `columns` or `data` inside `settings` objects will result in resetting states corresponding to rows and columns
   * (for example, row/column sequence, column width, row height, frozen columns etc.).
   *
   * Since 12.0.0 passing `data` inside `settings` objects no longer results in resetting states corresponding to rows and columns
   * (for example, row/column sequence, column width, row height, frozen columns etc.).
   *
   * @memberof Core#
   * @function updateSettings
   * @param {object} settings A settings object (see {@link Options}). Only provide the settings that are changed, not the whole settings object that was used for initialization.
   * @param {boolean} [init=false] Internally used for in initialization mode.
   * @example
   * ```js
   * hot.updateSettings({
   *    contextMenu: true,
   *    colHeaders: true,
   *    fixedRowsTop: 2
   * });
   * ```
   * @fires Hooks#afterCellMetaReset
   * @fires Hooks#afterUpdateSettings
   */
  this.updateSettings = function (settings) {
    var init = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    var dataUpdateFunction = (firstRun ? instance.loadData : instance.updateData).bind(this);
    var columnsAsFunc = false;
    var i;
    var j;
    if (isDefined(settings.rows)) {
      throw new Error('The "rows" setting is no longer supported. Do you mean startRows, minRows or maxRows?');
    }
    if (isDefined(settings.cols)) {
      throw new Error('The "cols" setting is no longer supported. Do you mean startCols, minCols or maxCols?');
    }
    if (isDefined(settings.ganttChart)) {
      throw new Error('Since 8.0.0 the "ganttChart" setting is no longer supported.');
    }

    // eslint-disable-next-line no-restricted-syntax
    for (i in settings) {
      if (i === 'data') {
        // Do nothing. loadData will be triggered later
      } else if (i === 'language') {
        setLanguage(settings.language);
      } else if (i === 'className') {
        setClassName('className', settings.className);
      } else if (i === 'tableClassName' && instance.table) {
        setClassName('tableClassName', settings.tableClassName);
        instance.view._wt.wtOverlays.syncOverlayTableClassNames();
      } else if (Hooks.getSingleton().isRegistered(i) || Hooks.getSingleton().isDeprecated(i)) {
        if (isFunction(settings[i]) || Array.isArray(settings[i])) {
          settings[i].initialHook = true;
          instance.addHook(i, settings[i]);
        }
      } else if (!init && hasOwnProperty(settings, i)) {
        // Update settings
        globalMeta[i] = settings[i];
      }
    }

    // Load data or create data map
    if (settings.data === void 0 && tableMeta.data === void 0) {
      dataUpdateFunction(null, 'updateSettings'); // data source created just now
    } else if (settings.data !== void 0) {
      dataUpdateFunction(settings.data, 'updateSettings'); // data source given as option
    } else if (settings.columns !== void 0) {
      datamap.createMap();

      // The `column` property has changed - dataset may be expanded or narrowed down. The `loadData` do the same.
      instance.initIndexMappers();
    }
    var clen = instance.countCols();
    var columnSetting = tableMeta.columns;

    // Init columns constructors configuration
    if (columnSetting && isFunction(columnSetting)) {
      columnsAsFunc = true;
    }

    // Clear cell meta cache
    if (settings.cell !== void 0 || settings.cells !== void 0 || settings.columns !== void 0) {
      metaManager.clearCache();
    }
    if (clen > 0) {
      for (i = 0, j = 0; i < clen; i++) {
        // Use settings provided by user
        if (columnSetting) {
          var column = columnsAsFunc ? columnSetting(i) : columnSetting[j];
          if (column) {
            metaManager.updateColumnMeta(j, column);
          }
        }
        j += 1;
      }
    }
    if (isDefined(settings.cell)) {
      objectEach(settings.cell, function (cell) {
        instance.setCellMetaObject(cell.row, cell.col, cell);
      });
    }
    instance.runHooks('afterCellMetaReset');
    var currentHeight = instance.rootElement.style.height;
    if (currentHeight !== '') {
      currentHeight = parseInt(instance.rootElement.style.height, 10);
    }
    var height = settings.height;
    if (isFunction(height)) {
      height = height();
    }
    if (init) {
      var initialStyle = instance.rootElement.getAttribute('style');
      if (initialStyle) {
        instance.rootElement.setAttribute('data-initialstyle', instance.rootElement.getAttribute('style'));
      }
    }
    if (height === null) {
      var _initialStyle = instance.rootElement.getAttribute('data-initialstyle');
      if (_initialStyle && (_initialStyle.indexOf('height') > -1 || _initialStyle.indexOf('overflow') > -1)) {
        instance.rootElement.setAttribute('style', _initialStyle);
      } else {
        instance.rootElement.style.height = '';
        instance.rootElement.style.overflow = '';
      }
    } else if (height !== void 0) {
      instance.rootElement.style.height = isNaN(height) ? "".concat(height) : "".concat(height, "px");
      instance.rootElement.style.overflow = 'hidden';
    }
    if (typeof settings.width !== 'undefined') {
      var width = settings.width;
      if (isFunction(width)) {
        width = width();
      }
      instance.rootElement.style.width = isNaN(width) ? "".concat(width) : "".concat(width, "px");
    }
    if (!init) {
      if (instance.view) {
        instance.view._wt.wtViewport.resetHasOversizedColumnHeadersMarked();
        instance.view._wt.exportSettingsAsClassNames();
      }
      instance.runHooks('afterUpdateSettings', settings);
    }
    grid.adjustRowsAndCols();
    if (instance.view && !firstRun) {
      instance.forceFullRender = true; // used when data was changed
      editorManager.lockEditor();
      instance._refreshBorders(null);
      instance.view._wt.wtOverlays.adjustElementsSize();
      editorManager.unlockEditor();
    }
    if (!init && instance.view && (currentHeight === '' || height === '' || height === void 0) && currentHeight !== height) {
      instance.view._wt.wtOverlays.updateMainScrollableElements();
    }
  };

  /**
   * Get value from the selected cell.
   *
   * @memberof Core#
   * @function getValue
   * @returns {*} Value of selected cell.
   */
  this.getValue = function () {
    var sel = instance.getSelectedLast();
    if (tableMeta.getValue) {
      if (isFunction(tableMeta.getValue)) {
        return tableMeta.getValue.call(instance);
      } else if (sel) {
        return instance.getData()[sel[0][0]][tableMeta.getValue];
      }
    } else if (sel) {
      return instance.getDataAtCell(sel[0], sel[1]);
    }
  };

  /**
   * Returns the object settings.
   *
   * @memberof Core#
   * @function getSettings
   * @returns {TableMeta} Object containing the current table settings.
   */
  this.getSettings = function () {
    return tableMeta;
  };

  /**
   * Clears the data from the table (the table settings remain intact).
   *
   * @memberof Core#
   * @function clear
   */
  this.clear = function () {
    this.selectAll();
    this.emptySelectedCells();
  };

  /**
   * The `alter()` method lets you alter the grid's structure
   * by adding or removing rows and columns at specified positions.
   *
   * ::: tip
   * The `alter()` method works only when your [`data`](@/api/options.md#data)
   * is an [array of arrays](@/guides/getting-started/binding-to-data.md#array-of-arrays).
   * :::
   *
   * ```js
   * // above row 10 (by visual index), insert 1 new row
   * hot.alter('insert_row_above', 10);
   * ```
   *
   *  | Action               | With `index` | Without `index` |
   *  | -------------------- | ------------ | --------------- |
   *  | `'insert_row_above'` | Inserts rows above the `index` row. | Inserts rows above the first row. |
   *  | `'insert_row_below'` | Inserts rows below the `index` row. | Inserts rows below the last row. |
   *  | `'remove_row'`       | Removes rows, starting from the `index` row. | Removes rows, starting from the last row. |
   *  | `'insert_col_start'` | Inserts columns before the `index` column. | Inserts columns before the first column. |
   *  | `'insert_col_end'`   | Inserts columns after the `index` column. | Inserts columns after the last column. |
   *  | `'remove_col'`       | Removes columns, starting from the `index` column. | Removes columns, starting from the last column. |
   *  | `'insert_row'` (<b>Deprecated</b>) |  Inserts rows above the `index` row. | Inserts rows below the last row. |
   *  | `'insert_col'` (<b>Deprecated</b>) |  Inserts columns before the `index` column. | Inserts columns after the last column. |
   *
   * The behavior of `'insert_col_start'`, `'insert_col_end'`, and `'insert_col'` depends on your [`layoutDirection`](@/api/options.md#layoutdirection).
   *
   * @memberof Core#
   * @function alter
   * @param {string} action Available operations:
   * <ul>
   *    <li> `'insert_row_above'` </li>
   *    <li> `'insert_row_below'` </li>
   *    <li> `'remove_row'` </li> </li>
   *    <li> `'insert_col_start'` </li>
   *    <li> `'insert_col_end'` </li>
   *    <li> `'remove_col'` </li>
   *    <li> `'insert_row'` (<b>Deprecated</b>) </li>
   *    <li> `'insert_col'` (<b>Deprecated</b>) </li>
   * </ul>
   * @param {number|number[]} [index] A visual index of the row/column before or after which the new row/column will be
   *                                inserted or removed. Can also be an array of arrays, in format `[[index, amount],...]`.
   * @param {number} [amount] The amount of rows or columns to be inserted or removed (default: `1`).
   * @param {string} [source] Source indicator.
   * @param {boolean} [keepEmptyRows] If set to `true`: prevents removing empty rows.
   * @example
   * ```js
   * // above row 10 (by visual index), insert 1 new row
   * hot.alter('insert_row_above', 10);
   *
   * // below row 10 (by visual index), insert 3 new rows
   * hot.alter('insert_row_below', 10, 3);
   *
   * // in the LTR layout direction: to the left of column 10 (by visual index), insert 3 new columns
   * // in the RTL layout direction: to the right of column 10 (by visual index), insert 3 new columns
   * hot.alter('insert_col_start', 10, 3);
   *
   * // in the LTR layout direction: to the right of column 10 (by visual index), insert 1 new column
   * // in the RTL layout direction: to the left of column 10 (by visual index), insert 1 new column
   * hot.alter('insert_col_end', 10);
   *
   * // remove 2 rows, starting from row 10 (by visual index)
   * hot.alter('remove_row', 10, 2);
   *
   * // remove 3 rows, starting from row 1 (by visual index)
   * // remove 2 rows, starting from row 5 (by visual index)
   * hot.alter('remove_row', [[1, 3], [5, 2]]);
   * ```
   */
  this.alter = function (action, index, amount, source, keepEmptyRows) {
    grid.alter(action, index, amount, source, keepEmptyRows);
  };

  /**
   * Returns a TD element for the given `row` and `column` arguments, if it is rendered on screen.
   * Returns `null` if the TD is not rendered on screen (probably because that part of the table is not visible).
   *
   * @memberof Core#
   * @function getCell
   * @param {number} row Visual row index.
   * @param {number} column Visual column index.
   * @param {boolean} [topmost=false] If set to `true`, it returns the TD element from the topmost overlay. For example,
   * if the wanted cell is in the range of fixed rows, it will return a TD element from the `top` overlay.
   * @returns {HTMLTableCellElement|null} The cell's TD element.
   */
  this.getCell = function (row, column) {
    var topmost = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    var renderableColumnIndex = column; // Handling also column headers.
    var renderableRowIndex = row; // Handling also row headers.

    if (column >= 0) {
      if (this.columnIndexMapper.isHidden(this.toPhysicalColumn(column))) {
        return null;
      }
      renderableColumnIndex = this.columnIndexMapper.getRenderableFromVisualIndex(column);
    }
    if (row >= 0) {
      if (this.rowIndexMapper.isHidden(this.toPhysicalRow(row))) {
        return null;
      }
      renderableRowIndex = this.rowIndexMapper.getRenderableFromVisualIndex(row);
    }
    if (renderableRowIndex === null || renderableColumnIndex === null) {
      return null;
    }
    return instance.view.getCellAtCoords(instance._createCellCoords(renderableRowIndex, renderableColumnIndex), topmost);
  };

  /**
   * Returns the coordinates of the cell, provided as a HTML table cell element.
   *
   * @memberof Core#
   * @function getCoords
   * @param {HTMLTableCellElement} element The HTML Element representing the cell.
   * @returns {CellCoords|null} Visual coordinates object.
   * @example
   * ```js
   * hot.getCoords(hot.getCell(1, 1));
   * // it returns CellCoords object instance with props row: 1 and col: 1.
   * ```
   */
  this.getCoords = function (element) {
    var renderableCoords = this.view._wt.wtTable.getCoords(element);
    if (renderableCoords === null) {
      return null;
    }
    var renderableRow = renderableCoords.row,
      renderableColumn = renderableCoords.col;
    var visualRow = renderableRow;
    var visualColumn = renderableColumn;
    if (renderableRow >= 0) {
      visualRow = this.rowIndexMapper.getVisualFromRenderableIndex(renderableRow);
    }
    if (renderableColumn >= 0) {
      visualColumn = this.columnIndexMapper.getVisualFromRenderableIndex(renderableColumn);
    }
    return instance._createCellCoords(visualRow, visualColumn);
  };

  /**
   * Returns the property name that corresponds with the given column index.
   * If the data source is an array of arrays, it returns the columns index.
   *
   * @memberof Core#
   * @function colToProp
   * @param {number} column Visual column index.
   * @returns {string|number} Column property or physical column index.
   */
  this.colToProp = function (column) {
    return datamap.colToProp(column);
  };

  /**
   * Returns column index that corresponds with the given property.
   *
   * @memberof Core#
   * @function propToCol
   * @param {string|number} prop Property name or physical column index.
   * @returns {number} Visual column index.
   */
  this.propToCol = function (prop) {
    return datamap.propToCol(prop);
  };

  /**
   * Translate physical row index into visual.
   *
   * This method is useful when you want to retrieve visual row index which can be reordered, moved or trimmed
   * based on a physical index.
   *
   * @memberof Core#
   * @function toVisualRow
   * @param {number} row Physical row index.
   * @returns {number} Returns visual row index.
   */
  this.toVisualRow = function (row) {
    return _this.rowIndexMapper.getVisualFromPhysicalIndex(row);
  };

  /**
   * Translate physical column index into visual.
   *
   * This method is useful when you want to retrieve visual column index which can be reordered, moved or trimmed
   * based on a physical index.
   *
   * @memberof Core#
   * @function toVisualColumn
   * @param {number} column Physical column index.
   * @returns {number} Returns visual column index.
   */
  this.toVisualColumn = function (column) {
    return _this.columnIndexMapper.getVisualFromPhysicalIndex(column);
  };

  /**
   * Translate visual row index into physical.
   *
   * This method is useful when you want to retrieve physical row index based on a visual index which can be
   * reordered, moved or trimmed.
   *
   * @memberof Core#
   * @function toPhysicalRow
   * @param {number} row Visual row index.
   * @returns {number} Returns physical row index.
   */
  this.toPhysicalRow = function (row) {
    return _this.rowIndexMapper.getPhysicalFromVisualIndex(row);
  };

  /**
   * Translate visual column index into physical.
   *
   * This method is useful when you want to retrieve physical column index based on a visual index which can be
   * reordered, moved or trimmed.
   *
   * @memberof Core#
   * @function toPhysicalColumn
   * @param {number} column Visual column index.
   * @returns {number} Returns physical column index.
   */
  this.toPhysicalColumn = function (column) {
    return _this.columnIndexMapper.getPhysicalFromVisualIndex(column);
  };

  /**
   * @description
   * Returns the cell value at `row`, `column`.
   *
   * __Note__: If data is reordered, sorted or trimmed, the currently visible order will be used.
   *
   * @memberof Core#
   * @function getDataAtCell
   * @param {number} row Visual row index.
   * @param {number} column Visual column index.
   * @returns {*} Data at cell.
   */
  this.getDataAtCell = function (row, column) {
    return datamap.get(row, datamap.colToProp(column));
  };

  /**
   * Returns value at visual `row` and `prop` indexes.
   *
   * __Note__: If data is reordered, sorted or trimmed, the currently visible order will be used.
   *
   * @memberof Core#
   * @function getDataAtRowProp
   * @param {number} row Visual row index.
   * @param {string} prop Property name.
   * @returns {*} Cell value.
   */
  this.getDataAtRowProp = function (row, prop) {
    return datamap.get(row, prop);
  };

  /**
   * @description
   * Returns array of column values from the data source.
   *
   * __Note__: If columns were reordered or sorted, the currently visible order will be used.
   *
   * @memberof Core#
   * @function getDataAtCol
   * @param {number} column Visual column index.
   * @returns {Array} Array of cell values.
   */
  this.getDataAtCol = function (column) {
    var _ref13;
    return (_ref13 = []).concat.apply(_ref13, _toConsumableArray(datamap.getRange(instance._createCellCoords(0, column), instance._createCellCoords(tableMeta.data.length - 1, column), datamap.DESTINATION_RENDERER)));
  };

  /**
   * Given the object property name (e.g. `'first.name'` or `'0'`), returns an array of column's values from the table data.
   * You can also provide a column index as the first argument.
   *
   * @memberof Core#
   * @function getDataAtProp
   * @param {string|number} prop Property name or physical column index.
   * @returns {Array} Array of cell values.
   */
  // TODO: Getting data from `datamap` should work on visual indexes.
  this.getDataAtProp = function (prop) {
    var _ref14;
    var range = datamap.getRange(instance._createCellCoords(0, datamap.propToCol(prop)), instance._createCellCoords(tableMeta.data.length - 1, datamap.propToCol(prop)), datamap.DESTINATION_RENDERER);
    return (_ref14 = []).concat.apply(_ref14, _toConsumableArray(range));
  };

  /**
   * Returns a clone of the source data object.
   * Optionally you can provide a cell range by using the `row`, `column`, `row2`, `column2` arguments, to get only a
   * fragment of the table data.
   *
   * __Note__: This method does not participate in data transformation. If the visual data of the table is reordered,
   * sorted or trimmed only physical indexes are correct.
   *
   * @memberof Core#
   * @function getSourceData
   * @param {number} [row] From physical row index.
   * @param {number} [column] From physical column index (or visual index, if data type is an array of objects).
   * @param {number} [row2] To physical row index.
   * @param {number} [column2] To physical column index (or visual index, if data type is an array of objects).
   * @returns {Array[]|object[]} The table data.
   */
  this.getSourceData = function (row, column, row2, column2) {
    var data;
    if (row === void 0) {
      data = dataSource.getData();
    } else {
      data = dataSource.getByRange(instance._createCellCoords(row, column), instance._createCellCoords(row2, column2));
    }
    return data;
  };

  /**
   * Returns the source data object as an arrays of arrays format even when source data was provided in another format.
   * Optionally you can provide a cell range by using the `row`, `column`, `row2`, `column2` arguments, to get only a
   * fragment of the table data.
   *
   * __Note__: This method does not participate in data transformation. If the visual data of the table is reordered,
   * sorted or trimmed only physical indexes are correct.
   *
   * @memberof Core#
   * @function getSourceDataArray
   * @param {number} [row] From physical row index.
   * @param {number} [column] From physical column index (or visual index, if data type is an array of objects).
   * @param {number} [row2] To physical row index.
   * @param {number} [column2] To physical column index (or visual index, if data type is an array of objects).
   * @returns {Array} An array of arrays.
   */
  this.getSourceDataArray = function (row, column, row2, column2) {
    var data;
    if (row === void 0) {
      data = dataSource.getData(true);
    } else {
      data = dataSource.getByRange(instance._createCellCoords(row, column), instance._createCellCoords(row2, column2), true);
    }
    return data;
  };

  /**
   * Returns an array of column values from the data source.
   *
   * @memberof Core#
   * @function getSourceDataAtCol
   * @param {number} column Visual column index.
   * @returns {Array} Array of the column's cell values.
   */
  // TODO: Getting data from `sourceData` should work always on physical indexes.
  this.getSourceDataAtCol = function (column) {
    return dataSource.getAtColumn(column);
  };

  /* eslint-disable jsdoc/require-param */
  /**
   * Set the provided value in the source data set at the provided coordinates.
   *
   * @memberof Core#
   * @function setSourceDataAtCell
   * @param {number|Array} row Physical row index or array of changes in format `[[row, prop, value], ...]`.
   * @param {number|string} column Physical column index / prop name.
   * @param {*} value The value to be set at the provided coordinates.
   * @param {string} [source] Source of the change as a string.
   */
  /* eslint-enable jsdoc/require-param */
  this.setSourceDataAtCell = function (row, column, value, source) {
    var input = setDataInputToArray(row, column, value);
    var isThereAnySetSourceListener = this.hasHook('afterSetSourceDataAtCell');
    var changesForHook = [];
    if (isThereAnySetSourceListener) {
      arrayEach(input, function (_ref15) {
        var _ref16 = _slicedToArray(_ref15, 3),
          changeRow = _ref16[0],
          changeProp = _ref16[1],
          changeValue = _ref16[2];
        changesForHook.push([changeRow, changeProp, dataSource.getAtCell(changeRow, changeProp),
        // The previous value.
        changeValue]);
      });
    }
    arrayEach(input, function (_ref17) {
      var _ref18 = _slicedToArray(_ref17, 3),
        changeRow = _ref18[0],
        changeProp = _ref18[1],
        changeValue = _ref18[2];
      dataSource.setAtCell(changeRow, changeProp, changeValue);
    });
    if (isThereAnySetSourceListener) {
      this.runHooks('afterSetSourceDataAtCell', changesForHook, source);
    }
    this.render();
    var activeEditor = instance.getActiveEditor();
    if (activeEditor && isDefined(activeEditor.refreshValue)) {
      activeEditor.refreshValue();
    }
  };

  /**
   * Returns a single row of the data (array or object, depending on what data format you use).
   *
   * __Note__: This method does not participate in data transformation. If the visual data of the table is reordered,
   * sorted or trimmed only physical indexes are correct.
   *
   * @memberof Core#
   * @function getSourceDataAtRow
   * @param {number} row Physical row index.
   * @returns {Array|object} Single row of data.
   */
  this.getSourceDataAtRow = function (row) {
    return dataSource.getAtRow(row);
  };

  /**
   * Returns a single value from the data source.
   *
   * @memberof Core#
   * @function getSourceDataAtCell
   * @param {number} row Physical row index.
   * @param {number} column Visual column index.
   * @returns {*} Cell data.
   */
  // TODO: Getting data from `sourceData` should work always on physical indexes.
  this.getSourceDataAtCell = function (row, column) {
    return dataSource.getAtCell(row, column);
  };

  /**
   * @description
   * Returns a single row of the data.
   *
   * __Note__: If rows were reordered, sorted or trimmed, the currently visible order will be used.
   *
   * @memberof Core#
   * @function getDataAtRow
   * @param {number} row Visual row index.
   * @returns {Array} Array of row's cell data.
   */
  this.getDataAtRow = function (row) {
    var data = datamap.getRange(instance._createCellCoords(row, 0), instance._createCellCoords(row, this.countCols() - 1), datamap.DESTINATION_RENDERER);
    return data[0] || [];
  };

  /**
   * @description
   * Returns a data type defined in the Handsontable settings under the `type` key ({@link Options#type}).
   * If there are cells with different types in the selected range, it returns `'mixed'`.
   *
   * __Note__: If data is reordered, sorted or trimmed, the currently visible order will be used.
   *
   * @memberof Core#
   * @function getDataType
   * @param {number} rowFrom From visual row index.
   * @param {number} columnFrom From visual column index.
   * @param {number} rowTo To visual row index.
   * @param {number} columnTo To visual column index.
   * @returns {string} Cell type (e.q: `'mixed'`, `'text'`, `'numeric'`, `'autocomplete'`).
   */
  this.getDataType = function (rowFrom, columnFrom, rowTo, columnTo) {
    var _this4 = this;
    var coords = rowFrom === void 0 ? [0, 0, this.countRows(), this.countCols()] : [rowFrom, columnFrom, rowTo, columnTo];
    var rowStart = coords[0],
      columnStart = coords[1];
    var rowEnd = coords[2],
      columnEnd = coords[3];
    var previousType = null;
    var currentType = null;
    if (rowEnd === void 0) {
      rowEnd = rowStart;
    }
    if (columnEnd === void 0) {
      columnEnd = columnStart;
    }
    var type = 'mixed';
    rangeEach(Math.max(Math.min(rowStart, rowEnd), 0), Math.max(rowStart, rowEnd), function (row) {
      var isTypeEqual = true;
      rangeEach(Math.max(Math.min(columnStart, columnEnd), 0), Math.max(columnStart, columnEnd), function (column) {
        var cellType = _this4.getCellMeta(row, column);
        currentType = cellType.type;
        if (previousType) {
          isTypeEqual = previousType === currentType;
        } else {
          previousType = currentType;
        }
        return isTypeEqual;
      });
      type = isTypeEqual ? currentType : 'mixed';
      return isTypeEqual;
    });
    return type;
  };

  /**
   * Remove a property defined by the `key` argument from the cell meta object for the provided `row` and `column` coordinates.
   *
   * @memberof Core#
   * @function removeCellMeta
   * @param {number} row Visual row index.
   * @param {number} column Visual column index.
   * @param {string} key Property name.
   * @fires Hooks#beforeRemoveCellMeta
   * @fires Hooks#afterRemoveCellMeta
   */
  this.removeCellMeta = function (row, column, key) {
    var _ref19 = [this.toPhysicalRow(row), this.toPhysicalColumn(column)],
      physicalRow = _ref19[0],
      physicalColumn = _ref19[1];
    var cachedValue = metaManager.getCellMetaKeyValue(physicalRow, physicalColumn, key);
    var hookResult = instance.runHooks('beforeRemoveCellMeta', row, column, key, cachedValue);
    if (hookResult !== false) {
      metaManager.removeCellMeta(physicalRow, physicalColumn, key);
      instance.runHooks('afterRemoveCellMeta', row, column, key, cachedValue);
    }
    cachedValue = null;
  };

  /**
   * Removes or adds one or more rows of the cell meta objects to the cell meta collections.
   *
   * @since 0.30.0
   * @memberof Core#
   * @function spliceCellsMeta
   * @param {number} visualIndex A visual index that specifies at what position to add/remove items.
   * @param {number} [deleteAmount=0] The number of items to be removed. If set to 0, no cell meta objects will be removed.
   * @param {...object} [cellMetaRows] The new cell meta row objects to be added to the cell meta collection.
   */
  this.spliceCellsMeta = function (visualIndex) {
    var _this5 = this;
    var deleteAmount = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
    for (var _len3 = arguments.length, cellMetaRows = new Array(_len3 > 2 ? _len3 - 2 : 0), _key3 = 2; _key3 < _len3; _key3++) {
      cellMetaRows[_key3 - 2] = arguments[_key3];
    }
    if (cellMetaRows.length > 0 && !Array.isArray(cellMetaRows[0])) {
      throw new Error('The 3rd argument (cellMetaRows) has to be passed as an array of cell meta objects array.');
    }
    if (deleteAmount > 0) {
      metaManager.removeRow(this.toPhysicalRow(visualIndex), deleteAmount);
    }
    if (cellMetaRows.length > 0) {
      arrayEach(cellMetaRows.reverse(), function (cellMetaRow) {
        metaManager.createRow(_this5.toPhysicalRow(visualIndex));
        arrayEach(cellMetaRow, function (cellMeta, columnIndex) {
          return _this5.setCellMetaObject(visualIndex, columnIndex, cellMeta);
        });
      });
    }
    instance.render();
  };

  /**
   * Set cell meta data object defined by `prop` to the corresponding params `row` and `column`.
   *
   * @memberof Core#
   * @function setCellMetaObject
   * @param {number} row Visual row index.
   * @param {number} column Visual column index.
   * @param {object} prop Meta object.
   */
  this.setCellMetaObject = function (row, column, prop) {
    var _this6 = this;
    if (_typeof(prop) === 'object') {
      objectEach(prop, function (value, key) {
        _this6.setCellMeta(row, column, key, value);
      });
    }
  };

  /**
   * Sets a property defined by the `key` property to the meta object of a cell corresponding to params `row` and `column`.
   *
   * @memberof Core#
   * @function setCellMeta
   * @param {number} row Visual row index.
   * @param {number} column Visual column index.
   * @param {string} key Property name.
   * @param {string} value Property value.
   * @fires Hooks#beforeSetCellMeta
   * @fires Hooks#afterSetCellMeta
   */
  this.setCellMeta = function (row, column, key, value) {
    var allowSetCellMeta = instance.runHooks('beforeSetCellMeta', row, column, key, value);
    if (allowSetCellMeta === false) {
      return;
    }
    var physicalRow = row;
    var physicalColumn = column;
    if (row < this.countRows()) {
      physicalRow = this.toPhysicalRow(row);
    }
    if (column < this.countCols()) {
      physicalColumn = this.toPhysicalColumn(column);
    }
    metaManager.setCellMeta(physicalRow, physicalColumn, key, value);
    instance.runHooks('afterSetCellMeta', row, column, key, value);
  };

  /**
   * Get all the cells meta settings at least once generated in the table (in order of cell initialization).
   *
   * @memberof Core#
   * @function getCellsMeta
   * @returns {Array} Returns an array of ColumnSettings object instances.
   */
  this.getCellsMeta = function () {
    return metaManager.getCellsMeta();
  };

  /**
   * Returns the cell properties object for the given `row` and `column` coordinates.
   *
   * @memberof Core#
   * @function getCellMeta
   * @param {number} row Visual row index.
   * @param {number} column Visual column index.
   * @returns {object} The cell properties object.
   * @fires Hooks#beforeGetCellMeta
   * @fires Hooks#afterGetCellMeta
   */
  this.getCellMeta = function (row, column) {
    var physicalRow = this.toPhysicalRow(row);
    var physicalColumn = this.toPhysicalColumn(column);
    if (physicalRow === null) {
      physicalRow = row;
    }
    if (physicalColumn === null) {
      physicalColumn = column;
    }
    return metaManager.getCellMeta(physicalRow, physicalColumn, {
      visualRow: row,
      visualColumn: column
    });
  };

  /**
   * Returns an array of cell meta objects for specified physical row index.
   *
   * @memberof Core#
   * @function getCellMetaAtRow
   * @param {number} row Physical row index.
   * @returns {Array}
   */
  this.getCellMetaAtRow = function (row) {
    return metaManager.getCellsMetaAtRow(row);
  };

  /**
   * Checks if the data format and config allows user to modify the column structure.
   *
   * @memberof Core#
   * @function isColumnModificationAllowed
   * @returns {boolean}
   */
  this.isColumnModificationAllowed = function () {
    return !(instance.dataType === 'object' || tableMeta.columns);
  };
  var rendererLookup = cellMethodLookupFactory('renderer');

  /**
   * Returns the cell renderer function by given `row` and `column` arguments.
   *
   * @memberof Core#
   * @function getCellRenderer
   * @param {number|object} row Visual row index or cell meta object (see {@link Core#getCellMeta}).
   * @param {number} column Visual column index.
   * @returns {Function} The renderer function.
   * @example
   * ```js
   * // Get cell renderer using `row` and `column` coordinates.
   * hot.getCellRenderer(1, 1);
   * // Get cell renderer using cell meta object.
   * hot.getCellRenderer(hot.getCellMeta(1, 1));
   * ```
   */
  this.getCellRenderer = function (row, column) {
    return getRenderer(rendererLookup.call(this, row, column));
  };

  /**
   * Returns the cell editor class by the provided `row` and `column` arguments.
   *
   * @memberof Core#
   * @function getCellEditor
   * @param {number} row Visual row index or cell meta object (see {@link Core#getCellMeta}).
   * @param {number} column Visual column index.
   * @returns {Function} The editor class.
   * @example
   * ```js
   * // Get cell editor class using `row` and `column` coordinates.
   * hot.getCellEditor(1, 1);
   * // Get cell editor class using cell meta object.
   * hot.getCellEditor(hot.getCellMeta(1, 1));
   * ```
   */
  this.getCellEditor = cellMethodLookupFactory('editor');
  var validatorLookup = cellMethodLookupFactory('validator');

  /**
   * Returns the cell validator by `row` and `column`.
   *
   * @memberof Core#
   * @function getCellValidator
   * @param {number|object} row Visual row index or cell meta object (see {@link Core#getCellMeta}).
   * @param {number} column Visual column index.
   * @returns {Function|RegExp|undefined} The validator function.
   * @example
   * ```js
   * // Get cell valiator using `row` and `column` coordinates.
   * hot.getCellValidator(1, 1);
   * // Get cell valiator using cell meta object.
   * hot.getCellValidator(hot.getCellMeta(1, 1));
   * ```
   */
  this.getCellValidator = function (row, column) {
    var validator = validatorLookup.call(this, row, column);
    if (typeof validator === 'string') {
      validator = getValidator(validator);
    }
    return validator;
  };

  /**
   * Validates all cells using their validator functions and calls callback when finished.
   *
   * If one of the cells is invalid, the callback will be fired with `'valid'` arguments as `false` - otherwise it
   * would equal `true`.
   *
   * @memberof Core#
   * @function validateCells
   * @param {Function} [callback] The callback function.
   * @example
   * ```js
   * hot.validateCells((valid) => {
   *   if (valid) {
   *     // ... code for validated cells
   *   }
   * })
   * ```
   */
  this.validateCells = function (callback) {
    this._validateCells(callback);
  };

  /**
   * Validates rows using their validator functions and calls callback when finished.
   *
   * If one of the cells is invalid, the callback will be fired with `'valid'` arguments as `false` - otherwise it
   *  would equal `true`.
   *
   * @memberof Core#
   * @function validateRows
   * @param {Array} [rows] Array of validation target visual row indexes.
   * @param {Function} [callback] The callback function.
   * @example
   * ```js
   * hot.validateRows([3, 4, 5], (valid) => {
   *   if (valid) {
   *     // ... code for validated rows
   *   }
   * })
   * ```
   */
  this.validateRows = function (rows, callback) {
    if (!Array.isArray(rows)) {
      throw new Error('validateRows parameter `rows` must be an array');
    }
    this._validateCells(callback, rows);
  };

  /**
   * Validates columns using their validator functions and calls callback when finished.
   *
   * If one of the cells is invalid, the callback will be fired with `'valid'` arguments as `false` - otherwise it
   *  would equal `true`.
   *
   * @memberof Core#
   * @function validateColumns
   * @param {Array} [columns] Array of validation target visual columns indexes.
   * @param {Function} [callback] The callback function.
   * @example
   * ```js
   * hot.validateColumns([3, 4, 5], (valid) => {
   *   if (valid) {
   *     // ... code for validated columns
   *   }
   * })
   * ```
   */
  this.validateColumns = function (columns, callback) {
    if (!Array.isArray(columns)) {
      throw new Error('validateColumns parameter `columns` must be an array');
    }
    this._validateCells(callback, undefined, columns);
  };

  /**
   * Validates all cells using their validator functions and calls callback when finished.
   *
   * If one of the cells is invalid, the callback will be fired with `'valid'` arguments as `false` - otherwise it would equal `true`.
   *
   * Private use intended.
   *
   * @private
   * @memberof Core#
   * @function _validateCells
   * @param {Function} [callback] The callback function.
   * @param {Array} [rows] An array of validation target visual row indexes.
   * @param {Array} [columns] An array of validation target visual column indexes.
   */
  this._validateCells = function (callback, rows, columns) {
    var waitingForValidator = new ValidatorsQueue();
    if (callback) {
      waitingForValidator.onQueueEmpty = callback;
    }
    var i = instance.countRows() - 1;
    while (i >= 0) {
      if (rows !== undefined && rows.indexOf(i) === -1) {
        i -= 1;
        continue;
      }
      var j = instance.countCols() - 1;
      while (j >= 0) {
        if (columns !== undefined && columns.indexOf(j) === -1) {
          j -= 1;
          continue;
        }
        waitingForValidator.addValidatorToQueue();
        instance.validateCell(instance.getDataAtCell(i, j), instance.getCellMeta(i, j), function (result) {
          if (typeof result !== 'boolean') {
            throw new Error('Validation error: result is not boolean');
          }
          if (result === false) {
            waitingForValidator.valid = false;
          }
          waitingForValidator.removeValidatorFormQueue();
        }, 'validateCells');
        j -= 1;
      }
      i -= 1;
    }
    waitingForValidator.checkIfQueueIsEmpty();
  };

  /**
   * Returns an array of row headers' values (if they are enabled). If param `row` was given, it returns the header of the given row as a string.
   *
   * @memberof Core#
   * @function getRowHeader
   * @param {number} [row] Visual row index.
   * @fires Hooks#modifyRowHeader
   * @returns {Array|string|number} Array of header values / single header value.
   */
  this.getRowHeader = function (row) {
    var rowHeader = tableMeta.rowHeaders;
    var physicalRow = row;
    if (physicalRow !== void 0) {
      physicalRow = instance.runHooks('modifyRowHeader', physicalRow);
    }
    if (physicalRow === void 0) {
      rowHeader = [];
      rangeEach(instance.countRows() - 1, function (i) {
        rowHeader.push(instance.getRowHeader(i));
      });
    } else if (Array.isArray(rowHeader) && rowHeader[physicalRow] !== void 0) {
      rowHeader = rowHeader[physicalRow];
    } else if (isFunction(rowHeader)) {
      rowHeader = rowHeader(physicalRow);
    } else if (rowHeader && typeof rowHeader !== 'string' && typeof rowHeader !== 'number') {
      rowHeader = physicalRow + 1;
    }
    return rowHeader;
  };

  /**
   * Returns information about if this table is configured to display row headers.
   *
   * @memberof Core#
   * @function hasRowHeaders
   * @returns {boolean} `true` if the instance has the row headers enabled, `false` otherwise.
   */
  this.hasRowHeaders = function () {
    return !!tableMeta.rowHeaders;
  };

  /**
   * Returns information about if this table is configured to display column headers.
   *
   * @memberof Core#
   * @function hasColHeaders
   * @returns {boolean} `true` if the instance has the column headers enabled, `false` otherwise.
   */
  this.hasColHeaders = function () {
    if (tableMeta.colHeaders !== void 0 && tableMeta.colHeaders !== null) {
      // Polymer has empty value = null
      return !!tableMeta.colHeaders;
    }
    for (var i = 0, ilen = instance.countCols(); i < ilen; i++) {
      if (instance.getColHeader(i)) {
        return true;
      }
    }
    return false;
  };

  /**
   * Returns an array of column headers (in string format, if they are enabled). If param `column` is given, it
   * returns the header at the given column.
   *
   * @memberof Core#
   * @function getColHeader
   * @param {number} [column] Visual column index.
   * @fires Hooks#modifyColHeader
   * @returns {Array|string|number} The column header(s).
   */
  this.getColHeader = function (column) {
    var columnIndex = instance.runHooks('modifyColHeader', column);
    var result = tableMeta.colHeaders;
    if (columnIndex === void 0) {
      var out = [];
      var ilen = instance.countCols();
      for (var i = 0; i < ilen; i++) {
        out.push(instance.getColHeader(i));
      }
      result = out;
    } else {
      var translateVisualIndexToColumns = function translateVisualIndexToColumns(visualColumnIndex) {
        var arr = [];
        var columnsLen = instance.countCols();
        var index = 0;
        for (; index < columnsLen; index++) {
          if (isFunction(tableMeta.columns) && tableMeta.columns(index)) {
            arr.push(index);
          }
        }
        return arr[visualColumnIndex];
      };
      var physicalColumn = instance.toPhysicalColumn(columnIndex);
      var prop = translateVisualIndexToColumns(physicalColumn);
      if (tableMeta.colHeaders === false) {
        result = null;
      } else if (tableMeta.columns && isFunction(tableMeta.columns) && tableMeta.columns(prop) && tableMeta.columns(prop).title) {
        result = tableMeta.columns(prop).title;
      } else if (tableMeta.columns && tableMeta.columns[physicalColumn] && tableMeta.columns[physicalColumn].title) {
        result = tableMeta.columns[physicalColumn].title;
      } else if (Array.isArray(tableMeta.colHeaders) && tableMeta.colHeaders[physicalColumn] !== void 0) {
        result = tableMeta.colHeaders[physicalColumn];
      } else if (isFunction(tableMeta.colHeaders)) {
        result = tableMeta.colHeaders(physicalColumn);
      } else if (tableMeta.colHeaders && typeof tableMeta.colHeaders !== 'string' && typeof tableMeta.colHeaders !== 'number') {
        result = spreadsheetColumnLabel(columnIndex); // see #1458
      }
    }

    return result;
  };

  /**
   * Return column width from settings (no guessing). Private use intended.
   *
   * @private
   * @memberof Core#
   * @function _getColWidthFromSettings
   * @param {number} col Visual col index.
   * @returns {number}
   */
  this._getColWidthFromSettings = function (col) {
    var width;

    // We currently don't support cell meta objects for headers (negative values)
    if (col >= 0) {
      var cellProperties = instance.getCellMeta(0, col);
      width = cellProperties.width;
    }
    if (width === void 0 || width === tableMeta.width) {
      width = tableMeta.colWidths;
    }
    if (width !== void 0 && width !== null) {
      switch (_typeof(width)) {
        case 'object':
          // array
          width = width[col];
          break;
        case 'function':
          width = width(col);
          break;
        default:
          break;
      }
      if (typeof width === 'string') {
        width = parseInt(width, 10);
      }
    }
    return width;
  };

  /**
   * Returns the width of the requested column.
   *
   * @memberof Core#
   * @function getColWidth
   * @param {number} column Visual column index.
   * @returns {number} Column width.
   * @fires Hooks#modifyColWidth
   */
  this.getColWidth = function (column) {
    var width = instance._getColWidthFromSettings(column);
    width = instance.runHooks('modifyColWidth', width, column);
    if (width === void 0) {
      width = ViewportColumnsCalculator.DEFAULT_WIDTH;
    }
    return width;
  };

  /**
   * Return row height from settings (no guessing). Private use intended.
   *
   * @private
   * @memberof Core#
   * @function _getRowHeightFromSettings
   * @param {number} row Visual row index.
   * @returns {number}
   */
  this._getRowHeightFromSettings = function (row) {
    // let cellProperties = instance.getCellMeta(row, 0);
    // let height = cellProperties.height;
    //
    // if (height === void 0 || height === tableMeta.height) {
    //  height = cellProperties.rowHeights;
    // }
    var height = tableMeta.rowHeights;
    if (height !== void 0 && height !== null) {
      switch (_typeof(height)) {
        case 'object':
          // array
          height = height[row];
          break;
        case 'function':
          height = height(row);
          break;
        default:
          break;
      }
      if (typeof height === 'string') {
        height = parseInt(height, 10);
      }
    }
    return height;
  };

  /**
   * Returns the row height.
   *
   * Mind that this method is different from the [AutoRowSize](@/api/autoRowSize.md) plugin's [`getRowHeight()`](@/api/autoRowSize.md#getrowheight) method.
   *
   * @memberof Core#
   * @function getRowHeight
   * @param {number} row Visual row index.
   * @returns {number} The given row's height.
   * @fires Hooks#modifyRowHeight
   */
  this.getRowHeight = function (row) {
    var height = instance._getRowHeightFromSettings(row);
    height = instance.runHooks('modifyRowHeight', height, row);
    return height;
  };

  /**
   * Returns the total number of rows in the data source.
   *
   * @memberof Core#
   * @function countSourceRows
   * @returns {number} Total number of rows.
   */
  this.countSourceRows = function () {
    return dataSource.countRows();
  };

  /**
   * Returns the total number of columns in the data source.
   *
   * @memberof Core#
   * @function countSourceCols
   * @returns {number} Total number of columns.
   */
  this.countSourceCols = function () {
    return dataSource.countFirstRowKeys();
  };

  /**
   * Returns the total number of visual rows in the table.
   *
   * @memberof Core#
   * @function countRows
   * @returns {number} Total number of rows.
   */
  this.countRows = function () {
    return datamap.getLength();
  };

  /**
   * Returns the total number of visible columns in the table.
   *
   * @memberof Core#
   * @function countCols
   * @returns {number} Total number of columns.
   */
  this.countCols = function () {
    var maxCols = tableMeta.maxCols;
    var dataLen = this.columnIndexMapper.getNotTrimmedIndexesLength();
    return Math.min(maxCols, dataLen);
  };

  /**
   * Returns the number of rendered rows including rows that are partially or fully rendered
   * outside the table viewport.
   *
   * @memberof Core#
   * @function countRenderedRows
   * @returns {number} Returns -1 if table is not visible.
   */
  this.countRenderedRows = function () {
    return instance.view._wt.drawn ? instance.view._wt.wtTable.getRenderedRowsCount() : -1;
  };

  /**
   * Returns the number of rendered rows that are only visible in the table viewport.
   * The rows that are partially visible are not counted.
   *
   * @memberof Core#
   * @function countVisibleRows
   * @returns {number} Number of visible rows or -1.
   */
  this.countVisibleRows = function () {
    return instance.view._wt.drawn ? instance.view._wt.wtTable.getVisibleRowsCount() : -1;
  };

  /**
   * Returns the number of rendered rows including columns that are partially or fully rendered
   * outside the table viewport.
   *
   * @memberof Core#
   * @function countRenderedCols
   * @returns {number} Returns -1 if table is not visible.
   */
  this.countRenderedCols = function () {
    return instance.view._wt.drawn ? instance.view._wt.wtTable.getRenderedColumnsCount() : -1;
  };

  /**
   * Returns the number of rendered columns that are only visible in the table viewport.
   * The columns that are partially visible are not counted.
   *
   * @memberof Core#
   * @function countVisibleCols
   * @returns {number} Number of visible columns or -1.
   */
  this.countVisibleCols = function () {
    return instance.view._wt.drawn ? instance.view._wt.wtTable.getVisibleColumnsCount() : -1;
  };

  /**
   * Returns the number of empty rows. If the optional ending parameter is `true`, returns the
   * number of empty rows at the bottom of the table.
   *
   * @memberof Core#
   * @function countEmptyRows
   * @param {boolean} [ending=false] If `true`, will only count empty rows at the end of the data source.
   * @returns {number} Count empty rows.
   */
  this.countEmptyRows = function () {
    var ending = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
    var emptyRows = 0;
    rangeEachReverse(instance.countRows() - 1, function (visualIndex) {
      if (instance.isEmptyRow(visualIndex)) {
        emptyRows += 1;
      } else if (ending === true) {
        return false;
      }
    });
    return emptyRows;
  };

  /**
   * Returns the number of empty columns. If the optional ending parameter is `true`, returns the number of empty
   * columns at right hand edge of the table.
   *
   * @memberof Core#
   * @function countEmptyCols
   * @param {boolean} [ending=false] If `true`, will only count empty columns at the end of the data source row.
   * @returns {number} Count empty cols.
   */
  this.countEmptyCols = function () {
    var ending = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
    var emptyColumns = 0;
    rangeEachReverse(instance.countCols() - 1, function (visualIndex) {
      if (instance.isEmptyCol(visualIndex)) {
        emptyColumns += 1;
      } else if (ending === true) {
        return false;
      }
    });
    return emptyColumns;
  };

  /**
   * Check if all cells in the row declared by the `row` argument are empty.
   *
   * @memberof Core#
   * @function isEmptyRow
   * @param {number} row Visual row index.
   * @returns {boolean} `true` if the row at the given `row` is empty, `false` otherwise.
   */
  this.isEmptyRow = function (row) {
    return tableMeta.isEmptyRow.call(instance, row);
  };

  /**
   * Check if all cells in the the column declared by the `column` argument are empty.
   *
   * @memberof Core#
   * @function isEmptyCol
   * @param {number} column Column index.
   * @returns {boolean} `true` if the column at the given `col` is empty, `false` otherwise.
   */
  this.isEmptyCol = function (column) {
    return tableMeta.isEmptyCol.call(instance, column);
  };

  /**
   * Select cell specified by `row` and `column` values or a range of cells finishing at `endRow`, `endCol`. If the table
   * was configured to support data column properties that properties can be used to making a selection.
   *
   * By default, viewport will be scrolled to the selection. After the `selectCell` method had finished, the instance
   * will be listening to keyboard input on the document.
   *
   * @example
   * ```js
   * // select a single cell
   * hot.selectCell(2, 4);
   * // select a single cell using column property
   * hot.selectCell(2, 'address');
   * // select a range of cells
   * hot.selectCell(2, 4, 3, 5);
   * // select a range of cells using column properties
   * hot.selectCell(2, 'address', 3, 'phone_number');
   * // select a range of cells without scrolling to them
   * hot.selectCell(2, 'address', 3, 'phone_number', false);
   * ```
   *
   * @memberof Core#
   * @function selectCell
   * @param {number} row Visual row index.
   * @param {number|string} column Visual column index or column property.
   * @param {number} [endRow] Visual end row index (if selecting a range).
   * @param {number|string} [endColumn] Visual end column index or column property (if selecting a range).
   * @param {boolean} [scrollToCell=true] If `true`, the viewport will be scrolled to the selection.
   * @param {boolean} [changeListener=true] If `false`, Handsontable will not change keyboard events listener to himself.
   * @returns {boolean} `true` if selection was successful, `false` otherwise.
   */
  this.selectCell = function (row, column, endRow, endColumn) {
    var scrollToCell = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;
    var changeListener = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : true;
    if (isUndefined(row) || isUndefined(column)) {
      return false;
    }
    return this.selectCells([[row, column, endRow, endColumn]], scrollToCell, changeListener);
  };

  /**
   * Make multiple, non-contiguous selection specified by `row` and `column` values or a range of cells
   * finishing at `endRow`, `endColumn`. The method supports two input formats which are the same as that
   * produces by `getSelected` and `getSelectedRange` methods.
   *
   * By default, viewport will be scrolled to selection. After the `selectCells` method had finished, the instance
   * will be listening to keyboard input on the document.
   *
   * @example
   * ```js
   * // Using an array of arrays.
   * hot.selectCells([[1, 1, 2, 2], [3, 3], [6, 2, 0, 2]]);
   * // Using an array of arrays with defined columns as props.
   * hot.selectCells([[1, 'id', 2, 'first_name'], [3, 'full_name'], [6, 'last_name', 0, 'first_name']]);
   * // Using an array of CellRange objects (produced by `.getSelectedRange()` method).
   * const selected = hot.getSelectedRange();
   *
   * selected[0].from.row = 0;
   * selected[0].from.col = 0;
   *
   * hot.selectCells(selected);
   * ```
   *
   * @memberof Core#
   * @since 0.38.0
   * @function selectCells
   * @param {Array[]|CellRange[]} coords Visual coords passed as an array of array (`[[rowStart, columnStart, rowEnd, columnEnd], ...]`)
   *                                     the same format as `getSelected` method returns or as an CellRange objects
   *                                     which is the same format what `getSelectedRange` method returns.
   * @param {boolean} [scrollToCell=true] If `true`, the viewport will be scrolled to the selection.
   * @param {boolean} [changeListener=true] If `false`, Handsontable will not change keyboard events listener to himself.
   * @returns {boolean} `true` if selection was successful, `false` otherwise.
   */
  this.selectCells = function () {
    var coords = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [[]];
    var scrollToCell = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    var changeListener = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
    if (scrollToCell === false) {
      preventScrollingToCell = true;
    }
    var wasSelected = selection.selectCells(coords);
    if (wasSelected && changeListener) {
      instance.listen();
    }
    preventScrollingToCell = false;
    return wasSelected;
  };

  /**
   * Select column specified by `startColumn` visual index, column property or a range of columns finishing at `endColumn`.
   *
   * @example
   * ```js
   * // Select column using visual index.
   * hot.selectColumns(1);
   * // Select column using column property.
   * hot.selectColumns('id');
   * // Select range of columns using visual indexes.
   * hot.selectColumns(1, 4);
   * // Select range of columns using column properties.
   * hot.selectColumns('id', 'last_name');
   * ```
   *
   * @memberof Core#
   * @since 0.38.0
   * @function selectColumns
   * @param {number} startColumn The visual column index from which the selection starts.
   * @param {number} [endColumn=startColumn] The visual column index to which the selection finishes. If `endColumn`
   *                                         is not defined the column defined by `startColumn` will be selected.
   * @returns {boolean} `true` if selection was successful, `false` otherwise.
   */
  this.selectColumns = function (startColumn) {
    var endColumn = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : startColumn;
    return selection.selectColumns(startColumn, endColumn);
  };

  /**
   * Select row specified by `startRow` visual index or a range of rows finishing at `endRow`.
   *
   * @example
   * ```js
   * // Select row using visual index.
   * hot.selectRows(1);
   * // Select range of rows using visual indexes.
   * hot.selectRows(1, 4);
   * ```
   *
   * @memberof Core#
   * @since 0.38.0
   * @function selectRows
   * @param {number} startRow The visual row index from which the selection starts.
   * @param {number} [endRow=startRow] The visual row index to which the selection finishes. If `endRow`
   *                                   is not defined the row defined by `startRow` will be selected.
   * @returns {boolean} `true` if selection was successful, `false` otherwise.
   */
  this.selectRows = function (startRow) {
    var endRow = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : startRow;
    return selection.selectRows(startRow, endRow);
  };

  /**
   * Deselects the current cell selection on the table.
   *
   * @memberof Core#
   * @function deselectCell
   */
  this.deselectCell = function () {
    selection.deselect();
  };

  /**
   * Select the whole table. The previous selection will be overwritten.
   *
   * @since 0.38.2
   * @memberof Core#
   * @function selectAll
   * @param {boolean} [includeHeaders=true] `true` If the selection should include the row, column and corner headers,
   * `false` otherwise.
   */
  this.selectAll = function () {
    var includeHeaders = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
    var includeRowHeaders = includeHeaders && this.hasRowHeaders();
    var includeColumnHeaders = includeHeaders && this.hasColHeaders();
    preventScrollingToCell = true;
    selection.selectAll(includeRowHeaders, includeColumnHeaders);
    preventScrollingToCell = false;
  };
  var getIndexToScroll = function getIndexToScroll(indexMapper, visualIndex) {
    // Looking for a visual index on the right and then (when not found) on the left.
    return indexMapper.getNearestNotHiddenIndex(visualIndex, 1, true);
  };

  /**
   * Scroll viewport to coordinates specified by the `row` and `column` arguments.
   *
   * @memberof Core#
   * @function scrollViewportTo
   * @param {number} [row] Row index. If the last argument isn't defined we treat the index as a visual row index. Otherwise,
   * we are using the index for numbering only this rows which may be rendered (we don't consider hidden rows).
   * @param {number} [column] Column index. If the last argument isn't defined we treat the index as a visual column index.
   * Otherwise, we are using the index for numbering only this columns which may be rendered (we don't consider hidden columns).
   * @param {boolean} [snapToBottom=false] If `true`, the viewport is scrolled to show the cell at the bottom of the table.
   * However, if the cell's height is greater than the table's viewport height, the cell is snapped to the top edge.
   * @param {boolean} [snapToRight=false] If `true`, the viewport is scrolled to show the cell at the right side of the table.
   * However, if the cell is wider than the table's viewport width, the cell is snapped to the left edge (or to the right edge, if the layout direction is set to `rtl`).
   * @param {boolean} [considerHiddenIndexes=true] If `true`, we handle visual indexes, otherwise we handle only indexes which
   * may be rendered when they are in the viewport (we don't consider hidden indexes as they aren't rendered).
   * @returns {boolean} `true` if scroll was successful, `false` otherwise.
   */
  this.scrollViewportTo = function (row, column) {
    var snapToBottom = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    var snapToRight = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
    var considerHiddenIndexes = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;
    var snapToTop = !snapToBottom;
    var snapToLeft = !snapToRight;
    var renderableRow = row;
    var renderableColumn = column;
    if (considerHiddenIndexes) {
      var _isRowInteger = Number.isInteger(row);
      var _isColumnInteger = Number.isInteger(column);
      var visualRowToScroll = _isRowInteger ? getIndexToScroll(this.rowIndexMapper, row) : void 0;
      var visualColumnToScroll = _isColumnInteger ? getIndexToScroll(this.columnIndexMapper, column) : void 0;
      if (visualRowToScroll === null || visualColumnToScroll === null) {
        return false;
      }
      renderableRow = _isRowInteger ? instance.rowIndexMapper.getRenderableFromVisualIndex(visualRowToScroll) : void 0;
      renderableColumn = _isColumnInteger ? instance.columnIndexMapper.getRenderableFromVisualIndex(visualColumnToScroll) : void 0;
    }
    var isRowInteger = Number.isInteger(renderableRow);
    var isColumnInteger = Number.isInteger(renderableColumn);
    if (isRowInteger && isColumnInteger) {
      return instance.view.scrollViewport(instance._createCellCoords(renderableRow, renderableColumn), snapToTop, snapToRight, snapToBottom, snapToLeft);
    }
    if (isRowInteger && isColumnInteger === false) {
      return instance.view.scrollViewportVertically(renderableRow, snapToTop, snapToBottom);
    }
    if (isColumnInteger && isRowInteger === false) {
      return instance.view.scrollViewportHorizontally(renderableColumn, snapToRight, snapToLeft);
    }
    return false;
  };

  /**
   * Removes the table from the DOM and destroys the instance of the Handsontable.
   *
   * @memberof Core#
   * @function destroy
   * @fires Hooks#afterDestroy
   */
  this.destroy = function () {
    instance._clearTimeouts();
    instance._clearImmediates();
    if (instance.view) {
      // in case HT is destroyed before initialization has finished
      instance.view.destroy();
    }
    if (dataSource) {
      dataSource.destroy();
    }
    dataSource = null;
    this.getShortcutManager().destroy();
    metaManager.clearCache();
    if (isRootInstance(instance)) {
      var licenseInfo = this.rootDocument.querySelector('#hot-display-license-info');
      if (licenseInfo) {
        licenseInfo.parentNode.removeChild(licenseInfo);
      }
    }
    empty(instance.rootElement);
    eventManager.destroy();
    if (editorManager) {
      editorManager.destroy();
    }

    // The plugin's `destroy` method is called as a consequence and it should handle
    // unregistration of plugin's maps. Some unregistered maps reset the cache.
    instance.batchExecution(function () {
      instance.rowIndexMapper.unregisterAll();
      instance.columnIndexMapper.unregisterAll();
      pluginsRegistry.getItems().forEach(function (_ref20) {
        var _ref21 = _slicedToArray(_ref20, 2),
          plugin = _ref21[1];
        plugin.destroy();
      });
      pluginsRegistry.clear();
      instance.runHooks('afterDestroy');
    }, true);
    Hooks.getSingleton().destroy(instance);
    objectEach(instance, function (property, key, obj) {
      // replace instance methods with post mortem
      if (isFunction(property)) {
        obj[key] = postMortem(key);
      } else if (key !== 'guid') {
        // replace instance properties with null (restores memory)
        // it should not be necessary but this prevents a memory leak side effects that show itself in Jasmine tests
        obj[key] = null;
      }
    });
    instance.isDestroyed = true;

    // replace private properties with null (restores memory)
    // it should not be necessary but this prevents a memory leak side effects that show itself in Jasmine tests
    if (datamap) {
      datamap.destroy();
    }
    instance.rowIndexMapper = null;
    instance.columnIndexMapper = null;
    datamap = null;
    grid = null;
    selection = null;
    editorManager = null;
    instance = null;
  };

  /**
   * Replacement for all methods after the Handsontable was destroyed.
   *
   * @private
   * @param {string} method The method name.
   * @returns {Function}
   */
  function postMortem(method) {
    return function () {
      throw new Error("The \"".concat(method, "\" method cannot be called because this Handsontable instance has been destroyed"));
    };
  }

  /**
   * Returns the active editor class instance.
   *
   * @memberof Core#
   * @function getActiveEditor
   * @returns {BaseEditor} The active editor instance.
   */
  this.getActiveEditor = function () {
    return editorManager.getActiveEditor();
  };

  /**
   * Returns plugin instance by provided its name.
   *
   * @memberof Core#
   * @function getPlugin
   * @param {string} pluginName The plugin name.
   * @returns {BasePlugin|undefined} The plugin instance or undefined if there is no plugin.
   */
  this.getPlugin = function (pluginName) {
    var unifiedPluginName = toUpperCaseFirst(pluginName);

    // Workaround for the UndoRedo plugin which, currently doesn't follow the plugin architecture.
    if (unifiedPluginName === 'UndoRedo') {
      return this.undoRedo;
    }
    return pluginsRegistry.getItem(unifiedPluginName);
  };

  /**
   * Returns name of the passed plugin.
   *
   * @private
   * @memberof Core#
   * @param {BasePlugin} plugin The plugin instance.
   * @returns {string}
   */
  this.getPluginName = function (plugin) {
    // Workaround for the UndoRedo plugin which, currently doesn't follow the plugin architecture.
    if (plugin === this.undoRedo) {
      return this.undoRedo.constructor.PLUGIN_KEY;
    }
    return pluginsRegistry.getId(plugin);
  };

  /**
   * Returns the Handsontable instance.
   *
   * @memberof Core#
   * @function getInstance
   * @returns {Handsontable} The Handsontable instance.
   */
  this.getInstance = function () {
    return instance;
  };

  /**
   * Adds listener to the specified hook name (only for this Handsontable instance).
   *
   * @memberof Core#
   * @function addHook
   * @see Hooks#add
   * @param {string} key Hook name (see {@link Hooks}).
   * @param {Function|Array} callback Function or array of functions.
   * @example
   * ```js
   * hot.addHook('beforeInit', myCallback);
   * ```
   */
  this.addHook = function (key, callback) {
    Hooks.getSingleton().add(key, callback, instance);
  };

  /**
   * Check if for a specified hook name there are added listeners (only for this Handsontable instance). All available
   * hooks you will find {@link Hooks}.
   *
   * @memberof Core#
   * @function hasHook
   * @see Hooks#has
   * @param {string} key Hook name.
   * @returns {boolean}
   *
   * @example
   * ```js
   * const hasBeforeInitListeners = hot.hasHook('beforeInit');
   * ```
   */
  this.hasHook = function (key) {
    return Hooks.getSingleton().has(key, instance) || Hooks.getSingleton().has(key);
  };

  /**
   * Adds listener to specified hook name (only for this Handsontable instance). After the listener is triggered,
   * it will be automatically removed.
   *
   * @memberof Core#
   * @function addHookOnce
   * @see Hooks#once
   * @param {string} key Hook name (see {@link Hooks}).
   * @param {Function|Array} callback Function or array of functions.
   * @example
   * ```js
   * hot.addHookOnce('beforeInit', myCallback);
   * ```
   */
  this.addHookOnce = function (key, callback) {
    Hooks.getSingleton().once(key, callback, instance);
  };

  /**
   * Removes the hook listener previously registered with {@link Core#addHook}.
   *
   * @memberof Core#
   * @function removeHook
   * @see Hooks#remove
   * @param {string} key Hook name.
   * @param {Function} callback Reference to the function which has been registered using {@link Core#addHook}.
   *
   * @example
   * ```js
   * hot.removeHook('beforeInit', myCallback);
   * ```
   */
  this.removeHook = function (key, callback) {
    Hooks.getSingleton().remove(key, callback, instance);
  };

  /**
   * Run the callbacks for the hook provided in the `key` argument using the parameters given in the other arguments.
   *
   * @memberof Core#
   * @function runHooks
   * @see Hooks#run
   * @param {string} key Hook name.
   * @param {*} [p1] Argument passed to the callback.
   * @param {*} [p2] Argument passed to the callback.
   * @param {*} [p3] Argument passed to the callback.
   * @param {*} [p4] Argument passed to the callback.
   * @param {*} [p5] Argument passed to the callback.
   * @param {*} [p6] Argument passed to the callback.
   * @returns {*}
   *
   * @example
   * ```js
   * // Run built-in hook
   * hot.runHooks('beforeInit');
   * // Run custom hook
   * hot.runHooks('customAction', 10, 'foo');
   * ```
   */
  this.runHooks = function (key, p1, p2, p3, p4, p5, p6) {
    return Hooks.getSingleton().run(instance, key, p1, p2, p3, p4, p5, p6);
  };

  /**
   * Get language phrase for specified dictionary key.
   *
   * @memberof Core#
   * @function getTranslatedPhrase
   * @since 0.35.0
   * @param {string} dictionaryKey Constant which is dictionary key.
   * @param {*} extraArguments Arguments which will be handled by formatters.
   * @returns {string}
   */
  this.getTranslatedPhrase = function (dictionaryKey, extraArguments) {
    return getTranslatedPhrase(tableMeta.language, dictionaryKey, extraArguments);
  };

  /**
   * Converts instance into outerHTML of HTMLTableElement.
   *
   * @memberof Core#
   * @function toHTML
   * @since 7.1.0
   * @returns {string}
   */
  this.toHTML = function () {
    return instanceToHTML(_this);
  };

  /**
   * Converts instance into HTMLTableElement.
   *
   * @memberof Core#
   * @function toTableElement
   * @since 7.1.0
   * @returns {HTMLTableElement}
   */
  this.toTableElement = function () {
    var tempElement = _this.rootDocument.createElement('div');
    tempElement.insertAdjacentHTML('afterbegin', instanceToHTML(_this));
    return tempElement.firstElementChild;
  };
  this.timeouts = [];

  /**
   * Sets timeout. Purpose of this method is to clear all known timeouts when `destroy` method is called.
   *
   * @param {number|Function} handle Handler returned from setTimeout or function to execute (it will be automatically wraped
   *                                 by setTimeout function).
   * @param {number} [delay=0] If first argument is passed as a function this argument set delay of the execution of that function.
   * @private
   */
  this._registerTimeout = function (handle) {
    var delay = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
    var handleFunc = handle;
    if (typeof handleFunc === 'function') {
      handleFunc = setTimeout(handleFunc, delay);
    }
    this.timeouts.push(handleFunc);
  };

  /**
   * Clears all known timeouts.
   *
   * @private
   */
  this._clearTimeouts = function () {
    arrayEach(this.timeouts, function (handler) {
      clearTimeout(handler);
    });
  };
  this.immediates = [];

  /**
   * Execute function execution to the next event loop cycle. Purpose of this method is to clear all known timeouts when `destroy` method is called.
   *
   * @param {Function} callback Function to be delayed in execution.
   * @private
   */
  this._registerImmediate = function (callback) {
    this.immediates.push(setImmediate(callback));
  };

  /**
   * Clears all known timeouts.
   *
   * @private
   */
  this._clearImmediates = function () {
    arrayEach(this.immediates, function (handler) {
      clearImmediate(handler);
    });
  };

  /**
   * Refresh selection borders. This is temporary method relic after selection rewrite.
   *
   * @private
   * @param {boolean} [revertOriginal=false] If `true`, the previous value will be restored. Otherwise, the edited value will be saved.
   * @param {boolean} [prepareEditorIfNeeded=true] If `true` the editor under the selected cell will be prepared to open.
   */
  this._refreshBorders = function () {
    var revertOriginal = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
    var prepareEditorIfNeeded = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    editorManager.destroyEditor(revertOriginal);
    instance.view.render();
    if (prepareEditorIfNeeded && selection.isSelected()) {
      editorManager.prepareEditor();
    }
  };

  /**
   * Check if currently it is RTL direction.
   *
   * @private
   * @memberof Core#
   * @function isRtl
   * @returns {boolean} True if RTL.
   */
  this.isRtl = function () {
    return instance.rootWindow.getComputedStyle(instance.rootElement).direction === 'rtl';
  };

  /**
   * Check if currently it is LTR direction.
   *
   * @private
   * @memberof Core#
   * @function isLtr
   * @returns {boolean} True if LTR.
   */
  this.isLtr = function () {
    return !instance.isRtl();
  };

  /**
   * Returns 1 for LTR; -1 for RTL. Useful for calculations.
   *
   * @private
   * @memberof Core#
   * @function getDirectionFactor
   * @returns {number} Returns 1 for LTR; -1 for RTL.
   */
  this.getDirectionFactor = function () {
    return instance.isLtr() ? 1 : -1;
  };
  var shortcutManager = createShortcutManager({
    handleEvent: function handleEvent(event) {
      var isListening = instance.isListening();
      var isKeyboardEventWithKey = (event === null || event === void 0 ? void 0 : event.key) !== void 0;
      return isListening && isKeyboardEventWithKey;
    },
    beforeKeyDown: function beforeKeyDown(event) {
      return _this.runHooks('beforeKeyDown', event);
    },
    afterKeyDown: function afterKeyDown(event) {
      if (_this.isDestroyed) {
        // Handsontable could be destroyed after performing action (executing a callback).
        return;
      }
      instance.runHooks('afterDocumentKeyDown', event);
    },
    ownerWindow: this.rootWindow
  });

  /**
   * Returns instance of a manager responsible for handling shortcuts stored in some contexts. It run actions after
   * pressing key combination in active Handsontable instance.
   *
   * @memberof Core#
   * @since 12.0.0
   * @function getShortcutManager
   * @returns {ShortcutManager} Instance of {@link ShortcutManager}
   */
  this.getShortcutManager = function () {
    return shortcutManager;
  };
  var gridContext = shortcutManager.addContext('grid');
  var gridConfig = {
    runOnlyIf: function runOnlyIf() {
      return isDefined(instance.getSelected()) && instance.countRenderedRows() > 0 && instance.countRenderedCols() > 0;
    },
    group: SHORTCUTS_GROUP
  };
  shortcutManager.setActiveContextName('grid');
  gridContext.addShortcuts([{
    keys: [['Control/Meta', 'A']],
    callback: function callback() {
      instance.selectAll();
    }
  }, {
    keys: [['Control/Meta', 'Enter']],
    callback: function callback() {
      var selectedRange = instance.getSelectedRange();
      var _selectedRange$highli = selectedRange[selectedRange.length - 1].highlight,
        highlightRow = _selectedRange$highli.row,
        highlightColumn = _selectedRange$highli.col;
      var valueToPopulate = instance.getDataAtCell(highlightRow, highlightColumn);
      var cellValues = new Map();
      for (var i = 0; i < selectedRange.length; i++) {
        selectedRange[i].forAll(function (row, column) {
          if (row >= 0 && column >= 0 && (row !== highlightRow || column !== highlightColumn)) {
            var _instance$getCellMeta = instance.getCellMeta(row, column),
              readOnly = _instance$getCellMeta.readOnly;
            if (!readOnly) {
              cellValues.set("".concat(row, "x").concat(column), [row, column, valueToPopulate]);
            }
          }
        });
      }
      instance.setDataAtCell(Array.from(cellValues.values()));
    },
    runOnlyIf: function runOnlyIf() {
      return instance.getSelectedRangeLast().getCellsCount() > 1;
    }
  }, {
    keys: [['ArrowUp']],
    callback: function callback() {
      selection.transformStart(-1, 0);
    }
  }, {
    keys: [['ArrowUp', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      selection.setRangeStart(instance._createCellCoords(instance.rowIndexMapper.getNearestNotHiddenIndex(0, 1), instance.getSelectedRangeLast().highlight.col));
    }
  }, {
    keys: [['ArrowUp', 'Shift']],
    callback: function callback() {
      selection.transformEnd(-1, 0);
    }
  }, {
    keys: [['ArrowUp', 'Shift', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var _instance$getSelected = instance.getSelectedRangeLast(),
        from = _instance$getSelected.from,
        to = _instance$getSelected.to;
      var row = instance.rowIndexMapper.getNearestNotHiddenIndex(0, 1);
      selection.setRangeStart(from.clone());
      selection.setRangeEnd(instance._createCellCoords(row, to.col));
    },
    runOnlyIf: function runOnlyIf() {
      return !(instance.selection.isSelectedByCorner() || instance.selection.isSelectedByColumnHeader());
    }
  }, {
    keys: [['ArrowDown']],
    callback: function callback() {
      selection.transformStart(1, 0);
    }
  }, {
    keys: [['ArrowDown', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      selection.setRangeStart(instance._createCellCoords(instance.rowIndexMapper.getNearestNotHiddenIndex(instance.countRows() - 1, -1), instance.getSelectedRangeLast().highlight.col));
    }
  }, {
    keys: [['ArrowDown', 'Shift']],
    callback: function callback() {
      selection.transformEnd(1, 0);
    }
  }, {
    keys: [['ArrowDown', 'Shift', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var _instance$getSelected2 = instance.getSelectedRangeLast(),
        from = _instance$getSelected2.from,
        to = _instance$getSelected2.to;
      var row = instance.rowIndexMapper.getNearestNotHiddenIndex(instance.countRows() - 1, -1);
      selection.setRangeStart(from.clone());
      selection.setRangeEnd(instance._createCellCoords(row, to.col));
    },
    runOnlyIf: function runOnlyIf() {
      return !(instance.selection.isSelectedByCorner() || instance.selection.isSelectedByColumnHeader());
    }
  }, {
    keys: [['ArrowLeft']],
    callback: function callback() {
      selection.transformStart(0, -1 * instance.getDirectionFactor());
    }
  }, {
    keys: [['ArrowLeft', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var _instance$columnIndex;
      var row = instance.getSelectedRangeLast().highlight.row;
      var column = (_instance$columnIndex = instance.columnIndexMapper).getNearestNotHiddenIndex.apply(_instance$columnIndex, _toConsumableArray(instance.isRtl() ? [instance.countCols() - 1, -1] : [0, 1]));
      selection.setRangeStart(instance._createCellCoords(row, column));
    }
  }, {
    keys: [['ArrowLeft', 'Shift']],
    callback: function callback() {
      selection.transformEnd(0, -1 * instance.getDirectionFactor());
    }
  }, {
    keys: [['ArrowLeft', 'Shift', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var _instance$columnIndex2;
      var _instance$getSelected3 = instance.getSelectedRangeLast(),
        from = _instance$getSelected3.from,
        to = _instance$getSelected3.to;
      var column = (_instance$columnIndex2 = instance.columnIndexMapper).getNearestNotHiddenIndex.apply(_instance$columnIndex2, _toConsumableArray(instance.isRtl() ? [instance.countCols() - 1, -1] : [0, 1]));
      selection.setRangeStart(from.clone());
      selection.setRangeEnd(instance._createCellCoords(to.row, column));
    },
    runOnlyIf: function runOnlyIf() {
      return !(instance.selection.isSelectedByCorner() || instance.selection.isSelectedByRowHeader());
    }
  }, {
    keys: [['ArrowRight']],
    callback: function callback() {
      selection.transformStart(0, instance.getDirectionFactor());
    }
  }, {
    keys: [['ArrowRight', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var _instance$columnIndex3;
      var row = instance.getSelectedRangeLast().highlight.row;
      var column = (_instance$columnIndex3 = instance.columnIndexMapper).getNearestNotHiddenIndex.apply(_instance$columnIndex3, _toConsumableArray(instance.isRtl() ? [0, 1] : [instance.countCols() - 1, -1]));
      selection.setRangeStart(instance._createCellCoords(row, column));
    }
  }, {
    keys: [['ArrowRight', 'Shift']],
    callback: function callback() {
      selection.transformEnd(0, instance.getDirectionFactor());
    }
  }, {
    keys: [['ArrowRight', 'Shift', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var _instance$columnIndex4;
      var _instance$getSelected4 = instance.getSelectedRangeLast(),
        from = _instance$getSelected4.from,
        to = _instance$getSelected4.to;
      var column = (_instance$columnIndex4 = instance.columnIndexMapper).getNearestNotHiddenIndex.apply(_instance$columnIndex4, _toConsumableArray(instance.isRtl() ? [0, 1] : [instance.countCols() - 1, -1]));
      selection.setRangeStart(from.clone());
      selection.setRangeEnd(instance._createCellCoords(to.row, column));
    },
    runOnlyIf: function runOnlyIf() {
      return !(instance.selection.isSelectedByCorner() || instance.selection.isSelectedByRowHeader());
    }
  }, {
    keys: [['Home']],
    captureCtrl: true,
    callback: function callback() {
      var fixedColumns = parseInt(instance.getSettings().fixedColumnsStart, 10);
      var row = instance.getSelectedRangeLast().highlight.row;
      var column = instance.columnIndexMapper.getNearestNotHiddenIndex(fixedColumns, 1);
      selection.setRangeStart(instance._createCellCoords(row, column));
    },
    runOnlyIf: function runOnlyIf() {
      return instance.view.isMainTableNotFullyCoveredByOverlays();
    }
  }, {
    keys: [['Home', 'Shift']],
    callback: function callback() {
      selection.setRangeEnd(instance._createCellCoords(selection.selectedRange.current().from.row, instance.columnIndexMapper.getNearestNotHiddenIndex(0, 1)));
    }
  }, {
    keys: [['Home', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var fixedRows = parseInt(instance.getSettings().fixedRowsTop, 10);
      var fixedColumns = parseInt(instance.getSettings().fixedColumnsStart, 10);
      var row = instance.rowIndexMapper.getNearestNotHiddenIndex(fixedRows, 1);
      var column = instance.columnIndexMapper.getNearestNotHiddenIndex(fixedColumns, 1);
      selection.setRangeStart(instance._createCellCoords(row, column));
    },
    runOnlyIf: function runOnlyIf() {
      return instance.view.isMainTableNotFullyCoveredByOverlays();
    }
  }, {
    keys: [['End']],
    captureCtrl: true,
    callback: function callback() {
      selection.setRangeStart(instance._createCellCoords(instance.getSelectedRangeLast().highlight.row, instance.columnIndexMapper.getNearestNotHiddenIndex(instance.countCols() - 1, -1)));
    },
    runOnlyIf: function runOnlyIf() {
      return instance.view.isMainTableNotFullyCoveredByOverlays();
    }
  }, {
    keys: [['End', 'Shift']],
    callback: function callback() {
      selection.setRangeEnd(instance._createCellCoords(selection.selectedRange.current().from.row, instance.columnIndexMapper.getNearestNotHiddenIndex(instance.countCols() - 1, -1)));
    }
  }, {
    keys: [['End', 'Control/Meta']],
    captureCtrl: true,
    callback: function callback() {
      var fixedRows = parseInt(instance.getSettings().fixedRowsBottom, 10);
      var row = instance.rowIndexMapper.getNearestNotHiddenIndex(instance.countRows() - fixedRows - 1, -1);
      var column = instance.columnIndexMapper.getNearestNotHiddenIndex(instance.countCols() - 1, -1);
      selection.setRangeStart(instance._createCellCoords(row, column));
    },
    runOnlyIf: function runOnlyIf() {
      return instance.view.isMainTableNotFullyCoveredByOverlays();
    }
  }, {
    keys: [['PageUp']],
    callback: function callback() {
      selection.transformStart(-instance.countVisibleRows(), 0);
    }
  }, {
    keys: [['PageUp', 'Shift']],
    callback: function callback() {
      var _instance$getSelected5 = instance.getSelectedRangeLast(),
        to = _instance$getSelected5.to;
      var nextRowIndexToSelect = Math.max(to.row - instance.countVisibleRows(), 0);
      var row = instance.rowIndexMapper.getNearestNotHiddenIndex(nextRowIndexToSelect, 1);
      if (row !== null) {
        var coords = instance._createCellCoords(row, to.col);
        var scrollPadding = to.row - instance.view.getFirstFullyVisibleRow();
        var nextVerticalScroll = Math.max(coords.row - scrollPadding, 0);
        selection.setRangeEnd(coords);
        instance.scrollViewportTo(nextVerticalScroll);
      }
    }
  }, {
    keys: [['PageDown']],
    callback: function callback() {
      selection.transformStart(instance.countVisibleRows(), 0);
    }
  }, {
    keys: [['PageDown', 'Shift']],
    callback: function callback() {
      var _instance$getSelected6 = instance.getSelectedRangeLast(),
        to = _instance$getSelected6.to;
      var nextRowIndexToSelect = Math.min(to.row + instance.countVisibleRows(), instance.countRows() - 1);
      var row = instance.rowIndexMapper.getNearestNotHiddenIndex(nextRowIndexToSelect, -1);
      if (row !== null) {
        var coords = instance._createCellCoords(row, to.col);
        var scrollPadding = to.row - instance.view.getFirstFullyVisibleRow();
        var nextVerticalScroll = Math.min(coords.row - scrollPadding, instance.countRows() - 1);
        selection.setRangeEnd(coords);
        instance.scrollViewportTo(nextVerticalScroll);
      }
    }
  }, {
    keys: [['Tab']],
    callback: function callback(event) {
      var tabMoves = typeof tableMeta.tabMoves === 'function' ? tableMeta.tabMoves(event) : tableMeta.tabMoves;
      selection.transformStart(tabMoves.row, tabMoves.col, true);
    }
  }, {
    keys: [['Shift', 'Tab']],
    callback: function callback(event) {
      var tabMoves = typeof tableMeta.tabMoves === 'function' ? tableMeta.tabMoves(event) : tableMeta.tabMoves;
      selection.transformStart(-tabMoves.row, -tabMoves.col);
    }
  }], gridConfig);
  getPluginsNames().forEach(function (pluginName) {
    var PluginClass = getPlugin(pluginName);
    pluginsRegistry.addItem(pluginName, new PluginClass(_this));
  });
  Hooks.getSingleton().run(instance, 'construct');
}