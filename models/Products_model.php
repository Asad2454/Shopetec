<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        
    }
	
	
	public function get_single_category_products($id) {
          	$this->db->select('*');
		$this->db->from('products');
		$this->db->where('status', '1');
		$this->db->where('category',$id);
		$this->db->order_by("id", "ASC"); 
		$query = $this->db->get();
		return $query->result();
    	}
    	
    	public function search_products($id) {
          	$this->db->select('*');
		$this->db->from('products');
		$this->db->where('status', '1');
		$this->db->like('title',$id);
		$this->db->order_by("id", "ASC"); 
		$query = $this->db->get();
		return $query->result();
    	}
    	
    	public function get_single_category_products1($id,$limit,$start,$sort,$filter,$price){
	    	if(!empty($filter)){
			$this->db->select('*,products.id as pid,attribute_product.id as aid');
			$this->db->from('products');
			$this->db->join('attribute_product','attribute_product.product_id = products.id');
		   	$this->db->where('products.status', '1');
		   	if(strpos($id, '|') > 0){
		   		$id = explode("|",$id);
				$this->db->like('products.title',$id[0]);
			}
			else{
				$this->db->where('category',$id);
			}
	  	  	$this->db->where('attribute_product.attribute_id', $filter);
	  	}
	  	else{
		  	$this->db->select('*,products.id as pid');
			$this->db->from('products');
		   	$this->db->where('status', '1');
			if(strpos($id, '|') > 0){
				$id = explode("|",$id);
				$this->db->like('products.title',$id[0]);
			}
			else{
				$this->db->where('category',$id);
			}
		}
	    	if(!empty($price)){
	    		$price = explode("|",$price);
	    		$this->db->where('price >',$price[0]);
	    		$this->db->where('price <',$price[1]);
	    	}
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	if(!empty($sort)){
	    		$sort = explode("-",$sort);
	  	    	$this->db->order_by($sort[0],$sort[1]);
	    	}
		$query = $this->db->get();
	    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
   	}
    	
    	public function get_single_product($id) {
          	$this->db->select('*');
		$this->db->from('products');
		$this->db->where('status', '1');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->result();
    	}
    	
    	public function get_related_product($id) {
          	$this->db->select('*');
		$this->db->from('products');
		$this->db->where('status', '1');
		$this->db->where('category',$id);
		$this->db->limit(4); 
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
    	
    public function get_product_attribute($id) {
    		
        $this->db->select('attribute_id');
		$this->db->from('attribute_product');
		$this->db->where('product_id',$id); 
		$query = $this->db->get();
		$a = objectToArray($query->result());
		foreach($a as $aa){
			$value .= str_replace(",","|",$aa['attribute_id'])."|";			
		}
		$value = rtrim($value,"|");
		$value = explode("|", $value);
		$this->db->select('attribute_group.title as gtitle,attribute_value.title as vtitle,attribute_value.id as vid,image');
		$this->db->from('attribute_value');
		$this->db->join('attribute_group','attribute_group.id = attribute_value.group_id');
		$this->db->where_in('attribute_value.id',$value);
		$query = $this->db->get();
		return $query->result();
		
    }
    	
    	public function get_groups($id) {
        	$this->db->select('parent_id');
		$this->db->from('categories');
		$this->db->where('id',$id); 
		$query = $this->db->get();
		$a = objectToArray($query->result());
		$this->db->select('id,title');
		$this->db->from('attribute_group');
		$this->db->where('category_id',$a[0]['parent_id']); 
		$query = $this->db->get();
		return $query->result();
    	}
    	
    	public function get_attributes($id) {
        	$this->db->select('title,image,id');
		$this->db->from('attribute_value');
		$this->db->where('group_id',$id);
		$query = $this->db->get();
		return $query->result();
    	}
    	
    	public function get_picture($id) {
        	$this->db->select('*');
		$this->db->from('product_images');
		$this->db->where('product_id',$id);
		$query = $this->db->get();
		return $query->result();
    	}
}