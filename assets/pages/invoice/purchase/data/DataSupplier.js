class DataSupplier {
    user_info_search(request, handle) {
        $.ajax({
            url: location.base + 'master_information/supplier/data_supplier',
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
export default DataSupplier;