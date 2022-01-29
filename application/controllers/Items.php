<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Items extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->page_data['page']->title = 'Item Management';
        $this->page_data['page']->menu = 'Items';
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        ifPermissions('items_list');
        $this->page_data['page']->submenu = 'list';
        $this->page_data['modals']->title = 'Modals upload items';
        $this->page_data['modals']->link  = 'items/upload';
        $this->page_data['title'] = 'item_list';

        $this->load->view('items/list', $this->page_data);
        $this->load->view('includes/modals', $this->page_data);
    }

    public function add()
    {
        ifPermissions('items_add');

        $this->form_validation->set_rules('category', 'Category item', 'required|trim');
        $this->form_validation->set_rules('item_code', 'Code item', 'required|trim');
        $this->form_validation->set_rules('item_name', 'Item name', 'required|trim');
        if ($this->form_validation->run() == false) {
            $this->page_data['title'] = 'item_add';
            $this->page_data['page']->submenu = 'add';
            $this->load->view('items/add', $this->page_data);
        } else {
            postAllowed();
            $data = [
                'item_code' => post('item_code'),
                'item_name' => post('item_name'),
                'category' => post('category'),
                'brand' => post('brand'),
                'brands' => post('brands'),
                'mg' => post('MG'),
                'ml' => post('ML'),
                'vg' => post('VG'),
                'pg' => post('PG'),
                'flavour' => post('flavour'),
                'quantity' => post('quantity'),
                'unit' => post('unit'),
                'capital_price' => post('capital_price'),
                'selling_price' => post('selling_price'),
                'customs' => post('customs'),
                'note' => post('note'),
                'created_by' => logged('id'),
            ];
            $item = $this->items_model->create($data);
            $this->activity_model->add("New Item #$item, #" . Post('item_code') . ", Created by User: #" . logged('id'), $data);
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'New Item Created Successfully');

            redirect('items/add');
        }
    }

    public function upload()
    {
        ifPermissions('upload_file');

        $data = $this->uploadlib->uploadFile();
        $request = [];
        $i = 0;
        foreach ($data as $key => $value) {
            if ($key == 1) {
                continue;
            } else {
                array_push($request, [
                    'item_code' => $value['A'],
                    'item_name' => $value['B'],
                    'category' => $value['C'],
                    'brand' => $value['M'],
                    'brands' => $value['N'],
                    'mg' => $value['D'],
                    'ml' => $value['E'],
                    'vg' => $value['F'],
                    'pg' => $value['G'],
                    'flavour' => $value['H'],
                    'quantity' => $value['I'],
                    'unit' => $value['J'],
                    'capital_price' => $value['K'],
                    'selling_price' => $value['L'],
                    'customs' => $value['O'],
                    'note' => $value['P'],
                    'created_by' => logged('id'),
                ]);
                $this->activity_model->add("New Item Upload, #" . $value['A'] . ", Created by User: #" . logged('id'), $request[$i]);
                $i++;
            }
        }
        $item = $this->items_model->create_batch($request);
        $this->session->set_flashdata('alert-type', 'success');
        $this->session->set_flashdata('alert', 'New Item Upload Successfully');

        redirect('items');
    }

    public function getItemCode()
    {
        if ($this->input->post('request')) {
            echo json_encode($this->db->get_where('items', ['category' => $this->input->post('data')])->num_rows());
        }
    }

    public function component()
    {
        if ($this->input->post('request')) {
            $this->load->view('items/add-component');
        }
    }

    public function edit()
    {
        ifPermissions('items_edit');
        $this->page_data['item'] = $this->items_model->getById(Get('id'));
        if (!$this->page_data['item']) {
            $this->load->view('errors/html/error_404');
            return false;
        }
        $this->form_validation->set_rules('item_name', 'Item name', 'required|trim');
        if ($this->form_validation->run() == false) {
            $this->page_data['title'] = 'item_edit';
            $this->page_data['page']->submenu = 'edit';
            $this->load->view('items/edit', $this->page_data);
        } else {
            $data = [
                'item_name' => post('item_name'),
                'brand' => post('brand'),
                'brands' => post('brands'),
                'mg' => post('mg'),
                'ml' => post('ml'),
                'vg' => post('vg'),
                'pg' => post('pg'),
                'flavour' => post('flavour'),
                'quantity' => post('quantity'),
                'unit' => post('unit'),
                'capital_price' => post('capital_price'),
                'selling_price' => post('selling_price'),
                'customs' => post('customs'),
                'note' => post('note'),
                'updated_at' => date('Y-m-d H:i:s', time()),
                'updated_by' => logged('id'),
            ];
            $history = (array)$this->items_model->getById(Get('id'));
            $item = $this->items_model->update(Get('id'), $data);
            $this->activity_model->add("Updated Item #$item, #" . Post('item_code') . ", Updated by User: #" . logged('id'), $history);

            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', "Item #$item has been Updated Successfully");

            redirect('items');
        }
    }

    public function info()
    {
        ifPermissions('items_info');
    }

    public function delete()
    {
        ifPermissions('items_delete');
    }

    public function truncate()
    {
        ifPermissions('items_truncate');
        $this->db->truncate('items');

        $this->activity_model->add("All item, Deleted by User: #" . logged('id'), (array)logged('id'));

        $this->session->set_flashdata('alert-type', 'success');
        $this->session->set_flashdata('alert', "Item #$item has been Updated Successfully");

        redirect('items');
    }

    public function serverside_datatables_data_items()
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
        $records = $this->db->get('items')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '')
            $this->db->like('item_name', $searchValue, 'both'); $this->db->or_like('item_code', $searchValue, 'both');
        $records = $this->db->get('items')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if ($searchQuery != '')
            $this->db->like('item_name', $searchValue, 'both'); $this->db->or_like('item_code', $searchValue, 'both');
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('items')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "id" => $record->id,
                "item_code" => $record->item_code,
                "item_name" => $record->item_name,
                "item_quantity" => $record->quantity,
                "item_unit" => $record->unit,
                "item_capital_price" => $record->capital_price,
                "item_selling_price" => $record->selling_price,
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

/* End of file Items.php */
/* Location: ./application/controllers/Items.php */