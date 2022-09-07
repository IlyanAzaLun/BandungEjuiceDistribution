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
        $(document).on('click', 'button#to_pay', function () {
            let toPayElement = $(this).parent('#to_pay');
            let toselectedchild = toPayElement.parents('tr').attr('id');
            $('table tr').removeClass('bg-primary');
            toPayElement.parents('tr').addClass('bg-primary')
            $(`tr.child-${toselectedchild}`).toggle(200, "linear", function () {
                $('[class*=child-row-]').empty()
                let html = `
                <td class="callout callout-priamary">
                    <form action="${location.base}/invoice/sales/payment/receivable_from" class="form-validate" id="to_pay" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <input type="hidden" id="id_payment" name="id_payment" value="${toPayElement.data('id')}" required>
                                    <input type="text" class="form-control" id="invoice_code" name="invoice_code" value="${toPayElement.data('code_invoice')}" readonly required>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <input type="text" class="form-control" id="created_at" name="created_at" required>
                                <small class="text-danger">*date payment !</small>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <input type="text" class="form-control currency" id="to_pay" name="to_pay" placeholder="wont to paid..." required>
                                <small class="text-danger">*numbers only !</small>
                            </div>
                            
                            <div class="col-lg-2 col-sm-12">
                                <input type="hidden" class="form-control bank_name" id="id" name="bank_id" required>
                                <input type="text" class="form-control bank_name" id="beneficiary_name" name="beneficiary_name" placeholder="search source found..." required>
                            </div>
                            <div class="col-lg">
                                <textarea class="form-control" name="note" id="note"></textarea>
                            </div>
                            <div class="col-lg-1 col-sm-12">
                            <button type="submit" class="btn btn-info btn-block start">
                                <i class="fa fa-fw fa-coins"></i>&nbsp;&nbsp;
                                <span><?=lang('payup')?></span>
                            </button>
                            </div>
                        </div>
                    </form>
                </td>`;
                $(this).append(`${html}`);
                $('#created_at').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 30,
                    // startDate: moment(toPayElement.data('date')).format('DD/MM/YYYY HH:mm:ss'),
                    startDate: moment().format('DD/MM/YYYY HH:mm:ss'),
                    locale: {
                        format: 'DD/MM/YYYY HH:mm:ss'
                    }
                })
            })
        })
        // 
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