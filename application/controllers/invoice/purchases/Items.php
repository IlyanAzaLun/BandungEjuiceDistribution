<?php
// NOT USED
defined('BASEPATH') or exit('No direct script access allowed');

class Items extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function remove_item_from_list_order_transcaction()
	{
		$order = $this->transaction_item_model->getById(post('id'));
		$item  = $this->items_model->getById($order->item_id);
		$history = array(
			'item_id' => $order->item_id, 
			'item_code' => $order->item_code, 
			'item_name' => $order->item_name, 
			'item_quantity' => $item->quantity, 
			'item_order_quantity' => $order->item_quantity, 
			'item_unit' => $order->item_unit, 
			'item_capital_price' => $order->item_capital_price, 
			'item_selling_price' => $order->item_selling_price, 
			'item_discount' => $order->item_discount, 
			'total_price' => $order->total_price, 
			'status_type' => 'DELETE', 
			'status_transaction' => 'Purchase', 
			'invoice_reference' => $order->invoice_code, 
			'created_by' => logged('id'), 
		);		
		$item_update = array(
			'item_code' => $item->item_code, 
			'item_name' => $item->item_name, 
			'quantity' => $item->quantity - $order->item_quantity, 
			'updated_by' => logged('id'), 
		);
		$this->items_history_model->create($history);
		$this->items_model->update($item->id, $item_update);
		$this->transaction_item_model->update($order->id, array('is_cancelled' => 1));
		$this->items_fifo_model->update($order->id, array('is_cancelled' => 1));
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}