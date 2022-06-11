<?php
// NOT USED
defined('BASEPATH') or exit('No direct script access allowed');

class Items extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function remove_item_from_list_order_transaction()
	{
		$dataPost = $this->input->post();
		$order_item = $this->order_list_item_model->getById($dataPost['idorder']);
		$item  = $this->items_model->getById($order_item->item_id);
		$item_update = array(
			'item_code' => $item->item_code, 
			'item_name' => $item->item_name, 
			'quantity' => $item->quantity + $order_item->item_order_quantity, 
			'updated_by' => logged('id'), 
		);
		$this->items_model->update($item->id, $item_update);
		$this->order_list_item_model->delete($dataPost['idorder']);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}