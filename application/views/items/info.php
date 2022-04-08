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
                                    <th><?= lang('updated_at') ?></th>
                                    <th><?= lang('invoice_reference') ?></th>
                                    <th><?= lang('item_code') ?></th>
                                    <th><?= lang('item_name') ?></th>
                                    <th><?= lang('item_quantity') ?></th>
                                    <th><?= lang('item_order_quantity') ?></th>
                                    <th><?= lang('status_transaction') ?></th>
                                    <th><?= lang('item_capital_price') ?></th>
                                    <th><?= lang('item_selling_price') ?></th>
                                    <th><?= lang('item_discount') ?></th>
                                    <th><?= lang('total_price') ?></th>
                                    <th><?= lang('created_by') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th><?= lang('created_at') ?></th>
                                    <th><?= lang('updated_at') ?></th>
                                    <th><?= lang('invoice_reference') ?></th>
                                    <th><?= lang('item_code') ?></th>
                                    <th><?= lang('item_name') ?></th>
                                    <th><?= lang('item_quantity') ?></th>
                                    <th><?= lang('item_order_quantity') ?></th>
                                    <th><?= lang('status_transaction') ?></th>
                                    <th><?= lang('item_capital_price') ?></th>
                                    <th><?= lang('item_selling_price') ?></th>
                                    <th><?= lang('item_discount') ?></th>
                                    <th><?= lang('total_price') ?></th>
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
        $("#example2").DataTable({

            dom: `<'row'<'col-10'<'row'<'col-3'f><'col-9'B>>><'col-2'<'float-right'l>>>
                <'row'<'col-12'tr>>
                <'row'<'col-5 col-xs-12'i><'col-7 col-xs-12'p>>`,
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                "url": "<?php echo url('items/serverside_datatables_data_items_information') ?>",
                "type": "POST",
                "data": {
                    "<?php echo $this->security->get_csrf_token_name(); ?>": $('meta[name=csrf_token_hash]').attr('content'),
                    "id": "<?= $item->item_code ?>"
                }
            },
            columns: [{
                    data: "id"
                },
                {
                    data: "created_at"
                },
                {
                    data: "updated_at"
                },
                {
                    data: "invoice_reference",
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
                },
                {
                    data: "item_quantity",
                    render: function(data, type, row) {
                        return `${data} (${row['item_unit']})`
                    }
                },
                {
                    data: "item_order_quantity",
                    render: function(data, type, row) {
                        return `${data} (${row['item_unit']})`
                    }
                },
                {
                    data: "status_transaction",
                    render: function(data, type, row) {
                        return `${data} ${row['item_order_quantity']} ${row['status_type']}`
                    }
                },
                {
                    data: "item_capital_price",
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
                    render: function(data, type, row) {
                        return data ? currency(data) : 0;
                    }
                },
                {
                    data: "total_price",
                    render: function(data, type, row) {
                        return data ? currency(data) : 0;
                    }
                },
                {
                    data: "created_by",
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
            }]
        });
    });
</script>