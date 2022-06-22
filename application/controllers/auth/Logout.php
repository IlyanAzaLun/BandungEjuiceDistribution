<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if(!is_logged()){
			redirect('auth/login','refresh');
		}

	}

	public function index()
	{

		$data = (array)$this->users_model->getById(logged('id'));
		$this->activity_model->add("User: ".logged('name').' Logged Out', $data);

		$this->users_model->logout();

		redirect('auth/login','refresh');

	}

}

/* End of file Logout.php */
/* Location: ./application/controllers/Admin/Logout.php */