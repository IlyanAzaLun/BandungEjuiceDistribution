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

                            <div class="col-sm-12 col-lg-1">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('customer_code') ?></label>
                                    <input type="hidden" class="form-control form-control-sm" name="id" id="id" readonly value="<?= $information->id ?>">
                                    <input type="text" class="form-control form-control-sm" name="customer_code" id="customer_code" readonly value="<?= $information->customer_code ?>">
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('store_name') ?></label>
                                    <textarea type="text" class="form-control form-control-sm" name="store_name" id="store_name" value="<?= $information->store_name ?>"><?= $information->store_name ?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label><?= lang('customer_owner') ?></label>
                                            <input type="text" class="form-control form-control-sm" name="owner_name" id="customer_owner" value="<?= $information->owner_name ?>">
                                            <?= form_error('customer_owner', '<small class="text-danger">', '</small>') ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-lg-6">
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

                            <div class="col-lg-4 col-sm-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('contact_us') ?></label>
                                    <input type="text" class="form-control form-control-sm" name="contact_us" id="contact_us" value="<?= $information->contact_us ?>" required>
                                </div>
                            </div>

                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <h6><?= lang('expedition_name') ?></h6>
                                    <select class="form-control form-control-sm" name="expedition_name" id="expedition_name" required>
                                        <option value="" selected disabled><?=lang('option')?></option>
                                        <?php foreach ($expedition as $key => $value):?>
                                            <option value="<?= $value->expedition_name ?>"<?=($value->expedition_name == $invoice->expedition)?' selected':''?> data-services="<?= $value->services_expedition ?>"><?= $value->expedition_name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <h6><?= lang('expedition_services') ?></h6>
                                    <select class="form-control form-control-sm" name="services_expedition" id="services_expedition">
                                        <option value=""><?=lang('option')?></option>
                                        <option value="<?=$invoice->services_expedition?>" selected><?=$invoice->services_expedition?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-sm-12">
                                <div class="form-group">
                                    <h6><?= lang('pack') ?></h6>
                                    <input type="number" class="form-control form-control-sm" name="pack" required>
                                </div>
                            </div>
                                
                            <div class="col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <h6><?= lang('select_payment_shipping') ?></h6>
                                    <select class="form-control form-control-sm" name="type_payment_shipping" id="type_payment_shipping">
                                        <option value="" <?=$invoice->type_payment_shipping==""?'selected':''?>><?=lang('option')?></option>
                                        <option value="TAGIH TUJUAN" <?=$invoice->type_payment_shipping=="TAGIH TUJUAN"?'selected':''?>>TAGIH TUJUAN</option>
                                        <option value="KREDIT" <?=$invoice->type_payment_shipping=="KREDIT"?'selected':''?>>KREDIT</option>
                                        <option value="TUNAI" <?=$invoice->type_payment_shipping=="TUNAI"?'selected':''?>>TUNAI</option>
                                        <option value="DFOD" <?=$invoice->type_payment_shipping=="DFOD"?'selected':''?>>DFOD</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <h6><?= lang('temporary_number') ?></h6>
                                    <input type="number" class="form-control form-control-sm" name="temporary_number">
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?= lang('temporary_address') ?></label>
                                    <textarea type="text" class="form-control form-control-sm" name="temporary_address" id="temporary_address"></textarea>
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
                                        <th><?= lang('note') ?></th>
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
                                        <td><?= $information->customer_note ?></td>
                                        <td><?= date(setting('datetime_format'), strtotime($information->created_at)) ?></td>
                                        <td><?= $information->updated_at?date(setting('datetime_format'), strtotime($information->updated_at)):''; ?></td>
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
                        <div class="float-left">
                            <a href="<?=url('master_information/address/edit?id='.get('id'))?>" class="btn btn-sm btn-warning float-right"><i class="fa fa-fw fa-edit"></i>&nbsp;&nbsp;<?=lang('edit')?></a>
                        </div>
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
    $(document).ready(function(){
        
        $('#expedition_name').on('change', function(){
            const expedition = $(this).find(":selected");
            const services = expedition.data('services')
            const service = services.split(',');
            const data_services = expedition.data('service_selected');
            $('#services_expedition').empty();
            $('#services_expedition').append(`<option value="">Opsi</option>`)
            service.forEach(element => {
                $('#services_expedition').append(`<option value="${element}">${element}</option>`)
            });
        });

    })
</script>