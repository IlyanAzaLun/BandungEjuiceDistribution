<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('profit_n_loss') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>">Home</a></li>
          <li class="breadcrumb-item active"><?php echo lang('pages_report_information') ?></li>
          <li class="breadcrumb-item active"><?php echo lang('accounting') ?></li>
          <li class="breadcrumb-item active"><?php echo lang('profit_n_loss') ?></li>
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
      <h3 class="card-title"><?php echo lang('month_profit_n_loss') ?></h3>

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
          <?php echo form_open(url('master_information/accounting/profit_n_loss'), ['method' => 'POST', 'autocomplete' => 'off']); ?>
          <div class="row">
            <div class="col-12">
              <label for="">Monthly Summary: </label>
            </div>
            <div class="col-12 col-lg-2">
                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="profit_n_loss" id="profit_n_loss">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-2">
              <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-eye"></i>&nbsp;&nbsp;<?=lang('see-report')?></button>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
      
      <!-- ACCOUNT -->
      <div class="row" id="profit_n_loss">
        <div class="col-12">
          <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Particulars</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <?php $i = 1;?>
            <?php foreach ($accounts as $key => $value):?>
            <!--  -->
            <?php if($value->HeadLevel == 2):$i=1;?>
            <thead>
                <tr>
                    <th colspan="2"><?=$value->HeadName?></th>
                    <td id="<?=$value->HeadCode?>" class="text-right"><b class="text-left">Rp. </b><span class="currency"></span></td>
                </tr>
            </thead>
            <?php else:?>
            <!--  -->
            <tboday>
                <tr>
                    <td><?=$i;$i++?></td>
                    <td>[<?=$value->HeadCode?>] <?=$value->HeadName?></td>
                    <td id="<?=$value->HeadCode?>" class="text-right"><span class="text-left">Rp. </span><span class="currency"></span></td>
                </tr>
            </tboday>
            <?php endif; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" class="text-right">
                    <strong>Total Debit</strong>
                </td>
                <td class="text-right"><b class="text-left">Rp. </b><strong id="total_debit">0</strong>
                </td>
            </tr><tr>
                <td colspan="2" class="text-right">
                    <strong>Total Kredit</strong>
                </td>
                <td class="text-right"><b class="text-left">Rp. </b><strong id="total_credit">0</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">
                    <strong>Total Sisa</strong>
                </td>
                <td class="text-right"><b class="text-left">Rp. </b><strong id="total">0</strong>
                </td>
            </tr>
            <!--  -->
          </table>
        </div>
      </div> 
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php include viewPath('includes/footer'); ?>
<script type="module" src="<?php echo $url->assets ?>pages/accounting/profit_n_loss/MainProfitNLoss.js"></script>
<script>
  $('#profit_n_loss').daterangepicker({
    "singleDatePicker": true,
    "autoApply": true,
    "locale": {
        "format": "MMMM YYYY",
        "firstDay": 1
    },
  })
</script>