<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
     
    }
   
   
       function getLastInserted() {
      $query =$this->db->select('id')->order_by('id','desc')->limit(1)->get('vendors')->row('id');

      return $query; 
       }
    


    	public function setdata($data){
        	$this->db->insert('vendors',$data);
        }
        
        public function notification($data){
        	$this->db->insert('notification',$data);
        }
        
        
        
         public function get_email5() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "5");
			 $this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}
        
        
        
        public function get_email() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "10");
			 $this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}
	public function get_email1() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "7");
			 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_admin_email(){
	                $this->db->select('*');
			$this->db->from('admin');
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
	 public function manager_data_single_by_category( $id){
         
         
                   $this->db->select('*');
                   $this->db->from('managers');
                   $this->db->where('category_id', $id);
                   $query =$this->db->get();
  
                   return $query->result();
      }
      public function get_email9() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "9");
			 $this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}
	public function get_manager_email($id){
	                $this->db->select('*');
			$this->db->from('managers');
			 
			$query = $this->db->get();
			return $query->result();
	
	}public function get_manager_email1($id){
	                $this->db->select('*');
			$this->db->from('managers');
			 
			$query = $this->db->get();
			return $query->result();
	
	}
	
        
}
 