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
        header("Location: ".url('master_information/accounting/journal'));
        die();
    }

    public function journal()
    {
        // ifPermissions('journal_list');
        $this->page_data['page']->submenu_child = 'journal';
        $this->page_data['title'] = 'journal';
        $this->load->view('accounting/journal', $this->page_data);
    }
    
    public function report_balance()
    {
        // ifPermissions('journal_list');
        $this->page_data['page']->submenu_child = 'report_balance';
        $this->page_data['title'] = 'journal';
        $this->page_data['get_account'] = $this->db->select('*')->from('acc_coa')->where('IsActive', 1)->order_by('HeadName')->get()->result();
        $this->page_data['visit'] = array();
        for ($i=0; $i < count($this->page_data['get_account']); $i++) { 
            $this->page_data['visit'][$i] = false;
        }
        $this->load->view('accounting/report_balance', $this->page_data);
    }
    
    public function profit_n_loss()
    {
        // ifPermissions('journal_list');
        $this->page_data['page']->submenu_child = 'profit_n_loss';
        $this->page_data['title'] = 'journal';
        $this->page_data['get_account'] = $this->db->select('*')->from('acc_coa')->where('IsActive', 1)->order_by('HeadName')->get()->result();
        $this->page_data['visit'] = array();
        for ($i=0; $i < count($this->page_data['get_account']); $i++) { 
            $this->page_data['visit'][$i] = false;
        }
        $this->load->view('accounting/profit_n_loss', $this->page_data);
    }

    public function chart_of_account_list()
    {
        $this->db->like('HeadName', post('query'), 'both');
        $result = $this->db->get('acc_coa')->result();
        $data['data'] = array_column($result, 'HeadName');
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}

/* End of file Journal.php */
/* Location: ./application/controllers/master_information/Journal.php */