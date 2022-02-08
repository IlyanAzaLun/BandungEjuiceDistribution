class DataCustomer {
    user_info(handle) {
        $.ajax({
            url: location.base + 'master_information/customer/serverside_datatables_data_customer',
            method: 'POST',
            dataType: 'JSON',
            success: function (result) {
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
            success: function (result) {
                handle(result);
            }
        })
    }
}
export default DataCustomer;