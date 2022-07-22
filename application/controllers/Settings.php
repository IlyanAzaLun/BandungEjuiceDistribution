<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Settings';
		$this->page_data['page']->menu = 'settings';
	}

	public function index()
	{
		$this->general();
	}

	public function general()
	{
		ifPermissions('general_settings');
		$this->page_data['page']->submenu = 'general';
		$this->load->view('settings/general', $this->page_data);
	}

	public function generalUpdate()
	{

		ifPermissions('general_settings');

		postAllowed();
		
		$this->settings_model->updateByKey('date_format', post('date_format'));
		$this->settings_model->updateByKey('datetime_format', post('datetime_format'));
		$this->settings_model->updateByKey('google_recaptcha_enabled', post('google_recaptcha_enabled') == 'ok' ? 1 : 0 );
		$this->settings_model->updateByKey('google_recaptcha_sitekey', post('google_recaptcha_sitekey'));
		$this->settings_model->updateByKey('google_recaptcha_secretkey', post('google_recaptcha_secretkey'));
		$this->settings_model->updateByKey('timezone', post('timezone'));
		$this->settings_model->updateByKey('default_lang', post('default_lang'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Settings has been Updated Successfully');

		$this->activity_model->add("Company Settings Updated by User: #".logged('id'), $this->input->post());
		
		redirect('settings/general');
	}

	public function company()
	{
		ifPermissions('company_settings');
		$this->page_data['page']->submenu = 'company';
		$this->load->view('settings/company', $this->page_data);
	}

	public function companyUpdate()
	{
		ifPermissions('company_settings');
		postAllowed();
		$config = $this->Config;
		if (!empty($_FILES['company_icon']['name'])) {

			$path = $_FILES['company_icon']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$this->uploadlib->initialize([
				'file_name' => time().'.'.$ext
			]);
			$image = $this->uploadlib->uploadImage('company_icon', '/company');
			$gbr = $this->upload->data();
			//Compress Image
			$config['image_library']     ='gd2';
			$config['source_image']      ='assets/uploads/company/'.$gbr['file_name'];
			$config['create_thumb']      = FALSE;
			$config['maintain_ratio']    = FALSE;
			$config['quality']           = '50%';
			$config['width']             = 100;
			$config['height']            = 100;
			$config['new_image']         = 'assets/uploads/company/'.$gbr['file_name'];
			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
	  
			if($image['status']){
				unlink('assets/uploads/company/'.$this->settings_model->getValueByKey('company_icon'));
				$this->settings_model->updateByKey('company_icon', $this->upload->data('file_name'));
			}

		}
		
		$this->settings_model->updateByKey('company_name', post('company_name'));
		$this->settings_model->updateByKey('company_email', post('company_email'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Settings has been Updated Successfully');

		$this->activity_model->add("Company Settings Updated by User: #".logged('id'), $this->input->post());
		redirect('settings/company');
	}

	public function login_theme()
	{
		ifPermissions('login_theme');
		$this->page_data['page']->submenu = 'login_theme';
		$this->load->view('settings/login_theme', $this->page_data);
	}

	public function loginthemeUpdate()
	{

		ifPermissions('login_theme');

		postAllowed();
		
		$this->settings_model->updateByKey('login_theme', post('login_theme'));

		if (!empty($_FILES['image']['name'])) {

			$path = $_FILES['image']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$this->uploadlib->initialize([
				'file_name' => 'login-bg.'.$ext
			]);
			$image = $this->uploadlib->uploadImage('image');

			if($image['status']){
				$this->settings_model->updateByKey('bg_img_type', $ext);
			}

			$this->activity_model->add("User #$id Updated his/her Profile Image.", (array)$id);

			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Profile Image has been Updated Successfully');

		}
		else{

			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Server Error Occured while Uploading Image !');

		}

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Settings has been Updated Successfully');

		$this->activity_model->add("Login Theme Updated by User: #".logged('id'), $this->input->post());
		
		redirect('settings/login_theme');
	}

	public function email_templates()
	{
		ifPermissions('email_templates');
		$this->page_data['page']->submenu = 'email_templates';
		$this->load->view('settings/email_templates/list', $this->page_data);
	}

	public function edit_email_templates($id)
	{
		ifPermissions('email_templates');
		$this->page_data['page']->submenu = 'email_templates';
		$this->page_data['template'] = $this->templates_model->getById($id);
		$this->load->view('settings/email_templates/edit', $this->page_data);
	}

	public function update_email_templates($id)
	{

		ifPermissions('login_theme');

		postAllowed();

		$history = (array)$this->templates_model->getById($id);
		
		$this->templates_model->update($id, [
			// 'code'	=>	post('code'),
			'name'	=>	post('name'),
			'data'	=>	post('data'),
		]);

		// dd( post('data') );

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Email Template has been Updated Successfully');

		$this->activity_model->add("Email Template Updated by User: #".logged('id'), $history);
		
		redirect('settings/email_templates');
	}

}

/* End of file Settings.php */
/* Location: ./application/controllers/Settings.php */