<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$i = 0; 
foreach ($bank as $key => $value) {
    if($value->id == $invoice_information_transaction->transaction_destination){
        $bank = $bank[$key];
    }
}?>
<?php
$account_bank = "$bank->name: $bank->no_account, a/n $bank->own_by, $bank->description";
$destination = "$supplier->address $supplier->village $supplier->subdistric $supplier->city $supplier->province $supplier->zip";
$contact = "$supplier->contact_phone $supplier->contact_mail";
?>
<html>
    <head>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 5px;
                font-family: helvetica;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2px;
                margin-left: 2px;
                margin-right: 2px;
                margin-bottom: 2px;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 1.5cm;
            }
            main{                
                top: 0px;
                left: 0px; 
                right: 0px;
                border-collapse: collapse;
                width: 100%;
            }
            table{
                font-size: 9px;
                width: 100%;
            }
            tr {
                background-color: #ffff;
            }
            tr:nth-child(even) {
                background-color: #ffff;
            }
            td, th{
                text-align: left;
                padding: 2px, 2px;
            }
            .text-right{
                text-align: right;
            }
            .text-left{
                text-align: left;
            }
            .text-center{
                text-align: center;
            }
            .invoice-destination{
                vertical-align:top;
                background-color: #fff;
                color: #000;
            }
            #header{
                background-color: #e9ecef;
            }
            tr#data:nth-child(even){
                background-color: #e9ecef;
            }
        </style>
    </head>
    <body>
        <main>
            <table class="table border">
                <thead>
                    <tr>
                        <th colspan="2" rowspan="4" class="text-center"><img width="50px" src="https://external-preview.redd.it/6l63BffeDoLRZw5EiCuApqXRMrViJK5RhHjnD1eEQ0M.jpg?auto=webp&s=34697fb3fc0b37b6d78c4859f99472bf5ac2b50e" alt=""></th>
                        <th class="text-right" colspan="3">Invoice Code: </th>
                        <th class="text-left" colspan="3"><?=$__invoice_code?> </th>
                    </tr>
                    <tr>
                    <th class="text-right" colspan="3">Created at: </th>
                        <th class="text-left" colspan="3"><?=$invoice_information_transaction->created_at?> </th>
                    </tr>
                    <tr>
                        <th colspan="2" style="vertical-align:top;"><?=lang('user_address')?>: <?=$destination?></th>
                        <th colspan="2" >Date Start</th>
                        <th colspan="2" >Date Due</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="vertical-align:top;"><?=lang('contacts')?>: <?=$contact?></th>
                        <th colspan="2" class="text-left"><?=$invoice_information_transaction->date_start?> </th>
                        <th colspan="2" class="text-left"><?=$invoice_information_transaction->date_due?> </th>
                    </tr>
                    <tr id="header">
                        <th  width="1%">No.</th>
                        <th>Item Code</th>
                        <th width="40%">Item Name</th>
                        <th>Item Quantity</th>
                        <th>Item Unit</th>
                        <th>Item Price</th>
                        <th>Item Discount</th>
                        <th>Total Price Item </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_item = 0;
                    foreach ($_data_item_invoice_parent as $key => $value):?>
                    <?php if($value->item_code == $intersect_codex_item[$key] && $value->index_list == $intersect_index_item[$key]):?>
                    <tr id="data" <?=($value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity == 0)?'style="display:none;"':''?>>
                        <td ><?=$key+1?></td>
                        <td><?=$value->item_code?></td>
                        <td><?=$value->item_name?></td>
                        <td class="text-right"><?=$value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity?></td>
                        <td><?=$value->item_unit?></td>
                        <td class="text-right"><?=getCurrentcy($_data_item_invoice_child_[$i]->item_selling_price)?></td>
                        <td class="text-right"><?=getCurrentcy($_data_item_invoice_child_[$i]->discount)?></td>
                        <td class="text-right"><?=getCurrentcy($_data_item_invoice_child_[$i]->total_price)?></td>
                    </tr>
                    <?php $total_item += $value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity?>;
                    <!-- <pre>Item INDEX RETURN:<?=$value->index_list?> | Item CODE RETURN:<?=$value->item_code?> | Item Quantity RETURN:<?=$_data_item_invoice_child_[$i]->item_quantity-$value->item_quantity?></pre> -->
                    <?php $i++;?>
                    <?php else:?>
                    <tr id="data">
                        <td ><?=$key+1?></td>
                        <td><?=$value->item_code?></td>
                        <td><?=$value->item_name?></td>
                        <td class="text-right"><?=$value->item_quantity?></td>
                        <td><?=$value->item_unit?></td>
                        <td class="text-right"><?=getCurrentcy($value->item_selling_price)?></td>
                        <td class="text-right"><?=getCurrentcy($value->discount)?></td>
                        <td class="text-right"><?=getCurrentcy($value->total_price)?></td>
                    </tr>
                    <!-- <pre>Item INDEX ORDER:<?=$value->index_list?> | Item CODE ORDER:<?=$value->item_code?> | Item Quantity ORDER:<?=$value->item_quantity?></pre> -->
                    <?php $total_item += $value->item_quantity?>;
                    <?php endif;?>
                    <?php endforeach?>
                </tbody>
                <tfoot>
                    <tr>
                        <th rowspan="2" style="vertical-align:top;">Note: </th>
                        <th colspan="2" rowspan="2" class="text-left" style="vertical-align:top;"><?=$invoice_information_transaction->note?></th>
                        <th class="text-right"><b><?=$total_item?></b></th>
                        <th colspan="3" class="text-right"><?=lang('total_price')?>: </th>
                        <th class="text-right"><?=getCurrentcy($invoice_information_transaction->total_price)?></th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-right"><?=lang('discount')?>: </th>
                        <th class="text-right"><?=getCurrentcy($invoice_information_transaction->discounts)?></th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right"></th>
                        <th class="text-center">Hormat Kami</th>
                        <th colspan="2" class="text-right">Penerima</th>
                        <th colspan="2" class="text-right"><?=lang('shipping_cost')?>: </th>
                        <th class="text-right"><?=getCurrentcy($invoice_information_transaction->shipping_cost)?></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-left"></th>
                        <th colspan="4" class="text-right"><?=$invoice_information_transaction->expedition?>:</th>
                        <th class="text-left"><?=$invoice_information_transaction->services_expedition?></th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right"></th>
                        <th class="text-center">(..................................)</th>
                        <th colspan="3" class="text-center">(..................................)</th>
                        <th  class="text-right"><?=lang('status_payment')?>:</th>
                        <th class="text-left"><?=$invoice_information_transaction->status_payment?></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-left"></th>
                        <th colspan="4" class="text-right"><?=lang('grandtotal')?>: </th>
                        <th class="text-right"><?=getCurrentcy($invoice_information_transaction->grand_total)?></th>
                    </tr>
                    <tr>
                        <th colspan="8" class="text-center"><?=$account_bank?></th>
                    </tr>
                    <tr style="border: none;">
                        <td colspan="8">Copyright &copy; <?php echo date("Y");?></td>
                    </tr>
                </tfoot>
            </table>
        </main>
    </body>
</html>
