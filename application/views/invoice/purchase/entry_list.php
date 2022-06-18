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
          <li class="breadcrumb-item active"><?php echo lang('page_purchase') ?></li>
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
                <a href="<?php echo url('invoice/purchase/entry') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('entry_items') ?></a>
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
                  <th width="1%">no.</th>
                  <th width="12%"><?= lang('created_at') ?></th>
                  <th width="12%"><?= lang('updated_at') ?></th>
                  <th width="9%"><?= lang('invoice_code') ?></th>
                  <th><?= lang('total_price') ?></th>
                  <th><?= lang('discount') ?></th>
                  <th><?= lang('shipping_cost') ?></th>
                  <th><?= lang('other_cost') ?></th>
                  <th width="7%"><?= lang('grandtotal') ?></th>
                  <th><?= lang('payment_type') ?></th>
                  <th><?= lang('status_payment') ?></th>
                  <th><?= lang('note') ?></th>
                  <th width="10%"><?= lang('created_by') ?></th>
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
      aLengthMenu: [
        [5, 50, 100, 200, <?=$this->db->get('invoice_purchasing')->num_rows()?>],
        [5, 50, 100, 200, "All"]
      ],
      order: [[ 1, "desc" ]],
      drawCallback: function ( settings ) {
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          api.rows( {page:'current'} ).data().each(function(index, i){
            if(index['is_cancelled'] == true){
              $(rows).eq(i).removeClass('bg-lightblue').addClass('bg-danger color-palette');
              <?php if(!hasPermissions('backup_db')):?>
              $(rows).eq(i).remove();
              <?php endif;?>
            }
          })
      },
      ajax: {
        "url": "<?php echo url('invoice/purchases/entry/serverside_datatables_data_list_entry_items') ?>",
        "type": "POST",
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
          data: "created_at",
          render: function(data, type, row){
            return data?formatDate(data):data;
          }
        },
        {
          data: "updated_at",
          render: function(data, type, row){
            return data?formatDate(data):data;
          }
        },
        {
          data: "invoice_code",
          render: function(data, type, row) {
            return `${data}`;
          }
        },
        {
          data: "total_price",
          visible: false,
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "discounts",
          visible: false,
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "shipping_cost",
          visible: false,
          render: function(data, type, row) {
            return currency(data)
          }
        },
        {
          data: "other_cost",
          visible: false,
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
          visible: false,
          render: function(data, type, row) {
            return data
          }
        },
        {
          data: "status_payment",
          orderable: false,
          visible: false,
          render: function(data, type, row) {
            return data
          }
        },
        {
          data: "purchase_note",
          orderable: false,
          render: function(data, type, row) {
            return shorttext(data, 80, true)
          }
        },
        {
          data: "user_purchasing_create_by",
          orderable: false,
          render: function(data, type, row) {
            return `${shorttext(data, 12, true)} <span class="float-right"><a href="${location.base}users/view/${row['user_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
            return `<a href="${location.base}users/view/${row['user_id']}">${data}</a>`;
            return `<a href="${location.base}users/view/${row['user_id']}">${shorttext(data, 12, true)}</a>`;
          }
        },
        {
          data: "invoice_code",
          orderable: false,
          render: function(data, type, row, meta) {
            let html = ``;
            let drop = ``;
            html += `
            <a target="_blank" href="<?= url('invoice/purchases/entry')  ?>/edit_entry?id=${data}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Edit purchasing"><i class="fa fa-fw fa-edit text-primary"></i></a>
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

  });
</script>