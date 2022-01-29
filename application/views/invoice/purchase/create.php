<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
      <!-- Information customer START -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_customer') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" name="user_id" id="user_id" class="form-control" placeholder="customer_id" value="<?= set_value('user_id') ?>" readonly>
              </div>
            </div>

            <div class="col-sm-12 col-lg-6">
              <div class="form-group">
                <label for="fullname">Nama toko</label>
                <input type="text" name="fullname" id="fullname" class="form-control" value="<?= set_value('fullname') ?>" autocomplete="false" required>
                <?= form_error('user_id','<small class="text-danger">','</small>') ?>
              </div>
            </div>

            <div class="col-sm-12 col-lg-6">
              <div class="form-group">
                <label for="contact_number">Nomor kontak <small class="text-primary">(whatsapp)</small></label>
                <input type="text" name="contact_number" id="contact_number" class="form-control" value="<?= set_value('contact_number') ?>" required readonly>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <label for="address">Alamat atau tujuan</label>
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
            <div class="col-10">
              <div class="form-group">
                <label for="item_name">Cari nama barang...</label>
                <input required type="hidden" id="item_id" class="form-control" autocomplete="off">
                <input required type="text" id="item_name" class="form-control" placeholder="Cari barang..." autocomplete="off">
              </div>
              <?= form_error('item_name[]','<small class="text-danger">','</small>') ?>
              <?= form_error('quantity[]','<small class="text-danger">','</small>') ?>
              <?= form_error('unit[]','<small class="text-danger">','</small>') ?>
            </div>
            <div class="col-2">
              <label for="">&nbsp;</label>
              <button type="button" class="btn btn-block btn-primary" id="add_order_item"><i class="fa fa-tw fa-plus"></i></button>
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
                <h6>Sub total :</h6>
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
                    <h6>Ongkos kirim :</h6>
                    <input type="text" name="shipping_cost" id="shipping_cost" class="form-control" value="0" required>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-lg-6 col-sm-12">
              <div class="form-group">
                <h6>Biaya lainnya :</h6>
                <input type="text" name="other_cost" id="other_cost" class="form-control" value="0" required>
              </div>
            </div>
            <div class="col-lg-6 col-sm-12">
              <div class="form-group">
                <h6>Grand total :</h6>
                <input type="text" name="grand_total" id="grand_total" class="form-control" required>
              </div>
            </div>
            <div class="col-lg col-sm-12">
              <div class="form-group">
                <label for="note">Catatan</label>
                <textarea name="note" id="note" class="form-control"></textarea>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- Information payment END -->
    <!-- /.card -->
    </div>
  </div>
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
<script>
  $('body').addClass('sidebar-collapse');
</script>