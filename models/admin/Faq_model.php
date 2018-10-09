<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faq_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
	$this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }
	public function get_faq() {
			    
			$this->db->select('*');
			$this->db->from('faqs');
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function single_faq($id) {
			    
			$this->db->select('*');
			$this->db->from('faqs');
			$this->db->where('id', $id); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function update_faq($id,$data) {
			    
		$this->db->where('id', $id);
		$this->db->update('faqs',$data);

	}
	
	public function status_update($id,$data){
		$this->db->where('id', $id);
	        $this->db->update('faqs', $data);
    	} 
	
	
	
}