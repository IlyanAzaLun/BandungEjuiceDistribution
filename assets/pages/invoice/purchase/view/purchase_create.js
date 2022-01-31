import DataCustomer from "../data/DataCustomer.js";
import DataItems from "../data/DataItems.js";

const data_customer = new DataCustomer();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {
        // Find Customer
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
        // Find Items
        $(document).on('focus', 'input[name="item_code"], input[name="item_name"]', function () {
            data_items.items(function (data) {
                let item_code = [];
                let item_name = [];
                let item_quantity = [];
                let item_selling_price = [];
                let item_capital_price = [];
                let item_unit = [];
                $(data.aaData).each(function (index, field) {
                    item_code.push(field.item_code)
                    item_name.push(field.item_name)
                })
                $('input[name="item_code"]').autocomplete({
                    source: item_code,
                })
                $('input[name="item_name"]').autocomplete({
                    source: item_name,
                })
                $('input[name="item_code"], input[name="item_name"]').change(function () {
                    let parentElement = $(this).parents('tr').attr('class');
                    data_items.item_info_search($(this).val(), function (data) {
                        $(`.${parentElement}`).find('input[name="item_code"]').val(data.aaData[0].item_code)
                        $(`.${parentElement}`).find('input[name="item_name"]').val(data.aaData[0].item_name)
                        $(`.${parentElement}`).find('input[name="item_quantity"]').val(`${data.aaData[0].item_quantity} (${data.aaData[0].item_unit})`)
                        $(`.${parentElement}`).find('input[name="item_selling_price"]').val(data.aaData[0].item_selling_price)
                        $(`.${parentElement}`).find('input[name="item_capital_price"]').val(data.aaData[0].item_capital_price)
                    })
                })
                $.ui.autocomplete.prototype._renderItem = function (ul, item) {
                    return $("<li>").attr("data-value", item.value).append(item.label).appendTo(ul);
                }
            })
        });

        // Add item to list
        $('button#add_more').on('click', function () {
            let input_id = parseInt($('tbody tr:nth-child(n)').last().attr('class').split('-')[1]) + 1;
            let html = `
            <tr class="input-${input_id}">
                <td><input class="form-control form-control-sm" type="text" name="item_code" required></td>
                <td><input class="form-control form-control-sm" type="text" name="item_name" required></td>
                <td><input class="form-control form-control-sm" type="text" name="item_quantity" required></td>
                <td><input readonly class="form-control form-control-sm" type="text" name="item_capital_price" required></td>
                <td><input readonly class="form-control form-control-sm" type="text" name="item_selling_price" required></td>
                <td><input class="form-control form-control-sm" type="number" name="item_order_quantity" min="0" value="0" required></td>
                <td><input class="form-control form-control-sm" type="number" name="discount" min="0" max="100" value="0" required></td>
                <td><button type="button" id="remove" class="btn btn-block btn-danger"><i class="fa fa-tw fa-times"></i></button></td>
            </tr>`;
            $('tbody').append(html)
        })
        // Remove item from list
        $(document).on('click', 'button#remove', function () {
            $(this).parents('tr').remove();
        })
    })
}
export default main;