"use strict";

exports.__esModule = true;
exports.updateCaretPosition = updateCaretPosition;
var _element = require("../../helpers/dom/element");
/**
 * Updates the textarea caret position depends on the action executed on that element.
 *
 * The following actions are supported:
 *  - 'home': Move the caret to the beginning of the current line;
 *  - 'end': Move the caret to the end of the current line.
 *
 * @param {'home'|'end'} actionName The action to perform that modifies the caret behavior.
 * @param {HTMLTextAreaElement} textareaElement The textarea element where the action is supposed to happen.
 */
function updateCaretPosition(actionName, textareaElement) {
  var caretPosition = (0, _element.getCaretPosition)(textareaElement);
  var textLines = textareaElement.value.split('\n');
  var newCaretPosition = caretPosition;
  var lineStartIndex = 0;
  for (var i = 0; i < textLines.length; i++) {
    var textLine = textLines[i];
    if (i !== 0) {
      lineStartIndex += textLines[i - 1].length + 1;
    }
    var lineEndIndex = lineStartIndex + textLine.length;
    if (actionName === 'home') {
      newCaretPosition = lineStartIndex;
    } else if (actionName === 'end') {
      newCaretPosition = lineEndIndex;
    }
    if (caretPosition <= lineEndIndex) {
      break;
    }
  }
  (0, _element.setCaretPosition)(textareaElement, newCaretPosition);
}