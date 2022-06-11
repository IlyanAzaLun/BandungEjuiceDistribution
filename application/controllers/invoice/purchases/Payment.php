<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Payment';
		$this->page_data['page']->menu = 'Payment';
		$this->page_data['page']->submenu = 'purchase_payment';
	}
    
    public function index()
    {
        $this->page_data['page']->submenu_child = 'purchase_payment_list';
        $this->load->view('payment/purchase/list_payment', $this->page_data);
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
		$dateStart = @$postData['startDate'];
		$dateFinal = @$postData['finalDate'];

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('invoice_purchasing')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		if ($searchValue != '') {
			$this->db->like('purchasing.invoice_code', $searchValue, 'both');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
			$this->db->or_like('purchasing.note', $searchValue, 'both');
			$this->db->or_like('purchasing.created_at', $searchValue, 'both');
		}
		if ($dateStart != '') {
			$this->db->where("purchasing.created_at >=", $dateStart);
			$this->db->where("purchasing.created_at <=", $dateFinal);
		}else{
			$this->db->like("purchasing.created_at", date("Y-m"), 'after');
		}
		$records = $this->db->get('invoice_purchasing purchasing')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('
		purchasing.id as purchasing_id, 
		purchasing.invoice_code as invoice_code, 
		purchasing.have_a_child as have_a_child, 
		purchasing.created_at as purchasing_create_at, 
		purchasing.total_price as total_price, 
		purchasing.discounts as discounts, 
		purchasing.shipping_cost as shipping_cost, 
		purchasing.other_cost as other_cost, 
		purchasing.payment_type as payment_type, 
		purchasing.status_payment as status_payment, 
		purchasing.grand_total as grand_total, 
		purchasing.note as purchase_note, 
		purchasing.created_at as created_at, 
		purchasing.updated_at as updated_at, 
		purchasing.created_by as created_by, 
		purchasing.is_cancelled as is_cancelled, 
		purchasing.cancel_note as cancel_note, 
		supplier.customer_code as supplier_code, 
		supplier.store_name as store_name, 
		user.id as user_id, 
		user.name as user_purchasing_create_by');
		if ($searchValue != '') {
			$this->db->like('purchasing.invoice_code', $searchValue, 'both');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
			$this->db->or_like('purchasing.note', $searchValue, 'both');
			$this->db->or_like('purchasing.created_at', $searchValue, 'both');
			$this->db->or_like('supplier.store_name', $searchValue, 'both');
		}
		$this->db->join('users user', 'user.id = purchasing.created_by', 'left');
		$this->db->join('supplier_information supplier', 'supplier.customer_code = purchasing.supplier', 'left');
		$this->db->where('purchasing.is_child', 1);
		if ($dateStart != '') {
			$this->db->where("purchasing.created_at >=", $dateStart);
			$this->db->where("purchasing.created_at <=", $dateFinal);
		}else{
			$this->db->like("purchasing.created_at", date("Y-m"), 'after');
		}
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('invoice_purchasing purchasing')->result();

		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->purchasing_id,
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
				'created_at' => $record->created_at,
				'updated_at' => $record->updated_at,
				'user_id' => $record->user_id,
				'is_cancelled' => $record->is_cancelled,
				'cancel_note' => $record->cancel_note,
				'user_purchasing_create_by' => $record->user_purchasing_create_by,
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