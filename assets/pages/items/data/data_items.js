class DataSourceItem {
    constructor() {
        this.BASEURL = location.href + '/';
    }
    itemComponentAdd(callback) {
        $.ajax({
            url: this.BASEURL + 'component',
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
            url: this.BASEURL + 'getItemCode',
            method: 'POST',
            dataType: 'JSON',
            data: { request: 'GET COMPONENT', data: type },
        }).done(function (result) {
            $('input#item_code').val(`${$('.category').find(':selected').data('id').substring(0, 3)}${($('.subcategory').find(':selected').data('id'))
                ? `-${sub($('.subcategory').find(':selected').data('id'))}-`
                : `-`}${pad(result + 1, 6)}`)
        }).fail(function () {
            console.log("error");
        }).always(function () {
            console.log("complete");
        });
    }
}
export default DataSourceItem;