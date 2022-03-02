<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

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
          <li class="breadcrumb-item active"><?php echo lang('supplier') ?></li>
          <li class="breadcrumb-item active"><?php echo lang($title) ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?= lang('supplier_info_add') ?></h3>
          </div>
          <!-- /.card-header -->
          <?php echo form_open_multipart('master_information/supplier/add', ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
          <div class="card-body">

            <div class="row">

              <div class="col-sm-2">
                <!-- text input -->
                <div class="form-group">
                  <label><?= lang('supplier_code') ?></label>
                  <input type="text" class="form-control" name="customer_code" id="customer_code" readonly value="<?= $supplier_code ?>">
                </div>
              </div>

              <div class="col-sm-2">
                <!-- text input -->
                <div class="form-group">
                  <label><?= lang('store_name') ?></label>
                  <input type="text" class="form-control" name="store_name" id="store_name" value="<?= set_value('store_name') ?>" required>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="row">
                  <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                      <label><?= lang('supplier_owner') ?></label>
                      <input type="text" class="form-control" name="owner_name" id="owner_name" value="<?= set_value('owner_name') ?>" required>
                      <?= form_error('owner_name', '<small class="text-danger">', '</small>') ?>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label><?= lang('category_supplier') ?></label>
                      <select class="form-control select2" style="width: 100%;" name="supplier_type" id="category" value="<?= set_value('category') ?>" required>
                        <option value="" selected="selected"><?= lang('select_category_supplier') ?></option>
                        <option value="WS"><?= lang('supplier_category_ws') ?></option>
                        <option value="DISTRIBUTION"><?= lang('supplier_category_distribution') ?></option>
                        <option value="OTHER"><?= lang('supplier_category_other') ?></option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col">
                <!-- text input -->
                <div class="form-group">
                  <label><?= lang('note') ?></label>
                  <textarea type="text" class="form-control" name="note" id="note"><?= set_value('note') ?></textarea>
                  <?= form_error('note', '<small class="text-danger">', '</small>') ?>
                </div>
              </div>
            </div>

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <div class="float-right">
              <button type="submit" class="btn btn-primary float-right"><?= lang('save') ?></button>
              <button type="button" class="btn btn-default mr-2"><?= lang('cancel') ?></button>
            </div>
          </div>
          <?php echo form_close(); ?>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>

<script>
  $('.select2').select2();
</script>