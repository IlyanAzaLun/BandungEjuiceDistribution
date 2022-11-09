<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" />


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('journal') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>">Home</a></li>
          <li class="breadcrumb-item active"><?php echo lang('journal') ?></li>
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
      <h3 class="card-title"><?php echo lang('list_all_activities') ?></h3>

      <div class="card-tools pull-right">
        <button type="button" class="btn btn-card-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-card-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>

    </div>
    <div class="card-body">

      <div class="row">
        <div class="col-sm-12">
          <?php echo form_open('journal', ['method' => 'GET', 'autocomplete' => 'off']); ?>
          <div class="row">
            <div class="col-12 col-lg-4">
                <label for="">Journal Date</label>
                <div class="form-group">
                    <input type="date" class="form-control" name="" id="">
                </div>
            </div>
          </div>
          <div id="handsontable"></div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
<?php include viewPath('includes/footer'); ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
<script>
    const data = [
        ['', '', , , ],
        ['', '', , , ],
        ['', '', , , ],
        ['', '', , , ],
        ['', '', , , ],
    ];

    const container = document.querySelector('#handsontable');
    const hot = new Handsontable(container, {
        data: data,
        rowHeaders: true,
        width: '100%',
        height: 450,
        colWidths: [200, 100, 100, 200],
        colHeaders: ["Account","Debit","Credit","Description",],
        columns: [
            {
                type: 'dropdown',
                source: function(query, process){
                    $.ajax({
                        url: location.base+'master_information/accounting/chart_of_account_list',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            query: query
                        },
                        success: function(response){
                            console.log('response', response);
                            process(response.data)
                        }
                    })
                }
            },
            {},
            {},
            {}
        ],
        contextMenu: true,
        multiColumnSorting: true,
        filters: true,
        rowHeaders: true,
        manualRowMove: true,
        stretchH: 'all',
        manualColumnResize: true,
        licenseKey: "non-commercial-and-evaluation",
        afterChange: function(change, source){
            // ajax here
            console.log(JSON.stringify({data: change}))
        }
    });
</script>