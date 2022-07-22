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
          <li class="breadcrumb-item active"><?php echo lang('customer') ?></li>
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
            <h3 class="card-title"><?=lang('customer_info_add')?></h3>
          </div>
          <!-- /.card-header -->
            <?php echo form_open_multipart('master_information/customer/add', [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
                <div class="card-body">

                    <div class="row">

                        <div class="col-sm-2">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('customer_code')?></label>
                                <input type="text" class="form-control form-control-sm" name="customer_code" id="customer_code" readonly value="<?=$customer_code?>">
                            </div>
                        </div>

                        <div class="col-sm-2">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('store_name')?></label>
                                <input type="text" class="form-control form-control-sm" name="store_name" id="store_name" value="<?=set_value('store_name')?>" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="row">
                            <div class="col-sm-6">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('customer_owner')?></label>
                                    <input type="text" class="form-control form-control-sm" name="owner_name" id="customer_owner" value="<?=set_value('customer_owner')?>" required>
                                    <?=form_error('customer_owner', '<small class="text-danger">','</small>')?>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><?=lang('category_customer')?></label>
                                    <select class="form-control select2" style="width: 100%;" name="customer_type" id="category"value="<?=set_value('category')?>" required>
                                        <option value="" selected="selected"><?=lang('select_category_customer')?></option>
                                        <option value="WS"><?=lang('customer_category_ws')?></option>
                                        <option value="RESELLER"><?=lang('customer_category_reseller')?></option>
                                        <option value="AGENT"><?=lang('customer_category_agent')?></option>
                                        <option value="SPECIAL AGENT"><?=lang('customer_category_special')?></option>
                                        <option value="DISTRIBUTION"><?=lang('customer_category_distributor')?></option>
                                        <option value="OTHER"><?=lang('customer_category_other')?></option>
                                    </select>
                                </div>
                            </div>

                          </div>
                        </div>
                        <div class="col">
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
    $('.select2').select2();
</script>