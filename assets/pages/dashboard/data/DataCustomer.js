class DataCustomer {
    customer_info_search(request, handle) {
        $.ajax({
            url: location.base + 'master_information/customer/data_customer',
            method: 'POST',
            dataType: 'JSON',
            data: {
                'search': {
                    'value': request,
                    'role': 3
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