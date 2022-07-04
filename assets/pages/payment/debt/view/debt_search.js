import DataCustomer from "../data/DataCustomer.js";
import DataSupplier from "../data/DataSupplier.js";

const data_customer = new DataCustomer();
const data_supplier = new DataSupplier();

const main = () => {
    $(document).ready(function () {
        $(document).on('keyup', 'input#supplier_name', function () {
            let valueElement = $(this).val();
            let selfElement = $(this);
            data_supplier.user_info_search(valueElement, function (data) {
                let result = data.map(({
                    customer_id, customer_code, store_name, owner_name,
                }) => [
                        customer_id, customer_code, store_name, owner_name,
                    ]
                );
                $(`input#${selfElement.attr('id')}`).autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $('input#supplier_code').val(ui.item[1])
                        $('input#supplier_name').val(ui.item[2])
                        return false;
                    },
                    select: function (event, ui) {
                        $('input#supplier_code').val(ui.item[1])
                        $('input#supplier_name').val(ui.item[2])
                        return false;
                    }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $('<li>').data("item.autocomplete", item)
                        .append(`<div>${item[2]}</div>`).appendTo(ul)
                }
            })
        })
        // Find customer // limit.. this overload
        $(document).on('keyup', 'input#customer_name', function () {
            let valueElement = $(this).val();
            let selfElement = $(this);
            data_customer.user_info_search(valueElement, function (data) {
                let result = data.map(({
                    customer_id, customer_code, store_name,
                }) => [
                        customer_id, customer_code, store_name,
                    ]
                );
                $(`input#${selfElement.attr('id')}`).autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $('input#customer_code').val(ui.item[1])
                        $('input#customer_name').val(ui.item[2])
                        return false;
                    },
                    select: function (event, ui) {
                        $('input#customer_code').val(ui.item[1])
                        $('input#customer_name').val(ui.item[2])
                        return false;
                    }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $('<li>').data("item.autocomplete", item)
                        .append(`<div>${item[2]}</div>`).appendTo(ul)
                }
            })
        })
    })
}
export default main;