<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function remove_item_order_purchase()
	{
		$order = $this->order_purchase_model->getById(post('id'));
		$item = $this->items_model->getById($order->item_id);
		$history = array(
			'item_id' => $order->item_id, 
			'item_code' => $order->item_code, 
			'item_name' => $order->item_name, 
			'item_quantity' => $item->quantity, 
			'item_order_quantity' => $order->item_order_quantity, 
			'item_unit' => $order->item_unit, 
			'item_capital_price' => $order->capital_price, 
			'item_selling_price' => $order->selling_price, 
			'item_discount' => $order->item_discount, 
			'total_price' => $order->total_price, 
			'status_type' => 'EDIT', 
			'status_transaction' => 'Purchase', 
			'invoice_reference' => $order->invoice, 
			'created_by' => logged('id'), 
		);		
		$item_update = array(
			'item_code' => $item->item_code, 
			'item_name' => $item->item_name, 
			'quantity' => $item->quantity - $order->item_order_quantity, 
			'capital_price' => $item->capital_price, 
			'selling_price' => $item->capital_price, 
			'updated_by' => logged('id'), 
		);
		$this->items_history_model->create($history);
		$this->items_model->update($item->id, $item_update);
		$this->order_purchase_model->delete($order->id);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}