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

	public function pack()
	{
		ifPermissions('shipper_transaction_list');	
		$this->form_validation->set_rules('pack', lang('pack'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('invoice'));
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->load->view('validation/shipper/pack', $this->page_data);
		}else{
			$this->data['invoice_code'] = get('invoice');
			$information = array(
				'pack' => post('pack'),
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

	public function destination()
	{	
		ifPermissions('shipper_transaction_list');	
		$customer = $this->customer_model->get_information_customer(get('id'));
		$data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('invoice'));
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

	//LIST QUALITY CONTROL
	public function list()
	{
		ifPermissions('quality_control');
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
	public function serverside_datatables_data_list_checkquality()
	{
		$response = array();

		$postData = $this->input->post();
		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value
		$dateStart = $postData['startDate'];
		$dateFinal = $postData['finalDate'];
		$logged = logged('id');
		$haspermission = hasPermissions('fetch_all_invoice_sales');

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('invoice_selling')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		if ($searchValue != '') {
			$this->db->like('sale.invoice_code', $searchValue, 'both');
			$this->db->or_like('sale.customer', $searchValue, 'both');
			$this->db->or_like('sale.note', $searchValue, 'both');
			$this->db->or_like('sale.created_at', $searchValue, 'both');
		}
		if ($dateStart != '') {
			$this->db->where("sale.created_at >=", $dateStart);
			$this->db->where("sale.created_at <=", $dateFinal);
		}else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
		$this->db->where("sale.is_controlled_by", null);
		$this->db->where("sale.is_delivered", null);
		$records = $this->db->get('invoice_selling sale')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('
		sale.id as id, 
		SUBSTRING(sale.invoice_code, 5) as invoice_code_reference, 
		sale.invoice_code as invoice_code, 
		sale.have_a_child as have_a_child, 
		sale.total_price as total_price, 
		sale.discounts as discounts, 
		sale.shipping_cost as shipping_cost, 
		sale.other_cost as other_cost, 
		sale.payment_type as payment_type, 
		sale.grand_total as grand_total, 
		sale.date_start as date_start, 
		sale.date_due as date_due, 
		sale.note as note, 
		sale.created_at as created_at, 
		sale.updated_at as updated_at, 
		sale.created_by as created_by, 
		sale.is_controlled_by as is_controlled_by,  
		sale.is_delivered as is_delivered,  
		sale.is_cancelled as is_cancelled,  
		sale.cancel_note as cancel_note,
		customer.customer_code as customer_code, 
		customer.store_name as store_name, 
		user_created.id as user_id, 
		user_created.name as user_sale_create_by,
		user_updated.id as user_id_updated, 
		user_updated.name as user_sale_update_by, ');
		if ($searchValue != '') {
			$this->db->like('sale.invoice_code', $searchValue, 'both');
			$this->db->or_like('sale.customer', $searchValue, 'both');
			$this->db->or_like('sale.note', $searchValue, 'both');
			$this->db->or_like('sale.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
		}
		$this->db->join('users user_created', 'user_created.id = sale.created_by', 'left');
		$this->db->join('users user_updated', 'user_updated.id = sale.created_by', 'left');
		$this->db->join('customer_information customer', 'customer.customer_code = sale.customer', 'left');
		if ($dateStart != '') {
			$this->db->where("sale.created_at >=", $dateStart);
			$this->db->where("sale.created_at <=", $dateFinal);
		}else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->where("sale.created_by", $logged);
		}
		$this->db->where("sale.is_controlled_by", null);
		$this->db->where("sale.is_delivered", null);
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('invoice_selling sale')->result();

		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->id,
				'invoice_code_reference' => $record->invoice_code_reference,
				'invoice_code' => $record->invoice_code,
				'have_a_child' => $record->have_a_child,
				'customer_code' => $record->customer_code,
				'store_name' => $record->store_name,
				'total_price' => $record->total_price,
				'discounts' => $record->discounts,
				'shipping_cost' => $record->shipping_cost,
				'other_cost' => $record->other_cost,
				'payment_type' => lang($record->payment_type),
				'grand_total' => $record->grand_total,
				'date_start' => $record->date_start,
				'date_due' => $record->date_due,
				'note' => $record->note,
				'created_at' => $record->created_at,
				'updated_at' => $record->updated_at,
				'user_id' => $record->user_id,
				'user_id_updated' => $record->user_id_updated,
				'is_controlled_by' => $record->is_controlled_by,
				'is_delivered' => $record->is_delivered,
				'is_cancelled' => $record->is_cancelled,
				'cancel_note' => $record->cancel_note,
				'user_sale_create_by' => $record->user_sale_create_by,
				'user_sale_update_by' => $record->user_sale_update_by,
			);
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
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