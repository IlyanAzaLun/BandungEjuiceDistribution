class DataSourceAccount {
    constructor() {
        // constructor
    }
    account_get(id, date, group, callback) {
        $.ajax({
            url: location.base + 'master_information/accounting',
            method: 'POST',
            dataType: 'JSON',
            data: {
                'id': id,
                'date': date,
                'group': group,
                'request': 'data',
                'type': 'profit_n_loss'
            },
        }).done(function (response) {
            console.log("success");
            callback(response)
        }).fail(function () {
            console.log("error");
        }).always(function () {
            console.log("complete");
        });
    }
}
export default DataSourceAccount;