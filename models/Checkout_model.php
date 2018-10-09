<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checkout_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        
    }

    public function client_details() {
	    $this->db->select('*');
		$this->db->from('customer');
		$this->db->where('id', $_SESSION['c_id']);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_last_order() {
	    $this->db->select('order_id');
		$this->db->from('orders');
		$this->db->order_by('order_id','DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function deduct_stock($id,$minus) {
	    $this->db->select('quantity');
		$this->db->from('attribute_product');
		$this->db->where('id',$id);
		$query = $this->db->get();
		foreach($query->result_array() as $stock);
		$new_stock = $stock['quantity'] - $minus;
		$data = array('quantity' => $new_stock);
		$this->db->where('id',$id);
		$this->db->update('attribute_product',$data);
	}
	
	public function insert_order($data) {
        $this->db->insert('orders',$data);
	}
	
	public function insert_address($data) {
        $this->db->insert('shipping',$data);
	}
}
/* End of file Products_model.php */
/* Location: ./application/model/Products_model.php */