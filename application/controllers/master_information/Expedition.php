<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Expedition extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->page_data['page']->title = 'Expedition Management';
        $this->page_data['page']->menu = 'expedition';
	}
    
    public function index()
    {
        $this->list();
    }

    public function list()
	{
		ifPermissions('expedition_list');
		$this->page_data['title'] = 'expedition_list';
		$this->page_data['page']->submenu = 'expedition_list';
        $this->page_data['modals'] = (object) array(
            'id' => 'expeditionModelDelete',
            'title' => 'Modals Confirmation',
            'link' => 'master_information/expedition/delete',
            'content' => 'delete',
            'btn' => 'btn-danger',
            'submit' => 'Yes Do it...',
        );
        $this->load->view('expedition/list', $this->page_data);
        $this->load->view('includes/modals', $this->page_data);
    }

    public function add()
    {
		ifPermissions('expedition_create');
		$this->page_data['title'] = 'expedition_add';
		$this->page_data['page']->submenu = 'expedition_add';
		$this->form_validation->set_rules('expedition_name', lang('expedition_name'), 'required|trim|min_length[3]');
		$this->form_validation->set_rules('service_expedition[]', lang('service_expedition'), 'required|trim');
		if ($this->form_validation->run() == false) {
            $this->load->view('expedition/add', $this->page_data);
        }else{
            $expedition = array(
                'expedition_name' => post('expedition_name'), 
                'services_expedition' => join(',',post('service_expedition')), 
                'note' => post('note'),
                'created_by' => logged('id'),
            );
            $result = $this->expedition_model->create($expedition);
            if ($result) {
                $this->activity_model->add("New Expedition #$result, Created by User: #" . logged('id'), (array)$data);
                $this->session->set_flashdata('alert-type', 'success');
                $this->session->set_flashdata('alert', 'New Expedition Created Successfully');
                redirect("master_information/expedition/list");
            } else {
                $this->session->set_flashdata('alert-type', 'error');
                $this->session->set_flashdata('alert', 'New Expedition Created Failed');
                redirect("master_information/expedition/list");
            }
        }
    }

    public function edit()
    {
        ifPermissions('expedition_create');
		$this->page_data['title'] = 'expedition_edit';
		$this->page_data['page']->submenu = 'expedition_edit';
		$this->form_validation->set_rules('expedition_name', lang('expedition_name'), 'required|trim|min_length[3]');
		$this->form_validation->set_rules('service_expedition[]', lang('service_expedition'), 'required|trim');
		if ($this->form_validation->run() == false) {
            $this->page_data['expedition'] = $this->expedition_model->getById(get('id')); 
            $this->load->view('expedition/edit', $this->page_data);
        }else{
            $expedition = array(
                'expedition_name' => post('expedition_name'), 
                'services_expedition' => join(',',post('service_expedition')), 
                'note' => post('note'), 
                'updated_by' => logged('id'),
                'updated_at' => date('Y-m-d H:i:s', time()),
            );
            $result = $this->expedition_model->update(get('id'), $expedition);
            if ($result) {
                $this->activity_model->add("New Expedition #$result, Created by User: #" . logged('id'), (array)$data);
                $this->session->set_flashdata('alert-type', 'success');
                $this->session->set_flashdata('alert', 'New Expedition Created Successfully');
                redirect("master_information/expedition/list");
            } else {
                $this->session->set_flashdata('alert-type', 'error');
                $this->session->set_flashdata('alert', 'New Expedition Created Failed');
                redirect("master_information/expedition/list");
            }
        }
    }

    public function delete()
    {
        ifPermissions('expedition_delete');
        # code...
    }

    public function serverside_datatables_data_expedition()
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
        $records = $this->db->get('expedition')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '') {
            $this->db->like('expedition_name', $searchValue, 'both');
            $this->db->or_like('services_expedition', $searchValue, 'both');
        }
        $records = $this->db->get('expedition')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('
             expedition.id as expedition_id
            ,expedition.expedition_name
            ,expedition.services_expedition
            ,expedition.created_at
            ,expedition.created_by
            ,expedition.updated_at
            ,expedition.updated_by
            ,user.id as user_id
            ,user.name');
        if ($searchQuery != '') {
            $this->db->like('expedition_name', $searchValue, 'both');
            $this->db->or_like('services_expedition', $searchValue, 'both');
        }
		$this->db->join('users user', 'user.id = expedition.created_by', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('expedition')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "expedition_id" => $record->expedition_id,
                "expedition_name" => $record->expedition_name,
                "services_expedition" => $record->services_expedition,
                "created_at" => $record->created_at,
                "created_by" => $record->created_by,
                "user_id" => $record->user_id,
                "name" => $record->name,
                "updated_at" => $record->updated_at,
                "updated_by" => $record->updated_by,
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