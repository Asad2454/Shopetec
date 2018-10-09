<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categories_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }
    
    public function get_vendor_categories1($id) {
			    
			$this->db->select('category_id');
			$this->db->from('vendors');
			$this->db->where('id',$id);
			$query = $this->db->get();
			foreach($query->result() as $key1);
			$cat = explode(',',$key1->category_id);
			$cat2 = array();
			foreach($cat as $key2){
			    array_push($cat2,rtrim($key2,"@"));
			}
			return $cat2;
	}
	
	public function update_category($data,$id) {
			    
		$this->db->where('id', $id);
		$this->db->update('categories',$data);

	}
	
		public function get_category_name($id) {
			    
			$this->db->select('title');
			$this->db->from('categories');
			$this->db->where('id',$id);
			$query = $this->db->get();
			return $query->result_array();
	}
	
	public function get_parent_category($id) {
			    
			$this->db->select('parent_id');
			$this->db->from('categories');
			$this->db->where('id',$id);
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_parent() {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where(array('status' => '1'));
			$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_products1($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*');
		$this->db->from('categories');
	    	if(!empty($key)){
	  	  	$this->db->or_where('title', $key);
	   	 	$this->db->or_where('description', $key);
	   	 	$this->db->where(array('parent_id' => '0'));
		}
		else{
			$this->db->where(array('parent_id' => '0'));
		}    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
	    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
	   }
	
	public function get_parent2() {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result_array();
	}
	public function get_cat() {
			    
			$this->db->select('c_id');
			$this->db->from('quality_form');
			$this->db->where(array('status' => '1'));
			$query = $this->db->get();
			return $query->result();
	}
	
	public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('categories', $data);
    	}
    	
    	public function status_update_subcat($id,$data){
           $this->db->where('id', $id);
           $this->db->update('categories', $data);
    	}
    	
    	function getRows(){
        $this->db->select('*');
        $this->db->from('categories');
        $query = $this->db->get();
        return $query->result_array();
      }	
	
	
	public function get_all_sub_categories() {
			    
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('parent_id!=', '0');
		$this->db->order_by('id','desc');
	        $query = $this->db->get();
	        return $query->result();
	}
	
	public function get_products2($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*');
		$this->db->from('categories');
	    	if(!empty($key)){
	  	  	$this->db->or_where('title', $key);
	   	 	$this->db->or_where('description', $key);
			$this->db->where('categories.parent_id!=', '0');
		}
		else{
			$this->db->where('categories.parent_id!=', '0');
		}    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
		foreach ($query->result() as $subcategories){
    			$return[$subcategories->id] = $subcategories;
    			$return[$subcategories->id]->parents = $this->get_subcategory123($subcategories->parent_id); 
   		}
	    	return ($query->num_rows() > 0)?$return:FALSE;
	   }
	   
	public function get_subcategory123($id) {
      		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where(array('id' => $id));
		$query = $this->db->get();
		return $query->result();
    	}
	
	public function get_all_categories() {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where('parent_id', '0');
			$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	public function single_category($id) {
			    
			$this->db->select('');
			$this->db->from('categories');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}

}
