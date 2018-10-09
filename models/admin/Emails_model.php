<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Emails_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	
	
	
	public function get_all_emails() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_single_email($id) {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', $id); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	
	
	public function update_email($data,$id) {
			    
		$this->db->where('id', $id);
		$this->db->update('email',$data);

	}
	   public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('email', $data);
    } 
	
	
	
}