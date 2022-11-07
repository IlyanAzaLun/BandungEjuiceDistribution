      <!-- Information customer START -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title"><i class="fa fa-fw fa-dice-one"></i><?php echo lang('information_customer') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="store_name"><?= lang('customer_code') ?></label>
                <input type="text" name="customer_code" id="customer_code" class="form-control" placeholder="<?= lang('find_customer_code') ?>" autocomplete="false" required>
                <?= form_error('customer_code', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for="store_name"><?= lang('store_name') ?></label>
                <input type="text" name="store_name" id="store_name" class="form-control" placeholder="<?= lang('find_store_name') ?>" autocomplete="false" required>
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
                    <th><?= lang('item_name') ?></th>
                    <th style="display:none"><?= lang('item_quantity') ?></th>
                    <th width="10%"><?= lang('item_order_quantity') ?></th>
                    <th style="display:none"><?= lang('item_capital_price') ?></th>
                    <th width="10%"><?= lang('item_selling_price') ?></th>
                    <th width="10%"><?= lang('discount') ?></th>
                    <th width="10%"><?= lang('total_price') ?></th>
                    <th width="10%"><?= lang('option') ?></th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="input-0" id="main">
                    <td class="text-center"><div class="form-control form-control-sm">1.</div></td>
                    <td>
                      <input type="hidden" name="id[]" id="id">
                      <input type="hidden" name="item_id[]" id="item_id" data-id="item_id">
                      <input class="form-control form-control-sm" type="text" name="item_code[]" data-id="item_code" required>
                    </td>
                    <td><textarea class="form-control form-control-sm" type="text" name="item_name[]" data-id="item_name" required></textarea></td>
                    <td style="display:none">
                      <div class="input-group input-group-sm">
                        <input readonly class="form-control form-control-sm" type="text" name="item_quantity[]" data-id="item_quantity" required>
                        <input type="hidden" name="item_unit[]" id="item_unit" data-id="item_unit">
                        <input class="form-control form-control-sm" type="hidden" name="item__total_weight[]" data-id="item__total_weight" required readonly>
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
                    <td style="display:none"><input class="form-control form-control-sm" type="text" name="item_capital_price[]" data-id="item_capital_price" required></td>
                    <td><input class="form-control form-control-sm" type="text" name="item_selling_price[]" data-id="item_selling_price" required></td>
                    <td><input class="form-control form-control-sm" type="text" name="item_discount[]" data-id="discount" value="0" required></td>
                    <td>
                      <input class="form-control form-control-sm" type="text" name="total_price[]" data-id="total_price" value="0" required readonly>
                      <input class="form-control form-control-sm" type="hidden" name="total_price_cap[]" data-id="total_price_cap" value="0" required>
                    </td>                
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
                <input type="hidden" id="total_weights_item" name="total_weights_item" value="0">
                <button type="button" class="btn btn-sm btn btn-info" id="add_more"><?= lang('add_more') ?></button>
              </div>
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
                      <input type="text" name="sub_total" id="sub_total" class="form-control" value="0" min="1" required readonly>
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
                      <input type="text" name="discount" id="discount" class="form-control" value="0" required>
                      <div class="input-group-append" style="display: none;">
                        <span class="input-group-text" id="discount_present">0%</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-12">
                  <div class="form-group">
                    <h6><?= lang('other_cost') ?> :</h6>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" name="other_cost" id="other_cost" class="form-control" value="0" required>
                      <div class="input-group-append">
                        <span class="input-group-text" id="other_cost_present">0%</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-12" style="display: none">
                  <div class="form-group">
                    <h6><?= lang('shipping_cost') ?> :</h6>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input type="text" name="shipping_cost" id="shipping_cost" class="form-control" value="0" required>
                    </div>
                  </div>
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
                  <input type="text" name="grand_total" id="grand_total" class="form-control" value="0" min="1" required readonly>
                </div>
              </div>
            </div>
            <!--  -->
            <div class="col-lg-3 col-sm-12">
                <div class="form-group">
                    <h6><?= lang('expedition_name') ?></h6>
                    <select class="custom-select" name="expedition_name" id="expedition_name" required>
                        <option selected disabled><?=lang('option')?></option>
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
            <div class="col-lg-1 col-sm-12">
              <div class="form-group">
                <h6><?= lang('payment_type') ?></h6>
                <select class="custom-select" name="payment_type">
                  <option value="cash" selected><?= lang('cash') ?></option>
                  <option value="credit"><?= lang('credit') ?></option>
                </select>
              </div>
            </div>
            
            <div class="col-lg-2 col-sm-12" id="source_destination">
                <div class="form-group">
                    <h6><?=lang('bank_name')?></h6>
                    <div class="input-group">
                        <select name="transaction_destination" id="destination" class="custom-select" required>
                            <option value="" disabled selected><?=lang('select_account')?></option>
                            <?php foreach ($bank as $key => $value):?>
                            <option value="<?=$value->id?>"><?=$value->name?>/<?=$value->no_account?>/<?=$value->own_by?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-12">
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

            <div class="col-lg-7 col-sm-12" style="display: none">
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
            <div class="col-lg col-sm-12">
              <div class="form-group">
                <label for="note"><?= lang('note') ?></label>
                <input type="hidden" name="is_have" id="is_have" class="form-control" value="<?=logged('id')?>">
                <input type="hidden" name="shipping_cost_to_invoice" value=0>
                <!-- transaction_destination->bank->id
                     same as customer -->
                <textarea name="note" id="note" class="form-control"></textarea>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- Information payment END -->
      <div class="card">
        <div class="card-footer">
          <div class="float-right">
            <button type="submit" class="btn btn-info float-right"><?= lang('save') ?></button>
            <button type="button" class="btn btn-default mr-2" onclick="history.back()"><?= lang('back') ?></button>
          </div>
        </div>
      </div>
      <script>
        document.addEventListener("DOMContentLoaded", () => {
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