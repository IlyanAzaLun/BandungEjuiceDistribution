<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

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
                        <h3 class="card-title"><?= lang('item_info_edit') ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <?php echo form_open_multipart('items/edit?id=' . $item->id, ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
                    <div class="card-body">
                        <div class="row">

                            <div class="col col-sm-12 category">
                                <div class="form-group">
                                    <label><?= lang('category_item') ?></label>
                                    <select class="form-control select2" style="width: 100%;" name="category" id="category" value="<?= set_value('category') ?>" required>
                                        <option value="ACS" <?= (explode('-', $item->category)[0] == 'ACS') ? 'selected' : '' ?> data-id="ACS" <?= set_select('category', 'ACCESSORIES') ?>>ACCESSORIES</option>
                                        <option value="ATO" <?= (explode('-', $item->category)[0] == 'ATO') ? 'selected' : '' ?> data-id="ATOMIZER" <?= set_select('category', 'ATOMIZER') ?>>ATOMIZER</option>
                                        <option value="BAT" <?= (explode('-', $item->category)[0] == 'BAT') ? 'selected' : '' ?> data-id="BATTERY" <?= set_select('category', 'BATTERY') ?>>BATTERY</option>
                                        <option value="CAT" <?= (explode('-', $item->category)[0] == 'CAT') ? 'selected' : '' ?> data-id="CATTRIDGE & COIL" <?= set_select('category', 'CATTRIDGE & COIL') ?>>CATTRIDGE & COIL</option>
                                        <option value="COT" <?= (explode('-', $item->category)[0] == 'COT') ? 'selected' : '' ?> data-id="COTTON" <?= set_select('category', 'COTTON') ?>>COTTON</option>
                                        <option value="LIQ" <?= (explode('-', $item->category)[0] == 'LIQ') ? 'selected' : '' ?> data-id="LIQUID" <?= set_select('category', 'LIQUID') ?>>LIQUID</option>
                                        <option value="DEVS" <?= (explode('-', $item->category)[0] == 'DEVS') ? 'selected' : '' ?> data-id="DEVICES" <?= set_select('category', 'DEVICES') ?>>DEVICES</option>
                                        <option value="WIR" <?= (explode('-', $item->category)[0] == 'WIR') ? 'selected' : '' ?> data-id="WIRE" <?= set_select('category', 'WIRE') ?>>WIRE</option>
                                    </select>
                                </div>
                            </div>
                            <?php $tmp = explode('-', $item->category); ?>
                            <!-- condition if item is liquid -->
                            <?php if (count($tmp) >= 2) : ?>
                                <div class="col-lg-3 col-sm-12 subcategory">
                                    <div class="form-group">
                                        <label>Sub Category</label>
                                        <select class="form-control form-control-sm select2" style="width: 100%;" name="subcategory" id="subcategory" required>
                                            <option value="LIQ-FC" data-id="FREEBASE-CREAMY" <?= ($tmp[1] == "FC") ? 'selected' : '' ?>>LIQUID FREEBASE CREAMY</option>
                                            <option value="LIQ-FF" data-id="FREEBASE-FRUITY" <?= ($tmp[1] == "FF") ? 'selected' : '' ?>>LIQUID FREEBASE FRUITY</option>
                                            <option value="LIQ-SC" data-id="SALT-CREAMY" <?= ($tmp[1] == "SC") ? 'selected' : '' ?>>LIQUID SALT CREAMY</option>
                                            <option value="LIQ-SF" data-id="SALT-FRUITY" <?= ($tmp[1] == "SF") ? 'selected' : '' ?>>LIQUID SALT FRUITY</option>
                                            <option value="LIQ-PC" data-id="PODS-CREAMY" <?= ($tmp[1] == "PC") ? 'selected' : '' ?>>LIQUID PODS CREAMY</option>
                                            <option value="LIQ-PF" data-id="PODS-FRUITY" <?= ($tmp[1] == "PF") ? 'selected' : '' ?>>LIQUID PODS FRUITY</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-12 subcategory">
                                    <!-- text input -->
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label>MG <small>(Nikotin)</small></label>
                                                <input type="number" class="form-control form-control-sm" name="mg" id="mg" value="<?= $item->mg ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label>ML <small>(Milligram)</small></label>
                                                <input type="number" class="form-control form-control-sm" name="ml" id="ml" value="<?= $item->ml ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 subcategory">
                                    <!-- text input -->
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label>VG</label>
                                                <input type="number" class="form-control form-control-sm" name="vg" id="vg" value="<?= $item->vg ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group">
                                                <label>PG</label>
                                                <input type="number" class="form-control form-control-sm" name="pg" id="pg" value="<?= $item->pg ?>" required>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 subcategory">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Flavour <small>(Rasa)</small></label>
                                        <input type="text" class="form-control form-control-sm" name="flavour" id="flavour" value="<?= $item->flavour ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 subcategory">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Customs <small>(Bea cukai)</small></label>
                                        <input type="text" class="form-control form-control-sm" name="customs" id="customs" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy" data-mask value="<?= $item->customs ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 subcategory">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Brand 1</label>
                                        <input type="text" class="form-control form-control-sm" name="brand" id="brand" value="<?= $item->brand ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 subcategory">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Brand 2</label>
                                        <input type="text" class="form-control form-control-sm" name="brands" id="brands" value="<?= $item->brands ?>">
                                    </div>
                                </div>
                                <!-- condition if item is liquid -->
                            <?php endif; ?>

                            <div class="col-lg-3 col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('item_code') ?></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-control-sm" name="item_code" id="item_code" value="<?= $item->item_code ?>" required>
                                        <div class="input-group-append">
                                            <select class="input-group-text custom-select custom-select-sm" name="unit" id="unit" required>
                                                <option value="PCS" <?= ($item->unit == 'PCS') ? ' selected' : '' ?>>PCS</option>
                                                <option value="PAX" <?= ($item->unit == 'PAX') ? ' selected' : '' ?>>PAX</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-sm-6">
                            <!-- text input -->
                                <div class="form-group">
                                    <label><?=lang('weight')?></label>
                                    <div class="input-group input-group-sm mb-3">
                                    <input type="text" class="form-control form-control-sm" name="weight" id="weight" value="<?=$item->weight?>" required>
                                    <div class="input-group-append">
                                        <p class="input-group-text">Gram</p>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg col-sm-12" style="display:<?php echo !hasPermissions('backup_db')?'none':''?>">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('item_quantity') ?></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-control-sm" name="quantity" id="quantity" value="<?= $item->quantity ?>" required>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('item_name') ?></label>
                                    <input type="text" class="form-control form-control-sm" name="item_name" id="item_name" value="<?= $item->item_name ?>" required>
                                </div>
                            </div>

                            <div class="col-lg-5 col-sm-12">
                                <div class="row">

                                    <div class="col-lg-6 col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label><?= lang('item_capital_price') ?></label>
                                            <input type="text" class="form-control form-control-sm" name="capital_price" id="capital_price" value="<?= $item->capital_price ?>" required>
                                            <?= form_error('capital_price', '<small class="text-danger">', '</small>') ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label><?= lang('item_selling_price') ?></label>
                                            <input type="text" class="form-control form-control-sm" name="selling_price" id="selling_price" value="<?= $item->selling_price ?>" required>
                                            <?= form_error('selling_price', '<small class="text-danger">', '</small>') ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('note') ?></label>
                                    <textarea type="text" class="form-control form-control-sm" name="note" id="note"><?= $item->note ?></textarea>
                                    <?= form_error('note', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" <?= ($item->is_active) ? ' checked' : '' ?>>
                                        <label class="custom-control-label" for="is_active"><?= lang('status_active') ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-right">
                            <button type="submit" class="btn btn-primary float-right"><?= lang('save') ?></button>
                            <button type="button" class="btn btn-default mr-2" onclick="history.back();"><?= lang('back') ?></button>
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
<script type="module" src="<?php echo $url->assets ?>pages/items/MainItem_edit.js"></script>