<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$i = 0; $total_items = 0; $data = $this->input->get();?>
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
                <?= lang('weight_shipping_information') ?></b>
            </div>
            <!-- //content -->
            <!-- Information customer START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_supplier') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('customer_code') ?></label>
                                <input readonly type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" autocomplete="false" value="<?= $invoice->customer?$invoice->customer:$invoice->supplier; ?>" required>
                                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('store_name') ?></label>
                                <input readonly type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" required>
                                <?= form_error('store_name', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="owner_name"><?= lang('name') ?></label>
                                <input readonly type="text" name="owner_name" id="owner_name" class="form-control" value="<?= set_value('owner_name') ?>">
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="contact_phone"><?= lang('contact_phone') ?><small class="text-primary"> (whatsapp)</small></label>
                                <input readonly type="text" name="contact_phone" id="contact_phone" class="form-control" value="<?= set_value('contact_phone') ?>">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="address"><?= lang('address_destination') ?></label>
                                <textarea readonly type="text" name="address" id="address" class="form-control" required><?= set_value('address') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-lg col-sm-12">
                            <div class="form-group">
                                <label for="contact_us"><?= lang('contact_us') ?></label>
                                <input readonly name="contact_us" id="contact_us" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_open_multipart('validation/shipper/pack?invoice=' . get('invoice'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
            <!-- Information customer END -->
            <div class="card" style="display:none">
                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Information payment START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-two"></i><?php echo lang('information_payment') ?></h3>
                </div>
                <div class="card-body payment">
                    <div class="row">
                        <!--  -->
                        <div class="col-lg-3 col-sm-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('is_controlled_by') ?></h6>
                                        <input class="form-control" type="text" disabled value="<?=$invoice->is_controlled_by?>">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('expedition_name') ?></h6>
                                        <select disabled class="custom-select" name="expedition_name" id="expedition_name" required>
                                            <option value="" selected disabled><?=lang('option')?></option>
                                            <?php foreach ($expedition as $key => $value):?>
                                                <option value="<?= $value->expedition_name ?>"<?=($value->expedition_name == $invoice->expedition)?' selected':''?> data-services="<?= $value->services_expedition ?>"><?= $value->expedition_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="row">
                                <div class="col-lg-8 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('expedition_services') ?></h6>
                                        <select disabled class="custom-select" name="services_expedition" id="services_expedition">
                                            <option value="" selected disabled><?=lang('option')?></option>
                                            <option value="<?=$invoice->services_expedition?>" selected><?=$invoice->services_expedition?$invoice->services_expedition:lang('option')?></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('pack') ?></h6>
                                        <input disabled class="form-control" type="text" name="pack" id="pack" value="<?=$invoice->pack?>" required>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('pack_by') ?></h6>
                                        <input disabled class="form-control" type="text" name="pack_by" id="pack_by" value="<?=$invoice->pack_by?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('select_payment_shipping') ?></h6>
                                        <select disabled class="custom-select" name="type_payment_shipping" id="type_payment_shipping">
                                            <option value=""><?=lang('option')?></option>
                                            <option value="TAGIH TUJUAN" <?=$invoice->type_payment_shipping=="TAGIH TUJUAN"?'selected':''?>>TAGIH TUJUAN</option>
                                            <option value="KREDIT" <?=$invoice->type_payment_shipping=="KREDIT"?'selected':''?>>KREDIT</option>
                                            <option value="TUNAI" <?=$invoice->type_payment_shipping=="TUNAI"?'selected':''?>>TUNAI</option>
                                            <option value="DFOD" <?=$invoice->type_payment_shipping=="DFOD"?'selected':''?>>DFOD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 colsm-12">
                                    <div class="form-group">
                                        <h6><?= lang('receipt_code') ?></h6>
                                        <input readonly class="form-control" type="text" name="pack" id="pack" value="<?=$invoice->receipt_code?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg col-sm-12">
                                    <div class="form-group">
                                        <label for="note"><?= lang('note') ?></label>
                                        <textarea readonly name="note" id="note" class="form-control"><?= $invoice->note ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Information payment END -->
            
            <div class="card">
                <div class="card-footer">
                    <div class="float-left">
                        <a href="<?= url("validation/shipper/pack?")."invoice=".$data['invoice'] ?>" class="btn btn-default float-right mr-2"><i class="fa fa-tw fa-undo text-danger"></i></a>
                    </div>
                    <div class="float-right">
                        <button disabled type="button" class="btn btn-info float-right"><?= lang('save') ?></button>
                        <button type="button" class="btn btn-default mr-2" onclick="window.close()"><?= lang('close') ?></button>
                        <button type="button" class="btn btn-primary mr-2 confirmation" data-id="<?=$data['invoice']?>" data-toggle="modal" data-target="#modal-confirmation-order"><?= lang('confirmation') ?></button>
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

        $('.confirmation').on('click', function(){
          let id = $(this).data('id');
          $('#modal-confirmation-order').on('shown.bs.modal', function(){
            $("textarea#note").prop('required',true);
            $("label[for='note']").text('Receipt Code');
            $(this).find('input#id').val(id);
          })
      });
    })
    
    //Date range picker
    function cb(start, end) {
      $('#date_due span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    $('#date_due').daterangepicker({
        startDate: moment(),
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
        opens: "center",
        drops: "up",
        locale: {
            format: 'DD/MM/YYYY H:mm:s'
        },
        ranges: {
            'Today': [moment(), moment()],
            'Tomorow': [moment(), moment().add(1, 'days')],
            'Next 7 Days': [moment(), moment().add(6, 'days')],
            'Next 14 Days': [moment(), moment().add(13, 'days')],
            'Next 30 Days': [moment(), moment().add(29, 'days')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
        },
    }, cb).on('apply.daterangepicker', function(ev, picker) {
        $('#numberdays').val(picker.endDate.diff(picker.startDate, "days"));
    });

    //Date range picker
    $('#created_at').daterangepicker({
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
</script>
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>

<script type="module" src="<?php echo $url->assets ?>pages/shipper/packer/MainShipperPacker.js"></script>