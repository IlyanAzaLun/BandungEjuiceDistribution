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
		$this->db->where('item_code', $data);
		if ($field) {
			return $this->db->get($this->table)->row()->$field;
		} else {
			return $this->db->get($this->table)->row();
		}
	}
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */