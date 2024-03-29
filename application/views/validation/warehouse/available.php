<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">
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
          <li class="breadcrumb-item active"><?php echo lang('order') ?></li>
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
        <?= lang('order_info_edit') ?>
      </div>
      <?php echo form_open_multipart('validation/warehouse/available?id='.get('id'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
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
                <input type="hidden" name="order_code" id="order_code" class="form-control" value="<?=$invoice->order_code?>" autocomplete="false" readonly required>
                <input type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" value="<?=$invoice->customer?>" autocomplete="false" readonly required>
                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for="store_name"><?= lang('store_name') ?></label>
                <input type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" readonly required>
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
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th width="2%">No.</th>
                    <th style="display:none" width="10%"><?= lang('item_code') ?></th>
                    <th><?= lang('item_name') ?></th>
                    <th style="display:none"><?= lang('item_quantity') ?></th>
                    <th style="display:none"><?= lang('item_capital_price') ?></th>
                    <th style="display:none"><?= lang('item_selling_price') ?></th>
                    <th width="5%"><?= lang('note') ?></th>
                    <th width="11%"><?= lang('item_order_quantity') ?></th>
                    <th style="display:none"><?= lang('discount') ?></th>
                    <th style="display:none"><?= lang('total_price') ?></th>
                    <th width="10%" class="text-center"><?= lang('status_available') ?></th>
                    <th width="5%"><?= lang('option') ?></th>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $key => $value) : ?>
                        <tr class="input-<?= $key ?>" id="main" style="display:<?=$value->is_cancelled&&!$value->status_available?'none':''?>">
                            <td class="text-center"><b><?=$key+1?>.</b></td>
                            <td style="display:none">
                                <input type="hidden" name="id[]" id="id" value="<?= $value->id ?>">
                                <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                                <input class="form-control form-control-sm" type="hidden" name="item_code[]" data-id="item_code" value="<?= $value->item_code ?>" readonly required>
                                <?= $value->item_code ?>
                            </td>
                            <td>
                              <input class="form-control form-control-sm" type="hidden" name="item_name[]" data-id="item_name" value="<?= $value->item_name ?>" readonly required>
                              <?php if($value->is_cancelled):?>
                              <strike><b class="text-danger"><?=$value->item_name ?></b></strike>
                              <?php else: ?>
                              <b><?= $value->item_name ?></b>
                              <?php endif; ?>

                            </td>
                            <td style="display:none">
                                <div class=" input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="hidden" name="item_quantity[]" data-id="item_quantity" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?>">
                                    <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit" value="<?= $value->item_unit ?>">
                                    <span class="input-group-append">
                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                    </span>
                                </div>
                                <input readonly class="form-control form-control-sm" type="hidden" name="item_quantity_current[]" data-id="item_quantity_current" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') - $value->item_quantity ?>">
                            </td>
                            <td style="display:none"><input class="form-control form-control-sm currency" type="hidden" name="item_capital_price[]" data-id="item_capital_price" required value="<?= $value->item_capital_price ?>" readonly></td>
                            <td style="display:none"><input class="form-control form-control-sm currency" type="hidden" name="item_selling_price[]" data-id="item_selling_price" required value="<?= $value->item_selling_price ?>" readonly></td>
                            <td><?= $this->items_model->getByCodeItem($value->item_code, 'note') ?></td>
                            <td>
                                <div class=" input-group input-group-sm" style="display:none">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text" data-id="item_quantity"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?></span>
                                    </span>
                                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" readonly required value="<?= (int)$value->item_order_quantity ?>">
                                    <span class="input-group-append">
                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                    </span>
                                </div>
                                <input readonly class="form-control form-control-sm" type="hidden" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" required value="<?= (int)$value->item_quantity ?>" style="display:none">
                                <?php if($value->is_cancelled):?>
                                <strike><b class="text-danger"><?= (int)$value->item_order_quantity ?></b></strike>
                                <?php else: ?>
                                <b><?= (int)$value->item_order_quantity ?></b>
                                <?php endif; ?>
                            </td>
                            <td style="display:none"><input class="form-control form-control-sm currency" type="hidden" name="item_discount[]" data-id="discount" min="0" required value="<?= (int)$value->item_discount ?>" readonly></td>
                            <td style="display:none"><input class="form-control form-control-sm currency" type="hidden" name="total_price[]" data-id="total_price" min="0" required value="<?= $value->item_total_price ?>" readonly></td>
                            <td class="text-center">
                              <div class="btn-group" role="group" aria-label="Basic example">
                                <input type="hidden" name="status_available[<?=$key?>]" value="0">
                                <input type="checkbox" name="status_available[<?=$key?>]"<?=($value->status_available)?' checked':''?> data-bootstrap-switch data-off-color="danger" data-off-text="<i class='fa fa-fw fa-times'>" data-on-color="success" data-on-text="<i class='fa fa-fw fa-check'></i>" value="1">
                              </div>
                            </td>
                            <td>
                                <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-fw fa-ellipsis-h"></i></button>
                                <?php if (sizeof($items) <= 1) : ?>
                                    <button  style="display:none" disabled type="button" class="btn btn-block btn-secondary"><i class="fa fa-fw fa-times"></i></button>
                                <?php else : ?>
                                    <button  style="display:none" disabled type="button" class="btn btn-block btn-danger remove" data-id="<?=$value->id?>" data-toggle="modal" data-target="#modal-remove-order"><i class="fa fa-fw fa-times"></i></button>
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
            </div>
            <div class="col-12">
              <div class="float-right">
                <div class="form-group">
                  <input type="hidden" name="is_completey_clear" value=0>
                  <div class="icheck-primary d-inline">
                    <input class="custom-cotrol-input" type="checkbox" id="is_completey_clear" name="is_completey_clear">
                    <label for="is_completey_clear" class="custom-cotrol-label"><?=lang('is_completey_clear')?></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- Information Items END -->
      <!-- Information payment START -->
      <div class="card" style="display:none;">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-three"></i><?php echo lang('information_payment') ?></h3>
        </div>
        <div class="card-body">

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <h6><?= lang('subtotal') ?> :</h6>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp</span>
                  </div>
                  <input type="text" name="sub_total" id="sub_total" class="form-control currency" value="<?=$invoice->total_price?>" min="1" required readonly>
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
                      <input type="text" name="discount" id="discount" class="form-control currency" value="<?=$invoice->discounts?>" required readonly>
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
                      <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" value="<?=$invoice->shipping_cost?>" required readonly>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                    <h6><b><?= lang('grandtotal') ?> :</b></h6>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><b>Rp</b></span>
                      </div>
                      <input type="text" name="grand_total" id="grand_total" class="form-control currency" value="<?=$invoice->grand_total?>" min="1" required readonly>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                    <h6><?= lang('payment_type') ?></h6>
                    <select disabled class="custom-select" name="payment_type">
                      <option value="cash"><?= lang('cash') ?></option>
                      <option value="credit"><?= lang('credit') ?></option>
                    </select>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-lg-3 col-sm-12" style="display: none">
              <div class="form-group">
                <h6><?= lang('other_cost') ?> :</h6>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp</span>
                  </div>
                  <input readonly type="text" name="other_cost" id="other_cost" class="form-control" value="<?=$invoice->other_cost?>" required>
                </div>
              </div>
            </div>
            <div class="col-lg col-sm-12">
              <div class="form-group">
                <label for="note"><?= lang('note') ?></label>
                <textarea name="note" id="note" class="form-control"><?=$invoice->note?></textarea>
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
            <button type="button" class="btn btn-default mr-2" onclick="window.close()"><?= lang('cancel') ?></button>
          </div>
          <div class="float-left">
            <a href="<?=url('validation/warehouse/print?id='.get('id'))?>" target="_blank" class="btn btn-info float-right"><?= lang('print') ?></a>
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
  
  //Date range picker
  $('#date_due').daterangepicker({
    timePicker: true,
    timePicker24Hour: true,
    timePickerIncrement: 30,
    locale: {
      format: 'DD/MM/YYYY H:mm'
    }
  });
  $('.alert-status').bootstrapSwitch('state', true);
</script>
<!-- Jquery ui -->
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>

<script type="module" src="<?php echo $url->assets ?>pages/invoice/sale/MainOrderEdit.js"></script>