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
          <li class="breadcrumb-item active"><?php echo lang('invoice') ?></li>
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
            <h3 class="card-title"><?=lang('item_info_edit')?></h3>
          </div>
          <!-- /.card-header -->
            <?php echo form_open_multipart('items/edit?id='.$item->id, [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
                <div class="card-body">
                    <div class="row">

                        <div class="col category">
                            <div class="form-group">
                                <label><?=lang('category_item')?></label>
                                <select class="form-control select2" disabled style="width: 100%;" name="category" id="category"value="<?=set_value('category')?>" required>
                                    <option value="ACC" <?=(explode('-',$item->category)[0] == 'ACC')?'selected':''?> data-id="ACC" <?=set_select('category', 'ACC' )?>>ACCESSORIES</option>
                                    <option value="ATO" <?=(explode('-',$item->category)[0] == 'ATO')?'selected':''?> data-id="ATOMIZER" <?=set_select('category', 'ATOMIZER' )?>>ATOMIZER</option>
                                    <option value="BAT" <?=(explode('-',$item->category)[0] == 'BAT')?'selected':''?> data-id="BATTERY" <?=set_select('category', 'BATTERY' )?>>BATTERY</option>
                                    <option value="CAT & COIL" <?=(explode('-',$item->category)[0] == 'CAT')?'selected':''?> data-id="CATTRIDGE & COIL"<?=set_select('category', 'CATTRIDGE & COIL' )?>>CATTRIDGE & COIL</option>
                                    <option value="COT" <?=(explode('-',$item->category)[0] == 'COT')?'selected':''?> data-id="COTTON" <?=set_select('category', 'COTTON' )?>>COTTON</option>
                                    <option value="DEV" <?=(explode('-',$item->category)[0] == 'DEV')?'selected':''?> data-id="DEVICE" <?=set_select('category', 'DEVICE' )?>>DEVICE</option>
                                    <option value="LIQ" <?=(explode('-',$item->category)[0] == 'LIQ')?'selected':''?> data-id="LIQUID" <?=set_select('category', 'LIQUID' )?>>LIQUID</option>
                                    <option value="POD" <?=(explode('-',$item->category)[0] == 'POD')?'selected':''?> data-id="PODS" <?=set_select('category', 'PODS' )?>>PODS</option>
                                    <option value="WIR" <?=(explode('-',$item->category)[0] == 'WIR')?'selected':''?> data-id="WIRE" <?=set_select('category', 'WIRE' )?>>WIRE</option>
                                </select>
                            </div>
                        </div>
                        <?php $tmp = explode('-',$item->item_code);?>
                        <!-- condition if item is liquid -->
                        <?php if (count($tmp)>=3):?>                        
                        <div class="col-sm-3 subcategory">
                            <div class="form-group">
                                <label>Sub Category</label>
                                <select class="form-control select2" style="width: 100%;" disabled name="subcategory" id="subcategory" required>
                                <option value="LIQUID FREEBASE CREAMY" data-id="FC" <?=($tmp[2]=="FC")?'selected':''?> >LIQUID FREEBASE CREAMY</option>
                                <option value="LIQUID FREEBASE FRUITY" data-id="FF" <?=($tmp[2]=="FF")?'selected':''?> >LIQUID FREEBASE FRUITY</option>
                                <option value="LIQUID SALT CREAMY" data-id="SC" <?=($tmp[2]=="SC")?'selected':''?> >LIQUID SALT CREAMY</option>
                                <option value="LIQUID SALT FRUITY" data-id="SF" <?=($tmp[2]=="SF")?'selected':''?> >LIQUID SALT FRUITY</option>
                                <option value="LIQUID PODS CREAMY" data-id="PC" <?=($tmp[2]=="PC")?'selected':''?> >LIQUID PODS CREAMY</option>
                                <option value="LIQUID PODS FRUITY" data-id="PF" <?=($tmp[2]=="PF")?'selected':''?> >LIQUID PODS FRUITY</option>
                                </select>
                            </div>
                            </div>

                            <div class="col-sm-3 subcategory">
                            <!-- text input -->
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>MG <small>(Nikotin)</small></label>
                                            <input type="number" class="form-control" name="mg" id="mg" value="<?=$item->mg?>" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>ML <small>(Milligram)</small></label>
                                            <input type="number" class="form-control" name="ml" id="ml" value="<?=$item->ml?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 subcategory">
                            <!-- text input -->
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label>VG</label>
                                        <input type="number" class="form-control" name="vg" id="vg" value="<?=$item->vg?>" required>
                                    </div>
                                </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                        <label>PG</label>
                                        <input type="number" class="form-control" name="pg" id="pg" value="<?=$item->pg?>" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-3 subcategory">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Flavour <small>(Rasa)</small></label>
                                <input type="text" class="form-control" name="flavour" id="flavour" value="<?=$item->flavour?>" required>
                            </div>
                        </div>
                        <div class="col-sm-3 subcategory">
                            <!-- text input -->
                        <div class="form-group">
                                <label>Customs <small>(Bea cukai)</small></label>
                                <input type="text" class="form-control" name="customs" id="customs" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy" data-mask value="<?=$item->customs?>">
                            </div>
                            </div>
                        <div class="col-sm-3 subcategory">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Brand 1</label>
                                <input type="text" class="form-control" name="brand" id="brand" value="<?=$item->brand?>" required>
                            </div>
                        </div>
                        <div class="col-sm-3 subcategory">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Brand 2</label>
                                <input type="text" class="form-control" name="brands" id="brands" value="<?=$item->brands?>">
                            </div>
                        </div>
                        <!-- condition if item is liquid -->
                        <?php endif;?>

                        <div class="col-sm-3">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('item_code')?></label>
                                <input type="text" class="form-control" name="item_code" id="item_code" value="<?=$item->item_code?>" required readonly required readonly>
                            </div>
                        </div>

                        <div class="col-sm-3">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('item_quantity')?></label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control" name="quantity" id="quantity" value="<?=$item->quantity?>" required>
                                  <div class="input-group-append">
                                    <select class="input-group-text" name="unit" id="unit" required>
                                      <option value="PCS"<?=($item->unit == 'PCS')?' selected':''?>>PCS</option>
                                      <option value="PAC"<?=($item->unit == 'PAC')?' selected':''?>>PAC</option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('item_name')?></label>
                                <input type="text" class="form-control" name="item_name" id="item_name" value="<?=$item->item_name?>" required>
                            </div>
                        </div>
                        
                        <div class="col">
                          <div class="row">

                            <div class="col-sm-6">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('item_capital_price')?></label>
                                    <input type="text" class="form-control" name="capital_price" id="capital_price" value="<?=$item->capital_price?>" required>
                                    <?=form_error('capital_price', '<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('item_selling_price')?></label>
                                    <input type="text" class="form-control" name="selling_price" id="selling_price" value="<?=$item->selling_price?>" required>
                                    <?=form_error('selling_price', '<small class="text-danger">','</small>')?>
                                </div>
                            </div>

                          </div>
                        </div>
                        <div class="col-12">
                        <!-- text input -->
                            <div class="form-group">
                                <label><?=lang('note')?></label>
                                <textarea type="text" class="form-control" name="note" id="note"><?=$item->note?></textarea>
                                <?=form_error('note', '<small class="text-danger">','</small>')?>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"<?=($item->is_active)?' checked':''?>>
                                    <label class="custom-control-label" for="is_active"><?=lang('status_active')?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary float-right"><?=lang('save')?></button>
                        <button type="cancel" class="btn btn-default mr-2"><?=lang('cancel')?></button>
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