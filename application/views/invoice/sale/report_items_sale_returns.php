<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

<?php include viewPath('includes/header'); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('sale_returns') ?></h1>
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
      <h3 class="card-title">Export <?php echo lang('sale_returns') ?></h3>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-4">
          <div class="input-group">
            <input class="form-control" type="text" id="min" name="min">
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-calendar"></i></span>
            </div>
          </div>
        </div>
        <div class="col-8">
          <a href="<?php echo url('master_information/report/download_report_transaction_items?params=sale_returns') ?>" class="btn btn-lg btn-info"> <i class="fa fa-download"></i> &nbsp;&nbsp;&nbsp; <?php echo lang('report_generate_message') ?></a>
        </div>
      </div>
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
        format: 'DD/MM/YYYY H:mm'
      }
    });
</script>