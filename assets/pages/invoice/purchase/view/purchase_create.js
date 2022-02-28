import DataSupplier from "../data/DataSupplier.js";
import DataItems from "../data/DataItems.js";
import { sum_sub_total_item, sum_sub_total, sum_grand_total } from "./purchase_create-calcualtion.js";

const data_supplier = new DataSupplier();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {
        // Find Supplier // limit.. this overload
        $(document).on('keyup', 'input#store_name, input#supplier_code', function () {
            let valueElement = $(this).val();
            let selfElement = $(this);
            function getFieldNo(type) {
                var fieldNo;
                switch (type) {
                    case 'supplier_code':
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
            data_supplier.user_info_search(valueElement, function (data) {
                let result = data.map(({
                    customer_id, customer_code, store_name, owner_name, address, village,
                    sub_district, city, province, zip, contact_phone, contact_mail,
                }) => [
                        customer_id, customer_code, store_name, owner_name, address, village,
                        sub_district, city, province, zip, contact_phone, contact_mail,
                    ]
                );
                $('input#store_name, input#supplier_code').autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $('input#supplier_code').val(ui.item[1])
                        $('input#store_name').val(ui.item[2])
                        return false;
                    },
                    select: function (event, ui) {
                        $('input#supplier_code').val(ui.item[1])
                        $('input#store_name').val(ui.item[2])
                        $('input#contact_phone').val(`${ui.item[10]} (${ui.item[3]})`)
                        $('textarea#address').val(`${ui.item[4]}, ${ui.item[5]}, ${ui.item[6]}, ${ui.item[7]}, ${ui.item[8]}, ${ui.item[9]}`)
                        return false;
                    }
                })

                $.ui.autocomplete.prototype._renderItem = function (ul, item) {
                    let fieldNo = getFieldNo(selfElement.attr('id'));
                    return $("<li>").attr("id", item[fieldNo]).append(item[fieldNo]).appendTo(ul);
                }
            })
        })
        // Find Items // limit.. this overload
        $(document).on('keyup', 'input[data-id="item_code"], input[data-id="item_name"]', function () {
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
                    item_code, item_name, quantity, unit, capital_price, selling_price, id
                }) => [
                        item_code, item_name, quantity, unit, capital_price, selling_price, id
                    ]
                );
                $('input[data-id="item_code"], input[data-id="item_name"]').autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $(`.${parentElement}`).find('input[data-id="item_id"]').val(ui.item[6])
                        $(`.${parentElement}`).find('input[data-id="item_code"]').val(ui.item[0])
                        $(`.${parentElement}`).find('input[data-id="item_name"]').val(ui.item[1])
                        $(`.${parentElement}`).find('input[data-id="item_quantity"]').val(`${ui.item[2]}`)
                        $(`.${parentElement}`).find('input[data-id="item_order_quantity"]')
                        $(`.${parentElement}`).find('span[data-id="item_unit"]').text(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_unit"]').val(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_selling_price"]').val(currency(currencyToNum(ui.item[5])))
                        $(`.${parentElement}`).find('input[data-id="item_capital_price"]').val(currency(currencyToNum(ui.item[4])))
                        return false
                    },
                    select: function (event, ui) {
                        $(`.${parentElement}`).find('input[data-id="item_id"]').val(ui.item[6])
                        $(`.${parentElement}`).find('input[data-id="item_code"]').val(ui.item[0])
                        $(`.${parentElement}`).find('input[data-id="item_name"]').val(ui.item[1])
                        $(`.${parentElement}`).find('input[data-id="item_quantity"]').val(`${ui.item[2]}`)
                        $(`.${parentElement}`).find('input[data-id="item_order_quantity"]')
                        $(`.${parentElement}`).find('span[data-id="item_unit"]').text(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_unit"]').val(`${ui.item[3].toUpperCase()}`)
                        $(`.${parentElement}`).find('input[data-id="item_selling_price"]').val(currency(currencyToNum(ui.item[5])))
                        $(`.${parentElement}`).find('input[data-id="item_capital_price"]').val(currency(currencyToNum(ui.item[4])))
                        return false
                    }
                })
                $.ui.autocomplete.prototype._renderItem = function (ul, item) {
                    let fieldNo = getFieldNo(selfElement.data('id'));
                    return $("<li>").attr("data-value", item[fieldNo]).append(item[fieldNo]).appendTo(ul);
                }
            })
        });
        // discount to currency
        $(document).on('keyup', 'input[data-id="discount"], input[data-id="total_price"], input[data-id="item_selling_price"], input[data-id="item_capital_price"]', function () {
            $(this).val(currency(currencyToNum($(this).val())));
        })

        // Add item to list
        $('button#add_more').on('click', function () {
            let input_id = parseInt($('tbody tr:nth-child(n)').last().attr('class').split('-')[1]) + 1;
            let html = `
            <tr class="input-${input_id}" id="main">
                <td class="text-center"><div class="form-control form-control-sm">${input_id + 1}.</div></td>
                <td>
                    <input type="hidden" name="item_id[]" id="item_id" data-id="item_id">
                    <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" required>
                </td>
                <td><input class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required ></td>
                <td style="display:none">
                    <div class="input-group input-group-sm">
                        <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required>
                        <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit">
                        <span class="input-group-append">
                            <span class="input-group-text" data-id="item_unit"></span>
                        </span>
                    </div>
                </td>
                <td><input class="form-control form-control-sm" type="text" name="item_capital_price[]" data-id="item_capital_price" required></td>
                <td style="display:none"><input class="form-control form-control-sm" type="text" name="item_selling_price[]" data-id="item_selling_price" required></td>
                <td>
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity"  min="1" value="0" required>
                        <span class="input-group-append">
                            <span class="input-group-text" data-id="item_unit"></span>
                        </span>
                    </div>
                </td>
                <td><input class="form-control form-control-sm" type="text" name="item_discount[]" data-id="discount" min="0" max="100" value="0" required></td>
                <td><input class="form-control form-control-sm" type="text" name="total_price[]" data-id="total_price" value="0" required></td>                
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" id="description" class="btn btn-default"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                        <button type="button" id="remove" class="btn btn-block btn-danger"><i class="fa fa-tw fa-times"></i></button>
                    </div>
                </td>
            </tr>
            <tr class="description input-${input_id}" style="display:none">
                <td colspan="9">
                    <div class="input-group input-group-sm">
                        <textarea class="form-control form-control-sm" name="description[]"></textarea>
                    </td>
                </div>
            </tr>`;
            $('tbody').append(html)
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
        // get sub total items
        $(document).on('keyup', 'input[data-id="item_order_quantity"], input[data-id="discount"]', function () {
            let row = $(this).parents('tr').attr('class');
            sum_sub_total_item(row);
        })
        // get sub total
        $(document).on('focus keyup', 'input#sub_total', function (event) {
            switch (event.type) {
                case 'keyup':
                    $(this).val(currency(currencyToNum($(this).val())));
                    break;
                case 'focusin':
                    $(this).val(currency(sum_sub_total()));
                    break;
                default:
                    console.log(event.type)
                    break;
            }
        });
        // discount, shipping, other cost to currency
        $(document).on('keyup', 'input#discount, input#shipping_cost, input#other_cost', function () {
            $(this).val(currency(currencyToNum($(this).val())));
        })
        // get grand total
        $(document).on('focus keyup', 'input#grand_total', function (event) {
            switch (event.type) {
                case 'keyup':
                    $(this).val(currency(currencyToNum($(this).val())));
                    break;
                case 'focusin':
                    $(this).val(currency(sum_grand_total()));
                    break;
                default:
                    console.log(event.type)
                    break;
            }
        })
    });
}
export default main;