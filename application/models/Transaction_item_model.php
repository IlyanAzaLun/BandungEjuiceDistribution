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

    public function get_report_items_transaction($data)
    {
        $this->db->select(
            'transaction.*
            ,supplier.store_name
            ,customer.store_name            
            ,supplier.owner_name
            ,customer.owner_name
            ,IFNULL(supplier.supplier_type,customer.customer_type) as type

            ,address.address
            ,address.village
            ,address.sub_district
            ,address.city
            ,address.province
            ,address.zip
            ,address.contact_phone
            ,address.contact_mail

            ,user_created.id as id_user_created
            ,user_created.name as user_created
            ,user_updated.id as id_user_updated
            ,user_updated.name as user_updated'
        );
        $this->db->like('transaction.invoice_code', $data['params'], 'after');
        $this->db->where('transaction.is_cancelled', 0);
        if ($data['date']['date_start'] != '') {
			$this->db->where("transaction.created_at >=", $data['date']['date_start']);
			$this->db->where("transaction.created_at <=", $data['date']['date_due']);
		}else{
			$this->db->like("transaction.created_at", date("Y-m"), 'after');
		}
        $this->db->join('users user_created', 'transaction.created_by = user_created.id', 'left');
        $this->db->join('users user_updated', 'transaction.updated_by = user_updated.id', 'left');
        $this->db->join('address_information address', 'transaction.customer_code = address.customer_code', 'left');
        $this->db->join('supplier_information supplier', 'transaction.customer_code = supplier.customer_code', 'left');
        $this->db->join('customer_information customer', 'transaction.customer_code = customer.customer_code', 'left');
        return $this->db->get('invoice_transaction_list_item transaction')->result();   
    }

}

/* End of file Transaction_item_model.php */
/* Location: ./application/models/Transaction_item_model.php */