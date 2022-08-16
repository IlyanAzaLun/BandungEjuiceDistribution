<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Invoice_controller.php';
class Order extends Invoice_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Order';
		$this->page_data['page']->menu = 'Sale';
	}
	public function index()
	{
		$this->list();
	}

	public function list()
	{
		ifPermissions('order_list');
		$this->page_data['title'] = 'order_list';
		$this->page_data['page']->submenu = 'order_list';
		$this->load->view('invoice/order/list', $this->page_data);
	}

	public function create()
	{
		ifPermissions('order_create');
		$this->page_data['title'] = 'order_create';
		$this->page_data['page']->submenu = 'order_list';
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['modals'] = (object) array(
				'id' => 'import_order_items',
				'title' => 'Modals Import Order Items',
				'link' => "invoice/order/create_import",
				'content' => 'upload',
				'btn' => 'btn-primary',
				'submit' => 'Yes do it',
			);
			// $this->load->view('invoice/order/create', $this->page_data);
			$this->load->view('invoice/order/create_order', $this->page_data);
			$this->load->view('includes/modals', $this->page_data);
		}else{
			$this->data['order_code'] = $this->order_model->get_code_order_sale();
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
					"customer_code" => post('customer_code'),
					'is_have' => post('is_have'),
				);
			}
			//information payment
			$payment = array(
				'customer' => post('customer_code'),
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
				'is_have' => post('is_have'),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note') == false? null : strtoupper(post('note')),
			);
			// CREATE
			$result = $this->validation_items($items);
			$error = $result['error'];
			$success = $result['success'];
			$items = array_values($success);
			$failed = array_values($error);
			$error = array_column($failed, 'item_name');

			if(!$items){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quantity is over: '.json_encode($error, true));
				redirect("invoice/order/create");
				return false;
			}
			//
			$this->db->trans_start(TRUE);
			$result_payment = $this->create_or_update_order($payment);
			$this->create_or_update_list_item_order_sale($items);
			$this->update_items($items);
			
			$this->activity_model->add("Create Order, #" . $this->data['order_code'], (array) $payment);
			$this->db->trans_complete();
			$order_code = ($this->order_model->getRowById($result_payment, 'order_code'));
			if($error){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quantity is over: '.json_encode($error));
				redirect("invoice/order/edit?id=$order_code");
				return false;
			}
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Create Order Successfully');
			redirect('invoice/order/list');
		}
	}

	public function create_import()
	{
		ifPermissions('upload_file');
        if ($_FILES['file']['name'] == "") {
            $this->session->set_flashdata('alert-type', 'danger');
            $this->session->set_flashdata('alert', 'Empty File, Please select file to import');
            redirect('invoice/order/create');
			die();
        }
		$this->page_data['data'] = $this->uploadlib->uploadFile();
		$this->page_data['title'] = 'order_create';
		$this->page_data['page']->submenu = 'order_list';
		
		$this->page_data['modals'] = (object) array(
			'id' => 'import_order_items',
			'title' => 'Modals Import Order Items',
			'link' => "invoice/order/create_import",
			'content' => 'upload',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('invoice/order/create_import_items', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);

	}

	public function edit()
	{
		ifPermissions('order_edit');
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['title'] = 'order_edit';
			$this->page_data['page']->submenu = 'order_list';
			$this->page_data['invoice'] = $this->order_model->get_order_selling_by_code(get('id'));
			$this->page_data['items'] = $this->order_list_item_model->get_order_item_by_code_order(get('id'));
			if(!hasPermissions('warehouse_order_list')){
				if( $this->page_data['invoice']->is_have != logged('id') ){
					$this->session->set_flashdata('alert-type', 'danger');
					$this->session->set_flashdata('alert', 'Worng Information');
					redirect('invoice/order/list');
					die();
				}
			}
			$this->page_data['modals'] = (object) array(
				'id' => 'modal-remove-order',
				'title' => 'Modals confirmation',
				'link' => "invoice/orders/items/remove_item_from_list_order_transaction?id=".get('id'),
				'content' => 'delete',
				'btn' => 'btn-danger',
				'submit' => 'Yes do it',
			);
			$this->load->view('invoice/order/edit', $this->page_data);
			$this->load->view('includes/modals');
		} else {
			$this->data['order_code'] = get('id');
			$items = array();
			foreach (post('item_code') as $key => $value) {
				$items[$key]['id'] = post('id')[$key];
				$items[$key]['item_id'] = post('item_id')[$key];
				$items[$key]['item_code'] = post('item_code')[$key];
				$items[$key]['item_name'] = post('item_name')[$key];
				$items[$key]['item_quantity'] = post('item_quantity')[$key];
				$items[$key]['item_quantity_current'] = post('item_quantity_current')[$key];
				$items[$key]['item_order_quantity'] =  post('item_order_quantity')[$key];
				$items[$key]['item_order_quantity_current'] = post('item_order_quantity_current')[$key];
				$items[$key]['item_unit'] = post('item_unit')[$key];
				$items[$key]['item_capital_price'] = post('item_capital_price')[$key];
				$items[$key]['item_selling_price'] = post('item_selling_price')[$key];
				$items[$key]['item_discount'] = post('item_discount')[$key];
				$items[$key]['total_price'] = post('total_price')[$key];
				$items[$key]['item_description'] = post('description')[$key];
				$items[$key]['customer_code'] = post('customer_code');
				if($items[$key]['item_order_quantity'] == $items[$key]['item_order_quantity_current'] && setCurrency($items[$key]['item_selling_price']) == $this->order_list_item_model->getRowById($items[$key]['id'], 'item_selling_price')){
					unset($items[$key]);
				}
			}
			$items = array_values($items);
			//information payment
			$payment = array(
				'customer' => post('customer_code'),
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
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note') == false? null : strtoupper(post('note')),
				'is_have' => post('is_have'),
				'is_confirmed' => null,
			);
			// EDIT
			
			// CREATE
			$result = $this->validation_items($items);
			$error = $result['error'];
			$success = $result['success'];
			$items = array_values($success);
			$failed = array_values($error);
			$error = array_column($failed, 'item_name');

			if(!$items){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quantity is over: '.json_encode($error, true));
				redirect("invoice/order/create");
				return false;
			}
			var_dump($result);
			die();
			//
			$this->create_or_update_order($payment);
			$this->create_or_update_list_item_order_sale($items);
			$this->update_items($items);
			
			$this->activity_model->add("Update Order, #" . $this->data['order_code'], (array) $payment);
			if($error){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quantity is over: '.json_encode($error));
				redirect("invoice/order/edit?id=$order_code");
				return false;
			}
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Update Order Successfully');
			// redirect('invoice/order/edit?id='.get('id'));
			redirect('invoice/order/list');
		}
	}

	private function validation_items($data)
	{
		$item = array();
		$result = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			if (($item[$key]->quantity - $value['item_order_quantity']) < 0) {
				$result['error'][$key] = $data[$key];
				unset($data[$key]);
				continue;
			}
			$result['success'][$key] = $data[$key];
		}
		return $result;
	}

	public function info()
	{
		$this->page_data['title'] = 'order_edit';
		$this->page_data['page']->submenu = 'order_list';
		$this->page_data['invoice'] = $this->order_model->get_order_selling_by_code(get('id'));
		$this->page_data['items'] = $this->order_list_item_model->get_order_item_by_code_order(get('id'));

		$this->page_data['modals'] = (object) array(
			'id' => 'modal-remove-order',
			'title' => 'Modals confirmation',
			'link' => "invoice/orders/items/remove_item_from_list_order_transaction?id=".get('id'),
			'content' => 'delete',
			'btn' => 'btn-danger',
			'submit' => 'Yes do it',
		);
		$this->load->view('invoice/order/edit', $this->page_data);
		$this->load->view('includes/modals');
	}

	public function cancel()
	{
		echo '<pre>';
		$order = $this->order_model->get_order_selling_by_code(get('id'));
		$this->order_model->update_by_code(get('id'), array('is_cancelled' => true));
		$items_order = $this->order_list_item_model->get_order_item_by_code_order(get('id'));
		$item = [];
		foreach ($items_order as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value->item_code])->row());
			$request[$key]['id'] = $item[$key]->id;
			$request[$key]['quantity'] = $item[$key]->quantity + $value->item_order_quantity;
			$items_order[$key]->is_cancelled = 1;
			$items_order[$key]->updated_at = date('Y-m-d H:i:s');
			$items_order[$key]->updated_by = logged('id');
			unset($items_order[$key]->name);
			// Update item_quantity
			$this->items_model->update($item[$key]->id, $request[$key]);
		}
		$response = $this->order_list_item_model->update_batch($items_order, 'id');
		//$response = $this->items_model->update_batch($request, 'id');
		if ($response) {
			$this->activity_model->add("Delete Order, #" . get('id'), (array) $order);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Update Order Successfully');
			redirect('invoice/order/list');
			return true;
			die();
		}else{
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Update Order Successfully');
			redirect('invoice/order/info?id='.get('id'));
			return false;
			die();
		}
		echo '</hr>';
	}

	protected function create_or_update_list_item_order_sale($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['order_code'] = $this->data['order_code'];
			$request[$key]['item_id'] = $value['item_id'];
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			$request[$key]['item_selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['item_total_price'] = setCurrency($value['total_price']);
			$request[$key]['item_description'] = $value['item_description'];
			$request[$key]['customer_code'] = $value['customer_code'];
			if ($value['id']) {
				$request[$key]['id'] = $value['id'];
				$request[$key]['item_quantity'] = $item[$key]->quantity + $value['item_order_quantity_current'];
				$request[$key]['item_order_quantity'] = abs($value['item_order_quantity']);
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$request[$key]['is_cancelled'] = $value['is_cancelled'];
				$data_positif[] = $request[$key];
			} else {
				$request[$key]['index_list'] = $key;
				$request[$key]['item_quantity'] = $item[$key]->quantity;
				$request[$key]['item_order_quantity'] = abs($value['item_order_quantity']);
				$request[$key]['created_by'] = logged('id');

				$data_negatif[$key] = $request[$key];
				unset($data_negatif[$key]['id']);
			}
		}
		if (@$data_negatif) {
			if ($this->order_list_item_model->create_batch($data_negatif) && $this->order_list_item_model->update_batch($data_positif, 'id')) {
				return true;
			}
		} else {
			$this->order_list_item_model->update_batch($data_positif, 'id');
			return true;
		}
		return false;
	}

	protected function create_or_update_order($data)
	{
		$response = $this->order_model->get_order_selling_by_code($this->data['order_code']);

		$request['total_price'] = setCurrency($data['total_price']);
		$request['discounts'] = setCurrency($data['discounts']);
		$request['shipping_cost'] = setCurrency($data['shipping_cost']);
		$request['other_cost'] = setCurrency($data['other_cost']);
		$request['grand_total'] = setCurrency($data['grand_total']);
		$request['customer'] = $data['customer'];
		$request['payment_type'] = $data['payment_type'];
		$request['note'] = $data['note'];
		$request['created_at'] = $data['created_at'];		
		$request['is_have'] = $data['is_have']?$data['is_have']:logged('id');
		if ($response) {
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['is_confirmed'] = $data['is_confirmed'];
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->order_model->update_by_code($this->data['order_code'], $request);
		} else {
			$request['order_code'] = $this->data['order_code'];
			$request['created_by'] = logged('id');
			//	
			return $this->order_model->create($request);
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
				$request[$key]['quantity'] = $item[$key]->quantity - ($value['item_order_quantity'] - $value['item_order_quantity_current']);
			} else {
				$request[$key]['quantity'] = $item[$key]->quantity - $value['item_order_quantity'];
			}
			$request[$key]['updated_by'] = logged('id');
			$this->items_model->update($item[$key]->id, $request[$key]);
		}
		return $request;
		// return $this->items_model->update_batch($request, 'item_code');
	}
	
	public function serverside_datatables_data_order()
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
		$haspermission = hasPermissions('warehouse_order_list');
		$is_super_user = hasPermissions('example');

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$this->db->where('is_created', 0);
		if(!$haspermission){
			$this->db->where("created_by", $logged);
			$this->db->where("is_cancelled", 0);
		}
		$records = $this->db->get('order_sale')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('customer_information customer', 'customer.customer_code = order.customer', 'left');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('order.order_code', $searchValue, 'both');
			$this->db->or_like('order.customer', $searchValue, 'both');
			$this->db->or_like('order.note', $searchValue, 'both');
			$this->db->or_like('order.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("order.created_at >=", $dateStart);
			$this->db->where("order.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("order.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->group_start();
			$this->db->where("order.created_by", $logged);
			$this->db->or_where("order.is_have", $logged);
			$this->db->group_end();
		}
		if(!$is_super_user){
			$this->db->where("order.is_cancelled", 0);
		}
		$this->db->where('order.is_created', 0);
		$records = $this->db->get('order_sale order')->result();
		$totalRecordwithFilter = $records[0]->allcount;
		## Fetch records
		$this->db->select('
		order.id as id, 
		order.order_code as order_code, 
		order.total_price as total_price, 
		order.discounts as discounts, 
		order.shipping_cost as shipping_cost, 
		order.other_cost as other_cost, 
		order.payment_type as payment_type, 
		order.grand_total as grand_total, 
		order.note as note, 
		order.created_at as created_at, 
		order.updated_at as updated_at, 
		order.created_by as created_by, 
		order.is_confirmed as is_confirmed,
		order.is_cancelled as is_cancelled, 
		order.is_created as is_created, 
		is_haved.name as is_have_name, 
		order.is_have as is_have, 
		customer.customer_code as customer_code, 
		customer.store_name as store_name, 
		user_created.id as user_id, 
		user_created.name as user_order_create_by,
		user_updated.name as user_order_update_by');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('order.order_code', $searchValue, 'both');
			$this->db->or_like('order.customer', $searchValue, 'both');
			$this->db->or_like('order.note', $searchValue, 'both');
			$this->db->or_like('order.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		$this->db->join('users user_created', 'user_created.id = order.created_by', 'left');
		$this->db->join('users user_updated', 'user_updated.id = order.updated_by', 'left');
		$this->db->join('users is_haved', 'is_haved.id = order.is_have', 'left');
		$this->db->join('customer_information customer', 'customer.customer_code = order.customer', 'left');
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("order.created_at >=", $dateStart);
			$this->db->where("order.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("order.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->group_start();
			$this->db->where("order.created_by", $logged);
			$this->db->or_where("order.is_have", $logged);
			$this->db->group_end();
		}	
		if(!$is_super_user){
			$this->db->where("order.is_cancelled", 0);
		}
		$this->db->where('order.is_created', 0);
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('order_sale order')->result();

		$data = array();
		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->id,
				'order_code' => $record->order_code,
				'customer_code' => $record->customer_code,
				'store_name' => $record->store_name,
				'total_price' => $record->total_price,
				'discounts' => $record->discounts,
				'shipping_cost' => $record->shipping_cost,
				'other_cost' => $record->other_cost,
				'payment_type' => lang($record->payment_type),
				'grand_total' => $record->grand_total,
				'note' => $record->note,
				'created_at' => $record->created_at,
				'updated_at' => $record->updated_at,
				'user_id' => $record->user_id,
				'is_confirmed' => $record->is_confirmed,
				'is_cancelled' => $record->is_cancelled,
				'is_created' => $record->is_created,
				'is_have_name' => $record->is_have_name,
				'is_have' => $record->is_have,
				'user_order_create_by' => $record->user_order_create_by,
				'user_order_update_by' => $record->user_order_update_by,
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

	public function serverside_datatables_data_order_is_created()
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
		$haspermission = hasPermissions('warehouse_order_list');

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$this->db->where('is_created', 1);
		$records = $this->db->get('order_sale')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('customer_information customer', 'customer.customer_code = order.customer', 'left');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('order.order_code', $searchValue, 'both');
			$this->db->or_like('order.customer', $searchValue, 'both');
			$this->db->or_like('order.note', $searchValue, 'both');
			$this->db->or_like('order.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("order.created_at >=", $dateStart);
			$this->db->where("order.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("order.created_at", date("Y-m"), 'after');
		}
		$this->db->where('order.is_created', 1);
		$this->db->where('order.is_cancelled', 0);
		$records = $this->db->get('order_sale order')->result();
		$totalRecordwithFilter = $records[0]->allcount;
		## Fetch records
		$this->db->select('
		order.id as id, 
		order.order_code as order_code, 
		order.total_price as total_price, 
		order.discounts as discounts, 
		order.shipping_cost as shipping_cost, 
		order.other_cost as other_cost, 
		order.payment_type as payment_type, 
		order.grand_total as grand_total, 
		order.note as note, 
		order.created_at as created_at, 
		order.updated_at as updated_at, 
		order.created_by as created_by, 
		order.is_confirmed as is_confirmed,
		order.is_cancelled as is_cancelled, 
		order.is_created as is_created, 
		customer.customer_code as customer_code, 
		customer.store_name as store_name, 
		user_created.id as user_id, 
		user_created.name as user_order_create_by,
		user_updated.name as user_order_update_by');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('order.order_code', $searchValue, 'both');
			$this->db->or_like('order.customer', $searchValue, 'both');
			$this->db->or_like('order.note', $searchValue, 'both');
			$this->db->or_like('order.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		$this->db->join('users user_created', 'user_created.id = order.created_by', 'left');
		$this->db->join('users user_updated', 'user_updated.id = order.updated_by', 'left');
		$this->db->join('customer_information customer', 'customer.customer_code = order.customer', 'left');
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("order.created_at >=", $dateStart);
			$this->db->where("order.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("order.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->where("order.created_by", $logged);
		}
		$this->db->where('order.is_created', 1);
		$this->db->where('order.is_cancelled', 0);
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('order_sale order')->result();

		$data = array();
		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->id,
				'order_code' => $record->order_code,
				'customer_code' => $record->customer_code,
				'store_name' => $record->store_name,
				'total_price' => $record->total_price,
				'discounts' => $record->discounts,
				'shipping_cost' => $record->shipping_cost,
				'other_cost' => $record->other_cost,
				'payment_type' => lang($record->payment_type),
				'grand_total' => $record->grand_total,
				'note' => $record->note,
				'created_at' => $record->created_at,
				'updated_at' => $record->updated_at,
				'user_id' => $record->user_id,
				'is_confirmed' => $record->is_confirmed,
				'is_cancelled' => $record->is_cancelled,
				'is_created' => $record->is_created,
				'user_order_create_by' => $record->user_order_create_by,
				'user_order_update_by' => $record->user_order_update_by,
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

	public function is_confirmation()
	{
		ifPermissions('warehouse_order_list');
		$this->form_validation->set_rules('id', lang('id_order'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Failed, need id Order to send this request!');
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}else{
			$request = array( 'is_confirmed' => 1, 'updated_by' =>  logged('id'));
			$this->db->where('order_code', post('id'));
			$result = $this->db->update('order_sale', $request);
			if($result){
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Successfully');
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Failed');
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			}
		}
	}
}