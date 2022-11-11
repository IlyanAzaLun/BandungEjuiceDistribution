import "core-js/modules/es.array.iterator.js";
import "core-js/modules/es.object.to-string.js";
import "core-js/modules/es.string.iterator.js";
import "core-js/modules/es.weak-map.js";
import "core-js/modules/web.dom-collections.iterator.js";
import "core-js/modules/es.array.includes.js";
import "core-js/modules/es.string.includes.js";
import "core-js/modules/es.regexp.to-string.js";
import "core-js/modules/web.timers.js";
import { baseRenderer } from "../baseRenderer/index.mjs";
import EventManager from "../../eventManager.mjs";
import { empty, addClass } from "../../helpers/dom/element.mjs";
import { isEmpty, stringify } from "../../helpers/mixed.mjs";
import { SHORTCUTS_GROUP_EDITOR } from "../../editorManager.mjs";
import Hooks from "../../pluginHooks.mjs";
var isListeningKeyDownEvent = new WeakMap();
var isCheckboxListenerAdded = new WeakMap();
var BAD_VALUE_CLASS = 'htBadValue';
var ATTR_ROW = 'data-row';
var ATTR_COLUMN = 'data-col';
var SHORTCUTS_GROUP = 'checkboxRenderer';
export var RENDERER_TYPE = 'checkbox';
Hooks.getSingleton().add('modifyAutoColumnSizeSeed', function (bundleSeed, cellMeta, cellValue) {
  var label = cellMeta.label,
    type = cellMeta.type,
    row = cellMeta.row,
    column = cellMeta.column,
    prop = cellMeta.prop;
  if (type !== RENDERER_TYPE) {
    return;
  }
  if (label) {
    var labelValue = label.value,
      labelProperty = label.property;
    var labelText = cellValue;
    if (labelValue) {
      labelText = typeof labelValue === 'function' ? labelValue(row, column, prop, cellValue) : labelValue;
    } else if (labelProperty) {
      var labelData = this.getDataAtRowProp(row, labelProperty);
      labelText = labelData !== null ? labelData : cellValue;
    }
    bundleSeed = labelText;
  }
  return bundleSeed;
});
/**
 * Checkbox renderer.
 *
 * @private
 * @param {Core} instance The Handsontable instance.
 * @param {HTMLTableCellElement} TD The rendered cell element.
 * @param {number} row The visual row index.
 * @param {number} col The visual column index.
 * @param {number|string} prop The column property (passed when datasource is an array of objects).
 * @param {*} value The rendered value.
 * @param {object} cellProperties The cell meta object ({@see Core#getCellMeta}).
 */
export function checkboxRenderer(instance, TD, row, col, prop, value, cellProperties) {
  var rootDocument = instance.rootDocument;
  baseRenderer.apply(this, [instance, TD, row, col, prop, value, cellProperties]);
  registerEvents(instance);
  var input = createInput(rootDocument);
  var labelOptions = cellProperties.label;
  var badValue = false;
  if (typeof cellProperties.checkedTemplate === 'undefined') {
    cellProperties.checkedTemplate = true;
  }
  if (typeof cellProperties.uncheckedTemplate === 'undefined') {
    cellProperties.uncheckedTemplate = false;
  }
  empty(TD); // TODO identify under what circumstances this line can be removed

  if (value === cellProperties.checkedTemplate || stringify(value).toLocaleLowerCase(cellProperties.locale) === stringify(cellProperties.checkedTemplate).toLocaleLowerCase(cellProperties.locale)) {
    input.checked = true;
  } else if (value === cellProperties.uncheckedTemplate || stringify(value).toLocaleLowerCase(cellProperties.locale) === stringify(cellProperties.uncheckedTemplate).toLocaleLowerCase(cellProperties.locale)) {
    input.checked = false;
  } else if (isEmpty(value)) {
    // default value
    addClass(input, 'noValue');
  } else {
    input.style.display = 'none';
    addClass(input, BAD_VALUE_CLASS);
    badValue = true;
  }
  input.setAttribute(ATTR_ROW, row);
  input.setAttribute(ATTR_COLUMN, col);
  if (!badValue && labelOptions) {
    var labelText = '';
    if (labelOptions.value) {
      labelText = typeof labelOptions.value === 'function' ? labelOptions.value.call(this, row, col, prop, value) : labelOptions.value;
    } else if (labelOptions.property) {
      var labelValue = instance.getDataAtRowProp(row, labelOptions.property);
      labelText = labelValue !== null ? labelValue : '';
    }
    var label = createLabel(rootDocument, labelText, labelOptions.separated !== true);
    if (labelOptions.position === 'before') {
      if (labelOptions.separated) {
        TD.appendChild(label);
        TD.appendChild(input);
      } else {
        label.appendChild(input);
        input = label;
      }
    } else if (!labelOptions.position || labelOptions.position === 'after') {
      if (labelOptions.separated) {
        TD.appendChild(input);
        TD.appendChild(label);
      } else {
        label.insertBefore(input, label.firstChild);
        input = label;
      }
    }
  }
  if (!labelOptions || labelOptions && !labelOptions.separated) {
    TD.appendChild(input);
  }
  if (badValue) {
    TD.appendChild(rootDocument.createTextNode('#bad-value#'));
  }
  if (!isListeningKeyDownEvent.has(instance)) {
    isListeningKeyDownEvent.set(instance, true);
    registerShortcuts();
  }

  /**
   * Register shortcuts responsible for toggling checkbox state.
   *
   * @private
   */
  function registerShortcuts() {
    var shortcutManager = instance.getShortcutManager();
    var gridContext = shortcutManager.getContext('grid');
    var config = {
      group: SHORTCUTS_GROUP
    };
    gridContext.addShortcuts([{
      keys: [['space']],
      callback: function callback() {
        changeSelectedCheckboxesState();
        return !areSelectedCheckboxCells(); // False blocks next action associated with the keyboard shortcut.
      }
    }, {
      keys: [['enter']],
      callback: function callback() {
        changeSelectedCheckboxesState();
        return !areSelectedCheckboxCells(); // False blocks next action associated with the keyboard shortcut.
      },

      runOnlyIf: function runOnlyIf() {
        return instance.getSettings().enterBeginsEditing;
      }
    }, {
      keys: [['delete'], ['backspace']],
      callback: function callback() {
        changeSelectedCheckboxesState(true);
        return !areSelectedCheckboxCells(); // False blocks next action associated with the keyboard shortcut.
      },

      relativeToGroup: SHORTCUTS_GROUP_EDITOR,
      position: 'before'
    }], config);
  }

  /**
   * Change checkbox checked property.
   *
   * @private
   * @param {boolean} [uncheckCheckbox=false] The new "checked" state for the checkbox elements.
   */
  function changeSelectedCheckboxesState() {
    var uncheckCheckbox = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
    var selRange = instance.getSelectedRange();
    if (!selRange) {
      return;
    }
    for (var key = 0; key < selRange.length; key++) {
      var _selRange$key$getTopS = selRange[key].getTopStartCorner(),
        startRow = _selRange$key$getTopS.row,
        startColumn = _selRange$key$getTopS.col;
      var _selRange$key$getBott = selRange[key].getBottomEndCorner(),
        endRow = _selRange$key$getBott.row,
        endColumn = _selRange$key$getBott.col;
      var changes = [];
      for (var visualRow = startRow; visualRow <= endRow; visualRow += 1) {
        for (var visualColumn = startColumn; visualColumn <= endColumn; visualColumn += 1) {
          var cachedCellProperties = instance.getCellMeta(visualRow, visualColumn);
          if (cachedCellProperties.type !== 'checkbox') {
            return;
          }

          /* eslint-disable no-continue */
          if (cachedCellProperties.readOnly === true) {
            continue;
          }
          if (typeof cachedCellProperties.checkedTemplate === 'undefined') {
            cachedCellProperties.checkedTemplate = true;
          }
          if (typeof cachedCellProperties.uncheckedTemplate === 'undefined') {
            cachedCellProperties.uncheckedTemplate = false;
          }
          var dataAtCell = instance.getDataAtCell(visualRow, visualColumn);
          if (uncheckCheckbox === false) {
            if ([cachedCellProperties.checkedTemplate, cachedCellProperties.checkedTemplate.toString()].includes(dataAtCell)) {
              // eslint-disable-line max-len
              changes.push([visualRow, visualColumn, cachedCellProperties.uncheckedTemplate]);
            } else if ([cachedCellProperties.uncheckedTemplate, cachedCellProperties.uncheckedTemplate.toString(), null, void 0].includes(dataAtCell)) {
              // eslint-disable-line max-len
              changes.push([visualRow, visualColumn, cachedCellProperties.checkedTemplate]);
            }
          } else {
            changes.push([visualRow, visualColumn, cachedCellProperties.uncheckedTemplate]);
          }
        }
      }
      if (changes.length > 0) {
        instance.setDataAtCell(changes);
      }
    }
  }

  /**
   * Check whether all selected cells are with checkbox type.
   *
   * @returns {boolean}
   * @private
   */
  function areSelectedCheckboxCells() {
    var selRange = instance.getSelectedRange();
    if (!selRange) {
      return;
    }
    for (var key = 0; key < selRange.length; key++) {
      var topLeft = selRange[key].getTopStartCorner();
      var bottomRight = selRange[key].getBottomEndCorner();
      for (var visualRow = topLeft.row; visualRow <= bottomRight.row; visualRow++) {
        for (var visualColumn = topLeft.col; visualColumn <= bottomRight.col; visualColumn++) {
          var cachedCellProperties = instance.getCellMeta(visualRow, visualColumn);
          if (cachedCellProperties.type !== 'checkbox') {
            return false;
          }
          var cell = instance.getCell(visualRow, visualColumn);
          if (cell === null || cell === void 0) {
            return true;
          } else {
            var checkboxes = cell.querySelectorAll('input[type=checkbox]');
            if (checkboxes.length > 0 && !cachedCellProperties.readOnly) {
              return true;
            }
          }
        }
      }
    }
    return false;
  }
}
checkboxRenderer.RENDERER_TYPE = RENDERER_TYPE;

/**
 * Register checkbox listeners.
 *
 * @param {Core} instance The Handsontable instance.
 * @returns {EventManager}
 */
function registerEvents(instance) {
  var eventManager = isCheckboxListenerAdded.get(instance);
  if (!eventManager) {
    var rootElement = instance.rootElement;
    eventManager = new EventManager(instance);
    eventManager.addEventListener(rootElement, 'click', function (event) {
      return onClick(event, instance);
    });
    eventManager.addEventListener(rootElement, 'mouseup', function (event) {
      return onMouseUp(event, instance);
    });
    eventManager.addEventListener(rootElement, 'change', function (event) {
      return onChange(event, instance);
    });
    isCheckboxListenerAdded.set(instance, eventManager);
  }
  return eventManager;
}

/**
 * Create input element.
 *
 * @param {Document} rootDocument The document owner.
 * @returns {Node}
 */
function createInput(rootDocument) {
  var input = rootDocument.createElement('input');
  input.className = 'htCheckboxRendererInput';
  input.type = 'checkbox';
  input.setAttribute('autocomplete', 'off');
  input.setAttribute('tabindex', '-1');
  return input.cloneNode(false);
}

/**
 * Create label element.
 *
 * @param {Document} rootDocument The document owner.
 * @param {string} text The label text.
 * @param {boolean} fullWidth Determines whether label should have full width.
 * @returns {Node}
 */
function createLabel(rootDocument, text, fullWidth) {
  var label = rootDocument.createElement('label');
  label.className = "htCheckboxRendererLabel ".concat(fullWidth ? 'fullWidth' : '');
  label.appendChild(rootDocument.createTextNode(text));
  return label.cloneNode(true);
}

/**
 * `mouseup` callback.
 *
 * @private
 * @param {Event} event `mouseup` event.
 * @param {Core} instance The Handsontable instance.
 */
function onMouseUp(event, instance) {
  var target = event.target;
  if (!isCheckboxInput(target)) {
    return;
  }
  if (!target.hasAttribute(ATTR_ROW) || !target.hasAttribute(ATTR_COLUMN)) {
    return;
  }
  setTimeout(instance.listen, 10);
}

/**
 * `click` callback.
 *
 * @private
 * @param {MouseEvent} event `click` event.
 * @param {Core} instance The Handsontable instance.
 */
function onClick(event, instance) {
  var target = event.target;
  if (!isCheckboxInput(target)) {
    return;
  }
  if (!target.hasAttribute(ATTR_ROW) || !target.hasAttribute(ATTR_COLUMN)) {
    return;
  }
  var row = parseInt(target.getAttribute(ATTR_ROW), 10);
  var col = parseInt(target.getAttribute(ATTR_COLUMN), 10);
  var cellProperties = instance.getCellMeta(row, col);
  if (cellProperties.readOnly) {
    event.preventDefault();
  }
}

/**
 * `change` callback.
 *
 * @param {Event} event `change` event.
 * @param {Core} instance The Handsontable instance.
 */
function onChange(event, instance) {
  var target = event.target;
  if (!isCheckboxInput(target)) {
    return;
  }
  if (!target.hasAttribute(ATTR_ROW) || !target.hasAttribute(ATTR_COLUMN)) {
    return;
  }
  var row = parseInt(target.getAttribute(ATTR_ROW), 10);
  var col = parseInt(target.getAttribute(ATTR_COLUMN), 10);
  var cellProperties = instance.getCellMeta(row, col);
  if (!cellProperties.readOnly) {
    var newCheckboxValue = null;
    if (event.target.checked) {
      newCheckboxValue = cellProperties.uncheckedTemplate === void 0 ? true : cellProperties.checkedTemplate;
    } else {
      newCheckboxValue = cellProperties.uncheckedTemplate === void 0 ? false : cellProperties.uncheckedTemplate;
    }
    instance.setDataAtCell(row, col, newCheckboxValue);
  }
}

/**
 * Check if the provided element is the checkbox input.
 *
 * @private
 * @param {HTMLElement} element The element in question.
 * @returns {boolean}
 */
function isCheckboxInput(element) {
  return element.tagName === 'INPUT' && element.getAttribute('type') === 'checkbox';
}