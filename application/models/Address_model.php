<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address_model extends MY_Model {

	public $table = 'address_information';

	public function __construct()
	{
		parent::__construct();
	}

	public function getByCustomerCode($data)
	{
		$this->db->where('customer_code', $data);
		return $this->db->get($this->table)->row();
	}

	public function updateByCustomerCode($id, $data)
	{
		$this->db->where('customer_code', $id);
		return $this->db->update($this->table, $data);
	}
	
	public function full_information($data)
	{
		$this->db->select("
              address_information.`id`
            , address_information.`customer_code`
            , address_information.`address`
            , address_information.`village`
            , address_information.`sub_district`
            , address_information.`city`
            , address_information.`province`
            , address_information.`zip`
            , address_information.`contact_phone`
            , address_information.`contact_mail`
            , address_information.`created_at`
            , address_information.`created_by`
            , address_information.`updated_at`
            , address_information.`updated_by`
            , address_information.`note`
            , address_information.`is_active`
            , NVL2(customer_information.customer_code, 'CUSTOMER', 'SUPPLIER') AS type
            , IFNULL(customer_information.store_name,supplier_information.store_name) AS store_name
            , IFNULL(customer_information.owner_name,supplier_information.owner_name) AS owner_name");
        $this->db->join('customer_information', 'customer_information.customer_code = address_information.customer_code','left');
        $this->db->join('supplier_information', 'supplier_information.customer_code = address_information.customer_code','left');
        $this->db->from('address_information');
		$this->db->where('address_information.id', $data);
		return $this->db->get()->row();
	}
}

/* End of file Items_model.php */
/* Location: ./application/models/Items_model.php */