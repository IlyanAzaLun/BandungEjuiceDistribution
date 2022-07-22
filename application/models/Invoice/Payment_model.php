<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_model extends MY_Model
{
    public $table = 'invoice_payment';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_payment_information_by_invoice_code($data, $row = false)
    {
        
        $this->db->where('invoice_code', $data);
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->table)->first_row();
    }
    
    public function update_by_code_invoice($code, $data)
    {
        $this->db->where('invoice_code', $code);
        $this->db->update($this->table, $data);
        return $code;
    }
}