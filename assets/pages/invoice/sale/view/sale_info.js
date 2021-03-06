import DataCustomer from "../data/DataCustomer.js";
import DataItems from "../data/DataItems.js";
import { sum_sub_total_item, sum_sub_total, sum_grand_total } from "./order_create-calcualtion.js";

const data_customer = new DataCustomer();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {
        data_customer.user_info_search($('input#customer_code').val(), function (output) {
            $('input#customer_code').val(output[0]['customer_code'])
            $('input#store_name').val(output[0]['store_name'])
            $('input#contact_phone').val(`${output[0]['contact_phone']} (${output[0]['owner_name']})`)
            $('textarea#address').val(`${output[0]['address']}, ${output[0]['village']}, ${output[0]['sub_district']}, ${output[0]['city']}, ${output[0]['province']}, ${output[0]['zip']}`)
            return false;
        });

        $('.currency').each(function (index, field) {
            $(field).val(currency(currencyToNum($(field).val())));
        });

        $(document).on('keyup', 'input#store_name, input#customer_code', function (event) {
            let valueElement = $(this).val();
            let selfElement = $(this);
            function getFieldNo(type) {
                var fieldNo;
                switch (type) {
                    case 'customer_code':
                        fieldNo = 1;
                        break;
                    case 'store_name':
                        fieldNo = 2;
                        break;
                    default:
                        break;
                }
                return fieldNo;
            }
            data_customer.user_info_search(valueElement, function (data) {
                let result = data.map(({
                    customer_id, customer_code, store_name, owner_name, address, village,
                    sub_district, city, province, zip, contact_phone, contact_mail,
                }) => [
                        customer_id, customer_code, store_name, owner_name, address, village,
                        sub_district, city, province, zip, contact_phone, contact_mail,
                    ]
                );
                $('input#store_name, input#customer_code').autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $('input#customer_code').val(ui.item[1])
                        $('input#store_name').val(ui.item[2])
                        $('textarea#address').val(`${ui.item[4]}, ${ui.item[5]}, ${ui.item[6]}, ${ui.item[7]}, ${ui.item[8]}, ${ui.item[9]}`)
                        return false;
                    },
                    select: function (event, ui) {
                        $('input#customer_code').val(ui.item[1])
                        $('input#store_name').val(ui.item[2])
                        $('input#contact_phone').val(`${ui.item[10]} (${ui.item[3]})`)
                        $('textarea#address').val(`${ui.item[4]}, ${ui.item[5]}, ${ui.item[6]}, ${ui.item[7]}, ${ui.item[8]}, ${ui.item[9]}`)
                        return false;
                    }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    let fieldNo = getFieldNo(selfElement.attr('id'));
                    return $('<li>').data("item.autocomplete", item)
                        .append(`<div>${item[fieldNo]}</div>`).appendTo(ul)
                }
            })
        })
        // Find Items // limit.. this overload
        $(document).on('keyup', 'input[data-id="item_code"], textarea[data-id="item_name"]', function () {
            function getFieldNo(type) {
                var fieldNo;
                switch (type) {
                    case 'item_code':
                        fieldNo = 0;
                        break;
                    case 'item_name':
                        fieldNo = 1;
                        break;
                    default:
                        break;
                }
                return fieldNo;
            }
            let parentElement = $(this).parents('tr').attr('class');
            let valueElement = $(this).val();
            let selfElement = $(this);
            data_items.item_info_search(valueElement, function (data) {
                let result = data.map(({
                    item_code, item_name, quantity, unit, capital_price, selling_price, id, note
                }) => [
                        item_code, item_name, quantity, unit, capital_price, selling_price, id, note
                    ]
                );
                $('input[data-id="item_code"], textarea[data-id="item_name"]').autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $(`.${parentElement}`).find('input[data-id="item_id"]').val(ui.item[6])
                        $(`.${parentElement}`).find('input[data-id="item_code"]').val(ui.item[0])
                        $(`.${parentElement}`).find('textarea[data-id="item_name"]').val(ui.item[1])
                        $(`.${parentElement}`).find('input[data-id="note"]').val(`${ui.item[7]}`)
                        $(`.${parentElement}`).find('input[data-id="item_quantity"]').val(`${ui.item[2]}`)
                        $(`.${parentElement}`).find('input[data-id="item_order_quantity"]').attr('max', ui.item[2])
                        $(`.${parentElement}`).find('span[data-id="item_quantity"]').text(`${ui.item[2].toUpperCase()}`)
                        $(`.${parentElement}`).find('span[data-id="item_unit"]').text(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_unit"]').val(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_selling_price"]').val(currency(currencyToNum(ui.item[5])))
                        $(`.${parentElement}`).find('input[data-id="item_capital_price"]').val(currency(currencyToNum(ui.item[4])))
                        return false
                    },
                    select: function (event, ui) {
                        $(`.${parentElement}`).find('input[data-id="item_id"]').val(ui.item[6])
                        $(`.${parentElement}`).find('input[data-id="item_code"]').val(ui.item[0])
                        $(`.${parentElement}`).find('textarea[data-id="item_name"]').val(ui.item[1])
                        $(`.${parentElement}`).find('input[data-id="note"]').val(`${ui.item[7]}`)
                        $(`.${parentElement}`).find('input[data-id="item_quantity"]').val(`${ui.item[2]}`)
                        $(`.${parentElement}`).find('input[data-id="item_order_quantity"]').attr('max', ui.item[2])
                        $(`.${parentElement}`).find('span[data-id="item_quantity"]').text(`${ui.item[2].toUpperCase()}`)
                        $(`.${parentElement}`).find('span[data-id="item_unit"]').text(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_unit"]').val(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_selling_price"]').val(currency(currencyToNum(ui.item[5])))
                        $(`.${parentElement}`).find('input[data-id="item_capital_price"]').val(currency(currencyToNum(ui.item[4])))
                        $(`.${parentElement}`).find('a#detail').attr('href', `${location.base}items/info_transaction?id=${ui.item[0]}&customer=${$('input#customer_code').val()}`)
                        return false
                    }
                })
                $.ui.autocomplete.prototype._renderItem = function (ul, item) {
                    let fieldNo = getFieldNo(selfElement.data('id'));
                    return $('<li>').data("item.autocomplete", item)
                        .append(`<div>${item[fieldNo]}</div>`).appendTo(ul)
                }
            })
        });
        // discount to currency
        $(document).on('keyup', 'input[data-id="discount"], input[data-id="total_price"], input[data-id="item_selling_price"], input[data-id="item_capital_price"]', function () {
            $(this).val(currency(currencyToNum($(this).val())));
        })

        // Description item from list
        $(document).on('click', 'button#description', function () {
            let row = $(this).parents('tr').attr('class');
            $(`.description.${row}`).toggle();
        })
        // Remove item from list
        $(document).on('click', 'button#remove', function () {
            let row = $(this).parents('tr').attr('class');
            $(`tr.${row}`).remove();
        })
    });
}
export default main;