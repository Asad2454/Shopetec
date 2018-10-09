<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customers_model extends CI_Model {

	public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }
	
	public function status_update($id,$data) {
		$this->db->where('id', $id);
		$this->db->update('customer',$data);
    }
	
	public function get_customer_detail($id) {
		$this->db->select('*');
		$this->db->from('customer');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_customer_orders($id) {
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->where('user_id',$id);
		$this->db->group_by('order_id');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_customers($key, $limit, $start, $sort_by, $sort_order){
	   	$this->db->select('*');
		$this->db->from('customer');
	   	if(!empty($key)){
	  	  	$this->db->or_where('name', $key);
	   	 	$this->db->or_where('email', $key);
	   	    $this->db->or_where('phone', $key);
	   	    $this->db->or_where('city', $key);
	   	    $this->db->or_where('country', $key);
        }    	
	    if(!empty($limit) || !empty($start)){
	    	$this->db->limit($limit, $start);
	    }
	    $this->db->order_by($sort_by,$sort_order);
		$query = $this->db->get();
	    return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
}
