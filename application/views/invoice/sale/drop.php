<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.structure.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.theme.min.css">

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
                    <li class="breadcrumb-item active"><?php echo lang('page_purchase') ?></li>
                    <li class="breadcrumb-item active"><?php echo lang('purchase') ?></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default card -->
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Note:</h5>
                <?= lang('purchase_info_edit') ?><b><?= get('id') ?></b>
            </div>
            <?php echo form_open_multipart('invoice/sales/drop', ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
            <!-- //content -->
            <!-- Information Items START -->
            <div class="card">
                <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-fw fa-dice-two"></i><?php echo lang('information_items') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row" id="order_item">
                        <div class="col-12">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th width="2%">No.</th>
                                    <th width="10%"><?= lang('item_code') ?></th>
                                    <th><?= lang('item_name') ?></th>
                                    <th style="display:none"><?= lang('item_quantity') ?></th>
                                    <th width="10%"><?= lang('item_order_quantity') ?></th>
                                    <th width="10%"><?= lang('item_capital_price') ?></th>
                                    <th style="display:none"><?= lang('item_selling_price') ?></th>
                                    <th style="display:none"><?= lang('discount') ?></th>
                                    <th width="10%"><?= lang('total_price') ?></th>
                                    <th width="10%"><?= lang('option') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="input-0" id="main">
                                    <td class="text-center"><div class="form-control form-control-sm">1.</div></td>
                                    <td>
                                    <input type="hidden" name="item_id[]" id="item_id" data-id="item_id">
                                    <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" required>
                                    </td>
                                    <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required></textarea></td>
                                    <td style="display:none" >
                                    <div class="input-group input-group-sm">
                                        <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required>
                                        <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit">
                                        <div class="input-group-append">
                                        <span class="input-group-text" data-id="item_unit"></span>
                                        </div>
                                    </div>
                                    </td>
                                    <td>
                                    <div class="input-group input-group-sm">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text" data-id="item_quantity"></span>
                                    </span>
                                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" value="0" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" data-id="item_unit"></span>
                                    </div>
                                    </div>
                                    </td>
                                    <td><input class="form-control form-control-sm" type="text" name="item_capital_price[]" data-id="item_capital_price" required readonly></td>
                                    <td style="display:none"><input class="form-control form-control-sm" type="text" name="item_selling_price[]" data-id="item_selling_price" required></td>
                                    <td style="display:none"><input class="form-control form-control-sm" type="text" name="item_discount[]" data-id="discount" value="0" required></td>
                                    <td><input class="form-control form-control-sm" type="text" name="total_price[]" data-id="total_price" value="0" required></td>
                                    <td>
                                    <div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-default" id="description" data-toggle="tooltip" data-placement="top" title="Open dialog description item purchase"><i class="fas fa-tw fa-ellipsis-h"></i></button>
                                        <a target="_blank" class="btn btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                                        <button type="button" class="btn btn-default" disabled><i class="fa fa-tw fa-times"></i></button>
                                    </div>
                                    </td>
                                </tr>
                                <tr class="description input-0" style="display:none">
                                    <td colspan="8">
                                        <textarea class="form-control form-control-sm" name="description[]"></textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="float-left ml-1">
                                <button type="button" class="btn btn-sm btn btn-info" id="add_more"><?= lang('add_more') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Information Items END -->
            
            <div class="card">
                <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-fw fa-dice-three"></i><?php echo lang('information_payment') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 offset-lg-10 col-sm-12">
                            <div class="form-group">
                                <h6><?=lang('date')?></h6>
                                <div class="input-group">
                                    <input type="text" id="created_at" name="created_at" class="form-control" data-target="#created_at"/>
                                    <div class="input-group-append" data-target="#created_at" data-toggle="daterangepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-sm-12">
                            <div class="form-group">
                                <label for="note"><?= lang('note') ?></label>
                                <textarea name="note" id="note" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <!-- Information payment END -->
            <div class="card">
                <div class="card-footer">
                <div class="float-right">
                    <button type="submit" class="btn btn-info float-right"><?= lang('save') ?></button>
                    <button type="button" class="btn btn-default mr-2" onclick="history.back()"><?= lang('back') ?></button>
                </div>
                </div>
            </div>
            <!-- //end-content -->
            <?php echo form_close(); ?>
        </div>
    </div>
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
<script>
    $('body').addClass('sidebar-collapse');
</script>
<!-- Jquery ui -->
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        $('.remove').on('click', function(){
            let id = $(this).data('id');
            $('#modal-remove-order').on('shown.bs.modal', function(){
                $(this).find('input#id').val(id);
            })
        })
            
        //Date range picker
        $('#created_at').daterangepicker({
            startDate: moment(),
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            opens: "center",
            drops: "up",
            locale: {
                format: 'DD/MM/YYYY H:mm:s'
            }
        });
    });
</script>
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>

<script type="module" src="<?php echo $url->assets ?>pages/invoice/sale/MainDropCreate.js"></script>