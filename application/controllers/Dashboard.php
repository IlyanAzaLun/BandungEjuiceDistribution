<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function index()
	{
		$data['is_cancelled'] = true;
		$this->page_data['num_rows_orderSale'] = $this->order_model->get_num_rows_order_sale();
		$this->page_data['num_rows_orderSale_cancelled'] = $this->order_model->get_num_rows_order_sale($data);
		$this->page_data['num_rows_sale'] = $this->sale_model->get_num_rows_sale();
		$this->load->view('dashboard', $this->page_data);
	}
	
	public function expense_statment()
	{
		$this->db->select('
		  SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS "Capital Price" 
        , SUM(CAST(transaction.total_price AS INT)) AS "Selling Price"
		');
		$this->db->where("DATE_FORMAT(transaction.created_at, '%Y%m') = ", "concat(year(now()), month(now()))", false);
		$result = $this->db->get('fifo_items transaction')->result_array()[0];
		$data['datasets'][]['data'] = array_values($result);
		$data['labels'] = array_keys($result);
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function monthly_statistic()
	{
		//
		$user = logged('id');
        $haspermission = hasPermissions('dashboard_staff');
		$this->db->select("
            , transaction.created_at
            , transaction.updated_at
            , DATE_FORMAT(transaction.created_at, '%Y-%m-%d') AS yearmountday
            , DATE_FORMAT(transaction.created_at, '%Y-%m') AS yearmount
            , DATE_FORMAT(transaction.created_at, '%M') AS mount
            , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS item_capital_price
			, SUM(CAST(IF(STRCMP(items.shadow_selling_price,0), items.shadow_selling_price, transaction.item_capital_price) AS INT) * CAST(transaction.item_quantity AS INT)) AS pseudo_price
            , SUM(CAST(transaction.total_price AS INT)) AS item_selling_price
			, SUM(CAST(transaction.total_price AS INT)) AS total_price
			,(SUM(CAST(transaction.total_price AS INT))-SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS profit
			, SUM(selling.discounts) AS discounts
			, SUM(selling.shipping_cost) AS shipping_cost
			, selling.is_have
			, user.name
		");
		$this->db->join('invoice_selling selling', 'selling.invoice_code = transaction.invoice_code', 'left');
		$this->db->join("items", "transaction.item_id = items.id", "left");
		$this->db->join('users user', 'user.id = selling.is_have');
		$this->db->group_start();
        $this->db->where('transaction.is_cancelled', 0);
        if(!$haspermission){
			$this->db->group_start();
			$this->db->where("transaction.created_by", $user);
			$this->db->or_where("selling.is_have", $user);
			$this->db->group_end();
        }
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where("transaction.created_at >=", 'DATE_ADD(NOW(), INTERVAL -6 MONTH)',false);
		$this->db->where("transaction.created_at <=", 'NOW()',false);
		$this->db->group_end();

		$this->db->group_by("yearmount, selling.is_have");
		$records = $this->db->get('fifo_items transaction')->result();
		$user = array_unique(array_column($records, 'is_have'));		
		$data['labels'] = array_unique(array_column($records, 'mount'));
		foreach ($records as $key => $value) {
			$rand1 = rand(0, 255);
			$rand2 = rand(0, 255);
			$rand3 = rand(0, 255);
			$data['datasets'][array_search($value->is_have, $user)]['data'][] = $value->item_selling_price - $value->pseudo_price - $value->discounts + $value->shipping_cost;
			
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
	{
		$user = logged('id');
        $haspermission = hasPermissions('dashboard_staff');
		$this->db->select("
            , transaction.created_at
            , transaction.updated_at
            , DATE_FORMAT(transaction.created_at, '%Y-%m-%d') AS yearmountday
            , DATE_FORMAT(transaction.created_at, '%Y-%m') AS yearmount
            , DATE_FORMAT(transaction.created_at, '%W') AS days
            , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS item_capital_price
            , SUM(CAST(transaction.total_price AS INT)) AS item_selling_price
			, SUM(CAST(transaction.total_price AS INT)) AS total_price
			,(SUM(CAST(transaction.total_price AS INT))-SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS profit
		");
		$this->db->join('invoice_selling selling', 'selling.invoice_code = transaction.invoice_code');
		$this->db->group_start();
        $this->db->where('transaction.is_cancelled', 0);
        if(!$haspermission){
			$this->db->group_start();
			$this->db->where("transaction.created_by", $user);
			$this->db->or_where("selling.is_have", $user);
			$this->db->group_end();
        }
		$this->db->group_end();

		if(post('user_id') != ""){
			$this->db->group_start();
			$this->db->where("selling.is_have", post('user_id'));
			$this->db->group_end();
		}
		if(post('date') != "") {
			$this->db->group_start();
			$this->db->where("transaction.created_at >=", post('date')['startdate']);
			$this->db->where("transaction.created_at <=", post('date')['enddate']);
			$this->db->group_end();
		}else{
			$this->db->group_start();
			$this->db->where("transaction.created_at >=", 'DATE_ADD(NOW(), INTERVAL -7 DAY)',false);
			$this->db->where("transaction.created_at <=", 'NOW()',false);
			$this->db->group_end();
		}
		$this->db->group_by("yearmountday");
		$records = $this->db->get('fifo_items transaction')->result();
		$data['labels'] = array();
		$data['datasets'][]['data'] = array();
		foreach ($records as $key => $record) {
			array_push($data['labels'], $record->days);
			
			$data['datasets'][0]['label'] = 'Profit';
			$data['datasets'][0]['backgroundColor'] = 'rgba(0, 109, 255, 0.39)';
			$data['datasets'][0]['borderColor'] = 'rgba(0, 109, 255, 0.30)';

			array_push($data['datasets'][0]['data'], $record->profit);
		}
		## Response
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
	
	public function test()
	{
		$user = logged('id');
        $haspermission = hasPermissions('dashboard_staff');
		$this->db->select("
            , transaction.created_at
            , transaction.updated_at
            , DATE_FORMAT(transaction.created_at, '%Y-%m-%d') AS yearmountday
            , DATE_FORMAT(transaction.created_at, '%Y-%m') AS yearmount
            , DATE_FORMAT(transaction.created_at, '%M') AS mount
            , SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)) AS item_capital_price
            , SUM(CAST(transaction.total_price AS INT)) AS item_selling_price
			, SUM(CAST(transaction.total_price AS INT)) AS total_price
			,(SUM(CAST(transaction.total_price AS INT))-SUM(CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS profit
		");
		$this->db->join('invoice_selling selling', 'selling.invoice_code = transaction.invoice_code');
		$this->db->group_start();
        $this->db->where('transaction.is_cancelled', 0);
        if(!$haspermission){
			$this->db->group_start();
			$this->db->where("transaction.created_by", $user);
			$this->db->or_where("selling.is_have", $user);
			$this->db->group_end();
        }
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where("transaction.created_at >=", 'DATE_ADD(NOW(), INTERVAL -6 MONTH)',false);
		$this->db->where("transaction.created_at <=", 'NOW()',false);
		$this->db->group_end();

		$this->db->group_by("yearmount");
		$records = $this->db->get('fifo_items transaction')->result();
		$data['labels'] = array();
		$data['datasets'][]['data'] = array();
		foreach ($records as $key => $record) {
			array_push($data['labels'], $record->mount);

			$data['datasets'][0]['label'] = 'Profit';
			$data['datasets'][0]['backgroundColor'] = 'rgba(0, 109, 255, 0.39)';
			$data['datasets'][0]['borderColor'] = 'rgba(0, 109, 255, 0.30)';

			array_push($data['datasets'][0]['data'], bd_nice_number((int) $record->profit));
		}
		## Response
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