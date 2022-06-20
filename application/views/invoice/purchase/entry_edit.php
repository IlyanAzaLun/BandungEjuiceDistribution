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
        <?= lang('purchase_info_create') ?>
      </div>
      <?php echo form_open_multipart('invoice/purchases/entry/edit_entry?id='.get('id'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
      <!-- Information Items START -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-two"></i><?php echo lang('information_items') ?></h3>
        </div>
        <div class="card-body">
          <pre><?php var_dump($items)?></pre>
          <div class="row" id="order_item">
            <div class="col-12">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th width="2%">No.</th>
                    <th width="10%"><?= lang('item_code') ?></th>
                    <th><?= lang('item_name') ?></th>
                    <th style=""><?= lang('item_quantity') ?></th>
                    <th width="10%"><?= lang('note') ?></th>
                    <th width="12%"><?= lang('item_order_quantity') ?></th>
                    <th width="10%"><?= lang('item_capital_price') ?></th>
                    <th style=""><?= lang('item_selling_price') ?></th>
                    <th width="7%"><?= lang('discount') ?></th>
                    <th width="10%"><?= lang('total_price') ?></th>
                    <th width="7%"><?= lang('option') ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $key => $value) : ?>
                  <tr class="input-<?=$key?>" id="main">
                    <td class="text-center"><div class="form-control form-control-sm"><?=$key+1?>.</div></td>
                    <td>
                      <input type="text" name="id[]" id="id" value="<?= $value->id ?>">
                      <input type="text" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                      <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?=$value->item_code?>" required>
                    </td>
                    <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required><?=$value->item_name?></textarea></td>
                    <td><input class="form-control form-control-sm" type="text" name="note[]" data-id="note" value="<?=$value->note?>"></td>
                    <td style="" >
                      <div class="input-group input-group-sm">
                      <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?>">
                      <input readonly class="form-control form-control-sm" type="text" name="item_quantity_current[]" data-id="item_quantity_current" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') - $value->item_quantity ?>">
                        <input type="text" name="item_unit[]" id="item_unit" data-id="item_unit" value="<?= $value->item_unit ?>">
                        <div class="input-group-append">
                          <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                        </div>
                      </div>
                    </td>
                    <td>
                    <div class="input-group input-group-sm">
                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" required value="<?= (int)$value->item_quantity ?>">
                    <input readonly class="form-control form-control-sm" type="text" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" required value="<?= (int)$value->item_quantity ?>" style="display:none">
                      <div class="input-group-append">
                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                      </div>
                    </div>
                    </td>
                    <td>
                      <input class="form-control form-control-sm currency" type="text" name="item_capital_price[]" data-id="item_capital_price" value="<?=$value->item_capital_price?>" required>
                    </td>
                    <td style="" ><input class="form-control form-control-sm currency" type="text" name="item_selling_price[]" data-id="item_selling_price" required value="<?=$value->item_selling_price?>"></td>
                    <td><input class="form-control form-control-sm currency" type="text" name="item_discount[]" data-id="discount" value="<?=$value->discount?>" required></td>
                    <td><input class="form-control form-control-sm currency" type="text" name="total_price[]" data-id="total_price" value="<?=$value->total_price?>" required></td>
                    <td>
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                        <a target="_blank" class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                        <button type="button" class="btn btn-default" disabled><i class="fa fa-tw fa-times"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr class="description input-<?=$key?>" style="">
                      <td colspan="8">
                          <textarea class="form-control form-control-sm" name="description[]"></textarea>
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
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-three"></i><?php echo lang('information_payment') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="note"><?= lang('note') ?></label>
                <textarea name="note" id="note" class="form-control"></textarea>
              </div>
            </div>
          </div>
        </div>
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

<script type="module" src="<?php echo $url->assets ?>pages/invoice/purchase/MainEntryEdit.js"></script>