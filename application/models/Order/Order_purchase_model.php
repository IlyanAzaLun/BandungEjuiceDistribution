<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_purchase_model extends MY_Model
{

    public $table = 'order_purchase';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_order_invoice_purchasing_by_code($data)
    {
        $this->db->where('invoice', $data);
        return $this->db->get($this->table)->result();
    }
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */