<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

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
            <h3 class="card-title">DataTable with minimal features & hover style</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <?php echo form_open('invoice/purchases/payment/debt', ['method' => 'POST', 'autocomplete' => 'off']); ?>
            <div class="row">
              <div class="col-10">
                <div class="row">

                  <div class="col-lg-4 col-sm-12">
                    <div class="form-group input-group">
                      <input class="form-control" type="text" id="min" name="min">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-8 col-sm-12"></div>

                  <div class="col-lg-3 col-sm-12">
                    <input class="form-control" type="hidden" id="customer_code" name="customer_code" required>
                    <div class="form-group">
                      <input class="form-control" type="text" id="customer_name" name="customer_name" required>
                      <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
                    </div>
                  </div>
                  <div class="col-lg-1 col-sm-12">
                    <button class="btn btn-block btn-info">Search</button>
                  </div>

                </div>
              </div>
              <div class="col-2">
              <?php if (hasPermissions('sale_create')) : ?>
                <!-- EMPTY -->
              <?php endif ?>
              </div>
            </div>
            <?php echo form_close(); ?>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <div class="card">
          <div class="card-body">
            
          </div>
        </div>

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
  $(function() {
    //Date range picker
    $('#min').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      startDate: moment().startOf('years').format('DD/MM/YYYY H:mm'),
      locale: {
        format: 'DD/MM/YYYY H:mm'
      }
    });
    $('.ui-buttonset').draggable();
  });
</script>