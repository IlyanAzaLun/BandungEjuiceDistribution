<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/Items.php';
require_once APPPATH . 'controllers/invoice/Invoice_controller.php';
class Consignment extends Invoice_controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Consignment';
		$this->page_data['page']->menu = 'Purchase';
        $this->page_data['page']->submenu = 'consignment';
	}

    public function index()
	{
		$this->report();
	}

    public function create()
    {
		ifPermissions('purchase_create');
		// 
		$this->form_validation->set_rules('supplier_code', lang('supplier_code'), 'required|trim');
		$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
		$this->form_validation->set_rules('item_code[]', lang('item_code'), 'required|trim');
		$this->form_validation->set_rules('item_name[]', lang('item_name'), 'required|trim');
		$this->form_validation->set_rules('grand_total', lang('grandtotal'), 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->page_data['title'] = 'consignment_create';
			$this->page_data['page']->submenu_child  = 'consignment_create';
			$this->load->view('invoice/purchase/consignment/create', $this->page_data);
		} else {
			// information invoice
			$this->data['invoice_code'] = $this->purchase_model->get_code_invoice_purchase();
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
					"customer_code" => post('supplier_code'),
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
				'status_payment' => (post('payment_type') == 'cash') ? 'payed' : 'credit',
				'date_start' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_start'])),
				'date_due' => date("Y-m-d H:i:s",strtotime($this->data['date']['date_due'])),
				'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',post('created_at'))))),
				'note' => post('note'),
			);
			try {
				$this->create_item_history($items, ['CREATE', 'UPDATE']);
				$this->create_or_update_invoice($payment);
				$this->update_items($items);
				$this->create_or_update_list_item_transcation($items);
			} catch (\Throwable $th) {
				echo "<pre>";
				var_dump($th);
				echo "</pre>";
				die();
			}
			$this->activity_model->add("Create Purchasing, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'New Purchase Successfully');

			redirect('invoice/purchase/list');
		}
    }

    public function report()
    {
		ifPermissions('purchase_create');
        $this->page_data['page']->submenu_child  = 'consignment_report';
		echo "<pre>";
		var_dump($this->purchase_model->purchase_consignment());
		echo "</pre>";
    }
}