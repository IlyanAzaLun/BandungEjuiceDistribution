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

    public function get_report_items_profit($data = '')
    {
        $this->db->select("
            transaction.created_at
            ,transaction.updated_at
            ,DATE_FORMAT(transaction.created_at, '%Y%m%d') AS yearmountday
            ,DATE_FORMAT(transaction.created_at, '%Y%m') AS yearmount
            ,SUM(transaction.item_capital_price) AS item_capital_price
            ,SUM(transaction.item_selling_price) AS item_selling_price
            ,(SUM(transaction.item_selling_price)-SUM(transaction.item_capital_price)) AS profit
            ,transaction.customer_code
            ,transaction.created_by
            ,users.name
            ,customer.store_name");
        $this->db->join("users", "transaction.created_by = users.id", "left");
        $this->db->join("customer_information customer", "customer.customer_code = transaction.customer_code", "left");
        $this->db->like('transaction.invoice_code', 'INV/SALE/', 'after');
        $this->db->where('transaction.is_cancelled', 0);
        if(@$data['customer_code'] != ''){
            $this->db->where("transaction.customer_code", $data['customer_code']);
        }
        if(@$data['user_id'] != ''){
            $this->db->where("transaction.created_by", $data['user_id']);
        }
        if(@$data['date']['date_start'] != '') {
            $this->db->where("transaction.created_at >=", $data['date']['date_start']);
            $this->db->where("transaction.created_at <=", $data['date']['date_due']);
        }
        else{
            $this->db->like("transaction.created_at", date("Y-m"), 'after');
        }
        switch (@$data['group_by']) {
            case 'monthly':
                # code...
                $this->db->group_by("yearmount");
                break;
                
            case 'daily':
                # code...
                $this->db->group_by("yearmountday");
                break;
            
            default:
                # code...
                $this->db->select('transaction.invoice_code');
                $this->db->group_by("invoice_code");
                break;
        }
        return $this->db->get('invoice_transaction_list_item transaction')->result();
    }

}

/* End of file Transaction_item_model.php */
/* Location: ./application/models/Transaction_item_model.php */