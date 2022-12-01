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
                'mg' => post('mg'),
                'ml' => post('ml'),
                'vg' => post('vg'),
                'pg' => post('pg'),
                'flavour' => post('flavour'),
                'quantity' => post('quantity'),
                'unit' => post('unit'),
                'weight' => post('weight'),
                'capital_price' => setCurrency(post('capital_price')),
                'selling_price' => setCurrency(post('selling_price')),
                'shadow_selling_price' => setCurrency(post('shadow_selling_price')),
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
        $items_fifo = [];
        $items_transaction = [];
        $i = 0;
        $now = date('ym');
        $item = array();
        foreach ($data as $key => $value) {
            if ($key == 1) {
                continue;
            } else {
                if(!$value['A']){
                    break;
                }else{
                    $item = $this->items_model->getByCodeItem($value['A']);
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
                        'weight' => $value['Q'],
                        'created_by' => logged('id'),
                    ]);
                    //transaction
                    array_push($items_fifo, [
                        'invoice_code' => "IMPORT/$now",
                        'item_id' => $item->id,
                        'item_code' => $value['A'],
                        'item_name' => $value['B'],
                        'item_quantity' => $value['I'],
                        'item_unit' => $value['J'],
                        'item_capital_price' => $value['K'],
                        'item_discount' => 0,
                        'customer_code' => 0,
                        'total_price' => (int) $value['I'] * (int) $value['K'],
                        'created_by' => logged('id'),
                    ]);
                    array_push($items_transaction, [
                        'invoice_code' => "IMPORT/$now",
                        'item_id' => $item->id,
                        'item_code' => $value['A'],
                        'item_name' => $value['B'],
                        'item_current_quantity' => (int) $value['I'], // not used on fifo
                        'item_quantity' => $value['I'],
                        'item_unit' => $value['J'],
                        'item_capital_price' => $value['K'],
                        'item_selling_price' => $value['L'], // not used on fifo
                        'item_status' => 'IN', // not used on fifo
                        'item_discount' => 0,
                        'item_description' => 0, // not used on fifo
                        'index_list' => 0, // not used on fifo
                        'customer_code' => 0,
                        'total_price' => 0,
                        'created_by' => logged('id'),
                    ]);
                    if($this->items_model->getByCodeItem($value['A'], 'item_code')){
                        $data_positif[] = $request[$i];
                    }else{
                        $data_negatif[] = $request[$i];
                    }
                    $request[$i]['invoice_reference'] = "IMPORT/$now";
                    //where is_readable = 1, is_cancel = 0, where item_code set item_quantity = 0 
                    $this->items_fifo_model->reset_fifo_by_item_code($value['A']);

                    $this->activity_model->add("New Item Upload, #" . $value['A'] . ", Created by User: #" . logged('id'));
                    $i++;
                }
            }
        }
        // UPDATE OR CREATE ITEMS
        if (@$data_negatif) {
            if ($this->items_model->create_batch($data_negatif) && $this->items_model->update_batch($data_positif, 'item_code')) {
                // 
			}
        }else{
            $this->items_model->update_batch($data_positif, 'item_code');
        }
        // CREATE NEW VALUE QUANTITY OF FIFO AND TRANSACTION.
        $this->items_fifo_model->create_batch($items_fifo);
        $this->transaction_item_model->create_batch($items_transaction);

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
            // echo '<pre>';
            $id = $this->input->get('id');
            // var_dump($this->input->post());
            // echo '</pre>';
            // die();
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
                'weight' => post('weight'),
                'capital_price' => setCurrency(post('capital_price')),
                'selling_price' => setCurrency(post('selling_price')),
                'shadow_selling_price' => setCurrency(post('shadow_selling_price')),
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

            redirect('items/edit?id='.get('id'));
        }
    }

    private function create_item_history($data, $status_type)
    {
        $item = array();
        foreach ($data as $key => $value) {
            $item[$key] = $this->db->get_where('items', ['item_code' => $value['item_code']])->row_array();
            $data[$key]['item_id'] = $item[$key]['id'];
            $data[$key]['item_quantity'] = $item[$key]['quantity'];
            $data[$key]['item_order_quantity'] = $value['quantity'];
            $data[$key]['item_unit'] = $value['unit'];
            $data[$key]['item_capital_price'] = $value['capital_price'];
            $data[$key]['item_selling_price'] = $value['selling_price'];
            $data[$key]['status_type'] = $status_type;
            $data[$key]['status_transaction'] = __CLASS__;
            $data[$key]['category'] = $item[$key]['category'];
            $data[$key]['created_by'] = logged('id');
            unset($data[$key]['capital_price']);
            unset($data[$key]['is_active']);
            unset($data[$key]['unit']);
            unset($data[$key]['quantity']);
            unset($data[$key]['weight']);
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

    public function info_fifo()
    {
        ifPermissions('list_fifo');
        
        $this->page_data['page']->submenu = 'list';
        $this->page_data['title'] = 'item_list_information_fifo';

        $this->page_data['item'] = $this->items_model->getByCodeItem(get('id'));
        if (!$this->page_data['item']) {
            $this->load->view('errors/html/error_404');
            return false;
        }
        $this->page_data['customer'] = $this->customer_model->get();
        $this->page_data['supplier'] = $this->supplier_model->get();
        $this->load->view('items/fistIn_fistOut', $this->page_data);
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
            $this->db->or_like('brand', $searchValue, 'after');
            $this->db->or_like('brands', $searchValue, 'after');
            $this->db->or_like('note', $searchValue, 'both');
        }
        $records = $this->db->get('items')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        /***
        // THIS QUERY TO SLOW RESPONSE TO DISPLAY RESULT
        $this->db->select('purchase_transactions.item_quantity AS transaction_quantity, purchase_transactions.created_at AS restock_date, sale_transactions.created_at AS sales_date, items.*, CAST(items.quantity AS INT) as quantity');
        // ONE TO MANY QUERY, to get last date data information on each infromation data
        // Reference : https://stackoverflow.com/questions/2111384/sql-join-selecting-the-last-records-in-a-one-to-many-relationship
        $this->db->join("(SELECT id, item_code, item_id, item_quantity, created_at FROM invoice_transaction_list_item WHERE invoice_code LIKE 'INV/PURCHASE/%') purchase_transactions", 'purchase_transactions.item_id = items.id', 'left');
        $this->db->join("(SELECT id,item_id,created_at FROM invoice_transaction_list_item WHERE invoice_code LIKE 'INV/PURCHASE/%') p2", '(p2.item_id = items.id AND (purchase_transactions.created_at < p2.created_at OR (purchase_transactions.created_at = p2.created_at AND purchase_transactions.id < p2.id)))', 'left');
        ***/
        $this->db->select('
                        invoice_transaction_list_item.total_item_quantity AS transaction_quantity, 
                        invoice_transaction_list_item.created_at AS restock_date, 
                        sale_transactions.created_at AS sales_date, 
                        items.*, 
                        CAST(items.quantity AS INT) as quantity,
                        CAST(items.capital_price AS INT) as capital_price,
                        CAST(items.selling_price AS INT) as selling_price');
        // ONE TO MANY QUERY, to get last date data information on each infromation data
        // Reference : https://stackoverflow.com/questions/2111384/sql-join-selecting-the-last-records-in-a-one-to-many-relationship
        $this->db->join("(SELECT id, item_code, item_id, created_at,
                                DATE_FORMAT(created_at, '%y%m%d') AS ordered_at,
                                SUM(item_quantity) AS total_item_quantity
                        FROM invoice_transaction_list_item 
                        WHERE 
                                invoice_code LIKE 'INV/PURCHASE/%'
                        GROUP BY item_code, DATE_FORMAT(created_at, '%y%m%d'))
                        invoice_transaction_list_item", 'invoice_transaction_list_item.item_id = items.id', 'left');
        $this->db->join("(
                        SELECT id, item_code, item_id, created_at,
                            MAX(DATE_FORMAT(created_at, '%y%m%d')) AS ordered_at,
                            SUM(item_quantity) AS total_item_quantity
                        FROM invoice_transaction_list_item 
                        WHERE
                                invoice_code LIKE 'INV/PURCHASE/%'
                        GROUP BY item_code) p2", '(items.item_code = p2.item_code AND (invoice_transaction_list_item.ordered_at < p2.ordered_at OR (invoice_transaction_list_item.ordered_at = p2.ordered_at AND invoice_transaction_list_item.id < p2.id)))', 'left');
        // END OF ONE TO MANY QUERY
        $this->db->join("(SELECT id, item_id, item_code, MAX(created_at) AS created_at FROM invoice_transaction_list_item WHERE invoice_code LIKE 'INV/SALE/%' GROUP BY item_id) sale_transactions", 'sale_transactions.item_id = items.id', 'left');
        if ($searchQuery != '') {
            $this->db->group_start();
            $this->db->like('items.item_name', $searchValue, 'both');
            $this->db->or_like('items.item_code', $searchValue, 'both');
            $this->db->or_like('items.category', $searchValue, 'both');
            $this->db->or_like('items.brand', $searchValue, 'after');
            $this->db->or_like('items.brands', $searchValue, 'after');
            $this->db->or_like('items.note', $searchValue, 'both');
            $this->db->group_end();
        }
        $this->db->where('p2.id', null);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('items')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "restock_date" => $record->restock_date,
                "transaction_quantity" => $record->transaction_quantity,
                "sales_date" => $record->sales_date,
                "id" => $record->id,
                "item_code" => $record->item_code,
                "item_name" => $record->item_name,
                "category" => $record->category,
                "quantity" => $record->quantity,
                "weight" => (int)$record->weight,
                "item_broken" => $record->broken,
                "item_unit" => $record->unit,
                "brand" => $record->brand,
                "brands" => $record->brands,
                "capital_price" => $record->capital_price,
                "selling_price" => $record->selling_price,
                "shadow_selling_price" => $record->shadow_selling_price,
                "note" => $record->note,
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
        $this->db->group_by('history.invoice_reference');
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

    public function serverside_datatabels_data_items_fifo()
    {
        ifPermissions('backup_db');
        /**
         * Information items fisrt in fisrt out
         **/
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
        $dateStart = @$postData['startDate'];
        $dateFinal = @$postData['finalDate'];    
        $getCustomer = $postData['getCustomer'];
        $customer = @$postData['DCustomer'];
        $supplier = @$postData['DSupplier'];

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where('item_code', $item_code);
        $this->db->where('is_cancelled', 0);
        if ($customer || $supplier) {
            $this->db->where('customer_code', $customer);
            $this->db->or_where('customer_code', $supplier);
        }
        $records = $this->db->get('fifo_items')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchValue != '') {
            $this->db->like('item_name', $searchValue, 'both');
            $this->db->or_like('item_code', $searchValue, 'both');
            $this->db->or_like('customer_code', $searchValue, 'both');
        }
        $this->db->where('item_code', $item_code);
        $this->db->where('is_cancelled', 0);
        if ($dateStart != '') {
            $this->db->where("created_at >=", $dateStart);
            $this->db->where("created_at <=", $dateFinal);
        }
        if ($customer || $supplier) {
            $this->db->where('customer_code', $customer);
            $this->db->or_where('customer_code', $supplier);
        }
        $records = $this->db->get('fifo_items')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('
             fifo_items.id
            ,fifo_items.invoice_code
            ,fifo_items.item_code
            ,fifo_items.item_name
            ,fifo_items.item_quantity
            ,fifo_items.item_discount
            ,fifo_items.total_price
            ,fifo_items.customer_code
            ,fifo_items.is_cancelled
            ,fifo_items.parent
            ,user_created.name as created_by
            ,user_updated.name as updated_by
            ,IF(fifo_items.updated_at, fifo_items.updated_at, fifo_items.created_at) as fifo_date_at
        ');
        $this->db->join('users user_created', 'user_created.id=fifo_items.created_by', 'left');
        $this->db->join('users user_updated', 'user_updated.id=fifo_items.updated_by', 'left');
        $this->db->join('supplier_information supplier', 'supplier.customer_code = fifo_items.customer_code', 'left');
        $this->db->join('customer_information customer', 'customer.customer_code = fifo_items.customer_code', 'left');
        $this->db->where('item_code', $item_code);
        $this->db->where('is_cancelled', 0);
        $this->db->where('is_readable', 1);
        if ($searchValue != '') {
            $this->db->like('item_name', $searchValue, 'both');
            $this->db->or_like('fifo_items.customer_code', $searchValue, 'both');
        }
        if ($customer || $supplier) {
            $this->db->where("(fifo_items.customer_code = '$customer' OR fifo_items.customer_code = '$supplier')");
        }
        if($dateStart != '') {
            // $this->db->where("((fifo_items.created_at >= '$dateStart' AND fifo_items.created_at <= '$dateFinal') OR (fifo_items.updated_at >= '$dateStart' AND fifo_items.updated_at <= '$dateFinal'))");
            $this->db->group_start();                
            $this->db->where("fifo_items.created_at >=", $dateStart);
            $this->db->where("fifo_items.created_at <=", $dateFinal);
            $this->db->group_end(); 
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get("fifo_items")->result();
        $data = array();
        
        // echo '<pre>';
        // var_dump($this->db->last_query());
        // echo '</pre>';
        // die();

        foreach ($records as $record) {

            $data[] = array(
                "invoice_code" => $record->invoice_code,
                "item_code" => $record->item_code,
                "item_name" => $record->item_name,
                "item_quantity" => $record->item_quantity,
                "item_discount"=>$record->item_discount,
                "total_price"=>$record->total_price,
                "customer_code"=>$record->customer_code,
                "is_cancelled"=>$record->is_cancelled,
                "parent"=>$record->parent,
                "created_by"=>$record->created_by,
                "updated_by"=>$record->updated_by,
                "fifo_date_at"=>$record->fifo_date_at,
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
    }

    public function serverside_datatables_data_items_transaction()
    {
        /**
         * List item transcation
         * Schedule: Order, show to list transaction, and removed if created invoice is done. 
         *  
         **/
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
            $customer = $postData['DCustomer']?$postData['DCustomer']:$postData['getCustomer'];
            $supplier = $postData['DSupplier'];
            $type = $postData['Dtype'];

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
                $this->db->group_start();
                $this->db->where("transaction.created_at >=", $dateStart);
                $this->db->where("transaction.created_at <=", $dateFinal);
                $this->db->group_end();
            }
            if ($customer || $supplier) {
                $this->db->group_start();
                $this->db->where('transaction.customer_code', $customer);
                $this->db->or_where('transaction.customer_code', $supplier);
                $this->db->group_end();
            }
            $records = $this->db->get('invoice_transaction_list_item transaction')->result();
            $totalRecordwithFilter = $records[0]->allcount;

            ## Fetch records
            // QUERY 1
            $this->db->select('
                `id`, 
                `index_list`, 
                `invoice_code`, 
                `item_id`, 
                `item_code`, 
                `item_name`, 
                `item_capital_price`, 
                `item_selling_price`, 
                `item_current_quantity`, 
                `item_quantity`, 
                `item_unit`, 
                `item_total_weight`, 
                `item_discount`, 
                `total_price`, 
                `item_status`, 
                `item_description`, 
                `customer_code`, 
                `is_cancelled`, 
                `created_at`, 
                `created_by`, 
                `updated_at`, 
                `updated_by`
            ');
            $this->db->group_start();
            $this->db->where('is_cancelled',0);
            $this->db->group_end();
            $raw_invoice_query = $this->db->get_compiled_select('invoice_transaction_list_item', false);
            $this->db->reset_query();
            // QUERY 1 END
            // QUERY 2
            $this->db->select('
                `id`, 
                `index_list`, 
                `order_code`, 
                `item_id`, 
                `item_code`, 
                `item_name`, 
                `item_capital_price`, 
                `item_selling_price`, 
                `item_quantity`, 
                `item_order_quantity`, 
                `item_unit`, 
                0 AS item_total_weight,
                `item_discount`, 
                `item_total_price`, 
                "OUT" AS item_status, 
                `item_description`, 
                `customer_code`, 
                `is_cancelled`, 
                `created_at`, 
                `created_by`, 
                `updated_at`, 
                `updated_by` 
            ');
            $this->db->group_start();
            $this->db->where('item_code',$item_code);
            $this->db->where('is_cancelled',0);
            $this->db->where('status_available',NULL);
            $this->db->group_end();
            $raw_order_query = $this->db->get_compiled_select('order_sale_list_item', false);
            $this->db->reset_query();
            // QUERY 2 END
            $this->db->select('
                transaction.id as transaction_id
                , transaction.invoice_code as invoice_code
                , SUBSTRING(transaction.invoice_code, 5) as invoice_code_reference
                , transaction.item_id as item_id
                , transaction.item_code as item_code
                , transaction.item_name as item_name
                , transaction.item_capital_price as item_capital_price
                , transaction.item_selling_price as item_selling_price
                , items.shadow_selling_price
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
            $this->db->join('items', 'items.item_code = transaction.item_code', 'left');
            if ($searchValue != '') {
                $this->db->group_start();
                $this->db->like('transaction.item_name', $searchValue, 'both');
                $this->db->or_like('transaction.item_code', $searchValue, 'both');
                $this->db->or_like('transaction.customer_code', $searchValue, 'both');
                $this->db->group_end();
            }
            if($type == 'purchase'){
                $this->db->like('transaction.invoice_code', 'INV/PURCHASE/', 'after');
            }else{
                $this->db->like('transaction.invoice_code', 'INV/SALE/', 'both');
            }
            if ($customer || $supplier) {
                $this->db->group_start();
                $this->db->where('transaction.customer_code', $customer);
                $this->db->or_where('transaction.customer_code', $supplier);
                $this->db->group_end();
            }
            if($dateStart != '') {
                $this->db->group_start();                
                $this->db->where("transaction.created_at >=", $dateStart);
                $this->db->where("transaction.created_at <=", $dateFinal);
                $this->db->group_end();                
            }
            $this->db->where('transaction.item_code', $item_code);
            $this->db->order_by($columnName, $columnSortOrder);
            $this->db->limit($rowperpage, $start);
            $records = $this->db->get("($raw_invoice_query UNION $raw_order_query) transaction", FALSE)->result();
            $data = array();
            
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
                    "shadow_selling_price" => $record->shadow_selling_price,
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
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);
            if ($search->value) {
            $where = "WHERE (
                        items.item_code LIKE '%$search->value%' OR 
                        items.item_name LIKE '%$search->value%'
                    ) AND items.is_active = 1";
            }else{
            $where = "WHERE items.is_active = 1";
            }
            $response = $this->db->query("
                WITH RECURSIVES AS (SELECT `id`
                      , `invoice_code` AS invoice
                      , `created_at`
                      , `updated_at`
                      , `invoice_code`
                      , `item_code`
                      , `item_name`
                      , SUM( IF(parent IS NULL, `item_quantity`, item_quantity*-1)) AS item_quantity
                      , `item_discount`
                      , `item_capital_price`
                      , `total_price`
                      , (total_price IS NOT TRUE) AS is_free
                      , `is_readable`
                      , `is_cancelled`
                  FROM fifo_items
                  WHERE is_cancelled = 0 AND is_readable = 1 AND item_quantity > 0
                  GROUP BY invoice, item_code, is_free
                  ORDER BY created_at ASC)

                  SELECT 
                        items.*
                      , RECURSIVES.invoice
                      , IFNULL(RECURSIVES.item_quantity,0) AS quantity
                      , IFNULL(RECURSIVES.item_capital_price,0) AS capital_price 
                  FROM items
                  LEFT JOIN RECURSIVES ON items.item_code = RECURSIVES.item_code
                  $where
                  -- if won't grouping, you can show all item, 
                  GROUP BY items.item_code
                  ORDER BY RECURSIVES.item_quantity DESC
                  LIMIT 15")->result();
            // $this->db->select('*, CAST(quantity AS INT) as quantity');
            // $this->db->limit(15);
            // if ($search->value) {
            //     $this->db->group_start();
            //     $this->db->like('item_code', $search->value, 'both');
            //     $this->db->or_like('item_name', $search->value, 'both');
            //     $this->db->or_like('brand', $search->value, 'after');
            //     $this->db->or_like('brands', $search->value, 'after');
            //     $this->db->or_like('note', $search->value, 'both');
            //     $this->db->group_end();
            // }
            // $this->db->where('is_active', 1);
            // $this->db->order_by('quantity', 'DESC');
            // $response = $this->db->get('items')->result();
            $this->db->trans_complete();
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        };
    }
}

/* End of file Items.php */
/* Location: ./application/controllers/Items.php */