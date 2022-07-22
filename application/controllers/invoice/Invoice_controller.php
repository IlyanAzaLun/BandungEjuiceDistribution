<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class Invoice_controller extends MY_Controller {

    public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
        // ... 
	}
	
    public function view()
	{
        // ... 
    }

	public function create()
	{
        // ... 
	}

    public function serverside_datatables_data_purchase()
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

        ## Search 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (name like '%".$searchValue."%' or email like '%".$searchValue."%' ) ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('users')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('users')->result();

        $data = array();

        foreach($records as $record ){

            $data[] = array( 
                "name"=>$record->name,
                "email"=>$record->email,
                "last_login"=>$record->last_login,
            ); 
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode( $response ));

    }
}