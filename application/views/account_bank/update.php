<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
          <li class="breadcrumb-item active"><?php echo lang('pages_account_bank') ?></li>
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
            <h3 class="card-title"><?=lang('info_add')?></h3>
          </div>
          <!-- /.card-header -->
            <?php echo form_open_multipart("master_information/account_bank/update?id=".get('id'), [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('bank_name')?></label>
                                <input type="text" class="form-control" name="name" id="name" value="<?=$bank->name?>" required>
                                <?=form_error('name', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('no_account')?></label>
                                <input type="text" class="form-control" name="no_account" id="no_account" value="<?=$bank->no_account?>" required>
                                <?=form_error('no_account', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('own_by')?></label>
                                <input type="text" class="form-control" name="own_by" id="own_by" value="<?=$bank->own_by?>" required>
                                <?=form_error('own_by', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('balance_total')?></label>
                                <input type="text" class="form-control currency" name="balance" id="balance" value="<?=$bank->balance?>" readonly>
                                <?=form_error('balance', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('note')?></label>
                                <textarea type="text" class="form-control" name="note" id="note"><?=$bank->description?></textarea>
                                <?=form_error('note', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary float-right"><?=lang('save')?></button>
                        <button type="button" class="btn btn-default mr-2"><?=lang('back')?></button>
                    </div>
                    <div class="float-left">
                        <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#modal-delete"><i class="fa fa-trash">&nbsp;&nbsp;</i><?=lang('cancel')?></button>
                    </div>
                </div>
            <?php echo form_close(); ?>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
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
  $(document).ready(function () {
    $('#modal-delete').on('shown.bs.modal', function(){
        $('.modal input#id').val('<?=get('id')?>');
    })
    $('.currency').each(function (index, field) {
        $(field).val(currency(currencyToNum($(field).val())));
    });

    $(document).on('keyup', '.currency', function () {
        $(this).val(currency(currencyToNum($(this).val())));
    })
  })
</script>