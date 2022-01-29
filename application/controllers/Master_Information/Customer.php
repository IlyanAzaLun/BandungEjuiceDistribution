<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Customer Management';
		$this->page_data['page']->menu = 'customer';
	}

	public function index()
	{
		$this->list();
	}

	public function list()
	{
		ifPermissions('customer_list');
        $this->page_data['page']->submenu = 'list';
        $this->page_data['title'] = 'customer_list';
        $this->load->view('customer/list', $this->page_data);
	}

	public function add()
	{
		ifPermissions('customer_add');
        $this->page_data['page']->submenu = 'add';
        $this->page_data['title'] = 'customer_add';

        $this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
        $this->form_validation->set_rules('owner_name', lang('customer_owner'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['customer_code'] = $this->customer_model->get_code_customer();
            $this->load->view('customer/add', $this->page_data);
        }else{
            $data = [
                'customer_code' => post('customer_code'),
                'store_name' => strtoupper(post('store_name')),
                'owner_name' => strtoupper(post('owner_name')),
                'customer_type' => strtoupper(post('customer_type')),
                'is_active' => 1,
                'created_by' => logged('id'),
                'note' => post('note'),
            ];
            $customer = $this->customer_model->create($data);
            
            $this->activity_model->add("New Customer #$customer, Created by User: #" . logged('id'), (array)$data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'New Customer Created Successfully');

            redirect("master_information/address/add?id=$customer");
        }
	}

    public function edit()
    {
		ifPermissions('customer_edit');
        $this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
        $this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['customer'] = $this->customer_model->getById(GET('id'));
            $this->page_data['address'] = $this->address_model->getByWhere(['customer_id' => GET('id')]);
            $this->page_data['title'] = 'customer_update';
            $this->load->view('customer/edit', $this->page_data);
        }else{
            $data = [
                'customer_code' => post('customer_code'),
                'store_name' => strtoupper(post('store_name')),
                'owner_name' => strtoupper(post('owner_name')),
                'customer_type' => strtoupper(post('customer_type')),
                'is_active' => post('is_active')?1:0,
                'updated_at' => date('Y-m-d H:i:s', time()),
                'updated_by' => logged('id'),
                'note' => post('note'),
            ];
            $customer = $this->customer_model->update(GET('id'), $data);
            
            $this->activity_model->add("Update Customer #$customer, Update by User: #" . logged('id'), (array)$this->page_data['customer']);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'Update Customer Successfully');

            redirect('master_information/customer/list');
        }
    }

    // SERVER SIDE
    public function serverside_datatables_data_customer()
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
        if ($searchValue != '') {
            $searchQuery = " (item_name like '%" . $searchValue . "%' or item_code like '%" . $searchValue . "%' ) ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('customer_information')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != ''){
            $this->db->like('store_name', $searchValue, 'both'); $this->db->or_like('owner_name', $searchValue, 'both'); 
            $this->db->or_like('customer_code', $searchValue, 'both');
        }
        $records = $this->db->get('customer_information')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if ($searchQuery != ''){
            $this->db->like('store_name', $searchValue, 'both'); $this->db->or_like('owner_name', $searchValue, 'both'); 
            $this->db->or_like('customer_code', $searchValue, 'both');
        }
        $this->db->join('address_information', 'address_information.customer_id=customer_information.id', 'right');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->where('address_information.is_active', 1);
        $this->db->group_by('customer_information.customer_code');
        $this->db->order_by('customer_information.customer_code','ASC');
        $records = $this->db->get('customer_information')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "id_customer" => $record->customer_id,
                "customer_code" => $record->customer_code,
                "store_name" => $record->store_name,
                "owner_name" => $record->owner_name,
                "customer_type" => $record->customer_type,
                
                "id" => $record->id,
                "village" => $record->village,
                "sub_district" => $record->sub_district,
                "city" => $record->city,
                "province" => $record->province,
                "zip" => $record->zip,
                "contact_phone" => $record->contact_phone,
                "contact_mail" => $record->contact_mail,

                "created_at" => $record->created_at,
                "created_by" => $record->created_by,
            );
        }
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */