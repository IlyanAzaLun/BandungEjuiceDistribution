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
                redirect("master_information/customer/edit?id=" . $data['customer_code']);
            }else{
                redirect("master_information/supplier/edit?id=" . $data['customer_code']);
            }
        }
    }
}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */