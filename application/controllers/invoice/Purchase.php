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
				'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'termint',
				'note' => post('note'),
				'created_by' => logged('id'),
			);
			try {
				$this->create_item_history($items, 'IN'); //OK
				$this->create_order_item($items);  		  //OK
				$this->create_invoice($payment);	   	  //OK
				$this->update_items($items);		      //OK
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			$this->activity_model->add("Create purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Item Upload Successfully');

			redirect('items');
		}
	}

	private function create_item_history($data, $status_type)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$data[$key]['item_id'] = $item[$key]->id;
			$data[$key]['item_quantity'] = $item[$key]->quantity;
			$data[$key]['item_order_quantity'] = $value['item_order_quantity'];
			$data[$key]['item_unit'] = $value['item_unit'];
			$data[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$data[$key]['item_selling_price'] = setCurrency($value['item_selling_price']);
			$data[$key]['status_type'] = $status_type;
			$data[$key]['status_transaction'] = __CLASS__;
			$data[$key]['category'] = $item[$key]->category;
			$data[$key]['invoice_reference'] = $this->data['invoice_code'];
			$data[$key]['item_discount'] = setCurrency($value['item_discount']);
			$data[$key]['total_price'] = setCurrency($value['total_price']);
			$data[$key]['created_by'] = logged('id');
			unset($data[$key]['capital_price']);
			unset($data[$key]['is_active']);
			unset($data[$key]['unit']);
			unset($data[$key]['quantity']);
			unset($data[$key]['selling_price']);
			unset($data[$key]['category']);
			unset($data[$key]['note']);
			unset($data[$key]['brand']);
			unset($data[$key]['brands']);
			unset($data[$key]['mg']);
			unset($data[$key]['ml']);
			unset($data[$key]['vg']);
			unset($data[$key]['pg']);
			unset($data[$key]['flavour']);
			unset($data[$key]['customs']);
		}
		return $this->items_history_model->create_batch($data);
	}

	private function create_order_item($data)
	{

		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$data[$key]['invoice'] = $this->data['invoice_code'];
			$data[$key]['item_id'] = $value['item_id'];
			$data[$key]['item_code'] = $item[$key]->item_code;
			$data[$key]['item_name'] = $value['item_name'];
			$data[$key]['item_order_quantity'] = $value['item_order_quantity'];
			$data[$key]['item_unit'] = $value['item_unit'];
			$data[$key]['capital_price'] = setCurrency($value['item_capital_price']);
			$data[$key]['selling_price'] = setCurrency($value['item_selling_price']);
			$data[$key]['item_discount'] = setCurrency($value['item_discount']);
			$data[$key]['total_price'] = setCurrency($value['total_price']);
			$data[$key]['created_by'] = logged('id');
			unset($data[$key]['item_capital_price']);
			unset($data[$key]['item_selling_price']);
			unset($data[$key]['item_quantity']);
		}
		return $this->order_purchase_model->create_batch($data);
	}
	private function create_invoice($data)
	{
		$data['invoice_code'] = $this->data['invoice_code'];
		$data['total_price'] = setCurrency($data['total_price']);
		$data['discounts'] = setCurrency($data['discounts']);
		$data['shipping_cost'] = setCurrency($data['shipping_cost']);
		$data['other_cost'] = setCurrency($data['other_cost']);
		$data['grand_total'] = setCurrency($data['grand_total']);
		$data['supplier'] = $data['supplier'];
		$data['payment_type'] = $data['payment_type'];
		$data['status_payment'] = $data['status_payment'];
		$data['note'] = $data['note'];
		unset($data['store_name']);
		unset($data['contact_phone']);
		unset($data['address']);

		return $this->purchase_model->create($data);
	}
	private function update_items($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$data[$key]['item_code'] = $item[$key]->item_code;
			$data[$key]['quantity'] = $item[$key]->quantity + $value['item_order_quantity'];
			$data[$key]['item_name'] = $value['item_name'];
			$data[$key]['capital_price'] = (setCurrency($value['item_capital_price']) > $item[$key]->capital_price) ? setCurrency($value['item_capital_price']) : $item[$key]->capital_price;
			$data[$key]['selling_price'] = setCurrency($value['item_selling_price']);
			$data[$key]['updated_by'] = logged('id');
			unset($data[$key]['item_id']);
			unset($data[$key]['item_unit']);
			unset($data[$key]['item_order_quantity']);
			unset($data[$key]['item_capital_price']);
			unset($data[$key]['item_selling_price']);
			unset($data[$key]['total_price']);
			unset($data[$key]['item_discount']);
			unset($data[$key]['item_quantity']);
		}

		return $this->db->update_batch('items', $data, 'item_code');
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

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('invoice_purchasing')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		if ($searchValue != '') {
			$this->db->like('purchasing.invoice_code', $searchValue, 'both');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
		}
		$records = $this->db->get('invoice_purchasing purchasing')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('*, purchasing.id as purchasing_id, purchasing.created_at as purchasing_create_at, user.name as user_purchasing_create_by');
		if ($searchValue != '') {
			$this->db->like('purchasing.invoice_code', $searchValue, 'both');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
		}
		$this->db->join('users user', 'user.id = purchasing.created_by', 'left');
		$this->db->join('supplier_information supplier', 'supplier.customer_code = purchasing.supplier', 'left');
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('invoice_purchasing purchasing')->result();

		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				'id' => $record->purchasing_id,
				'invoice_code' => $record->invoice_code,
				'supplier' => $record->store_name,
				'created_at' => $record->purchasing_create_at,
				'created_by' => $record->user_purchasing_create_by,
			);
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
}

/* End of file Purchasing.php */
/* Abstract file Location: ./application/controllers/Invoice.php */
/* Location: ./application/controllers/invoices/Purchasing.php */