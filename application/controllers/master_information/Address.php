<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Address extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->page_data['page']->title = 'Address Management';
        $this->page_data['page']->menu = 'address';
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        ifPermissions('address_list');
        redirect('master_information/address/add');
    }

    public function add()
    {
        ifPermissions('address_add');
        $this->page_data['page']->submenu = 'add';
        $this->page_data['title'] = 'address_add';

        $this->form_validation->set_rules('customer_code', lang('store_name'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['customers'] = $this->customer_model->get();
            foreach ($this->supplier_model->get() as $key => $value) {
                array_push($this->page_data['customers'], $value);
            }
            $this->load->view('address/add', $this->page_data);
        } else {
            $data = [
                'customer_code' => post('customer_code'),
                'province' => post('province'),
                'city' => post('city'),
                'sub_district' => post('sub_district'),
                'village' => post('village'),
                'zip' => post('zip'),
                'contact_phone' => post('contact_phone'),
                'contact_mail' => post('contact_mail'),
                'created_by' => logged('id'),
                'is_active' => 1,
                'note' => post('note'),
            ];
            $address = $this->address_model->create($data);

            $this->activity_model->add("New Address #$address, Created by User: #" . logged('id'), (array)$data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'New Address Created Successfully');

            redirect('master_information/address/add');
        }
    }

    public function edit()
    {
        ifPermissions('address_edit');
        $this->page_data['page']->submenu = 'edit';
        $this->page_data['title'] = 'address_edit';

        $this->form_validation->set_rules('customer_code', lang('store_name'), 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->page_data['customers'] = $this->customer_model->get();
            foreach ($this->supplier_model->get() as $key => $value) {
                array_push($this->page_data['customers'], $value);
            }
            $this->page_data['address'] = $this->address_model->getById(get('id'));
            $this->load->view('address/edit', $this->page_data);
        } else {
            $data = [
                'customer_code' => post('customer_code'),
                'province' => post('province'),
                'city' => post('city'),
                'sub_district' => post('sub_district'),
                'village' => post('village'),
                'zip' => post('zip'),
                'contact_phone' => post('contact_phone'),
                'contact_mail' => post('contact_mail'),
                'updated_at' => date('Y-m-d H:i:s', time()),
                'updated_by' => logged('id'),
                'is_active' => post('is_active') ? 1 : 0,
                'address' => post('address'),
                'note' => post('note'),
            ];
            $address = $this->address_model->update(GET('id'), $data);

            $this->activity_model->add("Update Address #$address, Update by User: #" . logged('id'), (array)$this->page_data['address']);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'New Address Update Successfully');
            if (preg_match('/PL/i', $data['customer_code'])) {
                redirect("master_information/address/detail?id=$address");
            }else{
                redirect("master_information/address/detail?id=$address");
            }
        }
    }

    public function detail()
    {
        $this->page_data['information'] = $this->address_model->full_information(get('id'));
        $this->form_validation->set_rules('id', 'Information', 'required|trim');
        if($this->form_validation->run() == false){
            $this->page_data['expedition'] = $this->expedition_model->get();
            $this->load->view('address/details', $this->page_data);
        }else{
            // SAVE INFORMATION IF CHANGE
            $this->db->trans_begin();
            $this->db->where('customer_code', post('customer_code'));
            $customer_information = $this->db->get('customer_information')->row();
            $type = 'customer';
            if(is_null($customer_information)){
                $this->db->where('customer_code', post('customer_code'));
                $customer_information = $this->db->get('supplier_information')->row();    
                $type = 'supplier';
            }
            $customer_information->customer_code = post("customer_code");
            $customer_information->store_name = strtoupper(post("store_name"));
            $customer_information->owner_name = strtoupper(post("owner_name"));
            $customer_information->contact_us = strtoupper(post("contact_us"));
            if($type == 'customer' ){
                $this->customer_model->update($customer_information->id ,$customer_information);
            }else{
                $this->supplier_model->update($customer_information->id ,$customer_information);
            }
            $this->db->trans_complete();
            $this->page_data['information'] = $this->address_model->full_information(get('id'));  
            $this->page_data['post'] = $this->input->post();
            // then print information
            $this->load->library('pdf');
        
            $options = $this->pdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $this->pdf->setOptions($options);

            $this->pdf->setPaper('A5', 'potrait');
            // $this->pdf->filename = $data['customer']->store_name."pdf";
            $this->pdf->load_view('address/print', $this->page_data);
        }
    }

    public function data_address()
    {
        if (hasPermissions('requst_data_customer')) {
            $search = (object) post('search');
            $this->db->limit(5);
            $this->db->select("
            IFNULL(customer_information.id,supplier_information.id) AS id
            , IFNULL(customer_information.customer_code,supplier_information.customer_code) AS customer_code
            , IFNULL(customer_information.store_name,supplier_information.store_name) AS store_name
            , IFNULL(customer_information.owner_name,supplier_information.owner_name) AS owner_name
            , IFNULL(customer_information.contact_us,supplier_information.contact_us) AS contact_us
            , address_information.address
            , address_information.village
            , address_information.sub_district
            , address_information.city
            , address_information.province
            , address_information.zip
            , address_information.contact_phone
            , address_information.contact_mail
            ");
            if (isset($search->value)) {
                $this->db->like('customer_information.customer_code', $search->value, 'both');
                $this->db->or_like('supplier_information.customer_code', $search->value, 'both');
                $this->db->or_like('customer_information.store_name', $search->value, 'both');
                $this->db->or_like('supplier_information.store_name', $search->value, 'both');
                $this->db->or_like('customer_information.owner_name', $search->value, 'both');
                $this->db->or_like('supplier_information.owner_name', $search->value, 'both');
            }
            $this->db->join('customer_information', 'customer_information.customer_code = address_information.customer_code','left');
            $this->db->join('supplier_information', 'supplier_information.customer_code = address_information.customer_code','left');
            $this->db->where('address_information.is_active', 1);
            $response = $this->db->get('address_information')->result();
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }else{
            ifPermissions('order_create');
        }
    }
}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */