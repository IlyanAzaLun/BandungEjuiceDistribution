<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounting extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->page_data['page']->title = 'Accounting';
        $this->page_data['page']->menu = 'report';
        $this->page_data['page']->submenu = 'accounting';
    }

    public function index()
    {
        $data = $this->input->post('date');
        if($data){
            switch (post('request')) {
                case 'journal':
                    // SELECT JOURNAL EACH DAY
                    $this->db->where('created_at', DateFomatDb($data));
                    $this->db->order_by('id', 'ASC');
                    $result = $this->db->get('journal')->result();
                    foreach ($result as $key => $value) {
                        $response[$key]['code'] = $value->id_account.'/'.$value->HeadCode;
                        $HeadName = $this->db->select('HeadName')->get_where('acc_coa', array('id'=>$value->id_account))->row()->HeadName;
                        $response[$key]['account'] = $value->id_account.'/'.$value->HeadCode.'-'.$HeadName;
                        $response[$key]['debit'] = $value->debit;
                        $response[$key]['credit'] = $value->credit;
                        $response[$key]['description'] = $value->description;
                    }
                    break;
                case 'report':
                    // SELECT JOURNAL GROUP MOUNTH WITH SUM AND EACH HEAD ACCOUNT CODE
                    $this->db->select('journal.id_account , journal.HeadCode , journal.HeadName, journal.PHeadCode, journal.debit , SUM(journal.debit) as total_debit , journal.credit , SUM(journal.credit) as total_credit , SUM(journal.debit)-SUM(journal.credit) as total, journal.created_at');
                    $this->db->join('acc_coa', 'journal.id_account = acc_coa.id', 'right');
                    $this->db->group_start();
                    $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m") =', 'DATE_FORMAT("'.DateFomatDb($data).'", "%Y-%m")', false);
                    $this->db->group_end();
                    switch (post('type')) {
                        case 'balance_sheet':
                            $this->db->where('acc_coa.HeadType', 'A');
                            break;
                        
                        default:
                            $this->db->where('acc_coa.HeadType', 'B');
                            break;
                    }
                    $this->db->group_by('journal.id_account');
                    $this->db->order_by('journal.id_account', 'ASC');
                    $response = $this->db->get('journal')->result();
                    break;
                default:
                    header("Location: ".url('master_information/accounting/journal'));
                    die();
                    break;
            }
            $callback['data'] = $response?$response:null;
            $this->output->set_content_type('application/json')->set_output(json_encode($callback));
        }else{
            header("Location: ".url('master_information/accounting/journal'));
            die();
        }
    }

    public function journal()
    {
        $this->form_validation->set_rules('journal_entry', lang('store_name'), 'required|trim');
        $this->form_validation->set_rules('amount', lang('store_name'), 'required|trim');
		if ($this->form_validation->run() == false) {
            $this->page_data['page']->submenu_child = 'journal';
            $this->page_data['title'] = 'journal';
            $this->load->view('accounting/journal', $this->page_data);
        }else{
            $juournal_enry = json_decode($this->input->post('journal_entry'));
            foreach ($juournal_enry as $key => $value) {
                if ($value[0] != '') {
                    $request[$key]['id_account'] = explode('/', $value[0])[0];
                    $data = $this->db->get_where('acc_coa', array('id'=>explode('/', $value[0])[0]))->row();
                    $request[$key]['HeadCode'] = $data->HeadCode;
                    $request[$key]['HeadName'] = $data->HeadName;
                    $request[$key]['PHeadCode'] = $data->PHeadCode;
                    $request[$key]['debit'] = $value[2];
                    $request[$key]['credit'] = $value[3];
                    $request[$key]['description'] = $value[4];
                    $request[$key]['created_at'] = DateFomatDb(post('journal_date'));
                    $request[$key]['created_by'] = logged('id');
                }
            }
            $this->db->where('created_at', DateFomatDb(post('journal_date')));
            $this->db->delete('journal');
            $this->db->reset_query();
            $result = $this->db->insert_batch('journal', $request);
            if($result > 0){
                $this->activity_model->add("New Journal #".DateFomatDb(post('journal_date')).", Created by User: #" . logged('id'), (array)$request);
                $this->session->set_flashdata('alert-type', 'success');
                $this->session->set_flashdata('alert', 'New Journal Created Successfully');
    
                redirect('master_information/accounting/journal');
            }else {
                echo '<pre>';
                var_dump($result);
                echo '</pre>';
            }
        }
    }
    
    public function balance_sheet()
    {
        // ifPermissions('journal_list');
        $this->page_data['page']->submenu_child = 'balance_sheet';
        $this->page_data['title'] = 'balance_sheet';
        $this->page_data['accounts'] = $this->db->where(array(
            'IsActive' => 1,
            'HeadLevel >' => 1,
            'HeadType' => 'A',
        ))->get('acc_coa')->result();
        $this->load->view('accounting/balance_sheet', $this->page_data);
    }
    
    public function profit_n_loss()
    {
        // ifPermissions('journal_list');
        $this->page_data['page']->submenu_child = 'profit_n_loss';
        $this->page_data['title'] = 'profit_n_loss';
        $this->page_data['accounts'] = $this->db->where(array(
            'IsActive' => 1,
            'HeadLevel >' => 1,
            'HeadType' => 'B',
        ))->get('acc_coa')->result();
        $this->load->view('accounting/profit_n_loss', $this->page_data);
    }

    public function chart_of_account_list()
    {
        $this->db->like('HeadName', post('query'), 'both');
        $this->db->where('HeadLevel >=', 3, true);
        $this->db->limit(10);
        $data = $this->db->get('acc_coa')->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}

/* End of file Journal.php */
/* Location: ./application/controllers/master_information/Journal.php */