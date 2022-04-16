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
                    <li class="breadcrumb-item active"><?php echo lang('supplier') ?></li>
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
                        <h3 class="card-title"><?= lang('supplier_info_edit') ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <?php echo form_open_multipart('master_information/supplier/edit?id=' . $supplier->customer_code, ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
                    <div class="card-body">

                        <div class="row">

                            <div class="col-sm-2">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= ucwords(lang('supplier_code')) ?></label>
                                    <input type="hidden" class="form-control" name="id" id="id" readonly value="<?= $supplier->id ?>">
                                    <input type="text" class="form-control form-control-sm" name="customer_code" id="customer_code" readonly value="<?= $supplier->customer_code ?>">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('store_name') ?></label>
                                    <input type="text" class="form-control form-control-sm" name="store_name" id="store_name" value="<?= $supplier->store_name ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label><?= lang('supplier_owner') ?></label>
                                            <input type="text" class="form-control form-control-sm" name="owner_name" id="supplier_owner" value="<?= $supplier->owner_name ?>" required>
                                            <?= form_error('supplier_owner', '<small class="text-danger">', '</small>') ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= lang('category_supplier') ?></label>
                                            <select class="form-control select2" style="width: 100%;" name="supplier_type" id="category" value="<?= set_value('category') ?>" required>
                                                <option value="WS" <?= ($supplier->supplier_type == 'WS') ? ' selected' : '' ?>><?= lang('customer_category_ws') ?></option>
                                                <option value="DISTRIBUTION" <?= ($supplier->supplier_type == 'DISTRIBUTION') ? ' selected' : '' ?>><?= lang('customer_category_distributor') ?></option>
                                                <option value="OTHER" <?= ($supplier->supplier_type == 'OTHER') ? ' selected' : '' ?>><?= lang('customer_category_other') ?></option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('note') ?></label>
                                    <textarea type="text" class="form-control form-control-sm" name="note" id="note"><?= $supplier->note ?></textarea>
                                    <?= form_error('note', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" <?= ($supplier->is_active) ? ' checked' : '' ?>>
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
                            <button type="button" class="btn btn-default mr-2"><?= lang('cancel') ?></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- /.card-body -->
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= lang('address_list') ?></h3>

                        <div class="card-tools pull-right">
                            <a href="<?= url('master_information/address/add?id=' . $supplier->id) ?>" type="button" class="btn btn-block btn-default"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th><?= lang('village') ?></th>
                                    <th><?= lang('sub_district') ?></th>
                                    <th><?= lang('city') ?></th>
                                    <th><?= lang('province') ?></th>
                                    <th><?= lang('zip') ?></th>
                                    <th><?= lang('contact_phone') ?></th>
                                    <th><?= lang('contact_mail') ?></th>
                                    <th><?= lang('created_at') ?></th>
                                    <th><?= lang('updated_at') ?></th>
                                    <th><?= lang('is_open') ?></th>
                                    <th><?= lang('option') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($address as $key => $value) : ?>
                                    <tr>
                                        <td><?= $value->village ?></td>
                                        <td><?= $value->sub_district ?></td>
                                        <td><?= $value->city ?></td>
                                        <td><?= $value->province ?></td>
                                        <td><?= $value->zip ?></td>
                                        <td><?= $value->contact_phone ?></td>
                                        <td><?= $value->contact_mail ?></td>
                                        <td><?= date(setting('datetime_format'), strtotime($value->created_at)) ?></td>
                                        <td><?= date(setting('datetime_format'), strtotime($value->updated_at)) ?></td>
                                        <td<?= ($value->is_active) ? ' class="text-primary"' : ' class="text-danger"' ?>><?= ($value->is_active) ? 'Open' : 'Closed' ?></td>
                                            <td>
                                                <a href="<?= url('master_information/address/edit?id=') . $value->id ?>" class="btn btn-sm btn-block btn-default"><i class="fa fa-tw fa-edit"></i></a>
                                            </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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