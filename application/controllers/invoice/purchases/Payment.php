<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		ifPermissions('payment');
		$this->page_data['page']->title = 'payment';
		$this->page_data['page']->menu = 'Payment';
		$this->page_data['page']->submenu = 'indebtedness';
	}
    
    public function index()
    {
        ifPermissions('payment');
		$this->page_data['title'] = 'payment_indebtedness_list';
        $this->page_data['page']->submenu_child = 'indebtedness_list';
        $this->load->view('payment/purchase/list_payment', $this->page_data);
    }

	public function debt()
	{
        ifPermissions('payment');
		$this->page_data['title'] = 'payment_indebtedness';
		$this->page_data['page']->submenu_child = 'payment_indebtedness';
		$this->form_validation->set_rules('supplier_code', 'Customer Name', 'required|trim');
        if ($this->form_validation->run() == false) {
			$this->load->view('payment/purchase/debt', $this->page_data);
		}else{
			postAllowed();
			$this->page_data['data_post'] = $this->input->post();
			$date = preg_split('/[-]/', $this->page_data['data_post']['min']);
			$this->page_data['data_post']['date'] = array(
				'date_start' => trim(str_replace('/', '-', $date[0])), 
				'date_end'	 => trim(str_replace('/', '-', $date[1]))
			);
			$this->page_data['data_list_debts'] = $this->indebtedness->select_invoice_by_customer_code($this->page_data['data_post']);
			$this->load->view('payment/purchase/debt_list', $this->page_data);
		}
	}

	public function debt_to()
	{
        ifPermissions('payment');
		$this->page_data['title'] = 'payment_indebtedness';
		$this->page_data['page']->submenu_child = 'payment_indebtedness';
		$this->form_validation->set_rules('invoice_code', 'Invoice Code', 'required|trim');
		if($this->form_validation->run() == false){
			// $this->page_data['information'] = $this->indebtedness->select_invoice();

			$this->load->view('payment/purchase/debt_to', $this->page_data);
		}
	}

    public function serverside_datatables_data_payment_purchase()
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

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('invoice_payment')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('invoice_purchasing purchase', 'payment.invoice_code = purchase.invoice_code', 'left');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('payment.invoice_code', $searchValue, 'both');
			$this->db->or_like('payment.customer_code', $searchValue, 'both');
			$this->db->or_like('payment.description', $searchValue, 'both');
			$this->db->group_end();
		}
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("payment.created_at >=", $dateStart);
			$this->db->where("payment.created_at <=", $dateFinal);
			$this->db->group_end();
		}
		else{
			// select by years
			$this->db->like("payment.created_at", date("Y"), 'after');
		}
		// select indebtness
		$this->db->where("purchase.is_transaction", 1);
		// is have indebtness,
		$this->db->where("payment.leftovers !=", 0);
		$records = $this->db->get('invoice_payment payment')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('
          payment.id
        , payment.invoice_code
        , payment.date_start
        , payment.date_due
        , payment.customer_code
        , payment.grand_total
        , payment.payup
        , payment.leftovers
        , payment.status_payment
        , payment.payment_type
        , payment.bank_id
        , payment.is_cancelled
        , payment.cancel_note
        , payment.created_by
        , MAX(payment.created_at) as created_at
        , payment.updated_by
        , payment.updated_at
        , payment.description
		, supplier.store_name
        , user_created.id as user_created_id
        , user_created.name as user_created_by
        , user_updated.id as user_updated_id
        , user_updated.name as user_updated_by
        , IF(payment.updated_at, payment.updated_at, payment.created_at) as payment_date_at
        ');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('payment.invoice_code', $searchValue, 'both');
			$this->db->or_like('payment.customer_code', $searchValue, 'both');
			$this->db->or_like('payment.description', $searchValue, 'both');
			$this->db->or_like('supplier.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
        $this->db->join('invoice_purchasing purchase', 'payment.invoice_code = purchase.invoice_code', 'left');
        $this->db->join('users user_created', 'user_created.id=payment.created_by', 'left');
        $this->db->join('users user_updated', 'user_updated.id=payment.updated_by', 'left');
        $this->db->join('supplier_information supplier', 'supplier.customer_code = payment.customer_code', 'left');
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("payment.created_at >=", $dateStart);
			$this->db->where("payment.created_at <=", $dateFinal);
			$this->db->group_end();
		}
		else{
			// select by years
			$this->db->like("payment.created_at", date("Y"), 'after');
		}
		// select indebtness
		$this->db->where("purchase.is_transaction", 1);
		// is have indebtness,
		$this->db->where("payment.leftovers !=", 0);
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->group_by('payment.invoice_code');
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('invoice_payment payment')->result();
		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				"id"=> $record->id,
				"invoice_code"=> $record->invoice_code,
				"date_start"=> $record->date_start,
				"date_due"=> $record->date_due,
				"customer_code"=> $record->customer_code,
				"store_name"=> $record->store_name,
				"grand_total"=> $record->grand_total,
				"payup"=> $record->payup,
				"leftovers"=> $record->leftovers,
				"status_payment"=> $record->status_payment,
				"payment_type"=> lang($record->payment_type),
				"bank_id"=> $record->bank_id,
				"is_cancelled"=> $record->is_cancelled,
				"cancel_note"=> $record->cancel_note,
				"created_by"=> $record->created_by,
				"created_at"=> $record->created_at,
				"updated_by"=> $record->updated_by,
				"updated_at"=> $record->updated_at,
				"description"=> $record->description,
				"user_created_id"=> $record->user_created_id,
				"user_created_by"=> $record->user_created_by,
				"user_updated_id"=> $record->user_updated_id,
				"user_updated_by"=> $record->user_updated_by,
				"payment_date_at"=> $record->payment_date_at,
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
}