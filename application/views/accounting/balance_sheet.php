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
      <h3 class="card-title"><?php echo lang('month_balance_sheet') ?></h3>

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
            <div class="col-12">
              <label for="">Monthly Summary: </label>
            </div>
            <div class="col-12 col-lg-2">
                <div class="input-group mb-2">
                    <input type="text" class="form-control form-control-sm" name="balance_sheet" id="balance_sheet">
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
        </div>
      </div>
      
      <!-- ACCOUNT -->
      <div class="row" id="balance_sheet">
        <div class="col-12">
          <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
                <tr>
                    <th width="3%">#</th>
                    <th colspan="5">Particulars</th>
                    <th width="15%">Balance</th>
                </tr>
            </thead>
            <?php $i = 1;?>
            <?php foreach ($accounts as $key => $value):?>
            <!--  -->
            <?php if($value->HeadLevel == 2):$i=1;?>
            <thead>
                <tr>
                    <th colspan="6"><?=$value->HeadName?></th>
                    <td id="<?=$value->HeadCode?>" class="text-right"><b class="text-left">Rp. </b><span class="currency"></span></td>
                </tr>
            </thead>
            <?php else:?>
            <!--  -->
            <tboday>
                <tr>
                    <td><?=$i;$i++?></td>
                    <td colspan="5">[<?=$value->HeadCode?>] <?=$value->HeadName?></td>
                    <td id="<?=$value->HeadCode?>" class="text-right"><span class="text-left">Rp. </span><span class="currency"></span></td>
                </tr>
            </tboday>
            <?php endif; ?>
            <?php endforeach; ?>
            <tfoot>
              <tr>
                  <td class="text-right" width="3%">  
                  </td>
                  <td class="text-right" width="15%">
                    <strong class="float-left">JUMLAH AKTIVA LANCAR</strong> <b class="text-left">Rp. </b><strong id="">0</strong>
                  </td>
                  
                  <td class="text-right" width="15%">
                    <strong class="float-left">JUMLAH AKTIVA TETAP</strong> <b class="text-left">Rp. </b><strong id="">0</strong>
                  </td>
                  
                  <td class="text-right" width="15%"> 
                    <strong class="float-left">JUMLAH KEWAJIBAN</strong> <b class="text-left">Rp. </b><strong id="">0</strong>  
                  </td>
                  
                  <td class="text-right" width="15%">  
                  </td>
                  
                  <td class="text-right" width="15%">  
                  </td>
                  
                  <td class="text-right">
                    <strong class="float-left">Total Debit</strong> <b class="text-left">Rp. </b><strong id="total_debit">0</strong>
                  </td>
              </tr>
              <tr>
                  <td class="text-right" width="3%">  
                  </td>
                  <td class="text-right" width="15%">
                    <strong class="float-left">JUMLAH AKTIVA LANCAR LAINNYA</strong> <b class="text-left">Rp. </b><strong id="">0</strong>
                  </td>
                  
                  <td class="text-right" width="15%">
                  </td>
                  
                  <td class="text-right" width="15%"> 
                    <strong class="float-left">JUMLAH EKUITAS</strong> <b class="text-left">Rp. </b><strong id="">0</strong> 
                  </td>
                  
                  <td class="text-right" width="15%">  
                  </td>
                  
                  <td class="text-right" width="15%">  
                  </td>
                  
                  <td class="text-right">
                    <strong class="float-left">Total Kredit</strong> <b class="text-left">Rp. </b><strong id="total_credit">0</strong>
                  </td>
              </tr>
              <tr>
                  <td class="text-right" width="3%">  
                  </td>
                  <td class="text-right" width="15%">
                    <strong class="float-left">JUMLAH TOTAL AKTIVA LANCAR</strong> <b class="text-left">Rp. </b><strong id="">0</strong>
                  </td>
                  
                  <td class="text-right" width="15%">
                  </td>
                  
                  <td class="text-right" width="15%">  
                  </td>
                  
                  <td class="text-right" width="15%">  
                  </td>
                  
                  <td class="text-right" width="15%">  
                  </td>
                  
                  <td class="text-right">
                    <strong class="float-left">Total Sisa</strong> <b class="text-left">Rp. </b><strong id="total">0</strong>
                  </td>
              </tr>
            </tfoot>
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
<script type="module" src="<?php echo $url->assets ?>pages/accounting/balance_sheet/MainBalanceSheet.js"></script>
<script>
  $('#balance_sheet').daterangepicker({
    "singleDatePicker": true,
    "autoApply": true,
    "locale": {
        "format": "MMMM YYYY",
        "firstDay": 1
    },
  })
</script>