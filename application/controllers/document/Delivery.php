<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Delivery extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Delivery';
		$this->page_data['page']->menu = 'Document';
	}

    public function index()
    {
        $this->list();
    }
    public function list()
    {
        ifPermissions('delivery_document');
		$this->page_data['title'] = 'delivery_document_list';
		$this->page_data['page']->submenu = 'list';
		$this->load->view('document/delivery/list', $this->page_data);
    }
    public function create()
    {
        ifPermissions('delivery_document_create');
        $this->page_data['title'] = 'shipping_list';
		$this->page_data['page']->submenu = 'create';
        $this->load->view('document/delivery/create', $this->page_data);
    }
	public function serverside_datatables_data_list_document_delivery()
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
		$this->db->join('customer_information customer', 'customer.customer_code = sale.customer', 'left');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('sale.invoice_code', $searchValue, 'both');
			$this->db->or_like('sale.customer', $searchValue, 'both');
			$this->db->or_like('sale.note', $searchValue, 'both');
			$this->db->or_like('sale.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("sale.created_at >=", $dateStart);
			$this->db->where("sale.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
		$this->db->where("sale.is_controlled_by !=", null);
		$this->db->where("sale.is_delivered", null);
		$this->db->where("sale.is_cancelled", 0);
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
            user_updated.name as user_sale_update_by
        ');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('sale.invoice_code', $searchValue, 'both');
			$this->db->or_like('sale.customer', $searchValue, 'both');
			$this->db->or_like('sale.note', $searchValue, 'both');
			$this->db->or_like('sale.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		$this->db->join('users user_created', 'user_created.id = sale.created_by', 'left');
		$this->db->join('users user_updated', 'user_updated.id = sale.created_by', 'left');
		$this->db->join('customer_information customer', 'customer.customer_code = sale.customer', 'left');
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("sale.created_at >=", $dateStart);
			$this->db->where("sale.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->where("sale.created_by", $logged);
		}
		$this->db->where("sale.is_controlled_by !=", null);
		$this->db->where("sale.is_delivered", null);
		$this->db->where("sale.is_cancelled", 0);
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
}