import "core-js/modules/es.array.slice.js";
import "core-js/modules/es.object.freeze.js";
var _templateObject;
function _taggedTemplateLiteral(strings, raw) { if (!raw) { raw = strings.slice(0); } return Object.freeze(Object.defineProperties(strings, { raw: { value: Object.freeze(raw) } })); }
import "core-js/modules/es.number.is-integer.js";
import "core-js/modules/es.number.constructor.js";
import "core-js/modules/es.array.includes.js";
import "core-js/modules/es.string.includes.js";
import "core-js/modules/es.array.splice.js";
import "core-js/modules/es.array.index-of.js";
import { toSingleLine } from "../../../../helpers/templateLiteralTag.mjs"; /**
                                                                            * @param {TreeNode} nodeToProcess A tree node to process.
                                                                            * @param {number} gridColumnIndex The visual column index that triggers the node modification.
                                                                            *                                 The index can be between the root node column index and
                                                                            *                                 column index plus node colspan length.
                                                                            */
export function showColumn(nodeToProcess, gridColumnIndex) {
  if (!Number.isInteger(gridColumnIndex)) {
    throw new Error('The passed gridColumnIndex argument has invalid type.');
  }
  if (nodeToProcess.childs.length > 0) {
    throw new Error(toSingleLine(_templateObject || (_templateObject = _taggedTemplateLiteral(["The passed node is not the last node on the tree. Only for \nthe last node, the show column modification can be applied."], ["The passed node is not the last node on the tree. Only for\\x20\nthe last node, the show column modification can be applied."]))));
  }
  var crossHiddenColumns = nodeToProcess.data.crossHiddenColumns;
  if (!crossHiddenColumns.includes(gridColumnIndex)) {
    return;
  }
  var isCollapsibleNode = false;
  nodeToProcess.walkUp(function (node) {
    var collapsible = node.data.collapsible;
    if (collapsible) {
      isCollapsibleNode = true;
      return false; // Cancel tree traversing
    }
  });

  // TODO: When the node is collapsible do not show the column. Currently collapsible headers
  // does not work with hidden columns (hidden index map types).
  if (isCollapsibleNode) {
    return;
  }
  nodeToProcess.walkUp(function (node) {
    var data = node.data;
    data.crossHiddenColumns.splice(data.crossHiddenColumns.indexOf(gridColumnIndex), 1);
    if (!data.isHidden && data.colspan < data.origColspan) {
      data.colspan += 1;
    }
    data.isHidden = false;
  });
}