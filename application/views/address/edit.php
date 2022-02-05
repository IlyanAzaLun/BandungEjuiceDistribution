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
          <li class="breadcrumb-item active"><?php echo lang('address') ?></li>
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
            <h3 class="card-title"><?= lang('address_info_edit') ?></h3>
          </div>
          <!-- /.card-header -->
          <?php echo form_open_multipart('master_information/address/edit?id=' . get('id'), ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <label><?= lang('store_name') ?></label>
                  <select class="form-control" style="width: 100%;" name="customer_code" id="customer_code" required>
                    <option value="" selected><?= lang('select_store_name') ?></option>
                    <?php foreach ($customers as $key => $customer) : ?>
                      <option value="<?= $customer->customer_code ?>" <?= ($customer->customer_code == $address->customer_code) ? ' selected' : '' ?>><?= $customer->store_name ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>

              <div class="col-sm-3">
                <div class="form-group">
                  <label for="province"><?= lang('province') ?></label>
                  <select class="form-control" name="province" id="province" required>

                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="city"><?= lang('city') ?></label>
                  <select class="form-control" name="city" id="city" required>

                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="sub_district"><?= lang('sub_district') ?></label>
                  <input type="text" class="form-control" name="sub_district" id="sub_district" value="<?= $address->sub_district ?>">
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="village"><?= lang('village') ?></label>
                  <input type="text" class="form-control" name="village" id="village" value="<?= $address->village ?>">
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="zip"><?= lang('zip') ?></label>
                  <input type="text" class="form-control" name="zip" id="zip" value="<?= $address->zip ?>">
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="contact_phone"><?= lang('contact_phone') ?></label>
                  <input type="text" class="form-control" name="contact_phone" id="contact_phone" value="<?= $address->contact_phone ?>">
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="contact_mail"><?= lang('contact_mail') ?></label>
                  <input type="text" class="form-control" name="contact_mail" id="contact_mail" value="<?= $address->contact_mail ?>">
                </div>
              </div>

              <div class="col-6">
                <div class="form-group">
                  <label><?= ucwords(lang('address')) ?></label>
                  <textarea type="text" class="form-control" name="address" id="address"><?= $address->address ?></textarea>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label><?= lang('note') ?></label>
                  <textarea type="text" class="form-control" name="note" id="note"><?= $address->note ?></textarea>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div class="form-group">
                  <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" <?= ($address->is_active) ? ' checked' : '' ?>>
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
              <button type="cancel" class="btn btn-default mr-2"><?= lang('cancel') ?></button>
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
  let option_1 = $('select#province');
  let option_2 = $('select#city');
  option_1.empty();
  option_1.append('<option selected="true" disabel value=""><?= lang('province') ?></option>');
  option_1.prop('selectedIndex', 0);
  $.getJSON('<?= url('assets/json/province.json') ?>', function(data) {
    $.each(data, function(key, entry) {
      option_1.append($(`<option data-id="${entry.province_id}" ${(entry.province=='<?= $address->province ?>')?' selected':''}></option>`).attr('value', entry.province).text(entry.province));
    })
  });
  $.getJSON('<?= url('assets/json/city.json') ?>', function(data) {
    province_id = option_1.find('option:selected').data('id');
    $.each(data, function(key, entry) {
      if (entry.province_id == province_id) {
        option_2.append($(`<option${(entry.province=='<?= $address->province ?>')?' selected':''}></option$>`).attr('value', entry.city_name).text(entry.city_name));
      }
    });
  });
  option_1.on('change', function() {
    province_id = option_1.find('option:selected').data('id');
    option_2.empty();
    option_2.append('<option selected="true" disabel value=""><?= lang('city') ?></option>');
    option_2.prop('selectedIndex', 0);
    $.getJSON('<?= url('assets/json/city.json') ?>', function(data) {
      $.each(data, function(key, entry) {
        if (entry.province_id == province_id) {
          option_2.append($('<option></option>').attr('value', entry.city_name).text(entry.city_name));
        }
      });
    });
  })
</script>