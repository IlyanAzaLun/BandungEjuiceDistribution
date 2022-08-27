class DataUser {
    user_info_search(request, handle) {
        $.ajax({
            url: location.base + 'users/data_user',
            method: 'POST',
            dataType: 'JSON',
            data: {
                'search': {
                    'value': request,
                    'role': 7
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
export default DataUser;