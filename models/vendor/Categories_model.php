<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categories_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
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
			return $query->result_array();
	}
	
	public function get_vendor_categories1() {
			    
			$this->db->select('category_id');
			$this->db->from('vendors');
			$this->db->where('id',$_SESSION['vid']);
			$query = $this->db->get();
			foreach($query->result() as $key1);
			$cat = explode(',',$key1->category_id);
			$cat2 = array();
			foreach($cat as $key2){
			    array_push($cat2,rtrim($key2,"@"));
			}
			return $cat2;
	}
	
	public function get_vendor_categories2() {
			    
			$this->db->select('category_id');
			$this->db->from('vendors');
			$this->db->where('id',$_SESSION['vid']);
			$query = $this->db->get();
			return $query->result();
	}
	
	public function get_vendor_categories($key, $limit, $start,$sort_by,$sort_order) {
			    
			$this->db->select('category_id');
			$this->db->from('vendors');
			$this->db->where('id',$_SESSION['vid']);
			$query = $this->db->get();
			foreach($query->result() as $key1);
			$cat = explode(',',$key1->category_id);
			
			asort($cat);
			
			$this->db->select('id,title,status');
			$this->db->from('categories');
			$this->db->where_in('id',$cat);			
			if(!empty($key)){
  	  	        $this->db->where('title', $key);
			}
	        if(!empty($limit) || !empty($start)){
    		    $this->db->limit($limit, $start);
    	    }
        	//$this->db->order_by($sort_by,$sort_order);
			$this->db->order_by('id','ASC');
			$query = $this->db->get();
			$result = $query->result_array();
			$index = array();
			foreach($cat as $key3){
			    $rest = substr($key3, -1);
			    if($rest == '@'){
			      array_push($index, "0");
			    }
			    else{
			      array_push($index, "1");
			    }
			}
			$i = 0;
			$result1 = array();
			foreach ($result as $row){
                $row['status'] = $index[$i];
                array_push($result1,$row);
                $i++;
            }
    	    return ($query->num_rows() > 0)?$result1:FALSE;
	}
	
	public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('categories', $data);
    	}
    	
    	public function status_update_subcat($id,$data){
           $this->db->where('parent_id', $id);
           $this->db->update('categories', $data);
    	}
    	
    	 function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('categories');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('title',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('title',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
      }	
	
	
	public function get_all_sub_categories() {
			    
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('parent_id!=', '0');
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
	
	public function get_all_categories22() {
	        $cat = explode(',',$_SESSION['category']);
	        $i = 0;
	        foreach ($cat as $cat1) {
	            $ser = strpos($cat1,"@");
                if($ser > 0){
                        unset($cat[$i]);
                }
                $i++;
            }
            
			$this->db->select('*');
			$this->db->from('categories');
			$this->db->where_in('id', $cat);
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
		$id = $_SESSION['category'];
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where_in('parent_id', $id);
	    $query = $this->db->get();
	    return $query->result_array();
	}

}
