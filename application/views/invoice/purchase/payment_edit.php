<?php
defined('BASEPATH') or exit('No direct script access allowed');
$__invoice_code = str_replace('RET','INV',$this->input->get('id'));
$_data_invoice = $this->transaction_item_model->get_transaction_item_by_code_invoice($__invoice_code);
$_data_item_invoice = $this->transaction_item_model->get_transaction_item_by_code_invoice($__invoice_code);
$items_code = array_column($_data_item_invoice, 'item_code');
$items_code_return = array_column($items, 'item_code');
$intersect = array_intersect($items_code, $items_code_return);
$i = 0; ?>
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">
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
                                <div type="text" name="supplier_code" id="supplier_code" class="form-control" readonly><?= $invoice->supplier ?></div>
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
            <!-- Information Items START -->
            <div class="card" style="display:none">
                <div class="card-header with-border">
                    <h3 class="card-title"><i class="fa fa-fw fa-dice-two"></i><?php echo lang('information_items') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row" id="order_item">
                        <div class="col-12">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th><?= lang('item_code') ?></th>
                                        <th><?= lang('item_name') ?></th>
                                        <th style="display:none"><?= lang('item_quantity') ?></th>
                                        <th><?= lang('item_capital_price') ?></th>
                                        <th style="display:none"><?= lang('item_selling_price') ?></th>
                                        <th><?= lang('item_order_quantity') ?></th>
                                        <th><?= lang('discount') ?></th>
                                        <th><?= lang('total_price') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($_data_item_invoice as $key => $value) : ?>
                                    <?php if ($value->item_code == $intersect[$key]):?>
                                      <tr class="input-<?= $key ?>">
                                          <td><?= $key+1 ?>.</td>
                                          <td><a href="<?=url('items/info_transaction?id='.$items[$i]->item_code)?>"><?= $items[$i]->item_code ?></a></td>
                                          <td><?= $items[$i]->item_name ?></td>
                                          <td style="display:none"><?= $this->items_model->getByCodeItem($items[$i]->item_code, 'quantity') ?> <?= $items[$i]->item_unit ?></td>
                                          <td>Rp.<?= number_format($items[$i]->item_capital_price) ?></td>
                                          <td style="display:none">Rp.<?= number_format($items[$i]->item_selling_price) ?></td>
                                          <td><?= $value->item_quantity - $items[$i]->item_quantity ?>  <?= $items[$i]->item_unit ?></td>
                                          <td>Rp.<?= number_format($items[$i]->item_discount) ?></td>
                                          <td>Rp.<b><?= number_format($items[$i]->total_price) ?></b></td>
                                      </tr>
                                      <?php if((strlen($items[$i]->item_description)) >= 1):?>
                                      <tr class="input-<?= $key ?>">
                                          <td></td>
                                          <td colspan="8"><b style="font-size: 14px;"><?= $items[$i]->item_description?></b></td>
                                      </tr>
                                      <?php endif ?>
                                    <?php $i++; ?>  
                                    <?php else: ?>
                                      <tr class="input-<?= $key ?>">
                                          <td><?= $key+1 ?>.</td>
                                          <td><a href="<?=url('items/info_transaction?id='.$value->item_code)?>"><?= $value->item_code ?></a></td>
                                          <td><?= $value->item_name ?></td>
                                          <td style="display:none"><?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?> <?= $value->item_unit ?></td>
                                          <td>Rp.<?= number_format($value->item_capital_price) ?></td>
                                          <td style="display:none">Rp.<?= number_format($value->item_selling_price) ?></td>
                                          <td><?= $value->item_quantity ?>  <?= $value->item_unit ?></td>
                                          <td>Rp.<?= number_format($value->item_discount) ?></td>
                                          <td>Rp.<b><?= number_format($value->total_price) ?></b></td>
                                      </tr>
                                      <?php if((strlen($value->item_description)) >= 1):?>
                                      <tr class="input-<?= $key ?>">
                                          <td></td>
                                          <td colspan="8"><b style="font-size: 14px;"><?= $value->item_description?></b></td>
                                      </tr>
                                      <?php endif ?>
                                    <?php endif ?>
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
                            <div class="form-group">
                                <h6><?= lang('subtotal') ?> :</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input readonly type="text" readonly class="form-control currency" value="<?= number_format($invoice->total_price) ?>" min="1" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('discount') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input readonly type="text" class="form-control currency" value="<?= number_format($invoice->discounts) ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('shipping_cost') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input readonly type="text" class="form-control currency" value="<?= number_format($invoice->shipping_cost) ?>" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('other_cost') ?> :</h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input readonly type="text" class="form-control currency" value="<?= number_format($invoice->other_cost) ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                <h6><b><?= lang('grandtotal') ?> :</b></h6>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><b>Rp</b></span>
                                    </div>
                                    <input type="text" readonly class="form-control currency" value="<?= number_format($invoice->grand_total) ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <div class="form-group">
                                <h6><?= lang('payment_type') ?></h6>
                                <input readonly type="text" class="form-control<?=($invoice->payment_type!='credit')?' bg-success':' bg-danger'?>" value="<?= lang($invoice->payment_type) ?>" required>
                            </div>
                        </div>
                        <div class="col-lg col-sm-12">
                            <div class="form-group">
                                <label for="note"><?= lang('note') ?></label>
                                <textarea readonly class="form-control"><?= $invoice->note ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
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
</script>
<script type="module" src="<?php echo $url->assets ?>pages/invoice/purchase/MainPurchaseEdit.js"></script>
<!-- /.content -->
