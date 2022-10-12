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

    public function get_report_items_transaction($data, $parameter = false)
    {
        if($data != ''){
            $date = preg_split('/[-]/', trim($parameter["min"]));
            $data['date'] = array(
                'date_start' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[0]))), "Y-m-d").' 00:00:00', 
                'date_finish' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[1]))), "Y-m-d").' 23:59:59'
            );
        }
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
        $this->db->group_start();
        $this->db->like('transaction.invoice_code', $data['params'], 'after');
        $this->db->where('transaction.is_cancelled', 0);
        $this->db->group_end();
        if ($data['date']['date_start'] != '') {
            $this->db->group_start();
			$this->db->where("transaction.created_at >=", $data['date']['date_start']);
			$this->db->where("transaction.created_at <=", $data['date']['date_finish']);
            $this->db->group_end();
		}else{
            $this->db->group_start();
			$this->db->like("transaction.created_at", date("Y-m"), 'after');
            $this->db->group_end();
		}
        if($parameter['item_code']){
            $this->db->group_start();
            $this->db->where('transaction.item_code', $parameter['item_code']);
            $this->db->group_end();
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
                'date_start' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[0]))), "Y-m-d").' 00:00:00', 
                'date_finish' => date_format(date_create(str_replace('/','-', str_replace(' ','',$date[1]))), "Y-m-d").' 23:59:59'
            );
        }
		$this->db->select("transaction.id
        , transaction.item_id
        , transaction.item_code
        , transaction.item_name
        , transaction.item_capital_price
        , transaction.item_quantity
        , transaction.item_unit
        , transaction.is_cancelled
        , transaction.created_at
        , transaction.updated_at
        , transaction.updated_by
        , DATE_FORMAT(transaction.created_at, '%Y%m%d') AS yearmountday
        , DATE_FORMAT(transaction.created_at, '%Y%m') AS yearmount
        , CAST(SUM(CAST(`purchase`.`shipping_cost` AS DECIMAL)) / list_items.item_quantity AS DECIMAL) AS calc,
        , SUM(CAST(IF(STRCMP(items.shadow_selling_price,0), items.shadow_selling_price, transaction.item_capital_price) AS INT) * CAST(transaction.item_quantity AS INT)) AS pseudo_price
        , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS time_capital_price 
        , SUM(CAST(transaction.total_price AS INT)) AS total_price
        ,(SUM(CAST(transaction.total_price AS INT))-SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS profit");
		$this->db->join("users", "transaction.created_by = users.id", "left");
		// $this->db->join("(SELECT fifo_items.invoice_code, SUM(fifo_items.item_quantity) AS item_total FROM fifo_items GROUP BY invoice_code) items_purchase", "items_purchase.invoice_code = transaction.invoice_code", "left");
        $this->db->join("(SELECT * FROM invoice_purchasing WHERE is_shipping_cost = 1 AND is_cancelled = 0) purchase", "purchase.invoice_code = transaction.reference_purchase", "left");
        $this->db->join("customer_information customer", "customer.customer_code = transaction.customer_code", "left");
        $this->db->join("invoice_selling sale", "transaction.invoice_code = sale.invoice_code", "left");
		$this->db->join("users is_have", "sale.is_have = is_have.id", "left");
		$this->db->join("items", "transaction.item_id = items.id", "left");
		$this->db->join("(SELECT invoice_code, SUM(item_quantity) item_quantity FROM invoice_transaction_list_item GROUP BY invoice_code) list_items", "list_items.invoice_code = transaction.reference_purchase", "left");

        $this->db->where("sale.is_transaction", 1);
        $this->db->where("sale.is_cancelled", 0);
        
        if($data['customer_code'] != ''){
            $this->db->select('
            transaction.customer_code
           ,customer.store_name');
            $this->db->where("transaction.customer_code", $data['customer_code']);
        }
        if($data['user_id'] != ''){
            $this->db->select('
            transaction.created_by
           ,users.name
           ,sale.is_have
           ,is_have.name AS is_have_name');
            $this->db->group_start();
            $this->db->where("transaction.created_by", $data['user_id']);
            $this->db->or_where("sale.is_have", $data['customer_code']);
            $this->db->group_end();
        }
		if ($data['date']['date_start'] != '') {
            $this->db->group_start();
			$this->db->where("transaction.created_at >=", $data['date']['date_start']);
			$this->db->where("transaction.created_at <=", $data['date']['date_finish']);
            $this->db->group_end();
		}
        else{
			$this->db->like("transaction.created_at", date("Y-m"), 'after');
		}
        
        switch ($data['group_by']) {
            case 'monthly':
                # code...
                $this->db->select('
               ,SUM(sale.grand_total) AS grand_total
               ,SUM(sale.shipping_cost) AS shipping_cost
               ,SUM(sale.discounts) AS discounts
               ');
                $this->db->group_by("yearmount");
                break;

            case 'monthly_by_customer':
                # code...
                $this->db->select('
                 transaction.customer_code
                ,customer.store_name
                ,SUM(sale.grand_total) AS grand_total
                ,SUM(sale.shipping_cost) AS shipping_cost
                ,SUM(sale.discounts) AS discounts
                ');
                $this->db->group_by("yearmount, transaction.customer_code");
                break;
            
            case 'monthly_by_user':
                # code...
                $this->db->select('
                 transaction.created_by
                ,users.name
                ,sale.is_have
                ,is_have.name AS is_have_name
                ,SUM(sale.grand_total) AS grand_total
                ,SUM(sale.shipping_cost) AS shipping_cost
                ,SUM(sale.discounts) AS discounts
                ');
                // $this->db->group_by("yearmount, transaction.created_by, sale.is_have");
                $this->db->group_by("yearmount, sale.is_have");
                break;
                    
            case 'daily':
                # code...
                $this->db->select('
               ,SUM(sale.grand_total) AS grand_total
               ,SUM(sale.shipping_cost) AS shipping_cost
               ,SUM(sale.discounts) AS discounts
               ');
                $this->db->group_by("yearmountday");
                break;
            
            case 'daily_by_user':
                # code...
                $this->db->select('
                 transaction.created_by
                 ,users.name
                 ,sale.is_have
                 ,is_have.name AS is_have_name
                 ,SUM(sale.grand_total) AS grand_total
                 ,SUM(sale.shipping_cost) AS shipping_cost
                 ,SUM(sale.discounts) AS discounts
                 ');
                // $this->db->group_by("yearmountday, transaction.created_by, sale.is_have");
                $this->db->group_by("yearmountday, sale.is_have");
                break;

            case 'daily_by_customer':
                # code...
                $this->db->select('
                 transaction.customer_code
                ,customer.store_name
                ,SUM(sale.grand_total) AS grand_total
                ,SUM(sale.shipping_cost) AS shipping_cost
                ,SUM(sale.discounts) AS discounts
                ');
                $this->db->group_by("yearmountday, transaction.customer_code");
                break;
            
            default:
                # code...
                $this->db->select('
                 transaction.invoice_code
                ,transaction.created_by
                ,users.name
                ,sale.is_have
                ,is_have.name AS is_have_name
                ,transaction.customer_code
                ,customer.store_name
                ,sale.grand_total
                ,sale.shipping_cost
                ,sale.discounts
                ');
                $this->db->group_by("transaction.invoice_code");
                break;
        }
		return $this->db->get('fifo_items transaction')->result();
        // return $this->db->get('invoice_transaction_list_item transaction')->result();
    }

}

/* End of file Transaction_item_model.php */
/* Location: ./application/models/Transaction_item_model.php */