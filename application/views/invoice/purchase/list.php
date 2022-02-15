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
          <li class="breadcrumb-item active"><?php echo lang('invoice') ?></li>
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
            <h3 class="card-title">DataTable with minimal features & hover style</h3>
            <div class="card-tools pull-right">
              <?php if (hasPermissions('purchase_create')) : ?>
                <a href="<?php echo url('invoice/purchase/create') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('purchase_create') ?></a>
              <?php endif ?>
            </div>

          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 10px;">
              <thead>
                <tr>
                  <th>no.</th>
                  <th><?= lang('invoice_code') ?></th>
                  <th><?= lang('supplier_code') ?></th>
                  <th><?= lang('created_at') ?></th>
                  <th><?= lang('created_by') ?></th>
                  <th>Last Login</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <th>no.</th>
                  <th><?= lang('invoice_code') ?></th>
                  <th><?= lang('supplier_code') ?></th>
                  <th><?= lang('created_at') ?></th>
                  <th><?= lang('created_by') ?></th>
                  <th>Last Login</th>
                </tr>
              </tfoot>
            </table>

          </div>
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
  $(function() {
    $("#example2").DataTable({

      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
                <'row'<'col-12'tr>>
                <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: {
        "url": "<?php echo url('invoice/purchase/serverside_datatables_data_purchase') ?>",
        "type": "POST",
        "data": {
          "<?php echo $this->security->get_csrf_token_name(); ?>": $('meta[name=csrf_token_hash]').attr('content')
        }
      },
      columns: [{
          data: "invoice_code",
          render: function(data, type, row) {
            return row['id']
          }
        },
        {
          data: "invoice_code"
        },
        {
          data: "supplier"
        },
        {
          data: "created_at"
        },
        {
          data: "created_by"
        },
        {
          data: "id",
          orderable: false,
          render: function(data, type, row) {
            return `
                <div class="btn-group d-flex justify-content-center">
                <a href="<?= url('items') ?>/edit?id=${row['id']}" class="btn btn-xs btn-default"><i class="fa fa-tw fa-edit"></i></a>
                <a href="<?= url('items') ?>/info?id=${row['id']}" class="btn btn-xs btn-default"><i class="fa fa-tw fa-history"></i></a>
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
  });
</script>