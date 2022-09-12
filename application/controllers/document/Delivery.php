<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Delivery extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Delivery';
		$this->page_data['page']->menu = 'delivery_document';
	}

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        ifPermissions('delivery_document');
		$this->page_data['title'] = 'delivery_document_list';
		$this->page_data['page']->submenu = 'delivery_document_list';
		$this->load->view('document/delivery/list', $this->page_data);
    }

    public function create()
    {
		ifPermissions('delivery_document_create');
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('item_id[]', "Item", 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['title'] = 'create_delivery_information';
			$this->page_data['page']->submenu = 'delivery_document_create';
			$this->page_data['expedition'] = $this->expedition_model->get();
			if (get('invoice')) {
				$invoice_reference = $this->delivery_model->getByWhere(array('invoice_reference' => get('invoice')));
				if($invoice_reference){
					redirect("document/delivery/edit?id=".$invoice_reference[0]->id);
				}
				if(preg_match('/^INV+\/PURCHASE|^RET+\/PURCHASE/i', get('invoice'))){
					$this->page_data['invoice'] = $this->purchase_model->get_invoice_purchasing_by_code(get('invoice'));
				}
				if(preg_match('/^INV+\/SALE|^RET+\/SALE/i', get('invoice'))){
					$this->page_data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('invoice'));
				}
				$this->page_data['item_list'] = $this->transaction_item_model->get_transaction_item_by_code_invoice($this->page_data['invoice']->invoice_code);
				$this->load->view('document/delivery/create_document', $this->page_data);
				return false;
			}
			$this->load->view('document/delivery/create', $this->page_data);
		}else{			
			// information invoice
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$random = substr(str_shuffle($permitted_chars), 0, 5);
			$now = date('ym');
			$this->db->like('delivery_code', "SURAT-JALAN/BED/$now/", 'after');
			$number = $this->db->get('delivery_head')->num_rows();
			$this->db->reset_query();
			$this->data['delivery_code'] = "SURAT-JALAN/BED/$now/$random/$number";			
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
				'customer_code' => post('customer_code'),
				'store_name' => post('store_name'),
				'contact_phone' => post('contact_phone'),
				'destination_address' => post('address'),
				'total_price' => post('sub_total'),
				'discounts' => post('discount'),
				'expedition' => post('expedition_name'),
				'services_expedition' => post('services_expedition'),
				'shipping_cost' => post('shipping_cost'),
				'other_cost' => post('other_cost'),
				'grand_total' => post('grand_total'),
				'invoice_reference' => post('invoice_reference'),
				'type_invoice' => post('type_invoice'),
				'is_shipping_cost' => post('shipping_cost_to_invoice'),
				'is_have' => post('is_have'),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note') == false? null : strtoupper(post('note')),
			);
			// CREATE
			$items = array_values($items);

			if(!$items){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quantity is over: '.json_encode($error, true));
				redirect("document/delivery/create");
				return false;
			}
			$this->db->trans_start(TRUE);
			$result_payment = $this->create_or_update_delivery($payment);
			$this->create_or_update_list_item_delivery($items);			
			$this->activity_model->add("Create Delivery Document, #" . $this->data['delivery_code'], (array) $payment);
			$this->db->trans_complete();
			$delivery_code = ($this->order_model->getRowById($result_payment, 'delivery_code'));
			if($error){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Quantity is over: '.json_encode($error));
				redirect("invoice/order/edit?id=$delivery_code");
				return false;
			}
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Create Order Successfully');
			redirect("document/delivery/print?id=$result_payment");
		}
    }

	public function edit()
	{
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('item_id[]', "Item", 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['title'] = 'delivery_document_list';
			$this->page_data['page']->submenu = 'delivery_document_list';
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['header']  = $this->delivery_model->getById(get('id'));
			$this->page_data['contens'] = $this->delivery_list_item_model->getByWhere(['delivery_code' => $this->page_data['header']->delivery_code]);	
			
			$this->page_data['modals'] = (object) array(
				'id' => 'modal-remove-order',
				'title' => 'Modals confirmation',
				'link' => "document/delivery/remove?id=".get('id'),
				'content' => 'delete',
				'btn' => 'btn-danger',
				'submit' => 'Yes do it',
			);
			$this->load->view('document/delivery/edit', $this->page_data);
			$this->load->view('includes/modals');
		}
		else{
			$this->data['delivery_code'] = post('delivery_code');	
			$items = array();
			foreach (post('item_code') as $key => $value) {
				$items[$key]['id'] = post('id')[$key];
				$items[$key]['item_id'] = post('item_id')[$key];
				$items[$key]['item_code'] = post('item_code')[$key];
				$items[$key]['item_name'] = post('item_name')[$key];
				$items[$key]['item_quantity'] = post('item_quantity')[$key];
				$items[$key]['item_order_quantity'] = post('item_order_quantity')[$key];
				$items[$key]['item_unit'] = post('item_unit')[$key];
				$items[$key]['item_description'] = post('description')[$key];
				$items[$key]['customer_code'] = post('customer_code');
				if($items[$key]['item_order_quantity'] == $items[$key]['item_quantity']){
					unset($items[$key]);
				}
			}
			$items = array_values($items);
			$header = array(
				'customer_code' => post('customer_code'),
				'store_name' => post('store_name'),
				'contact_phone' => post('contact_phone'),
				'destination_address' => post('address'),
				'shipping_cost' => post('shipping_cost'),
				'is_shipping_cost' => post('shipping_cost_to_invoice'),
				'expedition' => post('expedition_name'),
				'type_invoice' => post('type_invoice'),
				'services_expedition' => post('services_expedition'),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note') == false? null : strtoupper(post('note')),
			);
			try {
				$this->create_or_update_delivery($header);
				$this->create_or_update_list_item_delivery($items);

				$this->activity_model->add("Update Delivery Document, #" . $this->data['delivery_code'], (array) $header);

				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Create Order Successfully');
				redirect('document/delivery/edit?id='.get('id'));

			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($h);
				echo "</pre>";
			}
		}
	}

	public function print()
	{
		$this->load->library('pdf');
	
        $options = $this->pdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $this->pdf->setOptions($options);
		
		$this->page_data['header']  = $this->delivery_model->getById(get('id'));
		$this->page_data['contens'] = $this->delivery_list_item_model->getByWhere(['delivery_code' => $this->page_data['header']->delivery_code]);	

		$this->pdf->setPaper('A4', 'potrait');
		// $this->pdf->filename = $data['customer']->store_name."pdf";
		$this->pdf->load_view('document/delivery/print', $this->page_data);
	}
	
	protected function create_or_update_delivery($data)
	{
		$response = $this->delivery_model->getByWhere(array('delivery_code' => $this->data['delivery_code']));
		$request['shipping_cost'] = setCurrency($data['shipping_cost']);
		$request['customer_code'] = $data['customer_code'];
		$request['store_name'] = $data['store_name'];
		$request['note'] = $data['note'];
		$request['created_at'] = $data['created_at'];		
		$request['destination_address'] = $data['destination_address'];		
		$request['is_shipping_cost'] = $data['is_shipping_cost'];
		$request['expedition'] = $data['expedition'];
		$request['services_expedition'] = $data['services_expedition'];
		$request['invoice_reference'] = $data['invoice_reference'];
		$request['type_invoice'] = $data['type_invoice'];
		if ($response[0]) {
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->delivery_model->update($response[0]->id, $request);
		} else {
			$request['delivery_code'] = $this->data['delivery_code'];
			$request['created_by'] = logged('id');
			//	
			return $this->delivery_model->create($request);
		}
	}

	public function create_or_update_list_item_delivery($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->items_model->getByCodeItem($value['item_code'])); // Primary for find items with code item
			$request[$key]['delivery_code'] = $this->data['delivery_code'];
			$request[$key]['item_id'] = $item[$key]->id;
			$request[$key]['item_name'] = $item[$key]->item_name;
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_quantity'] = $value['item_order_quantity'];
			$request[$key]['item_unit'] = $item[$key]->unit;
			$request[$key]['item_total_weight'] = $item[$key]->weight * $value['item_order_quantity'];
			$request[$key]['item_description'] = $value['item_description'];
			if ($value['id']) {
				$request[$key]['id'] = $value['id'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$request[$key]['is_cancelled'] = $value['is_cancelled'];
				$data_positif[] = $request[$key];
			} else {
				$request[$key]['created_by'] = logged('id');
				$data_negatif[$key] = $request[$key];
				unset($data_negatif[$key]['id']);
			}
		}
		if (@$data_negatif) {
			if ($this->delivery_list_item_model->create_batch($data_negatif) && $this->delivery_list_item_model->update_batch($data_positif, 'id')) {
				return true;
			}
		} else {
			$this->delivery_list_item_model->update_batch($data_positif, 'id');
			return true;
		}
	}

	public function remove()
	{
		if($this->delivery_list_item_model->update(post('id'), array('is_canceled' => 1))){
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Delete List Item Successfully');
			redirect('document/delivery/edit?id='.get('id'));
		}
		else {
			$this->session->set_flashdata('alert-type', 'error');
			$this->session->set_flashdata('alert', 'Fail List Item');
			redirect('document/delivery/edit?id='.get('id'));
		}
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