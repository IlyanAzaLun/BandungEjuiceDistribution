<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Purchase Returns';
		$this->page_data['page']->menu = 'Purchase';
	}

	public function index()
	{
		ifPermissions('purchase_payment');
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->page_data['title'] = 'purchase_returns';
		$this->page_data['page']->submenu = 'payment';
		$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
		$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
		if(!($this->page_data['invoice'] && get('id'))){
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', lang('error_worng_information'));
			redirect('invoice/purchases/payment/list');	
		}
		if ($this->form_validation->run() == false) {
			$this->load->view('invoice/purchase/payment_edit', $this->page_data);
		} else {
			$this->activity_model->add("Create purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Item Upload Successfully');

			redirect('invoice/purchases/payment/list');
		}
	}

	public function list()
	{
		ifPermissions('purchase_payment_list');
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->page_data['title'] = 'purchase_returns';
		$this->page_data['page']->submenu = 'payment';
		if ($this->form_validation->run() == false) {
			$this->load->view('invoice/purchase/payment', $this->page_data);
		} else {
			$this->activity_model->add("Create purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Item Upload Successfully');

			redirect('invoice/purchases/payment/list');
		}
	}

	public function serverside_datatables_data_purchase_payment()
	{
		ifPermissions('purchase_payment_list');
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

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$this->db->where('have_a_child IS NULL', null, false);
		$records = $this->db->get('invoice_purchasing')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->where('purchasing.have_a_child IS NULL', null, false);
		if ($searchValue != '') {
			$this->db->like('purchasing.invoice_code', $searchValue, 'both');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
			$this->db->or_like('purchasing.note', $searchValue, 'both');
			$this->db->or_like('purchasing.created_at', $searchValue, 'both');
		}
		if ($dateStart != '') {
			$this->db->where("purchasing.created_at >=", $dateStart);
			$this->db->where("purchasing.created_at <=", $dateFinal);
			// $this->db->where("purchasing.created_at BETWEEN $dateStart AND $dateFinal", null, FALSE);
		}
		$records = $this->db->get('invoice_purchasing purchasing')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('
		purchasing.id as purchasing_id, 
		SUBSTRING(purchasing.invoice_code, 5) as invoice_code_reference,
		purchasing.invoice_code as invoice_code, 
		purchasing.have_a_child as have_a_child, 
		purchasing.created_at as purchasing_create_at, 
		purchasing.total_price as total_price, 
		purchasing.discounts as discounts, 
		purchasing.other_cost as other_cost, 
		purchasing.payment_type as payment_type, 
		purchasing.status_payment as status_payment, 
		purchasing.grand_total as grand_total, 
		purchasing.note as purchase_note, 
		purchasing.created_at as created_at, 
		purchasing.updated_at as updated_at, 
		purchasing.created_by as created_by, 
		purchasing.date_start as date_start, 
		purchasing.date_due as date_due, 
		purchasing.is_cancelled as is_cancelled, 
		supplier.customer_code as supplier_code, 
		supplier.store_name as store_name, 
		user.id as user_id, 
		user.name as user_purchasing_create_by');
		$this->db->join('users user', 'user.id = purchasing.created_by', 'left');
		$this->db->join('supplier_information supplier', 'supplier.customer_code = purchasing.supplier', 'left');
		if ($searchValue != '') {
			$this->db->like('purchasing.invoice_code', $searchValue, 'both');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
			$this->db->or_like('purchasing.note', $searchValue, 'both');
			$this->db->or_like('purchasing.created_at', $searchValue, 'both');
			$this->db->or_like('supplier.store_name', $searchValue, 'both');
		}
		if ($dateStart != '') {
			$this->db->where("purchasing.created_at >=", $dateStart);
			$this->db->where("purchasing.created_at <=", $dateFinal);
		}
		$this->db->where('purchasing.have_a_child IS NULL', null, false);
		$this->db->where('purchasing.is_cancelled', 0);
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('invoice_purchasing purchasing')->result();

		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->purchasing_id,
				'invoice_code_reference' => $record->invoice_code_reference,
				'invoice_code' => $record->invoice_code,
				'have_a_child' => $record->have_a_child,
				'supplier_code' => $record->supplier_code,
				'store_name' => $record->store_name,
				'total_price' => $record->total_price,
				'discounts' => $record->discounts,
				'shipping_cost' => $record->shipping_cost,
				'other_cost' => $record->other_cost,
				'payment_type' => lang($record->payment_type),
				'status_payment' => lang($record->status_payment),
				'grand_total' => $record->grand_total,
				'purchase_note' => $record->purchase_note,
				'date_start' => $record->date_start,
				'date_due' => $record->date_due,
				'created_at' => $record->created_at,
				'updated_at' => $record->updated_at,
				'user_id' => $record->user_id,
				'is_cancelled' => $record->is_cancelled,
				'user_purchasing_create_by' => $record->user_purchasing_create_by,
			);
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
			"invoices_code" => $invoices_code
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
}

/* End of file Purchasing.php */
/* Abstract file Location: ./application/controllers/Invoice.php */
/* Location: ./application/controllers/invoices/Purchasing.php */