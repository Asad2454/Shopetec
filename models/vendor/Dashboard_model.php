<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
    }
    
    public function get_status($id){
    
    	$this->db->select('*');
	$this->db->from('vendors');
	$this->db->where('id', $id);
	$query = $this->db->get();
	return $query->result();
    
    }


   
    
        
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */