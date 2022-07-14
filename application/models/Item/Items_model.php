<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items_model extends MY_Model
{

	public $table = 'items';

	public function __construct()
	{
		parent::__construct();
	}

	public function getByCodeItem($data, $field = false)
	{
		$this->db->trans_start();
		$this->db->where('item_code', $data);
		if ($field) {
			$resp = $this->db->get($this->table)->row_array();
			$response = $resp[$field];
		} else {
			$response = $this->db->get($this->table)->row();
		}
		$this->db->trans_complete();
		return $response;
	}
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */