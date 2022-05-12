<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items_fifo_model extends MY_Model
{

    public $table = 'fifo_items';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_items_fifo($data)
    {
        $this->db->where('invoice_code', $data);
        $this->db->where('is_cancelled', 0);
        return $this->db->get($this->table)->result();
    }

    public function select_fifo_by_item_code($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        // // UPDATE INVOICE PURCHASE
        $result = $this->db->query(
            "WITH RECURSIVES AS (SELECT 
                    `id`
                    , SUBSTRING(`invoice_code`, 5) AS invoice
                    , `created_at`
                    , `updated_at`
                    , `invoice_code`
                    , `item_code`
                    , `item_name`
                    , SUM( IF(parent IS NULL, `item_quantity`, item_quantity*-1)) AS item_quantity
                    , `item_discount`
                    , `total_price`
                    , (total_price IS NOT TRUE) AS is_free
                    , `is_cancelled`
            FROM fifo_items
            WHERE item_code = '$data' AND is_cancelled = 0
            GROUP BY invoice, is_free)
            SELECT * FROM RECURSIVES
            WHERE RECURSIVES.item_quantity > 0")->row();
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return FALSE;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            return $result;
        }
    }

    public function update_fifo_by_item_code($data, $request = false)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        // UPDATE INVOICE PURCHASE
        if ($request) {
            $this->db->set('item_quantity', 'item_quantity+1', false);
        }
        else{
            $this->db->set('item_quantity', 'item_quantity-1', false);
        }
        $this->db->where('id', $data->id);
        $this->db->update($this->table);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return FALSE;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function update_by_code($id, $data)
    {
        $this->db->where('invoice_code', $id);
        $this->db->update($this->table, $data);
        return $id;
    }
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */