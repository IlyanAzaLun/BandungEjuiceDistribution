<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

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

                  <div class="col-lg-5 col-sm-12">
                    <div class="form-group input-group">
                      <input class="form-control" type="text" id="min" name="min" value="<?=$data_post['min']?>">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-7 col-sm-12"></div>
                  <div class="col-lg-3 col-sm-12">
                    <input class="form-control" type="hidden" id="supplier_code" name="supplier_code" value="<?=$data_post['supplier_code']?>" required>
                    <div class="form-group">
                      <input class="form-control" type="text" id="supplier_name" name="supplier_name" value="<?=$data_post['supplier_name']?>" required>
                      <?= form_error('supplier_code', '<small class="text-danger">', '</small>') ?>
                    </div>
                  </div>
                  <div class="col-lg-2 col-sm-12">
                    <button type="submit" class="btn btn-info btn-block start">
                      <span>Start search</span>&nbsp;&nbsp;
                      <i class="fa fa-fw fa-search"></i>
                    </button>
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
            <div class="row">
              <div class="col-md-3 col sm-12"><b><?=lang('invoice_code')?></b></div>
              <div class="col-md-1 col sm-12"><b><?=lang('created_at')?></b></div>
              <div class="col-md-2 col sm-12"><b><?=lang('date_due')?></b></div>
              <div class="col-md-1 col sm-12"><span class="float-right"><b><?=lang('grandtotal')?></b></span></div>
              <div class="col-md-1 col sm-22"><span class="float-right"><b><?=lang('payup')?></b></span></div>
              <div class="col-md-2 col sm-12"><span class="float-right"><b><?=lang('leftovers')?></b></span></div>
              <div class="col-md-1 col sm-12"><b><?=lang('created_by')?></b></div>
              <div class="col-md-1 col sm-12 text-center"><b><?=lang('option')?></b></div>
            </div>
            <?php $grandtotal = 0;$payup = 0;$leftovers = 0;?>
            <table class="table table-sm table-hover table-border">
            <?php foreach ($data_list_debts as $key => $list):?>
            <tr>
              <td>
                <div class="row">
                  <div class="col-md-3 col sm-12"><?=$list->invoice_code?></div>
                  <div class="col-md-1 col sm-12"><?=date("d-m-Y",strtotime($list->created_at))?></div>
                  <div class="col-md-2 col sm-12"><?=date("d-m-Y",strtotime($list->date_start)).' ~ '.date("d-m-Y",strtotime($list->date_due))?></div>
                  <div class="col-md-1 col sm-12"><span class="float-right"><?=getCurrentcy($list->grand_total)?></span></div>
                  <div class="col-md-1 col sm-12"><span class="float-right"><?=getCurrentcy($list->payup)?></span></div>
                  <div class="col-md-2 col sm-12"><span class="float-right"><?=getCurrentcy($list->leftovers)?></span></div>
                  <div class="col-md-1 col sm-12"><?=$list->user_created?></div>
                  <div class="col-md-1 col sm-12">
                    <div class="btn-group btn-block">
                      <button class="btn btn-sm btn-default"><i class="fa fa-fw fa-dollar-sign text-primary"></i></button>
                      <button class="btn btn-sm btn-default"><i class="fa fa-fw fa-history text-primary"></i></button>
                    </div>
                  </div>
                </div>
              </td>
            <?php $grandtotal += $list->grand_total;$payup += $list->payup;$leftovers += $list->leftovers;?>
            </tr>
            <?php endforeach; ?>
            <tr>
              <td>
                  <div class="row">
                    <div class="col-md-3 col sm-12"></div>
                    <div class="col-md-1 col sm-12"></div>
                    <div class="col-md-2 col sm-12"></div>
                    <div class="col-md-1 col sm-12"><span class="float-right"><b><?=getCurrentcy($grandtotal)?></b></span></div>
                    <div class="col-md-1 col sm-12"><span class="float-right"><b><?=getCurrentcy($payup)?></b></span></div>
                    <div class="col-md-2 col sm-12"><span class="float-right"><b><?=getCurrentcy($leftovers)?></b></span></div>
                    <div class="col-md-1 col sm-12"></div>
                    <div class="col-md-1 col sm-12"></div>
                  </div>
              </td>
            </tr>
            </table>  
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
      showDropdowns: true,
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY H:mm'
      }
    });
    $('.ui-buttonset').draggable();
  });
</script>

<script type="module" src="<?php echo $url->assets ?>pages/payment/debt/mainDebtSearch.js"></script>