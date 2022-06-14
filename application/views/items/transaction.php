<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

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
                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="<?= lang('find_customer_name') ?>" autocomplete="false" required>
                        <input type="hidden" name="customer" id="customer" class="form-control">
                      </div>
                  </div>
                  <div class="col-2">
                      <div class="input-group">
                        <input type="text" name="supplier_name" id="supplier_name" class="form-control" placeholder="<?= lang('find_supplier_name') ?>" autocomplete="false" required>
                        <input type="hidden" name="supplier" id="supplier" class="form-control">
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
                    <th>No.</th>
                    <th><?= lang('created_at') ?></th>
                    <th><?= lang('date') ?></th>
                    <th><?= lang('updated_at') ?></th>
                    <th><?= lang('invoice_reference') ?></th>
                    <th><?= lang('customer') ?></th>
                    <th><?= lang('item_code') ?></th>
                    <th><?= lang('item_name') ?></th>
                    <th><?= lang('item_quantity_in') ?></th>
                    <th><?= lang('item_quantity_out') ?></th>
                    <th><?= lang('unit') ?></th>
                    <th><?= lang('status_transaction') ?></th>
                    <th><?= lang('item_quantity') ?></th>
                    <th><?= lang('item_capital_price') ?></th>
                    <th><?= lang('item_selling_price') ?></th>
                    <th><?= lang('item_discount') ?></th>
                    <th><?= lang('total_price') ?></th>
                    <th><?= lang('item_description') ?></th>
                    <th><?= lang('created_by') ?></th>
                    <th><?= lang('updated_by') ?></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th><?= lang('created_at') ?></th>
                        <th><?= lang('date') ?></th>
                        <th><?= lang('updated_at') ?></th>
                        <th><?= lang('invoice_reference') ?></th>
                        <th><?= lang('customer') ?></th>
                        <th><?= lang('item_code') ?></th>
                        <th><?= lang('item_name') ?></th>
                        <th><?= lang('item_quantity_in') ?></th>
                        <th><?= lang('item_quantity_out') ?></th>
                        <th><?= lang('unit') ?></th>
                        <th><?= lang('status_transaction') ?></th>
                        <th><?= lang('item_quantity') ?></th>
                        <th><?= lang('item_capital_price') ?></th>
                        <th><?= lang('item_selling_price') ?></th>
                        <th><?= lang('item_discount') ?></th>
                        <th><?= lang('total_price') ?></th>
                        <th><?= lang('item_description') ?></th>
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
        [10, 25, 50, 100, 200, <?=$this->db->get('invoice_transaction_list_item')->num_rows()?>], 
        [10, 25, 50, 100, 200, "All"]
        ],
        order: [[ 2, "desc" ]],
        ajax: {
        "url": "<?php echo url('items/serverside_datatables_data_items_transaction') ?>",
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
        drawCallback: function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            let regex = /RET/;
            api.rows( {page:'current'} ).data().each(function(index, i){
                if(index['is_cancelled'] == 1){
                $(rows).eq(i).addClass('bg-danger');
                <?php if(!hasPermissions('example')):?>
                    $(rows).eq(i).remove();
                <?php endif;?>
                }
            })
        },
        columns: [{
                data: "transaction_id"
            },
            {
                visible: false,
                data: "transaction_created_at"
            },
            {
                data: "transaction_date_at",
                render: function(data, type, row){
                    return row['transaction_date_at']
                }
            },
            {
                visible: false,
                data: "transaction_updated_at"
            },
            {
                data: "invoice_code",
                render: function(data, type, row) {
                    let url =  '';
                    if(data.match(/ORDER/)){
                        url = 'order';
                    }else if(data.match(/PURCHASE/)){
                        url = 'purchase';
                    }else{
                        url = 'sale';
                    }
                    if (data) {
                        return `<a href="${location.base}invoice/${url}/info?id=${data}">${data}</a>`;
                    }
                    return '';
                }
            },
            {
                data: "customer_code",
                render: function(data, type, row){
                    if(row['supplier_name']){
                        return `${row['supplier_name']}`;
                    }else if(row['customer_name']){
                        return `${row['customer_name']}`;
                    }else{
                        return data;
                    }
                }
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
                data: "item_in",
                render: function(data, type, row) {
                    let result = `${data} ${row['item_unit']}`
                    return data;
                    return `${(data)?result: ''}`
                }
            },
            {
                data: "item_out",
                render: function(data, type, row) {
                    let result = `${data} ${row['item_unit']}`
                    return data;
                    return `${(data)?result: ''}`
                }
            },
            {
                data: "item_unit",
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                data: "item_status"
            },
            {
                data: "item_current_quantity",
                render: function(data, type, row){
                    return parseInt(data)
                }
            },
            {
                data: "item_capital_price",
                visible: false,
                render: function(data, type, row) {
                    return data ? currency(data) : 0;
                }
            },
            {
                data: "item_selling_price",
                render: function(data, type, row) {
                    return data ? currency(data) : 0;
                }
            },
            {
                data: "item_discount",
                visible: false,
                render: function(data, type, row) {
                    return data ? currency(data) : 0;
                }
            },
            {
                data: "total_price",
                visible: false,
                render: function(data, type, row) {
                    return data ? currency(data) : 0;
                }
            },
            {
                data: "item_description",
                visible: false
            },
            {
                data: "transaction_created_by",
                render: function(data, type, row) {
                    return `<a href="${location.base}users/view/${row['user_id']}">${data}</a>`
                }
            },
            {
                data: "transaction_updated_by",
                visible: false,
                render: function(data, type, row) {
                    return `<a href="${location.base}users/view/${row['user_id']}">${data}</a>`
                }
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
    $('#customer_name, #supplier_name').on('change', function(){
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
        $('#customer').val('');
        $('#supplier').val('');
    })
  });
</script>
<script type="module" src="<?php echo $url->assets ?>pages/items/MainItemTransactions.js"></script>