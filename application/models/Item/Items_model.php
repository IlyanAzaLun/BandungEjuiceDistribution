<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items_model extends MY_Model
{

	public $table = 'Items';

	public function __construct()
	{
		parent::__construct();
	}

	public function getByCodeItem($data, $field = false)
	{
		$this->db->trans_start();
		$this->db->where('item_code', $data);
		if ($field) {
			$data = $this->db->get($this->table)->row()->$field;
		} else {
			$data = $this->db->get($this->table)->row();
		}
		$this->db->trans_complete();
		return $data;
	}
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */