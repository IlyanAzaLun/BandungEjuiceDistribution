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
          <li class="breadcrumb-item active"><?php echo lang('expedition') ?></li>
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
            <h3 class="card-title"><?=lang('item_info_add')?></h3>
          </div>
          <!-- /.card-header -->
            <?php echo form_open_multipart('master_information/expedition/add', [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('expedition_name')?></label>
                                <input type="text" class="form-control" name="expedition_name" id="expedition_name" required>
                                <?=form_error('expedition_name', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="row">

                            <div class="col-sm-6">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('shipping_services')?></label>
                                    <select class="form-control" multiple size="2" name="service_expedition[]" id="service_expedition" required>
                                      <option value="DARAT">DARAT</option>
                                      <option value="UDARA">UDARA</option>
                                    </select>
                                    <?=form_error('service_expedition[]', '<small class="text-danger">','</small>')?>
                                </div>
                            </div>

                          </div>
                        </div>
                        <div class="col-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('note')?></label>
                                <textarea type="text" class="form-control" name="note" id="note"><?=set_value('note')?></textarea>
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