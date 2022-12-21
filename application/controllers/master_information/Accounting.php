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
                    $this->db->select('
                      journal.id_account 
                    , journal.HeadCode 
                    , journal.HeadName
                    , journal.PHeadCode
                    , journal.debit 
                    , SUM(journal.debit) as total_debit 
                    , journal.credit 
                    , SUM(journal.credit) as total_credit 
                    , SUM(IFNULL(journal.debit,0)) - SUM(IFNULL(journal.credit,0)) as total
                    , journal.created_at
                    , acc_coa.HeadType
                    , acc_coa.IsGL
                    , acc_coa.IsTransaction
                    , acc_coa.isCashNature
                    , acc_coa.isBankNature
                    , acc_coa.IsBudget
                    , acc_coa.IsDepreciation
                    , acc_coa.depCode
                    , acc_coa.DepreciationRate
                    , acc_coa.isStock
                    , acc_coa.assetCode
                    , acc_coa.isFixedAssetSch');
                    $this->db->join('acc_coa', 'journal.id_account = acc_coa.id', 'right');
                    $this->db->group_start();
                    switch (post('group')) {
                        case 'year':
                            $this->db->where('DATE_FORMAT(journal.created_at, "%Y") =', 'DATE_FORMAT("'.DateFomatDb($data).'", "%Y")', false);
                            break;

                        case 'day':
                            $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m-%d") =', 'DATE_FORMAT("'.DateFomatDb($data).'", "%Y-%m-%d")', false);
                            break;
                        
                        default:
                            $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m") =', 'DATE_FORMAT("'.DateFomatDb($data).'", "%Y-%m")', false);
                            break;
                    }
                    $this->db->group_end();
                    switch (post('type')) {
                        case 'balance_sheet':
                            $this->db->where('acc_coa.HeadType', 'A');
                            break;
                        
                        default:
                            $this->db->like('acc_coa.HeadType','B','after');
                            break;
                    }
                    $this->db->group_by('journal.id_account');
                    $this->db->order_by('journal.id_account', 'ASC');
                    $response = $this->db->get('journal')->result();
                    break;
                case 'data':
                    $this->db->select('
                      journal.id_account
                    , journal.HeadCode
                    , journal.debit
                    , journal.credit
                    , journal.created_at
                    , journal.description');
                    $this->db->join('acc_coa', 'journal.id_account = acc_coa.id', 'right');
                    $this->db->group_start();
                    $this->db->where('journal.HeadCode', post('id'));
                    switch (post('group')) {
                        case 'year':
                            $this->db->where('DATE_FORMAT(journal.created_at, "%Y") =', 'DATE_FORMAT("'.DateFomatDb($data).'", "%Y")', false);
                            break;

                        case 'day':
                            $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m-%d") =', 'DATE_FORMAT("'.DateFomatDb($data).'", "%Y-%m-%d")', false);
                            break;
                        
                        default:
                            $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m") =', 'DATE_FORMAT("'.DateFomatDb($data).'", "%Y-%m")', false);
                            break;
                    }
                    if (post('type') == 'balance_sheet') {
                        $this->db->where('acc_coa.HeadType', 'A');
                    }
                    if (post('type') == 'profit_n_loss') {
                        $this->db->like('acc_coa.HeadType','B','after');
                    }
                    $this->db->group_end();
                    $this->db->order_by('journal.created_at', 'ASC');
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
            $juournal_entry = json_decode($this->input->post('journal_entry'));
            
            $this->db->where('created_at', DateFomatDb(implode('/',array_reverse(explode('/', post('journal_date'))))));
            $this->db->delete('journal');
            $this->db->reset_query();

            foreach ($juournal_entry as $key => $value) {
                if ($value[0] != '') {
                    $request[$key]['id_account'] = explode('/', $value[0])[0];
                    $data = $this->db->get_where('acc_coa', array('id'=>explode('/', $value[0])[0]))->row();
                    $request[$key]['HeadCode'] = $data->HeadCode;
                    $request[$key]['HeadName'] = $data->HeadName;
                    $request[$key]['PHeadCode'] = $data->PHeadCode;
                    $request[$key]['debit'] = $value[2];
                    $request[$key]['credit'] = $value[3];
                    $request[$key]['description'] = strtoupper($value[4]);
                    $request[$key]['created_at'] = DateFomatDb(implode('/',array_reverse(explode('/', post('journal_date')))));
                    $request[$key]['created_by'] = logged('id');
                }
            }
            $result = $this->db->insert_batch('journal', $request);
            if($result > 0){
                $this->activity_model->add("New Journal #".DateFomatDb(implode('/',array_reverse(explode('/', post('journal_date'))))).", Created by User: #" . logged('id'), (array)$request);
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

    public function report_journal()
    {
        
        $this->page_data['page']->submenu_child = 'journal';
        $this->page_data['date'] = DateFomatDb(implode('/',array_reverse(explode('/', post('journal_date')))));
        $this->db->select('
                CONCAT(journal.id_account,"-",journal.HeadCode,"/",journal.HeadName)  AS account
                ,journal.debit 
                ,journal.credit 
                ,journal.description 
                ,DATE_FORMAT(journal.created_at, "%d/%m/%Y") AS date');
        $this->db->join('acc_coa', 'journal.id_account = acc_coa.id', 'right');
        $this->db->group_start();
        if (post('group') == 'year') {
            $this->db->where('DATE_FORMAT(journal.created_at, "%Y") =', 'DATE_FORMAT("'.$this->page_data['date'].'", "%Y")', false);
        }
        if (post('group') == 'month') {
            $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m") =', 'DATE_FORMAT("'.$this->page_data['date'].'", "%Y-%m")', false);
        }
        if (post('group') == 'day') {
            $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m-%d") =', 'DATE_FORMAT("'.$this->page_data['date'].'", "%Y-%m-%d")', false);
        }
        $this->db->group_end();
        $this->db->order_by('journal.created_at, journal.id', 'ASC');
        $this->page_data['journal_report'] = $this->db->get('journal')->result();
        
        $this->load->view('accounting/journal_report', $this->page_data);
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
        
        $this->form_validation->set_rules('balance_sheet', lang('balance_sheet'), 'required|trim');
		if ($this->form_validation->run() == false) {
            $this->load->view('accounting/balance_sheet', $this->page_data);
        }
        else{

            $this->db->select('
                    CONCAT(journal.id_account,"-",journal.HeadCode,"/",journal.HeadName)  AS account
                   ,journal.debit 
                   ,journal.credit 
                   ,journal.description 
                   ,DATE_FORMAT(journal.created_at, "%d/%m/%Y") AS date');
            $this->db->join('acc_coa', 'journal.id_account = acc_coa.id', 'right');
            $this->db->group_start();
            if (post('group') == 'year') {
                $this->db->where('DATE_FORMAT(journal.created_at, "%Y") =', 'DATE_FORMAT("'.DateFomatDb($this->input->post('balance_sheet')).'", "%Y")', false);
            }
            if (post('group') == 'month'){
                $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m") =', 'DATE_FORMAT("'.DateFomatDb($this->input->post('balance_sheet')).'", "%Y-%m")', false);
            }
            if (post('group') == 'day'){
                $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m-%d") =', 'DATE_FORMAT("'.DateFomatDb($this->input->post('balance_sheet')).'", "%Y-%m-%d")', false);
            }
            $this->db->group_end();
            $this->db->where('acc_coa.HeadType', 'A');
            $this->db->order_by('journal.created_at, journal.id', 'ASC');
            $this->page_data['journal_report'] = $this->db->get('journal')->result();
            
            $this->load->view('accounting/journal_report', $this->page_data);
        }
    }
    
    public function profit_n_loss()
    {
        // ifPermissions('journal_list');
        $this->page_data['page']->submenu_child = 'profit_n_loss';
        $this->page_data['title'] = 'profit_n_loss';
        $this->page_data['accounts'] = $this->db->where(array(
            'IsActive' => 1,
            'HeadLevel >' => 1,
        ))->like('HeadType','B','after')->get('acc_coa')->result();
        // ORDER ACCOUNTS WITH HEAD TYPE THEN ORDER BY ID
        usort($this->page_data['accounts'], function($a, $b) {
            // ORDER HEADTYPE
            $retval = $b->HeadType <=> $a->HeadType;
            // ORDER ID
            if ($retval == 0) {
                $retval = $a->id <=> $b->id;
            }
            return $retval;
        });
        $this->form_validation->set_rules('profit_n_loss', lang('profit_n_loss'), 'required|trim');
		if ($this->form_validation->run() == false) {
            $this->load->view('accounting/profit_n_loss', $this->page_data);
        }
        else{

            $this->db->select('
                    CONCAT(journal.id_account,"-",journal.HeadCode,"/",journal.HeadName)  AS account
                   ,journal.debit 
                   ,journal.credit 
                   ,journal.description 
                   ,DATE_FORMAT(journal.created_at, "%d/%m/%Y") AS date');
            $this->db->join('acc_coa', 'journal.id_account = acc_coa.id', 'right');
            $this->db->group_start();
            
            if (post('group') == 'year') {
                $this->db->where('DATE_FORMAT(journal.created_at, "%Y") =', 'DATE_FORMAT("'.DateFomatDb($this->input->post('profit_n_loss')).'", "%Y")', false);
            }
            if (post('group') == 'month') {
                $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m") =', 'DATE_FORMAT("'.DateFomatDb($this->input->post('profit_n_loss')).'", "%Y-%m")', false);
            }
            if (post('group') == 'day') {
                $this->db->where('DATE_FORMAT(journal.created_at, "%Y-%m-%d") =', 'DATE_FORMAT("'.DateFomatDb($this->input->post('profit_n_loss')).'", "%Y-%m-%d")', false);
            }
            $this->db->group_end();
            $this->db->group_start();
            $this->db->like('acc_coa.HeadType','B','after');
            $this->db->group_end();
            $this->db->order_by('journal.created_at, journal.id', 'ASC');
            $this->page_data['journal_report'] = $this->db->get('journal')->result();

            $this->load->view('accounting/journal_report', $this->page_data);
        }
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