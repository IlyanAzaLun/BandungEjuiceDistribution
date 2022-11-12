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
        ifPermissions('account_bank_list');
        $this->page_data['page']->submenu = 'account';
        $this->page_data['title'] = 'chart_of_account';
        $this->page_data['get_account'] = $this->db->select('*')->from('acc_coa')->where('IsActive', 1)->order_by('HeadName')->get()->result();
        $this->page_data['visit'] = array();
        for ($i=0; $i < count($this->page_data['get_account']); $i++) { 
            $this->page_data['visit'][$i] = false;
        }
        $this->load->view('account_bank/index', $this->page_data);
    }
    public function form()
    {
        # code...
        $request = post('search');
        $this->page_data['role'] = $this->db->select('*')->from('acc_coa')->where('HeadCode', $request['value'])->get()->row();
        if($this->page_data['role']){
            $this->load->view('account_bank/index_form', $this->page_data);
        }
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
            $this->page_data['parent_account'] = $this->db->select('*')->from('acc_coa')->where(
                array(
                    'PHeadCode' => 111, 
                    'IsActive' => 1, 
                    'HeadLevel >' => 3
                ))->order_by('HeadName')->get()->result();

            $this->load->view('account_bank/create', $this->page_data);
        } else {
            $data = [
                'name' => strtoupper(post('name')),
                'no_account' => post('no_account'),
                'own_by' => strtoupper(post('own_by')),
                'description' => strtoupper(post('note')),
                'coa_parent' => post('parent_account'),
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
        $this->page_data['page']->submenu = 'account_bank_list';
        $this->page_data['title'] = 'account_bank_edit';

        $this->form_validation->set_rules('name', lang('bank_name'), 'required|trim');
        $this->form_validation->set_rules('no_account', lang('no_account'), 'required|trim|is_natural');
        $this->form_validation->set_rules('own_by', lang('own_by'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['modals'] = (object) array(
                'id' => 'modal-delete',
                'title' => 'Modals delete account',
                'link' => 'master_information/account_bank/delete',
                'content' => 'delete',
                'btn' => 'btn-danger',
                'submit' => 'Delete it',
            );
            $this->page_data['bank'] = $this->account_bank_model->getById(get('id'));
            $this->page_data['parent_account'] = $this->db->select('*')->from('acc_coa')->where(
                array(
                    'PHeadCode' => 111, 
                    'IsActive' => 1, 
                    'HeadLevel >' => 3
                ))->order_by('HeadName')->get()->result();
            $this->load->view('account_bank/update', $this->page_data);
            $this->load->view('includes/modals', $this->page_data);
        } else {
            $data = [
                'name' => strtoupper(post('name')),
                'no_account' => post('no_account'),
                'own_by' => strtoupper(post('own_by')),
                'description' => strtoupper(post('note')),
                'updated_by' => logged('id'),
                'coa_parent' => post('parent_account'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $account = $this->account_bank_model->update(get('id'),$data);

            $this->activity_model->add("Update Account Bank #$account, Created by User: #" . logged('id'), (array)$data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'Update Account Bank Created Successfully');

            redirect('master_information/account_bank/list');
        }
    }

    public function delete()
    {
        ifPermissions('account_bank_delete');
        if($this->account_bank_model->update(post('id'), array('is_canceled' => 1))){
            
            $this->activity_model->add("Delete Account Bank #$account, Delete by User: #" . logged('id'), (array)$data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'Delete Account Bank Successfully');
        }else{
            $this->session->set_flashdata('alert-type', 'error');
            $this->session->set_flashdata('alert', 'Delete Account Bank Failed');

        }
        
        redirect('master_information/account_bank/list');
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
        $this->page_data['page']->submenu = 'account';
        $this->page_data['title'] = 'transaction';
        $this->load->view('account_bank/cashflow', $this->page_data);
    }
    public function serverside_datatables_data_payment()
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
		$dateStart = $postData['startDate'];
		$dateFinal = $postData['finalDate'];
		$bank_id = $postData['id_bank'];

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('invoice_payment payment')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(payment.id) as allcount');
        if ($searchValue != '') {
            $this->db->group_start();
            $this->db->like('payment.invoice_code', $searchValue, 'both');
            $this->db->or_like('payment.customer_code', $searchValue, 'both');
            $this->db->or_like('payment.description', $searchValue, 'both');
            $this->db->or_like('supplier.store_name', $searchValue, 'both');
            $this->db->or_like('supplier.store_name', $searchValue, 'both');
            
            $this->db->or_like('customer.owner_name', $searchValue, 'both');
            $this->db->or_like('customer.owner_name', $searchValue, 'both');
            $this->db->group_end();
        }
        $this->db->where('payment.bank_id', $bank_id);
        $this->db->join('bank_information bank', 'bank.id = payment.bank_id', 'left');
        $this->db->join('supplier_information supplier', 'supplier.customer_code = payment.customer_code', 'left');
        $this->db->join('customer_information customer', 'customer.customer_code = payment.customer_code', 'left');
        $records = $this->db->get('invoice_payment payment')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('
              `payment`.`id`
            , `payment`.`invoice_code`
            , `payment`.`date_start`
            , `payment`.`date_due`
            , `payment`.`customer_code`
            , `payment`.`grand_total`
            ,  SUM(`payment`.`payup`) AS payup
            ,  `payment`.`leftovers` 
            , `payment`.`status_payment`
            , `payment`.`payment_type`
            , `payment`.`bank_id`
            , `payment`.`is_cancelled`
            , `payment`.`cancel_note`
            , `payment`.`created_at`
            , `payment`.`updated_at`
            , `payment`.`description`
            ,  IFNULL(`supplier`.`store_name`, `customer`.`store_name`) AS store_name
            ,  NVL2(`supplier`.`store_name`, `supplier`.`owner_name`, `customer`.`owner_name`) AS owner_name
            , `user_create`.`name` AS `user_create_name`
            , `user_update`.`name` AS `user_update_name`
        ');
        $this->db->join('bank_information bank', 'bank.id = payment.bank_id', 'left');
        $this->db->join('supplier_information supplier', 'supplier.customer_code = payment.customer_code', 'left');
        $this->db->join('customer_information customer', 'customer.customer_code = payment.customer_code', 'left');
        $this->db->join('users user_create', 'user_create.id = payment.created_by', 'left');
        $this->db->join('users user_update', 'user_update.id = payment.updated_by', 'left');
        if ($searchValue != '') {
            $this->db->group_start();
            $this->db->like('payment.invoice_code', $searchValue, 'both');
            $this->db->or_like('payment.customer_code', $searchValue, 'both');
            $this->db->or_like('payment.description', $searchValue, 'both');
            $this->db->or_like('supplier.store_name', $searchValue, 'both');
            $this->db->or_like('supplier.owner_name', $searchValue, 'both');            
            $this->db->or_like('customer.store_name', $searchValue, 'both');
            $this->db->or_like('customer.owner_name', $searchValue, 'both');
            $this->db->group_end();
        }
        $this->db->group_start();
        $this->db->where('payment.bank_id', $bank_id);
        $this->db->where('payment.is_cancelled', null);
        $this->db->where("payment.payup !=", 0);
        $this->db->group_end();
        $this->db->group_by('payment.invoice_code');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('invoice_payment payment')->result();

        // GET TOTAL CASH IN to this BANK ID
        $this->db->select('SUM(IF(payment.payup > payment.grand_total, payment.grand_total, payment.payup)) AS total_payup');
        $this->db->where('payment.bank_id', $bank_id);
        $this->db->where('payment.is_cancelled', null);
        $this->db->where('payment.payup !=', 0);
        $get_total = $this->db->get('invoice_payment payment')->row();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "id" => $record->id,
                "invoice_code" => $record->invoice_code,
                "date_start" => $record->date_start,
                "date_due" => $record->date_due,
                "customer_code" => $record->customer_code,
                "grand_total" => $record->grand_total,
                "payup" => $record->payup,
                "leftovers" => $record->leftovers,
                "status_payment" => $record->status_payment,
                "payment_type" => $record->payment_type,
                "bank_id" => $record->bank_id,
                "is_cancelled" => $record->is_cancelled,
                "cancel_note" => $record->cancel_note,
                "created_at" => $record->created_at,
                "updated_at" => $record->updated_at,
                "description" => $record->description,
                "store_name" => $record->store_name,
                "owner_name" => $record->owner_name,
                "user_create_name" => $record->user_create_name,
                "user_update_name" => $record->user_update_name,
            );
        }
        // var_dump($this->db->last_query());
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
            "ffooterData" => $get_total
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
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
        ,bank.updated_by');
        $this->db->join('users user', 'user.id = bank.created_by', 'left');
        if ($searchValue != '') {
            $this->db->group_start();
            $this->db->like('bank.name', $searchValue, 'both');
            $this->db->or_like('bank.no_account', $searchValue, 'both');
            $this->db->or_like('bank.own_by', $searchValue, 'both');
            $this->db->group_end();
        }
        $this->db->group_start();
        $this->db->where('bank.is_canceled', 0);
        $this->db->group_end();
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
            $this->db->where('bank_information.is_canceled', 0);
            $response = $this->db->get('bank_information')->result();
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }else{
            ifPermissions('order_create');
        }
    }
}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */