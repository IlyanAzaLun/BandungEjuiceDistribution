<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipper extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->page_data['page']->title = 'Shipper';
		$this->page_data['page']->menu = 'Shipper';
	}

    public function index()
    {
        $this->list();
    }

	public function list()
	{
		ifPermissions('shipper_transaction_list');
		$this->page_data['title'] = 'shipping_list';
		$this->page_data['page']->submenu = 'list_shipper';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'invoice/order/is_confirmation',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	

	public function report()
	{
		ifPermissions('shipper_transaction_report');
		$this->page_data['title'] = 'shipping_report';
		$this->page_data['page']->submenu = 'report_shipper';
		$this->page_data['modals'] = (object) array(
			'id' => 'modal-confirmation-order',
			'title' => 'Modals confirmation',
			'link' => 'invoice/order/is_confirmation',
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('validation/shipper/list', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	public function destination()
	{	
		$customer = $this->customer_model->get_information_customer(get('id'));
		$data['customer'] = $customer;
	
		$this->load->library('pdf');
	
        $options = $this->pdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $this->pdf->setOptions($options);


		$this->pdf->setPaper('A5', 'landscape');
		$this->pdf->filename = "$customer->store_name.pdf";
		$this->pdf->load_view('validation/shipper/destination', $data);
		// $dompdf = new Dompdf\Dompdf();
		// $options = $dompdf->getOptions();
		// $options->setDefaultFont('Courier');
		// $html = 'helllo';
		// $dompdf->setPaper('A4', 'landscape');
        // $dompdf->loadHtml($html);
		// $dompdf->render();
        // $dompdf->stream("welcome.pdf", array('Attachment' => 0));
	}
	
}