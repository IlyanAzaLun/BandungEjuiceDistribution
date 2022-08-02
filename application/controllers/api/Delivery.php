<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Delivery extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($searchValue = '', $id = 0)
	{
		$response = array();

		$postData = $this->input->get();
        ## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('delivery_head')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(delivery_head.id) as allcount');
        $this->db->join('customer_information', 'customer_information.customer_code = delivery_head.customer_code','left');
        $this->db->join('supplier_information', 'supplier_information.customer_code = delivery_head.customer_code','left');
		if ($searchValue != '') {
            $this->db->group_start();
            $this->db->like('customer_information.store_name', $searchValue, 'both');
            $this->db->like('supplier_information.owner_name', $searchValue, 'both');
            $this->db->or_like('customer_information.store_name', $searchValue, 'both');
            $this->db->or_like('supplier_information.owner_name', $searchValue, 'both');
            $this->db->group_end();
        }
		$records = $this->db->get('delivery_head')->result();
		$totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("
            delivery_head.`id`
            , delivery_head.`delivery_code`
            , delivery_head.`from_address`
            , delivery_head.`destination_address`
            , delivery_head.`pack_by`
            , delivery_head.`pack`
            , delivery_head.`weight`
            , delivery_head.`shipping_cost`
            , delivery_head.`customer_code`
            , delivery_head.`store_name`
            , delivery_head.`note`
            , delivery_head.`is_controlled_by`
            , delivery_head.`is_delivered`
            , delivery_head.`is_cancelled`
            , delivery_head.`is_shipping_cost`
            , delivery_head.`cancel_note`
            , delivery_head.`created_at`
            , delivery_head.`created_by`
            , delivery_head.`updated_at`
            , delivery_head.`updated_by`
            , NVL2(customer_information.customer_code, 'CUSTOMER', 'SUPPLIER') AS type
            , IFNULL(customer_information.store_name,supplier_information.store_name) AS store_name
            , IFNULL(customer_information.owner_name,supplier_information.owner_name) AS owner_name");
        $this->db->join('customer_information', 'customer_information.customer_code = delivery_head.customer_code','left');
        $this->db->join('supplier_information', 'supplier_information.customer_code = delivery_head.customer_code','left');
        $this->db->from('delivery_head');
        if(!empty($id)){
            $records = $this->db->where("id", $id)->row();
        }else{
            if ($searchValue != '') {
                $this->db->group_start();
                $this->db->like('customer_information.store_name', $searchValue, 'both');
                $this->db->like('supplier_information.owner_name', $searchValue, 'both');
                $this->db->or_like('customer_information.store_name', $searchValue, 'both');
                $this->db->or_like('supplier_information.owner_name', $searchValue, 'both');
                $this->db->group_end();
            }
            $this->db->order_by($columnName, $columnSortOrder);
            $this->db->limit($rowperpage, $start);
            $records = $this->db->get()->result();
        }

        $data = array();

		foreach ($records as $record) {

			$data[] = array(
                'id'=>$record->id,
                'delivery_code'=>$record->delivery_code,
                'from_address'=>$record->from_address,
                'destination_address'=>$record->destination_address,
                'pack_by'=>$record->pack_by,
                'pack'=>$record->pack,
                'weight'=>$record->weight,
                'shipping_cost'=>$record->shipping_cost,
                'customer_code'=>$record->customer_code,
                'store_name'=>$record->store_name,
                'note'=>$record->note,
                'is_controlled_by'=>$record->is_controlled_by,
                'is_delivered'=>$record->is_delivered,
                'is_cancelled'=>$record->is_cancelled,
                'is_shipping_cost'=>$record->is_shipping_cost,
                'cancel_note'=>$record->cancel_note,
                'created_at'=>$record->created_at,
                'created_by'=>$record->created_by,
                'updated_at'=>$record->updated_at,
                'updated_by'=>$record->updated_by,
                'type'=>$record->type,
                'store_name'=>$record->store_name,
                'owner_name'=>$record->owner_name
			);
		}
        

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
		);
     
        $this->response($response, REST_Controller::HTTP_OK);
	}
      
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {
        // $input = $this->input->post();
        // $this->db->insert('items',$input);
     
        // $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    } 
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        // $input = $this->put();
        // $this->db->update('items', $input, array('id'=>$id));
     
        // $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
    }
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        // $this->db->delete('items', array('id'=>$id));
       
        // $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
    }
    	
}