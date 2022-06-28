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
          <li class="breadcrumb-item active"><?php echo lang('item') ?></li>
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
            <?php echo form_open_multipart('items/add', [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-3 col-sm-12 category">
                            <div class="form-group">
                                <label><?=lang('category_item')?></label>
                                <select class="form-control select2" style="width: 100%;" name="category" id="category"value="<?=set_value('category')?>" required>
                                    <option value="" selected="selected"><?=lang('select_category_item')?></option>
                                    <option value="ACS" data-id="ACCESSORIES" <?=set_select('category', 'ACCESSORIES' )?>>ACCESSORIES</option>
                                    <option value="ATO" data-id="ATOMIZER" <?=set_select('category', 'ATOMIZER' )?>>ATOMIZER</option>
                                    <option value="BAT" data-id="BATTERY" <?=set_select('category', 'BATTERY' )?>>BATTERY</option>
                                    <option value="CAT" data-id="CATTRIDGE & COIL"<?=set_select('category', 'CATTRIDGE & COIL' )?>>CATTRIDGE & COIL</option>
                                    <option value="COT" data-id="COTTON" <?=set_select('category', 'COTTON' )?>>COTTON</option>
                                    <option value="LIQ" data-id="LIQUID" <?=set_select('category', 'LIQUID' )?>>LIQUID</option>
                                    <option value="DEVS" data-id="DEVICES" <?=set_select('category', 'DEVICES' )?>>DEVICES</option>
                                    <option value="WIR" data-id="WIRE" <?=set_select('category', 'WIRE' )?>>WIRE</option>
                                </select>
                            </div>
                        </div>



                        <div class="col-lg-3 col-sm-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('item_code')?></label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control form-control-sm" name="item_code" id="item_code" value="<?=set_value('item_code')?>" required>
                                  <div class="input-group-append">
                                    <select class="input-group-text custom-select custom-select-sm" name="unit" id="unit" required>
                                      <option value="PCS">PCS</option>
                                      <option value="PAX">PAX</option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('weight')?></label>
                                <div class="input-group input-group-sm mb-3">
                                  <input type="number" class="form-control form-control-sm" name="weight" id="weight" value="<?=set_value('weight')?>" required>
                                  <div class="input-group-append">
                                    <p class="input-group-text">Gram</p>
                                  </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3" style="display:none">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('item_quantity')?></label>
                                <div class="input-group mb-3">
                                  <input type="number" class="form-control form-control-sm" name="quantity" id="quantity" value="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('item_name')?></label>
                                <input type="text" class="form-control form-control-sm" name="item_name" id="item_name" value="<?=set_value('item_name')?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="row">

                            <div class="col-lg-4">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('item_capital_price')?></label>
                                    <input type="text" class="form-control form-control-sm currency" name="capital_price" id="capital_price" value="<?=set_value('capital_price')?>" required>
                                    <?=form_error('capital_price', '<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('item_selling_price')?></label>
                                    <input type="text" class="form-control form-control-sm currency" name="selling_price" id="selling_price" value="<?=set_value('selling_price')?>" required>
                                    <?=form_error('selling_price', '<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('item_shadow_selling_price')?></label>
                                    <input type="text" class="form-control form-control-sm currency" name="shadow_selling_price" id="shadow_selling_price" value="<?=set_value('shadow_selling_price')?>" required>
                                    <?=form_error('shadow_selling_price', '<small class="text-danger">','</small>')?>
                                    <small class="text-danger">*<?=lang('shadow_selling_price_desc')?></small>
                                </div>
                            </div>

                          </div>
                        </div>
                        <div class="col-lg-12">
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
                        <button type="button" class="btn btn-default mr-2" onclick="history.back()"><?= lang('back') ?></button>
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
<script src="<?php echo $url->assets ?>plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script type="module" src="<?php echo $url->assets ?>pages/items/MainItem_add.js"></script>