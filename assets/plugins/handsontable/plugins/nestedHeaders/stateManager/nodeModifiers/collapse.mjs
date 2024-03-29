import "core-js/modules/es.array.slice.js";
import "core-js/modules/es.array.iterator.js";
import "core-js/modules/es.object.to-string.js";
import "core-js/modules/es.set.js";
import "core-js/modules/es.string.iterator.js";
import "core-js/modules/web.dom-collections.iterator.js";
import "core-js/modules/es.array.from.js";
import { arrayEach } from "../../../../helpers/array.mjs";
import { expandNode } from "./expand.mjs";
import { getFirstChildProperty, isNodeReflectsFirstChildColspan, traverseHiddenNodeColumnIndexes } from "./utils/tree.mjs"; /**
                                                                                                                             * Collapsing a node is a process where the processing node is collapsed
                                                                                                                             * to the colspan width of the first child. All node children, except the
                                                                                                                             * first one, are hidden. To prevent losing a current state of node children
                                                                                                                             * on the right, all nodes are cloned (and restored while expanding), and
                                                                                                                             * only then original nodes are modified (hidden in this case).
                                                                                                                             *
                                                                                                                             * @param {TreeNode} nodeToProcess A tree node to process.
                                                                                                                             * @returns {object} Returns an object with properties:
                                                                                                                             *                    - rollbackModification: The function that rollbacks
                                                                                                                             *                      the tree to the previous state.
                                                                                                                             *                    - affectedColumns: The list of the visual column
                                                                                                                             *                      indexes which are affected. That list is passed
                                                                                                                             *                      to the hiddens column logic.
                                                                                                                             *                    - colspanCompensation: The number of colspan by
                                                                                                                             *                      which the processed node colspan was reduced.
                                                                                                                             */
export function collapseNode(nodeToProcess) {
  var _getFirstChildPropert;
  var nodeData = nodeToProcess.data,
    nodeChilds = nodeToProcess.childs;
  if (nodeData.isCollapsed || nodeData.isHidden || nodeData.origColspan <= 1) {
    return {
      rollbackModification: function rollbackModification() {},
      affectedColumns: [],
      colspanCompensation: 0
    };
  }
  var isNodeReflected = isNodeReflectsFirstChildColspan(nodeToProcess);
  if (isNodeReflected) {
    return collapseNode(nodeChilds[0]);
  }
  nodeData.isCollapsed = true;
  var allLeavesExceptMostLeft = nodeChilds.slice(1);
  var affectedColumns = new Set();
  if (allLeavesExceptMostLeft.length > 0) {
    arrayEach(allLeavesExceptMostLeft, function (node) {
      traverseHiddenNodeColumnIndexes(node, function (gridColumnIndex) {
        affectedColumns.add(gridColumnIndex);
      });

      // Clone the tree to preserve original tree state after header expanding.
      node.data.clonedTree = node.cloneTree();

      // Hide all leaves except the first leaf on the left (on headers context hide all
      // headers on the right).
      node.walkDown(function (_ref) {
        var data = _ref.data;
        data.isHidden = true;
      });
    });
  } else {
    var origColspan = nodeData.origColspan,
      columnIndex = nodeData.columnIndex;

    // Add column to "affected" started from 1. The header without children can not be
    // collapsed so the first have to be visible (untouched).
    for (var i = 1; i < origColspan; i++) {
      var gridColumnIndex = columnIndex + i;
      affectedColumns.add(gridColumnIndex);
    }
  }

  // Calculate by how many colspan it needs to reduce the headings to match them to
  // the first child colspan width.
  var colspanCompensation = nodeData.colspan - ((_getFirstChildPropert = getFirstChildProperty(nodeToProcess, 'colspan')) !== null && _getFirstChildPropert !== void 0 ? _getFirstChildPropert : 1);
  nodeToProcess.walkUp(function (node) {
    var data = node.data;
    data.colspan -= colspanCompensation;
    if (data.colspan <= 1) {
      data.colspan = 1;
      data.isCollapsed = true;
    } else if (isNodeReflectsFirstChildColspan(node)) {
      data.isCollapsed = getFirstChildProperty(node, 'isCollapsed');
    }
  });
  return {
    rollbackModification: function rollbackModification() {
      return expandNode(nodeToProcess);
    },
    affectedColumns: Array.from(affectedColumns),
    colspanCompensation: colspanCompensation
  };
}