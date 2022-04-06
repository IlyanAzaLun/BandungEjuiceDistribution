<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Sale.php';
class Returns extends Sale
{
	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Sales Returns';
		$this->page_data['page']->menu = 'Sale';
	}

	public function index()
	{
		ifPermissions('sale_returns');
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		$this->page_data['title'] = 'sale_returns';
		$this->page_data['page']->submenu = 'sale_list';
		$this->page_data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('id'));
		$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
		if(!($this->page_data['invoice'] && get('id'))){
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', lang('error_worng_information'));
			redirect('invoice/sale/list');	
		}
		if ($this->form_validation->run() == false) {
			$this->load->view('invoice/sale/returns', $this->page_data);
		} else {
			$this->data['invoice_code_parents'] = $this->input->get('id');
			$this->data['invoice_code'] = str_replace('INV','RET', $this->data['invoice_code_parents']);
			// information invoice
			$date = preg_split('/[-]/', $this->input->post('date_due'));
			$this->data['date'] = array(
				'date_start' => trim($date[0]), 
				'date_due'	 => trim($date[1])
			);
			//information items
			$items = array();
			$item = array();
			foreach (post('item_code') as $key => $value) {
				array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
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
				$items[$key]['status_return'] = post('status_return')[$key];
				if($items[$key]['item_order_quantity'] == "0"){
					unset($items[$key]);
				}
			}
			$items = array_values($items);

			if(sizeof($items) < 1){
				$this->session->set_flashdata('alert-type', 'warning');
				$this->session->set_flashdata('alert', lang('returns_failed'));

				redirect('invoice/sales/returns?id='.get('id'));
				die();
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
				'date_start' => date("Y-m-d H:i",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i",strtotime($this->data['date']['date_due'])),
				'note' => post('note'),
			);
			try {
				echo '<pre>';
				// CREATE
				$this->create_item_history($items, ['RETURNS', 'RETURNS']);
				$this->create_or_update_invoice($payment);
				$this->create_or_update_list_item_transcation($items);
				$this->update_items($items);
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

			redirect('invoice/sale/list');
		}
	}

	public function edit()
	{
		ifPermissions('sale_returns_edit');
		$this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		$this->page_data['title'] = 'sale_returns_edit';
		$this->page_data['page']->submenu = 'returns_edit';
		$this->page_data['invoice'] = $this->sale_model->get_invoice_selling_by_code(get('id'));
		$this->page_data['items'] = $this->transaction_item_model->get_transaction_item_by_code_invoice(get('id'));
		if(!($this->page_data['invoice'] && get('id'))){
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', lang('error_worng_information'));
			redirect('invoice/sale/list');
			die();
		}
		if ($this->form_validation->run() == false) {
			$this->load->view('invoice/sale/returns_edit', $this->page_data);
		} else {			
			$this->data['invoice_code'] = $this->input->get('id');
			$this->data['invoice_code_parents'] = str_replace('RET','INV', $this->data['invoice_code']);
			// information invoice
			$date = preg_split('/[-]/', $this->input->post('date_due'));
			$this->data['date'] = array(
				'date_start' => trim($date[0]), 
				'date_due'	 => trim($date[1])
			);
			//information items
			$items = array();
			$item = array();
			foreach (post('item_code') as $key => $value) {
				array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
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
				$items[$key]['status_return'] = post('status_return')[$key];
				if($items[$key]['item_order_quantity'] == "0"){
					unset($items[$key]);
				}
			}
			$items = array_values($items);

			if(sizeof($items) < 1){
				$this->session->set_flashdata('alert-type', 'warning');
				$this->session->set_flashdata('alert', lang('returns_failed'));

				redirect('invoice/sales/returns/edit?id='.get('id'));
				die();
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
				'date_start' => date("Y-m-d H:i",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i",strtotime($this->data['date']['date_due'])),
				'note' => post('note'),
			);
			try {
				// EDIT
				echo '<pre>';
				$this->create_item_history($items, ['RETURNS', 'RETURNS']);
				$this->create_or_update_invoice($payment);
				$this->create_or_update_list_item_transcation($items);
				$this->update_items($items);
				// echo '<hr>';
				// var_dump($this->db->get_compiled_insert());
				// echo '<hr>';
				// var_dump($this->db->last_query());
				echo '</pre>';
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			$this->activity_model->add("Create Returns Sales, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Returns Sales Successfully');

			redirect('invoice/sale/list');
		}
	}

	// 
	protected function create_or_update_invoice($data)
	{
		$response = $this->sale_model->get_invoice_selling_by_code($this->data['invoice_code']);

		$request['total_price'] = setCurrency($data['total_price']);
		$request['discounts'] = setCurrency($data['discounts']);
		$request['shipping_cost'] = setCurrency($data['shipping_cost']);
		$request['other_cost'] = setCurrency($data['other_cost']);
		$request['grand_total'] = setCurrency($data['grand_total']);
		$request['customer'] = $data['customer'];
		$request['payment_type'] = $data['payment_type'];
		$request['status_payment'] = $data['status_payment'];
		$request['date_start'] = $data['date_start'];
		$request['date_due'] = $data['date_due'];
		$request['note'] = $data['note'];
		$request['expedition'] = $data['expedition'];
		$request['services_expedition'] = $data['services_expedition'];
		if ($response) {
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->sale_model->update_by_code($this->data['invoice_code'], $request);
		} else {
			$request['invoice_code'] = $this->data['invoice_code'];
			$request['created_by'] = logged('id');
			//	
			$this->sale_model->update_by_code($this->data['invoice_code_parents'], array('have_a_child' => $this->data['invoice_code']));
			return $this->sale_model->create($request);
		}
		return $request;
	}

	protected function create_or_update_list_item_transcation($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['index_list'] = (int)$value['index_list'];
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
			$request[$key]['item_status'] = 'IN';
			$request[$key]['item_description'] = $value['item_description'];
			$request[$key]['customer_code'] = $value['customer_code'];
			if ($value['id']) {
				//EDIT
				$request[$key]['id'] = $value['id'];
				$request[$key]['item_quantity'] = $value['item_order_quantity']+$value['item_order_quantity_current'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$data_positif[] = $request[$key];
			} else {
				$request[$key]['item_quantity'] = $value['item_order_quantity'];
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

	// RETURN SELLING 
	protected function update_items($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			// if ($value['id']) {
			// 	//EDIT
			// 	$request[$key]['quantity'] = ($item[$key]->quantity - $value['item_order_quantity_current']) + ($value['item_order_quantity'] + $value['item_order_quantity_current']);
			// 	$request[$key]['capital_price'] = setCurrency($value['item_capital_price']);
			// } else {
			// 	$request[$key]['quantity'] = ($item[$key]->quantity + $value['item_order_quantity']);
			// 	$request[$key]['capital_price'] = (setCurrency($value['item_capital_price']) > $item[$key]->capital_price) ? setCurrency($value['item_capital_price']) : $item[$key]->capital_price;
			// }
			if($value['status_return']){
				if ($value['id']) {
					//EDIT
					$request[$key]['quantity'] = ($item[$key]->quantity - $value['item_order_quantity_current']) + ($value['item_order_quantity'] + $value['item_order_quantity_current']);
					$request[$key]['capital_price'] = setCurrency($value['item_capital_price']);
				} else {
					$request[$key]['quantity'] = ($item[$key]->quantity + $value['item_order_quantity']);
					$request[$key]['capital_price'] = (setCurrency($value['item_capital_price']) > $item[$key]->capital_price) ? setCurrency($value['item_capital_price']) : $item[$key]->capital_price;
				}
			}else{
				if($value['id']){
					$request[$key]['broken'] = ($item[$key]->broken - $value['item_order_quantity_current']) + ($value['item_order_quantity'] + $value['item_order_quantity_current']);
				}else{
					$request[$key]['broken'] = ($item[$key]->broken + $value['item_order_quantity']);
				}
			}
			$request[$key]['selling_price'] = setCurrency($value['item_selling_price']);
			$request[$key]['updated_by'] = logged('id');
			$this->items_model->update($item[$key]->id, $request[$key]);
		}
		return $request;
		// return $this->items_model->update_batch($request, 'item_code');
	}
}