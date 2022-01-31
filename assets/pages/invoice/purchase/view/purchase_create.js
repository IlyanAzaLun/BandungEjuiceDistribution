import DataCustomer from "../data/DataCustomer.js";
import DataItems from "../data/DataItems.js";

const data_customer = new DataCustomer();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {
        // Find Customer
        $('input#store_name').focus(function () {
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
                $("input#store_name, input#customer_code").on('change', function () {
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
        $('input[name="item_code"], input[name="item_name"]').hover(function () {
            data_items.items(false, function (output) {
                console.log(output)
            })
        })
        // Add item to list
        $('button#add_more').on('click', function () {
            let input_id = parseInt($('tbody tr:nth-child(n)').last().attr('class').split('-')[1]) + 1;
            let html = `
            <tr class="input-${input_id}">
                <td><input class="form-control form-control-sm" type="text" name="item_code"></td>
                <td><input class="form-control form-control-sm" type="text" name="item_name"></td>
                <td><input class="form-control form-control-sm" type="text" name="item_quantity"></td>
                <td><input class="form-control form-control-sm" type="text" name="iten_selling_price"></td>
                <td><input class="form-control form-control-sm" type="text" name="iten_order_quantity"></td>
                <td><input class="form-control form-control-sm" type="text" name="discount"></td>
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