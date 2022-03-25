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
            <?php echo form_open('invoice/purchase/list', ['method' => 'GET', 'autocomplete' => 'off']); ?>
            <div class="row">
              <div class="input-group col-4">
                <input class="form-control" type="text" id="min" name="min">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
              </div>
            </div>
            <?php echo form_close(); ?>
            <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 12px;">
              <thead>
                <tr>
                  <th>no.</th>
                  <th><?= lang('created_at') ?></th>
                  <th><?= lang('updated_at') ?></th>
                  <th><?= lang('invoice_code_reference') ?></th>
                  <th><?= lang('invoice_code') ?></th>
                  <th><?= lang('supplier_name') ?></th>
                  <th><?= lang('total_price') ?></th>
                  <th><?= lang('discount') ?></th>
                  <th><?= lang('shipping_cost') ?></th>
                  <th><?= lang('other_cost') ?></th>
                  <th><?= lang('grandtotal') ?></th>
                  <th><?= lang('payment_type') ?></th>
                  <th><?= lang('status_payment') ?></th>
                  <th><?= lang('date_due_start') ?></th>
                  <th><?= lang('date_due_end') ?></th>
                  <th><?= lang('note') ?></th>
                  <th><?= lang('created_by') ?></th>
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
    $('body').addClass('sidebar-collapse');
    var startdate;
    var enddate;
    //Date range picker
    $('#min').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      locale: {
        format: 'DD/MM/YYYY H:mm'
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
      order: [[ 3, "desc" ]],
      createdRow: function( row, data, dataIndex ) {
        let d = new Date();
        let month = d.getMonth()+1;
        let day = d.getDate();
        let date_current = d.getFullYear() + '-' +
            ((''+month).length<2 ? '0' : '') + month + '-' +
            ((''+day).length<2 ? '0' : '') + day;
        if ( date_current >= data['date_due'].split(' ')[0] && data['status_payment']=='Credit') {
          $(row).addClass('bg-danger')
      }},
      rowCallback: function(row, data, index){
        if(data['status_payment']=='Credit'){
          $(row).find('td:eq(10)').addClass('bg-danger');
        }else if(data['status_payment']=='Payed'){
          $(row).find('td:eq(10)').addClass('bg-success');
      }},
      drawCallback: function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last = null;
        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
            if ( last !== group ) {
                $(rows).eq( i ).before(
                    `<tr class="group"><td class="group" colspan="15"><span class="text-info">${group}</span></td></tr>`
                );
                last = group;
            }
          });
      },
      ajax: {
        "url": "<?php echo url('invoice/purchases/payment/serverside_datatables_data_purchase_payment') ?>",
        "type": "POST",
        // "data": {
        //   "<?php echo $this->security->get_csrf_token_name(); ?>": $('meta[name=csrf_token_hash]').attr('content'),
        //   "startDate": startdate,
        //   "finalDate": enddate
        // }
        "data": function(d) {
          d.startDate = startdate;
          d.finalDate = enddate;
        }
      },
      columns: [{
          data: "invoice_code",
          render: function(data, type, row) {
            return row['id']
          }
        },
        {
          data: "created_at"
        },
        {
          data: "updated_at"
        },
        {
          data: "invoice_code_reference",
          visible: false,
        },
        {
          data: "invoice_code",
          visible: false,
          render: function(data, type, row) {
            let html = '';
            let regex = /RET/;
            if(data.match(regex) != null){
              html += '<span class="float-right"><a class="btn btn-xs btn-default disabled" data-toggle="tooltip" data-placement="top" title="Is Returns"><i class="fa fa-fw fa-undo text-primary"></i></a></span>';
              //<span class="badge badge-danger">RETURNS</span>
            }
            return `${data} ${html}`;
          }
        },
        {
          data: "store_name",
          orderable: false,
          render: function(data, type, row) {
            return `${shorttext(data, 12, true)} <span class="float-right"><a href="${location.base}master_information/supplier/edit?id=${row['supplier_code']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a href="${location.base}master_information/supplier/edit?id=${row['supplier_code']}">${shorttext(data, 12, true)}</a>`
          }
        },
        {
          data: "total_price",
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "discounts",
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "shipping_cost",
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "other_cost",
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "grand_total",
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "payment_type",
          orderable: false,
          render: function(data, type, row) {
            return data
          }
        },
        {
          data: "status_payment",
          orderable: false,
          render: function(data, type, row, meta) {
            return data
          }
        },
        {
          data: "date_start",
          orderable: false,
          visible: false,
          render: function(data, type, row, meta) {
            return data
          }
        },
        {
          data: "date_due",
          orderable: false,
          render: function(data, type, row, meta) {
            let end_date_due = data.split(' ')[0];
            return end_date_due;
          }
        },
        {
          data: "purchase_note",
          visible: false,
          orderable: false,
          render: function(data, type, row) {
            return shorttext(data, 10, true)
            // return data
          }
        },
        {
          data: "user_purchasing_create_by",
          orderable: false,
          render: function(data, type, row) {
            return `${data} <span class="float-right"><a href="${location.base}users/view/${row['user_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a href="${location.base}users/view/${row['user_id']}">${data}</a>`;
            return `<a href="${location.base}users/view/${row['user_id']}">${shorttext(data, 12, true)}</a>`;
          }
        },
        {
          data: "invoice_code",
          orderable: false,
          render: function(data, type, row, meta) {
            let html = ``;
            let regex = /RET/;
            html += `
                <a href="<?= url('invoice/purchase')  ?>/info?id=${data}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-info text-primary"></i></a>
                <a href="<?= url('invoice/purchases') ?>/payment?id=${data}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-money-bill-wave-alt text-primary"></i></a>
            `;
            return `
                <div class="btn-group d-flex justify-content-center">
                ${html}
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
          text: 'Column visibility',
          extend: 'colvis',
          className: 'btn-sm'
        }
      ]
    });
    $('#example2 tbody').on( 'click', 'td:not(.group,[tabindex=0])', function(){
        table.search(table.cell( this ).data()).draw();
        $('input[type="search"]').focus()
        console.log($(this))
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