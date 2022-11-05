const main = () => {
    $(document).ready(function () {
        //Date range picker
        // Customer
        function split(val) {
            return val.split(/,\s*/);
        }
        function extractLast(term) {
            return split(term).pop();
        }
        $("#customer").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: location.base + "master_information/customer/data_customer",
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        'search': {
                            'value': extractLast(request.term)
                        },
                        'length': 10
                    },

                    success: function (data) {
                        response(data);
                    },
                });
            },
            minLength: 1,
            search: function () {
                var term_name = extractLast($(this).val())
                if (term_name.length < 2) {
                    return false;
                }
            },
            focus: function (event, ui) {
                // $('input#customer_code').val(ui.item.customer_code);
                // $(this).val(ui.item.store_name);
                return false;
            },
            select: function (event, ui) {
                var term_name = split($(this).val())
                var term_code = split($('input#customer_code').val())
                // remove the current input
                term_name.pop();
                term_code.pop();
                // add selected item
                term_name.push(ui.item.store_name);
                term_code.push(ui.item.customer_code);
                // add placeholder to get the comma and space at the end
                term_name.push("");
                term_code.push("");

                $('input#customer_code').val(term_code.join(", "));
                $(this).val(term_name.join(", "));
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
                    url: location.base + "users/data_user",
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