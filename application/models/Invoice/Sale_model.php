<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sale_model extends MY_Model
{

    public $table = 'invoice_selling';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_invoice_selling_by_code($data)
    {
        $this->db->where('invoice_code', $data);
        return $this->db->get($this->table)->row();
    }

    public function get_num_rows_sale()
    {
        $logged = logged('id');
        $haspermission = hasPermissions('dashboard_staff');

        $this->db->where('is_cancelled', 0);
		$this->db->where('is_child', 0);
        if(!$haspermission){
            $this->db->where('created_by', $logged);
        }
        $this->db->like("created_at", date("Y-m-d"), 'after');
		return $this->db->get($this->table)->num_rows();
    }

    public function get_code_invoice_sale()
    {
        $now = date('ym');
        $this->db->like('invoice_code', "INV/SALE/$now/", 'after');
        $this->db->order_by('id', 'DESC');
        $data = $this->db->get($this->table)->num_rows();
        return sprintf("INV/SALE/$now/%06s", (int)$data + 1);
    }

    public function update_by_code($id, $data)
    {
        $this->db->where('invoice_code', $id);
        $this->db->update($this->table, $data);
        return $id;
    }
}

/* End of file Sale_model.php */
/* Location: ./application/models/Invoice/Sale_model.php */