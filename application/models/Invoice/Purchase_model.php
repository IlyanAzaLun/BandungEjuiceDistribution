<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_model extends MY_Model
{

    public $table = 'invoice_purchasing';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_invoice_purchasing_by_code($data)
    {
        $this->db->where('invoice_code', $data);
        return $this->db->get($this->table)->row();
    }

    public function get_code_invoice_purchase()
    {
        $now = date('ymd');
        $this->db->like('invoice_code', "INV/PURCHASE/$now/", 'after');
        $this->db->order_by('id', 'DESC');
        $data = $this->db->get($this->table)->num_rows();
        return sprintf("INV/PURCHASE/$now/%06s", (int)$data + 1);
    }

    public function update_by_code($id, $data)
    {
        $this->db->where('invoice_code', $id);
        $this->db->update($this->table, $data);
        return $id;
    }
}

/* End of file Purchase_model.php */
/* Location: ./application/models/Invoice/Purchase_model.php */