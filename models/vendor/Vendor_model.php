<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
    }



    
    	
    	
    	
         public function get_all_vendors() {
			    
			$this->db->select('*');
			$this->db->from('vendors');
			//$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function vendor_data_single($id)
    {
         		$this->db->select('*');
  			$this->db->from('vendors');
  			$this->db->where('id', $id);
   			$query =$this->db->get();
  
  			return $query->result();
    
    }
        public function update_data($id,$data)
    {
         $this->db->where('id', $id);
         $this->db->update('vendors', $data);
    
    }
    
    
     public function update_pass( $id, $data )
    {
         $this->db->set('password', $data);
         $this->db->where('id', $id);
         $this->db->update('vendors');
    
    }
     public function get_vendoremail($id) {
			    
			$this->db->select('email');
			$this->db->from('vendors');
			
			$this->db->where('id', $id); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	 public function get_email3() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "5");
			$this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}
	 public function get_email33() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "11");
			$this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	 public function get_email4() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "7");
			 $this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}






}
