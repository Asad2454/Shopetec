<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manager_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }
         
    	public function setdata($data){
             $this->db->insert('managers',$data);
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


      
      
        public function get_all_managers() {
			    
	$this->db->select('*');
	$this->db->from('managers');
	
        $query = $this->db->get();
        
        return $query->result_array();
	}
	
	
	
	public function get_products1($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*,categories.title as ctitle,categories.status as ccstatus,managers.status as mstatus,managers.id as mid');
		$this->db->from('managers');
		$this->db->join('categories','categories.id=managers.category_id');
		
		
	    	if(!empty($key)){
	    	
	  	  	$this->db->or_where('name', $key);
	   	 	$this->db->or_where('email', $key);
	   	 	$this->db->or_where('address', $key);
	   	 	$this->db->or_where('phone', $key);
	   	 	
		}
		    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
	    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
	   }
	
	
	
	
	
	
	public function manager_data_single( $id){
         
         
                   $this->db->select('*');
                   $this->db->from('managers');
                   $this->db->where('id', $id);
                   $query =$this->db->get();
  
                   return $query->result();
      }
     
     
        public function update_data( $id, $data ){
         $this->db->where('id', $id);
         $this->db->update('managers', $data);
    
    }
    
    public function status_update($id,$data){
        $this->db->where('id', $id);
        $this->db->update('managers', $data);
    }
    
    public function single_category($id) {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}



}
