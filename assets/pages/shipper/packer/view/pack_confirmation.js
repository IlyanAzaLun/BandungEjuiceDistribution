import DataCustomer from "../data/DataCustomer.js";
import DataUser from "../data/DataUser.js";

const data_customer = new DataCustomer();
const data_user = new DataUser();

const main = () => {
    $(document).ready(function () {
        let customer_code = $('input#customer_code').val();
        if (isNaN(customer_code)) {
            data_customer.user_info_search(customer_code, function (output) {
                console.log(output)
                $('input#customer_code').val(output[0]['customer_code'])
                $('input#store_name').val(output[0]['store_name'])
                $('input#owner_name').val(`${output[0]['owner_name']}`)
                $('input#contact_phone').val(`${output[0]['contact_phone']}`)
                $('textarea#address').val(`${output[0]['address'] ? output[0]['address'] : ''} ${output[0]['village'] ? output[0]['village'] : ''} ${output[0]['sub_district'] ? output[0]['sub_district'] : ''} ${output[0]['city'] ? output[0]['city'] : ''} ${output[0]['province'] ? output[0]['province'] : ''} ${output[0]['zip'] ? output[0]['zip'] : ''}`)
                $('input#contact_us').val(`${output[0]['contact_us'] !== "" ? output[0]['contact_us'] : 'BED - '}`)
                return false;
            });
        }
    });

    $(document).ready(function () {
        let requestmame = $('input#pack_by').val();
        if (isNaN(requestmame)) {
            data_user.user_info_search(requestmame, function (output) {
                console.log(output)
                // $('input#pack_by').val(output[0]['pack_by'])
                return false;
            });
        }
    });

    $(document).on('keyup', 'input#pack_by', function () {
        let valueElement = $(this).val();
        let selfElement = $(this);
        data_user.user_info_search(valueElement, function (data) {
            let result = data.map(({
                id, name, username,
            }) => [
                    id, name, username,
                ]
            );
            $(`input#${selfElement.attr('id')}`).autocomplete({
                source: result,
                focus: function (event, ui) {
                    $('input#pack_by').val(ui.item[1])
                    return false;
                },
                select: function (event, ui) {
                    $('input#pack_by').val(ui.item[1])
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $('<li>').data("item.autocomplete", item)
                    .append(`<div>${item[1]}</div>`).appendTo(ul)
            }
        })
    })
}
export default main;