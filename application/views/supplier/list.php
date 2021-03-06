<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>
<style>
  td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
  }

  tr.shown td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_close.png') no-repeat center center;
  }
</style>
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
          <li class="breadcrumb-item active"><?php echo lang('supplier') ?></li>
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
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-sm table-bordered table-hover">
              <thead>
                <tr>
                  <th></th>
                  <th>No.</th>
                  <th><?= lang('supplier_code') ?></th>
                  <th><?= lang('store_name') ?></th>
                  <th><?= lang('supplier_owner') ?></th>
                  <th><?= lang('supplier_type') ?></th>
                  <th><?= lang('option') ?></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <th>No.</th>
                  <th><?= lang('supplier_code') ?></th>
                  <th><?= lang('store_name') ?></th>
                  <th><?= lang('supplier_owner') ?></th>
                  <th><?= lang('supplier_type') ?></th>
                  <th><?= lang('option') ?></th>
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
    function format(d) {
      // `d` is the original data object for the row
      return `
        <table class="table bg-secondary table-sm">
          <thead>
            <tr>
              <th><?= lang('village') ?>:</th>
              <th><?= lang('sub_district') ?>:</th>
              <th><?= lang('city') ?>:</th>
              <th><?= lang('province') ?>:</th>
              <th><?= lang('zip') ?>:</th>
              <th><?= lang('contact_phone') ?>:</th>
              <th><?= lang('contact_mail') ?>:</th>
              <th><?= lang('created_at') ?>:</th>
              <th><?= lang('created_by') ?>:</th>
            </tr>
          </thead>
            <tr>
                <td>${d.village}</td>
                <td>${d.sub_district}</td>
                <td>${d.city}</td>
                <td>${d.province}</td>
                <td>${d.zip}</td>
                <td>${d.contact_phone}</td>
                <td>${d.contact_mail}</td>
                <td>${d.created_at}</td>
                <td>${d.created_by}</td>
            </tr>
        </table>`;
    }
    var table = $("#example2").DataTable({
      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
              <'row'<'col-12'tr>>
              <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: {
        "url": "<?php echo url('master_information/supplier/serverside_datatables_data_supplier') ?>",
        "type": "POST",
        "data": {
          "<?php echo $this->security->get_csrf_token_name(); ?>": $('meta[name=csrf_token_hash]').attr('content')
        }
      },
      columns: [{
          className: 'details-control',
          orderable: false,
          data: null,
          defaultContent: ''
        },
        {
          data: "customer_id"
        },
        {
          data: "customer_code",
          orderable: false
        },
        {
          data: "store_name"
        },
        {
          data: "owner_name"
        },
        {
          data: "supplier_type"
        },
        {
          data: "customer_code",
          orderable: false,
          render: function(data, type, row) {
            return `
                <div class="btn-group d-flex justify-content-center">
                <a href="<?= url('master_information/supplier') ?>/edit?id=${data}" class="btn btn-xs btn-default"><i class="text-primary fa fa-fw fa-edit"></i></a>
                <a href="<?= url('master_information/supplier') ?>/info?id=${data}" class="btn btn-xs btn-default"><i class="text-primary fa fa-fw fa-search-plus"></i></a>
                </div>`;
          }
        },
      ],
      buttons: [{
          text: 'Expand All',
          className: 'btn-sm btn-success',
          attr: {
            id: 'btn-show-all-children',
          }
        },
        {
          text: 'Collapse All',
          className: 'btn-sm btn-info',
          attr: {
            id: 'btn-hide-all-children',
          }
        },
        {
          text: 'Export',
          extend: 'excelHtml5',
          className: 'btn-sm',
          customize: function(xlsx) {
            var sheet = xlsx.xl.worksheets['sheet1.xml'];
          }
        },
        {
          text: 'Empty',
          className: 'btn-sm btn-danger',
          action: function(e, dt, node, config) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                location.href = '<?= url('master_information/supplier/truncate') ?>';
              }
            })
          }
        },
        {
          text: 'Import',
          className: 'btn-sm',
          action: function(e, dt, node, config) {
            $('#modal-import').modal('show');
          }
        },
        {
          text: '<?= lang('add_data') ?>',
          className: 'btn-sm',
          action: function(e, dt, node, config) {
            location.href = '<?= url('master_information/supplier/add') ?>'
          }
        },
      ]
    });
    $('#example2 tbody').on('click', 'td.details-control', function() {
      var tr = $(this).closest('tr');
      var row = table.row(tr);

      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
      } else {
        // Open this row
        row.child(format(row.data())).show();
        tr.addClass('shown');
      }
    });
    // Handle click on "Expand All" button
    $('#btn-show-all-children').on('click', function() {
      // Enumerate all rows
      table.rows().every(function() {
        // If row has details collapsed
        if (!this.child.isShown()) {
          // Open this row
          this.child(format(this.data())).show();
          $(this.node()).addClass('shown');
        }
      });
    });
    // Handle click on "Collapse All" button
    $('#btn-hide-all-children').on('click', function() {
      // Enumerate all rows
      table.rows().every(function() {
        // If row has details expanded
        if (this.child.isShown()) {
          // Collapse row details
          this.child.hide();
          $(this.node()).removeClass('shown');
        }
      });
    });
  });
</script>