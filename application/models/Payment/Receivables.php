<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receivables extends MY_Model {

	public $table = 'invoice_payment';

	public function __construct()
	{
		parent::__construct();
	}
    
    public function select_invoice_by_customer_code($data)
    {
        $this->db->where('customer_code', $data);
        return $this->db->get($this->table)->result();
    }
}

/* End of file Account_bank_model.php */
/* Location: ./application/models/Account_bank_model.php */