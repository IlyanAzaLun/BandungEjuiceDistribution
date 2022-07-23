<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Warehouse Validation';
		$this->page_data['page']->menu = 'Warehouse';
	}

    public function index()
    {
        $this->list();
    }

	public function list()
	{
		ifPermissions('warehouse_order_list');
		$this->page_data['title'] = 'order_list';
		$this->page_data['page']->submenu = 'order_list';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'invoice/order/is_confirmation',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/warehouse/list', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}
	public function serverside_datatables_list_data_order_warehouse()
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
		$logged = logged('id');
		$haspermission = hasPermissions('warehouse_order_list');

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$this->db->where('is_created', 0);
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
		}
		// else{
		// 	$this->db->like("order.created_at", date("Y-m"), 'after');
		// }
		$this->db->where('order.is_created', 0);
		$this->db->where('order.is_confirmed', null);
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
		}
		// else{
		// 	$this->db->like("order.created_at", date("Y-m"), 'after');
		// }
		if(!$haspermission){
			$this->db->where("order.created_by", $logged);
		}
		$this->db->where('order.is_created', 0);
		$this->db->where('order.is_confirmed', null);
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

	public function available()
	{
		ifPermissions('warehouse_order_validation_available');
		$this->page_data['title'] = 'order_list_item_available';
		$this->page_data['page']->submenu = 'order_list';
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['invoice'] = $this->order_model->get_order_selling_by_code(get('id'));
			$this->page_data['items'] = $this->order_list_item_model->get_order_item_by_code_order_warehouse(get('id'));
			$this->load->view('validation/warehouse/available', $this->page_data);
		}else{
			$this->data['order_code'] = $this->input->get('id');
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
				$items[$key]['customer_code'] = post('customer_code');
				$items[$key]['status_available'] = post('status_available')[$key];
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
				'note' => post('note'),
				'created_by' => logged('id'),
			);
			echo '<pre>';
			$this->create_or_update_list_item_order_sale($items);
			$this->create_or_update_order($payment);

			$this->db->trans_start();
			$request = array( 'is_confirmed' => 0, 'updated_by' =>  logged('id'));
			if(post('is_completey_clear')){
				$request = array( 'is_confirmed' => 1, 'updated_by' =>  logged('id'));
			}
			$this->db->where('order_code', $this->data['order_code']);
			$this->db->update('order_sale', $request);
			$this->db->trans_complete();
			echo '</pre>';
		
			$this->activity_model->add("Update Status Available, #" . $this->data['order_code'], (array) $items);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Update Order Successfully');
			redirect('validation/warehouse/list');
		}
	}

	public function report()
	{
		ifPermissions('warehouse_order_list');
		$this->page_data['title'] = 'order_report';
		$this->page_data['page']->submenu = 'order_report';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'invoice/order/is_confirmation',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/warehouse/report', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	public function is_confirmation()
	{
		ifPermissions('warehouse_order_is_confirmation');
		$this->page_data['title'] = 'order_list_item_available';
		$this->page_data['page']->submenu = 'order_list';
		$this->page_data['invoice'] = $this->order_model->get_order_selling_by_code(get('id'));
		$this->page_data['items'] = $this->order_list_item_model->get_order_item_by_code_order(get('id'));
		$this->load->view('validation/warehouse/report_confirmation', $this->page_data);
	}

	protected function create_or_update_list_item_order_sale($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['index_list'] = $key;
			$request[$key]['order_code'] = $this->data['order_code'];
			$request[$key]['item_id'] = $value['item_id'];
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			$request[$key]['item_selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_quantity'] = $item[$key]->quantity;
			$request[$key]['item_order_quantity'] = abs($value['item_order_quantity']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['item_total_price'] = setCurrency($value['total_price']);
			$request[$key]['item_description'] = $value['item_description'];
			$request[$key]['customer_code'] = $value['customer_code'];
			if (isset($value['id'])) {
				$request[$key]['id'] = $value['id'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$request[$key]['status_available'] = $value['status_available'];
				$data_positif[] = $request[$key];
			} else {
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

		$request['note'] = $data['note'];
		if ($response) {
			$request['is_cancelled'] = @$data['is_cancelled'];
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

	public function test()
	{
		// $this->db->trans_start();
		
		// QUERY 1
		$this->db->select('
			`order`.id as id, 
			`order`.order_code as order_code, 
			`order`.total_price as total_price, 
			`order`.discounts as discounts, 
			`order`.shipping_cost as shipping_cost, 
			`order`.other_cost as other_cost, 
			`order`.payment_type as payment_type, 
			`order`.grand_total as grand_total, 
			`order`.note as note, 
			`order`.created_at as created_at, 
			`order`.updated_at as updated_at, 
			`order`.created_by as created_by, 
			`order`.is_confirmed as is_confirmed,
			`order`.is_cancelled as is_cancelled, 
			`order`.is_created as is_created,
			0 as controlled_by
		');
		$this->db->group_start();
		$this->db->where('order.is_cancelled !=', 1);
		$this->db->where('order.is_created', 0);
		$this->db->where('order.is_confirmed', NULL, false);
		$this->db->group_end();
		$raw_order_query = $this->db->get_compiled_select('order_sale order', false);
		$this->db->reset_query();

		// QUERY 2
		$this->db->select('
			`sale`.id as id, 
			`sale`.`invoice_code` as order_code, 
			`sale`.`total_price` as total_price, 
			`sale`.`discounts` as discounts, 
			`sale`.`shipping_cost` as shipping_cost, 
			`sale`.`other_cost` as other_cost, 
			`sale`.`payment_type` as payment_type, 
			`sale`.`grand_total` as grand_total, 
			`sale`.`note` as note, 
			`sale`.`created_at` as created_at, 
			`sale`.`updated_at` as updated_at, 
			`sale`.`created_by` as created_by, 
			`sale`.`updated_by` as is_confirmed,
			`sale`.`is_cancelled` as is_cancelled, 
			1 as is_created,
			`sale`.`is_controlled_by` as controlled_by
		');
		$this->db->join('invoice_transaction_list_item transactions', 'sale.invoice_code = transactions.invoice_code', 'LEFT');
		$this->db->group_start();
		$this->db->where('transactions.is_cancelled !=', 1);
		$this->db->where('transactions.control_by', NULL, false);
		$this->db->where('sale.is_controlled_by', NULL, false);
		$this->db->where('sale.is_controlled_by', NULL, false);
		$this->db->where('sale.is_transaction', 1);
		$this->db->where('sale.is_child', 0);
		$this->db->group_end();
		$raw_sale_transaction = $this->db->get_compiled_select('invoice_selling sale', false);
		$this->db->reset_query();
		
		// QUERY UNION
		$result = $this->db->query("$raw_order_query UNION $raw_sale_transaction")->result();
		// $this->db->trans_complete();
		echo '<pre>';
		var_dump($result);
		echo '<hr>';
		var_dump($this->db->last_query());
		echo '</pre>';

	}
}