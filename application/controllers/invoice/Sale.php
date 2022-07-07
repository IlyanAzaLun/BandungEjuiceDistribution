<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Invoice_controller.php';
class Sale extends Invoice_controller
{
	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Sale';
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
			
			if($this->page_data['order']->is_confirmed != '1'){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Failed Worng Information');
				redirect('invoice/order/list');	
				die();
			}
			if(!(hasPermissions('fetch_all_invoice_sales') || $this->page_data['order']->is_have == logged('id'))){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Failed Worng Information');
				redirect('invoice/order/list');	
				die();
			}
			
			$this->page_data['title'] = 'sale_create';
			$this->page_data['page']->submenu = 'sale_list';
			$this->load->view('invoice/sale/form', $this->page_data);
		}else{
			$this->data['order_code'] = $this->input->get('id')?$this->input->get('id'):false;
			$is_created = $this->sale_model->is_created_sales_($this->data['order_code']);
			if($is_created){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'New Sale Invoice Are Fail Created');
				redirect('invoice/sale/list');	
				die();
			}
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
					"item__total_weight" => post('item__total_weight')[$key],
					"item_capital_price" => post('item_capital_price')[$key],
					"item_selling_price" => post('item_selling_price')[$key],
					"item_discount" => post('item_discount')[$key],
					"total_price" => post('total_price')[$key],
					"item_description" => post('description')[$key],
					"customer_code" => post('customer_code'),
					'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
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
				'total_weights_item' => post('total_weights_item'),
				'expedition_name' => post('expedition_name'),
				'services_expedition' => post('services_expedition'),
				'payment_type' => post('payment_type'),
				'status_payment' => (post('payment_type') == 'cash') ? true : false,
				'date_start' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_due'])),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note') == false ? null : strtoupper(post('note')),
				'reference_order' => $this->data['order_code'],
				"is_have" => post('is_have'),
				'shipping_cost_to_invoice' => post('shipping_cost_to_invoice'),
				'transaction_destination' => post('transaction_destination'),
			);
			// // CREATE
			echo '<pre>';
			$this->db->trans_start();
			$this->update_item_fifo($items); // UPDATE ON PURCHASE QUANTITY
			$this->order_model->update_by_code($this->data['order_code'], array('is_created' => 1)); // UPDATE STATUS ORDER IS CREATED TO INVOICE
			$this->create_item_history($items, ['CREATE', 'UPDATE']);
			$this->create_or_update_invoice($payment);
			$this->create_or_update_list_item_transcation($items);
			$this->create_or_update_list_item_fifo($items); // CREATE OR UPDATE ONLY FOR SALE.. NEED FOR CANCEL
			$this->create_or_update_list_chart_cash($payment);			// Transaction Payment
			// // $this->update_items($items); // NOT USE HERE, BUT USED ON ORDER CREATE
			$this->db->trans_complete();
			echo '</pre>';
			if($this->db->trans_status() === FALSE){
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'Creating Purchase Failed');	
				redirect('invoice/purchase/list');
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
		ifPermissions('sale_edit');
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
					"item__total_weight" => post('item__total_weight')[$key],
					"total_price" => post('total_price')[$key],
					"item_description" => post('description')[$key],
					"customer_code" => post('customer_code'),
					"is_cancelled" => 0,
				);
				if($items[$key]['item_order_quantity'] == $items[$key]['item_order_quantity_current'] && setCurrency($items[$key]['item_selling_price']) == $this->transaction_item_model->getRowById($items[$key]['id'], 'item_selling_price')){	
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
				'status_payment' => post('status_payment'),
				'date_start' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_due'])),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'total_weights_item' => post('total_weights_item'),
				'note' => post('note') == false ? null : strtoupper(post('note')),
				'reference_order' => get('id'),
				'is_controlled_by' => null,
				'is_delivered' => null,
				'is_have' => post('is_have'),
				'shipping_cost_to_invoice' => post('shipping_cost_to_invoice'),
			);// Check
			
			echo '<pre>';
			// // EDIT
			$this->db->trans_start();
			$this->update_item_fifo($items); // UPDATE ON PURCHASE QUANTITY
			$this->create_item_history($items, ['CREATE', 'UPDATE']);
			$this->create_or_update_invoice($payment);
			$this->update_items($items);
			$this->create_or_update_list_item_transcation($items);
			$this->create_or_update_list_item_fifo($items); // CREATE ONLY FOR SALE.. NEED FOR CANCEL
			// Tranasction Payment
			$this->create_or_update_list_chart_cash($payment);
			
			$this->db->trans_complete();
			echo '</pre>';
			$this->activity_model->add("Edit Sale Invoice, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Edit Sale Invoice Successfully');

			redirect('invoice/sale/list');
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

		$this->page_data['invoice_information_transaction'] = $this->page_data['intersect_codex_item']? 
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
		ifPermissions('sale_cancel');
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
		$payment['cancel_note'] = $this->input->post('note');

		$this->create_item_history( $items, ['CANCELED', 'CANCELED']);
		$this->create_or_update_invoice($payment);
		$this->update_items($items);
		$this->create_or_update_list_item_transcation($items);

		// UPDATE FIFO ITEMS CANCEL
		// update list_item_fifo only cancel..
		$this->update_list_item_fifo_on_cancel($items); // PREPARE TOO FOR CANCEL CHILD 

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
				$request[$key]['status_type'] = $status_type[1];
			} else {
				$request[$key]['item_quantity'] = $item[$key]->quantity + $value['item_order_quantity'];
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
		$user = logged('id');
		$response = $this->sale_model->get_invoice_selling_by_code($this->data['invoice_code']);
		$request['transaction_destination'] = $data['transaction_destination'];
		$request['total_price'] = setCurrency($data['total_price']);
		$request['discounts'] = setCurrency($data['discounts']);
		$request['shipping_cost'] = setCurrency($data['shipping_cost']);
		$request['other_cost'] = setCurrency($data['other_cost']);
		$request['grand_total'] = setCurrency($data['grand_total']);
		$request['customer'] = $data['customer'];
		$request['weight'] = $data['total_weights_item'];
		$request['expedition'] = $data['expedition_name'];
		$request['services_expedition'] = $data['services_expedition'];
		$request['payment_type'] = $data['payment_type'];
		$request['status_payment'] = $data['status_payment'];
		$request['date_start'] = $data['date_start'];
		$request['date_due'] = $data['date_due'];
		$request['note'] = $data['note'];
		$request['is_have'] = $data['is_have']?$data['is_have']:$user;
		$request['is_shipping_cost'] = $data['shipping_cost_to_invoice'];
		if ($response) {
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['cancel_note'] = $data['cancel_note'];
			$request['created_at'] = $data['created_at'];
			$request['updated_by'] = $user;
			$request['updated_at'] = date('Y-m-d H:i:s');
			$request['is_controlled_by'] = null;
			$request['is_delivered'] = null;
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
			$request[$key]['item_total_weight'] = $value['item__total_weight'];
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
				$request[$key]['id'] = $value['id'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$request[$key]['is_cancelled'] = $value['is_cancelled'];
				$data_positif[] = $request[$key];
			} else {
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

	protected function create_or_update_list_item_fifo($data)
	{
		$item = array();
		$item_fifo = array();
		foreach ($data as $key => $value) {
			array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row()); // Primary for find items with code item
			// 
			array_push($item_fifo, $this->items_fifo_model->select_fifo_by_item_code($value['item_code']));
			// 
			$request[$key]['invoice_code'] = $this->data['invoice_code'];
			$request[$key]['item_id'] = $value['item_id'];
			$request[$key]['item_code'] = $item[$key]->item_code;
			$request[$key]['item_name'] = $value['item_name'];
			$request[$key]['item_capital_price'] = setCurrency($value['item_capital_price']);
			$request[$key]['item_quantity'] = abs($value['item_order_quantity']);
			$request[$key]['item_unit'] = $value['item_unit'];
			$request[$key]['item_discount'] = setCurrency($value['item_discount']);
			$request[$key]['total_price'] = setCurrency($value['total_price']);
			$request[$key]['customer_code'] = $value['customer_code'];
			$request[$key]['is_readable'] = 0;
			if ($value['id']) {
				$request[$key]['is_cancelled'] = $value['is_cancelled'];
				$request[$key]['id'] = $value['id'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				// //$this->items_fifo_model->update_by_code($this->data['invoice_code'], $request[$key]);
				$data_positif[] = $request[$key];
			} else {
				$request[$key]['reference_purchase'] = $item_fifo[$key]->invoice_code;
				$request[$key]['created_at'] = $value['created_at'];
				$request[$key]['created_by'] = logged('id');
				// //$this->items_fifo_model->create($request[$key]);
				$data_negatif[] = $request[$key];
			}
		}
		if (@$data_negatif) {
			if ($this->items_fifo_model->create_batch($data_negatif) && $this->items_fifo_model->update_batch($data_positif, 'id')) {
				return true;
			}
		} else {
			$this->items_fifo_model->update_batch($data_positif, 'id');
			return true;
		}
		return $request;
	}

	/**
	 * @param Type array Description: information for payment information, and balance on account bank
	 * */ 
	private function create_or_update_list_chart_cash($data)
	{
		$response = $this->payment_model->get_payment_information_by_invoice_code($this->data['invoice_code']);
		$request['invoice_code'] = $this->data['invoice_code'];
		$request['date_start'] = $data['date_start'];
		$request['date_due'] = $data['date_due'];
		$request['customer_code'] = $data['customer'];
		$request['grand_total'] = setCurrency($data['grand_total']);
		
		//
		$request['payment_type'] = $data['payment_type'];
		if($data['payment_type'] == 'cash'){
			$request['payup'] = setCurrency($data['grand_total']); // want to pay
			$request['leftovers'] = 0; // remaind
		}else{
			$request['payup'] = 0; // want to pay
			$request['leftovers'] = setCurrency($data['grand_total']); // remaind
		}
		$request['status_payment'] = 0; // "deposit, come in"
		$request['bank_id'] = $data['transaction_destination'];
		$request['description'] = $data['note'];
		if ($response) {
			$request['is_cancelled'] = $data['is_cancelled'];
			$request['cancel_note']  = $data['cancel_note'];
			$request['updated_by'] = logged('id');
			$request['updated_at'] = date('Y-m-d H:i:s');
			//
			return $this->payment_model->update_by_code_invoice($this->data['invoice_code'], $request);
		} else {
			$request['created_by'] = logged('id');
			//	
			return $this->payment_model->create($request);
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
			$request[$key]['updated_by'] = logged('id');
			$this->items_model->update($item[$key]->id, $request[$key]);
		}
		return $request;
		// return $this->items_model->update_batch($request, 'item_code');
	}
	
	/*
	 * @param $data Type array data items to update fifo quantity, where purchase
	 */
	protected function update_item_fifo($data)
	{
		$item = array();
		foreach ($data as $key => $value) {
			$a = 1;
			// in development mode error is showed, but nothing worng
			if ($value['id']) {				
				$status = ($value['item_order_quantity'] - $value['item_order_quantity_current'] <= 0)? true : false;
				while ($a <= abs($value['item_order_quantity'] - $value['item_order_quantity_current'])) {
					// 'Edit';
					$item[$a] = $this->items_fifo_model->select_fifo_by_item_code($value['item_code']); // Primary for find items with code item
					$result[$a] = $this->items_fifo_model->update_fifo_by_item_code($item[$a], $status);
					$a++;
				}
			} else {
				while ($a <= $value['item_order_quantity']) {
					// 'Create';
					$item[$a] = $this->items_fifo_model->select_fifo_by_item_code($value['item_code']); // Primary for find items with code item
					$result[$a] = $this->items_fifo_model->update_fifo_by_item_code($item[$a]);
					$a++;
				}
			}
		}
		return $item;
	}

	/*
	 * PREPARE TOO FOR CANCEL CHILD
	 * @data Type array Description: list item
	 * 
	 */
	protected function update_list_item_fifo_on_cancel($data)
	{
		$items = array();
		$request = array();
		// update list_item_fifo where return
		$this->items_fifo_model->update_batch($data, 'id');
		// prepare data
		foreach ($data as $key => $value) {
			array_push($items, $this->items_fifo_model->select_fifo_by_item_code_is_canceled($value['item_code']));
			$request[$key]['id'] = $items[$key]->id;
			$request[$key]['item_quantity'] = $items[$key]->item_quantity - $value['item_order_quantity'];
		}
		return $this->items_fifo_model->update_batch($request, 'id');
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
		$haspermission = hasPermissions('fetch_all_invoice_sales');
		$is_super_user = hasPermissions('example');

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		
		if(!$haspermission){
			$this->db->where("created_by", $logged);
		}
		$records = $this->db->get('invoice_selling')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('customer_information customer', 'customer.customer_code = sale.customer', 'left');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('sale.invoice_code', $searchValue, 'after');
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
		if(!$haspermission){
			$this->db->group_start();
			$this->db->where("sale.created_by", $logged);
			$this->db->or_where("sale.is_have", $logged);
			$this->db->group_end();
		}
		if(!$is_super_user){
			$this->db->where("sale.is_cancelled", 0);
		}
		$this->db->group_start();
		$this->db->where("sale.is_transaction", 1);
		$this->db->where("sale.is_child", 0);
		$this->db->group_end();
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
		sale.is_have as is_have, 
		is_have.name as is_have_name, 
		user_created.id as user_id, 
		user_created.name as user_sale_create_by,
		user_updated.id as user_id_updated, 
		user_updated.name as user_sale_update_by');
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
		$this->db->join('users user_updated', 'user_updated.id = sale.updated_by', 'left');
		$this->db->join('users is_have', 'is_have.id = sale.is_have', 'left');
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
			$this->db->group_start();
			$this->db->where("sale.created_by", $logged);
			$this->db->or_where("sale.is_have", $logged);
			$this->db->group_end();
		}
		if(!$is_super_user){
			$this->db->where("sale.is_cancelled", 0);
		}
		$this->db->group_start();
		$this->db->where("sale.is_transaction", 1);
		$this->db->where("sale.is_child", 0);
		$this->db->group_end();
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
				'is_have' => $record->is_have,
				'is_have_name' => $record->is_have_name,
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

	public function monthly_statistic()
	{
		$user = logged('id');
        $haspermission = hasPermissions('dashboard_staff');
		$this->db->select("
            , transaction.created_at
            , transaction.updated_at
            , DATE_FORMAT(transaction.created_at, '%Y%m%d') AS yearmountday
            , DATE_FORMAT(transaction.created_at, '%Y%m') AS yearmount
            , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS item_capital_price
            , SUM(CAST(transaction.total_price AS INT)) AS item_selling_price
            ,(SUM(CAST(transaction.total_price AS INT))-SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS profit
			");
		$this->db->join("users", "transaction.created_by = users.id", "left");
        $this->db->join("customer_information customer", "customer.customer_code = transaction.customer_code", "left");
        $this->db->like('transaction.invoice_code', 'INV/SALE/', 'after');
        $this->db->where('transaction.is_cancelled', 0);
        if(!$haspermission){
            $this->db->where("transaction.created_by", $user);
        }
		$this->db->group_by("yearmount");
        
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('invoice_transaction_list_item transaction')->result();
		$data['labels'] = array();
		$data['datasets'][]['data'] = array();
		foreach ($records as $key => $record) {
			array_push($data['labels'], date_format(date_create($record->yearmountday),"Y-m"));
			
			$data['datasets'][0]['label'] = 'Profit';
			array_push($data['datasets'][0]['data'], $record->profit);
		}
		## Response
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
		
	}

}