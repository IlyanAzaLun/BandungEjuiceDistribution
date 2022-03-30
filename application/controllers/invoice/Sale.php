<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Invoice_controller.php';
class Sale extends Invoice_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Sale (Penjualan)';
		$this->page_data['page']->menu = 'Sale';
	}
	public function index()
	{
		$this->list();
	}

	public function list()
	{
		ifPermissions('sale_list');
		$this->page_data['title'] = 'sale_list';
		$this->page_data['page']->submenu = 'sale_list';
		$this->load->view('invoice/sale/list', $this->page_data);
	}

	public function create()
	{
		ifPermissions('sale_create');
		$this->page_data['title'] = 'sale_create';
		$this->page_data['page']->submenu = 'sale_create';
		$this->load->view('invoice/sale/create', $this->page_data);
	}
}