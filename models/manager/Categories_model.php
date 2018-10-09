<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categories_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
        }
    }

	
	public function update_category($data,$id) {
			    
		$this->db->where('id', $id);
		$this->db->update('categories',$data);

	}
	
	public function get_parent() {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where(array('status' => '1'));
			$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_parent2() {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where(array('status' => '1'));
			$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result_array();
	}
	public function get_cat() {
			    
			$this->db->select('c_id');
			$this->db->from('quality_form');
			$this->db->where(array('status' => '1'));
			$query = $this->db->get();
			return $query->result();
	}
	
	public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('categories', $data);
    	}
    	
    	public function status_update_subcat($id,$data){
           $this->db->where('id', $id);
           $this->db->update('categories', $data);
    	}
    	
    	function getRows(){
        $this->db->select('*');
        $this->db->from('categories');
        $query = $this->db->get();
        return $query->result_array();
      }	
	
	
	public function get_all_sub_categories() {
			    
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('parent_id!=', '0');
		$this->db->order_by('id','desc');
	        $query = $this->db->get();
	        return $query->result();
	}
	
	public function get_all_categories() {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where('parent_id', '0');
			$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	public function single_category($id) {
			    
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_single_sub_categories() {
		$id = $_SESSION['mcategory'];
		
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('parent_id', $id);
	        $query = $this->db->get();
	        return $query->result_array();
	}

}
