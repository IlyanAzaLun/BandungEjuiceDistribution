import * as C from "../../../i18n/constants.mjs";
import { checkSelectionBorders, markSelected } from "../utils.mjs"; /**
                                                                     * @param {CustomBorders} customBordersPlugin The plugin instance.
                                                                     * @returns {object}
                                                                     */
export default function bottom(customBordersPlugin) {
  return {
    key: 'borders:bottom',
    name: function name() {
      var label = this.getTranslatedPhrase(C.CONTEXTMENU_ITEMS_BORDERS_BOTTOM);
      var hasBorder = checkSelectionBorders(this, 'bottom');
      if (hasBorder) {
        label = markSelected(label);
      }
      return label;
    },
    callback: function callback(key, selected) {
      var hasBorder = checkSelectionBorders(this, 'bottom');
      customBordersPlugin.prepareBorder(selected, 'bottom', hasBorder);
    }
  };
}