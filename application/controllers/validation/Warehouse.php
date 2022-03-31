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
			$this->page_data['items'] = $this->order_list_item_model->get_order_item_by_code_order(get('id'));
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
				'date_start' => date("Y-m-d H:i",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i",strtotime($this->data['date']['date_due'])),
				'note' => post('note'),
				'created_by' => logged('id'),
			);
			echo '<pre>';
			var_dump($this->create_or_update_list_item_order_sale($items));
			echo '<hr>';
			var_dump($this->create_or_update_order($payment));
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
			if ($value['id']) {
				$request[$key]['id'] = $value['id'];
				$request[$key]['updated_by'] = logged('id');
				$request[$key]['updated_at'] = date('Y-m-d H:i:s');
				$request[$key]['is_cancelled'] = $value['is_cancelled'];
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

		$request['total_price'] = setCurrency($data['total_price']);
		$request['discounts'] = setCurrency($data['discounts']);
		$request['shipping_cost'] = setCurrency($data['shipping_cost']);
		$request['other_cost'] = setCurrency($data['other_cost']);
		$request['grand_total'] = setCurrency($data['grand_total']);
		$request['customer'] = $data['customer'];
		$request['payment_type'] = $data['payment_type'];
		$request['note'] = $data['note'];
		if ($response) {
			$request['is_cancelled'] = $data['is_cancelled'];
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
}