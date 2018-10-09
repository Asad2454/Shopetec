<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
    }
    
   function getLastInserted() {
      $query =$this->db->select('id')->order_by('id','desc')->limit(1)->get('products')->row('id');

      return $query; 
       }
    
    public function add_products_image($dataimg){
     $this->db->insert('product_images',$dataimg);
    }
    
    public function sample_delivered($productid,$data){
    	//print_r($data);
        $this->db->where('id', $productid);
        $this->db->update('products',$data);
        
        
        
    }
    
     public function add_vendor_product($data){
     $this->db->insert('vendor_products',$data);
    }
    
    public function add_products($data){
    	$this->db->insert('products',$data);
        return $this->db->insert_id();
    }
    
    public function add_product_attribute($data){
    	$this->db->insert('attribute_product',$data);
    }

    public function get_products($id){
    	$this->db->select('*,products.status as pstatus, products.id as pid');
    	$this->db->from('products');
    	$this->db->where('user_id',$id);
	$this->db->join('vendors', 'vendors.id = products.user_id');
    	$query = $this->db->get();
    	$result = $query->result();
    	return $result;
    }
    
    public function check_seo_url($value){
    	$this->db->select('*');
    	$this->db->from('products');
    	$this->db->like('seo_url',$value);
    	$this->db->order_by('seo_url','DESC');
	$query = $this->db->get();
    	$result = $query->result();
    	return $result;
    }
    
     public function get_products1($key, $limit, $start,$sort_by,$sort_order,$id){
    	$this->db->select('*,products.status as pstatus, products.id as pid');
    	$this->db->from('products');
	$this->db->join('vendors', 'vendors.id = products.user_id');
    	if(!empty($key)){
  	  	
  	  	$this->db->or_where('products.title', $key);
   	 	$this->db->or_where('products.price', $key);
    		$this->db->where('user_id',$id);

	}
	else{
    		$this->db->where('user_id',$id);
	}    	
    	if(!empty($limit) || !empty($start)){
    		$this->db->limit($limit, $start);
    	}
    	$this->db->order_by($sort_by,$sort_order);
	$query = $this->db->get();
    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
   }
    
    public function attribute_products($id){
    	$this->db->select('*');
    	$this->db->from('attribute_product');
    	$this->db->where('product_id',$id);
    	$query = $this->db->get();
    	$result = $query->result();
    	return $result;
    }
    
     public function get_productsbyid($id){
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('products.id',$id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function productsimgbyid($id){
        $this->db->select('*');
        $this->db->from('product_images');
        $this->db->where('product_id',$id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function update_products($productid,$data){
        $this->db->where('id',$productid);
        $this->db->update('products',$data);
    }

    public function update_products_image($productid,$dataimg){
        $this->db->where('product_id',$productid);
        $this->db->update('product_images',$dataimg);
    }

    public function status_update_products($id,$data){
        $this->db->where('id', $id);
        $this->db->update('products', $data);
    }

    public function add_attribute_group($data){
        $this->db->insert('attribute_group',$data);
    }

    public function get_attribute_group(){
        $this->db->select('*');
        $this->db->from('attribute_group');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function get_attribute_groupbyid($id){
        $this->db->select('*');
        $this->db->from('attribute_group');
        $this->db->where('id',$id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function update_attribute_group($id,$data){
        $this->db->where('id', $id);
        $this->db->update('attribute_group',$data);
    }
    
    public function status_update_attrigroups($id,$data){
        $this->db->where('id', $id);
        $this->db->update('attribute_group', $data);
    }

    public function add_attribute_value($data){
        $this->db->insert('attribute_value',$data);
    }

    public function get_attribute_value(){
        $this->db->select('attribute_value.id as valueid,attribute_value.group_id as valuegroupid,attribute_value.title as valuetitle,attribute_value.image as valueimg,attribute_value.status as valuestatus,attribute_group.title as grouptitle,attribute_group.id as groupid,categories.id as categoriesid,categories.title as categoriestitle');
        $this->db->from('attribute_value');
        $this->db->join('attribute_group', 'attribute_value.group_id = attribute_group.id');
	$this->db->join('categories', 'categories.id = attribute_group.category_id');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function get_attribute_valuebyid($id){
        $this->db->select('attribute_value.id as valueid,attribute_value.group_id as valuegroupid,attribute_value.title as valuetitle,attribute_value.image as valueimg,attribute_value.status as valuestatus,attribute_group.title as grouptitle,attribute_group.id as groupid');
        $this->db->from('attribute_value');
        $this->db->where('attribute_value.id',$id);
        $this->db->join('attribute_group', 'attribute_value.group_id = attribute_group.id');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function get_groupcategoryid($id){
    	$this->db->select('*');
        $this->db->from('attribute_group');
        $this->db->where('status','1');
        $this->db->where('category_id',$id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function get_valuesbygroupid($id){
    	$this->db->select('*');
        $this->db->from('attribute_value');
        $this->db->where('group_id',$id);
        $this->db->where('status','1');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    

    public function update_attribute_value($id,$finaldata){
        $this->db->where('id', $id);
        $this->db->update('attribute_value',$finaldata);
    }
    
     public function update_product_attribute($id,$data){
     
        $this->db->where('id', $id);
        $this->db->update('attribute_product',$data);
    }
    
    
    public function status_update_attrivalues($id,$data){
        $this->db->where('id', $id);
        $this->db->update('attribute_value', $data);
    }
    
    public function getall_comments($pid) {
			    
			$this->db->select('*');
			$this->db->from('approved_quality');
   			$this->db->join('quality_form','quality_form.id=approved_quality.question_id');
			$this->db->where('product_id', $pid);
			$query = $this->db->get();
			return $query->result();
	}
}
/* End of file Products_model.php */
/* Location: ./application/model/Products_model.php */