<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Quality_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
        }
    }

	
	public function update1($data,$id){
		
		$this->db->where('id', $id);
		$this->db->update('quality_form',$data);
                 
	}
	
	
	
	public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('categories', $data);
    	}
    	
    	public function status_update_subcat($id,$data){
           $this->db->where('parent_id', $id);
           $this->db->update('categories', $data);
    	}
    	
    	
	
	public function get_all_quality() {
			    
			$this->db->select('*,categories.title as ctitle,quality_form.id as qid,categories.id as cid');
			$this->db->from('quality_form');
			 $this->db->join('categories', 'categories.id = quality_form.c_id'); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	
	public function getall_comments($pid) {
			    
			$this->db->select('*,approved_quality.id as aqid,quality_form.id as qfid');
			$this->db->from('approved_quality');
   			$this->db->join('quality_form','quality_form.id=approved_quality.question_id');
			$this->db->where('product_id', $pid);
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_all_comments() {
			    
			$this->db->select('*,quality_form.id as qfid');
			$this->db->from('quality_form');
   			
			$this->db->where('c_id', $_SESSION['mcategory']);
			$query = $this->db->get();
			return $query->result();
	}

}
