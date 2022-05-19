<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->page_data['page']->title = 'Report Master Information';
        $this->page_data['page']->menu = 'report';
    }

    // Items Report
    public function items()
    {
        $this->page_data['title'] = 'items';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_item';
        $this->load->view('items/report', $this->page_data);
    }
    public function download_report_items()
    {
        ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Items");
        $data = $this->items_model->get();
        $i = 2;

        $sheet->setCellValue("A1", "item_code");
        $sheet->setCellValue("B1", "item_name");
        $sheet->setCellValue("C1", "category");
        $sheet->setCellValue("D1", "brand");
        $sheet->setCellValue("E1", "brands");
        $sheet->setCellValue("F1", "mg");
        $sheet->setCellValue("G1", "ml");
        $sheet->setCellValue("H1", "vg");
        $sheet->setCellValue("I1", "pg");
        $sheet->setCellValue("J1", "flavour");
        $sheet->setCellValue("K1", "unit");
        $sheet->setCellValue("L1", "quantity");
        $sheet->setCellValue("M1", "broken");
        $sheet->setCellValue("N1", "capital_price");
        $sheet->setCellValue("O1", "selling_price");
        $sheet->setCellValue("P1", "customs");
        $sheet->setCellValue("Q1", "note");
        $sheet->setCellValue("R1", "is_active");
        $sheet->setCellValue("S1", "created_at");
        $sheet->setCellValue("T1", "created_by");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->item_code);
            $sheet->setCellValue("B".$i, $value->item_name);
            $sheet->setCellValue("C".$i, $value->category);
            $sheet->setCellValue("D".$i, $value->brand);
            $sheet->setCellValue("E".$i, $value->brands);
            $sheet->setCellValue("F".$i, $value->mg);
            $sheet->setCellValue("G".$i, $value->ml);
            $sheet->setCellValue("H".$i, $value->vg);
            $sheet->setCellValue("I".$i, $value->pg);
            $sheet->setCellValue("J".$i, $value->flavour);
            $sheet->setCellValue("K".$i, $value->unit);
            $sheet->setCellValue("L".$i, $value->quantity);
            $sheet->setCellValue("M".$i, $value->broken);
            $sheet->setCellValue("N".$i, $value->capital_price);
            $sheet->setCellValue("O".$i, $value->selling_price);
            $sheet->setCellValue("P".$i, $value->customs);
            $sheet->setCellValue("Q".$i, $value->note);
            $sheet->setCellValue("R".$i, $value->is_active);
            $sheet->setCellValue("S".$i, $value->created_at);
            $sheet->setCellValue("T".$i, $value->created_by);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'items-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    // Customer Report    
    public function customer()
    {
        $this->page_data['title'] = 'customer';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_customer';
        $this->load->view('customer/report', $this->page_data);
    }
    public function download_report_customer()
    {
        ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Customer");
        $this->db->join('address_information', 'address_information.customer_code=customer_information.customer_code', 'left');
        $data = $this->db->get('customer_information')->result();
        $i = 2;

        $sheet->setCellValue("A1", "customer_code");
        $sheet->setCellValue("B1", "store_name");
        $sheet->setCellValue("C1", "owner_name");
        $sheet->setCellValue("D1", "customer_type");
        $sheet->setCellValue("E1", "is_active");
        $sheet->setCellValue("F1", "note");
        $sheet->setCellValue("G1", "created_at");
        $sheet->setCellValue("H1", "address");
        $sheet->setCellValue("I1", "village");
        $sheet->setCellValue("J1", "sub_district");
        $sheet->setCellValue("K1", "city");
        $sheet->setCellValue("L1", "province");
        $sheet->setCellValue("M1", "zip");
        $sheet->setCellValue("N1", "contact_phone");
        $sheet->setCellValue("O1", "contact_mail");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->customer_code);
            $sheet->setCellValue("B".$i, $value->store_name);
            $sheet->setCellValue("C".$i, $value->owner_name);
            $sheet->setCellValue("D".$i, $value->customer_type);
            $sheet->setCellValue("E".$i, $value->is_active);
            $sheet->setCellValue("F".$i, $value->note);
            $sheet->setCellValue("G".$i, $value->created_at);
            $sheet->setCellValue("H".$i, $value->address);
            $sheet->setCellValue("I".$i, $value->village);
            $sheet->setCellValue("J".$i, $value->sub_district);
            $sheet->setCellValue("K".$i, $value->city);
            $sheet->setCellValue("L".$i, $value->province);
            $sheet->setCellValue("M".$i, $value->zip);
            $sheet->setCellValue("N".$i, $value->contact_phone);
            $sheet->setCellValue("O".$i, $value->contact_mail);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'customer-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    // Report Supplier
    public function supplier()
    {
        $this->page_data['title'] = 'supplier';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_supplier';
        $this->load->view('supplier/report', $this->page_data);
    }
    public function download_report_supplier()
    {
        ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Supplier");
        $this->db->join('address_information', 'address_information.customer_code=supplier_information.customer_code', 'left');
        $data = $this->db->get('supplier_information')->result();
        $i = 2;

        $sheet->setCellValue("A1", "customer_code");
        $sheet->setCellValue("B1", "store_name");
        $sheet->setCellValue("C1", "owner_name");
        $sheet->setCellValue("D1", "supplier_type");
        $sheet->setCellValue("E1", "is_active");
        $sheet->setCellValue("F1", "note");
        $sheet->setCellValue("G1", "created_at");
        $sheet->setCellValue("H1", "address");
        $sheet->setCellValue("I1", "village");
        $sheet->setCellValue("J1", "sub_district");
        $sheet->setCellValue("K1", "city");
        $sheet->setCellValue("L1", "province");
        $sheet->setCellValue("M1", "zip");
        $sheet->setCellValue("N1", "contact_phone");
        $sheet->setCellValue("O1", "contact_mail");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->customer_code);
            $sheet->setCellValue("B".$i, $value->store_name);
            $sheet->setCellValue("C".$i, $value->owner_name);
            $sheet->setCellValue("D".$i, $value->supplier_type);
            $sheet->setCellValue("E".$i, $value->is_active);
            $sheet->setCellValue("F".$i, $value->note);
            $sheet->setCellValue("G".$i, $value->created_at);
            $sheet->setCellValue("H".$i, $value->address);
            $sheet->setCellValue("I".$i, $value->village);
            $sheet->setCellValue("J".$i, $value->sub_district);
            $sheet->setCellValue("K".$i, $value->city);
            $sheet->setCellValue("L".$i, $value->province);
            $sheet->setCellValue("M".$i, $value->zip);
            $sheet->setCellValue("N".$i, $value->contact_phone);
            $sheet->setCellValue("O".$i, $value->contact_mail);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'supplier-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    // Report Expedition
    public function expedition()
    {
        $this->page_data['title'] = 'expedition';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_expedition';
        $this->load->view('expedition/report', $this->page_data);
    }
    public function download_report_expedition()
    {
        ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Expedition");
        $data = $this->expedition_model->get();
        $i = 2;

        $sheet->setCellValue("A1", "expedition_name");
        $sheet->setCellValue("B1", "services_expedition");
        $sheet->setCellValue("C1", "note");
        $sheet->setCellValue("D1", "created_at");
        $sheet->setCellValue("E1", "created_by");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->expedition_name);
            $sheet->setCellValue("B".$i, $value->services_expedition);
            $sheet->setCellValue("C".$i, $value->note);
            $sheet->setCellValue("D".$i, $value->created_at);
            $sheet->setCellValue("E".$i, $value->created_by);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'expedition-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    // Report Bank
    public function bank()
    {
        $this->page_data['title'] = 'bank';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_bank';
        $this->load->view('account_bank/report', $this->page_data);
    }
    public function download_report_bank()
    {
        ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Account Bank");
        $data = $this->account_bank_model->get();
        $i = 2;

        $sheet->setCellValue("A1", "bank_name");
        $sheet->setCellValue("B1", "no_account");
        $sheet->setCellValue("C1", "own_by");
        $sheet->setCellValue("D1", "created_at");
        $sheet->setCellValue("E1", "created_by");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->name);
            $sheet->setCellValue("B".$i, $value->no_account);
            $sheet->setCellValue("C".$i, $value->own_by);
            $sheet->setCellValue("D".$i, $value->created_at);
            $sheet->setCellValue("E".$i, $value->created_by);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'account_bank-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }


    // Report Purchase
    public function purchase()
    {
        $this->page_data['title'] = 'purchase';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_purchase';
        $this->load->view('account_bank/report', $this->page_data);
    }    
    // Report Purchase
    public function purchase_return()
    {
        $this->page_data['title'] = 'purchase';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_purchase';
        $this->load->view('account_bank/report', $this->page_data);
    }
    // Report sale
    public function sale()
    {
        $this->page_data['title'] = 'sale';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_sale';
        $this->load->view('account_bank/report', $this->page_data);
    }
    // Report sale
    public function sale()
    {
        $this->page_data['title'] = 'sale';
        $this->page_data['page']->submenu = 'report_master';
        $this->page_data['page']->submenu_child = 'report_sale';
        $this->load->view('account_bank/report', $this->page_data);
    }
    public function download_report_transaction_items()
    {
        ifPermissions('download_file');
        // (C) CREATE A NEW SPREADSHEET + WORKSHEET
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Account Bank");
        $data = $this->account_bank_model->get();
        $i = 2;

        $sheet->setCellValue("A1", "bank_name");
        $sheet->setCellValue("B1", "no_account");
        $sheet->setCellValue("C1", "own_by");
        $sheet->setCellValue("D1", "created_at");
        $sheet->setCellValue("E1", "created_by");
        foreach ($data as $key => $value) {
            $sheet->setCellValue("A".$i, $value->name);
            $sheet->setCellValue("B".$i, $value->no_account);
            $sheet->setCellValue("C".$i, $value->own_by);
            $sheet->setCellValue("D".$i, $value->created_at);
            $sheet->setCellValue("E".$i, $value->created_by);
            $i++;
        }
        // (E) SAVE FILE
        $writer = new Xlsx($spreadsheet);
		$fileName = 'account_bank-'. date("Y-m-d-His") .'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

}