<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends MY_Model {

	public $table = 'customer_information';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_code_customer()
	{
		$this->db->order_by('id', 'DESC');
		$data = $this->db->get('customer_information')->row();
		return sprintf('PL-%06s',(int)$data->id + 1);
	}

	public function get_information_customer($code)
	{
		$this->db->where('customer.customer_code', $code);
		$this->db->join('address_information address', 'customer.customer_code=address.customer_code', 'left');
		$this->db->where('address.is_active', '1');
		return $this->db->get($this->table.' customer')->row();
	}

	public function updateByCustomerCode($code, $data)
	{
		$this->db->where('customer_code', $code);
		return $this->db->update($this->table, $data);
	}
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */