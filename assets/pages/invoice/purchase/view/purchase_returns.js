import DataSupplier from "../data/DataSupplier.js";
import DataItems from "../data/DataItems.js";
import { sum_sub_total_item, sum_sub_total_item_returns, sum_sub_total, sum_grand_total } from "./purchase_create-calcualtion.js";

const data_supplier = new DataSupplier();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {

        data_supplier.user_info_search($('input#supplier_code').val(), function (output) {
            $('input#supplier_code').val(output[0]['customer_code'])
            $('input#store_name').val(output[0]['store_name'])
            $('input#contact_phone').val(`${output[0]['contact_phone']} (${output[0]['owner_name']})`)
            $('textarea#address').val(`${output[0]['address']}, ${output[0]['village']}, ${output[0]['sub_district']}, ${output[0]['city']}, ${output[0]['province']}, ${output[0]['zip']}`)
            return false;
        });
        $('.currency').each(function (index, field) {
            $(field).val(currency(currencyToNum($(field).val())));
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
        // update item from list
        $(document).on('click', 'button#update', function () {
            let row = $(this).parents('tr').attr('class');
            $(`.${row}`).toggle();
        })
        // field and item is sefty
        $(document).on('click', 'button#is_safty', function () {
            let row = $(this).parents('tr').attr('class');
            let main_row = $(`#main.${row}`);
            main_row.find('input[data-id="item_order_quantity"]').val(0);
            main_row.find('input[data-id="total_price"]').val(main_row.find('input[data-id="total_price_current"]').val());
            $(`.${row}`).toggle();
            $(`.description.${row}`).hide();
        })
        // get sub total items
        $(document).on('keyup', 'input[data-id="item_order_quantity"], input[data-id="item_capital_price"], input[data-id="discount"]', function () {
            let row = $(this).parents('tr').attr('class');
            sum_sub_total_item_returns(row);

            if ($('input#is_consignment').is(':checked')) {
                $('input#sub_total, input#grand_total').val(0);

                // Grand Total
                $('input#grand_total').val(currency(sum_grand_total()));
            } else {
                //Sub total
                $('input#sub_total').val(currency(sum_sub_total()));

                // Grand Total
                $('input#grand_total').val(currency(sum_grand_total()));
            }

        })
        // get sub total
        // $('input#sub_total').val(currency(sum_sub_total()));
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

            // get grand total
            $('input#grand_total').val(currency(sum_grand_total()));

        })
        // get grand total
        // $('input#grand_total').val(currency(sum_grand_total()));
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
        });

        $('#payment_type').on('change', function () {
            if ($(this).val() == 'cash') {
                $('#destination').attr('required', 'required');
                return $('#source_destination').show();
            }
            $('#destination').removeAttr('required');
            return $('#source_destination').hide();
        })

        //if is consignment
        $(document).on('change', 'input#is_consignment', function (event) {
            if ($('input#is_consignment').is(':checked')) {
                $('input#sub_total, input#grand_total').val(0);

                // Grand Total
                $('input#grand_total').val(currency(sum_grand_total()));
            } else {
                //Sub total
                $('input#sub_total').val(currency(sum_sub_total()));

                // Grand Total
                $('input#grand_total').val(currency(sum_grand_total()));
            }
        })
        if ($('input#is_consignment').is(':checked')) {
            $('input#sub_total, input#grand_total').val(0);

            // Grand Total
            $('input#grand_total').val(currency(sum_grand_total()));
        } else {
            //Sub total
            $('input#sub_total').val(currency(sum_sub_total()));

            // Grand Total
            $('input#grand_total').val(currency(sum_grand_total()));
        }
    });
}
export default main;