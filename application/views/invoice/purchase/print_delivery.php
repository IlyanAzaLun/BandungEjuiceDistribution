<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$account_bank = "$bank->name: $bank->no_account, a/n $bank->own_by";
$destination = "$customer->address $customer->village $customer->subdistric $customer->city $customer->province $customer->zip";
$contact = "$customer->contact_phone $customer->contact_mail";
$i = 0;
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
                margin-top: 4px;
                margin-left: 20px;
                margin-right: 20px;
                margin-bottom: 4px;
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
                background-color: #fff;
            }
            tr:nth-child(even) {
                background-color: #fff;
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
                        <th colspan="8" class="text-left"><h3><?=lang('purchase_return_note')?></h3></th>
                    </tr>
                    <tr>
                        <th colspan="2" rowspan="4" class="text-center"><h1 style="font-family: 'monospace';font-color: 'coral'">B.<span style="color:#FFBF00">E</span>.D</h1></th>
                        <!-- <th colspan="2" rowspan="4" class="text-center"><img width="50px" src="<?php echo url('uploads/company/').setting('company_icon')?>" alt=""></th> -->
                        <th colspan="2"></th>
                        <th colspan="2">Invoice Code</th>
                        <th class="text-left" colspan="2">:<?=$_data_item_invoice_parent[0]->invoice_code?> </th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th colspan="2">Created At</th>
                        <th class="text-left" colspan="2">:<?=date_format(date_create($invoice_information_transaction->created_at),"d/M/Y")?> </th>
                    </tr>
                    <tr>
                        <th colspan="2" style="vertical-align:top;"><?=lang('user_address')?>: <?=$destination?></th>
                        <th colspan="2" >Date Start</th>
                        <th colspan="2" >Date Due</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="vertical-align:top;"><?=lang('contacts')?>: <?=$contact?></th>
                        <th colspan="2" class="text-left"><?=date_format(date_create($invoice_information_transaction->date_start),"d/M/Y")?> </th>
                        <th colspan="2" class="text-left"><?=date_format(date_create($invoice_information_transaction->date_due),"d/M/Y")?> </th>
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
                    <tr id="data" <?=(($value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity) == $value->item_quantity)?'style="display:none;"':''?>>
                        <td ><?=$key+1?></td>
                        <td><?=$value->item_code?></td>
                        <td><?=$value->item_name?></td>
                        <td class="text-right"><?=$_data_item_invoice_child_[$i]->item_quantity?></td>
                        <td><?=$value->item_unit?></td>
                        <td class="text-right"><?=getCurrentcy($_data_item_invoice_child_[$i]->item_capital_price)?></td>
                        <td class="text-right"><?=getCurrentcy($_data_item_invoice_child_[$i]->discount)?></td>
                        <td class="text-right"><?=getCurrentcy($value->total_price- $_data_item_invoice_child_[$i]->total_price)?></td>
                    </tr>
                    <?php $total_item += $_data_item_invoice_child_[$i]->item_quantity?>;
                    <!-- <pre>Item INDEX RETURN:<?=$value->index_list?> | Item CODE RETURN:<?=$value->item_code?> | Item Quantity RETURN:<?=$_data_item_invoice_child_[$i]->item_quantity-$value->item_quantity?></pre> -->
                    <?php $i++;?>
                    <?php endif;?>
                    <?php endforeach?>
                </tbody>
                <tfoot>
                    <tr>
                        <th rowspan="2" style="vertical-align:top;">Note: </th>
                        <th colspan="2" rowspan="2" class="text-left" style="vertical-align:top;"><?=$_data_invoice_child_->note?></th>
                        <th class="text-right"><b><?=$total_item?></b></th>
                        <th colspan="3" class="text-right"><?=lang('total_price')?>: </th>
                        <th class="text-right"><?=getCurrentcy($_data_invoice_parent->total_price - $_data_invoice_child_->total_price)?></th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-right"><?=lang('discount')?>: </th>
                        <th class="text-right"><?=getCurrentcy($_data_invoice_child_->discounts)?></th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-right"><?=lang('shipping_cost')?>: </th>
                        <th class="text-right"><?=getCurrentcy($_data_invoice_child_->shipping_cost)?></th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-right"><?=lang('status_payment')?>:</th>
                        <th class="text-left"><?=$_data_invoice_child_->status_payment?lang('payed'):lang('credit')?></th>
                    </tr>
                    <tr>
                        <th colspan="3"><i>Terbilang : # <b><?=terbilang($_data_invoice_parent->grand_total - $_data_invoice_child_->grand_total)?> </b> rupiah #</i></th>
                        <th colspan="4" class="text-right"><?=lang('grandtotal')?>: </th>
                        <th class="text-right"><?=getCurrentcy($_data_invoice_parent->grand_total - $_data_invoice_child_->grand_total)?></th>
                    </tr>
                </tfoot>
            </table>
            <table>
                <tfoot>
                    <tr>
                        <th class="text-center">Hormat Kami</th>
                        <th class="text-center">Penerima</th>
                        <th class="text-center" style="color:white">&nbsp;</th>
                        <th class="text-center" style="color:white">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="4">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="4">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="text-center" colspan="4">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="text-center">( Bandung Ejuice Distribution. )</th>
                        <th class="text-center">(..................................)</th>
                        <th class="text-center" style="color:white">(..................................)</th>
                        <th class="text-center" style="color:white">(..................................)</th>
                    </tr>
                </tfoot>
            </table>
        </main>
    </body>

    <body>
        <main>
            <table>
                <tr>
                    <td>B.E.D</td>
                    <td class="text-center"><h6>Bandung Ejuice Distribution</h6></td>
                </tr>
            </table>
        </main>
    </body>
</html>
