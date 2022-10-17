<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.structure.min.css">
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.theme.min.css">
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

<?php include viewPath('includes/header'); ?>
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">    <?php echo lang('dashboard');?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?php echo lang('home');?></a></li>
              <li class="breadcrumb-item active"><?php echo lang('dashboard');?> v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?=$num_rows_orderSale?></h3>
                <p><?php echo lang('dashboard_new_orders');?></p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a target="_blank" href="<?=url('invoice/order/list')?>" class="small-box-footer"><?php echo lang('dashboard_more_info');?><i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=$num_rows_sale?><sup style="font-size: 20px"></sup></h3>

                <p><?php echo lang('dashboard_sales_today');?></p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a target="_blank" href="<?=url('invoice/sale/list')?>" class="small-box-footer"><?php echo lang('dashboard_more_info');?><i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=$this->db->get('users')->num_rows()?></h3>

                <p><?php echo lang('dashboard_user_register');?></p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="<?=url('users')?>" class="small-box-footer"><?php echo lang('dashboard_more_info');?><i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?=$num_rows_orderSale_cancelled?></h3>

                <p><?php echo lang('dashboard_order_cancelled');?></p>
              </div>
              <div class="icon">
                <i class="ion ion-close"></i>
              </div>
              <a href="#" class="small-box-footer"><?php echo lang('dashboard_more_info');?><i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- row-grouping -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-search"></i>
                </h3>
              </div>
              <div class="card-body">
                <span>
                  <div class="row" id="custom_graph">
                    <div class="col-5">
                      <span class="form-group">
                        <input type="text" class="form-control" name="date" id="min">
                      </span>
                      
                      <div class="row mt-2">
                        <div class="col-8 form-group">
                          <input type="text" class="form-control" name="user" id="user" placeholder="Select Marketing"  autocomplete="false">
                          <input type="hidden" name="user_id" id="user_id" class="form-control" readonly>
                        </div>
                        
                        <div class="col-4">
                          <button class="btn btn-block btn-primary" id="search"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                        </div>
                      </div>

                    </div>
                  </div>
                </span>
              </div>
            </div>
          </div>
        </div>
        <!-- ./row-grouping -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-5 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  <?=lang('dashboard_sales')?>
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab"><?php echo lang('dashboard_area');?></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab"><?php echo lang('dashboard_donut');?></a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 650px;">
                    <canvas id="revenue-chart-canvas" height="650" style="height: 650px;"></canvas>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 650px;">
                    <canvas id="sales-chart-canvas" height="650" style="height: 650px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-7 connectedSortable">

            <!-- solid sales graph -->
            <div class="card ">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  <?php echo lang('dashboard_sales') ?>
                </h3>
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="height: 300px;"></canvas>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php include viewPath('includes/footer'); ?>

<!-- Jquery ui -->
<script src="<?php echo $url->assets ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script type="module" src="<?php echo $url->assets ?>pages/dashboard/MainDashboard.js"></script>
<script>
  var startdate;
  var enddate;
  $(function() {
    //Date range picker
    $('#min').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 30,
      startDate: moment().startOf('month').format('DD/MM/YYYY H:mm'),
      locale: {
        format: 'DD/MM/YYYY H:mm'
      }
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
      // window.location.replace(`${location.base}invoice/purchase/list?start=${startdate}&final=${enddate}`)
    });
    
  })
</script>