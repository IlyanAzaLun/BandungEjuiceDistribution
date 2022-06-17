import DataCustomer from "../data/DataCustomer.js";

const data_customer = new DataCustomer();

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
                $('input#note_customer').val(`${output[0]['note'] !== "" ? output[0]['note'] : 'BED - '}`)
                return false;
            });
        }
    });
}
export default main;