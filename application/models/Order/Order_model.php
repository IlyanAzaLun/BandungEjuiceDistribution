<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends MY_Model
{

    public $table = 'order_sale';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_num_rows_order_sale($data = false)
    {
        $logged = logged('id');
        $haspermission = hasPermissions('dashboard_staff');
        
        if($data['is_cancelled']){
            $this->db->where('is_cancelled', 1);
        }else{
            $this->db->where('is_cancelled', 0);
        }
		$this->db->where('is_created', 0);
        if(!$haspermission){
            $this->db->where('created_by', $logged);
        }
		return $this->db->get($this->table)->num_rows();
    }

    public function get_order_selling_by_code($data)
    {
        $this->db->where('order_code', $data);
        return $this->db->get($this->table)->row();
    }

    public function get_code_order_sale()
    {
        $now = date('ym');
        $this->db->like('order_code', "ORDER/SALE/$now/", 'after');
        $this->db->order_by('id', 'DESC');
        $data = $this->db->get($this->table)->num_rows();
        return sprintf("ORDER/SALE/$now/%06s", (int)$data + 1);
    }

    public function update_by_code($id, $data)
    {
        $this->db->where('order_code', $id);
        $this->db->update($this->table, $data);
        return $id;
    }
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */