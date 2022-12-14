import "core-js/modules/es.regexp.exec.js";
import moment from 'moment';

// Formats which are correctly parsed to time (supported by momentjs)
var STRICT_FORMATS = ['YYYY-MM-DDTHH:mm:ss.SSSZ', 'X',
// Unix timestamp
'x' // Unix ms timestamp
];

export var VALIDATOR_TYPE = 'time';

/**
 * The Time cell validator.
 *
 * @private
 * @param {*} value Value of edited cell.
 * @param {Function} callback Callback called with validation result.
 */
export function timeValidator(value, callback) {
  var timeFormat = this.timeFormat || 'h:mm:ss a';
  var valid = true;
  var valueToValidate = value;
  if (valueToValidate === null) {
    valueToValidate = '';
  }
  valueToValidate = /^\d{3,}$/.test(valueToValidate) ? parseInt(valueToValidate, 10) : valueToValidate;
  var twoDigitValue = /^\d{1,2}$/.test(valueToValidate);
  if (twoDigitValue) {
    valueToValidate += ':00';
  }
  var date = moment(valueToValidate, STRICT_FORMATS, true).isValid() ? moment(valueToValidate) : moment(valueToValidate, timeFormat);
  var isValidTime = date.isValid();

  // is it in the specified format
  var isValidFormat = moment(valueToValidate, timeFormat, true).isValid() && !twoDigitValue;
  if (this.allowEmpty && valueToValidate === '') {
    isValidTime = true;
    isValidFormat = true;
  }
  if (!isValidTime) {
    valid = false;
  }
  if (!isValidTime && isValidFormat) {
    valid = true;
  }
  if (isValidTime && !isValidFormat) {
    if (this.correctFormat === true) {
      // if format correction is enabled
      var correctedValue = date.format(timeFormat);
      var row = this.instance.toVisualRow(this.row);
      var column = this.instance.toVisualColumn(this.col);
      this.instance.setDataAtCell(row, column, correctedValue, 'timeValidator');
      valid = true;
    } else {
      valid = false;
    }
  }
  callback(valid);
}
timeValidator.VALIDATOR_TYPE = VALIDATOR_TYPE;