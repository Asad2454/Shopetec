<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

   

    public function get_stockList(){
        $this->db->select('attribute_product.id as attripid,attribute_product.attribute_id as attributesid,attribute_product.quantity as attripqty,attribute_product.status as attripstatus,products.id as productsid, products.title as productstitle,categories.id as catid,categories.title as catetitle');
        $this->db->from('attribute_product');
        $this->db->join('products', 'products.id = attribute_product.product_id');
	$this->db->join('categories', 'categories.id = products.category');	
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function get_stockattri($id){
        $this->db->select('id,attribute_id');
        $this->db->from('attribute_product');
        $this->db->where('id',$id);	
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
     public function get_stockattrivalue($id){
        $this->db->select('*');
        $this->db->from('attribute_value');
        $this->db->where('id',$id);	
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    
}
/* End of file Products_model.php */
/* Location: ./application/model/Products_model.php */