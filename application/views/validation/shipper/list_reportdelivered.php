<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

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
          <li class="breadcrumb-item active"><?php echo lang('menu_validation') ?></li>
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
                  <th width="1%"></th>
                  <th width="5%">no.</th>
                  <th width="5%"><?= lang('created_at') ?></th>
                  <th width="5%"><?= lang('order_code') ?></th>
                  <th width="13%"><?= lang('store_name') ?></th>
                  <th width="5%"><?= lang('expedition') ?></th>
                  <th width="5%"><?= lang('expedition_services') ?></th>
                  <th width="5%"><?= lang('payment_type') ?></th>
                  <th width="5%"><?= lang('pack_by') ?></th>
                  <th width="5%"><?= lang('pack') ?></th>
                  <th width="5%"><?= lang('receipt_code') ?></th>
                  <th width="20%"><?= lang('note') ?></th>
                  <th width="10%"><?= lang('created_by') ?></th>
                  <th width="10%"><?= lang('updated_by') ?></th>
                  <th width="3%"><?= lang('option') ?></th>
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
        return (`
        ${d.invoice_code}
        `);
    }
    var startdate;
    var enddate;
    //Date range picker
    $('#min').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      startDate: moment().startOf('month').format('DD/MM/YYYY H:mm'),
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
      lengthChange: true,
      lengthMenu: [[10, 25, 50, 100, 200, <?=$this->db->count_all('invoice_selling')?>], [10, 25, 50, 100, 200, "All"]],
      order: [[ 2, "desc" ]],
      ajax: {
        "url": "<?php echo url('validation/shipper/serverside_datatables_data_list_reportdelivered') ?>",
        "type": "POST",
        "data": function(d) {
          d.startDate = startdate;
          d.finalDate = enddate;
        }
      },
      fnInitComplete: function(oSettings, json){
        // FUNCTION AFTER LOAD TABELS
      },      
      drawCallback: function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        api.rows( {page:'current'} ).data().each(function(index, i){
          // DON'T REMOVE OR DELETE FETCH DATA, ONLY MARKING DATA
            if(index['invoice_code'].match(/RET/) != null){
              //$(rows).eq(i).remove(); // DON'T DO THIS
            }
            if(index['receipt_code'] != null){
              $(rows).eq(i).addClass('bg-primary');
            }
          })
      },
      columns: [
        {
          class: 'details-control',
          orderable: false,
          data: null,
          defaultContent: '',
        },{
          data: "invoice_code",
          visible: false,
          render: function(data, type, row) {
            return row['id']
          }
        },{
          data: "created_at"
        },{
          data: "invoice_code",
          visible: false,
          render: function(data, type, row){
            return shorttextfromback(data, 12, true)
          }
        },{
          data: "store_name",
          orderable: false,
          render: function(data, type, row) {
            return `${data} <span class="float-right"><a target="_blank" href="${location.base}master_information/customer/edit?id=${row['customer_code']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a href="${location.base}master_information/customer/edit?id=${row['customer_code']}">${shorttext(data, 12, true)}</a>`
          }
        },{
          data: "expedition",
          render: function(data, type, row) {
            return (data != 0)?data:'';
          }
        },{
          data: "services_expedition",
          render: function(data, type, row) {
            return (data != 0)?data:'';
          }
        },{
          data: "type_payment_shipping",
          render: function(data, type, row) {
            return (data != 0)?data:'';
          }
        },{
          data: "pack_by",
          render: function(data, type, row) {
            return (data != 0)?data:'';
          }
        },{
          data: "pack",
          render: function(data, type, row) {
            return (data != 0)?data:'';
          }
        },{
          data: "receipt_code",
          render: function(data, type, row) {
            return (data != 0)?data:'';
          }
        },{
          data: "note",
          orderable: false,
          render: function(data, type, row) {
            if(row['invoice_code'].match(/RET/) != null){
              return `<span>${shorttext(data, 100, true)}<div class="float-right badge badge-primary"><i class="fa fa-fw fa-undo"></i></div></span>`;
            }
            return shorttext(data, 100, true)
            // return data
          }
        },{
          data: "user_sale_create_by",
          orderable: false,
          render: function(data, type, row) {
            return `${shorttext(data, 12, true)} <span class="float-right"><a target="_blank" href="${location.base}users/view/${row['user_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${data}</a>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${shorttext(data, 12, true)}</a>`;
          }
        },{
          data: "user_sale_update_by",
          orderable: false,
          visible: false,
          render: function(data, type, row) {
            return `${shorttext(data, 12, true)} <span class="float-right"><a target="_blank" href="${location.base}users/view/${row['user_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${data}</a>`;
            return `<a target="_blank" href="${location.base}users/view/${row['user_id']}">${shorttext(data, 12, true)}</a>`;
          }
        },{
          data: "invoice_code",
          orderable: false,
          render: function(data, type, row, meta) {
            return `
                <div class="btn-group d-flex justify-content-center">
                  <a target="_blank" href="<?= url('validation/shipper') ?>/delivered?invoice=${data}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Find Destination"><i class="fa fa-fw fa fa-fw fa-print text-primary"></i></a>
                  <button  class="btn btn-xs btn-primary confirmation" data-id="${data}" data-toggle="modal" data-target="#modal-confirmation-order"><i class="fas fa fa-fw fa-flag-checkered"></i></button>
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
        },{
          text: 'Column visibility',
          extend: 'colvis',
          className: 'btn-sm'
        }
        <?php endif ?>
      ]
    });
    table.on('draw', function(){
      $('.confirmation').on('click', function(){
          let id = $(this).data('id');
          $('#modal-confirmation-order').on('shown.bs.modal', function(){
            $("#note").prop('required',true);
            $("label[for='note']").text('Receipt Code');
            $(this).find('input#id').val(id);
          })
      })
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
    })
    $('#example2 tbody').on( 'click', 'td:not(.group,[tabindex=0])', function(){
        table.search(table.cell( this ).data()).draw();
        $('input[type="search"]').focus()
    })
    $('#example2 tbody').on( 'click', 'td.group', function () {
        table.search($(this).text()).draw();
        $('input[type="search"]').focus()
    } );
    $('#min').on('apply.daterangepicker', function(ev, picker) {
      startdate = picker.startDate.format('YYYY-MM-DD HH:mm');
      enddate = picker.endDate.format('YYYY-MM-DD HH:mm');
      $.fn.dataTableExt.afnFiltering.push(
        function(oSettings, aData, iDataIndex) {
          if (startdate != undefined) {
            var coldate = aData[3].split("/");
            var d = new Date(coldate[2], coldate[1] - 1, coldate[1]);
            var date = moment(d.toISOString());
            date = date.format("YYYY-MM-DD HH:mm");
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