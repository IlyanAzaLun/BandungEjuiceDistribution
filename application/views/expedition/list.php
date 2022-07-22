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
              <?php if (hasPermissions('expedition_create')) : ?>
                <a href="<?php echo url('master_information/expedition/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('expedition_add') ?></a>
              <?php endif ?>
            </div>

          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 12px;">
              <thead>
                <tr>
                  <th>no.</th>
                  <th><?= lang('created_at') ?></th>
                  <th><?= lang('updated_at') ?></th>
                  <th><?= lang('expedition_name') ?></th>
                  <th><?= lang('expedition_services') ?></th>
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
    var startdate;
    var enddate;
    var table = $("#example2").DataTable({

      dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
            <'row'<'col-12'tr>>
            <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
      processing: true,
      colReorder: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      order: [[ 1, "desc" ]],
      ajax: {
        "url": "<?php echo url('master_information/expedition/serverside_datatables_data_expedition') ?>",
        "type": "POST",
      },
      columns: [{
          data: "expedition_id",
          render: function(data, type, row) {
            return data
          }
        },
        {
          data: "created_at"
        },
        {
          data: "updated_at"
        },
        {
          data: "expedition_name"
        },
        {
          data: "services_expedition"
        },
        {
          data: "name",
          render: function(data, type, row){
              return `${data} <span class="float-right"><a href="${location.base}users/view/${row['user_id']}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Information purchasing"><i class="fa fa-fw fa-eye text-primary"></i></a></span>`;
          }
        },
        {
          data: "expedition_id",
          orderable: false,
          render: function(data, type, row, meta) {
            let html  = `
                  <a href="<?= url('master_information/expedition') ?>/edit?id=${data}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="Edit Expedition"><i class="fa fa-fw fa-edit text-primary"></i></a>
                  <button class="btn btn-xs btn-default" data-toggle="modal" data-target="#expeditionModelDelete" data-toggle="tooltip" data-placement="top" title="Delete Expedition"><i class="fa fa-fw fa-ban text-primary"></i></button>
                  `;
            return `<div class="btn-group d-flex justify-content-center">${html}</div>`;
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
    $('#example2 tbody').on( 'click', 'td:not(:eq(-1))', function(){
        table.search(table.cell( this ).data()).draw();
        $('input[type="search"]').focus()
    })
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
      // window.location.replace(`${location.base}master_information/purchase/list?start=${startdate}&final=${enddate}`)
    });

  });
</script>