import DataCustomer from "../data/DataCustomer.js";
import DataItems from "../data/DataItems.js";
import { sum_sub_total_item, sum_sub_total, sum_grand_total } from "./order_create-calcualtion.js";

const data_custommer = new DataCustomer();
const data_items = new DataItems();

const main = () => {
    $(document).ready(function () {

        data_custommer.user_info_search($('input#customer_code').val(), function (output) {
            $('input#customer_code').val(output[0]['customer_code'])
            $('input#store_name').val(output[0]['store_name'])
            $('input#contact_phone').val(`${output[0]['contact_phone']} (${output[0]['owner_name']})`)
            $('textarea#address').val(`${output[0]['address']}, ${output[0]['village']}, ${output[0]['sub_district']}, ${output[0]['city']}, ${output[0]['province']}, ${output[0]['zip']}`)
            return false;
        });
    });
}
export default main;