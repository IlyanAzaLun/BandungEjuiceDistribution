<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_list_item_model extends MY_Model
{

    public $table = 'order_sale_list_item';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_order_item_by_code_order($data)
    {
        $this->db->select('order_sale_list_item.*, users.name');
        $this->db->where('order_code', $data);
        $this->db->where('is_cancelled', 0);
        $this->db->join('users', 'order_sale_list_item.updated_by = users.id', 'left');
        return $this->db->get($this->table)->result();
    }

    public function get_order_item_by_code_order_warehouse($data)
    {
        $this->db->select('order_sale_list_item.*, users.name');
        $this->db->where('order_code', $data);
        $this->db->join('users', 'order_sale_list_item.updated_by = users.id', 'left');
        return $this->db->get($this->table)->result();
    }
    
    public function get_report_items_order($data)
    {
        $this->db->select('
            items.item_name,
            order_sale_list_item.item_code,
            order_sale_list_item.item_order_quantity,
            order_sale_list_item.item_capital_price,
            items.selling_price as selling_price,
            order_sale_list_item.item_selling_price as order_selling_price,
            order_sale.note,
            customer_information.store_name,
            users.name as marketing,
            order_sale.created_at
        ');
        $this->db->group_start();
        $this->db->where("order_sale.created_at >=", $data['date']['date_start']);
        $this->db->where("order_sale.created_at <=", $data['date']['date_due']);
        $this->db->group_end();
        $this->db->join('order_sale_list_item', 'order_sale.order_code = order_sale_list_item.order_code', 'left');
        $this->db->join('customer_information', 'order_sale.customer = customer_information.customer_code', 'left');
        $this->db->join('users', 'users.id = order_sale.is_have', 'right');
        $this->db->join('items', 'items.item_code = order_sale_list_item.item_code', 'left');
        $this->db->group_start();
        $this->db->where('order_sale.is_created', 0);
        $this->db->where('order_sale.is_cancelled', 0);
        $this->db->group_end();
        return $this->db->get('order_sale')->result();

    }
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */