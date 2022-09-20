<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.structure.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.theme.min.css">
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
                  <th>no.</th>
                  <th><?= lang('created_at') ?></th>
                  <th><?= lang('updated_at') ?></th>
                  <th><?= lang('invoice_code') ?></th>
                  <th><?= lang('grandtotal') ?></th>
                  <th><?= lang('leftovers') ?></th>
                  <th><?= lang('store_name') ?></th>
                  <th><?= lang('status_payment') ?></th>
                  <th><?= lang('created_by') ?></th>
                  <th><?= lang('updated_by') ?></th>
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
        "url": "<?php echo url('master_information/account_bank/serverside_datatables_data_payment') ?>",
        "type": "POST",
        "data": function(d) {
          d.startDate = startdate;
          d.finalDate = enddate;
          d.id_bank = <?=get('id')?>;
        }
      },
      columns: [
        {
          visible: false,
          data: "id"
        },{
          data: "created_at"
        },{
          visibel:false,
          data: "updated_at"
        },{
          data: "invoice_code"
        },{
          data: "grand_total",
          render: function(data, type, row){
            return currency(data)
          }
        },{
          data: "leftovers",
          render: function(data, type, row){
            return currency(data)
          }
        },{
          data: "store_name",
          render: function(data, type, row){
            return `${row['store_name']} / ${row['owner_name']}`
          }
        },{
          data: "status_payment",
          render: function(data, type, row){
            return data=='1'?'Kredit':'Debet'
          }
        },{
          data: "user_create_name"
        },{
          visible: false,
          data: "user_update_name"
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