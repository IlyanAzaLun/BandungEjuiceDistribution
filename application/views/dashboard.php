<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                    <!-- <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab"><?php echo lang('dashboard_area');?></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab"><?php echo lang('dashboard_donut');?></a>
                    </li> -->
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                    <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>                         
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

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script type="module" src="<?php echo $url->assets ?>pages/dashboard/MainDashboard.js"></script>