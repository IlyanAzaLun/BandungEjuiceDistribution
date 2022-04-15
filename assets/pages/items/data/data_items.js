class DataSourceItem {
    constructor() {
        // constructor
    }
    itemComponentAdd(callback) {
        $.ajax({
            url: location.base + 'items/add/component',
            method: 'POST',
            dataType: 'HTML',
            data: { request: 'GET COMPONENT' },
        }).done(function (response) {
            console.log("success");
            callback(response)
        }).fail(function () {
            console.log("error");
        }).always(function () {
            console.log("complete");
        });
    }
    getCode(type) {
        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
        function sub(data) {
            let result = [];
            $.each(data.split('-'), function (index, field) {
                result.push(field.charAt(0))
            })
            return result.join('');
        }
        $.ajax({
            url: location.base + 'items/add/getItemCode',
            method: 'POST',
            dataType: 'JSON',
            data: { request: 'GET COMPONENT', data: type },
        }).done(function (result) {
            $('input#item_code').val(`${$('.category').find(':selected').val()}${($('.subcategory').find(':selected').val())
                ? `-${sub($('.subcategory').find(':selected').val())}-`
                : `-`}${pad(result + 1, 6)}`)
        }).fail(function () {
            console.log("error");
        }).always(function () {
            console.log("complete");
        });
    }
}
export default DataSourceItem;