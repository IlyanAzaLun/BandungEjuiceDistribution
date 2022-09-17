<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		ifPermissions('payment');
		$this->page_data['page']->title = 'payment';
		$this->page_data['page']->menu = 'Payment';
		$this->page_data['page']->submenu = 'indebtedness';
	}
    
    public function index()
    {
		$this->page_data['title'] = 'payment_indebtedness_list';
        $this->page_data['page']->submenu_child = 'indebtedness_list';
        $this->load->view('payment/purchase/list_payment', $this->page_data);
    }

	public function debt()
	{
        ifPermissions('total_debt_to');
		$this->page_data['title'] = 'payment_indebtedness';
		$this->page_data['page']->submenu_child = 'payment_indebtedness';
		$this->form_validation->set_rules('supplier_code', 'Customer Name', 'required|trim');
        if ($this->form_validation->run() == false) {
			$this->load->view('payment/purchase/debt', $this->page_data);
		}else{
			postAllowed();
			$this->page_data['data_post'] = $this->input->post();
			$this->session->set_flashdata('lolwut',$this->page_data['data_post']);
			$date = preg_split('/[-]/', $this->page_data['data_post']['min']);
			$this->page_data['data_post']['date'] = array(
				'date_start' => trim(str_replace('/', '-', $date[0])), 
				'date_end'	 => trim(str_replace('/', '-', $date[1]))
			);
			$this->page_data['data_list_debts'] = $this->indebtedness->select_invoice_by_customer_code($this->page_data['data_post']);
			$this->load->view('payment/purchase/debt_list', $this->page_data);
		}
	}

	public function history()
	{
		$this->page_data['title'] = 'history_payment_indebtedness';
		$this->page_data['page']->submenu_child = 'payment_indebtedness';
		$this->data['request_get'] = $this->input->get();
		$this->page_data['response_data'] = $this->indebtedness->fetch_history_payment_by_invoice_code($this->data['request_get']);
		$this->page_data['data_invoice'] = $this->purchase_model->get_information_invoice_by_code($this->data['request_get']);
		
		$this->page_data['modals'] = (object) array(
			'id' => 'remmove_pay',
			'title' => 'Remove Payment',
			'link' => "invoice/purchase/payment/delete",
			'content' => 'delete',
			'btn' => 'btn-primary',
			'submit' => 'Yes do it',
		);
		$this->load->view('payment/purchase/history_payment_debt', $this->page_data);
		$this->load->view('includes/modals', $this->page_data);
	}

	public function edit_debt()
	{
		$this->page_data['data_post'] = $this->input->post();
		if(@$this->page_data['data_post']['search']){
			$this->db->trans_start();
			$this->db->select('payment.*, bank.name, bank.no_account, bank.own_by');
			$this->db->join('bank_information bank', 'bank.id = payment.bank_id');
			$this->db->group_start();
			$this->db->where('payment.id', $this->page_data['data_post']['search']['value']);
			$this->db->where('payment.is_cancelled', null);
			$this->db->group_end();
			$response = $this->db->get('invoice_payment payment')->row();
			$this->db->trans_complete();

			$this->output->set_content_type('application/json')->set_output(json_encode($response));
		}
		elseif ($this->page_data['data_post']['id_payment']) {
			// SELECT invoice_payment
			$data = $this->indebtedness->getById($this->page_data['data_post']['id_payment']);
			// topay - topay_current
			$diffirence = setCurrency($this->page_data['data_post']['to_pay']) - setCurrency($this->page_data['data_post']['current_to_pay']);
			// update invoice_payment where invoice_code = params and date < params
			$this->db->trans_start();
			$this->db->where('invoice_code', $data->invoice_code);
			$this->db->where('created_at >=', $data->created_at);
			$result = $this->db->get('invoice_payment')->result();
			$this->db->trans_complete();
			echo '<pre>';
			foreach ($result as $key => $value) {
				// $result[$key]->payup = $value->payup + $diffirence;
				$result[$key]->leftovers = $value->leftovers - $diffirence;
				$result[$key]->updated_by = logged('id');
				$result[$key]->updated_at = date('Y-m-d H:i:s');
			}
			$response = $this->indebtedness->update_batch($result, 'id');
			echo '</pre>';
			if($response){
				$this->indebtedness->update($this->page_data['data_post']['id_payment'], [
					'payup' => setCurrency($this->page_data['data_post']['to_pay']), 
					'description' => strtoupper($this->page_data['data_post']['note']),
					'created_at' => date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',$this->input->post('created_at')))))
				]);
				$this->activity_model->add("Create Payment Invoice, #" . $this->page_data['data_post']['invoice_code'], true);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'New Payment Invoice Successfully');
				redirect('invoice/purchases/payment/history?invoice_code='.$this->page_data['data_post']['invoice_code']);
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'New Payment Invoice Failed');
				redirect('invoice/purchases/payment/history?invoice_code='.$this->page_data['data_post']['invoice_code']);
			}
		}
	}

	public function delete()
	{
		echo '<pre>';
		$response = $this->payment_model->getById(post('id'));
		$response->is_cancelled = 1;
		$response->cancel_note = post('note');
		$request = $this->payment_model->update($response->id, $response);
		$this->db->reset_query();
		echo '</pre>';
		if($request){
			$this->activity_model->add("Delete Payment Invoice, #" . $response->id, (array) $response);
			$this->session->set_flashdata('alert-type', 'success');
			$this->session->set_flashdata('alert', 'Delete Payment Invoice Successfully');
			redirect('invoice/purchases/payment/history?invoice_code='.$response->invoice_code);
		}else{
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Delete Payment Invoice Failed');
			redirect('invoice/purchases/payment/history?invoice_code='.$response->invoice_code);
		}
	}

	public function debt_to()
	{
		postAllowed();
		$this->page_data['requset_post'] = $this->input->post();
		if(!($this->page_data['requset_post']['id_payment'] && ((int) $this->page_data['requset_post']['to_pay'] > 0))){
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Faild, Worng information');
			redirect('invoice/purchases/payment/debt');
		}else{
			$next_request = false;
			$request = $this->payment_model->getById($this->page_data['requset_post']['id_payment']);
			$dataPost = $this->input->post();
			$request->leftovers = $request->leftovers - setCurrency($dataPost['to_pay']);
			$request->payup = setCurrency($dataPost['to_pay']);
			$request->description = strtoupper($dataPost['note']);
			$request->created_by = logged('id');
			$request->bank_id = $dataPost['bank_id'];
			$request->created_at = ($dataPost['created_at'] == true)?date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',$dataPost['created_at'])))):nice_date(date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
			// unset($request->created_at);

			//// FIND INVOICE NOT YET PAYID
			echo '<pre>';
			

			//// IF LEFTOVER IS LARGE THEN ZERO WILL BE STOP HERE
			if($request->leftovers > 0){
				unset($request->id);
				$response = $this->payment_model->create($request);
				$this->db->reset_query();
			}
			//// IF LEFTOVER IS SMALLER THEN ZERO WILL BE CONTINUE TO DECREASE THE LEFTOVER
			elseif($request->leftovers < 0){
				//// UPDATE INVOICE PURCHASE TO PAID
				$this->purchase_model->update_by_code($request->invoice_code, array('status_payment' => 1));
				$this->db->reset_query();
				//// WILL BE LOOPED UNTIL LARGE OR DIVINE ZERO
				$response = $this->loop($request, $dataPost);
			}
			//// IF LEFTOVER IS DIVINE ZERO WILL BE STOP HERE TO.
			else{
				unset($request->id);
				$response = $this->payment_model->create($request);
				$this->db->reset_query();
				//// UPDATE INVOICE PURCHASE TO PAID
				$this->purchase_model->update_by_code($request->invoice_code, array('status_payment' => 1));

			}
			echo '</pre>';
			if($response){
				$this->activity_model->add("Create Payment Invoice, #" . $this->data['invoice_code'], (array) $payment);
				$this->session->set_flashdata('alert-type', 'success');
				$this->session->set_flashdata('alert', 'New Payment Invoice Successfully');
				redirect('invoice/purchases/payment/history?invoice_code='.$this->page_data['requset_post']['invoice_code']);
			}else{
				$this->session->set_flashdata('alert-type', 'danger');
				$this->session->set_flashdata('alert', 'New Payment Invoice Failed');
				redirect('invoice/purchases/payment/history?invoice_code='.$this->page_data['requset_post']['invoice_code']);
			}
		}

	}
	private function loop($data, $post)
	{
		//// THIS IS PAYMENT WITH OVERPAID 
		unset($data->id);
		$this->payment_model->create($data);
		
		//// FIND INVOICE
		$this->db->where('supplier', $data->customer_code);
		$this->db->where('status_payment', 0);
		$temp = $this->db->get('invoice_purchasing')->row();
		$this->db->reset_query();
		//// FIND PAYMENT
		$this->db->where('invoice_code', $temp->invoice_code);
		$next_request = $this->db->get('invoice_payment')->last_row();
		$this->db->reset_query();
		$next_request->leftovers = $next_request->leftovers - abs($data->leftovers);
		$next_request->payup = abs($data->leftovers);
		$next_request->description = strtoupper($post['note']);
		$next_request->created_by = logged('id');
		$next_request->bank_id = $post['bank_id'];
		$next_request->created_at = ($post['created_at'] == true)?date("Y-m-d H:i:s",strtotime(trim(str_replace('/', '-',$post['created_at'])))):nice_date(date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
		
		//// IF LEFTOVER IS LARGE THEN ZERO WILL BE STOP HERE
		if($next_request->leftovers > 0){
			unset($next_request->id);
			$this->payment_model->create($next_request);
			$this->db->reset_query();
			return true;
		}
		//// IF LEFTOVER IS SMALLER THEN ZERO WILL BE CONTINUE TO DECREASE THE LEFTOVER
		elseif($next_request->leftovers < 0){
			$this->purchase_model->update_by_code($next_request->invoice_code, array('status_payment' => 1));
			$this->db->reset_query();
			$this->loop($next_request, $post);
		}
		//// IF LEFTOVER IS DIVINE ZERO WILL BE STOP HERE TO.
		else{
			unset($next_request->id);
			$this->payment_model->create($next_request);
			$this->db->reset_query();	
			$this->purchase_model->update_by_code($next_request->invoice_code, array('status_payment' => 1));
			return true;
		}
	}

	public function validation_currency_check($str)
	{
		if((int) $str > 0){
			return true;
		}else{
			$this->form_validation->set_message('validation_currency_check', 'The {field} field can not be null');
		}
	}

    public function serverside_datatables_data_payment_purchase()
    {
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
		$dateStart = $postData['startDate'];
		$dateFinal = $postData['finalDate'];

		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$this->db->where("payup !=", 0);
		$records = $this->db->get('invoice_payment')->result();
		$totalRecords = $records[0]->allcount;

		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('invoice_purchasing purchase', 'payment.invoice_code = purchase.invoice_code', 'left');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('payment.invoice_code', $searchValue, 'both');
			$this->db->or_like('payment.customer_code', $searchValue, 'both');
			$this->db->or_like('payment.description', $searchValue, 'both');
			$this->db->group_end();
		}
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("payment.created_at >=", $dateStart);
			$this->db->where("payment.created_at <=", $dateFinal);
			$this->db->group_end();
		}
		else{
			// select by years
			$this->db->like("payment.created_at", date("Y"), 'after');
		}
		// select indebtness
		$this->db->where("purchase.is_transaction", 1);
		// is have indebtness,
		// $this->db->where("payment.leftovers !=", 0);
		$this->db->where("payment.payup !=", 0);
		$records = $this->db->get('invoice_payment payment')->result();
		$totalRecordwithFilter = $records[0]->allcount;

		## Fetch records
		$this->db->select('
          payment.id
        , payment.invoice_code
        , payment.date_start
        , payment.date_due
        , payment.customer_code
        , payment.grand_total
        , SUM(payment.payup) AS payup
        , payment.leftovers
        , payment.status_payment
        , payment.payment_type
        , payment.bank_id
        , payment.is_cancelled
        , payment.cancel_note
        , payment.created_by
        , MAX(payment.created_at) as created_at
        , payment.updated_by
        , payment.updated_at
        , MAX(payment.description) AS description
		, supplier.store_name
		, supplier.owner_name
        , user_created.id as user_created_id
        , user_created.name as user_created_by
        , user_updated.id as user_updated_id
        , user_updated.name as user_updated_by
        , IF(payment.updated_at, payment.updated_at, payment.created_at) as payment_date_at
        ');
		if ($searchValue != '') {
			$this->db->group_start();
			$this->db->like('payment.invoice_code', $searchValue, 'both');
			$this->db->or_like('payment.customer_code', $searchValue, 'both');
			$this->db->or_like('payment.description', $searchValue, 'both');
			$this->db->or_like('supplier.store_name', $searchValue, 'both');
			$this->db->group_end();
		}
        $this->db->join('invoice_purchasing purchase', 'payment.invoice_code = purchase.invoice_code', 'left');
        $this->db->join('users user_created', 'user_created.id=payment.created_by', 'left');
        $this->db->join('users user_updated', 'user_updated.id=payment.updated_by', 'left');
        $this->db->join('supplier_information supplier', 'supplier.customer_code = payment.customer_code', 'left');
		if ($dateStart != '') {
			$this->db->group_start();
			$this->db->where("payment.created_at >=", $dateStart);
			$this->db->where("payment.created_at <=", $dateFinal);
			$this->db->group_end();
		}
		// select indebtness
		$this->db->where("purchase.is_transaction", 1);
		// is have indebtness,
		// $this->db->where("payment.leftovers !=", 0);
		$this->db->group_start();
		$this->db->where("payment.is_cancelled", null);
		$this->db->where("payment.payup !=", 0);
		$this->db->group_end();
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->group_by('payment.invoice_code');
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get('invoice_payment payment')->result();
		$data = array();

		foreach ($records as $record) {

			$data[] = array(
				"id"=> $record->id,
				"invoice_code"=> $record->invoice_code,
				"date_start"=> $record->date_start,
				"date_due"=> $record->date_due,
				"customer_code"=> $record->customer_code,
				"store_name"=> $record->store_name,
				"grand_total"=> $record->grand_total,
				"payup"=> $record->payup,
				"leftovers"=> $record->leftovers,
				"status_payment"=> $record->status_payment,
				"payment_type"=> lang($record->payment_type),
				"bank_id"=> $record->bank_id,
				"is_cancelled"=> $record->is_cancelled,
				"cancel_note"=> $record->cancel_note,
				"created_by"=> $record->created_by,
				"created_at"=> $record->created_at,
				"updated_by"=> $record->updated_by,
				"updated_at"=> $record->updated_at,
				"description"=> $record->description,
				"user_created_id"=> $record->user_created_id,
				"user_created_by"=> $record->user_created_by,
				"user_updated_id"=> $record->user_updated_id,
				"user_updated_by"=> $record->user_updated_by,
				"payment_date_at"=> $record->payment_date_at,
			);
		}

		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecords,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data,
		);
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}