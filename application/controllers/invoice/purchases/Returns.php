<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Purchase.php';
class Returns extends Purchase
{
	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Purchase Returns';
		$this->page_data['page']->menu = 'Purchase';
	}

	public function index()
	{
		ifPermissions('purchase_returns');
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		$this->page_data['title'] = 'purchase_returns';
		$this->page_data['page']->submenu = 'returns';
		$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
		$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
		if(!($this->page_data['invoice'] && get('id'))){
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', lang('error_worng_information'));
			redirect('invoice/purchase/list');	
		}
		if ($this->form_validation->run() == false) {
			$this->load->view('invoice/purchase/form', $this->page_data);
		} else {
			$this->data['invoice_code_parents'] = $this->input->get('id');
			$this->data['invoice_code'] = str_replace('INV','RET', $this->data['invoice_code_parents']);
			// information invoice
			$date = preg_split('/[-]/', $this->input->post('date_due'));
			$this->data['date'] = array(
				'date_start' => trim(str_replace('/', '-', $date[0])), 
				'date_due'	 => trim(str_replace('/', '-', $date[1]))
			);
			//information items
			$items = array();
			$item = array();
			foreach (post('item_code') as $key => $value) {
				// array_push($item, $this->db->get_where('items', ['item_code' => post('item_code')[$key]])->row()); // Primary for find items with code item
				$items[$key]['id'] = post('id')[$key];
				$items[$key]['index_list'] = post('index_list')[$key];
				$items[$key]['item_id'] = post('item_id')[$key];
				$items[$key]['item_code'] = post('item_code')[$key];
				$items[$key]['item_name'] = post('item_name')[$key];
				$items[$key]['item_quantity_current'] = post('item_quantity_current')[$key];
				$items[$key]['item_quantity'] = post('item_quantity')[$key];
				$items[$key]['item_order_quantity_current'] = post('item_order_quantity_current')[$key];
				$items[$key]['item_order_quantity'] = post('item_order_quantity')[$key];
				$items[$key]['item_unit'] = post('item_unit')[$key];
				$items[$key]['item_capital_price'] = post('item_capital_price')[$key];
				$items[$key]['item_selling_price'] = post('item_selling_price')[$key];
				$items[$key]['item_discount'] = (post('total_price')[$key] == 0)?setCurrency(post('item_capital_price')[$key])*(post('item_order_quantity_current')[$key]-post('item_order_quantity')[$key]):post('item_discount')[$key];
				$items[$key]['total_price'] = post('total_price')[$key];
				$items[$key]['item_description'] = post('description')[$key];
				$items[$key]['customer_code'] = post('supplier_code');
				if($items[$key]['item_order_quantity'] == "0"){
					unset($items[$key]);
				}
			}
			$items = array_values($items);

			if(sizeof($items) < 1){
				$this->session->set_flashdata('alert-type', 'warning');
				$this->session->set_flashdata('alert', lang('returns_failed'));

				redirect('invoice/purchases/returns?id='.get('id'));
				die();
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
				'date_start' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_due'])),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note'),
				'is_consignment' => post('is_consignment'),
				'is_child' => 1,
				'is_cancelled' => 0,
			);
			try {
				echo '<pre>';
				// CREATE
				$this->create_item_history($items, ['RETURNS', 'RETURNS']);
				$this->create_or_update_invoice($payment);
				// $this->create_or_update_invoice_parent($payment);
				$this->update_items($items);
				$this->create_or_update_list_item_transcation($items);
				$this->create_or_update_list_item_fifo($items);
				echo '</pre>';
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			$this->activity_model->add("Create Purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Return Purchasing Successfully');

			redirect('invoice/purchase/list');
		}
	}
	
	public function list()
	{
		ifPermissions('purchase_return_list');
		$this->page_data['title'] = 'returns';
		$this->page_data['page']->submenu = 'list_purchase_returns';
		$this->load->view('invoice/purchase/returns_list', $this->page_data);
	}

	public function edit()
	{
		ifPermissions('purchase_returns_edit');
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		$this->page_data['title'] = 'purchase_returns_edit';
		$this->page_data['page']->submenu = 'returns_edit';
		$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
		$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
		if(!($this->page_data['invoice'] && get('id'))){
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', lang('error_worng_information'));
			redirect('invoice/purchase/list');
			die();
		}
		if ($this->form_validation->run() == false) {
			$this->load->view('invoice/purchase/returns_edit', $this->page_data);
		} else {			
			$this->data['invoice_code'] = $this->input->get('id');
			$this->data['invoice_code_parents'] = str_replace('RET','INV', $this->data['invoice_code']);
			// information invoice
			$date = preg_split('/[-]/', $this->input->post('date_due'));
			$this->data['date'] = array(
				'date_start' => trim(str_replace('/', '-', $date[0])), 
				'date_due'	 => trim(str_replace('/', '-', $date[1]))
			);
			//information items
			$items = array();
			$item = array();
			foreach (post('item_code') as $key => $value) {
				// array_push($item, $this->db->get_where('items', ['item_code' => post('item_code')[$key]])->row()); // Primary for find items with code item
				$items[$key]['id'] = post('id')[$key];
				$items[$key]['index_list'] = post('index_list')[$key];
				$items[$key]['item_id'] = post('item_id')[$key];
				$items[$key]['item_code'] = post('item_code')[$key];
				$items[$key]['item_name'] = post('item_name')[$key];
				$items[$key]['item_quantity_current'] = post('item_quantity_current')[$key];
				$items[$key]['item_quantity'] = post('item_quantity')[$key];
				$items[$key]['item_order_quantity_current'] = post('item_order_quantity_current')[$key];
				$items[$key]['item_order_quantity'] = post('item_order_quantity')[$key];
				$items[$key]['item_unit'] = post('item_unit')[$key];
				$items[$key]['item_capital_price'] = post('item_capital_price')[$key];
				$items[$key]['item_selling_price'] = post('item_selling_price')[$key];
				$items[$key]['item_discount'] = post('item_discount')[$key];
				$items[$key]['total_price'] = post('total_price')[$key];
				$items[$key]['item_description'] = post('description')[$key];
				$items[$key]['customer_code'] = post('supplier_code');
				if($items[$key]['item_order_quantity'] == "0"){
					unset($items[$key]);
				}
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
				'date_start' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_due'])),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note'),
				'is_cancelled' => 0,
			);
			
			$items = array_values($items);

			if(sizeof($items) < 1){
				$this->create_or_update_invoice($payment);
				$this->session->set_flashdata('alert-type', 'warning');
				$this->session->set_flashdata('alert', lang('returns_failed'));

				redirect('invoice/purchases/returns/edit?id='.get('id'));
				die();
			}
			try {
				// EDIT
				echo '<pre>';
				$this->create_item_history($items, ['RETURNS', 'RETURNS']);
				$this->create_or_update_invoice($payment);
				// $this->create_or_update_invoice_parent($payment);
				$this->update_items($items);
				$this->create_or_update_list_item_transcation($items);
				var_dump($this->create_or_update_list_item_fifo($items));
				var_dump($this->db->last_query());
				echo '</pre>';
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			$this->activity_model->add("Create Returns Purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Returns Purchasing Successfully');

			redirect('invoice/purchase/list');
		}
	}
	
	public function info()
	{
		ifPermissions('purchase_returns_info');
		echo __FUNCTION__;
	}
	
	public function cancel()
	{
		ifPermissions('purchase_returns_cancel');
		echo __FUNCTION__;
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
		$request['created_at'] = $data['created_at'];
		$request['is_cancelled'] = $data['is_cancelled'];
		$this->purchase_model->update_by_code($this->data['invoice_code_parents'], array('have_a_child' => $this->data['invoice_code']));
		if ($response) {
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->purchase_model->update_by_code($this->data['invoice_code'], $request);
		} else {
			$request['invoice_code'] = $this->data['invoice_code'];
			$request['created_by'] = logged('id');
			$request['is_consignment'] = $data['is_consignment'];
			$request['is_child'] = $data['is_child'];
			//	
			return $this->purchase_model->create($request);
		}
		return $request;
	}

	protected function create_or_update_invoice_parent($data)
	{
		$response = $this->purchase_model->get_invoice_purchasing_by_code($this->data['invoice_code_parents']);

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
		$request['created_at'] = $data['created_at'];
		if ($response) {
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->purchase_model->update_by_code($this->data['invoice_code_parents'], $request);
		} else {
			$request['invoice_code'] = $this->data['invoice_code_parents'];
			$request['created_by'] = logged('id');
			//	
			return $this->purchase_model->create($request);
		}
		return $request;
	}

	protected function create_or_update_list_item_transcation($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['index_list'] = $value['index_list'];
			$request[$key]['invoice_code'] = $this->data['invoice_code'];
			$request[$key]['item_id'] = $value['item_id'];
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			$request[$key]['item_selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_current_quantity'] = $item[$key]->quantity;
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			$request[$key]['item_status'] = 'OUT';
			$request[$key]['item_description'] = $value['item_description'];
			$request[$key]['customer_code'] = $value['customer_code'];
			if ($value['id']) {
				$request[$key]['id'] = $value['id'];
				$request[$key]['item_quantity'] = $value['item_order_quantity']+$value['item_order_quantity_current'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$data_positif[] = $request[$key];
				$this->transaction_item_model->update($request[$key]['id'], $request[$key]);
				// unset($data_positif[$key]['id']);
			} else {
				$request[$key]['item_quantity'] = $value['item_order_quantity'];
				$request[$key]['created_by'] = logged('id');
				$data_negatif[$key] = $request[$key];
				$this->transaction_item_model->create($request[$key]);
				// unset($data_negatif[$key]['id']);
			}
		}
		return $request;
	}
	
	protected function create_or_update_list_item_fifo($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['invoice_code'] = $this->data['invoice_code'];
			$request[$key]['item_id'] = $value['item_id'];
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			$request[$key]['customer_code'] = $value['customer_code'];
			$request[$key]['parent'] = $this->data['invoice_code_parents'];
			if ($value['id']) {
				$request[$key]['id'] = $value['id'];
				$request[$key]['item_quantity'] = $value['item_order_quantity']+$value['item_order_quantity_current'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$this->items_fifo_model->update($value['id'], $request[$key]);
				$data_positif[] = $request[$key];
			} else {
				$request[$key]['item_quantity'] = $value['item_order_quantity'];
				$request[$key]['created_at'] = @$value['created_at'];
				$request[$key]['created_by'] = logged('id');
				$this->items_fifo_model->create($request[$key]);
				$data_negatif[] = $request[$key];
			}
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
				$request[$key]['quantity'] = ($item[$key]->quantity + $value['item_order_quantity_current']) - ($value['item_order_quantity'] + $value['item_order_quantity_current']);
				$request[$key]['capital_price'] = setCurrency($value['item_capital_price']);
			} else {
				$request[$key]['quantity'] = $item[$key]->quantity - $value['item_order_quantity'];
				$request[$key]['capital_price'] = (setCurrency($value['item_capital_price']) > $item[$key]->capital_price) ? setCurrency($value['item_capital_price']) : $item[$key]->capital_price;
			}
			$request[$key]['selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['updated_by'] = logged('id');
			$this->items_model->update($item[$key]->id, $request[$key]);
		}
		return true;
		// return $this->items_model->update_batch($request, 'item_code');
	}

	
	public function serverside_datatables_data_purchase_returns()
	{
		ifPermissions('purchase_return_list');
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

/* End of file Purchasing.php */
/* Abstract file Location: ./application/controllers/Invoice.php */
/* Location: ./application/controllers/invoices/Purchasing.php */