<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Banners_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	
	
	public function get_banners() {
			    
			$this->db->select('*');
			$this->db->from('banners');
			
			
			$this->db->order_by("position", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function update_banners($data,$id) {
		
			    
		$this->db->where('id', $id);
		$this->db->update('banners',$data);

	}
	 public function status_update($id,$data){
           $this->db->where('id', $id);
           $this->db->update('banners', $data);
    	}

	
	
	public function single_banner($id) {
			    
			$this->db->select('*');
			$this->db->from('banners');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}
	
	

}
