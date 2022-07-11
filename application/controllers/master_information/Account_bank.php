<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account_bank extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->page_data['page']->title = 'Account Bank Management';
        $this->page_data['page']->menu = 'account_bank';
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        ifPermissions('account_bank_list');
        $this->page_data['page']->submenu = 'account_bank_list';
        $this->page_data['title'] = 'account_bank_list';
        $this->load->view('account_bank/list', $this->page_data);
    }

    public function create()
    {
        ifPermissions('account_bank_add');
        $this->page_data['page']->submenu = 'account_bank_add';
        $this->page_data['title'] = 'account_bank_add';

        $this->form_validation->set_rules('name', lang('bank_name'), 'required|trim');
        $this->form_validation->set_rules('no_account', lang('no_account'), 'required|trim|is_natural');
        $this->form_validation->set_rules('own_by', lang('own_by'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['customers'] = $this->customer_model->get();
            $this->load->view('account_bank/create', $this->page_data);
        } else {
            $data = [
                'name' => strtoupper(post('name')),
                'no_account' => post('no_account'),
                'own_by' => strtoupper(post('own_by')),
                'description' => strtoupper(post('note')),
                'created_by' => logged('id'),
            ];
            $account = $this->account_bank_model->create($data);

            $this->activity_model->add("New Account Bank #$account, Created by User: #" . logged('id'), (array)$data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'New Account Bank Created Successfully');

            redirect('master_information/account_bank/list');
        }
    }

    public function update()
    {
        ifPermissions('account_bank_edit');
        $this->page_data['page']->submenu = 'account_bank_edit';
        $this->page_data['title'] = 'account_bank_edit';

        $this->form_validation->set_rules('name', lang('bank_name'), 'required|trim');
        $this->form_validation->set_rules('no_account', lang('no_account'), 'required|trim|is_natural');
        $this->form_validation->set_rules('own_by', lang('own_by'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['bank'] = $this->account_bank_model->getById(get('id'));
            $this->load->view('account_bank/update', $this->page_data);
        } else {
            $data = [
                'name' => strtoupper(post('name')),
                'no_account' => post('no_account'),
                'own_by' => strtoupper(post('own_by')),
                'description' => strtoupper(post('note')),
                'updated_by' => logged('id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $account = $this->account_bank_model->update(get('id'),$data);

            $this->activity_model->add("Update Account Bank #$account, Created by User: #" . logged('id'), (array)$data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'Update Account Bank Created Successfully');

            redirect('master_information/account_bank/list');
        }
    }

    public function balance()
    {
        ifPermissions('update_balance');
        $this->page_data['page']->submenu = 'account_bank_edit';
        $this->page_data['title'] = 'transaction';

        $this->form_validation->set_rules('name', lang('bank_name'), 'required|trim');
        $this->form_validation->set_rules('no_account', lang('no_account'), 'required|trim|is_natural');
        $this->form_validation->set_rules('own_by', lang('own_by'), 'required|trim');

        $status = $this->input->get('status');
        if(!$status){
            $this->session->set_flashdata('alert-type', 'danger');
            $this->session->set_flashdata('alert', 'Update Failed, worng information');
            redirect('master_information/account_bank/list');
        }
        if ($this->form_validation->run() == false) {
            $this->page_data['bank'] = $this->account_bank_model->getById(get('id'));
            $this->load->view('account_bank/balance', $this->page_data);
        } else {
            $data = [
                'updated_by' => logged('id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $invoice_payment = [
                'created_by' => logged('id'),
                'payup' => setCurrency(post('nominal')),
                'status_payment' => get('status') == 'increse'?0:1, //
                'bank_id' => get('id'),
                'description'=> strtoupper(post('note')),
            ];
            $nominal = $status == "increse"? 'balance+' : 'balance-';
            $this->db->trans_start(); // UPDATE BALANCE
            $this->db->set('balance', $nominal.setCurrency(post('nominal')), FALSE);
            $this->db->where('id', get('id'));
            $account = $this->db->update('bank_information');
            $this->payment_model->create($invoice_payment);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE){
                $this->session->set_flashdata('alert-type', 'danger');
                $this->session->set_flashdata('alert', 'Update Balance');
                redirect('master_information/account_bank/list');
                return false;   
            }

            $this->activity_model->add(" Account Bank #$account, Created by User: #" . logged('id'), (array)$data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'Update Balance');

            redirect('master_information/account_bank/update?id='.get('id'));
        }
    }

    public function cashflow()
    {
        ifPermissions('cashflow');
        $this->page_data['page']->submenu = 'account_bank_edit';
        $this->page_data['title'] = 'transaction';
        $this->load->view('account_bank/cashflow', $this->page_data);
    }

    public function serverside_datatables_data()
    {
        ifPermissions('account_bank_list');
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

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('bank_information')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchValue != '') {
            $this->db->like('name', $searchValue, 'both');
            $this->db->or_like('no_account', $searchValue, 'both');
            $this->db->or_like('own_by', $searchValue, 'both');

        }
        $records = $this->db->get('bank_information')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('
        user.name as user_name
        ,bank.id
        ,bank.name
        ,bank.no_account
        ,bank.own_by
        ,bank.description
        ,bank.created_at
        ,bank.created_by
        ,bank.updated_at
        ,bank.updated_by
        ');
        if ($searchValue != '') {
            $this->db->like('name', $searchValue, 'both');
            $this->db->or_like('no_account', $searchValue, 'both');
            $this->db->or_like('own_by', $searchValue, 'both');
        }
        $this->db->join('users user', 'user.id = bank.created_by', 'left');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('bank_information bank')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "user_name" => $record->user_name,
                "id" => $record->id,
                "name" => $record->name,
                "no_account" => $record->no_account,
                "own_by" => $record->own_by,
                "description" => $record->description,
                "created_at" => $record->created_at,
                "created_by" => $record->created_by,
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
    

    public function data_bank()
    {
        if (hasPermissions('requst_data_bank')) {
            $search = (object) post('search');
            $this->db->limit(5);
            if (isset($search->value)) {
                $this->db->like('bank_information.name', $search->value, 'after');
                $this->db->or_like('bank_information.no_account', $search->value, 'after');
                $this->db->or_like('bank_information.own_by', $search->value, 'after');
            }
            $response = $this->db->get('bank_information')->result();
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }else{
            ifPermissions('order_create');
        }
    }
}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */