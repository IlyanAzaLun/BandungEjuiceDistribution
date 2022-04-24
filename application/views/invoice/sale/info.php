<?php
defined('BASEPATH') or exit('No direct script access allowed'); 
$i = 0; ?>
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.structure.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.theme.min.css">

<?php include viewPath('includes/header'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo lang($title) ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo url('/') ?>"><?php echo lang('home') ?></a></li>
                    <li class="breadcrumb-item active"><?php echo lang('page_purchase') ?></li>
                    <li class="breadcrumb-item active"><?php echo lang('purchase') ?></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default card -->
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Note:</h5>
                <?= lang('purchase_info_edit') ?><b><?= get('id') ?></b>
            </div>
            <?php echo form_open_multipart('invoice/sale/edit?id=' . get('id'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
            <!-- //content -->
            <!-- Information customer START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_supplier') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('customer_code') ?></label>
                                <input disabled type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" autocomplete="false" value="<?= $customer->customer_code ?>" required>
                                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('store_name') ?></label>
                                <input disabled type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" required>
                                <?= form_error('store_name', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="contact_phone"><?= lang('contact_phone') ?><small class="text-primary"> (whatsapp)</small></label>
                                <input disabled type="text" name="contact_phone" id="contact_phone" class="form-control" value="<?= set_value('contact_phone') ?>" required readonly>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="address"><?= lang('address_destination') ?></label>
                                <textarea disabled type="text" name="address" id="address" class="form-control" required readonly><?= set_value('address') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Information customer END -->
            <!-- Information Items START -->
            
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-two"></i><?php echo lang('information_items') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row" id="order_item">
                        <div class="col-12">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th width="2%">No.</th>
                                        <th width="10%"><?= lang('item_code') ?></th>
                                        <th><?= lang('item_name') ?></th>
                                        <th width="10%"><?= lang('note') ?></th>
                                        <th style="display: none"><?= lang('item_quantity') ?></th>
                                        <th width="10%"><small><?= lang('item_order_quantity') ?></small><br>Parents <small><?=date("d-m-Y",strtotime($_data_invoice_parent->created_at))?></small></th>
                                        <?php if($intersect_codex_item):?>
                                        <th width="10%"><small><?= lang('item_order_quantity') ?></small><br>Returns <small><?=date("d-m-Y",strtotime($_data_invoice_child_->created_at))?></small></th>
                                        <th width="9%"><small><?= lang('item_order_quantity') ?></small><br>Total Balance</th>
                                        <?php endif;?>

                                        <th style="display: none"><?= lang('item_capital_price') ?></th>
                                        <th width="7%"><?= lang('item_selling_price') ?></th>
                                        <th width="7%"><?= lang('discount') ?></th>
                                        <th width="10%"><?= lang('total_price') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_data_item_invoice_parent as $key => $value):?>
                                        <?php $total_price += $value->total_price;?>
                                        <?php if($value->item_code == $intersect_codex_item[$key] && $value->index_list == $intersect_index_item[$key]):?>
                                        <!-- <pre>Item INDEX TOTAL:<?=$value->index_list?> | Item CODE TOTALN:<?=$value->item_code?> | Item Quantity TOTAL:<?=$value->item_quantity - $_data_item_invoice_child_[$i]->item_quantity?></pre> -->
                                        <!--  -->
                                        <tr class="input-<?= $key ?>">
                                            <td><?= $key+1 ?>.</td>
                                            <td><a href="<?=url('items/info_transaction?id='.$value->item_code)?>"><?= $value->item_code ?></a></td>
                                            <td><?= $value->item_name ?></td>
                                            <td style="display:none"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?> <?= $value->item_unit ?></td>
                                            <td><?= $this->items_model->getByCodeItem($value->item_code, 'note') ?></td>
                                            <!--  -->
                                            <td><?= $value->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <td><?= $_data_item_invoice_child_[$i]->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <td><?= $value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <!--  -->
                                            <td style="display:none">Rp.<?= number_format($_data_item_invoice_child_[$i]->item_capital_price) ?></td>
                                            <td>Rp.<?= number_format($_data_item_invoice_child_[$i]->item_selling_price) ?></td>
                                            <td>Rp.<?= number_format($_data_item_invoice_child_[$i]->item_discount) ?></td>
                                            <td>Rp.<b><?= number_format($_data_item_invoice_child_[$i]->total_price) ?></b></td>
                                        </tr>
                                        <?php if((strlen($_data_item_invoice_child_[$i]->item_description)) >= 1):?>
                                        <tr>
                                            <td></td>
                                            <td colspan="8"><b style="font-size: 14px;"><?= $value->item_description?></b></td>
                                        </tr>
                                        <?php endif;?>
                                        <!--  -->
                                        <?php $i++;?>
                                        <?php else:?>
                                        <!--  -->
                                        <tr class="input-<?= $key ?>">
                                            <td><?= $key+1 ?>.</td>
                                            <td><a href="<?=url('items/info_transaction?id='.$value->item_code)?>"><?= $value->item_code ?></a></td>
                                            <td><?= $value->item_name ?></td>
                                            <td style="display:none"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?> <?= $value->item_unit ?></td>
                                            <td><?= $this->items_model->getByCodeItem($value->item_code, 'note') ?></td>
                                            <td><?= $value->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <?php if($intersect_codex_item):?>
                                            <td>0 PCS</td>
                                            <td><?= $value->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <?php endif;?>
                                            <td>Rp.<?= number_format($value->item_capital_price) ?></td>
                                            <td style="display:none">Rp.<?= number_format($value->item_selling_price) ?></td>
                                            <td>Rp.<?= number_format($value->item_discount) ?></td>
                                            <td>Rp.<b><?= number_format($value->total_price) ?></b></td>
                                        </tr>
                                        <?php if((strlen($value->item_description)) >= 1):?>
                                        <tr>
                                            <td></td>
                                            <td colspan="8"><b style="font-size: 14px;"><?= $value->item_description?></b></td>
                                        </tr>
                                        <?php endif;?>
                                        <!--  -->
                                        <!-- <pre>Item INDEX ORDER:<?=$value->index_list?> | Item CODE ORDER:<?=$value->item_code?> | Item Quantity ORDER:<?=$value->item_quantity?></pre> -->
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Information Items END -->
            <!-- Information payment START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-three"></i><?php echo lang('information_payment') ?></h3>
                </div>
                <div class="card-body payment">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('subtotal') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" readonly name="sub_total" id="sub_total" class="form-control currency" value="<?= number_format($invoice_information_transaction->total_price) ?>" min="1" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('discount') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="discount" id="discount" class="form-control currency" value="<?= number_format($invoice_information_transaction->discounts) ?>" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('shipping_cost') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" value="<?= number_format($invoice_information_transaction->shipping_cost) ?>" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12" style="display:none">
                            <div class="form-group">
                                <h6><?= lang('other_cost') ?> :</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" name="other_cost" id="other_cost" class="form-control currency" value="<?= number_format($invoice_information_transaction->other_cost) ?>" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <h6><b><?= lang('grandtotal') ?> :</b></h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><b>Rp</b></span>
                                    </div>
                                    <input type="text" readonly name="grand_total" id="grand_total" class="form-control currency" value="<?= number_format($invoice_information_transaction->grand_total) ?>" min="1" required readonly>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('expedition_name') ?></h6>
                                <select class="custom-select" name="expedition_name" id="expedition_name" required disabled>
                                    <option selected disabled><?=lang('option')?></option>
                                        <option value="<?= $invoice_information_transaction->expedition ?>" selected data-services="<?= $invoice_information_transaction->services_expedition ?>"><?= $invoice_information_transaction->expedition ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('expedition_services') ?></h6>
                                <select class="custom-select" name="services_expedition" id="services_expedition" required disabled>
                                    <option value="<?=$invoice_information_transaction->services_expedition?>" selected><?=$invoice_information_transaction->services_expedition?></option>
                                </select>
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('payment_type') ?></h6>
                                        <select class="custom-select" name="payment_type" disabled>
                                            <option value="cash" <?= ($invoice_information_transaction->payment_type == 'cash') ? ' selected' : '' ?>><?= lang('cash') ?></option>
                                            <option value="credit" <?= ($invoice_information_transaction->payment_type == 'credit') ? ' selected' : '' ?>><?= lang('credit') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?=lang('bank_name')?></h6>
                                        <div class="input-group">
                                            <select name="transaction_destination" id="destination" class="custom-select" required disabled>
                                                <option value="<?=$bank->id?>"><?=$bank->name?>/<?=$bank->no_account?>/<?=$bank->own_by?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <h6><?=lang('date')?></h6>
                                        <div class="input-group">
                                            <input disabled type="text" id="created_at" name="created_at" class="form-control" data-target="#created_at" value="<?=date("d/m/Y H:i:s",strtotime($invoice_information_transaction->created_at))?>">
                                            <div class="input-group-append" data-target="#created_at" data-toggle="daterangepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('date_due') ?></h6>
                                        <div class="input-group mb-3">
                                        <input disabled type="text" id="date_due" name="date_due" class="form-control" value="<?=date("d/m/Y H:i",strtotime($invoice_information_transaction->date_start))?> - <?=date("d/m/Y H:i",strtotime($invoice_information_transaction->date_due))?>">
                                        <dv class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-tw fa-calendar"></i></span>
                                        </dv>
                                        </div>
                                    </div>
                                </div>
                                <?php if($intersect_codex_item):?>
                                <div class="col-lg-2 col-sm-12 text-danger">
                                    <div class="form-group">
                                        <h6><b><?= lang('deposit') ?></b></h6>
                                        <input readonly type="text" class="form-control" value="<?= number_format($_data_invoice_parent->grand_total - $_data_invoice_child_->grand_total) ?>" required>
                                    </div>
                                </div>
                                <?php endif;?>
                                <div class="col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="note"><?= lang('note') ?></label>
                                        <textarea disabled name="note" id="note" class="form-control"><?= $invoice_information_transaction->note ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <?php if(!$invoice_information_transaction->is_cancelled):?>
                <div class="card-footer">
                    <div class="float-right">
                        <button type="button" class="btn btn-default mr-2"><?= lang('PDF') ?></button>
                        <button type="button" class="btn btn-md btn-danger" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Remove this information"><?=lang('cancel')?></button>
                    </div>
                </div>
                <?php endif?>
            </div>
            
            <!-- //end-content -->
            <?php echo form_close(); ?>
        </div>
    </div>
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
<script>
    $('body').addClass('sidebar-collapse');
</script>
<!-- Jquery ui -->
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        $('#exampleModal').on('shown.bs.modal', function(){
            $('textarea').prop('required',true);
            $('textarea').focus();
        })

        $('#expedition_name').on('change', function(){
            const expedition = $(this).find(":selected");
            const services = expedition.data('services')
            const service = services.split(',');
            const data_services = expedition.data('service_selected');
            $('#services_expedition').empty();
            service.forEach(element => {
                $('#services_expedition').append(`<option value="${element}">${element}</option>`)
            });
        })
    })
    
    //Date range picker
    function cb(start, end) {
      $('#date_due span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#date_due').daterangepicker({
        startDate: moment(),
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        opens: "center",
        drops: "up",
        locale: {
            format: 'DD/MM/YYYY H:mm:s'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Tomorow': [moment(), moment().add(1, 'days')],
            'Next 7 Days': [moment(), moment().add(6, 'days')],
            'Next 14 Days': [moment(), moment().add(13, 'days')],
            'Next 30 Days': [moment(), moment().add(29, 'days')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
        },
    }, cb).on('apply.daterangepicker', function(ev, picker) {
        $('#numberdays').val(picker.endDate.diff(picker.startDate, "days"));
    });

    //Date range picker
    $('#created_at').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        opens: "center",
        drops: "up",
        locale: {
            format: 'DD/MM/YYYY H:mm:s'
    }
    });
</script>
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>

<script type="module" src="<?php echo $url->assets ?>pages/invoice/sale/MainSaleEdit.js"></script>