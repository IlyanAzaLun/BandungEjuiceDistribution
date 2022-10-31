<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$i = 0; $due = new DateTime($_data_invoice_parent->date_due); $start = new DateTime($_data_invoice_parent->date_start);?>
<!-- Main content -->
<section class="content">

    <!-- Default card -->

    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Note:</h5>
                <?= lang('purchase_info_edit') ?><b><?= get('id') ?></b>
            </div>
            <?php echo form_open_multipart('invoice/purchase/edit?id=' . get('id'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
            <!-- Information customer START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_supplier') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('supplier_code') ?></label>
                                <input type="text" name="supplier_code" id="supplier_code" class="form-control" placeholder="<?= lang('find_supplier_code') ?>" autocomplete="false" value="<?= $_data_invoice_parent->supplier ?>" required>
                                <?= form_error('supplier_code', '<small class="text-danger">', '</small>') ?>
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
                        <div class="col-12 table-responsive">
                            <table class="table table-sm" style="">
                                <thead>
                                    <tr>
                                        <th width="2%">No.</th>
                                        <th width="10%"><?= lang('item_code') ?></th>
                                        <th><?= lang('item_name') ?></th>
                                        <th style="display:none"><?= lang('item_quantity') ?></th>
                                        <th width="10%"><?= lang('note') ?></th>
                                        <th width="12%"><?= lang('item_order_quantity') ?></th>
                                        <th width="10%"><?= lang('item_capital_price') ?></th>
                                        <th style="display:none"><?= lang('item_selling_price') ?></th>
                                        <th width="7%"><?= lang('discount') ?></th>
                                        <th width="10%"><?= lang('total_price') ?></th>
                                        <th width="7%"><?= lang('option') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- TO -->
                                    <?php foreach ($_data_item_invoice_parent as $key => $value) : ?>
                                        <?php if($value->item_code == isset($intersect_codex_item[$key]) && $value->index_list == isset($intersect_index_item[$key])):?>
                                        <tr class="input-<?= $key ?>" id="main">
                                            <td class="text-center"><input class="form-control form-control-sm" disabled value="<?=$key+1?>."></td>
                                            <td>
                                                <input type="hidden" name="id[]" id="id" value="<?= $value->id ?>">
                                                <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                                                <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?= $value->item_code ?>" required readonly>
                                            </td>
                                            <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required readonly><?= $value->item_name ?></textarea></td>
                                            <td><input class="form-control form-control-sm" type="text" name="note[]" data-id="note" value="<?= $this->items_model->getByCodeItem($value->item_code, 'note') ?>" required readonly></td>
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
                                                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" required value="<?= $value->item_quantity ?>">
                                                    <input disabled class="form-control form-control-sm" type="number" name="item_order_quantity_wiith_return[]" data-id="item_order_quantity" min="1" required value="<?= $value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity ?>">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                                <input readonly class="form-control form-control-sm" type="text" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" required value="<?= (int)$value->item_quantity ?>" style="display:none">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm currency" type="text" name="item_capital_price[]" data-id="item_capital_price" required value="<?= $_data_item_invoice_child_[$i]->item_capital_price ?>">
                                                <input class="form-control form-control-sm" type="hidden" name="item_capital_price_is_change[]" data-id="item_capital_price_is_change" value="0">
                                            </td>
                                            <td style="display:none"><input class="form-control form-control-sm currency" type="text" name="item_selling_price[]" data-id="item_selling_price" required value="<?= $_data_item_invoice_child_[$i]->item_selling_price ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_discount[]" data-id="discount" min="0" required value="<?= number_format($_data_item_invoice_child_[$i]->item_discount) ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="total_price[]" data-id="total_price" min="0" required value="<?= number_format($_data_item_invoice_child_[$i]->total_price) ?>"></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                                                    <a target="_blank" href='<?=url("items/info_transaction?id=$value->item_code&customer=$_data_invoice_parent->supplier")?>' class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                                                <?php if (sizeof($items) <= 1) : ?>
                                                    <button disabled type="button" class="btn btn-block btn-secondary"><i class="fa fa-tw fa-times"></i></button>
                                                <?php else : ?>
                                                    <button type="button" class="btn btn-block btn-danger remove" data-id="<?=$value->id?>" data-toggle="modal" data-target="#modal-remove-order"><i class="fa fa-tw fa-times"></i></button>
                                                <?php endif ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="description input-<?=$key?>" style="display:<?=(strlen($value->item_description) >=0 )?'none':''?>">
                                            <td colspan="8">
                                                <textarea class="form-control form-control-sm" name="description[]"><?= $value->item_description?></textarea>
                                            </td>
                                        </tr>
                                        <!-- <pre>Item INDEX RETUR:<?=$_data_item_invoice_child_[$i]->index_list?> | Item CODE PURCH:<?=$_data_item_invoice_child_[$i]->item_code?> | Item Quantity PURCH:<?=$_data_item_invoice_child_[$i]->item_quantity?></pre> -->
                                        <?php $i++;?>
                                        <?php else:?>
                                        <tr class="input-<?= $key ?>" id="main">
                                            <td class="text-center"><input class="form-control form-control-sm" disabled value="<?=$key+1?>."></td>
                                            <td>
                                                <input type="hidden" name="id[]" id="id" value="<?= $value->id ?>">
                                                <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                                                <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?= $value->item_code ?>" required readonly>
                                            </td>
                                            <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required readonly><?= $value->item_name ?></textarea></td>
                                            <td><input class="form-control form-control-sm" type="text" name="note[]" data-id="note" value="<?= $this->items_model->getByCodeItem($value->item_code, 'note') ?>" required readonly></td>
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
                                                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" required value="<?= (int)$value->item_quantity ?>">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                                <input readonly class="form-control form-control-sm" type="text" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" required value="<?= (int)$value->item_quantity ?>" style="display:none">
                                            </td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_capital_price[]" data-id="item_capital_price" required value="<?= $value->item_capital_price ?>"></td>
                                            <input class="form-control form-control-sm" type="hidden" name="item_capital_price_is_change[]" data-id="item_capital_price_is_change" value="0">
                                            <td style="display:none"><input class="form-control form-control-sm currency" type="text" name="item_selling_price[]" data-id="item_selling_price" required value="<?= $value->item_selling_price ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_discount[]" data-id="discount" min="0" required value="<?= number_format($value->item_discount) ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="total_price[]" data-id="total_price" min="0" required value="<?= number_format($value->total_price) ?>"></td>
                                            <td>
                                                <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                                                    <a target="_blank" href='<?=url("items/info_transaction?id=$value->item_code&customer=$_data_invoice_parent->supplier")?>' class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                                                <?php if (sizeof($items) <= 1) : ?>
                                                    <button disabled type="button" class="btn btn-secondary"><i class="fa fa-tw fa-times"></i></button>
                                                <?php else : ?>
                                                    <button type="button" class="btn btn-danger remove" data-id="<?=$value->id?>" data-toggle="modal" data-target="#modal-remove-order"><i class="fa fa-tw fa-times"></i></button>
                                                <?php endif ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="description input-<?=$key?>" style="display:<?=(strlen($value->item_description)>= 0)?'none':''?>">
                                            <td colspan="8">
                                                <textarea class="form-control form-control-sm" name="description[]"><?= $value->item_description?></textarea>
                                            </td>
                                        </tr>
                                        <!-- <pre>Item INDEX PURCH:<?=$value->index_list?> | Item CODE ORDER:<?=$value->item_code?> | Item Quantity ORDER:<?=$value->item_quantity?></pre> -->
                                        <?php endif;?>
                                    <?php endforeach ?>
                                    <!-- TO -->
                                </tbody>
                            </table>
                            <div class="float-left ml-1">
                                <button type="button" class="btn btn-sm btn btn-info" id="add_more"><?= lang('add_more') ?></button>
                            </div>
                            <!-- Total Items -->
                            <div class="float-right ml-1">
                                <div class="input-group input-group-sm">
                                <h6 id="total_items">Total Items: 0</h6>
                                </div>
                            </div>
                            <!--  -->
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
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                <h6><?= lang('subtotal') ?> :</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" readonly name="sub_total" id="sub_total" class="form-control currency" value="<?= $_data_invoice_parent->total_price ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('discount') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="discount" id="discount" class="form-control currency" value="<?= $_data_invoice_parent->discounts ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('shipping_cost') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" value="<?= $_data_invoice_parent->shipping_cost ?>" required>
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
                                    <input type="text" name="other_cost" id="other_cost" class="form-control currency" value="<?= $_data_invoice_parent->other_cost ?>" required>
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
                                    <input type="text" readonly name="grand_total" id="grand_total" class="form-control currency" value="<?= $_data_invoice_parent->grand_total ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                    <!-- Date -->
                                    <div class="form-group">
                                        <h6><?=lang('date')?></h6>
                                        <div class="input-group">
                                            <input type="text" id="created_at" name="created_at" class="form-control" data-target="#created_at" value="<?=date("d/m/Y H:i:s",strtotime($_data_invoice_parent->created_at))?>">
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
                                        <input type="text" id="date_due" name="date_due" class="form-control" value="<?=date("d/m/Y H:i:s",strtotime($_data_invoice_parent->date_start))?> - <?=date("d/m/Y H:i:s",strtotime($_data_invoice_parent->date_due))?>">
                                        <dv class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-tw fa-calendar"></i></span>
                                        </dv>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <h6><?=lang('number_of_day')?></h6>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="numberdays" value="<?=$start->diff($due)->d?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('payment_type') ?></h6>
                                        <select class="custom-select" name="payment_type" id="payment_type">
                                            <option value="cash" <?= ($_data_invoice_parent->payment_type == 'cash') ? ' selected' : '' ?>><?= lang('cash') ?></option>
                                            <option value="credit" <?= ($_data_invoice_parent->payment_type == 'credit') ? ' selected' : '' ?>><?= lang('credit') ?></option>
                                            <option value="consignment" <?= ($_data_invoice_parent->payment_type == 'consignment') ? ' selected' : '' ?>><?= lang('consignment') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12" style="display: none">
                                    <div class="form-group">
                                        <h6><?=lang('bank_name')?></h6>
                                        <div class="input-group">
                                            <select name="transaction_source" id="source" class="custom-select">
                                                <option value="" disabled selected><?=lang('select_account')?></option>
                                                <?php foreach ($bank as $key => $value):?>
                                                    <option value="<?=$value->id?>"<?=($value->id==$_data_invoice_parent->transaction_source)?' selected':''?>><?=$value->name?>/<?=$value->no_account?>/<?=$value->own_by?></option>
                                                <?php endforeach?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="note"><?= lang('note') ?></label>
                                <textarea name="note" id="note" class="form-control"><?= $_data_invoice_parent->note ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="shipping_cost_to_invoice" value=0>
                                    <input class="custom-control-input" type="checkbox" id="shipping_cost_to_invoice" name="shipping_cost_to_invoice" <?= $_data_invoice_parent->is_shipping_cost?'checked':'' ?> value=1>
                                    <label for="shipping_cost_to_invoice" class="custom-control-label"><?=strtolower(lang('is_shipping_cost_to_invoice'))?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="is_consignment" value=0>
                                    <input class="custom-control-input" type="checkbox" id="is_consignment" name="is_consignment" <?= $_data_invoice_parent->is_consignment?'checked':'' ?> value=1>
                                    <label for="is_consignment" class="custom-control-label"><?=strtolower(lang('is_consignment'))?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="status_payment" value=0>
                                    <input class="custom-control-input" type="checkbox" id="status_payment" name="status_payment" <?= $_data_invoice_parent->status_payment?'checked':'' ?> value=1>
                                    <label for="status_payment" class="custom-control-label"><?=strtolower(lang('is_status_payment'))?></label>
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
                    <div class="float-left">
                        <a class="btn btn-info" href="<?=url("document/delivery/create?invoice=").get('id')?>"><?=lang('delivery_documents')?></a>
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
    })
    
    //Date range picker
    function cb(start, end) {
      $('#date_due span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#date_due').daterangepicker({        
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        opens: "center",
        drops: "up",
        locale: {
            format: 'DD/MM/YYYY'
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
        format: 'DD/MM/YYYY H:mm:ss'
        }
    });
</script>
<script type="module" src="<?php echo $url->assets ?>pages/invoice/purchase/MainPurchaseEdit.js"></script>