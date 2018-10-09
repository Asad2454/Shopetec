<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class States_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	public function update_state($data,$id) {
			    
		$this->db->where('id', $id);
		$this->db->update('states',$data);

	}
	
	public function get_parent() {
			    
			$this->db->select('*');
			$this->db->from('countries');
			$this->db->where(array('status' => '1'));
			
			 $this->db->order_by("country_title", "ASC");
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_products1($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*');
		$this->db->from('states');
	    	if(!empty($key)){
	  	  	$this->db->or_where('state_code', $key);
	   	 	$this->db->or_where('state_title', $key);
	   	 	//$this->db->where(array('parent_id' => '0'));
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
			$this->db->from('states');
			//$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("states_title", "ASC"); 
			$query = $this->db->get();
			return $query->result_array();
	}
	
	
	public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('states', $data);
    	}
    	
    	
    	
    	function getRows(){
        $this->db->select('*');
        $this->db->from('states');
        $query = $this->db->get();
        return $query->result_array();
      }	
	
	
	public function get_all_sub_categories() {
			    
		$this->db->select('*');
		$this->db->from('states');
		$this->db->where('country_id!=', '0');
		$this->db->order_by('id','desc');
	        $query = $this->db->get();
	        return $query->result();
	}
	
	public function get_products2($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*,country_title as ctitle,states.status as sstatus,countries.status as cstatus,states.id as sid,countries.id as cid');
		$this->db->from('states');
		$this->db->join('countries','countries.id=states.country_id');
	    	if(!empty($key)){
	  	  	$this->db->or_where('state_title', $key);
	   	 	$this->db->or_where('state_code', $key);
			
		}
		    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
		
	    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
	   }
	   
	public function get_subcategory123($id) {
      		$this->db->select('*');
		$this->db->from('states');
		$this->db->where(array('id' => $id));
		$query = $this->db->get();
		return $query->result();
    	}
	
	public function get_all_sub_countries() {
			    
			$this->db->select('*');
			$this->db->from('states');
			//$this->db->where('parent_id', '0');
			//$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	public function single_state($id) {
			    
			$this->db->select('');
			$this->db->from('states');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}

}
