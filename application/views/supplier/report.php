<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('supplier') ?></h1>
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
      <h3 class="card-title">Export <?php echo lang('supplier') ?></h3>

    </div>

    <div class="card-body">

    	<a href="<?php echo url('master_information/supplier/report_download') ?>" class="btn btn-lg btn-info"> <i class="fa fa-download"></i> &nbsp;&nbsp;&nbsp; <?php echo lang('report_generate_message') ?></a>

    </div>
    <!-- /.card-body -->

  </div>
  <!-- /.card -->

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>

