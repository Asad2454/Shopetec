<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }
    
    public function add_products_image($dataimg){
     $this->db->insert('product_images',$dataimg);
    }
    
    public function check_form($id){
        $this->db->select('*');
        $this->db->from('approved_quality');
        $this->db->where('product_id',$id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function add_products($data){
    	$this->db->insert('products',$data);
        return $this->db->insert_id();
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
    
    public function add_product_attribute($data){
    	$this->db->insert('attribute_product',$data);
    }

    public function get_products(){
    	$this->db->select('*,vendors.name as vname,products.id as pid,products.status as pstatus');
    	$this->db->from('products');
    	$this->db->join('vendors', 'vendors.id = products.user_id','left');
    	$query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_products1($key, $limit, $start,$sort_by,$sort_order){
    	$this->db->select('*,vendors.name as vname,products.id as pid,products.status as pstatus');
    	$this->db->from('products');
    	$this->db->join('vendors', 'vendors.id = products.user_id','left');
    	if(!empty($key)){
  	  	
  	  	$this->db->or_where('products.title', $key);
   	 	$this->db->or_where('vendors.name', $key);
   	 	$this->db->or_where('products.price', $key);
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
    
      public function request_sample($productid,$data){
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
        $this->db->select('*,categories.title as ctitle,attribute_group.title as atitle,categories.id as cid,attribute_group.id as aid');
        $this->db->from('attribute_group');
        $this->db->join('categories', 'categories.id = attribute_group.category_id');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_products2($key, $limit, $start,$sort_by,$sort_order){
    	$this->db->select('*,categories.title as ctitle,attribute_group.title as atitle,categories.id as cid, attribute_group.id as aid, attribute_group.status as astatus');
        $this->db->from('attribute_group');
        $this->db->join('categories', 'categories.id = attribute_group.category_id');
        if(!empty($key)){
  	  	
  	  	$this->db->or_where('categories.title', $key);
   	 	$this->db->or_where('attribute_group.title', $key);
	}    	
    	if(!empty($limit) || !empty($start)){
    		$this->db->limit($limit, $start);
    	}
    	$this->db->order_by($sort_by,$sort_order);
	$query = $this->db->get();
    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
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
        return $query->result_array();
    }
    
    public function get_products3($key, $limit, $start,$sort_by,$sort_order){
    	$this->db->select('attribute_value.id as valueid,attribute_value.group_id as valuegroupid,attribute_value.title as valuetitle,attribute_value.image as valueimg,attribute_value.status as valuestatus,attribute_group.title as grouptitle,attribute_group.id as groupid,categories.id as categoriesid,categories.title as categoriestitle');
        $this->db->from('attribute_value');
        $this->db->join('attribute_group', 'attribute_value.group_id = attribute_group.id');
	$this->db->join('categories', 'categories.id = attribute_group.category_id');
       if(!empty($key)){
  	  	$this->db->or_where('attribute_value.title', $key);
   	 	$this->db->or_where('attribute_group.title', $key);
   	 	$this->db->or_where('categories.title', $key);
	}    	
    	if(!empty($limit) || !empty($start)){
    		$this->db->limit($limit, $start);
    	}
    	$this->db->order_by($sort_by,$sort_order);
	$query = $this->db->get();
    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
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
        $this->db->where('status','1');
        $this->db->where('group_id',$id);
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
}
/* End of file Products_model.php */
/* Location: ./application/model/Products_model.php */