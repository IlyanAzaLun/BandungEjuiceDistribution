<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
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
        <h1><?php echo lang('sale') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>"><?php echo lang('home') ?></a></li>
          <li class="breadcrumb-item active"><?php echo lang($title) ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Default card -->
  <div class="card">
    <div class="card-header with-border">
      <h3 class="card-title">Export <?php echo lang('sale') ?></h3>
    </div>

    <div class="card-body">
      <?php echo form_open_multipart('master_information/report/download_report_order_items', [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
        <div class="row">
          <div class="col-4">
            <div class="row">
              <div class="col-12">
              <input type="hidden" name="params" id="params" value="sale">
                <div class="input-group form-group">
                  <input class="form-control" type="text" id="min" name="min">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-4">
            <button class="btn btn-lg btn-info" type="submit"> <i class="fa fa-download"></i> <?php echo lang('report_generate_message') ?></button>
          </div>
        </div>
      <?php echo form_close(); ?>
    </div>
    <!-- /.card-body -->

  </div>
  <!-- /.card -->

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
<script>
  //Date range picker
  $('#min').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      locale: {
        format: 'YYYY/MM/DD H:mm'
      }
    });
</script>
<script type="module" src="<?php echo $url->assets ?>pages/report/sale/mainSaleItems.js"></script>