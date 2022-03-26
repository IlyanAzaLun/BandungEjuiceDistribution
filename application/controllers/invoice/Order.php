<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Invoice_controller.php';
class Order extends Invoice_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Order (Pemesanan)';
		$this->page_data['page']->menu = 'Sale';
	}
	public function index()
	{
		$this->list();
	}

	public function list()
	{
		ifPermissions('order_list');
		$this->page_data['title'] = 'order_list';
		$this->page_data['page']->submenu = 'order_list';
		$this->load->view('invoice/order/list', $this->page_data);
	}

	public function create()
	{
		ifPermissions('order_create');
		$this->page_data['title'] = 'order_create';
		$this->page_data['page']->submenu = 'order_create';
		$this->load->view('invoice/order/create', $this->page_data);
	}
}