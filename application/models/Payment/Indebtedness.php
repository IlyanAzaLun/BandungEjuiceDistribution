<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indebtedness extends MY_Model {

	public $table = 'invoice_payment';

	public function __construct()
	{
		parent::__construct();
	}
    
    public function select_invoice_by_customer_code($data)
    {
		$this->db->select('
        payment.id
        , payment.invoice_code
        , payment.date_start
        , payment.date_due
        , payment.customer_code
        , payment.grand_total
        , payment.payup
        , payment.leftovers
        , payment.status_payment
        , payment.payment_type
        , payment.bank_id
        , payment.is_cancelled
        , payment.cancel_note
        , payment.created_by
        , payment.created_at
        , payment.updated_by
        , payment.updated_at
        , payment.description
        , user_created.name as user_created');
        // $this->db->join('(SELECT invoice_code, MAX(created_at) AS maxDate FROM invoice_payment) temp', 'temp.invoice_code = payment.invoice_code', 'right');
        $this->db->join('users user_created', 'user_created.id=payment.created_by', 'left');
		if ($data['date']['date_start'] != '') {
			$this->db->group_start();
			$this->db->where("payment.created_at >=", DateFomatDb($data['date']['date_start'], true));
			$this->db->where("payment.created_at <=", DateFomatDb($data['date']['date_end'], true));
			$this->db->group_end();
		}
        $this->db->group_start();
        $this->db->where('payment.customer_code', $data['supplier_code']);
        $this->db->where('payment.leftovers >=', '0');
        $this->db->group_start();
        $this->db->where('payment.created_at', '(SELECT MAX(created_at) FROM invoice_payment WHERE invoice_code = payment.invoice_code)', false);
        $this->db->group_end();
        $this->db->group_end();
        $this->db->group_by('payment.invoice_code');

        return $this->db->get($this->table." payment")->result();

    }

    public function select_invoice($data)
    {
        $this->db->group_start();
        $this->db->where('payment.invoice_code', $data['invoice_code']);
        $this->db->where('payment.leftovers >', '0');
        $this->db->group_end();
        $this->db->group_by('payment.invoice_code');

        return $this->db->get($this->table." payment")->result();
    }
}

/* End of file Account_bank_model.php */
/* Location: ./application/models/Account_bank_model.php */