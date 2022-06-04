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
            <table id="example2" class="table table-sm table-bordered table-hover" style="font-size: 12px;">
              <thead>
                <tr>
                  <th>No.</th>
                  <th><?= lang('item_code') ?></th>
                  <th><?= lang('item_name') ?></th>
                  <th><?= lang('category_item') ?></th>
                  <th><?= lang('item_quantity') ?></th>
                  <th><?= lang('broken') ?></th>
                  <th><?= lang('unit') ?></th>
                  <th><?= lang('item_capital_price') ?></th>
                  <th><?= lang('item_selling_price') ?></th>
                  <th><?= lang('note') ?></th>
                  <th><?= lang('option') ?></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <th>No.</th>
                  <th><?= lang('item_code') ?></th>
                  <th><?= lang('item_name') ?></th>
                  <th><?= lang('category_item') ?></th>
                  <th><?= lang('item_quantity') ?></th>
                  <th><?= lang('broken') ?></th>
                  <th><?= lang('unit') ?></th>
                  <th><?= lang('item_capital_price') ?></th>
                  <th><?= lang('item_selling_price') ?></th>
                  <th><?= lang('note') ?></th>
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
    $("#example2").DataTable({

      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
                <'row'<'col-12'tr>>
                <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,        
			lengthChange: true,
      lengthMenu: [[10, 25, 50, 100, 200, <?=$this->db->count_all('items')?>], [10, 25, 50, 100, 200, "All"]],
      ajax: {
        "url": "<?php echo url('items/serverside_datatables_data_items') ?>",
        "type": "POST",
      },
      columns: [{
          data: "id"
        },
        {
          data: "item_code",
          orderable: false
        },
        {
          data: "item_name",
          render: function(data, type, row) {
            let html = `<span class="float-right badge badge-danger">
                          <?= lang('is_inactive') ?>
                        </span>`;
            return `${data} ${(row['is_active']=="0")?html:''}`
          }
        },
        {
          data: "category"
        },
        {
          data: 'item_quantity',
          orderable: false,
          render: function(data, type, row) {
            return `${row['item_quantity']}`;
          }
        },
        {
          data: 'item_broken',
          orderable: false,
          render: function(data, type, row) {
            return `${data}`;
          }
        },
        {
          data: 'item_unit',
          orderable: false,
          render: function(data, type, row) {
            return `${data}`;
          }
        },
        {
          data: "item_capital_price",
          <?php if(!hasPermissions('items_edit')):?>
          visible: false,
          <?php endif ?>
          orderable: false,
          render: function(data, type, row) {
            return (data) ? currency(data) : 0
          }
        },
        {
          data: "item_selling_price",
          orderable: false,
          render: function(data, type, row) {
            return (data) ? currency(data) : 0
          }
        },
        {
          data: "note"
        },
        {
          data: "id",
          orderable: false,
          render: function(data, type, row) {
            return `
                <div class="btn-group d-flex justify-content-center">
                <a target="_blank" href="<?= url('items') ?>/edit?id=${row['id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Edit items"><i class="fa fa-tw fa-edit text-primary"></i></a>
                <a target="_blank" href="<?= url('items') ?>/info?id=${row['item_code']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="History items"><i class="fa fa-tw fa-history text-primary"></i></a>
                <a target="_blank" href="<?= url('items') ?>/info_transaction?id=${row['item_code']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="History transaction items"><i class="fa fa-tw fa-layer-group text-primary"></i></a>
                <?php if(hasPermissions('list_fifo')):?>
                <a target="_blank" href="<?= url('items') ?>/info_fifo?id=${row['item_code']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Fifo items"><i class="fa fa-tw fa-book text-primary"></i></a>
                <?php endif;?>
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
                location.href = '<?= url('items/truncate') ?>';
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
          text: 'Column visibility',
          extend: 'colvis',
          className: 'btn-sm'
        },
        {
          text: '<?= lang('add_data') ?>',
          className: 'btn-sm',
          action: function(e, dt, node, config) {
            location.href = '<?= url('items/add') ?>'
          }
        },
      ]
    });
  });
</script>