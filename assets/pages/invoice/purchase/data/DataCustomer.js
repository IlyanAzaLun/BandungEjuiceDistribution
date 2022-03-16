class DataCustomer {
    user_info(handle) {
        $.ajax({
            url: location.base + 'master_information/customer/serverside_datatables_data_customer',
            method: 'POST',
            dataType: 'JSON',
            beforeSend: function () {
                // $('.loading').show()
            },
            success: function (result) {
                // $('.loading').hide()
                handle(result);
            }
        })
    }
    user_info_search(request, handle) {
        $.ajax({
            url: location.base + 'master_information/customer/serverside_datatables_data_customer',
            method: 'POST',
            dataType: 'JSON',
            data: {
                'search': {
                    'value': request
                },
                'length': 10
            },
            beforeSend: function () {
                // $('.loading').show()
            },
            success: function (result) {
                // $('.loading').hide()
                handle(result);
            }
        })
    }
}
export default DataCustomer;