function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
import "core-js/modules/es.array.iterator.js";
import "core-js/modules/es.map.js";
import "core-js/modules/es.object.to-string.js";
import "core-js/modules/es.string.iterator.js";
import "core-js/modules/web.dom-collections.iterator.js";
import "core-js/modules/es.array.from.js";
import "core-js/modules/es.array.map.js";
import "core-js/modules/es.array.includes.js";
import "core-js/modules/es.string.includes.js";
import "core-js/modules/es.array.splice.js";
import "core-js/modules/es.array.index-of.js";
import "core-js/modules/web.dom-collections.for-each.js";
import "core-js/modules/es.function.name.js";
import "core-js/modules/es.symbol.js";
import "core-js/modules/es.symbol.description.js";
import "core-js/modules/es.symbol.iterator.js";
import staticRegister from "../../../utils/staticRegister.mjs";
import { isUndefined } from "../../../helpers/mixed.mjs";
import { warn } from "../../../helpers/console.mjs";
import { PLUGIN_KEY } from "../formulas.mjs";
import { DEFAULT_LICENSE_KEY, getEngineSettingsWithDefaultsAndOverrides } from "./settings.mjs"; /**
                                                                                                  * Prepares and returns the collection for the engine relationship with the HoT instances.
                                                                                                  *
                                                                                                  * @returns {Map}
                                                                                                  */
function getEngineRelationshipRegistry() {
  var registryKey = 'engine_relationship';
  var pluginStaticRegistry = staticRegister(PLUGIN_KEY);
  if (!pluginStaticRegistry.hasItem(registryKey)) {
    pluginStaticRegistry.register(registryKey, new Map());
  }
  return pluginStaticRegistry.getItem(registryKey);
}

/**
 * Prepares and returns the collection for the engine shared usage.
 *
 * @returns {Map}
 */
function getSharedEngineUsageRegistry() {
  var registryKey = 'shared_engine_usage';
  var pluginStaticRegistry = staticRegister(PLUGIN_KEY);
  if (!pluginStaticRegistry.hasItem(registryKey)) {
    pluginStaticRegistry.register(registryKey, new Map());
  }
  return pluginStaticRegistry.getItem(registryKey);
}

/**
 * Setups the engine instance. It either creates a new (possibly shared) engine instance, or attaches
 * the plugin to an already-existing instance.
 *
 * @param {Handsontable} hotInstance Handsontable instance.
 * @returns {null|object} Returns the engine instance if everything worked right and `null` otherwise.
 */
export function setupEngine(hotInstance) {
  var hotSettings = hotInstance.getSettings();
  var pluginSettings = hotSettings[PLUGIN_KEY];
  var engineConfigItem = pluginSettings === null || pluginSettings === void 0 ? void 0 : pluginSettings.engine;
  if (pluginSettings === true) {
    return null;
  }
  if (isUndefined(engineConfigItem)) {
    return null;
  }

  // `engine.hyperformula` or `engine` is the engine class
  if (typeof engineConfigItem.hyperformula === 'function' || typeof engineConfigItem === 'function') {
    var _engineConfigItem$hyp;
    return registerEngine((_engineConfigItem$hyp = engineConfigItem.hyperformula) !== null && _engineConfigItem$hyp !== void 0 ? _engineConfigItem$hyp : engineConfigItem, hotSettings, hotInstance);

    // `engine` is the engine instance
  } else if (_typeof(engineConfigItem) === 'object' && isUndefined(engineConfigItem.hyperformula)) {
    var engineRelationship = getEngineRelationshipRegistry();
    var sharedEngineUsage = getSharedEngineUsageRegistry().get(engineConfigItem);
    if (!engineRelationship.has(engineConfigItem)) {
      engineRelationship.set(engineConfigItem, []);
    }
    engineRelationship.get(engineConfigItem).push(hotInstance);
    if (sharedEngineUsage) {
      sharedEngineUsage.push(hotInstance.guid);
    }
    if (!engineConfigItem.getConfig().licenseKey) {
      engineConfigItem.updateConfig({
        licenseKey: DEFAULT_LICENSE_KEY
      });
    }
    return engineConfigItem;
  }
  return null;
}

/**
 * Registers the engine in the global register and attaches the needed event listeners.
 *
 * @param {Function} engineClass The engine class.
 * @param {object} hotSettings The Handsontable settings.
 * @param {Handsontable} hotInstance Handsontable instance.
 * @returns {object} Returns the engine instance.
 */
export function registerEngine(engineClass, hotSettings, hotInstance) {
  var pluginSettings = hotSettings[PLUGIN_KEY];
  var engineSettings = getEngineSettingsWithDefaultsAndOverrides(hotSettings);
  var engineRegistry = getEngineRelationshipRegistry();
  var sharedEngineRegistry = getSharedEngineUsageRegistry();
  registerCustomFunctions(engineClass, pluginSettings.functions);
  registerLanguage(engineClass, pluginSettings.language);

  // Create instance
  var engineInstance = engineClass.buildEmpty(engineSettings);

  // Add it to global registry
  engineRegistry.set(engineInstance, [hotInstance]);
  sharedEngineRegistry.set(engineInstance, [hotInstance.guid]);
  registerNamedExpressions(engineInstance, pluginSettings.namedExpressions);

  // Add hooks needed for cross-referencing sheets
  engineInstance.on('sheetAdded', function () {
    engineInstance.rebuildAndRecalculate();
  });
  engineInstance.on('sheetRemoved', function () {
    engineInstance.rebuildAndRecalculate();
  });
  return engineInstance;
}

/**
 * Returns the list of the Handsontable instances linked to the specific engine instance.
 *
 * @param {object} engine The engine instance.
 * @returns {Map<number, Handsontable>} Returns Map with Handsontable instances.
 */
export function getRegisteredHotInstances(engine) {
  var _engineRegistry$get;
  var engineRegistry = getEngineRelationshipRegistry();
  var hotInstances = engineRegistry.size === 0 ? [] : Array.from((_engineRegistry$get = engineRegistry.get(engine)) !== null && _engineRegistry$get !== void 0 ? _engineRegistry$get : []);
  return new Map(hotInstances.map(function (hot) {
    return [hot.getPlugin('formulas').sheetId, hot];
  }));
}

/**
 * Removes the HOT instance from the global register's engine usage array, and if there are no HOT instances left,
 * unregisters the engine itself.
 *
 * @param {object} engine The engine instance.
 * @param {string} hotInstance The Handsontable instance.
 */
export function unregisterEngine(engine, hotInstance) {
  if (engine) {
    var engineRegistry = getEngineRelationshipRegistry();
    var engineHotRelationship = engineRegistry.get(engine);
    var sharedEngineRegistry = getSharedEngineUsageRegistry();
    var sharedEngineUsage = sharedEngineRegistry.get(engine);
    if (engineHotRelationship && engineHotRelationship.includes(hotInstance)) {
      engineHotRelationship.splice(engineHotRelationship.indexOf(hotInstance), 1);
      if (engineHotRelationship.length === 0) {
        engineRegistry.delete(engine);
      }
    }
    if (sharedEngineUsage && sharedEngineUsage.includes(hotInstance.guid)) {
      sharedEngineUsage.splice(sharedEngineUsage.indexOf(hotInstance.guid), 1);
      if (sharedEngineUsage.length === 0) {
        sharedEngineRegistry.delete(engine);
        engine.destroy();
      }
    }
  }
}

/**
 * Registers the custom functions for the engine.
 *
 * @param {Function} engineClass The engine class.
 * @param {Array} customFunctions The custom functions array.
 */
export function registerCustomFunctions(engineClass, customFunctions) {
  if (customFunctions) {
    customFunctions.forEach(function (func) {
      var name = func.name,
        plugin = func.plugin,
        translations = func.translations;
      try {
        engineClass.registerFunction(name, plugin, translations);
      } catch (e) {
        warn(e.message);
      }
    });
  }
}

/**
 * Registers the provided language for the engine.
 *
 * @param {Function} engineClass The engine class.
 * @param {object} languageSetting The engine's language object.
 */
export function registerLanguage(engineClass, languageSetting) {
  if (languageSetting) {
    var langCode = languageSetting.langCode;
    try {
      engineClass.registerLanguage(langCode, languageSetting);
    } catch (e) {
      warn(e.message);
    }
  }
}

/**
 * Registers the provided named expressions in the engine instance.
 *
 * @param {object} engineInstance The engine instance.
 * @param {Array} namedExpressions Array of the named expressions to be registered.
 */
export function registerNamedExpressions(engineInstance, namedExpressions) {
  if (namedExpressions) {
    engineInstance.suspendEvaluation();
    namedExpressions.forEach(function (namedExp) {
      var name = namedExp.name,
        expression = namedExp.expression,
        scope = namedExp.scope,
        options = namedExp.options;
      try {
        engineInstance.addNamedExpression(name, expression, scope, options);
      } catch (e) {
        warn(e.message);
      }
    });
    engineInstance.resumeEvaluation();
  }
}

/**
 * Sets up a new sheet.
 *
 * @param {object} engineInstance The engine instance.
 * @param {string} sheetName The new sheet name.
 * @returns {*}
 */
export function setupSheet(engineInstance, sheetName) {
  if (isUndefined(sheetName) || !engineInstance.doesSheetExist(sheetName)) {
    sheetName = engineInstance.addSheet(sheetName);
  }
  return sheetName;
}