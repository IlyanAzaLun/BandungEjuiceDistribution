const main = () => {
    $(document).ready(function () {
        //Date range picker
        // Customer
        $("#customer").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: location.base + "master_information/customer/data_customer",
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
                $('input#customer_code').val(ui.item.customer_code);
                $(this).val(ui.item.store_name);
                return false;
            },
            select: function (event, ui) {
                $('input#customer_code').val(ui.item.customer_code);
                $(this).val(ui.item.store_name);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $('<li>').data("item.autocomplete", item).append(`<div>${item['store_name']}</div>`).appendTo(ul);
        };
        // End Customer
        // User
        $("#users").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: location.base + "users/data_user_marketing",
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
                $('input#user_id').val(ui.item.id);
                $(this).val(ui.item.name);
                return false;
            },
            select: function (event, ui) {
                $('input#user_id').val(ui.item.id);
                $(this).val(ui.item.name);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $('<li>').data("item.autocomplete", item).append(`<div>${item['name']}</div>`).appendTo(ul);
        };
        // End User
    })
}
export default main;