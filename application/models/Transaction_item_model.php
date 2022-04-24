<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_item_model extends MY_Model {

	public $table = 'invoice_transaction_list_item';

	public function __construct()
	{
		parent::__construct();
	}
	
    public function get_transaction_item_by_code_invoice($data)
    {
        $this->db->where('invoice_code', $data);
        $this->db->where('is_cancelled', 0);
        return $this->db->get($this->table)->result();
    }

}

/* End of file Transaction_item_model.php */
/* Location: ./application/models/Transaction_item_model.php */