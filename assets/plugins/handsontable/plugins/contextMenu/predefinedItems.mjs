var _predefinedItems2;
import "core-js/modules/es.array.index-of.js";
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
import { objectEach } from "../../helpers/object.mjs";
import alignmentItem, { KEY as ALIGNMENT } from "./predefinedItems/alignment.mjs";
import clearColumnItem, { KEY as CLEAR_COLUMN } from "./predefinedItems/clearColumn.mjs";
import columnLeftItem, { KEY as COLUMN_LEFT } from "./predefinedItems/columnLeft.mjs";
import columnRightItem, { KEY as COLUMN_RIGHT } from "./predefinedItems/columnRight.mjs";
import readOnlyItem, { KEY as READ_ONLY } from "./predefinedItems/readOnly.mjs";
import redoItem, { KEY as REDO } from "./predefinedItems/redo.mjs";
import removeColumnItem, { KEY as REMOVE_COLUMN } from "./predefinedItems/removeColumn.mjs";
import removeRowItem, { KEY as REMOVE_ROW } from "./predefinedItems/removeRow.mjs";
import rowAboveItem, { KEY as ROW_ABOVE } from "./predefinedItems/rowAbove.mjs";
import rowBelowItem, { KEY as ROW_BELOW } from "./predefinedItems/rowBelow.mjs";
import separatorItem, { KEY as SEPARATOR } from "./predefinedItems/separator.mjs";
import noItemsItem, { KEY as NO_ITEMS } from "./predefinedItems/noItems.mjs";
import undoItem, { KEY as UNDO } from "./predefinedItems/undo.mjs";
export { KEY as ALIGNMENT } from "./predefinedItems/alignment.mjs";
export { KEY as CLEAR_COLUMN } from "./predefinedItems/clearColumn.mjs";
export { KEY as COLUMN_LEFT } from "./predefinedItems/columnLeft.mjs";
export { KEY as COLUMN_RIGHT } from "./predefinedItems/columnRight.mjs";
export { KEY as READ_ONLY } from "./predefinedItems/readOnly.mjs";
export { KEY as REDO } from "./predefinedItems/redo.mjs";
export { KEY as REMOVE_COLUMN } from "./predefinedItems/removeColumn.mjs";
export { KEY as REMOVE_ROW } from "./predefinedItems/removeRow.mjs";
export { KEY as ROW_ABOVE } from "./predefinedItems/rowAbove.mjs";
export { KEY as ROW_BELOW } from "./predefinedItems/rowBelow.mjs";
export { KEY as SEPARATOR } from "./predefinedItems/separator.mjs";
export { KEY as NO_ITEMS } from "./predefinedItems/noItems.mjs";
export { KEY as UNDO } from "./predefinedItems/undo.mjs";
export var ITEMS = [ROW_ABOVE, ROW_BELOW, COLUMN_LEFT, COLUMN_RIGHT, CLEAR_COLUMN, REMOVE_ROW, REMOVE_COLUMN, UNDO, REDO, READ_ONLY, ALIGNMENT, SEPARATOR, NO_ITEMS];
var _predefinedItems = (_predefinedItems2 = {}, _defineProperty(_predefinedItems2, SEPARATOR, separatorItem), _defineProperty(_predefinedItems2, NO_ITEMS, noItemsItem), _defineProperty(_predefinedItems2, ROW_ABOVE, rowAboveItem), _defineProperty(_predefinedItems2, ROW_BELOW, rowBelowItem), _defineProperty(_predefinedItems2, COLUMN_LEFT, columnLeftItem), _defineProperty(_predefinedItems2, COLUMN_RIGHT, columnRightItem), _defineProperty(_predefinedItems2, CLEAR_COLUMN, clearColumnItem), _defineProperty(_predefinedItems2, REMOVE_ROW, removeRowItem), _defineProperty(_predefinedItems2, REMOVE_COLUMN, removeColumnItem), _defineProperty(_predefinedItems2, UNDO, undoItem), _defineProperty(_predefinedItems2, REDO, redoItem), _defineProperty(_predefinedItems2, READ_ONLY, readOnlyItem), _defineProperty(_predefinedItems2, ALIGNMENT, alignmentItem), _predefinedItems2);

/**
 * Gets new object with all predefined menu items.
 *
 * @returns {object}
 */
export function predefinedItems() {
  var items = {};
  objectEach(_predefinedItems, function (itemFactory, key) {
    items[key] = itemFactory();
  });
  return items;
}

/**
 * Add new predefined menu item to the collection.
 *
 * @param {string} key Menu command id.
 * @param {object} item Object command descriptor.
 */
export function addItem(key, item) {
  if (ITEMS.indexOf(key) === -1) {
    _predefinedItems[key] = item;
  }
}