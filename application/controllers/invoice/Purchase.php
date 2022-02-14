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
		$this->view();
	}

	public function view()
	{
		ifPermissions('purchase_list');
		$this->page_data['title'] = 'purchase_list';
		$this->page_data['page']->submenu = 'view';
		$this->load->view('invoice/purchase/view', $this->page_data);
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
			// information supplier
			$supplier = array(
				'supplier_code' => post('supplier_code'),
				'store_name' => post('store_name'),
				'contact_phone' => post('contact_phone'),
				'address' => post('address')
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
				);
			}
			//information payment
			$payment = array(
				'sub_total' => post('sub_total'),
				'discount' => post('discount'),
				'shipping_cost' => post('shipping_cost'),
				'other_cost' => post('other_cost'),
				'grand_total' => post('grand_total'),
				'payment_type' => post('payment_type'),
				'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'termint',
				'note' => post('note'),
			);
			try {
				$this->create_item_history($items, 'IN'); //OK
				$this->add_order_item();   //OK
				$this->add_invoice();	   //OK
				$this->add_items();		   //OK
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
		$history_items = array();
		foreach (post('item_code') as $key => $value) {
			array_push(
				$history_items,
				[
					"item_id" => post('item_id')[$key],
					"item_code" => post('item_code')[$key],
					"item_name" => post('item_name')[$key],
					"item_quantity" => post('item_quantity')[$key],
					"item_order_quantity" => post('item_order_quantity')[$key],
					"item_unit" => post('item_unit')[$key],
					"item_capital_price" => setCurrency(post('item_capital_price')[$key]),
					"item_selling_price" => setCurrency(post('item_selling_price')[$key]),
					"item_discount" => setCurrency(post('item_discount')[$key]),
					"total_price" => setCurrency(post('total_price')[$key]),
					"status_transaction" => __CLASS__,
					"status_type" =>  $status_type,
					"invoice_reference" => $this->data['invoice_code'],
					"created_by" => logged('id'),
				]
			);
		}
		return $this->items_history_model->create_batch($history_items);
	}

	private function add_order_item()
	{
		$order_items = array();
		foreach (post('item_code') as $key => $value) {
			array_push(
				$order_items,
				[
					"invoice" => $this->data['invoice_code'],
					"item_id" => post('item_id')[$key],
					"item_code" => post('item_code')[$key],
					"item_name" => post('item_name')[$key],
					"item_order_quantity" => post('item_order_quantity')[$key],
					"item_unit" => post('item_unit')[$key],
					"capital_price" => setCurrency(post('item_capital_price')[$key]),
					"selling_price" => setCurrency(post('item_selling_price')[$key]),
					"item_discount" => setCurrency(post('item_discount')[$key]),
					"total_price" => setCurrency(post('total_price')[$key]),
					"created_by" => logged('id'),
				]
			);
		}
		return $this->order_purchase_model->create_batch($order_items);
	}
	private function add_invoice()
	{
		$invoice = array(
			'invoice_code' => $this->data['invoice_code'],
			'total_price' => setCurrency(post('sub_total')),
			'discounts' => setCurrency(post('discount')),
			'shipping_cost' => setCurrency(post('shipping_cost')),
			'other_cost' => setCurrency(post('other_cost')),
			'grand_total' => setCurrency(post('grand_total')),
			'supplier' => post('supplier_code'),
			'payment_type' => post('payment_type'),
			'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'termint',
			'note' => post('discount'),
			'created_by' => logged('id'),
		);
		return $this->purchase_model->create($invoice);
	}
	private function add_items()
	{
		$items = array();
		foreach (post('item_code') as $key => $value) {
			$current_item = $this->items_model->getById(post('item_id')[$key]);
			array_push(
				$items,
				[
					'category' => $current_item->category,
					'brand' => $current_item->brand,
					'brands' => $current_item->brands,
					'mg' => $current_item->mg,
					'ml' => $current_item->ml,
					'vg' => $current_item->vg,
					'pg' => $current_item->pg,
					'flavour' => $current_item->flavour,
					'customs' => $current_item->customs,
					'is_active' => $current_item->is_active,
					//
					"item_code" => post('item_code')[$key],
					"item_name" => post('item_name')[$key],
					"quantity" => post('item_order_quantity')[$key],
					"unit" => post('item_unit')[$key],
					"capital_price" => setCurrency(post('item_capital_price')[$key]),
					"selling_price" => setCurrency(post('item_selling_price')[$key]),
					"created_by" => logged('id'),
				]
			);
		}
		// return $items;
		return $this->items_model->create_batch($items);
	}
}

/* End of file Purchasing.php */
/* Abstract file Location: ./application/controllers/Invoice.php */
/* Location: ./application/controllers/invoices/Purchasing.php */