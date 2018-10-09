<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Quality_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	public function update1($data,$id){
		
		$this->db->where('id', $id);
		$this->db->update('quality_form',$data);
                 
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
	
	
	public function get_products1($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*,categories.title as ctitle,categories.status as cstatus,quality_form.status as qstatus,quality_form.id as qid,categories.id as cid');
		$this->db->group_by('c_id');// add group_by

			$this->db->from('quality_form');
			 $this->db->join('categories', 'categories.id = quality_form.c_id'); 
		
		
	    	if(!empty($key)){
	    	
	  	  	$this->db->or_where('categories.title', $key);
	   	 	
	   	 	//$this->db->where(array('parent_id' => '0'));
		}
		//else{
			//$this->db->where(array('parent_id' => '0'));
		//}    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
	    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
	   }
	   
	   public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('quality_form', $data);
    	}
	
	public function getall_questions($cid) {
			    
			$this->db->select('*');
			$this->db->from('quality_form');
			$this->db->where('c_id', $cid);
			$query = $this->db->get();
			return $query->result();
	}

}
