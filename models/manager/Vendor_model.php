<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('manager/Categories_model');
       
    }



    	public function setdata($data){
        	$this->db->insert('vendors',$data);
        }
        
         public function get_all_vendors(){
			    
		$this->db->select('*');
		$this->db->from('vendors');
		
	        $query = $this->db->get();
	        
	        return $query->result_array();
	}
	
	public function notification($data){
        	$this->db->insert('notification',$data);
    }
    
    
    public function get_products1($key, $limit, $start, $sort_by, $sort_order){
		$vendor_ids = array();
		$this->db->select('id,category_id');
		$this->db->from('vendors');
	    $query = $this->db->get();
	    foreach($query->result_array() as $cat){
	        $cur_cat = explode(',',$cat['category_id']);
	        if(in_array($_SESSION['mcategory'], $cur_cat) != '' || in_array($_SESSION['mcategory'].'@', $cur_cat) != ''){
	            array_push($vendor_ids,$cat['id']);
	        }
	    }
	    $this->db->select('*');
		$this->db->from('vendors');
		if(!empty($key)){
	    	$this->db->or_where('name', $key);
	   	 	$this->db->or_where('email', $key);
	   	 	$this->db->or_where('phone', $key);
	   	 	$this->db->where_in('id',$vendor_ids);
		}
		else{
	   	 	$this->db->where_in('id',$vendor_ids);
		}    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query1 = $this->db->get();
		$result = array();
		$index = 0;
		
	    foreach($query1->result_array() as $cat1){
	        $cur_cat1 = explode(',',$cat1['category_id']);
	        
	        if(in_array($_SESSION['mcategory'], $cur_cat1) != ''){
	            array_push($result,$cat1);
	            $result[$index]['category_id'] = $_SESSION['mcategory'];
	        }
	        else if(in_array($_SESSION['mcategory'].'@', $cur_cat1) != ''){
	            array_push($result,$cat1);
	            $result[$index]['category_id'] = $_SESSION['mcategory'].'@';

	        }
	        $index++;
	    }
	    return ($query1->num_rows() > 0)?$result:FALSE;
	   }
	
    
    
	
	public function get_products2($key, $limit, $start, $sort_by, $sort_order,$ids){
		$id = $_SESSION['mcategory'];
    	array_push($ids,$id);
	    $this->db->select('*,categories.title as ctitle,categories.status as ccstatus,vendors.id as cid,vendors.status as cstatus');
		$this->db->from('vendors');
		$this->db->join('categories','categories.id=vendors.category_id');
	    if(!empty($key)){
	    	$this->db->or_where('name', $key);
	   	 	$this->db->or_where('email', $key);
	   	 	$this->db->or_where('address', $key);
	   	 	$this->db->or_where('phone', $key);
	   	 	$this->db->where_in('category_id',$ids);
		}
		else{
	   	 	$this->db->where_in('category_id',$ids);
		}    	
	    	if(!empty($limit) || !empty($start)){
	    		$this->db->limit($limit, $start);
	    	}
	    	$this->db->order_by($sort_by,$sort_order);
		
		$query = $this->db->get();
	    return ($query->num_rows() > 0)?$query->result_array():FALSE;
	   }
	
	
	
	
	
	
	
	public function vendor_data_single($id)
    {
         		$this->db->select('*');
  			$this->db->from('vendors');
  			$this->db->where('id', $id);
   			$query =$this->db->get();
  
  			return $query->result();
    
    }
    
    
    public function update_category_status($id,$status,$statusid){
        $this->db->select('category_id');
  		$this->db->from('vendors');
  		$this->db->where('id', $id);
   		$query = $this->db->get();
        foreach($query->result() as $key);
        $newstatus = str_replace($statusid,$status,$key->category_id);
        $data = array('category_id' => $newstatus);
        $this->db->where('id',$id);
        $this->db->update('vendors',$data);
    }
    
    
        public function update_data( $id, $data )
    {

         $this->db->where('id', $id);
         $this->db->update('vendors', $data);
    
    }
    
    public function get_vendoremail($id){
         		$this->db->select('email');
  			$this->db->from('vendors');
  			$this->db->where('id', $id);
   			$query =$this->db->get();
  
  			return $query->result();
    }

    
    public function status_update($id,$data){
   
        $this->db->where('id', $id);
        $this->db->update('vendors', $data);
    }





}
