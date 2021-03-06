<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$i = 0;$j = 0; $total_price = 0;?>
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
                <?= lang('purchase_info_info') ?><b><?= get('id') ?></b>
            </div>
            <!-- Information customer START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_supplier') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('supplier_code') ?></label>
                                <div type="text" name="supplier_code" id="supplier_code" class="form-control" readonly><?= $_data_invoice_parent->supplier ?></div>
                                <?= form_error('supplier_code', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('store_name') ?></label>
                                <input type="text" name="store_name" id="store_name" class="form-control" required readonly>
                                <?= form_error('store_name', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="contact_phone"><?= lang('contact_phone') ?><small class="text-primary"> (whatsapp)</small></label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="<?= set_value('contact_phone') ?>" required readonly>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="address"><?= lang('address_destination') ?></label>
                                <textarea type="text" name="address" id="address" class="form-control" required readonly><?= set_value('address') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Information customer END -->
            <pre>
            <?php var_dump($_data_invoice_child_);?>
            </pre>
            <!-- Information Items START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-two"></i><?php echo lang('information_items') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row" id="order_item">
                        <div class="col-12">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th width="2%">No.</th>
                                        <th width="8%"><?= lang('item_code') ?></th>
                                        <th><?= lang('item_name') ?></th>
                                        <th style="display:none"><?= lang('item_quantity') ?></th>
                                        <th width="10%"><small><?= lang('item_order_quantity') ?></small><br><?=lang('parents')?> <small><?=date("d-m-Y",strtotime($_data_invoice_parent->created_at))?></small></th>
                                        <th width="10%"><small><?= lang('item_order_quantity') ?></small><br><?=lang('returns')?> <small><?=date("d-m-Y",strtotime(isset($_data_invoice_child_->updated_at)?$_data_invoice_child_->updated_at:'now'))?></small></th>
                                        <th width="9%"><small><?= lang('item_order_quantity') ?></small><br><?=lang('total_is_sold')?></th>
                                        <th width="9%"><small><?= lang('item_order_quantity') ?></small><br><?=lang('total_balace')?></th>
                                        <th width="8%"><?= lang('item_capital_price') ?></th>
                                        <th style="display:none"><?= lang('item_selling_price') ?></th>
                                        <th width="6%"><?= lang('discount') ?></th>
                                        <th width="9%"><?= lang('total_price') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_data_item_invoice_parent as $key => $value) : ?>
                                        <?php $total_price += $value->total_price;?>
                                        <?php if(@$value->item_code == @$intersect_codex_item[$key] && @$value->index_list == @$intersect_index_item[$key]):?>
                                        <tr class="input-<?= $key ?>">
                                            <td><?= $key+1 ?>.</td>
                                            <td><a href="<?=url('items/info_transaction?id='.$value->item_code)?>"><?= $value->item_code ?></a></td>
                                            <td><?= $value->item_name ?></td>
                                            <td style="display:none"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?> <?= $value->item_unit ?></td>
                                            <td><?= $value->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <td><?= $_data_item_invoice_child_[$i]->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <td></td>
                                            <td><?= $value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <td>Rp.<?= number_format($_data_item_invoice_child_[$i]->item_capital_price) ?></td>
                                            <td style="display:none">Rp.<?= number_format($_data_item_invoice_child_[$i]->item_selling_price) ?></td>
                                            <td>Rp.<?= number_format($_data_item_invoice_child_[$i]->item_discount) ?></td>
                                            <td>Rp.<?= number_format($_data_item_invoice_child_[$i]->total_price) ?></td>
                                        </tr>
                                        <!-- SALE OUT -->
                                        <tr class="input-<?= $key ?>">
                                            <?php if($value->item_code == $sale_out[$j]->item_code):?>
                                            <?php $item_sold = $sale_out[$j]->item_is_sold > $value->item_quantity-$sale_out[$j]->item_in?$value->item_quantity-$sale_out[$j]->item_in:$sale_out[$j]->item_is_sold;?>
                                            <td></td>
                                            <td><?=$sale_out[$j]->item_code;  // item_code?></td>
                                            <td><?=$sale_out[$j]->item_name;  // item_name?></td>
                                            <td></td>
                                            <td><?=$sale_out[$j]->item_in;  // item_retur?></td>
                                            <td><?=$item_sold;  // item_sold?></td>
                                            <td><?= $value->item_quantity-$_data_item_invoice_child_[$i]->item_quantity-$item_sold-$sale_out[$j]->item_in ?></td>
                                            <td>Rp.<?=number_format($sale_out[$j]->item_capital_price);  // item_price?></td>
                                            <td></td>
                                            <td><b>Rp.<?=number_format($sale_out[$j]->item_capital_price * $item_sold);  // item_total_price?></b></td>
                                            <?php $j++;?>
                                            <?php endif; ?>
                                        </tr>
                                        <!-- SALE OUT -->
                                        <?php if((strlen($_data_item_invoice_child_[$i]->item_description)) >= 1):?>
                                        <tr>
                                            <td></td>
                                            <td colspan="8"><b style="font-size: 14px;"><?= $value->item_description?></b></td>
                                        </tr>
                                        <?php endif;?>
                                        <!-- <pre>Item INDEX RETUR:<?=$_data_item_invoice_child_[$i]->index_list?> | Item CODE PURCH:<?=$_data_item_invoice_child_[$i]->item_code?> | Item Quantity PURCH:<?=$_data_item_invoice_child_[$i]->item_quantity?></pre> -->
                                        <?php $i++;?>
                                        <?php else: $item_sold?>     
                                        <tr class="input-<?= $key ?>">
                                            <td><?= $key+1 ?>.</td>
                                            <td><a href="<?=url('items/info_transaction?id='.$value->item_code)?>"><?= $value->item_code ?></a></td>
                                            <td><?= $value->item_name ?></td>
                                            <td style="display:none"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?> <?= $value->item_unit ?></td>
                                            <td><?= $value->item_quantity ?>  <?= $value->item_unit ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Rp.<?= number_format($value->item_capital_price) ?></td>
                                            <td style="display:none">Rp.<?= number_format($value->item_selling_price) ?></td>
                                            <td>Rp.<?= number_format($value->item_discount) ?></td>
                                            <td>Rp.<?= number_format($value->total_price) ?></td>
                                        </tr>
                                        <!-- SALE OUT -->
                                        <tr class="input-<?= $key ?>">
                                            <?php if($value->item_code == $sale_out[$j]->item_code):?>
                                            <?php $item_sold = $sale_out[$j]->item_is_sold > $value->item_quantity-$sale_out[$j]->item_in?$value->item_quantity-$sale_out[$j]->item_in:$sale_out[$j]->item_is_sold;?>
                                            <td></td>
                                            <td><?=$sale_out[$j]->item_code;  // item_retur?></td>
                                            <td><?=$sale_out[$j]->item_name;  // item_retur?></td>
                                            <td></td>
                                            <td><?=$sale_out[$j]->item_in;  // item_retur?></td>
                                            <td><?=$item_sold;  // item_sold?></td>
                                            <td><?= $value->item_quantity-$item_sold-$sale_out[$j]->item_in; ?></td>
                                            <td>Rp.<?=number_format($sale_out[$j]->item_capital_price);  // item_price?></td>
                                            <td></td>
                                            <td><b>Rp.<?=number_format($sale_out[$j]->item_capital_price * $item_sold);  // item_total_price?></b></td>
                                            <?php $j++;?>
                                            <?php endif; ?>
                                        </tr>
                                        <!-- SALE OUT -->

                                        <?php if((strlen($value->item_description)) >= 1):?>
                                        <tr>
                                            <td></td>
                                            <td colspan="8"><b style="font-size: 14px;"><?= $value->item_description?></b></td>
                                        </tr>
                                        <?php endif;?>
                                        <!-- <pre>Item INDEX PURCH:<?=$value->index_list?> | Item CODE ORDER:<?=$value->item_code?> | Item Quantity ORDER:<?=$value->item_quantity?></pre> -->
                                        <?php endif;?>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- Information Items END -->
            <!-- Information payment START -->
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-three"></i><?php echo lang('information_payment') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('subtotal') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input readonly type="text" readonly class="form-control currency" value="<?= number_format($_data_invoice_parent->total_price) ?>" min="1" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('discount') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input readonly type="text" class="form-control currency" value="<?= number_format($_data_invoice_parent->discounts) ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('shipping_cost') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input readonly type="text" class="form-control currency" value="<?= number_format($_data_invoice_parent->shipping_cost) ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12" style="display: none;">
                            <div class="form-group">
                                <h6><?= lang('other_cost') ?> :</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input readonly type="text" class="form-control currency" value="<?= number_format($_data_invoice_parent->other_cost) ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <h6><b><?= lang('grandtotal') ?> :</b></h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><b>Rp</b></span>
                                    </div>
                                    <input type="text" readonly class="form-control currency" value="<?= number_format($_data_invoice_parent->grand_total) ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('payment_type') ?></h6>
                                <input readonly type="text" class="form-control" value="<?= lang($_data_invoice_parent->payment_type) ?>" required>
                            </div>
                        </div>
                        <?php if($intersect_codex_item):?>
                        <div class="col-lg-3 col-sm-12 text-danger">
                            <div class="form-group">
                                <h6><b><?= lang('deposit') ?></b></h6>
                                <input readonly type="text" class="form-control" value="<?= number_format($_data_invoice_parent->grand_total - $_data_invoice_child_->grand_total) ?>" required>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="note"><?= lang('note') ?></label>
                                <textarea readonly class="form-control"><?= $_data_invoice_parent->note ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                <input disabled class="custom-control-input" type="checkbox" id="is_consignment" name="is_consignment" <?= $_data_invoice_parent->is_consignment?'checked':'' ?> value=1>
                                <label for="is_consignment" class="custom-control-label"><?=lang('is_consignment')?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <!-- /card-footer -->
                <?php if(!$_data_invoice_parent->is_cancelled):?>
                <div class="card-footer">
                    <div class="float-right">
                        <button type="button" class="btn btn-md btn-danger" data-toggle="modal" data-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Remove this information"><?=lang('cancel')?></button>
                    </div>
                </div>
                <?php endif?>
                <!-- /.card-body -->
            </div>
            <!-- Information payment END -->
            <!-- /.card -->
        </div>
    </div>
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
<script>
    $('body').addClass('sidebar-collapse');
    $(document).ready(function(){
        $('#exampleModal').on('shown.bs.modal', function(){
            $('textarea').prop('required',true);
            $('textarea').focus();
        })
    })
</script>
<script type="module" src="<?php echo $url->assets ?>pages/invoice/purchase/MainPurchaseEdit.js"></script>