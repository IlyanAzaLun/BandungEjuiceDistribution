<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items_fifo_model extends MY_Model
{

    public $table = 'fifo_items';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_items_fifo($data)
    {
        $this->db->where('invoice_code', $data);
        $this->db->where('is_cancelled', 0);
        return $this->db->get($this->table)->result();
    }
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */