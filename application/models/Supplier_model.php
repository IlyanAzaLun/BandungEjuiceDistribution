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

	public function get_information_supplier($code)
	{
		$this->db->where('supplier.customer_code', $code);
		$this->db->join('address_information address', 'supplier.customer_code=address.customer_code', 'left');
		$this->db->where('address.is_active', '1');
		return $this->db->get($this->table.' supplier')->row();
	}
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */