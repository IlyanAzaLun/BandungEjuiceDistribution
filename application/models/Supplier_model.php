<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends MY_Model
{

    public $table = 'supplier_information';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_code_supplier()
    {
        $this->db->order_by('id', 'DESC');
        $data = $this->db->get('supplier_information')->row();
        return sprintf('SP-%06s', (int)$data->id + 1);
    }
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */