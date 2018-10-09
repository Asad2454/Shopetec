<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
	$this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }
	public function get_pages() {
			    
			$this->db->select('*');
			$this->db->from('pages');
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function single_pages($id) {
			    
			$this->db->select('*');
			$this->db->from('pages');
			$this->db->where('id', $id); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function update_pages($id,$data) {
			    
		$this->db->where('id', $id);
		$this->db->update('pages',$data);

	}
	
	public function status_update($id,$data){
		$this->db->where('id', $id);
	        $this->db->update('pages', $data);
    	} 
	
	
	
}