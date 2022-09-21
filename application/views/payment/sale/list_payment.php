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
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <?php echo form_open('invoice/order/list', ['method' => 'GET', 'autocomplete' => 'off']); ?>
            <div class="row">
              <div class="col-10">
                <div class="row">
                  <div class="col-4">
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
              <?php if (hasPermissions('sale_create')) : ?>
                <!-- EMPTY -->
              <?php endif ?>
              </div>
            </div>
            <?php echo form_close(); ?>
            <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 12px;">
              <thead>
                <tr>
                  <th>No.</th>
                  <th><?= lang('invoice_code') ?></th>
                  <th><?= lang('date')?></th>
                  <th><?= lang('created_at')?></th>
                  <th><?= lang('updated_at')?></th>
                  <th><?= lang('date_due')?></th>
                  <th><?= lang('customer_code')?></th>
                  <th><?= lang('grandtotal')?></th>
                  <th><?= lang('payup')?></th>
                  <th><?= lang('leftovers')?></th>
                  <th><?= lang('beneficiary_name')?></th>
                  <th><?= lang('payment_type')?></th>
                  <th><?= lang('bank_id')?></th>
                  <th><?= lang('note')?></th>
                  <th><?= lang('created_by')?></th>
                  <th><?= lang('updated_by')?></th>
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
    var startdate;
    var enddate;
    //Date range picker
    $('#min').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      startDate: moment().startOf('years').format('DD/MM/YYYY H:mm'),
      locale: {
        format: 'DD/MM/YYYY H:mm'
      }
    });
    $('.ui-buttonset').draggable();
    var table = $("#example2").DataTable({
      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
            <'row'R<'col-12'tr>>
            <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      colReorder: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      aLengthMenu: [
        [5, 50, 100, 200, <?=$this->db->get('invoice_payment')->num_rows()?>],
        [5, 50, 100, 200, "All"]
      ],
      order: [[ 1, "desc" ]],
      ajax: {
        "url": "<?php echo url('invoice/sales/payment/serverside_datatables_data_payment_sale') ?>",
        "type": "POST",
        "data": function(d) {
          d.startDate = startdate;
          d.finalDate = enddate;
        }
      },
      drawCallback: function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        let regex = /RET/;
        api.rows( {page:'current'} ).data().each(function(index, i){
          if(index['invoice_code']?index['invoice_code'].match(regex) != null:false){
            $(rows).eq(i).remove();
          }
        })
        api.rows( {page:'current'} ).data().each(function(index, i){
          if(index['is_cancelled'] == 1){
            $(rows).eq(i).addClass('bg-danger');
            <?php if(!hasPermissions('example')):?>
              $(rows).eq(i).remove();
            <?php endif;?>
          }
        })
      },
      columns: [
        {
          visible: false,
          data: "id"
        },{
          data: "invoice_code"
        },{
          data: "payment_date_at",
          render: function(data, type, row){
            return data?formatDate(data):''
          }
        },{
          data: "created_at",
          visible: false,
          render: function(data, type, row){
            return data?formatDate(data):'';
          }
        },{
          data: "updated_at",
          visible: false,
          render: function(data, type, row){
            return data?formatDate(data):'';
          }
        },{
          data: "date_start",
          render: function(data, type, row){
            return `${formatDate(row['date_start'], false)}~${formatDate(row['date_due'], false)}`
          }
        },{
          data: "customer_code",
          render: function (data, type, row) {
            let owner = row['owner_name']?` / ${row['owner_name']}`:'';
            return `${row['store_name']}${owner}`
          }
        },{
          data: "grand_total",
          render: function(data, type, row){
            return currency(data)
          }
        },{
          data: "payup",
          render: function(data, type, row){
            return currency(data)
          }
        },{
          data: "leftovers",
          render: function(data, type, row){
            if((currencyToNum(row['grand_total']) - currencyToNum(row['payup'])) < 0){
              return `<p class="text-warning">${currency(Math.abs(currencyToNum(row['grand_total']) - currencyToNum(row['payup'])))}</p>`;
            }
            return currency(currencyToNum(row['grand_total']) - currencyToNum(row['payup']));
          }
        },{
          data: "name",
          render: function(data, type, row){
            let child = row['own_by']!=''?' / '+row['own_by']:'';
            return `${data+child}`
          }
        },{
          visible: false,
          data: "payment_type"
        },{
          visible: false,
          data: "bank_id",
        },{
          data: "description"
        },{
          data: "user_created_by",
          render: function(data, type, row){
            return `${shorttext(data, 5, true)} <span class="float-right"><a href="${location.base}users/view/${row['user_created_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;

          }
        },{
          data: "user_updated_by",
          visible: false,
          render: function(data, type, row){
            if(data){
              return `${shorttext(data, 5, true)} <span class="float-right"><a href="${location.base}users/view/${row['user_updated_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;

            }
            return '';
          }
        },{
          data: "id",
          orderable: false,
          render: function(data, type, row) {
            return `
                <div class="btn-group d-flex justify-content-center">
                  <a href="<?= url('invoice/sales/payment/history') ?>?invoice_code=${row['invoice_code']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Edit items"><i class="fa fa-fw fa-history text-primary"></i></a>
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
    $('#example2 tbody').on( 'click', 'td:not(.group,[tabindex=0], :nth-last-child(1))', function(){
        table.search(table.cell( this ).data()).draw();
        $('input[type="search"]').focus()
        console.log($(this))
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
    new $.fn.dataTable.ColReorder(table);
  });
</script>