<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->page_data['page']->title = 'Supllier Management';
        $this->page_data['page']->menu = 'supplier';
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        ifPermissions('supplier_list');
        $this->page_data['page']->submenu = 'list';
        $this->page_data['title'] = 'supplier_list';
        $this->page_data['modals'] = (object) array(
            'id' => 'modal-import',
            'title' => 'Modals upload supplier',
            'link' => 'master_information/supplier/upload',
            'content' => 'upload',
            'btn' => 'btn-primary',
            'submit' => 'Save changes',
        );
        $this->load->view('supplier/list', $this->page_data);
        $this->load->view('includes/modals', $this->page_data);
    }

    public function add()
    {
        ifPermissions('supplier_add');
        $this->page_data['page']->submenu = 'add';
        $this->page_data['title'] = 'supplier_add';

        $this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
        $this->form_validation->set_rules('owner_name', lang('supplier_owner'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['supplier_code'] = $this->supplier_model->get_code_supplier();
            $this->load->view('supplier/add', $this->page_data);
        } else {
            $data = [
                'customer_code' => post('customer_code'),
                'store_name' => strtoupper(post('store_name')),
                'owner_name' => strtoupper(post('owner_name')),
                'supplier_type' => strtoupper(post('supplier_type')),
                'is_active' => 1,
                'created_by' => logged('id'),
                'note' => post('note'),
            ];
            $supplier = $this->supplier_model->create($data);
            if ($supplier) {
                $this->activity_model->add("New Supplier #$supplier, Created by User: #" . logged('id'), (array)$data);
                $this->session->set_flashdata('alert-type', 'success');
                $this->session->set_flashdata('alert', 'New Supplier Created Successfully');
                redirect("master_information/address/add?id=" . $data['customer_code']);
                return false;
            } else {
                $this->session->set_flashdata('alert-type', 'danger');
                $this->session->set_flashdata('alert', 'New Supplier Created Failed');
                redirect("master_information/address/add?id=" . $data['customer_code']);
                return false;
            }
        }
    }

    public function edit()
    {
        ifPermissions('supplier_edit');
        $this->form_validation->set_rules('customer_code', lang('customer_code'), 'required|trim');
        $this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['supplier'] = $this->supplier_model->getByWhere(['customer_code' => GET('id')])[0];
            $this->page_data['address'] = $this->address_model->getByWhere(['customer_code' => GET('id')]);
            $this->page_data['title'] = 'supplier_update';
            $this->load->view('supplier/edit', $this->page_data);
        } else {
            $data = [
                'customer_code' => post('customer_code'),
                'store_name' => strtoupper(post('store_name')),
                'owner_name' => strtoupper(post('owner_name')),
                'supplier_type' => strtoupper(post('supplier_type')),
                'is_active' => post('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s', time()),
                'updated_by' => logged('id'),
                'note' => post('note'),
            ];
            $supplier = $this->supplier_model->update(POST('id'), $data);

            $this->activity_model->add("Update supplier #$supplier, Update by User: #" . logged('id'), (array)$this->page_data['supplier']);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'Update supplier Successfully');

            redirect('master_information/supplier/list');
        }
    }

    public function upload()
    {
        ifPermissions('upload_file');
        if (($_FILES['file']['name'] == '')) {
            $this->session->set_flashdata('alert-type', 'danger');
            $this->session->set_flashdata('alert', 'New Supplier Fail to Upload');
            redirect('master_information/supplier/list');
            die();
        }
        $data = $this->uploadlib->uploadFile();
        $request['supplier'] = [];
        $request['address'] = [];
        $i = 0;
        foreach ($data as $key => $value) {
            if ($key == 1) {
                continue;
            } else {
                array_push($request['supplier'], [
                    'id' => $value['A'],
                    'supplier_code' => $value['B'],
                    'store_name' => $value['C'],
                    'owner_name' => $value['D'],
                    'supplier_type' => $value['E'],
                    'note' => $value['F'],
                    'created_by' => logged('id'),
                    'is_active' => 1,
                ]);
                array_push($request['address'], [
                    'supplier_id' => $value['A'],
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
                $this->activity_model->add("New supplier Upload, #" . $value['A'] . ", Created by User: #" . logged('id'), $request[$i]);
                $this->activity_model->add("New Address Upload, #" . $value['A'] . ", Created by User: #" . logged('id'), $request[$i]);
                $i++;
            }
        }
        $this->supplier_model->create_batch($request['supplier']);
        $this->address_model->create_batch($request['address']);
        $this->session->set_flashdata('alert-type', 'success');
        $this->session->set_flashdata('alert', 'New supplier Upload Successfully');

        redirect('master_information/supplier/list');
    }

    // SERVER SIDE
    public function serverside_datatables_data_supplier()
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
        $records = $this->db->get('supplier_information')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '') {
            $this->db->like('store_name', $searchValue, 'both');
            $this->db->or_like('owner_name', $searchValue, 'both');
            $this->db->or_like('customer_code', $searchValue, 'both');
        }
        $records = $this->db->get('supplier_information')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*, supplier_information.id as customer_id');
        if ($searchQuery != '') {
            $this->db->like('store_name', $searchValue, 'both');
            $this->db->or_like('owner_name', $searchValue, 'both');
            $this->db->or_like('customer_code', $searchValue, 'both');
        }
        $this->db->join('address_information', 'address_information.customer_code=supplier_information.customer_code', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->where('address_information.is_active', 1);
        $this->db->group_by('supplier_information.customer_code');
        $this->db->order_by('supplier_information.customer_code', 'ASC');
        $records = $this->db->get('supplier_information')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "customer_id" => $record->customer_id,
                "customer_code" => $record->customer_code,
                "store_name" => $record->store_name,
                "owner_name" => $record->owner_name,
                "supplier_type" => $record->supplier_type,

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

    public function data_supplier()
    {
        if (ifPermissions('purchase_create') or ifPermissions('selling_create')) {
            $search = (object) post('search');
            $this->db->limit(3);
            if ($search->value) {
                $this->db->like('supplier_information.customer_code', $search->value, 'both');
                $this->db->or_like('supplier_information.store_name', $search->value, 'both');
                $this->db->or_like('supplier_information.owner_name', $search->value, 'both');
                $this->db->or_like('address_information.address', $search->value, 'both');
                $this->db->or_like('address_information.village', $search->value, 'both');
                $this->db->or_like('address_information.sub_district', $search->value, 'both');
                $this->db->or_like('address_information.city', $search->value, 'both');
                $this->db->or_like('address_information.province', $search->value, 'both');
            }
            $this->db->join('address_information', 'address_information.customer_code=supplier_information.customer_code', 'left');
            $response = $this->db->get('supplier_information')->result();
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        };
    }
}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */