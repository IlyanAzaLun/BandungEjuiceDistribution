<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

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
                        <h3 class="card-title">Information about: <b><?= $item->item_name ?></b></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-sm table-bordered table-hover" style="font-size: 9px;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th><?= lang('created_at') ?></th>
                                    <th><?= lang('invoice_code_reference') ?></th>
                                    <th><?= lang('invoice_reference') ?></th>
                                    <th><?= lang('item_code') ?></th>
                                    <th><?= lang('item_name') ?></th>
                                    <th><?= lang('item_quantity_in') ?></th>
                                    <th><?= lang('item_quantity_out') ?></th>
                                    <th><?= lang('status_transaction') ?></th>
                                    <th><?= lang('item_capital_price') ?></th>
                                    <th><?= lang('item_selling_price') ?></th>
                                    <th><?= lang('item_discount') ?></th>
                                    <th><?= lang('total_price') ?></th>
                                    <th><?= lang('item_description') ?></th>
                                    <th><?= lang('customer') ?></th>
                                    <th><?= lang('created_by') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th><?= lang('created_at') ?></th>
                                    <th><?= lang('invoice_code_reference') ?></th>
                                    <th><?= lang('invoice_reference') ?></th>
                                    <th><?= lang('item_code') ?></th>
                                    <th><?= lang('item_name') ?></th>
                                    <th><?= lang('item_quantity_in') ?></th>
                                    <th><?= lang('item_quantity_out') ?></th>
                                    <th><?= lang('status_transaction') ?></th>
                                    <th><?= lang('item_capital_price') ?></th>
                                    <th><?= lang('item_selling_price') ?></th>
                                    <th><?= lang('item_discount') ?></th>
                                    <th><?= lang('total_price') ?></th>
                                    <th><?= lang('item_description') ?></th>
                                    <th><?= lang('customer') ?></th>
                                    <th><?= lang('created_by') ?></th>
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
        var table = $("#example2").DataTable({

            dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
                <'row'<'col-12'tr>>
                <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            order: [[ 1, "desc" ]],
            ajax: {
                "url": "<?php echo url('items/serverside_datatables_data_items_transaction') ?>",
                "type": "POST",
                "data": {
                    "<?php echo $this->security->get_csrf_token_name(); ?>": $('meta[name=csrf_token_hash]').attr('content'),
                    "id": "<?= $item->item_code ?>"
                }
            },
            rowCallback: function(row, data, index){
                if(data['is_cancelled'] == true){
                    $(row).addClass('bg-danger');
                    $(row).find('a').addClass('text-light');
                }
            },
            columns: [{
                    data: "transaction_id"
                },
                {
                    data: "transaction_created_at"
                },
                {
                    data: "invoice_code_reference",
                    visible: false,
                },
                {
                    data: "invoice_code",
                    render: function(data, type, row) {
                        if (data) {
                            return `<a href="${location.base}invoice/purchase/info?id=${data}">${data}</a>`;
                        }
                        return '';
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
                        return `${(data)?result: ''}`
                    }
                },
                {
                    data: "item_out",
                    render: function(data, type, row) {
                        let result = `${data} ${row['item_unit']}`
                        return `${(data)?result: ''}`
                    }
                },
                {
                    data: "item_status"
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
                    visible: false,
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
                    data: "customer_code",
                    render: function(data, type, row){
                        if(row['supplier_name']){
                            return row['supplier_name'];
                        }else if(row['customer_name']){
                            return row['customer_name'];
                        }else{
                            return data;
                        }
                    }
                },
                {
                    data: "transaction_created_by",
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
            }]
        });
    });
</script>