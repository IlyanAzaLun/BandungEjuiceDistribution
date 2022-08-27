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
			
			$this->page_data['page']->submenu = 'report_packing';
			if(preg_match('/^(RET)/i', get('invoice'))){
				$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('invoice'));
			}else{
				$this->page_data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('invoice'));
			}
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['modals'] = (object) array(
				'id' => 'modal-confirmation-order',
				'title' => 'Modals confirmation',
				'link' => 'validation/shipper/is_delevered',
				'content' => 'delete',
				'btn' => 'btn-primary',
				'submit' => 'Yes do it',
			);
			$this->load->view('validation/shipper/pack', $this->page_data);
			
			$this->load->view('includes/modals', $this->page_data);
		}else{
			$this->data['invoice_code'] = get('invoice');
			$information = array(
				'is_controlled_by' => post('is_controlled_by'),
				'expedition' => post('expedition_name'),
				'services_expedition' => post('services_expedition'),
				'type_payment_shipping' => post('type_payment_shipping'),
				'pack_by' => strtoupper(post('pack_by')),
				'pack' => post('pack')
			);
			if($this->sale_model->update_by_code($this->data['invoice_code'], $information) && $this->purchase_model->update_by_code($this->data['invoice_code'], $information)){
				$this->activity_model->add("Delevered, #" . $this->data['invoice_code'], (array) $payment);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Delevered is Saved');
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Delevered Failed, need ID Invoice information!');
			}
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function update_address_destination()
	{
		ifPermissions('shipper_transaction_list');
		$data = $this->input->post();
		$information = array(
			'contact_phone' => $data['contact_phone'],
			'address' => $data['address'],
			'village' => null,
			'sub_district' => null,
			'city' => null,
			'province' => null,
			'zip' => null,
			'updated_at' => date('Y-m-d H:i:s', time()),
			'updated_by' => logged('id'),
		);
		$customer = array(
			'contact_us' => $data['contact_us'], 
			'owner_name' => $data['owner_name'],
		);
		if($this->address_model->updateByCustomerCode($data['customer_code'], $information)){
			# IF CUSTOMER OR SUPPLIER WILL BE UPDATED
			($this->customer_model->updateByCustomerCode($data['customer_code'], $customer) && $this->supplier_model->updateBySupplierCode($data['customer_code'], $customer));
			$this->activity_model->add("Customer,#" . $data['customer_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Customer is Saved');
		}else{
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Customer Failed, need ID Invoice information!');
		}
		redirect('validation/shipper/pack?invoice='.get('invoice'));
	}

	public function destination()
	{	
		ifPermissions('shipper_transaction_list');	
		if(preg_match('/^(RET)/i', get('invoice'))){
			$data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('invoice'));
		}else{
			$data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('invoice'));
		}
		$data['customer'] = $this->customer_model->get_information_customer(get('id')) && $this->supplier_model->get_information_supplier(get('id'));
		$data['loop'] = $data['invoice']->pack;
	
		$this->load->library('pdf');
	
        $options = $this->pdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $this->pdf->setOptions($options);


		$this->pdf->setPaper('A5', 'potrait');
		// $this->pdf->filename = $data['customer']->store_name."pdf";
		$this->pdf->load_view('validation/shipper/destination', $data);
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
	public function serverside_datatables_data_list_checkquality() // HERE
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
			$this->db->like('sale.invoice_code', $searchValue, 'after');
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
		$this->db->where("sale.is_controlled_by", null);
		$this->db->where("sale.is_delivered", null);
		$this->db->where("sale.is_cancelled", 0);
		$this->db->where("sale.is_child", 0);
		$records = $this->db->get('invoice_selling sale')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		## QUERY 1
		$this->db->select('
			purchasing.id as id, 
			purchasing.invoice_code as invoice_code, 
			purchasing.have_a_child as have_a_child, 
			purchasing.total_price as total_price, 
			purchasing.discounts as discounts, 
			purchasing.shipping_cost as shipping_cost, 
			purchasing.other_cost as other_cost, 
			purchasing.payment_type as payment_type, 
			purchasing.grand_total as grand_total, 
			purchasing.date_start as date_start, 
			purchasing.date_due as date_due, 
			purchasing.supplier as customer, 
			purchasing.note as note, 
			purchasing.created_at as created_at, 
			purchasing.updated_at as updated_at, 
			purchasing.created_by as created_by, 
			purchasing.updated_by as updated_by, 
			purchasing.is_controlled_by as is_controlled_by,  
			purchasing.is_delivered as is_delivered,  
			purchasing.is_cancelled as is_cancelled,  
			purchasing.cancel_note as cancel_note');
		$this->db->group_start();
		$this->db->where("purchasing.is_controlled_by", null);
		$this->db->where("purchasing.is_delivered", null);
		$this->db->where("purchasing.is_cancelled", 0);
		$this->db->where("purchasing.is_child", 1);
		$this->db->group_end();
		$subQuery1 = $this->db->get_compiled_select('invoice_purchasing purchasing', false);
		$this->db->reset_query();

		## QUERY 2
		$this->db->select('
			sale.id as id, 
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
			sale.customer as customer, 
			sale.note as note, 
			sale.created_at as created_at, 
			sale.updated_at as updated_at, 
			sale.created_by as created_by, 
			sale.updated_by as updated_by, 
			sale.is_controlled_by as is_controlled_by,  
			sale.is_delivered as is_delivered,  
			sale.is_cancelled as is_cancelled,  
			sale.cancel_note as cancel_note');
		$this->db->group_start();
		$this->db->where("sale.is_controlled_by", null);
		$this->db->where("sale.is_delivered", null);
		$this->db->where("sale.is_cancelled", 0);
		$this->db->where("sale.is_child", 0);
		$this->db->group_end();
		$subQuery2 = $this->db->get_compiled_select('invoice_selling sale', false);
		$this->db->reset_query();
		
		//
		$this->db->select('
			info.id as id, 
			SUBSTRING(info.invoice_code, 5) as invoice_code_reference, 
			info.invoice_code as invoice_code, 
			info.have_a_child as have_a_child, 
			info.total_price as total_price, 
			info.discounts as discounts, 
			info.shipping_cost as shipping_cost, 
			info.other_cost as other_cost, 
			info.payment_type as payment_type, 
			info.grand_total as grand_total, 
			info.date_start as date_start, 
			info.date_due as date_due, 
			info.note as note, 
			info.created_at as created_at, 
			info.updated_at as updated_at, 
			info.created_by as created_by, 
			info.is_controlled_by as is_controlled_by,  
			info.is_delivered as is_delivered,  
			info.is_cancelled as is_cancelled,  
			info.cancel_note as cancel_note,
			supplier.store_name as supplier_store_name,
			customer.store_name as customer_store_name,			
			supplier.customer_code as customer_code, 
			customer.customer_code as customer_code, 
			user_created.id as user_id, 
			user_created.name as user_sale_create_by,
			user_updated.id as user_id_updated, 
			user_updated.name as user_sale_update_by');

		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('info.invoice_code', $searchValue, 'both');
			$this->db->or_like('info.note', $searchValue, 'both');
			$this->db->or_like('info.created_at', $searchValue, 'both');
			$this->db->or_like('supplier.store_name', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}

		$this->db->join('users user_created', 'user_created.id = info.created_by', 'left');
		$this->db->join('users user_updated', 'user_updated.id = info.updated_by', 'left');
		$this->db->join('customer_information customer', 'customer.customer_code = info.customer', 'left');
		$this->db->join('supplier_information supplier', 'supplier.customer_code = info.customer', 'left');
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("info.created_at >=", $dateStart);
			$this->db->where("info.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("info.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->where("info.created_by", $logged);
		}
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get("($subQuery1 UNION $subQuery2) info", FALSE)->result();

		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->id,
				'invoice_code_reference' => $record->invoice_code_reference,
				'invoice_code' => $record->invoice_code,
				'have_a_child' => $record->have_a_child,
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
				'created_by' => $record->created_by,
				'is_controlled_by' => $record->is_controlled_by,
				'is_delivered' => $record->is_delivered,
				'is_cancelled' => $record->is_cancelled,
				'supplier_store_name' => $record->supplier_store_name,
				'customer_store_name' => $record->customer_store_name,
				'customer_code' => $record->customer_code,
				'customer_code' => $record->customer_code,
				'user_id' => $record->user_id,
				'user_sale_create_by' => $record->user_sale_create_by,
				'user_id_updated' => $record->user_id_updated,
				'user_sale_update_by' => $record->user_sale_update_by,
				'cancel_note' => $record->cancel_note,
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
	public function serverside_datatables_data_list_reportapack() // TO HERE
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
		## QUERY 1
		$this->db->select('
			purchasing.id as id, 
			purchasing.invoice_code as invoice_code, 
			purchasing.have_a_child as have_a_child, 
			purchasing.total_price as total_price, 
			purchasing.discounts as discounts, 
			purchasing.shipping_cost as shipping_cost, 
			purchasing.other_cost as other_cost, 
			purchasing.payment_type as payment_type, 
			purchasing.grand_total as grand_total, 
			purchasing.date_start as date_start, 
			purchasing.date_due as date_due, 
			purchasing.supplier as customer, 
			purchasing.note as note, 
			purchasing.created_at as created_at, 
			purchasing.updated_at as updated_at, 
			purchasing.created_by as created_by, 
			purchasing.updated_by as updated_by, 
			purchasing.is_controlled_by as is_controlled_by,  
			purchasing.is_delivered as is_delivered,  
			purchasing.is_cancelled as is_cancelled,  
			purchasing.cancel_note as cancel_note');
		$this->db->group_start();
		$this->db->where("purchasing.is_controlled_by !=", null);
		$this->db->where("purchasing.is_delivered", null);
		$this->db->where("purchasing.is_cancelled", 0);
		$this->db->where("purchasing.is_child", 1);
		$this->db->group_end();
		$subQuery1 = $this->db->get_compiled_select('invoice_purchasing purchasing', false);
		$this->db->reset_query();

		## QUERY 2
		$this->db->select('
			sale.id as id, 
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
			sale.customer as customer, 
			sale.note as note, 
			sale.created_at as created_at, 
			sale.updated_at as updated_at, 
			sale.created_by as created_by, 
			sale.updated_by as updated_by, 
			sale.is_controlled_by as is_controlled_by,  
			sale.is_delivered as is_delivered,  
			sale.is_cancelled as is_cancelled,  
			sale.cancel_note as cancel_note');
		$this->db->group_start();
		$this->db->where("sale.is_controlled_by !=", null);
		$this->db->where("sale.is_delivered", null);
		$this->db->where("sale.is_cancelled", 0);
		$this->db->group_end();
		$subQuery2 = $this->db->get_compiled_select('invoice_selling sale', false);
		$this->db->reset_query();

		$this->db->select('
			info.id as id, 
			SUBSTRING(info.invoice_code, 5) as invoice_code_reference, 
			info.invoice_code as invoice_code, 
			info.have_a_child as have_a_child, 
			info.total_price as total_price, 
			info.discounts as discounts, 
			info.shipping_cost as shipping_cost, 
			info.other_cost as other_cost, 
			info.payment_type as payment_type, 
			info.grand_total as grand_total, 
			info.date_start as date_start, 
			info.date_due as date_due, 
			info.note as note, 
			info.created_at as created_at, 
			info.updated_at as updated_at, 
			info.created_by as created_by, 
			info.is_controlled_by as is_controlled_by,  
			info.is_delivered as is_delivered,  
			info.is_cancelled as is_cancelled,  
			info.cancel_note as cancel_note,			
            IFNULL(supplier.store_name,customer.store_name) AS store_name,
            IFNULL(supplier.customer_code,customer.customer_code) AS customer_code,
			user_created.id as user_id, 
			user_created.name as user_sale_create_by,
			user_updated.id as user_id_updated, 
			user_updated.name as user_sale_update_by');

		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('info.invoice_code', $searchValue, 'both');
			$this->db->or_like('info.customer', $searchValue, 'both');
			$this->db->or_like('info.note', $searchValue, 'both');
			$this->db->or_like('info.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		$this->db->join('users user_created', 'user_created.id = info.created_by', 'left');
		$this->db->join('users user_updated', 'user_updated.id = info.updated_by', 'left');
		$this->db->join('customer_information customer', 'customer.customer_code = info.customer', 'left');
		$this->db->join('supplier_information supplier', 'supplier.customer_code = info.customer', 'left');
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("info.created_at >=", $dateStart);
			$this->db->where("info.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("info.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->where("info.created_by", $logged);
		}
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get("($subQuery1 UNION $subQuery2) info", FALSE)->result();

		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->id,
				'invoice_code_reference' => $record->invoice_code_reference,
				'invoice_code' => $record->invoice_code,
				'have_a_child' => $record->have_a_child,
				'total_price' => $record->total_price,
				'discounts' => $record->discounts,
				'shipping_cost' => $record->shipping_cost,
				'other_cost' => $record->other_cost,
				'payment_type' => $record->payment_type,
				'grand_total' => $record->grand_total,
				'date_start' => $record->date_start,
				'date_due' => $record->date_due,
				'note' => $record->note,
				'created_at' => $record->created_at,
				'updated_at' => $record->updated_at,
				'created_by' => $record->created_by,
				'is_controlled_by' => $record->is_controlled_by,
				'is_delivered' => $record->is_delivered,
				'is_cancelled' => $record->is_cancelled,
				'cancel_note' => $record->cancel_note,
				'store_name' => $record->store_name,
				'customer_code' => $record->customer_code,
				'user_id' => $record->user_id,
				'user_sale_create_by' => $record->user_sale_create_by,
				'user_id_updated' => $record->user_id_updated,
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

	public function delivered()
	{
		ifPermissions('report_delivered');
		$this->form_validation->set_rules('id', lang('id'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('invoice'));
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['title'] = 'shipping_report';
			$this->page_data['page']->submenu = 'report_delivered';
			$this->page_data['modals'] = (object) array(
				'id' => 'modal-confirmation-order',
				'title' => 'Modals confirmation',
				'link' => 'validation/shipper/delivered',
				'content' => 'delete',
				'btn' => 'btn-primary',
				'submit' => 'Yes do it',
			);
			$this->load->view('validation/shipper/delivered', $this->page_data);
			$this->load->view('includes/modals', $this->page_data);
		}else{
			$this->data['invoice_code'] = $this->input->get('id')?$this->input->get('id'):$this->input->post('id');
			//information items
			$information = array(
				'receipt_code' => post('note'),
			);
			if($this->sale_model->update_by_code($this->data['invoice_code'], $information)){
				$this->activity_model->add("Quality Control, #" . $this->data['invoice_code'], (array) $information);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Quality Control is Saved');
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quality Control Failed, need ID Invoice information!');
			}
			redirect('validation/shipper/report_delivered');
		}

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
			'link' => 'validation/shipper/delivered',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list_reportdelivered', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}
	public function serverside_datatables_data_list_reportdelivered()
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
		$this->db->where("sale.is_delivered !=", null);
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
		sale.receipt_code as receipt_code,
		customer.customer_code as customer_code, 
		customer.store_name as store_name, 
		user_created.id as user_id, 
		user_created.name as user_sale_create_by,
		user_updated.id as user_id_updated, 
		user_updated.name as user_sale_update_by, ');
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
		$this->db->where("sale.is_delivered !=", null);
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
				'receipt_code' => $record->receipt_code,
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

	public function quality_control()
	{	
		ifPermissions('quality_control');
		$this->form_validation->set_rules('id[]', lang('id'), 'required|trim');
		if ($this->form_validation->run() == false) {
			## MEMBEDAKAN FAKTUR PEMBELIAN DAN FAKTUR PENJUALAN
			if(preg_match('/^(RET)/i', get('id'))){
				$this->page_data['invoice_sale'] = $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
			}else{
				$this->page_data['invoice_sale'] = $this->sale_model->get_invoice_selling_by_code(get('id'));
			}
			$this->page_data['list_item_sale'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['bank'] = $this->account_bank_model->get();
			$this->page_data['title'] = 'sale_edit';
			$this->page_data['page']->submenu = 'sale_list';
			$this->load->view('validation/shipper/quality_control', $this->page_data);
		}else{
			$this->data['invoice_code'] = $this->input->get('id')?$this->input->get('id'):$this->input->post('id');
			echo '<pre>';
			$transaction = $this->transaction_item_model->getByWhere(['invoice_code' => $this->data['invoice_code'], 'is_cancelled' => 0]);
			foreach ($transaction as $key => $value) {
				$transaction[$key]->control_by = $this->input->post('is_controlled_by')[$key];
			}
			## UPDATE LIST ITEM FROM QUALITY TO PACKING LIST
			$this->transaction_item_model->update_batch($transaction, 'id');
			echo '</pre>';
			//information items
			$payment = array(
				'is_controlled_by' => logged('name'),
			);
			## UPDATE JIKA FAKTUR RETURN PEMBELIAN
			## UPDATE JIKA FAKTUR PENJUALAN
			if($this->sale_model->update_by_code($this->data['invoice_code'], $payment) && $this->purchase_model->update_by_code($this->data['invoice_code'], $payment)){
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
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			$this->data['invoice_code'] = $this->input->get('id')?$this->input->get('id'):$this->input->post('id');
			$information = array(
				'is_delivered' => 1,
			);
			if($this->sale_model->update_by_code($this->data['invoice_code'], $information) && $this->purchase_model->update_by_code($this->data['invoice_code'], $information)){
				$this->activity_model->add("Delevered, #" . $this->data['invoice_code'], (array) $payment);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Delevered is Saved');
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Delevered Failed, need ID Invoice information!');
			}
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function address()
	{
		$this->page_data['title'] = 'address_list';
		$this->page_data['page']->submenu = 'list_address_shipping';
		$this->load->view('address/address', $this->page_data);
	}	
}