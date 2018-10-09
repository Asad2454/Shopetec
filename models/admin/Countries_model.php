<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Countries_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	public function update_countries($data,$id) {
			    
		$this->db->where('id', $id);
		$this->db->update('countries',$data);

	}
	
	public function get_countries() {
			    
			$this->db->select('*');
			$this->db->from('countries');
			$this->db->where(array('status' => '1'));
			 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_products1($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*');
		$this->db->from('countries');
	    	if(!empty($key)){
	  	  	$this->db->or_where('country_title', $key);
	   	 	$this->db->or_where('country_code', $key);
	   	 
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
			$this->db->from('countries');
			
			
			$query = $this->db->get();
			return $query->result_array();
	}
	
	
	public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('countries', $data);
    	}
    	
    	
    	
    	function getRows(){
        $this->db->select('*');
        $this->db->from('countries');
        $query = $this->db->get();
        return $query->result_array();
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
	
	
	
	public function single_country($id) {
			    
			$this->db->select('');
			$this->db->from('countries');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}

}
