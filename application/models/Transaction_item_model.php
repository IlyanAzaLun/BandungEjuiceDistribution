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
            ,fifo_items.item_capital_price as item_capital_prices 
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
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('transaction.is_cancelled', 0);
        $this->db->where('invoice.is_cancelled', 0);
        $this->db->where('invoice.is_transaction', 1);
        $this->db->group_end();
        $this->db->group_start();    
        if ($data['date']['date_start'] != '') {
			$this->db->where("transaction.created_at >=", $data['date']['date_start']);
			$this->db->where("transaction.created_at <=", $data['date']['date_finish']);
		}else{
			$this->db->like("transaction.created_at", date("Y-m"), 'after');
		}
        $this->db->group_end();
        if($parameter['item_code']){
            $this->db->group_start();    
            $this->db->where('transaction.item_code', $parameter['item_code']);
            $this->db->group_end();
        }
        $this->db->join('fifo_items', '(transaction.invoice_code = fifo_items.invoice_code AND transaction.item_id = fifo_items.item_id)', 'left');
        if($parameter['params'] == 'purchase' || $parameter['params'] == 'purchase_returns' ){
            $this->db->join('invoice_purchasing invoice', 'transaction.invoice_code = invoice.invoice_code', 'right');
        }else{
            $this->db->join('invoice_selling invoice', 'transaction.invoice_code = invoice.invoice_code', 'right');
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
        // ITEM RESULT
		$this->db->select("
          sale.created_at
        , sale.updated_at
        , sale.updated_by
        , DATE_FORMAT(sale.created_at, '%Y%m%d') AS yearmountday
        , DATE_FORMAT(sale.created_at, '%Y%m') AS yearmount

        , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS time_capital_price 
        , SUM(CAST(transaction.total_price AS INT)) AS total_price");
        $this->db->join("invoice_selling sale", "transaction.invoice_code = sale.invoice_code", "rightd");
        $this->db->join("customer_information customer", "customer.customer_code = transaction.customer_code", "left");
		$this->db->join("users", "transaction.created_by = users.id", "left");
		$this->db->join("users is_have", "sale.is_have = is_have.id", "left");

        $this->db->group_start();
        $this->db->where("sale.is_transaction", 1);
        $this->db->where("sale.is_cancelled", 0);
        $this->db->where("transaction.is_cancelled", 0);
        $this->db->group_end();

        if($data['customer_code'] != ''){
            $this->db->select('
              sale.customer
            , customer.store_name');
            $this->db->group_start();
            $this->db->where("sale.customer", $data['customer_code']);
            $this->db->group_end();
        }
        if($data['user_id'] != ''){
            $this->db->select('
              sale.created_by
            , users.name
            , sale.is_have
            , is_have.name AS is_have_name');
            $this->db->group_start();
            $this->db->where("sale.created_by", $data['user_id']);
            $this->db->or_where("sale.is_have", $data['customer_code']);
            $this->db->group_end();
        }
		if ($data['date']['date_start'] != '') {
            $this->db->group_start();
			$this->db->where("sale.created_at >=", $data['date']['date_start']);
			$this->db->where("sale.created_at <=", $data['date']['date_finish']);
            $this->db->group_end();
		}
        else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
        
        switch ($data['group_by']) {
            case 'monthly':
                $this->db->group_by("yearmount");
                break;

            case 'monthly_by_customer':
                # code...
                $this->db->select('
                  transaction.customer_code
                , customer.store_name');
                $this->db->group_by("yearmount, sale.customer");
                break;
            
            case 'monthly_by_user':
                # code...
                $this->db->select('
                  transaction.created_by
                , users.name
                , sale.is_have
                , is_have.name AS is_have_name');
                $this->db->group_by("yearmount, sale.is_have");
                break;
                    
            case 'daily':
                $this->db->group_by("yearmountday");
                break;
            
            case 'daily_by_user':
                # code...
                $this->db->select('
                   users.name
                 , sale.is_have
                 , is_have.name AS is_have_name');
                $this->db->group_by("yearmountday, sale.is_have");
                break;

            case 'daily_by_customer':
                # code...
                $this->db->select('
                  sale.customer
                , customer.store_name');
                $this->db->group_by("yearmountday, sale.customer");
                break;
            
            default:
                # code...
                $this->db->select('
                 sale.invoice_code
                ,sale.created_by
                ,sale.expedition
                ,sale.note
                ,users.name
                ,sale.is_have
                ,is_have.name AS is_have_name
                ,sale.customer
                ,customer.store_name');
                $this->db->group_by("sale.invoice_code");
                break;
        }
        $this->db->order_by('sale.id', 'DESC');
		$result_items = $this->db->get('fifo_items transaction')->result();

        $this->db->select("
              DATE_FORMAT(invoice_selling.created_at, '%Y%m%d') AS yearmountday
            , DATE_FORMAT(invoice_selling.created_at, '%Y%m') AS yearmount
            , DATE_FORMAT(invoice_selling.created_at, '%Y') AS year
            , SUM(invoice_selling.total_price) AS total_price
            , SUM(invoice_selling.discounts) AS discounts
            , SUM(invoice_selling.shipping_cost) AS shipping_cost
            , SUM(invoice_selling.other_cost) AS other_cost
            , SUM(invoice_selling.grand_total) AS grand_total");
        $this->db->group_start();
        $this->db->where('invoice_selling.is_transaction', 1);
        $this->db->where('invoice_selling.is_cancelled', 0);
        $this->db->group_end();

        if($data['customer_code'] != ''){
            $this->db->group_start();
            $this->db->where("invoice_selling.customer_code", $data['customer_code']);
            $this->db->group_end();
        }
        if($data['user_id'] != ''){
            $this->db->group_start();
            $this->db->where("invoice_selling.created_by", $data['user_id']);
            $this->db->or_where("invoice_selling.is_have", $data['customer_code']);
            $this->db->group_end();
        }
		if ($data['date']['date_start'] != '') {
            $this->db->group_start();
			$this->db->where("invoice_selling.created_at >=", $data['date']['date_start']);
			$this->db->where("invoice_selling.created_at <=", $data['date']['date_finish']);
            $this->db->group_end();
		}
        else{
			$this->db->like("invoice_selling.created_at", date("Y-m"), 'after');
		}
        
        switch ($data['group_by']) {
            case 'monthly':
                $this->db->group_by("yearmount");
                break;

            case 'monthly_by_customer':
                # code...
                $this->db->group_by("yearmount, invoice_selling.customer_code");
                break;
            
            case 'monthly_by_user':
                # code...
                $this->db->group_by("yearmount, invoice_selling.is_have");
                break;
                    
            case 'daily':
                $this->db->group_by("yearmountday");
                break;
            
            case 'daily_by_user':
                # code...
                $this->db->group_by("yearmountday, invoice_selling.is_have");
                break;

            case 'daily_by_customer':
                # code...
                $this->db->group_by("yearmountday, invoice_selling.customer_code");
                break;
            
            default:
                # code...
                $this->db->select('invoice_selling.invoice_code');
                $this->db->group_by("invoice_selling.invoice_code");
                break;
        }
        $this->db->order_by('invoice_selling.id', 'DESC');
		$result_invoice = $this->db->get('invoice_selling')->result();

        $data = array();
        foreach ($result_items as $key => $value) {
            # code...
            $data[] = array(
				'is_have' => isset($value->is_have)?$value->is_have:null,
				'is_have_name' => isset($value->is_have_name)?$value->is_have_name:null,
				'created_at' => $value->created_at,
				'updated_at' => $value->updated_at,
				'invoice_code' => isset($value->invoice_code)?$value->invoice_code:null,
				'customer' => isset($value->customer)?$value->customer:null,
				'store_name' => isset($value->store_name)?$value->store_name:null,
				'time_capital_price' => $value->time_capital_price,
				'total_price' => $result_invoice[$key]->total_price,
				'shipping_cost' => $result_invoice[$key]->shipping_cost,
				'other_cost' => $result_invoice[$key]->other_cost,
				'discounts' => $result_invoice[$key]->discounts,
                'expedition' => $value->expedition,
				'grand_total' => $result_invoice[$key]->grand_total,
				'name' => isset($value->name)?$value->name:null,
			);
        }
        return $data;
    }

}

/* End of file Transaction_item_model.php */
/* Location: ./application/models/Transaction_item_model.php */