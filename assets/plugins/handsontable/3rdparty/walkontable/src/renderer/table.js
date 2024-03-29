"use strict";

exports.__esModule = true;
exports.default = void 0;
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
/**
 * TableRenderer class collects all renderers and properties necessary for table creation. It's
 * responsible for adjusting and rendering each renderer.
 *
 * Below is a diagram of the renderers together with an indication of what they are responisble for.
 *   <table>
 *     <colgroup>  \ (root node)
 *       <col>      \
 *       <col>       \___ ColGroupRenderer
 *       <col>       /
 *       <col>      /
 *     </colgroup> /
 *     <thead>     \ (root node)
 *       <tr>       \
 *         <th>      \
 *         <th>       \____ ColumnHeadersRenderer
 *         <th>       /
 *         <th>      /
 *       </tr>      /
 *     </thead>    /
 *     <tbody>   ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\ (root node)
 *       <tr>   (root node)          \
 *         <th>  --- RowHeadersRenderer
 *         <td>  \                     \
 *         <td>   -- CellsRenderer      \
 *         <td>  /                       \
 *       </tr>                            \
 *       <tr>   (root node)                \
 *         <th>  --- RowHeadersRenderer     \
 *         <td>  \                           \___ RowsRenderer
 *         <td>   -- CellsRenderer           /
 *         <td>  /                          /
 *       </tr>                             /
 *       <tr>   (root node)               /
 *         <th>  --- RowHeadersRenderer  /
 *         <td>  \                      /
 *         <td>   -- CellsRenderer     /
 *         <td>  /                    /
 *       </tr>                       /
 *     </tbody>  ___________________/
 *   </table>.
 *
 * @class {RowsRenderer}
 */var TableRenderer = /*#__PURE__*/function () {
  function TableRenderer(rootNode) {
    var _ref = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {},
      cellRenderer = _ref.cellRenderer;
    _classCallCheck(this, TableRenderer);
    /**
     * Table element which will be used to render the children element.
     *
     * @type {HTMLTableElement}
     */
    this.rootNode = rootNode;
    /**
     * Document owner of the root node.
     *
     * @type {HTMLDocument}
     */
    this.rootDocument = this.rootNode.ownerDocument;
    /**
     * Renderer class responsible for rendering row headers.
     *
     * @type {RowsRenderer}
     */
    this.rowHeaders = null;
    /**
     * Renderer class responsible for rendering column headers.
     *
     * @type {ColumnHeadersRenderer}
     */
    this.columnHeaders = null;
    /**
     * Renderer class responsible for rendering col in colgroup.
     *
     * @type {ColGroupRenderer}
     */
    this.colGroup = null;
    /**
     * Renderer class responsible for rendering rows in tbody.
     *
     * @type {RowsRenderer}
     */
    this.rows = null;
    /**
     * Renderer class responsible for rendering cells.
     *
     * @type {CellsRenderer}
     */
    this.cells = null;
    /**
     * Row filter which contains all necessary information about row index transformation.
     *
     * @type {RowFilter}
     */
    this.rowFilter = null;
    /**
     * Column filter which contains all necessary information about column index transformation.
     *
     * @type {ColumnFilter}
     */
    this.columnFilter = null;
    /**
     * Row utils class which contains all necessary information about sizes of the rows.
     *
     * @type {RowUtils}
     */
    this.rowUtils = null;
    /**
     * Column utils class which contains all necessary information about sizes of the columns.
     *
     * @type {ColumnUtils}
     */
    this.columnUtils = null;
    /**
     * Indicates how much rows should be rendered to fill whole table viewport.
     *
     * @type {number}
     */
    this.rowsToRender = 0;
    /**
     * Indicates how much columns should be rendered to fill whole table viewport.
     *
     * @type {number}
     */
    this.columnsToRender = 0;
    /**
     * An array of functions to be used as a content factory to row headers.
     *
     * @type {Function[]}
     */
    this.rowHeaderFunctions = [];
    /**
     * Count of the function used to render row headers.
     *
     * @type {number}
     */
    this.rowHeadersCount = 0;
    /**
     * An array of functions to be used as a content factory to column headers.
     *
     * @type {Function[]}
     */
    this.columnHeaderFunctions = [];
    /**
     * Count of the function used to render column headers.
     *
     * @type {number}
     */
    this.columnHeadersCount = 0;
    /**
     * Cell renderer used to render cells content.
     *
     * @type {Function}
     */
    this.cellRenderer = cellRenderer;
  }

  /**
   * Set row and column util classes.
   *
   * @param {RowUtils} rowUtils RowUtils instance which provides useful methods related to row sizes.
   * @param {ColumnUtils} columnUtils ColumnUtils instance which provides useful methods related to row sizes.
   */
  _createClass(TableRenderer, [{
    key: "setAxisUtils",
    value: function setAxisUtils(rowUtils, columnUtils) {
      this.rowUtils = rowUtils;
      this.columnUtils = columnUtils;
    }

    /**
     * Sets viewport size of the table.
     *
     * @param {number} rowsCount An amount of rows to render.
     * @param {number} columnsCount An amount of columns to render.
     */
  }, {
    key: "setViewportSize",
    value: function setViewportSize(rowsCount, columnsCount) {
      this.rowsToRender = rowsCount;
      this.columnsToRender = columnsCount;
    }

    /**
     * Sets row and column filter instances.
     *
     * @param {RowFilter} rowFilter Row filter instance which contains all necessary information about row index transformation.
     * @param {ColumnFilter} columnFilter Column filter instance which contains all necessary information about row
     * index transformation.
     */
  }, {
    key: "setFilters",
    value: function setFilters(rowFilter, columnFilter) {
      this.rowFilter = rowFilter;
      this.columnFilter = columnFilter;
    }

    /**
     * Sets row and column header functions.
     *
     * @param {Function[]} rowHeaders Row header functions. Factories for creating content for row headers.
     * @param {Function[]} columnHeaders Column header functions. Factories for creating content for column headers.
     */
  }, {
    key: "setHeaderContentRenderers",
    value: function setHeaderContentRenderers(rowHeaders, columnHeaders) {
      this.rowHeaderFunctions = rowHeaders;
      this.rowHeadersCount = rowHeaders.length;
      this.columnHeaderFunctions = columnHeaders;
      this.columnHeadersCount = columnHeaders.length;
    }

    /**
     * Sets table renderers.
     *
     * @param {renderers} renderers The renderer units.
     * @param {RowHeadersRenderer} renderers.rowHeaders Row headers renderer.
     * @param {ColumnHeadersRenderer} renderers.columnHeaders Column headers renderer.
     * @param {ColGroupRenderer} renderers.colGroup Col group renderer.
     * @param {RowsRenderer} renderers.rows Rows renderer.
     * @param {CellsRenderer} renderers.cells Cells renderer.
     */
  }, {
    key: "setRenderers",
    value: function setRenderers() {
      var _ref2 = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
        rowHeaders = _ref2.rowHeaders,
        columnHeaders = _ref2.columnHeaders,
        colGroup = _ref2.colGroup,
        rows = _ref2.rows,
        cells = _ref2.cells;
      rowHeaders.setTable(this);
      columnHeaders.setTable(this);
      colGroup.setTable(this);
      rows.setTable(this);
      cells.setTable(this);
      this.rowHeaders = rowHeaders;
      this.columnHeaders = columnHeaders;
      this.colGroup = colGroup;
      this.rows = rows;
      this.cells = cells;
    }

    /**
     * Transforms visual/rendered row index to source index.
     *
     * @param {number} rowIndex Rendered index.
     * @returns {number}
     */
  }, {
    key: "renderedRowToSource",
    value: function renderedRowToSource(rowIndex) {
      return this.rowFilter.renderedToSource(rowIndex);
    }

    /**
     * Transforms visual/rendered column index to source index.
     *
     * @param {number} columnIndex Rendered index.
     * @returns {number}
     */
  }, {
    key: "renderedColumnToSource",
    value: function renderedColumnToSource(columnIndex) {
      return this.columnFilter.renderedToSource(columnIndex);
    }

    /**
     * Renders the table.
     */
  }, {
    key: "render",
    value: function render() {
      this.colGroup.adjust();
      this.columnHeaders.adjust();
      this.rows.adjust();
      this.rowHeaders.adjust();
      this.columnHeaders.render();
      this.rows.render();
      this.rowHeaders.render();
      this.cells.render();

      // After the cells are rendered calculate columns width (or columns stretch width) to prepare proper values
      // for colGroup renderer (which renders COL elements).
      this.columnUtils.calculateWidths();
      this.colGroup.render();
      var rowsToRender = this.rowsToRender,
        rows = this.rows;

      // Fix for multi-line content and for supporting `rowHeights` option.
      for (var visibleRowIndex = 0; visibleRowIndex < rowsToRender; visibleRowIndex++) {
        var TR = rows.getRenderedNode(visibleRowIndex);
        if (TR.firstChild) {
          var sourceRowIndex = this.renderedRowToSource(visibleRowIndex);
          var rowHeight = this.rowUtils.getHeight(sourceRowIndex);
          if (rowHeight) {
            // Decrease height. 1 pixel will be "replaced" by 1px border top
            TR.firstChild.style.height = "".concat(rowHeight - 1, "px");
          } else {
            TR.firstChild.style.height = '';
          }
        }
      }
    }
  }]);
  return TableRenderer;
}();
exports.default = TableRenderer;