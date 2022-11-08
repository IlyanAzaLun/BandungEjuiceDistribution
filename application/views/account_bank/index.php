<?php
function dfs($HeadCode, $HeadName, $oResult, $visit, $d)
{
    if ($d == 0 || $d == 1) {
        echo "<li class='jstree-open' id='$HeadCode'>$HeadName";
    }elseif($d == 2){
        echo "<li id='$HeadCode'><a data-id='child' data-code='$HeadCode' >$HeadName</a>";
    }else{
        echo "<li id='$HeadCode'><a data-id='child' data-code='$HeadCode' >$HeadName</a>";
    }

    $p = 0;
    for($a = 1; $a <= count($oResult); $a++){
        if(!$visit[$a]){
            if($HeadCode == $oResult[$a]->PHeadCode){
                $visit[$a] = true;
                if($p == 0){
                    echo '<ul>';
                }
                $p++;
                dfs($oResult[$a]->HeadCode,$oResult[$a]->HeadName,$oResult,$visit,$d+1);
            }
        }
    }
    if($p == 0){
        echo '</li>';
    }else{
        echo '</ul>';
    }
}
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

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
          <li class="breadcrumb-item active"><?php echo lang('pages_account_bank') ?></li>
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
      <div class="col-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">DataTable with minimal features & hover style</h3>
            <div class="card-tools pull-right">
            </div>

          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div id="jstree_demo_div">
                <ul>
                    <?php dfs("0", "COA", $get_account, $visit, 0); ?>
                </ul>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->


      </div>
      <div class="col-6">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <div id="form"></div>
            </div>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<script type="module" src="<?php echo $url->assets ?>pages/account/MainChartOfAccount.js"></script>
