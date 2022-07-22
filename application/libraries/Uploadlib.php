<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadlib
{
	protected $ci;

	public $Config;

	public function __construct()
	{
        $this->ci =& get_instance();

        $config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['overwrite'] = true;
		$config['remove_spaces'] = TRUE;

		$this->Config = $config;
		$this->ci->load->library('upload', $config);

	}

	public function initialize($config = [])
	{

		$this->Config = array_merge($this->Config, $config);
		return $this->ci->upload->initialize($this->Config);

	}

	public function uploadImage($name = 'image', $path = '/')
	{

		$config = $this->Config;
		$config['upload_path'] = $config['upload_path'].'/'.trim($path, '/');
		$this->ci->upload->initialize($config);

		if ( ! $this->ci->upload->do_upload($name)){
			$return = array( 'status' => false, 'error' => $this->ci->upload->display_errors());
		}
		else{
			$return = array( 'status' => true, 'data' => $this->ci->upload->data());
		}

		return $return;
	
	}

	public function uploadFile()
	{
		$extension = pathinfo(
			$_FILES['file']['name'],
			PATHINFO_EXTENSION
		);

		if ($extension == 'csv') {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv(); //not problem
		} elseif ($extension == 'xlsx') {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		}
		$spreadsheet    = $reader->load($_FILES['file']['tmp_name']);
		$allDataInSheet = $spreadsheet
			->getActiveSheet()
			->toArray(null, true, true, true);

		return $allDataInSheet;
	}

	// public function multiUploadImage($name = 'image', $path = '/')
	// {

	// 	$config = $this->Config;
	// 	$config['upload_path'] = $config['upload_path'].'/'.trim($path, '/');
	// 	$this->ci->upload->initialize($config);

	// 	if ( ! $this->ci->upload->do_upload($name)){
	// 		$return = array( 'status' => false, 'error' => $this->ci->upload->display_errors());
	// 	}
	// 	else{
	// 		$return = array( 'status' => true, 'data' => $this->ci->upload->data());
	// 	}

	// 	return $return;
	
	// }

}

/* End of file Uploadlib.php */
/* Location: ./application/libraries/Uploadlib.php */
