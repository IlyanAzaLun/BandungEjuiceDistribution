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
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->load->view('invoice/order/create', $this->page_data);
		}else{
			echo '<pre>';
			var_dump($this->input->post());
			echo '</pre>';
		}
	}
}