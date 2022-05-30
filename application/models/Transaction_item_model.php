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
        if($data != ''){
            $date = preg_split('/[-]/', trim($data["min"]));
            $data['date'] = array(
                'date_start' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[0]))), "Y-m-d"), 
                'date_finish' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[1]))), "Y-m-d")
            );
        }
        $this->db->select("
            ,transaction.created_at
            ,transaction.updated_at
            ,DATE_FORMAT(transaction.created_at, '%Y%m%d') AS yearmountday
            ,DATE_FORMAT(transaction.created_at, '%Y%m') AS yearmount
            ,SUM(transaction.item_capital_price) AS item_capital_price
            ,SUM(transaction.item_selling_price) AS item_selling_price
            ,(SUM(transaction.item_selling_price)-SUM(transaction.item_capital_price)) AS profit");
		$this->db->join("users", "transaction.created_by = users.id", "left");
        $this->db->join("customer_information customer", "customer.customer_code = transaction.customer_code", "left");
        $this->db->like('transaction.invoice_code', 'INV/SALE/', 'after');
        $this->db->where('transaction.is_cancelled', 0);
        if($data['customer_code'] != ''){
            $this->db->select('
            transaction.customer_code
           ,customer.store_name');
            $this->db->where("transaction.customer_code", $data['customer_code']);
        }
        if($data['user_id'] != ''){
            $this->db->select('
            transaction.created_by
           ,users.name');
            $this->db->where("transaction.created_by", $data['user_id']);
        }
		if ($data['date']['date_start'] != '') {
            $this->db->group_start();
			$this->db->where("transaction.created_at >=", $data['date']['date_start']);
			$this->db->where("transaction.created_at <=", $data['date']['date_finish']);
            $this->db->group_end();
		}else{
			$this->db->like("transaction.created_at", date("Y-m"), 'after');
		}
        
        switch ($group_by) {
            case 'monthly':
                # code...
                $this->db->group_by("yearmount");
                break;

            case 'monthly_by_customer':
                # code...
                $this->db->select('
                 transaction.customer_code
                ,customer.store_name');
                $this->db->group_by("yearmount, transaction.customer_code");
                break;
            
            case 'monthly_by_user':
                # code...
                $this->db->select('
                 transaction.created_by
                ,users.name');
                $this->db->group_by("yearmount, transaction.created_by");
                break;
                    
            case 'daily':
                # code...
                $this->db->group_by("yearmountday");
                break;
            
            case 'daily_by_user':
                # code...
                $this->db->select('
                    transaction.created_by
                    ,users.name');
                $this->db->group_by("yearmountday, transaction.created_by");
                break;

            case 'daily_by_customer':
                # code...
                $this->db->select('
                 transaction.customer_code
                ,customer.store_name');
                $this->db->group_by("yearmountday, transaction.customer_code");
                break;
            
            default:
                # code...
                $this->db->select('
                 transaction.invoice_code
                ,transaction.created_by
                ,users.name
                ,transaction.customer_code
                ,customer.store_name');
                $this->db->group_by("invoice_code");
                break;
        }
        return $this->db->get('invoice_transaction_list_item transaction')->result();
    }

}

/* End of file Transaction_item_model.php */
/* Location: ./application/models/Transaction_item_model.php */