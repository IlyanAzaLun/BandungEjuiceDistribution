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
      <?php echo form_open_multipart('invoice/purchase/create', ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
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
                <input type="text" name="supplier_code" id="supplier_code" class="form-control" placeholder="<?= lang('find_supplier_code') ?>" autocomplete="false" required>
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
            <div class="col-12">
              <table class="table table-sm">
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
                  <tr class="input-0" id="main">
                    <td class="text-center"><div class="form-control form-control-sm">1.</div></td>
                    <td>
                      <input type="hidden" name="item_id[]" id="item_id" data-id="item_id">
                      <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" required>
                    </td>
                    <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required></textarea></td>
                    <td><input class="form-control form-control-sm" type="text" name="note[]" data-id="note"></td>
                    <td style="display:none" >
                      <div class="input-group input-group-sm">
                        <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required>
                        <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit">
                        <div class="input-group-append">
                          <span class="input-group-text" data-id="item_unit"></span>
                        </div>
                      </div>
                    </td>
                    <td>
                    <div class="input-group input-group-sm">
                      <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" value="0" required>
                      <div class="input-group-append">
                        <span class="input-group-text" data-id="item_unit"></span>
                      </div>
                    </div>
                    </td>
                    <td>
                      <input class="form-control form-control-sm" type="text" name="item_capital_price[]" data-id="item_capital_price" required>
                      <input class="form-control form-control-sm" type="hidden" name="item_capital_price_is_change[]" data-id="item_capital_price_is_change" value=0>
                    </td>
                    <td style="display:none" ><input class="form-control form-control-sm" type="text" name="item_selling_price[]" data-id="item_selling_price" required></td>
                    <td><input class="form-control form-control-sm" type="text" name="item_discount[]" data-id="discount" value="0" required></td>
                    <td><input class="form-control form-control-sm" type="text" name="total_price[]" data-id="total_price" value="0" required></td>
                    <td>
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                        <a target="_blank" class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                        <button type="button" class="btn btn-default" disabled><i class="fa fa-tw fa-times"></i></button>
                      </div>
                    </td>
                  </tr>
                  <tr class="description input-0" style="display:none">
                      <td colspan="8">
                          <textarea class="form-control form-control-sm" name="description[]"></textarea>
                      </td>
                  </tr>
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
                      <input type="text" name="sub_total" id="sub_total" class="form-control" value="0" min="1" required>
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
                      <input type="text" name="discount" id="discount" class="form-control" value="0" required>
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
                      <input type="text" name="shipping_cost" id="shipping_cost" class="form-control" value="0" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-sm-12" style="display: none">
                  <div class="form-group">
                    <h6><?= lang('other_cost') ?> :</h6>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input readonly type="text" name="other_cost" id="other_cost" class="form-control" value="0" required>
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
                      <input type="text" name="grand_total" id="grand_total" class="form-control" value="0" min="1" required>
                    </div>
                  </div>
                </div>
                <div class="col-lg-1 col-sm-12">
                  <div class="form-group">
                    <h6><?= lang('payment_type') ?></h6>
                    <select class="custom-select" name="payment_type">
                      <option value="cash"><?= lang('cash') ?></option>
                      <option value="credit"><?= lang('credit') ?></option>
                      <option value="consignment"><?= lang('consignment') ?></option>
                    </select>
                  </div>
                </div>
                
                <div class="col-lg-2 col-sm-12">
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
                <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                    <h6><?= lang('date_due') ?></h6>
                    <div class="input-group mb-3">
                      <input type="text" id="date_due" name="date_due" class="form-control" data-target="#date_due">
                      <div class="input-group-append" data-target="#date_due" data-toggle="daterangepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-11 col-sm-12">
                  <div class="form-group">
                    <label for="note"><?= lang('note') ?></label>
                    <textarea name="note" id="note" class="form-control"></textarea>
                  </div>
                </div>
                <div class="col-lg-1 col-sm-12">
                  <h6><?=lang('number_of_day')?></h6>
                  <div class="input-group">
                    <input type="text" class="form-control" id="numberdays" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox" id="is_consignment" name="is_consignment" value=1>
                      <label for="is_consignment" class="custom-control-label"><?=lang('is_consignment')?></label>
                    </div>
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

<script type="module" src="<?php echo $url->assets ?>pages/invoice/purchase/MainPurchaseCreate.js"></script>