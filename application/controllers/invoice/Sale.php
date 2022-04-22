<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Invoice_controller.php';
class Sale extends Invoice_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Sale (Penjualan)';
		$this->page_data['page']->menu = 'Sale';
	}
	public function index()
	{
		$this->list();
	}

	public function list()
	{
		ifPermissions('sale_list');
		$this->page_data['title'] = 'sale_list';
		$this->page_data['page']->submenu = 'sale_list';
		$this->load->view('invoice/sale/list', $this->page_data);
	}

	public function create()
	{
		ifPermissions('sale_create');
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');

		if ($this->form_validation->run() == false) {
			$this->page_data['order'] = $this->order_model->get_order_selling_by_code(get('id'));
			$this->page_data['items'] = $this->order_list_item_model->get_order_item_by_code_order(get('id'));
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['bank'] = $this->account_bank_model->get();
			$this->page_data['title'] = 'sale_create';
			$this->page_data['page']->submenu = 'sale_list';
			$this->load->view('invoice/sale/form', $this->page_data);
		}else{
			echo '<pre>';
			$this->data['invoice_code'] = $this->sale_model->get_code_invoice_sale();
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
					"customer_code" => post('customer_code'),
				);
			}
			//information payment
			$payment = array(
				'customer' => post('customer_code'),
				'transaction_destination' => post('transaction_destination'),
				'store_name' => post('store_name'),
				'contact_phone' => post('contact_phone'),
				'address' => post('address'),
				'total_price' => post('sub_total'),
				'discounts' => post('discount'),
				'shipping_cost' => post('shipping_cost'),
				'other_cost' => post('other_cost'),
				'grand_total' => post('grand_total'),
				'expedition_name' => post('expedition_name'),
				'services_expedition' => post('services_expedition'),
				'payment_type' => post('payment_type'),
				'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'credit',
				'date_start' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_due'])),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note'),
				'reference_order' => get('id'),
			);
			var_dump($payment);
			echo '</pre>';
			// die();
			try {
				// CREATE
				$this->order_model->update_by_code(get('id'), array('is_created' => 1));
				$this->create_item_history($items, ['CREATE', 'UPDATE']);
				$this->create_or_update_invoice($payment);
				$this->create_or_update_list_item_transcation($items);
				// $this->update_items($items); // NOT USE HERE, BUT USED ON ORDER CREATE
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			
			$this->activity_model->add("Create Sale Invoice, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Sale Invoice Successfully');

			redirect('invoice/sale/list');
		}
	}

	public function edit()
	{	
		ifPermissions('sale_create');
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->data_sales();
			$this->page_data['invoice_sale'] = $this->sale_model->get_invoice_selling_by_code(get('id'));
			$this->page_data['list_item_sale'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
			$this->page_data['expedition'] = $this->expedition_model->get();
			$this->page_data['bank'] = $this->account_bank_model->get();
			$this->page_data['title'] = 'sale_edit';
			$this->page_data['page']->submenu = 'sale_list';
			$this->page_data['modals'] = (object) array(
				'id' => 'modal-remove-order',
				'title' => 'Modals confirmation',
				'link' => 'invoice/sales/items/remove_item_from_list_order_transcaction',
				'content' => 'delete',
				'btn' => 'btn-danger',
				'submit' => 'Yes do it',
			);
			$this->load->view('invoice/sale/edit', $this->page_data);
			$this->load->view('includes/modals');
		}else{
			$this->data['invoice_code'] = $this->input->get('id');
			$date = preg_split('/[-]/', $this->input->post('date_due'));
			$this->data['date'] = array(
				'date_start' => trim(str_replace('/', '-', $date[0])), 
				'date_due'	 => trim(str_replace('/', '-', $date[1]))
			);
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
				);
				if($items[$key]['item_order_quantity'] == $items[$key]['item_order_quantity_current']){
					unset($items[$key]);
				}
			}
			$items = array_values($items);
			//information payment
			$payment = array(
				'customer' => post('customer_code'),
				'transaction_destination' => post('transaction_destination'),
				'store_name' => post('store_name'),
				'contact_phone' => post('contact_phone'),
				'address' => post('address'),
				'total_price' => post('sub_total'),
				'discounts' => post('discount'),
				'shipping_cost' => post('shipping_cost'),
				'other_cost' => post('other_cost'),
				'grand_total' => post('grand_total'),
				'expedition_name' => post('expedition_name'),
				'services_expedition' => post('services_expedition'),
				'payment_type' => post('payment_type'),
				'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'credit',
				'date_start' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_due'])),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note'),
				'reference_order' => get('id'),
			);
			try {
				// EDIT
				$this->create_item_history($items, ['CREATE', 'UPDATE']);
				$this->create_or_update_invoice($payment);
				$this->update_items($items);
				$this->create_or_update_list_item_transcation($items);
				
				$this->activity_model->add("Edit Sale Invoice, #" . $this->data['invoice_code'], (array) $payment);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'Edit Sale Invoice Successfully');

				redirect('invoice/sale/list');
			} catch (\Throwable $th) {
				var_dump( $th );
			}
		}
	}

	private function data_sales()
	{
		$_invoice_parent_code = str_replace('RET','INV',$this->input->get('id'), $is_replace);
		$_invoice_child__code = str_replace('INV','RET',$this->input->get('id'));
		$this->page_data['_data_item_invoice_parent'] = $this->transaction_item_model->get_transaction_item_by_code_invoice($_invoice_parent_code);
		$this->page_data['_data_item_invoice_child_'] = $this->transaction_item_model->get_transaction_item_by_code_invoice($_invoice_child__code);

		$parent_items_codex = array_column($this->page_data['_data_item_invoice_parent'], 'item_code');
		$parent_items_index = array_column($this->page_data['_data_item_invoice_parent'], 'index_list');
		$childs_items_codex = array_column($this->page_data['_data_item_invoice_child_'], 'item_code');
		$childs_items_index = array_column($this->page_data['_data_item_invoice_child_'], 'index_list');

		$this->page_data['intersect_codex_item'] = array_intersect($parent_items_codex, $childs_items_codex);
		$this->page_data['intersect_index_item'] = array_intersect($parent_items_index, $childs_items_index);
		
		$this->page_data['_data_invoice_parent'] = $this->sale_model->get_invoice_selling_by_code($_invoice_parent_code);
		$this->page_data['_data_invoice_child_'] = $this->sale_model->get_invoice_selling_by_code($_invoice_child__code);

		$this->page_data['invoice_information_transaction'] = $is_replace? 
			$this->page_data['_data_invoice_child_']:
			$this->page_data['_data_invoice_parent'];
		$this->page_data['customer'] = $this->customer_model->get_information_customer($this->page_data['invoice_information_transaction']->customer);
		$this->page_data['bank'] = $this->account_bank_model->getById($this->page_data['invoice_information_transaction']->transaction_destination);

	
	}

	public function info()
	{
		ifPermissions('sale_create');
		$this->data_sales();
		$this->page_data['title'] = 'sale_edit';
		$this->page_data['page']->submenu = 'sale_list';
		$this->page_data['modals'] = (object) array(
			'id' => 'exampleModal',
			'title' => 'Modals confirmation',
			'link' => 'invoice/sale/cancel?id='.get('id'),
			'content' => 'delete',
			'btn' => 'btn-danger',
			'submit' => 'Yes do it',
		);
		$this->load->view('invoice/sale/info', $this->page_data);
		$this->load->view('includes/modals');
	}

	public function print_PDF()
	{
		$this->data_sales();

		$this->load->library('pdf');
	
        $options = $this->pdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $this->pdf->setOptions($options);


		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "$customer->store_name.pdf";
		$this->pdf->load_view('invoice/sale/print_PDF', $this->page_data);
	
	}

	/** 
	 * @change status is_cancel to 1
	 * @change status parent have a child, if child is cancel
	 * @change status order is_created to null, if parents is cancel
	**/
	public function cancel()
	{
			$this->data_sales();
			echo '<pre>';
			$i = 0;
			$invoice_parent_transaction = $this->page_data['invoice_information_transaction'];
			$this->data['invoice_code'] = get('id');
			if($this->page_data['_data_item_invoice_child_'][0]->invoice_code == get('id')){
				$this->transaction_item_model->update($invoice_parent_transaction->id, array('have_a_child', 0));
			}
			$items = array();
			if(preg_match('/INV/', get('id'))){
				foreach ($this->page_data['_data_item_invoice_parent'] as $key => $value){
					if($value->item_code == $this->page_data['intersect_codex_item'][$key] && $value->index_list == $this->page_data['intersect_index_item'][$key]){
						$items[$key]['id'] = $value->id;
						$items[$key]['item_id'] = $value->item_id;
						$items[$key]['invoice_code'] = $value->invoice_code;
						$items[$key]['index_list'] = $value->index_list;
						$items[$key]['item_code'] = $value->item_code;
						$items[$key]['item_name'] = $value->item_name;
						$items[$key]['item_quantity_current'] = $this->page_data['_data_item_invoice_child_'][$i]->item_current_quantity;
						$items[$key]['item_unit'] = $value->item_unit;
						$items[$key]['item_capital_price'] = $this->page_data['_data_item_invoice_child_'][$i]->item_capital_price;
						$items[$key]['item_selling_price'] = $this->page_data['_data_item_invoice_child_'][$i]->item_selling_price;
						$items[$key]['item_discount'] = $this->page_data['_data_item_invoice_child_'][$i]->item_discount;
						$items[$key]['total_price'] = $this->page_data['_data_item_invoice_child_'][$i]->item_description;
						$items[$key]['item_description'] = $this->page_data['_data_item_invoice_child_'][$i]->item_description;
						$items[$key]['customer_code'] = $value->customer_code;
						$items[$key]['is_cancelled'] = 1;
						if($this->page_data['_data_item_invoice_child_'][$i]->is_cancelled){
							$items[$key]['item_order_quantity'] = ($value->item_quantity) * -1;
						}else{
							$items[$key]['item_order_quantity'] = ($value->item_quantity - $this->page_data['_data_item_invoice_child_'][$i]->item_quantity) * -1;
						}
						$i++;
					}else{
						$items[$key]['id'] = $value->id;
						$items[$key]['item_id'] = $value->item_id;
						$items[$key]['invoice_code'] = $value->invoice_code;
						$items[$key]['index_list'] = $value->index_list;
						$items[$key]['item_code'] = $value->item_code;
						$items[$key]['item_name'] = $value->item_name;
						$items[$key]['item_quantity_current'] = $value->item_current_quantity;
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
				}
			}else{
				$sale_parent_code = str_replace('RET','INV',$this->input->get('id'));
				$this->sale_model->update_by_code($sale_parent_code, array('have_a_child'=> null));
				foreach ($this->page_data['_data_item_invoice_child_'] as $key => $value) {
					$items[$key]['id'] = $value->id;
					$items[$key]['item_id'] = $value->item_id;
					$items[$key]['invoice_code'] = $value->invoice_code;
					$items[$key]['index_list'] = $value->index_list;
					$items[$key]['item_code'] = $value->item_code;
					$items[$key]['item_name'] = $value->item_name;
					$items[$key]['item_quantity_current'] = $value->item_current_quantity;
					$items[$key]['item_unit'] = $value->item_unit;
					$items[$key]['item_capital_price'] = $value->item_capital_price;
					$items[$key]['item_selling_price'] = $value->item_selling_price;
					$items[$key]['item_discount'] = $value->item_discount;
					$items[$key]['total_price'] = $value->item_description;
					$items[$key]['item_description'] = $value->item_description;
					$items[$key]['customer_code'] = $value->customer_code;
					$items[$key]['is_cancelled'] = 1;
					$items[$key]['item_order_quantity'] = ($value->item_quantity);
				}
			}
			$payment = (array) $this->page_data['invoice_information_transaction'];

			$payment['is_cancelled'] = 1;
			$this->create_item_history( $items, ['CANCELED', 'CANCELED']);
			$this->create_or_update_invoice($payment);
			$this->update_items($items);
			$this->create_or_update_list_item_transcation($items);
			
			$this->activity_model->add("Cancel Sale Invoice, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Cancel Sale Invoice Successfully');	
			echo '</pre>';
			
			redirect('invoice/sale/list');
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
				$request[$key]['item_quantity'] = $item[$key]->quantity;
			} else {
				$request[$key]['item_quantity'] = $item[$key]->quantity + $value['item_order_quantity'];
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
	
	protected function create_or_update_invoice($data)
	{
		$response = $this->sale_model->get_invoice_selling_by_code($this->data['invoice_code']);
		$request['transaction_destination'] = $data['transaction_destination'];
		$request['total_price'] = setCurrency($data['total_price']);
		$request['discounts'] = setCurrency($data['discounts']);
		$request['shipping_cost'] = setCurrency($data['shipping_cost']);
		$request['other_cost'] = setCurrency($data['other_cost']);
		$request['grand_total'] = setCurrency($data['grand_total']);
		$request['customer'] = $data['customer'];
		$request['expedition'] = $data['expedition_name'];
		$request['services_expedition'] = $data['services_expedition'];
		$request['payment_type'] = $data['payment_type'];
		$request['status_payment'] = $data['status_payment'];
		$request['date_start'] = $data['date_start'];
		$request['date_due'] = $data['date_due'];
		$request['note'] = $data['note'];
		if ($response) {
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['created_at'] = $data['created_at'];
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->sale_model->update_by_code($this->data['invoice_code'], $request);
		} else {
			$request['reference_order'] = $data['reference_order'];
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
			$request[$key]['item_current_quantity'] = $item[$key]->quantity;
			$request[$key]['item_quantity'] = abs($value['item_order_quantity']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			if ($value['item_order_quantity'] <= 0) {
				$request[$key]['item_status'] = 'IN';
			}else{
				$request[$key]['item_status'] = 'OUT';
			}
			$request[$key]['item_description'] = $value['item_description'];
			$request[$key]['customer_code'] = $value['customer_code'];
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
			if ($this->transaction_item_model->create_batch($data_negatif) && $this->transaction_item_model->update_batch($data_positif, 'id')) {
				return true;
			}
		} else {
			$this->transaction_item_model->update_batch($data_positif, 'id');
			return true;
		}
		return false;
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
			$request[$key]['selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['updated_by'] = logged('id');
			$this->items_model->update($item[$key]->id, $request[$key]);
		}
		return $request;
		// return $this->items_model->update_batch($request, 'item_code');
	}

	public function serverside_datatables_data_sale()
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
		$records = $this->db->get('invoice_selling')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		if ($searchValue != '') {
			$this->db->like('sale.invoice_code', $searchValue, 'both');
			$this->db->or_like('sale.customer', $searchValue, 'both');
			$this->db->or_like('sale.note', $searchValue, 'both');
			$this->db->or_like('sale.created_at', $searchValue, 'both');
		}
		if ($dateStart != '') {
			$this->db->where("sale.created_at >=", $dateStart);
			$this->db->where("sale.created_at <=", $dateFinal);
		}else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
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
		sale.is_cancelled as is_cancelled,  
		sale.cancel_note as cancel_note,
		customer.customer_code as customer_code, 
		customer.store_name as store_name, 
		user.id as user_id, 
		user.name as user_sale_create_by');
		if ($searchValue != '') {
			$this->db->like('sale.invoice_code', $searchValue, 'both');
			$this->db->or_like('sale.customer', $searchValue, 'both');
			$this->db->or_like('sale.note', $searchValue, 'both');
			$this->db->or_like('sale.created_at', $searchValue, 'both');
			$this->db->or_like('customer.store_name', $searchValue, 'both');
		}
		$this->db->join('users user', 'user.id = sale.created_by', 'left');
		$this->db->join('customer_information customer', 'customer.customer_code = sale.customer', 'left');
		if ($dateStart != '') {
			$this->db->where("sale.created_at >=", $dateStart);
			$this->db->where("sale.created_at <=", $dateFinal);
		}else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
		if(!$haspermission){
			$this->db->where("sale.created_by", $logged);
		}
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
				'is_cancelled' => $record->is_cancelled,
				'cancel_note' => $record->cancel_note,
				'user_sale_create_by' => $record->user_sale_create_by,
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