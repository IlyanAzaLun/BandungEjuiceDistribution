<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends MY_Model {

	public $table = 'activity_logs';

	public function __construct()
	{
		parent::__construct();
	}

	public function add($message, $data, $user_id = 0, $ip_address = false)
	{
		
		$details = implode(', ', array_map(
			function ($value, $key) { return sprintf("%s='%s'", $key, $value); },
			$data,
			array_keys($data)
		));
		
		return $this->create([
			'title' => $message,
			'details' => $details,
			'user' => ($user_id==0) ? logged('id') : $user_id,
			'ip_address' => !empty($ip_address) ? $ip_address : ip_address()
		]);
	}

}

/* End of file Activity_model.php */
/* Location: ./application/models/Activity_model.php */