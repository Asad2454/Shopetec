<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manager_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
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
			//$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	public function manager_data_single( $id){
         
         
                   $this->db->select('*');
                   $this->db->from('managers');
                   $this->db->where('id', $id);
                   $query =$this->db->get();
  
                   return $query->result();
      }
     public function manager_data_single_by_category( $id){
         
         
                   $this->db->select('*');
                   $this->db->from('managers');
                   $this->db->where('category_id', $id);
                   $query =$this->db->get();
  
                   return $query->result();
      }
     
        public function update_data( $id, $data ){
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


     public function update_pass( $id, $data )
    {
         $this->db->set('password', $data);
         $this->db->where('id', $id);
         $this->db->update('managers');
    
    }



}
