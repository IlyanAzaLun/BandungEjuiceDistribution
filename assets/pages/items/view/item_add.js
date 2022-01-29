import DataSourceItem from "../data/data_items.js";
const dataSourceItem = new DataSourceItem();

const main = () => {
    // category code //
    $('select#category').on('change', function (event) {
        if ($(this).find(':selected').data('id') == 'LIQUID') {
            dataSourceItem.itemComponentAdd(function (output) {
                $('.category').after(output);
                $('#customs').inputmask('yyyy', { 'placeholder': 'yyyy' });
            })
        } else {
            $('.subcategory').remove();
        }
        // after select category, on focus name field, get code 
        // category code //		
        $('input#item_name.form-control', 'form').on('focus', function () {
            dataSourceItem.getCode(($('div.subcategory').find(':selected').val())
                ? $('div.subcategory').find(':selected').val()
                : $('div.category').find(':selected').val())
        })
    });
    // uppercase name
    $('input#item_name').focusout(function () {
        $(this).val($(this).val().toUpperCase())
    })

    // price formater
    $('input#capital_price, input#selling_price').keyup(function () {
        $(this).val(currency(
            currencyToNum($(this).val())
        ));
    })
    // label changed import file
    $('input#file').change(function () {
        $('.custom-file-label').text(this.files[0]['name']);
    });

};
export default main;