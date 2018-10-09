<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
	$this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }
	public function get_newsletter() {
			    
			$this->db->select('*');
			$this->db->from('newsletter');
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function status_update($id,$data){
		$this->db->where('id', $id);
	        $this->db->update('newsletter', $data);
    	} 
	
	
	
}