<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         
	   if (!$this->session->userdata('is_admin_login')) {
			redirect('admin/home');
		}
    }



	public function setdata($data){
		
		$this->db->insert('vendors',$data);
	
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



	public function get_all_vendors(){
	
		$this->db->select('*');
		$this->db->from('vendors');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_category_name($id) {
			    
			$this->db->select('category_id');
			$this->db->from('vendors');
			$this->db->where('id',$id);
			$query = $this->db->get();
			foreach($query->result() as $key1);
			
			$cat = explode(',',$key1->category_id);
			asort($cat);
			
			$this->db->select('id,title,status');
			$this->db->from('categories');
			$this->db->where_in('id',$cat);			
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
    	    return $result1;
	}
	
	
	
	public function get_products1($key, $limit, $start, $sort_by, $sort_order){
	    $vendor_ids = array();
	    $index = 0;
	    $index1 = 0;
	    $title1 = '';
		$this->db->select('*');
		$this->db->from('vendors');
	    if(!empty($key)){
    		$this->db->or_where('name', $key);
			$this->db->or_where('category_id', $key);
			$this->db->or_where('phone', $key);
	    }    	
    	if(!empty($limit) || !empty($start)){
    		$this->db->limit($limit, $start);
    	}
    	$this->db->order_by($sort_by,$sort_order);
	    
	    $query = $this->db->get();
	    foreach($query->result_array() as $cat){
	        
	        $cur_cat = explode(',',$cat['category_id']);
	        foreach($cur_cat as $wow){
	            if(strpos($wow, "@") != false || $wow == ''){
	                unset($cur_cat[$index]);
	            }
	            $index++;
	        }

    	   foreach($cur_cat as $wow1){
    	        $title = $this->categories_model->get_category_name($wow1);
    	        foreach($title as $wow2);
    	            $title1 = $wow2['title'].', '.$title1; 
	        }
	        
	        array_push($vendor_ids,$cat);
	        if($title1 != ""){
	            $vendor_ids[$index1]['category_id'] = rtrim($title1,", ");
	        }
	        else{
	            $vendor_ids[$index1]['category_id'] = "No Category Approved";
	        }
	        $index1++;
	        $title1 = '';
	        $index = 0;
	    }
	    	return ($query->num_rows() > 0)?$vendor_ids:FALSE;
    }
	
	public function vendor_data_single($id)
    {
         	$this->db->select('*');
  			$this->db->from('vendors');
  			$this->db->where('id', $id);
   			$query =$this->db->get();
  
  			return $query->result();
    
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
