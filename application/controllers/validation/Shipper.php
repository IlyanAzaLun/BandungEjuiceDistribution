<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipper extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Shipper';
		$this->page_data['page']->menu = 'Shipper';
	}

    public function index()
    {
        $this->list();
    }

	public function destination()
	{	
		$customer = $this->customer_model->get_information_customer(get('id'));
		$data['customer'] = $customer;
	
		$this->load->library('pdf');
	
        $options = $this->pdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $this->pdf->setOptions($options);


		$this->pdf->setPaper('A6', 'landscape');
		$this->pdf->filename = "$customer->store_name.pdf";
		$this->pdf->load_view('validation/shipper/destination', $data);
	}

	private function data_sales()
	{
		$_invoice_parent_code = str_replace('RET','INV',$this->input->get('id'), $is_replace);
		$_invoice_child__code = str_replace('INV','RET',$this->input->get('id'));
		$this->page_data['_data_item_invoice_parent'] = $this->transaction_item_model->get_transaction_item_by_code_invoice($_invoice_parent_code);
		$this->page_data['_data_item_invoice_child_'] = $this->transaction_item_model->get_transaction_item_by_code_invoice($_invoice_child__code);

		$parent_items_codex = array_column($this->page_data['_data_item_invoice_parent'], 'item_code');
		$parent_items_index = array_column($this->page_data['_data_item_invoice_parent'], 'index_list');
		$childs_items_codex = array_column($this->page_data['_data_item_invoice_child_'], 'item_code');
		$childs_items_index = array_column($this->page_data['_data_item_invoice_child_'], 'index_list');

		$this->page_data['intersect_codex_item'] = array_intersect($parent_items_codex, $childs_items_codex);
		$this->page_data['intersect_index_item'] = array_intersect($parent_items_index, $childs_items_index);
		
		$this->page_data['_data_invoice_parent'] = $this->sale_model->get_invoice_selling_by_code($_invoice_parent_code);
		$this->page_data['_data_invoice_child_'] = $this->sale_model->get_invoice_selling_by_code($_invoice_child__code);

		$this->page_data['invoice_information_transaction'] = $this->page_data['intersect_codex_item']? 
			$this->page_data['_data_invoice_child_']:
			$this->page_data['_data_invoice_parent'];
		$this->page_data['customer'] = $this->customer_model->get_information_customer($this->page_data['invoice_information_transaction']->customer);
		$this->page_data['bank'] = $this->account_bank_model->getById($this->page_data['invoice_information_transaction']->transaction_destination);
	
	}

	//LIST QUALITY
	public function list()
	{
		ifPermissions('shipper_transaction_list');
		$this->page_data['title'] = 'shipping_list';
		$this->page_data['page']->submenu = 'list_quality_control';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'validation/shipper/quality_control',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list_checkquality', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	//LIST REPORT A PACKED
	public function report_packing()
	{
		ifPermissions('report_packing');
		$this->page_data['title'] = 'shipping_report';
		$this->page_data['page']->submenu = 'report_packing';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'validation/shipper/is_delevered',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list_reportapack', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	//LIST IS DELIVERED
	public function report_delivered()
	{
		ifPermissions('report_delivered');
		$this->page_data['title'] = 'shipping_report';
		$this->page_data['page']->submenu = 'report_delivered';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'validation/shipper/is_delivered',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list_reportdelivered', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	public function quality_control()
	{	
		ifPermissions('quality_control');
		$this->form_validation->set_rules('id[]', lang('id'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->data_sales();
			$this->page_data['invoice_sale'] = $this->sale_model->get_invoice_selling_by_code(get('id'));
			$this->page_data['list_item_sale'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['bank'] = $this->account_bank_model->get();
			$this->page_data['title'] = 'sale_edit';
			$this->page_data['page']->submenu = 'sale_list';
			$this->load->view('validation/shipper/quality_control', $this->page_data);
		}else{
			$this->data['invoice_code'] = $this->input->get('id')?$this->input->get('id'):$this->input->post('id');
			//information items
			$payment = array(
				'is_controlled_by' => logged('id'),
			);
			if($this->sale_model->update_by_code($this->data['invoice_code'], $payment)){
				$this->activity_model->add("Quality Control, #" . $this->data['invoice_code'], (array) $payment);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Quality Control is Saved');
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quality Control Failed, need ID Invoice information!');
			}
			redirect('validation/shipper/list');
		}
	}

	public function is_delevered()
	{
		ifPermissions('report_delivered');
		$this->form_validation->set_rules('id[]', lang('id'), 'required|trim');
		if($this->form_validation->run() == false){
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Delevered Failed, need ID Invoice information!');
			redirect('validation/shipper/report_packing');
		}else{
			$this->data['invoice_code'] = $this->input->get('id')?$this->input->get('id'):$this->input->post('id');
			$information = array(
				'is_delivered' => 1,
			);
			if($this->sale_model->update_by_code($this->data['invoice_code'], $information)){
				$this->activity_model->add("Delevered, #" . $this->data['invoice_code'], (array) $payment);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Delevered is Saved');
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Delevered Failed, need ID Invoice information!');
			}
			redirect('validation/shipper/report_packing');
		}
	}
	
}