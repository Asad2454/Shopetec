<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cities_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

	
	public function update_city($data,$id) {
			    
		$this->db->where('id', $id);
		$this->db->update('cities',$data);

	}
	
	public function get_parent() {
			    
			$this->db->select('*');
			$this->db->from('states');
			$this->db->where(array('status' => '1'));
			
			 $this->db->order_by("state_title", "ASC");
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_products1($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*');
		$this->db->from('cities');
	    	if(!empty($key)){
	  	  	$this->db->or_where('city_code', $key);
	   	 	$this->db->or_where('city_title', $key);
	   	 	//$this->db->where(array('parent_id' => '0'));
		}
		  	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
	    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
	   }
	
	public function get_parent2() {
			    
			$this->db->select('*');
			$this->db->from('cities');
			//$this->db->where(array('parent_id' => '0'));
			$this->db->order_by("city_title", "ASC"); 
			$query = $this->db->get();
			return $query->result_array();
	}
	
	
	public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('cities', $data);
    	}
    	
    	
    	
    	function getRows(){
        $this->db->select('*');
        $this->db->from('cities');
        $query = $this->db->get();
        return $query->result_array();
      }	
	
	
	public function get_all_sub_categories() {
			    
		$this->db->select('*');
		$this->db->from('cities');
		$this->db->where('state_id!=', '0');
		$this->db->order_by('id','desc');
	        $query = $this->db->get();
	        return $query->result();
	}
	
	public function get_products2($key, $limit, $start, $sort_by, $sort_order){
	    	$this->db->select('*,city_title as ctitle,states.status as sstatus,cities.status as cstatus,states.id as sid,cities.id as cid');
		$this->db->from('cities');
		$this->db->join('states','states.id=cities.state_id','left');
	    	if(!empty($key)){
	  	  	$this->db->or_where('city_title', $key);
	   	 	$this->db->or_where('city_code', $key);
			
		}
		    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
		
	    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
	   }
	   
	public function get_subcategory123($id) {
      		$this->db->select('*');
		$this->db->from('cities');
		$this->db->where(array('id' => $id));
		$query = $this->db->get();
		return $query->result();
    	}
	
	public function get_all_sub_countries() {
			    
			$this->db->select('*');
			$this->db->from('cities');
			//$this->db->where('parent_id', '0');
			//$this->db->order_by("title", "ASC"); 
			$query = $this->db->get();
			return $query->result();
	}
	
	
	public function single_city($id) {
			    
			$this->db->select('*');
			$this->db->from('cities');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
	}

}
