const main = () => {
    $(document).ready(function () {
        //Date range picker
        // Customer
        $("#supplier").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: location.base + "master_information/supplier/data_supplier",
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        'search': {
                            'value': request.term
                        },
                        'length': 10
                    },

                    success: function (data) {
                        response(data);
                    },
                });
            },
            minLength: 1,
            focus: function (event, ui) {
                console.log(ui.item)
                $('input#supplier_code').val(ui.item.customer_code);
                $(this).val(ui.item.store_name);
                return false;
            },
            select: function (event, ui) {
                $('input#supplier_code').val(ui.item.customer_code);
                $(this).val(ui.item.store_name);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $('<li>').data("item.autocomplete", item).append(`<div>${item['store_name']}</div>`).appendTo(ul);
        };
        // End Customer
    })
}
export default main;