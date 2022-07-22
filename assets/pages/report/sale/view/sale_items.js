import DataItems from "../data/DataItems.js";
const data_items = new DataItems();

const main = () => {
    $(document).on('keyup', 'textarea#item_name', function () {
        let valueElement = $(this).val();
        data_items.item_info_search(valueElement, function (data) {
            let result = data.map(({
                item_code, item_name, quantity, unit, capital_price, selling_price, id, note
            }) => [
                    item_code, item_name, quantity, unit, capital_price, selling_price, id, note
                ]
            );
            $('textarea#item_name').autocomplete({
                source: result,
                focus: function (event, ui) {
                    $('input#item_id').val(ui.item[6])
                    $('input#item_code').val(ui.item[0])
                    $('textarea#item_name').val(`${ui.item[1]}`)
                    return false
                },
                select: function (event, ui) {
                    $('input#item_id').val(ui.item[6])
                    $('input#item_code').val(ui.item[0])
                    $('textarea#item_name').val(ui.item[1])
                    return false
                }
            })
            $.ui.autocomplete.prototype._renderItem = function (ul, item) {
                return $('<li>').data("item.autocomplete", item)
                    .append(`<div>${item[1]}</div>`).appendTo(ul)
            }
        })
    });
}
export default main;