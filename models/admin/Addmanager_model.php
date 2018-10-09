<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Addmanager_model extends CI_Model {

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


}
