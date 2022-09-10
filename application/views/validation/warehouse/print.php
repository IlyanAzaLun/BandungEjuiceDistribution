<?php
defined('BASEPATH') or exit('No direct script access allowed');?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $page->title ?> | <?php echo $app->site_title ?> </title>
  <!-- Tell the browser to be responsive to screen width -->
  <link rel="stylesheet" href="">
  <link rel="icon" href="<?=url('uploads/company/').setting('company_icon')?>">  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=0.6">

  <meta name="csrf_token_name" content="<?php echo $this->security->get_csrf_token_name(); ?>" />
  <meta name="csrf_token_hash" content="<?php echo $this->security->get_csrf_hash(); ?>" />

  <!-- DataTables -->
  <!-- pace-progress -->
  <link rel="stylesheet" href="<?php echo $url->assets ?>css/adminlte.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $url->assets ?>css/app.css">
</head>
<pre>TERUNTUK: <?=($customer[0]->store_name);?></pre>
<table class="table">
    <?php foreach ($items as $key => $item):?>
    <tr>
        <td><?=$key+1?>.</td>
        <td><?=$item->item_name?></td>
        <td><b><?=$item->item_order_quantity?></b></td>
    </tr>
    <?php endforeach;?>
</table>