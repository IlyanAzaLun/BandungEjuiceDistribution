import DataCustomer from "../data/DataCustomer.js";
import DataSupplier from "../data/DataSupplier.js";
import DataSource from "../data/DataSource.js";

const data_customer = new DataCustomer();
const data_supplier = new DataSupplier();
const data_source = new DataSource();

const main = () => {
    $(document).ready(function () {
        // discount to currency
        $(document).on('keyup', 'input.currency', function () {
            $(this).val(currency(currencyToNum($(this).val())));
        })
        $('.currency').each(function (index, field) {
            $(field).val(currency(currencyToNum($(field).val())));
        });

        $(document).on('keyup', 'input#beneficiary_name', function () {
            let valueElement = $(this).val();
            let selfElement = $(this);
            data_source.user_info_search(valueElement, function (data) {
                let result = data.map(({
                    id, name, no_account, own_by,
                }) => [
                        id, name, no_account, own_by,
                    ]
                );
                $(`input#${selfElement.attr('id')}`).autocomplete({
                    source: result,
                    focus: function (event, ui) {
                        $('input#id').val(ui.item[0])
                        $('input#beneficiary_name').val(`${ui.item[1]}/${ui.item[3]}`)
                        return false;
                    },
                    select: function (event, ui) {
                        $('input#id').val(ui.item[0])
                        $('input#beneficiary_name').val(`${ui.item[1]}/${ui.item[3]}`)
                        return false;
                    }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $('<li>').data("item.autocomplete", item)
                        .append(`<div>${item[1]} / ${item[3]} / ${item[2]}</div>`).appendTo(ul)
                }
            })
        })
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
        // To Edit
        $(document).on('click', 'button#edit_pay', function () {
            let toPayElement = $(this).parent('#edit_pay');
            data_source.payment_information(toPayElement.data('id'), function (callback) {
                if (callback != null) {
                    $("div.card#to_pay").toggle(200, "linear", function () {
                        $('table tr').removeClass('bg-primary');
                        $('#id_payment').val(toPayElement.data('id'));
                        $('#invoice_code').val(toPayElement.data('code_invoice'));
                        $('input#to_pay').val(callback.payup !== null ? currency(callback.payup) : 0);
                        $('input#bank_id').val(currency(callback.bank_id));
                        $('input#beneficiary_name').val(`${callback.name}/${callback.own_by}`);
                        $('textarea#note').val(`${callback.description}`);
                        $('input#created_at').val(`${moment(callback.created_at).format('DD/MM/YYYY HH:mm:ss')}`);
                        $('form#to_pay').attr('action', `${location.base}invoice/sales/payment/edit_receivable`);
                        toPayElement.parents('tr').addClass('bg-primary');
                    });
                }
                else {
                    alert('This Parent of invoice, please edit on parent invoice!');
                }
            })
        })
        // TO DELETE
        $(document).on('click', 'button#remmove_pay_button', function () {
            let id = $(this).data('id');
            data_source.payment_information(id, function (callback) {
                if (callback != null) {
                    $('#remmove_pay').on('shown.bs.modal', function () {
                        $(this).find('input#id').val(id);
                        $(this).find('textarea#note').prop('required', true)
                    })
                }
                else {
                    alert('This Parent of invoice, please delete on parent invoice!');
                    $('#remmove_pay').on('shown.bs.modal', function () {
                        $('#remmove_pay').modal('hide');
                    });
                }
            })
        });
        // To Pay
        $(document).on('click', 'button#to_pay', function () {
            let toPayElement = $(this);
            $("div.card#to_pay").toggle(200, "linear", function () {
                $('table tr').removeClass('bg-primary');
                $('#id_payment').val(toPayElement.data('id'));
                $('#invoice_code').val(toPayElement.data('code_invoice'));
                $('input#to_pay').val("");
                $('input#bank_id').val("");
                $('input#beneficiary_name').val("");
                $('textarea#note').val("");
                $('input#created_at').val(`${moment().format('DD/MM/YYYY HH:mm:ss')}`);
                $('form#to_pay').attr('action', `${location.base}invoice/sales/payment/receivable_from`);
            });
        })
        $('#to_pay').on('removed.lte.cardwidget', function () {
            $('table tr').removeClass('bg-primary');
        });
        //validation to pay
        $(document).on('submit', 'form#to_pay', function (event) {
            let currency = Number($('input.currency').val())
            if (currency <= 0) {
                event.preventDefault();
                $('input.currency').addClass('is-invalid');
                $('input.currency').focus();
                $('.loading').css({ "display": "none" });
            }
        })
    })
}
export default main;