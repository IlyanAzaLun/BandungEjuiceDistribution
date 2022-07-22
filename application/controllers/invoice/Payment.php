<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Payment';
		$this->page_data['page']->menu = 'payment';
	}

    public function cashflow()
    {
        //BANK CASH FLOW
    }
}