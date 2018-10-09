<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

     public function update_pass( $id, $data )
    {
         $this->db->set('password', $data);
         $this->db->where('id', $id);
         $this->db->update('admin');
    
    }



   
    
     
	public function get_email($id){
		
		
		$this->db->select('*');
		$this->db->from('email');
		$this->db->where('id', $id);
		$this->db->where('status', "1"); 
		$query = $this->db->get();
		return $query->result();
		
		
	}
	
	   
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */