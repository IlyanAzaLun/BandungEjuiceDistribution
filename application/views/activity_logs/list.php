<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('activity_logs') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>">Home</a></li>
          <li class="breadcrumb-item active"><?php echo lang('activity_logs') ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Default card -->
  <div class="card">
    <div class="card-header with-border">
      <h3 class="card-title"><?php echo lang('list_all_activities') ?></h3>

      <div class="card-tools pull-right">
        <button type="button" class="btn btn-card-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-card-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>

    </div>
    <div class="card-body">

      <div class="row">
        <div class="col-sm-12">
          <?php echo form_open('activity_logs', ['method' => 'GET', 'autocomplete' => 'off']); ?>
          <div class="row">
            <div class="col-lg-4 col-sm-12">
              <div class="form-group">
                <label for="Filter-IpAddress"><?php echo lang('activity_ip_address') ?> </label>
                <input type="text" name="ip" id="Filter-IpAddress" onchange="$(this).parents('form').submit();" class="form-control form-control-sm" value="<?php echo get('ip') ?>" placeholder="Search by Ip Addres" />
              </div>
            </div>

            <div class="col-lg-4 col-sm-12">
              <div class="form-group">
                <label for="Filter-User"><?php echo lang('user') ?></label>
                <select name="user" id="Filter-User" onchange="$(this).parents('form').submit();" class="form-control select2">
                  <option value=""><?php echo lang('select_user') ?></option>
                  <?php foreach ($this->users_model->get() as $row) : ?>
                    <?php $sel = (get('user') == $row->id) ? 'selected' : '' ?>
                    <option value="<?php echo $row->id ?>" <?php echo $sel ?>><?php echo $row->name ?> #<?php echo $row->id ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>

            <div class="col-lg-4 col-sm-12">
              <div class="form-group" style="margin-top: 25px;">
                    <label for="">&nbsp;</label>
                <a href="<?php echo url('/activity_logs') ?>" class="btn btn-sm btn-danger"><?php echo lang('reset') ?></a>
                <button type="submit" class="btn btn-sm btn-primary"><?php echo lang('filter') ?></button>
              </div>
            </div>

          </div>

          <?php echo form_close(); ?>

        </div>
      </div>

      <table id="dataTable1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th><?php echo lang('id') ?></th>
            <th><?php echo lang('activity_ip_address') ?></th>
            <th><?php echo lang('activity_message') ?></th>
            <th><?php echo lang('activity_datetime') ?></th>
            <th><?php echo lang('action') ?></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>

<script>
  $(function() {
    // $('#dataTable1').DataTable({
    //   "order": []
    // })

    $('.select2').select2();
    $("#dataTable1").DataTable({

      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
          <'row'<'col-12'tr>>
          <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: {
        "url": "<?php echo url('activity_logs/serverside_datatables_data_activity_logs') ?>",
        "type": "POST",
        "data": {
          "<?php echo $this->security->get_csrf_token_name(); ?>": $('meta[name=csrf_token_hash]').attr('content'),
          "ip": '<?= get('ip') ?>',
          "user": '<?= get('user') ?>'
        }
      },
      columns: [{
          data: "id"
        },
        {
          data: "ip_address",
          render: function(data, type, row) {
            return `<a href="<?= url('activity_logs') ?>/index?ip=${data}">${data}</a>`
          }
        },
        {
          data: "title"
        },
        {
          data: "created_at",
          render: function(data, type, row) {
            return data;
          }
        },
        {
          data: "id",
          orderable: false,
          render: function(data, type, row) {
            let html = (row['user'] > 0) ? `<a href="<?= url('users') ?>/view/${row['user']}" class="btn btn-sm btn-default" title="View User" target="_blank" data-toggle="tooltip"><i class="fa fa-user"></i></a>` : ``;
            return `
            <div class="btn-group d-flex justify-content-center">
            <?php if (hasPermissions('activity_log_view')) : ?>
              <a href="<?= url('activity_logs') ?>/view/${row['id']}" class="btn btn-sm btn-default" title="View Activity" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
              ${html}
            <?php endif ?>
            </div>`;
          }
        },
      ],
      buttons: [{
        text: 'Export',
        extend: 'excelHtml5',
        className: 'btn-sm',
        customize: function(xlsx) {
          var sheet = xlsx.xl.worksheets['sheet1.xml'];
        }
      }, ]
    });
  })
</script>