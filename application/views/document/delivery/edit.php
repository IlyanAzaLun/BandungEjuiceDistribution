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
        <?=lang('order_info_create') ?>
      </div>
      <?php echo form_open_multipart('document/delivery/edit?id='.$header->id, ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
      <!-- Information customer START -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_customer') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            
            <div class="col-lg col-sm-12">
              <div class="form-group">
                <label for="store_name"><?= lang('customer_code') ?></label>
                <input type="hidden" name="delivery_code" id="delivery_code" class="form-control" autocomplete="false" value="<?=$header->delivery_code?>" required>
                <input type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" autocomplete="false" value="<?=$header->customer_code?>" required>
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
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="<?= set_value('contact_phone') ?>" required>
              </div>
            </div>

            <div class="col-lg-12 col-sm-12">
              <div class="form-group">
                <label for="address"><?= lang('address_destination') ?></label>
                <textarea type="text" name="address" id="address" class="form-control" required><?= set_value('address') ?></textarea>
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
              <table class="table table-fixed table-sm" width="100%">
                <thead>
                  <tr>
                    <th width="2%">No.</th>
                    <th width="10%"><?= lang('item_code') ?></th>
                    <th><?= lang('item_name') ?></th>
                    <th style="display:none"><?= lang('item_quantity') ?></th>
                    <th width="10%"><?= lang('note') ?></th>
                    <th width="12%"><?= lang('item_order_quantity') ?></th>
                    <th style="display:none"><?= lang('item_capital_price') ?></th>
                    <th style="display:none" width="10%"><?= lang('item_selling_price') ?></th>
                    <th style="display:none" width="7%"><?= lang('discount') ?></th>
                    <th style="display:none" width="10%"><?= lang('total_price') ?></th>
                    <th width="10%"><?= lang('option') ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($contens as $key => $item): ?>
                  <tr class="input-<?=$key?>" id="main" style="<?=($item->is_canceled == 1)?'display:none':''?>">
                    <td class="text-center"><div class="form-control form-control-sm"><?=$key+1?>.</div></td>
                    <td>
                      <input type="hidden" name="id[]" id="id" data-id="id" value="<?=$item->id?>">
                      <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?=$item->item_id?>">
                      <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?=$item->item_code?>" required readonly>
                    </td>
                    <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required readonly><?=$item->item_name?></textarea></td>
                    <td><input class="form-control form-control-sm" type="text" data-id="note" readonly value="<?=$item->note?>"></td>
                    <td style="display:none" >
                      <div class="input-group input-group-sm">
                        <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" value="<?=$item->item_quantity?>" data-id="item_quantity" required readonly>
                        <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit">
                        <div class="input-group-append">
                          <span class="input-group-text" data-id="item_unit"></span>
                        </div>
                      </div>
                    </td>
                    <td>
                    <div class="input-group input-group-sm">
                      <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" value="<?=$item->item_quantity?>" required>
                      <input class="form-control form-control-sm" type="hidden" name="item_weight[]" data-id="item_weight" readonly>
                      <input class="form-control form-control-sm" type="hidden" name="item__total_weight[]" data-id="item__total_weight" value="<?=$item->item_total_weight?>" readonly>
                      <div class="input-group-append">
                        <span class="input-group-text" data-id="item_unit"><?=$item->item_unit?></span>
                      </div>
                    </div>
                    <td style="display:none"><input readonly class="currency form-control form-control-sm" type="text" name="item_capital_price[]" data-id="item_capital_price" required readonly></td>
                    <td style="display:none"><input class="currency form-control form-control-sm" type="text" name="item_selling_price[]" data-id="item_selling_price" required readonly></td>
                    </td>
                    <td style="display:none"><input class="currency form-control form-control-sm" type="text" name="item_discount[]" data-id="discount" value="0" required readonly></td>
                    <td style="display:none"><input class="currency form-control form-control-sm" type="text" name="total_price[]" data-id="total_price" value="0" required readonly></td>
                    <td>
                        <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                            <a target="_blank" href='<?=url("items/info_transaction?id=$item->item_code&customer=$header->customer_code")?>' class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                        <?php if (sizeof($contens) <= 1) : ?>
                            <button disabled type="button" class="btn btn-secondary"><i class="fa fa-tw fa-times"></i></button>
                        <?php else : ?>
                            <button type="button" class="btn btn-danger remove" data-itemscode="<?=$item->item_code?>" data-id="<?=$item->id?>" data-toggle="modal" data-target="#modal-remove-order"><i class="fa fa-tw fa-times"></i></button>
                        <?php endif ?>
                        </div>
                    </td>
                  </tr>
                  <tr class="description input-<?=$key?>" style="display:none">
                      <td colspan="9">
                          <textarea class="form-control form-control-sm" name="description[]"></textarea>
                      </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <div class="float-left ml-1">
                <button type="button" class="btn btn-sm btn-info" id="add_more"><?= lang('add_more') ?></button>
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
      <!-- Information Items -->
      <!-- Information payment START -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-three"></i><?php echo lang('information_delivery') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">

            <div class="col-lg-2 col-sm-12">
            </div>
            <div class="col-lg-2 col-sm-12">
              <div class="form-group">
                <label for="expedition"><?= lang('expedition') ?></label>
                <select class="custom-select" name="expedition_name" id="expedition_name" required>
                    <option value="" selected disabled><?=lang('option')?></option>
                    <?php foreach ($expedition as $key => $value):?>
                        <option value="<?= $value->expedition_name ?>"<?=($value->expedition_name == $header->expedition)?' selected':''?> data-services="<?= $value->services_expedition ?>"><?= $value->expedition_name ?></option>
                    <?php endforeach ?>
                </select>
              </div>
            </div>

            <div class="col-lg-2 col-sm-12">
              <div class="form-group">
                  <h6><?= lang('expedition_services') ?></h6>
                  <select class="custom-select" name="services_expedition" id="services_expedition">
                      <option value=""><?=lang('option')?></option>
                      <option value="<?=$header->services_expedition?>" selected><?=$header->services_expedition?></option>
                  </select>
              </div>
            </div>
            
            <div class="col-lg-3 col-sm-12">
              <div class="form-group">
                <label for="shipping_cost"><?= lang('shipping_cost') ?></label>
                <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" required value="<?=$header->shipping_cost?>"><?= set_value('shipping_cost') ?></input>
              </div>
            </div>

            <div class="col-lg-3 col-sm-12">
              <div class="form-group">
                <h6><?=lang('date')?></h6>
                  <div class="input-group">
                    <input type="text" id="created_at" name="created_at" class="form-control" data-target="#created_at"/>
                    <div class="input-group-append" data-target="#created_at" data-toggle="daterangepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
              </div>
            </div>  

            <div class="col-lg-12 col-sm-12">
              <div class="form-group">
                <label for="note"><?= lang('note') ?></label>
                <textarea type="text" name="note" id="note" class="form-control" required><?= set_value('note') ?><?=$header->note?></textarea>
              </div>
            </div>  
            
            <div class="col-lg-12 col-sm-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="shipping_cost_to_invoice" value=0>
                        <input class="custom-control-input" type="checkbox" id="shipping_cost_to_invoice" name="shipping_cost_to_invoice" value=1>
                        <label for="shipping_cost_to_invoice" class="custom-control-label"><?=strtolower(lang('is_shipping_cost_to_invoice'))?></label>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Information Items END -->
      <!-- Information  -->
      <div class="card">
        <div class="card-footer">
          
          <div class="float-left">
            <a href="<?=url('document/delivery/print?id='.get('id'))?>" type="button" class="btn btn-default"><?= lang('print') ?></a>
          </div>

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
  $(document).ready(function () {
    $('input#store_name').focus();
  })
  
  $(document).ready(function () {
      $('.remove').on('click', function(){
          let id = $(this).data('id');
          let itemsCode = $(this).data('itemscode');
          console.log(id)
          $('#modal-remove-order').on('shown.bs.modal', function(){
              $(this).find('input#id').val(id);
              $(this).find('input#idorder').val(itemsCode);
          })
      })
  });

  $('#expedition_name').on('change', function(){
      const expedition = $(this).find(":selected");
      const services = expedition.data('services')
      const service = services.split(',');
      const data_services = expedition.data('service_selected');
      $('#services_expedition').empty();
      $('#services_expedition').append(`<option value="">Opsi</option>`)
      service.forEach(element => {
          $('#services_expedition').append(`<option value="${element}">${element}</option>`)
      });
  });
  // $('body').addClass('sidebar-collapse');
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

<script type="module" src="<?php echo $url->assets ?>pages/document/delivery/MainDocumentDeliveryEdit.js"></script>