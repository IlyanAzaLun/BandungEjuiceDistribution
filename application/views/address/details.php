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
                        <h3 class="card-title"><?= lang('customer_info_edit') ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <?php echo form_open_multipart('master_information/address/detail?id=' . $information->id, ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
                    <div class="card-body">

                        <div class="row">

                            <div class="col-sm-2">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('customer_code') ?></label>
                                    <input type="hidden" class="form-control form-control-sm" name="id" id="id" readonly value="<?= $information->id ?>">
                                    <input type="text" class="form-control form-control-sm" name="customer_code" id="customer_code" value="<?= $information->customer_code ?>">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('store_name') ?></label>
                                    <input type="text" class="form-control form-control-sm" name="store_name" id="store_name" value="<?= $information->store_name ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label><?= lang('customer_owner') ?></label>
                                            <input type="text" class="form-control form-control-sm" name="owner_name" id="customer_owner" value="<?= $information->owner_name ?>">
                                            <?= form_error('customer_owner', '<small class="text-danger">', '</small>') ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?= lang('category_customer') ?></label>
                                            <select class="form-control select2" style="width: 100%;" name="customer_type" id="category" value="<?= set_value('category') ?>">
                                                <option value="" selected="selected"><?= lang('select_category_customer') ?></option>
                                                <option value="WS" <?= ($information->customer_type == 'WS') ? ' selected' : '' ?>><?=lang('customer_category_ws')?></option>
                                                <option value="RESELLER" <?= ($information->customer_type == 'RESELLER') ? ' selected' : '' ?>><?= lang('customer_category_reseller') ?></option>
                                                <option value="AGENT" <?= ($information->customer_type == 'AGENT') ? ' selected' : '' ?>><?= lang('customer_category_agent') ?></option>
                                                <option value="SPECIAL AGENT" <?= ($information->customer_type == 'SPECIAL AGENT') ? ' selected' : '' ?>><?= lang('customer_category_special') ?></option>
                                                <option value="DISTRIBUTION" <?= ($information->customer_type == 'DISTRIBUTION') ? ' selected' : '' ?>><?= lang('customer_category_distributor') ?></option>
                                                <option value="OTHER" <?= ($information->customer_type == 'OTHER') ? ' selected' : '' ?>><?= lang('customer_category_other') ?></option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('note') ?></label>
                                    <textarea type="text" class="form-control form-control-sm" name="note" id="note"><?= $information->note ?></textarea>
                                    <?= form_error('note', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th><?= lang('address') ?></th>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $information->address ?></td>
                                        <td><?= $information->village ?></td>
                                        <td><?= $information->sub_district ?></td>
                                        <td><?= $information->city ?></td>
                                        <td><?= $information->province ?></td>
                                        <td><?= $information->zip ?></td>
                                        <td><?= $information->contact_phone ?></td>
                                        <td><?= $information->contact_mail ?></td>
                                        <td><?= date(setting('datetime_format'), strtotime($information->created_at)) ?></td>
                                        <td><?= date(setting('datetime_format'), strtotime($information->updated_at)) ?></td>
                                        <td <?= ($information->is_active) ? 'class="text-primary"' : 'class="text-danger"' ?>><?= ($information->is_active) ? 'Open' : 'Closed' ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" <?= ($information->is_active) ? ' checked' : '' ?>>
                                        <label class="custom-control-label" for="is_active"><?= lang('status_active') ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-right">
                            <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fa fa-fw fa-print"></i>&nbsp;&nbsp;<?=lang('print')?></button>
                            <button type="button" class="btn btn-sm btn-default mr-2" onclick="history.back()"><?=lang('back')?></button>
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