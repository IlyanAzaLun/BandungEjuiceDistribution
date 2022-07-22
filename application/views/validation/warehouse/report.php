<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

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
          <li class="breadcrumb-item active"><?php echo lang('page_sale') ?></li>
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
              <?php if (hasPermissions('order_create')) : ?>
                <a href="<?php echo url('invoice/order/create') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('order_create') ?></a>
              <?php endif ?>
            </div>

          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <?php echo form_open('invoice/order/list', ['method' => 'GET', 'autocomplete' => 'off']); ?>
            <div class="row">
              <div class="col-10">
                <div class="row">
                  <div class="col-md-3 col-sm-7">
                    <div class="input-group">
                      <input class="form-control" type="text" id="min" name="min">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-8"></div>
                </div>
              </div>
              <div class="col-2">
              <?php if (hasPermissions('order_create')) : ?>
                <!-- EMPTY -->
              <?php endif ?>
              </div>
            </div>
            <?php echo form_close(); ?>
            <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 12px;">
              <thead>
                <tr>
                  <th width="1%">no.</th>
                  <th width="7"><?= lang('invoice_code') ?></th>
                  <th><?= lang('customer_code') ?></th>
                  <th width="15%"><?= lang('store_name') ?></th>
                  <th width="12%"><?= lang('created_at') ?></th>
                  <th width="45%"><?= lang('note') ?></th>
                  <th width="12%"><?= lang('created_by') ?></th>
                  <th><?= lang('updated_by') ?></th>
                  <th width="7%"><?= lang('option') ?></th>
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
    var startdate;
    var enddate;
    //Date range picker
    $('#min').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY'
      }
    });
    var groupColumn = 3;
    $('.ui-buttonset').draggable();
    var table = $("#example2").DataTable({

      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
            <'row'<'col-12'tr>>
            <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      colReorder: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      order: [[ 4, "desc" ]],
      ajax: {
        "url": "<?php echo url('invoice/order/serverside_datatables_data_order_is_created') ?>",
        "type": "POST",
        "data": function(d) {
          d.startDate = startdate;
          d.finalDate = enddate;
        }
      },
      fnInitComplete: function(oSettings, json){
        $('.confirmation').on('click', function(){
            let id = $(this).data('id');
            $('#modal-confirmation-order').on('shown.bs.modal', function(){
                $(this).find('input#id').val(id);
            })
        })
      },      
      drawCallback: function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        api.rows( {page:'current'} ).data().each(function(index, i){
            if(index['is_confirmed'] == false){
              $(rows).eq(i).remove();
              $(rows).eq(i).removeClass('bg-lightblue').addClass('bg-primary color-palette');
            }
          })
      },
      columns: [{
          data: "order_code",
          render: function(data, type, row) {
            return row['id']
          }
        },
        {
          data: "order_code",
        },
        {
          data: "customer_code",
          visible: false
        },
        {
          data: "store_name",
          orderable: false,
          render: function(data, type, row) {
            return `${shorttext(data, 12, true)} <span class="float-right"><a target="_blank" href="${location.base}master_information/customer/edit?id=${row['customer_code']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a target="_blank" href="${location.base}master_information/customer/edit?id=${row['customer_code']}">${shorttext(data, 12, true)}</a>`
          }
        },
        {
          data: "created_at",
          render: function(data, type, row){
            return formatDate(data)
          }
        },
        {
          data: "note",
          orderable: false,
          render: function(data, type, row) {
            return shorttext(data, 80, true)
            // return data
          }
        },
        {
          data: "user_order_create_by",
          orderable: false,
          render: function(data, type, row) {
            return `${shorttext(data, 12, true)} <span class="float-right"><a target="_blank" href="${location.base}users/view/${row['user_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${data}</a>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${shorttext(data, 12, true)}</a>`;
          }
        },
        {
          data: "user_order_update_by",
          orderable: false,
          visible: false,
          render: function(data, type, row) {
            return `${shorttext(data, 12, true)} <span class="float-right"><a target="_blank" href="${location.base}users/view/${row['user_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${data}</a>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${shorttext(data, 12, true)}</a>`;
          }
        },
        {
          data: "order_code",
          orderable: false,
          render: function(data, type, row, meta) {
            return `
                <div class="btn-group d-flex justify-content-center">
                  <a target="_blank" href="<?= url('validation/warehouse/is_confirmation?id=')?>${data}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Detail Items Order"><i class="fa fa-fw fa-info text-primary"></i></a>
                  <button disabled class="btn btn-xs btn-danger confirmation" data-id="${data}" data-toggle="modal" data-target="#modal-confirmation-order"><i class="fa fa-fw fa-times"></i></button>
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
            text: 'Column visibility',
            extend: 'colvis',
            className: 'btn-sm'
          }
          <?php endif ?>
        ]
      });
    $('#example2 tbody').on( 'click', 'td:not(.group,[tabindex=0])', function(){
        table.search(table.cell( this ).data()).draw();
        $('input[type="search"]').focus()
    })
    $('#example2 tbody').on( 'click', 'td.group', function () {
        table.search($(this).text()).draw();
        $('input[type="search"]').focus()
    } );
    $('#min').on('apply.daterangepicker', function(ev, picker) {
      startdate = picker.startDate.format('YYYY-MM-DD');
      enddate = picker.endDate.format('YYYY-MM-DD');
      $.fn.dataTableExt.afnFiltering.push(
        function(oSettings, aData, iDataIndex) {
          if (startdate != undefined) {
            var coldate = aData[3].split("/");
            var d = new Date(coldate[2], coldate[1] - 1, coldate[1]);
            var date = moment(d.toISOString());
            date = date.format("YYYY-MM-DD");
            dateMin = startdate.replace(/-/g, "");
            dateMax = enddate.replace(/-/g, "");
            date = date.replace(/-/g, "");
            if (dateMin == "" && date <= dateMax) {
              return true;
            } else if (dateMin == "" && date <= dateMax) {
              return true;
            } else if (dateMin <= date && "" == dateMax) {
              return true;
            } else if (dateMin <= date && date <= dateMax) {
              return true;
            }
            return false;
          }
        });
      table.draw();
      // window.location.replace(`${location.base}invoice/purchase/list?start=${startdate}&final=${enddate}`)
    });
  });
</script>