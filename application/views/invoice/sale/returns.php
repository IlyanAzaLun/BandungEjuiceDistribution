<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                    <li class="breadcrumb-item active"><?php echo lang('page_sale') ?></li>
                    <li class="breadcrumb-item active"><?php echo lang('returns') ?></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <?php
        $__return_invoice_code = str_replace('INV','RET',$this->input->get('id'));
        $_data_invoice_return = $this->transaction_item_model->get_transaction_item_by_code_invoice($__return_invoice_code);
        $_data_item_invoice_return = $this->transaction_item_model->get_transaction_item_by_code_invoice($__return_invoice_code);
    ?>
    <!-- Default card -->
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Note:</h5>
                <?= lang('sale_info_returns') ?><b><?= get('id') ?></b>
            </div>
            <?php echo form_open_multipart('invoice/sales/returns?id=' . get('id'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
            <!-- Information customer START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_customer') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('customer_code') ?></label>
                                <input readonly type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" autocomplete="false" value="<?= $invoice->customer ?>" required>
                                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('store_name') ?></label>
                                <input readonly type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" required>
                                <?= form_error('store_name', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="contact_phone"><?= lang('contact_phone') ?><small class="text-primary"> (whatsapp)</small></label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="<?= set_value('contact_phone') ?>" required readonly>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="address"><?= lang('address_destination') ?></label>
                                <textarea type="text" name="address" id="address" class="form-control" required readonly><?= set_value('address') ?></textarea>
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
                                        <th width="7%"><?= lang('note') ?></th>
                                        <th style="display:none"><?= lang('item_quantity') ?></th>
                                        <th width="15%"><?= lang('item_order_quantity') ?></th>
                                        <th width="10%"><?= lang('item_capital_price') ?></th>
                                        <th style="display:none"><?= lang('item_selling_price') ?></th>
                                        <th width="8%"><?= lang('discount') ?></th>
                                        <th width="10%"><?= lang('total_price') ?></th>
                                        <th width="12%"><?= lang('option') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $key => $value) : ?>
                                        <tr class="input-<?= $key ?>">
                                            <td><?= $key+1 ?>.</td>
                                            <td><a href="<?=url('items/info_transaction?id='.$value->item_code)?>"><?= $value->item_code ?></a></td>
                                            <td><?= $value->item_name ?></td>
                                            <td><?= $this->items_model->getByCodeItem($value->item_code, 'note') ?></td>
                                            <td style="display:none"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?> <?= $value->item_unit ?></td>
                                            <td><?= $value->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <td style="display:none">Rp.<?= number_format($value->item_capital_price) ?></td>
                                            <td>Rp.<?= number_format($value->item_selling_price) ?></td>
                                            <td>Rp.<?= number_format($value->item_discount) ?></td>
                                            <td>Rp.<b><?= number_format($value->total_price) ?></b></td>
                                            <td><button type="button" class="btn btn-block btn-danger" id="update" data-id="<?=$value->id?>"><i class="fa fa-tw fa-pencil-alt"></i></button></td>
                                        </tr>
                                        <?php if((strlen($value->item_description)) >= 1):?>
                                        <tr class="input-<?= $key ?>">
                                            <td></td>
                                            <td colspan="8"><b style="font-size: 14px;"><?= $value->item_description?></b></td>
                                        </tr>
                                        <?php endif ?>

                                        <tr class="input-<?= $key ?>" id="main" style="display:none">
                                            <td><?= $key+1 ?>.</td>
                                            <td>
                                                <input disabled type="hidden" name="id[]" id="id" value="<?= $value->id ?>">
                                                <input type="hidden" name="index_list[]" id="index_list" value="<?= $value->index_list ?>">
                                                <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                                                <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?= $value->item_code ?>" required>
                                            </td>
                                            <td><input class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" value="<?= $value->item_name ?>" required></td>
                                            <td><input class="form-control form-control-sm" type="text"data-id="note" value="<?= $this->items_model->getByCodeItem($value->item_code, 'note') ?>" required></td>
                                            <td style="display:none">
                                                <div class=" input-group input-group-sm">
                                                    <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?>">
                                                    <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit" value="<?= $value->item_unit ?>">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                                <input readonly class="form-control form-control-sm" type="text" name="item_quantity_current[]" data-id="item_quantity_current" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') - $value->item_quantity ?>">
                                            </td>
                                            <td>
                                                <div class=" input-group input-group-sm">
                                                    <input <?= ($value->total_price == 0)?'this_horiable':'' ?> class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="0" max="<?= (int)$value->item_quantity ?>" value="0" required>
                                                    <input readonly class="form-control form-control-sm" type="number" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" value="<?= (int)$value->item_quantity ?>" required>
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                            </td>
                                            <td style="display:none"><input class="form-control form-control-sm currency" type="text" name="item_capital_price[]" data-id="item_capital_price" required value="<?= $value->item_capital_price ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_selling_price[]" data-id="item_selling_price" required value="<?= $value->item_selling_price ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_discount[]" data-id="discount" min="0" required value="<?= (int)$value->item_discount ?>"></td>
                                            <td>
                                                <input class="form-control form-control-sm currency" type="hidden" name="total_price_current[]" data-id="total_price_current" min="0" required value="<?= $value->total_price ?>" readonly>
                                                <input class="form-control form-control-sm currency" type="text" name="total_price[]" data-id="total_price" min="0" required value="<?= $value->total_price ?>">
                                            </td>
                                            <td>
                                                <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item sale"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                                                    <button type="button" class="btn btn-primary" id="is_safty"><i class="fa fa-tw fa-circle-notch"></i></button>
                                                    <!--  -->
                                                    <input type="hidden" name="status_return[<?=$key?>]" value="0">
                                                    <input type="checkbox" name="status_return[<?=$key?>]" data-bootstrap-switch data-off-color="danger" data-off-text="<i class='fa fa-fw fa-trash'>" data-on-color="success" data-on-text="<i class='fa fa-fw fa-recycle'></i>" value="1">
                                                    <!--  -->
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="description input-<?=$key?>" style="display:none">
                                            <td colspan="9">
                                                <textarea class="form-control form-control-sm" name="description[]"><?=$value->item_description?></textarea>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
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
                                            <input type="text" readonly name="sub_total" id="sub_total" class="form-control currency" value="<?= $invoice->total_price ?>" min="1" required>
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
                                            <input type="text" name="discount" id="discount" class="form-control currency" value="<?= $invoice->discounts ?>" required>
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
                                            <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" value="<?= $invoice->shipping_cost ?>" required>
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
                                    <input type="text" name="other_cost" id="other_cost" class="form-control currency" value="<?= $invoice->other_cost ?>" required>
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
                                    <input type="text" readonly name="grand_total" id="grand_total" class="form-control currency" value="<?= $invoice->grand_total ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('expedition_name') ?></h6>
                                <select class="custom-select" name="expedition_name" id="expedition_name" required>
                                    <option selected disabled><?=lang('option')?></option>
                                    <?php foreach ($expedition as $key => $value):?>
                                        <option value="<?= $value->expedition_name ?>"<?=($value->expedition_name == $invoice->expedition)?' selected':''?> data-services="<?= $value->services_expedition ?>"><?= $value->expedition_name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('expedition_services') ?></h6>
                                <select class="custom-select" name="services_expedition" id="services_expedition">
                                    <option value="<?=$invoice->services_expedition?>"><?=$invoice->services_expedition?></option>
                                </select>
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('payment_type') ?></h6>
                                        <select class="custom-select" name="payment_type">
                                            <option value="cash" <?= ($invoice->payment_type == 'cash') ? ' selected' : '' ?>><?= lang('cash') ?></option>
                                            <option value="credit" <?= ($invoice->payment_type == 'credit') ? ' selected' : '' ?>><?= lang('credit') ?></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?=lang('bank_name')?></h6>
                                        <div class="input-group">
                                            <select name="transaction_destination" id="destination" class="custom-select">
                                                <option value="" disabled selected><?=lang('select_account')?></option>
                                                <?php foreach ($bank as $key => $value):?>
                                                <option value="<?=$value->id?>"<?=($value->id==$invoice->transaction_destination)?' selected':''?>><?=$value->name?>/<?=$value->no_account?>/<?=$value->own_by?></option>
                                                <?php endforeach?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <h6><?=lang('date')?></h6>
                                        <div class="input-group">
                                            <input type="text" id="created_at" name="created_at" class="form-control" data-target="#created_at" value="<?=date("d/m/Y H:i:s",strtotime($invoice->created_at))?>">
                                            <div class="input-group-append" data-target="#created_at" data-toggle="daterangepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('date_due') ?></h6>
                                        <div class="input-group mb-3">
                                        <input type="text" id="date_due" name="date_due" class="form-control" value="<?=date("d/m/Y H:i",strtotime($invoice->date_start))?> - <?=date("d/m/Y H:i",strtotime($invoice->date_due))?>">
                                        <dv class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-tw fa-calendar"></i></span>
                                        </dv>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg col-sm-12">
                                    <div class="form-group">
                                        <label for="note"><?= lang('note') ?></label>
                                        <textarea name="note" id="note" class="form-control"><?= $invoice->note ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Information payment END -->
            <div class="card">
                <div class="card-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-info float-right"><?= lang('save') ?></button>
                        <button type="button" class="btn btn-default mr-2" onclick="history.back()"><?= lang('back') ?></button>
                    </div>
                </div>
            </div>
            <!-- /.card -->
            <?php echo form_close(); ?>
        </div>
    </div>
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
<script>
    $('body').addClass('sidebar-collapse');
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
        startDate: moment(),
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
<!-- Jquery ui -->
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="module" src="<?php echo $url->assets ?>pages/invoice/sale/MainSalesReturn.js"></script>