<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Warehouse Validation';
		$this->page_data['page']->menu = 'Warehouse';
	}

    public function index()
    {
        $this->list();
    }

	public function list()
	{
		// ifPermissions('warehouse_order_list');
		var_dump(!hasPermissions('warehouse_order_list'));
		// $this->page_data['title'] = 'order_list';
		// $this->page_data['page']->submenu = 'order_list';
		// $this->load->view('validation/warehouse/list', $this->page_data);
	}
}