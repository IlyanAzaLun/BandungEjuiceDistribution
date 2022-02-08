class DataItem {
    item_info_search(request = false, handle) {
        $.ajax({
            url: location.base + 'items/data_items',
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
export default DataItem;