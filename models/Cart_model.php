<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cart_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        
    }
	
	public function add_to_cart_search($id,$attr,$qty) {
	    $search = "attribute_id IN (".$attr.")";
	    $this->db->select('*');
		$this->db->from('attribute_product');
		$this->db->where('status', '1');
		$this->db->where('product_id', $id);
		$this->db->where($search);
		$this->db->where('quantity >=', $qty);
		$query = $this->db->get();
		return $query->result();
	}
    
   public function add_to_cart($id,$qty,$price,$name,$image,$id1,$attr) {
        $this->db->select('title');
		$this->db->from('attribute_value');
		$this->db->where_in('id', $attr);
		$query = $this->db->get();
		$new_attr='';
		foreach($query->result() as $result){
		    $new_attr .= $result->title.", ";
		}
		$new_attr = rtrim($new_attr,", ");
        $data = array(  'id' => $id,
                        'p_id' => $id1,
                        'qty' => $qty,
                        'attr' => $new_attr,
                        'price' => $price,
                        'name' => $name,
                        'image' => $image
                );
        $this->cart->insert($data);
        
        /* $session_id = md5(session_id());
        
        $this->db->select('*');
		$this->db->from('cart');
		$this->db->where('user_id', $session_id);
		$this->db->where('product_id', $id);
		$query = $this->db->get();
		$result = $query->result();
        
        if(empty($result)){
            $data = array(  'user_id' => $session_id,
                            'product_id' => $id,
                            'qty' => $qty,
                            'date' => date("Y-m-d h:i:sa")
                    );
    		$this->db->insert('cart', $data);
        }
        else{
            foreach($result as $res);
            $newid = $res->id;
            $newqty = $res->qty + $qty;
            $data = array(  'qty' => $newqty,
                            'date' => date("Y-m-d h:i:sa")
                    );
        	$this->db->where('id', $newid);
    		$this->db->update('cart', $data);
        } */
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