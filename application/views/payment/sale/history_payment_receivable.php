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
        <!-- payment -->
        <div class="card" id="to_pay" style="display: none;">

        <?php echo form_open_multipart('invoice/sales/payment/receivable_from', ['class' => 'form-validate', 'id' => 'to_pay', 'autocomplete' => 'off']); ?>
          
          <div class="card-header">
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
                <input type="hidden" id="to_pay" name="current_to_pay" value="0" required>
                <input type="text" class="form-control currency" id="to_pay" name="to_pay" value="0" required>
              </div>
              
              <div class="col-lg-2 col-sm-12">
                <label for="beneficiary_name"><?=lang('beneficiary_name')?></label>
                <input type="hidden" class="form-control bank_name" id="id" name="bank_id" value="" required>
                <input type="text" class="form-control bank_name" id="beneficiary_name" name="beneficiary_name" value="" required>
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
          </div>
        <?php echo form_close(); ?>

        </div>
        <!-- ./payment -->
        <!-- /.card -->
        <div class="card">
            
          <div class="card-header">
            <h3 class="card-title">List History</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-2 col sm-12"><b><?=lang('created_at')?></b></div>
              <div class="col-md-2 col sm-12"><b><?=lang('date_due')?></b></div>
              <div class="col-md-1 col sm-22"><span class="float-right"><b><?=lang('payup')?></b></span></div>
              <div class="col-md-2 col sm-12"><span class="float-right"><b><?=lang('leftovers')?></b></span></div>
              <div class="col-md-2 col sm-12"><b><?=lang('created_by')?></b></div>
              <div class="col-md-2 col sm-12"><b><?=lang('updated_by')?></b></div>
              <div class="col-md-1 col sm-12 text-center"><b><?=lang('option')?></b></div>
            </div>
            <?php $grandtotal = 0;$payup = 0;$leftovers = 0;?>
            <table class="table table-sm table-hover table-border">
            <?php foreach ($response_data as $key => $list):?>
            <tr class="<?php echo(getCurrentcy($list->leftovers) <= 0)?'bg-success':''?>">
              <td>
                <div class="row">
                  <div class="col-md-2 col sm-12"><?=date("d-m-Y",strtotime($list->created_at))?></div>
                  <div class="col-md-2 col sm-12"><?=date("d-m-Y",strtotime($list->date_start)).' ~ '.date("d-m-Y",strtotime($list->date_due))?></div>
                  <div class="col-md-1 col sm-12"><span class="float-right"><?=getCurrentcy($list->payup)?></span></div>
                  <div class="col-md-2 col sm-12"><span class="float-right"><?=getCurrentcy($list->leftovers)?></span></div>
                  <div class="col-md-2 col sm-12"><?=$list->user_created?></div>
                  <div class="col-md-2 col sm-12"><?=$list->user_updated?></div>
                  <div class="col-md-1 col sm-12">
                    <div class="btn-group btn-block" id="edit_pay" data-id="<?=$list->id?>" data-code_invoice="<?=$list->invoice_code?>">
                      <button class="btn btn-sm btn-default" id="edit_pay"><i class="fa fa-fw fa-edit text-primary"></i></button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php $grandtotal += $list->grand_total;$payup += $list->payup;$leftovers += $list->leftovers;?>
            <?php endforeach; ?>
            </table>  
          </div>          
        </div>

        <!-- Information Purchase -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?=lang('purchase_info')?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col-lg-4 col-sm-12">
                        <h6><?=lang('invoice_code')?>:</h6>
                        <b><?=get('invoice_code')?></b>
                    </div>
                    <div class="col-md-2 col-lg-1 offset-lg-6 col-sm-12">
                        <span class="float-right">
                            <h6><?=lang('grandtotal')?>:</h6>
                            <b><?=getCurrentcy($response_data[0]->grand_total)?></b>
                        </span>
                    </div>
                    <div class="col-md-2 col-lg-1 col-sm-12">
                        <span class="float-right">
                            <h6><?=lang('payup')?>:</h6>
                            <b><?=getCurrentcy($payup)?></b>
                        </span>
                    </div>
                    
                    <div class="col-md-2 col-lg-1 col-sm-12 mt-2">
                        <button class="btn btn-primary btn-block" id="to_pay" data-id="<?=$data_invoice->id?>" data-code_invoice="<?=$data_invoice->invoice_code?>">
                            <i class="fa fa-fw fa-dollar-sign"></i>&nbsp;&nbsp;
                            <span><?=lang('payup')?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Information Purchase -->


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

<script type="module" src="<?php echo $url->assets ?>pages/payment/debt/mainDebtHistory.js"></script>