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
        if ($row == 'last') {
            return $this->db->get_where($this->table, ['invoice_code' => $data])->last_row();
        }
        return $this->db->get_where($this->table, ['invoice_code' => $data])->first_row();
    }
    
    public function update_by_code_invoice($code, $data)
    {
        $this->db->where('invoice_code', $code);
        $this->db->update($this->table, $data);
        return $code;
    }
}