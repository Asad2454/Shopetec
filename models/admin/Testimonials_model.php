<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Testimonials_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	public function update_testimonails($data,$id) {
		$this->db->where('id', $id);
		$this->db->update('testimonials',$data);
	}
	
	public function get_all_testimonials() {
		$this->db->select('*');
		$this->db->from('testimonials');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function status_update($id,$data){
          	$this->db->where('id', $id);
          	$this->db->update('testimonials', $data);
    	}
	
	public function single_testimonial($id) {
		$this->db->select('');
		$this->db->from('testimonials');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result();
	}

}
