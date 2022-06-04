<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address_model extends MY_Model {

	public $table = 'address_information';

	public function __construct()
	{
		parent::__construct();
	}

	public function getByCustomerCode($data)
	{
		$this->db->where('customer_code', $data);
		return $this->db->get($this->table)->row();
	}

	public function updateByCustomerCode($id, $data)
	{
		$this->db->where('customer_code', $id);
		return $this->db->update($this->table, $data);
	}
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */