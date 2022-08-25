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
            <h3 class="card-title">Search and Find our debt..</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <?php echo form_open('invoice/sales/payment/receivable', ['method' => 'POST', 'autocomplete' => 'off']); ?>
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
                    <input class="form-control" type="hidden" id="customer_code" name="customer_code" value="<?=$data_post['customer_code']?>" required>
                    <div class="form-group">
                      <input class="form-control" type="text" id="customer_name" name="customer_name" value="<?=$data_post['customer_name']?>" required>
                      <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
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
        <!-- payment -->
        <div class="card" id="to_pay" style="display: none;">
        <?php //echo form_open_multipart('invoice/sales/payment/receivable_from', ['class' => 'form-validate', 'id' => 'to_pay', 'autocomplete' => 'off']); ?>
          <!-- <div class="card-header">
            <h3 class="card-title">Wont to pay ?</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-sm bg-default remove" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-2 col-sm-12">
                <div class="form-group">
                  <label for="invoice_code"><?=lang('invoice_code')?></label>
                  <input type="hidden" id="id_payment" name="id_payment" value="" required>
                  <input type="text" class="form-control" id="invoice_code" name="invoice_code" value="" required>
                </div>
              </div>
              <div class="col-lg-3 col-sm-12">
                <label for="to_pay"><?=lang('to_payup')?></label>
                <input type="text" class="form-control currency" id="to_pay" name="to_pay" required>
              </div>
              
              <div class="col-lg-2 col-sm-12">
                <label for="beneficiary_name"><?=lang('beneficiary_name')?></label>
                <input type="hidden" class="form-control bank_name" id="id" name="bank_id" required>
                <input type="text" class="form-control bank_name" id="beneficiary_name" name="beneficiary_name" required>
              </div>
              <div class="col-12 mb-2">
                <label for="note"><?=lang('note')?></label>
                <textarea class="form-control" name="note" id="note"></textarea>
              </div>

            </div>
            <div class="row">
              <div class="col-lg-1 col-sm-12">
                <button type="submit" class="btn btn-info btn-block start">
                  <i class="fa fa-fw fa-coins"></i>&nbsp;&nbsp;
                  <span><?=lang('payup')?></span>
                </button>
              </div>
            </div>
          </div> -->
        <?php //echo form_close(); ?>

        </div>
        <!-- ./payment -->
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
            <?php foreach ($data_list_receivables as $key => $list):?>
            <tr id="row-<?=$key;?>" class="<?php echo(getCurrentcy($list->leftovers) <= 0)?'bg-success':''?>" <?php echo(!hasPermissions('example') && (getCurrentcy($list->leftovers) <= 0))?'style="display:none;"':''?>>
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
                    <div class="btn-group btn-block" id="to_pay" data-id="<?=$list->id?>" data-code_invoice="<?=$list->invoice_code?>">
                      <?php if(getCurrentcy($list->leftovers) > 0):?>
                      <button class="btn btn-sm btn-default" id="to_pay"><i class="fa fa-fw fa-dollar-sign text-primary"></i></button>
                      <?php endif; ?>
                      <a href="<?= url('invoice/sales/payment/history') ?>?invoice_code=<?=$list->invoice_code?>" class="btn btn-sm btn-default"><i class="fa fa-fw fa-history text-primary"></i></a>
                    </div>
                  </div>
                </div>
              </td>
            <?php $grandtotal += $list->grand_total;$payup += $list->payup;$leftovers += $list->leftovers;?>
            </tr>
            <tr class="child-row-<?=$key;?>" style="display: none;"></tr>
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

<script type="module" src="<?php echo $url->assets ?>pages/payment/receivable/mainReceivableSearch.js"></script>