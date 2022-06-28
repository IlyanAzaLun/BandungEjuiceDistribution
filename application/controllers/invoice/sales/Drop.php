<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Sale.php';
class Drop extends Sale
{
	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->menu = 'drop_items';
	}
	
	public function list_drop_items()
	{
		ifPermissions('drop_items');
		$this->page_data['title'] = 'drop_list';
		$this->page_data['page']->submenu = 'list_drop';
		$this->load->view('invoice/sale/drop_list', $this->page_data);
	}
	
	public function download()
	{
		ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Drop Item");
		$date = preg_split('/[-]/', trim(post("min")));
		$data['date'] = array(
			'date_start' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[0]))), "Y-m-d"), 
			'date_due' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[1]))), "Y-m-d")
		);
		$this->db->select('
		sale.invoice_code as invoice_code,
		list_item.item_code,
		list_item.item_name,
		list_item.item_quantity,
		sale.note as purchase_note, 
		sale.created_at as created_at, 
		user.name as user_sale_create_by');
		$this->db->join('users user', 'user.id = sale.created_by', 'left');
		$this->db->join('invoice_transaction_list_item list_item', 'list_item.invoice_code = sale.invoice_code', 'left');
		$this->db->group_start();
        $this->db->where("sale.created_at >=", $data['date']['date_start']);
        $this->db->where("sale.created_at <=", $data['date']['date_due']);
        $this->db->group_end();
		$this->db->where("sale.is_transaction", 0);
		$this->db->where("sale.is_cancelled", 0);
		$data = $this->db->get('invoice_selling sale')->result();
        $i = 2;

        $sheet->setCellValue("A1", "invoice_code");
        $sheet->setCellValue("B1", "item_code");
        $sheet->setCellValue("C1", "item_name");
        $sheet->setCellValue("D1", "item_quantity");
        $sheet->setCellValue("E1", "purchase_note");
        $sheet->setCellValue("F1", "created_at");
        $sheet->setCellValue("G1", "user_sale_create_by");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->invoice_code);
            $sheet->setCellValue("B".$i, $value->item_code);
            $sheet->setCellValue("C".$i, $value->item_name);
            $sheet->setCellValue("D".$i, $value->item_quantity);
            $sheet->setCellValue("E".$i, $value->purchase_note);
            $sheet->setCellValue("F".$i, $value->created_at);
            $sheet->setCellValue("G".$i, $value->user_sale_create_by);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'drop_item-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
	}
	public function serverside_datatables_data_list_drop()
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
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('sale.invoice_code', $searchValue, 'after');
			$this->db->or_like('sale.customer', $searchValue, 'both');
			$this->db->or_like('sale.note', $searchValue, 'both');
			$this->db->or_like('sale.created_at', $searchValue, 'both');
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
		$this->db->where("sale.is_transaction", 0);
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
		user_updated.name as user_sale_update_by');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('sale.invoice_code', $searchValue, 'after');
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
		$this->db->where("sale.is_transaction", 0);
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
	/*
	 * drop stock item without invoice code
	*/
	public function index()
	{
		ifPermissions('drop_items');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->page_data['order'] = $this->order_model->get_order_selling_by_code(get('id'));
			$this->page_data['items'] = $this->order_list_item_model->get_order_item_by_code_order(get('id'));
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['bank'] = $this->account_bank_model->get();
			$this->page_data['title'] = 'drop_items';
			$this->page_data['page']->submenu = 'drop_items';
			$this->load->view('invoice/sale/drop', $this->page_data);
		}else{
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$random = substr(str_shuffle($permitted_chars), 0, 5);
			$now = date('ym');
			$this->data['invoice_code'] = "DROP/$random/$now";
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
					"customer_code" => 0,
				);
			}
			//information payment
			$payment = array(
				'customer' => 0,
				'transaction_destination' => 0,
				'store_name' => 0,
				'contact_phone' => 0,
				'address' => 0,
				'total_price' => 0,
				'discounts' => 0,
				'shipping_cost' => 0,
				'other_cost' => 0,
				'grand_total' => 0,
				'expedition_name' => 0,
				'services_expedition' => 0,
				'payment_type' => 0,
				'status_payment' => 0,
				'date_start' => 0,
				'date_due' => 0,
				'note' => post('note'),
				'reference_order' => 0,
				'is_transaction' => 0,
			);
			// // DROP
			$this->update_item_fifo($items); // UPDATE ON PURCHASE QUANTITY
			$this->create_item_history($items, ['DROP', 'DROP']);
			$this->create_or_update_invoice($payment);
			$this->create_or_update_list_item_transcation($items);
			$this->create_or_update_list_item_fifo($items); // CREATE OR UPDATE ONLY FOR SALE.. NEED FOR CANCEL
			$this->update_items($items); // CHANGE VALUE QUANTITY ITEMS
			
			$this->activity_model->add("Create Drop Items, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Drop out Item Successful');

			redirect('invoice/sales/drop/list_drop_items');
		}
	}

	public function edit_drop()
	{
		ifPermissions('drop_items');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->page_data['drops_information'] = $this->sale_model->get_invoice_selling_by_code(get('id'));
			$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
			$this->page_data['title'] = 'drop_edit';
			$this->page_data['page']->menu = 'drop_items';
			$this->page_data['page']->submenu = 'drop_items';
			$this->page_data['modals'] = (object) array(
				'id' => 'exampleModal',
				'title' => 'Modals confirmation',
				'link' => 'invoice/sales/drop/cancel?id='.get('id'),
				'content' => 'delete',
				'btn' => 'btn-danger',
				'submit' => 'Yes do it',
			);
			$this->load->view('invoice/sale/drop_edit', $this->page_data);
			$this->load->view('includes/modals', $this->page_data);
		}else{
			$this->data['invoice_code'] = $this->input->get('id');
			//information items

			$items = array();
			foreach (post('item_code') as $key => $value) {
				$items[] = array(
					"id" => post('id')[$key],
					"index_list" => post('index_list')[$key],
					"item_id" => post('item_id')[$key],
					"item_code" => post('item_code')[$key],
					"item_name" => post('item_name')[$key],
					"item_quantity" => post('item_quantity')[$key],
					"item_quantity_current" => post('item_quantity_current')[$key],
					"item_order_quantity" => post('item_order_quantity')[$key],
					"item_order_quantity_current" => post('item_order_quantity_current')[$key],
					"item_unit" => post('item_unit')[$key],
					"item_capital_price" => post('item_capital_price')[$key],
					"item_selling_price" => post('item_selling_price')[$key],
					"item_discount" => post('item_discount')[$key],
					"total_price" => post('total_price')[$key],
					"item_description" => post('description')[$key],
					"customer_code" => post('customer_code'),
					"is_cancelled" => 0,
				);
				if($items[$key]['item_order_quantity'] == $items[$key]['item_order_quantity_current']){	
					unset($items[$key]);
				}
			}
			$items = array_values($items);
			//information payment
			//information payment
			$payment = array(
				'customer' => 0,
				'transaction_destination' => 0,
				'store_name' => 0,
				'contact_phone' => 0,
				'address' => 0,
				'total_price' => 0,
				'discounts' => 0,
				'shipping_cost' => 0,
				'other_cost' => 0,
				'grand_total' => 0,
				'expedition_name' => 0,
				'services_expedition' => 0,
				'payment_type' => 0,
				'status_payment' => 0,
				'date_start' => 0,
				'date_due' => 0,
				'note' => post('note'),
				'reference_order' => 0,
				'is_transaction' => 0,
			);
			// // EDIT DROP
			$this->update_item_fifo($items); // UPDATE ON PURCHASE QUANTITY
			$this->create_item_history($items, ['DROP', 'DROP']);
			$this->create_or_update_invoice($payment);
			$this->create_or_update_list_item_transcation($items);
			$this->create_or_update_list_item_fifo($items); // CREATE OR UPDATE ONLY FOR SALE.. NEED FOR CANCEL
			$this->update_items($items); // CHANGE VALUE QUANTITY ITEMS
			
			$this->activity_model->add("Update Drop Items, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Edit Information Drop Out Item, Successfully');

			redirect('invoice/sales/drop/list_drop_items');
		}
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
				$request[$key]['item_quantity'] = $item[$key]->quantity + $value['item_order_quantity_current'];
				$request[$key]['status_type'] = $status_type[1];
			} else {
				$request[$key]['item_quantity'] = $item[$key]->quantity;
				$request[$key]['status_type'] = $status_type[0];
			}
			$request[$key]['item_order_quantity'] = abs($value['item_order_quantity']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			$request[$key]['status_transaction'] = __CLASS__;
			$request[$key]['created_by'] = logged('id');
			$this->items_history_model->create($request[$key]);
		}
		return $request;
		// // return $this->items_history_model->create_batch($request); // NOT USED HERE...  DIFFERENT CONDITION TO USE HERE...
	}
	protected function create_or_update_invoice($data)
	{
		$response = $this->sale_model->get_invoice_selling_by_code($this->data['invoice_code']);
		if ($response) {
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['cancel_note'] = $data['cancel_note'];
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			$request['is_controlled_by'] = null;
			$request['is_delivered'] = null;
			//
			return $this->sale_model->update_by_code($this->data['invoice_code'], $request);
		} else {
			$request['note'] = $data['note'];
			$request['is_transaction'] = $data['is_transaction'];
			$request['invoice_code'] = $this->data['invoice_code'];
			$request['created_at'] = $data['created_at'];
			$request['created_by'] = logged('id');
			//	
			return $this->sale_model->create($request);
		}
		return $request;
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
			$request[$key]['item_quantity'] = abs($value['item_order_quantity']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			$request[$key]['created_at'] = $value['created_at'];
			if ($value['item_order_quantity'] <= 0) {
				$request[$key]['item_status'] = 'IN';
			}else{
				$request[$key]['item_status'] = 'OUT';
			}
			$request[$key]['item_description'] = $value['item_description'];
			$request[$key]['customer_code'] = $value['customer_code'];
			if ($value['id']) {
				$request[$key]['item_current_quantity'] = ($item[$key]->quantity + $value['item_order_quantity_current']) - $value['item_order_quantity'];
				$request[$key]['id'] = $value['id'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$request[$key]['is_cancelled'] = $value['is_cancelled'];
				$data_positif[] = $request[$key];
			} else {
				$request[$key]['item_current_quantity'] = $item[$key]->quantity - $value['item_order_quantity'];
				$request[$key]['created_by'] = logged('id');
				$data_negatif[] = $request[$key];
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
	}

	public function cancel()
	{
		$items = array();
		$this->data['invoice_code'] = get('id');
		$this->data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
		foreach ($this->data['items'] as $key => $value) {
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
			$items[$key]['total_price'] = $value->item_description;
			$items[$key]['item_description'] = $value->item_description;
			$items[$key]['customer_code'] = $value->customer_code;
			$items[$key]['is_cancelled'] = 1;
			$items[$key]['item_order_quantity'] = ($value->item_quantity) * -1;
		}
		$payment['is_cancelled'] = 1;
		$payment['cancel_note'] = $this->input->post('note');
		//
		echo "<pre>";
		var_dump($this->create_item_history( $items, ['CANCELED', 'CANCELED'])); //ok
		var_dump($this->create_or_update_invoice($payment)); //
		var_dump($this->update_items($items)); //
		var_dump($this->create_or_update_list_item_transcation($items)); // ok

		var_dump($this->update_list_item_fifo_on_cancel($items)); // PREPARE TOO FOR CANCEL CHILD 

		echo "</pre>";

		$this->activity_model->add("Cancel Drop Quantity Items, #" . $this->data['invoice_code'], (array) $payment);
		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Cancel Drop Quantity Successfully');

		redirect('invoice/sales/drop/list_drop_items');
	}
	

}