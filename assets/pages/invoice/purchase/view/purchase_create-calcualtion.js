function sum_sub_total_item(row) {
    let item_capital_price = $(`.${row} td input[data-id='item_capital_price']`).val();
    let item_selling_price = $(`.${row} td input[data-id='item_selling_price']`).val();
    let item_order_quantity = $(`.${row} td input[data-id='item_order_quantity']`).val();
    let item_discount = $(`.${row} td input[data-id='discount']`).val();
    let total_price = $(`.${row} td input[data-id='total_price']`);
    total_price.val(
        currency((currencyToNum(item_capital_price) * parseInt(item_order_quantity)) - currencyToNum(item_discount))
    )
}

function sum_sub_total_item_returns(row) {
    let item_capital_price = $(`.${row} td input[data-id='item_capital_price']`).val();
    let item_selling_price = $(`.${row} td input[data-id='item_selling_price']`).val();
    let item_order_quantity = $(`.${row} td input[data-id='item_order_quantity']`).val();
    let item_order_quantity_current = $(`.${row} td input[data-id='item_order_quantity_current']`).val();
    let item_discount = $(`.${row} td input[data-id='discount']`).val();
    let total_price_current = $(`.${row} td input[data-id='total_price_current']`).val();
    let total_price = $(`.${row} td input[data-id='total_price']`);
    if (currencyToNum(item_discount) == (currencyToNum(item_capital_price) * parseInt(item_order_quantity_current))) {
        total_price.val(0)
    } else {
        total_price.val(
            currency(currencyToNum(total_price_current) - (currencyToNum(item_capital_price) * parseInt(item_order_quantity)))
        )
    }
}

function sum_sub_total() {
    let _total = [];
    let unique_values = [];
    let list_of_value = [];
    $('tbody>tr#main').each(function (key, value) {
        _total[key] = 0;
        let row = $(this).attr('class');
        let item_code = $(`.${row} td input[data-id='item_code']`).val();
        let item_quantity = $(`.${row} td input[data-id='item_quantity']`).val();
        let item_selling_price = $(`.${row} td input[data-id='item_selling_price']`).val();
        let item_capital_price = $(`.${row} td input[data-id='item_capital_price']`).val();
        let item_order_quantity = $(`.${row} td input[data-id='item_order_quantity']`).val();
        let item_discount = $(`.${row} td input[data-id='discount']`).val();
        let total_price = $(`.${row} td input[data-id='total_price']`).val();
        list_of_value.push({
            'item_code': item_code,
            'item_quantity': item_quantity,
            'item_selling_price': item_selling_price,
            'item_capital_price': item_capital_price,
            'item_order_quantity': item_order_quantity,
            'item_discount': item_discount,
            'total_price': total_price,
        })
    });
    /*
    let result = list_of_value.reduce((list_of_value, value) => list_of_value.set(
        value.item_code,
        (list_of_value.get(value.item_code) || 0) + Number(value.item_order_quantity),
    ), new Map);
    */
    list_of_value = list_of_value.reduce((index, value) => {
        let key = index.findIndex((event) => event.item_code == value.item_code);
        if (key == -1) {
            index.push({
                item_code: value.item_code,
                item_quantity: value.item_quantity,
                item_selling_price: value.item_selling_price,
                item_capital_price: value.item_capital_price,
                item_order_quantity: parseFloat(value.item_order_quantity),
                item_discount: value.item_discount,
                total_price: parseFloat(currencyToNum(value.total_price))
            });
        } else {
            index[key].item_order_quantity += parseFloat(value.item_order_quantity)
            index[key].total_price += parseFloat(currencyToNum(value.total_price))
        }
        return index
    }, []);

    let sub_total = list_of_value.reduce(function (sum, current) {
        return sum + current.total_price;
    }, 0)
    return sub_total;
}

function sum_grand_total() {
    let sub_total = Number(currencyToNum($('input#sub_total').val()));
    let discount = Number(currencyToNum($('input#discount').val()));
    let shipping_cost = Number(currencyToNum($('input#shipping_cost').val()));
    let other_cost = Number(currencyToNum($('input#other_cost').val()));

    let grand_total = sub_total - discount + shipping_cost + other_cost;

    return grand_total;
}

export { sum_sub_total_item, sum_sub_total_item_returns, sum_sub_total, sum_grand_total };