<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function index()
	{
		$data['is_cancelled'] = true;
		$this->page_data['num_rows_orderSale']=$this->order_model->get_num_rows_order_sale();
		$this->page_data['num_rows_orderSale_cancelled']=$this->order_model->get_num_rows_order_sale($data);
		$this->page_data['num_rows_sale']=$this->sale_model->get_num_rows_sale();
		$this->load->view('dashboard', $this->page_data);
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */