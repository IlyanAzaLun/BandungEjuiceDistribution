class DataItem {
    items(request = false, handle) {
        $.ajax({
            url: location.base + 'items/serverside_datatables_data_items',
            method: 'POST',
            dataType: 'JSON',
            data: {
                'search': {
                    'value': request
                }
            },
            success: function (result) {
                handle(result);
            }
        })
    }
}
export default DataItem;