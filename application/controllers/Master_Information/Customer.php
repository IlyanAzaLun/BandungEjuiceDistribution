<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends MY_Controller
{

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
        $this->page_data['modals'] = (object) array(
            'id' => 'modal-import',
            'title' => 'Modals upload customer',
            'link' => 'master_information/customer/upload',
            'content' => 'upload',
            'btn' => 'btn-primary',
            'submit' => 'Save changes',
        );


        $this->load->view('customer/list', $this->page_data);
        $this->load->view('includes/modals', $this->page_data);
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
        } else {
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
            if ($customer) {
                $this->activity_model->add("New Customer #$customer, Created by User: #" . logged('id'), (array)$data);
                $this->session->set_flashdata('alert-type', 'success');
                $this->session->set_flashdata('alert', 'New Customer Created Successfully');
                redirect("master_information/address/add?id=" . $data['customer_code']);
            } else {
                $this->session->set_flashdata('alert-type', 'error');
                $this->session->set_flashdata('alert', 'New Customer Created Failed');
                redirect("master_information/address/add?id=" . $data['customer_code']);
            }
        }
    }

    public function edit()
    {
        ifPermissions('customer_edit');
        $this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
        $this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['customer'] = $this->customer_model->getByWhere(array('customer_code' => GET('id')))[0];
            $this->page_data['address'] = $this->address_model->getByWhere(['customer_code' => GET('id')]);

            $this->page_data['title'] = 'customer_update';
            $this->load->view('customer/edit', $this->page_data);
        } else {
            $data = [
                'customer_code' => post('customer_code'),
                'store_name' => strtoupper(post('store_name')),
                'owner_name' => strtoupper(post('owner_name')),
                'customer_type' => strtoupper(post('customer_type')),
                'is_active' => post('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s', time()),
                'updated_by' => logged('id'),
                'note' => post('note'),
            ];
            $customer = $this->customer_model->update(post('id'), $data);

            $this->activity_model->add("Update Customer #$customer, Update by User: #" . logged('id'), (array)$this->page_data['customer']);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'Update Customer Successfully');

            redirect('master_information/customer/list');
        }
    }

    public function upload()
    {
        ifPermissions('upload_file');

        $data = $this->uploadlib->uploadFile();
        $request['customer'] = [];
        $request['address'] = [];
        $i = 0;
        foreach ($data as $key => $value) {
            if ($key == 1) {
                continue;
            } else {
                array_push($request['customer'], [
                    'id' => $value['A'],
                    'customer_code' => $value['B'],
                    'store_name' => $value['C'],
                    'owner_name' => $value['D'],
                    'customer_type' => $value['E'],
                    'note' => $value['F'],
                    'created_by' => logged('id'),
                    'is_active' => 1,
                ]);
                array_push($request['address'], [
                    'customer_code' => $value['A'],
                    'address' => $value['G'],
                    'village' => $value['H'],
                    'sub_district' => $value['I'],
                    'city' => $value['J'],
                    'province' => $value['K'],
                    'zip' => $value['L'],
                    'contact_phone' => $value['M'],
                    'contact_mail' => $value['N'],
                    'note' => $value['O'],
                    'created_by' => logged('id'),
                    'is_active' => 1,
                ]);
                $this->activity_model->add("New Customer Upload, #" . $value['A'] . ", Created by User: #" . logged('id'), $request[$i]);
                $this->activity_model->add("New Address Upload, #" . $value['A'] . ", Created by User: #" . logged('id'), $request[$i]);
                $i++;
            }
        }
        $item = $this->customer_model->create_batch($request['customer']);
        $item = $this->address_model->create_batch($request['address']);
        $this->session->set_flashdata('alert-type', 'success');
        $this->session->set_flashdata('alert', 'New Customer Upload Successfully');

        redirect('master_information/customer/list');
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
        if ($searchQuery != '') {
            $this->db->like('store_name', $searchValue, 'both');
            $this->db->or_like('owner_name', $searchValue, 'both');
            $this->db->or_like('customer_code', $searchValue, 'both');
        }
        $records = $this->db->get('customer_information')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*, customer_information.id as customer_id');
        if ($searchQuery != '') {
            $this->db->like('store_name', $searchValue, 'both');
            $this->db->or_like('owner_name', $searchValue, 'both');
            $this->db->or_like('customer_code', $searchValue, 'both');
        }
        $this->db->join('address_information', 'address_information.customer_code=customer_information.customer_code', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->where('address_information.is_active', 1);
        $this->db->group_by('customer_information.customer_code');
        $this->db->order_by('customer_information.customer_code', 'ASC');
        $records = $this->db->get('customer_information')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "customer_id" => $record->customer_id,
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

    public function data_customer()
    {
        if (ifPermissions('order_create')) {
            $search = (object) post('search');
            $this->db->limit(5);
            if ($search->value) {
                $this->db->like('customer_information.customer_code', $search->value, 'both');
                $this->db->or_like('customer_information.store_name', $search->value, 'both');
                $this->db->or_like('customer_information.owner_name', $search->value, 'both');
                $this->db->or_like('address_information.address', $search->value, 'both');
                $this->db->or_like('address_information.village', $search->value, 'both');
                $this->db->or_like('address_information.sub_district', $search->value, 'both');
                $this->db->or_like('address_information.city', $search->value, 'both');
                $this->db->or_like('address_information.province', $search->value, 'both');
            }
            $this->db->join('address_information', 'address_information.customer_code=customer_information.customer_code', 'left');
            $response = $this->db->get('customer_information')->result();
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        };
    }
}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */