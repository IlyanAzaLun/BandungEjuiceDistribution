<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_model extends MY_Model
{

    public $table = 'invoice_purchasing';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_invoice_purchasing_by_code($data)
    {
        $this->db->where('invoice_code', $data);
        return $this->db->get($this->table)->row();
    }

    public function get_information_invoice_by_code($data)
    {
        $this->db->select('
            payment.id,
            purchase.invoice_code,
            purchase.total_price,
            purchase.discounts,
            purchase.shipping_cost,
            purchase.is_shipping_cost,
            purchase.grand_total,
            payment.leftovers,
            payment.payup,
            purchase.status_payment,
            purchase.date_start,
            purchase.date_due,
            supplier.store_name,
            supplier.owner_name
        ');
        $this->db->join('supplier_information supplier', 'supplier.customer_code = purchase.supplier');
        $this->db->join('invoice_payment payment', 'payment.invoice_code = purchase.invoice_code');
        $this->db->where('purchase.invoice_code', $data['invoice_code']);
        return $this->db->get($this->table.' purchase')->last_row();
    }

    public function get_code_invoice_purchase()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random = substr(str_shuffle($permitted_chars), 0, 4);
        $this->db->trans_start();
        $now = date('ym');
        $this->db->like('invoice_code', "INV/PURCHASE/$now/", 'after');
        $this->db->order_by('id', 'DESC');
        $data = $this->db->get($this->table)->num_rows();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return die();
        }
        return sprintf("INV/PURCHASE/$now/$random/%06s", (int)$data + 1);
    }

    public function update_by_code($id, $data)
    {
        $this->db->where('invoice_code', $id);
        $this->db->update($this->table, $data);
        return $id;
    }

    public function purchase_consignment()
    {
        $this->db->where('is_consignment', 1);
        return $this->db->get($this->table)->result();
    }
}

/* End of file Purchase_model.php */
/* Location: ./application/models/Invoice/Purchase_model.php */