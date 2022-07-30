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
          <li class="breadcrumb-item active"><?php echo lang('customer') ?></li>
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
                  <th width="2%"></th>
                  <th width="2%">No.</th>
                  <th width="20%"><?= lang('customer_code') ?></th>
                  <th width="20%"><?= lang('store_name') ?></th>
                  <th width="20%"><?= lang('customer_owner') ?></th>
                  <th width="30%"><?= lang('note') ?></th>
                  <th width="2%"><?= lang('option') ?></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
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
        <table class="table table-sm table-hover">
            <tr class="bg-primary">
              <th>
                <div class="row">
                  <div class="col-3"><?= lang('address') ?>:</div>  
                  <div class="col-1"><?= lang('village') ?>:</div>  
                  <div class="col-1"><?= lang('sub_district') ?>:</div>  
                  <div class="col-1"><?= lang('city') ?>:</div>  
                  <div class="col-1"><?= lang('province') ?>:</div>  
                  <div class="col-1"><?= lang('zip') ?>:</div>  
                  <div class="col-1"><?= lang('contact_phone') ?>:</div>  
                  <div class="col-1"><?= lang('contact_mail') ?>:</div>  
                  <div class="col-1"><?= lang('created_at') ?>:</div>  
                  <div class="col-1"><?= lang('created_by') ?>:</div>  
                </div>
              </th>
            </tr>
          
            <tr class="bg-lightblue">
                <td>
                <div class="row">
                    <div class="col-3"><b>${d.address}</b></div>  
                    <div class="col-1">${d.village}</div>  
                    <div class="col-1">${d.sub_district}</div>  
                    <div class="col-1">${d.city}</div>  
                    <div class="col-1">${d.province}</div>  
                    <div class="col-1">${d.zip}</div>  
                    <div class="col-1">${d.contact_phone}</div>  
                    <div class="col-1">${d.contact_mail}</div>  
                    <div class="col-1">${d.created_at}</div>  
                    <div class="col-1">${d.created_by}</div>  
                </div>
                </td>
            </tr>
        </table>`;
    }
    var table = $("#example2").DataTable({
      dom: `R<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
              <'row'<'col-12'tr>>
              <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      colReorder: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      lengthChange: true,
      order: [[ 2, "desc" ]],
      lengthMenu: [
        [10, 25, 50, 100, 200, <?=$this->db->get('customer_information')->num_rows()?>], 
        [10, 25, 50, 100, 200, "All"]
        ],
      ajax: {
        "url": "<?php echo url('api/address') ?>",
        "type": "GET",
        "data": {
          "<?php echo $this->security->get_csrf_token_name(); ?>": $('meta[name=csrf_token_hash]').attr('content')
        }
      },
      columns: [
        {
          className: 'details-control',
          orderable: false,
          data: null,
          defaultContent: ''
        },
        {
          data: "id",
          orderable: false
        },
        {
          data: "customer_code",
          orderable: false
        },
        
        {
          data: "store_name",
          orderable: false
        },
        {
          data: "owner_name",
          orderable: false
        },
        {
          data: "note",
          orderable: false
        },
        {
          data: "id",
          orderable: false,
          render: function(data, type, row, meta){
            return `<span class="btn-group d-flex justify-content-center">
                        <a target="_blank" href="${location.base}master_information/address/detail?id=${row['id']}" class="btn btn-sm btn-default" id="detail" data-toggle="tooltip" data-placement="top" title="Open dialog information transaction item"><i class="fas fa-tw fa-info"></i></a>
                    </span>`;
          }
        }
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
          text: '<?= lang('add_data') ?>',
          className: 'btn-sm',
          action: function(e, dt, node, config) {
            location.href = '<?= url('master_information/customer/add') ?>'
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
        console.log(row.data());
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