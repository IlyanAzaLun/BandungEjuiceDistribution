<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('balance_sheet') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>">Home</a></li>
          <li class="breadcrumb-item active"><?php echo lang('pages_report_information') ?></li>
          <li class="breadcrumb-item active"><?php echo lang('accounting') ?></li>
          <li class="breadcrumb-item active"><?php echo lang('balance_sheet') ?></li>
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
      <h3 class="card-title"><?php echo lang('list_all_activities') ?></h3>

      <div class="card-tools pull-right">
        <button type="button" class="btn btn-card-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-card-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>

    </div>
    <div class="card-body">

      <div class="row">
        <div class="col-sm-12">
          <?php echo form_open(url('master_information/accounting/balance_sheet'), ['method' => 'POST', 'autocomplete' => 'off']); ?>
          <div class="row">
            <div class="col-12 col-lg-2">
                <label for=""><?=lang('balance_sheet_date')?></label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="balance_sheet" id="balance_sheet">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php include viewPath('includes/footer'); ?>
<script type="module" src="<?php echo $url->assets ?>pages/accounting/balance_sheet/MainBalanceSheet.js"></script>
<script>
  $('#balance_sheet').daterangepicker({
    "singleDatePicker": true,
    "autoApply": true,
    "locale": {
        "format": "YYYY-MM",
        "firstDay": 1
    },
  })
</script>