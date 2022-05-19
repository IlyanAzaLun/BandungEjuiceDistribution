<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipper extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Shipper';
		$this->page_data['page']->menu = 'Shipper';
	}

    public function index()
    {
        $this->list();
    }

	public function list()
	{
		ifPermissions('shipper_transaction_list');
		$this->page_data['title'] = 'shipping_list';
		$this->page_data['page']->submenu = 'list_shipper';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'invoice/order/is_confirmation',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	

	public function report()
	{
		ifPermissions('shipper_transaction_report');
		$this->page_data['title'] = 'shipping_report';
		$this->page_data['page']->submenu = 'report_shipper';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'invoice/order/is_confirmation',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	public function destination()
	{	
		$customer = $this->customer_model->get_information_customer(get('id'));
		$data['customer'] = $customer;
	
		$this->load->library('pdf');
	
        $options = $this->pdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $this->pdf->setOptions($options);


		$this->pdf->setPaper('A6', 'landscape');
		$this->pdf->filename = "$customer->store_name.pdf";
		$this->pdf->load_view('validation/shipper/destination', $data);
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
	public function quality_control()
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
			$this->load->view('validation/shipper/quality_control', $this->page_data);
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
					"is_cancelled" => 0,
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
			);// Check
			
			echo '<pre>';
			// // EDIT
			$this->update_item_fifo($items); // UPDATE ON PURCHASE QUANTITY
			$this->create_item_history($items, ['CREATE', 'UPDATE']);
			$this->create_or_update_invoice($payment);
			$this->update_items($items);
			$this->create_or_update_list_item_transcation($items);
			$this->create_or_update_list_item_fifo($items); // CREATE ONLY FOR SALE.. NEED FOR CANCEL
			// die();
			
			echo '</pre>';
			$this->activity_model->add("Edit Sale Invoice, #" . $this->data['invoice_code'], (array) $payment);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Edit Sale Invoice Successfully');

			redirect('invoice/sale/list');
		}
	}
	
}