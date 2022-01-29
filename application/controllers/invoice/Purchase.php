<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'controllers/invoice/Invoice_controller.php';
class Purchase extends Invoice_controller {

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Purchase (Pembelian)';
		$this->page_data['page']->menu = 'Purchase';
	}
	public function index()
	{
		$this->view();
	}
	
    public function view()
	{
		ifPermissions('purchase_list');
		$this->page_data['title'] = 'purchase_list';
		$this->page_data['page']->submenu = 'view';
		$this->load->view('invoice/purchase/view', $this->page_data);
	}

	public function create()
	{
		ifPermissions('purchase_create');
		$this->page_data['title'] = 'purchase_create';
		$this->page_data['page']->submenu = 'create';
		$this->load->view('invoice/purchase/create', $this->page_data);
	}

}

/* End of file Purchasing.php */
/* Abstract file Location: ./application/controllers/Invoice.php */
/* Location: ./application/controllers/invoices/Purchasing.php */