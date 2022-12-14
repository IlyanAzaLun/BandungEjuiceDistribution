<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/handsontable/dist/handsontable.full.min.css" />


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('journal') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>">Home</a></li>
          <li class="breadcrumb-item active"><?php echo lang('pages_report_information') ?></li>
          <li class="breadcrumb-item active"><?php echo lang('accounting') ?></li>
          <li class="breadcrumb-item active"><?php echo lang('journal') ?></li>
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
          <?php echo form_open(url('master_information/accounting/report_journal'), ['method' => 'POST', 'autocomplete' => 'off']); ?>
          <div class="row">
            <div class="col-12 col-lg-2">
              <label for="">Journal Date</label>
            </div>
            <div class="col-12 col-lg-1">
              <label for="">Group By</label>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-lg-2">
                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="journal_date" id="journal_date">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                </div>
            </div>            
            <div class="col-12 col-lg-1">
              <select name="group" class="form-control form-control-sm">
                <option value="month" selected>Month</option>
                <option value="year">Year</option>
              </select>
            </div>
            <div class="col-12 col-lg-2">
              <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-eye"></i>&nbsp;&nbsp;<?=lang('see-report')?></button>
            </div>
          </div>
          <?php echo form_close(); ?>
          <?php echo form_open(url('master_information/accounting/journal'), ['method' => 'POST', 'autocomplete' => 'off']); ?>
          <input type="hidden" name="journal_date" id="journal_input" value="">
          <div id="parenttable">
            <div id="handsontable"></div>
          </div>
          <div class="total">
            <table class="table text-right">
                <tbody>
                    <tr>
                      <td class="text-right bold"><?php echo lang('debit'); ?></td>
                      <td width="10%" class="total_debit">0</td>
                    </tr>
                  <tr>
                      <td class="text-right bold"><?php echo lang('credit'); ?></td>
                      <td width="10%" class="total_credit">0</td>
                  </tr>
                </tbody>
            </table>
          </div>
          <?php echo form_hidden('journal_entry'); ?>
          <?php echo form_hidden('amount'); ?>
          <div class="float-right">
            <button type="submit" class="btn btn-info float-right"><?= lang('save') ?></button>
            <button type="button" class="btn btn-default mr-2" onclick="history.back()"><?= lang('back') ?></button>
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
<script type="text/javascript" src="<?php echo $url->assets ?>plugins/handsontable/dist/handsontable.full.js"></script>
<script type="module" src="<?php echo $url->assets ?>pages/accounting/journal/MainJournal.js"></script>
<script>
  $('#journal_date').daterangepicker({
      "singleDatePicker": true,
      "autoApply": true,
      "locale": {
        "format": "DD/MM/YYYY",
        "firstDay": 1
    },
  },function(start, end, label) {
    $('input#journal_input').val(start.format('DD/MM/YYYY'));
  });
  
  $('input#journal_input').val($('#journal_date').val());
</script>