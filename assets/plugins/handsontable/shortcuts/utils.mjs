import "core-js/modules/es.array.iterator.js";
import "core-js/modules/es.map.js";
import "core-js/modules/es.object.to-string.js";
import "core-js/modules/es.string.iterator.js";
import "core-js/modules/web.dom-collections.iterator.js";
import "core-js/modules/es.array.join.js";
import "core-js/modules/es.array.sort.js";
import "core-js/modules/es.array.map.js";
// This file handles key-name discrepancies between browsers.
// For the list of discrepancies, go to: https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key/Key_Values.
var mappings = new Map([[' ', 'space'],
// custom mapping
['spacebar', 'space'], ['scroll', 'scrolllock'], ['del', 'delete'], ['esc', 'escape'], ['medianexttrack', 'mediatracknext'], ['mediaprevioustrack', 'mediatrackprevious'], ['volumeup', 'audiovolumeup'], ['volumedown', 'audiovolumedown'], ['volumemute', 'audiovolumemute'], ['multiply', '*'], ['add', '+'], ['divide', '/'], ['subtract', '-'], ['left', 'arrowleft'], ['right', 'arrowright'], ['up', 'arrowup'], ['down', 'arrowdown']]);

/* eslint-disable jsdoc/require-description-complete-sentence */

/**
 * Get a single, normalized string from the list of the `KeyboardEvent.key` properties.
 *
 * @param {Array<string>} keys The list of the `KeyboardEvent.key` properties
 * @returns {string}
 */
export var normalizeKeys = function normalizeKeys(keys) {
  return keys.map(function (key) {
    var lowercaseKey = key.toLowerCase();
    if (mappings.has(lowercaseKey)) {
      return mappings.get(lowercaseKey);
    }
    return lowercaseKey;
  }).sort().join('+');
};

/**
 * Get the list of the `KeyboardEvent.key` properties from a single, normalized string.
 *
 * @param {string} normalizedKeys A single, normalized string that contains the list of the `KeyboardEvent.key` properties
 * @returns {Array<string>}
 */
export var getKeysList = function getKeysList(normalizedKeys) {
  return normalizedKeys.split('+');
};

/**
 * Normalize a `KeyboardEvent.key` property, to use it for keyboard shortcuts.
 *
 * @param {string} key KeyboardEvent's key property.
 * @returns {string}
 */
export var normalizeEventKey = function normalizeEventKey(key) {
  return key.toLowerCase();
};