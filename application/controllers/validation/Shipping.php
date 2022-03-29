<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipping extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Activity Logs';
		$this->page_data['page']->menu = 'activity_logs';
	}

    public function index()
    {
        # code...
    }
}