<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
  <li class="nav-item">
    <a href="<?php echo url('dashboard') ?>" class="nav-link <?php echo ($page->menu == 'dashboard') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>
        <?php echo lang('dashboard') ?>
      </p>
    </a>
  </li>

  <?php if (hasPermissions('users_list')) : ?>
    <li class="nav-item">
      <a href="<?php echo url('users') ?>" class="nav-link <?php echo ($page->menu == 'users') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user"></i>
        <p>
          <?php echo lang('users') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('activity_log_list')) : ?>
    <li class="nav-item">
      <a href="<?php echo url('activity_logs') ?>" class="nav-link <?php echo ($page->menu == 'activity_logs') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-history"></i>
        <p>
          <?php echo lang('activity_logs') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('roles_list')) : ?>
    <li class="nav-item">
      <a href="<?php echo url('roles') ?>" class="nav-link <?php echo ($page->menu == 'roles') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-lock"></i>
        <p>
          <?php echo lang('manage_roles') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('permissions_list')) : ?>
    <li class="nav-item">
      <a href="<?php echo url('permissions') ?>" class="nav-link <?php echo ($page->menu == 'permissions') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user-lock"></i>
        <p>
          <?php echo lang('manage_permissions') ?>
        </p>
      </a>
    </li>
  <?php endif ?>


  <?php if (hasPermissions('backup_db')) : ?>
    <li class="nav-item">
      <a href="<?php echo url('backup') ?>" class="nav-link <?php echo ($page->menu == 'backup') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-hdd"></i>
        <p>
          <?php echo lang('backup') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('company_settings')) : ?>
    <li class="nav-item has-treeview <?php echo ($page->menu == 'settings') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'settings') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-cog"></i>
        <p>
          <?php echo lang('settings') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('settings/general') ?>" class="nav-link <?php echo ($page->submenu == 'general') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('general_setings') ?> </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo url('settings/company') ?>" class="nav-link <?php echo ($page->submenu == 'company') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('company_setings') ?> </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo url('settings/email_templates') ?>" class="nav-link <?php echo ($page->submenu == 'email_templates') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('manage_email_template') ?></p>
          </a>
        </li>
      </ul>
    </li>
  <?php endif ?>
  <!-- Items -->
  <?php if (hasPermissions('items_list')) : ?>
    <li class="nav-header"><strong> <?php echo lang('menus') . ' ' . lang('master') ?> </strong> &nbsp;
    <li class="nav-item has-treeview <?php echo ($page->menu == 'Items') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'Items') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-database"></i>
        <p>
          <?php echo lang('pages') . ' ' . lang('item') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('items/list') ?>" class="nav-link <?php echo ($page->submenu == 'list') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('item_list') ?> </p>
          </a>
        </li>
        <?php if(hasPermissions('items_add')):?>
        <li class="nav-item">
          <a href="<?php echo url('items/add') ?>" class="nav-link <?php echo ($page->submenu == 'add') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('item_add') ?> </p>
          </a>
        </li>
        <?php endif ?>
      </ul>
    </li>

  <?php endif ?>
  <!-- end Items -->
  <!-- Customer -->
  <?php if (hasPermissions('customer_list')) : ?>
    <!-- <li class="nav-header"><strong>  <?php echo lang('menus') . ' ' . lang('customer') ?> </strong> &nbsp; -->
    <li class="nav-item has-treeview <?php echo ($page->menu == 'customer') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'customer') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-database"></i>
        <p>
          <?php echo lang('pages') . ' ' . lang('customer') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('master_information/customer/list') ?>" class="nav-link <?php echo ($page->submenu == 'list') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('customer_list') ?> </p>
          </a>
        </li>
        <?php if(hasPermissions('customer_add')):?>
        <li class="nav-item">
          <a href="<?php echo url('master_information/customer/add') ?>" class="nav-link <?php echo ($page->submenu == 'add') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('customer_add') ?> </p>
          </a>
        </li>
        <?php endif ?>
      </ul>
    </li>
  <?php endif ?>
  <!-- end Customer -->
  <!-- Supplier -->
  <?php if (hasPermissions('supplier_list')) : ?>
    <!-- <li class="nav-header"><strong>  <?php echo lang('menus') . ' ' . lang('supplier') ?> </strong> &nbsp; -->
    <li class="nav-item has-treeview <?php echo ($page->menu == 'supplier') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'supplier') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-database"></i>
        <p>
          <?php echo lang('pages') . ' ' . lang('supplier') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('master_information/supplier/list') ?>" class="nav-link <?php echo ($page->submenu == 'list') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('supplier_list') ?> </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo url('master_information/supplier/add') ?>" class="nav-link <?php echo ($page->submenu == 'add') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('supplier_add') ?> </p>
          </a>
        </li>
      </ul>
    </li>
  <?php endif ?>
  <!-- end Supplier -->
  <!-- Expedition -->
  <?php if (hasPermissions('expedition_list')) : ?>
    <!-- <li class="nav-header"><strong>  <?php echo lang('menus_expedition')?> </strong> &nbsp; -->
    <li class="nav-item has-treeview <?php echo ($page->menu == 'expedition') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'expedition') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-database"></i>
        <p>
          <?php echo lang('pages_expedition') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('master_information/expedition/list') ?>" class="nav-link <?php echo ($page->submenu == 'expedition_list') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('expedition_list') ?> </p>
          </a>
        </li>
        <?php if(hasPermissions('expedition_create')):?>
        <li class="nav-item">
          <a href="<?php echo url('master_information/expedition/add') ?>" class="nav-link <?php echo ($page->submenu == 'expedition_add') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('expedition_add') ?> </p>
          </a>
        </li>
        <?php endif?>
      </ul>
    </li>
  <?php endif ?>
  <!-- end Expedition -->

  <!-- Account Bank -->
  <?php if (hasPermissions('account_bank_list')) : ?>
    <!-- <li class="nav-header"><strong>  <?php echo lang('menus_account_bank')?> </strong> &nbsp; -->
    <li class="nav-item has-treeview <?php echo ($page->menu == 'account_bank') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'account_bank') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-database"></i>
        <p>
          <?php echo lang('pages_account_bank') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('master_information/account_bank/list') ?>" class="nav-link <?php echo ($page->submenu == 'account_bank_list') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('account_bank_list') ?> </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo url('master_information/account_bank/create') ?>" class="nav-link <?php echo ($page->submenu == 'account_bank_add') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('account_bank_add') ?> </p>
          </a>
        </li>
      </ul>
    </li>
  <?php endif ?>
  <!-- end Account Bank -->
  <!-- Report -->
  <?php if (hasPermissions('download_file')) : ?>
    <!-- <li class="nav-header"><strong>  <?php echo lang('page_report_information')?> </strong> &nbsp; -->
    <li class="nav-item has-treeview <?php echo ($page->menu == 'report') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'report') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-database"></i>
        <p>
          <?php echo lang('pages_report_information') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">

        <!-- Data Master Report -->
        <li class="nav-item has-treeview <?php echo ($page->submenu == 'report_master') ? 'menu-open' : '' ?>">
          <a href="<?php echo url('') ?>" class="nav-link <?php echo ($page->submenu == 'report_master') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>
              <?php echo lang('report_master') ?>
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/items') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_item') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('item_report') ?> </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/customer') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_customer') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('customer_report') ?> </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/supplier') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_supplier') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('supplier_report') ?> </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/expedition') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_expedition') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('expedition_report') ?> </p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/bank') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_bank') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('bank_report') ?> </p>
              </a>
            </li>
          </ul>
        </li>
        <!-- End Data Master Report -->
        
        <!-- Data Purchase -->
        <li class="nav-item has-treeview <?php echo ($page->submenu == 'report_purchase') ? 'menu-open' : '' ?>">
          <a href="<?php echo url('') ?>" class="nav-link <?php echo ($page->submenu == 'report_purchase') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>
              <?php echo lang('report_purchase') ?>
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <!-- Purchase parent -->
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/purchase') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_purchase_list') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('purchase') ?> </p>
              </a>
            </li>
          </ul>
          <!-- Purchase Returns -->
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/purchase_returns') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_purchase_returns_list') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('purchase_returns') ?> </p>
              </a>
            </li>
          </ul>
        </li>
        <!-- End Data Purchase -->
        <!-- Data Sale -->
        <li class="nav-item has-treeview <?php echo ($page->submenu == 'report_sale') ? 'menu-open' : '' ?>">
          <a href="<?php echo url('') ?>" class="nav-link <?php echo ($page->submenu == 'report_sale') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>
              <?php echo lang('report_sale') ?>
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <!-- Sale Parents -->
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/sale') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_sale_list') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('report_sale_items') ?> </p>
              </a>
            </li>
          </ul>
          <!-- Sale Profit -->
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/sale_profit') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_sale_profit') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('report_sale_profit') ?> </p>
              </a>
            </li>
          </ul>
          <!-- Sale Returns -->
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo url('master_information/report/sale_returns') ?>" class="nav-link <?php echo (@$page->submenu_child == 'report_sale_returns_list') ? 'active' : '' ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p> <?php echo lang('sale_returns') ?> </p>
              </a>
            </li>
          </ul>
        </li>
        <!-- End Data Sale -->
      </ul>
    </li>
  <?php endif ?>
  <!-- end Report -->
  
  <!-- Invoice -->
  <?php if (hasPermissions('purchase_list') || hasPermissions('sale_list')) : ?>
  <li class="nav-header"><strong> <?php echo lang('menu_invoice') ?> </strong> &nbsp;
  <!-- Purchase -->
  <?php if (hasPermissions('purchase_list')) : ?>
  <li class="nav-item has-treeview <?php echo ($page->menu == 'Purchase') ? 'menu-open' : '' ?>">
    <a href="#" class="nav-link  <?php echo ($page->menu == 'Purchase') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-shopping-cart"></i>
      <p>
        <?php echo lang('page_purchase') ?>
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo url('invoice/purchase/list') ?>" class="nav-link <?php echo ($page->submenu == 'list_purchase') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('purchase_list') ?> </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo url('invoice/purchases/returns/list') ?>" class="nav-link <?php echo ($page->submenu == 'list_purchase_returns') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('purchase_return_list') ?> </p>
        </a>
      </li>
      <!-- Consignment Purchase -->
      <li class="nav-item has-treeview <?php echo ($page->submenu == 'consignment') ? 'menu-open' : '' ?>">
        <a href="<?php echo url('') ?>" class="nav-link <?php echo ($page->submenu == 'consignment') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p>
            <?php echo lang('consignment') ?>
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?php echo url('invoice/purchases/consignment/') ?>" class="nav-link <?php echo (@$page->submenu_child == 'consignment_report') ? 'active' : '' ?>">
              <i class="far fa-dot-circle nav-icon"></i>
              <p><?php echo lang('consignment_list') ?></p>
            </a>
          </li>
        </ul>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?php echo url('invoice/purchases/consignments/returns/') ?>" class="nav-link <?php echo (@$page->submenu_child == 'consignment_return_report') ? 'active' : '' ?>">
              <i class="far fa-dot-circle nav-icon"></i>
              <p><?php echo lang('consignment_return_list') ?></p>
            </a>
          </li>
        </ul>
      </li>
      <!--  -->
    </ul>
  </li>
  <?php endif ?>
    <!-- END Purchase -->
    <!-- Sale -->
  <?php if (hasPermissions('sale_list')) : ?>
  <li class="nav-item has-treeview <?php echo ($page->menu == 'Sale') ? 'menu-open' : '' ?>">
    <a href="#" class="nav-link  <?php echo ($page->menu == 'Sale') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-shopping-bag"></i>
      <p>
        <?php echo lang('page_sale') ?>
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo url('invoice/order/list') ?>" class="nav-link <?php echo ($page->submenu == 'order_list') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('order_list') ?> </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo url('invoice/sale/list') ?>" class="nav-link <?php echo ($page->submenu == 'sale_list') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('sale_list') ?> </p>
        </a>
      </li>
      <?php if(hasPermissions('sale_return_list')):?>
      <li class="nav-item">
        <a href="<?php echo url('invoice/sales/returns/list') ?>" class="nav-link <?php echo ($page->submenu == 'sale_return_list') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('sale_return_list') ?> </p>
        </a>
      </li>
      <?php endif ?>
    </ul>
  </li>
  <?php endif ?>
  <!-- end Sale -->
  <!-- Payment -->
  <?php if (hasPermissions('payment')) : ?>
  <li class="nav-item has-treeview <?php echo ($page->menu == 'Payment') ? 'menu-open' : '' ?>">
    <a href="#" class="nav-link  <?php echo ($page->menu == 'Payment') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-money-bill-wave"></i>
      <p>
        <?php echo lang('page_payment') ?>
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo url('invoice/purchases/payment') ?>" class="nav-link <?php echo ($page->submenu == 'purchase_payment') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('payment_purchases') ?> </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo url('invoice/sale/payment') ?>" class="nav-link <?php echo ($page->submenu == 'sale_payment') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('payment_sales') ?> </p>
        </a>
      </li>
    </ul>
  </li>
  <?php endif ?>
  <!-- End Payment -->
  <?php endif ?>
  <!-- end Invoice -->
  
  <!-- NOT Invoice -->
  <?php if (hasPermissions('purchase_list') || hasPermissions('sale_list')) : ?>
    <!-- Item IN -->
  <li class="nav-header"><strong> <?php echo lang('menu_notinvoice') ?> </strong> &nbsp;
  <!-- Purchase -->
  <?php if (hasPermissions('purchase_list')) : ?>
  <li class="nav-item has-treeview <?php echo ($page->menu == 'entry_items') ? 'menu-open' : '' ?>">
    <a href="#" class="nav-link  <?php echo ($page->menu == 'entry_items') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-file-download"></i>
      <p>
        <?php echo lang('entry_items') ?>
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo url('invoice/purchase/list_entry') ?>" class="nav-link <?php echo ($page->submenu == 'list_entry') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('list_entry') ?> </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo url('invoice/purchase/entry') ?>" class="nav-link <?php echo ($page->submenu == 'entry_items') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('entry_items') ?> </p>
        </a>
      </li>
    </ul>
  </li>
  <?php endif ?>
    <!-- END Purchase -->
    <!-- Item Out -->
  <?php if (hasPermissions('sale_list')) : ?>
  <li class="nav-item has-treeview <?php echo ($page->menu == 'drop_items') ? 'menu-open' : '' ?>">
    <a href="#" class="nav-link  <?php echo ($page->menu == 'drop_items') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-file-upload"></i>
      <p>
        <?php echo lang('drop_items') ?>
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo url('invoice/sale/list_drop_items') ?>" class="nav-link <?php echo ($page->submenu == 'list_drop') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('list_drop') ?> </p>
        </a>
      </li>
      <?php if(hasPermissions('drop_items')):?>
      <li class="nav-item">
        <a href="<?php echo url('invoice/sale/drop') ?>" class="nav-link <?php echo ($page->submenu == 'drop_items') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('drop_items') ?> </p>
        </a>
      </li>
      <?php endif ?>
    </ul>
  </li>
  <?php endif ?>
  <!-- end Sale -->
  <?php endif ?>
  <!-- end END Invoice -->

    <!-- Validation -->
  <?php if (hasPermissions('warehouse_order_list')) : $total = $this->order_model->count_new_order()?>
  <li class="nav-header"><strong> <?php echo lang('menu_validation') ?> </strong> &nbsp;
    <!-- Warehouse -->
  <li class="nav-item has-treeview <?php echo ($page->menu == 'Warehouse') ? 'menu-open' : '' ?>">
    <a href="#" class="nav-link  <?php echo ($page->menu == 'Warehouse') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-clipboard-check"></i>
      <p>
        <?php echo lang('page_order') ?>
        <i class="right fas fa-angle-left"></i>
        <?php if($total):?>
        <span class="badge badge-info right"><?=$total;?></span>
        <?php endif ?>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="<?php echo url('validation/warehouse/list') ?>" class="nav-link <?php echo ($page->submenu == 'order_list') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('order_list') ?> </p>
          <?php if($total):?>
          <span class="badge badge-info right"><?=$total;?></span>
          <?php endif ?>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo url('validation/warehouse/report') ?>" class="nav-link <?php echo ($page->submenu == 'order_report') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('order_report') ?> </p>
        </a>
      </li>
    </ul>
  </li>
  <?php endif ?>
    <!-- END Warehouse -->
    <!-- Shipping -->
    <?php if (hasPermissions('shipper_transaction_list')) : ?>
  <li class="nav-item has-treeview <?php echo ($page->menu == 'Shipper') ? 'menu-open' : '' ?>">
    <a href="#" class="nav-link  <?php echo ($page->menu == 'Shipper') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-dolly-flatbed"></i>
      <p>
        <?php echo lang('page_shipping') ?>
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview">
      <?php if(hasPermissions('quality_control')):?>
      <li class="nav-item">
        <a href="<?php echo url('validation/shipper/list') ?>" class="nav-link <?php echo ($page->submenu == 'list_quality_control') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('quality_control') ?> </p>
        </a>
      </li>
      <?php endif ?>
      <?php if(hasPermissions('report_packing')):?>
      <li class="nav-item">
        <a href="<?php echo url('validation/shipper/report_packing') ?>" class="nav-link <?php echo ($page->submenu == 'report_packing') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('shipping_report') ?> </p>
        </a>
      </li>
      <?php endif ?>
      
      <?php if(hasPermissions('report_delivered')):?>
      <li class="nav-item">
        <a href="<?php echo url('validation/shipper/report_delivered') ?>" class="nav-link <?php echo ($page->submenu == 'report_delivered') ? 'active' : '' ?>">
          <i class="far fa-circle nav-icon"></i>
          <p> <?php echo lang('delivered_report') ?> </p>
        </a>
      </li>
      <?php endif ?>
    </ul>
  </li>
    <?php endif ?>
    <!-- END Shipping -->
    <!-- END validation -->
<?php if(hasPermissions('example')):?>    
<li class="nav-header text-danger"><strong> <?php echo lang('ci_examples') ?> </strong> &nbsp;
  <span class="right badge badge-primary">New</span>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      <?php echo lang('datatables') ?>
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/ci_examples/basic_datatables') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('basic_datatables') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/ci_examples/serverside_datatables') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('serverside_datatables') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item">
  <a href="<?php echo url('adminlte/ci_examples/form_validation') ?>" class="nav-link">
    <i class="nav-icon fas fa-table"></i>
    <p>
      <?php echo lang('form_validation') ?>
    </p>
  </a>
</li>
<li class="nav-item">
  <a href="<?php echo url('adminlte/ci_examples/file_uploads') ?>" class="nav-link">
    <i class="nav-icon fas fa-upload"></i>
    <p>
      <?php echo lang('file_upload') ?>
    </p>
  </a>
</li>
<li class="nav-item">
  <a href="<?php echo url('adminlte/ci_examples/multi_file_uploads') ?>" class="nav-link">
    <i class="nav-icon fas fa-file-upload"></i>
    <p>
      <?php echo lang('multi_file_upload') ?>
    </p>
  </a>
</li>

<li class="nav-header text-danger"><strong> AdminLTE 3 Pages</strong></li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      <?php echo lang('dashboard') ?>
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/main/dashboard') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('dashboard') ?> v1</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/main/dashboard2') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('dashboard') ?> v2</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/main/dashboard3') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('dashboard') ?> v3</p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item">
  <a href="<?php echo url('adminlte/main/widgets') ?>" class="nav-link">
    <i class="nav-icon fas fa-th"></i>
    <p>
      <?php echo lang('widgets') ?>
    </p>
  </a>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-copy"></i>
    <p>
      <?php echo lang('layout_options') ?>
      <i class="fas fa-angle-left right"></i>
      <span class="badge badge-info right">6</span>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/layout/top_nav') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('top_navigation') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/layout/top_nav_sidebar') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('top_navigation_sidebar') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/layout/boxed') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('boxed') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/layout/fixed_sidebar') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('fixed_sidebar') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/layout/fixed_topnav') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('fixed_navbar') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/layout/fixed_footer') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('fixed_footer') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/layout/collapsed_sidebar') ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('collapsed_sidebar') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-chart-pie"></i>
    <p>
      <?php echo lang('charts') ?>
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/charts/chartjs'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('chartjs') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/charts/flot'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('flot') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/charts/inline'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('inline') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-tree"></i>
    <p>
      <?php echo lang('ui_elements') ?>
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/general'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('general') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/icons'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('icons') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/buttons'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('buttons') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/sliders'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('sliders') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/modals'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('modals_alerts') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/navbar'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('nav_tabs') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/timeline'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('timeline') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/Ui/ribbons'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('ribbons') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-edit"></i>
    <p>
      <?php echo lang('forms') ?>
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/forms/general'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('general_elements') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/forms/advanced'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('advanced_elements') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/forms/editors'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('editors') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/forms/validation'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('validation') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-table"></i>
    <p>
      <?php echo lang('tables') ?>
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/tables/simple'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('simple_tables') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/tables/data'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('datatables') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/tables/jsgrid'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('jsgrid') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-header text-danger"><?php echo lang('examples') ?></li>
<li class="nav-item">
  <a href="<?php echo url('adminlte/main/calendar'); ?>" class="nav-link">
    <i class="nav-icon far fa-calendar-alt"></i>
    <p>
      <?php echo lang('calendar') ?>
      <span class="badge badge-info right">2</span>
    </p>
  </a>
</li>
<li class="nav-item">
  <a href="<?php echo url('adminlte/main/gallery'); ?>" class="nav-link">
    <i class="nav-icon far fa-image"></i>
    <p>
      <?php echo lang('gallery') ?>
    </p>
  </a>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon far fa-envelope"></i>
    <p>
      <?php echo lang('mailbox') ?>
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/mailbox/mailbox'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('inbox') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/mailbox/compose'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('compose') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/mailbox/read_mail'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('read') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-book"></i>
    <p>
      <?php echo lang('pages') ?>
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/invoice'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('invoice') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/profile'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('profile') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/e-commerce'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('ecommerce') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/projects'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('projects') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/project_add'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('project_add') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/project_edit'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('project_edit') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/project_detail'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('project_detail') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/contacts'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('contacts') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon far fa-plus-square"></i>
    <p>
      <?php echo lang('extras') ?>
      <i class="fas fa-angle-left right"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/login'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('extras') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/register'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('register') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/forgot_password'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('forgot_password') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/recover_password'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('recover_password') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/lockscreen'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('lockscreen') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/legacy_user_menu'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('legacy_user_menu') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/language_menu'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('legacy_user_menu') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/error404'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('error_404') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/error500'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('error_500') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/pace'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('pace') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/blank'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('blank_page') ?></p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo url('adminlte/examples/starter'); ?>" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('starter_page') ?></p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-header text-danger"><?php echo lang('MISCELLANEOUS') ?></li>
<li class="nav-item">
  <a href="https://adminlte.io/docs/3.0" class="nav-link">
    <i class="nav-icon fas fa-file"></i>
    <p><?php echo lang('documentation') ?></p>
  </a>
</li>
<li class="nav-header text-danger"><?php echo lang('multi_level_example') ?></li>
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p><?php echo lang('level') ?> 1</p>
  </a>
</li>
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-circle"></i>
    <p>
      <?php echo lang('level') ?> 1
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('level') ?> 2</p>
      </a>
    </li>
    <li class="nav-item has-treeview">
      <a href="#" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>
          <?php echo lang('level') ?> 2
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-dot-circle nav-icon"></i>
            <p><?php echo lang('level') ?> 3</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-dot-circle nav-icon"></i>
            <p><?php echo lang('level') ?> 3</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-dot-circle nav-icon"></i>
            <p><?php echo lang('level') ?> 3</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p><?php echo lang('level') ?> 2</p>
      </a>
    </li>
  </ul>
</li>
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p><?php echo lang('level') ?> 1</p>
  </a>
</li>
<li class="nav-header text-danger"><?php echo lang('labels') ?></li>
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon far fa-circle text-danger"></i>
    <p class="text"><?php echo lang('important') ?></p>
  </a>
</li>
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon far fa-circle text-warning"></i>
    <p><?php echo lang('warning') ?></p>
  </a>
</li>
<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon far fa-circle text-info"></i>
    <p><?php echo lang('informational') ?></p>
  </a>
</li>

<?php endif ?>
<li class="nav-header">MISCELLANEOUS</li>
<li class="nav-item">
  <a href="https://ilyanazalun.github.io/projects/7-inventory-management-project" target="_blank" class="nav-link active bg-danger">
    <i class="nav-icon fas fa-file"></i>
    <p>Documentation</p>
  </a>
</li>

</ul>