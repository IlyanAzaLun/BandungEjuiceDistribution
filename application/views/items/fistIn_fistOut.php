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
            <?php echo form_open("items/info_transaction?id=$item->item_code", ['method' => 'GET', 'autocomplete' => 'off']); ?>
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
                  <div class="col-2">
                      <div class="input-group">
                          <select name="customer" id="customer" class="form-control">
                              <option value=""><?=lang('customer')?></option>
                              <?php foreach ($customer as $key => $value):?>
                              <option value="<?=$value->customer_code?>"<?=($value->customer_code==get('customer'))?' selected':''?>><?=$value->store_name?></option>
                              <?php endforeach ?>
                          </select>
                      </div>
                  </div>
                  <div class="col-2">
                      <div class="input-group">
                          <select name="supplier" id="supplier" class="form-control">
                              <option value=""><?=lang('supplier')?></option>
                              <?php foreach ($supplier as $key => $value):?>
                              <option value="<?=$value->customer_code?>"<?=($value->customer_code==get('customer'))?' selected':''?>><?=$value->store_name?></option>
                              <?php endforeach ?>
                          </select>
                      </div>
                  </div>
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
                        <th><?= lang('date') ?></th>
                        <th><?= lang('invoice_code') ?></th>
                        <th><?= lang('item_code') ?></th>
                        <th><?= lang('item_name') ?></th>
                        <th><?= lang('item_quantity') ?></th>
                        <th><?= lang('item_discount') ?></th>
                        <th><?= lang('total_price') ?></th>
                        <th><?= lang('created_by') ?></th>
                        <th><?= lang('updated_by') ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th><?= lang('date') ?></th>
                        <th><?= lang('invoice_code') ?></th>
                        <th><?= lang('item_code') ?></th>
                        <th><?= lang('item_name') ?></th>
                        <th><?= lang('item_quantity') ?></th>
                        <th><?= lang('item_discount') ?></th>
                        <th><?= lang('total_price') ?></th>
                        <th><?= lang('created_by') ?></th>
                        <th><?= lang('updated_by') ?></th>
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
    var startdate;
    var enddate;
    var customer;
    var supplier;
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

        dom: `<'row'<'col-12'Q>>
              <'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
              <'row'<'col-12'tr>>
              <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
        processing: true,
        colReorder: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        lengthMenu: [
            [10, 25, 50, 100, 200, <?=$this->db->get('fifo_items')->num_rows()?>], 
            [10, 25, 50, 100, 200, "All"]
        ],
        ajax: {
        "url": "<?php echo url('items/serverside_datatabels_data_items_fifo') ?>",
        "type": "POST",
        "data": function(d) {
            d.startDate = startdate;
            d.finalDate = enddate;
            d.DCustomer = customer;
            d.DSupplier = supplier;
            d.id = "<?= $item->item_code ?>";
            d.getCustomer = "<?= $this->input->get('customer') ?>";
            }
        },
        columns: [
            {
                data: "fifo_date_at",
            },
            {
                data: "invoice_code",
            },
            {
                data: "item_code",
                visible: false,
            },
            {
                data: "item_name",
                visible: false,
            },
            {
                data: "item_quantity",
            },
            {
                data: "item_discount",
                render: function(data, type, row){
                    return currency(data);
                }
            },
            {
                data: "total_price",
                render: function(data, type, row){
                    return currency(data);
                }
            },
            {
                data: "created_by",
            },
            {
                data: "updated_by",
            }
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
    $('#min').on('apply.daterangepicker', function(ev, picker) {
        startdate = picker.startDate.format('YYYY-MM-DD');
        enddate = picker.endDate.format('YYYY-MM-DD');
        $.fn.dataTableExt.afnFiltering.push(
        function(oSettings, aData, iDataIndex) {
            if (startdate != undefined) {
            let coldate = aData[3].split("/");
            let d = new Date(coldate[2], coldate[1] - 1, coldate[1]);
            let date = moment(d.toISOString());
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
    });
    $('#customer, #supplier').on('change', function(){
        customer = $('#customer').val();
        supplier = $('#supplier').val();
        $.fn.dataTableExt.afnFiltering.push(
        function(settings, searchData, index, rowData, counter){
            if(customer == "" || supplier == ""){
                return true;
            } else if(customer || supplier == ""){
                return true;
            } else if(customer == "" || supplier){
                return true;
            } else if(customer || supplier){
                return true;
            } return false;
        });
        table.draw();
    })
  });
</script>