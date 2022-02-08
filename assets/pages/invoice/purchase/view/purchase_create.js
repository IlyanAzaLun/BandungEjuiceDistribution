import DataSupplier from "../data/DataSupplier.js";
import DataItems from "../data/DataItems.js";
import { sum_sub_total, sum_grand_total } from "./purchase_create_calcualtion.js";

const data_supplier = new DataSupplier();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {
        //PR
        sum_sub_total();
        sum_grand_total();
        //PR
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
            console.log(parentElement);
            data_items.item_info_search(valueElement, function (data) {
                let result = data.map(({
                    item_code, item_name, quantity, unit, capital_price, selling_price
                }) => [
                        item_code, item_name, quantity, unit, capital_price, selling_price
                    ]
                );
                $('input[data-id="item_code"], input[data-id="item_name"]').autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $(`.${parentElement}`).find('input[data-id="item_code"]').val(ui.item[0])
                        $(`.${parentElement}`).find('input[data-id="item_name"]').val(ui.item[1])
                        $(`.${parentElement}`).find('input[data-id="item_quantity"]').val(`${ui.item[2]}`)
                        $(`.${parentElement}`).find('span[data-id="item_unit"]').text(`${ui.item[3]}`)
                        return false
                    },
                    select: function (event, ui) {
                        $(`.${parentElement}`).find('input[data-id="item_code"]').val(ui.item[0])
                        $(`.${parentElement}`).find('input[data-id="item_name"]').val(ui.item[1])
                        $(`.${parentElement}`).find('input[data-id="item_quantity"]').val(`${ui.item[2]}`)
                        $(`.${parentElement}`).find('span[data-id="item_unit"]').text(`${ui.item[3]}`)
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

        // Add item to list
        $('button#add_more').on('click', function () {
            let input_id = parseInt($('tbody tr:nth-child(n)').last().attr('class').split('-')[1]) + 1;
            let html = `
                <tr tr class= "input-${input_id}" >
                <td><input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" required></td>
                <td><input class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required ></td>
                <td>
                    <div class="input-group input-group-sm">
                        <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required>
                        <span class="input-group-append">
                            <span class="input-group-text" data-id="item_unit"></span>
                        </span>
                    </div>
                </td>
                <td><input readonly class="form-control form-control-sm" type="text" name="item_capital_price[]" data-id="item_selling_price" required></td>
                <td><input readonly class="form-control form-control-sm" type="text" name="item_selling_price[]" data-id="item_capital_price" required></td>
                <td><input class="form-control form-control-sm" type="number" name="item_order_quantity[]"  min="0" value="0" required></td>
                <td><input class="form-control form-control-sm" type="number" name="discount[]"  min="0" max="100" value="0" required></td>
                <td><button type="button" id="remove" class="btn btn-block btn-danger"><i class="fa fa-tw fa-times"></i></button></td>
            </tr> `;
            $('tbody').append(html)
        })
        // Remove item from list
        $(document).on('click', 'button#remove', function () {
            $(this).parents('tr').remove();
        })
    })
}
export default main;