<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subcategories_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        
    }
	
	
	public function get_parent_category($id) {          
		$this->db->select('title,image');
		$this->db->from('categories');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}
    
    	public function get_subcategories($id) {
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('parent_id', $id);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_single_category_products($id) {
          	$this->db->select('*');
		$this->db->from('products');
		$this->db->where('status', '1');
		$this->db->where('category',$id);
		$this->db->order_by("id", "ASC");
		$this->db->limit("8"); 
		$query = $this->db->get();
		return $query->result();
    	}
    	
    	public function get_single_picture($id) {
        	$this->db->select('*');
		$this->db->from('product_images');
		$this->db->where('product_id',$id);
		$this->db->limit(1); 
		$query = $this->db->get();
		return $query->result();
    	}
}