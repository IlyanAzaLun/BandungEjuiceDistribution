<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
          <li class="breadcrumb-item active"><?php echo lang('invoice') ?></li>
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
        <?=lang('purchase_info_create')?>
      </div>
      <?php echo form_open_multipart('invoice/purchase/create', [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
      <!-- Information customer START -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_customer') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="store_name"><?=lang('customer_code')?></label>
                <input type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?=lang('find_customer_code')?>" autocomplete="false" required>
                <?= form_error('customer_code','<small class="text-danger">','</small>') ?>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for="store_name"><?=lang('store_name')?></label>
                <input type="text" name="store_name" id="store_name" class="form-control" placeholder="<?=lang('find_store_name')?>" autocomplete="false" required>
                <?= form_error('store_name','<small class="text-danger">','</small>') ?>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for="contact_phone"><?=lang('contact_phone')?><small class="text-primary"> (whatsapp)</small></label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="<?= set_value('contact_phone') ?>" required readonly>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <label for="address"><?=lang('address_destination')?></label>
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
              <div class="form-group">
                <input type="text" name="order_id" id="order_id" class="form-control" placeholder="order_id" readonly>
              </div>
            </div>
            <div class="col-12">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th><?=lang('item_code')?></th>
                  <th><?=lang('item_name')?></th>
                  <th><?=lang('item_quantity')?></th>
                  <th><?=lang('item_selling_price')?></th>
                  <th><?=lang('item_order_quantity')?></th>
                  <th><?=lang('discount')?></th>
                  <th><?=lang('option')?></th>
                </tr>
              </thead>
              <tbody>
                <tr class="input-0">
                  <td><input class="form-control form-control-sm" type="text" name="item_code"></td>
                  <td><input class="form-control form-control-sm" type="text" name="item_name"></td>
                  <td><input class="form-control form-control-sm" type="text" name="item_quantity"></td>
                  <td><input class="form-control form-control-sm" type="text" name="iten_selling_price"></td>
                  <td><input class="form-control form-control-sm" type="text" name="iten_order_quantity"></td>
                  <td><input class="form-control form-control-sm" type="text" name="discount"></td>
                  <td><button disabled type="button" class="btn btn-block btn-secondary"><i class="fa fa-tw fa-times"></i></button></td>
                </tr>
              </tbody>
            </table>
            <div class="float-right mr-1">
              <button type="button" class="btn btn btn-info" id="add_more"><?=lang('add_more')?></button></div>
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
              <div class="form-group">
                <h6><?=lang('subtotal')?> :</h6>
                <input type="text" name="sub_total" id="sub_total" class="form-control" required>
              </div>
              <div class="row">
                <div class="col-lg-6 col-sm-12">
                  <div class="form-group">
                    <h6>Discount :</h6>
                    <div class="input-group mb-3">
                      <input type="number" name="discount" id="discount" class="form-control" value="0" required>
                      <div class="input-group-append">
                        <span class="input-group-text">%</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                  <div class="form-group">
                    <h6><?=lang('shipping_cost')?> :</h6>
                    <input type="text" name="shipping_cost" id="shipping_cost" class="form-control" value="0" required>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-lg-6 col-sm-12">
              <div class="form-group">
                <h6><?=lang('other_cost')?> :</h6>
                <input type="text" name="other_cost" id="other_cost" class="form-control" value="0" required>
              </div>
            </div>
            <div class="col-lg-6 col-sm-12">
              <div class="form-group">
                <h6><b><?=lang('grandtotal')?> :</b></h6>
                <input type="text" name="grand_total" id="grand_total" class="form-control" required>
              </div>
            </div>
            <div class="col-lg col-sm-12">
              <div class="form-group">
                <label for="note"><?=lang('note')?></label>
                <textarea name="note" id="note" class="form-control"></textarea>
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
            <button type="submit" class="btn btn-primary float-right"><?=lang('save')?></button>
            <button type="cancel" class="btn btn-default mr-2"><?=lang('cancel')?></button>
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

<script type="module" src="<?php echo $url->assets ?>pages/invoice/purchase/MainPurchaseCreate.js"></script>