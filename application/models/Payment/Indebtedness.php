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
        purchasing.invoice_code as invoice_code_purchasing,
        payment.*,
        `user_created`.`name` as `user_created`');
        $this->db->join('(
        SELECT
            MAX(`invoice_payment`.`id`) AS id, 
            `invoice_payment`.`invoice_code`, 
            `invoice_payment`.`date_start`, 
            `invoice_payment`.`date_due`, 
            `invoice_payment`.`customer_code`, 
            `invoice_payment`.`grand_total`, 
            SUM(`invoice_payment`.payup) AS payup, 
            MIN(
                CAST(`invoice_payment`.leftovers as INT)
            ) AS leftovers, 
            `invoice_payment`.`status_payment`, 
            `invoice_payment`.`payment_type`, 
            `invoice_payment`.`bank_id`, 
            `invoice_payment`.`is_cancelled`, 
            `invoice_payment`.`cancel_note`, 
            `invoice_payment`.`created_by`, 
            MIN(`invoice_payment`.created_at) AS created_at, 
            MAX(`invoice_payment`.created_at) AS last_payment_at, 
            IFNULL(
                `invoice_payment`.updated_at, 
                MAX(`invoice_payment`.created_at)
            ) AS updated_at, 
            `invoice_payment`.`updated_by`, 
            `invoice_payment`.`description`
        FROM `invoice_payment` `invoice_payment` 
        WHERE `invoice_payment`.`is_cancelled` IS NULL
        GROUP BY `invoice_payment`.`invoice_code`) payment', 'purchasing.invoice_code = payment.invoice_code');
        $this->db->join('users user_created', 'user_created.id=payment.created_by', 'left');
		if ($data['date']['date_start'] != '') {
			$this->db->group_start();
			$this->db->where("payment.created_at >=", DateFomatDb($data['date']['date_start'], true));
			$this->db->where("payment.created_at <=", DateFomatDb($data['date']['date_end'], true));
			$this->db->group_end();
		}
        $this->db->group_start();
        $this->db->where('payment.customer_code', $data['supplier_code']);
        // $this->db->where('payment.leftovers >=', '0');
        $this->db->where('payment.is_cancelled', null);
        // $this->db->group_start();
        // $this->db->where('payment.created_at', '(SELECT MAX(created_at) FROM invoice_payment WHERE invoice_code = payment.invoice_code)', false);
        // $this->db->group_end();
        $this->db->group_end();
        $this->db->group_by('payment.invoice_code');
        $this->db->order_by('payment.created_at', 'ASC');
        return $this->db->get("invoice_purchasing purchasing")->result();
    }

    public function fetch_history_payment_by_invoice_code($data)
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
        , user_created.name as user_created
        , user_updated.name as user_updated');
        $this->db->group_start();
        $this->db->where('payment.invoice_code', $data['invoice_code']);
        $this->db->where('payment.is_cancelled', null);
        $this->db->group_end();
        $this->db->join('users user_created', 'user_created.id=payment.created_by', 'left');
        $this->db->join('users user_updated', 'user_updated.id=payment.updated_by', 'left');
        $this->db->order_by('payment.id', 'DESC');
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