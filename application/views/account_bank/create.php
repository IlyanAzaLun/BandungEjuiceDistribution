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
            <?php echo form_open_multipart('master_information/account_bank/create', [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
                <div class="card-body">

                    <div class="row">
                      <div class="col-lg-3 col-sm-12">
                      <!-- text input -->
                          <div class="form-group">
                              <label><?=lang('account')?></label>
                              <select name="parent_account" id="parent_account" class="form-control form-control-sm">
                                <?php foreach ($parent_account as $key => $value):?>
                                <option value="<?=$value->HeadCode?>"><?=$value->HeadName?></option>
                                <?php endforeach; ?>
                              </select>
                              <?=form_error('parent_account', '<small class="text-danger">','</small>')?>
                          </div>
                      </div>  
                      <div class="col-lg-3 col-sm-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('bank_name')?></label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name" value="<?=set_value('name')?>" required>
                                <?=form_error('name', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('no_account')?></label>
                                <input type="text" class="form-control form-control-sm" name="no_account" id="no_account" value="<?=set_value('no_account')?>" required>
                                <?=form_error('no_account', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('own_by')?></label>
                                <input type="text" class="form-control form-control-sm" name="own_by" id="own_by" value="<?=set_value('own_by')?>" required>
                                <?=form_error('own_by', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('note')?></label>
                                <textarea type="text" class="form-control form-control-sm" name="note" id="note"><?=set_value('note')?></textarea>
                                <?=form_error('note', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary float-right"><?=lang('save')?></button>
                        <button type="button" class="btn btn-default mr-2"><?=lang('cancel')?></button>
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

    $("select.form-control.form-control-sm:not(.dont-select-me)").select2({
        placeholder: "Select option",
        allowClear: true
    });
  })
</script>