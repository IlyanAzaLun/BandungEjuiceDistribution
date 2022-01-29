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
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */