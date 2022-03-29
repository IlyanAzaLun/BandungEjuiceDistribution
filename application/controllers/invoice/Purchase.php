<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Invoice_controller.php';
class Purchase extends Invoice_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Purchase (Pembelian)';
		$this->page_data['page']->menu = 'Purchase';
	}
	public function index()
	{
		$this->list();
	}

	public function list()
	{
		ifPermissions('purchase_list');
		$this->page_data['title'] = 'purchase_list';
		$this->page_data['page']->submenu = 'list';
		$this->load->view('invoice/purchase/list', $this->page_data);
	}

	public function create()
	{
		ifPermissions('purchase_create');
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['title'] = 'purchase_create';
			$this->page_data['page']->submenu = 'create';
			$this->load->view('invoice/purchase/create', $this->page_data);
		} else {
			// information invoice
			$this->data['invoice_code'] = $this->purchase_model->get_code_invoice_purchase();
			$date = preg_split('/[-]/', $this->input->post('date_due'));
			$this->data['date'] = array(
				'date_start' => trim(str_replace('/', '-', $date[0])), 
				'date_due'	 => trim(str_replace('/', '-', $date[1]))
			);
			//information items
			$items = array();
			foreach (post('item_code') as $key => $value) {
				$items[] = array(
					"item_id" => post('item_id')[$key],
					"item_code" => post('item_code')[$key],
					"item_name" => post('item_name')[$key],
					"item_quantity" => post('item_quantity')[$key],
					"item_order_quantity" => post('item_order_quantity')[$key],
					"item_unit" => post('item_unit')[$key],
					"item_capital_price" => post('item_capital_price')[$key],
					"item_selling_price" => post('item_selling_price')[$key],
					"item_discount" => post('item_discount')[$key],
					"total_price" => post('total_price')[$key],
					"item_description" => post('description')[$key],
					"customer_code" => post('supplier_code'),
				);
			}
			//information payment
			$payment = array(
				'supplier' => post('supplier_code'),
				'store_name' => post('store_name'),
				'contact_phone' => post('contact_phone'),
				'address' => post('address'),
				'total_price' => post('sub_total'),
				'discounts' => post('discount'),
				'shipping_cost' => post('shipping_cost'),
				'other_cost' => post('other_cost'),
				'grand_total' => post('grand_total'),
				'payment_type' => post('payment_type'),
				'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'credit',
				'date_start' => date("Y-m-d H:i",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i",strtotime($this->data['date']['date_due'])),
				'note' => post('note'),
			);
			try {
				$this->create_item_history($items, ['CREATE', 'UPDATE']);
				$this->create_or_update_invoice($payment);
				$this->create_or_update_list_item_transcation($items);
				$this->update_items($items);
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			$this->activity_model->add("Create Purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Purchase Successfully');

			redirect('invoice/purchase/list');
		}
	}

	public function edit()
	{
		ifPermissions('purchase_edit');
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['title'] = 'purchase_edit';
			$this->page_data['page']->submenu = 'edit';
			$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
			$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));

			$this->page_data['modals'] = (object) array(
				'id' => 'modal-remove-order',
				'title' => 'Modals confirmation',
				'link' => 'invoice/purchases/items/remove_item_from_list_order_transcaction',
				'content' => 'delete',
				'btn' => 'btn-danger',
				'submit' => 'Yes do it',
			);
			$this->load->view('invoice/purchase/form', $this->page_data);
			$this->load->view('includes/modals');
		} else {
			// information invoice
			$this->data['invoice_code'] = $this->input->get('id');
			$date = preg_split('/[-]/', $this->input->post('date_due'));
			$this->data['date'] = array(
				'date_start' => trim(str_replace('/', '-', $date[0])), 
				'date_due'	 => trim(str_replace('/', '-', $date[1]))
			);
			//information items
			$items = array();
			foreach (post('item_code') as $key => $value) {
				$items[$key]['id'] = post('id')[$key];
				$items[$key]['item_id'] = post('item_id')[$key];
				$items[$key]['item_code'] = post('item_code')[$key];
				$items[$key]['item_name'] = post('item_name')[$key];
				$items[$key]['item_quantity_current'] = post('item_quantity_current')[$key];
				$items[$key]['item_quantity'] = post('item_quantity')[$key];
				$items[$key]['item_order_quantity_current'] = post('item_order_quantity_current')[$key];
				$items[$key]['item_order_quantity'] =  post('item_order_quantity')[$key];
				$items[$key]['item_unit'] = post('item_unit')[$key];
				$items[$key]['item_capital_price'] = post('item_capital_price')[$key];
				$items[$key]['item_selling_price'] = post('item_selling_price')[$key];
				$items[$key]['item_discount'] = post('item_discount')[$key];
				$items[$key]['total_price'] = post('total_price')[$key];
				$items[$key]['item_description'] = post('description')[$key];
				$items[$key]['customer_code'] = post('supplier_code');
				if($items[$key]['item_order_quantity'] == $items[$key]['item_order_quantity_current']){
					unset($items[$key]);
				}
			}
			$items = array_values($items);

			//information payment
			$payment = array(
				'supplier' => post('supplier_code'),
				'store_name' => post('store_name'),
				'contact_phone' => post('contact_phone'),
				'address' => post('address'),
				'total_price' => post('sub_total'),
				'discounts' => post('discount'),
				'shipping_cost' => post('shipping_cost'),
				'other_cost' => post('other_cost'),
				'grand_total' => post('grand_total'),
				'payment_type' => post('payment_type'),
				'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'credit',
				'date_start' => date("Y-m-d H:i",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i",strtotime($this->data['date']['date_due'])),
				'note' => post('note'),
				'created_by' => logged('id'),
			);
			try {
				$this->create_item_history($items, ['CREATE', 'UPDATE']);
				$this->create_or_update_invoice($payment);
				$this->create_or_update_list_item_transcation($items);
				$this->update_items($items);
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			$this->activity_model->add("Edit Purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Edit Purchase Successfully');

			redirect('invoice/purchase/list');
		}
	}

	public function info()
	{
		ifPermissions('purchase_info');
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		$this->page_data['title'] = 'purchase_info';
		$this->page_data['page']->submenu = 'info';
		$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
		$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
		
		$this->page_data['modals'] = (object) array(
			'id' => 'exampleModal',
			'title' => 'Modals confirmation',
			'link' => 'invoice/purchase/cancel?id='.get('id'),
			'content' => 'delete',
			'btn' => 'btn-danger',
			'submit' => 'Yes do it',
		);

		$this->load->view('invoice/purchase/form', $this->page_data);
		$this->load->view('includes/modals');
	}
	
	public function cancel()
	{
		echo "<pre>";
		$items = array();
		$payment = (array) $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
		$this->data['invoice_code'] = get('id');
		if(preg_match('/INV/', get('id'))){
			$purchase_ = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
			$purchase_return_ = $this->transaction_item_model->get_transaction_item_by_code_invoice(str_replace('INV','RET',$this->input->get('id')));
			$items_code = array_column($purchase_, 'item_code');
			$items_index = array_column($purchase_, 'index_list');
			$items_code_return = array_column($purchase_return_, 'item_code');
			$items_index_return = array_column($purchase_return_, 'index_list');
			$intersect_code_item = array_intersect($items_code, $items_code_return);
			$intersect_index_item = array_intersect($items_index, $items_index_return);
			$i = 0;
			foreach ($purchase_ as $key => $value){
				$items[$key]['id'] = $value->id;
				$items[$key]['item_id'] = $value->item_id;
				$items[$key]['invoice_code'] = $value->invoice_code;
				$items[$key]['index_list'] = $value->index_list;
				$items[$key]['item_code'] = $value->item_code;
				$items[$key]['item_name'] = $value->item_name;
				$items[$key]['item_quantity_current'] = $this->items_model->getByCodeItem($value->item_code, 'quantity');
				$items[$key]['item_unit'] = $value->item_unit;
				$items[$key]['item_capital_price'] = $value->item_capital_price;
				$items[$key]['item_selling_price'] = $value->item_selling_price;
				$items[$key]['item_discount'] = $value->item_discount;
				$items[$key]['total_price'] = $value->total_price;
				$items[$key]['item_description'] = $value->item_description;
				$items[$key]['customer_code'] = $value->customer_code;
				$items[$key]['is_cancelled'] = 1;
				if($intersect_code_item[$key] == $value->item_code && $intersect_index_item[$key] == $value->index_list){
					$items[$key]['item_order_quantity'] = ($value->item_quantity - $purchase_return_[$i]->item_quantity) * -1;
					$i++;
				}else{
					$items[$key]['item_order_quantity'] = ($value->item_quantity) * -1;
				}
			}
			//DIKURANGI
		}else{
			$purchase_ = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
			foreach ($purchase_ as $key => $value) {
				$items[$key]['id'] = $value->id;
				$items[$key]['item_id'] = $value->item_id;
				$items[$key]['invoice_code'] = $value->invoice_code;
				$items[$key]['index_list'] = $value->index_list;
				$items[$key]['item_code'] = $value->item_code;
				$items[$key]['item_name'] = $value->item_name;
				$items[$key]['item_quantity_current'] = $this->items_model->getByCodeItem($value->item_code, 'quantity');
				$items[$key]['item_unit'] = $value->item_unit;
				$items[$key]['item_capital_price'] = $value->item_capital_price;
				$items[$key]['item_selling_price'] = $value->item_selling_price;
				$items[$key]['item_discount'] = $value->item_discount;
				$items[$key]['total_price'] = $value->total_price;
				$items[$key]['item_description'] = $value->item_description;
				$items[$key]['customer_code'] = $value->customer_code;
				$items[$key]['is_cancelled'] = 1;
				$items[$key]['item_order_quantity'] = ($value->item_quantity);
			}
			//DITAMBAH
		}
		$payment['is_cancelled'] = 1;
		try {
			$this->create_item_history( $items, ['CANCELED', 'CANCELED']);
			$this->create_or_update_invoice($payment);
			$this->create_or_update_list_item_transcation($items);
			$this->update_items($items);
		} catch (\Throwable $th) {
			echo "<pre>";
			var_dump($th);
			echo "</pre>";
			die();
		}

		$this->activity_model->add("Cancel purchasing, #" . $this->data['invoice_code'], (array) $payment);
		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Cancel Purchase Successfully');

		redirect('invoice/purchase/list');
	}

	protected function create_item_history($data, $status_type)
	{
		$item = array();
		$data = json_encode($data, true);
		$data = json_decode($data, true);
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['invoice_reference'] = $this->data['invoice_code'];
			$request[$key]['item_id'] = $item[$key]->id;
			$request[$key]['item_code'] = $value['item_code'];
			$request[$key]['item_name'] = $value['item_name'];
			if ($value['id']) {
				$request[$key]['item_quantity'] = $value['item_quantity_current'];
			} else {
				$request[$key]['item_quantity'] = $item[$key]->quantity;
			}
			$request[$key]['item_order_quantity'] = abs($value['item_order_quantity']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			$request[$key]['status_type'] = ($value['id']) ? $status_type[1] : $status_type[0];
			$request[$key]['status_transaction'] = __CLASS__;
			$request[$key]['created_by'] = logged('id');
			$this->items_history_model->create($request[$key]);
		}
		return $request;
		// // return $this->items_history_model->create_batch($request); // NOT USED HERE...  DIFFERENT CONDITION TO USE HERE...
	}

	protected function create_or_update_list_item_transcation($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['index_list'] = $key;
			$request[$key]['invoice_code'] = $this->data['invoice_code'];
			$request[$key]['item_id'] = $value['item_id'];
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			$request[$key]['item_selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_current_quantity'] = $item[$key]->quantity;
			$request[$key]['item_quantity'] = abs($value['item_order_quantity']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			if ($value['item_order_quantity'] <= 0) {
				$request[$key]['item_status'] = 'OUT';
			}else{
				$request[$key]['item_status'] = 'IN';
			}
			$request[$key]['item_description'] = $value['item_description'];
			$request[$key]['customer_code'] = $value['customer_code'];
			if ($value['id']) {
				$request[$key]['id'] = $value['id'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$request[$key]['is_cancelled'] = $value['is_cancelled'];
				$data_positif[] = $request[$key];
				// unset($data_positif[$key]['id']);
			} else {
				$request[$key]['created_by'] = logged('id');
				$data_negatif[$key] = $request[$key];
				unset($data_negatif[$key]['id']);
			}
		}
		if (@$data_negatif) {
			if ($this->transaction_item_model->create_batch($data_negatif) && $this->transaction_item_model->update_batch($data_positif, 'id')) {
				return true;
			}
		} else {
			$this->transaction_item_model->update_batch($data_positif, 'id');
			return true;
		}
		return false;
	}

	protected function create_or_update_invoice($data)
	{
		$response = $this->purchase_model->get_invoice_purchasing_by_code($this->data['invoice_code']);

		$request['total_price'] = setCurrency($data['total_price']);
		$request['discounts'] = setCurrency($data['discounts']);
		$request['shipping_cost'] = setCurrency($data['shipping_cost']);
		$request['other_cost'] = setCurrency($data['other_cost']);
		$request['grand_total'] = setCurrency($data['grand_total']);
		$request['supplier'] = $data['supplier'];
		$request['payment_type'] = $data['payment_type'];
		$request['status_payment'] = $data['status_payment'];
		$request['date_start'] = $data['date_start'];
		$request['date_due'] = $data['date_due'];
		$request['note'] = $data['note'];
		if ($response) {
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->purchase_model->update_by_code($this->data['invoice_code'], $request);
		} else {
			$request['invoice_code'] = $this->data['invoice_code'];
			$request['created_by'] = logged('id');
			//	
			return $this->purchase_model->create($request);
		}
		return $request;
	}

	protected function update_items($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			if ($value['id']) {
				$request[$key]['quantity'] = $item[$key]->quantity + ($value['item_order_quantity'] - $value['item_order_quantity_current']);
				$request[$key]['capital_price'] = setCurrency($value['item_capital_price']);
			} else {
				$request[$key]['quantity'] = $item[$key]->quantity + $value['item_order_quantity'];
				$request[$key]['capital_price'] = (setCurrency($value['item_capital_price']) > $item[$key]->capital_price) ? setCurrency($value['item_capital_price']) : $item[$key]->capital_price;
			}
			$request[$key]['selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['updated_by'] = logged('id');
			$this->items_model->update($item[$key]->id, $request[$key]);
		}
		return true;
		// return $this->items_model->update_batch($request, 'item_code');
	}
	
	public function serverside_datatables_data_purchase()
	{
		ifPermissions('purchase_list');
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
			// $this->db->where("purchasing.created_at BETWEEN $dateStart AND $dateFinal", null, FALSE);
		}
		$records = $this->db->get('invoice_purchasing purchasing')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		
		## get all code invoice
		$this->db->select('purchasing.invoice_code');
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
		$invoices_code = $records;

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
		purchasing.is_cancelled as is_cancelled, 
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
		if ($dateStart != '') {
			$this->db->where("purchasing.created_at >=", $dateStart);
			$this->db->where("purchasing.created_at <=", $dateFinal);
			// $this->db->where("purchasing.created_at BETWEEN $dateStart AND $dateFinal", null, FALSE);
		}
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