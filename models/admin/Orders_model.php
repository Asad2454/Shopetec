<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Orders_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
     }
     
    public function get_attr($attr) {
		$this->db->select('title');
		$this->db->from('attribute_value');
		$this->db->where_in('id', $attr);
		$query = $this->db->get();
		$new_attr='';
		foreach($query->result() as $result){
		    $new_attr .= $result->title.", ";
		}
		return $new_attr = rtrim($new_attr,", ");
    } 
   
    public function update_orders_status($id,$data) {
	    $this->db->where('order_id', $id);
	    $this->db->update('orders',$data);
    }
    
     public function vieworder($id) {
		$this->db->select('orders.id as oid,payment, orders.user_id as ouid,payment_details,payment_status, products.user_id as opid,ip_address,customer.name as fname,customer.last_name as lname, products.id as pid,order_id,date,orders.status as ostatus');
		$this->db->from('orders');
		$this->db->join('customer','customer.id = orders.user_id');
		$this->db->join('attribute_product','attribute_product.id = orders.product_id');
		$this->db->join('products','products.id = attribute_product.product_id');
		$this->db->where('orders.order_id', $id);
		$this->db->group_by('orders.order_id');
	    $query = $this->db->get();
    	return $query->result_array();
    }
    
    public function vieworder1($id) {
		$this->db->select('products.title as ptitle, orders.qty as oqty,vendors.name as vname,orders.amount as oamt,orders.status as ostatus,orders.total as ottl,attribute_product.attribute_id as apid');
		$this->db->from('orders');
		$this->db->join('customer','customer.id = orders.user_id');
		$this->db->join('attribute_product','attribute_product.id = orders.product_id');
		$this->db->join('products','products.id = attribute_product.product_id');
		$this->db->join('vendors','vendors.id = products.user_id','left');
		$this->db->where('orders.order_id', $id);
		$query = $this->db->get();
    	return $query->result_array();
    }
    
    public function get_orders($key, $limit, $start, $sort_by, $sort_order) {
	    $this->db->select('orders.id as oid, products.id as pid,order_id,date,orders.status as ostatus,customer.name as fname,customer.last_name as lname,vendors.name as vname');
		$this->db->from('orders');
		$this->db->join('customer','customer.id = orders.user_id');
		$this->db->join('attribute_product','attribute_product.id = orders.product_id');
		$this->db->join('products','products.id = attribute_product.product_id');
		$this->db->join('vendors','vendors.id = products.user_id');
		if(!empty($key)){
      	  	$this->db->or_where('order_id', $key);
       	 	$this->db->or_where('date', $key);
       	 	$this->db->or_where('vendors.name', $key);
       	 	$this->db->or_where('customer.name', $key);
       	 	$this->db->or_where('customer.last_name', $key);
        }
    	if(!empty($limit) || !empty($start)){
    		$this->db->limit($limit, $start);
    	}
    	$this->db->group_by('orders.order_id');
    	$this->db->order_by($sort_by,$sort_order);
	    $query = $this->db->get();
    	return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
}
 