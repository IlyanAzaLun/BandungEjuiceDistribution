<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activity_logs extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Activity Logs';
		$this->page_data['page']->menu = 'activity_logs';
	}

	public function index()
	{
		ifPermissions('activity_log_list');
		$ip = !empty(get('ip')) ? urldecode(get('ip')) : false;
		$user = !empty(get('user')) ? urldecode(get('user')) : false;

		$arg = [];

		if ($ip)
			$arg['ip_address'] = $ip;

		if ($user)
			$arg['user'] = $user;

		$this->page_data['activity_logs'] = $this->activity_model->getByWhere($arg, [
			'order' => ['id', 'desc']
		]);
		$this->page_data['filter_ip'] = $ip;
		$this->page_data['filter_user'] = $user;
		$this->load->view('activity_logs/list', $this->page_data);
	}

	public function view($id)
	{
		ifPermissions('activity_log_view');
		$this->page_data['activity'] = $this->activity_model->getById($id);
		$this->load->view('activity_logs/view', $this->page_data);
	}

	public function serverside_datatables_data_activity_logs()
	{
		ifPermissions('activity_log_list');
		$response = array();

		$postData = $this->input->post();

		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value
		$ip = !empty(post('ip')) ? urldecode(post('ip')) : false;
		$user = !empty(post('user')) ? urldecode(post('user')) : false;

		## Search 
		$arg = [];

		if ($ip)
			$arg['ip_address'] = $ip;

		if ($user)
			$arg['user'] = $user;

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$records = $this->db->get('activity_logs')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		if ($searchValue != '') {
			$this->db->like('title', $searchValue, 'both');
			$this->db->or_like('user', $searchValue, 'both');
			$this->db->or_like('details', $searchValue, 'both');
			$this->db->or_like('ip_address', $searchValue, 'both');
			$this->db->or_like('created_at', $searchValue, 'both');
			$this->db->or_like('updated_at', $searchValue, 'both');
		}
		$records = $this->db->get_where('activity_logs', $arg)->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('*');
		if ($searchValue != '') {
			$this->db->like('title', $searchValue, 'both');
			$this->db->or_like('user', $searchValue, 'both');
			$this->db->or_like('details', $searchValue, 'both');
			$this->db->or_like('ip_address', $searchValue, 'both');
			$this->db->or_like('created_at', $searchValue, 'both');
			$this->db->or_like('updated_at', $searchValue, 'both');
		}
		$this->db->order_by('id', 'desc');
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get_where('activity_logs', $arg)->result();

		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				"id" => $record->id,
				"ip_address" => $record->ip_address,
				"title" => $record->title,
				"details" => $record->details,
				"user" => $record->user,
				"updated_at" => date(setting('datetime_format'), strtotime($record->updated_at)),
				"created_at" => date(setting('datetime_format'), strtotime($record->created_at)),
			);
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
	}
}

/* End of file Activity_logs.php */
/* Location: ./application/controllers/Activity_logs.php */