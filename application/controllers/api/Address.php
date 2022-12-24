<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Address extends REST_Controller {
    
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
		$records = $this->db->get('address_information')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(address_information.id) as allcount');
        $this->db->join('customer_information', 'customer_information.customer_code = address_information.customer_code','left');
        $this->db->join('supplier_information', 'supplier_information.customer_code = address_information.customer_code','left');
		if ($searchValue != '') {
            $this->db->group_start();
            $this->db->like('customer_information.store_name', $searchValue, 'both');
            $this->db->or_like('supplier_information.owner_name', $searchValue, 'both');
            $this->db->or_like('customer_information.store_name', $searchValue, 'both');
            $this->db->or_like('supplier_information.owner_name', $searchValue, 'both');
            $this->db->group_end();
        }
        $this->db->group_start();
        $this->db->like('address_information.is_active', 1);
        $this->db->group_end();
		$records = $this->db->get('address_information')->result();
		$totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select("
              address_information.`id`
            , address_information.`customer_code`
            , address_information.`address`
            , address_information.`village`
            , address_information.`sub_district`
            , address_information.`city`
            , address_information.`province`
            , address_information.`zip`
            , address_information.`contact_phone`
            , address_information.`contact_mail`
            , address_information.`created_at`
            , address_information.`created_by`
            , address_information.`updated_at`
            , address_information.`updated_by`
            , address_information.`note`
            , address_information.`is_active`
            , NVL2(customer_information.customer_code, 'CUSTOMER', 'SUPPLIER') AS type
            , IFNULL(customer_information.store_name,supplier_information.store_name) AS store_name
            , IFNULL(customer_information.owner_name,supplier_information.owner_name) AS owner_name");
        $this->db->join('customer_information', 'customer_information.customer_code = address_information.customer_code','left');
        $this->db->join('supplier_information', 'supplier_information.customer_code = address_information.customer_code','left');
        $this->db->from('address_information');
        
        $this->db->group_start();
        $this->db->like('address_information.is_active', 1);
        $this->db->group_end();
        
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
            $this->db->order_by("address_information.$columnName", "address_information.$columnSortOrder");
            $this->db->limit($rowperpage, $start);
            $records = $this->db->get()->result();
        }

        $data = array();

		foreach ($records as $record) {

			$data[] = array(
                'id'=>$record->id,
                'customer_code' => $record->customer_code,
                'address' => $record->address,
                'village' => $record->village,
                'sub_district' => $record->sub_district,
                'city' => $record->city,
                'province' => $record->province,
                'zip' => $record->zip,
                'contact_phone' => $record->contact_phone,
                'contact_mail' => $record->contact_mail,
                'created_at' => $record->created_at,
                'created_by' => $record->created_by,
                'updated_at' => $record->updated_at,
                'updated_by' => $record->updated_by,
                'note' => $record->note,
                'is_active' => $record->is_active,
                'type' => $record->type,
                'store_name' => $record->store_name,
                'owner_name' => $record->owner_name,
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
        $input = $this->input->post();
        $this->db->insert('items',$input);
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    } 
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        $input = $this->put();
        $this->db->update('items', $input, array('id'=>$id));
     
        $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
    }
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        $this->db->delete('items', array('id'=>$id));
       
        $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
    }
    	
}