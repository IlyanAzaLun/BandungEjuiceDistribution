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
                                <input type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" autocomplete="false" value="<?= $invoice_sale->customer ?>" required>
                                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('store_name') ?></label>
                                <input type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" required>
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
                                        <th>No.</th>
                                        <th width="10%"><?= lang('item_code') ?></th>
                                        <th width="45%"><?= lang('item_name') ?></th>
                                        <th style="display: none"><?= lang('item_quantity') ?></th>
                                        <th style="display: none"><?= lang('item_capital_price') ?></th>
                                        <th width="10%"><?= lang('item_selling_price') ?></th>
                                        <th width="10%"><?= lang('item_order_quantity') ?></th>
                                        <th width="10%"><?= lang('discount') ?></th>
                                        <th width="10%"><?= lang('total_price') ?></th>
                                        <th><?= lang('option') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list_item_sale as $key => $value) : ?>
                                        <tr class="input-<?= $key ?>" id="main">
                                            <td class="text-center"><div class="form-control form-control-sm"><?=$key+1?>.</div></td>
                                            <td>
                                                <input type="hidden" name="id[]" id="id" value="<?= $value->id ?>">
                                                <input type="hidden" name="index_list[]" id="index_list" value="<?= $value->index_list ?>">
                                                <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                                                <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?= $value->item_code ?>" required>
                                            </td>
                                            <td><input class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" value="<?= $value->item_name ?>" required></td>
                                            <td style="display: none">
                                                <div class=" input-group input-group-sm">
                                                    <input class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?>">
                                                    <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit" value="<?= $value->item_unit ?>">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                                <input class="form-control form-control-sm" type="text" name="item_quantity_current[]" data-id="item_quantity_current" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') - $value->item_quantity ?>">
                                            </td>
                                            <td style="display: none"><input class="form-control form-control-sm currency" type="text" name="item_capital_price[]" data-id="item_capital_price" required value="<?= $value->item_capital_price ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_selling_price[]" data-id="item_selling_price" required value="<?= $value->item_selling_price ?>"></td>
                                            <td>
                                                <div class=" input-group input-group-sm">
                                                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" max="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity')?>" required value="<?= (int)$value->item_quantity ?>">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                                <input style="display:none" class="form-control form-control-sm" type="text" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" required value="<?= (int)$value->item_quantity ?>">
                                            </td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_discount[]" data-id="discount" min="0" required value="<?= (int)$value->item_discount ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="total_price[]" data-id="total_price" min="0" required value="<?= $value->total_price ?>"></td>
                                            <td>
                                            <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                                                <a target="_blank" class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                                                <?php if (sizeof($list_item_sale) <= 1) : ?>
                                                <button disabled type="button" class="btn btn-block btn-secondary"><i class="fa fa-tw fa-times"></i></button>
                                                <?php else : ?>
                                                <button type="button" class="btn btn-block btn-danger remove" data-id="<?=$value->id?>" data-toggle="modal" data-target="#modal-remove-order"><i class="fa fa-tw fa-times"></i></button>
                                                <?php endif ?>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr class="description input-<?=$key?>" style="display:<?=(!strlen($value->item_description))?'none':''?>">
                                            <td colspan="8">
                                                <textarea class="form-control form-control-sm" name="description[]"><?=$value->item_description?></textarea>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            <div class="float-left ml-1">
                                <button type="button" class="btn btn-sm btn btn-info" id="add_more"><?= lang('add_more') ?></button>
                            </div>
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
                            <div class="form-group">
                                <h6><?= lang('subtotal') ?> :</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" readonly name="sub_total" id="sub_total" class="form-control currency" value="<?= $invoice_sale->total_price ?>" min="1" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('discount') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="discount" id="discount" class="form-control currency" value="<?= $invoice_sale->discounts ?>" required>
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
                                            <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" value="<?= $invoice_sale->shipping_cost ?>" required>
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
                                                <option value="<?= $value->expedition_name ?>"<?=($value->expedition_name == $invoice_sale->expedition)?' selected':''?> data-services="<?= $value->services_expedition ?>"><?= $value->expedition_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('expedition_services') ?></h6>
                                        <select class="custom-select" name="services_expedition" id="services_expedition" required>
                                            <option value="<?=$invoice_sale->services_expedition?>"><?=$invoice_sale->services_expedition?></option>
                                        </select>
                                    </div>
                                </div>
                                <!--  -->

                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12" style="display:none">
                            <div class="form-group">
                                <h6><?= lang('other_cost') ?> :</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" name="other_cost" id="other_cost" class="form-control currency" value="<?= $invoice_sale->other_cost ?>" required>
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
                                    <input type="text" readonly name="grand_total" id="grand_total" class="form-control currency" value="<?= $invoice_sale->grand_total ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('payment_type') ?></h6>
                                <select class="custom-select" name="payment_type">
                                    <option value="cash" <?= ($invoice_sale->payment_type == 'cash') ? ' selected' : '' ?>><?= lang('cash') ?></option>
                                    <option value="credit" <?= ($invoice_sale->payment_type == 'credit') ? ' selected' : '' ?>><?= lang('credit') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('date_due') ?></h6>
                                <div class="input-group mb-3">
                                <input type="text" id="date_due" name="date_due" class="form-control" value="<?=date("d/m/Y H:i",strtotime($invoice_sale->date_start))?> - <?=date("d/m/Y H:i",strtotime($invoice_sale->date_due))?>">
                                <dv class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-tw fa-calendar"></i></span>
                                </dv>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-sm-12">
                            <div class="form-group">
                                <label for="note"><?= lang('note') ?></label>
                                <textarea name="note" id="note" class="form-control"><?= $order->note ?></textarea>
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
                        <button type="button" class="btn btn-default mr-2"><?= lang('cancel') ?></button>
                    </div>
                </div>
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
        $('.remove').on('click', function(){
            let id = $(this).data('id');
            $('#modal-remove-order').on('shown.bs.modal', function(){
                $(this).find('input#id').val(id);
            })
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
    $('#date_due').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 30,
        locale: {
        format: 'DD/MM/YYYY H:mm'
        }
    });
</script>
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>

<script type="module" src="<?php echo $url->assets ?>pages/invoice/sale/MainSaleCreate.js"></script>