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
        $this->db->where('order_code', $data);
        return $this->db->get($this->table)->result();
    }
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */