<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function index()
	{
		$data['is_cancelled'] = true;
		$this->page_data['num_rows_orderSale'] = $this->order_model->get_num_rows_order_sale();
		$this->page_data['num_rows_orderSale_cancelled'] = $this->order_model->get_num_rows_order_sale($data);
		$this->page_data['num_rows_sale'] = $this->sale_model->get_num_rows_sale();
		$this->page_data['items_assets'] = $this->items_fifo_model->assets_items();
		$this->load->view('dashboard', $this->page_data);
	}
	
	public function expense_statment_daily()
	{
		$this->db->select('
		  SUM((CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS "Total Capital Price" 
        , SUM(CAST(transaction.total_price AS INT)) AS "Total Selling"
		, DATE_FORMAT(sell.created_at, "%Y%m%d") as days');
		$this->db->join('invoice_selling sell', 'transaction.invoice_code = sell.invoice_code', 'left');
		
        $this->db->group_start();
        $this->db->where("sell.is_transaction", 1);
        $this->db->where("sell.is_cancelled", 0);
        $this->db->where("transaction.is_cancelled", 0);
        $this->db->group_end();

		$this->db->where("DATE_FORMAT(sell.created_at, '%Y%m%d') = ", "DATE_FORMAT(now(), '%Y%m%d')", false);
		$this->db->group_by('days');
		$result = $this->db->get('fifo_items transaction')->result_array()[0];

		$data['datasets'][]['data'] = array_values($result);
		$data['datasets'][0]['backgroundColor'] = array_values(array("#2ecc71", "#3498db"));
		$data['labels'] = array_keys($result);
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function expense_statment_monthly()
	{
		$this->db->select('SUM(CAST(invoice_selling.grand_total AS INT)) AS "Total Selling"');
		$this->db->group_start();
		$this->db->where("invoice_selling.is_cancelled", 0);
		$this->db->where("invoice_selling.is_transaction", 1);
		$this->db->where("DATE_FORMAT(invoice_selling.created_at, '%Y%m') = ", "DATE_FORMAT(now(), '%Y%m')", false);
		$this->db->group_end();
		$result = $this->db->get('invoice_selling')->row_array();
		
		
		$this->db->select('SUM(CAST(invoice_purchasing.grand_total AS INT)) AS "Total Purchasing"');
		
		$this->db->group_start();
		$this->db->where("invoice_purchasing.is_cancelled", 0);
		$this->db->where("invoice_purchasing.is_transaction", 1);
		$this->db->where("DATE_FORMAT(invoice_purchasing.created_at, '%Y%m') = ", "DATE_FORMAT(now(), '%Y%m')", false);
		$this->db->group_end();
		$result = array_merge($result, $this->db->get('invoice_purchasing')->row_array());
		
		$data['datasets'][]['data'] = array_values($result);
		$data['datasets'][0]['backgroundColor'] = array_values(array("#2ecc71", "#3498db"));
		$data['labels'] = array_keys($result);
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function monthly_statistic()
	{
		//
		$user = logged('id');
        $haspermission = hasPermissions('dashboard_staff');
		$this->db->select("
            , selling.created_at
            , selling.updated_at
            , DATE_FORMAT(selling.created_at, '%Y-%m-%d') AS yearmountday
            , DATE_FORMAT(selling.created_at, '%Y-%m') AS yearmount
            , DATE_FORMAT(selling.created_at, '%M') AS mount
            , SUM( (CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) ) AS item_capital_price
			, selling.is_have
			, user.name
		");
		$this->db->join('invoice_selling selling', 'selling.invoice_code = transaction.invoice_code', 'left');
		$this->db->join('users user', 'user.id = selling.is_have');
		$this->db->group_start();
        $this->db->where('transaction.is_cancelled', 0);
        $this->db->where('selling.is_cancelled', 0);
        $this->db->where('selling.is_transaction', 1);
        if(!$haspermission){
			$this->db->group_start();
			$this->db->where("selling.created_by", $user);
			$this->db->or_where("selling.is_have", $user);
			$this->db->group_end();
        }
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where("selling.created_at >=", 'DATE_ADD(NOW(), INTERVAL -6 MONTH)',false);
		$this->db->where("selling.created_at <=", 'NOW()',false);
		$this->db->group_end();

		$this->db->group_by("yearmount, selling.is_have");
		$records = $this->db->get('fifo_items transaction')->result();

		//
		$this->db->select("
              DATE_FORMAT(invoice_selling.created_at, '%Y-%m-%d') AS yearmountday
			, DATE_FORMAT(invoice_selling.created_at, '%Y-%m') AS yearmount
			, DATE_FORMAT(invoice_selling.created_at, '%M') AS month
			, DATE_FORMAT(invoice_selling.created_at, '%W') AS days
            , SUM(invoice_selling.total_price) AS item_selling_price
            , SUM(invoice_selling.discounts) AS discounts
            , SUM(invoice_selling.shipping_cost) AS shipping_cost
            , SUM(invoice_selling.other_cost) AS other_cost 
        ");
		$this->db->group_start();
        $this->db->where('invoice_selling.is_cancelled', 0);
        $this->db->where('invoice_selling.is_transaction', 1);
		if(!$haspermission){
			$this->db->group_start();
			$this->db->where("invoice_selling.created_by", $user);
			$this->db->or_where("invoice_selling.is_have", $user);
			$this->db->group_end();
        }
		$this->db->group_end();
		$this->db->group_start();
		$this->db->where("invoice_selling.created_at >=", 'DATE_ADD(NOW(), INTERVAL -6 MONTH)',false);
		$this->db->where("invoice_selling.created_at <=", 'NOW()',false);
		$this->db->group_end();
		
		$this->db->group_by("yearmount, invoice_selling.is_have");
		
		$result2 = $this->db->get('invoice_selling')->result();
		//

		$user = array_unique(array_column($records, 'is_have'));		
		$data['labels'] = array_unique(array_column($records, 'mount'));
		foreach ($records as $key => $value) {
			$rand1 = rand(0, 255);
			$rand2 = rand(0, 255);
			$rand3 = rand(0, 255);
			$data['datasets'][array_search($value->is_have, $user)]['data'][] = ($result2[$key]->item_selling_price - $value->item_capital_price) - $result2[$key]->discount - $result2[$key]->other_cost;
			
			$data['datasets'][array_search($value->is_have, $user)]['label'] = $value->name;
			$data['datasets'][array_search($value->is_have, $user)]['backgroundColor'] = "rgba($rand2, $rand1, $rand3, 0.05)";
			$data['datasets'][array_search($value->is_have, $user)]['borderColor'] = "rgba($rand2, $rand1, $rand3, 0.60)";
		}
		// TIDY UP DATA ARRAY
		$data['labels'] = array_values($data['labels']);
		$data['datasets'] = array_values($data['datasets']);
		## Response
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function daily_statistic()
	{$user = logged('id');
        $haspermission = hasPermissions('dashboard_staff');

		// ITEMS WITH REFERENCE
		$this->db->select("
		, DATE_FORMAT(sale.created_at, '%Y-%m-%d') AS yearmountday
		, DATE_FORMAT(sale.created_at, '%Y-%m') AS yearmount
		, DATE_FORMAT(sale.created_at, '%M') AS month
		, DATE_FORMAT(sale.created_at, '%W') AS days

        , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS time_capital_price 
        , SUM(CAST(transaction.total_price AS INT)) AS total_price
        ,(SUM(CAST(transaction.total_price AS INT))-SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS profit
        ");
        $this->db->join("invoice_selling sale", "transaction.invoice_code=sale.invoice_code", "left");

        $this->db->where("sale.is_transaction", 1);
        $this->db->where("sale.is_cancelled", 0);
        $this->db->where("transaction.is_cancelled", 0);
		if(!$haspermission){
			$this->db->select('sale.created_by ,sale.is_have');
			$this->db->group_start();
			$this->db->where("sale.created_by", $user);
			$this->db->or_where("sale.is_have", $user);
			$this->db->group_end();
        }
		if(post('user_id') != ""){
			$this->db->group_start();
			$this->db->where("sale.is_have", post('user_id'));
			$this->db->group_end();
		}
		if (post('date') != "") {
            $this->db->group_start();
			$this->db->where("sale.created_at >=", post('date')['startdate']);
			$this->db->where("sale.created_at <=", post('date')['enddate']);
            $this->db->group_end();
		}else{
			$this->db->group_start();
			$this->db->where("sale.created_at >=", 'DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), "%Y-%m-%d 00:00:00")',false);
			$this->db->where("sale.created_at <=", 'NOW()',false);
			$this->db->group_end();
		}
		switch (post('group_by')) {
			case 'monthly':
				$this->db->group_by("yearmount");
				break;
				
			default:
				$this->db->group_by("yearmountday");
				break;
		}
		$result1 = $this->db->get('fifo_items transaction')->result();

		// INVOICE WITH REFERENCE
        $this->db->select("
              DATE_FORMAT(invoice_selling.created_at, '%Y-%m-%d') AS yearmountday
			, DATE_FORMAT(invoice_selling.created_at, '%Y-%m') AS yearmount
			, DATE_FORMAT(invoice_selling.created_at, '%M') AS month
			, DATE_FORMAT(invoice_selling.created_at, '%W') AS days
            , SUM(invoice_selling.total_price) AS total_price
            , SUM(invoice_selling.discounts) AS discounts
            , SUM(invoice_selling.shipping_cost) AS shipping_cost
            , SUM(invoice_selling.other_cost) AS other_cost 
        ");
        if(!$haspermission){
			$this->db->select('invoice_selling.created_by ,invoice_selling.is_have');
			$this->db->group_start();
			$this->db->where("invoice_selling.created_by", $user);
			$this->db->or_where("invoice_selling.is_have", $user);
			$this->db->group_end();
        }
		if(post('user_id') != ""){
			$this->db->group_start();
			$this->db->where("invoice_selling.is_have", post('user_id'));
			$this->db->group_end();
		}
		if (post('date') != "") {
            $this->db->group_start();
			$this->db->where("invoice_selling.created_at >=", post('date')['startdate']);
			$this->db->where("invoice_selling.created_at <=", post('date')['enddate']);
            $this->db->group_end();
		}else{
			$this->db->group_start();
			$this->db->where("invoice_selling.created_at >=", 'DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), "%Y-%m-%d 00:00:00")',false);
			$this->db->where("invoice_selling.created_at <=", 'NOW()',false);
			$this->db->group_end();
		}
		switch (post('group_by')) {
			case 'monthly':
				$this->db->group_by("yearmount");
				break;
				
			default:
				$this->db->group_by("yearmountday");
				break;
		}
        $this->db->group_start();
        $this->db->where("invoice_selling.is_transaction", 1);
        $this->db->where("invoice_selling.is_cancelled", 0);
        $this->db->group_end();
		$result2 = $this->db->get('invoice_selling')->result();

		$data['labels'] = array();
		$data['datasets'][]['data'] = array();
		foreach ($result2 as $key => $value) {
			switch (post('group_by')) {
				case 'monthly':
					array_push($data['labels'], $value->month);
					break;
				default:				
					array_push($data['labels'], $value->yearmountday);
					break;
			}
			
			$data['datasets'][0]['label'] = 'Profit';
			$data['datasets'][0]['backgroundColor'] = 'rgba(0, 109, 255, 0.39)';
			$data['datasets'][0]['borderColor'] = 'rgba(0, 109, 255, 0.30)';

			array_push($data['datasets'][0]['data'], $result1[$key]->total_price - $result1[$key]->time_capital_price - $value->discounts - $value->shipping_cost - $value->other_cost);
			
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
	
	public function test()
	{
		$user = logged('id');
        $haspermission = hasPermissions('dashboard_staff');

		// ITEMS WITH REFERENCE
		$this->db->select("
		, DATE_FORMAT(transaction.created_at, '%Y-%m-%d') AS yearmountday
		, DATE_FORMAT(transaction.created_at, '%Y-%m') AS yearmount
		, DATE_FORMAT(transaction.created_at, '%M') AS month
		, DATE_FORMAT(transaction.created_at, '%W') AS days

        , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS time_capital_price 
        , SUM(CAST(transaction.total_price AS INT)) AS total_price
        ,(SUM(CAST(transaction.total_price AS INT))-SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS profit
        ");
		$this->db->join("(SELECT * FROM invoice_purchasing WHERE is_shipping_cost = 1 AND is_cancelled = 0) purchase", "purchase.invoice_code = transaction.reference_purchase", "left");
        $this->db->join("invoice_selling sale", "transaction.invoice_code=sale.invoice_code", "left");

        $this->db->where("sale.is_transaction", 1);
        $this->db->where("sale.is_cancelled", 0);
        $this->db->where("transaction.is_cancelled", 0);
		if(!$haspermission){
			$this->db->select('transaction.created_by ,sale.is_have');
			$this->db->group_start();
			$this->db->where("transaction.created_by", $user);
			$this->db->or_where("sale.is_have", $user);
			$this->db->group_end();
        }
		if(post('user_id') != ""){
			$this->db->group_start();
			$this->db->where("sale.is_have", post('user_id'));
			$this->db->group_end();
		}
		if (post('date') != "") {
            $this->db->group_start();
			$this->db->where("transaction.created_at >=", post('date')['startdate']);
			$this->db->where("transaction.created_at <=", post('date')['enddate']);
            $this->db->group_end();
		}else{
			$this->db->group_start();
			$this->db->where("transaction.created_at >=", 'DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), "%Y-%m-%d 00:00:00")',false);
			$this->db->where("transaction.created_at <=", 'NOW()',false);
			$this->db->group_end();
		}
		switch (post('group_by')) {
			case 'monthly':
				$this->db->group_by("yearmount");
				break;
				
			default:
				$this->db->group_by("yearmountday");
				break;
		}
		$result1 = $this->db->get('fifo_items transaction')->result();

		// INVOICE WITH REFERENCE
        $this->db->select("
              DATE_FORMAT(invoice_selling.created_at, '%Y-%m-%d') AS yearmountday
			, DATE_FORMAT(invoice_selling.created_at, '%Y-%m') AS yearmount
			, DATE_FORMAT(invoice_selling.created_at, '%M') AS month
			, DATE_FORMAT(invoice_selling.created_at, '%W') AS days
            , SUM(invoice_selling.total_price) AS total_price
            , SUM(invoice_selling.discounts) AS discounts
            , SUM(invoice_selling.shipping_cost) AS shipping_cost
            , SUM(invoice_selling.other_cost) AS other_cost 
        ");
        if(!$haspermission){
			$this->db->select('invoice_selling.created_by ,invoice_selling.is_have');
			$this->db->group_start();
			$this->db->where("invoice_selling.created_by", $user);
			$this->db->or_where("invoice_selling.is_have", $user);
			$this->db->group_end();
        }
		if(post('user_id') != ""){
			$this->db->group_start();
			$this->db->where("invoice_selling.is_have", post('user_id'));
			$this->db->group_end();
		}
		if (post('date') != "") {
            $this->db->group_start();
			$this->db->where("invoice_selling.created_at >=", post('date')['startdate']);
			$this->db->where("invoice_selling.created_at <=", post('date')['enddate']);
            $this->db->group_end();
		}else{
			$this->db->group_start();
			$this->db->where("invoice_selling.created_at >=", 'DATE_FORMAT(DATE_ADD(now(), INTERVAL -7 DAY), "%Y-%m-%d 00:00:00")',false);
			$this->db->where("invoice_selling.created_at <=", 'NOW()',false);
			$this->db->group_end();
		}
		switch (post('group_by')) {
			case 'monthly':
				$this->db->group_by("yearmount");
				break;
				
			default:
				$this->db->group_by("yearmountday");
				break;
		}
        $this->db->group_start();
        $this->db->where("invoice_selling.is_transaction", 1);
        $this->db->where("invoice_selling.is_cancelled", 0);
        $this->db->group_end();
		$result2 = $this->db->get('invoice_selling')->result();

		$data['labels'] = array();
		$data['datasets'][]['data'] = array();
		foreach ($result2 as $key => $value) {
			switch (post('group_by')) {
				case 'monthly':
					array_push($data['labels'], $value->month);
					break;
				default:				
					array_push($data['labels'], $value->yearmountday);
					break;
			}
			
			$data['datasets'][0]['label'] = 'Profit';
			$data['datasets'][0]['backgroundColor'] = 'rgba(0, 109, 255, 0.39)';
			$data['datasets'][0]['borderColor'] = 'rgba(0, 109, 255, 0.30)';

			array_push($data['datasets'][0]['data'], $result1[$key]->total_price - $result1[$key]->time_capital_price - $value->discounts - $value->shipping_cost - $value->other_cost);
			// array_push($data['datasets'][0]['data'], $record->profit); // IS REAL
			
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

}
/*
SELECT * FROM invoice_selling 
WHERE created_at >= DATE_ADD(NOW(), INTERVAL -7 DAY)
  AND created_at <= now()
*/

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */