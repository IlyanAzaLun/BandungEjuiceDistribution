<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.structure.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.theme.min.css">

<?php include viewPath('includes/header'); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('sale') ?></h1>
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

  <!-- Default card -->
  <div class="card">
    <div class="card-header with-border">
      <h3 class="card-title">Export <?php echo lang('sale') ?></h3>
    </div>
    <div class="card-body">
      <?php echo validation_errors();?>
      <?php echo form_open('master_information/report/sale_profit', ['method' => 'POST', 'autocomplete' => 'off']); ?>

        <div class="row">
          <div class="col-4">
            <input type="hidden" name="params" id="params" value="sale">
            <div class="input-group">
                <input class="form-control" type="text" id="min" name="min">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
            </div>
          </div>
          <div class="col-4">
            <button class="btn btn-lg btn-info" type="submit"> <i class="fa fa-download"></i> <?php echo lang('report_generate_message') ?></button>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-2">
              <label for="users">Users</label>
              <div class="input-group">
                  <input type="text" name="users" id="users" class="form-control" placeholder="<?= lang('select_group_by') ?>" autocomplete="false">
                  <input type="text" name="user_id" id="user_id" class="form-control">
              </div>
          </div>
          <div class="col-2">
              <label for="customer">Customer</label>
              <div class="input-group">
                  <input type="text" name="customer" id="customer" class="form-control" placeholder="<?= lang('select_group_by') ?>" autocomplete="false">
                  <input type="text" name="customer_code" id="customer_code" class="form-control">
              </div>
          </div>
          <div class="col-3">
              <div class="row">
                <div class="col-7">
                    <label for="group_by">Group By</label>
                    <div class="input-group">
                        <select class="form-control" name="group_by" id="group_by">
                            <option value="" selected><?=lang('select_group_by')?></option>
                            <option value="daily"><?=lang('daily')?></option>
                            <option value="daily_by_customer"><?=lang('daily_by_customer')?></option>
                            <option value="daily_by_user"><?=lang('daily_by_user')?></option>
                            <option value="monthly"><?=lang('monthly')?></option>
                            <option value="monthly_by_customer"><?=lang('monthly_by_customer')?></option>
                            <option value="monthly_by_user"><?=lang('monthly_by_user')?></option>
                        </select>
                    </div>
                </div>
                <div class="col-5">
                    <label for="">&nbsp;</label>
                    <div class="input-group">
                        <button class="btn btn-lg btn-default" id="sync" type="button"><i class="fa fa-fw fa-sync"></i>&nbsp;Sync</button>
                    </div>
                </div>
              </div>
          </div>
        </div>
      <?php echo form_close(); ?>
        <table id="example2" class="table table-bordered table-hover table-sm" style="font-size: 12px;">
            <thead>
                <tr>
                    <th><?=lang('created_at')?></th>
                    <th><?=lang('updated_at')?></th>
                    <th><?=lang('invoice_code')?></th>
                    <th><?=lang('customer_code')?></th>
                    <th><?=lang('store_name')?></th>
                    <th><?=lang('item_capital_price')?></th>
                    <th><?=lang('item_shadow_selling_price')?></th>
                    <th><?=lang('item_selling_price')?></th>
                    <th>selling price</th>
                    <th><?=lang('profit')?></th>
                    <th><?=lang('profit_pesudo')?></th>
                    <th>actually profit</th>
                    <th>calc</th>
                    <th><?=lang('name')?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->

  </div>
  <!-- /.card -->

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>

<!-- Jquery ui -->
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="module" src="<?php echo $url->assets ?>pages/report/sale/mainSaleProfit.js"></script>
<script>
    $(function() {
        var startdate;
        var enddate;
        var users;
        var customer;
        var group_by;
        //Date range picker
        $('#min').daterangepicker({
            timePicker: false,
            timePicker24Hour: true,
            timePickerIncrement: 30,
            startDate: moment().startOf('month').format('DD/MM/YYYY H:mm'),
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $('.ui-buttonset').draggable();
        var table = $("#example2").DataTable({
            dom: `<'row'<'col-10'<'row'<'col-12'B>>><'col-2'<'float-right'l>>>
                <'row'R<'col-12'tr>>
                <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
            processing: true,
            colReorder: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            aLengthMenu: [
                [5, 50, 100, 200, <?=$this->db->like('invoice_code', 'INV/SALE/', 'after')->group_by('invoice_code')->get('invoice_transaction_list_item')->num_rows()?>],
                [5, 50, 100, 200, "All"]
            ],
            ajax: {
                "url": "<?php echo url('master_information/report/serverside_datatables_data_sale_items_profit') ?>",
                "type": "POST",
                "data": function(d) {
                    d.startDate = startdate;
                    d.finalDate = enddate;
                    d.users = users;
                    d.customer = customer;
                    d.group_by = group_by;
                }
            },
            columns: [{
                data: "created_at",
            },{
                data: "updated_at",
            },{
                data: "invoice_code",
            },{
                data: "customer_code",
            },{
                data: "store_name",
            },{
                data: "item_capital_price",
                render: function(data, type, row){
                    return data?currency(data):0;
                }
            },{
                data: "pseudo_price",
                className: "bg-warning",
                render: function(data, type, row){
                    return data?currency(data):0;
                }
            },{
                data: "grand_total",
                className: "bg-danger",
                render: function(data, type, row){
                    return data?currency(row['grand_total']):0;
                }
            },{
                data: "item_selling_price",
                render: function(data, type, row){
                    return data?currency(data):0;
                }
            },{
                data: "profit",
                render: function(data, type, row){
                    return data?currency(data):0;
                }
            },{
                data: "pseudo_price",
                className: "bg-warning",
                render: function(data, type, row){
                    return data?currency(row['item_selling_price'] - row['pseudo_price'] - currencyToNum(row['discounts']) + currencyToNum(row['shipping_cost'])):0;
                }
            },{
                data: "grand_total",
                className: "bg-danger",
                render: function(data, type, row){
                    return data?currency(row['grand_total'] - row['item_capital_price']):0;
                }
            },{
                data: "calc",
                className: "bg-primary",
                render: function(data, type, row){
                    return currency((row['grand_total'] - row['item_capital_price']) - row['calc']);
                }
            },{
                data: "name",
                render: function(data, type, row){
                    return (row['is_have_name']!=row['name'] && row['is_have_name']!=null)?row['is_have_name']:row['name']
                }
            }],
            buttons: [{
                text: 'Export',
                extend: 'excelHtml5',
                className: 'btn-sm',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                }},
                {
                    text: 'Column visibility',
                    extend: 'colvis',
                    className: 'btn-sm'
                }
            ]
        });
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
        });
        $('#users, #customer, #group_by').on('focusout', function(){
            users = $('#user_id').val();
            customer = $('#customer_code').val();
            group_by = $('#group_by').val();
            $.fn.dataTableExt.afnFiltering.push(
                function(oSettings, aData, iDataIndex) {
                    if($('#users').val() !== '' || $('#customer').val() !== '' || group_by !== null){
                        return true;
                    }
                    return false;
                });
                table.draw();
        });
        $('#sync').on('click', function(){
            table.draw();
        });
    new $.fn.dataTable.ColReorder(table);
  });
</script>