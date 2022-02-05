import DataCustomer from "../data/DataCustomer.js";
import DataItems from "../data/DataItems.js";

const data_customer = new DataCustomer();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {
        // Find Customer // limit.. this overload
        $(document).on('focus', 'input#store_name, input#customer_code', function () {
            data_customer.user_info(function (output) {
                let store_name = [];
                let customer_code = [];
                $(output.aaData).each(function (index, field) {
                    store_name.push(field.store_name)
                    customer_code.push(field.customer_code)
                })
                $.ui.autocomplete.prototype._renderItem = function (ul, item) {
                    return $("<li>").attr("data-value", item.value).append(item.label).appendTo(ul);
                }
                $("input#store_name").autocomplete({
                    source: store_name
                })
                $("input#customer_code").autocomplete({
                    source: customer_code
                })
                $("input#store_name, input#customer_code").change(function () {
                    data_customer.user_info_search($(this).val(), function (data) {
                        $('input#store_name').val(data.aaData[0].store_name);
                        $('input#customer_code').val(data.aaData[0].customer_code);
                        $('input#contact_phone').val(`${data.aaData[0].contact_phone} (${data.aaData[0].owner_name})`);
                        $('textarea#address').val(`${data.aaData[0].village}, ${data.aaData[0].sub_district}, ${data.aaData[0].city}, ${data.aaData[0].province}, ${data.aaData[0].zip}`);
                    })
                })
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
                let item_code = [];
                let item_name = [];
                let item_quantity = [];
                let item_selling_price = [];
                let item_capital_price = [];
                let item_unit = [];
                // let result = data.map(Object.values)
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
                        return false
                    },
                    select: function (event, ui) {
                        $(`.${parentElement}`).find('input[data-id="item_code"]').val(ui.item[0])
                        $(`.${parentElement}`).find('input[data-id="item_name"]').val(ui.item[1])
                        $(`.${parentElement}`).find('input[data-id="item_quantity"]').val(`${ui.item[2]} (${ui.item[3]})`)
                        $(`.${parentElement}`).find('input[data-id="item_selling_price"]').val(ui.item[5])
                        $(`.${parentElement}`).find('input[data-id="item_capital_price"]').val(ui.item[6])
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
                <td><input class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required></td>
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