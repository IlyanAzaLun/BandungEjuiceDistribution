"use strict";

exports.__esModule = true;
exports.getPluginsNames = exports.getPlugin = void 0;
exports.registerAllPlugins = registerAllPlugins;
var _autoColumnSize = require("./autoColumnSize");
exports.AutoColumnSize = _autoColumnSize.AutoColumnSize;
var _autofill = require("./autofill");
exports.Autofill = _autofill.Autofill;
var _autoRowSize = require("./autoRowSize");
exports.AutoRowSize = _autoRowSize.AutoRowSize;
var _base = require("./base");
exports.BasePlugin = _base.BasePlugin;
var _bindRowsWithHeaders = require("./bindRowsWithHeaders");
exports.BindRowsWithHeaders = _bindRowsWithHeaders.BindRowsWithHeaders;
var _collapsibleColumns = require("./collapsibleColumns");
exports.CollapsibleColumns = _collapsibleColumns.CollapsibleColumns;
var _columnSorting = require("./columnSorting");
exports.ColumnSorting = _columnSorting.ColumnSorting;
var _columnSummary = require("./columnSummary");
exports.ColumnSummary = _columnSummary.ColumnSummary;
var _comments = require("./comments");
exports.Comments = _comments.Comments;
var _contextMenu = require("./contextMenu");
exports.ContextMenu = _contextMenu.ContextMenu;
var _copyPaste = require("./copyPaste");
exports.CopyPaste = _copyPaste.CopyPaste;
var _customBorders = require("./customBorders");
exports.CustomBorders = _customBorders.CustomBorders;
var _dragToScroll = require("./dragToScroll");
exports.DragToScroll = _dragToScroll.DragToScroll;
var _dropdownMenu = require("./dropdownMenu");
exports.DropdownMenu = _dropdownMenu.DropdownMenu;
var _exportFile = require("./exportFile");
exports.ExportFile = _exportFile.ExportFile;
var _filters = require("./filters");
exports.Filters = _filters.Filters;
var _formulas = require("./formulas");
exports.Formulas = _formulas.Formulas;
var _hiddenColumns = require("./hiddenColumns");
exports.HiddenColumns = _hiddenColumns.HiddenColumns;
var _hiddenRows = require("./hiddenRows");
exports.HiddenRows = _hiddenRows.HiddenRows;
var _manualColumnFreeze = require("./manualColumnFreeze");
exports.ManualColumnFreeze = _manualColumnFreeze.ManualColumnFreeze;
var _manualColumnMove = require("./manualColumnMove");
exports.ManualColumnMove = _manualColumnMove.ManualColumnMove;
var _manualColumnResize = require("./manualColumnResize");
exports.ManualColumnResize = _manualColumnResize.ManualColumnResize;
var _manualRowMove = require("./manualRowMove");
exports.ManualRowMove = _manualRowMove.ManualRowMove;
var _manualRowResize = require("./manualRowResize");
exports.ManualRowResize = _manualRowResize.ManualRowResize;
var _mergeCells = require("./mergeCells");
exports.MergeCells = _mergeCells.MergeCells;
var _multiColumnSorting = require("./multiColumnSorting");
exports.MultiColumnSorting = _multiColumnSorting.MultiColumnSorting;
var _multipleSelectionHandles = require("./multipleSelectionHandles");
exports.MultipleSelectionHandles = _multipleSelectionHandles.MultipleSelectionHandles;
var _nestedHeaders = require("./nestedHeaders");
exports.NestedHeaders = _nestedHeaders.NestedHeaders;
var _nestedRows = require("./nestedRows");
exports.NestedRows = _nestedRows.NestedRows;
var _persistentState = require("./persistentState");
exports.PersistentState = _persistentState.PersistentState;
var _search = require("./search");
exports.Search = _search.Search;
var _touchScroll = require("./touchScroll");
exports.TouchScroll = _touchScroll.TouchScroll;
var _trimRows = require("./trimRows");
exports.TrimRows = _trimRows.TrimRows;
var _undoRedo = require("./undoRedo");
exports.UndoRedo = _undoRedo.UndoRedo;
var _registry = require("./registry");
exports.registerPlugin = _registry.registerPlugin;
exports.getPlugin = _registry.getPlugin;
exports.getPluginsNames = _registry.getPluginsNames;
/**
 * Registers all available plugins.
 */
function registerAllPlugins() {
  (0, _registry.registerPlugin)(_autoColumnSize.AutoColumnSize);
  (0, _registry.registerPlugin)(_autofill.Autofill);
  (0, _registry.registerPlugin)(_autoRowSize.AutoRowSize);
  (0, _registry.registerPlugin)(_bindRowsWithHeaders.BindRowsWithHeaders);
  (0, _registry.registerPlugin)(_collapsibleColumns.CollapsibleColumns);
  (0, _registry.registerPlugin)(_columnSorting.ColumnSorting);
  (0, _registry.registerPlugin)(_columnSummary.ColumnSummary);
  (0, _registry.registerPlugin)(_comments.Comments);
  (0, _registry.registerPlugin)(_contextMenu.ContextMenu);
  (0, _registry.registerPlugin)(_copyPaste.CopyPaste);
  (0, _registry.registerPlugin)(_customBorders.CustomBorders);
  (0, _registry.registerPlugin)(_dragToScroll.DragToScroll);
  (0, _registry.registerPlugin)(_dropdownMenu.DropdownMenu);
  (0, _registry.registerPlugin)(_exportFile.ExportFile);
  (0, _registry.registerPlugin)(_filters.Filters);
  (0, _registry.registerPlugin)(_formulas.Formulas);
  (0, _registry.registerPlugin)(_hiddenColumns.HiddenColumns);
  (0, _registry.registerPlugin)(_hiddenRows.HiddenRows);
  (0, _registry.registerPlugin)(_manualColumnFreeze.ManualColumnFreeze);
  (0, _registry.registerPlugin)(_manualColumnMove.ManualColumnMove);
  (0, _registry.registerPlugin)(_manualColumnResize.ManualColumnResize);
  (0, _registry.registerPlugin)(_manualRowMove.ManualRowMove);
  (0, _registry.registerPlugin)(_manualRowResize.ManualRowResize);
  (0, _registry.registerPlugin)(_mergeCells.MergeCells);
  (0, _registry.registerPlugin)(_multiColumnSorting.MultiColumnSorting);
  (0, _registry.registerPlugin)(_multipleSelectionHandles.MultipleSelectionHandles);
  (0, _registry.registerPlugin)(_nestedHeaders.NestedHeaders);
  (0, _registry.registerPlugin)(_nestedRows.NestedRows);
  (0, _registry.registerPlugin)(_persistentState.PersistentState);
  (0, _registry.registerPlugin)(_search.Search);
  (0, _registry.registerPlugin)(_touchScroll.TouchScroll);
  (0, _registry.registerPlugin)(_trimRows.TrimRows);
  (0, _registry.registerPlugin)(_undoRedo.UndoRedo);
}