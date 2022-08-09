<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Purchase.php';
class Entry extends Purchase
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Purchase';
		$this->page_data['page']->menu = 'entry_items';
	}
	/*
	 * entry stock item without invoice code
	*/
	public function list_entry()
	{
		ifPermissions('purchase_create');
		$this->page_data['title'] = 'entry_items';
		$this->page_data['page']->menu = 'entry_items';
		$this->page_data['page']->submenu = 'list_entry';
		$this->load->view('invoice/purchase/entry_list', $this->page_data);
	}
	public function download()
	{
		ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Entry Item");
		$date = preg_split('/[-]/', trim(post("min")));
		$data['date'] = array(
			'date_start' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[0]))), "Y-m-d"), 
			'date_due' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[1]))), "Y-m-d")
		);
		$this->db->select('
		purchasing.invoice_code as invoice_code,
		list_item.item_code,
		list_item.item_name,
		list_item.item_quantity,
		purchasing.note as purchase_note, 
		purchasing.created_at as created_at, 
		user.name as user_purchasing_create_by');
		$this->db->join('users user', 'user.id = purchasing.created_by', 'left');
		$this->db->join('invoice_transaction_list_item list_item', 'list_item.invoice_code = purchasing.invoice_code', 'left');
		$this->db->group_start();
        $this->db->where("purchasing.created_at >=", $data['date']['date_start']);
        $this->db->where("purchasing.created_at <=", $data['date']['date_due']);
        $this->db->group_end();
		$this->db->where("purchasing.is_transaction", 0);
		$this->db->where("purchasing.is_cancelled", 0);
		$data = $this->db->get('invoice_purchasing purchasing')->result();
        $i = 2;

        $sheet->setCellValue("A1", "invoice_code");
        $sheet->setCellValue("B1", "item_code");
        $sheet->setCellValue("C1", "item_name");
        $sheet->setCellValue("D1", "item_quantity");
        $sheet->setCellValue("E1", "purchase_note");
        $sheet->setCellValue("F1", "created_at");
        $sheet->setCellValue("G1", "user_purchasing_create_by");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->invoice_code);
            $sheet->setCellValue("B".$i, $value->item_code);
            $sheet->setCellValue("C".$i, $value->item_name);
            $sheet->setCellValue("D".$i, $value->item_quantity);
            $sheet->setCellValue("E".$i, $value->purchase_note);
            $sheet->setCellValue("F".$i, $value->created_at);
            $sheet->setCellValue("G".$i, $value->user_purchasing_create_by);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'entry_item-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
	}
	public function serverside_datatables_data_list_entry_items()
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
			$this->db->group_start();
			$this->db->like('purchasing.invoice_code', $searchValue, 'both');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
			$this->db->or_like('purchasing.note', $searchValue, 'both');
			$this->db->or_like('purchasing.created_at', $searchValue, 'both');
			$this->db->group_end();
		}
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("purchasing.created_at >=", $dateStart);
			$this->db->where("purchasing.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("purchasing.created_at", date("Y-m"), 'after');
		}
		$this->db->where("purchasing.is_transaction", 0);
		$records = $this->db->get('invoice_purchasing purchasing')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('
		purchasing.id as purchasing_id, 
		SUBSTRING(purchasing.invoice_code, 5) as invoice_code_reference,
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
			$this->db->group_start();
			$this->db->like('purchasing.invoice_code', $searchValue, 'after');
			$this->db->or_like('purchasing.supplier', $searchValue, 'both');
			$this->db->or_like('purchasing.note', $searchValue, 'both');
			$this->db->or_like('purchasing.created_at', $searchValue, 'both');
			$this->db->or_like('supplier.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
		$this->db->join('users user', 'user.id = purchasing.created_by', 'left');
		$this->db->join('supplier_information supplier', 'supplier.customer_code = purchasing.supplier', 'left');
		$this->db->where("purchasing.is_child", 0);
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("purchasing.created_at >=", $dateStart);
			$this->db->where("purchasing.created_at <=", $dateFinal);
			$this->db->group_end();
		}else{
			$this->db->like("purchasing.created_at", date("Y-m"), 'after');
		}
		$this->db->where("purchasing.is_transaction", 0);
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
				'status_payment' => (boolean) $record->status_payment,
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
	
	/*
	 * entry stock item without invoice code
	*/
	public function index()
	{
		ifPermissions('purchase_create');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['title'] = 'entry_items';
			$this->page_data['page']->menu = 'entry_items';
			$this->page_data['page']->submenu = 'entry_items';
			$this->load->view('invoice/purchase/entry', $this->page_data);
		} else {
			// information invoice
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$random = substr(str_shuffle($permitted_chars), 0, 5);
			$now = date('ym');
			$this->data['invoice_code'] = "ENTRY/$random/$now";
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
					"item_capital_price_is_change" => post('item_capital_price_is_change')[$key],
					"item_selling_price" => post('item_selling_price')[$key],
					"item_discount" => post('item_discount')[$key],
					"total_price" => post('total_price')[$key],
					"item_description" => post('description')[$key],
					"customer_code" => 0,
				);
			}
			//information payment
			$payment = array(
				'supplier' => 0,
				'store_name' => 0,
				'contact_phone' => 0,
				'address' => 0,
				'total_price' => 0,
				'discounts' => 0,
				'shipping_cost' => 0,
				'other_cost' => 0,
				'grand_total' => 0,
				'payment_type' => 0,
				'status_payment' => 0,
				'date_start' => 0,
				'date_due' => 0,
				'note' => post('note'),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'is_consignment' => 0,
				'is_transaction' => 0,
			);
			echo '<pre>';
			$this->create_item_history($items, ['ENTRY', 'ENTRY']);
			$this->create_or_update_invoice($payment);
			$this->update_items($items);
			$this->create_or_update_list_item_transcation($items);
			$this->create_or_update_list_item_fifo($items);
			echo "</pre>";
			
			$this->activity_model->add("Entry QUantity Item, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Entry Items Successfully');

			redirect('invoice/purchases/entry/list_entry');
		}
	}
	public function edit_entry(){
		ifPermissions('purchase_create');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
			$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('id'));
			$this->page_data['title'] = 'entry_edit';
			$this->page_data['page']->submenu = 'entry_items';			
			$this->page_data['modals'] = (object) array(
				'id' => 'exampleModal',
				'title' => 'Modals confirmation',
				'link' => 'invoice/purchases/entry/cancel?id='.get('id'),
				'content' => 'delete',
				'btn' => 'btn-danger',
				'submit' => 'Yes do it',
			);
			$this->load->view('invoice/purchase/entry_edit', $this->page_data);
			$this->load->view('includes/modals', $this->page_data);

		} else {
			// information invoice			
			$this->data['invoice_code'] = $this->input->get('id');
			// 

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
				$items[$key]['item_capital_price_is_change'] = post('item_capital_price_is_change')[$key];
				$items[$key]['item_selling_price'] = post('item_selling_price')[$key];
				$items[$key]['item_discount'] = post('item_discount')[$key];
				$items[$key]['total_price'] = post('total_price')[$key];
				$items[$key]['item_description'] = post('description')[$key];
				$items[$key]['customer_code'] = post('supplier_code');
				$items[$key]['is_cancelled'] = 0;
				if($items[$key]['item_order_quantity'] == $items[$key]['item_order_quantity_current']){
					unset($items[$key]);
				}
			}
			$items = array_values($items);
			//information payment
			$payment = array(
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note'),
				'is_transaction' => 0,
			);
			// EDIT
			echo '<pre>';
			$this->db->trans_start();
			$this->create_item_history($items, ['ENTRY', 'ENTRY']);
			$this->create_or_update_invoice($payment);
			$this->update_items($items);
			$this->create_or_update_list_item_transcation($items);
			$this->create_or_update_list_item_fifo($items);
			$this->db->trans_complete();
			
			$this->activity_model->add("Update Entry Quantity Items, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Update Entry Quantity Successfully');

			redirect('invoice/purchases/entry/list_entry');
		}
	}
	
	/**
	 * @param Type array Description: create or update invoices,
	 * */ 
	protected function create_or_update_invoice($data)
	{
		$response = $this->purchase_model->get_invoice_purchasing_by_code($this->data['invoice_code']);

		$request['created_at'] = $data['created_at'];
		if ($response) {
			$request['invoice_code'] = $this->data['invoice_code'];
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['cancel_note'] = $data['cancel_note'];
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->purchase_model->update_by_code($this->data['invoice_code'], $request) ? true: false;
		} else {
			$request['note'] = $data['note'];
			$request['invoice_code'] = $this->data['invoice_code'];
			$request['is_transaction'] = $data['is_transaction'];
			$request['created_by'] = logged('id');
			//	
			return $this->purchase_model->create($request) ? true: false;
		}
		return true;
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
			$items[$key]['total_price'] = $value->total_price;
			$items[$key]['item_description'] = $value->item_description;
			$items[$key]['customer_code'] = $value->customer_code;
			$items[$key]['is_cancelled'] = 1;			
			$items[$key]['item_order_quantity'] = ($value->item_quantity) * -1;
		}
		$payment['is_cancelled'] = 1;
		$payment['cancel_note'] = $this->input->post('note');
		//
		echo "<pre>";
		$this->create_item_history( $items, ['CANCELED', 'CANCELED']);
		$this->create_or_update_invoice($payment);
		$this->update_items($items);
		$this->create_or_update_list_item_transcation($items);
		$this->create_or_update_list_item_fifo($items);
		echo "</pre>";

		$this->activity_model->add("Cancel Entry Quantity Items, #" . $this->data['invoice_code'], (array) $payment);
		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Cancel Entry Quantity Successfully');

		redirect('invoice/purchases/entry/list_entry');
	}

	public function remove_list()
	{
		$data_transaction_item = $this->transaction_item_model->getById(post('idorder'));
		$data_fifo_item = $this->items_fifo_model->getById(post('idorder'));
		$this->data['invoice_code'] = $data_transaction_item->invoice_code;
		$data_transaction_item->item_order_quantity_current = $data_transaction_item->item_current_quantity;
		$data_transaction_item->is_cancelled = 1;
		$data_fifo_item->is_cancelled = 1;

		$this->create_item_history([0 => (array) $data_transaction_item], ['CANCELED', 'CANCELED']);
		$this->update_items([0 => (array) $data_transaction_item]);
		$this->create_or_update_list_item_transcation([0 => (array) $data_transaction_item]);
		$this->create_or_update_list_item_fifo([0 => (array) $data_fifo_item]);

		$this->activity_model->add("Cancel Items Entry Quantity Items, #" . $this->data['invoice_code'], (array) $payment);
		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Cancel Entry Quantity Successfully');

		redirect('invoice/purchases/entry/edit_entry?id='.$this->data['invoice_code']);
	}
}

/* End of file Purchasing.php */
/* Abstract file Location: ./application/controllers/Invoice.php */
/* Location: ./application/controllers/invoices/Purchasing.php */