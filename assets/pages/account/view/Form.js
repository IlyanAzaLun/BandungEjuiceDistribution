class Form {
    open(request, handle) {
        $.ajax({
            url: location.base + 'master_information/account_bank/form',
            method: 'POST',
            dataType: 'html',
            data: {
                'search': {
                    'value': request,
                },
                'length': 10
            },
            beforeSend: function () {
                // $('.loading').show()
            },
            success: function (result) {
                // $('.loading').hide()
                handle(result);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })
    }
}
export default Form;