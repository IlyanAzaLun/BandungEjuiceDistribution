

    public function serverside_datatables_data_sale_items_profit() /// HERE
    {
        $response = array();

		$postData = $this->input->post();

		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value
		$dateStart = $postData['startDate'];
		$dateFinal = $postData['finalDate'];
		$customer = $postData['customer'];
		$user = $postData['users'];
		$group_by = $postData['group_by'];
		$logged = logged('id');

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
        $this->db->like('invoice_code', 'INV/SALE/', 'after');
        $this->db->where('is_cancelled', 0);
        $this->db->group_by('invoice_code');
		// $records = $this->db->get('invoice_transaction_list_item')->result();
		$records = $this->db->get('fifo_items')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(transaction.invoice_code) as allcount');
        $this->db->join('invoice_selling sale', 'transaction.invoice_code=sale.invoice_code', 'left');
        $this->db->where('sale.is_transaction', 1);
        $this->db->where('transaction.is_cancelled', 0);
		if ($dateStart != '') {
            $this->db->group_start();
            $this->db->where("transaction.created_at >=", $dateStart);
			$this->db->where("transaction.created_at <=", $dateFinal);
            $this->db->group_end();
		}else{            
            $this->db->like("transaction.created_at", date("Y-m"), 'after');
		}
		// $records = $this->db->get('invoice_transaction_list_item transaction')->result();
		$records = $this->db->get('fifo_items transaction')->result();
		$totalRecordwithFilter = $records[0]->allcount;
		
        ## Fetch records
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
        $this->db->join("invoice_selling sale", "transaction.invoice_code=sale.invoice_code", "left");
		$this->db->join("users is_have", "sale.is_have = is_have.id", "left");
		$this->db->join("items", "transaction.item_id = items.id", "left");
		$this->db->join("(SELECT invoice_code, SUM(item_quantity) item_quantity FROM invoice_transaction_list_item GROUP BY invoice_code) list_items", "list_items.invoice_code = transaction.reference_purchase", "left");

        $this->db->where("sale.is_transaction", 1);
        $this->db->where("sale.is_cancelled", 0);
        $this->db->where("transaction.is_cancelled", 0);
        if($customer != ''){
            $this->db->select('
            transaction.customer_code
           ,customer.store_name');
            $this->db->where("transaction.customer_code", $customer);
        }
        if($user != ''){
            $this->db->select('
            transaction.created_by
           ,users.name
           ,sale.is_have
           ,is_have.name AS is_have_name');
            $this->db->group_start();
            $this->db->where("transaction.created_by", $user);
            $this->db->or_where("sale.is_have", $user);
            $this->db->group_end();
        }
		if ($dateStart != '') {
            $this->db->group_start();
			$this->db->where("transaction.created_at >=", $dateStart);
			$this->db->where("transaction.created_at <=", $dateFinal);
            $this->db->group_end();
		}
        else{
			$this->db->like("transaction.created_at", date("Y-m"), 'after');
		}
        switch ($group_by) {
            case 'monthly':
                # code...
                $this->db->select('
               ,SUM(sale.grand_total) AS grand_total
               ,SUM(sale.shipping_cost) AS shipping_cost
               ,SUM(sale.other_cost) AS other_cost
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
                ,SUM(sale.other_cost) AS other_cost
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
                ,SUM(sale.other_cost) AS other_cost
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
               ,SUM(sale.other_cost) AS other_cost
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
                 ,SUM(sale.other_cost) AS other_cost
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
                ,SUM(sale.other_cost) AS other_cost
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
                ,sale.other_cost
                ,sale.discounts
                ');
                $this->db->group_by("transaction.invoice_code");
                break;
        }
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('fifo_items transaction')->result();
		$data = array();
		foreach ($records as $record) {

			$data[] = array(
				'is_have' => $record->is_have,
				'is_have_name' => $record->is_have_name,
				'created_at' => $record->created_at,
				'updated_at' => $record->updated_at,
				'invoice_code' => $record->invoice_code,
				'customer_code' => $record->customer_code,
				'store_name' => $record->store_name,
				'pseudo_price' => $record->pseudo_price,
				'item_capital_price' => $record->time_capital_price,
				'item_selling_price' => $record->total_price,
				'shipping_cost' => $record->shipping_cost,
				'other_cost' => $record->other_cost,
				'discounts' => $record->discounts,
				'grand_total' => $record->grand_total,
				'profit' => $record->profit,
				'name' => $record->name,
				'calc' => $record->calc,
			);
		}
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
    public function data_sale_items_profit()
    {		
        $postData = $this->input->post();
		$dateStart = $postData['startDate'];
		$dateFinal = $postData['finalDate'];
		$customer = $postData['customer'];
		$user = $postData['users'];
		$group_by = $postData['group_by'];

        
		$this->db->select("
        , DATE_FORMAT(transaction.created_at, '%Y') AS year
        , DATE_FORMAT(transaction.created_at, '%Y%m') AS yearmount
        , SUM((CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT))) AS time_capital_price 
        , SUM(CAST(transaction.total_price AS INT)) AS total_price
        ,(SUM(CAST(transaction.total_price AS INT))-SUM((CAST(transaction.item_capital_price AS INT) * CAST(transaction.item_quantity AS INT)))) AS profit
        ");
        $this->db->join("invoice_selling sale", "transaction.invoice_code=sale.invoice_code", "left");

        $this->db->where("sale.is_transaction", 1);
        $this->db->where("sale.is_cancelled", 0);
        $this->db->where("transaction.is_cancelled", 0);
        if($customer != ''){
            $this->db->select('
            transaction.customer_code
           ,customer.store_name');
            $this->db->where("transaction.customer_code", $customer);
        }
        if($user != ''){
            $this->db->select('
            transaction.created_by
           ,sale.is_have');
            $this->db->group_start();
            $this->db->where("transaction.created_by", $user);
            $this->db->or_where("sale.is_have", $user);
            $this->db->group_end();
        }
		if ($dateStart != '') {
            $this->db->group_start();
			$this->db->where("sale.created_at >=", $dateStart);
			$this->db->where("sale.created_at <=", $dateFinal);
            $this->db->group_end();
		}
        else{
			$this->db->like("sale.created_at", date("Y-m"), 'after');
		}
        switch ($group_by) {
            case 'monthly':
                # code...
                $this->db->group_by("year");
                break;
            
            default:
                # code...
                $this->db->group_by("yearmount");
                break;
        }
		$result1 = $this->db->get('fifo_items transaction')->result();

        $this->db->select("
            DATE_FORMAT(invoice_selling.created_at, '%Y') AS year
            , DATE_FORMAT(invoice_selling.created_at, '%Y%m') AS yearmount
            , SUM(invoice_selling.total_price) AS total_price
            , SUM(invoice_selling.discounts) AS discounts
            , SUM(invoice_selling.shipping_cost) AS shipping_cost
            , SUM(invoice_selling.other_cost) AS other_cost 
        ");
        if($customer != ''){
            $this->db->where("invoice_selling.customer", $customer);
        }
        if($user != ''){
            $this->db->group_start();
            $this->db->where("invoice_selling.is_have", $user);
            $this->db->group_end();
        }
		if ($dateStart != '') {
            $this->db->group_start();
			$this->db->where("invoice_selling.created_at >=", $dateStart);
			$this->db->where("invoice_selling.created_at <=", $dateFinal);
            $this->db->group_end();
		}
        else{
			$this->db->like("invoice_selling.created_at", date("Y-m"), 'after');
		}
        switch ($group_by) {
            case 'monthly':
                # code...
                $this->db->group_by("year");
                break;
            
            default:
                # code...
                $this->db->group_by("yearmount");
                break;
        }
        $this->db->group_start();
        $this->db->where("invoice_selling.is_transaction", 1);
        $this->db->where("invoice_selling.is_cancelled", 0);
        $this->db->group_end();
		$result2 = $this->db->get('invoice_selling')->result();

        $records = array_merge($result1, $result2);
        $this->output->set_content_type('application/json')->set_output(json_encode($records));
    }