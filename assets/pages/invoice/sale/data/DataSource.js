class DataSource {
    user_info_search(request, handle) {
        $.ajax({
            url: location.base + 'master_information/account_bank/data_bank',
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
export default DataSource;