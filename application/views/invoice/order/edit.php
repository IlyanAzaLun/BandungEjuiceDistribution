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
  <pre><?php var_dump($invoice->payment_type);?></pre>

  <div class="row">
    <div class="col-12">
      <div class="callout callout-info">
        <h5><i class="fas fa-info"></i> Note:</h5>
        <?= lang('order_info_edit') ?>
      </div>
      <?php echo form_open_multipart('invoice/order/edit?id='.get('id'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
      <!-- Information customer START -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_customer') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            
            <?php if(hasPermissions('warehouse_order_list')):?>
            <div class="col-lg-1 col-sm-12">
              <div class="form-group">
                <label for="marketing"><?= lang('marketing') ?></label>
                <input type="hidden" name="is_have" id="is_have" value="<?=$invoice->is_have?>">
                <input type="text" name="marketing" id="marketing" class="form-control" value="<?=$this->db->get_where('users', array('id'=> $invoice->is_have))->row()->name?>" required>
              </div>
            </div>
            <?php endif ?>
            
            <div class="col-lg col-sm-12">
              <div class="form-group">
                <label for="store_name"><?= lang('customer_code') ?></label>
                <input type="hidden" name="order_code" id="order_code" class="form-control" value="<?=$invoice->order_code?>" autocomplete="false" readonly required>
                <input type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" value="<?=$invoice->customer?>" autocomplete="false" required>
                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>

            <div class="col-lg-4 col-sm-12">
              <div class="form-group">
                <label for="store_name"><?= lang('store_name') ?></label>
                <input type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" required>
                <?= form_error('store_name', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>

            <div class="col-lg-4 col-sm-12">
              <div class="form-group">
                <label for="contact_phone"><?= lang('contact_phone') ?><small class="text-primary"> (whatsapp)</small></label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="<?= set_value('contact_phone') ?>" required readonly>
              </div>
            </div>

            <div class="col-lg-12 col-sm-12">
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
                    <th width="8%"><?= lang('item_code') ?></th>
                    <th><?= lang('item_name') ?></th>
                    <th width="7%"><?= lang('note') ?></th>
                    <th style="display: none"><?= lang('item_quantity') ?></th>
                    <th width="11%"><?= lang('item_order_quantity') ?></th>
                    <th style="display: none"><?= lang('item_capital_price') ?></th>
                    <th width="10%"><?= lang('item_selling_price') ?></th>
                    <th width="7%"><?= lang('discount') ?></th>
                    <th width="10%"><?= lang('total_price') ?></th>
                    <th width="9%"><?= lang('status_available') ?></th>
                    <th width="10%"><?= lang('option') ?></th>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $key => $value) : ?>
                        <tr class="input-<?= $key ?>" id="main">
                            <td class="text-center"><div class="form-control form-control-sm"><?=$key+1?>.</div></td>
                            <td>
                                <input type="hidden" name="id[]" id="id" value="<?= $value->id ?>">
                                <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                                <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?= $value->item_code ?>" readonly required>
                            </td>
                            <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" readonly required><?= $value->item_name ?></textarea></td>
                            <td><input class="form-control form-control-sm" type="text" data-id="note" value="<?= $this->items_model->getByCodeItem($value->item_code, 'note') ?>"></td>
                            <td style="display: none">
                                <div class=" input-group input-group-sm">
                                    <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?>">
                                    <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit" value="<?= $value->item_unit ?>">
                                    <span class="input-group-append">
                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                    </span>
                                </div>
                                <input readonly class="form-control form-control-sm" type="text" name="item_quantity_current[]" data-id="item_quantity_current" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') - $value->item_order_quantity ?>">
                            </td>
                            <td>
                                <div class=" input-group input-group-sm">
                                    <span class="input-group-prepend" style="display: none">
                                        <span class="input-group-text" data-id="item_quantity"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?></span>
                                    </span>
                                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" max="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') + $value->item_order_quantity?>" required value="<?= (int)$value->item_order_quantity ?>">
                                    <input class="form-control form-control-sm" type="hidden" name="item_weight[]" data-id="item_weight" value="<?=$this->items_model->getByCodeItem($value->item_code, 'weight')?>" readonly>
                                    <input class="form-control form-control-sm" type="hidden" name="item__total_weight[]" data-id="item__total_weight" value="<?=$value->item_order_quantity*$this->items_model->getByCodeItem($value->item_code, 'weight')?>" readonly>
                                    <span class="input-group-append">
                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                    </span>
                                </div>
                                <input style="display: none" readonly class="form-control form-control-sm" type="text" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" required value="<?= (int)$value->item_order_quantity ?>">
                            </td>
                            <td style="display: none"><input class="form-control form-control-sm currency" type="text" name="item_capital_price[]" data-id="item_capital_price" required value="<?= $value->item_capital_price ?>" readonly></td>
                            <td><input class="form-control form-control-sm currency" type="text" name="item_selling_price[]" data-id="item_selling_price" required value="<?= $value->item_selling_price ?>"></td>
                            <td><input class="form-control form-control-sm currency" type="text" name="item_discount[]" data-id="discount" min="0" required value="<?= (int)$value->item_discount ?>"></td>
                            <td><input class="form-control form-control-sm currency" type="text" name="total_price[]" data-id="total_price" min="0" required readonly value="<?= $value->item_total_price ?>"></td>
                            <td class="text-center">
                              <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                                <button type="button" class="btn bg-<?=($value->status_available == null ?'secondary':($value->status_available == 0?'danger':'secondary'))?>" disabled id="status_availabel" ><i class="fas fa-tw fa-times"></i></button>
                                <button type="button" class="btn bg-<?=($value->status_available)?'success':'secondary'?>" disabled id="status_availabel" ><i class="fas fa-tw fa-check"></i></button>
                              </div>
                            </td>
                            <td>
                                <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                                    <a target="_blank" href='<?=url("items/info_transaction?id=$value->item_code&customer=$invoice->customer")?>' class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                                <?php if (sizeof($items) <= 1) : ?>
                                    <button disabled type="button" class="btn btn-block btn-secondary"><i class="fa fa-tw fa-times"></i></button>
                                <?php else : ?>
                                    <button type="button" class="btn btn-block btn-danger remove" data-id="<?=$value->item_code?>" data-indexlist="<?=$value->index_list?>" data-idorder="<?=$value->id?>" data-toggle="modal" data-target="#modal-remove-order"><i class="fa fa-tw fa-times"></i></button>
                                <?php endif ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="description input-<?=$key?>" style="display:<?=(!strlen($value->item_description))?'none':''?>">
                            <td colspan="8">
                                <textarea class="form-control form-control-sm" name="description[]"> <?=$value->item_description?> </textarea>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
              </table>
              <div class="float-left ml-1">
                <button type="button" class="btn btn-sm btn btn-info" id="add_more"><?= lang('add_more') ?></button>
              </div>
              <!-- Total Items -->
              <div class="float-right ml-1">
                <div class="">
                  <div class="input-group input-group-sm">
                      <h6 id="total_weights_item">Total Weight Items: 0 Kg</h6>
                  </div>
                  <div class="input-group input-group-sm">
                      <h6 id="total_items">Total Items: 0</h6>
                  </div>
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
        <div class="card-body">

          <div class="row">
            <div class="col-12">
              <div class="row">
                <div class="col-lg-6 col-12">
                  <div class="form-group">
                    <h6><?= lang('subtotal') ?> :</h6>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input readonly type="text" name="sub_total" id="sub_total" class="form-control currency" value="<?=$invoice->total_price?>" min="1" required>
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
                      <input type="text" name="discount" id="discount" class="form-control currency" value="<?=$invoice->discounts?>" required>
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
                      <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" value="<?=$invoice->shipping_cost?>" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-sm-12">
                  <div class="form-group">
                    <h6><b><?= lang('grandtotal') ?> :</b></h6>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><b>Rp</b></span>
                      </div>
                      <input readonly type="text" name="grand_total" id="grand_total" class="form-control currency" value="<?=$invoice->grand_total?>" min="1" required>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                    <h6><?= lang('payment_type') ?></h6>
                    <input type="hidden" name="payment_type" value="credit">
                    <select class="custom-select" name="payment_type" <?=(!hasPermissions('sale_edit'))?'disabled':''?>>
                      <option value="credit" <?=($invoice->payment_type == "credit")?'selected':''?>><?= lang('credit') ?></option>
                      <option value="cash" <?=($invoice->payment_type == "cash")?'selected':''?>><?= lang('cash') ?></option>
                    </select>
                  </div>
                </div>
                
                <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                    <h6><?=lang('date')?></h6>
                      <div class="input-group">
                        <input type="text" id="created_at" name="created_at" class="form-control" data-target="#created_at" value="<?=date("d/m/Y H:i:s",strtotime($invoice->created_at))?>"/>
                        <div class="input-group-append" data-target="#created_at" data-toggle="daterangepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
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
      <?php if ($invoice->is_cancelled == 0):?>
      <div class="card">
        <div class="card-footer">
          <div class="float-right">
            <button type="submit" class="btn btn-info float-right"><?= lang('save') ?></button>
            <button type="button" class="btn btn-default mr-2" onclick="history.back()" ><?= lang('back') ?></button>
          </div>
          <div class="float-left">
            <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#alertmodals" data-placement="top" title="Remove this information"><i class="fa fa-fw fa-trash"></i>&nbsp;&nbsp;<?=lang('delete_data')?></button>
          </div>
        </div>
      </div>
      <?php endif;?>
      <!-- /.card -->
      <?php echo form_close(); ?>
    </div>
  </div>
</section>
<!-- /.content -->
<div id="alertmodals" class="modal fade" id="btn" tabindex="-1" role="dialog" aria-labelledby="btnLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="btnLabel">Delete Information.?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Let me hidding on background...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="<?=url('invoice/order/cancel?id='.get('id'))?>" class="btn btn-danger mr-2" data-toggle="tooltip" data-placement="top" title="Remove this information">Yes ...</a>      
      </div>
    </div>
  </div>
</div>
<?php include viewPath('includes/footer'); ?>
<script>
  $('body').addClass('sidebar-collapse');
  $(document).ready(function () {
      $('.remove').on('click', function(){
          let id = $(this).data('id');
          let indexList = $(this).data('indexlist');
          let idOrder = $(this).data('idorder');
          $('#modal-remove-order').on('shown.bs.modal', function(){
              $(this).find('input#id').val(id);
              $(this).find('input#idorder').val(idOrder);
              $(this).find('input#indexlist').val(indexList);
          })
      })
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
<!-- Jquery ui -->
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>

<script type="module" src="<?php echo $url->assets ?>pages/invoice/sale/MainOrderEdit.js"></script>