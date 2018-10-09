<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Livechat_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	public function update_livechat($data,$id) {
			    
		$this->db->where('id', $id);
		$this->db->update('live_chat',$data);

	}
	
	
	
	
    	
    
	
	
	

	
	
	public function get_livechat() {
			    
			$this->db->select('*');
			$this->db->from('live_chat');
			
			$query = $this->db->get();
			return $query->result();
	}
	
	
	
}
