"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
require("core-js/modules/es.array.iterator.js");
require("core-js/modules/es.object.to-string.js");
require("core-js/modules/es.string.iterator.js");
require("core-js/modules/es.weak-map.js");
require("core-js/modules/web.dom-collections.iterator.js");
require("core-js/modules/es.object.get-own-property-descriptor.js");
require("core-js/modules/es.symbol.js");
require("core-js/modules/es.symbol.description.js");
require("core-js/modules/es.symbol.iterator.js");
exports.__esModule = true;
exports.default = void 0;
var C = _interopRequireWildcard(require("../constants"));
var _dictionary;
function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function _getRequireWildcardCache(nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }
function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
var dictionary = (_dictionary = {
  languageCode: 'it-IT'
}, _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ROW_ABOVE, 'Inserisci riga sopra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ROW_BELOW, 'Inserisci riga sotto'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_INSERT_LEFT, 'Inserisci colonna a sinistra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_INSERT_RIGHT, 'Inserisci colonna a destra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_ROW, ['Rimuovi riga', 'Rimuovi righe']), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_COLUMN, ['Rimuovi colonna', 'Rimuovi colonne']), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_UNDO, 'Annulla'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_REDO, 'Ripeti'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_READ_ONLY, 'Sola lettura'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_CLEAR_COLUMN, 'Svuota colonna'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT, 'Allineamento'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_LEFT, 'Sinistra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_CENTER, 'Centro'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_RIGHT, 'Destra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_JUSTIFY, 'Giustificato'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_TOP, 'In alto'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_MIDDLE, 'A metà'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ALIGNMENT_BOTTOM, 'In basso'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_FREEZE_COLUMN, 'Blocca colonna'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_UNFREEZE_COLUMN, 'Sblocca colonna'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS, 'Bordi'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_TOP, 'Sopra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_RIGHT, 'Destra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_BOTTOM, 'Sotto'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_BORDERS_LEFT, 'Sinistra'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_BORDERS, 'Rimuovi bordo(i)'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_ADD_COMMENT, 'Aggiungi commento'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_EDIT_COMMENT, 'Modifica commento'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_REMOVE_COMMENT, 'Rimuovi commento'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_READ_ONLY_COMMENT, 'Commento in sola lettura'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_MERGE_CELLS, 'Unisci celle'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_UNMERGE_CELLS, 'Separa celle'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_COPY, 'Copia'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_CUT, 'Taglia'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_NESTED_ROWS_INSERT_CHILD, 'Inserisci riga figlia'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_NESTED_ROWS_DETACH_CHILD, 'Scollega da riga madre'), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_HIDE_COLUMN, ['Nascondi colonna', 'Nascondi colonne']), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_SHOW_COLUMN, ['Mostra colonna', 'Mostra colonne']), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_HIDE_ROW, ['Nascondi riga', 'Nascondi righe']), _defineProperty(_dictionary, C.CONTEXTMENU_ITEMS_SHOW_ROW, ['Mostra riga', 'Mostra righe']), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_NONE, 'Nessuna'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_EMPTY, 'È vuoto'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_NOT_EMPTY, 'Non è vuoto'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_EQUAL, 'È uguale a'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_NOT_EQUAL, 'È diverso da'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_BEGINS_WITH, 'Inizia con'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_ENDS_WITH, 'Termina con'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_CONTAINS, 'Contiene'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_NOT_CONTAIN, 'Non contiene'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_GREATER_THAN, 'Maggiore'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_GREATER_THAN_OR_EQUAL, 'Maggiore o uguale'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_LESS_THAN, 'Minore'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_LESS_THAN_OR_EQUAL, 'Minore o uguale'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_BETWEEN, 'È compreso tra'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_NOT_BETWEEN, 'Non è compreso tra'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_AFTER, 'Dopo'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_BEFORE, 'Prima'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_TODAY, 'Oggi'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_TOMORROW, 'Domani'), _defineProperty(_dictionary, C.FILTERS_CONDITIONS_YESTERDAY, 'Ieri'), _defineProperty(_dictionary, C.FILTERS_VALUES_BLANK_CELLS, 'Celle vuote'), _defineProperty(_dictionary, C.FILTERS_DIVS_FILTER_BY_CONDITION, 'Filtra per condizione'), _defineProperty(_dictionary, C.FILTERS_DIVS_FILTER_BY_VALUE, 'Filtra per valore'), _defineProperty(_dictionary, C.FILTERS_LABELS_CONJUNCTION, 'E'), _defineProperty(_dictionary, C.FILTERS_LABELS_DISJUNCTION, 'O'), _defineProperty(_dictionary, C.FILTERS_BUTTONS_SELECT_ALL, 'Seleziona tutto'), _defineProperty(_dictionary, C.FILTERS_BUTTONS_CLEAR, 'Pulisci'), _defineProperty(_dictionary, C.FILTERS_BUTTONS_OK, 'OK'), _defineProperty(_dictionary, C.FILTERS_BUTTONS_CANCEL, 'Annulla'), _defineProperty(_dictionary, C.FILTERS_BUTTONS_PLACEHOLDER_SEARCH, 'Cerca'), _defineProperty(_dictionary, C.FILTERS_BUTTONS_PLACEHOLDER_VALUE, 'Valore'), _defineProperty(_dictionary, C.FILTERS_BUTTONS_PLACEHOLDER_SECOND_VALUE, 'Sostituisci con'), _dictionary);
var _default = dictionary;
exports.default = _default;