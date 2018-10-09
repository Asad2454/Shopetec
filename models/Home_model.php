<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        
    }
	
	
	public function get_categories_home() {
    	    $this->db->select('*');
			$this->db->from('categories');
			$this->db->where('status', '1');
			$this->db->where('parent_id', '0');
			$this->db->limit(1);
			$query = $this->db->get();
			return $query->result();
	}
    
    public function get_admin_email(){
	    $this->db->select('*');
	    $this->db->from('admin');
		$query = $this->db->get();
		return $query->result();
	
	}
    
    /*public function get_cart() {
	    $this->db->select('products.title as ptitle, products.price as pprice, cart.qty as cqty,images');
		$this->db->from('cart');
		$this->db->join('attribute_product','attribute_product.id = cart.product_id');
		$this->db->join('products','products.id = attribute_product.product_id');
		$this->db->join('product_images','product_images.product_id = products.id');
		$this->db->where('cart.user_id', md5(session_id()));
		$query = $this->db->get();
		return $query->result();
	} */
    
    public function get_category_name($id) {
          
	    $this->db->select('id,title');
		$this->db->from('categories');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->result();
			
		  
    }
    
    	public function get_subcategories($id) {
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('parent_id', $id);
		$query = $this->db->get();
		return $query->result();
	}
    
    
	public function get_categories_home1() {
          
		    $this->db->select('*');
			$this->db->from('categories');
			$this->db->where('status', '1');
			$this->db->where('parent_id', '0');
			$this->db->limit(2,1);
			$query = $this->db->get();
			
			return $query->result();
			
		  
    }
    
    public function get_categories_home3() {
          
		    $this->db->select('*');
			$this->db->from('categories');
			$this->db->where('status', '1');
			$this->db->where('parent_id', '0');
			
			$this->db->limit(5,1); 
			$query = $this->db->get();			
			return $query->result();
			
		  
    }
	
	public function get_categories_home2() {
          
		    $this->db->select('*');
			$this->db->from('categories');
			$this->db->where('status', '1');
			$this->db->where('parent_id', '0');
			$this->db->limit(3,3);
			$query = $this->db->get();
			
			return $query->result();
			
		  
    }
	
		
		
		
		public function get_banners($total,$offset) {
          
		    $this->db->select('*');
			$this->db->from('banners');
			$this->db->where(array('status' => '1', 'position' => '1'));
			$this->db->limit($total,$offset);
			$this->db->order_by("id", "ASC"); 
			$query = $this->db->get();
			return $query->result();
		  
    	}
		
		
		public function get_socialnetwork() {
            $id = 1;
		    $this->db->select('*');
			$this->db->from('social_media');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
		  
    	}
		
		
		public function get_contactus() {
          	    $this->db->select('*');
			$this->db->from('contactus');
			$this->db->where('status', '1');
			$query = $this->db->get();
			return $query->result();
		  
    	}
    	
    	public function get_aboutus() {
          	    $this->db->select('*');
			$this->db->from('aboutus');
			$this->db->where('status', '1');
			$query = $this->db->get();
			return $query->result();
		  
    	}
		
		
		public function get_faq() {
          	    $this->db->select('*');
			$this->db->from('faqs');
			$this->db->where('status', '1');
			$query = $this->db->get();
			return $query->result();
		  
    	}
		
		
		
		public function get_single_email($id) {
          
		    $this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
		  
    	}
		
			
		public function get_metas() {
            $id = 1;
		    $this->db->select('*');
			$this->db->from('seo');
			$this->db->where('id', $id);
			$query = $this->db->get();
			return $query->result();
		  
    	}
		
		
		
		

    
}
/* End of file Products_model.php */
/* Location: ./application/model/Products_model.php */