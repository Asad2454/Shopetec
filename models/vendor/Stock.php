<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
    }

	
	
	
	
	
	public function get_all_stocks() {
			    
			$this->db->select('*');
			$this->db->from('attribute_product');
			//$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_all_stock() {
			    
			$this->db->select('*');
			$this->db->from('attribute_product');
			$this->db->where('id!=', '0');
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function single_product($id) {
			    
			$this->db->select('*');
			$this->db->from('products');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}

}
