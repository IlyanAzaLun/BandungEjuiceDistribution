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
        $this->page_data['title'] = 'item_list';
        $this->page_data['modals'] = (object) array(
            'id' => 'modal-import',
            'title' => 'Modals upload items',
            'link' => 'items/upload',
            'content' => 'upload',
            'btn' => 'btn-primary',
            'submit' => 'Save changes',
        );

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
                'category' => post('subcategory') ? post('subcategory') : post('category'),
                'brand' => post('brand'),
                'brands' => post('brands'),
                'mg' => post('MG'),
                'ml' => post('ML'),
                'vg' => post('VG'),
                'pg' => post('PG'),
                'flavour' => post('flavour'),
                'quantity' => post('quantity'),
                'unit' => post('unit'),
                'capital_price' => setCurrency(post('capital_price')),
                'selling_price' => setCurrency(post('selling_price')),
                'customs' => post('customs'),
                'note' => post('note'),
                'created_by' => logged('id'),
            ];
            $item = $this->items_model->create($data);
            $this->activity_model->add("New Item #$item, #" . Post('item_code') . ", Created by User: #" . logged('id'), $data);
            $this->create_item_history(array(0 => $data), 'IN');
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', 'New Item Created Successfully');

            redirect('items/add');
        }
    }

    public function upload()
    {
        ifPermissions('upload_file');
        
        if (!$_FILES) {
            $this->session->set_flashdata('alert-type', 'danger');
            $this->session->set_flashdata('alert', 'Empty File, Please select file to import');
            redirect('items');
        }

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
        $this->create_item_history($request, 'IN');
        $this->session->set_flashdata('alert-type', 'success');
        $this->session->set_flashdata('alert', 'New Item Upload Successfully');

        redirect('items');
    }

    public function getItemCode()
    {
        if ($this->input->post('request')) {
            $this->db->like('item_code', $this->input->post('data'), 'after');
            echo json_encode($this->db->get('items')->num_rows());
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
            $id = $this->input->get('id');
            $data = [
                'item_code' => post('item_code'),
                'item_name' => post('item_name'),
                'category' => post('subcategory') ? post('subcategory') : post('category'),
                'brand' => post('brand'),
                'brands' => post('brands'),
                'mg' => post('mg'),
                'ml' => post('ml'),
                'vg' => post('vg'),
                'pg' => post('pg'),
                'flavour' => post('flavour'),
                'quantity' => post('quantity'),
                'unit' => post('unit'),
                'capital_price' => setCurrency(post('capital_price')),
                'selling_price' => setCurrency(post('selling_price')),
                'customs' => post('customs'),
                'note' => post('note'),
                'is_active' => post('is_active') ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s', time()),
                'updated_by' => logged('id'),
            ];
            $history = (array)$this->items_model->getById($id);
            $this->create_item_history(array(0 => $data), 'UPDATE');
            $item = $this->items_model->update($id, $data);
            $this->activity_model->add("Updated Item #$item, #" . Post('item_code') . ", Updated by User: #" . logged('id'), $history);

            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert', "Item #$item has been Updated Successfully");

            redirect('items');
        }
    }

    private function create_item_history($data, $status_type)
    {
        $item = array();
        foreach ($data as $key => $value) {
            array_push($item, $this->db->get_where('items', ['item_code' => $value['item_code']])->row());
            $data[$key]['item_id'] = $item[$key]->id;
            $data[$key]['item_quantity'] = $item[$key]->quantity;
            $data[$key]['item_order_quantity'] = $value['quantity'];
            $data[$key]['item_unit'] = $value['unit'];
            $data[$key]['item_capital_price'] = $value['capital_price'];
            $data[$key]['item_selling_price'] = $value['selling_price'];
            $data[$key]['status_type'] = $status_type;
            $data[$key]['status_transaction'] = __CLASS__;
            $data[$key]['category'] = $item[$key]->category;
            $data[$key]['created_by'] = logged('id');
            unset($data[$key]['capital_price']);
            unset($data[$key]['is_active']);
            unset($data[$key]['unit']);
            unset($data[$key]['quantity']);
            unset($data[$key]['selling_price']);
            unset($data[$key]['category']);
            unset($data[$key]['note']);
            unset($data[$key]['brand']);
            unset($data[$key]['brands']);
            unset($data[$key]['mg']);
            unset($data[$key]['ml']);
            unset($data[$key]['vg']);
            unset($data[$key]['pg']);
            unset($data[$key]['flavour']);
            unset($data[$key]['customs']);
        }
        return $this->items_history_model->create_batch($data);
    }

    public function info()
    {
        ifPermissions('items_info');
        $this->page_data['page']->submenu = 'list';
        $this->page_data['title'] = 'item_list_information';

        $this->page_data['item'] = $this->items_model->getByCodeItem(get('id'));
        if (!$this->page_data['item']) {
            $this->load->view('errors/html/error_404');
            return false;
        }
        $this->load->view('items/info', $this->page_data);
    }

    
    public function info_transaction()
    {
        ifPermissions('items_info');
        $this->page_data['page']->submenu = 'list';
        $this->page_data['title'] = 'item_list_information_transaction';

        $this->page_data['item'] = $this->items_model->getByCodeItem(get('id'));
        if (!$this->page_data['item']) {
            $this->load->view('errors/html/error_404');
            return false;
        }
        $this->page_data['customer'] = $this->customer_model->get();
        $this->page_data['supplier'] = $this->supplier_model->get();
        $this->load->view('items/transaction', $this->page_data);
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
        $this->session->set_flashdata('alert', "All Item has been Deleted Successfully");

        redirect('items');
    }

    public function serverside_datatables_data_items()
    {
        ifPermissions('items_list');
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
        if ($searchQuery != '') {
            $this->db->like('item_name', $searchValue, 'both');
            $this->db->or_like('item_code', $searchValue, 'both');
            $this->db->or_like('category', $searchValue, 'both');
        }
        $records = $this->db->get('items')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if ($searchQuery != '') {
            $this->db->like('item_name', $searchValue, 'both');
            $this->db->or_like('item_code', $searchValue, 'both');
            $this->db->or_like('category', $searchValue, 'both');
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('items')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "id" => $record->id,
                "item_code" => $record->item_code,
                "item_name" => $record->item_name,
                "category" => $record->category,
                "item_quantity" => $record->quantity,
                "item_broken" => $record->broken,
                "item_unit" => $record->unit,
                "item_capital_price" => $record->capital_price,
                "item_selling_price" => $record->selling_price,
                "is_active" => $record->is_active,
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

    public function serverside_datatables_data_items_information()
    {
        // ifPermissions('items_list');
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
        $this->db->where('item_code', post('id'));
        $records = $this->db->get('items_history')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchValue != '') {
            $this->db->like('history.item_name', $searchValue, 'both');
            $this->db->or_like('history.item_code', $searchValue, 'both');
            $this->db->or_like('user.name', $searchValue, 'both');
        }
        $this->db->join('users user', 'user.id=history.created_by', 'left');
        $this->db->where('history.item_code', post('id'));
        $records = $this->db->get('items_history history')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('user.name
        , user.id as user_id
        , history.item_code
        , history.item_name
        , history.item_quantity 
        , SUM(history.item_order_quantity) AS item_order_quantity 
        , history.item_unit
        , history.item_capital_price
        , history.item_selling_price
        , history.item_discount
        , history.total_price
        , history.status_type
        , history.status_transaction
        , history.invoice_reference
        , history.id as history_id
        , history.created_by as history_created_by
        , history.created_at as history_created_at
        , history.updated_by as history_updated_by
        , history.updated_at as history_updated_at');
        if ($searchValue != '') {
            $this->db->like('history.item_name', $searchValue, 'both');
            $this->db->or_like('history.item_code', $searchValue, 'both');
            $this->db->or_like('user.name', $searchValue, 'both');
        }
        $this->db->join('users user', 'user.id=history.created_by', 'left');
        $this->db->where('history.item_code', post('id'));
        $this->db->order_by('history.created_at', 'desc');
        $this->db->order_by("history.$columnName", $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $this->db->group_by('history.created_at');
        $records = $this->db->get('items_history history')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "id" => $record->history_id,
                "user_id" => $record->user_id,
                "item_code" => $record->item_code,
                "item_name" => $record->item_name,
                "item_quantity" => $record->item_quantity,
                "item_order_quantity" => $record->item_order_quantity,
                "item_unit" => $record->item_unit,
                "item_capital_price" => $record->item_capital_price,
                "item_selling_price" => $record->item_selling_price,
                "item_discount" => $record->item_discount,
                "total_price" => $record->total_price,
                "status_type" => $record->status_type,
                "status_transaction" => $record->status_transaction,
                "invoice_reference" => $record->invoice_reference,
                "updated_at" => $record->history_updated_at,
                "created_by" => $record->name,
                "created_at" => $record->history_created_at,
                "updated_by" => $record->history_updated_by
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

    

    public function serverside_datatables_data_items_transaction()
    {
        /**
         * List item transcation
         * Schedule: Order, show to list transaction, and removed if created invoice is done. 
         *  
         * **/
        ifPermissions('items_list');
        try {
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
            $item_code = $postData['id'];
            $dateStart = $postData['startDate'];
            $dateFinal = $postData['finalDate'];    
            $getCustomer = $postData['getCustomer'];
            $customer = $postData['DCustomer'];
            $supplier = $postData['DSupplier'];

            ## Total number of records without filtering
            $this->db->select('count(*) as allcount');
            $this->db->where('item_code', $item_code);
            if ($customer || $supplier) {
                $this->db->where('customer_code', $customer);
                $this->db->or_where('customer_code', $supplier);
            }
            $records = $this->db->get('invoice_transaction_list_item')->result();
            $totalRecords = $records[0]->allcount;

            ## Total number of record with filtering
            $this->db->select('count(*) as allcount');
            if ($searchValue != '') {
                $this->db->like('transaction.item_name', $searchValue, 'both');
                $this->db->or_like('transaction.item_code', $searchValue, 'both');
                $this->db->or_like('transaction.customer_code', $searchValue, 'both');
            }
            $this->db->join('users user_created', 'user_created.id=transaction.created_by', 'left');
            $this->db->join('users user_updated', 'user_updated.id=transaction.updated_by', 'left');
            $this->db->where('item_code', $item_code);
            if ($dateStart != '') {
                $this->db->where("transaction.created_at >=", $dateStart);
                $this->db->where("transaction.created_at <=", $dateFinal);
            }
            if ($customer || $supplier) {
                $this->db->where('transaction.customer_code', $customer);
                $this->db->or_where('transaction.customer_code', $supplier);
            }
            $records = $this->db->get('invoice_transaction_list_item transaction')->result();
            $totalRecordwithFilter = $records[0]->allcount;

            ## Fetch records
            $this->db->select('
                transaction.id as transaction_id
                , transaction.invoice_code as invoice_code
                , SUBSTRING(transaction.invoice_code, 5) as invoice_code_reference
                , transaction.item_id as item_id
                , transaction.item_code as item_code
                , transaction.item_name as item_name
                , transaction.item_capital_price as item_capital_price
                , transaction.item_selling_price as item_selling_price
                , transaction.item_current_quantity as item_current_quantity
                , transaction.item_quantity as item_quantity
                , IF(transaction.item_status = "IN",  transaction.item_quantity, NULL) as item_in
                , IF(transaction.item_status = "OUT", transaction.item_quantity, NULL) as item_out
                , transaction.item_unit as item_unit
                , transaction.item_discount as item_discount
                , transaction.total_price as total_price
                , transaction.item_status as item_status
                , transaction.customer_code as customer_code
                , supplier.store_name as supplier_name
                , customer.store_name as customer_name          
                , transaction.item_description as item_description
                , transaction.created_at as transaction_created_at
                , user_created.name as transaction_created_by
                , transaction.updated_at as transaction_updated_at
                , IF(transaction.updated_at, transaction.updated_at, transaction.created_at) as transaction_date_at
                , user_updated.name as transaction_updated_by
                , user_created.id as user_id
                , transaction.is_cancelled as is_cancelled');
            $this->db->join('users user_created', 'user_created.id=transaction.created_by', 'left');
            $this->db->join('users user_updated', 'user_updated.id=transaction.updated_by', 'left');
            $this->db->join('supplier_information supplier', 'supplier.customer_code = transaction.customer_code', 'left');
            $this->db->join('customer_information customer', 'customer.customer_code = transaction.customer_code', 'left');
            if ($searchValue != '') {
                $this->db->like('item_name', $searchValue, 'both');
                $this->db->or_like('item_code', $searchValue, 'both');
                $this->db->or_like('transaction.customer_code', $searchValue, 'both');
            }
            if ($customer || $supplier) {
                $this->db->where("(transaction.customer_code = '$customer' OR transaction.customer_code = '$supplier')");
            }
            if($dateStart != '') {
                $this->db->where("((transaction.created_at >= '$dateStart' AND transaction.created_at <= '$dateFinal') OR (transaction.updated_at >= '$dateStart' AND transaction.updated_at <= '$dateFinal'))");
            }
            $this->db->where('item_code', $item_code);
            $this->db->order_by($columnName, $columnSortOrder);
            // $this->db->group_by('invoice_code');
            $this->db->limit($rowperpage, $start);
            $records = $this->db->get('invoice_transaction_list_item transaction')->result();
            $data = array();
            
            // echo '<pre>';
            // var_dump($this->db->last_query());
            // echo '</pre>';
            // die();

            foreach ($records as $record) {

                $data[] = array(
                    "transaction_id" => $record->transaction_id,
                    "user_id" => $record->user_id,
                    "item_code" => $record->item_code,
                    "item_name" => $record->item_name,
                    "item_current_quantity" => $record->item_current_quantity,
                    "item_quantity" => $record->item_quantity,
                    "item_in" => $record->item_in,
                    "item_out" => $record->item_out,
                    "item_unit" => $record->item_unit,
                    "item_capital_price" => $record->item_capital_price,
                    "item_selling_price" => $record->item_selling_price,
                    "item_discount" => $record->item_discount,
                    "total_price" => $record->total_price,
                    "item_status" => $record->item_status,
                    "item_description" => $record->item_description,
                    "customer_code" => $record->customer_code,
                    "supplier_name" => $record->supplier_name,
                    "customer_name" => $record->customer_name,
                    "invoice_code" => $record->invoice_code,
                    "invoice_code_reference" => $record->invoice_code_reference,
                    "transaction_created_by" => $record->transaction_created_by,
                    "transaction_created_at" => $record->transaction_created_at,
                    "transaction_updated_by" => $record->transaction_updated_by,
                    "transaction_updated_at" => $record->transaction_updated_at,
                    "transaction_date_at" => $record->transaction_date_at,
                    "is_cancelled" => $record->is_cancelled,
                );
            }

            ## Response
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "aaData" => $data,
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } catch (\Throwable $th) {
            echo '<pre>';
            var_dump($th);
            echo '<hr>';
            var_dump($this->db->last_query());
            echo '</pre>';
        }
    }

    public function data_items()
    {
        if (ifPermissions('items_list')) {
            $search = (object) post('search');
            $this->db->limit(15);
            if ($search->value) {
                $this->db->like('item_code', $search->value, 'both');
                $this->db->or_like('item_name', $search->value, 'both');
            }
            $response = $this->db->get('items')->result();
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        };
    }
}

/* End of file Items.php */
/* Location: ./application/controllers/Items.php */