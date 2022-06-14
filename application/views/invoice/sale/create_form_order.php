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
                                <input type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" autocomplete="false" value="<?= $order->customer ?>" required readonly>
                                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="store_name"><?= lang('store_name') ?></label>
                                <input type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" required readonly>
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
                                        <th ><?= lang('item_name') ?></th>
                                        <th style="display:none"><?= lang('item_quantity') ?></th>
                                        <th width="12%"><?= lang('item_order_quantity') ?></th>
                                        <th style="display:none"><?= lang('item_capital_price') ?></th>
                                        <th width="10%"><?= lang('item_selling_price') ?></th>
                                        <th width="10%"><?= lang('discount') ?></th>
                                        <th width="10%"><?= lang('total_price') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $key => $value) : ?>
                                        <tr class="input-<?= $key ?>" id="main">
                                            <td class="text-center"><div class="form-control form-control-sm" readonly><?=$key+1?>.</div></td>
                                            <td>
                                                <input type="hidden" name="id[]" id="id" value="<?= $value->id ?>">
                                                <input type="hidden" name="item_id[]" id="item_id" data-id="item_id" value="<?= $this->items_model->getByCodeItem($value->item_code, 'id') ?>">
                                                <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" value="<?= $value->item_code ?>" required readonly>
                                            </td>
                                            <td><input class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" value="<?= $value->item_name ?>" required readonly></td>
                                            <td style="display:none">
                                                <div class=" input-group input-group-sm">
                                                    <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') ?>">
                                                    <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit" value="<?= $value->item_unit ?>">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                                <input readonly class="form-control form-control-sm" type="text" name="item_quantity_current[]" data-id="item_quantity_current" required value="<?= $this->items_model->getByCodeItem($value->item_code, 'quantity') - $value->item_quantity ?>">
                                            </td>
                                            <td>
                                                <div class=" input-group input-group-sm">
                                                    <input class="form-control form-control-sm" type="number" name="item_order_quantity[]" data-id="item_order_quantity" min="1" required value="<?= (int)$value->item_order_quantity ?>" readonly>
                                                    <span class="input-group-append">
                                                        <span class="input-group-text" data-id="item_unit"><?= $value->item_unit ?></span>
                                                    </span>
                                                </div>
                                                <input readonly class="form-control form-control-sm" type="text" name="item_order_quantity_current[]" data-id="item_order_quantity_current" min="1" required value="<?= (int)$value->item_quantity ?>" style="display:none">
                                            </td>
                                            <td style="display:none"><input class="form-control form-control-sm currency" type="text" name="item_capital_price[]" data-id="item_capital_price" required readonly value="<?= $value->item_capital_price ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_selling_price[]" data-id="item_selling_price" required readonly value="<?= $value->item_selling_price ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="item_discount[]" data-id="discount" min="0" readonly required value="<?= (int)$value->item_discount ?>"></td>
                                            <td><input class="form-control form-control-sm currency" type="text" name="total_price[]" data-id="total_price" min="0" readonly required value="<?= $value->item_total_price ?>"></td>
                                        </tr>
                                        <tr class="description input-<?=$key?>" style="display:<?=(!strlen($value->item_description))?'none':''?>">
                                            <td colspan="8">
                                                <textarea class="form-control form-control-sm" name="description[]" readonly><?=$value->item_description?></textarea>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                            
                            <!-- Total Items -->
                            <div class="float-right ml-1">
                                <div class="input-group input-group-sm">
                                <h6 id="total_items">Total Items: 0</h6>
                                </div>
                            </div>              
                            <!--  -->
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
                <div class="card-body payment">
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
                                            <input type="text" readonly name="sub_total" id="sub_total" class="form-control currency" value="<?= $order->total_price ?>" min="1" required>
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
                                            <input type="text" name="discount" id="discount" class="form-control currency" value="<?= $order->discounts ?>" required>
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
                                            <input type="text" name="shipping_cost" id="shipping_cost" class="form-control currency" value="<?= $order->shipping_cost ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <h6><b><?= lang('grandtotal') ?> :</b></h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><b>Rp</b></span>
                                            </div>
                                            <input type="text" readonly name="grand_total" id="grand_total" class="form-control currency" value="<?= $order->grand_total ?>" min="1" required>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('expedition_name') ?></h6>
                                        <select class="custom-select" name="expedition_name" id="expedition_name" required>
                                            <option value="" selected disabled><?=lang('option')?></option>
                                            <?php foreach ($expedition as $key => $value):?>
                                                <option value="<?= $value->expedition_name ?>" data-services="<?= $value->services_expedition ?>"><?= $value->expedition_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <h6><?= lang('expedition_services') ?></h6>
                                        <select class="custom-select" name="services_expedition" id="services_expedition" required>
                                        </select>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <!--  -->
                                <div class="col-lg-6 col-sm-12" style="display:none">
                                    <div class="form-group">
                                        <h6><?= lang('other_cost') ?> :</h6>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" name="other_cost" id="other_cost" class="form-control currency" value="<?= $order->other_cost ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-2 col-sm-12">
                                            <div class="form-group">
                                                <h6><?= lang('payment_type') ?></h6>
                                                <select class="custom-select" name="payment_type" id="payment_type">
                                                    <option value="cash" <?= ($order->payment_type == 'cash') ? ' selected' : '' ?>><?= lang('cash') ?></option>
                                                    <option value="credit" <?= ($order->payment_type == 'credit') ? ' selected' : '' ?>><?= lang('credit') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12" style="display:<?= ($order->payment_type == 'credit') ? 'none' : '' ?>" id="source_destination">
                                            <div class="form-group">
                                                <h6><?=lang('bank_name')?></h6>
                                                <div class="input-group">
                                                    <select name="transaction_destination" id="destination" class="custom-select" <?= ($order->payment_type == 'cash') ? 'required' : '' ?>>
                                                        <option value="" disabled selected><?=lang('select_account')?></option>
                                                        <?php foreach ($bank as $key => $value):?>
                                                        <option value="<?=$value->id?>"><?=$value->name?>/<?=$value->no_account?>/<?=$value->own_by?></option>
                                                        <?php endforeach?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg col-sm-12">
                                            <div class="form-group">
                                                <h6><?=lang('date')?></h6>
                                                <div class="input-group">
                                                    <input type="text" id="created_at" name="created_at" class="form-control" data-target="#created_at" value="<?=date("d/m/Y H:i:s",strtotime($order->created_at))?>">
                                                    <div class="input-group-append" data-target="#created_at" data-toggle="daterangepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-7 col-sm-12">
                                            <div class="form-group">
                                                <h6><?= lang('date_due') ?></h6>
                                                <div class="input-group mb-3">
                                                <input type="text" id="date_due" name="date_due" class="form-control" data-target="#date_due">
                                                <div class="input-group-append" data-target="#date_due" data-toggle="daterangepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-sm-12">
                                            <h6><?=lang('number_of_day')?></h6>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="numberdays" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                        </div>
                        <div class="col-lg col-sm-12">
                            <div class="form-group">
                                <label for="note"><?= lang('note') ?></label>
                                <textarea name="note" id="note" class="form-control"><?= $order->note ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <?php if ($order->is_created):?>
            <div class="callout callout-warning">
                <h5><i class="fas fa-exlamation"></i> Note:</h5>
                <b class="text-danger"><?= lang('order_invoice_has_created') ?></b>
            </div>
            <?php endif ?>
            <!-- Information payment END -->
            <div class="card">
                <div class="card-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-info float-right" <?php echo $order->is_created?'disabled':'';?>><?= lang('save') ?></button>
                        <button type="button" class="btn btn-default mr-2" onclick="history.back()"><?= lang('cancel') ?></button>
                    </div>
                </div>
            </div>