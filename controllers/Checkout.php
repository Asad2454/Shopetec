<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Checkout extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        if (!$this->session->userdata('is_client_login')) {
            redirect('login');
        }	
    
	
		$this->load->model('home_model');
		$this->load->model('checkout_model');
	
	
		function objectToArray($d) {
			
			if (is_object($d)) {
				$d = get_object_vars($d);
			}
			
			if (is_array($d)) {
				return array_map(__FUNCTION__, $d);
			}
			else {
				return $d;
			}
			
		}		
	}

    public function index() {
	    $data['metas'] = objectToArray($this->home_model->get_metas());
	    $data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
		$data['total'] = $this->cart->total();	
		$data['categories_home3'] = $this->home_model->get_categories_home3();
        $data['banners'] = $this->home_model->get_banners('1','0');
		$data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
		$data['contactus'] = $this->home_model->get_contactus();
		$data['client'] = $this->checkout_model->client_details();
        $this->load->view('checkout', $data);
	}
	
	public function index1() {
	    $data['order'] = $this->checkout_model->get_last_order();
	    if(count($data['order']) > 0){
	        foreach($data['order'] as $order);
	    	    $order_id = $order['order_id']+1;
	    	    $order_id = str_pad($order_id,6,"0",STR_PAD_LEFT);
            }
	    else{
	        $order_id = "000001";
	    }
	   
	    $data2 = array(
	            'order_id' => $order_id,
	            'phone' => $this->input->post('billing_phone'),
	            'address' => $this->input->post('billing_address_1'),
	            'address_2' => $this->input->post('billing_address_2'), 
	            'city' => $this->input->post('billing_city'), 
	            'state' => $this->input->post('billing_state'),
	            'country' => $this->input->post('billing_country'),
	            'postcode' => $this->input->post('billing_postcode')
	       );
	    		
	    $this->checkout_model->insert_address($data2);
	    	       
	    foreach($this->cart->contents() as $cart){
	        $data = array(
	            'order_id' => $order_id,
	            'user_id' => $_SESSION['c_id'],
	            'product_id' => $cart['id'],
	            'amount' => $cart['price'], 
	            'qty' => $cart['qty'], 
	            'status' => "hold",
	            'payment' => 'n',
	            'date' => date("Y-m-d h:i:sa"),
	            'payment_details' => $this->input->post('payment_method'),
	            'ip_address' => $this->input->ip_address(),
	            'total' => $cart['price']*$cart['qty']
	       );
	       $this->checkout_model->insert_order($data);
	       $this->checkout_model->deduct_stock($cart['id'],$cart['qty']);
	    }
	       
	    $this->cart->destroy();
	    $data['metas'] = objectToArray($this->home_model->get_metas());
	    $data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
		$data['total'] = $this->cart->total();	
		$data['categories_home3'] = $this->home_model->get_categories_home3();
        $data['banners'] = $this->home_model->get_banners('1','0');
		$data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
		$data['contactus'] = $this->home_model->get_contactus();
        $this->load->view('order_sucessful', $data);
    }
}