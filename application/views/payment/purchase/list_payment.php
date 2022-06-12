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
                  <th><?= lang('invoice_code') ?></th>
                  <th><?= lang('date_due')?></th>
                  <th><?= lang('customer_code')?></th>
                  <th><?= lang('grandtotal')?></th>
                  <th><?= lang('payup')?></th>
                  <th><?= lang('leftovers')?></th>
                  <th><?= lang('status_payment')?></th>
                  <th><?= lang('payment_type')?></th>
                  <th><?= lang('bank_id')?></th>
                  <th><?= lang('note')?></th>
                  <th><?= lang('created_at')?></th>
                  <th><?= lang('created_by')?></th>
                  <th><?= lang('updated_at')?></th>
                  <th><?= lang('updated_by')?></th>
                  <th><?= lang('date')?></th>
                  <th><?= lang('option') ?></th>
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
    $("#example2").DataTable({

      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
                <'row'<'col-12'tr>>
                <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,        
			lengthChange: true,
      lengthMenu: [[10, 25, 50, 100, 200, <?=$this->db->count_all('invoice_payment')?>], [10, 25, 50, 100, 200, "All"]],
      ajax: {
        "url": "<?php echo url('invoice/purchases/payment/serverside_datatables_data_payment_purchase') ?>",
        "type": "POST",
      },
      columns: [
        {
          data: "id"
        },{
          data: "invoice_code"
        },{
          data: "date_start",
          render: function(data, type, row){
            return `${formatDate(row['date_start'], false)}~${formatDate(row['date_due'], false)}`
          }
        },{
          data: "customer_code"
        },{
          data: "grand_total"
        },{
          data: "payup"
        },{
          data: "leftovers"
        },{
          data: "status_payment"
        },{
          data: "payment_type"
        },{
          data: "bank_id",
        },{
          data: "description"
        },{
          data: "created_at",
          visible: false,
          render: function(data, type, row){
            return data?formatDate(data):'';
          }
        },{
          data: "user_created_by",
          render: function(data, type, row){
            return `${shorttext(data, 5)} <a href="${row['user_created_id']}" class="btn btn-sm btn-default"><i class="fa fa-tw fa-eye text-primary"></i></a>`
          }
        },{
          data: "updated_at",
          visible: false,
          render: function(data, type, row){
            return data?formatDate(data):'';
          }
        },{
          data: "user_updated_by",
          visible: false,
          render: function(data, type, row){
            if(data){
              return `${shorttext(data, 5)} <a href="${row['user_updated_id']}" class="btn btn-sm btn-default"><i class="fa fa-tw fa-eye text-primary"></i></a>`
            }
            return '';
          }
        },{
          data: "payment_date_at",
          render: function(data, type, row){
            return data?formatDate(data):''
          }
        },{
          data: "id",
          orderable: false,
          render: function(data, type, row) {
            return `
                <div class="btn-group d-flex justify-content-center">
                    <a target="_blank" href="<?= url('items') ?>/edit?id=${row['id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Edit items"><i class="fa fa-tw fa-edit text-primary"></i></a>
                </div>`;
          }
        },
      ],
      buttons: [
        
        <?php if(hasPermissions('backup_db')):?>
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
        <?php endif ?>
      ]
    });
  });
</script>