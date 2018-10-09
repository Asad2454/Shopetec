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
    
    
    public function get_all_stock() {
			    
			$this->db->select('*');
			$this->db->from('attribute_product');
			$this->db->where('id!=', '0');
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	

	public function get_all_stocks(){
		$id = $_SESSION['vid'];
	    	$this->db->select('*');
	    	$this->db->from('products');
	    	$this->db->where('user_id',$id);
	    	$this->db->join('vendors', 'vendors.id = products.user_id');
		$this->db->join('attribute_product', 'products.id = attribute_product.product_id');
	    	$query = $this->db->get();
	    	$result = $query->result();
	    	return $result;
    }
    
	
	
	 public function get_products1($key, $limit, $start,$sort_by,$sort_order){
    		$id = $_SESSION['vid'];
	    	$this->db->select('*,categories.title as ctitle,products.title as ptitle,categories.id as cid,products.id as pid,attribute_product.id as apid');
	    	$this->db->from('products');
		$this->db->join('attribute_product', 'products.id = attribute_product.product_id');
		$this->db->join('categories', 'categories.id = products.category');

	    	if(!empty($key)){
  	  	
  	  	$this->db->or_where('products.title', $key);
   	 	$this->db->or_where('products.price', $key);
	    	$this->db->where('user_id',$id);

	}
	else{
	    	$this->db->where('user_id',$id);
	}    	
    	if(!empty($limit) || !empty($start)){
    		$this->db->limit($limit, $start);
    	}
    	$this->db->order_by($sort_by,$sort_order);
	$query = $this->db->get();
    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
   }
	
	
	
	
	
	
	public function single_product($id) {
			    
			$this->db->select('products.id as productsid,products.title as productstitle,categories.title as catetitle');
			$this->db->from('products');
			$this->db->where('products.id', $id);
			$this->db->join('categories','categories.id = products.category ','left');
			$query = $this->db->get();
			return $query->result();
	}
	public function single_attribute($id) {
			
			$this->db->select('*');
			$this->db->from('attribute_value');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}
	
	public function single_attribute1($id) {
		$ids = explode(",",$id);			
		$this->db->select('*');
		$this->db->from('attribute_value');
		$this->db->where_in('id', $ids);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_quantity($id) {
			    
			$this->db->select('*');
			$this->db->from('attribute_product');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
			
	}
	 public function update_quantity( $id, $data )
	    {
	     $this->db->set('quantity', $data);
	         $this->db->where('id', $id);
	         $this->db->update('attribute_product');
	         
	         
	    
	    }
	

}
